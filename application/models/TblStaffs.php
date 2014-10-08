<?php
class TblStaffs extends Db_Table {
	protected $_rowclass = "TblStaff";
	protected $_primary = 'staff_id';
	
	public function getStaff($where = array()) {
		$select = $this->select()
		->from(array('tblstaff' => $this->info('name')))
		->where('tblstaff.delete_flg <> ? OR tblstaff.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
		
		
		if(isset($where['name'])&& (strcmp($where['name'], '') !==0))
		{
			$title_key = preg_replace('!\s+!', '', $where['name']);
			$title_key = explode(Db_Table::SPACE, $title_key);
			
			$num = count($title_key);
			
			foreach ($title_key as $key => $value) {
				if (strcmp($num, '1') === 0) {
					$select->where('tblstaff.name like ?', '%' . $value . '%');
				} elseif (strcmp($key, '0') === 0) {
					$select->where('(tblstaff.name like ?', '%' . $value . '%');
				} elseif (strcmp($key, $num - 1) === 0){
					$select->orwhere('tblstaff.name like ?)', '%' . $value . '%');
				} else {
					$select->orwhere('tblstaff.name like ?', '%' . $value . '%');
				}
			}
				
			}
			
			return $select;
// 			return $this->fetchAll($select);
		}
		
		public function getStaffInfo() {
			$select = $this->select()
		    ->from(array('tblstaff' => $this->info('name')));
		    
			return $this->fetchAll($select);
		}
		
		
		public function getDepartment($where = array())
		{
			$select = $this->select()
			->from(array('tblstaff'=>$this->info('name')))->setIntegrityCheck(false)
			->joinInner(array('pos'=>Positions::getInstance()->info('name')), 'tblstaff.position_id = pos.id')
			->joinInner(array('dep'=>Departments::getInstance()->info('name')), 'tblstaff.department_id = dep.id')
			->where('tblstaff.delete_flg <> ? OR tblstaff.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
			
			if(isset($where['name'])&& (strcmp($where['name'], '') !==0))
			{
				$title_key = preg_replace('!\s+!', '', $where['name']);
				$title_key = explode(Db_Table::SPACE, $title_key);
					
				$num = count($title_key);
					
				foreach ($title_key as $key => $value) {
					if (strcmp($num, '1') === 0) {
						$select->where('tblstaff.name like ?', '%' . $value . '%');	
					} elseif (strcmp($key, '0') === 0) {
						$select->where('(tblstaff.name like ?', '%' . $value . '%');
					} elseif (strcmp($key, $num - 1) === 0){
						$select->orwhere('tblstaff.name like ?)', '%' . $value . '%');
					} else {
						$select->orwhere('tblstaff.name like ?', '%' . $value . '%');
					}
				}
			
			}
			return $select;
			
		}
		
		public function getPositionById($id)
		{	
			$select = $this->select()
			->distinct()
			->from(array('tblstaff'=>$this->info('name')),array('position_id'))->setIntegrityCheck(false)
			->joinInner(array('pos'=>Positions::getInstance()->info('name')), 'tblstaff.position_id = pos.id')
			->joinInner(array('dep'=>Departments::getInstance()->info('name')), 'tblstaff.department_id = dep.id')
			->where('tblstaff.department_id=?', $id);
			return $this->fetchAll($select);
		}
		
		public function getTestJoin()
		{
			$select = $this->select()
			->from(array('tblstaff'=>$this->info('name')))->setIntegrityCheck(false)
			->joinInner(array('pos'=>Positions::getInstance()->info('name')), 'tblstaff.position_id = pos.id')
			->joinInner(array('dep'=>Departments::getInstance()->info('name')), 'tblstaff.department_id = dep.id')
			->where('tblstaff.delete_flg <> ? OR tblstaff.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
			
			return $this->fetchAll($select);
		}
		public function getTestJoinById($id)
		{
			$select = $this->select()
			->from(array('tblstaff'=>$this->info('name')))->setIntegrityCheck(false)
			->joinInner(array('pos'=>Positions::getInstance()->info('name')), 'tblstaff.position_id = pos.id')
			->joinInner(array('dep'=>Departments::getInstance()->info('name')), 'tblstaff.department_id = dep.id')
			->where('tblstaff.delete_flg <> ? OR tblstaff.delete_flg IS NULL', Db_Table::DELETE_FLG_ON)
			->where('tblstaff.staff_id=?', $id);
				
			return $this->fetchRow($select);
		}
		
		public function getStaffById($id)
		{
			$select = $this->select()
			->from(array('tblstaff'=>$this->info('name')))
			
			->where('tblstaff.staff_id = ?', $id);
			
			return $this->fetchRow($select);
		}
		
		
		public function getInfo($where=array())
		{
			$select = $this->select()->setIntegrityCheck(false)
			->from(array('t_staff'=>$this->info('name')), array('name', 'email'))
			->joinInner(array('dep'=>Departments::getInstance()->info('name')), 't_staff.department_id = dep.id', array('department_name'))
			->where('t_staff.delete_flg <> ? OR t_staff.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
			
			return $select;
		}
        
		
}
