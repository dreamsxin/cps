<?php

class ApiController extends \Phalcon\Mvc\Controller {


	public function deliveryAction() {
		if ($this->request->isPost()) {
			$delivery = new \Delivery();
			$delivery->assign($this->request->getPost());
			if (!$delivery->save()) {
				$response->setStatusCode(500);
			}
		} else {
			$response->setStatusCode(400);
		}
	}
	
	public function moAction() {
		if ($this->request->isPost()) {
			$mo = new \Mo();
			$mo->assign($this->request->getPost());
			if (!$mo->save()) {
				$response->setStatusCode(500);
			}
		} else {
			$response->setStatusCode(400);
		}
	}

}
