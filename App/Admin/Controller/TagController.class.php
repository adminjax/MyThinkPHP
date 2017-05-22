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
class TagController extends CommonController
{
	private $_tag = 'tag';

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
   	 * [manage 标签管理入口]
   	 * @return [type] [description]
   	 */
	public function manage(){
		$tag = $this->getTagList();

		//获取本地数据
		$tag_info  = M('tag');
		$where['is_active'] = array('in','2,3');
		$res = $tag_info->where($where)->field('re_id,is_active')->select();
		if($res){
			foreach($res as $k => $v){
				foreach($tag as $k1 => $v1){
					if($v['re_id'] == $v1['id']){
						if($v['is_active'] == 2){
							$tag[$k1]['au'] = 2;
							break 1;
						}elseif($v['is_active'] == 3){
							$tag[$k1]['au'] = 3;
							break 1;
						}
					}
				}
			}
		}
		//添加button
		$add = $this->button('add', '添加标签', '1_3_3');
		$delet = $this->button('delet', '删除标签', '1_3_2');
		$edit = $this->button('edit', '修改标签', '1_3_1');

		$this->assign('add', $add);
		$this->assign('delet', $delet);
		$this->assign('edit', $edit);
		$this->assign('tag', $tag);
		$this->display('manage');
	}

	/**
	 * [getTagList 获取远程标签库]
	 * @return [type] [description]
	 */
	protected function getTagList(){
		//获取远程接口数据
		$data = A('SendRequest');
		$data = $data->curlPost(C('REQUEST').'met/informamtion ', array('type'=>5));
		$tag=json_decode($data,true);

		foreach ($tag as $key => $value) {
			$tag = $value;
		}

		return $tag;
	}

	/**
	 * [addTag 添加标签]
	 * return [type] [description]
	 */
	public function addTag(){
		$str = I('get.tag', '', 'addslashes');

		if(!empty($str)){
			$tag = D($this->_tag);
			$re = $tag->addTag($str);

			/*
			 * 写入日志
			 */
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':添加标签','fullaction' => __ACTION__,));

			if($re > 0){
				return $re;
			}
		}else{
			return -1;
		}
	}

	/**
	 * [editTag 编辑标签]
	 * @return [type] [description]
	 */
	public function editTag(){
		$re_id = I('get.id', '', 'addslashes');
		$str = I('get.tag', '', 'addslashes');

		if(!empty($re_id) && !empty($str)){
			$tag = D($this->_tag);	
			$re = $tag->editTag($re_id, $str);

			/*
			 * 写入日志
			 */
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':修改标签','fullaction' => __ACTION__,));
			return 0;
		}else{
			return -1;
		}
	}

	/**
	 * [delTag 删除标签]
	 * @return [type] [description]
	 */
	public function delTag(){
		$re_id = I('get.id', '', 'addslashes');
		$ids = explode(',', $re_id);
		$ids = array_filter($ids);

		if(!empty($re_id)){
			$tag = D($this->_tag);
			foreach ($ids as $k => $v) {
				$re = $tag->delTag($v);

				if($re != 0){
					return 1;//更新失败
					break;
				}
			}
			$log = A('Admin/LogEvent');
			$log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':删除标签','fullaction' => __ACTION__,));
		}else{
			return -1;
		}
	}
}

?>