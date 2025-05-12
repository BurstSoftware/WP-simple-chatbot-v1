jQuery(document).ready(function($) {
    $('#wpsc-chatbot-send').click(function() {
        let message = $('#wpsc-chatbot-input').val().trim();
        if (!message) return;

        // Display user message
        $('#wpsc-chatbot-messages').append('<div class="wpsc-message wpsc-user">' + message + '</div>');
        $('#wpsc-chatbot-input').val('');

        // Send message to server
        $.ajax({
            url: wpsc_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'wpsc_chatbot_message',
                message: message,
                nonce: wpsc_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#wpsc-chatbot-messages').append('<div class="wpsc-message wpsc-bot">' + response.data + '</div>');
                    `\(('#wpsc-chatbot-messages').scrollTop(\)`('#wpsc-chatbot-messages')[0].scrollHeight);
                } else {
                    $('#wpsc-chatbot-messages').append('<div class="wpsc-message wpsc-bot">Error: ' + response.data + '</div>');
                }
            }
        });
    });

    // Allow pressing Enter to send
    $('#wpsc-chatbot-input').keypress(function(e) {
        if (e.which === 13) {
            $('#wpsc-chatbot-send').click();
        }
    });
});
