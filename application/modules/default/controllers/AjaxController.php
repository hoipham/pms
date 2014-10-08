<?php
// header('Content-Type: application/json');
class AjaxController extends Controller {	
	const COOKIE_KEY_USER_ID = 'user_id';
		
	function indexAction(){
		
		
// 		echo __METHOD__;
		
// 		Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
// 		if ($this->getRequest()->isGet()) {
// 			$cookie = new Zynas_CookieManager();
// 			if ($cookie->getData(self::COOKIE_KEY_USER_ID)) {
// 				$this->view->mailAdress = $cookie->getData(self::COOKIE_KEY_USER_ID);
// 			}
// 		}
		
// 		Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
	}
	
	
	public function confirmationAction(){
// 		header('Content-Type: application/json');
		// get url cua file hien tai
		//$urlBase = $_SERVER['PHP_SELF'];
		
		//kiem tra thong tin request den
// 		if ($this->_request->isPost()) {
// 			$username = $this->_input->username;
// 		}
		
// 		if( isset($_POST['getRemember']) && $_POST['getRemember'] == 'view');
		$res = array('username'  => 'hoipt',
				'email'    => 'hoipt@trithucmoi.co',
				'password' => '123456');
		
		
// 				echo "111";
		$data = Zend_Json::encode($res);
		
	
		echo $data;
		//doi 1s truoc khi thuc thi
		//sleep(1);
// 		$this->_helper->viewRenderer->setNoRender(true);
// 		if ($this->getRequest()->isXmlHttpRequest()) {
// 		//tao mot mang
// // 		$data = array();
// // 		$data["username"] = "test user";
// // 		$data["email"] = "test@gmail.com";
// 		$member = array('username'  => 'hoipt',
// 						'email'    => 'hoipt@trithucmoi.co',
// 						'password' => '123456');
		
		
// // 		echo "111";
// 		echo json_encode($member);
// 		}
// 		$this->_helper->json->sendJson($data);
		
		//ket thuc va tra ve du lieu
		//die();
		
		
// // 		header('Content-type: application/json');
// 		$res = array('success' => 'OK', 'value' => '100');
		
// 	    $encode = json_encode($res);
		
// 		//exit;
		
// 		$this->_helper->viewRenderer->setNoRender(true);
// 		echo $encode;
	    // ghi log
// 		Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
		
// 		// get instance cua loginuser
// 		$auth = Ajax_User::getInstance();
		
// 	    // cookie
// 		$cookie = new Zynas_CookieManager();
		
// 		// get data
// 		$mailAdress = $this->_input->email;
// 		$password = $this->_input->password;
		
//		echo "OK";
		
	    //auth info input
// 		if ($auth->authenticate($mailAdress, $password)) {
// 			Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
// 			echo "Successful!";
// 		} else { 
// 			echo "Error!";
// 		}
		
		
	}
}