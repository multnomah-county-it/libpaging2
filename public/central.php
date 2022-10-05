<?php

use Symfony\Component\Yaml\Yaml;

$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

$type = 'title';
$code = 'CEN';
$name = $config['BRANCHES'][$code];
$display_type = ucfirst($type);
$cat_counts = [];

$data_file = $config['data_path'] . "/$code" . "_$type.json";
if ( filesize($data_file) > 2 ) {

    $entries = file($data_file);
    $cen_config = Yaml::parseFile('config/cen_config.yaml');

    // Prepare the buckets to put our data in
    foreach ($cen_config['categories'] as $key => $category) {
        $data[$key] = [];
        $cat_counts[$category['name']] = 0;
    }

    // Loop through the sorted data lines
    foreach ($entries as $line) {
        $entry = json_decode($line, true);

        // Loop through each category to see if this entry matches
        $match = 0;
        foreach ($cen_config['categories'] as $key => $category) {

            $match = 0;
            if ( ! empty($category['flags']) && $category['flags'] == $entry['currentLocation'] ) {

                $regex = $category['regex'];
                $not = $category['not'];

                if ( is_array($category['regex']) && preg_match('/^deweyRange/', $key) ) {
                    $dewey = preg_replace('/^(\d{3})(.*)/', "$1", $entry['callNumber']);
                    if ( $dewey >= $category['regex'][0] && $dewey <= $category['regex'][1] ) {
                        $match = 1;
                    }
                } elseif ( ! is_array($regex) ) {
                    if ( preg_match("$regex", null) === false ) {
                        error_log("\nInvalid regex in \"regex\": " . $regex . "\n");
                    } elseif ( preg_match("$regex", $entry['callNumber']) ) {
                        $match = 1;
                    }
                }
                if ( ! empty($not) ) {
                    if ( preg_match("$not", null) === false ) {
                        error_log("\nInvalid regex in \"not\": " . $not . "\n");
                    } elseif ( preg_match("$not", $entry['callNumber']) ) {
                        $match = 0;
                    }
                }
                if ( $match ) {
                    // We match, so push the entry into the $data structure from which we'll report
                    array_push($data[$key], $entry);
                    $cat_counts[$category['name']]++;

                    continue 2;
                } 
            }
        }
        if ( ! $match ) {
            // Nothing matched, so put the entry into the "unmatched" category
            array_push($data['unmatched'], $entry);
            $cat_counts[$cen_config['categories']['unmatched']['name']]++;
        }
    }

    $list_counts['Total'] = 0;
    foreach ($cen_config['lists'] as $list) {
        $list_counts[$list['name']] = 0;
        foreach ($list['categories'] as $category) {
            $list_counts[$list['name']] += $cat_counts[$cen_config['categories'][$category]['name']];
            $list_counts['Total'] += $cat_counts[$cen_config['categories'][$category]['name']];
        }
    }

    echo $twig->render('_central_counts.html.twig', ['list_counts' => $list_counts]);

    // Loop through each list
    foreach ($cen_config['lists'] as $key => $list) {

        $list['today'] = $today;
        $list['type'] = $display_type;

        echo $twig->render('_central_list_start.html.twig', $list);

        foreach ($list['categories'] as $category) {

            $name = $cen_config['categories'][$category]['name'];

            if ( ! empty($data[$category]) ) {

                echo $twig->render('_central_category_start.html.twig', ['name' => $name]);

                foreach ($data[$category] as $entry) {
                    $entry['author_search'] = urlencode($ilsws->prepare_search($entry['author']));
                    $entry['title_search'] = urlencode($ilsws->prepare_search($entry['title']));
                    $entry['base_URL'] = $config['base_URL'];

                    echo $twig->render('_list.html.twig', $entry);
                }
            }
        }

        echo $twig->render('_central_list_end.html.twig', []);
    }

} else {

    echo $twig->render('_list_empty.html.twig', ['type' => $type]);
}

?>
