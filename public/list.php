<?php

declare(strict_types=1);

// No specific 'use' statements needed for this file based on its contents,
// but if Libilsws is in a namespace, it should be included here.

$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

// Get the parameters from the URL
$type = $_GET['type'];
$code = $_GET['code'];
$name = $config['BRANCHES'][$code];

$displayType = ucfirst($type);

$dataFile = $config['data_path'] . "/{$code}_{$type}.json";
if (filesize($dataFile) > 2) {
    $entries = file($dataFile);

    echo $twig->render('_list_start.html.twig', [
        'base_URL' => $config['base_URL'],
        'list_count' => count($entries),
        'type' => $displayType,
        'name' => $name,
        'today' => $today,
    ]);

    foreach ($entries as $line) {
        $entry = json_decode($line, true);
        $entry['base_URL'] = $config['base_URL'];
        $entry['author_search'] = urlencode($ilsws->prepareSearch($entry['author']));
        $entry['title_search'] = urlencode($ilsws->prepareSearch($entry['title']));

        echo $twig->render('_list.html.twig', $entry);
    }

    echo $twig->render('_list_end.html.twig', []);
} else {
    echo $twig->render('_list_empty.html.twig', ['type' => $type]);
}
