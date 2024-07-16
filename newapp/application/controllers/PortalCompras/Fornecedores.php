<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Fornecedores extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->auth->is_logged('admin');
		$this->load->model('portal_compras/fornecedor');
	}

	public function buscarPeloDocumentoOuNome($codigoEmpresa = '01') {
		$termoConsulta = $this->input->get('term');

		// Valida o $nomeProduto
		if (empty($termoConsulta) || !is_string($termoConsulta)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Termo de consulta inválido.']));
		}

		// Decodifica e deixa apenas numeros e letras
		$termoConsulta = urldecode($termoConsulta);
		$termoConsulta = preg_replace('/[^a-zA-Z0-9\s]/', '', $termoConsulta);

		$dados = [];
		$fornecedores = $this->fornecedor->buscarPeloDocumentoOuNome('id, codigo, documento, nome, loja', $termoConsulta, $codigoEmpresa);
		if (!empty($fornecedores)) {
			foreach ($fornecedores as $fornecedor) {
				$dados[] = [
					'id' => $fornecedor->id,
					'codigo' => $fornecedor->codigo,
					'documento' => $fornecedor->documento,
					'nome' => $fornecedor->nome,
					'loja' => $fornecedor->loja
				];
			}
		}

		exit(json_encode($dados));
	}

}