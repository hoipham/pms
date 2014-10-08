<?php
class Departments extends Db_Table
{
	protected $_classRow = "Department";
	
	public function getDepartment()
	{
		$select = $this->select()
		->from(array('dep'=>$this->info('name')));
		
		return $this->fetchAll($select);
	}
	
}