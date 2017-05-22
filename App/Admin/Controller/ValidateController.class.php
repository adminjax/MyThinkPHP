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
 * 数据验证类
 */
class ValidateController extends CommonController
{

	private $_reg = "/^[\w]{6,12}$/";
	private $_linkReg = '/^http[s]?:\/\/(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z_!~*\'()-]+\.)*([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.[a-z]{2,6})(:[0-9]{1,4})?((\/\?)|(\/[0-9a-zA-Z_!~\*\'\(\)\.;\?:@&=\+\$,%#-\/]*)?)$/';
	private $_path = "/[\"|\']?\s*([^>\"\'\s]*)/i";


	/**
	 * [checkString 检查字符串是否合法]
	 * @param  [string] $string [要检查的字符串]
	 * @param  [string] $reg    [正则表达式]
	 * @return [bool]         [description]
	 */
	public function checkString($string, $reg = null){

		if(!$reg){
			$reg = $this->_reg;
		}

		if(preg_match($reg, $string)){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * [checkLink 验证链接]
	 * @param  [string] $link [链接地址]
	 * @param  [string] $reg  [正则]
	 * @return [bool]       [description]
	 */
	public function checkLink($link, $reg = null){
		if(!$reg){
			$reg = $this->_linkReg;
		}

		return preg_match($reg, $link);
	}

	/**
	 * [checkPath 检查路径]
	 * @param  [string] $path [需要检查的路径]
	 * @param  [string] $reg  [正则表达式]
	 * @return [type]       [description]
	 */
	public function checkPath($path, $reg = null){
		if(!$reg){
			$reg = $this->_path;
		}

		return preg_match($reg, $path);
	}
}

?>