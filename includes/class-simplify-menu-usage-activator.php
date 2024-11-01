<?php
namespace SimplifyMenuUsage\Includes;

class Simplify_Menu_Usage_Activator {

    public static function activate(){
        add_option('smu_down_insert', 'default');
        add_option('smu_is_rating_dismissed', FALSE);
        add_option('smu_display_menu_description', 'yes');
    }
}
