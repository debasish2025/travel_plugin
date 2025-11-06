<?php
/**
 * Enhanced Meta Boxes with Rich Text Editors
 * 
 * Adds visual editors (WYSIWYG) for itinerary formatting
 * 
 * SAFE: Works alongside existing data, doesn't delete anything
 */

// Add enhanced itinerary meta box
add_action('add_meta_boxes', 'stp_add_enhanced_itinerary_metabox', 20);

function stp_add_enhanced_itinerary_metabox() {
    add_meta_box(
        'package_itinerary_enhanced',
        '📅 Day by Day Itinerary (Rich Text)',
        'stp_render_enhanced_itinerary',
        'travel_package',
        'normal',
        'high'
    );
}

function stp_render_enhanced_itinerary($post) {
    wp_nonce_field('package_itinerary_enhanced_nonce', 'package_itinerary_enhanced_nonce');

    // Get existing itinerary
    $itinerary = get_post_meta($post->ID, '_itinerary', true);
    if (!is_array($itinerary)) $itinerary = array();

    // If empty, add one default day
    if (empty($itinerary)) {
        $itinerary = array(
            array('title' => '', 'activities' => '')
        );
    }

    ?>
    <div class="stp-modern-admin-wrapper">
        <div class="stp-admin-header">
            <div class="stp-header-content">
                <h3>✈️ Day by Day Itinerary Builder</h3>
                <p>Create a beautiful timeline itinerary for your travel package</p>
            </div>
            <button type="button" id="add-day-enhanced" class="stp-btn-primary">
                <span class="dashicons dashicons-plus"></span> Add New Day
            </button>
        </div>

        <div id="itinerary-container-enhanced" class="stp-accordion-container">
            <?php foreach ($itinerary as $i => $day): ?>
                <div class="stp-accordion-item" data-day="<?php echo $i; ?>">
                    <div class="stp-accordion-header">
                        <div class="stp-day-indicator">
                            <span class="stp-day-num"><?php echo $i + 1; ?></span>
                        </div>
                        <div class="stp-day-title-preview">
                            <strong>Day <?php echo $i + 1; ?></strong>
                            <span class="stp-title-text"><?php echo esc_html($day['title']) ?: 'Click to edit'; ?></span>
                        </div>
                        <div class="stp-accordion-actions">
                            <button type="button" class="stp-btn-toggle">
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                            <button type="button" class="stp-btn-remove remove-day-enhanced">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>

                    <div class="stp-accordion-body">
                        <div class="stp-form-group">
                            <label class="stp-label">
                                <span class="stp-label-icon">📝</span> Day Title
                            </label>
                            <input type="text"
                                   name="itinerary[<?php echo $i; ?>][title]"
                                   value="<?php echo esc_attr($day['title']); ?>"
                                   class="stp-input-title"
                                   placeholder="e.g., Arrival & City Exploration">
                        </div>

                        <div class="stp-form-group">
                            <label class="stp-label">
                                <span class="stp-label-icon">📋</span> Activities & Details
                                <span class="stp-label-hint">Use Ctrl+B for bold, Ctrl+I for italic</span>
                            </label>
                            <?php
                            $editor_id = 'itinerary_activities_' . $i;
                            $content = isset($day['activities']) ? $day['activities'] : '';

                            // Convert old pipe format to list
                            if (strpos($content, '|') !== false && strpos($content, '<') === false) {
                                $activities = explode('|', $content);
                                $content = '<ul>';
                                foreach ($activities as $activity) {
                                    $activity = trim($activity);
                                    if (!empty($activity)) {
                                        $content .= '<li>' . esc_html($activity) . '</li>';
                                    }
                                }
                                $content .= '</ul>';
                            }

                            $editor_settings = array(
                                'textarea_name' => 'itinerary[' . $i . '][activities]',
                                'textarea_rows' => 10,
                                'media_buttons' => false,
                                'teeny' => false,
                                'tinymce' => array(
                                    'toolbar1' => 'formatselect | bold italic underline | bullist numlist | link | removeformat',
                                    'toolbar2' => '',
                                    'block_formats' => 'Paragraph=p;Heading=h4',
                                ),
                                'quicktags' => array(
                                    'buttons' => 'strong,em,ul,ol,li,link'
                                )
                            );

                            wp_editor($content, $editor_id, $editor_settings);
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="stp-admin-footer">
            <button type="button" id="add-day-enhanced-bottom" class="stp-btn-secondary">
                <span class="dashicons dashicons-plus"></span> Add Another Day
            </button>
            <div class="stp-footer-info">
                <span class="dashicons dashicons-info"></span>
                <strong>Tip:</strong> Click on any day to expand and edit. Use keyboard shortcuts in the editor for faster formatting.
            </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        var dayCount = <?php echo count($itinerary); ?>;

        // Accordion toggle
        $(document).on('click', '.stp-btn-toggle', function(e) {
            e.preventDefault();
            var $item = $(this).closest('.stp-accordion-item');
            var $body = $item.find('.stp-accordion-body');
            var $icon = $(this).find('.dashicons');

            if ($body.is(':visible')) {
                $body.slideUp(300);
                $icon.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
                $item.removeClass('active');
            } else {
                $body.slideDown(300);
                $icon.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
                $item.addClass('active');
            }
        });

        // Add new day (both buttons)
        $('#add-day-enhanced, #add-day-enhanced-bottom').on('click', function() {
            dayCount++;
            var newIndex = dayCount - 1;

            var newDay = $('<div class="stp-accordion-item" data-day="' + newIndex + '"></div>');

            var dayHtml = '<div class="stp-accordion-header">' +
                '<div class="stp-day-indicator"><span class="stp-day-num">' + dayCount + '</span></div>' +
                '<div class="stp-day-title-preview">' +
                '<strong>Day ' + dayCount + '</strong>' +
                '<span class="stp-title-text">Click to edit</span>' +
                '</div>' +
                '<div class="stp-accordion-actions">' +
                '<button type="button" class="stp-btn-toggle">' +
                '<span class="dashicons dashicons-arrow-down-alt2"></span>' +
                '</button>' +
                '<button type="button" class="stp-btn-remove remove-day-enhanced">' +
                '<span class="dashicons dashicons-trash"></span>' +
                '</button>' +
                '</div>' +
                '</div>' +
                '<div class="stp-accordion-body" style="display:none;">' +
                '<div class="stp-form-group">' +
                '<label class="stp-label"><span class="stp-label-icon">📝</span> Day Title</label>' +
                '<input type="text" name="itinerary[' + newIndex + '][title]" value="" class="stp-input-title" placeholder="e.g., Arrival & City Exploration">' +
                '</div>' +
                '<div class="stp-form-group">' +
                '<label class="stp-label"><span class="stp-label-icon">📋</span> Activities & Details</label>' +
                '<textarea name="itinerary[' + newIndex + '][activities]" rows="10" class="stp-textarea" placeholder="Enter activities for this day..."></textarea>' +
                '<p class="stp-note">💡 Rich text editor will be available after saving</p>' +
                '</div>' +
                '</div>';

            newDay.html(dayHtml);
            $('#itinerary-container-enhanced').append(newDay);

            // Auto-expand new day
            newDay.find('.stp-btn-toggle').click();

            // Scroll to new day
            $('html, body').animate({
                scrollTop: newDay.offset().top - 100
            }, 500);
        });

        // Update title preview on input
        $(document).on('input', '.stp-input-title', function() {
            var title = $(this).val() || 'Click to edit';
            $(this).closest('.stp-accordion-item').find('.stp-title-text').text(title);
        });

        // Remove day
        $(document).on('click', '.remove-day-enhanced', function(e) {
            e.preventDefault();

            if ($('.stp-accordion-item').length <= 1) {
                alert('⚠️ You must have at least one day in the itinerary.');
                return;
            }

            if (confirm('🗑️ Are you sure you want to remove this day?')) {
                var $item = $(this).closest('.stp-accordion-item');

                $item.fadeOut(300, function() {
                    $(this).remove();

                    // Renumber all days
                    $('.stp-accordion-item').each(function(i) {
                        $(this).attr('data-day', i);
                        $(this).find('.stp-day-num').text(i + 1);
                        $(this).find('.stp-day-title-preview strong').text('Day ' + (i + 1));

                        // Update input names
                        $(this).find('input[type="text"]').attr('name', 'itinerary[' + i + '][title]');
                        $(this).find('textarea').attr('name', 'itinerary[' + i + '][activities]');
                    });

                    dayCount = $('.stp-accordion-item').length;
                });
            }
        });

        // Collapse all initially except first
        $('.stp-accordion-item').each(function(i) {
            if (i > 0) {
                $(this).find('.stp-accordion-body').hide();
            } else {
                $(this).addClass('active');
                $(this).find('.stp-btn-toggle .dashicons')
                    .removeClass('dashicons-arrow-down-alt2')
                    .addClass('dashicons-arrow-up-alt2');
            }
        });
    });
    </script>
    
    <style>
    /* Modern Admin Wrapper */
    .stp-modern-admin-wrapper {
        background: #f8f9fa;
        padding: 25px;
        margin: -6px -12px -12px;
        border-radius: 8px;
    }

    /* Header */
    .stp-admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #0073aa 0%, #00a0d2 100%);
        padding: 25px 30px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 115, 170, 0.2);
    }
    .stp-header-content h3 {
        color: #fff;
        font-size: 24px;
        margin: 0 0 5px 0;
        font-weight: 600;
    }
    .stp-header-content p {
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        font-size: 14px;
    }

    /* Buttons */
    .stp-btn-primary {
        background: #fff;
        color: #0073aa;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .stp-btn-primary:hover {
        background: #f0f0f0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .stp-btn-secondary {
        background: #0073aa;
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    .stp-btn-secondary:hover {
        background: #005a87;
    }

    /* Accordion Container */
    .stp-accordion-container {
        margin-bottom: 20px;
    }

    /* Accordion Item */
    .stp-accordion-item {
        background: #fff;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .stp-accordion-item:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }
    .stp-accordion-item.active {
        box-shadow: 0 4px 20px rgba(0, 115, 170, 0.15);
    }

    /* Accordion Header */
    .stp-accordion-header {
        display: flex;
        align-items: center;
        padding: 20px 25px;
        cursor: pointer;
        background: #fff;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .stp-accordion-item.active .stp-accordion-header {
        background: #f8f9fa;
        border-bottom-color: #0073aa;
    }

    /* Day Indicator */
    .stp-day-indicator {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #0073aa, #00a0d2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        flex-shrink: 0;
        box-shadow: 0 2px 10px rgba(0, 115, 170, 0.3);
    }
    .stp-day-num {
        color: #fff;
        font-size: 20px;
        font-weight: 700;
    }

    /* Day Title Preview */
    .stp-day-title-preview {
        flex: 1;
    }
    .stp-day-title-preview strong {
        display: block;
        font-size: 12px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .stp-title-text {
        font-size: 16px;
        color: #333;
        font-weight: 500;
    }

    /* Accordion Actions */
    .stp-accordion-actions {
        display: flex;
        gap: 10px;
    }
    .stp-btn-toggle, .stp-btn-remove {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        background: #f0f0f0;
    }
    .stp-btn-toggle:hover {
        background: #0073aa;
        color: #fff;
    }
    .stp-btn-toggle:hover .dashicons {
        color: #fff;
    }
    .stp-btn-remove {
        background: #fef0f0;
        color: #dc3232;
    }
    .stp-btn-remove:hover {
        background: #dc3232;
        color: #fff;
    }
    .stp-btn-remove:hover .dashicons {
        color: #fff;
    }

    /* Accordion Body */
    .stp-accordion-body {
        padding: 0 25px 25px 25px;
        background: #fafafa;
    }

    /* Form Groups */
    .stp-form-group {
        margin-bottom: 25px;
    }
    .stp-form-group:last-child {
        margin-bottom: 0;
    }

    /* Labels */
    .stp-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 14px;
        color: #333;
        margin-bottom: 10px;
    }
    .stp-label-icon {
        font-size: 16px;
    }
    .stp-label-hint {
        margin-left: auto;
        font-weight: 400;
        font-size: 12px;
        color: #888;
        font-style: italic;
    }

    /* Inputs */
    .stp-input-title {
        width: 100%;
        padding: 12px 16px;
        font-size: 16px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        transition: all 0.3s ease;
        background: #fff;
    }
    .stp-input-title:focus {
        outline: none;
        border-color: #0073aa;
        box-shadow: 0 0 0 3px rgba(0, 115, 170, 0.1);
    }

    /* Textarea */
    .stp-textarea {
        width: 100%;
        padding: 12px 16px;
        font-size: 14px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        transition: all 0.3s ease;
        background: #fff;
        resize: vertical;
    }
    .stp-textarea:focus {
        outline: none;
        border-color: #0073aa;
        box-shadow: 0 0 0 3px rgba(0, 115, 170, 0.1);
    }

    /* TinyMCE Styling */
    .stp-accordion-body .mce-tinymce {
        border-radius: 6px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .stp-accordion-body .mce-tinymce:focus-within {
        border-color: #0073aa;
        box-shadow: 0 0 0 3px rgba(0, 115, 170, 0.1);
    }

    /* Note */
    .stp-note {
        margin-top: 10px;
        font-size: 13px;
        color: #666;
        font-style: italic;
    }

    /* Footer */
    .stp-admin-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    .stp-footer-info {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #666;
    }
    .stp-footer-info .dashicons {
        color: #0073aa;
    }

    /* Dashicons */
    .dashicons {
        line-height: inherit;
        width: auto;
        height: auto;
        font-size: 18px;
    }

    /* Responsive */
    @media (max-width: 782px) {
        .stp-admin-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        .stp-admin-footer {
            flex-direction: column;
            gap: 15px;
        }
    }
    </style>
    <?php
}

// Save enhanced itinerary (SAFE: Only saves new data, doesn't delete)
add_action('save_post', 'stp_save_enhanced_itinerary', 10, 2);

function stp_save_enhanced_itinerary($post_id, $post) {
    // Security checks
    if (!isset($_POST['package_itinerary_enhanced_nonce']) || 
        !wp_verify_nonce($_POST['package_itinerary_enhanced_nonce'], 'package_itinerary_enhanced_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if ($post->post_type != 'travel_package') {
        return;
    }
    
    // Save itinerary (SAFE: Updates meta, doesn't delete other data)
    $itinerary = array();
    if (isset($_POST['itinerary']) && is_array($_POST['itinerary'])) {
        foreach ($_POST['itinerary'] as $day) {
            if (!empty($day['title']) || !empty($day['activities'])) {
                $itinerary[] = array(
                    'title' => sanitize_text_field($day['title']),
                    'activities' => wp_kses_post($day['activities']) // Preserves HTML formatting
                );
            }
        }
    }
    
    // Only update if we have itinerary data
    if (!empty($itinerary)) {
        update_post_meta($post_id, '_itinerary', $itinerary);
    }
}

// REMOVED: Duplicate "Detailed Itinerary" display
// The itinerary is now only displayed in the template file (single-travel-package.php)
// with the heading "Day by Day Itinerary"
//
// add_filter('the_content', 'stp_display_enhanced_itinerary', 20);
//
// function stp_display_enhanced_itinerary($content) {
//     if (!is_singular('travel_package')) {
//         return $content;
//     }
//
//     global $post;
//     $itinerary = get_post_meta($post->ID, '_itinerary', true);
//
//     if (empty($itinerary) || !is_array($itinerary)) {
//         return $content;
//     }
//
//     ob_start();
//     ?>
// <div class="travel-itinerary-enhanced" style="margin: 30px 0; padding: 30px; background: #f8f9fa; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
//         <h2 style="color: #0073aa; margin-top: 0; font-size: 28px; border-bottom: 3px solid #0073aa; padding-bottom: 15px; margin-bottom: 25px;">
//             📅 Detailed Itinerary
//         </h2>
//
//         <?php foreach ($itinerary as $i => $day): ?>
//             <div class="itinerary-day-item" style="margin-bottom: 30px; padding: 25px; background: white; border-left: 4px solid #0073aa; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
//                 <h3 style="color: #333; font-size: 22px; margin-top: 0; margin-bottom: 15px;">
//                     <span style="display: inline-block; width: 35px; height: 35px; background: #0073aa; color: white; border-radius: 50%; text-align: center; line-height: 35px; margin-right: 10px; font-size: 16px;">
//                         <?php echo $i + 1; ?>
//                     </span>
//                     <?php echo esc_html($day['title']); ?>
//                 </h3>
//
//                 <div class="itinerary-activities" style="color: #555; font-size: 15px; line-height: 1.8;">
//                     <?php echo wpautop($day['activities']); ?>
//                 </div>
//             </div>
//         <?php endforeach; ?>
//     </div>
//
//     <style>
//     .travel-itinerary-enhanced .itinerary-activities ul {
//         list-style: none;
//         padding-left: 0;
//         margin: 0;
//     }
//     .travel-itinerary-enhanced .itinerary-activities ul li {
//         padding-left: 30px;
//         position: relative;
//         margin-bottom: 10px;
//     }
//     .travel-itinerary-enhanced .itinerary-activities ul li:before {
//         content: "✓";
//         position: absolute;
//         left: 0;
//         color: #0073aa;
//         font-weight: bold;
//         font-size: 18px;
//     }
//     .travel-itinerary-enhanced .itinerary-activities a {
//         color: #0073aa;
//         text-decoration: none;
//         border-bottom: 1px dotted #0073aa;
//     }
//     .travel-itinerary-enhanced .itinerary-activities a:hover {
//         border-bottom-style: solid;
//     }
//     .itinerary-day-item:hover {
//         box-shadow: 0 3px 10px rgba(0,0,0,0.15);
//         transform: translateY(-2px);
//         transition: all 0.3s ease;
//     }
//     </style>
//     <?php
//     $itinerary_html = ob_get_clean();
//
//     return $content . $itinerary_html;
// }