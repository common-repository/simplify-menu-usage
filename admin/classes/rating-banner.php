<?php
namespace SimplifyMenuUsage\Core;
use SimplifyMenuUsage\Core\Screen_Helper;

class Rating_Banner{

    private $file_timestamp;
    const DAYS_PASSED_REQUIREMENT = 5;
    
    
    public function __construct($file_timestamp){
        $this->file_timestamp = $file_timestamp;
    }
    
    private function is_banner_required(){
        $current_timestamp = microtime(true);
        $passed_days = ($current_timestamp - $this->file_timestamp)/86400;
        $is_rating_dismissed = get_option('smu_is_rating_dismissed', FALSE);
        if($passed_days >= self::DAYS_PASSED_REQUIREMENT && !$is_rating_dismissed){
            return TRUE;
        }
        return FALSE;
    }
    
    public function display_banner(){
        $screen_helper = new Screen_Helper();
        if($screen_helper->is_screen_like('nav-menus') && $this->is_banner_required()){
            require_once plugin_dir_path(dirname(__FILE__)) . 'partials/rating-banner.php';
        }
    }
    
    public function dismiss_rating_banner(){
        if(!check_ajax_referer('smu_dismiss_rating_banner', '_wpnonce', FALSE)){
            wp_send_json_error(__('No valid Ajax request', 'simplify-menu-usage'));
        }
        
        $is_updated = update_option('smu_is_rating_dismissed', TRUE);        
        if($is_updated){
            wp_send_json_success(__('Banner has been dismissed', 'simplify-menu-usage'));
        }else{
            wp_send_json_error(__('Something went wrong - try again.', 'simplify-menu-usage'));
        }
        
    }
}
