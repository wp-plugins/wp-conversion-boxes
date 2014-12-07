<?php

/**
 * Adds WPCB_Widget widget.
 */

class WPCB_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
        
	function __construct() {
		parent::__construct(
			'wpcb_widget', // Base ID
			__('WP Conversion Boxes Pro','wp-conversion-boxes-pro'), // Name
			array( 
                            'description' => __('Add a conversion box to your sidebar.','wp-conversion-boxes-pro'), 
                            'classname' => 'wpcb_widget_wrap'
                        ) // Args
		);
        }
        
        /**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
                extract( $args );
		
                $wpcb_public =  WPCB_Public::get_instance();
                
                echo $before_widget;
                
                if ( isset( $instance['wpcb_selected_box'] ) ) {
			$wpcb_selected_box = $instance['wpcb_selected_box'];
		}
		else {
			$wpcb_selected_box = '';
		}
                echo $wpcb_public->show_the_box($wpcb_selected_box);
                echo $after_widget;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
                $instance = wp_parse_args(
                    (array)$instance,
                        array(
                            'wpcb_selected_box' => ''
                        )
                );
            
		if ( isset( $instance['wpcb_selected_box'] ) ) {
			$wpcb_selected_box = $instance['wpcb_selected_box'];
		}
		else {
			$wpcb_selected_box = '';
		}
                $wpcb =  WPCB_Admin::get_instance();
		?>
                <div class="wpcb_widget_wrap">
                    <p>
                    <label for="<?php echo $this->get_field_id('wpcb_selected_box'); ?>"><?php _e('Select a box to show in this widget:','wp-conversion-boxes-pro'); ?></label><br />
                    <?php echo $wpcb->wpcb_box_list($wpcb_selected_box,'default',$this->get_field_id('wpcb_selected_box'),$this->get_field_name('wpcb_selected_box')); ?>
                    </p>
                </div>
		<?php 
                
	}

	/**
	 * Sanitize widget form values as they are saved.
         * 
         * Here we got new instance value and returned it.
	 */
	public function update( $new_instance, $old_instance ) {

		$old_instance['wpcb_selected_box'] = ( ! empty( $new_instance['wpcb_selected_box'] ) ) ? strip_tags( $new_instance['wpcb_selected_box'] ) : '';

		return $old_instance;

	}

} // class Foo_Widget