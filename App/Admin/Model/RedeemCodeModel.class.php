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
 * 兑换码
 */
class RedeemCodeModel extends Model
{
	private $_goods = 'goods';
	private $_integral_card = 'integral_card';
	private $_integral_info = 'integral_info';

	/**
	 * [getRedeem 获取兑换码中的商品]
	 * @param  [string] $code [兑换码]
	 * @return [type]       [description]
	 */
	public function getRedeem($code){
		$data = $this->where('code="'.$code.'" and status = 1')->getField('account, code, sku, num, integral, status');

		foreach ($data as $key => $value) {
			$data = $value;
		}
		$goods = $this->getGoods($data['sku']);

		$data = array_merge($data, $goods);
		return $data;
	}

	/**
	 * [getGoods 获取商品信息]
	 * @param  [string] $sku [商品号]
	 * @return [type]      [description]
	 */
	protected function getGoods($sku){
		$goods = D($this->_goods);
		$data = $goods->table('goods')
					 ->field('title, header_img')
			 		 ->join('LEFT JOIN goods_info on goods.g_id = goods_info.g_id')
			 		 ->where('goods.goods_num="'.$sku.'"')
			 		 ->find();

		

		return $data;
	}

	/**
	 * [actionRedeem 使用兑换码]
	 * @param  [string] $code    [兑换码]
	 * @param  [string] $account [帐号]
	 * @return [type]          [description]
	 */
	public function actionRedeem($code, $account){
		$data = array(
			'status' => 2,
			'modified' => time(),
			'username' => session('user.username'),
			);
		$re = $this->where('code="'.$code.'" and account="'.$account.'"')->save($data);

		return $re;
	}

	/**
	 * [seachGoods 在商品兑换中查找商品]
	 * @param  [string] $sku [商品号]
	 * @return [type]      [description]
	 */
	public function seachGoods($sku){
		$good = D($this->_goods);
		$data = $good->table('goods')
			 		->field('goods_num, integral, header_img')
			 		->join('LEFT JOIN goods_info ON goods.g_id = goods_info.g_id')
			 		->where('goods.title="'.$sku.'"')
			 		->find();

		return $data;
	}

	/**
	 * [goodsRedeem 现场兑换商品]
	 * @param  [string] $account [帐号]
	 * @param  [string] $sku     [商品sku]
	 * @param  [int] $num     [商品数量]
	 * @return [type]          [description]
	 */
	public function goodsRedeem($account, $sku, $num){
		$card = D($this->_integral_card);
		$integral = $card->where('account="'.$account.'"')->getField('integral');

		$goods = D($this->_goods);
		$result = $goods->field('integral, title')->where('goods_num="'.$sku.'"')->find();

		$integral = (float)$integral - ((float)$result['integral'] * (int)$num);

		$data = array(
			'integral' => $integral,
			'updated' => time(),
			);
		$re = $card->where('account="'.$account.'"')->save($data);

		if($re){
			$data = array(
				'account' => $account,
				'use' => (float)$result['integral'] * (int)$num,
				'desc' => '兑换商品“'.$result['title'].'” x '.$num.'个花费积分。',
				'g_num' => $sku,
				'created' => time(),
				'num' => $num,
				);

			$info = D($this->_integral_info);
			$re = $info->data($data)->add();

			return $re;
		}

	}
}
?>