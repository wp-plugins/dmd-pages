<?php
/**
 * Plugin Name: dmd Pages
 * Plugin URI: https://dimadirekt.de/
 * Author: digitalmarketingditrekt gmbh
 * Author URI: https://dimadirekt.de/
 * Description: This plugin loads a list of all published pages into a menu in the adminbar. You can edit pages faster and don't need to search in the dashboard=>pages.
 * Version: 0.2
 **/


/*
 * Load style for the adminbar menu
 */
function dmd_custom_style_load() {
        wp_register_style( 'dmd-pages-css-admin', plugins_url('style.css',  __FILE__));
        wp_enqueue_style( 'dmd-pages-css-admin' );
}
add_action( 'admin_enqueue_scripts', 'dmd_custom_style_load' );


add_action( 'wp_enqueue_scripts', 'dmd_enqueue_child_theme_styles');
function dmd_enqueue_child_theme_styles() {
		wp_register_style( 'dmd-pages-css-fe', plugins_url( 'style.css', __FILE__ ) );
		wp_enqueue_style( 'dmd-pages-css-fe' );		
}

function dmdPages() {
	
if ( !is_super_admin() || !is_admin_bar_showing() )
        return;	
	
        global $wpdb;
	global $wp_admin_bar;
	
/*
 * get all posts of post_type 'page' orderby post_title
 */
        $results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'posts WHERE post_status="publish" AND post_type="page" ORDER BY post_title ASC', OBJECT );  
	
/*
 * Create new menu in adminbar 
 */
	$wp_admin_bar->add_node(array(
		'id'    => 'FastMenu',
		'title' => 'FastMenu'
	));

/*
 * Create submenu in the adminbar
 */	
	foreach ($results as $post){
		$site = admin_url();
		$url = $site.'post.php?post='.$post->ID.'&action=edit';

		$wp_admin_bar->add_menu( array(
			'id'    => $post->post_title,
			'title' => $post->post_title,
			'href'  => $url,
			'parent'=>'FastMenu',
			'meta'=> array('target' => '_blank' ) 
		));			
		
	}	
}
add_action( 'wp_before_admin_bar_render', 'dmdPages' );