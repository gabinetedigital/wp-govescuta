<?php

function add_new_contrib_govescuta_columns($columns) {
	$new_columns['cb'] = '<input type="checkbox" />';	
	$new_columns['title'] = __('Title');
	$new_columns['ativa'] = __('Ativa');
	$new_columns['author'] = __('Author');
	$new_columns['categories'] = __('Categories');
	$new_columns['comments'] = __('Comments');
	$new_columns['date'] = __('Date');
	return $new_columns;
}

function manage_contrib_govescuta_columns($column, $id_post) {
	switch ($column) {
		case 'ativa':
			$ativa = get_post_meta( $id_post , 'wp_govescuta_ativa' , true );
			switch($ativa)
			{
				case 'n':
					echo 'Inativa';
					break;
				case 's':
					echo 'Ativa';
					break;
				default:
					break;
			}
			break;
	}
}

function manage_contrib_govescuta_sortable_columns( $columns ) {
	$columns['ativa'] = 'ativa';
	return $columns;
}

function govescuta_request( $vars ) {
	if ( isset( $vars['orderby'] ) ) {
		switch ( $vars['orderby'] ) {
			case 'ativa' :
				$vars = array_merge( $vars, array(
				'meta_key' => 'wp_govescuta_ativa',
				'orderby' => 'meta_value'
				) );
				break;
		}
	}
	return $vars;
}


add_filter('manage_edit-audiencia_govesc_columns', 'add_new_contrib_govescuta_columns');
add_action('manage_audiencia_govesc_posts_custom_column', 'manage_contrib_govescuta_columns', 10, 2);

add_filter( 'manage_edit-audiencia_govesc_sortable_columns', 'manage_contrib_govescuta_sortable_columns' );
add_filter( 'request', 'govescuta_request' );

add_filter( 'parse_query', 'wp_govescuta_restrict_manage_posts_cutom_field_filter' );

function wp_govescuta_restrict_manage_posts_cutom_field_filter( $query ){
	global $pagenow;
	$type = 'audiencia_govesc';
	if (isset($_GET['post_type'])) {
		$type = $_GET['post_type'];
	}
	if ( 'audiencia_govesc' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['wp_govescuta_ativa']) && $_GET['wp_govescuta_ativa'] != '') {
		$query->query_vars['meta_key'] = 'wp_govescuta_ativa';
		$query->query_vars['meta_value'] = $_GET['wp_govescuta_ativa'];
	}
}



?>