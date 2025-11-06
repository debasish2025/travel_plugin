<?php
/**
 * Elementor Destination Carousel Widget
 * Displays packages in a horizontal scrolling carousel
 */

if (!defined('ABSPATH')) exit;

class Elementor_Destination_Carousel_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'destination_carousel';
    }
    
    public function get_title() {
        return 'Destination Carousel';
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
                'default' => 10,
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
        foreach ($categories as $cat) {
            $options[$cat->slug] = $cat->name;
        }
        
        return $options;
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $args = array(
            'post_type' => 'travel_package',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'date',
            'order' => 'DESC',
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
            $carousel_id = 'carousel-' . uniqid();
            ?>
            <div class="stp-carousel-wrapper">
                <div class="stp-carousel <?php echo esc_attr($carousel_id); ?>" 
                     data-autoplay="<?php echo esc_attr($settings['autoplay']); ?>"
                     data-autoplay-speed="<?php echo esc_attr($settings['autoplay_speed']); ?>">
                    
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <?php
                        $subtitle = get_post_meta(get_the_ID(), '_subtitle', true);
                        $days = get_post_meta(get_the_ID(), '_days', true);
                        $nights = get_post_meta(get_the_ID(), '_nights', true);
                        $price = get_post_meta(get_the_ID(), '_price', true);
                        $border_radius = $settings['card_border_radius']['size'] . 'px';
                        ?>
                        <div class="stp-carousel-slide">
                            <a href="<?php the_permalink(); ?>" class="stp-carousel-card" style="border-radius: <?php echo esc_attr($border_radius); ?>; text-decoration: none; display: block; color: inherit;">
                                <div class="stp-carousel-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium_large'); ?>
                                    <?php else : ?>
                                        <img src="https://via.placeholder.com/400x300?text=<?php echo urlencode(get_the_title()); ?>" alt="<?php the_title(); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="stp-carousel-content">
                                    <h3 class="stp-carousel-title" style="color: <?php echo esc_attr($settings['card_title_color']); ?>">
                                        <?php the_title(); ?>
                                    </h3>
                                    <?php if ($subtitle) : ?>
                                        <p class="stp-carousel-subtitle" style="color: <?php echo esc_attr($settings['card_subtitle_color']); ?>">
                                            <?php echo esc_html($subtitle); ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($days && $nights): ?>
                                        <p class="stp-carousel-duration" style="font-size: 13px; color: #888; margin: 8px 0 0 0;">
                                            <?php echo esc_html($days . ' Days / ' . $nights . ' Nights'); ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($price): ?>
                                        <p class="stp-carousel-price" style="font-size: 18px; font-weight: 600; color: #667eea; margin: 10px 0 0 0;">
                                            From $<?php echo esc_html($price); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <div class="stp-carousel-dots"></div>
            </div>
            
            <script>
            (function($) {
                function initCarousel_<?php echo str_replace('-', '_', $carousel_id); ?>() {
                    var carousel = $('.<?php echo $carousel_id; ?>');

                    // Check if carousel exists and Slick is available
                    if (carousel.length === 0) {
                        return;
                    }

                    if (typeof $.fn.slick === 'undefined') {
                        // Retry after 100ms if Slick not loaded yet
                        setTimeout(initCarousel_<?php echo str_replace('-', '_', $carousel_id); ?>, 100);
                        return;
                    }

                    // Destroy existing instance if any
                    if (carousel.hasClass('slick-initialized')) {
                        carousel.slick('unslick');
                    }

                    var autoplay = carousel.data('autoplay') === 'yes';
                    var autoplaySpeed = carousel.data('autoplay-speed') || 3000;

                    // Initialize Slick carousel
                    carousel.slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        autoplay: autoplay,
                        autoplaySpeed: autoplaySpeed,
                        dots: true,
                        arrows: true,
                        infinite: true,
                        speed: 500,
                        cssEase: 'ease-in-out',
                        appendDots: carousel.parent().find('.stp-carousel-dots'),
                        prevArrow: '<button type="button" class="slick-prev">←</button>',
                        nextArrow: '<button type="button" class="slick-next">→</button>',
                        responsive: [
                            {
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 992,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });
                }

                // Initialize on DOM ready
                $(document).ready(function() {
                    initCarousel_<?php echo str_replace('-', '_', $carousel_id); ?>();
                });

                // Re-initialize for Elementor editor
                if (typeof elementorFrontend !== 'undefined') {
                    elementorFrontend.hooks.addAction('frontend/element_ready/widget', function() {
                        setTimeout(initCarousel_<?php echo str_replace('-', '_', $carousel_id); ?>, 200);
                    });
                }
            })(jQuery);
            </script>
            <?php
        endif;
        
        wp_reset_postdata();
    }
    
    public function get_script_depends() {
        return ['jquery', 'slick'];
    }
    
    public function get_style_depends() {
        return ['slick'];
    }
}

// Enqueue Slick Carousel
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
    wp_enqueue_style('slick-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');
    wp_enqueue_script('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);
});
