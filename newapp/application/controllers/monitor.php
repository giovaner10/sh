<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Monitor extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->model('auth');
		$this->load->model('veiculo');
		$this->load->model('cliente');
		$this->load->model('usuario');
		$this->load->model('contrato');
		$this->load->model('mapa_calor');
	}

	public function index()
	{
		echo "";
	}

	/*
	* VIEW MONITORAMENTO DE CONTRATOS
	*/
	public function monitor_panico()
	{
		$dados['titulo'] = lang('monitoramento_panico') . ' - ' . lang('show_tecnologia');
		$dados['load'] = array('dataTable', 'select2');
		$this->mapa_calor->registrar_acessos_url(site_url('/monitor/monitor_panico'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('monitor/panico');
		$this->load->view('fix/footer_NS');
	}

	/**
	 * Monitoramento de Apenados - Safety
	 * @author Saulo Mendes
	 * @method GET
	 */
	public function monitorApenados()
	{
		$this->load->view('fix/header', array('titulo' => 'Monitoramento de Apenados'));
		$this->load->view('monitor/monitorado');
		$this->load->view('fix/footer');
	}

	public function geraRelPanico()
	{
		$users = $retorno = array();
		$id_cliente = $this->input->get('id_cliente');
		$nome = $this->input->get('cliente');
		$inicio = $this->input->get('inicio');
		$fim = $this->input->get('final');

		if (!$id_cliente || !$inicio || !$fim) {
			echo json_encode(array('status' => 'ERRO', 'msg' => 'Erro nos parâmetros passados. Verifique e tente novamente!'));
			return;
		}

		$this->load->model('veiculo');
		$eventos = $this->veiculo->relatorioPanico($id_cliente, $inicio, $fim);
		if ($eventos) {
			foreach ($eventos as $e) {
				if (!isset($users[$e->id_cad_regra])) {
					$users[$e->id_cad_regra] = $this->veiculo->getNameUserShownet($e->id_cad_regra);
				}
				$retorno[] = array(
					$e->placa,
					date('d/m/Y H:i:s', strtotime($e->time_gps)),
					$e->serial,
					$e->endereco ? $e->endereco : 'Não localizado',
					$e->velocidade . ' Km/h',
					$nome,
					$users[$e->id_cad_regra],
					date('d/m/Y H:i:s', strtotime($e->dhcad_evento))
				);
			}
		}

		echo json_encode($retorno);
	}

	public function remove_panico()
	{
		$id = $this->input->post('id');
		$serial = $this->input->post('serial');
		if ($id && is_numeric($id)) {
			$this->load->model('veiculo');
			if ($this->veiculo->finishPanico($id, $serial)) {
				echo json_encode(array('status' => 'OK', 'msg' => lang('alerta_removido_sucesso')));
			} else {
				echo json_encode(array('status' => 'ERRO', 'msg' => lang('alerta_nao_removido')));
			}
		} else {
			echo json_encode(array('status' => 'ERRO', 'msg' => lang('dados_fautosos')));
		}
	}

	/**
	 * TRATA UM EVENTO DE PANICO
	 */
	public function tratar_evento()
	{
		if ($dados = $this->input->post()) {
			$this->load->model('veiculo');

			if ($this->veiculo->finishPanico($dados['id'], $dados['serial'], $dados['observacao']))
				exit(json_encode(array('success' => true, 'msg' => lang('alerta_tratado_sucesso'))));
			else
				exit(json_encode(array('success' => false, 'msg' => lang('alerta_tratado_falha'))));
		} else {
			exit(json_encode(array('success' => false, 'msg' => lang('dados_fautosos'))));
		}
	}

	/*
	* VIEW MONITORAMENTO DE CONTRATOS
	*/
	public function monitor_contratos_old($id_cont = false)
	{
		$this->load->model('contrato');

		if ($id_cont) {
			$this->contrato->desativaNotification($id_cont);
		}
		$this->mapa_calor->registrar_acessos_url(site_url('/monitor/monitor_contratos'));
		$this->load->view('fix/header', array('titulo' => 'Monitoramento de Contratos'));
		$this->load->view('monitor/monitor_contratos');
		$this->load->view('fix/footer');
	}

	public function monitor_contratos()
	{
		$this->load->model('contrato');

		$this->auth->is_allowed('monitor_contrato');

		$this->mapa_calor->registrar_acessos_url(site_url('/monitor/monitor_contratos'));

		$dados['load'] = array('ag-grid', 'select2', 'mask');
		$dados['titulo'] = lang('monitoramento_de_contratos');

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('monitor/new_monitor_contratos');
		$this->load->view('fix/footer_NS');
	}

	public function removeContrato($id_cont = false) {
		if ($id_cont) {
			if($this->contrato->desativaNotification($id_cont)) {
				echo json_encode(
					array(
						'success' => true,
						'mensagem' => 'Sucesso!'
					)
				);
			} else {
				echo json_encode(
					array(
						'success' => false,
						'mensagem' => 'Erro ao desativar o contrato!'
					)
				);
			}
		} else {
			echo json_encode(
				array(
					'success' => false,
					'mensagem' => 'Informe o ID para desativar o contrato!'
				)
			);
		}
	}

	/*
    * FUNÇÃO PARA MONTAGEM DA LISTA DE MONITORAMENTO DOS CONTRATOS
    */
	public function ajaxListMonitorContracts()
	{
		$this->load->model('contrato');
		$draw = 1; # Draw Datatable
		$start = 0; # Contador (Start request)
		$limit = 10; # Limite de registros na consulta
		$where = NULL; # Campo pesquisa
		if ($this->input->get()) {
			$draw = $this->input->get('draw');
			$search = $this->input->get('search')['value'];
			$start = $this->input->get('start'); # Contador (Página Atual)
			$limit = $this->input->get('length'); # Limite (Atual)
		}

		echo json_encode($this->contrato->getMonitorContratos($draw, $search, $start, $limit));
	}

	public function listarContratosMonitorados()
	{
		$search = '';
		$startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
		$cliente = $this->input->post('cliente');
		$vendedor = $this->input->post('vendedor');
		$contrato = $this->input->post('contrato');
		$groupKeys = $this->input->post('groupKeys');
		$rowGroupCols = $this->input->post('rowGroupCols');

		if ($groupKeys) {
			$groupBy = false;

			$cliente = $groupKeys[0];
		} else {
			$groupBy = 'clie.nome';
		}

		$limit = $endRow - $startRow;
		$offset = $startRow;

		$result = $this->contrato->getContratosMonitorados($offset, $limit, $cliente, $vendedor, $contrato, $groupBy);

		if ($result['success']) {
			if (count($result['data']) != 0) {
				echo json_encode(array(
					"success" => true,
					"rows" => $result['data'],
					"lastRow" => $result['recordsTotal']
				));
			} else {
				echo json_encode(array(
					"success" => false,
					"message" => $result['message'] ? $result['message'] : 'Erro ao realizar listagem!',
				));
			}
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $result['message'],
            ));
        }
	}

	// equipamento violado na versao antiga:
	// public function equipamento_violado()
	// {
	// 	$this->load->model('cliente');

	// 	$dados['all'] = $this->cliente->listar(array(), 0, 99999, 'id', 'DESC', 'nome');

	// 	$clientes = array();
	// 	if (count($dados['all']) > 0) {
	// 		foreach ($dados['all'] as $cli) {
	// 			$clientes[] = $cli->nome;
	// 		}
	// 	}

	// 	$dados['clientes'] = json_encode($clientes);
	// 	$dados['titulo'] = 'Show Tecnologia';
	// 	$this->mapa_calor->registrar_acessos_url(site_url('/monitor/equipamento_violado'));
	// 	$this->load->view('fix/header', $dados);
	// 	$this->load->view('monitor/violacao_rastreador');
	// 	$this->load->view('fix/footer');
	// }

	public function equipamentoVioladoCampos()
	{

		if (!$this->input->is_ajax_request())
			exit(lang("nenhum_acesso_direto_script_permitido"));


		$this->load->model('cliente');

		$clientes = $this->cliente->listar(array(), 0, 99999, 'id', 'DESC', 'nome');

		$data = [];
		$x = 0;

		foreach ($clientes as $cliente) {
			$data[$x] =
				[
					"nome" => ucwords(strtolower($cliente->nome)), # ex: Nome Sobrenome
				];
			$x++;
		}

		echo json_encode($data);
	}

	public function equipamento_violado()
	{
		$this->load->model('cliente');

		$dados['all'] = $this->cliente->listar(array(), 0, 99999, 'id', 'DESC', 'nome');

		$clientes = array();
		if (count($dados['all']) > 0) {
			foreach ($dados['all'] as $cli) {
				$clientes[] = $cli->nome;
			}
		}

		$dados['clientes'] = json_encode($clientes);
		$dados['titulo'] = 'Show Tecnologia';
		$ag['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$ag['titulo'] = 'Show Tecnologia';
		$this->mapa_calor->registrar_acessos_url(site_url('/monitor/equipamento_violado'));
		$this->load->view('new_views/fix/header', $ag);
		$this->load->view('monitor/violacao_rastreadornv', $dados);
		$this->load->view('fix/footer_NS');
	}

	public function load_violados()
	{

		$this->load->model('veiculo');

		$hoje = date('Y-m-d');

		if ($this->input->post('cliente') != ' ') {
			$find = array(
				'DATA >=' => $hoje . ' 00:00:00', 'DATA <=' => $hoje . ' 23:59:59',
				'resposta.VOLTAGE <=' => 0, 'cad_clientes.nome' => $this->input->post('cliente')
			);
		} else {
			$find = array(
				'DATA >=' => $hoje . ' 00:00:00', 'DATA <=' => $hoje . ' 23:59:59',
				'resposta.VOLTAGE <=' => 0
			);
		}
		$select = 'cad_clientes.email, cad_clientes.fone, cad_clientes.nome, systems.cadastro_veiculo.placa, resposta.DATA, resposta.X, resposta.Y,
					resposta.ID,resposta.VOLTAGE, cadastro_veiculo.prefixo_veiculo';

		$veiculos = $this->veiculo->find_with_last_track($find, 9999999, 0, $select);

		foreach ($veiculos as $v) {
			$v->DATA = dh_for_humans($v->DATA);
			$v->email = isset($v->email) ? substr($v->email, 0, 30) : 'Email não cadastrado';
			$v->fone = isset($v->fone) ? $v->fone : 'Telefone não cadastrado';
		}

		echo json_encode($veiculos);
	}

	public function load_panico()
	{
		$this->load->model('veiculo');
		$veiculos = $this->veiculo->load_panico();
		echo json_encode($veiculos);
	}

	public function lista_cliente()
	{
		$this->load->model('cliente');
		$query = $this->input->get('q');
		$clientes = $this->cliente->lista_clientes($query);

		$retorno['results'] = array();
		if (count($clientes) > 0) {
			foreach ($clientes as $cli) {
				$texto = $cli->cnpj ? $cli->cnpj : $cli->cpf;
				$retorno['results'][] = array(
					'id' => $cli->id,
					'text' => $cli->nome . ' - ' . $texto
				);
			}
		}

		echo json_encode($retorno);

		$dados['clientes'] = json_encode($clientes);
	}

	/**
	 * Busca ultimos registros de monitorados
	 * @author Saulo Mendes
	 * @method GET
	 * @param Int - last_tracker (Ultimo indice recebido)
	 *
	 * @return Array()
	 */
	public function load_monitorados($data = '2020-01-01 00:00:01')
	{
		$this->load->model('veiculo');
		exit(json_encode($this->veiculo->load_monitorados($data)));
	}

	/**
	 * Busca lista de Monitorados ativos
	 * @author Saulo Mendes
	 * @method GET
	 *
	 * @return Array()
	 */
	public function getMonitoradosAtivos()
	{
		$this->load->model('veiculo');
		exit(json_encode($this->veiculo->getMonitorados()));
	}

	/*
	* CARREGA OS EVENTOS DE PANICOS
	*/

	public function ajaxLoadEventosPanico()
	{
		$this->load->model('veiculo');

		$dados = $this->input->post();
		$order = $dados['order'][0] ? $dados['order'][0] : false;
		$draw = $dados['draw'] ? $dados['draw'] : 1;
		$start = $dados['start'] ? $dados['start'] : 0;
		$limit = $dados['length'] ? $dados['length'] : 10;
		$filtro = $dados['filtroPanico'] ? $dados['filtroPanico'] : false;
		$search = $dados['searchPanico'] ? $dados['searchPanico'] : false;

		$ids_clientes = $ids_responsaveis = array();
		$nomes_clientes = $nomes_responsaveis = array();
		$retorno = array();

		$panicos = $this->veiculo->listEventosPanicoServerSide(
			'id_evento, id_cliente, placa, time_gps, serial, lat, lng, velocidade, endereco, id_cad_regra as id_usuario',
			$order,
			$start,
			$limit,
			$search,
			$filtro,
			$draw
		);

		if ($panicos['eventos']) {
			foreach ($panicos['eventos'] as $panic) {
				$ids_clientes[] = $panic->id_cliente;
				if ($panic->id_usuario) $ids_responsaveis[] = $panic->id_usuario;
			}

			$ids_responsaveis = array_unique($ids_responsaveis);
			$ids_clientes = array_unique($ids_clientes);

			$clientes = $this->cliente->getClientesPorIds('id, nome', $ids_clientes);
			if ($clientes) {
				foreach ($clientes as $c) {
					$nomes_clientes[$c->id] = $c->nome;
				}
				$clientes = $ids_clientes = [];
			}

			if (!empty($ids_responsaveis)) {
				$responsaveis = $this->usuario->listDadosUsersShownet($ids_responsaveis, 'id, nome');
				if ($responsaveis) {
					foreach ($responsaveis as $user) {
						$nomes_responsaveis[$user->id] = $user->nome;
					}
					$responsaveis = $ids_responsaveis = [];
				}
			}

			foreach ($panicos['eventos'] as $p) {
				$disabled = isset($nomes_responsaveis[$p->id_usuario]) && $p->id_usuario != $this->auth->get_login_dados('user') ? 'disabled' : '';

				$retorno[] = array(
					'id' => $p->id_evento,
					'placa' => $p->placa,
					'data' => date('d/m/Y H:i:s', strtotime($p->time_gps)),
					'serial' => $p->serial,
					'latitude' => $p->lat,
					'longitude' => $p->lng,
					'velocidade' => $p->velocidade . ' Km/h',
					'endereco' => $p->endereco ? $p->endereco : lang('nao_localizado'),
					'cliente' => isset($nomes_clientes[$p->id_cliente]) ? $nomes_clientes[$p->id_cliente] : lang('cliente_nao_vinculado'),
					'responsavel' => isset($nomes_responsaveis[$p->id_usuario]) ? $nomes_responsaveis[$p->id_usuario] : null,
					'admin' => '<button data-serial="' . $p->serial . '" data-id="' . $p->id_evento . '" class="btn btn-primary btnTratar" title="' . lang('tratar_alerta') . '" ' . $disabled . '>' .
						'<i class="fa fa-edit"></i>' .
						'</button>'
				);
			}
			echo json_encode(array('draw' => intval($panicos['draw']), 'recordsTotal' =>  intval($panicos['recordsTotal']), 'recordsFiltered' =>  intval($panicos['recordsFiltered']), 'data' => $retorno));
		} else {
			echo json_encode(array('status' => false, 'data' => array(), 'draw' => intval($draw) + 1, 'recordsTotal' => 0, 'recordsFiltered' => 0));
		}
	}

	/*
	* RETORNA OS DADOS DE EVENTOS DE PANICO
	*/
	public function gerarRelatorioPanico()
	{
		$users = $retorno = array();
		$cliente = $this->input->post('cliente_historico');
		$nome = $this->input->post('nome_cliente');
		$inicio = $this->input->post('di');
		$fim = $this->input->post('df');

		if (!$cliente || !$inicio || !$fim) exit(json_encode(array('success' => false, 'msg' => lang('dados_fautosos'))));

		$id_cliente = explode(' - ', $cliente)[0];
		$nome_cliente = explode(' - ', $cliente)[1];

		$eventos = $this->veiculo->relatorioPanico($id_cliente, $inicio, $fim, 't.id_evento, t.id_cliente, t.placa, t.time_gps, t.serial, t.lat, t.lng, t.velocidade, t.endereco, t.id_cad_regra, t.dhcad_evento, tt.observacao');
		if ($eventos) {
			$users = array();
			foreach ($eventos as $e) {
				$users[] = $e->id_cad_regra;
			}
			$loginsUsers = $this->usuario->listDadosUsersShownet($users, 'login, id');

			$logins = array();
			$users = array();

			if ($loginsUsers) {
				foreach ($loginsUsers as $user) {
					$logins[$user->id] = $user->login;
				}
				$loginsUsers = array();
			}

			foreach ($eventos as $e) {
				$retorno[] = array(
					'id' => $e->id_evento,
					'placa' => $e->placa,
					'data' => date('d/m/Y H:i:s', strtotime($e->time_gps)),
					'serial' => $e->serial,
					'endereco' => $e->endereco ? $e->endereco : 'Não localizado',
					'velocidade' => $e->velocidade . ' Km/h',
					'cliente' => $nome_cliente,
					'removido_por' => isset($logins[$e->id_cad_regra]) ? $logins[$e->id_cad_regra] : '',
					'tratado_em' => date('d/m/Y H:i:s', strtotime($e->dhcad_evento)),
					'observacao' => $e->observacao,
				);
			}
		}
		exit(json_encode(array('success' => true, 'table' => $retorno)));
	}

	/*
	* RETORNA OS DADOS DE UM EVENTO
	*/
	public function get_evento($id = false)
	{
		if (!$id) exit(json_encode(array('success' => false, 'msg' => lang('dados_fautosos'))));

		$tem_responsavel = false;
		$usuario_atual = $this->auth->get_login_dados('user');
		$id_usuario = $usuario_atual;

		$evento = $this->veiculo->getEventoTracker(array('id_evento' => $id), 'id_evento, id_cad_regra as id_usuario');
		if (!empty($evento)) {
			if ($evento->id_usuario && $evento->id_usuario != $usuario_atual) {
				$id_usuario = $evento->id_usuario;
				$tem_responsavel = true;
			} else {
				$this->veiculo->update_evento_tracker($id, array('id_cad_regra' => $usuario_atual));
			}

			$nome_responsavel = $this->usuario->getUser($id_usuario, 'nome')[0]->nome;

			$retorno = array(
				'tem_responsavel' => $tem_responsavel,
				'nome_responsavel' => $nome_responsavel
			);
			exit(json_encode(array('success' => true, 'retorno' => $retorno)));
		} else {
			exit(json_encode(array('success' => false, 'msg' => lang('evento_nao_encontrado'))));
		}
	}
}
