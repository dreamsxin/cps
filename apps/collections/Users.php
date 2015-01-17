<?php

class Users extends \Phalcon\Mvc\Collection {

	public $username;
	public $password;
	public $fullname;
	public $phone;
	public $email;
	public $company;
	public $group;

	public function beforeSave() {
		$mode = $this->getOperationMade();
		if ($mode != self::OP_UPDATE) {
			$count = self::count(array(
						array('username' => $this->username)
			));
		} else {
			$count = self::count(array(
						array('_id' => array('$ne' => $this->getId()), 'username' => $this->username)
			));
		}

		if ($count) {
			$this->appendMessage('用户名已存在', 'username');
			return FALSE;
		}

		if ($this->username == 'admin') {		var_dump($this->group);exit;
			$this->group = '超级管理员';
		}
	}

	public static function labels() {
		return array(
			'username' => '账户名',
			'password' => '密码',
			'fullname' => '真实姓名',
			'phone' => '联系电话',
			'email' => '邮箱地址',
			'company' => '公司',
			'group' => '角色',
			'channel' => '渠道号',
		);
	}

	public static function groups() {
		return array(
			'商务人员' => '商务人员', // 只可以看到特定渠道 ID 的数据
			'信息费用户' => '信息费用户', // 可以看到 游戏名 渠道号 日期 信息费 分成比例 收益
			'CPS用户' => 'CPS 用户', // 可以看到 游戏名 渠道号 日期 信息费(可选)分成比例 收益
			'CPA用户' => 'CPA 用户', // 可以看到 游戏名 渠道号 日期 新增用户 CPA 单价 收益
			'管理员' => '管理员', // 可以看到所有数据,但不能修改任何数据
			'超级管理员' => '超级管理员', // 拥有所有的权限
		);
	}
}
