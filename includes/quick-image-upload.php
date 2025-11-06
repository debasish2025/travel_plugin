<?php
/**
 * Quick Image Upload for Travel Packages
 * Allows bulk image upload directly from admin list page
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Featured Image column to packages list
 */
function stp_add_featured_image_column($columns) {
    $new_columns = array();

    // Add checkbox first
    if (isset($columns['cb'])) {
        $new_columns['cb'] = $columns['cb'];
        unset($columns['cb']);
    }

    // Add featured image column
    $new_columns['featured_image'] = '📷 Featured Image';

    // Add remaining columns
    $new_columns = array_merge($new_columns, $columns);

    return $new_columns;
}
add_filter('manage_travel_package_posts_columns', 'stp_add_featured_image_column');

/**
 * Display featured image with quick upload button
 */
function stp_display_featured_image_column($column, $post_id) {
    if ($column !== 'featured_image') {
        return;
    }

    $thumbnail = get_the_post_thumbnail($post_id, array(80, 80), array('style' => 'border-radius: 8px; display: block;'));

    ?>
    <div class="stp-quick-image-wrapper" data-post-id="<?php echo esc_attr($post_id); ?>">
        <div class="stp-image-preview">
            <?php if ($thumbnail): ?>
                <?php echo $thumbnail; ?>
            <?php else: ?>
                <div class="stp-no-image" style="width: 80px; height: 80px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 12px;">
                    No Image
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="button button-small stp-upload-image-btn" data-post-id="<?php echo esc_attr($post_id); ?>" style="margin-top: 5px; width: 80px;">
            <?php echo $thumbnail ? '🔄 Change' : '📤 Upload'; ?>
        </button>
        <div class="stp-upload-loader" style="display: none; margin-top: 5px;">
            <span class="spinner is-active" style="float: none; margin: 0;"></span>
        </div>
    </div>
    <?php
}
add_action('manage_travel_package_posts_custom_column', 'stp_display_featured_image_column', 10, 2);

/**
 * Make featured image column sortable
 */
function stp_make_featured_image_sortable($columns) {
    $columns['featured_image'] = 'featured_image';
    return $columns;
}
add_filter('manage_edit-travel_package_sortable_columns', 'stp_make_featured_image_sortable');

/**
 * Enqueue scripts for quick upload
 */
function stp_enqueue_quick_upload_scripts($hook) {
    // Only load on packages list page
    if ($hook !== 'edit.php' || !isset($_GET['post_type']) || $_GET['post_type'] !== 'travel_package') {
        return;
    }

    // Enqueue WordPress media uploader
    wp_enqueue_media();

    // Enqueue custom script
    wp_enqueue_script(
        'stp-quick-upload',
        STP_URL . 'assets/js/quick-upload.js',
        array('jquery'),
        '1.0.0',
        true
    );

    // Localize script
    wp_localize_script('stp-quick-upload', 'stpQuickUpload', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('stp_quick_upload_nonce'),
        'uploadingText' => 'Uploading...',
        'successText' => '✅ Done!',
        'errorText' => '❌ Error'
    ));

    // Add inline styles
    wp_add_inline_style('wp-admin', '
        .stp-quick-image-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .stp-image-preview {
            position: relative;
        }
        .stp-upload-image-btn {
            font-size: 11px;
            padding: 3px 8px;
            height: auto;
            line-height: 1.4;
        }
        .stp-upload-loader {
            text-align: center;
        }
        .column-featured_image {
            width: 120px;
        }
        .stp-quick-image-wrapper img {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .stp-quick-image-wrapper img:hover {
            transform: scale(1.05);
        }
    ');
}
add_action('admin_enqueue_scripts', 'stp_enqueue_quick_upload_scripts');

/**
 * AJAX handler for quick image upload
 */
function stp_ajax_quick_upload_image() {
    // Verify nonce
    check_ajax_referer('stp_quick_upload_nonce', 'nonce');

    // Check user permissions
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(array('message' => 'Permission denied'));
    }

    // Get post ID and attachment ID
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $attachment_id = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : 0;

    if (!$post_id || !$attachment_id) {
        wp_send_json_error(array('message' => 'Invalid post or attachment ID'));
    }

    // Set featured image
    $result = set_post_thumbnail($post_id, $attachment_id);

    if ($result) {
        // Get the new thumbnail HTML
        $thumbnail = get_the_post_thumbnail($post_id, array(80, 80), array('style' => 'border-radius: 8px; display: block;'));

        wp_send_json_success(array(
            'message' => 'Featured image updated successfully!',
            'thumbnail' => $thumbnail
        ));
    } else {
        wp_send_json_error(array('message' => 'Failed to set featured image'));
    }
}
add_action('wp_ajax_stp_quick_upload_image', 'stp_ajax_quick_upload_image');

/**
 * Add bulk image upload button at top of list
 */
function stp_add_bulk_upload_notice() {
    $screen = get_current_screen();

    if ($screen->id !== 'edit-travel_package') {
        return;
    }

    ?>
    <div class="notice notice-info" style="padding: 15px; margin-top: 20px;">
        <p style="margin: 0; font-size: 14px;">
            <strong>💡 Quick Tip:</strong> You can now upload/change featured images directly from this page!
            Click the <strong>"Upload"</strong> or <strong>"Change"</strong> button in the Featured Image column for any package.
            No need to edit each package individually! 🚀
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'stp_add_bulk_upload_notice');
