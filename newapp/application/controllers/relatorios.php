<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Relatorios extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('cliente');
        $this->load->model('veiculo');
        $this->load->model('relatorio');
        $this->load->model('envio_fatura');
        $this->load->model('usuario');
        $this->load->model('contrato');
        $this->load->model('fatura');
        $this->load->model('equipamento');
        $this->load->model('mapa_calor');
        $this->load->helper('util_helper');
    }

    public function index()
    {


        $dados['titulo'] = 'Show Tecnologia';
        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/index');
        $this->load->view('fix/footer');
    }

    public function rel_tickets()
    {
        $relatorio = array('resultados' => array(), 'concluidos' => 0, 'total' => 0);
        $erro = false;

        if ($this->input->post()) {
            $tipo_data = $this->input->post('tipo_data');
            $dt_ini = $this->input->post('data_ini') ? ($this->input->post('data_ini')) : '';
            $dt_fim = $this->input->post('data_fim') ? ($this->input->post('data_fim')) : '';
            $mes_ano = $this->input->post('mes_ano') ? $this->input->post('mes_ano') : '';

            if ($tipo_data == '0') {

                if (!$dt_ini || !$dt_fim)
                    $erro = 'Verifique as datas e tente novamente.';
                elseif ($dt_fim < $dt_ini)
                    $erro = 'A data fim não pode ser menor que a data Inicial.';
                else
                    $relatorio = (get_buscarRelatoriosTickets(data_for_humans($dt_ini), data_for_humans($dt_fim), $mes_ano));
                if (isset($relatorio['status']) && $relatorio['status'] == 404) {
                    $erro = 'Não foi possível encontrar tickets nesse período.';
                } else if (isset($relatorio['status']) && ($relatorio['status'] == 500 || $relatorio['status'] == 400)) {
                    $erro = 'Não foi possível buscar os tickets. Tente novamente.';
                }
            } else {

                if (!$mes_ano)
                    $erro = 'Verifique o mês e o ano e tente novamente.';
                else
                    $relatorio = (get_buscarRelatoriosTickets($dt_ini, $dt_fim, $mes_ano));
                if (isset($relatorio['status']) && $relatorio['status'] == 404) {
                    $erro = 'Não foi possível encontrar tickets nesse período.';
                } else if (isset($relatorio['status']) && ($relatorio['status'] == 500 || $relatorio['status'] == 400)) {
                    $erro = 'Não foi possível buscar os tickets. Tente novamente.';
                }
            }
        }

        $dados = array(
            'relatorio' => $relatorio['resultados'],
            'concluidos' => $relatorio['concluidos'],
            'total' => $relatorio['total'],
            'erro' => $erro,
            'titulo' => 'Relatório de Tickets'
        );
        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/tickets/rel_tickets');
        $this->load->view('fix/footer');
    }

    public function ajaxRelFaturas()
    {
        if ($this->input->post()) {
            $dt_ini = data_for_unix($this->input->post('dataInicio'));
            $dt_fim = data_for_unix($this->input->post('dataFim'));
            $n_cliente = $this->input->post('cliente');
            $uf = $this->input->post('uf');
            $status = $this->input->post('status_fatura');
            $orgao = $this->input->post('orgao');
            $dados['agrupar_cliente'] = $this->input->post('agrupar');
            $empresa = $this->input->post('emp');
            $vendedor = $this->input->post('vendedor');
            $tipoPessoa = $this->input->post('tipo_pessoa');
            $tipoAtividade = $this->input->post('tipoAtividade');
            $informacoes = $this->input->post('informacoes');
            $status_financeiro_cliente = $this->input->post('status_financeiro_cliente');


            if (!$dt_ini || !$dt_fim) {
                echo json_encode(array('status' => 'ERRO', 'msg' => 'Data Inicio e Data Fim são obrigatórios.'));
            } elseif (!$n_cliente && $empresa == 'todos' && $vendedor == 'todos' && !$orgao && !$uf) {
                echo json_encode(array('status' => 'ERRO', 'msg' => 'Não é permitido gerar esse relatório sem nenhum filtro. (Orgão, Vendedor, Prestadora ou Estado)'));
            } elseif ($dt_ini > $dt_fim) {
                echo json_encode(array('status' => 'ERRO', 'msg' => 'Data Início não pode ser maior que Data Fim.'));
            } else {
                // Cria retorno default //
                $data = array();
                $dataag = array();
                $total_fatura = 0.0;
                $total_taxa = 0.0;
                $total_pago = 0.0;

                // Gera dados de retorno //
                $retorno = $this->relatorio->fatura($dt_ini, $dt_fim, $n_cliente, $status, $empresa, $uf, $vendedor, $orgao, $tipoPessoa, $tipoAtividade, $informacoes, $status_financeiro_cliente);
                if ($retorno && count($retorno) > 0) {
                    foreach ($retorno as $fatura) {
                        switch ($fatura->informacoes) {
                            case 'NORIO':
                                $prestadora = 'SIGA-ME RASTREAMENTO';
                                break;
                            case 'SIMM2M':
                                $prestadora = 'SIMM2M';
                                break;
                            case 'OMNILINK':
                                $prestadora = 'OMNILINK TECNOLOGIA';
                                break;
                            case 'EUA':
                                $prestadora = 'SHOW TECHNOLOGY';
                                break;
                            default:
                                $prestadora = 'SHOW TECNOLOGIA';
                                break;
                        }

                        switch ($fatura->status) {
                            case '0':
                                $status_fatura = 'Aberta';
                                break;
                            case '1':
                                $status_fatura = 'Paga';
                                break;
                            case '2':
                                $status_fatura = 'Aberta';
                                break;
                            case '3':
                                $status_fatura = 'Cancelada';
                                break;
                        }

                        switch ($fatura->atividade) {
                            case '0':
                                $atividade_fatura = 'Outros';
                                break;
                            case '1':
                                $atividade_fatura = 'Atividade de Monitoramento';
                                break;
                            case '2':
                                $atividade_fatura = 'Serviços Técnicos';
                                break;
                            case '3':
                                $atividade_fatura = 'Aluguel de Máquinas e Equipamento';
                                break;
                            case '4':
                                $atividade_fatura = 'Suporte técnico, manutenção e outros serviços em tecnologia da informação';
                                break;
                            case '5':
                                $atividade_fatura = 'Desenvolvimento e licenciamento de programas de computador customizáveis';
                                break;
                            case '6':
                                $atividade_fatura = 'Serviços combinados de escritório e apoio administrativo';
                                break;
                            default:
                                $atividade_fatura = 'Valor Inválido';
                                break;
                        }

                        // Monta Tbody do datatable //
                        $data[] = array(
                            $fatura->Id,
                            $fatura->nome,
                            (!is_null($fatura->cnpj) ? $fatura->cnpj : $fatura->cpf),
                            ($fatura->status_cliente == '1' ? 'Ativo' : 'Inativo'),
                            ($fatura->orgao == 'privado' ? 'Privado' : 'Público'),
                            $prestadora,
                            data_for_humans($fatura->data_vencimento),
                            data_for_humans($fatura->data_emissao),
                            $fatura->valor_total,
                            $fatura->total_taxa,
                            $fatura->nota_fiscal,
                            str_replace('/', '-', $fatura->mes_referencia),
                            data_for_humans($fatura->periodo_inicial),
                            data_for_humans($fatura->periodo_final),
                            $fatura->data_pagto != '' ? data_for_humans($fatura->data_pagto) : '',
                            $fatura->valor_pago != '' ? $fatura->valor_pago : '0,00',
                            $fatura->tipo_pag,
                            isset($fatura->fim_contrato) ? $fatura->fim_contrato : 'N/A',
                            $fatura->generator == 1 ? 'Automática' : 'Manual',
                            $status_fatura,
                            $atividade_fatura
                        );

                        $dataag[] = array(
                            "id" => $fatura->Id,
                            "nome" => $fatura->nome,
                            "cnpj_cpf" => (!is_null($fatura->cnpj) ? $fatura->cnpj : $fatura->cpf),
                            "status_cliente" => ($fatura->status_cliente == '1' ? 'Ativo' : 'Inativo'),
                            "orgao" => ($fatura->orgao == 'privado' ? 'Privado' : 'Público'),
                            "prestadora" => $prestadora,
                            "data_vencimento" => data_for_humans($fatura->data_vencimento),
                            "data_emissao" => data_for_humans($fatura->data_emissao),
                            "valor_total" => $fatura->valor_total,
                            "total_taxa" => $fatura->total_taxa,
                            "nota_fiscal" => $fatura->nota_fiscal,
                            "mes_referencia" => str_replace('/', '-', $fatura->mes_referencia),
                            "periodo_inicial" => data_for_humans($fatura->periodo_inicial),
                            "periodo_final" => data_for_humans($fatura->periodo_final),
                            "data_pagto" => $fatura->data_pagto != '' ? data_for_humans($fatura->data_pagto) : '',
                            "valor_pago" => $fatura->valor_pago != '' ? $fatura->valor_pago : '0,00',
                            "tipo_pag" => $fatura->tipo_pag,
                            "fim_contrato" => isset($fatura->fim_contrato) ? $fatura->fim_contrato : 'N/A',
                            "generator" => $fatura->generator == 1 ? 'Automática' : 'Manual',
                            "status_fatura" => $status_fatura,
                            "atividade_fatura" => $atividade_fatura
                        );


                        // Soma valores de retorno //
                        $total_fatura += $fatura->valor_total;
                        $total_taxa += $fatura->total_taxa;
                        $total_pago += $fatura->valor_pago;
                    }
                }

                echo json_encode(array('status' => 'OK', 'dataag' => $dataag , 'tbody' => $data, 'tfooter' => array('total_fatura' => $total_fatura, 'total_taxa' => $total_taxa, 'total_pago' => $total_pago, 'total_liquido' => $total_pago - $total_taxa)));
            }
        } else {
            echo json_encode(array('status' => 'ERRO', 'msg' => 'Nenhum parâmetro enviado.'));
        }
    }

    // public function faturas_relatorio_antigo()
    // {
    //     $this->auth->is_allowed('rel_financeiro_faturas');
    //     $dados['vendedores'] = $this->usuario->all();
    //     $dados['titulo'] = 'Faturas';
    //     $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/faturas_relatorio'));
    //     $this->load->view('fix/header_NS', $dados);
    //     $this->load->view('relatorios/faturas/relatorio_fatura');
    //     $this->load->view('fix/footer_NS');
    // }

    public function faturas_relatorio()
    {
        $this->auth->is_allowed('rel_financeiro_faturas');
        $dados['vendedores'] = $this->usuario->all();
        $dados['titulo'] = 'Faturas';

        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/faturas_relatorio'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/faturas/relatorio_faturanv');
        $this->load->view('fix/footer_NS');
    }


    /*
    * CARREGA VIEW DE RELATÓRIO >> DISPONIBILIDADE DE VEICULOS
    */
    public function veiculosDisponiveis()
    {
        $dados['titulo'] = lang('veiculos_disponiveis');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/veiculosDisponiveis'));
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/veiculos_diponiveis/relatorio_veiculos_disponiveis');
        $this->load->view('fix/footer_NS');
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> CONTABILIZAR POR TEMPO DE CONTRATO
    */
    public function veiculos_tempo_contrato()
    {
        $this->auth->is_allowed('rel_veic_tempo_contrato');

        $dados['titulo'] = lang('relatorio_tempo_contrato');
        $dados['load'] = array('ag-grid', 'select2', 'mask');

        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/relatorio_tempo_contrato'));

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/tempo_contrato/index');
        $this->load->view('fix/footer_NS');
    }

    public function buscarDadosTempoContrato()
    {
        $baseCalculo = $this->input->post('baseCalculo');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');
        $cliente = $this->input->post('cliente');
        $status = $this->input->post('status');

        $POSTFIELDS = array(
            'baseCalculo' => $baseCalculo,
            'dataInicio' => data_for_humans($dataInicial),
            'dataFim' => data_for_humans($dataFinal),
            'idCliente' => $cliente,
            'status' => $status
        );

        $dados = get_dadosTempoContrato($POSTFIELDS);

        echo json_encode($dados);
    }

    public function relatorio_tempo_contrato()
    {
        $dados['titulo'] = lang('relatorio') . ' - ' . lang('proporcional_tempo_contrato');
        $dados['load'] = array('buttons_html5', 'datapicket', 'datatable_responsive');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/veiculos_tempo_contrato'));
        $this->load->view('fix/header-new', $dados);
        $this->load->view('relatorios/proporcional_tempo_contrato');
        $this->load->view('fix/footer_new');
    }

    /*
    * CARREGA DADOS DA TABELA >> CONTABILIZAR POR TEMPO DE CONTRATO
    */
    public function ajax_veiculos_tempo_contrato()
    {
        $base_calculo = $this->input->get('base_calculo');
        $di = data_for_unix($this->input->get('di'));
        $df = data_for_unix($this->input->get('df'));
        $id_cliente = $this->input->get('id_cliente');
        $statusVeiculo = $this->input->get('statusVeiculo');
        $table = array(); # Data DATATABLE


        if ($di && $df) { # Se for passado os parâmetros
            $body = [
                'inicio'     => $di . ' 00:00:00',
                'fim'        => $df . ' 23:59:59',
                'idCliente'  => $id_cliente,
                'baseCalculo' => $base_calculo,
                'formato' => 'tabela',
                'statusVeiculo' => $statusVeiculo
            ];

            $veiculos = json_decode(from_relatorios_api($body, "relatorios/custoContratoAdm"), true);
            if ($veiculos['status'] == 1) {
                $tabela = $veiculos['dados']['tabela'];
                if (isset($veiculos['dados']) && $tabela) {
                    echo json_encode(array('status' => 'OK', 'tabela' => $tabela, 'total_veic' => $tabela[count($tabela) - 1]['linha'], 'valor_total' => 'R$ ' . number_format($veiculos['dados']['total'], 2, ',', '.')));
                } else {
                    echo json_encode(array('status' => 'ERRO', 'msg' => lang('veic_nao_encontrado_periodo_tempo')));
                }
            } else {
                echo json_encode(array('status' => 'ERRO', 'msg' => $veiculos['message']));
            }
        } else {
            echo json_encode(array('status' => 'ERRO', 'msg' => lang('datas_obrigatorias')));
        }
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> FATURAS GERADAS ENTRE INTERVALO DE TEMPO
    */
    public function faturas_geradas()
    {
        $dados['titulo'] = lang('relatorio') . ' - ' . lang('faturas_geradas');
        $dados['load'] = array('buttons_html5');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/faturas_geradas'));
        $this->load->view('fix/header-new', $dados);
        $this->load->view('relatorios/faturas/faturas_geradas');
        $this->load->view('fix/footer_new');
    }

    /*
    * CARREGA DADOS DA TABELA >> CONTABILIZAR POR TEMPO DE CONTRATO
    */
    public function ajax_load_faturas_geradas()
    {
        $di = $this->input->get('di');
        $df = $this->input->get('df');
        $gerar_por = $this->input->get('gerar_por');
        $id_cliente = $this->input->get('id_cliente');
        $situacao = $this->input->get('situacao');
        $orgao = $this->input->get('orgao');
        $valor_total = 0.0;
        $status = array(0, 1, 2);
        $table = array();

        if ($di && $df) { # Se for passado os parâmetros

            $where = array('f.' . $gerar_por . ' >=' => $di, 'f.' . $gerar_por . ' <=' => $df);
            if ($orgao != 'todos') $where += array('cli.orgao' => $orgao);
            if ($id_cliente)  $where += array('f.id_cliente' => $id_cliente);
            if ($situacao != 'todos') {
                $status = $situacao == 2 ? array(2) : array(0, 1);
            }

            $faturas = $this->fatura->getFaturasGeradas('cli.nome as cliente, cli.informacoes as prestadora, cli.orgao, f.Id, f.data_emissao, f.data_vencimento, f.valor_total, f.status', $where, $status);
            if ($faturas) {
                foreach ($faturas as $fatura) {
                    $orgao_cliente = $fatura->orgao == 'publico' ? lang('publico') : lang('privado');
                    $table[] = array(
                        'id' => $fatura->Id,
                        'cliente' => $fatura->cliente,
                        'prestadora' => getNomePrestadora($fatura->prestadora),
                        'orgao' => $orgao_cliente,
                        'data_emissao' => $fatura->data_emissao,
                        'data_vencimento' => $fatura->data_vencimento,
                        'valor' => $fatura->valor_total,
                        'status' => status_fatura($fatura->status, $fatura->data_vencimento)
                    );

                    $valor_total += $fatura->valor_total;
                }
                echo json_encode(array('success' => true, 'tabela' => $table, 'total_faturas' => count($faturas), 'valor_total' => number_format($valor_total, 2, ',', '.')));
            } else {
                echo json_encode(array('success' => false, 'msg' => lang('nenhuma_fatura_encontrada_periodo')));
            }
        } else {
            echo json_encode(array('success' => false, 'msg' => lang('datas_obrigatorias')));
        }
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> CALCULO DE DISPONIBILIDADE DE VEICULOS
    */
    // public function calculoVeiculosDisponiveis($di, $df, $cliente, $filtro=false, $veic_desatualizado=false, $veic_sem_serial=false, $veic_sem_contrato=false, $valor_diario=false, $remover_filtros=false) {
    public function calculoVeiculosDisponiveis()
    {
        $filtro = false;
        $di = $this->input->post('di');
        $df = $this->input->post('df');
        if ($this->input->post('id_cliente')) {
            $cliente = $this->input->post('id_cliente');
        }
        if ($this->input->post('filtro')) {
            $filtro = $this->input->post('filtro');
        }

        if (!$this->input->post('resumo')) {
            $opcoes = $this->input->post('opcoes');

            if ($di && $df) {
                $dados = $this->veiculo->veiculos_disponiveis($di, $df, $opcoes, $cliente, $filtro);

                $valor_total = 0;
                $veiculos_disponiveis = array();
                foreach ($dados as $value) {
                    $ult_comun_part1 = data_for_humans(substr($value->ultima_comunicacao, 0, 10));
                    $ult_comun_part2 = substr($value->ultima_comunicacao, 11, 19);
                    $ult_comunicacao = $ult_comun_part1 . ' ' . $ult_comun_part2;
                    $veiculos_disponiveis[] = array(
                        "data" => substr($value->datahora, 0, 10),
                        "ultimaConfig" => $ult_comunicacao,
                        "placa" => $value->placa,
                        "serial" => $value->serial,
                        "cliente" => $value->nome,
                        "contrato" => $value->id_contrato,
                        "valorDiario" => number_format($value->valor_diario, 2, ',', '.'),
                        "valorMensal" => number_format($value->valor_mensal, 2, ',', '.'),

                        // '<a onclick="getUsers('.$link.')" data-toggle="modal" data-target="#usuarios" class="btn btn-primary dropdown-toggle"><i class="fa fa-users"></i></a>'
                    );
                    $valor_total += $value->valor_mensal;
                }
            } else { # Se não vir parâmetros
                echo json_encode(array('status' => 'ERRO'));
            }
            echo json_encode(array('status' => 'OK', 'veiculos_disponiveis' => $veiculos_disponiveis, 'valor_total' => number_format($valor_total, 2, ',', '.')));
            exit();
        } else {
            //     //$placa = $this->input->get('opcoes');
            //
            //     if($di && $df){
            //         $dados = $this->veiculo->resumo_disponiveis($di, $df, $cliente );
            //         $valor_total = 0;
            //         $veiculos_disponiveis = array();
            //         foreach ($dados as $key => $value) {
            //             $ult_comun_part1 = data_for_humans(substr($value->ultima_comunicacao, 0, 10));
            //             $ult_comun_part2 = substr($value->ultima_comunicacao, 11, 19);
            //             $ult_comunicacao = $ult_comun_part1.' '.$ult_comun_part2;
            //             $veiculos_disponiveis[$key] = array(
            //                 data_for_humans(substr($value->datahora, 0, 10)),
            //                 $ult_comunicacao,
            //                 $value->placa,
            //                 $value->serial,
            //                 $value->nome,
            //                 $value->id_contrato,
            //                 number_format($value->valor_diario, 2, ',', '.'),
            //                 number_format($value->valor_mensal, 2, ',', '.'),
            //                 "<button onclick='getUsers($value->id)' data-toggle='modal' data-target='#usuarios' class='btn btn-primary dropdown-toggle'><i class='fa fa-users'></i></button>"
            //                 // '<a onclick="getUsers('.$link.')" data-toggle="modal" data-target="#usuarios" class="btn btn-primary dropdown-toggle"><i class="fa fa-users"></i></a>'
            //             );
            //             $valor_total += $value->valor_mensal;
            //         }
            //
            //     } else { # Se não vir parâmetros
            //         echo json_encode(array('status' => 'ERRO'));
            //     }
            //     echo json_encode(array('status' => 'OK', 'veiculos_disponiveis' => $veiculos_disponiveis, 'valor_total' => number_format($valor_total, 2, ',', '.')));
        }
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> RESUMO DE DISPONIBILIDADE DE VEICULOS
    */
    public function resumoVeiculosDisponiveis()
    {
        $dados['titulo'] = lang('relatorio') . ' - ' . lang('resumo_veiculos_disponiveis');
        $dados['load'] = array('buttons_html5', 'datapicket');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/resumoVeiculosDisponiveis'));
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/veiculos_diponiveis/resumo_relatorio_veiculos_disponiveis');
        $this->load->view('fix/footer_NS');
    }

    /*
    * GERA RELATÓRIO >> RESUMO DE DISPONIBILIDADE DE VEÍCULOS
    */
    public function loadRelResumoDisp()
    {
        $valor_total = 0; # Default Valor Total
        $qtd_veiculos = 0; # Default quantidade de Veiculos
        $table = array(); # Default Data DATATABLE

        if ($this->input->get('di') && $this->input->get('df')) { # Se for passado os parâmetros
            $veiculos_disponiveis = $this->veiculo->resumo_disponiveis(
                data_for_unix($this->input->get('di')),
                data_for_unix($this->input->get('df')),
                $this->input->get('id_cliente')
            );
            $data_inicial = $this->input->get('di');
            $data_final = $this->input->get('df');

            if (isset($veiculos_disponiveis['result']) && count($veiculos_disponiveis['result']) > 0) {
                $count = 0;
                foreach ($veiculos_disponiveis['result'] as $key => $disponivel) {
                    $valor_total += $disponivel->valor_total_arredondado;

                    if ($disponivel->qtd_dias > 0) {
                        $qtd_veiculos++;
                    }
                    $placa = $disponivel->placa;

                    $table[] = array(
                        'status' => $disponivel->status,
                        'count' => $count++,
                        'placa' => $placa ? $placa : '-',
                        'nomeCliente'  => $disponivel->nome ? $disponivel->nome : '-',
                        'idContrato' => $disponivel->id_contrato ? $disponivel->id_contrato : '-',
                        'qtdDias' => $disponivel->qtd_dias ? $disponivel->qtd_dias : '-',
                        'valorTotal' => $disponivel->valor_total_arredondado ?  'R$ ' . number_format($disponivel->valor_total_arredondado, 2, ',', '.') : '-',
                        'dataInicial' => $data_inicial,
                        'dataFinal' => $data_final,
                        /* '<button class="get_datas_placa btn btn_aditivos btn-primary" data-di="'.$this->input->get('di').'" data-df="'.$this->input->get('df').'" data-placa="'.$disponivel->placa.'" title="'.lang('dias_placa').'"><i class="fa fa-eye"></i></button>' */
                    );
                }
            }
        } else { # Se não vir parâmetros
            echo json_encode(array('status' => 'ERRO'));
        }

        echo json_encode(array('status' => 'OK', 'valor_total' => number_format($valor_total, 2, ',', '.'), 'table' => $table, 'qtd_veiculos' => $qtd_veiculos, 'dataInicial' => $data_inicial, 'dataFinal' => $data_final));
    }

    /*
    * LISTA CLIENTE - SELECT2 INTELIGENTE
    */
    public function listAjaxSelectClient()
    {
        $data = array('results' => array());

        if ($search = $this->input->get('term')) { # Se usuário realizar busca no select
            $clientes = $this->cliente->listarClientesFilter($search); # Filtra clientes
        } else { # Se não vir filtro
            $clientes = $this->cliente->listar(array(), 0, 50); # Lista 50 registros
        }

        if ($clientes) {
            foreach ($clientes as $key => $cliente) {
                $data['results'][] = array(
                    'id' => $cliente->id,
                    'text' => $cliente->id . ' - ' . $cliente->nome . ' (' . ($cliente->cpf ? $cliente->cpf : $cliente->cnpj) . ')'
                );
            }
        }

        echo json_encode($data);
    }

    //Gabriel Bandeira
    public function usuariosVeiculosDisponiveis()
    {
        $id = $this->input->get('id');
        echo json_encode($this->veiculo->usuariosDisponiveis($id));
    }
    //Gabriel Bandeira
    public function getDateRunCronVeiculosDisponiveis()
    {
        $di = data_for_unix($this->input->post('di'));
        $df = data_for_unix($this->input->post('df'));
        $datas = [];
        $diferenca = $this->veiculo->dateRunCronDisponiveis($di, $df);
        foreach ($diferenca as $data) {
            $datas[$data->data] = true;
        }
        $data_di = date($di);
        $data_df = date($df);
        $d_i = $data_di;
        $sem_data = [];
        while ($d_i != $data_df) {
            if (!isset($datas[$d_i])) {
                $sem_data[] = $d_i;
            }
            $d_i = date('Y-m-d', strtotime("+1 days", strtotime($d_i)));
        }
        if (!isset($datas[$data_df])) {
            $sem_data[] = $d_i;
        }
        echo json_encode($sem_data);
    }
    //Gabriel Bandeira
    public function dashboardVeiculosDisponiveis()
    {
        $dados = array();
        $dados['titulo'] = 'Dashboard Veículos Disponíveis';
        if ($this->input->get('di') && $this->input->get('df')) {
            $di = data_for_unix($this->input->get('di'));
            $df = data_for_unix($this->input->get('df'));
        } else {
            $di = date('Y-m-d', strtotime("-30 days", strtotime(date('Y-m-d'))));
            $df = date("Y-m-d");
        }
        $veiculos_disponiveis = $this->veiculo->dashboardDisponiveis($di, $df, $this->input->get('cliente'));
        $dados['veiculos_disponiveis'] = $veiculos_disponiveis['vd'];
        $dados['contratos'] = $veiculos_disponiveis['contratos'];
        $dados['contratos_dia'] = $veiculos_disponiveis['contratos_dia'];

        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/dashboardVeiculosDisponiveis'));

        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/veiculos_diponiveis/dashboard_relatorio_veiculos_disponiveis');
        $this->load->view('fix/footer');
    }

    /*
    * RETORNA OS DIAS QUE UMA PLACA/VEICULO TRABALHOU/CONSTA NA TABELA DISPONIBILIDADE_VEICULO
    */
    public function placaDiasVeiculosDisponiveis()
    {
        if ($dados = $this->input->post()) {
            $di = data_for_unix($dados['di']);
            $df = data_for_unix($dados['df']);

            $datas = $this->veiculo->datasVeiculosDisponiveis($di, $df, $dados['placa']);
            if ($datas) {
                $datasString = '';
                foreach ($datas as $data) {
                    $dia = data_for_humans(explode(' ', $data->datahora)[0]);
                    $datasString .= $dia . ' | ';
                }
                echo json_encode(array('status' => true, 'result' => rtrim($datasString, ' | ')));
            } else {
                echo json_encode(array('status' => true, 'result' => array()));
            }
        } else {
            echo json_encode(array('status' => false, 'result' => lang('data_veic_nao_enc')));
        }
    }
    //Gabriel Bandeira
    public function tabelaDashboardVeiculosDisponiveis()
    {
        $data = $this->input->get('data');
        $veiculos_disponiveis = $this->veiculo->tabelaDashboardDisponiveis($data);
        echo json_encode($veiculos_disponiveis);
    }
    public function alteracoesVeiculosDisponiveis()
    {
        $data = $this->input->get('data');
        $op = $this->input->get('op');
        $id_cliente = $this->input->get('id_cliente');
        $veiculos_disponiveis = $this->veiculo->alteracoesVeiculosDisponiveis($data, $id_cliente, $op);
        echo json_encode($veiculos_disponiveis);
    }

    public function resumo_faturas()
    {
        $this->auth->is_allowed('rel_financeiro_faturas');
        $dados['relatorio'] = array();

        if ($this->input->post()) {
            if ($this->input->post('dt_ini') && $this->input->post('dt_ini') != '/') {
                if ($this->input->post('dt_fim') && $this->input->post('dt_fim') != '/') {
                    $dt1 = '01/' . $this->input->post('dt_ini');
                    $dt2 = '31/' . $this->input->post('dt_fim');
                    $dt_ini = date('Y-m', strtotime(str_replace("/", "-", $dt1)));
                    $dt_fim = date('Y-m', strtotime(str_replace("/", "-", $dt2)));
                    $cliente = $this->input->post('cliente');

                    // GERA RELATORIO
                    if ($dt_ini <= $dt_fim) {
                        if (diff_entre_datas($dt_ini . '-01', $dt_fim . '-31', 'mes') <= 12) {
                            $aux = $this->input->post('status');
                            if ($aux == 'ativos') $status = array(1, 2);
                            else $status = array($aux);
                            // VERIFICA SE USUARIO PASSOU CLIENTE
                            if ($cliente)
                                foreach ($cliente as $key => $clie)
                                    $clientes[] = $this->cliente->busca_cliente(array('id' => $clie))[0];
                            else
                                $clientes = $this->cliente->get_IdName_clientesAll($status);

                            $dados['relatorio'] = $this->relatorio->resumo_faturas($dt_ini, $dt_fim, $clientes);
                        } else
                            $this->session->set_flashdata('erro', 'O Intervalo máximo é de 1 ano, verifique e tente novamente.');
                    } else
                        $this->session->set_flashdata('erro', 'Data Início não pode ser maior que Data Fim.');
                } else
                    $this->session->set_flashdata('erro', 'Data Final não informada, selecione e tente novamente.');
            } else
                $this->session->set_flashdata('erro', 'Data Inicial não informada, selecione e tente novamente.');
        }

        $dados['titulo'] = 'Faturas';
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/resumo_faturas'));
        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/faturas/resumo_faturas');
        $this->load->view('fix/footer');
    }

    public function faturas_enviadas()
    {

        //$this->auth->is_allowed('rel_financeiro_fatenviadas');

        grava_url(current_url());
        $where = array();

        if ($this->input->post()) {
            $dt_ini = data_for_unix($this->input->post('dt_ini'));
            $dt_fim = data_for_unix($this->input->post('dt_fim'));
            $where = array('dhcad_envio' => '');
            $this->session->set_userdata('filtro_fatura', "cad_faturas.Id = '{$filtro}'
                OR cad_clientes.nome LIKE '%{$filtro}%' " . $numero);
            $this->session->set_userdata('filtro_keyword', $this->input->post('filtro'));
        }

        $dados['filtro'] = $this->session->userdata('filtro_envio');
        $where = $this->session->userdata('filtro_fatura');



        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');


        $dados['faturas'] = $this->envio_fatura->listar_relatorio($where);
        $dados['titulo'] = 'Faturas';
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/faturas_enviadas'));
        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/faturas/lista_enviadas');
        $this->load->view('fix/footer');
    }

    public function relatorio_contratos()
    {
        $this->load->model('contrato');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/relatorio_contratos'));

        $lista_clientes = array();
        $lista_cidades = array();

        $relatorio = $this->contrato->relatorio_cliente(array('dt_ini' => '0000-00-00', 'dt_fim' => date('Y-m-d'), 'status' => array(1, 2)));

        $dados = array('msg' => '', 'titulo' => 'Relatório de Contratos', 'clientes' => json_encode($lista_clientes), 'relatorio' => $relatorio, 'cidades' => json_encode($lista_cidades));

        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/contratos/lista_contrato');
        $this->load->view('fix/footer');
    }

    /*
	* Lista clientes para select2
	*/
    function ajax_cliente()
    {
        $like = NULL;
        if ($search = $this->input->get('q'))
            $like = array('nome' => $search);

        $results = $this->cliente->listar(array(), 0, 10, 'nome', 'asc', 'nome as text, nome as id, cpf, cnpj', $like);

        if ($results) {
            foreach ($results as $key => $value) {
                $digitosCpf = preg_replace('/\D/', '', $value->cpf);
                $verificaCpf = strlen($digitosCpf);
                $documento = $verificaCpf == 11 ? $value->cpf : $value->cnpj;
                $results[$key]->text = $value->text . ' (' . $documento . ')';
            }
        }
        echo json_encode(array('results' => $results));
    }

    public function ajax_cidade()
    {
        $like = NULL;
        if ($search = $this->input->get('q'))
            $like = $this->input->get('q');

        $results = $this->cliente->get_cidades($like);

        echo json_encode(array('results' => $results));
    }

    public function ajax_contrato()
    {
        $this->load->model('contrato');
        $msg = false;
        $relatorio = array();

        if ($this->input->post()) {
            $rules_form = array(
                array('field' => 'dt_ini', 'label' => 'Data Início', 'rules' => 'required|data_for_unix'),
                array('field' => 'dt_fim', 'label' => 'Data Fim', 'rules' => 'required|data_for_unix')
            );
            $this->form_validation->set_rules($rules_form);

            if ($this->form_validation->run() === false) {
                $msg = validation_errors('<li>', '</li>');
            } else {
                $relatorio = $this->contrato->relatorio_cliente($this->input->post());
            }
        }

        $dados = array('msg' => $msg, 'relatorio' => $relatorio);

        $this->load->view('relatorios/contratos/relatorio_contrato', $dados);
    }

    public function veiculos_gestor()
    {
        $this->load->model('veiculo');
        echo $this->veiculo->total_lista_veiculos_gestor($this->input->post('id'));
    }

    public function sms()
    {

        $this->load->model('log_desatualizados', 'log_sms');
        $msg = array();
        $sms = array();

        $dados = array('titulo' => 'Relatório de SMS', 'msg' => $msg, 'relatorio' => $sms);
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/sms'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/sms/log_sms');
        $this->load->view('fix/footer_NS');

        if ($this->input->post()) {

            $busca = $this->input->post('keyword');
            $tipo = $this->input->post('tipo');
            $dt_ini = $this->input->post('dt_ini');
            $dt_fim = $this->input->post('dt_fim');

            $rules = array(
                array('field' => 'dt_ini', 'label' => 'Data Início', 'rules' => 'required'),
                array('field' => 'dt_fim', 'label' => 'Data Fim', 'rules' => 'required')
            );

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() === false) {

                $msg['success'] = false;
                $msg['msg'] = validation_errors('<li>', '</li>');
                echo json_encode($msg);
            } else {
                if ($dt_ini <= $dt_fim) {

                    $find = array(
                        'dhenvio_log >=' => $dt_ini . ' 00:00:00',
                        'dhenvio_log <=' => $dt_fim . ' 23:59:59',
                        'sms_log' => 1
                    );

                    if ($busca != '') {
                        switch ($tipo) {
                            case 'prefixo':
                                $find['prefixo'] = $busca;
                                break;
                            case 'placa':
                                $find['placa'] = $busca;
                                break;
                            case 'usuario':
                                $find['CNPJ_'] = $busca;
                                break;
                            case 'celular':
                                $find['celular_log'] = $busca;
                                break;
                        }
                    }

                    $sms = $this->log_sms->find($find);
                    echo json_encode($sms);
                    exit;
                }
            }
        }
    }

    public function tempo_logado()
    {

        $this->load->model('auth');

        $msg = false;
        $relatorio = array();

        if ($this->input->post()) {

            $rules_form = array(
                array('field' => 'dt_ini', 'label' => 'Data Início', 'rules' => 'required|data_for_unix'),
                array('field' => 'dt_fim', 'label' => 'Data Fim', 'rules' => 'required|data_for_unix')
            );

            $this->form_validation->set_rules($rules_form);

            if ($this->form_validation->run() === false) {

                $msg = validation_errors('<li>', '</li>');
            } else {

                $relatorio = $this->relatorio->tempo_logado($this->input->post());
            }
        }

        $dados = array('msg' => $msg, 'relatorio' => $relatorio);

        $usuarios = $this->relatorio->get_usuarios();
        $lista_usuarios = array();
        if (count($usuarios) > 0) {

            foreach ($usuarios as $usuario) {

                if ($this->input->post() && $usuario->login == $this->input->post('usuario_email')) {
                    $dados['nome_usuario'] = $usuario->nome;
                }
                $lista_usuarios[] = $usuario->login;
            }
        }

        $users['titulo'] = 'Relatório de Tempo Logado';
        $users['usuarios'] = json_encode($lista_usuarios);
        $users['dt_ini'] = $this->input->post('dt_ini');
        $users['dt_fim'] = $this->input->post('dt_fim');

        if ($this->input->post()) {

            $users['dt_ini'] = data_for_humans($this->input->post('dt_ini'));
            $users['dt_fim'] = data_for_humans($this->input->post('dt_fim'));
        }
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/tempo_logado'));
        $this->load->view('fix/header_NS', $users);
        $this->load->view('relatorios/tempo_logado', $dados);
        $this->load->view('fix/footer_NS');
    }

    public function getLoginUsers()
    {
        $input = $this->input->get('input'); // Pega o valor do input
        $usuarios = $this->relatorio->get_user_logins();

        $suggestions = array();

        foreach ($usuarios as $usuario) {
            if (strpos($usuario['l'], $input) !== false) {
                $suggestions[] = $usuario['l'];
            }

            // Limita o número de sugestões a 10
            if (count($suggestions) >= 8) {
                break;
            }
        }

        echo json_encode($suggestions);
    }



    public function relatorio_contas()
    {
        $this->auth->is_allowed('rel_contas');
        $this->load->model('conta');
        $dados['titulo'] = 'Relatório de Contas';
        $dados['msg'] = false;
        $dados['tipo_pendente'] = false;
        $dados['tipo_pago'] = false;
        $dados['tipo_cancelado'] = false;
        $dados['agrupar_cliente'] = false;
        $dados['valor_pago'] = false;
        $dados['valor_eco'] = false;
        $dados['valor_pendente'] = false;
        $dados['valor_total'] = false;
        $dados['categorias'] = $this->conta->getCategorias();
        $fornec = $this->conta->get_all();
        $fornecedores = array();
        foreach ($fornec as $f) :
            if (!in_array($f->fornecedor, $fornecedores))
                $fornecedores[] = $f->fornecedor;
        endforeach;
        $dados['fornecedores'] = json_encode($fornecedores);
        if ($this->input->post()) {
            $date_start = data_for_unix($this->input->post('dt_ini'));
            $date_end = data_for_unix($this->input->post('dt_fim'));
            $provider = $this->input->post('fornecedor');
            $status = $this->input->post('status_conta');
            $empresa = $this->input->post('empresa');
            $categoria = $this->input->post('categoria');

            if ($date_start <= $date_end) {
                $dados['valor_pago'] = 0;
                $dados['valor_total'] = 0;
                $dados['valor_pendente'] = 0;
                if ($status) {
                    $contas = $this->conta->get_bills($date_start, $date_end, $provider, $status, $empresa, $categoria);
                    if ($contas) {
                        $dados['contas'] = $contas;

                        foreach ($dados['contas'] as $c) {
                            $dados['valor_total'] += $c->valor;
                            if ($c->valor_pago > $c->valor) {
                                $dados['valor_pago'] += $c->valor;
                            } else {
                                $dados['valor_pago'] += $c->valor_pago;
                            }
                            if ($c->valor_pago < 1 && $c->status != 3)
                                $dados['valor_pendente'] += $c->valor;
                            if ($c->valor_pago >= 1 && $c->valor_pago < $c->valor)
                                $dados['valor_eco'] += ($c->valor - $c->valor_pago);
                        }
                    } else {
                        $dados['msg'] = 'Nenhum registro encontrado.';
                    }
                } else {
                    $dados['msg'] = 'Favor escolher uma das três opções (Pendente, Pago e Cancelado).';
                }
            } else {
                $dados['msg'] = 'Data Início não pode ser maior que Data Fim.';
            }
            if (is_array($status)) {
                for ($i = 0; $i < count($status); $i++) {
                    if ($status[$i] == 0)
                        $dados['tipo_pendente'] = true;
                    elseif ($status[$i] == 1)
                        $dados['tipo_pago'] = true;
                    elseif ($status[$i] == 3)
                        $dados['tipo_cancelado'] = true;
                    else
                        $dados['agrupar_cliente'] = true;
                }
            }
        }
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/relatorio_contas'));
        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/contas/view');
        $this->load->view('fix/footer');
    }

    //DEVELOPER ANDRÉ GOUVEIA ---- ;D
    public function fatura_cliente()
    {
        $dados['titulo'] = "Showtecnologia";
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/fatura_cliente'));
        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/faturas/faturas_cliente');
        $this->load->view('fix/footer');
    }

    //CARREGA VIEW DO RELATORIO DE FATURAS ATRASADAS
    public function faturas_atrasadas()
    {

        // $this->auth->is_allowed('rel_monitorados_dia_atividade');
        $dados['titulo'] = lang('relatorio') . ' - ' . lang('faturas_atrasadas');
        $dados['load'] = array('buttons_html5', 'datapicket', 'datatable_responsive');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/faturas_atrasadas'));
        $this->load->view('fix/header-new', $dados);
        $this->load->view('relatorios/faturas/faturas_atrasadas');
        $this->load->view('fix/footer_new');
    }

    //CARREGA OS DADOS DE FATURAS ATRASADAS POR AJAX
    public function ajaxListaFaturasAtrasadas()
    {
        $di = data_for_unix($this->input->post('di'));
        $df = data_for_unix($this->input->post('df'));
        $empresa = $this->input->post('empresa');
        $orgao = $this->input->post('orgao');
        $id_cliente = $this->input->post('id_cliente');
        $valor_total = 0;
        $hoje = date('Y-m-d');
        $table = array();

        if ($di && $df) {
            $fatsAtrasadas = $this->fatura->faturasDiasAtraso($di, $df, $id_cliente, $empresa, $orgao);
            if ($fatsAtrasadas) {
                foreach ($fatsAtrasadas as $fatura) {
                    $table[] =  array(
                        'id' => $fatura->Id,
                        'cliente' => $fatura->nome,
                        'data_emissao' => data_for_humans($fatura->data_emissao),
                        'data_vencimento' => data_for_humans($fatura->data_vencimento),
                        'valor_total' => $fatura->valor_total,
                        'status' => status_fatura($fatura->status, $fatura->data_vencimento)
                    );

                    $valor_total += $fatura->valor_total;
                }
            }

            echo json_encode(array('success' => true, 'valor_total' => 'R$ ' . number_format($valor_total, 2, ',', '.'), 'tabela' => $table));
        } else {
            echo json_encode(array('success' => true, 'msg' => lang('datas_obrigatorias')));
        }
    }

    //CARREGA VIEW DO RELATORIO DE FATURAS PROCESSADAS
    public function faturas_processadas()
    {


        $dados['titulo'] = lang('relatorio') . ' - ' . lang('faturas_processadas');
        $dados['load'] = array('buttons_html5', 'datapicket', 'datatable_responsive');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/faturas_processadas'));
        $this->load->view('fix/header-new', $dados);
        $this->load->view('relatorios/faturas/faturas_processadas');
        $this->load->view('fix/footer_new');
    }

    //CARREGA OS DADOS DE FATURAS PROCESSADAS POR AJAX
    public function ajaxListaFaturasProcessadas()
    {
        $di = data_for_unix($this->input->post('di'));
        $df = data_for_unix($this->input->post('df'));

        $table = array();

        if ($di && $df) {
            $fatsProcessadas = $this->fatura->faturasDiasProcessadas($di, $df);

            if ($fatsProcessadas) {
                foreach ($fatsProcessadas as $fatura) {
                    $table[] =  array(
                        'id_retorno' => $fatura->id_retorno,
                        'arquivo_retorno' => $fatura->arquivo_retorno,
                        'fatnumero_retorno' => $fatura->fatnumero_retorno,
                        'datapagto_retorno' => data_for_humans($fatura->datapagto_retorno),
                        'dataexec_retorno' => data_for_humans(substr($fatura->dataexec_retorno, 0, 10)),
                        'statusexec_retorno' => $fatura->statusexec_retorno
                    );
                }
            }

            echo json_encode(array('success' => true, 'tabela' => $table));
        } else {
            echo json_encode(array('success' => true, 'msg' => lang('datas_obrigatorias')));
        }
    }




    public function get_list_fatura()
    {
        $dtIni = data_for_unix($this->input->get('dtIni'));
        $dtFim = data_for_unix($this->input->get('dtFim'));
        $cliente = $this->input->get('cliente');
        $dados = null;
        $dadosFatura[] = $this->relatorio->fatura_by_deve($dtIni, $dtFim, $cliente);
        $json[] = array(
            '#' => 1,
            'cliente' => $dadosFatura[0]->cliente,
            'faturasEmAtraso' => $dadosFatura[0]->faturasEmAtraso,
            'valorMédio' => number_format($dadosFatura[0]->valorMédio, 2, ",", "."),
            'valorTotalDoDébito' => number_format($dadosFatura[0]->valorTotalDoDébito, 2, ",", "."),
            'situação' => "Inadimplente"
        );
        if ($dadosFatura[0]->faturasEmAtraso > 0) {
            echo json_encode($json);
        } else {
            echo json_encode(array('dados' => false, 'msg' => "O cliente " . $cliente . " não possui faturas em atraso."));
        }
    }

    // public function clientes_uf() {
    //     if ($this->input->post('uf')) {
    //         $uf = $this->input->post('uf');
    //         $empresa = $this->input->post('emp');
    //         $result = $this->relatorio->get_clientes_uf($uf, $empresa);
    //
    //         if (empty($result)) {
    //             $dados['msg'] = 'Nenhum registro encontrado!';
    //         } else {
    //             $dados['list_clientes'] = $result;
    //         }
    //     }
    //     $dados['titulo'] = "Showtecnologia";
    //     $this->load->view('fix/header', $dados);
    //     $this->load->view('relatorios/clientes_uf');
    //     $this->load->view('fix/footer');
    // }

    /*
    * CARREGA VIEW RELATORIO CLIETES POR UF
    */
    public function clientes_uf()
    {
        $dados['titulo'] = lang('relatorio') . ' - ' . lang('resumo_veiculos_estado');
        // $dados['load'] = array('buttons_html5');
        $dados['load'] = array('ag-grid', 'select2', 'mask');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/clientes_uf'));

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/clientes_uf');
        $this->load->view('fix/footer_NS.php');
    }

    public function listClienesUF()
    {
        if ($this->input->post('uf')) {
            $uf = $this->input->post('uf');
            $empresa = $this->input->post('empresa');
            $orgao = $this->input->post('orgao');
            $results = $this->relatorio->get_clientes_uf($uf, $empresa, $orgao);

            if ($results) {
                $table = array();
                foreach ($results as $key => $result) {
                    $table[] = array(
                        'linha' => $key + 1,
                        'id' => $result->id,
                        'nome' => $result->nome,
                        'endereco' => $result->endereco,
                        'cidade' => $result->cidade,
                        'uf' => $result->uf,
                        'telefone' => $result->fone,
                        'data_cadastro' => data_for_humans(explode(' ', $result->data_cadastro)[0])
                    );
                }
                echo json_encode(array('status' => true, 'result' => $table));
            } else {
                echo json_encode(array('status' => false, 'result' => array()));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => lang('estado_nao_informado')));
        }
    }

    /**CARREGA A VIEW PASSANDO INFORMAÇÕES REFERENTE AS FATURAS POR DISPONIBILIDADE**/
    public function fatura_disponibilidade()
    {
        $dados['titulo'] = "ShowTecnologia";
        $faturas = $this->fatura->get_fatura_by_contrato();
        $lista_faturas = [];
        if (count($faturas) > 0) {
            foreach ($faturas as $key => $fatura) {
                $cliente = $this->cliente->get_nameClient($fatura->id_cliente);
                $lista_faturas[] = $fatura->id . ' - ' . $fatura->id_contrato . ' - ' . $cliente . ' - ' . data_for_humans($fatura->data_vencimento);
            }
        }
        $dados['faturas'] = json_encode($lista_faturas);
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/fatura_disponibilidade'));
        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/faturas/resumo_por_disponibilidade');
        $this->load->view('fix/footer');
    }

    /**VERIFICA SE O CONTRATO (POR DISPONIBILIDADE) EXISTE**/
    private function validaContrato($id_contrato)
    {
        $ids = [];
        $contratos = $this->contrato->get_contratos_disponibilidade();
        foreach ($contratos as $contrato) {
            $ids[] = $contrato->id;
        }
        if (in_array($id_contrato, $ids)) {
            return true;
        } else {
            return false;
        }
    }

    /**RESUMO DA FATURA POR DISPONIBILIDADE**/
    public function getResumoDisponibilidade()
    {
        $id_contrato = trim($this->input->get('id_contrato'));
        if ($this->validaContrato($id_contrato)) {
            $dt_vencimento = data_for_unix(trim($this->input->get('dt_vencimento')));
            $lastDayConsumo = date('Y-m-d', strtotime("$dt_vencimento -5 DAYS")) . " 23:59:59";
            $consumo = $this->fatura->getResumoConsumo($id_contrato, $lastDayConsumo);
        } else {
            $consumo = [];
        }
        echo json_encode($consumo);
    }

    /** VERIFICANDO SAEB **/
    public function saeb()
    {
        $this->relatorio->saeb1();
    }

    public function assinatura_eptc()
    {
        $dados['titulo'] = "Show Tecnologia";
        $dados['assinaturas'] = $this->relatorio->assinatura_eptc();

        $this->load->view('fix/header4', $dados);
        $this->load->view('relatorios/assinatura_eptc');
        $this->load->view('fix/footer4');
    }
    /*
    ** Relatorio de Placas Ativas e Inativas
    */
    public function placas_ativas_inativas()
    {
        $dados['titulo'] = lang('rel_placas_ativas_inativas');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/placas_ativas_inativas'));

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/relatorio_placas');
        $this->load->view('fix/footer_NS');
    }

    public function buscar_relatorio_placas()
    {
        $data = $this->veiculo->get_rel_status_veiculo();
        echo json_encode($data);
    }

    /*
    ** Função utilizada pela function relPlacas
    */
    public function save_list($id, $nome, $qtAtivos, $qtInativos)
    {
        $list = array(
            'id' => $id,
            'nome' => $nome,
            'qtAtivos' => $qtAtivos,
            'qtInativos' => $qtInativos
        );
        return $list;
    }

    public function comissao_dev()
    {
        $dados['titulo'] = 'Comissionamento - ShowRoutes';

        if ($this->input->post()) {
            $dados['relatorio'] = $this->relatorio->getPointsByDate($this->input->post('data_ini'), $this->input->post('data_fim'));
        }
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/comissao_dev'));
        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/comissao_dev');
        $this->load->view('fix/footer');
    }

    public function detalha_points_ajax()
    {
        $dados['relatorio'] = $this->relatorio->getPointsByClie($this->input->post());

        echo $this->load->view('relatorios/modal_pontos', $dados);
    }

    public function comissao()
    {
        if ($this->input->get('request')) {

            $id_vendedor = $this->input->get('id_vendedor');
            $data_inicial = $this->input->get('dt_inicial');
            $data_final = $this->input->get('dt_final');
            $percent_adesao = $this->input->get('percent_adesao');
            $percent_mensalidade = $this->input->get('percent_mensalidade');

            //recupera os contratos do vendedor escolhido
            $contratos = $this->relatorio->getContratosByVendedor($id_vendedor, $data_inicial, $data_final);

            if ($contratos) {
                foreach ($contratos as $key => $contrato) {
                    $quantidade_instalados = 0;

                    //recupera os veiculo de cada contrato
                    $veiculos = $this->relatorio->getVeiculosContrato($contrato->id);
                    foreach ($veiculos as $key => $veiculo) {
                        $resultado = $this->relatorio->getVeiculosbyPlaca($veiculo->placa, $data_inicial, $data_final);
                        if ($resultado) {
                            $quantidade_instalados++;
                        }
                    }
                    $contrato->quantidade_instalados = $quantidade_instalados;
                    $contrato->total_instalacao = $quantidade_instalados * $contrato->valor_instalacao;
                    $contrato->total_mensalidade = $quantidade_instalados * $contrato->valor_mensal;
                    $contrato->comissao_adesao = ($percent_adesao / 100) * $contrato->total_instalacao;
                    $contrato->comissao_mensalidade = ($percent_mensalidade / 100) * $contrato->total_mensalidade;
                    $contrato->total_comissao = $contrato->comissao_adesao + $contrato->comissao_mensalidade;
                }
                echo json_encode($contratos);
            } else {
                echo json_encode(array());
            }
        } else {
            $dados['titulo'] = "Relatório de Comissão";
            $dados['vendedores'] = $this->usuario->all();
            $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/comissao'));
            $this->load->view('fix/header', $dados);
            $this->load->view('relatorios/comissao');
            $this->load->view('fix/footer');
        }
    }

    public function veiculos_dia_atualizacao()
    {
        $this->load->model('veiculo');
        echo $this->veiculo->total_lista_veiculos_gestor($this->input->post('id'));
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> LISTA A QUANTIDADE DE VEÍCULOS POR DIA DE ATUALIZAÇÃO
    */
    public function veiculosDiaAtualizacao()
    {
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/veiculosDiaAtualizacao'));
        $dados['titulo'] = lang('veiculos_dia_atualizacao');
        $dados['load'] = array('buttons_html5', 'datapicket', 'datatable_responsive');
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/veiculos_dia_atualizacao');
        $this->load->view('fix/footer_NS');
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> RETORNA CONSULTA DO BANCO VEICULOS DIA ATUALIZADO
    */
    public function listaVeiculosDiaAtualizacao()
    {

        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');
        $id_cliente = $this->input->post('clienteId');

        $dataInicioReplace = str_replace("-", "/", $dataInicial);
        $dataInicialFormatada = date('d/m/Y', strtotime($dataInicioReplace));
        $dataFinalReplace = str_replace("-", "/", $dataFinal);
        $dataFinalFormatada = date('d/m/Y', strtotime($dataFinalReplace));

        $retorno = get_RelatorioVeiculosAtualizados($id_cliente, $dataInicialFormatada, $dataFinalFormatada);

        echo json_encode($retorno);
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> CARREGA A VIEW INICIAL DOS CONTRATOS DE RESCISÃO PRIVADA
    */
    public function rescisao_contratos_privados($id_contrato = null)
    {
        if ($id_contrato) {
            $dados['id_contrato'] = $id_contrato;
            $dados['titulo'] = 'Relatórios - Cálculo de Rescisão de Contratos Privados';
            $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/rescisao_contratos_privados'));
            $this->load->view('fix/header_NS', $dados);
            $this->load->view('relatorios/contratos/calculo_rescisao_contrato_privado');
            $this->load->view('fix/footer_NS');
        } else {
            $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/rescisao_contratos_privados'));
            $dados['titulo'] = 'Relatórios - Listagem Rescisão de Contratos Privados';
            $this->load->view('fix/header_NS', $dados);
            $this->load->view('relatorios/contratos/lista_rescisao_contrato_privado');
            $this->load->view('fix/footer_NS');
        }
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> LISTA OS CONTRATOS DE RESCISÃO PRIVADA
    */
    public function listar_rescisao_contratos_privados()
    {
        $table = array(); # Data DATATABLE
        $id_cliente = $this->input->get('id_cliente');


        if ($id_cliente) { # Se for passado os parâmetros
            //$dados['cliente'] = $this->cliente->get_clientes($id_cliente);
            $select = 'ctr.*';
            $where =  array('ctr.id_cliente' => $id_cliente);
            $dados['contratos'] = $this->contrato->listar($where, 0, 9999, 'ctr.data_contrato', 'DESC', $select);

            $hoje = date('Y-m-d');
            foreach ($dados['contratos'] as $value) {
                $link = site_url('relatorios/rescisao_contratos_privados/' . $value->id);
                $qtd_veiculos = $value->quantidade_veiculos;
                $valor_por_veiculo = $value->valor_mensal;
                $valor_mensal = $valor_por_veiculo * $qtd_veiculos;
                $mesAvencer = $value->meses - (dateDifference($value->data_contrato, $hoje, '%a') / 30);
                if ($mesAvencer <= 0) {
                    $mesAvencer = 0;
                }
                $totalAvencer = $mesAvencer * $valor_mensal;

                $table[] = array(
                    $value->id,
                    $qtd_veiculos,
                    number_format($valor_por_veiculo, 2, ',', '.'),
                    number_format($valor_mensal, 2, ',', '.'),
                    $value->meses,
                    data_for_humans($value->data_contrato),
                    data_for_humans(date('Y-m-d', strtotime($value->data_contrato . ' + ' . $value->meses . ' month'))),
                    $mesAvencer,
                    number_format($totalAvencer, 2, ',', '.'),
                    status_contrato($value->status),
                    '<a href="' . $link . '" class="btn btn-primary" style="display: flex;" type="button">Calcular</a>'
                );
            }
            echo json_encode(array('status' => 'OK', 'tabela' => $table));
        } else { # Se não vir parâmetros
            echo json_encode(array('status' => 'ERRO'));
        }
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> CALCULA O CONTRATO DE RESCISÃO PRIVADA
    */
    public function calcular_rescisao_contratos_privados($id_contrato)
    {
        $retirada = $this->input->get('retirada');
        $contrato = $this->contrato->get_contratos($id_contrato);
        $hoje = date('Y-m-d');

        if ($retirada) {
            $qtd_veiculos = $contrato[0]->quantidade_veiculos;
            $valor_por_veiculo = $contrato[0]->valor_mensal;
            $valor_mensal = $valor_por_veiculo * $qtd_veiculos;
            $mesAvencer = $contrato[0]->meses - (dateDifference($contrato[0]->data_contrato, $hoje, '%a') / 30);
            if ($mesAvencer <= 0) {
                $mesAvencer = 0;
                $retirada = 0.0;
            }
            $totalAvencer = $mesAvencer * $valor_mensal;
            $trintaPorcento = $totalAvencer * 0.3;

            $table[] = array(
                $contrato[0]->id,
                $qtd_veiculos,
                number_format($valor_por_veiculo, 2, ',', '.'),
                number_format($valor_mensal, 2, ',', '.'),
                $contrato[0]->meses,
                data_for_humans($contrato[0]->data_contrato),
                data_for_humans(date('Y-m-d', strtotime($contrato[0]->data_contrato . ' + ' . $contrato[0]->meses . ' month'))),
                $mesAvencer,
                number_format($totalAvencer, 2, ',', '.'),
                number_format($trintaPorcento, 2, ',', '.'),
                number_format($retirada, 2, ',', '.'),
                number_format(($totalAvencer + $trintaPorcento + $retirada), 2, ',', '.'),
                number_format(($totalAvencer + $trintaPorcento + $retirada), 2, ',', '.'),
                status_contrato($contrato[0]->status)
            );

            echo json_encode(array('status' => 'OK', 'tabela' => $table));
        } else { # Se não vir parâmetros
            echo json_encode(array('status' => 'ERRO'));
        }
    }

    public function relatorio_tipo_servico()
    {
        $dados['titulo'] = 'Relatório - Por Tipo de Serviço';
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/relatorio_tipo_servico'));
        $this->load->view('fix/header', $dados);
        $this->load->view('relatorios/faturas/relatorio_tipo_servico');
        $this->load->view('fix/footer');
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> CALCULA O CONTRATO DE RESCISÃO PRIVADA
    */
    public function calcula_relatorio_tipo_servico()
    {

        $table = array(); # Data DATATABLE
        $di = data_for_unix($this->input->get('di'));
        $df = data_for_unix($this->input->get('df'));
        $id_cliente = $this->input->get('id_cliente');

        $somatorioMensalidade = 0.0;
        $somatorioInstalacao = 0.0;
        $somatorioManutencao = 0.0;
        $somatorioRetirada = 0.0;
        $somatorioTroca = 0.0;

        $faturas = $this->fatura->listar_por_tipo_servico($di, $df, $id_cliente, 0, 1000);

        if ($faturas) {
            foreach ($faturas as $key => $fatura) {
                if ($fatura->valor_pago == NULL) {
                    $fatura->valor_pago = 0.0;
                }

                if ($fatura->tipo_os == 1) {
                    $status_tipo_os = '<span class="label label-info">Instalação</span>';
                    $somatorioInstalacao += $fatura->valor_pago;
                } elseif ($fatura->tipo_os == 2) {
                    $status_tipo_os = '<span class="label label-warning">Manutenção</span>';
                    $somatorioManutencao += $fatura->valor_pago;
                } elseif ($fatura->tipo_os == 3) {
                    $status_tipo_os = '<span class="label label-warning">Troca</span>';
                    $somatorioTroca += $fatura->valor_pago;
                } elseif ($fatura->tipo_os == 4) {
                    $status_tipo_os = '<span class="label label-danger">Retirada</span>';
                    $somatorioRetirada += $fatura->valor_pago;
                } else {
                    $status_tipo_os = '<span class="label label-success">Mensalidade</span>';
                    $somatorioMensalidade += $fatura->valor_pago;
                }

                $table[] = array(
                    $fatura->Id,
                    $fatura->nome,
                    $status_tipo_os,
                    number_format($fatura->valor_total, 2, ',', '.'),
                    number_format($fatura->valor_pago, 2, ',', '.'),
                    data_for_humans($fatura->data_vencimento),
                    status_fatura($fatura->tipo_os, $fatura->data_vencimento)
                );
            }

            echo json_encode(
                array(
                    'status' => 'OK',
                    'table' => $table,
                    'somatorioMensalidade' => number_format($somatorioMensalidade, 2, ',', '.'),
                    'somatorioInstalacao' => number_format($somatorioInstalacao, 2, ',', '.'),
                    'somatorioManutencao' => number_format($somatorioManutencao, 2, ',', '.'),
                    'somatorioTroca' => number_format($somatorioTroca, 2, ',', '.'),
                    'somatorioRetirada' => number_format($somatorioRetirada, 2, ',', '.')

                )
            );
        } else { # Se não vir parâmetros
            echo json_encode(array('status' => 'ERRO'));
        }
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> CLIENTES
    */
    public function rel_clientes()
    {
        $dados['titulo'] = 'Relatório - Clientes';
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/rel_clientes'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('relatorios/relatorio_clientes');
        $this->load->view('fix/footer_NS');
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> DISPONIBILIDADE DE VEICULOS
    */
    public function ajax_clientes()
    {
        $prestadora = $this->input->get('prestadora');
        $status = $this->input->get('status');
        $orgao = $this->input->get('orgao');
        $idCliente = $this->input->get('idCliente');
        $statusContrato = $this->input->get('statusContrato');

        $table = array();

        $where_clie = array('clie.informacoes' => $prestadora);
        $select_clie = 'clie.id as idCliente, clie.nome, clie.cpf, clie.cnpj, clie.status, cont.id, cont.status as statusContrato, cont.valor_mensal, cont.valor_instalacao, cont.dataFim_aditivo';

        if ($idCliente) {
            $where_clie['clie.id'] = $idCliente;
        }

        if ($orgao) {
            $where_clie['clie.orgao'] = $orgao;
        }

        if (is_numeric($status)) {
            $where_clie['clie.status'] = $status;
        }

        if (is_numeric($statusContrato)) {
            $where_clie['cont.status'] = $statusContrato;
        }

        $clientes = $this->cliente->get_clientes_and_contratos($select_clie, $where_clie);

        $statusClientes = [
            0 => 'Bloqueado',
            1 => 'Ativo',
            2 => 'Prospectado',
            3 => 'Em teste',
            4 => 'A reativar',
            5 => 'Inativo',
            6 => 'Bloqueio parcial',
            7 => 'Negativado'
        ];

        $statusContratos = [
            0 => 'Cadastrado',
            1 => 'Esperando OS',
            2 => 'Ativo',
            3 => 'Cancelado',
            5 => 'Bloqueado',
            6 => 'Encerrado',
            7 => 'Cancelamento em Processo de Retirada',

        ];
        if (count($clientes) > 0) {
            foreach ($clientes as $key => $cliente) {
                $statusCliente = isset($statusClientes[$cliente->status]) ? $statusClientes[$cliente->status] : '-';
                $statusContrato = isset($statusContratos[$cliente->statusContrato]) ? $statusContratos[$cliente->statusContrato] : '-';
                $documentoCliente = $cliente->cpf ? $cliente->cpf : $cliente->cnpj;

                $faturamento = buscarFaturamento($cliente->faturamento);

                $table[] = array(
                    $cliente->idCliente,
                    $cliente->nome,
                    $documentoCliente,
                    $statusCliente,
                    $cliente->id,
                    $faturamento,
                    $cliente->num_veiculos_contrato ? $cliente->num_veiculos_contrato : '-',
                    $cliente->statusContrato == '1' || $cliente->statusContrato == '2' || $cliente->statusContrato == '7' ? $cliente->num_veiculos_ativos : '-',
                    $cliente->valor_unit,
                    $cliente->valor_contratado,
                    $cliente->valor_total,
                    $statusContrato,
                    $cliente->valor_mensal,
                    $cliente->valor_instalacao,
                    $cliente->dataFim_aditivo ? data_for_humans($cliente->dataFim_aditivo) : '-'
                );
            }

            echo json_encode(array('status' => 'OK', 'tabela' => $table));
        } else {
            echo json_encode(array('status' => 'ERRO', 'msg' => 'Dados não encontrados para os parâmetros informados!'));
        }
    }

    /*
   * CARREGA VIEW DE RELATÓRIO >> CLIENTES PUBLICOS
   */
    public function quantitativoContratosOld()
    {
        $dados['titulo'] = lang('quantitativo_contratos_veiculos') . ' - ' . lang('show_tecnologia');
        $dados['load'] = array('buttons_html5', 'datapicket', 'googlechart');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/quantitativoContratos'));
        $this->load->view('fix/header-new', $dados);
        $this->load->view('relatorios/contratos/quantitativo_contrato_veiculo');
        $this->load->view('fix/footer_new');
    }

    /*
   * RETORNA OS CALCULOS/ QUANTITATIVO DOS CONTRATOS MENSAL ENTRE AS DATAS PASSADAS POR PARAMETRO
   */
    public function loadQuantitativoContratosOld()
    {

        $table = array(); # Data DATATABLE
        $di = data_for_unix($this->input->post('di'));
        $df = data_for_unix($this->input->post('df'));
        $prest = $this->input->post('prestadora');

        if ($di && $df && $prest) { # Se for passado os parâmetros
            $countContract = 0;
            $countVeics = 0;
            $prestadora = '';
            $veiculosArray = array();
            $contratosArray = array();

            foreach ($prest as $informacao) {
                $prestadora .= '"' . $informacao . '",';
            }
            $prestadora = '(' . substr($prestadora, 0, -1) . ')';
            $contratos = $this->contrato->getQuantitativoContratos($di, $df, $prestadora);
            if ($contratos) {
                foreach ($contratos as $key => $contrato) {
                    $countContract += $contrato->qtdContratos;
                    $contratosArray[$contrato->data] = array(
                        'indice' => $key + 1,
                        'data' => $contrato->data,
                        'qtdContratos' => $contrato->qtdContratos
                    );
                }
            }
            $veiculos = $this->contrato->getQuantitativoVeiculos($di, $df, $prestadora);
            if ($veiculos) {
                foreach ($veiculos as $veiculo) {
                    $countVeics += $veiculo->qtdVeiculos;
                    $status = explode(',', $veiculo->status);
                    $qtdVeicativos =
                        $veiculosArray[$veiculo->data] = array(
                            'qtdVeiculos' => $veiculo->qtdVeiculos,   //total de veiculos
                            'veiculosAtivos' => array_count_values($status)[1]  //qtd de veiculos ativos
                        );
                }
            }
            $tempArray = array_merge_recursive($contratosArray, $veiculosArray);
            if ($tempArray) {
                foreach ($tempArray as $temp) {
                    $table[] = $temp;
                }
            }
            echo json_encode(array('success' => true, 'total_contratos' => $countContract, 'total_veiculos' => $countVeics, 'table' => $table));
        } else {
            echo json_encode(array('success' => false, 'msg' => lang('datas_invalidas')));
        }
    }

    public function quantitativoContratos()
    {
        $dados['titulo'] = lang('quantitativo_contratos_veiculos') . ' - ' . lang('show_tecnologia');
        $dados['load'] = array('buttons_html5', 'datapicket', 'googlechart');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/quantitativoContratos'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/contratos/quantitativo_contrato_veiculo_new');
        $this->load->view('fix/footer_NS');   
    }

    /*
   * RETORNA OS CALCULOS/ QUANTITATIVO DOS CONTRATOS MENSAL ENTRE AS DATAS PASSADAS POR PARAMETRO
   */
  public function loadQuantitativoContratos()
  {
      $table = array(); // Data for DataTable
      $di = data_for_unix($this->input->post('di_formatted'));
      $df = data_for_unix($this->input->post('df_formatted'));
      $prest = $this->input->post('prestadora');
  
      if ($di && $df && $prest) { // Check if parameters are provided
          $countContract = 0;
          $countVeics = 0;
          $prestadora = '';
          $veiculosArray = array();
          $contratosArray = array();
  
          // Create a string of prestadora values
          foreach ($prest as $informacao) {
              $prestadora .= '"' . $informacao . '",';
          }
          $prestadora = '(' . substr($prestadora, 0, -1) . ')';
  
          // Fetch contract data
          $contratos = $this->contrato->getQuantitativoContratos($di, $df, $prestadora);
          if ($contratos) {
              foreach ($contratos as $key => $contrato) {
                  $countContract += $contrato->qtdContratos;
                  $contratosArray[$contrato->data] = array(
                      'indice' => $key + 1,
                      'data' => $contrato->data,
                      'qtdContratos' => $contrato->qtdContratos,
                      'qtdVeiculos' => 0, // Default value
                      'veiculosAtivos' => 0 // Default value
                  );
              }
          }
  
          // Fetch vehicle data
          $veiculos = $this->contrato->getQuantitativoVeiculos($di, $df, $prestadora);
          if ($veiculos) {
              foreach ($veiculos as $veiculo) {
                  $countVeics += $veiculo->qtdVeiculos;
                  $status = explode(',', $veiculo->status);
                  $statusCounts = array_count_values($status);
                  $activeVehiclesCount = isset($statusCounts[1]) ? $statusCounts[1] : 0;
  
                  if (isset($veiculosArray[$veiculo->data])) {
                      $veiculosArray[$veiculo->data]['qtdVeiculos'] += $veiculo->qtdVeiculos;
                      $veiculosArray[$veiculo->data]['veiculosAtivos'] += $activeVehiclesCount;
                  } else {
                      $veiculosArray[$veiculo->data] = array(
                          'qtdVeiculos' => $veiculo->qtdVeiculos,
                          'veiculosAtivos' => $activeVehiclesCount
                      );
                  }
              }
          }
  
          // Merge contract and vehicle data
          $tempArray = array_merge_recursive($contratosArray, $veiculosArray);
          if ($tempArray) {
              foreach ($tempArray as $data => $temp) {
                  $table[] = array(
                      'indice' => isset($temp['indice'][0]) ? $temp['indice'][0] : (isset($temp['indice']) ? $temp['indice'] : ''),
                      'data' => $data,
                      'qtdContratos' => isset($temp['qtdContratos'][0]) ? $temp['qtdContratos'][0] : (isset($temp['qtdContratos']) ? $temp['qtdContratos'] : 0),
                      'qtdVeiculos' => isset($temp['qtdVeiculos'][0]) ? $temp['qtdVeiculos'][0] : (isset($temp['qtdVeiculos']) ? $temp['qtdVeiculos'] : 0),
                      'veiculosAtivos' => isset($temp['veiculosAtivos'][0]) ? $temp['veiculosAtivos'][0] : (isset($temp['veiculosAtivos']) ? $temp['veiculosAtivos'] : 0)
                  );
              }
          }
  
          // Return JSON response with data
          echo json_encode(array('success' => true, 'total_contratos' => $countContract, 'total_veiculos' => $countVeics, 'table' => $table));
      } else {
          // Return error message if parameters are invalid
          echo json_encode(array('success' => false, 'msg' => lang('datas_invalidas')));
      }
  }
  
  

    //CARREGA VIEW DO RELATORIO DE ADESAO/INSTALACAO GERADAS
    public function rel_adesao()
    {
        $dados['titulo'] = lang('rel_adesao') . ' - ' . lang('show_tecnologia');
        $dados['load'] = array('buttons_html5', 'datapicket');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/rel_adesao'));
        $this->load->view('fix/header-new', $dados);
        $this->load->view('relatorios/faturas/rel_adesao');
        $this->load->view('fix/footer_new');
    }

    /*
    * RETORNA OS STATOS (SE JA FOI CADASTRADA) DAS ADESOES PARA CADA CONTRATO
    */
    public function loadStatusAdesoesInstalacoes()
    {

        $table = array();
        $contratos = array();
        $contratosQuery = array();
        $id_cliente = $this->input->get('id_cliente');
        $id_contrato = $this->input->get('id_contrato');

        if ($id_cliente && $id_contrato) {

            if ($id_contrato == 'todos') {
                $contratosQuery = $this->contrato->listar(array('id_cliente' => $id_cliente, 'status !=' => 3), 0, 9999999, 'ctr.id', 'DESC', 'ctr.id', false);
                if (count($contratosQuery) > 0) {
                    foreach ($contratosQuery as $contrato) {
                        $contratos[] = $contrato->id;
                    }
                }
                $contratosQuery = array();
            } else {
                $contratos[] = $id_contrato;
            }
            //PEGA OS CONTRATOS QUE POSSUEM ADESAO
            $returnQuery = $this->fatura->listaContratosAdesao($contratos);
            //SE DA LISTA DE CONTRATOS PASSADA TIVER PELO MENOS UMA ADESAO GERADA, MONTA A TEBELA
            if ($returnQuery) {
                foreach ($returnQuery as $key => $return) {
                    $contratosQuery[] = $return->id_contrato;
                }

                foreach ($contratos as $key => $contrato) {
                    if (in_array($contrato, $contratosQuery)) {
                        $table[] = array(
                            'indice' => $key + 1,
                            'idContrato' => $contrato,
                            'status_adesao' => '<label class="label label-success">' . lang('gerada') . '<label>',
                            'status_instalacao' => 'desconhecido'
                        );
                    } else {
                        $table[] = array(
                            'indice' => $key + 1,
                            'idContrato' => $contrato,
                            'status_adesao' => '<label class="label label-danger">' . lang('nao_gerada') . '<label>',
                            'status_instalacao' => 'desconhecido'
                        );
                    }
                }
                echo json_encode(array('success' => true, 'table' => $table));
            } else {
                //SE DA LISTA DE CONTRATOS PASSADA NENHUM TIVER ADESAO GERADA
                foreach ($contratos as $key => $contrato) {
                    $table[] = array(
                        'indice' => $key + 1,
                        'idContrato' => $contrato,
                        'status_adesao' => '<label class="label label-danger">' . lang('nao_gerada') . '<label>',
                        'status_instalacao' => 'desconhecido'
                    );
                }
                echo json_encode(array('success' => true, 'table' => $table));
            }
        } else {
            echo json_encode(array('success' => false, 'msg' => lang('dados_fautosos')));
        }
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> LISTA A QUANTIDADE/VALOR DE DETENTOS POR DIA DE ATUALIZAÇÃO
    */
    public function monitoradosDiaAtualizacao()
    {
        $this->auth->is_allowed('rel_monitorados_dia_atividade');
        $dados['titulo'] = lang('relatorio') . ' - ' . lang('monitorados_dia_atualizacao');
        $dados['load'] = array('buttons_html5', 'datapicket', 'datatable_responsive');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/monitoradosDiaAtualizacao'));
        $this->load->view('fix/header-new', $dados);
        $this->load->view('relatorios/monitoradosDiaAtualizacao');
        $this->load->view('fix/footer_new');
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> RETORNA CONSULTA DO BANCO DETENTOS POR DIA DE ATUALIZADO
    */
    public function listaMonitoradosDiaAtualizacao()
    {

        $table = array(); # Data DATATABLE
        $di = data_for_unix($this->input->post('di'));
        $df = data_for_unix($this->input->post('df'));
        $id_cliente = $this->input->post('id_cliente');
        $tipo_faturamento = $this->input->post('tipo_faturamento');
        $parte = $this->input->post('parte');
        $formatoRelatorio = $tipo_faturamento == 0 ? $this->input->post('formato_relatorio') : 'resumido';


        $diasAtividade = array();
        $valor_total = 0.0;
        $dias = array();

        if ($di && $df && $id_cliente) { # Se for passado os parâmetros

            $temConTornozeleira = $this->contrato->listar(array('id_cliente' => $id_cliente, 'tipo_proposta' => 4), 0, 1, 'ctr.id', 'DESC', 'ctr.id', false);
            if (count($temConTornozeleira) > 0) {

                $body = [
                    'inicio'           => $di . ' 00:00:00',
                    'fim'              => $df . ' 23:59:59',
                    'idCliente'        => $id_cliente,
                    'tipoFaturamento'  => $tipo_faturamento,
                    'parte'  => $parte
                ];

                $atividades = json_decode(from_relatorios_api($body, "relatorios/monitoradosDiasRastreados"), true);
                if ($atividades) {
                    if (!isset($atividades['message'])) {
                        mb_internal_encoding('UTF-8');

                        foreach ($atividades['dados']['relatorio'] as $atividade) {
                            $table[] = array(
                                '',
                                $atividade['nome'] ? mb_strtoupper($atividade['nome']) : '',
                                $atividade['idContrato'],
                                $atividade['tornozeleira'],
                                $atividade['cinta'],
                                $atividade['powerbank'],
                                $atividade['carregador'],
                                $atividade['unidade'] ? mb_strtoupper($atividade['unidade']) : '',
                                isset($atividade['unidade_custodia']) ? mb_strtoupper($atividade['unidade_custodia']) : '',
                                $atividade['data_ativacao'] ? data_for_humans($atividade['data_ativacao']) : '',
                                $atividade['data_inativacao'] ? data_for_humans($atividade['data_inativacao']) : '',
                                $atividade['contagemDias'],
                                $atividade['valorPeriodo']
                            );

                            $valor_total += $atividade['valorPeriodo'];

                            end($table);
                            $key = key($table); //PEGA A POSICAO DO ULTIMO ARRAY DENTRO DE $TABLE

                            if ($formatoRelatorio == 'completo') {

                                //PREENCHE OS DIAS DE ATIVIDADE
                                for ($data_temp = $di; $data_temp <= $df; $data_temp = date('Y-m-d', strtotime($data_temp . ' + 1 days'))) {
                                    if (count($atividade['diasRastreados']) > 0) {
                                        if (in_array($data_temp, $atividade['diasRastreados'])) {
                                            array_push($table[$key], "X");
                                        } else {
                                            array_push($table[$key], " ");
                                        }
                                    } else {
                                        array_push($table[$key], " ");
                                    }
                                }
                            }
                        }
                        echo json_encode(
                            array(
                                'success' => true,
                                'qtd_total' => $atividades['dados']['totalDetentos'],
                                'valor_total' => $valor_total,
                                'tabela' => $table,
                                'parte' => $atividades['parte'],
                                'partes' => $atividades['partes']
                            )
                        );
                    } else {
                        echo json_encode(array('success' => false, 'msg' => $atividades['message']));
                    }
                } else {
                    echo json_encode(array('success' => false, 'msg' => lang('operacao_nao_finalizada')));
                }
            } else {
                echo json_encode(array('status' => false, 'msg' => lang('cliente_nao_possui_tornozeleiras')));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => lang('datas_invalidas')));
        }
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >>LISTA DADOS DE EQUIPAMENTOS/VEICULOS
    */
    public function veiculos_por_atividades()
    {
        $this->auth->is_allowed('rel_veic_disponiveis');
        $dados['titulo'] = lang('relatorio') . ' - ' . lang('veiculos_por_atividades');
        $dados['load'] = array('buttons_html5', 'datapicket', 'datatable_responsive');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/veiculos_por_atividades'));
        $this->load->view('fix/header-new', $dados);
        $this->load->view('relatorios/equipamentos');
        $this->load->view('fix/footer_new');
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> RETORNA CONSULTA DO BANCO PARA EQUIPAMENTOS/VEICULOS
    * JUNTAMENTE COM OS CALCULOS DE COBRANÇAS BASEADOS NOS DIAS DE ATIVIDADE
    */
    public function listaDadosEquipamentosAtividades()
    {

        $table = array(); # Data DATATABLE
        $di = data_for_unix($this->input->post('di'));
        $df = data_for_unix($this->input->post('df'));
        $id_cliente = $this->input->post('id_cliente');


        if ($di && $df && $id_cliente) { # Se for passado os parâmetros

            $temContratoVeiculos = $this->contrato->listar('id_cliente = ' . $id_cliente . ' and tipo_proposta not in (1,4,6)', 0, 1, 'ctr.id', 'DESC', 'ctr.id', false);
            if (count($temContratoVeiculos) > 0) {
                $body = [
                    'inicio'     => $di . ' 00:00:00',
                    'fim'        => $df . ' 23:59:59',
                    'idCliente'  => $id_cliente
                ];

                $atividades = json_decode(from_relatorios_api($body, "relatorios/diasRastreados"), true);
                if (!isset($atividades['err']) && $atividades != null) {

                    $diasAtividade = array();
                    foreach ($atividades as $atividade) {
                        $diasAtividade[$atividade['placa']] = array('contagemDias' =>  $atividade['contagemDias'], 'valorPeriodo' => $atividade['valorPeriodo']);
                    }

                    $dadosEquipamento = $this->equipamento->getEquipamentosPeloCliente($id_cliente);
                    if ($dadosEquipamento) {
                        $valor_total = 0.0;
                        $mes = explode('-', $di)[1];
                        $ano = explode('-', $di)[0];

                        //PEGA AS PLACAS DO CLIENTE E SUAS DATAS DE DESINSTALACAO
                        $placasCliente = $this->veiculo->getPlacasCliente('placa, data_inativa as data_desinstalacao', array('id_cliente' => $id_cliente));
                        $placas = array();
                        $desinstalados = array();
                        foreach ($placasCliente as $p) {
                            if ($p->data_desinstalacao) {
                                if (!isset($desinstalados[$p->placa])) $desinstalados[$p->placa] = [];
                                $desinstalados[$p->placa] = $p->data_desinstalacao;
                            }
                            $placas[] = $p->placa;
                        }
                        $placasCliente = array();

                        //PEGA OS GRUPOS DOS VEICULOS DO CLIENTE
                        $grupos = array();
                        $gp_veiculos = $this->veiculo->getGruposVeiculosPorPlacas('g.nome as secretaria, veic_g.placa', $placas);
                        if ($gp_veiculos) {
                            foreach ($gp_veiculos as $gp) {
                                if (!isset($grupos[$gp->placa])) $grupos[$gp->placa] = [];
                                $grupos[$gp->placa] = $gp->secretaria;
                            }
                        }
                        $gp_veiculos = array();

                        foreach ($dadosEquipamento as $equip) {
                            //CALCULA VALOR DIARIO
                            $valor_diario = (float)$equip->valor_mensal / cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

                            $secretaria = isset($grupos[$equip->placa]) ? $grupos[$equip->placa] : '';
                            $data_desinstalacao = isset($desinstalados[$equip->placa]) ? data_for_humans(explode(' ', $desinstalados[$equip->placa])[0]) : '';
                            $contagemDias = isset($diasAtividade[$equip->placa]) ? $diasAtividade[$equip->placa]['contagemDias'] : 0;
                            $valorFaturado = isset($diasAtividade[$equip->placa]) ? $diasAtividade[$equip->placa]['valorPeriodo'] : 0.0;
                            $valor_total += $valorFaturado;

                            $table[] = array(
                                '',         //para a coluna do botao "+" detalhes
                                $equip->id,
                                $equip->placa,
                                $equip->serial,
                                $equip->prefixo_veiculo,
                                $equip->modelo_veiculo,
                                $equip->modelo_equipamento,
                                $equip->fabricante,
                                $equip->tipo_frota,
                                '001/2020', //fixo para todos
                                lang('unico'), //fixo para todos
                                $secretaria,  //filiais
                                $data_desinstalacao,
                                $contagemDias,
                                $equip->valor_mensal ? $equip->valor_mensal : 0.0,
                                $valor_diario,
                                $equip->valor_instalacao ? $equip->valor_instalacao : 0.0,
                                $valorFaturado
                            );
                        }
                        echo json_encode(array('status' => true, 'valor_total' => 'R$ ' . number_format($valor_total, 2, ',', '.'), 'total' => count($table), 'tabela' => $table));
                    }
                } else {

                    if (!isset($atividades['err'])) {
                        echo json_encode(array('status' => false, 'msg' => "Falha ao buscar os dias rastreados na base de dados"));
                    } else {
                        echo json_encode(array('status' => false, 'msg' => $atividades['err']));
                    }
                }
            } else {
                echo json_encode(array('status' => false, 'msg' => lang('cliente_nao_possui_veiculos')));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => lang('datas_invalidas')));
        }
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> LISTA AS INFORMACOES DE ITENS DE CLIENTES
    */
    public function base_clientes()
    {
        $this->auth->is_allowed('vis_relatoriobasedeclientes');
        $dados['titulo'] = lang('relatorio') . ' - ' . lang('base_clientes');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/base_clientes'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/clientes/base_clientes');
        $this->load->view('fix/footer_NS.php');
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> RETORNA OS ITENS DE CLEINTES E SEUS DADOS
    */
    public function ajaxBaseClientesServeSide($export = false, $empresa = false, $orgao = false, $tipo_proposta = false, $cliente = false, $extensaoArq = false)
    {

        $table = array(); # Data DATATABLE
        $total_itens = 0;
        $dados = array();
        $draw = 1;
        $start = 0;
        $limit = 100;

        if ($export) {
            $dados = array(
                'export' => true,
                'empresa' => $empresa,
                'orgao' => $orgao,
                'tipo_proposta' => $tipo_proposta,
                'cliente' => $cliente
            );
        } else {
            $dados = $this->input->post();
            $dados['export'] = false;
            $draw = isset($dados['draw']) ? $dados['draw'] : 1;
            $start = isset($dados['start']) ? $dados['start'] : 0;
            $limit = isset($dados['length']) ? $dados['length'] : 100;
        }

        $select = '*';
        $descricao_item = '//';
        $retorno = array();

        if ($dados) {

            mb_internal_encoding('UTF-8');

            //CONSTROI O SELECT E DESCRICAO DO ITEM APARTIR DO TIPO DE PROPOSTA SELECIONADA
            if ($dados['tipo_proposta'] == 'veiculos') {
                $select = 'cli.id as id_cliente, cli.nome as nome_cliente, cli.cpf, cli.cnpj, cli.informacoes as prestadora, cli.orgao, cli.id_produto, veic.placa as item, veic.serial, con_veic.id_contrato as id_contrato, veic.data_instalacao as data';
                $descricao_item = lang('veiculo');
                $where = array('veic.status' => '1', 'veic.id_usuario !=' => null, 'veic.id_usuario !=' => '', 'veic.contrato_veiculo !=' => null, 'veic.contrato_veiculo !=' => '', 'con_veic.status' => 'ativo');
            } elseif ($dados['tipo_proposta'] == 'tornozeleiras') {
                $select = 'cli.id as id_cliente, cli.nome as nome_cliente, cli.cpf, cli.cnpj, cli.informacoes as prestadora, cli.orgao, cli.id_produto, tor.equipamento as item, tor.equipamento as serial, tor.id_contrato, tor.data_cadastro as data';
                $descricao_item = lang('tornozeleira');
                $where = array('tor.status' => 'ativo', 'tor.equipamento !=' => '', 'tor.id_cliente !=' => null, 'tor.id_cliente !=' => '', 'tor.id_contrato !=' => null, 'tor.id_contrato !=' => '');
            } elseif ($dados['tipo_proposta'] == 'chips') {
                $select = 'cli.id as id_cliente, cli.nome as nome_cliente, cli.cpf, cli.cnpj, cli.informacoes as prestadora, cli.orgao, cli.id_produto, chip.numero as item, chip.id_contrato_sim2m as id_contrato, chip.data_cadastro as data';
                $descricao_item = lang('chip');
                $where = array('chip.id_cliente_sim2m !=' => null, 'chip.id_cliente_sim2m !=' => '', 'chip.id_contrato_sim2m !=' => null, 'chip.id_contrato_sim2m !=' => '');
            } elseif ($dados['tipo_proposta'] == 'iscas') {
                $select = 'cli.id as id_cliente, cli.nome as nome_cliente, cli.cpf, cli.cnpj, cli.informacoes as prestadora, cli.orgao, cli.id_produto, isc.placa as item, isc.serial, isc.id_contrato, isc.data_cadastro as data';
                $descricao_item = lang('isca');
                $where = array('isc.status' => '1', 'isc.id_cliente !=' => null, 'isc.id_cliente !=' => '', 'isc.id_contrato !=' => null, 'isc.id_contrato !=' => '');
            }

            //BUSCA OS DADOS NO BANCO
            $listItens = $this->cliente->listBaseClientesServerSide(
                $select,
                $where,
                $start,
                $limit,
                $draw,
                $dados
            );

            if (is_array($listItens['itens']) && !empty($listItens['itens'])) {
                //MONTA ARRAY DE IDENTIFICADORES DE CLIENTES PARA CONSULTAS
                // $ids_clientes = array_unique(array_map(function($element) { return $element->id_cliente; }, $listItens['itens']));
                $ids_produtos = array_filter(array_unique(array_map(function ($element) {
                    return $element->id_produto;
                }, $listItens['itens'])));
                $produtos = array();
                if (is_array($ids_produtos) && !empty($ids_produtos)) {
                    $produtosList = $this->cliente->getProdutosByIds('id, nome', $ids_produtos);
                    foreach ($produtosList as $key => $prod) {
                        $produtos[$prod->id] = $prod->nome;
                    }
                }

                $ids_contratos = array_filter(array_unique(array_map(function ($element) {
                    return $element->id_contrato;
                }, $listItens['itens'])));
                $contratos = array();
                if (is_array($ids_contratos) && !empty($ids_contratos)) {
                    $contratosList = $this->contrato->getContratosByIds('id, tipo_proposta, status, valor_mensal, vencimento', $ids_contratos);
                    foreach ($contratosList as $key => $con) {
                        $contratos[$con->id] = $con;
                    }
                }

                foreach ($listItens['itens'] as $item) {
                    $id_contrato = $item->id_contrato;
                    $tipo_proposta = '//';
                    $status = '//';
                    $valor_mensal = '//';
                    $vencimento = '//';

                    if (isset($contratos[$id_contrato])) {

                        //PEGA O TIPO DE PROPOSTA DE CONTRATO
                        switch ($contratos[$id_contrato]->tipo_proposta) {
                            case '1':
                                $tipo_proposta = lang('chip_dados');
                                break;
                            case '2':
                                $tipo_proposta = lang('telemetria');
                                break;
                            case '3':
                                $tipo_proposta = lang('sigame_norioepp');
                                break;
                            case '4':
                                $tipo_proposta = lang('rastreador_pessoal');
                                break;
                            case '4':
                                $tipo_proposta = lang('gestor_entregas');
                                break;
                            case '6':
                                $tipo_proposta = lang('iscas');
                                break;
                            default:
                                $tipo_proposta = lang('gestor_rastreador');
                                break;
                        }

                        //PEGA O STATUS DO CONTRATO, VALOR MENSAL E VENCIMENTO DA FATURA
                        $status = status_contrato($contratos[$id_contrato]->status);
                        $valor_mensal = $contratos[$id_contrato]->valor_mensal;
                        $vencimento = $contratos[$id_contrato]->vencimento;
                    }

                    //PEGA O PRODUTO DO CLIENTE
                    $nome_produto = $item->id_produto && isset($produtos[$item->id_produto]) ? $produtos[$item->id_produto] : '//';

                    $table[] = array(
                        'plus' => '',         //para a coluna do botao "+" detalhes
                        'cliente' => $item->nome_cliente,
                        'cpf_cnpj' => $item->cpf ? $item->cpf : $item->cnpj,
                        'pf_pj' => $item->cpf ? lang('pessoa_fisica') : lang('pessoa_juridica'),
                        'prestadora' => getNomePrestadora($item->prestadora),
                        'orgao' => $item->orgao === 'privado' ? lang('privado') : lang('publico'),
                        'plano_disponivel' => $nome_produto,
                        'codigo_produto' => $id_contrato ? $id_contrato : '//',
                        'descricao_produto' => $tipo_proposta,
                        'status_contrato' => $status,
                        'valor_unitario' => $valor_mensal,
                        'vencimento_mensalidade' => $vencimento,
                        'item' => $item->item ? $item->item : '//',
                        'descricao_item' => $descricao_item,
                        'serial' => isset($item->serial) ? $item->serial : '//',
                        'data_ativacao' => date('d/m/Y', strtotime($item->data))
                    );
                }

                //EM CASO DE EXPORTAR EM EXCELL O CARREGAMENTO DOS DADOS SERA FEITO DO LADO DO SERVIDOR QUE FORNECERA APENAS O ARQUIVO .XLS JA PRONTO PARA A VIEW
                if ($export) {
                    $colunas = [
                        lang('cliente'),
                        lang('cpf') . '/' . lang('cnpj'),
                        lang('pf') . '/' . lang('pj'),
                        lang('prestadora'),
                        lang('orgao'),
                        lang('plano_disponivel'),
                        lang('codigo_produto'),
                        lang('descricao_produto'),
                        lang('status_contrato'),
                        lang('valor_unitario'),
                        lang('vencimento_mensalidade'),
                        lang('item'),
                        lang('descricao_item'),
                        lang('serial'),
                        lang('data_ativacao')
                    ];

                    $titulo = lang('relatorio') . ' - ' . lang('base_clientes');
                    $exportExcel = tableToExcell($titulo . '.xls', $colunas, $table, [0], $titulo, lang('total_itens') . ': ' . $listItens['recordsTotal']);
                    echo $exportExcel;
                    exit;

                    //SE NAO FOR EXPORTAR APENAS CARREGAR OS DADOS DA TABELA SERVE-SIDE
                } else {
                    echo json_encode(array('success' => true, 'draw' => intval($listItens['draw']), 'recordsTotal' =>  intval($listItens['recordsTotal']), 'recordsFiltered' =>  intval($listItens['recordsFiltered']), 'data' => $table, 'total_itens' => intval($listItens['recordsTotal'])));
                }
            } else {
                echo json_encode(array('success' => false, 'data' => array(), 'draw' => intval($draw) + 1, 'recordsTotal' => 0, 'recordsFiltered' => 0));
            }
        } else {
            echo json_encode(array('success' => false, 'msg' => lang('dados_faltosos')));
        }
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> LISTA AS INFORMACOES DE ITENS DE CLIENTES
    */
    public function custos_perfis_profissionais()
    {
        $this->auth->is_allowed('vis_custosdosperfisdeprofissionais');
        $dados['titulo'] = lang('relatorio_custos_consultas') . ' - ' . lang('show_tecnologia');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/custos_perfis_profissionais'));
        $dados['load'] = array('dataTable', 'select2');
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('omniscore/custos_perfis_profissionais');
        $this->load->view('fix/footer_NS');
    }

    /*
    * RETORNA O HISTORICO DE CONSULTAS DE PERFIS
    */
    public function ajax_custos_consultas_perfis()
    {
        $dataImput = $this->input->post();

        if ($dataImput) { # Se for passado os parâmetros
            if (strtotime($dataImput['data_inicial']) > strtotime($dataImput['data_final']))
                exit(json_encode(array('success' => false, 'msg' => lang('data_inicial_maior'))));


            $body = [
                'inicio'     => $dataImput['data_inicial'] . ' 00:00:00',
                'fim'        => $dataImput['data_final'] . ' 23:59:59',
                'idCliente'  => $dataImput['id_cliente']
            ];

            $resposta = json_decode(from_relatorios_api($body, "relatorios/custoConsultasPerfis"), true);
            if ($resposta['status'] == 1) {
                $dados = $resposta['dados'];
                exit(json_encode(array('success' => true, 'quantidadeTotal' => $dados['quantidadeTotal'], 'valorTotal' => 'R$ ' . number_format($dados['valorTotal'], 2, ',', '.'), 'table' => $dados['relatorio'])));
            } else {
                exit(json_encode(array('success' => false, 'msg' => $resposta['mensagem'])));
            }
        } else {
            exit(json_encode(array('success' => false, 'msg' => lang('parametros_invalidos'))));
        }
    }


    /*
    * CARREGA VIEW DE RELATÓRIO >> LISTA AS INFORMACOES DE ITENS DE CLIENTES SINTETIZADOS
    */
    public function base_clientes_sintetizada()
    {
        $this->auth->is_allowed('vis_relatoriobasedeclientes');
        $dados['titulo'] = lang('relatorio') . ' - ' . lang('base_clientes');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/base_clientes_sintetizada'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/clientes/base_clientes_sintetizada');
        $this->load->view('fix/footer_NS');
    }

    /*
    * CARREGA VIEW DE RELATÓRIO >> RETORNA OS ITENS DE CLEINTES E SEUS DADOS
    */
    public function ajaxBaseClientesServeSideSintetizada($export = false, $empresa = false, $orgao = false, $cliente = false, $extensaoArq = false)
    {

        $table = array(); # Data DATATABLE
        $total_itens = 0;
        $dados = array();
        $draw = 1;
        $start = 0;
        $limit = 100;

        if ($export) {
            $dados = array(
                'export' => true,
                'empresa' => $empresa,
                'orgao' => $orgao,
                'cliente' => $cliente
            );
        } else {
            $dados = $this->input->post();
            $dados['export'] = false;
            $draw = isset($dados['draw']) ? $dados['draw'] : 1;
            $start = isset($dados['start']) ? $dados['start'] : 0;
            $limit = isset($dados['length']) ? $dados['length'] : 100;
        }
        // echo json_encode(array("draw" => $draw, "start" => $start, "limit" => $limit));
        // return;

        $select = '*';
        $descricao_item = '//';
        $retorno = array();

        if ($dados) {

            mb_internal_encoding('UTF-8');

            //CONSTROI O SELECT E DESCRICAO DO ITEM APARTIR DO TIPO DE PROPOSTA SELECIONADA
            $select = 'cli.ID,
                con.tipo_proposta as Faturamento,
                cli.Nome as Clientes,
                cli.cpf as CPF, 
                cli.cnpj as CNPJ, 
                cli.orgao AS orgao, 
                con.quantidade_veiculos as Contrato,
                con.valor_mensal as Valor_unit,
                con.quantidade_veiculos * con.valor_mensal as valor_contratado,
                con.quantidade_veiculos - COUNT(con_veic.id_cliente) as veiculos_restantes,
                (con.quantidade_veiculos - COUNT(con_veic.id_cliente)) * con.valor_mensal as total,
                COUNT(con_veic.id_cliente) as veiculos_faturados,
                COUNT(con_veic.id_cliente) * con.valor_mensal as valor_total,
                con.vencimento as prazo_pagamento';

            $descricao_item = lang('veiculo');

            $where = array(
                'veic.status' => '1',
                'veic.id_usuario !=' => null,
                'veic.id_usuario !=' => '',
                'veic.contrato_veiculo !=' => null,
                'veic.contrato_veiculo !=' => '',
                'con_veic.status' => 'ativo'
            );

            //BUSCA OS DADOS NO BANCO
            $listItens = $this->cliente->listBaseClientesSintetizado(
                $select,
                $where,
                $start,
                $limit,
                $draw,
                $dados
            );

            if (is_array($listItens['itens']) && !empty($listItens['itens'])) {
                foreach ($listItens['itens'] as $item) {
                    $faturamento = buscarFaturamento($item->Faturamento);

                    $table[] = array(
                        'ID' => $item->ID,
                        'Faturamento' => $faturamento,
                        'cliente' => $item->Clientes,
                        'Documento' => $item->CNPJ ? $item->CNPJ : $item->CPF,
                        'pf_pj' => $item->CNPJ ? lang('pessoa_juridica') : lang('pessoa_fisica'),
                        'orgao' => $item->orgao,
                        'Contrato' => $item->Contrato,
                        'Valor_unit' => $item->Valor_unit,
                        'valor_contratado' => $item->valor_contratado,
                        'veiculos_restantes' => $item->veiculos_restantes,
                        'total' => $item->total,
                        'veiculos_faturados' => $item->veiculos_faturados,
                        'valor_total' => $item->valor_total,
                        'prazo_pagamento' => $item->prazo_pagamento,
                    );
                }

                //EM CASO DE EXPORTAR EM EXCELL O CARREGAMENTO DOS DADOS SERA FEITO DO LADO DO SERVIDOR QUE FORNECERA APENAS O ARQUIVO .XLS JA PRONTO PARA A VIEW
                if ($export) {
                    $colunas = [
                        'ID',
                        'Faturamento',
                        lang('cliente'),
                        lang('cpf') . '/' . lang('cnpj'),
                        lang('pf') . '/' . lang('pj'),
                        'orgao',
                        'Contrato',
                        'Valor_unit',
                        'valor_contratado',
                        'veiculos_restantes',
                        'total',
                        'veiculos_faturados',
                        'valor_total',
                        'prazo_pagamento',
                    ];

                    $titulo = lang('relatorio') . ' - ' . lang('base_clientes') . ' - sintetizada';
                    $exportExcel = tableToExcell($titulo . '.xls', $colunas, $table, array(), $titulo, lang('total_itens') . ': ' . $listItens['recordsTotal']);
                    echo $exportExcel;
                    exit;

                    //SE NAO FOR EXPORTAR APENAS CARREGAR OS DADOS DA TABELA SERVE-SIDE
                } else {
                    echo json_encode(array('success' => true, 'draw' => intval($listItens['draw']), 'recordsTotal' =>  intval($listItens['recordsTotal']), 'recordsFiltered' =>  intval($listItens['recordsFiltered']), 'data' => $table, 'total_itens' => intval($listItens['recordsTotal'])));
                }
            } else {
                echo json_encode(array('success' => false, 'data' => array(), 'draw' => intval($draw) + 1, 'recordsTotal' => 0, 'recordsFiltered' => 0));
            }
        } else {
            echo json_encode(array('success' => false, 'msg' => lang('dados_faltosos')));
        }
    }

    public function relatorio_acessos()
    {
        $this->auth->is_allowed_block('vis_relatorio_acessos');
        $dados['titulo'] = lang('relatorio_acessos');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/relatorio_acessos');
        $this->load->view('fix/footer_NS');
    }

    public function listarRelatorioAcesso()
    {

        $retorno = get_listarMapaCalor();

        echo json_encode($retorno);
    }

    public function listaUsuariosAtivosMapa()
    {
        $retorno = array('pagination' => [
            'more' => false
        ]);

        $retorno = listarUsuariosAtivoMapa();

        foreach ($retorno['results'] as $key => $value) {
            $retorno['results'][$key] = array(
                'id' => $value['emailUsuario'],
                'text' => $value['emailUsuario']
            );
        }

        if (!empty($retorno)) {
            echo json_encode($retorno);
        } else {
            return false;
        }
    }


    public function listarMapaCalorByUserOrData()
    {
        $mes = $this->input->post('mes');
        $ano = $this->input->post('ano');
        $periodo = $this->input->post('periodo');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');
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
        } else {
            if ($dataInicial && $dataFinal) {
                $dataInicial = str_replace("-", "/", $dataInicial);
                $dataInicial = date('d/m/Y', strtotime($dataInicial));
                $dataFinal = str_replace("-", "/", $dataFinal);
                $dataFinal = date('d/m/Y', strtotime($dataFinal));
            }
        }

        $dados = get_mapaCalorByUserOrData($dataInicial, $dataFinal);

        echo json_encode($dados);
    }

    public function ajax_clientesRel()
    {
        $prestadora = $this->input->get('prestadoraCliente');
        $status = $this->input->get('statusCliente');
        $statusContrato = $this->input->get('statusContratoClientes');
        $orgao = $this->input->get('orgaoCliente');

        $table = array();

        $where_clie = array('clie.informacoes' => $prestadora);
        $select_clie = 'clie.id as idCliente, clie.nome, clie.cpf, clie.cnpj, clie.status, clie.informacoes as prestadora, clie.orgao, clie.data_cadastro';

        if ($orgao) {
            $where_clie['clie.orgao'] = $orgao;
        }

        if (is_numeric($status)) {
            $where_clie['clie.status'] = $status;
        }

        if (is_numeric($statusContrato)) {
            $where_clie['cont.status'] = $statusContrato;
        }

        $clientes = $this->cliente->get_clientesRel($select_clie, $where_clie);

        if (count($clientes) > 0) {
            foreach ($clientes as $key => $cliente) {
                $documentoCliente = $cliente->cpf ? $cliente->cpf : $cliente->cnpj;

                $table[] = array(
                    $cliente->idCliente,
                    $cliente->nome,
                    $documentoCliente ? $documentoCliente : '-',
                    $cliente->prestadora ? ($cliente->prestadora == 'TRACKER' ? 'SHOW TECNOLOGIA' : $cliente->prestadora) : '-',
                    $cliente->orgao ? ($cliente->orgao == 'privado' ? 'Privado' : 'Público') : '-',
                    $cliente->data_cadastro ? date('d/m/Y', strtotime($cliente->data_cadastro)) : '-',
                    $cliente->num_contratos ? $cliente->num_contratos : 0,
                    $cliente->num_veiculos_contrato ? $cliente->num_veiculos_contrato : '-',
                    $cliente->num_veiculos_ativos ? $cliente->num_veiculos_ativos : 0.00,
                    $cliente->valor_contratado,
                    $cliente->valor_total ? $cliente->valor_total : 0.00,
                    $cliente->valor_instalacao
                );
            }

            echo json_encode(array('status' => 200, 'tabela' => $table));
        } else {
            echo json_encode(array('status' => 'ERRO', 'msg' => 'Dados não encontrados para os parâmetros informados!'));
        }
    }


    public function UltimosAcessosWSTT()
    {
        $this->auth->is_allowed('vis_ultimos_acessos');

        $this->mapa_calor->registrar_acessos_url(site_url('/relatorios/UltimosAcessosWSTT'));

        $dados['titulo'] = 'Últimos Acessos WSTT';
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/ultimos_acessos_wstt');
        $this->load->view('fix/footer_NS');
    }

    public function buscarUltimosAcessosServerSide()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $email = $this->input->post('email');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');

        $startRow++;

        $dados = array();

        if ($dataInicial) $dataInicial = data_for_humans($dataInicial);
        if ($dataFinal) $dataFinal = data_for_humans($dataFinal);

        $dados = get_UltimosAcessosWSTTPaginated($email, $dataInicial, $dataFinal, $startRow, $endRow);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "status" => $dados['status'],
                "rows" => $dados['resultado']['acessos'],
                "lastRow" => $dados['resultado']['qtdTotalAcessos']
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "status" => $dados['status'],
                "message" => $dados['resultado']['mensagem'],
            ));
        }
    }

    public function relPlacas(){
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $cliente = $this->input->post('idCliente');
        
        $startRow++;

        $url = "relatorios/placas?itemInicio=". $startRow . "&itemFim=" . $endRow;

        if (isset($cliente) && $cliente){
            $url .= '&idCliente=' . $cliente;
        }

        $dados = $this->to_get($url);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "status" => $dados['status'],
                "rows" => $dados['resultado']['contratos'],
                "lastRow" => $dados['resultado']['qtdTotalContratos']
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "status" => $dados['status'],
                "message" => $dados['resultado']['mensagem'],
            ));
        }
    }

    public function clientesSelect2() {
        $itemInicio = $this->input->post('itemInicio');
        $itemFim = $this->input->post('itemFim');
        $nome = $this->input->post('searchTerm');
        $id = $this->input->post('id');

        $dados = $this->get_clientesGerais($itemInicio, $itemFim, $nome, $id);

        echo json_encode($dados);
    }

    private function get_clientesGerais($itemInicio, $itemFim, $nome, $id){
        $url = ('clienteVendas/listarClientesPorParametrosPaginado?itemInicio='.$itemInicio . '&itemFim='. $itemFim);
        if (isset($nome) && $nome){
            $url.= '&nome=' . urlencode($nome);
        }
        if (isset($id) && $id){
            $url = ('clienteVendas/listarClientesPorParametrosPaginado?idCliente='.$id);
        }
        return $this->to_get($url);
    }

    private function to_get($url){
        $CI = &get_instance();
    
        $request = $CI->config->item('url_api_shownet_rest').$url;
    
        $token = $CI->config->item('token_api_shownet_rest');
    
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
        ));
    
        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    
        return array(
            'status' => $statusCode,
            'resultado' => $resultado
        );
    }
}

function buscarFaturamento($faturamentoID)
{

    $faturamento = "";
    switch ($faturamentoID) {
        case 0:
            $faturamento = "Contrato SHOW";
            break;
        case 1:
            $faturamento = "Contrato SIMM2M";
            break;
        case 2:
            $faturamento = "Contrato SHOW TELEMETRIA";
            break;
        case 3:
            $faturamento = "Contrato NORIO";
            break;
        case 4:
            $faturamento = "Contrato TORNOZELEIRA";
            break;
        case 5:
            $faturamento = "Contrato Roteirização";
            break;
        case 6:
            $faturamento = "Contrato Iscas";
            break;
        case 7:
            $faturamento = "Contrato Licenciamento Software";
            break;
    }
    return $faturamento;
}

function clientesSelect2() {
    $itemInicio = $this->input->post('itemInicio');
    $itemFim = $this->input->post('itemFim');
    $nome = $this->input->post('searchTerm');
    $id = $this->input->post('id');

    $dados = get_clientesGerais($itemInicio, $itemFim, $nome, $id);

    echo json_encode($dados);
}