(function($) {
    'use strict';

    /**
     * Smart Contact Form - AJAX Handler
     */
    var SmartContactForm = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            $(document).on('submit', '.scf-form', this.handleSubmit.bind(this));
        },

        handleSubmit: function(e) {
            e.preventDefault();

            var $form = $(e.target);
            var $submitBtn = $form.find('.scf-submit-btn');
            var $btnText = $submitBtn.find('.scf-btn-text');
            var $spinner = $submitBtn.find('.scf-spinner');
            var $successMsg = $form.find('.scf-message-success');
            var $errorMsg = $form.find('.scf-message-error');

            // Reset messages
            $successMsg.hide().text('');
            $errorMsg.hide().text('');

            // Get routing data
            var routing = $form.data('routing');
            if (routing) {
                try {
                    routing = atob(routing);
                } catch (e) {
                    routing = '';
                }
            }

            // Disable button and show loading
            $submitBtn.prop('disabled', true).addClass('scf-loading');
            $btnText.css('opacity', '0');
            $spinner.show();

            // Collect form data
            var formData = {
                action: 'scf_submit_form',
                nonce: scf_ajax.nonce,
                scf_name: $form.find('[name="scf_name"]').val(),
                scf_email: $form.find('[name="scf_email"]').val(),
                scf_subject: $form.find('[name="scf_subject"]').val(),
                scf_message: $form.find('[name="scf_message"]').val(),
                scf_department: $form.find('[name="scf_department"]').val() || '',
                scf_website: $form.find('[name="scf_website"]').val() || '',
                scf_routing: routing
            };

            // Send AJAX request
            $.ajax({
                url: scf_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $successMsg.text(response.data.message || $form.data('success')).fadeIn();
                        $form[0].reset();

                        // Scroll to message
                        $('html, body').animate({
                            scrollTop: $successMsg.offset().top - 100
                        }, 300);
                    } else {
                        $errorMsg.text(response.data.message || $form.data('error')).fadeIn();
                    }
                },
                error: function() {
                    $errorMsg.text($form.data('error')).fadeIn();
                },
                complete: function() {
                    // Re-enable button
                    $submitBtn.prop('disabled', false).removeClass('scf-loading');
                    $btnText.css('opacity', '1');
                    $spinner.hide();
                }
            });
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        SmartContactForm.init();
    });

    // Re-initialize for Divi Visual Builder
    $(window).on('load', function() {
        if (typeof window.ET_Builder !== 'undefined') {
            SmartContactForm.init();
        }
    });

})(jQuery);
