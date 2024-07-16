<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class PoliticasFormularios extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');

		# Models
		$this->load->model('formulario_informacao', 'formularioInformacao');
		
		# Helpers
		$this->load->helper('upload');
		$this->uploadHelper = new Upload_Helper();
		
		# Models in model
        $this->load->model('arquivo');

		# Vars
		$this->extensoesPermitidas = 'jpg|jpeg|png|gif|pptx|ppt|pdf|doc|docx|xlsx|xls';
	}

	public function getPoliticasFormularios($tipo, $departamentoId)
	{
		$politicas = $this->formularioInformacao->getPoliticasFormularios([
			'informacao.tipo' => $tipo, // Formulario ou Politica
			'informacao.id_departamentos' => $departamentoId
		]);

		$data = [];
		$x = 0;
		foreach ($politicas as $politica)
        {
			$data[$x] =
			[
				$politica->id,
				$politica->descricao,
				[
					'id' => $politica->id, # Editar e excluir
					'arquivo' => $politica->file # Visualizar
				]
			];
			$x++;
		}

		echo json_encode(['data' => $data]);
	}

	public function formularioPolitica($politicaId = null)
	{
		$dados = [];
		$dados['extensoesPermitidas'] = str_replace('|', ' | ', $this->extensoesPermitidas);

		if ($politicaId)
		{
			$dados['modalTitulo'] = lang('editar_politica');
			$dados['politica'] = $this->formularioInformacao->getPoliticaFormulario($politicaId);
		}
		else
			$dados['modalTitulo'] = lang('nova_politica');

		$this->load->view('politica_formulario/politica', $dados);
	}

	public function formularioFormulario($formularioId = null)
	{
		$dados = [];
		$dados['extensoesPermitidas'] = str_replace('|', ' | ', $this->extensoesPermitidas);

		if ($formularioId)
		{
			$dados['modalTitulo'] = lang('editar_formulario');
			$dados['formulario'] = $this->formularioInformacao->getPoliticaFormulario($formularioId);
		}
		else
			$dados['modalTitulo'] = lang('novo_formulario');

		$this->load->view('politica_formulario/formulario', $dados);
	}

	public function adicionarPoliticaFormulario($departamentoId)
	{
		try
		{
			$descricao = $this->input->post('descricao');
			$tipo = $this->input->post('tipo');

			# Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidas, 'arquivo');

			$arquivoDados = $this->uploadHelper->upload(
				'./uploads/politica_formulario',
				'arquivo',
				$this->extensoesPermitidas
			);

			$arquivoNome = $arquivoDados['file_name'];
			$arquivo = $arquivoDados['full_path'];

			# Adiciona politica ou formulario
			$this->formularioInformacao->adicionarFormularioInformacao(
				$descricao, $arquivoNome, $arquivo, $tipo, $departamentoId
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

	public function editarPoliticaFormulario($politicaFormularioId, $departamentoId)
	{
		try
		{
			$descricao = $this->input->post('descricao');
			$tipo = $this->input->post('tipo');
			$arquivo = '';
			$arquivoNome = '';

			# Altera arquivo
			if ($_FILES['arquivo']['name'] != '')
			{
				# Valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidas, 'arquivo');

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/politica_formulario',
					'arquivo',
					$this->extensoesPermitidas
				);
			
				$arquivo = $arquivoDados['full_path'];
				$arquivoNome = $arquivoDados['file_name'];
			}
			
			# Altera politica ou formulario
			$this->formularioInformacao->editarFormularioInformacao(
				$politicaFormularioId, $descricao, $arquivoNome, $arquivo, $tipo, $departamentoId
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

	public function excluirPoliticaFormulario($politicaId)
	{
		try
		{
			# Inativa o politica
			$this->formularioInformacao->excluirFormularioInformacao($politicaId);

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