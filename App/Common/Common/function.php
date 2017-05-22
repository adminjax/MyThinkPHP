<?php
/**
 * 辅助方法
 */
session('unique', 1);
//产生唯一id
function create_unique_id($type = 'int'){
	$time = time();
	switch ($type) {
		case 'int':
			$result = mt_rand(10000, 99999).$time.session('unique');
			break;

		default:
			$result = sha1($time.session('unique'));
			break;
	}

	if(session('unique') > 1500){
		session('unique', 1);
	}else{
		session('unique', session('unique')+1);
	}

	return $result;
}

?>