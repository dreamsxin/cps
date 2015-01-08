<?php

class IdCard
{
	// 计算身份证校验码，根据国家标准GB 11643-1999
	public static function idcard_verify_number($idcard_base) 
	{
		if (strlen($idcard_base) != 17) {
			return FALSE;
		}
		// 加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

		// 校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');

		$checksum = 0;
		for ($i = 0; $i < strlen($idcard_base); $i++) {
			$checksum += substr($idcard_base, $i, 1) * $factor[$i];
		}

		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];

		return $verify_number;
	}

	public static function idcard_15to18($idcard)
	{
		if (strlen($idcard) == 18) {
			return $idcard;
		} else if (strlen($idcard) != 15) {
			return $idcard;
		}

		// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
		if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false) {
			$idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
		} else {
			$idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
		}

		return $idcard . self::idcard_verify_number($idcard);
	}

	public static function idcardCheckSum18($idcard) 
	{
		if (strlen($idcard) != 18) {
			return FALSE;
		}
		$idcard_base = substr($idcard, 0, 17);
		if (self::idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public static function check($idcard) {
		if ((strlen($idcard) == 15)) {
			$idcard = self::idcard_15to18($idcard);
		}
		return self::idcardCheckSum18($idcard);
	}

	public static function parse($idcard)
	{
		if (!self::check($idcard)) {
			return FALSE;
		}

		$pattern = '/^([1-9]\d)(\d{2})(\d{2})(\d{4})(\d{2})(\d{2})\d{2}(\d)[\dx]$/i';
		if (!preg_match($pattern, $idcard, $matches)) {
			return FALSE;
		}
		$result = array('idnum' => NULL, 'province' => NULL, 'city' => NULL, 'area' => NULL, 'year' => NULL, 'month' => NULL, 'day' => NULL, 'sex' => NULL);
		return array_combine(array_keys($result), $matches);
	}

}

