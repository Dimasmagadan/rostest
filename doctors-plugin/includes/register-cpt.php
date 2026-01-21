<?php
/**
 * Doctors Plugin - Register Custom Post Type
 *
 * @package DoctorsPlugin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register the 'doctors' custom post type.
 *
 * @since 1.0.0
 */
function doctors_register_cpt() {
    $labels = array(
        'name'                  => _x( 'Врачи', 'Post type general name', 'doctors-plugin' ),
        'singular_name'         => _x( 'Врач', 'Post type singular name', 'doctors-plugin' ),
        'menu_name'             => _x( 'Врачи', 'Admin Menu text', 'doctors-plugin' ),
        'name_admin_bar'        => _x( 'Врач', 'Add New on Toolbar', 'doctors-plugin' ),
        'add_new'               => __( 'Добавить нового', 'doctors-plugin' ),
        'add_new_item'          => __( 'Добавить врача', 'doctors-plugin' ),
        'new_item'              => __( 'Новый врач', 'doctors-plugin' ),
        'edit_item'             => __( 'Редактировать врача', 'doctors-plugin' ),
        'view_item'             => __( 'Просмотреть врача', 'doctors-plugin' ),
        'all_items'             => __( 'Все врачи', 'doctors-plugin' ),
        'search_items'          => __( 'Поиск врачей', 'doctors-plugin' ),
        'parent_item_colon'     => __( 'Врачи:', 'doctors-plugin' ),
        'not_found'             => __( 'Врачи не найдены.', 'doctors-plugin' ),
        'not_found_in_trash'    => __( 'Врачи в корзине не найдены.', 'doctors-plugin' ),
        'featured_image'        => _x( 'Фото врача', 'Overrides the "Featured Image" phrase', 'doctors-plugin' ),
        'set_featured_image'    => _x( 'Установить фото', 'Overrides the "Set featured image" phrase', 'doctors-plugin' ),
        'remove_featured_image' => _x( 'Удалить фото', 'Overrides the "Remove featured image" phrase', 'doctors-plugin' ),
        'use_featured_image'    => _x( 'Использовать как фото врача', 'Overrides the "Use as featured image" phrase', 'doctors-plugin' ),
        'archives'              => _x( 'Архив врачей', 'The post type archive label used in nav menus', 'doctors-plugin' ),
        'insert_into_item'      => _x( 'Вставить в карточку врача', 'Overrides the "Insert into post" phrase', 'doctors-plugin' ),
        'uploaded_to_this_item' => _x( 'Загружено для этого врача', 'Overrides the "Uploaded to this post" phrase', 'doctors-plugin' ),
        'filter_items_list'     => _x( 'Фильтровать список врачей', 'Screen reader text for the filter links', 'doctors-plugin' ),
        'items_list_navigation' => _x( 'Навигация по списку врачей', 'Screen reader text for the pagination', 'doctors-plugin' ),
        'items_list'            => _x( 'Список врачей', 'Screen reader text for the items list', 'doctors-plugin' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'doctors' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-businessperson',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'doctors', $args );
}

add_action( 'init', 'doctors_register_cpt' );
