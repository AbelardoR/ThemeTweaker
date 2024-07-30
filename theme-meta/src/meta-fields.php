<?php

$prefix = 'theme_meta_box';
$elements = array();

$sections = [
    'meta1' => __('theme custom meta', THEME_DOMAIN),
];
$sectionKeys = array_keys($sections);


/**
 * METABOX "META1" 
 */

$meta1 = new FieldGenerator('meta1','display_featured_img',$prefix);
$meta1->attrs['name'] = 'display_featured_img';
$meta1->attrs['label'] = __('Display featured img', THEME_DOMAIN);
$meta1->attrs['class'] = 'meta-css';
$meta1->attrs['attributes'] = ['class' => "toggle-switch"];
$elements['meta1'] = [$meta1->checkField()];


return ['sections' => $sections, 'elements' => $elements, 'prefix' => $prefix];