<?php
/**
 * MET纪元后台音乐管理工具
 *
 * @Author: jax
 *
 * @category    App
 * @package     App_Admin
 */
namespace Admin\Controller;

/**
 * 权限管理类
 */
class AclController extends CommonController
{
	private $_acl = 'acl';
	private $_user = 'admin_user';
	private $num = 30;

	/**
     * [_initialize 初始化]
     * @return [type] [description]
     */
    public function _initialize(){
        parent::checkLogin();
        $menu = parent::getMenu();

        $this->assign('menu', $menu);
    }

	/**
	 * [user 后台用户list入口]
	 * @return [type] [description]
	 */
	public function user($p = 1){
		$p = I('get.p') ? I('get.p') : $p;
		$userList = $this->getAllUser($p);
		$user_page = $this->pageTools($userList['user_num'],$p,$this->num);

		/**
		 * 写入日志
		 */
		$log = A('Admin/LogEvent');
    	$log->writeLog(array('action' => ACTION_NAME,'info' => '浏览后台用户信息','fullaction' => __ACTION__,));

		$this->assign('edit', $this->button('edit', '编辑', '7_1_2'));
		$this->assign('delete', $this->button('delete', '删除', '7_1_3'));
		$this->assign('setAcl', $this->button('setAcl', '设置权限', '7_1_4'));
		$this->assign('add', $this->button('add', '添加用户', '7_1_1'));
		$this->assign('userList', $userList['list']);
		$this->assign('user_page', $user_page);
		$this->display('user');
	}


	/**
	 * [setAcl 后台用户权限设置入口]
	 */
	public function setAcl(){
		$edit = $this->button('edit', '编辑', '7_2_1');
		$delete = $this->button('delete', '删除', '7_2_2');
		$save = $this->button('save', '保存', '7_2_3');
		
		if($_GET){
			$uid = I('get.uid', '', 'int');
			$user = D($this->_user);

			$field = array('u_id', 'realname', 'gender', 'position', 'phone', 'username');
			$list = $user->getUser($uid, $field);
			$this->assign('list', $list);
		}

		$this->assign('edit', $edit);
		$this->assign('delete', $delete);
		$this->assign('save', $save);
		$this->assign('menu', C('ADMIN_MENU'));
		$this->display('setAcl');
	}

	/**
	 * [getAllUser 获取所用用户]
	 * @return [type]      [description]
	 */
	protected function getAllUser($p){
		$userList = array();
		$user = D($this->_user);
		$userList['list'] = $user->getAllUser($p,$this->num);

		$userList['user_num'] = $user->userCount();
		return $userList;
	}

	/**
	 * [getResource 获取用户资源]
	 * @return [type] [description]
	 */
	public function getResource(){
		$u_id = session('user.user_id');
		$acl = D($this->_acl);
		$resource = $acl->getAcl($u_id);
		if($resource != 'all'){
			$resource = unserialize($resource);
		}

		return $resource;
	}

	/**
	 * [checkController 检查是否有权限]
	 * @return [type] [description]
	 */
	public function checkAcl($menuArr){
		$result = self::getResource();
		if($result != 'all'){
			foreach ($menuArr as $k => $v){
				if(!in_array($k, $result)){
					unset($menuArr[$k]);
				}
				foreach ($v['low'] as $ke => $val) {
					if(!in_array($ke, $result)){
						unset($menuArr[$k]['low'][$ke]);
					}
					foreach ($val['low'] as $key => $value) {
						if(!in_array($key, $result)){
							unset($menuArr[$k]['low'][$ke]['low'][$key]);
						}
					}
				}
			}
			return $menuArr;
		}else{
			return $menuArr;
		}
	}

	/**
	 * [save 保存用户权限设置]
	 * @return [type] [description]
	 */
	public function save(){
		$userAcl = I('post.acl', '', 'addslashes');
		$uid = I('get.uid', '', 'addslashes');

		$resource = array();

		foreach ($_POST['checkbox'] as $key => $value) {
			$resource[] = $value;
		}

		//实例化数据表
		$acl = D($this->_acl);
		if($userAcl == 'all'){
			$resource = array(
				'resource_id' => 'all',
				);
			$re = $acl->saveAcl($uid, $resource);
		}else{
			//序列化resource
			$data = serialize($resource);
			$resource = array(
				'resource_id' => $data,
				);
			$re = $acl->saveAcl($uid, $resource);
		}

		/**
		 * 写入日志
		 */
		$log = A('Admin/LogEvent');
    	$log->writeLog(array('action' => ACTION_NAME,'info' => '设置后台用户权限','fullaction' => __ACTION__,));
		//$this->redirect("Acl/setAcl/", array('uid'=>$uid));
		$this->redirect("Acl/user");
	}

	/**
	 * [editUser 用户编辑]
	 * @return [type] [description]
	 */
	public function editUser(){
		$uid = I('post.uid', '', 'int');

		if($_POST['gender'] == '男'){
			$gender = 1;
		}elseif ($_POST['gender'] == '女') {
			$gender = 2;
		}else{
			$gender = 0;
		}

		$data = array(
			'username' => I('post.username', '', 'addslashes'),
			'password' => md5(I('post.password', '', 'addslashes').C('MD5_KEY')),
			'realname' => I('post.realname', '', 'addslashes'),
			'gender' => $gender,
			'phone' => I('post.phone', '', 'addslashes'),
			'position' => I('post.position', '', 'addslashes'),
			);

		$user = D($this->_user);
		$re = $user->updateUser($data, $uid);

		if($re > 0){
			/**
			 * 写入日志
			 */
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => '编辑后台用户:'.I('post.username').'的信息','fullaction' => __ACTION__,));

			echo $re;
		}else{
			echo -1;
		}
	}

	/**
	 * [deleteUser 删除用户]
	 * @return [type] [description]
	 */
	public function deleteUser(){
		$uid = I('post.uid', '', 'int');
		$user = D($this->_user);
		$re = $user->delUser($uid);

		if($re > 0){
			/**
			 * 写入日志
			 */
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => '删除后台用户','fullaction' => __ACTION__,));

			echo $re;
		}else{
			echo -1;
		}
	}

	/**
	 * [addUser 添加新用户]
	 */
	public function addUser(){
		if($_POST['gender'] == '男'){
			$gender = 1;
		}elseif ($_POST['gender'] == '女') {
			$gender = 2;
		}else{
			$gender = 0;
		}

		$userData = array(
			'realname' => I('post.realname', '', 'addslashes'),
			'gender' => $gender,
			'position' => I('post.position', '', 'addslashes'),
			'phone' => I('post.phone', '', 'addslashes'),
			'username' => I('post.username', '', 'addslashes'),
			'password' => md5(I('post.password').C('MD5_KEY')),
			'created' => time(),
			'is_active' => 1,
			);

		$user = D($this->_user);
		$re = $user->register($userData);

		/**
		 * 写入日志
		 */
		$log = A('Admin/LogEvent');
		$log->writeLog(array('action' => ACTION_NAME,'info' => '添加后台用户:'.I('post.username'),'fullaction' => __ACTION__,));

		$this->ajaxReturn($re);
	}
}
?>