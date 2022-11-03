<?php

// Set the current directory so we can run this from a cron job more easily
$install_path = $argv[1];
chdir($install_path);

require_once 'vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;

$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

$config = Yaml::parseFile('config/config.yaml');
if ( empty($config['BRANCHES']) ) {
    die("Could not load configuration!");
}
$config['data_path'] = "$install_path/data";
$config['template_path'] = "$install_path/public/templates";

$today = date("Y-m-d");

// Prepare for logging
$stats = [];

// Loop through the branches
foreach ($config['BRANCHES'] as $code => $name) {

    // Prepare to count paging records
    $stats[$today][$code] = 0;

    // Get the list for a library
    $list = $ilsws->get_library_paging_list($token, $code);

    // Sort the list into title and item paging lists
    $title_holds = [];
    $item_holds = [];
    foreach ($list as $hold) {
        if ( ! empty($hold['currentLocation']) ) {
            // Increment the count for this branch
            $stats[$today][$code]++;
            if ( $hold['holdType'] == 'TITLE' ) {
                array_push($title_holds, $hold);
            } else {
                array_push($item_holds, $hold);
            }
        }
    }

    // Sort the title list
    $titles = array_column($title_holds, 'title');
    $authors = array_column($title_holds, 'author');
    $call_numbers = array_column($title_holds, 'sortCallNumber');
    $locations = array_column($title_holds, 'currentLocation');
    array_multisort($locations, SORT_ASC, SORT_STRING, $call_numbers, SORT_ASC, SORT_STRING, $authors, SORT_ASC, SORT_STRING, $titles, SORT_ASC, SORT_STRING, $title_holds);

    // Sort the item (copy) list
    $titles = array_column($item_holds, 'title');
    $authors = array_column($item_holds, 'author');
    $call_numbers = array_column($item_holds, 'sortCallNumber');
    $locations = array_column($item_holds, 'currentLocation');
    array_multisort($locations, SORT_ASC, SORT_STRING, $call_numbers, SORT_ASC, SORT_STRING, $authors, SORT_ASC, SORT_STRING, $titles, SORT_ASC, SORT_STRING, $item_holds);

    // Define the file names
    $title_file = $config['data_path'] . "/$code" . '_title.json';
    $item_file = $config['data_path'] . "/$code" . '_item.json';

    // Convert the data structures to JSON and save to file
    $f_title = fopen($title_file, "w") or die("Could not open file: $title_file");
    for ($i = 0; $i < count($title_holds); $i++) {
        fwrite($f_title, json_encode($title_holds[$i]) . "\n");
    }
    fclose($f_title);

    $f_item = fopen($item_file, "w") or die("Could not open file: $item_file");
    for ($i = 0; $i < count($item_holds); $i++) {
        fwrite($f_item, json_encode($item_holds[$i]) . "\n");
    }
    fclose($f_item);

    // Initialize the Twig environment
    $loader = new \Twig\Loader\FilesystemLoader($config['template_path']);
    $twig = new \Twig\Environment($loader, ['cache' => $config['template_path'] . '/cache']);

    // Send email using the same HTML templates as the list page
    foreach (['Title','Item'] as $type) {

        if ( $type == 'Title' ) {
            $data_file = $title_file;
        } else {
            $data_file = $item_file;
        }

        if ( filesize($data_file) > 2 ) {

            // Send email
            // $to = $config['BRANCH_EMAILS'][$code];
            $to = 'john.houser@multco.us';
            $from = $config['EMAIL_FROM'];
            $subject = "$type Paging List for $name for $today";

            // Header for HTML content
            $header = 'Content-type:text/html;charset=UTF-8';

            $entries = file($data_file);

            $body = $twig->render('_gen_lists_header.html.twig', ['base_URL' => $config['base_URL']]);
            $body .= $twig->render('_list_start.html.twig', ['base_URL' => $config['base_URL'], 'type' => $type, 'name' => $name, 'today' => $today]);

            foreach ($entries as $line) {
                $entry = json_decode($line, true);
                $entry['base_URL'] = $config['base_URL'];
                $entry['author_search'] = urlencode($ilsws->prepare_search($entry['author']));
                $entry['title_search'] = urlencode($ilsws->prepare_search($entry['title']));

                $body .= $twig->render('_list.html.twig');
            }

            $body .= $twig->render('_list_end.html.twig', []);
            $body .= $twig->render('_gen_lists_footer.html.twig', []);

            $f_mail = fopen("$install_path/bin/email_temp.html", "w") or die("Could not open email temporary file");
            fwrite($f_mail, $body) or die("Could not write to email temporary file");
            fclose($f_mail);

            // Send the email
            $command_line = "/usr/bin/mailx -r $from -a \"$header\" -s \"$subject\" $to < $install_path/bin/email_temp.html";
            system($command_line, $retval);
        }
    }
}

// Log the statistics
$log_file = $config['log_path'] . '/' . substr($today, 0, 7) . '.log';
$f_log = fopen($log_file, 'a') or die("Could not open file: $log_file");
fwrite($f_log, json_encode($stats) . "\n") or die("Could not write to file: $log_file");
fclose($f_log);

// EOF
