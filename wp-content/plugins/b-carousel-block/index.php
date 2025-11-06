<?php

/**
 * Plugin Name: Carousel Block
 * Description: Create stunning responsive carousels effortlessly.
 * Version: 1.1.7
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: carousel-block
 */
// ABS PATH
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( function_exists( 'bicb_fs' ) ) {
    bicb_fs()->set_basename( false, __FILE__ );
} else {
    define( 'BICB_VERSION', ( isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.1.7' ) );
    define( 'BICB_DIR_URL', plugin_dir_url( __FILE__ ) );
    define( 'BICB_DIR_PATH', plugin_dir_path( __FILE__ ) );
    define( 'BICB_HAS_PRO', file_exists( dirname( __FILE__ ) . '/vendor/freemius/start.php' ) );
    if ( !function_exists( 'bicb_fs' ) ) {
        function bicb_fs() {
            global $bicb_fs;
            if ( !isset( $bicb_fs ) ) {
                if ( BICB_HAS_PRO ) {
                    require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                } else {
                    require_once dirname( __FILE__ ) . '/vendor/freemius-lite/start.php';
                }
                $bicbConfig = [
                    'id'                  => '15342',
                    'slug'                => 'b-carousel-block',
                    'premium_slug'        => 'b-carousel-block-pro',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_a45f62e2b56488230717561f70db4',
                    'is_premium'          => true,
                    'premium_suffix'      => 'Pro',
                    'has_premium_version' => true,
                    'has_addons'          => false,
                    'has_paid_plans'      => true,
                    'trial'               => [
                        'days'               => 7,
                        'is_require_payment' => true,
                    ],
                ];
                $freeConfig = array_merge( $bicbConfig, [
                    'menu' => [
                        'slug'    => 'carousel-block',
                        'contact' => false,
                        'support' => false,
                        'parent'  => [
                            'slug' => 'tools.php',
                        ],
                    ],
                ] );
                $proConfig = array_merge( $bicbConfig, [
                    'menu' => [
                        'slug'       => 'edit.php?post_type=bicb',
                        'first-path' => 'edit.php?post_type=bicb',
                        'contact'    => false,
                        'support'    => false,
                    ],
                ] );
                $bicb_fs = ( BICB_HAS_PRO ? fs_dynamic_init( $proConfig ) : fs_lite_dynamic_init( $freeConfig ) );
            }
            return $bicb_fs;
        }

        bicb_fs();
        do_action( 'bicb_fs_loaded' );
    }
    function bicbIsPremium() {
        return ( BICB_HAS_PRO ? bicb_fs()->can_use_premium_code() : false );
    }

    // Require Files
    require_once BICB_DIR_PATH . 'includes/Patterns.php';
    if ( BICB_HAS_PRO ) {
        require_once BICB_DIR_PATH . 'includes/admin/CPT.php';
    } else {
        require_once BICB_DIR_PATH . 'includes/admin/SubMenu.php';
    }
    class BICBPlugin {
        function __construct() {
            add_action( 'init', [$this, 'onInit'] );
            add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
            add_action( 'enqueue_block_editor_assets', [$this, 'enqueueBlockEditorAssets'] );
        }

        function onInit() {
            register_block_type( __DIR__ . '/build' );
        }

        function adminEnqueueScripts( $hook ) {
            if ( strpos( $hook, 'carousel-block' ) ) {
                wp_enqueue_style(
                    'bicb-admin-dashboard',
                    BICB_DIR_URL . 'build/admin-dashboard.css',
                    [],
                    BICB_VERSION
                );
                wp_enqueue_script(
                    'bicb-admin-dashboard',
                    BICB_DIR_URL . 'build/admin-dashboard.js',
                    ['react', 'react-dom'],
                    BICB_VERSION,
                    true
                );
                wp_set_script_translations( 'bicb-admin-dashboard', 'carousel-block', BICB_DIR_PATH . 'languages' );
            }
        }

        static function renderDashboard() {
            ?>
			<div
				id='bicbDashboard'
				data-info='<?php 
            echo esc_attr( wp_json_encode( [
                'version'   => BICB_VERSION,
                'isPremium' => bicbIsPremium(),
                'hasPro'    => BICB_HAS_PRO,
            ] ) );
            ?>'
			></div>
		<?php 
        }

        function enqueueBlockEditorAssets() {
            wp_add_inline_script( 'bicb-carousel-editor-script', 'const bicbpipecheck = ' . wp_json_encode( bicbIsPremium() ) . '; const bicbpricingurl = "' . admin_url( ( BICB_HAS_PRO ? 'edit.php?post_type=bicb&page=carousel-block#/pricing' : 'tools.php?page=carousel-block#/pricing' ) ) . '";', 'before' );
        }

    }

    new BICBPlugin();
}