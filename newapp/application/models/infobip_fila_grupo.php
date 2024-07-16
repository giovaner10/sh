<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Infobip_Fila_Grupo extends CI_Model
{
    function __construct()
    {
		parent::__construct();
	}

    public function get()
    {
        return $this->db->get_where("infobip.filas_grupos", ["status" => "ativo"])->result();
    }

    public function getFilaGrupo($id)
    {
        return $this->db->get_where("infobip.filas_grupos", ["id" => $id])->result()[0];
    }
    
    public function adicionar($dados)
    {
        if (!$this->db->insert("infobip.filas_grupos", $dados))
            throw new Exception(lang("mensagem_erro"));

        return $this->db->insert_id();
    }

    public function editar($id, $dados)
    {
        if (!$this->db->update("infobip.filas_grupos", $dados, ["id" => $id]))
            throw new Exception(lang("mensagem_erro"));
    }

    public function excluir($id)
	{
	    if (!$this->db->update("infobip.filas_grupos", ["status" => "inativo"], ["id" => $id]))
			throw new Exception(lang("mensagem_erro"));
	}
}