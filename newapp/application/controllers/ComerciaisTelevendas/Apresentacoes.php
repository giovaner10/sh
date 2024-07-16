<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Apresentacoes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
		$this->load->model('mapa_calor');
		$this->load->model('apresentacao');
		
        $this->load->helper('upload');
		$this->uploadHelper = new Upload_Helper();

        # Models in model
		$this->load->model('arquivo');

		$this->extensoesPermitidas = 'pdf|ppt|pptx';
    }

    public function index()
    {
        $dados['titulo'] = lang('apresentacoes');
		$this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas_Apresentação'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/apresentacao/index');
        $this->load->view('fix/footer_NS');
    }

    public function get()
    {
        $apresentacoes = $this->apresentacao->get();
		
		$data = [];
		$x = 0;
		
		foreach ($apresentacoes as $apresentacao)
        {
			$data[$x] =
			[
				$apresentacao->id,
				$apresentacao->descricao,
				[ # Ações
					'id' => $apresentacao->id,
					'arquivo' => $apresentacao->file
				]
			];

			$x++;
		}

		echo json_encode(['data' => $data]);
    }

    public function formulario($id = null)
	{
		$dados['extensoesPermitidas'] = str_replace('|', ' | ', $this->extensoesPermitidas);

		if ($id)
		{
			$dados['modalTitulo'] = lang('editar_apresentacao');
			$dados['apresentacao'] = $this->apresentacao->getPorId($id);
		}
		else
			$dados['modalTitulo'] = lang('nova_apresentacao');

		$this->load->view('comercial_televenda/apresentacao/formulario', $dados);
	}

	public function adicionar()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidas, 'arquivo');

			$descricao = $this->input->post('descricao');

			$arquivoDados = $this->uploadHelper->upload(
				'./uploads/apresentacoes', 'arquivo', $this->extensoesPermitidas
			);

			$arquivoNome = $arquivoDados['file_name'];
			$arquivo = $arquivoDados['full_path'];

			# Adiciona a apresentacao
			$this->apresentacao->adicionar($descricao, $arquivoNome, $arquivo);

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

	public function editar($id)
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
				$this->uploadHelper->validaExtensao($this->extensoesPermitidas, 'arquivo');

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/apresentacoes', 'arquivo', $this->extensoesPermitidas
				);
			
				$arquivo = $arquivoDados['full_path'];
				$arquivoNome = $arquivoDados['file_name'];
			}
			
			# Altera a apresentação
			$this->apresentacao->editar(
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

	public function excluir($id)
	{
		try
		{
			# Inativa a apresentação
			$this->apresentacao->excluir($id);

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
}