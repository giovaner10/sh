<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));

class NotaFiscal extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->model('portal_compras/nota_fiscal');
	}

	public function valida_anexo() {
		$file = $_FILES['anexo'];
		if (empty ($file['name'])) {
			$this->form_validation->set_message('valida_anexo', 'O campo Anexo é obrigatório');
			return FALSE;
		}

		if ($file['type'] !== 'application/pdf') {
			$this->form_validation->set_message('valida_anexo', 'Anexo: O arquivo deve ser do tipo PDF.');
			return FALSE;
		}

		if ($file['size'] > 1024 * 1024 * 5) {
			$this->form_validation->set_message('valida_anexo', 'Anexo: O arquivo deve ter no máximo 5MB.');
			return FALSE;
		}
		
		return TRUE;
	}

	public function incluir($idSolicitacao, $numeroPedido) {
		$dados = $this->input->post();

		// Validacao dos campos
		if(empty($idSolicitacao) || $idSolicitacao == 0) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'IdSolicitacao inválido.']));
		}

		if (empty($numeroPedido) || $numeroPedido == 0) {
			exit (json_encode(['status' => '-1', 'mensagem' => 'Número do Produto inválido.']));
		}

		$this->form_validation->set_rules('numero', 'Número', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('serie', 'Série', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('especie', 'Espécie', 'trim|required|min_length[1]|max_length[20]');
		$this->form_validation->set_rules('valor', 'Valor', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('data_emissao', 'Data de Emissão', 'trim|required|regex_match[/^\d{4}\-\d{2}\-\d{2}$/]');
		$this->form_validation->set_rules('data_vencimento', 'Data de Vencimento', 'trim|required|regex_match[/^\d{4}\-\d{2}\-\d{2}$/]');
		$this->form_validation->set_rules('anexo', 'Anexo', 'callback_valida_anexo');
		
		if ($this->form_validation->run() == FALSE) {
			exit (json_encode(['status' => '-1', 'mensagem' => validation_errors()]));
		}

		$this->load->model('portal_compras/solicitacao');
		$solicitacao = $this->solicitacao->buscar($idSolicitacao);

		$this->load->model('portal_compras/empresa');
		$empresa = $this->empresa->buscar($solicitacao->id_empresas);

		$this->load->model('portal_compras/filial');
		$filial = $this->filial->buscar($solicitacao->id_filiais);
		
		$novaPreNota = [
			'numero' => $dados['numero'],
			'serie' => $dados['serie'],
			'especie' => $dados['especie'],
			'valor' => $dados['valor'],
			'data_emissao' => $dados['data_emissao'],
			'data_vencimento' => $dados['data_vencimento'],
			'numero_pedido' => $numeroPedido,
		];

		// Inclui a pré-nota no ERP
		$this->load->helper('util_portal_compras_helper');
		$resposta = incluirPreNotaNoERP($empresa->codigo, $filial->codigo, $novaPreNota);
		if ($resposta['CLSTATUS'] == 'false') {
			exit (json_encode([
				'status' => '-1',
				'mensagem' => 'Não foi possível incluir a pré-nota no ERP, tente novamente mais tarde.'
			]));
		}
		$idUsuario = $this->auth->get_login_dados('user');

		$this->load->model('portal_compras/log_solicitacao', 'log');
		$this->log->cadastrar([
			'acao' => 'adicionar_pre_nota',
			'id_usuario' => $idUsuario,
			'id_solicitacoes' => $idSolicitacao,
			'datahora_cadastro' => date('Y-m-d H:i:s'),
		]);

		$novaPreNota['codigo_empresa'] = $empresa->codigo;
		$novaPreNota['codigo_filial'] = $filial->codigo;

		exit (json_encode([
			'status' => '1',
			'mensagem' => 'Pré nota incluída com sucesso!',
			'preNota' => $novaPreNota
		]));
	}

	public function listarParaSolicitacao($idSolicitacao) {
		$notasFiscais = $this->nota_fiscal->listar(
			'nota_fiscal.id, nota_fiscal.numero, nota_fiscal.serie, nota_fiscal.especie, nota_fiscal.valor,  nota_fiscal.status,
			nota_fiscal.datahora_emissao as dataEmissao, nota_fiscal.data_vencimento as dataVencimento', 
			['solicitacoes.id' => $idSolicitacao], 
			true
		);

		exit (json_encode(['status' => '1', 'notasFiscais' => $notasFiscais]));
	}


}

