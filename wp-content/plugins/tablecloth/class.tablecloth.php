<?php
/**
 * Plugin Name: TableCloth
 * Plugin URI: https://github.com/ThatGerber/wp-tablecloth
 * Description: Creating beautiful interactive tables.
 * Version: 0.1.0
 * Author: Christopher Gerber
 * Author URI: http://www.chriswgerber.com/
 * License: GPL2
 */

if ( ! class_exists( 'tablecloth' ) ) {

    class tablecloth {

        /**
         * @const PLUGIN_NAME Name of the plugin
         */
        CONST PLUGIN_NAME = 'tablecloth';

        /**
         * @const PLUGIN_VER Version number for the plugin
         */
        CONST PLUGIN_VER  = '0.1.0';

        /**
         * @var string $asset_uri Directory for tablecloth JS files
         */
        public $asset_uri;

        /**
         * Constructor
         *
         * Begins registering scripts and preparing variables.
         */
        public function __construct() {

            $this->asset_uri = plugins_url('', __FILE__) . '/assets';

            add_action( 'wp_enqueue_scripts', array($this, 'register_scripts') );

            add_action( 'wp', array($this, 'detect_shortcode') );

            // Create Shortcode
            add_shortcode('tablecloth' , array($this, 'shortcode'));
        }

        /**
         * Creates the shortcode to wrap around the table.
         *
         * @param null   $atts
         * @param string $content
         *
         * @return string
         */
        public function shortcode( $atts = null, $content = '' ) {

            return '<div class="tablecloth-container">' . $content . '</div>';
        }

        /**
         * Code to run if the tablecloth is to be run.
         */
        public function init_tablecloth() {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        }

        /**
         * Registers the scripts and styles
         */
        public function register_scripts() {
            // CSS
            wp_register_style(
                self::PLUGIN_NAME . '-tablecloth',
                $this->asset_uri . '/tablecloth.css',
                array(),
                null
            );
            // Tablecloth Call
            wp_register_script(
                self::PLUGIN_NAME . '-clothed',
                $this->asset_uri . '/clothed.js',
                array(),
                null,
                true
            );
        }

        /**
         * Enqueues scripts and styles.
         */
        public function enqueue_scripts() {
            wp_enqueue_style(self::PLUGIN_NAME . '-tablecloth');
            wp_enqueue_script(self::PLUGIN_NAME . '-clothed');
        }

        /**
         * Check to see if shortcode exists in any of the post objects.
         */
        public function detect_shortcode() {
            global $wp_query;
            $posts = $wp_query->posts;
            $pattern = get_shortcode_regex();
            foreach ($posts as $post){
                if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
                       && array_key_exists( 2, $matches )
                       && in_array( 'tablecloth', $matches[2] ) )
                {
                    // enque my css and js
                    $this->init_tablecloth();
                    break;
                }
            }
        }
    }
}

new tablecloth;