<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Comentario extends CI_Model {
	
	public function __construct(){
		parent::__construct(); 
	}

	public function listar($colunas = '*', $condicao = [], $joinUsuario = false, $campo_ordenacao = 'comentario.id', $ordenacao = 'desc') {
    $query = $this->db
      ->select($colunas)
      ->where($condicao)
			->order_by($campo_ordenacao, $ordenacao)
      ->from('portal_compras.comentarios as comentario');
    
    if ($joinUsuario) {
      $query->join('showtecsystem.usuario', 'usuario.id = comentario.id_usuario');
    }

    return $query->get()->result();
  }

  public function cadastrar($dados) {
		$this->db->insert('portal_compras.comentarios', $dados);
		return $this->db->insert_id();
	}

}