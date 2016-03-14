<?php

namespace Manage;

class BillController extends ControllerBase {

	public function indexAction($page = 1) {
		\Phalcon\Tag::appendTitle('计费信息');
		$this->view->labels = \Delivery::labels();
		$conditions = array();
		$reference = $this->request->get('reference');
		if ($reference) {
			$conditions['reference'] = $reference;
		}

		$data = \Delivery::find(array(
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

		$keys = array('enduserprice' => 0);
		$this->view->totals = \Delivery::summatory($keys, $conditions);
	}

	public function moAction($page = 1) {
		\Phalcon\Tag::appendTitle('计费Mo信息');
		$this->view->labels = \Mo::labels();
		$conditions = array();
		$reference = $this->request->get('reference');
		if ($reference) {
			$conditions['reference'] = $reference;
		}

		$data = \Mo::find(array(
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

		$keys = array('enduserprice' => 0);
		$this->view->totals = \Delivery::summatory($keys, $conditions);
	}

	public function dropAction() {
		if ($this->session->get('group') != '超级管理员') {
			$this->redirect('manage/bill/index', '您没有超级管理员权限');
		}

		\Mo::setup(array('allowDrop' => true));
		\Delivery::setup(array('allowDrop' => true));
		\Mo::drop();
		\Delivery::drop();
		$this->redirect('manage/bill/index', '清除成功');
	}

}

