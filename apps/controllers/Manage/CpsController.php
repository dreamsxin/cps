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
					array('渠道名称' => $channel)
				);
			} else {
				$conditions['渠道名称'] = $channel;
			}
		} elseif (in_array($this->getUser('group'), array('信息费用户', 'CPA/CPD用户', 'CPS用户'))) {
			$company = $this->getUser('company');
			if (!empty($company)) {
				$conditions['公司名'] = $company;
			}
		}

		$v = $this->request->get('渠道名称');
		if (!empty($v)) {
			$conditions['渠道名称'] = $v;
		}

		$v = $this->request->get('公司名');
		if (!empty($v)) {
			$conditions['公司名'] = $v;
		}

		$sd = $this->request->get('sd');
		$ed = $this->request->get('ed');
		if (!empty($sd)) {
			$conditions['日期'] = array('$gt' => new \MongoDate(strtotime($sd)), '$lte' => new \MongoDate(strtotime($ed)));
		} else if (!empty($ed)) {
			$conditions['日期'] = array('$lte' => new \MongoDate(strtotime($ed)));
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
		$lables = \Cps::labels($group);
		$this->view->labels = $lables;
		$conditions = array();
		if ($this->getUser('group') == '商务人员') {
			$channel = $this->getUser('channel');
			$fullname = $this->getUser('fullname');
			if (!empty($fullname)) {
				$conditions['$or'] = array(
					array('渠道接口人' => $fullname),
					array('渠道名称' => $channel)
				);
			} else {
				$conditions['渠道名称'] = $channel;
			}
                } elseif (in_array($this->getUser('group'), array('信息费用户', 'CPA/CPD用户', 'CPS用户'))) {
                        $company = $this->getUser('company');
                        if (!empty($company)) {
                                $conditions['公司名'] = $company;
                        }
                }

		$v = $this->request->get('渠道名称');
		if (!empty($v)) {
			$conditions['渠道名称'] = $v;
		}

		$v = $this->request->get('公司名');
		if (!empty($v)) {
			$conditions['公司名'] = $v;
		}

                $sd = $this->request->get('sd');
                $ed = $this->request->get('ed');
                if (!empty($sd)) {
                        $conditions['日期'] = array('$gt' => new \MongoDate(strtotime($sd)), '$lte' => new \MongoDate(strtotime($ed)));
                } else if (!empty($ed)) {
                        $conditions['日期'] = array('$lte' => new \MongoDate(strtotime($ed)));
                }

		$query = array(
			array(
				'$group' => array(
					'_id' => array('游戏名称' => '$游戏名称', '渠道名称' => '$渠道名称', '日期' => '$日期'),
					'厂商收益' => array('$sum' => '$厂商收益'),
					'新增用户数' => array('$sum' => '$新增用户数'),
					'调整后新增用户' => array('$sum' => '$调整后新增用户'),
					'利润' => array('$sum' => '$利润'),
					'信息费' => array('$sum' => '$信息费'),
					'调整后信息费' => array('$sum' => '$调整后信息费'),
					'总付费流水' => array('$sum' => '$总付费流水'),
					'调整后总付费流水' => array('$sum' => '$调整后总付费流水'),
					'坏账' => array('$sum' => '$坏账'),
					'公司名' => array('$first' => '$公司名'),
					'应用名称' => array('$first' => '$应用名称'),
					'游戏名称' => array('$first' => '$游戏名称'),
					'渠道名称' => array('$first' => '$渠道名称'),
					'CPA/CPD单价' => array('$first' => '$CPA/CPD单价'),
					'调整后CPA/CPD单价' => array('$first' => '$调整后CPA/CPD单价'),
					'日期' => array('$first' => '$日期'),
					'sortid' => array('$last' => '$_id'),
				),
			),
			array(
				'$sort' => array("sortid" => -1),
			),
		);
		if (!empty($conditions)) {
			$query[] = array('$match' => $conditions);
		}
		$data = \Cps::aggregate($query);
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
		//		$keys[$key] = 0;
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
		if (!$cps) {
			$cps = new \Cps;
		}
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
		\Cps::setup(array('allowDrop' => true));
		\Cps::drop();
		$this->redirect('manage/cps/index', '禁止清除');
	}
}
