<?php 

class SampleModel extends Model {
    
    
    public function getTable(){
    	$result = $this->query("SELECT * FROM m_friends");
		return $result;
	}
	
	public function abc(){
    	return 'Hello user. Database connected!';
	}
    
}
