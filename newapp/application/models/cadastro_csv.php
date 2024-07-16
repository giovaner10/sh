<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cadastro_csv extends CI_Model {
	private $to_view = null;
	
	public function __construct(){
		parent::__construct();
	}

	public function add_eqp($dados){
		$now = date('Y-m-d H:i:s');
		for ($i=0; $i < count($dados['serial']) - 1 ; $i++) { 
			$insert =  array('serial' => $dados['serial'][$i],'modelo' => $dados['modelo'][$i],'marca' => $dados['marca'][$i]);
			$query = $this->db->select('*')
									->where('serial', $dados['serial'][$i])
								  ->get('showtecsystem.cad_equipamentos');
			if($query->num_rows() > 0)
				continue;
			$this->db->insert('showtecsystem.cad_equipamentos', $insert);
			$id_eqp = $this->db->insert_id();
			$insert_chip = array('ccid' => $dados['ccid'][$i],'numero' => $dados['linha'][$i],'operadora' => $dados['operadora'][$i],'data_cadastro' => $now,'id_equipamento' =>$id_eqp);
			$this->db->insert('showtecsystem.cad_chips', $insert_chip);
			$id_chip = $this->db->insert_id();
			$this->db->where('id', $id_eqp);
			$this->db->update('showtecsystem.cad_equipamentos',array('id_chip' =>$id_chip) );
		}
		if($this->db->affected_rows() > 0) {
			return true;
		}else{
			$msg = $this->db->_error_message();
			$num = $this->db->_error_number();
			throw new Exception($msg.' --- '.$num);
		}		
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