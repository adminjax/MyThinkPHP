<?php
return array(
	//'配置项'=>'配置值'
	//Skin
	'SKIN' => 'http://www.website.com/MyThinkPHP/Design/Default/Skin/Admin/',
	'PUBLIC_LIB' => 'http://www.website.com/MyThinkPHP/Public/lib/',
	'ADMIN_URL' => 'http://www.website.com/MyThinkPHP/Admin/',
	//后台menu
	'ADMIN_MENU' => array(
		'1' => array(
			'acl' => '1',
			'lable' => '控制面板',
			'name' => '',
			'sort' => 1,
		),
		'2' => array(
			'acl' => '2',
			'lable' => '系统设置',
			'name' => 'SystemSet',
			'sort' => 55,
			'low' => array(
				'2_1' => array(
					'acl' => '2_1',
					'lable' => '账户设置',
					'name' => 'Account',
					'sort' => 1,
					'path' => 'Account/Set',
				),
			),
		),			
	),
);
?>