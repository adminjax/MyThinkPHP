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
class TagModel extends Model
{
	private $_tmp_tag = 'tmp_tag';

	/**
	 * [addTag 添加标签到本地]
	 * @param [string] $str [标签名]
	 */
	public function addTag($str){
		$data = array(
			'tag' => $str,
			'user' => session('user.username'),
			'created' => time(),
			'is_active' => 1,
			);
		$re = $this->data($data)->add();
		return $re;
	}

	/**
	 * [editTag 修改标签]
	 * @param  [string] $re_id [回调id]
	 * @param  [string] $str   [标签]
	 * @return [type]        [description]
	 */
	public function editTag($re_id, $str){
		$id = $this->where('re_id='.$re_id)->getField('t_id');
		if(!$id){
			$id = $this->useBackup($re_id);
		}

		$data = array(
			't_id' => $id,
			're_id' => $re_id,
			'tag' => $str,
			'user' => session('user.username'),
			'modified' => time(),
			'is_active' => 2,
			);

		$re = D($this->_tmp_tag)->data($data)->add();
		if($re>0){
			$data = array(
				'is_active' => 2,
				);
			$re = $this->where('re_id="'.$re_id.'"')->save($data);	
			if($re){
				return 0; //修改成功
			}
		}
	}

	/**
	 * [delTag 删除标签]
	 * @param  [string] $re_id [回调id]
	 * @return [type]        [description]
	 */
	public function delTag($re_id){
		$flag = $this->where('re_id='.$re_id)->find();
		if(!$flag){
			$this->useBackup($re_id);
		}

		$data = array(
			'is_active' => 3
			);
		$re = $this->where('re_id='.$re_id)->save($data);

		if($re){
			return 0;//成功
		}
	}

	protected function useBackup($id){
		$data = A('SendRequest');
		$data = $data->curlPost(C('REQUEST').'met/informamtion ', array('type'=>10, 'id'=>$id));
		$tag=json_decode($data,true);

		$tagContent = array(
				're_id' => $tag['id'],
				'tag' => $tag['desc'],
				'user' => session('user.username'),
				'created' => time(),
				'is_active' => 5,
			);
		$id = $this->add($tagContent);
		return $id;
	}

	/**
	 * [getTagCount 获取待审核总条数]
	 * @return [type] [description]
	 */
	public function getTagCount(){
		$count = $this->where('is_active != 4  and is_active != 5')->count();

		return $count;
	}

	/**
	 * [refuse 拒绝标签]
	 * @param  [int] $v [标签ID]
	 * @return [type]    [description]
	 */
	public function refuse($v){
		$type = $this->where('t_id='.$v)->getField('is_active');
		switch ($type) {
			case '1':
				$re = $this->where('t_id='.$v)->delete();
				break;
			case '2':
				$data = array(
					'is_active' => 5,
				);
				$re = $this->where('t_id='.$v)->save($data);
				D($this->_tmp_tag)->where('t_id='.$v)->delete();
				break;
			case '3':
				$data = array(
					'is_active' => 5,
				);
				$re = $this->where('t_id='.$v)->save($data);
				break;
		}
		
		
		return $re;
	}

	/**
	 * [adopt 通过标签]
	 * @param  [type] $v [description]
	 * @return [type]    [description]
	 */
	public function adopt($v, $re_id, $info=null){
		$data = array(
			'is_active' => 5,
			're_id' => $re_id
			);
		if(!empty($info)){
			$data = array_merge($data, $info);
		}
		
		$re = $this->where('t_id='.$v)->save($data);
		D($this->_tmp_tag)->where('re_id='.$re_id)->delete();

		return $re;
	}

	/**
	 * [getType 获取标签状态]
	 * @param  [int] $id [标签id]
	 * @return [type]     [description]
	 */
	public function getType($id){
		$re = $this->field('is_active, tag, re_id')->where('t_id='.$id)->find();
		$res = D($this->_tmp_tag)->field('is_active, tag, re_id,modified,user')->where('t_id='.$id)->find();
		if(!empty($res)){
			$re = array_merge($re, $res);
		}
		return $re;
	}

	/**
	 * [delAdopt 删除标签]
	 * @param  [int] $re_id [回执id]
	 * @return [type]        [description]
	 */
	public function delAdopt($re_id){
		$re = $this->where('re_id='.$re_id)->delete();

		return $re;
	}

	/**
	 * [getAllTag 获取需要操作的标签]
	 * @return [type] [description]
	 */
	public function getAllTag($first,$last){
		$where['is_active'] = array('in','1,3');
		$alist = $this->where($where)->order('created DESC')->limit($first.','.$last)->select();
		$elist = D($this->_tmp_tag)->select();
		$arr = array_merge($alist,$elist);

		return $arr;
	}

}
?>