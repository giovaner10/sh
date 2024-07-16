<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Gerencia_equipamentos extends CI_Controller {

	private $mensagem = NULL;

	function __construct() {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('login');
		$this->load->model('logistica');
		$this->load->model('log_logistica');
		$this->load->model('equipamento');
		$this->load->model('chip');
		$this->load->model('instalador');
		$this->load->model('cliente');
		$this->load->library('session');
		$this->load->model('mapa_calor');
	}

	private function tratarDados(){
		if ($dadosBanco = $this->logistica->listarOrdem()) {
        $dados = array();
        $i = 0;

        foreach ($dadosBanco as $dados) {
			if ($dados->cliente) {
 				if ($dados->nome_clie) {
					$pessoa = '<b title='.$dados->nome_clie.'>Cli.</b> '.$dados->nome_clie;
 				} else {
 					$pessoa = 'Cód. Cliente: '.$dados->cliente;
 				}

 			} else {
 				if ($dados->nome_tec) {
					$pessoa = '<b title='.$dados->nome_tec.'>Téc.</b> '.$dados->nome_tec.' '.$dados->sobrenome_tec;
 				} else {
 					$pessoa = 'Cód. Instalador: '.$dados->tecnico;
 				}
			}

			// TRATA TIPO OS //
			if ($dados->tipoOS == 1) $tipoOS = 'Manutenção';
            elseif ($dados->tipoOS == 2) $tipoOS = 'Instalação';
			else $tipoOS = 'Outro';

			// DESABILITA BTN'S
			$first_btn = array("disabled", "true");
			$second_btn = array("disabled", "true");
			$tecParaCli = "";
			$upload = "";

			// TRATA STATUS DA OS
			switch ($dados->statusOS) {
				case '0':
					$estado = 'Bloqueado';
					break;
				case '1':
					$estado = 'Configurado';
					break;
				case '2':
					$estado = 'Em teste';
					break;
				case '3':
					$estado ='Manutenção'; #chegou do cliente ou técnico, fica em estado de manutenção
					break;
				case '4':
					$estado = 'Enviado ao cliente';
					$upload = 7;
					$first_btn = array("", "false");
					break;
				case '5':
					$estado = 'Em uso (instalado)';
					break;
				case '6':
					$estado = 'Enviado ao Instalador';
					$upload = 8;
					$first_btn = array("", "false");
					break;
				case '7':
					$estado ='Posse do cliente';
					$tecParaCli = 0;
					$second_btn = array("", "false");
					break;
				case '8':
					$estado ='Posse do técnico';
					$tecParaCli = 1;
					$second_btn = array("", "false");
					break;
				case '9':
					$estado ='Estornado';
					break;
				default:
					$estado = '';
					break;
            }

			if ($chips = $this->logistica->get_chips($dados->equipamento)) {
				$num = $chips->numero;
				$inforOperadora = $chips->operadora;
			} else {
				$inforChip = "Sem número.";
				$inforOperadora = "Sem número.";
			}

			// Botões de administrar //
			$botaoDataChegada = '<a id="um" type="button" class="btn btn-danger btn-xs '.$first_btn[0].'" data-toggle="modal" data-id="'.$dados->idlogistica.'" data-serial="'.$dados->serial.'" data-lastdate="'.date('Y-m-d', strtotime($dados->dataEnvio)).'" data-upload="'.$upload.'" data-target="#modal_ordem_11" title="Assinalar data de chegada" aria-disabled="'.$first_btn[1].'" ><i class="fa fa-sign-in" aria-hidden="true"></i></a>';
			$botaoConfirmarInstalacao = '<a id="dois" type="button" class="btn btn-success btn-xs '.$second_btn[0].'" data-toggle="modal" data-id="'.$dados->idlogistica.'" data-serial="'.$dados->serial.'" data-lastdate="'.date('Y-m-d', strtotime($dados->dataRecebimento)).'" data-oldserial="'.$dados->equipAntigo.'" data-tecparacli="'.$tecParaCli.'" data-target="#modal_ordem_2'.$dados->tipoOS.'" title="Assinalar instalação" aria-disabled="'.$second_btn[1].'" ><i class="fa fa-download" aria-hidden="true"></i></a>';	
			$botaoVerOrdem = '<a type="button" class="btn btn-info btn-xs" href="gerencia_equipamentos/verEquip/?origem='.$dados->idlogistica.'" target="_blank" title="Detalhar ordem"><i class="fa fa-eye" aria-hidden="true"></i></a>';
			if ($dados->statusOS == '5' || $dados->statusOS == '9')
                $botaoDevolucao = '<a type="button" class="btn btn-warning btn-xs" title="Estornar Equipamento" disabled="disabled"><i class="fa fa-recycle"></i></a>';
            else
                $botaoDevolucao = '<a id="buttonRet'.$dados->idlogistica.'" type="button" class="btn btn-warning btn-xs" title="Estornar Equipamento" style="cursor: pointer !important;" onclick="devolve('.$dados->idlogistica.')"><i class="fa fa-recycle"></i></a>';

			$result[$i] = array('id' => $dados->idlogistica,
								'modelo' => $dados->modelo,
								'serial' => $dados->serial,
								'linha' => 'test',//$inforChip,
								'operadora' => $inforOperadora,
								'placa' => ($dados->placaOrdem != null ? $dados->placaOrdem : 'Não informada'),
								'estado' => '<span id="status'.$dados->idlogistica.'">'.$estado.'</span>',
								'solicitante' => $dados->solicitante,
								'os' => $tipoOS,
								'data' => date('d/m/Y' , strtotime($dados->dataEnvio)),
								'destino' => $pessoa,
								'tipoDeEnvio' => ($dados->tipoEnvio==1 ? 'Correio' : ($dados->tipoEnvio==2 ? 'Tam Cargo' : 'Outro')), #correio, tamCargo, outro..
								'rastreio' => '<a href="gerencia_equipamentos/rastreio_correios/'.$dados->inforTipo.'/" target="_blank" role="button" data-toggle="modal" data-code="'.$dados->inforTipo.'">'.$dados->inforTipo.'</a>',
								'gerenciar' => $botaoDataChegada.' | '.$botaoConfirmarInstalacao.' | '.$botaoVerOrdem.' | '.$botaoDevolucao
							);
			$i++;
		}
			return $result;
		} else {
			return NULL;
		}
	}

	function devolver_equip($id_log){
	    $estorno = $this->logistica->estornar($id_log);

	    if ($estorno) {
	        if ($row = $this->logistica->getOrdem('idlogistica', $id_log))
	            $this->equipamento->atualizaStatus_equip($row[0]->equipamento, '1');
        }
    }

    #tratamentos dos dados do formulário para insersão no banco de dados.
	private function tratarForm() {
		#ver se o campo serial está setado no fomulário
		if(isset($_POST['serial'])) {			
			#verifica se existe o serial informado no banco de dados
			if ($id_equipamento = $this->equipamento->get_equipamento ('serial', $this->input->post('serial'))) {
				if ($this->input->post('destino') == 1) {
					#escolheu técnico
					$nova = explode(" - ", $this->input->post('instalador'));
					$tec = $nova[0];
					$cli = NULL;
					$referencial = $this->input->post('referencialT');
					$status = 6;
				}else{
					#escolheu cliente
					$nova = explode(" - ", $this->input->post('cliente'));
					$cli = $nova[0];
					$tec = NULL;
					$referencial = $this->input->post('referencialC');
					$status = 4;
				}
				if ($this->input->post('tipo') == 1) {
					#tipo de os é manutenção
					$serialAntigo = $this->input->post('serialAntigo');
				}else{
					#tipo de os é instalação
					$serialAntigo = NULL;
				}
				if ($this->input->post('tipoEnvio')==1) {
					#se o usuário escolher correio, define-se o modo PAC ou sedex
					$modo = $this->input->post('encomendaC');
				}else if ($this->input->post('tipoEnvio')==2) {
					#se o usuário escolher tam cargo, define-se o modo pré-pago ou próximo voo.
					$modo = $this->input->post('encomendaT');
				}else{
					#não define nada e modo e preenchido como nulo.
					$modo = NULL;
				}
				$ordem = array ('equipamento' => $id_equipamento[0]->id,#identificador do equipamento
								'solicitante' => $this->input->post('solicitante'), #pessoa do suporte
								'placaOrdem' =>  $this->input->post('placa'), #veículo que vai receber o equipamento.
								'tipoOS' => $this->input->post('tipo'), #manutenção ou instalação
								'tecnico' => $tec, #técnico de destino ou
								'cliente' => $cli, #pessoa de destino
								'infors' => $referencial, #informações extras de quem recebe
								'equipAntigo' => $serialAntigo,#equipamento antigo que volta da manutenção
								'tipoEnvio' => $this->input->post('tipoEnvio'), #correio, tamcargo, outro
								'inforTipo' => $this->input->post('infoTipo'), #código de rastrio
								'modoEnvio' => $modo,
								'dataEnvio' => $this->input->post('dataEnvio') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('dataEnvio')))) : date('Y-m-d') , #data de envio
								'statusOS' => $status
								);
				return $ordem;
			}else{
				$this->mensagem = "O serial informado não pertence a algum equipamento dentro de nossos registros de dados.";
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	#carrega a página de de listagem de ordens cadastradas.
	public function index() {
		$this->auth->is_logged('admin');
		$dados['titulo'] = 'Controle de logística';
		// $dados['ordens'] = $this->tratarDados();
		$dados['obj'] = array('name' => $this->session->userdata('log_admin')['nome'],
							  'email' => $this->session->userdata('log_admin')['email']);
		$dados['msg'] = $this->session->userdata('content');
		$dados['msgtipo'] = $this->session->userdata('type');
		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

		$this->mapa_calor->registrar_acessos_url(site_url('/gerencia_equipamentos'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('gerencia_equip/lista_logistica');
		$this->load->view('fix/footer_NS');
	}

	public function ajaxGetPagination(){
		$draw = 1; # Draw Datatable
		$start = 0; # Contador (Start request)
		$limit = 10; # Limite de registros na consulta
		$where = NULL; # Campo pesquisa
		$search = $this->input->get('search');
		$limit = $this->input->get('length'); # Limite (Atual)
		
		
		//echo $this->logistica->eqpPaginated($start, $limit, $search, $draw);
		echo $this->logistica->eqpPaginated();
	}

	
	public function ajaxGetPaginationFind(){
		$search = $this->input->get('search');
		
		echo $this->logistica->eqpPaginated(0, 50, $search, 1);
	}
	
	
	#método de cadastro de ordem através do formulário.
	public function cadOrdem() {
		$dados['titulo'] = 'Ordem de logística';
		// $dados['obj'] = array('name' => $this->session->userdata('log_admin')['nome'],
		// 					  'email' => $this->session->userdata('log_admin')['email']);
		// if($dados['ordem'] = $this->tratarForm()) {
		// 	if ($ordem = $this->logistica->inserirOrdem($dados['ordem'])) {
		// 		$control = array('agente' => $this->session->userdata('log_admin')['user'],
		// 					 'paciente' => $ordem,
		// 					 'acao' => "Cadastro de nova ordem"
		// 					 );
		// 		$this->log_logistica->inserirLog($control);
		// 		# Atualiza equipamento para status "Enviado ao cliente" (showtecsystem.cad_equipamentos)
	 	// 		  $this->equipamento->atualizaStatus_equip($ordem['ordem']->equipamento, 2);
	 	// 		# Atualiza equipamento retirado para status "Em estoque" (showtecsystem.cad_equipamentos)
	 	// 		 if ($ordem['ordem']->equipAntigo) {
	 	// 		 $this->equipamento->atualizaStatus_equip($ordem['ordem']->equipAntigo, 1);
	 	// 		 }
		// 		$dados['msg'] ="Dados seguros com sucesso.";
		// 	}else{
		// 		$dados['msg'] = "Ocorreu uma falha na comunicação com o Banco de Dados.";
		// 	}
		// }else{
		// 	$dados['msg'] = $this->mensagem;
		// }
		$this->load->view('fix/header4', $dados);
		$this->load->view('gerencia_equip/cadastro');
		$this->load->view('fix/footer4');
	}

	public function cadOrdem_ajax(){		
		if($dados['ordem'] = $this->tratarForm()) {
			if ($ordem = $this->logistica->inserirOrdem($dados['ordem'])) {
				$control = array(
								'agente' => $this->session->userdata('log_admin')['user'],
								'paciente' => $ordem,
								'acao' => "Cadastro de nova ordem"
							);
				$this->log_logistica->inserirLog($control);
				# Atualiza equipamento para status "Enviado ao cliente" (showtecsystem.cad_equipamentos)
				
	 			  $this->equipamento->atualizaStatus_equip($ordem['ordem']['equipamento'], 2);
	 			# Atualiza equipamento retirado para status "Em estoque" (showtecsystem.cad_equipamentos)
	 			 if ($ordem['ordem']['equipAntigo']) {
	 			 	$this->equipamento->atualizaStatus_equip($ordem['ordem']['equipAntigo'], 1);
	 			 }
				echo json_encode(array('status'=> 'true', 'msg'=> 'OS cadastrada com sucesso!'));
			}else{
				echo json_encode(array('status'=> 'false', 'msg'=> 'Erro ao cadastrar, confira os dados!'));
			}
			
		}else{
			echo json_encode(array('status'=> 'false', 'msg'=> $this->mensagem));
		}
	}
	
	public function relatorio() {
		$dados['titulo'] = 'Relatório Gerais';
		$dados['obj'] = array('name' => $this->session->userdata('log_admin')['nome'],
							  'email' => $this->session->userdata('log_admin')['email']);
		$this->load->view('fix/header3', $dados);
		$total[] = $this->logistica->contaOrdem('statusOS = 4');
		$total[] = $this->logistica->contaOrdem('statusOS = 6');
		$total[] = $this->logistica->contaOrdem('statusOS = 7');
		$total[] = $this->logistica->contaOrdem('statusOS = 8');
		$total[] = $this->logistica->contaOrdem('statusOS = 5');
		$total[] = $this->logistica->contaOrdem('statusOS = 5 and tipoOs = 1 and dataEnvioRetorno is not null and dataRecebidoRetorno is null');
		$total[] = $this->logistica->contaOrdem('statusOS = 5 and tipoOs = 1 and dataEnvioRetorno is not null and dataRecebidoRetorno is not null');
		$total[] = $this->logistica->contaOrdem();
		$dados = array('envioCli' => $total[0] >= 0 ? $total[0] : '???',
						'envioTec' => $total[1] >= 0 ? $total[1] : '???',
						'possesCli' => $total[2] >= 0 ? $total[2] : '???',
						'possesTec' => $total[3] >= 0 ? $total[3] : '???',
						'instalacoes' => $total[4] >= 0 ? $total[4] : '???',
						'retornos' => $total[5] >= 0 ? $total[5] : '???',
						'manutencoes' => $total[6] >= 0 ? $total[6] : '???',
						'totalOrdens' => $total[7] >= 0 ? $total[7] : '???'
						);
		$this->load->view('gerencia_equip/relatorio', $dados);
		$this->load->view('fix/footer3');
	}
	
	public function chegada() {
		if (($this->input->post('upload_1')=="um") || ($this->input->post('topSecret_1')=="um")) {
			redirect('gerencia_equipamentos', 'refresh');
		}else{
			if ($this->input->post('dataChegada_1')=="") {
				$msg = array('content' => 'Um erro foi detectado ao preencher os campos.',
							'type' => 'danger'
							);
				$this->session->set_userdata($msg);
			}else{
				$ordem = array('dataRecebimento' => $this->input->post('dataChegada_1') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('dataChegada_1')))) : date('Y-m-d'),
								'statusOS' => $this->input->post('upload_1')
								);
				if($this->input->post('cascatear1')) {
					if($ordemForm = $this->logistica->getOrdem('idlogistica', $this->input->post('topSecret_1'))) {
						$iguais = $this->logistica->getSame($ordemForm[0]);
						foreach ($iguais as $igual) {
							if(!$this->atualizar($igual->idlogistica, $ordem, "Confirmação de chegada do equipamento")){
								redirect('gerencia_equipamentos');
							}
							# Atualiza Status do equipamento para Disponivel no cliente
							$id_equipamento = $this->logistica->buscaIdEqp_byIdLog($igual->idlogistica);
							$this->equipamento->atualizaStatus_equip($id_equipamento, 3);
						}
					}else{
						$msg = array('content' => 'Ocorreu um erro fatal na comunicação do sistema!',
						'type' => 'danger'
						);
						$this->session->set_userdata($msg);
					}
				}else{
					$this->atualizar($this->input->post('topSecret_1'), $ordem, "Confirmação de chegada do equipamento");

					# ATUALIZA STATUS DO EQUIPAMENTO PARA DISPONIVEL NO CLIENTE
					$id_equipamento = $this->logistica->buscaIdEqp_byIdLog($this->input->post('topSecret_1'));
					$this->equipamento->atualizaStatus_equip($id_equipamento, 3);
				}
			}
			redirect('gerencia_equipamentos');
		}
	}
	#Método unificado com o chegada_1 que agora é apenas o método chegada.
	
	// public function chegada_2() {
	// 	if (($this->input->post('upload_2')=="dois") || ($this->input->post('topSecret_2')=="dois")) {
	// 		redirect('gerencia_equipamentos', 'refresh');
	// 	}else{
	// 		$ordem = array('dataRecebimento' => $this->input->post('dataChegada_2') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('dataChegada_2')))) : date('Y-m-d'),
	// 						'statusOS' => $this->input->post('upload_2')
	// 						);
	// 		$this->atualizar($this->input->post('topSecret_2'), $ordem);
	// 	}
	// }

	public function instalacao_1() {
		#OS do tipo: Manutenção
		if (($this->input->post('topSecret_3')=="tres") || ($this->input->post('dataInstal_1')=="")) {
			redirect('gerencia_equipamentos', 'refresh');
		}else{
			if (($this->input->post('dataInstal_1')=="") || ($this->input->post('serial_old_1')=="") || ($this->input->post('tipoEnvio_1')==0) || ($this->input->post('rastreio_1')=="")) {
				$msg = array('content' => 'Um erro foi detectado ao preencher os campos.',
							'type' => 'danger'
							);
				$this->session->set_userdata($msg);
			}else{
				$ordem = array('statusOS' => 5,
							'equipAntigo' => $this->input->post('serial_old_1'),
							'tipoRetorno' => $this->input->post('tipoEnvio_1'),
							'inforRetorno' => $this->input->post('rastreio_1'),
							'modoRetorno' => ($this->input->post('tipoEnvio_1')==1 ? $this->input->post('encomendaC') : ($this->input->post('tipoEnvio_1')==2 ? $this->input->post('encomendaT') : null)),
							'dataInstalacao' => $this->input->post('dataInstal_1') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('dataInstal_1')))) : date('Y-m-d')
							   );
				if ($this->input->post('cliente_1') != null) {
					$aux = explode(" - ", $this->input->post('cliente_1'));
					$ordem['cliente'] = $aux[0];
				}
				$this->atualizar($this->input->post('topSecret_3'), $ordem, "Confirmação de instalação por manutenção");
			}
			redirect('gerencia_equipamentos');
		}
	}

	public function instalacao_2() {
		#OS do tipo: Instalação
		if (($this->input->post('topSecret_4')=="quatro") || ($this->input->post('dataInstal_2')=="")) {
			redirect('gerencia_equipamentos', 'refresh');
		}else{
			$ordem = array('statusOS' => 5,
						   'dataInstalacao' => $this->input->post('dataInstal_2') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('dataInstal_2')))) : date('Y-m-d')
							);
			if ($this->input->post('cliente_1') != null) {
				$aux = explode(" - ", $this->input->post('cliente_1'));
				$ordem['cliente'] = $aux[0];
			}
			$this->atualizar($this->input->post('topSecret_4'), $ordem, "Confirmação de instalação");
			redirect('gerencia_equipamentos');
		}
	}

	public function devolucao_1() {
		$this->auth->is_logged('admin');
		if (($this->input->post('dataRetorno_1') != "") && ($this->session->userdata('ordem') != -1)){
			$ordem = array('dataEnvioRetorno' => $this->input->post('dataRetorno_1') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('dataRetorno_1')))) : date('Y-m-d')
						);
			if ($this->logistica->atualizarOrdem($this->session->userdata('ordem'), $ordem, "Confirmação de envio do retorno")) {
				$msg = array('msgOrdem' => 'Dados gravados com sucesso!',
							'type' => 'success'
							);
			}else{
				$msg = array('msgOrdem' => 'Um erro no Banco de dados ocorreu ao atualizar a ordem!',
							'type' => 'danger'
							);
			}
			$this->session->set_userdata($msg);
			redirect('gerencia_equipamentos/verEquip/?origem='.$this->session->userdata('ordem'));
		}else{
			$msg = array('msg' => 'Um erro ocorreu ao atualizar a ordem!',
						'type' => 'danger'
						);
			$this->session->set_userdata($msg);
			redirect('gerencia_equipamentos');
		}	
	}

	public function devolucao_2() {
		$this->auth->is_logged('admin');
		if (($this->input->post('dataRetorno_2') != "") && ($this->session->userdata('ordem') != -1)){
			$ordem = array('dataRecebidoRetorno' => $this->input->post('dataRetorno_2') ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('dataRetorno_2')))) : date('Y-m-d')
						);
			if ($this->logistica->atualizarOrdem($this->session->userdata('ordem'), $ordem, "Confirmação de recebimento do retorno")) {
				$msg = array('msgOrdem' => 'Dados gravados com sucesso!',
							'type' => 'success'
							);
			}else{
				$msg = array('msgOrdem' => 'Um erro no Banco de dados ocorreu ao atualizar a ordem!',
							'type' => 'danger'
							);
			}
			$this->session->set_userdata($msg);
			redirect('gerencia_equipamentos/verEquip/?origem='.$this->session->userdata('ordem'));
		}else{
			$msg = array('msg' => 'Um erro ocorreu ao atualizar a ordem!',
						'type' => 'danger'
						);
			$this->session->set_userdata($msg);
			redirect('gerencia_equipamentos');
		}
	}

	private function atualizar($id, $ordem, $log = null) {
		$this->auth->is_logged('admin');
		if (($log != null) && ($this->logistica->atualizarOrdem($id, $ordem))) {
			$control = array('agente' => $this->session->userdata('log_admin')['user'],
							 'paciente' => $id,
							 'acao' => $log
							 );
			$this->log_logistica->inserirLog($control);
			$msg = array('content' => 'Dados gravados com sucesso!',
						'type' => 'success'
						);
			$this->session->set_userdata($msg);
			return true;
		}else{
			$msg = array('content' => 'Um erro ocorreu ao atualizar a ordem!',
						'type' => 'danger'
						);
			$this->session->set_userdata($msg);
			return false;
		}
	}

	private function comparaDatas ($time1, $time2) {
		if(strtotime($time1) > strtotime($time2)) {
			return 1;
		}elseif(strtotime($time1) == strtotime($time2)) {
			return 0;
		}else{
			return -1;
		}
	}

	public function verEquip() {
		$dados['titulo'] = 'Detalhe de Ordem';
		$dados['obj'] = array('name' => $this->session->userdata('log_admin')['nome'],
							  'email' => $this->session->userdata('log_admin')['email']);
		$this->load->view('fix/header3', $dados);
		if ($this->session->userdata('ordem') == $this->input->get('origem')) {
			$msg = $this->session->userdata('msgOrdem');
			$msgtipo = $this->session->userdata('type');
		}
		#Coisa aquela coisa do coiso. Vá embora você é melhor que isso aqui.
		if($ordem = $this->logistica->getOrdem('idlogistica', $this->input->get('origem'))) {
			$this->session->set_userdata('ordem', $ordem[0]->idlogistica);
			if ($ordem[0]->cliente) {
				if ($cli = $this->cliente->get_clientes($ordem[0]->cliente)){
					$pessoa = 'Cliente: </label> '.$cli[0]->nome;
				}else{
					$pessoa = 'Código do cliente: </label> '.$ordem[0]->cliente;
				}
			}else{
				if ($tec = $this->instalador->get_instalador($ordem[0]->tecnico)){
					$pessoa = 'Técnico: </label> '.$tec[0]->nome;
				}else{
					$pessoa = 'Código do Técnico: </label>'.$ordem[0]->tecnico;
				}
			}
			$infor = 'Código de rastreio: ';
			if($ordem[0]->tipoEnvio==1){
				$tipoEnvio = 'Correio';
				$modoEnvio =  ($ordem[0]->modoEnvio == 1 ? ' - P.A.C.' : ' - Sedex');
			}elseif($ordem[0]->tipoEnvio==2){
				$tipoEnvio = 'Tam Cargo';
				$modoEnvio = ($ordem[0]->modoEnvio == 1 ? ' - Pré-pago' : ' - Próximo voo');
			}else{
				$infor = 'Complemento: ';
				$tipoEnvio = 'Outro';
				$modoEnvio = "";
			}
			#controla a imagem que aparece na tela de detalhes
			if(($ordem[0]->modelo == 'ST350')||($ordem[0]->modelo == 'st350')) {
				$imagem = 'st350';
			}elseif (($ordem[0]->modelo == 'LMU800')||($ordem[0]->modelo == 'lmu800')) {
				$imagem = 'lmu800';
			}elseif (($ordem[0]->modelo == 'RASTREAR LIGHT QB')||($ordem[0]->modelo == 'LIGHT QB')) {
				$imagem = 'conti';
			}else{
				$imagem = 'default';
			}
			#pega os dados do chip
			if($chip = $this->chip->get_chips('id_equipamento', $ordem[0]->id)){
				$inforChip = $this->formataNum($chip[0]->numero);
				$infoCCID = $chip[0]->ccid;
				$inforOperadora = $chip[0]->operadora;
				$obs = '';
			}else{
				$infoCCID = "Sem número.";
				$inforChip = "Sem número.";
				$inforOperadora = "Sem número.";
				$obs = '<a class="text-danger" href="https://gestor.showtecnologia.com/sistema/newapp/index.php/equipamentos/listar?palavra='.$ordem[0]->serial.'&coluna=serial" target="_blank"><b>Problemas com a falta de linha? Clique e vincule uma linha. <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></b></a>';
				$data2 = date('d/m/Y' , strtotime($ordem[0]->data_cadastro));
			}
			$esconde3 = '';
			$esconde4 = '';
			$esconde5 = '';
			$esconde6 = 'hidden';
			$data5 = '';
			$datasRetorno = '';
			$cor = '#e4c128'; #amarelo
			switch ($ordem[0]->statusOS) {
				case '0':
					$estado='Bloqueado';
					break;
				case '1':
					$estado='Configurado';
					break;
				case '2':
					$estado='Em teste';
					break;
				case '3':
					$estado='Manutenção'; #chegou do cliente ou técnico, fica em estado de manutenção
					break;
				case '4':
					$estado='Enviado ao cliente';
					$esconde3 = 'hidden';
					$esconde4 = 'hidden';
					$esconde5 = 'hidden';
					$cor = '#e87e7b'; #vermelho
					break;
				case '5':
					$estado='Em uso (instalado)';
					if ($ordem[0]->tipoOS==2){
						$esconde5 = 'hidden';
						$cor = '#65ce65'; #verde
					}else{
						if ($ordem[0]->dataEnvioRetorno == '') {
							$data5 = '<a id="data5" href="#">Indicar data</a>'; #recebe o botão
						}else{
							if ($ordem[0]->dataRecebidoRetorno == '') {
								$data5 = date('d/m/Y' , strtotime($ordem[0]->dataEnvioRetorno));
								$datasRetorno = '<p>No dia <b>'.$data5.'</b> o equipamento foi enviado de volta para SHOW TECNOLOGIA. <label><a id="data6" href="#"> Chegou?</a></label>';
							}else{
								$data5 = date('d/m/Y' , strtotime($ordem[0]->dataRecebidoRetorno));
								$datasRetorno = '<label>Data de envio para manutenção: </label> '.date('d/m/Y' , strtotime($ordem[0]->dataEnvioRetorno)).'<br><label>Data de chegada para manutenção: </label> '.$data5.'<br>';
							}
						}
						$esconde6 = '';
					}
					break;
				case '6':
					$estado='Enviado ao técnico';
					$esconde3 = 'hidden';
					$esconde4 = 'hidden';
					$esconde5 = 'hidden';
					$cor = '#e87e7b'; #vermelho
					break;
				case '7':
					$estado='Posse do cliente';
					$esconde4 = 'hidden';
					$esconde5 = 'hidden';
					$cor = '#4fb7d8'; #azul
					break;
				case '8':
					$estado='Posse do técnico';
					$esconde4 = 'hidden';
					$esconde5 = 'hidden';
					$cor = '#4fb7d8'; #azul
					break;
				case '9':
					$estado='Retornando';
					break;
				default:
					$estado='';
					break;
			}
			$infor2 = 'Código de rastreio: ';
			if($ordem[0]->tipoRetorno==1){
				$tipoRetorno = 'Correio';
				$modoRetorno =  ($ordem[0]->modoRetorno == 1 ? ' - P.A.C.' : ' - Sedex');
			}elseif($ordem[0]->tipoRetorno==2){
				$tipoRetorno = 'Tam Cargo';
				$modoRetorno = ($ordem[0]->modoRetorno == 1 ? ' - Pré-pago' : ' - Próximo voo');
			}else{
				$infor2 = 'Complemento: ';
				$tipoRetorno = 'Outro';
				$modoRetorno = "";
			}
			$dados = array('flexaCor' => $cor,
						'observacao' => $obs,
						'modelo' => $ordem[0]->modelo,
						'serial' => $ordem[0]->serial,
						'numero' => $inforChip,
						'operadora' => $inforOperadora,
						'ccid' => $infoCCID,
						'modeloIMG' => $imagem,
						'idOrdem' => $ordem[0]->idlogistica,
						'tipoOs' => ($ordem[0]->tipoOS==1 ? 'Manutenção<br><label>Serial Antigo: </label> '.$ordem[0]->equipAntigo : 'Instalação'),
						'placa' => ($ordem[0]->placaOrdem != null ? $ordem[0]->placaOrdem : 'Não informada'),
						'pessoa' => $pessoa,
						'solicitante' => $ordem[0]->solicitante,
						'tipoEnvio' => $tipoEnvio.$modoEnvio,
						'tipoRetorno' => $tipoRetorno.$modoRetorno,
						'inforTipo' => $infor.'</label> '.$ordem[0]->inforTipo,
						'inforRetorno' => $infor2.'</label> '.$ordem[0]->inforRetorno,
						'estado' => $estado,
						'data1' => date('d/m/Y' , strtotime($ordem[0]->data_cadastro)),
						'data2' => date('d/m/Y' , strtotime($ordem[0]->dataEnvio)),
						'data3' => date('d/m/Y' , strtotime($ordem[0]->dataRecebimento)),
						'data4' => date('d/m/Y' , strtotime($ordem[0]->dataInstalacao)),
						'data5' => $data5,
						'datasRetorno' => $datasRetorno,
						'esconde3' => $esconde3,
						'esconde4' => $esconde4,
						'esconde5' => $esconde5,
						'esconde6' => $esconde6,
						'msgOrdem' => (isset($msg) ? $msg : null),
						'msgtipo' => (isset($msgtipo) ? $msgtipo : null)
						);
			$this->load->view('gerencia_equip/detalhes', $dados);
		}else{
			$dados['msg'] = "A comunicação com o Sistema de Gerenciamento de Banco de Dados (SGBD) não retornou resultados válidos.";
			$this->session->set_userdata('ordem', -1);
			$this->load->view('gerencia_equip/error', $dados);
		}
		$this->load->view('fix/footer3');
	}

	#preenche os campos apenas leitura do formulário via jquery
	public function getTracker() {
		$serial= $this->input->get('serial');
		#controla a busca do sérial passado por parâmetro
		if($equip = $this->equipamento->get_equipamento('serial', $serial)) {
			if ($equipamento = $this->equipamento->get_resposta($serial)) {
				$latitude = isset($equipamento->X) ? $equipamento->X : '';
				$longitude = isset($equipamento->Y) ? $equipamento->Y : '';
				if($longitude != '' && $latitude != '') {
					$ping = pegar_endereco_referencias($latitude, $longitude).(isset($equipamento->DATA) ? ' - Em: '.dh_for_humans($equipamento->DATA) : '');
				}else{
					$ping = 'Endereço não encontrado';
				}
			}else{
				$ping = 'Equipamento sem informação de posição!';
			}
			if($chips = $this->chip->get_chips('id_equipamento', $equip[0]->id)) {
				 #o erro está aqui ele passa o numero 4 mas volta 
				$data_cadastro = $chips[0]->data_cadastro;
				$operadora = $chips[0]->operadora;
				$num_chip = $this->formataNum($chips[0]->numero);
			}else{
				$data_cadastro = "Chip não vinculado";
				$operadora = "Chip não vinculado";
				$num_chip = "Chip não vinculado";
			}
			$data = array('modelo' => $equip[0]->modelo.' - '.$equip[0]->marca,
						'data_recebimento' => $equip[0]->data_cadastro,
						'data_cadastro' => $data_cadastro,
						'num_chip' => $num_chip,
						'operadora' => $operadora,
						'ping' => $ping,
						'placa' => $equip[0]->placa
						);
			echo json_encode($data);
		}else{
			echo json_encode($equip);
		}
	}
	
	private function formataNum($numero){
		$pais="";
		if (strlen($numero)==13){
			$pais="+".substr($numero, 0, 2)." ";
			$numero=substr($numero, 2);
		}
		return $pais.'('.substr($numero, 0, 2).') '.substr($numero, 2, -4).'-'.substr($numero, -4);
	}

	public function getTable() {
		$data = $this->tratarDados();
		echo json_encode($data);
	}

	public function unsetSessionData() {
		$this->session->unset_userdata('content');
		$this->session->unset_userdata('msgOrdem');
	}

	public function getClientes() {
		if($clientes = $this->cliente->get_lista_clientes()) {
			foreach ($clientes as $cliente) {
				$dados[] = $cliente->id.' - '.$cliente->nome;
			}
		}else{
			$dados[] = 'Sem clientes cadastrados.';
		}
		echo json_encode($dados);
	}
	
	public function getInstaladores() {
		if($tecnicos = $this->instalador->get_lista_instaladores()) {
			foreach ($tecnicos as $tecnico) {
				$dados[] = $tecnico->id.' - '.$tecnico->nome;
			}
		}else{
			$dados[] = 'Sem instaladores cadastrados.';
		}
		echo json_encode($dados);
	}

	public function graficoInfor() {
		$dateHead = date_create($this->input->get('inicio'));
		$dateTale = date_create($this->input->get('fim'));
		$diasQuant = date_diff($dateHead, $dateTale)->days+1;
		$resultado = (int)($diasQuant/7);
		$resto=$diasQuant%7;
		$dados['etiquetas'] = [];
		$dados['valoresEnvio'] = [];
		$dados['valoresInstal'] = [];
		$dados['valoresRetorno'] = [];
		# este do...while agrupa a pesquisa em no max 7 sub períodos de tempo enquanto não chegar a data de fim da pesquisa que o usuário pediu.
		do {
			$label = $dateHead->format('d-m-Y');
			$dateSearch = $dateHead->add(new DateInterval("P".$resultado."D")); #Soma a data de início da pesquisa o resultado da divisão entre a quantidade de dias / 7 e atribui a data de fim da pesquisa
			$dateSearch->sub(new DateInterval("P1D")); #diminui a data de fim de pesquisa em 1 dia
			if ($resto > 0) {
				#soma a data de fim de pesquisa 1 dia se o resto da divisão for maior que 0;
				$dateSearch->add(new DateInterval("P1D"));
				$resto -= 1;
			}$pesquisa = implode("-", array_reverse(explode("-", $label)));
			$dados['valoresEnvio'][] = $this->logistica->contaOrdem("dataEnvio is not null and dataEnvio >= '".$pesquisa."' and dataEnvio <= '".$dateSearch->format('Y-m-d')."'");
			$dados['valoresInstal'][] = $this->logistica->contaOrdem("dataInstalacao is not null and dataInstalacao >= '".$pesquisa."' and dataInstalacao <= '".$dateSearch->format('Y-m-d')."'");
			$dados['valoresRetorno'][] = $this->logistica->contaOrdem("tipoOS = 1 and dataRecebidoRetorno is not null and dataRecebidoRetorno >= '".$pesquisa."' and dataRecebidoRetorno <= '".$dateSearch->format('Y-m-d')."'");

			$dados['etiquetas'][] = ($dateSearch->format('d-m-Y') != $label ? substr(str_replace('-', '/', $label), 0, 5)." a " : " ").$dateSearch->format('d/m');
			$dateHead = $dateSearch->add(new DateInterval("P1D"));
		} while ($dateHead<=$dateTale);
		echo json_encode($dados);
	}

	public function getPairs() {
		$idOrdem = $this->input->get('ordem');
		if($ordem = $this->logistica->getOrdem('idlogistica', $idOrdem)){
			$iguais = $this->logistica->getSame($ordem[0]);
		}
		echo json_encode($iguais);
	}

	public function rastreio_correios($codigo) {
		$result = $this->logistica->request_correios($codigo);

		echo $result;
	}

	// DESENVOLVIDO POR SAULO MENDES //
	public function correspondencias($tipo = '') {
		$this->auth->is_logged('admin');
		$dados['titulo'] = 'Controle de Correspondencias';
		$dados['obj'] = array('name' => $this->session->userdata('log_admin')['nome'],
							  'email' => $this->session->userdata('log_admin')['email']);
		$dados['destinatarios'] = $this->logistica->get_destinatarios();
		
		# PAGINAÇÃO - LISTA MOVIMENTOS
		if (isset($_GET['pag'])) {
			$pg_atual = $_GET['pag'];
			$dados['movimentos'] = $this->logistica->lista_mov($pg_atual, 100);
		} elseif (isset($_GET['id']) && isset($_GET['coluna'])) {
			$dados['movimentos'] = $this->logistica->filtrar_corresp_by_id($_GET['coluna'], $_GET['id']);
		} else {
			$dados['movimentos'] = $this->logistica->lista_mov(0, 100, $tipo);
		}

		# PAGINAÇÃO - DEFINE A QUANTIDADE DE PAGINAS
		$count_pag = $this->logistica->get_count_pag();
		$dados['paginacao'] = ceil($count_pag / 100);

		# ACRESCENTA O NOME DO DESTINATARIO NO MOVIMENTO
		foreach ($dados['movimentos'] as $movimento) {
			$name = $this->logistica->get_name_dest($movimento->id_destinatario);
			$movimento->nome = $name;
		}
		
		# CADASTRA DESTINATARIO
		if ($this->input->post()) {
			$tipo = $this->input->post('tipo');
			$remetente = $this->input->post('rem');
			$destinatario = $this->input->post('dest');
			$id_dest = $this->logistica->getId_Dest($destinatario);

			$insert = array('tipo_movimentacao' => $tipo, 'id_destinatario' => $id_dest, 'remetente' => $remetente);
			$save = $this->logistica->insert_mov($insert);
			if ($save) {
				$this->session->set_flashdata('sucesso', 'Movimentação cadastrada com sucesso!');
			} else {
				$this->session->set_flashdata('erro', 'Erro ao gravar informação, por favor tente novamente mais tarde.');
			}
		}

		$this->load->view('fix/header4', $dados);
		$this->load->view('gerencia_equip/correspondencias');
		$this->load->view('fix/footer4');
	}

	public function cad_destinatario() {
		$nome = $this->input->post('nome');
		$resp = $this->input->post('resp');
		$end = $this->input->post('end');
		$cidade = $this->input->post('cidade');
		$estado = $this->input->post('estado');
		$cep = $this->input->post('cep');
		$tel = $this->input->post('tel');
		$email = $this->input->post('email');

		$dados = array('nome' => $nome, 'responsavel' => $resp, 'telefone' => $tel, 'email' => $email, 'endereco' => $end,
			'cidade' => $cidade, 'estado' => $estado, 'cep' => $cep);

		$save = $this->logistica->cadastrar_dest($dados);
		if ($save) {
			$this->session->set_flashdata('sucesso', 'Cadastrado com sucesso!');
			redirect('gerencia_equipamentos/correspondencias');
		} else {
			$this->session->set_flashdata('erro', 'Cadastro não realizado, verifique os campos e tente novamente mais tarde.');
			redirect('gerencia_equipamentos/correspondencias');
		}
	}

	public function entregar_corresp() {
		$id = $this->input->post('id');
		$comment = $this->input->post('comment');
		$insert = array('status' => 2, 'observacao' => $comment." - Data Entrega: ".date('Y-m-d H:i:s'));

		$result = $this->logistica->atualizar_corresp($id, $insert);

		if ($result) {
			$this->session->set_flashdata('sucesso', 'Correspondência entregue com sucesso!');
			redirect('gerencia_equipamentos/correspondencias');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possivel gravar a entrega.');
			redirect('gerencia_equipamentos/correspondencias');
		}
	}

	public function enviar_corresp() {
		$id = $this->input->post('id');
		$cod_rast = $this->input->post('cod_rast');
		$insert = array('data_despacho' => date('Y-m-d H:i:s'), 'status' => 1, 'cod_rastreio' => $cod_rast);

		$result = $this->logistica->atualizar_corresp($id, $insert);

		if ($result) {
			$this->session->set_flashdata('sucesso', 'Correspondência enviada com sucesso!');
			redirect('gerencia_equipamentos/correspondencias');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possivel gravar o envio.');
			redirect('gerencia_equipamentos/correspondencias');
		}
	}

	public function devolver_corresp() {
		$id = $this->input->post('id');
		$comment = $this->input->post('comment');
		$insert = array('status' => 3, 'observacao' => $comment." - Data Recebimento da Devolução: ".date('Y-m-d H:i:s'));

		$result = $this->logistica->atualizar_corresp($id, $insert);

		if ($result) {
			$this->session->set_flashdata('sucesso', 'Correspondência devolvida!');
			redirect('gerencia_equipamentos/correspondencias');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possivel gravar a devolução.');
			redirect('gerencia_equipamentos/correspondencias');
		}
	}
}
