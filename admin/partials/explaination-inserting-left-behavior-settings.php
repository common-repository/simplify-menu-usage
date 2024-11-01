<?php
if (!defined('ABSPATH')){
    exit;
}
?>
<div id="nav-menu-settings-explainations-left" class="menu-simplify-usage outter-content">
    <div class="simplify-menu-usage down-handling">
        <div class="simplify-menu-usage accordion-wrapper">
            <p>
                <?php _e('The behavior when moving/inserting menu elements downwards is explained '
                        . 'below the relevant setting. The modification for leftwards moving/inserting can be '
                        . 'explained as follows. The behavior of moving/inserting menu elements leftwards is the same '
                        . 'as provided by WordPress except when the following menu items become child elements and represent a submenu.','simplify-menu-usage'); ?>
            </p>
            <p class="no-margin"><?php _e('By clicking the info icon you will see the description and '
                    . 'an image illustrating the different behaviors. Clicking again removes the section.', 'simplify-menu-usage'); ?>
                <span class="downwards-insert-description dashicons dashicons-info"></span>
            </p>
            <?php 
                require_once plugin_dir_path(__FILE__) . 'leftwards-inserting-behavior.php';
            ?>
        </div>
    </div>
</div>