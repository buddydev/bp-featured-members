<?php

class BP_Featured_Members_Template_Helper {

	public function setup() {

		//buttons
		add_action( 'bp_directory_members_actions', array( $this, 'add_member_list_button' ) );
		add_action( 'bp_group_members_list_item_action', array( $this, 'add_member_list_button' ) );
		add_action( 'bp_member_header_actions', array( $this, 'add_member_header_button' ) );
		//add to members directory?
		add_action( 'bp_members_directory_member_types', array( $this, 'directory_tab' ) );
	}

	/**
	 * Generate the button for the given member
	 *
	 * @param int $member_id user id
	 */
	public function generate_button( $member_id ) {

		//$user_id = bp_loggedin_user_id();

		// button will not show for logged-in user in loop
		//or should the admin be able to set his profile as featured?
		//if ( $user_id == $member_id ) {
		//	return '';
		//}

		$is_featured = get_user_meta( $member_id, '_is_featured', true );

		$button_label = $is_featured ? 'Remove Featured' : 'Set Featured';

		?>
		<div class="generic-button bp-featured-members-button">
			<a href="#" data-nonce="<?php echo wp_create_nonce( 'bp-featured-members-toggle-' . $member_id ) ?>"
			   data-user-id="<?php echo $member_id ?>">
				<?php echo $button_label; ?>
			</a>
		</div>
		<?php
	}

	public function add_member_header_button() {
		//checking authentication
		if ( ! bp_featured_members()->current_user_can_toggle_member_status() ) {
			return '';
		}

		$this->generate_button( bp_displayed_user_id() );
	}

	/*
	 * add button to mark user as featured or un-featured.
	 * return if user is not able to mark user as featured or un-featured
	 *
	 * @return string
	 */
	public function add_member_list_button() {

		//checking authentication
		if ( ! bp_featured_members()->current_user_can_toggle_member_status() ) {
			return '';
		}

		$this->generate_button( bp_get_member_user_id() );
	}

	public function directory_tab() {

		if ( ! apply_filters( 'bp_featured_members_display_directory_tab', true ) ) {
			return;
		}
		?>
		<li id="members-featured">
			<a href="<?php bp_members_directory_permalink(); ?>"><?php printf( __( 'Featured Members %s', 'bp-featured-members' ), '<span>' . bp_featured_members()->get_count() . '</span>' ); ?></a>
		</li>
		<?php
	}
}

$helper = new BP_Featured_Members_Template_Helper();
$helper->setup();
