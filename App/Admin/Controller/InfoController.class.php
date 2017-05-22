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
 * 资讯管理类
 */
class InfoController extends CommonController
{
	private $_subject = 'subject';
	private $_tag = 'tag';
	protected $pageNum = 25;
	protected $tagNum = 5;
	protected $result;

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
	 * [notice 公告入口]
	 * @return [type] [description]
	 */
	/*
	public function notice(){
		$field_name = 'n_id,title,img,link';
		$notice_info = M('notice')->order('created desc')->getField($field_name);

		if(!empty($notice_info))
		{
			$this->assign('notice_info', $notice_info);
		}

		$add = $this->button('add', '添加公告', '1_1_1');
		$delete = $this->button('delete', '删除公告', '1_1_2');
		$save = $this->button('save', '保存并提交至审核', '1_1_4');

		$this->assign('add', $add);
		$this->assign('delete', $delete);
		$this->assign('save', $save);

		$this->display();
	}
	*/

	/**
	 * [subject 专题入口]
	 * @return [type] [description]
	 */
	public function subject(){
		if(IS_GET){
			$p = I('get.p', '1', 'int');
		}else{
			$p = 1;
		}
		//获取本地资讯那些进行了修改
		$subject = M('subject');
		$where['is_active'] = array('in','3,4');
		$res = $subject->where($where)->field('is_active,re_id')->select();

		//获取远程接口数据
		$curl = A('SendRequest');
		$data = $curl->curlPost(C('REQUEST').'met/informamtion ', array('page'=>$p, 'num'=>$this->pageNum, 'type'=>1));
		$arr=json_decode($data,true);
		$arr2=array();

		foreach($arr['informations'] as $val){
			$arr2[] = $val;
		}

		//分页工具栏
		$show = $this->pageTools($arr['size'], $p, $this->pageNum);

		//获取远程标签数据
		$data = $curl->curlPost(C('REQUEST').'met/informamtion ', array('type'=>5));
		$tag=json_decode($data,true);

		foreach ($tag as $key => $value) {
			$tag = $value;
		}
		unset($tag[0]);

		if($res){
			foreach($res as $k => $v){
				foreach($arr2 as $k1 => $v1){
					if($v['re_id'] == $v1['id']){
						if($v['is_active'] == 3) $arr2[$k1]['au'] = 3;
						if($v['is_active'] == 4) $arr2[$k1]['au'] = 4;
						break 1;
					}
				}
			}
		}

		//添加button
		$add = $this->button('add', '添加公告', '1_2_1');
		$delete = $this->button('delete', '删除公告', '1_2_2');
		$save = $this->button('save', '保存并提交至审核', '1_2_4');

		$this->assign('tag', $tag);
		$this->assign('add', $add);
		$this->assign('delete', $delete);
		$this->assign('save', $save);
		$this->assign('list', $arr2);
		$this->assign('page', $show);
		$this->assign('msg', cookie('msg')?cookie('msg'):cookie('error'));


		/**
		 * 写入日志
		 */
		$log = A('Admin/LogEvent');
		$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':浏览专题','fullaction' => __ACTION__,));
		$this->display('subject');
	}

	/**
	 * [setNotice 添加专题]
	 * [setNotice description]
	 */
	public function setNotice(){
		$imgPath = I('post.imgl');//图片地址
		$link = I('post.links');//链接地址

		$validate = A('Validate');
		$url = $validate->checkLink($link);
		$path = $validate->checkPath($imgPath);
		$time = time();
		if($url && $path){
			$data = array(
				'title'=>'',
				'img'=>$imgPath,
				'link'=>$link,
				'sort'=>'',
				'created'=>$time,
				'column'=>''
			);
			if(M('notice')->add($data))
			{
				$this->success('添加成功！');
				/**
				 * 写入日志
				 */
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':添加专题','fullaction' => __ACTION__,));
				return false;
			}
		}
		$this->error ('不能提交空白信息！');
	}


	/**
	 * [uploadify 上传图片]
	 * @return [type] [description]
	 */
	public function uploadImage(){
		if (!empty($_FILES)) {
			$image = A('Admin/Image');
			$path = $image->upLoadImage($_FILES);
			/**
			 * 写入日志
			 */
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':上传图片','fullaction' => __ACTION__,));

			echo $path;
		}
	}

	/**
	 * [audit 审核入口]
	 * @return [type] [description]
	 */
	public function audit(){
		$p = I('get.p',1,'int');
		$page = $this->getAllInfo($p);
		$tag = $this->getAllTag($p);

		//添加button
		$refuse = $this->button('tag-refuse', '拒绝', '1_4_1');
		$adopt = $this->button('tag-adopt', '通过', '1_4_2');

		$refuseSub = $this->button('refuse-sub', '拒绝', '1_4_3');
		$adoptSub = $this->button('adopt-sub', '通过', '1_4_4');

		$this->assign('refuseSub', $refuseSub);
		$this->assign('adoptSub', $adoptSub);
		$this->assign('refuse', $refuse);
		$this->assign('adopt', $adopt);

		$this->assign('list', $page['list']);
		$this->assign('page', $page['show']);
		$this->assign('taglist', $tag['taglist']);
		$this->assign('tagpage', $tag['tagshow']);
		$this->display('audit');
	}

	/**
	 * [getAllInfo 获取所有专题]
	 * @return [array] [专题信息]
	 */
	protected function getAllInfo($p){
		$info = D($this->_subject);

		//获取记录总条数
		$data = $info->getInfoCount();
		$show = $this->pageTools($data,$p,$this->pageNum);
		$nu = ($p * $this->pageNum) - $this->pageNum;
		if($nu < 0) $nu = 0;
		$list = $info->getPage($nu, $this->pageNum);

		return array('list'=>$list, 'show'=>$show);
	}

	protected function getAllTag($p){
		$tag = D($this->_tag);
		//获取待审核总条数
		$count = $tag->getTagCount();
		//分页
		$show = $this->pageTools($count,$p,$this->pageNum);
		$nu = ($p * $this->pageNum) - $this->pageNum;
		if($nu < 0) $nu = 0;
		$list = $tag->getAllTag($nu,$this->pageNum);

		return array('taglist'=>$list, 'tagshow'=>$show);
	}

	/**
	 * [getInfo 获取专题]
	 * @return [type] [description]
	 */
	public function getInfo(){
		$id = I('get.id', '', 'int');
		$active = I('get.active', '', 'int');
		if($id > 0){
			$info = D($this->_subject);
			$data = $info->getInfo($id,$active);
			foreach($data as $k => $v){
				$data[$k]['content'] = htmlspecialchars_decode($data[$k]['content']);
			}
		}
		$this->ajaxreturn($data);
	}

	/**
	 * [delSub 删除专题]
	 * @return [type] [description]
	 */
	public function refuse(){
		$id = I('get.id', '', 'addslashes');
		$id = explode(',', $id);
		$id = array_filter($id);

		//拒绝操作
		try {
			$info = D($this->_subject);
			foreach ($id as $k => $v) {
					$re = $info->refuseSubject($v);

				if($re != 0){
					break;
				}
			}
			/**
			 * 写入日志
			 */
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':拒绝专题通过审核','fullaction' => __ACTION__,));
			return 0; //操作成功
		} catch (Exception $e) {
			$msg = $e->getMessge();
			/**
			 * 写入日志
			 */
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':拒绝专题审核失败','fullaction' => __ACTION__,));
			return 1; //操作失败
		}
	}

	/**
	 * [adopt 通过操作]
	 * @return [type] [description]
	 */
	public function adopt(){
		$ids = I('post.ids', '', 'addslashes');
		$id = explode(',', $ids);
		$id = array_filter($id);

		$idArr = array();
		foreach ($id as $k => $v) {
			$arr = explode(':', $v);
			$idArr[$k]['id'] = $arr[1];
			$idArr[$k]['type'] = $arr[0];
		}

		$data = $this->actionSub($idArr);

		$this->ajaxreturn($data);
	}

	/**
	 * [tagRefuse 拒绝标签通过]
	 * @return [type] [description]
	 */
	public function tagRefuse(){
		$ids = I('get.id', '', 'addslashes');
		$id = explode(',', $ids);
		$id = array_filter($id);

		$tag = D($this->_tag);
		foreach ($id as $k => $v) {
			$re = $tag->refuse($v);

			if(!$re){
				return 1;//
			}
		}
		/**
		 * 写入日志
		 */
		$log = A('Admin/LogEvent');
		$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':拒绝标签通过成功','fullaction' => __ACTION__,));
		return 0; //成功
	}

	/**
	 * [tagAdopt 通过标签]
	 * @return [type] [description]
	 */
	public function tagAdopt(){
		$ids = I('get.id', '', 'addslashes');
		$id = explode(',', $ids);
		$id = array_filter($id);

		$tag = D($this->_tag);
		$curl = A('SendRequest');
		foreach ($id as $k => $v) {
			try {
				$tagarr = explode(':', $v);
				$type = $tag->getType($tagarr[0]);
				switch ($type['is_active']) {
					case '1': //添加标签
						$data = array('type'=>8, 'desc'=>$type['tag']);
		      			$re = $curl->curlPost(C('REQUEST').'met/informamtion', $data);
		  				$arr=json_decode($re,true);

						if($arr['error']){
							throw new \Exception("未获取到数据！请检查");
						}else{
							$re = $tag->adopt($tagarr[0], $arr['id']);

							if(!$re){
								return 1;//
							}
						}

						break;
					case '2': //修改标签
						$data = array('type'=>7, 'id'=>$type['re_id'], 'desc'=>$type['tag']);
						$re = $curl->curlPost(C('REQUEST').'met/informamtion', $data);
		  				$arr=json_decode($re,true);

						if($arr['error']){
							throw new \Exception("未获取到数据！请检查帐号");
						}else{
							$re = $tag->adopt($tagarr[0], $arr['id'], array('modified'=>$type['modified'],'user'=>$type['user'],'tag'=>$type['tag']));

							if(!$re){
								return 1;//
							}
						}

						break;
					case '3':
						$data = array('type'=>6, 'id'=>$type['re_id']);
						$re = $curl->curlPost(C('REQUEST').'met/informamtion', $data);
		  				$arr=json_decode($re,true);

						if($arr['error']){
							throw new \Exception("未获取到数据！请检查帐号");
						}else{
							$re = $tag->delAdopt($arr['id']);

							if(!$re){
								return 1;//
							}
						}

						break;
					default:
						throw new \Exception("未获取到数据操作！");
						break;
				}
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':标签通过审核','fullaction' => __ACTION__,));
			} catch (\Exception $e) {
				$msg = $e->getMessage();
			}
		}

		return 0; //成功
	}


	/**
	 * [actionSub 操作]
	 * @param  [array] $info [操作信息]
	 * @return [type]       [description]
	 */
	protected function actionSub($info){

		if(is_array($info)){
			try {
				//实例化通讯类
				$crul = A('SendRequest');
				foreach ($info as $k => $v) {
					if ($v['type'] == '4') { //修改
						$subject = D($this->_subject);
						$subData = $subject->getInfo($v['id'],4);
						if($subData[$v['id']]['type'] == 2){ //修改视频
							$data = array(
								'type'=>3,
								'id' => $subData[$v['id']]['re_id'],
								'picUrl' => $subData[$v['id']]['icon'],
								'videoUrl' => $subData[$v['id']]['video'],
								'videoSize' => $subData[$v['id']]['size'],
								'videoTime' => $subData[$v['id']]['time'],
								'icon' => $subData[$v['id']]['icon'],
								'title' => $subData[$v['id']]['title'],
								'briefing' => $subData[$v['id']]['content'],
								'isTop' => $subData[$v['id']]['is_sub'],
								'time' => $subData[$v['id']]['created'],
								'tagId' => explode(',',  $subData[$v['id']]['t_id']),
							);
						}else if($subData[$v['id']]['type'] == 3){ //修改图文
							$data = htmlspecialchars_decode(htmlspecialchars_decode($subData[$v['id']]['content']));
							$data = strip_tags($data);
							$briefing = mb_strcut($data, 0, 60, 'utf-8').'...';

							$data = array(
								'type'=>3,
								'id' => $subData[$v['id']]['re_id'],
								'icon' => $subData[$v['id']]['icon'],
								'title' => $subData[$v['id']]['title'],
								'briefing' => $briefing,
								'isTop' => $subData[$v['id']]['is_sub'],
								'time' => $subData[$v['id']]['created'],
								'tagId' => explode(',',  $subData[$v['id']]['t_id']),
								'url' => $subData[$v['id']]['pageurl'],
							);
						}
					}else if($v['type'] == '5'){ //添加
						$subject = D($this->_subject);
						$subData = $subject->getInfo($v['id']);
						if($subData[$v['id']]['type'] == 2){ //添加视频
							$data = array(
							'type'=>4,
							'information'=>array(
									array(
										'type' => $subData[$v['id']]['type'],
										'picUrl' => $subData[$v['id']]['icon'],
										'videoUrl' => $subData[$v['id']]['video'],
										'videoSize' => $subData[$v['id']]['size'],
										'videoTime' => $subData[$v['id']]['time'],
										'icon' => $subData[$v['id']]['icon'],
										'title' => $subData[$v['id']]['title'],
										'briefing' => $subData[$v['id']]['content'],
										'isTop' => $subData[$v['id']]['is_sub'],
										'tagId' => explode(',',  $subData[$v['id']]['t_id']),
									),
								),
							);
						}else if($subData[$v['id']]['type'] == 3){ //添加图文
							$data = htmlspecialchars_decode(htmlspecialchars_decode($subData[$v['id']]['content']));
							$data = strip_tags($data);
							$briefing = mb_strcut($data, 0, 60).'...';
							
							$data = array(
								'type'=>4,
								'information'=>array(
									array(
										'type' => $subData[$v['id']]['type'],
										'url' => $subData[$v['id']]['pageurl'],
										'icon' => $subData[$v['id']]['icon'],
										'title' => $subData[$v['id']]['title'],
										'briefing' => $briefing,
										'isTop' => $subData[$v['id']]['is_sub'],
										'tagId' => explode(',',  $subData[$v['id']]['t_id']),
									),
								),
							);
						}
					}else if($v['type'] == '3'){
						$subject = D($this->_subject);
						$subData = $subject->getInfo($v['id']);
						$data = array('id'=>$subData[$v['id']]['re_id'], 'type'=>2);
					}

					$re = $crul->curlPost(C('REQUEST').'met/informamtion', $data);

					$arr=json_decode($re,true);
					if($arr['result'] || $arr){
						//更新本地数据
						$s_id = $v['id'];
						$sub = D($this->_subject);
						if($arr['ids']){
							$this->result = $sub->setStatus($v['id'], array('is_active'=>1, 're_id'=>$arr['ids'][0]));
						}else
						{
							if($v['type'] == 4)
							{
								$edit_data = $sub->getInfo($v['id'],$v['type']);
								foreach($edit_data as $k => $v)
								{
									$subject = array(
										'icon' => $edit_data[$k]['icon'],
										'title' => $edit_data[$k]['title'],
										'modified' => $edit_data[$k]['created'],
										'is_active' => 1,
										'user' => $edit_data[$k]['user'],
										'is_sub' => $edit_data[$k]['is_sub'],
										't_id' => $edit_data[$k]['t_id']
									);

									$subject_content = array(
										'type' => $edit_data[$k]['type'],
										'video' => $edit_data[$k]['video'],
										'picUrl' => $edit_data[$k]['picurl'],
										'pageUrl' => $edit_data[$k]['pageurl'],
										'u_info' => $edit_data[$k]['u_info'],
										'content' => $edit_data[$k]['content'],
										'description' => $edit_data[$k]['description']
									);

									$this->result = $sub->setStatus($s_id,'',4,$subject,$subject_content);
								}
								//return 1;
							}elseif($v['type'] == 3)
							{
								if($arr['id'])
								{
									$this->result = $sub->setStatus($v['id'],$arr['id'],3);
								}
							}
						}
					}

					if($arr['error']){
						return 1; //失败
					}
				}
				/**
				 * 写入日志
				 */
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => '专题通过审核操作','fullaction' => __ACTION__,));
				return 0;//成功
			} catch (\Exception $e) {
				$msg = $e->getMessge();
				/**
				 * 写入日志
				 */
				$log = A('Admin/LogEvent');
				$log->writeLog(array('action' => ACTION_NAME,'info' => '专题通过审核操作失败','fullaction' => __ACTION__,));

				return 1; //失败
			}
		}
	}

}

?>