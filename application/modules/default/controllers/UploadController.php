<?php
class UploadController extends Controller {
	
	const URL_ROOT_UPLOAD = '/var/parameterSheet/';
	
	public function init(){
		
		
	}
	
	public function indexAction(){
		//action body
		$form = new Application_Form_Upload();
		$this->view->assign('form', $form);
	}
	
	private function getFileExtension($filename){
		$fext_tmp = explode('.', $filename);
		return $fext_tmp[(count($fext_tmp)-1)];
	}
	
	public function requestAction()
	{
	
	}
	

	
	public function uploadAction(){
		$des_dir = APPLICATION_PATH . "/var/";
		
		
		$fileName = $_FILES['filename']['name'];
		
// 		echo "<pre>";
// 		print_r($content );
// 		echo "</pre>";exit;
		
		$file = substr($fileName,0,-4);
		$file_ext = substr($fileName, strpos($fileName, 'jpg'),3);
		
// 		echo "<pre>";
// 		print_r($fileInfo);
// 		echo "</pre>";exit;
		
		//upload file on server
		$upload = new Zend_File_Transfer_Adapter_Http();
		$upload->setDestination($des_dir);
		$upload->addFilter(new Zend_Filter_File_Rename(array(
				'target' =>  $des_dir."parameterSheet/" . $file.'_'.mktime().'.'.$file_ext,
				'overwrite' => true)
		));
		
		
		$upload->receive();
		
		$this->_helper->viewRenderer->setNoRender(true);
		
		$db = Zynas_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		try {
			//create row
			$row = MImages::getInstance()->createRow();
			
			//set row info
			$row->create_date = Zynas_Date::dbDatetime();
			$row ->create_user =  Auth_Info::getUser()->user_id;
            $row->img_update = Zynas_Date::dbDatetime();
			$row->path = $file.'_'.mktime().'.'.$file_ext;
			
			$row->save();
		
			FlashMessenger::addSuccess(E065V);
			$db->commit();
		} catch (Exception $e) {
			$db->rollBack();
			FlashMessenger::addError($e->getMessage());
		}
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
		
		$this->_redirect('/download/list');

	}		
		
		
}	
		
// 		$file = $upload->getFileInfo();
		
		
		
// 		//debug
// 		echo '<hr/>
// 				  <pre>';
// 		print_r($file);
// 		echo '</pre>
// 				  <hr/>';
// 		try
// 		{
// 			$upload->receive();
// 		}
// 		catch (Zend_File_Transfer_Exception $e)
// 		{
// 			$e->getMessage();
// 			exit;
// 		}
		
// 		$mime_type = $upload->getMimeType('doc_path');
// 		$fname = $upload->getFileName('doc_path');
// 		$size = $upload->getFileSize('doc_path');
// 		$file_ext = $this->getFileExtension($fname);
// 		$new_file = $des_dir.md5(mktime()).'.'.$file_ext;
		
// 		$filterFileRename = new Zend_Filter_File_Rename(array('target'=>$new_file, 'overwrite'=>true));
		
// 		$filterFileRename->filter($fname);
		
// 		if(file_exists($new_file))
// 		{
// 			$request = $this->getRequest();
// 			$caption = $request->getParam('caption');
				
// 			$html = 'Orig Filename: '.$fname.'<br />';
// 			$html .= 'New Filename: '.$new_file.'<br />';
// 			$html .= 'File Size: '.$size.'<br />';
// 			$html .= 'Mime Type: '.$mime_type.'<br />';
// 			$html .= 'Caption: '.$caption.'<br />';
				
// 		}
// 		else {
// 			$html = "Unable to upload the file";
// 		}
		
// 		$this->view->assign('up_result',$html);

	
	