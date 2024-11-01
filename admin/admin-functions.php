<?php
/**
 * WP Eventbrite Embedded Checkout - Admin Functions
 *
 * In this file,
 * you will find all functions related to the plugin settings in WP-Admin area.
 *
 * @author  Hendra Setiawan
 * @version 2.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

function wpeec_admin_js($hook)
{
    if ('toplevel_page_eventbrite-embedded-checkout' != $hook) {
        return;
    }
    wp_enqueue_script('sweetalert2', '//cdn.jsdelivr.net/npm/sweetalert2@11', array('jquery'), '1.0.0', true);
    wp_enqueue_script('wpeec_admin_js_file', plugin_dir_url(__FILE__) . 'js/wpeec-admin.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'wpeec_admin_js');

add_action('admin_menu', 'wpeec_admin_menu');
function wpeec_admin_menu()
{
    add_menu_page(__('Eventbrite Form', 'wpeec'), __('Eventbrite Form', 'wpeec'), 'manage_options', 'eventbrite-embedded-checkout', 'wpeec_toplevel_page', 'dashicons-cart', 79);
}

function wpeec_register_settings()
{
    register_setting('wpeec-settings-group', 'wpeec-event-id', 'sanitize_text_field');
    register_setting('wpeec-settings-group', 'wpeec-event-form-mode', 'sanitize_text_field');
    register_setting('wpeec-settings-group', 'wpeec-frame-height', 'absint');
    register_setting('wpeec-settings-group', 'wpeec-button-text', 'sanitize_text_field');
}
add_action('admin_init', 'wpeec_register_settings');

function wpeec_toplevel_page()
{
    // Permission check
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // SSL Check
    if (!is_ssl()) {
        wp_die(__('To use Eventbrite Embedded Checkout, your website has to follow the latest security standards and must serve pages over HTTPS encryption. <a href="https://www.eventbrite.com/support/articleredirect?anum=41024" target="_blank">Learn more</a>'));
    }

    // Get the embed frame height
    $wpeecFrameHeight = get_option('wpeec-frame-height', 425);

    // Button Text
    $wpeecButtonText = get_option('wpeec-button-text', 'Buy Tickets');
    ?>
    <div class="wpwrap">
        <div class="card">
            <a href="https://wpeec.pro/?utm_source=admin_banner" target="_blank" style="text-decoration: none;"><img src="<?php echo plugin_dir_url(__FILE__) . '../img/vipbanner.png'; ?>" style="width: 100%; margin-top: 10px;"></a>
            <h1 style="padding-top: 15px; text-align: center; border-top: dashed 2px #ddd;"><?php _e('WP Eventbrite Embedded Checkout', 'wpeec'); ?></h1>
            <hr />
            <div class="form-wrap">
                <form method="post" action="options.php">
                    <?php settings_fields('wpeec-settings-group'); ?>
                    <?php do_settings_sections('wpeec-settings-group'); ?>
                    <div class="form-field wpeec-event-id-wrap">
                        <h3><?php _e('Set The Event ID', 'wpeec'); ?></h3>
                        <label for="wpeec-event-id" style="font-weight: bold;"><?php _e('Event ID', 'wpeec'); ?></label>
                        <input type="text" style="width: 100%;" value="<?php echo esc_attr(get_option('wpeec-event-id')); ?>" name="wpeec-event-id">
                        <p><?php _e('How to find your event ID? Please check this', 'wpeec'); ?> <a href="https://www.eventbrite.com/platform/docs/events" target="_blank"><?php _e('documentation', 'wpeec'); ?></a>.</p>
                    </div>
                    <div class="form-field wpeec-form-mode-wrap">
                        <h3><?php _e('Choose How Checkout Appears', 'wpeec'); ?></h3>
                        <input type="radio" <?php if (get_option('wpeec-event-form-mode') == 'modal') : ?>checked="checked"<?php endif; ?> value="modal" id="wpeec-event-form-mode-modal" name="wpeec-event-form-mode">
                        <label for="wpeec-event-form-mode-modal" style="display: inline-block;"><?php _e('A button that opens the checkout modal over your content', 'wpeec') ?></label>
                        <br />
                        <input type="radio" <?php if (get_option('wpeec-event-form-mode') == 'embed' || get_option('wpeec-event-form-mode') == '') : ?>checked="checked"<?php endif; ?> value="embed" id="wpeec-event-form-mode-embed" name="wpeec-event-form-mode">
                        <label for="wpeec-event-form-mode-embed" style="display: inline-block;"><?php _e('Embedded on the page with your content', 'wpeec') ?></label>
                        <div class="wpeec-frame-height-wrapper" style="display: none; padding: 15px; margin-top: 10px; border: 1px solid #eee; border-radius: 5px;">
                            <label for="wpeec-frame-height" style="font-weight: bold; display: inline-block;"><?php _e('Height', 'wpeec'); ?> </label>
                            <input type="number" name="wpeec-frame-height" id="wpeec-frame-height" value="<?php echo $wpeecFrameHeight; ?>" style="width: 80px;"> <strong>px</strong>
                        </div>
                        <div class="wpeec-button-text-wrapper" style="display: none; padding: 15px; margin-top: 10px; border: 1px solid #eee; border-radius: 5px;">
                            <label for="wpeec-button-text" style="font-weight: bold; display: inline-block;"><?php _e('Button Text', 'wpeec'); ?> </label>
                            <input type="text" name="wpeec-button-text" id="wpeec-button-text" value="<?php echo esc_attr($wpeecButtonText); ?>" style="width: 200px;">
                        </div>
                    </div>
                    <hr />
                    <h3><?php _e('Shortcode', 'wpeec'); ?></h3>
                    <?php
                    $eid = get_option('wpeec-event-id');
                    if ($eid) {
                        echo '<p>' . __('Copy and paste this shortcode directly into any post or page.', 'wpeec') . '</p>';
                        echo '<textarea style="width: 100%;" readonly="readonly">[wp-eventbrite-checkout]</textarea>';
                    } else {
                        echo '<p style="color: red;">' . __('Problem detected! Please set your Event ID.', 'wpeec') . '</p>';
                    }
                    ?>
                    <?php submit_button(__('Save Settings', 'wpeec')); ?>
                </form>
                <hr />
                <p style="text-align: center;"><?php _e('WP Eventbrite Embedded Checkout. Made with', 'wpeec'); ?> <span class="dashicons dashicons-heart"></span> <?php _e('by', 'wpeec'); ?> <a href="https://hellohendra.com" target="_blank">Hendra</a>.</p>
            </div>
        </div>
    </div>
<?php
}
