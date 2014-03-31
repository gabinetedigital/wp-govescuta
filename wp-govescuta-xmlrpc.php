<?php

function logLeo($io, $msg){
	$fp = fopen("/var/www/wordpress/log/xmlrpc.log","a+");
	$date = gmdate("Y-m-d H:i:s ");
	$iot = ($io == "I") ? " Input: " : " Output: ";
	fwrite($fp, "\n\n".$date.$iot.$msg);
	fclose($fp);
}

function wp_govescuta_get_audiencias($args){

	global $wpdb;

	$page    = $args[3]["page"] ? $args[3]["page"] : '0';    //page
	$sortby  = $args[3]["sortby"] ? $args[3]["sortby"] : 'date'; //order by
	$order   = $args[3]["order"] ? $args[3]["order"] : 'DESC'; // order by
	$postID  = $args[3]["postID"] ? $args[3]["postID"] : NULL; // post_Id
	$ativa   = $args[3]["ativa"] ? $args[3]["ativa"] : 'n'; // audiencia_ativa
	$visivel = $args[3]["visivel"] ? $args[3]["visivel"] : 's'; // audiencia_visivel
	$offset  = $args[3]["offset"] ? $args[3]["offset"] : '0';
	$perpage = $args[3]["perpage"] ? $args[3]["perpage"] : '10000';
	$totalporpage = $args[3]["totalporpage"] ? $args[3]["totalporpage"] : WPGOVE_RESULTS_PER_PAGE; // paginacao
	$listatodas = $args[3]["allaudiencia"] ? $args[3]["allaudiencia"] : '';

	if ($listatodas == "T"){
		$visivel = '';
		$ativa = '';
	} else {
		$visivel = "WHERE x.aud_".WPGOVE_TYPE_POST_campo5." = '".$visivel."' ";
		$ativa   = "AND x.aud_".WPGOVE_TYPE_POST_campo1." = '".$ativa."' ";
	}

	$sortfields = array('date' => 'aud_'.WPGOVE_TYPE_POST_campo3.' ');

	if ($sortby[0] === '-') {
        $order = 'ASC';
        $sortby = substr($sortby, 1, strlen($sortby));
    }

	if ($postID) {
		$filter = "AND ID = ".$postID;
	}

	if (isset($sortfields[$sortby])) {
        $sortfield = $sortfields[$sortby];
    } else {
        $sortfield = 'aud_'.WPGOVE_TYPE_POST_campo3.'';
    }

	$sql = "	SELECT 	x.*,
						(SELECT meta_value FROM wp_postmeta where meta_key='".WPGOVE_TYPE_POST_campo2."' and x.id = post_id ) aud_".WPGOVE_TYPE_POST_campo2."
				FROM 	(
						SELECT
							p.*,
        					GROUP_CONCAT(IF(m.meta_key='".WPGOVE_TYPE_POST_campo1."', m.meta_value, NULL)) aud_".WPGOVE_TYPE_POST_campo1.",
        					GROUP_CONCAT(IF(m.meta_key='".WPGOVE_TYPE_POST_campo3."', m.meta_value, NULL)) aud_".WPGOVE_TYPE_POST_campo3.",
        					GROUP_CONCAT(IF(m.meta_key='".WPGOVE_TYPE_POST_campo4."', m.meta_value, NULL)) aud_".WPGOVE_TYPE_POST_campo4.",
        					GROUP_CONCAT(IF(m.meta_key='".WPGOVE_TYPE_POST_campo5."', m.meta_value, NULL)) aud_".WPGOVE_TYPE_POST_campo5.",
        					GROUP_CONCAT(IF(m.meta_key='".WPGOVE_TYPE_POST_campo7."', m.meta_value, NULL)) aud_".WPGOVE_TYPE_POST_campo7."
						FROM
							wp_posts p
        					left join wp_postmeta m on p.id = m.post_id
						WHERE
							post_type = '".WPGOVE_TYPE_POST."'
						AND post_status = 'publish'
						group
							by p.id
						) x
				$visivel
					$ativa
					$filter
				ORDER
					by 	$sortfield $order";

    error_log("SQLLLLLLLLLLLLLLLLLL");
    error_log($sql);
    $sql = $wpdb->prepare($sql . " LIMIT %d, %d", array($offset, $perpage));

    //$sql = $wpdb->prepare($sql);
    $listing = $wpdb->get_results($sql, ARRAY_A);

        error_log("AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA POST A");
        error_log( print_r($listing, True) );

    $sql = $wpdb->prepare("SELECT COUNT(*) from ($sql) x");
    $count = $wpdb->get_var($sql);

    $ret = array();
    foreach ($listing as $c) {
        error_log("AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA POST");
        error_log( print_r($c, True) );

    	if ($c["aud_".WPGOVE_TYPE_POST_campo7]){
    		$c["video"] = wpgd_videos_get_video_wpgp($c["aud_".WPGOVE_TYPE_POST_campo7]);
        	$c["video_sources"] = wpgd_videos_get_sources_wpgp($c["aud_".WPGOVE_TYPE_POST_campo7]);
		}

		$c["category"] = "";
		foreach((get_the_category($c["ID"])) as $category) {
			$c["category"] = $category->cat_ID;
		}

		$c["hashtag"] = wp_govescuta_get_hashtags($c["ID"]);

		$c["imagem"] = wp_get_attachment_url( get_post_meta($c["ID"], WPGOVE_TYPE_POST_campo8, 'true') );

        $ret[] = $c;
    }

    error_log("AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA POST 2");
    error_log( print_r($ret, True) );

    return array($count, $ret);

}

function wp_govescuta_get_hashtags($args){

	if (is_array($args)) {
		$postID  = $args[3]["postID"];
	} else {
		$postID = $args;
	}

	$ret = get_post_meta($postID, WPGOVE_TYPE_POST_campo6, 'true');

    return $ret;
}

function wp_govescuta_insere_audiencia($args)
{
	$username	= $args[0];
	$password	= $args[1];
	$data 		= $args[2];

	global $wp_xmlrpc_server;

	if ( !$user = $wp_xmlrpc_server->login($username, $password) ) {
		return $wp_xmlrpc_server->error;
	}

	$title 			= $data["title"];
	$custom_fields 	= $data["custom_fields"];

	$new_post = array(
			'post_status' => 'draft',
			'post_title' => $title,
			'post_type' => 'audiencia_govesc',
	);

	$new_post_id = wp_insert_post($new_post);
	foreach($custom_fields as $meta_key => $values)
		foreach ($values as $meta_value)
		add_post_meta($new_post_id, $meta_key, $meta_value);

	return "INSERIDO";
}

add_filter('xmlrpc_methods', 'wp_govescuta_xmlrpc_methods');
function wp_govescuta_xmlrpc_methods($methods)
{
	//declarar array de metodos
	//$methods[<chamada do metodo pelo rpc>] = <metodo que deve ser executado>;
	$methods['wpgove.getAudiencias'] 	= 'wp_govescuta_get_audiencias';
	$methods['wpgove.gethashtags'] 		= 'wp_govescuta_get_hashtags';
	$methods['wpgove.getBuzz'] 			= 'wp_govescuta_get_buzz';
	$methods['wpgove.listAudiencia'] 	= 'wp_govescuta_obtem_audiencia';
	$methods['wpgove.insAudiencia'] 	= 'wp_govescuta_insere_audiencia';
	return $methods;
}

?>
