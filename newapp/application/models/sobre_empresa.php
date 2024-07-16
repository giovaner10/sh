<?php if ( ! defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Sobre_empresa extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

    public function get()
    {
	    $query = $this->db->select('*')->get("showtecsystem.cad_sobre_empresa");

	    return $query->result();
	}

}