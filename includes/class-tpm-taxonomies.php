<?php
/**
 * Simple Travel Plugin - Taxonomies
 * 
 * Handles registration of all custom taxonomies for Travel Packages
 * Adapted for 'travel_package' post type
 * 
 * @package SimpleTravelPlugin
 * @since 1.0.0
 */

class TPM_Taxonomies {

    /**
     * Initialize taxonomies
     */
    public function __construct() {
        add_action('init', array($this, 'register_taxonomies'), 20);
    }

    /**
     * Register all custom taxonomies
     */
    public function register_taxonomies() {
        $this->register_category();
        $this->register_region();
        $this->register_duration();
        $this->register_season();
        $this->register_difficulty_level();
        $this->register_package_type();
        $this->register_activity_type();
        $this->register_amenities();
        $this->register_tags();
    }

    /**
     * Register Category taxonomy
     * Applied to: travel_package
     */
    private function register_category() {
        $labels = array(
            'name'              => _x('Categories', 'taxonomy general name', 'simple-travel-plugin'),
            'singular_name'     => _x('Category', 'taxonomy singular name', 'simple-travel-plugin'),
            'search_items'      => __('Search Categories', 'simple-travel-plugin'),
            'all_items'         => __('All Categories', 'simple-travel-plugin'),
            'parent_item'       => __('Parent Category', 'simple-travel-plugin'),
            'parent_item_colon' => __('Parent Category:', 'simple-travel-plugin'),
            'edit_item'         => __('Edit Category', 'simple-travel-plugin'),
            'update_item'       => __('Update Category', 'simple-travel-plugin'),
            'add_new_item'      => __('Add New Category', 'simple-travel-plugin'),
            'new_item_name'     => __('New Category Name', 'simple-travel-plugin'),
            'menu_name'         => __('Categories', 'simple-travel-plugin'),
        );

        register_taxonomy('tpm_category', array('travel_package'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'category'),
        ));
    }

    /**
     * Register Region taxonomy
     * Applied to: travel_package
     */
    private function register_region() {
        $labels = array(
            'name'              => _x('Regions', 'taxonomy general name', 'simple-travel-plugin'),
            'singular_name'     => _x('Region', 'taxonomy singular name', 'simple-travel-plugin'),
            'search_items'      => __('Search Regions', 'simple-travel-plugin'),
            'all_items'         => __('All Regions', 'simple-travel-plugin'),
            'parent_item'       => __('Parent Region', 'simple-travel-plugin'),
            'parent_item_colon' => __('Parent Region:', 'simple-travel-plugin'),
            'edit_item'         => __('Edit Region', 'simple-travel-plugin'),
            'update_item'       => __('Update Region', 'simple-travel-plugin'),
            'add_new_item'      => __('Add New Region', 'simple-travel-plugin'),
            'new_item_name'     => __('New Region Name', 'simple-travel-plugin'),
            'menu_name'         => __('Regions', 'simple-travel-plugin'),
        );

        register_taxonomy('tpm_region', array('travel_package'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'region'),
        ));
    }

    /**
     * Register Duration taxonomy
     * Applied to: travel_package
     */
    private function register_duration() {
        $labels = array(
            'name'              => _x('Durations', 'taxonomy general name', 'simple-travel-plugin'),
            'singular_name'     => _x('Duration', 'taxonomy singular name', 'simple-travel-plugin'),
            'search_items'      => __('Search Durations', 'simple-travel-plugin'),
            'all_items'         => __('All Durations', 'simple-travel-plugin'),
            'edit_item'         => __('Edit Duration', 'simple-travel-plugin'),
            'update_item'       => __('Update Duration', 'simple-travel-plugin'),
            'add_new_item'      => __('Add New Duration', 'simple-travel-plugin'),
            'new_item_name'     => __('New Duration Name', 'simple-travel-plugin'),
            'menu_name'         => __('Durations', 'simple-travel-plugin'),
        );

        register_taxonomy('tpm_duration', array('travel_package'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'duration'),
        ));
    }

    /**
     * Register Season taxonomy
     * Applied to: travel_package
     */
    private function register_season() {
        $labels = array(
            'name'              => _x('Seasons', 'taxonomy general name', 'simple-travel-plugin'),
            'singular_name'     => _x('Season', 'taxonomy singular name', 'simple-travel-plugin'),
            'search_items'      => __('Search Seasons', 'simple-travel-plugin'),
            'all_items'         => __('All Seasons', 'simple-travel-plugin'),
            'edit_item'         => __('Edit Season', 'simple-travel-plugin'),
            'update_item'       => __('Update Season', 'simple-travel-plugin'),
            'add_new_item'      => __('Add New Season', 'simple-travel-plugin'),
            'new_item_name'     => __('New Season Name', 'simple-travel-plugin'),
            'menu_name'         => __('Seasons', 'simple-travel-plugin'),
        );

        register_taxonomy('tpm_season', array('travel_package'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'season'),
        ));
    }

    /**
     * Register Difficulty Level taxonomy
     * Applied to: travel_package
     */
    private function register_difficulty_level() {
        $labels = array(
            'name'              => _x('Difficulty Levels', 'taxonomy general name', 'simple-travel-plugin'),
            'singular_name'     => _x('Difficulty Level', 'taxonomy singular name', 'simple-travel-plugin'),
            'search_items'      => __('Search Difficulty Levels', 'simple-travel-plugin'),
            'all_items'         => __('All Difficulty Levels', 'simple-travel-plugin'),
            'edit_item'         => __('Edit Difficulty Level', 'simple-travel-plugin'),
            'update_item'       => __('Update Difficulty Level', 'simple-travel-plugin'),
            'add_new_item'      => __('Add New Difficulty Level', 'simple-travel-plugin'),
            'new_item_name'     => __('New Difficulty Level Name', 'simple-travel-plugin'),
            'menu_name'         => __('Difficulty Levels', 'simple-travel-plugin'),
        );

        register_taxonomy('tpm_difficulty', array('travel_package'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'difficulty'),
        ));
    }

    /**
     * Register Package Type taxonomy
     * Applied to: travel_package
     */
    private function register_package_type() {
        $labels = array(
            'name'              => _x('Package Types', 'taxonomy general name', 'simple-travel-plugin'),
            'singular_name'     => _x('Package Type', 'taxonomy singular name', 'simple-travel-plugin'),
            'search_items'      => __('Search Package Types', 'simple-travel-plugin'),
            'all_items'         => __('All Package Types', 'simple-travel-plugin'),
            'edit_item'         => __('Edit Package Type', 'simple-travel-plugin'),
            'update_item'       => __('Update Package Type', 'simple-travel-plugin'),
            'add_new_item'      => __('Add New Package Type', 'simple-travel-plugin'),
            'new_item_name'     => __('New Package Type Name', 'simple-travel-plugin'),
            'menu_name'         => __('Package Types', 'simple-travel-plugin'),
        );

        register_taxonomy('tpm_package_type', array('travel_package'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'package-type'),
        ));
    }

    /**
     * Register Activity Type taxonomy
     * Applied to: travel_package
     */
    private function register_activity_type() {
        $labels = array(
            'name'              => _x('Activity Types', 'taxonomy general name', 'simple-travel-plugin'),
            'singular_name'     => _x('Activity Type', 'taxonomy singular name', 'simple-travel-plugin'),
            'search_items'      => __('Search Activity Types', 'simple-travel-plugin'),
            'all_items'         => __('All Activity Types', 'simple-travel-plugin'),
            'parent_item'       => __('Parent Activity Type', 'simple-travel-plugin'),
            'parent_item_colon' => __('Parent Activity Type:', 'simple-travel-plugin'),
            'edit_item'         => __('Edit Activity Type', 'simple-travel-plugin'),
            'update_item'       => __('Update Activity Type', 'simple-travel-plugin'),
            'add_new_item'      => __('Add New Activity Type', 'simple-travel-plugin'),
            'new_item_name'     => __('New Activity Type Name', 'simple-travel-plugin'),
            'menu_name'         => __('Activity Types', 'simple-travel-plugin'),
        );

        register_taxonomy('tpm_activity', array('travel_package'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'activity'),
        ));
    }

    /**
     * Register Amenities taxonomy
     * Applied to: travel_package
     */
    private function register_amenities() {
        $labels = array(
            'name'              => _x('Amenities', 'taxonomy general name', 'simple-travel-plugin'),
            'singular_name'     => _x('Amenity', 'taxonomy singular name', 'simple-travel-plugin'),
            'search_items'      => __('Search Amenities', 'simple-travel-plugin'),
            'all_items'         => __('All Amenities', 'simple-travel-plugin'),
            'parent_item'       => __('Parent Amenity', 'simple-travel-plugin'),
            'parent_item_colon' => __('Parent Amenity:', 'simple-travel-plugin'),
            'edit_item'         => __('Edit Amenity', 'simple-travel-plugin'),
            'update_item'       => __('Update Amenity', 'simple-travel-plugin'),
            'add_new_item'      => __('Add New Amenity', 'simple-travel-plugin'),
            'new_item_name'     => __('New Amenity Name', 'simple-travel-plugin'),
            'menu_name'         => __('Amenities', 'simple-travel-plugin'),
        );

        register_taxonomy('tpm_amenity', array('travel_package'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => false,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'amenity'),
        ));
    }

    /**
     * Register Tags taxonomy (non-hierarchical)
     * Applied to: travel_package
     */
    private function register_tags() {
        $labels = array(
            'name'                       => _x('Tags', 'taxonomy general name', 'simple-travel-plugin'),
            'singular_name'              => _x('Tag', 'taxonomy singular name', 'simple-travel-plugin'),
            'search_items'               => __('Search Tags', 'simple-travel-plugin'),
            'popular_items'              => __('Popular Tags', 'simple-travel-plugin'),
            'all_items'                  => __('All Tags', 'simple-travel-plugin'),
            'edit_item'                  => __('Edit Tag', 'simple-travel-plugin'),
            'update_item'                => __('Update Tag', 'simple-travel-plugin'),
            'add_new_item'               => __('Add New Tag', 'simple-travel-plugin'),
            'new_item_name'              => __('New Tag Name', 'simple-travel-plugin'),
            'separate_items_with_commas' => __('Separate tags with commas', 'simple-travel-plugin'),
            'add_or_remove_items'        => __('Add or remove tags', 'simple-travel-plugin'),
            'choose_from_most_used'      => __('Choose from the most used tags', 'simple-travel-plugin'),
            'not_found'                  => __('No tags found.', 'simple-travel-plugin'),
            'menu_name'                  => __('Tags', 'simple-travel-plugin'),
        );

        register_taxonomy('tpm_tag', array('travel_package'), array(
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'tag'),
        ));
    }
}

// Initialize taxonomies
new TPM_Taxonomies();