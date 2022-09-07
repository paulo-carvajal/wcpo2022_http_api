<?php

namespace paulo\api\util;

class admin {

	private $plugin_id = 'httpapi';

	public function __construct() {}


	public function init() {
		add_action( 'admin_menu', [ $this, 'add_menu_item' ] );

	}


	public function add_menu_item() {
		$page = add_options_page(
			__('HTTP API', $this->plugin_id), // page_title
			__('HTTP API', $this->plugin_id), // menu_title
			'manage_options', // capability
			$this->plugin_id . '_page', // menu_slug
			array($this, 'options_page') // callback
		);
		// add_action('admin_print_styles-' . $page, array($this, 'settings_assets'));
	}



	public function options_page() {

		// Check user capabilities
		if (!current_user_can('manage_options')) {
			new WP_Error('403', __('You are not allowed to see this page!!!', $this->plugin_id));
			return;
		}

		$is_import = (isset($_GET['import'])) ? true : false;

		if ($is_import && check_admin_referer('import', 'import_nounce')) {

			$requested_data = $this->api->get_experiences();

			if( is_wp_error( $requested_data ) ) {

				$error_string = $requested_data->get_error_message();
				echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
				return;
			}

			$post_imported = $this->importer->import_batch($requested_data);

			?>
		<div class="wrap ">
			<?php _e('Importing data...', $this->plugin_id); ?>
			<br>
			<?php printf( __('Imported %d posts.', $this->plugin_id), $post_imported);?>

		</div>
		<?php
		} else {
		?>
		<div class="wrap ">
			<h2><?php _e('HTTP API Options', $this->plugin_id); ?></h2>
			<form method="POST" action="options.php">
			<?php settings_fields($this->plugin_id . '_settings'); ?>
			<?php do_settings_sections($this->plugin_id . '_page') ?>
			<?php submit_button(); ?>
			</form>


			<h2 id="pac_hs_import_section"><?php _e('Import selected posts', $this->plugin_id); ?></h2>
			<form method="POST" action="options-general.php?page=<?php echo $this->plugin_id . '_page&import'; ?>">
			<?php wp_nonce_field('import', 'import_nounce'); ?>
			<?php submit_button(__('Import', $this->plugin_id)); ?>

			</form>
		</div>
		<?php
		}
	}


}
