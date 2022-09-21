<?php

// Set the search values based on defaults and inputs
$field_list = 'barcode,copyNumber,currentLocation';
if ( ! empty($_GET['field_list']) ) {
    $field_list = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $_GET['field_list']);
}

isset($_GET['item_key']) ? $item_key = $_GET['item_key'] : $item_key = '';

// Connect to ILSWS and get valid search indexes
$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

// Display the form
$template= $twig->load('_get_item.html.twig');
echo $template->render([
    'item_key' => $item_key,
    'field_list' => $field_list
    ]);

if ( ! empty($item_key) && ! empty($field_list) ) {

    try {
        $record = $ilsws->get_item($token, $item_key, $field_list);
    } catch (Exception $e) {
        $template = $twig->load('_error.html.twig');
        echo $template->render(['message' => $e->getMessage()]);
    }

    $template = '_get_item_fields.html.twig';

    if ( $ilsws->code >=200 && $ilsws->code < 400 ) {

        $template = $twig->load($template);
        echo $template->render(['record' => $record]);
    }
} 

?>
