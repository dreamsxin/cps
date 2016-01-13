<?php

namespace Manage;

class BillController extends ControllerBase {

	public function indexAction($page = 1) {
		\Phalcon\Tag::appendTitle('计费信息');
		$this->view->labels = \Delivery::labels();
		$conditions = array();
		$channel = $this->getUser('channel');
		if ($channel) {
			$conditions['msg'] = $channel;
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
	}

}

