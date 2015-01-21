<?php

class AuthController extends ControllerBase {

	public function indexAction() {
		\Phalcon\Tag::appendTitle('登录');

		if ($this->request->isPost()) {
			$username = $this->request->getPost('username');
			$password = $this->request->getPost('password');
			
			$user = Users::findFirst(array(
				array('username' => $username),
			));

			if (!$user) {
				$this->error('登录失败');
			} else {
				if (!$this->security->checkHash($password, $user->password)) {
					$this->error('帐号和密码不匹配！');
				} else {
					$this->session->set('logintime', time());
					$this->session->sets($user->toArray());

					$this->redirect('manage/index/index', '登录成功');
				}
			}
		}
	}
	
	public function logoutAction() {
		$this->session->destroy();
		$this->redirect('index/index');
	}

	public function regAction() {
		\Phalcon\Tag::appendTitle('注册');

		$errors = array();

		if ($this->request->isPost()) {
			$username = $this->request->getPost('username', 'trim');
			$password = $this->request->getPost('password', 'trim');

			if (empty($username)) {
				$this->error('注册失败');
				$errors['username'] = '用户名不能为空';
			} elseif (strlen($password) < 6 || strlen($password) > 18) {
				$this->error('注册失败');
				$errors['password'] = '密码长度必须鉴于6到18位之间';
			} else {
				$user = new Users();
				$user->username = $username;
				$user->password = $this->security->hash($password);

				if ($user->save()) {
					$this->redirect('auth/index', '注册成功');
				} else {
					$this->error('注册失败');

					foreach ($user->getMessages() as $message) {
						$errors[$message->getField()] = $message->getMessage();
					}
				}
			}
		}

		$this->view->setVar('errors', $errors);
	}

}
