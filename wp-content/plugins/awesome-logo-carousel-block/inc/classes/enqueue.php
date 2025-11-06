<?php
/**
 * Enqueue scripts and styles.
 */

namespace AwesomeLogoCarouselBlocks\Inc; 

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Alcb_Enqueue {

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
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ], 2, 2 );
        add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_assets' ] ); 
    }

    /**
     * Enqueue Block Editor Assets
     * 
     * @return void
     */
    public function enqueue_block_editor_assets() {

        // modules 
        if ( file_exists( trailingslashit( ALCB_PATH ) . 'build/modules/index.asset.php' ) ) {
            $md_file = require_once trailingslashit( ALCB_PATH ) . 'build/modules/index.asset.php';
            if( ! is_array( $md_file ) ) {
                return;
            }
            wp_enqueue_script(
                'alcb-modules',
                ALCB_URL . 'build/modules/index.js',
                $md_file['dependencies'],
                $md_file['version'],
                false
            );
            
        }

        // gloabl 
       if( file_exists( ALCB_PATH . 'build/global/index.asset.php' ) ) {
            $gd_file = require_once ALCB_PATH . 'build/global/index.asset.php';
            if ( is_array( $gd_file ) ) {
                wp_register_script(
                    'alcb-global',
                    ALCB_URL . 'build/global/index.js',
                    $gd_file['dependencies'],
                    $gd_file['version'],
                    false
                );
                wp_register_style(
                    'alcb-global-style',
                    ALCB_URL . 'build/global/index.css',
                    [],
                    $gd_file['version'],
                    'all'
                );
            }
        }

        // localize script 
        wp_localize_script( 'alcb-global', 'alcbData', [
            'hasPro' => class_exists('Alcb_Logo_Carousel_Pro')
        ] );
    }

    /**
     * Enqueue Block Assets
     * 
     * @return void
     */
    public function enqueue_block_assets() {
        wp_register_style( 'alcb-swiper', ALCB_URL . 'inc/assets/css/swiper-bundle.min.css', [], '11.1.14', 'all' );
        wp_register_script( 'alcb-swiper', ALCB_URL . 'inc/assets/js/swiper-bundle.min.js', [], '11.1.14', true );
    }

}

// Initialize the class
Alcb_Enqueue::get_instance();