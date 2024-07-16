<?php if ( ! defined("BASEPATH")) exit(lang("nenhum_acesso_direto_script_permitido")); 

class Menu extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Undocumented function
	 *
	 * @param array $where
	 * @param array $whereIn ('coluna' => ['valor', 'valor'])
	 * @return array
	 */
    public function get($where = [], $whereIn = [], $limit = null)
    {
		if ($where)
			$this->db->where($where);
		if ($whereIn)
			$this->db->where_in(key($whereIn), $whereIn[key($whereIn)]);
		if ($limit)
			$this->db->limit($limit);

	    return $this->db->get("menu")->result();
    }
			
	# Função para construção dos menus com hierarquia
	public function getComHierarquia()
	{
		$log_admin = $this->session->userdata('log_admin');
		$permissoes = '';
		
		if(isset($log_admin['permissoes']) && is_array($log_admin['permissoes'])) {
			$permissoes = implode("','", $log_admin['permissoes']);
		}

		$menus = $this->db
			->where("status = 'ativo' AND codigo_permissao IN('$permissoes')")
			->order_by('ordem', 'ASC')
			->get("menu")
			->result_array();

		$menusFormatados = [];
		$this->construirMenuComHierarquia($menus, $menusFormatados, null);

		return $menusFormatados;
	}

	# Função de apoio para construção dos menus de forma recursiva
	private function construirMenuComHierarquia($menus, &$menusFormatados, $menuPaiId = null, $nivel = 0)
	{
		# Passando por todos os menus
		foreach ($menus as $menu)
		{
			# Se for um menu filho do menu superior que estamos procurando
			if ($menu['id_pai'] == $menuPaiId)
			{
				# Armazenando no menu final
				$menusFormatados[] = $menu;
			}
		}
		# Incrementando nível
		$nivel++;
		
		# Passando pelos menus desse nível
		for ($i = 0; $i < count($menusFormatados); $i++)
		{
			# Inicializando indices
			$menusFormatados[$i]['sub_menus'] = [];
			$menusFormatados[$i]['nivel'] = $nivel;

			# Chamando a função novamente para construção dos submenus
			$this->construirMenuComHierarquia($menus, $menusFormatados[$i]['sub_menus'], $menusFormatados[$i]['id'], $nivel);
		}
	}

	public function adicionar($menu)
    {
        if (!$this->db->insert("menu", $menu))
            throw new Exception(lang("mensagem_erro"));
		
		return $this->db->insert_id();
    }

	public function editar($id, $menu)
	{
		if (!$this->db->update("menu",
                $menu, ["id" => $id])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
	}

	public function excluir($id)
    {
        if (!$this->db->update("menu",
                ["status" => "inativo"], ["id" => $id])
        )
        {
            throw new Exception(lang("mensagem_erro"));
        }
    }

	public function verificaPermissao(){
		@session_start();

		$this->load->model('usuario');

		$email = $this->auth->get_login_dados('email');
		
		$verifica = $this->db->query("SELECT permissoes FROM usuario WHERE login = '$email'");

		if ($verifica->row()->permissoes != null) {
			return true;
		}else{
			return false;
		}
		//dd('cargo:'.$verifica->row()->cargo);

		

	}

}