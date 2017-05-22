<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/17
 * Time: 14:38
 */

namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller
{
   /*
     * 空操作
     */
    public function _empty(){
        $this->assign('pageFirst',C('WEB_SITE'));
        $this->display('Empty/index');
    }

    /**
     * [getUserInte 用户积分]
     * @param $user [用户]
     * @return bool
     */
    public function getUserInte($user)
    {
        $inte_card = M('integral_card');
        $integral = $inte_card->where(array('account'=>$user))->getField('integral');
        if(empty($integral)){
            $integral = 0;
        }
        return (float)$integral;
    }

    /**
     * [parArr 转换数据格式(decimal->float)]
     */
    public function parArr($arr,$key){
        foreach($arr as $k=>$v){
            $arr[$k][$key] = (float)$arr[$k][$key];
        }
        return $arr;
    }

    /**
     * [curlPost post请求远程数据]
     * @param  [string] $url   [远程地址]
     * @param  [array] $param [请求参数]
     * @return [json]        [返回数据]
     */
    protected function curlPost($url, $param){
        //请求初始化
        $curl = curl_init();

        if($url){
            curl_setopt($curl, CURLOPT_URL, $url); //提交请求url
        }

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_POST, 1);  //post方式提交

        if($param){
            $post_data = json_encode($param);
             //设置post数据
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        }

        $data = curl_exec($curl);  //执行请求
        //$data = curl_errno($curl);
        curl_close($curl);  //关闭请求

        return $data;
    }

    /**
     * [checkAccont 验证账户]
     * @return [type] [description]
     */
    protected function checkAccont(){
        $data = array(
            'type' => 6,
            'account' => I('get.account'),
            'password' => I('get.password'),
            'token' => I('get.token'),
            );

        $flag = $this->curlPost(C('REQUEST').'met/user', $data);
        $flag = json_decode($flag, true);

        if($flag['result'] == false){
            $this->redirect('Empty/index');
        }

        return $data['account'];
    }
}