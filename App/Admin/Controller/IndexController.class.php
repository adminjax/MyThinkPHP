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
 * 网站入口
 */
class IndexController extends CommonController
{ 
    /**
     * [index 后台登录入口]
     * @return [type] [description]
     */
    public function index(){

        if(session('error')){
            $this->assign('msg', session('error'));
        }

        $this->display('index');
    }
}
?>