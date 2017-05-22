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
 * 战队管理类
 */
class TeamController extends CommonController
{
	private $_team = 'team';
	protected $_pageNum = '20';
	protected $_curl;

	/**
     * [_initialize 初始化]
     * @return [type] [description]
     */
    public function _initialize(){
        parent::checkLogin();
        $menu = parent::getMenu();

        //实例化远程请求方法
        $this->_curl = A('SendRequest');

        $this->assign('menu', $menu);
    }
	
    /**
	 * [uploadify 上传图片]
	 * @return [type] [description]
	 */
	public function uploadImage(){
		if (!empty($_FILES)) {
			$file['Filedata'] = $_FILES['file'];
           	$image = A('Admin/Image');
            $image->upLoadImage($file);
          	$path = $image->getPath();
			
          	//echo $path;
          	echo json_encode(array("error"=>"0","pic"=>$path,"name"=>'0000000'));
		}
	}

	/**
	 * [teamList 战队列表入口]
	 * @return [type] [description]
	 */
	public function teamList(){
		/*
		$team = D($this->_team);
		$list = $team->getAllTeam();
		*/
		if(I('get.p', '', 'int') > 0){
			$page = I('get.p', '', 'int');
		}else{
			$page = 1;
		}


		$edit = $this->button('edit','编辑','6_1_1');
		$delete = $this->button('del','删除','6_1_2');


		$list = $this->getAllTeam($page);
		$pageTools = $this->pageTools($list['total'], $page, $this->_pageNum);

		$this->assign('pageTools', $pageTools);
		$this->assign('list', $list);
		$this->assign('edit', $edit);
		$this->assign('delete', $delete);
		$this->display('teamList');
	}

	/**
	 * [getAllTeam 获取远程战队信息]
	 * @param  [int] $page [第几页]
	 * @return [type]       [description]
	 */
	protected function getAllTeam($page){
		try{
			$curl = A('SendRequest');
			$data = array('type'=>1, 'page'=>$page, 'num'=>$this->_pageNum);
			$result = $curl->curlPost(C('REQUEST').'met/team', $data);
			$result=json_decode($result,true);

			return $result;
		}catch(\Exception $e){
			return $e->getMessage();
		}
	}

	/**
	 * [delTeam 删除战队]
	 * @return [type] [description]
	 */
	public function delTeam(){
		$id = I('get.id', '', 'addslashes');
		if(!empty($id)){
			$result = $this->_curl->curlPost(C('REQUEST').'met/team', array('type'=>3, 'id'=>$id));
			$result=json_decode($result,true);

			if($result == $id){
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':删除战队','fullaction' => __ACTION__,));
			}

			$this->ajaxreturn($result);
		}
	}

	/**
	 * [teamManage 战队管理入口]
	 * @return [type] [description]
	 */
	public function teamManage(){
		$this->display('teamManage');
	}

	/**
	 * [getTeamById 根据id请求战队信息]
	 * @param  [int] $id [战队id]
	 * @return [type]     [description]
	 */
	protected function getTeamById($id){
		try{
			$result = $this->_curl->curlPost(C('REQUEST').'met/team', array('type'=>2, 'id'=>$id));
			$result=json_decode($result,true);

			if($result['result'] == 'error'){
				throw new \Exception("请求错误！");
			}

			return $result;
		}catch(\Exception $e){
			return $e->getMessage();
		}
	}

	/**
	 * [addTeam 增该战队信息]
	 */
	public function actionTeam(){
		//成员信息
		$members = array();
		foreach ($_POST['members'] as $k => $v) {
			$members[] = array(
					'position' => $v['position'],
					'name' => $v['name'],
					'icon' => $v['avater'],
					'profession' => $v['profession'],
					'desc' => $v['desc'],
					'age' => $v['age'],
					'sex' => $v['gander'],
				);
		}

		//战队信息
		$data = array(
			'type' => 5,
			'score' => I('post.score', '', 'int'),
			'icon' => I('post.imgl', '', 'addslashes'),
			'name' => I('post.name', '', 'addslashes'),
			'desc' => I('post.brief', '', 'addslashes'),
			'sign' => I('post.decl', '', 'addslashes'),
			'bool' => I('post.ck', '', 'int'),
			'members' => $members,
		);

		$id = I('post.t_id', '', 'int');
		if(empty($id)){
			$re = $this->addTeam($data);
			if($re['result'] == true){
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':添加战队','fullaction' => __ACTION__,));

				$this->redirect('Team/teamManage'); //success
			}else{
				$this->redirect('Team/teamManage'); //fail
			}
		}
	}

	/**
	 * [addTeam 添加队伍]
	 * @param [array] $data [队伍数据]
	 */
	protected function addTeam($data){
		try{
			$result = $this->_curl->curlPost(C('REQUEST').'met/team', $data);
			$result = json_decode($result, true);
			if($result['result'] == true){
				return $result;
			}else{
				throw new \Exception("添加失败");
			}
		}catch(\Exception $e) {
			return $e->getMessage();
		}

	}

	/**
	 * [teamEdit 修改战队信息]
	 * @return [type] [description]
	 */
	public function teamEdit(){
		if(IS_GET){
			$id = I('get.id', '', 'addslashes');
			if(!empty($id)){
				$result = $this->getTeamById($id);
			}
		}
		/**
		 * 写入日志
		 */
		$log = A('Admin/LogEvent');
		$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':编辑战队信息','fullaction' => __ACTION__,));

		$this->assign('list', $result);
		$this->display('teamEdit');
	}

	/**
	 * [editTeam 修改战队信息]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function editTeam(){
		$data = array(
			'type' => 4,
			'id' => I('post.id', '', 'addslashes'),
			'score' => I('post.score', '', 'int'),
			'icon' => I('post.imgl', '', 'addslashes'),
			'name' => I('post.name', '', 'addslashes'),
			'desc' => I('post.brief', '', 'addslashes'),
			'sign' => I('post.decl', '', 'addslashes'),
			'isMaker' => I('post.ck', '', 'addslashes'),
			);
		$result = $this->_curl->curlPost(C('REQUEST').'met/team', $data);
		$result = json_decode($result, true);
		if($result['result'] == 'true'){
			/**
			 * 写入日志
			 */
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':修改战队信息','fullaction' => __ACTION__,));
			$this->redirect('Team/teamEdit', array('id'=>$data['id'])); //success
		}
	}

	/**
	 * [delTeamUser 删除队员]
	 * @return [type] [description]
	 */
	public function delTeamUser(){
		$id = I('get.id', '', 'addslashes');
		$tid = I('get.tid', '', 'addslashes');
		$data = array(
			'type' => 6,
			'teamId'=> $tid,
			'members' => array(
				'0' => array(
					'type' => 3,
					'memberId' => $id,
					),
				),
			);

		$result = $this->_curl->curlPost(C('REQUEST').'met/team', $data);
		$result = json_decode($result, true);

		/**
		 * 写入日志
		 */
		$log = A('Admin/LogEvent');
		$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':删除队员','fullaction' => __ACTION__,));
		$this->ajaxreturn($result);
	}

	/**
	 * [editTeamUser 编辑队员信息]
	 * @return [type] [description]
	 */
	public function editTeamUser(){
		if(empty(I('post.userid', '', 'addslashes'))){
			$data = array(
				'type' => 6,
				'teamId' => I('post.id', '', 'addslashes'),
				'members' => array(
					'0' => array(
						'type' => 1,
						'position' => I('post.position', '', 'int'),
						'name' => I('post.name', '', 'addslashes'),
						'icon' => I('post.avater', '', 'addslashes'),
						'profession' => I('post.profession', '', 'addslashes'),
						'desc' => I('post.desc', '', 'addslashes'),
						'age' => I('post.age', '', 'int'),
						'sex' => I('post.gander', '', 'int'),
						),
					),
				);

			$result = $this->_curl->curlPost(C('REQUEST').'met/team', $data);
			$result = json_decode($result, true);
			/**
			 * 写入日志
			 */
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':编辑队员信息','fullaction' => __ACTION__,));
			if($result['result'] == 'true'){
				$this->redirect('Team/teamEdit', array('id'=>$data['teamId'])); //success
			}
		}else{
			$data = array(
				'type' => 6,
				'teamId' => I('post.id', '', 'addslashes'),
				'members' => array(
					'0' => array(
						'type' => 2,
						'memberId' => I('post.userid', '', 'addslashes'),
						'position' => I('post.position', '', 'int'),
						'name' => I('post.name', '', 'addslashes'),
						'icon' => I('post.avater', '', 'addslashes'),
						'profession' => I('post.profession', '', 'addslashes'),
						'desc' => I('post.desc', '', 'addslashes'),
						'age' => I('post.age', '', 'int'),
						'sex' => I('post.gander', '', 'int'),
						),
					),
				);

			$result = $this->_curl->curlPost(C('REQUEST').'met/team', $data);
			$result = json_decode($result, true);

			if($result['result'] == 'true'){
				$this->redirect('Team/teamEdit', array('id'=>$data['teamId'])); //success
			}
		}
		$this->redirect('Team/teamEdit', array('id'=>$data['teamId'])); //success
	}

	/**
	 * [enroll 战队报名入口]
	 * @return [type] [description]
	 */
	public function enroll(){
		if(I('get.p', '', 'int') <= 1){
			$p = 1;
		}else{
			$p = I('get.p', '', 'int');
		}

		if(!empty(I('get.begin', '', 'addslashes'))){
			$time = str_replace('+', ' ', I('get.begin', '', 'addslashes'));
			$startTime = strtotime($time);
		}else{
			$startTime = strtotime(date("Y-m-d"));
		}

		if(!empty(I('get.end', '', 'addslashes'))){
			$time = str_replace('+', ' ', I('get.end', '', 'addslashes'));
			$endTime = strtotime($time); 
		}else{
			$endTime = time();	
		}

		$data = $this->getEnrollTeam($p, $startTime, $endTime);
		$tools = $this->pageTools($data['total'], $p, $this->_pageNum);

		$this->assign('begin', $startTime);
		$this->assign('end', $endTime);
		$this->assign('pageTools', $tools);
		$this->assign('team', $data);
		$this->display('enroll');
	}

	/**
	 * [getEnrollTeam 查询报名信息]
	 * @param  [type] $page      [description]
	 * @param  [type] $startTime [description]
	 * @param  [type] $endTime   [description]
	 * @return [type]            [description]
	 */
	protected function getEnrollTeam($page, $startTime, $endTime){
		$data = array(
			'type' => 1,
			'page' => $page,
			'num' => $this->_pageNum,
			'startTime' => $startTime,
			'endTime' => $endTime,
			);
		$result = $this->_curl->curlPost(C('REQUEST').'met/registTeam', $data);
		$result = json_decode($result, true);
		/**
		 * 写入日志
		 */
		$log = A('Admin/LogEvent');
		$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':获取报名队伍','fullaction' => __ACTION__,));
		return $result;
	}

	/**
	 * [searchEnroll 查询报名列表]
	 * @return [type] [description]
	 */
	/*
	public function searchEnroll(){
		$begin = I('get.begin', '', 'addslashes');
		$end = I('get.end', '', 'addslashes');
		$page = I('get.p', '', 'int');

		if(empty($page)){
			$page = 1;
		}

		if(!empty($begin) || !empty($end)){
			$data = array('type'=>1, 'page'=>$page, 'num'=>$this->_pageNum, 'startTime'=>$begin, 'endTime'=>$end);
			$result = $this->_curl->curlPost(C('REQUEST').'met/registTeam', $data);
			$result = json_decode($result, true);

			$tools = $this->pageTools($result['total'], $this->_pageNum, $page);
			$result['tools'] = $tools;
			$this->ajaxreturn($result);
		}
	}
    */

	/**
	 * [export 导出报名信息]
	 * @return [type] [description]
	 */
	public function export(){
		$excel = A('Excel');
		/**
		 * 写入日志
		 */
		$log = A('Admin/LogEvent');
		$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':导出报名信息','fullaction' => __ACTION__,));
		$excel->exportExcel();
	}

	public function enrollExport(){
		$begin = strtotime(I('get.begin', '', 'addslashes'));
		$end = strtotime(I('get.end', '', 'addslashes'));

		$data = array(
			'type' => 1,
			'page' => 1,
			'num' => 1000,
			'startTime' => $begin,
			'endTime' => $end,
			);
		$result = $this->_curl->curlPost(C('REQUEST').'met/registTeam', $data);
		$result = json_decode($result, true);

		vendor('PHPExcel.PHPExcel');
		$export = new \PHPExcel(); 
		$export->getProperties()->setCreator("met")  
            ->setLastModifiedBy("met")  
            ->setTitle("team enroll list")  
            ->setSubject("team enroll list")  
            ->setDescription("team enroll list.")  
            ->setKeywords("team enroll list")  
            ->setCategory("team enroll list"); 

        // 设置水平居中    
	   	$export->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

        $export->getActiveSheet()->mergeCells('A1:Q1'); 
    	$export->setActiveSheetIndex(0)  
            ->setCellValue('A1', '报名信息')  
            ->setCellValue('A2', '报名时间')  
            ->setCellValue('B2', '队伍名称')  
            ->setCellValue('C2', '挑战榜单')  
            ->setCellValue('D2', '宣言')
            ->setCellValue('E2', '联系电话')
            ->setCellValue('F2', '创建时间')
            ->setCellValue('G2', '拉票作品名称')
            ->setCellValue('H2', '拉票URL')
            ->setCellValue('I2', '打榜作品名称')
            ->setCellValue('J2', '打榜作品URL')
            ->setCellValue('K2', '备注')
            ->setCellValue('L2', '队员名称')
            ->setCellValue('M2', '性别')
            ->setCellValue('N2', '年龄')
            ->setCellValue('O2', '身份证')
            ->setCellValue('P2', '艺名')
            ->setCellValue('Q2', '标签');


        $index = 3;
    	foreach ($result['array'] as $k => $v) {
    		// 内容  
	        $export->getActiveSheet(0)->setCellValue('A'.$index, date('Y-m-d H:i:s', $v['teamRegistTime']));  
	        $export->getActiveSheet(0)->setCellValue('B'.$index, $v['teamName']);  
	        $export->getActiveSheet(0)->setCellValue('C'.$index, $v['teamTag']);  
	        $export->getActiveSheet(0)->setCellValue('D'.$index, $v['teamDeclaration']);  
	        $export->getActiveSheet(0)->setCellValue('E'.$index, $v['teamPhone']);  
	        $export->getActiveSheet(0)->setCellValue('F'.$index, date('Y-m-d H:i:s', $v['teamTime'])); 
	        $export->getActiveSheet(0)->setCellValue('G'.$index, $v['canvassName']); 
	        $export->getActiveSheet(0)->setCellValue('H'.$index, $v['canvassUrl']); 
	        $export->getActiveSheet(0)->setCellValue('I'.$index, $v['rangkingName']); 
	        $export->getActiveSheet(0)->setCellValue('J'.$index, $v['rankingUrl']); 
	        $export->getActiveSheet(0)->setCellValue('K'.$index, $v['marks']); 

	        $membersIndex = $index;
	        foreach ($v['members'] as $value) {
	        	$export->getActiveSheet(0)->setCellValue('L'.$membersIndex, $value['memberName']);  
	        	$export->getActiveSheet(0)->setCellValue('M'.$membersIndex, $value['memberSex']==0?'女':'男');  
	        	$export->getActiveSheet(0)->setCellValue('N'.$membersIndex, $value['memberAge']);  
	        	$export->getActiveSheet(0)->setCellValue('O'.$membersIndex, $value['memberIdCard']);  
	        	$export->getActiveSheet(0)->setCellValue('P'.$membersIndex, $value['memberStageName']);  
	        	$export->getActiveSheet(0)->setCellValue('Q'.$membersIndex, $value['memberSign']);  
	        	$membersIndex++;
	        }
	        
	        if($urlIndex >= $membersIndex){
	        	$index = $urlIndex;
	        }else{
	        	$index = $membersIndex;
	        }
    	}

        // Rename sheet    
	    $export->getActiveSheet()->setTitle('报名信息');  
  
	    // Set active sheet index to the first sheet, so Excel opens this as the first sheet    
	    $export->setActiveSheetIndex(0);  
  
	    // 输出  
	    header('Content-Type: application/vnd.ms-excel');  
	    header('Content-Disposition: attachment;filename="报名信息.xls"');  
	    header('Cache-Control: max-age=0');  
	  
	    $objWriter = \PHPExcel_IOFactory::createWriter($export, 'Excel5'); 
	    ob_clean(); //clear cache area

	    $objWriter->save('php://output'); 

	}
}

?>