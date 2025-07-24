<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Set the current directory so we can run this from a cron job more easily
$installPath = $argv[1];
chdir($installPath);

$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

$config = Yaml::parseFile('config/config.yaml');
if (empty($config['BRANCHES'])) {
    die('Could not load configuration!');
}
$config['data_path'] = "{$installPath}/data";
$config['template_path'] = "{$installPath}/public/templates";

$today = date('Y-m-d');

// Prepare for logging
$stats = [];

// Loop through the branches
foreach ($config['BRANCHES'] as $code => $name) {
    // Prepare to count paging records
    $stats[$today][$code] = 0;

    // Get the list for a library
    $list = $ilsws->getLibraryPagingList($token, $code);

    // Sort the list into title and item paging lists
    $titleHolds = [];
    $itemHolds = [];
    foreach ($list as $hold) {
        if (!empty($hold['currentLocation']) && $hold['currentLocation'] !== 'HOLDS') {
            // Increment the count for this branch
            $stats[$today][$code]++;
            if ($hold['holdType'] === 'TITLE') {
                $titleHolds[] = $hold;
            } else {
                $itemHolds[] = $hold;
            }
        }
    }

    // Sort the title list
    $titles = array_column($titleHolds, 'title');
    $authors = array_column($titleHolds, 'author');
    $callNumbers = array_column($titleHolds, 'sortCallNumber');
    $locations = array_column($titleHolds, 'currentLocation');
    array_multisort(
        $locations,
        SORT_ASC,
        SORT_STRING,
        $callNumbers,
        SORT_ASC,
        SORT_STRING,
        $authors,
        SORT_ASC,
        SORT_STRING,
        $titles,
        SORT_ASC,
        SORT_STRING,
        $titleHolds
    );

    // Sort the item (copy) list
    $titles = array_column($itemHolds, 'title');
    $authors = array_column($itemHolds, 'author');
    $callNumbers = array_column($itemHolds, 'sortCallNumber');
    $locations = array_column($itemHolds, 'currentLocation');
    array_multisort(
        $locations,
        SORT_ASC,
        SORT_STRING,
        $callNumbers,
        SORT_ASC,
        SORT_STRING,
        $authors,
        SORT_ASC,
        SORT_STRING,
        $titles,
        SORT_ASC,
        SORT_STRING,
        $itemHolds
    );

    // Define the file names
    $titleFile = $config['data_path'] . "/{$code}_title.json";
    $itemFile = $config['data_path'] . "/{$code}_item.json";

    // Convert the data structures to JSON and save to file
    $fTitle = fopen($titleFile, 'w');
    if ($fTitle === false) {
        die("Could not open file: {$titleFile}");
    }
    for ($i = 0; $i < count($titleHolds); $i++) {
        fwrite($fTitle, json_encode($titleHolds[$i]) . "\n");
    }
    fclose($fTitle);
    chown($titleFile, 'paging');
    chgrp($titleFile, 'www-data');
    chmod($titleFile, 0660);

    $fItem = fopen($itemFile, 'w');
    if ($fItem === false) {
        die("Could not open file: {$itemFile}");
    }
    for ($i = 0; $i < count($itemHolds); $i++) {
        fwrite($fItem, json_encode($itemHolds[$i]) . "\n");
    }
    fclose($fItem);
    chown($itemFile, 'paging');
    chgrp($itemFile, 'www-data');
    chmod($itemFile, 0660);

    // Initialize the Twig environment
    $loader = new FilesystemLoader($config['template_path']);
    $twig = new Environment($loader, ['cache' => $config['template_path'] . '/cache']);

    // Send email using the same HTML templates as the list page
    foreach (['Title', 'Item'] as $type) {
        $dataFile = ($type === 'Title') ? $titleFile : $itemFile;

        if (filesize($dataFile) > 2) {
            // Send email
            $to = $config['BRANCH_EMAILS'][$code];
            $from = $config['email_from'];
            $subject = "{$type} Paging List for {$name} for {$today}";

            // Header for HTML content
            $header = 'Content-type: text/html; charset=UTF-8';

            $entries = file($dataFile);

            $body = $twig->render('_gen_lists_header.html.twig', ['base_URL' => $config['base_URL']]);
            $body .= $twig->render('_list_start.html.twig', [
                'base_URL' => $config['base_URL'],
                'type' => $type,
                'name' => $name,
                'today' => $today,
                'list_count' => $stats[$today][$code],
            ]);

            foreach ($entries as $line) {
                $entry = json_decode($line, true);
                $entry['base_URL'] = $config['base_URL'];
                $entry['author_search'] = urlencode($ilsws->prepareSearch($entry['author']));
                $entry['title_search'] = urlencode($ilsws->prepareSearch($entry['title']));

                $body .= $twig->render('_list.html.twig', $entry);
            }

            $body .= $twig->render('_list_end.html.twig', []);
            $body .= $twig->render('_gen_lists_footer.html.twig', []);

            $fMail = fopen("{$installPath}/bin/email_temp.html", 'w');
            if ($fMail === false) {
                die('Could not open email temporary file');
            }
            fwrite($fMail, $body);
            fclose($fMail);

            // Send the email
            $commandLine = "/usr/bin/mailx -r {$from} -a \"{$header}\" -s \"{$subject}\" {$to} < {$installPath}/bin/email_temp.html";
            system($commandLine, $retval);
        }
    }
}

// Log the statistics
$logFile = $config['log_path'] . '/' . substr($today, 0, 7) . '.log';
$fLog = fopen($logFile, 'a');
if ($fLog === false) {
    die("Could not open file: {$logFile}");
}
fwrite($fLog, json_encode($stats) . "\n");
fclose($fLog);
