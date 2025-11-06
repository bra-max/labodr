<?php
/**
 * Plugin Name:       Awesome Logo Carousel Blocks
 * Plugin URI:        https://logocarousel.gutenbergkits.com
 * Description:       Showcase brand logos in interactive grid, carousel, slider, ticker, and list view.
 * Requires at least: 6.0
 * Requires PHP:      7.0
 * Version:           2.1.10
 * Author:            Gutenbergkits Team
 * Author URI:        https://gutenbergkits.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       awesome-logo-carousel-blocks
 * Domain Path:       /languages
 *
 */

// Stop Direct Access 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Blocks Final Class
 */
if( ! class_exists ( 'Alcb_Logo_Carousel' ) ) {

	final class Alcb_Logo_Carousel {

		// version 
		const VERSION = '2.1.10';
		
		// instance 
		private static $instance = null;

		// constructor
		public function __construct() {
			$this->constants();
			$this->includes();

			// enable redirect
			register_activation_hook( __FILE__, [ $this, 'redirect_to_admin' ] );
			// handle redirect
			add_action( 'admin_init', [ $this, 'handle_redirection' ] );
		}

		/**
		 * Define Constants
		 * 
		 * @return void
		 */
		public function constants() {
			$constants = [
				'ALCB_VERSION' => self::VERSION,
				'ALCB_FILE'    => __FILE__,
				'ALCB_URL'     => plugin_dir_url( __FILE__ ),
				'ALCB_PATH'    => plugin_dir_path( __FILE__ ),
				'ALCB_INC'     => plugin_dir_path( __FILE__ ) . 'inc/',
			];

			foreach ( $constants as $key => $value ) {
				if ( ! defined( $key ) ) {
					define( $key, $value );
				}
			}
		}

		/**
		 * Includes
		 * 
		 * @return void
		 */
		public function includes() {
			require_once ALCB_INC . 'instance.php';
			require_once ALCB_INC . 'init.php';
			require_once ALCB_PATH . 'admin/admin.php';
		}

		/**
		 * Instance 
		 * 
		 * @return Alcb_Logo_Carousel
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

				/**
		 * Redirect to admin page after activation
		 */
		public function redirect_to_admin() {
			set_transient( '_alcb_redirect', true, 30 );
		}

		/**
		 * Handle Redirection
		 */
		public function handle_redirection() {
			if ( get_transient( '_alcb_redirect' ) ) {
				delete_transient( '_alcb_redirect' );
				if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && ! ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
					wp_safe_redirect( admin_url( 'options-general.php?page=aclb-carousel' ) );
					exit;
				}
			}
		}

	}

	// initialize the plugin
	function alcb_logo_carousel() {
		return Alcb_Logo_Carousel::instance();
	}

	// kick-off
	alcb_logo_carousel();

}