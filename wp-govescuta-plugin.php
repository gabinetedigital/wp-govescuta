<?php
/*
 Plugin Name: WP Governo Escuta
Plugin URI: http://www.procergs.com.br/
Description: Plugin Wordpress Governo Escuta, desenvolvido pela PROCERGS. Controle de audiencias do Governo Escuta.
Version: 1.0.2
Author: Cristiane | Felipe | Leo
Author URI: http://www.procergs.com.br
*/

/*  Copyright 2012

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('WPGOVE_TYPE_POST','audiencia_govesc');
define('WPGOVE_TYPE_POST_campo1','wp_govescuta_ativa');
define('WPGOVE_TYPE_POST_campo2','wp_govescuta_detalhes_govesc');
define('WPGOVE_TYPE_POST_campo3','wp_govescuta_data_govesc');
define('WPGOVE_TYPE_POST_campo4','wp_govescuta_video_embed');
define('WPGOVE_TYPE_POST_campo5','wp_govescuta_visivel');
define('WPGOVE_TYPE_POST_campo6','wp_govescuta_hashtags_audiencia');
define('WPGOVE_TYPE_POST_campo7','wp_govescuta_video');

define('WPGOVE_RESULTS_PER_PAGE', 10);

class WPGovEsc{

	public function ativar(){
		add_option('wp_govesc', '');
	}
	public function desativar(){
		delete_option('wp_govesc');
	}
}

$pathPlugin = substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).DIRECTORY_SEPARATOR.basename(__FILE__);

// Função ativar
register_activation_hook( $pathPlugin, array('WPGovEsc','ativar'));

// Função desativar
register_deactivation_hook( $pathPlugin, array('WPGovEsc','desativar'));

include_once('wp-govescuta-custom_css.php');
include_once('wp-govescuta-custom_edit.php');
include_once('wp-govescuta-custom_post.php');
include_once('wp-govescuta-custom_taxonomy.php');
include_once('wp-govescuta-custom_metabox.php');
include_once('wp-govescuta-xmlrpc.php');
?>