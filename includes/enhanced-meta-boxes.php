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
    <div class="stp-admin-wrapper">
        <div class="alert alert-info mb-3">
            <strong>💡 Keyboard Shortcuts:</strong>
            Ctrl+B = Bold | Ctrl+I = Italic | Ctrl+U = Underline | Ctrl+K = Add Link
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0">Itinerary Days</h4>
            <button type="button" id="add-day-enhanced" class="btn btn-success">
                <i class="dashicons dashicons-plus-alt" style="vertical-align: middle;"></i>
                Add New Day
            </button>
        </div>

        <div class="accordion" id="itinerary-accordion">
            <?php foreach ($itinerary as $i => $day): ?>
                <div class="accordion-item itinerary-day-enhanced mb-2" data-day="<?php echo $i; ?>">
                    <h2 class="accordion-header">
                        <button class="accordion-button <?php echo $i === 0 ? '' : 'collapsed'; ?>" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse-day-<?php echo $i; ?>"
                                aria-expanded="<?php echo $i === 0 ? 'true' : 'false'; ?>">
                            <strong>📍 Day <?php echo $i + 1; ?></strong>
                            <span class="ms-2 text-muted"><?php echo !empty($day['title']) ? '- ' . esc_html($day['title']) : '(No title yet)'; ?></span>
                        </button>
                    </h2>
                    <div id="collapse-day-<?php echo $i; ?>"
                         class="accordion-collapse collapse <?php echo $i === 0 ? 'show' : ''; ?>"
                         data-bs-parent="#itinerary-accordion">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold">Day Title</label>
                                    <input type="text"
                                           name="itinerary[<?php echo $i; ?>][title]"
                                           value="<?php echo esc_attr($day['title']); ?>"
                                           class="form-control form-control-lg day-title-input"
                                           placeholder="e.g., Arrival & Temple Trail">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Activities & Details</label>
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
                                        'textarea_rows' => 6,
                                        'media_buttons' => false,
                                        'teeny' => false,
                                        'tinymce' => array(
                                            'toolbar1' => 'bold,italic,underline,strikethrough,|,bullist,numlist,|,link,unlink,|,undo,redo,|,removeformat',
                                            'toolbar2' => '',
                                            'content_css' => false,
                                            'setup' => 'function(editor) {
                                                editor.addShortcut("ctrl+b", "Bold", "Bold");
                                                editor.addShortcut("ctrl+i", "Italic", "Italic");
                                                editor.addShortcut("ctrl+u", "Underline", "Underline");
                                                editor.addShortcut("ctrl+k", "Insert Link", function() {
                                                    editor.execCommand("mceLink");
                                                });
                                            }'
                                        ),
                                        'quicktags' => array(
                                            'buttons' => 'strong,em,ul,ol,li,link'
                                        )
                                    );

                                    wp_editor($content, $editor_id, $editor_settings);
                                    ?>
                                    <small class="form-text text-muted">
                                        Use formatting buttons or keyboard shortcuts (Ctrl+B for bold, Ctrl+I for italic, etc.)
                                    </small>
                                </div>

                                <div class="col-12">
                                    <button type="button" class="btn btn-danger btn-sm remove-day-enhanced">
                                        <i class="dashicons dashicons-trash" style="vertical-align: middle;"></i>
                                        Remove This Day
                                    </button>
                                </div>
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

        // Update accordion header when title changes
        $(document).on('input', '.day-title-input', function() {
            var title = $(this).val();
            var dayItem = $(this).closest('.itinerary-day-enhanced');
            var dayNum = dayItem.find('.accordion-button strong').text();
            var headerSpan = dayItem.find('.accordion-button .text-muted');

            if (title) {
                headerSpan.text('- ' + title);
            } else {
                headerSpan.text('(No title yet)');
            }
        });

        // Add new day
        $('#add-day-enhanced').on('click', function() {
            var newIndex = dayCount;
            var newDayHtml = `
                <div class="accordion-item itinerary-day-enhanced mb-2" data-day="${newIndex}">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse-day-${newIndex}"
                                aria-expanded="true">
                            <strong>📍 Day ${dayCount + 1}</strong>
                            <span class="ms-2 text-muted">(No title yet)</span>
                        </button>
                    </h2>
                    <div id="collapse-day-${newIndex}"
                         class="accordion-collapse collapse show"
                         data-bs-parent="#itinerary-accordion">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold">Day Title</label>
                                    <input type="text"
                                           name="itinerary[${newIndex}][title]"
                                           value=""
                                           class="form-control form-control-lg day-title-input"
                                           placeholder="e.g., Arrival & Temple Trail">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Activities & Details</label>
                                    <textarea name="itinerary[${newIndex}][activities]"
                                              rows="6"
                                              class="form-control"
                                              placeholder="Enter activities for this day..."></textarea>
                                    <small class="form-text text-muted">
                                        Rich text editor will be available after saving
                                    </small>
                                </div>

                                <div class="col-12">
                                    <button type="button" class="btn btn-danger btn-sm remove-day-enhanced">
                                        <i class="dashicons dashicons-trash" style="vertical-align: middle;"></i>
                                        Remove This Day
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Collapse all other days
            $('.accordion-collapse').removeClass('show');
            $('.accordion-button').addClass('collapsed');

            // Add new day
            $('#itinerary-accordion').append(newDayHtml);
            dayCount++;

            // Scroll to new day
            $('html, body').animate({
                scrollTop: $('.itinerary-day-enhanced:last').offset().top - 100
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

                    // Renumber all days
                    $('.itinerary-day-enhanced').each(function(i) {
                        $(this).attr('data-day', i);

                        // Update day number in header
                        $(this).find('.accordion-button strong').text('📍 Day ' + (i + 1));

                        // Update collapse IDs
                        $(this).find('.accordion-button')
                            .attr('data-bs-target', '#collapse-day-' + i);
                        $(this).find('.accordion-collapse')
                            .attr('id', 'collapse-day-' + i);

                        // Update input names
                        $(this).find('input[name*="[title]"]')
                            .attr('name', 'itinerary[' + i + '][title]');
                        $(this).find('[name*="[activities]"]')
                            .attr('name', 'itinerary[' + i + '][activities]');
                    });

                    dayCount = $('.itinerary-day-enhanced').length;
                });
            }
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
    .stp-admin-wrapper {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 10px;
    }

    .stp-admin-wrapper .alert-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        border-radius: 8px;
    }

    .stp-admin-wrapper .accordion-button {
        background: #fff;
        font-size: 16px;
        padding: 15px 20px;
        transition: all 0.3s ease;
    }

    .stp-admin-wrapper .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .stp-admin-wrapper .accordion-button:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .stp-admin-wrapper .accordion-body {
        padding: 25px;
        background: #ffffff;
    }

    .stp-admin-wrapper .form-control-lg {
        font-size: 18px;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }

    .stp-admin-wrapper .form-control-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .stp-admin-wrapper .form-label {
        color: #333;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .stp-admin-wrapper .mce-tinymce {
        border: 2px solid #e0e0e0 !important;
        border-radius: 8px !important;
        margin-top: 5px;
    }

    .stp-admin-wrapper .btn-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        padding: 10px 20px;
        font-weight: 600;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stp-admin-wrapper .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4);
    }

    .stp-admin-wrapper .btn-danger {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        border: none;
        transition: transform 0.2s ease;
    }

    .stp-admin-wrapper .btn-danger:hover {
        transform: scale(1.05);
    }

    .stp-admin-wrapper .accordion-item {
        border: none;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .stp-admin-wrapper .text-muted {
        font-weight: 400;
        opacity: 0.8;
    }

    .dashicons {
        line-height: inherit;
    }

    /* Smooth animations */
    .accordion-collapse {
        transition: height 0.35s ease;
    }

    /* Better spacing for WordPress admin */
    #poststuff .stp-admin-wrapper {
        margin: 0;
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