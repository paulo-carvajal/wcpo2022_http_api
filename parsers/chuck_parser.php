<?php

namespace paulo\api\parsers;

use paulo\api\interfaces\parser_interface;

class chuck_parser implements parser_interface {

	public function parse( array $data ): array {
		return $this->format_data( $data );
	}

	private function format_data (array $data ): array {
		$posts = [];

		if( ! empty( $data ) ) {
			foreach ($data as $datum ) {
				$post['post_title'] = $datum->value;
				$post['post_content'] = $datum->created_at . $datum->url;
				$post['post_status'] = 'publish';
				$posts[] = $post;
			}
		}

		return $posts;
	}

}

/*
https://api.chucknorris.io/jokes/search?query=sleep

	"created_at": "2020-01-05 13:42:18.823766",
	"icon_url": "https://assets.chucknorris.host/img/avatar/chuck-norris.png",
	"id": "qqthrspvtqyigfwvaui2eq",
	"updated_at": "2020-01-05 13:42:18.823766",
	"url": "https://api.chucknorris.io/jokes/qqthrspvtqyigfwvaui2eq",
	"value": "Chuck Norris sleeps with a pillow under his gun."

*/
