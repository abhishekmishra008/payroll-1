<?php

class profile extends CI_controller{
    
    public function index(){
        
        
          $data['prev_title'] = "Profile";
        $data['page_title'] = "Profile";
        $this->load->view('Recommendation/profile', $data);

    }
    
    
    
    
    
    
}
