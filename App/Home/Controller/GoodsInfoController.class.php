<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/3
 * Time: 16:13
 */

namespace Home\Controller;


class GoodsInfoController extends CommonController
{
    private $account;

    /**
     * [_initialize 检验帐号是否合法]
     * @return [type] [description]
     */
    public function _initialize(){
        //$this->account = $this->checkAccont();
    }

    /**
     * [getGoodsInfo 获取商品信息]
     * @return bool
     */
    public function getGoodsInfo()
    {
        $g_id = I('get.id','','int');
        $this->account = session('account');
        if(empty($g_id) || empty($this->account))
        {
            $this->assign('pageFirst',C('WEB_SITE'));
            $this->display('Empty/index');
            return false;
        }
        $goods = M('goods');
        $where = array('goods.g_id'=>$g_id);
        $goods_info = $goods->join('LEFT JOIN goods_info ON goods_info.g_id=goods.g_id')->where($where)->getField('goods_info.g_id,goods.integral,goods.goods_num,goods.title,goods_info.img,goods_info.content,goods_info.desc');
        if(empty($goods_info))
        {
            $this->assign('pageFirst',C('WEB_SITE'));
            $this->display('Empty/index');
            return false;
        }
        foreach($goods_info as $k => $v){
            $goods_info[$k]['content'] = htmlspecialchars_decode(htmlspecialchars_decode($goods_info[$k]['content']));
            $goods_info[$k]['integral'] = (float)$goods_info[$k]['integral'];
        }
        $user_inte = $this->getUserInte($this->account);
        $this->assign('pageFirst',C('WEB_SITE'));
        $this->assign('user_inte',$user_inte);
        $this->assign('goods_info',$goods_info);
        $this->display('goodsInfo/getGoodsInfo');
    }

    /**
     * [ruleInte 兑换积分]
     */
    public function ruleInte()
    {
        $goods_num = I('post.goods_num');//兑换商品数量
        $goods_id = I('post.goods_id');//兑换商品编号
        $goods_inte = I('post.goods_inte');//兑换商品积分
        $goods_title = I('post.goods_title');//商品名
        $goods_inte = (float)$goods_inte;

        if(empty($goods_num) || empty($goods_inte)|| empty($goods_id))
        {
            echo 0;
            return false;
        }
        $my_inte = $this->getUserInte(session('account'));//总积分
        $use_inte = (float)$goods_num * (float)$goods_inte;//使用的积分
        $inte = (float)$my_inte - (float)$use_inte;//剩余积分

        if(($inte < 0))
        {
            echo 1;
            return false;
        }

        $card_num = $this->create_guid();
        if(!session('account')){
            echo 0;
            return false;
        }
        $user = session('account');
        $data_code = array(
            'account'=>$user,
            'code'=>$card_num,
            'sku'=>$goods_id,
            'num'=>$goods_num,
            'integral'=>$goods_inte,
            'created'=>time(),
            'status'=>1,
            'modified'=>'',
            'username'=>''
        );
        $data_card = array(
            'integral'=>$inte,
            'updated'=>time()
        );
        $desc = '积分兑换商品、'.$goods_title.' 消耗积分';

        $inte_code = M('redeem_code');
        $inte_info = M('integral_info');
        $inte_card = M('integral_card');
        $inte_code->startTrans();
        $inte_code_res = $inte_code->data($data_code)->add();
        $data_info = array(
            'account'=>$user,
            'use'=>$use_inte,
            'desc'=>$desc,
            'g_num'=>$goods_id,
            'created'=>time(),
            'num'=>$goods_num,
            'is_scene' => $inte_code_res
        );
        $inte_info_res = $inte_info->data($data_info)->add();
        $inte_card_res = $inte_card->where(array('account'=>$user))->save($data_card);
        if($inte_code_res && $inte_info_res && $inte_card_res)
        {
            $inte_code->commit();
            echo 2;
            return false;
        }else{
            $inte_code->rollback();
            echo 3;
            return false;
        }
    }

    /**
     * [create_guid 兑换码]
     * @param string $namespace
     * @return string
     */
    public function create_guid($namespace = '') {
        static $guid = '';
        $uid = uniqid("", true);
        $data = $namespace;
        $data .= $data;
        $data .= $_SERVER['HTTP_USER_AGENT'];
        $data .= $_SERVER['LOCAL_ADDR'];
        $data .= $_SERVER['LOCAL_PORT'];
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid =
            substr($hash,  0,  4) .
            '-' .
            substr($hash,  8,  4) .
            '-' .
            substr($hash, 12,  4) .
            '-' .
            substr($hash, 16,  4) .
            '-' .
            substr($hash, 20, 4)
            ;
        return $guid;
    }
}