<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Infobip_Canal_Tipo extends CI_Model
{
    function __construct()
    {
		parent::__construct();
	}

    public function get($where = [])
    {
        return $this->db->get_where("infobip.canais_tipos", $where)->result();
    }

    public function getCanalTipo($id)
    {
        return $this->db->get_where("infobip.canais_tipos", ["id" => $id])->result()[0];
    }
    
    public function editar($id, $dados)
    {
        if (!$this->db->update("infobip.canais_tipos", $dados, ["id" => $id]))
            throw new Exception(lang("mensagem_erro"));
    }
}