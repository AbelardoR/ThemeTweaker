<?php 
/**
 * THEME PERSONALIZER USING CUSTOM PAGE OPTIONS AND CUSTOM META BOXES
 * ThemeTweaker is a powerful tool that allows you to create custom fields in page options, empowering you to create or customize WordPress themes with ease. 
 * Leveraging PHP and WordPress functions, ThemeTweaker provides a seamless way to tailor your theme to your unique needs. 
 * With its intuitive interface and robust functionality, ThemeTweaker is the perfect solution for theme developers and designers looking to take their WordPress themes to the next level.
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