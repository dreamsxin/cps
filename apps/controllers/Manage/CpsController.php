<?php
namespace Manage;

class CpsController extends ControllerBase {

	public function initialize() {
		\Phalcon\Tag::appendTitle('CPS 数据');
	}
	
	public function indexAction($page = 0) {
		$group = $this->getUser('group');
		$this->view->labels = \Cps::labels($group);
		$channel = $this->getUser('channel');
		if (!empty($channel)) {
			$this->view->result = \Cps::find(array(
				array('渠道号' => $channel),
				'limit' => 50,
				'skip' => (int)$page * 50
			));
		} else {
			$this->view->result = \Cps::find(array(
				'limit' => 50,
				'skip' => (int)$page * 50
			));
		}
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
