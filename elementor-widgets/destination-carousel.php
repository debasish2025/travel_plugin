<?php
/**
 * Elementor Wedyara Advanced Destination Carousel Widget
 * Professional carousel with extensive customization options
 */

if (!defined('ABSPATH')) exit;

class Elementor_Destination_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wedyara_destination_carousel';
    }

    public function get_title() {
        return 'Wedyara - Destination Carousel';
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {

        // ============ CONTENT SECTION ============
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Multiple Category Selection
        $this->add_control(
            'categories',
            [
                'label' => 'Select Categories',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_package_categories(),
                'default' => [],
                'label_block' => true,
                'description' => 'Select one or more categories. Leave empty to show all packages.',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => 'Number of Packages',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 12,
                'min' => 1,
                'max' => 50,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => 'Order By',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'date' => 'Date',
                    'title' => 'Title',
                    'rand' => 'Random',
                    'menu_order' => 'Menu Order',
                ],
                'default' => 'date',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => 'Order',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'DESC' => 'Descending',
                    'ASC' => 'Ascending',
                ],
                'default' => 'DESC',
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
                    '{{WRAPPER}} .wedyara-carousel-card' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .wedyara-carousel-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .wedyara-carousel-card',
            ]
        );

        $this->add_control(
            'card_padding',
            [
                'label' => 'Card Content Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 20,
                    'right' => 15,
                    'bottom' => 20,
                    'left' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-carousel-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                'tablet_default' => ['size' => 220, 'unit' => 'px'],
                'mobile_default' => ['size' => 200, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-carousel-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_object_fit',
            [
                'label' => 'Image Fit',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'cover' => 'Cover',
                    'contain' => 'Contain',
                    'fill' => 'Fill',
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-carousel-image img' => 'object-fit: {{VALUE}};',
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
                    '{{WRAPPER}} .wedyara-carousel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .wedyara-carousel-title',
            ]
        );

        $this->add_control(
            'title_alignment',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                    'right' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-carousel-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => 'Bottom Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 8, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-carousel-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ============ STYLE: SUBTITLE ============
        $this->start_controls_section(
            'style_subtitle',
            [
                'label' => 'Subtitle Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_subtitle' => 'yes'],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-carousel-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .wedyara-carousel-subtitle',
            ]
        );

        $this->add_control(
            'subtitle_alignment',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                    'right' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-carousel-subtitle' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ============ STYLE: DURATION ============
        $this->start_controls_section(
            'style_duration',
            [
                'label' => 'Duration Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_duration' => 'yes'],
            ]
        );

        $this->add_control(
            'duration_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-carousel-duration' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'duration_typography',
                'selector' => '{{WRAPPER}} .wedyara-carousel-duration',
            ]
        );

        $this->end_controls_section();

        // ============ STYLE: PRICE ============
        $this->start_controls_section(
            'style_price',
            [
                'label' => 'Price Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => ['show_price' => 'yes'],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-carousel-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .wedyara-carousel-price',
            ]
        );

        $this->add_control(
            'price_alignment',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                    'right' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-carousel-price' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ============ STYLE: NAVIGATION ============
        $this->start_controls_section(
            'style_navigation',
            [
                'label' => 'Navigation Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'arrow_size',
            [
                'label' => 'Arrow Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 30, 'max' => 80]],
                'default' => ['size' => 50, 'unit' => 'px'],
                'condition' => ['show_arrows' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => 'Arrow Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
                'condition' => ['show_arrows' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_background',
            [
                'label' => 'Arrow Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.95)',
                'condition' => ['show_arrows' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label' => 'Dot Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
                'condition' => ['show_dots' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_package_categories() {
        $categories = get_terms(array(
            'taxonomy' => 'package_category',
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
        ));

        $options = array();

        if (!is_wp_error($categories) && !empty($categories)) {
            foreach ($categories as $cat) {
                $options[$cat->term_id] = $cat->name;
            }
        } else {
            // If no categories exist, show a helpful message
            $options = array('' => 'No categories found - Please create categories first');
        }

        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Enqueue Swiper CSS and JS
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
        wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);

        $args = array(
            'post_type' => 'travel_package',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'post_status' => 'publish',
        );

        // Multiple category filter
        if (!empty($settings['categories']) && $settings['categories'][0] !== '') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'package_category',
                    'field' => 'term_id',
                    'terms' => $settings['categories'],
                    'operator' => 'IN',
                )
            );
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            $carousel_id = 'wedyara-carousel-' . uniqid();
            ?>
            <div class="wedyara-carousel-wrapper">
                <?php if ($settings['show_arrows'] === 'yes'): ?>
                <!-- Navigation Arrows -->
                <div class="swiper-button-prev wedyara-prev-<?php echo esc_attr($carousel_id); ?>"></div>
                <div class="swiper-button-next wedyara-next-<?php echo esc_attr($carousel_id); ?>"></div>
                <?php endif; ?>

                <!-- Swiper Container -->
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
                                <a href="<?php the_permalink(); ?>" class="wedyara-carousel-card">
                                    <div class="wedyara-carousel-image">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium_large'); ?>
                                        <?php else : ?>
                                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: bold;">
                                                <?php echo esc_html(substr(get_the_title(), 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="wedyara-carousel-content">
                                        <h3 class="wedyara-carousel-title">
                                            <?php the_title(); ?>
                                        </h3>
                                        <?php if ($settings['show_subtitle'] === 'yes' && $subtitle) : ?>
                                            <p class="wedyara-carousel-subtitle">
                                                <?php echo esc_html($subtitle); ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($settings['show_duration'] === 'yes' && $days && $nights): ?>
                                            <p class="wedyara-carousel-duration">
                                                <?php echo esc_html($days . ' Days / ' . $nights . ' Nights'); ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($settings['show_price'] === 'yes' && $price): ?>
                                            <p class="wedyara-carousel-price">
                                                From $<?php echo esc_html($price); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <?php if ($settings['show_dots'] === 'yes'): ?>
                    <!-- Pagination -->
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
                            },
                            <?php endif; ?>
                            <?php if ($settings['show_dots'] === 'yes'): ?>
                            pagination: {
                                el: '.wedyara-pagination-<?php echo esc_js($carousel_id); ?>',
                                clickable: true,
                                dynamicBullets: true,
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
            <?php
        else:
            echo '<p>No packages found.</p>';
        endif;

        wp_reset_postdata();
    }

    public function get_script_depends() {
        return ['jquery'];
    }

    public function get_style_depends() {
        return [];
    }
}
