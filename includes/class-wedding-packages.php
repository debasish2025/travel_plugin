<?php
/**
 * Wedding Packages System
 * Exact replica of Travel Packages with wedding-specific taxonomies
 */

if (!defined('ABSPATH')) exit;

class WP_Wedding_Packages {

    public function __construct() {
        // Register post type and taxonomies
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));

        // Add meta boxes (same as travel packages)
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_package_data'));

        // Template loader
        add_filter('single_template', array($this, 'load_single_template'));

        // Add template selection meta box
        add_action('add_meta_boxes', array($this, 'add_template_meta_box'));
    }

    /**
     * Register Wedding Package Post Type
     * Exact replica of travel_package
     */
    public function register_post_type() {
        register_post_type('wedding_package', array(
            'labels' => array(
                'name' => 'Wedding Packages',
                'singular_name' => 'Wedding Package',
                'add_new' => 'Add New Wedding Package',
                'add_new_item' => 'Add New Wedding Package',
                'edit_item' => 'Edit Wedding Package',
                'all_items' => 'All Wedding Packages',
                'view_item' => 'View Wedding Package',
                'search_items' => 'Search Wedding Packages',
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'elementor'),
            'menu_icon' => 'dashicons-heart',
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'wedding-package'),
            'menu_position' => 21,
        ));

        // DEBUG: Log post type registration
        error_log('===== WEDYARA DEBUG: Wedding Package Post Type Registered =====');
    }

    /**
     * Register Wedding Package Taxonomies
     * Categories: wedding_category, wedding_region, wedding_duration, wedding_activity
     */
    public function register_taxonomies() {

        // Wedding Categories
        register_taxonomy('wedding_category', 'wedding_package', array(
            'labels' => array(
                'name' => 'Wedding Categories',
                'singular_name' => 'Wedding Category',
                'add_new_item' => 'Add New Wedding Category',
                'edit_item' => 'Edit Wedding Category',
                'all_items' => 'All Wedding Categories',
                'search_items' => 'Search Wedding Categories',
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'wedding-category'),
        ));

        // Wedding Regions
        register_taxonomy('wedding_region', 'wedding_package', array(
            'labels' => array(
                'name' => 'Wedding Regions',
                'singular_name' => 'Wedding Region',
                'add_new_item' => 'Add New Wedding Region',
                'edit_item' => 'Edit Wedding Region',
                'all_items' => 'All Wedding Regions',
                'search_items' => 'Search Wedding Regions',
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'wedding-region'),
        ));

        // Wedding Duration
        register_taxonomy('wedding_duration', 'wedding_package', array(
            'labels' => array(
                'name' => 'Wedding Duration',
                'singular_name' => 'Duration',
                'add_new_item' => 'Add New Duration',
                'edit_item' => 'Edit Duration',
                'all_items' => 'All Durations',
                'search_items' => 'Search Durations',
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'wedding-duration'),
        ));

        // Wedding Activity Type (Wedding-related activities)
        register_taxonomy('wedding_activity', 'wedding_package', array(
            'labels' => array(
                'name' => 'Wedding Activities',
                'singular_name' => 'Wedding Activity',
                'add_new_item' => 'Add New Wedding Activity',
                'edit_item' => 'Edit Wedding Activity',
                'all_items' => 'All Wedding Activities',
                'search_items' => 'Search Wedding Activities',
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'wedding-activity'),
        ));

        // DEBUG: Log taxonomy registration
        error_log('===== WEDYARA DEBUG: Wedding Taxonomies Registered =====');
        error_log('Taxonomies: wedding_category, wedding_region, wedding_duration, wedding_activity');
    }

    /**
     * Add Template Selection Meta Box
     */
    public function add_template_meta_box() {
        add_meta_box(
            'wedding_template',
            'Page Template',
            array($this, 'render_template_selection'),
            'wedding_package',
            'side',
            'default'
        );
    }

    /**
     * Render Template Selection
     * Same templates as travel packages for now
     */
    public function render_template_selection($post) {
        $selected_template = get_post_meta($post->ID, '_page_template', true);
        ?>
        <p>
            <label>
                <input type="radio" name="page_template" value="default" <?php checked($selected_template, 'default'); ?> <?php checked($selected_template, ''); ?>>
                Template 1 - Classic Hero
            </label>
        </p>
        <p>
            <label>
                <input type="radio" name="page_template" value="modern" <?php checked($selected_template, 'modern'); ?>>
                Template 2 - Modern Split
            </label>
        </p>
        <p>
            <label>
                <input type="radio" name="page_template" value="fullwidth" <?php checked($selected_template, 'fullwidth'); ?>>
                Template 3 - Full Width Hero
            </label>
        </p>
        <p class="description">Choose the layout style for this wedding package page. All templates are Elementor-editable.</p>
        <?php
    }

    /**
     * Add Meta Boxes - Same as Travel Packages
     */
    public function add_meta_boxes() {
        // Package Details (same as travel)
        add_meta_box(
            'wedding_package_details',
            'Wedding Package Details',
            array($this, 'render_package_details'),
            'wedding_package',
            'normal',
            'high'
        );
    }

    /**
     * Render Package Details - Same as Travel Packages
     */
    public function render_package_details($post) {
        wp_nonce_field('package_details_nonce', 'package_details_nonce');

        $subtitle = get_post_meta($post->ID, '_subtitle', true);
        $days = get_post_meta($post->ID, '_days', true);
        $nights = get_post_meta($post->ID, '_nights', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label>Subtitle/Tagline</label></th>
                <td><input type="text" name="subtitle" value="<?php echo esc_attr($subtitle); ?>" class="widefat" placeholder="e.g., Your Dream Wedding Celebration"></td>
            </tr>
            <tr>
                <th><label>Duration</label></th>
                <td>
                    <input type="number" name="days" value="<?php echo esc_attr($days); ?>" style="width:80px"> Days
                    <input type="number" name="nights" value="<?php echo esc_attr($nights); ?>" style="width:80px"> Nights
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save Package Data - Same as Travel Packages
     */
    public function save_package_data($post_id) {
        // Check if our nonce is set and verify it
        if (!isset($_POST['package_details_nonce']) || !wp_verify_nonce($_POST['package_details_nonce'], 'package_details_nonce')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check post type
        if (get_post_type($post_id) !== 'wedding_package') {
            return;
        }

        // Save template selection
        if (isset($_POST['page_template'])) {
            update_post_meta($post_id, '_page_template', sanitize_text_field($_POST['page_template']));
        }

        // Save subtitle
        if (isset($_POST['subtitle'])) {
            update_post_meta($post_id, '_subtitle', sanitize_text_field($_POST['subtitle']));
        }

        // Save days
        if (isset($_POST['days'])) {
            update_post_meta($post_id, '_days', sanitize_text_field($_POST['days']));
        }

        // Save nights
        if (isset($_POST['nights'])) {
            update_post_meta($post_id, '_nights', sanitize_text_field($_POST['nights']));
        }

        // Save itinerary (same format as travel packages)
        if (isset($_POST['itinerary'])) {
            update_post_meta($post_id, '_itinerary', $_POST['itinerary']);
        }

        // DEBUG: Log save operation
        error_log('===== WEDYARA DEBUG: Wedding Package Data Saved =====');
        error_log('Post ID: ' . $post_id);
    }

    /**
     * Load Custom Single Template for Wedding Packages
     * Uses same template as travel packages for now
     */
    public function load_single_template($template) {
        if (is_singular('wedding_package')) {
            // Use travel package template for now
            $custom_template = plugin_dir_path(dirname(__FILE__)) . 'templates/single-travel-package.php';

            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }

        return $template;
    }
}

// Initialize
new WP_Wedding_Packages();
