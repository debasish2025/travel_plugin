<?php
/**
 * Elementor Destination Grid Widget
 * Displays packages in a 3x2 grid layout
 */

if (!defined('ABSPATH')) exit;

class Elementor_Destination_Grid_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'destination_grid';
    }
    
    public function get_title() {
        return 'Destination Grid';
    }
    
    public function get_icon() {
        return 'eicon-gallery-grid';
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
            'title',
            [
                'label' => 'Section Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Top Destination wedding location Abroad.',
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
                'default' => 6,
            ]
        );
        
        $this->add_control(
            'columns',
            [
                'label' => 'Columns',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'default' => '3',
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
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
            ]
        );
        
        $this->add_control(
            'subtitle_color',
            [
                'label' => 'Subtitle Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );
        
        $this->add_control(
            'card_bg_color',
            [
                'label' => 'Card Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
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
            ?>
            <div class="stp-destination-grid-section">
                <?php if ($settings['title']) : ?>
                    <h2 class="stp-section-title" style="color: <?php echo esc_attr($settings['title_color']); ?>">
                        <?php echo esc_html($settings['title']); ?>
                    </h2>
                <?php endif; ?>
                
                <div class="stp-destination-grid columns-<?php echo esc_attr($settings['columns']); ?>">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <?php
                        $subtitle = get_post_meta(get_the_ID(), '_subtitle', true);
                        ?>
                        <div class="stp-destination-card" style="background-color: <?php echo esc_attr($settings['card_bg_color']); ?>">
                            <div class="stp-card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large'); ?>
                                <?php else : ?>
                                    <img src="https://via.placeholder.com/400x300?text=<?php echo urlencode(get_the_title()); ?>" alt="<?php the_title(); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="stp-card-content">
                                <h3 class="stp-card-title"><?php the_title(); ?></h3>
                                <?php if ($subtitle) : ?>
                                    <p class="stp-card-subtitle" style="color: <?php echo esc_attr($settings['subtitle_color']); ?>">
                                        <?php echo esc_html($subtitle); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php
        endif;
        
        wp_reset_postdata();
    }
}
