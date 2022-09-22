<?php

// Get the parameters from the URL
$type = $_GET['type'];
$code = $_GET['code'];
$name = $config['BRANCHES'][$code];

$display_type = ucfirst($type);

$data_file = $config['data_path'] . "/$code" . "_$type.json";
if ( filesize($data_file) > 2 ) {
    $entries = file($data_file);

    $template = $twig->load('_list_start.html.twig');
    echo $template->render(['type' => $display_type, 'name' => $name, 'today' => $today]);

    foreach ($entries as $line) {
        $entry = json_decode($line, true);
        $entry['author_search'] = urlencode(preg_replace("/[\[\],:;'?]/", '', $entry['author']));
        $entry['title_search'] = urlencode(preg_replace("/[\[\],:;'?]/", '', $entry['title']));
        $entry['base_URL'] = $config['base_URL'];

        $template = $twig->load('_list.html.twig');
        echo $template->render($entry);
    }

    $template = $twig->load('_list_end.html.twig');
    echo $template->render();

} else {

    $template= $twig->load('_list_empty.html.twig');
    echo $template->render(['type' => $type]);
}

?>
