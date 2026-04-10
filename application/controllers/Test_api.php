<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_api extends CI_Controller {
public function __construct() {
        parent::__construct();
        $this->load->model('Api_model');
        
    }
 function index()
 {
  $this->load->view('api_view');
 }

 function action()
 {
  if($this->input->post('data_action'))
  {
   $data_action = $this->input->post('data_action');

   if($data_action == "Delete")
   {
       
//    $api_url = "http://localhost/rmt_git/controller/api/delete";

       $user_id=$this->input->post('user_id');
//    $form_data = array(
//     'id'  => $this->input->post('user_id')
//    );
    $api_url =  $this->Api_model->delete_single_user($user_id);
    $client = curl_init($api_url);

    curl_setopt($client, CURLOPT_POST, true);

    curl_setopt($client, CURLOPT_POSTFIELDS, $user_id);

    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($client);

    curl_close($client);

    echo $response;
   }

   if($data_action == "Edit")
   {
//    $api_url = "http://localhost/rmt_git/controller/api/update";
    $api_url=$this->Api_model->update($user_id);
    $form_data = array(
     'first_name'  => $this->input->post('first_name'),
     'last_name'   => $this->input->post('last_name'),
     'id'    => $this->input->post('user_id')
    );

    $client = curl_init($api_url);

    curl_setopt($client, CURLOPT_POST, true);

    curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($client);

    curl_close($client);

    echo $response;



   }

   if($data_action == "fetch_single")
   {
//    $api_url = "http://localhost/tutorial/controller/api/fetch_single";
    $api_url = $this->Api_model->fetch_single_user();
    $form_data = array(
     'id'  => $this->input->post('user_id')
    );

    $client = curl_init($api_url);

    curl_setopt($client, CURLOPT_POST, true);

    curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($client);

    curl_close($client);

    echo $response;

   }

   if($data_action == "Insert")
   {
       
//    $api_url = "http://localhost/rmt_git/models/Api_model/api/insert";
   
    $form_data = array(
     'first_name'  => $this->input->post('first_name'),
     'last_name'   => $this->input->post('last_name')
    );
$api_url= $this->Api_model->insert_api($form_data);

    $client = curl_init($api_url);

    curl_setopt($client, CURLOPT_POST, true);

    curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($client);

    curl_close($client);

    echo $response;


   }





   if($data_action == "fetch_all")
   {
       
       $Q6 =  $this->Api_model->fetch_all();
        
       
       
//    $api_url = "http://localhost/rmt_git/controller/api";
////    $api_url= $this->Api_model->fetch_all();
//
//    $client = curl_init($Q6);
//
//    curl_setopt($client, CURLOPT_RETURNTRANSFER, true); 
//
//    $response = curl_exec($client);
//
//    curl_close($client);
//
//    $result = json_decode($response);

    $output = '';

    if ($Q6->num_rows() > 0) 
    {
           $result = $Q6->result();
     foreach($result as $row)
     {
      $output .= '
      <tr>
       <td>'.$row->first_name.'</td>
       <td>'.$row->last_name.'</td>
       <td><butto type="button" name="edit" class="btn btn-warning btn-xs edit" id="'.$row->id.'">Edit</button></td>
       <td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row->id.'">Delete</button></td>
      </tr>

      ';
     }
    }
    else
    {
     $output .= '
     <tr>
      <td colspan="4" align="center">No Data Found</td>
     </tr>
     ';
    }

    echo $output;
   }
  }
 }
 
}

?>

