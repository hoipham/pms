<?php
class Countrys extends Db_Table {
	protected $_rowclass = "Country";
	protected $_primary = 'country_id';
	
	public function getCountry() {
		
		$select = $this->select()
		->from(array('country' => $this->info('name')));
		
        return $this->fetchAll($select);
	}
}