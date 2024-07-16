<?php

use function PHPSTORM_META\type;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Agendamento extends CI_Controller {
    public function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('processo_atendimento_agendamento');
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
        $this->auth->is_allowed('logistica_shownet');

		// $this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/agendamento'));
		
        $dados['titulo'] = lang('pa_agendamento');
		
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('processosAtendimento/agendamento');
		$this->load->view('fix/footer_NS');
    }
	
    public function agendamentoAtendimentoOld() {
        $this->auth->is_allowed('vis_agendamento_processos_atendimento');

		$this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/agendamentoAtendimento'));
		
        $dados['titulo'] = lang('pa_agendamento');
		
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('processosAtendimento/agendamento');
		$this->load->view('fix/footer_NS');
    }

	/**
     * Função carrega view de Agendamento
     * @author Lucas Henrique
     */
	public function agendamentoAtendimento() {
        $this->auth->is_allowed('vis_agendamento_processos_atendimento');

		$this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/agendamentoAtendimento'));
		
        $dados['titulo'] = lang('pa_agendamento');
		
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('processosAtendimento/agendamento_new');
		$this->load->view('fix/footer_NS');
    }

	/**
     * Função que busca os agendamentos
     * @author Lucas Henrique
     */
	public function buscarAgendamentos()
	{

		try {
			$agendamentos = $this->processo_atendimento_agendamento->getAgendamentos();
			
			if ($agendamentos) {
				foreach ($agendamentos as &$agendamento) {
					$agendamento->id = (int) $agendamento->id;
				}
				echo json_encode(array(
					"status" => 200,
					"dados" => $agendamentos
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


    public function getAgendamentos()
	{
		$agendamentos = $this->processo_atendimento_agendamento->getAgendamentos();
		
		$data = [];
		$x = 0;
		
		foreach ($agendamentos as $agendamento)
        {
			$data[$x] =
			[
				$agendamento->id,
				$agendamento->titulo_agendamento,
				[
					'id' => $agendamento->id, # Editar e excluir
					'arquivo' => $agendamento->file # Visualizar
				]
			];

			$x++;
		}

		echo json_encode(["data" => $data]);
	}

    public function adicionarAgendamento()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

			$agendamento = $this->input->post("agendamento");
			$usuarioId = $this->auth->get_login_dados()["user"];

			$arquivoDados = $this->uploadHelper->upload(
				"./uploads/processos_atendimento_agendamento", "arquivo", $this->extensoesPermitidasRelease
			);

			$arquivoNome = $arquivoDados["file_name"];
			$arquivo = $arquivoDados["full_path"];

			# Adiciona release
			$this->processo_atendimento_agendamento->adicionarAgendamento($agendamento, $arquivoNome, $arquivo, $usuarioId);

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

    public function formularioAgendamento($agendamentoId = null)
	{
		$dados = [];

		if ($agendamentoId)
		{
			$dados["modalTitulo"] = lang("editar_agendamento");
			$dados["agendamento"] = $this->processo_atendimento_agendamento->getAgendamento($agendamentoId);
		}
		else
			$dados["modalTitulo"] = lang("adicionar_agendamento");

		$this->load->view("processosAtendimento/agendamento_cadastro", $dados); //alterar para release

	}

	public function editarAgendamento($agendamentoId)
	{
		try
		{
			$agendamento = $this->input->post("agendamento");
			$usuarioId = $this->auth->get_login_dados()["user"];
			$arquivo = "";
			$arquivoNome = "";

			# Altera arquivo
			if ($_FILES["arquivo"]["name"] != "")
			{
	            # valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/processos_atendimento_agendamento', "arquivo", $this->extensoesPermitidasRelease
				);
			
				$arquivo = $arquivoDados["full_path"];
				$arquivoNome = $arquivoDados["file_name"];
			}
			
			# Altera release
			$this->processo_atendimento_agendamento->editarAgendamento(
				$agendamentoId, $agendamento, $usuarioId, $arquivo, $arquivoNome
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

	public function excluirAgendamento($agendamentoId)
	{
		try
		{
			# inativa o agendamento
			$this->processo_atendimento_agendamento->excluirAgendamento($agendamentoId);

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