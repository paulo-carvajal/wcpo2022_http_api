<?php

namespace paulo\api\parsers;

use paulo\api\interfaces\parser_interface;

class sw_parser implements parser_interface {


	public function parse( array $data ): array {

		// do_action( 'qm/debug', $data );return [];
		return $this->format_data( $data );

	}

	public function format_data( array $data ): array {
		$posts = [];

		if ( ! empty( $data ) ) {
			foreach ( $data as $datum ) {
				$post['post_title']   = $datum->name;
				$post['post_content'] = 'Height: ' . $datum->height;
				$post['post_content'] .= 'Weight: ' . $datum->mass;
				$post['post_content'] .= 'Hair color: ' . $datum->hair_color;
				$post['post_content'] .= 'Eye color: ' . $datum->eye_color;
				$post['post_content'] .= 'Gender: ' . $datum->gender;
				$post['post_status']  = 'publish';
				$posts[]              = $post;
			}
		}

		return $posts;
	}

}



/*
    https://swapi.dev/api/people/
        {
            "name": "Luke Skywalker",
            "height": "172",
            "mass": "77",
            "hair_color": "blond",
            "skin_color": "fair",
            "eye_color": "blue",
            "birth_year": "19BBY",
            "gender": "male",
            "homeworld": "https://swapi.dev/api/planets/1/",
            "films": [
                "https://swapi.dev/api/films/1/",
                "https://swapi.dev/api/films/2/",
                "https://swapi.dev/api/films/3/",
                "https://swapi.dev/api/films/6/"
            ],
            "species": [],
            "vehicles": [
                "https://swapi.dev/api/vehicles/14/",
                "https://swapi.dev/api/vehicles/30/"
            ],
            "starships": [
                "https://swapi.dev/api/starships/12/",
                "https://swapi.dev/api/starships/22/"
            ],
            "created": "2014-12-09T13:50:51.644000Z",
            "edited": "2014-12-20T21:17:56.891000Z",
            "url": "https://swapi.dev/api/people/1/"
        },


*/
