<?php

// Remove non-printing characters, particularly returns
$_GET['field_list'] = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $_GET['field_list']);

// Set the search values based on defaults and inputs
$field_list = ! empty($_GET['field_list']) ? $_GET['field_list'] : 'key,author,title,bib{650_a,856_u},callList{callNumber,itemList{barcode,library}}';
$index = ! empty($_GET['index']) ? $_GET['index'] : 'SUBJECT';
$terms = ! empty($_GET['terms']) ? $_GET['terms'] : '';
$ct = ! empty($_GET['ct']) ? $_GET['ct'] : '50';
$j = ! empty($_GET['j']) ? $_GET['j'] : 'AND';

// Remove accents from search terms
setlocale(LC_ALL, "en_US.utf8");
$terms = iconv("utf-8", "ASCII//TRANSLIT//IGNORE", $terms);
$terms = preg_replace('/\?/', '', $terms);

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
            $author_search = preg_replace("/[\[\],;:'?]/", '', $record['author']);
            $title_search = preg_replace("/[\[\],;:'?]/", '', $record['title']);

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

function remove_accents ($str)
{
    static $map = [
        // single letters
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'ą' => 'a',
        'å' => 'a',
        'ā' => 'a',
        'ă' => 'a',
        'ǎ' => 'a',
        'ǻ' => 'a',
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'A',
        'Ą' => 'A',
        'Å' => 'A',
        'Ā' => 'A',
        'Ă' => 'A',
        'Ǎ' => 'A',
        'Ǻ' => 'A',

        'ç' => 'c',
        'ć' => 'c',
        'ĉ' => 'c',
        'ċ' => 'c',
        'č' => 'c',
        'Ç' => 'C',
        'Ć' => 'C',
        'Ĉ' => 'C',
        'Ċ' => 'C',
        'Č' => 'C',

        'ď' => 'd',
        'đ' => 'd',
        'Ð' => 'D',
        'Ď' => 'D',
        'Đ' => 'D',

        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ę' => 'e',
        'ē' => 'e',
        'ĕ' => 'e',
        'ė' => 'e',
        'ě' => 'e',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ę' => 'E',
        'Ē' => 'E',
        'Ĕ' => 'E',
        'Ė' => 'E',
        'Ě' => 'E',

        'ƒ' => 'f',

        'ĝ' => 'g',
        'ğ' => 'g',
        'ġ' => 'g',
        'ģ' => 'g',
        'Ĝ' => 'G',
        'Ğ' => 'G',
        'Ġ' => 'G',
        'Ģ' => 'G',

        'ĥ' => 'h',
        'ħ' => 'h',
        'Ĥ' => 'H',
        'Ħ' => 'H',

        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ĩ' => 'i',
        'ī' => 'i',
        'ĭ' => 'i',
        'į' => 'i',
        'ſ' => 'i',
        'ǐ' => 'i',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ĩ' => 'I',
        'Ī' => 'I',
        'Ĭ' => 'I',
        'Į' => 'I',
        'İ' => 'I',
        'Ǐ' => 'I',

        'ĵ' => 'j',
        'Ĵ' => 'J',

        'ķ' => 'k',
        'Ķ' => 'K',

        'ł' => 'l',
        'ĺ' => 'l',
        'ļ' => 'l',
        'ľ' => 'l',
        'ŀ' => 'l',
        'Ł' => 'L',
        'Ĺ' => 'L',
        'Ļ' => 'L',
        'Ľ' => 'L',
        'Ŀ' => 'L',

        'ñ' => 'n',
        'ń' => 'n',
        'ņ' => 'n',
        'ň' => 'n',
        'ŉ' => 'n',
        'Ñ' => 'N',
        'Ń' => 'N',
        'Ņ' => 'N',
        'Ň' => 'N',

        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'ð' => 'o',
        'ø' => 'o',
        'ō' => 'o',
        'ŏ' => 'o',
        'ő' => 'o',
        'ơ' => 'o',
        'ǒ' => 'o',
        'ǿ' => 'o',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'O',
        'Ø' => 'O',
        'Ō' => 'O',
        'Ŏ' => 'O',
        'Ő' => 'O',
        'Ơ' => 'O',
        'Ǒ' => 'O',
        'Ǿ' => 'O',

        'ŕ' => 'r',
        'ŗ' => 'r',
        'ř' => 'r',
        'Ŕ' => 'R',
        'Ŗ' => 'R',
        'Ř' => 'R',

        'ś' => 's',
        'š' => 's',
        'ŝ' => 's',
        'ş' => 's',
        'Ś' => 'S',
        'Š' => 'S',
        'Ŝ' => 'S',
        'Ş' => 'S',

        'ţ' => 't',
        'ť' => 't',
        'ŧ' => 't',
        'ṭ' => 't',
        'Ţ' => 'T',
        'Ť' => 'T',
        'Ŧ' => 'T',

        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ü' => 'u',
        'ũ' => 'u',
        'ū' => 'u',
        'ŭ' => 'u',
        'ů' => 'u',
        'ű' => 'u',
        'ų' => 'u',
        'ư' => 'u',
        'ǔ' => 'u',
        'ǖ' => 'u',
        'ǘ' => 'u',
        'ǚ' => 'u',
        'ǜ' => 'u',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'U',
        'Ũ' => 'U',
        'Ū' => 'U',
        'Ŭ' => 'U',
        'Ů' => 'U',
        'Ű' => 'U',
        'Ų' => 'U',
        'Ư' => 'U',
        'Ǔ' => 'U',
        'Ǖ' => 'U',
        'Ǘ' => 'U',
        'Ǚ' => 'U',
        'Ǜ' => 'U',


        'ŵ' => 'w',
        'Ŵ' => 'W',

        'ý' => 'y',
        'ÿ' => 'y',
        'ŷ' => 'y',
        'Ý' => 'Y',
        'Ÿ' => 'Y',
        'Ŷ' => 'Y',

        'ż' => 'z',
        'ź' => 'z',
        'ž' => 'z',
        'Ż' => 'Z',
        'Ź' => 'Z',
        'Ž' => 'Z',


        // accentuated ligatures
        'Ǽ' => 'A',
        'ǽ' => 'a',
    ];
    return strtr($str, $map);
}

?>
