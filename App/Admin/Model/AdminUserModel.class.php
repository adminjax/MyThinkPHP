<?php
/**
 * MET纪元后台音乐管理工具
 *
 * @Author: jax
 *
 * @category    App
 * @package     App_Admin
 */
namespace Admin\Model;
use Think\Model;

/**
 * 后台用户登录
 */
class AdminUserModel extends Model
{
	protected $aclTable = 'acl';

	/**
	 * [login 比对数据库]
	 * @param  [string] $name     [用户名]
	 * @param  [string] $password [密码]
	 * @return [bool]           [description]
	 */
	public function login($name, $password){
		$condition = array('username'=>$name, 'password'=>$password);
		$data = $this->where($condition)->find();

		if($data){
			session('user.user_id', $data['u_id']);
			session('user.username', $data['username']);
			session('error', null);

			$uData = array(
				'lognum' => $data['lognum'] + 1,
				);
			$re = $this->updateData($data['u_id'], $uData);

			if($re){
				return true;
			}else{
				return false;
			}
		}
	}

	/**
	 * [updateData 更新数据]
	 * @param  [string] $uid  [用户 id]
	 * @param  [array] $data [要更新的数据]
	 * @return [string]       [description]
	 */
	protected function updateData($uid, $data){
		$re = $this->where('u_id='.$uid)->save($data);
		
		return $re;
	}

	/**
	 * [getUser 获取用户信息]
	 * @param  [int] $uid [用户 id]
	 * @return [type]      [description]
	 */
	public function getUser($uid, $field = null){
		if($field){
			$re = $this->field($field)->where("u_id=".$uid)->select();
		}else{
			$re = $this->where("u_id=".$uid)->find();
		}
	
		return $re;
	}

	/**
	 * [getAllUser 获取所有用户]
	 * @return [array] [用户信息]
	 */
	public function getAllUser($p,$num){
		$st = ($p*$num)-$num;
		$re = $this->limit($st.','.$num)->where(1)->select();

		return $re;
	}

	/*
	 * 用户个数
	 */
	public function userCount(){
		$res = $this->count();
		return $res;
	}

	/**
	 * [updateUser 修改用户数据]
	 * @param  [array] $data [用户数据]
	 * @return [type]       [description]
	 */
	public function updateUser($data, $uid){
		if(!$this->create($data)){
			return $this->getError();
		}else{
			$re = $this->where("u_id=".$uid)->save($data);
		}

		return $re;
	}

	/**
	 * [delUser 删除用户]
	 * @param  [int] $uid [用户 id]
	 * @return [type]      [description]
	 */
	public function delUser($uid){
		$re = $this->where("u_id=".$uid)->delete();
		return $re;
	}

	/**
	 * [register 添加新用户]
	 * @param  [array] $data [用户信息]
	 * @return [type]       [description]
	 */
	public function register($data){
		if(!$this->create($data)){
			return $this->getError();
		}else{
			$re = $this->data($data)->add();

			$acl = array(
				'u_id' => $re,
				'resource_id' => '0',
				);
			M('acl')->data($acl)->add();

			return $re;
		}
	}
}


?>