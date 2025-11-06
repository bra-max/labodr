<?php
/**
 * Init Class for Awesome Logo Carousel Block Plugin 
 */

 namespace AwesomeLogoCarouselBlocks\Inc;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'Alcb_Init' ) ) {

    class Alcb_Init {

        use Alcb_Instance;

        /**
         * Constructor
         * 
         * @return void
         */
        public function __construct() {
            $this->hooks();
        }

        /**
         * Hooks
         * 
         * @return void
         */
        public function hooks() {
            add_filter( 'block_categories', [ $this, 'register_block_category' ], 10, 2 );
            $this->includes();
        }

        /**
         * Register Block Category
         * 
         * @param array $categories
         * @param object $post
         * @return array
         */
        public function register_block_category( $categories, $post ) {
            return array_merge(
                [
                    [
                        'slug'  => 'logo-blocks',
                        'title' => __( 'Logo Blocks', 'awesome-logo-carousel-blocks' ),
                        'icon'  => null,
                    ],
                ],
                $categories
            );
        }

        /**
         * Includes Files
         */
        public function includes() {
            require_once ALCB_INC . 'classes/register.php';
            require_once ALCB_INC . 'classes/enqueue.php';
            require_once ALCB_INC . 'classes/style.php';
        }

    }

    // Initialize the class
    Alcb_Init::get_instance();

}
