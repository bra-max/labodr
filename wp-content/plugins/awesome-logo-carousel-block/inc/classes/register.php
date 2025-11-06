<?php 
/**
 * Register Class to register the blocks
 */

namespace AwesomeLogoCarouselBlocks\Inc;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'Alcb_Register_Blocks' ) ) {

    class Alcb_Register_Blocks {

        use Alcb_Instance;

        /**
         * Constructor
         * 
         * @return void
         */
        public function __construct() {
            add_action( 'init', [ $this, 'register_block' ] );
        }

        /**
         * Register Block
         * 
         * @return void
         */
        public function register_block() {

            $blocks = [
                [
                    'name'   => 'logo-carousel',
                    'is_pro' => false
                ],
                [
                    'name'   => 'logo',
                    'is_pro' => false
                ],
                [
                    'name'   => 'grid-logo',
                    'is_pro' => false
                ],
                [
                    'name'   => 'stagger-child',
                    'is_pro' => true
                ],
                [
                    'name'   => 'stagger',
                    'is_pro' => true
                ],
                [
                    'name'   => 'ticker-child',
                    'is_pro' => true
                ],
                [
                    'name'   => 'ticker-carousel',
                    'is_pro' => true
                ]
            ]; 

            if ( ! empty( $blocks ) && is_array( $blocks ) ) {
                foreach ( $blocks as $block ) {
                    // $block_path = trailingslashit( ALCB_PATH ) . '/build/blocks/' . $block;
                    // if ( file_exists( $block_path ) ) {
                    //     register_block_type( $block_path );
                    // }

                    if( $block['is_pro'] ) {

                        if( ! class_exists( 'Alcb_Logo_Carousel_Pro' ) ) {
                            return; 
                        }

                        $block_path = trailingslashit( ALCBP_PATH ) . '/build/blocks/' . $block['name'];
                        if ( file_exists( $block_path ) ) {
                            register_block_type( $block_path );
                        }
                    } else {
                        $block_path = trailingslashit( ALCB_PATH ) . '/build/blocks/' . $block['name'];
                        if ( file_exists( $block_path ) ) {
                            register_block_type( $block_path );
                        }
                    }

                }
            }
        }
    }

    // Initialize the class
    Alcb_Register_Blocks::get_instance();

}