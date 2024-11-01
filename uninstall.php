<?php
if (!defined('WP_UNINSTALL_PLUGIN')){
    exit;
}

$settings = array(
    'smu_down_insert',
    'smu_display_menu_description',
    'smu_is_rating_dismissed'
);

function uninstall($settings) {
    foreach($settings as $setting){
        delete_option($setting);
    }
}

if (function_exists('is_multisite') && is_multisite()) {
    global $wpdb;
    $old_blog = $wpdb->blogid;
    $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    
    foreach($blogids as $blog_id){
        switch_to_blog($blog_id);
        uninstall($settings);
        restore_current_blog();
    }
}else{
    uninstall($settings);
}