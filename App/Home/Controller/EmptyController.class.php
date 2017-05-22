<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/17
 * Time: 14:36
 */

namespace Home\Controller;


class EmptyController extends CommonController
{
    public function index(){
        $this->assign('pageFirst',C('WEB_SITE'));
        $this->display('Empty/index');
    }
}