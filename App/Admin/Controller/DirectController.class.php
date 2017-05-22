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
 * 直播管理类
 */
class DirectController extends CommonController
{
	private $_team = 'team';
	private $_report = 'report';
	private $_pageNum = '50';

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
	 * [patrol 巡查及管理]
	 * @return [type] [description]
	 */
	public function patrol(){
		if(I('get.p', '', 'int') > 0){
			$page = I('get.p', '', 'int');
		}else{
			$page = 1;
		}

		$param = I('get.search', '', 'addslashes');
		if($param){
			$live = $this->searchLive($param, $page);
		}else{
			$live  = $this->getLiveList($page);
		}
		//var_dump($live);
		$patrol_page = $this->pageTools($live['total'],$page,$this->_pageNum);

		$this->assign('livelist', $live);
		$this->assign('patrol_page', $patrol_page);
		$this->display('patrol');
	}

	/**
	 * [getLiveList 获取直播列表]
	 * @return [type] [description]
	 */
	protected function getLiveList($page){
		try{
			$curl = A('SendRequest');
			$data = array('type'=>1, 'page'=>$page, 'num'=>$this->_pageNum);
			$data = $curl->curlPost(C('REQUEST').'met/room', $data);
			$data = json_decode($data, true);

			if(empty($data['total'])){
				throw new \Exception("Error Processing Request", 1201);				
			}

			return $data;
		}catch(\Exception $e) {
			$msg = $e->getMessage();

			return $msg;
		}
	}


	/**
	 * [report 战报系统]
	 * @return [type] [description]
	 */
	public function report(){
		$live = $this->isLive();
		if($live['isOpen'] == true){
			$team1 = $this->getTeam($live['attackId']);
			$team2 = $this->getTeam($live['defenseId']);
		}

		$live['team1'] = $team1;
		$live['team2'] = $team2;

		$data = $this->getAllTeam();

		//$this->assign('report', $report);
		$this->assign('live', $live);
		$this->assign('team', $data);
		$this->display('report');
	}


	protected function getTeam($id){
		$data = array(
			'type' => 2,
			'id' => $id,
			);
		$curl = A('SendRequest');
		$data = $curl->curlPost(C('REQUEST').'met/team', $data);
		$result = json_decode($data, true);

		return $result;
	}

	protected function isLive(){
		$curl = A('SendRequest');
		$data = $curl->curlPost(C('REQUEST').'met/room', array('type'=>10));
		$arr=json_decode($data,true);

		if($arr['isOpen'] != false){
			return $arr;
		}else{
			return false;
		}
	}

	/**
	 * [getAllTeam 获取队伍列表]
	 * @return [type] [description]
	 */
	protected function getAllTeam(){
		try{
			$curl = A('SendRequest');
			$data = $curl->curlPost(C('REQUEST').'met/team', array('type'=>7));
			$arr=json_decode($data,true);

			if(!isset($arr['array'])){
				throw new \Exception("没有数据！", 1203);
			}

			return $arr;
		}catch(\Exception $e){
			return $e->getMessage();
		}
	}

	/**
	 * [searchLive 搜索直播间]
	 * @return [type] [description]
	 */
	public function searchLive($param, $page){
		//$live = I('get.live', '', 'addslashes');

		if(!empty(trim($param))){
			try{
				$curl = A('SendRequest');
				$data = $curl->curlPost(C('REQUEST').'met/room', array('type'=>3, 'searchTxt'=>trim($param), 'page'=>$page, 'num'=>$this->_pageNum));
				$arr=json_decode($data,true);

				if(!isset($arr['total'])){
					throw new \Exception("没有数据！", 1203);
				}
				return $arr;
				//$this->ajaxreturn($arr);
			}catch(\Exception $e){
				$msg = $e->getMessage();
				return $arr;
				//$this->ajaxreturn($msg);
			}
		}
	}

	/**
	 * [getLiveById 根据id请求单个数据信息]
	 * @return [json] [具体数据]
	 */
	public function getLiveById(){
		$id = I('get.id', '', 'addslashes');

		if(!empty(trim($id))){
			try{
				$curl = A('SendRequest');
				$data = $curl->curlPost(C('REQUEST').'met/room', array('type'=>2, 'id'=>$id));
				$result = json_decode($data, true);

				if($result['result'] == 'error'){
					throw new \Exception("请求错误！", 1202);
				}

				$this->ajaxreturn($result);
			}catch(\Exception $e){
				$msg = $e->getMessage();
				$this->ajaxreturn($msg);
			}
		}
	}

	/**
	 * [searchUser 用户检索]
	 * @return [type] [description]
	 */
	public function searchUser(){
		$info = trim(I('get.text', '', 'addslashes'));

		if(!empty($info)){
			try{
				$curl = A('SendRequest');
				$data = $curl->curlPost(C('REQUEST').'met/room', array('type'=>4, 'userName'=>$info, 'page'=>1, 'num'=>$this->_pageNum));
				$arr=json_decode($data,true);

				if(!empty($arr['total'])){
					throw new \Exception("没有数据！", 1204);
				}

				$this->ajaxreturn($arr);
			}catch(\Exception $e){
				$msg = $e->getMessage();
				$this->ajaxreturn($msg);
			}
		}
	}

	/**
	 * [silenced 用户禁言]
	 * @return [type] [description]
	 */
	public function silenced(){
		$useid = I('get.uesrId', '', 'addslashes');
		$roomid = I('get.roomid', '', 'addslashes');
		$minute = I('get.time', 43200, 'int');

		if($useid && $roomid){
			try{
				$curl = A('SendRequest');
				$data = $curl->curlPost(C('REQUEST').'met/room', array('type'=>12, 'id'=>$useid, 'chatroomId'=>$roomid, 'minute'=>$minute));
				$arr=json_decode($data,true);

				if(!$arr['result']){
					throw new \Exception("没有数据！", 1204);
				}

				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':用户禁言','fullaction' => __ACTION__,));
				$this->ajaxreturn($arr);
			}catch(\Exception $e){
				$msg = $e->getMessage();
				$this->ajaxreturn($msg);
			}
		}

	}

	/**
	 * [sendAffiche 发送公告]
	 * @return [type] [description]
	 */
	public function sendAffiche(){
		$affiche = I('post.affiche', '', 'addslashes');

		if(!empty(trim($affiche))){
			try{
				$curl = A('SendRequest');
				$data = $curl->curlPost(C('REQUEST').'met/room', array('type'=>5, 'content'=>trim($affiche)));
				$arr=json_decode($data,true);
	
				if($arr['result'] == false){
					throw new \Exception("发送公告失败！", 1205);
				}

				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':发送公告','fullaction' => __ACTION__,));
				$this->ajaxreturn($arr);
			}catch(\Exception $e){
				$msg = $e->getMessage();
				$this->ajaxreturn($msg);
			}
		}
	}

	/**
	 * [liveManage 直播管理]
	 * @return [type] [description]
	 */
	public function liveManage(){
		$team1 = I('post.team1', '', 'addslashes');
		$team2 = I('post.team2', '', 'addslashes');
		$title = I('post.title', '', 'addslashes');
		$flag = I('post.type', '', 'int');

		if(!empty($team1) && !empty($team2)){ // && !empty($title)
			/*
			$team = D($this->_team);			
			$result1 = $team->getTeamById($team1);
			$result2 = $team->getTeamById($team2);
			*/
			$data = array(
				'type' => 6,
				'title' => $title,
				'attackId' => $team1,
				'defenseId' => $team2,
				);
		}else{
			$this->ajaxreturn('请完整填写数据！');
			return;
		}

		if(!empty($flag)){
			$curl = A('SendRequest');
			switch ($flag) {
				case '1':
					$flag = array('flag'=>1);
				break;
				
				case '2':
					$flag = array('flag'=>2);
				break;

				case '3':
					$flag = array('flag'=>3);
				break;
			}
			$data = array_merge($data, $flag);
			$data = $curl->curlPost(C('REQUEST').'met/room', $data);
			$result=json_decode($data,true);
			/**
			 * 写入日志
			 */
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':直播管理','fullaction' => __ACTION__,));
			$this->ajaxreturn($result);
		}
	}

	/**
	 * [getTeamById 根据id获取队伍信息]
	 * @return [type] [description]
	 */
	public function getTeamById(){
		$attackId = I('get.attackId', '', 'addslashes');

		if(!empty($attackId)){
			$data = array(
				'type' => 2,
				'id' => $attackId,
				);
		}

		try{
			$curl = A('SendRequest');
			$data = $curl->curlPost(C('REQUEST').'met/team', $data);
			$result = json_decode($data, true);

			if(empty($result['id'])){
				throw new \Exception("请求错误！", 1208);
			}
			
			$this->ajaxreturn($result);
		}catch(\Exception $e){
			$msg = $e->getMessage();
			$this->ajaxreturn($msg);
		}
	}

	/**
	 * [sendReport 发送战报]
	 * @return [type] [description]
	 */
	public function sendReport(){
		$text = I('post.event', '', 'addslashes');

		if(!empty($text)){
			$data = array(
				'type' => 7,
				'event' => $text,
				);

			try{
				$curl = A('SendRequest');
				$data = $curl->curlPost(C('REQUEST').'met/room', $data);
				$result = json_decode($data, true);

				if($result['error']){
					throw new \Exception("请求错误！", 1208);
				}
				/**
				 * 写入日志
				 */
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':发送战报','fullaction' => __ACTION__,));
				$this->ajaxreturn($result);
			}catch(\Exception $e){
				$msg = $e->getMessage();
				$this->ajaxreturn($msg);
			}
		}
	}

	/**
	 * [editRepost 编辑战报]
	 * @return [type] [description]
	 */
	public function editReport(){
		$id = I('post.id', '', 'int');
		$event = I('post.event', '', 'addslashes');

		if(!empty($id) && !empty($event)){
			$data = array(
				'type' => 8,
				'id' => $id,
				'event' => $event,
				);

			try{
				$curl = A('SendRequest');
				$data = $curl->curlPost(C('REQUEST').'met/room', $data);
				$result = json_decode($data, true);

				if($result['error']){
					throw new \Exception("请求错误！", 1208);
				}
				/**
				 * 写入日志
				 */
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':编辑战报','fullaction' => __ACTION__,));
				$this->ajaxreturn($result);
			}catch(\Exception $e){
				$msg = $e->getMessage();
				$this->ajaxreturn($msg);
			}
		}
	}

	/**
	 * [delRepost 删除战报]
	 * @return [type] [description]
	 */
		public function delReport(){
		$id = I('get.id', '', 'int');

		if(!empty($id)){
			try{
				$curl = A('SendRequest');
				$data = array('type'=> 9, 'id' => $id);
				$data = $curl->curlPost(C('REQUEST').'met/room', $data);
				$result = json_decode($data, true);

				if($result['result'] == false){
					throw new \Exception("请求错误！", 1209);
				}
				/**
				 * 写入日志
				 */
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':删除战报','fullaction' => __ACTION__,));
				$this->ajaxreturn($result);
			}catch(\Exception $e){
				$msg = $e->getMessage();
				$this->ajaxreturn($msg);
			}
		}else{
			$this->ajaxreturn('选择要删除的战报！');
		}
	}
}

?>