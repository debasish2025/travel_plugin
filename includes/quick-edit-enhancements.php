<?php
/**
 * Quick Edit Enhancements for Travel and Wedding Packages
 * Makes Quick Edit compact and adds conditional category/region fields
 */

if (!defined('ABSPATH')) exit;

class Wedyara_Quick_Edit_Enhancements {

    public function __construct() {
        // Add custom quick edit fields
        add_action('quick_edit_custom_box', array($this, 'add_quick_edit_fields'), 10, 2);

        // Save quick edit data
        add_action('save_post', array($this, 'save_quick_edit_data'));

        // Add inline edit values
        add_action('manage_travel_package_posts_custom_column', array($this, 'add_inline_values'), 10, 2);
        add_action('manage_wedding_package_posts_custom_column', array($this, 'add_inline_values'), 10, 2);

        // Enqueue scripts for quick edit
        add_action('admin_footer-edit.php', array($this, 'enqueue_quick_edit_script'));

        // Add CSS for compact layout
        add_action('admin_head-edit.php', array($this, 'add_quick_edit_styles'));

        error_log('WEDYARA: Quick Edit enhancements loaded');
    }

    /**
     * Add custom quick edit fields
     */
    public function add_quick_edit_fields($column_name, $post_type) {
        if (!in_array($post_type, array('travel_package', 'wedding_package'))) {
            return;
        }

        // Only add once
        static $printed = false;
        if ($printed) {
            return;
        }
        $printed = true;

        // Determine taxonomies
        $is_wedding = ($post_type === 'wedding_package');
        $category_tax = $is_wedding ? 'wedding_category' : 'tpm_category';
        $region_tax = $is_wedding ? 'wedding_region' : 'tpm_region';

        // Get categories and regions
        $categories = get_terms(array('taxonomy' => $category_tax, 'hide_empty' => false));
        $regions = get_terms(array('taxonomy' => $region_tax, 'hide_empty' => false));

        ?>
        <fieldset class="inline-edit-col-left wedyara-quick-edit">
            <div class="inline-edit-col">
                <label class="inline-edit-group">
                    <span class="title">Category</span>
                    <select name="<?php echo esc_attr($category_tax); ?>[]" multiple="multiple" class="wedyara-category-select">
                        <?php foreach ($categories as $term) : ?>
                            <option value="<?php echo esc_attr($term->term_id); ?>">
                                <?php echo esc_html($term->name); ?> (<?php echo $term->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
        </fieldset>

        <fieldset class="inline-edit-col-right wedyara-quick-edit">
            <div class="inline-edit-col">
                <label class="inline-edit-group">
                    <span class="title">Region</span>
                    <select name="<?php echo esc_attr($region_tax); ?>[]" multiple="multiple" class="wedyara-region-select">
                        <?php foreach ($regions as $term) : ?>
                            <option value="<?php echo esc_attr($term->term_id); ?>">
                                <?php echo esc_html($term->name); ?> (<?php echo $term->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
        </fieldset>
        <?php
    }

    /**
     * Add inline values for JavaScript to populate
     */
    public function add_inline_values($column, $post_id) {
        // Only add once per post
        static $added = array();
        if (isset($added[$post_id])) {
            return;
        }
        $added[$post_id] = true;

        $post_type = get_post_type($post_id);
        $is_wedding = ($post_type === 'wedding_package');
        $category_tax = $is_wedding ? 'wedding_category' : 'tpm_category';
        $region_tax = $is_wedding ? 'wedding_region' : 'tpm_region';

        // Get current terms
        $categories = wp_get_post_terms($post_id, $category_tax, array('fields' => 'ids'));
        $regions = wp_get_post_terms($post_id, $region_tax, array('fields' => 'ids'));

        ?>
        <div class="hidden" id="wedyara_inline_<?php echo $post_id; ?>">
            <div class="wedyara_categories"><?php echo implode(',', $categories); ?></div>
            <div class="wedyara_regions"><?php echo implode(',', $regions); ?></div>
        </div>
        <?php
    }

    /**
     * Save quick edit data
     */
    public function save_quick_edit_data($post_id) {
        // Verify nonce and permissions
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $post_type = get_post_type($post_id);
        if (!in_array($post_type, array('travel_package', 'wedding_package'))) {
            return;
        }

        $is_wedding = ($post_type === 'wedding_package');
        $category_tax = $is_wedding ? 'wedding_category' : 'tpm_category';
        $region_tax = $is_wedding ? 'wedding_region' : 'tpm_region';

        // Save categories
        if (isset($_POST[$category_tax])) {
            $term_ids = array_map('intval', $_POST[$category_tax]);
            wp_set_post_terms($post_id, $term_ids, $category_tax);
        }

        // Save regions
        if (isset($_POST[$region_tax])) {
            $term_ids = array_map('intval', $_POST[$region_tax]);
            wp_set_post_terms($post_id, $term_ids, $region_tax);
        }
    }

    /**
     * Enqueue JavaScript for quick edit
     */
    public function enqueue_quick_edit_script() {
        global $typenow;
        if (!in_array($typenow, array('travel_package', 'wedding_package'))) {
            return;
        }

        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Populate quick edit fields
            var $wp_inline_edit = inlineEditPost.edit;
            inlineEditPost.edit = function(id) {
                $wp_inline_edit.apply(this, arguments);

                var post_id = 0;
                if (typeof(id) == 'object') {
                    post_id = parseInt(this.getId(id));
                }

                if (post_id > 0) {
                    var $inline_data = $('#wedyara_inline_' + post_id);

                    // Get categories and regions
                    var categories = $inline_data.find('.wedyara_categories').text();
                    var regions = $inline_data.find('.wedyara_regions').text();

                    // Set categories
                    if (categories) {
                        var cat_array = categories.split(',');
                        $('.wedyara-category-select option').prop('selected', false);
                        cat_array.forEach(function(cat_id) {
                            $('.wedyara-category-select option[value="' + cat_id + '"]').prop('selected', true);
                        });
                    }

                    // Set regions
                    if (regions) {
                        var region_array = regions.split(',');
                        $('.wedyara-region-select option').prop('selected', false);
                        region_array.forEach(function(region_id) {
                            $('.wedyara-region-select option[value="' + region_id + '"]').prop('selected', true);
                        });
                    }
                }
            };
        });
        </script>
        <?php
    }

    /**
     * Add CSS for compact quick edit layout
     */
    public function add_quick_edit_styles() {
        global $typenow;
        if (!in_array($typenow, array('travel_package', 'wedding_package'))) {
            return;
        }

        ?>
        <style>
            /* Compact Quick Edit Layout */
            .wedyara-quick-edit {
                width: 50% !important;
                float: left !important;
                clear: none !important;
            }

            .wedyara-quick-edit .inline-edit-col {
                padding: 10px !important;
            }

            .wedyara-quick-edit .inline-edit-group {
                margin-bottom: 10px !important;
            }

            .wedyara-quick-edit .title {
                display: inline-block;
                width: 80px;
                font-weight: 600;
            }

            .wedyara-quick-edit select {
                width: calc(100% - 90px) !important;
                max-width: 300px;
                height: 80px !important;
            }

            /* Make overall quick edit more compact */
            .inline-edit-row td {
                padding: 5px 10px !important;
            }

            .inline-edit-row fieldset {
                margin: 0 !important;
            }
        </style>
        <?php
    }
}

// Initialize
new Wedyara_Quick_Edit_Enhancements();
