<?php

class Delivery extends \Phalcon\Mvc\Collection {

	public static function labels() {
		return array(
			'msisdn' => 'msisdn',
			'status' => 'status',
			'msg' => 'msg',
			'uid' => 'uid',
		);
	}
}

