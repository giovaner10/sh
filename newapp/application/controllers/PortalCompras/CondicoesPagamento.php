<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class CondicoesPagamento extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->auth->is_logged('admin');
		$this->load->model('portal_compras/condicao_pagamento');
	}

	public function listar($codigoEmpresa = '01') {
		$dados = [];
		$condicoesPagamento = $this->condicao_pagamento->listar(['codigo_empresa' => $codigoEmpresa, 'status' => 'ativo'], 'id, codigo, descricao');
		if (!empty($condicoesPagamento)) {
			foreach ($condicoesPagamento as $condicaoPagamento) {
				$dados[] = [
					'id' => $condicaoPagamento->id,
					'codigo' => $condicaoPagamento->codigo,
					'descricao' => strtoupper($condicaoPagamento->descricao)
				];
			}
		}

		exit(json_encode($dados));
	}

}