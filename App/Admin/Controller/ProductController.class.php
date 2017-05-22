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
 * [商品控制器]
 */
class ProductController extends CommonController
{
	private $_product = 'product';
	private $_product_order = 'product_order';
	private $_redeem = 'redeem_code';

	public function addCart(){
		$sku = I('get.product', '', 'addslashes');
		//获取产品数据
		$product = D($this->_product);
		$re = $product->getProduct($sku);
		//加入购物车
		if(cookie('cart')){
			$cart = unserialize(cookie('cart'));
		}
		$cart[] = array('sku'=>$re['sku'], 'num'=>1);
		cookie('cart', serialize($cart));

		$this->ajaxreturn($re);

	}

	/**
	 * [reduceNum 增加购物车中商品数量]
	 * @return [type] [description]
	 */
	public function addNum(){
		$sku = I('get.sku', '', 'addslashes');
		$num = I('get.num', '', 'int');

		$cart = unserialize(cookie('cart'));
		foreach ($cart as $key => $value) {
			if($value['sku'] == $sku){			
				$cart[$key]['num'] = $num;
			}
		}

		cookie('cart', serialize($cart));
		return 0;
	}

	/**
	 * [reduceNum 减少购物车中商品数量]
	 * @return [type] [description]
	 */
	public function reduceNum(){
		$sku = I('get.sku', '', 'addslashes');
		$num = I('get.num', '', 'int');

		$cart = unserialize(cookie('cart'));
		foreach ($cart as $key => $value) {
			if($value['sku'] == $sku){			
				$cart[$key]['num'] = $num;
			}
		}

		cookie('cart', serialize($cart));
		return 0;

	}

	/**
	 * [delCart 删除购物车中商品]
	 * @return [type] [description]
	 */
	public function delCart(){
		$sku = I('get.sku', '', 'addslashes');

		$cart = unserialize(cookie('cart'));
		foreach ($cart as $key => $value) {
			if($value['sku'] == $sku){			
				unset($cart[$key]);
			}
		}

		cookie('cart', serialize($cart));
		return 0;
	}

	/**
	 * [setRatio 设置比例]
	 */
	public function setRatio(){
		$rmb = I('post.rmb', '', 'addslashes');
		$int = I('post.int', '', 'addslashes');

		if($rmb <= 0 || $int <= 0){
			$re = 1;
			$this->ajaxreturn($re);
		}

		$product = D($this->_product);
		$re = $product->setRatio($rmb, $int);
		if($re){
			$re = 0; //
		}

		$log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => '设置人民币兑换积分比例','fullaction' => __ACTION__,));
		$this->ajaxreturn($re);
	}

	/**
	 * [getRatio 获取比例]
	 * @return [type] [description]
	 */
	public function getRatio(){
		$product = D($this->_product);
		$re = $product->getRatio();

		$this->ajaxreturn($re);
	}

	/**
	 * [checkout 结账]
	 * @return [type] [description]
	 */
	public function checkout(){

		$data = array(
			'order_number' => sha1(I('post.shiper', '', 'addslashes').time()),
			'shiper' => I('post.shiper', '', 'addslashes'),
			'username' => session('user.username'),
			'amount' => I('post.amount', '', 'float'),
			'total' => I('post.total', '', 'float'),
			'change' => I('post.change', '', 'float'),
			'ratio' => I('post.intratio', '', 'float'),
			'integral' => I('post.intere', '', 'float'),
			'created' => time(),
			);

		$order = D($this->_product_order);
		
		try{
			//开始事物
			$order->startTrans();

			$data = $order->checkout($data);

			if($data){
				$order->commit();
			}else{
				$order->rollback();
			}

          	$log = A('Admin/LogEvent');
          	$log->writeLog(array('action' => ACTION_NAME,'info' => '现场购物','fullaction' => __ACTION__,));
			$this->redirect('Integral/magange');
		}catch(\Exception $e){
			$msg = $e->getMessage();

			$this->redirect('Integral/magange');
		}		
	}

	/**
	 * [getRedeem 获取兑换码中的商品]
	 * @return [type] [description]
	 */
	public function getRedeem(){
		$code = I('get.code', '', 'addslashes');
		$sku = I('get.sku', '', 'addslashes');

		if($code){
			$redeem = D($this->_redeem);
			$data = $redeem->getRedeem($code);
		}

		if($sku){
			$redeem = D($this->_redeem);
			$data = $redeem->seachGoods($sku);
		}

		$log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => '现场兑换商品','fullaction' => __ACTION__,));
		$this->ajaxreturn($data);
	}

	/**
	 * [redeem 兑换商品]
	 * @return [type] [description]
	 */
	public function redeem(){
		$code = $_POST['code'];
		$account = $_POST['account'];

		$sku = $_POST['sku'];
		$innum = $_POST['innum'];

		$redeem = D($this->_redeem);
		try{
			//使用兑换码兑换
			if($code){
				$redeem->startTrans(); //开启事物
				foreach($code as $k => $v){
					$re = $redeem->actionRedeem($v, $account[$k]);
					if($re <= 0){
						throw new \Exception("请检查兑换码或兑换码以使用！");
						break;		
					}
				}

				$redeem->commit();	//提交
			}
			//现场兑换
			if($sku){
				foreach($account as $k => $v){
					$redeem->startTrans(); //开启事物
					$re = $redeem->goodsRedeem($v, $sku[$k], $innum[$k]);
					if($re <= 0){
						throw new \Exception("请检查兑换码或兑换码以使用！");
						break;		
					}
					$redeem->commit();	//提交
				}
			}
			
			$this->redirect('Integral/magange');
		}catch(\Exception $e){
			$redeem->rollback(); //回滚
			$msg = $e->getMessage();

			$this->redirect('Integral/magange');
		}
	}
}
?>