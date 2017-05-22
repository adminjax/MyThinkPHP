<?php
/**
 *
 * B2C 商城
 *
 * NOTICE OF LICENSE(许可的通知)
 *
 * DISCLAIMER
 * 
 * @Author: jax
 *
 * @category    Vendor
 * @package     Vendor_Custom
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.b2c.com)
 * @license http://www.b2c.com/license/enterprise-edition
 */

/**
 * from表单验证类
 */
class validForm
{
	/**
	 * [valid 验证入口]
	 * @Author--JAX
	 * @DateTime    2016-11-22T11:49:23+0800
	 * @param       [string] $data [需要验证的数据]
	 * @param       [type] $reg [验证的正则表达式]
	 * @param       [type] $msg [返回的提示信息]
	 * @return      [type] [description]
	 */
	public function valid($data, $reg){
		if($data && $reg){
			if(preg_match($reg, $data)){
				return 0;
			}else{
				return -2;
			}
		}else{
			return -1;//-1表示没有数据or没有正则
		}
	}
}

?>