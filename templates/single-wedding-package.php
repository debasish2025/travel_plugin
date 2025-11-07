<?php
/**
 * Single Wedding Package Template - Ultra Modern Corporate Wedding Theme
 * Very professional, clean, and elegant design for wedding packages
 */

get_header();

while (have_posts()) : the_post();

    $subtitle = get_post_meta(get_the_ID(), '_subtitle', true);
    $days = get_post_meta(get_the_ID(), '_days', true);
    $nights = get_post_meta(get_the_ID(), '_nights', true);
    $itinerary = get_post_meta(get_the_ID(), '_itinerary', true);
    $template = get_post_meta(get_the_ID(), '_page_template', true);

    // Get feature visibility settings
    $show_reason = get_post_meta(get_the_ID(), '_show_reason', true) === '1';
    $show_duration = get_post_meta(get_the_ID(), '_show_duration', true) === '1';
    $show_season = get_post_meta(get_the_ID(), '_show_season', true) === '1';
    $show_activity_types = get_post_meta(get_the_ID(), '_show_activity_types', true) === '1';

    if (!$template || $template == 'default') {
        $template = 'default';
    }

    ?>
    <style>
        /* Ultra Modern Corporate Wedding Theme */
        :root {
            --wedding-primary: #d4af37;
            --wedding-secondary: #8b7355;
            --wedding-accent: #e6d5b8;
            --wedding-dark: #2c2c2c;
            --wedding-light: #f8f8f8;
            --wedding-text: #333;
        }

        .wedding-package-wrapper {
            font-family: 'Cormorant Garamond', 'Georgia', serif;
            background: linear-gradient(135deg, #ffffff 0%, #f8f5f0 100%);
            min-height: 100vh;
        }

        /* Hero Section - Ultra Modern */
        .wedding-hero {
            position: relative;
            height: 75vh;
            min-height: 600px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--wedding-dark) 0%, var(--wedding-secondary) 100%);
        }

        .wedding-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            <?php if (has_post_thumbnail()): ?>
            background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>');
            <?php endif; ?>
            background-size: cover;
            background-position: center;
            opacity: 0.4;
            z-index: 1;
        }

        .wedding-hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(44, 44, 44, 0.7) 0%, rgba(139, 115, 85, 0.85) 100%);
            z-index: 2;
        }

        .wedding-hero-content {
            position: relative;
            z-index: 3;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 60px 30px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .wedding-hero-title {
            font-size: 72px;
            font-weight: 300;
            color: white;
            margin: 0 0 25px 0;
            letter-spacing: 3px;
            text-transform: uppercase;
            line-height: 1.2;
            animation: fadeInUp 1s ease-out;
        }

        .wedding-hero-subtitle {
            font-size: 28px;
            color: var(--wedding-accent);
            margin: 0 0 40px 0;
            font-weight: 300;
            letter-spacing: 1px;
            animation: fadeInUp 1.2s ease-out;
        }

        .wedding-hero-meta {
            display: flex;
            gap: 40px;
            margin-top: 30px;
            animation: fadeInUp 1.4s ease-out;
        }

        .wedding-meta-item {
            background: rgba(212, 175, 55, 0.15);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-radius: 50px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            color: white;
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 1px;
            transition: all 0.4s ease;
        }

        .wedding-meta-item:hover {
            background: rgba(212, 175, 55, 0.3);
            border-color: var(--wedding-primary);
            transform: translateY(-3px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Main Content Container */
        .wedding-content-wrapper {
            max-width: 1400px;
            margin: -100px auto 0;
            padding: 0 30px 80px;
            position: relative;
            z-index: 10;
        }

        /* Info Card - Elegant Box */
        .wedding-info-card {
            background: white;
            border-radius: 20px;
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            margin-bottom: 60px;
            border: 1px solid var(--wedding-accent);
        }

        .wedding-section-title {
            font-size: 42px;
            font-weight: 300;
            color: var(--wedding-dark);
            margin: 0 0 30px 0;
            letter-spacing: 2px;
            text-align: center;
            text-transform: uppercase;
            position: relative;
            padding-bottom: 20px;
        }

        .wedding-section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--wedding-primary), transparent);
        }

        .wedding-description {
            font-size: 18px;
            line-height: 1.9;
            color: var(--wedding-text);
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Features Grid - Modern Corporate */
        .wedding-features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 60px;
        }

        .wedding-feature-box {
            background: white;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 2px solid var(--wedding-accent);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .wedding-feature-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--wedding-primary), var(--wedding-secondary));
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .wedding-feature-box:hover::before {
            transform: scaleX(1);
        }

        .wedding-feature-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(212, 175, 55, 0.3);
            border-color: var(--wedding-primary);
        }

        .wedding-feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
        }

        .wedding-feature-title {
            font-size: 22px;
            font-weight: 600;
            color: var(--wedding-dark);
            margin: 0 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .wedding-feature-content {
            font-size: 16px;
            line-height: 1.7;
            color: #666;
        }

        .wedding-feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .wedding-feature-list li {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            color: var(--wedding-text);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .wedding-feature-list li::before {
            content: '◆';
            color: var(--wedding-primary);
            font-size: 12px;
        }

        /* Itinerary Timeline - Corporate Style */
        .wedding-itinerary {
            background: white;
            border-radius: 20px;
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            margin-bottom: 60px;
            border: 1px solid var(--wedding-accent);
        }

        .wedding-timeline {
            position: relative;
            padding-left: 60px;
            margin-top: 50px;
        }

        .wedding-timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--wedding-primary), var(--wedding-secondary));
        }

        .wedding-day-item {
            position: relative;
            margin-bottom: 50px;
            padding: 30px;
            background: #fafafa;
            border-radius: 15px;
            border-left: 4px solid var(--wedding-primary);
            transition: all 0.3s ease;
        }

        .wedding-day-item:hover {
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transform: translateX(10px);
        }

        .wedding-day-item::before {
            content: '';
            position: absolute;
            left: -66px;
            top: 30px;
            width: 16px;
            height: 16px;
            background: var(--wedding-primary);
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 0 0 3px var(--wedding-accent);
        }

        .wedding-day-number {
            font-size: 16px;
            font-weight: 700;
            color: var(--wedding-primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .wedding-day-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--wedding-dark);
            margin: 0 0 15px 0;
        }

        .wedding-day-content {
            font-size: 17px;
            line-height: 1.8;
            color: #555;
        }

        /* CTA Section - Ultra Corporate */
        .wedding-cta {
            background: linear-gradient(135deg, var(--wedding-dark) 0%, var(--wedding-secondary) 100%);
            border-radius: 20px;
            padding: 80px 60px;
            text-align: center;
            color: white;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .wedding-cta-title {
            font-size: 48px;
            font-weight: 300;
            margin: 0 0 20px 0;
            letter-spacing: 2px;
        }

        .wedding-cta-text {
            font-size: 20px;
            margin: 0 0 40px 0;
            opacity: 0.9;
        }

        .wedding-cta-button {
            display: inline-block;
            padding: 20px 60px;
            background: var(--wedding-primary);
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 50px;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        }

        .wedding-cta-button:hover {
            background: var(--wedding-secondary);
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.6);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .wedding-hero-title {
                font-size: 42px;
            }

            .wedding-hero-subtitle {
                font-size: 20px;
            }

            .wedding-hero-meta {
                flex-direction: column;
                gap: 15px;
            }

            .wedding-info-card,
            .wedding-itinerary,
            .wedding-cta {
                padding: 40px 30px;
            }

            .wedding-section-title {
                font-size: 32px;
            }

            .wedding-features-grid {
                grid-template-columns: 1fr;
            }

            .wedding-timeline {
                padding-left: 40px;
            }

            .wedding-timeline::before {
                left: 15px;
            }

            .wedding-day-item::before {
                left: -51px;
            }
        }
    </style>

    <article id="post-<?php the_ID(); ?>" <?php post_class('wedding-package-wrapper'); ?>>

        <!-- Hero Section -->
        <div class="wedding-hero">
            <div class="wedding-hero-content">
                <h1 class="wedding-hero-title"><?php the_title(); ?></h1>
                <?php if ($subtitle): ?>
                    <p class="wedding-hero-subtitle"><?php echo esc_html($subtitle); ?></p>
                <?php endif; ?>

                <?php if ($days || $nights): ?>
                    <div class="wedding-hero-meta">
                        <?php if ($days): ?>
                            <div class="wedding-meta-item">
                                <?php echo esc_html($days); ?> Days Celebration
                            </div>
                        <?php endif; ?>
                        <?php if ($nights): ?>
                            <div class="wedding-meta-item">
                                <?php echo esc_html($nights); ?> Nights Stay
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Content -->
        <div class="wedding-content-wrapper">

            <!-- Description Section -->
            <?php if (get_the_content()): ?>
                <div class="wedding-info-card">
                    <h2 class="wedding-section-title">Your Dream Wedding</h2>
                    <div class="wedding-description">
                        <?php the_content(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Features/Taxonomies Section -->
            <?php
            $has_features = false;
            $features_html = '';

            // Check what features to show
            if ($show_reason) {
                $reasons = get_the_terms(get_the_ID(), 'wedding_region');
                if ($reasons && !is_wp_error($reasons)) {
                    $has_features = true;
                    $features_html .= '<div class="wedding-feature-box"><div class="wedding-feature-icon">📍</div><h3 class="wedding-feature-title">Location</h3><ul class="wedding-feature-list">';
                    foreach ($reasons as $reason) {
                        $features_html .= '<li>' . esc_html($reason->name) . '</li>';
                    }
                    $features_html .= '</ul></div>';
                }
            }

            if ($show_duration) {
                $durations = get_the_terms(get_the_ID(), 'wedding_duration');
                if ($durations && !is_wp_error($durations)) {
                    $has_features = true;
                    $features_html .= '<div class="wedding-feature-box"><div class="wedding-feature-icon">⏰</div><h3 class="wedding-feature-title">Duration</h3><ul class="wedding-feature-list">';
                    foreach ($durations as $duration) {
                        $features_html .= '<li>' . esc_html($duration->name) . '</li>';
                    }
                    $features_html .= '</ul></div>';
                }
            }

            if ($show_activity_types) {
                $activities = get_the_terms(get_the_ID(), 'wedding_activity');
                if ($activities && !is_wp_error($activities)) {
                    $has_features = true;
                    $features_html .= '<div class="wedding-feature-box"><div class="wedding-feature-icon">✨</div><h3 class="wedding-feature-title">Activities & Services</h3><ul class="wedding-feature-list">';
                    foreach ($activities as $activity) {
                        $features_html .= '<li>' . esc_html($activity->name) . '</li>';
                    }
                    $features_html .= '</ul></div>';
                }
            }

            if ($has_features): ?>
                <div class="wedding-info-card">
                    <h2 class="wedding-section-title">Package Features</h2>
                    <div class="wedding-features-grid">
                        <?php echo $features_html; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Itinerary Section -->
            <?php if (!empty($itinerary) && is_array($itinerary)): ?>
                <div class="wedding-itinerary">
                    <h2 class="wedding-section-title">Your Wedding Journey</h2>
                    <div class="wedding-timeline">
                        <?php foreach ($itinerary as $index => $day): ?>
                            <div class="wedding-day-item">
                                <div class="wedding-day-number">Day <?php echo ($index + 1); ?></div>
                                <h3 class="wedding-day-title"><?php echo esc_html($day['title']); ?></h3>
                                <div class="wedding-day-content">
                                    <?php echo wp_kses_post($day['activities']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- CTA Section -->
            <div class="wedding-cta">
                <h2 class="wedding-cta-title">Ready to Begin Your Journey?</h2>
                <p class="wedding-cta-text">Let us help you create the wedding of your dreams</p>
                <a href="#contact" class="wedding-cta-button">Get In Touch</a>
            </div>

        </div>

    </article>

    <?php
endwhile;

get_footer();
