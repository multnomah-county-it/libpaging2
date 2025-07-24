<?php

declare(strict_types=1);

// Get the parameters from the URL
$code = $_GET['code'];
$name = $config['BRANCHES'][$code];

// Render page template for each library
echo $twig->render('_library.html.twig', [
    'base_URL' => $config['base_URL'],
    'code' => $code,
    'name' => $name,
    'today' => $today,
]);
