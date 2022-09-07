<?php

namespace paulo\api\util;

class settings {

	private $plugin_id = 'http';

	protected $settings;

	public function __construct(){

	}

	public function init () {

		$this->define_settings();
		$this->init_settings();

	}


	private function define_settings()  {

		$settings['standard'] = [
			'title' => __('HTTP Settings', $this->plugin_id),
			'description' => __('Please, select your options and save them before clicking <a href="#pac_hs_import_section">Import</a>.', $this->plugin_id),
			'fields' => [
				array(
					'id'      => 'url_input',
					'label'     => __('Choose a url:', $this->plugin_id),
					'description' => __('Choose the url you want to call.', $this->plugin_id),
					'type'      => 'input',
					'placeholder' => __('https://...', $this->plugin_id),
					'default'   => ''
				),
				array(
					'id'      => 'hobbies',
					'label'     => __('Please, select one or more Hobbies:', $this->plugin_id),
					'description' => '',
					'type'      => 'select',
					'placeholder' => __('Hobbies', $this->plugin_id),
					'default'   => ''
				),
				array(
					'id'      => 'experiences_amount',
					'label'     => __('Select the amount of Experiences to import', $this->plugin_id),
					'description' => '',
					'type'      => 'number',
					'default'   => ''
				),
				array(
					'id'      => 'location_radius',
					'label'     => __('Select radius of Location to search', $this->plugin_id),
					'description' => '',
					'type'      => 'number',
					'default'   => '100'
				),

			],
		];

		$this->settings = apply_filters($this->plugin_id . '_settings', $settings);
	}



	public function init_settings() {

		if (is_array($this->settings)) {

			foreach ($this->settings as $section => $data) {

				add_settings_section(
					$section,
					$data['title'],
					array($this, 'settings_section'),
					$this->plugin_id . '_page'
				);

				foreach ($data['fields'] as $field) {

					// Validation callback for field
					$validation = '';
					if (isset($field['callback'])) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->plugin_id . '_' . $field['id'];
					register_setting($this->plugin_id . '_settings', $option_name, $validation);

					// Add field to page
					add_settings_field(
						$field['id'], // id
						$field['label'], // title
						array($this, 'display_field'), // callback
						$this->plugin_id . '_page', // page (slug)
						$section, // section='default'
						array('field' => $field, 'prefix' => $this->plugin_id) // args to callback
					);
				}
			}
		}
	}


	public function settings_section($section) {
			$html = '<p> ' . $this->settings[$section['id']]['description'] . '</p>' . "\n";
			echo $html;
	}


	public function display_field($args) {
		$out = '';

		switch ($args['field']['type']) {
			case 'select':
				if ('location' == $args['field']['id']) {
					// $out = $this->locations_select();
				} else if ('hobbies' == $args['field']['id']) {
					// $out = $this->hobbies_select();
				}
				break;
			case 'number':
				if ('experiences_amount' == $args['field']['id']) {
					// $out = $this->experiences_amount_input();
				} else if( 'location_radius' == $args['field']['id']) {
					// $out = $this->location_radius_input();
				}
				break;
			case 'input':
				$out =  $this->set_url_input();
				break;
			default:
				# code...
				break;
		}

		echo $out;
	}


	private function set_url_input() {
		$field = 'url_input';
		$settings = get_option($this->plugin_id . '_' . $field);
		$value = $settings != '' ? esc_attr($settings) : '';
		$out = '<input name="' . $this->plugin_id . '_' . $field . '" type="tet" id="' . $this->plugin_id . '_' . $field . '" value="' . $value . '" class="small-text"></td>';

		return $out;
	}


}
