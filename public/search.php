<?php

// Set the search values based on defaults and inputs
$field_list = 'key,author,title,bib{650_a,856_u},callList{callNumber,itemList{barcode}}';
if ( ! empty($_GET['field_list']) ) {
    $field_list = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $_GET['field_list']);
}
$index = 'SUBJECT';
if ( ! empty($_GET['index']) ) {
    $index = $_GET['index'];
}
$terms = '';
if ( ! empty($_GET['terms']) ) {
    $terms = $_GET['terms'];
}
$ct = '50';
if ( ! empty($_GET['ct']) ) {
    $ct = $_GET['ct'];
}
$j_AND = ' checked';
$j_OR = '';
if ( ! empty($_GET['j']) ) {
    if ( $_GET['j'] == 'AND' ) {
        $j_AND = ' checked';
        $j_OR = '';
    } else {
        $j_AND = '';
        $j_OR = ' checked';
    }
}

// Connect to ILSWS and get valid search indexes
$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();
$indexes = $ilsws->get_catalog_indexes($token);

// Define select statement with user preference selected
$index_select = "<select id=\"index\" name=\"index\" required>\n";
foreach ($indexes as $index_option) {
    if ( $index == $index_option ) {
        $index_select .= "<option value=\"$index_option\" selected>$index_option</option>\n";
    } else {
        $index_select .= "<option value=\"$index_option\">$index_option</option>\n";
    }
}
$index_select .= "</select>\n";

// Display the search form
$template= $twig->load('_search.html.twig');
echo $template->render([
    'index_select' => $index_select, 
    'terms' => $terms, 
    'j_AND' => $j_AND, 
    'j_OR' => $j_OR, 
    'ct' => $ct, 
    'field_list' => $field_list
    ]);

if ( ! empty($_GET['index']) && ! empty($_GET['terms']) && ! empty($_GET['field_list']) ) {

    try {
        $response = $ilsws->search_bib($token, $_GET['index'], $_GET['terms'], ['j' => $_GET['j'], 'ct' => $_GET['ct'], 'includeFields' => $_GET['field_list']]);
    } catch (Exception $e) {
        $template = $twig->load('_error.html.twig');
        echo $template->render(['message' => $e->getMessage()]);
    } 

    if ( empty($ilsws->error) ) {
        $template = $twig->load('_search_result.html.twig');
        $template->render(['message' => count($response) . " records returned"]);
    }

    if ( $ilsws->code >=200 && $ilsws->code < 400 ) {

        foreach ($response as $record) {
            $author_search = preg_replace("/[\[\],;:'?]/", '', $record['author']);
            $title_search = preg_replace("/[\[\],;:'?]/", '', $record['title']);

            $template = $twig->load('_search_result_list.html.twig');
            echo $template->render(['record' => $record, 'author_search' => $author_search, 'title_search' => $title_search, 'base_URL' => $config['base_URL']]);
        }
    }
} 

?>
