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
use Think\Exception;

/**
 * 积分商城
 */
class IntegralController extends CommonController
{
    //上传类参数
    private $_maxSize = 3145728;// 设置附件上传大小
    private $_exts = array('xlsx', 'xls');// 设置附件上传类型
    private $_path = './Public/Uploads/Files/'; // 设置附件上传根目录
    private $_subName = array('date', 'Ymd');
    private $_saveName = array('uniqid','');
    private $_autoSub = true;

    private $_product = 'product';
    private $_card = 'integral_card';
    private $_integral_info = 'integral_info';

	private $num = 10;//显示审核条数

	/**
     * [_initialize 初始化]
     * @return [type] [description]
     */
    public function _initialize(){
        parent::checkLogin();
        $menu = parent::getMenu();

        cookie('cart', null);

        $this->assign('menu', $menu);
    }


	/**
	 * [getInfo 获取商品信息]
	 * @return bool
	 */
	public function getInfo()
	{
		$id = I('post.g_id','','int');
		if($id)
		{
			$goods_info = M('tmp_goods')->where(array('id'=>$id))->getField('id,integral,title,img,content');
			if(empty($goods_info)){
				echo 0;
				return false;
			}
			//$goods_info = $this->parArr($goods_info,'integral');
			$goods_info[$id]['content'] = htmlspecialchars_decode(htmlspecialchars_decode($goods_info[$id]['content']));
			$this->log('获取审核商品预览信息');
			echo $this->ajaxReturn($goods_info,'json');
		}
	}

	/**
	 * [goodsEdit 商品编辑入口]
	 * @return [type] [description]
	 */
	public function goodsEdit(){
		$goods = M('goods');
		$tmp_goods = M('tmp_goods');
		$where['is_active'] = array('in','4,5');
		//商品信息
		$goods_info = $goods->join('left join goods_info ON goods.g_id=goods_info.g_id')
			->where(array('is_active'=>1))
			->order('sort desc')
			->getField('goods.g_id,goods.title,goods.sort,goods_info.header_img,goods.integral,goods_info.img,goods_info.content');
		if($goods_info){
			//待审核的商品
			$tmp_goods_info = $tmp_goods->where($where)->field('g_id,is_active')->select();
			if($tmp_goods_info){
				foreach($tmp_goods_info as $k => $v){
					if($v['g_id'] == $goods_info[$v['g_id']]['g_id']){
						if($v['is_active'] == 4){
							$goods_info[$v['g_id']]['au'] =4;//修改
						}else{
							$goods_info[$v['g_id']]['au'] =5;//删除
						}
					}
				}
			}
			foreach($goods_info as $k => $v){
				$goods_info[$k]['content'] = htmlspecialchars_decode($goods_info[$k]['content']);
			}
			//$goods_info = $this->parArr($goods_info,'integral');
			$this->assign('goods_info',$goods_info);
		}

		$sort = $this->button('new_sort','保存排序','2_1_1');
		$addGoods = $this->button('addGoods','添加商品','2_1_2');
		$del = $this->button('del','删除商品','2_1_3');
		$save = $this->button('save','保存并提交至审核','2_1_4');
		$this->log('商品编辑');
		$this->assign('sort',$sort);
		$this->assign('addGoods',$addGoods);
		$this->assign('del',$del);
		$this->assign('save',$save);
		$this->display('goodsEdit');
	}

	/**
	 * [audit 商品审核入口]
	 * @return [type] [description]
	 */
	public function audit(){
		session('msg',null);
		session('m_type',null);
		$p = I('get.p',1,'int');
		$p_num = ($p * $this->num) - $this->num;
		$tmp_goods = M('tmp_goods');
		//排版审核
		$where['tmp_goods.is_active'] = array('in','0');
		$where['tmp_goods.u_id'] = session('user.user_id');
		$goods_sort = $tmp_goods->join('left join admin_user ON tmp_goods.u_id=admin_user.u_id')
			->where($where)->limit("$p_num,$this->num")
			->order('tmp_goods.created desc')
			->field('tmp_goods.id,admin_user.username,tmp_goods.created,tmp_goods.is_active')
			->select();

		//page
		$sort_num = $tmp_goods->where($where)->count();//排版条数
		$page_sort = $this->pageTools($sort_num,$p,$this->num);

		if($goods_sort){
			$this->assign('goods_sort_info',$goods_sort);
			$this->assign('sort_num',$page_sort);
		}

		//商品审核
		$data['tmp_goods.is_active'] = array('in','3,4,5');
		$goods_info = $tmp_goods->join('left join admin_user ON tmp_goods.u_id=admin_user.u_id')
			->where($data)->order('created desc')
			->field('admin_user.username,tmp_goods.id,tmp_goods.g_id,tmp_goods.created,tmp_goods.is_active,tmp_goods.price')
			->select();
		//page
		$goods_num = $tmp_goods->where($data)->count();//商品条数
		$page_sort = $this->pageTools($goods_num,$p,$this->num);
		if($goods_info){
			$this->assign('goods_info',$goods_info);
			$this->assign('goods_num',$page_sort);
		}
		$this->log('商品、排版审核');
		$refuse_p = $this->button('refuse_p','拒绝','2_2_1');
		$pass = $this->button('pass','通过','2_2_2');
		$this->assign('refuse_p',$refuse_p);
		$this->assign('pass',$pass);

		$refuse = $this->button('refuse','拒绝','2_2_3');
		$adopt = $this->button('adopt','通过','2_2_4');
		$this->assign('refuse',$refuse);
		$this->assign('adopt',$adopt);
		$this->display('audit');
	}

	/**
	 * [delGoods 删除商品]
	 * @return bool
	 */
	public function delGoods()
	{
		session('msg',null);
		session('m_type',null);
		$g_id = I('post.g_id','','int');
		$integral = I('post.integral','','addslashes');
		$title = I('post.title','','addslashes');
		$img = I('post.img','','addslashes');
		$content = I('post.content');
		$g_id = rtrim($g_id,',');
		if(empty($g_id)){
			return false;
		}
		$data1 = array(
			'is_active'=>5,
			'g_id' => $g_id,
			'integral' => $integral,
			'title' => $title,
			'img' => $img,
			'content' => $content,
			'u_id' => session('user.user_id'),
			'created' => time()
		);
		$res = M('tmp_goods')->add($data1);
		if($res)
		{
			echo 1;
			$this->log('删除商品成功,待审核!');
			return false;
		}else
		{
			echo 0;
		}
	}

	/**
	 * [goodsAudit 审核商品]
	 * @return bool
	 */
	public function goodsAudit()
	{
		$p = '审核商品通过';
		$f = '审核商品拒绝';
		$id = I('post.g_id','','addslashes');
		$type = I('post.type','','addslashes');//判断是通过还是拒绝
		$id = rtrim($id,',');
		$goods = M('goods');
		$goods_info = M('goods_info');
		$tmp_goods = M('tmp_goods');
		$where['id'] = array('in',$id);
		$res = $tmp_goods->where($where)
			->field('id,g_id,is_active,goods_num,created,sort,u_id,integral,price,special_price,title,header_img,img,content,desc')
			->select();
		try{
			if($type == 'refuse'){//拒绝
				$resss = $tmp_goods->where($where)->delete();
				if($resss){
					$this->log($f);
					echo 1;
					return false;
				}
				throw new \Exception($f.'失败');
			}else{//通过
					foreach($res as $k => $v){
						$data = array(
							'integral' => $v['integral'],
							'is_active' => 1,
							'title' => $v['title'],
							'u_id' => $v['u_id'],
							'sort' => $v['sort']
						);
						$info = array(
							'header_img'=>$v['header_img'],
							'img'=>$v['img'],
							'content'=>$v['content'],
							'desc'=>$v['desc'],
						);
						if($v['g_id'] && $v['is_active'] == 4){
							$data['modified'] = $v['create'];
							$goods->where(array('g_id'=>$v['g_id']))->save($data);
							$goods_info->where(array('g_id'=>$v['g_id']))->save($info);
						}elseif(!$v['g_id'] && $v['is_active'] == 3){
							$data['goods_num'] = $v['goods_num'];
							$data['create'] = $v['create'];
							$add_id = $goods->data($data)->add();
							if($add_id){
								$info['g_id'] = $add_id;
								$goods_info->data($info)->add();
							}
						}elseif($v['g_id'] && $v['is_active'] == 5){
							$goods->where(array('g_id'=>$v['g_id']))->delete();
							$goods_info->where(array('g_id'=>$v['g_id']))->delete();
						}
						$tmp_goods->where(array('id'=>$v['id']))->delete();
					}
				}
				$this->log($p);
				echo 1;
				return false;
			}catch(\Exception $e){
				$msg = $e->getMessage();

				$this->ajaxreturn($msg);
			}
	}

	/**
	 * [addGoods 添加商品]
	 * @param int $type 3是添加，4是修改
	 * @return bool
	 */
	public function addGoods($type=3,$g_id = null){
		$header_title = I('post.title', '', 'addslashes');
		$smallImage = I('post.imgl', '', 'addslashes');
		$integral = I('post.integral','','addslashes');
		$sort = I('post.sort','','int');
		$headImage = I('post.imgls', '', 'addslashes');
		$content = I('post.editorValue');
		$content = htmlspecialchars($content);
		/*$content = mb_substr($content,17);
		$content = mb_substr($content,0,-18);*/
		$num = chr(mt_rand(65,90)).chr(mt_rand(97,122)).substr(md5(time()),11);
		if(empty($smallImage)||empty($headImage)||empty($header_title) || empty($sort))
		{
			session('msg','填写信息不完整!');
			session('m_type',0);
			$this->redirect('Integral/goodsEdit');
		}
		$check = A('Admin/Validate');
		$title_url = $check->checkPath($smallImage);
		$text_url = $check->checkPath($headImage);

		if($title_url && $text_url)
		{
			$data = array(
				'integral'=>$integral,
				'is_active'=>$type,
				'title'=>$header_title,
				'sort' => $sort,
				'u_id' => session('user.user_id'),
				'header_img'=>$smallImage,
				'img'=>$headImage,
				'content'=>$content,
				'created'=>time(),
				'desc'=>''
			);

			$tmp_goods = M('tmp_goods');
			if(empty($g_id)){
				$data['goods_num'] = $num;
				$msg1 = '添加商品成功,待审核!';
				$msg2 = '添加商品失败!';
			}else{
				$data['g_id'] = $g_id;
				$msg1 = '修改商品成功,待审核!';
				$msg2 = '修改商品失败!';
			}
			$res = $tmp_goods->data($data)->add();
			if($res){
				$this->log($msg1);
				session('msg',$msg1);
				session('m_type',1);
				$this->redirect('Integral/goodsEdit');
			}else{
				$this->log($msg2);
				session('msg',$msg2);
				session('m_type',0);
				$this->redirect('Integral/goodsEdit');
			}
		}
		else {
			session('msg','解析错误!');
			session('m_type',1);
			$this->redirect('Integral/goodsEdit');
		}
	}

	/**
	 * [getGoodsInfoById 商品信息]
	 * @param $g_id [商品信息ID]
	 * @return mixed
	 */
	public function getGoodsInfoById($g_id)
	{
		$goods_info = M('goods_info')->where(array('g_id'=>$g_id))->field('g_id,header_img,img,content,desc')->find();
		return $goods_info;
	}

	/**
	 * [editGoodsInfo 修改商品信息]
	 */
	public function editGoodsInfo()
	{
		$g_id = I('get.id','','int');
		$this->addGoods(4,$g_id);
	}

	/**
	 * [sort 商品新的排序]
	 */
	public function sort()
	{
		session('msg',null);
		session('m_type',null);
		$goods_id = I('post.newsort');//商品ID
		$sort = I('post.oldsort');//商品新的排序
		if($sort && $goods_id)
		{
			$tmp_goods = M('tmp_goods');
			$data = array(
				'g_id'=>serialize($goods_id),
				'u_id'=>session('user.user_id'),
				'new_sort'=>serialize($sort),
				'created'=>time(),
				'is_action'=>0
			);
			if($tmp_goods->add($data))
			{
				$this->log('保存商品排序');
				echo 1;
			}else echo 0;
		}
	}

	/**
	 * [sortID 获取商品排序表里的商品ID]
	 * @param $sort_id [排序ID]
	 * @return array
	 */
	public function sortID($sort_id)
	{
		$tmp_goods = M('tmp_goods');
		$where['id'] = array('in',$sort_id);
		$sort_info = $tmp_goods->where($where)->order('created desc')->getField('tmp_goods.id,tmp_goods.g_id');
		$goods_id = array();
		foreach($sort_info as $k => $v)
		{
			$goods_id[] = unserialize($v);
		}
		$this->log('获取商品排序表的商品id');
		return $goods_id;
	}

	/**
	 * [getSortInfo 获取商品信息]
	 */
	public function getSortInfo()
	{
		$sort_id = I('post.sort_id');
		$goods_id = $this->sortID($sort_id);
		if($goods_id){
			$goods = M('goods');
			$g_id = implode(',',$goods_id[0]);
			$where['goods.g_id'] = array('in',$g_id);
			$goods_info = $goods->join('left join goods_info ON goods.g_id=goods_info.g_id')->order('sort asc')->where($where)->Field('goods.sort,goods.g_id,goods.integral,goods_info.header_img')->select();

			//$goods_info = $this->parArr($goods_info,'integral');
			$new_goods_sort = array();
			foreach($goods_id[0] as $k=>$v)
			{
				foreach($goods_info as $k1=>$v1)
				{
					if($goods_info[$k1]['g_id'] == $v)
					{
						$new_goods_sort[] = $goods_info[$k1];
						break 1;
					}
				}
			}
				$this->log('审核预览商品排版信息');
				$this->assign('new_goods_sort',$new_goods_sort);
		}
		$this->display('previewSort');
	}

	/**
	 * [setting 排版审核]
	 */
	public function setting()
	{
		$sort_id = I('post.sort_id', '', 'addslashes');
		$type = I('post.type', '', 'addslashes');
		$sort_id = rtrim($sort_id,',');
		$where['id'] = array('in',$sort_id);
		$goods = M('goods');
		$tmp_goods = M('tmp_goods');
		$sort = '';
		if($type == 'pass')//审核通过
		{
			try{
				$where['id'] = array('in',$sort_id);
				$sort_info = $tmp_goods->where($where)->order('created desc')->getField('tmp_goods.id,tmp_goods.g_id,tmp_goods.new_sort');
				foreach($sort_info as $k => $v){
					$g_id = unserialize($v['g_id']);
					$newsort = unserialize($v['new_sort']);
					foreach($g_id as $k1 => $v1){
						foreach($newsort as $k2 => $v2){
							if($k1 == $k2){
								$goods->where(array('g_id'=>$v1))->save(array('sort'=>$v2));
								$tmp_goods->where(array('id'=>$v['id']))->delete();
								break;
							}
						}
					}
				}
				$sort = 1;
			}catch(\Exception $e){
				$this->log('商品排版审核失败！');
				echo 0;
				throw new \Exception('商品排版审核失败！');
			}
		}
		elseif($type == 'refuse_p')//审核拒绝
		{
			$sort = $tmp_goods->where(array('id'=>$sort_id))->delete();
		}

		if($sort){
			$this->log('商品排版审核通过！');
			echo 1;
		}
		else{
			$this->log('商品排版审核失败！');
			echo 0;
		}
	}

  	/**
  	 * [magange 积分卡管理入口]
  	 * @return [type] [description]
  	 */
  	public function magange(){
		$bind = $this->button('bind','绑定','2_3_1');
		$add = $this->button('add','确认添加','2_3_2');
		$confirm = $this->button('confirm','确认','2_3_3');
		$code = $this->button('code','确认','2_3_4');
		$sku = $this->button('sku','确认','2_3_5');
		$confirm1 = $this->button('confirm1','确认兑换','2_3_6');
		$importExcel = $this->button('importExcel','导入商品','2_3_7');
		$set_ratio = $this->button('set-ratio','设置比例','2_3_8');
		$this->assign('bind',$bind);
		$this->assign('add',$add);
		$this->assign('code',$code);
		$this->assign('sku',$sku);
		$this->assign('confirm',$confirm);
		$this->assign('confirm1',$confirm1);
		$this->assign('importExcel',$importExcel);
		$this->assign('set_ratio',$set_ratio);
		$this->display();
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
     * [seachUser 查询用户信息]
     * @return [json] [用户信息]
     */
    public function seachUser(){
    	  $phone = I('get.phone', '', 'addslashes');

    	  if($phone){
        		try{
          			$curl = A('SendRequest');
          			$data = array('type'=>1, 'account'=>$phone);
          			$re = $curl->curlPost(C('REQUEST').'met/card', $data);
      				$arr=json_decode($re,true);

    				if($arr['error']){
    					throw new Exception("no_data");//未获取到数据！请检查帐号
    				}else{
                $card = D($this->_card);

                $flag = $card->where('account="'.$arr['account'].'"')->find();
                if(!$flag){
                    $cardinfo = array(
                        'account' => $arr['account'],
                        'updated' => time(),
                    );
                    if($arr['metCard']){
                        $info = array(
                            'card_number' => $arr['metCard'],
                            );
                        $cardinfo = array_merge($cardinfo, $info);
                    }

                    $card->data($cardinfo)->add();
                }else{
                   $result = $card->field('integral, card_number')->where('account="'.$arr['account'].'"')->find();
                   $info = array(
                      'integral' => $result['integral'],
                      'metCard' => $result['card_number'],
                    );
                   $arr = array_merge($arr, $info);

                   $re = $this->getIntegralInfo($arr['account']);
                   $arr = array_merge($arr, $re);
                }

                /**
                 * 写入日志
                 */
                $log = A('Admin/LogEvent');
                $log->writeLog(array('action' => ACTION_NAME,'info' => '查询用户积分信息','fullaction' => __ACTION__,));
					      $this->ajaxreturn($arr);
				    }
    		}catch(Exception $e){
    			  $msg = $e->getMessage();

    			  $this->ajaxreturn($msg);
    		}
    	}
    }

    /**
     * [getIntegralInfo 获取用户积分消费详情]
     * @param  [string] $account [帐号]
     * @return [type]          [description]
     */
    public function getIntegralInfo($account){
        $info = D($this->_integral_info);
        $re = $info->field('if_id, use, get, desc, g_num, created')->where('account="'.$account.'"')->select();

        return $re;
    }


    /**
     * [bindCart 绑定积分卡]
     * @return [type] [description]
     */
    public function bindCard(){
    	$account = I('get.account', '', 'addslashes');
    	$card = I('get.integral', '', 'addslashes');

    	if($account && $card){
    		try{
    			$curl = A('SendRequest');
    			$data = array('type'=>2, 'account'=>$account, 'metCard'=>$card);
    			$re = $curl->curlPost(C('REQUEST').'met/card', $data);
    			$arr = json_decode($re, true);

    			if($arr['error']){
    				  throw new Exception("积分卡绑定失败！");
    			}else{
              $cardTable = D($this->_card);
              $metCard = array(
                'card_number' => $card, 
                );
              $cardTable->where('account="'.$account.'"')->save($metCard);

              /**
               * 写入日志
               */
              $log = A('Admin/LogEvent');
              $log->writeLog(array('action' => ACTION_NAME,'info' => '绑定用户积分卡','fullaction' => __ACTION__,));
    				  $this->ajaxreturn($arr);
    			}
    		}catch(Exception $e){
    			$msg = $e->getMessage();

    			$this->ajaxreturn($msg);
    		}
    	}
    }


    /**
     * [importProduct 从excel导入商品数据]
     * @return [type] [description]
     */
    public function importProduct(){
        $name = $_FILES['product']['name'];
        $tmpFile = $_FILES['product']['tmp_name'];
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        if($ext == 'xlsx'){
            try {
                $file = $this->upLoadFile();
                $excel = A('Excel'); 
                $data = $excel->getDataByExcel($file);
                $re = $this->putInTable($data);
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }

        $log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => '导入商品','fullaction' => __ACTION__,));
        $this->redirect('Integral/magange');
    }

    /**
     * [upLoadFile 图片上传]
     * @param  [array] $config [配置信息]
     * @return [type]         [description]
     */
    public function upLoadFile(){
        $config = array(
            'maxSize' => $this->_maxSize,
            'rootPath' => $this->_path,
            'saveName' => $this->_saveName,
            'exts' => $this->_exts,
            'autoSub' => $this->_autoSub,
            'subName' => $this->saveName,
            );

        $upload = new \Think\Upload($config);// 实例化上传类
        $file = $upload->upload();

        if(!$file) {
            // 上传错误提示错误信息
            $this->error($upload->getError());
        }else{
            $path = $this->_path.$file['product']['savename'];
            return $path;
        }
    }

	/**
	 * [log 写入日志信息]
	 * @param [string] $work [日志信息]
	 */
	private function log($work)
	{
		$log = A('Admin/LogEvent');
		$data = array(
			'action' => ACTION_NAME,
			'info' => session('user.username').':'.$work.'',
			'status' => '1',
			'fullaction' => __ACTION__,
			'error_message' => '',
		);
		$log->writeLog($data);
	}

    /**
     * [putInTable 将数据写入数据库]
     * @param  [array] $data [数据]
     * @return [type]       [description]
     */
    protected function putInTable($data){
        $product = D($this->_product);
        $re = $product->putInTable($data);

        return $re;
    }
}


?>