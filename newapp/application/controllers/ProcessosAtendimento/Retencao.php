<?php

use function PHPSTORM_META\type;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Retencao extends CI_Controller {
    public function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('processo_atendimento_retencao');
		$this->load->model('mapa_calor');
        $this->load->helper("upload");
		$this->uploadHelper = new Upload_Helper();

		$this->load->helper('api_helper');

        # Models in model
		$this->load->model("arquivo");

        # Vars
		$this->extensoesPermitidasRelease = "pdf";
	}

    public function index() {
        $this->auth->is_allowed('vis_retencao_processos_atendimento');

        $dados['titulo'] = lang('retencao');
		$this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/Retencao'));
        $this->load->view('new_views/fix/header', $dados);
		$this->load->view('processosAtendimento/retencao');
		$this->load->view('fix/footer_NS');
    }

    public function getRetencaos()
	{
		$retencaos = $this->processo_atendimento_retencao->getRetencaos();
		
		$data = [];
		$x = 0;
		
		foreach ($retencaos as $retencao)
        {
			$data[$x] =
			[
				$retencao->id,
				$retencao->titulo_retencao,
				[
					'id' => $retencao->id, # Editar e excluir
					'arquivo' => $retencao->file # Visualizar
				]
			];

			$x++;
		}

		echo json_encode(["data" => $data]);
	}

    public function adicionarRetencao()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

			$retencao = $this->input->post("retencao");
			$usuarioId = $this->auth->get_login_dados()["user"];

			$arquivoDados = $this->uploadHelper->upload(
				"./uploads/processos_atendimento_retencao", "arquivo", $this->extensoesPermitidasRelease
			);

			$arquivoNome = $arquivoDados["file_name"];
			$arquivo = $arquivoDados["full_path"];

			# Adiciona release
			$this->processo_atendimento_retencao->adicionarRetencao($retencao, $arquivoNome, $arquivo, $usuarioId);

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

    public function formularioRetencao($retencaoId = null)
	{
		$dados = [];

		if ($retencaoId)
		{
			$dados["modalTitulo"] = lang("editar_retencao");
			$dados["retencao"] = $this->processo_atendimento_retencao->getRetencao($retencaoId);
		}
		else
			$dados["modalTitulo"] = lang("adicionar_retencao");

		$this->load->view("processosAtendimento/retencao_cadastro", $dados); //alterar para release

	}

	public function editarRetencao($retencaoId)
	{
		try
		{
			$retencao = $this->input->post("retencao");
			$usuarioId = $this->auth->get_login_dados()["user"];
			$arquivo = "";
			$arquivoNome = "";

			# Altera arquivo
			if ($_FILES["arquivo"]["name"] != "")
			{
	            # valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/processos_atendimento_retencao', "arquivo", $this->extensoesPermitidasRelease
				);
			
				$arquivo = $arquivoDados["full_path"];
				$arquivoNome = $arquivoDados["file_name"];
			}
			
			# Altera release
			$this->processo_atendimento_retencao->editarRetencao(
				$retencaoId, $retencao, $usuarioId, $arquivo, $arquivoNome
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

	public function excluirRetencao($retencaoId)
	{
		try
		{
			# inativa o retencao
			$this->processo_atendimento_retencao->excluirRetencao($retencaoId);

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