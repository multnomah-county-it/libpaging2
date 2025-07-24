<?php

declare(strict_types=1);

// Set the search values based on defaults and inputs
$fieldList = $_GET['field_list'] ?? 'key,author,title,bib{650_a,856_u},callList{callNumber,itemList{barcode,library}}';
$bibKey = $_GET['bib_key'] ?? '';

// Remove non-printing characters from the incoming field list, particularly returns
$fieldList = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $fieldList);

// Connect to ILSWS and get valid search indexes
$ilsws = new Libilsws\Libilsws('config/libilsws.yaml');
$token = $ilsws->connect();

// Display the form
echo $twig->render('_get_bib.html.twig', [
    'base_URL' => $config['base_URL'],
    'bib_key' => $bibKey,
    'field_list' => $fieldList,
]);

if (!empty($bibKey) && !empty($fieldList)) {
    $record = [];
    $template = '';
    $authorSearch = '';
    $titleSearch = '';

    if ($fieldList === 'marc') {
        try {
            $record = $ilsws->getBibMarc($token, $bibKey);
        } catch (Exception $e) {
            echo $twig->render('_error.html.twig', ['message' => $e->getMessage()]);
        }
        $template = '_get_bib_marc.html.twig';
    } else {
        try {
            $record = $ilsws->getBib($token, $bibKey, $fieldList);
        } catch (Exception $e) {
            echo $twig->render('_error.html.twig', ['message' => $e->getMessage()]);
        }
        $template = '_get_bib_fields.html.twig';
        if (isset($record['author'])) {
            $authorSearch = urlencode($ilsws->prepareSearch($record['author']));
        }
        if (isset($record['title'])) {
            $titleSearch = urlencode($ilsws->prepareSearch($record['title']));
        }
    }

    if (!empty($ilsws->error)) {
        echo $twig->render('_error.html.twig', ['message' => $ilsws->error]);
    }

    if ($ilsws->code >= 200 && $ilsws->code < 400) {
        echo $twig->render('_get_record_start.html.twig', []);

        echo $twig->render($template, [
            'base_URL' => $config['base_URL'],
            'record' => $record,
            'author_search' => $authorSearch,
            'title_search' => $titleSearch,
        ]);

        echo $twig->render('_get_record_end.html.twig', []);
    }
}
