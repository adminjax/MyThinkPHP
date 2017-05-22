<?php
/**
 * MET纪元后台音乐管理工具
 *
 * @Author: jax
 *
 * @category    App
 * @package     App_Api
 */
namespace Api\Controller;

/**
 * 接口类
 */
class MetController
{
	protected $_helper;
	protected $_data;
	protected $_clentIp;//客户端ip
	/**
	 * [_init 初始化]
	 * @return [type] [description]
	 */
	protected function _init(){
		$this->_helper = A('Helper');
		$this->_clentIp = $this->_helper->getIpAddress();
	}

	/**
	 * [server 处理数据]
	 * @return [type] [description]
	 */
	public function server(){
		$this->_init();

		try{
			//验证ip address
			$result = $this->_helper->vailderIp($this->_clentIp);
			if($result == false){
				throw new \Exception("Illegal request address!", 1101);
			}

			//验证客户端appid ,token
			$appid = I('get.appid', '', 'addslashes');
			$appsecret = I('get.appsecret', '', 'addslashes');
			$result = $this->_helper->vailderUser($appid, $appsecret);
			if($result == false){
				throw new \Exception("Illegal Appid,Appsecret!", 1102);
			}

			//获取数据
			foreach ($_POST as $key => $value) {
				$this->_data = json_decode($key);
			}
			
			if($this->_data->type == 1){
			
				$msg['result'] = $this->_data->account;
				echo json_encode($msg, 1100);
			}else{
				throw new \Exception("Parameter error!", 1102);
			}
		}catch(\Exception $e){
			$msg['error'] = $e->getMessage();
			echo json_encode($msg, true);
		}
	}


	/**
	 * [server 处理数据]
	 * @return [type] [description]
	 */
	public function salesTicket(){
		$data = file_get_contents('php://input', 'r');
		$data = json_decode($data, true);

		$D = D('integral_card');
		$D->startTrans(); //开启事物

		$fail = array();
		foreach ($data['ship'] as $key => $value) {
			$result = $D->salesTicket($value['t_id'], $value['num'], $data['account']);
			if($result){
				$D->commit(); //提交
			}else{
				$D->rollback(); //回滚
				$fail[] = $value['t_id'];
				continue;
			}
		}

		if(!$fail){
			echo json_encode(array('result'=>true));
		}else{
			echo json_encode(array('result'=>false, 'array'=>$fail));
		}
	}
}
?>