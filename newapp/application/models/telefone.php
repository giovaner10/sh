<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Telefone extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/*
	* PEGA OS TELEFONES DO CLIENTE
	*/
	//$setor eh um caracter/string
	public function getTelefonesCliente($id_cliente, $select='*', $setor='todos', $condicao=array('lixo'=>'0')) {
		if ($setor != 'todos') {
			$query = $this->db->select($select)
			->where( array('cliente_id' => $id_cliente, 'setor' => $setor ) )
			->where($condicao)
			->get('showtecsystem.clientes_telefones');

			if ($query->num_rows()){
				return $query->result();
			}
		}else {
			$query = $this->db->select($select)
			->where( 'cliente_id', $id_cliente )
			->where($condicao)
			->get('showtecsystem.clientes_telefones');

			if ($query->num_rows()){
				return $query->result();
			}
		}
		return false;
	}

	/*
	* SALVA O ENDERECO NO BANCO
	*/
	public function insertTelefone($dados) {
        $this->db->insert('showtecsystem.clientes_telefones', $dados);
		return $this->db->insert_id();
	}


	/*
	* SALVA OS TELEFONES POR MEIO DE INSERTBATCH
	*/
	public function insertBatchTelefones($dados) {
        @$this->db->insert_batch('showtecsystem.clientes_telefones', $dados);
		return $this->db->affected_rows();
	}

	//ATUALIZA TELEFONE
    public function atulizarTelefone($id_fone, $dados){
        $this->db->update('showtecsystem.clientes_telefones', $dados, array('id' => $id_fone));
        if ($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

	/*
    * PEGA OS DADOS DE UM TELEFONE
    */
    public function get_telefone($where, $colunas='*'){
        return $query = $this->db->select($colunas)
        ->where($where)
        ->get('showtecsystem.telefones')->row();        
    }

	/*
	* ATUALIZA OS EMAILS POR MEIO DE UPDATEBATCH
	*/
	public function updateBatchTelefones($dados, $identificador='id') {
        @$this->db->update_batch('showtecsystem.clientes_telefones', $dados, $identificador);
		return $this->db->affected_rows();
	}

}
