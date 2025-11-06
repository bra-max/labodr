<?php
/**
 * Create a trait to handle the instance of the class
 */

 namespace AwesomeLogoCarouselBlocks\Inc;

trait Alcb_Instance {

    /**
     * Instance
     * 
     * @var object
     */
    private static $instance;

    /**
     * Get Instance
     * 
     * @return object
     */
    public static function get_instance() {
        if( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

}


