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
class TeamModel extends Model
{
	private $_team_user = 'team_user';

	/**
	 * [addTeam 添加战队]
	 * @param [array] $data [战队信息]
	 * @param [array] $user [成员信息]
	 */
	public function addTeam($data){
		$team = array(
			'title' => $data['name'],
			'logo' => $data['icon'],
			'declare' => $data['sign'],
			'brief' => $data['desc'],
			'is_ck' => $data['bool'],
			'ck_num' => ($data['bool'] == 1?create_unique_id():0),
			'created' => time(),
			);
		$id = $this->data($team)->add();
		
		if($id > 0){
			$user = array();
			foreach ($data['members'] as $k => $v) {
				$user[] = array(
					't_id' => $id,
					'username' => $v['name'],
					'gender' => $v['sex'],
					'age' => $v['age'],
					'skill' => $v['profession'],
					'prize' => $v['desc'],
					'position' => $v['position'],
					);
			}

			$re = D($this->_team_user)->addAll($user);
		}
		return $re;
	}

	/**
	 * [editTeam 修改队伍]
	 * @param  [int] $ids  [队伍id]
	 * @param  [array] $data [修改]
	 * @return [type]       [description]
	 */
	public function editTeam($ids, $data){
		$id = $this->where('t_id='.$ids)->save($data);

		return $id;
	}

	/**
	 * [editTeamUser 修改队伍成员信息]
	 * @param  [int] $id [队伍id]
	 * @param  [array] $user [用户信息]
	 * @return [type]       [description]
	 */
	public function editTeamUser($id, $user){
		$result = D($this->_team_user)->where('t_id='.$id)->save($user);

		return $result;
	}

	/**
	 * [getAllTeam 战队列表]
	 * @return [array] [description]
	 */
	public function getAllTeam(){
		$list = $this->order('t_id DESC')->getField('title, logo, created, t_id');
		return $list;
	}

	/**
	 * [getTeam 获取战队信息]
	 * @param  [int] $id [战队id]
	 * @return [array]     [description]
	 */
	public function getTeam($id){
		$field = "t_id,title,logo,declare,brief,is_ck,ck_num";
		$team = $this->where('t_id='.$id)->getField($field);
		$user = D($this->_team_user);
		$data = $user->where('t_id='.$id)->select();
	
		$team[$id]['user'] = $data;

		return $team;
	}

	/**
	 * [delTeam 删除战队]
	 * @param  [int] $id [战队id]
	 * @return [type]     [description]
	 */
	public function delTeam($id){
		$re = $this->where('t_id='.$id)->delete();
		if($re){
			$user = D($this->_team_user);
			$re = $user->where('t_id='.$id)->delete();
			if($re){
				return 0; //成功
			}

			return 1; //删除
		}
	}

	/**
	 * [getTeamById 根据id获取数据]
	 * @param  [int] $id [team id]
	 * @return [type]     [description]
	 */
	public function getTeamById($id){
		$data = $this->field('t_id, title,logo')->where('t_id='.$id)->find();
		
		return $data;
	}
}
?>