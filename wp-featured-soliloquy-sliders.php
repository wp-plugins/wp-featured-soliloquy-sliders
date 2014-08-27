<?php
/**
 * Plugin Name: WP Featured Soliloquy Slider
 * Plugin URI: http://topher1kenobe.com
 * Description: Provides a mechanism for associating a Soliloquy Slider with a Page or Post
 * Author: Topher
 * Version: 1.1
 * Author URI: http://codeventure.net
 * Text Domain: wp-featured-soliloquy
 */


/**
 * Provides a mechanism for associating a WordPress Menu with a Page or Post
 *
 * @package T1K_Featured_Sliders
 * @since T1K_Featured_Sliders 1.0
 * @author Topher
 */


/**
 * Instantiate the T1K_Featured_Sliders instance
 * @since T1K_Featured_Sliders 1.0
 */
add_action( 'plugins_loaded', array( 'T1K_Featured_Sliders', 'instance' ) );

/**
 * Main T1K Featured Sliders Class
 *
 * Contains the main functions for the admin side of T1K Featured Sliders
 *
 * @class T1K_Featured_Sliders
 * @version 1.0.0
 * @since 1.0
 * @package T1K_Featured_Sliders
 * @author Topher
 */
class T1K_Featured_Sliders {

	/**
	* Instance handle
	*
	* @static
	* @since 1.2
	* @var string
	*/
	private static $__instance = null;

	/**
	 * T1K_Featured_Sliders Constructor, actually contains nothing
	 *
	 * @access public
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Instance initiator, runs setup etc.
	 *
	 * @access public
	 * @return self
	 */
	public static function instance() {
		if ( ! is_a( self::$__instance, __CLASS__ ) ) {
			self::$__instance = new self;
			self::$__instance->setup();
		}
		
		return self::$__instance;
	}

	/**
	 * Runs things that would normally be in __construct
	 *
	 * @access private
	 * @return void
	 */
	private function setup() {

		// only do this in the admin area
		if ( is_admin() ) {
			add_action( 'save_post', array( $this, 'save' ) );
			add_action( 'add_meta_boxes', array( $this, 'sliders_meta_box' ) );
		}

	}

	/**
	 * Make meta box holding select menu of Sliders
	 *
	 * @access public
	 * @return void
	 */
	public function sliders_meta_box( $post_type ) {

		// limit meta box to certain post types
		$post_types = array( 'post', 'page' );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'wp-fetaured-slider',
				esc_html__( 'Featured Slider', 'wp-featured-sliders' ),
				array( $this, 'render_sliders_meta_box_contents' ),
				$post_type,
				'advanced',
				'high'
			);
		}
	}

	/**
	 * Render select box of WP Sliders
	 *
	 * @access public
	 * @return void
	 */
	public function render_sliders_meta_box_contents() {

		global $post;

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'wp-featured-sliders', 'wp_featured_sliders_nonce' );

		// go get the meta field
		$wpfs_meta_value = get_post_meta( $post->ID, '_t1k_featured_slider', true );

		// Display the form, using the current value.

		echo '<p>';
		esc_html_e( 'Please choose from the existing sliders below.  If you need to create a new Slider, please go to ', 'wp-featured-sliders' );
		echo '<a href="' . esc_url( admin_url( 'edit.php?post_type=soliloquy'  ) ) . '">';
		esc_html_e( 'the Soliloquy Admin ', 'wp-featured-sliders' );
		echo '</a>.';
		echo '</p>';

		$args = array (
			'post_type' => 'soliloquy'
		);

		// The Query
		$sliders = get_posts( $args );


		// make sure we have some
		if ( count( $sliders ) > 0 ) {
			echo '<select name="_t1k_featured_slider">' . "\n";
			echo '<option value="">' . __( 'Please choose', 'wp-featured-sliders' ) . '</option>' . "\n";
			foreach ( $sliders as $key => $slider ) {
				echo '<option value="' . absint( $slider->ID ) . '"' . selected( $wpfs_meta_value, $slider->ID, false ) . '>' . esc_html( $slider->post_title ) . '</option>' . "\n";
			}
			echo '</select>' . "\n";
		} else {
			echo '<p>';
			esc_html_e( 'No sliders found, ', 'wp-featured-sliders' );
			echo '<a href="' . esc_url( admin_url( 'edit.php?post_type=soliloquy'  ) ) . '">';
			esc_html_e( 'let\'s go make some!', 'wp-featured-sliders' );
			echo '</a></p>';
		}

	}

	/**
	 * Updates the options table with the form data
	 *
	 * @access public
	 * @param int $post_id
	 * @return void
	 */
	public function save( $post_id ) {

		// Check if the current user is authorised to do this action. 
		$post_type = get_post_type_object( get_post( $post_id )->post_type );
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return;
		}

		// Check if the user intended to change this value.
		if ( ! isset( $_POST['wp_featured_sliders_nonce'] ) || ! wp_verify_nonce( $_POST['wp_featured_sliders_nonce'], 'wp-featured-sliders' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}


		// Sanitize user input
		$wp_featured_slider = sanitize_text_field( $_POST['_t1k_featured_slider'] );

		// Update or create the key/value
		update_post_meta( $post_id, '_t1k_featured_slider', $wp_featured_slider );

	}

	// end class
}

?>
