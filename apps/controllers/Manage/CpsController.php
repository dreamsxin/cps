<?php
namespace Manage;

class CpsController extends ControllerBase {

	public function initialize() {
		\Phalcon\Tag::appendTitle('CPS 数据');
	}
	
	public function indexAction() {
	}
	
	public function addAction() {
		\Phalcon\Tag::appendTitle('录入');
		if ($this->request->isPost()) {
		}

		$this->view->pick('manage/cps/form');
		$this->view->title = '数据录入';
		$this->view->errors = array();
	}
	
	public function importAction() {
		\Phalcon\Tag::appendTitle('导入');
		if ($this->request->isPost()) {
		}

		$this->view->errors = array();
	}

}
