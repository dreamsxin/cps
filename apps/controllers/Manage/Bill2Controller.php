<?php

namespace Manage;

class Bill2Controller extends ControllerBase {

	public function indexAction() {
		\Phalcon\Tag::appendTitle('艾米Mo信息');
		$this->view->labels = \Mo2::labels();
		$page = $this->request->get('page', 'int', 1);
		$conditions = array();
		$operator = $this->request->get('operator');
		if ($operator) {
			$conditions['operator'] = $operator;
		}
		$countryISO = $this->request->get('countryISO');
		if ($countryISO) {
			$conditions['countryISO'] = $countryISO;
		}
		$moContent = $this->request->get('moContent');
		if ($moContent) {
			$conditions['moContent'] =  new \MongoRegex("/".$moContent."/");
		}
		$addtime = $this->request->get('addtime');
		if ($addtime) {
			$conditions['addtime'] =  new \MongoRegex("/^".$addtime."/");
		}

		$data = \Mo2::find(array(
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

		$keys = array('price' => 0);
		$this->view->totals = \Mo2::summatory($keys, $conditions);
	}

	public function dnAction($page = 1) {
		\Phalcon\Tag::appendTitle('艾米Dn信息');
		$this->view->labels = \Dn2::labels();
		$conditions = array();
		$operator = $this->request->get('operator');
		if ($reference) {
			$conditions['$or']['operator'] = $moContent;
		}
		$countryISO = $this->request->get('countryISO');
		if ($reference) {
			$conditions['$or']['countryISO'] = $moContent;
		}
		$moContent = $this->request->get('moContent');
		if ($reference) {
			$conditions['$or']['moContent'] = $moContent;
		}
		$addtime = $this->request->get('addtime');
		if ($reference) {
			$conditions['$or']['addtime'] = $reference;
		}

		$data = \Dn2::find(array(
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

		$keys = array('price' => 0);
		$this->view->totals = \Dn2::summatory($keys, $conditions);
	}

	public function dropAction() {
		if ($this->session->get('group') != '超级管理员') {
			$this->redirect('manage/bill2/index', '您没有超级管理员权限');
		}
		\Mo2::setup(array('allowDrop' => true));
		\Dn2::setup(array('allowDrop' => true));
		\Mo2::drop();
		\Dn2::drop();
		$this->redirect('manage/bill2/index', '清除成功');
	}

}

