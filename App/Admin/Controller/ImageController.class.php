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
 * 图片处理类
 */
class ImageController extends CommonController
{
    //上传类参数
    private $_maxSize = 3145728;// 设置附件上传大小
    private $_exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    private $_path = './Public/Uploads/images/origi/'; // 设置附件上传根目录
    private $_subName = array('date', 'Ymd');
    private $_saveName = array('uniqid','');
    private $_autoSub = true;

    //iamge类参数
    private $_thumbPath = './Public/Uploads/images/thumb/';
    private $_height;
    private $_width;
    protected $path;

    /**
     * [ks3client 实例化云存储]
     * @return [type] [description]
     */
    protected function ks3client(){
        //是否开启日志(写入日志文件)
        define("KS3_API_LOG",FALSE);
        //是否显示日志(直接输出日志)
        define("KS3_API_DISPLAY_LOG", FALSE);
        Vendor('ks3.Ks3Client');
        $client = new \Ks3Client(C('ACCESSKEY'), C('SECRETKEY'), C('REGION'));

        return $client;
    }

    /**
     * [getObject 获取上传对象]
     * @return [type] [description]
     */
    public function getObject(){
        return $this->ks3client();
    }

    /**
     * [bigFile 上传大文件]
     * @param  [array] $file [大文件信息]
     * @return [type]       [description]
     */
    public function bigFile($file){
        $client = $this->ks3client();

        $path = pathinfo($file['Filedata']['name']);
        $exts = $path['extension'];
        $name = create_unique_id().'.'.$exts;

        $args = array(
            'Bucket' => C('BUCKET'),
            'Key' => $name,
            "ACL"=>"public-read-write",
            'UserMeta' => array(
                'x-kss-meta-met' => 'metupload',
            ),
            'ObjectMeta' => array(
                'Content-Type' => 'text/plain',
            ),
        );

        $uploadid = $client->initMultipartUpload($args);
        $uploadid = $uploadid['UploadId'];

        $file = $file['Filedata']['tmp_name'];
        $partsize = 1024*1024*5; //5m
        $resource = fopen($file, 'r');
        $stat = fstat($resource);
        $total = $stat['size']; //文件的总大小
        fclose($resource);
        $count = (int)($total/$partsize+1); //计算文件需要分几块上传

        for ($i = 0; $i < $count; $i++) { 
            //依次上传每一块
            $args=array(
                "Bucket"=>C('BUCKET'),
                "Key"=>$name,
                "Options"=>array(
                    "partNumber"=>$i+1,
                    "uploadId"=>$uploadid
                ),
                "ObjectMeta"=>array(
                    "Content-Length"=>min($partsize,$total-$partsize*$i)//每次上传$partsize大小
                ),
                "Content"=>array(
                    "content"=>$file,
                    "seek_position"=>$partsize*$i//跳过之前已经上传的
                )
            );

            $etag = $client->uploadPart($args);
            $etag = $etag["ETag"];
        }

        $parts = $client->listParts(array("Bucket"=>C('BUCKET'),"Key"=>$name,"Options"=>array("uploadId"=>$uploadid)));

        $args=array(
            "Bucket"=>C('BUCKET'),
            "Key"=>$name,
            "Options"=>array("uploadId"=>$uploadid),
            "Parts"=>$parts["Parts"]//使用之前列出的块完成分开上传
        );

        $result = $client->completeMultipartUpload($args);
 
        $this->path = 'http://'.C('BUCKET').'.'.C('REGION').'/'.$name;
    }

    /**
     * [getName 设置名称]
     * @return [type] [description]
     */
    protected function getName(){
        return sha1(time().rand(1000));
    }

    /**
     * [upLoadImage 上传图片]
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function upLoadImage($file){
        $content = fopen($file['Filedata']['tmp_name'], "r");
        $path = pathinfo($file['Filedata']['name']);
        $exts = $path['extension'];
        $name = $this->getName().'.'.$exts;

        $args = array(
            "Bucket"=>C('BUCKET'),
            "Key"=>$name,
            "Content"=>array(
                "content"=>$content,
                "seek_position"=>0
                ),
            "ACL"=>"public-read-write",
            "ObjectMeta"=>array(
                "Content-Type"=>"binay/ocet-stream"
                ),
            "UserMeta"=>array(
                'x-kss-meta-met'=>"met"
                )
        );
        //上传
        $client = $this->ks3client();
        $re = $client->putObjectByFile($args);
        if(!$re['ETag']){
            return false;
        }else{
            $this->path = 'http://'.C('BUCKET').'.'.C('REGION').'/'.$name;
        }
    }

    /**
     * [getPath 获取图片路径]
     * @return [type] [description]
     */
    public function getPath(){
        return $this->path;
    }

    /**
     * [createThumb 创建缩略图]
     * @param [string] $path [原图路径]
     * @return [type] [description]
     */
    protected function createThumb($path, $name){
        $image = new \Think\Image();
        $image->open($path);
        $re = $image->thumb($this->_width, $this->_height, \Think\Image::IMAGE_THUMB_FIXED)->save($this->_thumbPath.$name);

        if($re){
            return substr($this->_thumbPath.$name, 2);
        }
    }


    /**
     * [upLoadImage 图片上传]
     * @param  [array] $config [配置信息]
     * @return [type]         [description]
     */
    public function upLoadImage_bak($width, $height){
        $this->_height = $height;
        $this->_width = $width;

        $config = array(
            'maxSize' => $this->_maxSize,
            'rootPath' => $this->_path,
            'saveName' => $this->_saveName,
            'exts' => $this->_exts,
            'autoSub' => $this->_autoSub,
            'subName' => $this->saveName,
            );

        $upload = new \Think\Upload($config);// 实例化上传类
        $images = $upload->upload();

        if(!$images) {
            // 上传错误提示错误信息
            $this->error($upload->getError());
        }else{
            $imgPath = $this->_path.$images['Filedata']['savename'];
            $re = $this->createThumb($imgPath, $images['Filedata']['savename']);

            if($re){
                return $re;
            }
        }
    }

}


?>