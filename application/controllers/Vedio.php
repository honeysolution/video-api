<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Vedio extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    function vedio()
	{
		parent::__construct();
		$this->load->model('common_model');
        $this->load->model('home_model');
        
      
    } 
	public function index()
	{
		$this->load->view('welcome_message');
	}
    public function getAllVedio()
	{
		$allcategories = "SELECT * FROM categories";
        $data['allcategories'] = $this->common_model->selectAllRecords($allcategories); 
        $allvedios = "SELECT vedios.*,categories.cat_name as cat_name FROM vedios,categories where vedios.category=categories.ID";
        $data['allvedios'] = $this->common_model->selectAllRecords($allvedios);            
       echo  $json_response = json_encode($data);
	}
    public function getCategories()
	{
		$allcategories = "SELECT * FROM categories where status = '1'";
        $data['allcategories'] = $this->common_model->selectAllRecords($allcategories);            
       echo  $json_response = json_encode($data);
	}
    public function viewcategory($cat_id='')
	{
        $catName = "SELECT *  FROM categories  WHERE  ID=".$cat_id."";
		$data['catName'] = $this->home_model->selectRecord($catName);
		$viewcategory = "SELECT * FROM vedios where category = '".$cat_id."'";
        $data['viewcategory'] = $this->common_model->selectAllRecords($viewcategory);            
       echo  $json_response = json_encode($data);
	}
    public function likes($vedio_id = '')
	{
        $insert['vedio_id'] = $vedio_id;		        
        $data['insert'] = $this->common_model->insertTableData('likes', $insert);
        $selectsong = "SELECT *  FROM likes  WHERE  vedio_id=".$vedio_id."";
        $like = $this->common_model->selectAllRecords($selectsong);
		$data['likes'] = sizeof($like);
        echo  $json_response = json_encode($data);
	}
    
     public function getSingle($vedio_id = '')
	{
        $insert['vedio_id'] = $vedio_id;		        
        $view = $this->common_model->insertTableData('views', $insert); 
         $selectsong = "SELECT *  FROM likes  WHERE  vedio_id=".$vedio_id."";
		$like = $this->common_model->selectAllRecords($selectsong);
		$data['likes'] = sizeof($like);
         $selectsong = "SELECT *  FROM views  WHERE  vedio_id=".$vedio_id."";
		$view_get = $this->common_model->selectAllRecords($selectsong);
		$data['views'] = sizeof($view_get);
		$selectsong = "SELECT *  FROM vedios  WHERE  ID=".$vedio_id."";
		$data['song'] = $this->home_model->selectRecord($selectsong);
            
       echo  $json_response = json_encode($data);
	}
}
