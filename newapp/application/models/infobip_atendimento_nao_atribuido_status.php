<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Infobip_Atendimento_Nao_Atribuido_Status extends CI_Model
{
    function __construct()
    {
		parent::__construct();
	}

    public function get()
    {
        return $this->db->get("infobip.atendimentos_nao_atribuidos_status")->result();
    }

    public function getPorId($id)
    {
        $retorno = $this->db->get_where(
            "infobip.atendimentos_nao_atribuidos_status",
            ["id" => $id]
        )->result();

        if (count($retorno) > 0)
            return $retorno[0];
    }

    public function editar($id, $dados)
    {
        if (!$this->db->update("infobip.atendimentos_nao_atribuidos_status", $dados, ["id" => $id]))
            throw new Exception(lang("mensagem_erro"));
    }
}