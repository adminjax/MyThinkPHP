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
 * 验证码
 */
class VerifyController extends CommonController
{
	private $_fontSize = 12; //验证码字体大小
	private $_length = 3; //验证码长度
    private $_useNoise = false; //是否开启干扰点
    private $_string = '1234567890'; //验证字符

	/**
     * [getVerify 获取验证码]
     * @return [type] [description]
     */
    public function getVerify(){
        ob_end_clean();

    	$config = array(
    			'fontSize' => $this->_fontSize,
    			'length' => $this->_length,
                'useNoise' => $this->_useNoise,
                'codeSet' => $this->_string,
    		);
    	$Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    /**
	 * [checkVerify 检查验证码]
	 * @param  [string] $code [验证码]
	 * @param  string $id   [description]
	 * @return [bool]       [description]
	 */
	public function checkVerify($code, $id=''){
		$verify = new \Think\Verify();
		$result = $verify->check($code, $id);
		return $result;
	}
}


?>