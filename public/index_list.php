<?php

declare(strict_types=1);

// No specific 'use' statements needed for this file based on its contents,
// but if Libilsws is in a namespace, it should be included here.

$update = '';
if (isset($_GET['update']) && $_GET['update']) {
    // Connect to ILSWS and get valid search indexes
    $ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
    $token = $ilsws->connect();

    // Loop through the branches
    foreach ($config['BRANCHES'] as $code => $name) {
        // Get the list for a library
        $list = $ilsws->getLibraryPagingList($token, $code);

        // Sort the list into title and item paging lists
        $titleHolds = [];
        $itemHolds = [];
        foreach ($list as $hold) {
            if (!empty($hold['currentLocation']) && $hold['currentLocation'] !== 'HOLDS') {
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

        $fItem = fopen($itemFile, 'w');
        if ($fItem === false) {
            die("Could not open file: {$itemFile}");
        }
        for ($i = 0; $i < count($itemHolds); $i++) {
            fwrite($fItem, json_encode($itemHolds[$i]) . "\n");
        }
        fclose($fItem);

        $date = date('Y-m-d H:i:s');
        $update = "Lists updated {$date}";
    }
}

// Loop through branch codes and generate links
echo $twig->render('_index_list.html.twig', [
    'base_URL' => $config['base_URL'],
    'branches' => $config['BRANCHES'],
    'today' => $today,
    'update' => $update,
]);
