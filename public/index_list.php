<?php

$update = '';
if ( isset($_GET['update']) && $_GET['update'] ) {

    // Connect to ILSWS and get valid search indexes
    $ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
    $token = $ilsws->connect();

    // Loop through the branches
    foreach ($config['BRANCHES'] as $code => $name) {

        // Get the list for a library
        $list = $ilsws->get_library_paging_list($token, $code);

        // Sort the list into title and item paging lists
        $title_holds = [];
        $item_holds = [];
        foreach ($list as $hold) {
            if ( ! empty($hold['currentLocation']) ) {
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

        $date = date("Y-m-d H:i:s");
        $update = "Lists updated $date";
    }
}

// Loop through brance codes and generate links
$template = $twig->load('_index_list.html.twig');
echo $template->render(['branches' => $config['BRANCHES'], 'today' => $today, 'update' => $update]);

?>
