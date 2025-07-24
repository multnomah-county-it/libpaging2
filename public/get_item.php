<?php

declare(strict_types=1);

// Set the search values based on defaults and inputs
$fieldList = $_GET['field_list'] ?? 'library,barcode,copyNumber,itemType,currentLocation,call{callNumber}';
$itemKey = $_GET['item_key'] ?? ''; // Removed the redundant assignment

// Remove non-printing characters from the incoming field list, particularly returns
$fieldList = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $fieldList);

// Connect to ILSWS and get valid search indexes
$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

// Display the form
echo $twig->render('_get_item.html.twig', [
    'base_URL' => $config['base_URL'],
    'item_key' => $itemKey,
    'field_list' => $fieldList,
]);

if (!empty($itemKey) && !empty($fieldList)) {
    try {
        $record = $ilsws->getItem($token, $itemKey, $fieldList);
    } catch (Exception $e) {
        echo $twig->render('_error.html.twig', ['message' => $e->getMessage()]);
    }

    if (!empty($ilsws->error)) {
        echo $twig->render('_error.html.twig', ['message' => $ilsws->error]);
    }

    if ($ilsws->code >= 200 && $ilsws->code < 400) {
        echo $twig->render('_get_record_start.html.twig', []);
        echo $twig->render('_get_item_fields.html.twig', ['record' => $record]);
        echo $twig->render('_get_record_end.html.twig', []);
    }
}
