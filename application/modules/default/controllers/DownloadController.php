<?php
class DownloadController extends Controller {
	const SESSION_KEY_PAGE = 'page';
	const SESSION_KEY_SEARCH = 'search';
	const SESSION_KEY_RETURN_DETAIL = 'return';
	const SESSION_KEY_DETAIL = 'detail';
	const URL_ROOT_UPLOAD = '/var/parameterSheet';
	const SEPARATOR = '/';
	
	public function indexAction() {
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
		$this->_forward('/list');
	}

	public function listAction() {
	
		//$this->view->role = Auth_Info::getUser()->user_type;
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		
		if($this->getRequest()->isGet()&&!isset($this->_input->page)){
			$this->session->removeData(self::SESSION_KEY_SEARCH);
		}
		
		$this->session->removeData(self::SESSION_KEY_PAGE);
		$this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);
	
		$page = null;
		if ($this->getRequest()->isPost()) {
			$where = $this->_input->getEscaped();
			if(isset($this->getRequest()->path) && !isset($where['path'])){
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
		
		$select = MImages::getInstance()->getImage($where);
		
		$this->view->max_display_char = Zynas_Registry::getConfig()->constants->max_display_char;
		$this->view->paginator = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);
		
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
		
	}
	
	
	public function deleteAction() {
		
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		
		
		if (!$this->getRequest()->isPost()){
			FlashMessenger::addError(E062V);
			$this->_redirect('/download/list');
		}
		
		$idx = $this->_input->id;
		
		
		foreach ($idx as $id)
		{
			$row = MImages::getInstance()->getEntryById($id);
				
			if (is_Null($row)) {
				// error
				$this -> handleErrorDelete();
			}
			$db = Zynas_Db_Table::getDefaultAdapter();
			$db->beginTransaction();
			try {
				$row->delete_flg = MInformations::DELETE_FLG_ON;
				$row->save();
				$db->commit();
				//FlashMessenger::addSuccess(I002V);
					
			} catch (Exception $e) {
				$db->rollBack();
				FlashMessenger::addError($e->getMessage());
			}
				
		}
		Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
		$this->_redirect('/download/list');
	}
	
	public function downloadAction() {
		 Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
		 $id = isset($this->_input->id)? $this->_input->id :'';
		
		 
		 $this->_helper->layout->disableLayout();
		 $this->_helper->viewRenderer->setNoRender(true);
		
		 $file = MImages::getInstance()->getImageById($id);
		 $file_name = $file->path;
		
		
		 
		 $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
		
		 
		// $tfile ='';
// 		 $allow_ext = Zynas_Registry::getConfig()->document->type;
// 		$detail = $this->session->getData(self::SESSION_KEY_DETAIL);
		
// 		 $create_date = str_replace('-', '', substr($detail->create_date,0,7));
		
		 
		 //$tfile = APPLICATION_PATH.self::URL_ROOT_UPLOAD.$create_date.self::SEPARATOR.$id.self::SEPARATOR.$file_name;
		 header("Content-Type: application/images");
		 
		 $tfile = APPLICATION_PATH.self::URL_ROOT_UPLOAD.'/'.$file_name;
		 $tp = pathinfo($tfile);
		 //directly download
		 header("Content-disposition: attachment; filename=".$tp['basename'].";");
// 		 
// 		echo $tfile; exit;
		
		
		 header("Content-Length: ".filesize($tfile));
		 header('Content-Transfer-Encoding: Binary');
		 header('Accept-Ranges: bytes');
		 header('ETag: "'.md5($tfile).'"');
		 header("Cache-Control: no-cache, must-revalidate");
		 header("Pragma: no-cache");
		 
		 if(!empty($tfile)) {
		 	FileManager::readfileChunked($tfile);
		 	
		 }
		 
		 Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
		 
		 
		 
	}
	
}