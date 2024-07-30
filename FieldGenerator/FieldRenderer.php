<?php

/**
 * The `FieldRenderer` class is designed to render different types of form fields
 * based on specified field types and attributes. 
 */
class FieldRenderer {

    private $field;
    private $value;
    private $attributes;
    public $assetsPath = '/inc/ThemeTweaker/FieldGenerator/assets';

    /**
     * The constructor initializes the object with a field value and attributes based on the provided
     * field.
     * 
     * The `__construct` function in the code snippet is a constructor method for a class.
     * @param object|array takes a parameter `$_field` and initializes the class properties
     * using the methods `getFieldValue()` and `getFieldAttributes()`.
     * @param mixed $_value The parameter represents the value associated with that field that has been saved.
     */
    public function __construct($_field, $_value) {
        $this->field = $_field;
        $this->value = $this->getFieldValue($_value);
        $this->attributes =  $this->getFieldAttributes();
        
		add_action('admin_footer', array($this, 'fields_styles_scripts'));
    }

    /**
     * The function renders different types of form fields based on the specified type in a PHP class.
     * 
     * @return string The `render()` function returns an HTML string based on the type of field specified in
     * the `->field['type']` property. The function checks the type of field and calls the
     * corresponding method to generate the HTML for that field type. After generating the HTML for the
     * field, it appends the description and divider HTML before returning the final HTML string.
     */
    public function render() {
        $html = "";
        switch ($this->field['type']) {
            case 'media':
                $html.= $this->media();
                break;

            case 'gallery':
                $html.= $this->gallery();
                break;

            case 'select':
            case 'multiselect':
                $html.= $this->select();
                break;

            case 'checkbox':
                $html.= $this->checkbox();
                break;

            case 'radio':
                $html.= $this->radio();
                break;

            case 'button':
                $html.= $this->button();
                break;

            case 'redactor':
            case 'wysiwyg':
                $html.= $this->redactor();
                break;

            case 'textarea':
                $html.= $this->text();
                break;

            default:
               $html.= $this->default();
        }
        $html.= $this->description();
        $html.= $this->divider();

        return $html;
    }

    /**
     * The `media` function generates HTML input elements for handling media files with options to
     * select and clear the media.
     * 
     * @return string The `media()` function is returning an HTML input element along with some buttons for
     * selecting and clearing media. The input element includes a text field for entering a value, an
     * image preview, and buttons for opening a media selection modal and removing the selected media.
     */
    public function media() {
        $mediaHtml = "";
        $imgbox = sprintf(
            '<div class="img-box" data-img="%1$s">
                <img id="image-preview-%2$s" src="%3$s">
            </div>',
            $this->value,
            $this->field['id'],
            $this->getAttachment($this->value)
        );
        $mediaHtml .= '<input class="not-focusable" id="%1$s" name="%1$s" type="text" value="%2$s" data-return="%3$s" %4$s>';
        $mediaHtml .= '<div class="image-single-preview"> %5$s';
        $mediaHtml .= '<input class="button open-media" data-target="%1$s" id="%1$s_open" type="button" value="%6$s" />';
        $mediaHtml .= '<input class="button remove-media" data-target="%1$s" id="%1$s_remove" type="button" value="%7$s" />';
        $mediaHtml .= '</div>';

        $inputHtml = sprintf(
            $mediaHtml,
            $this->field['id'],
            $this->value,
            $this->field['returnvalue'],
            $this->attributes,
            $imgbox,
            __('Select', THEME_DOMAIN),
            __('Clear', THEME_DOMAIN),
        );

        return $inputHtml;
    }

    /**
     * The function "gallery" generates HTML input elements for a gallery with image previews and an
     * upload button.
     * 
     * @return string The `gallery()` function is returning an HTML input element, a div element with image
     * previews, and an upload image button. The HTML elements are generated based on the values stored
     * in the object's properties and the images associated with those values.
     */
    public function gallery() {
        $galleryHtml = "";
        $imgbox = '';
        if ($this->value) {
            $images = explode(",", $this->value);
            foreach ($images as $url => $image) {
                $imgbox .= sprintf(
                    '<div class="img-box" data-img="%1$s">
								<img id="image-preview-%1$s" src="%2$s">
							</div>',
                    $image,
                    $this->getAttachment($image)
                );
            }
        }

        $galleryHtml .= '<input class="not-focusable" id="%1$s" name="%1$s" type="text" value="%2$s" data-return="%3$s" %4$s>';
        $galleryHtml .= '<div class="image-preview-wrapper" data-target="%1$s" id="preview_%1$s" data-render="%2$s"> %5$s';
        $galleryHtml .= '</div>';
        $galleryHtml .= '<input class="upload_image_button" data-target="%1$s" data-prev="preview_%1$s" data-multi="true"  type="button" class="button" value="%6$s" />';

        $inputHtml = sprintf(
            $galleryHtml,
            $this->field['id'],
            $this->value,
            $this->field['returnvalue'],
            $this->attributes,
            $imgbox,
            __('Choose your images', THEME_DOMAIN)
        );
        return $inputHtml;
    }

    /**
     * The function generates HTML for a select dropdown based on options provided.
     * 
     * @return string The `select()` function returns HTML code for a `<select>` dropdown input field based on
     * the options provided in the `->field['options']` array. If options are available, it
     * generates the `<option>` tags with values and text, and sets the selected attribute based on the
     * current value. If no options are available, it returns a message prompting to add options.
     */
    public function select() {
        $options = $this->field['options'];
        if (!empty($options)) {
            $optionsHtml = "";
            $firstKeyNull = array_key_first($options) === 0;
            foreach ($options as $optionValue => $optionText) {
                if (!is_string($optionValue) && $firstKeyNull) {
                    $optionValue = $optionText;
                }
                $optionsHtml .= sprintf(
                    '<option value="%1$s" %3$s>%2$s</option>',
                    $optionValue,
                    $optionText,
                    $this->getSelected($this->value, $optionValue)
                );
            }

            $inputHtml = sprintf(
                '<select name="%1$s" id="%1$s" %2$s>%3$s</select>',
                $this->field['id'],
                $this->attributes,
                $optionsHtml
            );
        } else {
            $inputHtml = '<span class="needed">' . __('please add some options', THEME_DOMAIN) . '<span>';
        }

        return $inputHtml;
    }

    /**
     * The function generates HTML code for a checkbox input element in PHP.
     * 
     * @return string|int The `checkbox()` function returns an HTML input element of type checkbox with the
     * specified attributes and value.
     */
    public function checkbox() {
        $inputHtml = sprintf(
            '<input name="%1$s" id="base_%1$s" type="hidden" value="0" />
            <input name="%1$s" id="%1$s" type="checkbox" value="1" %2$s %3$s />',
            $this->field['id'],
            $this->value ? 'checked' : '',
            $this->attributes,
        );

        return $inputHtml;
    }

    /**
     * The function `radio` generates HTML code for a radio button input field based on the provided
     * options.
     * 
     * @return string The `radio()` function returns HTML code for a set of radio buttons based on the options
     * provided in the field. The HTML code includes input elements for each radio button option along
     * with their corresponding labels.
     */
    public function radio() {
        
        $options = $this->field['options'];
        $optionsHtml = sprintf(
            '<input class="not-focusable" id="%1$s" name="%1$s" type="text" value="" >',
            $this->field['id'],
        );
        if (!empty($options)) {
            $i = 0;
            $firstKeyNull = array_key_first($options) === 0;
            foreach ($options as $optionValue => $optionText) {
                if (!is_string($optionValue) && $firstKeyNull) {
                    $optionValue = $optionText;
                }

                $optionsHtml.= sprintf(
                    '<p class="radio-box">
                        <input id="%1$s_%6$s" name="%1$s" type="radio" value="%2$s" %3$s %4$s><span>%5$s
                    </span></p>',
                    $this->field['id'],
                    $optionValue,
                    $this->attributes,
                    $this->getSelected($this->value, $optionValue, 'checked'),
                    $optionText,
                    $i
                );
                $i++;
            }

            $inputHtml = $optionsHtml;
        } else {
            $inputHtml = "";
        }

        return $inputHtml;
    }

    /**
     * The function generates HTML code for a button input field with a link input field in PHP.
     * 
     * @return string The `button()` function is returning an HTML input element wrapped in a `<div>` with the
     * class "button-wrapper". The input element has an id, name, class, type, placeholder, value, and
     * attributes based on the values of the `->field` and `->value` properties. The URL for
     * the input element is determined based on the value of the option stored in the
     */
    public function button() {
        if (!is_array($this->value)) {
            $this->value = [
                'text' => $this->value,
                'url' => $this->field['url'],
            ];
        }
        $btnHtml = '<div class="button-wrapper" id="btn_box_%1$s">';
        $btnHtml .= '<input id="%1$s" name="%1$s[]" class="has-title" type="text" value="%2$s" placeholder="%3$s" %4$s/>';
        $btnHtml .= '<input id="%1$s_link" name="%1$s[]" class="has-link" value="%6$s" type="%7$s" placeholder="https://" />';
        $btnHtml .= '</div>';
        $inputHtml = sprintf(
            $btnHtml,
            $this->field['id'],
            current($this->value),
            $this->field['placeholder'],
            $this->attributes,
            $this->field['id'].'_link',
            end($this->value),
            $this->field['url_type'],
        );

        return $inputHtml;
    }

    /**
     * The redactor function generates an HTML editor with specified attributes and content.
     * 
     * @return string The `redactor()` function is returning an HTML string that includes a `<div>` element
     * with an id attribute set to "editor-{field_id}" and some content generated by the
     * `recreate_wp_editor()` method.
     */
    public function redactor() {
        $inputHtml = sprintf(
            '<div id="editor-%1$s" %3$s>%2$s</div>',
            $this->field['id'],
            $this->recreate_wp_editor($this->value, $this->field['id']),
            $this->attributes,
        );

        return $inputHtml;
    }

    /**
     * The function generates a textarea HTML element with specified attributes and values.
     * 
     * @return string The `text()` function returns an HTML `<textarea>` element with the specified attributes
     * and values.
     */
    public function text() {
        $inputHtml = sprintf(
            '<textarea name="%1$s" id="%1$s" placeholder="%2$s" %4$s>%3$s</textarea>',
            $this->field['id'],
            $this->field['placeholder'],
            $this->value,
            $this->attributes,
        );

        return $inputHtml;
    }

    /**
     * The function generates an HTML input element with specified attributes and values.
     * 
     * @return string The `default()` function is returning an HTML input element with attributes based on the
     * values of the properties of `field`.
     */
    public function default() {
        $inputHtml = sprintf(
            '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" %5$s/>',
            $this->field['id'],
            $this->field['type'],
            $this->field['placeholder'],
            esc_html($this->value),
            $this->attributes,
        );

        return $inputHtml;
    }

    /**
     * The function returns a description field if it is set in the field array, otherwise it returns
     * an empty string.
     * 
     * @return string HTML string containing the description of a field, wrapped in a span with the class
     * "desc-field", or an empty string if no description is provided.
     */
    public function description() {
        if (isset($this->field['desc']) && $this->field['desc'] != '') {
            $inputHtml = '<span class="desc-field">' . $this->field['desc'] . '</span>';
        } else {
            $inputHtml = "";
        }
        return $inputHtml;
    }

    /**
     * The function divider checks if a separator value is set and returns an HTML horizontal rule if
     * it is, otherwise it returns an empty string.
     * 
     * @return string HTML `<hr>` element is being returned if the condition is true. 
     * Otherwise, an empty string is being returned.
     */
    public function divider() {
        $inputHtml = '';
        if (isset($this->field['sep']) && $this->field['sep']) {
            $inputHtml .= '<hr>';
        } else {
            $inputHtml = "";
        }
        return $inputHtml;
    }

    /**
	 * The function `getSelected` returns the word 'selected' if the given value matches the current
	 * value.
	 * 
	 * @param mixed $setValue The `value` parameter is the value that you want to compare against the current value.
	 * @param mixed $current The `current` parameter in the `getSelected` function is the value that you want to
	 * compare against the `value` parameter. If `value` is equal to `current`, the function will return
	 * the `word` parameter, which defaults to 'selected'. Otherwise, it will return an empty
	 * @param string $word The `word` parameter in the `getSelected` function is a default value that is set to
	 * 'selected' if no value is provided when calling the function. It is used to determine the string
	 * that will be returned if the `` is equal to the ``.
	 * 
	 * @return string If the value is equal to the current value, the word 'selected' is being returned.
	 * Otherwise, an empty string is being returned.
	 */
	protected function getSelected( $setValue, $current, $word = 'selected'  ) {
		if ( $setValue === $current ) {
			return $word;
		}
		return '';
	}

	/**
	 * The function `getFieldValue` retrieves a field value, prioritizing a default value if set, and then
	 * checking for an option value.
	 * 
	 * @param object|array $field The `getFieldValue` function takes a parameter ``, which is expected to be an
	 * array containing information about a field. The function retrieves the value of the field based on
	 * certain conditions and returns the value.
	 * 
	 * @return mixed The `getFieldValue` function returns the value of a field. If the field has a default
	 * value set, it will return that default value. Otherwise, it will check if there is an option set
	 * for the field ID using `get_option` and return that value. If no default or option value is found,
	 * it will return an empty string.
	 */
	protected function getFieldValue($active_value=null) : mixed {
		$retVal = '';
		if (isset($this->field['default']) && $this->field['default']) {
			$retVal = $this->field['default'];
		}
		if ($active_value) {
			$retVal = $active_value;
		}
		return $retVal;
	}

	/**
	 * The getFieldAttributes function in PHP retrieves and formats attributes for a given field.
	 * 
	 * @param object|array $field The `getFieldAttributes` function takes a parameter named ``, which is expected
	 * to be an associative array containing attributes for a form field. These attributes can be used to
	 * generate HTML input tags with the specified attributes.
	 * 
	 * @return string The function `getFieldAttributes` returns a string containing the attributes of a
	 * given field.
	 */
	protected function getFieldAttributes() : string {
		$inputAtts = '';
			if (isset($this->field['attrs'])) {
				foreach ($this->field['attrs'] as $key => $attr) {
					$inputAtts .= $key . '="' . $attr . '" ';
				}
			}
		return $inputAtts;
	}

	/**
	 * The function `getAttachment` returns either the URL of an attachment or the attachment URL itself
	 * based on the specified field.
	 * 
	 * @param string|int $useId `value` parameter in the `getAttachment` function represents the ID of the
	 * attachment. This function checks the return value specified in the `` parameter and returns
	 * either the attachment URL or the attachment ID based on the condition.
	 * 
	 * @return string The function `getAttachment` returns either the value itself or the URL of the
	 * attachment based on the condition specified. If the `returnvalue` in the `` array is set to
	 * 'url', it returns the value directly. Otherwise, it retrieves and returns the URL of the attachment
	 * using `wp_get_attachment_url()`.
	 */
	protected function getAttachment($useId) : string {
		return ($this->field['returnvalue'] == 'url') ? $this->value : wp_get_attachment_url($useId);
	}

    /**
	 * The function `recreate_wp_editor` in PHP outputs a WordPress editor with specified content and
	 * options.
	 * 
	 * @param string $content The `content` parameter in the `recreate_wp_editor` function is used to specify
	 * the initial content that will be displayed in the editor. This can be HTML content, text, or any
	 * other content you want to pre-fill the editor with.
	 * @param string|int $editor_id The `editor_id` parameter in the `recreate_wp_editor` function is used to specify
	 * the unique ID for the editor instance. This ID is important for identifying and targeting the
	 * specific editor when working with multiple editors on a page. It should be a unique identifier to
	 * differentiate it from other editors on
	 * @param array $options The `options` parameter in the `recreate_wp_editor` function is an array that
	 * allows you to customize the behavior and appearance of the WordPress editor. Some common options
	 * that you can include in the `` array are:
	 * 
	 * @return mixed function `recreate_wp_editor` returns the output generated by the `wp_editor` function
	 * along with the scripts and editor JavaScript enqueued by `_WP_Editors::enqueue_scripts()` and
	 * `_WP_Editors::editor_js()`.
	 */
	public function recreate_wp_editor($content = '', $editor_id, $options = array()) {
		ob_start();
	
		wp_editor( $content, $editor_id, $options );
	
		$output = ob_get_clean();
		$output .= \_WP_Editors::enqueue_scripts();
		$output .= \_WP_Editors::editor_js();
	
		return $output;
	}

	/**
     * The `fields_Scripts` function enqueues two JavaScript files for handling media fields and media
     * gallery fields in WordPress.
     */
    
	public function fields_styles_scripts() {
        wp_enqueue_style('FieldRenderer__styles', get_template_directory_uri() . $this->assetsPath .'/styles.css', array(), THEME_VERSION);

		wp_enqueue_script('FieldRenderer__media_fields', get_template_directory_uri() . $this->assetsPath . '/media_fields.js', array('jquery'));
		wp_enqueue_script('FieldRenderer__media_gallery_fields', get_template_directory_uri() . $this->assetsPath . '/media_gallery_fields.js', array('jquery'));
	}
}
