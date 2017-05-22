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
* 社区管理类
*/
class CommunityController extends CommonController
{
	protected $pageNum = 11;

	/**
     * [_initialize 初始化]
     * @return [type] [description]
     */
    public function _initialize(){
        parent::checkLogin();
        $menu = parent::getMenu();

        $this->assign('menu', $menu);
    }

	/**
	 * [contentManage 社区内容管理]
	 * @return [type] [description]
	 */
	public function contentManage(){
		$pageF = I('get.p', '', 'int') ? I('get.p', '', 'int') : 1;
		$pageN = I('get.n', '', 'int') ? I('get.n', '', 'int') : 1;




		$data = $this->getList($pageF,$pageN);
		C("VAR_PAGE",'p');
		$pageF = $this->pageTools($data['friend']['total'], $pageF,$this->pageNum);

		C("VAR_PAGE",'n');

		$pageN = $this->pageTools($data['near']['total'], $pageN,$this->pageNum);
		$this->assign('pageF', $pageF);
		$this->assign('pageN', $pageN);
		$this->assign('data', $data);
		$this->display('contentManage');
	}

	/**
	 * [getInfoList 获取社区列表]
	 * @param  [int] $page [当前页数]
	 * @param  [int] $type [5是获取附近的信息 6是获取朋友圈的信息]
	 * @return [array] [数据]
	 */
	protected function getList($pageF,$pageN,$type=null){
		$list = array();
		try {
			$curl = A('SendRequest');
			if($type == 6){
				$data = array('type'=>1, 'page'=>$pageF, 'num'=>$this->pageNum);
				$result = $curl->curlPost(C('REQUEST').'met/community', $data);
				$list['friend'] = json_decode($result, true);
			}else if($type == 5){
				$data = array('type'=>2, 'page'=>$pageN, 'num'=>$this->pageNum);
				$result = $curl->curlPost(C('REQUEST').'met/community', $data);
				$list['near'] = json_decode($result, true);
			}else{
				$data = array('type'=>1, 'page'=>$pageF, 'num'=>$this->pageNum);
				$result = $curl->curlPost(C('REQUEST').'met/community', $data);
				$list['friend'] = json_decode($result, true);

				$data = array('type'=>2, 'page'=>$pageN, 'num'=>$this->pageNum);
				$result = $curl->curlPost(C('REQUEST').'met/community', $data);
				$list['near'] = json_decode($result, true);
			}




    		if($list['friend']['error'] && $list['near']['error']){
    			throw new \Exception("没有数据！");
    		}

    		return $list;
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * [delAction 删除消息]
	 * @return [type] [description]
	 */
	public function delAction(){
		$id = I('get.id', '', 'addslashes');
		if(I('get.type', '', 'int') == 5 || I('get.type', '', 'int') == 1){
			$type = 3;
		}elseif(I('get.type', '', 'int') == 6 || I('get.type', '', 'int') == 2){
			$type = 4;
		}

		if(!empty($id) && !empty($type)){
			try {
				$curl = A('SendRequest');
				$data = array('type'=> $type, 'id'=> $id);
				$result = $curl->curlPost(C('REQUEST').'met/community', $data);
				$result = json_decode($result, true);

				if(!empty($result['error'])){
					throw new \Exception("删除失败请重试！");
				}
				/**
			 	 * 写入日志
			 	 */
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':删除消息','fullaction' => __ACTION__,));
				$this->ajaxreturn(0);
			} catch (\Exception $e) {
				$msg = $e->getMessage();
				$this->ajaxreturn($msg);
			}
		}
	}

	/**
	 * [getDataById 根据id获取数据]
	 * @return [type] [description]
	 */
	public function getDataById(){
		$id = I('get.id', '', 'addslashes');
		if(I('get.type', '', 'int') == 5 || I('get.type', '', 'int') == 1){
			$type = 7;
		}elseif(I('get.type', '', 'int') == 6 || I('get.type', '', 'int') == 2){
			$type = 8;
		}

		if(!empty($id) && !empty($type)){
			try {
				$curl = A('SendRequest');
				$data = array('type'=>$type, 'id'=>$id);
				$result = $curl->curlPost(C('REQUEST').'met/community', $data);
				$result = json_decode($result, true);

				if(!empty($result['error'])){
					throw new \Exception("数据请求失败，请重试！");
				}
				/**
				 * 写入日志
				 */
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':获取消息数据','fullaction' => __ACTION__,));
				$this->ajaxreturn($result);
			} catch (\Exception $e) {
				$msg['error'] = $e->getMessage();
				$this->ajaxreturn($msg);
			}
		}
	}

	public function search(){
		$text = I('get.search', '', 'addslashes');
		//$text = I('get.text', '', 'addslashes');
		$type = I('get.type', '', 'int');
		$text = trim($text);
		$p = I('get.p','','int') ? I('get.p','','int') : 1;
		$n = I('get.n','','int') ? I('get.n','','int') : 1;
		$page = $p > 1 ? $p : $n;
		if(!empty($text)){
			try {
				$curl = A('SendRequest');
				$data = array('type'=>$type,'page'=>$page,'num'=>$this->pageNum, 'searchTxt'=>$text);
				$result = $curl->curlPost(C('REQUEST').'met/community', $data);
				$result = json_decode($result, true);

				if(!empty($result['error'])){
					throw new \Exception("数据请求失败，请重试！");
				}
				/**
				 * 写入日志
				 */
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':搜索消息','fullaction' => __ACTION__,));
				//$this->ajaxreturn($result);

				$data = $this->getList($p , $n , $type);
				if($type == 5){
					C("VAR_PAGE",'n');
					$pageN = $this->pageTools($data['near']['total'], $n,$this->pageNum);
					C("VAR_PAGE",'p');
					$pageF = $this->pageTools($result['total'], $p,$this->pageNum);
					$data['friend'] = $result;
					$search_textP = $text;
				}else if($type == 6){
					C("VAR_PAGE",'p');
					$pageF = $this->pageTools($data['friend']['total'], $p,$this->pageNum);
					C("VAR_PAGE",'n');
					$pageN = $this->pageTools($result['total'], $n,$this->pageNum);
					$data['near'] = $result;
					$search_textN = $text;
				}
				$this->assign('pageF',$pageF);
				$this->assign('pageN',$pageN);
				$this->assign('data',$data);
				$this->assign('search_textP',$search_textP);
				$this->assign('search_textN',$search_textN);
				$this->display('contentManage');
			} catch (\Exception $e) {
				$msg['error'] = $e->getMessage();
				$this->ajaxreturn($msg);
			}
		}else{
			$msg['error'] = '请输入想要搜索的内容！';
			$this->ajaxreturn($msg);
		}
	}
}

?>