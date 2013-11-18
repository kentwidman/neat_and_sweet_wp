<?php

function recipe_init() {
	register_post_type( 'recipe', array(
		'hierarchical'      => false,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_ui'           => true,
		'supports'          => array( 'title', 'editor' ),
		'has_archive'       => true,
		'query_var'         => true,
		'rewrite'           => true,
		'labels'            => array(
			'name'                => __( 'Recipes', 'YOUR-TEXTDOMAIN' ),
			'singular_name'       => __( 'Recipe', 'YOUR-TEXTDOMAIN' ),
			'add_new'             => __( 'Add new recipe', 'YOUR-TEXTDOMAIN' ),
			'all_items'           => __( 'Recipes', 'YOUR-TEXTDOMAIN' ),
			'add_new_item'        => __( 'Add new recipe', 'YOUR-TEXTDOMAIN' ),
			'edit_item'           => __( 'Edit recipe', 'YOUR-TEXTDOMAIN' ),
			'new_item'            => __( 'New recipe', 'YOUR-TEXTDOMAIN' ),
			'view_item'           => __( 'View recipe', 'YOUR-TEXTDOMAIN' ),
			'search_items'        => __( 'Search recipes', 'YOUR-TEXTDOMAIN' ),
			'not_found'           => __( 'No recipes found', 'YOUR-TEXTDOMAIN' ),
			'not_found_in_trash'  => __( 'No recipes found in trash', 'YOUR-TEXTDOMAIN' ),
			'parent_item_colon'   => __( 'Parent recipe', 'YOUR-TEXTDOMAIN' ),
			'menu_name'           => __( 'Recipes', 'YOUR-TEXTDOMAIN' ),
		),
	) );

}
add_action( 'init', 'recipe_init' );

function recipe_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['recipe'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Recipe updated. <a target="_blank" href="%s">View recipe</a>', 'YOUR-TEXTDOMAIN'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'YOUR-TEXTDOMAIN'),
		3 => __('Custom field deleted.', 'YOUR-TEXTDOMAIN'),
		4 => __('Recipe updated.', 'YOUR-TEXTDOMAIN'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Recipe restored to revision from %s', 'YOUR-TEXTDOMAIN'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Recipe published. <a href="%s">View recipe</a>', 'YOUR-TEXTDOMAIN'), esc_url( $permalink ) ),
		7 => __('Recipe saved.', 'YOUR-TEXTDOMAIN'),
		8 => sprintf( __('Recipe submitted. <a target="_blank" href="%s">Preview recipe</a>', 'YOUR-TEXTDOMAIN'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Recipe scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview recipe</a>', 'YOUR-TEXTDOMAIN'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Recipe draft updated. <a target="_blank" href="%s">Preview recipe</a>', 'YOUR-TEXTDOMAIN'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'recipe_updated_messages' );