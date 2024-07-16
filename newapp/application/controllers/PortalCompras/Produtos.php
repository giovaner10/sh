<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Produtos extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->auth->is_logged('admin');
		$this->load->model('portal_compras/produto');
	}

	public function listar($codigoEmpresa='01') {
		$dados = [];
		// Busca apenas os produtos ativos
		$produtos = $this->produto->listar(['status' => 'ativo', 'codigo_empresa' => $codigoEmpresa], 'id, codigo, descricao, tipo');
		if (!empty($produtos)) {
			foreach ($produtos as $produto) {
				$dados[$produto->codigo] = [
					'id' => $produto->id,
					'codigo' => $produto->codigo,
					'descricao' => $produto->descricao,
					'tipo' => $produto->tipo
				];
			}
		}

		exit(json_encode($dados));
	}

	public function buscarPelaDescricao($codigoEmpresa = '01') {
		$termoConsulta = $this->input->get('term');

		// Valida o $nomeProduto
		if (empty($termoConsulta) || !is_string($termoConsulta)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Termo de consulta inválido.']));
		}

		// Decodifica e deixa apenas numeros e letras
		$termoConsulta = urldecode($termoConsulta);
		$termoConsulta = preg_replace('/[^a-zA-Z0-9\s]/', '', $termoConsulta);

		$dados = [];
		$produtos = $this->produto->buscarPelaDescricao('id, codigo, descricao, tipo', $termoConsulta, $codigoEmpresa);
		if (!empty($produtos)) {
			foreach ($produtos as $produto) {
				$dados[] = [
					'id' => $produto->id,
					'codigo' => $produto->codigo,
					'descricao' => $produto->descricao,
					'tipo' => $produto->tipo
				];
			}
		}

		exit(json_encode($dados));
	}

}