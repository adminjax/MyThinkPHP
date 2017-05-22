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
* 视频管理
*/
class VideoController extends CommonController
{

	private $num = 50;//每页显示50条
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
	 * [getUrlPostInfo 获取视频数据并进行处理]
	 */
	private function getUrlPostInfo($post_array=array())
	{
		$curl = A('SendRequest');
		$url = C('CURL_');
		$info = $curl->curlPost($url.'met/video ',$post_array['data']);
		if(!empty($info))
		{
			//写入日志
			$this->log($post_array['work']);
			//请求地址后返回的视频数据
			$array_info = json_decode($info,true);
		}
		$page = $this->pageTools($array_info['size'],$post_array['page'],$this->num);
		$this->assign('video_info',$array_info['array']);
		$this->assign('page',$page);
		$this->assign('search_info',$post_array['data']['name']);
	}

    /**
     * [manage 视频管理入口]
	 * @param [type = 1，是视频管理；type = 2，是视频审核]
	 * @param [int] $page [第几页]
	 * @param [string] $work [进行的操作]
     * @return [type] [description]
     */
	public function manage($type=null,$page=null,$work=null){
		$work = $work ? $work : '审核内容列表';
		$type = $type ? $type : I('get.type','','int');
		$page = $page ? $page : I('get.p',1,'int');
		$data = array(
			'page'=>$page,
			'num'=>$this->num,
			'type'=>$type
		);
		$del = $this->button('delete','删除','4_1_1');
		$this->assign('del',$del);
		/** @param [string] $work [进行的操作]
		 *  @param [array] $data [传入的数据]
		 *  @param int $page [第几页]
		 */
		$post_array = array(
			'data' => $data,
			'page' => $page,
			'work' => $work,
		);
		$this->getUrlPostInfo($post_array);
		if($type == 1)
			$this->display('manage');
		else if($type == 2)
			$this->display('audit');
	}

	/**
	 * [audit 视频审核入口]
	 * @return [type] [description]
	 */
	public function audit(){
		$type = I('get.type','','int');
		$page = I('get.p',1,'int');
		$work = '审核视频列表';
		$audit = $this->button('audit','审核','4_2_1');
		$this->assign('audit',$audit);
		$this->manage($type,$page,$work);
	}

	/**
	 * [delData 删除视频]
	 * @param [string] $work [进行的操作]
	 * @param [int] $vid [视频ID]
	 * @param [string] $type [视频类型]
	 * @return bool
	 */
	public function delData($work=null,$vid=null,$type=null)
	{
		$work = $work ? $work : '删除视频';
		$type = $type ? $type : I('post.type','','addslashes');
		$vid = $vid ? $vid : I('post.vid','','addslashes');
		$url = C('CURL_');
		if($vid)
		{
			//写入日志
			$this->log($work);
			$vid = array($vid);
			$data = array(
				'ids'=>$vid,
				'type'=>$type
			);
			$curl = A('SendRequest');
			$res = $curl->curlPost($url.'met/video ',$data);
			$res = json_decode($res);
			if($res->result){
				echo 1;
			}else{
				echo 0;
			}
			return false;
		}
		echo 0;
	}

	/**
	 * [pass_audit 审核视频]
	 * @return bool
	 */
	public function pass_audit()
	{
		$type = I('post.type','','addslashes');
		$vid = I('post.vid','','addslashes');
		$work = '通过视频审核';
		$this->delData($work,$vid,$type);
	}

	/**
	 * [video_search 搜索视频]
	 */
	public function video_search()
	{
		$content = I('post.search','','addslashes');
		$page = I('get.p',1,'int');
		$search_type = I('get.type','','int');
		$work = '搜索视频';
		$data = array(
			'name'=>$content,
			'page'=>$page,
			'num'=>$this->num,
			'type'=>5,
			'category' => $search_type
		);
		/** @param [string] $work [进行的操作]
		 *  @param [array] $data [传入的数据]
		 *  @param int $page [第几页]
		 */
		$post_array = array(
			'data' => $data,
			'page' => $page,
			'work' => $work,
		);

		$this->getUrlPostInfo($post_array);
		if($search_type == 1){
			$del = $this->button('delete','删除','4_1_1');
			$this->assign('del',$del);
			$this->display('manage');
		}
		else if($search_type == 2){
			$audit = $this->button('audit','审核','4_2_1');
			$this->assign('audit',$audit);
			$this->display('audit');
		}
	}

	/**
	 * [log 写入日志信息]
	 * @param [string] $work [日志信息]
	 */
	private function log($work)
	{
		$log = A('Admin/LogEvent');
		$data = array(
			'action' => ACTION_NAME,
			'info' => session('user.username').':'.$work.'',
			'status' => '1',
			'fullaction' => __ACTION__,
			'error_message' => '',
		);
		$log->writeLog($data);
	}
}


?>