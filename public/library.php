<?php

// Get the parameters from the URL
$code = $_GET['code'];
$name = $config['BRANCHES'][$code];

// Render page template for each library
echo $twig->render('_library.html.twig', [
    'code' => $code, 
    'name' => $name, 
    'today' => $today
    ]);

?>
