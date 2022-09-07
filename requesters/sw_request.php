<?php

namespace paulo\api\requesters;

use paulo\api\interfaces\requester_interface;
use paulo\api\interfaces\parser_interface;

class sw_request implements requester_interface {

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

	/**
	 * @throws \JsonException
	 */
	public function request():void {

		$response = wp_remote_get( $this->remote_url, $this->args );

		if ( ( ! is_wp_error($response)) && (200 === wp_remote_retrieve_response_code( $response ) ) ) {
			$body = json_decode( wp_remote_retrieve_body( $response ), false, 512, JSON_THROW_ON_ERROR );

			$posts = $this->parser->parse( $body->results );
			if( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					wp_insert_post( $post );
				}

			}
		}

	}

}
