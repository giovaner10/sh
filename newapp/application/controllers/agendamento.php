<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agendamento extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('agenda');
		$this->load->model('mapa_calor');
	}
	
	public function index ($pagina = false){
		
		$titulo = 'Agendamento de serviços';
		$msg = array();
		$find = array();
		
		if ($this->input->post()){

			$busca = $this->input->post('keyword');
			$tipo = $this->input->post('tipo');
			$dt_ini = data_for_unix($this->input->post('dt_ini'));
			$dt_fim = data_for_unix($this->input->post('dt_fim'));
				
			$rules = array(array('field' => 'dt_ini', 'label' => 'Data Início', 'rules' => 'required'),
					array('field' => 'dt_fim', 'label' => 'Data Fim', 'rules' => 'required'));
				
			$this->form_validation->set_rules($rules);
				
			if ($this->form_validation->run() === false){
			
				$msg['success'] = false;
				$msg['msg'] = validation_errors('<li>','</li>');
			
			}else{
				if ($dt_ini <= $dt_fim){
						
					$find = array('data_agenda >=' => $dt_ini.' 00:00:00',
							'data_agenda <=' => $dt_fim.' 23:59:59');
						
					if ($busca != ''){
						switch ($tipo){
							case 'prefixo':
								$find['prefixo'] = $busca;
								break;
							case 'placa':
								$find['placa'] = $busca;
								break;
						}
					}
			
				}else{
					$msg['success'] = false;
					$msg['msg'] = 'Data início deve ser menor ou igual a data fim.';
				}
			}
		
		}
		
		$paginacao = $pagina != false  ? $pagina : 0;
		$config['base_url'] = site_url('agendamento/index/');
		$config['uri_segment'] = 3;
		$config['total_rows'] = $this->agenda->has_found($find);
		
		$this->pagination->initialize($config);
		
		$agendados = $this->agenda->find($find, $pagina, 15);

		$dados = array('titulo' => $titulo, 'agendados' => $agendados);

		// $this->mapa_calor->registrar_acessos_url(site_url('/agendamento'));

		$this->load->view('fix/header4', $dados);
		$this->load->view('cadastros/agenda/listar');
		$this->load->view('fix/footer4');
		
	}
	
	public function imprimir($dt_ini, $dt_fim){
			
		$find = array('data_agenda >=' => $dt_ini.' 00:00:00', 
						'data_agenda <=' => $dt_fim.' 23:59:59');
		
		$agendados = $this->agenda->find($find, 0, 9999999999);

		$dados = array('agendados' => $agendados, 'dt_ini' => $dt_ini, 'dt_fim' => $dt_fim);
		
		$this->load->view('cadastros/agenda/imprimir', $dados);
		
	}
	
	public function ajax_form_add (){
		
		$this->load->model('veiculo');
		$this->load->model('configuracao');
		
		$veiculos = $this->veiculo->get_veiculo(array(), 9999999, 'placa, serial');
		
		$messages = $this->configuracao->find_message(array('tipo' => 1), 0, 9999999);
		
		$placas = array();
		if (count($veiculos)){
			foreach ($veiculos as $veiculo){
				$placas[] = "{$veiculo->placa}#{$veiculo->prefixo_veiculo}";
			}
		}
		
		$dados = array( 'placas' => json_encode($placas), 'messages' => $messages );
		
		$this->load->view('cadastros/agenda/form_agenda', $dados);
		
	}
	
	public function ajax_save (){
		
		$post = $this->input->post();
		$usuario = $this->auth->get_login('admin', 'user');
		$this->load->model('configuracao');
		
		$retorno = array('success' => false, 'msg' => '', 'agenda' => false);
		
		if ($post){

			$rules_form = array(array('field' => 'placa', 'label' => 'Placa/prefixo', 'rules' => 'required' ),
								array('field' => 'data', 'label' => 'Data', 'rules' => 'required' ),
								array('field' => 'hora', 'label' => 'Hora', 'rules' => 'required' ),
								array('field' => 'servico_agenda', 'label' => 'Serviço', 'rules' => 'required')
							);
			
			if ($post['fone_sms'] != ''){
				$rules_form[] = array('field' => 'id_msg', 'label' => 'Mensagem SMS', 'rules' => 'required'); 
			}
			
			$this->form_validation->set_rules($rules_form);
			
			if (!$this->form_validation->run()){
				$retorno['msg'] = validation_errors('<li>', '</li>');
			}else{
				
				$placa_prefixo = explode("#", $post['placa']);
				$placa = trim($placa_prefixo[0]);
				$prefixo = trim($placa_prefixo[1]);
				$data_agenda = data_for_unix($post['data']).' '.$post['hora'].':00';
					
				$d_insert = array('id_usuario' => $usuario, 'placa' => $placa, 'prefixo' => $prefixo,
						'status_agenda' => 0, 'servico_agenda' => $post['servico_agenda'],
						'data_agenda' => $data_agenda, 'fone_sms' => $post['fone_sms'], 
						'id_msg' => $post['id_msg']);
				
				try{
				
					$id_agenda = $this->agenda->save($d_insert);
					$agenda = $this->agenda->get(array('id_agenda' => $id_agenda));
					$retorno['agenda'][] = $agenda;
					$retorno['success'] = true;
					$retorno['msg'] = 'Agendamento salvo com sucesso!';
					
					if (!empty($post['fone_sms'])){
						
						$this->load->library('parser');
						
						$dia = substr($post['data'], 0, 5);
						$hora = $post['hora'];
						$data_parser = array('dd/mm' => $dia, 'hh:mm' => $hora); 
						$message = $this->configuracao->get_message(array('id_msg' => $agenda->id_msg));
						
						$msg_sms = $this->parser->parse_string($message->mensagem, $data_parser, true);
						
						$fone = '55'.$post['fone_sms'];
						$sms = array('prefixo' => $prefixo, 'serial' => '',
									'placa' => $placa, 'celular_log' => $fone, 'msg_sms' => $msg_sms,
									'last_track' => '', 'CNPJ_' => '',
									'id_usuario' => 0);
						
						$this->db->insert('systems.envio_sms', $sms);
						
					}
				
				}catch (Exception $e){
				
					$retorno['msg'] = $e->getMessage();
				
				}
					
			}
			
		}
		
		echo json_encode($retorno);
		
	}
	
	public function agendamento_eptc(){
		
		$sql = "SELECT uf.fone, ug.CNPJ_, ug.code id_usuario,
					(SELECT COUNT(1) FROM systems.cadastro_veiculo WHERE CNPJ_ = ug.CNPJ_ AND serial != '') instalado
					FROM showtecsystem.usuario_gestor AS ug
					JOIN showtecsystem.usuario_fone AS uf ON uf.id_usuario = ug.code
					WHERE uf.tipo = 1 AND ug.CNPJ_ BETWEEN '1000' AND '5100'
					HAVING instalado = 0
					ORDER BY ug.CNPJ_ ASC
				LIMIT 0, 5000";
		
		$feriados = array('2015-04-03', '2015-04-21', '2015-05-01', '2015-06-04', '2015-09-07',
							'2015-10-12', '2015-11-02', '2015-11-15', '2015-12-25');
		
		$veiculos = $this->db->query($sql)->result();
		$desinstalados = $this->group_prefixo($veiculos);
		
		if (count($desinstalados)){
			
			$dt_agendamento = new DateTime('2015-03-09 08:00:00');
			$agenda_insert = array();
			
			foreach ($desinstalados as $prefixo => $infos){
				
				inicio:
				$data = date('Y-m-d H:i:s', $dt_agendamento->getTimestamp());
				$day = substr($data, 0, 10);
				
				if (in_array($day, $feriados)){
					$dt_agendamento->add(new DateInterval('P1D'));
					goto inicio;
				}elseif (date("w", $dt_agendamento->getTimestamp()) == 6){
					$dt_agendamento->add(new DateInterval('P2D'));
					goto inicio;
				}elseif (date("w", $dt_agendamento->getTimestamp()) == 0){
					$dt_agendamento->add(new DateInterval('P1D'));
					goto inicio;
				}

				foreach ($infos as $info){
					
					$d = date("d/m", strtotime($data));
					$h = date("H:i", strtotime($data));
					
					$msg_sms = "Voce foi escolhido para instalar o gps da eptc dia {$d} as {$h} Necessario presenca do permissionario arrendatario Av Julio de Castilhos 566";
				
					$fone = '55'.$info->fone;
					$sms = array('prefixo' => $prefixo, 'serial' => '',
									'placa' => '', 'celular_log' => $fone, 'msg_sms' => $msg_sms,
									'last_track' => '', 'CNPJ_' => '',
									'id_usuario' => 0);
					
					$agenda_insert['sms'][]	= $sms; 	
				}
				
				$agenda_insert['agenda'][] = array('id_usuario' => 0, 'prefixo' => $prefixo, 'placa' => '',
												'status_agenda' => 0, 'servico_agenda' => 1,
												'data_agenda' => $data);
				
				$dt_agendamento->add(new DateInterval('PT15M'));
				
				$day_of_week = date("w", $dt_agendamento->getTimestamp());
				$dia = date('Y-m-d', $dt_agendamento->getTimestamp());
				$hora = date("H:i:s", $dt_agendamento->getTimestamp());				
				
				if ($hora == '11:15:00'){
					$dt_agendamento->add(new DateInterval('PT1H45M'));
				}elseif ($hora == '17:15:00'){
					$dt_agendamento->add(new DateInterval('PT14H45M'));
					$new_day_of_week = date("w", $dt_agendamento->getTimestamp());
					if ($new_day_of_week > 5)
						$dt_agendamento->add(new DateInterval('P2D'));
				}
			}
		}
		
		if (count($agenda_insert['agenda'])){
			$this->db->insert_batch('agenda', $agenda_insert['agenda']);
			
			$this->db->insert_batch('systems.envio_sms', $agenda_insert['sms']);
		}
	}
	
	protected function group_prefixo ($desinstalados){
		
		$prefixos_agrupados = array();
		
		if (count($desinstalados)){
			foreach ($desinstalados as $veiculo){
				$prefixos_agrupados[$veiculo->CNPJ_][] = $veiculo;
			}
		}
		
		return $prefixos_agrupados;
	}
	
}