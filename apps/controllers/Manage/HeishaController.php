<?php

namespace Manage;

class HeishaController extends ControllerBase {

	public function moAction($page = 1) {
		\Phalcon\Tag::appendTitle('计费Mo信息');
		$this->view->labels = \HeiShaMo::labels();
		$conditions = array();

		$data = \HeiShaMo::find(array(
			$conditions,
			'sort' => array('_id' => -1)
		));

		$paginator = new \Phalcon\Paginator\Adapter\Model(
				array(
					"data" => $data,
					"limit" => 50,
					"page" => $page
				)
		);

		$this->view->page = $paginator->getPaginate();
	}

	public function drAction($page = 1) {
		\Phalcon\Tag::appendTitle('计费Dr信息');
		$this->view->labels = \HeiShaMo::labels();
		$conditions = array();

		$data = \HeiShaDr::find(array(
			$conditions,
			'sort' => array('_id' => -1)
		));

		$paginator = new \Phalcon\Paginator\Adapter\Model(
				array(
					"data" => $data,
					"limit" => 50,
					"page" => $page
				)
		);

		$this->view->page = $paginator->getPaginate();
	}

	public function dropAction() {
		if ($this->session->get('group') != '超级管理员') {
			$this->redirect('manage/heisha/mo', '您没有超级管理员权限');
		}

		\HeiShaMo::setup(array('allowDrop' => true));
		\HeiShaDr::setup(array('allowDrop' => true));
		\HeiShaMo::drop();
		\HeiShaDr::drop();
		$this->redirect('manage/heisha/mo', '清除成功');
	}

}
