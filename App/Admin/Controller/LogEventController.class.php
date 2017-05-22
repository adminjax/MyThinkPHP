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
 * 日志处理类
 */
class LogEventController extends CommonController
{
	private $logEvent = 'log_event';
	private $logEventC = 'log_event_changes';
	protected $config = array();

	/**
	 * [init 初始化信息]
	 * @return [type] [description]
	 */
	protected function initInfo(){
		$this->config = array(
				'event_code' => '',
				'ip' => $this->getIp(),
				'time' => time(),
				'u_id' => session('user.user_id'),
				'status' => '1',
				'error_message' => '',
			);
	}

	/**
	 * [writeLog 写入日志]
	 * @param  [array] $theme       [主题]
	 * @return [type]              [description]
	 */
	public function writeLog(Array $data){
		$this->initInfo();
		$config = array_merge($this->config, $data);

		//实例化model
		$logEvent = D($this->logEvent);
		if($logEvent){
			$logEvent->write($config);
		}
	}

	/**
	 * [getIp 获取客户端ip]
	 * @return [string] [ip地址]
	 */
	protected function getIp(){
		return $_SERVER["REMOTE_ADDR"];
	}

	/**
	 * [validateIp 检验ip是否合法]
	 * @param  [string] $ip [ip地址]
	 * @return [bool]     []
	 */
	protected function validateIp($ip){
		return preg_match('#^(1?\d{1,2}|2([0-4]\d|5[0-5]))(\.(1?\d{1,2}|2([0-4]\d|5[0-5]))){3}$#', $ip);
	}

	/**
	 * [clearLog 清除后台日志]
	 * @return [type] [description]
	 */
	public function clearLog(){
		$log = D($this->_logEvent);
		$log->deleteLog();
	}
}

?>