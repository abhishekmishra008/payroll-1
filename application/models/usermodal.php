<?php 

class Usermodal extends CI_model
{
	public function getUserdata()
	{
		$this->load->database();
				
		$this->db->where("id",5);
		$q=$this->db->get("partner_header_all");
		return $q->result();


		
	}
}

?>