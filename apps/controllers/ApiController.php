<?php

class ApiController extends \Phalcon\Mvc\Controller {


	public function deliveryAction() {
		if ($this->request->isPost()) {
			$delivery = new \Delivery();
			$delivery->assign($this->request->getPost());
			if (!$delivery->save()) {
				$this->response->setStatusCode(500, 'Server Error');
			}
		} else if ($this->request->isGet()) {
			$delivery = new \Delivery();
			$delivery->assign($this->request->getQuery());
			if (!$delivery->save()) {
				$this->response->setStatusCode(500, 'Server Error');
			}
		} else {
			$this->response->setStatusCode(400, 'Bad Request');
		}
		$this->response->send();exit;
	}
	
	public function moAction() {
		if ($this->request->isPost()) {
			$mo = new \Mo();
			$mo->assign($this->request->getPost());
			if (!$mo->save()) {
				$this->response->setStatusCode(500, 'Server Error');
			}
		} else {
			$this->response->setStatusCode(400, 'Bad Request');
		}
		$this->response->send();exit;
	}

}

