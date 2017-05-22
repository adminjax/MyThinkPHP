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
 * [空控制器]
 */
class EmptyController extends CommonController
{
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
	 * [index 空控制器]
	 * @return [void] [提示访问错误]
	 */
	public function index(){
		$mysql = M()->query('SELECT VERSION() as ver');

		$info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            '主机名'=>$_SERVER['SERVER_NAME'],
            'PHP版本'=>PHP_VERSION,
            'MySQL版本'=>$mysql[0]['ver'],
            '通信协议'=>$_SERVER['SERVER_PROTOCOL'],
            'MET版本'=>C('MET_VERSION'),
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '剩余空间'=>round((disk_free_space(".")/(1024*1024)),2).'M',
            '时区'=>date_default_timezone_get(),
            '编码' => 'UTF',
        );

		$this->assign('info', $info);
		$this->display('Empty/Index/index');
	}
}
?>