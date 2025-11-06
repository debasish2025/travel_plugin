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
                
                <!-- Itinerary Section -->
                <?php if (!empty($itinerary) && is_array($itinerary)): ?>
                    <section class="stp-itinerary-section">
                        <h2 class="stp-section-title">
                            <span class="stp-title-icon">📅</span>
                            Day by Day Itinerary
                        </h2>
                        
                        <div class="stp-itinerary-timeline">
                            <?php foreach ($itinerary as $index => $day): ?>
                                <div class="stp-itinerary-day-item">
                                    <div class="stp-day-number">
                                        <span>Day</span>
                                        <strong><?php echo $index + 1; ?></strong>
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
