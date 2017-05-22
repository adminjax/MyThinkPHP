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
 * 票务处理类
 */
class TicketController extends CommonController
{
	private $_ticket = 'ticket';
	private $num = 10;

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
            
            echo json_encode(array("error"=>"0","pic"=>$path,"name"=>'0000000'));
        }
    }

	/**
	 * [addTicket 添加票务]
	 */
	public function addTicket(){
        $data = $this->getTicketByCurl();

        $delTicket = $this->button('delTicket','删除','9_1_1');
        $editTicket = $this->button('editTicket','编辑','9_1_2');
        $saveTicket = $this->button('save','保存并提交至审核','9_1_3');
        $this->assign('list', $data);
        $this->assign('delTicket', $delTicket);
        $this->assign('editTicket', $editTicket);
        $this->assign('save', $saveTicket);
		$this->display('addTicket');
	}

    /**
     * [getTicketByCurl 获取远程票务数据]
     * @return [type] [description]
     */
    protected function getTicketByCurl(){
        try {
            $curl = A('SendRequest');
            $data = array('type'=>1);
            $resulte = $curl->curlPost(C('REQUEST').'met/ticket', $data);
            $resulte = json_decode($resulte, true);

            return $resulte['array'];
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }

    /**
     * [saveTicket 保存，修改票据]
     * @return [type] [description]
     */
    public function saveTicket(){
        $data = array(
            'reid' => I('post.id', '', 'addslashes'),
            'img' => I('post.imgl', '', 'addslashes'),
            'title' => I('post.ticket-title', '', 'addslashes'),
            'startTime' => strtotime(I('post.begin', '', 'addslashes')), 
            'exp_date' => strtotime(I('post.end', '', 'addslashes')),
            'price' => I('post.price', '', 'float'),
            'content' => I('post.ticket-content', '', 'addslashes'),
            'count' => I('post.count', '', 'addslashes'),
            'score' => I('post.score', '', 'addslashes'),
            );

        $ticket = D($this->_ticket);

        if(array_filter($data)){
            $re = $ticket->saveTicket($data);
            
            if($re > 0){
                //session('msg', '添加票根成功！');
                $this->redirect('Ticket/addTicket', array('msg' => '操作成功！等待审核'));
            }else{
                //session('error', '添加票根失败，请重试！');
                $this->redirect('Ticket/addTicket');
            }    
        }else{
            $this->redirect('Ticket/addTicket');
        }
    }

    /**
     * [ticketAudit 票务审核入口]
     * @return [type] [description]
     */
    public function ticketAudit($p=1){
        $p = I('get.p') ? I('get.p') : $p;
        $data = $this->getTicket($p);

        $tic_page = $this->pageTools($data['count'],$p,$this->num);
        $reject = $this->button('reject','拒绝','9_2_1');
        $adopt = $this->button('adopts','通过','9_2_2');
        $this->assign('tic_page',$tic_page);
        $this->assign('data', $data['data']);
        $this->assign('reject', $reject);
        $this->assign('adopt', $adopt);
        $this->display('ticketAudit');
    }

    /**
     * [getTicket 获取操作票务]
     * @return [type] [description]
     */
    protected function getTicket($p){
        $ticket = D($this->_ticket);

        return $ticket->getTicket($p,$this->num);
    }

    /**
     * [refuse 拒绝]
     * @return [type] [description]
     */
    public function refuse(){
        $ids = I('get.id', '', 'addslashes');
        $id = array_filter(explode(',', $ids));

        try {
            $ticket = D($this->_ticket);

            foreach ($id as $k => $v) {
                $type = explode(':', $v);
                switch ($type['1']) {
                    case '1':
                        $data = $ticket->delTicketById($type[0]);
                        break;
                    case '2':
                        $data = $ticket->delTmpTicketById($type[0]);
                        break;
                    case '3':
                        $data = $ticket->nodel($type[0]);
                        break;
                }
                if($data > 0){
                    $re = 0;
                }else{
                    break;
                }

                $this->ajaxreturn($re);
            }
            /**
             * 写入日志
             */
            $log = A('Admin/LogEvent');
            $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':票据拒绝通过审核','fullaction' => __ACTION__,));
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $this->ajaxreturn($msg);
        }
    }

    /**
     * [adopt 通过操作]
     * @return [type] [description]
     */
    public function adopt(){
        $ids = I('get.id', '', 'addslashes');
        $id = array_filter(explode(',', $ids));

        try { 
            $curl = A('SendRequest');
            $ticket = D($this->_ticket);

            foreach ($id as $k => $v) {
                $type = explode(':', $v);
                switch ($type[1]) {
                    case '1':
                        $result = $ticket->getTicketById($type[0]);
                        $data = array(
                            'type' => 2,
                            'name' => $result['title'],
                            'style' => $result['img'],
                            'desc' => $result['content'],
                            'rmb' => (int)$result['price'],
                            'startTime' => $result['starttime'],
                            'endTime' => $result['exp_date'],
                            'count' => $result['count'],
                            'score' => $result['score'],
                        );

                        $re = $curl->curlPost(C('REQUEST').'met/ticket', $data);
                        $arr=json_decode($re,true);
                        if($arr['sid']){
                            $ticket->saveId($type[0], $arr['sid']);
                            $re = 0;
                        }
                        /**
                         * 写入日志
                         */
                        $log = A('Admin/LogEvent');
                        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':票据添加通过审核','fullaction' => __ACTION__,));
                        break;
                    case '2':
                        $result = $ticket->getTmpTicketById($type[0]);
                        $data = array(
                            'type' => 4,
                            'sid' => $result['reid'],
                            'name' => $result['title'],
                            'style' => $result['img'],
                            'desc' => $result['content'],
                            'rmb' => (int)$result['price'],
                            'startTime' => $result['starttime'],
                            'endTime' => $result['exp_date'],
                            'count' => $result['count'],
                            'score' => $result['score'],
                        );
                       
                        $re = $curl->curlPost(C('REQUEST').'met/ticket', $data);
                        $arr=json_decode($re,true);
                        if($arr['sid']){
                            $ticket->updateTicket($data);
                            $ticket->delTmpTicket($arr['sid']);
                            $re = 0;
                        }

                        $log = A('Admin/LogEvent');
                        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':票据修改通过审核','fullaction' => __ACTION__,));
                        break;
                    case '3':
                        $result = $ticket->getTicketById($type[0]);
                        $data = array(
                            'type' => 3,
                            'sid' => $result['reid'], 
                        );

                        $re = $curl->curlPost(C('REQUEST').'met/ticket', $data);
                        $arr=json_decode($re,true);
                        if($arr['result'] == true){
                            //$ticket->delTmpTicket($arr['sid']);
                            $ticket->delelteTicket($result['reid']);
                            $re = 0;
                        }

                        $log = A('Admin/LogEvent');
                        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':票据删除通过审核','fullaction' => __ACTION__,));
                        break;
                }
                if($re != 0){
                    break;
                }
            }

            $this->ajaxreturn($re);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $this->ajaxreturn($msg);
        }
    }

    /**
     * [delTicket 删除票]
     * @return [type] [description]
     */
    public function delTicket(){
        $id = I('get.id', '', 'int');

        $ticket = D($this->_ticket);
        $re = $ticket->delTicket($id);
        /**
         * 写入日志
         */
        $log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':删除票据','fullaction' => __ACTION__,));
        $this->ajaxreturn($re);
    }

    /**
     * [getTicketById 获取需要审核数据]
     * @return [type] [description]
     */
    public function getTicketById(){
        $id = I('get.id', '', 'addslashes');
        $data = explode(':', $id);
        $ticket = D($this->_ticket);
        
        switch ($data[1]) {
            case '1':
                $data = $ticket->getTicketData($data[0]);
                break;
            case '2':
                $data = $ticket->getTmpTicketData($data[0]);
                break;
            case '3':
                $data = $ticket->getTicketData($data[0]);
                break;
        }

        $this->ajaxreturn($data);
    }

    /**
     * [getTicketTid 修改票务获得数据]
     * @return [type] [description]
     */
    public function getTicketTid(){
        $reid = I('get.id', '', 'addslashes');
        
        if(!empty($reid)){
            $ticket = D($this->_ticket);
            $data = $ticket->getData($reid);

            $this->ajaxreturn($data);
        }
    }
}

?>