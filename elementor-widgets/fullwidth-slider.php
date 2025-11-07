<?php
/**
 * Elementor Full Width Slider Widget
 * Displays packages in a full-width slider with advanced customization
 */

if (!defined('ABSPATH')) exit;

class Elementor_Fullwidth_Slider_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wedyara_package_slider';
    }

    public function get_title() {
        return 'Wedyara - Package Slider';
    }

    public function get_icon() {
        return 'eicon-slider-album';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {

        // ===================================
        // CONTENT TAB - Query Settings
        // ===================================
        $this->start_controls_section(
            'query_section',
            [
                'label' => 'Query Settings',
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
                'description' => 'Choose which type of package to display',
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => 'Select Categories',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_package_categories(),
                'default' => [],
                'label_block' => true,
                'select2options' => [
                    'placeholder' => 'Select categories...',
                    'allowClear' => true,
                ],
                'description' => 'Select one or more categories. Leave empty to show all packages.',
            ]
        );

        // Region Filter
        $this->add_control(
            'regions',
            [
                'label' => 'Select Regions',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_package_regions(),
                'default' => [],
                'label_block' => true,
                'select2options' => [
                    'placeholder' => 'Select regions...',
                    'allowClear' => true,
                ],
                'description' => 'Select one or more regions. Leave empty to show all regions.',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => 'Number of Packages',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 100,
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
                    'modified' => 'Last Modified',
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

        // ===================================
        // CONTENT TAB - Slider Settings
        // ===================================
        $this->start_controls_section(
            'slider_section',
            [
                'label' => 'Slider Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => 'Slides to Show',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'tablet_default' => 3,
                'mobile_default' => 2,
                'min' => 1,
                'max' => 10,
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
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => 'Show Dots',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => 'Show Arrows',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // ===================================
        // STYLE TAB - Container Style
        // ===================================
        $this->start_controls_section(
            'container_style_section',
            [
                'label' => 'Container Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => 'Background Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#EC407A',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-fullwidth-slider-section' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 60,
                    'right' => 0,
                    'bottom' => 60,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-fullwidth-slider-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===================================
        // STYLE TAB - Image Style
        // ===================================
        $this->start_controls_section(
            'image_style_section',
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
                    'px' => [
                        'min' => 150,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'size' => 250,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 220,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 200,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-fullwidth-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 8,
                    'right' => 8,
                    'bottom' => 8,
                    'left' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-fullwidth-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===================================
        // STYLE TAB - Title Style
        // ===================================
        $this->start_controls_section(
            'title_style_section',
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
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-fullwidth-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .wedyara-fullwidth-title',
            ]
        );

        $this->add_responsive_control(
            'title_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => 'Left',
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => 'Center',
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => 'Right',
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-fullwidth-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => 'Top Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-fullwidth-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===================================
        // STYLE TAB - Navigation Arrows
        // ===================================
        $this->start_controls_section(
            'arrows_style_section',
            [
                'label' => 'Navigation Arrows',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_bg_color',
            [
                'label' => 'Background Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.5)',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_size',
            [
                'label' => 'Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===================================
        // STYLE TAB - Pagination Dots
        // ===================================
        $this->start_controls_section(
            'dots_style_section',
            [
                'label' => 'Pagination Dots',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_active_color',
            [
                'label' => 'Active Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_size',
            [
                'label' => 'Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_package_categories() {
        // Get both travel and wedding categories
        $travel_categories = get_terms(array(
            'taxonomy' => 'tpm_category',
            'hide_empty' => false,
        ));

        $wedding_categories = get_terms(array(
            'taxonomy' => 'wedding_category',
            'hide_empty' => false,
        ));

        error_log('===== WEDYARA SLIDER DEBUG: get_package_categories =====');

        $options = array();

        // Add travel categories with prefix
        if (!is_wp_error($travel_categories) && !empty($travel_categories)) {
            foreach ($travel_categories as $cat) {
                $options['travel_' . $cat->term_id] = '🏝️ ' . $cat->name . ' (Travel)';
                error_log('Travel Category: ID=' . $cat->term_id . ' Name=' . $cat->name);
            }
        }

        // Add wedding categories with prefix
        if (!is_wp_error($wedding_categories) && !empty($wedding_categories)) {
            foreach ($wedding_categories as $cat) {
                $options['wedding_' . $cat->term_id] = '💒 ' . $cat->name . ' (Wedding)';
                error_log('Wedding Category: ID=' . $cat->term_id . ' Name=' . $cat->name);
            }
        }

        error_log('Total categories: ' . count($options));
        error_log('===== END DEBUG =====');

        return $options;
    }

    private function get_package_regions() {
        // Get both travel and wedding regions
        $travel_regions = get_terms(array(
            'taxonomy' => 'tpm_region',
            'hide_empty' => false,
        ));

        $wedding_regions = get_terms(array(
            'taxonomy' => 'wedding_region',
            'hide_empty' => false,
        ));

        error_log('===== WEDYARA SLIDER DEBUG: get_package_regions =====');

        $options = array();

        // Add travel regions with prefix
        if (!is_wp_error($travel_regions) && !empty($travel_regions)) {
            foreach ($travel_regions as $region) {
                $options['travel_' . $region->term_id] = '🏝️ ' . $region->name . ' (Travel)';
                error_log('Travel Region: ID=' . $region->term_id . ' Name=' . $region->name);
            }
        }

        // Add wedding regions with prefix
        if (!is_wp_error($wedding_regions) && !empty($wedding_regions)) {
            foreach ($wedding_regions as $region) {
                $options['wedding_' . $region->term_id] = '💒 ' . $region->name . ' (Wedding)';
                error_log('Wedding Region: ID=' . $region->term_id . ' Name=' . $region->name);
            }
        }

        error_log('Total regions: ' . count($options));
        error_log('===== END DEBUG =====');

        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Get package type (travel_package or wedding_package)
        $package_type = isset($settings['package_type']) ? $settings['package_type'] : 'travel_package';

        // Determine taxonomy names based on package type
        $category_taxonomy = ($package_type === 'wedding_package') ? 'wedding_category' : 'tpm_category';
        $region_taxonomy = ($package_type === 'wedding_package') ? 'wedding_region' : 'tpm_region';

        // Build query arguments
        $args = array(
            'post_type' => $package_type,
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'post_status' => 'publish',
        );

        // DEBUG: Log settings
        error_log('===== WEDYARA SLIDER DEBUG: RENDER =====');
        error_log('Package Type: ' . $package_type);
        error_log('Category Taxonomy: ' . $category_taxonomy);
        error_log('Region Taxonomy: ' . $region_taxonomy);
        error_log('Raw categories: ' . print_r($settings['categories'], true));
        error_log('Raw regions: ' . print_r($settings['regions'], true));

        // Build tax_query array
        $tax_query = array('relation' => 'AND');

        // Category filter
        $selected_categories = isset($settings['categories']) ? $settings['categories'] : array();
        if (is_array($selected_categories)) {
            $selected_categories = array_filter($selected_categories);

            // Extract term IDs based on package type
            $category_term_ids = array();
            $prefix = ($package_type === 'wedding_package') ? 'wedding_' : 'travel_';

            foreach ($selected_categories as $cat) {
                if (strpos($cat, $prefix) === 0) {
                    $term_id = intval(str_replace($prefix, '', $cat));
                    $category_term_ids[] = $term_id;
                }
            }

            if (!empty($category_term_ids)) {
                error_log('Applying category filter: ' . print_r($category_term_ids, true));
                $tax_query[] = array(
                    'taxonomy' => $category_taxonomy,
                    'field' => 'term_id',
                    'terms' => $category_term_ids,
                    'operator' => 'IN',
                );
            }
        }

        // Region filter
        $selected_regions = isset($settings['regions']) ? $settings['regions'] : array();
        if (is_array($selected_regions)) {
            $selected_regions = array_filter($selected_regions);

            // Extract term IDs based on package type
            $region_term_ids = array();
            $prefix = ($package_type === 'wedding_package') ? 'wedding_' : 'travel_';

            foreach ($selected_regions as $region) {
                if (strpos($region, $prefix) === 0) {
                    $term_id = intval(str_replace($prefix, '', $region));
                    $region_term_ids[] = $term_id;
                }
            }

            if (!empty($region_term_ids)) {
                error_log('Applying region filter: ' . print_r($region_term_ids, true));
                $tax_query[] = array(
                    'taxonomy' => $region_taxonomy,
                    'field' => 'term_id',
                    'terms' => $region_term_ids,
                    'operator' => 'IN',
                );
            }
        }

        // Add tax_query to args if we have filters
        if (count($tax_query) > 1) {
            $args['tax_query'] = $tax_query;
        }

        error_log('Final WP_Query args: ' . print_r($args, true));
        error_log('===== END DEBUG =====');

        $query = new WP_Query($args);

        error_log('Query found posts: ' . $query->found_posts);

        // Console log for frontend debugging
        echo '<script>console.log("WEDYARA DEBUG: Package Slider", ' . wp_json_encode(array(
            'package_type' => $package_type,
            'category_taxonomy' => $category_taxonomy,
            'region_taxonomy' => $region_taxonomy,
            'found_posts' => $query->found_posts,
        )) . ');</script>';

        if ($query->have_posts()) :
            $slider_id = 'wedyara-fullwidth-slider-' . uniqid();
            ?>
            <div class="wedyara-fullwidth-slider-section">
                <div class="wedyara-fullwidth-slider-wrapper">
                    <div class="swiper <?php echo esc_attr($slider_id); ?>">
                        <div class="swiper-wrapper">
                            <?php while ($query->have_posts()) : $query->the_post(); ?>
                                <div class="swiper-slide">
                                    <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit; display: block;">
                                        <!-- Background Image for Consistent Height -->
                                        <div class="wedyara-fullwidth-image" style="background-image: url('<?php
                                            if (has_post_thumbnail()) {
                                                echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large'));
                                            } else {
                                                // SVG placeholder with gradient
                                                echo 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Cdefs%3E%3ClinearGradient id=\'grad\' x1=\'0%25\' y1=\'0%25\' x2=\'100%25\' y2=\'100%25\'%3E%3Cstop offset=\'0%25\' style=\'stop-color:%23667eea;stop-opacity:1\' /%3E%3Cstop offset=\'100%25\' style=\'stop-color:%23764ba2;stop-opacity:1\' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect fill=\'url(%23grad)\' width=\'400\' height=\'300\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' font-family=\'Arial\' font-size=\'72\' fill=\'white\' text-anchor=\'middle\' dy=\'.3em\'%3E' . esc_html(substr(get_the_title(), 0, 1)) . '%3C/text%3E%3C/svg%3E';
                                            }
                                        ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                                        </div>
                                        <h3 class="wedyara-fullwidth-title" style="margin: 0; padding: 10px; font-size: 18px; font-weight: 600;">
                                            <?php the_title(); ?>
                                        </h3>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <!-- Navigation Arrows -->
                        <?php if ($settings['show_arrows'] === 'yes'): ?>
                            <div class="swiper-button-prev wedyara-prev-<?php echo esc_attr($slider_id); ?>"></div>
                            <div class="swiper-button-next wedyara-next-<?php echo esc_attr($slider_id); ?>"></div>
                        <?php endif; ?>

                        <!-- Pagination Dots -->
                        <?php if ($settings['show_dots'] === 'yes'): ?>
                            <div class="swiper-pagination wedyara-pagination-<?php echo esc_attr($slider_id); ?>"></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <script>
            (function() {
                // Ensure Swiper is loaded
                if (typeof Swiper === 'undefined') {
                    // Load Swiper CSS
                    var swiperCSS = document.createElement('link');
                    swiperCSS.rel = 'stylesheet';
                    swiperCSS.href = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css';
                    document.head.appendChild(swiperCSS);

                    // Load Swiper JS
                    var swiperScript = document.createElement('script');
                    swiperScript.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
                    document.head.appendChild(swiperScript);
                }

                // Wait for Swiper to be available
                var swiperInterval = setInterval(function() {
                    if (typeof Swiper !== 'undefined') {
                        clearInterval(swiperInterval);

                        new Swiper('.<?php echo esc_js($slider_id); ?>', {
                            slidesPerView: 1,
                            slidesPerGroup: 1,
                            spaceBetween: 20,
                            loop: false,
                            watchOverflow: true,
                            observer: true,
                            observeParents: true,
                            <?php if ($settings['autoplay'] === 'yes'): ?>
                            autoplay: {
                                delay: <?php echo intval($settings['autoplay_speed']); ?>,
                                disableOnInteraction: false,
                                pauseOnMouseEnter: true,
                            },
                            <?php endif; ?>
                            <?php if ($settings['show_dots'] === 'yes'): ?>
                            pagination: {
                                el: '.wedyara-pagination-<?php echo esc_js($slider_id); ?>',
                                clickable: true,
                                dynamicBullets: true,
                            },
                            <?php endif; ?>
                            <?php if ($settings['show_arrows'] === 'yes'): ?>
                            navigation: {
                                nextEl: '.wedyara-next-<?php echo esc_js($slider_id); ?>',
                                prevEl: '.wedyara-prev-<?php echo esc_js($slider_id); ?>',
                            },
                            <?php endif; ?>
                            breakpoints: {
                                640: {
                                    slidesPerView: <?php echo intval($settings['slides_to_show_mobile'] ?? 2); ?>,
                                    slidesPerGroup: 1,
                                    spaceBetween: 15,
                                },
                                992: {
                                    slidesPerView: <?php echo intval($settings['slides_to_show_tablet'] ?? 3); ?>,
                                    slidesPerGroup: 1,
                                    spaceBetween: 20,
                                },
                                1200: {
                                    slidesPerView: <?php echo intval($settings['slides_to_show']); ?>,
                                    slidesPerGroup: 1,
                                    spaceBetween: 25,
                                }
                            }
                        });
                    }
                }, 100);
            })();
            </script>

            <style>
            /* Allow Swiper to calculate widths automatically based on slidesPerView */
            .<?php echo esc_attr($slider_id); ?> .swiper-slide {
                height: auto;
            }

            /* Ensure Swiper wrapper and container are full width */
            .<?php echo esc_attr($slider_id); ?> {
                width: 100%;
            }

            .<?php echo esc_attr($slider_id); ?> .swiper-wrapper {
                display: flex;
                align-items: stretch;
            }

            /* Hover Effects for Fullwidth Slider */
            .wedyara-fullwidth-image {
                transition: background-size 0.4s ease;
            }
            .swiper-slide a:hover .wedyara-fullwidth-image {
                background-size: 110%;
            }
            </style>
            <?php
        endif;

        wp_reset_postdata();
    }

    public function get_script_depends() {
        return [];
    }

    public function get_style_depends() {
        return [];
    }
}
