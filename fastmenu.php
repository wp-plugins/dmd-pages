<?php
/**
 * Plugin Name: dmd Pages
 * Plugin URI: https://dimadirekt.de/
 * Author: digitalmarketingditrekt gmbh
 * Author URI: https://dimadirekt.de/
 * Description: This plugin loads a list of all published pages into a menu in the adminbar. You can edit pages faster and don't need to search in the dashboard=>pages.
 * Version: 0.1
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
	
	global $wp_admin_bar;
	
/*
 * get all posts of post_type 'page' orderby post_title
 */
	$args = array(
		'posts_per_page'   => -1,
		'offset'           => 0,
		'category'         => '',
		'category_name'    => '',
		'orderby'          => 'post_title',
		'order'            => 'ASC',
		'include'          => '',
		'exclude'          => '',
		'meta_key'         => '',
		'meta_value'       => '',
		'post_type'        => 'page',
		'post_mime_type'   => '',
		'post_parent'      => '',
		'author'	   => '',
		'post_status'      => 'publish',
		'suppress_filters' => true 
	);
	$posts_array = get_posts( $args ); 
	
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
	foreach ($posts_array as $post){
		
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