<?php
namespace SimplifyMenuUsage;
use SimplifyMenuUsage\Includes\Simplify_Menu_Usage;
use SimplifyMenuUsage\Includes\Simplify_Menu_Usage_Activator;
use SimplifyMenuUsage\Includes\Simplify_Menu_Usage_Deactivator;

/*
 * Plugin Name:       Simplify Menu Usage
 * Plugin URI:        https://wordpress.org/plugins/simplify-menu-usage/
 * Description:       Improves the handling of the menu section in the dashboard. Fast deleting and inserting/moving menu items.
 * Version:           1.0.0
 * Author:            Konstantin Kröpfl
 * Author URI:        https://profiles.wordpress.org/konstk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simplify-menu-usage
 * Domain Path:       /languages
 * Requires at least: 5.0
 * Requires PHP:      7.0
 */

if(!defined('WPINC')){
    die;
}
define('SIMPLIFY_MENU_USAGE_VERSION', '1.0.0');

function activate_simplify_menu_usage($networkwide){
    require_once plugin_dir_path(__FILE__) . 'includes/class-simplify-menu-usage-activator.php';
    global $wpdb;
    if(function_exists('is_multisite') && is_multisite()){
        // check if it is a network activation - if so, run the activation function for each blog id
        if($networkwide) {
            $old_blog = $wpdb->blogid;
            $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach($blogids as $blog_id){
                switch_to_blog($blog_id);
                Simplify_Menu_Usage_Activator::activate();
                restore_current_blog();
            }
            switch_to_blog($old_blog);
            return;
        }
    }
    Simplify_Menu_Usage_Activator::activate();
}

function deactivate_simplify_menu_usage($networkwide) {
    require_once plugin_dir_path(__FILE__) . 'includes/class-simplify-menu-usage-deactivator.php';
    global $wpdb;
    if (function_exists('is_multisite') && is_multisite()) {
        // check if it is a network activation - if so, run the activation function for each blog id
        if ($networkwide) {
            $old_blog = $wpdb->blogid;
            $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach($blogids as $blog_id){
                switch_to_blog($blog_id);
                Simplify_Menu_Usage_Deactivator::deactivate();
                restore_current_blog();
            }
            switch_to_blog($old_blog);
            return;
        }
    }
    Simplify_Menu_Usage_Deactivator::deactivate();
}

register_activation_hook(__FILE__, __NAMESPACE__ . '\activate_simplify_menu_usage');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\deactivate_simplify_menu_usage');
add_action('wpmu_new_blog', __NAMESPACE__ . '\activate_simplify_menu_usage');

function run_simplify_menu_usage(){
    require plugin_dir_path(__FILE__) . 'includes/class-simplify-menu-usage.php';
    $plugin = new Simplify_Menu_Usage();
    $plugin->run();
}
run_simplify_menu_usage();