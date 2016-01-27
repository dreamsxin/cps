<?php

class Games extends \Phalcon\Mvc\Collection {

	public function getColumnMap() {
		return array(
			'name' => 'name',
		);
	}
}
