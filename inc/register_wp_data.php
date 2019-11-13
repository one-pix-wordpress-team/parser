<?php

add_action('init', 'bd_custom_init');
function bd_custom_init()
{
    /*
    register_taxonomy('complementary-services-type', array('additional-services'), array(
        'labels' => array(
            'name' => 'Вид услуг',
            'singular_name' => 'Вид услуги',
            'search_items' => 'Найти',
            'all_items' => 'Все',
            'view_item ' => 'Посмотреть',
            'edit_item' => 'Редактировать',
            'update_item' => 'Обновить',
            'add_new_item' => 'Добавить новый',
            'new_item_name' => 'Добавить',
            'menu_name' => 'Вид услуг',
        ),
        'description' => '', // описание таксономии
        'public' => true,
        'show_in_rest' => null, // добавить в REST API
        'rest_base' => null, // $taxonomy
        'hierarchical' => false,
        //'update_count_callback' => '_update_post_term_count',
        'rewrite' => true,
        //'query_var'             => $taxonomy, // название параметра запроса
        'capabilities' => array(),
        'meta_box_cb' => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
        'show_admin_column' => false, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
        '_builtin' => false,
        'show_in_quick_edit' => null, // по умолчанию значение show_ui
    ));
    */

    register_post_type('bd_record', array(
        'labels' => array(
            'name' => 'record',
            'singular_name' => 'Запись',
            'add_new' => 'Добавить',
            'add_new_item' => 'Добавить',
            'edit_item' => 'Редактировать',
            'new_item' => 'Новая',
            'view_item' => 'Посмотреть',
            'search_items' => 'Найти Запись',
            'not_found' => 'Не найдено',
            'not_found_in_trash' => 'Корзина пуста',
            'menu_name' => 'Записи'
        ),
        'public' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title'),
        'taxonomies' => ['complementary-services'],
        'menu_icon' => 'dashicons-feedback',
    ));
}

add_action('init', 'bd_custom_fields');
function bd_custom_fields() {
    add_post_type_support( 'bd_record', 'custom-fields'); // в качестве первого параметра укажите название типа поста
}
