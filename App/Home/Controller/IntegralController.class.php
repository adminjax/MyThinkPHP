<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/4
 * Time: 16:21
 */

namespace Home\Controller;


class IntegralController extends CommonController
{
    private $account;
    private $integral_num = 6;//显示条数

    /**
     * [_initialize 检验帐号是否合法]
     * @return [type] [description]
     */
    public function _initialize(){
        //$this->account = $this->checkAccont();
    }

    /**
     * [integralInfo 积分详情]
     */
    public function integralInfo()
    {
        $user = session('account');
        $p = I('get.p','','int') ? I('get.p','','int') : 1;
        $li = ($p * $this->integral_num) - $this->integral_num;
        $inte_info = M('integral_info');
        $use['use'] = array('neq',0);
        $get['get'] = array('neq',0);
        $goods_sel = $inte_info->where($use)->field('if_id')->select();
        foreach($goods_sel as $k => $v){
            $goods_tab[] = $v['if_id'];
        }
        $goods_tab = implode($goods_tab,',');
        $product_sel = $inte_info->where($get)->field('if_id')->select();
        foreach($product_sel as $k => $v){
            $product_tab[] = $v['if_id'];
        }
        $product_tab = implode($product_tab,',');
        if($goods_tab){
            $where['integral_info.account'] = $user;
            $where['integral_info.if_id'] = array('in',$goods_tab);
            $goods_inte_info = $inte_info->join('LEFT JOIN goods ON goods.goods_num=integral_info.g_num')
                ->where($where)->order('integral_info.created desc')->limit("$li,$this->integral_num")
                ->getField('integral_info.if_id,integral_info.use,integral_info.get,integral_info.desc,
            integral_info.created,integral_info.num,integral_info.g_num,integral_info.is_scene,goods.title');
        }
        if($product_tab) {
            $where['integral_info.account'] = $user;
            $where['integral_info.if_id'] = array('in',$product_tab);
            $product_inte_info = $inte_info->join('LEFT JOIN product ON product.sku=integral_info.g_num')
                ->where($where)->order('integral_info.created desc')->limit("$li,$this->integral_num")
                ->getField('integral_info.if_id,integral_info.use,integral_info.get,integral_info.desc,
                integral_info.created,integral_info.num,integral_info.is_scene,product.sku,product.name');
        }
        if(empty($goods_inte_info)){
            $inte_info = $product_inte_info;
        }else if(empty($product_inte_info)){
            $inte_info = $goods_inte_info;
        }else{
            $inte_info = array_merge_recursive($goods_inte_info,$product_inte_info);
        }
        if (!empty($inte_info))
            $this->assign('inte_info', $inte_info);
        else {
            $this->assign('inte_info', '');
        }

        if($p != 1){
            $this->display('integral_table');
        }else{
            $user_inte = $this->getUserInte($user);
            $this->assign('user_inte',$user_inte);
            $this->display('integralInfo');
        }

    }

    /**
     * [orderInfo 订单详情]
     */
    public function orderInfo()
    {
        $g_num = I('get.g_num');
        $is_scene = I('get.is_scene');
        $type = I('get.type');
        $id = I('get.id');
        if(empty($g_num))
        {
            $this->assign('pageFirst',C('WEB_SITE'));
            $this->display('Empty/index');
            return false;
        }
        if($type == 1 || $type == 3){
            $goods = M('goods');
            if($type == 3){//goods表(兑换码兑换)
                $where = array('redeem_code.rc_id'=>$is_scene);
                $order_info = $goods->join('LEFT JOIN redeem_code ON goods.goods_num=redeem_code.sku')->join('LEFT JOIN goods_info ON goods_info.g_id=goods.g_id')->where($where)->getField('redeem_code.rc_id,redeem_code.code,redeem_code.num,redeem_code.integral,redeem_code.status,goods.title,goods_info.header_img');
            }else{//goods表(商品兑换)
                $where['integral_info.if_id'] = $id;
                $where['integral_info.g_num'] = $g_num;
                $order_info = $goods->join('LEFT JOIN integral_info ON goods.goods_num=integral_info.g_num')->join('LEFT JOIN goods_info ON goods_info.g_id=goods.g_id')->where($where)->getField('goods.title,goods_info.header_img,integral_info.num');
            }

        }else if($type == 2){//product表
            $product = M('integral_info');
            $where['integral_info.g_num'] = $g_num;
            $where['integral_info.if_id'] = $id;
            $order_info = $product->join('LEFT JOIN product ON integral_info.g_num=product.sku')->where($where)->getField('product.name,product.price,product.img,integral_info.num');
        }
        if(!empty($order_info)){
            $this->assign('order_info',$order_info);
        }else{
            $this->assign('order_info','');
        }
        $this->display('orderInfo');
    }
}