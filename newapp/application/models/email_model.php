<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Email_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/*
	* PEGA OS EMAILS DO CLIENTE
	*/
	//$setor eh um caracter/string
	public function getEmails($id_cliente, $select='*', $setor='todos', $condicao=array('lixo'=>'0')) {
		if ($setor != 'todos') {
			$query = $this->db->select($select)
			->where( array('cliente_id' => $id_cliente, 'setor' => $setor ) )
			->where($condicao)
			->get('showtecsystem.clientes_emails');

			if ($query->num_rows()){
				return $query->result();
			}
		}else {
			$query = $this->db->select($select)
			->where( 'cliente_id', $id_cliente )
			->where($condicao)
			->get('showtecsystem.clientes_emails');
			if ($query->num_rows()){
				return $query->result();
			}
		}
		return false;
	}

	/*
	* INSERE O EMAIL NO BANCO
	*/
	public function insertEmail($dados) {
        $this->db->insert('showtecsystem.clientes_emails', $dados);	
		return $this->db->insert_id();
	}

	/*
	* SALVA OS EMAILS POR MEIO DE INSERTBATCH
	*/
	public function insertBatchEmails($dados) {
        @$this->db->insert_batch('showtecsystem.clientes_emails', $dados);
		return $this->db->affected_rows();
	}

	//ATUALIZA EMAIL
    public function atulizarEmail($id_email, $dados){
        $this->db->update('showtecsystem.clientes_emails', $dados, array('id' => $id_email));
        if ($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

	/*
	* ATUALIZA OS EMAILS POR MEIO DE UPDATEBATCH
	*/
	public function updateBatchEmails($dados, $identificador='id') {
        @$this->db->update_batch('showtecsystem.clientes_emails', $dados, $identificador);
		return $this->db->affected_rows();
	}

}
