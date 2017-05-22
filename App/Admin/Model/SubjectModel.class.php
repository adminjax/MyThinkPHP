<?php
/**
 * MET纪元后台音乐管理工具
 *
 * @Author: jax
 *
 * @category    App
 * @package     App_Admin
 */
namespace Admin\Model;
use Think\Model;

/**
 * 日志管理
 */
class SubjectModel extends Model
{
	private $_subject_content = 'subject_content';

	/**
	 * [addSubject 添加专题]
	 * @param  [array] $data    [专题信息]
	 * @param  [array] $content [专题内容]
	 * @return [int]          [专题ID]
	 */
	public function addSubject($data, $content){
		if(!$this->create($data)){
			return $this->getError();
		}else{
			$id = $this->data($data)->add();
			if($id > 0){
				$content['s_id'] = $id;
				$content['pageUrl'] = C('WEB_SITE').'Home/index/subject/new/'.$id;
				//$content['pageUrl'] = 'http://192.168.120.238/met/Home/index/subject/new/'.$id;
				$id = D($this->_subject_content)->data($content)->add();
			}else{
				return -1;
			}

			return $id;
		}
	}


	/**
	 * [saveSubject 修改专题]
	 * @param  [array] $data    [专题信息]
	 * @param  [array] $content [专题内容]
	 * @return [int]          [专题ID]
	 */
	public function saveSubject($ids,$data, $content){
		if(!$this->create($data)){

			return $this->getError();
		}else{
			$id = $this->where(array('re_id'=>$ids))->save($data);
			if(false !== $id || 0 !== $id){
				$s_id = $this->where('re_id="'.$ids.'"')->getField('s_id');
				if($s_id){
					$content['s_id'] = $s_id;
					$where = array('s_id'=>$s_id);
					$content['pageUrl'] = C('WEB_SITE').'Home/index/subject/new/'.$s_id;
					//$content['pageUrl'] = 'http://192.168.120.238/met/Home/index/subject/new/'.$s_id;
					$subject_id = M('tmp_subject')->where($where)->getField('s_id');
					if(!$subject_id){
						$res = M('tmp_subject')->add($content);
					}else{ 
						$res = M('tmp_subject')->where($where)->save($content);
					}
				}
			}else{
				return -1;
			}

			return $res;
		}
	}

	/**
	 * [delSub 删除专题]
	 * @param  [array] $id [专题id]
	 * @return [type]     [description]
	 */
	public function delSub($id){
		foreach ($id as $key => $value) {
			$result = $this->field('s_id')->where("re_id='$value'")->find();
			if(!$result['s_id']){
				$this->useBackup($value);
			}

			$re = $this->where("re_id='$value'")->save(array('is_active'=>3));
			if($re <= 0){
				
			}
		}

		return 0;
	}

	/**
	 * 
	 */
	protected function useBackup($reid){
		$curl = A('SendRequest');
		$data = $curl->curlPost(C('REQUEST').'met/informamtion ', array('type'=>9,'id'=>$reid));
		$arr=json_decode($data,true);

		$subject = array(
			're_id' => $arr['id'],
			'is_sub' => $arr['isTop'],
			't_id' => implode(',', $arr['tagIds']),
			'title' => $arr['title'],
			'icon' => $arr['icon'],
			'created' => $arr['time'],
			'is_active' => 1,
			'user' => session('user.username'),
			);
		$result = $this->add($subject);

		if($arr['type'] == 2){
			$content = array(
				's_id' => $result,
				'type' => $arr['type'],
				'content' => $arr['briefing'],
				'video' => $arr['videoUrl'],
				'size' => $arr['videoSize'],
				'time' => $arr['videoTime'],
				'picUrl' => $arr['picUrl']?$arr['picUrl']:'no data',
				);
		}else if($arr['type'] == 3){
			$content = array(
				's_id' => $result,
				'type' => $arr['type'],
				'content' => $arr['briefing'],
				'pageUrl' => $arr['url']?$arr['url']:'no data',
				);
		}

		
		$result = M('subject_content')->add($content);

		if(!$result){
			return -1;
		}
	}

	/**
	 * [getSubject 获取专题信息]
	 * @param  [int] $id [专题id]
	 * @return [array]     [专题信息]
	 */
	public function getSubject($id){
		$re = $this->table('subject S')
			 ->join('subject_content SC on S.s_id = SC.s_id')
			 ->field('S.*,SC.*')
			 ->where('S.re_id="'.$id.'"')
			 ->find();

		if(!$re){
			$this->useBackup($id);
			$re = $this->table('subject S')
				 ->join('subject_content SC on S.s_id = SC.s_id')
				 ->field('S.*,SC.*')
				 ->where('S.re_id="'.$id.'"')
				 ->find();
		}
		return $re;
	}

	/**
	 * [getAllInfo 获取专题总条数]
	 * @return [int] [条数]
	 */
	public function getInfoCount(){
		$where['is_active'] = array('in','3,4,5');
		return $this->where($where)->count();
	}

	/**
	 * [getPage 获取专题数据]
	 * @param  [int] $first [从第几条数据]
	 * @param  [int] $last  [到第几条数据]
	 * @return [array]      [专题数据]
	 */
	public function getPage($first, $last){
		$data = $this->table('subject')
			 		 ->join('LEFT JOIN subject_content on subject.s_id = subject_content.s_id')
			 		 ->where('subject.is_active != 2 and subject.is_active != 1')
			 		 ->order('created asc')
			 		 ->limit($first, $last)
			 		 ->getField('subject.s_id, created, type, user, is_active, description');
		$tmp_subject = M('tmp_subject');
		foreach($data as $k=>$v)
		{
			if($data[$k]['is_active'] == 4)
			{
				$tmp_subject_con = $tmp_subject->where(array('s_id'=>$data[$k]['s_id']))->field('s_id, created, type, user, is_active, description')->find();
				$data[$k] = $tmp_subject_con;
				continue;
			}
		}
		return $data;
	}

	/**
	 * [getInfo 获取指定专题]
	 * @param  [int] $id [专题id]
	 * @return [array]     [专题信息]
	 */
	public function getInfo($id,$active=0){
		if($active != 4)
		{
			//需要查询字段
			$field = 'subject.s_id, icon, title, created, modified, user, is_active, is_sub, re_id, t_id, type, video, size, time, picUrl, pageUrl, u_info, content, description';

			$data = $this->table('subject')
				->join('LEFT JOIN subject_content on subject.s_id = subject_content.s_id')
				->where('subject.s_id='.$id)
				->getField($field);
		}
		else
		{
			//需要查询字段
			$field = 's_id, icon, title, created, user, is_active, is_sub, re_id, t_id, type,
		video, size, time, picUrl, pageUrl, u_info, content, description';

			$data = M('tmp_subject')
				->where(array('tmp_subject.s_id'=>$id))
				->getField($field);
		}

		return $data;
	}

	/**
	 * [delSubject 拒绝]
	 * @param  [int] $id [拒绝专题id]
	 * @return [int]     []
	 */
	public function refuseSubject($id){
		if($id > 0){
			$re = $this->where('s_id='.$id)->save(array('is_active'=>1));

			return 0; //成功
		}
	}

	/**
	 * [setStatus 当改变远程状态时更新本地状态]
	 * @param [int] $id   [专题id]
	 * @param [array] $data [更新的数据]
	 */
	public function setStatus($id, $data=null ,$type=0,$subject_data=null,$subject_content_data=null){
		$re = '';
		$subject = M('subject');
		$subject_content = M('subject_content');
		$tmp_subject = M('tmp_subject');
		if(empty($data) && $type == 4)
		{
			$where['s_id'] = $id;
			$res = $subject->where($where)->save($subject_data);

			$res_t = $subject_content->where($where)->save($subject_content_data);
			if($res && $res_t)
			{
				$tmp_subject->where($where)->delete();
				$re = 1;
			}
		}elseif($type == 3 && !empty($data))
		{
			$re = $this->where(array('re_id'=>$data))->delete();//这个$data是删除审核通过后返回id
			$subject_content->where(array('s_id'=>$id))->delete();
		}
		else
		{
			$re = $this->where('s_id='.$id)->save($data);
		}

		return $re;
	}
}
?>