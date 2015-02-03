<?php
namespace Manage;

class UserController extends ControllerBase {

	public function beforeExecuteRoute($dispatcher) {
		parent::beforeExecuteRoute($dispatcher);
		
		if ($this->session->get('group') != '超级管理员') {
			$this->redirect('manage/index/index', '您没有超级管理员权限');
		}
	}

	public function initialize() {
		\Phalcon\Tag::appendTitle('用户管理');
	}
	
	public function indexAction($page = 1) {
		$this->view->labels = \Users::labels();
		$data = \Users::find(array(
			array('username' => array('$ne' => 'root'))
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
		\Phalcon\Tag::appendTitle('用户录入');
		$errors = array();
		if ($this->request->isPost()) {
			$username = $this->request->getPost('username', 'trim');
			$password = $this->request->getPost('password', 'trim');

			if (empty($username)) {
				$this->error('录入失败');
				$errors['username'] = '用户名不能为空';
			} elseif (empty($password)) {
				$this->error('录入失败');
				$errors['password'] = '密码不能为空';
			} else {
				$user = new \Users();
				$user->assign($this->request->getPost(NULL, 'trim'));
				$user->password = $this->security->hash($password);

				if ($user->save()) {
					$this->redirect('manage/user/index', '录入成功');
				} else {
					$this->error('录入失败');

					foreach ($user->getMessages() as $message) {
						$errors[$message->getField()] = $message->getMessage();
					}
				}
			}
		}

		$this->view->labels = \Users::labels();
		$this->view->groups = \Users::groups();
		$this->view->pick('manage/user/form');
		$this->view->title = '用户录入';
		$this->view->errors = $errors;
		$this->view->data = $_POST;
	}
	
	public function editAction($id) {
		\Phalcon\Tag::appendTitle('用户编辑');
		$errors = array();
		$user = \Users::findFirst($id);
		if ($this->request->isPost()) {
			$username = $this->request->getPost('username', 'trim');
			$password = $this->request->getPost('password', 'trim');

			if (empty($username)) {
				$this->error('编辑失败');
				$errors['username'] = '用户名不能为空';
			} else {
				$data = $this->request->getPost(NULL, 'trim');
				unset($data['password']);
				$user->assign($data);
				if (!empty($password)) {
					$user->password = $this->security->hash($password);
				} 

				if ($user->save()) {
					$this->redirect('manage/user/index', '编辑成功');
				} else {
					$this->error('编辑失败');

					foreach ($user->getMessages() as $message) {
						$errors[$message->getField()] = $message->getMessage();
					}
				}
			}
		}

		$this->view->labels = \Users::labels();
		$this->view->groups = \Users::groups();
		$this->view->pick('manage/user/form');
		$this->view->title = '用户编辑';
		$this->view->errors = $errors;
		$this->view->data = $user->toArray();
	}

	public function delAction($id) {
		if (empty($id)) {
			$this->redirect('manage/user/index', '删除失败');
		}

		$user = \Users::findFirst($id);
		if ($user && $user->username != 'root') {
			$user->delete();
		}
		$this->redirect('manage/user/index', '删除成功');
	}
}
