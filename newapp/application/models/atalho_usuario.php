<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Atalho_Usuario extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get($where = [], $retornarArray = false)
    {
        $consulta = $this->db
            ->where('status', 'ativo')
            ->where($where)
            ->order_by('ordem')
            ->get('atalho_usuario');
        
        if (!$retornarArray)
            return $consulta->result();

        return $consulta->result_array();
    }

    public function getComMenu($where = [])
    {
	    return $this->db
            ->select('
                atalho_usuario.*,
                menu.id as menuId,
                menu.nome as menuNome,
                menu.icone as menuIcone,
                menu.caminho as menuCaminho,
                menu.id_pai as menuPaiId,
                menu.lang_pt as lang_pt,
                menu.lang_en as lang_en
            ')
            ->from('atalho_usuario')
            ->where(['atalho_usuario.status' => 'ativo', 'menu.status' => 'ativo'])
            ->where($where)
            ->join('menu', 'menu.id = atalho_usuario.id_menu')
            ->order_by('atalho_usuario.ordem')
            ->get()
            ->result();
    }

    public function adicionarEmLote($menuIds, $usuarioId)
    {
        if (!$menuIds)
            return false;

		$atalhos = [];
		foreach ($menuIds as $index => $menuId)
		{
			$atalhos[] = [
				"id_usuario" => $usuarioId,
				"id_menu" => $menuId,
				"ordem" => $index + 1
			];
		}
			
        if (!$this->db->insert_batch('atalho_usuario', $atalhos))
            throw new Exception();
    }

    public function editarEmLote($atalhos)
    {
        if (!$atalhos)
            return false;

        @$this->db->update_batch('atalho_usuario', $atalhos, 'id');
    }

    public function excluirEmLote($menuIds, $usuarioId)
    {
        if (!$menuIds)
            return false;

        $consulta = $this->db
            ->where(['status' => 'ativo', 'id_usuario' => $usuarioId])
            ->where_in('id_menu', $menuIds)
            ->get('atalho_usuario');
            

        if ($consulta->num_rows() == 0)
            return false;

        $atalhosExclusao = $consulta->result();

        foreach ($atalhosExclusao as $atalho)
        {
            $atalho->status = 'inativo';
            $atalho->data_exclusao = date('Y-m-d H:i:s');
        }

        @$this->db->update_batch('atalho_usuario', $atalhosExclusao, 'id');
    }
}