<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Faturas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('fatura');
        $this->load->model('contrato');
        $this->load->model('cliente');
        $this->load->model('retorno');
        $this->load->model('envio_fatura');
        $this->load->model('ordem_servico');
        $this->load->model('cadastro');
        $this->load->model('layout_email');
        $this->load->model('email_model');
        $this->load->model('endereco');
        $this->load->model('telefone');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->load->model('log_shownet');
        $this->load->helper('util_helper');
        $this->load->helper('file');
    }

    function transFaturasCancelar($id_fatura, $status){
        $motivo = $this->input->post('motivo');
        if ($motivo)
            $this->add_coment_fat_a_cancelar($motivo, $id_fatura);

        if ($this->fatura->transfereFatura($id_fatura, $status))
            echo json_encode(array('mensagem' => 'Fatura transferida com sucesso!', 'status' => 'OK'));
        else
            echo json_encode(array('mensagem' => 'Não foi possível transferir a fatura.', 'status' => 'erro'));
    }

    public function index($c_order = false, $order = false, $pagina = false) {
        $this->auth->is_allowed('faturas_visualiza');
        $dados['titulo'] = 'Faturas';
        // $this->load->view('fix/header', $dados);
        // $this->load->view('faturas/lista_fatura');
        // $this->load->view('fix/footer');
        $this->mapa_calor->registrar_acessos_url(site_url('/faturas'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('faturas/lista_fatura_NS');
        $this->load->view('fix/footer_NS');
    }

    public function index_antigo($c_order = false, $order = false, $pagina = false) {
        $this->auth->is_allowed('faturas_visualiza');
        $dados['titulo'] = 'Faturas';
        
        $this->load->view('fix/header', $dados);
        $this->load->view('faturas/lista_fatura');
        $this->load->view('fix/footer');
    }

    public function ajaxListFaturas() {
        $list = 'cad_faturas.status != 4';
        $draw = 1; # Draw Datatable
        $start = 0; # Contador (Start request)
        $limit = 10; # Limite de registros na consulta
        $search = NULL; # Campo pesquisa
        if ($this->input->get()) {
            $list = $this->input->get('list') ? 'cad_faturas.status = '.$this->input->get('list') : 'cad_faturas.status != 4';
            $draw = $this->input->get('draw');
            $search = $this->input->get('search')['value'];
            $start = $this->input->get('start'); # Contador (Página Atual)
            $limit = $this->input->get('length'); # Limite (Atual)
        }

        echo $this->fatura->ajaxListFaturas($start, $limit, $search, $draw, $list);
    }

    public function filtro($c_order = false, $order = false, $pagina = false) {
        $this->auth->is_allowed('faturas_visualiza');

        $paginacao = $pagina != false  ? $pagina : 0;
        $c_order = $c_order == false ? 'Id' : $c_order;
        $order = $order == false ? 'desc' : $order;
        $dados['order'] = $order;
        $dados['c_order'] = $c_order;
        $dados['toogle_order'] = $order == 'desc' ? 'asc' : 'desc';
        if($this->input->post('filtro')){
            $filtro = $this->input->post('filtro');
            $numero = is_numeric($filtro) == true ? 'OR cad_faturas.numero = '.$filtro : '';
            $this->session->set_userdata('filtro_fatura', "cad_faturas.Id = '{$filtro}'
			OR cad_clientes.nome LIKE '%{$filtro}%' ".$numero);
            $this->session->set_userdata('filtro_keyword', $this->input->post('filtro'));
        }
        $dados['filtro'] = $this->session->userdata('filtro_keyword');
        $where = $this->session->userdata('filtro_fatura');
        if(!$where)
            redirect('faturas');
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');
        $config['base_url'] = base_url().'faturas/filtro/'.$c_order.'/'.$order.'/';
        $config['uri_segment'] = 5;
        $config['total_rows'] = $this->fatura->total($where);
        $this->pagination->initialize($config);
        $dados['faturas'] = $this->fatura->listar($where, $paginacao, 10, 'cad_faturas.'.$c_order, $order);
        $dados['titulo'] = 'Faturas';
        $this->load->view('fix/header', $dados);
        $this->load->view('faturas/lista_fatura');
        $this->load->view('fix/footer');
    }

    public function add() {
        $this->auth->is_allowed('faturas_add');
        if($this->input->post('add_fatura'))
            $this->fatura->sess_add_fatura($this->input->post());

        if($this->input->post('add_item')){
            $post = $this->input->post();
            if(!$this->input->post('taxa_item'))
                $post['taxa_item'] = 'nao';
            $this->fatura->sess_add_item_fatura($post);
        }
        if($this->input->post('remover_item')){
            $post = $this->input->post();
            $this->fatura->sess_remover_item_fatura($post);
        }
        //$dados['clientes'] = $this->cliente->listar(array('status !=' => 3, 'informacoes !=' => 'OMNILINK'));
        $dados['fatura'] = $this->fatura->sess_get_fatura();

        $dados['data_emissao'] = $dados['fatura'] === true ? $dados['fatura']['data_emissao'] : date('d-m-Y');
        $dados['titulo'] = 'Faturas';
        $this->load->view('fix/header', $dados);
        $this->load->view('faturas/add_fatura');
        $this->load->view('fix/footer');
    }

    public function add_new() {
        $this->load->model('ticket');
        $id_cliente = $this->input->get('id');
        $dados = array();
        $dados['secretarias'] = $this->cliente->getAjaxListSecretaria($id_cliente);
        $dados['tickets'] = $this->ticket->getAjaxListTicketId($id_cliente);
        if ($dados['tickets']) {
            $dados['tickets'] = json_encode($dados['tickets']);
        }
        if ($dados['secretarias']) {
            foreach ($dados['secretarias'] as $secretaria) {
                $s[] = array(
                    'id' => $secretaria->id,
                    'nome' => $secretaria->nome,
                );
            }
            $dados['secretarias'] = json_encode($s);
        }

        $this->load->view('faturas/add_fatura_new', $dados);
    }

    public function gerar_fatura_new() {
        $this->auth->is_allowed('faturas_add');
        $dados = $this->input->post();
        $itens = $this->input->post('itens');

        $fatura['fatura'] = array(
            'id_cliente'            => $dados['cliente_id'],
            'irpj'                  => $dados['irpj'],
            'csll'                  => $dados['csll'],
            'pis'                   => $dados['pis'],
            'cofins'                => $dados['cofins'],
            'iss'                   => $dados['iss'],
            'valor_total'           => $dados['valorTotal'],
            'id_secretaria'         => $dados['secretaria'] ? $dados['secretaria'] : null,
            'data_emissao'          => $dados['data_emissao'],
            'data_vencimento'       => $dados['data_vencimento'],
            'formapagamento_fatura' => $dados['forma_pagamento'],
            'nota_fiscal'           => $dados['nota_fiscal'],
            'mes_referencia'        => $dados['mes_referencia'],
            'periodo_inicial'       => $dados['periodo_inicial'] ? $dados['periodo_inicial'] : null,
            'periodo_final'         => $dados['periodo_final'] ? $dados['periodo_final'] : null,
            'id_ticket'             => $dados['id_ticket'] ? $dados['id_ticket'] : null,
            'atividade'             => $dados['atividade'] ? $dados['atividade'] : '0',
            'status'                => 2,
            'quantidade_veiculos'   => $dados['qtd_veiculos'],
            'data_inclusao'         => $dados['data_inclusao'],
            'numero'                => 0,
            'id_ps'                 => 0,
            'vencimento_fn'         => "1970-01-01",
            'pagamento_fn'          => "1970-01-01",
            'valor_fn'              => 0,
            'valor_pago_fn'         => 0,
            'multa_fn'              => 0,
            'juros_fn'              => 0,
            'status_fn'             => '',
            'data_fn'               => "1970-01-01",
            'hora_fn'               => "00:00:00",
            'retorno_fn'            => '',
            'competencia_fn'        => 0,
        );

        foreach ($itens as $item){

            $fatura['itens'][] = array(
                'id_cliente'            => $dados['cliente_id'],
                'tipo_item'             => $item['tipo_item'],
                'descricao_item'        => $item['descricao_item'],
                'valor_item'            => str_replace(',', '.', str_replace('.', '', $item['valor_item'])),
                'taxa_item'             => $item['tipotaxa_item'] ? 'sim' : 'nao',
                'tipotaxa_item'         => $item['tipotaxa_item'] && $item['tipotaxa_item'] ? $item['tipotaxa_item'] : 'boleto',
                'vencimento_item'       => $dados['data_vencimento'],
                'relid_item'            => $item['relid_item'] && $item['relid_item'] ? $item['relid_item'] : 0,
                'obs_item'              => '',
            );
        }
        $retorno = $this->fatura->gravar_fatura($fatura);
        if ($retorno){
            echo json_encode(array('status' => true, 'msg' => 'Fatura gerada com sucesso'));
        }else{
            echo json_encode(array('status' => false, 'msg' => 'Erro ao gerar a fatura. Tente novamente!'));
        }
    }


    public function gerar_fatura() {
        $this->auth->is_allowed('faturas_add');
        if($this->input->post('gerar_fatura')) {
            $fatura = $this->fatura->sess_get_fatura();
            $db_fatura['fatura'] = array(
                'id_cliente' => $fatura['id_cliente'],
                'data_emissao' => date('Y-m-d'),
                'data_vencimento' => $fatura['data_vencimento'],
                'formapagamento_fatura' => $fatura['formapagamento_fatura'],
                'id_ticket' => $fatura['id_ticket'],
                'id_secretaria' => $fatura['id_secretaria'],
                'nota_fiscal' => $fatura['nota_fiscal'],
                'mes_referencia'=>$fatura['mes_referencia'],
                'periodo_inicial'=>$fatura['periodo_inicial'],
                'iss'=>$fatura["cliente"]->ISS,
                'irpj'=>$fatura["cliente"]->IRPJ,
                'csll'=>$fatura["cliente"]->Cont_Social,
                'pis'=>$fatura["cliente"]->PIS,
                'cofins'=> $fatura["cliente"]->COFINS,
                'periodo_final'=> $fatura['periodo_final'],
                'valor_total' => $this->fatura->sess_total_fatura(),
                'status' => 0
            );
            $db_fatura['itens'] = $fatura['itens'];
            try {
                $this->fatura->gravar_fatura($db_fatura);
            } catch (Exception $e) {
                $this->session->set_flashdata('msg', $e->getMessage());
            }
            redirect('faturas');
        }
    }

    /*
     * CONSTROI A FATURA
     */
    public function constroiFatura($impostos, $id_fatura, $id_cliente, $vencimento, $referente, $inicial, $final){

        $nova_fatura = array(
            'Id' => $id_fatura,
            'id_cliente' => $id_cliente,
            'data_emissao' => date('Y-m-d'),
            'data_vencimento' => $vencimento,
            'formapagamento_fatura' => 1,
            'status' => 2,
            'numero' => $id_fatura,
            'mes_referencia' => $referente,
            'periodo_inicial' => $inicial,
            'periodo_final' => $final,
            'irpj'   => $impostos->IRPJ,
            'csll'   => $impostos->Cont_Social,
            'pis'    => $impostos->PIS,
            'cofins' => $impostos->COFINS,
            'iss'    => $impostos->ISS,
            'atividade' => '1' //Atividade de Monitoramento
        );

        return $nova_fatura;
    }

    /*
     * CONSTROI ITEM
     */
    public function constroiItem($tipo, $id_fatura, $id_cliente, $descricao, $id_contrato, $valor, $vencimento){
        if ($tipo == 'adesao') {
            $item = array(
                'id_cliente' => $id_cliente,
                'id_fatura' => $id_fatura,
                'tipo_item' => 'adesao',
                'descricao_item' => $descricao,
                'relid_item' => $id_contrato,
                'valor_item' => $valor,
                'taxa_item' => 'nao',
                'tipotaxa_item' => 'boleto',
                'vencimento_item' => $vencimento
            );

        }elseif ($tipo == 'boleto') {
            $item = array(
                'id_cliente' => $id_cliente,
                'id_fatura' => $id_fatura,
                'tipo_item' => '',
                'descricao_item' => $descricao,
                'relid_item' => $id_contrato,
                'valor_item' => $valor,
                'taxa_item' => 'sim',
                'tipotaxa_item' => 'boleto',
                'vencimento_item' => $vencimento
            );

        }elseif ($tipo=='mensalidade') {
            $item = array(
                'id_cliente' => $id_cliente,
                'id_fatura' => $id_fatura,
                'tipo_item' => 'mensalidade',
                'descricao_item' => $descricao,
                'relid_item' => $id_contrato,
                'valor_item' => $valor,
                'taxa_item' => 'nao',
                'tipotaxa_item' => 'boleto',
                'vencimento_item' => $vencimento
            );
        }
        return $item;
    }

    //GERA INICIO E FIM DE PERIODO, E MES DE REFRENCIA DO FATURAMENTO
    public function inicio_fim_periodo($vencimento){
        $retorno = [];
        $data = explode('-',$vencimento);
        $retorno['inicial'] = date('Y-m-d', strtotime("-".(intval($data[2])-1)." days", strtotime(date('Y-m-d', strtotime("-1 months", strtotime($vencimento))))));
        $retorno['final'] = date('Y-m-d', strtotime("-".$data[2]." days", strtotime($vencimento)));
        $retorno['referente'] = substr($retorno['inicial'], 5, 2).'/'.substr($retorno['inicial'], 0, 4);

        return $retorno;
    }

    /*
    * GERA ADESAO
    */
    public function geraAdesao($impostos, $contrato, $proxFatura, $minhasFaturas, $novasFaturas, $itensFaturas, $adesoes, $novosItens, $attNumeroFaturas, $attValorFaturas, $dt_inicio=false){
        $qtd_adesoes = count($adesoes);

        //QUANDO EH PASSADO UMA DATA PARA INICIO DAS GERACOES
        if ($dt_inicio) {
           $ult_venc_adesao = $dt_inicio;

        //QUANDO EH PASSADO UMA DATA PARA INICIO DAS GERACOES, PEGA A DATA DE VENCIMENTO DEPOIS DA ULTIMA ADESAO GERADA.
        }else {
           $ult_venc_adesao = $contrato->data_prestacao;

           if ($qtd_adesoes > 0){
               foreach ($adesoes as $adesao){
                   if($ult_venc_adesao < $adesao->vencimento_item)
                       $ult_venc_adesao = $adesao->vencimento_item;
               }
               $ult_venc_adesao = proximaDataMes($ult_venc_adesao);               
           }
        }

        $ult_venc_adesao_temp = explode('-', $ult_venc_adesao);
        $ultimo_dia_mes = dias_no_mes($ult_venc_adesao_temp[1], $ult_venc_adesao_temp[0]);

        if ($ult_venc_adesao_temp[2] > $ultimo_dia_mes) {
            $ult_venc_adesao_temp[2] = $ultimo_dia_mes;
            $ult_venc_adesao = implode('-', $ult_venc_adesao_temp);
        }

        $qtd_adesoes++;

        //GERA AS ADESOES FALTANTES
        for ($qtd_adesoes; $qtd_adesoes <= $contrato->prestacoes; $qtd_adesoes++){

            $id_fat = $proxFatura;

           //CRIA A DESCRICAO
           $descricao = '[Contrato '.$contrato->id.'] Valor referente a adesão '.$qtd_adesoes.' / '.$contrato->prestacoes;
           $vencimento = $ult_venc_adesao;

           //ANALIZA SE JA EXISTE FATURA PARA O VENCIMENTO

           if (isset($minhasFaturas['vencimentos']) && is_numeric($pos = array_search($vencimento, $minhasFaturas['vencimentos']) )) {
                
                $qtd_faturas = array_count_values($minhasFaturas['vencimentos']);
                $id_fat_paga = false;
                $id_fat_aberta = false;
                $temAdesaoFatPaga = false;
                $temAdesaoFatAberta = false;
                $posFatAberta = false;
                $adicionouItem = false;

                //TRABALHA COM AS FATURAS DAQUELE VENCIMENTO
                $tempMinhasFaturas = $minhasFaturas;

                for ($j=$qtd_faturas[$vencimento]; $j > 0; $j--) {   

                    if ($tempMinhasFaturas['status'][$pos] == 1 ) {
                        $id_fat_paga = $tempMinhasFaturas['id'][$pos];
                        if (isset($itensFaturas[$id_fat_paga]) && in_array('adesao', $itensFaturas[$id_fat_paga])) {
                            $temAdesaoFatPaga = true;
                        }                        
                        
                    }else {
                        $id_fat_aberta = $tempMinhasFaturas['id'][$pos];
                        $posFatAberta = $pos;
                        if (isset($itensFaturas[$id_fat_aberta]) && in_array('adesao', $itensFaturas[$id_fat_aberta])) {
                            $temAdesaoFatAberta = true;
                        }
                    }

                    unset($tempMinhasFaturas['id'][$pos]);
                    unset($tempMinhasFaturas['vencimentos'][$pos]);
                    unset($tempMinhasFaturas['status'][$pos]);

                    $pos = array_search($vencimento, $tempMinhasFaturas['vencimentos']);
                }

                //SE TEM FATURA PAGA
                if ($id_fat_paga && !$temAdesaoFatPaga) {

                    //SE NAO TEM FATURA ABERTA, GERA UMA NOVA FATURA PARA A ADESAO
                    if (!$id_fat_aberta) {
                        $id_fat = $proxFatura;

                        $dados = (object) [
                            'vencimento' => $vencimento,
                            'id_fat' => $id_fat,
                            'contrato' => $contrato,
                            'impostos' => $impostos,
                            'minhasFaturas' => $minhasFaturas,
                            'novasFaturas' => $novasFaturas,
                            'novosItens' => $novosItens,
                            'descricao' => $descricao,
                            'proxFatura' => $proxFatura
                        ];

                        //gera uma nova adesao para aquele vencimento
                        $novaAdesao = $this->geraNovaAdesao($dados);

                        //Atualiza os dados adicionados
                        $minhasFaturas = $novaAdesao->minhasFaturas;
                        $novasFaturas = $novaAdesao->novasFaturas;
                        $novosItens = $novaAdesao->novosItens;
                        
                        $proxFatura++; 
                        
                        //GUARDA A FATURA PARA ATULIZAR SEU VALOR TOTAL MAIS TARDE
                        if (!in_array($id_fat, $attValorFaturas))
                            $attValorFaturas[] = $id_fat;
                    
                    //SE TEM FATURA ABERTA
                    }else {
                        if (!$temAdesaoFatAberta) {
                            //ADICIONA ITEM DE ADESAO
                            $novosItens[] = $this->constroiItem('adesao', $id_fat_aberta, $contrato->id_cliente, $descricao, $contrato->id, $contrato->valor_prestacao, $vencimento);
                            $itensFaturas[$id_fat_aberta][] = 'adesao';
                            $adicionouItem = true;
                        }                        
                        if ($contrato->boleto == 1 && !in_array('boleto', $itensFaturas[$id_fat_aberta])) {
                            //ADICIONA ITEM DE BOLETO
                            $novosItens[] = $this->constroiItem('boleto', $id_fat_aberta, $contrato->id_cliente, 'Taxa Boleto', 0, 4.5, $vencimento);                            
                            $itensFaturas[$id_fat_aberta][] = 'boleto';
                            $adicionouItem = true;
                        }
                    }

                }elseif ($id_fat_aberta) {

                    $pos = $posFatAberta;
                    if (!$temAdesaoFatAberta) {
                        //ADICIONA ITEM DE MENSALIDADE
                        $novosItens[] = $this->constroiItem('adesao', $id_fat_aberta, $contrato->id_cliente, $descricao, $contrato->id, $contrato->valor_prestacao, $vencimento);                            
                        $itensFaturas[$id_fat_aberta][] = 'adesao';
                        $adicionouItem = true;
                    }                    

                    if ($contrato->boleto == 1 && !in_array('boleto', $itensFaturas[$id_fat_aberta])) {
                        //ADICIONA ITEM DE BOLETO
                        $novosItens[] = $this->constroiItem('boleto', $id_fat_aberta, $contrato->id_cliente, 'Taxa Boleto', 0, 4.5, $vencimento);                            
                        $itensFaturas[$id_fat_aberta][] = 'boleto';
                        $adicionouItem = true;
                    }
                }

                if ($adicionouItem) {
                    //SALVA FATURA PARA ATUALIZAR NUMERO, CASO O STATUS DELA FOR '0'
                    if ( !in_array($id_fat_aberta, $attNumeroFaturas) && $minhasFaturas['status'][$pos] == 0 )
                        $attNumeroFaturas[] = $id_fat_aberta;

                    //GUARDA A FATURA PARA ATULIZAR SEU VALOR TOTAL MAIS TARDE
                    if (!in_array($id_fat_aberta, $attValorFaturas))
                        $attValorFaturas[] = $id_fat_aberta;
                }                

           }else {
                $dados = (object) [
                    'vencimento' => $vencimento,
                    'id_fat' => $id_fat,
                    'contrato' => $contrato,
                    'impostos' => $impostos,
                    'minhasFaturas' => $minhasFaturas,
                    'novasFaturas' => $novasFaturas,
                    'novosItens' => $novosItens,
                    'descricao' => $descricao,
                    'proxFatura' => $proxFatura,
                    'itensFaturas' => $itensFaturas
                ];

                //gera uma nova adesao para aquele vencimento
                $novaAdesao = $this->geraNovaAdesao($dados);

                //Atualiza os dados adicionados
                $minhasFaturas = $novaAdesao->minhasFaturas;
                $novasFaturas = $novaAdesao->novasFaturas;
                $novosItens = $novaAdesao->novosItens;
                $itensFaturas = $novaAdesao->itensFaturas;
                
                $proxFatura++; 
                
                //GUARDA A FATURA PARA ATULIZAR SEU VALOR TOTAL MAIS TARDE
                if (!in_array($id_fat, $attValorFaturas))
                    $attValorFaturas[] = $id_fat;
           }

            $vencimento_temp = explode('-', $vencimento);
            $ult_venc_adesao = proximaDataMes($vencimento_temp[0] . '-' . $vencimento_temp[1] . '-' . explode('-', $contrato->data_prestacao)[2]);
        }

        if (count($novasFaturas)>0 || count($novosItens)>0) {
            return array( 
                $proxFatura,
                $itensFaturas, 
                $minhasFaturas, 
                $novasFaturas, 
                $novosItens, 
                $attNumeroFaturas, 
                $attValorFaturas 
            );
        }
        return false;
    }

    //GERA UMA NOVA ADESAO
    public function geraNovaAdesao($dados){

        //MONTA INICIO E FIM DE PERIODO
        $periodo = $this->inicio_fim_periodo($dados->vencimento);

        //ADICIONA UMA NOVA FATURA
        $dados->novasFaturas[] = $this->constroiFatura(
            $dados->impostos,
            $dados->id_fat,
            $dados->contrato->id_cliente,
            $dados->vencimento,
            $periodo['referente'],
            $periodo['inicial'],
            $periodo['final']
        );

        $dados->minhasFaturas['id'][] = $dados->id_fat;
        $dados->minhasFaturas['vencimentos'][] = $dados->vencimento;
        $dados->minhasFaturas['status'][] = 2;

        //ADICIONA A ADESAO DA FATURA
        $dados->novosItens[] = $this->constroiItem(
            'adesao', 
            $dados->id_fat, 
            $dados->contrato->id_cliente, 
            $dados->descricao, 
            $dados->contrato->id, 
            $dados->contrato->valor_prestacao, 
            $dados->vencimento
        );

        $dados->itensFaturas[$dados->id_fat][] = 'adesao';

        if ($dados->contrato->boleto == 1) {
            //ADICIONA ITEM DE BOLETO
            $dados->novosItens[] = $this->constroiItem('boleto', $dados->id_fat, $dados->contrato->id_cliente, 'Taxa Boleto', 0, 4.5, $dados->vencimento);                            
            $dados->itensFaturas[$dados->id_fat][] = 'boleto';
        }

        return $dados;
    }

    /*
     * gera fatura por contrato
    */
    public function gerarFaturasParaContrato() {
        $ids_contratos = $this->input->post('ids_contratos');
        $itensNovos = array();
        $contratosComNovasFaturas = array();
        $contratosSemNovasFaturas = array();

        // Recebe array com os ids de contrato
        if($ids_contratos) {
            foreach ($ids_contratos as $id_contrato) {

                $contrato = $this->contrato->get(array('id' => $id_contrato), 'id, data_prestacao, prestacoes, valor_prestacao, vencimento, id_cliente, boleto, meses, quantidade_veiculos, valor_mensal, tipo_proposta, primeira_mensalidade');
                
                // verifica se contrato existe
                if($contrato) {
                    $novasFaturas = array();
                    $novosItens = array();
                    $minhasFaturas = array();
                    $meusItens = array();
                    $attNumeroFaturas = array();
                    $attValorFaturas = array();

                    //CARREGA AS FATURAS ABERTAS DO CLIENTE
                    $faturas = $this->fatura->listar_por_cliente('Id, id_contrato, data_vencimento, status', array('cad_faturas.id_cliente' => $contrato->id_cliente, 'cad_faturas.status !=' => 3, 'cad_faturas.status !=' => 4), 0, 99999, 'Id', 'asc' );
                    
                    if ($faturas) {

                        foreach ($faturas as $key => $fatura) {
                            $minhasFaturas['id'][] = $fatura->Id;
                            $minhasFaturas['vencimentos'][] = $fatura->data_vencimento;
                            $minhasFaturas['status'][] = $fatura->status;
                        }
                        
                        if (count($minhasFaturas) > 0){
                            //CARREGA OS ITENS DAS FATURAS ABERTAS DO CLIENTE
                            $itens = $this->fatura->listaItensContratoFaturas($minhasFaturas['id'], 'id_fatura, vencimento_item, tipo_item, taxa_item, tipotaxa_item, relid_item');
                            if ($itens) {
                                foreach ($itens as $key => $item) {
                                    if ($item->relid_item == $contrato->id || $item->taxa_item == 'sim') {
                                        if ($item->tipo_item == 'adesao'){
                                            $meusItens['adesoes'][] = $item;
                                            $meusItens['faturas'][$item->id_fatura][] = 'adesao';

                                        }elseif ($item->tipo_item == 'mensalidade'){
                                            $meusItens['faturas'][$item->id_fatura][] = 'mensalidade';

                                        }elseif ($item->tipotaxa_item =='boleto'){
                                            $meusItens['faturas'][$item->id_fatura][] = 'boleto';
                                        }
                                    }
                                }
                            }
                        }
                    }        

                    $impostos = $this->cliente->listar(array('id' => $contrato->id_cliente), 0, 1, 'id', 'DESC', 'IRPJ, PIS, COFINS, ISS, Cont_Social')[0];

                    //FATURA MAIS RECENTE NO BANCO DE DADOS
                    $faturaAtual = $this->fatura->faturasRecentes($select = 'Id')[0]->Id;
                    $proxFatura = $faturaAtual + 1;

                    //PEGA O INICIO DE FATURAMENTO
                    $data_adesao = $this->input->post('data_adesao');
                    $data_mensalidade_inicio = $this->input->post('data_mensalidade_inicio');
                    $data_mensalidade_fim = $this->input->post('data_mensalidade_fim');
                    $dt_adesao = $dt_mensalidade_inicio = $dt_mensalidade_fim = false;

                    //FORMATA A DATA DE VENCIMENTO DA ADESAO
                    if($data_adesao != ''){
                        $dia_adesao = get_data_exploded($contrato->data_prestacao, 2, false, '-');
                        $dt_adesao = data_for_unix( $dia_adesao.'/'.$data_adesao );
                    }

                    //FORMATA A DATA DE VENCIMENTO DA MENSALIDADE
                    $dia_mensalidade = sprintf('%02d', $contrato->vencimento);

                    if($data_mensalidade_inicio != '')
                        $dt_mensalidade_inicio = data_for_unix( $dia_mensalidade.'/'.$data_mensalidade_inicio );

                    if($data_mensalidade_fim != '')
                        $dt_mensalidade_fim = data_for_unix( $dia_mensalidade.'/'.$data_mensalidade_fim );


                    $adesoes = isset($meusItens['adesoes']) ? $meusItens['adesoes'] : array();
                    $itensFaturas = isset($meusItens['faturas']) ? $meusItens['faturas'] : array();

                    //GERAR ADESOES
                    if($contrato->data_prestacao != '0000/00/00' && count($adesoes) < $contrato->prestacoes){
                        list($proxFatura, $itensFaturas, $minhasFaturas, $novasFaturas, $novosItens, $attNumeroFaturas, $attValorFaturas) = $this->geraAdesao($impostos, $contrato, $proxFatura, $minhasFaturas, $novasFaturas, $itensFaturas, $adesoes, $novosItens, $attNumeroFaturas, $attValorFaturas, $dt_adesao);
                    }

                    //GERAR MENSALIDADES E TAXA DE BOLETO
                    list($proxFatura, $itensFaturas, $minhasFaturas, $novasFaturas, $novosItens, $attNumeroFaturas, $attValorFaturas) = $this->geraMensalidade($impostos, $contrato, $proxFatura, $minhasFaturas, $novasFaturas, $itensFaturas, $novosItens, $attNumeroFaturas, $attValorFaturas, $dt_mensalidade_inicio, $dt_mensalidade_fim);

                    //SALVA AS FATURAS NO BANCO
                    if ($novasFaturas && count($novasFaturas)>0){
                        foreach ($novasFaturas as &$novaFaturaTemp) {
                            $novaFaturaTemp['id_ps'] = 0;
                            $novaFaturaTemp['vencimento_fn'] = '1969-01-01';
                            $novaFaturaTemp['pagamento_fn'] = '1969-01-01';
                            $novaFaturaTemp['valor_fn'] = 0;
                            $novaFaturaTemp['valor_pago_fn'] = 0;
                            $novaFaturaTemp['multa_fn'] = 0;
                            $novaFaturaTemp['juros_fn'] = 0;
                            $novaFaturaTemp['status_fn'] = '';
                            $novaFaturaTemp['data_fn'] = '1969-01-01';
                            $novaFaturaTemp['hora_fn'] = '00:00:00';
                            $novaFaturaTemp['retorno_fn'] = '';
                            $novaFaturaTemp['competencia_fn'] = 0;
                            $novaFaturaTemp['id_contrato'] = $id_contrato;
                            $novaFaturaTemp['data_inclusao'] = date('Y-m-d', strtotime('today'));
                        }
                        $this->fatura->insertFaturasBatch($novasFaturas);
                    }
                    //SALVA OS ITENS DE FATURAS NO BANCO
                    if ($novosItens && count($novosItens)>0){
                        array_push($contratosComNovasFaturas, $id_contrato);
                        foreach ($novosItens as &$novoItem) {
                            if (!isset($novoItem['obs_item'])){
                                $novoItem['obs_item'] = '';
                            }
                        }
                        $this->fatura->insertItensBatch($novosItens);

                        foreach ($novosItens as $key => $itemNovo) {
                            $tipo_item = 'boleto';
                            if ($itemNovo['tipo_item'] != '')
                                $tipo_item = $itemNovo['tipo_item'];

                            $itensNovos[] = array(
                                'fatura' => $itemNovo['id_fatura'],
                                'contrato' => $id_contrato,
                                'item' => $tipo_item,
                                'vencimento' => data_for_humans($itemNovo['vencimento_item']),
                                'valor' => 'R$ '.number_format($itemNovo['valor_item'], 2, ',', '.')
                            );
                        }
                    } else {
                        array_push($contratosSemNovasFaturas, $id_contrato);
                    }

                    //ATUALIZA OS VALORES DAS FATURAS
                    if ($attValorFaturas && count($attValorFaturas)>0)
                        $this->fatura->updateValorFaturasBatch(implode(',', $attValorFaturas));

                    //ATUALIZA O NUMERO DAS FATURAS NECESSARIAS
                    if ($attNumeroFaturas && count($attNumeroFaturas)>0)
                        $this->attNumeroBoletos($attNumeroFaturas);

                }else {
                    echo json_encode( array('status' => false, 'msn' => 'Insira um contrato válido!') );
                }
            }

            if ($itensNovos && count($itensNovos)>0) {
                if (count($contratosSemNovasFaturas)>0){
                    echo json_encode( array('status' => 1, 'msn' => 'Mensalidades/Adesões geradas com sucesso para o(s) contrato(s): '. implode(', ', $contratosComNovasFaturas). '! <br>No entanto, os itens de fatura do(s) contrato(s) ' . implode(', ', $contratosSemNovasFaturas) . ' já haviam sido gerados!', 'itens_novos' => $itensNovos) );
                } else {
                    echo json_encode( array('status' => 0, 'msn' => 'Mensalidades/Adesões geradas com sucesso para o(s) contrato(s): '. implode(', ', $contratosComNovasFaturas) . '!', 'itens_novos' => $itensNovos) );
                }
            }else {
                if (count($ids_contratos) > 1){
                    echo json_encode( array('status' => 2, 'msn' => 'Os ítens de fatura já foram gerados para estes contratos!') );
                } else {
                    echo json_encode( array('status' => 2, 'msn' => 'Os ítens de fatura já foram gerados para este contrato!') );
                }
            }

        }else {
            echo json_encode( array('status' => false, 'msn' => 'O campo de contrato é obrigatório!') );
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

    /*
     * GERA MENSALIDADES DE UM CONTRTO
     */
    public function geraMensalidade($impostos, $contrato, $proxFatura, $minhasFaturas, $novasFaturas, $itensFaturas, $novosItens, $attNumeroFaturas, $attValorFaturas, $dt_mensalidade_inicio, $dt_mensalidade_fim)
    {   
        $ult_venc_mensal = '';

        if ($dt_mensalidade_inicio && (date($dt_mensalidade_inicio) >= date($contrato->primeira_mensalidade)))
            $primeira_mensalidade = $dt_mensalidade_inicio;
        else
            $primeira_mensalidade = $contrato->primeira_mensalidade;

        $primeira_mensalidade_temp = explode('-', $primeira_mensalidade);
        $ultimo_dia_mes = dias_no_mes($primeira_mensalidade_temp[1], $primeira_mensalidade_temp[0]);

        if ($primeira_mensalidade_temp[2] > $ultimo_dia_mes) {
            $primeira_mensalidade_temp[2] = $ultimo_dia_mes;
            $primeira_mensalidade = implode('-', $primeira_mensalidade_temp);
        }

        //gera as mensalidades faltantes
        for ($i = 1; $i <= $contrato->meses; $i++){
            $id_fat = $proxFatura;
            $adicionouItem = false;

            //CRIA A DESCRICAO
            if ( $contrato->tipo_proposta == 1 )
                $descricao = '[Contrato '.$contrato->id.'] Locação de SIM CARD {'.$contrato->quantidade_veiculos.' unidade(s)}';            
            elseif ($contrato->tipo_proposta == 7) 
                $descricao = '['. lang('contrato') .' '.$contrato->id.'] '. lang('licencimento_programas_customizados') .' {'.$contrato->quantidade_veiculos.' '. lang('unidade_unidades') .'}';
            else
                $descricao = '[Contrato '.$contrato->id.'] Locação de módulos para rastreamento veicular {'.$contrato->quantidade_veiculos.' unidade(s)}';

            $vencimento = $i == 1 ? $primeira_mensalidade : $ult_venc_mensal;

            //ANALIZA SE JA EXISTE FATURA PARA O VENCIMENTO
            if (isset($minhasFaturas['vencimentos']) && is_numeric($pos = array_search($vencimento, $minhasFaturas['vencimentos']) )) {

                $qtd_faturas = array_count_values($minhasFaturas['vencimentos']);
                $id_fat_paga = false;
                $id_fat_aberta = false;
                $temMensalidadeFatPaga = false;
                $temMensalidadeFatAberta = false;
                $posFatAberta = false;

                $tempMinhasFaturas = $minhasFaturas;

                //TRABALHA COM AS FATURAS DAQUELE VENCIMENTO
                for ($j=$qtd_faturas[$vencimento]; $j > 0; $j--) {

                    if ($tempMinhasFaturas['status'][$pos] == 1 ) {
                        $id_fat_paga = $tempMinhasFaturas['id'][$pos];
                        if (isset($itensFaturas[$id_fat_paga]) && in_array('mensalidade', $itensFaturas[$id_fat_paga])) {
                            $temMensalidadeFatPaga = true;
                        }                        
                        
                    }else {
                        $id_fat_aberta = $tempMinhasFaturas['id'][$pos];
                        $posFatAberta = $pos;
                        if (isset($itensFaturas[$id_fat_aberta]) && in_array('mensalidade', $itensFaturas[$id_fat_aberta])) {
                            $temMensalidadeFatAberta = true;
                        }
                    }

                    unset($tempMinhasFaturas['id'][$pos]);
                    unset($tempMinhasFaturas['vencimentos'][$pos]);
                    unset($tempMinhasFaturas['status'][$pos]);

                    $pos = array_search($vencimento, $tempMinhasFaturas['vencimentos']);
                }

                //SE TEM FATURA PAGA
                if ($id_fat_paga) {

                    if ($temMensalidadeFatPaga) {
                        break;

                    }else {
                        //SE NAO TEM FATURA ABERTA, GERA UMA NOVA FATURA PARA A MENSALIDADE
                        if (!$id_fat_aberta) {
                            $id_fat = $proxFatura;
                            
                            $dados = (object) [
                                'vencimento' => $vencimento,
                                'id_fat' => $id_fat,
                                'contrato' => $contrato,
                                'impostos' => $impostos,
                                'minhasFaturas' => $minhasFaturas,
                                'novasFaturas' => $novasFaturas,
                                'novosItens' => $novosItens,
                                'descricao' => $descricao,
                                'proxFatura' => $proxFatura
                            ];

                            //gera uma nova mensalidade para aquele vencimento
                            $novaMensalidade = $this->geraNovaMensalidade($dados);

                            //Atualiza os dados adicionados
                            $minhasFaturas = $novaMensalidade->minhasFaturas;
                            $novasFaturas = $novaMensalidade->novasFaturas;
                            $novosItens = $novaMensalidade->novosItens;
                            
                            $proxFatura++; 

                        
                        //SE TEM FATURA ABERTA
                        }else {
                            // $pos = $posFatPaga;
                            if (!$temMensalidadeFatAberta) {
                                //ADICIONA ITEM DE MENSALIDADE
                                $valor_item = $contrato->valor_mensal * $contrato->quantidade_veiculos;
                                $novosItens[] = $this->constroiItem('mensalidade', $id_fat_aberta, $contrato->id_cliente, $descricao, $contrato->id, $valor_item, $vencimento);
                                $itensFaturas[$id_fat_aberta][] = 'mensalidade';
                                $adicionouItem = true;
                            }

                            if ($contrato->boleto == 1 && !in_array('boleto', $itensFaturas[$id_fat_aberta])) {
                                //ADICIONA ITEM DE BOLETO
                                $novosItens[] = $this->constroiItem('boleto', $id_fat_aberta, $contrato->id_cliente, 'Taxa Boleto', 0, 4.5, $vencimento);                            
                                $itensFaturas[$id_fat_aberta][] = 'boleto';
                                $adicionouItem = true;
                            }
                        }
                    }                    

                }elseif ($id_fat_aberta) {
                    $pos = $posFatAberta;
                    if (!$temMensalidadeFatAberta) {
                        //ADICIONA ITEM DE MENSALIDADE
                        $valor_item = $contrato->valor_mensal * $contrato->quantidade_veiculos;
                        $novosItens[] = $this->constroiItem('mensalidade', $id_fat_aberta, $contrato->id_cliente, $descricao, $contrato->id, $valor_item, $vencimento);                            
                        $itensFaturas[$id_fat_aberta][] = 'mensalidade';
                        $adicionouItem = true;
                    }

                    if ($contrato->boleto == 1 && !in_array('boleto', $itensFaturas[$id_fat_aberta])) {
                        //ADICIONA ITEM DE BOLETO
                        $novosItens[] = $this->constroiItem('boleto', $id_fat_aberta, $contrato->id_cliente, 'Taxa Boleto', 0, 4.5, $vencimento);                            
                        $itensFaturas[$id_fat_aberta][] = 'boleto';
                        $adicionouItem = true;
                    }
                }

                if ($adicionouItem) {
                    //SALVA FATURA PARA ATUALIZAR NUMERO, CASO O STATUS DELA FOR '0'
                    if ( !in_array($id_fat_aberta, $attNumeroFaturas) && $minhasFaturas['status'][$pos] == 0 )
                        $attNumeroFaturas[] = $id_fat_aberta;

                    //GUARDA A FATURA PARA ATULIZAR SEU VALOR TOTAL MAIS TARDE
                    if (!in_array($id_fat_aberta, $attValorFaturas))
                        $attValorFaturas[] = $id_fat_aberta;
                }
            }
            else {

                $dados = (object) [
                    'vencimento' => $vencimento,
                    'id_fat' => $id_fat,
                    'contrato' => $contrato,
                    'impostos' => $impostos,
                    'minhasFaturas' => $minhasFaturas,
                    'novasFaturas' => $novasFaturas,
                    'novosItens' => $novosItens,
                    'descricao' => $descricao,
                    'proxFatura' => $proxFatura,
                    'itensFaturas' => $itensFaturas
                ];

                //gera uma nova mensalidade para aquele vencimento
                $novaMensalidade = $this->geraNovaMensalidade($dados);                

                //Atualiza os dados adicionados
                $minhasFaturas = $novaMensalidade->minhasFaturas;
                $novasFaturas = $novaMensalidade->novasFaturas;
                $novosItens = $novaMensalidade->novosItens;
                $itensFaturas = $novaMensalidade->itensFaturas;
                
                $proxFatura++;                 
                
                //GUARDA A FATURA PARA ATULIZAR SEU VALOR TOTAL MAIS TARDE
                if (!in_array($id_fat, $attValorFaturas))
                    $attValorFaturas[] = $id_fat;

                //SEMPRE QUE ADICIONAR UMA FATURA, INCREMENTA PARA TER O NUMERO DA PROXIMA
                $proxFatura++;
            }
            
            $vencimento_temp = explode('-', $vencimento);
            $vencimento = $vencimento_temp[0] . '-' . $vencimento_temp[1] . '-' . $contrato->vencimento;
            
            if ($dt_mensalidade_fim && (date($vencimento) >= date($dt_mensalidade_fim))){
                break;
            }

            //VAI PRA FATURA DO PROXIMO MES
            $ult_venc_mensal = proximaDataMes($vencimento);
        }
        if (count($novosItens)>0 || count($novasFaturas)>0) {
            return array(
                $proxFatura, 
                $itensFaturas, 
                $minhasFaturas, 
                $novasFaturas, 
                $novosItens, 
                $attNumeroFaturas, 
                $attValorFaturas
            );
        }
        return false;
    }

    //GERA UMA NOVA MENSALIDADE
    public function geraNovaMensalidade($dados){

        //MONTA INICIO E FIM DE PERIODO
        $periodo = $this->inicio_fim_periodo($dados->vencimento);

        //ADICIONA UMA NOVA FATURA
        $dados->novasFaturas[] = $this->constroiFatura(
            $dados->impostos, 
            $dados->id_fat, 
            $dados->contrato->id_cliente, 
            $dados->vencimento, 
            $periodo['referente'], 
            $periodo['inicial'], 
            $periodo['final']
        );

        $dados->minhasFaturas['id'][] = $dados->id_fat;
        $dados->minhasFaturas['vencimentos'][] = $dados->vencimento;
        $dados->minhasFaturas['status'][] = 2;

        //ADICIONA ITEM DE MENSALIDADE
        $valor_item = $dados->contrato->valor_mensal * $dados->contrato->quantidade_veiculos;
        $dados->novosItens[] = $this->constroiItem(
            'mensalidade', 
            $dados->id_fat, 
            $dados->contrato->id_cliente, 
            $dados->descricao, 
            $dados->contrato->id, 
            $valor_item, 
            $dados->vencimento
        );

        $dados->itensFaturas[$dados->id_fat][] = 'mensalidade';

        //ADICIONA ITEM DE BOLETO
        if ($dados->contrato->boleto == 1) {
            $dados->novosItens[] = $this->constroiItem(
                'boleto', 
                $dados->id_fat, 
                $dados->contrato->id_cliente, 
                'Taxa Boleto', 
                0, 
                4.5, 
                $dados->vencimento
            );

            $dados->itensFaturas[$dados->id_fat][] = 'boleto';
        }

        return $dados;
    }

    public function form_fatura_contrato_new() {
        $this->auth->is_allowed('faturas_add');
        $id_cliente = $this->input->get('id');
        $contratos = $this->ajax_contrato_cliente($id_cliente);
        $dados['id_cliente'] = $id_cliente;
        $dados['contratos'] = json_encode($contratos);

        $this->load->view('faturas/form_imprimir_contrato_new_NS', $dados);
    }

    public function imprimir_por_cliente() {
        $this->auth->is_allowed('faturas_visualiza');
        $id_cliente = $this->input->post('id_cliente');
        $id_contrato = $this->input->post('id_contrato');
        if ($id_cliente) {
            $dados['faturas'] = $this->fatura->listar_por_contrato("cad_faturas.id_cliente = {$id_cliente}
			AND itens.relid_item = {$id_contrato}
			AND cad_faturas.status IN ('0','2')",
                0, 99999, 'data_vencimento', 'ASC');

            if ($dados['faturas'])
                if ($dados['faturas'][0]->informacoes == "NORIO") {
                    $this->load->view('faturas/imprimir_fatura_norio', $dados);
                } else {
                    if ( $dados['faturas'][0]->informacoes == "SIMM2M" ) {
    	                $dados['logo'] = 'logo-simm2m.png';
    				}
                    $this->load->view('faturas/imprimir_fatura', $dados);
                }
            else
                echo 'Cliente não encontrado! Verifique o código do cliente';
        } else {
            echo 'Cliente não encontrado! Verifique o código do cliente';
        }
    }

    public function imprimir_fatura($id_fatura) {
        $this->auth->is_allowed('faturas_visualiza');
        $dados['faturas'] = $this->fatura->listar("cad_faturas.Id = {$id_fatura}", 0, 99999, 'data_vencimento', 'ASC');

        //CARREGA O ENDERECO PRINCIPAL
        $endereco = $this->endereco->getEnderecos($dados['faturas'][0]->id_cliente, '0')[0];
        if ($endereco) {
            $dados['faturas'][0]->endereco = $endereco->rua;
            $dados['faturas'][0]->numero = $endereco->numero;
            $dados['faturas'][0]->bairro = $endereco->bairro;
            $dados['faturas'][0]->complemento = $endereco->complemento;
            $dados['faturas'][0]->cep = $endereco->cep;
            $dados['faturas'][0]->cidade = $endereco->cidade;
            $dados['faturas'][0]->uf = $endereco->uf;
        }

        //CARREGA O EMAIL FINANCEIRO
        $email = $this->email_model->getEmails($dados['faturas'][0]->id_cliente, 'email', '0')[0];
        if ($email)
            $dados['faturas'][0]->email = $email->email;

        //CARREGA O TELEFONE FINANCEIRO
        $fone = $this->telefone->getTelefonesCliente($dados['faturas'][0]->id_cliente, 'numero', '0')[0];
        if ($fone)
            $dados['faturas'][0]->fone = $fone->numero;

        //muda o status para à pagar
        if ($dados['faturas'][0]->status_fatura == 2) {
            $this->fatura->atualizar_fatura($id_fatura, array('status' => 0));
        }
        if ($dados['faturas'][0]->informacoes == "NORIO") {
            $this->load->view('faturas/imprimir_fatura_norio', $dados);
        } else {
            if ( $dados['faturas'][0]->informacoes == "SIMM2M" ) {
                $dados['logo'] = 'logo-simm2m.png';
            }
            $this->load->view('faturas/imprimir_fatura', $dados);
        }
    }

    public function descritivo_fatura($id_fatura) {
        $this->auth->is_allowed('faturas_visualiza');
        $dados['faturas'] = $this->fatura->listar("cad_faturas.Id = {$id_fatura}", 0, 99999, 'data_vencimento', 'ASC');

        //CARREGA O ENDERECO PRINCIPAL
        $endereco = $this->endereco->getEnderecos($dados['faturas'][0]->id_cliente, '0')[0];
        if ($endereco) {
            $dados['faturas'][0]->endereco = $endereco->rua;
            $dados['faturas'][0]->numero = $endereco->numero;
            $dados['faturas'][0]->bairro = $endereco->bairro;
            $dados['faturas'][0]->complemento = $endereco->complemento;
            $dados['faturas'][0]->cep = $endereco->cep;
            $dados['faturas'][0]->cidade = $endereco->cidade;
            $dados['faturas'][0]->uf = $endereco->uf;
        }

        //CARREGA O EMAIL FINANCEIRO
        $email = $this->email_model->getEmails($dados['faturas'][0]->id_cliente, 'email', '0')[0];
        if ($email)
            $dados['faturas'][0]->email = $email->email;

        //CARREGA O TELEFONE FINANCEIRO
        $fone = $this->telefone->getTelefonesCliente($dados['faturas'][0]->id_cliente, 'numero', '0')[0];
        if ($fone)
            $dados['faturas'][0]->fone = $fone->numero;

        //muda o status para à pagar
        if ($dados['faturas'][0]->status_fatura == 2) {
            $this->fatura->atualizar_fatura($id_fatura, array('status' => 0));
        }

        if ( $dados['faturas'][0]->informacoes == "SIMM2M" ) {
                $dados['logo'] = 'logo-simm2m.png';
            }
        
        $this->load->view('faturas/descritivo_fatura', $dados);        
    }

    public function cancelar_sess() {
        $this->fatura->sess_fatura_delete();
        redirect('faturas/add');
    }

    /*
     * baixa faturas
    */
    public function baixar($pagina = false) {
        $this->auth->is_allowed('faturas_retorno');
        grava_url(current_url());
        $paginacao = $pagina != false  ? $pagina : 0;
        $where = array('cad_faturas.status' => 1);
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');
        $config['base_url'] = base_url().'faturas/baixar/';
        $config['uri_segment'] = 3;
        $config['total_rows'] = $this->fatura->total($where);
        $this->pagination->initialize($config);
        $dados['faturas'] = $this->fatura->listar($where, $paginacao, 10, 'data_pagto', 'DESC');
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['titulo'] = 'Baixar Retorno';
        $this->mapa_calor->registrar_acessos_url(site_url('/faturas/baixar'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('faturas/lista_baixadas');
        $this->load->view('fix/footer_NS');
    }

    public function form_baixa_automatica() {
        $this->auth->is_allowed('faturas_retorno');
        $this->load->view('faturas/form_baixa_automatica');
    }

    public function run_baixa_automatica() {
        $this->fatura->baixar_retorno();
    }

    public function abrir($id_fatura) {
        $this->auth->is_allowed('faturas_update');
        $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fatura));
        if($fatura->numero > 0 && $fatura->descricao != '' && $fatura->status_fatura != 1) {
            try {
                $this->fatura->migrar($fatura->Id, $fatura->numero);
                $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fatura));
            } catch (Exception $e) {
                $this->session->set_flashdata('msg', $e->getMessage());
            }
        }

        $dados['fatura'] = $fatura;
        $dados['itens'] = $this->fatura->get_items(array('id_fatura' => $id_fatura));
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['titulo'] = 'Fatura';
        $this->load->view('fix/header', $dados);
        $this->load->view('faturas/edit_fatura');
        $this->load->view('fix/footer');
    }

    public function abrir_NS($id_fatura) {
        $this->auth->is_allowed('faturas_update');
        $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fatura));
        if($fatura->numero > 0 && $fatura->descricao != '' && $fatura->status_fatura != 1) {
            try {
                $this->fatura->migrar($fatura->Id, $fatura->numero);
                $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fatura));
            } catch (Exception $e) {
                $this->session->set_flashdata('msg', $e->getMessage());
            }
        }

        $dados['fatura'] = $fatura;
        $dados['itens'] = $this->fatura->get_items(array('id_fatura' => $id_fatura));
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['titulo'] = 'Fatura';
        
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('faturas/edit_fatura_NS');
        $this->load->view('fix/footer_NS');
    }

    public function ajax_contrato_cliente($id_cliente) {
        $contratos = $this->contrato->listar(array('ctr.id_cliente' => $id_cliente), 0, 9999, $campo_ordem = 'ctr.id', $ordem = 'DESC', $select = 'ctr.id, ctr.id_cliente');

        if ($contratos){
            return $contratos;
        }else{
            return array();
        }
    }

    public function form_add_item_contrato($id_fatura) {
        $this->auth->is_allowed('faturas_update');
        $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fatura));
        $dados['contratos'] = $this->contrato->listar(array('contratos.id_cliente' => $fatura->id_cliente), 0, 99999);
        $dados['id_fatura'] = $fatura->Id;
        if($this->input->post()) {
            $id_contrato = $this->input->post('id_contrato');
            $item = $this->input->post('item');
            $itens_fatura = $this->fatura->get_items(array('fatura_itens.id_fatura' => $fatura->Id));
            if(!$this->fatura->have_item($item, $itens_fatura, $id_contrato)){
                $contrato = $this->contrato->get(array('contratos.id' => $id_contrato));
                if($contrato) {
                    if($item == 'mensalidade') {
                        $descricao = '[Contrato '.$contrato->id.'] Locação de módulos para rastreamento
								veicular {'.$contrato->quantidade_veiculos.' unidade(s)}';
                        $valor_item = $contrato->valor_mensal * $contrato->quantidade_veiculos;
                    } else {
                        $descricao = '[Contrato '.$contrato->id.'] Locação de módulos para rastreamento
								veicular {'.$contrato->quantidade_veiculos.' unidade(s)}';
                        $valor_item = $contrato->valor_mensal * $contrato->quantidade_veiculos;
                    }
                    $d_item = array('id_cliente' => $contrato->id_cliente, 'id_fatura' => $fatura->Id,
                        'tipo_item' => $item,
                        'descricao_item' => $descricao, 'relid_item' => $contrato->id,
                        'valor_item' => $valor_item,
                        'vencimento_item' => $fatura->data_vencimento);
                }
            }
        }
        $this->load->view('faturas/form_add_item_contrato_NS', $dados);
    }

    public function form_atualizar_data() {
        $id_fatura = $this->input->post('id_fatura');
        $data_nova = data_for_unix($this->input->post('data_nova'));
        $this->auth->is_allowed('faturas_update');
        if($data_nova >= date('Y-m-d')) {
            $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fatura));
            if(count($fatura) > 0) {
                try {
                    $this->fatura->atualizar_vencimento($fatura->Id, $data_nova);
                    $nova_fatura = $this->atualizar_numero_boleto($id_fatura);
                    echo json_encode(array('status' => 'OK', 'msg' => '<div class="alert alert-success"><p><b>Fatura atualizada com sucesso.</b></p></div>', 'nova_fatura' => $nova_fatura));

                } catch(Exception $e) {
                    echo json_encode(array('status' => 'False', 'msg', $e->getMessage()));
                }
            }
            else {
                echo json_encode(array('status' => 'False', 'msg' => '<div class="alert alert-error"><p><b>Falha ao atualizar a fatura, tente mais tarde!</b></p></div>'));
            }
        }else {
            echo json_encode(array('status' => 'False', 'msg' => '<div class="alert alert-error"><p><b>A data de vencimento precisa ser maior ou igual a data de hoje!</b></p></div>'));
        }

    }

    /*
    * CANCELA A FATURA ATUAL E GERA UMA NOVA COM AS MESMAS CARACTERÍSTICAS QUE A ANTERIOR
    */
    public function atualizar_numero_boleto($id_fatura=false){

        if ($this->input->get('id_fatura')) {
            $id_fatura = $this->input->get('id_fatura');
        }

        if($id_fatura) {
            $fatura['fatura'] = (array)$this->fatura->get(array('cad_faturas.Id' => $id_fatura),true);
            $fatura['itens']= (array)$this->fatura->get_items(array('id_fatura'=>$fatura['fatura']['Id']));
            foreach($fatura['itens'] as $key=>$item){
                unset($item->id_fatura);
                unset($item->id_item);
                $fatura['itens'][$key] = (array)$item;
            }
            unset($fatura['fatura']['Id']);
            unset($fatura['fatura']['numero']);
            try {
                $hoje = date('Y-m-d');
                $d_atualiza = array('datacancel_fatura' => $hoje, 'status' => 3, 'instrucoes1' => "Atualização de fatura");
                if($this->fatura->atualizar_fatura($id_fatura, $d_atualiza)) {
                    $nova_fatura = $this->fatura->gravar_fatura($fatura);
                    $this->fatura->inserir_fatura_log(array('fatura_antiga'=>$id_fatura,'fatura_atualizada'=>$nova_fatura,'usuario'=>$this->auth->get_login('admin', 'email'),'data_atualizacao'=>date('Y-m-d H:m:s'),'descricao'=>$this->auth->get_login('admin', 'email')." atualizou a fatura."));
                    $total_fat = $this->fatura->total_fatura($nova_fatura);
                    $this->fatura->atualizar_fatura($nova_fatura, array('valor_total' => $total_fat));
                    $this->session->set_flashdata('msg', 'Fatura atualizada com sucesso.');
                    return $nova_fatura;
                } else {
                    $this->session->set_flashdata('msg', 'Não foi possível atualizar a fatura. Tente novamente.');
                }
            } catch (Exception $e) {
                $this->session->set_flashdata('msg', $e->getMessage());
            }
        }
        return false;
    }

    public function form_remove_item($id_item, $id_fatura) {
        $this->auth->is_allowed('faturas_update');
        if($this->input->post('id_item')) {
            $item = $this->fatura->get_item(array('id_item' => $id_item));
            if (count($item) > 0) {
                if (md5($this->input->post('senha_exclusao')) == $this->fatura->senhaExclusaoFatura()) {
                    if($this->fatura->delete_item($id_item)) {
                        $this->fatura->atualizar_fatura($item->id_fatura, array('valor_total' => $this->fatura->total_fatura($item->id_fatura)));
                        $this->session->set_flashdata('msg', 'Item removido com sucesso.');
                    } else {
                        $this->session->set_flashdata('msg', 'Não foi possível excluir o item. Tente novamente.');
                    }
                } else {
                    $this->session->set_flashdata('msg', 'Senha inválida. Não foi possível excluir.');
                }
            } else {
                $this->session->set_flashdata('msg', 'Item não encontrado. Não foi possível excluir.');
            }
            redirect('faturas/abrir_NS/'.$id_fatura);
        }
        $dados['id_item'] = $id_item;
        $dados['id_fatura'] = $id_fatura;
        $this->load->view('faturas/form_remove_item_fatura_NS', $dados);
    }

    public function form_baixa_manual($id_fatura) {
        $this->auth->is_allowed('faturas_update');
        if($this->input->post('id_fatura')) {
            $id_fat = $this->input->post('id_fatura');
            $f_pagamento = $this->input->post('f_pagamento');
            $valor = $this->input->post('valor_pagto');
            $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fat));
            if (count($fatura) > 0) {
                if ($this->input->post('senha_exclusao') == $this->config->item('senha_exclusao')) {
                    $hoje = date('Y-m-d');
                    $d_atualiza = array('data_pagto' => $hoje, 'valor_pago' => $valor, 'status' => 1, 'formapagamento_fatura' => $f_pagamento);
                    if ($this->fatura->atualizar_fatura($fatura->Id, $d_atualiza)) {
                        $this->session->set_flashdata('msg', 'Fatura atualizada com sucesso.');
                    } else {
                        $this->session->set_flashdata('msg', 'Não gravar no banco de dados. Tente novamente.');
                    }
                } else {
                    $this->session->set_flashdata('msg', 'Senha inválida. Não foi possível atualizar a fatura.');
                }
            } else {
                $this->session->set_flashdata('msg', 'Fatura não encontrada. Verifique se existe uma fatura com esse código');
            }
            redirect('faturas/abrir_NS/'.$id_fatura);
        }
        $dados['id_fatura'] = $id_fatura;
        $this->load->view('faturas/form_baixa_fatura_NS', $dados);
    }

    public function form_cancelar_fatura($id_fatura) {
        $this->auth->is_allowed('faturas_update');
        if($this->input->post('id_fatura')) {
            $id_fat = $this->input->post('id_fatura');
            $motivo = $this->input->post('motivo');
            $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fat, 'cad_faturas.status !=' => 1));
            $senha = md5($this->input->post('senha_exclusao'));

            if (count($fatura) > 0) {
                if ($senha == $this->fatura->senhaExclusaoFatura()) {
                    $hoje = date('Y-m-d');
                    $d_atualiza = array('datacancel_fatura' => $hoje, 'status' => 3, 'instrucoes1' => $motivo);
                    if($this->fatura->atualizar_fatura($fatura->Id, $d_atualiza)) {
                        $this->session->set_flashdata('msg', 'Fatura cancelada com sucesso.');
                    } else {
                        $this->session->set_flashdata('msg', 'Não gravar no banco de dados. Tente novamente.');
                    }
                } else {
                    $this->session->set_flashdata('msg', 'Senha inválida. Não foi possível atualizar a fatura.');
                }
            } else {
                $this->session->set_flashdata('msg', 'Fatura já está paga e não pode ser cancelada.');
            }
            redirect('faturas/abrir_NS/'.$id_fatura);
        }
        $dados['id_fatura'] = $id_fatura;
        $this->load->view('faturas/form_cancela_fatura_NS', $dados);
    }

    public function form_cancelar_varias_fatura() {
        $retorno = [];
        $retorno['msg'] = "<h4>Última operação</h4>";
        $retorno['status'] = [];
        foreach($this->input->post('cod_fatura') as $id_fatura){

            $this->auth->is_allowed('faturas_update');
            if($id_fatura) {
                $id_fat = $id_fatura;
                $motivo = $this->input->post('motivo');
                $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fat, 'cad_faturas.status !=' => 1));
                $senha = md5($this->input->post('senha_exclusao'));

                if (count($fatura) > 0) {
                    if ($senha == $this->fatura->senhaExclusaoFatura()) {
                        $hoje = date('Y-m-d');
                        $d_atualiza = array('datacancel_fatura' => $hoje, 'status' => 3, 'instrucoes1' => $motivo);
                        if($this->fatura->atualizar_fatura($fatura->Id, $d_atualiza)) {
                            $retorno['msg']= $retorno['msg'].'<h5>Fatura #'.$id_fatura.' cancelada com sucesso.</h5>';
                            $retorno['status'][]=$id_fatura;
                        }
                    } else {
                        $retorno['msg']=$retorno['msg']. '<h5>Senha inválida. Não foi possível atualizar as faturas.</h5>';
                        break;
                    }
                } else {
                    $retorno['msg']= $retorno['msg'].'<h5>Fatura #'.$id_fatura.' já está paga e não pode ser cancelada.</h5>';
                }
            }
        }
        echo json_encode($retorno);
    }

    public function a_cancelar_faturas_individual() {
        $dados = $this->input->post();
        $id_usuario = $this->auth->get_login_dados('user');
        if ($dados['senha'] != '') {
            if (isset($_SESSION['tokenACancelar'])){
                if ( $dados['senha'] == $_SESSION['tokenACancelar']) {
                    $validadeSenha = $this->validadeSenha();
                    if ($validadeSenha) {
                        if ($this->fatura->transfereFatura($dados['id_fatura'], $dados['status'])){
                            $this->add_coment_fat_a_cancelar($dados['motivo'], $dados['id_fatura']);
                            echo json_encode(array('mensagem' => 'Status da fatura '.$dados['id_fatura'].' Alterado para À CANCELAR com sucesso.', 'status' => 'OK'));
                            $this->log_shownet->gravar_log($id_usuario, 'cad_faturas', $dados['id_fatura'], 'atualizar', $dados['statusAtual'], $dados['status']);
                        }else{
                            echo json_encode(array('mensagem' => 'Não foi possível mudar o status da fatura para "à cancelar".', 'status' => 'erro'));
                        }
                    }else{
                        echo json_encode(array('mensagem' => 'Sua senha anteriormente gerada expirou. Para receber uma nova senha, clique no botão "Gerar Senha".', 'status' => 'erro'));
                    }
                }else {
                    echo json_encode(array('mensagem' => 'Senha incorreta!', 'status' => 'erro'));
                }
            }else {
                echo json_encode(array('mensagem' => 'Senha incorreta!', 'status' => 'erro'));
            }
        }else {
            $somador = 0.0;
            $faturaAtual = $this->fatura->get(array('Id' => $dados['id_fatura']), true, 'valor_total');
            $faturas =  explode(",", $dados['faturas_substitutas']);
            foreach ($faturas as $key => $id_fatura) {
                $fatura = $this->fatura->get(array('Id' => $id_fatura), true, 'valor_total');
                if ($fatura) {
                    $somador += $fatura->valor_total;
                }
            }
            if ($somador>0) {
                if ($somador <= $faturaAtual->valor_total+5 && $somador >= $faturaAtual->valor_total-5) {
                    $this->add_coment_fat_a_cancelar($dados['motivo'], $dados['id_fatura']);
                    if ($this->fatura->transfereFatura($dados['id_fatura'], $dados['status'])){
                        echo json_encode(array('mensagem' => 'Status da fatura '.$dados['id_fatura'].' Alterado para À CANCELAR com sucesso.', 'status' => 'OK'));
                    }else{
                        echo json_encode(array('mensagem' => 'Não foi possível mudar o status da fatura para "à cancelar".', 'status' => 'erro'));
                    }
                }else{
                    echo json_encode(array('mensagem' => 'O somatório dos valores das faturas substitutas não bate com o valor da fatura atual com margem de erro de R$5,00.', 'status' => 'erro'));
                }
            }else {
                echo json_encode(array('mensagem' => 'Fatura(s) substituta(s) não cadastradas no sistema!', 'status' => 'erro'));
            }

        }
    }

    public function a_cancelar_faturas_lote() {

        $dados = $this->input->post();
        if ($dados['senha'] != '') {
            if ( md5($dados['senha']) == $this->fatura->senha_a_cancelar_fatura() ) {
                $fat_a_cancelar =  '';
                $fat_pagas = '';
                $this->auth->is_allowed('faturas_update');

                foreach($dados['faturas'] as $id_fatura){
                    $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fatura, 'cad_faturas.status !=' => 1));
                    if (count($fatura) > 0) {
                        $d_atualiza = array('status' => 4);
                        if($this->fatura->atualizar_fatura($fatura->Id, $d_atualiza)) {
                            $this->add_coment_fat_a_cancelar($dados['motivo'], $id_fatura);
                            $fat_a_cancelar .= $id_fatura.' ';
                        }
                    } else {
                        $fat_pagas .= $id_fatura.' ';
                    }
                }
                if ($fat_a_cancelar == '') {
                    echo json_encode( array( 'status' => 'todas_pagas', 'msn' => 'Faturas já encontram-se como "à cancelar". ' ) );
                }elseif ($fat_a_cancelar != '' && $fat_pagas == '') {
                    echo json_encode( array( 'status' => 'todas_a_cancel', 'a_cancelar' => $fat_a_cancelar, 'msn' => 'Alterado status da(s) fatura(s): '.$fat_a_cancelar.',  para À CANCELAR.' ) );
                }elseif ($fat_a_cancelar != '' && $fat_pagas != '') {
                    echo json_encode( array( 'status' => 'pagas_a_cancel', 'a_cancelar' => $fat_a_cancelar, 'pagas' => $fat_pagas, 'msn' => 'Alterado status das faturas: '.$fat_a_cancelar.',  para à cancelar, com sucesso.<br >'+'As faturas: ['.$fat_pagas.'], já está(ão) paga(as) e não pode(em) ser cancelada(as)' ) );
                }

            }else {
                echo json_encode(array('msn' => 'Senha Incorreta!', 'status' => 'erro'));
            }

        }else {
            $exist_duplicata = false;
            $somatorioFaturas = $somatorioFats = 0.0;
            foreach ($dados['faturas'] as $key => $id_fatura) {
                $fatura = $this->fatura->get(array('Id' => $id_fatura), true, 'valor_total');
                if ($fatura) {
                    $somatorioFaturas += $fatura->valor_total;
                }
            }
            $fats =  explode(",", $dados['faturas_substitutas']);
            foreach ($fats as $key => $id_fat) {
                if (in_array($id_fat, $dados['faturas'])) {
                    $exist_duplicata = true;
                    break;
                }
                $fat = $this->fatura->get(array('Id' => $id_fat), true, 'valor_total');
                if ($fat) {
                    $somatorioFats += $fat->valor_total;
                }
            }

            if ($exist_duplicata) {
                echo json_encode(array('msn' => 'Não é permitido que uma fatura substituta também esteja entre as faturas "à cancelar".', 'status' => 'erro'));
            }else {
                if ($somatorioFats <= $somatorioFaturas+5 && $somatorioFats >= $somatorioFaturas-5) {
                    $fat_a_cancelar =  '';
                    $fat_pagas = '';
                    $this->auth->is_allowed('faturas_update');

                    foreach($dados['faturas'] as $id_fatura){
                        $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fatura, 'cad_faturas.status !=' => 1));

                        if (count($fatura) > 0) {
                            $d_atualiza = array('status' => 4);
                            if($this->fatura->atualizar_fatura($fatura->Id, $d_atualiza)) {
                                $this->add_coment_fat_a_cancelar($dados['motivo'], $id_fatura);
                                $fat_a_cancelar .= $id_fatura.' ';
                            }
                        } else {
                            $fat_pagas .= $id_fatura.' ';
                        }
                    }

                    if ($fat_a_cancelar == '') {
                        echo json_encode( array( 'status' => 'todas_pagas', 'msn' => 'Faturas já encontram-se como "à cancelar". ' ) );
                    }elseif ($fat_a_cancelar != '' && $fat_pagas == '') {
                        echo json_encode( array( 'status' => 'todas_a_cancel', 'a_cancelar' => $fat_a_cancelar, 'msn' => 'Alterado status da(s) fatura(s): '.$fat_a_cancelar.',  para À CANCELAR.' ) );
                    }elseif ($fat_a_cancelar != '' && $fat_pagas != '') {
                        echo json_encode( array( 'status' => 'pagas_a_cancel', 'a_cancelar' => $fat_a_cancelar, 'pagas' => $fat_pagas, 'msn' => 'Alterado status das faturas: '.$fat_a_cancelar.',  para à cancelar, com sucesso.<br >'+'As faturas: ['.$fat_pagas.'], já está(ão) paga(as) e não pode(em) ser cancelada(as)' ) );
                    }

                }else{
                    echo json_encode(array('msn' => 'O somatório dos valores das faturas substitutas não bate com o somatório das faturas atuais com margem de erro de R$5,00.', 'status' => 'erro'));
                }
            }

        }

    }

    public function add_coment_fat_a_cancelar($comentario, $id_fatura) {
            $data['comentario'] = 'Alterado para à cancelar, Motivo: ' . $comentario;
            $data['id_fatura'] = $id_fatura;
            $data['user'] = $this->auth->get_login('admin', 'nome');
            $this->fatura->saveComents($data);

    }

    public function enviar_retorno() {
        $this->load->model('send_file');
        if($_SERVER['REQUEST_METHOD'] == 'POST')
            echo $this->send_file->envia_http( $this->input->post() );
    }

    public function ajax_baixa_retorno() {
        $this->load->model('conta');
        $dados['fats_retorno'] = array();
        $arquivo = $this->session->flashdata('file_retorno');
        if ($arquivo) {
            //baixando faturas
            $fats_retorno = $this->retorno->listar(array('arquivo_retorno' => $arquivo,'operacao'=>'C'));
            if (count($fats_retorno) > 0) {
                $dados['content_retorno'] = $this->fatura->baixa_novo_retorno($fats_retorno);

            } else {
                echo 'Nenhuma fatura processada no retorno';
            }
            //baixando contas
            $contas_retorno = $this->retorno->listar(array('arquivo_retorno' => $arquivo,'operacao'=>'D'));
            if (count($contas_retorno) > 0) {
                $dados['content_retorno_pagamento'] = $this->conta->baixa_novo_retorno($contas_retorno);
            } else {
                echo 'Nenhuma conta processada no retorno';
            }

            $this->load->view('faturas/retorno_baixa', $dados);
        } else {
            echo 'Nenhuma fatura/conta encontrada nesse retorno';
        }
    }
    public function baixa_retorno_manual() {
        $json_fatura[] = json_decode($this->input->post('json'));
        if ($json_fatura) {
            $retorno_fatura = $this->fatura->baixa_novo_retorno($json_fatura);
            $this->retorno->baixa_manual($retorno_fatura,$json_fatura[0]);
            echo json_encode($retorno_fatura);
        } else {
            echo 'Passe um item do retorno';
        }
    }

    public function baixa_pendente() {
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['retornos'] = $this->retorno->listar(array('statusexec_retorno' => 'erro'));
        $dados['titulo'] = 'Fatura';
        $this->load->view('fix/header', $dados);
        $this->load->view('faturas/retorno_pendente');
        $this->load->view('fix/footer');
    }

    public function form_update_emissao($id_fatura) {
        $this->auth->is_allowed('faturas_update');
        if($this->input->post('id_fatura')) {
            $id_fat = $this->input->post('id_fatura');
            $data = data_for_unix($this->input->post('data_emissao'));
            $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fat));
            if (is_date($data)) {
                if (count($fatura) > 0) {
                    if ($this->input->post('senha_exclusao') == $this->config->item('senha_exclusao')) {
                        $d_atualiza = array('data_emissao' => $data);
                        if($this->fatura->atualizar_fatura($fatura->Id, $d_atualiza)) {
                            $this->session->set_flashdata('msg', 'Data de emissão atualizada com sucesso.');
                        } else {
                            $this->session->set_flashdata('msg', 'Não gravar no banco de dados. Tente novamente.');
                        }
                    } else {
                        $this->session->set_flashdata('msg', 'Senha inválida. Não foi possível atualizar a fatura.');
                    }
                } else {
                    $this->session->set_flashdata('msg', 'Fatura não encontrada.');
                }
            } else {
                $this->session->set_flashdata('msg', 'Não foi possível atualizar. A data informada não é válida.');
            }
            redirect('faturas/abrir_NS/'.$id_fatura);
        }
        $dados['id_fatura'] = $id_fatura;
        $this->load->view('faturas/form_update_emissao_NS', $dados);
    }
    public function form_update_fatura() {
        $this->auth->is_allowed('faturas_update');
        $id_fat = $this->input->post('id_fatura');
        $id_comment = $this->input->post('id_comment');
        $id_fatura=$id_fat;
        var_dump($id_fat);
        if($id_comment){
            $comment = $this->input->post('comment');
            $this->fatura->update_comment_item($id_comment,$comment);
            die;
        }
        if($id_fat) {
            $nota_fiscal = $this->input->post('nota_fiscal');
            $mes_referencia = $this->input->post('mes_referencia');
            $periodo_final = data_for_unix($this->input->post('periodo_final'));
            $periodo_inicial = data_for_unix($this->input->post('periodo_inicial'));
            $fatura = $this->fatura->get(array('cad_faturas.Id' => $id_fat));
            $chave = $this->input->post('chave_nfe');
            //var_dump($id_fat);
            if (count($fatura) > 0) {
                $d_atualiza = array('nota_fiscal' => $nota_fiscal,'mes_referencia'=>$mes_referencia,'periodo_inicial'=>$periodo_inicial,'periodo_final'=>$periodo_final, 'chave_nfe' => $chave);
                /*var_dump($fatura);
                echo "<br><br>";
                var_dump($d_atualiza);die;*/
                if($this->fatura->atualizar_fatura($fatura->Id, $d_atualiza)) {
                    $this->session->set_flashdata('msg', 'Fatura atualizada com sucesso.');
                }
                else {
                    $this->session->set_flashdata('msg', 'Não foi possível gravar no banco de dados. Tente novamente.');
                }
                /*if ($this->input->post('senha_exclusao') == $this->config->item('senha_exclusao')) {
                    $d_atualiza = array('data_emissao' => $data);
                    if($this->fatura->atualizar_fatura($fatura->Id, $d_atualiza)) {
                        $this->session->set_flashdata('msg', 'Data de emissão atualizada com sucesso.');
                    } else {
                        $this->session->set_flashdata('msg', 'Não gravar no banco de dados. Tente novamente.');
                    }
                } else {
                    $this->session->set_flashdata('msg', 'Senha inválida. Não foi possível atualizar a fatura.');
                }*/
            } else {
                $this->session->set_flashdata('msg', 'Fatura não encontrada.');
            }
            redirect('faturas/abrir_NS/'.$id_fatura);
        }
    }

    public function gera_mensalidade_automatizado($mes = false) {
        if (!$mes) $mes = date('m');
        $clientes = $this->cliente->listar(array('status' => 1));
        if (count($clientes) > 0) {
            foreach ($clientes as $cliente) {
                $this->fatura->gerar_fatura_clientes($cliente->id, $mes);
            }
        }
    }

    public function enviar($numero_fatura) {
        $this->auth->is_allowed('faturas_visualiza');
        $fatura = $this->fatura->get(array('cad_faturas.numero' => $numero_fatura));
        if (count($fatura) > 0) {
            $now = date('Y-m-d H:i:s');
            $d_envio[] = array('id_fatura' => $fatura->numero, 'dhcad_envio' => $now, 'msg_envio' => 'envio manual');
            $envia = $this->envio_fatura->inserir($d_envio);
            if ($envia) {
                $this->fatura->atualizar_fatura($fatura->Id, array('status' => 0));
                echo 'Fatura #'.$fatura->numero.' enviada para caixa de saída com sucesso.';
            } else {
                echo 'Não foi possível enviar a fatura. Tente novamente.';
            }
        } else {
            echo 'Fatura não encontrada.';
        }
    }

    public function teste() {
        $this->fatura->__set('id', 30125);
    }

    public function emitir() {
        $dados['retorno'] = false;
        $dados['block'] = true;
        if($this->input->post())
            echo "olar";
        $dados['titulo'] = 'Show Tecnologia - Faturas';
        $this->load->view('faturas/emitir', $dados);
    }

    public function gerar_fatura_eptc() {
        if($this->input->post('prefixo')) {
            $dados = array();
            if($this->fatura->validar_permissionario($this->input->post('prefixo'), $this->input->post('cpf'))) {
                $kms = 0;
                $valor = 0;
                if($this->input->post('kms') > 1) {
                    $valor = $this->input->post('kms')*2;
                    $kms = $valor;
                }
                if($this->input->post('procedimento') == 1) {
                    $valor += '344.65';
                    $descricao = 'Reposição do GPS por dano ou extravio + Reinstalação: R$344.65';
                } elseif($this->input->post('procedimento') == 2) {
                    $valor += '94.22';
                    $descricao = 'Retirar e reinstalar equipamento: R$94.22';
                }
                $db_fatura['fatura'] = array('id_cliente' => '2089', 'data_emissao' => date('Y-m-d'),
                    'data_vencimento' => date('Y-m-d'), 'id_contrato' => $this->input->post('prefixo'),
                    'formapagamento_fatura' => '1',
                    'valor_total' => $valor, 'descricao' => $descricao, 'status' => 0);
                try {
                    $this->fatura->gravar_fatura($db_fatura, true);
                } catch (Exception $e) {
                    $this->session->set_flashdata('msg', $e->getMessage());
                }
                $id = $this->fatura->get_ultimo();
                $id = $id->Id;
                $itens[] = array('id_cliente' => '2089', 'id_fatura' => $id, 'tipo_item' => 'avulso',
                    'descricao_item' => "Pref: ".$this->input->post("prefixo")." - ".$descricao, 'valor_item' => $valor, 'taxa_item' => 'nao',
                    'vencimento_item' => date('Y-m-d'));
                if($kms > 1) {
                    $itens[] = array('id_cliente' => '2089', 'id_fatura' => $id, 'tipo_item' => 'avulso',
                        'descricao_item' => "Pref: ".$this->input->post("prefixo")." - KM's rodados: ".$this->input->post('kms'),
                        'valor_item' => $kms, 'taxa_item' => 'nao',
                        'vencimento_item' => date('Y-m-d'));
                }
                $this->fatura->gravar_itens($itens);
                $dados['kms'] = $kms;
                $dados['faturas'] = $this->fatura->listar("cad_faturas.Id = {$id}", 0, 99999, 'data_vencimento', 'ASC');
                $this->load->view('faturas/imprimir_fatura', $dados);
            } else {
                $dados['retorno'] = false;
                $dados['block'] = false;
                $this->load->view('faturas/emitir', $dados);
            }
        } else {
            $dados['retorno'] = false;
            $dados['block'] = false;
            $this->load->view('faturas/emitir', $dados);
        }
    }

    public function debitos($c_order = false, $order = false, $pagina = false, $yes=true,$ordernar=false) {
        $this->session->set_userdata('fatura_id_check', []);
        $this->auth->is_allowed('faturas_visualiza');
        grava_url(current_url());
        $ordernar = ($ordernar == false ||$ordernar == "false")  ? 'cad_faturas.Id' : $ordernar;
        $order = ($order == false||$order == "false") ? 'desc' : $order;
        $dados['order'] = $order;
        $dados['c_order'] = $c_order;
        $dados['id_cliente'] = $c_order;
        $dados['toogle_order'] = $order == 'desc' ? 'asc' : 'desc';
        if($this->session->userdata('filtro_fatura'))
            $this->session->unset_userdata('filtro_fatura');
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');
        $config['base_url'] = base_url().'faturas/index/'.$c_order.'/'.$order.'/';
        $config['uri_segment'] = 5;
        $config['total_rows'] = $this->fatura->total();
        $this->pagination->initialize($config);
        $dados['faturas'] = $this->fatura->listar(array('id_cliente' => $c_order), 0, 100000000,$ordernar,$order);
        if (boolval($yes)) {
            $dados['titulo'] = "Show Tecnologia - Débito";
            $this->load->view('fix/header');
            $this->load->view('clientes/tab_debitos', $dados);
            $this->load->view('fix/footer');
        } else {
            $dados['titulo'] = "Show Tecnologia - Débito";
            $this->load->view('clientes/tab_debitos', $dados);
        }
    }

    public function getSecretarias(){
        $id_cliente = $this->input->post('id_cliente');
        $secretarias =  $this->cadastro->get_grupo($id_cliente, 'id, nome');
        echo json_encode($secretarias);
    }

    //LISTA AS FATURAS DO CLIENTE EM SERVE-SIDE
    public function ajaxDebitosServeSide(){

        $dados = $this->input->post();
        $order = $dados['order'][0] ? $dados['order'][0] : false;
        $draw = $dados['draw'] ? $dados['draw'] : 1;
        $start = $dados['start'] ? $dados['start'] : 0;
        $limit = $dados['length'] ? $dados['length'] : 10;
        $search = $dados['searchDebitos'] ? $dados['searchDebitos'] : false;
        $filtro = $dados['filtroDebitos'] ? $dados['filtroDebitos'] : false;
        $id_cliente = $dados['id_cliente'];
        $secretaria = $dados['secretaria'] ? $dados['secretaria'] : false;

        $status = array();

        if ($dados['status'] == 'f_abertas') $status = ['0', '2'];
        elseif ($dados['status'] == 'f_pagas') $status = ['1'];
        elseif ($dados['status'] == 'f_a_cancelar') $status = ['4'];
        elseif ($dados['status'] == 'f_canceladas') $status = ['3'];

        if ($id_cliente) {
            $paramentro_consulta =array('id_cliente' => $id_cliente);
            if (isset ($secretaria)) {
                if ($secretaria!='-1') {
                    $paramentro_consulta['id_secretaria'] = $secretaria;
                }
            }
            //CARREGA AS FATURAS
            $retorno = $this->fatura->listFaturasServeside(
                $paramentro_consulta,
                $status, $filtro,
                'Id, valor_total, valor_pago, id_secretaria, status, data_vencimento, dataatualizado_fatura, data_emissao, atividade, nota_fiscal, mes_referencia, periodo_inicial, periodo_final, data_pagto, id_ticket, numero, iss, pis, csll, irpj, cofins',
                $order, $start, $limit, $search, $draw
            );

            if ($retorno['faturas']) {
                $dados = array();
                $instalacao = 0;
                $itens_ativos = 0;

                $grupos =  $this->cadastro->get_grupo($id_cliente, 'id, nome');

                foreach ($retorno['faturas'] as $key => $f){

                    $valor_total = $f->valor_total - ($f->valor_total * $f->irpj/100) - ($f->valor_total * $f->pis/100) - ($f->valor_total * $f->cofins/100) - ($f->valor_total * $f->iss/100) - ($f->valor_total * $f->csll/100);
                    $sub_total = $f->valor_total;
                    
                    if (!$f->valor_pago) {
                        $f->valor_pago = 0.0;
                    }

                    foreach ($grupos as $key => $grupo) {
                        if ($f->id_secretaria == $grupo->id) {
                            $secretaria = $grupo->nome;
                            break;
                        }
                    }

                    $btn_boleto = '<a title="Ação não permitida" ><i class="fa fa-barcode"></i> Boleto</a>';
                    $btn_paypal = '<a title="Ação não permitida" ><i class="fa fa-paypal"></i> Paypal</a>';

                    switch ($f->status) {
                        case '0':
                            $tipo_status = 'f_aberto';
                            if ($this->auth->is_allowed_block('alterar_status_a_cancelar')) {
                                $acao_pag = '<a href="" id="opcaoDropACancelar" onclick="return false;" class="acao_a_cancelar cancel_'.$f->Id.' " data-id_fatura="'.$f->Id.'" data-status="4" data-status_atual="0"><i class="fa fa-exchange"></i> A Cancelar</a>';
                            }
                            $btn_boleto = '<a href="" data-id_fatura="'.$f->Id.'" data-dataatualizado_fatura="'.$f->dataatualizado_fatura.'" data-data_emissao="'.$f->data_emissao.'" data-data_vencimento="'.$f->data_vencimento.'" data-tipo_pagamento="boleto" class="gerar_pagamento" onclick="return false;" ><i class="fa fa-barcode"></i> Boleto</a>';
                            $btn_paypal = '<a href="" data-id_fatura="'.$f->Id.'" data-dataatualizado_fatura="'.$f->dataatualizado_fatura.'" data-data_emissao="'.$f->data_emissao.'" data-data_vencimento="'.$f->data_vencimento.'" data-tipo_pagamento="paypal" class="gerar_pagamento" onclick="return false;" ><i class="fa fa-paypal"></i> Paypal</a>';
                            break;
                        case '2':
                            $tipo_status = 'f_aberto';
                            if ($this->auth->is_allowed_block('alterar_status_a_cancelar')) {
                                $acao_pag = '<a href="" id="opcaoDropACancelar" onclick="return false;" class="acao_a_cancelar cancel_'.$f->Id.' " data-id_fatura="'.$f->Id.'" data-status="4" data-status_atual="2"><i class="fa fa-exchange"></i> A Cancelar</a>';
                            }
                            $btn_boleto = '<a href="" data-id_fatura="'.$f->Id.'" data-dataatualizado_fatura="'.$f->dataatualizado_fatura.'" data-data_emissao="'.$f->data_emissao.'" data-data_vencimento="'.$f->data_vencimento.'" data-tipo_pagamento="boleto" class="gerar_pagamento" onclick="return false;" ><i class="fa fa-barcode"></i> Boleto</a>';
                            $btn_paypal = '<a href="" data-id_fatura="'.$f->Id.'" data-dataatualizado_fatura="'.$f->dataatualizado_fatura.'" data-data_emissao="'.$f->data_emissao.'" data-data_vencimento="'.$f->data_vencimento.'" data-tipo_pagamento="paypal" class="gerar_pagamento" onclick="return false;" ><i class="fa fa-paypal"></i> Paypal</a>';
                            break;
                        case '3':
                            $tipo_status = 'f_cancelado';
                            $acao_pag = '<a><i class="fa fa-exchange"></i> A Pagar</a>';
                            break;
                        case '4':
                            $tipo_status = 'f_a_cancelar';
                            $acao_pag = '<a href="" onclick="return false;" class="acao_a_pagar" data-id_fatura="'.$f->Id.'" data-status="0"><i class="fa fa-exchange"></i> A Pagar</a>
                                         <a href="" class="cancelarFatura" data-id="'.$f->Id.'"><i class="fa fa-ban"></i> '.lang('cancelar').' </a>';
                            break;
                        default:
                            $tipo_status = 'f_pago';
                            $acao_pag = '<a><i class="fa fa-exchange"></i> A Pagar</a>';
                            break;
                    }

                    $atividade = '';
                    switch ($f->atividade) {
                        case '1':
                            $atividade = 'Atividade de Monitoramento';
                            break;
                        case '2':
                            $atividade = 'Serviços Técnicos';
                            break;
                        case '3':
                            $atividade = 'Aluguel de outras máquinas e equipamentos';
                            break;
                        case '4':
                            $atividade = 'Suporte técnico, manutenção e outros serviços em tecnologia da informação';
                            break;
                        case '5':
                            $atividade = 'Desenvolvimento e licenciamento de programas de computador customizáveis';
                            break;
                        case '6':
                            $atividade = 'Serviços combinados de escritório e apoio administrativo';
                            break;
                        default:
                            $atividade = 'Outros';
                            break;
                    }

                    $dados['data'][] = array(
                        'id' => $f->Id,
                        'data_vencimento' => data_for_humans($f->data_vencimento),
                        'valor_total' => 'R$ '.number_format($valor_total, 2, ',', '.'),
                        'sub_total' => 'R$ '.number_format($sub_total, 2, ',', '.'),
                        'nota_fiscal' => $f->nota_fiscal,
                        'mes_referencia' => $f->mes_referencia,
                        'inicio_periodo' => data_for_humans($f->periodo_inicial),
                        'fim_periodo' => data_for_humans($f->periodo_final),
                        'data_pagto' => $f->data_pagto ? data_for_humans($f->data_pagto) : null,
                        'valor_pago' => $f->valor_pago ? number_format($f->valor_pago, 2, ',', '.') : null,
                        'secretaria' => $secretaria,
                        'ticket' => $f->id_ticket,
                        'atividade' => $atividade,
                        'status' => '<span class="status_fatura_'.$f->Id.'"><span class="hidden">'.$tipo_status.'</span>'.status_fatura($f->status, $f->data_vencimento).label_nova_data($f->status_fatura, $f->dataatualizado_fatura).'</span>',
                        'admin' => '<div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                                <ul class="dropdown-menu" style="margin-left: -107px;min-width: 0px !important;width: 150px;">
                                <li>
                                    <a href="#" class="abrirFatura" data-id="'.$f->Id.'"><i class="fa fa-folder-open"></i>
                                        '.lang('abrir').'
                                    </a>
                                <li>
                                    <a href="#envia_anexo" onclick="countAnexo('. $f->Id .')" id="getAnexo" data-toggle="modal" data-target="#envia_anexo"><i class="fa fa-file"></i>
                                        '.lang('anexar').'
                                    </a>
                                </li>
                                <li>
                                    <a class="visualizarAnexosNF" href="#" data-id="'.$f->Id.'"><i class="fa fa-file-pdf-o"></i>
                                        '.lang('notas_fiscais').'
                                    </a>
                                </li>                                
                                <li>
                                    <a href="#" class="getComentariosFatura" data-id="'.$f->Id.'"><i class="fa fa-comments"></i>
                                        '.lang('comentarios').'
                                    </a>
                                </li>
                                <li>
                                    <a href="#"class="enviaFat" data-numero="'.$f->Id.'" ><i class="fa fa-envelope icon-black"></i>
                                        '.lang('enviar').'
                                    </a>
                                </li>
                                <li>
                                    <a href="" data-id_fatura="'.$f->Id.'" data-dataatualizado_fatura="'.$f->dataatualizado_fatura.'" data-data_emissao="'.$f->data_emissao.'" data-data_vencimento="'.$f->data_vencimento.'" class="gerar_desc" onclick="return false;" ><i class="fa fa-file-text-o icon-black"></i> Descritivo</a>

                                </li>
                                <li>
                                    '.$acao_pag.'
                                </li>
                                <li>
                                    '.$btn_boleto.'
                                </li>
                                <li>
                                    '.$btn_paypal.'
                                </li>
                            </ul>
                        </div>'
                    );
                }
                echo json_encode(array('draw' => intval($retorno['draw']), 'recordsTotal' =>  intval($retorno['recordsTotal']), 'recordsFiltered' =>  intval($retorno['recordsFiltered']), 'data'=> $dados['data']) );

            }else{
                $recordsTotal = 0; # Total de registros
    			$recordsFiltered = 0; # Total de registros Filtrados
    	        $draw = intval($draw); # Draw do datatable
                echo json_encode(array('status' => false, 'data' => array(), 'draw' => $draw++, 'recordsTotal' => intval($recordsTotal), 'recordsFiltered' => intval($recordsFiltered)));
            }
        }
    }


    //RETORNA OS TOTAIS DE FATURAMENTOS (PAGOS, ATRASADOS, ABERTAS)
    public function ajaxTotaisFaturamento() {
        $id_cliente = $this->input->post('id_cliente');
        $hoje = date('Y-m-d');
        $somatorioPagos = 0;
        $somatorioFaltaPagar = 0;
        $somatorioAtrasadas = 0;

        if ($id_cliente) {

            $faturas = $this->fatura->listFaturas(
                'Id, valor_total, valor_pago, status, data_vencimento, iss, pis, csll, irpj, cofins',
                array('id_cliente' => $id_cliente)
            );

            if ($faturas) {
                foreach ($faturas as $key => $f) {
                    $valor_total = $f->valor_total - ($f->valor_total * $f->irpj/100) - ($f->valor_total * $f->pis/100) - ($f->valor_total * $f->cofins/100) - ($f->valor_total * $f->iss/100) - ($f->valor_total * $f->csll/100);

                    if (!$f->valor_pago) $f->valor_pago = 0.0;
                    if ($f->status == '1') $somatorioPagos += $f->valor_pago;

                    if (in_array($f->status, array('0', '2'))) {
                        if ($hoje > $f->data_vencimento) {
                            $somatorioAtrasadas += $valor_total;
                        }
                        $somatorioFaltaPagar += $valor_total - $f->valor_pago;
                    }
                }
            }
        }
        echo json_encode(array( 'somatorioPagos' => 'R$ '.number_format($somatorioPagos, 2, ',', '.'), 'somatorioFaltaPagar' => 'R$ '.number_format($somatorioFaltaPagar, 2, ',', '.'), 'somatorioAtradas' => 'R$ '.number_format($somatorioAtrasadas, 2, ',', '.') ));
    }


    public function salvar_config_boleto() {
        if ($this->input->post('emissor_boleto')) {
            $this->db->update('showtecsystem.fatura_config', array('emissor_boleto' => $this->input->post('emissor_boleto')));
            if ($this->db->affected_rows())
                echo 1;
            else
                echo 0;
        }
    }

    public function anexar() {
        $nome_arquivo = "";
        $fatura = $this->input->post('id_fatura');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false;
        $arquivo_enviado = true;
        if ($arquivo) {
            if (isset($_FILES['arquivo'])) {
                $connection = ftp_connect('ftp-arquivos.showtecnologia.com');
                if (!$connection) {
                    echo json_encode(array('mensagem' => 'ERROR: Falha de conexão!'));
                    exit;
                }
                $log = ftp_login($connection, 'show', 'show2592');
                if (!$log){
                    echo json_encode(array('mensagem' => 'ERROR: Falha de autenticação!'));
                    exit;
                } 
                ftp_pasv($connection, true);
                    $send = ftp_put($connection, 'particao_ftp/uploads/anexo_fatura/'.$_FILES['arquivo']['name'], $_FILES['arquivo']['tmp_name'], FTP_ASCII);
                ftp_close($connection);
            }
            if($send) {
                $retorno = $this->fatura->salvar_anexo($fatura, $_FILES['arquivo']['name']);
                die(json_encode(array(
                    'success' => true,
                    'mensagem' => '<div class="alert alert-success">Processo realizado com sucesso!</div>',
                    'registro' => $retorno
                )));
            } else {
                die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
            }
        } else {
            die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
        }
    }

    public function upload() {
        $config['upload_path'] = './uploads/anexo_fatura';
        $config['allowed_types'] = 'pdf';
        $config['max_size']	= '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $config['encrypt_name']  = 'true';
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('arquivo')) {
            $data = $this->upload->data();
            return $data;
        } else {
            $error = array('error' => $this->upload->display_errors());
            return false;
        }
    }

    public function count_anexo() {
        $count = $this->fatura->count($this->input->post('id'));
        echo json_encode($count);
    }

    public function list_anexos() {
        $fatura = $this->input->post('id');
        $arquivos = $this->fatura->get_arquivos($fatura);
        echo json_encode($arquivos);
    }

    public function comentario() {
        $data['comentario'] = $this->input->post('comentario');
        $data['id_fatura'] = $this->input->post('id_fatura');
        $data['user'] = $this->auth->get_login('admin', 'email');
        $this->fatura->saveComents($data);
        echo json_encode($data);

    }

    public function cad_rps()
    {
        $dados['titulo'] = 'Lista de RPS Geradas';
        $dados['rps'] = $this->fatura->load_rps();

        $this->load->view('fix/header', $dados);
        $this->load->view('faturas/lista_rps');
        $this->load->view('fix/footer');
    }

    public function getComments() {
        $id_fatura = $this->input->post('id_fatura');
        $comments = $this->fatura->get_comments($id_fatura);
        $array = array();
        if ($comments) {
            foreach ($comments as $dados) {
                $array[] = array(
                    'user' => $dados->user,
                    'data' => dh_for_humans($dados->data),
                    'comentario' => $dados->comentario
                );
            }
        }

        echo json_encode($array);
    }

    public function getComentarios($c_order = false, $order = false, $pagina = false) {
        $this->auth->is_allowed('faturas_visualiza');
        $where = array('cad_faturas.status !=' => '4');
        $rows = 10;
        if (isset($_GET['cancelar'])) {
            $where = array('cad_faturas.status' => '4'); # 4 = FATURAS A CANCELAR
            $rows = 9999999;
        }
        grava_url(current_url());
        $paginacao = $pagina != false  ? $pagina : 0;
        $c_order = $c_order == false ? 'Id' : $c_order;
        $order = $order == false ? 'desc' : $order;
        $dados['order'] = $order;
        $dados['c_order'] = $c_order;
        $dados['toogle_order'] = $order == 'desc' ? 'asc' : 'desc';
        if($this->session->userdata('filtro_fatura')) {
            $this->session->unset_userdata('filtro_fatura');
        }
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');
        $config['base_url'] = base_url().'faturas/index/'.$c_order.'/'.$order.'/';
        $config['uri_segment'] = 5;
        $config['total_rows'] = $this->fatura->total();
        $this->pagination->initialize($config);
        $dados['faturas'] = $this->fatura->listar($where, $paginacao, $rows, 'cad_faturas.'.$c_order, $order);
        echo json_encode($dados['faturas']);
    }

    public function getcomentarios_filtro($c_order = false, $order = false, $pagina = false) {
        $this->auth->is_allowed('faturas_visualiza');
        $where = array('cad_faturas.status !=' => '4');
        $rows = 10;
        if (isset($_GET['cancelar'])) {
            $where = array('cad_faturas.status' => '4'); # 4 = FATURAS A CANCELAR
            $rows = 9999999;
        }

        grava_url(current_url());
        $paginacao = $pagina != false  ? $pagina : 0;
        $c_order = $c_order == false ? 'Id' : $c_order;
        $order = $order == false ? 'desc' : $order;
        $dados['order'] = $order;
        $dados['c_order'] = $c_order;
        $dados['toogle_order'] = $order == 'desc' ? 'asc' : 'desc';
        if($this->input->post('filtro')){
            $filtro = $this->input->post('filtro');
            $numero = is_numeric($filtro) == true ? 'OR cad_faturas.numero = '.$filtro : '';
            $this->session->set_userdata('filtro_fatura', "cad_faturas.Id = '{$filtro}'
			OR cad_clientes.nome LIKE '%{$filtro}%' ".$numero);
            $this->session->set_userdata('filtro_keyword', $this->input->post('filtro'));
        }
        $dados['filtro'] = $this->session->userdata('filtro_keyword');
        $where = $this->session->userdata('filtro_fatura');
        if(!$where)
            redirect('faturas');
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');
        $config['base_url'] = base_url().'faturas/filtro/'.$c_order.'/'.$order.'/';
        $config['uri_segment'] = 5;
        $config['total_rows'] = $this->fatura->total($where);
        $this->pagination->initialize($config);
        $dados['faturas'] = $this->fatura->listar($where, $paginacao, $rows, 'cad_faturas.'.$c_order, $order);
        echo json_encode($dados['faturas']);
    }

    public function getComentariosCliente($c_order = false, $order = false, $pagina = false) {
        $this->auth->is_allowed('faturas_visualiza');
        grava_url(current_url());
        $paginacao = $pagina != false  ? $pagina : 0;
        $c_order = $c_order == false ? 'Id' : $c_order;
        $order = $order == false ? 'desc' : $order;
        $dados['order'] = $order;
        $dados['c_order'] = $c_order;
        $dados['toogle_order'] = $order == 'desc' ? 'asc' : 'desc';
        if($this->input->post('filtro')){
            $filtro = $this->input->post('filtro');
            $numero = is_numeric($filtro) == true ? 'OR cad_faturas.numero = '.$filtro : '';
            $this->session->set_userdata('filtro_fatura', "cad_faturas.Id = '{$filtro}'
			OR cad_clientes.nome LIKE '%{$filtro}%' ".$numero);
            $this->session->set_userdata('filtro_keyword', $this->input->post('filtro'));
        }
        $dados['filtro'] = $this->session->userdata('filtro_keyword');
        $where = $this->session->userdata('filtro_fatura');
        if(!$where)
            redirect('faturas');
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');
        $config['base_url'] = base_url().'faturas/filtro/'.$c_order.'/'.$order.'/';
        $config['uri_segment'] = 5;
        $config['total_rows'] = $this->fatura->total($where);
        $this->pagination->initialize($config);
        $dados['faturas'] = $this->fatura->listar($where, $paginacao, 999999, 'cad_faturas.'.$c_order, $order);
        echo json_encode($dados['faturas']);
    }
    public function setCheckBox(){
        $id = $this->input->post('id');
        $array = $this->session->userdata('fatura_id_check');
        if(isset($array[$id])&&$array[$id]){
            $array[$id]=false;
        }
        else{
            $array[$id]=true;
        }
        $this->session->set_userdata('fatura_id_check', $array);
        echo json_encode($this->session->userdata('fatura_id_check'));
    }
    public function getCheckBox(){
        echo json_encode($this->session->userdata('fatura_id_check'));
    }
    public function getEua($c_order = false, $order = false, $pagina = false){
        $this->auth->is_allowed('faturas_visualiza');
        grava_url(current_url());
        $paginacao = $pagina != false  ? $pagina : 0;
        $c_order = $c_order == false ? 'Id' : $c_order;
        $order = $order == false ? 'desc' : $order;
        $dados['order'] = $order;
        $dados['c_order'] = $c_order;
        $dados['toogle_order'] = $order == 'desc' ? 'asc' : 'desc';
        if($this->session->userdata('filtro_fatura')) {
            $this->session->unset_userdata('filtro_fatura');
        }
        $dados['msg'] = $this->session->flashdata('msg');
        $dados['erros'] = $this->session->flashdata('erro');
        $config['base_url'] = base_url().'faturas/index/'.$c_order.'/'.$order.'/';
        $config['uri_segment'] = 5;
        $config['total_rows'] = $this->fatura->total();
        $this->pagination->initialize($config);
        $dados['faturas'] = $this->fatura->listar(array(), $paginacao, 10, 'cad_faturas.'.$c_order, $order);
        $dados['titulo'] = 'Faturas';
        $this->load->view('faturas/lista_fatura');
    }


    public function inadimplencia()
    {
        $this->auth->is_allowed('inadimplencias_faturas');
        $dados = array(
            "valor" => $this->fatura->recuperaQtdModulos(),
            "titulo" => "Monitoramento de Faturas - Gráfico de Inadimplências"
        );
        $this->mapa_calor->registrar_acessos_url(site_url('/faturas/inadimplencia'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('faturas/inadimplencia/index');

        $this->load->view('fix/footer_NS');
    }

    public function geraRelatorio(){


        $inicio = '\'' . $this->converteData("d/m/Y", "Y-m-d", $_POST['inicio']) . ' 00:00:00\'';
        $fim = '\'' . $this->converteData("d/m/Y", "Y-m-d", $_POST['fim']) . ' 23:59:59\'';

        $dados = array(
            "resultado" => $this->fatura->recuperaDadosRelatorio($inicio, $fim)->result(),
            "inicio" => $_POST['inicio'] . ' às 00:00:00',
            "fim" => $_POST['fim'] . ' às 23:59:59',
            "titulo" => "Relatorio: Monitoramento de Inadimplências"
        );

        $this->load->view('fix/header_NS', $dados);
        $this->load->view('faturas/inadimplencia/relatorio');

        $this->load->view('fix/footer_NS');

     }

     public function converteData($formatoDe, $formatoPara, $valor) {
        try {
            if ($valor > 0) {
                if ($formatoDe == 'd/m/Y' OR $formatoDe == 'm/Y') {
                    $valor = implode("-", array_reverse(explode("/", $valor)));
                } else if ($formatoDe == 'Ymd') {
                    $valor = substr($valor, 4, 4) . "-" . substr($valor, 2, 2) . "-" . substr($valor, 0, 2);
                }
                $dateTime = new DateTime($valor);
                return $dateTime->format($formatoPara);
            }
        } catch (Exception $e) {
            return false;
        }
    }
    public function exibirDadosMinuto(){
        $dados = array(
            "valor" => $this->fatura->recuperaQtdModulos(),
        );

        $this->load->view('index', $dados);
     }

// +++++++++++++++++++++++ jerônimo gabriel init ++++++++++++++++++++++++++++
    // métodos desenvolvidos

    function arrears() {
        echo $this->fatura->arrears();
    }

    function invoicesPaidOnTheDay() {
        echo $this->fatura->invoicesPaidOnTheDay();
    }

    function invoicesPaidInTheMonth() {
        echo $this->fatura->invoicesPaidInTheMonth();
    }

    function openDayInvoices() {
        echo $this->fatura->openDayInvoices();
    }

    function openWeekInvoices() {
        echo $this->fatura->openWeekInvoices();
    }

    function openMonthInvoices() {
        echo $this->fatura->openMonthInvoices();
    }

    public function envia_notificacao_status_fatura(){

       // Ignore user aborts and allow the script
       // to run forever
       ignore_user_abort(true);
       set_time_limit(0);

       $clientes = $this->cliente->listar(array('status' => 1, 'id' => 142476));
       if (count($clientes) > 0) {

           foreach ($clientes as $cliente) {
               if ($cliente->orgao == 'privado') {

                   $faturas = $this->fatura->listar_por_cliente(
                       'Id, data_vencimento',
                       'cad_faturas.id_cliente =' .$cliente->id. ' and cad_faturas.status in (0,2)'
                   );

                    if ($faturas) {
                        $hoje = date('Y-m-d');
                        $email_finan = $this->cliente->get_clientes_emails($cliente->id, 'email', '0')[0]->email;
                        $email_finan = strtolower($email_finan);

                        foreach ($faturas as $fatura) {

                            $dif_datas = dif_datas($hoje, $fatura->data_vencimento);
                            $nome = explode(" ", $cliente->nome);
                            $dia = substr($fatura->data_vencimento, 8, 2);
                            $mes = data_mes_texto_completo( substr($fatura->data_vencimento, 5, 2) );

                            if ($dif_datas == '+3') {
                                $texto = 'Faltam apenas 3 dias para o vencimento da sua fatura. Salientamos a importância de realizar este pagamento o mais breve possível a fim de evitar o acúmulo de juros.';
                                $mensagem = $this->layout_email->notifica_status_pagamento($nome[0], $texto, $fatura->Id, 0);
                                $sender = $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato.ps08@hotmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
                                $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato@showtecnologia.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
                                $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'contato.renato.ps08@gmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);

                            }elseif ($dif_datas == '+0') {
                                $texto = 'Lembramos que a sua fatura vence hoje. Salientamos a importância de realizar este pagamento o mais breve possível a fim de evitar o acúmulo de juros.';
                                $mensagem = $this->layout_email->notifica_status_pagamento($nome[0], $texto, $fatura->Id, 1);
                                $sender = $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato.ps08@hotmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
                                $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato@showtecnologia.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
                                $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'contato.renato.ps08@gmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);

                            }elseif ($dif_datas == '-3') {
                                $texto ='Não identificamos em nossos registros o pagamento da fatura  referente ao mês de '.$mes.', vencido no dia '.$dia.'. Salientamos a importância deste
                                pagamento o mais breve possível a fim de evitar o acúmulo de juros. Ressaltamos que, a visualização poderá ser suspensa até a liquidação do débito.';
                                $mensagem = $this->layout_email->notifica_status_pagamento($nome[0], $texto, $fatura->Id, 2);
                                $sender = $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato.ps08@hotmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
                                $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'renato@showtecnologia.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
                                $this->sender->sendEmail( 'no-reply@notificacaogestor.com', 'contato.renato.ps08@gmail.com', 'Referente à fatura de '.$mes.' - Show Tecnologia', $mensagem);
                            }
                        }
                    }
                }
            }
        }
    }

    /*
     * RETORNA OS DADOS DAS FATURAS DE UM GRUPO DE IDS DE FATURAS
     */
     //$faturasIds eh um array de ids de faturas
    public function ajaxListaFaturasPorGrupoId() {
        $faturasIds = $this->input->post('faturas');
        //CARREGA AS FATURAS
        $listaFaturas = $this->fatura->listaFaturasPorGrupoId($faturasIds, 'Id, data_vencimento, data_emissao, dataatualizado_fatura, status');

        if ($listaFaturas) {
            echo json_encode(array('success' => true, 'faturas' => $listaFaturas));
        }else {
            echo json_encode(array('success' => false, 'msg' => 'Falha, tente novamente mais tarde!'));
        }
    }

   public function notif_email_status_pag($nome, $status, $boleto){
       $dados['nome'] = $nome;
       $dados['num_boleto'] = $boleto;

       if ($status == 0) {
           $dados['texto'] = 'Faltam apenas 3 dias para o vencimento da sua fatura. Salientamos a importância de realizar este pagamento o mais breve possível a fim de evitar o acúmulo de juros.';

       }elseif ($status == 1) {
           $dados['texto'] = 'Lembramos que a sua fatura vence hoje. Salientamos a importância de realizar este pagamento o mais breve possível a fim de evitar o acúmulo de juros.';

       }elseif ($status == 2) {
           $dados['texto'] ='Não identificamos em nossos registros o pagamento da fatura  referente ao mês de Maio, vencido no dia 11. Salientamos a importância deste pagamento o mais breve possível a fim de evitar o acúmulo de juros.
                            Ressaltamos que, a visualização poderá ser suspensa até a liquidação do débito.';
       }

       $this->load->view('layout_email/notif_status_pag', $dados);
   }

   /*
  * CORTAR IMAGEM - BASE64
  */
  function cortarImagem() {
       $dados = $this->input->post();
       //CARREGA AS DIMENSOES
       $nameFile = $dados['name'];
       $dst_x = $dados['dst_x'];       // $dst_x: coordenada X da imagem de destino(ponto superior esquerdo).
       $dst_y = $dados['dst_y'];       // $dst_y: coordenada Y da imagem de destino(ponto superior esquerdo).
       $src_x = $dados['src_x'];       // $src_x: coordenada X da imagem de origem(ponto superior esquerdo).
       $src_y = $dados['src_y'];       // $src_y: coordenada Y da imagem de destino(ponto superior esquerdo).
       $dst_w = $dados['dst_w'];       // $dst_w: largura do destino.
       $dst_h = $dados['dst_h'];       // $dst_h: altura do destino.
       $src_w = $dados['src_w'];       // $src_w: largura da origem.
       $src_h = $dados['src_h'];       // $src_h: altura da origem.

       header('Content-type: image/png');

       //CONMVERTE A IMAGEM BASE64 PARA BIN
       $img = str_replace('data:image/png;base64,', '', $dados['src_image']);
       $img = str_replace(' ', '+', $img);
       $imageData = base64_decode($img);

       //SALVANDO A IMAGEM
       $img_file = 'uploads/faturas/'.rand().'.png';
       file_put_contents($img_file, $imageData);
       $src_image = imagecreatefrompng($img_file);
       imagepng($src_image, $img_file, 3);

       //CRIA UMA IMAGEM LIMPA E COPIA OS PIXELS ESPECIFICADOS DA IMAGEM DE ORIGEM PARA A NOVA
       $dst_image = imagecreatetruecolor($dst_w, $dst_h);
       imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
       //CRIA A NOVA IMAGEM PNG
       imagepng($dst_image, $img_file, 3);
       imagedestroy($src_image);
       imagedestroy($dst_image);

       //ENCODADA A IAMGEM PARA BASE64
       $imgEncodada = imageToBase64($img_file);
       //REMOVE OS ARQUIVOS .PNG GERADOS PARA AUXILIO
       unlink($img_file);

       echo $imgEncodada;
  }

    public function get_comments_fatura() {
        $id = $this->input->get('id');
        $retorno = array();
        $comentarios = $this->fatura->get_comments( $id );
        if (!empty($comentarios)) {
            foreach ($comentarios as $comentario) {
                $retorno[] = array(
                    'id' => $comentario->id,
                    'comentario' => $comentario->comentario,
                    'user' => $comentario->user,
                    'data' => dh_for_humans($comentario->data)
                );
            }
        }

        exit( json_encode(array('table' => $retorno)) );
    }

    /**
     * ADICIONA UM NOVO COMENTARIO A UMA FATURA
    */
    public function add_comentario_fatura() {
        $dados = $this->input->post();
        $dados['user'] = $this->auth->get_login('admin', 'email');

        if ( $id_insert = $this->fatura->addComentFatura($dados) ) {
            $dados['data'] = date('d/m/Y H:i:s');            
            $dados['id'] = $id_insert;
            exit( json_encode(array('success' => true, 'retorno' => $dados)));
        
        } else { 
            exit( json_encode(array('success' => false, 'msg' => lang('falha_comentar_fatura'))));
        }
    }

    /**
     * CANCELAR UMA FATURA
    */
    public function cancelar_fatura() {
        if($dados = $this->input->post()) {
            
            $senha = md5($dados['senha_exclusao']);
            if ($senha == $this->fatura->senhaExclusaoFatura()) {
                
                $d_atualiza = array('datacancel_fatura' => date('Y-m-d'), 'status' => 3, 'instrucoes1' => $dados['motivo']);

                if($this->fatura->atualizar_fatura($dados['id_fatura'], $d_atualiza))
                    exit( json_encode(array('success' => true, 'id_fatura' => $dados['id_fatura'], 'msg' => lang('fatura_cancelada_sucesso'))));                
                else 
                    exit( json_encode(array('success' => false, 'msg' => lang('falha_cancelar_fatura'))));                

            } else {
                exit( json_encode(array('success' => false, 'msg' => lang('senha_invalida_tente_novamente'))));
            }
       
        } else {
            exit( json_encode(array('success' => false, 'msg' => lang('erro_params'))));
        }
    }

    /**
     * CARREGA UMA FATURA
    */
    public function get_fatura($id=false) {

        $fatura = $this->fatura->get( array('Id' => $id), true, '*' );
        if(!empty($fatura)) {

            $itens = $this->fatura->get_items( array('id_fatura' => $id), '*' );
            $fatura->status = '<span>'.status_fatura($fatura->status, $fatura->data_vencimento).label_nova_data($fatura->status, $fatura->dataatualizado_fatura).'</span>';

            exit( json_encode(array('success' => true, 'fatura' => $fatura, 'itens' => $itens)));                
        }
        else  {
            exit( json_encode(array('success' => false, 'msg' => lang('fatura_nao_encontrada'))));                
        }     
    }

    /**
     * EDITAR UMA FATURA
    */
    public function editar_fatura() {
        if($fatAtualizada = $this->input->post()) {

            $id_fatura = $fatAtualizada['Id'];
            $motivo_edicao = $fatAtualizada['motivo_edicao'];
            $itensAtualizados = $fatAtualizada['itensFatura'];
            unset($fatAtualizada['Id']);
            unset($fatAtualizada['motivo_edicao']);
            unset($fatAtualizada['itensFatura']);
            
            $fatura = $this->fatura->get( array('Id' => $id_fatura), true, '*' );
            if(!empty($fatura)) {    
                
                $temJuros = false;
                $itensUpdate = $itensInsert = $itensNovos = [];

                if (!empty($itensAtualizados)) {
                    foreach ($itensAtualizados as $itemAtt) {
                        $itemAtt += array(
                                'id_cliente' => $fatura->id_cliente,
                                'taxa_item' => $itemAtt['tipo_item'] === 'taxa' ? 'sim' : 'nao',
                                'vencimento_item' => $fatura->data_vencimento
                            );

                        $itemAtt['valor_item'] = str_replace(',', '.', str_replace('.', '', $itemAtt['valor_item']));
                        $itemAtt['tipotaxa_item'] = $itemAtt['tipotaxa_item'] ? $itemAtt['tipotaxa_item'] : 'boleto';
                        $itemAtt['relid_item'] = $itemAtt['relid_item'] ? $itemAtt['relid_item'] : 0;

                        if ( isset($itemAtt['id_item']) ) {
                            $itensUpdate[] = $itemAtt;
                        } else {                            
                            $itensInsert[] = $itemAtt;
                        }

                        if ($itemAtt['tipotaxa_item'] == 'juros') $temJuros = true;
                    }                    
                }                

                //MOTIVO DA EDICAO
                $dadosComentario = array( 
                    'user' => $this->auth->get_login('admin', 'email'),
                    'id_fatura' => $id_fatura,
                    'comentario' => $motivo_edicao,
                    'status' => '1'
                );

                $faturaCompararOriginal = array(
                    'dataatualizado_fatura' => $fatura->dataatualizado_fatura === '' ? NULL : $fatura->dataatualizado_fatura,
                    'iss' => $fatura->iss,
                    'pis' => $fatura->pis,
                    'cofins' => $fatura->cofins,
                    'irpj' => $fatura->irpj,
                    'csll' => $fatura->csll,
                    'valor_total' => number_format($fatura->valor_total, 2, '.', '')
                );                                

                $faturaCompararNovo = array(
                    'dataatualizado_fatura' => $fatAtualizada['dataatualizado_fatura'] === '' ? NULL : $fatAtualizada['dataatualizado_fatura'],
                    'iss' => $fatAtualizada['iss'],
                    'pis' => $fatAtualizada['pis'],
                    'cofins' => $fatAtualizada['cofins'],
                    'irpj' => $fatAtualizada['irpj'],
                    'csll' => $fatAtualizada['csll'],
                    'valor_total' => number_format($fatAtualizada['valor_total'], 2, '.', '')
                );

                if ($faturaCompararNovo === $faturaCompararOriginal && empty($itensInsert)) {
                    unset($fatAtualizada['dataatualizado_fatura']);
                    $fatAtualizada['valor_boleto'] = $fatAtualizada['valor_total'];
                    $fatAtualizada['total_fatura'] = $fatAtualizada['valor_total'];

                    $this->fatura->atualizar_fatura($id_fatura, $fatAtualizada);

                    //ADICIONA O MOTIVO PARA A EDICAO                    
                    $this->fatura->addComentFatura($dadosComentario);

                    $this->fatura->updateItensBatch($itensUpdate);
                    exit( json_encode(array('success' => true, 'msg' => lang('fatura_atualiza_sucesso'))));

                } else {
                    $d_atualiza = array('datacancel_fatura' => date('Y-m-d'), 'status' => 3);                    
                    if($this->fatura->atualizar_fatura($id_fatura, $d_atualiza)) {

                        //ADICIONA O MOTIVO PARA A EDICAO
                        $dadosComentario['comentario'] = lang('cancelamento_para_atualizacao');
                        $this->fatura->addComentFatura($dadosComentario);

                        $fatAtualizada['valor_boleto'] = $fatAtualizada['valor_total'];
                        $fatAtualizada['total_fatura'] = $fatAtualizada['valor_total'];
                        $fatAtualizada = array_merge((array)$fatura, $fatAtualizada);
                        if ($fatAtualizada['dataatualizado_fatura'] === '' || !$temJuros) unset($fatAtualizada['dataatualizado_fatura']);
                        unset($fatAtualizada['Id']);
                        unset($fatAtualizada['numero']);

                        $id_nova_fatura = $this->fatura->inserir_fatura($fatAtualizada);
                        $this->fatura->atualizar_fatura($id_nova_fatura, array('numero' => $id_nova_fatura));

                        //ADICIONA O MOTIVO PARA A EDICAO
                        $dadosComentario['id_fatura'] = $id_nova_fatura;
                        $dadosComentario['comentario'] = $motivo_edicao;
                        $this->fatura->addComentFatura($dadosComentario);
                        
                        $itensNovos = array_merge($itensUpdate,  $itensInsert);
                        if (!empty($itensNovos)) {
                            foreach ($itensNovos as $key => $item) {
                                $itensNovos[$key]['id_fatura'] = $id_nova_fatura;

                                if (isset($item['id_item']))
                                    unset($itensNovos[$key]['id_item']);
                            }
                            $this->fatura->insertItensBatch($itensNovos);
                        }

                        exit( json_encode(array('success' => true, 'msg' => lang('fatura_atualiza_sucesso'))));
                    }
                }
            }
            else  {
                exit( json_encode(array('success' => false, 'msg' => lang('fatura_nao_encontrada'))));                
            }  
       
        } else {
            exit( json_encode(array('success' => false, 'msg' => lang('erro_params'))));
        }
    }

    //função para baixar arquivos retorno e processar automaticamente do nexxera
    public function baixar_arquivos_retorno(){
		// Carregar as funções e conexão
        $this->load->helper('util_helper');

        //carregar a função para processar arquivos
        $this->load->library('upload');
		$this->load->model('retorno');
    
        //data de hoje
        $final_date = date('d-m-Y');

        $initial_date = date("d-m-Y", strtotime($final_date) - (2 * 24 * 60 * 60));
                      		
        //buscar as URLs dos arquivos e pelos nomes dos arquivos conforme data passada
        $links_url = from_nexxera_urls("$final_date", "$initial_date");

        //configurar o ssl para download dos arquivos
        $config = array(
            "ssl"=> array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        
        //configuração de contexto para download de arquivos
        $context = stream_context_create($config);

        //decodificar o json para percorrer o objeto
        $arquivos = json_decode($links_url);
        //percorrer os index do array de retorno da api
        foreach ($arquivos as $chave => $valor) {
           //percorrer os arrays que estão dentro do array de retorno 
            foreach ($valor as $key => $value) {
                $file_name = "{$key}";
                $url_download = "{$value->url}";

                $dir = join(DIRECTORY_SEPARATOR, array('uploads', 'retorno'));
                if(file_put_contents("$dir/".$file_name ,file_get_contents($url_download, false, $context))) {
                   // echo "File ".$file_name. " downloaded successfully \n";                                        
                    $this->retorno->processar($dir, $file_name);    
                    $this->session->set_flashdata('file_retorno', $file_name);                                                              
                }                                               
            } 
        }
        
        $this->load->model('conta');
        $dados['fats_retorno'] = array();
        $arquivo = $this->session->flashdata('file_retorno');
        if ($arquivo) {
            //baixando faturas
            $fats_retorno = $this->retorno->listar(array('arquivo_retorno' => $arquivo,'operacao'=>'C'));
            if (count($fats_retorno) > 0) {
                $dados['content_retorno'] = $this->fatura->baixa_novo_retorno($fats_retorno);

            } else {
                echo 'Nenhuma fatura processada no retorno';
            }
            //baixando contas
            $contas_retorno = $this->retorno->listar(array('arquivo_retorno' => $arquivo,'operacao'=>'D'));
            if (count($contas_retorno) > 0) {
                $dados['content_retorno_pagamento'] = $this->conta->baixa_novo_retorno($contas_retorno);
            } else {
                echo 'Nenhuma conta processada no retorno';
            }

            $this->load->view('faturas/retorno_baixa', $dados);
        } else {
            echo 'Nenhuma fatura/conta encontrada nesse retorno';
        }
        

	}
    
    public function gerar_fatura_mesclada() {
        $this->auth->is_allowed('faturas_add');
        $dados = $this->input->post();
        $itens = $this->input->post('itensFatura');
        $dataAtual = date('Y-m-d', strtotime('today'));

        $fatura['fatura'] = array(
            'id_cliente'            => $dados['id_cliente'],
            'irpj'                  => $dados['irpj'],
            'csll'                  => $dados['csll'],
            'pis'                   => $dados['pis'],
            'cofins'                => $dados['cofins'],
            'iss'                   => $dados['iss'],
            'valor_total'           => $dados['valor_total'],
            'data_emissao'          => $dataAtual,
            'data_vencimento'       => $dados['data_vencimento'],
            'formapagamento_fatura' => $dados['forma_pagamento'],
            'nota_fiscal'           => $dados['nota_fiscal'],
            'mes_referencia'        => $dados['mes_referencia'],
            'periodo_inicial'       => $dados['periodo_inicial'],
            'periodo_final'         => $dados['periodo_final'],
            'atividade'             => $dados['faturaAtividade'],
            'status'                => 2,
            'data_inclusao'         => $dataAtual,
            'numero'                => 0,
            'id_ps'                 => 0,
            'vencimento_fn'         => "1970-01-01",
            'pagamento_fn'          => "1970-01-01",
            'valor_fn'              => 0,
            'valor_pago_fn'         => 0,
            'multa_fn'              => 0,
            'juros_fn'              => 0,
            'status_fn'             => '',
            'data_fn'               => "1970-01-01",
            'hora_fn'               => "00:00:00",
            'retorno_fn'            => '',
            'competencia_fn'        => 0,

        );

        foreach ($itens as $item){

            $fatura['itens'][] = array(
                'id_cliente'            => $dados['id_cliente'],
                'tipo_item'             => $item['tipo_item'],
                'descricao_item'        => $item['descricao_item'],
                'valor_item'            => str_replace(',', '.', str_replace('.', '', $item['valor_item'])),
                'taxa_item'             => $item['tipotaxa_item'] ? 'sim' : 'nao',
                'tipotaxa_item'         => $item['tipotaxa_item'] && $item['tipotaxa_item'] ? $item['tipotaxa_item'] : 'boleto',
                'vencimento_item'       => $dados['data_vencimento'],
                'relid_item'            => $item['relid_item'] && $item['relid_item'] ? $item['relid_item'] : 0,
                'obs_item'              => '',
            );
        }
        $retorno = $this->fatura->gravar_fatura($fatura);
        if ($retorno){
            echo json_encode(array('status' => true, 'msg' => 'Faturas mescladas com sucesso!'));
        }else{
            echo json_encode(array('status' => false, 'msg' => 'Erro ao mesclar faturas. Tente novamente!'));
        }
    }

    public function status_faturaID(){
        $id = $this->input->post('id');
        $retorno = $this->fatura->getStatusFatura($id);

        if ($retorno || $retorno == 0){
            echo json_encode(array('status' => true, 'msg' => 'Status da fatura', 'data' => $retorno));
        }else{
            echo json_encode(array('status' => false, 'msg' => 'Erro ao buscar status da fatura. Tente novamente!'));
        }
    }

    public function alterar_status_fatura (){
        $id = $this->input->post('id_fatura');
        $status = $this->input->post('status');

        $retorno = $this->fatura->to_alterarStatusFatura($id, $status);

        if ($retorno){
            echo json_encode(array('status' => true, 'msg' => 'Status da fatura alterado com sucesso'));
        }else{
            echo json_encode(array('status' => false, 'msg' => 'Erro ao alterar status da fatura. Tente novamente!'));
        }
    }

    public function gerarSenhaACancelar(){
        $id_usuario = $this->auth->get_login_dados('user');
        if (!isset($_SESSION['tokenACancelar']) && !isset($_SESSION['validadeTokenACancelar'])) {

            $random_bytes = openssl_random_pseudo_bytes(10);
            $hash = md5($random_bytes);

            $token = $hash;

            $_SESSION['tokenACancelar'] = $token;
            $_SESSION['validadeTokenACancelar'] = date("d/m/y H:i:s",strtotime(" + 720 minutes"));

            $resultadoEnvioEmail = $this->enviarEmailSenha($token);
            $resultadoEnvioEmail = json_decode($resultadoEnvioEmail, true);
            if ($resultadoEnvioEmail !== null && isset($resultadoEnvioEmail['code'])){
                if ($resultadoEnvioEmail['code'] == 200){
                    echo json_encode(array('status' => true, 'msg' => 'Senha gerada com sucesso e enviada para seu e-mail!'));
                    $this->log_shownet->gravar_log($id_usuario, '', 0, 'criar', 'Envio de senha via e-mail para alterar status da fatura(à cancelar)', 'Envio de senha via e-mail para alterar status da fatura(à cancelar)');
                }else{
                    echo json_encode(array('status' => false, 'msg' => 'Erro ao enviar senha para seu e-mail! Tente novamente.'));
                }
            }else{
                echo json_encode(array('status' => false, 'msg' => 'Erro ao enviar senha para seu e-mail! Tente novamente.'));
            }
        } else {
            if($_SESSION['validadeTokenACancelar'] > date('d/m/y H:i:s')){
                echo json_encode(array('status' => false, 'msg' => 'Existe uma senha ativa! Verifique seu e-mail.'));
            } else {
                unset($_SESSION['tokenACancelar']);
                unset($_SESSION['validadeTokenACancelar']);
                return $this->gerarSenhaACancelar();
            }
        }
    }

    public function validadeSenha(){
        $tempoAtual = date('d/m/y H:i:s');
        $tempoToken = $_SESSION['validadeTokenACancelar'];

        if ($tempoToken > $tempoAtual){
            return true;
        } else {
            return false;
        }
    }

    public function enviarEmailSenha($senha){
        $email = $this->auth->get_login_dados('email');
        $usuario = $this->auth->get_login_dados('nome');

        $mensagem = "<table>
				<tbody>
				<tr>
					<td style='background-color:#0079bf;padding:25px 0;text-align:center'>
					<div class='card-body' style='background-color: #0079bf; '>
						<div align='center'>
							<img src='".base_url('media/img/logo-topo-login.png')."' alt=''>
							<div align='right' style='color: #fff;'>
								<span>
									SHOWNET
								</span>
							</div>
						</div>
					</div>
					</td>
				</tr>
				<tr>
					<td style='padding:35px;width:570px;text-align:center;margin:0'>
					<h3>Olá, ". $usuario."</h3>
					<p>
						Sua senha para alterar o status da fatura foi gerada.
					</p>
					<p>
						Para alterar o status, insira a senha abaixo:
                        ".$senha."
					</p>
                    <p>
                        <b>OBS: <b>
                    </p>
					<p>Esta senha tem validade de 12 horas.</p>
					</td>
				</tr>
				<tr>
					<td style='background-color:#f5f8fa;padding:35px;width:570px;text-align:center;margin:0'>
					<p style='color:#aeaeae;font-size:12px;'>
						Grupo Show Tecnologia <br>
						Fone: 4020-2472 / E-mail: no-reply@notificacaogestor.com <br>
					</p>
					</td>
				</tr>
				</tbody>
				</table>";

				$sender = $this->sender->sendEmailAPI("Shownet - Senha para Alteração de Status (à cancelar)", $mensagem, $email);
                return $sender;
    }


    // ANEXAR NF
    public function uploadNF() {
        $idFatura = $this->input->post('id_fatura_nf');
        $descricao = $this->input->post('descricaoNF');
        $arquivo = isset($_FILES) ? $_FILES['anexo_nf']['tmp_name'] : false;
        $ext = 'pdf';

        if ($arquivo) {
            // Abre o arquivo no modo de leitura binária
            $handle = fopen($arquivo, "rb");

            if ($handle) {
                $base64_content = '';

                // Lê o conteúdo do arquivo em pedaços e o converte para Base64
                while (!feof($handle)) {
                    $base64_content .= base64_encode(fread($handle, 8192)); 
                }

                fclose($handle);

                $POSTFIELDS = array(
                    'idFatura' => $idFatura,
                    'descricao' => $descricao,
                    'extensaoArquivo' => $ext,
                    'arquivo' => $base64_content
                );

                $resultado = post_inserirAnexoFatura($POSTFIELDS);

                echo json_encode($resultado);
            } else {
                echo json_encode(
                    array(
                        'status' => '400',
                        'mensagem' => 'Erro ao abrir o arquivo.'
                    )
                );
            }
        } else {
            echo json_encode(
                array(
                    'status' => '400',
                    'mensagem' => 'Não foi possível identificar o arquivo a ser enviado.'
                )
            );
        }
        
    }

    public function editNF() {
        $idNF = $this->input->post('id_nf');
        $idFatura = $this->input->post('id_fatura_edit');
        $descricao = $this->input->post('descricaoNFEdit');
        $arquivo = isset($_FILES) ? $_FILES['anexoNFEdit']['tmp_name'] : false;
        $ext = 'pdf';

        if ($arquivo) {
            $handle = fopen($arquivo, "rb");

            if ($handle) {
                $base64_content = '';

                while (!feof($handle)) {
                    $base64_content .= base64_encode(fread($handle, 8192));
                }

                fclose($handle);

                $POSTFIELDS = array(
                    'id' => $idNF,
                    'idFatura' => $idFatura,
                    'descricao' => $descricao,
                    'extensaoArquivo' => $ext,
                    'arquivo' => $base64_content
                );
            } else {
                echo json_encode(
                    array(
                        'status' => '400',
                        'mensagem' => 'Erro ao abrir o arquivo.'
                    )
                );
            }
        } else {
            $POSTFIELDS = array(
                'id' => $idNF,
                'idFatura' => $idFatura,
                'descricao' => $descricao,
                'extensaoArquivo' => $ext
            );
        }

        $resultado = post_atualizarAnexoFatura($POSTFIELDS);
        echo json_encode($resultado);
        
    }

    public function listarAnexosNF($idFatura) {

        $result = get_listarAnexosNFFatura($idFatura);

        if ($result['status'] == 200) {
            echo json_encode(
                array(
                    'status' => $result['status'],
                    'anexos' => $result['results']['anexos']
                )
            );
        } else {
            echo json_encode(
                $result
            );
        }
    }

    public function excluirAnexosNF($idNF) {

        $resultado = delete_anexoNF($idNF);

        echo json_encode($resultado);
    }
}
