<?php

namespace paulo\api\util;

use paulo\api\interfaces\requester_interface;

class requests_maker {

	protected array $requesters;

	public function __construct ( ) {
		$this->requesters = [];
	}

	public function add_requester( requester_interface $requester ): void {
		$this->requesters[] = $requester;
	}

	public function load(): bool {

		if( ! empty( $this->requesters ) ){
			foreach ( $this->requesters as $requester ) {
				$requester->request();
			}
			return true;
		}

		return false;
	}


}
