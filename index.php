<?php

require_once 'vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;

header("Application-Name: libpaging2");
header("Author: Multnomah County IT");
header("Cache-Control: no-cache");
header("Content-Type: text/html; charset=UTF-8");
header("Content-Security-Policy: default-src 'self'; img-src https://*; style-src https://*.multcolib.org; style-src-elem https://*.multcolib.org; child-src 'none'");
header("Language: en");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Robots: noindex");
header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

// Get the configuration
$config = Yaml::parseFile('config/config.yaml');
$config['template_path'] = $config['base_path'] . '/public/templates';
$config['data_path'] = $config['base_path'] . '/data';
$config['bin_path'] = $config['base_path'] . '/bin';

// Initialize the Twig environment
$loader = new \Twig\Loader\FilesystemLoader($config['template_path']);
$twig = new \Twig\Environment($loader, ['cache' => $config['template_path'] . '/cache']);
/**
 * Replace the line above with the following two lines to turn on debugging
 * $twig = new \Twig\Environment($loader, ['debug' => true, 'cache' => $config['template_path'] . '/cache']);
 * $twig->addExtension(new \Twig\Extension\DebugExtension());
 */

// Variables for the header
$today = date("Y-m-d");
$config['public_url'] = $config['base_URL'] . '/public';
$styles_path = $config['public_url'] . '/assets/css/styles.css';
$favicon_path = $config['public_url'] . '/assets/favicon.ico';
$logo_path = $config['public_url'] . '/assets/logo2016.png';
$logo_link_path = $config['logo_link_path'];
$page_title = 'Multnomah County Library Paging Lists';

// Set the default menu item
if ( empty($_GET['page']) ) {
    $_GET['page'] = 'index_list';
}

// Define the menu
$menu_items[0] = ['page' => 'index_list', 'class' => '', 'link' => '/', 'name' => 'Branch Paging Lists'];
$menu_items[1] = ['page' => 'central', 'class' => '', 'link' => '/index.php?page=central', 'name' => 'Central'];
$menu_items[2] = ['page' => 'search', 'class' => '', 'link' => '/index.php?page=search', 'name' => 'Search Catalog'];
$menu_items[3] = ['page' => 'get_bib', 'class' => '', 'link' => '/index.php?page=get_bib', 'name' => 'Get Bib'];
$menu_items[4] = ['page' => 'get_item', 'class' => '', 'link' => '/index.php?page=get_item', 'name' => 'Get Item'];
$menu_items[5] = ['page' => 'about', 'class' => '', 'link' => '/index.php?page=about', 'name' => 'About'];

for ($i = 0; $i < count($menu_items); $i++) {
    if ( $menu_items[$i]['page'] == $_GET['page'] ) {
        $menu_items[$i]['class'] = 'active';
    } else {
        $menu_items[$i]['class'] = '';
    }
}

// Set active menu from pages that don't get menu items
if ( in_array($_GET['page'], ['list','library']) ) {
    $menu_items[0]['class'] = 'active';
}

// Display the header
echo $twig->render('_header.html.twig', [
    'base_URL' => $config['base_URL'],
    'today' => $today, 
    'styles_path' => $styles_path, 
    'favicon_path' => $favicon_path, 
    'page_title' => $page_title,
    'logo_path' => $logo_path,
    'logo_link_path' => $logo_link_path,
    'menu_items' => $menu_items
    ]);

// Display the page requested or the index_list page
include $config['base_path'] . '/public/' . $_GET['page'] . '.php';

// Display the footer
echo $twig->render('_footer.html.twig', ['today' => $today]);

// EOF
