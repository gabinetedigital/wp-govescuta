<?php
global $meta_boxes_govescuta;

$prefix = 'wp_govescuta_';

$meta_boxes_govescuta = array();

$meta_boxes_govescuta[] = array(
		'id' => $prefix.'info_geral',
		'title' => 'Informações Gerais',
		'pages' => array('audiencia_govesc'),
		'context'=> 'normal',
		'priority'=> 'high',
		'fields' => array(
				array(
						'name'		=> 'Audiência Ativa',
						'id'		=> $prefix . 'ativa',
						'type'		=> 'radio',
						'options'	=> array(
								's'			=> 'Sim',
								'n'			=> 'Não'
						),
						'std'		=> 'n'
				),
				array(
						'name' 		=> 'Imagem da audiência',
						'id' 		=> $prefix . 'imagem_govesc',
						'type'	 	=> 'image'
				),
				array(
						'name' 		=> 'Mais detalhes sobre a audiência',
						'id' 		=> $prefix . 'detalhes_govesc',
						'type'	 	=> 'wysiwyg'
				),
				array(
						'name'		=> 'Quando acontece',
						'id'		=> $prefix . 'data_govesc',
						'desc'		=> 'Data da audiência do Governo Escuta.',
						'type'		=> 'datetime',
						'format'	=> 'hh:mm'
				),
				array(
						'name'		=> 'Embed',
						'id'		=> $prefix . 'video_embed',
						'desc'		=> 'Link do stream do video ao vivo.',
						'type'		=> 'text'
				),
				array(
						'name'		=> 'Audiência visivel',
						'id'		=> $prefix . 'visivel',
						'type'		=> 'radio',
						'options'	=> array(
								's'			=> 'Sim',
								'n'			=> 'Não'
						),
						'std'		=> 'n'
				),
				array(
						'name'		=> 'Vídeo',
						'id'		=> $prefix . 'video',
						'desc'		=> 'Id do vídeo.',
						'type'		=> 'text'
				)
		)
);

$meta_boxes_govescuta[] = array(
		'id' => $prefix.'hash_tag_audiencia',
		'title' => 'Hashtags',
		'pages' => array('audiencia_govesc'),
		'context'=> 'normal',
		'priority'=> 'high',
		'fields' => array(
				array(
						'name'		=> 'Termo',
						'id'		=> $prefix . 'hashtags_audiencia',
						'type'		=> 'text',
						'clone'		=> true
				)
		)
);


function wp_govescuta_register_meta_boxes()
{
	global $meta_boxes_govescuta;

	if ( class_exists( 'RW_Meta_Box' ) )
	{
		foreach ( $meta_boxes_govescuta as $meta_box )
		{
			new RW_Meta_Box( $meta_box );
		}
	}
}

add_action('admin_init', 'wp_govescuta_register_meta_boxes' );

?>
