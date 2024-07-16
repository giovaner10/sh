<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('models/fatura.php');

class Itens_fatura extends Fatura {

	private $id_item;
	private $id_fatura;
	private $tipo_item;

	public function __construct(){

		parent::__construct();
		
	}

	public function salvar_item(){
        $data = array(
            'id_ft' => $this->id_fatura,
            'tipo_item' => $this->tipo_item
        );

        $this->db->insert('itens_fatura', $data);

        $this->id_item = $this->db->insert_id();
    }

    public function excluir_item(){
        $this->db->where('id_item', $this->id_item);
        $this->db->delete('itens_fatura');
    }

}
