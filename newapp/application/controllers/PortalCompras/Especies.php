<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Especies extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->auth->is_logged('admin');
		$this->load->model('portal_compras/especie');
	}

	public function listar($codigoEmpresa = '01') {
		$dados = [];
		$especies = $this->especie->listar('id, codigo, descricao', ['codigo_empresa' => $codigoEmpresa, 'status' => 'ativo']);
		if (!empty($especies)) {
			foreach ($especies as $especie) {
				$dados[] = [
					'id' => $especie->id,
					'codigo' => $especie->codigo,
					'descricao' => $especie->descricao
				];
			}
		}

		exit(json_encode($dados));
	}

}