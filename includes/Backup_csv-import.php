<?php
/**
 * Fixed CSV Import for Travel Packages
 * 
 * Fixes:
 * 1. Critical error on import
 * 2. Sample CSV download not working
 * 
 * SAFE: Won't touch existing data, only adds import functionality
 */

// Add CSV import menu page
add_action('admin_menu', 'stp_csv_import_menu_fixed', 20);

function stp_csv_import_menu_fixed() {
    add_submenu_page(
        'edit.php?post_type=travel_package',
        'Import CSV',
        'Import CSV',
        'manage_options',
        'stp-csv-import-fixed',
        'stp_csv_import_page_fixed'
    );
}

function stp_csv_import_page_fixed() {
    // Handle sample CSV download
    if (isset($_GET['action']) && $_GET['action'] === 'download_sample' && check_admin_referer('download_sample_csv', 'sample_nonce')) {
        stp_download_sample_csv_fixed();
        exit;
    }
    
    ?>
    <div class="wrap">
        <h1>Import Travel Packages (CSV)</h1>
        
        <?php
        // Handle CSV import
        if (isset($_POST['stp_import_csv_fixed'])) {
            check_admin_referer('stp_csv_import_fixed');
            
            if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === 0) {
                $result = stp_process_csv_import_safe($_FILES['csv_file']['tmp_name']);
                
                if ($result['success']) {
                    echo '<div class="notice notice-success is-dismissible"><p>';
                    echo '<strong>✓ Import Complete!</strong><br>';
                    echo 'Successfully imported <strong>' . $result['imported'] . '</strong> packages.';
                    if (!empty($result['errors'])) {
                        echo '<br><br><strong>Minor issues (skipped):</strong><ul>';
                        foreach ($result['errors'] as $error) {
                            echo '<li>' . esc_html($error) . '</li>';
                        }
                        echo '</ul>';
                    }
                    echo '</p></div>';
                } else {
                    echo '<div class="notice notice-error is-dismissible"><p>';
                    echo '<strong>✗ Import Failed:</strong> ' . esc_html($result['message']);
                    echo '</p></div>';
                }
            } else {
                echo '<div class="notice notice-error is-dismissible"><p>';
                echo '<strong>✗ Error:</strong> Please upload a valid CSV file.';
                echo '</p></div>';
            }
        }
        ?>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>📥 Import Packages from CSV</h2>
            
            <!-- Sample CSV Download Button (FIXED) -->
            <div style="background: #e7f5fe; padding: 15px; border-left: 4px solid #0073aa; margin-bottom: 20px;">
                <h3 style="margin-top: 0;">📄 Download Sample CSV Template</h3>
                <p>Download our sample CSV file to see the correct format with examples.</p>
                <?php
                $download_url = wp_nonce_url(
                    add_query_arg(array(
                        'action' => 'download_sample',
                        'page' => 'stp-csv-import-fixed',
                        'post_type' => 'travel_package'
                    ), admin_url('edit.php')),
                    'download_sample_csv',
                    'sample_nonce'
                );
                ?>
                <a href="<?php echo esc_url($download_url); ?>" class="button button-primary button-large">
                    <span class="dashicons dashicons-download" style="vertical-align: middle;"></span>
                    Download Sample CSV
                </a>
            </div>
            
            <!-- Upload Form -->
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('stp_csv_import_fixed'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="csv_file">Select CSV File</label></th>
                        <td>
                            <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                            <p class="description">Upload your CSV file with travel packages data.</p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" name="stp_import_csv_fixed" class="button button-primary button-large">
                        <span class="dashicons dashicons-upload" style="vertical-align: middle;"></span>
                        Import Packages
                    </button>
                </p>
            </form>
        </div>
        
        <!-- Instructions -->
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>📋 CSV Format Instructions</h2>
            
            <h3>Required Columns:</h3>
            <ul>
                <li><strong>title</strong> - Package name</li>
                <li><strong>description</strong> - Full description</li>
                <li><strong>subtitle</strong> - Package tagline</li>
                <li><strong>days</strong> - Number of days</li>
                <li><strong>nights</strong> - Number of nights</li>
            </ul>
            
            <h3>Taxonomy Columns:</h3>
            <ul>
                <li><strong>category</strong> - Heritage Sites, Beach & Islands, etc.</li>
                <li><strong>region</strong> - North India, South India, etc.</li>
                <li><strong>duration</strong> - Weekend Getaway, Week-Long Trip, etc.</li>
                <li><strong>season</strong> - Summer, Monsoon, Winter, All Year Round</li>
                <li><strong>difficulty</strong> - Easy, Moderate, Challenging, Difficult</li>
                <li><strong>package_type</strong> - Honeymoon, Family, Cultural Tours, etc.</li>
                <li><strong>activity_type</strong> - Sightseeing, Water Sports, Trekking, etc.</li>
                <li><strong>amenities</strong> - Hotels, Meals, Transport (comma-separated)</li>
                <li><strong>tags</strong> - romantic, family-friendly (comma-separated)</li>
            </ul>
            
            <h3>Itinerary Columns (up to 10 days):</h3>
            <ul>
                <li><strong>day1_title</strong>, <strong>day1_activities</strong></li>
                <li><strong>day2_title</strong>, <strong>day2_activities</strong></li>
                <li>... (up to day10)</li>
            </ul>
            
            <p><strong>Note:</strong> Activities can be separated by pipe character (|) for multiple activities per day.</p>
            
            <h3>⚠️ Important Notes:</h3>
            <ul>
                <li>✅ All packages imported as <strong>drafts</strong> (safe - won't publish automatically)</li>
                <li>✅ Existing packages won't be affected</li>
                <li>✅ Taxonomy terms auto-created if they don't exist</li>
                <li>✅ Test with 1-2 rows first before bulk import</li>
            </ul>
        </div>
    </div>
    
    <style>
    .card h2 { margin-top: 0; }
    .card h3 { margin-top: 20px; color: #23282d; }
    .card ul { margin-bottom: 15px; }
    .dashicons { line-height: inherit; }
    </style>
    <?php
}

function stp_download_sample_csv_fixed() {
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=travel-packages-sample.csv');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Add BOM for Excel UTF-8 support
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Add header row
    fputcsv($output, array(
        'title',
        'description',
        'subtitle',
        'days',
        'nights',
        'category',
        'region',
        'duration',
        'season',
        'difficulty',
        'package_type',
        'activity_type',
        'amenities',
        'tags',
        'day1_title',
        'day1_activities',
        'day2_title',
        'day2_activities',
        'day3_title',
        'day3_activities',
        'day4_title',
        'day4_activities'
    ));
    
    // Add sample data row 1
    fputcsv($output, array(
        'Goa Beach Paradise',
        'Experience the perfect blend of sun, sand, and sea in beautiful Goa. Enjoy pristine beaches, water sports, heritage sites, and vibrant nightlife. Perfect for families and couples.',
        'Sun, Sand & Sea Paradise',
        '4',
        '3',
        'Beach & Islands',
        'West India',
        'Weekend Getaway',
        'All Year Round',
        'Easy',
        'Family',
        'Water Sports',
        'Hotels, Meals, Transport, Tour Guide, WiFi',
        'romantic, beaches, water-sports, family-friendly',
        'Arrival & North Goa Beaches',
        'Arrival at Goa Airport|Traditional welcome and check-in|Visit Calangute Beach and Baga Beach|Enjoy water sports|Sunset at Anjuna Beach|Seafood dinner at beach shack',
        'Heritage & Culture Tour',
        'Breakfast at hotel|Visit Old Goa Churches (UNESCO sites)|Explore Fontainhas Latin Quarter|Mandovi River cruise|Visit Dona Paula Jetty|Shopping at local markets',
        'South Goa Serenity',
        'Drive to South Goa|Visit Colva Beach and Palolem Beach|Spice plantation tour with lunch|Relax at Majorda Beach|Candlelight dinner by the sea',
        'Departure',
        'Breakfast at hotel|Free time for spa or beach|Last-minute shopping|Check-out and transfer to airport'
    ));
    
    // Add sample data row 2
    fputcsv($output, array(
        'Jaipur Heritage Tour',
        'Explore the royal palaces, magnificent forts, and vibrant bazaars of the Pink City. Experience the rich culture and heritage of Rajasthan.',
        'Royal Rajasthan Experience',
        '3',
        '2',
        'Heritage Sites',
        'North India',
        'Weekend Getaway',
        'Winter',
        'Easy',
        'Cultural Tours',
        'Sightseeing',
        'Hotels, Meals, Transport, Tour Guide',
        'heritage, cultural, historical, royal',
        'Arrival & City Tour',
        'Arrival at Jaipur Airport|Traditional Rajasthani welcome|Check-in at heritage hotel|Visit Hawa Mahal and City Palace|Explore Jantar Mantar|Sunset at Nahargarh Fort',
        'Amber Fort & Shopping',
        'Breakfast at hotel|Visit Amber Fort with elephant ride|Photo stop at Jal Mahal|Lunch at traditional restaurant|Shopping at Johari Bazaar and Bapu Bazaar|Cultural evening at Chokhi Dhani',
        'Departure',
        'Breakfast at hotel|Visit Albert Hall Museum|Free time for shopping|Check-out and transfer to airport',
        '',
        ''
    ));
    
    fclose($output);
    exit;
}

function stp_process_csv_import_safe($file_path) {
    if (!file_exists($file_path)) {
        return array('success' => false, 'message' => 'File not found');
    }
    
    // Increase execution time and memory for large imports
    @ini_set('max_execution_time', 300);
    @ini_set('memory_limit', '256M');
    
    $handle = @fopen($file_path, 'r');
    if (!$handle) {
        return array('success' => false, 'message' => 'Could not open CSV file. Please check file permissions.');
    }
    
    // Get header row
    $header = fgetcsv($handle, 10000, ',');
    if (!$header) {
        fclose($handle);
        return array('success' => false, 'message' => 'CSV file is empty or invalid');
    }
    
    $imported = 0;
    $errors = array();
    $row_number = 1;
    
    while (($row = fgetcsv($handle, 10000, ',')) !== false) {
        $row_number++;
        
        // Skip empty rows
        if (empty(array_filter($row))) {
            continue;
        }
        
        try {
            // Combine header with row data
            if (count($header) !== count($row)) {
                $errors[] = "Row {$row_number}: Column count mismatch (expected " . count($header) . ", got " . count($row) . ")";
                continue;
            }
            
            $data = array_combine($header, $row);
            
            if (!$data || empty($data['title'])) {
                $errors[] = "Row {$row_number}: Missing title";
                continue;
            }
            
            // Create the post as DRAFT (safe - won't publish automatically)
            $post_data = array(
                'post_title'   => sanitize_text_field($data['title']),
                'post_content' => isset($data['description']) ? wp_kses_post($data['description']) : '',
                'post_status'  => 'draft', // SAFE: Import as draft
                'post_type'    => 'travel_package',
                'post_author'  => get_current_user_id()
            );
            
            $post_id = wp_insert_post($post_data, true);
            
            if (is_wp_error($post_id)) {
                $errors[] = "Row {$row_number}: " . $post_id->get_error_message();
                continue;
            }
            
            // Add meta fields (SAFE: Only adds new data)
            if (isset($data['subtitle']) && !empty($data['subtitle'])) {
                update_post_meta($post_id, '_subtitle', sanitize_text_field($data['subtitle']));
            }
            
            if (isset($data['days']) && is_numeric($data['days'])) {
                update_post_meta($post_id, '_days', intval($data['days']));
            }
            
            if (isset($data['nights']) && is_numeric($data['nights'])) {
                update_post_meta($post_id, '_nights', intval($data['nights']));
            }
            
            // Assign taxonomies (SAFE: Auto-creates terms if needed)
            $taxonomy_map = array(
                'category' => 'tpm_category',
                'region' => 'tpm_region',
                'duration' => 'tpm_duration',
                'season' => 'tpm_season',
                'difficulty' => 'tpm_difficulty',
                'package_type' => 'tpm_package_type',
                'activity_type' => 'tpm_activity'
            );
            
            foreach ($taxonomy_map as $csv_key => $taxonomy) {
                if (isset($data[$csv_key]) && !empty($data[$csv_key])) {
                    $term = sanitize_text_field($data[$csv_key]);
                    
                    // Check if term exists, create if not
                    $term_obj = term_exists($term, $taxonomy);
                    if (!$term_obj) {
                        $term_obj = wp_insert_term($term, $taxonomy);
                    }
                    
                    if (!is_wp_error($term_obj)) {
                        $term_id = is_array($term_obj) ? $term_obj['term_id'] : $term_obj;
                        wp_set_object_terms($post_id, $term_id, $taxonomy, false);
                    }
                }
            }
            
            // Handle multiple taxonomies (comma-separated)
            if (isset($data['amenities']) && !empty($data['amenities'])) {
                $amenities = array_map('trim', explode(',', $data['amenities']));
                $amenity_ids = array();
                
                foreach ($amenities as $amenity) {
                    if (empty($amenity)) continue;
                    
                    $term_obj = term_exists($amenity, 'tpm_amenity');
                    if (!$term_obj) {
                        $term_obj = wp_insert_term($amenity, 'tpm_amenity');
                    }
                    
                    if (!is_wp_error($term_obj)) {
                        $amenity_ids[] = is_array($term_obj) ? $term_obj['term_id'] : $term_obj;
                    }
                }
                
                if (!empty($amenity_ids)) {
                    wp_set_object_terms($post_id, $amenity_ids, 'tpm_amenity', false);
                }
            }
            
            // Handle tags (comma-separated)
            if (isset($data['tags']) && !empty($data['tags'])) {
                $tags = array_map('trim', explode(',', $data['tags']));
                $tag_ids = array();
                
                foreach ($tags as $tag) {
                    if (empty($tag)) continue;
                    
                    $term_obj = term_exists($tag, 'tpm_tag');
                    if (!$term_obj) {
                        $term_obj = wp_insert_term($tag, 'tpm_tag');
                    }
                    
                    if (!is_wp_error($term_obj)) {
                        $tag_ids[] = is_array($term_obj) ? $term_obj['term_id'] : $term_obj;
                    }
                }
                
                if (!empty($tag_ids)) {
                    wp_set_object_terms($post_id, $tag_ids, 'tpm_tag', false);
                }
            }
            
            // Build itinerary (SAFE: Only adds new data)
            $itinerary = array();
            for ($i = 1; $i <= 10; $i++) {
                $title_key = "day{$i}_title";
                $activities_key = "day{$i}_activities";
                
                if (isset($data[$title_key]) && !empty($data[$title_key])) {
                    $activities = isset($data[$activities_key]) ? $data[$activities_key] : '';
                    
                    $itinerary[] = array(
                        'title' => sanitize_text_field($data[$title_key]),
                        'activities' => sanitize_textarea_field($activities)
                    );
                }
            }
            
            if (!empty($itinerary)) {
                update_post_meta($post_id, '_itinerary', $itinerary);
            }
            
            $imported++;
            
        } catch (Exception $e) {
            $errors[] = "Row {$row_number}: " . $e->getMessage();
            continue;
        }
    }
    
    fclose($handle);
    
    if ($imported === 0 && !empty($errors)) {
        return array(
            'success' => false,
            'message' => 'No packages were imported. Please check your CSV format.',
            'errors' => $errors
        );
    }
    
    return array(
        'success' => true,
        'imported' => $imported,
        'errors' => $errors
    );
}