<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comando extends CI_Model {
	private $to_view = null;
	
	public function __construct(){
		parent::__construct();
	}

	public function add_command($dados) {	
		
	    $this->db->insert('systems.cad_comando_mxt', $dados);
		if($this->db->affected_rows() > 0) {
			return true;
		}else{
			$msg = $this->db->_error_message();
			$num = $this->db->_error_number();
			throw new Exception($msg.' --- '.$num);
		}
	}

	public function get_commands($serial){
		if(substr($serial, 0, 2) == '64' || substr($serial, 0, 2) == '65' ){
			$query = $this->db->select('*')
							  ->where('equipamento', 700)
							  ->get('systems.cad_comando_mxt');
		}else{
			$query = $this->db->select('*')
							  ->where('equipamento', 140)
							  ->get('systems.cad_comando_mxt');
		}
		if($query->num_rows() > 0)
			return $query->result();
		return false;
	}
	public function get_all(){
			$query = $this->db->select('*')
							  ->get('systems.cad_comando_mxt');
		if($query->num_rows() > 0)
			return $query->result();
		return false;
	}

	public function get_xml($code){
			$query = $this->db->select('xml')
							  ->where('code', $code)
							  ->get('systems.cad_comando_mxt');
		if($query->num_rows() > 0)
			return $query->result();
		return false;
	}

	public function get_nome($code){
			$query = $this->db->select('nome')
							  ->where('code', $code)
							  ->get('systems.cad_comando_mxt');
		if($query->num_rows() > 0)
			return $query->result();
		return false;
	}

	public function remove_command($id) {
	
		$this->db->delete('systems.cad_comando_mxt',$id);
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			throw new Exception('Não foi possível remover no banco de dados. Tente novamente.');
		}	
	}
}