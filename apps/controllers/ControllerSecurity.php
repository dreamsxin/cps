<?php

abstract class ControllerSecurity extends ControllerBase {

	public function beforeExecuteRoute($dispatcher) {
		if (!$this->session->get('username') || !$this->getUser()) {
			$this->redirect('auth/index');
		} 

		parent::beforeExecuteRoute($dispatcher);
	}

}
