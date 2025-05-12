<?php
function wpsc_register_settings() {
    add_option('wpsc_openai_api_key', '');
    register_setting('wpsc_options_group', 'wpsc_openai_api_key', 'sanitize_text_field');
}
add_action('admin_init', 'wpsc_register_settings');

function wpsc_options_page() {
    add_options_page(
        'WP Simple Chatbot Settings',
        'Chatbot Settings',
        'manage_options',
        'wp-simple-chatbot',
        'wpsc_options_page_html'
    );
}
add_action('admin_menu', 'wpsc_options_page');

function wpsc_options_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('wpsc_options_group');
            do_settings_sections('wpsc_options_group');
            ?>
            <table class="form-table">
                <tr>
                    <th><label for="wpsc_openai_api_key">OpenAI API Key</label></th>
                    <td>
                        <input type="text" name="wpsc_openai_api_key" value="<?php echo esc_attr(get_option('wpsc_openai_api_key')); ?>" size="50" />
                        <p class="description">Get your API key from <a href="https://platform.openai.com/account/api-keys" target="_blank">OpenAI</a>.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}
