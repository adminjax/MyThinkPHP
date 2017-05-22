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
 * 订单
 */
class ProductOrderModel extends Model
{
	private $_product = 'product';
	private $_product_ship = 'product_ship';
	private $_integral_card = 'integral_card';
	private $_integral_info = 'integral_info';
	protected $goodsName;


	/**
	 * [checkout 结账]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function checkout($data){
		$flag = $this->data($data)->add();
		if($flag > 0){
			$ship = D($this->_product_ship);
			$cart = unserialize(cookie('cart'));

			$infoAll = array();
			foreach ($cart as $k => $v) {
				$info = array(
					'shiper' => $data['shiper'],
					'sku' => $v['sku'],
					'num' => $v['num'],
					'created' => time(),
					'order_number' => $data['order_number'],
				);
				$this->goodsName .= '、'.D($this->_product)->where('sku="'.$v['sku'].'"')->getField('name');
				$infoAll[] = $info;
			}
			
			$flag = $ship->addAll($infoAll);
		}

		if($flag){
			$card = D($this->_integral_card);
			$integ = $card->where('account = "'.$data['shiper'].'"')->getField('integral');
			$integral = $integ + $data['integral'];

			$cardData = array(
				'integral' => $integral,
			);

			$flag = $card->where('account = "'.$data['shiper'].'"')->save($cardData);
		}

		if(is_int($flag)){
			$info = D($this->_integral_info);
			
			$infoData = array();
			foreach ($cart as $k => $v) {
				$infos = array(
					'account' => $data['shiper'],
					'get' => D($this->_product)->where('sku="'.$v['sku'].'"')->getField('price')*$v['num']/D('ratio')->where('r_id = 1')->getField('ratio'),
					'desc' => '购买商品'.D($this->_product)->where('sku="'.$v['sku'].'"')->getField('name').'获取积分。',
					'created' => time(),
					'g_num' => $v['sku'],
					'num' => $v['num'],
				);

				$infoData[] = $infos;
			}

			$flag = $info->addAll($infoData);
		}

		return $flag;
	}

}
?>