<?php
namespace Home\Controller;

class IndexController extends CommonController {
    private $account = 15882137306;

    /**
     * [index 商城首页]
     */
    public function index(){
        $goods = M('goods');
        $goods_info = $goods->join('LEFT JOIN goods_info ON goods_info.g_id=goods.g_id')->where(array('is_active'=>1))->limit(0,6)->order('goods.sort desc')->getField('goods.g_id,goods.sort,goods.integral,goods.title,goods_info.header_img');
        if(!empty($goods_info)){
            $goods_infoma = $this->parArr($goods_info,'integral');
            $this->assign('goods_info',$goods_infoma);
        }else{
            $this->assign('goods_info','');
        }
        //$this->account = $this->checkAccont();
        session('account',$this->account);
        $user_inte = $this->getUserInte($this->account);
        $this->assign('user_inte',$user_inte);
        $this->assign('account',$this->account);
        $this->display('Index/index');
    }

    /**
     * [getGoodsInfo 获取商品]
     * @return bool
     */
    public function getGoodsInfo()
    {
        $sort = I('post.sort','','int');
        if($sort < 6){
            echo 0;
            return false;
        }
        $goods = M('goods');
        $where['is_active'] = 1;
        $goods_info = $goods
            ->join('LEFT JOIN goods_info ON goods_info.g_id=goods.g_id')
            ->limit($sort,6)->order('goods.sort desc')
            ->where($where)
            ->getField('goods.g_id,goods.sort,goods.integral,goods.title,goods_info.header_img');

        if(!empty($goods_info))
        {
            $goods_infoma = $this->parArr($goods_info,'integral');
            $this->assign('goods_info',$goods_infoma);
            $this->assign('account',$this->account);
        }
        $this->display('goods_info');
    }

    /**
     * [rules 积分规则页面]
     * @return [type] [description]
     */
    public function rules()
    {
        $this->display('rules');
    }

    /**
     * [subject 专题页面]
     * @return [type] [description]
     */
    public function subject(){
        $s_id = I('get.new','','int');
        if(empty($s_id)){
            $this->assign('pageFirst',C('WEB_SITE'));
            $this->display('Empty/index');
            return false;
        }
        $where = array(
            'subject.s_id'=>$s_id,
            'subject.is_active'=>array('in','1')
        );
        $sub = M('subject');
        $sub_res = $sub->join('LEFT JOIN subject_content ON subject_content.s_id=subject.s_id')
            ->where($where)
            ->field('subject.title,subject.icon,subject.created,subject.modified,subject_content.type,subject_content.video,subject_content.u_info,subject_content.content')
            ->select();

        if(!empty($sub_res))
        {
            $sub_res[0]['content'] = htmlspecialchars_decode(htmlspecialchars_decode($sub_res[0]['content']));
            $this->assign('sub_res',$sub_res);
        }
    	$this->display('subject');
    }

    /*
     * [getPlayInstr 打榜说明]
     */
    public function playInstr(){
        $tmp_play = M('play_instruction');
        $res = $tmp_play->field('content')->find();
        if($res){
            $con = htmlspecialchars_decode(htmlspecialchars_decode($res['content']));
            $this->assign('con',$con);
        }
        $this->display('playInstr');
    }
}
?>
