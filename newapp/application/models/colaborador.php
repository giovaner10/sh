<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Colaborador extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getColaborador($funcionarioId)
    {
	    $query = $this->db->select("colaborador.*")
            ->where(["colaborador.id_usuario" => $funcionarioId])
            ->get("showtecsystem.cad_colaborador as colaborador");

		$colaborador = $query->result();

		if ($colaborador)
			return $colaborador[0];
		else
			return new stdClass();
	}

	public function adicionar($dados)
	{
	    if (!$this->db->insert('showtecsystem.cad_colaborador', $dados))
			throw new Exception(lang("mensagem_erro"));

		return $this->db->insert_id();
	}

	public function editar($id, $dados)
	{
	    if (!$this->db->update('showtecsystem.cad_colaborador', $dados, ['id' => $id]))
			throw new Exception(lang("mensagem_erro"));
	}
}