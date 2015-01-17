<?php

abstract class ControllerBase extends \Phalcon\Mvc\Controller {
	
	private static $user;

	public function beforeExecuteRoute($dispatcher) {
		\Phalcon\Tag::setTitleSeparator('·');
		\Phalcon\Tag::setTitle('CPS 数据中心');
	}

	public function getUser($field = NULL) {
		if ($this->session->get('username')) {
			if (!self::$user) {
				self::$user = Users::findFirstByUsername($this->session->get('username'));
				$this->session->sets(self::$user->toArray());
			}
		}
		
		if ($field && self::$user) {
			return self::$user->{$field};
		}
		return self::$user;
	}

	public function redirect($url, $message = NULL) {
		if (!empty($message)) {
			$this->notify($message);
		}
		$this->response->redirect($url)->send();
		exit;
	}

	public function sendData($data, $message = NULL) {
		$this->send(array('data' => $data, 'message' => $message, 'status' => 'ok'));
	}

	public function sendSuccess($message = '操作成功') {
		$this->send(array('message' => $message, 'status' => 'ok'));
	}

	public function sendError($message = '操作失败') {
		$this->send(array('message' => $message, 'status' => 'error'), 400);
	}

	public function send($content = NULL, $status = 200, $message = NULL) {
		$this->response->setContentType('application/json;charset=utf-8');
		$this->response->setJsonContent($content, JSON_UNESCAPED_UNICODE);
		if ($status) {
			$this->response->setStatusCode($status, $message);
		}
		$this->response->send();
		exit;
	}

	public function error($message) {
		$this->flashSession->error($message);
	}

	public function success($message) {
		$this->flashSession->success($message);
	}

	public function notify($message, $type = 'success') {
		$this->flashSession->message('notify', '<script>$.notify("'.$message.'", "'.$type.'");</script>');
	}

}
