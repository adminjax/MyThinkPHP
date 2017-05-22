<?php
/**
 * 开发测试用类
 */
namespace Admin\Controller;

class TestController extends CommonController
{

	/**
	 * [_initialize 初始化]
	 * @return [type] [description]
	 */
	public function _initialize(){
		

        parent::checkLogin();
    }

    public  function strip_html_tags($tags,$str){ 
        $html=array();
        foreach ($tags as $tag) {
            $html[]="/(<(?:\/".$tag."|".$tag.")[^>]*>)/i";
        }
        
        $data=preg_replace($html, '', $str); 
        return $data;
    } 

    public function uploadImage(){
    	if (!empty($_FILES)) {
    		$file['Filedata'] = $_FILES['file'];
           	$image = A('Admin/Image');
            $image->upLoadImage($file);
          	$path = $image->getPath();
          	
          	//echo $path;
          	echo json_encode(array("error"=>"0","pic"=>$path,"name"=>'0000000'));
        }
    }

    /**
     * [upload 分块上传]
     * @return [type] [description]
     */
    public function upload(){
    	$image = A('Admin/Image');
    	$client = $image->getObject();

    	$name = '111111111111111112.mp4';
    	$args = array(
    		'Bucket' => 'metphpbucket',
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

    	$file = $_FILES['file']['tmp_name'];
    	$partsize = 1024*1024*5;
    	$resource = fopen($file, 'r');
    	$stat = fstat($resource);
    	$total = $stat['size']; //文件的总大小
    	fclose($resource);
    	$count = (int)($total/$partsize+1); //计算文件需要分几块上传

    	for ($i = 0; $i < $count; $i++) { 
    		//依次上传每一块
    		$args=array(
	            "Bucket"=>"metphpbucket",
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

    	$parts = $client->listParts(array("Bucket"=>"metphpbucket","Key"=>$name,"Options"=>array("uploadId"=>$uploadid)));

    	$args=array(
	        "Bucket"=>"metphpbucket",
	        "Key"=>$name,
	        "Options"=>array("uploadId"=>$uploadid),
	        "Parts"=>$parts["Parts"]//使用之前列出的块完成分开上传
	    );

	    $result = $client->completeMultipartUpload($args);
    	print_r($result);
    }



	public function index(){

	$crul = A('SendRequest');
	$data = array(
		'type'=>1, 
		'account'=>'1234567890',
		'ship' => array(
				array(
					't_id' => 1,
					'num' => 1,
				),
				array(
					't_id' => 1,
					'num' => 1,
				),
			),
		);

	$d = $crul->curlPost('http://www.website.com/MET_Beta/index.php/Api/Met/serverb', $data);
	//echo $d;


die;
		$crul = A('SendRequest');
		$data = array('type'=>1, 'account'=>'1234567890', 'add'=>'56');
		//$token = md5('GizDNkkf1psoXVWzD0TZyhuvtpt2RyS5DKeUTv');

		$appsecret = 'GizDNkkf1psoXVWzD0TZyhuvtpt2RyS5DKeUTv';
		$appid = 's7GTA370JtACNwQAtvFI';
		$d = $crul->curlPost('http://www.website.com/MET_Beta/index.php/Api/Met/server/appid/'.$appid.'/appsecret/'.$appsecret, $data);
		var_dump($d);

die;
		$this->display();
die;
$str = 'aa测a锁定cc';

    $result = array();
    $len = strlen($str);
    $i = 0;
    while($i < $len){
        $chr = ord($str[$i]);
        if($chr == 9 || $chr == 10 || (32 <= $chr && $chr <= 126)) {
            $result[] = substr($str,$i,1);
            $i +=1;
        }elseif(192 <= $chr && $chr <= 223){
            $result[] = substr($str,$i,2);
            $i +=2;
        }elseif(224 <= $chr && $chr <= 239){
            $result[] = substr($str,$i,3);
            $i +=3;
        }elseif(240 <= $chr && $chr <= 247){
            $result[] = substr($str,$i,4);
            $i +=4;
        }elseif(248 <= $chr && $chr <= 251){
            $result[] = substr($str,$i,5);
            $i +=5;
        }elseif(252 <= $chr && $chr <= 253){
            $result[] = substr($str,$i,6);
            $i +=6;
        }
    }
    var_dump($result);

		die;
		$sub = D('subject_content');
		$s = $sub->where('s_id=138')->getField('content');
	
		die;
		$data = 5;
		echo strtotime("+".$data." day");

		echo date('Y-m-d H:m:s', strtotime("+".$data." day"));
		die;
		echo create_unique_num($type='sting');
		die;
		var_dump(unserialize(cookie('cart')));
		$this->display();

		die;
		//$list = $client->listBuckets();
		Vendor('ks3.Ks3Client');
		$client = new \Ks3Client('s7GTA370JtACNwQAtvFI', 'GizDNkkf1psoXVWzD0+TZyhuvtp/t2RyS5DKeUTv', 'ks3-cn-beijing.ksyun.com');
		$path = "./Public/Uploads/images/thumb/5858c90173fc1.jpg";
		$content = fopen($path, "r");

		$pathinfo = pathinfo($path);
		$name = $pathinfo['basename'];


		$args = array(
			"Bucket"=>"metphpbucket",
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
		$re = $client->putObjectByFile($args);
		//是否存在
		$args = '';
		$args = array(
			"Bucket" => "metphpbucket",
			"Key" => $name
			);
		//$re = $client->objectExists($args);
		//$re = $client->getObjectMeta($args);
		//$client->deleteObject($args);
		if($re){
			@unlink($paths);
		}
		var_dump($re);
		//$this->display();

die;
		Vendor('ks3.Ks3Client');
		$client = new \Ks3Client('s7GTA370JtACNwQAtvFI', 'GizDNkkf1psoXVWzD0+TZyhuvtp/t2RyS5DKeUTv', 'ks3-cn-beijing.ksyun.com');

//视频上传
		$path = "./Public/Uploads/images/ueditor/video/20161226/1482719650100182.mp4";
		$content = fopen($path, "r");

		$pathinfo = pathinfo($path);
		$name = $pathinfo['basename'];


		$args = array(
			"Bucket"=>"metphpbucket",
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
		$re = $client->putObjectByFile($args);

		var_dump($re);

die;
//图片上传		



die;
		$da = '&amp;lt;p&amp;gt;&amp;lt;span style=&amp;quot;font-weight: 700;&amp;quot;&amp;gt; &amp;amp;nbsp;DJ 是一项技术&amp;lt;/span&amp;gt;&amp;lt;br/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;DJ ，英文全称Disc Jockey，可以翻译成唱片骑师，是随着唱片产业的兴起而发展起来的。&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;DJ 是一项技术，是一门控制音乐的学问。通俗的讲就是放音乐，在夜店(club)、酒吧(bar)、派对、音乐节等场所都需要DJ，当然，广播电台也不例外。&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;DJ并不是音乐风格，许多人会把电音错误的理解成DJ或把电子舞曲错误的理解成“DJ舞曲”，这是概念的混淆，DJ 是一个技术名词，不是音乐类型。&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;DJ 的宗旨就是科学合理的并播放音乐，使舞者或聆听者产生快乐的感官享受。&amp;lt;img src=&amp;quot;http://www.met.com/Public/Uploads/images/ueditor/20161223/1482464997643626.jpg&amp;quot; title=&amp;quot;1482464997643626.jpg&amp;quot; alt=&amp;quot;0e2442a7d933c895f6121276d51373f08202004a.jpg&amp;quot;/&amp;gt;&amp;lt;/p&amp;gt;&amp;lt;p&amp;gt;&amp;lt;span style=&amp;quot;color: rgb(51, 51, 51); font-family: arial, 宋体, sans-serif; font-size: 14px; text-indent: 28px; background-color: rgb(255, 255, 255);&amp;quot;&amp;gt;DJ是指从事电台广播剧、电视台新闻报道等电子媒体节目播音工作的人，而“播报员”一词，通常又特指电视台的播音人员。 从事广电等媒体新闻播报的人被称做播音员。同模特儿一样，他们所从事的工作是将信息传达给每个人。就好比模特儿只能体现服饰，不能过度强调自己一样，播音员在播报新闻的时候，一般字正腔圆，不带任何感情色彩。所以说，对于一个播音员来说，更为重要的是自己的语言、音质，形象次之。&amp;lt;/span&amp;gt;&amp;lt;/p&amp;gt;';
		
		echo preg_replace('/^&[a-z];$/','',$da);;
die;
		$crul = A('SendRequest');
		$data = array(
			'page' => 1,
			'num' => 2,
			'type' =>1,
			);
		$d = $crul->curlPost('http://metmastersandbox.metworld.com.cn:8093/met/video', $data);

var_dump($d);


		$arr=json_decode($d,true);
		$arr2=array();
		 
		foreach($arr['informations'] as $val){
			$arr2[] = $val;
		}
		var_dump($arr2);

		$url = C('CURL_');
		$data = array(
			'name'=>'78',
			'page'=>'1',
			'num'=>'9',
			'type'=>5
		);
		$curl = A('SendRequest');
		$info = $curl->curlPost($url.'met/video ',$data);
		print_r($info);
		die;


$this->display();

die;		
$d = get_variable_name($msg);
var_dump($d);
die;
		$data = '{"informations":[{"id":"232344656","icon":"https://kss.ksyun.com/tuempetslb/i2.png","time":1475851000,"title":"从地下走到地上 嘻哈文化在中国年轻人中火热崛起\r","index":0,"isTop":true,"briefing":"说唱、街舞、涂鸦、DJ打碟、服饰为代表的嘻哈文化...","type":3,"url":"http://www.tuem.com.cn/Appclient/view/hip-pop.html"},{"id":"11111iu9iu","icon":"https://kss.ksyun.com/tuempetslb/i1.png","time":1475858200,"title":"2016 Red Bull BC One 中国赛Danny夺冠","index":1,"isTop":false,"briefing":"2016 Red Bull BC One中国赛8月18日下午在上海运动loft举行...","type":3,"url":"http://www.tuem.com.cn/Appclient/view/RedBull.html"},{"id":"1111","icon":"https://kss.ksyun.com/tuempetslb/i3.png","time":1478585010,"title":"机械哥新作《Higher》再次凌乱你的视觉","index":2,"picUrl":"https://kss.ksyun.com/tuempetslb/pics/2016-12-14-1.jpg","videoUrl":"https://kss.ksyun.com/tuempetslb/video/office_2016-12-12-1.mp4","isTop":false,"briefing":"机械哥Marquese Scott 的舞步有时给人一种脱离地心引力的错觉","type":2},{"id":"sdfhsdklfjsdklfjkls","icon":"https://kss.ksyun.com/tuempetslb/i10.png","time":1478585200,"title":"R16-精彩套路及routine剪辑\r","index":3,"picUrl":"https://kss.ksyun.com/tuempetslb/video/2016-12-13-2.jpg","videoUrl":"https://kss.ksyun.com/tuempetslb/video/sdjkfhsdjhfsjh.mp4","isTop":false,"briefing":"R16，全称R-16 Korea Sparkling","type":2},{"id":"bb","icon":"https://kss.ksyun.com/tuempetslb/pics/2016-12-14-1.jpg","time":1478585200,"title":"我是一个直播视频","index":4,"roomId":1,"isTop":true,"briefing":"直播哦","type":1}],"size":7}';
		var_dump(json_decode($data));die;

die;
		$crul = A('SendRequest');
		$data = array(
			'index' => 0,
			'num' => 5,
			'type' =>1,
			);
		$d = $crul->curl_post('192.168.120.92:8093/met/informamtion', $data);
		var_dump($d);


die;		
		//$v = A('Validate');
		//$re = $v->checkString('admins');
$resource = A('Acl')->getResource();
var_dump($resource);
die;
		var_dump(ACTION_NAME);
		var_dump(__MODULE__ );
		var_dump(CONTROLLER_NAME);
		var_dump(MODULE_NAME);
		var_dump(__ACTION__);
	}
}

?>