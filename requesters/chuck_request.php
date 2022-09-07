<?php

namespace paulo\api\requesters;

use paulo\api\interfaces\requester_interface;
use paulo\api\interfaces\parser_interface;

class chuck_request implements requester_interface {

	private string $remote_url;
	private array $args;
	private parser_interface $parser;

	public function __construct ( string $remote_url, parser_interface $parser ) {

		$this->remote_url = $remote_url;
		$this->parser = $parser;
		$this->args = [
			'timeout' => 10,
			'headers' => [
				'referer' => home_url(),
			],
		];
	}

	public function request():void {

		$chuck_response = get_transient( 'retrieved_chuck_data' );

		if ( false === $chuck_response ) {

			$response = wp_remote_get( $this->remote_url, $this->args );

			if ( ( ! is_wp_error($response)) && (200 === wp_remote_retrieve_response_code( $response ) ) ) {
				set_transient( 'retrieved_chuck_data', $response, HOUR_IN_SECONDS );
			}

			// https://www.php.net/manual/es/json.constants.php
			try {
				$body = json_decode( wp_remote_retrieve_body( $chuck_response ), false, 512, JSON_THROW_ON_ERROR );
			} catch ( \JsonException $e ) {
				throw new \RuntimeException( 'Error Processing Request', 1 );
			}

			$posts = $this->parser->parse( $body->result );

			// Change behaviour here, or extract it to another Class:
			if( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					wp_insert_post( $post );
				}

			}
		}

	}

}
