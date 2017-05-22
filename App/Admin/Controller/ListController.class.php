<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/16
 * Time: 13:45
 */

namespace Admin\Controller;
use Think\Exception;

/*
 * 榜单维护类
 */
class ListController extends CommonController
{
    private $num = 20;
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
     * [uploadify 上传图片]
     * @return [type] [description]
     */
    public function uploadImage(){
        if (!empty($_FILES)) {
            $file['Filedata'] = $_FILES['file'];
            $image = A('Admin/Image');
            $image->upLoadImage($file);
            $path = $image->getPath();
            $this->log('上传图片');
            echo json_encode(array("error"=>"0","pic"=>$path,"name"=>'0000000'));
        }
    }

    /**
     * [uploadVideo 上传视频]
     * @return [type] [description]
     */
    public function uploadVideo(){
        if (!empty($_FILES)) {
            $file['Filedata'] = $_FILES['file'];
            $image = A('Admin/Image');
            $image->bigFile($file);
            $path = $image->getPath();
            $this->log('上传视频');
            echo json_encode(array("error"=>"0","pic"=>$path,"name"=>'0000000'));
        }
    }

    /*
     * [getMakerTeamList 创客队伍列表]
     */
    public function getMakerTeamList(){
        $url = C('CURL_');
        $data = array(
            'type' => 7
        );
        $curl = A('SendRequest');
        $res = $curl->curlPost($url.'met/team',$data);
        $res = json_decode($res,true);
        $this->log('获取创客队伍列表');
        return $res['array'];
    }

    /*
     * [getListType 获取榜单类型]
     */
    public function getListType(){
        $url = C('CURL_');
        $data = array(
            'type' => 1
        );
        $curl = A('SendRequest');
        $res = $curl->curlPost($url.'met/topTen',$data);
        $res = json_decode($res,true);
        $this->log('获取榜单类型');
        return $res['array'];
    }

    /*
     * [getInstrType 获取报名类型]
     */
    public function getInstrType(){
        $url = C('CURL_');
        $data = array(
            'type' => 7
        );
        $curl = A('SendRequest');
        $res = $curl->curlPost($url.'met/topTen',$data);
        $res = json_decode($res,true);
        $this->log('获取报名类型');
        return $res['array'];
    }

    /**
     * 榜单编辑入口
     */
    public function editList()
    {
        session('msg',null);
        session('m_type',null);
        $onlineList = $this->getListType();
        $btn_list = $this->button('btn_list','保存编辑至审核','10_1_1');
        $btn_del_num = $this->button('btn_del_num','删除榜单','10_1_2');
        $edit_list = $this->button('edit_list','修改期数','10_1_3');
        $add_list = $this->button('add_list','添加榜单','10_1_4');
        $btn_addRank = $this->button('btn_addRank','增加排名','10_1_5');
        $btn_delRank = $this->button('btn_delRank','删除','10_1_6');
        $makerTeamList = $this->getMakerTeamList();
        $this->assign('makerTeamList',$makerTeamList);
        $this->assign('onlineList',$onlineList);
        $this->assign('btn_list',$btn_list);
        $this->assign('btn_del_num',$btn_del_num);
        $this->assign('edit_list',$edit_list);
        $this->assign('add_list',$add_list);
        $this->assign('btn_addRank',$btn_addRank);
        $this->assign('btn_delRank',$btn_delRank);
        $this->display('editList');
    }

    /*
     * 榜单类型设置入口
     */
    public function listType()
    {
        session('msg',null);
        session('m_type',null);
        $tmp_list = M('tmp_list');
        $where['is_active'] = array('in','9,11');
        $sta = $tmp_list->where($where)->getField('show_id,type,is_active');
        $onlineList = $this->getInstrType();
        foreach($onlineList as $k => $v){
            if($onlineList[$k]['id'] == $sta[$onlineList[$k]['id']]['show_id']){
                $onlineList[$k]['sta'] = $sta[$onlineList[$k]['id']]['is_active'];
            }
        }
        $this->assign('onlineList',$onlineList);

        $add_type = $this->button('add_type','添加','10_2_1');
        $del_type = $this->button('del_type','删除','10_2_2');
        $btn_listAudit = $this->button('btn_listAudit','提交榜单至审核','10_2_3');
        $this->assign('add_type',$add_type);
        $this->assign('del_type',$del_type);
        $this->assign('btn_listAudit',$btn_listAudit);
        $this->display('listType');
    }

    /*
     * 榜单展示管理入口
     */
    public function listShow()
    {
        $onlineList = $this->getListType();
        $this->assign('onlineList',$onlineList);
        $save = $this->button('save','保存','10_3_1');
        $this->assign('save',$save);
        $this->display('listShow');
    }

    /*
     * 榜单审核入口
     */
    public function listAudit()
    {
        session('msg',null);
        session('m_type',null);
        $p = I('get.p',1,'int');
        $re = ($p * $this->num) - $this->num;
        //榜单审核
        $tmp_list = M('tmp_list');
        $where['is_active'] = array('in','4,5,6,14,15');
        $list = $tmp_list->where($where)->limit($re.','.$this->num)->field('id,show_id,list_id,is_active,create,stage,type')->select();
        if($list){
            $num = $tmp_list->where($where)->count();
            $list_page = $this->pageTools($num,$p,$this->num);
            $this->assign('list_page',$list_page);
            $this->assign('list',$list);
        }
        //榜单类型审核
        $where['is_active'] = array('in','8,9,11');
        $list_type = $tmp_list->where($where)->field('id,is_active,create,type,img_url,index,list_desc,list_rule')->select();
        if($list_type){
            $num = $tmp_list->where($where)->count();
            $list_t_page = $this->pageTools($num,$p,$this->num);
            $this->assign('list_t_page',$list_t_page);
            $this->assign('list_type',$list_type);
        }
        //榜单顺序审核
        $tmp_list = M('tmp_list_sort');
        $list_sort = $tmp_list->where(array('is_active'=>0))->field('id,create')->select();
        if($list_sort){
            $num = $tmp_list->where(array('is_active'=>0))->count();
            $list_s_page = $this->pageTools($num,$p,$this->num);
            $this->assign('list_s_page',$list_s_page);
            $this->assign('list_sort',$list_sort);
        }
        $reject_l = $this->button('reject_l','拒绝','10_4_1');
        $adopt_l = $this->button('adopt_l','通过','10_4_2');

        $reject_t = $this->button('reject_t','拒绝','10_4_3');
        $adopt_t = $this->button('adopt_t','通过','10_4_4');

        $reject_s = $this->button('reject_s','拒绝','10_4_5');
        $adopt_s = $this->button('adopt_s','通过','10_4_6');

        $this->assign('reject_l',$reject_l);
        $this->assign('adopt_l',$adopt_l);

        $this->assign('reject_t',$reject_t);
        $this->assign('adopt_t',$adopt_t);

        $this->assign('reject_s',$reject_s);
        $this->assign('adopt_s',$adopt_s);
        $this->display('listAudit');
    }

    /*
     * [addList 添加榜单]
     */
    public function addList()
    {
        $type = I('get.type','','addslashes');
        $listNumber = I('post.listNumber','','int');
        $listTitle = I('post.listTitle','','addslashes');
        $videoUrl = I('post.videoUrl','','addslashes');
        $ImgUrl = I('post.ImgUrl','','addslashes');
        $listTypes = I('post.listTypes','','addslashes');//类型
        $onlineType = I('post.onlineType','','addslashes');//创客id
        $tmp_list = M('tmp_list');
        if(empty($listTitle)||empty($type)||empty($videoUrl)||empty($ImgUrl)||empty($listTypes)||empty($onlineType)){
            session('msg','填写信息不完整!');
            session('m_type',0);
            $this->redirect('List/editList');
        }
        if($type == 'add'){//添加榜单
            $data = array(
                'is_active' => 6,
                'stage' => $listNumber,
                'show_id' => $onlineType,
                'type' => $listTypes,
                'create' => time(),
                'video_title' => $listTitle,
                'video_url' => $videoUrl,
                'img_url' => $ImgUrl
            );
            $res = $tmp_list->data($data)->add();
            /**
             * 写入日志
             */
            $log = A('Admin/LogEvent');
            if($res) {
                $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':添加榜单成功','fullaction' => __ACTION__,));
                $this->success('添加榜单成功!','',1);
                session('msg','添加榜单成功!');
                session('m_type',1);
                $this->redirect('List/editList');
            }
            else{
                $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':添加榜单失败','fullaction' => __ACTION__,));
                session('msg','添加榜单失败!');
                session('m_type',0);
                $this->redirect('List/editList');
            }
        }
    }

    /*
     * [getOneListInfo 单个榜单信息]
     */
    public function getOneListInfo($list_id){
        $url = C('CURL_');
        $data = array(
            'type' => 3,
            'id' => $list_id
        );
        $curl = A('SendRequest');
        $res = $curl->curlPost($url.'met/topTen',$data);
        $res = json_decode($res,true);

        return $res['array'];
    }

    /*
     * [getAwaitAuditData 获取榜单待审核信息]
     */
    private function getAwaitAuditData($show_id,$list_id=null,$n1){
        $tmp_list = M('tmp_list');

        $where['show_id'] = $show_id;
        if($list_id){
            $where['list_id'] = $list_id;
        }
        $in['list.is_active'] = array('in',$n1);
        $z = $tmp_list->field('show_id,list_id,maker_id,is_active,index,maker_desc,maker_icon,maker_name')->where($where)->buildSql();
        $data = $tmp_list->table($z.'list')->where($in)->select();
        return $data;
    }

    /*
     * [getListInfo 获取榜单信息]
     */
    public function getListInfo()
    {
        $list_id = I('post.list_id');
        $show_id = I('post.show_id');
        //榜单数据
        $onlineListListInfo = $this->getOneListInfo($list_id);
        //队伍信息
        $makerTeamList = $this->getMakerTeamList();
        //待审核信息
        $await_audit = $this->getAwaitAuditData($show_id,$list_id,'4,5');

        foreach($onlineListListInfo as $k => $v){
            foreach($await_audit as $k1 => $v1){
                if($onlineListListInfo[$k]['index'] == $await_audit[$k1]['index']){
                    if($await_audit[$k1]['is_active'] == 5){//删除排名
                        $res[$k]['status'] = $await_audit[$k1]['is_active'];
                        unset($onlineListListInfo[$k]);
                        break 1;
                    }
                }
            }
        }
        /**
         * 写入日志
         */
        $log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':获取单个榜单信息','fullaction' => __ACTION__,));

        $this->assign('makerTeamList',$makerTeamList);
        $this->assign('await_audit',$await_audit);
        $this->assign('onlineListListInfo',$onlineListListInfo);
        $this->display('listInfo');
    }

    /*
     * [getListList 榜单列表]
     */
    public function getListList(){
        $this->setSession();
        $on_id = I('post.on_id');
        if(empty($on_id)) return false;
        $url = C('CURL_');
        $data = array(
            'type' => 2,
            'id' => $on_id
        );
        $curl = A('SendRequest');
        $res = $curl->curlPost($url.'met/topTen',$data);
        $res = json_decode($res,true);
        if($res['array']){
            //待审核信息
            $await_audit = $this->getAwaitAuditData($on_id,'','4,5,14,15');
            $res = $res['array'];
            if($await_audit){
                foreach($res as $k => $v){
                    foreach($await_audit as $k1 => $v1){
                        if($res[$k]['id'] == $await_audit[$k1]['list_id']){
                            $res[$k]['status'] = $await_audit[$k1]['is_active'];
                            break 1;
                        }
                    }
                }
            }
        }
        /**
         * 写入日志
         */
        $log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':获取榜单列表','fullaction' => __ACTION__,));

        echo json_encode($res);
    }

    /*
     * [listTypeSave 添加榜单类型]
     */
    public function listTypeSave()
    {
        $id = I('get.id','','int');
        $listName = I('post.listName','','addslashes');
        $listInstr = I('post.listInstr','','addslashes');
        $listRule = I('post.listRule','','addslashes');
        $score = I('post.score','','int');
        $listLOGO = I('post.listLOGO_url','','addslashes');
        if(empty($listName) || empty($listLOGO)){
            session('msg','榜单类型填写信息不完整!');
            session('m_type',0);
            $this->redirect('List/listType');
        }
        $tmp_list = M('tmp_list');
        if(!$id){
            $data = array(
                'is_active' => 8,
                'type' => $listName,
                'img_url' => $listLOGO,
                'index' => $score,
                'create' => time(),
                'list_desc' => $listInstr,
                'list_rule' => $listRule
            );
        }else{
            $data = array(
                'show_id' => $id,
                'is_active' => 11,
                'type' => $listName,
                'img_url' => $listLOGO,
                'index' => $score,
                'create' => time(),
                'list_desc' => $listInstr,
                'list_rule' => $listRule
            );
        }
        $res = $tmp_list->data($data)->add();
        /**
         * 写入日志
         */
        $log = A('Admin/LogEvent');
        if(!$id){
            if($res){
                $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':添加榜单类型成功,待审核','fullaction' => __ACTION__,));
                session('msg','添加榜单类型成功,待审核!');
                session('m_type',1);
                $this->redirect('List/listType');
            }else{
                $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':添加榜单类型失败','fullaction' => __ACTION__,));
                session('msg','添加榜单类型失败!');
                session('m_type',0);
                $this->redirect('List/listType');
            }
        }else{
            if($res){
                $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':修改榜单类型成功,待审核','fullaction' => __ACTION__,));
                session('msg','修改榜单类型成功,待审核!');
                session('m_type',1);
                $this->redirect('List/listType');
            }else{
                $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':修改榜单类型失败','fullaction' => __ACTION__,));
                session('msg','修改榜单类型失败!');
                session('m_type',0);
                $this->redirect('List/listType');
            }
        }

    }

    /*
     * [ sort 榜单排序]
     */
    public function sort()
    {
        $newsort = I('post.newsort');
        $record = I('post.record');
        $newscore = I('post.newscore');
        $newsort = explode(',',rtrim($newsort,','));//榜单权重
        $newscore = explode('aa=',$newscore);//榜单id
        //$record = htmlspecialchars($record);
        unset($newscore[0]);
        $tmp_list_sort = M('tmp_list_sort');

        $data = array(
            'show_id' => serialize($newscore),
            'score' => serialize($newsort),
            'create' => time(),
            'is_active' => 0,
            'style' => $record
        );

        $res = $tmp_list_sort->data($data)->add();
        /**
         * 写入日志
         */
        $log = A('Admin/LogEvent');

        if($res){
            $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':榜单排序保存成功','fullaction' => __ACTION__,));
            session('msg','保存成功!');
            session('m_type',1);
            $this->redirect('List/listShow');
        }
    else {
        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':榜单排序保存失败','fullaction' => __ACTION__,));
        session('msg','保存失败!');
        session('m_type',0);
        $this->redirect('List/listShow');
    }
    }

    /*
     * [getListInfoById 获取单个榜单审核数据]
     */
    public function getListInfoById(){
        $id = I('post.id');
        $list_id = I('post.list_id');
        $where['id'] = $id;
        $tmp_list = M('tmp_list');
        $res = $tmp_list->where($where)
            ->field('show_id,list_id,maker_id,video_id,is_active,index,create,stage,type,video_title,video_url,img_url,maker_desc,maker_icon,maker_name')
            ->find();
        /**
         * 写入日志
         */
        $log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':获取单个榜单审核数据','fullaction' => __ACTION__,));
        if($res['is_active'] != 6){
            $onlineListListInfo = $this->getOneListInfo($list_id);
            if($res['is_active'] == 4){
                $len = count($onlineListListInfo);
                $onlineListListInfo[$len]['index'] = $res['index'];
                $onlineListListInfo[$len]['makerDesc'] = $res['maker_desc'];
                $onlineListListInfo[$len]['makerIcon'] = $res['maker_icon'];
                $onlineListListInfo[$len]['makerName'] = htmlspecialchars_decode($res['maker_name']);
                $onlineListListInfo[$len]['meggg'] = 1;//增加排名
                $i = $onlineListListInfo[$len];
                unset($onlineListListInfo[$len]);
                array_unshift($onlineListListInfo,$i);
            }elseif($res['is_active'] == 5){
                foreach($onlineListListInfo as $k => $v){
                    if($v['index'] == $res['index']){
                        $onlineListListInfo[$k]['meggg'] = 2;//删除排名
                        $i = $onlineListListInfo[$k];
                        unset($onlineListListInfo[$k]);
                        array_unshift($onlineListListInfo,$i);
                        break;
                    }
                }
            }
            $this->assign('onlineListListInfo',$onlineListListInfo);
            $this->assign('listType',$res['type']);
            $this->display('audit_list_info');
        }
        else{
            $addListInfo[] = $res;
            $this->assign('addListInfo',$addListInfo);
            $this->display('add_list_info');
        }

    }


    /*
     * [getListInfo 获取榜单审核信息]
     */
    public function getListAuditInfo($ID){
        $tmp_list = M('tmp_list');
        $in['id'] = array('in',$ID);
        $info = $tmp_list->where($in)->field('id,show_id,list_id,maker_id,video_id,video_url,video_title,img_url,index,is_active,stage,type')->select();
        return $info;
    }

    /*
     * [adopt_l 榜单审核通过]
     */
    public function adopt_l()
    {
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        $tmp_list = M('tmp_list');
        $res = $this->getListAuditInfo($listID);
        if(!$res) return false;
        $url = C('CURL_');
        $curl = A('SendRequest');
        try{
            foreach($res as $k => $v){
                    if($v['is_active'] == 4){//增加排名
                        $data = array(
                            'type' => 4,
                            'showId' => $v['show_id'],
                            'topTenListId' => $v['list_id'],
                            'index' => $v['index'],
                            'makerId' => $v['maker_id']
                        );
                    }elseif($v['is_active'] == 5){//删除排名
                        $data = array(
                            'type' => 5,
                            'showId' => $v['show_id'],
                            'topTenListId' => $v['list_id'],
                            'index' => $v['index']
                        );
                    }elseif($v['is_active'] == 6){//添加榜单
                        $data = array(
                            'type' => 6,
                            'showId' => $v['show_id'],
                            'videoUrl' => $v['video_url'],
                            'videoName' => $v['video_title'],
                            'picUrl' => $v['img_url'],
                            'stage' => $v['stage'].'期'
                        );
                    }elseif($v['is_active'] == 14){//删除榜单期数
                        $data = array(
                            'type' => 14,
                            'id' => $v['list_id']
                        );
                    }elseif($v['is_active'] == 15){//修改榜单期数
                        $data = array(
                            'type' => 15,
                            'topTenShowId' => $v['show_id'],
                            'id' => $v['list_id'],
                            'stage' => $v['stage'].'期',
                            'videoId' => $v['video_id'],
                        );
                    }else{
                        break 1;
                    }
                        $res = $curl->curlPost($url.'met/topTen',$data);
                        $res = json_decode($res,true);
                        if(!$res['error'] || $res['id']){
                            $tmp_list->where(array('id'=>$v['id']))->delete();
                        }elseif($res['error'] == 'has index topTen'){
                            echo 2;
                            return false;
                        }
                        elseif($res['error']){
                            echo 0;
                            return false;
                        }
            }
            echo 1;
            $this->log('榜单审核通过');
        }catch(\Exception $e){
            echo 0;
            $this->log('榜单审核失败');
            throw new \Exception('榜单审核失败');
        }
    }

    /*
     * [reject_l 榜单审核拒绝]
     */
    public function reject_l()
    {
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        $where['id'] = array('in',$listID);
        $tmp_list = M('tmp_list');
        $res = $tmp_list->where($where)->delete();
        if($res){
            $this->log('榜单审核拒绝通过');
            echo 1;
        }
        else{
            $this->log('榜单审核拒绝失败');
            echo 0;
        }
    }

    /*
     * [adopt_t 榜单类型审核通过]
     */
    public function adopt_t()
    {
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        $listID = explode(',',$listID);
        $tmp_list = M('tmp_list');
        $tmp_type = M('tmp_type');
        $url = C('CURL_');
        $curl = A('SendRequest');
        $arr = array('季榜','周榜','月榜','年榜');
        $arr_type = array('季榜','周榜','月榜','年榜');
        try{
            foreach($listID as $k => $v){
                $where['id'] = $v;
                $res = $tmp_list->where($where)->field('show_id,index,type,img_url,is_active')->find();
                if($res){
                    if($res['is_active'] == 8){//添加榜单数据类型
                        $data = array(
                            'type' => 8,
                            'name' => $res['type'],
                            'icon' => $res['img_url'],
                            'score' => $res['index']
                        );
                        $data_f = array(
                            'type' => 13,
                            'desc' => $res['type'],
                            'pic' => $res['img_url'],
                            'score' => $res['index']
                        );
                    }elseif($res['is_active'] == 9){//删除榜单数据类型
                        $data = array(
                            'type' => 9,
                            'id' => $res['show_id']
                        );
                        $arr = $tmp_type->where(array('parent_id'=>$res['show_id']))->field('id')->select();
                        $data_f = array(
                            'type' => 12,
                            'showId' => ''
                        );
                    }elseif($res['is_active'] == 11){//修改榜单数据类型
                        $data = array(
                            'type' => 16,
                            'id' => $res['show_id'],
                            'name' => $res['type'],
                            'icon' => $res['img_url'],
                            'score' => $res['index']
                        );
                        $arr = $tmp_type->where(array('parent_id'=>$res['show_id']))->field('id')->select();
                        $data_f = array(
                            'type' => 11,
                            'showId' => '',
                            'desc' => $res['type'],
                            'pic' => $res['img_url'],
                            'score' => $res['index']
                        );
                    }
                    if($data){
                        $res_f = $curl->curlPost($url.'met/topTen',$data);
                        $res_f = json_decode($res_f,true);
                        if($res_f->error){
                            echo 0;
                            return false;
                        }
                    }
                    if(!$arr){
                        echo 0;
                        return false;
                    }
                    foreach($arr as $k1 => $v1){
                        if($res['is_active'] == 8){//添加榜单类型
                            $data_f['desc'] = $res['type'].$v1;
                            $res_t = $curl->curlPost($url.'met/topTen',$data_f);
                            $list_type = json_decode($res_t,true);
                            $data = array(
                                'id' => $list_type['id'],
                                'parent_id' => $res_f['id'],
                                'create' => time()
                            );
                            $tmp_type->data($data)->add();
                        }elseif($res['is_active'] == 9){//删除榜单类型
                            $data_f['showId'] = $v1['id'];
                            $res_t = $curl->curlPost($url.'met/topTen',$data_f);
                            $res_t = json_decode($res_t,true);
                            if(!$res_t->error)
                                $tmp_type->where(array('id'=>$v1['id']))->delete();
                            else{
                                echo 0;
                                return false;
                            }
                        }elseif($res['is_active'] == 11){//修改榜单类型
                            $data_f['showId'] = $v1['id'];
                            $data_f['desc'] = str_replace(' ','',$res['type'].$arr_type[$k1]);
                            $res_t = $curl->curlPost($url.'met/topTen',$data_f);
                            $res_t = json_decode($res_t,true);
                            if($res_t->error){
                                echo 0;
                                return false;
                            }
                        }
                    }
                    $tmp_list->where($where)->delete();
                }
            }
            echo 1;
            $this->log('榜单类型审核通过');
        }catch(\Exception $e){
            echo 0;
            $this->log('榜单类型审核失败');
            throw new \Exception('榜单类型审核失败');
        }

    }

    /*
     * [reject_t 榜单类型审核拒绝]
     */
    public function reject_t()
    {
        $tmp_list = M('tmp_list');
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        $listID = explode(',',$listID);
        $where['id'] = array('in',$listID);
        $res = $tmp_list->where($where)->delete();

        if($res){
            $this->log('榜单类型审核拒绝通过');
            echo 1;
        }else{
            $this->log('榜单类型审核拒绝失败');
            echo 0;
        }
    }

    /*
     * [adopt_s 榜单顺序审核通过]
     */
    public function adopt_s()
    {
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        $tmp_list = M('tmp_list_sort');
        $list_sort = $tmp_list->where(array('is_active'=>0))->field('show_id,score')->find();
        $show_id = unserialize($list_sort['show_id']);
        $score = unserialize($list_sort['score']);
        $url = C('CURL_');
        $curl = A('SendRequest');
        try{
            if(is_array($show_id)){
                foreach($show_id as $k => $v){
                    foreach($score as $k1 => $v1){
                        if(($k) == $k1+1){
                            $data = array(
                                'type' => 10,
                                'showId' => $v,
                                'score' => $v1
                            );
                            $res = $curl->curlPost($url.'met/topTen',$data);
                            break 1;
                        }
                    }
                }
                $tmp_list->where(array('id'=>$listID))->delete();
                echo 1;
                $this->log('榜单顺序审核通过');
                return false;
            }
            echo 0;
        }catch(\Exception $e){
            echo 0;
            $this->log('榜单顺序审核失败');
            throw new \Exception('榜单顺序审核失败');
        }
    }

    /*
     * [reject_s 榜单顺序审核拒绝]
     */
    public function reject_s()
    {
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        $in['id'] = array('in',$listID);
        $tmp_list = M('tmp_list_sort');
        $res = $tmp_list->where($in)->delete();

        if($res){
            $this->log('榜单顺序审核拒绝通过');
            echo 1;
        }else{
            $this->log('榜单顺序审核拒绝失败');
            echo 0;
        }
    }

    /*
     * [getMakerTeamInfo 队伍信息]
     */
    public function getMakerTeamInfo(){
        $makerTeam_id = I("post.makerTeam_id");
        $url = C('CURL_');
        $data = array(
            'type' => 2,
            'id' => $makerTeam_id
        );
        $curl = A('SendRequest');
        $res = $curl->curlPost($url.'met/team',$data);
        $res = json_decode($res);
        $teamInfo = array();
        foreach($res as $k => $v){
            $teamInfo[$k] = $v;
            if($k = 'members')
                $teamInfo[$k] = '';
        }
        $this->log('获取单个创客队伍信息');
        echo json_encode($teamInfo );
    }

    /*
     * [addRanking 增加排名]
     */
    public function addRanking(){
        if(!$this->checkOneAcl('10_1_5')){//检查权限
            echo 3;
            return false;
        }
        $showId = I('post.showId','','int');
        $type = I('post.type','','addslashes');
        $num = I('post.num','','int');
        $topTenListId = I('post.topTenListId','','addslashes');
        $index = I('post.index','','int');
        $teamId = I('post.teamId','','addslashes');
        $tmp_list = M('tmp_list');
        $maker_name = I("post.maker_name",'','addslashes');
        $maker_desc = I("post.maker_desc",'','addslashes');
        $maker_url = I("post.maker_url",'','addslashes');
        $data = array(
            'show_id' => $showId,
            'list_id' => $topTenListId,
            'index' => $index,
            'maker_id' => $teamId,
            'is_active' => 4,
            'stage' => $num,
            'create' => time(),
            'type' => $type,
            'maker_name' => htmlspecialchars($maker_name),
            'maker_desc' => $maker_desc,
            'maker_icon' => $maker_url
        );
        $res = $tmp_list->where(array('is_active'=>4,'index'=>$index,'list_id'=>$topTenListId))->find();
        if($res){
            $this->log('不能增加已有的排名');
            echo 2;
            return false;
        }
        $res = $tmp_list->data($data)->add();
        if($res){
            $this->log('增加排名成功');
            echo 1;
        }else{
            $this->log('增加排名失败');
            echo 0;
        }
    }

    /*
     * [delRanking 删除排名]
     */
    public function delRanking(){
        if(!$this->checkOneAcl('10_1_6')){//检查权限
            echo 2;
            return false;
        }
        $showId = I('post.showId');
        $type = I('post.type');
        $topTenListId = I('post.topTenListId');
        $index = I('post.index');
        $num = I('post.num');
        $maker_name = I("post.maker_name");
        $maker_desc = I("post.maker_desc");
        $maker_url = I("post.maker_url");
        $tmp_list = M('tmp_list');
        $data = array(
            'show_id' => $showId,
            'list_id' => $topTenListId,
            'index' => $index,
            'is_active' => 5,
            'stage' => $num,
            'create' => time(),
            'type' => $type,
            'maker_name' => $maker_name,
            'maker_desc' => $maker_desc,
            'maker_icon' => $maker_url
        );
        $res = $tmp_list->data($data)->add();
        if($res){
            $this->log('删除排名成功');
            echo 1;
        }else{
            $this->log('删除排名失败');
            echo 0;
        }
    }

    /*
     * [getListSortById 榜单顺序信息]
     */
    public function getListSortById(){
        $id= I('post.id');
        $tmp_sort = M('tmp_list_sort');
        $res = $tmp_sort->where(array('id'=>$id))->field('style')->find();
        $this->log('获取榜单顺序预览信息');
        echo htmlspecialchars_decode($res['style']);
    }

    /*
     * [delList 删除榜单类型]
     */
    public function delList(){
        if(!$this->checkOneAcl('10_2_2')){//检查权限
            echo 2;
            return false;
        }
        $this->setSession();
        $onlineID = I('post.onlineID');
        $con = I('post.con');
        $url = I('post.url');
        $score = I('post.score');

        $onlineID = rtrim($onlineID,',');
        $con = rtrim($con,',');
        $url = rtrim($url,',');
        $score = rtrim($score,',');

        $onlineID = explode(',',$onlineID);
        $con = explode(',',$con);
        $url = explode(',',$url);
        $score = explode(',',$score);

        $tmp_list = M('tmp_list');
        $tmp_list->startTrans();
        foreach($onlineID as $k => $v){
            $data = array(
                'is_active' => 9,
                'index' => $score[$k],
                'show_id' => $onlineID[$k],
                'type' => $con[$k],
                'img_url' => $url[$k],
                'create' => time()
            );
            $res[] = $tmp_list->data($data)->add();
        }
        if(!in_array(0,$res)){
            $tmp_list->commit();
            $this->log('删除榜单类型成功');
            echo 1;
        }else{
            $tmp_list->rollback();
            $this->log('删除榜单类型失败');
            echo 0;
        }
    }

    /*
     * [delListNum 删除榜单]
     */
    public function delListNum(){
        $this->setSession();
        $l_num = I('post.l_num');
        $l_num = str_replace('期','',$l_num);
        $l_id = I('post.l_id');
        $show_name = I('post.show_name');
        $show_id = I('post.show_id');
        if(empty($l_id) || empty($show_id)){
            echo 0;
            return false;
        }
        $tmp_list = M('tmp_list');
        $data = array(
            'show_id' => $show_id,
            'list_id' => $l_id,
            'is_active' => 14,
            'create' => time(),
            'stage' => $l_num,
            'type' => $show_name
        );
        if($tmp_list->data($data)->add()){
            echo 1;
            $this->log('删除榜单期数成功,待审核!');
        }else{
            echo 0;
            $this->log('删除榜单期数失败,待审核!');
        }
    }

    /*
     * [editListStage 修改榜单期数]
     */
    public function editListStage(){
        $this->setSession();
        $id = I('post.id','','addslashes');
        $videoid = I('post.videoid','','addslashes');
        $showid = I('post.showid','','int');
        $show_name = I('post.show_name','','addslashes');
        $stage_num = I('post.stage_num','','int');
        if(empty($stage_num)){
            echo 0;
            return false;
        }
        $tmp_list = M('tmp_list');
        $data = array(
            'show_id' => $showid,
            'list_id' => $id,
            'is_active' => 15,
            'stage' => $stage_num,
            'video_id' => $videoid,
            'create' => time(),
            'type' => $show_name
        );
        $res = $tmp_list->data($data)->add();
        if($res){
            echo 1;
        }else{
            echo 0;
        }
    }

    private function setSession(){
        session('msg',null);
        session('m_type',null);
    }

    /*
     * 写入日志
     */
    public function log($data){
        $log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':'.$data,'fullaction' => __ACTION__,));
    }

    /*
     * [checkOneAcl 检查单个权限]
     */
    protected function checkOneAcl($str){
        $u_id = session('user.user_id');
        $acl = D('acl');
        $resource = $acl->getAcl($u_id);
        if($resource == 'all'){
            return true;
        }else{
            $resource = unserialize($resource);
            if(in_array($str,$resource)){
                return true;
            }else{
                return false;
            }
        }
    }
}
?>