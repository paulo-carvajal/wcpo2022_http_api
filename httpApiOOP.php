<?php
/**
 * Plugin Name: HTTP API OOP
 * Description: A HTTP API OOP implementation
 * Author: Paulo Carvajal
 * Version: 1.0
 */

namespace paulo\api;

use paulo\api\parsers\chuck_parser;
use paulo\api\parsers\sw_parser;
use paulo\api\requesters\chuck_request;
use paulo\api\requesters\sw_request;
use paulo\api\util\requests_maker;

class httpApiOOP {

	private static httpApiOOP $instance;

	public static function getInstance(): httpApiOOP {
		static::$instance = static::$instance ?? new static();
		return static::$instance;
	}

	// singleton constructs should be protected
	protected function __construct() {}

	public function init(): void {
		$this->create_plugin_constants();

		add_action( 'plugins_loaded', [ $this, 'auto_load' ] );
		add_action( 'init', [ $this, 'init_import'] );
	}

	public function create_plugin_constants(): void {
		$constant_name_prefix = 'HTTP_';
		$plugin_data = get_file_data( __FILE__, array( 'name'=>'Plugin Name', 'version'=>'Version', 'text'=>'Text Domain' ) );
		define( $constant_name_prefix . 'DIR', dirname( plugin_basename( __FILE__ ) ) );
		define( $constant_name_prefix . 'BASE', plugin_basename( __FILE__ ) );
		define( $constant_name_prefix . 'URL', plugin_dir_url( __FILE__ ) );
		define( $constant_name_prefix . 'PATH', plugin_dir_path( __FILE__ ) );
		define( $constant_name_prefix . 'NAME', $plugin_data['name'] );
		define( $constant_name_prefix . 'VERSION', $plugin_data['version'] );
	}

	public function auto_load(): void {
		if (file_exists(__DIR__ . '/vendor/autoload.php')) {
			include __DIR__ . '/vendor/autoload.php';
		}
	}

	public function init_import(): void {

		$request_maker = new requests_maker();

		$request_maker->add_requester( new chuck_request( 'https://api.chucknorris.io/jokes/search?query=sleep', new chuck_parser() ) );
		$request_maker->add_requester( new sw_request( 'https://swapi.dev/api/people/', new sw_parser() ) );

		$request_maker->load();

	}

}

httpApiOOP::getInstance()->init();
