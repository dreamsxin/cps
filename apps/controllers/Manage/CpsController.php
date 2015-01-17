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

		$conditions = array();
		if (!empty($channel)) {
			$conditions['渠道号'] = $channel;
		}

		$t = $this->request->get('t');
		$q = $this->request->get('q');
		if (!empty($t) && !empty($q)) {
			$conditions[$t] = new \MongoRegex('/'.trim($q, '/').'/i');
		}

		$data = \Cps::find(array(
			$conditions
		));

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
			$cps = new \Cps();
			$cps->assign($this->request->getPost(NULL, 'trim'));

			if ($cps->save()) {
				$this->redirect('manage/cps/index', '录入成功');
			} else {
				$this->error('录入失败');

				foreach ($user->getMessages() as $message) {
					$errors[$message->getField()] = $message->getMessage();
				}
			}
		}

		$this->view->labels = \Cps::labels();
		$this->view->pick('manage/cps/form');
		$this->view->title = '数据录入';
		$this->view->errors = array();
		$this->view->data = $_POST;
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
	
	public function editAction($id) {
		\Phalcon\Tag::appendTitle('CPS 编辑');
		$errors = array();
		$cps = \Cps::findFirst($id);
		if ($this->request->isPost()) {
			$cps->assign($this->request->getPost(NULL, 'trim'));
			if ($cps->save()) {
				$this->redirect('manage/cps/index', '编辑成功');
			} else {
				$this->error('编辑失败');

				foreach ($user->getMessages() as $message) {
					$errors[$message->getField()] = $message->getMessage();
				}
			}
		}

		$this->view->labels = \Cps::labels();
		$this->view->pick('manage/user/form');
		$this->view->title = 'CPS 编辑';
		$this->view->errors = $errors;
		$this->view->data = $cps->toArray();
	}

	public function delAction($id) {
		if (empty($id)) {
			$this->redirect('manage/cps/index', '删除失败');
		}

		$cps = \Cps::findFirst($id);
		$cps->delete();
		$this->redirect('manage/cps/index', '删除成功');
	}

}
