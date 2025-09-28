<?php

if (!defined('ABSPATH')) {
    exit;
}

return [
    'name' => 'rio_cards_module',
    'title' => __('Listagem de Cursos', 'ead-rio'),
    'description' => __('Display courses in a beautiful card layout with customizable options', 'ead-rio'),
    'icon' => 'eicon-posts-grid',
    'category' => 'ead-rio-widgets',
    'keywords' => ['cursos', 'courses', 'grid', 'list', 'ead rio'],
    'version' => '1.0.0',
    'dependencies' => [
        'elementor' => '3.0.0',
    ],
    'style_dependencies' => ['rio-cards-module-widget'],
    'template' => 'rio-cards-module.template.php',
    'features' => [
        'responsive' => true,
        'customizable_layout' => true,
        'course_filtering' => true,
        'material_design' => true,
    ],

    'controls' => [
        'content_section' => [
            'label' => __('Configurações dos Cursos', 'ead-rio'),
            'tab' => 'content',
            'controls' => [
                'posts_per_page' => [
                    'type' => 'number',
                    'label' => __('Número de Cursos', 'ead-rio'),
                    'default' => 6,
                    'min' => 1,
                    'max' => 20,
                ],
                'columns' => [
                    'type' => 'select',
                    'label' => __('Colunas', 'ead-rio'),
                    'default' => '3',
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                    ],
                ],
                'show_short_description' => [
                    'type' => 'switcher',
                    'label' => __('Mostrar Descrição Curta', 'ead-rio'),
                    'label_on' => __('Sim', 'ead-rio'),
                    'label_off' => __('Não', 'ead-rio'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ],
                'show_instituicao' => [
                    'type' => 'switcher',
                    'label' => __('Mostrar Instituição', 'ead-rio'),
                    'label_on' => __('Sim', 'ead-rio'),
                    'label_off' => __('Não', 'ead-rio'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ],
                'show_course_area' => [
                    'type' => 'switcher',
                    'label' => __('Mostrar Área do Curso', 'ead-rio'),
                    'label_on' => __('Sim', 'ead-rio'),
                    'label_off' => __('Não', 'ead-rio'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ],
                'show_course_degree' => [
                    'type' => 'switcher',
                    'label' => __('Mostrar Grau do Curso', 'ead-rio'),
                    'label_on' => __('Sim', 'ead-rio'),
                    'label_off' => __('Não', 'ead-rio'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ],
            ],
        ],

        'style_section' => [
            'label' => __('Style', 'ead-rio'),
            'tab' => 'style',
            'controls' => [
                'card_gap' => [
                    'type' => 'slider',
                    'label' => __('Card Gap', 'ead-rio'),
                    'responsive' => true,
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
                        '{{WRAPPER}} .rio-cards-module__grid' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ],
                'card_background' => [
                    'type' => 'color',
                    'label' => __('Card Background', 'ead-rio'),
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .course-card' => 'background-color: {{VALUE}};',
                    ],
                ],
                'card_border' => [
                    'type' => 'border',
                    'name' => 'card_border',
                    'selector' => '{{WRAPPER}} .course-card',
                ],
                'card_border_radius' => [
                    'type' => 'dimensions',
                    'label' => __('Border Radius', 'ead-rio'),
                    'responsive' => true,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .course-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ],
                'card_shadow' => [
                    'type' => 'box_shadow',
                    'name' => 'card_shadow',
                    'selector' => '{{WRAPPER}} .course-card',
                ],
            ],
        ],
    ],
];