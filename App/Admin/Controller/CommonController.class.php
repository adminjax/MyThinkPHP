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
use Think\Controller;

/**
 * 公共方法，变量设置
 */
class CommonController extends Controller
{
	/**
	 * [_empty 空操作]
	 * @return [void] [提示操作错误]
	 */
	public function _empty(){
		$this->display('Empty/Index/index');
	}

	/**
	 * [checkLogin 检查是否已经登录]
	 * @return [type] [description]
	 */
	public function checkLogin(){
		if(strpos(__ACTION__, 'Index/index') > -1){
			if(session('?user.user_id')){
				$this->redirect('Info/notice');
			}
		}else{
			if(!session('?user.user_id')){
				//echo 2;
				$this->redirect('Index/index');
			}
		}
	}

	/**
	 * [checkAcl 检查权限]
	 * @param [int] [后台登录用户的resource_id]
	 * @return
	 */
	protected function checkAcl($menuArr){
		$acl = A('Acl');
		$result = $acl->checkAcl($menuArr);
		
		return $result;
	}

	/**
	 * [getMenu 获取后台menu]
	 * @return [array] [当前权限所有menu]
	 */
	public function getMenu(){
		$menuArr = C('ADMIN_MENU');

		$menuArr = $this->checkAcl($menuArr);

		$path = 'path/';
		foreach ($menuArr as $key => $value) {
			foreach ($value['low'] as $k => $v) {
				$path .= $v['path'];
			}
		}

		$allPath = $this->getAllPath();

		$currPath = substr(__ACTION__, 11);
		if((int)strpos($path, $currPath) <= 0 && (int)strpos($allPath, $currPath) > 0){
			$this->redirect('Empty/index');
		}
		
		return $menuArr;
	}

	/**
	 * [button 操作权限]
	 * @param  [string] $action [操作名]
	 * @param  [string] $text   [按钮文本]
	 * @param  [string] $acl    [权限]
	 * @return [type]         [description]
	 */
	protected function button($action, $text, $acl){
		$resource_id = A('Acl')->getResource();
		if($resource_id != 'all'){
			if(in_array($acl, $resource_id)){
				$button = '<button type="button" class="'.$action.'"><span>'.$text.'</span></button>';
			}
		}else{
			$button = '<button type="button" class="'.$action.'"><span>'.$text.'</span></button>';
		}
		
		return $button;
	}

	/**
	 * [getAllPath 获取所有menu]
	 * @return [type] [description]
	 */
	protected function getAllPath(){
		$menuArr = C('ADMIN_MENU');
		$path = 'path/';
		foreach ($menuArr as $key => $value) {
			foreach ($value['low'] as $k => $v) {
				$path .= $v['path'];
			}		
		}

		return $path;
	}

	/**
	 * [parArr 转换数据格式（decimal->float）]
	 */
	public function parArr($arr,$key)
	{
		foreach($arr as $k=>$v){
			$arr[$k][$key] = (float)$arr[$k][$key];
		}
		return $arr;
	}

	/**
	 * [pageTools 分页工具]
	 * @param  [int] $size    [总共多少条]
	 * @param  [int] $pageNum [第几页]
	 * @param  [int] $numPage    [每页多少条]
	 * @return [string]          [需要显示的html]
	 */
	protected function pageTools($size, $pageNum, $numPage){
		//分页工具栏
		$page = new \Think\Page($size, $numPage);
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('first','首页');
		$page->setConfig('last','尾页');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%  第 '.$pageNum.' 页/共 %TOTAL_PAGE% 页 ( '.$numPage.' 条/页 共 '.$size.' 条)');
		$show = $page->show();

		return $show;
	}
}
?>