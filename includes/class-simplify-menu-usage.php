<?php
namespace SimplifyMenuUsage\Includes;
use SimplifyMenuUsage\PublicPlugin\Simplify_Menu_Usage_Public;
use SimplifyMenuUsage\Includes\Simplify_Menu_Usage_i18n;
use SimplifyMenuUsage\Includes\Simplify_Menu_Usage_Loader;
use SimplifyMenuUsage\Admin\Simplify_Menu_Usage_Admin;
use SimplifyMenuUsage\Core\Rating_Banner;
use SimplifyMenuUsage\SettingsLib\Page_Controller;
use SimplifyMenuUsage\SettingsLib\Settings_Bootstrap;

class Simplify_Menu_Usage {

    protected $loader;
    protected $plugin_name;
    protected $version;


    public function __construct(){
        if(defined('SIMPLIFY_MENU_USAGE_VERSION')){
                $this->version = SIMPLIFY_MENU_USAGE_VERSION;
        }else{
                $this->version = '1.0.0';
        }
        $this->plugin_name = 'simplify-menu-usage';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies(){
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-simplify-menu-usage-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-simplify-menu-usage-i18n.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-simplify-menu-usage-admin.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-simplify-menu-usage-public.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/interfaces/configure.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/interfaces/factory.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/interfaces/validator.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/interfaces/attachment-output.php';                            

        require_once ABSPATH . 'wp-admin/includes/class-walker-nav-menu-edit.php';
        require_once ABSPATH . 'wp-admin/includes/class-walker-nav-menu-checklist.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/menu-item-checker.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/simplify-menu-usage-nav-item-walker.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/simplify-menu-usage-walker-nav-menu-checklist.php';
        
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/rating-banner.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/quick-menu-search-controller.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/screen-helper.php';  
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/settings_page/settings-bootstrap.php';

        $this->loader = new Simplify_Menu_Usage_Loader();
    }

    private function set_locale(){
        $plugin_i18n = new Simplify_Menu_Usage_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    private function define_admin_hooks(){                
        Settings_Bootstrap::bootstrap();
        $page_controller = new Page_Controller();
        $this->loader->add_action('admin_menu', $page_controller, 'create_menu_page');
        $this->loader->add_action('admin_init', $page_controller, 'prepare_page_elements');

        $plugin_admin = new Simplify_Menu_Usage_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_filter('wp_edit_nav_menu_walker', $plugin_admin, 'use_custom_nav_item_edit_walker');
        $this->loader->add_filter('wp_ajax_custom_menu_quick_search', $plugin_admin, 'custom_menu_quick_search');      
        
        $rating_banner = new Rating_Banner(filemtime(__FILE__));
        $this->loader->add_action('admin_notices', $rating_banner, 'display_banner');
        $this->loader->add_filter('wp_ajax_smu_dismiss_rating_banner', $rating_banner, 'dismiss_rating_banner');
    }

    private function define_public_hooks(){
        $plugin_public = new Simplify_Menu_Usage_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    }

    public function run(){
        $this->loader->run();
    }

    public function get_plugin_name(){
        return $this->plugin_name;
    }

    public function get_loader(){
        return $this->loader;
    }

    public function get_version(){
        return $this->version;
    }

}
