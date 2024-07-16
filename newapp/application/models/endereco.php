<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Endereco extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_endereco_entrega($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('showtecsystem.clientes_enderecos');
		if ($query->num_rows())
			return $query->row();
		return false;
	}

	/*
	* PEGA O ENDERECO
	*/
	//$tipo eh um caracter/string
	public function getEnderecos($id_cliente, $tipo='todos') {
		if ($tipo != 'todos') {
			$query = $this->db->where( array('cliente_id' => $id_cliente, 'tipo' => $tipo ) )
			->get('showtecsystem.clientes_enderecos');

			if ($query->num_rows()){
				return $query->result();
			}
		}else {
			$query = $this->db->where( 'cliente_id', $id_cliente )
			->get('showtecsystem.clientes_enderecos');

			if ($query->num_rows()){
				return $query->result();
			}
		}
		return false;
	}

	/*
	* SALVA OS ENDERECOS DO CLIENTE POR MEIO DE INSERTBATCH
	*/
	public function insertBatchEnderecos($dados) {
        return $this->db->insert_batch('showtecsystem.clientes_enderecos', $dados);
	}

	/*
	* SALVA O ENDERECO NO BANCO
	*/
	public function insertEndereco($dados) {
        $this->db->insert('showtecsystem.clientes_enderecos', $dados);
		return $this->db->insert_id();
	}

	//ATUALIZA ENDERECO
    public function atulizarEndereco($id_endereco, $dados){
        $this->db->update('showtecsystem.clientes_enderecos', $dados, array('id' => $id_endereco));
        if ($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

	/*
    * LISTA OS DADOS DE UM ENDERECO
    */
    public function get_endereco($where, $colunas='*'){
        return $query = $this->db->select($colunas)
        ->where($where)
        ->get('showtecsystem.enderecos')->row();        
    }

}
