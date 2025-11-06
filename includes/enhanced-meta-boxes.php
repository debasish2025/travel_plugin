<?php
/**
 * Enhanced Meta Boxes with Rich Text Editors
 *
 * Modern Bootstrap-styled interface with keyboard shortcuts
 * Accordion layout to reduce scrolling
 */

// Add enhanced itinerary meta box
add_action('add_meta_boxes', 'stp_add_enhanced_itinerary_metabox', 20);

function stp_add_enhanced_itinerary_metabox() {
    add_meta_box(
        'package_itinerary_enhanced',
        '📅 Day by Day Itinerary',
        'stp_render_enhanced_itinerary',
        'travel_package',
        'normal',
        'high'
    );
}

// Enqueue Bootstrap and custom admin styles
add_action('admin_enqueue_scripts', 'stp_enqueue_admin_assets');

function stp_enqueue_admin_assets($hook) {
    global $post_type;
    if ('travel_package' !== $post_type || !in_array($hook, ['post.php', 'post-new.php'])) {
        return;
    }

    // Bootstrap CSS
    wp_enqueue_style('bootstrap-admin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    // Bootstrap JS
    wp_enqueue_script('bootstrap-admin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}

function stp_render_enhanced_itinerary($post) {
    wp_nonce_field('package_itinerary_enhanced_nonce', 'package_itinerary_enhanced_nonce');

    $itinerary = get_post_meta($post->ID, '_itinerary', true);
    if (!is_array($itinerary)) $itinerary = array();

    if (empty($itinerary)) {
        $itinerary = array(array('title' => '', 'activities' => ''));
    }

    ?>
    <div class="stp-admin-wrapper-tabs">
        <!-- Header Section -->
        <div class="stp-header-section">
            <div class="stp-header-content">
                <h3 class="stp-header-title">
                    <span class="stp-icon">📅</span>
                    Itinerary Management
                </h3>
                <p class="stp-header-subtitle">
                    <strong>💡 Shortcuts:</strong> Ctrl+B (Bold) • Ctrl+I (Italic) • Ctrl+U (Underline) • Ctrl+K (Link)
                </p>
            </div>
            <button type="button" id="add-day-tab" class="stp-add-btn">
                <span class="dashicons dashicons-plus-alt"></span>
                Add Day
            </button>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs stp-day-tabs" id="itinerary-tabs" role="tablist">
            <?php foreach ($itinerary as $i => $day): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo $i === 0 ? 'active' : ''; ?>"
                            id="day-tab-<?php echo $i; ?>"
                            data-bs-toggle="tab"
                            data-bs-target="#day-content-<?php echo $i; ?>"
                            type="button"
                            role="tab">
                        <span class="day-number">Day <?php echo $i + 1; ?></span>
                        <span class="day-title-preview"><?php echo !empty($day['title']) ? esc_html($day['title']) : 'Untitled'; ?></span>
                        <button type="button" class="remove-tab-day" data-day="<?php echo $i; ?>" title="Remove this day">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content stp-tab-content" id="itinerary-tab-content">
            <?php foreach ($itinerary as $i => $day): ?>
                <div class="tab-pane fade <?php echo $i === 0 ? 'show active' : ''; ?>"
                     id="day-content-<?php echo $i; ?>"
                     role="tabpanel"
                     data-day="<?php echo $i; ?>">

                    <div class="stp-day-form">
                        <!-- Day Title -->
                        <div class="stp-form-group">
                            <label class="stp-label">
                                <span class="stp-label-icon">📌</span>
                                Day Title
                            </label>
                            <input type="text"
                                   name="itinerary[<?php echo $i; ?>][title]"
                                   value="<?php echo esc_attr($day['title']); ?>"
                                   class="stp-input day-title-input"
                                   placeholder="e.g., Arrival & Temple Trail"
                                   data-day="<?php echo $i; ?>">
                        </div>

                        <!-- Activities Editor -->
                        <div class="stp-form-group">
                            <label class="stp-label">
                                <span class="stp-label-icon">✍️</span>
                                Activities & Details
                            </label>
                            <?php
                            $editor_id = 'itinerary_activities_' . $i;
                            $content = isset($day['activities']) ? $day['activities'] : '';

                            // Convert old format
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
                                    'toolbar1' => 'formatselect | bold italic underline strikethrough | forecolor backcolor | bullist numlist | link unlink | alignleft aligncenter alignright | undo redo | removeformat',
                                    'toolbar2' => '',
                                    'block_formats' => 'Paragraph=p;Heading 3=h3;Heading 4=h4',
                                    'content_css' => false,
                                ),
                                'quicktags' => array(
                                    'buttons' => 'strong,em,ul,ol,li,link,close'
                                )
                            );

                            wp_editor($content, $editor_id, $editor_settings);
                            ?>
                            <div class="stp-help-text">
                                💡 Tip: Use the toolbar above or keyboard shortcuts to format your text
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        var dayCount = <?php echo count($itinerary); ?>;

        // Update tab title when day title changes
        $(document).on('input', '.day-title-input', function() {
            var title = $(this).val();
            var dayIndex = $(this).data('day');
            var tabButton = $('#day-tab-' + dayIndex);
            var titlePreview = tabButton.find('.day-title-preview');

            if (title) {
                titlePreview.text(title);
            } else {
                titlePreview.text('Untitled');
            }
        });

        // Add new day tab
        $('#add-day-tab').on('click', function() {
            var newIndex = dayCount;

            // Create new tab
            var newTab = `
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="day-tab-${newIndex}"
                            data-bs-toggle="tab"
                            data-bs-target="#day-content-${newIndex}"
                            type="button"
                            role="tab">
                        <span class="day-number">Day ${dayCount + 1}</span>
                        <span class="day-title-preview">Untitled</span>
                        <button type="button" class="remove-tab-day" data-day="${newIndex}" title="Remove this day">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                    </button>
                </li>
            `;

            // Create new tab content
            var newContent = `
                <div class="tab-pane fade"
                     id="day-content-${newIndex}"
                     role="tabpanel"
                     data-day="${newIndex}">
                    <div class="stp-day-form">
                        <div class="stp-form-group">
                            <label class="stp-label">
                                <span class="stp-label-icon">📌</span>
                                Day Title
                            </label>
                            <input type="text"
                                   name="itinerary[${newIndex}][title]"
                                   value=""
                                   class="stp-input day-title-input"
                                   placeholder="e.g., Arrival & Temple Trail"
                                   data-day="${newIndex}">
                        </div>
                        <div class="stp-form-group">
                            <label class="stp-label">
                                <span class="stp-label-icon">✍️</span>
                                Activities & Details
                            </label>
                            <textarea name="itinerary[${newIndex}][activities]"
                                      rows="10"
                                      class="stp-input"
                                      placeholder="Enter activities for this day... (Rich editor available after saving)"></textarea>
                            <div class="stp-help-text">
                                💡 Tip: Save to enable rich text editor with formatting options
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Add tab and content
            $('#itinerary-tabs').append(newTab);
            $('#itinerary-tab-content').append(newContent);

            // Activate the new tab
            var newTabButton = $('#day-tab-' + newIndex);
            var tabInstance = new bootstrap.Tab(newTabButton[0]);
            tabInstance.show();

            dayCount++;
        });

        // Remove day tab
        $(document).on('click', '.remove-tab-day', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if ($('.nav-item').length <= 1) {
                alert('You must have at least one day in the itinerary.');
                return;
            }

            if (!confirm('Are you sure you want to remove this day?')) {
                return;
            }

            var dayIndex = $(this).data('day');
            var tabItem = $(this).closest('.nav-item');
            var tabContent = $('#day-content-' + dayIndex);

            // Check if this is the active tab
            var wasActive = $(this).closest('.nav-link').hasClass('active');

            // Remove tab and content
            tabItem.remove();
            tabContent.remove();

            // If we removed the active tab, activate the first remaining tab
            if (wasActive) {
                var firstTab = $('.nav-item:first .nav-link');
                if (firstTab.length) {
                    var tabInstance = new bootstrap.Tab(firstTab[0]);
                    tabInstance.show();
                }
            }

            // Renumber all tabs
            $('.nav-item').each(function(i) {
                var oldTabButton = $(this).find('.nav-link');
                var oldIndex = oldTabButton.attr('id').replace('day-tab-', '');

                // Update IDs
                oldTabButton.attr('id', 'day-tab-' + i);
                oldTabButton.attr('data-bs-target', '#day-content-' + i);
                oldTabButton.find('.day-number').text('Day ' + (i + 1));
                oldTabButton.find('.remove-tab-day').attr('data-day', i);

                // Update content IDs
                var contentPane = $('#day-content-' + oldIndex);
                contentPane.attr('id', 'day-content-' + i);
                contentPane.attr('data-day', i);

                // Update input names
                contentPane.find('input[name*="[title]"]').attr('name', 'itinerary[' + i + '][title]');
                contentPane.find('input[name*="[title]"]').attr('data-day', i);
                contentPane.find('[name*="[activities]"]').attr('name', 'itinerary[' + i + '][activities]');
            });

            dayCount = $('.nav-item').length;
        });

        // Add keyboard shortcuts to TinyMCE
        if (typeof tinymce !== 'undefined') {
            tinymce.on('AddEditor', function(e) {
                e.editor.on('init', function() {
                    this.shortcuts.add('ctrl+b', 'Bold', 'Bold');
                    this.shortcuts.add('ctrl+i', 'Italic', 'Italic');
                    this.shortcuts.add('ctrl+u', 'Underline', 'Underline');
                    this.shortcuts.add('ctrl+k', 'Insert Link', function() {
                        this.execCommand('mceLink');
                    });
                });
            });
        }
    });
    </script>
    
    <style>
    /* Main Container */
    .stp-admin-wrapper-tabs {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin: 10px 0;
    }

    /* Header Section */
    .stp-header-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .stp-header-content {
        flex: 1;
    }

    .stp-header-title {
        margin: 0 0 8px 0;
        color: #ffffff;
        font-size: 24px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stp-icon {
        font-size: 28px;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }

    .stp-header-subtitle {
        margin: 0;
        color: rgba(255, 255, 255, 0.95);
        font-size: 14px;
        font-weight: 400;
    }

    /* Add Day Button */
    .stp-add-btn {
        background: rgba(255, 255, 255, 0.25);
        border: 2px solid rgba(255, 255, 255, 0.5);
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(10px);
    }

    .stp-add-btn:hover {
        background: rgba(255, 255, 255, 0.35);
        border-color: rgba(255, 255, 255, 0.8);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stp-add-btn .dashicons {
        font-size: 18px;
        line-height: inherit;
    }

    /* Tab Navigation */
    .stp-day-tabs {
        background: #f8f9fa;
        border-bottom: 2px solid #e0e0e0;
        padding: 15px 20px 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .stp-day-tabs .nav-item {
        margin-bottom: -2px;
    }

    .stp-day-tabs .nav-link {
        background: #ffffff;
        border: 2px solid #e0e0e0;
        border-bottom: none;
        border-radius: 8px 8px 0 0;
        padding: 12px 20px;
        color: #555;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
        min-width: 140px;
    }

    .stp-day-tabs .nav-link:hover {
        background: #f8f9fa;
        border-color: #667eea;
        color: #667eea;
    }

    .stp-day-tabs .nav-link.active {
        background: #ffffff;
        border-color: #667eea;
        color: #667eea;
        border-bottom: 2px solid #ffffff;
        margin-bottom: -2px;
        box-shadow: 0 -2px 8px rgba(102, 126, 234, 0.15);
    }

    .day-number {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stp-day-tabs .nav-link.active .day-number {
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
    }

    .day-title-preview {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
        font-size: 13px;
    }

    /* Remove Button in Tab */
    .remove-tab-day {
        background: transparent;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
    }

    .remove-tab-day:hover {
        background: #dc3545;
        color: #ffffff;
        transform: scale(1.1);
    }

    .remove-tab-day .dashicons {
        font-size: 16px;
        line-height: 1;
    }

    /* Tab Content */
    .stp-tab-content {
        padding: 30px;
        background: #ffffff;
        min-height: 400px;
    }

    .stp-day-form {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Form Groups */
    .stp-form-group {
        margin-bottom: 30px;
    }

    .stp-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
    }

    .stp-label-icon {
        font-size: 20px;
    }

    /* Input Fields */
    .stp-input {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 16px;
        color: #333;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .stp-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .stp-input::placeholder {
        color: #aaa;
    }

    /* TinyMCE Editor Styling */
    .stp-day-form .mce-tinymce {
        border: 2px solid #e0e0e0 !important;
        border-radius: 10px !important;
        overflow: hidden !important;
    }

    .stp-day-form .mce-tinymce:focus-within {
        border-color: #667eea !important;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1) !important;
    }

    .stp-day-form .mce-toolbar-grp {
        background: #f8f9fa !important;
        border-bottom: 1px solid #e0e0e0 !important;
    }

    .stp-day-form .mce-btn {
        background: #ffffff !important;
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        margin: 2px !important;
    }

    .stp-day-form .mce-btn:hover {
        background: #667eea !important;
        color: #ffffff !important;
        border-color: #667eea !important;
    }

    /* Help Text */
    .stp-help-text {
        margin-top: 10px;
        padding: 12px 16px;
        background: #f0f7ff;
        border-left: 4px solid #667eea;
        border-radius: 6px;
        color: #555;
        font-size: 13px;
    }

    /* Dashicons */
    .dashicons {
        line-height: inherit;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stp-header-section {
            flex-direction: column;
            align-items: flex-start;
        }

        .stp-add-btn {
            width: 100%;
            justify-content: center;
        }

        .stp-day-tabs {
            padding: 10px 15px 0;
        }

        .stp-day-tabs .nav-link {
            padding: 10px 15px;
            min-width: auto;
        }

        .day-title-preview {
            max-width: 80px;
        }

        .stp-tab-content {
            padding: 20px 15px;
        }
    }

    /* Better spacing for WordPress admin */
    #poststuff .stp-admin-wrapper-tabs {
        margin: 10px 0;
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

// Removed detailed itinerary display from frontend - only day-by-day itinerary is shown now