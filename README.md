<div align="center" id="top"> 
  &#xa0;

  <!-- <a href="https://themetweaker.netlify.app">Demo</a> -->
</div>

<h1 align="center">ThemeTweaker</h1>

<p align="center">
  <img alt="Github top language" src="https://img.shields.io/github/languages/top/{{AbelardoR}}/themetweaker?color=4535C1">

  <img alt="Github language count" src="https://img.shields.io/github/languages/count/{{AbelardoR}}/themetweaker?color=4535C1">

  <img alt="Repository size" src="https://img.shields.io/github/repo-size/{{AbelardoR}}/themetweaker?color=4535C1">

  <img alt="License" src="https://img.shields.io/github/license/{{AbelardoR}}/themetweaker?color=4535C1">

  <!-- <img alt="Github issues" src="https://img.shields.io/github/issues/{{AbelardoR}}/themetweaker?color=56BEB8" /> -->

  <!-- <img alt="Github forks" src="https://img.shields.io/github/forks/{{AbelardoR}}/themetweaker?color=56BEB8" /> -->

  <!-- <img alt="Github stars" src="https://img.shields.io/github/stars/{{AbelardoR}}/themetweaker?color=56BEB8" /> -->
</p>

Status

<h4 align="center"> 
	🚧  ThemeTweaker 🚀 under construction and testing...  🚧
</h4> 

<hr>

<p align="center">
  <a href="#about">About</a> &#xa0; | &#xa0; 
  <a href="#features">Features</a> &#xa0; | &#xa0;
  <a href="#rocket-technologies">Technologies</a> &#xa0; | &#xa0;
  <a href="#white_check_mark-requirements">Requirements</a> &#xa0; | &#xa0;
  <a href="#checkered_flag-starting">Starting</a> &#xa0; | &#xa0;
  <a href="#memo-license">License</a> &#xa0; | &#xa0;
  <a href="https://github.com/{{AbelardoR}}" target="_blank">Author</a>
</p>

<br>

## About ##

ThemeTweaker is a powerful tool that allows you to create custom fields in page options, empowering you to create or customize WordPress themes with ease. Leveraging PHP and WordPress functions, ThemeTweaker provides a seamless way to tailor your theme to your unique needs. With its intuitive interface and robust functionality, ThemeTweaker is the perfect solution for theme developers and designers looking to take their WordPress themes to the next level.

### Note ### 
This project is still in development and is not yet ready for production use. This is not a plugin, it is an alternative to customise a theme.

## Features ##

- Create Custom Fields in Page Options: Allows users to create custom fields in page options, giving them more control over their WordPress theme.
- Leverages PHP and WordPress Functions: Utilizes the power of PHP and WordPress functions to provide a seamless and robust theme customization experience.
- Intuitive Interface: Features an easy-to-use interface that makes it simple for users to tailor their theme to their unique needs.
Robust Functionality: Offers a wide range of features and options to help users take their WordPress themes to the next level.
- Ideal for Theme Developers and Designers: Specifically designed to meet the needs of theme developers and designers, providing them with the tools they need to create custom WordPress themes with ease.

## Technologies ##

The following tools were used in this project:

- [PHP](https://www.php.net/)
- [WordPress](https://wordpress.org/)
- [GitHub](https://github.com/)
- [Visual Studio Code](https://code.visualstudio.com/)
- [Bootstrap](https://getbootstrap.com/)
- [JQuery](https://jquery.com/)


## Requirements ##

Before starting ! you need to have [GitHub](https://github.com/) and code editor.
Have a minimum knowledge of PHP and WP theme development.


## Installation ##

```bash
# Clone this project or download as zip
git clone https://github.com/{{AbelardoR}}/themetweaker

# Place within the theme
move source_dir_path destination_dir_path

# Access
cd destination_dir_path themetweaker

# Open the main file 
ThemeTweaker.php

# Change the path variable
$_dir_path to $your_destination_dir_path

# Include the ThemeTweaker class file in your WordPress theme. You can do this by adding the following code to your theme's functions.php file
require get_template_directory() . $your_destination_dir_path .'/ThemeTweaker/ThemeTweaker.php';
```

## Getting Started ##
### Step 1: Create a new instance of ThemeOptions_Settings_Page ###

To get started, you need to create a new instance of the ThemeOptions_Settings_Page class. This class is the core of ThemeTweaker, and it's responsible for setting up the theme options page.

Here's an example of how to create a new instance:
```php
$SettingsPage = new ThemeOptions_Settings_Page(
    [
        'id'    => 'ThemeSettings',
        'title' => __('Theme Settings', THEME_DOMAIN)
    ],
    $dataFilePath = '/inc/ThemeTweaker/theme-options/src/settings.php'
);
```
In this example, we're creating a new instance of `ThemeOptions_Settings_Page` with an ID of `ThemeSettings` and a title of `Theme Settings`. The second argument is the path to the data file that contains the settings and fields for the theme options page.
### Step 2: Define sections and fields ###
To add sections and fields to your theme options page, you need to define them in the data file specified in the previous step. The data file should return an array with the following structure:
```php
return [
    'sections' => [
        'section_id' => 'Section Title',
        // ...
    ],
    'elements' => [
        [
            'id' => 'field_id',
            'label' => 'Field Label',
            'type' => 'text', // or checkbox, select, etc.
            'section' => 'section_id',
            // ...
        ],
        // ...
    ],
    'prefix' => 'options_page_id'
];
```
In this example, we're defining a section with an ID of `section_id` and a title of `Section Title`. We're also defining a field with an ID of `field_id`, a label of `Field Label`, and a type of `text`. The `section` key specifies the section where the field should be displayed.

#### Adding Sections and Elements to ThemeTweaker ####
To add sections and elements to ThemeTweaker, follow these steps:

1. Create a new file `theme_options` or any name in the directory of your theme.
2. Define prefix and empty array of elements
```php 
$prefix = 'theme_home_section';
$elements = array();
```
3. Add Sections the following code to the file:
Define your section keys in the $sections array. For example:
```php
$sections = [
    'header' => __('Header', THEME_DOMAIN),
    'footer' => __('Footer', THEME_DOMAIN),
    // Add new sections here
    'hero' => __('Hero', THEME_DOMAIN),
    'services' => __('Services', THEME_DOMAIN),
    // ...
];
```
Update the `$sectionKeys` array by getting the keys of the `$sections` array:
```php
$sectionKeys = array_keys($sections);
```
4. Add Elements the following code to the file:
To add elements to all sections, use the array_map function to generate fields for each section. For example, to add a "Title" element to all sections:
```php
$elements['title_all'] = array_map(function($element) use ($prefix) {
    $field = new FieldGenerator($element, 'Title', $prefix);
    $field->attrs['name'] = 'Title';
    $field->attrs['label'] = 'Title of ' . $element;
    $field->attrs['desc'] = 'Title of ' . $element;
    $field->attrs['attributes'] = ['required' => 'required'];
    return $field->defaultField();
}, $sectionKeys);
```
Replace Title with the desired element type (e.g., Subtitle, Textarea, Gallery, Image, Radio, Button, etc.).

To add elements only to one section, you can use the `section` parameter when creating a new FieldGenerator object. Here's an example:

```php
// section_id = hero
// field_id = Gallery
$hero_gallery = new FieldGenerator('hero','Gallery',$prefix);
$hero_gallery->attrs['name'] = 'gallery';
$hero_gallery->attrs['label'] = __('Gallery of section');
$hero_gallery->attrs['class'] = 'take-6';
$elements['hero_gallery'] = [$hero_gallery->galleryField()];
```

5. Returning the Config Array
Finally, return the config array with the sections, elements, and prefix:
```php
return ['sections' => $sections, 'elements' => $elements, 'prefix' => $prefix];
```
This will make the new sections and elements available in instance of ThemeOptions_Settings_Page in ThemeTweaker.

### Step 4: Use the Fields in Your Theme ###

To use the fields in your theme, you can use the `get_option` function to retrieve the field values. For example:
```php
// header.php
<header>
    <?php if (get_option('header_logo')) : ?>
        <img src="<?php echo get_option('header_logo'); ?>" alt="Logo">
    <?php endif; ?>
    <h1>
      <?php echo get_option('header_text'); ?>
    </h1>
</header>
```

#### What is FieldGenerator ? ####
What is it? The FieldGenerator class is a PHP class that helps create and customize form field properties with default values and optional attributes.

How does it work? When creating an instance of the FieldGenerator class, you need to pass three required parameters: sectionName, fieldId, and prefix. The class then initializes various properties related to a form field, such as label, placeholder, ID, description, type, attributes, class, options, and default values.

`Methods` The class provides several methods to generate different types of form fields, including:

- `defaultField()`: Returns an array with default values for a field.
- `checkField()`: Returns an array of properties for a checkbox field.
- `radioField()`: Returns an array of properties for a radio field.
- `textField()`: Returns an array of properties for a textarea field.
- `selectField()`: Returns an array of properties for a select or multiselect field.
- `redactorField()`: Returns an array of properties for a redactor field.
- `mediaField()`: Returns an array of properties for a media field.
- `galleryField()`: Returns an array of properties for a gallery field.
- `buttonField()`: Returns an array of properties for a button field.


## License ##

ThemeTweaker is licensed under the GNU General Public License (GPL).

## Contributing ##
If you'd like to contribute to ThemeTweaker, please fork the repository and submit a pull request.

Made with :heart: by <a href="https://github.com/{{AbelardoR}}" target="_blank">{{Abelardo R.}}</a>

&#xa0;

<a href="#top">Back to top</a>
