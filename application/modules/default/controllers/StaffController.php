<?php
class StaffController extends Controller {
	const SESSION_KEY_PAGE = 'page';
	const SESSION_KEY_SEARCH = 'search';
	const SESSION_KEY_RETURN_DETAIL = 'return';
	const URL_ROOT_UPLOAD = '/var/parameterSheet';
	
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
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
		$this->_forward('/list');
	}
	
	public function listAction()
	{
		$this->view->role = Auth_Info::getUser()->user_type;
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		
		if($this->getRequest()->isGet()&&!isset($this->_input->page)){
			$this->session->removeData(self::SESSION_KEY_SEARCH);
		}
		
		$this->session->removeData(self::SESSION_KEY_PAGE);
		$this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);
		
		$page = null;
		if ($this->getRequest()->isPost()) {
			$where = $this->_input->getEscaped();
			if(isset($this->getRequest()->name) && !isset($where['name'])){
				$where = array();
			}
		} else {
			$where = $this->session->getData(self::SESSION_KEY_SEARCH);
			if (is_null($where)) {
				$where = array();
			}
			$page = isset($this->_input->page) ? $this->_input->page : $this->session->getData(self::SESSION_KEY_PAGE);
		}
		
		$this->getRequest()->setParams($where);
		$this->session->setModuleScope(self::SESSION_KEY_SEARCH, $where);
		$this->session->setModuleScope(self::SESSION_KEY_PAGE, $page);
		
// 		$select = TblStaffs::getInstance()->getStaff();
		
		$select = TblStaffs::getInstance()->getDepartment($where);
		
		$this->view->max_display_char = Zynas_Registry::getConfig()->constants->max_display_char;
		$this->view->paginator = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
	}
	
	public function listOneAction()
	{
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number='.'Start action');
		
		$page = null;
		
		$select = TblStaffs::getInstance()->getInfo(array());
		
		$this->view->max_display_char = Zynas_Registry::getConfig()->constants->max_display_char;
		$this->view->paginator = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);

	
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number='.'End action');
		
	}
	
	public function addAction(){
		
		
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		$this->view->token = Csrf::getToken();
		
		$dep = TblStaffs::getInstance()->getTestJoin();
		
		$arr = array();
		
		foreach ($dep as $k=>$val) {
			$arr[$val->department_id] = $val->department_name;
		}
		
		$this->view->option = $arr;
	
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
	
		
	}
	
	public function confirmAddAction() {
		
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		if(!Csrf::checkToken($this->_input->token)) {
			FlashMessenger::addError(E061V);
			$this->_redirect('/staff/list');
		}
	
		$db = Zynas_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		try {
			$row = TblStaffs::getInstance()->createRow();
			$row->create_date = Zynas_Date::dbDatetime();
			$row ->create_user =  Auth_Info::getUser()->user_id;
			$row->name = $this->_input->name;
			$row->email = $this->_input->email;
			$row->position_id = $this->_input->sbt_pos;
			$row->department_id = $this->_input->dep;
			
			$row->save();
			
	
			FlashMessenger::addSuccess(E065V);
			$db->commit();
		} catch (Exception $e) {
			$db->rollBack();
			FlashMessenger::addError($e->getMessage());
		}
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
	
		$this->_redirect('/staff/list');
	}
	
	public function positionAction()
	{

		$id = $this->_input->dep_id;
// 				echo $id; exit;
		$str ='';
		$res = TblStaffs::getInstance()->getPositionById($id);
		
// 		$result[] = array_values($res);
// 		$result[] = array($res['id'], $res['pos_name']);
        $arr = array();
        foreach ($res as $k =>$val)
        {
        	$arr[$val->position_id] = $val->pos_name;
        }
		 echo json_encode($arr); exit;
			
// 		foreach($res as $row)
// 		{
// 			$str = $str."$row[pos_name]".",";
// // 			$result = $result."$row[id]".",";
// 		}
		
// 		$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
		
// 		echo json_encode($str);exit;
	}
	
	private function setInfoData($row){
			
		$row->name = $this->_input->name;
		$row->position = $this->_input->sbt_pos;
		$row->email = $this->_input->email;
		$row->department_name = $this->_input->option;
		return $row;
	}
	
	public function editAction(){
		
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
	
		$this->view->token = Csrf::getToken();
		if ($this->getRequest()->isGet()){
			$id = $this->_input->staff_id;
// 			$this->view->staff_id = $id;
// 			echo $id; exit;
			
// 			$row = TblStaffs::getInstance()->getEntryById($id);
			$row = TblStaffs::getInstance()->getStaffById($id);
// 					echo "<pre>";
// 					print_r($row);exit;
// 					echo "</pre>";
			$this->getRequest()->setParams($row->toArray());
			
			$dep = TblStaffs::getInstance()->getTestJoin();
			
			$arr = array();
			
			foreach ($dep as $k=>$val) {
				$arr[$val->department_id] = $val->department_name;
			}
			
			$this->view->option = $arr;
		
		}
		
		
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
	
	}
	

	public function confirmEditAction() {
		
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
				if(!Csrf::checkToken($this->_input->token)) {
					FlashMessenger::addError(E061V);
					$this->_redirect('/staff/list');
				}
		
		$id = $this->_input->staff_id;
		
		
		$infoRow = TblStaffs::getInstance()->getTestJoinById($id);
		
		$db = Zynas_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		try {
			
		    $infoRow->name = $this->_input->name;
		    $infoRow->position_id = $this->_input->sbt_pos;
		    $infoRow->email = $this->_input->email;
		    $infoRow->department_id = $this->_input->option;
			$infoRow->update_date = Zynas_Date::dbDatetime();
			$infoRow->update_user = Auth_Info::getUser()->user_id;
			
			$infoRow->save();
			FlashMessenger::addSuccess(E074V);
			$db->commit();
			
		} catch (Exception $e) {
			$db->rollBack();
			FlashMessenger::addError($e->getMessage());
		}
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
		return $this->_redirect('/staff/list');
	}
	
	
	
	
	public function reportAction() {
		

        //get instance.
		$data = TblStaffs::getInstance()->getStaffInfo();
// 		echo "<pre>";
// 		print_r($data);
// 		echo "</pre>";
// 		exit;
		
		//create file name
		$filename = APPLICATION_PATH.self::URL_ROOT_UPLOAD.'_'.mktime().'.xls';
		
		//return absolute pathname
		$realPath = realpath($filename);
		

		if ( false === $realPath )
		{
			touch( $filename );
			chmod( $filename, 0777 );
		}
		
		//write file
		$filename = realpath( $filename );
		$handle = fopen( $filename, "w" );
		$finalData = array();
		
		foreach ( $data as $row )
		{
			$finalData[] = array(
					utf8_decode( $row["name"] ), // For chars with accents.
					utf8_decode( $row["email"] ),
					utf8_decode( $row["position"] ),
					utf8_decode( $row["create_date"] ),
			);
		}
		
		foreach ( $finalData as $finalRow )
		{
			//fomat lines as CSV and write to the poiter
			 fputcsv( $handle, $finalRow, "\t" );
		}
		
		
		fclose( $handle );
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$this->getResponse()->setRawHeader( "Content-Type: application/vnd.ms-excel; charset=UTF-8" )
		->setRawHeader( "Content-Disposition: attachment; filename=excel.xls" )
		->setRawHeader( "Content-Transfer-Encoding: binary" )
		->setRawHeader( "Expires: 0" )
		->setRawHeader( "Cache-Control: must-revalidate, post-check=0, pre-check=0" )
		->setRawHeader( "Pragma: public" )
		->setRawHeader( "Content-Length: " . filesize( $filename ) )
		->sendResponse();
		
		readfile( $filename ); exit();
		
}
	
    public function importAction()
     {
       
     	if($this->getRequest()->isPost())
     	{
//      		echo "<pre>";
//      		print_r($_FILES['filename']);
//      		echo "</pre>";exit;
     		
     		//filename import
     		$fname = $_FILES['filename']['name'];
     		
     		$chk_ext = explode(".",$fname);
     		
     		if(strtolower($chk_ext[1]) == "csv")
     		{
     	        //filename temporary copy of file stored on server
     			$filename = $_FILES['filename']['tmp_name'];
     			
     			$handle = fopen($filename, "r");
     			
     			
     		
     		   while (($data = fgetcsv($handle)) !== FALSE)
     			{   
                     
     				$db = Zynas_Db_Table::getDefaultAdapter();
     				$db->beginTransaction();
     				try {
     					$row = TblStaffs::getInstance()->createRow();
     					$row->create_date = $data[3];
     					$row ->create_user =  Auth_Info::getUser()->user_id;
     					$row->name = $data[0];
     					$row->email =$data[1];
     					$row->position = $data[2];
     					$row->save();
     					
     					FlashMessenger::addSuccess(E065V);
     					$db->commit();
     				}
     				catch (Exception $e) {
     					$db->rollBack();
     					FlashMessenger::addError($e->getMessage());
     				}
     			}
     			
     			fclose($handle);
     			echo "Successfully Imported";
     			$this->redirect('/staff/list');
     		}
     	}
// //     	$filename = APPLICATION_PATH.self::URL_ROOT_UPLOAD.'/'.'*'.'.xls';
//     	$filename = $_FILES[APPLICATION_PATH.self::URL_ROOT_UPLOAD];
//     	$columns = "'name','email','position','create_date'";
//     	$handle = fopen($filename, "r");
    	
//     	while(($data=fgetcsv($handle, 1000, ","))!=FALSE)
//     	{
//     		foreach ($data as $v)
//     		{
//     			$insertValues = "'".addcslashes(trim($v))."'";
    			
//     		}
    		
//     		$value = implode(',', $insertValues);
    		
//     		$sql = "insert into 'tbl_staff'($columns) value ($value)";
//     		mysql_query($sql) or die ('SQL ERROR:'.mysql_error());
//     	}
    	
//     	fclose($handle);
    	
    }

    
	
	public function deleteAction() {
		
// 		$ids = $this->_input->id;
		
// 		var_dump($ids);
		
// 		$this->_helper->viewRenderer->setNoRender();
		
		
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
	
		
		if (!$this->getRequest()->isPost()){
			FlashMessenger::addError(E062V);
			$this->_redirect('/staff/list');
		}
	
		$idx = $this->_input->id;
		
// 		var_dump($idx);
// 		exit();
		
		
		foreach ($idx as $id)
		{
// 			$row = TblStaffs::getInstance()->getEntryById($id);
            $row = TblStaffs::getInstance()->getStaffById($id);
			
			if (is_Null($row)) {
				// error
				$this -> handleErrorDelete();
			}
			$db = Zynas_Db_Table::getDefaultAdapter();
			$db->beginTransaction();
			try {
				$row->delete_flg = MInformations::DELETE_FLG_ON;
				$row->delete_date = Zynas_Date::dbDatetime();
				$row->delete_flg_update_user= Auth_Info::getUser()->user_id;
				$row->save();
				$db->commit();
				FlashMessenger::addSuccess(I002V);
			
			} catch (Exception $e) {
				$db->rollBack();
				FlashMessenger::addError($e->getMessage());
			}
			
		}
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
		$this->_redirect('/staff/list');
	}
	
	public function testAction() {
		$res = array("ok" => "OK");

		echo json_encode($res);
	}
	
}
