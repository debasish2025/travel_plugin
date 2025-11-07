<?php
/**
 * Admin List Filters for Travel and Wedding Packages
 * Adds category and region filters to admin list pages
 */

if (!defined('ABSPATH')) exit;

class Wedyara_Admin_Filters {

    public function __construct() {
        // Add filters to admin list pages
        add_action('restrict_manage_posts', array($this, 'add_admin_filters'));
        add_filter('parse_query', array($this, 'filter_admin_query'));

        error_log('WEDYARA: Admin Filters module loaded');
    }

    /**
     * Add filter dropdowns to admin list pages
     */
    public function add_admin_filters($post_type) {
        // Only add filters for travel_package and wedding_package
        if (!in_array($post_type, array('travel_package', 'wedding_package'))) {
            return;
        }

        // Determine taxonomies based on post type
        if ($post_type === 'wedding_package') {
            $category_taxonomy = 'wedding_category';
            $region_taxonomy = 'wedding_region';
        } else {
            $category_taxonomy = 'tpm_category';
            $region_taxonomy = 'tpm_region';
        }

        // Category Filter
        $this->render_taxonomy_filter($category_taxonomy, 'Category');

        // Region Filter
        $this->render_taxonomy_filter($region_taxonomy, 'Region');
    }

    /**
     * Render a taxonomy filter dropdown
     */
    private function render_taxonomy_filter($taxonomy, $label) {
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ));

        if (empty($terms) || is_wp_error($terms)) {
            return;
        }

        // Get currently selected value
        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';

        ?>
        <select name="<?php echo esc_attr($taxonomy); ?>" id="<?php echo esc_attr($taxonomy); ?>">
            <option value="">All <?php echo esc_html($label); ?>s</option>
            <?php foreach ($terms as $term) : ?>
                <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($selected, $term->slug); ?>>
                    <?php echo esc_html($term->name); ?> (<?php echo $term->count; ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    /**
     * Filter admin query based on selected filters
     */
    public function filter_admin_query($query) {
        global $pagenow, $typenow;

        // Only filter on admin list pages
        if ($pagenow !== 'edit.php' || !is_admin()) {
            return $query;
        }

        // Only for our post types
        if (!in_array($typenow, array('travel_package', 'wedding_package'))) {
            return $query;
        }

        // Determine taxonomies
        if ($typenow === 'wedding_package') {
            $taxonomies = array('wedding_category', 'wedding_region');
        } else {
            $taxonomies = array('tpm_category', 'tpm_region');
        }

        // Apply taxonomy filters
        foreach ($taxonomies as $taxonomy) {
            if (isset($_GET[$taxonomy]) && $_GET[$taxonomy] !== '') {
                $query->query_vars['tax_query'][] = array(
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET[$taxonomy]),
                );
            }
        }

        return $query;
    }
}

// Initialize
new Wedyara_Admin_Filters();
