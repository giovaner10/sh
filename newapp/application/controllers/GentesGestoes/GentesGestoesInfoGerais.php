<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class GentesGestoesInfoGerais extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('comunicado');
		$this->load->model('parceria');
		$this->load->model('parceria_categoria', 'parceriaCategoria');
		$this->load->model('mapa_calor');
        $this->load->model('auth');
		$this->load->helper("upload");
		$this->uploadHelper = new Upload_Helper();

		# Models in model
		$this->load->model("arquivo");

		# Vars
		$this->extensoesPermitidasParcerias = "jpg|png|jpeg";
		$this->extensoesPermitidasComunicados = "pdf";
	}

	public function index()
    {
		$dados['titulo'] = lang('gente_gestao');
		$dados["categoriasParcerias"] = $this->buscarParceriasPorCaterias();
		$this->mapa_calor->registrar_acessos_url(site_url('/GentesGestoes/GentesGestoesInfoGerais'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('gente_gestao/informacao_geral/index');
		$this->load->view('fix/footer_NS');
	}

	public function getComunicados()
	{
		$comunicados = $this->comunicado->getComunicados();
		
		$data = [];
		$x = 0;
		
		foreach ($comunicados as $comunicado)
        {
			$data[$x] =
			[
				$comunicado->id,
				$comunicado->comunicado,
				[
					'id' => $comunicado->id, # Editar e excluir
					'arquivo' => $comunicado->file # Visualizar
				]
			];

			$x++;
		}

		echo json_encode(["data" => $data]);
	}

	public function formularioComunicado($comunicadoId = null)
	{
		$dados = [];

		if ($comunicadoId)
		{
			$dados["modalTitulo"] = lang("editar_comunicado");
			$dados["comunicado"] = $this->comunicado->getComunicado($comunicadoId);
		}
		else
			$dados["modalTitulo"] = lang("novo_comunicado");

		$this->load->view("gente_gestao/informacao_geral/comunicado", $dados);
	}

	public function adicionarComunicado()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidasComunicados, "arquivo");

			$comunicado = $this->input->post("comunicado");
			$usuarioId = $this->auth->get_login_dados()["user"];

			$arquivoDados = $this->uploadHelper->upload(
				"./uploads/comunicados", "arquivo", $this->extensoesPermitidasComunicados
			);

			$arquivoNome = $arquivoDados["file_name"];
			$arquivo = $arquivoDados["full_path"];

			# Adiciona comunicado
			$this->comunicado->adicionarComunicado($comunicado, $arquivoNome, $arquivo, $usuarioId);

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

	public function editarComunicado($comunicadoId)
	{
		try
		{
			$comunicado = $this->input->post("comunicado");
			$usuarioId = $this->auth->get_login_dados()["user"];
			$arquivo = "";
			$arquivoNome = "";

			# Altera arquivo
			if ($_FILES["arquivo"]["name"] != "")
			{
	            # valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasComunicados, "arquivo");

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/comunicados', "arquivo", $this->extensoesPermitidasComunicados
				);
			
				$arquivo = $arquivoDados["full_path"];
				$arquivoNome = $arquivoDados["file_name"];
			}
			
			# Altera comunicado
			$this->comunicado->editarComunicado(
				$comunicadoId, $comunicado, $usuarioId, $arquivo, $arquivoNome
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

	public function excluirComunicado($comunicadoId)
	{
		try
		{
			# inativa o comunicado
			$this->comunicado->excluirComunicado($comunicadoId);

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

	# Carrega view - Formulário de administração de parcerias
    public function administrarParcerias()
    {
        $dados["modalTitulo"] = lang("administrar_parcerias");
		$this->load->view("gente_gestao/informacao_geral/parcerias_adm", $dados);
    }

	public function listarParcerias()
    {	
        $parcerias = $this->parceria->get();

        $data = [];
        $x = 0;
        foreach ($parcerias as $parceria)
        {
            $data[$x] =
            [
                $parceria->file,
                $parceria->categoria,
                $parceria->descricao,
                $parceria->link,
                $parceria->id
            ];
            $x++;
        }

		echo json_encode(["data" => $data]);
    }

    public function formularioParceria($parceriaId = null)
	{
		$dados = [];
		$dados["parceriasCategorias"] = $this->parceriaCategoria->get();

		if ($parceriaId)
		{
			$dados["modalTitulo"] = lang("editar_parceria");
			$dados["parceria"] = $this->parceria->getParceria($parceriaId);
		}
		else
			$dados["modalTitulo"] = lang("nova_parceria");

		$this->load->view("gente_gestao/informacao_geral/parceria", $dados);
	}

	public function adicionarParceria()
	{
		try
		{
			# dados
			$descricao = $this->input->post("descricao");
			$categoriaId = $this->input->post("categoriaId");
			$link = $this->input->post("link");
			$arquivo = "";
			$arquivoNome = "";

			if ($_FILES["foto_capa"]["name"] != "")
			{
				# valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasParcerias, "foto_capa");
				
				# salvar arquivo no servidor
				$arquivoDados = $this->uploadHelper->upload(
					"./uploads/gente_gestao/desenv_organizagional/parcerias",
					"foto_capa",
					$this->extensoesPermitidasParcerias
				);

				$arquivoNome = $arquivoDados["file_name"];
				$arquivo = $arquivoDados["full_path"];
			}

			# Adiciona parceria
			$this->parceria->adicionar(
            [
                'arquivoNome' => $arquivoNome,
                'categoriaId' => $categoriaId,
                'descricao' => $descricao,
                'arquivo' => $arquivo,
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

	public function editarParceria($treinamentoEadId)
	{
		try
		{
			# valida id
			if (!$treinamentoEadId)
				throw new Exception(lang("mensagem_erro"));
				
			# dados
			$descricao = $this->input->post("descricao");
			$categoriaId = $this->input->post("categoriaId");
			$arquivoId = $this->input->post("arquivoId");
			$link = $this->input->post("link");
			$arquivo = "";
			$arquivoNome = "";

			# Altera arquivo
			if ($_FILES["foto_capa"]["name"] != "")
			{
				# valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasParcerias, "foto_capa");

				$arquivoDados = $this->uploadHelper->upload(
					"./uploads/gente_gestao/desenv_organizagional/parcerias",
					"foto_capa",
					$this->extensoesPermitidasParcerias
				);
			
				$arquivo = $arquivoDados["full_path"];
				$arquivoNome = $arquivoDados["file_name"];
			}

			# Altera treinamento ead
			$this->parceria->editar(
				[
					"arquivoNome" => $arquivoNome,
					"categoriaId" => $categoriaId,
					"arquivoId" => $arquivoId,
					"descricao" => $descricao,
					"arquivo" => $arquivo,
					"link" => $link
				],
				$treinamentoEadId # where
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

	public function excluirParceria($parceriaId)
	{
		try
		{
			# valida id
			if (!$parceriaId)
				throw new Exception(lang("mensagem_erro"));
			
			# inativa a parceria
			$this->parceria->excluir($parceriaId);

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

	public function atualizarListagemParcerias()
	{
		$dados["categoriasParcerias"] = $this->buscarParceriasPorCaterias();

		$this->load->view("gente_gestao/informacao_geral/parcerias", $dados);
	}

	private function buscarParceriasPorCaterias()
	{
		$parcerias = $this->parceria->get([], "categorias.ordem ASC, parcerias.id ASC");

		# Agrupa as parcerias por categoria para a exibicao
		$categoriasParcerias = [];
		foreach ($parcerias as $parceria)
		{
			$categoriasParcerias[$parceria->id_categoria]["categoriaNome"] = $parceria->categoria;
			$categoriasParcerias[$parceria->id_categoria]["parcerias"][] = $parceria;
		}
		
		return $categoriasParcerias;
	}

	public function listarParceriasCategorias()
    {	
        $parceriasCategorias = $this->parceriaCategoria->get();

        $data = [];
        $x = 0;
        foreach ($parceriasCategorias as $categoria)
        {
            $data[$x] =
            [
                $categoria->ordem,
                $categoria->nome,
                $categoria->id
            ];
            $x++;
        }

		echo json_encode(["data" => $data]);
    }

	public function formularioParceriaCategoria($categoriaId = null)
	{
		$dados = [];

		if ($categoriaId)
		{
			$dados["modalTitulo"] = lang("editar_parceria");
			$dados["categoria"] = $this->parceriaCategoria->getCategoria($categoriaId);
		}
		else
			$dados["modalTitulo"] = lang("nova_categoria");

		$this->load->view("gente_gestao/informacao_geral/parceria_categoria", $dados);
	}

	public function adicionarParceriaCategoria()
	{
		try
		{
			# dados
			$nome = $this->input->post("nome");
			$ordem = $this->input->post("ordem");

			# Adiciona categoria
			$this->parceriaCategoria->adicionar(
            [
                'nome' => $nome,
                'ordem' => $ordem,
                'status' => "ativo"
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

	public function editarParceriaCategoria($categoriaId)
	{
		try
		{
			# Valida id
			if (!$categoriaId)
				throw new Exception(lang("mensagem_erro"));
				
			# Dados
			$nome = $this->input->post("nome");
			$ordem = $this->input->post("ordem");

			# Altera categoria
			$this->parceriaCategoria->editar(
            	[
					'nome' => $nome,
					'ordem' => $ordem
				],
				$categoriaId # Where
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

	public function excluirParceriaCategoria($categoriaId)
	{
		try
		{
			# valida id
			if (!$categoriaId)
				throw new Exception(lang("mensagem_erro"));
			
			# inativa a parceria
			$this->parceriaCategoria->excluir($categoriaId);

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
}