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
                            <?php the_content(); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Itinerary Section - MOVED TO TOP (below description) -->
                <?php if (!empty($itinerary) && is_array($itinerary)): ?>
                    <section class="stp-itinerary-section">
                        <h2 class="stp-section-title">
                            <span class="stp-title-icon">📅</span>
                            Day by Day Itinerary
                        </h2>

                        <div class="stp-itinerary-timeline">
                            <?php foreach ($itinerary as $index => $day): ?>
                                <div class="stp-itinerary-day-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                                    <div class="stp-day-number">
                                        <span class="stp-day-label">Day</span>
                                        <strong class="stp-day-num"><?php echo $index + 1; ?></strong>
                                    </div>

                                    <div class="stp-day-content">
                                        <h3 class="stp-day-title"><?php echo esc_html($day['title']); ?></h3>
                                        <div class="stp-day-activities">
                                            <?php echo wp_kses_post(wpautop($day['activities'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Package Features Section -->
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
                <section class="stp-features-section">
                    <h2 class="stp-section-title">
                        <span class="stp-title-icon">✨</span>
                        Package Features
                    </h2>

                    <div class="stp-features-grid">
                        <?php if ($reasons): ?>
                        <div class="stp-feature-card" data-aos="fade-up">
                            <div class="stp-feature-icon">🎯</div>
                            <h3 class="stp-feature-title">Reason to Visit</h3>
                            <div class="stp-feature-items">
                                <?php foreach ($reasons as $term): ?>
                                    <span class="stp-feature-tag"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($durations): ?>
                        <div class="stp-feature-card" data-aos="fade-up" data-aos-delay="100">
                            <div class="stp-feature-icon">⏱️</div>
                            <h3 class="stp-feature-title">Duration</h3>
                            <div class="stp-feature-items">
                                <?php foreach ($durations as $term): ?>
                                    <span class="stp-feature-tag"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($seasons): ?>
                        <div class="stp-feature-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="stp-feature-icon">🌤️</div>
                            <h3 class="stp-feature-title">Best Season</h3>
                            <div class="stp-feature-items">
                                <?php foreach ($seasons as $term): ?>
                                    <span class="stp-feature-tag"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($difficulties): ?>
                        <div class="stp-feature-card" data-aos="fade-up" data-aos-delay="300">
                            <div class="stp-feature-icon">📊</div>
                            <h3 class="stp-feature-title">Difficulty Level</h3>
                            <div class="stp-feature-items">
                                <?php foreach ($difficulties as $term): ?>
                                    <span class="stp-feature-tag stp-difficulty-<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($package_types): ?>
                        <div class="stp-feature-card" data-aos="fade-up" data-aos-delay="400">
                            <div class="stp-feature-icon">📦</div>
                            <h3 class="stp-feature-title">Package Types</h3>
                            <div class="stp-feature-items">
                                <?php foreach ($package_types as $term): ?>
                                    <span class="stp-feature-tag"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($activity_types): ?>
                        <div class="stp-feature-card" data-aos="fade-up" data-aos-delay="500">
                            <div class="stp-feature-icon">🏃</div>
                            <h3 class="stp-feature-title">Activities</h3>
                            <div class="stp-feature-items">
                                <?php foreach ($activity_types as $term): ?>
                                    <span class="stp-feature-tag"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($amenities): ?>
                        <div class="stp-feature-card" data-aos="fade-up" data-aos-delay="600">
                            <div class="stp-feature-icon">🛎️</div>
                            <h3 class="stp-feature-title">Amenities</h3>
                            <div class="stp-feature-items">
                                <?php foreach ($amenities as $term): ?>
                                    <span class="stp-feature-tag"><?php echo esc_html($term->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
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
