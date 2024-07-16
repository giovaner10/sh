<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Token extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function get($token){
		
		$call_token = array();
		
		$query = $this->db->get_where('token', array('token' => $token)); 
		if ($query->num_rows() > 0){
			foreach ($query->result() as $call_token);
		}
		
		return $call_token;
	}

	public function validar($token, $request){
		
		$token = $this->get($token);
		if(isset($token) > 0){
			return true;
		}
		
		return false;
	}
}
	