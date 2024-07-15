<?php

$prefix = 'theme_meta_box';
$elements = array();

$sections = [
    'meta1' => __('meta fieldset 1', THEME_DOMAIN),
];
$sectionKeys = array_keys($sections);


/**
 * METABOX "META1" 
 */

$meta1 = new FieldGenerator('meta1','data_meta',$prefix);
$meta1->attrs['name'] = 'Data Meta';
$meta1->attrs['label'] = 'Data Meta of hero';
$meta1->attrs['desc'] = 'Data Meta of hero';
$meta1->attrs['class'] = 'Data Meta-css';
$elements['meta1'] = [$meta1->defaultField()];


return ['sections' => $sections, 'elements' => $elements, 'prefix' => $prefix];