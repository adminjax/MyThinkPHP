<?php
/**
 * MET纪元后台音乐管理工具
 *
 * @Author: jax
 *
 * @category    App
 * @package     App_Admin
 */
namespace Api\Model;
use Think\Model;

/**
 * 日志管理
 */
class IntegralCardModel extends Model
{
	private $_integral_card = 'integral_card';
	private $_integral_info = 'integral_info';
	private $_ratio = 'ratio';
	private $_ticket = 'ticket';

	/**
	 * [getRate 获取人民币积分兑换比例]
	 * @return [type] [description]
	 */
	protected function getRatio(){
		$ratio = M($this->_ratio)->field('ratio')->where('r_id=1')->find();
		return $ratio['ratio'];
	}

	/**
	 * [saveTickt 买票增加积分]
	 * @param  [type] $tid     [票据id]
	 * @param  [type] $num     [购票数量]
	 * @param  [type] $account [帐号]
	 * @return [type]          [description]
	 */
	public function salesTicket($tid, $num, $account){
		$ratio = $this->getRatio();
		$ticket = $this->getTicket($tid);

		$integral = ($ticket['price'] * $num) / $ratio;		
		$result = $this->addUserIntegral($integral, $account);
		if($result){
			$msg = "购买票务".$ticket['title']."获取积分。";
			$result = $this->addIntegralInfo($account, $integral, $msg, $tid, $num);
		}

		return $result;
	}

	/**
	 * [getTicket 获取票据信息]
	 * @param  [type] $tid [description]
	 * @return [type]      [description]
	 */
	protected function getTicket($tid){
		$data = M($this->_ticket)->field('title, price')->where('reid = '.$tid)->find();
		return $data;
	}

	/**
	 * [addUserIntegral 增加用户总积分]
	 * @param [type] $integral [积分]
	 * @param [type] $account  [帐号]
	 */
	protected function addUserIntegral($integral, $account){
		$result = M($this->_integral_card)->where('account = '.$account)->find();

		if($result){//若有帐号
			$data = array(
				'integral' => (float)$result['integral'] + $integral,
				'updated' => time(),
				);
			$result = M($this->_integral_card)->where('account = '.$account)->save($data);	
		}else{ //若没有帐号
			$data = array(
				'account' => $account,
				'integral' => $integral,
				'updated' => time(),
				);
			$result = M($this->_integral_card)->data($data)->add();
		}
		
		return $result;
	}

	/**
	 * [addIntegralInfo 增加详情]
	 * @param [type] $account  [帐号]
	 * @param [type] $integral [积分]
	 * @param [type] $msg      [说明]
	 * @param [type] $tid      [商品id]
	 * @param [type] $num      [数量]
	 */
	protected function addIntegralInfo($account, $integral, $msg, $tid, $num){
		$data = array(
				'account' => $account,
				'get' => $integral,
				'desc' => $msg,
				'g_num' => $tid,
				'created' => time(),
				'num' => $num,
			);

		$result = M('integral_info')->data($data)->add();
		return $result;
	}

}
?>