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
 * [请求接口器]
 */
class SendRequestController extends CommonController
{
	/**
	 * [curlPost post请求远程数据]
	 * @param  [string] $url   [远程地址]
	 * @param  [array] $param [请求参数]
	 * @return [json]        [返回数据]
	 */
	public function curlPost($url, $param){
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
	 * 发送HTTP请求，获取响应结果
	 *
	 * @param string $method 请求方法
	 * @param string $url 请求地址
	 * @param array $params 请求体参数
	 * @param array $headers 请求头
	 * @param bool|string $https 是否启用证书认证，可传入证书路径
	 *
	 * @return array
	 */
	public function sendRequest($method, $url, $params = [], $headers = [], $https = false)
	{
	    // 标准化请求方法
	    $method = strtoupper($method);

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);

	    // 写入Headers
	    if ($headers) {
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    }

	    // 控制请求参数
	    if ($method != 'GET') {
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

	        if ($method != 'POST') {
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	        }
	    }

	    // Https证书
	    if (stripos($url, 'https') === 0) {
	        if ($https && is_string($https)) { // 传入证书路径
	            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	            curl_setopt($ch, CURLOPT_CAINFO , $https);
	        } else {
	            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $https ? 2 : 0);
	            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $https ? 1 : 0);
	        }
	    }

	    curl_exec($ch);
	    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    $content = curl_multi_getcontent($ch);
	    curl_close($ch);

	    return [
	        'status' => $status,
	        'content' => $content
	    ];
	}
}

?>