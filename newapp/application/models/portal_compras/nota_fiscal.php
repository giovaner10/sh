<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Nota_fiscal extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}

	public function listar($colunas = '*', $condicao = [], $joinSolicitacao = false) {
    $query = $this->db
      ->select($colunas)
      ->where($condicao)
			->order_by('nota_fiscal.id', 'desc')
      ->from('portal_compras.nota_fiscal');
    
    if ($joinSolicitacao) {
      $query->join('portal_compras.solicitacoes', 'solicitacoes.codigo_pedido_erp = nota_fiscal.codigo_pedido_erp');
    }

    return $query->get()->result();
  }

}