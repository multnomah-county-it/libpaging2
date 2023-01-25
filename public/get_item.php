<?php

// Remove non-printing characters from the incoming field list, particularly returns
$_GET['field_list'] = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $_GET['field_list']);

// Set the search values based on defaults and inputs
$field_list = ! empty($_GET['field_list']) ? $_GET['field_list'] : 'library,barcode,copyNumber,itemType,currentLocation';
$item_key = ! empty($_GET['item_key']) ? $_GET['item_key'] : $item_key = '';

// Connect to ILSWS and get valid search indexes
$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

// Display the form
echo $twig->render('_get_item.html.twig', [
    'base_URL' => $config['base_URL'],
    'item_key' => $item_key,
    'field_list' => $field_list
    ]);

if ( ! empty($item_key) && ! empty($field_list) ) {

    try {
        $record = $ilsws->get_item($token, $item_key, $field_list);
    } catch (Exception $e) {
        echo $twig->render('_error.html.twig', ['message' => $e->getMessage()]);
    }

    if ( ! empty($ilsws->error) ) {
        echo $twig->render('_error.html.twig', ['message' => $ilsws->error]);
    }

    if ( $ilsws->code >= 200 && $ilsws->code < 400 ) {
        echo $twig->render('_get_record_start.html.twig', []);
        echo $twig->render('_get_item_fields.html.twig', ['record' => $record]);
        echo $twig->render('_get_record_end.html.twig', []);
    }
} 

?>
