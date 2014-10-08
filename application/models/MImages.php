<?php
class MImages extends Db_Table {
	protected $_rowclass = "MImage";
	
	public function getImage($where = array()) {
		$select = $this->select()
		->from(array('m_image' => $this->info('name')))
		->where('m_image.delete_flg <> ? OR m_image.delete_flg IS NULL', Db_Table::DELETE_FLG_ON);
		return $select;
	}
	
	public function getImageById($id) {
	   $select = $this->select() ->from (array('m_image' => $this->info('name')))
	                              ->where('m_image.id = ? ', $id);
	   return $this->fetchRow($select);
	}
}