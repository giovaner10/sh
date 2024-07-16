<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class MeuOmnilink extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->auth->is_allowed('vis_marketingmeuomnilink');
        $this->load->model('auth');
		$this->load->model('mapa_calor');
		$this->load->model('meu_omnilink/banner', 'banner');
		$this->load->model('meu_omnilink/ultimas_noticia', 'noticia');

		$this->load->helper("upload");
		$this->uploadHelper = new Upload_Helper();

		$this->extensoesPermitidas = "jpg|pjpeg|jpeg|png|gif|bmp";
	}

	public function index()
    {
		$dados['titulo'] = lang('meu_omnilink');
		$this->mapa_calor->registrar_acessos_url(site_url('/Marketings/MeuOmnilink'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('marketing/meu_omnilink/index');
		$this->load->view('fix/footer_NS');
	}

	public function listarBanners()
	{
		$banners = $this->banner->listar();
		
		$dados = [];
		
		foreach ($banners as $banner)
        {
			array_push($dados, [
				$banner->id,
				$banner->titulo,
				$banner->ordem,
				$banner->exibe_na_home,
				$banner->id # Editar e excluir
			]);
		}

		echo json_encode(["data" => $dados]);
	}

	public function formularioBanner($id = null)
	{
		$dados = [];
        $dados['formatosPermitidos'] = str_replace('|', ' | ', $this->extensoesPermitidas);

		if ($id)
		{
			$dados["modalTitulo"] = lang("editar_banner");
			$dados["banner"] = $this->banner->buscarPorId($id);
		}
		else
			$dados["modalTitulo"] = lang("novo_banner");

		$this->load->view('marketing/meu_omnilink/banners/modal', $dados);
	}

	public function adicionarBanner()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidas, "imagem");

			$usuarioId = $this->auth->get_login_dados('user');
			$arquivoDiretorio = 'meu_omnilink/banners';

			$arquivoDados = $this->uploadHelper->upload(
				"uploads/$arquivoDiretorio", "imagem", $this->extensoesPermitidas
			);

			$dados = $this->input->post();
			$ordemExibicao = $dados['ordem'] ? $dados['ordem'] : 1;

			$banner = [
				'titulo' => $dados['titulo'],
				'conteudo_url' => $dados['conteudo_url'],
				'ordem' => $ordemExibicao,
				'exibe_na_home' => empty($dados['exibe_na_home']) ? 'nao' : 'sim',
				'imagem_nome' => $arquivoDados["file_name"],
				'imagem_diretorio' => $arquivoDiretorio,
				'id_usuario' => $usuarioId
			];

			# Adicionar banner
			$this->banner->adicionar($banner);

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

	public function editarBanner($id)
	{
		try
		{
			# Valida id
			if (!$id)
				throw new Exception(lang("mensagem_erro"));

			$arquivoDiretorio = 'meu_omnilink/banners';
			$arquivoDados = [];
			
			# Altera arquivo
			if ($_FILES["imagem"]["name"] != "")
			{
				# valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidas, "imagem");

				$arquivoDados = $this->uploadHelper->upload(
					"uploads/$arquivoDiretorio",
					"imagem",
					$this->extensoesPermitidas
				);
			
				# Deleta arquivo antigo do servidor
				$dadosAntigoBanner = $this->banner->buscarPorId($id);

				if (file_exists("uploads/$dadosAntigoBanner->imagem_diretorio/$dadosAntigoBanner->imagem_nome"))
                	unlink("uploads/$dadosAntigoBanner->imagem_diretorio/$dadosAntigoBanner->imagem_nome");
			}

			# Dados
			$usuarioId = $this->auth->get_login_dados('user');
			$dados = $this->input->post();
			$ordemExibicao = $dados['ordem'] ? $dados['ordem'] : 1;

			$banner = [
				'titulo' => $dados['titulo'],
				'conteudo_url' => $dados['conteudo_url'],
				'ordem' => $ordemExibicao,
				'exibe_na_home' => empty($dados['exibe_na_home']) ? 'nao' : 'sim',
				'id_usuario' => $usuarioId
			];

			if ($_FILES["imagem"]["name"] != "")
			{
				$banner['imagem_nome'] = $arquivoDados["file_name"];
				$banner['imagem_diretorio'] = $arquivoDiretorio;
			}

			# Altera banner
			$this->banner->editar($id, $banner);

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

	public function excluirBanner($id)
	{
		try
		{
			# Valida id
			if (!$id)
				throw new Exception(lang("mensagem_erro"));
			
			# Inativa o banner
			$this->banner->excluir($id);

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

	public function listarNoticias()
	{
		$noticias = $this->noticia->listar();
		
		$dados = [];
		
		foreach ($noticias as $noticia)
        {
			$dataHoraCadastroFormatado = '';
			if ($noticia->data_hora_cadastro && $noticia->data_hora_cadastro != '0000-00-00 00:00:00')
				$dataHoraCadastroFormatado = date('d/m/Y H:i:s', strtotime($noticia->data_hora_cadastro));

			$noticia->titulo = substr($noticia->titulo, 0, 70) . (strlen($noticia->titulo) > 70 ? "..." : "");
			$noticia->descricao = substr($noticia->descricao, 0, 100) . (strlen($noticia->descricao) > 100 ? "..." : "");
			
			array_push($dados, [
				$noticia->id,
				$noticia->titulo,
				$noticia->descricao,
				$dataHoraCadastroFormatado,
				$noticia->id # Editar e excluir
			]);
		}

		echo json_encode(["data" => $dados]);
	}

	public function formularioNoticia($id = null)
	{
		$dados = [];
        $dados['formatosPermitidos'] = str_replace('|', ' | ', $this->extensoesPermitidas);

		if ($id)
		{
			$dados["modalTitulo"] = lang("editar_noticia");
			$dados["noticia"] = $this->noticia->buscarPorId($id);
		}
		else
			$dados["modalTitulo"] = lang("nova_noticia");

		$this->load->view('marketing/meu_omnilink/ultimas_noticias/modal', $dados);
	}

	public function adicionarNoticia()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidas, "imagem");

			$usuarioId = $this->auth->get_login_dados('user');
			$arquivoDiretorio = 'meu_omnilink/ultimas_noticias';

			$arquivoDados = $this->uploadHelper->upload(
				"uploads/$arquivoDiretorio", "imagem", $this->extensoesPermitidas
			);

			$dados = $this->input->post();

			$noticia = [
				'titulo' => $dados['titulo'],
				'descricao' => $dados['descricao'],
				'conteudo_url' => $dados['conteudo_url'],
				'imagem_nome' => $arquivoDados["file_name"],
				'imagem_diretorio' => $arquivoDiretorio,
				'id_usuario' => $usuarioId
			];

			# Adicionar noticia
			$this->noticia->adicionar($noticia);

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

	public function editarNoticia($id)
	{
		try
		{
			# Valida id
			if (!$id)
				throw new Exception(lang("mensagem_erro"));

			$arquivoDiretorio = 'meu_omnilink/ultimas_noticias';
			$arquivoDados = [];
			
			# Altera arquivo
			if ($_FILES["imagem"]["name"] != "")
			{
				# valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidas, "imagem");

				$arquivoDados = $this->uploadHelper->upload(
					"uploads/$arquivoDiretorio",
					"imagem",
					$this->extensoesPermitidas
				);
			
				# Deleta arquivo antigo do servidor
				$dadosAntigoNoticia = $this->noticia->buscarPorId($id);

				if (file_exists("uploads/$dadosAntigoNoticia->imagem_diretorio/$dadosAntigoNoticia->imagem_nome"))
                	unlink("uploads/$dadosAntigoNoticia->imagem_diretorio/$dadosAntigoNoticia->imagem_nome");
			}

			# Dados
			$usuarioId = $this->auth->get_login_dados('user');
			$dados = $this->input->post();

			$noticia = [
				'titulo' => $dados['titulo'],
				'descricao' => $dados['descricao'],
				'conteudo_url' => $dados['conteudo_url'],
				'id_usuario' => $usuarioId
			];

			if ($_FILES["imagem"]["name"] != "")
			{
				$noticia['imagem_nome'] = $arquivoDados["file_name"];
				$noticia['imagem_diretorio'] = $arquivoDiretorio;
			}

			# Altera noticia
			$this->noticia->editar($id, $noticia);

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

	public function excluirNoticia($id)
	{
		try
		{
			# Valida id
			if (!$id)
				throw new Exception(lang("mensagem_erro"));
			
			# Inativa o noticia
			$this->noticia->excluir($id);

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