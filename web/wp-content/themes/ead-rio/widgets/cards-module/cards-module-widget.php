<?php

if (!defined('ABSPATH')) {
    exit;
}

class Cards_Module_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'listagem_cursos';
    }

    public function get_style_depends() {
        return ['cards-module-widget'];
    }

    public function get_title() {
        return __('Listagem de Cursos', 'ead-rio');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['cursos', 'courses', 'grid', 'list'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Configurações dos Cursos', 'ead-rio'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Número de Cursos', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Colunas', 'ead-rio'),
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
            'show_short_description',
            [
                'label' => __('Mostrar Descrição Curta', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sim', 'ead-rio'),
                'label_off' => __('Não', 'ead-rio'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_instituicao',
            [
                'label' => __('Mostrar Instituição', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sim', 'ead-rio'),
                'label_off' => __('Não', 'ead-rio'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_course_area',
            [
                'label' => __('Mostrar Área do Curso', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sim', 'ead-rio'),
                'label_off' => __('Não', 'ead-rio'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_course_degree',
            [
                'label' => __('Mostrar Grau do Curso', 'ead-rio'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Sim', 'ead-rio'),
                'label_off' => __('Não', 'ead-rio'),
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
            'post_type' => 'curso',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status' => 'publish',
        ];

        $posts = new WP_Query($query_args);

        if (!$posts->have_posts()) {
            echo '<p>' . __('Nenhum curso encontrado.', 'ead-rio') . '</p>';
            return;
        }

        // Include the course card component
        require_once get_stylesheet_directory() . '/components/molecules/course-card.php';

        $columns = $settings['columns'];
        ?>
        <div class="cards-module-wrapper">
            <div class="cards-module-grid cards-columns-<?php echo esc_attr($columns); ?>">
                <?php while ($posts->have_posts()) : $posts->the_post();
                    render_course_card([
                        'post_id' => get_the_ID(),
                        'show_short_description' => 'yes' === $settings['show_short_description'],
                        'show_instituicao' => 'yes' === $settings['show_instituicao'],
                        'show_course_area' => 'yes' === $settings['show_course_area'],
                        'show_course_degree' => 'yes' === $settings['show_course_degree'],
                    ]);
                endwhile; ?>
            </div>
        </div>
        <?php

        wp_reset_postdata();
    }
}