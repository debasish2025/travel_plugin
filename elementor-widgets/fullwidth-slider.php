<?php
/**
 * Elementor Full Width Slider Widget
 * Displays packages in a full-width slider with custom background
 */

if (!defined('ABSPATH')) exit;

class Elementor_Fullwidth_Slider_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'fullwidth_slider';
    }
    
    public function get_title() {
        return 'Full Width Slider';
    }
    
    public function get_icon() {
        return 'eicon-slider-album';
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
            'slides_to_show',
            [
                'label' => 'Slides to Show',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
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
            'background_color',
            [
                'label' => 'Background Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#EC407A',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
            ]
        );
        
        $this->add_control(
            'padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 60,
                    'right' => 0,
                    'bottom' => 60,
                    'left' => 0,
                    'unit' => 'px',
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
            $slider_id = 'fullwidth-slider-' . uniqid();
            $padding_style = sprintf(
                'padding: %spx %spx %spx %spx;',
                $settings['padding']['top'],
                $settings['padding']['right'],
                $settings['padding']['bottom'],
                $settings['padding']['left']
            );
            ?>
            <div class="stp-fullwidth-slider-section" 
                 style="background-color: <?php echo esc_attr($settings['background_color']); ?>; <?php echo esc_attr($padding_style); ?>">
                
                <div class="stp-fullwidth-slider <?php echo esc_attr($slider_id); ?>">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="stp-fullwidth-slide">
                            <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit; display: block;">
                                <div class="stp-fullwidth-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('large'); ?>
                                    <?php else : ?>
                                        <img src="https://via.placeholder.com/500x400?text=<?php echo urlencode(get_the_title()); ?>" alt="<?php the_title(); ?>">
                                    <?php endif; ?>
                                </div>
                                <h3 class="stp-fullwidth-title" style="color: <?php echo esc_attr($settings['title_color']); ?>">
                                    <?php the_title(); ?>
                                </h3>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <script>
            jQuery(document).ready(function($){
                $('.<?php echo $slider_id; ?>').slick({
                    slidesToShow: <?php echo intval($settings['slides_to_show']); ?>,
                    slidesToScroll: 1,
                    autoplay: <?php echo $settings['autoplay'] === 'yes' ? 'true' : 'false'; ?>,
                    autoplaySpeed: 3000,
                    dots: false,
                    arrows: true,
                    centerMode: false,
                    infinite: true,
                    responsive: [
                        {
                            breakpoint: 1400,
                            settings: {
                                slidesToShow: 4
                            }
                        },
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            });
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
