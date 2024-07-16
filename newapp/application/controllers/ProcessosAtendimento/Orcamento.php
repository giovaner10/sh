<?php

use function PHPSTORM_META\type;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Orcamento extends CI_Controller {
    public function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('processo_atendimento_orcamento');
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
     * Função carrega a tela de Orçamentos
     * @author Lucas Henrique
    */
	public function index() {
        $this->auth->is_allowed('vis_orcamento_processos_atendimento');

        $dados['titulo'] = lang('orcamento');
        $this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/orcamento'));

        $this->load->view('new_views/fix/header', $dados);
		$this->load->view('processosAtendimento/orcamento_new');
		$this->load->view('fix/footer_NS');
    }

	/**
     * Função que busca os Orçamentos
     * @author Lucas Henrique
     */
	public function buscarOrcamentos()
	{
		try {
			$orcamentos = $this->processo_atendimento_orcamento->getOrcamentos();
			
			if ($orcamentos) {
				foreach ($orcamentos as &$orcamento) {
					$orcamento->id = (int) $orcamento->id;
				}
				echo json_encode(array(
					"status" => 200,
					"dados" => $orcamentos
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
        $this->auth->is_allowed('vis_orcamento_processos_atendimento');

        $dados['titulo'] = lang('orcamento');
        $this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/orcamento'));

        $this->load->view('fix/header_NS', $dados);
		$this->load->view('processosAtendimento/orcamento');
		$this->load->view('fix/footer_NS');
    }

    public function getOrcamentos()
	{
		$orcamentos = $this->processo_atendimento_orcamento->getOrcamentos();
		
		$data = [];
		$x = 0;
		
		foreach ($orcamentos as $orcamento)
        {
			$data[$x] =
			[
				$orcamento->id,
				$orcamento->titulo_orcamento,
				[
					'id' => $orcamento->id, # Editar e excluir
					'arquivo' => $orcamento->file # Visualizar
				]
			];

			$x++;
		}

		echo json_encode(["data" => $data]);
	}

    public function adicionarOrcamento()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

			$orcamento = $this->input->post("orcamento") ? trim($this->input->post("orcamento")) : '';
			$usuarioId = $this->auth->get_login_dados()["user"];

			$arquivoDados = $this->uploadHelper->upload(
				"./uploads/processos_atendimento_orcamento", "arquivo", $this->extensoesPermitidasRelease
			);

			$arquivoNome = $arquivoDados["file_name"];
			$arquivo = $arquivoDados["full_path"];

			# Adiciona release
			$this->processo_atendimento_orcamento->adicionarOrcamento($orcamento, $arquivoNome, $arquivo, $usuarioId);

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

    public function formularioOrcamento($orcamentoId = null)
	{
		$dados = [];

		if ($orcamentoId)
		{
			$dados["modalTitulo"] = lang("editar_orcamento");
			$dados["orcamento"] = $this->processo_atendimento_orcamento->getOrcamento($orcamentoId);
		}
		else
			$dados["modalTitulo"] = lang("adicionar_orcamento");

		$this->load->view("processosAtendimento/orcamento_cadastro", $dados); //alterar para release

	}

	public function editarOrcamento($orcamentoId)
	{
		try
		{
			$orcamento = $this->input->post("orcamento") ? trim($this->input->post("orcamento")) : '';
			$usuarioId = $this->auth->get_login_dados()["user"];
			$arquivo = "";
			$arquivoNome = "";

			# Altera arquivo
			if ($_FILES["arquivo"]["name"] != "")
			{
	            # valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/processos_atendimento_orcamento', "arquivo", $this->extensoesPermitidasRelease
				);
			
				$arquivo = $arquivoDados["full_path"];
				$arquivoNome = $arquivoDados["file_name"];
			}
			
			# Altera release
			$this->processo_atendimento_orcamento->editarOrcamento(
				$orcamentoId, $orcamento, $usuarioId, $arquivo, $arquivoNome
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

	public function excluirOrcamento($orcamentoId)
	{
		try
		{
			# inativa o orcamento
			$this->processo_atendimento_orcamento->excluirOrcamento($orcamentoId);

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