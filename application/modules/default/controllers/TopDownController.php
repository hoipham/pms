<?php
class TopDownController extends Controller {
	
	public function indexAction() {
		
		$this->_forward('/test');
	}
	public function testAction()
	{
// 		$res = TblStaffs::getInstance()->getPositionById($id);
// 		echo "<pre>";
// 		print_r($res);
// 		echo "</pre>";exit;
		
		$country = Countrys::getInstance()->getCountry();
		////echo "</pre>";exit;
// 		echo "<pre>";
// 		print_r($country);
// 		echo "</pre>";exit;
		$arr = array();
		
		foreach ($country as $k=>$val) {
			$arr[$val->country_id] = $val->country_name;
		}
		
		$this->view->option = $arr;
		
	}
	
	public function provinceAction()
	{
		
		$id = $this->_input->country_id;
// 		echo $id; exit;
		$str ='';
		$res = Provinces::getInstance()->getProvinceById($id);
		
		foreach($res as $row)
		{
			$str = $str."$row[province_name]".",";
		}
		
		$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
		
		echo json_encode($str);exit;
	}
}