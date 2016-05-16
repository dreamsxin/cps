<?php

class HeishaController extends \Phalcon\Mvc\Controller {
	
	public function moAction() {
		if ($this->request->isPost()) {
			$mo = new \HeiShaMo();
			$mo->assign($this->request->getPost());
			if (!$mo->save()) {
				$this->response->setStatusCode(500, 'Server Error');
				$this->response->setContent("500")->send();exit;
			}
		} else {
			$mo = new \HeiShaMo();
			$mo->assign($this->request->get());
			if (!$mo->save()) {
				$this->response->setStatusCode(500, 'Server Error');
				$this->response->setContent("500")->send();exit;
			}
		}
		$this->response->setContent("200")->send();exit;
	}

	public function drAction() {
		if ($this->request->isPost()) {
			$data = $this->request->getPost();
			if (empty($data)) {
				$this->response->setStatusCode(400, 'Bad Params');
				$this->response->setContent("202")->send();exit;
			}
			$dn = new \HeiShaDr();
			$dn->assign($data);
			if (!$dn->save()) {
				$this->response->setStatusCode(500, 'Server Error');
				$this->response->setContent("500")->send();exit;
			}
		} else if ($this->request->isGet()) {
			$data = $this->request->get();
			if (empty($data)) {
				$this->response->setStatusCode(400, 'Bad Params');
				$this->response->setContent("202")->send();exit;
			}
			$dn = new \HeiShaDr();
			$dn->assign($data);
			if (!$dn->save()) {
				$this->response->setStatusCode(500, 'Server Error');
				$this->response->setContent("500")->send();exit;
			}
		} else {
			$this->response->setStatusCode(400, 'Bad Request');
			$this->response->setContent("400")->send();exit;
		}
		$this->response->setContent("200")->send();exit;
	}

}
