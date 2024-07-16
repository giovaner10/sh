<?php

use function PHPSTORM_META\type;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class KeyAccount extends CI_Controller {
    public function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('processo_atendimento_key_account');
		$this->load->model('mapa_calor'); //deve sempre ficar antes das chamadas de helper
        $this->load->helper("upload");
		$this->uploadHelper = new Upload_Helper();
		
		$this->load->helper('api_helper');      

        # Models in model
		$this->load->model("arquivo");

        # Vars
		$this->extensoesPermitidasRelease = "pdf";
	}

	public function index() {
        $this->auth->is_allowed('vis_key_account_processos_atendimento');

        $dados['titulo'] = lang('key_account');
		$this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/KeyAccount'));
        $this->load->view('new_views/fix/header', $dados);
		$this->load->view('processosAtendimento/key_account_new');
		$this->load->view('fix/footer_NS');
    }

    public function index_old() {
        $this->auth->is_allowed('vis_key_account_processos_atendimento');

        $dados['titulo'] = lang('key_account');
		$this->mapa_calor->registrar_acessos_url(site_url('/ProcessosAtendimento/KeyAccount'));
        $this->load->view('fix/header_NS', $dados);
		$this->load->view('processosAtendimento/key_account');
		$this->load->view('fix/footer_NS');
    }

	/**
     * Função que busca as Key Accounts
     * @author Lucas Henrique
     */
	public function buscarKeyAccounts()
	{
		try {
			$key_accounts = $this->processo_atendimento_key_account->getKeyAccounts();
			
			if ($key_accounts) {
				foreach ($key_accounts as &$key_account) {
					$key_account->id = (int) $key_account->id;
				}
				echo json_encode(array(
					"status" => 200,
					"dados" => $key_accounts
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

    public function getKeyAccounts()
	{
		$key_accounts = $this->processo_atendimento_key_account->getKeyAccounts();
		
		$data = [];
		$x = 0;
		
		foreach ($key_accounts as $key_account)
        {
			$data[$x] =
			[
				$key_account->id,
				$key_account->titulo_key_account,
				[
					'id' => $key_account->id, # Editar e excluir
					'arquivo' => $key_account->file # Visualizar
				]
			];

			$x++;
		}

		echo json_encode(["data" => $data]);
	}

    public function adicionarKeyAccount()
	{
		try
		{
            # Valida extensao do arquivo
			$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

			$key_account = $this->input->post("key_account");
			$usuarioId = $this->auth->get_login_dados()["user"];

			$arquivoDados = $this->uploadHelper->upload(
				"./uploads/processos_atendimento_key_account", "arquivo", $this->extensoesPermitidasRelease
			);

			$arquivoNome = $arquivoDados["file_name"];
			$arquivo = $arquivoDados["full_path"];

			# Adiciona release
			$this->processo_atendimento_key_account->adicionarKeyAccount($key_account, $arquivoNome, $arquivo, $usuarioId);

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

    public function formularioKeyAccount($keyAccountId = null)
	{
		$dados = [];

		if ($keyAccountId)
		{
			$dados["modalTitulo"] = lang("editar_key_account");
			$dados["key_account"] = $this->processo_atendimento_key_account->getKeyAccount($keyAccountId);
		}
		else
			$dados["modalTitulo"] = lang("adicionar_key_account");

		$this->load->view("processosAtendimento/key_account_cadastro", $dados); //alterar para release

	}

	public function editarKeyAccount($keyAccountId)
	{
		try
		{
			$key_account = $this->input->post("key_account");
			$usuarioId = $this->auth->get_login_dados()["user"];
			$arquivo = "";
			$arquivoNome = "";

			# Altera arquivo
			if ($_FILES["arquivo"]["name"] != "")
			{
	            # valida extensao do arquivo
				$this->uploadHelper->validaExtensao($this->extensoesPermitidasRelease, "arquivo");

				$arquivoDados = $this->uploadHelper->upload(
					'./uploads/processos_atendimento_key_account', "arquivo", $this->extensoesPermitidasRelease
				);
			
				$arquivo = $arquivoDados["full_path"];
				$arquivoNome = $arquivoDados["file_name"];
			}
			
			# Altera release
			$this->processo_atendimento_key_account->editarKeyAccount(
				$keyAccountId, $key_account, $usuarioId, $arquivo, $arquivoNome
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

	public function excluirKeyAccount($keyAccountId)
	{
		try
		{
			# inativa o key_account
			$this->processo_atendimento_key_account->excluirKeyAccount($keyAccountId);

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