<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class DesenvolvimentosOrganizacionais extends CI_Controller
{
    public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged("admin");

        $this->load->model("arquivo");
        $this->load->model("usuario");
        $this->load->model("atividade");
        $this->pastaTreinamentos = "gente_gestao/desenv_organizagional/treinamentos";
		$this->load->model('mapa_calor');
        $this->load->model('auth');
		$this->load->helper("data");
		$this->load->helper("upload");
		$this->uploadHelper = new Upload_Helper();

		# Vars
		$this->extensoesPermitidasTreinamentosEad = "jpg|png|jpeg";
	}
	
    public function index()
    {
        $dados["titulo"] = lang("desenvolvimento_organizacional");

		# Treinamentos Ead
		$treinamentosEad = $this->buscarTreinamentosEadPorGrupos();
		
        $dados["treinamentosEad"]["treinamentos"] = $treinamentosEad["treinamentos"];
        $dados["treinamentosEad"]["videoaulas"] = $treinamentosEad["videoaulas"];
        $dados["treinamentosEad"]["sites"] = $treinamentosEad["sites"];
		$this->mapa_calor->registrar_acessos_url(site_url('/GentesGestoes/DesenvolvimentosOrganizacionais'));
		$this->load->view("fix/header_NS", $dados);
		$this->load->view("gente_gestao/desenvolvimento_organizacional/index");
		$this->load->view("fix/footer_NS");
    }

    # Carrega view - Formulário de administração de treinamentos ead
    public function administrarTreinamentosEad()
    {
        $dados["modalTitulo"] = lang("treinamentos_ead");
		$this->load->view("gente_gestao/desenvolvimento_organizacional/treinamentos_ead_adm", $dados);
    }

    public function listarTreinamentosEad()
    {
        $treinamentosOnline = $this->arquivo->getArquivos([
            "pasta" => $this->pastaTreinamentos
        ]);

        $data = [];
        $x = 0;
        foreach ($treinamentosOnline as $treinamentoOnline)
        {
            $data[$x] =
            [
                $treinamentoOnline->file,
                $treinamentoOnline->tipo,
                $treinamentoOnline->descricao,
                $treinamentoOnline->link,
                $treinamentoOnline->id
            ];
            $x++;
        }

		echo json_encode(["data" => $data]);
    }

    public function formularioTreinamentoEad($treinamentoEadId = null)
	{
		$dados = [];

		if ($treinamentoEadId)
		{
			$dados["modalTitulo"] = lang("editar_treinamento_ead");
			$dados["treinamentoEad"] = $this->arquivo->getArquivos([
                "id" => $treinamentoEadId
            ])[0];
		}
		else
			$dados["modalTitulo"] = lang("novo_treinamento_ead");

		$this->load->view("gente_gestao/desenvolvimento_organizacional/treinamento_ead", $dados);
	}

	public function adicionarTreinamentoEad()
	{
		try
		{
			# dados
			$descricao = $this->input->post("descricao");
			$tipo = $this->input->post("tipo");
			$link = $this->input->post("link");
			$arquivo = "";
			$arquivoNome = "";

			if ($_FILES["foto_capa"]["name"] != "")
			{
				# Valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasTreinamentosEad, "foto_capa");
				
				# Salvar arquivo no servidor
				$arquivoDados = $this->uploadHelper->upload(
					"./uploads/gente_gestao/desenv_organizagional/treinamentos",
					"foto_capa",
					$this->extensoesPermitidasTreinamentosEad
				);

				$arquivoNome = $arquivoDados["file_name"];
				$arquivo = $arquivoDados["full_path"];
			}
			else if ($tipo == "sites")
			{
				$arquivoNome = "sites.png";
			}

			# Adiciona treinamento ead
			$this->arquivo->adicionar(
            [
                'file' => $arquivoNome,
                'descricao' => $descricao,
                'pasta' => 'gente_gestao/desenv_organizagional/treinamentos',
                'ndoc' => '',
                'path' => $arquivo,
                'tipo' => $tipo,
                'link' => $link
            ]);

			echo json_encode([
				"status" => 1,
				"mensagem" => lang("mensagem_sucesso")
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				"status" => 0,
				"mensagem" => $e->getMessage()
			]);
		}
	}

	public function editarTreinamentoEad($treinamentoEadId)
	{
		try
		{
			# valida id
			if (!$treinamentoEadId)
				throw new Exception(lang("mensagem_erro"));
				
			$dados = $this->input->post();
			$arquivo = "";
			$arquivoNome = "";

			# Altera arquivo
			if ($_FILES["foto_capa"]["name"] != "")
			{
				# Apaga o arquivo antigo
				if (file_exists($dados["path"]))
					unlink($dados["path"]);

				# Valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasTreinamentosEad, "foto_capa");

				$arquivoDados = $this->uploadHelper->upload(
					"./uploads/gente_gestao/desenv_organizagional/treinamentos",
					"foto_capa",
					$this->extensoesPermitidasTreinamentosEad
				);
			
				$arquivo = $arquivoDados["full_path"];
				$arquivoNome = $arquivoDados["file_name"];
			}
			else if ($dados["tipo"] == "sites")
			{
				$arquivoNome = "sites.png";
			}

			# Altera treinamento ead
			$this->arquivo->editar(
				[
					"descricao" => $dados["descricao"],
					"tipo" => $dados["tipo"],
					"link" => $dados["link"],
					"file" => $arquivoNome,
					"path" => $arquivo,
					"pasta" => "gente_gestao/desenv_organizagional/treinamentos"
				],
				["id" => $treinamentoEadId] # where
			);

			echo json_encode([
				"status" => 1,
				"mensagem" => lang("mensagem_sucesso")
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				"status" => 0,
				"mensagem" => $e->getMessage()
			]);
		}
	}

	public function excluirTreinamentoEad($treinamentoEadId)
	{
		try
		{
			# valida id
			if (!$treinamentoEadId)
				throw new Exception(lang("mensagem_erro"));

			# get treinamento ead
			$treinamentoEad = $this->arquivo->getArquivos([
				"id" => $treinamentoEadId
			])[0];

			# tipo site não armazena arquivo no servidor
			if ($treinamentoEad->tipo != "sites")
			{
				# apaga o arquivo
				if(file_exists($treinamentoEad->path))
					unlink($treinamentoEad->path);
			}
			
			# exclui o arquivo
			$this->arquivo->excluir($treinamentoEadId);

			echo json_encode([
				"status" => 1,
				"mensagem" => lang("mensagem_sucesso")
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				"status" => 0,
				"mensagem" => $e->getMessage()
			]);
		}
	}

	public function atualizarListagemTreinamentosEad()
	{
		$treinamentosEad = $this->buscarTreinamentosEadPorGrupos();

        $dados["treinamentosEad"]["treinamentos"] = $treinamentosEad["treinamentos"];
        $dados["treinamentosEad"]["videoaulas"] = $treinamentosEad["videoaulas"];
        $dados["treinamentosEad"]["sites"] = $treinamentosEad["sites"];

		$this->load->view("gente_gestao/desenvolvimento_organizacional/treinamentos_ead", $dados);
	}

	private function buscarTreinamentosEadPorGrupos()
	{
		$treinamentos = $this->arquivo->getArquivos([
            "pasta" => $this->pastaTreinamentos,
            "tipo" => "online"
        ]);

        $videoaulas = $this->arquivo->getArquivos([
            "pasta" => $this->pastaTreinamentos,
            "tipo" => "videos"
        ]);

        $sites = $this->arquivo->getArquivos([
            "pasta" => $this->pastaTreinamentos,
            "tipo" => "sites"
        ]);

		return [
			"treinamentos" => $treinamentos,
			"videoaulas" => $videoaulas,
			"sites" => $sites
		];
	}

	public function listarTreinamentos($usuarioId = null)
	{
		# se nao tiver filtro e tiver permissao de adm - lista todos os funcionarios
		if (!$usuarioId && $this->auth->is_allowed_block('cad_atividades'))
			$usuarioId = null;
		# se nao tiver filtro, recebe o id do usuario
		elseif (!$usuarioId)
			$usuarioId = $this->auth->get_login_dados()["user"];

		# Meus treinamentos
		$meusTreinamentos = $this->atividade->getAtividades($usuarioId);

		$data = [];
		$x = 0;
		foreach ($meusTreinamentos as $treinamento)
		{
			$data[$x] =
			[
				$treinamento->curso,
				ucfirst($treinamento->tipo),
				dataFormatar($treinamento->data_inicio),
				dataFormatar($treinamento->data_fim),
				$treinamento->carga_hr,
				ucfirst($treinamento->status),
				$treinamento->id
			];

			# add funcionario na primeira posição do array
			if ($this->auth->is_allowed_block('cad_atividades'))
				array_unshift($data[$x], ucwords(strtolower($treinamento->funcionario)));

			$x++;
		}

		echo json_encode(["data" => $data]);
	}

	public function formularioTreinamento($treinamentoId = null)
	{
		$dados = [];

		$dados["funcionarios"] = $this->usuario->listarFuncionarios(
			"id, nome", # select
			["status" => 1], # where
			"nome ASC" # order
		);

		if ($treinamentoId)
		{
			$dados["modalTitulo"] = lang("editar_treinamento");
			$dados["treinamento"] = $this->atividade->getAtividade($treinamentoId);
		}
		else
			$dados["modalTitulo"] = lang("novo_treinamento");

		$this->load->view("gente_gestao/desenvolvimento_organizacional/meu_treinamento", $dados);
	}

	public function adicionarTreinamento()
	{
		try
		{
			$dados = $this->input->post();
			$funcionarioId = empty($dados["funcionario"])
				? $this->auth->get_login_dados()["user"]
				: $dados["funcionario"];

			# Adiciona treinamento
			$this->atividade->adicionar(
            [
                'id_usuario' => $funcionarioId,
                'curso' => $dados['descricao'],
                'tipo' => $dados['tipo'],
                'data_inicio' => $dados['inicio'],
                'data_fim' => $dados['termino'],
                'carga_hr' => $dados['carga_hr'],
                'status' => $dados['status']
            ]);

			echo json_encode([
				"status" => 1,
				"mensagem" => lang("mensagem_sucesso")
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				"status" => 0,
				"mensagem" => $e->getMessage()
			]);
		}
	}

	public function editarTreinamento($treinamentoId)
	{
		try
		{
			# valida id
			if (!$treinamentoId)
				throw new Exception(lang("mensagem_erro"));
				
			$dados = $this->input->post();
			$funcionarioId = empty($dados["funcionario"])
				? $this->auth->get_login_dados()["user"]
				: $dados["funcionario"];

			# Altera treinamento
			$this->atividade->editar(
				$treinamentoId,
				[
					'id_usuario' => $funcionarioId,
					'curso' => $dados['descricao'],
					'tipo' => $dados['tipo'],
					'data_inicio' => $dados['inicio'],
					'data_fim' => $dados['termino'],
					'carga_hr' => $dados['carga_hr'],
					'status' => $dados['status']
				]
			);

			echo json_encode([
				"status" => 1,
				"mensagem" => lang("mensagem_sucesso")
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				"status" => 0,
				"mensagem" => $e->getMessage()
			]);
		}
	}

	public function excluirTreinamento($treinamentoId)
	{
		try
		{
			# valida id
			if (!$treinamentoId)
				throw new Exception(lang("mensagem_erro"));
			
			# inativa o comunicado
			$this->atividade->excluir($treinamentoId);

			echo json_encode([
				"status" => 1,
				"mensagem" => lang("mensagem_sucesso")
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				"status" => 0,
				"mensagem" => $e->getMessage()
			]);
		}
	}

	public function listarFuncionarios()
	{
		try
		{
			$funcionarios = $this->usuario->listarFuncionarios(
				"id, nome", # select
				["status" => 1], # where
				"nome ASC" # order
			);

			echo json_encode([
				"status" => 1,
				"dados" => $funcionarios
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				"status" => 0,
				"mensagem" => $e->getMessage()
			]);
		}
	}
}