<?php

use function PHPSTORM_META\type;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class ReclameAqui extends CI_Controller {
    public function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('processo_atendimento_reclame_aqui');
		$this->load->model('mapa_calor');
        $this->load->helper("upload");
		$this->uploadHelper = new Upload_Helper();

		$this->load->helper('api_helper');

        # Models in model
		$this->load->model("arquivo");

        # Vars
		$this->extensoesPermitidasRelease = "pdf";
	}

	/**
     * Função carrega a tela Reclame Aqui
     * @author Lucas Henrique
    */
	public function index() {
        $this->auth->is_allowed('vis_reclame_aqui_processos_atendimento');

        $dados['titulo'] = lang('reclame_aqui');
        $this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/ReclameAqui'));

        $this->load->view('new_views/fix/header', $dados);
		$this->load->view('processosAtendimento/reclame_aqui_new');
		$this->load->view('fix/footer_NS');
    }

	/**
     * Função que busca os Reclame Aqui
     * @author Lucas Henrique
     */
	public function buscarReclameAqui()
	{
		try {
			$reclame_aquis = $this->processo_atendimento_reclame_aqui->getReclameAquis();
			
			if ($reclame_aquis) {
				foreach ($reclame_aquis as &$reclame_aqui) {
					$reclame_aqui->id = (int) $reclame_aqui->id;
				}
				echo json_encode(array(
					"status" => 200,
					"dados" => $reclame_aquis
				));
			} else {
				echo json_encode(array(
					"status" => 404,
					"dados" => []
				));
			}
		} catch (\Exception $e) {
			echo json_encode(array(
				"status" => 500
			));
		}
	}

    public function index_old() {
        $this->auth->is_allowed('vis_reclame_aqui_processos_atendimento');

        $dados['titulo'] = lang('reclame_aqui');
        $this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/ReclameAqui'));

        $this->load->view('fix/header_NS', $dados);
		$this->load->view('processosAtendimento/reclame_aqui');
		$this->load->view('fix/footer_NS');
    }

    public function getReclameAquis()
	{
		$reclame_aquis = $this->processo_atendimento_reclame_aqui->getReclameAquis();
		
		$data = [];
		$x = 0;
		
		foreach ($reclame_aquis as $reclame_aqui)
        {
			$data[$x] =
			[
				$reclame_aqui->id,
				$reclame_aqui->titulo_reclame_aqui,
				[
					'id' => $reclame_aqui->id, # Editar e excluir
					'arquivo' => $reclame_aqui->file # Visualizar
				]
			];

			$x++;
		}

		echo json_encode(["data" => $data]);
	}

    public function adicionarReclameAqui()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

			$reclame_aqui = $this->input->post("reclame_aqui");
			$usuarioId = $this->auth->get_login_dados()["user"];

			$arquivoDados = $this->uploadHelper->upload(
				"./uploads/processos_atendimento_reclame_aqui", "arquivo", $this->extensoesPermitidasRelease
			);

			$arquivoNome = $arquivoDados["file_name"];
			$arquivo = $arquivoDados["full_path"];

			# Adiciona release
			$this->processo_atendimento_reclame_aqui->adicionarReclameAqui($reclame_aqui, $arquivoNome, $arquivo, $usuarioId);

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

    public function formularioReclameAqui($reclame_aquiId = null)
	{
		$dados = [];

		if ($reclame_aquiId)
		{
			$dados["modalTitulo"] = lang("editar_reclame_aqui");
			$dados["reclame_aqui"] = $this->processo_atendimento_reclame_aqui->getReclameAqui($reclame_aquiId);
		}
		else
			$dados["modalTitulo"] = lang("adicionar_reclame_aqui");

		$this->load->view("processosAtendimento/reclame_aqui_cadastro", $dados); //alterar para release

	}

	public function editarReclameAqui($reclame_aquiId)
	{
		try
		{
			$reclame_aqui = $this->input->post("reclame_aqui");
			$usuarioId = $this->auth->get_login_dados()["user"];
			$arquivo = "";
			$arquivoNome = "";

			# Altera arquivo
			if ($_FILES["arquivo"]["name"] != "")
			{
	            # valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/processos_atendimento_reclame_aqui', "arquivo", $this->extensoesPermitidasRelease
				);
			
				$arquivo = $arquivoDados["full_path"];
				$arquivoNome = $arquivoDados["file_name"];
			}
			
			# Altera release
			$this->processo_atendimento_reclame_aqui->editarReclameAqui(
				$reclame_aquiId, $reclame_aqui, $usuarioId, $arquivo, $arquivoNome
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

	public function excluirReclameAqui($reclame_aquiId)
	{
		try
		{
			# inativa o reclame_aqui
			$this->processo_atendimento_reclame_aqui->excluirReclameAqui($reclame_aquiId);

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