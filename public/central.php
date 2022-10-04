<?php

use Symfony\Component\Yaml\Yaml;

$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

// Get the parameters from the URL
$type = 'title';
$code = 'CEN';
$name = $config['BRANCHES'][$code];

$display_type = ucfirst($type);

$data_file = $config['data_path'] . "/$code" . "_$type.json";
if ( filesize($data_file) > 2 ) {

    $entries = file($data_file);
    $cen_config = Yaml::parseFile('config/cen_config.yaml');

    // Prepare the buckets to put our data in
    foreach ($cen_config['categories'] as $key => $category) {
        $data[$key] = [];
    }

    // Loop through the sorted data lines
    foreach ($entries as $line) {
        $entry = json_decode($line, true);

        // Loop through each category to see if this entry matches
        $match = 0;
        foreach ($cen_config['categories'] as $key => $category) {

            $match = 0;
            if ( ! empty($category['flags']) && $category['flags'] == $entry['currentLocation'] ) {

                $regex = ! empty($category['regex']) ? $category['regex'] : '';
                $not = ! empty($category['not']) ? $category['not'] : '';

                if ( is_array($category['regex']) && preg_match('/^deweyRange/', $key) ) {
                    $dewey = preg_replace('/^(\d{3})(.*)/', "$1", $entry['callNumber']);
                    if ( $dewey >= $category['regex'][0] && $dewey <= $category['regex'][1] ) {
                        $match = 1;
                    }
                } elseif ( ! is_array($regex) && preg_match("$regex", $entry['callNumber']) ) {
                    $match = 1;
                }
                if ( $not && preg_match("$not", $entry['callNumber']) ) {
                    $match = 0;
                }
                if ( $match ) {
                    // We match, so push the entry into the $data structure from which we'll report
                    array_push($data[$key], $entry);
                    $match = 0;
                }
            }
        }
        if ( ! $match ) {
            // Nothing matched, so put the entry into the "unmatched" category
            array_push($data['unmatched'], $entry);
        }
    }

    // Loop through each list
    foreach ($cen_config['lists'] as $key => $list) {

        $list['today'] = $today;
        $list['type'] = $display_type;

        $template = $twig->load('_central_list_start.html.twig');
        echo $template->render($list);

        foreach ($list['categories'] as $category) {

            $name = $cen_config['categories'][$category]['name'];

            if ( ! empty($data[$category]) ) {

                $template = $twig->load('_central_category_start.html.twig');
                echo $template->render(['name' => $name]);

                foreach ($data[$category] as $entry) {
                    $entry['author_search'] = urlencode($ilsws->prepare_search($entry['author']));
                    $entry['title_search'] = urlencode($ilsws->prepare_search($entry['title']));
                    $entry['base_URL'] = $config['base_URL'];

                    $template = $twig->load('_list.html.twig');
                    echo $template->render($entry);
                }
            }
        }

        $template = $twig->load('_central_list_end.html.twig');
        echo $template->render();
    }

} else {

    $template= $twig->load('_list_empty.html.twig');
    echo $template->render(['type' => $type]);
}

?>
