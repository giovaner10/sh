<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Colaborador_Dependente extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get($colaboradorId)
    {
	    $query = $this->db->select("dependentes.*")
            ->where([
				"status" => "ativo",
				"dependentes.id_cad_colaborador" => $colaboradorId
				])
            ->get("showtecsystem.cad_colaborador_dependentes as dependentes");
	    
	    return $query->result();
	}

	public function adicionar($dados)
	{
	    if (!$this->db->insert("showtecsystem.cad_colaborador_dependentes", $dados))
			throw new Exception(lang("mensagem_erro"));

		return $this->db->insert_id();
	}

	public function editar($id, $dados)
	{
	    if (!$this->db->update("showtecsystem.cad_colaborador_dependentes", $dados, ["id" => $id]))
			throw new Exception(lang("mensagem_erro"));
	}

	public function excluir($id)
	{
		if (!$this->db->update("showtecsystem.cad_colaborador_dependentes", ["status" => "inativo"], ["id" => $id]))
			throw new Exception(lang("mensagem_erro"));
	}
}