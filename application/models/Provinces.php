<?php
class Provinces extends Db_Table {
	
	
	
	public function getProvinceById($country_id) {
		$select = $this->select()
		->from(array('province' => $this->info('name')))

		->where('province.country_id = ?' ,$country_id);
		
		return $this->fetchAll($select);
	}
}