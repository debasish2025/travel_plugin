/**
 * Quick Actions for Travel Packages
 * - Quick image upload from admin list page
 * - Quick publish/unpublish toggle from admin list page
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        /**
         * Handle upload button click
         */
        $(document).on('click', '.stp-upload-image-btn', function(e) {
            e.preventDefault();

            var $button = $(this);
            var $wrapper = $button.closest('.stp-quick-image-wrapper');
            var postId = $button.data('post-id');
            var $preview = $wrapper.find('.stp-image-preview');
            var $loader = $wrapper.find('.stp-upload-loader');

            // Create WordPress media frame
            var mediaUploader = wp.media({
                title: 'Select or Upload Package Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            // When image is selected
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();

                // Show loader
                $button.hide();
                $loader.show();

                // Send AJAX request to set featured image
                $.ajax({
                    url: stpQuickUpload.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'stp_quick_upload_image',
                        nonce: stpQuickUpload.nonce,
                        post_id: postId,
                        attachment_id: attachment.id
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update preview with new thumbnail
                            $preview.html(response.data.thumbnail);

                            // Update button text
                            $button.html('🔄 Change');

                            // Show success message
                            showSuccessMessage($wrapper, '✅ Image updated!');

                            // Hide loader, show button
                            $loader.hide();
                            $button.show();

                        } else {
                            // Show error
                            showErrorMessage($wrapper, response.data.message || 'Failed to upload');
                            $loader.hide();
                            $button.show();
                        }
                    },
                    error: function() {
                        showErrorMessage($wrapper, 'Network error occurred');
                        $loader.hide();
                        $button.show();
                    }
                });
            });

            // Open media uploader
            mediaUploader.open();
        });

        /**
         * Handle status toggle button click
         */
        $(document).on('click', '.stp-toggle-status-btn', function(e) {
            e.preventDefault();

            var $button = $(this);
            var $wrapper = $button.closest('.stp-quick-status-wrapper');
            var postId = $button.data('post-id');
            var targetStatus = $button.data('target-status');
            var $loader = $wrapper.find('.stp-status-loader');
            var $badge = $wrapper.find('.stp-status-badge');

            // Disable button and show loader
            $button.prop('disabled', true).hide();
            $loader.show();

            // Send AJAX request to toggle status
            $.ajax({
                url: stpQuickUpload.ajaxurl,
                type: 'POST',
                data: {
                    action: 'stp_toggle_status',
                    nonce: stpQuickUpload.nonce,
                    post_id: postId,
                    target_status: targetStatus
                },
                success: function(response) {
                    if (response.success) {
                        // Update badge text and style
                        $badge.text(response.data.status_text);
                        $badge.attr('style', 'display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 8px; ' + response.data.badge_style);

                        // Update button text and classes
                        $button.text(response.data.button_text);
                        $button.removeClass('stp-publish-btn stp-unpublish-btn').addClass(response.data.button_class);
                        $button.data('target-status', response.data.next_target_status);

                        // Show success message
                        showSuccessMessage($wrapper, response.data.message);

                        // Hide loader, show button
                        $loader.hide();
                        $button.prop('disabled', false).show();

                    } else {
                        // Show error
                        showErrorMessage($wrapper, response.data.message || 'Failed to update status');
                        $loader.hide();
                        $button.prop('disabled', false).show();
                    }
                },
                error: function() {
                    showErrorMessage($wrapper, 'Network error occurred');
                    $loader.hide();
                    $button.prop('disabled', false).show();
                }
            });
        });

        /**
         * Show success message
         */
        function showSuccessMessage($wrapper, message) {
            var $message = $('<div class="stp-upload-message stp-success" style="position: absolute; background: #46b450; color: white; padding: 5px 10px; border-radius: 4px; font-size: 11px; margin-top: 5px; white-space: nowrap; animation: fadeInOut 2s ease-in-out;">' + message + '</div>');
            $wrapper.append($message);

            setTimeout(function() {
                $message.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 2000);
        }

        /**
         * Show error message
         */
        function showErrorMessage($wrapper, message) {
            var $message = $('<div class="stp-upload-message stp-error" style="position: absolute; background: #dc3232; color: white; padding: 5px 10px; border-radius: 4px; font-size: 11px; margin-top: 5px; white-space: nowrap; animation: fadeInOut 3s ease-in-out;">' + message + '</div>');
            $wrapper.append($message);

            setTimeout(function() {
                $message.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }

        /**
         * Add CSS animation
         */
        var style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translateY(-10px); }
                15% { opacity: 1; transform: translateY(0); }
                85% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(-10px); }
            }

            .stp-quick-image-wrapper,
            .stp-quick-status-wrapper {
                position: relative;
            }

            .stp-upload-message {
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                z-index: 9999;
            }

            .stp-upload-image-btn:hover {
                background: #0073aa;
                color: white;
                border-color: #0073aa;
            }
        `;
        document.head.appendChild(style);

        // Log initialization
        var imageCount = $('.stp-upload-image-btn').length;
        var statusCount = $('.stp-toggle-status-btn').length;
        console.log('✅ Quick Actions initialized: ' + imageCount + ' image uploads, ' + statusCount + ' status toggles available');
    });

})(jQuery);
