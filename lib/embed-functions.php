<?php
/**
* WP Eventbrite Embedded Checkout - Embed Functions
*
* In this file,
* you will find all functions to embed the Eventbrite Checkout form to your WordPress site.
*
* @author 	Hendra Setiawan
* @version 	2.1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpeec_shortcode_functions() {
    // Get the plugin option values
    $eventID = get_option('wpeec-event-id');
    $eventMode = get_option('wpeec-event-form-mode');

    // Button Text
    $wpeecButtonText = get_option('wpeec-button-text') ?: 'Buy Tickets';

    if (empty($eventID)) {
        return 'Error! Please specify the event ID.';
    }

    if (empty($eventMode) || $eventMode == 'embed') {
        return '<div id="eventbrite-widget-container-' . $eventID . '"></div><div style="display:flex; justify-content: flex-end;"><a style="border: 1px solid #e5e7eb; display: inline-block; padding: 3px 10px; border-radius: 5px; font-size: 12px; line-height: 16px; text-decoration: none; background-color: #ffffff; margin-top: 10px; color:#4b5563;" href="https://wpeec.pro/?utm_source=embedded&utm_medium=powered_by" target="_blank">Widget by <strong>wpeec.pro</strong></a></span></div>';
    }

    return '<button id="eventbrite-widget-modal-trigger-' . $eventID . '" type="button">' . $wpeecButtonText . '</button>';
}

add_shortcode('wp-eventbrite-checkout', 'wpeec_shortcode_functions');

function wpeec_external_js() {
    global $post;
    if (has_shortcode($post->post_content, 'wp-eventbrite-checkout')) {
        wp_enqueue_script('wpeec_eb_widgets', 'https://www.eventbrite.com/static/widgets/eb_widgets.js', array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'wpeec_external_js');

function wpeec_js_code() {
    global $post;
    // Get the plugin option values
    $eventID = get_option('wpeec-event-id');
    $eventMode = get_option('wpeec-event-form-mode');

    // Get the embed frame height
    $wpeecFrameHeight = get_option('wpeec-frame-height') ?: 425;

    if (has_shortcode($post->post_content, 'wp-eventbrite-checkout') && $eventID) {
        if (empty($eventMode) || $eventMode == 'embed') {
            ?>
            <script type="text/javascript">
                var OrderCompleteLog = function() {
                    console.log('Order complete!');
                };

                window.EBWidgets.createWidget({
                    // Required
                    widgetType: 'checkout',
                    eventId: '<?php echo $eventID; ?>',
                    iframeContainerId: 'eventbrite-widget-container-<?php echo $eventID; ?>',

                    // Optional
                    iframeContainerHeight: <?php echo $wpeecFrameHeight; ?>,
                    onOrderComplete: OrderCompleteLog
                });
            </script>
            <?php
        } else {
            ?>
            <script type="text/javascript">
                var OrderCompleteLog = function() {
                    console.log('Order complete!');
                };

                window.EBWidgets.createWidget({
                    widgetType: 'checkout',
                    eventId: '<?php echo $eventID; ?>',
                    modal: true,
                    modalTriggerElementId: 'eventbrite-widget-modal-trigger-<?php echo $eventID; ?>',
                    onOrderComplete: OrderCompleteLog
                });
            </script>
            <?php
        }
    }
}

add_action('wp_footer', 'wpeec_js_code', 100);