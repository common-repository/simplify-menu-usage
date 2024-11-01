<?php
if (!defined('ABSPATH')){
    exit;
}

return array(
    'section' => array(
        array(
            'id' => 'simplify_menu_usage_info',
            'title' => __('Info', 'simplify-menu-usage'),
            'callback' => '',
            'page' => 'simplify-menu-usage-page',
            'data' => array(
                "description" => __('The Simplify Menu Usage plugin let you work faster '
                    . 'with menus in the backend. The behavior of inserting/moving menu items '
                    . 'is partially modified.', 'simplify-menu-usage'
                ),
                'template' => plugin_dir_path(dirname(dirname(dirname(__FILE__)))) . 'partials/explaination-inserting-left-behavior-settings.php'
            ),
            'keys' => array('title', 'id', 'page')
        ),
        array(
            'id' => 'simplify_menu_usage_general',
            'title' => __('General Settings', 'simplify-menu-usage'),
            'callback' => '',
            'page' => 'simplify-menu-usage-page',
            'data' => array(
                "description" => __('In this section you can define the default behavior of '
                    . 'inserting/moving menu items downwards.', 'simplify-menu-usage'
                )
            ),
            'keys' => array('title', 'id', 'page')
        )
    ),
    'field' => array(
        array(
            'id' => 'smu_display_menu_description',
            'type' => 'radio',
            'title' => __('Display description on menu page', 'simplify-menu-usage'),
            'calback' => '',
            'page' => 'simplify-menu-usage-page',
            'section' => 'simplify_menu_usage_general',
            'option_name' => 'smu_display_menu_description',
            'data' => array(
                'description' => __('This option displays the description of the different '
                    . 'behaviors on the menu edit page in the admin dashboard.', 'simplify-menu-usage'
                )
            ),
            'group' => array(
                0 => array('id' => 'smu_display_menu_description_yes', 'value' => 'yes', 'title' => __('Display description', 'simplify-menu-usage')),
                1 => array('id' => 'smu_display_menu_description_no', 'value' => 'no', 'title' => __('Do not display description', 'simplify-menu-usage')),
                ),
            'is_required' => TRUE,
            'default_value' => 'yes'
        ),
        array(
            'id' => 'smu_down_insert',
            'type' => 'radio',
            'title' => __('Behavior when inserting a menu item downwars', 'simplify-menu-usage'),
            'calback' => '',
            'page' => 'simplify-menu-usage-page',
            'section' => 'simplify_menu_usage_general',
            'option_name' => 'smu_down_insert',
            'data' => array(
                'description' => __('The different options change the behavior of '
                    . 'inserting/moving menu items downwards.', 'simplify-menu-usage'
                ),
                'template' => plugin_dir_path(dirname(dirname(dirname(__FILE__)))) . 'partials/explaination-inserting-down-behavior-settings.php'
            ),
            'group' => array(
                0 => array('id' => 'smu_down_insert_default', 'value' => 'default', 'title' => __('Default inserting', 'simplify-menu-usage')),
                1 => array('id' => 'smu_down_insert_same_level', 'value' => 'same-level', 'title' => __('Insert on same level', 'simplify-menu-usage')),
                2 => array('id' => 'smu_down_insert_take_children', 'value' => 'take-children', 'title' => __('Take children elements', 'simplify-menu-usage')),
                ),
            'is_required' => TRUE,
            'default_value' => 'default'
        )
    )
);