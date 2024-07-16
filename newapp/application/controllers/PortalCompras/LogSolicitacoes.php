<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class LogSolicitacoes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->auth->is_logged('admin');

		$this->load->model('portal_compras/log_solicitacao', 'log');
	}

	public function listarParaSolicitacao($idSolicitacao) {
		$dados = [];

		$logs = $this->log->listar( 
			'log.*, usuario.nome as nomeUsuario, usuario.login as email, usuario.funcao_portal as funcaoUsuario',
			['log.id_solicitacoes' => $idSolicitacao], 
			true
		);

		if (!empty($logs)) {
			foreach ($logs as $log) {
				$dados[] = [
					'id' => (int)$log->id,
					'nomeUsuario' => $log->nomeUsuario,
					'emailUsuario' => $log->email,
					'funcaoUsuario' => $log->funcaoUsuario,
					'acao' => $log->acao,
					'datahoraCadastro' => $log->datahora_cadastro,
				];
			}	
		}

		exit(json_encode(['status' => '1', 'logs' => $dados]));
	}

}

