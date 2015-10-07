<?php
 
class Home_model extends CI_Model 
{
	/*
	Function name :Home_model
	Description :its default constuctor which called when Home_model object initialzie.its load necesary parent constructor
	*/
	function Home_model()
    {
        parent::__construct();	
    } 

   
	function getTableData($sql, $offset, $per_page)
	{
	   $query = $this->db->query($sql ." LIMIT $offset, $per_page");
	   return $query->result_array();
	}
	
	/* Function to count number of rows from previous query	*/
	function countRows()
	{
            return $this->db->query('SELECT FOUND_ROWS() AS cnt')->row()->cnt;
	}

    /*Insert record*/
	function insertTableData($table, $insert)
	{
	    if($this->db->insert($table, $insert))
		return $this->db->insert_id();
            else
		return FALSE;
	}

    /*Update record*/
	function updateTableData($table, $update, $arr)
	{
		if($this->db->update($table,$update,$arr))
				return TRUE;
		return FALSE;
	}
	
    /*Returns array of all records*/
	function selectAllRecords($sql)
	{
	   $query = $this->db->query($sql);
	   return $query->result_array();
	}

    /*select single record*/
	function selectRecord($sql)
	{
	   $query = $this->db->query($sql);
	   return $query->row();
	}

	function deleteRecord($table,$arr)
	{
       $this->db->delete($table, $arr); //array('id' => $id)
	}
    
    /*check duplicate while adding*/
	function check_duplicate($table, $field, $value)
	{
		$sql = "select $field from $table where $field=? LIMIT 1";
		$query= $this->db->query($sql,array($value));
		return $query->num_rows();
	}


    /*check duplicate while editing*/
	function check_duplicate_edit($table, $field, $primary_field, $primary_value, $new_value)
	{
		$sql = "select $field from $table where $field=? and $primary_field!=? LIMIT 1";
		$query= $this->db->query($sql,array($new_value, $primary_value));
		return $query->num_rows();
	}
	
	function generate_code($length = 3)
	{
	   $salt = "ABCDEFGHIJKLMNPQRSTUVWXYZ";  // salt to select chars from
	   srand((double)microtime()*1000000); // start the random generator
	   $code=$final_code=""; // set the inital variable
	   for ($i = 0;$i < $length;$i++)  // loop and create password
		   $code = $code . substr ($salt, rand() % strlen($salt), 1);
	   // done!
	   $final_code = 'COS'.$code;
	   
	   $dup_cnt = $this->check_duplicate('sci_school','schoolRegCode',$final_code);
	   if($dup_cnt > 0)
		$this->generate_code();
	   else
		return $final_code;
	}
	
	function get_country()
	{
		$query=$this->db->query("select countryName,countryID from sci_country where 1");
		return $query->result_array();
	}

	function get_statesofCountry($country_id)
	{
		$query=$this->db->query("select stateID,stateName from sci_state where countryID='$country_id'");
		return $query->result_array();
		
	}	
    function get_user($userid) {
		$this->db->select('*');
		$this->db->where('userID', $userid);
		$query = $this->db->get('user');
		return $query->row_array();
	}
}

?>