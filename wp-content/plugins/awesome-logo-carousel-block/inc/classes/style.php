<?php
/**
 * Dynamic Styles
 */

namespace AwesomeLogoCarouselBlocks\Inc;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'Alcb_Style' ) ) {

    class Alcb_Style {

        use Alcb_Instance;

        /**
         * Constructor
         */
        public function __construct() {
            add_filter( 'render_block', [ $this, 'generate_style' ], 10, 2 );
            add_filter( 'render_block_lcb/logo-carousel', [ $this, 'add_unique_class' ], 10, 2 );
        }

        /**
         * Generate Style
         * 
         * @return void
         */
        public function generate_style( $block_content, $block ) {
            if ( isset( $block['blockName'] ) && str_contains( $block['blockName'], 'lcb/' ) ) {
                $attrs = $block['attrs'] ?? [];
    
                // Early return if attributes are empty.
                if ( empty( $attrs ) ) {
                    return $block_content;
                }
            
                // Get the unique ID safely.
                $unique_id = isset( $attrs['sliderId'] ) ? sanitize_key( $attrs['sliderId'] ) : '';
                if ( empty( $unique_id ) ) {
                    return $block_content;
                }
            
                $block_style = $attrs['blockStyle'] ?? '';

                if ( ! empty( $block_style ) ) {
                    $handle = 'alcb-style-' . $unique_id;
                    $this->render_inline_css( $handle, $block_style );
                    return $block_content;
                }
            }


            return $block_content;
        }
        /**
         * Render Inline CSS
        */
        public function render_inline_css( $handle, $css ) {
            wp_register_style( $handle, false, array(), ALCB_VERSION, 'all' );
            wp_enqueue_style( $handle, false, array(), ALCB_VERSION, 'all' );
            wp_add_inline_style( $handle, $css );
        }

        /**
         * Add Unique Class
         * 
         * @return void
         */
        public function add_unique_class( $block_content, $block ) {
            $attrs = $block['attrs'] ?? [];

            // Early return if attributes are empty.
            if ( empty( $attrs ) ) {
                return $block_content;
            }

            // Get the unique ID safely and properly escape it
            $unique_id = isset( $attrs['sliderId'] ) ? esc_attr( $attrs['sliderId'] ) : '';
            if ( empty( $unique_id ) ) {
                return $block_content;
            }
            
            $block_content = str_replace( 'wp-block-lcb-logo-carousel', 'wp-block-lcb-logo-carousel ' . $unique_id, $block_content );
            return $block_content;
            
        }
    }

    // Initialize the class
    Alcb_Style::get_instance();

}