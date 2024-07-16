<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Atividade extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getAtividades($usuarioid = null)
    {
        # Filtra atividades por usuário
        if ($usuarioid)
            $this->db->where("atividades.id_usuario = $usuarioid");

        return $this->db->select("usuario.nome as funcionario, atividades.*")
	        ->join("showtecsystem.usuario as usuario", "usuario.id = atividades.id_usuario", "inner")
	        ->where("data_exclusao IS NULL")
            ->order_by("atividades.id", "DESC")
            ->get("showtecsystem.cad_atividades as atividades")
            ->result();
    }

    public function getAtividade($id)
    {
        return $this->db->get_where("showtecsystem.cad_atividades", ["id" => $id])
            ->result()[0];
    }

    public function adicionar($dados)
    {
        if (!$this->db->insert("showtecsystem.cad_atividades", $dados))
            throw new Exception(lang("mensagem_erro"));
    }

    public function editar($id, $dados)
    {
        if (!$this->db->update("showtecsystem.cad_atividades", $dados, ["id" => $id]))
            throw new Exception(lang("mensagem_erro"));
    }

    public function excluir($id)
    {
        # Inativa o comunicado
        if (!$this->db->update(
                "showtecsystem.cad_atividades", ["data_exclusao" => date("Y-m-d H:i:s")], ["id" => $id])
            )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }
}