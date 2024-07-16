<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class CentrosCusto extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->auth->is_logged('admin');
		$this->load->model('portal_compras/centro_custo');
	}

	public function buscarPeloCodigoOuDescricao($codigoEmpresa = '01') {
		$termoConsulta = $this->input->get('term');

		// Valida o $nomeProduto
		if (empty($termoConsulta) || !is_string($termoConsulta)) {
			exit(json_encode(['status' => '-1', 'mensagem' => 'Termo de consulta inválido.']));
		}

		// Decodifica e deixa apenas numeros e letras
		$termoConsulta = urldecode($termoConsulta);
		$termoConsulta = preg_replace('/[^a-zA-Z0-9\s]/', '', $termoConsulta);

		$dados = [];
		$centrosCusto = $this->centro_custo->buscarPeloCodigoOuDescricao('id, codigo, descricao', $termoConsulta, $codigoEmpresa);
		if (!empty($centrosCusto)) {
			foreach ($centrosCusto as $centroCusto) {
				$dados[] = [
					'id' => $centroCusto->id,
					'codigo' => $centroCusto->codigo,
					'descricao' => $centroCusto->descricao
				];
			}
		}

		exit(json_encode($dados));
	}

}