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
class GoodsModel extends Model
{
	private $_ship = 'ship';

	public function addProduct($shiper, $var){
		$goods = $this->table('goods G')
			 		  ->join('goods_info GI on GI.g_id = G.g_id')
			   		  ->where('goods_num="'.$var.'" or title="'.$var.'"')
			 		  ->getField('G.g_id, header_img, integral, goods_num, price');

		foreach ($goods as $key => $value) {
			$goods = $value;
		}
		//加入ship表
		$ship = D($this->_ship);
		$data = array(
			'shiper' => $shiper,
			'goods_num' => $goods['goods_num'],
			'num' => 1,
			'price' => $goods['integral'],
			'total' => (float)$goods['integral'] * 1,
			'created' => time(),
			);
		$re = $ship->data($data)->add();
		if($re){
			return $goods;
		}
	}

	/**
	 * [addNum 增加数量]
	 * @param [string] $account   [购买者]
	 * @param [string] $goods_num [商品号]
	 * @param [int] $num       [购买数量]
	 */
	public function addNum($account, $goods_num, $num){
		$goods_info = D($this->_goods_info);
		$price = $this->field('integral')->where('goods_num="'.$goods_num.'"')->find();

		$total = D($this->_ship)->field('total')->where('shiper="'.$account.'" and goods_num="'.$goods_num.'"')->find();
		$data = array(
			'num' => $num,
			'total' => $price['integral']+$total['total'],
			);

		$re = D($this->_ship)->where('shiper="'.$account.'" and goods_num="'.$goods_num.'"')->save($data);
		if($re){
			return 0;
		}
	}

	/**
	 * [reduceNum 减少数量]
	 * @param [string] $account   [购买者]
	 * @param [string] $goods_num [商品号]
	 * @param [int] $num       [购买数量]
	 * @return [type]            [description]
	 */
	public function reduceNum($account, $goods_num, $num){
		$goods_info = D($this->_goods_info);
		$price = $this->field('integral')->where('goods_num="'.$goods_num.'"')->find();

		$total = D($this->_ship)->field('total')->where('shiper="'.$account.'" and goods_num="'.$goods_num.'"')->find();
		$data = array(
			'num' => $num,
			'total' => $total['total'] - $price['integral'],
			);

		$re = D($this->_ship)->where('shiper="'.$account.'" and goods_num="'.$goods_num.'"')->save($data);
		if($re){
			return 0;
		}
	}

	public function delGoods($account, $goods_num){
		$re = D($this->_ship)->where('shiper="'.$account.'" and goods_num="'.$goods_num.'"')->delete();
		if($re){
			return 0;
		}
	}
}
?>