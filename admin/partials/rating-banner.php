<?php
if (!defined('ABSPATH')){
    exit;
}
?>
<div class="notice smu-rating-banner">
    <h3><?php _e('Simplify Menu Usage says Thank you!','simplify-menu-usage'); ?></h3>
    <div class="content">
        <p><?php _e('I hope the "Simplify Menu Usage" plugin helps you working more efficient on your site. '
                . 'If you like it, it would be very kind if you rate the plugin.', 'simplify-menu-usage'); ?>
        </p>
        <p class="simplify-menu-usage action-links">
            <a data-nonce="<?php esc_attr_e(wp_create_nonce('smu_dismiss_rating_banner')); ?>" class="smu-dismiss-rating-banner" href="#"><?php _e('Dismiss', 'simplify-menu-usage'); ?></a>
            <a href="https://wordpress.org/support/plugin/simplify-menu-usage/reviews/#new-post" target="_blank">
                <?php _e('Rate Simplify Menu Usage', 'simplify-menu-usage'); ?>
            </a>
            <span class="spinner"></span>
        </p>
    </div>
</div>