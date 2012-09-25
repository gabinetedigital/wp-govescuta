<?php

function wp_govescuta_css() {
	$siteurl = get_option('siteurl');
	$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/wp-govescuta.css';
	echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}

add_action('admin_head', 'wp_govescuta_css');