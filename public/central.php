<?php

declare(strict_types=1);

use Symfony\Component\Yaml\Yaml;

$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

$type = 'title';
$code = 'CEN';
$name = $config['BRANCHES'][$code];
$displayType = ucfirst($type);

$dataFile = $config['data_path'] . "/$code" . "_$type.json";
if (filesize($dataFile) > 2) {
    $entries = file($dataFile);
    $cenConfig = Yaml::parseFile('config/cen_config.yaml');

    // Prepare the buckets to put our data in
    $data = [];
    foreach ($cenConfig['categories'] as $key => $category) {
        $data[$key] = [];
    }

    // Loop through the sorted data lines
    foreach ($entries as $line) {
        $entry = json_decode($line, true);

        // Loop through each category to see if this entry matches
        $match = 0;
        foreach ($cenConfig['categories'] as $key => $category) {
            $match = 0;
            if (!empty($category['flags']) && $category['flags'] === $entry['currentLocation']) {
                $regex = $category['regex'];
                $not = $category['not'];

                // Not actually regex, but a range of Dewey numbers
                if (is_array($regex)) {
                    $dewey = preg_replace('/^(\d{2,3})(.*)/', '$1', $entry['callNumber']);
                    if ($dewey >= $regex[0] && $dewey <= $regex[1]) {
                        $match = 1;
                    }
                } else {
                    // Check the regex before using it so as to produce a useful error message
                    if (preg_match($regex, '') === false) { // Passing empty string to avoid warning
                        error_log("\nInvalid regex in \"regex\": " . $regex . "\n");
                    } elseif (preg_match($regex, $entry['callNumber'])) {
                        $match = 1;
                    }
                }
                if (!empty($not)) {
                    // Check the regex before using it so as to produce a useful error message
                    if (preg_match($not, '') === false) { // Passing empty string to avoid warning
                        error_log("\nInvalid regex in \"not\": " . $not . "\n");
                    } elseif (preg_match($not, $entry['callNumber'])) {
                        $match = 0;
                    }
                }
                if ($match) {
                    // We match, so push the entry into the $data structure from which we'll report
                    $data[$key][] = $entry;

                    // Jump out to the outer loop after we find a match
                    continue 2;
                }
            }
        }
        if (!$match) {
            // Nothing matched, so put the entry into the "unmatched" category
            $data['unmatched'][] = $entry;
        }
    }

    // Compile list counts
    $listCounts['Total'] = 0;
    foreach ($cenConfig['lists'] as $list) {
        $listCounts[$list['name']] = 0;
        foreach ($list['categories'] as $category) {
            $listCounts[$list['name']] += count($data[$category]);
            $listCounts['Total'] += count($data[$category]);
        }
    }

    // Start of table output
    echo $twig->render('_central_counts.html.twig', ['list_counts' => $listCounts]);

    // Loop through each list
    foreach ($cenConfig['lists'] as $key => $list) {
        $list['today'] = $today;
        $list['type'] = $displayType;

        // Start of list table
        echo $twig->render('_central_list_start.html.twig', $list);

        foreach ($list['categories'] as $category) {
            $name = $cenConfig['categories'][$category]['name'];

            if (!empty($data[$category])) {
                echo $twig->render('_central_category_start.html.twig', ['name' => $name]);

                foreach ($data[$category] as $entry) {
                    /**
                     * For authors or titles to work as search terms, we must convert UTF-8
                     * characters to ascii and remove Boolean operators punctuation and non-printing
                     * characters.
                     */
                    $entry['author_search'] = urlencode($ilsws->prepareSearch($entry['author']));
                    $entry['title_search'] = urlencode($ilsws->prepareSearch($entry['title']));
                    $entry['base_URL'] = $config['base_URL'];

                    echo $twig->render('_central_list.html.twig', $entry);
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
