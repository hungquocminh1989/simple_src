<?php 
class Model extends Database {
	
    /**■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
	* Các Hàm Cơ Bản Truy Xuất DB
	* ■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
	*/
	//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
	
	public function selectRowById($id){
		
    	$db = $this->MedooDb();
    	
    	$data = $db->select($this->table_name,'*',
    		[
				$this->pk_id => $id
			]
		);
		
		if($data != NULL && count($data) > 0 ){
			return $data;
		}
    	
		return NULL;
		
	}
	
	public function selectAllRows($arrOrderBy = array()){
		
    	$db = $this->MedooDb();
    	
    	if($arrOrderBy != NULL && count($arrOrderBy) > 0){
    	$data = $db->select($this->table_name,'*',
    			[
		    			"ORDER" => $arrOrderBy
	    		]
    	);
		}
		else{
			$data = $db->select($this->table_name,'*',
	    			[
		    			"ORDER" => [$this->pk_id => 'ASC']
		    		]
	    	);
		}
		
		if($data != NULL && count($data) > 0 ){
			return $data;
		}
    	
		return NULL;
		
	}
	
	public function selectRowsByConditions($param = array()){
		
    	$db = $this->MedooDb();
    	
    	$data = $db->select($this->table_name,'*',$param);
		
		if($data != NULL && count($data) > 0 ){
			return $data;
		}

		return NULL;
		
	}
	
	public function insertRow($sql_param){
    	
    	$db = $this->MedooDb();
		$db->begin_transaction();
		
			$db->insert($this->table_name,$sql_param);
			$lastInsertId = $db->id();
			$db->commit();
			return $lastInsertId;
			
	}
	
	public function insertRows($sql_params){
    	
    	$db = $this->MedooDb();
		$db->begin_transaction();		
		
			$arr_ids = array();
			foreach($sql_params as $key => $sql_param){
				$db->insert($this->table_name,$sql_param);
				$lastInsertId = $db->id();
				$arr_ids[] = $lastInsertId;
			}
			$db->commit();
			return $arr_ids;
			
	}
	
	public function updateRowById($sql_param, $id){
    	
    	$db = $this->MedooDb();
		$db->begin_transaction();
		
			$db->update($this->table_name,$sql_param,
				[
					$this->pk_id => $id
				]
			);
			$db->commit();
			return TRUE;
			
	}
	
	public function updateRowsByConditions($sql_param, $where_param){
    	
    	$db = $this->MedooDb();
		$db->begin_transaction();
		
			$db->update($this->table_name,$sql_param,$where_param);
			$db->commit();
			return TRUE;
			
	}
	
	public function deleteRowById($id){
		
		$db = $this->MedooDb();
		$db->begin_transaction();
		
			$db->delete($this->table_name,
				[
					$this->pk_id => $id
				]
			);
			$db->commit();
			return TRUE;
			
	}
	
	public function deleteRowsByConditions($param){
		
		$db = $this->MedooDb();
		$db->begin_transaction();
		
		$db->delete($this->table_name,$param);
		$db->commit();
		return TRUE;
		
	}
	
}
