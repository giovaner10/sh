<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*
* Model - Arquivos
*
* @author Erick Amaral
*
**/
class ocorrencia_cliente extends CI_Model 
{
	
	/**
	*
	* Construtor
	*
	**/
	public function __construct()
	{
		parent::__construct();
	}
	// ------------------------------------------------------------------------------------------

	/**
	*
	* Pega os arquivos
	*
	**/
	public function get($where = null, $order = null, $sort = null, $limit = null, $offset = null)
	{
		if ($where) {
			$this->db->where($where);
		}

		if ($order) {
			$this->db->order_by($order, $sort ? $sort : 'asc');
		}

		if ($limit) {
			$this->db->limit($limit, $offset ? $offset : null);
		}

		$query = $this->db->get('showtec.ocorrencia_cliente');

		return $query->result();
	}

	public function getOcorrencias($id)
	{
    $this->db->select('ocorrencia_cliente.*, cad_clientes.nome as usuario');
    
    $this->db->from('showtecsystem.ocorrencia_cliente');

    $this->db->join('cad_clientes', 'cad_clientes.id = ocorrencia_cliente.idCliente');
    
    $this->db->where('ocorrencia_cliente.idCliente', $id);
    
    $this->db->order_by('ocorrencia_cliente.data', 'DESC');
    
    $query = $this->db->get();
    
    // Retornar os resultados
    return $query->result();
	}

	public function deletarOcorrencia($id)
{
    $this->db->where('id', $id);
    return $this->db->delete('showtecsystem.ocorrencia_cliente');
}

public function editarOcorrencia($id, $descricao)
{
    $data = array(
        'descricao' => $descricao
    );

    $this->db->where('id', $id);
    
    return $this->db->update('showtecsystem.ocorrencia_cliente', $data);
}
	
	/**
	*
	* Salvar um arquivo
	*
	**/
	public function salvar($dados)
	{
		return $this->db->insert('showtecsystem.ocorrencia_cliente', $dados);
	}
	// ------------------------------------------------------------------------------------------

	/**
	*
	* Apaga um arquivo
	*
	**/
	public function deletar($id)
	{
		$this->db->where('id', $id);
		$this->db->limit(1);

		$query = $this->db->get('showtec.ocorrencia_cliente');

		if ($query->num_rows()) {
			
			$arquivo = $query->row();

			if ($this->db->delete('showtec.arquivos', array('id' => $arquivo->id))) {

				if (file_exists('arquivos/' . $arquivo->nome)) {
					unlink('arquivos/' . $arquivo->nome);
				}

				return true;

			}

		}

		return false;
	}

}