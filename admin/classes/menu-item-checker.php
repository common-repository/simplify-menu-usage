<?php
namespace SimplifyMenuUsage\Core;

class Menu_Item_Checker{
    
    private $menu_id;
    private $menu_items = [];
    
    public function __construct($menu_id){
        $this->menu_id = $menu_id;
        $this->prepare_menu_items();
    }
    
    private function prepare_menu_items(){
        global $wpdb;
   
        $sql = $wpdb->prepare(
            "SELECT pm.meta_value FROM {$wpdb->prefix}postmeta AS pm "
            . "INNER JOIN {$wpdb->prefix}term_relationships AS wtr ON pm.post_id=wtr.object_id "
            . "WHERE pm.meta_key='_menu_item_object_id' AND wtr.term_taxonomy_id=%d", $this->menu_id);
        
        $results = $wpdb->get_results($sql, ARRAY_A);
        if(!empty($results)){
            foreach($results as $result){
                $this->menu_items[] = $result['meta_value'];
            }
        }
    }
    
    public function get_used_menu_items(){
        return $this->menu_items;
    }
    
    public function is_post_in_menu($post_id){
        if(in_array($post_id, $this->menu_items)){
            return TRUE;
        }
        return FALSE;
    }
    
}
