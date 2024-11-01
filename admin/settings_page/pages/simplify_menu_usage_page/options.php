<?php
if (!defined('ABSPATH')){
    exit;
}

return array(
    'page_title' => 'Simplify Menu Usage',
    'menu_title' => __('Simplify Menu Usage Settings', 'simplify-menu-usage'),
    'capability' => 'manage_options',
    'menu_slug' => 'simplify-menu-usage-page',
    'callback' => '',
    'keys' => array('page_title', 'menu_title', 'menu_slug'),
    'is_top_page' => FALSE,
    'parent_slug' => 'themes.php'
);
