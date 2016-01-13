<?php

class Delivery extends \Phalcon\Mvc\Collection {

	public static function labels() {
		return array(
			'country' => 'country',
			'amount' => 'amount',
			'originalmessage' => 'originalmessage',
			'sign' => 'sign',
			'shortcode' => 'shortcode',
			'transactionid' => 'transactionid',
			'mno' => 'mno',
			'reference' => 'reference',
			'revenue' => 'revenue',
			'event_type' => 'event_type',
			'phone' => 'phone',
			'momessage' => 'momessage',
			'enduserprice' => 'enduserprice',
			'service' => 'service',
			'mnocode' => 'mnocode',
			'status' => 'status',
			'revenuecurrency' => 'revenuecurrency',
			'errormessage' => 'errormessage',
		);
	}
}
