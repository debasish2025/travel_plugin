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
    
    // Get existing itinerary (SAFE: Reads existing data)
    $itinerary = get_post_meta($post->ID, '_itinerary', true);
    if (!is_array($itinerary)) $itinerary = array();
    
    // If empty, add one default day
    if (empty($itinerary)) {
        $itinerary = array(
            array('title' => '', 'activities' => '')
        );
    }
    
    ?>
    <div id="itinerary-container-enhanced">
        <?php foreach ($itinerary as $i => $day): ?>
            <div class="itinerary-day-enhanced" data-day="<?php echo $i; ?>" style="border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; background: #f9f9f9; border-radius: 5px;">
                <h3 style="margin-top: 0; color: #23282d;">
                    📍 Day <?php echo $i + 1; ?>
                    <button type="button" class="button remove-day-enhanced" style="float: right; background: #dc3232; color: white; border-color: #dc3232;">
                        <span class="dashicons dashicons-trash" style="vertical-align: middle;"></span> Remove Day
                    </button>
                    <div style="clear: both;"></div>
                </h3>
                
                <p>
                    <label style="font-weight: 600; display: block; margin-bottom: 5px;">Day Title:</label>
                    <input type="text" 
                           name="itinerary[<?php echo $i; ?>][title]" 
                           value="<?php echo esc_attr($day['title']); ?>" 
                           class="widefat" 
                           placeholder="e.g., Arrival & Temple Trail"
                           style="padding: 8px; font-size: 14px;">
                </p>
                
                <p>
                    <label style="font-weight: 600; display: block; margin-bottom: 5px;">Activities & Details:</label>
                    <?php
                    // Create unique editor ID
                    $editor_id = 'itinerary_activities_' . $i;
                    $content = isset($day['activities']) ? $day['activities'] : '';
                    
                    // If old format with pipes, convert to list
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
                    
                    // Rich text editor settings
                    $editor_settings = array(
                        'textarea_name' => 'itinerary[' . $i . '][activities]',
                        'textarea_rows' => 8,
                        'media_buttons' => false, // No media upload
                        'teeny' => false, // Full editor
                        'tinymce' => array(
                            'toolbar1' => 'bold,italic,underline,|,bullist,numlist,|,link,unlink,|,undo,redo',
                            'toolbar2' => '',
                        ),
                        'quicktags' => array(
                            'buttons' => 'strong,em,ul,ol,li,link'
                        )
                    );
                    
                    wp_editor($content, $editor_id, $editor_settings);
                    ?>
                    <span class="description" style="display: block; margin-top: 5px;">
                        ✨ Use the editor toolbar to format your text with <strong>bold</strong>, <em>italic</em>, bullet points, and links
                    </span>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
    
    <p>
        <button type="button" id="add-day-enhanced" class="button button-primary button-large">
            <span class="dashicons dashicons-plus-alt" style="vertical-align: middle;"></span>
            Add Another Day
        </button>
    </p>
    
    <script>
    jQuery(document).ready(function($) {
        var dayCount = <?php echo count($itinerary); ?>;
        
        // Add new day
        $('#add-day-enhanced').on('click', function() {
            dayCount++;
            var newDay = $('<div class="itinerary-day-enhanced" data-day="' + dayCount + '" style="border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; background: #f9f9f9; border-radius: 5px;"></div>');
            
            var dayHtml = '<h3 style="margin-top: 0; color: #23282d;">' +
                '📍 Day ' + dayCount + 
                '<button type="button" class="button remove-day-enhanced" style="float: right; background: #dc3232; color: white; border-color: #dc3232;">' +
                '<span class="dashicons dashicons-trash" style="vertical-align: middle;"></span> Remove Day' +
                '</button>' +
                '<div style="clear: both;"></div>' +
                '</h3>' +
                '<p>' +
                '<label style="font-weight: 600; display: block; margin-bottom: 5px;">Day Title:</label>' +
                '<input type="text" name="itinerary[' + (dayCount - 1) + '][title]" value="" class="widefat" placeholder="e.g., Arrival & Temple Trail" style="padding: 8px; font-size: 14px;">' +
                '</p>' +
                '<p>' +
                '<label style="font-weight: 600; display: block; margin-bottom: 5px;">Activities & Details:</label>' +
                '<textarea name="itinerary[' + (dayCount - 1) + '][activities]" rows="8" class="widefat" style="font-family: monospace;" placeholder="Enter activities for this day..."></textarea>' +
                '<span class="description" style="display: block; margin-top: 5px;">Note: Rich text editor will be available after saving this day</span>' +
                '</p>';
            
            newDay.html(dayHtml);
            $('#itinerary-container-enhanced').append(newDay);
            
            // Scroll to new day
            $('html, body').animate({
                scrollTop: newDay.offset().top - 50
            }, 500);
        });
        
        // Remove day
        $(document).on('click', '.remove-day-enhanced', function() {
            if ($('.itinerary-day-enhanced').length <= 1) {
                alert('You must have at least one day in the itinerary.');
                return;
            }
            
            if (confirm('Are you sure you want to remove this day?')) {
                $(this).closest('.itinerary-day-enhanced').fadeOut(300, function() {
                    $(this).remove();
                    // Renumber days
                    $('.itinerary-day-enhanced').each(function(i) {
                        $(this).attr('data-day', i);
                        $(this).find('h3').first().html(
                            '📍 Day ' + (i + 1) + 
                            '<button type="button" class="button remove-day-enhanced" style="float: right; background: #dc3232; color: white; border-color: #dc3232;">' +
                            '<span class="dashicons dashicons-trash" style="vertical-align: middle;"></span> Remove Day' +
                            '</button>' +
                            '<div style="clear: both;"></div>'
                        );
                        
                        // Update input names
                        $(this).find('input[type="text"]').attr('name', 'itinerary[' + i + '][title]');
                        $(this).find('textarea').attr('name', 'itinerary[' + i + '][activities]');
                    });
                    dayCount = $('.itinerary-day-enhanced').length;
                });
            }
        });
    });
    </script>
    
    <style>
    .itinerary-day-enhanced {
        position: relative;
        transition: all 0.3s ease;
    }
    .itinerary-day-enhanced:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .itinerary-day-enhanced .mce-tinymce {
        margin-top: 5px;
        border-radius: 3px;
    }
    .itinerary-day-enhanced h3 {
        border-bottom: 2px solid #0073aa;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    .remove-day-enhanced:hover {
        background: #a00 !important;
        border-color: #a00 !important;
    }
    .dashicons {
        line-height: inherit;
    }
    #add-day-enhanced {
        font-size: 14px;
        padding: 8px 15px;
        height: auto;
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

// Display formatted itinerary on frontend
add_filter('the_content', 'stp_display_enhanced_itinerary', 20);

function stp_display_enhanced_itinerary($content) {
    if (!is_singular('travel_package')) {
        return $content;
    }
    
    global $post;
    $itinerary = get_post_meta($post->ID, '_itinerary', true);
    
    if (empty($itinerary) || !is_array($itinerary)) {
        return $content;
    }
    
    ob_start();
    ?>
<div class="travel-itinerary-enhanced" style="margin: 30px 0; padding: 30px; background: #f8f9fa; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <h2 style="color: #0073aa; margin-top: 0; font-size: 28px; border-bottom: 3px solid #0073aa; padding-bottom: 15px; margin-bottom: 25px;">
            📅 Detailed Itinerary
        </h2>
        
        <?php foreach ($itinerary as $i => $day): ?>
            <div class="itinerary-day-item" style="margin-bottom: 30px; padding: 25px; background: white; border-left: 4px solid #0073aa; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="color: #333; font-size: 22px; margin-top: 0; margin-bottom: 15px;">
                    <span style="display: inline-block; width: 35px; height: 35px; background: #0073aa; color: white; border-radius: 50%; text-align: center; line-height: 35px; margin-right: 10px; font-size: 16px;">
                        <?php echo $i + 1; ?>
                    </span>
                    <?php echo esc_html($day['title']); ?>
                </h3>
                
                <div class="itinerary-activities" style="color: #555; font-size: 15px; line-height: 1.8;">
                    <?php echo wpautop($day['activities']); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <style>
    .travel-itinerary-enhanced .itinerary-activities ul {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }
    .travel-itinerary-enhanced .itinerary-activities ul li {
        padding-left: 30px;
        position: relative;
        margin-bottom: 10px;
    }
    .travel-itinerary-enhanced .itinerary-activities ul li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #0073aa;
        font-weight: bold;
        font-size: 18px;
    }
    .travel-itinerary-enhanced .itinerary-activities a {
        color: #0073aa;
        text-decoration: none;
        border-bottom: 1px dotted #0073aa;
    }
    .travel-itinerary-enhanced .itinerary-activities a:hover {
        border-bottom-style: solid;
    }
    .itinerary-day-item:hover {
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    </style>
    <?php
    $itinerary_html = ob_get_clean();
    
    return $content . $itinerary_html;
}