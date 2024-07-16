<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Empresas extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->auth->is_logged('admin');
		$this->load->model('portal_compras/empresa');
	}

	public function listar($codigoEmpresa = '01') {
		$dados = [];
		$empresas = $this->empresa->listar(['status' => 'ativo'], 'id, codigo, nome');
		if (!empty($empresas)) {
			foreach ($empresas as $empresa) {
				if (strtolower($empresa->nome) == 'omnilink') {
					$dados[] = [
						'id' => $empresa->id,
						'codigo' => $empresa->codigo,
						'nome' => $empresa->nome
					];
				}
			}
		}

		exit(json_encode($dados));
	}

}