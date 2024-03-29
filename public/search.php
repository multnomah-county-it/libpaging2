<?php

// Set the search values based on defaults and inputs
$field_list = ! empty($_GET['field_list']) ? $_GET['field_list'] : 'key,author,title,bib{650_a,856_u},callList{callNumber,itemList{barcode,library}}';
$index = ! empty($_GET['index']) ? $_GET['index'] : 'SUBJECT';
$terms = ! empty($_GET['terms']) ? $_GET['terms'] : '';
$ct = ! empty($_GET['ct']) ? $_GET['ct'] : '50';
$j = ! empty($_GET['j']) ? $_GET['j'] : 'AND';

// Remove non-printing characters, particularly returns
$field_list = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $field_list);

// Check the appropriate radio buttons
$j_AND = ' checked';
$j_OR = '';
if ( $j == 'AND' ) {
    $j_AND = ' checked';
    $j_OR = '';
} else {
    $j_AND = '';
    $j_OR = ' checked';
}

// Connect to ILSWS and get valid search indexes
$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();
$indexes = $ilsws->get_catalog_indexes($token);

// Remove accents and punctuation from search terms
$terms = $ilsws->prepare_search($terms);

// Define select statement with user preference selected
$index_select = "<select form=\"search\" id=\"index\" name=\"index\" required>\n";
$index_select .= "<option value=\"\">Select Index...</option>\n";
foreach ($indexes as $index_option) {
    if ( $index == $index_option ) {
        $index_select .= "<option value=\"$index_option\" selected>$index_option</option>\n";
    } else {
        $index_select .= "<option value=\"$index_option\">$index_option</option>\n";
    }
}
$index_select .= "</select>\n";

// Display the search form
echo $twig->render('_search.html.twig', [
    'base_URL' => $config['base_URL'],
    'index_select' => $index_select, 
    'terms' => $terms, 
    'j_AND' => $j_AND, 
    'j_OR' => $j_OR, 
    'ct' => $ct, 
    'field_list' => $field_list
    ]);

if ( $index && $terms && $field_list ) {

    try {
        $response = $ilsws->search_bib($token, $index, urlencode($terms), ['j' => $j, 'ct' => $ct, 'includeFields' => $field_list]);
    } catch (Exception $e) {
        echo $twig->render('_error.html.twig', ['message' => $e->getMessage()]);
    } 

    if ( empty($ilsws->error) ) {
        echo $twig->render('_search_result.html.twig', ['message' => count($response) . " records returned"]);
    } else {
        echo $twig->render('_search_result.html.twig', ['message' => $ilsws->error]);
    }

    if ( $ilsws->code >=200 && $ilsws->code < 400 ) {

        echo $twig->render('_get_record_start.html.twig', []);

        foreach ($response as $record) {
            $author_search = $ilsws->prepare_search($record['author']);
            $title_search = $ilsws->prepare_search($record['title']);


            echo $twig->render('_get_bib_fields.html.twig', [
                'base_URL' => $config['base_URL'],
                'record' => $record, 
                'author_search' => urlencode($author_search), 
                'title_search' => urlencode($title_search)
                ]);

        }

        echo $twig->render('_get_record_end.html.twig', []);
    }
} 

?>
