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
 * 文字战报
 */
class ReportModel extends Model
{
	/**
	 * [getAllReport 获取文字战报]
	 * @return [type] [description]
	 */
	public function getAllReport(){
		$data = $this->field('r_id, title, event, source, created, re_id')->select();

		return $data;
	}

	/**
	 * [addReport 添加文字战报]
	 * @param [type] $data [description]
	 */
	public function addReport($data){
		$result = $this->data($data)->add();

		return $result;
	}

	/**
	 * [saveReId 保存存回调id]
	 * @param  [int] $id    [本地id]
	 * @param  [int] $re_id [回调id]
	 * @return [type]        [description]
	 */
	public function saveReId($id, $re_id){
		$result = $this->where('r_id='.$id)->save(array('re_id'=>$re_id));

		return $result;
	}

	/**
	 * [delReport 删除文字战报]
	 * @param  [int] $id [本地id]
	 * @return [type]     [description]
	 */
	public function delReport($id){
		$result = $this->where('id='.$id)->delete();

		return $result;
	}

	/**
	 * [editReport 编辑文字战报]
	 * @param  [int] $id   [文字战报id]
	 * @param  [array] $data [数据]
	 * @return [type]       [description]
	 */
	public function editReport($id, $data){
		$result = $this->where('id='.$id)->save($data);

		return $result;
	}
}
?>