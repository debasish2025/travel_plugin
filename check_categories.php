<?php
require_once('/home/user/travel_plugin/../../../wp-load.php');

echo "=== Checking Travel Packages ===\n";
$packages = get_posts(['post_type' => 'travel_package', 'posts_per_page' => 5, 'post_status' => 'any']);
echo "Total packages: " . count($packages) . "\n";
foreach ($packages as $pkg) {
    echo "- " . $pkg->post_title . " (Status: " . $pkg->post_status . ")\n";
}

echo "\n=== Checking Categories ===\n";
$categories = get_terms(['taxonomy' => 'package_category', 'hide_empty' => false]);
if (is_wp_error($categories)) {
    echo "Error: " . $categories->get_error_message() . "\n";
} else {
    echo "Total categories: " . count($categories) . "\n";
    foreach ($categories as $cat) {
        echo "- " . $cat->name . " (ID: " . $cat->term_id . ", Count: " . $cat->count . ")\n";
    }
}

echo "\n=== Testing Category Creation ===\n";
if (empty($categories)) {
    echo "No categories found. Creating sample categories...\n";
    $sample_categories = ['Beach Destinations', 'Mountain Trips', 'City Tours'];
    foreach ($sample_categories as $cat_name) {
        $result = wp_insert_term($cat_name, 'package_category');
        if (is_wp_error($result)) {
            echo "Failed to create '$cat_name': " . $result->get_error_message() . "\n";
        } else {
            echo "Created '$cat_name' successfully\n";
        }
    }
}
