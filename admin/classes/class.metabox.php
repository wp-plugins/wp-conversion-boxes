<?php

class WPCB_Metabox{
    
        /************************************
	 * Instance of this class.
	 ************************************/
	protected static $instance = null;
        
        /************************************
	 * Initialize Meta Boxes
	 ************************************/
        function __construct() {
            add_action( 'add_meta_boxes', array( $this , 'meta_box_for_all_post_types' ) );
            add_action( 'save_post', array( $this , 'meta_box_save_data' ) );
        }
        
        /************************************
	 * Return an instance of this class.
	 ************************************/
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
        
        /**
        * Adds a meta box with drop down list of conversion boxes.
        */
        function meta_box_for_all_post_types() {
                
                $wpcb = WPCB_Admin::get_instance();
                $screens = $wpcb->get_available_post_types();
                
                foreach ( $screens as $screen ) {
                    add_meta_box(
                            'wpcb_box_post_meta',
                            __('WP Conversion Boxes Pro','wp-conversion-boxes-pro'),
                            array( $this , 'meta_box_form'),
                            $screen
                    );
                }
        }
        
        /**
         * Prints the box content.
         * 
         * @param WP_Post $post The object for the current post/page.
         */
        function meta_box_form( $post ) {
                
                $wpcb = WPCB_Admin::get_instance();
                
                $value = get_post_meta( $post->ID, 'wpcb_meta_selected_box_id', true );
                
                ?>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="wpcb_meta_selected_box_id"><?php _e('Select Conversion Box','wp-conversion-boxes-pro'); ?>: </label></th>
                            <td>
                                <?php echo $wpcb->wpcb_box_list($value,'both','wpcb_meta_selected_box_id'); ?>
                                <?php wp_nonce_field( 'wpcb_meta_box', 'wpcb_meta_box_nonce' ); ?>
                                <p class="description"><?php echo sprintf( __('Assign a conversion box for this %s. Setting a box here will override all other settings and show the selected box under this %s.','wp-conversion-boxes-pro'), $post->post_type, $post->post_type); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php
        }

        function meta_box_save_data( $post_id ) {

                if ( ! isset( $_POST['wpcb_meta_box_nonce'] ) ) {
                        return;
                }

                if ( ! wp_verify_nonce( $_POST['wpcb_meta_box_nonce'], 'wpcb_meta_box' ) ) {
                    return;
                }

                if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                    return;
                }
                
                // Check the user's permissions.
                if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

                        if ( ! current_user_can( 'edit_page', $post_id ) ) {
                                return;
                        }

                } else {

                        if ( ! current_user_can( 'edit_post', $post_id ) ) {
                                return;
                        }
                }
                
                if ( ! isset( $_POST['wpcb_meta_selected_box_id'] ) ) {
                    return;
                }

                $my_data = sanitize_text_field( $_POST['wpcb_meta_selected_box_id'] );
                update_post_meta( $post_id, 'wpcb_meta_selected_box_id', $my_data );
        }
}