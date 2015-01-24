<?php

namespace Manage;

class CpsController extends ControllerBase {

	public function initialize() {
		\Phalcon\Tag::appendTitle('CPS 数据');
	}

	public function indexAction($page = 1) {
		$group = $this->getUser('group');
		$this->view->labels = \Cps::labels($group);
		$conditions = array();
		if ($this->getUser('group') == '商务人员') {
			$channel = $this->getUser('channel');
			$fullname = $this->getUser('fullname');
			if (!empty($fullname)) {
				$conditions['$or'] = array(
					array('渠道接口人' => $fullname),
					array('渠道号' => $channel)
				);
			} else {
				$conditions['渠道号'] = $channel;
			}
		} elseif (in_array($this->getUser('group'), array('信息费用户', 'CPA用户', 'CPS用户'))) {
			$company = $this->getUser('company');
			if (!empty($company)) {
				$conditions['厂商名称'] = $company;
			}
		}

		$v = $this->request->get('渠道号');
		if (!empty($v)) {
			$conditions['渠道号'] = $v;
		}

		$v = $this->request->get('厂商名称');
		if (!empty($v)) {
			$conditions['厂商名称'] = $v;
		}

		$v = $this->request->get('日期');
		if (!empty($v)) {
			$conditions['日期'] = $v;
		}

		$t = $this->request->get('t');
		$q = $this->request->get('q');
		if (!empty($t) && !empty($q)) {
			$conditions[$t] = new \MongoRegex('/' . trim($q, '/') . '/i');
		}

		$data = \Cps::find(array(
					$conditions,
					'sort' => array('_id' => -1)
		));

		$paginator = new \Phalcon\Paginator\Adapter\Model(
				array(
			"data" => $data,
			"limit" => 50,
			"page" => $page
				)
		);

		$this->view->page = $paginator->getPaginate();
		
		$keys = array();
		foreach ($this->view->labels as $key => $label) {
			if (strpos($label, '收益') !== false) {
				$keys[$key] = 0;
			}
		}

		$this->view->totals = \Cps::summatory($keys, $conditions);
	}

	public function groupAction($page = 1) {
		$group = $this->getUser('group');
		$lables = array();
		if ($group == '信息费用户') {
			$lables = array(
				'游戏名称' => '游戏名称',
				'渠道号' => '渠道号',
				'日期' => '日期',
				'渠道号' => '渠道号',
				'CP收益' => 'CP收益',
			);
		} elseif ($group == 'CPS用户') {
			$lables = array(
				'游戏名称' => '游戏名称',
				'渠道号' => '渠道号',
				'日期' => '日期',
				'渠道号' => '渠道号',
				'CP收益' => 'CP收益',
			);
		} elseif ($group == 'CPA用户') {
			$lables = array(
				'游戏名称' => '游戏名称',
				'渠道号' => '渠道号',
				'日期' => '日期',
				'渠道号' => '渠道号',
				'CP收益' => 'CP收益',
				'CPA单价' => 'CPA单价',
			);
		} else {
			$lables = array(
				'游戏名称' => '游戏名称',
				'渠道号' => '渠道号',
				'日期' => '日期',
				'渠道号' => '渠道号',
				'CP收益' => 'CP收益',
				'CPA单价' => 'CPA单价',
			);
		}
		$this->view->labels = $lables;
		$conditions = array();
		if ($this->getUser('group') == '商务人员') {
			$channel = $this->getUser('channel');
			$fullname = $this->getUser('fullname');
			if (!empty($fullname)) {
				$conditions['$or'] = array(
					array('渠道接口人' => $fullname),
					array('渠道号' => $channel)
				);
			} else {
				$conditions['渠道号'] = $channel;
			}
                } elseif (in_array($this->getUser('group'), array('信息费用户', 'CPA用户', 'CPS用户'))) {
                        $company = $this->getUser('company');
                        if (!empty($company)) {
                                $conditions['厂商名称'] = $company;
                        }
                }

		$v = $this->request->get('渠道号');
		if (!empty($v)) {
			$conditions['渠道号'] = $v;
		}

		$v = $this->request->get('厂商名称');
		if (!empty($v)) {
			$conditions['厂商名称'] = $v;
		}

		$v = $this->request->get('日期');
		if (!empty($v)) {
			$conditions['日期'] = $v;
		}

		if (empty($conditions)) {
			$data = \Cps::aggregate(array(
						array(
							'$group' => array(
								'_id' => array('游戏名称' => '$游戏名称', '渠道号' => '$渠道号', '日期' => '$日期'),
								'CP收益' => array('$sum' => '$CP收益'),
								'游戏名称' => array('$first' => '$游戏名称'),
								'渠道号' => array('$first' => '$渠道号'),
								'CPA单价' => array('$first' => '$CPA单价'),
								'日期' => array('$first' => '$日期'),
								'sortid' => array('$last' => '$_id'),
							),
						),
						array(
							'$sort' => array("sortid" => -1),
						),
			));
		} else {
			$data = \Cps::aggregate(array(
						array(
							'$match' => $conditions
						),
						array(
							'$group' => array(
								'_id' => array('游戏名称' => '$游戏名称', '渠道号' => '$渠道号', '日期' => '$日期'),
								'CP收益' => array('$sum' => '$CP收益'),
								'游戏名称' => array('$first' => '$游戏名称'),
								'渠道号' => array('$first' => '$渠道号'),
								'CPA单价' => array('$first' => '$CPA单价'),
								'日期' => array('$first' => '$日期'),
								'sortid' => array('$last' => '$_id'),
							),
						),
						array(
							'$sort' => array("sortid" => -1),
						),
			));
		}

		$paginator = new \Phalcon\Paginator\Adapter\NativeArray(
				array(
			"data" => $data['result'],
			"limit" => 50,
			"page" => $page
				)
		);

		$this->view->page = $paginator->getPaginate();
		
		$keys = array();
		foreach ($lables as $key => $label) {
			if (strpos($label, '收益') !== false) {
				$keys[$key] = 0;
			}
		}

		$this->view->totals = \Cps::summatory($keys, $conditions);
	}

	public function addAction() {
		if ($this->session->get('group') != '超级管理员') {
			$this->redirect('manage/index/index', '您没有超级管理员权限');
		}

		\Phalcon\Tag::appendTitle('录入');
		if ($this->request->isPost()) {
			$cps = new \Cps();
			$cps->assign($this->request->getPost(NULL, 'trim'));

			if ($cps->save()) {
				$this->redirect('manage/cps/index', '录入成功');
			} else {
				$this->error('录入失败');

				foreach ($user->getMessages() as $message) {
					$errors[$message->getField()] = $message->getMessage();
				}
			}
		}

		$this->view->labels = \Cps::labels();
		$this->view->pick('manage/cps/form');
		$this->view->title = '数据录入';
		$this->view->errors = array();
		$this->view->data = $_POST;
	}

	public function importAction() {
		if ($this->session->get('group') != '超级管理员') {
			$this->redirect('manage/index/index', '您没有超级管理员权限');
		}

		\Phalcon\Tag::appendTitle('导入');
		if ($this->request->hasFiles()) {
			foreach ($this->request->getUploadedFiles() as $file) {

				$file = fopen($file->getPathname(), "r");
				$fileds = null;
				while ($row = fgetcsv($file)) {
					$row = array_filter($row);
					if (empty($row)) {
						continue;
					}
					$row = array_map(function($field) {
						return trim(mb_convert_encoding($field, "UTF-8", "GB2312"));
					}, $row);
					if (empty($fileds)) {
						$fileds = $row;
					} else {
						$values = array();
						foreach ($row as $key => $value) {
							if (isset($fileds[$key])) {
								if (is_numeric($value)) {
									$values[$fileds[$key]] =  strpos($value, ".") !== false ? floatval($value) : intval($value);
								} else {
									$values[$fileds[$key]] = $value;
								}
							}
						}
						if (!empty($values)) {
							$cps = new \Cps;
							$cps->save($values);
						}
					}
				}
				fclose($file);
			}

			$this->redirect('manage/cps/index', '导入成功');
		}

		$this->view->errors = array();
	}

	public function editAction($id) {
		if ($this->session->get('group') != '超级管理员') {
			$this->redirect('manage/index/index', '您没有超级管理员权限');
		}

		\Phalcon\Tag::appendTitle('CPS 编辑');
		$errors = array();
		$cps = \Cps::findFirst($id);
		if ($this->request->isPost()) {
			$cps->assign($this->request->getPost(NULL, 'trim'));
			if ($cps->save()) {
				$this->redirect('manage/cps/index', '编辑成功');
			} else {
				$this->error('编辑失败');

				foreach ($user->getMessages() as $message) {
					$errors[$message->getField()] = $message->getMessage();
				}
			}
		}

		$this->view->labels = \Cps::labels();
		$this->view->users = \Users::find(array(
						//array('group' => '商务人员')
		));
		$this->view->pick('manage/user/form');
		$this->view->title = 'CPS 编辑';
		$this->view->errors = $errors;
		$this->view->data = $cps->toArray();
	}

	public function delAction($id) {
                if ($this->session->get('group') != '超级管理员') {
                        $this->redirect('manage/index/index', '您没有超级管理员权限');
                }

		if (empty($id)) {
			$this->redirect('manage/cps/index', '删除失败');
		}

		$cps = \Cps::findFirst($id);
		$cps->delete();
		$this->redirect('manage/cps/index', '删除成功');
	}

	public function dropAction() {
                if ($this->session->get('group') != '超级管理员') {
                        $this->redirect('manage/index/index', '您没有超级管理员权限');
                }

		\Cps::drop();
		$this->redirect('manage/cps/index', '清除成功');
	}
}
