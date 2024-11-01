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
                <?php _e('Choosing the default behavior is the standard way how WordPress handles inserting menu items downwards. '
                    . 'To put it simply, if the next menu item is on the same level inserting downwards will be on the same level. '
                    . 'If the next menu item has children elements, the item, which is about to be inserted/moved downwards will '
                    . 'become a new children element of the next menu item. It should be familiar as it is the default way of handling '
                    . 'the downwards move. For better visualization, the following image show the behavior.', 'simplify-menu-usage');
                ?>
            </div>
            <div class="info-image">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'css/images/default-behavior.png'); ?>" alt="<?php esc_attr_e('Showing the default behavior of moving/inserting elements downwards.', 'simplify-menu-usage'); ?>"/>
            </div>
        </div>
        <div class="simplify-menu-usage handling-case-same-level">
            <h4><?php _e('Same-Level behaviour', 'simplify-menu-usage'); ?></h4>
            <div class="info-text">
                <?php _e('Choosing the same level behavior is very intuitive. It simply inserts/moves the menu item on the same '
                    . 'level one step downwards. Special case when the next menu item has children elements it will be inserted after '
                    . 'the entire sub-menu with its original level. For better visualization, the following imagenav-menu-settings-explainations show the behavior.', 'simplify-menu-usage');
            ?>
            </div>
            <div class="info-image">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'css/images/same-level-behavior.png'); ?>" alt="<?php esc_attr_e('Showing the same-level behavior of moving/inserting elements downwards.', 'simplify-menu-usage'); ?>"/>
            </div>
        </div>
        <div class="simplify-menu-usage handling-case-same-level-children">
            <h4><?php _e('Take-Children elements behaviour', 'simplify-menu-usage'); ?></h4>
            <div class="info-text">
                <?php _e('Choosing the behavior of taking children elements can be very handy. Especially if you '
                    . 'have very large nested menus, which would require to manually drag and drop every child element '
                    . 'to its new parent element. This is the scenario when the "take-children" behavior should be used '
                    . 'when inserting an menu item downwards. For better visualization, the following image show the behavior.', 'simplify-menu-usage');
                ?>
            </div>
            <div class="info-image">
                <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'css/images/take-children-behavior.png'); ?>" alt="<?php esc_attr_e('Showing the take-children behavior of moving/inserting elements downwards.', 'simplify-menu-usage'); ?>"/>
            </div>
        </div>
    </div>
</div>