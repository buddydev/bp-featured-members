<?php

// exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// BP_Featured_Members_List_Widget widget
class BP_Featured_Members_List_Widget extends WP_Widget {

	public function __construct( $name = '', $widget_options = array() ) {

		if ( empty( $name ) ) {
			$name = __( 'BuddyPress Featured Members', 'bp-featured-members' );
		}

		parent::__construct( false, $name, $widget_options );
	}

	public function widget( $args, $instance ) {

		bp_featured_members()->set( 'max', $instance['max'] );
		bp_featured_members()->set( 'view', $instance['view'] );
		bp_featured_members()->set( 'context', 'widget' );
		bp_featured_members()->set( 'member_type', isset( $instance['member_type'] ) ? $instance['member_type'] : '' );

		$slider_settings = array(
			'item'         => 1,
			'slideMargin'  => 0,
			'mode'         => 'slide',//fade
			'speed'        => 400,
			'auto'         => true,
			'pauseOnHover' => false,
			'controls'     => false,
			'loop'         => true,
		);

		bp_featured_members()->set( 'slider-settings', $slider_settings );

		//log loop start
		bp_featured_members()->start_loop();

		echo $args['before_widget'];

		echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $instance['title'] ), $instance, $this->id_base ) . $args['after_title'] ;

		?>

		<div class="bp-featured-members-widget">
			<?php bp_fm_load_members_list( $instance['view'], 'widget' ); ?>
		</div>

		<?php echo $args['after_widget']; ?>

		<?php
		bp_featured_members()->end_loop();//mark loop end
	}

	public function update( $new_instance, $old_instance ) {

		$view_options      = bp_fm_get_views_options();
		$view              = key_exists( $new_instance['view'], $view_options ) ? $new_instance['view'] : 'list';
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['max']   = strip_tags( $new_instance['max'] );
		$instance['view']  = $view;
		$instance['member_type']  = isset( $new_instance['member_type'] ) ? $new_instance['member_type'] : '' ;//not validating as admins are not supposed to be fooling around

		return $instance;
	}

	public function form( $instance ) {

		$defaults = array(
			'title'         => __( 'Featured Members', 'bp-featured-members' ),
			'max'           => 5,
			'view'          => 'list',
            'member_type'   => '',
		);

		$instance     = wp_parse_args( (array) $instance, $defaults );
		$title        = strip_tags( $instance['title'] );
		$max          = strip_tags( $instance['max'] );
		$view         = $instance['view'];
		$member_type  = $instance['member_type'];
		$view_options = bp_fm_get_views_options();

		$member_types = bp_get_member_types( array(), 'objects' );

		?>
		<p>
			<label>
				<?php _e( 'Title:', 'bp-featured-members' ); ?><br/>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>"
				       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
				       value="<?php echo esc_attr( $title ); ?>" class="widefat"/>
			</label>
		</p>

		<p>
			<label>
				<?php _e( 'Max. number of users to show:', 'bp-featured-members' ); ?>
				<input id="<?php echo $this->get_field_id( 'max' ); ?>" name="<?php echo $this->get_field_name( 'max' ); ?>" type="text" value="<?php echo esc_attr( $max ); ?>" />
			</label>
		</p>

        <?php if ( ! empty( $member_types ) ) : ?>
            <p>
                <label>
					<?php _e( 'Filter by Member Type', 'bp-featured-members' ); ?>
                    <select id="<?php echo $this->get_field_id( 'member_type' ); ?>" name ="<?php echo $this->get_field_name( 'member_type' ); ?>">
                        <option value="" <?php selected( $member_type, "" ); ?> ><?php _e( 'N/A', 'bp-featured-members' );?> </option>
						<?php foreach ( $member_types as $member_type_obj ): ?>
                            <option value="<?php echo esc_attr( $member_type_obj->name ); ?>" <?php selected( $member_type, $member_type_obj->name ); ?> ><?php echo esc_html( $member_type_obj->labels['singular_name'] ); ?></option>
						<?php endforeach; ?>
                    </select>
                </label>
                <br/>
            </p>
        <?php endif;?>

        <p>
			<label>
				<?php _e( 'Display View:', 'bp-featured-members' ); ?>
				<select id="<?php echo $this->get_field_id( 'view' ); ?>" name="<?php echo $this->get_field_name( 'view' ); ?>">
                    <?php foreach ( $view_options as $value => $option_name ) : ?>
						<option value="<?php echo $value ?>" <?php selected( $view, $value ) ?>><?php echo $option_name; ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</p>
		<?php
	}
}

/**
 * Register Widget
 */
function bp_featured_members_register_widgets() {
	register_widget( 'BP_Featured_Members_List_Widget' );
}

add_action( 'bp_widgets_init', 'bp_featured_members_register_widgets' );