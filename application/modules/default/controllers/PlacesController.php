<?php
class PlacesController extends Controller {
	public function specifyAction()
	{ 
		$db = Zynas_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		
		$row = Countrys::getInstance()->getCountryById($id);
		$this->getRequest()->setParams($row->toArray());
		
	}
}