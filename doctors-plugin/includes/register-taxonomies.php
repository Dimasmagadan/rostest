<?php
/**
 * Register Taxonomies for Doctors CPT
 */

function doctors_register_specialization_taxonomy() {
    $labels = array(
        'name'              => _x( 'Специализации', 'taxonomy general name', 'doctors-plugin' ),
        'singular_name'     => _x( 'Специализация', 'taxonomy singular name', 'doctors-plugin' ),
        'search_items'      => __( 'Поиск специализаций', 'doctors-plugin' ),
        'all_items'         => __( 'Все специализации', 'doctors-plugin' ),
        'parent_item'       => __( 'Родительская специализация', 'doctors-plugin' ),
        'parent_item_colon' => __( 'Родительская специализация:', 'doctors-plugin' ),
        'edit_item'         => __( 'Редактировать специализацию', 'doctors-plugin' ),
        'update_item'       => __( 'Обновить специализацию', 'doctors-plugin' ),
        'add_new_item'      => __( 'Добавить специализацию', 'doctors-plugin' ),
        'new_item_name'     => __( 'Новое название специализации', 'doctors-plugin' ),
        'menu_name'         => __( 'Специализации', 'doctors-plugin' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'specialization' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'specialization', array( 'doctors' ), $args );
}

add_action( 'init', 'doctors_register_specialization_taxonomy' );

function doctors_register_city_taxonomy() {
    $labels = array(
        'name'              => _x( 'Города', 'taxonomy general name', 'doctors-plugin' ),
        'singular_name'     => _x( 'Город', 'taxonomy singular name', 'doctors-plugin' ),
        'search_items'      => __( 'Поиск городов', 'doctors-plugin' ),
        'all_items'         => __( 'Все города', 'doctors-plugin' ),
        'parent_item'       => __( 'Родительский город', 'doctors-plugin' ),
        'parent_item_colon' => __( 'Родительский город:', 'doctors-plugin' ),
        'edit_item'         => __( 'Редактировать город', 'doctors-plugin' ),
        'update_item'       => __( 'Обновить город', 'doctors-plugin' ),
        'add_new_item'      => __( 'Добавить город', 'doctors-plugin' ),
        'new_item_name'     => __( 'Новое название города', 'doctors-plugin' ),
        'menu_name'         => __( 'Города', 'doctors-plugin' ),
        'not_found'         => __( 'Города не найдены.', 'doctors-plugin' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'city' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'city', array( 'doctors' ), $args );
}

add_action( 'init', 'doctors_register_city_taxonomy' );
