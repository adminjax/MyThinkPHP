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
class TicketModel extends Model
{
	private $_tmp_ticket = 'tmp_ticket';
	/**
	 * [saveTicket 添加票务]
	 * @param  [array] $data [添加的数据]
	 * @return [type]       [description]
	 */
	public function saveTicket($data){
		if($data['reid'] > 0){
			$id = $this->where('reid='.$data['reid'])->getField('t_id');
			$cfg = array(
				'creater' => session('user.username'),
				'modified' => time(),
				'is_active' => 2,
				't_id' => $id,
				);
			$data = array_merge($data, $cfg);
			$t_id = D($this->_tmp_ticket)->data($data)->add();
			if($t_id > 0){
				$re = $this->where('reid='.$data['reid'])->save(array('is_active'=>2));
			}
			/**
             * 写入日志
             */
            $log = A('Admin/LogEvent');
            $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':修改票务','fullaction' => __ACTION__,));
		}else{
			$cfg = array(
				'creater' => session('user.username'),
				'created' => time(),
				'is_active' => 1,
				);
			$data = array_merge($data, $cfg);
			$re = $this->data($data)->add();

            $log = A('Admin/LogEvent');
            $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':添加票务','fullaction' => __ACTION__,));
		}
		
		return $re;
	}

	/**
	 * [getTicket 获取票据]
	 * @return [type] [description]
	 */
	public function getTicket($p,$num){
		$datas = array();
		$re = ($p*$num)-$num;
		$where['is_active'] = array('in','1,3');
		$data = $this->where($where)->order('t_id DESC')->select();
		$edit = D($this->_tmp_ticket)->limit($re.','.$num)->order('t_id DESC')->select();
		$count = D($this->_tmp_ticket)->count();
		$counts = $this->where($where)->count();
		$datas['count'] = (int)$count + (int)$counts;
		$datas['data'] = array_merge($data, $edit);
		return $datas;
	}

	/**
	 * [delTicket 删除票务]
	 * @param  [int] $id [票务id]
	 * @return [type]     [description]
	 */
	public function delTicket($id){
		$data = array('is_active'=>3);
		$re = $this->where('reid='.$id)->save($data);
		return $re;
	}

	/**
	 * [getTicketById 根据id获取票务]
	 * @param  [int] $id [票务id]
	 * @return [array]     [票据信息]
	 */
	public function getTicketById($id){
		$data = $this->where('t_id='.$id)->find();

		return $data;
	}

	/**
	 * [getTmpTicketById 通过操作后获取修改数据]
	 * @param  [int] $id [票务id]
	 * @return [type]     [description]
	 */
	public function getTmpTicketById($id){
		$data = D($this->_tmp_ticket)->where('t_id='.$id)->find();

		return $data;
	}

	/**
	 * [delTmpTicket 通过后删除临时数据]
	 * @param  [int] $id [票务返回id]
	 * @return [type]     [description]
	 */
	public function delTmpTicket($id){
		$data = D($this->_tmp_ticket)->where('reid='.$id)->delete();
		
		return $data;
	}

	/**
	 * [saveId 存储返回id]
	 * @param  [int] $id   [票务id]
	 * @param  [int] $reid [返回id]
	 * @return [type]       [description]
	 */
	public function saveId($id, $reid){
		$re = $this->where('t_id='.$id)->save(array('reid'=>$reid, 'is_active'=>5));
		return $re;
	}

	/**
	 * [delTicketById description]
	 * @param [int] $id [票务id]
	 * @return [type] [description]
	 */
	public function delTicketById($id){
		$data = $this->where('t_id='.$id)->delete();
		return $data;
	}

	/**
	 * [delTmpTicketById 拒绝后删除临时数据]
	 * @param  [int] $id [票务id]
	 * @return [type]     [description]
	 */
	public function delTmpTicketById($id){
		$data = D($this->_tmp_ticket)->where('t_id='.$id)->delete();
		if($data > 0){
			$data = $this->where('t_id='.$id)->save(array('is_active'=>5));
		}
		return $data;
	}
	
	/**
	 * [nodel 拒绝操作后回复状态]
	 * @param  [int] $id [票务id]
	 * @return [type]     [description]
	 */
	public function nodel($id){
		$data = $this->where('t_id='.$id)->save(array('is_active'=>5));

		return $data;
	}

	/**
	 * [getTicketData 获取临时数据]
	 * @param  [int] $id [票务id]
	 * @return [type]     [description]
	 */
	public function getTicketData($id){
		$data = $this->where('t_id='.$id)->find();
		return $data;
	}

	/**
	 * [getTmpTicketData 获取临时数据]
	 * @param  [int] $id [票务id]
	 * @return [type]     [description]
	 */
	public function getTmpTicketData($id){
		$data = D($this->_tmp_ticket)->where('t_id='.$id)->find();
		return $data;
	}

	/**
	 * [getTicketById 根据返回id获取票务]
	 * @param  [int] $id [票务id]
	 * @return [array]     [票据信息]
	 */
	public function getData($reid){
		$data = $this->where('reid='.$reid)->find();

		return $data;
	}

	public function updateTicket($data){
		$update = array(
            'img' => $data['style'],
            'title' => $data['name'],
            'startTime' => $data['startTime'], 
            'exp_date' => $data['endTime'],
            'price' => $data['rmb'],
            'content' => $data['desc'],
            'count' => $data['count'],
            'score' => $data['score'],
            'is_active' => 5,
            );

		$this->where('reid='.$data['sid'])->save($update);
	}

	public function delelteTicket($reid){
		$this->where('reid='.$reid)->delete();
	}
}
?>