<?php 
/**
 * THEME PERSONALIZER USING CUSTOM PAGE OPTIONS AND CUSTOM META BOXES
 */

if (! function_exists('ThemeTweaker')) {
    function ThemeTweaker() {
        $_dir_path = '/inc';
        include_once( get_template_directory() . $_dir_path .'/ThemeTweaker/FieldGenerator/FieldGenerator.php' );
        include_once( get_template_directory() . $_dir_path .'/ThemeTweaker/FieldGenerator/FieldRenderer.php' );  

        /**
         * Custom template metaboxes.
         */
        require_once get_template_directory() . $_dir_path .'/ThemeTweaker/theme-meta/theme-metaboxes.php';
        
        /**
         * Custom template options.
         */
        require_once get_template_directory() . $_dir_path .'/ThemeTweaker/theme-options/options-page.php';
        
        /**
         * Install menus
         */
        require_once get_template_directory() . $_dir_path .'/ThemeTweaker/theme-menu-install/theme-menu-install.php';
    }
}

add_action( 'after_setup_theme', 'ThemeTweaker');