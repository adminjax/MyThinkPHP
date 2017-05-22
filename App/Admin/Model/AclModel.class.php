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
 * 日志管理
 */
class AclModel extends Model
{
	protected $_validate = array(
		//验证字段
		array("resource_id", '/^[\";{:}_0-9a-z]+$/', "字段不合法", self::EXISTS_VALIDATE),
		);


	/**
	 * [getAcl 获取用户resource_id]
	 * @param  [string] $user_id [用户 id]
	 * @return [type]          [description]
	 */
	public function getAcl($user_id){
		$re = $this->where('u_id = '.$user_id)->getField('resource_id');
		return $re;
	}

	/**
	 * [saveAcl 保存用户resource_id]
	 * @param  [string] $uid [用户ID]
	 * @param  [string] $acl [用户权限]
	 * @return [type]      [description]
	 */
	public function saveAcl($uid, $acl){
		if(!$this->create($acl)){
			return $this->getError();
		}else{
			$re = $this->where('u_id = '.$uid)->save($acl);	
		}

		return $re;
	}
}
?>