<?php
namespace SimplifyMenuUsage\Core;
use \WP_Query;

class Quick_Menu_Search_Controller{
    
    public function ajax_quick_menu_search($request = array()){
        $args = array();
	$type = isset($request['type'] ) ? $request['type'] : '';
	$object_type = isset($request['object_type'] ) ? $request['object_type'] : '';
	$query = isset($request['q'] ) ? $request['q'] : '';
	$response_format = isset($request['response-format']) && in_array($request['response-format'], array('json', 'markup')) ? $request['response-format'] : 'json';
        $menu = isset($request['menu'] ) ? $request['menu'] : '';
        
	if('markup' == $response_format){
            $walker = new Simplify_Menu_Usage_Walker_Nav_Menu_Checklist();
            if(is_numeric($menu)){
                $walker->set_menu_item_checker(new Menu_Item_Checker((int) $menu));
            }
            $args['walker'] = $walker;
	}

	if('get-post-item' == $type) {
            if(post_type_exists($object_type)){
                $this->get_post_item_in_search($args, $request, $response_format);
            }elseif(taxonomy_exists($object_type)){
                $this->get_taxonomy_in_search($args, $request, $response_format);
            }
	} elseif(preg_match('/quick-search-(posttype|taxonomy)-([a-zA-Z_-]*\b)/', $type, $matches)){
            if('posttype' == $matches[1] && get_post_type_object($matches[2])){
                $this->get_post_type_in_quick_search($matches, $query, $args, $response_format);
            }elseif('taxonomy' == $matches[1]){
                $this->get_taxonomy_in_quick_search($matches, $query, $args, $response_format);
            }
	}

    }
    
    private function get_post_type_in_quick_search($matches, $query, $args, $response_format){
        $post_type_obj = $this->wp_nav_menu_meta_box_object(get_post_type_object($matches[2]));
        $args = array_merge(
                $args,
                array(
                    'no_found_rows' => true,
                    'update_post_meta_cache' => false,
                    'update_post_term_cache' => false,
                    'posts_per_page' => 10,
                    'post_type' => $matches[2],
                    's' => $query,
                )
        );
        if(isset($post_type_obj->_default_query)){
            $args = array_merge($args, (array) $post_type_obj->_default_query);
        }
        $search_results_query = new WP_Query($args);
        if(!$search_results_query->have_posts()){
            return;
        }
        while($search_results_query->have_posts()){
            $post = $search_results_query->next_post();
            if ('markup' == $response_format) {
                $var_by_ref = $post->ID;
                echo walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', array(get_post($var_by_ref))), 0, (object) $args);
            }elseif('json' == $response_format){
                $this->output_post_json($post->ID, get_the_title($post->ID), $matches[2]);
                echo "\n";
            }
        }
    }
    
    private function get_taxonomy_in_quick_search($matches, $query, $args, $response_format){
        $terms = get_terms(
                array(
                    'taxonomy' => $matches[2],
                    'name__like' => $query,
                    'number' => 10,
                )
        );
        if(empty($terms) || is_wp_error($terms)){
            return;
        }
        foreach((array) $terms as $term){
                if('markup' == $response_format){
                        echo walk_nav_menu_tree(
                                array_map(
                                        'wp_setup_nav_menu_item', 
                                        array($term)
                                        ), 0, (object) $args);
                }elseif('json' == $response_format){
                    $this->output_post_json($term->term_id, $term->name, $matches[2]);
                    echo "\n";
                }
        }
    }
    
    private function get_post_item_in_search($args, $request, $response_format){
        if(isset($request['ID'])){
            $object_id = (int) $request['ID'];
            if ('markup' == $response_format){
                    echo walk_nav_menu_tree(
                            array_map(
                                    'wp_setup_nav_menu_item',
                                    array(get_post($object_id))
                            ), 0, (object) $args);
            }elseif('json' == $response_format){
                $this->output_post_json($object_id, get_the_title($object_id), get_post_type($object_id));
                echo "\n";
            }
        }
    }
    
    private function get_taxonomy_in_search($args, $request, $response_format){
        if(isset($request['ID'])){
            $object_id = (int) $request['ID'];
            if ('markup' == $response_format){
                    echo walk_nav_menu_tree(
                            array_map(
                                    'wp_setup_nav_menu_item', 
                                    array(get_term($object_id, $object_type)) 
                                    ), 0, (object) $args);
            }elseif('json' == $response_format){
                $post_obj = get_term($object_id, $object_type);
                $this->output_post_json($object_id, $post_obj->name, $object_type);
                echo "\n";
            }
        }
    }
    
    private function output_post_json($id, $title, $type){
        echo wp_json_encode(
            array(
                'ID' => $id,
                'post_title' => $title,
                'post_type' => $type,
            )
        );
    }
    
    private function wp_nav_menu_meta_box_object($object = null){
	if(isset($object->name)){
            if('page' == $object->name){
                $object->_default_query = array(
                    'orderby' => 'menu_order title',
                    'post_status' => 'publish',
                );
                // Posts should show only published items.
            }elseif('post' == $object->name){
                $object->_default_query = array(
                    'post_status' => 'publish',
                );
                // Categories should be in reverse chronological order.
            }elseif('category' == $object->name){
                $object->_default_query = array(
                    'orderby' => 'id',
                    'order'   => 'DESC',
                );
                // Custom post types should show only published items.
            }else{
                $object->_default_query = array(
                    'post_status' => 'publish',
                );
            }
	}
	return $object;
    }
    
}