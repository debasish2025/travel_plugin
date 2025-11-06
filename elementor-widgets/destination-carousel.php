<?php
/**
 * Elementor Wedyara Destination Carousel Widget
 * Displays packages in a horizontal scrolling carousel using Swiper.js
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

        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => 'Filter by Category',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_package_categories(),
                'default' => 'all',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => 'Number of Packages',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 12,
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
                'condition' => ['autoplay' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
            ]
        );

        $this->add_control(
            'card_subtitle_color',
            [
                'label' => 'Subtitle Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => ['size' => 15],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_package_categories() {
        $categories = get_terms(array(
            'taxonomy' => 'package_category',
            'hide_empty' => false,
        ));

        $options = array('all' => 'All Categories');

        if (!is_wp_error($categories) && !empty($categories)) {
            foreach ($categories as $cat) {
                $options[$cat->slug] = $cat->name;
            }
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
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish',
        );

        if ($settings['category'] != 'all') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'package_category',
                    'field' => 'slug',
                    'terms' => $settings['category'],
                )
            );
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            $carousel_id = 'wedyara-carousel-' . uniqid();
            $border_radius = isset($settings['card_border_radius']['size']) ? $settings['card_border_radius']['size'] . 'px' : '15px';
            ?>
            <div class="wedyara-carousel-wrapper">
                <!-- Navigation Arrows -->
                <div class="swiper-button-prev wedyara-prev-<?php echo esc_attr($carousel_id); ?>"></div>
                <div class="swiper-button-next wedyara-next-<?php echo esc_attr($carousel_id); ?>"></div>

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
                                <a href="<?php the_permalink(); ?>" class="wedyara-carousel-card" style="border-radius: <?php echo esc_attr($border_radius); ?>; text-decoration: none; display: block; color: inherit;">
                                    <div class="wedyara-carousel-image">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium_large'); ?>
                                        <?php else : ?>
                                            <div style="width: 100%; height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: bold;">
                                                <?php echo esc_html(substr(get_the_title(), 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="wedyara-carousel-content">
                                        <h3 class="wedyara-carousel-title" style="color: <?php echo esc_attr($settings['card_title_color']); ?>">
                                            <?php the_title(); ?>
                                        </h3>
                                        <?php if ($subtitle) : ?>
                                            <p class="wedyara-carousel-subtitle" style="color: <?php echo esc_attr($settings['card_subtitle_color']); ?>">
                                                <?php echo esc_html($subtitle); ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($days && $nights): ?>
                                            <p class="wedyara-carousel-duration" style="font-size: 13px; color: #888; margin: 8px 0 0 0;">
                                                <?php echo esc_html($days . ' Days / ' . $nights . ' Nights'); ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($price): ?>
                                            <p class="wedyara-carousel-price" style="font-size: 18px; font-weight: 600; color: #667eea; margin: 10px 0 0 0;">
                                                From $<?php echo esc_html($price); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Pagination -->
                    <div class="swiper-pagination wedyara-pagination-<?php echo esc_attr($carousel_id); ?>"></div>
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
                            pagination: {
                                el: '.wedyara-pagination-<?php echo esc_js($carousel_id); ?>',
                                clickable: true,
                                dynamicBullets: true,
                            },
                            navigation: {
                                nextEl: '.wedyara-next-<?php echo esc_js($carousel_id); ?>',
                                prevEl: '.wedyara-prev-<?php echo esc_js($carousel_id); ?>',
                            },
                            breakpoints: {
                                640: {
                                    slidesPerView: 2,
                                    spaceBetween: 20,
                                },
                                992: {
                                    slidesPerView: 3,
                                    spaceBetween: 25,
                                },
                                1200: {
                                    slidesPerView: 4,
                                    spaceBetween: 30,
                                }
                            }
                        });
                    }
                }, 100);
            })();
            </script>
            <?php
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
