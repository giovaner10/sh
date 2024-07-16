<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Solicitacao extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}

  public function buscar($id, $colunas="*") {
    return $this->db
			->select($colunas)
      ->where('id', $id)
      ->get('portal_compras.solicitacoes')
      ->row();
  }
	
	public function listar($colunas = '*', $condicao = [], $joinUsuario = false) {
    $query = $this->db
      ->select($colunas)
      ->where($condicao)
      ->from('portal_compras.solicitacoes as solicitacao');
    
    if ($joinUsuario) {
      $query->join('showtecsystem.usuario', 'usuario.id = solicitacao.id_usuario');
    }

    return $query->get()->result();
  }


  public function cadastrar($dados) {
		$this->db->insert('portal_compras.solicitacoes', $dados);
		return $this->db->insert_id();
	}
	
  public function alterarStatus($id, $status) {
		$this->db->where('id', $id);
		return $this->db->update('portal_compras.solicitacoes', ['status' => $status]);
	}

  public function editar($id, $dados) {
		$this->db->where('id', $id);
		return $this->db->update('portal_compras.solicitacoes', $dados);
	}

  public function vincularCotacoes($dados) {
		if (is_array($dados) && !empty($dados)) {
			return $this->db->insert_batch('portal_compras.solicitacao_x_cotacoes', $dados);
		}
		return false;
  }

   public function removerVinculosCotacoes($idSolicitacao) {
    return $this->db->where('id_solicitacoes', $idSolicitacao)->delete('portal_compras.solicitacao_x_cotacoes');
  }

  public function buscarCotacoesPorSolicitacao($idSolicitacao) {
    return $this->db
      ->select('cotacao.*')
      ->join('portal_compras.cotacoes as cotacao', 'cotacao.id = sxc.id_cotacoes')
      ->where(['sxc.id_solicitacoes' => $idSolicitacao])
      ->get('portal_compras.solicitacao_x_cotacoes as sxc')
      ->result();
  }

}