<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
*
* Model - Arquivos
*
* @author Erick Amaral
*
**/
class Arquivo extends CI_Model 
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

		$query = $this->db->get('showtec.arquivos');

		return $query->result();
	}
	
	/**
	*
	* Salvar um arquivo
	*
	**/
	public function salvar($dados)
	{
		return $this->db->insert('showtec.arquivos', $dados);
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

		$query = $this->db->get('showtec.arquivos');

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
	// ------------------------------------------------------------------------------------------
	
	public function atualizar($where, $dados){
		
		$this->db->update('showtec.arquivos', $dados, $where);
		if($this->db->affected_rows() > 0)
			return true;
		
		return false;
		
	}
	
	public function getArquivo($where = array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'ASC'){
	    
	    $query = $this->db->where($where)
	    ->order_by($campo_ordem, $ordem)
	    ->get('arquivos', $limite, $paginacao);
	    
	    return $query->result();
	    
	}
	public function get_folhetos(){
		$this->db->from('arquivos');
		$this->db->where("pasta = 'folhetos'");
		return $this->db->get()->result();
	}
	public function get_folhetos_by_id($id){
		$this->db->from('arquivos');
		$this->db->where("pasta = 'folhetos'");
		$this->db->where('id',$id);
		return $this->db->get();
	}
	
	public function getApresentacao(){
	    
	    $query = $this->db->select('*')->get('showtecsystem.cad_apresentacao');
	    
	    return $query->result();
	    
	}
	
	public function getParceria(){
	    
	    $query = $this->db->select('*')->get('showtecsystem.cad_parcerias');
	    
	    return $query->result();
	    
	}
	
	public function getApresentacaoEditar($tabela, $where){
	    
	    $query = $this->db->get_where($tabela, $where);
	    
	    return $query->result();
	    
	}
	
	
	public function getArquivoApresentacao($where = array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'ASC'){
	    
	    $query = $this->db->where($where)
	    ->order_by($campo_ordem, $ordem)
	    ->get('arquivos', $limite, $paginacao);
	    
	    return $query->result();
	    
	}
	
	public function excluirArquivoById($id) {	    
	    return $this->db->where('id', $id)->delete('arquivos');
	}
	
	public function excluirApresentacaoById($id) {
	    return $this->db->where('id', $id)->delete('cad_apresentacao');
	}
	
	public function excluirArquivoApresentacaoById($id) {
	    return $this->db->where('id', $id)->delete('cad_apresentacao_arquivos');
	}
	
	public function getComunicados(){
	    
	    $query = $this->db->select('c.id, c.comunicado, c.data, a.pasta, a.path, a.file, c.id_arquivo ')
	    ->where("pasta = 'comunicados' AND c.status = 'ativo'")
	    ->order_by('c.id', 'DESC')
	    ->join('showtecsystem.arquivos as a', 'c.id_arquivo = a.id', 'inner')
	    ->get('showtecsystem.cad_comunicados as c');
	    
	    return $query->result();
	    
	}

	/* ------------------------------ Showtecsystem ------------------------------------ */
	public function adicionar($dados)
	{
		if (!$this->db->insert('showtecsystem.arquivos', $dados))
            throw new Exception(lang("mensagem_erro"));

		return $this->db->insert_id();
	}

	public function editar($dados, $where)
	{
		# se file for vazio, desconsidera alteracao da coluna
		if (array_key_exists("file", $dados) && !$dados["file"])
			unset($dados["file"]);

		# se path for vazio, desconsidera alteracao da coluna
		if (array_key_exists("path", $dados) && !$dados["path"])
			unset($dados["path"]);

		if (!$this->db->update('showtecsystem.arquivos', $dados, $where))
			throw new Exception(lang("mensagem_erro"));
	}

	public function excluir($id)
	{
	    if (!$this->db->update("showtecsystem.arquivos", ["status" => "inativo"], ["id" => $id]))
			throw new Exception(lang("mensagem_erro"));
	}

	public function getArquivos($where = array(), $paginacao = 0, $limite = 9999999, $campo_ordem = 'id', $ordem = 'ASC'){
	    
	    $query = $this->db->where($where)
		->where("status", "ativo")
	    ->order_by($campo_ordem, $ordem)
	    ->get("showtecsystem.arquivos", $limite, $paginacao);
	    
	    return $query->result();
	}
}