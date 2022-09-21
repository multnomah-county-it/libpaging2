<?php

// Get the parameters from the URL
$code = $_GET['code'];
$name = $config['BRANCHES'][$code];

// Render page template for each library
$template = $twig->load('_library.html.twig');
echo $template->render(['code' => $code, 'name' => $name, 'today' => $today]);

?>
