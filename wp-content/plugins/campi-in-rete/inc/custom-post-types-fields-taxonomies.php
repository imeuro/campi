<?php
// Replace Posts label in Opere in Admin Panel
// and remove default submenu items
// and change std dashicon
function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Opere';
    $submenu['edit.php'][5][0] = 'Opere';
    unset($submenu['edit.php'][10]);
}
add_action( 'admin_menu', 'change_post_menu_label' );

function change_post_object_label() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Opere';
    $labels->menu_icon = 'dashicons-art';
    $labels->singular_name = 'Opera';
    $labels->add_new = 'Aggiungi Opera';
    $labels->add_new_item = 'Aggiungi Opera';
    $labels->edit_item = 'Modifica Opera';
    $labels->new_item = 'Opera';
    $labels->view_item = 'Visualizza Opera';
    $labels->search_items = 'Cerca Opere';
    $labels->not_found = 'Nessuna Opera trovata';
    $labels->not_found_in_trash = 'Nessuna Opera trovata in Trash';
    $labels->name_admin_bar = 'Aggiungi Opera';
}
add_action( 'init', 'change_post_object_label' );

function change_post_menu_icon() {
    ?>
    <style>
        .dashicons-admin-post:before {
            font-family: "dashicons";
            content: "\f309" !important;
        }
    </style>
    <?php
}
add_action( 'admin_head', 'change_post_menu_icon' );

// remove std taxonomies
function wpsnipp_remove_default_taxonomies(){
    global $pagenow;
    register_taxonomy( 'post_tag', array() );
    register_taxonomy( 'category', array() );
    $tax = array('post_tag','category');
}
add_action('init', 'wpsnipp_remove_default_taxonomies');

// creates new taxonomy "Autori"
function campi_register_tax_autori() {
    $labels = [
        "name" => esc_html__( "Autori", "twentytwentyfour" ),
        "singular_name" => esc_html__( "Autore", "twentytwentyfour" ),
        "menu_name" => esc_html__( "Autori", "twentytwentyfour" ),
        "all_items" => esc_html__( "Tutti gli Autori", "twentytwentyfour" ),
        "edit_item" => esc_html__( "Modifica Autore", "twentytwentyfour" ),
        "view_item" => esc_html__( "Visualizza Autore", "twentytwentyfour" ),
        "update_item" => esc_html__( "Aggiorna Autore", "twentytwentyfour" ),
        "add_new_item" => esc_html__( "Aggiuingi Autore", "twentytwentyfour" ),
        "new_item_name" => esc_html__( "Nuovo Autore", "twentytwentyfour" ),
        //"parent_item" => esc_html__( "Autore padre", "twentytwentyfour" ),
        "parent_item_colon" => esc_html__( "Autore padre:", "twentytwentyfour" ),
        "search_items" => esc_html__( "Cerca in Autori", "twentytwentyfour" ),
        "popular_items" => esc_html__( "Autori più utilizzati", "twentytwentyfour" ),
    ];

    
    $args = [
        "label" => esc_html__( "Autori", "twentytwentyfour" ),
        "labels" => $labels,
        "public" => true,
        "publicly_queryable" => true,
        "hierarchical" => false,
        "show_ui" => true,
        "show_in_menu" => false,
        "show_in_nav_menus" => false,
        "query_var" => true,
        "rewrite" => [ 'slug' => 'autori', 'with_front' => true, ],
        "show_admin_column" => false,
        "show_in_rest" => true,
        "show_tagcloud" => false,
        "rest_base" => "autori",
        "rest_controller_class" => "WP_REST_Terms_Controller",
        "rest_namespace" => "wp/v2",
        "show_in_quick_edit" => false,
        "sort" => true,
        "show_in_graphql" => false,
    ];
    register_taxonomy( "autori", [ "post", "attachment" ], $args );
}
add_action( 'init', 'campi_register_tax_autori' );





// creates new taxonomy "Luoghi"
function campi_register_tax_luoghi() {
    $labels = [
        "name" => esc_html__( "Luoghi", "twentytwentyfour" ),
        "singular_name" => esc_html__( "Luogo", "twentytwentyfour" ),
        "menu_name" => esc_html__( "Luoghi", "twentytwentyfour" ),
        "all_items" => esc_html__( "Tutti i Luoghi", "twentytwentyfour" ),
        "edit_item" => esc_html__( "Modifica Luogo", "twentytwentyfour" ),
        "view_item" => esc_html__( "Visualizza Luogo", "twentytwentyfour" ),
        "update_item" => esc_html__( "Aggiorna Luogo", "twentytwentyfour" ),
        "add_new_item" => esc_html__( "Aggiuingi Luogo", "twentytwentyfour" ),
        "new_item_name" => esc_html__( "Nuovo Luogo", "twentytwentyfour" ),
        "parent_item" => esc_html__( "Luogo padre", "twentytwentyfour" ),
        "parent_item_colon" => esc_html__( "Luogo padre:", "twentytwentyfour" ),
        "search_items" => esc_html__( "Cerca in Luoghi", "twentytwentyfour" ),
        "popular_items" => esc_html__( "Luoghi più utilizzati", "twentytwentyfour" ),
    ];

    
    $args = [
        "label" => esc_html__( "Luoghi", "twentytwentyfour" ),
        "labels" => $labels,
        "public" => true,
        "publicly_queryable" => true,
        "hierarchical" => true,
        "show_ui" => true,
        "show_in_menu" => false,
        "show_in_nav_menus" => false,
        "query_var" => true,
        "rewrite" => [ 'slug' => 'luoghi', 'with_front' => true, ],
        "show_admin_column" => false,
        "show_in_rest" => true,
        "show_tagcloud" => false,
        "rest_base" => "luoghi",
        "rest_controller_class" => "WP_REST_Terms_Controller",
        "rest_namespace" => "wp/v2",
        "show_in_quick_edit" => false,
        "sort" => true,
        "show_in_graphql" => false,
    ];
    register_taxonomy( "luoghi", [ "post", "attachment" ], $args );
}
add_action( 'init', 'campi_register_tax_luoghi' );


// ADMIN MENU VOICES:
function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
        __( 'Luoghi', 'textdomain' ),
        'Luoghi',
        'edit_posts',
        'edit-tags.php?taxonomy=luoghi',
        '',
        'dashicons-location',
        6
    );
    add_menu_page(
        __( 'Autori', 'textdomain' ),
        'Autori',
        'manage_options',
        'edit-tags.php?taxonomy=autori',
        '',
        'dashicons-admin-users',
        7
    );

    // adds a separator in pos 8 (after "Autori")
    global $menu;
    $menu[8] = ['', 'read', '', '', 'wp-menu-separator'];
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

// GMAPS API key [custom feld in BE]
function my_acf_google_map_api( $api ){
    $api['key'] = 'AIzaSyBg6edw-g51IXd8gFxaMH0tkMpC1ZqLGls';
    return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

