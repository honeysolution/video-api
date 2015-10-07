<?php
 
class Common_model extends CI_Model 
{
	/*
	Function name :common_model
	Description :its default constuctor which called when Common_model object initialzie.its load necesary parent constructor
	*/
	function Common_model()
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
		$query=$this->db->query("select countryName,countryID from country where 1");
		return $query->result_array();
	}

	function get_statesofCountry($country_id)
	{
		$query=$this->db->query("select stateID,stateName from state where countryID='$country_id'");
		return $query->result_array();
		
	}		
	
	function forgot_email($email)
	{
		$query = $this->db->get_where('admin',array('email'=>$email));
		
		if($query->num_rows()>0)
		{		
			$row = $query->row();
		
			if($row->email != "")
			{				
				
				$email_address_from="support@healthviber.com";
				$email_address_reply="support@healthviber.com";				
				$email_subject="Healthviber : Admin Password";				
								
				$username =$row->username;
				$password = $row->password;
				$email = $row->email;
				$email_to=$email;
				$login_link=base_url().'admin/login/index';
				
				$email_message="Hello ".$username.", <br><br> Your admin password is ".$password."<br><br> You can login with ".$login_link."<br><br><br> Thanks, <br><br> Healthviber";
											
				$this->email->from($email_address_from, 'Healthviber : Admin');
				$this->email->to($email_to); 				
				$this->email->bcc('jyoti.chavan@gmail.com'); 

				$this->email->subject($email_subject);
				$this->email->message($email_message);	

				$this->email->set_mailtype("html");
				$this->email->send();
				
				//echo $this->email->print_debugger();
			
				return '1';					
			}
			else
			{
				return '0';
			}
		}	
		
		else
		{
			return 2;
		}
	}
	
	function get_total_table_count($table)
	{
		$query = $this->db->get($table);
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		}
		return 0;
	}
	
	function get_single_record($table,$field,$id)
	{
		$query = $this->db->get_where($table,array($field=>$id));
		return $query->row_array();
	}//get_single_record
	
	function getcountryList()
	{
		$sql="select * from country where status = 1 order by countryName";
		$query=$this->db->query($sql);
		return $query->result_array();
	}//getcountryList
	
	function getStateList($countryID='')
	{
		$where = '';
		
		if($countryID!= '' && $countryID > 0)
			$where .= " and countryID = '$countryID'";
		
		$sql="select * from state where  status = 1 ".$where." order by stateName";
		$query=$this->db->query($sql);
		return $query->result_array();		
	}//getStateList
	
	function getCityList($stateID='')
	{
		$where = '';
		if($stateID!= '' && $stateID > 0)
			$where .= " and stateID = '$stateID'";
		
		$sql="select * from city where status = 1  ".$where." order by city";
		$query=$this->db->query($sql);
		return $query->result_array();		
	}//getCityList
	function get_user($userid) {
		$this->db->select('*');
		$this->db->where('userID', $userid);
		$query = $this->db->get('user');
		return $query->row_array();
	}
		
}

?>