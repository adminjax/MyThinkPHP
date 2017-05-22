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
use Think\Controller;

/**
 * 接口辅助类
 */
class HelperController extends Controller
{
  	/**
  	 * [getIPaddress 获取客户端ip]
  	 * @return [type] [description]
  	 */
  	public function getIpAddress(){
  		  $IPaddress='';

     		if (isset($_SERVER)){
          	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
              	$IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
          	} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
              	$IPaddress = $_SERVER["HTTP_CLIENT_IP"];
         		} else {
              	$IPaddress = $_SERVER["REMOTE_ADDR"];
         		}
      	} else {
          	if (getenv("HTTP_X_FORWARDED_FOR")){
              	$IPaddress = getenv("HTTP_X_FORWARDED_FOR");
         		} else if (getenv("HTTP_CLIENT_IP")) {
             		$IPaddress = getenv("HTTP_CLIENT_IP");
          	} else {
              	$IPaddress = getenv("REMOTE_ADDR");
          	}
      	}

      	return $IPaddress;
  	}

    /**
     * [vailderIp 检验客户端ip]
     * @param  [string] $ipAddress [ip地址]
     * @return [type]            [description]
     */
    public function vailderIp($ipAddress){
        $_limitIp = C('LIMIT_IP');
        
        if($_limitIp == $ipAddress){
          return true;
        }else{
          return false;
        }
    }

    /**
     * [getTokenServer 获取服务端token]
     * @param [string] $appid [客户端id]
     * @return [string] [token值]
     */
    public function vailderUser($appid, $appsecret){
        $id = C('APPID');
        $secret = C('APPSECRET');

        if($appid == $id && $appsecret == $secret){
          return true;
        }else{
          return false;
        }
    }

}
?>