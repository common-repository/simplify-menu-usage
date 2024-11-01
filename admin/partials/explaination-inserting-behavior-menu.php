<?php
if (!defined('ABSPATH')){
    exit;
}
?>
<div id="nav-menu-settings-explainations" class="menu-simplify-usage outter-content">
    <h4><?php _e('Settings applied for inserting/moving menu items', 'simplify-menu-usage'); ?></h4>
    <div class="simplify-menu-usage down-handling">
        <a href="#" data-handling="<?php esc_attr_e($inserting_downwards_behavior); ?>" class="toggle-simplify-menu-usage"><span class="dashicons dashicons-arrow-down-alt"></span></a>
        <?php echo sprintf(__('The current behavior for inserting/moving items downwards is: <span class="current-behavior bold">%s</span>', 'simplify-menu-usage'), $inserting_downwards_behavior); ?>
        <?php if($display_menu_description === 'yes'): ?>
            <div class="simplify-menu-usage accordion-wrapper">
                <p><?php echo sprintf(__('By clicking the info icon you will see the description and an image illustrating the different behaviors. Clicking again removes the section. '
                        . 'This is the behavior set on the settings page <a href="%s" target="_blank">Simplify Menu Usage Settings</a>. To modify the current behavior just temporarily you can '
                        . 'click on the downwards arrow, which will change the behavior just temporarily without saving it in the database. ', 'simplify-menu-usage'), admin_url('themes.php?page=simplify-menu-usage-page')); ?>
                        <span class="downwards-insert-description dashicons dashicons-info"></span>
                </p>
                <?php 
                    require_once plugin_dir_path(__FILE__) . 'downwards-inserting-behavior.php';
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>