<?php

use function PHPSTORM_META\type;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class SuporteN1 extends CI_Controller {
    public function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('mapa_calor');
        $this->load->model('processo_atendimento_suporte_n1');

        $this->load->helper("upload");
		$this->uploadHelper = new Upload_Helper();


		$this->load->helper('api_helper');
        $this->load->model('mapa_calor');

        # Models in model
		$this->load->model("arquivo");

        # Vars
		$this->extensoesPermitidasRelease = "pdf";
	}

    public function index() {
        $this->auth->is_allowed('vis_suporte_processos_atendimento');

        $dados['titulo'] = lang('suporte_n1');
        $this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/SuporteN1'));

        $this->load->view('new_views/fix/header', $dados);
		$this->load->view('processosAtendimento/suporte_n1');
		$this->load->view('fix/footer_NS');
    }

    public function getSuportes()
	{
		$suportes = $this->processo_atendimento_suporte_n1->getSuportes();
		
		$data = [];
		$x = 0;
		
		foreach ($suportes as $suporte)
        {
			$data[$x] =
			[
				$suporte->id,
				$suporte->titulo_suporte,
				[
					'id' => $suporte->id, # Editar e excluir
					'arquivo' => $suporte->file # Visualizar
				]
			];

			$x++;
		}

		echo json_encode(["data" => $data]);
	}

    public function adicionarSuporte()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

			$suporte = $this->input->post("suporte");
			$usuarioId = $this->auth->get_login_dados()["user"];

			$arquivoDados = $this->uploadHelper->upload(
				"./uploads/processos_atendimento_suporte_n1", "arquivo", $this->extensoesPermitidasRelease
			);

			$arquivoNome = $arquivoDados["file_name"];
			$arquivo = $arquivoDados["full_path"];

			# Adiciona release
			$this->processo_atendimento_suporte_n1->adicionarSuporte($suporte, $arquivoNome, $arquivo, $usuarioId);

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

    public function formularioSuporte($suporteId = null)
	{
		$dados = [];

		if ($suporteId)
		{
			$dados["modalTitulo"] = lang("editar_suporte_n1");
			$dados["suporte"] = $this->processo_atendimento_suporte_n1->getSuporte($suporteId);
		}
		else
			$dados["modalTitulo"] = lang("adicionar_suporte_n1");

		$this->load->view("processosAtendimento/suporte_n1_cadastro", $dados); //alterar para release

	}

	public function editarSuporte($suporteId)
	{
		try
		{
			$suporte = $this->input->post("suporte");
			$usuarioId = $this->auth->get_login_dados()["user"];
			$arquivo = "";
			$arquivoNome = "";

			# Altera arquivo
			if ($_FILES["arquivo"]["name"] != "")
			{
	            # valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/processos_atendimento_suporte_n1', "arquivo", $this->extensoesPermitidasRelease
				);
			
				$arquivo = $arquivoDados["full_path"];
				$arquivoNome = $arquivoDados["file_name"];
			}
			
			# Altera release
			$this->processo_atendimento_suporte_n1->editarSuporte(
				$suporteId, $suporte, $usuarioId, $arquivo, $arquivoNome
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

	public function excluirSuporte($suporteId)
	{
		try
		{
			# inativa o suporte
			$this->processo_atendimento_suporte_n1->excluirSuporte($suporteId);

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