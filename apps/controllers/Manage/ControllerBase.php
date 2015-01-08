<?php
namespace Manage;

abstract class ControllerBase extends \ControllerSecurity {

	public function beforeExecuteRoute($dispatcher) {
		parent::beforeExecuteRoute($dispatcher);
		\Phalcon\Tag::appendTitle('管理后台');
		$this->view->disableLevel(\Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT);
	}

}
