<?php
if (!defined('ABSPATH')){
    exit;
}
?>
 <div class="simplify-menu-usage content-wrapper">
    <div class="simplify-menu-usage content">
        <div class="simplify-menu-usage handling-case-default">
            <h4><?php _e('Default behaviour', 'simplify-menu-usage'); ?></h4>
            <div class="info-text">
                <?php _e('The default behavior is the standard way how WordPress handles inserting menu items leftwards. '
                    . 'To put it simply, if the next menu item will not become a child element of the element, which is going to be moved left '
                    . 'the behavior is the default one used by WordPress.', 'simplify-menu-usage');
                ?>
            </div>
            <div class="info-image">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'css/images/left-default-behavior.png'); ?>" alt="<?php esc_attr_e('Showing the default behavior of moving/inserting elements downwards.', 'simplify-menu-usage'); ?>"/>
            </div>
        </div>
        <div class="simplify-menu-usage handling-case-same-level-children">
            <h4><?php _e('Take-Children elements behaviour', 'simplify-menu-usage'); ?></h4>
            <div class="info-text">
                <?php _e('The behavior of taking children elements is automatically applied when the next menu item '
                        . 'becomes a child element of the menu item, which is moved/inserted leftwards.', 'simplify-menu-usage');
                ?>
            </div>
            <div class="info-image">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'css/images/left-take-children-behavior.png'); ?>" alt="<?php esc_attr_e('Showing the take-children behavior of moving/inserting elements downwards.', 'simplify-menu-usage'); ?>"/>
            </div>
        </div>
    </div>
</div>