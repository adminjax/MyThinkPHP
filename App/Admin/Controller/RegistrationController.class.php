<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/16
 * Time: 13:44
 */

namespace Admin\Controller;


/*
 * 报名信息维护类
 */
class RegistrationController extends CommonController
{

    private $num = 10;
    /**
     * [_initialize 初始化]
     * @return [type] [description]
     */
    public function _initialize()
    {
        parent::checkLogin();
        $menu = parent::getMenu();

        $this->assign('menu', $menu);
    }

    /*
     * [playList 打榜说明入口]
     */
    public function playList(){
        if(session('msg') && session('m_type')){
            $this->assign('msg',session('msg'));
            $this->assign('m_type',session('m_type'));
        }
        $play = M('play_instruction');
        $play_info = $play->field('p_instr,content,start_time,end_time')->find();
        $play_info['content'] = htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode($play_info['content'])));
        if(empty($play_info['p_instr'])){
            $play_info['p_instr'] = 'nu';
        }
        $save = $this->button('save','提交至审核','11_1_1');
        $this->assign('save',$save);
        $this->assign('play_info',$play_info);
        $this->assign('p_instr',$play_info['p_instr']);
        $this->display('playList');
    }

    /*
     * [playList 标签管理入口]
     */
    public function tagManage(){
        session('msg',null);
        session('m_type',null);
        $url = C('CURL_');
        $curl = A('SendRequest');
        $data = array(
            'type' => 2
        );
        $tag_info = $curl->curlPost($url.'met/registTeam',$data);
        $tag_info = json_decode($tag_info, true);
        $status = D("tags")->where("is_active=5 or is_active=4")->getField("t_id,is_active,score");
        foreach ($tag_info['array'] as $k => $v) {
            if($v['id'] == $status[$v['id']]['t_id']){
                $tag_info['array'][$k]['status'] = $status[$v['id']]['is_active'];
            }
        }
        $tag_info['array'] = $this->arraySequence($tag_info['array'],'score');
        $save = $this->button('save','提交至审核','11_2_1');
        $del_tag = $this->button('del_tag','删除标签','11_2_2');
        $edit_tag = $this->button('edit_tag','修改标签','11_2_3');
        $add_tag = $this->button('add_tag','添加标签','11_2_4');
        $this->assign('tag_infos',$tag_info['array']);
        $this->assign('save',$save);
        $this->assign('del_tag',$del_tag);
        $this->assign('edit_tag',$edit_tag);
        $this->assign('add_tag',$add_tag);
        $this->assign('status', $status);
        $this->display('tagManage');
    }

    /**
     * 二维数组根据字段进行排序
     * @params array $array 需要排序的数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
    function arraySequence($array, $field, $sort = 'SORT_DESC')
    {
        $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }

    /*
     * [playList 报名成功入口]
     */
    public function regSuccess()
    {
        $save = $this->button('save','提交至审核','11_1_1');
        $this->assign('save',$save);
        $this->display('regSuccess');
    }

    /*
     * [playList 审核入口]
     */
    public function regAudit()
    {
        session('msg',null);
        session('m_type',null);
        $p = I('get.p',1,'int');
        $re = ($p * $this->num) - $this->num;
        //打榜说明审核
        $play_instr = $this->getPlayInstr($re,$p);
        $this->assign('play_instr',$play_instr['res']);
        $this->assign('instr_page',$play_instr['instr_page']);

        //个性标签审核
        $id = '3,4,5';
        $tags_info = $this->getTagsInfo($id,$re,$p);
        $this->assign('tags_info',$tags_info['tags_info']);
        $this->assign('tag_page',$tags_info['tag_page']);

        //报名成功审核

        $reject_p = $this->button('reject_p','拒绝','11_4_1');
        $adopt_p = $this->button('adopt_p','通过','11_4_2');

        $reject_t = $this->button('reject_t','拒绝','11_4_3');
        $adopt_t = $this->button('adopt_t','通过','11_4_4');

        $reject_s = $this->button('reject_s','拒绝','11_4_5');
        $adopt_s = $this->button('adopt_s','通过','11_4_6');

        $this->assign('reject_p',$reject_p);
        $this->assign('adopt_p',$adopt_p);

        $this->assign('reject_t',$reject_t);
        $this->assign('adopt_t',$adopt_t);

        $this->assign('reject_s',$reject_s);
        $this->assign('adopt_s',$adopt_s);

        $this->display('regAudit');
    }

    /*
     * [playInstr 打榜说明]
     */
    public function playInstr(){
        session('msg',null);
        session('m_type',null);
        $content = I('post.editorValue');
        $p_instr = I('post.p_instr','','addslashes');
        $start = I('post.start', '', 'addslashes');
        $end = I('post.end', '', 'addslashes');
        if($p_instr != 'nu'){
            $data = D('tmp_play')->where('p_instr='.$p_instr)->find();
            if(!empty($data)){
                session('msg','还有未审核项，请审核后再做修改！');
                session('m_type',0);
                $this->redirect('Registration/playList');
                return false;
            }
        }
        if(empty($start) || empty($end)){
            session('msg','信息填写不完整!');
            session('m_type',0);
            $this->redirect('Registration/playList');
            return false;
        }

        if($p_instr){
            $startTime = strtotime($start);
            $endTime = strtotime($end);

            $content = htmlspecialchars($content);
            $play_instr = M('tmp_play');
            $data = array(
                'p_instr' => $p_instr,
                'content' => $content,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'auther' => session('user.username'),
                'create' => time()
            );
            $res = $play_instr->data($data)->add();
            if($res){
                $this->log('打榜说明提交成功');
                session('msg','打榜说明提交成功!');
                session('m_type',1);
                $this->redirect('Registration/playList');
            }else{
                $this->log('打榜说明提交失败');
                session('msg','打榜说明提交失败!');
                session('m_type',0);
                $this->redirect('Registration/playList');
            }
            return false;
        }
        session('msg','打榜说明提交失败!');
        session('m_type',0);
        $this->redirect('Registration/playList');
    }

    /*
     * [saveTag 保存标签信息]
     */
    public function saveTag(){
        $tag_desc = I('post.tag_desc','','addslashes');
        $score = I('post.score','','int');
        $tagID = I('post.tagID', '', 'int');
        $tags = M('tags');

        if(!empty($tag_desc)){
            if($tagID == ''){//添加
                $data = array(
                    'is_active' => 3,
                    'desc' => $tag_desc,
                    'score' => $score,
                    'create' => time()
                );

                $res = $tags->data($data)->add();
                if($res){
                    echo 1;
                    $this->log('添加标签信息通过');
                }
            }else{//修改
                $data = array(
                    't_id' => $tagID,
                    'is_active' => 4,
                    'desc' => $tag_desc,
                    'score' => $score,
                    'modify' => time()
                );

                $res = $tags->data($data)->add();
                if($res){ echo 1;
                $this->log('修改标签信息通过');
                }
            }
        }
    }

    /*
     * [del_tag 删除标签]
     */
    public function del_tag(){
        $tagID = I('post.tagID');
        $tagID = rtrim($tagID,',');
        $tagID = explode(',',$tagID);
        $tags = M('tags');
        try{
            foreach($tagID as $k => $v ){
                $tag[$k] = explode('.',$v);
                if($tag[$k]){
                    $data = array(
                        't_id' => $tag[$k][0],
                        'desc' => $tag[$k][1],
                        'score' => $tag[$k][2],
                        'is_active' => 5,
                        'create' => time()
                    );
                    $tags->data($data)->add();
                }
            }
            echo 1;
            $this->log('删除标签通过');
        }catch(\Exception $e){
            echo 0;
            $this->log('删除标签失败');
            throw new \Exception();
        }


    }

    /*
     * [regSuc 报名成功]
     */
    public function regSuc(){
        $reg_time = I('post.reg_time');
        $listIntr = I('post.listIntr');
        echo $reg_time;
        echo $listIntr;
    }

    /*
     * [adopt_l 打榜说明审核通过]
     */
    public function adopt_p()
    {
        $url = C('CURL_');
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        $play_instr = M('play_instruction');
        $tmp_play = M('tmp_play');
        $curl = A('SendRequest');
        try{
            $where['id'] = array('in',$listID);
            $con = $tmp_play->where($where)->field('id,p_instr,content,start_time,end_time')->select();
            if(!$con) return false;
            foreach($con as $k => $v) {
                if(empty($v['p_instr']) || $v['p_instr'] == 'nu'){
                    $add_save = array(
                        'content' => $v['content'],
                        'start_time' => $v['start_time'],
                        'end_time' => $v['end_time'],
                        'create'=>time()
                    );
                }else{
                    $data = array(
                        'content' => $v['content'],
                        'start_time' => $v['start_time'],
                        'end_time' => $v['end_time'],
                        'modify'=>time()
                    );
                }
                $data_post = array(
                    'type' => 6,
                    'startTime' => $v['start_time'],
                    'endTime' => $v['end_time']
                );
                $res = $curl->curlPost($url.'met/registTeam ',$data_post);
                if($add_save){
                    $play_instr->data($add_save)->add();
                }else{
                    $res1 = $play_instr->where(array('p_instr'=>$v['p_instr']))->save($data);
                }
                $tmp_play->where(array('id'=>$v['id']))->delete();
             }
            echo 1;
            $this->log('打榜说明审核通过');
        }catch(\Exception $e){
            echo 0;
            $this->log('打榜说明审核失败');
            throw new \Exception('打榜说明审核失败');
        }
    }

    /*
     * [reject_l 打榜说明审核拒绝]
     */
    public function reject_p()
    {
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        $tmp_play = M('tmp_play');
        $listID = explode(',',$listID);
        foreach($listID as $k => $v){
            $res[] = $tmp_play->where(array('id'=>$v))->delete();
        }
        if(!in_array(0,$res)){
            echo 1;
            $this->log('打榜说明审核拒绝');
        }
    }

    /*
     * [adopt_t 个性标签审核通过]
     */
    public function adopt_t()
    {
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        $tags = M('tags');
        try{
            $in['id'] = array('in',$listID);
            $tags_info = $tags->where($in)->field('id,t_id,is_active,desc,score,modify,create')->select();
            if(!empty($tags_info)){
                foreach($tags_info as $k => $v){
                        if($v['is_active'] == 3){//添加
                            $data = array(
                                'type' => 3,
                                'desc' => $v['desc'],
                                'score' => $v['score']
                            );
                        }else if($v['is_active'] == 4){//修改
                            $data = array(
                                'type' => 4,
                                'id' => $v['t_id'],
                                'desc' => $v['desc'],
                                'score' => $v['score']
                            );
                        }else if($v['is_active'] == 5){//删除
                            $data = array(
                                'type' => 5,
                                'id' => $v['t_id'],
                            );
                        }
                        $url = C('CURL_');
                        $curl = A('SendRequest');

                        $tag_info = $curl->curlPost($url.'met/registTeam',$data);
                        $tags->where(array('id'=>$v['id']))->delete();
                }
                echo 1;
                $this->log('个性标签审核通过');
            }
        }catch(\Exception $e){
            echo 0;
            $this->log('个性标签审核失败');
            throw new \Exception();
        }
    }

    /*
     * [reject_t 个性标签审核拒绝]
     */
    public function reject_t()
    {
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        $in['id'] = array('in',$listID);
        $data = array(
            'is_active' => 0
        );
        $tags = M('tags');
        if($tags->where($in)->delete()){
            $this->log('个性标签审核拒绝通过');
            echo 1;
        }else{
            echo 0;
        }
    }

    /*
     * [adopt_s 报名成功审核通过]
     */
    public function adopt_s()
    {
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        echo $listID;
    }

    /*
     * [reject_s 报名成功审核拒绝]
     */
    public function reject_s()
    {
        $listID = I('post.listID');
        $listID = rtrim($listID,',');
        echo $listID;
    }

    /*
     * [getTagsInfo 获取标签审核信息]
     */
    public function getTagsInfo($ID,$re,$p){
        $tags = M('tags');
        $in['is_active'] = array('in',$ID);
        $tags_info['tags_info'] = $tags->where($in)->field('id,t_id,is_active,desc,score,modify,create')->limit($re.','.$this->num)->select();
        $num = $tags->where($in)->count();
        $tags_info['tag_page'] = $this->pageTools($num,$p,$this->num);
        return $tags_info;
    }

    /*
     * [getPlayInstr 获取打榜说明审核信息]
     */
    public function getPlayInstr($re,$p){
        $tmp_play = M('tmp_play');
        $res['res'] = $tmp_play->field('id,p_instr,content,start_time,end_time,create')->limit($re.','.$this->num)->select();
        $num = $tmp_play->where(1)->count();
        $res['instr_page'] = $this->pageTools($num,$p,$this->num);
        if($res)
            return $res;
    }

    /*
     * [getPlayInfo 获取打榜说明内容信息]
     */
    public function getPlayInfo(){
        $p_id = I('post.listID');
        if(!$p_id) return false;
        $tmp_play = M('tmp_play');
        $res = $tmp_play->where(array('id'=>$p_id))->field('content,start_time,end_time')->find();
        if($res){
            $con = htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode($res['content'])));
            $this->log('获取打榜说明内容信息');
            $res['content'] = $con;
            $this->ajaxreturn($res);
        }else{
            echo 0;
        }
    }

    /*
     * 写入日志
     */
    public function log($data){
        $log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':'.$data,'fullaction' => __ACTION__,));
    }
}