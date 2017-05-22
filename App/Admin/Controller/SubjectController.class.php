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
* 专题类
*/
class SubjectController extends CommonController
{
    private $_subject = 'subject';
    private $type = 0;
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
            echo json_encode(array("error"=>"0","pic"=>$path,"name"=>'0000000'));
        }
    }

    /**
     * [setSubject 添加专题]
     */
    public function setSubject(){
        //判断是添加还是修改
        $id = I('post.id', '', 'addslashes');
        //获取视频url
        if($_POST['video-url']){
            $video = I('post.video-url', '', 'addslashes');
        }else{
            $video = I('post.embed-url', '', 'addslashes');
        }
        if(I('post.image-word')){
            $this->type = 3;
            $content = array(
                'type' => $this->type,
                'content' => htmlspecialchars(I('post.image-word')),
                'description' => I('post.desc', '', 'addslashes'),
                'u_info' => I('post.anthor', '', 'addslashes'),
            );
        }elseif (I('post.embed-url') || I('post.video-url')) {
            $this->type = 2;
            $content = array(
                'type' => $this->type,
                'content' => I('post.imgword', '', 'addslashes'),
                'description' => I('post.desc', '', 'addslashes'),
                'u_info' => I('post.anthor', '', 'addslashes'),
                'video' => $video,
                'size' => I('post.size', '', 'float'),
                'time' => I('post.time', '', 'int'),
            );
        }

        if($id){
            $content = array(
                's_id'=>$id,
                'title' => I('post.title', '', 'addslashes'),
                'icon' => I('post.imgl', '', 'addslashes'),
                'created' => time(),
                'is_sub' => I('post.is_subject', '', 'int'),
                'is_active' => 4,
                'user' => session('user.username'),
                't_id' => implode(',', I('post.tag')),
                're_id' => $id,
                'type' => $this->type,
                'content' => $video ? I('post.imgword', '', 'addslashes') : htmlspecialchars(I('post.image-word')),
                'description' => I('post.desc', '', 'addslashes'),
                'u_info' => I('post.anthor', '', 'addslashes'),
                'video' => $video,
                'size' => I('post.size', '', 'float'),
                'time' => I('post.time', '', 'int'),
            );
            $data = array(
                'is_active'=>4
            );
            $this->saveSubject($id,$data, $content);
        }else{
            $data = array(
                'title' => I('post.title', '', 'addslashes'),
                'icon' => I('post.imgl', '', 'addslashes'),
                'is_sub' => I('post.is_subject', '', 'int'),
                'created' => time(),
                'is_active' => 5,
                'user' => session('user.username'),
                't_id' => implode(',', I('post.tag')),
            );
            $this->addSubject($data, $content);
        }
        
    }

    /**
     * [editSubject 编辑专题]
     * @return [type] [description]
     */
    public function editSubject(){
        $id = I('get.id', '', 'addslashes');
        $subject = D($this->_subject);
        $re = $subject->getSubject($id);
        $re['content'] = htmlspecialchars_decode($re['content']);
        $this->ajaxreturn($re);
    }

    /**
     * [delSubject 删除专题]
     * @return [type] [description]
     */
    public function delSubject(){
        $ids = I('get.ids', '', 'addslashes');
        $id = array_filter(explode(',', $ids));

        $subject = D($this->_subject);
        $re = $subject->delSub($id);
        $this->ajaxreturn($re);
    }

    /**
     * [addSubject 添加专题]
     * @param [array] $data    [description]
     * @param [array] $content [description]
     */
    public function addSubject($data, $content){
        $subject = D($this->_subject);
        if($subject){
            $id = $subject->addSubject($data, $content);
            
            /**
             * 写入日志
             */
            $log = A('Admin/LogEvent');
            if($id > 0){
                $log->writeLog(array('action' => ACTION_NAME,'info' => '添加专题','fullaction' => __ACTION__,));
                cookie('msg','专题添加成功，请等待审核！',10); 
            }else{
                $log->writeLog(array('action' => ACTION_NAME,'info' => '添加专题失败','fullaction' => __ACTION__, 'status' => '0'));
                cookie('error','专题添加失败，请重试！',10); 
            }

            $this->redirect('Info/subject');
        }
    }

    /**
     * [saveSubject 修改后保存专题]
     * @param [array] $data    [description]
     * @param [array] $content [description]
     * @return [type] [description]
     */
    public function saveSubject($id, $data,$content){
        $subject = D($this->_subject);
        if($subject){
            $id = $subject->saveSubject($id,$data , $content);

            /**
             * 写入日志
             */
            $log = A('Admin/LogEvent');
            if($id > 0){
                $log->writeLog(array('action' => ACTION_NAME,'info' => '修改专题','fullaction' => __ACTION__,));
                cookie('msg','专题修改成功，请等待审核！',10); 
            }else{
                $log->writeLog(array('action' => ACTION_NAME,'info' => '修改专题失败','fullaction' => __ACTION__, 'status' => '0'));
                cookie('error','专题修改失败，请重试！',10); 
            }

            $this->redirect('Info/subject');
        }
    }

}
?>