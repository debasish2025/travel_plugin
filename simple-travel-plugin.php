<?php
/**
 * Plugin Name: Wedyara - Travel & Wedding Packages
 * Description: Complete package management system for travel and wedding services with Elementor widgets, CSV import, and advanced features
 * Version: 1.0.0
 * Author: Wedyara Team
 * Author URI: https://wedyara.com
 * Requires Plugins: elementor
 * Text Domain: wedyara
 */

if (!defined('ABSPATH')) exit;

define('STP_VERSION', '1.0.0');
define('STP_PATH', plugin_dir_path(__FILE__));
define('STP_URL', plugin_dir_url(__FILE__));

class Simple_Travel_Plugin {
    
    public function __construct() {
        // Load includes
        add_action('plugins_loaded', array($this, 'load_includes'));

        // Register parent menu
        add_action('admin_menu', array($this, 'register_parent_menu'));

        // Register post type
        add_action('init', array($this, 'register_post_type'));

        // Add meta boxes
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_package_data'));

        // Elementor widgets
        add_action('elementor/widgets/register', array($this, 'register_elementor_widgets'));

        // Enqueue styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));

        // Template loader
        add_filter('single_template', array($this, 'load_single_template'));

        // Add template selection meta box
        add_action('add_meta_boxes', array($this, 'add_template_meta_box'));

        // AJAX for AI category detection
        add_action('wp_ajax_stp_ai_detect_category', array($this, 'ai_detect_category'));
    }

    /**
     * Register Wedyara Parent Menu
     * We rename Travel Packages menu to "Wedyara" and add Wedding Packages as submenu
     */
    public function register_parent_menu() {
        global $menu, $submenu;

        // Rename Travel Packages menu to "Wedyara"
        $travel_menu_slug = 'edit.php?post_type=travel_package';

        // Find and rename the travel package menu
        foreach ($menu as $key => $item) {
            if ($item[2] === $travel_menu_slug) {
                $menu[$key][0] = 'Wedyara';  // Change menu title
                $menu[$key][6] = 'dashicons-heart';  // Change icon
                break;
            }
        }

        // Rename the submenu item from "Travel Packages" to "Travel Packages"
        if (isset($submenu[$travel_menu_slug])) {
            foreach ($submenu[$travel_menu_slug] as $key => $item) {
                if ($item[2] === $travel_menu_slug) {
                    $submenu[$travel_menu_slug][$key][0] = 'Travel Packages';
                    break;
                }
            }
        }
    }
    
    public function load_includes() {
        // Load comprehensive taxonomies system
        require_once STP_PATH . 'includes/class-tpm-taxonomies.php';
        require_once STP_PATH . 'includes/class-tpm-default-terms.php';
        require_once STP_PATH . 'includes/csv-import-fixed.php';
        require_once STP_PATH . 'includes/csv-import.php'; // New powerful CSV importer with HTML support
        require_once STP_PATH . 'includes/enhanced-meta-boxes.php';
        require_once STP_PATH . 'includes/whatsapp-social-related.php';
        require_once STP_PATH . 'includes/quick-image-upload.php'; // Quick image upload from list page

        // Load Wedding Packages System
        require_once STP_PATH . 'includes/class-wedding-packages.php';
        require_once STP_PATH . 'includes/wedding-csv-import.php'; // CSV importer for wedding packages
    }
    
    public function register_post_type() {
        register_post_type('travel_package', array(
            'labels' => array(
                'name' => 'Travel Packages',
                'singular_name' => 'Travel Package',
                'add_new' => 'Add New Package',
                'add_new_item' => 'Add New Travel Package',
                'edit_item' => 'Edit Travel Package',
                'all_items' => 'Travel Packages',
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'elementor'),
            'menu_icon' => 'dashicons-palmtree',
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'package'),
            'taxonomies' => array('package_category'), // Keep for backward compatibility
        ));
        
        /* 
         * OLD SIMPLE CATEGORY REGISTRATION - COMMENTED OUT
         * The comprehensive taxonomy system (class-tpm-taxonomies.php) now handles all taxonomies
         * including Categories, Regions, Durations, Seasons, etc.
         * 
         * If you want to keep using the old simple category, you can uncomment this section
         * and remove the new taxonomy files. Otherwise, use the new comprehensive system.
         */
        
        /*
        // Categories - Old Simple Version
        register_taxonomy('package_category', 'travel_package', array(
            'labels' => array(
                'name' => 'Package Categories',
                'singular_name' => 'Category',
                'search_items' => 'Search Categories',
                'all_items' => 'All Categories',
                'parent_item' => 'Parent Category',
                'parent_item_colon' => 'Parent Category:',
                'edit_item' => 'Edit Category',
                'update_item' => 'Update Category',
                'add_new_item' => 'Add New Category',
                'new_item_name' => 'New Category Name',
                'menu_name' => 'Categories',
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'package-category'),
        ));
        */
    }
    
    public function add_template_meta_box() {
        add_meta_box(
            'package_template',
            'Page Template',
            array($this, 'render_template_selection'),
            'travel_package',
            'side',
            'default'
        );
    }
    
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
        <p class="description">Choose the layout style for this package page. All templates are Elementor-editable.</p>
        <?php
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'package_details',
            'Package Details',
            array($this, 'render_package_details'),
            'travel_package',
            'normal',
            'high'
        );

        // OLD ITINERARY META BOX - REMOVED
        // Now using enhanced meta box from enhanced-meta-boxes.php with rich text editor
        /*
        add_meta_box(
            'package_itinerary',
            'Itinerary (Day by Day)',
            array($this, 'render_itinerary'),
            'travel_package',
            'normal',
            'default'
        );
        */
    }
    
    public function render_package_details($post) {
        wp_nonce_field('package_details_nonce', 'package_details_nonce');
        
        $subtitle = get_post_meta($post->ID, '_subtitle', true);
        $days = get_post_meta($post->ID, '_days', true);
        $nights = get_post_meta($post->ID, '_nights', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label>Subtitle/Tagline</label></th>
                <td><input type="text" name="subtitle" value="<?php echo esc_attr($subtitle); ?>" class="widefat" placeholder="e.g., Sun, sand & vows by the sea."></td>
            </tr>
            <tr>
                <th><label>Duration</label></th>
                <td>
                    <input type="number" name="days" value="<?php echo esc_attr($days); ?>" style="width:80px"> Days
                    <input type="number" name="nights" value="<?php echo esc_attr($nights); ?>" style="width:80px"> Nights
                </td>
            </tr>
            <tr>
                <th><label>AI Category Detection</label></th>
                <td>
                    <button type="button" id="stp-ai-detect-category" class="button button-secondary">
                        🤖 Auto-Detect Category from Title & Description
                    </button>
                    <p class="description">AI will analyze your content and automatically assign the best category.</p>
                    <div id="stp-ai-result" style="margin-top:10px;"></div>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function render_itinerary($post) {
        wp_nonce_field('package_itinerary_nonce', 'package_itinerary_nonce');
        
        $itinerary = get_post_meta($post->ID, '_itinerary', true);
        if (!is_array($itinerary)) $itinerary = array();
        ?>
        <div id="itinerary-container">
            <?php foreach ($itinerary as $i => $day): ?>
                <div class="itinerary-day" style="border:1px solid #ddd; padding:15px; margin-bottom:10px;">
                    <h4>Day <?php echo $i + 1; ?> 
                        <button type="button" class="button remove-day" style="float:right;">Remove</button>
                    </h4>
                    <p>
                        <label>Day Title</label><br>
                        <input type="text" name="itinerary[<?php echo $i; ?>][title]" value="<?php echo esc_attr($day['title']); ?>" class="widefat" placeholder="e.g., Arrival & Welcome">
                    </p>
                    <p>
                        <label>Activities</label><br>
                        <textarea name="itinerary[<?php echo $i; ?>][activities]" rows="4" class="widefat"><?php echo esc_textarea($day['activities']); ?></textarea>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-day" class="button button-primary">Add Day</button>
        
        <script>
        jQuery(document).ready(function($){
            var dayCount = <?php echo count($itinerary); ?>;
            
            $('#add-day').click(function(){
                dayCount++;
                var html = '<div class="itinerary-day" style="border:1px solid #ddd; padding:15px; margin-bottom:10px;">' +
                    '<h4>Day ' + dayCount + ' <button type="button" class="button remove-day" style="float:right;">Remove</button></h4>' +
                    '<p><label>Day Title</label><br><input type="text" name="itinerary[' + (dayCount-1) + '][title]" class="widefat"></p>' +
                    '<p><label>Activities</label><br><textarea name="itinerary[' + (dayCount-1) + '][activities]" rows="4" class="widefat"></textarea></p>' +
                    '</div>';
                $('#itinerary-container').append(html);
            });
            
            $(document).on('click', '.remove-day', function(){
                $(this).closest('.itinerary-day').remove();
                $('.itinerary-day').each(function(i){
                    $(this).find('h4').first().text('Day ' + (i+1) + ' ');
                    $(this).find('h4').first().append('<button type="button" class="button remove-day" style="float:right;">Remove</button>');
                });
                dayCount = $('.itinerary-day').length;
            });
            
            // AI Category Detection
            $('#stp-ai-detect-category').click(function(){
                var btn = $(this);
                var title = $('#title').val();
                var description = '';
                
                if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                    description = tinymce.get('content').getContent({format: 'text'});
                } else {
                    description = $('#content').val();
                }
                
                if (!title) {
                    alert('Please enter a package title first!');
                    return;
                }
                
                btn.prop('disabled', true).text('🤖 Analyzing...');
                $('#stp-ai-result').html('<p style="color:#666;">AI is analyzing your content...</p>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'stp_ai_detect_category',
                        nonce: '<?php echo wp_create_nonce("stp_ai_category_nonce"); ?>',
                        title: title,
                        description: description
                    },
                    success: function(response){
                        if (response.success && response.data.length > 0) {
                            var categories = response.data.map(function(cat){ return cat.name; }).join(', ');
                            $('#stp-ai-result').html('<p style="color:#46b450; font-weight:600;">✓ Detected: ' + categories + '</p><p class="description">Category has been automatically assigned. Check the Categories box on the right →</p>');
                            
                            // Auto-select the categories
                            response.data.forEach(function(cat){
                                $('#in-package_category-' + cat.term_id).prop('checked', true);
                            });
                        } else {
                            $('#stp-ai-result').html('<p style="color:#dc3232;">Could not detect category. Please select manually.</p>');
                        }
                    },
                    error: function(){
                        $('#stp-ai-result').html('<p style="color:#dc3232;">Error occurred. Please try again.</p>');
                    },
                    complete: function(){
                        btn.prop('disabled', false).text('🤖 Auto-Detect Category from Title & Description');
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    public function save_package_data($post_id) {
        if (!isset($_POST['package_details_nonce']) || !wp_verify_nonce($_POST['package_details_nonce'], 'package_details_nonce')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        
        update_post_meta($post_id, '_subtitle', sanitize_text_field($_POST['subtitle']));
        update_post_meta($post_id, '_days', intval($_POST['days']));
        update_post_meta($post_id, '_nights', intval($_POST['nights']));
        
        // Save template selection
        if (isset($_POST['page_template'])) {
            update_post_meta($post_id, '_page_template', sanitize_text_field($_POST['page_template']));
        }

        // OLD ITINERARY SAVE - REMOVED
        // Now using enhanced save function from enhanced-meta-boxes.php which preserves HTML formatting
        /*
        if (isset($_POST['package_itinerary_nonce']) && wp_verify_nonce($_POST['package_itinerary_nonce'], 'package_itinerary_nonce')) {
            $itinerary = array();
            if (isset($_POST['itinerary']) && is_array($_POST['itinerary'])) {
                foreach ($_POST['itinerary'] as $day) {
                    $itinerary[] = array(
                        'title' => sanitize_text_field($day['title']),
                        'activities' => sanitize_textarea_field($day['activities']),
                    );
                }
            }
            update_post_meta($post_id, '_itinerary', $itinerary);
        }
        */
    }
    
    public function register_elementor_widgets($widgets_manager) {
        // Unified Package Widgets (work for both Travel and Wedding packages)
        require_once STP_PATH . 'elementor-widgets/destination-grid.php';
        require_once STP_PATH . 'elementor-widgets/destination-carousel.php';
        require_once STP_PATH . 'elementor-widgets/fullwidth-slider.php';

        $widgets_manager->register(new \Elementor_Destination_Grid_Widget());
        $widgets_manager->register(new \Elementor_Destination_Carousel_Widget());
        $widgets_manager->register(new \Elementor_Fullwidth_Slider_Widget());
    }
    
    public function enqueue_styles() {
        wp_enqueue_style('stp-styles', STP_URL . 'assets/css/styles.css', array(), STP_VERSION);

        // Enqueue GSAP for animations on single package pages (both travel and wedding)
        if (is_singular('travel_package') || is_singular('wedding_package')) {
            wp_enqueue_script('gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), '3.12.5', true);
            wp_enqueue_script('gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', array('gsap'), '3.12.5', true);
            wp_enqueue_script('stp-animations', STP_URL . 'assets/js/animations.js', array('jquery', 'gsap', 'gsap-scrolltrigger'), STP_VERSION, true);
        }
    }
    
    public function load_single_template($template) {
        if (is_singular('travel_package')) {
            $custom_template = STP_PATH . 'templates/single-travel-package.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        return $template;
    }
    
    public function ai_detect_category() {
        check_ajax_referer('stp_ai_category_nonce', 'nonce');
        
        $title = sanitize_text_field($_POST['title']);
        $description = sanitize_textarea_field($_POST['description']);
        
        // Simple AI-like keyword detection
        $text = strtolower($title . ' ' . $description);
        
        $categories = array(
            'Wedding' => array('wedding', 'bride', 'groom', 'marriage', 'sangeet', 'mehendi', 'haldi'),
            'Honeymoon' => array('honeymoon', 'romantic', 'couple', 'romance', 'newlywed'),
            'Adventure' => array('adventure', 'trek', 'hiking', 'safari', 'camping', 'climbing'),
            'Beach' => array('beach', 'ocean', 'sea', 'coast', 'island', 'maldives', 'goa'),
            'Heritage' => array('heritage', 'temple', 'fort', 'palace', 'historical', 'ancient', 'culture'),
            'Family' => array('family', 'kids', 'children', 'family-friendly'),
            'Luxury' => array('luxury', 'premium', 'deluxe', 'royal', '5-star'),
        );
        
        $detected = array();
        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($text, $keyword) !== false) {
                    // Check if category exists, if not create it
                    $term = term_exists($category, 'package_category');
                    if (!$term) {
                        $term = wp_insert_term($category, 'package_category');
                    }
                    if (!is_wp_error($term)) {
                        $detected[] = array(
                            'term_id' => $term['term_id'],
                            'name' => $category
                        );
                    }
                    break;
                }
            }
        }
        
        if (empty($detected)) {
            // Default to "General" if no category detected
            $term = term_exists('General', 'package_category');
            if (!$term) {
                $term = wp_insert_term('General', 'package_category');
            }
            if (!is_wp_error($term)) {
                $detected[] = array(
                    'term_id' => $term['term_id'],
                    'name' => 'General'
                );
            }
        }
        
        wp_send_json_success($detected);
    }
}

new Simple_Travel_Plugin();