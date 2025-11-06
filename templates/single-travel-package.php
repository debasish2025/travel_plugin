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

                <!-- Day by Day Itinerary - Modern Design -->
                <?php if (!empty($itinerary) && is_array($itinerary)): ?>
                    <section class="stp-itinerary-modern">
                        <div class="stp-itinerary-header">
                            <h2>Day by Day Itinerary</h2>
                            <p>Explore your journey day by day</p>
                        </div>

                        <div class="stp-timeline-wrapper">
                            <?php foreach ($itinerary as $index => $day): ?>
                                <div class="stp-timeline-item" data-aos="fade-up">
                                    <div class="stp-timeline-marker">
                                        <div class="stp-day-badge">
                                            <span class="day-number"><?php echo $index + 1; ?></span>
                                            <span class="day-label">Day</span>
                                        </div>
                                    </div>
                                    <div class="stp-timeline-content">
                                        <div class="stp-day-card">
                                            <h3 class="stp-day-heading"><?php echo esc_html($day['title']); ?></h3>
                                            <div class="stp-day-description">
                                                <?php echo wpautop(wp_kses_post($day['activities'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <style>
                    .stp-itinerary-modern {
                        margin: 60px 0;
                        padding: 0;
                    }
                    .stp-itinerary-header {
                        text-align: center;
                        margin-bottom: 50px;
                    }
                    .stp-itinerary-header h2 {
                        font-size: 36px;
                        font-weight: 700;
                        color: #1a1a1a;
                        margin-bottom: 10px;
                        position: relative;
                        display: inline-block;
                    }
                    .stp-itinerary-header h2:after {
                        content: '';
                        position: absolute;
                        bottom: -10px;
                        left: 50%;
                        transform: translateX(-50%);
                        width: 80px;
                        height: 4px;
                        background: linear-gradient(90deg, #0073aa, #00a0d2);
                        border-radius: 2px;
                    }
                    .stp-itinerary-header p {
                        font-size: 16px;
                        color: #666;
                        margin-top: 20px;
                    }
                    .stp-timeline-wrapper {
                        position: relative;
                        max-width: 1000px;
                        margin: 0 auto;
                    }
                    .stp-timeline-wrapper:before {
                        content: '';
                        position: absolute;
                        left: 40px;
                        top: 0;
                        bottom: 0;
                        width: 3px;
                        background: linear-gradient(180deg, #0073aa, #00a0d2);
                    }
                    .stp-timeline-item {
                        position: relative;
                        display: flex;
                        margin-bottom: 40px;
                        padding-left: 0;
                    }
                    .stp-timeline-marker {
                        flex-shrink: 0;
                        width: 80px;
                        display: flex;
                        justify-content: center;
                        position: relative;
                        z-index: 2;
                    }
                    .stp-day-badge {
                        width: 80px;
                        height: 80px;
                        background: linear-gradient(135deg, #0073aa, #00a0d2);
                        border-radius: 50%;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        box-shadow: 0 4px 20px rgba(0, 115, 170, 0.3);
                        transition: transform 0.3s ease;
                    }
                    .stp-timeline-item:hover .stp-day-badge {
                        transform: scale(1.1);
                    }
                    .day-number {
                        font-size: 28px;
                        font-weight: 700;
                        color: #fff;
                        line-height: 1;
                    }
                    .day-label {
                        font-size: 11px;
                        color: #fff;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        margin-top: 2px;
                    }
                    .stp-timeline-content {
                        flex: 1;
                        padding-left: 30px;
                        padding-top: 5px;
                    }
                    .stp-day-card {
                        background: #fff;
                        border-radius: 12px;
                        padding: 30px;
                        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                        border-left: 4px solid #0073aa;
                        transition: all 0.3s ease;
                    }
                    .stp-day-card:hover {
                        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
                        transform: translateY(-5px);
                    }
                    .stp-day-heading {
                        font-size: 24px;
                        font-weight: 600;
                        color: #1a1a1a;
                        margin: 0 0 20px 0;
                    }
                    .stp-day-description {
                        font-size: 15px;
                        line-height: 1.8;
                        color: #555;
                    }
                    .stp-day-description p {
                        margin-bottom: 15px;
                    }
                    .stp-day-description p:last-child {
                        margin-bottom: 0;
                    }
                    .stp-day-description strong {
                        color: #0073aa;
                        font-weight: 600;
                    }
                    .stp-day-description ul, .stp-day-description ol {
                        margin: 15px 0;
                        padding-left: 25px;
                    }
                    .stp-day-description li {
                        margin-bottom: 10px;
                        position: relative;
                    }
                    .stp-day-description ul li:before {
                        content: '✓';
                        position: absolute;
                        left: -25px;
                        color: #0073aa;
                        font-weight: bold;
                    }
                    .stp-day-description a {
                        color: #0073aa;
                        text-decoration: none;
                        border-bottom: 1px solid rgba(0, 115, 170, 0.3);
                        transition: all 0.2s;
                    }
                    .stp-day-description a:hover {
                        border-bottom-color: #0073aa;
                    }

                    @media (max-width: 768px) {
                        .stp-itinerary-header h2 {
                            font-size: 28px;
                        }
                        .stp-timeline-wrapper:before {
                            left: 30px;
                        }
                        .stp-timeline-marker {
                            width: 60px;
                        }
                        .stp-day-badge {
                            width: 60px;
                            height: 60px;
                        }
                        .day-number {
                            font-size: 22px;
                        }
                        .day-label {
                            font-size: 10px;
                        }
                        .stp-timeline-content {
                            padding-left: 20px;
                        }
                        .stp-day-card {
                            padding: 20px;
                        }
                        .stp-day-heading {
                            font-size: 20px;
                        }
                    }
                    </style>
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
