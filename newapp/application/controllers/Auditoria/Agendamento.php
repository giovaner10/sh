<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Agendamento extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->load->helper('util_helper');
        $this->load->helper('agendamento_helper');
    }

    public function index()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Auditoria/Agendamento'));

        $dados['titulo'] = lang('auditoria');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('Auditoria/Agendamento');
        $this->load->view('fix/footer_NS');
    }

    public function AgendamentoInstalacao()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Auditoria/Agendamento/AgendamentoInstalacao'));

        $dados['titulo'] = lang('auditoria');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('Auditoria/AgendamentoInstalacao');
        $this->load->view('fix/footer_NS');
    }

    public function AgendamentoManutencao()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Auditoria/Agendamento/AgendamentoManutencao'));

        $dados['titulo'] = lang('auditoria');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('Auditoria/AgendamentoManutencao');
        $this->load->view('fix/footer_NS');
    }

    public function relatorioAgendamentoOld()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Auditoria/RelatorioAgendamento'));

        $this->auth->is_allowed('vis_relatorio_instalacao');

        $dados['titulo'] = lang('relatorio_agendamento');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('Auditoria/RelatorioAgendamento');
        $this->load->view('fix/footer_NS');
    }

    public function relatorioAgendamento()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Auditoria/RelatorioAgendamento'));

        $this->auth->is_allowed('vis_relatorio_instalacao');

        $dados['titulo'] = lang('relatorio_instalacao');
        $dados['load'] = array('ag-grid', 'select2', 'mask');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('Auditoria/RelatorioAgendamentoNew');
        $this->load->view('fix/footer_NS');
    }

    public function relatorioAgendamentoDetalhado()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Auditoria/RelatorioAgendamento'));

        $this->auth->is_allowed('vis_relatorio_instalacao');

        $dados['titulo'] = lang('relatorio_instalacao_detalhado');
        $dados['load'] = array('ag-grid', 'select2', 'mask');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('Auditoria/RelatorioAgendamentoDetalhado');
        $this->load->view('fix/footer_NS');
    }

    public function relatorioManutencaoDetalhado()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Auditoria/RelatorioAgendamento'));

        $this->auth->is_allowed('vis_relatorio_instalacao');

        $dados['titulo'] = lang('relatorio_manutencao_detalhado');
        $dados['load'] = array('ag-grid', 'select2', 'mask');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('Auditoria/RelatorioManutencaoDetalhado');
        $this->load->view('fix/footer_NS');
    }

    public function relatorioManutencaoOld()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Auditoria/RelatorioManutencao'));

        $this->auth->is_allowed('vis_relatorio_manutencao');

        $dados['titulo'] = lang('relatorio_manutencao');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('Auditoria/RelatorioManutencao');
        $this->load->view('fix/footer_NS');
    }

    public function relatorioManutencao()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/Auditoria/RelatorioManutencao'));

        $this->auth->is_allowed('vis_relatorio_manutencao');

        $dados['titulo'] = lang('relatorio_manutencao');
        $dados['load'] = array('ag-grid', 'select2', 'mask');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('Auditoria/RelatorioManutencaoNew');
        $this->load->view('fix/footer_NS');
    }

    public function generateServerSideReport() {
        $postDataJSON = file_get_contents('php://input');
        $postData = json_decode($postDataJSON, true);
        $searchOptions = $postData['searchOptions'];
        $tipoArquivo = $postData['type'];
    
        $dataInicial = isset($searchOptions['dataInicial']) ? date('d/m/Y', strtotime($searchOptions['dataInicial'])) : (new DateTime())->sub(new DateInterval('P5M'))->format('d/m/Y');
        $dataFinal = isset($searchOptions['dataFinal']) ? date('d/m/Y', strtotime($searchOptions['dataFinal'])) : date('d/m/Y');
        $nomeTecnico = isset($searchOptions['nomeTecnico']) ? urlencode($searchOptions['nomeTecnico']) : null;

        if ($tipoArquivo !== 'pdf' && $tipoArquivo !== 'xlsx') {
            header("HTTP/1.1 400 Bad Request");
            echo "Tipo de arquivo não suportado.";
            exit();
        }
    
        downloadReportFile($tipoArquivo, $dataInicial, $dataFinal);
    }

    public function generateServerSideReportInstalacao() {
        $postDataJSON = file_get_contents('php://input');
        $postData = json_decode($postDataJSON, true);
        $searchOptions = $postData['searchOptions'];
        $tipoArquivo = $postData['type'];
    
        $dataInicial = isset($searchOptions['dataInicial']) ? date('d/m/Y', strtotime($searchOptions['dataInicial'])) : (new DateTime())->sub(new DateInterval('P5M'))->format('d/m/Y');
        $dataFinal = isset($searchOptions['dataFinal']) ? date('d/m/Y', strtotime($searchOptions['dataFinal'])) : date('d/m/Y');
        $nomeTecnico = isset($searchOptions['nomeTecnico']) ? urlencode($searchOptions['nomeTecnico']) : null;
    
        if ($tipoArquivo !== 'pdf' && $tipoArquivo !== 'xlsx') {
            header("HTTP/1.1 400 Bad Request");
            echo "Tipo de arquivo não suportado.";
            exit();
        }
    
        downloadReportFileInstalacao($tipoArquivo, $dataInicial, $dataFinal, $nomeTecnico);
    }
    
    
    public function listarAgendamentos()
    {
        $data = $this->input->post('searchOptions');
        $status = $data['status'];
        $idConversation =  $data['id_conversation'];

        $dataInicial = $data['dataInicial'];
        $dataFinal = $data['dataFinal'];
        $tresMeses = new DateInterval('P3M');
        $tresMeses->invert = 1;

        if (!$dataInicial) {
            $dataInicial = new DateTime();
            $dataInicial->add($tresMeses);
            $dataInicial = $dataInicial->format('d/m/Y');
        } else {
            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
        }

        if (!$dataFinal) {
            $dataFinal = date('d/m/Y');
        } else {
            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $agendamentos = to_listarAgendamentos($dataInicial, $dataFinal);

        if ($idConversation != null || $idConversation != "") {
            $agendamentos = to_listarAgendamentosByConversation($idConversation);
        }

        if ($status != null || $status != "") {
            $agendamentos = to_listarAgendamentosByStatus($status);
        }

        echo json_encode($agendamentos);
    }

    public function listarAgendamentosServerSide()
    {
        $data = $this->input->post('searchOptions');
        $startRow = $this->input->post('startRow');
        $endRow = $this->input->post('endRow');
        $status = $data['status'];
        $idConversation =  $data['id_conversation'];


        $dataInicial = $data['dataInicial'];
        $dataFinal = $data['dataFinal'];
        $tresMeses = new DateInterval('P3M');
        $tresMeses->invert = 1;

        if (!$dataInicial) {
            $dataInicial = new DateTime();
            $dataInicial->add($tresMeses);
            $dataInicial = $dataInicial->format('d/m/Y');
        } else {
            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
        }

        if (!$dataFinal) {
            $dataFinal = date('d/m/Y');
        } else {
            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $agendamentos = to_listarAgendamentosPaginated($dataInicial, $dataFinal, $startRow, $endRow);

        if ($idConversation != null || $idConversation != "") {
            $agendamentos = to_listarAgendamentosByConversationPaginated($idConversation, $startRow, $endRow);
        }

        if ($status != null || $status != "") {
            $agendamentos = to_listarAgendamentosByStatusPaginated($status, $startRow, $endRow);
        }

        if ($agendamentos['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $agendamentos['dados']['listaConversationNameDTO'],
                "lastRow" => $agendamentos['dados']['qtdTotalEventos']
            ));
        } else if ($agendamentos['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => $agendamentos['dados']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $agendamentos['dados']['mensagem'],
            ));
        }
    }

    public function listarRelatorioServerSide()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');

        $startRow++;

        $searchOptionsRaw = $this->input->post('searchOptions', TRUE);
        $searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

        $tecnicoId = isset($searchOptions['nomeTecnico']) ? urlencode($searchOptions['nomeTecnico']) : null;
        $dataInicial = isset($searchOptions['dataInicial']) && $searchOptions['dataInicial'] != "" ? $searchOptions['dataInicial'] : null;
        $dataFinal = isset($searchOptions['dataFinal']) && $searchOptions['dataFinal'] != "" ? $searchOptions['dataFinal'] : null;

        if (isset($dataInicial) && isset($dataFinal)) {
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $agendamentos = to_listarRelatorioPaginated($dataInicial, $dataFinal, $startRow, $endRow, $tecnicoId);

        if ($agendamentos['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $agendamentos['dados']['rows'],
                "lastRow" => $agendamentos['dados']['lastRow']
            ));
        } else if ($agendamentos['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Cliente ou placa não encontrados!",
            ));
        }
        else {
            echo json_encode(array(
                "success" => false,
                "message" => $agendamentos['dados']['mensagem'],
            ));
        }
    }
    
    public function listarTodosRelatorioServerSide()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');

        $startRow++;

        $searchOptionsRaw = $this->input->post('searchOptions', TRUE);
        $searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

        $tecnicoId = isset($searchOptions['nomeTecnico']) ? urlencode($searchOptions['nomeTecnico']) : null;
        $dataInicial = isset($searchOptions['dataInicial']) && $searchOptions['dataInicial'] != "" ? $searchOptions['dataInicial'] : null;
        $dataFinal = isset($searchOptions['dataFinal']) && $searchOptions['dataFinal'] != "" ? $searchOptions['dataFinal'] : null;

        if (isset($dataInicial) && isset($dataFinal)) {
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $agendamentos = to_listarTodosRelatorioPaginatedRoute($dataInicial, $dataFinal, $startRow, $endRow, $tecnicoId);

        if ($agendamentos['status'] == '200' && $agendamentos['dados']['lastRow'] > 0) {
            echo json_encode(array(
                "success" => true,
                "rows" => $agendamentos['dados']['rows'],
                "lastRow" => $agendamentos['dados']['lastRow']
            ));
        } else if ($agendamentos['status'] == '404' || $agendamentos['dados']['lastRow'] == 0) {
            echo json_encode(array(
                "success" => false,
                "message" => "Dados não encontrados!",
            ));
        }
        else {
            echo json_encode(array(
                "success" => false,
                "message" => $agendamentos['dados']['mensagem'],
            ));
        }
    }

    public function listarRelatorioManutencaoServerSide()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');

        $startRow++;

        $searchOptionsRaw = $this->input->post('searchOptions', TRUE);
        $searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

        $tecnicoId = isset($searchOptions['nomeTecnico']) ? urlencode($searchOptions['nomeTecnico']) : null;
        $dataInicial = isset($searchOptions['dataInicial']) && $searchOptions['dataInicial'] != '' ? $searchOptions['dataInicial'] : null;
        $dataFinal = isset($searchOptions['dataFinal']) && $searchOptions['dataFinal'] != '' ? $searchOptions['dataFinal'] : null;

        if (isset($dataInicial) && isset($dataFinal)) {
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $agendamentos = to_listarRelatorioManutencaoPaginated($dataInicial, $dataFinal, $startRow, $endRow, $tecnicoId);

        if ($agendamentos['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $agendamentos['dados']['rows'],
                "lastRow" => $agendamentos['dados']['lastRow']
            ));
        } else if ($agendamentos['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Cliente ou placa não encontrados!",
            ));
        }
        else {
            echo json_encode(array(
                "success" => false,
                "message" => $agendamentos['dados']['mensagem'],
            ));
        }
    }

    public function listarRelatorioManutencaoConsolidadoServerSide()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');

        $startRow++;

        $searchOptionsRaw = $this->input->post('searchOptions', TRUE);
        $searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

        $tecnicoId = isset($searchOptions['nomeTecnico']) ? urlencode($searchOptions['nomeTecnico']) : null;
        $dataInicial = isset($searchOptions['dataInicial']) && $searchOptions['dataInicial'] != '' ? $searchOptions['dataInicial'] : null;
        $dataFinal = isset($searchOptions['dataFinal']) && $searchOptions['dataFinal'] != '' ? $searchOptions['dataFinal'] : null;

        if (isset($dataInicial) && isset($dataFinal)) {
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $agendamentos = to_listarRelatorioManutencaoConsolidadoPaginated($dataInicial, $dataFinal, $startRow, $endRow, $tecnicoId);

        if ($agendamentos['status'] == '200' && (int) $agendamentos['dados']['lastRow'] > 0) {
            echo json_encode(array(
                "success" => true,
                "rows" => $agendamentos['dados']['rows'],
                "lastRow" => $agendamentos['dados']['lastRow']
            ));
        } else if ($agendamentos['status'] == '404' || $agendamentos['dados']['lastRow'] == 0) {
            echo json_encode(array(
                "success" => false,
                "message" => "Dados não encontrados!",
            ));
        }
        else {
            echo json_encode(array(
                "success" => false,
                "message" => $agendamentos['dados']['mensagem'],
            ));
        }
    }


    public function pegarAgendamento()
    {
        $idConversation =  $this->input->post('idConversation');
        $agendamentos = to_buscarAgendamento($idConversation);

        echo json_encode($agendamentos);
    }

    public function pegarAgenda()
    {
        $idConversation =  $this->input->post('idConversation');
        $agenda = to_agenda_instalacao($idConversation);

        echo json_encode($agenda);
    }

    public function pegarAuditTrack()
    {
        $idConversation = $this->input->post('idConversation');
        $idInstalador = $this->input->post('idInstalador');

        $audit_tracker = to_audit_tracker($idConversation, $idInstalador);

        // Ordenar por 'acao', colocando 'SUCCESS' no início
        usort($audit_tracker['dados'], function ($a, $b) {
            if ($a['acao'] === 'SUCCESS' && $b['acao'] !== 'SUCCESS') {
                return -1;
            } elseif ($a['acao'] !== 'SUCCESS' && $b['acao'] === 'SUCCESS') {
                return 1;
            } else {
                // Se as ações forem iguais ou diferentes de 'SUCCESS', ordene por 'createdAt'
                return strtotime($b['createdAt']) - strtotime($a['createdAt']);
            }
        });

        echo json_encode($audit_tracker);
    }

    public function pegarAuditTrackManutencao()
    {
        $idConversation = $this->input->post('idConversation');
        $idMantenedor = $this->input->post('idMantenedor');

        $audit_tracker = to_audit_tracker_manutencao($idConversation, $idMantenedor);

        // Ordenar por 'acao', colocando 'SUCCESS' no início
        usort($audit_tracker['dados'], function ($a, $b) {
            if ($a['acao'] === 'SUCCESS' && $b['acao'] !== 'SUCCESS') {
                return -1;
            } elseif ($a['acao'] !== 'SUCCESS' && $b['acao'] === 'SUCCESS') {
                return 1;
            } else {
                // Se as ações forem iguais ou diferentes de 'SUCCESS', ordene por 'createdAt'
                return strtotime($b['createdAt']) - strtotime($a['createdAt']);
            }
        });

        echo json_encode($audit_tracker);
    }


    public function pegarSMS()
    {
        $idConversation =  $this->input->post('idConversation');
        $sms = to_sms_installers($idConversation);

        echo json_encode($sms);
    }

    public function pegarSMSManutencao()
    {
        $idConversation =  $this->input->post('idConversation');
        $sms = to_sms_manutencao($idConversation);

        echo json_encode($sms);
    }

    public function listarRelatorioSmsPorPeriodo()
    {
        $dataInicial =  $this->input->post('dataInicial');
        $dataFinal =  $this->input->post('dataFinal');

        if ($dataInicial && $dataFinal) {

            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));

            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $retorno = get_listarRelatorioSmsPorPeriodo($dataInicial, $dataFinal);

        echo json_encode($retorno);
    }

    public function listarUltimosCemRelatorioTecnicos()
    {
        $retorno = get_listarUltimosCemSmsRelatorioTecnicos();

        $contagemAcoes = array(
            'ATTEMPT' => 0,
            'CLOSE' => 0,
            'OPEN' => 0,
            'SUCCESS' => 0,
            'ACCEPT' => 0,
            'CANCEL' => 0,
            'NULL' => 0,
        );

        if ($retorno['status'] == '200') {
            $resultados = $retorno['results'];

            foreach ($resultados as $resultado) {
                $acao = $resultado['acao'];

                if (array_key_exists($acao, $contagemAcoes)) {
                    $contagemAcoes[$acao]++;
                } else {
                    $contagemAcoes['NULL']++;
                }
            }
        }

        $retorno['contagemAcoes'] = $contagemAcoes;

        echo json_encode($retorno);
    }

    public function listarUltimosCemRelatorioTecnicosManutencao()
    {

        $retorno = get_listarUltimosCemSmsRelatorioTecnicosManutencao();

        $contagemAcoes = array(
            'ATTEMPT' => 0,
            'CLOSE' => 0,
            'OPEN' => 0,
            'SUCCESS' => 0,
            'ACCEPT' => 0,
            'CANCEL' => 0,
            'NULL' => 0,
        );

        if ($retorno['status'] == '200') {
            $resultados = $retorno['results'];

            foreach ($resultados as $resultado) {
                $acao = $resultado['acao'];

                if (array_key_exists($acao, $contagemAcoes)) {
                    $contagemAcoes[$acao]++;
                } else {
                    $contagemAcoes['NULL']++;
                }
            }
        }

        $retorno['contagemAcoes'] = $contagemAcoes;

        echo json_encode($retorno);
    }

    public function listarTecnicos()
    {

        $retorno = to_listarTecnicos();

        echo json_encode($retorno);
    }

    public function listarTecnicosManutencao()
    {

        $retorno = to_listarTecnicosManutencao();

        echo json_encode($retorno);
    }

    public function listarRelatorioSmsPorNomeTecnicoEData()
    {
        $nomeTecnico =  $this->input->post('nomeTecnico');
        $dataInicial =  $this->input->post('dataInicial');
        $dataFinal =  $this->input->post('dataFinal');

        if ($nomeTecnico) {
            $nomeTecnico = urlencode($nomeTecnico);
        }
        if ($dataInicial && $dataFinal) {

            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));

            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $retorno = get_listarRelatorioSmsTecnicoEData($nomeTecnico, $dataInicial, $dataFinal);

        $contagemAcoes = array(
            'ATTEMPT' => 0,
            'CLOSE' => 0,
            'OPEN' => 0,
            'SUCCESS' => 0,
            'ACCEPT' => 0,
            'CANCEL' => 0,
            'NULL' => 0,
        );

        if ($retorno['status'] == '200') {
            $resultados = $retorno['results'];

            foreach ($resultados as $resultado) {
                $acao = $resultado['acao'];

                if (array_key_exists($acao, $contagemAcoes)) {
                    $contagemAcoes[$acao]++;
                } else {
                    $contagemAcoes['NULL']++;
                }
            }
        }

        $retorno['contagemAcoes'] = $contagemAcoes;

        echo json_encode($retorno);
    }

    public function listarRelatorioManutencaoSmsPorNomeTecnicoEData()
    {
        $nomeTecnico =  $this->input->post('nomeTecnico');
        $dataInicial =  $this->input->post('dataInicial');
        $dataFinal =  $this->input->post('dataFinal');

        if ($nomeTecnico) {
            $nomeTecnico = urlencode($nomeTecnico);
        }
        if ($dataInicial && $dataFinal) {

            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));

            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $retorno = get_listarRelatorioManutencaoSmsTecnicoEData($nomeTecnico, $dataInicial, $dataFinal);

        $contagemAcoes = array(
            'ATTEMPT' => 0,
            'CLOSE' => 0,
            'OPEN' => 0,
            'SUCCESS' => 0,
            'ACCEPT' => 0,
            'CANCEL' => 0,
            'NULL' => 0,
        );

        if ($retorno['status'] == '200') {
            $resultados = $retorno['results'];

            foreach ($resultados as $resultado) {
                $acao = $resultado['acao'];

                if (array_key_exists($acao, $contagemAcoes)) {
                    $contagemAcoes[$acao]++;
                } else {
                    $contagemAcoes['NULL']++;
                }
            }
        }

        $retorno['contagemAcoes'] = $contagemAcoes;

        echo json_encode($retorno);
    }

    public function alterarStatusAgendamento()
    {
        $idLinha =  $this->input->post('idLinha');
        $status =  $this->input->post('status');
        $retorno = to_alterarStatusAgendamento($idLinha, $status);

        echo json_encode($retorno);
    }

    public function listarDadosDashboardAgendamento()
    {
        $input = $this->input->post('searchOptions');
        $mes = isset($input['mes']) ? $input['mes'] : null;
        $ano = isset($input['ano']) ?  $input['ano'] : null;
        $periodo = isset($input['periodo']) ? $input['periodo'] : null;
        $dataInicial = isset($input['dataInicialDashboard']) ? $input['dataInicialDashboard'] : null;
        $dataFinal = isset($input['dataFinalDashboard']) ? $input['dataFinalDashboard'] : null;
        $tresMeses = new DateInterval('P3M');
        $tresMeses->invert = 1;

        if (!$dataInicial) {
            $dataInicial = new DateTime();
            $dataInicial->add($tresMeses);
            $dataInicial = $dataInicial->format('d/m/Y');
        } else {
            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
        }

        if (!$dataFinal) {
            $dataFinal = date('d/m/Y');
        } else {
            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        if ($mes) {
            $month = date('m', strtotime($mes));
            $year = date('Y', strtotime($mes));
            $dataInicial = "01/$month/$year";

            list($day, $month, $year) = explode('/', $dataInicial);
            $dataFormatada = "$year-$month-$day";

            $dataFinal = date('d/m/Y', strtotime('last day of this month', strtotime($dataFormatada)));
        } elseif ($ano) {
            $dataInicial = "01/01/$ano";
            $dataFinal = "31/12/$ano";
        } elseif ($periodo) {
            switch ($periodo) {
                case '7days':
                    $dataInicial = date('d/m/Y', strtotime('-7 days'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '1mes':
                    $dataInicial = date('d/m/Y', strtotime('first day of -1 month'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '3mes':
                    $dataInicial = date('d/m/Y', strtotime('-3 months'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '6mes':
                    $dataInicial = date('d/m/Y', strtotime('-6 months'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '12mes':
                    $dataInicial = date('d/m/Y', strtotime('-1 year'));
                    $dataFinal = date('d/m/Y');
                    break;
            }
        }

        $agendamentos = to_listarAgendamentos($dataInicial, $dataFinal);
        $contagemStatusPorMes = array();

        if ($agendamentos['status'] == '200') {
            $result = $agendamentos['dados'];

            $contagemStatusPorMes = array();

            foreach ($result as $agendamento) {
                $status = $agendamento['status'];
                $dataAgendamento = new DateTime($agendamento['createdAt']);
                $mesAno = $dataAgendamento->format('m/Y');

                if (!isset($contagemStatusPorMes[$mesAno])) {
                    $contagemStatusPorMes[$mesAno] = array(
                        'NAO_AGENDADO' => 0,
                        'AGENDADO' => 0,
                        'ATENDENTE' => 0,
                        'AGUARDANDO_INSTALADOR' => 0,
                        'AGENDADO_ATENDENTE' => 0,
                        'CANCELADO_AUSENTE' => 0,
                        'EM_ATENDIMENTO' => 0,
                        'CONCLUIDO_FINALIZADO' => 0,
                        'CANCELADO_TECNICO' => 0,
                    );
                }

                $contagemStatusPorMes[$mesAno][$status]++;
            }
        }

        echo json_encode($contagemStatusPorMes);
    }

    public function listarDadosRecusaTecnicosGlobal()
    {
        $mes = $this->input->post('month');

        //Default
        $currentDate = date('Y-m');

        $month = date('m', strtotime($currentDate));
        $year = date('Y', strtotime($currentDate));
        $dataInicial = "$year-$month-01";

        list($year, $month, $day) = explode('-', $dataInicial);
        $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));

        $dataFinal = date('Y-m-d', strtotime("$year-$month-$lastDay"));

        if ($mes  !== '') {
            $month = date('m', strtotime($mes));
            $year = date('Y', strtotime($mes));
            $dataInicial = "$year-$month-01";

            list($year, $month, $day) = explode('-', $dataInicial);
            $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));
            $dataFormatada = "$year-$month-$lastDay";

            $dataFinal = date('Y-m-d', strtotime($dataFormatada));
        }

        $recusaTecnicos = to_RecusaTecnicosInstalacao($dataInicial, $dataFinal);
        $recusaTecnicos['anoMes'] = "$year-$month";


        $contagemRecusa = array(
            'dataIndisponivel' => 0,
            'pontoFixo' => 0,
            'operacao' => 0,
            'kmMuitoLonge' => 0,
            'erro' => 0,
            'localRisco' => 0,
            'atestado' => 0,
            'naoAtendeOmnilink' => 0,
            'motivoNaoInformado' => 0,
            'totalRecusa' => 0,
        );

        if ($recusaTecnicos['status'] == '200') {
            $result = $recusaTecnicos['dados'];

            foreach ($result as $recusaTecnico) {
                $contagemRecusa['dataIndisponivel'] += $recusaTecnico['dataIndisponivel'];
                $contagemRecusa['pontoFixo'] += $recusaTecnico['pontoFixo'];
                $contagemRecusa['operacao'] += $recusaTecnico['operacao'];
                $contagemRecusa['kmMuitoLonge'] += $recusaTecnico['kmMuitoLonge'];
                $contagemRecusa['erro'] += $recusaTecnico['erro'];
                $contagemRecusa['localRisco'] += $recusaTecnico['localRisco'];
                $contagemRecusa['atestado'] += $recusaTecnico['atestado'];
                $contagemRecusa['naoAtendeOmnilink'] += $recusaTecnico['naoAtendeOmnilink'];
                $contagemRecusa['motivoNaoInformado'] += $recusaTecnico['motivoNaoInformado'];
                $contagemRecusa['totalRecusa'] += $recusaTecnico['totalRecusa'];
            }
        }

        echo json_encode(array(
            'status' => $recusaTecnicos['status'],
            'data' => $contagemRecusa,
            'mes' => $recusaTecnicos['anoMes']
        ));
    }

    public function listarDadosMaiorRecusaTecnicos()
    {
        $mes = $this->input->post('month');

        //Default
        $currentDate = date('Y-m');

        $month = date('m', strtotime($currentDate));
        $year = date('Y', strtotime($currentDate));
        $dataInicial = "$year-$month-01";

        list($year, $month, $day) = explode('-', $dataInicial);
        $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));

        $dataFinal = date('Y-m-d', strtotime("$year-$month-$lastDay"));

        if ($mes  !== '') {
            $month = date('m', strtotime($mes));
            $year = date('Y', strtotime($mes));
            $dataInicial = "$year-$month-01";

            list($year, $month, $day) = explode('-', $dataInicial);
            $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));
            $dataFormatada = "$year-$month-$lastDay";

            $dataFinal = date('Y-m-d', strtotime($dataFormatada));
        }

        $recusaTecnicos = to_RecusaTecnicosInstalacao($dataInicial, $dataFinal);
        $recusaTecnicos['dados'] = array_slice($recusaTecnicos["dados"], 0, 10);
        $recusaTecnicos['anoMes'] = "$year-$month";

        $contagemRecusa = array();

        if ($recusaTecnicos['status'] == '200') {
            $result = $recusaTecnicos['dados'];

            foreach ($result as $recusaTecnico) {
                $contagemRecusa[] = array(
                    'nomeTecnico' => $recusaTecnico['nomeTecnico'],
                    'totalRecusa' => $recusaTecnico['totalRecusa']
                );
            }
        }

        echo json_encode(array(
            'status' => $recusaTecnicos['status'],
            'data' => $contagemRecusa,
            'mes' => $recusaTecnicos['anoMes']
        ));
    }

    public function listarDadosMenorRecusaTecnicos()
    {
        $mes = $this->input->post('month');

        //Default
        $currentDate = date('Y-m');

        $month = date('m', strtotime($currentDate));
        $year = date('Y', strtotime($currentDate));
        $dataInicial = "$year-$month-01";

        list($year, $month, $day) = explode('-', $dataInicial);
        $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));

        $dataFinal = date('Y-m-d', strtotime("$year-$month-$lastDay"));

        if ($mes  !== '') {
            $month = date('m', strtotime($mes));
            $year = date('Y', strtotime($mes));
            $dataInicial = "$year-$month-01";

            list($year, $month, $day) = explode('-', $dataInicial);
            $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));
            $dataFormatada = "$year-$month-$lastDay";

            $dataFinal = date('Y-m-d', strtotime($dataFormatada));
        }

        $recusaTecnicos = to_RecusaTecnicosInstalacao($dataInicial, $dataFinal);
        $recusaTecnicos['dados'] = array_slice($recusaTecnicos["dados"], -10, 10);
        $recusaTecnicos['anoMes'] = "$year-$month";

        $contagemRecusa = array();

        if ($recusaTecnicos['status'] == '200') {
            $result = $recusaTecnicos['dados'];

            foreach ($result as $recusaTecnico) {
                $contagemRecusa[] = array(
                    'nomeTecnico' => $recusaTecnico['nomeTecnico'],
                    'totalRecusa' => $recusaTecnico['totalRecusa']
                );
            }
        }

        echo json_encode(array(
            'status' => $recusaTecnicos['status'],
            'data' => $contagemRecusa,
            'mes' => $recusaTecnicos['anoMes']
        ));
    }

    public function listarDadosRecusaManutencaoTecnicosGlobal()
    {
        $mes = $this->input->post('month');

        //Default
        $currentDate = date('Y-m');

        $month = date('m', strtotime($currentDate));
        $year = date('Y', strtotime($currentDate));
        $dataInicial = "$year-$month-01";

        list($year, $month, $day) = explode('-', $dataInicial);
        $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));

        $dataFinal = date('Y-m-d', strtotime("$year-$month-$lastDay"));

        if ($mes  !== '') {
            $month = date('m', strtotime($mes));
            $year = date('Y', strtotime($mes));
            $dataInicial = "$year-$month-01";

            list($year, $month, $day) = explode('-', $dataInicial);
            $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));
            $dataFormatada = "$year-$month-$lastDay";

            $dataFinal = date('Y-m-d', strtotime($dataFormatada));
        }

        $recusaTecnicos = to_RecusaTecnicosManutencao($dataInicial, $dataFinal);
        $recusaTecnicos['anoMes'] = "$year-$month";


        $contagemRecusa = array(
            'dataIndisponivel' => 0,
            'pontoFixo' => 0,
            'operacao' => 0,
            'kmMuitoLonge' => 0,
            'erro' => 0,
            'localRisco' => 0,
            'atestado' => 0,
            'naoAtendeOmnilink' => 0,
            'motivoNaoInformado' => 0,
            'totalRecusa' => 0,
        );

        if ($recusaTecnicos['status'] == '200') {
            $result = $recusaTecnicos['dados'];

            foreach ($result as $recusaTecnico) {
                $contagemRecusa['dataIndisponivel'] += $recusaTecnico['dataIndisponivel'];
                $contagemRecusa['pontoFixo'] += $recusaTecnico['pontoFixo'];
                $contagemRecusa['operacao'] += $recusaTecnico['operacao'];
                $contagemRecusa['kmMuitoLonge'] += $recusaTecnico['kmMuitoLonge'];
                $contagemRecusa['erro'] += $recusaTecnico['erro'];
                $contagemRecusa['localRisco'] += $recusaTecnico['localRisco'];
                $contagemRecusa['atestado'] += $recusaTecnico['atestado'];
                $contagemRecusa['naoAtendeOmnilink'] += $recusaTecnico['naoAtendeOmnilink'];
                $contagemRecusa['motivoNaoInformado'] += $recusaTecnico['motivoNaoInformado'];
                $contagemRecusa['totalRecusa'] += $recusaTecnico['totalRecusa'];
            }
        }

        echo json_encode(array(
            'status' => $recusaTecnicos['status'],
            'data' => $contagemRecusa,
            'mes' => $recusaTecnicos['anoMes']
        ));
    }

    public function listarDadosMaiorRecusaManutencaoTecnicos()
    {
        $mes = $this->input->post('month');

        //Default
        $currentDate = date('Y-m');

        $month = date('m', strtotime($currentDate));
        $year = date('Y', strtotime($currentDate));
        $dataInicial = "$year-$month-01";

        list($year, $month, $day) = explode('-', $dataInicial);
        $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));

        $dataFinal = date('Y-m-d', strtotime("$year-$month-$lastDay"));

        if ($mes  !== '') {
            $month = date('m', strtotime($mes));
            $year = date('Y', strtotime($mes));
            $dataInicial = "$year-$month-01";

            list($year, $month, $day) = explode('-', $dataInicial);
            $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));
            $dataFormatada = "$year-$month-$lastDay";

            $dataFinal = date('Y-m-d', strtotime($dataFormatada));
        }

        $recusaTecnicos = to_RecusaTecnicosManutencao($dataInicial, $dataFinal);
        $recusaTecnicos['dados'] = array_slice($recusaTecnicos["dados"], 0, 10);
        $recusaTecnicos['anoMes'] = "$year-$month";

        $contagemRecusa = array();

        if ($recusaTecnicos['status'] == '200') {
            $result = $recusaTecnicos['dados'];

            foreach ($result as $recusaTecnico) {
                $contagemRecusa[] = array(
                    'nomeTecnico' => $recusaTecnico['nomeTecnico'],
                    'totalRecusa' => $recusaTecnico['totalRecusa']
                );
            }
        }

        echo json_encode(array(
            'status' => $recusaTecnicos['status'],
            'data' => $contagemRecusa,
            'mes' => $recusaTecnicos['anoMes']
        ));
    }

    public function listarDadosMenorRecusaManutencaoTecnicos()
    {
        $mes = $this->input->post('month');

        //Default
        $currentDate = date('Y-m');

        $month = date('m', strtotime($currentDate));
        $year = date('Y', strtotime($currentDate));
        $dataInicial = "$year-$month-01";

        list($year, $month, $day) = explode('-', $dataInicial);
        $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));

        $dataFinal = date('Y-m-d', strtotime("$year-$month-$lastDay"));

        if ($mes  !== '') {
            $month = date('m', strtotime($mes));
            $year = date('Y', strtotime($mes));
            $dataInicial = "$year-$month-01";

            list($year, $month, $day) = explode('-', $dataInicial);
            $lastDay = date("t", mktime(0, 0, 0, $month, '01', $year));
            $dataFormatada = "$year-$month-$lastDay";

            $dataFinal = date('Y-m-d', strtotime($dataFormatada));
        }

        $recusaTecnicos = to_RecusaTecnicosManutencao($dataInicial, $dataFinal);
        $recusaTecnicos['dados'] = array_slice($recusaTecnicos["dados"], -10, 10);
        $recusaTecnicos['anoMes'] = "$year-$month";

        $contagemRecusa = array();

        if ($recusaTecnicos['status'] == '200') {
            $result = $recusaTecnicos['dados'];

            foreach ($result as $recusaTecnico) {
                $contagemRecusa[] = array(
                    'nomeTecnico' => $recusaTecnico['nomeTecnico'],
                    'totalRecusa' => $recusaTecnico['totalRecusa']
                );
            }
        }

        echo json_encode(array(
            'status' => $recusaTecnicos['status'],
            'data' => $contagemRecusa,
            'mes' => $recusaTecnicos['anoMes']
        ));
    }

    public function listarUltimosCemAgendamentosManutencao()
    {

        $retorno = to_listarUltimosCemAgendamentosManutencao();

        echo json_encode($retorno);
    }

    public function listarAgendamentoManutencaoByPeriod()
    {
        $data = $this->input->post('searchOptions');
        $id_conversation = $data['id_conversation'];
        $status = $data['status'];
        $dataInicial =  $data['dataInicial'];
        $dataFinal =  $data['dataFinal'];
        $tresMeses = new DateInterval('P3M');
        $tresMeses->invert = 1;

        if (!$dataInicial) {
            $dataInicial = new DateTime();
            $dataInicial->add($tresMeses);
            $dataInicial = $dataInicial->format('d/m/Y');
        } else {
            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
        }

        if (!$dataFinal) {
            $dataFinal = date('d/m/Y');
        } else {
            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $agendamentos = to_listarAgendamentoManutencaoByPeriodName($dataInicial, $dataFinal);

        if ($id_conversation != null || $id_conversation != "") {
            $agendamentos = to_listarAgendamentoManutencaoByConversation($id_conversation);
        }
        if ($status != null || $status != "") {
            $agendamentos = to_listarAgendamentoManutencaoByStatus($status);
        }

        echo json_encode($agendamentos);
    }

    public function listarAgendamentoManutencaoByPeriodServerSide()
    {
        $data = $this->input->post('searchOptions');
        $startRow = $this->input->post('startRow');
        $endRow = $this->input->post('endRow');

        $id_conversation = $data['id_conversation'];
        $status = $data['status'];
        $dataInicial =  $data['dataInicial'];
        $dataFinal =  $data['dataFinal'];

        $tresMeses = new DateInterval('P3M');
        $tresMeses->invert = 1;

        if (!$dataInicial) {
            $dataInicial = new DateTime();
            $dataInicial->add($tresMeses);
            $dataInicial = $dataInicial->format('d/m/Y');
        } else {
            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
        }

        if (!$dataFinal) {
            $dataFinal = date('d/m/Y');
        } else {
            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $agendamentos = to_listarAgendamentoManutencaoByPeriodNamePaginated($dataInicial, $dataFinal, $startRow, $endRow);

        if ($id_conversation != null || $id_conversation != "") {
            $agendamentos = to_listarAgendamentoManutencaoByConversationPaginated($id_conversation, $startRow, $endRow);
        }
        if ($status != null || $status != "") {
            $agendamentos = to_listarAgendamentoManutencaoByStatusPaginated($status, $startRow, $endRow);
        }

        if ($agendamentos['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $agendamentos['dados']['listaConversationNameDTO'],
                "lastRow" => $agendamentos['dados']['qtdTotalEventos']
            ));
        } else if ($agendamentos['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => $agendamentos['dados']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $agendamentos['dados']['mensagem'],
            ));
        }
    }

    public function pegarAgendamentoManutencao()
    {
        $idConversation =  $this->input->post('idConversation');
        $agendamentos = to_buscarAgendamentoManutencao($idConversation);

        echo json_encode($agendamentos);
    }

    public function pegarConversationManutencao()
    {
        $idConversation =  $this->input->post('idConversation');
        $agenda = to_buscarConversationManutencao($idConversation);

        echo json_encode($agenda);
    }

    public function listarDadosDashboardAgendamentoManutencao()
    {
        $input = $this->input->post('searchOptions');
        $mes = isset($input['mes']) ? $input['mes'] : null;
        $ano = isset($input['ano']) ?  $input['ano'] : null;
        $periodo = isset($input['periodo']) ? $input['periodo'] : null;
        $dataInicial = isset($input['dataInicialDashboard']) ? $input['dataInicialDashboard'] : null;
        $dataFinal = isset($input['dataFinalDashboard']) ? $input['dataFinalDashboard'] : null;
        $tresMeses = new DateInterval('P3M');
        $tresMeses->invert = 1;

        if (!$dataInicial) {
            $dataInicial = new DateTime();
            $dataInicial->add($tresMeses);
            $dataInicial = $dataInicial->format('d/m/Y');
        } else {
            $dataInicial = str_replace("-", "/", $dataInicial);
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
        }

        if (!$dataFinal) {
            $dataFinal = date('d/m/Y');
        } else {
            $dataFinal = str_replace("-", "/", $dataFinal);
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        if ($mes) {
            $month = date('m', strtotime($mes));
            $year = date('Y', strtotime($mes));
            $dataInicial = "01/$month/$year";

            list($day, $month, $year) = explode('/', $dataInicial);
            $dataFormatada = "$year-$month-$day";

            $dataFinal = date('d/m/Y', strtotime('last day of this month', strtotime($dataFormatada)));
        } elseif ($ano) {
            $dataInicial = "01/01/$ano";
            $dataFinal = "31/12/$ano";
        } elseif ($periodo) {
            switch ($periodo) {
                case '7days':
                    $dataInicial = date('d/m/Y', strtotime('-7 days'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '1mes':
                    $dataInicial = date('d/m/Y', strtotime('first day of -1 month'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '3mes':
                    $dataInicial = date('d/m/Y', strtotime('-3 months'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '6mes':
                    $dataInicial = date('d/m/Y', strtotime('-6 months'));
                    $dataFinal = date('d/m/Y');
                    break;
                case '12mes':
                    $dataInicial = date('d/m/Y', strtotime('-1 year'));
                    $dataFinal = date('d/m/Y');
                    break;
            }
        }

        $agendamentosManutencao = to_listarAgendamentosManutencaoDashboard($dataInicial, $dataFinal);
        $contagemStatusPorMes = array();

        if ($agendamentosManutencao['status'] == '200') {
            $result = $agendamentosManutencao['dados'];

            $contagemStatusPorMes = array();

            foreach ($result as $agendamento) {
                $status = $agendamento['status'];
                $dataAgendamento = new DateTime($agendamento['createdAt']);
                $mesAno = $dataAgendamento->format('m/Y');

                if (!isset($contagemStatusPorMes[$mesAno])) {
                    $contagemStatusPorMes[$mesAno] = array(
                        'NAO_AGENDADO' => 0,
                        'AGENDADO' => 0,
                        'ATENDENTE' => 0,
                        'AGUARDANDO_MANTENEDOR' => 0,
                        'AGENDADO_ATENDENTE' => 0,
                        'CANCELADO_AUSENTE' => 0,
                        'EM_ATENDIMENTO' => 0,
                        'CONCLUIDO_FINALIZADO' => 0,
                        'CANCELADO_TECNICO' => 0,
                    );
                }

                $contagemStatusPorMes[$mesAno][$status]++;
            }
        }

        echo json_encode($contagemStatusPorMes);
    }

    public function alterarStatusAgendamentoManutencao()
    {
        $idLinha =  $this->input->post('idLinha');
        $status =  $this->input->post('status');
        $retorno = to_alterarStatusAgendamentoManutencao($idLinha, $status);

        echo json_encode($retorno);
    }

    public function listarRelatorioDetalhadoServerSide()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');

        $startRow++;

        $searchOptionsRaw = $this->input->post('searchOptions', TRUE);
        $searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

        $tecnicoId = isset($searchOptions['nomeTecnico']) ? urlencode($searchOptions['nomeTecnico']) : null;
        $dataInicial = isset($searchOptions['dataInicial']) && $searchOptions['dataInicial'] != "" ? $searchOptions['dataInicial'] : null;
        $dataFinal = isset($searchOptions['dataFinal']) && $searchOptions['dataFinal'] != "" ? $searchOptions['dataFinal'] : null;

        if (isset($dataInicial) && isset($dataFinal)) {
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $agendamentos = to_listarRelatorioDetalhadoPaginated($dataInicial, $dataFinal, $startRow, $endRow, $tecnicoId);

        if ($agendamentos['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $agendamentos['dados']['rows'],
                "lastRow" => $agendamentos['dados']['lastRow']
            ));
        } else if ($agendamentos['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Cliente ou placa não encontrados!",
            ));
        }
        else {
            echo json_encode(array(
                "success" => false,
                "message" => $agendamentos['dados']['mensagem'],
            ));
        }
    }

    public function generateServerSideReportInstallationDetailed() {
        $postDataJSON = file_get_contents('php://input');
        $postData = json_decode($postDataJSON, true);
        $searchOptions = $postData['searchOptions'];
        $tipoArquivo = $postData['type'];
    
        $dataInicial = isset($searchOptions['dataInicial']) ? date('d/m/Y', strtotime($searchOptions['dataInicial'])) : (new DateTime())->sub(new DateInterval('P5M'))->format('d/m/Y');
        $dataFinal = isset($searchOptions['dataFinal']) ? date('d/m/Y', strtotime($searchOptions['dataFinal'])) : date('d/m/Y');
        $nomeTecnico = isset($searchOptions['nomeTecnico']) ? urlencode($searchOptions['nomeTecnico']) : null;
    
        if ($tipoArquivo !== 'pdf' && $tipoArquivo !== 'xlsx') {
            header("HTTP/1.1 400 Bad Request");
            echo "Tipo de arquivo não suportado.";
            exit();
        }
    
        downloadReportFileInstallationDetailed($tipoArquivo, $dataInicial, $dataFinal, $nomeTecnico);
    }

    public function listarRelatorioManutencaoDetalhadoServerSide()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');

        $startRow++;

        $searchOptionsRaw = $this->input->post('searchOptions', TRUE);
        $searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

        $tecnicoId = isset($searchOptions['nomeTecnico']) ? urlencode($searchOptions['nomeTecnico']) : null;
        $dataInicial = isset($searchOptions['dataInicial']) && $searchOptions['dataInicial'] != "" ? $searchOptions['dataInicial'] : null;
        $dataFinal = isset($searchOptions['dataFinal']) && $searchOptions['dataFinal'] != "" ? $searchOptions['dataFinal'] : null;

        if (isset($dataInicial) && isset($dataFinal)) {
            $dataInicial = date('d/m/Y', strtotime($dataInicial));
            $dataFinal = date('d/m/Y', strtotime($dataFinal));
        }

        $agendamentos = to_listarRelatorioManutencaoDetalhadoPaginated($dataInicial, $dataFinal, $startRow, $endRow, $tecnicoId);

        if ($agendamentos['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "rows" => $agendamentos['dados']['rows'],
                "lastRow" => $agendamentos['dados']['lastRow']
            ));
        } else if ($agendamentos['status'] == '404') {
            echo json_encode(array(
                "success" => false,
                "message" => "Cliente ou placa não encontrados!",
            ));
        }
        else {
            echo json_encode(array(
                "success" => false,
                "message" => $agendamentos['dados']['mensagem'],
            ));
        }
    }

    public function generateServerSideReportMaintenanceDetailed() {
        $postDataJSON = file_get_contents('php://input');
        $postData = json_decode($postDataJSON, true);
        $searchOptions = $postData['searchOptions'];
        $tipoArquivo = $postData['type'];
    
        $dataInicial = isset($searchOptions['dataInicial']) ? date('d/m/Y', strtotime($searchOptions['dataInicial'])) : (new DateTime())->sub(new DateInterval('P5M'))->format('d/m/Y');
        $dataFinal = isset($searchOptions['dataFinal']) ? date('d/m/Y', strtotime($searchOptions['dataFinal'])) : date('d/m/Y');
        $nomeTecnico = isset($searchOptions['nomeTecnico']) ? urlencode($searchOptions['nomeTecnico']) : null;
    
        if ($tipoArquivo !== 'pdf' && $tipoArquivo !== 'xlsx') {
            header("HTTP/1.1 400 Bad Request");
            echo "Tipo de arquivo não suportado.";
            exit();
        }
    
        downloadReportFileMaintenanceDetailed($tipoArquivo, $dataInicial, $dataFinal, $nomeTecnico);
    }
}
