<?php

// Remove non-printing characters, particularly returns
$_GET['field_list'] = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $_GET['field_list']);

// Set the search values based on defaults and inputs
$field_list = ! empty($_GET['field_list']) ? $_GET['field_list'] : 'key,author,title,bib{650_a,856_u},callList{callNumber,itemList{barcode,library}}';
$index = ! empty($_GET['index']) ? $_GET['index'] : 'SUBJECT';
$terms = ! empty($_GET['terms']) ? $_GET['terms'] : '';
$ct = ! empty($_GET['ct']) ? $_GET['ct'] : '50';
$j = ! empty($_GET['j']) ? $_GET['j'] : 'AND';

// Remove accents and punctuation from search terms
$terms = clean_terms($terms);

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
$template= $twig->load('_search.html.twig');
echo $template->render([
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
        $template = $twig->load('_error.html.twig');
        echo $template->render(['message' => $e->getMessage()]);
    } 

    $template = $twig->load('_search_result.html.twig');
    if ( empty($ilsws->error) ) {
        echo $template->render(['message' => count($response) . " records returned"]);
    } else {
        echo $template->render(['message' => $ilsws->error]);
    }

    if ( $ilsws->code >=200 && $ilsws->code < 400 ) {

        foreach ($response as $record) {
            $author_search = clean_terms($record['author']);
            $title_search = clean_terms($record['title']);

            $template = $twig->load('_get_bib_fields.html.twig');
            echo $template->render([
                'record' => $record, 
                'author_search' => urlencode($author_search), 
                'title_search' => urlencode($title_search), 
                'base_URL' => 
                $config['base_URL']
                ]);
        }
    }
} 

/**
 * Removes accents, punctuation, and non-ascii characters to
 * create string acceptable to search engine
 *
 * @param  string $terms
 * @return string $terms
 */

function clean_terms ($terms)
{
    // Change utf8 letters with accents to ascii characters
    setlocale(LC_ALL, "en_US.utf8");
    $terms = iconv("utf-8", "ASCII//TRANSLIT", $terms);

    // Handle time strings
    $terms = preg_replace("/(\d{1,2}):(\d{2})/", "$1 $2", $terms);

    // Replace certain characters with a space
    $terms = preg_replace("/[\.\:\/]/", ' ', $terms);

    // Remove most punctuation and other unwanted characters
    $terms = preg_replace("/[,;?!&+=><%\'\"\|\{\}\(\)\[\]]/", '', $terms);

    // Remove non-printing characters
    $terms = preg_replace('/[^\x20-\x7E]/','', $terms);

    // Replace multiple spaces with a single space
    $terms = preg_replace('/\s+/', ' ', $terms);

    return $terms;
}

?>
