<?php
/**
 * Elementor Wedyara Selected Packages Carousel Widget
 * Allows manual selection of specific packages to display in carousel
 */

if (!defined('ABSPATH')) exit;

class Elementor_Selected_Packages_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wedyara_selected_packages_carousel';
    }

    public function get_title() {
        return 'Wedyara - Selected Packages Carousel';
    }

    public function get_icon() {
        return 'eicon-posts-carousel';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {

        // ============ CONTENT SECTION ============
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Package Selection',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Package Type Selector
        $this->add_control(
            'package_type',
            [
                'label' => 'Package Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'travel_package' => 'Travel Packages',
                    'wedding_package' => 'Wedding Packages',
                ],
                'default' => 'travel_package',
                'description' => 'Choose which type of packages to select from',
            ]
        );

        // Travel Packages Selector
        $this->add_control(
            'selected_travel_packages',
            [
                'label' => 'Select Travel Packages',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_travel_packages(),
                'default' => [],
                'label_block' => true,
                'description' => 'Choose specific travel packages to display in the carousel',
                'condition' => [
                    'package_type' => 'travel_package',
                ],
            ]
        );

        // Wedding Packages Selector
        $this->add_control(
            'selected_wedding_packages',
            [
                'label' => 'Select Wedding Packages',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_wedding_packages(),
                'default' => [],
                'label_block' => true,
                'description' => 'Choose specific wedding packages to display in the carousel',
                'condition' => [
                    'package_type' => 'wedding_package',
                ],
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => 'Order By',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'post__in' => 'Selected Order',
                    'date' => 'Date',
                    'title' => 'Title',
                    'rand' => 'Random',
                ],
                'default' => 'post__in',
            ]
        );

        $this->end_controls_section();

        // ============ CAROUSEL SETTINGS ============
        $this->start_controls_section(
            'carousel_settings',
            [
                'label' => 'Carousel Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slides_to_show_desktop',
            [
                'label' => 'Slides to Show (Desktop)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 1,
                'max' => 6,
            ]
        );

        $this->add_control(
            'slides_to_show_tablet',
            [
                'label' => 'Slides to Show (Tablet)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 4,
            ]
        );

        $this->add_control(
            'slides_to_show_mobile',
            [
                'label' => 'Slides to Show (Mobile)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
                'max' => 3,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => 'Autoplay',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => 'Autoplay Speed (ms)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'min' => 1000,
                'max' => 10000,
                'condition' => ['autoplay' => 'yes'],
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => 'Show Navigation Arrows',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => 'Show Pagination Dots',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // ============ CONTENT DISPLAY ============
        $this->start_controls_section(
            'content_display',
            [
                'label' => 'Content Display',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_subtitle',
            [
                'label' => 'Show Subtitle',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_duration',
            [
                'label' => 'Show Duration',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label' => 'Show Price',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // ============ STYLE: CARD ============
        $this->start_controls_section(
            'style_card',
            [
                'label' => 'Card Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label' => 'Card Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-selected-carousel-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 15, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-selected-carousel-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .wedyara-selected-carousel-card',
            ]
        );

        $this->end_controls_section();

        // ============ STYLE: IMAGE ============
        $this->start_controls_section(
            'style_image',
            [
                'label' => 'Image Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => 'Image Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 150, 'max' => 500],
                ],
                'default' => ['size' => 250, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-selected-carousel-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ============ STYLE: TITLE ============
        $this->start_controls_section(
            'style_title',
            [
                'label' => 'Title Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-selected-carousel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .wedyara-selected-carousel-title',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get list of travel packages
     */
    private function get_travel_packages() {
        $packages = get_posts(array(
            'post_type' => 'travel_package',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        ));

        $options = array();
        foreach ($packages as $package) {
            $options[$package->ID] = $package->post_title;
        }

        return $options;
    }

    /**
     * Get list of wedding packages
     */
    private function get_wedding_packages() {
        $packages = get_posts(array(
            'post_type' => 'wedding_package',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        ));

        $options = array();
        foreach ($packages as $package) {
            $options[$package->ID] = $package->post_title;
        }

        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Enqueue Swiper
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
        wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);

        // Get package type and selected packages
        $package_type = isset($settings['package_type']) ? $settings['package_type'] : 'travel_package';

        if ($package_type === 'wedding_package') {
            $selected_packages = isset($settings['selected_wedding_packages']) ? $settings['selected_wedding_packages'] : array();
        } else {
            $selected_packages = isset($settings['selected_travel_packages']) ? $settings['selected_travel_packages'] : array();
        }

        // If no packages selected, show message
        if (empty($selected_packages)) {
            echo '<p style="padding: 20px; background: #f0f0f0; border-radius: 8px; text-align: center;">Please select packages from the widget settings.</p>';
            return;
        }

        // Build query arguments
        $args = array(
            'post_type' => $package_type,
            'post__in' => $selected_packages,
            'posts_per_page' => count($selected_packages),
            'orderby' => $settings['orderby'],
            'post_status' => 'publish',
        );

        // If orderby is post__in, preserve the selected order
        if ($settings['orderby'] === 'post__in') {
            $args['orderby'] = 'post__in';
        }

        $query = new WP_Query($args);

        // Log errors only
        if (is_wp_error($query)) {
            error_log('WEDYARA ERROR: Selected Packages Carousel - ' . $query->get_error_message());
            return;
        }

        if (!$query->have_posts()) {
            error_log('WEDYARA ERROR: Selected Packages Carousel - No packages found with IDs: ' . implode(',', $selected_packages));
            echo '<p style="padding: 20px; background: #fff3cd; border-radius: 8px; text-align: center;">No packages found. Please check your selection.</p>';
            return;
        }

        $carousel_id = 'wedyara-selected-carousel-' . uniqid();
        ?>
        <div class="wedyara-selected-carousel-wrapper">
            <?php if ($settings['show_arrows'] === 'yes'): ?>
            <div class="swiper-button-prev wedyara-prev-<?php echo esc_attr($carousel_id); ?>"></div>
            <div class="swiper-button-next wedyara-next-<?php echo esc_attr($carousel_id); ?>"></div>
            <?php endif; ?>

            <div class="swiper <?php echo esc_attr($carousel_id); ?>">
                <div class="swiper-wrapper">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <?php
                        $subtitle = get_post_meta(get_the_ID(), '_subtitle', true);
                        $days = get_post_meta(get_the_ID(), '_days', true);
                        $nights = get_post_meta(get_the_ID(), '_nights', true);
                        $price = get_post_meta(get_the_ID(), '_price', true);
                        ?>
                        <div class="swiper-slide">
                            <a href="<?php the_permalink(); ?>" class="wedyara-selected-carousel-card">
                                <div class="wedyara-selected-carousel-image" style="background-image: url('<?php
                                    if (has_post_thumbnail()) {
                                        echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large'));
                                    } else {
                                        echo 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Cdefs%3E%3ClinearGradient id=\'grad\' x1=\'0%25\' y1=\'0%25\' x2=\'100%25\' y2=\'100%25\'%3E%3Cstop offset=\'0%25\' style=\'stop-color:%23667eea;stop-opacity:1\' /%3E%3Cstop offset=\'100%25\' style=\'stop-color:%23764ba2;stop-opacity:1\' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect fill=\'url(%23grad)\' width=\'400\' height=\'300\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' font-family=\'Arial\' font-size=\'72\' fill=\'white\' text-anchor=\'middle\' dy=\'.3em\'%3E' . esc_html(substr(get_the_title(), 0, 1)) . '%3C/text%3E%3C/svg%3E';
                                    }
                                ?>'); background-size: cover; background-position: center;">
                                </div>
                                <div class="wedyara-selected-carousel-content" style="padding: 20px 15px;">
                                    <h3 class="wedyara-selected-carousel-title" style="margin: 0 0 10px 0; font-size: 18px; font-weight: 600;">
                                        <?php the_title(); ?>
                                    </h3>
                                    <?php if ($settings['show_subtitle'] === 'yes' && $subtitle) : ?>
                                        <p class="wedyara-selected-carousel-subtitle" style="margin: 0 0 8px 0; color: #666; font-size: 14px;">
                                            <?php echo esc_html($subtitle); ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($settings['show_duration'] === 'yes' && $days && $nights): ?>
                                        <p class="wedyara-selected-carousel-duration" style="margin: 0 0 10px 0; color: #888; font-size: 14px;">
                                            <?php echo esc_html($days . ' Days / ' . $nights . ' Nights'); ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($settings['show_price'] === 'yes' && $price): ?>
                                        <p class="wedyara-selected-carousel-price" style="margin: 0; color: #667eea; font-size: 20px; font-weight: 600;">
                                            From $<?php echo esc_html($price); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php if ($settings['show_dots'] === 'yes'): ?>
                <div class="swiper-pagination wedyara-pagination-<?php echo esc_attr($carousel_id); ?>"></div>
                <?php endif; ?>
            </div>
        </div>

        <script>
        (function() {
            var swiperInterval = setInterval(function() {
                if (typeof Swiper !== 'undefined') {
                    clearInterval(swiperInterval);

                    new Swiper('.<?php echo esc_js($carousel_id); ?>', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: false,
                        <?php if ($settings['autoplay'] === 'yes'): ?>
                        autoplay: {
                            delay: <?php echo intval($settings['autoplay_speed']); ?>,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true,
                        },
                        <?php endif; ?>
                        <?php if ($settings['show_dots'] === 'yes'): ?>
                        pagination: {
                            el: '.wedyara-pagination-<?php echo esc_js($carousel_id); ?>',
                            clickable: true,
                        },
                        <?php endif; ?>
                        <?php if ($settings['show_arrows'] === 'yes'): ?>
                        navigation: {
                            nextEl: '.wedyara-next-<?php echo esc_js($carousel_id); ?>',
                            prevEl: '.wedyara-prev-<?php echo esc_js($carousel_id); ?>',
                        },
                        <?php endif; ?>
                        breakpoints: {
                            640: {
                                slidesPerView: <?php echo intval($settings['slides_to_show_mobile']); ?>,
                                spaceBetween: 20,
                            },
                            992: {
                                slidesPerView: <?php echo intval($settings['slides_to_show_tablet']); ?>,
                                spaceBetween: 25,
                            },
                            1200: {
                                slidesPerView: <?php echo intval($settings['slides_to_show_desktop']); ?>,
                                spaceBetween: 30,
                            }
                        }
                    });
                }
            }, 100);
        })();
        </script>

        <style>
        .<?php echo esc_attr($carousel_id); ?> .swiper-slide {
            height: auto;
        }
        .<?php echo esc_attr($carousel_id); ?> .wedyara-selected-carousel-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            text-decoration: none;
            color: inherit;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .<?php echo esc_attr($carousel_id); ?> .wedyara-selected-carousel-card:hover {
            transform: translateY(-5px);
        }
        .swiper-button-prev, .swiper-button-next {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.95);
            border-radius: 50%;
            color: #667eea;
        }
        .swiper-button-prev:after, .swiper-button-next:after {
            font-size: 20px;
        }
        </style>
        <?php

        wp_reset_postdata();
    }

    public function get_script_depends() {
        return ['jquery'];
    }
}
