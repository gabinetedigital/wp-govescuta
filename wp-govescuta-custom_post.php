<?php

function wp_govesc_audiencia() {
	$labels = array(
			'name' => _x( 'Audiências', 'audiencia' ),
			'singular_name' => _x( 'Audiência', 'audiencia' ),
			'add_new' => _x( 'Adicionar Nova', 'audiencia' ),
			'all_items' => _x('Audiências', 'audiencia'),
			'add_new_item' => _x( 'Adicionar Nova Audiência', 'audiencia' ),
			'edit_item' => _x( 'Editar Audiência', 'audiencia' ),
			'new_item' => _x( 'Nova Audiência', 'audiencia' ),
			'view_item' => _x( 'Visualizar Audiência', 'audiencia' ),
			'search_items' => _x( 'Pesquisar Audiência', 'audiencia' ),
			'not_found' => _x( 'Nenhuma audiência encontrada', 'audiencia' ),
			'not_found_in_trash' => _x( 'Nenhuma audiência encontrada na lixeira', 'audiencia' ),
			'parent_item_colon' => _x( 'Audiência mae:', 'audiencia' ),
			'menu_name' => _x( 'Governo Escuta', 'audiencia'),
	);
	$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'author', 'comments', 'revisions'),
			'taxonomies' => array( 'category'),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 100,
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
	);
	register_post_type( 'audiencia_govesc', $args );
}

add_action( 'init', 'wp_govesc_audiencia' );

?>