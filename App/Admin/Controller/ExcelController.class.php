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
 * 权限管理类
 */
class ExcelController extends CommonController
{
	protected $phpExcel;
	protected $phpExcelRead;
	protected $sheet = 0;
	protected $excel;

	/**
	 * [_initialize 初始化]
	 * @return [type] [description]
	 */
	public function _initialize(){
		vendor('PHPExcel.PHPExcel');
		$this->excel = new \PHPExcel();
    }

    /**
     * [getDataByExcel 获取excel中的数据]
     * @param  [string] $file [文件路径]
     * @return [type]       [description]
     */
	public function getDataByExcel($file){
		if(!file_exists($file)){
			return 1; //没有文件
		}

		$this->phpExcelRead = new \PHPExcel_Reader_Excel2007();
		if(!$this->phpExcelRead->canRead($file)){
			$this->phpExcelRead = new \PHPExcel_Reader_Excel5();
			if(!$this->phpExcelRead->canRead($file)){
				return 2; //文件没有权限
			}
		}

		$excel = $this->phpExcelRead->load($file);
		$currentSheet = $excel->getSheet($this->sheet);	
		$allColumn = $currentSheet->getHighestColumn();
		$allRow = $currentSheet->getHighestRow();

		$data = array();

		for ($rowIndex = 1; $rowIndex <= $allRow ; $rowIndex++) { 
			for ($colIndex = 'A'; $colIndex <= $allColumn ; $colIndex++) {
				$addr = $colIndex.$rowIndex;
				$cell = $currentSheet->getCell($addr)->getValue();

				if($cell instanceof PHPExcel_RichText){
					$cell = $cell->__toString();
				}

				$data[$rowIndex][$colIndex] = $cell;
			}
		}

		return $data;
	}


	public function exportExcel(){
		return $this->excel;

		$this->excel->getProperties()->setCreator("met")  
            ->setLastModifiedBy("met")  
            ->setTitle("team enroll list")  
            ->setSubject("team enroll list")  
            ->setDescription("team enroll list.")  
            ->setKeywords("team enroll list")  
            ->setCategory("team enroll list");  

        // 设置水平居中    
	   	$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $this->excel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $this->excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $this->excel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $this->excel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
	    $this->excel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

        //  合并  
    	$this->excel->getActiveSheet()->mergeCells('A1:E1'); 
    	$this->excel->setActiveSheetIndex(0)  
            ->setCellValue('A1', '报名列表')  
            ->setCellValue('A2', 'ID')  
            ->setCellValue('B2', '报名时间')  
            ->setCellValue('C2', '队名')  
            ->setCellValue('D2', '联系人')
            ->setCellValue('E2', '联系方式');

        // 内容  
        $this->excel->getActiveSheet(0)->setCellValue('A3', 'aaaaaaaaaaaa');  
        $this->excel->getActiveSheet(0)->setCellValue('B3', 'bbbbbbbbbbbb');  
        $this->excel->getActiveSheet(0)->setCellValue('C3', 'cccccccccccc');  
        $this->excel->getActiveSheet(0)->setCellValue('D3', 'dddddddddddd');  
        $this->excel->getActiveSheet(0)->setCellValue('E3', 'eeeeeeeeeeee');  
        $this->excel->getActiveSheet()->getStyle('A3:E3')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);  
  
	    // Rename sheet    
	    $this->excel->getActiveSheet()->setTitle('报名列表');  
  
	    // Set active sheet index to the first sheet, so Excel opens this as the first sheet    
	    $this->excel->setActiveSheetIndex(0);  
  
	    // 输出  
	    header('Content-Type: application/vnd.ms-excel');  
	    header('Content-Disposition: attachment;filename="111.xls"');  
	    header('Cache-Control: max-age=0');  
	  
	    $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
	    $objWriter->save('php://output');  
	}

}
?>