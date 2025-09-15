<?php

if (!defined('ABSPATH')) {
    exit;
}

class Cards_Module_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'cards_module';
    }

    public function get_style_depends() {
        return ['cards-module-widget'];
    }

    public function get_title() {
        return __('Cards Module', 'ead-rio');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['cards', 'posts', 'grid', 'list'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'ead-rio'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $post_types = get_post_types(['public' => true], 'objects');
        $post_type_options = [];
        foreach ($post_types as $post_type) {
            $post_type_options[$post_type->name] = $post_type->label;
        }

        $this->add_control(
            'post_type',
            [
                'label' => __('Post Type', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $post_type_options,
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __('Category', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_categories_options(),
                'condition' => [
                    'post_type' => 'post',
                ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Number of Posts', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Show Excerpt', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'ead-rio'),
                'label_off' => __('No', 'ead-rio'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => __('Show Date', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'ead-rio'),
                'label_off' => __('No', 'ead-rio'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'ead-rio'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_gap',
            [
                'label' => __('Card Gap', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cards-module-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label' => __('Card Background', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .card-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .card-item',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .card-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .card-item',
            ]
        );

        $this->end_controls_section();
    }

    private function get_categories_options() {
        $categories = get_categories(['hide_empty' => false]);
        $options = [];
        foreach ($categories as $category) {
            $options[$category->term_id] = $category->name;
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $query_args = [
            'post_type' => $settings['post_type'],
            'posts_per_page' => $settings['posts_per_page'],
            'post_status' => 'publish',
        ];

        if (!empty($settings['category']) && $settings['post_type'] === 'post') {
            $query_args['cat'] = implode(',', $settings['category']);
        }

        $posts = new WP_Query($query_args);

        if (!$posts->have_posts()) {
            echo '<p>' . __('No posts found.', 'ead-rio') . '</p>';
            return;
        }

        $columns = $settings['columns'];
        ?>
        <div class="cards-module-wrapper">
            <div class="cards-module-grid cards-columns-<?php echo esc_attr($columns); ?>">
                <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                    <div class="card-item">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="card-content">
                            <h3 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <?php if ('yes' === $settings['show_date']) : ?>
                                <div class="card-date">
                                    <?php echo get_the_date(); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ('yes' === $settings['show_excerpt']) : ?>
                                <div class="card-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php

        wp_reset_postdata();
    }
}