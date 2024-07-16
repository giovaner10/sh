<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Cor_Pele extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get()
    {
	    return $this->db->get("showtecsystem.cor_pele")->result();
	}
}