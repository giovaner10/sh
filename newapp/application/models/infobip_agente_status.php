<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Infobip_Agente_Status extends CI_Model
{
    function __construct()
    {
		parent::__construct();
	}

    public function get($where = [])
    {
        return $this->db->get_where("infobip.agentes_status", $where)->result();
    }

    public function getAgenteStatus($id)
    {
        return $this->db->get_where("infobip.agentes_status", ["id" => $id])->result()[0];
    }

    public function editar($id, $dados)
    {
        if (!$this->db->update("infobip.agentes_status", $dados, ["id" => $id]))
            throw new Exception(lang("mensagem_erro"));
    }
}