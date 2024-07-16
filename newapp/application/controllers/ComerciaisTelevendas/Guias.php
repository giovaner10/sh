<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Guias extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');

		$this->load->model('guia');
		$this->load->model('mapa_calor');
        $this->load->helper('upload');
		$this->uploadHelper = new Upload_Helper();

        # Models in model
		$this->load->model('arquivo');

		$this->extensoesPermitidas = 'pdf';
    }

    public function index()
    {
        $dados['titulo'] = lang('guias');
		$this->mapa_calor->registrar_acessos_url(site_url('/ComerciaisTelevendas/Guias'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/guia/index');
        $this->load->view('fix/footer_NS');
    }

    public function get()
    {
        $guias = $this->guia->get();
		
		$data = [];
		$x = 0;
		
		foreach ($guias as $guia)
        {
			$data[$x] =
			[
				$guia->id,
				$guia->descricao,
				[ # Ações
					'id' => $guia->id,
					'arquivo' => $guia->file
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
			$dados['modalTitulo'] = lang('editar_guia');
			$dados['guia'] = $this->guia->getPorId($id);
		}
		else
			$dados['modalTitulo'] = lang('nova_guia');

		$this->load->view('comercial_televenda/guia/formulario', $dados);
	}

	public function adicionar()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidas, 'arquivo');

			$descricao = $this->input->post('descricao');

			$arquivoDados = $this->uploadHelper->upload(
				'./uploads/guias', 'arquivo', $this->extensoesPermitidas
			);

			$arquivoNome = $arquivoDados['file_name'];
			$arquivo = $arquivoDados['full_path'];

			# Adiciona o guia
			$this->guia->adicionar($descricao, $arquivoNome, $arquivo);

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
					'./uploads/guias', 'arquivo', $this->extensoesPermitidas
				);
			
				$arquivo = $arquivoDados['full_path'];
				$arquivoNome = $arquivoDados['file_name'];
			}
			
			# Altera o guia
			$this->guia->editar(
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
			# Inativa o guia
			$this->guia->excluir($id);

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