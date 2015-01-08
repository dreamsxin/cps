<?php
namespace Manage;

class AccountController extends ControllerBase {

	public function initialize() {
		\Phalcon\Tag::appendTitle('账户');
	}
	
	public function indexAction() {
	}
	
	public function passwordAction() {
		if ($this->request->isPost()) {
			$user = $this->getUser();
			$old_password = $this->request->getPost('old-password', 'trim');
			$password = $this->request->getPost('password', 'trim');
			if (strlen($password) < 6 || strlen($password) > 18) {
				$this->error('密码修改失败');
				$errors['password'] = '密码长度必须鉴于6到18位之间';
			} else if (!$this->security->checkHash($password, $user->password)) {
				$this->error('原始密码错误！');
			} else {
				$user->password = $this->security->hash($password);
				if ($user->save()) {
					$this->success('密码修改成功');
				} else {
					$this->error('密码修改失败');
				}
				
			}
		}
	}

}
