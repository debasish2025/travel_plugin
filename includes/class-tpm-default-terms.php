<?php
/**
 * Travel Packages Manager - Default Terms Installer
 * 
 * Automatically adds default terms for all taxonomies
 * 
 * @package TravelPackagesManager
 * @since 1.0.0
 */

class TPM_Default_Terms {
    
    /**
     * Initialize default terms installer
     */
    public function __construct() {
        add_action('init', array($this, 'add_default_terms'), 20);
    }
    
    /**
     * Add default terms to all taxonomies
     * Runs only once
     */
    public function add_default_terms() {
        // Only run once
        if (get_option('tpm_default_terms_added') === 'yes') {
            return;
        }
        
        $this->add_categories();
        $this->add_regions();
        $this->add_durations();
        $this->add_seasons();
        $this->add_difficulty_levels();
        $this->add_package_types();
        $this->add_activity_types();
        $this->add_amenities();
        
        // Mark as completed
        update_option('tpm_default_terms_added', 'yes');
    }
    
    /**
     * Add default categories
     */
    private function add_categories() {
        $categories = array(
            'Adventure Travel',
            'Beach & Islands',
            'Cultural Tours',
            'Pilgrimage',
            'Wildlife & Nature',
            'Hill Stations',
            'Heritage Sites',
            'Backwaters',
            'Desert Safari',
            'City Tours'
        );
        
        foreach ($categories as $category) {
            if (!term_exists($category, 'tpm_category')) {
                wp_insert_term($category, 'tpm_category');
            }
        }
    }
    
    /**
     * Add default regions with hierarchy
     */
    private function add_regions() {
        $regions = array(
            'North India' => array(
                'Himachal Pradesh',
                'Uttarakhand',
                'Jammu & Kashmir',
                'Punjab',
                'Haryana'
            ),
            'South India' => array(
                'Kerala',
                'Tamil Nadu',
                'Karnataka',
                'Andhra Pradesh',
                'Telangana'
            ),
            'East India' => array(
                'West Bengal',
                'Odisha',
                'Sikkim',
                'Assam'
            ),
            'West India' => array(
                'Rajasthan',
                'Gujarat',
                'Goa',
                'Maharashtra'
            ),
            'Central India' => array(
                'Madhya Pradesh',
                'Chhattisgarh'
            )
        );
        
        foreach ($regions as $parent => $children) {
            // Add parent region
            $parent_term = term_exists($parent, 'tpm_region');
            if (!$parent_term) {
                $parent_term = wp_insert_term($parent, 'tpm_region');
            }
            
            // Add child regions
            if (!is_wp_error($parent_term)) {
                $parent_id = $parent_term['term_id'];
                foreach ($children as $child) {
                    if (!term_exists($child, 'tpm_region')) {
                        wp_insert_term($child, 'tpm_region', array(
                            'parent' => $parent_id
                        ));
                    }
                }
            }
        }
    }
    
    /**
     * Add default durations
     */
    private function add_durations() {
        $durations = array(
            'Weekend Getaway (2-3 Days)',
            'Short Break (4-6 Days)',
            'One Week (7-9 Days)',
            'Extended Tour (10-14 Days)',
            'Long Journey (15+ Days)'
        );
        
        foreach ($durations as $duration) {
            if (!term_exists($duration, 'tpm_duration')) {
                wp_insert_term($duration, 'tpm_duration');
            }
        }
    }
    
    /**
     * Add default seasons
     */
    private function add_seasons() {
        $seasons = array(
            'Summer (March - June)',
            'Monsoon (July - September)',
            'Winter (October - February)',
            'All Year Round',
            'Peak Season',
            'Off Season'
        );
        
        foreach ($seasons as $season) {
            if (!term_exists($season, 'tpm_season')) {
                wp_insert_term($season, 'tpm_season');
            }
        }
    }
    
    /**
     * Add default difficulty levels
     */
    private function add_difficulty_levels() {
        $difficulties = array(
            'Easy' => 'Suitable for all ages and fitness levels',
            'Moderate' => 'Average fitness required, some walking involved',
            'Challenging' => 'Good fitness required, extended activities',
            'Difficult' => 'High fitness level required, strenuous activities',
            'Expert' => 'Professional level fitness and experience required'
        );
        
        foreach ($difficulties as $name => $description) {
            if (!term_exists($name, 'tpm_difficulty')) {
                wp_insert_term($name, 'tpm_difficulty', array(
                    'description' => $description
                ));
            }
        }
    }
    
    /**
     * Add default package types
     */
    private function add_package_types() {
        $package_types = array(
            'Honeymoon Packages',
            'Family Packages',
            'Group Tours',
            'Solo Travel',
            'Budget Tours',
            'Luxury Tours',
            'Corporate Tours',
            'Student Tours',
            'Senior Citizen Tours',
            'Customized Tours'
        );
        
        foreach ($package_types as $type) {
            if (!term_exists($type, 'tpm_package_type')) {
                wp_insert_term($type, 'tpm_package_type');
            }
        }
    }
    
    /**
     * Add default activity types with hierarchy
     */
    private function add_activity_types() {
        $activities = array(
            'Trekking',
            'Wildlife Safari',
            'Water Sports' => array(
                'Scuba Diving',
                'Snorkeling',
                'Rafting',
                'Kayaking'
            ),
            'Photography Tours',
            'Yoga & Meditation',
            'Adventure Sports' => array(
                'Paragliding',
                'Rock Climbing',
                'Zip Lining',
                'Bungee Jumping'
            ),
            'Sightseeing',
            'Food Tours',
            'Shopping Tours'
        );
        
        foreach ($activities as $key => $value) {
            if (is_array($value)) {
                // Parent with children
                $parent_term = term_exists($key, 'tpm_activity');
                if (!$parent_term) {
                    $parent_term = wp_insert_term($key, 'tpm_activity');
                }
                
                if (!is_wp_error($parent_term)) {
                    $parent_id = $parent_term['term_id'];
                    foreach ($value as $child) {
                        if (!term_exists($child, 'tpm_activity')) {
                            wp_insert_term($child, 'tpm_activity', array(
                                'parent' => $parent_id
                            ));
                        }
                    }
                }
            } else {
                // Simple term
                if (!term_exists($value, 'tpm_activity')) {
                    wp_insert_term($value, 'tpm_activity');
                }
            }
        }
    }
    
    /**
     * Add default amenities with hierarchy
     */
    private function add_amenities() {
        $amenities = array(
            'Accommodation' => array(
                '3-Star Hotel',
                '4-Star Hotel',
                '5-Star Hotel',
                'Resort',
                'Homestay',
                'Camping'
            ),
            'Transportation' => array(
                'AC Vehicle',
                'Train Tickets',
                'Flight Tickets',
                'Private Car'
            ),
            'Meals' => array(
                'Breakfast',
                'Lunch',
                'Dinner',
                'All Meals Included'
            ),
            'Services' => array(
                'Tour Guide',
                'Porter Service',
                'Travel Insurance',
                'Medical Support',
                '24/7 Support'
            ),
            'Facilities' => array(
                'WiFi',
                'Parking',
                'Pick-up/Drop-off',
                'Airport Transfer'
            )
        );
        
        foreach ($amenities as $parent => $children) {
            // Add parent amenity
            $parent_term = term_exists($parent, 'tpm_amenity');
            if (!$parent_term) {
                $parent_term = wp_insert_term($parent, 'tpm_amenity');
            }
            
            // Add child amenities
            if (!is_wp_error($parent_term)) {
                $parent_id = $parent_term['term_id'];
                foreach ($children as $child) {
                    if (!term_exists($child, 'tpm_amenity')) {
                        wp_insert_term($child, 'tpm_amenity', array(
                            'parent' => $parent_id
                        ));
                    }
                }
            }
        }
    }
    
    /**
     * Reset all default terms (for testing)
     * Call this function to re-add all terms
     */
    public function reset_default_terms() {
        delete_option('tpm_default_terms_added');
        $this->add_default_terms();
    }
}

// Initialize default terms installer
new TPM_Default_Terms();