<?php
// exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a list og valid view options
 *
 * @return array
 */
function bp_fm_get_views_options() {
	$options = array(
		'list'    => __( 'List', 'bp-featured-members' ),
		'slider'  => __( 'Slider', 'bp-featured-members' ),
		'default' => __( 'Theme Default', 'bp-featured-members' ),
	);

	return $options;
}

function bp_members_slider_data_attributes( $settings = array() ) {
	foreach ( $settings as $key => $val ) {
		echo "data-$key='" . esc_attr( $val ) . "' ";
	}
}

function bp_fm_load_members_list( $view_type, $context = 'widget', $load = true ) {

	if ( ! in_array( $view_type, array( 'default', 'list', 'slider' ) ) ) {
		$view_type = 'list';//fallback to list if invalid view type
	}

	//in case of default view type, we load it from theme
	if ( $view_type == 'default' ) {
		$located = bp_locate_template( array( 'members/members-loop.php' ) , false, false);
	} else {

		$templates = array(
			"members/featured/{$context}/members-loop-{$view_type}.php",
			"members/featured/members-loop-{$view_type}.php",
		);

		$located = bp_locate_template( $templates, false, false );

		if ( ! $located ) {
			$located = bp_featured_members()->get_path() . 'templates/members-loop-' . $view_type . '.php';
		}
	}

	$located = apply_filters( 'bp_featured_members_located_template', $located, $view_type, $context );

	if ( $load ) {
		require $located;
	} else {
		return $located;
	}

}
