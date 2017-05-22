<?php
/**
 * 通用后台管理工具
 *
 * @Author: jax
 *
 * @category    App
 * @package     App_Admin
 */
namespace Admin\Controller;

/**
 * 分页类
 */
class IndexController extends CommonController
{ 
	/**
	 * [page 普通分页]
	 * @return [type] [description]
	 */
	public function page(){

		$tool = $this->pageTools();
	}

	/**
	 * [ajaxPage ajax分页]
	 * @return [type] [description]
	 */
	public function ajaxPage(){

		$tool = $this->pageTools();
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