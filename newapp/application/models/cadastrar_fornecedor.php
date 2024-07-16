<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cadastrar_fornecedor extends CI_Model {
	
	public function __construct(){
		parent::__construct();
	}


	public function getFornecedorByid($id=NULL){

		if ($id != NULL):
			$this->db->where('id', $id);
			$this->db->limit(1);
			$query = $this->db->get('fornecedores');
			return $query->row();

		endif;	

	}

	public function addFornecedor($dados,$banco){
		//if ($query->num_rows() == false) {
			$this->db->insert('showtecsystem.fornecedores',$dados);
			if($this->db->affected_rows() > 0) {
				$banco['id_retorno'] = $this->db->insert_id();
				$this->db->insert('showtecsystem.cad_contasbank',$banco);
				return true;
			}
			
		//}
		return false;    
	}

	public function addConta($banco){
		$this->db->insert('showtecsystem.cad_contasbank',$banco);
		return true;
	}

	public function get_contasById($id) {
        return $this->db->get_where('showtecsystem.cad_contasbank', array('id_retorno' => $id, 'cad_retorno' => 'fornecedor'))->result();
    }
	public function update_conta($dados, $id) {
	    return $this->db->where('id', $id)->update('showtecsystem.cad_contasbank', $dados);
	}
	public function update_fornecedor($dados,$id){
		$this->db->where('id', $id)->update('showtecsystem.fornecedores',$dados);
	}
	public function getFornecedor(){

		$query = $this->db->select('*')->where('status',1)->from('fornecedores')-> get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return null;

	}

	public function contatadorAtivo(){


		$sql = "SELECT COUNT(*) AS status FROM fornecedores WHERE status = '1'";      
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$fila=$query->row();
			return $fila->status;
		}else{
			return 0;
		}

	}

	public function contatadorInativo(){


		$sql = "SELECT COUNT(*) AS status FROM fornecedores WHERE status = '0'";      
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$fila=$query->row();
			return $fila->status;
		}else{
			return 0;
		}

	}

}
