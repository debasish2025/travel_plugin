<?php
/**
 * Single Wedding Package Template - Ultra Modern Corporate Wedding Theme
 * With Creative Tabs UI, Social Share, and WhatsApp CTA
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

    // Get current page URL for sharing
    $page_url = get_permalink();
    $page_title = get_the_title();
    $page_image = get_the_post_thumbnail_url(get_the_ID(), 'full');

    // WhatsApp message
    $whatsapp_message = "Check out this amazing wedding package: " . $page_title . " - " . $page_url;
    $whatsapp_link = "https://wa.me/?text=" . urlencode($whatsapp_message);

    error_log('===== WEDYARA DEBUG: Single Wedding Package =====');
    error_log('Post ID: ' . get_the_ID());
    error_log('Title: ' . $page_title);
    error_log('Has Features: ' . ($show_reason || $show_duration || $show_activity_types ? 'YES' : 'NO'));
    error_log('Has Itinerary: ' . (!empty($itinerary) ? 'YES' : 'NO'));

    ?>
    <style>
        /* Ultra Modern Corporate Wedding Theme with Tabs */
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

        /* Hero Section */
        .wedding-hero {
            position: relative;
            height: 70vh;
            min-height: 500px;
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
            background-image: url('<?php echo esc_url($page_image); ?>');
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
            padding: 40px 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .wedding-hero-title {
            font-size: clamp(32px, 8vw, 72px);
            font-weight: 300;
            color: white;
            margin: 0 0 20px 0;
            letter-spacing: 3px;
            text-transform: uppercase;
            line-height: 1.2;
            animation: fadeInUp 1s ease-out;
        }

        .wedding-hero-subtitle {
            font-size: clamp(18px, 4vw, 28px);
            color: var(--wedding-accent);
            margin: 0 0 30px 0;
            font-weight: 300;
            letter-spacing: 1px;
            animation: fadeInUp 1.2s ease-out;
        }

        .wedding-hero-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
            animation: fadeInUp 1.4s ease-out;
        }

        .wedding-meta-item {
            background: rgba(212, 175, 55, 0.15);
            backdrop-filter: blur(10px);
            padding: 12px 25px;
            border-radius: 50px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            color: white;
            font-size: 15px;
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

        /* Social Share & WhatsApp Section */
        .wedding-actions {
            position: relative;
            z-index: 5;
            max-width: 1400px;
            margin: -60px auto 0;
            padding: 0 20px;
        }

        .wedding-actions-card {
            background: white;
            border-radius: 20px;
            padding: 25px 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            border: 2px solid var(--wedding-accent);
        }

        .social-share {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .social-share-label {
            font-size: 16px;
            font-weight: 600;
            color: var(--wedding-dark);
            margin-right: 10px;
        }

        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
            font-size: 20px;
        }

        .social-btn:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .social-facebook { background: #3b5998; }
        .social-twitter { background: #1da1f2; }
        .social-linkedin { background: #0077b5; }
        .social-pinterest { background: #bd081c; }

        .whatsapp-cta {
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            color: white;
            padding: 15px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.4s ease;
            box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);
        }

        .whatsapp-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(37, 211, 102, 0.5);
            background: linear-gradient(135deg, #128c7e 0%, #25d366 100%);
        }

        /* Main Content Container */
        .wedding-content-wrapper {
            max-width: 1400px;
            margin: 40px auto 80px;
            padding: 0 20px;
        }

        /* Ultra Modern Tabs Navigation */
        .wedding-tabs-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 2px solid var(--wedding-accent);
        }

        .wedding-tabs-nav {
            display: flex;
            background: linear-gradient(135deg, var(--wedding-dark) 0%, var(--wedding-secondary) 100%);
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--wedding-primary) transparent;
        }

        .wedding-tabs-nav::-webkit-scrollbar {
            height: 4px;
        }

        .wedding-tabs-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .wedding-tabs-nav::-webkit-scrollbar-thumb {
            background: var(--wedding-primary);
            border-radius: 10px;
        }

        .wedding-tab-btn {
            flex: 1;
            min-width: 150px;
            padding: 20px 30px;
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            font-family: inherit;
        }

        .wedding-tab-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--wedding-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .wedding-tab-btn:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .wedding-tab-btn.active {
            color: var(--wedding-primary);
            background: rgba(212, 175, 55, 0.1);
        }

        .wedding-tab-btn.active::after {
            transform: scaleX(1);
        }

        .wedding-tabs-content {
            padding: 50px;
        }

        .wedding-tab-pane {
            display: none;
            animation: fadeIn 0.5s ease-out;
        }

        .wedding-tab-pane.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Tab Content Styles */
        .wedding-description {
            font-size: 18px;
            line-height: 1.9;
            color: var(--wedding-text);
        }

        .wedding-features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .wedding-feature-box {
            background: var(--wedding-light);
            border-radius: 15px;
            padding: 35px;
            text-align: center;
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
            background: white;
        }

        .wedding-feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
        }

        .wedding-feature-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--wedding-dark);
            margin: 0 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .wedding-feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .wedding-feature-list li {
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
            color: var(--wedding-text);
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .wedding-feature-list li::before {
            content: '◆';
            color: var(--wedding-primary);
            font-size: 10px;
        }

        .wedding-feature-list li:last-child {
            border-bottom: none;
        }

        /* Timeline */
        .wedding-timeline {
            position: relative;
            padding-left: 50px;
        }

        .wedding-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--wedding-primary), var(--wedding-secondary));
        }

        .wedding-day-item {
            position: relative;
            margin-bottom: 40px;
            padding: 25px;
            background: var(--wedding-light);
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
            left: -56px;
            top: 25px;
            width: 14px;
            height: 14px;
            background: var(--wedding-primary);
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 0 0 3px var(--wedding-accent);
        }

        .wedding-day-number {
            font-size: 14px;
            font-weight: 700;
            color: var(--wedding-primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .wedding-day-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--wedding-dark);
            margin: 0 0 12px 0;
        }

        .wedding-day-content {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
        }

        /* CTA in Tab */
        .wedding-cta-box {
            background: linear-gradient(135deg, var(--wedding-dark) 0%, var(--wedding-secondary) 100%);
            border-radius: 15px;
            padding: 50px;
            text-align: center;
            color: white;
        }

        .wedding-cta-title {
            font-size: 36px;
            font-weight: 300;
            margin: 0 0 15px 0;
            letter-spacing: 2px;
        }

        .wedding-cta-text {
            font-size: 18px;
            margin: 0 0 30px 0;
            opacity: 0.9;
        }

        .wedding-cta-button {
            display: inline-block;
            padding: 18px 50px;
            background: var(--wedding-primary);
            color: white;
            text-decoration: none;
            font-size: 16px;
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
            .wedding-hero {
                height: 60vh;
                min-height: 400px;
            }

            .wedding-actions-card {
                flex-direction: column;
                padding: 20px;
                text-align: center;
            }

            .social-share {
                justify-content: center;
                width: 100%;
            }

            .whatsapp-cta {
                width: 100%;
                justify-content: center;
            }

            .wedding-tab-btn {
                min-width: 120px;
                padding: 15px 20px;
                font-size: 14px;
            }

            .wedding-tabs-content {
                padding: 30px 20px;
            }

            .wedding-features-grid {
                grid-template-columns: 1fr;
            }

            .wedding-timeline {
                padding-left: 35px;
            }

            .wedding-timeline::before {
                left: 10px;
            }

            .wedding-day-item::before {
                left: -41px;
            }

            .wedding-cta-box {
                padding: 35px 25px;
            }

            .wedding-cta-title {
                font-size: 28px;
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
                                <?php echo esc_html($days); ?> Days
                            </div>
                        <?php endif; ?>
                        <?php if ($nights): ?>
                            <div class="wedding-meta-item">
                                <?php echo esc_html($nights); ?> Nights
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Social Share & WhatsApp Actions -->
        <div class="wedding-actions">
            <div class="wedding-actions-card">
                <div class="social-share">
                    <span class="social-share-label">Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($page_url); ?>"
                       target="_blank"
                       class="social-btn social-facebook"
                       title="Share on Facebook"
                       onclick="console.log('DEBUG: Facebook share clicked');">
                        <span>f</span>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($page_url); ?>&text=<?php echo urlencode($page_title); ?>"
                       target="_blank"
                       class="social-btn social-twitter"
                       title="Share on Twitter"
                       onclick="console.log('DEBUG: Twitter share clicked');">
                        <span>𝕏</span>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($page_url); ?>&title=<?php echo urlencode($page_title); ?>"
                       target="_blank"
                       class="social-btn social-linkedin"
                       title="Share on LinkedIn"
                       onclick="console.log('DEBUG: LinkedIn share clicked');">
                        <span>in</span>
                    </a>
                    <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode($page_url); ?>&media=<?php echo urlencode($page_image); ?>&description=<?php echo urlencode($page_title); ?>"
                       target="_blank"
                       class="social-btn social-pinterest"
                       title="Share on Pinterest"
                       onclick="console.log('DEBUG: Pinterest share clicked');">
                        <span>P</span>
                    </a>
                </div>
                <a href="<?php echo esc_url($whatsapp_link); ?>"
                   target="_blank"
                   class="whatsapp-cta"
                   onclick="console.log('DEBUG: WhatsApp CTA clicked');">
                    <span style="font-size: 24px;">📱</span>
                    <span>Inquire on WhatsApp</span>
                </a>
            </div>
        </div>

        <!-- Main Content with Tabs -->
        <div class="wedding-content-wrapper">
            <div class="wedding-tabs-container">
                <!-- Tabs Navigation -->
                <div class="wedding-tabs-nav">
                    <button class="wedding-tab-btn active" data-tab="overview" onclick="wedyaraSwitchTab(event, 'overview')">
                        Overview
                    </button>
                    <?php if ($show_reason || $show_duration || $show_activity_types): ?>
                    <button class="wedding-tab-btn" data-tab="features" onclick="wedyaraSwitchTab(event, 'features')">
                        Features
                    </button>
                    <?php endif; ?>
                    <?php if (!empty($itinerary) && is_array($itinerary)): ?>
                    <button class="wedding-tab-btn" data-tab="itinerary" onclick="wedyaraSwitchTab(event, 'itinerary')">
                        Itinerary
                    </button>
                    <?php endif; ?>
                    <button class="wedding-tab-btn" data-tab="contact" onclick="wedyaraSwitchTab(event, 'contact')">
                        Contact Us
                    </button>
                </div>

                <!-- Tabs Content -->
                <div class="wedding-tabs-content">
                    <!-- Overview Tab -->
                    <div class="wedding-tab-pane active" id="tab-overview">
                        <?php if (get_the_content()): ?>
                            <div class="wedding-description">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Features Tab -->
                    <?php if ($show_reason || $show_duration || $show_activity_types): ?>
                    <div class="wedding-tab-pane" id="tab-features">
                        <div class="wedding-features-grid">
                            <?php
                            if ($show_reason) {
                                $reasons = get_the_terms(get_the_ID(), 'wedding_region');
                                if ($reasons && !is_wp_error($reasons)) {
                                    echo '<div class="wedding-feature-box"><div class="wedding-feature-icon">📍</div><h3 class="wedding-feature-title">Location</h3><ul class="wedding-feature-list">';
                                    foreach ($reasons as $reason) {
                                        echo '<li>' . esc_html($reason->name) . '</li>';
                                    }
                                    echo '</ul></div>';
                                }
                            }

                            if ($show_duration) {
                                $durations = get_the_terms(get_the_ID(), 'wedding_duration');
                                if ($durations && !is_wp_error($durations)) {
                                    echo '<div class="wedding-feature-box"><div class="wedding-feature-icon">⏰</div><h3 class="wedding-feature-title">Duration</h3><ul class="wedding-feature-list">';
                                    foreach ($durations as $duration) {
                                        echo '<li>' . esc_html($duration->name) . '</li>';
                                    }
                                    echo '</ul></div>';
                                }
                            }

                            if ($show_activity_types) {
                                $activities = get_the_terms(get_the_ID(), 'wedding_activity');
                                if ($activities && !is_wp_error($activities)) {
                                    echo '<div class="wedding-feature-box"><div class="wedding-feature-icon">✨</div><h3 class="wedding-feature-title">Activities & Services</h3><ul class="wedding-feature-list">';
                                    foreach ($activities as $activity) {
                                        echo '<li>' . esc_html($activity->name) . '</li>';
                                    }
                                    echo '</ul></div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Itinerary Tab -->
                    <?php if (!empty($itinerary) && is_array($itinerary)): ?>
                    <div class="wedding-tab-pane" id="tab-itinerary">
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

                    <!-- Contact Tab -->
                    <div class="wedding-tab-pane" id="tab-contact">
                        <div class="wedding-cta-box">
                            <h2 class="wedding-cta-title">Ready to Begin Your Journey?</h2>
                            <p class="wedding-cta-text">Let us help you create the wedding of your dreams</p>
                            <a href="<?php echo esc_url($whatsapp_link); ?>" target="_blank" class="wedding-cta-button" onclick="console.log('DEBUG: Contact WhatsApp button clicked');">
                                Contact Us on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </article>

    <script>
        console.log('DEBUG: Wedding package template loaded');
        console.log('DEBUG: Post ID:', <?php echo get_the_ID(); ?>);
        console.log('DEBUG: Has itinerary:', <?php echo !empty($itinerary) ? 'true' : 'false'; ?>);

        function wedyaraSwitchTab(event, tabName) {
            console.log('DEBUG: Switching to tab:', tabName);

            // Hide all tab panes
            var tabPanes = document.querySelectorAll('.wedding-tab-pane');
            tabPanes.forEach(function(pane) {
                pane.classList.remove('active');
            });

            // Remove active class from all buttons
            var tabButtons = document.querySelectorAll('.wedding-tab-btn');
            tabButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });

            // Show selected tab
            var selectedTab = document.getElementById('tab-' + tabName);
            if (selectedTab) {
                selectedTab.classList.add('active');
                console.log('DEBUG: Tab activated:', tabName);
            } else {
                console.error('DEBUG: Tab not found:', tabName);
            }

            // Add active class to clicked button
            event.currentTarget.classList.add('active');
        }

        // Log when social buttons are clicked
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DEBUG: Wedding package page fully loaded');
            console.log('DEBUG: Tabs count:', document.querySelectorAll('.wedding-tab-btn').length);
        });
    </script>

    <?php
endwhile;

get_footer();
