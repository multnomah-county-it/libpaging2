<?php

use Symfony\Component\Yaml\Yaml;

$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

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

                $regex = $category['regex'];
                $not = $category['not'];

                // Not actually regex, but a range of Dewey numbers
                if ( is_array($category['regex']) && preg_match('/^deweyRange/', $key) ) {
                    $dewey = preg_replace('/^(\d{3})(.*)/', "$1", $entry['callNumber']);
                    if ( $dewey >= $category['regex'][0] && $dewey <= $category['regex'][1] ) {
                        $match = 1;
                    }
                } elseif ( ! is_array($regex) ) {
                    // Check the regex before using it so as to produce a useful error message
                    if ( preg_match("$regex", null) === false ) {
                        error_log("\nInvalid regex in \"regex\": " . $regex . "\n");
                    } elseif ( preg_match("$regex", $entry['callNumber']) ) {
                        $match = 1;
                    }
                }
                if ( ! empty($not) ) {
                    // Check the regex before using it so as to produce a useful error message
                    if ( preg_match("$not", null) === false ) {
                        error_log("\nInvalid regex in \"not\": " . $not . "\n");
                    } elseif ( preg_match("$not", $entry['callNumber']) ) {
                        $match = 0;
                    }
                }
                if ( $match ) {
                    // We match, so push the entry into the $data structure from which we'll report
                    array_push($data[$key], $entry);

                    // Jump out to the outer loop after we find a match
                    continue 2;
                } 
            }
        }
        if ( ! $match ) {
            // Nothing matched, so put the entry into the "unmatched" category
            array_push($data['unmatched'], $entry);
        }
    }

    // Compile list counts
    $list_counts['Total'] = 0;
    foreach ($cen_config['lists'] as $list) {
        $list_counts[$list['name']] = 0;
        foreach ($list['categories'] as $category) {
            $list_counts[$list['name']] += count($data[$category]);
            $list_counts['Total'] += count($data[$category]);
        }
    }

    // Start of table output
    echo $twig->render('_central_counts.html.twig', ['list_counts' => $list_counts]);

    // Loop through each list
    foreach ($cen_config['lists'] as $key => $list) {

        $list['today'] = $today;
        $list['type'] = $display_type;

        // Start of list table
        echo $twig->render('_central_list_start.html.twig', $list);

        foreach ($list['categories'] as $category) {

            $name = $cen_config['categories'][$category]['name'];

            if ( ! empty($data[$category]) ) {

                echo $twig->render('_central_category_start.html.twig', ['name' => $name]);

                foreach ($data[$category] as $entry) {

                    /**
                     * For authors or titles to work as search terms, we must convert UTF-8
                     * characters to ascii and remove Boolean operators punctuation and non-printing 
                     * characters.
                     */
                    $entry['author_search'] = urlencode($ilsws->prepare_search($entry['author']));
                    $entry['title_search'] = urlencode($ilsws->prepare_search($entry['title']));
                    $entry['base_URL'] = $config['base_URL'];

                    echo $twig->render('_list.html.twig', $entry);
                }
            }
        }

        // End of list table
        echo $twig->render('_central_list_end.html.twig', []);
    }

} else {

    // Nothing to report
    echo $twig->render('_list_empty.html.twig', ['type' => $type]);
}

?>
