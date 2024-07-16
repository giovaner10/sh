<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cliente');
        $this->load->model('fatura');
        $this->load->model('veiculo');
        $this->load->model('envio_fatura');
        $this->load->model('sender');
        $this->load->model('ordem_servico', 'os');
        $this->load->model('layout_email');
        $this->auth->is_logged('admin');
        $this->load->model('auth');
    }

    public function index() {
        echo 'isto é uma cron, safadinho!';
    }

    /*
     * Gera os pontos mensais do cliente com vencimento no dia atual
     * (Leva em consideração o plano atual do cliente)
     */
    public function monthlyPointsByDay() {
        // Carrega Model Contrato
        $this->load->model('cliente');

        // Lista de contratos com o vencimento no dia atual
        $contratos = $this->contrato->listar(array('ctr.vencimento' => date('d'), 'ctr.tipo_proposta' => 5, 'ctr.status !=' => 3, 'ctr.status !=' => 5, 'ctr.status !=' => 6));

        if ($contratos) {
            foreach ($contratos as $contrato) {
                // Insere a quantidade do pacote na carteira do cliente
                $this->contrato->insertMonthlyPointByClient($contrato->id_cliente, $contrato->quantidade_veiculos);
            }
        }
    }

    function gerarCaixaConsolidado() {
        $mes = date('m');
        $ano = date("Y");
        $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));

        if (date('d') == $ultimo_dia) {
            $empresas = array('2' => 'showtecsystem.fluxo_eua', '0' => 'showtecsystem.fluxo_caixa', '1' => 'showtecsystem.fluxo_norio');

            foreach ($empresas as $key => $empresa) {
                $this->load->model('conta');
                $dados = $this->conta->get_fluxo($empresa);

                if ($dados) {
                    if ($dados->caixa_consolidado)
                        $this->conta->addCaixaConsolidado($key, $dados->caixa_consolidado);
                    else
                        $this->conta->addCaixaConsolidado($key, '0');
                } else {
                    $this->conta->addCaixaConsolidado($key, '0');
                }
            }
        }

    }

    // public function enviar_faturas() {
    // 	$this->load->library('parser');
    // 	$this->load->helper('file');
    // 	$envio_pendentes = $this->envio_fatura->listar(array('envio.status_envio' => 'pendente', 'email2 !=' => ''), 0, 2);
    // 	if(count($envio_pendentes) > 0) {
    // 		foreach ($envio_pendentes as $retorno) {
    // 			if ($retorno->email2 != '') {
    // 				$data = array(
    // 					'cliente' => $retorno->nome,
    // 					'link_fatura' => 'https://gestor.showtecnologia.com/sistema/newapp/index.php/cliente/faturas/view_email/'.base64_encode($retorno->id_fatura)
    // 				);
    // 				$emails = explode(';', $retorno->email2);
    // 				$mensagem = $this->parser->parse('template/emails/fatura', $data, true);
    // 				$dados['faturas'] = $this->fatura->listar("cad_faturas.Id = {$retorno->id_fatura}", 0, 1, 'data_vencimento', 'ASC');
    // 				$boleto = $this->load->view('faturas/imprimir_fatura', $dados, TRUE);
    // 				$name_anexo = 'uploads/faturas/'.time().'.html';
    // 				write_file($name_anexo, $boleto);

    // 				$envio = $this->sender->enviar_email('financeiro@showtecnologia.com', 'Show Tecnologia', $emails,
    // 					'Fatura para Pagamento #'.$retorno->id_fatura, $mensagem, '', false, $name_anexo);
    // 				if ($envio)  {
    // 					$now = date('Y-m-d H:i:s');
    // 					$d_envio = array('status_envio' => 'enviado','emails_envio' => $retorno->email2,'dh_envio' => $now, 'msg_envio' => 'envio automático', 'path_anexo' => $name_anexo);
    // 					$this->envio_fatura->atualizar($retorno->id_envio, $d_envio);
    // 					$this->fatura->atualizar_fatura($retorno->id_fatura, array('status' => 0));
    // 				}
    // 			} else {
    // 				$d_envio = array('msg_envio' => 'Cliente sem email cadastrado.');
    // 				$this->envio_fatura->atualizar($retorno->id_envio, $d_envio);
    // 			}
    // 		}
    // 	}
    // }

    // public function gerar_fatura_dia($mes = false, $data = false) {
    // 	if(!$mes) $mes = date('m');
    // 	$today = date('d');
    // 	$clientes = $this->cliente->listar(array('status' => 1, 'data_cadastro like' => '%-'.$today.' %'));
    // 	if(count($clientes) > 0) {
    // 		foreach ($clientes as $cliente) {
    // 			if ($cliente->fatura_consumo == 0) {
    // 				$this->fatura->gerar_fatura_clientes($cliente->id, $mes, $data);
    // 			} else {
    // 				$this->fatura->gerar_fatura_consumo($cliente->id, $mes, $data);
    // 				echo $cliente->nome."\n";
    // 			}
    // 		}
    // 	}
    // }

    function gerarFaturas() {
        // BUSCA TODOS OS CONTRATOS COM VENCIMENTOS +5 DAYS DATA_ATUAL
        $contratos_dia = $this->fatura->getFaturas_by_dia();
        var_dump($contratos_dia);
        if(count($contratos_dia)){
            foreach ($contratos_dia as $contrato) {
                $total = $contrato->quantidade_veiculos * $contrato->valor_mensal;
                $d_inicio = date('Y-m-d', strtotime("-".(intval(date('d'))-1)." days", strtotime(date('Y-m-d', strtotime("-1 months", strtotime(date('Y-m-d')))))));
                $d_final = date('Y-m-d', strtotime("-".(intval(date('d')))." days", strtotime(date('Y-m-d'))));
                $vencimento = date('Y-m-d', strtotime("+30 days", strtotime(date('Y-m-d'))));
                $taxa=0;
                if ($contrato->boleto == 1) {
                    $taxa=4.50;
                }
                $t1 = 'Locação de módulos para rastreamento veicular {'.$contrato->quantidade_veiculos.' unidade(s)}, fatura gerada automaticamente';
                //$t1='FATURA AUTOMÁTICA';
                $t3='Contrato';
                if($contrato->pais=="EUA"){
                    $t1='AUTOMATIC INVOICE';
                    $t3='Contract';
                }

                $iss=0;
                if($contrato->ISS!=null){
                    $iss=$contrato->ISS;
                }

                $irpj=0;
                if($contrato->IRPJ!=null){
                    $irpj=$contrato->IRPJ;
                }

                $csll=0;
                if($contrato->Cont_Social!=null){
                    $csll=$contrato->Cont_Social;
                }

                $pis=0;
                if($contrato->PIS!=null){
                    $pis=$contrato->PIS;
                }

                $confins=0;
                if($contrato->COFINS!=null){
                    $confins=$contrato->COFINS;
                }
                $descricao = '['.$t3.' '.$contrato->id.'] '.$t1;
                $dados = array(
                    'descricao' => $descricao,
                    'id_contrato' => '',
                    'id_cliente' => $contrato->id_cliente,
                    'data_vencimento' => $vencimento,
                    'boleto_vencimento' => $vencimento,
                    'valor_boleto' => $total,
                    'quantidade' => $contrato->quantidade_veiculos,
                    'valor_total' => $total,
                    'valor_unitario' => $contrato->valor_mensal,
                    'total_fatura' => $total,
                    'taxa_boleto' => $taxa,
                    'data_emissao' => date('Y-m-d'),
                    'status' => 2,
                    'periodo_inicial' => $d_inicio,
                    'periodo_final' => $d_final,
                    'mes_referencia' =>date('m/Y', strtotime("-".(intval(date('d')))." days", strtotime(date('Y-m-d'))))
                );
                var_dump($dados);
                $id_fatura = $this->fatura->gerarFaturaDia($dados, $contrato->tipo_proposta, $descricao);

                if ($id_fatura) {
                    $this->fatura->gravaNumFatura($id_fatura);
                    if ($contrato->boleto == 1) {
                        $this->fatura->gerarTaxaBoleto($contrato->id_cliente, $id_fatura, $vencimento);
                    }
                }
            }
        }
        $this->gerarFaturasProporcional();
    }

    function gerarFaturasProporcional() {
        // BUSCA TODOS OS CONTRATOS COM VENCIMENTOS +5 DAYS DATA_ATUAL
        $contratos_dia = $this->fatura->getFaturas_by_dia(true);
        var_dump($contratos_dia);
        if(count($contratos_dia)){
            foreach ($contratos_dia as $contrato) {
                $dias = 30;
                $d_inicio = date('Y-m-d', strtotime("-".(intval(date('d'))-1)." days", strtotime(date('Y-m-d', strtotime("-1 months", strtotime(date('Y-m-d')))))));
                $d_final = date('Y-m-d', strtotime("-".(intval(date('d')))." days", strtotime(date('Y-m-d'))));
                $vencimento = date('Y-m-d', strtotime("+30 days", strtotime(date('Y-m-d'))));

                $result = $this->veiculo->resumo_disponiveis($d_inicio,$d_final,false,$contrato->id)['result'];

                $valor_total = 0;
                if($result){
                    foreach ($result as $key=>$disponivel){
                        $valor_total+=$disponivel->valor_total_arredondado;
                    }
                }

                $taxa=0;
                if ($contrato->boleto == 1) {
                    $taxa=4.50;
                }
                $t1 = 'Locação de módulos para rastreamento veicular, fatura proporcional';
                //$t1='FATURA PROPORCIONAL';
                $t2='Detalhar';
                $t3='Contrato';
                if($contrato->pais=="EUA"){
                    $t1='PROPORTIONAL INVOICE';
                    $t2='Detail';
                    $t3='Contract';
                }

                $iss=0;
                if($contrato->ISS!=null){
                    $iss=$contrato->ISS;
                }

                $irpj=0;
                if($contrato->IRPJ!=null){
                    $irpj=$contrato->IRPJ;
                }

                $csll=0;
                if($contrato->Cont_Social!=null){
                    $csll=$contrato->Cont_Social;
                }

                $pis=0;
                if($contrato->PIS!=null){
                    $pis=$contrato->PIS;
                }

                $confins=0;
                if($contrato->COFINS!=null){
                    $confins=$contrato->COFINS;
                }

                $descricao = '['.$t3.' '.$contrato->id.'] '.$t1.' (<a href="https://gestor.showtecnologia.com/sistema/newapp/index.php/api/resumoVeiculosDisponiveis?di='.urlencode(data_for_humans($d_inicio)).'&df='.urlencode(data_for_humans($d_final)).'&id_cliente='.$contrato->id_cliente.'">'.$t2.'</a>)';
                $dados = array(
                    'id_cliente' => $contrato->id_cliente,
                    'id_contrato' => '',
                    'data_vencimento' => $vencimento,
                    'boleto_vencimento' => $vencimento,
                    'valor_boleto' => $valor_total,
                    'quantidade' => $contrato->quantidade_veiculos,
                    'valor_total' => $valor_total,
                    'valor_unitario' => $contrato->valor_mensal,
                    'total_fatura' => $valor_total,
                    'taxa_boleto' => $taxa,
                    'data_emissao' => date('Y-m-d'),
                    'status' => 2,
                    'periodo_inicial' => $d_inicio,
                    'periodo_final' => $d_final,
                    'mes_referencia' =>date('m/Y', strtotime("-".(intval(date('d')))." days", strtotime(date('Y-m-d')))),
                    'iss'=>$iss,
                    'irpj'=>$irpj,
                    'csll'=>$csll,
                    'pis'=>$pis,
                    'cofins'=>$confins
                );
                var_dump($dados);
                $id_fatura = $this->fatura->gerarFaturaDia($dados, $contrato->tipo_proposta, $descricao);

                if ($id_fatura) {
                    $this->fatura->gravaNumFatura($id_fatura);
                    if ($contrato->boleto == 1) {
                        $this->fatura->gerarTaxaBoleto($contrato->id_cliente, $id_fatura, $vencimento);
                    }
                }
            }
        }
        while($this->fatura->migrar_todas());
    }

    public function migrar_dados_tabela() {
        $this->load->helper('file');
        inicio_app:
        $inicio = $this->db->get_where('systems.id_temp', array('id' => 1))->result();
        $ult_id = false;
        if($inicio){
            $query = $this->db->order_by('CODE', 'DESC')->get_where('systems.resposta1_prob', array('CODE <=' => $inicio[0]->temp), 500);
            if ($query->num_rows() > 0) {
                $d_bd = array();
                foreach ($query->result() as $resposta) {
                    $ult_id = $resposta->CODE;
                    unset($resposta->CODE);
                    foreach($resposta as $key => $valor) {
                        if ($valor != NULL)
                            $d_bd[$key] = $valor;
                    }
                    $this->db->insert('systems.resposta1', $d_bd);
                    $ult_data = $resposta->DATA;
                    echo $ult_id.' - '.$resposta->DATA."\r\n";
                }
                if($this->db->affected_rows() > 0) {
                    $now = date('Y-m-d H:i:s');
                    $this->db->update('systems.id_temp', array('temp' => $ult_id, 'data' => $now, 'datasys' => $ult_data), array('id' => 1));
                }
                goto inicio_app;
            } else {
                write_file('id.txt', 'Nenhum registro encontrado: '.date('Y-m-d H:i:s')."\r\n", 'a+');
                echo 'nenhum registro encontrado';
            }
        }
    }

    public function migrar_dados_serial($serial, $data_ini, $data_fim) {
        $this->load->helper('file');
        inicio_app:
        $ult_id = false;
        $query = $this->db->order_by('DATA', 'DESC')
            ->get_where('systems.resposta1_prob',
                array('ID' => $serial, 'DATA >=' => $data_ini.' 00:00:00',
                    'DATA <=' => $data_fim.' 23:59:59'));

        if ($query->num_rows() > 0) {
            $d_bd = array();
            foreach ($query->result() as $resposta) {
                $ult_id = $resposta->CODE;
                unset($resposta->CODE);
                foreach($resposta as $key => $valor) {
                    if ($valor != NULL)
                        $d_bd[$key] = $valor;
                }
                $this->db->insert('systems.resposta1', $d_bd);
                echo $resposta->ID.' - '.$resposta->DATA."\r\n";
            }

            if($this->db->affected_rows() > 0) {
                $now = date('Y-m-d H:i:s');
                echo "Todos os dados foram inseridos com sucesso. \r\n";
                exit();
            }
            goto inicio_app;
        } else {
            echo 'Nenhum registro encontrado'."\r\n";
        }
    }

    public function lembrete_fatura($qtd_dias) {
        $hoje = date('Y-m-d');
        $vencimento = date('Y-m-d', strtotime("{$hoje} +{$qtd_dias} days"));
        $faturas = $this->fatura->listar("(cad_faturas.data_vencimento = '{$vencimento}' OR
										  cad_faturas.dataatualizado_fatura = '{$vencimento}') AND
										  cad_faturas.status IN (0,2) AND
										  cad_faturas.numero >= 30000
										  ", 0, 999999);
        if($faturas) {
            foreach($faturas as $fatura) {
                $now = date('Y-m-d H:i:s');
                $d_fatura[] = array('id_fatura' => $fatura->numero_fatura, 'dhcad_envio' => $now);
            }
            $this->envio_fatura->inserir($d_fatura);
        }
    }

    public function lembrete_fatura_atrasada() {
        $hoje = date('Y-m-d');
        $d_fatura = array();
        $faturas = $this->fatura->listar("(cad_faturas.data_vencimento < '{$hoje}' OR
										cad_faturas.dataatualizado_fatura < '{$hoje}') AND
										cad_faturas.status IN (0,2) AND
										cad_faturas.numero >= 30000
										", 0, 100000);

        if($faturas) {
            foreach($faturas as $fatura) {
                $agora = new DateTime($hoje);
                $vencimento = new DateTime($fatura->data_vencimento);
                $diff = $agora->diff($vencimento);
                if ($diff->days > 2) {
                    $now = date('Y-m-d H:i:s');
                    $d_fatura[] = array('id_fatura' => $fatura->numero_fatura, 'status_envio' => 'enviado',
                        'dhcad_envio' => $now);

                    $data = array(
                        'id_fatura' => $fatura->Id,
                        'data_vencimento' => data_for_humans($fatura->data_vencimento),
                        'cliente' => $fatura->nome,
                        'link_fatura' => 'https://gestor.showtecnologia.com/sistema/newapp/index.php/cliente/faturas/view_email/'.base64_encode($fatura->Id)
                    );
                    //$emails = explode(';', $faturas->email2);
                    $emails2 = str_replace(';', ',', $fatura->email2);
                    $mensagem = $this->parser->parse('template/emails/fatura_atrasada', $data, true);
                    //echo $fatura->Id.' - '.$fatura->status_fatura."\n";
                    enviar_email('Fatura em atraso #'.$fatura->id_fatura,
                        $mensagem, $emails2, 'faturas');
                }
            }
            if (count($d_fatura))
                $this->envio_fatura->inserir($d_fatura);
        }
    }

    /*
    Salva na tabela estatisticas_faturas as porcentagens referentes ao mes atual, anterior e dos ultimos 12 meses.
    */
    public function salvar_porcentagens() {
        $this->fatura->salvar_porcentagens($this->fatura->calcular_porcentagens());
    }

    /* RETORNA A QUANTIDADE DE DIAS QUE TEM NO MÊS - DEVELOPER BY ANDRÉ GOUVEIA*/
    private function qtd_dias_mes(){
        $dias = null;
        $mes = date('m');
        $ano = date('y');

        if ($mes == '04' || $mes == '06' || $mes == '09' || $mes == '11'){
            $dias = 30;
        }elseif ($mes == '01' || $mes == '03' || $mes == '05' || $mes == '07' || $mes == '08' || $mes == '10' || $mes == '12'){
            $dias = 31;
        }elseif($mes == '02' && (($ano % 4) == 0 && ($ano % 100) != 0) || ($ano % 400) == 0 ){//VERIFICA SE O ANO É BISSEXTO
            $dias = 29;
        }else{
            $dias = 28;
        }
        return $dias;
    }

    /* RETORNA OS CLIENTES/CONTRATOS FATURADOS POR CONSUMO - DEVELOPER BY ANDRÉ GOUVEIA*/
    private function contratos_by_consumo(){
        return $this->cliente->list_consum();
    }

    /* RETORNA OS CLIENTES/CONTRATOS ATIVOS DO DIA ATUAL - DEVELOPER BY ANDRÉ GOUVEIA*/
    private function clientes_by_day(){
        return $this->cliente->list_by_day();
    }

    /* RECUPERA E SALVA AS INFORMAÇÕES DO CONSUMO POR VEICULO - DEVELOPER BY ANDRÉ GOUVEIA*/
    public function gerar_fatura_car() {
        $clientes = $this->contratos_by_consumo();
        $dias = $this->qtd_dias_mes();
        $placas = [];
        if ($clientes != ""){
            foreach ($clientes as $cliente){
                if ($cliente->consumo_fatura == 1){
                    $contratos = $this->fatura->get_number_contrato($cliente->id);
                    foreach ($contratos as $contrato){
                        $dados_fatura = $this->fatura->get_dados_contrato($dias, $cliente->id, $contrato->id);
                        if ($dados_fatura['fatura'][0]->id_contrato != "")
                            try {
                                $this->fatura->set_consumo_fatura($dados_fatura, $dados_fatura['placas_ativas']);
                            } catch (Exception $e) {
                                echo 'Exceção capturada: ',  $e->getMessage(), "\n";
                            }
                    }
                }
            }
        }
    }

    public function get_dados_fatura_disp(){
        $qtd_dias = $this->qtd_dias_mes();
        $clientes = $this->clientes_by_day();
        $mesAnterior = date('Y-m-d', strtotime('-'.$qtd_dias.' days'));
        foreach ($clientes as $cliente){
            $contratos = $this->fatura->get_number_contrato($cliente->id);
            foreach ($contratos as $contrato) {
                $interval = diff_entre_datas($cliente->data_init_consumo, $mesAnterior);
                $inicioCobranca = $interval < 28 ? $cliente->data_init_consumo : $mesAnterior;
                $dadosConsumo = $this->fatura->get_dados_consumo($inicioCobranca, $contrato->id, $cliente->id);
                if ($dadosConsumo){
                    pr($dadosConsumo);
                }
            }
        }
    }

    public function envia_faturasPendentes() {
        $faturas = $this->fatura->get_faturasPendentesEnv();
        foreach ($faturas as $fatura) {
            // ENVIA EMAIL PARA O CLIENTE
            $this->envio_fatura->envia_fatura($fatura->Id, $fatura->id_cliente);
            // ATUALIZA STATUS DO ENVIO DA FATURA
            $this->fatura->atualizar_fatura($fatura->Id, array('status' => 0));
        }
    }

    public function gerarConsumo() {
        $clientes = $this->clientes_by_day();
        foreach ($clientes as $cliente) {
            $this->fatura->gerarConsumo($cliente->id);
        }
    }

    //Gabriel Bandeira
    // public function veiculoDisponiveis(){
    //     $input = $this->input->post('data');
    //     $this->veiculo->getVeiculoDisponiveis($input);
    // }

    /*
    * PEGA AS ATIVIDADES DOS VEICULOS DO DIA ANTERIOR NA TABELA LAST_TRACK E CALCULA O VALOR DIARIO,
      PEGA O CLIENTE, CONTRATO, USUARIOS, PLACA, SERIAL E ULTIMA COMUNICACAO.
      E INSERE ESTES CALCULOS NA TABELA DISPONIBILIDADE_VEICULO.
      - ESTE PROCESSO É FEITO TODOS OS DIAS, CALCULANDO O DIA ANTERIOR
    */
    public function veiculoDisponiveis(){
        $this->load->model('usuario_gestor');

        $data = date('Y-m-d  H:i:s', strtotime("-1 days", strtotime(date('Y-m-d  H:i:s'))));
        if ($this->input->post('data'))
            $data = $this->input->post('data');  //data no formato 'Y-m-d H:i:s, referente à data do dia anterior'

        //ve se ja foi inserido para aquele dia
        $verifica = $this->veiculo->getVeiculoDisponiveis('date(datahora) as data', date('Y-m-d') );
        if( !$verifica || count($verifica) <= 0 ){
            //PEGA AS ATIVIDADE/RASTREAMENTO
            $rastreamentos = $this->veiculo->getRastreamento( 'DATA as data, ID as serial, ID_OBJECT_TRACKER as placa', explode(' ', $data)[0] );

            $rastreios = array();
            $placas = array();
            $insert = array();
             if ($rastreamentos) {
                 foreach ($rastreamentos as $key => $rastreamento) {
                     $placas[] = $rastreamento->placa;
                     $insert[$rastreamento->placa] = array(
                             'placa' => $rastreamento->placa,
                             'ultima_comunicacao' => $rastreamento->data,
                             'serial'=>$rastreamento->serial,
                             'id_cliente' => null,
                             'id_contrato'=> null
                         );
                 }
                 $rastreamentos=array();  //LIMPA OS DADOS, LIBERANDO ESPACO DE MEMORIA

                 //PEGA OS DADOS DOS VEICULOS - ID_CLIENTE E ID_CONTRATO
                 $clientes = array();
                 $contratos = array();
                 $infoVeiculos = $this->veiculo->listInfoPlacas($placas);
                 if ($infoVeiculos) {
                     foreach ($infoVeiculos as $key => $info) {
                         $clientes[] = $info->id_cliente;
                         $contratos[] = $info->id_contrato;

                         if (isset($insert[$info->placa])){
                             $insert[$info->placa]['id_cliente']  = $info->id_cliente;
                             $insert[$info->placa]['id_contrato']  = $info->id_contrato;
                         }
                     }
                 }
                 $infoVeiculos = array();

                 //PEGA OS USUARIOS DO CLIENTES
                 $usuarios = array();
                 $listUsuarios = $this->usuario_gestor->listUsuarios(implode(',', $clientes));
                 if ($listUsuarios) {
                     foreach ($listUsuarios as $key => $user) {
                         $usuarios[$user->id_cliente] = $user->usuarios;
                     }
                 }
                 $listUsuarios = array();
                 $clientes = array();

                 //PEGA OS VALORES DIARIOS
                 $valores = array();
                 $listValores = $this->contrato->listValores($contratos);
                 if ($listValores) {
                     foreach ($listValores as $valor) {
                         $valores[$valor->id] = (float)$valor->valor_mensal/cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
                     }
                 }
                 $listValores = array();
                 $contratos = array();

                 //ADICIONA OS USUARIOS E VALORES DIARIOS
                 foreach ($insert as $key => $veiculo) {
                     if ( isset($usuarios[$veiculo['id_cliente']]) )
                         $insert[$veiculo['placa']] += array( 'usuarios' => $usuarios[$veiculo['id_cliente']] );
                     else
                        $insert[$veiculo['placa']] += array( 'usuarios' => null);

                     if ( isset($valores[$veiculo['id_contrato']]) )
                         $insert[$veiculo['placa']] += array( 'valor_diario' => $valores[$veiculo['id_contrato']] );
                     else
                        $insert[$veiculo['placa']] += array( 'valor_diario' => 0);
                 }

             }
             if (count($insert)>0) {
                 //SALVA OS DADOS NO BANCO
                 $this->veiculo->setVeiculoDisponiveis($insert);
             }
        }
    }

    /*
   * BUSCA OS VALORES PROPORCIONAIS DE CADA CONTRATO POR USO DE VEICULOS
   */
   public function list_valor_total_disponiveis($d_inicio, $d_final, $contratos){
       $disponiveis = array();
       $datas_placa = array();
       $results = $this->veiculo->list_disponibilidade_veiculo($d_inicio, $d_final, 'id_contrato, valor_diario, datahora, placa', false, false, $contratos);
       if ($results) {
           foreach ($results as $key => $result) {
               //verifica se aquela placa daquele contrato para aquele dia ja teve seus dados(total e qtd_dias) calculados
               if(!isset($datas_placa[explode(' ', $result->datahora)[0]][$result->id_contrato][$result->placa])){
                   $datas_placa[explode(' ',$result->datahora)[0]][$result->id_contrato][$result->placa]=true;
                   //se ainda nao calculado uma primeira vez, iniciaza com valores 0
                   if (!isset($disponiveis[$result->id_contrato])) {
                       $disponiveis[$result->id_contrato] = (object)array('total' => 0);
                   }
                   //calcula os soamatorios para aquele contrato
                   $disponiveis[$result->id_contrato] = (object)array(
                       'total' => intval($disponiveis[$result->id_contrato]->total) + $result->valor_diario
                   );
               }
           }
           $results = array();  //LIMPA O ARRAY PARA LIBERAR ESPACO NA MEMORIA
           if (count($disponiveis)>0) {
               return $disponiveis;
           }
       }
       return false;
   }

    /*
    * GERA AS FATURAS PARA CONTRATOS COM VENCIMENTO DE FATURAS PARA +5 DIAS DA DATA ATUAL
    */
    public function gerarFaturasAutomatiacas(){
        $faturas = array();
        $insertFaturas = array();
        $insertItens = array();
        $itens = array();
        $attValorFatura = array();
        $attNumeroFatura = array();
        $data = date('Y-m-d', strtotime("+5 days", strtotime(date('Y-m-d'))));

        // BUSCA TODOS OS CONTRATOS
        $select_contratos = 'c.id, c.boleto, c.quantidade_veiculos, c.id_cliente, c.valor_mensal,c.tipo_proposta,c.primeira_mensalidade,c.meses,c.vencimento,c.consumo_fatura,cc.pais,cc.ISS,cc.IRPJ,cc.Cont_Social,cc.PIS,cc.COFINS';
        $contratos = $this->contrato->listContratosJoinClientes($select_contratos, array('c.vencimento' => explode('-', $data)[2] ));
        if ($contratos) {
            //GUARDA OS ID_CONTRATOS
            $contratos_id = array();
            foreach ($contratos as $contrato) {
                $contratos_id[] = $contrato->id;
            }

            //PEGA TODAS AS FATURAS ABERTAS
            $fatsRetorno = $this->fatura->listFaturas('Id, id_cliente, status', array('data_vencimento' => $data));
            if ($fatsRetorno) {
                foreach ($fatsRetorno as $fatura) {
                    $faturas[$fatura->id_cliente] = (object)array(
                        'id' => $fatura->Id,
                        'status' => $fatura->status
                    );
                    $faturas['faturas'][] = $fatura->Id;
                }
                $fatsRetorno=array();  //LIMPA OS DADOS, ESSE ARRAY NAO SERA MAIS USADO

                //PEGA TODOS OS ITENS DE FATURAS
                $select = 'id_cliente, id_fatura, relid_item, vencimento_item';
                $itensFaturas = $this->fatura->listItensFaturas($faturas['faturas'], $select);
            }

            //PEGA O ID (FATURA) MAIS RECENTE - PARA APARTIR DAÍ IR ADICIONANDO NOVAS FATURAS
            $idFaturaAtual = intval($this->fatura->faturasRecentes('Id')[0]->Id);

            $d_inicio = date('Y-m-d', strtotime("-".(intval(date('d'))-1)." days", strtotime(date('Y-m-d', strtotime("-1 months", strtotime(date('Y-m-d')))))));
            $d_final = date('Y-m-d', strtotime("-".(intval(date('d')))." days", strtotime(date('Y-m-d'))));

            //PEGA OS VALORES PROPORCIONAIS PARA CADA CONTRATO QUE TIVERAM VEICULOS TRABALHANDO NAQUELE PERIODO
            $total_disponiveis = $this->list_valor_total_disponiveis($d_inicio, $d_final, $contratos_id);
            $contratos_id = array();

            $novoItemMensalidade = array();
            $novoItemBoleto = array();
            $vencimento = $contratoVencimento = '';

            foreach ($contratos as $contrato) {
                //DATAS
                $data_inicio = new DateTime($contrato->primeira_mensalidade);
                $contratoVencimento = $contrato->vencimento < 10 ? '0'.$contrato->vencimento : $contrato->vencimento; //tratamento do dia de vencimento
                $dia = isset($contratoVencimento) ? strval($contratoVencimento) : '30';
                $vencimento = date('Y-m').'-'.$dia;
                $data_fim = new DateTime($vencimento);

                //QTD DE MESES ENTRE O PROX VENCIMENTO E A PRIMEIRA MENSALIDADE
                $diferenca = $data_inicio->diff($data_fim);
                $meses_diff = $diferenca->y > 0 ? ($diferenca->y * 12) + $diferenca->m : $diferenca->m;

                //ANALISA SE O CONTRATO AINDA TEM FATURAS PARA GERAR
                if ($meses_diff <= $contrato->meses) {

                    $valor_mensalidade = 0;
                    $total_fatura = 0;
                    $t1 = '';
                    $t2='';
                    $t3 = '';
                    $descricao = '';
                    $taxa = 0;

                    //CALCULA VALOR TOTAL E DESCRICAO, DEPENDENDO DO TIPO DE FATURAMENTO (MENSAL OU POR USO)
                    if ($contrato->consumo_fatura == 0) {
                        //FATURAMENTO MENSAL
                        $valor_mensalidade = (float)$contrato->quantidade_veiculos * $contrato->valor_mensal;  //TOTAL DA FATURA/ITEM
                        //CRIA A DESCRICAO DA FATURA E ITEM RASTREAMENTO
                        $t1 = 'Locação de módulos para rastreamento veicular {'.$contrato->quantidade_veiculos.' unidade(s)}, fatura gerada automaticamente';
                        $t3='Contrato';
                        if($contrato->pais == "EUA"){
                            $t1='AUTOMATIC INVOICE';
                            $t3='Contract';
                        }
                        $descricao = '['.$t3.' '.$contrato->id.'] '.$t1;
                    }else {
                        //FATURAMTENTO POR USO
                        if (isset($total_disponiveis[$contrato->id])) {
                            $valor_mensalidade = round($total_disponiveis[$contrato->id]->total, 2);
                            $t1 = 'Locação de módulos para rastreamento veicular, fatura proporcional';
                            $t2 = 'Detalhar';
                            $t3 = 'Contrato';
                            if($contrato->pais == "EUA"){
                                $t1 = 'PROPORTIONAL INVOICE';
                                $t2 = 'Detail';
                                $t3 = 'Contract';
                            }
                            $descricao = '['.$t3.' '.$contrato->id.'] '.$t1.' (<a href="https://gestor.showtecnologia.com/system/newapp/index.php/api/resumoVeiculosDisponiveis?di='.urlencode(data_for_humans($d_inicio)).'&df='.urlencode(data_for_humans($d_final)).'&id_cliente='.$contrato->id_cliente.'">'.$t2.'</a>)';
                        }else {
                            //CASO NAO TEVE USO DOS VEICULOS DAQUELE CONTRATO, SEGUE PARA A PROXIMA ITERACAO
                            continue;
                        }
                    }

                    //DEFINE OS VALORES DOS ITENS DE FATURAS
                    if ($contrato->tipo_proposta == 1) {        // VERIFICA TIPO FATURA
                        // CRIA ARRAY DADOS CHIP
                        $novoItemMensalidade = array(
                            'id_cliente' => $contrato->id_cliente,
                            'tipo_item' => 'mensalidade',
                            'descricao_item' => "[Contrato ".$contrato->id."] Locação de SIM CARD {".$contrato->quantidade_veiculos." chips}",
                            'valor_item' => $valor_mensalidade,
                            'relid_item' => $contrato->id,
                            'taxa_item' => 'nao',
                            'tipotaxa_item' => 'boleto',
                            'vencimento_item' => $vencimento
                        );

                    } else {
                        // CRIA ARRAY DADOS RASTREADOR
                        $novoItemMensalidade = array(
                            'id_cliente' => $contrato->id_cliente,
                            'tipo_item' => 'mensalidade',
                            'descricao_item' => $descricao,
                            'valor_item' => $valor_mensalidade,
                            'relid_item' => $contrato->id,
                            'taxa_item' => 'nao',
                            'tipotaxa_item' => 'boleto',
                            'vencimento_item' => $vencimento
                        );
                    }

                    //DEFINE OS VALORES PARA ITENS DE TAXA DE BOLETO SE O CONTRATO PEDIR
                    if ($contrato->boleto == 1) {
                        $taxa = 4.50;

                        $novoItemBoleto = array(
                            'id_cliente' => $contrato->id_cliente,
                            'tipo_item' => 'taxa',
                            'descricao_item' => 'Taxa Boleto',
                            'valor_item' => $taxa,
                            'relid_item' => 0,
                            'taxa_item' => 'sim',
                            'tipotaxa_item' => 'boleto',
                            'vencimento_item' => $vencimento
                        );
                    }

                    //SE O CONTRATO AINDA NAO POSSUI FATURA PARA O VENCIMENTO DO MÊS ATUAL, ELE VAI GERAR UMA NOVA FATURA
                    if (!isset($faturas[$contrato->id_cliente])) {
                        $id_fatura = $idFaturaAtual+1;  //recebe o id da proxima fatura (fatura nova)

                        //DEFINE OS VALORES DA NOVA FATURA
                        $iss = $contrato->ISS != null ? $contrato->ISS : 0;
                        $irpj = $contrato->IRPJ != null ? $contrato->IRPJ : 0;
                        $csll = $contrato->Cont_Social != null ? $contrato->Cont_Social : 0;
                        $pis = $contrato->PIS != null ? $contrato->PIS : 0;
                        $confins = $contrato->COFINS != null ? $contrato->COFINS : 0;

                        //GUARDA OS ITENS PARA INSERCAO NO BANCO
                        $novoItemMensalidade['id_fatura'] = $id_fatura;
                        $insertItens[] = $novoItemMensalidade;
                        //ADICIONA NA LISTA DE ITENS EXISTENTES
                        $itensFaturas[] = array(
                            'id_cliente' => $contrato->id_cliente,
                            'id_fatura' => $id_fatura,
                            'relid_item' => $contrato->id,
                            'vencimento_item' => $vencimento
                        );
                        //ATUALIZA O VALOR TOTAL DA FATURA
                        $total_fatura += $valor_mensalidade;

                        if ($contrato->boleto==1) {
                            $novoItemBoleto['id_fatura'] = $id_fatura;
                            $insertItens[] = $novoItemBoleto;
                            //ADICIONA NA LISTA DE ITENS EXISTENTES
                            $itensFaturas[] = array(
                                'id_cliente' => $contrato->id_cliente,
                                'id_fatura' => $id_fatura,
                                'relid_item' => 0,
                                'vencimento_item' => $vencimento
                            );
                            //ATUALIZA O VALOR TOTAL DA FATURA
                            $total_fatura += $taxa;
                        }

                        //DADOS DA FATURA
                        $novaFatura = array(
                            'Id' => $id_fatura,
                            'id_contrato' => '',
                            'id_cliente' => $contrato->id_cliente,
                            'data_vencimento' => $vencimento,
                            'boleto_vencimento' => $vencimento,
                            'valor_boleto' => $total_fatura,
                            'quantidade' => $contrato->quantidade_veiculos,
                            'valor_total' => $total_fatura,
                            'valor_unitario' => $contrato->valor_mensal,
                            'total_fatura' => $total_fatura,
                            'taxa_boleto' => $taxa,
                            'data_emissao' => date('Y-m-d'),
                            'status' => 2,
                            'numero' => $id_fatura,
                            'periodo_inicial' => $d_inicio,
                            'periodo_final' => $d_final,
                            'mes_referencia' =>date('m/Y', strtotime("-".(intval(date('d')))." days", strtotime(date('Y-m-d')))),
                            'iss' => $iss,
                            'irpj' => $irpj,
                            'csll' => $csll,
                            'pis' => $pis,
                            'cofins' => $confins
                        );
                        //GUARDA A FATURA PARA INSERCAO NO BANCO
                        $insertFaturas[] = $novaFatura;

                        //INCREMENTA O ID PARA A PROXIMA FATURA
                        $idFaturaAtual++;
                    }
                    //CASO A FATURA JA EXISTA
                    else {
                        //SE A FATURA NAO ESTA PAGA
                        if ($faturas[$contrato->id_cliente]->status != 1) {
                            //PEGA O ID DA FATURA
                            $id_fatura = $faturas[$contrato->id_cliente]->id;
                            $atualizou = false;

                            //VERIFICA SE O ITEM MENSALIDADE JA EXISTE
                            $verifaMensalidade = array('id_cliente'=>$contrato->id_cliente, 'id_fatura'=>$id_fatura, 'relid_item'=>$contrato->id, 'vencimento_item'=>$vencimento);
                            if (!in_array($verifaMensalidade, $itensFaturas)) {
                                $novoItemMensalidade['id_fatura'] = $id_fatura;
                                //GUARDA O ITEM PARA INSERCAO NO BANCO
                                $insertItens[] = $novoItemMensalidade;
                                //ADICIONA NA LISTA DE ITENS EXISTENTES
                                $itensFaturas[] = array(
                                    'id_cliente' => $contrato->id_cliente,
                                    'id_fatura' => $id_fatura,
                                    'relid_item' => $contrato->id,
                                    'vencimento_item' => $vencimento
                                );

                                $atualizou = true;
                            }
                            //VERIFICA SE O ITEM TAXA DE BOLETO JA EXISTE
                            $verifaBoleto = array('id_cliente'=>$contrato->id_cliente, 'id_fatura'=>$id_fatura, 'relid_item'=>0, 'vencimento_item'=>$vencimento);
                            if (!in_array($verifaBoleto, $itensFaturas) && $contrato->boleto==1) {
                                $novoItemBoleto['id_fatura'] = $id_fatura;
                                //GUARDA O ITEM PARA INSERCAO NO BANCO
                                $insertItens[] = $novoItemBoleto;
                                //ADICIONA NA LISTA DE ITENS EXISTENTES
                                $itensFaturas[] = array(
                                    'id_cliente' => $contrato->id_cliente,
                                    'id_fatura' => $id_fatura,
                                    'relid_item' => 0,
                                    'vencimento_item' => $vencimento
                                );
                                $atualizou = true;
                            }

                            if ($atualizou) {
                                //GUARDA O ID DA FATURA PARA ATUALIZAR O SEU VALOR POSTERIORMENTE
                                if (!in_array($id_fatura, $attValorFatura))
                                    $attValorFatura[] = $id_fatura;

                                //GUARDA O ID DA FATURA PARA ATUALIZAR O SEU NUMERO POSTERIORMENTE
                                if (!in_array($id_fatura, $attNumeroFatura) && $faturas[$contrato->id_cliente]->status == 0)
                                    $attNumeroFatura[] = $id_fatura;
                            }
                        }
                    }
                }
            }
        }
        if (count($insertFaturas)>0) {
            //SALVA AS FATURAS NO BANCO
            $this->fatura->insertFaturasBatch($insertFaturas);
        }
        if (count($insertItens)>0) {
            //SALVA OS ITENS DE FATURAS NO BANCO
            $this->fatura->insertItensBatch($insertItens);
        }
        if (count($attValorFatura)>0) {
            //ATUALIZA OS VALORES DAS FATURAS
            $this->fatura->updateValorFaturasBatch(implode(',', $attValorFatura));
        }
        if (count($attNumeroFatura)>0) {
            //ATUALIZA OS NUMEROS DAS FATURAS ENVIADAS
            $this->attNumeroBoletos($attNumeroFatura);
        }
    }

    /*
     * ATUALIZA O NUMERO DA FATURA/BOLETO
     */
    public function attNumeroBoletos($attFaturas)
    {
        $cancel = array();
        $insert = array();
        $itens = array();

        //FATURA MAIS RECENTE NO BANCO DE DADOS
        $faturaAtual = $this->fatura->faturasRecentes($select = 'Id')[0]->Id;
        $id_fat = $faturaAtual + 1;

        //CARREGA AS FATURAS
        $listaFaturas = $this->fatura->listaFaturasPorGrupoId($attFaturas);

        //CARREGA OS ITENS DAS FATURAS
        $listaItens = $this->fatura->listaItensContratoFaturas($attFaturas);
        if ($listaItens) {
            foreach ($listaItens as $item) {
                $itens[$item->id_fatura][] = $item;
            }
        }

        if ($listaFaturas) {
            foreach ($listaFaturas as $key => $fatura) {
                //ATUALIZA A FATURA COM DADOS DE CANCELAMENTO
                $cancel['faturas'][$key]['Id'] = $fatura['Id'];
                $cancel['faturas'][$key]['datacancel_fatura'] = date('Y-m-d');
                $cancel['faturas'][$key]['status'] = 3;
                $cancel['faturas'][$key]['instrucoes1'] = "Atualização de fatura";

                //REMOVE DADOS DE ID DOS ITENS PARA INSERCAO POSTERIOR NO BANCO
                if (isset($itens[$fatura['Id']])) {
                    foreach ($itens[$fatura['Id']] as $ch => $item) {
                        $item = (array)$item;
                        unset($item['id_item']);
                        $item['id_fatura'] = $id_fat;
                        $insert['itens'][] = $item;
                    }
                }

                //REMOVE APENAS O ID E NUMERO DA FATURA, PARA A INSERCAO POSTERIOR NO BANCO
                $fatura['Id'] = $id_fat;
                $fatura['numero'] = $id_fat;
                $fatura['status'] = 2;
                $insert['faturas'][] = $fatura;

                $id_fat++;
            }

            //CANCELA AS FATURAS ATUAIS
            $this->fatura->updateFaturasBatch($cancel['faturas']);
            //GERA NOVAS FATURAS COM SEUS NUMEROS/ID ATUALIZADOS
            $this->fatura->insertFaturasBatch($insert['faturas']);
            //ADICIONA OS ITENS ATUALIZADOS/NOVOS
            $this->fatura->insertItensBatch($insert['itens']);
        }
    }


    public function testeFaturas(){


        $faturas = $this->db->select('sum(valor_total) as vt,id_cliente')
            ->where('date(data_vencimento)>="2018-04-01" and date(data_vencimento)<="2018-04-30"')
            ->group_by('id_cliente')
            ->get('showtecsystem.cad_faturas')
            ->result();
        $clientes = [];
        foreach($faturas as $cliente){
            if($cliente->id_cliente)
                $clientes[] = $cliente->id_cliente;
        }
        $contratos = $this->db->select('id')
            ->where_in('status', [1,2])
            ->where_not_in('id_cliente',$clientes)
            ->where('data_contrato <','2018-04-30')
            ->get('showtecsystem.contratos')
            ->result();

        echo json_encode($contratos);
    }
    public function getTaxZipCode(){
        $tax_day = $this->db->where('date(data)',date("Y-m-d"))
            ->get('systems.tax_usa');
        if($tax_day->num_rows() == 0){
            $tax_day->result();
            $clientes = $this->db->select('cep,uf,cidade')
                ->where('informacoes','EUA')
                ->get('showtecsystem.cad_clientes')
                ->result();
            foreach($clientes as $cliente){
                $context = stream_context_create(
                    array(
                        'http' => array(
                            'method' => 'GET',
                            'header' => "Content-Type: type=application/json\r\n"
                                . "Authorization: Basic Z2FicmllbGJhbmRlaXJhY2FybmVpcm9AZ21haWwuY29tOjBFQzhGMTE5MkI="
                        )
                    )
                );
                $records = @file_get_contents("https://rest.avatax.com/api/v2/taxrates/byaddress?city=".urlencode(strtolower(trim($cliente->cidade)))."&region=".strtolower(trim($cliente->uf))."&postalCode=".strtolower(trim($cliente->cep))."&country=US", null, $context);
                if($records){
                    $data = array('estado'=>strtolower(trim($cliente->uf)),'cidade'=>strtolower(trim($cliente->cidade)),'data'=>date("Y-m-d"),'taxa'=>json_decode($records)->totalRate);
                    echo json_encode($data);
                    $this->db->insert('systems.tax_usa',$data);
                }
            }
        }
        else{
            echo "0";
        }
    }

    public function updateStatusWeekly() {
        //weekly (0 -> default, 1 -> unificando, 2 -> unificada)
        $this->os->updateWeekly();
    }

    public function correcao_last() {
        $this->veiculo->correcao_lastTrack();
    }

    //pega as faturas de 3 à vencer, no dia de vencimento e as com 3 dias para vencer e envia aos clientes
    public function envia_notificacao_status_fatura(){
        $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato@showtecnologia.com', 'Referente à fatura - Show Tecnologia', 'teste cron');

       // // Ignore user aborts and allow the script
       // // to run forever
       // ignore_user_abort(true);
       // set_time_limit(0);
       //
       // $clientes = $this->cliente->listar(array('status' => 1, 'id' => 142476));
       // if (count($clientes) > 0) {
       //
       //     foreach ($clientes as $cliente) {
       //         if ($cliente->orgao == 'privado') {
       //
       //             $faturas = $this->fatura->listar_por_cliente(
       //                 'Id, data_vencimento',
       //                 'cad_faturas.id_cliente =' .$cliente->id. ' and cad_faturas.status in (0,2)'
       //             );
       //
       //              if ($faturas) {
       //                  $hoje = date('Y-m-d');
       //                  $email_finan = $this->cliente->get_clientes_emails($cliente->id, 'email', '0')[0]->email;
       //                  $email_finan = strtolower($email_finan);
       //
       //                  foreach ($faturas as $fatura) {
       //
       //                      $dif_datas = dif_datas($hoje, $fatura->data_vencimento);
       //                      $nome = explode(" ", $cliente->nome);
       //                      $dia = substr($fatura->data_vencimento, 8, 2);
       //                      $mes = data_mes_texto_completo( substr($fatura->data_vencimento, 5, 2) );
       //
       //                      if ($dif_datas == '+3') {
       //                          $texto = 'Faltam apenas 3 dias para o vencimento da sua fatura. Salientamos a importância de realizar este pagamento o mais breve possível a fim de evitar o acúmulo de juros.';
       //                          $mensagem = $this->layout_email->notifica_status_pagamento($nome[0], $texto, $fatura->Id, 0);
       //                          $sender = $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato.ps08@hotmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
       //                          $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato@showtecnologia.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
       //                          $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'contato.renato.ps08@gmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
       //
       //                      }elseif ($dif_datas == '+0') {
       //                          $texto = 'Lembramos que a sua fatura vence hoje. Salientamos a importância de realizar este pagamento o mais breve possível a fim de evitar o acúmulo de juros.';
       //                          $mensagem = $this->layout_email->notifica_status_pagamento($nome[0], $texto, $fatura->Id, 1);
       //                          $sender = $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato.ps08@hotmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
       //                          $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato@showtecnologia.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
       //                          $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'contato.renato.ps08@gmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
       //
       //                      }elseif ($dif_datas == '-3') {
       //                          $texto ='Não identificamos em nossos registros o pagamento da fatura  referente ao mês de '.$mes.', vencido no dia '.$dia.'. Salientamos a importância deste
       //                          pagamento o mais breve possível a fim de evitar o acúmulo de juros. Ressaltamos que, a visualização poderá ser suspensa até a liquidação do débito.';
       //                          $mensagem = $this->layout_email->notifica_status_pagamento($nome[0], $texto, $fatura->Id, 2);
       //                          $sender = $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato.ps08@hotmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
       //                          $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato@showtecnologia.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
       //                          $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'contato.renato.ps08@gmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
       //                      }
       //                  }
       //              }
       //          }
       //      }
       //  }
    }


}
