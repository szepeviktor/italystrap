<?php
// Register Custom Post Type
function custom_post_type() {
	$labels = array(
		'name'                => _x( 'Prodotti', 'Post Type General Name', 'ItalyStrap' ),
		'singular_name'       => _x( 'Prodotti', 'Post Type Singular Name', 'ItalyStrap' ),
		'menu_name'           => __( 'Prodotti', 'ItalyStrap' ),
		'parent_item_colon'   => __( 'Parent prodotti:', 'ItalyStrap' ),
		'all_items'           => __( 'Tutti i prodotti', 'ItalyStrap' ),
		'view_item'           => __( 'Visualizza i prodotti', 'ItalyStrap' ),
		'add_new_item'        => __( 'Aggiungi un nuovo prodotto', 'ItalyStrap' ),
		'add_new'             => __( 'Aggiungi un nuovo prodotto', 'ItalyStrap' ),
		'edit_item'           => __( 'Modifica prodotti', 'ItalyStrap' ),
		'update_item'         => __( 'Aggiorna prodotti', 'ItalyStrap' ),
		'search_items'        => __( 'Ricerca prodotti', 'ItalyStrap' ),
		'not_found'           => __( 'Nessun prodotto trovato', 'ItalyStrap' ),
		'not_found_in_trash'  => __( 'Nessun prodotto nel cestino', 'ItalyStrap' ),
	);

	$args = array(
		'label'               => __( 'prodotti', 'ItalyStrap' ),
		'description'         => __( 'Information pages of prodotti - <strong>insert here</strong> any description of custom post page', 'ItalyStrap' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', ),
		'taxonomies'          => array( 'category', 'post_tag', 'Slide' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		//'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => true,
		'capability_type'     => 'page',
	);

	register_post_type( 'prodotti', $args );
}

// Hook into the 'init' action
add_action( 'init', 'custom_post_type', 0 );
?>