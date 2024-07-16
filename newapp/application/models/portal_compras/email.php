<?php 

class Email extends CI_Model {

	public function __construct() {
		$this->load->model('portal_compras/solicitacao');
		$this->load->model('portal_compras/filial');
		$this->load->model('portal_compras/comentario');
		$this->load->model('portal_compras/cotacao');
		$this->load->model('usuario');
		$this->load->model('sender');
	}

	private function mapFormaPagamento($valor) {
		$formasPagamento = [
			'pix' => 'Pix',
			'boleto' => 'Boleto',
			'ted' => 'Ted',
			'cartao_credito' => 'Cartão de Crédito',
			'deposito' => 'Depósito Bancário',
		];

		return !empty($formasPagamento[$valor]) ? $formasPagamento[$valor] : $valor;
	}

	/**
	 * Envia email para os aprovadores da solicitação
	 * @param  int $idSolicitacao
	 * @param  string $layoutEmail list ['aprovadores', 'solicitante', 'area_compras', 'comentarios']
	*/
	public function send($idSolicitacao, $layoutEmail) {
		$solicitacao = $this->solicitacao->listar( 
				'solicitacao.*, usuario.nome as nome_usuario, usuario.login as email_usuario',  
				['solicitacao.id' => $idSolicitacao], 
				true 
			)[0];
			
		switch ($layoutEmail) {
			case 'aprovador':
				$this->sendEmailAprovador($solicitacao);
				break;

			case 'solicitante':
				$this->sendEmailSolicitante($solicitacao);
				break;
			
			case 'area_compras':
				$this->sendEmailAreaCompras($solicitacao);
				break;

			case 'comentarios':
				$this->sendEmailComentario($solicitacao);
				break;
		}
	}

	private function sendEmailComentario($solicitacao) {
		//Monta a mensagem do email
		$assunto = '[Portal Compras] Um novo comentário foi adicionado a solicitação de compra.';

		// Busca os emails para quem deve ser enviado
		$comentarios = $this->comentario->listar(
			'usuario.login as email, usuario.nome as nomeUsuario, comentario.mensagem, comentario.datahora_cadastro as dataHoraCadastro', 
			['comentario.id_solicitacoes' => $solicitacao->id], 
			true,
			'comentario.id',
			'desc'
		);

		if (!empty($comentarios)) {
			$usuarioComitante = $comentarios[0];
			unset($comentarios[0]);
	
			// Monta o corpo do email
			$dados = [
				'idSolicitacao' => $solicitacao->id,
				'nome' => $usuarioComitante->nomeUsuario,
				'email' => $usuarioComitante->email,
				'mensagem' => $usuarioComitante->mensagem,
				'dataHoraCadastro' => date('d/m/Y H:i:s', strtotime($usuarioComitante->dataHoraCadastro)),
			];
	
			$html = $this->load->view('portal_compras/template_email/comentarios', $dados, true);

			// pega os emails de todos que comentaram
			$emails = [];
			$emailsDosComentaristas = array_column_custom($comentarios, 'email');
			if (!empty($emailsDosComentaristas)) {
				foreach ($emailsDosComentaristas as $email) {
					if (!in_array(trim($email), $emails)) {
						$emails[] = trim($email);
					}
				}
			}
			
			// pega o email do solicitante
			$emailSolicitante = trim($solicitacao->email_usuario);
			if (!in_array($emailSolicitante, $emails)) $emails[] = $emailSolicitante;
	
			$this->sender->sendEmailAPI($assunto, $html, $emails);
		}
	}

	private function sendEmailAreaCompras($solicitacao) {
		//Monta a mensagem do email
		$assunto = '[Portal Compras] Uma nova solicitação de compra foi cadastrada.';

		// Monta o corpo do email
		$dados = [
			'idSolicitacao' => $solicitacao->id,
			'situacao' => $solicitacao->situacao,
		];

		$html = $this->load->view('portal_compras/template_email/area_compras', $dados, true);

		// Busca os emails para quem deve ser enviado
		$usuarios = $this->usuario->listarFuncionarios('id, nome, login as email', ['funcao_portal' => 'area_compras']);
		$emails = array_column_custom($usuarios, 'email');
		$emails = array_map(function($item) { return trim($item); }, $emails);

    $this->sender->sendEmailAPI($assunto, $html, $emails);
	}

	private function sendEmailSolicitante($solicitacao) {
		$html = [];
		$assunto = ''; 
		$mensagem = '';
		$link = site_url() .'/PortalCompras/Solicitacoes/visualizar/'. $solicitacao->id;

		//Monta a mensagem do email		
		if ($solicitacao->situacao === 'aprovado') {
			$assunto = '[Portal Compras] Sua solicitação de compra foi aprovada.';
			$mensagem = 'Informamos que a solicitação de compra #'.$solicitacao->id.' foi <strong>APROVADA</strong>.';
		}
		else if ($solicitacao->situacao === 'reprovado') {
			$assunto = '[Portal Compras] Sua solicitação de compra foi reprovada.';
			$mensagem = 'Informamos que a solicitação de compra #'.$solicitacao->id.' foi <strong>REPROVADA</strong>.';
		}
		else if ($solicitacao->situacao === 'aguardando_aprovacao') {
			$assunto = '[Portal Compras] Sua solicitação foi enviada para aprovação.';
			$mensagem = 'Informamos que a solicitação de compra #'.$solicitacao->id.' foi <strong>ENVIADA PARA APROVAÇÃO</strong>.';
		}
		else if($solicitacao->situacao === 'aguardando_confirmacao_cotacao') {
			$assunto = '[Portal Compras] Selecione a cotação desejada.';
			$mensagem = 'Informamos que a solicitação de compra #'.$solicitacao->id.' está <strong>AGUARDANDO A SELEÇÃO DA COTAÇÃO</strong>.';
			$link = site_url() .'/PortalCompras/Solicitacoes/acaoSobreProdutoECotacao/'. $solicitacao->id .'/selecionar_cotacao';
		}

		// Monta o corpo do email
		$dados = [
			'status' => $solicitacao->situacao,
			'idSolicitacao' => $solicitacao->id,
			'nomeSolicitante' => $solicitacao->nome_usuario,
			'mensagem' => $mensagem,
			'link' => $link,
		];

		$html = $this->load->view('portal_compras/template_email/solicitante', $dados, true);
		
		// Busca os emails para quem deve ser enviado
		$emails[] = trim($solicitacao->email_usuario);

    $this->sender->sendEmailAPI($assunto ,$html, $emails);
	}

	private function sendEmailAprovador($solicitacao) {
		//Monta a mensagem do email
		$assunto = '[Portal Compras] Uma nova solicitação de compra aguarda sua aprovação.';

		// Busca o email para quem deve ser enviado
		$emails = [];
		$aprovadores = !empty($solicitacao->acao_aprovadores) ? json_decode($solicitacao->acao_aprovadores) : [];
		if (!empty($aprovadores->diretor) && $aprovadores->diretor->resultado === 'aguardando') $emails[] = trim($aprovadores->diretor->email);
		else if (!empty($aprovadores->cfo) && $aprovadores->cfo->resultado === 'aguardando') $emails[] = trim($aprovadores->cfo->email);
		else if (!empty($aprovadores->ceo) && $aprovadores->ceo->resultado === 'aguardando') $emails[] = trim($aprovadores->ceo->email);
		if (empty($emails)) return false;

		// Monta o corpo do email
		$cotacao = $this->cotacao->buscar($solicitacao->id_cotacoes, 'id, fornecedor, condicao_pagamento, forma_pagamento');
		$fornecedor = !empty($cotacao->fornecedor) ? json_decode($cotacao->fornecedor) : null;

		$filial = $this->filial->buscar($solicitacao->id_filiais, 'id, codigo, nome');

		$documentoFornecedorFormatado = '';
		if (!empty($fornecedor->documento && strlen($fornecedor->documento) == 11)) {
			$documentoFornecedorFormatado = mask($fornecedor->documento, '###.###.###-##');
		}
		else if (!empty($fornecedor->documento && strlen($fornecedor->documento) == 14)) {
			$documentoFornecedorFormatado = mask($fornecedor->documento, '##.###.###/####-##');
		}

		$dados = [
			'idSolicitacao' => $solicitacao->id,
			'nomeSolicitante' => $solicitacao->nome_usuario,
			'nomeFornecedor' => !empty($fornecedor->nome) && !empty($fornecedor->documento) ? $documentoFornecedorFormatado . ' - ' . $fornecedor->nome : '',
			'filial' => !empty($filial->nome) ? $filial->nome : '',
			'condicaoPagamento' => !empty($cotacao->condicao_pagamento) ? explode(' - ', $cotacao->condicao_pagamento)[1] : '',
			'formaPagamento' => !empty($cotacao->forma_pagamento) ? $this->mapFormaPagamento($cotacao->forma_pagamento) : '',
			'valorTotal' => 'R$ ' . number_format($solicitacao->valor_total, 2, ',', '.'),
			'dataCadastro' => date('d/m/Y H:i:s', strtotime($solicitacao->data_cadastro)),
		];

		$html = $this->load->view('portal_compras/template_email/aprovadores', $dados, true);
		
		$this->sender->sendEmailAPI($assunto ,$html, $emails);
		return true;
	}


}