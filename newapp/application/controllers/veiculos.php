<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Veiculos extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->model('auth');
		$this->load->model('cliente');
		$this->load->model('veiculo');
		$this->load->model('usuario_gestor');
		$this->load->model('log_veiculo');
		$this->load->model('contrato');
		$this->load->model('sender');
		$this->load->model('ticket');
		$this->load->model('template_emails');
		$this->load->model('equipamento');
		$this->load->model('mapa_calor');
		$this->load->helper('util_helper');
		$this->load->helper('espelhamento_helper');
	}

	public function tab_listar_gestor($id_cliente, $pagina = false)
	{
		$this->auth->is_logged('admin');
		$veiculos = getCad_VeicByClie($id_cliente);
		$dados['total_gestor'] = count($veiculos);
		$dados['veiculos'] = $veiculos;
		$this->load->view('clientes/tab_veiculos', $dados);
	}

	public function listar_veiculos_cliente($id_cliente)
	{
		$this->auth->is_logged('admin');
		$veiculos = get_listarVeiculosByCliente($id_cliente);
		$dados['total_gestor'] = 0;
		$dados['veiculos'] = array();

		if (isset($veiculos['results'])) {
			$dados['total_gestor'] = count($veiculos['results']);

			foreach ($veiculos['results'] as $veiculo) {
				if (isset($veiculo['code'])) {
					$dados['veiculos'][] = array(
						'code' => $veiculo['code'],
						'veiculo' => $veiculo['veiculo'],
						'placa' => $veiculo['placa'],
						'serial' => $veiculo['serial'],
						'prefixo_veiculo' => $veiculo['prefixoVeiculo'],
						'contratos_veiculo' => $veiculo['contratos_veiculo']
					);
				}
			}
		}
		echo json_encode(array('data' => $dados['veiculos']));
	}

	public function tab_filtrar_gestor($id_cliente, $placa = false, $pagina = false)
	{
		$this->auth->is_logged('admin');

		if ($this->input->post())
			$placa = $this->input->post('placa');

		$paginacao = $pagina != false  ? $pagina : 0;

		$config['per_page'] = 10;
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="pag_cli">';
		$config['num_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li class="pag_cli">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li class="pag_cli">';
		$config['perv_tag_close'] = '</li>';
		$config['last_link'] = 'Fim';
		$config['last_tag_open'] = '<li class="pag_cli">';
		$config['last_tag_close'] = '</li>';
		$config['first_link'] = 'Início';
		$config['first_tag_open'] = '<li class="pag_cli">';
		$config['first_tag_close'] = '</li>';
		$config['base_url'] = base_url() . 'veiculos/tab_filtrar_gestor/' . $id_cliente . '/' . $placa . '/';
		$config['uri_segment'] = 5;
		$config['total_rows'] = $this->veiculo->total_filtro_gestor($id_cliente, $placa);
		$this->pagination->initialize($config);

		$dados['placa'] = $placa;

		$dados['qtd_veiculos_ctr'] = $this->veiculo->total_veiculos_contratos($id_cliente);
		$dados['total_gestor'] = $this->veiculo->total_lista_veiculos_gestor($id_cliente);
		//$dados['total_contrato'] = $this->veiculo->total_veiculos_contrato("id_cliente = {$id_cliente}");
		$dados['veiculos'] = $this->veiculo->filtrar_gestor($id_cliente, $placa, $paginacao, 10);
		$dados['id_cliente'] = $id_cliente;

		$this->load->view('clientes/tab_veiculos', $dados);
	}

	// -----------------------------------------------------------------------------------------
	// DEVELOPER: ERICK AMARAL
	// -----------------------------------------------------------------------------------------

	/**
	 * Verifica se os veículos estão desatualizados
	 **/
	public function checkout_desatualizados()
	{
		return $this->veiculo->checkout();
	}

	/**
	 * Listagem dos veículos desatualizados
	 **/
	public function desatualizados($pagina = false, $clear = false)
	{
		$this->load->helper('text');
		$this->auth->is_logged('admin');
		$data = new DateTime();

		if ($clear) {
			$this->session->unset_userdata('finddesatualizados');
			$this->session->unset_userdata('find_form_desatualizado');
		}

		$veiculos = array();
		$msg = '';

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$horas = $this->input->post('horas');
			$data->sub(new DateInterval("PT{$horas}H"));
			$data_where = $data->format('Y-m-d H:i:s');

			$where['(cv.ultima_atualizacao <='] = "'{$data_where}'" . ' OR cv.ultima_atualizacao IS NULL)';

			$keyword = $this->input->post('keyword');
			$tipo_busca = $this->input->post('tipo_busca');

			$filtro = array('keyword' => $keyword, 'tipo_busca' => $tipo_busca, 'horas' => $horas);
			$this->session->set_userdata('find_form_desatualizado', $filtro);

			$this->form_validation->set_rules('keyword', 'Busca', 'required');
			if ($this->form_validation->run() ===  false) {
				$msg = validation_errors('<li>', '</li>');
			} else {
				switch ($tipo_busca) {
					case 'veiculo':
						$where['cv.veiculo LIKE '] = "'%{$keyword}%'";
						break;
					case 'serial':
						$where['cv.serial'] = "'{$keyword}'";
						break;
					case 'placa':
						$where['cv.placa'] = "'{$keyword}'";
						break;
					case 'user':
						$where['ug.usuario LIKE '] = "'%{$keyword}%'";
				}

				$this->session->set_userdata('finddesatualizados', $where);
			}
		}

		if (!$this->session->userdata('finddesatualizados')) {
			$data->sub(new DateInterval("PT24H"));
			$data_where = $data->format('Y-m-d H:i:s');

			$where['(cv.ultima_atualizacao <='] = "'{$data_where}'" . ' OR cv.ultima_atualizacao IS NULL)';
			$this->session->set_userdata('finddesatualizados', $where);
		}

		$where = $this->session->userdata('finddesatualizados');

		$limite = $pagina != false ? $pagina : 0;
		$config['base_url'] = site_url('veiculos/desatualizados');
		$config['uri_segment'] = 3;
		$config['total_rows'] = $this->veiculo->have_found_full($where);
		$config['per_page'] = $pagina == 'all' ? $config['total_rows'] : 30;

		$this->pagination->initialize($config);

		$select_veiculos = 'cv.code, usuario, nome cliente, veiculo, placa, serial, cv.ultima_atualizacao';
		$veiculos = $this->veiculo->find_full_with_track($where, $config['per_page'], $pagina, $select_veiculos);

		// $veiculos_unique = $this->unique_multidim_array($veiculos,'code');
		// $config['total_rows'] = count($veiculos_unique);

		$title = 'Veículos Desatualizados';
		$filter = $this->session->userdata('find_form_desatualizado');
		$dados = array(
			'titulo' => $title, 'veiculos' => $veiculos, 'msg' => $msg,
			'filter' => $filter, 'total_veiculos' => $config['total_rows']
		);

		$this->mapa_calor->registrar_acessos_url(site_url('/veiculos/desatualizados'));
		$this->load->view('fix/header', $dados);
		$this->load->view('veiculos/desatualizados');
		$this->load->view('fix/footer');
	}

	/**
	 * Envia e-mail com os veículos desatualizados para o usuário
	 **/
	public function enviar_desatualizados()
	{

		$usuarios_parametros = $this->usuario_gestor->get_parametros();

		$data_atual = new DateTime();

		foreach ($usuarios_parametros as $parametro) {

			$usuarios_veiculos = $this->veiculo->get_desatualizados_enviar($parametro->cnpj);
			$cliente_usuario = $this->usuario_gestor->get_cliente_usuario($parametro->cnpj);

			$veiculos = array();

			if (count($usuarios_veiculos)) {

				foreach ($usuarios_veiculos as $usuario_veiculo) {

					if ($usuario_veiculo->enviado === '1') {

						$tempo = $data_atual->diff(new DateTime($usuario_veiculo->enviado_data));

						if ($tempo->d > 2) {

							$veiculos[] = $usuario_veiculo;
						}
					} else {

						$veiculos[] = $usuario_veiculo;
					}
				}
			}

			if (count($veiculos)) {

				$emails = preg_split('[\,]', $parametro->email, -1, PREG_SPLIT_NO_EMPTY);

				$emails = array_merge($emails, array(
					'eduardo@showtecnologia.com'
				));

				$veiculos_ticket = '';

				foreach ($veiculos as $veiculo) {
					$veiculos_ticket .= "{$veiculo->placa}({$veiculo->serial})<br>";
				}

				$mensagem_ticket = 'Constatamos que os veículos listados abaixo estão desatualizados a mais de 48hs:<br>';
				$mensagem_ticket .= $veiculos_ticket;
				$mensagem_ticket .= 'Pedimos que responda este ticket com as informações necessárias, tais como, data, hora e local para que seja agendada a menutenção.<br>Obrigado!';

				$ticket = array(
					'assunto' => 'Veículos Desatualizados',
					'departamento' => 'Suporte',
					'mensagem' => $mensagem_ticket,
					'data_abertura' => date('Y-m-d H:i:s'),
					'ultima_interacao' => date('Y-m-d H:i:s'),
					'id_usuario' => '422',
					'status' => '1',
					'arquivo' => '',
					'status_anterior' => '1',
					'suporte' => 'sim',
					'nome_usuario' => '',
					'prioridade' => '3',
					'usuario' => 'Suporte',
					'cliente' => 'SHOW TEC',
				);

				$cliente_usuario->ticket = $this->ticket->abrir_ticket_manutencao($ticket);

				$mensagem = $this->template_emails->email_abertura_ticker_suporte($cliente_usuario, $veiculos);

				$sender = $this->sender->enviar_email('suporteshowtech@gmail.com', 'Show Tecnologia', 'suporteshowtech@gmail.com', 'Veículos Desatualizados', $mensagem, $emails);
				// $sender = $this->sender->enviar_email('suporteshowtec@gmail.com', 'Show Tecnologia', 'suporteshowtec@gmail.com', 'Veículos Desatualizados', $mensagem, $emails);

			}

			// die(json_encode($veiculos));

			// if (count($veiculos)) {

			// $emails = explode(',', $usuarios_parametros->email . ',erick.amaral@gmail.com');

			// $mensagem = $this->template_emails->email_veiculos_desatualizados_usuario($veiculos, $cliente_usuario);

			// $sender = $this->sender->enviar_email('suporteshowtec@gmail.com', 'Show Tecnologia', 'suporteshowtec@gmail.com', 'Veículos Desatualizados', $mensagem, $emails);
			// $ticket = array(
			// 	'assunto' => 'Veículos Desatualizados',
			// 	'departamento' => 'Suporte',
			// 	'mensagem' => 'Constatamos que os veículos listados abaixo estão desatualizados a mais de 48hs:<br>
			// 		ABD-9283(4932847239)<br>
			// 		SHD-3874(4932847239)<br>
			// 		BJD-9274(4932847239)<br>
			// 		JDH-2847(4932847239)<br>
			// 		KDO-2947(4932847239)<br>
			// 		Pedimos que responda este ticket com as informações necessárias, tais como data, hora e local para que seja marcada a manutenção dos mesmos.<br>
			// 		Obrigado!
			// 	',
			// 	'data_abertura' => date('Y-m-d H:i:s'),
			// 	'ultima_interacao' => date('Y-m-d H:i:s'),
			// 	'id_usuario' => $cliente_usuario->code,
			// 	'status' => '4',
			// 	'arquivo' => '',
			// 	'status_anterior' => '4',
			// 	'nome_usuario' => $cliente_usuario->nome_usuario,
			// 	'prioridade' => '3',
			// 	'usuario' => $cliente_usuario->usuario,
			// 	'cliente' => $cliente_usuario->nome,
			// );

			// if ($this->ticket->abrir_ticket_manutencao($ticket)) {

			// 	$mensagem = $this->template_emails->email_abertura_ticker_suporte();

			// 	$this->ticket->enviar_email_manutencao($ticket['departamento'], $ticket['assunto'], $ticket['nome_usuario'], $ticket['data_abertura'], $ticket['mensagem']);

			// }

			// }

		}

		// foreach ($usuarios as $usuario) {
		// 	$user = $this->usuario->get(array('code' => $id_usuario));
		// 	if (count($user)) {
		// 		$params = $this->auth->get_rel_params($user->CNPJ_);
		// 		if (count($params)) {
		// 			if($params->receber_veiculos_desatualizados == 'sim'){
		// 				$this->load->model('sender');
		// 				foreach ($veiculos as $v) {
		// 					$this->veiculo->atualizar_status_enviado($v->id);
		// 				}

		// 				// exit(pr($emails));
		// 				// $emails = explode(',', 'erick@showtecnologia.com');
		// 				// $mensagem = $this->template_emails->email_veiculos_desatualizados_usuario($veiculos);
		// 				// $this->sender->enviar_email('suporteshowtec@gmail.com', 'Show Tecnologia', 'erick@showtecnologia.com', 'Veículos Desatualizados', $mensagem, $emails);

		// 				$emails = $params->email.',eduardo@showtecnologia.com,suportecomercial@showtecnologia.com,erica@showtecnologia.com,erick@showtecnologia.com';
		// 				$dados_email = array(
		// 					'cnpj' => $params->cnpj,
		// 					'item_envio' => 'equipamentos_desatualizados',
		// 					'conteudo_envio' => $this->template_emails->email_veiculos_desatualizados_usuario($veiculos),
		// 					'assunto_envio' => 'Veículos Desatualizados',
		// 					'emails_envio' => $emails
		// 				);
		// 				$this->veiculo->enviar_email_desatualizados($dados_email);
		// 			}
		// 		}
		// 	}
		// }
	}

	/**
	 * Agenda manutenção do veículo
	 **/
	public function agendar_manutencao()
	{
		$this->auth->is_logged('admin');
		if ($this->veiculo->salva_manutencao($this->input->post())) {
			exit(json_encode(array('success' => true, 'id' => $this->input->post('id'))));
		}

		exit(json_encode(array('success' => false)));
	}

	/**
	 * Pega o usuário e cliente
	 **/
	public function get_usuario_cliente($placa, $serial)
	{
		$this->auth->is_logged('admin');
		return $this->veiculo->find_usuario_cliente(array('placa' => $placa, 'serial' => $serial));
	}

	public function posicao_veiculo($placa)
	{

		$dados['posicao'] = false;

		$seriais = $this->contrato->verificar_placa_serial($placa);

		if ($seriais) {

			$tamanho = count($seriais);

			if ($tamanho == 1) {
				foreach ($seriais as $ser) {
					$serial = $ser->serial;
				}

				if ($veiculo = $this->veiculo->get_resposta($serial)) {

					$latitude = isset($veiculo->X) ? $veiculo->X : '';
					$longitude = isset($veiculo->Y) ? $veiculo->Y : '';

					if ($longitude != '' && $latitude != '') {
						$endereco = pegar_endereco_referencias($latitude, $longitude);
					} else {
						$endereco = 'Endereço não encontrado';
					}

					$dados = array(
						'data' => isset($veiculo->DATA) ? $veiculo->DATA : ' ',
						'serial' => isset($veiculo->ID) ? $veiculo->ID : ' ',
						'ignicao' => isset($veiculo->IGNITION) ? $veiculo->IGNITION : '0',
						'gps' => isset($veiculo->GPRS) ? $veiculo->GPRS : '0',
						'gprs' => isset($veiculo->GPRS) ? $veiculo->GPRS : '0',
						'velocidade' => isset($veiculo->VEL) ? $veiculo->VEL : '0',
						'endereco' => $endereco
					);

					$dados['posicao'] = true;
				}
			}
			$dados['tamanho_seriais'] = $tamanho;
		}

		$dados['seriais'] = $seriais;
		$dados['placa'] = $placa;

		$this->load->view('veiculos/posicao_veiculo', $dados);
	}

	public function editar_veiculo($placa)
	{

		$seriais = $this->contrato->verificar_placa_serial($placa);

		if ($seriais) {

			$tamanho = count($seriais);

			if ($tamanho == 1) {
				foreach ($seriais as $ser) {
					$dados['serial'] = $ser->serial;
				}
			}

			$dados['tamanho_seriais'] = $tamanho;
		}

		$dados['seriais'] = $seriais;
		$dados['placa'] = $placa;

		$this->load->view('veiculos/editar_veiculo', $dados);
	}

	public function veiculo_editar($placa)
	{

		$veiculo = $this->input->post('veiculo');
		$prefixo = $this->input->post('prefixo');
		$taxi = $this->input->post('taxi');

		if ($taxi == 1) {
			$imagem = "taxi_eptc_10.png";
		} else {
			$imagem = "caminhao3.png";
		}

		$dados = array(
			'veiculo' => $veiculo, 'prefixo_veiculo' => $prefixo, 'taxi' => $taxi, 'imagem' => $imagem
		);

		if ($this->veiculo->atualizar_veiculo($placa, $dados)) {
			$acao = array(
				'data_criacao' => date('Y-m-d H:i:s'),
				'usuario' => $usuario_email,
				'placa' => $placa,
				'acao' => 'O usuário ' . $usuario_email . ' atualizou o veículo de placa ' . $placa
			);
			$ret = $this->log_veiculo->add($acao);
			echo json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success"><p>Dados do veículo atualizados com sucesso!</p></div>'));
		} else {
			echo json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-error"><p>ERRO - Dados do veículo não atualizados!</p></div>'));
		}
	}

	public function desvincular_placas($placa, $serial)
	{
		$dados['placa'] = explode(':', $placa);
		$dados['serial'] = $serial;
		$dados['retorno'] = false;

		$dados['titulo'] = 'Show Tecnologia';

		$this->load->view('fix/header_NS', $dados);
		$this->load->view('veiculos/desvincular_placas');
		$this->load->view('fix/footer_NS');
	}

	public function desvincular_confirmar($serial, $placa)
	{
		$dados['placa'] = $placa;
		$dados['serial'] = $serial;
		$dados['titulo'] = 'Show Tecnologia';
		$this->load->view('veiculos/desvincular_confirmar', $dados);
	}

	public function desvincular_confirmado($serial, $placa)
	{
		$usuario_email = $this->auth->get_login('admin', 'email');
		$desvincular = true;
		if ($this->veiculo->atualizar_veiculo($placa, $serial, $desvincular)) {
			$acao = array(
				'data_criacao' => date('Y-m-d H:i:s'),
				'usuario' => $usuario_email,
				'placa' => $placa,
				'acao' => 'Serial ' . $serial . ' foi desvinculado a placa ' . $placa . ' pelo usuário ' . $usuario_email
			);
			$ret = $this->log_veiculo->add($acao);
			$dados['retorno'] = '<div class="alert alert-success">Serial ' . $serial . ' foi desvinculado da placa ' . $placa . '!</div>';
		} else {
			$dados['retorno'] = '<div class="alert alert-error">Erro - Serial ' . $serial . ' não desvinculado da placa ' . $placa . '!</div>';
		}
		$placas = $this->equipamento->get_equipamentos_desvincular($serial, $placa);
		$dados['placa'] = false;
		if ($placas && $placas != null) {
			$dados['placa'] = implode(',', $this->equipamento->get_equipamentos_desvincular($serial, $placa));
		}

		$dados['serial'] = $serial;
		$dados['titulo'] = 'Show Tecnologia';
		$this->load->view('fix/header', $dados);
		$this->load->view('veiculos/desvincular_placas');
		$this->load->view('fix/footer');
	}

	public function remover_usuario_veiculo($placa)
	{
		$usuario_code = $this->input->post('usuario');
		$usu = $this->usuario_gestor->get(array('code' => $usuario_code));

		if ($this->veiculo->remover_usuario_veiculo($usu->CNPJ_, $placa)) {
			$mensagem = '<div class="alert alert-success">Usuário <b>' . $usu->usuario . '</b> removido da placa ' . $placa . '!</div>';
		} else {
			$mensagem = '<div class="alert alert-info">Atenção - Usuário <b>' . $usu->usuario . '</b> ainda sem vinculado com a placa ' . $placa . '!</div>';
		}

		echo json_encode(array('certo' => true, 'msg' => $mensagem));
	}

	public function log_veiculos_old($pagina = false)
	{
		$dados['titulo'] = 'Logs Veículos - Show Tecnologia';

		if ($this->input->get()) {
			$dados['logs'] = $this->veiculo->get_logs_search($this->input->get());
		} else {
			$paginacao = $pagina != false ? $pagina : 0;
			$this->mapa_calor->registrar_acessos_url(site_url('/veiculos/log_veiculos'));
			$config['base_url'] = site_url('/veiculos/log_veiculos');
			$config['uri_segment'] = 3;
			$config['total_rows'] = $this->veiculo->total_log();
			$config['per_page'] = 20;

			$this->pagination->initialize($config);

			$dados['logs'] = $this->veiculo->get_logs($paginacao, $config['per_page']);
		}

		$this->load->view('fix/header', $dados);
		$this->load->view('veiculos/log_veiculo', $dados);
		$this->load->view('fix/footer');
	}

	public function log_veiculos()
	{
		$dados['titulo'] = lang('logs') . ' - ' . lang('cadastro_de_veiculos');
		$dados['load'] = array('ag-grid', 'select2', 'mask');
		$config['base_url'] = site_url('/veiculos/log_veiculos');

		$this->mapa_calor->registrar_acessos_url(site_url('/veiculos/log_veiculos'));
		
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('logs/cadastro_veiculos');
		$this->load->view('fix/footer_NS');
	}

	public function get_log_veiculos()
	{
		$startRow = $this->input->post('startRow');
		$endRow = $this->input->post('endRow');
		$coluna = $this->input->post('coluna');
		$valorBusca = $this->input->post('valorBusca');
		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');
		$config['per_page'] = $endRow - $startRow;
		$valor = false;

		if ($coluna) {
			if ($coluna == 'placa') {
				$where = array(
					'placa_antes LIKE' => '%'. $valorBusca . '%',
					'placa_depois LIKE' => '%'. $valorBusca . '%'
				);
			} else if ($coluna == 'serial') {
				$where = array(
					'serial_antes LIKE' => '%'. $valorBusca . '%',
					'serial_depois LIKE' => '%'. $valorBusca . '%'
				);
				$valor = $valorBusca;
			} else if ($coluna == 'usuario') {
				$where = array(
					'usuario LIKE' => '%'. $valorBusca . '%'
				);
			} else if ($coluna == 'data') {
				$where = array(
					'data >=' => $dataInicial . " 00:00:00",
					'data <=' => $dataFinal . " 23:59:59"
				);
			} else {
				$where = array(
					$coluna .' LIKE' => '%'. $valorBusca . '%'
				);
			}
			$result = $this->veiculo->get_cad_logs_ajustado($startRow, $config['per_page'], 'desc', '*', $where, $valor);
		} else {
			$result = $this->veiculo->get_cad_logs_ajustado($startRow, $config['per_page']);
		}

		if ($result) {
			if ($result['sucess']) {
				foreach ($result['itens'] as &$item) {
					$item->antes = json_decode($item->antes);
					$item->depois = json_decode($item->depois);
		
					if ($item->antes) {
						$item->antes->valor = "Antes";
						foreach ($item->antes as &$itemAntes) {
							if ($itemAntes === null) {
								$itemAntes = '';
							}
						}
					} else {
						$item->antes = new stdClass();
						$item->antes->valor = "Antes";
						$item->antes->veiculo = "";
						$item->antes->placa = "";
						$item->antes->serial = "";
						$item->antes->CPNJ_ = "";
						$item->antes->imagem = "";
					}
					if ($item->depois) {
						$item->depois->valor = "Depois";
						foreach ($item->depois as &$itemDepois) {
							if ($itemDepois === null) {
								$itemDepois = '';
							}
						}
					} else {
						$item->depois = new stdClass();
						$item->depois->valor = "Depois";
						$item->depois->veiculo = "";
						$item->depois->placa = "";
						$item->depois->serial = "";
						$item->depois->CPNJ_ = "";
						$item->depois->imagem = "";
					}
					
					$item->details = array(
						$item->antes,
						$item->depois
					);
				}

				echo json_encode(array(
					"success" => true,
					"rows" => $result['itens'],
				));
			} else {
				echo json_encode(array(
					"success" => false,
					"message" => $result['mensagem'],
				));
			}
			
		} else {
			echo json_encode(array(
                "success" => false,
                "message" => 'Erro ao listar o log de Cadastro Veículos!',
            ));
		}

	}

	public function cadastrar_veiculo_lote()
	{
		$veiculos = json_decode($this->input->post('veiculos'));
		$id_cliente = $this->input->post('id_cliente');
		$id_contrato = $this->input->post('id_contrato');

		// busca fuso-horario do cliente para replicar no contrato_veiculo
		$cliente = $this->cliente->get_clientes($id_cliente)[0];
		$fuso_horario = $cliente->gmt;

		if ($veiculos) {
			foreach ($veiculos as $key => $veiculo) {
				$dados['contrato_veic'][] = array(
					'id_contrato' => $id_contrato,
					'id_cliente' => $id_cliente,
					'status' => 'ativo',
					'placa' => $veiculo->Placa,
					'data_cadastro' => date('Y-m-d H:i:s'),
					'fuso_horario' => $fuso_horario
				);

				$dados['cadastro_veic'][] = array(
					'id_usuario' => $id_cliente,
					'status' => 1,
					'placa' => $veiculo->Placa,
					'veiculo' => $veiculo->Nome,
					'serial' => $veiculo->Serial,
					'data_instalacao' => date('Y-m-d H:i:s')
				);
			}

			if ($table = $this->veiculo->cadastrar_veiculo_lote($id_contrato, $id_cliente, $dados)) {
				echo json_encode(array('status' => 'OK', 'msg' => 'Veículos cadastrados', 'table' => $table));
			} else {
				echo json_encode(array('status' => 'ERRO', 'msg' => 'Veículo(s) já existem no sistema ou contrato não possui vagas suficientes para veículos ativos!'));
			}
		} else {
			echo json_encode(array('status' => 'ERRO', 'msg' => 'Por favor, informe os veículos!'));
		}
	}

	public function lista_placas_usuario($id_usuario)
	{
		$dados = $this->veiculo->list_placas_usuario($id_usuario);
		if ($dados) {
			echo json_encode(array('status' => 'OK', 'results' => $dados));
		} else {
			return false;
		}
	}

	public function listar_grupos_veiculos()
	{
		$placa = $this->input->post('placa');
		$dados = $this->veiculo->getGruposVeiculos($placa);
		if ($dados) {
			echo json_encode(array('status' => 'OK', 'results' => $dados));
		} else {
			return false;
		}
	}

	public function listar_grupos_veiculos_ag()
	{
		$placa = $this->input->post('placa');
		$dados = $this->veiculo->getGruposVeiculos($placa);

		if ($dados) {
			$data = [];
			$x = 0;
			foreach ($dados as $funcionario) {
				$data[$x] =
					[
						"espelhamento" => ucwords(strtolower($this->tratarEspelhamento($funcionario->espelhamento))),
						"grupo" => ucwords(strtolower($funcionario->nome_grupo)),
						"cliente" => ucwords(strtolower($funcionario->nome_cliente)),
					];
				$x++;
			}

			echo json_encode($data);
		} else {
			return [];
		}
	}

	 private function tratarEspelhamento($dados) {
        if ($dados == 0) {
            return "Contrato";
        } else if ($dados == 1) {
            return "Espelhamento Mhs";
        } else if ($dados == 2) {
            return "Compartilhamento de sinal";
        } else {
            return "Rejeitado";
        }
    }

	public function listar_espelhamentos_veiculos()
	{
		error_reporting(0);
		$serial = $this->input->POST('serial');
		$this->load->helper('espelhamento_helper');

		$response = ConsultaCentraisNumeroSerie_api($serial);
		$dados = json_decode($response);

		if ($dados) {
			echo json_encode(array('status' => 'OK', 'results' => $dados->ListaCentrais));
		} else {
			return false;
		}

		die;
	}

	public function listarDadosPost()
	{
		$terminal = $this->input->post('terminal');

		$retorno = json_decode(listar_dados_post($terminal));
		if (!empty($retorno)) {
			echo json_encode($retorno);
		}
		return;
	}

	public function listarDadosPostByData()
	{
		$terminal = $this->input->post('terminal');

		$retorno = json_decode(listar_dados_post_by_data($terminal));
		if (!empty($retorno)) {
			echo json_encode($retorno);
		}
		return;
	}

	public function listarDadosPostByPeriod()
	{
		$this->load->helper('util_helper');

		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');
		$terminal = $this->input->post('terminal');

		$retorno = (listar_dados_post_by_period($dataInicial, $dataFinal, $terminal));

		echo json_encode($retorno);
	}

	public function listarDadosPostCtrl()
	{
		$terminal = $this->input->post('terminal');

		$retorno = json_decode(listar_dados_post_ctrl($terminal));
		if (!empty($retorno)) {
			echo json_encode($retorno);
		}
		return;
	}

	public function listarDadosPostCtrlByData()
	{
		$terminal = $this->input->post('terminal');

		$retorno = json_decode(listar_dados_post_ctrl_by_data($terminal));
		if (!empty($retorno)) {
			echo json_encode($retorno);
		}
		return;
	}

	public function listarDadosPostCtrlByPeriod()
	{
		$this->load->helper('util_helper');

		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');
		$terminal = $this->input->post('terminal');

		$retorno = (listar_dados_post_ctrl_by_period($dataInicial, $dataFinal, $terminal));

		echo json_encode($retorno);
	}

	public function listarDadosPostIscasByData()
	{
		$terminal = $this->input->post('terminal');

		$retorno = json_decode(listar_dados_post_iscas_by_data($terminal));
		if (!empty($retorno)) {
			echo json_encode($retorno);
		}
		return;
	}

	public function listarDadosPostIscasByPeriod()
	{
		$this->load->helper('util_helper');

		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');
		$terminal = $this->input->post('terminal');

		$retorno = (listar_dados_post_iscas_by_period($dataInicial, $dataFinal, $terminal));

		echo json_encode($retorno);
	}


	public function listarDadosPostOmniFrotaByData()
	{
		$terminal = $this->input->post('terminal');

		$retorno = json_decode(listar_dados_post_omnifrota_by_data($terminal));
		if (!empty($retorno)) {
			echo json_encode($retorno);
		}
		return;
	}

	public function listarDadosPostOmniFrotaByPeriod()
	{
		$this->load->helper('util_helper');

		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');
		$terminal = $this->input->post('terminal');

		$retorno = (listar_dados_post_omnifrota_by_period($dataInicial, $dataFinal, $terminal));

		echo json_encode($retorno);
	}

	public function listarDadosPostOmniSafeByData()
	{
		$terminal = $this->input->post('terminal');

		$retorno = json_decode(listar_dados_post_omnisafe_by_data($terminal));
		if (!empty($retorno)) {
			echo json_encode($retorno);
		}
		return;
	}

	public function listarDadosPostOmniSafeByPeriod()
	{
		$this->load->helper('util_helper');

		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');
		$terminal = $this->input->post('terminal');

		$retorno = (listar_dados_post_omnisafe_by_period($dataInicial, $dataFinal, $terminal));

		echo json_encode($retorno);
	}

	public function listarDadosPostOmniSafeCtrlByData()
	{
		$terminal = $this->input->post('terminal');

		$retorno = json_decode(listar_dados_post_omnisafe_ctrl_by_data($terminal));
		if (!empty($retorno)) {
			echo json_encode($retorno);
		}
		return;
	}

	public function listarDadosPostOmniSafeCtrlByPeriod()
	{
		$this->load->helper('util_helper');

		$dataInicial = $this->input->post('dataInicial');
		$dataFinal = $this->input->post('dataFinal');
		$terminal = $this->input->post('terminal');

		$retorno = (listar_dados_post_omnisafe_ctrl_by_period($dataInicial, $dataFinal, $terminal));

		echo json_encode($retorno);
	}

	function unique_multidim_array($array, $key)
	{
		$temp_array = array();
		$i = 0;
		$key_array = array();

		foreach ($array as $val) {
			if (!in_array($val->$key, $key_array)) {
				$key_array[$i] = $val->$key;
				$temp_array[$i] = $val;
			}
			$i++;
		}

		return $temp_array;
	}
}
