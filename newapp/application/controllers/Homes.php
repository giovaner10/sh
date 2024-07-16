<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Homes extends CI_Controller
{	
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		
		$this->load->model('comunicado');
		$this->load->model('release');
        $this->load->model('arquivo');
        $this->load->model('usuario');
		$this->load->model('arquivo');
        $this->load->model('menu');
        $this->load->model('atalho_usuario', 'atalhoUsuario');

		$this->load->helper("data");

		$this->usuarioId = $this->auth->get_login_dados('user');
	}

	public function index() {
		$this->auth->is_logged_api_shownet();

		$dados["load"] = ["jquery-ui", "css-new-style"];
    	$dados['titulo'] = 'Home';

		$atalhosUsuario = $this->atalhoUsuario->getComMenu(
			['id_usuario' => $this->usuarioId] # Where
		);

		# Se o usuário não tiver atalhos são selecionados os atalhos padrões
		if (!$atalhosUsuario || count($atalhosUsuario) == 0)
			$atalhosUsuario = $this->getAtalhosPadrao();

		$dados['atalhosUsuario'] = $this->formataAtalhosDashbord($atalhosUsuario);
		
		$dados['comunicados'] = $this->comunicado->getComunicados();
		$dados['releases'] = $this->release->getReleases();

        $dados['banners'] = $this->arquivo->getArquivo("pasta = 'banners'");

		$dados['aniversariantes'] = [];
		$aniversariantes = $this->usuario->listarFuncionarios(
			'nome, ocupacao, data_nasc, id_arquivos',
			'status = 1 AND Month(data_nasc) = Month(NOW()) AND Day(data_nasc) = Day(NOW())'
		);
		
		if ($aniversariantes) {
			foreach ($aniversariantes as $aniversariante) {
				if (isset($aniversariante->id_arquivos)) {
					$file = $this->arquivo->getArquivos(['id' => $aniversariante->id_arquivos]);
					$file = $file[0];

					if ($file) {
						if (file_exists('./uploads/perfil_usuarios/' . $file->file)) {
							$aniversariante->file = $file->file;
						}
					}
				}
			}

			$dados['aniversariantes'] = $aniversariantes;
		}

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('new_views/home/index');
		$this->load->view('fix/footer_NS');
	}

	public function getMenus()
	{
		$permissaoCodigos = $this->session->userdata('log_admin')['permissoes'];

		// Menus de atalhos
		$menusFilhos = $this->menu->get(
			"filhos = 'nao' AND id_pai IS NOT NULL AND status = 'ativo'", # Where
			['codigo_permissao' => $permissaoCodigos]
		);

		echo json_encode(['data' => $this->formataMenusListagem($menusFilhos)]);
	}

	public function acessoRapidoModal()
	{
		$dados['modalTitulo'] = lang('acesso_rapido');

		$atalhosUsuario = $this->atalhoUsuario->getComMenu(
			['id_usuario' => $this->usuarioId] # Where
		);

		# Se o usuário não tiver atalhos é selecionado os atalhos padrões
		if (!$atalhosUsuario || count($atalhosUsuario) == 0)
			$atalhosUsuario = $this->getAtalhosPadrao();

		$dados['atalhosUsuario'] = $this->formataAtalhosDashbord($atalhosUsuario);

		$this->load->view('new_views/home/acesso_rapido', $dados);
	}

	public function atalhosUsuarioAtualizar()
	{
		try
		{
			$atalhos = $this->input->post('atalhos');

			if (!$atalhos || count($atalhos) < 6)
				throw new Exception(lang('por_favor_selecione_6_atalhos'));

			$atalhosSalvos = $this->atalhoUsuario->get(
				['id_usuario' => $this->usuarioId], # Where
				true # Retornar array
			);

			$atalhosSalvosMenuIds = array_column($atalhosSalvos, 'id_menu');

			$atalhosAdicaoMenuIds = array_diff($atalhos, $atalhosSalvosMenuIds);
			$this->atalhoUsuario->adicionarEmLote($atalhosAdicaoMenuIds, $this->usuarioId);

			$atalhosExclusaoMenuIds = array_diff($atalhosSalvosMenuIds, $atalhos);
			$this->atalhoUsuario->excluirEmLote($atalhosExclusaoMenuIds, $this->usuarioId);

			$atalhosEdicaoMenuIds = array_intersect($atalhos, $atalhosSalvosMenuIds);
			$atalhosEdicaoDados = array_filter(array_map(
				function ($atalho) use ($atalhosEdicaoMenuIds)
				{
					if (in_array($atalho['id_menu'], $atalhosEdicaoMenuIds))
					{
						// Obtem alteracoes na ordem dos atalhos
						$atalho['ordem'] = array_search($atalho['id_menu'], $atalhosEdicaoMenuIds) + 1;
						return $atalho;
					}
				},
				$atalhosSalvos
			));

			$this->atalhoUsuario->editarEmLote($atalhosEdicaoDados);

			echo json_encode([
                'status'   => 1,
                'mensagem' => lang('mensagem_sucesso')
            ]);
		}
		catch (Exception $e)
		{
			echo json_encode([
                'status'   => 0,
                'mensagem' => $e->getMessage() ? : lang('mensagem_erro')
            ]);
		}
	}

	public function getAtalhosUsuario()
	{
		$atalhosUsuario = $this->atalhoUsuario->getComMenu(
			['id_usuario' => $this->usuarioId] # Where
		);

		$atalhosUsuario = $this->formataAtalhosDashbord($atalhosUsuario);

		echo json_encode($atalhosUsuario);
	}

	private function getAtalhosPadrao()
	{
		$atalhosUsuario = [];
		$permissaoCodigos = $this->session->userdata('log_admin')['permissoes'];

		// Pega os 6 primeiros menus (de atalho) que o usuário possui permissão
		$atalhosPadrao = $this->menu->get(
			"filhos = 'nao' AND id_pai IS NOT NULL AND status = 'ativo'", # Where
			['codigo_permissao' => $permissaoCodigos], # WhereIn
			6 # Limit
		);
		
		foreach ($atalhosPadrao as $atalho)
		{
			$atalhosUsuario[] = (object)[
				'menuId' => $atalho->id,
				'menuNome' => $atalho->nome,
				'menuCaminho' => $atalho->caminho,
				'menuIcone' => $atalho->icone,
				'menuPaiId' => $atalho->id_pai,
				'lang_pt' => $atalho->lang_pt,
				'lang_en' => $atalho->lang_en
			];
		}

		return $atalhosUsuario;
	}

	private function formataMenusListagem($menusFilhos)
	{
		
		$log_admin = $this->session->userdata('log_admin'); 
		$idioma = isset($log_admin['idioma']) ? $log_admin['idioma'] : null ;

		// Todos os menus
		$menus = $this->menu->get();

		// Formata dados
		$menusFormatados = [];
		foreach ($menus as $menu)
			$menusFormatados[$menu->id] = $menu;

		$retorno = [];
		foreach ($menusFilhos as $menu)
		{
			$menuNome = lang($menu->nome);
			
            if(!isset($menuNome) || $menuNome == ""){
                $menuNome = $idioma == "pt-BR" ? $menu->lang_pt : $menu->lang_en;
            }

			$menusAntecessores = '';
			$menuPaiId = $menu->id_pai;
			
			// Trabalha a busca e preenchimento de menus antecessores
			while ($menuPaiId)
			{
				$menuNomePai = lang($menusFormatados[$menuPaiId]->nome);
				
				if(!isset($menuNomePai) || $menuNomePai == ""){
					$menuNomePai = $idioma == "pt-BR" ? $menusFormatados[$menuPaiId]->lang_pt : $menusFormatados[$menuPaiId]->lang_en;
				}

				$menusAntecessores = $menuNomePai . ' > ' . $menusAntecessores;
				$menuPaiId = $menusFormatados[$menuPaiId]->id_pai;
			}

			$retorno[] =
			[
				$menu->id,
				$menusAntecessores . $menuNome,
				$menuNome,
				$menu->icone ? : 'list'
			];
		}

		return $retorno;
	}

	private function formataAtalhosDashbord($atalhos)
	{
		
		$log_admin = $this->session->userdata('log_admin'); 
		$idioma = isset($log_admin['idioma']) ? $log_admin['idioma'] : null ;

		// Todos os menus
		$menus = $this->menu->get();

		// Formata dados
		$menusFormatados = [];
		foreach ($menus as $menu)
			$menusFormatados[$menu->id] = $menu;

		$retorno = [];
		if (!empty($atalhos)) {
			foreach ($atalhos as $atalho) {
				$menuNome = lang($atalho->menuNome);
				
				if(!isset($menuNome) || $menuNome == ""){
					$menuNome = $idioma == "pt-BR" ? $atalho->lang_pt : $atalho->lang_en;
				}
	
				$menusAntecessores = '';
				$menuPaiId = $atalho->menuPaiId;
	
				// Trabalha a busca e preenchimento de menus antecessores
				while ($menuPaiId)
				{			
					$menuNomePai = lang($menusFormatados[$menuPaiId]->nome);
					
					if(!isset($menuNomePai) || $menuNomePai == ""){
						$menuNomePai = $idioma == "pt-BR" ? $menusFormatados[$menuPaiId]->lang_pt : $menusFormatados[$menuPaiId]->lang_en;
					}
	
					$menusAntecessores = $menuNomePai . ' > ' . $menusAntecessores;
	
					$menuPaiId = $menusFormatados[$menuPaiId]->id_pai;
				}
	
				$retorno[] = (object)
				[
					'menuId' => $atalho->menuId,
					'menuNome' => $menuNome,
					'menuNomeCompleto' => $menusAntecessores . $menuNome,
					'menuCaminho' => site_url($atalho->menuCaminho),
					'menuIcone' => $atalho->menuIcone ? : 'list',
					'menuIdPai' => $atalho->menuPaiId
				];
			}
			
		}

		return $retorno;
	}
}