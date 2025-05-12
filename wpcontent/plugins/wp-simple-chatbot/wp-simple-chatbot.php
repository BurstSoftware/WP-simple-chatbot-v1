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

function wpsc_chatbot_message() {
    check_ajax_referer('wpsc_chatbot_nonce', 'nonce');
    $message = sanitize_text_field($_POST['message']);
    $api_key = get_option('wpsc_openai_api_key');

    if (empty($api_key)) {
        wp_send_json_error('API key not configured.');
    }

    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json'
        ],
        'body' => json_encode([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $message]
            ],
            'max_tokens' => 150
        ]),
        'timeout' => 30
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error($response->get_error_message());
    }

    `\(body = json_decode(wp_remote_retrieve_body(\)`response), true);
    if (isset($body['choices'][0]['message']['content'])) {
        wp_send_json_success($body['choices'][0]['message']['content']);
    } else {
        wp_send_json_error('Failed to get response from OpenAI.');
    }
}
add_action('wp_ajax_wpsc_chatbot_message', 'wpsc_chatbot_message');
add_action('wp_ajax_nopriv_wpsc_chatbot_message', 'wpsc_chatbot_message');
