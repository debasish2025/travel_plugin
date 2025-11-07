<?php
/**
 * CSV Import Functionality for Wedding Packages
 * Supports HTML formatting from Word documents
 */

// Add admin menu for CSV import
add_action('admin_menu', 'wedding_csv_import_menu', 20);

function wedding_csv_import_menu() {
    add_submenu_page(
        'edit.php?post_type=wedding_package',
        'Import Packages (CSV)',
        '📥 Import CSV',
        'manage_options',
        'wedding-csv-import',
        'wedding_csv_import_page'
    );
}

// CSV Import Page
function wedding_csv_import_page() {
    ?>
    <div class="wrap stp-import-wrapper">
        <h1 class="stp-import-title">
            <span class="dashicons dashicons-upload" style="font-size: 32px; color: #667eea;"></span>
            Import Wedding Packages from CSV
        </h1>

        <div class="stp-import-grid">
            <!-- Instructions Card -->
            <div class="stp-import-card stp-instructions-card">
                <h2>📖 How It Works</h2>
                <ol class="stp-instruction-list">
                    <li><strong>Download the template</strong> using the button below</li>
                    <li><strong>Copy content from Word</strong> - paste directly into Excel/Google Sheets</li>
                    <li><strong>HTML is supported!</strong> Bold, italic, lists - all formatting preserved</li>
                    <li><strong>Day-by-day itinerary</strong> - unlimited days supported</li>
                    <li><strong>Save as CSV</strong> and upload below</li>
                </ol>

                <div class="stp-feature-highlights">
                    <div class="stp-feature">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <span>HTML Formatting Support</span>
                    </div>
                    <div class="stp-feature">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <span>Multiple Days/Itinerary</span>
                    </div>
                    <div class="stp-feature">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <span>Categories & Taxonomies</span>
                    </div>
                    <div class="stp-feature">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <span>Bulk Import Ready</span>
                    </div>
                </div>

                <a href="<?php echo admin_url('admin.php?action=wedding_download_csv_template'); ?>"
                   class="button button-primary button-hero stp-download-btn">
                    <span class="dashicons dashicons-download"></span>
                    Download CSV Template
                </a>
            </div>

            <!-- Upload Card -->
            <div class="stp-import-card stp-upload-card">
                <h2>📤 Upload Your CSV File</h2>

                <form method="post" enctype="multipart/form-data" class="stp-upload-form">
                    <?php wp_nonce_field('wedding_csv_import_nonce', 'wedding_csv_import_nonce'); ?>

                    <div class="stp-upload-area">
                        <div class="stp-upload-icon">📁</div>
                        <p class="stp-upload-text">Choose CSV file to import</p>
                        <input type="file" name="csv_file" id="csv_file" accept=".csv" required class="stp-file-input">
                        <label for="csv_file" class="stp-file-label">Browse Files</label>
                    </div>

                    <div class="stp-import-options">
                        <label class="stp-checkbox-label">
                            <input type="checkbox" name="skip_duplicates" value="1" checked>
                            <span>Skip duplicate packages (check by title)</span>
                        </label>

                        <label class="stp-checkbox-label">
                            <input type="checkbox" name="create_categories" value="1" checked>
                            <span>Auto-create missing categories</span>
                        </label>

                        <label class="stp-checkbox-label">
                            <input type="checkbox" name="publish_immediately" value="1">
                            <span>Publish packages immediately (default: draft)</span>
                        </label>
                    </div>

                    <button type="submit" name="wedding_import_csv" class="button button-primary button-hero stp-import-btn">
                        <span class="dashicons dashicons-upload"></span>
                        Start Import
                    </button>
                </form>

                <?php
                // Handle import
                if (isset($_POST['wedding_import_csv']) && check_admin_referer('wedding_csv_import_nonce', 'wedding_csv_import_nonce')) {
                    wedding_process_csv_import();
                }
                ?>
            </div>
        </div>
    </div>

    <style>
    .stp-import-wrapper {
        max-width: 1400px;
        margin: 20px auto;
    }

    .stp-import-title {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 30px;
    }

    .stp-import-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 30px;
    }

    .stp-import-card {
        background: #fff;
        border-radius: 16px;
        padding: 35px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 2px solid #e9ecef;
    }

    .stp-import-card h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 25px 0;
    }

    .stp-instruction-list {
        padding-left: 20px;
        margin: 0 0 30px 0;
    }

    .stp-instruction-list li {
        margin-bottom: 15px;
        line-height: 1.6;
        color: #444;
    }

    .stp-feature-highlights {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 30px;
    }

    .stp-feature {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #667eea;
    }

    .stp-feature .dashicons {
        color: #4caf50;
        font-size: 20px;
    }

    .stp-download-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 15px 30px;
        font-size: 16px;
    }

    .stp-download-btn:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .stp-upload-area {
        border: 3px dashed #667eea;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        background: #f8f9fa;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }

    .stp-upload-area:hover {
        background: #e9ecef;
        border-color: #764ba2;
    }

    .stp-upload-icon {
        font-size: 64px;
        margin-bottom: 15px;
    }

    .stp-upload-text {
        font-size: 16px;
        color: #666;
        margin: 0 0 15px 0;
    }

    .stp-file-input {
        display: none;
    }

    .stp-file-label {
        display: inline-block;
        padding: 12px 30px;
        background: #667eea;
        color: #fff;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .stp-file-label:hover {
        background: #764ba2;
        transform: translateY(-2px);
    }

    .stp-import-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 25px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stp-checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        color: #444;
        cursor: pointer;
    }

    .stp-checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .stp-import-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        justify-content: center;
        background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
        border: none;
        padding: 15px;
        font-size: 16px;
    }

    .stp-import-btn:hover {
        background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
    }

    .stp-import-result {
        margin-top: 30px;
        padding: 25px;
        background: #fff;
        border-radius: 12px;
        border-left: 5px solid #4caf50;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .stp-import-result.error {
        border-left-color: #f44336;
    }

    .stp-import-result h3 {
        margin: 0 0 15px 0;
        font-size: 20px;
    }

    .stp-import-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    .stp-stat-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .stp-stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #667eea;
        display: block;
    }

    .stp-stat-label {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }

    @media (max-width: 992px) {
        .stp-import-grid {
            grid-template-columns: 1fr;
        }

        .stp-feature-highlights {
            grid-template-columns: 1fr;
        }

        .stp-import-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    </style>

    <script>
    jQuery(document).ready(function($) {
        $('#csv_file').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $('.stp-upload-text').text('Selected: ' + fileName);
                $('.stp-file-label').text('Change File');
            }
        });
    });
    </script>
    <?php
}

// Download CSV Template
add_action('admin_action_wedding_download_csv_template', 'wedding_download_csv_template');

function wedding_download_csv_template() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }

    $filename = 'wedding-packages-import-template.csv';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);

    $output = fopen('php://output', 'w');

    // CSV Headers with all fields for Wedding Packages
    $headers = array(
        'title',
        'subtitle',
        'description',
        'days',
        'nights',
        'price',
        'featured_image_url',
        'categories',          // wedding_category
        'regions',             // wedding_region
        'duration',            // wedding_duration
        'activity_types',      // wedding_activity
        'day_1_title',
        'day_1_activities',
        'day_2_title',
        'day_2_activities',
        'day_3_title',
        'day_3_activities',
        'day_4_title',
        'day_4_activities',
        'day_5_title',
        'day_5_activities',
        // Add more days as needed - up to 15 days
        'day_6_title', 'day_6_activities',
        'day_7_title', 'day_7_activities',
        'day_8_title', 'day_8_activities',
        'day_9_title', 'day_9_activities',
        'day_10_title', 'day_10_activities',
    );

    fputcsv($output, $headers);

    // Sample row with wedding package example
    $sample = array(
        'Beach Wedding in Goa',
        'Your Dream Beach Wedding',
        '<p>Celebrate your love with a beautiful <strong>beach wedding in Goa</strong>.</p><ul><li>Sunset ceremony on the beach</li><li>Floral decorations</li><li>Professional photography</li></ul>',
        '3',
        '2',
        '150000',
        'https://example.com/beach-wedding.jpg',
        'Beach Wedding, Destination Wedding',
        'Goa, India',
        '3 Days',
        'Photography, Decoration, Catering, Entertainment',
        'Pre-Wedding Day',
        '<p><strong>Morning:</strong> Arrival and hotel check-in</p><p><strong>Afternoon:</strong> Meet with wedding planner</p><ul><li>Venue walkthrough</li><li>Final arrangements</li></ul>',
        'Wedding Day',
        '<p><strong>Morning:</strong> Bride and groom preparations</p><p><strong>Afternoon:</strong> Beach ceremony at sunset</p><ul><li>150 guests capacity</li><li>Live music</li><li>Gourmet dinner</li></ul>',
        'Post-Wedding Celebration',
        '<p>Casual <strong>beach brunch</strong> with guests</p><p>Departure arrangements</p>',
        '', '', '', '',
        '', '', '', '', '', '', '', '', '', '',
    );

    fputcsv($output, $sample);

    fclose($output);
    exit;
}

// Process CSV Import
function wedding_process_csv_import() {
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        echo '<div class="notice notice-error"><p>Error uploading file. Please try again.</p></div>';
        return;
    }

    $file = $_FILES['csv_file']['tmp_name'];
    $skip_duplicates = isset($_POST['skip_duplicates']);
    $create_categories = isset($_POST['create_categories']);
    $publish_immediately = isset($_POST['publish_immediately']);

    $handle = fopen($file, 'r');
    if ($handle === false) {
        echo '<div class="notice notice-error"><p>Error reading CSV file.</p></div>';
        return;
    }

    $headers = fgetcsv($handle);
    $imported = 0;
    $skipped = 0;
    $errors = 0;
    $error_messages = array();

    while (($data = fgetcsv($handle)) !== false) {
        if (empty($data[0])) continue; // Skip empty rows

        $row = array_combine($headers, $data);

        // Check for duplicates
        if ($skip_duplicates) {
            $existing = get_page_by_title($row['title'], OBJECT, 'wedding_package');
            if ($existing) {
                $skipped++;
                continue;
            }
        }

        // Create package
        $post_data = array(
            'post_title' => sanitize_text_field($row['title']),
            'post_content' => wp_kses_post($row['description']),
            'post_type' => 'wedding_package',
            'post_status' => $publish_immediately ? 'publish' : 'draft',
        );

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            $errors++;
            $error_messages[] = 'Failed to import: ' . $row['title'];
            continue;
        }

        // Add meta fields
        update_post_meta($post_id, '_subtitle', sanitize_text_field($row['subtitle']));
        update_post_meta($post_id, '_days', absint($row['days']));
        update_post_meta($post_id, '_nights', absint($row['nights']));
        update_post_meta($post_id, '_price', sanitize_text_field($row['price']));

        // Handle taxonomies
        wedding_import_handle_taxonomies($post_id, $row, $create_categories);

        // Handle itinerary
        wedding_import_handle_itinerary($post_id, $row);

        // Handle featured image
        if (!empty($row['featured_image_url'])) {
            wedding_import_set_featured_image($post_id, $row['featured_image_url']);
        }

        $imported++;
    }

    fclose($handle);

    // Display results
    ?>
    <div class="stp-import-result <?php echo $errors > 0 ? 'error' : ''; ?>">
        <h3><?php echo $errors > 0 ? '⚠️ Import Completed with Errors' : '✅ Import Successful!'; ?></h3>
        <div class="stp-import-stats">
            <div class="stp-stat-box">
                <span class="stp-stat-number"><?php echo $imported; ?></span>
                <span class="stp-stat-label">Imported</span>
            </div>
            <div class="stp-stat-box">
                <span class="stp-stat-number"><?php echo $skipped; ?></span>
                <span class="stp-stat-label">Skipped</span>
            </div>
            <div class="stp-stat-box">
                <span class="stp-stat-number"><?php echo $errors; ?></span>
                <span class="stp-stat-label">Errors</span>
            </div>
            <div class="stp-stat-box">
                <span class="stp-stat-number"><?php echo $imported + $skipped + $errors; ?></span>
                <span class="stp-stat-label">Total Rows</span>
            </div>
        </div>
        <?php if (!empty($error_messages)): ?>
            <div style="margin-top: 20px;">
                <h4>Error Details:</h4>
                <ul>
                    <?php foreach ($error_messages as $msg): ?>
                        <li><?php echo esc_html($msg); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <p style="margin-top: 20px;">
            <a href="<?php echo admin_url('edit.php?post_type=wedding_package'); ?>" class="button button-primary">
                View Imported Packages
            </a>
        </p>
    </div>
    <?php
}

// Handle taxonomies during import
function wedding_import_handle_taxonomies($post_id, $row, $create_categories) {
    $taxonomies = array(
        'categories' => 'wedding_category',
        'regions' => 'wedding_region',
        'duration' => 'wedding_duration',
        'activity_types' => 'wedding_activity',
    );

    foreach ($taxonomies as $csv_field => $taxonomy) {
        if (empty($row[$csv_field])) continue;

        $terms = array_map('trim', explode(',', $row[$csv_field]));
        $term_ids = array();

        foreach ($terms as $term_name) {
            $term = get_term_by('name', $term_name, $taxonomy);

            if (!$term && $create_categories) {
                $result = wp_insert_term($term_name, $taxonomy);
                if (!is_wp_error($result)) {
                    $term_ids[] = $result['term_id'];
                }
            } elseif ($term) {
                $term_ids[] = $term->term_id;
            }
        }

        if (!empty($term_ids)) {
            wp_set_post_terms($post_id, $term_ids, $taxonomy);
        }
    }
}

// Handle itinerary during import
function wedding_import_handle_itinerary($post_id, $row) {
    $itinerary = array();

    // Check for up to 15 days
    for ($i = 1; $i <= 15; $i++) {
        $title_key = 'day_' . $i . '_title';
        $activities_key = 'day_' . $i . '_activities';

        if (empty($row[$title_key]) && empty($row[$activities_key])) {
            continue;
        }

        $itinerary[] = array(
            'title' => sanitize_text_field($row[$title_key]),
            'activities' => wp_kses_post($row[$activities_key]),
        );
    }

    if (!empty($itinerary)) {
        update_post_meta($post_id, '_itinerary', $itinerary);
    }
}

// Set featured image from URL
function wedding_import_set_featured_image($post_id, $image_url) {
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $image_id = media_sideload_image($image_url, $post_id, null, 'id');

    if (!is_wp_error($image_id)) {
        set_post_thumbnail($post_id, $image_id);
    }
}
