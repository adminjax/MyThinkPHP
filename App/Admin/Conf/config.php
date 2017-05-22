<?php
return array(
	//'配置项'=>'配置值'
	//后台menu
	'ADMIN_MENU' => array(
		'1' => array(
			'name' => '资讯页面管理',
			'code' => 'Info',
			'sort' => '',
			'low'  => array(
				/*'1_1' => array(
						'name' => 'BANNER编辑',
						'code' => 'Orders',
						'path' => 'Info/notice',
						'sort' => '',
						'low' => array(
							'1_1_1' => array(
									'code' => 'add',
									'name' => '添加公告',
									'path' => 'Notice/add'
								),
							'1_1_2' => array(
									'code' => 'delete',
									'name' => '删除公告',
									'path' => 'Notice/delete'
								),
							'1_1_3' => array(
									'code' => 'edit',
									'name' => '编辑公告',
									'path' => 'Notice/edit'
								),
							'1_1_4' => array(
									'code' => 'save',
									'name' => '保存并提交至审核',
									'path' => 'Notice/save'
								),
							'1_1_5' => array(
									'code' => 'audit',
									'name' => '公告审核',
									'path' => 'Notice/audit'
								),
							),
					),*/
					'1_2' => array(
						'name' => '专题编辑',
						'code' => 'Shipments',
						'path' => 'Info/subject',
						'sort' => '',
						'low' => array(
							'1_2_1' => array(
									'code' => 'add',
									'name' => '添加专题',
									'path' => 'Info/add'
								),
							'1_2_2' => array(
									'code' => 'delete',
									'name' => '删除专题',
									'path' => 'Info/delete'
								),
							'1_2_3' => array(
									'code' => 'edit',
									'name' => '编辑专题',
									'path' => 'Info/edit'
								),
							'1_2_4' => array(
									'code' => 'save',
									'name' => '保存并提交至审核',
									'path' => 'Info/save'
								),
							'1_2_5' => array(
									'code' => 'audit',
									'name' => '专题审核',
									'path' => 'Info/audit'
								)
							),
					),
					'1_3' => array(
						'name' => '标签编辑',
						'code' => 'Tag',
						'path' => 'Tag/manage',
						'sort' => '',
						'low' => array(
							'1_3_1' => array(
									'code' => 'edit',
									'name' => '修改标签',
									'path' => 'Tag/editTag'
								),
							'1_3_2' => array(
									'code' => 'delete',
									'name' => '删除标签',
									'path' => 'Tag/delTag'
								),
							'1_3_3' => array(
									'code' => 'add',
									'name' => '添加标签',
									'path' => 'Tag/addTag'
								)
						),
					),
					'1_4' => array(
						'name' => '审核',
						'code' => 'Shipments',
						'path' => 'Info/audit',
						'sort' => '',
						'low' => array(
							'1_4_1' => array(
								'code' => 'refuse',
								'name' => '拒绝',
								'path' => 'Info/refuse'
							),
							'1_4_2' => array(
								'code' => 'adopt',
								'name' => '通过',
								'path' => 'Info/adopt'
							),'1_4_3' => array(
								'code' => 'refuse_sub',
								'name' => '拒绝',
								'path' => 'Info/refuse'
							),
							'1_4_4' => array(
								'code' => 'adopt_sub',
								'name' => '通过',
								'path' => 'Info/adopt'
							),
						),
					),
				),
		),
		'2' => array(
			'name' => '积分商城',
			'code' => 'Integral',
			'low'  => array(
					'2_1' => array(
						'name' => '商品编辑',
						'code' => 'Integral/goodsEdit',
						'path' => 'Integral/goodsEdit',
						'low' => array(
							'2_1_1' => array(
								'code' => 'new_sort',
								'name' =>'保存排序',
								'path' => 'Integral/goodsEdit'
							),
							'2_1_2' => array(
								'code' => 'add',
								'name' => '添加商品',
								'path' => 'Integral/addGoods'
							),
							'2_1_3' => array(
								'code' => 'del',
								'name' => '删除商品',
								'path' => 'Integral/delGoods'
							),
							'2_1_4' => array(
								'code' => 'save',
								'name' =>  '保存并提交至审核',
								'path' => 'Integral/addGoods'
							)
						)
					),
					'2_2' => array(
						'name' => '审核',
						'code' => 'Integral/audit',
						'path' => 'Integral/audit',
						'low' => array(
							'2_2_1' => array(
								'code' => 'refuse_g',
								'name' => '拒绝(商品审核)',
								'path' => 'Integral/goodsAudit'
							),
							'2_2_2' => array(
								'code' => 'adopt_g',
								'name' => '通过(商品审核)',
								'path' => 'Integral/goodsAudit'
							),
							'2_2_3' => array(
								'code' => 'refuse_s',
								'name' => '拒绝(排版审核)',
								'path' => 'Integral/setting'
							),
							'2_2_4' => array(
								'code' => 'adopt_s',
								'name' => '通过(排版审核)',
								'path' => 'Integral/setting'
							)
						)
					),
					'2_3' => array(
						'name' => '积分卡管理',
						'code' => 'Integral/magange',
						'path' => 'Integral/magange',
						'low' => array(
							'2_3_1' => array(
								'code' => 'bind',
								'name' => '绑定积分卡',
								'path' => 'Integral/bindCard'
							),
							'2_3_2' => array(
								'code' => 'add_goods',
								'name' => '确认添加商品',
								'path' => 'Product/addCart'
							),
							'2_3_3' => array(
								'code' => 'confirm',
								'name' => '确认',
								'path' => 'Product/checkout'
							),
							'2_3_4' => array(
								'code' => 'confirm_code',
								'name' => '确认(兑换码)',
								'path' => 'Product/getRedeem'
							),
							'2_3_5' => array(
								'code' => 'confirm_sku',
								'name' => '确认(商品码)',
								'path' => 'Product/getRedeem'
							),
							'2_3_6' => array(
								'code' => 'confirm1',
								'name' => '确认兑换',
								'path' => 'Product/getRedeem'
							),
							'2_3_7' => array(
								'code' => 'importExcel',
								'name' => '导入商品',
								'path' => 'Integral/importProduct'
							),
							'2_3_8' => array(
								'code' => 'set_ratio',
								'name' => '设置比例',
								'path' => 'Product/getRatio'
							)
						)
					),
				),
		),
		'3' => array(
			'name' => '社区管理',
			'code' => 'Community',
			'low'  => array(
					'3_1' => array(
						'name' => '社区内容管理',
						'code' => 'Community/contentManage',
						'path' => 'Community/contentManage'
					),
				),
		),
		'4' => array(
			'name' => '视频管理',
			'code' => 'Video',
			'low'  => array(
					'4_1' => array(
						'name' => '视频内容管理',
						'code' => 'Video/manage',
						'path' => 'Video/manage/type/1',
						'low' => array(
							'4_1_1' => array(
								'code' => 'delete',
								'name' => '删除',
								'path' => 'Video/delData'
							)
						)
					),
					'4_2' => array(
						'name' => '视频内容审核',
						'code' => 'Video/audit',
						'path' => 'Video/audit/type/2',
						'low' => array(
							'4_2_1' => array(
								'code' => 'audit',
								'name' => '审核',
								'path' => 'Video/pass_audit'
							)
						)
					),
				),
		),
		'5' => array(
			'name' => '直播间管理',
			'code' => 'Direct',
			'low' => array(
				'5_1' => array(
					'name' => '巡查及管理',
					'code' => 'Direct/patrol',
					'path' => 'Direct/patrol',
					'low' => array(
						'5_1_1' => array(
							'code' => 'silenced',
							'name' => '禁言',
							'path' => ''
						),
						'5_1_2' => array(
							'code' => 'send_msg',
							'name' => '发送消息',
							'path' => 'Direct/sendAffiche'
						)
					)
				),
				'5_2' => array(
					'name' => '战报系统',
					'code' => 'Direct/report',
					'path' => 'Direct/report',
					'low' => array(
						'5_2_1' => array(
							'code' => 'Direct/',
							'name' => '确认切换',
							'path' => ''
						),
						'5_2_2' => array(
							'code' => 'Direct/',
							'name' => '开播',
							'path' => ''
						),
						'5_2_3' => array(
							'code' => 'Direct/',
							'name' => '关闭直播',
							'path' => ''
						),
						'5_2_4' => array(
							'code' => 'Direct/editReport',
							'name' => '编辑',
							'path' => ''
						),
						'5_2_5' => array(
							'code' => 'Direct/delReport',
							'name' => '删除',
							'path' => ''
						),
						'5_2_6' => array(
							'code' => 'Direct/sendReport',
							'name' => '发送战报',
							'path' => ''
						)
					)
				),
			),
		),
		'6' => array(
			'name' => '战队管理',
			'code' => 'Team',
			'low' => array(
				'6_1' => array(
					'name' => '战队列表',
					'code' => 'Team/teamList',
					'path' => 'Team/teamList',
					'low' => array(
						'6_1_1' => array(
							'code' => 'edit_teamList',
							'name' => '编辑',
							'path' => 'Team/teamManage'
						),
						'6_1_2' => array(
							'code' => 'del_team',
							'name' => '删除',
							'path' => 'Team/delTeam'
						)
					)
				),
				'6_2' => array(
					'name' => '资料管理',
					'code' => 'Team/teamManage',
					'path' => 'Team/teamManage',
					'low' => array(
						'6_2_1' => array(
							'code' => 'save',
							'name' => '保存',
							'path' => 'Team/actionTeam'
						)
					)
				),
				/*'6_3' => array(
					'name' => '报名列表',
					'code' => 'Team/enrollList',
					'path' => 'Team/enrollList',
				),*/
				'6_4' => array(
					'name' => '报名信息',
					'code' => 'Team/enroll',
					'path' => 'Team/enroll',
					'low' => array(
						'6_4_1' => array(
							'code' => 'find',
							'name' => '查询',
							'path' => 'Team/actionTeam'
						)
					)
				),
			),
		),
		'7' => array(
			'name' => '后台权限管理',
			'code' => 'Acl',
			'sort' => '',
			'low' => array(
				'7_1' => array(
					'name' => '用户管理',
					'code' => 'Acl/user',
					'path' => 'Acl/user',
					'sort' => '',
					'low' => array(
						'7_1_1' => array(
								'code' => 'add',
								'name' => '添加用户',
								'path' => 'Notice/add'
							),
						'7_1_2' => array(
								'code' => 'edit',
								'name' => '编辑',
								'path' => 'Notice/add'
							),
						'7_1_3' => array(
								'code' => 'delete',
								'name' => '删除',
								'path' => 'Notice/add'
							),
						'7_1_4' => array(
								'code' => 'setAcl',
								'name' => '权限设置',
								'path' => 'Notice/add'
							),
					),
				),
				/*
				'7_2' => array(
					'name' => '权限设置',
					'code' => 'Acl/setAcl',
					'path' => 'Acl/setAcl',
					'sort' => '',
					'low' => array(
						'7_2_1' => array(
								'code' => 'edit',
								'name' => '编辑',
								'path' => 'Acl/',
						),
						'7_2_2' => array(
								'code' => 'delete',
								'name' => '删除',
								'path' => 'Acl/',
						),
						'7_2_3' => array(
								'code' => 'save',
								'name' => '保存',
								'path' => 'Acl/',
						),
					),
				),
				*/	
			),
		),
		'8' => array(
			'name' => '客户管理',
			'code' => 'User',
			'sort' => '',
			'low' => array(
				'8_1' => array(
					'name' => '客户列表',
					'code' => 'User/userList',
					'path' => 'User/userList',
					'low' => array(
						'8_1_1' => array(
							'code' => 'details',
							'name' => '详情',
							'path' => ''
						),
						'8_1_2' => array(
							'code' => 'nomal',
							'name' => '正常',
							'path' => ''
						),
						'8_1_3' => array(
							'code' => 'stop',
							'name' => '封号',
							'path' => ''
						),
						'8_1_4' => array(
							'code' => 'reported',
							'name' => '被举报',
							'path' => ''
						),
						'8_1_5' => array(
							'code' => 'save',
							'name' => '保存',
							'path' => ''
						)
					)
				),	
			),
		),
		'9' => array(
			'name' => '票务管理',
			'code' => 'Ticket',
			'low' => array(
				'9_1' => array(
					'name' => '票务',
					'code' => 'Ticket/addTicket',
					'path' => 'Ticket/addTicket',
					'low' => array(
						'9_1_1' => array(
							'code' => 'delTicket',
							'name' => '删除',
							'path' => 'Ticket/delTicket'
						),
						'9_1_2' => array(
							'code' => 'editTicket',
							'name' => '编辑',
							'path' => 'Ticket/getTicketById'
						),
						'9_1_3' => array(
							'code' => 'saveTicket',
							'name' => '保存并提交至审核',
							'path' => 'Ticket/saveTicket'
						)
					)
				),
				'9_2' => array(
					'name' => '审核',
					'code' => 'Ticket/ticketAudit',
					'path' => 'Ticket/ticketAudit',
					'low' => array(
						'9_2_1' => array(
							'code' => 'reject',
							'name' => '拒绝',
							'path' => 'Ticket/refuse'
						),
						'9_2_2' => array(
							'code' => 'adopt',
							'name' => '通过',
							'path' => 'Ticket/adopt'
						),
					)
				),
			),
		),
		'10' => array(
			'name' => '榜单维护',
			'code' => 'List',
			'low' => array(
				'10_1' => array(
					'name' => '榜单编辑',
					'code' => 'List/editList',
					'path' => 'List/editList',
					'low' => array(
						'10_1_1' => array(
							'code' => 'btn_list',
							'name' => '保存编辑至审核',
							'path' => 'List/addList'
						),
						'10_1_2' => array(
							'code' => 'btn_del_num',
							'name' => '删除榜单',
							'path' => 'List/delListNum'
						),
						'10_1_3' => array(
							'code' => 'edit_list',
							'name' => '修改榜单',
							'path' => 'List/editListStage'
						),
						'10_1_4' => array(
							'code' => 'add_list',
							'name' => '添加榜单',
							'path' => 'List/addList'
						),
						'10_1_5' => array(
							'code' => 'btn_addRank',
							'name' => '增加排名',
							'path' => 'List/addRanking'
						),
						'10_1_6' => array(
							'code' => 'btn_delRank',
							'name' => '删除',
							'path' => 'List/delRanking'
						)
					)
				),
				'10_2' => array(
					'name' => '榜单类型设置',
					'code' => 'List/listType',
					'path' => 'List/listType',
					'low' => array(
						'10_2_1' => array(
							'code' => 'add_type',
							'name' => '添加',
							'path' => 'List/addListType'
						),
						'10_2_2' => array(
							'code' => 'del_type',
							'name' => '删除',
							'path' => 'List/delListType'
						),
						'10_2_3' => array(
							'code' => 'btn_listAudit',
							'name' => '提交榜单至审核',
							'path' => 'List/saveListType'
						)
					)
				),
				'10_3' => array(
					'name' => '榜单展示管理',
					'code' => 'List/listShow',
					'path' => 'List/listShow',
					'low' => array(
						'10_3_1' => array(
							'code' => 'save',
							'name' => '保存',
							'path' => 'List/saveListSort'
						)
					),
				),
				'10_4' => array(
					'name' => '审核',
					'code' => 'List/listAudit',
					'path' => 'List/listAudit',
					'low' => array(
						'10_4_1' => array(
							'code' => 'reject',
							'name' => '拒绝(榜单审核)',
							'path' => 'List/reject_l'
						),
						'10_4_2' => array(
							'code' => 'adopt',
							'name' => '通过(榜单审核)',
							'path' => 'List/adopt_l'
						),
						'10_4_3' => array(
							'code' => 'reject',
							'name' => '拒绝(榜单类型审核)',
							'path' => 'List/reject_t'
						),
						'10_4_4' => array(
							'code' => 'adopt',
							'name' => '通过(榜单类型审核)',
							'path' => 'List/adopt_t'
						),
						'10_4_5' => array(
							'code' => 'reject',
							'name' => '拒绝(榜单顺序审核)',
							'path' => 'List/reject_s'
						),
						'10_4_6' => array(
							'code' => 'adopt',
							'name' => '通过(榜顺序单审核)',
							'path' => 'List/adopt_s'
						),
					)
				),
		    ),
		),
		'11' => array(
			'name' => '报名信息维护',
			'code' => 'Registration',
			'low' => array(
				'11_1' => array(
					'name' => '打榜说明',
					'code' => 'Registration/playList',
					'path' => 'Registration/playList',
					'low' => array(
						'11_1_1' => array(
							'code' => 'save',
							'name' => '提交至审核',
							'path' => 'Registration/playInstr'
						)
					)
				),
				'11_2' => array(
					'name' => '标签管理',
					'code' => 'Registration/tagManage',
					'path' => 'Registration/tagManage',
					'low' => array(
						/*'11_2_1' => array(
							'code' => 'save',
							'name' => '提交至审核',
							'path' => 'Registration/saveTag'
						),*/
						'11_2_2' => array(
							'code' => 'del_tag',
							'name' => '删除标签',
							'path' => 'Registration/del_tag'
						),
						'11_2_3' => array(
							'code' => 'edit_tag',
							'name' => '修改标签',
							'path' => ''
						),
						'11_2_4' => array(
							'code' => 'add_tag',
							'name' => '添加标签',
							'path' => ''
						)
					)
				),
				/*'11_3' => array(
					'name' => '报名成功',
					'code' => 'Registration/regSuccess',
					'path' => 'Registration/regSuccess',
					'low' => array(
						'11_3_1' => array(
							'code' => 'save',
							'name' => '提交至审核',
							'path' => ''
						)
					),
				),*/
				'11_4' => array(
					'name' => '审核',
					'code' => 'Registration/regAudit',
					'path' => 'Registration/regAudit',
					'low' => array(
						'11_4_1' => array(
							'code' => 'reject',
							'name' => '拒绝(打榜说明审核)',
							'path' => 'Registration/reject_p'
						),
						'11_4_2' => array(
							'code' => 'adopt',
							'name' => '通过(打榜说明审核)',
							'path' => 'Registration/adopt_p'
						),
						'11_4_3' => array(
							'code' => 'reject',
							'name' => '拒绝(个性标签审核)',
							'path' => 'Registration/reject_t'
						),
						'11_4_4' => array(
							'code' => 'adopt',
							'name' => '通过(个性标签审核)',
							'path' => 'Registration/adopt_t'
						),
						/*'11_4_5' => array(
							'code' => 'reject',
							'name' => '拒绝(报名成功审核)',
							'path' => ''
						),
						'11_4_6' => array(
							'code' => 'adopt',
							'name' => '通过(报名成功审核)',
							'path' => ''
						),*/
					)
				),
			),
		),
		'12' => array(
			'name' => '系统设置',
			'code' => 'SystemAction',
			'low' => array(
				'12_1' => array(
					'name' => '数据库操作',
					'code' => 'dbAction/index',
					'path' => 'dbAction/index',
					'low' => array(
						'12-1-1' => array(
							'code' => 'save',
							'name' => '提交',
							'path' => 'dbAction/buck'
						)
					)
				),
			),
		),
	),
);
?>