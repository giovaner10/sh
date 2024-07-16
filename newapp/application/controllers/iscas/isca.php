<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Isca extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->auth->is_logged('admin');
    $this->load->model('auth');
    $this->load->model('iscas/iscas');
    $this->load->model('cliente');
    $this->load->model('instalador');
    $this->load->model('contrato');
    $this->load->model('usuario_gestor');
    $this->load->model('iscas/comandos_iscas', 'comandos');
    $this->load->model('mapa_calor');
  }

  public function dashboardIscas()
  {

    $dados['titulo'] = "Dashboard Iscas";
    $this->mapa_calor->registrar_acessos_url(site_url('/iscas/isca/dashboardIscas'));
    $this->load->view('fix/header-new', $dados);
    $this->load->view('iscas/dashboard');
    $this->load->view('fix/footer_new');
  }

  public function ajaxDadosDashboard()
  {


    $resposta = json_decode(from_relatorios_api([], "dashboardIscas"), true);
    // var_dump($resposta);die();
    if ($resposta['status'] == true) {
      $totalInstalador = $this->instalador->get_total_instaladores();
      $resposta['dados']['instaladores'] = $totalInstalador;
    }
    echo json_encode($resposta);
  }
  // public function iscaAgendamentos()
  // {
  //   $dados['titulo'] = "Agendamentos - Ordens de Serviço";
  //   $this->mapa_calor->registrar_acessos_url(site_url('/iscas/isca/iscaAgendamentos'));
  //   $this->load->view('fix/header-new', $dados);
  //   $this->load->view('iscas/agendamentos');
  //   $this->load->view('fix/footer_new');
  // }

  public function iscaAgendamentos()
  {
    $dados['titulo'] = "Agendamentos - Ordens de Serviço";
    $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

    $this->mapa_calor->registrar_acessos_url(site_url('/iscas/isca/iscaAgendamentos'));
		$this->load->view('new_views/fix/header', $dados);
    $this->load->view('iscas/agendamentosView');
    $this->load->view('fix/footer_NS');
  }
  public function index()
  {

    $dados['titulo'] = "Iscas em Estoque";
    $this->mapa_calor->registrar_acessos_url(site_url('/iscas/isca'));
    $this->load->view('new_views/fix/header', $dados);
    $this->load->view('iscas/listar_iscas_estoque');
    $this->load->view('fix/footer_NS');
  }
  public function listarIscasVinculadasOld()
  {
    $dados['titulo'] = "Iscas Vinculadas";
    $this->mapa_calor->registrar_acessos_url(site_url('/iscas/isca/listarIscasVinculadas'));
    $this->load->view('fix/header-new', $dados);
    $this->load->view('iscas/listar_iscas_vinculadas');
    $this->load->view('fix/footer_new');
  }

  public function listarIscasVinculadas()
  {
    $this->mapa_calor->registrar_acessos_url(site_url('/iscas/isca/listarIscasVinculadas'));
    $dados['titulo'] = "Iscas Vinculadas";
    $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

    $this->load->view('new_views/fix/header', $dados);    
    $this->load->view('iscas/listar_iscas_vinculadas_new');
    $this->load->view('fix/footer_NS');
  }

  public function relatorioIscas()
  {
    $dados['titulo'] = "Relatório de Iscas";
    $this->mapa_calor->registrar_acessos_url(site_url('/iscas/isca/relatorioIscas'));
    $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
    $this->load->view('new_views/fix/header', $dados);
    $this->load->view('relatorios/iscas/relatorioIscas');
    $this->load->view('fix/footer_NS');
  }

  public function getIscasSemVinculo()
  {

    $data = $this->iscas->get_iscas_sem_vinculo(false);

    echo json_encode(array('data' => $data['dados']));
  }

  public function getIscasVinculadas()
  {
    $dados = $this->input->post();

    $draw = $dados['draw'] ? $dados['draw'] : 1;
    $start = $dados['start'] ? $dados['start'] : 0;
    $limit = $dados['length'] ? $dados['length'] : 10;

    $order = false;
    if (isset($dados['order'][0]['column'])) {
      $campos = array(
        1 => 'cad_iscas.id',
        2 => 'cad_iscas.serial',
        3 => 'cad_iscas.descricao',
        4 => 'cad_iscas.modelo',
        5 => 'cad_iscas.marca',
        6 => 'cad_iscas.data_cadastro',
        7 => 'cad_iscas.data_expiracao',
        8 => 'cad_iscas.status',
        9 => 'cad_clientes.nome',
        10 => 'cad_iscas.id_contrato',
      );

      $order = $campos[$dados['order'][0]['column']];
    }
    $orderDir = isset($dados['order'][0]['dir']) ? $dados['order'][0]['dir'] : false;

    $filtro = isset($dados['filtro']) ? $dados['filtro'] : false;
    $search = isset($dados['search']) ? $dados['search'] : false;

    // if ($filtro == 'cad_iscas.status') {
    //   $data = $this->iscas->get_iscas_vinculadas(false, $start, $limit, $filtro, $search, $order, $orderDir);
    // } else {
    $data = $this->iscas->get_iscas_vinculadas(false, $start, $limit, $filtro, $search, $order, $orderDir);
    // }
    echo json_encode(array('draw' => intval($draw), 'recordsTotal' => intval($data['qtd_total']), 'recordsFiltered' => intval($data['qtd_filtrado']), 'data' => $data['dados']));
  }

  public function getIscasVinculadasNew()
  {
    $dados = $this->input->post();

    $start = $dados['start'] ? $dados['start'] : 0;
    $limit = $dados['length'] ? $dados['length'] : 10;

    $order = false;
    if (isset($dados['order'][0]['column'])) {
      $campos = array(
        1 => 'cad_iscas.id',
        2 => 'cad_iscas.serial',
        3 => 'cad_iscas.descricao',
        4 => 'cad_iscas.modelo',
        5 => 'cad_iscas.marca',
        6 => 'cad_iscas.data_cadastro',
        7 => 'cad_iscas.data_expiracao',
        8 => 'cad_iscas.status',
        9 => 'cad_clientes.nome',
        10 => 'cad_iscas.id_contrato',
      );

      $order = $campos[$dados['order'][0]['column']];
    }
    $orderDir = isset($dados['order'][0]['dir']) ? $dados['order'][0]['dir'] : false;

    $filtro = isset($dados['filtro']) ? $dados['filtro'] : false;
    $search = isset($dados['search']) ? $dados['search'] : false;

    // if ($filtro == 'cad_iscas.status') {
    //   $data = $this->iscas->get_iscas_vinculadas(false, $start, $limit, $filtro, $search, $order, $orderDir);
    // } else {
    $data = $this->iscas->get_iscas_vinculadas(false, $start, $limit, $filtro, $search, $order, $orderDir);
    // }
    echo json_encode(array('recordsTotal' => intval($data['qtd_total']), 'recordsFiltered' => intval($data['qtd_filtrado']), 'data' => $data['dados']));
  }

  public function getIscasVinculadasServerSide()
  {
    $startRow = (int) $this->input->post('startRow', TRUE) ?: 0;
		$endRow = (int) $this->input->post('endRow', TRUE) ?: 10;

    $dados = $this->input->post("searchOptions", TRUE);

    $order = false;
    if (isset($dados['order'][0]['column'])) {
      $campos = array(
        1 => 'cad_iscas.id',
        2 => 'cad_iscas.serial',
        3 => 'cad_iscas.descricao',
        4 => 'cad_iscas.modelo',
        5 => 'cad_iscas.marca',
        6 => 'cad_iscas.data_cadastro',
        7 => 'cad_iscas.data_expiracao',
        8 => 'cad_iscas.status',
        9 => 'cad_clientes.nome',
        10 => 'cad_iscas.id_contrato',
      );

      $order = $campos[$dados['order'][0]['column']];
    }
    $orderDir = isset($dados['order'][0]['dir']) ? $dados['order'][0]['dir'] : false;

    $filtro = isset($dados['filtro']) ? $dados['filtro'] : false;
    $search = isset($dados['search']) ? $dados['search'] : false;

    $data = $this->iscas->get_iscas_vinculadas(false, $startRow, $endRow, $filtro, $search, $order, $orderDir);

    echo json_encode(
      array(
        "success" => true,
        'lastRow' => intval($data['qtd_total']), 
        'rows' => $data['dados']
      ));
  }

  public function getDadosIscaVinculada()
  {
    $dados = $this->input->post();
    $isca = $this->iscas->get_isca_by_id($dados['id_isca']);

    echo json_encode($isca);
  }

  public function getDadosContratoClientePorCpfOuCnpj()
  {
    $cnpj = $this->input->post('cnpj');
    $cpf = $this->input->post('cpf');
    $id_contrato = $this->input->post('id_contrato');
    $tipo = $this->input->post('tipoBusca');

    $id_iscas = explode(",", $this->input->post('id_isca'));

    if ($tipo == 'cnpj') {
      $dadosCliente = $this->iscas->getDadosContratoClienteByCnpj($cnpj);
    } else if ($tipo == 'cpf') {
      $dadosCliente = $this->iscas->getDadosContratoClienteByCpf($cpf);
    } else {
      $dadosCliente = $this->iscas->getDadosContratoClienteByContrato($id_contrato);

      if (count($dadosCliente) == 0) {
        echo json_encode(array('status' => false, 'msg' => 'O contrato informado não foi encontrado ou não está ativo'));
        return;
      }
    }

    if (count($dadosCliente) == 0) {
      echo json_encode(array('status' => false, 'msg' => 'O Cliente informado não foi encontrado'));
      return;
    } else {
      $iscasAtivasCliente = $this->iscas->getIscasAtivasByIdCliente(array(
        'id_cliente' => $dadosCliente[0]['id'],
        'status' => 1
      ));

      $quantidade_iscas = count($id_iscas);
      $quantidade_permitida = 0;
      $ids_contratos = [];

      if ($tipo == 'cnpj' || $tipo == 'cpf') {
        foreach ($dadosCliente as $key => $cliente) {
          $quantidade_permitida += $cliente['quantidade_veiculos'];
          $ids_contratos[$cliente['id_contrato']] = $cliente;
        }
      } else {

        $ids_contratos[$id_contrato] = $dadosCliente[0];

        $contratos_cliente = $this->iscas->getDadosContratoClienteByIdCliente($dadosCliente[0]['id']);
        foreach ($contratos_cliente as $key => $cliente) {
          $quantidade_permitida += $cliente['quantidade_veiculos'];
        }
      }

      if (count($iscasAtivasCliente) + $quantidade_iscas <= $quantidade_permitida) {

        //tenta inserir uma ou mais iscas em apenas um contrato
        foreach ($ids_contratos as $id_contrato => $dados) {
          $iscas_contrato = $this->iscas->getIscasAtivasByContrato($id_contrato);

          if (count($iscas_contrato) + $quantidade_iscas <= $dados['quantidade_veiculos']) {
            echo json_encode([$dados]);
            return;
          }

          if ($tipo == "id_contrato" && count($iscas_contrato) + $quantidade_iscas > $dados['quantidade_veiculos']) {
            echo json_encode(array('status' => false, 'msg' => 'O contrato informado já atingiu o número de equipamentos disponíveis. Por favor, informe outro contrato.'));
            return;
          }
        }

        //retorna os contratos necessários para alocar mais de uma isca
        if ($tipo != "id_contrato" && count($ids_contratos) > 1) {
          $contratos = [];
          foreach ($ids_contratos as $id_contrato => $dados) {
            if ($quantidade_iscas <= 0) {
              break;
            }

            $iscas_contrato = $this->iscas->getIscasAtivasByContrato($id_contrato);
            $vagasEmUso = count($iscas_contrato);
            if ($vagasEmUso < $dados['quantidade_veiculos']) {
              $vagasSemUso = $dados['quantidade_veiculos'] - $vagasEmUso;
              $quantidade_iscas -= $vagasSemUso;
              array_push($contratos, $dados);
            }
          }

          echo json_encode($contratos);
          return;
        }
      } else {
        echo json_encode(array(
          'status' => false,
          'msg' => 'Não é possível vincular a(s) isca(s) ao cliente pois o mesmo irá ultrapassar o número de equipamentos disponíveis no(s) contrato(s). Por favor, crie um novo contrato para o cliente.'
        ));
        return;
      }
    }
  }
  public function cadastrarIsca()
  {
    $dados = $this->input->post();
    $isca = [
      'serial' => $dados['serial'],
      'modelo' => $dados['modelo'],
      'marca' => "Suntech",
      'descricao' => $dados['descricao'],
      'data_cadastro' => date('Y-m-d H:i:s'),
      'status' => $dados['status'],
    ];
    $resposta = $this->iscas->insert_isca($isca);
    echo json_encode($resposta);
  }

  public function migrarIscaCliente()
  {
    $dados = $this->input->post();
    $iscas = $dados['id_isca'];

    $contratos = explode(",", $dados['id_contrato']);

    if (count($contratos) == 1) {
      $falhas = [];
      foreach ($iscas as $isca) {
        //get info anterior antes de salvar em novo contrato
        $info_isca = $this->iscas->get_cad_isca_by_id($isca, 'serial, id_contrato, id_cliente');
        $query = $this->iscas->migrarIsca($isca, $dados['id_cliente'], $contratos[0]);

        if (!$query) {
          $falhas[] = $isca;
        } else {
          $id_usuario = $this->auth->get_login('admin', 'user');
          $auditoria = array(
            'serial' => $info_isca['serial'],
            'id_cliente_antigo' => $info_isca['id_cliente'],
            'id_cliente_atual' => $dados['id_cliente'],
            'id_contrato_antigo' => $info_isca['id_contrato'],
            'id_contrato_atual' => $contratos[0],
            'id_usuario' => $id_usuario,
            'operacao' => 'migração'
          );

          $this->iscas->save_auditoria_iscas($auditoria);
        }
      }
    } else {
      $falhas = [];

      $vagas = [];
      foreach ($contratos as $contrato) {
        $iscas_contrato = $this->iscas->getIscasAtivasByContrato($contrato);
        $contrato_cliente = $this->iscas->getDadosContratoClienteByContrato($contrato);
        $vagasEmUso = count($iscas_contrato);
        $vagasSemUso = $contrato_cliente[0]['quantidade_veiculos'] - $vagasEmUso;
        $vagas[$contrato] = $vagasSemUso;
      }

      foreach ($iscas as $isca) {
        for ($i = 0; $i < count($contratos); $i++) {

          //inserindo isca no contrato
          if ($vagas[$contratos[$i]] > 0) {
            //get info anterior antes de salvar em novo contrato
            $info_isca = $this->iscas->get_cad_isca_by_id($isca, 'serial, id_contrato, id_cliente');
            $query = $this->iscas->migrarIsca($isca, $dados['id_cliente'], $contratos[$i]);

            if (!$query) {
              $falhas[] = $isca;
            } else {
              $vagas[$contratos[$i]] -= 1;

              $id_usuario = $this->auth->get_login('admin', 'user');
              $auditoria = array(
                'serial' => $info_isca['serial'],
                'id_cliente_antigo' => $info_isca['id_cliente'],
                'id_cliente_atual' => $dados['id_cliente'],
                'id_contrato_antigo' => $info_isca['id_contrato'],
                'id_contrato_atual' => $contratos[$i],
                'id_usuario' => $id_usuario,
                'operacao' => 'migração'
              );

              $this->iscas->save_auditoria_iscas($auditoria);

              break;
            }
          }
        }
      }
    }

    if (count($falhas) == count($iscas)) {
      echo json_encode([
        'status' => false,
        'msg' => 'Erro ao vincular isca(s).',
        'falhas' => $falhas
      ]);
    } else {
      echo json_encode([
        'status' => true,
        'msg' => 'Isca(s) vinculada(s) com sucesso.',
        'falhas' => $falhas
      ]);
    }
  }

  public function ajaxRelatorioIscas()
  {
    $dados = $this->input->post();
    // Monta a consulta do relatório
    $where = array();
    if ($dados['disponibilidade'] == 'estoque')
      $where['id_cliente'] = null;
    if ($dados['disponibilidade'] == 'vinculada')
      $where['id_cliente !='] = 0;
    if ($dados['status'] == 'inativo')
      $where['status'] = 0;
    if ($dados['status'] == 'ativo')
      $where['status'] = 1;
    if ($dados['checkboxPeriodo'] == 'true') {
      $where['data_cadastro >='] = $dados['dataInicio'] . ' 00:00:00';
      $where['data_cadastro <='] = $dados['dataFim'] . ' 23:59:59';
    }

    $iscas = $this->iscas->getIscas($where);
    echo json_encode($iscas);
  }
  public function desvincularIsca()
  {
    $iscas = $this->input->post('id_isca');

    $falhas = [];
    foreach ($iscas as $isca) {
      $query = $this->iscas->desvincularIsca($isca);
      if (!$query) {
        $falhas[] = $isca;
      }
    }

    if (count($falhas) == count($iscas)) {
      echo json_encode([
        'status' => false,
        'msg' => 'Erro ao desvincular isca(s).',
        'falhas' => $falhas
      ]);
    } else {
      echo json_encode([
        'status' => true,
        'msg' => 'Isca(s) desvinculada(s) com sucesso.',
        'falhas' => $falhas
      ]);
    }
  }

  public function ajaxAtivarDesativarIsca()
  {
    $id_isca = $this->input->post('id_isca');

    $result = $this->iscas->ativarDesativarIsca($id_isca);

    echo json_encode($result);
  }

  public function ajaxGetIscaAgendamento()
  {
    $serial = $this->input->post('searchTerm');
    $tipo_instalacao = $this->input->post('tipo');
    $result = $this->iscas->get_isca_agendamento($serial, $tipo_instalacao);


    echo json_encode($result);
  }
  public function ajaxGetInstalador()
  {
    $instalador = $this->input->post('searchTerm');

    $result = $this->iscas->get_instalador($instalador);

    echo json_encode($result);
  }
  public function ajaxGetCliente()
  {
    $cliente = $this->input->post('searchTerm');

    $result = $this->iscas->get_cliente($cliente);

    echo json_encode($result);
  }
  public function ajaxCadastrarAgendamento()
  {
    $dados = $this->input->post();
    $insert = array(
      'cliente_id' => $dados['cliente'],
      'instalador_id' => $dados['instalador'],
      'isca_id' => $dados['serial'],
      'data_agendamento' => $dados['dataAgendamento'] . " " . $dados['horaAgendamento'],
      'tipo' => $dados['tipo'],
      'rua' => $dados['rua'],
      'numero' => $dados['numero'],
      'bairro' => $dados['bairro'],
      'cidade' => $dados['cidade'],
      'uf' => $dados['uf'],
      'situacao' => 'em_aberto',
      'status' => 0,
      'obs' => $dados['obs']
    );

    $return = $this->iscas->cad_agendamento($insert);
    echo json_encode($return);
  }
  public function ajaxEditarAgendamento()
  {
    $dados = $this->input->post();
    $update = array(
      'cliente_id' => $dados['cliente'],
      'instalador_id' => $dados['instalador'],
      'isca_id' => $dados['serial'],
      'data_agendamento' => $dados['dataAgendamento'] . " " . $dados['horaAgendamento'],
      'tipo' => $dados['tipo'],
      'rua' => $dados['rua'],
      'numero' => $dados['numero'],
      'bairro' => $dados['bairro'],
      'cidade' => $dados['cidade'],
      'uf' => $dados['uf'],
      'situacao' => 'em_aberto',
      'status' => 0,
      'obs' => $dados['obs']
    );

    $return = $this->iscas->update_agendamento($dados['id'], $update);
    echo json_encode($return);
  }

  /**
   * Retorna os agendamentos mensais para exibir no calendário
   */
  function ajax_get_agendamentos()
  {
    $dados = $this->input->get();


    $agendamentos = $this->iscas->get_agendamentos($dados['initDate'], $dados['endDate']);
    echo json_encode($agendamentos);
  }
  public function ajax_get_agendamentos_por_dia()
  {
    $dia = $this->input->get('dia');
    $agendamentos = $this->iscas->get_agendamentos_por_dia($dia);

    echo json_encode($agendamentos);
  }

  public function ajax_get_agendamentos_por_dia_novo()
  {
    $dia = $this->input->get('dia');
    $agendamentos = $this->iscas->get_agendamentos_por_dia($dia);

    echo json_encode($agendamentos);
  }

  public function ajaxGetAgendamento()
  {
    $id_agend = $this->input->get('id');

    $agendamento = $this->iscas->get_agendamento_by_id($id_agend);

    if (count($agendamento) > 0) {
      echo json_encode(array('status' => true, 'dados' => $agendamento));
    } else {
      echo json_encode(array('status' => false, 'msg' => "Agendamento não encontrado."));
    }
  }
  public function ajax_confirmar_agendamento()
  {
    $dados = $this->input->post();

    $update = array(
      'situacao' => $dados['situacao'],
      'data_resultado' => date("Y-m-d h:i:s"),
      'status' => 1,
    );
    $resultado = $this->iscas->confirmar_agendamento($dados['id'], $update);
    echo json_encode($resultado);
  }
  public function ajaxGetIsca()
  {
    // pr($this->input->post());die();
    $serial = $this->input->post('searchTerm');
    $result = $this->iscas->get_isca($serial);
    // $data = array();
    // foreach ($result as $key => $r) {
    //   $data[] = array(
    //     'id' => $r->id,
    //     'text' => $r->text
    //   );
    // }

    echo json_encode($result);
  }

  public function getDadosIsca()
  {
    $dados = $this->input->get();
    $isca = $this->iscas->getInfoIsca($dados['id_isca']);

    echo json_encode($isca);
  }
  public function updateDadosIsca()
  {
    $dados = $this->input->post();
    $id_isca = $dados['id'];
    $update = array(
      'serial' => $dados['serial'],
      'modelo' => $dados['modelo'],
      'marca' => $dados['marca'],
      'descricao' => $dados['descricao'],
    );

    $resposta = $this->iscas->updateIsca($id_isca, $update);
    echo json_encode($resposta);
  }

  public function ajaxRelatorio()
  {
    $param = $this->input->post();
    $hora_inicio = $param['horaInicioRelatorio'] ? $param['horaInicioRelatorio'] : '00:00:00';
    $hora_fim = $param['horaFimRelatorio'] ? $param['horaFimRelatorio'] : '23:59:59';

    $isca = $param['iscaSelecionada'];
    $tipo = $param['tipoRelatorio'];


    if ($param['dataInicioRelatorio'] && $param['dataFimRelatorio'] && $isca && $tipo) {
      $data_inicio = $param['dataInicioRelatorio'] . ' ' . $hora_inicio;
      $data_fim = $param['dataFimRelatorio'] . ' ' . $hora_fim;

      $results = from_relatorios_api([
        'isca' => $isca,
        'dataIni' => $data_inicio,
        'dataFim' => $data_fim,
      ], "historicoIscaData");

      echo $results;
    } else {
      echo [
        'status' => false,
        'msg' => 'Datas inválidas ou tipo não selecionado.',
        'dados' => [],
      ];
    }
  }

  public function ajaxRelatorioComandos()
  {
    $parametros = $this->input->post();
    $hora_inicio = $parametros['horaInicioRelatorio'] ? $parametros['horaInicioRelatorio'] : "00:00:00";
    $hora_fim = $parametros['horaFimRelatorio'] ? $parametros['horaFimRelatorio'] : "29:59:59";
    $data_inicio = $parametros['dataInicioRelatorio'] . " " . $hora_inicio;
    $data_fim = $parametros['dataFimRelatorio'] . " " . $hora_fim;
    $serial = $parametros['iscaSelecionada'];

    $where = [
      'cmd_cadastro >=' => $data_inicio,
      'cmd_cadastro <=' => $data_fim,
      'cmd_eqp' => $serial
    ];

    $comandos = $this->comandos->relatorioComandosIsca($where);

    foreach ($comandos as $key => $comando) {
      if ($comando->cmd_envio == null && $comando->cmd_confirmacao != null) {
        $comando->status = 'Cancelado';
      } else if ($comando->cmd_envio == null && $comando->cmd_confirmacao == null) {
        $comando->status = 'Aguardando Envio';
      } elseif ($comando->cmd_envio != null && $comando->cmd_confirmacao == null) {
        $comando->status = 'Aguardando Confirmação';
      } elseif ($comando->cmd_envio != null && $comando->cmd_confirmacao != null) {
        $comando->status = 'Confirmado';
      }
    }

    echo json_encode([
      'status' => true,
      'dados' => $comandos
    ]);
  }

  public function ajaxComandosIsca()
  {
    $parametros = $this->input->post();
    $serial = $parametros['iscaSelecionada'];

    $where = [
      'cmd_eqp' => $serial,
    ];

    $comandos = $this->comandos->comandosIsca($where);

    $data = [];
    foreach ($comandos as $key => $comando) {
      $data[] = [
        $comando->descricao_comando ? $comando->descricao_comando : '',
        $comando->cmd_cadastro ? $comando->cmd_cadastro : '',
        $comando->cmd_envio ? $comando->cmd_envio : '',
        $comando->cmd_confirmacao ? $comando->cmd_confirmacao : '',
        ($comando->cmd_envio == null && $comando->cmd_confirmacao == null ?
          'Aguardando Envio'
          : ($comando->cmd_envio != null && $comando->cmd_confirmacao == null ?
            'Aguardando Confirmação'
            : ($comando->cmd_envio != null && $comando->cmd_confirmacao != null ?
              'Confirmado'
              : ($comando->cmd_envio == null && $comando->cmd_confirmacao != null && $comando->cmd_cadastro != null ?
                'Cancelado'
                : '')))),
        $comando->cmd_id,
        $comando->cmd_comando,
      ];
    }

    echo json_encode([
      'status' => true,
      'data' => $data
    ]);
  }

  public function ajaxCancelarComando()
  {
    $id = $this->input->post('cmd_id');

    if ($id) {
      $query = $this->comandos->cancelarComando($id, date('Y-m-d H:i:s'));
      echo json_encode([
        'status' => $query,
        'msg' => $query ? 'Comando cancelado com sucesso' : 'Erro ao cancelar comando',
      ]);
    } else {
      echo json_encode([
        'status' => false,
        'msg' => 'Comando inválido.'
      ]);
    }
  }

  public function buscarDadosIsca()
  {
    $dados = $this->input->post('serial');

    if ($dados) {
      $query = $this->comandos->buscarDadosIsca($dados);
      if ($query && count($query) > 0) {
        echo json_encode([
          'status' => true,
          'msg' => 'Dados da isca encontrados com sucesso.',
          'serial' => $query[0]->serial,
          'firmware' => $query[0]->ver_app,
          'ccid' => $query[0]->ccid,
          'apn' => $query[0]->apn,
          'usuario' => $query[0]->usuario,
          'senha' => $query[0]->senha,
          'ip1' => $query[0]->ip1,
          'porta1' => $query[0]->porta1,
          'ip2' => $query[0]->ip2,
          'porta2' => $query[0]->porta2,
        ]);
      } else {
        echo json_encode([
          'status' => false,
          'msg' => 'Dados da isca não encontrados.',
        ]);
      }
    } else {
      echo json_encode([
        'status' => false,
        'msg' => 'Selecione uma isca.'
      ]);
    }
  }
  public function buscarDadosIscaMassa()
  {
    $clienteId = $this->input->post('cliente');
    $seriaisCliente = $this->iscasPorCliente($clienteId);
    $dados = $this->input->post('selectAll') == 'true' ? $seriaisCliente : $this->input->post('serial');
    $dados = array_unique($dados);
    $resposta = array();

    if ($dados) {
      foreach ($dados as $key => $serial) {
        if (in_array($serial, $seriaisCliente)) {
          $query = $this->comandos->buscarDadosIsca($serial);
          array_push($resposta, [
            'serial' => $serial,
            'firmware' => (count($query) > 0 ? $query[0]->ver_app : null),
            'ccid' => (count($query) > 0 ? $query[0]->ccid : null),
            'apn' => (count($query) > 0 ?  $query[0]->apn : null),
            'usuario' => (count($query) > 0 ? $query[0]->usuario :  null),
            'senha' => (count($query) > 0 ? $query[0]->senha : null),
            'ip1' => (count($query) > 0 ? $query[0]->ip1 : null),
            'porta1' => (count($query) > 0 ? $query[0]->porta1 : null),
            'ip2' => (count($query) > 0 ? $query[0]->ip2 : null),
            'porta2' => (count($query) > 0 ? $query[0]->porta2 : null),
          ]);
        }
      }

      echo json_encode([
        'status' => true,
        'msg' => 'Dados da isca encontrados com sucesso.',
        'dados' => $resposta
      ]);
    } else {
      echo json_encode([
        'status' => false,
        'msg' => 'Selecione uma isca.'
      ]);
    }
  }

  function iscasPorCliente($idCliente)
  {
    $lista = [];

    if ($idCliente) {
      $where['id_cliente'] = $idCliente;
      $lista = $this->iscas->getIscas($where);

      $results = [];
      foreach ($lista as $isca) {
        $results[] = $isca['serial'];
      }

      return $results;
    }
  }

  function ajaxAlterarDataExpiracao()
  {
    $dados = $this->input->post();

    if (
      !isset($dados) ||
      (!isset($dados['dataExpiracao']) || $dados['dataExpiracao'] == "") ||
      (!isset($dados['horaExpiracao']) || $dados['horaExpiracao'] == "") ||
      (!isset($dados['iscas']) || count($dados['iscas']) == 0)
    ) {
      echo json_encode(array(
        'status' => false,
        'message' => 'Informe os dados corretamente!'
      ));
    } else {
      $dataHoraExpiracao = $dados['dataExpiracao'] . " " . $dados['horaExpiracao'];

      $resposta = $this->iscas->alterarDataExpiracao($dados['iscas'], $dataHoraExpiracao);

      echo json_encode($resposta);
    }
  }

  function relatorioIscasExpiradas()
  {
    $dados['titulo'] = "Relatório de Iscas Expiradas";
    $dados['load'] = array('datatable_responsive', 'buttons_html5');

    $this->load->view('fix/header-new', $dados);
    $this->load->view('relatorios/iscas/relatorioIscasExpiradas');
    $this->load->view('fix/footer_new');
  }

  function ajaxIscasExpiradas()
  {
    $body = $this->input->post();
    $this->load->helper('api_helper');
    echo API_Helper::post("relatorios/iscasExpiradas", $body);
  }

  /*
	* Lista iscas para select2
	*/
  function ajaxListSelect()
  {
    $like = NULL;
    if ($search = $this->input->get('q')) {
      $tipo_busca = $this->input->get('tipo_busca');

      if ($tipo_busca == "id") {
        $like = array('id' => $search);
      } else {
        $like = array('serial' => $search);
      }
    }

    if ($tipo_busca == "id") {
      exit(json_encode(array('results' => $this->iscas->listar(array(), 0, 10, 'id', 'asc', "CONCAT((id),(' - '),(serial)) as text, id", $like))));
    } else {
      exit(json_encode(array('results' => $this->iscas->listar(array(), 0, 10, 'serial', 'asc', 'serial as text, id', $like))));
    }
  }
}
