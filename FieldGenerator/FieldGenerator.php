<?php

/**
 * The `FieldGenerator` class is designed to create
 *  and customize form field properties with default values and optional attributes.
 */

class FieldGenerator {
    private $prefix;
    private $sectionName;
    public $type;
    public $attrs = [];
    protected $fieldId;

    /**
     * The function is a PHP constructor that initializes properties related to a form field with
     * default values and allows for customization through an array of attributes.
     * 
     * @param string $sectionName The `sectionName` parameter in the constructor function is used to store the
     * name of the section to which the field belongs. It is passed as an argument when creating an
     * instance of the class.
     * @param string $field_id The `field_id` parameter in the constructor function is used to store the ID of
     * the field. This ID is typically used to uniquely identify the field within the form or section
     * where it is being used. It can be used for various purposes such as styling, scripting, or
     * backend processing.
     * @param string $prefix The `prefix` parameter in the constructor function is used to store a prefix value
     * that can be used in the class for various purposes, such as forming unique identifiers or keys
     * for fields or elements within the class. It is a string value that is passed to the constructor
     * when creating an instance of the class
     * @param array $attrs The `attrs` parameter in the constructor function is an array that contains various
     * attributes for the field. These attributes include:
     */
    public function __construct($sectionName, $field_id, $prefix, $attrs = []) {

        $this->sectionName = $sectionName;
        $this->fieldId = $field_id;
        $this->prefix = $prefix;
        $this->type = 'text';
        
        /* the `->attrs` property is being initialized as an 
           associative array with default values for various attributes related to a form field. 
        */
        $this->attrs = [
            'placeholder' => __('type your text', THEME_DOMAIN),
            'desc'    => '',
            'sep'    => false,
            'options' => array(),
            'name' => '',
            'label' => __('label of field', THEME_DOMAIN),
            'class' => '',
            'attributes' => array(),
            'default' => ''
        ];
        $this->attrs = array_merge($this->attrs, $attrs);

    }

    /**
     * The `slug` function takes an array of strings, converts them to lowercase, and then joins them
     * with hyphens to create a slug.
     * 
     * @param array $textArray The `slug` function takes an array as input and returns a string that is a
     * concatenation of the array elements converted to lowercase and separated by hyphens.
     * 
     * @return string  The `slug` function takes an array as input, converts each element to
     * lowercase, and then joins them together with a hyphen. The resulting string is returned.
     */
    protected function slug($textArray=[]): string {
        $slugFrom = array_map('strtolower', array_filter($textArray));
        return implode("_", $slugFrom);
    }

    protected function fieldset($arrayMerger=[]) : array {
        $base = array(
            'section' => $this->slug([$this->prefix, $this->sectionName]),
            'label'   => __('Label of field', THEME_DOMAIN),
            'placeholder' => __('Type a content of field', THEME_DOMAIN),
            'id'      => $this->slug([$this->prefix , $this->sectionName , $this->fieldId]),
            'desc'    => $this->attrs['desc'],
            'type'    => $this->type,
            'attrs'   => $this->attrs['attributes'],
            'class'   => $this->attrs['class'],
            'default' => $this->attrs['default'],
            'sep'     => $this->attrs['sep']
        );
        $fieldset = array_merge($base, $arrayMerger);
        return $fieldset;
    }

    /**
     * The function `defaultField` returns an array with default values for a field in PHP.
     * 
     * @return array An 'array' is being returned with various key-value pairs for a redactor field, including
     * section, label, placeholder, id, desc, type, attrs, class, options, and default.
     */
    public function defaultField() {
        $field = $this->fieldset();
        $field['label'] = $this->attrs['label']; 
        $field['placeholder'] = $this->attrs['placeholder'];
        return $field;
    }

    /**
     * The function `checkField` returns an array of properties for a checkbox field in PHP.
     * 
     * @return array An 'array' is being returned with various key-value pairs for a redactor field, including
     * section, label, placeholder, id, desc, type, attrs, class, options, and default.
     */
    public function checkField() {
        $this->type = 'checkbox';
        $field = $this->fieldset();
        $field['label'] = $this->attrs['label'] ?? __('Label of checkbox', THEME_DOMAIN);

        return $field;
    }

   /**
    * The function `radioField` generates an array of properties for a radio field, with an option for
    * multiple selection.
    * 
    * @param bool $multiple The `multiple` parameter in the `radioField` function is a boolean parameter that
    * determines whether the radio field should allow multiple selections or not. If `multiple` is set
    * to `true`, the function will set the field type to 'multiselect' and add the 'multiple' attribute
    * to the
    * 
    * @return array An 'array' is being returned with various key-value pairs for a redactor field, including
    * section, label, placeholder, id, desc, type, attrs, class, options, and default.
    */
    public function radioField($multiple=false) {
        $this->type = 'radio';
        if ($multiple) {
            $this->type = 'multiselect';
            $this->attrs['options'] = array_filter($this->attrs['options'], function($key) {
                return $key !== '';
            }, ARRAY_FILTER_USE_KEY);
            $this->attrs['attributes']['multiple'] = 'multiple';
        }
        $field = $this->fieldset();
        $field['label'] = $this->attrs['label'] ??__('Select an option', THEME_DOMAIN);
        $field['options'] = $this->attrs['options'];

        return $field;
    }

    /**
     * The function `textField` in PHP returns an array with various properties for a textarea field.
     * @return array An 'array' is being returned with various key-value pairs for a redactor field, including
     * section, label, placeholder, id, desc, type, attrs, class, options, and default.
     */
    public function textField() {
        $this->type = 'textarea';
        $field = $this->fieldset();
        $field['label'] = $this->attrs['label'] ??__('Section content', THEME_DOMAIN); 
        $field['placeholder'] = $this->attrs['placeholder'] ?? __('Type the text of your section', THEME_DOMAIN); 
        return $field;
    }

    /**
     * The function `selectField` sets up a select or multiselect field with specified attributes and
     * options.
     * 
     * @param bool $multiple The `multiple` parameter in the `selectField` function is a boolean parameter
     * that determines whether the field should be a regular select field or a multiselect field. If
     * `multiple` is set to `true`, the field type will be set to 'multiselect', and additional
     * attributes will be added
     * @return array An 'array' is being returned with various key-value pairs for a redactor field, including
     * section, label, placeholder, id, desc, type, attrs, class, options, and default.
     */
    public function selectField($multiple=false) {
        $this->type = 'select';
        if ($multiple) {
            $this->type = 'multiselect';
            $this->attrs['options'] = array_filter($this->attrs['options'], function($key) {
                return $key !== '';
            }, ARRAY_FILTER_USE_KEY);
            $this->attrs['attributes']['multiple'] = 'multiple';
        }
        $field = $this->fieldset();
        $field['label'] = $this->attrs['label'] ??__('Select an option', THEME_DOMAIN);
        $field['options'] = $this->attrs['options'];

        return $field;
    }

    /**
     * The function `redactorField` returns an array of properties for a redactor field in PHP.
     * 
     * @return array An 'array' is being returned with various key-value pairs for a redactor field, including
     * section, label, placeholder, id, desc, type, attrs, class, options, and default.
     */
    public function redactorField() {
        $this->type = 'redactor';
        $field = $this->fieldset();
        $field['label'] = $this->attrs['label'] ?? __('Redactor for section', THEME_DOMAIN);
        $field['placeholder'] = $this->attrs['placeholder'] ?? __('Type the text of your section', THEME_DOMAIN);

        return $field;
    }

    /**
     * The function `mediaField` returns an array of properties for a media field, with a default
     * return value of 'id'.
     * 
     * @param string $retVal The `retVal` parameter in the `mediaField` function is used to determine what
     * value should be returned when the function is called. It is a parameter that allows the caller
     * of the function to specify what specific field or property of the media field should be
     * returned.
     * 
     * @return array An 'array' is being returned with various key-value pairs for a redactor field, including
     * section, label, placeholder, id, desc, type, attrs, class, options, and default.
     */
    public function mediaField($retVal="id") {
        $this->type = 'media';
        $field = $this->fieldset();
        $field['label'] = $this->attrs['label'] ?? __('Image of section', THEME_DOMAIN);
        $field['returnvalue'] = $retVal;

        return $field;
    }

    /**
     * The function `galleryField` returns an array with various properties related to a gallery field,
     * with the option to specify the return value.
     * 
     * @param string $retVal The `retVal` parameter in the `galleryField` function is used to determine the
     * value that will be returned by the function. It specifies the key of the array element that
     * should be returned as the output of the function. In the provided code snippet, the default
     * value for `retVal`
     * 
     * @return array An 'array' is being returned with various key-value pairs for a redactor field, including
     * section, label, placeholder, id, desc, type, attrs, class, options, and default.
     */
    public function galleryField($retVal='id') {
        $this->type = 'gallery';
        $field = $this->fieldset();
        $field['label'] = $this->attrs['label'] ?? __('Gallery of section', THEME_DOMAIN);
        $field['placeholder'] = $this->attrs['placeholder'] ?? __('Gallery of section', THEME_DOMAIN);
        $field['returnvalue'] = $retVal;

        return $field;
    }

    /**
     * The function `buttonField` generates an array with various properties for a button field, with
     * default values and optional URL parameter.
     * 
     * @param string url The `url` parameter in the `buttonField` function is a default value set to
     * `'http://'`. This parameter allows you to specify a URL that the button will link to when
     * clicked. If no URL is provided when calling the `buttonField` function, it will default to `'http://'
     * 
     * @return array An 'array' is being returned with various key-value pairs for a redactor field, including
     * section, label, placeholder, id, desc, type, attrs, class, options, and default.
     */
    public function buttonField($url = '', $url_type='url') {
        $this->type = 'button';
        $field = $this->fieldset();
        $field['label'] = $this->attrs['label'] ?? __('Button for section', THEME_DOMAIN);
        $field['placeholder'] = $this->attrs['placeholder'] ?? __('Button title', THEME_DOMAIN);
        $field['options'] = $this->attrs['options'];
        $field['url'] = $url;
        $field['url_type'] = $url_type;

        return $field;
    }

}
