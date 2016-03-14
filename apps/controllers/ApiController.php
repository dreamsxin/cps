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
			$delivery->assign($this->request->get());
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
	
	public function mo2Action() {
		if ($this->request->isPost()) {
			$mo = new \Mo2();
			$mo->assign($this->request->getPost());
			if (!$mo->save()) {
				$this->response->setStatusCode(500, 'Server Error');
			}
		} else {
			$mo = new \Mo2();
			$mo->assign($this->request->get());
			if (!$mo->save()) {
				$this->response->setStatusCode(500, 'Server Error');
			}
		}
		$this->response->send();exit;
	}

	public function dn2Action() {
		if ($this->request->isPost()) {
			$dn = new \Dn2();
			$dn->assign($this->request->getPost());
			if (!$dn->save()) {
				$this->response->setStatusCode(500, 'Server Error');
			}
		} else if ($this->request->isGet()) {
			$dn = new \Dn2();
			$dn->assign($this->request->get());
			if (!$dn->save()) {
				$this->response->setStatusCode(500, 'Server Error');
			}
		} else {
			$this->response->setStatusCode(400, 'Bad Request');
		}
		$this->response->send();exit;
	}

}

