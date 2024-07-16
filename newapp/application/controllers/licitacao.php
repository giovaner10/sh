<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Licitacao extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('model_licitacao');
        $this->load->model('cliente');
        $this->load->model('email_model');
        $this->load->model('endereco');
        $this->load->model('telefone');
        $this->load->model('mapa_calor');

        $this->id_user = $this->auth->get_login_dados('user');
    }

    public function index() {
        $dados['titulo'] = lang('termos_adesao').' - '.lang('show_tecnologia');
        //$dados["load"] = ["ag-grid" ,"select2", "mask", "jquery-form"];
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $this->mapa_calor->registrar_acessos_url(site_url('/licitacao'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('licitacao/index_NS');
        $this->load->view('fix/footer_NS');
    }
    
    public function index_old() {
        $dados['titulo'] = lang('termos_adesao').' - '.lang('show_tecnologia');
        $this->load->view('fix/header-new', $dados);
        $this->load->view('licitacao/index_new');
        $this->load->view('fix/footer_new');
    }

    // public function termo_adesao_antigo(){
    //     $this->load->model('usuario');
    //     $this->load->model('contrato_eptc');
    //     $dados['titulo'] = "Show Tecnologia";
    //     $dados['vendedores'] = $this->usuario->all();
    //     if($this->input->get('eptc')){
    //         $dados['dados'] = $this->contrato_eptc->get_contrato($this->input->get('eptc'));
    //     }
    //     $this->load->view('fix/header', $dados);
    //     $this->load->view('licitacao/termo_adesao');
    //     $this->load->view('fix/footer');
    // }

    // public function termo_adesao(){
    //     $this->load->model('usuario');
    //     $this->load->model('contrato_eptc');
    //     $dados['titulo'] = "Show Tecnologia";
    //     $dados['vendedores'] = $this->usuario->all();
    //     if($this->input->get('eptc')){
    //         $dados['dados'] = $this->contrato_eptc->get_contrato($this->input->get('eptc'));
    //     }
    //     $this->mapa_calor->registrar_acessos_url(site_url('/licitacao/termo_adesao'));
	// 	$this->load->view('new_views/fix/header', $dados);
    //     $this->load->view('licitacao/termo_adesao');
	// 	$this->load->view('fix/footer_NS');
    // }

    public function termo_adesao_sim(){
        $this->load->model('usuario');
        $dados['titulo'] = "Show Tecnologia";
        $dados['vendedores'] = $this->usuario->all();
        $this->load->view('fix/header', $dados);
        $this->load->view('licitacao/termo_adesao_sim');
        $this->load->view('fix/footer');
    }

    function edit($empresa, $id_termo){

        if ($this->input->post()) {
            $dados = $this->input->post();

            //separa o endereço de entrega dos demais dados
            $retorno = $this->pega_endereco_entrega($dados);
            $dados = $retorno['dados'];
            $endereco_entrega = $retorno['endereco_entrega'];
            $id_entrega = $retorno['id_entrega'];

            // setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $dados['data_termo'] = traduzData(strftime('%A, %d de %B de %Y', strtotime('today')));
            $dados['id_user'] = $this->id_user;
            $dados['sim'] = false;

            if ($this->input->post('quant_chips')) {
                $dados['sim'] = true;
                $dados['valor_ativacao_chip'] = moneyForFloat($dados['valor_ativacao_chip']);
                $dados['valor_mensalidade_chip'] = moneyForFloat($dados['valor_mensalidade_chip']);
                $dados['primeiro_vencimento_mensalidade'] = data_for_unix($dados['primeiro_vencimento_mensalidade']);
                $dados['venc_ativacao'] = data_for_unix($dados['venc_ativacao']);
            } else{
                $dados['valor_final_un'] = moneyForFloat($dados['valor_final_un']);
                $dados['total'] = moneyForFloat($dados['total']);
                $dados['adicional_parcelas'] = moneyForFloat($dados['adicional_parcelas']);
                $dados['valor_inst_veic'] = moneyForFloat($dados['valor_inst_veic']);
                $dados['valor_mens_veic'] = moneyForFloat($dados['valor_mens_veic']);
            }

            $edit = $this->model_licitacao->editTermo($dados, $id_termo, $empresa, $id_entrega, $endereco_entrega);

            if ($edit)
                $this->session->set_flashdata('sucesso', 'Termo Editado com sucesso!');
            else
                $this->session->set_flashdata('erro', 'Erro ao editar termo, tente novamente mais tarde!');

            redirect('licitacao');

        }else {
            $this->load->model('usuario');
            $dados['vendedores'] = $this->usuario->all();
            $dados['titulo'] = "Show Tecnologia";
            $dados['termo'] = $this->model_licitacao->getTermoById($id_termo, $empresa);

            if ($dados['termo']->end_entrega_id != NULL) {
                $this->load->model('endereco');
                $dados['end_entrega'] = $this->endereco->get_endereco_entrega($dados['termo']->end_entrega_id);
            }

            $this->load->view('fix/header', $dados);
            if ($empresa == '2') {
                $this->load->view('licitacao/editar_termo_sim');
            }else {
                $this->load->view('licitacao/editar_termo');
            }

        }

    }

    public function get($empresa){
        $executivo = $this->id_user;
        echo $this->model_licitacao->getTermos($empresa, $executivo);
    }

    public function acompanhamento(){
        $dados['titulo'] = "Show Tecnologia";
        $dados['licitacoes'] =  $this->model_licitacao->getLicitacoes();
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
        $this->mapa_calor->registrar_acessos_url(site_url('/licitacao/acompanhamento'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('licitacao/licitacoesNew');
        $this->load->view('fix/footer_NS.php');
    }

    public function getLicitacoesJson(){
        echo json_encode($this->model_licitacao->getLicitacoes());
    }
    
    public function dash_acompanhamento(){
        echo json_encode( $this->model_licitacao->getDashLicitacoes());
    }

    public function getGraficoVeiculosJson(){
        echo json_encode( $this->model_licitacao->getGraficoVeiculos());
    }

    public function getGraficoLicitacoesJson(){
        echo json_encode( $this->model_licitacao->getGraficoLicitacoes());
    }

    public function getGraficoValoresJson(){
        echo json_encode( $this->model_licitacao->getGraficoValores());
    }



    public function addOld(){
        $dados['titulo'] = "Show Tecnologia";
        $post = $this->input->post();
        if($post){
            $post['data_licitacao'] = data_for_unix($post['data_licitacao']);
            $post['data'] = date('Y-m-d');
            if(!$post['situacao_final']){
                unset($post['situacao_final']);
            }
            if(!$post['situacao_preliminar']){
                unset($post['situacao_preliminar']);
            }
            $post["valor_unitario_ref"]=str_replace(',','.',$post["valor_unitario_ref"]);
            $post["valor_global_ref"]=str_replace(',','.',$post["valor_global_ref"]);
            $post["valor_unitario_arremate"]=str_replace(',','.',$post["valor_unitario_arremate"]);
            $post["valor_global_arremate"]=str_replace(',','.',$post["valor_global_arremate"]);
            $this->model_licitacao->addLicitacoes($post);
            //var_dump($post);die;//$data=array('orgao'=>$post['orgao']);
            redirect('licitacao/acompanhamento');

        }
        $this->load->view('fix/header4', $dados);
        $this->load->view('licitacao/licitacao_add');
        $this->load->view('fix/footer4');
    }

    public function add()
    {
        $post = $this->input->post();
        if ($post) {
            $post['data_licitacao'] = $this->data_for_unix_new($post['data_licitacao']);
            $post['data'] = date('Y-m-d');

            $campos_opcionais = [
                'situacao_final',
                'situacao_preliminar',
                'plataforma',
                'descricao_servico',
                'meses',
                'valor_unitario_ref',
                'valor_instalacao_ref',
                'valor_global_ref',
                'vencedor',
                'valor_unitario_arremate',
                'preco_instalacao',
                'valor_global_arremate',
                'observacoes',
                'qtd_veiculos'
            ];

            foreach ($campos_opcionais as $campo) {
                if (!isset($post[$campo]) || $post[$campo] === '') {
                    unset($post[$campo]);
                }
            }

            $post["valor_unitario_ref"] = isset($post["valor_unitario_ref"]) ? $this->convertToFloat($post["valor_unitario_ref"]) : 0.0;
            $post["valor_global_ref"] = isset($post["valor_global_ref"]) ? $this->convertToFloat($post["valor_global_ref"]) : 0.0;
            $post["valor_unitario_arremate"] = isset($post["valor_unitario_arremate"]) ? $this->convertToFloat($post["valor_unitario_arremate"]) : 0.0;
            $post["valor_global_arremate"] = isset($post["valor_global_arremate"]) ? $this->convertToFloat($post["valor_global_arremate"]) : 0.0;

            $campos_obrigatorios = ['data_licitacao', 'orgao', 'estado', 'esfera', 'empresa', 'tipo', 'tipo_contrato', 'ata_registro_preco'];

            foreach ($campos_obrigatorios as $campo) {
                if (!isset($post[$campo]) || $post[$campo] === '') {
                    echo json_encode("O campo {$campo} é obrigatório.");
                    return;
                }
            }

            $result = $this->model_licitacao->insertLicitacoes($post);

            if ($result) {
                echo true;
            } else {
                echo json_encode("Não foi possível completar a requisição");
            }
        }
    }


    private function convertToFloat($value) {
        if (empty($value)) {
            return 0.0;
        }
        return floatval(str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $value))));
    }
    
    private function data_for_unix_new($data) {
        if (!$data) {
            return null;
        }
        $dt = explode("-", $data);
        if (count($dt) !== 3) {
            return null;
        }
        return $dt[0] . '-' . $dt[1] . '-' . $dt[2];
    }
    

    public function edit_licitacao($id){
        $dados['titulo'] = "Show Tecnologia";
        $post = $this->input->post();
        if($post){
            $post['data_licitacao'] = data_for_unix($post['data_licitacao']);
            $post['data'] = date('Y-m-d');
            if(!$post['situacao_final']){
                unset($post['situacao_final']);
            }
            if(!$post['situacao_preliminar']){
                unset($post['situacao_preliminar']);
            }
            $post["valor_unitario_ref"]=str_replace(',','.',$post["valor_unitario_ref"]);
            $post["valor_global_ref"]=str_replace(',','.',$post["valor_global_ref"]);
            $post["valor_unitario_arremate"]=str_replace(',','.',$post["valor_unitario_arremate"]);
            $post["valor_global_arremate"]=str_replace(',','.',$post["valor_global_arremate"]);
            $this->model_licitacao->updateLicitacoes($id,$post);
            //var_dump($post);die;//$data=array('orgao'=>$post['orgao']);
            redirect('licitacao/acompanhamento');

        }
        $dados=(array)$this->model_licitacao->getLicitacao($id);
        $dados['titulo'] = "Show Tecnologia";
        $this->load->view('fix/header', $dados);
        $this->load->view('licitacao/licitacao_edit');
        $this->load->view('fix/footer');
    }

    /*
    * CARREGA OS TERMOS DE ADESAO DA SHOW/SIM
    */
    public function listTermos(){
        $empresa = $this->input->post('empresa');
        $executivo = $this->id_user;
        $termos = false;
        $dados = array();
        //EMPRESA = SIMM2M
        if ($empresa == '2') {
            $termos = $this->model_licitacao->listTermosSim($executivo);
            if ($termos) {
                $editar = $print = $aditivo = '';
                foreach ($termos as $key => $termo){
                    $print = $this->auth->is_allowed_block('print_termo') ? '<button data-id='.$termo->id.' class="btn btn-primary btn_getTermo_sim" title="Imprimir Termo"><i class="fa fa-print" ></i></button>' : '<button class="btn btn-secundary" title="Imprimir" disabled ><i class="fa fa-print" ></i></button>';
                    $editar = $this->auth->is_allowed_block('edit_termo') ? '<button data-id='.$termo->id.' data-empresa="2" class="btn btn-info btn_editTermo_sim"  title="Editar Termo"><i class="fa fa-edit"></i></button>' : '<button class="btn btn-secundary" title="Editar Termo" disabled ><i class="fa fa-edit" ></i></button>';
                    $aditivo = $this->auth->is_allowed_block('add_termo') && !isset($termo->aditivo_de) ? '<button data-id='.$termo->id.' data-empresa="2" data-acao="novo" class="btn btn-success btn_addEditAditivos_sim" title="Criar Aditivo" ><i class="fa fa-plus"></i></button>' : '<button class="btn btn-secundary" title="Criar Aditivo" disabled ><i class="fa fa-plus" ></i></button>';
                    $listAditivos = $this->auth->is_allowed_block('edit_termo') && !isset($termo->aditivo_de) ? '<button data-id='.$termo->id.' data-empresa="2" class="btn btn-primary btn_aditivos_sim" title="Listar Aditivos" ><i class="fa fa-eye"></i></button>' : '<button class="btn btn-secundary" title="Listar Aditivos" disabled ><i class="fa fa-eye" ></i></button>';

                    $dados[] = array(
                        'id' => $termo->id,
                        'razaoSocial' => $termo->razao_social,
                        'cpf_cnpj' => $termo->cnpj_cpf,
                        'prestadora' => 'SIMM2M',
                        'admin' => $print.' '.$editar.' '.$aditivo.' '.$listAditivos
                    );
                }
            }
        }
        //EMPRESA = SHOWCURITIBA
        if ($empresa == '3') {
            $termos = $this->model_licitacao->listTermosShowCuritiba($executivo);
            if ($termos) {
                $editar = $print = $aditivo = '';
                foreach ($termos as $key => $termo){
                    $print = $this->auth->is_allowed_block('print_termo') ? '<button data-id='.$termo->id.' class="btn btn-primary btn_getTermo_curitiba" title="Imprimir Termo"><i class="fa fa-print" ></i></button>' : '<button class="btn btn-secundary" title="Imprimir" disabled ><i class="fa fa-print" ></i></button>';
                    $editar = $this->auth->is_allowed_block('edit_termo') ? '<button data-id='.$termo->id.' data-empresa="1" class="btn btn-info btn_editTermo"  title="Editar Termo"><i class="fa fa-edit"></i></button>' : '<button class="btn btn-secundary" title="Editar Termo" disabled ><i class="fa fa-edit" ></i></button>';
                    $addAditivo = $this->auth->is_allowed_block('add_termo') && !isset($termo->aditivo_de) ? '<button data-id='.$termo->id.' data-empresa="1" data-acao="novo" class="btn btn-success btn_addEditAditivos" title="Criar Aditivo" ><i class="fa fa-plus"></i></button>' : '<button class="btn btn-secundary" title="Criar Aditivo" disabled ><i class="fa fa-plus" ></i></button>';
                    $listAditivos = $this->auth->is_allowed_block('add_termo') && !isset($termo->aditivo_de) ? '<button data-id='.$termo->id.' data-empresa="1" class="btn btn-primary btn_aditivosCuritiba" title="Listar Aditivos" ><i class="fa fa-eye"></i></button>' : '<button class="btn btn-secundary" title="Listar Aditivos" disabled ><i class="fa fa-eye" ></i></button>';

                    $dados[] = array(
                        'id' => $termo->id,
                        'razaoSocial' => $termo->razao_social,
                        'cpf_cnpj' => $termo->cnpj_cpf,
                        'prestadora' => lang('show_tecnologia'),
                        'admin' => $print.' '.$editar.' '.$addAditivo.' '.$listAditivos
                    );
                }
            }
        }
        else {
            //EMPRESA = SHOW
            $termos = $this->model_licitacao->listTermosShow($executivo);
            if ($termos) {
                $editar = $print = $aditivo = '';
                foreach ($termos as $termo){
                    $print = $this->auth->is_allowed_block('print_termo') ? '<button data-id='.$termo->id.' class="btn btn-primary btn_getTermo" title="Imprimir Termo"><i class="fa fa-print" ></i></button>' : '<button class="btn btn-secundary" title="Imprimir" disabled ><i class="fa fa-print" ></i></button>';
                    $editar = $this->auth->is_allowed_block('edit_termo') ? '<button data-id='.$termo->id.' data-empresa="1" class="btn btn-info btn_editTermo"  title="Editar Termo"><i class="fa fa-edit"></i></button>' : '<button class="btn btn-secundary" title="Editar Termo" disabled ><i class="fa fa-edit" ></i></button>';
                    $addAditivo = $this->auth->is_allowed_block('add_termo') && !isset($termo->aditivo_de) ? '<button data-id='.$termo->id.' data-empresa="1" data-acao="novo" class="btn btn-success btn_addEditAditivos" title="Criar Aditivo" ><i class="fa fa-plus"></i></button>' : '<button class="btn btn-secundary" title="Criar Aditivo" disabled ><i class="fa fa-plus" ></i></button>';
                    $listAditivos = $this->auth->is_allowed_block('add_termo') && !isset($termo->aditivo_de) ? '<button data-id='.$termo->id.' data-empresa="1" class="btn btn-primary btn_aditivos" title="Listar Aditivos" ><i class="fa fa-eye"></i></button>' : '<button class="btn btn-secundary" title="Listar Aditivos" disabled ><i class="fa fa-eye" ></i></button>';

                    $dados[] = array(
                        'id' => $termo->id,
                        'razaoSocial' => $termo->razao_social,
                        'cpf_cnpj' => $termo->cnpj_cpf,
                        'prestadora' => lang('show_tecnologia'),
                        'admin' => $print.' '.$editar.' '.$addAditivo.' '.$listAditivos
                    );

                }
            }
        }
        echo json_encode( array('status' => true, 'result' => $dados) );
    }

    /*
    * SALVAR TERMO DE ADESAO
    */
    public function salvarTermo($empresa, $aditivo=false){
        if ($dados = $this->input->post()) {

            //DADOS DOS ENDERECOS
            $enderecos = array();
            $enderecos['endereco'] = $dados['endereco'];
            $enderecos['endereco_entrega'] = $dados['endereco_entrega'];

            //DADOS DOS TELEFONES
            $fones = array();
            $fones['fone_cel'] = array('numero' => $dados['fone']['fone_cel']);
            $fones['fone_fixo'] = array('numero' => $dados['fone']['fone_fixo']);

            //DADOS DOS EMAILS
            $emails = array();
            $emails['email'] = array('email' => $dados['email']['email']);
            $emails['email_financeiro'] = array('email' => $dados['email']['email_financeiro']);

            //DADOS DO CLIENTE
            // $inscricao_estadual = isset($dados['cliente']['inscricao_estadual']) ? $dados['cliente']['inscricao_estadual'] : $dados['cliente']['insc_estadual'];
            $cliente = array(
                'nome' => $dados['cliente']['razao_social'],
                'razao_social' => $dados['cliente']['razao_social'],
                'contato' => $dados['termo']['pessoa_contas_pagar'],
                'status' => 2
            );

            $cpfCnpj = preg_replace("/[^0-9]/", "", str_replace("/","",str_replace("-","",str_replace(".","",$dados['cliente']['cnpj_cpf']))));
            strlen($cpfCnpj) <= 11 ? $cliente['cpf'] = $dados['cliente']['cnpj_cpf'] : $cliente['cnpj'] = $dados['cliente']['cnpj_cpf'];

            //DADOS DO TERMO
            $termo = array(
                'cnpj_cpf' => $dados['cliente']['cnpj_cpf'],
                'insc_estadual' => $dados['cliente']['inscricao_estadual'],
                'razao_social' => $dados['cliente']['razao_social'],
                'empresa' => $dados['empresa'],
            );
            $termo += $dados['termo'];

            $termo['data_termo'] = date('Y-m-d');
            $termo['id_user'] = $this->id_user;


            //SALVANDO O TERMO NO BANCO
            $id_termo = false;
            if ($empresa == 2) {
                $termo['sim'] = true;

                //CONVERSAO DE MOEDA PARA FLUTUANTE
                $termo['valor_mensalidade_chip'] = moneyForFloat($dados['termo']['valor_mensalidade_chip']);
                $termo['valor_ativacao_chip'] = moneyForFloat($dados['termo']['valor_ativacao_chip']);
                $termo['taxa_envio'] = moneyForFloat($dados['termo']['taxa_envio']);

                //SE FOR CADASTRAR O TERMO
                $id_termo = $this->model_licitacao->verificaTermoSim($termo['cnpj_cpf']);
                if (!$id_termo){
                    $id_termo = $this->model_licitacao->insertTermoSim($termo);
                    $cliente['informacoes'] = "SIMM2M";
                }

            } else {
                $termo['sim'] = false;

                //CONVERSAO DE MOEDA PARA FLUTUANTE
                $termo['valor_inst_veic'] = moneyForFloat($dados['termo']['valor_inst_veic']);
                $termo['valor_final_un'] = moneyForFloat($dados['termo']['valor_final_un']);
                $termo['valor_mens_veic'] = moneyForFloat($dados['termo']['valor_mens_veic']);
                $termo['total'] = moneyForFloat($dados['termo']['total']);

                $id_termo = $this->model_licitacao->verificaTermoShow($termo['cnpj_cpf']);
                if (!$id_termo) {
                    $id_termo = $this->model_licitacao->insertTermoShow($termo);
                    $cliente['informacoes'] = "TRACKER";
                    // if($eptc){
                    //     $this->db->where('id', $eptc)->update('showtecsystem.contratos_eptc', array('id_termo'=>$id_termo,'status_termo'=>'1'));
                    // }
                }
            }

            //SALVANDO CLIENTE
            if ($id_termo){
                $duplic_clie = false;
                if (isset($cliente['cpf']))
                    $duplic_clie = $this->cliente->clientePorCPF($cliente['cpf']);
                else
                    $duplic_clie = $this->cliente->clientePorCNPJ($cliente['cnpj']);

                if (!$duplic_clie){
                    $cliente_id = $this->cliente->insertCliente($cliente);

                    //INSERE ENDERECOS
                    $enderecos['endereco'] += array(
    					'data_criado' => date('Y-m-d'),
    					'hora_criado' => date('H:i:s'),
    					'cliente_id' => $cliente_id,
                        'pais' => "BRA",
                        'tipo' => 0
                    );
                    //INSERE ENDERECO DE ENTREGA
                    $enderecos['endereco_entrega'] += array(
    					'data_criado' => date('Y-m-d'),
    					'hora_criado' => date('H:i:s'),
    					'cliente_id' => $cliente_id,
                        'pais' => "BRA",
                        'tipo' => 1
                    );
                    $this->endereco->insertBatchEnderecos($enderecos);

                    //INSERE EMAILS
                    $emails['email'] += array(
    					'observacao' => "",
    					'setor' => "3",
    					'data_criado' => date('Y-m-d'),
    					'hora_criado' => date('H:i:s'),
    					'cliente_id' => $cliente_id
    				);

                    $emails['email_financeiro'] += array(
    					'observacao' => "",
    					'setor' => "0",
    					'data_criado' => date('Y-m-d'),
    					'hora_criado' => date('H:i:s'),
    					'cliente_id' => $cliente_id
    				);
                    $this->email_model->insertBatchEmails($emails);

                    //INSERE TELEFONES
                    $fones['fone_cel'] += array(
                        'ddd' => "",
                        'setor' => "3",
                        'observacao' => "",
                        'data_criado' => date('Y-m-d'),
                        'hora_criado' => date('H:i:s'),
                        'cliente_id' => $cliente_id
    				);

                    $fones['fone_fixo'] += array(
                        'ddd' => "",
                        'setor' => "0",
                        'observacao' => "",
                        'data_criado' => date('Y-m-d'),
                        'hora_criado' => date('H:i:s'),
                        'cliente_id' => $cliente_id
    				);
                    $this->telefone->insertBatchTelefones($fones);

                    //PEGA OS DADOS PARA APRESENTAR NA TABELA DE LISTAGEM DE TERMOS
                    if ($empresa == 2) {
                        //EMPRESA = SIMM2M
                        $prestadora = 'SIMM2M';
                        $print = $this->auth->is_allowed_block('print_termo') ? '<button data-id='.$id_termo.' class="btn btn-primary btn_getTermo_sim" title="Imprimir Termo"><i class="fa fa-print" ></i></button>' : '<button class="btn btn-secundary" title="Imprimir" disabled ><i class="fa fa-print" ></i></button>';
                        $editar = $this->auth->is_allowed_block('edit_termo') ? '<button data-id='.$id_termo.' data-empresa="2" class="btn btn-info btn_editTermo_sim"  title="Editar Termo"><i class="fa fa-edit"></i></button>' : '<button class="btn btn-secundary" title="Editar Termo" disabled ><i class="fa fa-edit" ></i></button>';
                        $aditivo = $this->auth->is_allowed_block('add_termo') ? '<button data-id='.$id_termo.' data-empresa="2" data-acao="novo" class="btn btn-success btn_addEditAditivos_sim" title="Criar Aditivo" ><i class="fa fa-plus"></i></button>' : '<button class="btn btn-secundary" title="Criar Aditivo" disabled ><i class="fa fa-plus" ></i></button>';
                        $listAditivos = $this->auth->is_allowed_block('edit_termo') ? '<button data-id='.$id_termo.' data-empresa="2" class="btn btn-primary btn_aditivos_sim" title="Listar Aditivos" ><i class="fa fa-eye"></i></button>' : '<button class="btn btn-secundary" title="Listar Aditivos" disabled ><i class="fa fa-eye" ></i></button>';

                    }else {
                        //EMPRESA = SHOW
                        $prestadora = 'Show Tecnologia';
                        $print = $this->auth->is_allowed_block('print_termo') ? '<button data-id='.$id_termo.' class="btn btn-primary btn_getTermo" title="Imprimir Termo"><i class="fa fa-print" ></i></button>' : '<button class="btn btn-secundary" title="Imprimir" disabled ><i class="fa fa-print" ></i></button>';
                        $editar = $this->auth->is_allowed_block('edit_termo') ? '<button data-id='.$id_termo.' data-empresa="1" class="btn btn-info btn_editTermo"  title="Editar Termo"><i class="fa fa-edit"></i></button>' : '<button class="btn btn-secundary" title="Editar Termo" disabled ><i class="fa fa-edit" ></i></button>';
                        $addAditivo = $this->auth->is_allowed_block('add_termo') ? '<button data-id='.$id_termo.' data-empresa="1" data-acao="novo" class="btn btn-success btn_addEditAditivos" title="Criar Aditivo" ><i class="fa fa-plus"></i></button>' : '<button class="btn btn-secundary" title="Criar Aditivo" disabled ><i class="fa fa-plus" ></i></button>';
                        $listAditivos = $this->auth->is_allowed_block('add_termo') ? '<button data-id='.$id_termo.' data-empresa="1" class="btn btn-primary btn_aditivos" title="Listar Aditivos" ><i class="fa fa-eye"></i></button>' : '<button class="btn btn-secundary" title="Listar Aditivos" disabled ><i class="fa fa-eye" ></i></button>';
                    }

                    $data = array(
                        'id' => $id_termo,
                        'razaoSocial' => $termo['razao_social'],
                        'cpf_cnpj' => $termo['cnpj_cpf'],
                        'prestadora' => $prestadora,
                        'admin' => $print.' '.$editar.' '.$addAditivo.' '.$listAditivos
                    );

                    echo json_encode(array('success' => true, 'retorno' => $data, 'msg' => lang('termo_cadastrado_sucesso') ));

                }else{
                    echo json_encode(array('success' => false, 'msg' => lang('termo_cliente_existente') ));
                }

            }else{
                echo json_encode(array('success' => false, 'msg' => sprintf(lang('termo_cpf_cnpj_ja_cadastrado'), $termo['cnpj_cpf'], $id_termo) ));
            }

        } else {
            echo json_encode(array('success' => false, 'msg' => lang('erro_cadastro_termo') ));
        }
    }

    /*
    * CARREGA O TERMO PARA EDICAO
    */
    public function ajaxloadTermo(){
        $id_termo = $this->input->post('id_termo');
        $empresa = $this->input->post('empresa');
        $ids = array();
        $dados = array();

        $termo = $this->model_licitacao->getTermoById($id_termo, $empresa);
        if ($termo) {
            $cobrancas = ['valor_inst_veic', 'valor_final_un', 'valor_mens_veic', 'total', 'valor_mensalidade_chip', 'valor_ativacao_chip', 'taxa_envio'];
            $datas = ['primeiro_venc_mens', 'primeiro_venc_adesao', 'primeiro_venc_adicional', 'venc_ativacao', 'primeiro_vencimento_mensalidade'];
            $selectOpt = ['bloqueio', 'inst_sigilosa'];

            foreach ($termo as $key => $valor) {
                if (in_array($key, $cobrancas)) {
                    $dados[$key] = number_format($valor, 2, ',','.');

                }elseif (in_array($key, $datas)) {
                    $dados[$key] = date('Y-m-d', strtotime($valor));
                }else {
                    if (in_array($key, $selectOpt)) {
                        $dados[$key] = mb_strtoupper($valor, 'UTF-8');
                    }else {
                        $dados[$key] = $valor;
                    }
                }
            }

            //CARREGA OS DADOS DO CLIENTE
            $cliente = false;
            $cpfCnpj = preg_replace("/[^0-9]/", "", str_replace("/","",str_replace("-","",str_replace(".","",$termo->cnpj_cpf))));
            if(strlen($cpfCnpj) <= 11)
                $cliente = $this->cliente->clientePorCPF($termo->cnpj_cpf, 'id, razao_social')[0];
            else
                $cliente = $this->cliente->clientePorCNPJ($termo->cnpj_cpf, 'id, razao_social')[0];

            if ($cliente) {
                foreach ($cliente as $key => $valor) {
                    if ($key === 'id') {
                        $ids['id_cliente'] = $valor;
                    }else {
                        $dados[$key] = $valor;
                    }
                }
                //CARREGA OS ENDERECOS
                $enderecos = $this->cliente->get_clientes_enderecos($cliente->id);
                if ($enderecos) {
                    foreach ($enderecos as $key => $endereco) {
                        if ($endereco->tipo == '0') {
                            foreach ($endereco as $key => $valor) {
                                if ($key === 'id') {
                                    $ids['id_endereco'] = $valor;
                                }else {
                                    $dados[$key] = $valor;
                                }
                            }
                        }elseif ($endereco->tipo == '1') {
                            foreach ($endereco as $key => $valor) {
                                if ($key === 'id') {
                                    $ids['id_endereco_entrega'] = $valor;
                                }else {
                                    $dados[$key.'_entrega'] = $valor;
                                }
                            }
                        }
                    }
                }
                //CARREGA OS EMAILS
                $emails = $this->cliente->get_clientes_emails($cliente->id, 'id, email, setor');
                if ($emails) {
                    foreach ($emails as $key => $email) {
                        if ($email->setor == '0') {
                            $dados['email_financeiro'] = $email->email;
                            $ids['id_email_financeiro'] = $email->id;

                        }elseif ($email->setor == '3') {
                            $dados['email'] = $email->email;
                            $ids['id_email'] = $email->id;
                        }
                    }
                }
                //CARREGA OS TELEFONES
                $fones = $this->cliente->get_clientes_telefones($cliente->id, 'id, numero, setor');
                if ($fones) {
                    foreach ($fones as $key => $fone) {
                        if ($fone->setor == '0') {
                            $dados['fone_fixo'] = $fone->numero;
                            $ids['id_fone_fixo'] = $fone->id;

                        }elseif ($fone->setor == '3') {
                            $dados['fone_cel'] = $fone->numero;
                            $ids['id_fone_cel'] = $fone->id;
                        }
                    }
                }
                echo json_encode(array('success' => true, 'termo' => $dados, 'ids' => $ids));

            }else {
                echo json_encode(array('success' => false, 'msn' => lang('termo_nao_possui_cliente') ));
            }

        }else {
            echo json_encode(array('success' => false, 'msn' => lang('termo_nao_carregado') ));
        }
    }

    //EDITA OS DADOS DO TERMO DE ADESAO
    public function editTermo(){

        if ($dados = $this->input->post()) {

            //PEGA OS ID'S PARA ATUALIZACAO DOS DADOS
            $id_cliente= $dados['id_cliente'];
            $id_termo = $dados['id_termo'];
            $id_endereco = $dados['id_endereco'];
            $id_endereco_entrega = $dados['id_endereco_entrega'];
            $id_email = $dados['id_email'];
            $id_email_financeiro = $dados['id_email_financeiro'];
            $id_fone_cel = $dados['id_fone_cel'];
            $id_fone_fixo = $dados['id_fone_fixo'];
            $empresa = $dados['empresa'];

            //DADOS DO TERMO
            $termo = array(
                'cnpj_cpf' => $dados['cliente']['cnpj_cpf'],
                'insc_estadual' => $dados['cliente']['inscricao_estadual'],
                'razao_social' => $dados['cliente']['razao_social']
            );
            $termo += $dados['termo'];

            //ATUALIZA O TERMO
            if ($empresa == 2) {
                //CONVERSAO DE MOEDA PARA FLUTUANTE
                $termo['valor_mensalidade_chip'] = moneyForFloat($dados['termo']['valor_mensalidade_chip']);
                $termo['valor_ativacao_chip'] = moneyForFloat($dados['termo']['valor_ativacao_chip']);
                $termo['taxa_envio'] = moneyForFloat($dados['termo']['taxa_envio']);

                $this->model_licitacao->atulizarTermoSim($id_termo, $termo);
            }else {
                //CONVERSAO DE MOEDA PARA FLUTUANTE
                $termo['valor_inst_veic'] = moneyForFloat($dados['termo']['valor_inst_veic']);
                $termo['valor_final_un'] = moneyForFloat($dados['termo']['valor_final_un']);
                $termo['valor_mens_veic'] = moneyForFloat($dados['termo']['valor_mens_veic']);
                $termo['total'] = moneyForFloat($dados['termo']['total']);

                $this->model_licitacao->atulizarTermoShow($id_termo, $termo);
            }

            //DADOS DO CLIENTE
            $cliente = $dados['cliente'];
            $cliente['nome'] = $dados['cliente']['razao_social'];
            $cliente['contato'] = $dados['termo']['pessoa_contas_pagar'];

            $cpfCnpj = preg_replace("/[^0-9]/", "", str_replace("/","",str_replace("-","",str_replace(".","",$dados['cliente']['cnpj_cpf']))));
            strlen($cpfCnpj) <= 11 ? $cliente['cpf'] = $dados['cliente']['cnpj_cpf'] : $cliente['cnpj'] = $dados['cliente']['cnpj_cpf'];
            unset($cliente['cnpj_cpf']);

            //ATUALIZA O CLIENTE
            $this->cliente->atualizar($id_cliente, $cliente);


            //ATUALIZA ENDERECO
            $endereco = $dados['endereco'];
            $this->endereco->atulizarEndereco($id_endereco, $endereco);

            //ATUALIZA ENDERECO DE ENTREGA, CASO ELE NAO EXISTA SERÁ CRIADO
            $enderecoEntrega = $dados['endereco_entrega'];
            $existEndEntraga = $this->endereco->getEnderecos($id_cliente, '1');
            if ($existEndEntraga) {
                $this->endereco->atulizarEndereco($id_endereco_entrega, $enderecoEntrega);
            }else {
                $enderecoEntrega += array(
					'data_criado' => date('Y-m-d'),
					'hora_criado' => date('H:i:s'),
					'cliente_id' => $id_cliente,
                    'pais' => "BRA",
                    'tipo' => 1
                );
                $this->endereco->insertEndereco($enderecoEntrega);
            }

            //ATUALIZA TELEFONE CELULAR, CASO ELE NAO EXISTA SERÁ CRIADO
            $existefoneCell = $this->telefone->getTelefonesCliente($id_cliente, 'numero', '3');
            if ($existefoneCell) {
                $foneCell = array('numero' => $dados['fone']['fone_cel']);
                $this->telefone->atulizarTelefone($id_fone_cel, $foneCell);
            }else {
                $foneCell = array(
                    'ddd' => "",
                    'numero' => $dados['fone']['fone_cel'],
                    'setor' => "3",
                    'observacao' => "",
                    'data_criado' => date('Y-m-d'),
                    'hora_criado' => date('H:i:s'),
                    'cliente_id' => $id_cliente
    			);
                $this->telefone->insertTelefone($foneCell);
            }

            //ATUALIZA TELEFONE FIXO, CASO ELE NAO EXISTA SERÁ CRIADO
            $existefoneFixo = $this->telefone->getTelefonesCliente($id_cliente, 'numero', '0');
            if ($existefoneFixo) {
                $foneFixo = array('numero' => $dados['fone']['fone_fixo']);
                $this->telefone->atulizarTelefone($id_fone_fixo, $foneFixo);
            }else {
                $foneFixo = array(
                    'ddd' => "",
                    'numero' => $dados['fone']['fone_fixo'],
                    'setor' => "0",
                    'observacao' => "",
                    'data_criado' => date('Y-m-d'),
                    'hora_criado' => date('H:i:s'),
                    'cliente_id' => $id_cliente
    			);
                $this->telefone->insertTelefone($foneFixo);
            }

            //ATUALIZA EMAIL PESSOAL, CASO ELE NAO EXISTA SERÁ CRIADO
            $existeEmail = $this->email_model->getEmails($id_cliente, 'email', '3');
            if ($existeEmail) {
                $email = array('email' => $dados['email']['email']);
                $this->email_model->atulizarEmail($id_email, $email);
            }else {
                $email = array(
                    'email' => $dados['email']['email'],
					'observacao' => "",
					'setor' => "3",
					'data_criado' => date('Y-m-d'),
					'hora_criado' => date('H:i:s'),
					'cliente_id' => $id_cliente
    			);
                $this->email_model->insertEmail($email);
            }

            //ATUALIZA EMAIL FINANCEIRO, CASO ELE NAO EXISTA SERÁ CRIADO
            $existeEmailFinanceiro = $this->email_model->getEmails($id_cliente, 'email', '0');
            if ($existeEmailFinanceiro) {
                $emailFinanceiro = array('email' => $dados['email']['email_financeiro']);
                $this->email_model->atulizarEmail($id_email_financeiro, $emailFinanceiro);
            }else {
                $emailFinanceiro = array(
                    'email' => $dados['email']['email_financeiro'],
					'observacao' => "",
					'setor' => "0",
					'data_criado' => date('Y-m-d'),
					'hora_criado' => date('H:i:s'),
					'cliente_id' => $id_cliente
    			);
                $this->email_model->insertEmail($emailFinanceiro);
            }

            echo json_encode(array('success' => true, 'msg' => lang('termo_editado_com_sucesso') ));

        }else {
            echo json_encode(array('success' => false, 'msg' => 'Dados incompletos, verifique os dados e tente novamente!' ));
        }
    }

    //CARREGA OS DADOS DO TERMO PARA IMPRESSAO
    public function page_print($id, $simm2m = false, $curitiba = false){        
        if($simm2m == 'false'){
            $simm2m = false;
        }
        $dados['titulo'] = lang('show_tecnologia');
        $termo = $this->model_licitacao->getTermoPrint($id, $simm2m);


        if ($termo) {
            foreach ($termo as $key => $valor) {
                $dados[$key] = $valor;
            }

            //CARREGA OS DADOS DO CLIENTE
            $cliente = false;
            $cpfCnpj = preg_replace("/[^0-9]/", "", str_replace("/","",str_replace("-","",str_replace(".","",$dados['cnpj_cpf']))));
            if(strlen($cpfCnpj) <= 11)
                $cliente = $this->cliente->clientePorCPF($dados['cnpj_cpf'], 'id, razao_social, inscricao_estadual')[0];
            else
                $cliente = $this->cliente->clientePorCNPJ($dados['cnpj_cpf'], 'id, razao_social, inscricao_estadual')[0];

            if ($cliente) {
                foreach ($cliente as $key => $valor) {
                        $dados[$key] = $valor;
                }
                //CARREGA OS ENDERECOS
                $enderecos = $this->cliente->get_clientes_enderecos($cliente->id);
                if ($enderecos) {
                    foreach ($enderecos as $key => $endereco) {
                        if ($endereco->tipo == '0') {
                            foreach ($endereco as $key => $valor) {
                                    $dados[$key] = $valor;
                            }
                        }elseif ($endereco->tipo == '1') {
                            foreach ($endereco as $key => $valor) {
                                    $dados['endereco_entrega'][$key] = $valor;
                            }
                        }
                    }
                }
                //CARREGA OS EMAILS
                $emails = $this->cliente->get_clientes_emails($cliente->id, 'id, email, setor');
                if ($emails) {
                    foreach ($emails as $key => $email) {
                        if ($email->setor == '0') {
                            $dados['email_financeiro'] = $email->email;

                        }elseif ($email->setor == '3') {
                            $dados['email'] = $email->email;
                        }
                    }
                }
                //CARREGA OS TELEFONES
                $fones = $this->cliente->get_clientes_telefones($cliente->id, 'id, numero, setor');
                if ($fones) {
                    foreach ($fones as $key => $fone) {
                        if ($fone->setor == '0') {
                            $dados['fone_fixo'] = $fone->numero;

                        }elseif ($fone->setor == '3') {
                            $dados['fone_cel'] = $fone->numero;
                        }
                    }
                }

                if(validateDate($dados['data_termo'], 'Y-m-d'))
                    $dados['data_termo'] = strtoupper( traduzData( strftime('%A, %d de %B de %Y', strtotime($dados['data_termo']))) );
                else
                    $dados['data_termo'] = strtoupper( traduzData($dados['data_termo']) );

                $hoje = strtoupper( strftime('%A, %d de %B de %Y', strtotime(date('d-m-Y'))) );
                $dados['data_atual'] = strtoupper(traduzData($hoje));

                $dados['load'] = array('print');
                $this->load->view('fix/header-new', $dados);
                //$simm2m ? $this->load->view('licitacao/print_sim') : $this->load->view('licitacao/print');
                if ($simm2m) {
                    $this->load->view('licitacao/print_sim');
                }elseif ($curitiba) {
                    $this->load->view('licitacao/print_curitiba');
                }else {
                    $this->load->view('licitacao/print');
                }
            }
        }
    }

    //CARREGA A LISTA DOS ADITIVOS DE UM TERMO
    public function ajaxLoadAditivos(){
        $id_termo = $this->input->post('id_termo');
        $empresa = $this->input->post('empresa');

        $aditivos = $this->model_licitacao->getAditivoById($id_termo, $empresa, 'id, data_termo');
        if ($aditivos){
            $data = array();

            if ($empresa == 2) {    //EMPRESA = SIMM2M
                foreach ($aditivos as $aditivo) {
                    $print = $this->auth->is_allowed_block('print_termo') ? '<button data-id='.$aditivo->id.' class="btn btn-primary btn_getTermo_sim" title="Imprimir Termo"><i class="fa fa-print" ></i></button>' : '<button class="btn btn-secundary" title="Imprimir" disabled ><i class="fa fa-print" ></i></button>';
                    $editar = $this->auth->is_allowed_block('edit_termo') ? '<button data-id='.$aditivo->id.' data-empresa="2" data-acao="editar" class="btn btn-info btn_addEditAditivos_sim"  title="Editar Aditivo"><i class="fa fa-edit"></i></button>' : '<button class="btn btn-secundary" title="Editar Aditivo" disabled ><i class="fa fa-edit" ></i></button>';

                    $dataAditivo = '';
                    if(validateDate($aditivo->data_termo, 'Y-m-d'))
                        $dataAditivo = traduzData( strftime('%A, %d de %B de %Y', strtotime($aditivo->data_termo)) );
                    else
                        $dataAditivo = traduzData($aditivo->data_termo);

                    $data[] = array(
                        'id' => $aditivo->id,
                        'data_cadastro' => $dataAditivo,
                        'admin' => $print.' '.$editar
                    );
                }

            }else {     //EMPRESA = SHOW
                foreach ($aditivos as $aditivo) {
                    $print = $this->auth->is_allowed_block('print_termo') ? '<button data-id='.$aditivo->id.' class="btn btn-primary btn_getTermo" title="Imprimir Termo"><i class="fa fa-print" ></i></button>' : '<button class="btn btn-secundary" title="Imprimir" disabled ><i class="fa fa-print" ></i></button>';
                    $editar = $this->auth->is_allowed_block('edit_termo') ? '<button data-id='.$aditivo->id.' data-empresa="1" data-acao="editar" class="btn btn-info btn_addEditAditivos"  title="Editar Aditivo"><i class="fa fa-edit"></i></button>' : '<button class="btn btn-secundary" title="Editar Aditivo" disabled ><i class="fa fa-edit" ></i></button>';

                    $dataAditivo = '';
                    if(validateDate($aditivo->data_termo, 'Y-m-d'))
                        $dataAditivo = traduzData( strftime('%A, %d de %B de %Y', strtotime($aditivo->data_termo)) );
                    else
                        $dataAditivo = traduzData($aditivo->data_termo);

                    $data[] = array(
                        'id' => $aditivo->id,
                        'data_cadastro' => $dataAditivo,
                        'admin' => $print.' '.$editar
                    );
                }
            }

            echo json_encode(array('data' => $data ));

        }else {
            echo json_encode(array('data' => array( ) ));
        }
    }

    /*
    * CARREGA OS DADOS DE UM ADITIVO
    */
    public function ajaxloadDadosAditivo($edicao=false){
        $id_termo = $this->input->post('id_termo');
        $empresa = $this->input->post('empresa');
        $dados = array();

        $termo = $this->model_licitacao->getTermoById($id_termo, $empresa);
        if ($termo) {
            if ($edicao) {
                $cobrancas = ['valor_inst_veic', 'valor_final_un', 'valor_mens_veic', 'total', 'valor_mensalidade_chip', 'valor_ativacao_chip', 'taxa_envio'];
                $datas = ['primeiro_venc_mens', 'primeiro_venc_adesao', 'primeiro_venc_adicional', 'venc_ativacao', 'primeiro_vencimento_mensalidade'];
                $selectOpt = ['bloqueio', 'inst_sigilosa'];

                foreach ($termo as $key => $valor) {
                    if (in_array($key, $cobrancas)) {
                        $dados[$key] = number_format($valor, 2, ',','.');

                    }elseif (in_array($key, $datas)) {
                        $dados[$key] = date('Y-m-d', strtotime($valor));
                    }else {
                        if (in_array($key, $selectOpt)) {
                            $dados[$key] = mb_strtoupper($valor, 'UTF-8');
                        }else {
                            $dados[$key] = $valor;
                        }
                    }
                }

            }else {
                $dados['executivo_vendas'] = $termo->executivo_vendas;
                $dados['cnpj_cpf'] = $termo->cnpj_cpf;
                $dados['insc_estadual'] = $termo->insc_estadual;
            }

            //CARREGA OS DADOS DO CLIENTE
            $cliente = false;
            $cpfCnpj = preg_replace("/[^0-9]/", "", str_replace("/","",str_replace("-","",str_replace(".","",$termo->cnpj_cpf))));
            if(strlen($cpfCnpj) <= 11)
                $cliente = $this->cliente->clientePorCPF($termo->cnpj_cpf, 'id, razao_social')[0];
            else
                $cliente = $this->cliente->clientePorCNPJ($termo->cnpj_cpf, 'id, razao_social')[0];

            if ($cliente) {
                foreach ($cliente as $key => $valor) {
                    if ($key === 'id') {
                        $ids['id_cliente'] = $valor;
                    }else {
                        $dados[$key] = $valor;
                    }
                }
                //CARREGA OS ENDERECOS
                $enderecos = $this->cliente->get_clientes_enderecos($cliente->id);
                if ($enderecos) {
                    foreach ($enderecos as $key => $endereco) {
                        if ($endereco->tipo == '0') {
                            foreach ($endereco as $key => $valor) {
                                if ($key === 'id') {
                                    $ids['id_endereco'] = $valor;
                                }else {
                                    $dados[$key] = $valor;
                                }
                            }
                        }elseif ($endereco->tipo == '1') {
                            foreach ($endereco as $key => $valor) {
                                if ($key === 'id') {
                                    $ids['id_endereco_entrega'] = $valor;
                                }else {
                                    $dados[$key.'_entrega'] = $valor;
                                }
                            }
                        }
                    }
                }
                //CARREGA OS EMAILS
                $emails = $this->cliente->get_clientes_emails($cliente->id, 'id, email, setor');
                if ($emails) {
                    foreach ($emails as $key => $email) {
                        if ($email->setor == '0') {
                            $dados['email_financeiro'] = $email->email;
                            $ids['id_email_financeiro'] = $email->id;

                        }elseif ($email->setor == '3') {
                            $dados['email'] = $email->email;
                            $ids['id_email'] = $email->id;
                        }
                    }
                }
                //CARREGA OS TELEFONES
                $fones = $this->cliente->get_clientes_telefones($cliente->id, 'id, numero, setor');
                if ($fones) {
                    foreach ($fones as $key => $fone) {
                        if ($fone->setor == '0') {
                            $dados['fone_fixo'] = $fone->numero;
                            $ids['id_fone_fixo'] = $fone->id;

                        }elseif ($fone->setor == '3') {
                            $dados['fone_cel'] = $fone->numero;
                            $ids['id_fone_cel'] = $fone->id;
                        }
                    }
                }
                echo json_encode(array('success' => true, 'termo' => $dados, 'ids' => $ids));

            }else {
                echo json_encode(array('success' => false, 'msn' => lang('termo_nao_possui_cliente') ));
            }

        }else {
            echo json_encode(array('success' => false, 'msn' => lang('termo_nao_carregado') ));
        }
    }


    /*
    * SALVAR ADITIVO DE TERMO DE ADESAO
    */
    public function salvarAditivo(){
        if ($dados = $this->input->post()) {
            $empresa = $dados['empresa'];

            //DADOS DO TERMO
            $termo = array(
                'cnpj_cpf' => $dados['cliente']['cnpj_cpf'],
                'insc_estadual' => $dados['cliente']['inscricao_estadual'],
                'razao_social' => $dados['cliente']['razao_social'],
                'aditivo_de' => $dados['id_termo']
            );
            $termo += $dados['termo'];

            $termo['data_termo'] = date('Y-m-d');
            $termo['id_user'] = $this->id_user;

            //SALVANDO O ADITIVO NO BANCO
            $id_termo = false;
            if ($empresa == 2) {
                $termo['sim'] = true;

                //CONVERSAO DE MOEDA PARA FLUTUANTE
                $termo['valor_mensalidade_chip'] = moneyForFloat($dados['termo']['valor_mensalidade_chip']);
                $termo['valor_ativacao_chip'] = moneyForFloat($dados['termo']['valor_ativacao_chip']);
                $termo['taxa_envio'] = moneyForFloat($dados['termo']['taxa_envio']);

                $id_termo = $this->model_licitacao->insertTermoSim($termo);

            } else {
                $termo['sim'] = false;

                //CONVERSAO DE MOEDA PARA FLUTUANTE
                $termo['valor_inst_veic'] = moneyForFloat($dados['termo']['valor_inst_veic']);
                $termo['valor_final_un'] = moneyForFloat($dados['termo']['valor_final_un']);
                $termo['valor_mens_veic'] = moneyForFloat($dados['termo']['valor_mens_veic']);
                $termo['total'] = moneyForFloat($dados['termo']['total']);
                
                $id_termo = $this->model_licitacao->insertTermoShow($termo);
            }

            if ($id_termo)
                echo json_encode(array('success' => true, 'msg' => lang('aditivo_cadastrado_sucesso') ));
            else
                echo json_encode(array('success' => false, 'msg' => lang('aditivo_nao_cadastrado_sucesso') ));
        }
    }

    //EDITA OS DADOS UM ADITIVO
    public function editAditivo(){

        if ($dados = $this->input->post()) {
            $id_termo = $dados['id_termo'];
            $empresa = $dados['empresa'];

            //DADOS DO TERMO
            $termo = array(
                'cnpj_cpf' => $dados['cliente']['cnpj_cpf'],
                'insc_estadual' => $dados['cliente']['inscricao_estadual'],
                'razao_social' => $dados['cliente']['razao_social']
            );
            $termo += $dados['termo'];

            //ATUALIZA O TERMO
            if ($empresa == 2){
                //CONVERSAO DE MOEDA PARA FLUTUANTE
                $termo['valor_mensalidade_chip'] = moneyForFloat($dados['termo']['valor_mensalidade_chip']);
                $termo['valor_ativacao_chip'] = moneyForFloat($dados['termo']['valor_ativacao_chip']);
                $termo['taxa_envio'] = moneyForFloat($dados['termo']['taxa_envio']);

                $this->model_licitacao->atulizarTermoSim($id_termo, $termo);

            }else{
                //CONVERSAO DE MOEDA PARA FLUTUANTE
                $termo['valor_inst_veic'] = moneyForFloat($dados['termo']['valor_inst_veic']);
                $termo['valor_final_un'] = moneyForFloat($dados['termo']['valor_final_un']);
                $termo['valor_mens_veic'] = moneyForFloat($dados['termo']['valor_mens_veic']);
                $termo['total'] = moneyForFloat($dados['termo']['total']);

                $this->model_licitacao->atulizarTermoShow($id_termo, $termo);
            }

            echo json_encode(array('success' => true, 'msg' => lang('aditivo_editado_com_sucesso') ));

        }else {
            echo json_encode(array('success' => false, 'msg' => 'Dados incompletos, verifique os dados e tente novamente!' ));
        }
    }


}
