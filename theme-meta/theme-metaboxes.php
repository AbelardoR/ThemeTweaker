<?php

// Meta Box Class: ThemeReaderMetaBox
// Get the field value: $metavalue = get_post_meta( $post_id, $field_id, true );
class ThemeReaderMetaBox
{
	private $metabox_id;
	private $metabox_title;
	private $metabox_context = 'side';
	private $metabox_nonce = 'this_nonce';
	private $screens = ['post'];
	private $meta_fieldsets = [];
	private $meta_fields = [];
	private $meta_fieldset_class='';
	public $prefix = 'theme_meta_box';
	public $data_file;

	public function __construct(array $metabox, $dataFilePath="/") {
		$this->metabox_id = $metabox['id'];
		$this->metabox_title = $metabox['title'];

		if (array_key_exists('context', $metabox)) {
			$this->metabox_context = $metabox['context'];
		}
		if (array_key_exists('nonce', $metabox)) {
			$this->metabox_nonce = $metabox['nonce'];
		}
		if (array_key_exists('post_type', $metabox)) {
			$this->screens = $metabox['post_type'];
		}
		
		$this->data_file = $dataFilePath;
		
		$this->getDataFromFile();
		$this->add_actions();
		
	}

	protected function add_actions() {
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_meta_box_fields'));
	}

	protected function getDataFromFile() {
		$filePath = get_template_directory() . $this->data_file;
		if (!is_dir($filePath) && file_exists($filePath)) {
			$data = include $filePath;
			if (isset($data) && is_array($data)) {
				$this->meta_fieldsets = $data['sections'];
				$this->meta_fields = $data['elements'];
				$this->prefix = $data['prefix'];
			}
		} else {
			// Handle the case where the file does not exist
			throw new Exception("File $filePath does not exist");
		}
	}

	public function add_meta_boxes() {
		$id = $this->metabox_id;
		$title = $this->metabox_title;
		$context = 'side';
		$priority = 'default';
		$callback_args = null;
		foreach ($this->screens as $screen) {
			add_meta_box($id, $title, array($this, 'meta_box_render'), $screen, $context, $priority, $callback_args);
		}
	}

	public function meta_box_render($post)
	{
		wp_nonce_field($this->metabox_id, $this->metabox_nonce);
		echo $this->meta_box_fieldsets($post);
	}

	public function meta_box_fieldsets($post, $display=false) {
		$fieldsetHtml = '';
		foreach ($this->meta_fieldsets as $fieldset_key => $fieldset_title) {
			$fieldset = $this->prefix.'_'.$fieldset_key;
			$fieldsetHtml.= sprintf('
				<fieldset id="%1$s" class="fieldset-%4$s %5$s">
					<legend>%2$s</legend>
					%3$s
				</fieldset>',
				$fieldset,
				$fieldset_title,
				$this->meta_box_fields($post, $fieldset),
				$this->metabox_context,
				$this->meta_fieldset_class
			);
		}
		if ($display) { echo $fieldsetHtml; }
		return $fieldsetHtml;
	}

	public function meta_box_fields($post, $fieldset, $display=false) {
		$inputHtml = '';

		foreach ($this->meta_fields as $meta_field) {
			foreach ($meta_field as $field) {
				if ($field['section'] == $fieldset) {
					$this->meta_fieldset_class = $field['class'];
					$inputHtml.= '<div class="input-group">';
					$inputHtml.= '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
					$fieldRenderer = new FieldRenderer($field,  get_post_meta($post->ID, $field['id'], true));
					$inputHtml.= $fieldRenderer->render();
					$inputHtml.= '</div>';
				}
			}
		}
		
		if ($display) { echo $inputHtml; }
		return $inputHtml;

	}

	public function format_rows($label, $input, $desc = '')
	{
		$style = 'margin-bottom: 12px;';
		return '<div class="components-input-control__container" style="' . $style . '">' . __($label, THEME_DOMAIN) . '' . $input . '' . __($desc, THEME_DOMAIN) . '</div>';
	}

	public function save_meta_box_fields($post_id)
	{
		if (!isset($_POST[$this->metabox_nonce])) {
			return $post_id;
		}
		$nonce = $_POST[$this->metabox_nonce];
		if (!wp_verify_nonce($nonce, $this->metabox_id)) {
			return $post_id;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		foreach ($this->meta_fields as $meta_field) {
			foreach ($meta_field as $field) {
				update_post_meta($post_id, $field['id'], $_POST[$field['id']]);
			}
		}
	}

}

$SettingsPage = new ThemeReaderMetabox(
	[
		'id' 	=> 'ThemeReader',
		'title' => __('ThemeTweaker', THEME_DOMAIN),
		'nonce' => 'Theme_Reader_Nonce',
		'post_type' => [
			'post',
			'page',
			'link',
		]
	],
$dataFilePath = '/inc/ThemeTweaker/theme-meta/src/meta-fields.php');