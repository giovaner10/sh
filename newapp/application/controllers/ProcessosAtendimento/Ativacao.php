<?php

use function PHPSTORM_META\type;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ativacao extends CI_Controller {
    public function __construct() {
		parent::__construct();
        $this->load->model('processo_ativacao');
        $this->load->model('arquivo');
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('mapa_calor');
        $this->load->helper("upload");
		$this->load->helper('api_helper');
		$this->uploadHelper = new Upload_Helper();

        $this->load->model('mapa_calor');

		# Vars
		$this->extensoesPermitidasRelease = "pdf";
	}

	/**
     * Função carrega view de Agendamento
     * @author Lucas Henrique
     */
    public function index() {
        $this->auth->is_allowed('vis_processos_atendimento_ativacao');

        $dados['titulo'] = lang('pa_ativacao');
        $this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/ativacao'));

        $this->load->view('new_views/fix/header', $dados);
		$this->load->view('processosAtendimento/ativacao_new');
		$this->load->view('fix/footer_NS');
    }

	/**
     * Função que busca as Ativações
     * @author Lucas Henrique
     */
	public function buscarAtivacoes()
	{

		try {
			$ativacoes = $this->processo_ativacao->getProcessos();
			
			if ($ativacoes) {
				foreach ($ativacoes as &$ativacao) {
					$ativacao->id = (int) $ativacao->id;
				}
				echo json_encode(array(
					"status" => 200,
					"dados" => $ativacoes
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

	public function indexOld() {
        $this->auth->is_allowed('vis_processos_atendimento_ativacao');

        $dados['titulo'] = lang('pa_ativacao');
        $this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/ativacao'));

        $this->load->view('fix/header_NS', $dados);
		$this->load->view('processosAtendimento/ativacao');
		$this->load->view('fix/footer_NS');
    }

    public function adicionarProcesso() {
        try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");
			$processo = $this->input->post("processo");
			$usuarioId = $this->auth->get_login_dados()["user"];
			$arquivoDados = $this->uploadHelper->upload(
				"./uploads/processo_notes", "arquivo", $this->extensoesPermitidasRelease
			);
			$arquivoNome = $arquivoDados["file_name"];
			$arquivo = $arquivoDados["full_path"];
			# Adiciona processo
			$this->processo_ativacao->adicionarProcesso($this->arquivo, $processo, $arquivoNome, $arquivo, $usuarioId);
			// $this->processo_ativacao->adicionarProcesso('',' $arquivoNome',' $arquivo', '');
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

    public function editarProcesso() {
        try
		{
			$processoId = $this->input->post("processoId");
			$processo = $this->input->post("processo");
			$usuarioId = $this->auth->get_login_dados()["user"];
			$arquivo = "";
			$arquivoNome = "";
			# Altera arquivo
			if ($_FILES["arquivo"]["name"] != "")
			{
	            # valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");
				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/processo_notes', "arquivo", $this->extensoesPermitidasRelease
				);
			
				$arquivo = $arquivoDados["full_path"];
				$arquivoNome = $arquivoDados["file_name"];
			}
			
			# Altera processo
			$this->processo_ativacao->editarProcesso(
				$this->arquivo, $processoId, $processo, $usuarioId, $arquivo, $arquivoNome
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
    
    public function buscarProcesso() {
        $id = $this->input->get('id');
        $this->processo_ativacao->getProcesso($id);
    }

    public function removerProcesso() {
        $id = $this->input->get('ativacaoId');

        // $this->Processo_Ativacao->excluirProcesso($id);
        try
		{
			# inativa a release
			$this->processo_ativacao->excluirProcesso($id);
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
    
    public function listaProcessos() {
        $processos = $this->processo_ativacao->getProcessos();
        
		$data = [];
		$x = 0;
		
		foreach ($processos as $processo)
        {
			$data[$x] =
			[
				$processo->id,
				$processo->processo,
				[
					'id' => $processo->id, # Editar e excluir
					'arquivo' => $processo->file, # Visualizar
					'processo' => $processo->processo,
				]
			];
			$x++;
		}
		echo json_encode(["data" => $data]);
    }

}