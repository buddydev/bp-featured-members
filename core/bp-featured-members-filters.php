<?php
/**
 * Filter bp_has_members() transparently for listing our members
 *
 * @param $args
 *
 * @return mixed
 */
function bp_featured_members_filter_members_list( $args ) {
	//our scope must be featured
	if ( ! isset( $args['scope'] ) || $args['scope'] != 'featured' ) {
		return $args;
	}

	$args['meta_key'] = '_is_featured';
	$args['meta_value'] = 1;

	//which other params are we allowing?
	$max = bp_featured_members()->get( 'max' );
	$member_type = bp_featured_members()->get( 'member_type' );

	if ( $max ) {
		$args['per_page'] = absint( $max );
		$args['max'] = absint( $max );
	}

	if ( $member_type ) {
		$args['member_type'] = $member_type;
	}

	return $args;
}

add_filter( 'bp_after_has_members_parse_args', 'bp_featured_members_filter_members_list' );
