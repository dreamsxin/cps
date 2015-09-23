<?php
namespace Manage;

class AdController extends ControllerBase {

	public function beforeExecuteRoute($dispatcher) {
		parent::beforeExecuteRoute($dispatcher);
		
		if ($this->session->get('group') != '超级管理员') {
			$this->redirect('manage/index/index', '您没有超级管理员权限');
		}
	}

	public function initialize() {
		\Phalcon\Tag::appendTitle('广告管理');
	}
	
	public function indexAction() {
		$json = null;
		if ($this->request->isPost()) {
			$data = $_POST;
			$key = 'ad-'.md5(json_encode($data));
			$cache = $this->cache->get($key);
			if (empty($cache)) {
				$client = new \Phalcon\Http\Client\Adapter\Curl('http://api.c.avazunativeads.com/s2s?'.http_build_query($data));
				$response = $client->get();
				$cache = $response->getBody();
				if ($response->getStatusCode() == 200 ) {
					$this->cache->save($key, $cache);
				}
			}
			$json = json_decode($cache);
		}
		$this->view->json = $json;
	}
}

