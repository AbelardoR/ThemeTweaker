<?php 

/**
 * settings_section 'hero'    :[]
 * settings_section 'services':[]
 * settings_section 'about_us':[]
 * settings_section 'news'    :[]
 * settings_section 'video'   :[]
 * settings_section 'staff'   :[]
 * settings_section 'why_us'  :[]
 * settings_section 'gallery' :[]
 * settings_section 'supplies':[] 
 * 
 */

$prefix = 'theme_home_section';
$elements = array();

$sections = [
    'hero'     => __('Hero', THEME_DOMAIN),
    'about_us' => __('about_us', THEME_DOMAIN),
    'services' => __('services', THEME_DOMAIN),
    'sites'    => __('sites', THEME_DOMAIN),
    'projects'    => __('projects', THEME_DOMAIN),
  
];
$sectionKeys = array_keys($sections);

/**
 * FOR ALL SECTIONS
 */
$elements['title_all'] = array_map(function($element) use ($prefix) {
    $field = new FieldGenerator($element,'Title',$prefix);
    $field->attrs['name'] = 'title';
    $field->attrs['label'] = __('Title of ', THEME_DOMAIN). $element;
    $field->attrs['desc'] =  __('Title of ', THEME_DOMAIN). $element;
    $field->attrs['class'] = 'take-3';
    return $field->defaultField();
}, $sectionKeys);
$elements['subtitle_all'] = array_map(function($element) use ($prefix) {
    $field = new FieldGenerator($element,'Subtitle',$prefix);
    $field->attrs['name'] = 'subtitle';
    $field->attrs['label'] =  __('Subtitle of ', THEME_DOMAIN). $element;
    $field->attrs['desc'] =  __('Subtitle of ', THEME_DOMAIN). $element;
    $field->attrs['class'] = 'take-3';
    return $field->defaultField();
}, $sectionKeys);

/**
 * SECTION HERO / HEADER
 */

$hero_gallery = new FieldGenerator('hero','Gallery',$prefix);
$hero_gallery->attrs['name'] = 'gallery';
$hero_gallery->attrs['label'] = __('Gallery of section');
$hero_gallery->attrs['class'] = 'take-6';
$elements['hero_gallery'] = [$hero_gallery->galleryField()];

/**
 * SECTION ABOUT US
 */

$about_text = new FieldGenerator('about_us','Text',$prefix);
$about_text->attrs['name'] = 'Text';
$about_text->attrs['label'] = __('Text of section');
$elements['about_text'] = [$about_text->textField()];

$about_image = new FieldGenerator('about_us','Image',$prefix);
$about_image->attrs['name'] = 'image';
$about_image->attrs['label'] = __('Image of section');
$elements['about_image'] = [$about_image->mediaField()];

$about_gallery = new FieldGenerator('about_us','Gallery',$prefix);
$about_gallery->attrs['name'] = 'gallery';
$about_gallery->attrs['label'] = __('Gallery of section');
$elements['about_gallery'] = [$about_gallery->galleryField()];



/**
 * SECTION SERVICES
 */
$services = [
    'service 1',
    'service 2',
    'service 3',
];
$servicesOut=[];
$container = $prefix."_services";
foreach ($services as $key => $service) {
    $loop = $key +1;
    $service_text = new FieldGenerator('services','Title_'.$loop, $prefix);
    $service_text->attrs['name'] = 'Title_'.$loop;
    $service_text->attrs['label'] = __('Title of ', THEME_DOMAIN).$service;
    $service_text->attrs['class'] = "joined $container service-$loop";
    $servicesOut[] = $service_text->defaultField();

    $service_text = new FieldGenerator('services','Text_'.$loop, $prefix);
    $service_text->attrs['name'] = 'Text_'.$loop;
    $service_text->attrs['label'] = __('Text of ', THEME_DOMAIN).$service;
    $service_text->attrs['class'] = "joined $container service-$loop";
    $servicesOut[] = $service_text->textField();

    $service_icon = new FieldGenerator('services','Icon_'.$loop, $prefix);
    $service_icon->attrs['name'] = 'Icon_'.$loop;
    $service_icon->attrs['label'] = __('Icon of ', THEME_DOMAIN).$service;
    $service_icon->attrs['class'] = "joined $container service-$loop";
    $servicesOut[] = $service_icon->mediaField();
}
$service_counter = new FieldGenerator('services','counter', $prefix);
$service_counter->attrs['name'] = 'service_counter';
$service_counter->attrs['label'] = 'service_counter';
$service_counter->attrs['class'] = "$container take-3 hidden";
$service_counter->attrs['default'] = count($services);
$servicesOut[] = $service_counter->defaultField();

$elements['services'] = $servicesOut;

$diagrams = [
    'diagram 1',
    'diagram 2',
    'diagram 3',
    'diagram 4',
    'diagram 5',
    'diagram 6',
    'diagram center',
];
$diagramsOut=[];
foreach ($diagrams as $key => $diagram) {
    $loop = $key +1;
    $diagram_text = new FieldGenerator('services','Diagram_title_'.$loop, $prefix);
    $diagram_text->attrs['name'] = 'Diagram_title_'.$loop;
    $diagram_text->attrs['label'] = __('Title of ', THEME_DOMAIN).$diagram;
    $diagram_text->attrs['class'] = 'joined take-1 diagram-'.$loop;
    $diagramsOut[] = $diagram_text->defaultField();
    $diagram_icon = new FieldGenerator('services','Diagram_icon_'.$loop, $prefix);
    $diagram_icon->attrs['name'] = 'Diagram_icon_'.$loop;
    $diagram_icon->attrs['label'] = 'Icon of '.$diagram;
    $diagram_icon->attrs['class'] = 'joined take-2 diagram-'.$loop;
    $diagramsOut[] = $diagram_icon->mediaField();
}
$diagram_count = new FieldGenerator('services','diagram_count', $prefix);
$diagram_count->attrs['name'] = 'diagram_count';
$diagram_count->attrs['label'] = 'diagram_count';
$diagram_count->attrs['class'] = "diagram_count take-3 hidden";
$diagram_count->attrs['default'] = (count($diagrams)) - 1;
$diagramsOut[] = $diagram_count->defaultField();

$elements['service_diagrams'] = $diagramsOut;

/**
 * SECTION AVAILABLE SITES
 */

$available_sites = [
    __('Custom Development', THEME_DOMAIN),
    __('Template Development', THEME_DOMAIN),
];
$available_sites_out=[];      
foreach ($available_sites as $key => $av_site) {
    $loop = $key +1;
    $sites_icon = new FieldGenerator('sites', "Available_site_icon_$loop", $prefix);
    $sites_icon->attrs['name'] = "Available_site_icon_$loop";
    $sites_icon->attrs['label'] = __('Icon of ', THEME_DOMAIN).$av_site;
    $sites_icon->attrs['class'] = 'take-3 joined site-type-'.$loop;
    $available_sites_out[] = $sites_icon->mediaField(); 

    $sites_title = new FieldGenerator('sites', "Available_site_title_$loop", $prefix);
    $sites_title->attrs['name'] = "Available_site_title_$loop";
    $sites_title->attrs['label'] = __('Title of ', THEME_DOMAIN).$av_site;
    $sites_title->attrs['class'] = 'joined take-3 site-type-'.$loop;
    $sites_title->attrs['default'] = $av_site;
    $available_sites_out[] = $sites_title->defaultField(); 

    $sites_text = new FieldGenerator('sites', "Available_site_text_$loop", $prefix);
    $sites_text->attrs['name'] = "Available_site_text_$loop";
    $sites_text->attrs['label'] = __('Text of ', THEME_DOMAIN).$av_site;
    $sites_text->attrs['class'] = 'joined take-3 site-type-'.$loop;
    $available_sites_out[] = $sites_text->textField(); 

    $sites_image = new FieldGenerator('sites', "Available_site_image_$loop", $prefix);
    $sites_image->attrs['name'] = "Available_site_image_$loop";
    $sites_image->attrs['label'] = __('Image of ', THEME_DOMAIN).$av_site;
    $sites_image->attrs['class'] = 'take-3 joined site-type-'.$loop;
    $available_sites_out[] = $sites_image->mediaField(); 

}
$sites_count = new FieldGenerator('sites', "Available_site_count", $prefix);
$sites_count->attrs['name'] = "Available_site_count";
$sites_count->attrs['label'] = "Available_site_count";
$sites_count->attrs['class'] = 'Available_site_count take-3 hidden';
$sites_count->attrs['default'] = count($available_sites);
$available_sites_out[] = $sites_count->defaultField(); 

$elements['available_sites'] = $available_sites_out;
    
/**
 * SECTION PROJECTS
 */

$projects_select = new FieldGenerator('projects','Catergory_select',$prefix);
$projects_select->attrs['name'] = 'Catergory_select';
$projects_select->attrs['label'] = __( 'Select a post category of section', THEME_DOMAIN);
$projects_select->attrs['options'] = array_column(get_categories(), 'name', 'term_id');
$elements['projects_select'] = [$projects_select->selectField()];

$projects_button = new FieldGenerator('projects','Button',$prefix);
$projects_button->attrs['name'] = 'button';
$projects_button->attrs['label'] = __('Button of section', THEME_DOMAIN);
$elements['projects_button'] = [$projects_button->buttonField()];

return ['sections' => $sections, 'elements' => $elements, 'prefix' => $prefix];