<?php

/**
 * The `ThemeOptions_Settings_Page` class a comprehensive implementation 
 * for creating and managing theme options pages in WordPress. 
 * Including handling settings, fields, sections, and styling.
 * Retrieving values: get_option( 'your_field_id' )
 */
class ThemeOptions_Settings_Page {
	// Default Page Id: ThemeOptions
	private $page_id = 'ThemeOptions';
	protected $page_title;
	protected $page_parent = null;
	protected $page_description = null;
	public $page_pos = 2;
	public $prefix = 'themeopts_section';
	public $sections = [];
	public $fields_group = [];
	public $data_file;
	public $assetsPath = '/inc/ThemeTweaker/theme-options/assets';

	/**
	 * The function is a PHP constructor that initializes properties based on input array values and file
	 * path, then calls additional methods.
	 * 
	 * @param array $page parameter in the constructor is an array that contains information
	 * about a specific page. It typically includes the following keys:
	 * @param string $dataFilePath parameter in the constructor is a string that represents the
	 * path to the data file. By default, it is set to "/" which means the root directory. You can provide
	 * a different path when creating an instance of the class if the data file is located in a different
	 * directory.
	 */
	public function __construct(array $page, $dataFilePath="/") {
		$this->page_id = $page['id'];
		$this->page_title = $page['title'];
		if (isset($page['parent'])) {
			$this->page_parent = $page['parent'];
		}
		if (isset($page['description'])) {
			$this->page_description = $page['description'];
		}
		$this->data_file = $dataFilePath;
		
		$this->getDataFromFile();
		$this->add_actions();
		
	}

	/**
	 * The function `add_actions` call wp actions for setting up a theme options page.
	 */
	protected function add_actions() {
		add_action('admin_menu', array($this, 'themeopts_create_page'));
		add_action('admin_init', array($this, 'themeopts_setup_sections'));
		add_action('admin_init', array($this, 'themeopts_setup_fields'));
		
		if (isset($_GET['page']) && $_GET['page'] == $this->page_id) {
			add_action('admin_head', array($this, 'themeopts_page_style'));
			add_action('admin_footer', array($this, 'themeopts_page_script'));	
        }
		
		add_action('admin_enqueue_scripts', 'wp_enqueue_media');
	}

	/**
	 * The function `add_page` adds a new page or submenu page to the WordPress admin menu.
	 * 
	 * @param string $page_title The `page_title` parameter is the title of the page that will be displayed in the
	 * browser window and in the admin menu. It is the main title of the page that you are adding to the
	 * WordPress admin menu.
	 * @param string $menu_title The `menu_title` parameter in the `add_page` function is the text that will be
	 * displayed in the admin menu for the page you are adding. It is the title that users will see and
	 * click on to access the page.
	 * @param string $capability The `capability` parameter in the `add_page` function is used to specify the
	 * minimum capability required for a user to access the page. This capability is typically a user role
	 * or a specific capability granted to users. For example, common capabilities include
	 * 'manage_options', 'edit_posts', 'publish_pages
	 * @param string $slug The `slug` parameter in the `add_page` function is used to specify the unique
	 * identifier for the page being added to the WordPress admin menu. It is typically a string that is
	 * used in the URL to access the page. For example, if the slug is "my-custom-page", the URL
	 * @param array $callback The `callback` parameter in the `add_page` function is a callback function that
	 * will be called when the menu item is clicked. This function should contain the code that generates
	 * the content for the page associated with the menu item.
	 * @param string $icon The `icon` parameter in the `add_page` function is used to specify the URL to the icon
	 * that will be displayed next to the menu item. This icon can be a URL to an image file or a
	 * dashicons class name. It helps in visually identifying the menu item in the WordPress admin
	 * @param int $position The `position` parameter in the `add_page` function is used to specify the position
	 * of the menu item in the admin menu. It determines where the menu item will be placed in the menu
	 * hierarchy relative to other menu items.
	 */
	protected function add_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position=null) {
		if ($this->page_parent != null) {
			add_submenu_page($this->page_parent, $page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
		} else {
			add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
		}
	}

	/**
	 * The function `getDataFromFile` reads data from a file and assigns specific values to class
	 * properties if the file exists, otherwise it throws an exception.
	 */
	protected function getDataFromFile() {
		$filePath = get_template_directory() . $this->data_file;
		if (!is_dir($filePath) && file_exists($filePath)) {
			$data = include $filePath;
			if (isset($data) && is_array($data)) {
				$this->sections = $data['sections'];
				$this->fields_group = $data['elements'];
				$this->prefix = $data['prefix'];
			} 
		} else {
			// Handle the case where the file does not exist
			throw new Exception("File $filePath does not exist");
		}
	}

	/**
	 * The function themeopts_create_page() creates a new admin page in WordPress with specified
	 * parameters.
	 */
	public function themeopts_create_page() {
		$menu_title = $this->page_title;
		$capability = 'manage_options';
		$slug = $this->page_id;
		$callback = array($this, 'themeopts_render');
		$icon = 'dashicons-welcome-widgets-menus';
		$position = $this->page_pos;
		$this->add_page($this->page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
	}

	/**
	 * The function `themeopts_render` generates HTML for a theme options page.
	 */
	public function themeopts_render() {
		$html = sprintf(
			'<div class="wrap" id="%1$s">
				<div class="page-title">
					<h1>%2$s</h1>
				</div>
				<div class="page-errors">
					%3$s
				</div>
				<form id="%1$s_form" class="opts_form" method="POST" action="options.php">
					%4$s
					%5$s
					%6$s
				</form>
				<div class="page-notes">
					%7$s
				</div>
			</div>',
			$this->page_id,
			$this->page_title,
			$this->themeopts_settings_errors(),
			$this->themeopts_settings_fields($this->page_id),
			$this->themeopts_do_settings_sections($this->page_id),
			$this->themeopts_submit_button(),
			'<span>'.__('all fields with <strong>*</strong> are required', THEME_DOMAIN).'</span>'
		);
		echo $html;
	}

	/**
	 * The function `themeopts_setup_sections` sets up sections for a theme options page.
	 */
	public function themeopts_setup_sections() {
		foreach ($this->sections as $section => $sectionName) {
			$sectionId = $this->prefix.'_'. $section;
			$args = [];
			
			/* The above code is adding a settings section to a WordPress settings page. The
			`add_settings_section` function is used to define a new section within a settings page. The
			parameters passed to the function are the section ID, section name, callback function (which is
			empty in this case), the page ID where the section will be added, and any additional arguments. */
			add_settings_section($sectionId, $sectionName, array(), $this->page_id, $args);
		}
	}

	/**
	 * The function `themeopts_setup_fields` sets up fields for a theme options page by iterating
	 * through an array of fields and registering them with WordPress settings.
	 */
	public function themeopts_setup_fields() {
		$allFields = $this->fields_group;
		if (count($allFields) > 0) {
			foreach ($allFields as $fields) {
				if (count($fields) > 0) {
					foreach ($fields as $field) {
						$field['label_for'] = $field['id'];

						/*  This function is typically used in WordPress theme development to add custom settings fields to the
						theme options page for users to customize the theme settings. */
						add_settings_field($field['id'], $field['label'], array($this, 'themeopts_field_callback'), $this->page_id,  $field['section'], $field);
						
						/* The `register_setting` function is used to register a setting for a specific page in the WordPress admin panel. */
						register_setting($this->page_id, $field['id']);
					}
				}
			}
		}
	}

	/**
	 * The function `themeopts_field_callback` generates HTML input fields based on the provided field
	 * type and attributes.
	 * 
	 * @param object|array $field The `themeopts_field_callback` function is used to generate HTML input fields based on
	 * the provided field configuration. The function takes two parameters:
	 * @param bool $display parameter in the `themeopts_field_callback` function is a boolean
	 * parameter that determines whether the generated input HTML should be displayed immediately or
	 * returned as a string. If `` is set to `true`, the input HTML will be echoed out
	 * immediately. If it's set to `
	 * 
	 * @return mixed The function `themeopts_field_callback` is returning the HTML input elements based on the
	 * field type specified in the `` parameter. The generated HTML input elements include text
	 * inputs, select dropdowns, checkboxes, radio buttons, buttons, textareas, and more, depending on the
	 * type of field.
	 */
	public function themeopts_field_callback($field, $display=false) {
		
		$inputHtml = '';
		
		$fieldRenderer = new FieldRenderer($field, get_option($field['id']));
		$inputHtml = $fieldRenderer->render();

		

		if ($display) { echo $inputHtml; }
		return $inputHtml;
	}

	/**
	 * The function `themeopts_do_settings_sections` generates HTML tabs for displaying settings sections
	 * on a page.
	 * 
	 * @param string $page The `page` parameter in the `themeopts_do_settings_sections` function represents the
	 * page or section within the WordPress admin settings where these settings sections will be
	 * displayed. It is used to determine which settings sections should be rendered on that specific
	 * page.
	 * @param bool $display The `display` parameter in the `themeopts_do_settings_sections` function is a
	 * boolean parameter that determines whether the output should be displayed immediately or returned as
	 * a string.
	 * 
	 * @return mixed The function `themeopts_do_settings_sections` is returning the HTML output for displaying
	 * tabs and tab content based on the settings sections and fields provided for a specific page. The
	 * output includes tab headers, tab links, tab content with form fields, and the necessary HTML
	 * structure for organizing the tabs and content within a container. If the `` parameter is
	 * set to true, the output is echoed to the
	 */
	public function themeopts_do_settings_sections($page, $display=false) {
		global $wp_settings_sections, $wp_settings_fields;
	
		if (!isset($wp_settings_sections[$page])) {
			return;
		}
	
		$output = '<div class="tabs">';
		$output.= '<div class="tab-header">';
	
		foreach ((array) $wp_settings_sections[$page] as $section) {
			if (''!== $section['before_section']) {
				if (''!== $section['section_class']) {
					$output.= wp_kses_post(sprintf($section['before_section'], esc_attr($section['section_class'])));
				} else {
					$output.= wp_kses_post($section['before_section']);
				}
			}
			
			
			$tab = sprintf(
				'<a class="tab-link" data-tab="%1$s" title="" role="button">
					<span>%2$s</span>
				</a>', 
				$section['id'],
				$section['title'] ?? ""
			);
			$output.= $tab;
	
			if ($section['callback']) {
				call_user_func($section['callback'], $section);
			}
			if (''!== $section['after_section']) {
				$output.= wp_kses_post($section['after_section']);
			}
		}
		$output.= '</div><!--.tab-header -->';
	
		$output.= '<div class="tab-content">';
		foreach ((array) $wp_settings_sections[$page] as $section) {
			if (!isset($wp_settings_fields) ||!isset($wp_settings_fields[$page]) ||!isset($wp_settings_fields[$page][$section['id']])) {
				continue;
			}
			$tabpane = sprintf(
				'<div id="%1$s" class="tab-pane">
					<div class="form-fields" role="presentation">
					%2$s
					</div>
				</div><!-- #%1$s -->',
				$section['id'],
				$this->themeopts_do_settings_fields($page, $section['id'])
			);
			$output.= $tabpane;
		}
		$output.= '</div><!--.tab-content -->';
		
		$output.= '</div><!--.tabs -->';
	
		if ($display) {
			echo $output;
		}
		return $output;
	}

	/**
	 * The function `themeopts_do_settings_fields` generates HTML input fields for a given page and
	 * section in WordPress theme settings.
	 * 
	 * @param string $page The `page` parameter in the `themeopts_do_settings_fields` function represents the page
	 * on which the settings fields are displayed. It is used to specify the settings page where the
	 * fields belong, such as 'general', 'reading', 'writing', 'discussion', etc. This parameter helps in
	 * organizing
	 * @param mixed $section The `section` parameter in the `themeopts_do_settings_fields` function refers to the
	 * specific section within a settings page where the settings fields are located. This parameter helps
	 * to identify and retrieve the settings fields associated with that particular section for further
	 * processing or display.
	 * @param bool $display The `display` parameter in the `themeopts_do_settings_fields` function is a boolean
	 * parameter that determines whether the generated settings fields should be displayed immediately or
	 * returned as a string.
	 * 
	 * @return mixed The `themeopts_do_settings_fields` function returns the generated HTML output for the
	 * settings fields specified by the `` and `` parameters. If the `` parameter is
	 * set to `true`, the output is echoed to the screen. The function also returns the generated output
	 * string.
	 */
	public function themeopts_do_settings_fields($page, $section, $display=false) {
		global $wp_settings_fields;

		if (!isset($wp_settings_fields[$page][$section])) {
			return;
		}

		$output = '';
		foreach ((array) $wp_settings_fields[$page][$section] as $field) {
			$class = ['input-group'];

			if (!empty($field['args']['class'])) {
				$class[] = esc_attr($field['args']['class']);
			}

			$inputGroup = sprintf(
				'<div class="%1$s">
					<label for="%2$s">%3$s</label>
					%4$s
				</div><!-- %1$s -->',
				implode(" ", $class),
				esc_attr($field['args']['label_for']),
				$field['args']['label'],
				call_user_func($field['callback'], $field['args']),
			);

			$output.= $inputGroup;
		}
		if ($display) {
			echo $output;
		}
		return $output;
	}

	/**
	 * The function `themeopts_settings_errors` displays settings errors in a formatted HTML output based
	 * on their type.
	 * 
	 * @param string $setting The `setting` parameter in the `themeopts_settings_errors` function is used to
	 * specify the type of settings errors to retrieve. It is typically a string that identifies a
	 * specific group of settings errors. This parameter helps in fetching and displaying errors related
	 * to a particular setting or section of the theme options.
	 * @param bool $sanitize The `sanitize` parameter in the `themeopts_settings_errors` function is used to
	 * determine whether the settings errors should be sanitized or not. If `` is set to `true`,
	 * it means that the settings errors will be sanitized before being displayed. Sanitizing data helps
	 * prevent security vulnerabilities by
	 * @param bool hide_on_update The `hide_on_update` parameter in the `themeopts_settings_errors` function is
	 * a boolean parameter that determines whether to hide the settings errors when the settings are
	 * updated. If `hide_on_update` is set to `true` and the `settings-updated` query parameter is present
	 * in the URL
	 * 
	 * @return mixed The function `themeopts_settings_errors` returns HTML output for displaying settings
	 * errors. It checks if there are any settings errors for a specific setting, sanitizes them if
	 * needed, and then generates HTML markup for each error message. The final output is a series of
	 * `<div>` elements with appropriate CSS classes and error messages.
	 */
	public function themeopts_settings_errors($setting = '', $sanitize = false, $hide_on_update = false ) {

		if ( $hide_on_update && ! empty( $_GET['settings-updated'] ) ) {
			return;
		}
	
		$settings_errors = get_settings_errors( $setting, $sanitize );
	
		if ( empty( $settings_errors ) ) {
			return;
		}
	
		$output = '';
	
		foreach ( $settings_errors as $key => $details ) {
			if ( 'updated' === $details['type'] ) {
				$details['type'] = 'success';
			}
	
			if ( in_array( $details['type'], array( 'error', 'success', 'warning', 'info' ), true ) ) {
				$details['type'] = 'notice-' . $details['type'];
			}
	
			$css_id    = sprintf(
				'setting-error-%s',
				esc_attr( $details['code'] )
			);
			$css_class = sprintf(
				'notice %s settings-error is-dismissible',
				esc_attr( $details['type'] )
			);
	
			$output .= "<div id='$css_id' class='$css_class'> \n";
			$output .= "<p><strong>{$details['message']}</strong></p>";
			$output .= "</div> \n";
		}
	
		return $output;
	}

	/**
	 * The function `themeopts_submit_button` returns an HTML submit button with customizable text, type,
	 * name, wrapping, and additional attributes.
	 * 
	 * @param string $text The `text` parameter is the text that will be displayed on the submit button. It is a
	 * string value that represents the label or text content of the button.
	 * @param string $type The `type` parameter in the `themeopts_submit_button` function is used to specify the
	 * type of button to be displayed. It can have values like 'primary', 'secondary', 'delete', 'submit',
	 * etc., depending on the styling you want for the button.
	 * @param string $name The `name` parameter in the `themeopts_submit_button` function is used to specify the
	 * name attribute of the submit button input element in the HTML form. This name attribute is
	 * important when the form is submitted as it identifies the form data that will be sent to the
	 * server.
	 * @param bool $wrap The `wrap` parameter in the `themeopts_submit_button` function is a boolean parameter
	 * that determines whether the submit button should be wrapped in a `<div>` element. If `wrap` is set
	 * to `true`, the submit button will be wrapped in a `<div>` element. If `wrap
	 * @param mixed $other_attributes The `other_attributes` parameter in the `themeopts_submit_button` function
	 * is used to pass additional HTML attributes to the submit button element. These attributes can
	 * include things like `class`, `id`, `data-*` attributes, `style`, and any other valid HTML
	 * attributes that you want to include
	 * 
	 * @return mixed The `themeopts_submit_button` function is returning the result of the `get_submit_button`
	 * function with the provided arguments.
	 */
	public function themeopts_submit_button( $text = '', $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = '' ) {
		return get_submit_button( $text, $type, $name, $wrap, $other_attributes );
	}
	
	/**
	 * The function `themeopts_settings_fields` generates hidden input fields for WordPress theme options
	 * settings with a nonce field for security.
	 * 
	 * @param string $option_group The `option_group` parameter in the `themeopts_settings_fields` function is
	 * used to specify the settings group for which the fields are being generated. It is typically used
	 * in WordPress settings API functions to group related settings together. This helps in organizing
	 * and managing settings for a theme or plugin.
	 * 
	 * @return mixed The `themeopts_settings_fields` function is returning a series of hidden input fields for a
	 * form. The returned HTML includes:
	 * 1. An input field with the name 'option_page' and the value of the `` parameter,
	 * escaped using `esc_attr`.
	 * 2. Another hidden input field with the name 'action' and the value 'update'.
	 * 3. A nonce field generated by the
	 */
	public function themeopts_settings_fields($option_group) {

		$html = "<input type='hidden' name='option_page' value='" . esc_attr( $option_group ) . "' />";
		$html.= '<input type="hidden" name="action" value="update" />';
		// $action = -1, $name = '_wpnonce', $referer = true, $display = true 
		$html.= wp_nonce_field( "$option_group-options",'_wpnonce',true, false );
		return $html;
	}
	

	/**
	 * The function `themeopts_page_style` enqueues a CSS file for the theme options page in WordPress.
	 */
	public function themeopts_page_style() {
		wp_enqueue_style('themeopts_page_style', get_template_directory_uri() . $this->assetsPath .'/options-page.css', array(), THEME_VERSION);
	}

	/**
	 * The function `themeopts_page_script` enqueues three JavaScript files for a WordPress theme options
	 * page.
	 */
	public function themeopts_page_script() {
		wp_enqueue_script('themeopts_page_script', get_template_directory_uri() . $this->assetsPath . '/options-page.js',  array('jquery'), THEME_VERSION, true);
	}
}

$SettingsPage = new ThemeOptions_Settings_Page(
	[
		'id' 	=> 'ThemeSettings',
		'title' => __('Theme Settings', THEME_DOMAIN) 
	],
$dataFilePath = '/inc/ThemeTweaker/theme-options/src/settings.php');

$homePage = new ThemeOptions_Settings_Page(
	[
		'id' 	=> 'ThemeHomePage',
		'title' => __('Theme Home page', THEME_DOMAIN), 
		'parent'=> 'ThemeSettings', 
	],
$dataFilePath = '/inc/ThemeTweaker/theme-options/src/home.php');