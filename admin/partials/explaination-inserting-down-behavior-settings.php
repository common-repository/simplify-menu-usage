<?php
if (!defined('ABSPATH')){
    exit;
}
?>
<div id="nav-menu-settings-explainations" class="menu-simplify-usage outter-content">
    <div class="simplify-menu-usage down-handling">
        <div class="simplify-menu-usage accordion-wrapper">
            <p><?php _e('By clicking the info icon you will see the description and '
                    . 'an image illustrating the different behaviors. Clicking again removes the section.', 'simplify-menu-usage'); ?>
                <span class="downwards-insert-description dashicons dashicons-info"></span>
            </p>
            <?php 
                require_once plugin_dir_path(__FILE__) . 'downwards-inserting-behavior.php';
            ?>
        </div>
    </div>
</div>