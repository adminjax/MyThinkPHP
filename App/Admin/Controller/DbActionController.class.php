<?php
/**
 *
 * MET纪元后台音乐管理工具
 *
 * @Author: jax
 *
 */
namespace Admin\Controller;
/**
 * 数据备份和恢复
 * 数据库信息必须为进行了严格的转义数据，不能存在引起sql歧义符号！否则备份不能按每一行保存，导致数据恢复错误!
 */
class DbActionController extends CommonController {
  
    public $backup_path = "/var/www/html/met/Public/Backup/Db"; 
    public $msg = array();
    public $ds = '\n\r\n\r';
    public $dir = '';
    private $ext = '.sql';

    public function _initialize()
    {
        parent::checkLogin();
        $menu = parent::getMenu();

        $this->assign('menu', $menu);
    }

    /**
     * 
     */
    public function index(){
        $files = $this->getBackupFile($this->backup_path);
        $this->assign("files", $files);
        $this->display('index');
    }

    public function dbBackup(){
        $result = $this->checkBackupDir();
        if($result == true){
           $result = $this->checkBackupLock();
           if($result == true){
                $tables = $this->getTables();
                $header = $this->createFileHeader();

                if($this->mkdir()){
                    $this->mkfile($header);
                }

                foreach ($tables as $key => $value) {
                    $dbBack = $this->backupTable($value['name']);
                    $flag = $this->mkfile($dbBack);
                    if($flag != true){
                        break;
                    }
                }

                if($flag == true){
                    $this->ajaxreturn('true');
                }
           }else{
                $this->redirect($this->url, $result);
           }
        }else{
            $this->redirect($this->url, $result);
        }
    }

    /**
     * [checkBackupDir 检查备份目录是否存在（没有就创建），是否有读写权限]
     * @return [type] [description]
     */
    protected function checkBackupDir(){
        if(is_dir($this->backup_path)){
            if(is_readable($this->backup_path) && is_writable($this->backup_path)){
                return true;
            }else{
                return "目标目录没有读写权限，请手动赋予权限！";               
            }
        }else{
            $result = mkdir(iconv("UTF-8", "GBK", $this->backup_path),0777,true); 
            if($result == true){
                return true;
            }else{
                return "创建目标失败，请手动创建！";
            }
        }
    }

    /**
     * [checkBackupLock 检查是否有锁定文件.lock，没有创建锁定文件]
     * @return [type] [description]
     */
    protected function checkBackupLock(){
        $lock = $this->backup_path.'/backup.lock';
        if(!file_exists($lock)){
            $file = @fopen($lock,'w+');
            if($file){
                fwrite($file,time()); 
                fclose($file); 
                return true;
            }else{
                return "系统错误！";    
            }
        }else{
            return "其他管理员正在备份数据，请稍等！";
        }
    }

    /**
     * [createFileHeader 备份文件的头部信息]
     * @return [type] [description]
     */
    protected function createFileHeader(){
        $header = '';
        $header .= "-- MySQL Database Dump \n\r";
        $header .= "-- Created by DbAction class \n\r";
        $header .= "-- http://www.met.com \n\r";
        $header .= "-- \n\r";
        $header .= "-- 主机：".$_SERVER['SERVER_ADDR']." \n\r";
        $header .= "-- 生成日期：".date('Y')." 年 ".date('m')." 月 ".date('d')." 日 ".date('H:i')." \n\r";
        $header .= "-- MySQL版本号：".mysql_get_server_info()." \n\r";
        $header .= "-- PHP版本号：".phpversion()." \n\r";
        $header .= "\n\r";
        $header .= "-- ----------------------------------------\n\r";
        $header .= "-- 数据库：`".C('DB_NAME')."` \n\r";
        $header .= "-- ----------------------------------------\n\r";
        $header .= "\n\r\n\r";

        return $header;
    }

    /**
     * [getTables 获取数据库中所有表]
     * @return [type] [description]
     */
    protected function getTables(){
        $m = M();
        $name = explode(',', C('DB_NAME'));
        $result = $m->query("show table status from " . $name[0]);

        return $result;
    }

    /**
     * [backupTable 独立备份每一张表的数据和结构]
     * @param string $table 表名
     * @return [type]        [description]
     */
    protected function backupTable($table){
        $structure = $this->getTableStructure($table);
        $data = $this->getTableData($table);

        return $structure."\n\r".$data."\n\r";
    }

    /**
     * [getTableStructure 获取表结构]
     * @param string $table 表名
     * @return [type] [description]
     */
    protected function getTableStructure($table){
        $tableHeader = "-- ------------------------------------\n";
        $tableHeader .= "-- Table structure for $table \n";
        $tableHeader .= "-- ------------------------------------\n";
        $tableHeader .= "DROP TABLE IF EXISTS `$table`;\n";

        $table = M()->query("SHOW CREATE TABLE `$table` ");
        $tableSql = $table[0]['create table'];
        $tableSql = $tableHeader.$tableSql.";\n";

        return $tableSql;
    }

    /**
     * [getTableData 获取表数据]
     * @param  [string] $table [表名]
     * @return [type]        [description]
     */
    protected function getTableData($table){
        $tableDatas = M()->query("SELECT * FROM $table");
        $dataSql = "-- ----------------------------\n";
        $dataSql .= "-- Records of acl\n";
        $dataSql .= "-- ----------------------------\n";
        $dataSql .= "LOCK TABLES `$table` WRITE;\n";

        foreach ($tableDatas as $key => $value) {
            foreach (array_values($value) as $k => $v) {
                $data[$k] = "'".$v."'";
            }

            $str = implode(',', $data);
            $dataSql .= "INSERT INTO `$table` VALUES ($str);\n";
        }        
        $dataSql .= "UNLOCK TABLES;\n";

        return $dataSql;
    }

    /**
     * [mkdir 创建当前备份文件夹]
     * @return [type] [description]
     */
    protected function mkdir(){
        $name = date('Ymd', time());

        if(is_dir($this->backup_path.'/'.$name)){
            if(is_readable($this->backup_path.'/'.$name) && is_writable($this->backup_path.'/'.$name)){
                $this->dir = $this->backup_path.'/'.$name;
                return true;
            }else{
                return "目标目录没有读写权限，请手动赋予权限！";
            }
        }else{
            $result = mkdir(iconv("UTF-8", "GBK", $this->backup_path.'/'.$name),0777,true); 
            if($result == true){
                $this->dir = $this->backup_path.'/'.$name;
                return true;
            }else{
                return "创建目标失败，请手动创建！";
            }
        }
    }

    /**
     * [mkfile 创建当前备份文件]
     * @return [type] [description]
     */
    protected function mkfile($content){
        $name = date('YmdHis', time()).$this->ext;
        $sqlFile = $this->dir.'/'.$name;
        if(!file_exists($sqlFile)){
            $file = @fopen($sqlFile, 'w+');
            if($file){
                fwrite($file, $content); 
                fclose($file); 
                return true;
            }else{
                return "系统错误！";
            }
        }else{
            $file = @fopen($sqlFile, 'a');
            if($file){
                fwrite($file, $content); 
                fclose($file); 
                return true;
            }else{
                return "系统错误！";    
            }   
        }
    }

    /**
     * [clearLog 日志清理]
     * @return [type] [description]
     */
    public function clearLog(){
        $log = M('log_event');
        $time = time() - (60*60*24*30); //清理30天以前的日志

        $writelog = A('Admin/LogEvent');
        $writelog->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':日志清理','fullaction' => __ACTION__,));

        $flag = $log->where('time<'.$time)->delete();
        if($flag){
            $this->ajaxreturn('true');
        }else{
            $this->ajaxreturn('false');
        }
    }

    /**
     * [repairTable 修复表];
     * @return [type] [description]
     */
    public function repairTable(){
        $tables = $this->getTables();

        $log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':修复表','fullaction' => __ACTION__,));


        if(is_array($tables)){
            foreach ($tables as $key => $value) {
                $result = M()->query("REPAIR TABLE `{$value['name']}`");
                if(!$result){
                    $this->ajaxreturn("false");
                    break;
                }
            }

            if($result){
                $this->ajaxreturn("true");
            }
        }
    }

    /**
     * [optimize 优化表]
     * @return [type] [description]
     */
    public function optimize(){
        $tables = $this->getTables();

        $log = A('Admin/LogEvent');
        $log->writeLog(array('action' => ACTION_NAME,'info' => session('user.username').':优化表','fullaction' => __ACTION__,));

        if(is_array($tables)){
            foreach ($tables as $key => $value) {
                $result = M(0)->query("OPTIMIZE TABLE `{$value['name']}`");
                if(!$result){
                    $this->ajaxreturn("false");
                    break;
                }
            }

            if($result){
                $this->ajaxreturn("true");
            }
        }
    }

    /**
     * [restoreData 还原备份]
     * @return [type] [description]
     */
    public function restoreData(){
        $file = I('get.data', '', 'addslashes');
        $path = $this->backup_path.'/'.$file;
        
        $result = M()->query($path);
        if($result){
            $this->ajaxreturn("true");
        }else{
            $this->ajaxreturn("false");
        }
    }

    /**
     * [getBackupFile 获取所有备份文件]
     * @param  [type] $dir [description]
     * @return [type]      [description]
     */
    protected function getBackupFile($dir){
        $files = array();
        if(is_dir($dir)){
            if($handle = opendir($dir)){
                while(($file = readdir($handle)) !== false){
                    if($file != "." && $file != ".."){
                        if(is_dir($dir."/".$file)){
                            $files[$file] = $this->getBackupFile($dir."/".$file);
                        }else{
                            $files[] = $file;
                        }
                    }
                }
                closedir($handle);
                return $files;
            }
        }
    }

}