<?php
class CustomerController extends Controller {

	const COOKIE_KEY_USER_ID = 'user_id';
	const SESSION_KEY_USER_ID = 'user_id';
	const COOKIE_KEY_PASSWORD = 'passwd';
	/**
	 * 初期化処理
	 * <pre>
	 * </pre>
	 */
	public function loginAction() {
		
		
		
		Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
		if ($this->getRequest()->isGet()) {
			$cookie = new Zynas_CookieManager();
			if ($cookie->getData(self::COOKIE_KEY_USER_ID)) {
				$this->view->mailAdress = $cookie->getData(self::COOKIE_KEY_USER_ID);
				
			}
		}
		Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
	}

	/**
	 * ログイン処理
	 * <pre>
	 * </pre>
	 */
	public function confirmLoginAction() {
		
		// ghi log
		Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
		
		// get instance cua loginuser
		$auth = Customer_User::getInstance();
		
	    // cookie
		$cookie = new Zynas_CookieManager();
		
		// get data
		$mailAdress = $this->_input->mailAdress;
		$password = $this->_input->password;
		$confirmPass = $this->_input->confirmPass;
		$remember = $this->_input->remember;
		
	    //auth info input
		if ($auth->authenticate($mailAdress, $password)) {
			
			if ($remember == MUsers::REMEMBER) {
				$cookie->setData($mailAdress, self::COOKIE_KEY_USER_ID, '', 30, false);
			}
			Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
			
			return $this->_redirect('/staff/list');
			
		} else { 
			return $this->handleErrorDoLogin();
		}
	}
	
	public function handleErrorDoLogin() {
		$this->view->errors = array('login' => E068V);
		return $this->_forward('login');
	}
	
	public function registerAction()
	{
		$db = Zynas_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		
		$row = TblStaffs::getInstance()->createRow();
		$row->create_date = Zynas_Date::dbDatetime();
		$row->name = $this->_input->name;
		$row->email = $this->_input->email;
		$row->password = $this->_input->password;
		$row->position = $this->_input->sbt_pos;
		
		$row->save();
		
// 		FlashMessenger::addSuccess(E065V);
		$db->commit();
		
		FlashMessenger::addSuccess("Register Successful!");
		
// 		$this->redirect('/staff/list');
	}
	
	
}