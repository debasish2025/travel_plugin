<?php
/**
 * Single Travel Package Template
 * Professional design with hero section and Elementor support
 */

get_header();

while (have_posts()) : the_post();

    $subtitle = get_post_meta(get_the_ID(), '_subtitle', true);
    $days = get_post_meta(get_the_ID(), '_days', true);
    $nights = get_post_meta(get_the_ID(), '_nights', true);
    $itinerary = get_post_meta(get_the_ID(), '_itinerary', true);
    $template = get_post_meta(get_the_ID(), '_page_template', true);

    if (!$template || $template == 'default') {
        $template = 'default';
    }

    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('stp-single-package'); ?>>

        <?php if ($template == 'default'): ?>
            <!-- Template 1: Classic Hero -->
            <div class="stp-hero-section stp-hero-classic">
                <?php if (has_post_thumbnail()): ?>
                    <div class="stp-hero-image">
                        <?php the_post_thumbnail('full'); ?>
                        <div class="stp-hero-overlay"></div>
                    </div>
                <?php endif; ?>

                <div class="stp-hero-content">
                    <div class="container">
                        <h1 class="stp-hero-title"><?php the_title(); ?></h1>
                        <?php if ($subtitle): ?>
                            <p class="stp-hero-subtitle"><?php echo esc_html($subtitle); ?></p>
                        <?php endif; ?>
                        <?php if ($days && $nights): ?>
                            <div class="stp-duration-badge">
                                <span class="stp-days"><?php echo $days; ?> Days</span>
                                <span class="stp-separator">|</span>
                                <span class="stp-nights"><?php echo $nights; ?> Nights</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php elseif ($template == 'modern'): ?>
            <!-- Template 2: Modern Split -->
            <div class="stp-hero-section stp-hero-modern">
                <div class="container">
                    <div class="stp-modern-grid">
                        <div class="stp-modern-content">
                            <h1 class="stp-hero-title"><?php the_title(); ?></h1>
                            <?php if ($subtitle): ?>
                                <p class="stp-hero-subtitle"><?php echo esc_html($subtitle); ?></p>
                            <?php endif; ?>
                            <?php if ($days && $nights): ?>
                                <div class="stp-duration-badge">
                                    <span class="stp-days"><?php echo $days; ?> Days</span>
                                    <span class="stp-separator">|</span>
                                    <span class="stp-nights"><?php echo $nights; ?> Nights</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (has_post_thumbnail()): ?>
                            <div class="stp-modern-image">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Template 3: Full Width Hero -->
            <div class="stp-hero-section stp-hero-fullwidth">
                <?php if (has_post_thumbnail()): ?>
                    <div class="stp-hero-image-full">
                        <?php the_post_thumbnail('full'); ?>
                        <div class="stp-hero-overlay-dark"></div>
                    </div>
                <?php endif; ?>

                <div class="stp-hero-content-centered">
                    <div class="container">
                        <h1 class="stp-hero-title-large"><?php the_title(); ?></h1>
                        <?php if ($subtitle): ?>
                            <p class="stp-hero-subtitle-large"><?php echo esc_html($subtitle); ?></p>
                        <?php endif; ?>
                        <?php if ($days && $nights): ?>
                            <div class="stp-duration-badge-large">
                                <span class="stp-days"><?php echo $days; ?> Days</span>
                                <span class="stp-separator">•</span>
                                <span class="stp-nights"><?php echo $nights; ?> Nights</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Content Area -->
        <div class="stp-package-content">
            <div class="container">

                <!-- Description -->
                <?php if (get_the_content()): ?>
                    <section class="stp-description-section">
                        <div class="stp-content-wrapper">
                            <?php
                            // Remove WhatsApp filter temporarily
                            remove_filter('the_content', 'stp_add_whatsapp_cta', 25);
                            the_content();
                            ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Itinerary Section - HORIZONTAL TABS -->
                <?php if (!empty($itinerary) && is_array($itinerary)): ?>
                    <section class="stp-itinerary-section-tabs">
                        <h2 class="stp-section-title-modern">
                            📅 Day by Day Itinerary
                        </h2>

                        <div class="stp-tabs-container">
                            <!-- Tab Buttons -->
                            <div class="stp-tab-buttons">
                                <?php foreach ($itinerary as $index => $day): ?>
                                    <button class="stp-tab-btn <?php echo $index === 0 ? 'active' : ''; ?>"
                                            data-tab="day-<?php echo $index; ?>">
                                        Day <?php echo $index + 1; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>

                            <!-- Tab Content -->
                            <div class="stp-tab-content-wrapper">
                                <?php foreach ($itinerary as $index => $day): ?>
                                    <div class="stp-tab-panel <?php echo $index === 0 ? 'active' : ''; ?>"
                                         id="day-<?php echo $index; ?>">
                                        <h3 class="stp-tab-title"><?php echo esc_html($day['title']); ?></h3>
                                        <div class="stp-tab-content">
                                            <?php echo wp_kses_post(wpautop($day['activities'])); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Package Features Section - COMPACT DESIGN -->
                <?php
                // Get visibility settings
                $show_reason = get_post_meta(get_the_ID(), '_show_reason', true) !== '0';
                $show_duration = get_post_meta(get_the_ID(), '_show_duration', true) !== '0';
                $show_season = get_post_meta(get_the_ID(), '_show_season', true) !== '0';
                $show_difficulty = get_post_meta(get_the_ID(), '_show_difficulty', true) !== '0';
                $show_label = get_post_meta(get_the_ID(), '_show_label', true) !== '0';
                $show_package_types = get_post_meta(get_the_ID(), '_show_package_types', true) !== '0';
                $show_activity_types = get_post_meta(get_the_ID(), '_show_activity_types', true) !== '0';
                $show_amenities = get_post_meta(get_the_ID(), '_show_amenities', true) !== '0';

                // Get taxonomy terms
                $reasons = $show_reason ? get_the_terms(get_the_ID(), 'tpm_label') : false;
                $durations = $show_duration ? get_the_terms(get_the_ID(), 'tpm_duration') : false;
                $seasons = $show_season ? get_the_terms(get_the_ID(), 'tpm_season') : false;
                $difficulties = $show_difficulty ? get_the_terms(get_the_ID(), 'tpm_difficulty_level') : false;
                $package_types = $show_package_types ? get_the_terms(get_the_ID(), 'tpm_package_type') : false;
                $activity_types = $show_activity_types ? get_the_terms(get_the_ID(), 'tpm_activity_type') : false;
                $amenities = $show_amenities ? get_the_terms(get_the_ID(), 'tpm_amenities') : false;

                // Check if we have any features to display
                $has_features = $reasons || $durations || $seasons || $difficulties || $package_types || $activity_types || $amenities;
                ?>

                <?php if ($has_features): ?>
                <section class="stp-features-compact">
                    <h3 class="stp-features-heading">✨ Package Features</h3>
                    <div class="stp-features-list">
                        <?php if ($reasons): ?>
                            <div class="stp-feature-item">
                                <span class="stp-feature-label">🎯 Reason:</span>
                                <?php foreach ($reasons as $term): ?>
                                    <span class="stp-feature-badge"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($durations): ?>
                            <div class="stp-feature-item">
                                <span class="stp-feature-label">⏱️ Duration:</span>
                                <?php foreach ($durations as $term): ?>
                                    <span class="stp-feature-badge"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($seasons): ?>
                            <div class="stp-feature-item">
                                <span class="stp-feature-label">🌤️ Season:</span>
                                <?php foreach ($seasons as $term): ?>
                                    <span class="stp-feature-badge"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($difficulties): ?>
                            <div class="stp-feature-item">
                                <span class="stp-feature-label">📊 Difficulty:</span>
                                <?php foreach ($difficulties as $term): ?>
                                    <span class="stp-feature-badge stp-diff-<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($package_types): ?>
                            <div class="stp-feature-item">
                                <span class="stp-feature-label">📦 Type:</span>
                                <?php foreach ($package_types as $term): ?>
                                    <span class="stp-feature-badge"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($activity_types): ?>
                            <div class="stp-feature-item">
                                <span class="stp-feature-label">🏃 Activities:</span>
                                <?php foreach ($activity_types as $term): ?>
                                    <span class="stp-feature-badge"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($amenities): ?>
                            <div class="stp-feature-item">
                                <span class="stp-feature-label">🛎️ Amenities:</span>
                                <?php foreach ($amenities as $term): ?>
                                    <span class="stp-feature-badge"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- WhatsApp Button -->
                <?php
                $whatsapp_number = get_option('stp_whatsapp_number', '');
                if (!empty($whatsapp_number)):
                    $whatsapp_message = get_option('stp_whatsapp_message', 'Hi! I am interested in the {{package_name}} package.');
                    $message = str_replace('{{package_name}}', get_the_title(), $whatsapp_message);
                    $whatsapp_url = 'https://wa.me/' . $whatsapp_number . '?text=' . urlencode($message);
                ?>
                <div class="whatsapp-cta-container" style="margin: 40px 0; text-align: center;">
                    <a href="<?php echo esc_url($whatsapp_url); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="whatsapp-cta-button"
                       style="display: inline-block; padding: 18px 40px; background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: white; text-decoration: none; border-radius: 50px; font-size: 18px; font-weight: 600; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4); transition: all 0.3s ease;">
                        <svg style="width: 24px; height: 24px; vertical-align: middle; margin-right: 10px; fill: currentColor;" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span style="vertical-align: middle;">Send Query on WhatsApp</span>
                    </a>
                </div>
                <?php endif; ?>

                <!-- Elementor Content (if exists) -->
                <?php
                // Check if Elementor content exists
                if (class_exists('\Elementor\Plugin')) {
                    $elementor_data = get_post_meta(get_the_ID(), '_elementor_data', true);
                    if ($elementor_data) {
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display(get_the_ID());
                    }
                }
                ?>

            </div>
        </div>

    </article>
    <?php

endwhile;

get_footer();
