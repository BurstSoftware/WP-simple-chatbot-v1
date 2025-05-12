<?php
/*
Plugin Name: WP Simple Chatbot
Description: A basic chatbot using OpenAI API for WordPress.
Version: 1.0
Author: Your Name
License: GPL2
*/

require_once plugin_dir_path(__FILE__) . 'includes/settings.php';

function wpsc_enqueue_assets() {
    wp_enqueue_style('wpsc-chatbot-css', plugins_url('assets/css/chatbot.css', __FILE__), [], '1.0');
    wp_enqueue_script('wpsc-chatbot-js', plugins_url('assets/js/chatbot.js', __FILE__), ['jquery'], '1.0', true);
    wp_localize_script('wpsc-chatbot-js', 'wpsc_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wpsc_chatbot_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'wpsc_enqueue_assets');

function wpsc_chatbot_shortcode() {
    ob_start();
    ?>
    <div id="wpsc-chatbot">
        <div id="wpsc-chatbot-messages"></div>
        <input type="text" id="wpsc-chatbot-input" placeholder="Type your message..." />
        <button id="wpsc-chatbot-send">Send</button>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('wpsc_chatbot', 'wpsc_chatbot_shortcode');

