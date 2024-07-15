<?php
/**
 * ON ACTIVATE THEME INSTALLING MENUS 
 */

 function create_nav_menu_item($menu_id, $title, $url ) {
   
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'   =>  $title,
        'menu-item-url'     =>  home_url( '/' . $url ),
        'menu-item-status'  =>  'publish'
    ) );
   
}

function create_nav_menu( $menu_name, $menu_items_array, $location_target ) {
   
    $_menu = $menu_name;
    wp_create_nav_menu( $_menu );
    $menu_obj = get_term_by( 'name', $_menu, 'nav_menu' );
    $menu_id = $menu_obj->term_id;

    foreach( $menu_items_array as $menu_item_name => $menu_item_location ){
        create_nav_menu_item( $menu_id, $menu_item_name, $menu_item_location );
    }

    $locations_arr = get_theme_mod( 'nav_menu_locations' );
    $locations_arr[$location_target] = $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations_arr );
       
    update_option( 'menu_check', true );
   
}


/**
 * Runs when user switches to your custom theme
 *
 */
function setup_theme_navigation() {

    $run_menu_maker_once = get_option('menu_check');
    $locations =  get_registered_nav_menus();
    
    if (! $run_menu_maker_once ){
        $make_menus = [];
       
        /**
         * Setup Navigation for : Header Menu
         */    
        $header_menu_items = array(
            'Home'       => 'home',
            'Who we are' => 'who we are',
            'Services'   => 'services',
            'Portfolio'  => 'portfolio',
            'Prices'     => 'prices',
            'Contacts'   => 'contacts',
        );
        $make_menus[] = $header_menu_items;
        
        /**
         * Setup Navigation for : Footer Menu 
         */
        $footer_menu_items = array(
            'Home'       => 'home',
            'Services'   => 'services',
            'Contacts'   => 'contacts',
        );
        $make_menus[] = $footer_menu_items;

        /**
         * Setup Navigation for : Social Menu 
         */
        $social_menu_items = array(
            'Facebook'   => 'facebook',
            'Instagram'  => 'instagram',
            'Twitter'    => 'twitter',
            'Youtube'    => 'Youtube',
        );

        $make_menus[] = $social_menu_items;
        $loop = 0;
        foreach ( $locations as $location => $location_name ) {
            dump($make_menus[$loop]);
            create_nav_menu( $location_name, $make_menus[$loop], $location );
            $loop++;
        }
    }
}

add_action( 'after_setup_theme', 'setup_theme_navigation');