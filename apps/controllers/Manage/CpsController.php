<?php
namespace Manage;

class CpsController extends ControllerBase {

	public function initialize() {
		\Phalcon\Tag::appendTitle('CPS 数据');
	}
	
	public function indexAction($page = 1) {
		$group = $this->getUser('group');
		$this->view->labels = \Cps::labels($group);
		$channel = $this->getUser('channel');
		if (!empty($channel)) {
			$data = \Cps::find(array(
				array('渠道号' => $channel)
			));
		} else {
			$data = \Cps::find();
		}

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => $data,
				"limit"=> 50,
				"page" => $page
			)
		);

		$this->view->page = $paginator->getPaginate();
	}
	
	public function addAction() {
		if ($this->session->get('group') != '超级管理员') {
			$this->redirect('manage/index/index', '您没有超级管理员权限');
		}

		\Phalcon\Tag::appendTitle('录入');
		if ($this->request->isPost()) {
		}

		$this->view->pick('manage/cps/form');
		$this->view->title = '数据录入';
		$this->view->errors = array();
	}
	
	public function importAction() {
		if ($this->session->get('group') != '超级管理员') {
			$this->redirect('manage/index/index', '您没有超级管理员权限');
		}

		\Phalcon\Tag::appendTitle('导入');
		if ($this->request->hasFiles()) {
			foreach ($this->request->getUploadedFiles() as $file) {
				
				$file = fopen($file->getPathname(),"r");
				$fileds = null;
				while($row = fgetcsv($file)) {
					$row = array_map(function($field) {
						return mb_convert_encoding($field, "UTF-8", "GB2312");
					}, $row);
					if (empty($fileds)) {
						$fileds = $row;
					} else {
						$values = array();
						foreach($row as $key => $value) {
							if (isset($fileds[$key])) {
								$values[$fileds[$key]] = $value;
							}
						}
						if (!empty($values)) {
							$cps = new \Cps;
							$cps->save($values);
						}
						
					}
				}
				fclose($file);
            }
			
			$this->redirect('manage/cps/index', '导入成功');
		}

		$this->view->errors = array();
	}

}
