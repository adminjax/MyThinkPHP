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
 * 用户管理
 */
class UserController extends CommonController
{
    private $num = 50;//每页显示20条数据
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
     * [userList 用户列表入口]
     * @return [type] [description]
     */
	public function userList(){
        $page = I('get.p',1,'int');
        $url = C('CURL_');
        $data = array(
            'type' => 1,
            'page' => $page,
            'num' => $this->num
        );
        $curl = A('SendRequest');
        $user_res = $curl->curlPost($url.'met/user ',$data);
        $user_res = json_decode($user_res,true);
        if(!empty($user_res['array'])){
            //用户数量
            $user_num = $user_res['total'];

            $page = $this->pageTools($user_num,$page,$this->num);
            $info = $this->button('details','详情','8_1_1');
            $this->assign('user_info',$user_res['array']);
            $this->assign('info',$info);
            $this->assign('page', $page);
        }
		$this->display('userList');
	}

    /*
     * [editUserInfo 保存客户账号状态]
     */
    public function editUserInfo()
    {
        $status = I('post.status');
        $account = I('post.account','addslashes');
        $endTime = I('post.endTime','','addslashes');
        if(empty($account))
        {
            echo 'full';
            return false;
        }
        if($status && empty($endTime)){
            $time = -1;
            $desc = '客户账号恢复正常';
        }else{
            $time = strtotime($endTime);
            $desc = '客户账号封号至'.$endTime;
        }
        if($time){
            $data = array(
                'type' => 2,
                'time' => $time,
                'account' => $account
            );
            $curl = A('SendRequest');
            $url = C('CURL_');
            $res = $curl->curlPost($url.'met/user ',$data);
            $res = json_decode($res);
            /**
             * 写入日志
             */
            $log = A('Admin/LogEvent');
            $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':'.$desc,'fullaction' => __ACTION__,));
            if($res->result){
                echo 1;
            }else{
                echo 0;
            }
            return false;
        }
        echo 0;
    }

    /*
     * [userDetailed 账号详情]
     */
    public function userDetailed(){
        $account = I('post.account');
        $real_name = I('post.real_name');
        $card = I('post.card');
        $url = C('CURL_');
        $curl = A('SendRequest');
        $data =array(
            'type' => 3,
            'account' => $account
        );
        $res = $curl->curlPost($url.'met/user ',$data);
        $res = json_decode($res,true);
        $user_info = array($res);

        if($user_info[0]['time'] <= time()){
            $user_info[0]['time'] = -1;
        }
        $user_info[0]['realName'] = $real_name;
        $user_info[0]['metCard'] = $card;
        $this->assign('user_info',$user_info);
        /**
         * 写入日志
         */
        $log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':获取客户详情','fullaction' => __ACTION__,));

        $nomal = $this->button('nomal','正常','8_1_2');
        $stop = $this->button('User_info','封号','8_1_3');
        $reported = $this->button('reported','被举报','8_1_4');
        $save = $this->button('save','保存','8_1_5');
        $this->assign('nomal',$nomal);
        $this->assign('stop',$stop);
        $this->assign('reported',$reported);
        $this->assign('save',$save);
        $this->display('user_info');
    }

    /*
     * [changeStatus 更改账户授权状态]
     */
    public function changeStatus(){
        $account = I('post.account');
        $sta = I('post.sta');
        $url = C('CURL_');
        if($account){
            $data = array(
                'type' => (int)5,
                'is_official' => (bool)$sta,
                'account' => (string)$account
            );
            $curl = A('SendRequest');
            $res = $curl->curlPost($url.'met/user',$data);
            if($sta){
                $desc = '改为是官方授权账号';
            }else{
                $desc = '改为不是官方授权账号';
            }
            /**
             * 写入日志
             */
            $log = A('Admin/LogEvent');
            $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':'.$desc,'fullaction' => __ACTION__,));
            $res = json_decode($res);
            echo $res->result;
        }
    }

    /*
     * [searchUser 搜索用户]
     */
    public function searchUser(){
        $con = I('post.search');
        $p = I('get.p','','int') ? I('get.p','','int') : 1;
        if(empty($con)){
            $this->redirect('User/userList');
        }
        $url = C('CURL_');
        $data = array(
            'type' => 4,
            'txt' => $con
        );
        $curl = A('SendRequest');
        $res = $curl->curlPost($url.'met/user',$data);
        $goods_info = json_decode($res,true);
        if($goods_info[0]['erro'] == 'not found player'){
            $goods_info = '';
        }
        $this->assign('user_info',$goods_info['array']);
        $this->assign('search_info',$con);
        $page = $this->pageTools($goods_info['total'],$p,$this->num);
        $info = $this->button('details','详情','8_1_1');
        $this->assign('info',$info);
        $this->assign('page', $page);

        $this->display('userList');
    }
}

?>