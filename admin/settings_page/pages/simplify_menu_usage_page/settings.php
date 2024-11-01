<?php
if (!defined('ABSPATH')){
    exit;
}

return array(
    array(
        'option_group' => 'simplify-menu-usage-page',
        'option_name' => 'smu_down_insert',
        'args' => array(
            'sanitize_callback_type' => 'radio-whitelist',
        )
    ),
    array(
        'option_group' => 'simplify-menu-usage-page',
        'option_name' => 'smu_display_menu_description',
        'args' => array(
            'sanitize_callback_type' => 'radio-whitelist',
        )
    )
);