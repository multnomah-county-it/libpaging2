<?php

// Loop through brance codes and generate links
echo $twig->render('_about.html.twig', [ 'base_URL' => $config['base_URL'] ]);

?>
