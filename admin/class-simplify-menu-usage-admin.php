<?php
namespace SimplifyMenuUsage\Admin;
use SimplifyMenuUsage\Core\Quick_Menu_Search_Controller;
use SimplifyMenuUsage\Core\Menu_Item_Checker;
use SimplifyMenuUsage\Core\Screen_Helper;

class Simplify_Menu_Usage_Admin{

    private $plugin_name;
    private $version;


    public function __construct($plugin_name, $version){
            $this->plugin_name = $plugin_name;
            $this->version = $version;
    }

    public function use_custom_nav_item_edit_walker(){
        return 'SimplifyMenuUsage\Core\Simplify_Menu_Usage_Nav_Item_Walker';
    }


    public function custom_menu_quick_search(){
        if(!current_user_can('edit_theme_options')){
            wp_die(-1);
        }

        $quick_menu_search_controller = new Quick_Menu_Search_Controller();
        $quick_menu_search_controller->ajax_quick_menu_search($_POST);
        wp_die();
    }

    public function enqueue_styles(){
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/simplify-menu-usage-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts($hook){
        wp_register_script('simplify-menu-usage-setting', plugin_dir_url(__FILE__) . 'js/simplify-menu-usage-settings.js', array('jquery'));
        wp_enqueue_script('simplify-menu-usage-setting');

        if($hook === 'nav-menus.php'){
            global $nav_menu_selected_id;
            $menu_item_checker = new Menu_Item_Checker($nav_menu_selected_id);
            $menu_items = $menu_item_checker->get_used_menu_items();
            $saved_behavior = get_option('smu_down_insert', 'default');
            $params = array(
                'menu_items' => $menu_items,
                'insert_downwards_behavior' => $saved_behavior
           );

            wp_register_script('simplify-menu-usage-admin', plugin_dir_url(__FILE__) . 'js/simplify-menu-usage-admin.js', array('jquery', 'wp-i18n'));
            wp_set_script_translations('simplify-menu-usage-admin', 'simplify-menu-usage');
            wp_localize_script('simplify-menu-usage-admin', 'obj', $params);
            wp_enqueue_script('simplify-menu-usage-admin');           
        }
    }

}
