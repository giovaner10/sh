<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Veiculos extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('cliente');
		$this->load->model('veiculo');
		$this->load->model('usuario_gestor');
		$this->load->model('contrato');
		$this->load->model('sender');
		$this->load->model('ticket');
		$this->load->model('template_emails');
	}
	
	public function tab_listar_gestor($id_cliente, $pagina = false){
		$this->auth->is_logged('admin');
		
		if ($pagina == 'tudo') {

			$dados['link_tudo'] = '<a href="'.base_url('veiculos/tab_listar_gestor/'.$id_cliente.'/').'">Voltar</a>';
			$dados['total_gestor'] = $this->veiculo->total_lista_gestor(array('id_cliente' => $id_cliente));
			$dados['veiculos'] = $this->veiculo->listar_gestor(array('id_cliente' => $id_cliente), 0);

		} else {
		
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
			$config['base_url'] = base_url().'veiculos/tab_listar_gestor/'.$id_cliente.'/';
			$config['uri_segment'] = 4;
			$config['total_rows'] = $this->veiculo->total_lista_gestor(array('id_cliente' => $id_cliente));
			$this->pagination->initialize($config);
			

			$dados['link_tudo'] = '<a href="'.base_url('veiculos/tab_listar_gestor/'.$id_cliente.'/tudo').'">Todos</a>';
			$dados['total_gestor'] = $config['total_rows'];
			$dados['veiculos'] = $this->veiculo->listar_gestor(array('id_cliente' => $id_cliente), $paginacao, 10);

		}
		
		$ctr_cli = $this->contrato->listar(array('ctr.id_cliente' => $id_cliente));
		$dados['qtd_veiculos_ctr'] = 0;
		if ($ctr_cli){
			foreach($ctr_cli as $contrato){
				$dados['qtd_veiculos_ctr'] += $contrato->quantidade_veiculos;
			}
		}
		
		$dados['total_contrato'] = $this->veiculo->total_veiculos_contrato("id_cliente = {$id_cliente}");
		$dados['id_cliente'] = $id_cliente;

		// echo '<pre>';
		// print_r($dados['veiculos']);
		// echo '</pre>';

		// exit();
		
		$this->load->view('clientes/tab_veiculos', $dados);
		
	}
	
	public function tab_filtrar_gestor($id_cliente, $placa = false, $pagina = false){
		$this->auth->is_logged('admin');
		
		if($this->input->post())
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
		$config['base_url'] = base_url().'veiculos/tab_filtrar_gestor/'.$id_cliente.'/'.$placa.'/';
		$config['uri_segment'] = 5;
		$config['total_rows'] = $this->veiculo->total_filtro_gestor("id_cliente = $id_cliente", $placa);
		$this->pagination->initialize($config);
		
		$dados['placa'] = $placa;
		
		$ctr_cli = $this->contrato->listar(array('ctr.id_cliente' => $id_cliente));
		$dados['qtd_veiculos_ctr'] = 0;
		if ($ctr_cli){
			foreach($ctr_cli as $contrato){
				$dados['qtd_veiculos_ctr'] += $contrato->quantidade_veiculos;
			}
		}
		
		$dados['total_gestor'] = $this->veiculo->total_lista_gestor("id_cliente = $id_cliente");
		$dados['total_contrato'] = $this->veiculo->total_veiculos_contrato("id_cliente = {$id_cliente}");
		$dados['veiculos'] = $this->veiculo->filtrar_gestor("id_cliente = $id_cliente", $placa, $paginacao, 10);
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
	public function desatualizados($pagina = false)
	{
		$this->auth->is_logged('admin');
		$dados['titulo'] = 'Veículos Desatualizados';

		if ($pagina == 'tudo') {

			$dados['desatualizados'] = $this->veiculo->get_desatualizados();

		} else if ($this->input->get()) {
			
			$dados['desatualizados'] = $this->veiculo->get_desatualizados(array($this->input->get('coluna') => $this->input->get('palavra')));
		
		} else {

			$limite = $pagina != false ? $pagina : 0;
			$config['base_url'] = site_url('/veiculos/desatualizados');
			$config['uri_segment'] = 3;
			$config['total_rows'] = count($this->veiculo->get_desatualizados());
			$config['per_page'] = 30;

			$this->pagination->initialize($config);

			$dados['desatualizados'] = $this->veiculo->get_desatualizados(null, $limite, $config['per_page']);

		}
		
		$this->load->view('fix/header', $dados);
		$this->load->view('veiculos/desatualizados', $dados);
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
					'erickamaral@gmail.com',
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
					'nome_usuario' => 'erickamaral@gmail.com',
					'prioridade' => '3',
					'usuario' => 'Suporte',
					'cliente' => 'SHOW TEC',
				);

				$cliente_usuario->ticket = $this->ticket->abrir_ticket_manutencao($ticket);

				$mensagem = $this->template_emails->email_abertura_ticker_suporte($cliente_usuario, $veiculos);

				$sender = $this->sender->enviar_email('erickamaral@gmail.com', 'Show Tecnologia', 'erickamaral@gmail.com', 'Veículos Desatualizados', $mensagem, array('erickamaral@gmail.com'));
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
}