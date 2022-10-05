<?php

$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

// Get the parameters from the URL
$type = $_GET['type'];
$code = $_GET['code'];
$name = $config['BRANCHES'][$code];

$display_type = ucfirst($type);

$data_file = $config['data_path'] . "/$code" . "_$type.json";
if ( filesize($data_file) > 2 ) {
    $entries = file($data_file);

    echo $twig->render('_list_start.html.twig', [
        'list_count' => count($entries),
        'type' => $display_type, 
        'name' => $name, 
        'today' => $today
        ]);

    foreach ($entries as $line) {
        $entry = json_decode($line, true);
        $entry['author_search'] = urlencode($ilsws->prepare_search($entry['author']));
        $entry['title_search'] = urlencode($ilsws->prepare_search($entry['title']));
        $entry['base_URL'] = $config['base_URL'];

        echo $twig->render('_list.html.twig', $entry);
    }

    echo $twig->render('_list_end.html.twig', []);

} else {

    echo $twig->render('_list_empty.html.twig', ['type' => $type]);
}

?>
