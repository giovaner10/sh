<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class Filiais extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->auth->is_logged('admin');
		$this->load->model('portal_compras/filial');
	}

	public function listar($codigoEmpresa = '01') {
		$dados = [];
		$filiais = $this->filial->listar(['codigo_empresa' => $codigoEmpresa, 'status' => 'ativo'], 'id, codigo, nome');
		if (!empty($filiais)) {
			foreach ($filiais as $filial) {
				if (strtolower($filial->nome) == 'matriz' || strpos(strtolower($filial->nome), 'santa rita') !== false) {
					$dados[] = [
						'id' => $filial->id,
						'codigo' => $filial->codigo,
						'nome' => $filial->nome
					];
				}
			}
		}

		exit(json_encode($dados));
	}

}