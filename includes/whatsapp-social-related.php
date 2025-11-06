<?php
/**
 * WhatsApp CTA, Social Share, and Related Packages
 * 
 * Features:
 * 1. WhatsApp button with configurable number
 * 2. Professional social share icons
 * 3. Related/suggested packages
 * 
 * SAFE: Only adds new features, doesn't modify existing functionality
 */

// Add settings page
add_action('admin_menu', 'stp_add_settings_page', 25);

function stp_add_settings_page() {
    add_submenu_page(
        'edit.php?post_type=travel_package',
        'Settings',
        'Settings',
        'manage_options',
        'stp-settings',
        'stp_settings_page'
    );
}

function stp_settings_page() {
    // Save settings
    if (isset($_POST['stp_save_settings'])) {
        check_admin_referer('stp_settings');
        
        update_option('stp_whatsapp_number', sanitize_text_field($_POST['whatsapp_number']));
        update_option('stp_whatsapp_message', sanitize_textarea_field($_POST['whatsapp_message']));
        update_option('stp_enable_social_share', isset($_POST['enable_social_share']) ? '1' : '0');
        update_option('stp_enable_related_packages', isset($_POST['enable_related_packages']) ? '1' : '0');
        update_option('stp_related_packages_count', absint($_POST['related_packages_count']));
        
        echo '<div class="notice notice-success is-dismissible"><p><strong>✓ Settings saved successfully!</strong></p></div>';
    }
    
    // Get current settings
    $whatsapp_number = get_option('stp_whatsapp_number', '');
    $whatsapp_message = get_option('stp_whatsapp_message', 'Hi! I am interested in the {{package_name}} package. Please share more details.');
    $enable_social = get_option('stp_enable_social_share', '1');
    $enable_related = get_option('stp_enable_related_packages', '1');
    $related_count = get_option('stp_related_packages_count', '4');
    
    ?>
    <div class="wrap">
        <h1>🎨 Travel Packages Settings</h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('stp_settings'); ?>
            
            <div class="card" style="max-width: 800px;">
                <h2>📱 WhatsApp CTA Settings</h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="whatsapp_number">WhatsApp Number</label>
                        </th>
                        <td>
                            <input type="text" 
                                   name="whatsapp_number" 
                                   id="whatsapp_number" 
                                   value="<?php echo esc_attr($whatsapp_number); ?>" 
                                   class="regular-text" 
                                   placeholder="919876543210">
                            <p class="description">
                                Enter with country code (no + or spaces). Example: 919876543210 for India<br>
                                Format: Country Code + Number (e.g., 1 for USA, 91 for India, 44 for UK)
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="whatsapp_message">WhatsApp Message Template</label>
                        </th>
                        <td>
                            <textarea name="whatsapp_message" 
                                      id="whatsapp_message" 
                                      rows="4" 
                                      class="large-text"><?php echo esc_textarea($whatsapp_message); ?></textarea>
                            <p class="description">
                                Use <code>{{package_name}}</code> to insert the package title automatically<br>
                                Example: "Hi! I'm interested in {{package_name}}. Please share details."
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>🔗 Social Share Settings</h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">Enable Social Share Icons</th>
                        <td>
                            <label>
                                <input type="checkbox" 
                                       name="enable_social_share" 
                                       value="1" 
                                       <?php checked($enable_social, '1'); ?>>
                                Show social share icons (Facebook, Twitter, LinkedIn, WhatsApp, Email)
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>🎯 Related Packages Settings</h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">Enable Related Packages</th>
                        <td>
                            <label>
                                <input type="checkbox" 
                                       name="enable_related_packages" 
                                       value="1" 
                                       <?php checked($enable_related, '1'); ?>>
                                Show related packages from the same category
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="related_packages_count">Number of Related Packages</label>
                        </th>
                        <td>
                            <select name="related_packages_count" id="related_packages_count">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php selected($related_count, $i); ?>>
                                        <?php echo $i; ?> Package<?php echo $i > 1 ? 's' : ''; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <p class="description">Choose how many related packages to display (1-12)</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <p class="submit">
                <button type="submit" name="stp_save_settings" class="button button-primary button-large">
                    <span class="dashicons dashicons-yes" style="vertical-align: middle;"></span>
                    Save Settings
                </button>
            </p>
        </form>
        
        <div class="card" style="max-width: 800px; margin-top: 20px; background: #f0f6fc; border-left: 4px solid #0073aa;">
            <h3>📌 Preview</h3>
            <p>Once configured, these features will appear on your package pages in this order:</p>
            <ol>
                <li><strong>Package Content</strong> - Your main package description</li>
                <li><strong>Itinerary</strong> - Day by day itinerary</li>
                <li><strong>WhatsApp Button</strong> - Green button to contact via WhatsApp</li>
                <li><strong>Social Share Icons</strong> - Share on Facebook, Twitter, etc.</li>
                <li><strong>Related Packages</strong> - Similar packages grid</li>
            </ol>
        </div>
    </div>
    
    <style>
    .card h2 { margin-top: 0; color: #23282d; }
    .card h3 { margin-top: 0; color: #0073aa; }
    .dashicons { line-height: inherit; }
    </style>
    <?php
}

// Display WhatsApp CTA button on package pages
add_filter('the_content', 'stp_add_whatsapp_cta', 25);

function stp_add_whatsapp_cta($content) {
    if (!is_singular('travel_package')) {
        return $content;
    }
    
    $whatsapp_number = get_option('stp_whatsapp_number', '');
    
    if (empty($whatsapp_number)) {
        return $content;
    }
    
    global $post;
    $whatsapp_message = get_option('stp_whatsapp_message', 'Hi! I am interested in the {{package_name}} package.');
    $message = str_replace('{{package_name}}', get_the_title($post), $whatsapp_message);
    $whatsapp_url = 'https://wa.me/' . $whatsapp_number . '?text=' . urlencode($message);
    
    ob_start();
    ?>
    <div class="whatsapp-cta-container" style="margin: 40px 0; text-align: center;">
        <a href="<?php echo esc_url($whatsapp_url); ?>" 
           target="_blank" 
           rel="noopener noreferrer"
           class="whatsapp-cta-button"
           style="display: inline-block; padding: 18px 40px; background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: white; text-decoration: none; border-radius: 50px; font-size: 18px; font-weight: 600; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4); transition: all 0.3s ease; border: none;">
            <svg style="width: 24px; height: 24px; vertical-align: middle; margin-right: 10px; fill: currentColor;" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            <span style="vertical-align: middle;">Send Query on WhatsApp</span>
        </a>
    </div>
    
    <style>
    .whatsapp-cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
        background: linear-gradient(135deg, #128C7E 0%, #25D366 100%);
    }
    .whatsapp-cta-button:active {
        transform: translateY(-1px);
    }
    </style>
    <?php
    $whatsapp_html = ob_get_clean();
    
    return $content . $whatsapp_html;
}

// Display social share icons
add_filter('the_content', 'stp_add_social_share', 30);

function stp_add_social_share($content) {
    if (!is_singular('travel_package')) {
        return $content;
    }
    
    $enable_social = get_option('stp_enable_social_share', '1');
    if ($enable_social !== '1') {
        return $content;
    }
    
    global $post;
    $url = get_permalink($post);
    $title = get_the_title($post);
    $image = get_the_post_thumbnail_url($post, 'full');
    
    // Share URLs
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url);
    $twitter_url = 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title);
    $linkedin_url = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url);
    $whatsapp_url = 'https://wa.me/?text=' . urlencode($title . ' ' . $url);
    $email_url = 'mailto:?subject=' . urlencode($title) . '&body=' . urlencode($title . ' - ' . $url);
    
    ob_start();
    ?>
    <div class="social-share-container" style="margin: 40px 0; padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; text-align: center;">
        <h3 style="color: white; margin-top: 0; margin-bottom: 20px; font-size: 20px;">
            ✨ Share This Package
        </h3>
        
        <div class="social-share-buttons" style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <a href="<?php echo esc_url($facebook_url); ?>" 
               target="_blank" 
               rel="noopener noreferrer"
               class="social-btn social-facebook"
               title="Share on Facebook"
               style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #1877F2; color: white; border-radius: 50%; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                <svg style="width: 24px; height: 24px; fill: currentColor;" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
            </a>
            
            <a href="<?php echo esc_url($twitter_url); ?>" 
               target="_blank" 
               rel="noopener noreferrer"
               class="social-btn social-twitter"
               title="Share on Twitter"
               style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #1DA1F2; color: white; border-radius: 50%; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                <svg style="width: 24px; height: 24px; fill: currentColor;" viewBox="0 0 24 24">
                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
            </a>
            
            <a href="<?php echo esc_url($linkedin_url); ?>" 
               target="_blank" 
               rel="noopener noreferrer"
               class="social-btn social-linkedin"
               title="Share on LinkedIn"
               style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #0A66C2; color: white; border-radius: 50%; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                <svg style="width: 24px; height: 24px; fill: currentColor;" viewBox="0 0 24 24">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
            </a>
            
            <a href="<?php echo esc_url($whatsapp_url); ?>" 
               target="_blank" 
               rel="noopener noreferrer"
               class="social-btn social-whatsapp"
               title="Share on WhatsApp"
               style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #25D366; color: white; border-radius: 50%; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                <svg style="width: 24px; height: 24px; fill: currentColor;" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
            
            <a href="<?php echo esc_url($email_url); ?>" 
               class="social-btn social-email"
               title="Share via Email"
               style="display: inline-flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #EA4335; color: white; border-radius: 50%; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                <svg style="width: 24px; height: 24px; fill: currentColor;" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
            </a>
        </div>
    </div>
    
    <style>
    .social-btn:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    .social-btn:active {
        transform: translateY(-2px) scale(1.05);
    }
    </style>
    <?php
    $social_html = ob_get_clean();
    
    return $content . $social_html;
}

// Display related packages
add_filter('the_content', 'stp_add_related_packages', 35);

function stp_add_related_packages($content) {
    if (!is_singular('travel_package')) {
        return $content;
    }
    
    $enable_related = get_option('stp_enable_related_packages', '1');
    if ($enable_related !== '1') {
        return $content;
    }
    
    global $post;
    $related_count = get_option('stp_related_packages_count', 4);
    
    // Get current package categories
    $categories = wp_get_post_terms($post->ID, 'tpm_category', array('fields' => 'ids'));
    
    if (empty($categories)) {
        return $content;
    }
    
    // Query related packages
    $args = array(
        'post_type' => 'travel_package',
        'posts_per_page' => $related_count,
        'post__not_in' => array($post->ID),
        'tax_query' => array(
            array(
                'taxonomy' => 'tpm_category',
                'field' => 'term_id',
                'terms' => $categories
            )
        ),
        'orderby' => 'rand'
    );
    
    $related_query = new WP_Query($args);
    
    if (!$related_query->have_posts()) {
        return $content;
    }
    
    ob_start();
    ?>
    <div class="related-packages-container" style="margin: 50px 0; padding: 40px 30px; background: #f8f9fa; border-radius: 15px;">
        <h2 style="text-align: center; color: #333; margin-top: 0; margin-bottom: 35px; font-size: 32px; font-weight: 700;">
            🌟 You May Also Like
        </h2>
        
        <div class="related-packages-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px;">
            <?php while ($related_query->have_posts()): $related_query->the_post(); ?>
                <div class="related-package-item" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 15px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                    <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit; display: block;">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="related-package-image" style="height: 180px; overflow: hidden; position: relative;">
                                <?php the_post_thumbnail('medium', array('style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;')); ?>
                                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.3) 100%);"></div>
                            </div>
                        <?php else: ?>
                            <div style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 48px;">🏖️</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="related-package-content" style="padding: 20px;">
                            <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #333; line-height: 1.4; font-weight: 600;">
                                <?php the_title(); ?>
                            </h3>
                            
                            <?php
                            $days = get_post_meta(get_the_ID(), '_days', true);
                            $nights = get_post_meta(get_the_ID(), '_nights', true);
                            
                            if ($days || $nights):
                            ?>
                                <p style="margin: 0 0 12px 0; color: #666; font-size: 14px;">
                                    <span style="display: inline-block; padding: 4px 10px; background: #e7f5fe; border-radius: 15px; font-size: 12px; color: #0073aa; font-weight: 500;">
                                        <?php echo esc_html($days); ?> Days / <?php echo esc_html($nights); ?> Nights
                                    </span>
                                </p>
                            <?php endif; ?>
                            
                            <?php if (has_excerpt()): ?>
                                <p style="margin: 0; color: #777; font-size: 14px; line-height: 1.5;">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                </p>
                            <?php endif; ?>
                            
                            <p style="margin: 15px 0 0 0;">
                                <span style="color: #0073aa; font-weight: 600; font-size: 14px;">
                                    View Details →
                                </span>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
    
    <style>
    .related-package-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    .related-package-item:hover .related-package-image img {
        transform: scale(1.1);
    }
    @media (max-width: 768px) {
        .related-packages-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
    }
    @media (max-width: 480px) {
        .related-packages-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
    <?php
    $related_html = ob_get_clean();
    
    return $content . $related_html;
}