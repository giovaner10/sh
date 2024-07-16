<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class digitaliza extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function remove($id) {
	
		$this->db->delete('showtecsystem.cad_digi',$id);
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			throw new Exception('Não foi possível remover no banco de dados. Tente novamente.');
		}	
	}
	

	public function add($data) {
		$this->db->insert('cad_digi', $data);
		if($this->db->affected_rows() > 0)
			return 1;
		return 0;
	}

	public function get($where){
		$query = $this->db->select('*')
						  ->where($where)
						  ->get('cad_digi');

		if($query->num_rows() > 0){
			foreach($query->result() as $conta);
				return $conta;
		}
		return array();		
	}

	public function get_all() {
		$query = $this->db->select('*')
						  ->order_by('id', 'DESC')
				          ->get('cad_digi');

		if($query->num_rows() > 0)
			return $query->result();
		return array();
	}

	public function get_bills($dates, $provider, $status) {

		if(!$status) 
			$status = array('0', '1', '3');

		$query = $this->db->select('*')
						  ->where($dates)
						  ->like('fornecedor', $provider)
						  ->where_in('status', $status)
						  ->order_by('id', 'DESC')
				          ->get('cad_digi');

		if($query->num_rows() > 0)
			return $query->result();
		return array();
		
	}

	public function update($data) {
		$this->db->update('showtecsystem.cad_digi', $data, array('id' => $data['id']));
		if($this->db->affected_rows() > 0)
			return 1;
		return 0;
	}

	public function get_files() {	
		$query = $this->db->get('showtecsystem.cad_digi');

		if ($query->num_rows()) 
			return $query->result();
		return false;
	}

	public function digitalizacao($descricao, $nome_arquivo) {
		$pasta = "digitalizacoes";
		$contrato = uniqid();
		$data = new DateTime();
		$dados = array(
			'arquivo' => $nome_arquivo,
			'descricao' => $descricao,
			'created_at' => $data->format('Y-m-d H:i:s'),
			'updated' => $data->format('Y-m-d H:i:s')
			
			
		);

		$resposta = $this->db->insert('showtecsystem.cad_digi', $dados);

		if ($resposta) {
			return array('id' => $this->db->insert_id(), 'descricao' => $descricao, 'file' => $nome_arquivo);
		}else{
		    return false;
		}
	}

}