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
 * 忘记密码类
 */
class ForgetpwdController extends CommonController
{
	/**
	 * [index 忘记密码入口]
	 * @return [type] [description]
	 */
    public function index(){
        $this->display('index');
    }
}