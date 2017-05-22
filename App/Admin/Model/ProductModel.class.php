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
class ProductModel extends Model
{
	private $_ship = 'ship';
	private $_ratio = 'ratio';

	/**
	 * [putInTable 写入数据库]
	 * @param  [array] $data [description]
	 * @return [type]       [description]
	 */
	public function putInTable($data){
		$dataList = array();
		foreach ($data as $key => $value) {
			if($key == 1){
				continue;
			}
			$product['sku'] = $value['A'];
			$product['name'] = $value['B'];
			$product['price'] = $value['C'];
			$product['img'] = $value['D'];

			$dataList[] = $product;
		}

		$re = $this->addAll($dataList);
	}

	/**
	 * [getProduct 在积分管理中添加购物车查找相应的产品]
	 * @param  [string] $var [商品的sku]
	 * @return [type]      [description]
	 */
	public function getProduct($var){
		$product = $this->where('sku="'.$var.'" or name="'.$var.'"')->getField('sku, name, price, img');

		foreach ($product as $key => $value) {
			$product = $value;
		}

		return $product;
		//加入ship表
		/*
		$ship = D($this->_ship);
		$data = array(
			'shiper' => $shiper,
			'goods_num' => $product['sku'],
			'num' => 1,
			'price' => $product['price'],
			'total' => (float)$product['price'] * 1,
			'created' => time(),
			);
		$re = $ship->data($data)->add();
		if($re){
			return $product;
		}
		*/
	}

	/**
	 * [setRatio 设置比例]
	 * @param [type] $rmb [description]
	 * @param [type] $int [description]
	 */
	public function setRatio($rmb, $int){
		$ratio = D($this->_ratio);
		$data = array(
			'rmb' => $rmb,
			'integral' => $int,
			'ratio' => number_format($rmb/$int, 2),
			'u_id' => session('user.user_id'),
			);
		
		$id = $ratio->find();
		if(!$id['r_id']){
			$time = array('created'=>time());
			$data = array_merge($data, $time);

			$re = $ratio->data($data)->add();			
		}else{
			$time = array('modified'=>time());
			$data = array_merge($data, $time);

			$re = $ratio->where('r_id='.$id['r_id'])->save($data);
		}

		return $re;
	}

	/**
	 * [getRatio 获取比例]
	 * @return [type] [description]
	 */
	public function getRatio(){
		$ratio = D($this->_ratio);

		return $ratio->field('ratio, rmb, integral')->find();
	}
}
?>