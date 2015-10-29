<?php

class Cps extends \Phalcon\Mvc\Collection {

	public function beforeSave() {
		foreach(self::labels() as $key => $label) {
			$value = $this->{$key};
			if (is_numeric($value)) {
				$this->{$key} = (float)$value;
			} else if (Phalcon\Date::valid($value)) {
				$this->{$key} = new MongoDate(strtotime($value));
			}
		}
	}

	public function afterFetch() {
		foreach(self::labels() as $key => $label) {
                        $value = $this->{$key};
			if ($value instanceof MongoDate) {
				$this->{$key} = date('Y-m-d', $value->sec);
			}
		}
	}

	public function getColumnMap() {
		return array(
			'_id' => '_id',
			'公司名' => '公司名',
			'应用名称' => '应用名称',
			'游戏名称' => '游戏名称',
			'合作模式' => '合作模式',
			'渠道名称' => '渠道名称',
			'日期' => '日期',
			'CPA/CPD单价' => 'CPA/CPD单价',
			'调整后CPA/CPD单价' => '调整后CPA/CPD单价',
			'新增用户数' => '新增用户数',
			'调整后新增用户' => '调整后新增用户',
			'厂商收益' => '厂商收益',
			'利润' => '利润',
			'CPT折扣' => 'CPT折扣',
			'调整后CPT折扣' => '调整后CPT折扣',
			'分成比例' => '分成比例',
			'调整后分成比例' => '调整后分成比例',
			'信息费' => '信息费',
			'调整后信息费' => '调整后信息费',
			'总付费流水' => '总付费流水',
			'调整后总付费流水' => '调整后总付费流水',
			'支付方' => '支付方',
			'支付通道' => '支付通道',
			'通道成本' => '通道成本',
			'坏账' => '坏账',
			'支付方税金' => '支付方税金',
			'备注' => '备注',
			'结算方式' => '结算方式',
			'渠道接口人' => '渠道接口人',
		);
	}

	public static function labels($group = NULL) {
		if ($group == 'CPA/CPD用户') {
			return array(
				'应用名称' => '应用名称',
				'合作模式' => '合作模式',
				'日期' => '日期',
				'调整后CPA/CPD单价' => 'CPA/CPD单价',
				'调整后新增用户' => '新增用户数',
				'厂商收益' => '收益',
			);
		} elseif ($group == 'CPS用户') {
			return array(
				'游戏名称' => '游戏名称',
				'合作模式' => '合作模式',
				'日期' => '日期',
				'调整后信息费' => '信息费',
				'调整后分成比例' => '分成比例',
				'厂商收益' => '收益',
			);
		} elseif ($group == '信息费用户') {
			return array(
				'游戏名称' => '游戏名称',
				'渠道名称' => '渠道名称',
				'日期' => '日期',
				'调整后信息费' => '信息费',
				'厂商分成比列' => '分成比例',
				'厂商收益' => '收益',
			);
		}

                return array(
                        '公司名' => '公司名',
                        '应用名称' => '应用名称',
                        '游戏名称' => '游戏名称',
                        '合作模式' => '合作模式',
                        '渠道名称' => '渠道名称',
                        '日期' => '日期',
                        'CPA/CPD单价' => 'CPA/CPD单价',
                        '调整后CPA/CPD单价' => '调整后CPA/CPD单价',
                        '新增用户数' => '新增用户数',
                        '调整后新增用户' => '调整后新增用户',
                        '厂商收益' => '厂商收益',
                        '利润' => '利润',
                        'CPT折扣' => 'CPT折扣',
                        '调整后CPT折扣' => '调整后CPT折扣',
                        '分成比例' => '分成比例',
                        '调整后分成比例' => '调整后分成比例',
                        '信息费' => '信息费',
                        '调整后信息费' => '调整后信息费',
                        '总付费流水' => '总付费流水',
                        '调整后总付费流水' => '调整后总付费流水',
                        '支付方' => '支付方',
                        '支付通道' => '支付通道',
                        '通道成本' => '通道成本',
                        '坏账' => '坏账',
                        '支付方税金' => '支付方税金',
                        '备注' => '备注',
                        '结算方式' => '结算方式',
                        '渠道接口人' => '渠道接口人',
                );
			$my = new self();
			return $my->getColumnMap();
	}

}
