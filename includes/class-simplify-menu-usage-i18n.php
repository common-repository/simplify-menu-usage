<?php
namespace SimplifyMenuUsage\Includes;

class Simplify_Menu_Usage_i18n{

    public function load_plugin_textdomain(){
        load_plugin_textdomain(
                'simplify-menu-usage',
                false,
                dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
