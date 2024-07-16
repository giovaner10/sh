<?php if ( ! defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Atalho_Comercial_Televenda extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get()
    {
        return $this->db
            ->get("atalho_comercial_televenda")
            ->result();
    }

    public function alterarEmLote($dados)
    {
        @$this->db->update_batch('atalho_comercial_televenda', $dados, 'id');
    }
}