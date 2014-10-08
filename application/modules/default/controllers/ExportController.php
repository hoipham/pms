<?php
class ExportController extends Zend_Controller_Action {
	const URL_ROOT_UPLOAD = '/var/Download/';
	
	public function init(){
		parent::init();
		
		$excelConfig = array(
				'excel'=> array(
						'suffix'=>'excel', 
						'header'=>array('Content-type' =>'application/vnd.ms-excel'))
		);
		
		//init the context switch action helper
		$contextSwitch = $this->_helper->contextSwitch();
		
		//add new context
		$contextSwitch->setContexts($excelConfig);
		
		//set new context to the reports action
		$contextSwitch->addActionContext('report', 'excel');
		
		//Init the action helper
		$contextSwitch->initContext();
	}
	
	public function indexAction() {
		
	}
	
	
	public function newAction() {
		
	}
	
	public function reportAction() {
		$id = isset($this->_input->id)? $this->_input->id :'';
		
			
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
// 		$conn = mysql_connect("localhost","root","");
//         $db = mysql_select_db("database",$conn);
    
//         $sql = "SELECT * FROM table";
//         $rec = mysql_query($sql) or die (mysql_error());
           
         $field = TblStaffs::getInstance()->getStaff();
         $num_fields = count($field);
         
// 		 $db = Zynas_Db_Table::getDefaultAdapter();
// 		 $db->beginTransaction();
		 
    
//         $num_fields = mysql_num_fields($rec);
    
//     for($i = 0; $i < $num_fields; $i++ )
//     {
//         $header .= mysql_field_name($rec,$i)."\\t";
//     }
    
//     while($row = mysql_fetch_row($rec))
//     {
//         $line = '';
//         foreach($row as $value)
//         {                                            
//             if((!isset($value)) || ($value == ""))
//             {
//                 $value = "\\t";
//             }
//             else
//             {
//                 $value = str_replace( '"' , '""' , $value );
//                 $value = '"' . $value . '"' . "\\t";
//             }
//             $line .= $value;
//         }
//         $data .= trim( $line ) . "\\n";
//     }
    
//     $data = str_replace("\\r" , "" , $data);
    
//     if ($data == "")
//     {
//         $data = "\\n No Record Found!\n";                        
//     }
    
//     header("Content-type: application/octet-stream");
//     header("Content-Disposition: attachment; filename=reports.xls");
//     header("Pragma: no-cache");
//     header("Expires: 0");
//     print "$header\\n$data";
	}
	
}