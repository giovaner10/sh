<?php
error_reporting(0);

if (!defined('BASEPATH')) exit('No direct script access allowed');

class PaineisOmnilink extends CI_Controller {

    protected $sac;
    private $timeZone;
    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('painel_omnilink','painelOmnilink');
        $this->load->model('auth');
        $this->load->model('cliente');
        $this->load->model('mapa_calor');
        $this->load->helper('sac_crm_helper');
        $this->sac = new SacCrmHelper();
        $this->timeZone = new DateTimeZone('America/Sao_Paulo');
        $this->load->model('log_shownet');
        $this->load->helper('funcoes_programaveis_helper');
        $this->load->model('usuario_gestor');
    }

    public function index($cpfCnpjRedirecionado = null) {
        
        $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');

        if(!$pageRefreshed){
            unset($_SESSION['clientes']);
        }

        $this->auth->is_allowed('out_omnilink');

		$this->mapa_calor->registrar_acessos_url(site_url('/PaineisOmnilink'));

        $para_view['cpfCnpjRedirecionado'] = '';

        if(!empty($cpfCnpjRedirecionado)) {
            $para_view['cpfCnpjRedirecionado'] = $cpfCnpjRedirecionado;
        }

        $para_view['titulo'] = 'Suporte Omnilink - Painel de Atendimento';
        $para_view['load'] = ["buttons_html5","datatable_responsive", "select2"];
        $para_view['permissoes'] = json_encode(array(
            'vis_visualizarsinistroagendamento' => $this->auth->is_allowed_block('vis_visualizarsinistroagendamento'),
            'out_alterarInfoItensContratoOmnilink' => $this->auth->is_allowed_block('out_alterarInfoItensContratoOmnilink'),
            'edi_alterarcadastrodecliente' => $this->auth->is_allowed_block('edi_alterarcadastrodecliente'),
            'out_lideranca' => $this->auth->is_allowed_block('out_lideranca'),
            'edi_alteracaoos' => $this->auth->is_allowed_block('edi_alteracaoos')
        ));

        if(isset($_SESSION['clientes'])){
            $para_view['clientes'] = $_SESSION['clientes'];
        }
        
		$this->load->view('new_views/fix/header', $para_view);
        $this->load->view('sacOmnilink/modaisPainelOmnilink');
        $this->load->view('sacOmnilink/painelomnilink');
        $this->load->view('fix/footer_NS');        
    }
    
    public function recuperarClientesCRM(){
        //pega o parametro passado
        $search = $this->input->get('q');

        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];

        $requisicaoClientes = $this->sac->get('accounts',array(
            '$select' => 'accountid,name',
            '$filter' => "contains(name, '{$search}')",
            '$orderby' => 'name asc'
        ));

        //verifica se o código de retorno está correto para rodar o for
        if($requisicaoClientes->code == 200){
            $values = $requisicaoClientes->data->value;
            foreach ($values as $value) {

                $resposta['results'][] = array(
                    'id' => $value->accountid,
                    'text' => $value->name
                );
            }
        }
        echo json_encode($resposta);
    }

    public function get_id($id) {

        $cliente_id = $this->input->get('id');

        $cliente = $this->cliente->get_cliente_by_id($cliente_id);

        echo json_encode($cliente);
    }

    /**
     * Busca o cliente pelo documento (cpf ou cnpj)
     */
    public function ajax_get_cliente(){
        try {
            $document = $this->input->get('document');

            //pega "somente" os campos necessários
            $select = 'zatix_codigocliente,zatix_loja,zatix_nomefantasia,tz_possui_particularidade ,websiteurl, customertypecode
                ,emailaddress1,emailaddress3,tz_emailaddress4,tz_emailaf,emailaddress2,tz_email_alerta_cerca, tz_dddtelefoneprincipal
                    ,tz_ddd_telefone1,tz_telefone1,tz_ddd_outro_telefone,tz_ddd_telefone_celular,tz_dddtelefonecelular,tz_dddfax
            ,fax,_tz_cep_principalid_value,address1_line1,address1_line2,address1_line3,_tz_cidade_principalid_value,address1_city
                ,_tz_estado_principalid_value,address1_stateorprovince,address2_addressid,zatix_atendimentoriveiculo,zatix_comunicacaochip
                    ,zatix_comunicacaosatelital,zatix_emissaopv,zatix_bloqueiototal,tz_desbloqueioportal,address2_line1,address2_line2,address2_line3,address2_city
            ,address2_stateorprovince,_tz_cep_entregaid_value,zatix_enderecoentregacep,zatix_enderecoentrega,zatix_enderecoentregacomplemento
                ,zatix_enderecoentregabairro,zatix_enderecoentregamunicipio,zatix_enderecoentregaestado,_tz_segmentacao_cliente_value
                    ,tz_segmentacao_manual,zatix_nomevendedor,_tz_canal_vendaid_value,tz_codigo_cliente_show,tz_codigo_cliente_graber
            ,tz_possui_providencia,address1_postalcode,_tz_vendedorid_value,tz_envio_sustentavel,zatix_gerenciadorarisconome,tz_gerenciadora_risco, statecode';
                
            $expand = 'tz_segmentacao_cliente($select=tz_name),tz_consultor_pos_vendas($select=systemuserid,fullname)
                ,tz_forma_cobrancaid($select=tz_forma_cobrancaid,tz_name),tz_vendedorid($select=tz_name), tz_cidade_principalid($select=tz_name), tz_estado_principalid($select=tz_uf)';

            // Procura cliente pessoa física
            if(strlen($document) == 14){
                $entity = "contacts";
                //adiciona campos específicos de cliente PF
                $select .= ",contactid, zatix_cpf,firstname,lastname,tz_ddd_telefone_principal,_tz_cep_cobranaid_value
                    ,tz_dddtelefoneresidencial";

                $api_request_parameters = array(
                    '$select' => $select,
                    '$filter'=>"zatix_cpf eq '{$document}'",
                    '$expand' => $expand
                );
            }else{// procura cliente pessoa jurídica
                $entity = "accounts";
                //adiciona campos específicos de cliente PJ
                $select .= ",accountid, zatix_cnpj,name,zatix_inscricaoestadual,zatix_inscricaomunicipal,tz_ddd_principal,
                    _tz_cep_cobrancaid_value,tz_nome_responsavel,tz_cargo_responsavel,tz_tipo_cliente,tz_dddtelefoneoutros
                        ,tz__data_desbloqueio_portal";

                $api_request_parameters = array(
                    '$select' => $select,
                    '$filter'=>"zatix_cnpj eq '{$document}'",
                    '$expand' => $expand . ',primarycontactid,parentaccountid($select=name,accountid)'
                );
            }

            $cliente = $this->sac->get($entity, $api_request_parameters);
            $cliente->entity = $entity;

            $resposta = array();
            if($cliente->code == 200){
                $value = (object) $cliente->data->value[0];
                $id = isset($value->accountid) ? $value->accountid : $value->contactid;
                
                if(!isset($value->address1_postalcode)){
                    $cep = $this->sac->get('tz_ceps',array(
                        '$select' => 'tz_cep1',
                        '$filter' => "tz_cepid eq ". $value->_tz_cep_principalid_value
                    ));
                }

                $resposta = array(
                    'Id' => $id,
                    'codeERP' => $value->zatix_codigocliente,
                    'storeERP' => $value->zatix_loja,
                    'fantasyName' => $value->zatix_nomefantasia,
                    'nomeResponsavel' => $value->tz_nome_responsavel,
                    'cargoResponsavel' => $value->tz_cargo_responsavel,
                    'particularidade' => $value->tz_possui_particularidade ? 1 : 0,
                    'gerenciadoraDeRisco' => $value->tz_gerenciadora_risco  ? 1 : 0,
                    'gerenciadoraRiscoNome' => $value->zatix_gerenciadorarisconome,
                    'envioSustentavel' => intval($value->tz_envio_sustentavel), //0 = NULL 1 = SIM 2 = NAO
                    'document' => isset($value->zatix_cnpj) ? $value->zatix_cnpj : $value->zatix_cpf,
                    'site' => $value->websiteurl,
                    'customertypecode' => $value->customertypecode,
                    'email' => $value->emailaddress1,
                    'emailTelemetria' => $value->emailaddress3,
                    'emailNovo' => $value->tz_emailaddress4,
                    'emailAF' => $value->tz_emailaf,
                    'emailLinker' => $value->emailaddress2,
                    'emailAlertaCerca' => $value->tz_email_alerta_cerca,
                    'telephone' => $value->tz_dddtelefoneprincipal,
                    'ddd2' => $value->tz_ddd_telefone1,
                    'telephone2' => $value->tz_telefone1,
                    'ddd3' => $value->tz_ddd_outro_telefone,
                    'dddCell' => $value->tz_ddd_telefone_celular,
                    'cellPhone' => $value->tz_dddtelefonecelular,
                    'dddfax' => $value->tz_dddfax,
                    'fax' => $value->fax,
                    'statusCadastro' => $value->statecode,
                    // CONTA PRIMARIA
                    'contaPrimaria' => array(
                        'id' => $value->parentaccountid->accountid,
                        'nome' => $value->parentaccountid->name,
                    ),
                    // Endereço principal
                    'address1_addressid' => $value->address1_postalcode,
                    'postalCode' => array(
                        'Id' => $value->_tz_cep_principalid_value,
                        "Name" => $value->address1_postalcode !== null ?  $value->address1_postalcode : $cep->data->value[0]->tz_cep1,
                    ),
                    'address' => $value->address1_line1,
                    'addressComplement' => $value->address1_line2,
                    'district' => $value->address1_line3,
                    'city' => array(
                        "Id" => $value->_tz_cidade_principalid_value,
                        "Name" => $value->tz_cidade_principalid->tz_name,
                        "Uf" => $value->tz_estado_principalid->tz_uf
                    ),
                    'stateOrProvince' => array(
                        'Id' => $value->_tz_estado_principalid_value,
                        'Name' => $value->address1_stateorprovince
                    ),
                    // Endereço cobrança
                    'address2_addressid' => $value->address2_addressid,

                    // Status Financeiro //
                    'status_atendimentoriveiculo'       => $value->zatix_atendimentoriveiculo,
                    'status_comunicacaochip'            => $value->zatix_comunicacaochip,
                    'status_comunicacaosatelital'       => $value->zatix_comunicacaosatelital,
                    'status_data_desbloqueio_portal'    => $value->tz__data_desbloqueio_portal,
                    'status_emissaopv'                  => $value->zatix_emissaopv,
                    'status_bloqueiototal'              => $value->zatix_bloqueiototal,
                    'status_desbloqueioportal'          => $value->tz_desbloqueioportal,
//FORMA DE COBRANÇA
                    'formaCobranca' => array(
                        "id" => $value->tz_forma_cobrancaid->tz_forma_cobrancaid,
                        "nome" => $value->tz_forma_cobrancaid->tz_name
                    ),
                    'billingaddress' => $value->address2_line1,
                    'billingAddressComplement' => $value->address2_line2,
                    'billingDistrict' => $value->address2_line3,
                    'billingCity' => $value->address2_city,

                    'billingStateorprovince' => array(
                        'Id' => '',
                        'Name' => $value->address2_stateorprovince,
                    ),
                    
                    // Endereço Entrega
                    'deliveryPostalCode' => array(
                        'Id' => $value->_tz_cep_entregaid_value,
                        'Name' => $value->zatix_enderecoentregacep
                    ),
                    'deliveryAddress' => $value->zatix_enderecoentrega,
                    'deliveryAddressComplement' => $value->zatix_enderecoentregacomplemento,
                    'deliveryDistrict' => $value->zatix_enderecoentregabairro,
                    'deliveryCity' => $value->zatix_enderecoentregamunicipio,
                    'deliveryStateorprovince' => array(
                        'Id' => $value->_tz_cep_entregaid_value,
                        'Name' => $value->zatix_enderecoentregaestado,
                    ),
                    'idSegmentation' => $value->_tz_segmentacao_cliente_value,
                    'segmentacaoManual' => $value->tz_segmentacao_manual ? 1 : 0,
                    'segmentation' => isset($value->tz_segmentacao_cliente->tz_name) ? $value->tz_segmentacao_cliente->tz_name : '',
                    'seller' => array(
                        'Id' => $value->_tz_vendedorid_value,
                        'Nome' => $value->tz_vendedorid->tz_name,
                    ),
                    'primarycontactid' => isset($value->primarycontactid) ? $value->primarycontactid : null,
                    'salesChannel' => array(
                        'Id' => $value->_tz_canal_vendaid_value,
                        'Nome' => $value->_tz_canal_vendaid_value
                    ),
                    'codigoClienteShow' => $value->tz_codigo_cliente_show,
                    'codigoClienteGraber' => $value->tz_codigo_cliente_graber,
                    'tipoCliente' => $value->tz_tipo_cliente,
                    //ANALISTA DE SUPORTE
                    'analistaSuporte' => array(
                        "id" => isset($value->tz_consultor_pos_vendas->systemuserid) ? $value->tz_consultor_pos_vendas->systemuserid : '',
                        "nome" => isset($value->tz_consultor_pos_vendas->fullname) ? $value->tz_consultor_pos_vendas->fullname : ''
                    )
                );

                if($entity == "accounts"){// Pessoa Jurídica
                    $resposta['name'] = $value->name;
                    $resposta['stateRegistration'] = $value->zatix_inscricaoestadual;
                    $resposta['inscricaoMunicipal'] = $value->zatix_inscricaomunicipal;
                    $resposta['ddd'] = $value->tz_ddd_principal;
                    $resposta['billingPostalCode'] = array(
                        'Id' => $value->_tz_cep_cobrancaid_value,
                        'Name' => $value->address1_postalcode,
                    );
                    $resposta['telephone3'] = $value->tz_dddtelefoneoutros;

                    $resposta['grupoEconomico'] = $this->get_grupo_economico($document);
                }else{//Pessoa Física
                    $resposta['firstname'] = $value->firstname;
                    $resposta['lastname'] = $value->lastname;
                    $resposta['ddd'] = $value->tz_ddd_telefone_principal;
                    $resposta['billingPostalCode'] = array(
                        'Id' => $value->_tz_cep_cobranaid_value,
                        'Name' => $value->address1_postalcode,
                    );
                    $resposta['telephone3'] = $value->tz_dddtelefoneresidencial;
                }
                //função para verificar se existe alguma providência pendente nesse cliente
                $resposta['providencia'] = $this->verificar_providencias($id);
                $resposta['entity'] =  $cliente->entity;
                if( isset($_SESSION['clientes'])){
                    if(count($_SESSION['clientes']) <= 5){
                        if (!in_array($resposta, $_SESSION['clientes'])){
                            array_push($_SESSION['clientes'], $resposta);
                        }
                    } else {
                        $_SESSION['clientes'][0] = $resposta;
                    }
                    
                } else {
                    $_SESSION['clientes'] = array();
                    array_push($_SESSION['clientes'], $resposta);
                }
                
                if(!$this->auth->is_allowed_block('out_usuarioceabs')){
                    echo json_encode(array(
                        'code' => $cliente->code,
                        'customers' => $resposta
                    ));
                } else {
                    if(strlen($document) == 14){
                        $filtroPjPf = '<condition attribute="tz_cliente_pfid" operator="eq" uitype="contact" value="'.$resposta['Id'].'" />';
                    }else{
                        $filtroPjPf = '<condition attribute="tz_cliente_pjid" operator="eq" uitype="account" value="'.$resposta['Id'].'" />';
                    }
                    $xml = '<fetch>
                        <entity name="tz_item_contrato_venda">
                            <attribute name="tz_name" />
                            <attribute name="tz_plataformaid" />
                            <filter>
                                '.$filtroPjPf.'
                            </filter>
                            <link-entity name="tz_produto_servico_contratado" from="tz_codigo_item_contratoid" to="tz_item_contrato_vendaid" alias="tz_produto">
                                <attribute name="tz_produtoid" />
                            </link-entity>
                        </entity>
                    </fetch>';
                    
                    $api_request_parameters = array('fetchXml' => $xml);
                    $response = $this->sac->get('tz_item_contrato_vendas',$api_request_parameters);
                    $ceabs = $response->data;
                    $ceabs = $ceabs->value;
                    $boolLiberamento = false;
                    //array contendo as plataformas que necessitam serem verificadas para realizar a liberação dos dados dos cliente ou não 5,6,7
                    $arrayPlataformas = ['63006545-60b4-e911-95e6-005056ba64fc', '7e40dbc5-967f-e611-91a8-005056800012', '22c0f390-a00e-e711-b382-005056800012', 'c5951d7a-d384-e211-88b0-005056800011', '4f28b01a-936d-e211-a070-005056800011', '1eceac39-917f-e611-91a8-005056800012', '37376bd3-47cb-e211-9a6e-005056800011', '6adf5f1b-29bf-e611-80d7-005056800012', '87282141-25cf-e911-95e6-005056ba64fc'];

                    foreach ($ceabs as $item){
                        if($item->{'_tz_plataformaid_value'} == $arrayPlataformas[5] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[6] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[7]){
                            $boolLiberamento = true;
                            break;
                        }

                        if ($item->{'_tz_plataformaid_value'} == $arrayPlataformas[0] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[1] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[2] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[3] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[4] || $item->{'_tz_plataformaid_value'} == $arrayPlataformas[8]){
                            if($item->{'tz_produto_x002e_tz_produtoid'} == "29BC7BD8-61E1-EA11-B889-005056BA183F"){
                                $boolLiberamento = true;
                                break;
                            }
                        }
                    }

                    echo json_encode(array(
                        'code' => $cliente->code,
                        'customers' => $resposta,
                        'ceabs' => $boolLiberamento
                    ));
                }
                
            }else{
                echo json_encode(array('code' => $cliente->code, 'error' => $cliente->value));
            }
        } catch (\Throwable $th) {
            throw $th;
        }   
    }

    public function buscarSystemUser(){
        //pega o parametro passado
        $search = $this->input->get('q');

        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];

        $requisicaoUsers = $this->sac->get('systemusers',array(
            '$select' => 'systemuserid,fullname',
            '$filter' => "contains(fullname, '{$search}')",
            '$orderby' => 'fullname asc'
        ));

        //verifica se o código de retorno está correto para rodar o for
        if($requisicaoUsers->code == 200){
            $values = $requisicaoUsers->data->value;
            foreach ($values as $value) {

                $resposta['results'][] = array(
                    'id' => $value->systemuserid,
                    'text' => $value->fullname
                );
            }
        }
        echo json_encode($resposta);
    }
    /**
     * Busca o grupo econômico do cliente
     * Grupo econômico é aquele que possui a mesma base do cnpj
     * Ex.: 
     * 99.999.999/0000-01
     * 99.999.999/0010-02
     * 99.999.999/1100-30
     */
    public function get_grupo_economico($cnpjCliente){
        try {
            $baseCNPJ = explode('/',$cnpjCliente)[0].'/';
            
            $entity = "accounts";
            $api_request_parameters = array(
                '$select' => 'zatix_cnpj',
                '$filter'=>"(startswith(zatix_cnpj, '{$baseCNPJ}') eq true) and (zatix_cnpj  ne '{$cnpjCliente}')"
            );
            $grupoEconomico = $this->sac->get($entity, $api_request_parameters);
            
            if($grupoEconomico->code == 200){
                return $grupoEconomico->data->value;
            }else{
                return array();
            }
        } catch (\Throwable $th) {
            return array();
        }
    }

    public function avisoDePagamentoCRM() {
        $document = $this->input->post('document');
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;
        $documentFormtado = strlen($document) == 11 ? vsprintf('%s%s%s.%s%s%s.%s%s%s-%s%s', str_split($document)) : vsprintf('%s%s.%s%s%s.%s%s%s/%s%s%s%s-%s%s', str_split($document));
        
        $dadosCliente = $this->buscarDadosClienteToAviso($documentFormtado);
        
        $dados_antigos = array(
            "bloqueioTotal"         => $dadosCliente->zatix_bloqueiototal ? "true" : "false",
            "agendamento"           => $dadosCliente->zatix_atendimentoriveiculo ? "true" : "false",
            "comunicacaoSatelite"   => $dadosCliente->zatix_comunicacaosatelital ? "true" : "false",
            "comunicacaoChip"       => $dadosCliente->zatix_comunicacaochip ? "true" : "false",
            "emissãoPV"             => $dadosCliente->zatix_emissaopv ? "true" : "false",
            "desbloqueioPortal"     => $dadosCliente->tz_desbloqueioportal
        );

        $dados = array(
            "bloqueioTotal"         => "false",
            "agendamento"           => "true",
            "comunicacaoSatelite"   => "true",
            "comunicacaoChip"       => "true",
            "emissãoPV"             => "true",
            "desbloqueioPortal"     => ""
        );

        if (is_string($document) && !is_null($document)) {
            $retorno = $this->sac->putAvisoDePagamento([
                'Cpf_Cnpj' => $document,
                'bloqueioTotal' => $dados['bloqueioTotal'],
                'agendamento' => $dados['agendamento'],
                'comunicacaoSatelite' => $dados['comunicacaoSatelite'],
                'comunicacaoChip' => $dados['comunicacaoChip'],
                'emissãoPV' => $dados['emissãoPV'],
                'desbloqueioPortal' => ''
            ]);

            if ($retorno->Status == 200) {
                $this->log_shownet->gravar_log($id_user, 'Aviso de pagamento (CRM)', $document, 'atualizar', $dados_antigos, $dados);
            }
            
            exit(json_encode($retorno));

        }

        exit(json_encode(['Status' => 400, 'Message' => 'Document not informed']));
    }

    public function ajax_atualizar_cliente() {
        $dados = $this->input->post();
        $id = $dados['Id'];
       
        $api_request_parameters = $this->get_body_request_cliente($dados);
        
        if(strlen($dados['Cpf_Cnpj']) == 14){ //Pessoa Física
            $entity = "contacts";            
        }else{// Pessoa Jurídica
            $entity = "accounts";
        }

        //verifica se houve alteração nos grupos de email
        if(isset($api_request_parameters['grupoEmailsRetorno'])){
            //adiciona a mensagem a uma variável e remove a mensagem do retorno para fazer o patch
            $mensagemGrupoEmails = $api_request_parameters['grupoEmailsRetorno'];
            unset($api_request_parameters['grupoEmailsRetorno']);
        } 

        if(empty($api_request_parameters) && isset($mensagemGrupoEmails)){
            echo json_encode(array(
                "code" => 200,
                "value" => "Grupo de e-mails atualizados com sucesso",
            ));
        }else{

            $response = $this->sac->patch($entity, $id, $api_request_parameters);
            if($response->code == 200){
                $value = $response->data;
                $resposta = array(
                    "fantasyName" => $value->zatix_nomefantasia,
                    "customertypecode" => $value->customertypecode,
                    "seller" => array(
                        'Id' => $value->_tz_vendedorid_value,
                        'Nome' => $value->zatix_nomevendedor
                    ),
                    "salesChannel" => array(
                        'Id' => $value->_tz_canal_vendaid_value
                    ),
                    "email" => $value->emailaddress1,
                    "address" => $value->address1_line1,
                    "district" => $value->address1_line3,
                    "postalCode" => array(
                        'Id' => $value->_tz_cep_principalid_value,
                        'Name' => $value->address1_postalcode
                    ),
                    "addressComplement" => $value->address1_line2,
                    "billingCity" => $value->address2_city,
                    "billingDistrict" => $value->address2_line3,
                    "billingAddressComplement" => $value->address2_line2,
                    "deliveryCity" => $value->zatix_enderecoentregamunicipio,
                    "deliveryDistrict" => $value->zatix_enderecoentregabairro,
                    "deliveryPostalCode" => array(
                        'Id' => $value->_tz_cep_entregaid_value,
                        'Name' => $value->zatix_enderecoentregacep
                    ),
                    "deliveryAddressComplement" => $value->zatix_enderecoentregacomplemento,
                    'telephone' => $value->tz_dddtelefoneprincipal,
                    'ddd2' => $value->tz_ddd_telefone1,
                    'telephone2' => $value->tz_telefone1,
                    'ddd3' => $value->tz_ddd_outro_telefone,
                    'dddCell' => $value->tz_ddd_telefone_celular,
                    'cellPhone' => $value->tz_dddtelefonecelular,
                    'dddfax' => $value->tz_dddfax,
                    'fax' => $value->fax,
                    'tipoCliente' => $value->tz_tipo_cliente,
                    'idSegmentation' => $value->_tz_segmentacao_cliente_value,
                    'clientEntity' => $entity,
                    'emailTelemetria' => $value->emailaddress3,
                    'emailNovo' => $value->tz_emailaddress4,
                    'emailaddress2' => $value->emailaddress2,
                    'analistaSuporte' => isset($value->tz_consultor_pos_vendas) ? $value->tz_consultor_pos_vendas->yomifullname : '',
                    'possuiProvidencia' => $value->tz_possui_providencia,
                    'statusCadastro' => $value->statuscode
                );
    
                if($entity == "accounts"){// Pessoa Jurídica
                    $resposta['Id'] = $value->accountid;
                    $resposta['document'] = $value->zatix_cnpj;
                    
                    $resposta['name'] = $value->name;
                    $resposta['stateRegistration'] = $value->zatix_inscricaoestadual;
                    $resposta['ddd'] = $value->tz_ddd_principal;
                    $resposta['billingPostalCode'] = array(
                        'Id' => $value->_tz_cep_cobrancaid_value,
                        'Name' => $value->address1_postalcode,
                    );
                    $resposta['telephone3'] = $value->tz_dddtelefoneoutros;
    
                }else{//Pessoa Física
                    $resposta['Id'] = $value->contactid;
                    $resposta['document'] = $value->zatix_cpf;
    
                    $resposta['firstname'] = $value->firstname;
                    $resposta['lastname'] = $value->lastname;
                    $resposta['ddd'] = $value->tz_ddd_telefone_principal;
                    $resposta['billingPostalCode'] = array(
                        'Id' => $value->_tz_cep_cobranaid_value,
                        'Name' => $value->address1_postalcode,
                    );
                    $resposta['telephone3'] = $value->tz_dddtelefoneresidencial;
                }
                
                if(isset($mensagemGrupoEmails))
                    $resposta['mensagemGrupoEmails'] = $mensagemGrupoEmails;
    
                    
                echo json_encode(array(
                    "code" => $response->code,
                    "value" => $resposta,
                ));
    
            }else{
                echo json_encode(array(
                    "code" => $response->code,
                    "error" => $response->data,
                ));
            }
        }
    }
    /**
     * Função que retorna os dados formatados para requisição de atualizar cliente
     * @param Array $dados
     * @return Array
     */
    public function get_body_request_cliente($dados){
        $body = array();

        if(isset($dados['status_atendimentoriveiculo'])     && $dados['status_atendimentoriveiculo'] != "")     $body['zatix_atendimentoriveiculo']     =   $dados['status_atendimentoriveiculo'];
        if(isset($dados['status_comunicacaochip'])          && $dados['status_comunicacaochip'] != "")          $body['zatix_comunicacaochip']          =   $dados['status_comunicacaochip'];
        if(isset($dados['status_comunicacaosatelital'])     && $dados['status_comunicacaosatelital'] != "")     $body['zatix_comunicacaosatelital']     =   $dados['status_comunicacaosatelital'];
        if(isset($dados['status_data_desbloqueio_portal'])  && $dados['status_data_desbloqueio_portal'] != "")  $body['tz__data_desbloqueio_portal']    =   $dados['status_data_desbloqueio_portal'];
        if(isset($dados['status_emissaopv'])                && $dados['status_emissaopv'] != "")                $body['zatix_emissaopv']                =   $dados['status_emissaopv'];
        if(isset($dados['status_bloqueiototal'])            && $dados['status_bloqueiototal'] != "")            $body['zatix_bloqueiototal']            =   $dados['status_bloqueiototal'];
        if(isset($dados['status_desbloqueioportal'])        && $dados['status_desbloqueioportal'] != "")        $body['tz_desbloqueioportal']           =   $dados['status_desbloqueioportal'];

        if(isset($dados['Vendedor']) && $dados['Vendedor'] != "") $body['tz_vendedorid@odata.bind'] = "/tz_vendedors(".$dados['Vendedor'].")";
        if(isset($dados['CanalVenda']) && $dados['CanalVenda'] != "") $body['tz_canal_vendaid@odata.bind'] = "/tz_canal_vendas(".$dados['CanalVenda'].")";
        if(isset($dados['formaCobranca']) && $dados['formaCobranca'] != "") $body['tz_forma_cobrancaid@odata.bind'] = "/tz_forma_cobrancas(".$dados['formaCobranca'].")";
        if(isset($dados['contaPrimaria']) && $dados['contaPrimaria'] != "") $body['parentaccountid@odata.bind'] = "/accounts(".$dados['contaPrimaria'].")";
        if(isset($dados['Segmentacao']) && $dados['Segmentacao'] != "") $body['tz_segmentacao_cliente@odata.bind'] = "/tz_segmentacao_clientes(".$dados['Segmentacao'].")";
        if(isset($dados['analistaSuporte']) && $dados['analistaSuporte'] != "") $body['tz_consultor_pos_vendas@odata.bind'] = "/systemusers(".$dados['analistaSuporte'].")";
        if(isset($dados['NomeFantasia_Sobrenome']) && $dados['NomeFantasia_Sobrenome'] != "") $body['zatix_nomefantasia'] = $dados['NomeFantasia_Sobrenome'];
        if(isset($dados['TipoRelacao']) && $dados['TipoRelacao'] != "") $body['customertypecode'] = $dados['TipoRelacao'];
        if(isset($dados['Email']) && $dados['Email'] != "") $body['emailaddress1'] = $dados['Email'];
        if(isset($dados['enderecoPrincipal']) && $dados['enderecoPrincipal'] != "") $body['address1_line1'] = $dados['enderecoPrincipal'];
        if(isset($dados['bairroPrincipal']) && $dados['bairroPrincipal'] != "") $body['address1_line3'] = $dados['bairroPrincipal'];
        if(isset($dados['CepPrincipal']) && $dados['CepPrincipal'] != "") $body['address1_postalcode'] = $dados['CepPrincipal'];
        if(isset($dados['complementoPrincipal']) && $dados['complementoPrincipal'] != "") $body['address1_line2'] = $dados['complementoPrincipal'];
        if(isset($dados['enderecoCobranca']) && $dados['enderecoCobranca'] != "") $body['address2_line1'] = $dados['enderecoCobranca'];
        if(isset($dados['bairroCobranca']) && $dados['bairroCobranca'] != "") $body['address2_line3'] = $dados['bairroCobranca'];
        if(isset($dados['CepCobranca']) && $dados['CepCobranca'] != "") $body['address2_postalcode'] = $dados['CepCobranca'];
        if(isset($dados['complementoCobranca']) && $dados['complementoCobranca'] != "") $body['address2_line2'] = $dados['complementoCobranca'];
        if(isset($dados['enderecoEntrega']) && $dados['enderecoEntrega'] != "") $body['zatix_enderecoentrega'] = $dados['enderecoEntrega'];
        if(isset($dados['bairroEntrega']) && $dados['bairroEntrega'] != "") $body['zatix_enderecoentregabairro'] = $dados['bairroEntrega'];
        if(isset($dados['CepEntrega']) && $dados['CepEntrega'] != "") $body['zatix_enderecoentregacep'] = $dados['CepEntrega'];
        if(isset($dados['complementoEntrega']) && $dados['complementoEntrega'] != "") $body['zatix_enderecoentregacomplemento'] = $dados['complementoEntrega'];
        if(isset($dados['Telefone']) && $dados['Telefone'] != "") $body['tz_dddtelefoneprincipal'] = $dados['Telefone'];
        if(isset($dados['DDD2']) && $dados['DDD2'] != "") $body['tz_ddd_telefone1'] = $dados['DDD2'];
        if(isset($dados['Telefone2']) && $dados['Telefone2'] != "") $body['tz_telefone1'] = $dados['Telefone2'];
        if(isset($dados['DDD3']) && $dados['DDD3'] != "") $body['tz_ddd_outro_telefone'] = $dados['DDD3'];
        if(isset($dados['DDDCel']) && $dados['DDDCel'] != "") $body['tz_ddd_telefone_celular'] = $dados['DDDCel'];
        if(isset($dados['Celular']) && $dados['Celular'] != "") $body['tz_dddtelefonecelular'] = $dados['Celular'];
        if(isset($dados['DDDFax']) && $dados['DDDFax'] != "") $body['tz_dddfax'] = $dados['DDDFax'];
        if(isset($dados['Fax']) && $dados['Fax'] != "") $body['fax'] = $dados['Fax'];
        if(isset($dados['ClassificacaoCliente']) && $dados['ClassificacaoCliente'] != "") $body['tz_tipo_cliente'] = $dados['ClassificacaoCliente'];
        if(isset($dados['envioSustentavel']) && $dados['envioSustentavel'] != "") $body['tz_envio_sustentavel'] = intval($dados['envioSustentavel']); //0 = NULL 1 = SIM 2 = NAO
        if(isset($dados['segmentacaoManual']) && $dados['segmentacaoManual'] != "") $body['tz_segmentacao_manual'] = boolval($dados['segmentacaoManual']);
        if(isset($dados['particularidade']) && $dados['particularidade'] != "") $body['tz_possui_particularidade'] = boolval($dados['particularidade']);
        if(isset($dados['EmailTelemetria']) && $dados['EmailTelemetria'] != "") $body['emailaddress3'] = $dados['EmailTelemetria'];
        if(isset($dados['EmailAF']) && $dados['EmailAF'] != "") $body['tz_emailaf'] = $dados['EmailAF'];
        if(isset($dados['EmailLinker']) && $dados['EmailLinker'] != "") $body['emailaddress2'] = $dados['EmailLinker'];
        if(isset($dados['EmailNovo']) && $dados['EmailNovo'] != "") $body['tz_emailaddress4'] = $dados['EmailNovo'];
        if(isset($dados['EmailAlertaCerca']) && $dados['EmailAlertaCerca'] != "") $body['tz_email_alerta_cerca'] = $dados['EmailAlertaCerca'];
        if(isset($dados['site']) && $dados['site'] != "") $body['websiteurl'] = $dados['site'];
        if(isset($dados['gerenciadoraRiscoNome']) && $dados['gerenciadoraRiscoNome'] != "") $body['zatix_gerenciadorarisconome'] = $dados['gerenciadoraRiscoNome'];
        if(isset($dados['gerenciadoraDeRisco']) && $dados['gerenciadoraDeRisco'] != "") $body['tz_gerenciadora_risco'] = boolval($dados['gerenciadoraDeRisco']);
        if(isset($dados['cargoResponsavel']) && $dados['cargoResponsavel'] != "") $body['tz_cargo_responsavel'] = $dados['cargoResponsavel'];
        if(isset($dados['nomeResponsavel']) && $dados['nomeResponsavel'] != "") $body['tz_nome_responsavel'] = $dados['nomeResponsavel'];
        if(isset($dados['InscricaoMunicipal']) && $dados['InscricaoMunicipal'] != "") $body['zatix_inscricaomunicipal'] = $dados['InscricaoMunicipal'];
        if(isset($dados['statusCADASTRO']) && $dados['statusCADASTRO'] != "") $body['statecode'] = intval($dados['statusCADASTRO']);

        $cpf_cnpj = $dados['Cpf_Cnpj'];
        // Seta CPF e CNPJ de acordo com o tamanho do dado
        if(strlen($cpf_cnpj) == 14){ //Pessoa Física
            if(isset($cpf_cnpj) && $cpf_cnpj != "") $body['zatix_cpf'] = $cpf_cnpj;
            if(isset($dados['firstname']) && $dados['firstname'] != "" ) $body['firstname'] = $dados['firstname'];
            if(isset($dados['lastname']) && $dados['lastname'] != "" ) $body['lastname'] = $dados['lastname'];
            if(isset($dados['DDD']) && $dados['DDD'] != "" ) $body['tz_ddd_telefone_principal'] = $dados['DDD'];
            if(isset($dados['Telefone3']) && $dados['Telefone3'] != "" ) $body['tz_dddtelefoneresidencial'] = $dados['Telefone3'];
            
        }else{// Pessoa Jurídica
            if(isset($cpf_cnpj) && $cpf_cnpj != "") $body['zatix_cnpj'] = $cpf_cnpj;
            if(isset($dados['Nome']) && $dados['Nome'] != "" ) $body['name'] = $dados['Nome'];
            if(isset($dados['InscricaoEstadual']) && $dados['InscricaoEstadual'] != "" ) $body['zatix_inscricaoestadual'] = $dados['InscricaoEstadual'];
            if(isset($dados['DDD']) && $dados['DDD'] != "" ) $body['tz_ddd_principal'] = $dados['DDD'];
            if(isset($dados['Telefone3']) && $dados['Telefone3'] != "" ) $body['tz_dddtelefoneoutros'] = $dados['Telefone3'];
            if(isset($dados['InscricaoEstadual']) && $dados['InscricaoEstadual'] != "" ) $body['zatix_inscricaoestadual'] = $dados['InscricaoEstadual'];
            if(isset($dados['inscricaoMunicipal']) && $dados['inscricaoMunicipal'] != "" ) $body['zatix_inscricaomunicipal'] = $dados['inscricaoMunicipal'];
        }

        //grupo de emails
        if(isset($dados['id-emails']) && $dados['id-emails'] != ""){
            $dadosEmail = null; 

            for($i = 1 ; $i < 11 ; $i++){
                $indiceAtual = 'email' . $i;
                $idIndiceAtual = 'id-email' . $i;

                if(isset($dados[$indiceAtual]) && $dados[$indiceAtual] != ""){
                    $emails[$i] = $dados[$indiceAtual];
                    $idEmails[$i] = $dados[$idIndiceAtual];
                    $indiceEmail[$i] = 'tz_email' . $i . 'id' .  "@odata.bind";
                } 

                if(isset($emails) && isset($idEmails)){
                    $dadosEmail[] = array(
                        'email' => $emails[$i],
                        'id' => $idEmails[$i],
                        'indiceEmail' => $indiceEmail[$i]
                    );
                    unset($emails);
                    unset($idEmails);
                }
            }
            
            $body['grupoEmailsRetorno'] = $this->atualizarGrupoDeEmail($dadosEmail,$dados['id-emails']);
                
        }

        return $body;
    }

    /**
     * Requisição que retorna a lista de assuntos
     */
    public function ajax_get_assuntos(){
        try {
            $url = 'subjects';

            //pega somente os assuntos com fila válida
            $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="true">
            <entity name="subject"> 
            <attribute name="subjectid" />
            <attribute name="title" />
            <order attribute="title" descending="false" /> 
            <link-entity name="zatix_responsavelfla" from="zatix_assuntoid" to="subjectid" alias="ah">
            <filter type="and">
            <condition attribute="zatix_inativo" operator="eq" value="0" />
            <condition attribute="zatix_filaid" operator="not-null" />
            </filter>
            </link-entity> 
            </entity>
            </fetch>';

            $api_request_parameters = array('fetchXml' => $xml);
            
            $response = $this->sac->get($url,$api_request_parameters);
            if($response->code == 200){
                echo json_encode(array(
                    'code' => $response->code,
                    'values' => $response->data->value
                ));
            }else{
                echo json_encode(array("code" => $response->code, "error" => $response->data));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function buscarFormasCobranca(){
        //pega o parametro passado
        $search = $this->input->get('q');

        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];

        $requisicaoFormasCobranca = $this->sac->get('tz_forma_cobrancas',array(
            '$select' => 'tz_forma_cobrancaid,tz_name',
            '$filter' => "contains(tz_name, '{$search}')",
            '$orderby' => 'tz_name asc'
        ));

        //verifica se o código de retorno está correto para rodar o for
        if($requisicaoFormasCobranca->code == 200){
            $values = $requisicaoFormasCobranca->data->value;
            foreach ($values as $value) {

                $resposta['results'][] = array(
                    'id' => $value->tz_forma_cobrancaid,
                    'text' => $value->tz_name
                );
            }
        }
        
        echo json_encode($resposta);
    }
    /**
     * Requisição que retorna a lista de vendedores
     */
    public function ajax_get_vendedores(){
        try {
            $url = 'tz_vendedors';
            $api_request_parameters = array('$select'=>'tz_vendedorid,tz_name','$orderby' => "tz_name asc");
            
            $response = $this->sac->get($url,$api_request_parameters);
            if($response->code == 200){
                echo json_encode(array(
                    'code' => $response->code,
                    'values' => $response->data->value
                ));
            }else{
                echo json_encode(array("code" => $response->code, "error" => $response->data));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /**
     * Requisição que retorna a lista de canais de vendas
     */
    public function ajax_get_canais_vendas(){
        try {
            $url = 'tz_canal_vendas';
            $api_request_parameters = array('$select'=>'tz_canal_vendaid,tz_name');
            
            $response = $this->sac->get($url,$api_request_parameters);
            if($response->code == 200){
                echo json_encode(array(
                    'code' => $response->code,
                    'values' => $response->data->value
                ));
            }else{
                echo json_encode(array("code" => $response->code, "error" => $response->data));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Retorna histórico de anotações da ocorrencia
     */
    public function ajax_get_historico_anotacoes($idOcorrencia, $return = false) {
        $response = $this->sac->get_anotacoes($idOcorrencia);

        if ($return === true) {
            if ($response->code == 200)
                return array('data' => $response->data->value);
            else
                return [];
        } else {
            if($response->code == 200){
                echo json_encode(array("code" => $response->code, "value" => $response->data->value));
            }else{
                echo json_encode($response);
            }
        }
    }
    /**
     * Insere Anotações para tratar as ocorrências
     * cnpj show - 09.338.999/0002-39
     */
    public function ajax_inserir_anotacao() {
        $dados = $this->input->post();
        pr($dados);
    }

    /**
     * Public function salva auditoria das modificações realizadas pelo usuário.
     * Os arrays de valores antigos e novos devem possuir a mesma chave.
     */
    public function ajax_salvar_auditoria(){
        $dados = $this->input->post();

        if(!isset($dados['url_api'])){
            echo json_encode(array(
                'status' => false,
                'error' => 'url_api não informada.'
            ));
        }elseif(!isset($dados['clause'])){
            echo json_encode(array(
                'status' => false,
                'error' => 'clause não informada.'
            ));
        }elseif(!isset($dados['valores_novos'])){
            echo json_encode(array(
                'status' => false,
                'error' => 'valores_novos não informado.'
            ));
        }elseif(!is_array($dados['valores_novos'])){
            echo json_encode(array(
                'status' => false,
                'error' => 'valores_novos deve ser um array.'
            ));
        }else{
            $resposta = $this->painelOmnilink->salvar_auditoria($dados);
            if($resposta) {
                echo json_encode(array(
                    'status' => true,
                    'message' => 'Auditoria salva com sucesso!'
                ));
            }else{
                echo json_encode(array(
                    'status' => false,
                    'error' => 'Erro ao salvar auditoria.'
                )); 
            }
        }
    }

    /**
     * Envia ficha de ativação
     */
    public function sendActivationToken()
    {   
        try {
            # Recupera dados da requisição
            $data   = $this->input->post();

            # Cria parâmetros de retorno
            $msg    = ''; # Mensagem de retorno ao usuário
            $status = false; # Controlador de Status de resposta da requisição

            # Gera Token para API Gateway
            $token = GetTokenGatewayOmnilink();
            
            if (!empty($token) && !empty($data)) {
                # Envia a ficha de ativação
                $envio = sendActivationTokenOmnilink($data, $token.access_token);
                
                # Trata resposta ao usuário
                $status = $envio ? true : false;
                $msg = $envio ? 'Ficha de ativação enviada com sucesso.' : 'Não foi possível enviar a ficha de ativação. #ErroGatewayAPI';
            } else {
                # Cria retorno caso obter token falhe
                $status = false;
                $msg = 'Não foi possível obter o token. #GetTokenGatewayOmnilink';
            }

            # Resposta da requisição
            die(
                json_encode(
                    [
                        'status'    => $status,
                        'msg'       => $msg
                    ]
                )
            );
        } catch (\Throwable $th) {
            die(
                json_encode(
                    [
                        'status' => false,
                        'msg' => 'Erro inesperado. #GetTokenGateway'
                    ]
                )
            );
        }
    }

    /**
     * Consultar as Ocorrências de um Cliente por Status
     *
     * @param string $clientid  Id do Cliente
     * @param int    $statecode Status da Ocorrência
     *  
     * @return void
     */
    public function listar_ocorrencias($clientid, $statecode)
    {
        // Nome da Tabela de Ocorrências para consulta
        $entity = 'incident';
        try{
            // Informações recebidas através da requisição do DataTables
            $draw   = $this->input->post('draw');
            $length = $this->input->post('length');
            $start  = $this->input->post('start');
            $search = $this->input->post('search');
            $search = $search['value'];

            // Calcular número da página a ser exibida, de acordo com os parâmetros
            // passados pelo plugin do DataTables
            $pagenumber = $start / $length + 1;

            // Paramêtros da requisição ao CRM para obter o número total de
            // Ocorrências do cliente por Status.
            $totalXml = "
            <fetch aggregate='true'>
            <entity name='{$entity}'>
                <attribute name='{$entity}id' alias='{$entity}scount' aggregate='count' />
                <filter>
                <condition attribute='statecode' operator='eq' value='{$statecode}' />
                <condition attribute='customerid' operator='eq' value='{$clientid}' />
                </filter>
            </entity>
            </fetch>
            ";

            // Requisição ao CRM para obter o número total de Ocorrências do
            // Cliente por Status.
            $totalResult = $this->sac->buscar("{$entity}s", http_build_query(['fetchXml' => $totalXml]));
    
            // Parâmetros da requisição ao CRM para obter o número total de
            // Ocorrências do Cliente por Status, filtrando o termo pesquisado pelo
            // Usuário.
            $filteredTotalXml = "
            <fetch aggregate='true'>
              <entity name='{$entity}'>
                <attribute name='{$entity}id' alias='{$entity}scount' aggregate='count' />
                <filter>
                  <condition attribute='statecode' operator='eq' value='{$statecode}' />
                  <condition attribute='customerid' operator='eq' value='{$clientid}' />
                </filter>
                <filter type='or'>
                  <condition attribute='ticketnumber' operator='like' value='%{$search}%' />
                  <condition entityname='subject' attribute='title' operator='like' value='%{$search}%' />
                  <condition entityname='type' attribute='value' operator='like' value='%{$search}%' />
                  <condition entityname='origin' attribute='value' operator='like' value='%{$search}%' />
                  <condition entityname='technology' attribute='tz_name' operator='like' value='%{$search}%' />
                  <condition attribute='zatix_filaatual' operator='like' value='%{$search}%' />
                  <condition entityname='cause' attribute='value' operator='like' value='%{$search}%' />
                  <condition attribute='description' operator='like' value='%{$search}%' />
                  <condition attribute='zatix_observacoes' operator='like' value='%{$search}%' />
                  <condition attribute='tz_usuario_gestor' operator='like' value='%{$search}%' />
                  <condition entityname='createdby' attribute='fullname' operator='like' value='%{$search}%' />
                  <condition attribute='tz_usuario_gestor_mod' operator='like' value='%{$search}%' />
                  <condition entityname='modifiedby' attribute='fullname' operator='like' value='%{$search}%' />
                </filter>
                <link-entity name='subject' from='subjectid' to='subjectid' alias='subject' />
                <link-entity name='stringmap' from='attributevalue' to='casetypecode' alias='type'>
                <filter>
                  <condition attribute='objecttypecode' operator='eq' value='112' />
                  <condition attribute='attributename' operator='eq' value='casetypecode' />
                </filter>
                </link-entity>
                <link-entity name='stringmap' from='attributevalue' to='caseorigincode' alias='origin'>
                <filter>
                  <condition attribute='objecttypecode' operator='eq' value='112' />
                  <condition attribute='attributename' operator='eq' value='caseorigincode' />
                </filter>
                </link-entity>
                <link-entity name='tz_tecnologia' from='tz_tecnologiaid' to='tz_tecnologia' link-type='outer' alias='technology' />
                <link-entity name='stringmap' from='attributevalue' to='statuscode' alias='cause'>
                <filter>
                  <condition attribute='objecttypecode' operator='eq' value='112' />
                  <condition attribute='attributename' operator='eq' value='statuscode' />
                </filter>
                </link-entity>
                <link-entity name='stringmap' from='attributevalue' to='tz_tipo_atendimento' link-type='outer' alias='servicetype'>
                <filter>
                  <condition attribute='objecttypecode' operator='eq' value='112' />
                  <condition attribute='attributename' operator='eq' value='tz_tipo_atendimento' />
                </filter>
                </link-entity>
                <link-entity name='systemuser' from='systemuserid' to='createdby' alias='createdby' />
                <link-entity name='systemuser' from='systemuserid' to='modifiedby' alias='modifiedby' />
              </entity>
            </fetch>
            ";
    
            // Requisição ao CRM para obter o número total de Ocorrências do
            // Cliente por Status, filtrando o termo pesquisado pelo Usuário.
            $filteredTotalResult = $this->sac->buscar("{$entity}s", http_build_query(['fetchXml' => $filteredTotalXml]));
    
            // Parâmetros da requisição ao CRM para obter os dados das Ocorrências
            // do Cliente por Status, filtrando o termo pesquisado pelo Usuário.
            if($filteredTotalResult['code'] === 200){
    
                $dataXml = "
                <fetch count='{$length}' page='{$pagenumber}'>
                  <entity name='{$entity}'>
                    <attribute name='{$entity}id' />
                    <attribute name='ticketnumber' />
                    <attribute name='zatix_filaatual' />
                    <attribute name='description' />
                    <attribute name='zatix_observacoes' />
                    <attribute name='tz_usuario_gestor' />
                    <attribute name='createdon' />
                    <attribute name='tz_usuario_gestor_mod' />
                    <attribute name='modifiedon' />
                    <filter>
                      <condition attribute='statecode' operator='eq' value='{$statecode}' />
                      <condition attribute='customerid' operator='eq' value='{$clientid}' />
                    </filter>
                    <filter type='or'>
                      <condition attribute='ticketnumber' operator='like' value='%{$search}%' />
                      <condition entityname='subject' attribute='title' operator='like' value='%{$search}%' />
                      <condition entityname='type' attribute='value' operator='like' value='%{$search}%' />
                      <condition entityname='origin' attribute='value' operator='like' value='%{$search}%' />
                      <condition entityname='technology' attribute='tz_name' operator='like' value='%{$search}%' />
                      <condition attribute='zatix_filaatual' operator='like' value='%{$search}%' />
                      <condition entityname='cause' attribute='value' operator='like' value='%{$search}%' />
                      <condition entityname='servicetype' attribute='value' operator='like' value='%{$search}%' />
                      <condition attribute='description' operator='like' value='%{$search}%' />
                      <condition attribute='zatix_observacoes' operator='like' value='%{$search}%' />
                      <condition attribute='tz_usuario_gestor' operator='like' value='%{$search}%' />
                      <condition entityname='createdby' attribute='fullname' operator='like' value='%{$search}%' />
                      <condition attribute='tz_usuario_gestor_mod' operator='like' value='%{$search}%' />
                      <condition entityname='modifiedby' attribute='fullname' operator='like' value='%{$search}%' />
                    </filter>
                    <link-entity name='subject' from='subjectid' to='subjectid' alias='subject'>
                      <attribute name='title' />
                    </link-entity>
                    <link-entity name='stringmap' from='attributevalue' to='casetypecode' alias='type'>
                      <attribute name='value' />
                    <filter>
                      <condition attribute='objecttypecode' operator='eq' value='112' />
                      <condition attribute='attributename' operator='eq' value='casetypecode' />
                    </filter>
                    </link-entity>
                    <link-entity name='stringmap' from='attributevalue' to='caseorigincode' alias='origin'>
                      <attribute name='value' />
                    <filter>
                      <condition attribute='objecttypecode' operator='eq' value='112' />
                      <condition attribute='attributename' operator='eq' value='caseorigincode' />
                    </filter>
                    </link-entity>
                    <link-entity name='tz_tecnologia' from='tz_tecnologiaid' to='tz_tecnologia' link-type='outer' alias='technology'>
                      <attribute name='tz_name' />
                    </link-entity>
                    <link-entity name='stringmap' from='attributevalue' to='tz_tipo_atendimento' link-type='outer' alias='servicetype'>
                    <attribute name='value' />
                    <filter>
                      <condition attribute='objecttypecode' operator='eq' value='112' />
                      <condition attribute='attributename' operator='eq' value='tz_tipo_atendimento' />
                    </filter>
                    </link-entity>
                    <link-entity name='stringmap' from='attributevalue' to='statuscode' alias='cause'>
                      <attribute name='value' />
                      <attribute name='attributevalue' />
                    <filter>
                      <condition attribute='objecttypecode' operator='eq' value='112' />
                      <condition attribute='attributename' operator='eq' value='statuscode' />
                    </filter>
                    </link-entity>
                    <link-entity name='systemuser' from='systemuserid' to='createdby' alias='createdby'>
                      <attribute name='fullname' />
                    </link-entity>
                    <link-entity name='systemuser' from='systemuserid' to='modifiedby' alias='modifiedby'>
                      <attribute name='fullname' />
                    </link-entity>
                    <order attribute='createdon' descending='true' />
                  </entity>
                </fetch>
                ";
        
                // Requisição ao CRM para obter os dados das Ocorrências do Cliente por
                // Status, filtrando o termo pesquisado pelo Usuário.
                $dataResult = $this->sac->buscar("{$entity}s", http_build_query(['fetchXml' => $dataXml]));
        
                // Inicia o objeto de dados como uma lista vazia, para caso não haja
                // nenhum retorno do CRM
                $data = [];
        
                // Verifica se existem dados retornados da requisição de Ocorrências ao
                // CRM
                if (count($dataResult['value']) >= 1) {
                    // Trata o retorno dos dados de Ocorrências
                    $data = array_map(function ($element) {
                        $incident = [];
        
                        // Verifica se os campos existem na resposta da requisição e
                        // monta o objeto de Ocorrência com os valores
        
                        // O termo "_x002e_" é o caractere Unicode para ponto "."
                        // Devido à uma limitação da API do CRM de versão abaixo da 9
                        // o retorno sempre usa o caractere Unicode ao invés do UTF-8
                        // para campos externos à tabela consultada
                        $incident['ticketnumber']   = isset($element['ticketnumber'])               ? strval($element['ticketnumber'])               : '--';
                        $incident['subject']        = isset($element['subject_x002e_title'])        ? strval($element['subject_x002e_title'])        : '--';
                        $incident['type']           = isset($element['type_x002e_value'])           ? strval($element['type_x002e_value'])           : '--';
                        $incident['origin']         = isset($element['origin_x002e_value'])         ? strval($element['origin_x002e_value'])         : '--';
                        $incident['technology']     = isset($element['technology_x002e_tz_name'])   ? strval($element['technology_x002e_tz_name'])   : '--';
                        $incident['servicetype']    = isset($element['servicetype_x002e_value'])    ? strval($element['servicetype_x002e_value'])    : '--';
                        $incident['actualqueue']    = isset($element['zatix_filaatual'])            ? htmlspecialchars($element['zatix_filaatual'])  : '--';
                        $incident['cause']['text']  = isset($element['cause_x002e_value'])          ? strval($element['cause_x002e_value'])          : '--';
                        $incident['cause']['value'] = isset($element['cause_x002e_attributevalue']) ? strval($element['cause_x002e_attributevalue']) : '--';
                        $incident['description']    = isset($element['description'])                ? strval($element['description'])                : '--';
                        $incident['id']             = isset($element['incidentid'])                 ? strval($element['incidentid'])                 : '--';
        
                        // Verifica e monta o objeto de Criado com os valores de
                        // "Criado por" e "Criado em"
                        $incident['created'] = [
                            'by' => isset($element['tz_usuario_gestor']) ? strval($element['tz_usuario_gestor'])                                   : '--',
                            'on' => isset($element['createdon'])         ? strval($element['createdon@OData.Community.Display.V1.FormattedValue']) : '--',
                        ];
        
                        // Verifica e monta o objeto de Modificado com os valores de
                        // "Modificado por" e "Modificado em"
                        $incident['modified'] = [
                            'by' => isset($element['tz_usuario_gestor_mod']) ? strval($element['tz_usuario_gestor_mod'])                                : '--',
                            'on' => isset($element['modifiedon'])            ? strval($element['modifiedon@OData.Community.Display.V1.FormattedValue']) : '--',
                        ];
        
                        // Verifica se o campo de Descrição já tem dados, se não
                        // usa os dados do campo de Observações
                        if ($incident['description'] == '--') {
                            $incident['description'] = isset($element['zatix_observacoes']) ? strval($element['zatix_observacoes']) : '--';
                        }
        
                        // Os campos de "Criado Por" e "Modificado Por" podem conter
                        // dois campos válidos, isso ocorre porque o CRM funciona de
                        // maneira diferente do Shownet
                        //
                        // As verificações abaixo tratam essa diferença
        
                        // Verificar se o Usuário de Criação já tem dados, se não
                        // usa os dados do campo de Criado Por
                        if ($incident['created']['by'] == '--') {
                            $incident['created']['by'] = isset($element['createdby_x002e_fullname']) ? strval($element['createdby_x002e_fullname']) : '--';
                        }
        
                        // Verificar se o Usuário de Modificação já tem dados, se não
                        // usa os dados do campo de Modificado Por
                        if ($incident['modified']['by'] == '--') {
                            $incident['modified']['by'] = isset($element['modifiedby_x002e_fullname']) ? strval($element['modifiedby_x002e_fullname']) : '--';
                        }
        
                        // Retorono do objeto de Ocorrência
                        return $incident;
                    }, $dataResult['value']);
                }
        
                // Resposta dos dados retornados do CRM decodificados para seguir o
                // padrão de resposta do DataTables
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode([
                    'draw'            => $draw,
                    'recordsTotal'    => $totalResult['value'][0]["{$entity}scount"],
                    'recordsFiltered' => $filteredTotalResult['value'][0]["{$entity}scount"],
                    'data'            => $data,
                    'status'          => $dataResult['code']
                ]);
            }
        }catch(Throwable $th){
            echo json_encode([
                'status'    => 500,
                'error'     => $th
            ]);
        }
        
    }

    /**
     * Consultar os totais de Ocorrências de um Cliente agrupadas por Status
     *
     * @param string $clientid  Id do Cliente
     *  
     * @return void
     */
    public function listar_totais_ocorrencias($clientid)
    {
        // Nome da Tabela de Ocorrências para consulta
        $entity = 'incident';

        // Paramêtros da requisição ao CRM para obter o número total de
        // Ocorrências do cliente agrupadas por Status.
        $xml = "
        <fetch aggregate='true'>
          <entity name='{$entity}'>
            <attribute name='{$entity}id' alias='{$entity}scount' aggregate='count' />
            <attribute name='statecode' alias='{$entity}state' groupby='true' />
            <filter>
              <condition attribute='customerid' operator='eq' value='{$clientid}' />
            </filter>
          </entity>
        </fetch>
        ";

        // Requisição ao CRM para obter o número total de Ocorrências do
        // Cliente agrupadas por Status.
        $result = $this->sac->buscar("{$entity}s", http_build_query(['fetchXml' => $xml]));

        $response = [
            'active' => 0,
            'canceled' => 0,
            'resolved' => 0
        ];

        // Verifica se existem dados retornados da requisição ao CRM
        if (count($result['value']) >= 1) {
            foreach ($result['value'] as $value) {
                switch ($value["{$entity}state"]) {
                    case 0:
                        $response['active'] = $value["{$entity}scount"];
                        break;
                    case 1:
                        $response['resolved'] = $value["{$entity}scount"];
                        break;
                    case 2:
                        $response['canceled'] = $value["{$entity}scount"];
                        break;
                }
            }
        }

        // Resposta dos dados retornados do CRM
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($response);
    }
    
    /**
     * cadastrar_ocorrencias
     *
     * @return void
     */
    public function cadastrar_ocorrencias(){
        $url = 'incidents';
        $isEmpresa = $this->input->post('isEmpresa');
        $id_user = $this->auth->get_login_dados('user');
        $id_user = (int) $id_user;

        if ($isEmpresa === true || $isEmpresa === 'true'){
            $dadosOcorrencia = array(
                'customerid_account@odata.bind' => "/accounts(".$this->input->post('Cliente').")",
                'subjectid@odata.bind' => '/subjects('.$this->input->post('Assunto').')',
                'title' => $this->input->post('nomeAssunto'),
                'casetypecode' => intval($this->input->post('TipoOcorrencia')),
                'caseorigincode' => intval($this->input->post('OrigemOcorrencia')), 
                'tz_usuario_gestor' => $this->auth->get_login_dados('email'),
                'tz_usuario_gestor_mod' => $this->auth->get_login_dados('email'),
                'description' => $this->input->post('Descricao'),
                'tz_tecnologia@odata.bind' => '/tz_tecnologias('.$this->input->post('Tecnologia').')',
            );
        } else {
            $dadosOcorrencia = array(
                'customerid_contact@odata.bind' => "/contacts(".$this->input->post('Cliente').")",
                'subjectid@odata.bind' => '/subjects('.$this->input->post('Assunto').')',
                'title' => $this->input->post('nomeAssunto'),
                'casetypecode' => intval($this->input->post('TipoOcorrencia')),
                'caseorigincode' => intval($this->input->post('OrigemOcorrencia')), 
                'description' => $this->input->post('Descricao'),
                'tz_tecnologia@odata.bind' => '/tz_tecnologias('.$this->input->post('Tecnologia').')',
            );
        }

        $response = $this->sac->post($url, $dadosOcorrencia);
        
        // Se a ocorrência for cadastrada com sucesso, inserir no banco o usuário
        // do shownet que fez o cadastro
        if($response->code == 201 && isset($response->data->incidentid)){
            $this->painelOmnilink->salvar_cadastro_ocorrencia($response->data->incidentid);
            $this->log_shownet->gravar_log($id_user, 'Cadastrou uma ocorrência no CRM', $response->data->incidentid, 'criar', '', $dadosOcorrencia);
        }
        
        exit(json_encode($response));

    }
    
    /**
     * editar_ocorrencias
     *
     * @return void
     */
    public function editar_ocorrencias(){
        $idIncident = $this->input->post('Id');

        $dadosOcorrencia = array(
            'casetypecode' => intval($this->input->post('TipoOcorrencia')),
            'caseorigincode' => intval($this->input->post('OrigemOcorrencia')),
            'tz_usuario_gestor_mod' => $this->auth->get_login_dados('email'), 
            'description' => $this->input->post('Descricao'),
            'tz_tecnologia@odata.bind' => '/tz_tecnologias('.$this->input->post('Tecnologia').')',
        );

        //a fila só será alterada se o assunto não for alterado
        if($this->input->post('Fila') == null){
            $dadosOcorrencia['subjectid@odata.bind'] = '/subjects('.$this->input->post('Assunto').')';
        }else{
            $dadosOcorrencia['zatix_filaatual'] = $this->input->post('NomeFila');
            $dadosOcorrencia['zatix_filaatendimentoid@odata.bind'] = '/queues('. $this->input->post('Fila') . ')';
                 
        }

        $response = $this->sac->patch('incidents', $idIncident, $dadosOcorrencia);

        if($response->code == 200) {
            $this->painelOmnilink->atualizar_cadastro_ocorrencia($idIncident);
        }
        exit(json_encode($response));
    }
    
    /**
     * listar_tecnologias
     *
     * @return void
     */
    public function listar_tecnologias(){
        $url = 'tz_tecnologias';
        $api_request_parameters = array('$select' => 'tz_name,tz_codigo_erp,tz_tecnologiaid');

        $tecnologias = $this->sac->get($url, $api_request_parameters);

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($tecnologias);
        exit();
    }
        
    /**
     * alterarStatus
     *
     * @return void
     */
    public function alterarStatus(){
        $idIncident = $this->input->post('idIncident');
        $statusCode = $this->input->post('razao_status');

        $novosStatus = array(
            'statuscode' => $statusCode
        );

        $response = $this->sac->patch('incidents', $idIncident, $novosStatus);

        exit(json_encode($response));

    }

    public function carregarOcorrencia($idocorrencia){
        $params = array(
            '$select' => 'ticketnumber,_subjectid_value,casetypecode,caseorigincode,description,incidentid,_tz_tecnologia_value,statecode,statuscode'
        );

        $response = $this->sac->buscar("incidents({$idocorrencia})", http_build_query($params));

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($response);
        exit();
    }

    private function mask($mask,$str){
        $str = str_replace(" ","",$str);
        for($i=0;$i<strlen($str);$i++){
            $mask[strpos($mask,"#")] = $str[$i];
        }
        return $mask;
    }

    private function validar_cnpj($cnpj) {
        if (strlen($cnpj) != 14) return false;
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;	
    
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
    
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) return false;
    
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
    
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    private function validar_cpf($cpf) {
        if (strlen($cpf) != 11) return false;
        if (preg_match('/(\d)\1{10}/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    private function validar_documento($documento, $tipo_cliente) {
        return $tipo_cliente == 'pj' ? $this->validar_cnpj($documento) : $this->validar_cpf($documento);
    }

    /**
     * Faz a requisição da lista de contratos (originalmente feito para preencher o select2 da busca específica)
     */
    public function listarContratosSerial(){
         //pega o parametro passado
         $numeroSerie = $this->input->get('q');
         $buscaPorContratosAtivos = $this->input->get('buscaPorContratosAtivos');

        //Verifica se o checkbox de busca por contratos ativos está marcado
         $filtroAtivo = ($buscaPorContratosAtivos === 'true') ? '<filter><condition attribute="tz_status_item_contrato" operator="eq" value="1" /></filter>' : "";
         
        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
        <entity name="tz_item_contrato_venda">
          <attribute name="tz_name" />
          <attribute name="tz_cliente_pjid" alias="tz_cliente_pjid" />
          <attribute name="tz_cliente_pfid" />
          <attribute name="tz_numero_serie_modulo_principal" />
          <attribute name="tz_status_item_contrato" />
          '.$filtroAtivo.'
          <filter>
            <condition attribute="tz_numero_serie_modulo_principal" operator="eq" value="'.$numeroSerie.'" />
          </filter>
        </entity>
      </fetch>';
         
         
         $resposta = [
             'results' => [],
             'pagination' => [
                 'more' => false,
             ]
         ];

        $requisicao = $this->sac->buscar('tz_item_contrato_vendas', http_build_query(['fetchXml' => $xml]));
         
        if($requisicao['code'] === 200){
            $values = $requisicao['value'];
            foreach ($values as $key => $value) {
                $resposta['results'][] = array(
                    'id' => $value['tz_name'],
                    'text' => $value['_tz_cliente_pjid_value@OData.Community.Display.V1.FormattedValue'] ? $value['tz_name'] .' - '. $value['tz_numero_serie_modulo_principal']. " (" . $value['_tz_cliente_pjid_value@OData.Community.Display.V1.FormattedValue'] . ' - '. $value['tz_status_item_contrato@OData.Community.Display.V1.FormattedValue'].")" : $value['tz_name'] .' - '. $value['tz_numero_serie_modulo_principal']. " (" .$value['_tz_cliente_pfid_value@OData.Community.Display.V1.FormattedValue'] .  ' - ' . $value['tz_status_item_contrato@OData.Community.Display.V1.FormattedValue'] . ")",
                );
            }
        }

        echo json_encode($resposta);
    }
    /**
     * Faz a requisição da lista de contratos de um cliente ao Banco de dados do CRM.
     */
    public function ajax_listar_contratos()
    {
        $requisicao = $this->input->get();

        $tipo      = isset($requisicao['tipo'])      ? $requisicao['tipo']               : null;
        $documento = isset($requisicao['documento']) ? $requisicao['documento']          : null;
        $status    = isset($requisicao['status'])    ? $requisicao['status']             : 0;
        $inicio    = isset($requisicao['start'])     ? $requisicao['start']              : 1;
        $desenhar  = isset($requisicao['draw'])      ? $requisicao['draw']               : 0;
        $tamanho   = isset($requisicao['length'])    ? $requisicao['length']             : 10;

        $entidadeBusca  = $tipo == 'pj' ? 'account'         : 'contact';
        $atributoBusca  = $tipo == 'pj' ? 'tz_cliente_pjid' : 'tz_cliente_pfid';
        $atributoFiltro = $tipo == 'pj' ? 'zatix_cnpj'      : 'zatix_cpf';
        $pagina         = $inicio > 1   ? $inicio / 10 + 1  : 1;

        if($status == 0){
            $filtroStatus = "";
        } else if ($status == 1) {
            $filtroStatus = "<condition attribute='tz_status_item_contrato' operator='in'>
                                <value>1</value>
                                <value>5</value>
                            </condition>";
        } else {
            $filtroStatus = "<condition attribute='tz_status_item_contrato' operator='eq' value='$status' />";
        }
        

        $pesquisa = '';
        if (isset($requisicao['search']) && $requisicao['search']['value'] != '') {
            $termo = $requisicao['search']['value'];

            $pesquisaData = '';
            if ($termo >= 1900 && $termo <= 2100) {
                $pesquisaData = "<condition attribute='tz_data_ativacao' operator='in-fiscal-year' value='$termo' />";
            }

            $pesquisa = "
            <filter type='or'>
                <condition attribute='tz_name' operator='like' value='%{$termo}%' />
                <condition attribute='tz_numero_serie_modulo_principal' operator='like' value='%{$termo}%' />
                <condition attribute='tz_numero_af' operator='like' value='%{$termo}%' />
                <condition attribute='name' entityname='equipamento' operator='like' value='%{$termo}%' />
                <condition attribute='tz_placa' entityname='tz_veiculo' operator='like' value='%{$termo}%' />
                <condition attribute='name' entityname='plano' operator='like' value='%{$termo}%' />
                $pesquisaData
            </filter>
            ";
        }

        $xml = "
        <fetch page='$pagina' count='$tamanho'>
            <entity name='tz_item_contrato_venda'>
                <attribute name='tz_name' />
                <attribute name='tz_numero_serie_modulo_principal' />
                <attribute name='tz_item_contrato_vendaid' />
                <attribute name='tz_numero_af' />
                <attribute name='tz_status_item_contrato' />
                <attribute name='tz_data_ativacao' />
                <attribute name='tz_tipo_contrato' />
                <order attribute='createdon' descending='true' />
                <link-entity name='product' to='tz_rastreadorid' alias='equipamento' link-type='outer'>
                    <attribute name='name' />
                </link-entity>
                <link-entity name='tz_veiculo' to='tz_veiculoid' link-type='outer'>
                    <attribute name='tz_placa' />
                </link-entity>
                <link-entity name='product' to='tz_plano_linkerid' alias='plano' link-type='outer'>
                    <attribute name='name' />
                </link-entity>
                <link-entity name='$entidadeBusca' to='$atributoBusca' link-type='outer' />
                <filter>
                    <condition attribute='$atributoFiltro' entityname='$entidadeBusca' operator='eq' value='$documento' />
                    $filtroStatus
                </filter>
                $pesquisa
            </entity>
        </fetch>
        ";


        $resposta['xml'] = $xml;

        $parametros = ['fetchXml' => urlencode($xml)];
        $resultado = json_decode(json_encode($this->sac->get('tz_item_contrato_vendas', $parametros)), true);

        $resposta['resultado'] = $resultado;

        $resposta['data'] = isset($resultado['data']) && isset($resultado['data']['value']) ?
        array_map(function ($item) {
            return [
                'codigo'         => isset($item['tz_name'])                          ? $item['tz_name']                                                           : '-',
                'placa'          => isset($item['tz_veiculo2_x002e_tz_placa'])       ? $item['tz_veiculo2_x002e_tz_placa']                                        : '-',
                'serial'         => isset($item['tz_numero_serie_modulo_principal']) ? $item['tz_numero_serie_modulo_principal']                                  : '-',
                'equipamento'    => isset($item['equipamento_x002e_name'])           ? $item['equipamento_x002e_name']                                            : '-',
                'plano'          => isset($item['plano_x002e_name'])                 ? $item['plano_x002e_name']                                                  : '-',
                'modalidade'     => isset($item['tz_tipo_contrato'])                 ? $item['tz_tipo_contrato@OData.Community.Display.V1.FormattedValue']        : '-',
                'codVenda'       => isset($item['tz_numero_af'])                     ? $item['tz_numero_af']                                                      : '-',
                'dataAtivacao'   => isset($item['tz_data_ativacao'])                 ? $item['tz_data_ativacao@OData.Community.Display.V1.FormattedValue']        : '-',
                'status'         => isset($item['tz_status_item_contrato'])          ? $item['tz_status_item_contrato']                                           : '-',
                'statusContrato' => isset($item['tz_status_item_contrato'])          ? $item['tz_status_item_contrato@OData.Community.Display.V1.FormattedValue'] : '-',
                'id'             => isset($item['tz_item_contrato_vendaid'])         ? $item['tz_item_contrato_vendaid']                                          : '-'
            ];
        }, $resultado['data']['value']) :
        [];

        $xml = "
        <fetch aggregate='true'>
            <entity name='tz_item_contrato_venda'>
                <attribute name='tz_item_contrato_vendaid' alias='count' aggregate='count' />
                <link-entity name='product' to='tz_rastreadorid' alias='equipamento' link-type='outer' />
                <link-entity name='tz_veiculo' to='tz_veiculoid' link-type='outer' />
                <link-entity name='product' to='tz_plano_linkerid' alias='plano' link-type='outer' />
                <link-entity name='$entidadeBusca' to='$atributoBusca' link-type='outer' />
                <filter>
                    <condition attribute='$atributoFiltro' entityname='$entidadeBusca' operator='eq' value='$documento' />
                    $filtroStatus
                </filter>
                $pesquisa
            </entity>
        </fetch>
        ";

        $parametros = ['fetchXml' => urlencode($xml)];
        $resultado = json_decode(json_encode($this->sac->get('tz_item_contrato_vendas', $parametros)), true);

        $resposta['recordsTotal'] = isset($resultado['data']['value'])    ? $resultado['data']['value'][0]['count'] : 0;
        $resposta['recordsFiltered'] = isset($resultado['data']['value']) ? $resultado['data']['value'][0]['count'] : 0;
        $resposta['draw'] = $desenhar;
        $resposta['status'] = $resultado['code'];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resposta);
        exit();
    }

    public function ajax_listar_quantidade_contratos() {
        $requisicao = $this->input->get();

        $tipo      = isset($requisicao['tipo'])      ? $requisicao['tipo']      : null;
        $documento = isset($requisicao['documento']) ? $requisicao['documento'] : null;

        $entidadeBusca  = $tipo == 'pj' ? 'account'         : 'contact';
        $atributoBusca  = $tipo == 'pj' ? 'tz_cliente_pjid' : 'tz_cliente_pfid';
        $atributoFiltro = $tipo == 'pj' ? 'zatix_cnpj'      : 'zatix_cpf';

        $xml = "
        <fetch aggregate='true'>
            <entity name='tz_item_contrato_venda'>
                <attribute name='tz_item_contrato_vendaid' alias='count' aggregate='count' />
                <attribute name='tz_status_item_contrato' alias='status' groupby='true' />
                <link-entity name='$entidadeBusca' to='$atributoBusca' link-type='outer' />
                <filter>
                    <condition attribute='$atributoFiltro' entityname='$entidadeBusca' operator='eq' value='$documento' />
                </filter>
            </entity>
        </fetch>
        ";

        $parametros = ['fetchXml' => urlencode($xml)];
        $resultado = json_decode(json_encode($this->sac->get('tz_item_contrato_vendas', $parametros)), true);

        $ativos = $aguardando = $suspensos = $cancelados = $todos = 0;

        foreach ($resultado['data']['value'] as $item) {
            switch ($item['status']) {
                case 1:
                    $ativos += $item['count'];
                    break;
                case 2:
                    $aguardando += $item['count'];
                    break;
                case 3:
                    $cancelados += $item['count'];
                    break;
                case 4:
                    $suspensos += $item['count'];
                    break;
                case 5:
                    $ativos += $item['count'];
                    break;
            }
        }

        $todos = $ativos + $aguardando + $cancelados + $suspensos;

        $resposta['ativos']     = $ativos;
        $resposta['aguardando'] = $aguardando;
        $resposta['cancelados'] = $cancelados;
        $resposta['suspensos']  = $suspensos;
        $resposta['todos'] = $todos;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resposta);
        exit();
    }

    public function ajax_remover_item_contrato($id_item_contrato){
        try {
            if(isset($id_item_contrato)){
                $response = $this->sac->delete('tz_item_contrato_vendas', $id_item_contrato);

                if($response->code == 204){
                    echo json_encode(array(
                        'status' => true,
                        'message' => "Item de contrato removido com sucesso!"
                    ));
                }else{
                    $erro = null;
                    if(isset($response->data) && isset($response->data->error) && isset($response->data->error->message)){
                        $erro = "Erro ao remover item de contrato: ".$response->data->error->message;
                    }else{
                        $erro = "Erro ao remover item de contrato!";
                    }
                    echo json_encode(array(
                        'status' => false,
                        'erro' => $erro
                    ));
                }
            }
        } catch (\Throwable $th) {
            
            echo json_encode(array(
                'status' => false,
                'erro' => "Erro ao remover item de contrato! ". $th->getMessage()
            ));
        }
        
    }

    /**
     * Faz a requisição ao Banco do CRM dos detalhes de um contrato.
     * @param String $idContrato
     * @return Array Contrato.
     */
    public function ajax_contrato($idContrato) {
        if (!isset($idContrato)) {
            echo json_encode([
                'status' => -1,
                'mensagem' => 'Contrato inválido!',
            ]);
            return;
        }

        $xml = '
        <fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
            <entity name="tz_item_contrato_venda">
                <attribute name="tz_name" alias="codigo"/>
                <attribute name="tz_status_item_contrato" alias="status"/>
                <attribute name="tz_data_ativacao" alias="dataAtivacao" />
                <attribute name="tz_numero_serie_modulo_principal" alias="serialEquipamento" />
                <attribute name="tz_data_termino" alias="dataTermino"/>
                <filter type="and">
                    <condition attribute="tz_item_contrato_vendaid" operator="eq" value="{0}"/>
                </filter>
                <link-entity name="tz_tecnologia" from="tz_tecnologiaid" to="tz_tecnologiaid" visible="false" link-type="outer" alias="tecnologia">
                    <attribute name="tz_name" alias="tecnologia"/>
                </link-entity>
                <link-entity name="tz_veiculo" from="tz_veiculoid" to="tz_veiculoid" visible="false" link-type="outer" alias="veiculo">
                    <attribute name="tz_placa" alias="placa"/>
                </link-entity>
                <link-entity name="product" from="productid" to="tz_plano_linkerid" visible="false" link-type="outer" alias="plano">
                    <attribute name="name" alias="plano"/>
                </link-entity>
            </entity>
        </fetch>';

        $xml = str_replace('{0}', $idContrato, $xml);
        $xml = urlencode($xml);

        $api_request_parameters = array('fetchXml' => $xml);
        $contrato = $this->sac->get("tz_item_contrato_vendas", $api_request_parameters);

        if ($contrato->code == 200) echo json_encode([
            'status' => 1,
            'contratos' => $contrato->data->value
        ]);
        else echo json_encode([
            'status' => -1,
            'mensagem' => 'Falha na requisição',
        ]);
    }

    public function ajax_itens_contrato($id_contrato) {
        if (!isset($id_contrato) || !strlen($id_contrato)) {
            echo json_encode([
                'status' => -1,
                'mensagem' => 'Id do contrato vazio',
            ]);
            return;
        }
        
        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
                    <entity name="tz_produto_servico_contratado">
                        <attribute name="tz_produto_servico_contratadoid" alias="id"/>
                        <attribute name="tz_name" alias="codigo"/>
                        <attribute name="createdon" alias="dataCriacao"/>
                        <attribute name="tz_valor_contratado" alias="valor"/>
                        <attribute name="tz_produtoid" />
                        <attribute name="tz_quantidade" alias="quantidade"/>
                        <attribute name="tz_grupo_receitaid" />
                        <attribute name="tz_data_fim_carencia" alias="dataFimCarencia"/>
                        <attribute name="tz_data_termino" alias="dataTermino"/>
                        <attribute name="tz_data_inicio" alias="dataInicio"/>
                        <attribute name="tz_classificacao_produtoid" />
                        <order attribute="tz_name" descending="false" />
                        <filter type="and">
                        <condition attribute="tz_codigo_item_contratoid" operator="eq" uiname="G50056-001" uitype="tz_item_contrato_venda" value="{0}" />
                        </filter>
                        <link-entity name="product" from="productid" to="tz_produtoid" visible="false" link-type="outer" alias="produto">
                        <attribute name="name" alias="produto"/>
                        <attribute name="productnumber" alias="numeroProduto"/>
                        </link-entity>
                        <link-entity name="tz_grupo_receita" from="tz_grupo_receitaid" to="tz_grupo_receitaid" visible="false" link-type="outer" alias="gr">
                        <attribute name="tz_name" alias="grupoReceita"/>
                        <attribute name="tz_codigo_erp" alias="codigoErpReceita"/>
                        </link-entity>
                        <link-entity name="tz_classificacao_produto" from="tz_classificacao_produtoid" to="tz_classificacao_produtoid" visible="false" link-type="outer" alias="cp">
                        <attribute name="tz_name" alias="classificacaoProduto"/>
                        <attribute name="tz_codigo_erp" alias="codigoErpProduto"/>
                        </link-entity>
                    </entity>
                </fetch>';

        $xml = str_replace('{0}', $id_contrato, $xml);
        $xml = urlencode($xml);

        $api_request_parameters = array('fetchXml'=> $xml);
        $itens = $this->sac->get("tz_produto_servico_contratados", $api_request_parameters);
        
        if ($itens->code == 200) echo json_encode([
            'status' => 1,
            'itens' => $itens->data->value
        ]);
        else echo json_encode([
            'status' => -1,
            'mensagem' => 'Falha na requisição',
        ]);
    }

    
    public function ajax_info_ocorrencia($ticket) {
        if (!isset($ticket) || !strlen($ticket)) {
            echo json_encode([
                'status' => -1,
                'mensagem' => 'Ticket vazio',
            ]);
            return;
        }

        $api_request_parameters = array(
            '$select' => 'ticketnumber,title,_subjectid_value,casetypecode,caseorigincode,_tz_tecnologia_value,statecode,statuscode,zatix_observacoes,description,modifiedon,createdon,tz_status_aprovacao,zatix_placasveculos,prioritycode,_customerid_value,servicestage,resolvebyslastatus,zatix_filaatual,severitycode,incidentstagecode,tz_enviar_guia,customercontacted,tz_sucessotratamentoevento,tz_agendada,tz_quantidade_contratos_retidos,tz_usuario_gestor_mod',
            '$filter'=> "contains(ticketnumber, '{$ticket}')",
            '$expand' => 'owninguser($select=yomifullname),customerid_account($select=zatix_cnpj,zatix_nomefantasia),customerid_contact($select=zatix_cpf, fullname)'
        );
        $itens = $this->sac->get("incidents", $api_request_parameters);

        if ($itens->code == 200) {
            $resposta = $itens->data->value[0];
            $info = null;
            if ($resposta) {
                // Verifica se existe registro da criação ou alteração da ocorrência no Shownet
                $auditoria_incident = $this->getAuditoriaIncident($resposta->incidentid);

                // Busca anotações vinculadas a ocorrência
                $anotacoes = $this->listarAnotacoes($resposta->incidentid, true);

                // Monta retorna da requisição
                $info = [
                    'clienteContatado'              => $resposta->customercontacted,
                    'codigoAssunto'                 => $resposta->_subjectid_value,
                    'codigoEstagioOcorrencia'       => $resposta->incidentstagecode,
                    'codigoEstagioServico'          => $resposta->servicestage,
                    'codigoOrigemOcorrencia'        => $resposta->caseorigincode,
                    'codigoPrioridade'              => $resposta->prioritycode,
                    'codigoStatusOcorrencia'        => $resposta->statecode,
                    'codigoRazaoStatusOcorrencia'   => $resposta->statuscode,
                    'codigoSeveridade'              => $resposta->severitycode,
                    'codigoTecnologia'              => $resposta->_tz_tecnologia_value,
                    'codigoTipoOcorrencia'          => $resposta->casetypecode,
                    'dataCriacao'                   => $resposta->createdon,
                    'dataModificacao'               => $resposta->modifiedon,
                    'filaAtual'                     => $resposta->zatix_filaatual,
                    'id'                            => $resposta->incidentid,
                    'numeroTicket'                  => $resposta->ticketnumber,
                    'observacoes'                   => $resposta->description,
                    'placas'                        => $resposta->zatix_placasveculos,
                    'quantidadeContratosRetidos'    => $resposta->tz_quantidade_contratos_retidos,
                    'titulo'                        => $resposta->title,
                    'statusAprovacao'               => $resposta->tz_status_aprovacao,
                    'sucessoTratamentoEvento'       => $resposta->tz_sucessotratamentoevento,
                    'criadoPor'                     => isset($auditoria_incident->usuario_cadastro) ? $auditoria_incident->usuario_cadastro : $resposta->owninguser->yomifullname,
                    'modificadoPor'                 => isset($resposta->tz_usuario_gestor_mod) ? $resposta->tz_usuario_gestor_mod : $resposta->owninguser->yomifullname,
                    'anotacoes'                     => $anotacoes,
                    'detalhamento'                  => $resposta->zatix_observacoes
                ];

                if (isset($resposta->customerid_account) && !empty($resposta->customerid_account)) {
                    $info['cnpj'] = $resposta->customerid_account->zatix_cnpj;
                    $info['nomeFantasia'] = $resposta->customerid_account->zatix_nomefantasia;
                }
                
                if (isset($resposta->customerid_contact) && !empty($resposta->customerid_contact)) {
                    $info['cpf'] = $resposta->customerid_contact->zatix_cpf;
                    $info['nomeFantasia'] = $resposta->customerid_contact->fullname;
                }
            }

            echo json_encode([
                'status' => 1,
                'info' => $info
            ]);
        }
        else echo json_encode([
            'status' => -1,
            'mensagem' => 'Falha na requisição',
            'detalhes' => $itens
        ]);
    }

    /**
     * Busca atividades 
     */
    public function ajax_buscar_atividades_servico($clientEntity,$id_cliente,$idAtividadeServico = null){
        try {
            $dados = $this->input->post();
            $id = $dados['Id_item_contrato_venda'];
        
            //verifica se o parametro de id é instanciado, esse parametro é passado na busca específica da NA
            if($idAtividadeServico === null){
                // Pessoa física
                if($clientEntity == 'contacts'){
                    $filtro = '<filter>
                                <condition attribute="tz_cliente_pfid" operator="eq" value="'.$id_cliente.'" />
                            </filter>';
                }else{ // Pessoa Jurídica
                    $filtro = '<filter>
                                <condition attribute="tz_cliente_pjid" operator="eq" value="'.$id_cliente.'" />
                            </filter>';
                }
            }else{
                $select = '
                    <attribute name="tz_nome_solicitante" />
                    <attribute name="tz_telefone_solicitante" />
                    <attribute name="tz_tipo_servico" />
                    <attribute name="tz_data_minima_agendamento" />
                    <attribute name="tz_local_atendimento" />
                    <attribute name="tz_endereco_numero" />
                    <attribute name="tz_endereco_rua" />
                    <attribute name="tz_endereco_estadoid" />
                    <attribute name="tz_endereco_bairro" />
                    <attribute name="tz_endereco_complemento" />
                    <attribute name="tz_endereco_cidadeid" />
                ';

                $expand = '';
                $clientEntity == 'contacts' ? $expand =  '<link-entity name="contact" from="contactid" to="tz_cliente_pfid" alias="pf" link-type="outer">
                                                            <attribute name="fullname" />
                                                            <attribute name="zatix_cpf" />
                                                        </link-entity>' : $expand =
                                                        '<link-entity name="account" from="accountid" to="tz_cliente_pjid" alias="pj" link-type="outer">
                                                            <attribute name="name" />
                                                            <attribute name="zatix_cnpj" />
                                                        </link-entity>';
                //caso haja o parametro, colocar o expand para buscar os campos que serão necessários na busca específica.

                $filtro = '<filter>
                                <condition attribute="activityid" operator="eq" value="'.$idAtividadeServico.'" />
                            </filter>';
            }
            $xml = '<fetch>
                    <entity name="serviceappointment">
                        <attribute name="tz_houve_troca_modulo" />
                        <attribute name="tz_id_agendamento" />
                        <attribute name="tz_numero_serie_contrato" />
                        <attribute name="tz_taxa_visita" />
                        <attribute name="tz_numero_serie_antena_contrato" />
                        <attribute name="statuscode" />
                        <attribute name="tz_item_contratoid" />
                        <attribute name="tz_houve_troca_antena" />
                        <attribute name="tz_num_serie_rastreador_instalado" />
                        <attribute name="tz_distancia_bonificada" />
                        <attribute name="tz_modelo_tipo_ativacao" />
                        <attribute name="tz_cliente_pfid" />
                        <attribute name="tz_distancia_considerada" />
                        <attribute name="regardingobjectid" />
                        <attribute name="subject" />
                        <attribute name="tz_baseinstaladarastreador" />
                        <attribute name="tz_valor_km_considerado_cliente" />
                        <attribute name="statecode" />
                        <attribute name="createdby" />
                        <attribute name="tz_baseinstaladaantena" />
                        <attribute name="tz_distancia_total" />
                        <attribute name="activityid" />
                        <attribute name="scheduledend" />
                        <attribute name="scheduledstart" />
                        <attribute name="tz_valor_total_deslocamento" />
                        <attribute name="actualend" />
                        '.$select.'
                        <link-entity name="service" from="serviceid" to="serviceid" link-type="outer" alias="service">
                            <attribute name="name" />
                            <attribute name="serviceid" />
                        </link-entity>
                        <link-entity name="site" from="siteid" to="siteid" link-type="outer" alias="siteid">
                            <attribute name="siteid" />
                            <attribute name="name" />
                        </link-entity>
                        <link-entity name="tz_complemento_servico" from="tz_complemento_servicoid" to="tz_complemento_servicoid" link-type="outer" alias="complemento">
                            <attribute name="tz_name" />
                        </link-entity>
                        <link-entity name="tz_ordem_servico" from="tz_atividade_servicoid" to="activityid" link-type="outer" alias="os">
                            <attribute name="tz_numero_os" />
                        </link-entity>
                        '.$expand.$filtro.'
                        <link-entity name="tz_base_instalada_cliente" from="tz_base_instalada_clienteid" to="tz_baseinstaladaantena" link-type="outer" alias="antena">
                            <attribute name="tz_numero_serie" />
                        </link-entity>
                        </entity>
                    </fetch>';

            // $atividadesServico = $this->sac->get($entity, $api_request_parameters);
            $atividadesServico = $this->sac->buscar('serviceappointments', http_build_query(['fetchXml' => $xml]));

            if($atividadesServico["code"] == 200){
                $resposta = array();
                $values = $atividadesServico['value'];

                for($i = 0; $i < count($values); $i++){
                    $valor = $values[$i];
                    
                    // Descrição do Status
                    if($valor['statecode'] == 0) $statusDescription = 'Aberto';
                    elseif($valor['statecode'] == 1) $statusDescription = 'Fechado';
                    elseif($valor['statecode'] == 2) $statusDescription = 'Cancelado';
                    else $statusDescription = 'Agendado';
                    
                    $resposta[] = array(
                        "Id" => $valor['activityid'],
                        'Code' => isset($valor['tz_id_agendamento']) ? $valor['tz_id_agendamento'] : '',
                        'Status' => $valor['statecode'],
                        'distanciaBonificada' => $valor['tz_distancia_bonificada'],
                        'distanciaConsiderada' => $valor['tz_distancia_considerada'],
                        'distanciaTotal' => $valor['tz_distancia_total'],
                        'StatusCode' => $valor['statuscode'],
                        'valorStatusCode' => json_decode(json_encode($valor),true)['statuscode@OData.Community.Display.V1.FormattedValue'],
                        'statusDescription' => $statusDescription,
                        'scheduledstart' => isset($valor['scheduledstart']) ? date_format(date_create($valor['scheduledstart'])->setTimezone($this->timeZone), 'd/m/Y H:i:s') : '',
                        'scheduledend' => isset($valor['scheduledend']) ? date_format(date_create($valor['scheduledend'])->setTimezone($this->timeZone), 'd/m/Y H:i:s') : '',
                        'actualend' => isset($valor['actualend']) ? date_format(date_create($valor['actualend'])->setTimezone($this->timeZone), 'd/m/Y H:i:s') : '',
                        'subject' => isset($valor['subject']) ? $valor['subject'] : '',
                        'contract' => array(
                            'Id' => $valor['_tz_item_contratoid_value'],
                            'Code' => isset($valor['_tz_item_contratoid_value']) ? $valor['_tz_item_contratoid_value'] : '',
                            'Name' => $valor['_tz_item_contratoid_value@OData.Community.Display.V1.FormattedValue']
                        ),
                        'serviceId' => isset($valor['service_x002e_serviceid']) ? $valor['service_x002e_serviceid'] : '',
                        'serviceName' => isset($valor['service_x002e_name']) ? $valor['service_x002e_name'] : '',
                        'serviceNameComplement' => isset($valor['complemento_x002e_tz_name']) ? $valor['complemento_x002e_tz_name'] : '',
                        'provider' => isset($valor['siteid_x002e_siteid']) ? $valor['siteid_x002e_name'] : '',
                        'trackerSerialNumber' => isset($valor['tz_numero_serie_contrato']) ? $valor['tz_numero_serie_contrato'] : '',
                        'satelliteSerialNumber' => isset($valor['tz_numero_serie_antena_contrato']) ? $valor['tz_numero_serie_antena_contrato'] : '',
                        'trackerSerialNumberInstall' => isset($valor['antena_x002e_tz_numero_serie']) ? $valor->tz_baseinstaladaantena_serviceappointment->tz_numero_serie : '',
                        'satelliteSerialNumberInstall' => isset($valor->tz_baseinstaladaantena->tz_numero_serie) ? $valor->tz_baseinstaladaantena->tz_numero_serie : '',
                        'incident' => array(
                            'Id' => $valor['_regardingobjectid_value'],
                            'TicketNumber' => isset($valor['_regardingobjectid_value@OData.Community.Display.V1.FormattedValue']) ? $valor['_regardingobjectid_value@OData.Community.Display.V1.FormattedValue'] : ''
                        ),
                        'houveTrocaAntena' => isset($valor['tz_houve_troca_antena']) ? $valor['tz_houve_troca_antena'] : '',
                        'houveTrocaModulo' => isset($valor['tz_houve_troca_modulo']) ? $valor['tz_houve_troca_modulo'] : '',
                        'taxaVista' => isset($valor['tz_taxa_vista']) ? $valor['tz_taxa_vista'] : '',
                        'numeroSerieRastreadorInstalado' => isset($valor['tz_num_serie_rastreador_instalado']) ? $valor['tz_num_serie_rastreador_instalado'] : '',
                        'tipoAtivacao' => isset($valor['tz_tipo_ativacao']) ? $valor['tz_tipo_ativacao'] : '',
                        'valorKmConsiderado' => isset($valor['tz_valor_km_considerado']) ? $valor['tz_valor_km_considerado'] : '',
                        'valorTotalDeslocamento' => isset($valor['tz_valor_total_deslocamento']) ? $valor['tz_valor_total_deslocamento'] : '',
                        'baseInstaladaRastreador' => isset($valor['_tz_baseinstaladarastreador_value']) ? $valor['_tz_baseinstaladarastreador_value'] : '',
                        'baseInstaladaAntena' => isset($valor['_tz_baseinstaladaantena_value']) ? $valor['_tz_baseinstaladaantena_value'] : '',
                        'numeroOs' => isset($valor['os_x002e_tz_numero_os']) ? $valor['os_x002e_tz_numero_os'] : ''
                    );
                }

                //verifica se é uma busca específica, se for, adicionar o array de cliente
                if( $idAtividadeServico != null){
                    $resposta['cliente'] = array(
                        "nome" => $clientEntity == 'contacts' ? $values[0]['pf_x002e_fullname'] : $values[0]['pj_x002e_name'],
                        "documento" => $clientEntity == 'contacts' ? $values[0]['pf_x002e_zatix_cpf'] : $values[0]['pj_x002e_zatix_cnpj'],
                    );
                    
                    if(isset($valor['siteid_x002e_siteid'])){
                        $prestador = json_decode($this->buscarRecursosNA($valor['siteid_x002e_siteid']));
                        $resposta['recurso'] = $prestador->data[0];
                        $resposta['prestador'] = array(
                            "id" => $valor['siteid_x002e_siteid'],
                            "nome" => $valor['siteid_x002e_name']
                        );
                    }

                    $resposta['nomeSolicitante'] = isset($valor['tz_nome_solicitante']) ? $valor['tz_nome_solicitante'] : '';
                    $resposta['telefoneSolicitante'] = isset($valor['tz_telefone_solicitante']) ? $valor['tz_telefone_solicitante'] : '';
                    $resposta['tipoServico'] = isset($valor['tz_tipo_servico']) ? $valor['tz_tipo_servico'] : '';
                    $resposta['dataMinimaAgendamento'] = isset($valor['tz_data_minima_agendamento']) ? $valor['tz_data_minima_agendamento'] : '';
                    $resposta['localAtendimento'] = isset($valor['tz_local_atendimento']) ? $valor['tz_local_atendimento'] : '';
                    $resposta['complementoEndereco'] = isset($valor['tz_endereco_complemento']) ? $valor['tz_endereco_complemento'] : '';
                         

                    $recursoID = $this->sac->getRecursoNA($idAtividadeServico);
                    $recursoID = $recursoID->data['value'];
                    $recursoID = $recursoID[0];
                    $resposta['recursoId'] = $recursoID['_partyid_value'] ? $recursoID['_partyid_value'] : null;

                    if($resposta['localAtendimento'] === 2){
                        $resposta['endereco'] = array(
                            "estado"    => isset($valor['_tz_endereco_estadoid_value']) ? $valor['_tz_endereco_estadoid_value'] : '',
                            "cidade"    => isset($valor['_tz_endereco_cidadeid_value']) ? $valor['_tz_endereco_cidadeid_value'] : '',
                            "bairro"    => isset($valor['tz_endereco_bairro']) ? $valor['tz_endereco_bairro'] : '',
                            "rua"       => isset($valor['tz_endereco_rua']) ? $valor['tz_endereco_rua'] : '',
                            "numero"    => isset($valor['tz_endereco_numero']) ? $valor['tz_endereco_numero'] : ''
                        );
                    }

                    $anotacoes = json_decode($this->buscar_anotacao($idAtividadeServico));
                    $resposta['anotacoes'] = $anotacoes->data;

                }
                
                echo json_encode(array('code' => $atividadesServico['code'], "data" => $resposta));
            }else{
                echo json_encode(array('code' => $atividadesServico['code'], 'error' => $atividadesServico['value']));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('code' => $atividadesServico['code'], 'error' => $th));
        }
    }

    public function ajax_buscar_item_contrato_atividade_servico($idContrato){
        
        try {
            $entity = "tz_item_contrato_vendas";
            $api_request_parameters = array(
                '$expand'=>'tz_veiculoid($select=tz_placa,tz_chassi,tz_renavam,tz_modelo_tipo_ativacao),tz_plano_linkerid($select=name),tz_rastreadorid($select=name),tz_tipo_veiculoid($select=tz_name),tz_canal_vendaid($select=tz_name),tz_cenario_vendaid($select=tz_name),tz_tecnologiaid($select=tz_name),tz_afid($select=tz_name)',
                '$filter'=>"tz_item_contrato_vendaid eq {$idContrato}",
            );

            $item_contrato = $this->sac->get($entity, $api_request_parameters);

            if($item_contrato->code == 200){
                $item = $item_contrato->data->value[0];
                
                
                if($item->tz_status_item_contrato == 1){
                    $status_item_contrato = 'Ativo';
                }
                elseif($item->tz_status_item_contrato == 2){
                    $status_item_contrato = 'Aguardando Ativação';
                }
                elseif($item->tz_status_item_contrato == 3){
                    $status_item_contrato = 'Cancelado';
                }
                else{
                    $status_item_contrato = 'Suspenso';
                }
                $resposta = array(
                    // IDENTIFICAÇÃO
                    'codigo' => isset($item->tz_name) ? $item->tz_name : '',
                    'numero_af' => isset($item->tz_numero_af) ? $item->tz_numero_af : '',
                    'modelo_tipo_informado_ativacao' => isset($item->tz_modelo_tipo_informado_ativacao) ? $item->tz_modelo_tipo_informado_ativacao : '',
                    'codigo_item_contrato' => isset($item->tz_codigo_item_contrato) ? $item->tz_codigo_item_contrato : '',
                    'status_item_contrato' => $status_item_contrato,
                    // VENDA
                    'integra_contrato' => $item->tz_integra_contrato == false ? "Não" : 'Sim', // venda
                    'reenviar_apolice_graber' => $item->tz_reenviar_apolice_graber == false ? 'Não' : 'Sim', //venda
                    'placa' => isset($item->tz_veiculoid->tz_placa) ? $item->tz_veiculoid->tz_placa : '',
                    'chassi' => isset($item->tz_veiculoid->tz_chassi) ? $item->tz_veiculoid->tz_chassi : '',
                    'renavam' => isset($item->tz_veiculoid->tz_renavam) ? $item->tz_veiculoid->tz_renavam : '',
                    'tipo_veiculo' => isset($item->tz_tipo_veiculoid->tz_name) ? $item->tz_tipo_veiculoid->tz_name : '',
                    'modelo_tipo_ativacao' => isset($item->tz_veiculoid->tz_modelo_tipo_ativacao) ? $item->tz_veiculoid->tz_modelo_tipo_ativacao : '',
                    'valor_licenca_base' => isset($item->tz_veiculoid->tz_valor_licenca_base) ? $item->tz_veiculoid->tz_valor_licenca_base : '',  //venda
                    'numero_serie_modulo_principal' => isset($item->tz_numero_serie_modulo_principal) ? $item->tz_numero_serie_modulo_principal : '',
                    // PRODUTO
                    'plano_linker' => isset($item->tz_plano_linkerid->name) ? $item->tz_plano_linkerid->name : '', // produto
                    'rastreador' => isset($item->tz_rastreadorid->name) ? $item->tz_rastreadorid->name : '', //produto
                    'canal_venda' => isset($item->tz_canal_vendaid->tz_name) ? $item->tz_canal_vendaid->tz_name : '',
                    'cenario_venda' => isset($item->tz_cenario_vendaid->tz_name) ? $item->tz_cenario_vendaid->tz_name : '',
                    'tecnologia' => isset($item->tz_tecnologiaid->tz_name) ? $item->tz_tecnologiaid->tz_name : '',
                    'af' => isset($item->tz_afid->tz_name) ? $item->tz_afid->tz_name : '',
                    'valor_deslocamento_km_base' => isset($item->tz_valor_deslocamento_km_base) ? $item->tz_valor_deslocamento_km_base : '',
                    'taxa_visita_base' => isset($item->tz_taxa_visita_base) ? $item->tz_taxa_visita_base : '',
                    'data_ativacao' => isset($item->tz_data_ativacao) ? date_format(date_create($item->tz_data_ativacao), 'd/m/Y') : '',
                    'data_aniversario_contrato' => isset($item->tz_data_aniversario_contrato) ? date_format(date_create($item->tz_data_aniversario_contrato), 'd/m/Y') : '',
                    'data_vencimento_comodato' => isset($item->tz_data_vencimento_comodato) ? date_format(date_create($item->tz_data_vencimento_comodato), 'd/m/Y') : '',
                    'data_termino_fidelidade' => isset($item->data_termino_fidelidade) ? date_format(date_create($item->tz_tz_data_termino_fidelidade), 'd/m/Y') : '',
                    
                );
                
                echo json_encode(array('code' => $item_contrato->code, "item" => $resposta));

            }else{
                echo json_encode(array('code' => $item_contrato->code, 'error' => $item_contrato->value));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('code' => 500, 'error' => $th));
        }
    }

    public function ajax_remover_cliente(){
        $dados = $this->input->post('account');
        if($dados){
            if(isset($_SESSION['clientes'])){
                foreach ($_SESSION['clientes'] as $key => $cliente){
                    if ($cliente['Id'] == $dados){
                        array_splice($_SESSION['clientes'], $key, 1);
                    }
                }
            }
        }
    }

    /**
     * Função que retorna dados da atividade de serviço cadastrada para edição
     * @param String $idAtividade
     */
    public function ajax_buscar_info_atividade_servico($idAtividade){
        try {
            $atividadeServico = $this->sac->get("serviceappointments($idAtividade)", array());
            
            $response = array();

            if($atividadeServico->code == 200){
                
                $value = $atividadeServico->data;

                $response["inputs"] = array(
                    "na_activityid" => $value->activityid,
                    "na_subject" => $value->subject,
                    "na_tz_nome_solicitante" => $value->tz_nome_solicitante,
                    "na_tz_telefone_solicitante" => $value->tz_telefone_solicitante,
                    "na_tz_tipo_servico" => $value->tz_tipo_servico,
                    "na_tz_agendar_sem_contrato" => $value->tz_agendar_sem_contrato ? 'true' : 'false',
                    "na_tz_local_atendimento" => $value->tz_local_atendimento,
                    "na_tz_envia_email_alteracao" => $value->tz_envia_email_alteracao ? 'true' : 'false',
                    "na_tz_endereco_bairro" => $value->tz_endereco_bairro,
                    "na_tz_endereco_rua" => $value->tz_endereco_rua,
                    "na_tz_endereco_numero" => $value->tz_endereco_numero,
                    "na_tz_endereco_complemento" => $value->tz_endereco_complemento,
                    "na_tz_endereco_pais" => $value->tz_endereco_pais,
                    "na_tz_referencia" => $value->tz_referencia,
                    "na_tz_tcnicodocliente" => $value->tz_tcnicodocliente ? 'true' : 'false',
                    "na_statuscode" => $value->statuscode,
                    "na_tz_encaixe" => $value->tz_encaixe ? 'true' : 'false',
                    "na_tz_data_minima_agendamento" => isset($value->tz_data_minima_agendamento) ? date_format(date_create($value->tz_data_minima_agendamento), 'Y-m-d\Th:i') : '',
                    "na_tz_motivo_encaixe" => $value->tz_motivo_encaixe,
                    "na_tz_prestador_possui_peca_estoque" => $value->tz_prestador_possui_peca_estoque ? 'true' : 'false',
                    "na_tz_distancia_total" => $value->tz_distancia_total,
                    "na_tz_distancia_considerada" => $value->tz_distancia_considerada,
                    "na_tz_valor_km_considerado_cliente" => $value->tz_valor_km_considerado_cliente,
                    "na_tz_valor_total_deslocamento" => $value->tz_valor_total_deslocamento,
                    "na_tz_taxa_visita" => $value->tz_taxa_visita,
                    "na_scheduledstart" => isset($value->scheduledstart) ? date_format(date_create($value->scheduledstart), 'Y-m-d\Th:i') : '',
                    "na_scheduledend" => isset($value->scheduledend) ? date_format(date_create($value->scheduledend), 'Y-m-d\Th:i') : '',
                    "na_tz_id_agendamento" => $value->tz_id_agendamento,
                    "na_tz_distancia_bonificada" => $value->tz_distancia_bonificada,
                    
                );

                $relationshipsAS = $this->sac->get("serviceappointments($idAtividade)", array(
                    '$select' => "activityid",
                    '$expand' => 'tz_item_contratoid_serviceappointment($select=tz_name,tz_item_contrato_vendaid),
                        serviceid_serviceappointment($select=serviceid,name),
                        siteid($select=siteid,name),
                        tz_endereco_estadoid_serviceappointment($select=tz_estadoid,tz_name),
                        tz_endereco_cidadeid_serviceappointment($select=tz_cidadeid,tz_name),
                        regardingobjectid_tz_bloqueio_agenda_serviceappointment($select=tz_bloqueio_agendaid,tz_name),
                        tz_rastreadorid_serviceappointment($select=productid,name),
                        tz_planoid_serviceappointment($select=productid,name),
                        tz_veiculo_contratoid_serviceappointment($select=tz_placa,tz_veiculoid),
                        tz_frota_afid_serviceappointment($select=tz_frotaafid,tz_name)'
                ));

                if($relationshipsAS->code == 200){
                    $relValue = $relationshipsAS->data;

                    $response["selects"]["na_tz_item_contratoid"] = array(
                        "id" => $relValue->tz_item_contratoid_serviceappointment->tz_item_contrato_vendaid,
                        "text" => $relValue->tz_item_contratoid_serviceappointment->tz_name
                    );
                    $response["selects"]["na_serviceid"] = array(
                        "id" => $relValue->serviceid_serviceappointment->serviceid,
                        "text" => $relValue->serviceid_serviceappointment->name
                    );
                    $response["selects"]["na_tz_endereco_estadoid"] = array(
                        "id" => $relValue->tz_endereco_estadoid_serviceappointment->tz_estadoid,
                        "text" => $relValue->tz_endereco_estadoid_serviceappointment->tz_name
                    );
                    $response["selects"]["na_tz_endereco_cidadeid"] = array(
                        "id" => $relValue->tz_endereco_cidadeid_serviceappointment->tz_cidadeid,
                        "text" => $relValue->tz_endereco_cidadeid_serviceappointment->tz_name
                    );
                    $response["selects"]["na_siteid"] = array(
                        "id" => $relValue->siteid->siteid,
                        "text" => $relValue->siteid->name
                    );
                    $response["selects"]["na_tz_bloqueio_agendaid"] = array(
                        "id" => $relValue->regardingobjectid_tz_bloqueio_agenda_serviceappointment->tz_bloqueio_agendaid,
                        "text" => $relValue->regardingobjectid_tz_bloqueio_agenda_serviceappointment->tz_name
                    );
                    $response["selects"]["na_tz_rastreadorid"] = array(
                        "id" => $relValue->tz_rastreadorid_serviceappointment->productid,
                        "text" => $relValue->tz_rastreadorid_serviceappointment->name
                    );
                    $response["selects"]["na_tz_planoid"] = array(
                        "id" => $relValue->tz_planoid_serviceappointment->productid,
                        "text" => $relValue->tz_planoid_serviceappointment->name
                    );
                    $response["selects"]["na_tz_veiculo_contratoid"] = array(
                        "id" => $relValue->tz_veiculo_contratoid_serviceappointment->tz_veiculoid,
                        "text" => $relValue->tz_veiculo_contratoid_serviceappointment->tz_placa
                    );
                    $response["selects"]["na_tz_tipo_veiculo_vendaid"] = array(
                        "id" => $relValue->tz_veiculo_contratoid_serviceappointment->tz_veiculoid,
                        "text" => $relValue->tz_veiculo_contratoid_serviceappointment->tz_name
                    );
                    $response["selects"]["na_tz_frota_afid"] = array(
                        "id" => $relValue->tz_frota_afid_serviceappointment->tz_frotaafid,
                        "text" => $relValue->tz_frota_afid_serviceappointment->tz_name
                    );
                }
                
                $relationshipsAS2 = $this->sac->get("serviceappointments($idAtividade)", array(
                    '$select' => "activityid",
                    '$expand' => 'tz_plataformaid_serviceappointment($select=tz_plataformaid,tz_name),
                        tz_cenario_vendaid_serviceappointment($select=tz_cenario_vendaid,tz_name),
                        tz_marca_vendaid_serviceappointment($select=tz_marcaid,tz_name),
                        tz_modelo_vendaid_serviceappointment($select=tz_modeloid,tz_name),
                        tz_emails_envio_agendamentoid_serviceappointment($select=tz_grupo_emails_clienteid,tz_name),
                        regardingobjectid_account_serviceappointment($select=accountid,name),
                        regardingobjectid_contact_serviceappointment($select=contactid,fullname),
                        tz_motivo_agendamento_tardioid_serviceappointment($select=tz_motivo_agendamento_tardioid,tz_name),
                        tz_endereco_cepid_serviceappointment,regardingobjectid_tz_item_contrato_venda_serviceappointment($select=tz_name,tz_item_contrato_vendaid)'
                        
                ));
                
                if($relationshipsAS2->code == 200){
                    $relValue2 = $relationshipsAS->data;
                    $response["selects"]["na_tz_plataformaid"] = array(
                        "id" => $relValue2->tz_plataformaid_serviceappointment->tz_plataformaid,
                        "text" => $relValue2->tz_plataformaid_serviceappointment->tz_name
                    );

                    $response["selects"]["na_tz_cenario_vendaid"] = array(
                        "id" => $relValue2->tz_cenario_vendaid_serviceappointment->tz_cenario_vendaid,
                        "text" => $relValue2->tz_cenario_vendaid_serviceappointment->tz_name
                    );

                    $response["selects"]["na_tz_marca_vendaid"] = array(
                        "id" => $relValue2->tz_marca_vendaid_serviceappointment->tz_marcaid,
                        "text" => $relValue2->tz_marca_vendaid_serviceappointment->tz_name
                    );

                    $response["selects"]["na_tz_modelo_vendaid"] = array(
                        "id" => $relValue2->tz_modelo_vendaid_serviceappointment->tz_modeloid,
                        "text" => $relValue2->tz_modelo_vendaid_serviceappointment->tz_name
                    );

                    $response["selects"]["na_tz_endereco_cepid"] = array(
                        "id" => $relValue2->tz_endereco_cepid_serviceappointment->tz_cepid,
                        "text" => $relValue2->tz_endereco_cepid_serviceappointment->tz_cep1
                    );

                    $response["selects"]["na_tz_emails_envio_agendamentoid"] = array(
                        "id" => $relValue2->tz_emails_envio_agendamentoid_serviceappointment->tz_grupo_emails_clienteid,
                        "text" => $relValue2->tz_emails_envio_agendamentoid_serviceappointment->tz_name
                    );

                    $response["selects"]["na_tz_emails_envio_orcamentoid"] = array(
                        "id" => $relValue2->tz_emails_envio_agendamentoid_serviceappointment->tz_grupo_emails_clienteid,
                        "text" => $relValue2->tz_emails_envio_agendamentoid_serviceappointment->tz_name
                    );

                    $response["selects"]["na_tz_cliente_pj_pagar_osid"] = array(
                        "id" => $relValue2->regardingobjectid_account_serviceappointment->accountid,
                        "text" => $relValue2->regardingobjectid_account_serviceappointment->name
                    );

                    $response["selects"]["na_tz_cliente_pf_pagar_osid"] = array(
                        "id" => $relValue2->regardingobjectid_contact_serviceappointment->contactid,
                        "text" => $relValue2->regardingobjectid_contact_serviceappointment->fullname
                    );

                    $response["selects"]["na_tz_motivo_agendamento_tardioid"] = array(
                        "id" => $relValue2->tz_motivo_agendamento_tardioid_serviceappointment->tz_item_contrato_vendaid,
                        "text" => $relValue2->tz_motivo_agendamento_tardioid_serviceappointment->tz_name
                    );
                    
                }        
                echo json_encode(array('status' => true, 'data' => $response));

            }else{
                echo json_encode(array('status' => false, 'erro' => $atividadeServico->data));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => false, 'erro' => $th->getMessage()));
        }        

    }

    /**
     * Retorna data de inicio e fim da atividade de serviço
     */
    public function ajax_buscar_info_datas_atividade_servico($idAtividade){
        $atividadeServico = $this->sac->get("serviceappointments($idAtividade)", array(
            '$select' => "scheduledstart,scheduledend"
        ));
        if($atividadeServico->code == 200){
            $response = array(
                "inputs" => array(
                    "na_activityid" => $atividadeServico->data->activityid,
                    "na_scheduledstart" => isset($atividadeServico->data->scheduledstart) ? date_format(date_create($atividadeServico->data->scheduledstart)->setTimezone($this->timeZone), 'Y-m-d\Th:i') : null,
                    "na_scheduledend" => isset($atividadeServico->data->scheduledend) ? date_format(date_create($atividadeServico->data->scheduledend)->setTimezone($this->timeZone), 'Y-m-d\Th:i') : null
                )
            );
            
            echo json_encode(array('status' => true, 'data' => $response));

        }else{
            $erro = isset($atividadeServico->data->error) ? $atividadeServico->data->error->message : 'Erro ao buscar datas da atividade de serviço!';
            echo json_encode(array('status' => false, 'erro' => $erro));
        }

    }
    public function ajax_buscar_os(){
        $data = $this->input->post('numero');

        if(isset($data)){
            $parametros = array(
                '$select'   => 'tz_ordem_servicoid,statuscode,tz_tipo_servico,tz_observacoes,createdon,_modifiedby_value,
                    modifiedon,tz_tipo_servico,tz_valor_total,tz_tipo_pagamento,tz_condicao_pagamentoid,statecode',
                '$expand'   => 'tz_atividade_servicoid($select=statecode)',
                '$filter'   => "tz_numero_os eq '" . $data ."'"
            );
            
            $ordensDeServico = $this->sac->buscar('tz_ordem_servicos', http_build_query($parametros));
            $resposta = array();
            if($ordensDeServico['code'] === 200){
                $value = $ordensDeServico['value'][0];

                $resposta[] = array(
                    'tz_ordem_servicoid'            => $value['tz_ordem_servicoid'],
                    'tz_numero_os'                  => $data,
                    'statecode'                     => $value['statecode'],
                    'statecodeAtividadeDeServico'   => $value['tz_atividade_servicoid']['statecode'],
                    'statuscodevalue'               => $value['statuscode'],
                    'statuscode'                    => $value['statuscode@OData.Community.Display.V1.FormattedValue'],
                    'tz_tipo_servico'               => $value['tz_tipo_servico'],
                    'createdon'                     => $value['createdon@OData.Community.Display.V1.FormattedValue'],
                    'modifiedon'                    => $value['modifiedon@OData.Community.Display.V1.FormattedValue'],
                    'modifiedby'                    => $value['_modifiedby_value@OData.Community.Display.V1.FormattedValue'],
                    'tz_tipo_servico'               => $value['tz_tipo_servico@OData.Community.Display.V1.FormattedValue'],
                    'tz_valor_total'                => $value['tz_valor_total'],
                    'tz_tipo_pagamento'             => $value['tz_tipo_pagamento'],
                    'tz_condicao_pagamentoid'       => $value['tz_condicao_pagamentoid'],
                    'tz_observacoes'                => $value['tz_observacoes']
                );

                echo json_encode(array(
                    'status'    => 200,
                    'data'      => $resposta
                ));
            }else{
                echo json_encode(array(
                    'status'    => $ordensDeServico['code'],
                    'data'      => null
                ));
            }
           
        }else{
            echo json_encode(array(
                'status'    => 500,
                'message'   => "O número da OS é inválido."
            ));
        }
    }
    public function ajax_buscar_ocorrencia_atividade_servico($idOcorrencia){
        try {
            $entity = "incidents";
            $api_request_parameters = array(
                '$select' => 'title,zatix_observacoes,zatix_filaatual,ticketnumber,createdon',
                '$filter'=>"incidentid eq {$idOcorrencia}",
            );
    
            $ocorrencia = $this->sac->get($entity, $api_request_parameters);
            if($ocorrencia->code == 200){
                $valor = $ocorrencia->data->value[0];
                $resposta = array(
                    'titulo' => isset($valor->title) ? $valor->title : '',
                    'descricao' => isset($valor->zatix_observacoes) ? $valor->zatix_observacoes : '',
                    'filaAtual' => isset($valor->zatix_filaatual) ? $valor->zatix_filaatual : '',
                    'ticketnumber' => isset($valor->ticketnumber) ? $valor->ticketnumber : '',
                    'createdon' => isset($valor->createdon) ? date_format(date_create($valor->createdon), 'd/m/Y H:i:s') : '',
                );
                echo json_encode(array('code' => $ocorrencia->code, "item" => $resposta));
            }else{
                echo json_encode(array('code' => $ocorrencia->code, 'error' => $ocorrencia->value));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('code' => 500, 'error' => $th));
        }
    }
    /*
    * Essa função irá buscar as atividades de serviço de uma NA (atividade de serviço)
    * @param
    */
    public function listarOS($idNA = false){
        try {
            if (!$idNA){
                $dados = $this->input->get();
            } else {
                $dados = array('atividade_de_servico_id' => $idNA);
            }   


            if(!isset($dados['atividade_de_servico_id'])){
                echo json_encode(array(
                    'status'    => false,
                    'erro'      => 'Ativdade de serviço não informada.'
                ));   
            }else{
                $idAtividade = $dados['atividade_de_servico_id'];
                $parametros = array(
                    '$select'   => 'tz_numero_os,statecode,statuscode,tz_tipo_servico,tz_observacoes',
                    '$filter'   => "_tz_atividade_servicoid_value eq " . $idAtividade 
                );
                
                $ordensDeServico = $this->sac->get('tz_ordem_servicos', $parametros);
                $resposta = array();
                if($ordensDeServico->code == 200){
                    $values = $ordensDeServico->data->value;
                    
                    foreach ($values as $key => $value) {
                        $resposta[] = array(
                            'tz_numero_os'                  => $value->tz_numero_os,
                            'statecode'                     => $value->statecode,
                            'statuscode'                    => $value->statuscode,
                            'tz_tipo_servico'               => $value->tz_tipo_servico,
                            'tz_observacoes'                => $value->tz_observacoes,
                        );
                    }
                    
                    if($idNA){
                        return $resposta[0]['tz_numero_os'];
                    }
                    
                    echo json_encode(array(
                        'status'    => true,
                        'dados'     => $resposta
                    ));
                }else{
                    echo json_encode(array(
                        'status'    => false,
                        'erro'      => $ordensDeServico->data
                    ));   
                }
            }
        } catch (\Throwable $th) {
            echo json_encode(array(
                'status'    => false,
                'erro'      => 'Erro ao buscar ordens de serviço!'.$th->data
            ));   
        }
        
    }

    private function getAuditoriaIncident($incidentId)
    {
        try {
            $auditoria = $this->painelOmnilink->getAuditoriaIncident($incidentId);

            if (!empty($auditoria)) {
                return [ 'status' => true, 'data' => $auditoria ];
            } else {
                return [ 'status' => false, 'data' => [] ];
            }
        } catch (\Throwable $th) {
            return [ 'status' => false, 'message' => 'Unexpected error - 500' ];
        }
    }

    public function listarAnotacoes($incident = null, $retorno = false){
        try {
            $idIncident = is_null($incident) ? $this->input->get('idIncident') : $incident;

            $parametros = array(
                '$filter' => '_objectid_value eq '.$idIncident, 
                '$select' => 'annotationid, subject, notetext, filename, documentbody, mimetype, createdon',
                '$expand' => 'owninguser($select=yomifullname)'
            );

            $response = $this->sac->get('annotations',$parametros);

            if($response->code == 200){ 
                $return['code'] = $response->code;           
                $return['data'] = [];

                foreach($response->data->value as $annotation){

                    $user = $this->painelOmnilink->find_user_annotation($annotation->annotationid);
                    $anexo = '';

                    # verifica se existe anexo na anotação e faz o decode e cria link para download
                    if($annotation->filename){
                        $file = 'data: '.mime_content_type($annotation->mimetype).';base64,'.$annotation->documentbody;
                        $anexo = '<a download="'.$annotation->filename.'" href="'.$file.'">'.$annotation->filename.'</a>';
                    }
                    //necessário converter para o timezone do brasil
                    
                    
                    $return['data'][] = [
                        'subject'       => $annotation->subject,
                        'notetext'      => $annotation->notetext,
                        'createdon'     => date_format(date_create($annotation->createdon)->setTimezone($this->timeZone), 'd/m/Y H:i:s'),
                        'criado_por'    => isset($user) ? '(Shownet) '.$user->nome : '(CRM) '.$annotation->owninguser->yomifullname,
                        'anexo'         => $anexo,
                        'acoes'         => $this->gerarBotoesAnotacoes($annotation->annotationid)
                    ];
                }
            }

            if ($retorno === true)
                return $return;
            else
                exit(json_encode($return));
        } catch(\Throwable $th){
            echo json_encode(array('code' => 500, 'error' => $th));
        } 
    }

    public function buscar_anotacao($id = null){
        
        if($id === null){
            $id = $this->input->post('id');
            $filter = 'annotationid eq '. $id;
        } else{
            $filter = '_objectid_value eq '. $id;
        }

        $parametros = array(
            '$filter' => $filter,
            '$select' => 'annotationid, subject, filename, notetext, documentbody, mimetype'
        );

        $response = $this->sac->get('annotations',$parametros);

        $dados = [];

        if ($response->code === 200){

            foreach($response->data->value as $anotacao){
                $user = $this->painelOmnilink->find_user_annotation($anotacao->annotationid);
                $anexo = '';

                    # verifica se existe anexo na anotação e faz o decode e cria link para download
                if($anotacao->filename){
                    $file = 'data: '.mime_content_type($anotacao->mimetype).';base64,'.$anotacao->documentbody;
                    $anexo = '<a download="'.$anotacao->filename.'" href="'.$file.'">'.$anotacao->filename.'</a>';
                }

                //necessário converter para o timezone do brasil
                $dados[] = [
                    'idAnotacao'    => $anotacao->annotationid,
                    'subject'       => $anotacao->subject,
                    'notetext'      => $anotacao->notetext,
                    'createdon'     => date_format(date_create($anotacao->createdon)->setTimezone($this->timeZone), 'd/m/Y H:i:s'),
                    'criado_por'    => isset($user) ? '(Shownet) '.$user->nome : '(CRM) '.$anotacao->owninguser->yomifullname,
                    'anexo'         => $anexo,
                    'acoes'         => $this->gerarBotoesAnotacoes($anotacao->annotationid)
                ];  
            }

            $retorno = json_encode(
                array(
                    'code'  => 200,
                    'data'  => $dados
                )
            );

            if($this->input->post('id'))
                echo $retorno;
            else
                return $retorno;

        }
        else{
            $retorno = json_encode(
                array(
                    'code'      => $response->code,
                    'error'     => $response->error
                    )
                );

            if($this->input->post('id') !== null)
                echo $retorno;
            else
                return $retorno;
        }

    }

    private function gerarBotoesAnotacoes($idAnotacao){
        return '<div class="btn btn-primary" onclick="editarAnotacao(this,\''.$idAnotacao.'\')" title=Editar Anotação" style="text-align: center; margin: 1%;"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                <div class="btn btn-danger" onclick="excluirAnotacao(this,\''.$idAnotacao.'\', true)" title="Excluir anotação" style="text-align: center; margin: 1%;"><i class="fa fa-trash" aria-hidden="true"></i></div>';
    }

    public function criarAnotacoes($dados = null){
        try {
            if($dados === null){

                $subject = $this->input->post('subject');
                $notext = $this->input->post('notext');
                $id = $this->input->post('incidentId');

                if($this->input->post('anexo')){
                    $documentbody = $this->input->post('anexo');
                    $mimetype = $this->input->post('mimeType');
                    $filename = $this->input->post('fileName');
                }
                $entidade = "incident";
 
                if(!isset($id) || !$id){
                    $id = $this->input->post('idNa');
                    $entidade = "serviceappointment";
                } 
            }else{
                $subject = $dados['subject'];
                $notext = $dados['noteText'];
                $id = $dados['idNa'];

                if($dados['documentBody']){
                    $documentbody = $dados['documentBody'];
                    $mimetype = $dados['mimeType'];
                    $filename = $dados['fileName'];
                }
                $entidade = "serviceappointment";
            }

            $dataPost = array(
                'subject' => $subject,
                'notetext' => $notext,
                'objectid_'. $entidade .'@odata.bind' => "/" . $entidade ."s(".$id.")",
                'documentbody' => isset($documentbody) ? $documentbody : '',
                'mimetype' => isset($mimetype) ? $mimetype : '',
                'filename' => isset($filename) ? $filename : ''
            );

            $response = $this->sac->post('annotations',$dataPost);

            // insere usuario que criou anotação
            if($response->code == 201 && isset($response->data->annotationid)){
                $this->painelOmnilink->salvar_user_anotacao($response->data->annotationid);
            }

            if($dados === null){
                exit(json_encode($response));
            }else{
                return $response->code === 201 ?  true : false;
            }
        } catch (\Throwable $th){
            exit(
                json_encode(
                    array(
                        'code' => 500, 
                        'error' => $th
                    )
                )
            );
        }
    }

    public function editarAnotacoes($dados = null){
        try {

            if($dados === null){
                $idAnotacao = $this->input->post('idAnotacao');
                $notext = $this->input->post('notext');
                if(!isset($idAnotacao)){
                    echo json_encode(
                        array(
                            'status'    => 400,
                            'message'   => "ID da anotação não informado." 
                        )
                    );
                }
    
                if($this->input->post('anexo')){
                    $documentbody = $this->input->post('anexo');
                    $mimetype = $this->input->post('mimeType');
                    $filename = $this->input->post('fileName');
                    $notext = $this->input->post('notext');
                }

                $noteText = $this->input->post('notext');
                $subject = $this->input->post('subject');
                $idAnotacao = $this->input->post('idAnotacao');

            }else{
                if(!isset($dados['idAnotacao'])) return false;
                
                $noteText = $dados['noteText'];
                $idAnotacao = $dados['idAnotacao'];
                
                if(isset($dados['documentBody'])){
                    $documentbody = $dados['documentBody'];
                    $mimetype = $dados['mimeType'];
                    $filename = $dados['fileName'];
                }
            }

            $dataPost = array('notetext' => $noteText);

            if(isset($subject)) $dataPost['subject'] = $subject;

            if(isset($documentbody)) $dataPost['documentbody'] = $documentbody;

            if(isset($mimetype)) $dataPost['mimetype'] = $mimetype;

            if(isset($filename)) $dataPost['filename'] = $filename;

            $response = $this->sac->patch('annotations', $idAnotacao, $dataPost);
            // insere usuario que criou anotação
            if($response->code === 200){
                if($dados !== null) return true;

                echo json_encode(
                    array(
                        'status'    => 200,
                        'message'   => "Anotação alterada com sucesso!"
                    )
                );
            }else{
                if($dados !== null) return false;

                echo json_encode(
                    array(
                        'status'    => $response->code,
                        'message'   => "Ocorreu um erro ao tentar alterar a anotação.",
                        'error'     => $response->error    
                    )
                );
            }
        } catch (\Throwable $th){
            echo json_encode(
                    array(
                        'status' => 500, 
                        'error' => $th
                )
            );
        }
    }

    public function excluirAnotacao(){
        try {
            $idAnotacao = $this->input->post('idAnotacao');
            $response = $this->sac->delete('annotations',$idAnotacao);

            if($response->code == 204){
                echo json_encode(
                    array(
                        'status'    => 204,
                        'message'   => "Anotação deletada com sucesso!"
                    )
                );
            }else{
                echo json_encode(
                    array(
                        'status'    => $response->code,
                        'message'   => "Ocorreu um erro ao tentar deletar a anotação.",
                        'error'     => $response->error    
                    )
                );
            }
        } catch (\Throwable $th){
            echo json_encode(
                    array(
                        'status' => 500, 
                        'error' => $th
                )
            );
        }
    }

    public function ajax_buscar_contrato_por_placa_serial($valorBusca){
        try {
            $contrato = $this->buscar_contrato_por_codigo($valorBusca);
                echo json_encode($contrato);
        } catch (\Throwable $th) {
            echo json_encode(array('code' => 500, 'error' => $th));
        }        
    }

    private function buscar_contrato_por_codigo($codigo){
        $entity = 'tz_item_contrato_vendas';
        
        //procura o contrato que esteja ativo
        $api_request_parameters = array(
            '$select' => 'tz_item_contrato_vendaid,tz_name,_tz_motivo_alteracao_value, tz_status_item_contrato, tz_numero_serie_modulo_principal,tz_valor_total_servicos_ativos_base,tz_valor_licenca_base,tz_numero_af,tz_descricao,_tz_canal_vendaid_value,_tz_cenario_vendaid_value,_tz_veiculoid_value,_tz_rastreadorid_value,_tz_tecnologiaid_value,_tz_cliente_pjid_value,_tz_cliente_pfid_value,tz_data_entrada,tz_data_ativacao',
            '$filter'=>"tz_name eq '{$codigo}' and tz_status_item_contrato eq 1",
            '$expand'=>'tz_plano_linkerid($select=name),tz_canal_vendaid($select=tz_name),tz_cenario_vendaid($select=tz_name),tz_veiculoid($select=tz_placa),tz_rastreadorid($select=name),tz_tecnologiaid($select=tz_name),tz_cliente_pjid($select=zatix_cnpj,name,zatix_nomefantasia,emailaddress1),tz_cliente_pfid($select=yomifullname,zatix_nomefantasia,emailaddress1,zatix_cpf)',
            '$top'=> 1
        );

        $contrato = $this->sac->get($entity, $api_request_parameters);
        
        //caso não encontre um contrato ativo, procura contratos com qualquer status
        if(!isset($contrato->data->value[0])){
            $api_request_parameters = array(
                '$select' => 'tz_item_contrato_vendaid,tz_name,tz_numero_serie_modulo_principal,tz_valor_total_servicos_ativos_base,tz_valor_licenca_base,tz_numero_af,tz_descricao,_tz_canal_vendaid_value,_tz_cenario_vendaid_value,_tz_veiculoid_value,_tz_rastreadorid_value,_tz_tecnologiaid_value,_tz_cliente_pjid_value,_tz_cliente_pfid_value,tz_data_entrada,tz_data_ativacao, _tz_motivo_alteracao_value, tz_status_item_contrato',
                '$filter'=>"tz_name eq '{$codigo}'",
                '$expand'=>'tz_plano_linkerid($select=name),tz_canal_vendaid($select=tz_name),tz_cenario_vendaid($select=tz_name),tz_veiculoid($select=tz_placa),tz_rastreadorid($select=name),tz_tecnologiaid($select=tz_name),tz_cliente_pjid($select=zatix_cnpj,name,zatix_nomefantasia,emailaddress1),tz_cliente_pfid($select=yomifullname,zatix_nomefantasia,emailaddress1,zatix_cpf)',
                '$top'=> 1
            );
            $contrato = $this->sac->get($entity, $api_request_parameters);
        }

        if($contrato->code == 200){
            $valor = $contrato->data->value[0];

            $xml = '<fetch>
                    <entity name="serviceappointment">
                        <attribute name="tz_houve_troca_modulo" />
                        <attribute name="tz_id_agendamento" />
                        <attribute name="tz_numero_serie_contrato" />
                        <attribute name="tz_taxa_visita" />
                        <attribute name="tz_numero_serie_antena_contrato" />
                        <attribute name="statuscode" />
                        <attribute name="tz_item_contratoid" />
                        <attribute name="tz_houve_troca_antena" />
                        <attribute name="tz_num_serie_rastreador_instalado" />
                        <attribute name="tz_distancia_bonificada" />
                        <attribute name="tz_modelo_tipo_ativacao" />
                        <attribute name="tz_cliente_pfid" />
                        <attribute name="tz_distancia_considerada" />
                        <attribute name="regardingobjectid" />
                        <attribute name="subject" />
                        <attribute name="tz_baseinstaladarastreador" />
                        <attribute name="tz_valor_km_considerado_cliente" />
                        <attribute name="statecode" />
                        <attribute name="createdby" />
                        <attribute name="tz_baseinstaladaantena" />
                        <attribute name="tz_distancia_total" />
                        <attribute name="activityid" />
                        <attribute name="scheduledend" />
                        <attribute name="tz_valor_total_deslocamento" />
                        <attribute name="actualend" />
                        <attribute name="scheduledstart" />
                        <link-entity name="service" from="serviceid" to="serviceid" link-type="outer" alias="service">
                            <attribute name="name" />
                            <attribute name="serviceid" />
                        </link-entity>
                        <link-entity name="site" from="siteid" to="siteid" link-type="outer" alias="siteid">
                            <attribute name="siteid" />
                            <attribute name="name" />
                        </link-entity>
                        <link-entity name="tz_complemento_servico" from="tz_complemento_servicoid" to="tz_complemento_servicoid" link-type="outer" alias="complemento">
                            <attribute name="tz_name" />
                        </link-entity>
                        <link-entity name="tz_ordem_servico" from="tz_atividade_servicoid" to="activityid" link-type="outer" alias="os">
                            <attribute name="tz_numero_os" />
                        </link-entity>
                        <link-entity name="tz_base_instalada_cliente" from="tz_base_instalada_clienteid" to="tz_baseinstaladaantena" link-type="outer" alias="antena">
                            <attribute name="tz_numero_serie" />
                        </link-entity>
                        <filter>
                            <condition attribute="tz_item_contratoid" operator="eq" value="{0}" />
                        </filter>
                        </entity>
                    </fetch>';

            $xml = str_replace('{0}', $valor->tz_item_contrato_vendaid, $xml);

            $os = $this->sac->buscar('serviceappointments', http_build_query(['fetchXml' => $xml]));
            if ($os['code'] == 200) {
                $os = $os['value'];
            } else {
                $os = null;
            }

            for($i = 0; $i < count($os); $i++){
                $valorOs = $os[$i];
                
                // Descrição do Status
                if($valorOs['statecode'] == 0) $statusDescription = 'Aberto';
                elseif($valorOs['statecode'] == 1) $statusDescription = 'Fechado';
                elseif($valorOs['statecode'] == 2) $statusDescription = 'Cancelado';
                else $statusDescription = 'Agendado';
                
                $respostaOS[] = array(
                    "Id" => $valorOs['activityid'],
                    'Code' => isset($valorOs['tz_id_agendamento']) ? $valorOs['tz_id_agendamento'] : '',
                    'Status' => $valorOs['statecode'],
                    'distanciaBonificada' => $valorOs['tz_distancia_bonificada'],
                    'distanciaConsiderada' => $valorOs['tz_distancia_considerada'],
                    'distanciaTotal' => $valorOs['tz_distancia_total'],
                    'StatusCode' => $valorOs['statuscode'],
                    'valorStatusCode' => json_decode(json_encode($valorOs),true)['statuscode@OData.Community.Display.V1.FormattedValue'],
                    'statusDescription' => $statusDescription,
                    'scheduledstart' => isset($valorOs['scheduledstart']) ? date_format(date_create($valorOs['scheduledstart']), 'd/m/Y H:i:s') : '',
                    'scheduledend' => isset($valorOs['scheduledend']) ? date_format(date_create($valorOs['scheduledend']), 'd/m/Y H:i:s') : '',
                    'actualend' => isset($valorOs['actualend']) ? date_format(date_create($valorOs['actualend']), 'd/m/Y H:i:s') : '',
                    'subject' => isset($valorOs['subject']) ? $valorOs['subject'] : '',
                    'contract' => array(
                        'Id' => $valorOs['_tz_item_contratoid_value'],
                        'Code' => isset($valorOs['_tz_item_contratoid_value']) ? $valorOs['_tz_item_contratoid_value'] : '',
                        'Name' => $valorOs['_tz_item_contratoid_value@OData.Community.Display.V1.FormattedValue']
                    ),
                    'serviceId' => isset($valorOs['service_x002e_serviceid']) ? $valorOs['service_x002e_serviceid'] : '',
                    'serviceName' => isset($valorOs['service_x002e_name']) ? $valorOs['service_x002e_name'] : '',
                    'serviceNameComplement' => isset($valorOs['complemento_x002e_tz_name']) ? $valorOs['complemento_x002e_tz_name'] : '',
                    'provider' => isset($valorOs['siteid_x002e_siteid']) ? $valorOs['siteid_x002e_name'] : '',
                    'trackerSerialNumber' => isset($valorOs['tz_numero_serie_contrato']) ? $valorOs['tz_numero_serie_contrato'] : '',
                    'satelliteSerialNumber' => isset($valorOs['tz_numero_serie_antena_contrato']) ? $valorOs['tz_numero_serie_antena_contrato'] : '',
                    'trackerSerialNumberInstall' => isset($valorOs['antena_x002e_tz_numero_serie']) ? $valorOs->tz_baseinstaladaantena_serviceappointment->tz_numero_serie : '',
                    'satelliteSerialNumberInstall' => isset($valorOs->tz_baseinstaladaantena->tz_numero_serie) ? $valorOs->tz_baseinstaladaantena->tz_numero_serie : '',
                    'incident' => array(
                        'Id' => $valorOs['_regardingobjectid_value'],
                        'TicketNumber' => isset($valorOs['_regardingobjectid_value@OData.Community.Display.V1.FormattedValue']) ? $valorOs['_regardingobjectid_value@OData.Community.Display.V1.FormattedValue'] : ''
                    ),
                    'houveTrocaAntena' => isset($valorOs['tz_houve_troca_antena']) ? $valorOs['tz_houve_troca_antena'] : '',
                    'houveTrocaModulo' => isset($valorOs['tz_houve_troca_modulo']) ? $valorOs['tz_houve_troca_modulo'] : '',
                    'taxaVista' => isset($valorOs['tz_taxa_vista']) ? $valorOs['tz_taxa_vista'] : '',
                    'numeroSerieRastreadorInstalado' => isset($valorOs['tz_num_serie_rastreador_instalado']) ? $valorOs['tz_num_serie_rastreador_instalado'] : '',
                    'tipoAtivacao' => isset($valorOs['tz_tipo_ativacao']) ? $valorOs['tz_tipo_ativacao'] : '',
                    'valorKmConsiderado' => isset($valorOs['tz_valorOs_km_considerado']) ? $valorOs['tz_valorOs_km_considerado'] : '',
                    'valorTotalDeslocamento' => isset($valorOs['tz_valorOs_total_deslocamento']) ? $valorOs['tz_valorOs_total_deslocamento'] : '',
                    'baseInstaladaRastreador' => isset($valorOs['_tz_baseinstaladarastreador_value']) ? $valorOs['_tz_baseinstaladarastreador_value'] : '',
                    'baseInstaladaAntena' => isset($valorOs['_tz_baseinstaladaantena_value']) ? $valorOs['_tz_baseinstaladaantena_value'] : '',
                    'numeroOs' => isset($valorOs['os_x002e_tz_numero_os']) ? $valorOs['os_x002e_tz_numero_os'] : ''
                );
            }

            if(isset($valor)){

                $resposta = array(
                    'id' => $valor->tz_item_contrato_vendaid,
                    'codigo' => isset($valor->tz_name) ? $valor->tz_name : '',
                    'serial' => isset($valor->tz_numero_serie_modulo_principal) ? $valor->tz_numero_serie_modulo_principal : '',
                    'valorTotalServicosAtivos' => isset($valor->tz_valor_total_servicos_ativos_base) ? $valor->tz_valor_total_servicos_ativos_base : '',
                    'valorLicencaBase' => isset($valor->tz_valor_licenca_base) ? $valor->tz_valor_licenca_base : '',
                    'numeroAf' => isset($valor->tz_numero_af) ? $valor->tz_numero_af : '',
                    'descricao' => isset($valor->tz_descricao) ? $valor->tz_descricao : '',
                    'plano' => isset($valor->tz_plano_linkerid->name) ? $valor->tz_plano_linkerid->name : '',
                    'canal_venda' => isset($valor->tz_canal_vendaid->tz_name) ? $valor->tz_canal_vendaid->tz_name : '',
                    'cenario_venda' => isset($valor->tz_cenario_vendaid->tz_name) ? $valor->tz_cenario_vendaid->tz_name : '',
                    'placa' => isset($valor->tz_veiculoid->tz_placa) ? $valor->tz_veiculoid->tz_placa : '',
                    'rastreador' => isset($valor->tz_rastreadorid->name) ? $valor->tz_rastreadorid->name : '',
                    'tecnologia' => isset($valor->tz_tecnologiaid->tz_name) ? $valor->tz_tecnologiaid->tz_name : '',
                    'status' => isset($valor->{'tz_status_item_contrato@OData.Community.Display.V1.FormattedValue'}) ? $valor->{'tz_status_item_contrato@OData.Community.Display.V1.FormattedValue'} : '',
                    'motivoAlteracao' => isset($valor->{'_tz_motivo_alteracao_value@OData.Community.Display.V1.FormattedValue'}) ? $valor->{'_tz_motivo_alteracao_value@OData.Community.Display.V1.FormattedValue'} : '',
                    'data_entrada' => isset($valor->tz_data_entrada) ? date_format(date_create($valor->tz_data_entrada), 'd/m/Y H:i:s') : '',
                    'data_ativacao' => isset($valor->tz_data_ativacao) ? date_format(date_create($valor->tz_data_ativacao), 'd/m/Y H:i:s') : '',
                );

                $resposta['os'] = $respostaOS;
    
                if(isset($valor->_tz_cliente_pjid_value)){
                    $resposta['cliente'] = array(
                        'cnpj' => $valor->tz_cliente_pjid->zatix_cnpj,
                        'nome' => $valor->tz_cliente_pjid->name,
                        'nomefantasia' => $valor->tz_cliente_pjid->zatix_nomefantasia,
                        'email' => $valor->tz_cliente_pjid->emailaddress1,
                    );
                }
                if(isset($valor->_tz_cliente_pfid_value)){
                    $resposta['cliente'] = array(
                        'cpf' => $valor->tz_cliente_pfid->zatix_cpf,
                        'nome' => $valor->tz_cliente_pfid->yomifullname,
                        'nomefantasia' => $valor->tz_cliente_pfid->zatix_nomefantasia,
                        'email' => $valor->tz_cliente_pfid->emailaddress1,
                    );
                }
    
                return (array('code' => $contrato->code, "contrato" => $resposta));
            }else{
                return (array('code' => $contrato->code, "contrato" => null));
            }
        }else{
            return (array('code' => $contrato->code, 'error' => $contrato->value));
        }
    }

    public function ajax_informacoes_ocorrencia()
    {
        try {
            $incidentId = $this->input->get('incidentId');

            $parametros = array(
                '$select' => 'ticketnumber,caseorigincode,casetypecode,tz_tipo_atendimento,zatix_filaatual,description,statecode,statuscode,createdon,modifiedon,tz_usuario_gestor_mod',
                '$expand' => 'owninguser($select=yomifullname),subjectid($select=description,_parentsubject_value,title),tz_tecnologia,zatix_filaatendimentoid($select=name),tz_ultima_fila,ownerid,createdby($select=fullname),modifiedby($select=fullname)',
            );

            $incidentInfo = $this->sac->buscar('incidents(' . $incidentId . ')', http_build_query($parametros));
            if (isset($incidentInfo['incidentid'])) {
                $resposta = array(
                    'assunto'           => isset($incidentInfo['subjectid']['title'])                ? $incidentInfo['subjectid']['title']                                                          : ' - ',
                    'assunto_primario'  => isset($incidentInfo['subjectid']['_parentsubject_value']) ? $incidentInfo['subjectid']['_parentsubject_value@OData.Community.Display.V1.FormattedValue'] : ' - ',
                    'assunto_descricao' => isset($incidentInfo['subjectid']['description'])          ? $incidentInfo['subjectid']['description']                                                    : ' - ',
                    'numero_ocorrencia' => isset($incidentInfo['ticketnumber'])                      ? $incidentInfo['ticketnumber']                                                                : ' - ',
                    'origem_ocorrencia' => isset($incidentInfo['caseorigincode'])                    ? $incidentInfo['caseorigincode@OData.Community.Display.V1.FormattedValue']                    : ' - ',
                    'tipo_ocorrencia'   => isset($incidentInfo['casetypecode'])                      ? $incidentInfo['casetypecode@OData.Community.Display.V1.FormattedValue']                      : ' - ',
                    'tipo_atendimento'  => isset($incidentInfo['tz_tipo_atendimento'])               ? $incidentInfo['tz_tipo_atendimento']                                                         : ' - ',
                    'tecnologia'        => isset($incidentInfo['tz_tecnologia']['tz_name'])          ? $incidentInfo['tz_tecnologia']['tz_name']                                                    : ' - ',
                    'fila_atual'        => isset($incidentInfo['zatix_filaatual'])                   ? $incidentInfo['zatix_filaatual']                                                             : ' - ',
                    'fila_atendimento'  => isset($incidentInfo['zatix_filaatendimentoid'])           ? $incidentInfo['zatix_filaatendimentoid']['name']                                             : ' - ',
                    'ultima_fila'       => isset($incidentInfo['tz_ultima_fila']['name'])            ? $incidentInfo['tz_ultima_fila']['name']                                                      : ' - ',
                    'proprietario'      => isset($incidentInfo['owninguser']['yomifullname'])        ? $incidentInfo['owninguser']['yomifullname']                                                  : ' - ',
                    'description'       => isset($incidentInfo['description'])                       ? $incidentInfo['description']                                                                 : ' - ',
                    'status'            => isset($incidentInfo['statecode'])                         ? $incidentInfo['statecode@OData.Community.Display.V1.FormattedValue']                         : ' - ',
                    'razao_status'      => isset($incidentInfo['statuscode'])                        ? $incidentInfo['statuscode@OData.Community.Display.V1.FormattedValue']                        : ' - ',
                    'data_criacao'      => isset($incidentInfo['createdon'])                         ? $incidentInfo['createdon@OData.Community.Display.V1.FormattedValue']                         : ' - ',
                    'data_modificacao'  => isset($incidentInfo['modifiedon'])                        ? $incidentInfo['modifiedon@OData.Community.Display.V1.FormattedValue']                        : ' - ',
                    'criado_por'        => isset($incidentInfo['createdby']['fullname'])             ? $incidentInfo['createdby']['fullname'] . ' (CRM)'                                            : ' - ',
                    'modificado_por'    => isset($incidentInfo['tz_usuario_gestor_mod'])             ? $incidentInfo['tz_usuario_gestor_mod'] . ' (SHOWNET)'                                        : $incidentInfo['modifiedby']['fullname'] . ' (CRM)' ,
                );

                // Caso esteja salvo no banco, substitui o usuário que criou e atualizou a ocorrência
                $ocorrencia_x_user_sac = $this->painelOmnilink->buscar_cadastro_ocorrencia($incidentInfo['incidentid']);
                if (isset($ocorrencia_x_user_sac)) {
                    if (isset($ocorrencia_x_user_sac->id_usuario_cadastro)) {
                        $resposta['data_criacao'] = isset($ocorrencia_x_user_sac->data_cadastro) ? date_format(date_create($ocorrencia_x_user_sac->data_cadastro), 'd/m/Y H:i:s') : ' - ';
                        $resposta['criado_por'] = isset($ocorrencia_x_user_sac->nome_usuario_cadastro) ? $ocorrencia_x_user_sac->nome_usuario_cadastro . ' (Shownet)' : '-';
                    }
                    if (isset($ocorrencia_x_user_sac->id_usuario_update)) {
                        $resposta['data_modificacao'] = isset($ocorrencia_x_user_sac->data_update) ? date_format(date_create($ocorrencia_x_user_sac->data_update), 'd/m/Y H:i:s') : ' - ';
                        $resposta['modificado_por'] = isset($ocorrencia_x_user_sac->nome_usuario_update) ? $ocorrencia_x_user_sac->nome_usuario_update . ' (Shownet)' : '-';
                    }
                }

                header('Content-Type: application/json; charset=utf-8');
                exit(json_encode(
                    array(
                        'code' => 200,
                        'ocorrencia' => $resposta
                    )
                ));
            } else {
                throw new \Throwable("Erro ao realizar a consulta.");
            }
        } catch (\Throwable $th) {
            exit(json_encode(
                array(
                    'code' => 500,
                    'error' => $th
                )
            ));
        }
    }

    public function encerrarOcorrencia(){
        try {
            $incidentId = $this->input->post('incidentId');
            $tipoResolucao = $this->input->post('selectIdTipoResolucao');
            $subject = $this->input->post('resolucaoOcorrencia');
            $description = $this->input->post('descricaoAnotacao');            
            $quantidadeAtividadesAbertas = $this->input->post('quantidadeAtividadesAbertas');
            
            // Caso exista atividades em aberto, executa operação em lote para finalizar atividades da ocorrência
            if(isset($quantidadeAtividadesAbertas) && intval($quantidadeAtividadesAbertas) > 0){
                $respostaAtividadesOcorrencia = $this->finalizarAtividadesAbertasOcorrencia($incidentId);
            }


            $responseIncidentResolution = $this->inserirIncidentResolution($incidentId, $subject, $description, $tipoResolucao);
            
            if ($responseIncidentResolution->code != 201){
                throw new Exception();
            }


            $incidentResolution = array(
                'subject' =>  $subject,
                'description' =>  $description,                
                'incidentid@odata.bind' => '/incidents('.$incidentId.')'
            );

            $response = $this->closeIncident($incidentResolution, $tipoResolucao);

            if ($response['code'] != 204){
                throw new Exception();
            }

            exit(
                json_encode(
                    array(
                        'code' => 200, 
                        'mensagem' => "Ocorrência encerrada com sucesso."
                    )
                )
            );

        } catch (Exception $ex){
            exit(
                json_encode(
                    array(
                        'code' => 500, 
                        'error' => "Não foi possível encerrar a ocorrência, por favor tente mais tarde."
                    )
                )
            );
        }
    }

    /**
     * Finaliza as atividades em aberto das ocorrencias
     */
    private function finalizarAtividadesAbertasOcorrencia($incidentId){
        $paramentros = array(
            '$filter' => '(statecode eq 0) and (_regardingobjectid_value eq '.$incidentId.')',
            '$select' => 'activityid,_regardingobjectid_value,statecode'
        );

        $response = $this->sac->get('activitypointers', $paramentros);
    }
    /**
     * Insere IncidentResolucion para finalização da ocorrência.
     * indicando que está 'completed'
     */
    private function inserirIncidentResolution($incidentId, $subject, $description, $statecode){
        $parametros = array(
            'subject' =>  $subject,
            'statecode' => $statecode,
            'description' =>  $description, 
            'incidentid@odata.bind' => '/incidents('.$incidentId.')'
        );
        
        $response = $this->sac->post('incidentresolutions', $parametros);
        return $response;
    }

    /**
     * Fecha ocorrência
     */
    private function closeIncident($incidentResolution, $status = -1){
        $parametros = array(
            'IncidentResolution' => $incidentResolution,
            'Status' => $status
        );
        
        $response = $this->sac->post('CloseIncident', $parametros);
        return $response;
    }

    /**
     * Cancelar ocorrência
     */
    public function cancelar_ocorrencia(){
        try {
            $dados = $this->input->post();
            $ocorrenciaId = $dados['incident_id'];

            if($ocorrenciaId){
                $data['statecode'] = 2;
                $response = $this->sac->patch('incidents', $ocorrenciaId, $data);
            
                if($response->code == 200){                
                    echo json_encode(array('status' => 1, 'msg' => "Ocorrência cancelada com sucesso!"));
                }else{
                    echo json_encode(array('status' => 0, 'erro' => $response->data));
                }
            }else{
                echo json_encode(array('status' => 0, 'erro' => "O id da ocorrência não é válido!"));
            }
           
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 0, 'erro' => $th));
        }
    }

    public function solicitarAtividadesAbertas(){
        try {
            $incidentId = $this->input->get('incidentId');
            // statecode 0 = aberta
            // statecode 3 = agendada
            $paramentros = array(
                '$filter' => '(statecode eq 0 or statecode eq 3) and (_regardingobjectid_value eq '.$incidentId.')',
                '$select' => 'activityid,_regardingobjectid_value,statecode'
            );
    
            $response = $this->sac->get('activitypointers', $paramentros);

            if ($response->code >= 400){
                throw new Exception();
            }

            exit(
                json_encode(
                    array(
                        'code' => 200, 
                        'atividades' => $response->data->value
                    )
                )
            );

        } catch (Exception $ex){
            exit(
                json_encode(
                    array(
                        'code' => 500, 
                        'error' => "Não foi possível solicitar o encerramento da ocorrência, por favor tente mais tarde"
                    )
                )
            );
        }
    }
    public function ajax_buscar_cliente_por_nome() {
        $search = $this->input->get('q');
        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];
        
        if (strlen($search)) {
            $clientes = $this->painelOmnilink->buscar_cliente_nome($search);

            if(count($clientes) > 0){
                foreach ($clientes as $key => $cliente) {
                    //ocorreram alguns casos de o cpf está com poucos caracteres e bugava a verificação
                    //necessário colocar uma verificação de caracteres 
                    if(isset($cliente['cpf']) && strlen($cliente['cpf']) >= 11 ){
                        $document = $cliente['cpf'];
                    }else{
                        $document = $cliente['cnpj'];
                    }
    
                    $resposta['results'][] = array(
                        'id' => $document,
                        'text' => $cliente['nome']." (" .$cliente['razao_social'] .") - ". $document
                    );
                }
            }

            echo json_encode($resposta);
        }else{
            echo json_encode($resposta);
        }
        
    }

    public function ajax_buscar_cliente_por_id() {
        $search = $this->input->get('q');
        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];
        
        if (strlen($search)) {
            $clientes = $this->painelOmnilink->buscar_cliente_id($search);

            if(count($clientes) > 0){
                foreach ($clientes as $key => $cliente) {
                    //ocorreram alguns casos de o cpf está com poucos caracteres e bugava a verificação
                    //necessário colocar uma verificação de caracteres 
                    if(isset($cliente['cpf']) && strlen($cliente['cpf']) >= 11 ){
                        $document = $cliente['cpf'];
                    }else{
                        $document = $cliente['cnpj'];
                    }
    
                    $resposta['results'][] = array(
                        'id' => $document,
                        'text' => $cliente['nome']." - " .$cliente['id'] ." - ". $document
                    );
                }
            }

            echo json_encode($resposta);
        }else{
            echo json_encode($resposta);
        }
        
    }

    public function ajax_buscar_cliente_por_usuario() {
        $search = $this->input->get('q');
        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];
        
        if (strlen($search)) {
            $clientes = $this->usuario_gestor->buscar_cliente_usuario($search);

            if(count($clientes) > 0){
                foreach ($clientes as $key => $cliente) {
                    //ocorreram alguns casos de o cpf está com poucos caracteres e bugava a verificação
                    //necessário colocar uma verificação de caracteres 
                    if(isset($cliente['cpf']) && strlen($cliente['cpf']) >= 11 ){
                        $document = $cliente['cpf'];
                    }else{
                        $document = $cliente['CNPJ_'];
                    }
    
                    $resposta['results'][] = array(
                        'id' => $document,
                        'text' => $cliente['nome_usuario']." - " .$cliente['usuario'] ." - ". $document
                    );
                }
            }

            echo json_encode($resposta);
        }else{
            echo json_encode($resposta);
        }
        
    }



    function listar_segmentacoes(){
        $url = 'tz_segmentacao_clientes';
        $api_request_parameters = array('$select' => 'tz_name,tz_segmentacao_clienteid');

        $segmentacoes = $this->sac->get($url, $api_request_parameters);
        exit(json_encode($segmentacoes));
    }
    

    public function ajax_buscar_faturas_cliente($id_cliente){
        try {
            $api_request_parameters = array(
                '$filter' => "_customerid_value eq {$id_cliente}",
                '$expand' => 'pricelevelid($select=name),opportunityid($select=name),salesorderid($select=name,ordernumber),transactioncurrencyid($select=currencyname,isocurrencycode,currencysymbol)'
            );
    
            $faturas = $this->sac->get('invoices', $api_request_parameters);
    
            if($faturas->code == 200){
                $valores = $faturas->data->value;
                $resposta = array();
                foreach ($valores as $key => $valor) {
                    $resposta[] = array(
                        'codigo' => $valor->invoicenumber,
                        'nome' => $valor->name,
                        'moeda' => isset($valor->transactioncurrencyid) ? $valor->transactioncurrencyid->currencyname : '',
                        'codigoMoeda' => isset($valor->transactioncurrencyid) ? $valor->transactioncurrencyid->isocurrencycode : '',
                        'simboloMoeda' => isset($valor->transactioncurrencyid) ? $valor->transactioncurrencyid->currencysymbol : '',
                        'simboloMoeda' => isset($valor->transactioncurrencyid) ? $valor->transactioncurrencyid->currencysymbol : '',
                        'listaPrecos' => isset($valor->pricelevelid) ? $valor->pricelevelid->name : '',
                        'precoBloqueado' => $valor->ispricelocked,
                        'dataEntrega' => isset($valor->datedelivered) ? date_format(date_create($valor->datedelivered), 'd/m/Y') : '',
                        'dataConclusao' => isset($valor->duedate) ? date_format(date_create($valor->duedate), 'd/m/Y') : '',
                        'codigoMetodoEntrega' => $valor->shippingmethodcode,
                        'codigoCondicoesPagamento' => $valor->paymenttermscode,
                        'enderecoCobranca' => array(
                            'rua1' => $valor->billto_line1,
                            'rua2' => $valor->billto_line2,
                            'rua3' => $valor->billto_line3,
                            'cidade' => $valor->billto_city,
                            'estado' => $valor->billto_stateorprovince,
                            'cep' => $valor->billto_postalcode,
                            'paisRegiao' => $valor->billto_country,
                        ),
                        'enderecoEntrega' => array(
                            'rua1' => $valor->shipto_line1,
                            'rua2' => $valor->shipto_line2,
                            'rua3' => $valor->shipto_line3,
                            'cidade' => $valor->shipto_city,
                            'estado' => $valor->shipto_stateorprovince,
                            'cep' => $valor->shipto_postalcode,
                            'paisRegiao' => $valor->shipto_country,
                        ),
                        'oportunidade' => isset($valor->opportunityid) ? $valor->opportunityid->name : '',
                        'pedido'=> isset($valor->salesorderid) ? $valor->salesorderid->name : '',
                        'numeroPedido'=> isset($valor->salesorderid) ? $valor->salesorderid->ordernumber : '',
                        'descricao'=>$valor->description,
                        'valorDetalhado'=>$valor->totallineitemamount_base,
                        'porcentagemDesconto'=>$valor->discountpercentage,
                        'desconto'=>$valor->discountamount,
                        'valorSemFrete'=>$valor->totalamountlessfreight,
                        'valorSemFrete'=>$valor->totalamountlessfreight,
                        'valorDoFrete'=>$valor->freightamount,
                        'valorDoFrete'=>$valor->freightamount,
                        'totalImpostos'=>$valor->totaltax_base,
                        'valorTotal'=>$valor->totalamount_base,
                        'proprietario' => isset($valor->owninguser) ? $valor->owninguser->fullname : ''
                    );
                }

                echo json_encode(array('code' => $faturas->code, 'faturas' => $resposta));
            }else{
                echo json_encode(array('code' => $faturas->code, 'error' => $faturas->value));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('code' => 500, 'error' => $th));
        }
    }


    function ajax_novo_contato_associado() {
        try {
            $documento = $this->input->post('documento');
            $email = $this->input->post('email');
            $funcao = $this->input->post('funcao');
            $nome = $this->input->post('nome');
            $telefone = $this->input->post('telefone');

            $id = $this->cliente->novo_contato_associado($documento, $nome, $funcao, $email, $telefone);
            echo json_encode(['status' => 1, 'id' => $id]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => -1,
                'mensagem' => $e->getMessage()
            ]);
        }
    }


    function ajax_listar_contatos_associados($documento) {
        try {
            $contatos = $this->cliente->listar_contatos_associados($documento);

            $contatos_res = [];
            
            foreach ($contatos as $contato) {
                $telefone = '';
                if (isset($contato->telefones[0]->telefone)) {
                    $telefone = $contato->telefones[0]->telefone;
                }
                $email = '';
                if (isset($contato->emails[0]->email)) {
                    $email = $contato->emails[0]->email;
                }
                $contatos_res[] = [
                    'id'        => $contato->id,
                    'nome'      => $contato->nome,
                    'funcao'    => $contato->funcao,
                    'telefone'  => $telefone,
                    'email'     => $email,
                ];
            }

            $resposta = [
                'status' => 1,
                'contatos' => $contatos_res
            ];
            echo json_encode($resposta);
        } catch (Exception $e) {
            echo json_encode([
                'status' => -1,
                'mensagem' => $e->getMessage()
            ]);
        }
    }
    
    
    function ajax_atualizar_contato_associado() {
        try {
            $id_contato = $this->input->post('id');
            $email = $this->input->post('email');
            $funcao = $this->input->post('funcao');
            $nome = $this->input->post('nome');
            $telefone = $this->input->post('telefone');

            if (empty($email) && empty($telefone)) {
                throw new Exception('Os contatos devem manter ao menos um meio de contato contato (email ou telefone)');
            }

            $mensagem = '';
            $this->cliente->atualizar_contato_associado($id_contato, $nome, $funcao);
                
            $telefones = $this->cliente->listar_telefones_contato_associado($id_contato);
            
            if (count($telefones)) {
                $this->cliente->atualizar_telefone_contato_associado($telefones[0]->id, $telefone);
            } else {
                $this->cliente->adicionar_telefone_contato_associado($id_contato, $telefone);
            }

            $emails = $this->cliente->listar_emails_contato_associado($id_contato);
            if (count($emails)) {
                $this->cliente->atualizar_email_contato_associado($emails[0]->id, $email);
            } else {
                $this->cliente->adicionar_email_contato_associado($id_contato, $email);
            }

            echo json_encode([
                'status' => 1,
                'mensagem' => $mensagem
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => -1,
                'mensagem' => $e->getMessage()
            ]);
        }
    }
    
    
    function ajax_excluir_contato_associado() {
        try {
            $id = $this->input->post('id');

            $status = $this->cliente->excluir_contato_associado($id);
            echo json_encode(['status' => $status]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => -1,
                'mensagem' => $e->getMessage()
            ]);
        }
    }
    public function auditoria(){
        $this->auth->is_allowed('vis_auditoriaomnilink');
        $para_view['titulo'] = 'Suporte Omnilink - Auditoria';
        $para_view['load'] = ["buttons_html5","datatable_responsive"];

        $this->load->view('fix/header-new' , $para_view);
        $this->load->view('webdesk/auditoria_sac_omnilink');
        $this->load->view('fix/footer_new');
    }


    public function ajax_get_auditoria(){
        try {
            $dados = $this->input->post();
            $where = array(
                'data_cadastro >= ' => $dados['inicio'].' 00:00:00', 
                'data_cadastro <= ' => $dados['fim'].' 23:59:59', 
            );
            $auditoria = $this->painelOmnilink->get_auditoria($where);
            $resposta = array();
            foreach ($auditoria as $key => $audit) {
                $resposta[] = array(
                    'id' => $audit['id'],
                    'email_usuario' => $audit['email_usuario'],
                    'data_cadastro' => $audit['data_cadastro'],
                    'clause' => $audit['clause'],
                    'campo' => $audit['campo'],
                    'valor_antigo' => $audit['valor_antigo'],
                    'valor_novo' => $audit['valor_novo'],
                );
            }

            echo json_encode(array(
                'status' => 1,
                'dados' => $resposta,
            ));
        } catch (\Throwable $th) {
            echo json_encode(array(
                'status' => 0,
                'erro' => $th
            ));
        }
    }

    /**
     * Busca as providências do cliente
     */
    public function ajax_get_providencias($id_cliente){
        try {
            if(isset($id_cliente)){

                $search = $this->input->post("search")['value'];
                if($search != null){
                    //where caso haja nenhuma busca específica na tabela, busca o campo de nome e data de criação
                    $api_request_parameters = array(
                        '$filter' => "(contains(tz_name, '{$search}') and _tz_accountid_value eq {$id_cliente} or _tz_contactid_value eq {$id_cliente}  )",
                        '$orderby' => 'createdon desc'
                    );
                }else{
                    //where caso não haja nenhuma busca específica
                    $api_request_parameters = array(
                        '$filter' => "(_tz_accountid_value eq {$id_cliente}) or (_tz_contactid_value eq {$id_cliente})",
                        '$orderby' => 'createdon desc'
                    );
                }

                //faz a requisição 
                $providencias = $this->sac->get('tz_providenciases', $api_request_parameters);
                if($providencias->code == 200){
                    $resposta = array();
                    $values = $providencias->data->value;
                    
                    //verifica se existem mais que 10 ocorrências no retorno
                    if(count($providencias->data->value) > 10){

                        //pega a pagina para fazer a paginação correta
                        $primeiroIndicePagina = intval($this->input->post("start"));

                        //pega os 10 itens da página
                        for ($i = $primeiroIndicePagina; $i <= count($providencias->data->value); $i++){
                            array_push($resposta, array(
                                'tz_providenciasid' => $values[$i]->tz_providenciasid,
                                'tz_name' => $values[$i]->tz_name,
                                'createdon' => $values[$i]->createdon,
                                'statecode' => $values[$i]->statecode,
                                'acao' => $this->getButtonsProvidencia($values[$i]->tz_providenciasid)
                            ));

                            $arrayDadosOcorrencia[] = $this->jsonOcorrencias[$i];
                            
                            //pega os 10 próximos índices ou até chegar o fim do array caso não tenha mais que 10 índices
                            if($i >= ($primeiroIndicePagina + 10) || $i == count($providencias->data->value))
                                break;
 
                        }

                    }else{
                        
                        //faz um foreach para pegar todos os valores já que existem menos que 10
                        foreach ($values as $key => $value) {
                            array_push($resposta, array(
                                'tz_providenciasid' => $value->tz_providenciasid,
                                'tz_name' => $value->tz_name,
                                'createdon' => $value->createdon,
                                'statecode' => $value->statecode,
                                'acao' => $this->getButtonsProvidencia($value->tz_providenciasid)
                            ));
                        }
                    }
                
                echo json_encode(
                    array(
                        'status'            => 200,
                        'providencias'      => $resposta,
                        "recordsTotal"      => 10,
                        "recordsFiltered"   => count($providencias->data->value)));
                }else{
                    echo json_encode(
                        array(
                            'status'    => $providencias->code ,
                            'erro'      => $providencias->data->error
                        )
                    );
                }
            }
        } catch (\Throwable $th) {
            echo json_encode(array(
                'status' => 500,
                'erro' => $th
            ));
        }
    }

    /**
     * Verificar se o cliente possui providência, essa função serve para o alert ao buscar o cliente
     */
    public function verificar_providencias($id_cliente){
        try {
            $api_request_parameters = array(
                '$select' => 'tz_name',
                '$filter' => "(_tz_accountid_value eq {$id_cliente})",
                '$top' => 1
            );

            //faz a requisição 
            $providencias = $this->sac->get('tz_providenciases', $api_request_parameters);
            $checa_providencia = $providencias->data;            
            
            if(count($checa_providencia->value) == 0){
                $api_request_parameters = array(
                    '$select' => 'tz_name',
                    '$filter' => "(_tz_contactid_value eq {$id_cliente})",
                    '$top' => 1
                );   

                //faz a requisição
                $providencias = $this->sac->get('tz_providenciases', $api_request_parameters);
            }

            isset($providencias->data->value[0]) ? $retorno = true : $retorno = false; 
            return $retorno;

        } catch (\Throwable $th) {
            return false;
        }
    }

    public function ajax_buscar_providencia($providenciaId){
        try {
            $api_request_parameters = array(
                '$select' => 'tz_ddd1_pessoa1,tz_ddd1_pessoa2,tz_ddd1_pessoa3,tz_ddd1_pessoa4,tz_ddd1_pessoa5,tz_ddd1_pessoa6,tz_ddd1_pessoa7,tz_ddd1_pessoa8,tz_ddd2_pessoa1,tz_ddd2_pessoa2,tz_ddd2_pessoa3,tz_ddd2_pessoa4,tz_ddd2_pessoa5,tz_ddd2_pessoa6,tz_ddd2_pessoa7,tz_ddd2_pessoa8,tz_name,tz_pergunta,tz_pessoa_1,tz_pessoa_2,tz_pessoa_3,tz_pessoa_4,tz_pessoa_5,tz_pessoa_6,tz_pessoa_7,tz_pessoa_8,tz_providenciasid,tz_resposta,tz_telefone1_pessoa1,tz_telefone1_pessoa2,tz_telefone1_pessoa3,tz_telefone1_pessoa4,tz_telefone1_pessoa5,tz_telefone1_pessoa6,tz_telefone1_pessoa7,tz_telefone1_pessoa8,tz_telefone2_pessoa1,tz_telefone2_pessoa2,tz_telefone2_pessoa3,tz_telefone2_pessoa4,tz_telefone2_pessoa5,tz_telefone2_pessoa6,tz_telefone2_pessoa7,tz_telefone2_pessoa8,statecode,statuscode',
                '$filter' => "tz_providenciasid eq {$providenciaId}"
            );

            $providencia = $this->sac->get('tz_providenciases', $api_request_parameters);
            if($providencia->code == 200){
                $value = (array)$providencia->data->value[0];
                // Remore atributos que não serão usados
                unset($value['@odata.etag']);

                $resposta = $value;

                echo json_encode(array('status' => 1, 'providencia' => $resposta));
            }else{
                echo json_encode(array('status' => 0, 'erro' => $providencia->data));
            }
        } catch (\Throwable $th) {
            echo json_encode(array(
                'status' => 0,
                'erro' => $th
            ));
        }
    }
    /**
     * Cadastrar providência
     */
    public function ajax_cadastrar_providencia(){
        try {
            $dados = $this->input->post();
            if(!isset($dados['idCliente']) || $dados['idCliente'] == ''){
                echo json_encode(array('status' => 0, 'erro' => 'Não foi possível obter o id do cliente!'));
            }
            elseif(!isset($dados['clientEntity']) || $dados['clientEntity'] == ''){
                echo json_encode(array('status' => 0, 'erro' => 'Não foi possível obter o tipo do cliente!'));
            }else{
                $idCliente = $dados['idCliente'];
                // remove o id do cliente
                unset($dados['idCliente']);
                // Remove o id da providência caso esteja setado
                if(isset($dados['tz_providenciasid'])) unset($dados['tz_providenciasid']);

                $clientEntity = $dados['clientEntity'];
                unset($dados['clientEntity']);
                if($clientEntity == 'accounts'){
                    $dados['tz_accountid@odata.bind'] =  '/accounts('.$idCliente.')';
                }else{
                    $dados['tz_contactid@odata.bind'] =  '/contacts('.$idCliente.')';
                }

                if(isset($dados['tz_pergunta'])){
                    $dados['tz_name'] = 'Perg/Resp: '.$dados['tz_pergunta'];
                }
                 
                $response = $this->sac->post('tz_providenciases', $dados);
                if($response->code == 201){
                    echo json_encode($this->getRespostaProvidencia($response));
                }else{
                    echo json_encode(array('status' => 0, 'erro' => 'Erro ao cadastrar providência!'));
                }
                
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 0, 'erro' => $th));
        }
    }
    /**
     * Editar providência
     */
    public function ajax_editar_providencia(){
        try {
            $dados = $this->input->post();
            $providenciasid = $dados['tz_providenciasid'];
            // Remove id da providencia do array de dados
            unset($dados['tz_providenciasid']);
            if(isset($dados['tz_pergunta'])){
                $dados['tz_name'] = 'Perg/Resp: '.$dados['tz_pergunta'];
            }
            $response = $this->sac->patch('tz_providenciases', $providenciasid, $dados);
            
            if($response->code == 200){                
                echo json_encode($this->getRespostaProvidencia($response));
            }else{
                echo json_encode(array('status' => 0, 'erro' => $response->data));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 0, 'erro' => $th));
        }
    }

    //FUCAO BUSCA DE DADOS PARA PEDF

    public function ajax_get_base_instalada(){

        $dadosPost = $this->input->get();

        $this->load->helper("util_helper");
        
        $data = baseInstalada($dadosPost['numeroSerie'], $dadosPost['documento'], $dadosPost['email']);
    
        $objecto_data = json_decode($data);

        echo $objecto_data;

        return $objecto_data;
    
    }

    //enviar email da fixa de ativaçao
    public function enviaEmailfa(){
		if ($this->input->post()) {			
			$dados = (object) array(
				'mensagem' => array(
					'remetente' => "noreply@shownet.com.br ",
					'destinatarios' => [$this->input->post('Email')],
					'assunto' => 'Ficha de Ativação do Cliente',
					'corpo' =>'Seu numero de Série é:  '. $this->input->post()
				),
				);
			API_Helper::post('shownet/email/enviar', $dados);
		}
	}
    
    /**
     * Remover Providência
     */
    public function ajax_remover_providencia($providenciaId){
        try {
            if(isset($providenciaId)){
                $response = $this->sac->delete('tz_providenciases',$providenciaId);
                if($response->code == 204){
                    echo json_encode(array('status'=> 1, 'msg' => 'Providência excluida com sucesso!'));
                }else{
                    echo json_encode(array('status'=> 1, 'msg' => 'Erro ao excluir a proidência!'));
                }
            }else{
                echo json_encode(array('status' => 0, 'erro' => "Não foi possível obter o ID da providência"));
            }
            
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 0, 'erro' => $th));
        }
    }

    /**
     * Altera o status de uma providência
     */
    public function ajax_mudar_status_providencia(){
        try {
            $providenciasid = $this->input->post('idProvidencia');
            $newStateCode = $this->input->post('newStateCode');
            
            $response = $this->sac->patch('tz_providenciases', $providenciasid, array(
                'statecode' => intval($newStateCode)
            ));
            if($response->code == 200){
                $value = $response->data;
                echo json_encode(
                    array(
                        'status' => 1,
                        'providencia' => array(
                            'tz_providenciasid' => $value->tz_providenciasid,
                            'tz_name' => $value->tz_name,
                            'createdon' => $value->createdon,
                            'statecode' => $value->statecode,
                            'acao' => $this->getButtonsProvidencia($value->tz_providenciasid)       
                    )
                ));
            }else{
                echo json_encode(array(
                    'status' => 0,
                    'erro' => 'Erro ao mudar status da providência!'
                ));
            }
        } catch (\Throwable $th) {
            echo json_encode(array(
                'status' => 0,
                'erro' => $th
            ));
        }
        
        
    }

    /**
     * Retorna html dos botões que serão exibidos na tabela de providência
     */
    private function getButtonsProvidencia($tz_providenciasid){
        return '<button class="btn btn-primary btn-body-datatable" onclick="visualizarProvidencia(this,\''.$tz_providenciasid.'\')" title="Visualizar/Editar Providência"><i class="fa fa-eye" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-body-datatable" onclick="removerProvidencia(this,\''.$tz_providenciasid.'\')" title="Remover Providência"><i class="fa fa-trash" aria-hidden="true"></i></button>';
    }

    private function getRespostaProvidencia($response){
        if(isset($response)){
            $resposta = (array) $response->data;
            unset($resposta['@odata.context']);
            unset($resposta['@odata.etag']);
            $resposta['acao'] = $this->getButtonsProvidencia($resposta['tz_providenciasid']);
            return (array('status' => 1, 'providencia' => $resposta));
        }else{
            return (array('status' => 0, 'erro' => 'Erro ao converter resposta da providência'));
        }
    }

    /**
     * Função utilizada para filtrar os dados do select
     * @param String $url - url da api que será feita a busca
     */
    public function ajax_search_select($url){

        if(isset($url) && $url != ''){
            $search = $this->input->get('q');
            $id = $this->input->get('id');
            $name = $this->input->get('name');
            $array_select = array();
            $resposta = [
                'results' => [],
                'pagination' => [
                    'more' => false,
                ]
            ];
            if(isset($id) && $id != ""){
                array_push($array_select, $id);
            }
            if(isset($name) && $name != ""){
                array_push($array_select, $name);
                
                if(strlen($search)){
                    $api_request_parameters['$filter'] = "contains({$name}, '{$search}')";
                }
            }

            if(count($array_select) > 0){
                $select = implode(",", $array_select);
                $api_request_parameters['$select'] = $select;
            }
            
            $response = $this->sac->get($url,$api_request_parameters);
            
            $resposta = array();
            if (!empty($response->data->value) && is_array($response->data->value)) {
                foreach($response->data->value as $r) {
                    $r = (Array) $r;

                    $resposta['results'][] = [
                        'id' => $r[$id],
                        'text' => $r[$name],
                    ];
                }
            }
            echo json_encode($resposta);
        }else{
            echo json_encode($resposta = [
                'results' => [],
                'pagination' => [
                    'more' => false,
                ]
            ]);
        }
    }
    public function ajax_adicionar_alteracao_contrato(){
        try {
            $dados = $this->input->post();
            if(isset($dados)){
                $body = array(
                    'tz_motivoid@odata.bind' => '/tz_motivos('.$dados["alteracao_contrato_tz_motivoid"].')',
                    'statuscode' => $dados['alteracao_contrato_statuscode'],
                    'tz_incidentid@odata.bind' => '/incidents('.$dados["alteracao_contrato_tz_incidentid"].')'
                );

                $response = $this->sac->post('tz_manutencao_contrato_lotes', $body);

                if($response->code == 201){
                    echo json_encode(array('status' => 1, 'data' => $response->data));
                }else{
                    echo json_encode(array('status' => 0, 'erro' => $response->data));
                }
            
            }

        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array('status' => 0, 'erro' => 'Erro ao cadastrar alteração de contrato! '. $th->getMessage()));
        }
    }
    /**
     * Função que retorna as informações do item de contrato para ser exibido na tela
     */
    public function ajax_get_informacoes_item_de_contrato($idItemDeContrato){
        try {
            $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
                        <entity name="serviceappointment">
                            <attribute name="tz_name" alias="codigo"/>        
                        </entity>
                    </fetch>';
                            
                
            $xml = str_replace('{id}', $id, $xml);
            $xml = urlencode($xml);

            $api_request_parameters = array('fetchXml'=> $xml);
            $item = $this->sac->get("tz_item_contrato_vendas", $api_request_parameters);

            $retorno = array();
            $entity = "tz_item_contrato_vendas";
            
            $item_contrato_parte_1 = $this->sac->get($entity, array(
                '$filter'=>"tz_item_contrato_vendaid eq {$idItemDeContrato}",
                '$expand'=>'tz_plataformaid($select=tz_name),tz_cenario_vendaid($select=tz_name),tz_canal_vendaid($select=tz_name),tz_modeloid($select=tz_name),tz_tecnologiaid($select=tz_name),tz_plano_linkerid($select=name),tz_tipo_veiculoid($select=tz_name),tz_frota_afid($select=tz_frotaafid,tz_name),tz_modeloid($select=tz_modeloid,tz_name)',
            ));
            
            // id_item_de_contrato
            if($item_contrato_parte_1->code == 200){
                $value_parte_1 = $item_contrato_parte_1->data->value[0];
                
                // Salva Inputs
                $retorno['inputs'] = array(
                    "id_item_de_contrato" => isset($value_parte_1->tz_item_contrato_vendaid) ? $value_parte_1->tz_item_contrato_vendaid : null,
                    "tz_name" => isset($value_parte_1->tz_name) ? $value_parte_1->tz_name : null,
                    "tz_numero_af" => isset($value_parte_1->tz_numero_af) ? $value_parte_1->tz_numero_af : null,
                    "tz_codigo_item_contrato" => isset($value_parte_1->tz_codigo_item_contrato) ? $value_parte_1->tz_codigo_item_contrato : null,
                    "tz_integrar_gestor" => isset($value_parte_1->tz_integrar_gestor) ? ($value_parte_1->tz_integrar_gestor ? 'true' : 'false') : null,
                    "tz_codigo_gestor" => isset($value_parte_1->tz_codigo_gestor) ? $value_parte_1->tz_codigo_gestor : null, 
                    "tz_numero_serie_modulo_principal" => isset($value_parte_1->tz_numero_serie_modulo_principal) ? $value_parte_1->tz_numero_serie_modulo_principal : null,
                    "tz_numero_serie_antena_satelital" => isset($value_parte_1->tz_numero_serie_antena_satelital) ? $value_parte_1->tz_numero_serie_antena_satelital : null,
                    "tz_modelo_tipo_informado_ativacao" => isset($value_parte_1->tz_modelo_tipo_informado_ativacao) ? $value_parte_1->tz_modelo_tipo_informado_ativacao : null,
                    "tz_entrada_devido_a" => isset($value_parte_1->{'tz_entrada_devido_a@OData.Community.Display.V1.FormattedValue'}) ? $value_parte_1->{'tz_entrada_devido_a@OData.Community.Display.V1.FormattedValue'}: null,
                    "tz_duracao_comodato" => $value_parte_1->tz_duracao_comodato ? $value_parte_1->tz_duracao_comodato : null,
                    "tz_data_alteracao_status" => isset($value_parte_1->tz_data_alteracao_status) ? (new DateTime($value_parte_1->tz_data_alteracao_status))->format('Y-m-d') : null,
                    "tz_data_entrada" => isset($value_parte_1->tz_data_entrada) ? (new DateTime($value_parte_1->tz_data_entrada))->format('Y-m-d') : null,
                    "tz_data_expedicao" => isset($value_parte_1->tz_data_expedicao) ? (new DateTime($value_parte_1->tz_data_expedicao))->format('Y-m-d') : null,
                    "tz_data_ativacao" => isset($value_parte_1->tz_data_ativacao) ? (new DateTime($value_parte_1->tz_data_ativacao))->format('Y-m-d') : null,
                    "tz_data_inicio_suspensao" => isset($value_parte_1->tz_data_inicio_suspensao) ? (new DateTime($value_parte_1->tz_data_inicio_suspensao))->format('Y-m-d') : null,
                    "tz_data_termino_suspensao" => isset($value_parte_1->tz_data_termino_suspensao) ? (new DateTime($value_parte_1->tz_data_termino_suspensao))->format('Y-m-d') : null,
                    "tz_data_vencimento_comodato" => isset($value_parte_1->tz_data_vencimento_comodato) ? (new DateTime($value_parte_1->tz_data_vencimento_comodato))->format('Y-m-d') : null,
                    "tz_data_termino" => isset($value_parte_1->tz_data_termino) ? (new DateTime($value_parte_1->tz_data_termino))->format('Y-m-d') : null,
                    "tz_data_termino_fidelidade" => isset($value_parte_1->tz_data_termino_fidelidade) ? (new DateTime($value_parte_1->tz_data_termino_fidelidade))->format('Y-m-d') : null,
                    "tz_data_desinstalacao" => isset($value_parte_1->tz_data_desinstalacao) ? (new DateTime($value_parte_1->tz_data_desinstalacao))->format('Y-m-d') : null,
                    "tz_data_aniversario_contrato" => isset($value_parte_1->tz_data_aniversario_contrato) ? (new DateTime($value_parte_1->tz_data_aniversario_contrato))->format('Y-m-d') : null,
                    "tz_data_inicio_garantia" => isset($value_parte_1->tz_data_inicio_garantia) ? (new DateTime($value_parte_1->tz_data_inicio_garantia))->format('Y-m-d') : null,
                    "tz_data_fim_garantia" => isset($value_parte_1->tz_data_fim_garantia) ? (new DateTime($value_parte_1->tz_data_fim_garantia))->format('Y-m-d') : null,
                    "tz_data_vencimento_demonstracao" => isset($value_parte_1->tz_data_vencimento_demonstracao) ? (new DateTime($value_parte_1->tz_data_vencimento_demonstracao))->format('Y-m-d') : null,
                    "tz_data_fim_carencia" => isset($value_parte_1->tz_data_fim_carencia) ? (new DateTime($value_parte_1->tz_data_fim_carencia))->format('Y-m-d') : null,
                    "tz_data_proxima_renovacao" => isset($value_parte_1->tz_data_proxima_renovacao) ? (new DateTime($value_parte_1->tz_data_proxima_renovacao))->format('Y-m-d') : null,
                    "tz_data_ultimo_reajuste" => isset($value_parte_1->tz_data_ultimo_reajuste) ? (new DateTime($value_parte_1->tz_data_ultimo_reajuste))->format('Y-m-d') : null,                
                    "tz_percentual_ultimo_reajuste" => isset($value_parte_1->tz_percentual_ultimo_reajuste) ? $value_parte_1->tz_percentual_ultimo_reajuste : null,
                    "tz_qtde_meses_isento_reajuste" => isset($value_parte_1->tz_qtde_meses_isento_reajuste) ? $value_parte_1->tz_qtde_meses_isento_reajuste : null,
                    "tz_data_carencia_reajuste" => isset($value_parte_1->tz_data_carencia_reajuste) ? $value_parte_1->tz_data_carencia_reajuste : null,
                    "tz_valor_deslocamento_km" => isset($value_parte_1->tz_valor_deslocamento_km) ? $value_parte_1->tz_valor_deslocamento_km : null,
                    "tz_taxa_visita" => isset($value_parte_1->tz_taxa_visita) ? $value_parte_1->tz_taxa_visita : null,
                    "tz_descricao" => isset($value_parte_1->tz_descricao) ? $value_parte_1->tz_descricao : null,
                    "tz_modalidade_venda" => isset($value_parte_1->tz_modalidade_venda) ? $value_parte_1->tz_modalidade_venda : null,
                    "tz_local_instalacao" => isset($value_parte_1->tz_local_instalacao) ? $value_parte_1->tz_local_instalacao : null,              
                    "tz_chassi" => isset($value_parte_1->tz_chassi) ? $value_parte_1->tz_chassi : null,              
                    "tz_venda_efetuada" => isset($value_parte_1->tz_venda_efetuada) ? $value_parte_1->tz_venda_efetuada : null,
                    "tz_periodo_degustacao" => isset($value_parte_1->tz_periodo_degustacao) ? $value_parte_1->tz_periodo_degustacao : null,
                    "tz_contrato_agrupador" => isset($value_parte_1->tz_contrato_agrupador) ? $value_parte_1->tz_contrato_agrupador : null,
                    "tz_integra_contrato" => isset($value_parte_1->tz_integra_contrato) ? ($value_parte_1->tz_integra_contrato ? 'true' : 'false') : null,
                    "tz_sequencial_item_contrato" => isset($value_parte_1->tz_sequencial_item_contrato) ? $value_parte_1->tz_sequencial_item_contrato : null,
                    "tz_valor_licenca" => isset($value_parte_1->tz_valor_licenca) ? $value_parte_1->tz_valor_licenca : null,
                    "tz_reenviar_apolice_graber" => isset($value_parte_1->tz_reenviar_apolice_graber) ? $value_parte_1->tz_reenviar_apolice_graber : null,
                    "tz_tipo_contrato" => isset($value_parte_1->tz_tipo_contrato) ? $value_parte_1->tz_tipo_contrato : null,
                    "tz_status_item_contrato" => isset($value_parte_1->tz_status_item_contrato) ? $value_parte_1->tz_status_item_contrato : null,
                    "tz_cancelado_devido_a" => isset($value_parte_1->tz_cancelado_devido_a) ? $value_parte_1->tz_cancelado_devido_a : null,
                //novos campos
                
                    "tz_produtoid" => isset($value_parte_1->tz_produtoid) ? $value_parte_1->tz_produtoid : null,
                   
                    "siteid" => isset($value_parte_1->siteid) ? $value_parte_1->siteid : null,
                    "tz_cliente_pjid" => isset($value_parte_1->tz_cliente_pjid) ? $value_parte_1->tz_cliente_pjid : null,

                    "tz_modelo_ativacao" => isset($value_parte_1->tz_modelo_ativacao) ? $value_parte_1->tz_modelo_ativacao : null,
                    "tz_marcaid" => isset($value_parte_1->tz_marcaid) ? $value_parte_1->tz_marcaid : null,
                    "tz_ano_modelo" => isset($value_parte_1->tz_ano_modelo) ? $value_parte_1->tz_ano_modelo : null,
                    "tz_cor" => isset($value_parte_1->tz_cor) ? $value_parte_1->tz_cor : null,
                    "tz_numero_serie" => isset($value_parte_1->tz_numero_serie) ? $value_parte_1->tz_numero_serie : null,
                    "tz_placa" => isset($value_parte_1->tz_placa) ? $value_parte_1->tz_placa : null,
                    "tz_linha1" => isset($value_parte_1->tz_linha1) ? $value_parte_1->tz_linha1 : null,
                    "tz_operadora1" => isset($value_parte_1->tz_operadora1) ? $value_parte_1->tz_operadora1 : null,
                    "tz_linha2" => isset($value_parte_1->tz_linha2) ? $value_parte_1->tz_linha2 : null,
                    "tz_operadora2" => isset($value_parte_1->tz_operadora2) ? $value_parte_1->tz_operadora2 : null,
                    "tz_modelo_antena" => isset($value_parte_1->tz_modelo_antena) ? $value_parte_1->tz_modelo_antena : null,
                    "tz_versao_firmware" => isset($value_parte_1->tz_versao_firmware) ? $value_parte_1->tz_versao_firmware : null,

                    "tz_ignicao"  => isset($value_parte_1->tz_ignicao) ? $value_parte_1->tz_ignicao : null,
                    "tz_boto_panico"  => isset($value_parte_1->tz_boto_panico) ? $value_parte_1->tz_boto_panico : null,
                    "tz_painel"  => isset($value_parte_1->tz_painel) ? $value_parte_1->tz_painel : null,
                    "tz_painel_read_switch"  => isset($value_parte_1->tz_painel_read_switch) ? $value_parte_1->tz_painel_read_switch : null,
                    "tz_painel_micro_switch" => isset($value_parte_1->tz_painel_micro_switch) ? $value_parte_1->tz_painel_micro_switch : null,
                    "tz_portas_cabine" => isset($value_parte_1->tz_portas_cabine) ? $value_parte_1->tz_portas_cabine : null,
                    "tz_bau_traseiro" => isset($value_parte_1->tz_bau_traseiro) ? $value_parte_1->tz_bau_traseiro : null,
                    "tz_bau_lateral" => isset($value_parte_1->tz_bau_lateral) ? $value_parte_1->tz_bau_lateral : null,
                    "tz_bau_intermediario" => isset($value_parte_1->tz_bau_intermediario) ? $value_parte_1->tz_bau_intermediario : null,
                    "tz_engate_aspiral" => isset($value_parte_1->tz_engate_aspiral) ? $value_parte_1->tz_engate_aspiral : null,
                    "tz_trava_quinta_roda_inteligente" => isset($value_parte_1->tz_trava_quinta_roda_inteligente) ? $value_parte_1->tz_trava_quinta_roda_inteligente : null,
                    "tz_bloqueador_combustivel_inteligente" => isset($value_parte_1->tz_bloqueador_combustivel_inteligente) ? $value_parte_1->tz_bloqueador_combustivel_inteligente : null,
                    "tz_sensor_configuravel1_1" => isset($value_parte_1->tz_sensor_configuravel1_1) ? $value_parte_1->tz_sensor_configuravel1_1 : null,
                    "tz_sensor_configuravel2_1" => isset($value_parte_1->tz_sensor_configuravel2_1) ? $value_parte_1->tz_sensor_configuravel2_1 : null,
                    "tz_sensor_configuravel3_1" => isset($value_parte_1->tz_sensor_configuravel3_1) ? $value_parte_1->tz_sensor_configuravel3_1 : null,
                    "tz_sensor_configuravel4_1" => isset($value_parte_1->tz_sensor_configuravel4_1) ? $value_parte_1->tz_sensor_configuravel4_1 : null,
                    "tz_sirene"  => isset($value_parte_1->tz_sirene) ? $value_parte_1->tz_sirene : null,

                    "tz_setas_pulsantes"  => isset($value_parte_1->tz_setas_pulsantes) ? $value_parte_1->tz_setas_pulsantes : null,
                    "tz_setas_continuas"  => isset($value_parte_1->tz_setas_continuas) ? $value_parte_1->tz_setas_continuas : null,
                    "tz_bloqueio_selenoide"  => isset($value_parte_1->tz_bloqueio_selenoide) ? $value_parte_1->tz_bloqueio_selenoide : null,
                    "tz_bloqueio_eletronico"  => isset($value_parte_1->tz_bloqueio_eletronico) ? $value_parte_1->tz_bloqueio_eletronico : null,
                    "tz_tipo_trava_bau"  => isset($value_parte_1->tz_tipo_trava_bau) ? $value_parte_1->tz_tipo_trava_bau : null,
                    "tz_trava_bau_traseira"  => isset($value_parte_1->tz_trava_bau_traseira) ? $value_parte_1->tz_trava_bau_traseira : null,
                    "tz_trava_bau_lateral"  => isset($value_parte_1->tz_trava_bau_lateral) ? $value_parte_1->tz_trava_bau_lateral : null,
                    "tz_trava_bau_intermediario"  => isset($value_parte_1->tz_trava_bau_intermediario) ? $value_parte_1->tz_trava_bau_intermediario : null,
                    "tz_trava_quinta_roda"  => isset($value_parte_1->tz_trava_quinta_roda) ? $value_parte_1->tz_trava_quinta_roda : null,
                    "tz_engate_eletronico"  => isset($value_parte_1->tz_engate_eletronico) ? $value_parte_1->tz_engate_eletronico : null,
                    "tz_temperatura"  => isset($value_parte_1->tz_temperatura) ? $value_parte_1->tz_temperatura : null,
                    "tz_bloqueador_can"  => isset($value_parte_1->tz_bloqueador_can) ? $value_parte_1->tz_bloqueador_can : null,

                    "tz_teclado"            => isset($value_parte_1->tz_teclado) ? $value_parte_1->tz_teclado : null,
                    "tz_bateria_backup"     => isset($value_parte_1->tz_bateria_backup) ? $value_parte_1->tz_bateria_backup : null,
                    "tz_cofre_eletronico"   => isset($value_parte_1->tz_cofre_eletronico) ? $value_parte_1->tz_cofre_eletronico : null,
                    "tz_teclado_multimidia" => isset($value_parte_1->tz_teclado_multimidia) ? $value_parte_1->tz_teclado_multimidia : null,
                    "tz_telemetria"         => isset($value_parte_1->tz_telemetria) ? $value_parte_1->tz_telemetria : null,
                    "tz_conversor_tacografo"=> isset($value_parte_1->tz_conversor_tacografo) ? $value_parte_1->tz_conversor_tacografo : null,   
                );

                // Array que guardará o valor dos selects
                $array_selects = array();

                $array_selects["tz_modeloid"] = array(
                    "id" => isset($value_parte_1->tz_modeloid->tz_modeloid) ? $value_parte_1->tz_modeloid->tz_modeloid : null,
                    "text" => isset($value_parte_1->tz_modeloid->name) ? $value_parte_1->tz_modeloid->name : ''
                );
                $array_selects["tz_plano_linkerid"] = array(
                    "id" => isset($value_parte_1->_tz_plano_linkerid_value) ? $value_parte_1->_tz_plano_linkerid_value : null,
                    "text" => isset($value_parte_1->tz_plano_linkerid->name) ? $value_parte_1->tz_plano_linkerid->name : ''
                );
    
                $array_selects["tz_plataformaid"] = array(
                    "id" => isset($value_parte_1->_tz_plataformaid_value) ? $value_parte_1->_tz_plataformaid_value : null,
                    "text" => isset($value_parte_1->tz_plataformaid->tz_name) ? $value_parte_1->tz_plataformaid->tz_name : ''
                );
                $array_selects["tz_cenario_vendaid"] = array(
                    "id" => isset($value_parte_1->_tz_cenario_vendaid_value) ? $value_parte_1->_tz_cenario_vendaid_value : null,
                    "text" => isset($value_parte_1->tz_cenario_vendaid->tz_name) ? $value_parte_1->tz_cenario_vendaid->tz_name : ''
                );
                $array_selects["tz_tipo_veiculoid"] = array(
                    "id" => isset($value_parte_1->_tz_tipo_veiculoid_value) ? $value_parte_1->_tz_tipo_veiculoid_value : null,
                    "text" => isset($value_parte_1->tz_tipo_veiculoid->tz_name) ? $value_parte_1->tz_tipo_veiculoid->tz_name : ''
                );
                $array_selects["tz_tecnologiaid"] = array(
                    "id" => isset($value_parte_1->_tz_tecnologiaid_value) ? $value_parte_1->_tz_tecnologiaid_value : null,
                    "text" => isset($value_parte_1->tz_tecnologiaid->tz_name) ? $value_parte_1->tz_tecnologiaid->tz_name : ''
                );
                $array_selects["tz_frota_afid"] = array(
                    "id" => isset($value_parte_1->tz_frota_afid->tz_frotaafid) ? $value_parte_1->tz_frota_afid->tz_frotaafid : null,
                    "text" => isset($value_parte_1->tz_frota_afid->tz_name) ? $value_parte_1->tz_frota_afid->tz_name : ''
                );
                $array_selects["tz_canal_vendaid"] = array(
                    "id" => isset($value_parte_1->_tz_canal_vendaid_value) ? $value_parte_1->_tz_canal_vendaid_value : null,
                    "text" => isset($value_parte_1->tz_canal_vendaid->tz_name) ? $value_parte_1->tz_canal_vendaid->tz_name : ''
                );
    
                if(isset($value_parte_1->_tz_item_contrato_originalid_value)){
                    $item_contrato_original = $this->sac->get($entity, array(
                        '$filter'=>"tz_item_contrato_vendaid eq {$idItemDeContrato}",
                        '$select'=>'tz_name'
                    ));
                    if($item_contrato_original->code == 200){
                        $array_selects["tz_item_contrato_originalid"] = array(
                            "id" => isset($value_parte_1->_tz_item_contrato_originalid_value) ? $value_parte_1->_tz_item_contrato_originalid_value : null,
                            "text" => isset($item_contrato_original->data->value[0]->tz_name) ? $item_contrato_original->data->value[0]->tz_name : null
                        );
                    }
                }
                
                $item_contrato_parte_2 = $this->sac->get($entity, array(
                    '$select' => '_tz_veiculoid_value,_tz_marcaid_value,_tz_providenciasid_value,_tz_motivo_alteracao_value,_tz_indice_reajusteid_value,_tz_rastreadorid_value',
                    '$filter'=>"tz_item_contrato_vendaid eq {$idItemDeContrato}",
                    '$expand'=>'tz_veiculoid($select=tz_placa),tz_marcaid($select=tz_name),tz_providenciasid($select=tz_name),tz_motivo_alteracao($select=tz_name),tz_indice_reajusteid($select=tz_name),tz_rastreadorid($select=name),tz_afid($select=tz_afid,tz_name)',
                ));
                
                if($item_contrato_parte_2->code == 200){
                    $value_parte_2 = $item_contrato_parte_2->data->value[0];
    
                    $array_selects["tz_veiculoid"] = array(
                        'id' => isset($value_parte_2->_tz_veiculoid_value) ? $value_parte_2->_tz_veiculoid_value: null,
                        'text' => isset($value_parte_2->tz_veiculoid->tz_placa) ? $value_parte_2->tz_veiculoid->tz_placa: '',
                    );
                    $array_selects["tz_providenciasid"] = array(
                        'id' => isset($value_parte_2->_tz_providenciasid_value) ? $value_parte_2->_tz_providenciasid_value: null,
                        'text' => isset($value_parte_2->tz_providenciasid->tz_name) ? $value_parte_2->tz_providenciasid->tz_name: '',
                    );
                    $array_selects["tz_indice_reajusteid"] = array(
                        'id' => isset($value_parte_2->_tz_indice_reajusteid_value) ? $value_parte_2->_tz_indice_reajusteid_value: null,
                        'text' => isset($value_parte_2->tz_indice_reajusteid->tz_name) ? $value_parte_2->tz_indice_reajusteid->tz_name: '',
                    );
                    $array_selects["tz_rastreadorid"] = array(
                        'id' => isset($value_parte_2->_tz_rastreadorid_value) ? $value_parte_2->_tz_rastreadorid_value: null,
                        'text' => isset($value_parte_2->tz_rastreadorid->name) ? $value_parte_2->tz_rastreadorid->name: '',
                    );
    
                    $array_selects["tz_motivo_alteracao"] = array(
                        'id' => isset($value_parte_2->_tz_motivo_alteracao_value) ? $value_parte_2->_tz_motivo_alteracao_value: null,
                        'text' => isset($value_parte_2->tz_motivo_alteracao->tz_name) ? $value_parte_2->tz_motivo_alteracao->tz_name: '',
                    );
                    $array_selects["tz_marcaid"] = array(
                        'id' => isset($value_parte_2->_tz_marcaid_value) ? $value_parte_2->_tz_marcaid_value: null,
                        'text' => isset($value_parte_2->tz_marcaid->tz_name) ? $value_parte_2->tz_marcaid->tz_name: '',
                    );
                    $array_selects["tz_afid"] = array(
                        "id" => isset($value_parte_2->tz_afid->tz_afid) ? $value_parte_2->tz_afid->tz_afid : null,
                        "text" => isset($value_parte_2->tz_afid->tz_name) ? $value_parte_2->tz_afid->tz_name : ''
                    );
                }
    
                // Salva Selects
                $retorno['selects'] = $array_selects;
    
                // Busca alteração de contrato // tz_manutencao_contrato_lotes
                $alteracoes_contrato = $this->sac->get('tz_manutencao_contratos',array(
                    '$select' => 'tz_manutencao_contratoid,tz_name,tz_motivo_cancelamentoid,statecode,createdon,_tz_veiculoid_value,_tz_item_contratoid_value,_tz_item_contratoid_value,tz_modelo_tipo_ativacao,statuscode,statecode',
                    '$expand' => 'tz_motivo_cancelamentoid($select=tz_name),tz_veiculoid($select=tz_placa),tz_item_contratoid,tz_item_contratoid($select=tz_name)',
                    '$filter' => '_tz_item_contratoid_value eq '.$idItemDeContrato
                ));
    
                if($alteracoes_contrato->code == 200){
                    $value_alteracoes_contrato = $alteracoes_contrato->data->value;
                    $alteracoes_contrato_aux = array();
                    foreach ($value_alteracoes_contrato as $key => $alteracao) {
                        $alteracoes_contrato_aux[] = array(
                            'tz_manutencao_contratoid' => isset($alteracao->tz_manutencao_contratoid) ? $alteracao->tz_manutencao_contratoid: null,
                            'tz_name' => isset($alteracao->tz_name) ? $alteracao->tz_name: null,
                            'tz_veiculo' => isset($alteracao->tz_veiculoid->tz_placa) ? $alteracao->tz_veiculoid->tz_placa: null,
                            'tz_item_contrato' => isset($alteracao->tz_item_contratoid->tz_name) ? $alteracao->tz_item_contratoid->tz_name: null,
                            'tz_modelo_tipo_ativacao' => isset($alteracao->tz_modelo_tipo_ativacao) ? $alteracao->tz_modelo_tipo_ativacao: null,
                            'statecode' =>isset($alteracao->statecode) ? $alteracao->statecode: null,
                            'statuscode' => isset($alteracao->statuscode) ? $alteracao->statuscode: null,
                            'createdon' => isset($alteracao->createdon) ? $alteracao->createdon: null,
                            'acoes' => $this->getBtnAcoesAlteracaoDeContratos($alteracao->tz_manutencao_contratoid),
                        );
                    }
                    $retorno['alteracoes_contrato'] = $alteracoes_contrato_aux;
                }
    
                // Busca Serviços Contratados
                $servicos_contratados = $this->sac->get('tz_produto_servico_contratados', array(
                    '$filter' => "_tz_codigo_item_contratoid_value eq {$idItemDeContrato}",
                    '$select' => 'tz_name,_tz_codigo_item_contratoid_value,_tz_produtoid_value,tz_quantidade,tz_valor_contratado,_tz_classificacao_produtoid_value,_tz_grupo_receitaid_value,tz_data_inicio,tz_data_termino,tz_data_fim_carencia',
                    '$expand' => 'tz_codigo_item_contratoid($select=tz_name),tz_produtoid($select=name),tz_classificacao_produtoid($select=tz_name),tz_grupo_receitaid($select=tz_name)'
                ));
                if($servicos_contratados->code == 200){
                    $value_servicos_contratados = $servicos_contratados->data->value;
                    $servicos_contratados_aux = array();
                    foreach ($value_servicos_contratados as $key => $servico) {
                        $servicos_contratados_aux[] = array(
                            'tz_produto_servico_contratadoid' =>isset( $servico->tz_produto_servico_contratadoid) ?  $servico->tz_produto_servico_contratadoid : null,
                            'tz_name' => isset($servico->tz_name) ? $servico->tz_name : null,
                            'tz_codigo_item_contrato' => isset($servico->tz_codigo_item_contratoid->tz_name) ? $servico->tz_codigo_item_contratoid->tz_name : null,
                            'tz_produto' => isset($servico->tz_produtoid->name) ? $servico->tz_produtoid->name : null,
                            'tz_quantidade' => isset($servico->tz_quantidade) ? $servico->tz_quantidade : null,
                            'tz_valor_contratado' => isset($servico->tz_valor_contratado) ? $servico->tz_valor_contratado : null,
                            'tz_classificacao_produto' => isset($servico->tz_classificacao_produtoid->tz_name) ? $servico->tz_classificacao_produtoid->tz_name : null,
                            'tz_grupo_receita' => isset($servico->tz_grupo_receitaid->tz_name) ? $servico->tz_grupo_receitaid->tz_name : null,
                            'tz_data_inicio' => isset($servico->tz_data_inicio) ? $servico->tz_data_inicio : null,
                            'tz_data_termino' => isset($servico->tz_data_termino) ? $servico->tz_data_termino : null,
                            'tz_data_fim_carencia' => isset($servico->tz_data_fim_carencia) ? $servico->tz_data_fim_carencia : null,
                            'acoes' => $this->getButtonsAcaoServicoContratado($servico->tz_produto_servico_contratadoid)                        
                        );
                    }
    
                    $retorno['servicos_contratados'] = $servicos_contratados_aux;
                }
                
                echo json_encode(array('status' => 1, 'data' => $retorno));
            }else{
                echo json_encode(array('status' => 0, 'erro' => "O ID do item de contrato não foi informado!"));
            }
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array('status' => 0, 'erro' => "Erro ao bustar item de contrato! ". $th->getMessage()));
        }
        
        
    }

    /**
     * Função que realiza edição do item de contrato
     */
    public function ajax_editar_item_de_contrato($idItemDeContrato){
        $dados = $this->input->post();
        
        $valoresModificados = $this->sac->filtrarValoresAlterados($dados['valores_antigos'], $dados['valores_novos']);
        $validacaoServicoContratado = $this->validar_servicos_contratados($idItemDeContrato);
        if($validacaoServicoContratado['status']){
            // Retorna parametros da requisição
            $body = $this->getBodyRequestItemDeContrato($valoresModificados);
            
            if(count($body) > 0){
                $response = $this->sac->patch('tz_item_contrato_vendas', $idItemDeContrato, $body);
                if($response->code == 200){
                    $idItem = $response->data->tz_item_contrato_vendaid;
                    $retorno = $this->getItemContratoVendaPorId($idItem);
                    echo json_encode(array('status' => 1, 'data' => $retorno));
                }else{    
                    echo json_encode(array('status' => 0, 'erro' => $response->data->error ? $response->data->error->message : "Erro ao editar item de contrato!"));
                }
            }else{
                echo json_encode(array('status' => 0, 'erro' => "Para atualizar o item de contrato, altere pelo menos um valor no formulário!"));
            }
        }else{
            echo json_encode(array('status' => 0, 'erro' => $validacaoServicoContratado['message']));
        }
        
    }

    public function ajax_atualizar_status_item_de_contrato(){
        $dados = $this->input->post();
        $idItemDeContrato = $dados['tz_item_contrato_vendaid'];
        $validacaoServicoContratado = $this->validar_servicos_contratados($idItemDeContrato);
        if($validacaoServicoContratado['status']){
            $response = $this->sac->patch('tz_item_contrato_vendas', $idItemDeContrato, array(
                'statecode' => $dados['statecode']
            ));
    
            if($response->code == 200){
    
                echo json_encode(array('status' => 1, 'data' => $response->data));
    
            }else{
                $erro = isset($response->data->error) ? $response->data->error->message : 'Erro ao alterar status do Item de Contrato';
                echo json_encode(array('status' => 0, 'erro' => $erro));
            }
        }else{
            echo json_encode(array('status' => 0, 'erro' => $validacaoServicoContratado['message']));
        }
    }

    /**
     * Função que verifica se existe algum serviço contratado para realizar a edição do item de contrato.
     */
    public function validar_servicos_contratados($idItemDeContrato){
        // Busca Serviços Contratados
        $servicos_contratados = $this->sac->get('tz_produto_servico_contratados', array(
            '$filter' => "_tz_codigo_item_contratoid_value eq {$idItemDeContrato}",
            '$select' => 'tz_name,_tz_codigo_item_contratoid_value,_tz_produtoid_value,tz_quantidade,tz_valor_contratado,_tz_classificacao_produtoid_value,_tz_grupo_receitaid_value,tz_data_inicio,tz_data_termino,tz_data_fim_carencia',
            '$expand' => 'tz_codigo_item_contratoid($select=tz_name),tz_produtoid($select=name),tz_classificacao_produtoid($select=tz_name),tz_grupo_receitaid($select=tz_name)'
        ));
        if($servicos_contratados->code == 200){
            if(count($servicos_contratados->data->value) > 0){
                return array('status' => true);
            }else{
                return array('status' => false, 'message' =>"Para realizar a alteração no item de contrato, é necessário cadastrar o serviço contratado!");
            }
            
        }else{
            return array('status' => false, 'message' =>"Erro ao buscar o serviço contratado");
        }
        
    }
    /**
     * Função que realiza cadastro do item de contrato
     */
    public function ajax_adicionar_item_contrato(){
        try {
            $dados = $this->input->post();
            if(isset($dados)){
                $idCliente = $dados['idCliente'];
                unset($dados['idCliente']);
                $clientEntity = $dados['clientEntity'];
                unset($dados['clientEntity']);
                
                // Retorna parametros da requisição
                $body = $this->getBodyRequestItemDeContrato($dados);
                // Salva data de criação do contrato
                $body['tz_data_entrada'] = (new DateTime())->format('Y-m-d\TH:i:s\Z');
                $createdon = new DateTime();
                $body['createdon'] = $createdon->format('Y-m-d\TH:i:s\Z');

                // SALVA CLIENTE PELO TIPO DE ENTIDADE
                if($clientEntity == "accounts") $body["tz_cliente_pjid@odata.bind"] = '/accounts('.$idCliente.')';
                else $body["tz_cliente_pfid@odata.bind"] = '/contacts('.$idCliente.')';

                if(!isset($idCliente)){
                    exit(json_encode(array('status' => 0, 'erro' => 'Erro ao cadastrar item de contrato! Não foi possível obter o cliente.')));
                }
                elseif(!isset($clientEntity)){
                    exit(json_encode(array('status' => 0, 'erro' => 'Erro ao cadastrar item de contrato! Não foi possível obter o tipo do cliente.')));
                }else{
                                        
                    $body['tz_name'] = $this->montarNomeItemContratoVenda($body['tz_numero_af'],$body['tz_codigo_item_contrato']);

                    $response = $this->sac->post('tz_item_contrato_vendas', $body);

                    if($response->code == 201){
                        $idItem = $response->data->tz_item_contrato_vendaid;
                        $item = $this->getItemContratoVendaPorId($idItem);

                        echo json_encode(array('status' => 1, 'data' => $item));
                    }
                    else{
                        echo json_encode(array('status' => 0, 'erro' => $response->data->error ? $response->data->error->message : 'Erro ao cadastrar item de contrato!'));
                    }
                }
            }

        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array('status' => 0, 'erro' => 'Erro ao cadastrar item de contrato! '. $th->getMessage()));
        }

    }
    /**
     * Monta o nome do item de contrato com base no número da af, código item de contrato e o timestamp
     */
    private function montarNomeItemContratoVenda($tz_numero_af,$tz_codigo_item_contrato){
        // Seta nome do item de contrato
        $tz_name = array();
        $datetime = new DateTime();
        // Número da AF
        if(isset($tz_numero_af)) array_push($tz_name,$tz_numero_af);
        // Código do item de contrato
        if(isset($tz_codigo_item_contrato)) array_push($tz_name,$tz_codigo_item_contrato);
        // timestamp atual
        array_push($tz_name,$datetime->getTimestamp());
        // concatena tudo e gera o nome do item de contrato
        if(count($tz_name) > 0) {
            $tz_name = implode('-',$tz_name);
        }else{
            $tz_name = $datetime->getTimestamp();
        }
        
        return strtoupper($tz_name);
    }
    /**
     * Retorna cep com informações de endereço
     */
    public function ajax_buscar_cep(){
        $search = $this->input->get('q');
        
        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];
        $ceps = $this->sac->get('tz_ceps',array(
            '$expand' => 'tz_estadoid($select=tz_name,tz_estadoid),tz_cidadeid($select=tz_name,tz_cidadeid)',
            '$select' => 'tz_cepid,tz_cep1,tz_logradouro,tz_bairro',
            '$filter' => "contains(tz_cep1, '{$search}')"
        ));
        if($ceps->code == 200){
            $values = $ceps->data->value;
            foreach ($values as $key => $value) {
                $resposta['results'][] = array(
                    'id' => $value->tz_cepid,
                    'text' => $value->tz_cep1,
                    'logradouro' => $value->tz_logradouro,
                    'bairro' => $value->tz_bairro,
                    'cidade' => $value->tz_cidadeid->tz_name,
                    'cidade_id' => $value->tz_cidadeid->tz_cidadeid,
                    'estado' => $value->tz_estadoid->tz_name,
                    'estado_id' =>$value->tz_estadoid->tz_estadoid,
                );
            }
        }
        
        echo json_encode($resposta);
    }
    
    /**
     * Função que busca AF para exibir no select
     */
    public function ajax_buscar_af(){
        $search = $this->input->get('q');
        
        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];

        $afs = $this->sac->get('tz_afs',array(
            '$select' => 'tz_afid,tz_name,tz_numero_af',
            '$filter' => "contains(tz_name, '{$search}')"
        ));

        if($afs->code == 200){
            $values = $afs->data->value;
            foreach ($values as $key => $value) {
                $resposta['results'][] = array(
                    'id' => $value->tz_afid,
                    'text' => $value->tz_name,
                    'tz_numero_af' => $value->tz_numero_af,
                );
            }
        }
        
        echo json_encode($resposta);
    }

    public function ajax_editar_atividade_de_servico($activityid = null){
        try {
            $dados = $this->input->post();

            if($activityid === null) $activityid = $dados['idNa'];

            if(isset($dados["recurso"]) and $dados["recurso"] != ""){
                $resultado = $this->sac->put("serviceAppointment/AlteraPrestador", array (
                    "ID" => $activityid,
                    "Recurso" => $dados["recurso"] ,
                    "Prestador" =>$dados["prestador"])
                );
            }

            if(isset($activityid)){

                $body = $this->get_body_atividade_de_servico($dados);
                
                $response = $this->sac->patch('serviceappointments', $activityid, $body);
                if($response->code == 200) {
                    array_push($response, array ("recurso" => $resultado));

                    $atividadeServico = $this->get_atividade_de_servico($response->data->activityid);

                    if($dados['anotacoes'][0]){
                        $anotacoes = $dados['anotacoes'][0];
                        
                        $anotacoes['idNa'] = $activityid;
                        $resposta = $this->criarAnotacoes($anotacoes);

                        if($resposta)
                            exit(json_encode(array('status' => 200, 'data' => $atividadeServico)));
                        else
                            exit(json_encode(array('status' => 500, 'erro' => 'NA atualizada, mas ocorreu um erro ao atualizar a anotação.')));
                    }
                    echo json_encode(array('status' => 200, 'data' => $atividadeServico));
                } 
            }else{
                echo json_encode(array('status' => 500, 'erro' => 'O ID da atividade de serviço não foi informado!'));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 500, 'erro' => 'Erro ao atualizar atividade de serviço! '.$th->getMessage()));
        }
    }

    /*
     * Retorna um array com os dados para serem salvos no item de contrato
     */
    private function getBodyRequestItemDeContrato($dados){
        $body = array();
        /**
         * SALVA RELACIONAMENTOS
         */
        // SALVA RELACIONAMENTO CENÁRIO DE VENDA
        if(isset($dados["tz_cenario_vendaid"]) && $dados["tz_cenario_vendaid"] != "") $body["tz_cenario_vendaid@odata.bind"] = '/tz_cenario_vendas('.$dados["tz_cenario_vendaid"].')';
        unset($dados["tz_cenario_vendaid"]);
        // SALVA RELACIONAMENTO INDICE REAJUSTE
        if(isset($dados["tz_indice_reajusteid"]) && $dados["tz_indice_reajusteid"] != "" ) $body["tz_indice_reajusteid@odata.bind"] = '/tz_indice_reajustes('.$dados["tz_indice_reajusteid"].')';
        unset($dados["tz_indice_reajusteid"]);
        // SALVA RELACIONAMENTO PLANO LINKER
        if(isset($dados["tz_plano_linkerid"]) && $dados["tz_plano_linkerid"] != "") $body["tz_plano_linkerid@odata.bind"] = '/tz_plano_satelitals('.$dados["tz_plano_linkerid"].')';
        unset($dados["tz_plano_linkerid"]);
        // SALVA RELACIONAMENTO PLATAFORMA
        if(isset($dados["tz_plataformaid"]) && $dados["tz_plataformaid"] != "") $body["tz_plataformaid@odata.bind"] = '/tz_plataformas('.$dados["tz_plataformaid"].')';
        unset($dados["tz_plataformaid"]);
        // SALVA RELACIONAMENTO RASTREADOR
        if(isset($dados["tz_rastreadorid"]) && $dados["tz_rastreadorid"] != "") $body["tz_rastreadorid@odata.bind"] = '/products('.$dados["tz_rastreadorid"].')';
        unset($dados["tz_rastreadorid"]);
        // SALVA RELACIONAMENTO TECNOLOGIA
        if(isset($dados["tz_tecnologiaid"]) && $dados["tz_tecnologiaid"] != "") $body["tz_tecnologiaid@odata.bind"] = '/tz_tecnologias('.$dados["tz_tecnologiaid"].')';
        unset($dados["tz_tecnologiaid"]);
        // SALVA RELACIONAMENTO VEÍCULO
        if(isset($dados["tz_veiculoid"]) && $dados["tz_veiculoid"] != "") $body["tz_veiculoid@odata.bind"] = '/tz_veiculos('.$dados["tz_veiculoid"].')';
        unset($dados["tz_veiculoid"]);
        // SALVA RELACIONAMENTO PROVIDÊNCIAS
        if(isset($dados["tz_providenciasid"]) && $dados["tz_providenciasid"] != "") $body["tz_providenciasid@odata.bind"] = '/tz_providencias('.$dados["tz_providenciasid"].')';
        unset($dados["tz_providenciasid"]);
        // SALVA RELACIONAMENTO ITEM DE CONTRATO ORIGINAL
        if(isset($dados["tz_item_contrato_originalid"]) && $dados["tz_item_contrato_originalid"] != "") $body["tz_item_contrato_originalid_tz_item_contrato_venda@odata.bind"] = '/tz_item_contrato_vendas('.$dados["tz_item_contrato_originalid"].')';
        unset($dados["tz_item_contrato_originalid"]);
        // SALVA RELACIONAMENTO MOTIVO ALTERACAO
        if(isset($dados["tz_motivo_alteracao"]) && $dados["tz_motivo_alteracao"] != "") $body["tz_motivo_alteracao@odata.bind"] = "/tz_motivo_cancelamentos(".$dados["tz_motivo_alteracao"].")";
        unset($dados["tz_motivo_alteracao"]);
        // SALVA RELACIONAMENTO AF
        if(isset($dados["tz_afid"]) && $dados["tz_afid"] != "") $body["tz_afid@odata.bind"] = "/tz_afs(".$dados["tz_afid"].")";
        unset($dados["tz_afid"]);

        /**
         * SALVA DATAS
         */
        if(isset($dados["tz_data_alteracao_status"]) && $dados["tz_data_alteracao_status"] != "") $body["tz_data_alteracao_status"] = (new DateTime($dados["tz_data_alteracao_status"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_alteracao_status"]);

        if(isset($dados["tz_data_entrada"]) && $dados["tz_data_entrada"] != "") $body["tz_data_entrada"] = (new DateTime($dados["tz_data_entrada"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_entrada"]);

        if(isset($dados["tz_data_expedicao"]) && $dados["tz_data_expedicao"] != "") $body["tz_data_expedicao"] = (new DateTime($dados["tz_data_expedicao"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_expedicao"]);
        
        if(isset($dados["tz_data_ativacao"]) && $dados["tz_data_ativacao"] != "") $body["tz_data_ativacao"] = (new DateTime($dados["tz_data_ativacao"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_ativacao"]);
        
        if(isset($dados["tz_data_vencimento_comodato"]) && $dados["tz_data_vencimento_comodato"] != "") $body["tz_data_vencimento_comodato"] = (new DateTime())->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_vencimento_comodato"]);
        
        if(isset($dados["tz_data_termino"]) && $dados["tz_data_termino"] != "") $body["tz_data_termino"] = (new DateTime($dados["tz_data_termino"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_termino"]);
        
        if(isset($dados["tz_data_termino_fidelidade"]) && $dados["tz_data_termino_fidelidade"] != "") $body["tz_data_termino_fidelidade"] = (new DateTime($dados["tz_data_termino_fidelidade"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_termino_fidelidade"]);
        
        if(isset($dados["tz_data_desinstalacao"]) && $dados["tz_data_desinstalacao"] != "") $body["tz_data_desinstalacao"] = (new DateTime($dados["tz_data_desinstalacao"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_desinstalacao"]);
        
        if(isset($dados["tz_data_aniversario_contrato"]) && $dados["tz_data_aniversario_contrato"] != "") $body["tz_data_aniversario_contrato"] = (new DateTime($dados["tz_data_aniversario_contrato"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_aniversario_contrato"]);
        
        if(isset($dados["tz_data_inicio_garantia"]) && $dados["tz_data_inicio_garantia"] != "") $body["tz_data_inicio_garantia"] = (new DateTime($dados["tz_data_inicio_garantia"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_inicio_garantia"]);
        
        if(isset($dados["tz_data_fim_garantia"]) && $dados["tz_data_fim_garantia"] != "") $body["tz_data_fim_garantia"] = (new DateTime($dados["tz_data_fim_garantia"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_fim_garantia"]);
        
        if(isset($dados["tz_data_vencimento_demonstracao"]) && $dados["tz_data_vencimento_demonstracao"] != "") $body["tz_data_vencimento_demonstracao"] = (new DateTime($dados["tz_data_vencimento_demonstracao"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_vencimento_demonstracao"]);
        
        if(isset($dados["tz_data_fim_carencia"]) && $dados["tz_data_fim_carencia"] != "") $body["tz_data_fim_carencia"] = (new DateTime($dados["tz_data_fim_carencia"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_fim_carencia"]);
        
        if(isset($dados["tz_data_proxima_renovacao"]) && $dados["tz_data_proxima_renovacao"] != "") $body["tz_data_proxima_renovacao"] = (new DateTime($dados["tz_data_proxima_renovacao"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_proxima_renovacao"]);
        
        if(isset($dados["tz_data_ultimo_reajuste"]) && $dados["tz_data_ultimo_reajuste"] != "") $body["tz_data_ultimo_reajuste"] = (new DateTime($dados["tz_data_ultimo_reajuste"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_ultimo_reajuste"]);
        
        if(isset($dados["tz_data_carencia_reajuste"]) && $dados["tz_data_carencia_reajuste"] != "") $body["tz_data_carencia_reajuste"] = (new DateTime($dados["tz_data_carencia_reajuste"]))->format('Y-m-d\TH:i:s\Z');
        unset($dados["tz_data_carencia_reajuste"]);

        /**
         * SALVA BOOLEANS
         */
        if(isset($dados["tz_integrar_gestor"]) && $dados["tz_integrar_gestor"] != "") $body["tz_integrar_gestor"] = $dados["tz_integrar_gestor"] == "true" ? true : false;
        unset($dados["tz_integrar_gestor"]);
        
        if(isset($dados["tz_integra_contrato"]) && $dados["tz_integra_contrato"] != "") $body["tz_integra_contrato"] = $dados["tz_integra_contrato"] == "true" ? true : false;
        unset($dados["tz_integra_contrato"]);
        
        if(isset($dados["tz_reenviar_apolice_graber"]) && $dados["tz_reenviar_apolice_graber"] != "") $body["tz_reenviar_apolice_graber"] = $dados["tz_reenviar_apolice_graber"] == "true" ? true : false;
        
        
        /**
         * SALVA FLOATS
         */
        if(isset($dados["tz_percentual_ultimo_reajuste"]) && $dados["tz_percentual_ultimo_reajuste"] != "") $body["tz_percentual_ultimo_reajuste"] = floatval($dados["tz_percentual_ultimo_reajuste"]);
        unset($dados["tz_percentual_ultimo_reajuste"]);
        
        if(isset($dados["tz_valor_deslocamento_km"]) && $dados["tz_valor_deslocamento_km"] != "") $body["tz_valor_deslocamento_km"] = floatval($dados["tz_valor_deslocamento_km"]);
        unset($dados["tz_valor_deslocamento_km"]);
        
        if(isset($dados["tz_taxa_visita"]) && $dados["tz_taxa_visita"] != "") $body["tz_taxa_visita"] = floatval($dados["tz_taxa_visita"]);
        unset($dados["tz_taxa_visita"]);
        
        if(isset($dados["tz_valor_licenca"]) && $dados["tz_valor_licenca"] != "") $body["tz_valor_licenca"] = floatval($dados["tz_valor_licenca"]);
        unset($dados["tz_valor_licenca"]);
        
        /**
         * SALVA INTEGERS
         */

        if(isset($dados["tz_tipo_contrato"]) && $dados["tz_tipo_contrato"] != "") $body["tz_tipo_contrato"] = intval($dados["tz_tipo_contrato"]);
        unset($dados["tz_tipo_contrato"]);
        
        if(isset($dados["tz_codigo_gestor"]) && $dados["tz_codigo_gestor"] != "") $body["tz_codigo_gestor"] = intval($dados["tz_codigo_gestor"]);
        unset($dados["tz_codigo_gestor"]);
        
        if(isset($dados["tz_status_item_contrato"]) && $dados["tz_status_item_contrato"] != "") $body["tz_status_item_contrato"] = intval($dados["tz_status_item_contrato"]);
        unset($dados["tz_status_item_contrato"]);
        
        if(isset($dados["tz_cancelado_devido_a"]) && $dados["tz_cancelado_devido_a"] != "") $body["tz_cancelado_devido_a"] = intval($dados["tz_cancelado_devido_a"]);
        unset($dados["tz_cancelado_devido_a"]);
        
        if(isset($dados["tz_duracao_comodato"]) && $dados["tz_duracao_comodato"] != "") $body["tz_duracao_comodato"] = intval($dados["tz_duracao_comodato"]);
        unset($dados["tz_duracao_comodato"]);
        
        if(isset($dados["tz_qtde_meses_isento_reajuste"]) && $dados["tz_qtde_meses_isento_reajuste"] != "") $body["tz_qtde_meses_isento_reajuste"] = intval($dados["tz_qtde_meses_isento_reajuste"]);
        unset($dados["tz_qtde_meses_isento_reajuste"]);
        
        /**
         * Item de Contrato
         */
        if(isset($dados["tz_codigo_item_contrato"]) && $dados["tz_codigo_item_contrato"] != "") $body["tz_codigo_item_contrato"] = $dados["tz_codigo_item_contrato"];
        unset($dados["tz_codigo_item_contrato"]);
         /**
          * SALVA STRINGS
          */
        // COPIA OS VALORES QUE FORAM INFORMADOS
        foreach ($dados as $key => $dado) {
            if($dado != ''){
                $body[$key] = $dado;
            }
        }

        return $body;
    }

    /**
     * Retorna item de contrato de venda por id
     */
    public function getItemContratoVendaPorId($id){
        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
                    <entity name="tz_item_contrato_venda">
                        <attribute name="tz_name" alias="codigo"/>
                        <attribute name="statecode" alias="statecode"/>
                        <attribute name="tz_status_item_contrato" alias="status"/>
                        <attribute name="tz_data_entrada" alias="dataEntrada"/>
                        <attribute name="tz_data_ativacao" alias="dataAtivacao" />
                        <attribute name="tz_numero_serie_modulo_principal" alias="serialEquipamento" />
                        <attribute name="tz_item_contrato_vendaid" alias="id"/>
                        <attribute name="tz_numero_af" alias="codigoVenda"/>
                        <attribute name="tz_indice_reajusteid" />
                        <attribute name="tz_data_termino_fidelidade" alias="dataTerminoFidelidade"/>
                        <attribute name="tz_data_inicio_garantia" alias="dataInicioGarantia"/>
                        <attribute name="tz_data_fim_garantia" alias="dataFimGarantia"/>
                        <attribute name="tz_data_termino" alias="dataTermino"/>
                        <attribute name="tz_data_ultimo_reajuste" alias="dataUltimoReajuste"/>
                        <attribute name="tz_data_vencimento_comodato" alias="dataVencimentoComodato"/>
                        <attribute name="tz_data_termino_suspensao" alias="dataTerminoSuspensao"/>
                        <attribute name="tz_data_inicio_suspensao" alias="dataInicioSuspensao"/>
                        <attribute name="tz_data_expedicao" alias="dataExpedicao"/>
                        <attribute name="tz_data_desinstalacao" alias="dataDesinstalacao"/>
                        <attribute name="createdon" alias="dataCriacao"/>
                        <attribute name="tz_numero_serie_antena_satelital" />
                        <order attribute="tz_name" descending="false" />
                        <link-entity name="product" from="productid" to="tz_rastreadorid" visible="false" link-type="outer" alias="rastreador">
                            <attribute name="name" alias="modeloEquipamento"/>
                        </link-entity>
                        <link-entity name="tz_tecnologia" from="tz_tecnologiaid" to="tz_tecnologiaid" visible="false" link-type="outer" alias="tecnologia">
                            <attribute name="tz_name" alias="tecnologia"/>
                        </link-entity>
                        <link-entity name="tz_veiculo" from="tz_veiculoid" to="tz_veiculoid" visible="false" link-type="outer" alias="veiculo">
                            <attribute name="tz_placa" alias="placa"/>
                        </link-entity>
                        <link-entity name="product" from="productid" to="tz_plano_linkerid" visible="false" link-type="outer" alias="plano">
                            <attribute name="name" alias="plano"/>
                        </link-entity>
                        <filter type="and">
                            <condition attribute="tz_item_contrato_vendaid" operator="eq" uiname="G3118A-001" uitype="tz_item_contrato_venda" value="{id}" />
                        </filter>
                        </entity>
            </fetch>';
                        
            
        $xml = str_replace('{id}', $id, $xml);
        $xml = urlencode($xml);

        $api_request_parameters = array('fetchXml'=> $xml);
        $item = $this->sac->get("tz_item_contrato_vendas", $api_request_parameters);

        if($item->code == 200){
            $dado = $item->data->value[0];
            $resposta = array(
                "id" => isset($dado->tz_item_contrato_vendaid) ? $dado->tz_item_contrato_vendaid : '-',
                "codigo" => isset($dado->codigo) ? $dado->codigo : null,
                "placa" => isset($dado->placa) ? $dado->placa : '-',
                "serialEquipamento" => isset($dado->serialEquipamento) ? $dado->serialEquipamento : '-',
                "modeloEquipamento" => isset($dado->modeloEquipamento) ? $dado->modeloEquipamento : '-',
                "plano" => isset($dado->plano) ? $dado->plano : '-',
                "codigoVenda" => isset($dado->codigoVenda) ? $dado->codigoVenda : '-',
                "dataAtivacao" => isset($dado->dataAtivacao) ? $dado->dataAtivacao : null,
                "status" => isset($dado->status) ? $dado->status : '-',
                "statecode" => isset($dado->statecode) ? $dado->statecode : null,

                "dataEntrada" => isset($dado->dataEntrada) ? $dado->dataEntrada : '-',
                "tz_indice_reajusteid" => isset($dado->tz_indice_reajusteid) ? $dado->tz_indice_reajusteid : '-',
                "dataTerminoFidelidade" => isset($dado->dataTerminoFidelidade) ? $dado->dataTerminoFidelidade : '-',
                "dataInicioGarantia" => isset($dado->dataInicioGarantia) ? $dado->dataInicioGarantia : '-',
                "dataFimGarantia" => isset($dado->dataFimGarantia) ? $dado->dataFimGarantia : '-',
                "dataTermino" => isset($dado->dataTermino) ? $dado->dataTermino : '-',
                "dataUltimoReajuste" => isset($dado->dataUltimoReajuste) ? $dado->dataUltimoReajuste : '-',
                "dataVencimentoComodato" => isset($dado->dataVencimentoComodato) ? $dado->dataVencimentoComodato : '-',
                "dataTerminoSuspensao" => isset($dado->dataTerminoSuspensao) ? $dado->dataTerminoSuspensao : '-',
                "dataInicioSuspensao" => isset($dado->dataInicioSuspensao) ? $dado->dataInicioSuspensao : '-',
                "dataExpedicao" => isset($dado->dataExpedicao) ? $dado->dataExpedicao : '-',
                "dataDesinstalacao" => isset($dado->dataDesinstalacao) ? $dado->dataDesinstalacao : '-',
                "dataCriacao" => isset($dado->dataCriacao) ? $dado->dataCriacao : '-',
                "tz_numero_serie_antena_satelital" => isset($dado->tz_numero_serie_antena_satelital) ? $dado->tz_numero_serie_antena_satelital : '-',
                "tecnologia" => isset($dado->tecnologia) ? $dado->tecnologia : '-',
            );
            return $resposta;
        }else{
            return null;
        }

    }

    /**
     * Retora os dados da alteração de contrato para exibir no modal
     */
    public function ajax_get_alteracao_contrato($idAlteracao){
        try {
            $alteracaoContrato = $this->sac->get("tz_manutencao_contratos", array(
                '$filter'=>"tz_manutencao_contratoid eq {$idAlteracao}",
                '$expand' => 'tz_motivoid($select=tz_motivo_manutencao_contratoid,tz_name),
                    tz_ocorrenciaid($select=incidentid,ticketnumber),
                    tz_item_contratoid($select=tz_item_contrato_vendaid,tz_name),
                    tz_motivo_cancelamentoid($select=tz_motivo_cancelamentoid,tz_name),
                    tz_veiculoid($select=tz_veiculoid,tz_placa),
                    tz_concorrenteid($select=competitorid,name),
                    tz_planoid($select=productid,name),
                    tz_indice_reajusteid($select=tz_indice_reajusteid,tz_name),
                    tz_providencias($select=tz_providenciasid,tz_pergunta),
                    tz_rastreadorid($select=productid,name)',
            ));
            
            if($alteracaoContrato->code == 200){
                $value = $alteracaoContrato->data->value[0];
                $retorno = array(
                    "inputs" => array(
                        "id_alteracao_de_contrato" => isset($value->tz_manutencao_contratoid) ? $value->tz_manutencao_contratoid : '',
                        "alteracao_contrato_tz_prazo_cancelamento" => isset($value->tz_prazo_cancelamento) ? $value->tz_prazo_cancelamento : '',
                        "alteracao_contrato_tz_numero_serie" => isset($value->tz_numero_serie) ? $value->tz_numero_serie : '',
                        "alteracao_contrato_tz_numero_serie_antena_satelital" => isset($value->tz_numero_serie_antena_satelital) ? $value->tz_numero_serie_antena_satelital : '',
                        "alteracao_contrato_tz_modelo_tipo_ativacao" => isset($value->tz_modelo_tipo_ativacao) ? $value->tz_modelo_tipo_ativacao : '',
                        "alteracao_contrato_tz_periodosuspensao" => isset($value->tz_periodosuspensao) ? $value->tz_periodosuspensao : '',
                        "alteracao_contrato_tz_data_ultima_comunicacao" => isset($value->tz_data_ultima_comunicacao) ? (new DateTime($value->tz_data_ultima_comunicacao))->format('Y-m-d\TH:i:s') : '',
                        "alteracao_contrato_tz_data_termino_demonstrao" => isset($value->tz_data_termino_demonstrao) ? (new DateTime($value->tz_data_termino_demonstrao))->format('Y-m-d') : '',
                        "alteracao_contrato_tz_data_inicio_suspensao" => isset($value->tz_data_inicio_suspensao) ? (new DateTime($value->tz_data_inicio_suspensao))->format('Y-m-d') : '',
                        "alteracao_contrato_tz_data_termino_suspenso" =>isset($value->tz_data_termino_suspenso) ? (new DateTime($value->tz_data_termino_suspenso))->format('Y-m-d') : '',
                        "alteracao_contrato_tz_data_termino_comodato" => isset($value->tz_data_termino_comodato) ? (new DateTime($value->tz_data_termino_comodato))->format('Y-m-d') : '',
                        "alteracao_contrato_tz_data_ativacao" => isset($value->tz_data_ativacao) ? (new DateTime($value->tz_data_ativacao))->format('Y-m-d') : '',
                        "alteracao_contrato_tz_data_termino_garantia" => isset($value->tz_data_termino_garantia) ? (new DateTime($value->tz_data_termino_garantia))->format('Y-m-d') : '',
                        "alteracao_contrato_tz_data_inicio_garantia" => isset($value->tz_data_inicio_garantia) ? (new DateTime($value->tz_data_inicio_garantia))->format('Y-m-d') : '',
                        "alteracao_contrato_tz_data_aniversario" => isset($value->tz_data_aniversario) ? (new DateTime($value->tz_data_aniversario))->format('Y-m-d') : '',
                        "alteracao_contrato_tz_providencias" => isset($value->tz_providencias) ? $value->tz_providencias : '',
                        "alteracao_contrato_statuscode" => isset($value->tz_status_manutencao) ? $value->tz_status_manutencao : '',
                    ),
                    "selects" => array(
                        "alteracao_contrato_tz_motivo_cancelamentoid" => array(
                            "id" => isset($value->tz_motivo_cancelamentoid->tz_motivo_cancelamentoid) ? $value->tz_motivo_cancelamentoid->tz_motivo_cancelamentoid : null,
                            "text" => isset($value->tz_motivo_cancelamentoid->tz_name) ? $value->tz_motivo_cancelamentoid->tz_name : null,
                        ),
                        "alteracao_contrato_tz_incidentid" => array(
                            'id' => isset($value->tz_ocorrenciaid->incidentid) ? $value->tz_ocorrenciaid->incidentid : null,
                            'text' => isset($value->tz_ocorrenciaid->ticketnumber) ? $value->tz_ocorrenciaid->ticketnumber : null
                        ),
                        "alteracao_contrato_tz_veiculo_id" => array(
                            "id" => isset($value->tz_veiculoid->tz_veiculoid) ? $value->tz_veiculoid->tz_veiculoid : null,
                            "text" => isset($value->tz_veiculoid->tz_placa) ? $value->tz_veiculoid->tz_placa : null,
                        ),
                        "alteracao_contrato_tz_concorrenteid" => array(
                            "id" => isset($value->tz_concorrenteid->competitorid) ? $value->tz_concorrenteid->competitorid : null,
                            "text" => isset($value->tz_concorrenteid->name) ? $value->tz_concorrenteid->name : null,
                        ),
                        "alteracao_contrato_tz_rastreadorid" => array(
                            "id" => isset($value->tz_rastreadorid->productid) ? $value->tz_rastreadorid->productid : null,
                            "text" => isset($value->tz_rastreadorid->name) ? $value->tz_rastreadorid->name : null,
                        ),
                        "alteracao_contrato_tz_planoid" => array(
                            "id" => isset($value->tz_planoid->productid) ? $value->tz_planoid->productid : null,
                            "text" => isset($value->tz_planoid->name) ? $value->tz_planoid->name : null,
                        ),
                        "alteracao_contrato_tz_indice_reajusteid" => array(
                            "id" => isset($value->tz_indice_reajusteid->tz_indice_reajusteid) ? $value->tz_indice_reajusteid->tz_indice_reajusteid : null,
                            "text" => isset($value->tz_indice_reajusteid->tz_name) ? $value->tz_indice_reajusteid->tz_name : null,
                        ),
                        
                        "alteracao_contrato_tz_motivoid" => array(
                            'id' => isset($value->tz_motivoid->tz_motivo_manutencao_contratoid) ? $value->tz_motivoid->tz_motivo_manutencao_contratoid : null,
                            'text' => isset($value->tz_motivoid->tz_name) ? $value->tz_motivoid->tz_name : null
                        ),
                        "alteracao_contrato_tz_providencias" => array(
                            'id' => isset($value->tz_providencias->tz_providenciasid) ? $value->tz_providencias->tz_providenciasid : null,
                            'text' => isset($value->tz_providencias->tz_pergunta) ? "Perg/Resp: ".$value->tz_providencias->tz_pergunta : null
                        ),
                    ),                    
                    
                    "item_contrato" => isset($value->tz_item_contratoid->tz_name) ? $value->tz_item_contratoid->tz_name : null,
                    "tz_valortaxa" => isset($value->tz_valortaxa) ? $value->tz_valortaxa : null,
                );

                if(isset($value->tz_isencaodetaxa)){
                    $retorno['inputs']["alteracao_contrato_tz_isencaodetaxa"] = $value->tz_isencaodetaxa == true ? 'true' : 'false';
                }
                
                echo json_encode(array(
                    'status' => 1,
                    'data' => $retorno
                ));
            }else{
                echo json_encode(array('status' => 0, 'erro' => $alteracaoContrato->data));
            }
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array('status' => 0, 'erro' => "Erro ao buscar Alteração de Contrato! ". $th->getMessage()));
        }
    }

    public function ajax_cadastrar_alteracao_contrato(){
        
        try {            
            $dados = $this->input->post();

            if(isset($dados['id_item_de_contrato'])){
                $nome_alteracao_contrato = array();
                $item_contrato = $this->sac->get('tz_item_contrato_vendas', array(
                    '$filter' => "tz_item_contrato_vendaid eq ".$dados['id_item_de_contrato'],
                    '$select' => 'tz_name'
                ));
                if($item_contrato->code = 200){
                    $nome_item_contrato = $item_contrato->data->value[0]->tz_name;
                    if(isset($nome_item_contrato) && $nome_item_contrato != ""){
                        array_push($nome_alteracao_contrato, $nome_item_contrato);
                    }
                }

                $motivo = $this->sac->get('tz_motivo_manutencao_contratos', array(
                    '$filter' => "tz_motivo_manutencao_contratoid eq ".$dados['alteracao_contrato_tz_motivoid'],
                    '$select' => 'tz_name'
                ));

                if($motivo->code == 200){
                    $nome_motivo = $motivo->data->value[0]->tz_name;
                    if(isset($nome_motivo) && $nome_motivo != ""){
                        array_push($nome_alteracao_contrato, $nome_motivo);
                    }
                }

                if(count($nome_alteracao_contrato) > 1) {
                    $nome_alteracao_contrato = implode("-",$nome_alteracao_contrato);
                }else{
                    $nome_alteracao_contrato = $nome_alteracao_contrato[0];
                }
                
                $body = $this->getBodyRequestAlteracaoDeContrato($dados);
                $body["tz_item_contratoid@odata.bind"] = "/tz_item_contrato_vendas(".$dados['id_item_de_contrato'].")";
                $body["tz_name"] = $nome_alteracao_contrato;


                $response = $this->sac->post('tz_manutencao_contratos', $body);

                if($response->code == 201){
                    $idAlteracao = $response->data->tz_manutencao_contratoid;
                    $alteracaoContrato = $this->getAlteracaoDeContrato($idAlteracao);

                    echo json_encode(array('status' => 1, 'data' => $alteracaoContrato));
                }else{
                    echo json_encode(array('status' => 0, 'erro' => "Erro ao cadastrar alteração de contrato"));
                }
            }else{
                echo json_encode(array('status' => 0, 'erro' => "Erro ao cadastrar alteração de contrato. Item de contrato não informado."));
            }
        } catch (\Throwable $th) {
            
            echo json_encode(array('status' => 0, 'erro' => "Erro ao cadastrar alteração de contrato! ". $th->getMessage()));
        }
    }

    /**
     * Função que retorna dados da atividade de serviço que serão adicionados na tabela
     */
    public function get_atividade_de_servico($activityid){

        $atividadesServico = $this->sac->get("serviceappointments($activityid)", array(
            '$select'=>"subject,statecode,statuscode,scheduledstart,scheduledend,_createdby_value,_regardingobjectid_value,activityid,tz_id_agendamento,tz_numero_serie_contrato,tz_numero_serie_antena_contrato,actualend,_tz_item_contratoid_value,tz_cliente_pfid_serviceappointment",
            '$expand' => 'tz_item_contratoid_serviceappointment($select=tz_name),regardingobjectid_incident_serviceappointment($select=ticketnumber)'
        ));

        if($atividadesServico->code == 200){
            $valor = $atividadesServico->data;
            // Descrição do Status
            if($valor->statecode == 0) $statusDescription = 'Aberto';
            elseif($valor->statecode == 1) $statusDescription = 'Fechado';
            elseif($valor->statecode == 2) $statusDescription = 'Cancelado';
            else $statusDescription = 'Agendado';
    
            return array(
                "Id" => $valor->activityid,
                'Code' => isset($valor->tz_id_agendamento) ? $valor->tz_id_agendamento : '',
                'Status' => $valor->statecode,
                'StatusCode' => $valor->statuscode,
                'statusDescription' => $statusDescription,
                'scheduledstart' => isset($valor->scheduledstart) ? date_format(date_create($valor->scheduledstart), 'd/m/Y H:i:s') : '',
                'scheduledend' => isset($valor->scheduledend) ? date_format(date_create($valor->scheduledend), 'd/m/Y H:i:s') : '',
                'actualend' => isset($valor->actualend) ? date_format(date_create($valor->actualend), 'd/m/Y H:i:s') : '',
                'subject' => isset($valor->subject) ? $valor->subject : '',
                'contract' => array(
                    'Id' => $valor->_tz_item_contratoid_value,
                    'Code' => isset($valor->tz_item_contratoid_serviceappointment) ? $valor->tz_item_contratoid_serviceappointment->tz_name : '',
                ),
                'serviceName' => isset($valor->serviceid_serviceappoint) ? $valor->serviceid_serviceappointment->name : '',
                'serviceNameComplement' => isset($valor->tz_complemento_servicoid_serviceappointment) ? $valor->tz_complemento_servicoid_serviceappointment->tz_name : '',
                'provider' => isset($valor->siteid) ? $valor->siteid->name : '',
                'trackerSerialNumber' => isset($valor->tz_numero_serie_contrato) ? $valor->tz_numero_serie_contrato : '',
                'satelliteSerialNumber' => isset($valor->tz_numero_serie_antena_contrato) ? $valor->tz_numero_serie_antena_contrato : '',
                'trackerSerialNumberInstall' => isset($valor->tz_baseinstaladarastreador_serviceappointment->tz_numero_serie) ? $valor->tz_baseinstaladarastreador_serviceappointment->tz_numero_serie : '',
                'satelliteSerialNumberInstall' => isset($valor->tz_baseinstaladaantena_serviceappointment->tz_numero_serie) ? $valor->tz_baseinstaladaantena_serviceappointment->tz_numero_serie : '',
                'incident' => array(
                    'Id' => $valor->_regardingobjectid_value,
                    'TicketNumber' => isset($valor->regardingobjectid_incident_serviceappointment) ? $valor->regardingobjectid_incident_serviceappointment->ticketnumber : ''
                )
            );
        }else{
            return [];
        }
    }

    /**
     * Retorna bases instaladas do cliente
     */
    public function ajax_listar_bases_instaladas($tipoCliente, $idCliente) {
        try {
            // Recupera os dados vindos da requisição
            $requisicao = $this->input->get();

            $entidade = 'tz_base_instalada_clientes';

            // Recuperando o valor do item inicial a ser carregado
            $inicio = isset($requisicao['start']) && $requisicao['start'] != 1 ? $requisicao['start'] : 0;

            // Recupera o valor de quantos item devem ser carregados
            $tamanho = isset($requisicao['length']) ? $requisicao['length'] : 10;
            
            // Página que deve ser carrregada e mostrado ao usuário
            $pagina = ($inicio / $tamanho) + 1;

            // Recupera o valor da caixa de pesquisa para busca refinada entre os dados do cliente
            if (isset($requisicao['search']) && $requisicao['search']['value'] != '') {
                $pesquisa = $requisicao['search']['value'];
            }

            // Recupera e trata os dados do cliente para pesquisa
            $tipoCliente = isset($tipoCliente) ? ($tipoCliente == 'pj' ? 'tz_cliente_pjid' : 'tz_cliente_pfid'
            ) : null;
            $idCliente = isset($idCliente) ? $idCliente : null;

            // XML de requisição e busca dos dados na API do CRM
            $dadosXml = "
            <fetch version='1.0' mapping='logical' distinct='true' page='$pagina' count='$tamanho'>
                <entity name='tz_base_instalada_cliente'>
                    <attribute name='tz_name' />
                    <attribute name='tz_data_instalacao' />
                    <attribute name='tz_data_desinstalacao' />
                    <attribute name='tz_numero_serie' />
                    <attribute name='tz_base_instalada_clienteid' />
                    <order attribute='createdon' descending='true' />
                    <link-entity name='tz_veiculo' to='tz_veiculoid' link-type='outer'>
                        <attribute name='tz_placa' />
                    </link-entity>
                    <link-entity name='product' to='tz_produtoid' link-type='outer'>
                        <attribute name='name' />
                    </link-entity>
                    <filter type='and'>
                        <condition attribute='$tipoCliente' operator='eq' value='$idCliente' />
                    </filter>
                    {{ search }}
                </entity>
            </fetch>
            ";

            // Monta o XML de pesquisa em todos as colunas recuperadas para que a busca do DataTables
            // funcione corretamente.
            // TODO: Revisar a busca por Data (por enquanto faz apenas a busca por ano)
            if (isset($pesquisa)) {
                $pesquisaXml = "
                <filter type='or'>
                    <condition attribute='tz_name' operator='like' value='%$pesquisa%' />
                    <condition attribute='tz_placa' entityname='tz_veiculo' operator='like' value='%$pesquisa%' />
                    <condition attribute='name' entityname='product' operator='like' value='%$pesquisa%' />
                    <condition attribute='tz_numero_serie' operator='like' value='%$pesquisa%' />
                ";

                // Verificar se a pesquisa é um ano válido
                if (is_numeric($pesquisa)) {
                    if ($pesquisa >= 1900 && $pesquisa <= 2100) {
                        $pesquisaXml .= "<condition attribute='tz_data_instalacao' operator='in-fiscal-year' value='$pesquisa' />";
                    }
                }

                $pesquisaXml .= "</filter>";

                $dadosXml = str_replace('{{ search }}', $pesquisaXml, $dadosXml);
            }

            $dadosXml = str_replace('{{ search }}', '', $dadosXml);

            // XML para contagem dos resultados totais
            $totalXml = "
            <fetch version='1.0' mapping='logical' aggregate='true'>
                <entity name='tz_base_instalada_cliente'>
                    <attribute name='tz_base_instalada_clienteid' aggregate='count' alias='count' />
                    <link-entity name='tz_veiculo' to='tz_veiculoid' link-type='outer' />
                    <link-entity name='product' to='tz_produtoid' link-type='outer' />
                    <filter>
                        <condition attribute='$tipoCliente' operator='eq' value='$idCliente' />
                    </filter>
                    {{ search }}
                </entity>
            </fetch>
            ";

            // Insere os filtros adicionais do DataTables na requisição de contagem
            if (isset($pesquisaXml)) {
                $totalXml = str_replace('{{ search }}', $pesquisaXml, $totalXml);
            }

            // Faz a limpeza do XML de contagem, caso algum parâmetro não seja utilizado
            $totalXml = str_replace('{{ search }}', '', $totalXml);

            // Codifica os XML de consulta para serem passados através de uma requsicao de URL
            $parametrosTotais = http_build_query(['fetchXml' => urlencode($totalXml)]);
            $parametrosDados = http_build_query(['fetchXml' => urlencode($dadosXml)]);

            // Faz as requisições dos dados e dos totais
            $totais = $this->sac->buscar($entidade, $parametrosTotais);
            $dados = $this->sac->buscar($entidade, $parametrosDados);

            $resposta['draw']            = isset($requisicao['draw']) ? $requisicao['draw'] : 0;
            $resposta['recordsTotal']    = $totais['value'][0]['count'];
            $resposta['recordsFiltered'] = $totais['value'][0]['count'];
            $resposta['data']            = array_map(function ($item) {
                return [
                    'nome' => isset($item['tz_name']) ? $item['tz_name'] : '-',
                    'placa_veiculo' => isset($item['tz_veiculo1_x002e_tz_placa']) ? $item['tz_veiculo1_x002e_tz_placa'] : '-',
                    'data_instalacao' => isset($item['tz_data_instalacao@OData.Community.Display.V1.FormattedValue']) ? $item['tz_data_instalacao@OData.Community.Display.V1.FormattedValue'] : '-',
                    'data_desinstalacao' => isset($item['tz_data_desinstalacao@OData.Community.Display.V1.FormattedValue']) ? $item['tz_data_desinstalacao@OData.Community.Display.V1.FormattedValue'] : '-',
                    'nome_produto' => isset($item['product2_x002e_name']) ? $item['product2_x002e_name'] : '-',
                    'numero_serie' => isset($item['tz_numero_serie']) ? $item['tz_numero_serie'] : '-',
                    'id' => isset($item['tz_base_instalada_clienteid']) ? $item['tz_base_instalada_clienteid'] : '-',
                    'status' => $item['code']
                ];
            }, $dados['value']);

            $resposta['status'] = $dados['code'];
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($resposta);
            exit();
        } catch (Exception $e) {
            $mensagem = 'Erro ao buscar bases instaladas do cliente: ' . $e->getMessage();
            
            if($e->getCode() === 0) $mensagem = 'Base de dados está apresentando instabilidade, tente novamente em alguns minutos';

            echo json_encode([
                'status' => $e->getCode(),
                'error' => $mensagem
            ]);
        }
    }

    /**
     * Retorna bases instaladas do cliente
     */
    public function ajax_cotacoes($tipoCliente, $idCliente) {
        try {
            // Recupera os dados vindos da requisição
            $requisicao = $this->input->get();

            $entidade = 'quotes';

            // Recuperando o valor do item inicial a ser carregado
            $inicio = isset($requisicao['start']) && $requisicao['start'] != 1 ? $requisicao['start'] : 0;

            // Recupera o valor de quantos item devem ser carregados
            $tamanho = isset($requisicao['length']) ? $requisicao['length'] : 10;
            
            // Página que deve ser carrregada e mostrado ao usuário
            $pagina = ($inicio / $tamanho) + 1;

            // Recupera o valor da caixa de pesquisa para busca refinada entre os dados do cliente
            if (isset($requisicao['search']) && $requisicao['search']['value'] != '') {
                $pesquisa = $requisicao['search']['value'];
            }

            // Recupera e trata os dados do cliente para pesquisa
            $tipoCliente = isset($tipoCliente) ? ($tipoCliente == 'pj' ? 'tz_cliente_pjid' : 'tz_cliente_pfid'
            ) : null;
            $idCliente = isset($idCliente) ? $idCliente : null;

            // XML de requisição e busca dos dados na API do CRM
            $dadosXml = "
            <fetch version='1.0' mapping='logical' distinct='true' page='$pagina' count='$tamanho'>
                <entity name='quote'>
                    <attribute name='customerid' />
                    <attribute name='statecode' />
                    <attribute name='quoteid' />
                    <attribute name='createdon' />
                    <attribute name='statuscode' />
                    <attribute name='quotenumber' />
                    <attribute name='tz_docusign_status' />
                    <attribute name='effectiveto' />
                    <attribute name='effectivefrom' />
                    <attribute name='tz_valor_total_licenca' />
                    <attribute name='tz_valor_total_hardware' />
                    <attribute name='tz_analise_credito' />
                    <attribute name='tz_resultado_analise_credito' />
                    <order attribute='createdon' descending='true' />

                    <link-entity name='contact' from='contactid' to='customerid' visible='false' link-type='outer' alias='CLIENTE_PF'>
                        <attribute name='fullname' />
                    </link-entity>

                    <link-entity name='account' from='accountid' to='customerid' visible='false' link-type='outer' alias='CLIENTE_PJ'>
                        <attribute name='name' />
                    </link-entity>

                    <filter type='and'>                        
                        <condition attribute='customerid' operator='eq' uitype='account' value='$idCliente' />
                        <condition attribute='tz_docusign_status' operator='not-null' />
                    </filter>
                    {{ search }}
                </entity>
            </fetch>
            ";

            // Monta o XML de pesquisa em todos as colunas recuperadas para que a busca do DataTables
            // funcione corretamente.
            // TODO: Revisar a busca por Data (por enquanto faz apenas a busca por ano)
            if (isset($pesquisa)) {
                $pesquisaXml = "
                <filter type='or'>
                    <condition attribute='quotenumber' operator='like' value='%$pesquisa%' />
                ";

                // Verificar se a pesquisa é um ano válido
                if (is_numeric($pesquisa)) {
                    if ($pesquisa >= 1900 && $pesquisa <= 2100) {
                        $pesquisaXml .= "<condition attribute='tz_data_instalacao' operator='in-fiscal-year' value='$pesquisa' />";
                    }
                }

                $pesquisaXml .= "</filter>";

                $dadosXml = str_replace('{{ search }}', $pesquisaXml, $dadosXml);
            }

            $dadosXml = str_replace('{{ search }}', '', $dadosXml);

            // XML para contagem dos resultados totais
            $totalXml = "
            <fetch version='1.0' mapping='logical' aggregate='true'>
                <entity name='quote'>
                    <attribute name='quoteid' aggregate='count' alias='count' />
                    <filter>
                        <condition attribute='customerid' operator='eq' uitype='account' value='$idCliente' />
                        <condition attribute='tz_docusign_status' operator='not-null' />
                    </filter>
                    {{ search }}
                </entity>
            </fetch>
            ";

            // Insere os filtros adicionais do DataTables na requisição de contagem
            if (isset($pesquisaXml)) {
                $totalXml = str_replace('{{ search }}', $pesquisaXml, $totalXml);
            }

            // Faz a limpeza do XML de contagem, caso algum parâmetro não seja utilizado
            $totalXml = str_replace('{{ search }}', '', $totalXml);

            // Codifica os XML de consulta para serem passados através de uma requsicao de URL
            $parametrosTotais = http_build_query(['fetchXml' => urlencode($totalXml)]);
            $parametrosDados = http_build_query(['fetchXml' => urlencode($dadosXml)]);

            // Faz as requisições dos dados e dos totais
            $totais = $this->sac->buscar($entidade, $parametrosTotais);
            $dados = $this->sac->buscar($entidade, $parametrosDados);

            $resposta['draw']            = isset($requisicao['draw']) ? $requisicao['draw'] : 0;
            $resposta['recordsTotal']    = $totais['value'][0]['count'];
            $resposta['recordsFiltered'] = $totais['value'][0]['count'];

            $resposta['data']            = array_map(function ($item) {
                return [
                    'quotenumber' => isset($item['quotenumber']) ? $item['quotenumber'] : '-',
                    'createdon' => isset($item['createdon']) ? $item['createdon@OData.Community.Display.V1.FormattedValue'] : '-',
                    'statecode' => isset($item['statecode']) ? $item['statecode@OData.Community.Display.V1.FormattedValue'] : '-',
                    'tz_analise_credito' => isset($item['tz_analise_credito']) ? $item['tz_analise_credito@OData.Community.Display.V1.FormattedValue'] : '-',
                    'tz_valor_total_licenca' => isset($item['tz_valor_total_licenca']) ? $item['tz_valor_total_licenca@OData.Community.Display.V1.FormattedValue'] : '-',
                    'tz_valor_total_hardware' => isset($item['tz_valor_total_hardware']) ? $item['tz_valor_total_hardware@OData.Community.Display.V1.FormattedValue'] : '-',
                    'effectivefrom' => isset($item['effectivefrom']) ? $item['effectivefrom@OData.Community.Display.V1.FormattedValue'] : '-',
                    'effectiveto' => isset($item['effectiveto']) ? $item['effectiveto@OData.Community.Display.V1.FormattedValue'] : '-',
                    'id' => isset($item['quoteid']) ? $item['quoteid'] : '-'
                ];
            }, $dados['value']);

            $resposta['status'] = $dados['code'];
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($resposta);
            exit();
        } catch (Exception $e) {
            $mensagem = 'Erro ao buscar bases instaladas do cliente: ' . $e->getMessage();
            
            if($e->getCode() === 0) $mensagem = 'Base de dados está apresentando instabilidade, tente novamente em alguns minutos';

            echo json_encode([
                'status' => $e->getCode(),
                'error' => $mensagem
            ]);
        }

    }

    // Retorna Funções Programáveis
    public function ajax_funcoes_programaveis_by_cliente($idCliente) {

        $dados = get_funcoes_programaveis_by_cliente($idCliente);
        
        echo json_encode($dados);
    }

    public function ajax_grupo_funcoes_programaveis_by_cliente($idCliente) {

        $dados = get_grupos_funcoes_programaveis_by_cliente($idCliente);
        
        echo json_encode($dados);
    }

    public function ajax_grupos_funcoes_programaveis_by_cliente($idCliente) {

        $dados = get_funcoes_programaveis_by_cliente($idCliente);
        
        echo json_encode($dados);
    }

    public function ajax_all_grupo_funcoes_programaveis() {

        $dados = get_all_grupo_funcoes_programaveis();

        $funcoes = array();
   
        if ($dados["status"] == 200) {
            foreach($dados["resultado"] as $funcao) {
                $funcoes[] = array(
                    'id' => $funcao["idGrupoFuncProg"],
                    'text' => '[' . $funcao["idGrupoFuncProg"] . '] - ' . $funcao["descricao"] 
                );
            }

            echo json_encode(array(
                'status' => 200,
                'resultado' => $funcoes
            ));
        } else {
            echo json_encode($dados);
        }
    }

    function ajax_associar_grupo_funcao_programavel() {
        $idCliente = $this->input->post('idCliente');
        $idFuncaoProgramavel = $this->input->post('idFuncaoProgramavel');
        $descricao = $this->input->post('descricao');

        $POSTFIELDS = array(
            'idGrupoFunc' => $idFuncaoProgramavel,
            'idCliente' => $idCliente,
            'descricao' => $descricao
        );

        $dados = post_associar_Grupo_funcao_programavel($POSTFIELDS);

        echo json_encode($dados);
    }

    function ajax_desassociar_grupo_funcao_programavel() {
        $id = $this->input->post('id');
        $status = 0;

        $POSTFIELDS = array(
            'id' => $id,
            'status' => $status
        );

        $dados = post_desassociar_grupo_funcao_programavel($POSTFIELDS);

        echo json_encode($dados);
    }


    /**
     * Função que retorna as informações da base instalada para edição
     */
    public function ajax_get_info_base_instalada($idBaseInstalada) {
        try {
            if (!isset($idBaseInstalada)) echo json_encode(array('status' => 0, 'erro' => 'O ID da base instalada não foi informado'));

            $baseInstalada = $this->sac->get("tz_base_instalada_clientes($idBaseInstalada)", array(
                '$expand' => 'tz_cliente_pjid($select=name,accountid),
                    tz_cliente_pfid($select=contactid,fullname),
                    tz_cliente_pj_matrizid($select=name,accountid),
                    tz_cliente_pf_matrizid($select=contactid,fullname),
                    tz_cliente_pj_instaladoid($select=name,accountid),
                    tz_cliente_pf_instaladoid($select=contactid,fullname),
                    tz_veiculoid($select=tz_veiculoid,tz_placa, tz_cor),
                    tz_produtoid($select=productid,name),
                    tz_item_contratoid($select=tz_name,tz_item_contrato_vendaid),
                    tz_marcaid($select=tz_marcaid,tz_name),
                    tz_plataformaid($select=tz_plataformaid,tz_name),
                    tz_tecnologiaid($select=tz_name,tz_tecnologiaid),
                    tz_grupo_emails_clienteid($select=tz_grupo_emails_clienteid,tz_name),
                    tz_cliente_anterior_account($select=name,accountid)'
            ));

            if ($baseInstalada->code == 200) {
                $value = $baseInstalada->data;
                $resposta = array(
                    'inputs' => array(
                        "base_instalada_tz_base_instalada_clienteid" => $value->tz_base_instalada_clienteid,
                        "base_instalada_tz_modelo_ativacao" => isset($value->tz_modelo_ativacao) ? $value->tz_modelo_ativacao : '',
                        "base_instalada_tz_chassi" => isset($value->tz_chassi) ? $value->tz_chassi : '',
                        "base_instalada_tz_cor" => isset($value->tz_veiculoid) ? $value->tz_veiculoid->tz_cor : '',

                        "base_instalada_tz_numero_serie" => isset($value->tz_numero_serie) ? $value->tz_numero_serie : '',
                        "base_instalada_tz_tipo_produto" => isset($value->tz_tipo_produto) ? $value->tz_tipo_produto : '',
                        "base_instalada_tz_data_desinstalacao" => isset($value->tz_data_desinstalacao) ? date_format(date_create($value->tz_data_desinstalacao), 'Y-m-d') : '',
                        "base_instalada_tz_data_instalacao" => isset($value->tz_data_instalacao) ? date_format(date_create($value->tz_data_instalacao), 'Y-m-d') : '',
                        "base_instalada_tz_local_rastreador" => isset($value->tz_local_rastreador) ? $value->tz_local_rastreador : '',
                        "base_instalada_tz_simcard1" => isset($value->tz_simcard1) ? $value->tz_simcard1 : '',
                        "base_instalada_tz_linha1" => isset($value->tz_linha1) ? $value->tz_linha1 : '',
                        "base_instalada_tz_operadora1" => isset($value->tz_operadora1) ? $value->tz_operadora1 : '',
                        "base_instalada_tz_simcard2" => isset($value->tz_simcard2) ? $value->tz_simcard2 : '',
                        "base_instalada_tz_linha2" => isset($value->tz_linha2) ? $value->tz_linha2 : '',
                        "base_instalada_tz_operadora2" => isset($value->tz_operadora2) ? $value->tz_operadora2 : '',
                        "base_instalada_tz_versao_firmware" => isset($value->tz_versao_firmware) ? $value->tz_versao_firmware : '',
                        "base_instalada_tz_observacoes" => isset($value->tz_observacoes) ? $value->tz_observacoes : '',
                        "base_instalada_tz_ignicao" => ($value->tz_ignicao) ? 'true' : 'false',
                        "base_instalada_tz_bloqueio_solenoide" => ($value->tz_bloqueio_solenoide) ? 'true' : 'false',
                        "base_instalada_tz_boto_panico" => ($value->tz_boto_panico) ? 'true' : 'false',
                        "base_instalada_tz_bloqueio_eletronico" => ($value->tz_bloqueio_eletronico) ? 'true' : 'false',
                        "base_instalada_tz_painel" => ($value->tz_painel) ? 'true' : 'false',
                        "base_instalada_tz_trava_bau_traseira" => ($value->tz_trava_bau_traseira) ? 'true' : 'false',
                        "base_instalada_tz_portas_cabine" => ($value->tz_portas_cabine) ? 'true' : 'false',
                        "base_instalada_tz_trava_bau_lateral" => ($value->tz_trava_bau_lateral) ? 'true' : 'false',
                        "base_instalada_tz_bau_traseiro" => ($value->tz_bau_traseiro) ? 'true' : 'false',
                        "base_instalada_tz_trava_bau_intermediria" => ($value->tz_trava_bau_intermediria) ? 'true' : 'false',
                        "base_instalada_tz_bau_lateral" => ($value->tz_bau_lateral) ? 'true' : 'false',
                        "base_instalada_tz_trava_quinta_roda" => ($value->tz_trava_quinta_roda) ? 'true' : 'false',
                        "base_instalada_tz_bau_intermediario" => ($value->tz_bau_intermediario) ? 'true' : 'false',
                        "base_instalada_tz_teclado" => ($value->tz_teclado) ? 'true' : 'false',
                        "base_instalada_tz_engate_aspiral" => ($value->tz_engate_aspiral) ? 'true' : 'false',
                        "base_instalada_tz_teclado_multimidia" => ($value->tz_teclado_multimidia) ? 'true' : 'false',
                        "base_instalada_tz_engate_eletronico" => ($value->tz_engate_eletronico) ? 'true' : 'false',
                        "base_instalada_tz_bateria_backup" => ($value->tz_bateria_backup) ? 'true' : 'false',
                        "base_instalada_tz_temperatura" => ($value->tz_temperatura) ? 'true' : 'false',
                        "base_instalada_tz_telemetria" => ($value->tz_telemetria) ? 'true' : 'false',
                        "base_instalada_tz_sirene" => ($value->tz_sirene) ? 'true' : 'false',
                        "base_instalada_tz_painel_read_switch" => ($value->tz_painel_read_switch) ? 'true' : 'false',
                        "base_instalada_tz_setas_pulsantes" => ($value->tz_setas_pulsantes) ? 'true' : 'false',
                        "base_instalada_tz_setas_continuas" => ($value->tz_setas_continuas) ? 'true' : 'false',
                        "base_instalada_tz_painel_micro_switch" => ($value->tz_painel_micro_switch) ? 'true' : 'false',
                        "base_instalada_tz_tipo_trava_bau"         => isset($value->tz_tipo_trava_bau)         ? $value->tz_tipo_trava_bau         : null,
                        "base_instalada_tz_imobilizador_bt5"    => ($value->tz_imobilizador_bt5) ? 'true' : 'false',
                        "base_instalada_tz_sensor_configuravel1_1" => isset($value->tz_sensor_configuravel1_1) ? $value->tz_sensor_configuravel1_1 : null,
                        "base_instalada_tz_sensor_configuravel2_1" => isset($value->tz_sensor_configuravel2_1) ? $value->tz_sensor_configuravel2_1 : null,
                        "base_instalada_tz_sensor_configuravel3_1" => isset($value->tz_sensor_configuravel3_1) ? $value->tz_sensor_configuravel3_1 : null,
                        "base_instalada_tz_sensor_configuravel4_1" => isset($value->tz_sensor_configuravel4_1) ? $value->tz_sensor_configuravel4_1 : null,
                        
                    ),
                    'selects' => array(
                        'base_instalada_tz_cliente_anterior' => array(
                            'id' => isset($value->tz_cliente_anterior_account) ? $value->tz_cliente_anterior_account->accountid : null,
                            'text' => isset($value->tz_cliente_anterior_account) ? $value->tz_cliente_anterior_account->name : null
                        ),
                        'base_instalada_tz_cliente_pjid' => array(
                            'id' => isset($value->tz_cliente_pjid) ? $value->tz_cliente_pjid->accountid : null,
                            'text' => isset($value->tz_cliente_pjid) ? $value->tz_cliente_pjid->name : null
                        ),
                        'base_instalada_tz_cliente_pfid' => array(
                            'id' => isset($value->tz_cliente_pfid) ? $value->tz_cliente_pfid->contactid : null,
                            'text' => isset($value->tz_cliente_pfid) ? $value->tz_cliente_pfid->fullname : null
                        ),
                        'base_instalada_tz_cliente_pj_matrizid' => array(
                            'id' => isset($value->tz_cliente_pj_matrizid) ? $value->tz_cliente_pj_matrizid->accountid : null,
                            'text' => isset($value->tz_cliente_pj_matrizid) ? $value->tz_cliente_pj_matrizid->name : null
                        ),
                        'base_instalada_tz_cliente_pf_matrizid' => array(
                            'id' => isset($value->tz_cliente_pf_matrizid) ? $value->tz_cliente_pf_matrizid->fullname : null,
                            'text' => isset($value->tz_cliente_pf_matrizid) ? $value->tz_cliente_pf_matrizid->contactid : null
                        ),
                        'base_instalada_tz_cliente_pj_instaladoid' => array(
                            'id' => isset($value->tz_cliente_pj_instaladoid) ? $value->tz_cliente_pj_instaladoid->contactid : null,
                            'text' => isset($value->tz_cliente_pj_instaladoid) ? $value->tz_cliente_pj_instaladoid->fullname : null
                        ),
                        'base_instalada_tz_cliente_pf_instaladoid' => array(
                            'id' => isset($value->tz_cliente_pf_instaladoid) ? $value->tz_cliente_pf_instaladoid->contactid : null,
                            'text' => isset($value->tz_cliente_pf_instaladoid) ? $value->tz_cliente_pf_instaladoid->fullname : null
                        ),
                        'base_instalada_tz_veiculoid' => array(
                            'id' => isset($value->tz_veiculoid) ? $value->tz_veiculoid->tz_veiculoid : null,
                            'text' => isset($value->tz_veiculoid) ? $value->tz_veiculoid->tz_placa : null
                        ),
                        'base_instalada_tz_item_contratoid' => array(
                            'id' => isset($value->tz_item_contratoid) ? $value->tz_item_contratoid->tz_item_contrato_vendaid : null,
                            'text' => isset($value->tz_item_contratoid) ? $value->tz_item_contratoid->tz_name : null
                        ),
                        'base_instalada_tz_marcaid' => array(
                            'id' => isset($value->tz_marcaid) ? $value->tz_marcaid->tz_marcaid : null,
                            'text' => isset($value->tz_marcaid) ? $value->tz_marcaid->tz_name : null
                        ),
                        'base_instalada_tz_produtoid' => array(
                            'id' => isset($value->tz_produtoid) ? $value->tz_produtoid->productid : null,
                            'text' => isset($value->tz_produtoid) ? $value->tz_produtoid->name : null
                        ),
                        'base_instalada_tz_plataformaid' => array(
                            'id' => $value->tz_plataformaid->tz_plataformaid,
                            'text' => $value->tz_plataformaid->tz_name
                        ),
                        'base_instalada_tz_tecnologiaid' => array(
                            'id' => isset($value->tz_tecnologiaid) ? $value->tz_tecnologiaid->tz_tecnologiaid : null,
                            'text' => isset($value->tz_tecnologiaid) ? $value->tz_tecnologiaid->tz_name : null
                        ),
                        'base_instalada_tz_grupo_emails_clienteid' => array(
                            'id' => isset($value->tz_grupo_emails_clienteid) ? $value->tz_grupo_emails_clienteid->tz_grupo_emails_clienteid : null,
                            'text' => isset($value->tz_grupo_emails_clienteid) ? $value->tz_grupo_emails_clienteid->tz_name : null
                        ),
                    ),
                );

                echo json_encode(array('status' => 1, 'data' => $resposta));
            } else {
                echo json_encode(array('status' => 0, 'erro' => 'Erro ao buscar base instalada! ' . $baseInstalada->data));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 0, 'erro' => 'Erro ao buscar base instalada! ' . $th->getMessage()));
        }
    }

    /**
     * Cadastra base instalada do cliente
     */
    public function ajax_cadastrar_base_instalada(){
        try {
            $dados = $this->input->post();
            $nomeBaseInstalada = $this->criar_nome_base_instalada($dados);
            $body = $this->getBodyBaseInstalada($dados);
            $body['tz_name'] = $nomeBaseInstalada;
    
            // verifica se já existe alguma base cadastrada no banco
            $basesCadastradas = $this->get_bases_instaladas_by_name($nomeBaseInstalada);
            
            if(isset($basesCadastradas) && count($basesCadastradas) > 0){
                echo json_encode(array('status' => 0, 'erro' => "Já existe uma base instalada com o nome : ". $nomeBaseInstalada));
            }else{
                $response = $this->sac->post('tz_base_instalada_clientes', $body);
                
                if($response->code == 201){
                    $idBaseInstalada = $response->data->tz_base_instalada_clienteid;

                    if(isset($dados['base_instalada_tz_cor']) && $dados['base_instalada_tz_cor'] != "" ) {
                        $bodyCor = array(
                            'tz_cor' => $dados['base_instalada_tz_cor']
                        );

                        if(isset($dados['base_instalada_tz_veiculoid']) && $dados['base_instalada_tz_veiculoid'] != "") {
                            $idVeiculo = $dados['base_instalada_tz_veiculoid'];
                            $responseCor = $this->sac->patch('tz_veiculos', $idVeiculo, $bodyCor);

                            if ($responseCor->code == 200) {
                                $baseInstalada = $this->get_base_instalada_by_id($idBaseInstalada);
                                echo json_encode(array('status' => 1, 'data' => $baseInstalada, 'mensagem' =>'Base instalada criada com sucesso!'));
                            } else {
                                $baseInstalada = $this->get_base_instalada_by_id($idBaseInstalada);
                                echo json_encode(array('status' => 1, 'data' => $baseInstalada, 'mensagem' =>'Base instalada criada com sucesso! No entanto, não foi possível salvar o campo "cor" do veículo.'));
                            }
                        } else {
                            $baseInstalada = $this->get_base_instalada_by_id($idBaseInstalada);
                            echo json_encode(array('status' => 1, 'data' => $baseInstalada, 'mensagem' =>'Base instalada criada com sucesso! No entando o campo "cor" não foi salvo, pois não há veículo selecionado.'));
                        }
                        
                    } else {
                        $baseInstalada = $this->get_base_instalada_by_id($idBaseInstalada);
                        echo json_encode(array('status' => 1, 'data' => $baseInstalada, 'mensagem' =>'Base instalada criada com sucesso!'));
                    }
                }else{
                    $erro = $response->data->error ? $response->data->error->message : 'Erro ao cadastrar base instalada!';
                    echo json_encode(array('status' => 0, 'erro' => $erro));
                }
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 0, 'erro' => 'Erro ao cadastrar base instalada! '. $th->getMessage()));
        }
    }

    public function ajax_buscar_bases_instaladas_placa_serial() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $dados = $this->input->post();

            $valorFiltro    = $dados['valor'];
            $atributoFiltro = $dados['filtro'] == 'serial'          ? 'tz_numero_serie' : 'tz_placa';
            $entidadeFiltro = $atributoFiltro  == 'tz_numero_serie' ? ''                : "entityname='tz_veiculo'";

            $xml = urlencode("
            <fetch mapping='logical'>
                <entity name='tz_base_instalada_cliente'>
                    <attribute name='tz_name' alias='nome' />
                    <attribute name='tz_numero_serie' alias='serial' />
                    <attribute name='tz_base_instalada_clienteid' alias='id' />
                    <link-entity name='tz_veiculo' to='tz_veiculoid' alias='veiculo' link-type='outer'>
                        <attribute name='tz_placa' alias='placa' />
                    </link-entity>
                    <link-entity name='account' to='tz_cliente_pjid' alias='pj' link-type='outer'>
                        <attribute name='name' alias='nomepj' />
                        <attribute name='zatix_cnpj' alias='cnpj' />
                    </link-entity>
                    <link-entity name='contact' to='tz_cliente_pfid' alias='pf' link-type='outer'>
                        <attribute name='zatix_cpf' alias='cpf' />
                        <attribute name='fullname' alias='nomepf' />
                    </link-entity>
                    <filter>
                        <condition $entidadeFiltro attribute='$atributoFiltro' operator='like' value='%$valorFiltro%' />
                    </filter>
                </entity>
            </fetch>
            ");

            $resposta = $this->sac->buscar('tz_base_instalada_clientes', http_build_query(['fetchXml' => $xml]));

            echo json_encode([
                'data'  => isset($resposta['value']) ? array_map(function ($item) {
                    return [
                        'nome'   => isset($item['nome'])   ? $item['nome']   : '-',
                        'placa'  => isset($item['placa'])  ? $item['placa']  : '-',
                        'serial' => isset($item['serial']) ? $item['serial'] : '-',
                        'nomepf' => isset($item['nomepf']) ? $item['nomepf'] : null,
                        'cpf'    => isset($item['cpf'])    ? $item['cpf']    : null,
                        'nomepj' => isset($item['nomepj']) ? $item['nomepj'] : null,
                        'cnpj'   => isset($item['cnpj'])   ? $item['cnpj']   : null,
                        'id'     => isset($item['id'])     ? $item['id']     : '-',
                    ];
                }, $resposta['value']) : [],
                'error' => isset($resposta['error']) ? $resposta['error'] : ''
            ]);
            exit();
        } catch (Exception $e) {
            echo json_encode(['error' => $e]);
            exit();
        }
    }

    /**
     * Função que edita a alteração de contrato
     */
    public function ajax_editar_alteracao_contrato($idAlteracaoContrato){
       
        try {
            
            $dados = $this->input->post();
            $body = $this->getBodyRequestAlteracaoDeContrato($dados);
            
            $response = $this->sac->patch('tz_manutencao_contratos', $idAlteracaoContrato, $body);

            if($response->code == 200){
                $alteracao = $this->getAlteracaoDeContrato($idAlteracaoContrato);
                echo json_encode(array("status" => 1, "data" => $alteracao));
            }else{
                echo json_encode(array("status" => 0, "erro" => $response->data));
            }
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array("status" => 0, "erro" => "Erro ao editar alteração de contrato. ". $th->getMessage()));
        }
    }

    public function ajax_remover_alteracao_contrato($idAlteracaoContrato){
        try {
            if(isset($idAlteracaoContrato)){
                $response = $this->sac->delete('tz_manutencao_contratos',$idAlteracaoContrato);

                if($response->code == 204){
                    echo json_encode(array('status' => 1, 'msg' => "Alteração de contrato removido com sucesso!"));
                }
            }else{
                echo json_encode(array('status' => 0, 'erro' => "Erro ao excluir alteração de contrato! Alteração de contrato não informado."));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 0, 'erro' => "Erro ao excluir alteração de contrato! ". $th->getMessage()));
        }
    }
    /**
     * Retorna o body da requisição de ALteração de contrato formatado
     */
    public function getBodyRequestAlteracaoDeContrato($dados){
        $body = array();

        if(isset($dados["alteracao_contrato_tz_motivoid"]) && $dados["alteracao_contrato_tz_motivoid"] != ""){
            $body["tz_motivoid@odata.bind"] =  "/tz_motivo_cancelamentos(". $dados["alteracao_contrato_tz_motivoid"] .")";
        }
        if(isset($dados["alteracao_contrato_statuscode"]) && $dados["alteracao_contrato_statuscode"] != "" ){
            $body["tz_status_manutencao"] = $dados["alteracao_contrato_statuscode"];
        }
        if(isset($dados["alteracao_contrato_tz_incidentid"]) && $dados["alteracao_contrato_tz_incidentid"] != "" && $dados["alteracao_contrato_tz_incidentid"] != "undefined"){
            $body["tz_ocorrenciaid@odata.bind"] = "/incidents(".$dados["alteracao_contrato_tz_incidentid"].")";
        }
        if(isset($dados["alteracao_contrato_tz_data_ultima_comunicacao"]) && $dados["alteracao_contrato_tz_data_ultima_comunicacao"] != ""){
            $body["tz_data_ultima_comunicacao"] = (new DateTime($dados["alteracao_contrato_tz_data_ultima_comunicacao"]))->format('Y-m-d\TH:i:s\Z');
        }
        if(isset($dados["alteracao_contrato_tz_numero_serie"]) && $dados["alteracao_contrato_tz_numero_serie"] != ""){
            $body["tz_numero_serie"] = $dados["alteracao_contrato_tz_numero_serie"];
        }
        if(isset($dados["alteracao_contrato_tz_numero_serie_antena_satelital"]) && $dados["alteracao_contrato_tz_numero_serie_antena_satelital"] != ""){
            $body["tz_numero_serie_antena_satelital"] = $dados["alteracao_contrato_tz_numero_serie_antena_satelital"];
        }
        if(isset($dados["alteracao_contrato_tz_rastreadorid"]) && $dados["alteracao_contrato_tz_rastreadorid"] != ""){
            $body["tz_rastreadorid@odata.bind"] = "/products(".$dados["alteracao_contrato_tz_rastreadorid"].")";
        }
        if(isset($dados["alteracao_contrato_tz_planoid"]) && $dados["alteracao_contrato_tz_planoid"] != ""){
            $body["tz_planoid@odata.bind"] = "/tz_plano_satelitals(".$dados["alteracao_contrato_tz_planoid"].")";
        }
        if(isset($dados["alteracao_contrato_tz_periodosuspensao"]) && $dados["alteracao_contrato_tz_periodosuspensao"] != ""){
            $body["tz_periodosuspensao"] = intval($dados["alteracao_contrato_tz_periodosuspensao"]);
        }
        if(isset($dados["alteracao_contrato_tz_data_aniversario"]) && $dados["alteracao_contrato_tz_data_aniversario"] != ""){
            $body["tz_data_termino_garantia"] = (new DateTime($dados["alteracao_contrato_tz_data_aniversario"]))->format('Y-m-d\TH:i:s\Z');
        }
        if(isset($dados["alteracao_contrato_tz_data_termino_garantia"]) && $dados["alteracao_contrato_tz_data_termino_garantia"] != ""){
            $body["tz_data_aniversario"] = (new DateTime($dados["alteracao_contrato_tz_data_termino_garantia"]))->format('Y-m-d\TH:i:s\Z');
        }
        if(isset($dados["alteracao_contrato_tz_data_inicio_garantia"]) && $dados["alteracao_contrato_tz_data_inicio_garantia"] != ""){
            $body["tz_data_inicio_garantia"] = (new DateTime($dados["alteracao_contrato_tz_data_inicio_garantia"]))->format('Y-m-d\TH:i:s\Z');
        }
        if(isset($dados["alteracao_contrato_tz_isencaodetaxa"]) && $dados["alteracao_contrato_tz_isencaodetaxa"] != ""){
            $body["tz_isencaodetaxa"] = $dados["alteracao_contrato_tz_isencaodetaxa"] == "true" ? true : false;
        }
        if(isset($dados["alteracao_contrato_tz_providencias"]) && $dados["alteracao_contrato_tz_providencias"] != ""){
            $body["tz_providencias@odata.bind"] = "/tz_providencias(".$dados["alteracao_contrato_tz_providencias"] .")";
        }
        if(isset($dados["alteracao_contrato_tz_veiculo_id"]) && $dados["alteracao_contrato_tz_veiculo_id"] != ""){
            $body["tz_veiculoid@odata.bind"] = "/tz_veiculos(".$dados["alteracao_contrato_tz_veiculo_id"] .")";
        }
        if(isset($dados["alteracao_contrato_tz_motivo_cancelamentoid"]) && $dados["alteracao_contrato_tz_motivo_cancelamentoid"] != ""){
            $body["tz_motivo_cancelamentoid@odata.bind"] = "/tz_motivo_cancelamentos(".$dados["alteracao_contrato_tz_motivo_cancelamentoid"] .")";
        }
        if(isset($dados["alteracao_contrato_tz_modelo_tipo_ativacao"]) && $dados["alteracao_contrato_tz_modelo_tipo_ativacao"] != ""){
            $body["tz_modelo_tipo_ativacao"] = $dados["alteracao_contrato_tz_modelo_tipo_ativacao"];
        }
        if(isset($dados["alteracao_contrato_tz_indice_reajusteid"]) && $dados["alteracao_contrato_tz_indice_reajusteid"] != ""){
            $body["tz_indice_reajusteid@odata.bind"] = "/tz_indice_reajustes(" .$dados["alteracao_contrato_tz_indice_reajusteid"] .")";
        }
        if(isset($dados["alteracao_contrato_tz_providencias"]) && $dados["alteracao_contrato_tz_providencias"] != ""){
            $body["tz_providencias@odata.bind"] = "/tz_providencias(" .$dados["alteracao_contrato_tz_providencias"] .")";
        }
        
        return $body;
    }

    /**
     * Retorna a alteração de contrato que será adicionada na tabela pelo id da alteração
     */
    public function getAlteracaoDeContrato($idAlteracao){
        // Busca alteração de contrato // tz_manutencao_contrato_lotes
        $alteracao_contrato = $this->sac->get('tz_manutencao_contratos',array(
            '$expand' => 'tz_motivo_cancelamentoid($select=tz_name),tz_veiculoid($select=tz_placa),tz_item_contratoid,tz_item_contratoid($select=tz_name)',
            '$filter' => 'tz_manutencao_contratoid eq '. $idAlteracao
        ));

        if($alteracao_contrato->code == 200){
            $alteracao = $alteracao_contrato->data->value[0];

            return array(
                'tz_manutencao_contratoid' => $alteracao->tz_manutencao_contratoid,
                'tz_name' => isset($alteracao->tz_name) ? $alteracao->tz_name : null,
                'tz_veiculo' => isset($alteracao->tz_veiculoid->tz_placa) ? $alteracao->tz_veiculoid->tz_placa : null,
                'tz_item_contrato' => isset($alteracao->tz_item_contratoid->tz_name) ? $alteracao->tz_item_contratoid->tz_name : null,
                'tz_modelo_tipo_ativacao' => isset($alteracao->tz_modelo_tipo_ativacao) ? $alteracao->tz_modelo_tipo_ativacao: null,
                'statecode' => isset($alteracao->statecode) ? $alteracao->statecode : null,
                'statuscode' => isset($alteracao->statuscode) ? $alteracao->statuscode : null,
                'createdon' => isset($alteracao->createdon) ? $alteracao->createdon: null,
                'acoes' => $this->getBtnAcoesAlteracaoDeContratos($alteracao->tz_manutencao_contratoid),
                'tz_valortaxa ' => isset($alteracao->tz_valortaxa) ? $alteracao->tz_valortaxa : null
            );
        }else{
            return [];
        }
    }
            
    /**
     * Função que cria o nome da base instalada. Para criar, concatena o código do cliente do contrato com o número de
     * série do equipamento
     * @param Array $dados
     * @return String
     */
    private function criar_nome_base_instalada($dados){
        
        $cliente = null;
        if(isset($dados['base_instalada_tz_cliente_pjid'])){
            $response = $this->sac->get('accounts('.$dados['base_instalada_tz_cliente_pjid'].')', array());
            if($response->code == 200) $cliente = $response->data;
        }else{
            $response = $this->sac->get('contacts('.$dados['base_instalada_tz_cliente_pjid'].')', array());
            if($response->code == 200) $cliente = $response->data;
        }

        $itensNome = array();
        if(isset($cliente)){
            array_push($itensNome, $cliente->zatix_codigocliente);
        }
        if(isset($dados['base_instalada_tz_numero_serie'])){
            array_push($itensNome, $dados['base_instalada_tz_numero_serie']);
        }
        
        return implode('-', $itensNome);
    }

    /**
     * Retorna bases instaladas cadastradas com o nome informado
     * @param String $nome
     * @return Array
     */
    private function get_bases_instaladas_by_name($nome){
        $response = $this->sac->get('tz_base_instalada_clientes', array(
            '$filter' => 'tz_name eq \''.$nome.'\''
        ));
        
        if($response->code == 200){
            return $response->data->value ? $response->data->value : [];
        }else{
            return [];
        }
    }

    /**
     * Retorna os botões de ações da tabela de alterações de contrato
     */
    private function getBtnAcoesAlteracaoDeContratos($idAlteracao){
        return '
            <button type="button" class="btn btn-primary btnEditAlteracaoContrato" title="Editar Alteração de Contrato" onclick="getInfoAlteracaoContrato(this, \''.$idAlteracao.'\')"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger btnDeleteAlteracaoContrato" title="Remover Alteração de Contrato" onclick="removerAlteracaoDeContrato(this, \''.$idAlteracao.'\')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        ';
    }


    /**
     * Função que realiza cadastro dde serviço contratado
     */
    public function ajax_adicionar_servico_contratado(){
        try {
            $dados = $this->input->post();
            
            if(isset($dados)){
                //verifica se o nome do serviço contratado existe. Caso exista, retorna true
                if(!$this->nomeServicoContratadoExiste($dados['servico_contratado_tz_name'])){
                    unset($dados['idCliente']);
                    unset($dados['clientEntity']);
                    $body = $this->getBodyRequestServicoContratado($dados);
    
                    $response = $this->sac->post('tz_produto_servico_contratados', $body);
                    
                    if($response->code == 201){
                        $idServico = $response->data->tz_produto_servico_contratadoid;
                        $servico = $this->getServicoContratado($idServico);
                        
                        echo json_encode(array('status' => 1, 'data' => $servico));
                    }else{
                        echo json_encode(array(
                            'status' => 0, 
                            'erro' => isset($response->data->error) ? $response->data->error->message : 'Erro ao cadastrar serviço contratado'
                        ));
                    }
                }else{
                    echo json_encode(array('status' => 0, 'erro' => 'O nome do serviço contratado já existe!'));
                }
            
            }else{
                    echo json_encode(array('status' => 0, 'erro' => 'Forneça os dados para cadastrar o serviço contratado'));
            }

        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array('status' => 0, 'erro' => 'Erro ao cadastrar alteração de contrato! '. $th->getMessage()));
        }
    }
    /**
     * Verifica se o nome do serviço contratado já existe. Caso sim, retorna true, senão retorna false
     * @param String $tz_name
     * @return Boolean
     */
    private function nomeServicoContratadoExiste($tz_name){
        try {
            //code...
            $servicoContratado = $this->sac->get('tz_produto_servico_contratados', array(
                '$select' => 'tz_produto_servico_contratadoid,tz_name',
                '$filter' => "tz_name eq '{$tz_name}'"
            ));
            if($servicoContratado->code == 200){
                if(count($servicoContratado->data->value) > 0){
                    return true;
                }else{
                    return false;
                }
            }else{
                throw 'Erro ao buscar nome do serviço contratado';
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
    /**
     * Função que realiza cadastro dde serviço contratado
     */
    public function ajax_editar_servico_contratado($idServico){
        try {
            if($idServico){
                $dados = $this->input->post();
                if(isset($dados)){
                    unset($dados['idCliente']);
                    unset($dados['clientEntity']);
                    $body = $this->getBodyRequestServicoContratado($dados);                    
                    
                    $response = $this->sac->patch('tz_produto_servico_contratados', $idServico, $body);
                    
                    if($response->code == 200){
                        $idServico = $response->data->tz_produto_servico_contratadoid;
                        $servico = $this->getServicoContratado($idServico);
                        
                        echo json_encode(array('status' => 1, 'data' => $servico));
                        exit;
                    }else{
                        echo json_encode(array(
                            'status' => 0, 
                            'erro' => isset($response->data->error) ? $response->data->error->message : 'Erro ao editar serviço contratado')
                        );
                        exit;
                    }
                
                }
            }else{
                echo json_encode(array('status' => 0, 'erro' => 'Erro ao editar serviço contratado! O ID do serviço contratado não foi informado.'));    
            }

        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array('status' => 0, 'erro' => 'Erro ao editar serviço contratado! '. $th->getMessage()));
        }
    }

    private function getBodyRequestServicoContratado($dados){
        $body = array();


        if(isset($dados['servico_contratado_tz_name']) && $dados['servico_contratado_tz_name'] != ""){
            $body['tz_name'] = $dados['servico_contratado_tz_name'];
        }
        if(isset($dados['id_item_de_contrato']) && $dados['id_item_de_contrato'] != ""){
            $body['tz_codigo_item_contratoid@odata.bind'] = '/tz_item_contrato_vendas('.$dados['id_item_de_contrato'].')';
        }
        if(isset($dados['servico_contratado_tz_codigo_item_contratoid']) && $dados['servico_contratado_tz_codigo_item_contratoid'] != ""){
            $body['tz_codigo_item_contratoid@odata.bind'] = '/tz_item_contrato_vendas('.$dados['servico_contratado_tz_codigo_item_contratoid'].')';
        }
        
        if(isset($dados['servico_contratado_tz_classificacao_produtoid']) && $dados['servico_contratado_tz_classificacao_produtoid'] != ""){
            $body['tz_classificacao_produtoid@odata.bind'] = '/tz_classificacao_produtos('.$dados['servico_contratado_tz_classificacao_produtoid'].')';
            
        }
        
        if(isset($dados['servico_contratado_tz_produtoid']) && $dados['servico_contratado_tz_produtoid'] != ""){
            $body['tz_produtoid@odata.bind'] = '/products('.$dados['servico_contratado_tz_produtoid'].')';
            
        }

        if(isset($dados['servico_contratado_tz_quantidade']) && $dados['servico_contratado_tz_quantidade'] != ""){
            $body['tz_quantidade'] = floatval($dados['servico_contratado_tz_quantidade']);
        }
        if(isset($dados['servico_contratado_tz_valor_contratado']) && $dados['servico_contratado_tz_valor_contratado'] != ""){
            $body['tz_valor_contratado'] = floatval($dados['servico_contratado_tz_valor_contratado']);
        }
        if(isset($dados['servico_contratado_tz_data_inicio']) && $dados['servico_contratado_tz_data_inicio'] != ""){
            $body['tz_data_inicio'] = $dados['servico_contratado_tz_data_inicio'];
        }
        if(isset($dados['servico_contratado_tz_data_termino']) && $dados['servico_contratado_tz_data_termino'] != ""){
            $body['tz_data_termino'] = $dados['servico_contratado_tz_data_termino'];
        }
        if(isset($dados['servico_contratado_tz_data_fim_carencia']) && $dados['servico_contratado_tz_data_fim_carencia'] != ""){
            $body['tz_data_fim_carencia'] = $dados['servico_contratado_tz_data_fim_carencia'];
        }
        if(isset($dados['servico_contratado_tz_descricao']) && $dados['servico_contratado_tz_descricao'] != ""){
            $body['tz_descricao'] = $dados['servico_contratado_tz_descricao'];
        }
        if(isset($dados['servico_contratado_tz_grupo_receitaid']) && $dados['servico_contratado_tz_grupo_receitaid'] != ""){
            $body['tz_grupo_receitaid@odata.bind'] = "/tz_grupo_receitas(".$dados['servico_contratado_tz_grupo_receitaid'].")";
        }
        

        return $body;
    }
    /**
     * Busca serviço contratado para ser exibido no modal
     */
    public function ajax_buscar_servico_contratado($idServico){
        try {
            if(isset($idServico)){
                $servico = $this->sac->get("tz_produto_servico_contratados({$idServico})",array(
                    '$select' => 'tz_name,tz_quantidade,tz_valor_contratado,tz_data_inicio,tz_data_termino,tz_data_fim_carencia,tz_descricao',
                    '$expand' => 'tz_codigo_item_contratoid($select=tz_name),
                        tz_produtoid($select=name),
                        tz_classificacao_produtoid($select=tz_classificacao_produtoid,tz_name),
                        tz_grupo_receitaid($select=tz_grupo_receitaid,tz_name),
                        transactioncurrencyid($select=currencyname),
                        tz_produtoid($select=productid,name)'
                ));
                if($servico->code == 200){
                    $dados = $servico->data;
                    $resposta = array();

                    $resposta['inputs'] = array(
                        "id_servico_contratado" => isset($dados->tz_produto_servico_contratadoid) ? $dados->tz_produto_servico_contratadoid : null,
                        "servico_contratado_tz_name" => isset($dados->tz_name) ? $dados->tz_name : null,
                        "servico_contratado_tz_quantidade" => isset($dados->tz_quantidade) ? $dados->tz_quantidade : null,
                        "servico_contratado_tz_valor_contratado" => isset($dados->tz_valor_contratado) ? $dados->tz_valor_contratado : null,
                        
                        "servico_contratado_tz_data_inicio" => isset($dados->tz_data_inicio) ? (new DateTime($dados->tz_data_inicio))->format('Y-m-d') : null,
                        "servico_contratado_tz_data_termino" => isset($dados->tz_data_termino) ? (new DateTime($dados->tz_data_termino))->format('Y-m-d') : null,
                        "servico_contratado_tz_data_fim_carencia" => isset($dados->tz_data_fim_carencia) ? (new DateTime($dados->tz_data_fim_carencia))->format('Y-m-d'): null,
                        "servico_contratado_tz_descricao" => isset($dados->tz_descricao) ? $dados->tz_descricao : null,
                    );
                    $resposta['selects'] = array(
                        "servico_contratado_tz_grupo_receitaid" => array(
                            'id' => isset($dados->tz_grupo_receitaid->tz_grupo_receitaid) ? $dados->tz_grupo_receitaid->tz_grupo_receitaid : null,
                            'text' => isset($dados->tz_grupo_receitaid->tz_name) ? $dados->tz_grupo_receitaid->tz_name : null,
                        ),
                        "servico_contratado_tz_classificacao_produtoid" => array(
                            'id' => isset($dados->tz_classificacao_produtoid) ? $dados->tz_classificacao_produtoid->tz_classificacao_produtoid : null,
                            'text' => isset($dados->tz_classificacao_produtoid->tz_name) ? $dados->tz_classificacao_produtoid->tz_name : null,
                        ),
                        "servico_contratado_transactioncurrencyid" => array(
                            'id' => isset($dados->_transactioncurrencyid_value) ? $dados->_transactioncurrencyid_value : null,
                            'text' => isset($dados->transactioncurrencyid->currencyname) ? $dados->transactioncurrencyid->currencyname : null,
                        ),
                        "servico_contratado_tz_produtoid" => array(
                            'id' => isset($dados->tz_produtoid->productid) ? $dados->tz_produtoid->productid : null,
                            'text' => isset($dados->tz_produtoid->name) ? $dados->tz_produtoid->name : null,
                        ),
    
                    );
    
                    echo json_encode(array('status'=>true, 'data' => $resposta));
                }else{
                    echo json_encode(array('status'=>false, 'erro' => $servico->data));
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array('status'=>false, 'erro' => "Erro ao buscar Serviço Contratado! ". $th->getMessage()));
        }
    }

    /**
     * Função que retorna servico contratado para exibir na tabela
     */
    private function getServicoContratado($idServico){
        $servico = $this->sac->get("tz_produto_servico_contratados({$idServico})",array(
            '$select' => 'tz_name,tz_quantidade,tz_valor_contratado,tz_data_inicio,tz_data_termino,tz_data_fim_carencia',
            '$expand' => 'tz_codigo_item_contratoid($select=tz_name),tz_produtoid($select=name),tz_classificacao_produtoid($select=tz_name),tz_grupo_receitaid($select=tz_name)'
        ));
        if($servico->code == 200){
            $data = $servico->data;

            return array(
                'tz_name' => isset($data->tz_name) ? $data->tz_name : null,
                'tz_quantidade' => isset($data->tz_quantidade) ? $data->tz_quantidade : null,
                'tz_valor_contratado' => isset($data->tz_valor_contratado) ? $data->tz_valor_contratado : null,
                'tz_data_inicio' => isset($data->tz_data_inicio) ? $data->tz_data_inicio : null,
                'tz_data_termino' => isset($data->tz_data_termino) ? $data->tz_data_termino : null,
                'tz_data_fim_carencia' => isset($data->tz_data_fim_carencia) ? $data->tz_data_fim_carencia : null,
                'tz_produto_servico_contratadoid' => isset($data->tz_produto_servico_contratadoid) ? $data->tz_produto_servico_contratadoid : null,
                'tz_codigo_item_contrato' => isset($data->tz_codigo_item_contratoid->tz_name) ? $data->tz_codigo_item_contratoid->tz_name : null,
                'tz_produto' => isset($data->tz_produtoid->name) ? $data->tz_produtoid->name : null,
                'tz_classificacao_produto' => isset($data->tz_classificacao_produtoidid->tz_name) ? $data->tz_classificacao_produtoidid->tz_name : null,
                'tz_grupo_receita' => isset($data->tz_grupo_receitaid->tz_name) ? $data->tz_grupo_receitaid->tz_name : null,
                'acoes' => $this->getButtonsAcaoServicoContratado($idServico)
            );
        }else{
            return [];
        }
    }
    
    /**
     * Função que retorna o body para requisiçao de atividade de serviço
     */
    private function get_body_atividade_de_servico($dados){
        $body = array();

        // atributos
        if(isset($dados['subject']) && $dados['subject'] != '') 
            $body['subject'] = $dados['subject'];

        if(isset($dados['nomeSolicitante']) && $dados['nomeSolicitante'] != '') 
            $body['tz_nome_solicitante'] = $dados['nomeSolicitante'];

        if(isset($dados['telefoneSolicitante']) && $dados['telefoneSolicitante'] != '') 
            $body['tz_telefone_solicitante'] = $dados['telefoneSolicitante'];

        if(isset($dados['na_tz_tipo_servico']) && $dados['na_tz_tipo_servico'] != '') 
            $body['tz_tipo_servico'] = intval($dados['na_tz_tipo_servico']);

        if(isset($dados['na_tz_agendar_sem_contrato']) && $dados['na_tz_agendar_sem_contrato'] != '') 
            $body['tz_agendar_sem_contrato'] = $dados['na_tz_agendar_sem_contrato'] == 'true' ? true : false;

        if(isset($dados['na_tz_local_atendimento']) && $dados['na_tz_local_atendimento'] != '') 
            $body['tz_local_atendimento'] = intval($dados['na_tz_local_atendimento']);

        if(isset($dados['na_tz_envia_email_alteracao']) && $dados['na_tz_envia_email_alteracao'] != '') 
            $body['tz_envia_email_alteracao'] = $dados['na_tz_envia_email_alteracao'] == 'true' ? true : false;

        if(isset($dados['na_tz_endereco_bairro']) && $dados['na_tz_endereco_bairro'] != '') 
            $body['tz_endereco_bairro'] = $dados['na_tz_endereco_bairro'];

        if(isset($dados['na_tz_endereco_rua']) && $dados['na_tz_endereco_rua'] != '') 
            $body['tz_endereco_rua'] = $dados['na_tz_endereco_rua'];

        if(isset($dados['na_tz_endereco_numero']) && $dados['na_tz_endereco_numero'] != '') 
            $body['tz_endereco_numero'] = intval($dados['na_tz_endereco_numero']);

        if(isset($dados['na_tz_endereco_complemento']) && $dados['na_tz_endereco_complemento'] != '') 
            $body['tz_endereco_complemento'] = $dados['na_tz_endereco_complemento'];

        if(isset($dados['na_tz_endereco_pais']) && $dados['na_tz_endereco_pais'] != '') 
            $body['tz_endereco_pais'] = $dados['na_tz_endereco_pais'];

        if(isset($dados['na_tz_referencia']) && $dados['na_tz_referencia'] != '') 
            $body['tz_referencia'] = $dados['na_tz_referencia'];

        if(isset($dados['na_tz_tcnicodocliente']) && $dados['na_tz_tcnicodocliente'] != '') 
            $body['tz_tcnicodocliente'] = $dados['na_tz_tcnicodocliente'] == 'true' ? true : false;

        if(isset($dados['na_tz_encaixe']) && $dados['na_tz_encaixe'] != '') 
            $body['tz_encaixe'] = $dados['na_tz_encaixe'] ? true : false;

        if(isset($dados['na_tz_data_minima_agendamento']) && $dados['na_tz_data_minima_agendamento'] != '')
            $body['tz_data_minima_agendamento'] = (new DateTime($dados['na_tz_data_minima_agendamento']))->format('Y-m-d\TH:i:s\Z');

        if(isset($dados['na_tz_motivo_encaixe']) && $dados['na_tz_motivo_encaixe'] != '') 
            $body['tz_motivo_encaixe'] = $dados['na_tz_motivo_encaixe'];

        if(isset($dados['na_tz_prestador_possui_peca_estoque']) && $dados['na_tz_prestador_possui_peca_estoque'] != '') 
            $body['tz_prestador_possui_peca_estoque'] = $dados['na_tz_prestador_possui_peca_estoque']  == 'true' ? true : false;

        if(isset($dados['na_tz_distancia_total']) && $dados['na_tz_distancia_total'] != '') 
            $body['tz_distancia_total'] = intval($dados['na_tz_distancia_total']);

        if(isset($dados['na_tz_distancia_bonificada']) && $dados['na_tz_distancia_bonificada'] != '') 
            $body['tz_distancia_bonificada'] = intval($dados['na_tz_distancia_bonificada']);

        if(isset($dados['na_tz_distancia_considerada']) && $dados['na_tz_distancia_considerada'] != '') 
            $body['tz_distancia_considerada'] = floatval($dados['na_tz_distancia_considerada']);

        if(isset($dados['na_tz_valor_km_considerado_cliente']) && $dados['na_tz_valor_km_considerado_cliente'] != '') 
            $body['tz_valor_km_considerado_cliente'] = floatval($dados['na_tz_valor_km_considerado_cliente']);

        if(isset($dados['na_tz_valor_total_deslocamento']) && $dados['na_tz_valor_total_deslocamento'] != '') 
            $body['tz_valor_total_deslocamento'] = floatval($dados['na_tz_valor_total_deslocamento']);

        if(isset($dados['na_tz_taxa_visita']) && $dados['na_tz_taxa_visita'] != '') 
            $body['tz_taxa_visita'] = floatval($dados['na_tz_taxa_visita']);

        if(isset($dados['na_scheduledstart']) && $dados['na_scheduledstart'] != '') 
            $body['scheduledstart'] = (new DateTime($dados['na_scheduledstart']))->format('Y-m-d\TH:i:s\Z');

        if(isset($dados['na_scheduledend']) && $dados['na_scheduledend'] != '') 
            $body['scheduledend'] = (new DateTime($dados['na_scheduledend']))->format('Y-m-d\TH:i:s\Z');

        
        // relacionamentos

        if(isset($dados['tz_item_contrato_vendas']) && $dados['tz_item_contrato_vendas'] != '') 
            $body['regardingobjectid_tz_item_contrato_venda_serviceappointment@odata.bind'] = '/tz_item_contrato_vendas('.$dados['tz_item_contrato_vendas'].')';

        if(isset($dados['serviceid']) && $dados['serviceid'] != '') 
            $body['serviceid_serviceappointment@odata.bind'] = "/services(".$dados['serviceid'].")";

        if(isset($dados['na_tz_endereco_estadoid']) && $dados['na_tz_endereco_estadoid'] != '') 
            $body['tz_endereco_estadoid_serviceappointment@odata.bind'] = "/tz_estados(".$dados['na_tz_endereco_estadoid'].")";

        if(isset($dados['na_tz_endereco_cidadeid']) && $dados['na_tz_endereco_cidadeid'] != '') 
            $body['tz_endereco_cidadeid_serviceappointment@odata.bind'] = "/tz_cidades(".$dados['na_tz_endereco_cidadeid'].")";

        if(isset($dados['prestador']) && $dados['prestador'] != '') 
            $body['siteid@odata.bind'] = "/sites(".$dados['prestador'].")";

        // if(isset($dados['recurso']) && $dados['recurso'] != '') 
        //     $body['equipmentid@odata.bind'] = "/equipments(".$dados['recurso'].")";

        if(isset($dados['na_tz_bloqueio_agendaid']) && $dados['na_tz_bloqueio_agendaid'] != '') 
            $body['regardingobjectid_tz_bloqueio_agenda_serviceappointment@odata.bind'] = "/tz_bloqueio_agendas(".$dados['na_tz_bloqueio_agendaid'].")";
        
        if(isset($dados['na_tz_rastreadorid']) && $dados['na_tz_rastreadorid'] != '') 
            $body['tz_rastreadorid_serviceappointment@odata.bind'] = "/products(".$dados['na_tz_rastreadorid'].")";

        if(isset($dados['na_tz_planoid']) && $dados['na_tz_planoid'] != '') 
            $body['tz_planoid_serviceappointment@odata.bind'] = "/products(".$dados['na_tz_planoid'].")";

        if(isset($dados["na_tz_veiculo_contratoid"]) && $dados["na_tz_veiculo_contratoid"] != "")
            $body['tz_veiculo_contratoid_serviceappointment@odata.bind'] = "/tz_veiculos(".$dados["na_tz_veiculo_contratoid"].")";

        if(isset($dados["na_tz_frota_afid"]) && $dados["na_tz_frota_afid"] != "")
            $body['tz_frota_afid_serviceappointment@odata.bind'] = "/tz_frotaafs(".$dados["na_tz_frota_afid"].")";

        if(isset($dados["na_tz_plataformaid"]) && $dados["na_tz_plataformaid"] != "")
            $body['tz_plataformaid_serviceappointment@odata.bind'] = "/tz_plataformas(".$dados["na_tz_plataformaid"].")";

        if(isset($dados["na_tz_cenario_vendaid"]) && $dados["na_tz_cenario_vendaid"] != "")
            $body['tz_cenario_vendaid_serviceappointment@odata.bind'] = "/tz_cenario_vendas(".$dados["na_tz_cenario_vendaid"].")";

        if(isset($dados["na_tz_marca_vendaid"]) && $dados["na_tz_marca_vendaid"] != "")
            $body['tz_marca_vendaid_serviceappointment@odata.bind'] = "tz_marcas(".$dados["na_tz_marca_vendaid"].")";

        if(isset($dados["na_tz_modelo_vendaid"]) && $dados["na_tz_modelo_vendaid"] != "")
            $body['tz_modelo_vendaid_serviceappointment@odata.bind'] = "/tz_modelos(".$dados["na_tz_modelo_vendaid"].")";

        if(isset($dados["na_tz_tipo_veiculo_vendaid"]) && $dados["na_tz_tipo_veiculo_vendaid"] != "")
            $body['tz_tipo_veiculo_vendaid_serviceappointment@odata.bind'] = "/tz_tipo_veiculos(".$dados["na_tz_tipo_veiculo_vendaid"].")";

        if(isset($dados["na_tz_endereco_cepid"]) && $dados["na_tz_endereco_cepid"] != "")
            $body['tz_endereco_cepid_serviceappointment@odata.bind'] = "/tz_ceps(".$dados["na_tz_endereco_cepid"].")";

        if(isset($dados["na_tz_emails_envio_agendamentoid"]) && $dados["na_tz_emails_envio_agendamentoid"] != "")
            $body['tz_emails_envio_agendamentoid_serviceappointment@odata.bind'] = "/tz_grupo_emails_clientes(".$dados["na_tz_emails_envio_agendamentoid"].")";

        if(isset($dados["na_tz_emails_envio_orcamentoid"]) && $dados["na_tz_emails_envio_orcamentoid"] != "")
            $body['tz_emails_envio_orcamentoid_serviceappointment@odata.bind'] = "/tz_grupo_emails_clientes(".$dados["na_tz_emails_envio_orcamentoid"].")";

        if(isset($dados["na_tz_cliente_pj_pagar_osid"]) && $dados["na_tz_cliente_pj_pagar_osid"] != "")
            $body['tz_cliente_pj_pagar_osid_serviceappointment@odata.bind'] = "/accounts(".$dados["na_tz_cliente_pj_pagar_osid"].")";

        if(isset($dados["na_tz_cliente_pf_pagar_osid"]) && $dados["na_tz_cliente_pf_pagar_osid"] != "")
            $body['tz_cliente_pf_pagar_osid_serviceappointment@odata.bind'] = "/contacts(".$dados["na_tz_cliente_pf_pagar_osid"].")";

        if(isset($dados["na_tz_motivo_agendamento_tardioid"]) && $dados["na_tz_motivo_agendamento_tardioid"] != "")
            $body['tz_motivo_agendamento_tardioid_serviceappointment@odata.bind'] = "/tz_motivo_agendamento_tardios(".$dados["na_tz_motivo_agendamento_tardioid"].")";
        return $body;
    }
    /**
     * Remove base instalada do cliente
     */
    public function ajax_remover_base_instalada($idBaseInstalada){
        try {
            if(isset($idBaseInstalada)){
                $response = $this->sac->delete('tz_base_instalada_clientes', $idBaseInstalada);
                if($response->code == 204){
                    echo json_encode(array(
                        'status' => 1,
                        'msg' => 'Base Instalada removida com sucesso!'
                    ));
                }
            }else{
                echo json_encode(array(
                    'status' => 0,
                    'erro' => 'Erro ao remover base instalada!'
                ));
            }
        } catch (\Throwable $th) {
            echo json_encode(array(
                'status' => 0,
                'erro' => 'Erro ao remover base instalada! '.$th->getMessage()
            ));
        }
    }
    
    /**
     * Função que realiza update da base instalada
     * @param String $idBaseInstalada
     */
    public function ajax_editar_base_instalada($idBaseInstalada) {
        try {
            if (isset($idBaseInstalada) && $idBaseInstalada != "") {
                $dados = $this->input->post();
                $body = $this->getBodyBaseInstalada($dados);
                $response = $this->sac->patch('tz_base_instalada_clientes', $idBaseInstalada, $body);

                if ($response->code == 200) {
                    if(isset($dados['base_instalada_tz_cor']) && $dados['base_instalada_tz_cor'] != "" ) {
                        $bodyCor = array(
                            'tz_cor' => $dados['base_instalada_tz_cor']
                        );

                        if(isset($dados['base_instalada_tz_veiculoid']) && $dados['base_instalada_tz_veiculoid'] != "") {
                            $idVeiculo = $dados['base_instalada_tz_veiculoid'];
                            $responseCor = $this->sac->patch('tz_veiculos', $idVeiculo, $bodyCor);

                            if ($responseCor->code == 200) {
                                $baseInstalada = $this->get_base_instalada_by_id($idBaseInstalada);
                                echo json_encode(array('status' => 1, 'data' => $baseInstalada, 'mensagem' =>'Base instalada editada com sucesso!'));
                            } else {
                                $baseInstalada = $this->get_base_instalada_by_id($idBaseInstalada);
                                echo json_encode(array('status' => 1, 'data' => $baseInstalada, 'mensagem' =>'Base instalada editada com sucesso! No entanto, não foi possível editar o campo cor do veículo. Tente novamente.'));
                            }
                        } else {
                            $baseInstalada = $this->get_base_instalada_by_id($idBaseInstalada);
                            echo json_encode(array('status' => 1, 'data' => $baseInstalada, 'mensagem' =>'Base instalada editada com sucesso! No entando o campo cor não foi salvo, pois não há veículo selecionado.'));
                        }
                        
                    } else {
                        $baseInstalada = $this->get_base_instalada_by_id($idBaseInstalada);
                        echo json_encode(array('status' => 1, 'data' => $baseInstalada, 'mensagem' =>'Base instalada editada com sucesso!'));
                    }
                    
                } else {
                    $erro = $response->data->error ? $response->data->error->message : 'Erro ao editar a base instalada!';
                    echo json_encode(array('status' => 0, 'erro' => $erro));
                }
            } else {
                echo json_encode(array('status' => 0, 'erro' => "O ID da base instalada não foi informado!"));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 0, 'erro' => "Erro ao editar a base instalada! " . $th->getMessage()));
        }
    }

    /**
     * Retorna corpo da requisição que será utilizado no cadastro e edição de bases instaladas
     * @param Array $dados
     * @return Array
     */
    private function getBodyBaseInstalada($dados){
        $body = array();

        // Atributos
        if(isset($dados['base_instalada_tz_modelo_ativacao']) && $dados['base_instalada_tz_modelo_ativacao'] != "")
            $body['tz_modelo_ativacao'] = $dados['base_instalada_tz_modelo_ativacao'];
        
        if(isset($dados['base_instalada_tz_chassi']) && $dados['base_instalada_tz_chassi'] != "")
            $body['tz_chassi'] = $dados['base_instalada_tz_chassi'];
        
        if(isset($dados['base_instalada_tz_numero_serie']) && $dados['base_instalada_tz_numero_serie'] != "")
            $body['tz_numero_serie'] = $dados['base_instalada_tz_numero_serie'];
        
        if(isset($dados['base_instalada_tz_numero_serie']) && $dados['base_instalada_tz_numero_serie'] != "")
            $body['tz_numero_serie'] = $dados['base_instalada_tz_numero_serie'];

        if(isset($dados['base_instalada_tz_tipo_produto']) && $dados['base_instalada_tz_tipo_produto'] != "")
            $body['tz_tipo_produto'] = $dados['base_instalada_tz_tipo_produto'];

        if(isset($dados['base_instalada_tz_data_desinstalacao']) && $dados['base_instalada_tz_data_desinstalacao'] != "")
            $body['tz_data_desinstalacao'] = $dados['base_instalada_tz_data_desinstalacao'];

        if(isset($dados['base_instalada_tz_data_instalacao']) && $dados['base_instalada_tz_data_instalacao'] != "")
            $body['tz_data_instalacao'] = $dados['base_instalada_tz_data_instalacao'];
        
        if(isset($dados['base_instalada_tz_local_rastreador']) && $dados['base_instalada_tz_local_rastreador'] != "")
            $body['tz_local_rastreador'] = $dados['base_instalada_tz_local_rastreador'];

        if(isset($dados['base_instalada_tz_simcard1']) && $dados['base_instalada_tz_simcard1'] != "")
            $body['tz_simcard1'] = $dados['base_instalada_tz_simcard1'];

        if(isset($dados['base_instalada_tz_linha1']) && $dados['base_instalada_tz_linha1'] != "")
            $body['tz_linha1'] = $dados['base_instalada_tz_linha1'];

        if(isset($dados['base_instalada_tz_operadora1']) && $dados['base_instalada_tz_operadora1'] != "")
            $body['tz_operadora1'] = $dados['base_instalada_tz_operadora1'];

        if(isset($dados['base_instalada_tz_simcard2']) && $dados['base_instalada_tz_simcard2'] != "")
            $body['tz_simcard2'] = $dados['base_instalada_tz_simcard2'];

        if(isset($dados['base_instalada_tz_linha2']) && $dados['base_instalada_tz_linha2'] != "")
            $body['tz_linha2'] = $dados['base_instalada_tz_linha2'];

        if(isset($dados['base_instalada_tz_operadora2']) && $dados['base_instalada_tz_operadora2'] != "")
            $body['tz_operadora2'] = $dados['base_instalada_tz_operadora2'];

        if(isset($dados['base_instalada_tz_versao_firmware']) && $dados['base_instalada_tz_versao_firmware'] != "")
            $body['tz_versao_firmware'] = $dados['base_instalada_tz_versao_firmware'];

        if(isset($dados['base_instalada_tz_observacoes']) && $dados['base_instalada_tz_observacoes'] != "")
            $body['tz_observacoes'] = $dados['base_instalada_tz_observacoes'];
        
        if(isset($dados['base_instalada_tz_ignicao']) && $dados['base_instalada_tz_ignicao'] != "")
            $body['tz_ignicao'] = $dados['base_instalada_tz_ignicao'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_bloqueio_solenoide']) && $dados['base_instalada_tz_bloqueio_solenoide'] != "")
            $body['tz_bloqueio_solenoide'] = $dados['base_instalada_tz_bloqueio_solenoide'] == 'true' ? true : false;
        
        if(isset($dados['base_instalada_tz_boto_panico']) && $dados['base_instalada_tz_boto_panico'] != "")
            $body['tz_boto_panico'] = $dados['base_instalada_tz_boto_panico'] == 'true' ? true : false;
        
        if(isset($dados['base_instalada_tz_bloqueio_eletronico']) && $dados['base_instalada_tz_bloqueio_eletronico'] != "")
            $body['tz_bloqueio_eletronico'] = $dados['base_instalada_tz_bloqueio_eletronico'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_painel']) && $dados['base_instalada_tz_painel'] != "")
            $body['tz_painel'] = $dados['base_instalada_tz_painel'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_trava_bau_traseira']) && $dados['base_instalada_tz_trava_bau_traseira'] != "")
            $body['tz_trava_bau_traseira'] = $dados['base_instalada_tz_trava_bau_traseira'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_portas_cabine']) && $dados['base_instalada_tz_portas_cabine'] != "")
            $body['tz_portas_cabine'] = $dados['base_instalada_tz_portas_cabine'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_trava_bau_lateral']) && $dados['base_instalada_tz_trava_bau_lateral'] != "")
            $body['tz_trava_bau_lateral'] = $dados['base_instalada_tz_trava_bau_lateral'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_bau_traseiro']) && $dados['base_instalada_tz_bau_traseiro'] != "")
            $body['tz_bau_traseiro'] = $dados['base_instalada_tz_bau_traseiro'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_trava_bau_intermediria']) && $dados['base_instalada_tz_trava_bau_intermediria'] != "")
            $body['tz_trava_bau_intermediria'] = $dados['base_instalada_tz_trava_bau_intermediria'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_bau_lateral']) && $dados['base_instalada_tz_bau_lateral'] != "")
            $body['tz_bau_lateral'] = $dados['base_instalada_tz_bau_lateral'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_trava_quinta_roda']) && $dados['base_instalada_tz_trava_quinta_roda'] != "")
            $body['tz_trava_quinta_roda'] = $dados['base_instalada_tz_trava_quinta_roda'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_bau_intermediario']) && $dados['base_instalada_tz_bau_intermediario'] != "")
            $body['tz_bau_intermediario'] = $dados['base_instalada_tz_bau_intermediario'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_teclado']) && $dados['base_instalada_tz_teclado'] != "")
            $body['tz_teclado'] = $dados['base_instalada_tz_teclado'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_engate_aspiral']) && $dados['base_instalada_tz_engate_aspiral'] != "")
            $body['tz_engate_aspiral'] = $dados['base_instalada_tz_engate_aspiral'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_teclado_multimidia']) && $dados['base_instalada_tz_teclado_multimidia'] != "")
            $body['tz_teclado_multimidia'] = $dados['base_instalada_tz_teclado_multimidia'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_engate_eletronico']) && $dados['base_instalada_tz_engate_eletronico'] != "")
            $body['tz_engate_eletronico'] = $dados['base_instalada_tz_engate_eletronico'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_bateria_backup']) && $dados['base_instalada_tz_bateria_backup'] != "")
            $body['tz_bateria_backup'] = $dados['base_instalada_tz_bateria_backup'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_temperatura']) && $dados['base_instalada_tz_temperatura'] != "")
            $body['tz_temperatura'] = $dados['base_instalada_tz_temperatura'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_telemetria']) && $dados['base_instalada_tz_telemetria'] != "")
            $body['tz_telemetria'] = $dados['base_instalada_tz_telemetria'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_sirene']) && $dados['base_instalada_tz_sirene'] != "")
            $body['tz_sirene'] = $dados['base_instalada_tz_sirene'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_painel_read_switch']) && $dados['base_instalada_tz_painel_read_switch'] != "")
            $body['tz_painel_read_switch'] = $dados['base_instalada_tz_painel_read_switch'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_setas_pulsantes']) && $dados['base_instalada_tz_setas_pulsantes'] != "")
            $body['tz_setas_pulsantes'] = $dados['base_instalada_tz_setas_pulsantes'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_setas_continuas']) && $dados['base_instalada_tz_setas_continuas'] != "")
        $body['tz_setas_continuas'] = $dados['base_instalada_tz_setas_continuas'] == 'true' ? true : false;

        if(isset($dados['base_instalada_tz_painel_micro_switch']) && $dados['base_instalada_tz_painel_micro_switch'] != "")
            $body['tz_painel_micro_switch'] = $dados['base_instalada_tz_painel_micro_switch'] == 'true' ? true : false;
                    
        $body['tz_tipo_trava_bau']         = (isset($dados['base_instalada_tz_tipo_trava_bau'])         && $dados['base_instalada_tz_tipo_trava_bau'] != "")         ? $dados['base_instalada_tz_tipo_trava_bau']         : null;

        if(isset($dados['base_instalada_tz_imobilizador_bt5']) && $dados['base_instalada_tz_imobilizador_bt5'] != "")
            $body['tz_imobilizador_bt5'] = $dados['base_instalada_tz_imobilizador_bt5'] == 'true' ? true : false;
            
        $body['tz_sensor_configuravel1_1'] = (isset($dados['base_instalada_tz_sensor_configuravel1_1']) && $dados['base_instalada_tz_sensor_configuravel1_1'] != "") ? $dados['base_instalada_tz_sensor_configuravel1_1'] : null;
        $body['tz_sensor_configuravel2_1'] = (isset($dados['base_instalada_tz_sensor_configuravel2_1']) && $dados['base_instalada_tz_sensor_configuravel2_1'] != "") ? $dados['base_instalada_tz_sensor_configuravel2_1'] : null;
        $body['tz_sensor_configuravel3_1'] = (isset($dados['base_instalada_tz_sensor_configuravel3_1']) && $dados['base_instalada_tz_sensor_configuravel3_1'] != "") ? $dados['base_instalada_tz_sensor_configuravel3_1'] : null;
        $body['tz_sensor_configuravel4_1'] = (isset($dados['base_instalada_tz_sensor_configuravel4_1']) && $dados['base_instalada_tz_sensor_configuravel4_1'] != "") ? $dados['base_instalada_tz_sensor_configuravel4_1'] : null;
    
        // Relacionamentos
        if(isset($dados['base_instalada_tz_cliente_anterior_pj']) && $dados['base_instalada_tz_cliente_anterior_pj'] != "")
            $body['tz_cliente_anterior_account@odata.bind'] = "/accounts(".$dados['base_instalada_tz_cliente_anterior_pj'].")";

        if(isset($dados['base_instalada_tz_cliente_anterior_pf']) && $dados['base_instalada_tz_cliente_anterior_pf'] != "")
            $body['tz_cliente_anterior_contact@odata.bind'] = "/contacts(".$dados['base_instalada_tz_cliente_anterior_pf'].")";

        if(isset($dados['base_instalada_tz_cliente_pjid']) && $dados['base_instalada_tz_cliente_pjid'] != "")
            $body['tz_cliente_pjid@odata.bind'] = "/accounts(".$dados['base_instalada_tz_cliente_pjid'].")";

        if(isset($dados['base_instalada_tz_cliente_pfid']) && $dados['base_instalada_tz_cliente_pfid'] != "")
            $body['tz_cliente_pfid@odata.bind'] = "/contacts(".$dados['base_instalada_tz_cliente_pfid'].")";

        if(isset($dados['base_instalada_tz_cliente_pj_matrizid']) && $dados['base_instalada_tz_cliente_pj_matrizid'] != "")
            $body['tz_cliente_pj_matrizid@odata.bind'] = "/accounts(".$dados['base_instalada_tz_cliente_pj_matrizid'].")";

        if(isset($dados['base_instalada_tz_cliente_pf_matrizid']) && $dados['base_instalada_tz_cliente_pf_matrizid'] != "")
            $body['tz_cliente_pf_matrizid@odata.bind'] = "/contacts(".$dados['base_instalada_tz_cliente_pf_matrizid'].")";

        if(isset($dados['base_instalada_tz_cliente_pj_instaladoid']) && $dados['base_instalada_tz_cliente_pj_instaladoid'] != "")
            $body['tz_cliente_pj_instaladoid@odata.bind'] = "/accounts(".$dados['base_instalada_tz_cliente_pj_instaladoid'].")";

        if(isset($dados['base_instalada_tz_cliente_pf_instaladoid']) && $dados['base_instalada_tz_cliente_pf_instaladoid'] != "")
            $body['tz_cliente_pf_instaladoid@odata.bind'] = "/accounts(".$dados['base_instalada_tz_cliente_pf_instaladoid'].")";

        if(isset($dados['base_instalada_tz_veiculoid']) && $dados['base_instalada_tz_veiculoid'] != "")
            $body['tz_veiculoid@odata.bind'] = "/tz_veiculos(".$dados['base_instalada_tz_veiculoid'].")";

        if(isset($dados['base_instalada_tz_item_contratoid']) && $dados['base_instalada_tz_item_contratoid'] != "")
            $body['tz_item_contratoid@odata.bind'] = "/tz_item_contrato_vendas(".$dados['base_instalada_tz_item_contratoid'].")";

        if(isset($dados['base_instalada_tz_marcaid']) && $dados['base_instalada_tz_marcaid'] != "")
            $body['tz_marcaid@odata.bind'] = "/tz_marcas(".$dados['base_instalada_tz_marcaid'].")";

        if(isset($dados['base_instalada_tz_produtoid']) && $dados['base_instalada_tz_produtoid'] != "")
            $body['tz_produtoid@odata.bind'] = "/products(".$dados['base_instalada_tz_produtoid'].")";

        if(isset($dados['base_instalada_tz_plataformaid']) && $dados['base_instalada_tz_plataformaid'] != "")
            $body['tz_plataformaid@odata.bind'] = "/tz_plataformas(".$dados['base_instalada_tz_plataformaid'].")";

        if(isset($dados['base_instalada_tz_tecnologiaid']) && $dados['base_instalada_tz_tecnologiaid'] != "")
            $body['tz_tecnologiaid@odata.bind'] = "/tz_tecnologias(".$dados['base_instalada_tz_tecnologiaid'].")";

        if(isset($dados['base_instalada_tz_grupo_emails_clienteid']) && $dados['base_instalada_tz_grupo_emails_clienteid'] != "")
            $body['tz_grupo_emails_clienteid@odata.bind'] = "/tz_grupo_emails_clientes(".$dados['base_instalada_tz_grupo_emails_clienteid'].")";


        return $body;
    }

    public function ajax_remover_atividade_servico($idAtividade){
        try {
            if(isset($idAtividade)){
                $response = $this->sac->delete("serviceappointments",$idAtividade);
                
                if($response->code == 204){
                    echo json_encode(array('status' => true, 'msg' => 'Atividade de serviço removida com sucesso!'));
                }else{
                    echo json_encode(array('status' => true, 'erro' => 'Erro ao remover atividade de serviço.'));
                }
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => true, 'erro' => 'Erro ao remover atividade de serviço! '.$th->getMessage()));
        }
    }

    public function ajax_alterar_status_atividade_de_servico(){
        try {
            $dados = $this->input->post();
            
            if(!isset($dados['idAtividadeServico'])) echo json_encode(array('status' => 0, 'erro' => 'O ID da atividade de serviço não foi informado!'));
            elseif(!isset($dados['statuscode'])) echo json_encode(array('status' => 0, 'erro' => 'O statuscode da atividade de serviço não foi informado!'));
            elseif(!isset($dados['statecode'])) echo json_encode(array('status' => 0, 'erro' => 'O statecode da atividade de serviço não foi informado!'));
            else{
                $response = $this->sac->patch('serviceappointments', $dados['idAtividadeServico'], array(
                    'statuscode' => $dados['statuscode'],
                    'statecode' => $dados['statecode']
                ));
                
                if($response->code == 200){
                    echo json_encode(array('status' => 1, 'data' => $response->data));
                }else{
                    $erro = isset($response->data->error) ? $response->data->error->message : 'Erro ao atualizar status da atividade de serviço!';
                    echo json_encode(array('status' => 0, 'erro' => $erro));

                }
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 0, 'erro' => $th->getMessage()));
        }
    }

    /**
     * Retorna base instalada por id
     * @param String $id
     * @return Array
     */
    private function get_base_instalada_by_id($id) {
        $baseInstalada = $this->sac->buscar('tz_base_instalada_clientes(' . $id . ')', http_build_query(
            array(
                '$select' => 'tz_name,tz_data_instalacao,tz_data_desinstalacao,tz_numero_serie',
                '$expand' => 'tz_veiculoid($select=tz_placa),tz_produtoid($select=name)'
            )
        ));

        if ($baseInstalada['code'] == 200) {
            $value = $baseInstalada->data;
            return array(
                'nome'               => isset($value['tz_name'])                     ? $value['tz_name']                                                         : '-',
                'placa_veiculo'      => isset($value['tz_veiculoid']['tz_placa'])    ? $value['tz_veiculoid']['tz_placa']                                        : '-',
                'data_instalacao'    => isset($value['tz_data_instalacao'])          ? $value['tz_data_instalacao@OData.Community.Display.V1.FormattedValue']    : '-',
                'data_desinstalacao' => isset($value['tz_data_desinstalacao'])       ? $value['tz_data_desinstalacao@OData.Community.Display.V1.FormattedValue'] : '-',
                'nome_produto'       => isset($value['tz_produtoid']['name'])        ? $value['tz_produtoid']['name']                                            : '-',
                'numero_serie'       => isset($value['tz_numero_serie'])             ? $value['tz_numero_serie']                                                 : '-',
                'id'                 => isset($value['tz_base_instalada_clienteid']) ? $value['tz_base_instalada_clienteid']                                     : '-',
            );
        } else {
            return [];
        }
    }    

    /**
     * Função que retorna html dos botões dos serviços contratados
     */
    private function getButtonsAcaoServicoContratado($idServico){
        return '
            <button type="button" class="btn btn-primary btnEditServicoContratado" style="width: 75%; margin:1%;" title="Editar Serviço Contratado" onclick="getInfoServicoContratado(this, \''.$idServico.'\')"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger btnDeleteServicoContratado" style="width: 75%; margin:1%;" title="Remover Serviço Contratado" onclick="removerServicoContratado(this, \''.$idServico.'\')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        ';
    }

    /**
     * Requisição re remove serviço contratado
     */
    public function ajax_remover_servico_contratado($idServico){
        try {
            if(isset($idServico)){
                $response = $this->sac->delete('tz_produto_servico_contratados',$idServico);
                
                if($response->code == 204){
                    echo json_encode(array('status' => 1, 'msg' => "Serviço contratado removido com sucesso!"));
                }
            }else{
                echo json_encode(array('status' => 0, 'erro' => "Erro ao excluir o serviço contratado! Serviço contratado não informado."));
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 0, 'erro' => "Erro ao excluir serviço contratado! ". $th->getMessage()));
        }
    }
        
    /** 
     * listar_filas
     *
     * @return void
     */
    public function listar_filas(){
        //pega o parametro passado
        $search = $this->input->get('q');
        
        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];

        $requisicaoFilas = $this->sac->get('queues',array(
            '$select' => 'queueid,name',
            '$filter' => "(contains(name, '{$search}') and statuscode eq 1)",
            '$orderby' => 'name asc'
        ));

        //verifica se o código de retorno está correto para rodar o for
        if($requisicaoFilas->code == 200){
            $values = $requisicaoFilas->data->value;
            foreach ($values as $key => $value) {
                //tira o < e o > da string
                $nome = str_replace("<", "", $value->name);
                $nome = str_replace(">", "", $nome);

                $resposta['results'][] = array(
                    'id' => $value->queueid,
                    'text' => $nome
                );
            }
        }
        
        echo json_encode($resposta);
    }

    /** 
     * listarGruposDeEmail
     *
     * @return void
     */
    public function listarGruposDeEmail(){
        try{
            //pega o parametro passado
            $idCliente = $this->input->get('idCliente');
            
            //busca os grupos de e-mails válidos
            $requisicaoGrupos = $this->sac->get('tz_grupo_emails_clientes',array(
                '$select' => 'tz_grupo_emails_clienteid,tz_name',
                '$filter' => "(_tz_cliente_pjid_value eq {$idCliente} or _tz_cliente_pfid_value eq {$idCliente} and statuscode eq 1)",
                '$orderby' => 'tz_name asc'
            ));
    
            //verifica se o código de retorno está correto para rodar o for
            if($requisicaoGrupos->code == 200){
                $values = $requisicaoGrupos->data->value;

                foreach ($values as $key => $value) {

                    $resposta[] = array(
                        'id' => $value->tz_grupo_emails_clienteid,
                        'nome' => $value->tz_name
                    );           
 
                }
            }

            if(count($resposta) > 0){
                echo json_encode(array('status' => 200, 'data' => $resposta));
            }else if(count($resposta) === 0){
                echo json_encode(array('status' => -1, 'data' => array(), 'message' => "Nenhum grupo de e-mail encontrado."));
            }else{
                echo json_encode(array('status' => 500, 'message' => "Ocorreu um erro ao buscar os grupos, tente novamente em alguns minutos."));
            }

        }catch(Throwable $th){
            echo json_encode(array('status' => 500, 'error' => $th));
        }
    }

    /** 
     * listarGruposDeEmail
     *
     * @return void
     */
    public function buscarGrupoDeEmail(){
        try{   
            //pega o id passado no post 
            $idGrupo = $this->input->post('idGrupo');

            //faz a requisição com os expands para pegar o valor do campo
            $requisicaoGrupo = $this->sac->get('tz_grupo_emails_clientes',array(
                '$select' => '_tz_email10id_value,_tz_email1id_value,_tz_email2id_value,_tz_email3id_value,_tz_email4id_value,_tz_email5id_value
                    ,_tz_email6id_value,_tz_email7id_value,_tz_email8id_value,_tz_email9id_value,_tz_email10id_value',

                '$filter' => "tz_grupo_emails_clienteid eq {$idGrupo} ",

                '$expand' => 'tz_email1id($select=emailaddress),tz_email2id($select=emailaddress),tz_email3id($select=emailaddress),
                    tz_email4id($select=emailaddress),tz_email5id($select=emailaddress),tz_email6id($select=emailaddress),tz_email7id($select=emailaddress),
                        tz_email8id($select=emailaddress),tz_email9id($select=emailaddress),tz_email10id($select=emailaddress)'
            ));
    
            //verifica se o código de retorno está correto para rodar o for
            if($requisicaoGrupo->code == 200){
                $values = $requisicaoGrupo->data->value;

                //pega os e-mails e os ids (caso seja necessário fazer o update)
                foreach ($values as $key => $value) {

                    $resposta[] = array(
                        'email1' => isset($value->tz_email1id->emailaddress) ? $value->tz_email1id->emailaddress : '',
                        'email2' => isset($value->tz_email2id->emailaddress) ? $value->tz_email2id->emailaddress : '',
                        'email3' => isset($value->tz_email3id->emailaddress) ? $value->tz_email3id->emailaddress : '',
                        'email4' => isset($value->tz_email4id->emailaddress) ? $value->tz_email4id->emailaddress : '',
                        'email5' => isset($value->tz_email5id->emailaddress) ? $value->tz_email5id->emailaddress : '',
                        'email6' => isset($value->tz_email6id->emailaddress) ? $value->tz_email6id->emailaddress : '',
                        'email7' => isset($value->tz_email7id->emailaddress) ? $value->tz_email7id->emailaddress : '',
                        'email8' => isset($value->tz_email8id->emailaddress) ? $value->tz_email8id->emailaddress : '',
                        'email9' => isset($value->tz_email9id->emailaddress) ? $value->tz_email9id->emailaddress : '',
                        'email10' => isset($value->tz_email10id->emailaddress) ? $value->tz_email10id->emailaddress : '',
                        'idEmail1' => $value->_tz_email1id_value,
                        'idEmail2' => $value->_tz_email2id_value,
                        'idEmail3' => $value->_tz_email3id_value,
                        'idEmail4' => $value->_tz_email4id_value,
                        'idEmail5' => $value->_tz_email5id_value,
                        'idEmail6' => $value->_tz_email6id_value,
                        'idEmail7' => $value->_tz_email7id_value,
                        'idEmail8' => $value->_tz_email8id_value,
                        'idEmail9' => $value->_tz_email9id_value,
                        'idEmail10' => $value->_tz_email10id_value,
                        'idEmails' =>  $idGrupo
                    );           
 
                }
            }
            
            if(count($resposta) > 0){
                echo json_encode(array('status' => 200, 'data' => $resposta));
            }else if(count($resposta) === 0){
                echo json_encode(array('status' => -1, 'data' => array(), 'message' => "Nenhum e-mail encontrado."));
            }else{
                echo json_encode(array('status' => 500, 'message' => "Ocorreu um erro ao buscar os e-mails do grupo, tente novamente em alguns minutos."));
            }

        }catch(Throwable $th){
            echo json_encode(array('status' => 500, 'error' => $th));
        }
    }

    /** 
     * atualizarGrupoDeEmail
     *
     * @return void
     */
    private function atualizarGrupoDeEmail($dados,$idGrupoEmail){
        
        //verifica se foram validados os campos para atualizar/inserir
        if($dados !== null){
            //roda o for com a quantidade de campos passadas
            for($i = 0; $i < count($dados); $i++){

                $dadosEmail = array(
                    'emailaddress' => $dados[$i]['email']
                ); 
                
                //verifica se o e-mail já foi instanciado e atualiza
                if(isset($dados[$i]['id']) && $dados[$i]['id'] !== ""){
                    $emailAtualizado = $this->sac->patch('tz_demais_emailses', $dados[$i]['id'], $dadosEmail);

                    if($emailAtualizado->code !== 200){
                        return "O cliente foi alterado com sucesso, porém ocorreu um erro ao tentar 
                            atualizar os grupos de e-mail, tente novamente.";
                    }
                }else{
                    //se cair nesse else, é necessário inserir o e-mail no banco
                    $dadosEmail['tz_name'] =  $dados[$i]['email'];
                    $novoEmail = $this->sac->post('tz_demais_emailses', $dadosEmail);
                    
                    if($novoEmail->code === 201){
                        //se for inserido corretamente, atualizar a tabela de grupos e inserir esse e-mail na respectiva coluna
                        $novoEmail = array(
                            $dados[$i]['indiceEmail'] => "/tz_demais_emailses(" . $novoEmail->data->tz_demais_emailsid . ")"
                        );

                        //atualiza a tabela de grupos
                        $insereEmailNoGrupo = $this->sac->patch('tz_grupo_emails_clientes',$idGrupoEmail,$novoEmail);

                        if($insereEmailNoGrupo->code !== 200){
                            return "O cliente foi alterado com sucesso, porém ocorreu um erro ao tentar 
                                atualizar os grupos de e-mail, tente novamente.";
                        }

                    }
                }
            }
            return "Cliente atualizado com sucesso!";
        }else{
            return "O cliente foi alterado com sucesso, porém ocorreu um erro ao tentar 
                                atualizar os grupos de e-mail, tente novamente.";
        }
    }

    /*
     * buscarIdContratoAtividadeServico
     *
     * @return json
     */
    public function buscarIdContratoAtividadeServico(){
        $valorBusca = strval($_GET['valor']);
        $sqlBusca = "tz_id_agendamento eq '{$valorBusca}'";
        
        if($_GET['tipo'] !== "codigo"){
            $idSerial = $this->buscarIdSerialAtividadeServico($valorBusca);

            if($idSerial !== null){
                $sqlBusca = "_tz_baseinstaladaantena_value eq {$idSerial}"; 
            }else{
                return json_encode(array('code' => -1, "data" => null));  
            }
        }

        $entity = "serviceappointments";

        $api_request_parameters = array(
            '$select'=> "activityid,_tz_cliente_pfid_value,_tz_cliente_pjid_value",
            '$orderby'=> "createdon desc",
            '$filter'=> $sqlBusca,
            '$top'=> 1
        );
        
        $atividadesServico = $this->sac->get($entity, $api_request_parameters);
            
        if($atividadesServico->code == 200){
            if(isset($atividadesServico->data->value[0])){
                $retornoAtividade = $atividadesServico->data->value[0];

                $idAtividade = $retornoAtividade->activityid;

                if(isset($retornoAtividade->_tz_cliente_pfid_value)){
                    $clientEntity = "contacts";
                    $id_cliente = $retornoAtividade->_tz_cliente_pfid_value;
                }else{
                    $clientEntity = "accounts";
                    $id_cliente = $retornoAtividade->_tz_cliente_pjid_value;
                }
                $this->ajax_buscar_atividades_servico($clientEntity,$id_cliente,$idAtividade);
            }
        }
        return json_encode(array('code' => -1, "data" => null));  
    }

    /** 
     * buscarIdSerialAtividadeServico
     *
     * @return json
     */
    private function buscarIdSerialAtividadeServico($serial){
        $entity = "tz_base_instalada_clientes";
        //verifica o serial por nome e número de série
        $api_request_parameters = array(
            '$select'=> "tz_base_instalada_clienteid",
            '$filter'=> "(tz_name eq '{$serial}' or tz_numero_serie eq '{$serial}')",
            '$top'=> 1
        );
        //faz a requisição
        $baseInstaladaCliente = $this->sac->get($entity, $api_request_parameters);
        
        if($baseInstaladaCliente->code == 200){
            if(isset($baseInstaladaCliente->data->value[0])){
                //retorna o id, caso tenha um retorno tudo ok
                return $baseInstaladaCliente->data->value[0]->tz_base_instalada_clienteid;
            }
        }
        return null;  
    }

    ########## INÍCIO ORDEM DE SERVIÇO/ITENS ORDEM DE SERVIÇO ##########
    /** 
     * buscar ordem de serviço
     *
     * @return void
     */
    public function buscarOS($os = false){
        if(!$os){
            $numeroOS = intval($_GET['numeroOS']);
        } else {
            $numeroOS = $os;
        }

        if(isset($numeroOS)){
            $parametros = array(
                '$select'   => 'tz_ordem_servicoid,statuscode,tz_tipo_servico,tz_observacoes,createdon,_modifiedby_value,
                    modifiedon,tz_tipo_servico,tz_valor_total,tz_tipo_pagamento,tz_condicao_pagamentoid,statecode',
                '$expand'   => 'tz_atividade_servicoid($select=statecode)',
                '$filter'   => "tz_numero_os eq '" . $numeroOS ."'"
            );
            
            $ordensDeServico = $this->sac->buscar('tz_ordem_servicos', http_build_query($parametros));
            $resposta = array();
            if($ordensDeServico['code'] === 200){
                $value = $ordensDeServico['value'][0];

                $resposta[] = array(
                    'tz_ordem_servicoid'            => $value['tz_ordem_servicoid'],
                    'tz_numero_os'                  => $numeroOS,
                    'statecode'                     => $value['statecode'],
                    'statecodeAtividadeDeServico'   => $value['tz_atividade_servicoid']['statecode'],
                    'statuscodevalue'               => $value['statuscode'],
                    'statuscode'                    => $value['statuscode@OData.Community.Display.V1.FormattedValue'],
                    'tz_tipo_servico'               => $value['tz_tipo_servico'],
                    'createdon'                     => $value['createdon@OData.Community.Display.V1.FormattedValue'],
                    'modifiedon'                    => $value['modifiedon@OData.Community.Display.V1.FormattedValue'],
                    'modifiedby'                    => $value['_modifiedby_value@OData.Community.Display.V1.FormattedValue'],
                    'tz_tipo_servico'               => $value['tz_tipo_servico@OData.Community.Display.V1.FormattedValue'],
                    'tz_valor_total'                => $value['tz_valor_total'],
                    'tz_tipo_pagamento'             => $value['tz_tipo_pagamento'],
                    'tz_condicao_pagamentoid'       => $value['tz_condicao_pagamentoid'],
                    'tz_observacoes'                => $value['tz_observacoes']
                );

                if($os){
                    return $resposta[0];
                }

                echo json_encode(array(
                    'status'    => 200,
                    'data'      => $resposta
                ));
            }else{
                echo json_encode(array(
                    'status'    => $ordensDeServico['code'],
                    'data'      => null
                ));
            }
           
        }else{
            echo json_encode(array(
                'status'    => 500,
                'message'   => "O número da OS é inválido."
            ));
        }
    }

    /** 
     * buscar os itens da ordem de serviço
     *
     * @return void
     */
    public function buscarItensOS($os = false){
        if(!$os){
            $idOS       =  $_GET['idOS'];
            $idItemOS   =  $_GET['idItemOS'];
        }else{
            $idOS       =  $os;
            $idItemOS   =  null;
        }

        $valorBusca = $idOS;
        $atributoBusca = "tz_ordem_servicoid";

        $atributosBuscaRequisicao = "
        <attribute name='tz_item_ordem_servicoid' />
        <attribute name='tz_name' />
        <attribute name='tz_itemid' />
        <attribute name='tz_quantidade' />
        <attribute name='tz_valor_total' />
        <attribute name='tz_status_aprovacao' />
        <attribute name='tz_percentual_desconto' />
        <attribute name='tz_valor_desconto' />
        <attribute name='tz_motivo_desconto' />
        <attribute name='tz_aprovador_desconto' />
        <attribute name='modifiedby' />";

        if(!isset($idOS)){ 
            $valorBusca = $idItemOS; 
            $atributoBusca = "tz_item_ordem_servicoid";

            $atributosBuscaRequisicao .= "<attribute name='tz_valor_unitario' />";
        }

        $xml = "<fetch mapping='logical'>
        <entity name='tz_item_ordem_servico'>"
            . $atributosBuscaRequisicao .
            "<filter>
                <condition attribute='$atributoBusca' operator='eq' value='$valorBusca' />
            </filter>
            </entity>
        </fetch>";

        $requisicaoItensOS = $this->sac->buscar('tz_item_ordem_servicos', http_build_query(['fetchXml' => $xml]));
        
        if($requisicaoItensOS['code'] === 200){
            $values = $requisicaoItensOS['value'];

            if(!isset($idOS)){
                $value = $values[0];
                $data = array(
                    'tz_item_ordem_servicoid'   => $value['tz_item_ordem_servicoid'],
                    'tz_name'                   => $value['tz_name'],
                    'tz_itemid'                 => $value['_tz_itemid_value'],
                    'tz_itemname'               => $value['_tz_itemid_value@OData.Community.Display.V1.FormattedValue'],
                    'tz_quantidade'             => $value['tz_quantidade'],
                    'tz_valor_total'            => $value['tz_valor_total'],
                    'tz_valor_unitario'         => $value['tz_valor_unitario'],
                    'tz_percentual_desconto'    => $value['tz_percentual_desconto'],
                    'tz_valor_desconto'         => $value['tz_valor_desconto'],
                    'tz_motivo_desconto'        => $value['tz_motivo_desconto'],
                    'tz_status_aprovacao'       => $value['tz_status_aprovacao'],
                    'tz_aprovador_desconto'     => $value['_tz_aprovador_desconto_value@OData.Community.Display.V1.FormattedValue'] ? $value['_tz_aprovador_desconto_value@OData.Community.Display.V1.FormattedValue'] : null,
                    'tz_aprovador_desconto_id'  => $value['_tz_aprovador_desconto_value'] ? $value['_tz_aprovador_desconto_value'] : null,
                    'modifiedby'                => $value['_modifiedby_value@OData.Community.Display.V1.FormattedValue'],
                );
            }else{
                
                foreach($values as $value){
                    $data[] = array(
                        'tz_item_ordem_servicoid'   => $value['tz_item_ordem_servicoid'],
                        'tz_name'                   => $value['tz_name'],
                        'tz_itemid'                 => $value['_tz_itemid_value@OData.Community.Display.V1.FormattedValue'],
                        'tz_itemid_value'           => $value['_tz_itemid_value'],
                        'tz_quantidade'             => $value['tz_quantidade'],
                        'tz_valor_total'            => $value['tz_valor_total'],
                        'tz_status_aprovacao'       => $value['tz_status_aprovacao@OData.Community.Display.V1.FormattedValue'],
                        'acoes'                     => $this->pegarBotoesItensOS($idOS,$value['tz_item_ordem_servicoid'])
                    );
                }
            }

            if ($data == null) $data = array();

            if(!$os){
                echo json_encode(array(
                    'code' => 200,
                    'data' => $data)
                );
            } else {
                return $data;
            }
        }else{
            echo json_encode(array(
                'code'      => $requisicaoItensOS['code'],
                'data'      => null,
                'message'   => "Ocorreu um erro ao buscar as informações de algum item da OS, tente novamente em alguns minutos.",
                'error'     => $requisicaoItensOS['error']
                )
            );
        }
    }

    public function buscarUsuarios(){
        $search = $this->input->get('q');
        
        $resposta = [
            'results'       => [],
            'pagination'    => [
                'more'      => false,
            ]
        ];

        $xml = "<fetch version='1.0' output-format='xml-platform' mapping='logical' distinct='false'>
            <entity name='systemuser'>
                <attribute name='systemuserid' />
                <attribute name='fullname' />
                <filter>
                    <condition attribute='fullname' operator='like' value='%{valorBusca}%' />
                </filter>
            </entity>
        </fetch>";

        $xml = str_replace('{valorBusca}', $search, $xml);
        $requisicaoUsuarios = $this->sac->buscar('systemusers', http_build_query(['fetchXml' => $xml]));

        if($requisicaoUsuarios['code'] === 200){
            $values = $requisicaoUsuarios['value'];
            foreach ($values as $value) {
                $resposta['results'][] = array(
                    'id'    => $value['systemuserid'],
                    'text'  => $value['fullname']
                );
            }
        }
        echo json_encode($resposta);
    }

    /**
     * listarProdutos
     * @return void
     */
    public function listarProdutos(){
        
        //parametro passado para buscar o produto
        $search = $this->input->get('q');
        
        $resposta = [
            'results'       => [],
            'pagination'    => [
                'more'      => false,
            ]
        ];

        //busca os itens válidos para serem inseridos no item da OS
        //deve ser chumbado o valor do campo pricelevelid, consulta igual ao do CRM
        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="true">
        <entity name="product">
            <attribute name="name" />
            <attribute name="productid" />
            <attribute name="productnumber" />
            <order attribute="name" ascending="false" />
            <link-entity name="productpricelevel" from="productid" to="productid" alias="af">
            <filter type="and">
                <condition attribute="pricelevelid" operator="eq" uitype="pricelevel" value="{89A514B9-BAFB-E211-9A6E-005056800011}" />
            </filter>
            </link-entity>
            <link-entity name="tz_item_ordem_servico" from="tz_itemid" to="productid">
                <attribute name="tz_valor_unitario" alias="valorUnitario"/>
            </link-entity>
            <filter type="and">
                <condition attribute="name" operator="like" value="%{valorBusca}%" />
                <condition attribute="statuscode" operator="eq" value="1" />
            </filter>
        </entity>
        </fetch>';

        $xml = str_replace('{valorBusca}', $search, $xml);
        $requisicaoProdutos= $this->sac->buscar('products', http_build_query(['fetchXml' => $xml]));

        if($requisicaoProdutos['code'] === 200){
            $values = $requisicaoProdutos['value'];
            foreach ($values as $value) {

                $resposta['results'][] = array(
                    'id'    => $value['productid'],
                    'text'  => $value['name'] . " ( " . $value['productnumber'] . " )",
                    'valorUnitario' => $value['valorUnitario']
                );
            }
        }
        
        echo json_encode($resposta);
    }

    /**
     * cadastrarItemOS
     * @return void
     */
    public function cadastrarItemOS(){
        try {
            $dados = $this->input->post();
            if(isset($dados)){
                $idOS = $dados["idOS"];

                //busca o nome do último item e retorna o valor apropriado para a próxima adição
                $nomeItemOS = $this->validarNomeItemOS($idOS,$dados['numeroOS']);

                $body = array(
                    'tz_itemid@odata.bind'          => '/products('.$dados["produtoItemOS"].')',
                    'tz_quantidade'                 => $dados['quantidadeItensOS'],
                    'tz_name'                       => $nomeItemOS,
                    'tz_ordem_servicoid@odata.bind' => '/tz_ordem_servicos('.$idOS.')',
                    'tz_status_aprovacao'           => $dados['statusAprovacao'],
                );

                if(isset($dados['valorDesconto'])){
                    $body['tz_percentual_desconto'] = floatval($dados['percentualDesconto']);
                    $body['tz_valor_desconto'] = floatval($dados['valorDesconto']);
                    $body['tz_motivo_desconto'] = $dados['motivoDescontoItensOS'];
                }
                
                $response = $this->sac->post('tz_item_ordem_servicos', $body);

                if($response->code === 201){
                    echo json_encode(
                        array(
                            'status'    => 201,
                            'message'   => 'Item da OS cadastrado com sucesso!'
                        ));
                }else{
                    echo json_encode(
                        array(
                            'status'    => $response->code,
                            'error'     => $response->error
                        ));
                }
            
            }

        } catch (\Throwable $th) {
            echo json_encode(
                array(
                    'status'    => 0,
                    'error'     => 'Erro ao cadastrar o item da OS! '. $th->getMessage()
                ));
        }
    }

    /**
     * excluir o item da ordem de serviço
     * @return void
     */
    public function excluirItemOS(){
        $idItemOS =  $_POST['idItemOS'];
        try {
            if(isset($idItemOS)){
                $response = $this->sac->delete('tz_item_ordem_servicos',$idItemOS);
                
                if($response->code == 204){
                    echo json_encode(
                        array(
                            'status'    => 204, 
                            'message'   => "Item da OS excluído com sucesso!"
                        ));
                }else{
                    echo json_encode(
                        array(
                            'status'    =>  $response->code, 
                            'message'   =>  "Ocorreu um erro ao excluir o item da OS, tente novamente mais tarde."
                        ));
                }
            }else{
                echo json_encode(
                    array(
                        'status'    => 500, 
                        'message'   => "Ocorreu um erro ao excluir o item da OS. Verifique os campos e tente novamente."
                    ));
            }
        } catch (\Throwable $th) {
            echo json_encode(
                array(
                    'status'    => $response->code, 
                    'message'   => "Ocorreu um erro ao excluir o item da OS, tente novamente em alguns minutos.",
                    'error'     => $th->getMessage()
                ));
        }
    }

    /**
     * editar o item da ordem de serviço
     * @return void
     */
    public function editarItemOS($edicao = false){
        try {
            if(!$edicao){
                $dados = $this->input->post();
            } else {
                $dados = $edicao;
            }
            
            if(!isset($dados['idItemOS'])){
                echo json_encode(
                    array(
                        'status' => 500,
                        'error' => 'O id do item da OS não foi informado!'
                    ));
            }else{
                $body = array(
                    'tz_itemid@odata.bind'              => '/products(' . $dados['produtoItemOS'] . ')',
                    'tz_quantidade'                     => $dados['quantidadeItensOS'],
                    'tz_status_aprovacao'               => $dados['statusAprovacao'],
                );

                if(isset($dados['aprovadorDesconto']) && ($dados['motivoDescontoItensOS'] == 6 || $dados['motivoDescontoItensOS'] == 3)){
                    $body['tz_aprovador_desconto@odata.bind'] = '/systemusers('.$dados['aprovadorDesconto'].')';
                }

                if($edicao){
                    if(isset($dados['percentualDesconto'])){
                        $body['tz_percentual_desconto'] = floatval($dados['percentualDesconto']);
                        $body['tz_motivo_desconto'] = $dados['motivoDescontoItensOS'];
                    }
                } else {
                    if(isset($dados['valorDesconto'])){
                        $body['tz_percentual_desconto'] = floatval($dados['percentualDesconto']);
                        $body['tz_valor_desconto'] = floatval($dados['valorDesconto']);
                        $body['tz_motivo_desconto'] = $dados['motivoDescontoItensOS'];
                    }
                }

                $response = $this->sac->patch('tz_item_ordem_servicos', $dados['idItemOS'],$body);
                
                if ($edicao){
                    return;
                }

                if($response->code == 200){
                    echo json_encode(
                        array(
                            'status'    => 200,
                            'data'      => $response->data,
                            'message'   => "Item da OS alterado com sucesso!"
                        )
                    );
                }else{
                    $error = isset($response->data->error) ? $response->data->error->message : 'Ocorreu um erro ao tentar atualizar a OS, tente novamente em instantes.';
                    echo json_encode(
                        array(
                            'status'    => $response->code,
                            'error'     => $error
                            )
                        );

                }
            }
        } catch (\Throwable $th) {
            echo json_encode(
                array(
                    'status'    => 500,
                    'error'     => $th->getMessage()
                )
            );
        }
    }

    /**
     * editar a ordem de serviço
     * @return void
     */

    public function editarOS(){
        try {
            $dados = $this->input->post();
            $idOS = $dados['idOS'];
            if(!isset($idOS)){
                echo json_encode(
                    array(
                        'status' => 500,
                        'error' => 'O id da ordem de serviço não foi informado!'
                    ));
            } 
            else{
                $razaoStatus = $dados['statusAprovacao'];

                //valor do status de "fechado", se existirem itens da OS aguardando aprovação não permitido fechar a OS
                if($razaoStatus === "419400000"){
                    if($this->verificarItensEmAbertoOS($idOS)){
                        echo json_encode(
                            array(
                                'status'    => 500,
                                'message'   => "Existem itens da OS que ainda não foram aprovados, não é possível fechar esta OS."
                            )
                        );
                        exit();
                    }
                }
                $response = $this->sac->patch('tz_ordem_servicos', $idOS, array(
                    'statuscode'        => $razaoStatus,
                    'tz_observacoes'    => $dados['observacoesOS'],
                ));
                
                if($response->code === 200){
                    echo json_encode(
                        array(
                            'status'                    => 200,
                            'idAtividadeDeServicoId'    => $response->data->_tz_atividade_servicoid_value,
                            'message'                   => "Ordem de serviço alterada com sucesso!"
                        )
                    );
                }else{
                    $error = isset($response->data->error) ? $response->data->error->message : 'Ocorreu um erro ao tentar atualizar a OS, tente novamente em instantes.';
                    echo json_encode(
                        array(
                            'status'    => $response->code,
                            'error'     => $error,
                            'message'   => 'Ocorreu um erro ao tentar atualizar a OS, tente novamente em instantes.'
                            )
                        );

                }
            }
        } catch (\Throwable $th) {
            echo json_encode(
                array(
                    'status'    => 500,
                    'error'     => $th->getMessage()
                )
            );
        }
    }

    /** 
     * buscar os status dos itens da ordem de serviço
     * 
     * @return boolean
     */
    private function verificarItensEmAbertoOS($idOS){
        $api_request_parameters = array(
            '$select'   => "tz_status_aprovacao",
            '$filter'   => "_tz_ordem_servicoid_value eq " . $idOS
        );

        //faz a requisição
        $requisicaoItensOS = $this->sac->get('tz_item_ordem_servicos', $api_request_parameters);

        if($requisicaoItensOS->code === 200){
            $values = $requisicaoItensOS->data->value;

            //se houver algum item com o status 1 (aguardando aprovação), retornar true
            foreach($values as $value){
                if($value->tz_status_aprovacao === 1) return true;
            }
                return false;
        }

        return false;
    }

     /**
     * Retorna o nome do último item da OS cadastrado
     * @return String
     */
    private function validarNomeItemOS($idOS,$numeroOS){
        $url = 'tz_item_ordem_servicos';

        $api_request_parameters = array(
            '$select'   => 'tz_name',
            '$filter'   => "_tz_ordem_servicoid_value eq " . $idOS,
            '$orderby'  => 'createdon desc',
            '$top'      => 1
        );

        $requisicaoItemOS = $this->sac->get($url, $api_request_parameters);

        if($requisicaoItemOS->code === 200){

            if($requisicaoItemOS->data->value[0]){
                $nomeItemOS = strval($requisicaoItemOS->data->value[0]->tz_name);
    
                //separa o nome antigo pelo ponto
                $novoNomeItemOS = explode(".",$nomeItemOS);
    
                //soma 1 ao indice antigo
                $novoIndice = intval($novoNomeItemOS[1]) + 1;
    
                //retorna a string do novo nome
                return $novoNomeItemOS[0] . "." . $novoIndice;

            }
        }
        return $numeroOS . "." . 1;
    }
    /**
     * Retorna html dos botões que serão exibidos na tabela de itens de OS
     */
    private function pegarBotoesItensOS($idOS,$idItemOS){
        $this->auth->is_allowed_block('edi_alteracaoos') ? $disabled =  'onclick="excluirItemOS(this,\''.$idItemOS.'\',\''.$idOS.'\')"' : $disabled = "disabled";
        $this->auth->is_allowed_block('edi_alteracaoos') ? $edDisabled =  'editarItemOS(this,\''.$idItemOS.'\',\''.$idOS.'\')' : $edDisabled = "disabled";

        return '<div class="btn btn-primary" onclick="'.$edDisabled.'" "title="Visualizar/Editar Item OS" style="text-align: center; margin: 1%;"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                <div class="btn btn-danger" title="Remover Item da OS" style="text-align: center; margin: 1%;"' . $disabled . '><i class="fa fa-trash" aria-hidden="true"></i></div>';
    }
    ########## FIM ORDEM DE SERVIÇO/ITENS ORDEM DE SERVIÇO ##########

    public function buscarChipsComunicacao() {
        $url = $this->input->post()['url'];

        $ci =& get_instance();

        $token = $ci->config->item('token_api_shownet_rest');

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'OData-MaxVersion: 4.0',
                'OData-Version: 4.0',
                'Prefer: odata.include-annotations="*"',
                'Authorization: Bearer '.$token
            ]
        ]);

        // remove o certificado ssl (apenas para desenvolvimento)
        if (defined('ENVIRONMENT') && ENVIRONMENT == 'development') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        if (curl_error($ch))  throw new Exception(curl_error($ch));

        $resultado = json_decode(curl_exec($ch), true);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        echo json_encode(
            array(
                "status"    => $statusCode,
                "dados"     => $resultado
            )
        );
    }

    public function buscarInfoIridium(){
        $serial = $this->input->post('serial');
        
        $resultado = get_listarDadosIridium($serial);

        echo json_encode($resultado);
        
    }
    
    public function buscarInfoNA(){
        $idNA = $this->input->post('id');

        $xml = '';

        //faz a substituição dos caracteres no XML

        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="true">
                    <entity name="serviceappointment">
                        <attribute name="tz_id_agendamento" />
                        <attribute name="createdby" />
                        <attribute name="createdon" />
                        <attribute name="modifiedby" />
                        <attribute name="modifiedon" />
                        <filter>
                            <condition attribute="activityid" operator="eq" value="{idNA}" />
                        </filter>
                    </entity>
                </fetch>';

        $xml = str_replace('{idNA}', $idNA, $xml);

        $requisicaoInfoNA = $this->sac->buscar('serviceappointments', http_build_query(['fetchXml' => $xml]));
        
        if($requisicaoInfoNA['code'] === 200){
            $dadosNA = $requisicaoInfoNA['value'];

            //preenche o array com os dados necessários
            foreach($dadosNA as $na){
                $retornoNA[] = array(
                    'code'              => $na['tz_id_agendamento'],
                    'criadoEm'          => $na['createdon@OData.Community.Display.V1.FormattedValue'],
                    'criadoPor'         => $na['_createdby_value@OData.Community.Display.V1.FormattedValue'] ? $na['_createdby_value@OData.Community.Display.V1.FormattedValue'] : "-",
                    'modificadoEm'      => $na['modifiedon@OData.Community.Display.V1.FormattedValue'],
                    'modificadoPor'     => $na['_modifiedby_value@OData.Community.Display.V1.FormattedValue'] ? $na['_modifiedby_value@OData.Community.Display.V1.FormattedValue'] : "-"
                );
            }
            
            echo json_encode(
                array(
                    'status'    => 200,
                    'data'      => $retornoNA
                )
            );
        }else{
            echo json_encode(
                array(
                    'status'    => $requisicaoInfoNA['code'],
                    'error'     => $requisicaoInfoNA['error']
            ));
        }
    }

    public function buscarinformacoesMHS() {
        $url = $this->input->post()['url'];
        
        $CI =& get_instance();

        $token = $CI->config->item('token_api_shownet_rest');

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'OData-MaxVersion: 4.0',
                'OData-Version: 4.0',
                'Prefer: odata.include-annotations="*"',
                'Authorization: Bearer '.$token,
            ]
        ]);

        // remove o certificado ssl (apenas para desenvolvimento)
        if (defined('ENVIRONMENT') && ENVIRONMENT == 'development') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        if (curl_error($ch))  throw new Exception(curl_error($ch));

        $resultado = json_decode(curl_exec($ch), true);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        echo json_encode(
            array(
                "status"    => $statusCode,
                "dados"     => $resultado
            )
        );
    }

    ########## INÍCIO ANOTAÇÃO DE FATURAMENTO (AF) ##########
    /**
     * Retorna as AFs do cliente para preencher a tabela
     */
    function buscarAfCliente(){
        $dadosPost = $this->input->post();
        // verifica se o cliente é PF ou PJ para buscar na coluna adequada
        strcmp(strtoupper($dadosPost['tipoCliente']), "PJ") === 0 ? $tipoCliente = "PJ" : $tipoCliente = "PF";

        strcmp($tipoCliente, "PJ") === 0 ? $atributoBusca = "tz_clientepessoajuridicaid" : $atributoBusca = "tz_clientepessoafisicaid";
        
        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="true">
            <entity name="tz_af">
                <attribute name="tz_tipodepagamentoid" />
                <attribute name="tz_afid" />
                <attribute name="tz_modalidadevenda" />
                <attribute name="tz_status_af" />
                <attribute name="tz_numero_af" />
                <attribute name="createdon" />
                <attribute name="modifiedon" />
                <filter>
                    <condition attribute="{atributo}" operator="eq" value="{idCliente}" />
                </filter>
            </entity>
        </fetch>';

        //faz a substituição dos caracteres no XML
        $xml = str_replace('{idCliente}', $dadosPost['idCliente'], $xml);
        $xml = str_replace('{atributo}', $atributoBusca, $xml);

        //faz a requisição
        $requisicaoAf= $this->sac->buscar('tz_afs', http_build_query(['fetchXml' => $xml]));

        if($requisicaoAf['code'] === 200){
            $dadosAf = $requisicaoAf['value'];

            //preenche o array com os dados necessários
            foreach($dadosAf as $af){
                $retornoAf[] = array(
                    'numeroAf'      => $af['tz_numero_af'] ? $af['tz_numero_af'] : "-",
                    'modalidade'    => $af['tz_modalidadevenda@OData.Community.Display.V1.FormattedValue'] ? $af['tz_modalidadevenda@OData.Community.Display.V1.FormattedValue'] : "-",
                    'tipoPagamento' => $af['_tz_tipodepagamentoid_value@OData.Community.Display.V1.FormattedValue'] ? $af['_tz_tipodepagamentoid_value@OData.Community.Display.V1.FormattedValue'] : "-",
                    'statusAf'      => $af['tz_status_af'] ? $af['tz_status_af'] : "-",
                    'criado'        => $af['createdon@OData.Community.Display.V1.FormattedValue'],
                    'modificado'    => $af['modifiedon@OData.Community.Display.V1.FormattedValue'],
                    'statusAf'      => $af['tz_status_af'] ? $af['tz_status_af'] : "-",
                    'acoes'         => $this->gerarBotaosAf($af['tz_afid'])
                );
            }
            
            echo json_encode(
                array(
                    'status'    => 200,
                    'data'      => $retornoAf
                )
            );
        }else{
            echo json_encode(
                array(
                    'status'    => $requisicaoAf['code'],
                    'error'     => $requisicaoAf['error']
            ));
        }
    }

    /**
     * Retorna html dos botões que serão exibidos na tabela de AF
     */
    private function gerarBotaosAf($idAf){
        // pega o link da API passada no arquivo config (já feita a verificação se é prod ou hml)
        $linkApiComExplode = explode("/", $this->config->item("base_url_api_crmintegration")."/crmintegration/api/");

        //faz um link específico para esta API de visualização do arquivo PDF
        $linkApiPdf = $linkApiComExplode[0]. "//" . $linkApiComExplode[2];
        
        //o "IU" até então é fixo, se trata do usuário do CRM que está fazendo a requisição, este usuário não muda no lado do Shownet
        //verifica se é prd ou hml para utilizar o id do usuário do CRM correto
        strcmp($linkApiPdf, "https://app.omnilink.com.br") === 0 ? $idUsuarioShownet = "&IU=C1F00735-B69E-EB11-B922-005056BA183F" : $idUsuarioShownet = "&IU=72564BB5-B59E-EB11-B886-000C293B0919";
        
        //link completo para atribuir ao botão
        $linkApiPdf .= "/ShowPDF/ShowPDF?ID=" . $idAf . $idUsuarioShownet;
        return '
        <div class="row" style="text-align: center">
            <a class="btn btn-primary" title="Visualizar Arquivo AF" href="' . $linkApiPdf . '" target="_blank">
                <i class="fa fa-file" aria-hidden="true"></i>
            </a>
            <a class="btn btn-primary" title="Histórico Status AF" onclick= "buscarHistoricoAf(this,\''.$idAf.'\')">
                <i class="fa fa-book" aria-hidden="true"></i>
            </a>
        </div>';
    }

    public function buscarHistoricoAf(){
        $idAF = $this->input->post('idAF');

        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="true">
                 <entity name="tz_status_af">
                   <attribute name="tz_observacoes" />
                   <attribute name="tz_data_evento" />
                   <filter>
                     <condition attribute="tz_afid" operator="eq" value="{idAf}" />
                   </filter>
                </entity>
                </fetch>';

        //faz a substituição dos caracteres no XML
        $xml = str_replace('{idAf}', $idAF, $xml);

        //faz a requisição
        $requisicaoAf= $this->sac->buscar('tz_status_afs', http_build_query(['fetchXml' => $xml]));

        if($requisicaoAf['code'] === 200){
            $dadosAf = $requisicaoAf['value'];

            //preenche o array com os dados necessários
            foreach($dadosAf as $af){
                $retornoAf[] = array(
                    'observacoes'      => $af['tz_observacoes'] ? $af['tz_observacoes'] : "-",
                    'dataEvento'        => $af['tz_data_evento'] ? date_format(date_create($af['tz_data_evento'])->setTimezone($this->timeZone), 'd/m/Y H:i:s') : "-"
                );

            }
            echo json_encode(
                array(
                    'status'    => 200,
                    'data'      => $retornoAf
                )
            );
        }else{
            echo json_encode(
                array(
                    'status'    => $requisicaoAf['code'],
                    'error'     => $requisicaoAf['error']
            ));
        }
    }

    ########## FIM ANOTAÇÃO DE FATURAMENTO (AF) ##########

    ########## INÍCIO DAS BUSCAS MODAL NA ##########
    
    public function buscarSeriaisNA(){
        $dados = $this->input->post();
        $id = $dados['Id_item_contrato_venda'];
        $id_cliente = $dados['id_cliente'];

        if ($id && $id != null){
            $xml = "<fetch version='1.0' output-format='xml-platform' mapping='logical' distinct='false'>
                <entity name='tz_base_instalada_cliente'>
                    <attribute name='tz_base_instalada_clienteid' />
                    <attribute name='tz_item_contratoid' />
                    <attribute name='tz_numero_serie' />
                    <filter>
                        <condition attribute='tz_item_contratoid' operator='eq' value='".$id."' />
                    </filter>
                </entity>
            </fetch>";
        } else {
            $xml = "<fetch version='1.0' output-format='xml-platform' mapping='logical' distinct='false'>
                <entity name='tz_base_instalada_cliente'>
                    <attribute name='tz_base_instalada_clienteid' />
                    <attribute name='tz_item_contratoid' />
                    <attribute name='tz_numero_serie' />
                    <filter>
                        <condition attribute='tz_item_contratoid' operator='not-null' />
                    </filter>
                    <filter type='or'>
                        <condition attribute='tz_cliente_pfid' operator='eq' value='".$id_cliente."' />
                        <condition attribute='tz_cliente_pjid' operator='eq' value='".$id_cliente."' />
                    </filter>
                    <link-entity name='tz_item_contrato_venda' from='tz_item_contrato_vendaid' to='tz_item_contratoid' alias='itemContrato'>
                        <filter>
                            <condition attribute='tz_status_item_contrato' operator='in'>
                                <value>1</value>
                                <value>2</value>
                            </condition>
                        </filter>
                    </link-entity>
                </entity>
            </fetch>";
        }

        $requisicaoSeriais = $this->sac->buscar('tz_base_instalada_clientes', http_build_query(['fetchXml' => $xml]));

        if($requisicaoSeriais['code'] === 200){
            $dadosSeriais = $requisicaoSeriais['value'];
            $retornoSeriais = array();
            
            foreach($dadosSeriais as $serial){
                $retornoSeriais[] = array(
                    'id'                 => $serial['tz_base_instalada_clienteid'],
                    'numeroSerie'        => $serial['tz_numero_serie'],
                    'itemContrato'       => $serial['_tz_item_contratoid_value']
                );
            }
            echo json_encode(
                array(
                    'status'    => 200,
                    'data'      => $retornoSeriais
                )
            );
        } else{
            echo json_encode(
                array(
                    'code'      => $requisicaoSeriais['code'],
                    'data'      => null,
                    'message'   => "Ocorreu um erro ao buscar os serias para o item de contrato",
                    'error'     => $requisicaoSeriais['error']
                )
            );
        }
    }

    public function buscarItensDeContrato(){
        $dados = $this->input->post();
        $id = $dados['id'];
        $documento = $dados["documento"];

        $atributoBusca = $documento === "pj" ? "tz_cliente_pjid" : "tz_cliente_pfid";
        
        $ativos = $this->buscarItensDeContratoAtivos($id, $atributoBusca);
        $aguardandoAtivacao = $this->buscarItensDeContratoAguardandoAtivação($id, $atributoBusca);

        if ( count($ativos['data']) > 0 || count($aguardandoAtivacao['data']) > 0 ){
            echo json_encode(
                array(
                    'status'    => 200,
                    'data'      => array_merge($ativos['data'], $aguardandoAtivacao['data'])
                )
            );
        } else {
            echo json_encode(
                array(
                    'code'      => $ativos['code'],
                    'data'      => null,
                    'message'   => "Ocorreu um erro ao buscar os itens de contrato",
                    'error'     => $ativos['error']
                )
            );

        }
        
    }

    public function buscarItensDeContratoAguardandoAtivação($id, $atributoBusca){
        $xml = "<fetch version='1.0' output-format='xml-platform' mapping='logical' distinct='false'>
            <entity name='tz_item_contrato_venda'>
                <order attribute='tz_name' />
                <attribute name='tz_item_contrato_vendaid' />
                <attribute name='tz_name' />
                <filter>
                    <condition attribute='tz_status_item_contrato' operator='eq' value='2' />
                    <condition attribute='statuscode' operator='eq' value='1' />
                    <condition attribute='" . $atributoBusca . "' operator='eq' value='" . $id . "' />
                </filter>
            </entity>
        </fetch>";

        $requisicaoItensDeContrato = $this->sac->buscar('tz_item_contrato_vendas', http_build_query(['fetchXml' => $xml]));

        if($requisicaoItensDeContrato['code'] === 200){

            $data[] = array_map(function ($item) {
                return [
                    'id'    => $item['tz_item_contrato_vendaid'],
                    'nome'  => isset($item['tz_name']) ? $item['tz_name'] : '-'
                ];
            }, $requisicaoItensDeContrato['value']);

            return (array( 'code' => 200, 'data' => $data[0] ));
        }else{
            return(
                array(
                    'code'      => $requisicaoItensDeContrato['code'],
                    'data'      => null,
                    'message'   => "Ocorreu um erro ao buscar os itens de contrato",
                    'error'     => $requisicaoItensDeContrato['error']
                )
            );
        }
    
    }

    public function buscarItensDeContratoAtivos($id, $atributoBusca){
        // $dados = $this->input->post();
        // $id = $dados['id'];
        // $documento = $dados["documento"];

        // $atributoBusca = $documento === "pj" ? "tz_cliente_pjid" : "tz_cliente_pfid";
        
        $xml = "<fetch version='1.0' output-format='xml-platform' mapping='logical' distinct='false'>
            <entity name='tz_item_contrato_venda'>
                <order attribute='tz_name' />
                <attribute name='tz_item_contrato_vendaid' />
                <attribute name='tz_name' />
                <filter>
                    <condition attribute='tz_status_item_contrato' operator='eq' value='1' />
                    <condition attribute='statuscode' operator='eq' value='1' />
                    <condition attribute='" . $atributoBusca . "' operator='eq' value='" . $id . "' />
                </filter>
            </entity>
        </fetch>";

        $requisicaoItensDeContrato = $this->sac->buscar('tz_item_contrato_vendas', http_build_query(['fetchXml' => $xml]));

        if($requisicaoItensDeContrato['code'] === 200){

            $data[] = array_map(function ($item) {
                return [
                    'id'    => $item['tz_item_contrato_vendaid'],
                    'nome'  => isset($item['tz_name']) ? $item['tz_name'] : '-'
                ];
            }, $requisicaoItensDeContrato['value']);

            return (array( 'code' => 200, 'data' => $data[0] ));
        }else{
            return (
                array(
                    'code'      => $requisicaoItensDeContrato['code'],
                    'data'      => null,
                    'message'   => "Ocorreu um erro ao buscar os itens de contrato",
                    'error'     => $requisicaoItensDeContrato['error']
                )
            );
        }
    }

    public function buscarPrestadoresNA(){

        $xml = "<fetch version='1.0' output-format='xml-platform' mapping='logical' distinct='false'>
            <entity name='site'>
                <attribute name='siteid' />
                <attribute name='name' />
                <link-entity name='internaladdress' from='parentid' to='siteid' link-type='outer' intersect='true'>
                    <attribute name='latitude' />
                    <attribute name='longitude' />
                    <filter>
                        <condition attribute='longitude' operator='not-null' />
                        <condition attribute='latitude' operator='not-null' />
                    </filter>
                    <order attribute='latitude' descending='true' />
                </link-entity> 
            </entity>
        </fetch>";

        $requisicaoPrestadores = $this->sac->buscar('sites', http_build_query(['fetchXml' => $xml]));
        
        if($requisicaoPrestadores['code'] === 200){

            $data[] = array_map(function ($item) {
                return [
                    'id'        => $item['siteid'],
                    'nome'      => isset($item['name']) ? $item['name'] : '-',
                    'latitude'  => isset($item['internaladdress1_x002e_latitude']) ? $item['internaladdress1_x002e_latitude'] : '-',
                    'longitude' => isset($item['internaladdress1_x002e_longitude']) ? $item['internaladdress1_x002e_longitude'] : '-'
                ];
            }, $requisicaoPrestadores['value']);

            echo json_encode(array( 'code' => 200, 'data' => $data[0] ));
        }else{
            echo json_encode(
                array(
                    'code'      => $requisicaoPrestadores['code'],
                    'data'      => null,
                    'message'   => "Ocorreu um erro ao buscar os prestadores",
                    'error'     => $requisicaoPrestadores['error']
                )
            );
        }
    }

    public function prestadoresPorLatituteLongitude(){
        $idPrestador = $this->input->post('idPrestador');
        $xml ="<fetch version='1.0' output-format='xml-platform' mapping='logical' distinct='false'>
            <entity name='internaladdress'>
            <attribute name='latitude' />
            <attribute name='longitude' />
            <filter>
              <condition attribute='latitude' operator='not-null' />
              <condition attribute='longitude' operator='not-null' />
              <condition attribute='parentid' operator='eq' value='" . $idPrestador . "' uitype='internaladdress' />
            </filter>
          </entity>
        </fetch>";

        $requisicao = $this->sac->buscar('internaladdresses', http_build_query(['fetchXml' => $xml]));
        
        if ($requisicao['code'] === 200){
            $data[] = array_map(function ($item) {
                return [
                    'latitude'      => isset($item['latitude']) ? $item['latitude'] : '-',
                    'longitude'     => isset($item['longitude']) ? $item['longitude'] : '-',
                ];
            }, $requisicao['value']);

            echo json_encode(array( 'code' => 200, 'data' => $data[0] ));
        }else{
            echo json_encode(
                array(
                    'code'      => $requisicao['code'],
                    'data'      => null,
                    'message'   => "Ocorreu um erro ao buscar os prestadores",
                    'error'     => $requisicao['error']
                )
            );
        }
    }

    public function cadastrarNA() {
        $dados = $this->input->post();
        $url = "serviceAppointment/CreateNAGestor";
        
        $resposta = $this->requisicaoApiCrm($url, $dados);

        $decod = json_decode($resposta);

        $status = $decod->status;
        $message = explode(',', $decod->message);
        if(count($message) > 1){
            $na = explode(':', $message[0]);
            $msg = explode(':', $message[1]);
            $message = array( 
                'na' =>  trim(str_replace("'", '',$na[1])),
                'msg' => trim(str_replace("'", '',$msg[1]))
            );

            $instalacoes = $this->getServicosInstalacao();
            $servico = $dados['Servico'];

            foreach ($instalacoes as $instalacao){
                if (trim($instalacao) == trim($servico)){
                    $os = $this->listarOS(trim(str_replace("'", '',$na[1])));
                    $os = $this->buscarOS($os);
                    $itensOS = $this->buscarItensOS($os['tz_ordem_servicoid']);

                    if(count($itensOS) > 0){
                        $deslocamentos = ['B4DA13A5-296E-E211-A070-005056800011', '1E730C7F-96FB-E211-9A6E-005056800011', '252D0F12-2A6E-E211-A070-005056800011'];
                        foreach ($itensOS as $item){
                            if(in_array(strtoupper($item['tz_itemid_value']), $deslocamentos)){
                                $itemDeslocamento = [];
                                $itemDeslocamento['idItemOS'] = $item['tz_item_ordem_servicoid'];
                                $itemDeslocamento['produtoItemOS'] = $item['tz_itemid_value'];
                                $itemDeslocamento['quantidadeItensOS'] = $dados['DistanciaTotal'];
                                if((float) $dados['DistanciaTotal'] <= 50){
                                    $itemDeslocamento['statusAprovacao'] = 2;
                                } else {
                                    $itemDeslocamento['statusAprovacao'] = 1;
                                }

                                $itemDeslocamento['percentualDesconto'] = $dados['distanciaConsiderada'] == 0 ? 100 : (($dados['distanciaBonificada'])/($dados['DistanciaTotal']*100));
                                $itemDeslocamento['motivoDescontoItensOS'] = 5;
                                
                                $this->editarItemOS($itemDeslocamento);
                            }
                        }
                    }
                    echo json_encode(array(
                        'status' => $status,
                        'message' => $message
                    ));
                    return;
                }else {
                    echo json_encode(array(
                        'status' => $status,
                        'message' => $message
                    ));
                    return;
                }
            }

        } else {
            echo json_encode(array(
                'status' => $status,
                'message' => $message
            ));
            return;
        }
    }

    public function getServicosInstalacao(){

        try {            
            $entity = "services";
            $api_request_parameters = array(
                '$select' => 'serviceid, name',
                '$filter'=>"startswith(name,'instalação') and isschedulable eq true"
            );
            $requisicao = $this->sac->get($entity, $api_request_parameters);
            
            if($requisicao->code == 200){
                foreach($requisicao->data->value as $servico){
                    $servicos[] = $servico->serviceid;
                }

                return ($servicos);
            }else{
                return array();
            }
        } catch (\Throwable $th) {
            return array();
        }

    }

    public function buscarServicosDoContrato() {
        $dados = $this->input->post();
        $url = "serviceAppointment/GetServices";

        echo $this->requisicaoApiCrm($url, $dados);
    }

    private function requisicaoApiCrm($url, $dados = null){
        if( !$url ) return null;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->config->item("base_url_api_crmintegration")."/crmintegration/api/" . $url ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'OData-MaxVersion: 4.0',
                'OData-Version: 4.0',
                'Prefer: odata.include-annotations="*"'
            ],
            
        ]);
        
        // remove o certificado ssl (apenas para desenvolvimento)
        if (defined('ENVIRONMENT') && ENVIRONMENT == 'development') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        
        if( $dados !== null )
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados));

        if (curl_error($ch))  throw new Exception(curl_error($ch));

        $resultado = json_decode(curl_exec($ch), true);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        return json_encode(
            array(
                "status"    => $statusCode,
                "message"   => $resultado['Message']
            )
        );
    }

    public function buscarRecursosNA($idRecurso = null){
        $filtroId = "";
        if($idRecurso == null){
           $idPrestador = $this->input->post('idPrestador');
        }

        if($idRecurso !== null){
            $filtroId = "<filter>
                            <condition attribute='siteid' operator='eq' value='" . $idRecurso . "' />
                        </filter>";
        } 

        if(isset($idPrestador) && $idPrestador !== null){
            $filtroId = "<filter>
                            <condition attribute='siteid' operator='eq' value='" . $idPrestador . "' />
                        </filter>";
        }

        $xml = "<fetch version='1.0' output-format='xml-platform' mapping='logical' distinct='false'>
            <entity name='equipment'>
                <attribute name='equipmentid' />
                <attribute name='name' />
                " . $filtroId . "
            </entity>
        </fetch>";

        $requisicaoRecursos = $this->sac->buscar('equipments', http_build_query(['fetchXml' => $xml]));
        
        if($requisicaoRecursos['code'] === 200){

            $data[] = array_map(function ($item) {
                return [
                    'id'        => $item['equipmentid'],
                    'nome'      => isset($item['name']) ? $item['name'] : '-',
                ];
            }, $requisicaoRecursos['value']);

            if($idRecurso !== null):
                return json_encode(array('code' => 200, 'data' => $data[0]));
            endif;

            echo json_encode(array( 'code' => 200, 'data' => $data[0] ));
        }else{
            echo json_encode(
                array(
                    'code'      => $requisicaoRecursos['code'],
                    'data'      => null,
                    'message'   => "Ocorreu um erro ao buscar os recursos",
                    'error'     => $requisicaoRecursos['error']
                )
            );
        }
    }

    public function buscarEstados(){

        $xml = "<fetch version='1.0' output-format='xml-platform' mapping='logical' distinct='false'>
            <entity name='tz_estado'>
                <attribute name='tz_estadoid' />
                <attribute name='tz_name' />
                <attribute name='tz_uf' />
            </entity>
        </fetch>";

        $requisicaoEstados = $this->sac->buscar('tz_estados', http_build_query(['fetchXml' => $xml]));
        
        if($requisicaoEstados['code'] === 200){

            $data[] = array_map(function ($item) {
                return [
                    'id'    => $item['tz_estadoid'],
                    'nome'  => isset($item['tz_name']) ? $item['tz_name'] : '-',
                    'uf'    => isset($item['tz_uf']) ? $item['tz_uf'] : '-',
                ];
            }, $requisicaoEstados['value']);

            echo json_encode(array( 'code' => 200, 'data' => $data[0] ));
        }else{
            echo json_encode(
                array(
                    'code'      => $requisicaoEstados['code'],
                    'data'      => null,
                    'message'   => "Ocorreu um erro ao buscar os estados",
                    'error'     => $requisicaoEstados['error']
                )
            );
        }
    }

    public function buscarCidades(){
        $idEstado = $this->input->post()['idEstado'];

        $xml = "<fetch version='1.0' output-format='xml-platform' mapping='logical' distinct='false'>
            <entity name='tz_cidade'>
                <attribute name='tz_cidadeid' />
                <attribute name='tz_name' />
                <filter>
                <condition attribute='tz_estado_cidade_id' operator='eq' value='" . $idEstado . "' />
                </filter>
            </entity>
        </fetch>";

        $requisicaoCidades = $this->sac->buscar('tz_cidades', http_build_query(['fetchXml' => $xml]));
        
        if($requisicaoCidades['code'] === 200){

            $data[] = array_map(function ($item) {
                return [
                    'id'    => $item['tz_cidadeid'],
                    'nome'  => isset($item['tz_name']) ? $item['tz_name'] : '-'
                ];
            }, $requisicaoCidades['value']);

            echo json_encode(array( 'code' => 200, 'data' => $data[0] ));
        }else{
            echo json_encode(
                array(
                    'code'      => $requisicaoCidades['code'],
                    'data'      => null,
                    'message'   => "Ocorreu um erro ao buscar as cidades deste estado",
                    'error'     => $requisicaoCidades['error']
                )
            );
        }
    }

    ########## FIM DAS BUSCAS DA NA ##########

    public function buscar_bases_instaladas(){
        $search = $this->input->get('q');
        $idCliente = $this->input->get('id');
        
        $resposta = ['results' => [], 'pagination' => ['more' => false]];

        $requisicaoBases = $this->sac->get('tz_base_instalada_clientes',array(
            '$select' => 'tz_base_instalada_clienteid,tz_name',
            '$filter' => "((contains(tz_name, '{$search}') and statuscode eq 1) and 
                (_tz_cliente_pjid_value eq {$idCliente} or _tz_cliente_pfid_value eq {$idCliente}))",
            '$orderby' => 'tz_name asc'
        ));

        if($requisicaoBases->code == 200){
            $values = $requisicaoBases->data->value;
            foreach ($values as $key => $value) {
                $nome = str_replace("<", "", $value->tz_name);
                $nome = str_replace(">", "", $nome);

                $resposta['results'][] = array(
                    'id' => $value->tz_base_instalada_clienteid,
                    'text' => $nome
                );
            }
        }
        
        echo json_encode($resposta);
    }

    public function ajax_enviar_email(){

        $mensagem = $this->input->post("mensagem");
        $assunto = $this->input->post("assunto");
        $destinatarios = $this->input->post("destinatarios");

        $this->load->model('sender');
		
        $sender = $this->sender->sendEmailAPI($assunto, $mensagem, $destinatarios);

		echo $sender;
    }

    public function get_numeroNA(){
        $activityid = $this->input->post('activityid');

        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="true">
                    <entity name="serviceappointment">
                      <attribute name="tz_id_agendamento" />
                      <filter>
                        <condition attribute="activityid" operator="eq" value="{activityid}" />
                      </filter>
                    </entity>
                </fetch>';
        
        $xml = str_replace("{activityid}", $activityid, $xml);
        $requisicao = $this->sac->buscar('serviceappointments', http_build_query(['fetchXml' => $xml]));
        
        if($requisicao['code'] === 200){
            $data = $requisicao['value'][0];
            echo json_encode(array('code' => 200, 'data' => $data));
        }else{
            echo json_encode(
                array(
                    'code'      => $requisicao['code'],
                    'data'      => null,
                    'message'   => "Ocorreu um erro ao buscar o número da NA",
                    'error'     => $requisicao['error']
                )
            );
        }
    }

    public function listarContratosPlaca(){
        $placa = $this->input->get('q');
        $buscaPorContratosAtivos = $this->input->get('buscaPorContratosAtivos');

        $filtroAtivo = ($buscaPorContratosAtivos === 'true') ? '<filter><condition attribute="tz_status_item_contrato" operator="eq" value="1" /></filter>' : "";
        
        $xml = '<fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
        <entity name="tz_item_contrato_venda">
        <attribute name="tz_name" />
          <attribute name="tz_cliente_pjid" alias="tz_cliente_pjid" />
          <attribute name="tz_cliente_pfid" />
          <attribute name="tz_veiculoid" />
          '.$filtroAtivo.'
          <link-entity name="tz_veiculo" from="tz_veiculoid" to="tz_veiculoid" alias="ad">
            <filter type="and">
              <condition attribute="tz_placa" operator="eq" value="'.$placa.'" />
            </filter>
          </link-entity>
        </entity>
      </fetch>';

        $resposta = [
            'results' => [],
            'pagination' => [
                'more' => false,
            ]
        ];
        $requisicao = $this->sac->buscar('tz_item_contrato_vendas', http_build_query(['fetchXml' => $xml]));
        
        if($requisicao['code'] === 200){
            $values = $requisicao['value'];
            foreach ($values as $key => $value) {
                $resposta['results'][] = array(
                    'id' => $value['tz_name'],
                    'text' => $value['_tz_cliente_pjid_value@OData.Community.Display.V1.FormattedValue'] ? $value['tz_name'] . " (" . $value['_tz_cliente_pjid_value@OData.Community.Display.V1.FormattedValue'] . ")" : $value['tz_name'] . " (" .$value['_tz_cliente_pfid_value@OData.Community.Display.V1.FormattedValue'] . ")",
                );
            }
        }

        echo json_encode($resposta);

    }

    public function ajax_obter_coordenadas(){
        $cep = $this->input->get("cep");

        $CI =& get_instance();

        $request = $CI->config->item('url_api_shownet_rest').'endereco/getLatLong/';

        $token = $CI->config->item('token_api_shownet_rest');

        $headers[] = 'Accept: application/json';
	    $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $request.$cep,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
            ));
    
            if (curl_error($ch))  throw new Exception(curl_error($ch));
            $resultado = json_decode(curl_exec($ch), true);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($statusCode == 200) {
                echo json_encode(array('code' => 200, 'data' => $resultado));
            }else{
                echo json_encode(array('code' => $statusCode, 'data' => null, 'msg' => 'Ocorreu um erro ao buscar as coordenadas do CEP'));
            }
    }

    public function retornaIdCliente(){
        $documento = $this->input->get('documento');
        $documentoVerificado = preg_replace('/[^0-9]/', '', $documento);

        if (strlen($documentoVerificado) == 14){
            $requisicao = $this->sac->get('accounts',array(
                '$select' => 'accountid',
                '$filter' => "zatix_cnpj eq '{$documento}'",
            ));
    
            if($requisicao->code == 200){
                $values = $requisicao->data->value[0]->accountid;
                echo json_encode(array('code' => 200, 'data' => $values));
            }else{
                echo json_encode(array('code' => $requisicao->code, 'data' => null, 'msg' => 'Ocorreu um erro ao buscar o id do cliente'));
            }
        }else if (strlen($documentoVerificado) == 11){
            
            $requisicao = $this->sac->get('contacts',array(
                '$select' => 'contactid',
                '$filter' => "zatix_cpf eq '{$documento}'",
            ));
    
            if($requisicao->code == 200){
                $values = $requisicao->data->value[0]->contactid;
                echo json_encode(array('code' => 200, 'data' => $values));
            }else{
                echo json_encode(array('code' => $requisicao->code, 'data' => null, 'msg' => 'Ocorreu um erro ao buscar o id do cliente'));
            }
        }else{
            echo json_encode(array('code' => 400, 'data' => null, 'msg' => 'Ocorreu um erro ao buscar o id do cliente'));
        }
    }

    public function resetarChip(){
        $this->load->helper('util_helper');

        $id_user_logado = $this->auth->get_login_dados('user');
		$id_user_logado = (int) $id_user_logado;

        $linha = $this->input->post('linha');
        $idOperadora = $this->input->post('idOperadora');
        
        $retorno = to_resetarChip($linha, $idOperadora, $id_user_logado);

        echo json_encode($retorno);
    }

    public function atualizarLicencaContratoCliente(){
        $this->load->helper('util_helper');

        $servicoContratadoId = $this->input->post('servicoContratadoId');
       
        $retorno = to_atualizarLicencaContratoCliente($servicoContratadoId);

        echo json_encode($retorno);
    }

    public function buscarDadosClienteToAviso($documento){
        $select = 'zatix_atendimentoriveiculo,zatix_comunicacaochip
        ,zatix_comunicacaosatelital,zatix_emissaopv,zatix_bloqueiototal,tz_desbloqueioportal';

        if (strlen($documento) == 14){
            $entity = 'contacts';

            $api_request_parameters = array(
                '$select' => $select,
                '$filter'=>"zatix_cpf eq '{$documento}'"
            );
        }else{
            $entity = 'accounts';

            $api_request_parameters = array(
                '$select' => $select,
                '$filter'=>"zatix_cnpj eq '{$documento}'"
            );
        }

        $requisicao = $this->sac->get($entity, $api_request_parameters);
        
        if ($requisicao->code == 200){
            $data = $requisicao->data->value[0];
            return $data;
        }else{
            return null;
        }

    }

    public function auditoriaCadastroOcorrencia(){
        $POSTFIELDS = array(
            'idCliente' => (int) $this->input->post('idCliente'),
            'assunto' => $this->input->post('assunto'),
        );
       
        $dados = $this->post_auditoriaCadastroOcorrencia($POSTFIELDS);
        echo json_encode($dados);
    }

    private function post_auditoriaCadastroOcorrencia($POSTFIELDS){
        return $this->to_post('suporte/tickets/cadastrarTicketSuporte', $POSTFIELDS);
    }

    public function buscarFuncoesByGrupoFp() {
        $idGrupoFp = (int) $this->input->post('id');

        $dados = $this->to_get('funcoesProgramaveis/buscarFuncoesPorIdGrupoFP?idGrupoFp='.$idGrupoFp);
        echo json_encode($dados);
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

    private function to_post($url, $POSTFIELDS){
        $CI =& get_instance();
    
        $request = $CI->config->item('url_api_shownet_rest').$url;
        
        $token = $CI->config->item('token_api_shownet_rest');
    
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';	   
        $headers[] = 'Authorization: Bearer '.$token;
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  json_encode($POSTFIELDS),
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