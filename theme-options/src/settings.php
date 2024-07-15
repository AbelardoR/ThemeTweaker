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

$prefix = 'theme_opts_section';
$elements = array();

$sections = [
    'header' => __('Header', THEME_DOMAIN),
    'footer' => __('Footer', THEME_DOMAIN),
    'information' => __('Site information', THEME_DOMAIN),
    'banners' => __('Site banners', THEME_DOMAIN),
];
$sectionKeys = array_keys($sections);
/**
 * FOR ALL SECTIONS
 */


/**
 * SECTION HEADER
 */

$header_social_menu = new FieldGenerator('header', 'show_social_menu', $prefix);
$header_social_menu->attrs['name'] = 'show_social_menu';
$header_social_menu->attrs['label'] = __('Display', THEME_DOMAIN)." ".__('Social menu', THEME_DOMAIN);
$header_social_menu->attrs['desc'] = __('checked to show element / uncheck to hide element', THEME_DOMAIN);
$header_social_menu->attrs['attributes'] = [
    'class' => 'toggle-switch'
];
$elements['header_social_menu'] = [$header_social_menu->checkField()];

/**
 * SECTION FOOTER
 */

$footer_social_menu = new FieldGenerator('footer', 'show_social_menu', $prefix);
$footer_social_menu->attrs['name'] = 'show_social_menu';
$footer_social_menu->attrs['label'] = __('Display', THEME_DOMAIN)." ".__('Social menu', THEME_DOMAIN);
$footer_social_menu->attrs['desc'] = __('checked to show element / uncheck to hide element', THEME_DOMAIN);
$footer_social_menu->attrs['attributes'] = [
    'class' => 'toggle-switch'
];
$elements['footer_social_menu'] = [$footer_social_menu->checkField()];

/**
 * SECTION SITE INFORMATION
 */
$site_info_address = new FieldGenerator('information', 'address', $prefix);
$site_info_address->attrs['name'] = 'address';
$site_info_address->attrs['label'] = __('Address', THEME_DOMAIN);
$site_info_address->attrs['class'] = 'joined site-info-main';
$site_info_address->attrs['default'] = "вул. 600-річчя, 17 (Компанія Ліана оф.202)";

$elements['site_info_address'] = [$site_info_address->defaultField()];

$site_info_phone = new FieldGenerator('information', 'phone', $prefix);
$site_info_phone->attrs['name'] = 'phone';
$site_info_phone->attrs['label'] = __('Phone', THEME_DOMAIN);
$site_info_phone->attrs['class'] = 'joined site-info-main';
$site_info_phone->attrs['desc'] = __('Always set the phone to international format without +.', THEME_DOMAIN);
$site_info_phone->attrs['default'] = "380970327080";

$elements['site_info_phone'] = [$site_info_phone->defaultField()];

$site_info_email = new FieldGenerator('information', 'email', $prefix);
$site_info_email->attrs['name'] = 'email';
$site_info_email->attrs['label'] = __('E-mail', THEME_DOMAIN);
$site_info_email->attrs['class'] = 'joined site-info-main';
$site_info_email->attrs['desc'] = __('Always set the email to international format without +.', THEME_DOMAIN);
$site_info_email->attrs['default'] = "sale@webliana.com";

$elements['site_info_email'] = [$site_info_email->defaultField()];

$site_info_working_title = new FieldGenerator('information', 'working_title', $prefix);
$site_info_working_title->attrs['name'] = 'working_title';
$site_info_working_title->attrs['label'] = __('Office working hours', THEME_DOMAIN);
$site_info_working_title->attrs['default'] = "Графік роботи офісу";
$site_info_working_title->attrs['class'] = 'joined work-hours';

$site_info_working_week = new FieldGenerator('information', 'working_week', $prefix);
$site_info_working_week->attrs['name'] = 'working_week';
$site_info_working_week->attrs['label'] = __('Working Hours', THEME_DOMAIN).': '.__('Monday - Friday', THEME_DOMAIN);
$site_info_working_week->attrs['default'] = "10:00 - 18:00";
$site_info_working_week->attrs['class'] = 'joined work-hours';

$site_info_working_weekend = new FieldGenerator('information', 'working_weekend', $prefix);
$site_info_working_weekend->attrs['name'] = 'working_weekend';
$site_info_working_weekend->attrs['label'] =  __('Working Hours', THEME_DOMAIN).': '.__('Saturday', THEME_DOMAIN);
$site_info_working_weekend->attrs['default'] = "10:00 - 18:00";
$site_info_working_weekend->attrs['class'] = 'joined work-hours';

$site_info_working_weekend_2 = new FieldGenerator('information', 'working_weekend_2', $prefix);
$site_info_working_weekend_2->attrs['name'] = 'working_weekend_2';
$site_info_working_weekend_2->attrs['label'] =  __('Working Hours', THEME_DOMAIN).': '.__('Sunday', THEME_DOMAIN);
$site_info_working_weekend_2->attrs['default'] = "Вихідний";
$site_info_working_weekend_2->attrs['class'] = 'joined work-hours';

$elements['site_info_working_weekend'] = [
    $site_info_working_title->defaultField(),
    $site_info_working_week->defaultField(),
    $site_info_working_weekend->defaultField(),
    $site_info_working_weekend_2->defaultField()
];

$site_info_work_dep = new FieldGenerator('information', 'work_dep', $prefix);
$site_info_work_dep->attrs['name'] = 'work_dep';
$site_info_work_dep->attrs['label'] = __('Job Department', THEME_DOMAIN);
$site_info_work_dep->attrs['desc'] = __('Department of work used in contact sections', THEME_DOMAIN);
$site_info_work_dep->attrs['default'] = "Відділ продажу";
$site_info_work_dep->attrs['class'] = 'joined office-working-hours';
$site_info_contact_image = new FieldGenerator('information', 'contact_image', $prefix);
$site_info_contact_image->attrs['name'] = 'contact_image';
$site_info_contact_image->attrs['label'] = __('Image Contact', THEME_DOMAIN);
$site_info_contact_image->attrs['class'] = 'joined office-working-hours';
$site_info_contact_image->attrs['desc'] = __('Image contact before footer', THEME_DOMAIN);

$elements['site_info_work_dep'] = [$site_info_work_dep->defaultField(), $site_info_contact_image->mediaField()];

$site_info_buttons = [
    'email'    => ['link' => "mailto:sale@webliana.com", 'title' => "Напишіть на е-mail" ],
    'phone'    => ['link' => "tel:+380970327080", 'title' => "Зателефонувати" ],
    'viber'    => ['link' => "viber://chat/?number=%2B380970327080&amp;draft=Вітаю!", 'title' => "Написати" ],
    'telegram' => ['link' => "t.me/+380970327080", 'title' => "Написати" ],
];
$site_info_buttonsOut = [];
$loop = 1;
foreach ($site_info_buttons as $_type => $_button) {
    $site_info_button = new FieldGenerator('information', "Button_group_$loop", $prefix);
    $site_info_button->attrs['name'] =  "Button_group_$loop";
    $site_info_button->attrs['label'] =  __('Contact button', THEME_DOMAIN)." ".__($_type);
    $site_info_button->attrs['default'] = $_button['title'];
    $site_info_button->attrs['class'] = 'joined button_group';
    $site_info_buttonsOut[] = $site_info_button->buttonField($_button['link'], 'text');
    $loop++;
}
$site_info_button_count = new FieldGenerator('information', "Button_count", $prefix);
$site_info_button_count->attrs['name'] =  "Button_count";
$site_info_button_count->attrs['label'] =  __('Button count', THEME_DOMAIN);
$site_info_button_count->attrs['default'] = count($site_info_buttons);
$site_info_button_count->attrs['class'] = 'hidden joined button_group';
$site_info_buttonsOut[] = $site_info_button_count->defaultField();

$elements['site_info_buttons'] = $site_info_buttonsOut;

/**
 * SECTION BANNERS
 */
// banner counters
$banner_counters_out = [];
for ($i=1; $i < 4; $i++) { 
    $banner_counters_title = new FieldGenerator('banners', 'counters_title_'.$i, $prefix);
    $banner_counters_title->attrs['name'] = 'counters_title_'.$i;
    $banner_counters_title->attrs['label'] = __('Title of counter', THEME_DOMAIN).' '.$i;
    $banner_counters_title->attrs['class'] = "joined banner-counter";
    $banner_counters_out[] = $banner_counters_title->defaultField();
    $banner_counters_number = new FieldGenerator('banners', 'counters_number_'.$i, $prefix);
    $banner_counters_number->attrs['name'] = 'counters_number_'.$i;
    $banner_counters_number->attrs['label'] = __('Number of counter', THEME_DOMAIN).' '.$i;
    $banner_counters_number->attrs['placeholder'] = 100;
    $banner_counters_number->attrs['class'] = "joined banner-counter";
    $banner_counters_number->attrs['attributes'] = [ 'min' => 0, 'max' => 10000 ];
    $banner_counters_number->type = 'number';
    $banner_counters_out[] = $banner_counters_number->defaultField();

}

$banner_counters_quant = new FieldGenerator('banners', 'counters_quant', $prefix);
$banner_counters_quant->attrs['name'] = 'counters_quant';
$banner_counters_quant->attrs['label'] = 'counters_quant';
$banner_counters_quant->attrs['class'] = "joined banner-counter";
$banner_counters_quant->attrs['default'] = count($banner_counters_out)/2;
$banner_counters_out[] = $banner_counters_quant->defaultField();

$elements['banner_counters'] = $banner_counters_out;

// banner corporative email
$banner_corp_email_title = new FieldGenerator('banners', 'corp_email_title', $prefix);
$banner_corp_email_title->attrs['name'] = 'corp_email_title';
$banner_corp_email_title->attrs['label'] = __('Crporative email title', THEME_DOMAIN);
$banner_corp_email_title->attrs['class'] = "joined banner-corporative-email";

$banner_corp_email_subtitle = new FieldGenerator('banners', 'corp_email_subtitle', $prefix);
$banner_corp_email_subtitle->attrs['name'] = 'corp_email_subtitle';
$banner_corp_email_subtitle->attrs['label'] = __('Corporative email subtitle', THEME_DOMAIN);
$banner_corp_email_subtitle->attrs['class'] = "joined banner-corporative-email";

$banner_corp_email_button_primary = new FieldGenerator('banners','Button_primary',$prefix);
$banner_corp_email_button_primary->attrs['name'] = 'button_primary';
$banner_corp_email_button_primary->attrs['label'] = __('Button of section', THEME_DOMAIN).' '.__('primary', THEME_DOMAIN);
$banner_corp_email_button_primary->attrs['class'] = "joined banner-corporative-email";

$banner_corp_email_button_secondary = new FieldGenerator('banners','Button_secondary',$prefix);
$banner_corp_email_button_secondary->attrs['name'] = 'button_secondary';
$banner_corp_email_button_secondary->attrs['label'] = __('Button of section', THEME_DOMAIN).' '.__('secondary', THEME_DOMAIN);
$banner_corp_email_button_secondary->attrs['class'] = "joined banner-corporative-email";

$elements['banner_corp_email'] = [
    $banner_corp_email_title->defaultField(),
    $banner_corp_email_subtitle->defaultField(),
    $banner_corp_email_button_primary->buttonField(),
    $banner_corp_email_button_secondary->buttonField()
];

return ['sections' => $sections, 'elements' => $elements, 'prefix' => $prefix];