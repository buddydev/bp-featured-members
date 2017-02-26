<?php
add_shortcode( 'bp-featured-members', 'bp_featured_members_shortcode' );

function bp_featured_members_shortcode( $atts, $content = '' ) {
	$atts = shortcode_atts( array(
		'view'               => 'list', //slider,default
		'max'                => 5,
        'member_type'        => '',
		'slide-item'         => 1,
		'slide-slideMargin'  => 0,
		'slide-mode'         => 'slide',//fade
		'slide-speed'        => 400,
		'slide-auto'         => true,
		'slide-pauseOnHover' => false,
		'slide-controls'     => false,
		'slide-loop'         => true,
	), $atts );


	$max  = $atts['max'];
	$view = $atts['view'];
	bp_featured_members()->set( 'max', $max );
	bp_featured_members()->set( 'view', $view );
	bp_featured_members()->set( 'context', 'shortcode' );
	bp_featured_members()->set( 'member_type', isset( $atts['member_type'] ) ? $atts['member_type'] : '' );
	unset( $atts['max'] );
	unset( $atts['view'] );

	$new_settings = array();

	foreach ( $atts as $key => $val ) {
		$new_settings[ str_replace( 'slide-', '', $key ) ] = $val;
	}

	bp_featured_members()->set( 'slider-settings', $new_settings );

	//log loop start
	bp_featured_members()->start_loop();
	ob_start();
	?>

	<div class="bp-featured-members-shortcode">
		<?php bp_fm_load_members_list( $view, 'shortcode' ); ?>
	</div>
	<?php
	bp_featured_members()->end_loop();//mark loop end
	$content = ob_get_clean();
	return $content;
}