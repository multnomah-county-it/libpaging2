<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Main application entry point for libpaging2.
 * Handles configuration loading, Twig environment setup,
 * header/footer rendering, and page routing.
 *
 * @author Multnomah County IT
 */

// Set application headers
header('Application-Name: libpaging2');
header('Author: Multnomah County IT');
header('Cache-Control: no-cache');
header('Content-Type: text/html; charset=UTF-8');
header("Content-Security-Policy: default-src 'self'; img-src https://*; style-src https://*.multcolib.org; style-src-elem https://*.multcolib.org; child-src 'none'");
header('Language: en');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Robots: noindex');
header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

// Get the configuration
$config = Yaml::parseFile('config/config.yaml');
$config['template_path'] = $config['base_path'] . '/public/templates';
$config['data_path'] = $config['base_path'] . '/data';
$config['bin_path'] = $config['base_path'] . '/bin';

// Initialize the Twig environment
$loader = new FilesystemLoader($config['template_path']);
$twig = new Environment($loader, ['cache' => $config['template_path'] . '/cache']);
/**
 * Replace the line above with the following two lines to turn on debugging
 * $twig = new \Twig\Environment($loader, ['debug' => true, 'cache' => $config['template_path'] . '/cache']);
 * $twig->addExtension(new \Twig\Extension\DebugExtension());
 */

// Variables for the header
$today = date('Y-m-d');
$config['public_url'] = $config['base_URL'] . '/public';
$stylesPath = $config['public_url'] . '/assets/css/styles.css';
$faviconPath = $config['public_url'] . '/assets/favicon.ico';
$logoPath = $config['public_url'] . '/assets/logo2016.png';
$logoLinkPath = $config['logo_link_path'];
$pageTitle = 'Multnomah County Library Paging Lists';

// Set the default menu item
if (empty($_GET['page'])) {
    $_GET['page'] = 'index_list';
}

// Define the menu
$menuItems = [
    ['page' => 'index_list', 'class' => '', 'link' => '/', 'name' => 'Branch Paging Lists'],
    ['page' => 'central', 'class' => '', 'link' => '/index.php?page=central', 'name' => 'Central'],
    ['page' => 'search', 'class' => '', 'link' => '/index.php?page=search', 'name' => 'Search Catalog'],
    ['page' => 'get_bib', 'class' => '', 'link' => '/index.php?page=get_bib', 'name' => 'Get Bib'],
    ['page' => 'get_item', 'class' => '', 'link' => '/index.php?page=get_item', 'name' => 'Get Item'],
    ['page' => 'about', 'class' => '', 'link' => '/index.php?page=about', 'name' => 'About'],
];

for ($i = 0; $i < count($menuItems); $i++) {
    if ($menuItems[$i]['page'] === $_GET['page']) {
        $menuItems[$i]['class'] = 'active';
    } else {
        $menuItems[$i]['class'] = '';
    }
}

// Set active menu from pages that don't get menu items
if (in_array($_GET['page'], ['list', 'library'], true)) {
    $menuItems[0]['class'] = 'active';
}

// Display the header
echo $twig->render('_header.html.twig', [
    'base_URL' => $config['base_URL'],
    'today' => $today,
    'styles_path' => $stylesPath,
    'favicon_path' => $faviconPath,
    'page_title' => $pageTitle,
    'logo_path' => $logoPath,
    'logo_link_path' => $logoLinkPath,
    'menu_items' => $menuItems,
]);

// Display the page requested or the index_list page
include $config['base_path'] . '/public/' . $_GET['page'] . '.php';

// Display the footer
echo $twig->render('_footer.html.twig', ['today' => $today]);
