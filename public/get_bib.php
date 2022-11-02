<?php

// Remove non-printing characters from the incoming field list, particularly returns
$_GET['field_list'] = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $_GET['field_list']);

// Set the search values based on defaults and inputs
$field_list = ! empty($_GET['field_list']) ? $_GET['field_list'] : 'key,author,title,bib{650_a,856_u},callList{callNumber,itemList{barcode,library}}';
$bib_key = ! empty($_GET['bib_key']) ? $_GET['bib_key'] : '';

// Connect to ILSWS and get valid search indexes
$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

// Display the form
echo $twig->render('_get_bib.html.twig', [
    'base_URL' => $config['base_URL'],
    'bib_key' => $bib_key,
    'field_list' => $field_list
    ]);

if ( ! empty($bib_key) && ! empty($field_list) ) {

    if ( $field_list == 'marc' ) {

        try {
            $record = $ilsws->get_bib_marc($token, $bib_key);
        } catch (Exception $e) {
            echo $twig->render('_error.html.twig', ['message' => $e->getMessage()]);
        } 
        $template = '_get_bib_marc.html.twig';

    } else {

        try {
            $record = $ilsws->get_bib($token, $bib_key, $field_list);
        } catch (Exception $e) {
            echo $twig->render('_error.html.twig', ['message' => $e->getMessage()]);
        } 
        $template = '_get_bib_fields.html.twig';
        $author_search = preg_replace("/[\[\],;:'?]/", '', $record['author']);
        $title_search = preg_replace("/[\[\],;:'?]/", '', $record['title']);
    }

    if ( ! empty($ilsws->error) ) {
        echo $twig->render('_error.html.twig', ['message' => $ilsws->error]);
    }

    if ( $ilsws->code >=200 && $ilsws->code < 400 ) {

        echo $twig->render($template, [
            'base_URL' => $config['base_URL'],
            'record' => $record, 
            'author_search' => $author_search, 
            'title_search' => $title_search 
            ]);
    }
} 

?>
