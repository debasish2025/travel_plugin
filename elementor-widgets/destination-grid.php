<?php
/**
 * Elementor Destination Grid Widget
 * Displays packages in a responsive grid layout with advanced customization
 */

if (!defined('ABSPATH')) exit;

class Elementor_Destination_Grid_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wedyara_destination_grid';
    }

    public function get_title() {
        return 'Wedyara - Destination Grid';
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {

        // ===================================
        // CONTENT TAB - General Settings
        // ===================================
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'General Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label' => 'Section Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Top Destination wedding location Abroad.',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'show_section_title',
            [
                'label' => 'Show Section Title',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

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

        $this->add_control(
            'posts_per_page',
            [
                'label' => 'Number of Packages',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
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
        // CONTENT TAB - Layout Settings
        // ===================================
        $this->start_controls_section(
            'layout_section',
            [
                'label' => 'Layout Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => 'Columns',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => 'Column Gap',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-container' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => 'Row Gap',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-container' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===================================
        // CONTENT TAB - Content Display
        // ===================================
        $this->start_controls_section(
            'content_display_section',
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

        // ===================================
        // STYLE TAB - Card Style
        // ===================================
        $this->start_controls_section(
            'card_style_section',
            [
                'label' => 'Card Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => 'Background Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .wedyara-grid-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
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
                    '{{WRAPPER}} .wedyara-grid-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .wedyara-grid-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 20,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wedyara-grid-image' => 'height: {{SIZE}}{{UNIT}};',
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
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===================================
        // STYLE TAB - Section Title
        // ===================================
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => 'Section Title',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_section_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'section_title_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'section_title_typography',
                'selector' => '{{WRAPPER}} .wedyara-section-title',
            ]
        );

        $this->add_responsive_control(
            'section_title_align',
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-section-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'section_title_spacing',
            [
                'label' => 'Bottom Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-section-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===================================
        // STYLE TAB - Card Title
        // ===================================
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => 'Card Title',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .wedyara-grid-title',
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => 'Bottom Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===================================
        // STYLE TAB - Subtitle
        // ===================================
        $this->start_controls_section(
            'subtitle_style_section',
            [
                'label' => 'Subtitle',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_subtitle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .wedyara-grid-subtitle',
            ]
        );

        $this->add_responsive_control(
            'subtitle_align',
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-subtitle' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label' => 'Bottom Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===================================
        // STYLE TAB - Duration
        // ===================================
        $this->start_controls_section(
            'duration_style_section',
            [
                'label' => 'Duration',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_duration' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'duration_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-duration' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'duration_typography',
                'selector' => '{{WRAPPER}} .wedyara-grid-duration',
            ]
        );

        $this->add_responsive_control(
            'duration_align',
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-duration' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'duration_spacing',
            [
                'label' => 'Bottom Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-duration' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===================================
        // STYLE TAB - Price
        // ===================================
        $this->start_controls_section(
            'price_style_section',
            [
                'label' => 'Price',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_price' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .wedyara-grid-price',
            ]
        );

        $this->add_responsive_control(
            'price_align',
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wedyara-grid-price' => 'text-align: {{VALUE}};',
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

        $options = array();

        if (!is_wp_error($categories) && !empty($categories)) {
            foreach ($categories as $cat) {
                $options[$cat->term_id] = $cat->name;
            }
        }

        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Build query arguments
        $args = array(
            'post_type' => 'travel_package',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'post_status' => 'publish',
        );

        // Category filter - SIMPLE AND WORKING
        $selected_categories = isset($settings['categories']) ? $settings['categories'] : array();

        // Remove empty values
        if (is_array($selected_categories)) {
            $selected_categories = array_filter($selected_categories);
        }

        // Apply filter if categories are selected
        if (!empty($selected_categories)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'package_category',
                    'field' => 'term_id',
                    'terms' => $selected_categories,
                    'operator' => 'IN',
                )
            );
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            $grid_id = 'wedyara-grid-' . uniqid();
            ?>
            <div class="wedyara-grid-wrapper">
                <?php if ($settings['show_section_title'] === 'yes' && $settings['section_title']) : ?>
                    <h2 class="wedyara-section-title">
                        <?php echo esc_html($settings['section_title']); ?>
                    </h2>
                <?php endif; ?>

                <div class="wedyara-grid-container <?php echo esc_attr($grid_id); ?>" style="display: grid; grid-template-columns: repeat(<?php echo esc_attr($settings['columns']); ?>, 1fr);">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <?php
                        $subtitle = get_post_meta(get_the_ID(), '_subtitle', true);
                        $price = get_post_meta(get_the_ID(), '_price', true);
                        $days = get_post_meta(get_the_ID(), '_days', true);
                        $nights = get_post_meta(get_the_ID(), '_nights', true);
                        ?>
                        <a href="<?php the_permalink(); ?>" class="wedyara-grid-card" style="text-decoration: none; color: inherit; display: block; overflow: hidden;">

                            <!-- Background Image for Consistent Height -->
                            <div class="wedyara-grid-image" style="background-image: url('<?php
                                if (has_post_thumbnail()) {
                                    echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large'));
                                } else {
                                    // SVG placeholder with gradient
                                    echo 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Cdefs%3E%3ClinearGradient id=\'grad\' x1=\'0%25\' y1=\'0%25\' x2=\'100%25\' y2=\'100%25\'%3E%3Cstop offset=\'0%25\' style=\'stop-color:%23667eea;stop-opacity:1\' /%3E%3Cstop offset=\'100%25\' style=\'stop-color:%23764ba2;stop-opacity:1\' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect fill=\'url(%23grad)\' width=\'400\' height=\'300\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' font-family=\'Arial\' font-size=\'72\' fill=\'white\' text-anchor=\'middle\' dy=\'.3em\'%3E' . esc_html(substr(get_the_title(), 0, 1)) . '%3C/text%3E%3C/svg%3E';
                                }
                            ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                            </div>

                            <div class="wedyara-grid-content" style="padding: 20px;">
                                <h3 class="wedyara-grid-title" style="margin: 0 0 10px 0; font-size: 20px; font-weight: 600;">
                                    <?php the_title(); ?>
                                </h3>

                                <?php if ($settings['show_subtitle'] === 'yes' && $subtitle) : ?>
                                    <p class="wedyara-grid-subtitle" style="margin: 0 0 8px 0; font-size: 14px;">
                                        <?php echo esc_html($subtitle); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($settings['show_duration'] === 'yes' && $days && $nights): ?>
                                    <p class="wedyara-grid-duration" style="margin: 0 0 10px 0; font-size: 14px;">
                                        <?php echo esc_html($days . ' Days / ' . $nights . ' Nights'); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($settings['show_price'] === 'yes' && $price): ?>
                                    <p class="wedyara-grid-price" style="margin: 0; font-size: 20px; font-weight: 600;">
                                        From $<?php echo esc_html($price); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>

            <style>
            /* Responsive Grid for <?php echo $grid_id; ?> */
            @media (max-width: 1024px) {
                .<?php echo esc_attr($grid_id); ?> {
                    grid-template-columns: repeat(<?php echo esc_attr($settings['columns_tablet'] ?? '2'); ?>, 1fr) !important;
                }
            }
            @media (max-width: 767px) {
                .<?php echo esc_attr($grid_id); ?> {
                    grid-template-columns: repeat(<?php echo esc_attr($settings['columns_mobile'] ?? '1'); ?>, 1fr) !important;
                }
            }

            /* Hover Effects */
            .wedyara-grid-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .wedyara-grid-card:hover {
                transform: translateY(-5px);
            }
            .wedyara-grid-image {
                transition: background-size 0.4s ease;
            }
            .wedyara-grid-card:hover .wedyara-grid-image {
                background-size: 110%;
            }
            </style>
            <?php
        endif;

        wp_reset_postdata();
    }
}
