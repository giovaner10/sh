<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class ComerciaisTelevendasInfoGerais extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('mapa_calor');
		$this->load->model('dica_venda', 'dicaVenda');
		$this->load->model('regulamento_campanha_venda', 'regulamentoCampanhaVenda');
		$this->load->model('atalho_comercial_televenda', 'atalhoComercialTelevenda');
		
        $this->load->helper('upload');
		$this->uploadHelper = new Upload_Helper();

        # Models in model
		$this->load->model('arquivo');
		
		$this->extensoesPermitidasDicasVendas = 'pdf';
    }

    public function index()
    {
        $dados['titulo'] = lang('comercial_e_televendas');
		$dados['atalhos'] = $this->atalhoComercialTelevenda->get();
        $this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/ComerciaisTelevendasInfoGerais'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/informacao_geral/index');
        $this->load->view('fix/footer_NS');
    }

    public function getDicasVendas()
    {
        $dicasVendas = $this->dicaVenda->get();
		
		$data = [];
		$x = 0;
		
		foreach ($dicasVendas as $dicaVenda)
        {
			$data[$x] =
			[
				$dicaVenda->id,
				$dicaVenda->descricao,
				[
					'id' => $dicaVenda->id, # Editar e excluir
					'arquivo' => $dicaVenda->file # Visualizar
				]
			];

			$x++;
		}

		echo json_encode(['data' => $data]);
    }

    public function formularioDicaVenda($dicaVendaId = null)
	{
		$dados = [];

		if ($dicaVendaId)
		{
			$dados['modalTitulo'] = lang('editar_dica');
			$dados['dicaVenda'] = $this->dicaVenda->getPorId($dicaVendaId);
		}
		else
			$dados['modalTitulo'] = lang('nova_dica');

		$this->load->view('comercial_televenda/informacao_geral/dica_venda', $dados);
	}

	public function adicionarDicaVenda()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidasDicasVendas, 'arquivo');

			$descricao = $this->input->post('descricao');

			$arquivoDados = $this->uploadHelper->upload(
				'./uploads/dicas_vendas', 'arquivo', $this->extensoesPermitidasDicasVendas
			);

			$arquivoNome = $arquivoDados['file_name'];
			$arquivo = $arquivoDados['full_path'];

			# Adiciona dica de venda
			$this->dicaVenda->adicionar($descricao, $arquivoNome, $arquivo);

			echo json_encode([
				'status' => 1,
				'mensagem' => lang('mensagem_sucesso')
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				'status' => 0,
				'mensagem' => $e->getMessage()
			]);
		}
	}

	public function editarDicaVenda($dicaVendaId)
	{
		try
		{
			$descricao = $this->input->post('descricao');
			$arquivo = '';
			$arquivoNome = '';

			# Altera arquivo
			if ($_FILES['arquivo']['name'] != '')
			{
	            # valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasDicasVendas, 'arquivo');

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/dicas_vendas', 'arquivo', $this->extensoesPermitidasDicasVendas
				);
			
				$arquivo = $arquivoDados['full_path'];
				$arquivoNome = $arquivoDados['file_name'];
			}
			
			# Altera dica de venda
			$this->dicaVenda->editar(
				$dicaVendaId, $descricao, $arquivo, $arquivoNome
			);

			echo json_encode([
				'status' => 1,
				'mensagem' => lang('mensagem_sucesso')
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				'status' => 0,
				'mensagem' => $e->getMessage()
			]);
		}
	}

	public function excluirDicaVenda($dicaVendaId)
	{
		try
		{
			# Inativa a dica de venda
			$this->dicaVenda->inativar($dicaVendaId);

			echo json_encode([
				'status' => 1,
				'mensagem' => lang('mensagem_sucesso')
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				'status' => 0,
				'mensagem' => $e->getMessage()
			]);
		}
	}

    public function getRegulamentosCampanhaVendas()
    {
        $regulamentos = $this->regulamentoCampanhaVenda->get();
		
		$data = [];
		$x = 0;
		
		foreach ($regulamentos as $regulamento)
        {
			$data[$x] =
			[
				$regulamento->id,
				$regulamento->descricao,
				[
					'id' => $regulamento->id, # Editar e excluir
					'arquivo' => $regulamento->file # Visualizar
				]
			];

			$x++;
		}

		echo json_encode(['data' => $data]);
    }

	public function formularioRegulamentoCampanhaVenda($id = null)
	{
		$dados = [];

		if ($id)
		{
			$dados['modalTitulo'] = lang('editar_regulamento');
			$dados['regulamentoCampanhaVenda'] = $this->regulamentoCampanhaVenda->getPorId($id);
		}
		else
			$dados['modalTitulo'] = lang('novo_regulamento');

		$this->load->view('comercial_televenda/informacao_geral/regulamento_campanha_venda', $dados);
	}

	public function adicionarRegulamentoCampanhaVenda()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidasDicasVendas, 'arquivo');

			$descricao = $this->input->post('descricao');

			$arquivoDados = $this->uploadHelper->upload(
				'./uploads/regulamentos_campanha_vendas', 'arquivo', $this->extensoesPermitidasDicasVendas
			);

			$arquivoNome = $arquivoDados['file_name'];
			$arquivo = $arquivoDados['full_path'];

			# Adiciona dica de venda
			$this->regulamentoCampanhaVenda->adicionar($descricao, $arquivoNome, $arquivo);

			echo json_encode([
				'status' => 1,
				'mensagem' => lang('mensagem_sucesso')
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				'status' => 0,
				'mensagem' => $e->getMessage()
			]);
		}
	}

	public function editarRegulamentoCampanhaVenda($id)
	{
		try
		{
			$descricao = $this->input->post('descricao');
			$arquivo = '';
			$arquivoNome = '';

			# Altera arquivo
			if ($_FILES['arquivo']['name'] != '')
			{
	            # valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasDicasVendas, 'arquivo');

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/regulamentos_campanha_vendas', 'arquivo', $this->extensoesPermitidasDicasVendas
				);
			
				$arquivo = $arquivoDados['full_path'];
				$arquivoNome = $arquivoDados['file_name'];
			}
			
			# Altera dica de venda
			$this->regulamentoCampanhaVenda->editar(
				$id, $descricao, $arquivo, $arquivoNome
			);

			echo json_encode([
				'status' => 1,
				'mensagem' => lang('mensagem_sucesso')
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				'status' => 0,
				'mensagem' => $e->getMessage()
			]);
		}
	}

	public function excluirRegulamentoCampanhaVenda($id)
	{
		try
		{
			# Inativa a dica de venda
			$this->regulamentoCampanhaVenda->inativar($id);

			echo json_encode([
				'status' => 1,
				'mensagem' => lang('mensagem_sucesso')
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				'status' => 0,
				'mensagem' => $e->getMessage()
			]);
		}
	}
	
	public function formularioAtalhosComercialTelevendas()
	{
		$dados['modalTitulo'] = lang('editar_atalhos');
		$dados['atalhos'] = $this->atalhoComercialTelevenda->get();

		$this->load->view('comercial_televenda/informacao_geral/atalhos_comercial_televendas', $dados);
	}

	public function alterarAtalhosComercialTelevendas()
	{
		try
		{
			$dados = $this->input->post();

			$atalhos = $this->atalhoComercialTelevenda->get();

			if (!$atalhos) 
				throw new Exception();

			$atalhos[0]->url = $dados['tabelaDePrecos'];
			$atalhos[1]->url = $dados['imagensParaWhatsapp'];

			$this->atalhoComercialTelevenda->alterarEmLote($atalhos);
			
			echo json_encode([
				'status' => 1,
				'mensagem' => lang('mensagem_sucesso'),
				'dados' => [
					'tabelaDePrecos' => $dados['tabelaDePrecos'],
					'imagensParaWhatsapp' => $dados['imagensParaWhatsapp']
				]
			]);
		}
		catch (Exception $e)
		{
			echo json_encode([
				'status' => 0,
				'mensagem' => $e->getMessage() ? : lang('mensagem_erro')
			]);
		}
	}
}