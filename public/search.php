<?php

declare(strict_types=1);

// Set the search values based on defaults and inputs
$fieldList = $_GET['field_list'] ?? 'key,author,title,bib{650_a,856_u},callList{callNumber,itemList{barcode,library}}';
$index = $_GET['index'] ?? 'SUBJECT';
$terms = $_GET['terms'] ?? '';
$ct = $_GET['ct'] ?? '50';
$j = $_GET['j'] ?? 'AND';

// Remove non-printing characters, particularly returns
$fieldList = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $fieldList);

// Check the appropriate radio buttons
$j_AND = '';
$j_OR = '';
if ($j === 'AND') {
    $j_AND = ' checked';
} else {
    $j_OR = ' checked';
}

// Connect to ILSWS and get valid search indexes
$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();
$indexes = $ilsws->getCatalogIndexes($token);

// Remove accents and punctuation from search terms
$terms = $ilsws->prepareSearch($terms);

// Define select statement with user preference selected
$indexSelect = "<select form=\"search\" id=\"index\" name=\"index\" required>\n";
$indexSelect .= "<option value=\"\">Select Index...</option>\n";
foreach ($indexes as $indexOption) {
    if ($index === $indexOption) {
        $indexSelect .= "<option value=\"{$indexOption}\" selected>{$indexOption}</option>\n";
    } else {
        $indexSelect .= "<option value=\"{$indexOption}\">{$indexOption}</option>\n";
    }
}
$indexSelect .= "</select>\n";

// Display the search form
echo $twig->render('_search.html.twig', [
    'base_URL' => $config['base_URL'],
    'index_select' => $indexSelect,
    'terms' => $terms,
    'j_AND' => $j_AND,
    'j_OR' => $j_OR,
    'ct' => $ct,
    'field_list' => $fieldList,
]);

if ($index && $terms && $fieldList) {
    $response = [];
    try {
        $response = $ilsws->searchBib($token, $index, urlencode($terms), ['j' => $j, 'ct' => $ct, 'includeFields' => $fieldList]);
    } catch (Exception $e) {
        echo $twig->render('_error.html.twig', ['message' => $e->getMessage()]);
    }

    if (empty($ilsws->error)) {
        echo $twig->render('_search_result.html.twig', ['message' => count($response) . ' records returned']);
    } else {
        echo $twig->render('_search_result.html.twig', ['message' => $ilsws->error]);
    }

    if ($ilsws->code >= 200 && $ilsws->code < 400) {
        echo $twig->render('_get_record_start.html.twig', []);

        foreach ($response as $record) {
            $authorSearch = $ilsws->prepareSearch($record['author'] ?? ''); // Use null coalescing to prevent undefined index
            $titleSearch = $ilsws->prepareSearch($record['title'] ?? ''); // Use null coalescing to prevent undefined index

            echo $twig->render('_get_bib_fields.html.twig', [
                'base_URL' => $config['base_URL'],
                'record' => $record,
                'author_search' => urlencode($authorSearch),
                'title_search' => urlencode($titleSearch),
            ]);
        }

        echo $twig->render('_get_record_end.html.twig', []);
    }
}
