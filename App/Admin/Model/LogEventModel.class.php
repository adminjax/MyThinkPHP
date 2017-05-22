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
class LogEventModel extends Model
{
	
	/**
	 * [write 写入数据]
	 * @param  Array  $data [要记录的数据]
	 * @return [type]       [description]
	 */
	public function write(Array $data){
		$result = $this->add($data);
	}

	public function clearLog(){
		//$this->where()->delete();
		$this->delete();
	}
}
?>