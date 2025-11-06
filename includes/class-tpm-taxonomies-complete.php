<?php
/**
 * Travel Packages Manager - Taxonomies
 * 
 * Handles registration of all custom taxonomies
 * 
 * @package TravelPackagesManager
 * @since 1.0.0
 */

class TPM_Taxonomies {

    /**
     * Initialize taxonomies
     */
    public function __construct() {
        add_action('init', array($this, 'register_taxonomies'));
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
     */
    private function register_category() {
        $labels = array(
            'name'              => _x('Categories', 'taxonomy general name', 'travel-packages-manager'),
            'singular_name'     => _x('Category', 'taxonomy singular name', 'travel-packages-manager'),
            'search_items'      => __('Search Categories', 'travel-packages-manager'),
            'all_items'         => __('All Categories', 'travel-packages-manager'),
            'parent_item'       => __('Parent Category', 'travel-packages-manager'),
            'parent_item_colon' => __('Parent Category:', 'travel-packages-manager'),
            'edit_item'         => __('Edit Category', 'travel-packages-manager'),
            'update_item'       => __('Update Category', 'travel-packages-manager'),
            'add_new_item'      => __('Add New Category', 'travel-packages-manager'),
            'new_item_name'     => __('New Category Name', 'travel-packages-manager'),
            'menu_name'         => __('Categories', 'travel-packages-manager'),
        );

        register_taxonomy('tpm_category', array('tpm_destination', 'tpm_package'), array(
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
     */
    private function register_region() {
        $labels = array(
            'name'              => _x('Regions', 'taxonomy general name', 'travel-packages-manager'),
            'singular_name'     => _x('Region', 'taxonomy singular name', 'travel-packages-manager'),
            'search_items'      => __('Search Regions', 'travel-packages-manager'),
            'all_items'         => __('All Regions', 'travel-packages-manager'),
            'parent_item'       => __('Parent Region', 'travel-packages-manager'),
            'parent_item_colon' => __('Parent Region:', 'travel-packages-manager'),
            'edit_item'         => __('Edit Region', 'travel-packages-manager'),
            'update_item'       => __('Update Region', 'travel-packages-manager'),
            'add_new_item'      => __('Add New Region', 'travel-packages-manager'),
            'new_item_name'     => __('New Region Name', 'travel-packages-manager'),
            'menu_name'         => __('Regions', 'travel-packages-manager'),
        );

        register_taxonomy('tpm_region', array('tpm_destination', 'tpm_package'), array(
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
     */
    private function register_duration() {
        $labels = array(
            'name'              => _x('Durations', 'taxonomy general name', 'travel-packages-manager'),
            'singular_name'     => _x('Duration', 'taxonomy singular name', 'travel-packages-manager'),
            'search_items'      => __('Search Durations', 'travel-packages-manager'),
            'all_items'         => __('All Durations', 'travel-packages-manager'),
            'edit_item'         => __('Edit Duration', 'travel-packages-manager'),
            'update_item'       => __('Update Duration', 'travel-packages-manager'),
            'add_new_item'      => __('Add New Duration', 'travel-packages-manager'),
            'new_item_name'     => __('New Duration Name', 'travel-packages-manager'),
            'menu_name'         => __('Durations', 'travel-packages-manager'),
        );

        register_taxonomy('tpm_duration', array('tpm_package'), array(
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
     */
    private function register_season() {
        $labels = array(
            'name'              => _x('Seasons', 'taxonomy general name', 'travel-packages-manager'),
            'singular_name'     => _x('Season', 'taxonomy singular name', 'travel-packages-manager'),
            'search_items'      => __('Search Seasons', 'travel-packages-manager'),
            'all_items'         => __('All Seasons', 'travel-packages-manager'),
            'edit_item'         => __('Edit Season', 'travel-packages-manager'),
            'update_item'       => __('Update Season', 'travel-packages-manager'),
            'add_new_item'      => __('Add New Season', 'travel-packages-manager'),
            'new_item_name'     => __('New Season Name', 'travel-packages-manager'),
            'menu_name'         => __('Seasons', 'travel-packages-manager'),
        );

        register_taxonomy('tpm_season', array('tpm_destination', 'tpm_package'), array(
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
     */
    private function register_difficulty_level() {
        $labels = array(
            'name'              => _x('Difficulty Levels', 'taxonomy general name', 'travel-packages-manager'),
            'singular_name'     => _x('Difficulty Level', 'taxonomy singular name', 'travel-packages-manager'),
            'search_items'      => __('Search Difficulty Levels', 'travel-packages-manager'),
            'all_items'         => __('All Difficulty Levels', 'travel-packages-manager'),
            'edit_item'         => __('Edit Difficulty Level', 'travel-packages-manager'),
            'update_item'       => __('Update Difficulty Level', 'travel-packages-manager'),
            'add_new_item'      => __('Add New Difficulty Level', 'travel-packages-manager'),
            'new_item_name'     => __('New Difficulty Level Name', 'travel-packages-manager'),
            'menu_name'         => __('Difficulty Levels', 'travel-packages-manager'),
        );

        register_taxonomy('tpm_difficulty', array('tpm_destination', 'tpm_package'), array(
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
     */
    private function register_package_type() {
        $labels = array(
            'name'              => _x('Package Types', 'taxonomy general name', 'travel-packages-manager'),
            'singular_name'     => _x('Package Type', 'taxonomy singular name', 'travel-packages-manager'),
            'search_items'      => __('Search Package Types', 'travel-packages-manager'),
            'all_items'         => __('All Package Types', 'travel-packages-manager'),
            'edit_item'         => __('Edit Package Type', 'travel-packages-manager'),
            'update_item'       => __('Update Package Type', 'travel-packages-manager'),
            'add_new_item'      => __('Add New Package Type', 'travel-packages-manager'),
            'new_item_name'     => __('New Package Type Name', 'travel-packages-manager'),
            'menu_name'         => __('Package Types', 'travel-packages-manager'),
        );

        register_taxonomy('tpm_package_type', array('tpm_package'), array(
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
     */
    private function register_activity_type() {
        $labels = array(
            'name'              => _x('Activity Types', 'taxonomy general name', 'travel-packages-manager'),
            'singular_name'     => _x('Activity Type', 'taxonomy singular name', 'travel-packages-manager'),
            'search_items'      => __('Search Activity Types', 'travel-packages-manager'),
            'all_items'         => __('All Activity Types', 'travel-packages-manager'),
            'parent_item'       => __('Parent Activity Type', 'travel-packages-manager'),
            'parent_item_colon' => __('Parent Activity Type:', 'travel-packages-manager'),
            'edit_item'         => __('Edit Activity Type', 'travel-packages-manager'),
            'update_item'       => __('Update Activity Type', 'travel-packages-manager'),
            'add_new_item'      => __('Add New Activity Type', 'travel-packages-manager'),
            'new_item_name'     => __('New Activity Type Name', 'travel-packages-manager'),
            'menu_name'         => __('Activity Types', 'travel-packages-manager'),
        );

        register_taxonomy('tpm_activity', array('tpm_destination', 'tpm_package'), array(
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
     */
    private function register_amenities() {
        $labels = array(
            'name'              => _x('Amenities', 'taxonomy general name', 'travel-packages-manager'),
            'singular_name'     => _x('Amenity', 'taxonomy singular name', 'travel-packages-manager'),
            'search_items'      => __('Search Amenities', 'travel-packages-manager'),
            'all_items'         => __('All Amenities', 'travel-packages-manager'),
            'parent_item'       => __('Parent Amenity', 'travel-packages-manager'),
            'parent_item_colon' => __('Parent Amenity:', 'travel-packages-manager'),
            'edit_item'         => __('Edit Amenity', 'travel-packages-manager'),
            'update_item'       => __('Update Amenity', 'travel-packages-manager'),
            'add_new_item'      => __('Add New Amenity', 'travel-packages-manager'),
            'new_item_name'     => __('New Amenity Name', 'travel-packages-manager'),
            'menu_name'         => __('Amenities', 'travel-packages-manager'),
        );

        register_taxonomy('tpm_amenity', array('tpm_destination', 'tpm_package'), array(
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
     */
    private function register_tags() {
        $labels = array(
            'name'                       => _x('Tags', 'taxonomy general name', 'travel-packages-manager'),
            'singular_name'              => _x('Tag', 'taxonomy singular name', 'travel-packages-manager'),
            'search_items'               => __('Search Tags', 'travel-packages-manager'),
            'popular_items'              => __('Popular Tags', 'travel-packages-manager'),
            'all_items'                  => __('All Tags', 'travel-packages-manager'),
            'edit_item'                  => __('Edit Tag', 'travel-packages-manager'),
            'update_item'                => __('Update Tag', 'travel-packages-manager'),
            'add_new_item'               => __('Add New Tag', 'travel-packages-manager'),
            'new_item_name'              => __('New Tag Name', 'travel-packages-manager'),
            'separate_items_with_commas' => __('Separate tags with commas', 'travel-packages-manager'),
            'add_or_remove_items'        => __('Add or remove tags', 'travel-packages-manager'),
            'choose_from_most_used'      => __('Choose from the most used tags', 'travel-packages-manager'),
            'not_found'                  => __('No tags found.', 'travel-packages-manager'),
            'menu_name'                  => __('Tags', 'travel-packages-manager'),
        );

        register_taxonomy('tpm_tag', array('tpm_destination', 'tpm_package'), array(
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
