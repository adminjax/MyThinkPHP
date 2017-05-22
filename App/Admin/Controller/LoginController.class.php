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
 * 登录，登出，后台帐号管理类
 */
class LoginController extends CommonController
{
	/**
	 * [$_userTable 后台用户表]
	 * @var string
	 */
	protected $userTable = 'AdminUser';

	/**
	 * [logInto 登录]
	 * @return [type] [description]
	 */
	public function logInto(){		
		$name = I('post.username', '', 'addslashes');
		$password = I('post.password', '', 'addslashes');
		$code = I('post.j_verify', '', 'addslashes');

		//验证码检查
		if(!A('Verify')->checkVerify($code)){
			session('error', '验证码错误!');
			$this->redirect("Index/index");
		}else{
			session('error', null);
		}
		
		//验证数据
		$validate = A('Validate');
		if($validate){
			if($validate->checkString($name)){
				$user = D($this->userTable);
				$re = $user->login($name, md5($password.C('MD5_KEY')));
				if($re){
					/**
					 * 写入日志
					 */
					$log = A('Admin/LogEvent');
					$data = array(
							'action' => ACTION_NAME,
							'info' => session('user.username').':用户登录',
							'fullaction' => __ACTION__,
						);
			    	$log->writeLog($data);

					$this->redirect("Empty/index");
				}else{
					session('error', '用户名或密码!');
					$this->redirect("Index/index");
				}
			}else{
				session('error', '用户名或密码!');
				$this->redirect("Index/index");
			}
		}
	}

	/**
	 * [logout 退出]
	 * @return [type] [description]
	 */
	public function logout(){
		/**
		 * 写入日志
		 */
		$log = A('Admin/LogEvent');
		$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').'退出','fullaction' => __ACTION__,));

		session('user.user_id', null);
		session('user.username', null);
		session('user.acl', null);

		$this->redirect("Index/index");
	}
}


?>