<?php if ( ! defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Departamento extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getDepartamentos()
    {
        return $this->db
            ->select("id, nome")
            ->get_where("showtecsystem.departamentos", ["status" => "ativo"])
            ->result();
    }

}