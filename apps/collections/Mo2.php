<?php

class Mo2 extends \Phalcon\Mvc\Collection {

	public static function labels() {
		return array(
			'moId' => 'moId',
			'countryISO' => 'countryISO',
			'origin' => 'origin',
			'moContent' => 'moContent',
			'spnumber' => 'spnumber',
			'price' => 'price',
			'operator' => 'operator',
			'addtime' => 'addtime',
		);
	}
}
