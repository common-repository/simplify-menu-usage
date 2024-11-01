<?php
namespace SimplifyMenuUsage\SettingsLib\Elements\Fields;
use SimplifyMenuUsage\SettingsLib\Elements\Basic_Field;

class Image extends Basic_Field{
    
    protected $template = 'image';
    
    protected function provide_value(){
        $option_value = get_option($this->data['option_name'], 0);
        $post = get_post($option_value);
        $url = '';
        if($post !== NULL && $post->post_type === 'attachment'){
            $url = wp_get_attachment_url($post->ID);
            $this->data['attachment'] = $post->ID;
            $this->data['attachment_title'] = $post->post_title;
            $this->data['url'] = $url;
        }else{
            $this->data['attachment'] = 0;
            $this->data['attachment_title'] = '';
            $this->data['url'] = '';
        }
        $this->data['option_value'] = $option_value;
    }
}
