<?php

class HeiShaController extends \Phalcon\Mvc\Controller {
	
	public function moAction() {
		if ($this->request->isPost()) {
			$mo = new \HeiShaMo();
			$mo->assign($this->request->getPost());
			if (!$mo->save()) {
				$this->response->setStatusCode(500, 'Server Error');
			}
		} else {
			$mo = new \HeiShaMo();
			$mo->assign($this->request->get());
			if (!$mo->save()) {
				$this->response->setStatusCode(500, 'Server Error');
			}
		}
		$this->response->send();exit;
	}

	public function drAction() {
		if ($this->request->isPost()) {
			$dn = new \HeiShaDr();
			$dn->assign($this->request->getPost());
			if (!$dn->save()) {
				$this->response->setStatusCode(500, 'Server Error');
			}
		} else if ($this->request->isGet()) {
			$dn = new \HeiShaDr();
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
