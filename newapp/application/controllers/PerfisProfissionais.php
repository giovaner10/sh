<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PerfisProfissionais extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('perfil_profissional');
        $this->load->model('mapa_calor');
        
    }

    public function index(){        
        $this->auth->is_allowed('vis_visualizarperfisdeprofissionais');
        $dados['titulo'] = lang('consultas_perfis') . ' - ' .lang('show_tecnologia');
        $dados['load'] = array('dataTable', 'select2');
        $this->mapa_calor->registrar_acessos_url(site_url('/PerfisProfissionais'));
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('omniscore/index');
        $this->load->view('fix/footer_NS');
    }

    /*
    * LISTA CLIENTE - SELECT2 INTELIGENTE
    */
    public function ajaxSelectProfissionais() {
        $data = array('results' => array());

        if ($search = $this->input->get('term')) {
            $profissionais = $this->perfil_profissional->listarProfissionaisFilter($search); # Filtra clientes
            
            if ($profissionais) {
                foreach ($profissionais as $key => $profissional) {
                    $data['results'][] = array(
                        'id' => $profissional->id,
                        'text' => $profissional->nome
                    );
                }
            }
        }
        echo json_encode($data);
    }

    /*
    * RETORNA OS DADOS DO LOG DE UMA CONSULTA
    */
    public function ajax_load_consulta()
    {
        if ($id_log = $this->input->get('id_log')) {
            $log = $this->perfil_profissional->get_log(array('id' => $id_log), 'id, id_perfis_profissionais as id_perfil, id_perfis_profissionais_consultas as id_consultas, score, configuracao_omniscore, tipo_profissional');
            $perfil = $this->perfil_profissional->get_perfil(array('id' => $log->id_perfil), 'id, id_profissionais, id_proprietarios_veiculos, id_veiculos_proprietarios_veiculo, id_veiculos_proprietarios_carreta, id_veiculos_proprietarios_segunda_carreta');
            if (is_object($perfil) && !empty($perfil)) {

                //IMPORTA OS MODELS
                $this->load->model('endereco');
                $this->load->model('telefone');
                $this->load->model('profissional');
                $this->load->model('proprietario_veiculo');
                $this->load->model('veiculo_proprietario');
                $this->load->model('consulta_perfil_profissional');

                $profissional = $this->profissional->get_profissional(array('id' => $perfil->id_profissionais));
                $profissional->cpf = mask($profissional->cpf, "###.###.###-##");
                $profissional->vencimento_cnh = date('d/m/Y', strtotime($profissional->vencimento_cnh));
                $profissional->data_emissao_rg = date('d/m/Y', strtotime($profissional->data_emissao_rg));
                $profissional->data_modificacao = date('d/m/Y', strtotime($profissional->data_modificacao));
                $profissional->data_primeira_habilitacao = date('d/m/Y', strtotime($profissional->data_primeira_habilitacao));
                $fone_residencial = $this->telefone->get_telefone(array('id' => $profissional->id_telefones_residencial), 'numero, contato');
                $fone_comercial = $this->telefone->get_telefone(array('id' => $profissional->id_telefones_comercial), 'numero, contato');
                $endereco = $this->endereco->get_endereco(array('id' => $profissional->id_enderecos), 'endereco, numero, bairro, complemento, cep ,cidade, uf');
                $endereco->cep = mask($endereco->cep, "##.###-###");

                $profissional = (array) $profissional;
                
                if(!empty($fone_residencial))
                    $profissional = array_merge((array) $profissional, array('telefone_residencial' => mask($fone_residencial->numero, "####-####"), 'contato_residencial' => $fone_residencial->contato));

                if(!empty($fone_comercial))
                    $profissional = array_merge($profissional, array('telefone_comercial' => mask($fone_comercial->numero, "####-####"), 'contato_comercial' => $fone_comercial->contato));
                
                if(!empty($endereco))
                    $profissional = array_merge($profissional, (array) $endereco);

                $proprietario = [];
                if ($perfil->id_proprietarios_veiculos) {
                    $proprietario = $this->proprietario_veiculo->get_proprietario(array('id' => $perfil->id_proprietarios_veiculos));
                    $proprietario->cpf_cnpj = strlen($proprietario->cpf_cnpj) == 11 ? mask($proprietario->cpf_cnpj, "###.###.###-##") : mask($proprietario->cpf_cnpj, "##.###.###/####-##");
                    $fone_residencialfone_residencial = $this->telefone->get_telefone(array('id' => $proprietario->id_telefones_residencial), 'numero, contato');
                    $fone_comercial_proprietario = $this->telefone->get_telefone(array('id' => $proprietario->id_telefones_comercial), 'numero, contato');
                    $endereco_proprietario = $this->endereco->get_endereco(array('id' => $proprietario->id_enderecos), 'endereco, numero, bairro, complemento, cep ,cidade, uf');
                    $endereco_proprietario->cep = mask($endereco_proprietario->cep, "##.###-###");

                    $proprietario = (array) $proprietario;

                    if(!empty($fone_residencialfone_residencial))
                        $proprietario = array_merge((array) $proprietario, array('telefone_residencial' => mask($fone_residencialfone_residencial->numero, "####-####"), 'contato_residencial' => $fone_residencialfone_residencial->contato));

                    if(!empty($fone_comercial_proprietario))
                        $proprietario = array_merge($proprietario, array('telefone_comercial' => mask($fone_comercial_proprietario->numero, "####-####"), 'contato_comercial' => $fone_comercial_proprietario->contato));

                    if(!empty($endereco_proprietario))
                        $proprietario =  array_merge($proprietario, (array) $endereco_proprietario);
                }

                $veiculo = [];
                if ($perfil->id_veiculos_proprietarios_veiculo) {
                    $veiculo = $this->veiculo_proprietario->get_veiculo(array('id' => $perfil->id_veiculos_proprietarios_veiculo));
                }

                $carreta = [];
                if ($perfil->id_veiculos_proprietarios_carreta) {
                    $carreta = $this->veiculo_proprietario->get_veiculo(array('id' => $perfil->id_veiculos_proprietarios_carreta));
                }

                $segunda_carreta = [];
                if ($perfil->id_veiculos_proprietarios_segunda_carreta) {
                    $segunda_carreta = $this->veiculo_proprietario->get_veiculo(array('id' => $perfil->id_veiculos_proprietarios_segunda_carreta));
                }

                //Configuracao do omniscore do cliente
                $divisao_rank = 0; $consultar_debito = 'nao';
                if( $log->configuracao_omniscore ){
                    $config = json_decode($log->configuracao_omniscore);
                    $divisao_rank = $config->divisao_rank;                 
                    $consultar_debito = $config->consultar_debito;
                }

                $retorno = array(
                    'profissional' => (object) $profissional,
                    'proprietario' => (object) $proprietario,
                    'veiculo' => (object) $veiculo,
                    'carreta' => (object) $carreta,
                    'segunda_carreta' => (object) $segunda_carreta,
                    'ponto_corte' => $divisao_rank,
                    'score' => $log->score ? $log->score : '100',
                    'tipo_profissional' => $log->tipo_profissional,
                    'consta_obito' => 'nao'
                );

                //CARREGA AS CONSULTAS
                if ($relacao_consultas = $this->consulta_perfil_profissional->get_relacao_consulta(array('id' => $log->id_consultas))) {
                    $retorno['id_consulta'] = $relacao_consultas->id;
                    $retorno['protocolo'] = openssl_encrypt_string('consulta_' . $relacao_consultas->id);
                    //CARREGA A CONSULTA DE CPF
                    if ($relacao_consultas->id_consulta_cpf) {
                        $consulta_cpf = $this->consulta_perfil_profissional->get_consulta_cpf(array('id' => $relacao_consultas->id_consulta_cpf));
                        
                        //Se teve retorno de sucesso ou sucesso parcial
                        if (in_array($consulta_cpf->status_consulta, [1, 2])) {
                            $consulta_cpf->cpf = mask($consulta_cpf->cpf, "###.###.###-##");
                            $consulta_cpf->data_nascimento = date('d/m/Y', strtotime($consulta_cpf->data_nascimento));
                            $consulta_cpf->data_inscricao = date('d/m/Y', strtotime($consulta_cpf->data_inscricao));
                            
                            $retorno['consta_obito'] = $consulta_cpf->consta_obito;

                            if( in_array($consulta_cpf->situacao_cadastral, ['REGULAR', 'regular'])) $consulta_cpf->situacao_cadastral = '<span class="label label-success">'. lang('regular') .'</span>';
                            else $consulta_cpf->situacao_cadastral = '<span class="label label-warning">'. lang('irregular') .'</span>';

                            if( in_array($consulta_cpf->consta_obito, ['SIM', 'sim'])) $consulta_cpf->consta_obito = '<span class="label label-danger">'. lang('sim') .'</span>';
                            else $consulta_cpf->consta_obito = '<span class="label label-success">'. lang('nao') .'</span>';

                            if( $consulta_cpf->path_file) $consulta_cpf->path_file = '<a href="'.base_url($consulta_cpf->path_file).'" target="_blank">'.lang('aqui').'</a>';                            
                            else $consulta_cpf->path_file = '<span class="label label-warning">'.lang('nao_possui').'</span>';                            
                        }                        
                        
                    }
                    if (isset($consulta_cpf)) $retorno['consulta_cpf'] = $consulta_cpf;


                    //CARREGA A CONSULTA DE CNH
                    if ($relacao_consultas->id_consulta_cnh) {
                        $consulta_cnh = $this->consulta_perfil_profissional->get_consulta_cnh(array('id' => $relacao_consultas->id_consulta_cnh));

                        //Se teve retorno de sucesso ou sucesso parcial
                        if (in_array($consulta_cnh->status_consulta, [1, 2])) {
                            $consulta_cnh->data_validade = date('d/m/Y', strtotime($consulta_cnh->data_validade));
                            $consulta_cnh->data_emissao = date('d/m/Y', strtotime($consulta_cnh->data_emissao));

                            if( $consulta_cnh->path_file) $consulta_cnh->path_file = '<a href="'.base_url($consulta_cnh->path_file).'" target="_blank">'.lang('aqui').'</a>';
                            else $consulta_cnh->path_file = '<span class="label label-warning">'.lang('nao_possui').'</span>';
                        }
                        
                    }
                    if (isset($consulta_cnh)) $retorno['consulta_cnh'] = $consulta_cnh;


                    //CARREGA A CONSULTA DE ANTECEDENTES CRIMINAIS
                    if ($relacao_consultas->id_consulta_antecedentes) {
                        $consulta_antecedentes = $this->consulta_perfil_profissional->get_consulta_antecedentes(array('id' => $relacao_consultas->id_consulta_antecedentes));

                        //Se teve retorno de sucesso ou sucesso parcial
                        if (in_array($consulta_antecedentes->status_consulta, [1, 2])) {

                            if( $consulta_antecedentes->possui_anteced_criminais === 'sim') $consulta_antecedentes->possui_anteced_criminais = '<span class="label label-danger">'.lang('sim').'</span>';
                            else $consulta_antecedentes->possui_anteced_criminais = '<span class="label label-success">'.lang('nao').'</span>';

                            $consulta_antecedentes->data_emissao = date('d/m/Y', strtotime($consulta_antecedentes->data_emissao));                                            

                            if( $consulta_antecedentes->path_file) $consulta_antecedentes->path_file = '<a href="'.base_url($consulta_antecedentes->path_file).'" target="_blank">'.lang('aqui').'</a>';
                            else $consulta_antecedentes->path_file = '<span class="label label-warning">'.lang('nao_possui').'</span>';
                        }
                        
                    }
                    if (isset($consulta_antecedentes)) $retorno['consulta_antecedentes'] = $consulta_antecedentes;


                    //CARREGA A CONSULTA DE MANDADOS DE PRISAO
                    $consulta_mandados = $this->consulta_perfil_profissional->list_consulta_mandados(array('id_perfis_profissionais_consultas' => $relacao_consultas->id));
                    if (!empty($consulta_mandados)) {
                        foreach ($consulta_mandados as $mandado) {
                            //Se teve retorno de sucesso ou sucesso parcial
                            if (in_array($mandado->status_consulta, [1, 2]))
                                $mandado->data_expedicao = date('d/m/Y', strtotime($mandado->data_expedicao));
                        }                        
                        $retorno['consulta_mandados'] = $consulta_mandados;   
                    }

                    //CARREGA A CONSULTA DE PROCESSOS
                    $consulta_processos = $this->consulta_perfil_profissional->list_consulta_processos(array('id_perfis_profissionais_consultas' => $relacao_consultas->id));
                    if (!empty($consulta_processos))
                        $retorno['consulta_processos'] = $consulta_processos;

                    //CARREGA A CONSULTA DE DEBITOS FINANCEIROS
                    $consulta_debitos = $this->consulta_perfil_profissional->list_consulta_debitos(array('id_perfis_profissionais_consultas' => $relacao_consultas->id));
                    if (!empty($consulta_debitos)  && $consultar_debito === 'sim') {
                        foreach ($consulta_debitos as $debito) {
                            //Se teve retorno de sucesso ou sucesso parcial
                            if (in_array($debito->status_consulta, [1, 2]) && $debito->cnpj)
                                $debito->data_ocorrencia = $debito->data_ocorrencia ? date('d/m/Y', strtotime($debito->data_ocorrencia)) : null;
                                $debito->cnpj = $debito->cnpj ? mask($debito->cnpj, "##.###.###/####-##") : null;
                                $debito->valor = $debito->valor ? number_format($debito->valor, 2, ',', '.') : null;
                        }                        
                        $retorno['consulta_debitos'] = $consulta_debitos;
                    }


                    //CARREGA A CONSULTA DE VEICULO
                    if ($relacao_consultas->id_consulta_veiculo_carro) {
                        $consulta_veiculo = $this->consulta_perfil_profissional->get_consulta_veiculo(array('id' => $relacao_consultas->id_consulta_veiculo_carro));
                        
                        //Se teve retorno de sucesso ou sucesso parcial
                        if (in_array($consulta_veiculo->status_consulta, [1, 2])) {

                            if( $consulta_veiculo->indicador_roubo_furto === 'sim') $consulta_veiculo->indicador_roubo_furto = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_veiculo->indicador_roubo_furto = '<span class="label label-success">'.lang('nao').'</span>';

                            if( $consulta_veiculo->indicador_pendencia_emissao === 'sim') $consulta_veiculo->indicador_pendencia_emissao = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_veiculo->indicador_pendencia_emissao = '<span class="label label-success">'.lang('nao').'</span>';
                            
                            if( $consulta_veiculo->indicador_restricao_renajud === 'sim') $consulta_veiculo->indicador_restricao_renajud = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_veiculo->indicador_restricao_renajud = '<span class="label label-success">'.lang('nao').'</span>';
                            
                            if( $consulta_veiculo->indicador_multa_renainf === 'sim') $consulta_veiculo->indicador_multa_renainf = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_veiculo->indicador_multa_renainf = '<span class="label label-success">'.lang('nao').'</span>';

                            if( $consulta_veiculo->path_file) $consulta_veiculo->path_file = '<a href="'.base_url($consulta_veiculo->path_file).'" target="_blank">'.lang('aqui').'</a>';
                            else $consulta_veiculo->path_file = '<span class="label label-warning">'.lang('nao_possui').'</span>';
                        }                        
                    }
                    if (isset($consulta_veiculo)) $retorno['consulta_veiculo'] = $consulta_veiculo;

                    //CARREGA A CONSULTA DA CARRETA
                    if ($relacao_consultas->id_consulta_veiculo_carreta) {
                        $consulta_carreta = $this->consulta_perfil_profissional->get_consulta_veiculo(array('id' => $relacao_consultas->id_consulta_veiculo_carreta));
                        
                        //Se teve retorno de sucesso ou sucesso parcial
                        if (in_array($consulta_carreta->status_consulta, [1, 2])) {

                            if( $consulta_carreta->indicador_roubo_furto === 'sim') $consulta_carreta->indicador_roubo_furto = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_carreta->indicador_roubo_furto = '<span class="label label-success">'.lang('nao').'</span>';

                            if( $consulta_carreta->indicador_pendencia_emissao === 'sim') $consulta_carreta->indicador_pendencia_emissao = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_carreta->indicador_pendencia_emissao = '<span class="label label-success">'.lang('nao').'</span>';
                            
                            if( $consulta_carreta->indicador_restricao_renajud === 'sim') $consulta_carreta->indicador_restricao_renajud = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_carreta->indicador_restricao_renajud = '<span class="label label-success">'.lang('nao').'</span>';
                            
                            if( $consulta_carreta->indicador_multa_renainf === 'sim') $consulta_carreta->indicador_multa_renainf = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_carreta->indicador_multa_renainf = '<span class="label label-success">'.lang('nao').'</span>';

                            if( $consulta_carreta->path_file) $consulta_carreta->path_file = '<a href="'.base_url($consulta_carreta->path_file).'" target="_blank">'.lang('aqui').'</a>';
                            else $consulta_carreta->path_file = '<span class="label label-warning">'.lang('nao_possui').'</span>';
                        }                        
                    }
                    if (isset($consulta_carreta)) $retorno['consulta_carreta'] = $consulta_carreta;

                    //CARREGA A CONSULTA DA SEGUNDA CARRETA
                    if ($relacao_consultas->id_consulta_veiculo_segunda_carreta) {
                        $consulta_segunda_carreta = $this->consulta_perfil_profissional->get_consulta_veiculo(array('id' => $relacao_consultas->id_consulta_veiculo_segunda_carreta));
                        
                        //Se teve retorno de sucesso ou sucesso parcial
                        if (in_array($consulta_segunda_carreta->status_consulta, [1, 2])) {

                            if( $consulta_segunda_carreta->indicador_roubo_furto === 'sim') $consulta_segunda_carreta->indicador_roubo_furto = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_segunda_carreta->indicador_roubo_furto = '<span class="label label-success">'.lang('nao').'</span>';

                            if( $consulta_segunda_carreta->indicador_pendencia_emissao === 'sim') $consulta_segunda_carreta->indicador_pendencia_emissao = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_segunda_carreta->indicador_pendencia_emissao = '<span class="label label-success">'.lang('nao').'</span>';
                            
                            if( $consulta_segunda_carreta->indicador_restricao_renajud === 'sim') $consulta_segunda_carreta->indicador_restricao_renajud = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_segunda_carreta->indicador_restricao_renajud = '<span class="label label-success">'.lang('nao').'</span>';
                            
                            if( $consulta_segunda_carreta->indicador_multa_renainf === 'sim') $consulta_segunda_carreta->indicador_multa_renainf = '<span class="label label-warning">'.lang('sim').'</span>';
                            else $consulta_segunda_carreta->indicador_multa_renainf = '<span class="label label-success">'.lang('nao').'</span>';

                            if( $consulta_segunda_carreta->path_file) $consulta_segunda_carreta->path_file = '<a href="'.base_url($consulta_segunda_carreta->path_file).'" target="_blank">'.lang('aqui').'</a>';
                            else $consulta_segunda_carreta->path_file = '<span class="label label-warning">'.lang('nao_possui').'</span>';
                        }                        
                    }
                    if (isset($consulta_segunda_carreta)) $retorno['consulta_segunda_carreta'] = $consulta_segunda_carreta;

                }                
            
                exit(json_encode(array('success' => true, 'retorno' => $retorno)));
            }

            exit(json_encode(array('success' => false, 'msg' => lang('falha_carregar_perfil') )));
        }
    } 

    /**
     * RETORNA OS DADOS DE UMA CONSULTA PARA O LAUDO
    */
    public function carrega_dados_laudo($id_log) {
        $dados = [];

        $log = $this->perfil_profissional->get_log(array('id' => $id_log), 'id, id_perfis_profissionais as id_perfil, id_perfis_profissionais_consultas as id_consultas, score, data_cadastro, id_cad_clientes as id_cliente, configuracao_omniscore, tipo_profissional, id_perfis_profissionais_consultas as id_consulta');
        $perfil = $this->perfil_profissional->get_perfil(
            array('id' => $log->id_perfil), 
            'id, id_profissionais, id_proprietarios_veiculos, id_veiculos_proprietarios_veiculo, id_veiculos_proprietarios_carreta, id_veiculos_proprietarios_segunda_carreta'
        );

        //Configuracao do omniscore do cliente
        $divisao_rank = 0; $debito_corte = 0; $consultar_debito = 'nao';
        if( $log->configuracao_omniscore ){
            $config = json_decode($log->configuracao_omniscore);
            $divisao_rank = $config->divisao_rank;
            $debito_corte = $config->debito_corte;
            $consultar_debito = $config->consultar_debito;
        }

        $this->load->model('profissional');
        $profissional = $this->profissional->get_profissional(array('id' => $perfil->id_profissionais), 'id, cpf, nome, rg, numero_registro_cnh, tipo_profissional');
        $profissional->cpf = mask($profissional->cpf, "###.###.###-##");
        $dados['profissional'] = $profissional;

         #******** CONSULTAS PARA PESSOA FISICA *********
        $status_sucesso = [1, 2];     //SUCESSO
        $status_nao_avaliado = [3, 4, 5, 6, 7]; //TODO PROBLEMA POR INCONSISTENCIA/AUSENCIA DE DADOS

        $consultas_regulares = $consultas_irregulares = $consultas_indisponiveis = $consultas_nao_avaliadas = $comprovantes = [];

        $this->load->model('consulta_perfil_profissional');
        $consultas = $this->consulta_perfil_profissional->get_relacao_perfil_consulta(array('id' => $log->id_consultas), '*');
        
        $consultasRealizadas = [];
        $dados['dados_consultas'] = [];

        //ANALISA A CONSULTA PARA CPF
        if ($consultas->id_consulta_cpf) {
            $consultasRealizadas[] = 'cpf';

            $consulta_cpf = $this->consulta_perfil_profissional->get_consulta_cpf(array('id' => $consultas->id_consulta_cpf), '*');
            $dados['dados_consultas']['cpf'] = array(
                'nome' => $consulta_cpf->nome,
                'cpf' => mask($consulta_cpf->cpf, "###.###.###-##"),
                'data_nascimento' => $consulta_cpf->data_nascimento ? date('d/m/Y H:i:s', strtotime($consulta_cpf->data_nascimento)) : null,
                'data_inscricao' => $consulta_cpf->data_inscricao ? date('d/m/Y H:i:s', strtotime($consulta_cpf->data_inscricao)) : null,
                'situacao_cadastral' => ucfirst(strtolower($consulta_cpf->situacao_cadastral)),
                'consta_obito' => $consulta_cpf->consta_obito === 'nao' ? lang('nao') : lang('sim')
            );
            
            if (in_array($consulta_cpf->status_consulta, $status_sucesso)) {
                if (in_array($consulta_cpf->situacao_cadastral, ['regular', 'REGULAR'])) {
                    $consultas_regulares[] = 'cpf';
                } else {
                    $consultas_irregulares[] = 'cpf';
                }
            } elseif (in_array($consulta_cpf->status_consulta, $status_nao_avaliado)){
                $consultas_nao_avaliadas[] = 'cpf';
            } else {
                $consultas_indisponiveis[] = 'cpf';
            }

            if ($consulta_cpf->path_file && file_exists($consulta_cpf->path_file)) $comprovantes['cpf'] = base_url($consulta_cpf->path_file);
        }

        //ANALISA A CONSULTA PARA ANTECEDENTES CRIMINAIS
        if ($consultas->id_consulta_antecedentes) {
            $consultasRealizadas[] = 'antecedentes';
            $consulta_antecedentes = $this->consulta_perfil_profissional->get_consulta_antecedentes(array('id' => $consultas->id_consulta_antecedentes), '*');
            $dados['dados_consultas']['antecedentes'] = array(
                'data_emissao' => $consulta_antecedentes->data_emissao ? date('d/m/Y H:i:s', strtotime($consulta_antecedentes->data_emissao)) : null,
                'numero_certidao' => $consulta_antecedentes->numero_certidao,
                'possui_anteced_criminais' => $consulta_antecedentes->possui_anteced_criminais === 'nao' ? lang('nao') : lang('sim')
            );
            
            if (in_array($consulta_antecedentes->status_consulta, $status_sucesso)) {
                if ($consulta_antecedentes->possui_anteced_criminais === 'sim') {
                    $consultas_irregulares[] = 'antecedentes';
                } else {
                    $consultas_regulares[] = 'antecedentes';
                }
            } elseif (in_array($consulta_antecedentes->status_consulta, $status_nao_avaliado)){
                $consultas_nao_avaliadas[] = 'antecedentes';
            } else {
                $consultas_indisponiveis[] = 'antecedentes';
            }

            if ($consulta_antecedentes->path_file && file_exists($consulta_antecedentes->path_file)) $comprovantes['antecedentes'] = base_url($consulta_antecedentes->path_file);
        }

        //ANALISA A CONSULTA PARA CNH
        if ($consultas->id_consulta_cnh) {
            $consultasRealizadas[] = 'cnh';

            $consulta_cnh = $this->consulta_perfil_profissional->get_consulta_cnh(array('id' => $consultas->id_consulta_cnh), '*');
            $dados['dados_consultas']['cnh'] = array(
                'numero_registro' => $consulta_cnh->numero_registro,
                'nome_condutor' => $consulta_cnh->nome_condutor,
                'situacao' => ucfirst(strtolower($consulta_cnh->situacao)),
                'data_emissao' => $consulta_cnh->data_emissao ? date('d/m/Y H:i:s', strtotime($consulta_cnh->data_emissao)) : null,
                'data_validade' => $consulta_cnh->data_validade ? date('d/m/Y H:i:s', strtotime($consulta_cnh->data_validade)) : null
            );
            
            if (in_array($consulta_cnh->status_consulta, $status_sucesso)) {
                if (!in_array($consulta_cnh->situacao, ['vencida', 'VENCIDA'])) {
                    $consultas_regulares[] = 'cnh';
                } else {
                    $consultas_irregulares[] = 'cnh';
                }
            } elseif (in_array($consulta_cnh->status_consulta, $status_nao_avaliado)){
                $consultas_nao_avaliadas[] = 'cnh';
            } else {
                $consultas_indisponiveis[] = 'cnh';
            }

            if ($consulta_cnh->path_file && file_exists($consulta_cnh->path_file)) $comprovantes['cnh'] = base_url($consulta_cnh->path_file);
        }

        //ANALISA A CONSULTA PARA VEICULO
        if ($consultas->id_consulta_veiculo_carro) {
            $consultasRealizadas[] = 'veiculo';
            $consulta_veiculo = $this->consulta_perfil_profissional->get_consulta_veiculo(array('id' => $consultas->id_consulta_veiculo_carro), '*');
            $dados['dados_consultas']['veiculo'] = array(
                'placa' => $consulta_veiculo->placa,
                'codigo_renavam' => $consulta_veiculo->codigo_renavam,
                'chassi' => $consulta_veiculo->chassi,
                'nome_proprietario' => $consulta_veiculo->nome_proprietario,
                'numero_identificacao_proprietario' => strlen($consulta_veiculo->numero_identificacao_proprietario) == 11 ? mask($consulta_veiculo->numero_identificacao_proprietario, "###.###.###-##") : mask($consulta_veiculo->numero_identificacao_proprietario, "##.###.###/####-##"),
                'situacao' => ucfirst(strtolower($consulta_veiculo->situacao))
            );
            
            if (in_array($consulta_veiculo->status_consulta, $status_sucesso)) {
                if (in_array($consulta_veiculo->situacao, ['circulacao', 'CIRCULACAO'])) {
                    $consultas_regulares[] = 'veiculo';
                } else {
                    $consultas_irregulares[] = 'veiculo';
                }
            } elseif (in_array($consulta_veiculo->status_consulta, $status_nao_avaliado)){
                $consultas_nao_avaliadas[] = 'veiculo';
            } else {
                $consultas_indisponiveis[] = 'veiculo';
            }

            if ($consulta_veiculo->path_file && file_exists($consulta_veiculo->path_file)) $comprovantes['veiculo'] = base_url($consulta_veiculo->path_file);
        }

        //ANALISA A CONSULTA PARA CARRETA
        if ($consultas->id_consulta_veiculo_carreta) {
            $consultasRealizadas[] = 'carreta';
            $consulta_carreta = $this->consulta_perfil_profissional->get_consulta_veiculo(array('id' => $consultas->id_consulta_veiculo_carreta), '*');
            $dados['dados_consultas']['carreta'] = array(
                'placa' => $consulta_carreta->placa,
                'codigo_renavam' => $consulta_carreta->codigo_renavam,
                'chassi' => $consulta_carreta->chassi,
                'nome_proprietario' => $consulta_carreta->nome_proprietario,
                'numero_identificacao_proprietario' => strlen($consulta_carreta->numero_identificacao_proprietario) == 11 ? mask($consulta_carreta->numero_identificacao_proprietario, "###.###.###-##") : mask($consulta_carreta->numero_identificacao_proprietario, "##.###.###/####-##"),
                'situacao' => ucfirst(strtolower($consulta_carreta->situacao))
            );
            
            if (in_array($consulta_carreta->status_consulta, $status_sucesso)) {
                if (in_array($consulta_carreta->situacao, ['circulacao', 'CIRCULACAO'])) {
                    $consultas_regulares[] = 'carreta';
                } else {
                    $consultas_irregulares[] = 'carreta';
                }
            } elseif (in_array($consulta_carreta->status_consulta, $status_nao_avaliado)){
                $consultas_nao_avaliadas[] = 'carreta';
            } else {
                $consultas_indisponiveis[] = 'carreta';
            }
 
            if ($consulta_carreta->path_file && file_exists($consulta_carreta->path_file)) $comprovantes['carreta'] = base_url($consulta_carreta->path_file);        
        }

        //ANALISA A CONSULTA PARA A SEGUNDA CARRETA
        if ($consultas->id_consulta_veiculo_segunda_carreta) {
            $consultasRealizadas[] = 'segunda_carreta';
            $consulta_segunda_carreta = $this->consulta_perfil_profissional->get_consulta_veiculo(array('id' => $consultas->id_consulta_veiculo_segunda_carreta), '*');
            $dados['dados_consultas']['segunda_carreta'] = array(
                'placa' => $consulta_segunda_carreta->placa,
                'codigo_renavam' => $consulta_segunda_carreta->codigo_renavam,
                'chassi' => $consulta_segunda_carreta->chassi,
                'nome_proprietario' => $consulta_segunda_carreta->nome_proprietario,
                'numero_identificacao_proprietario' => strlen($consulta_segunda_carreta->numero_identificacao_proprietario) == 11 
                    ? mask($consulta_segunda_carreta->numero_identificacao_proprietario, "###.###.###-##") 
                    : mask($consulta_segunda_carreta->numero_identificacao_proprietario, "##.###.###/####-##"),
                'situacao' => ucfirst(strtolower($consulta_segunda_carreta->situacao))
            );
            
            if (in_array($consulta_segunda_carreta->status_consulta, $status_sucesso)) {
                if (in_array($consulta_segunda_carreta->situacao, ['circulacao', 'CIRCULACAO'])) {
                    $consultas_regulares[] = 'segunda_carreta';
                } else {
                    $consultas_irregulares[] = 'segunda_carreta';
                }
            } elseif (in_array($consulta_segunda_carreta->status_consulta, $status_nao_avaliado)){
                $consultas_nao_avaliadas[] = 'segunda_carreta';
            } else {
                $consultas_indisponiveis[] = 'segunda_carreta';
            }
 
            if ($consulta_segunda_carreta->path_file && file_exists($consulta_segunda_carreta->path_file)) $comprovantes['segunda_carreta'] = base_url($consulta_segunda_carreta->path_file);        
        }  

        //ANALISA A CONSULTA MANDADOS DE PRISAO
        $consulta_mandados = $this->consulta_perfil_profissional->get_consulta_mandados_relacao(
            array('rc.id_perfis_profissionais_consultas' => $consultas->id), 
            'c.status_consulta, c.numero_processo, c.nome_orgao, c.data_expedicao'
        );
    
        $status_sucesso[] = 5;
        unset($status_nao_avaliado[2]);  //remove o valor 5 (status)

        if (!empty($consulta_mandados)) {
            $consultasRealizadas[] = 'mandados';

            $dados['dados_consultas']['mandados'] = [];
            function filter_mandados($mandado) {
                if ( isset($mandado->numero_processo) && $mandado->numero_processo) {
                    return $mandado;
                }
            }

            # Filtra mandados criminais e penais
            $dados['dados_consultas']['mandados']['dados'] = array_filter($consulta_mandados, "filter_mandados");
            # Contabiliza a quantidade de mandados localizados
            $dados['dados_consultas']['mandados']['total'] = count($dados['dados_consultas']['mandados']['dados']);            

            if (in_array($consulta_mandados[0]->status_consulta, $status_sucesso)) {
                if ($dados['dados_consultas']['mandados']['total'] > 0) 
                    $consultas_irregulares[] = 'mandados';
                else 
                    $consultas_regulares[] = 'mandados';
            } 
            elseif (in_array($consulta_mandados[0]->status_consulta, $status_nao_avaliado)){
                $consultas_nao_avaliadas[] = 'mandados';
            } 
            else {
                $consultas_indisponiveis[] = 'mandados';
            }           
        }

        //ANALISA A CONSULTA PROCESSOS
        $consulta_processos = $this->consulta_perfil_profissional->get_consulta_processos_relacao(
            array('rc.id_perfis_profissionais_consultas' => $consultas->id), 
            'c.status_consulta, c.numero, c.tribunal_instancia, c.tribunal_nome, c.data_publicacao, c.tribunal_tipo, c.tipo'
        );

        if (!empty($consulta_processos)) {
            $consultasRealizadas[] = 'processos';

            $dados['dados_consultas']['processos'] = [];
            function filter_processos($processo) {
                if (
                    isset($processo->tipo) && $processo->tipo && strpos(strtolower($processo->tipo), 'criminal') !== false || 
                    isset($processo->tribunal_tipo) && $processo->tribunal_tipo && strpos(strtolower($processo->tribunal_tipo), 'criminal') !== false || 
                    isset($processo->tipo) && $processo->tipo && strpos(strtolower($processo->tipo), 'penal') !== false || 
                    isset($processo->tribunal_tipo) && $processo->tribunal_tipo && strpos(strtolower($processo->tribunal_tipo), 'penal') !== false                    
                ) {
                    return $processo;
                }
            }

            # Filtra processos criminais e penais
            $dados['dados_consultas']['processos']['dados'] = array_filter($consulta_processos, "filter_processos");
            # Contabiliza a quantidade de processos localizados
            $dados['dados_consultas']['processos']['total'] = count($dados['dados_consultas']['processos']['dados']);

            if (in_array($consulta_processos[0]->status_consulta, $status_sucesso)) {
                if ($dados['dados_consultas']['processos']['total'] > 0)
                    $consultas_irregulares[] = 'processos';
                else
                    $consultas_regulares[] = 'processos';
            } elseif (in_array($consulta_processos[0]->status_consulta, $status_nao_avaliado)){
                $consultas_nao_avaliadas[] = 'processos';
            } else {
                $consultas_indisponiveis[] = 'processos';
            }           
        }

        //ANALISA A CONSULTA DE DEBITOS
        $consulta_debitos = $this->consulta_perfil_profissional->get_consulta_debitos_relacao(
            array('rc.id_perfis_profissionais_consultas' => $consultas->id), 
            'c.status_consulta, c.cnpj, c.natureza, c.data_ocorrencia, c.valor, c.empresa'
        );

        $dados['dados_consultas']['debitos'] = $consulta_debitos;

        if (!empty($consulta_debitos) && $consultar_debito === 'sim') {
            $consultasRealizadas[] = 'debitos';

            $dados['dados_consultas']['debitos'] = [];
            function filter_debitos($debito) {
                if ( isset($debito->cnpj) && $debito->cnpj) {
                    return $debito;
                }
            }

            # Filtra debitos financeiros
            $dados['dados_consultas']['debitos']['dados'] = array_filter($consulta_debitos, "filter_debitos");
            
            # Contabiliza p valor total de debitos
            $valores = array_column_custom($consulta_debitos, 'valor');
            $total = array_sum($valores);
            $dados['dados_consultas']['debitos']['total'] = $total;

            if (in_array($consulta_debitos[0]->status_consulta, $status_sucesso)) {
                if ($total > 0) {
                    if ($debito_corte && $total > $debito_corte) $consultas_irregulares[] = 'debitos';
                    else $consultas_regulares[] = 'debitos';
                } 
                else {
                    $consultas_regulares[] = 'debitos';
                }
            } elseif (in_array($consulta_debitos[0]->status_consulta, $status_nao_avaliado)){
                $consultas_nao_avaliadas[] = 'debitos';
            } else {
                $consultas_indisponiveis[] = 'debitos';
            }
        }
        
        $this->load->model('cliente');
        $cliente = $this->cliente->get_cliente($log->id_cliente, 'nome, cpf, cnpj, logotipo');
        $cliente->cpf_cnpj = $cliente->cpf ? $cliente->cpf : $cliente->cnpj;
        $cliente->logotipo = $cliente->logotipo && file_exists($cliente->logotipo) ? base_url($cliente->logotipo) : null;

        //Cria o hash que representa o protocolo
        $protocolo = openssl_encrypt_string('consulta_' . $consultas->id);

        //Pega dados da consulta do cpf do perfil
        $consulta_cpf = $this->consulta_perfil_profissional->get_consulta_cpf_by_ids_relacao([$log->id_consultas], 'cpf.consta_obito');

        $dados['consultas_regulares'] = $consultas_regulares;
        $dados['consultas_irregulares'] = $consultas_irregulares;
        $dados['consultas_indisponiveis'] = $consultas_indisponiveis;
        $dados['consultas_nao_avaliadas'] = $consultas_nao_avaliadas;
        $dados['comprovantes'] = $comprovantes;
        $dados['consultas'] = $consultasRealizadas;
        $dados['score'] = $log->score ? $log->score : '100';
        $dados['ponto_corte'] = $divisao_rank;
        $dados['protocolo'] = $protocolo;
        $dados['id_consulta'] = $consultas->id;
        $dados['cliente'] = $cliente;
        $dados['data_consulta'] = date('d/m/Y H:i:s', strtotime($log->data_cadastro));
        $dados['consta_obito'] = !empty($consulta_cpf) ? $consulta_cpf[0]->consta_obito : null;
        $dados['tipo_profissional'] = $log->tipo_profissional;

        exit(json_encode($dados));

    }  
    
    /*
    * Atualiza as configuracoes do omnisearch do cliente
    */
    public function atualiaza_config_omniscore(){
        $dados = $this->input->post();
        if (empty($dados)) exit( json_encode( array( 'success' => false, 'msg' => lang('parametros_invalidos') )));        
        
		$this->load->model('cliente');
        $this->load->model('log_shownet');

        //para registro de log
		$id_user = $this->auth->get_login_dados('user');
		$id_user = (int) $id_user;

        //pegar dados antigos do cliente.
        $clientebd = $this->cliente->get(array('id' => $dados['id_cliente'])); 

        $dados_cliente_antigo = array(	            
            'configuracao_omniscore'       => $clientebd->configuracao_omniscore,
        ); 

        if( $this->cliente->atualizar_cliente($dados['id_cliente'], array('configuracao_omniscore' => json_encode(array('acesso' => $dados['acesso'])) )) ){

            $dados_cliente_novos = array('configuracao_omniscore' => json_encode(array('acesso' => $dados['acesso'])) );
			$this->log_shownet->gravar_log($id_user, 'cad_clientes', $dados['id_cliente'], 'atualizar', $dados_cliente_antigo, $dados_cliente_novos);

            exit( json_encode( array( 'success' => true, 'msg' => lang('atualizacao_config_omniscore_sucesso') )));
        }else{
        exit( json_encode( array( 'success' => false, 'msg' => lang('atualizacao_config_omniscore_falha') )));   
        }
    }


    /**
     * CARREGA OS DADOS DO LAUDO DE VITIMISMO
    */
    public function carrega_laudo_vitimismo($id_log) {
        $dados = [];

        $log = $this->perfil_profissional->get_log(
            array('id' => $id_log),
            'id, id_perfis_profissionais as id_perfil, id_perfis_profissionais_consultas as id_consultas, cpf_profissional, score, data_cadastro, id_cad_clientes as id_cliente, divisao_rank, debito_corte, tipo_profissional'
        );

        $perfil = $this->perfil_profissional->get_perfil(
            array('id' => $log->id_perfil), 
            'id, id_profissionais, id_proprietarios_veiculos, id_veiculos_proprietarios_veiculo, id_veiculos_proprietarios_carreta'
        );        
        
        $this->load->model('consulta_perfil_profissional');
        $consultas = $this->consulta_perfil_profissional->get_relacao_perfil_consulta(array('id' => $log->id_consultas), '*');     
        
        //ANALISA A CONSULTA PROCESSOS
        $processos = $this->consulta_perfil_profissional->get_consulta_processos_relacao(
            array('rc.id_perfis_profissionais_consultas' => $consultas->id), 
            'c.id, c.status_consulta, c.numero, c.tribunal_instancia, c.tribunal_nome, c.tribunal_tipo, c.tipo, c.data_publicacao, c.assunto, c.tribunal_distrito, c.estado, c.situacao'
        );

        $dados['processos'] = [];

        if (!empty($processos)) {
            $ids_processos = array_column_custom($processos, 'id');
    
            $partes = [];
            $todas_partes = $this->consulta_perfil_profissional->get_partes_por_ids_processos($ids_processos);
            foreach ($todas_partes as $key => $parte) {
                if ($parte->documento == $log->cpf_profissional && strtolower($parte->tipo) === 'requerente') {
                    $partes[$parte->id_consulta_processos] = true;
                }
            }

            foreach ($processos as $processo) {
                if (isset($partes[$processo->id]) && $partes[$processo->id]) {
                    $dados['processos'][] = $processo;
                }
            }
        }
        
        //Pega os dados do profissional
        $this->load->model('profissional');
        $profissional = $this->profissional->get_profissional(array('id' => $perfil->id_profissionais), 'id, cpf, nome, rg, numero_registro_cnh, tipo_profissional');
        $profissional->cpf = mask($profissional->cpf, "###.###.###-##");
        $dados['profissional'] = $profissional;
        
        $this->load->model('cliente');
        $cliente = $this->cliente->get_cliente($log->id_cliente, 'nome, cpf, cnpj, logotipo');
        $cliente->cpf_cnpj = $cliente->cpf ? $cliente->cpf : $cliente->cnpj;
        $cliente->logotipo = $cliente->logotipo && file_exists($cliente->logotipo) ? base_url($cliente->logotipo) : null;

        //Cria o hash que representa o protocolo
        $protocolo = openssl_encrypt_string('consulta_' . $consultas->id);

        $dados['protocolo'] = $protocolo;
        $dados['id_consulta'] = $consultas->id;
        $dados['cliente'] = $cliente;
        $dados['data_consulta'] = date('d/m/Y H:i:s', strtotime($log->data_cadastro));
        $dados['tipo_profissional'] = $log->tipo_profissional;

        exit(json_encode($dados));
    }


    /**
     * CARREGA OS DADOS DO LAUDO RESUMIDO
    */
    public function carrega_laudo_resumido($id_log) {
        $dados = [];
        
        //Pega os dados do log da consulta
        $log = $this->perfil_profissional->get_log(
            array('id' => $id_log),
            'id, id_perfis_profissionais as id_perfil, id_perfis_profissionais_consultas as id_consulta, cpf_profissional, score, data_cadastro, id_cad_clientes as id_cliente, configuracao_omniscore, tipo_profissional, validade, id_usuario_gestor'
        );

        //Pega os dados do perfil
        $perfil = $this->perfil_profissional->get_perfil(
            array('id' => $log->id_perfil), 
            'id, id_profissionais'
        ); 
        
        //Pega os dados do profissional
        $this->load->model('profissional');
        $profissional = $this->profissional->get_profissional(array('id' => $perfil->id_profissionais), 'id, cpf, nome, rg, numero_registro_cnh, tipo_profissional');        
        $profissional->cpf = mask($profissional->cpf, "###.###.###-##");
        $dados['profissional'] = $profissional;
        
        $this->load->model('cliente');
        $cliente = $this->cliente->get(array('id' => $log->id_cliente));
        $cliente->cpf_cnpj = $cliente->cpf ? $cliente->cpf : $cliente->cnpj;
        $cliente->logotipo = $cliente->logotipo && file_exists($cliente->logotipo) ? base_url($cliente->logotipo) : null;

        $this->load->model('usuario_gestor');
        $usuario = $this->usuario_gestor->get_usuario_for_id($log->id_usuario_gestor, 'nome_usuario');

        //Cria o hash que representa o protocolo
        $protocolo = openssl_encrypt_string('consulta_' . $log->id_consulta);

        //Configuracao do omniscore do cliente
        $divisao_rank = 0;
        if( $log->configuracao_omniscore ){
            $config = json_decode($log->configuracao_omniscore);
            $divisao_rank = $config->divisao_rank;
        }

        //Pega dados da consulta do cpf do perfil
        $this->load->model('consulta_perfil_profissional');
        $consulta_cpf = $this->consulta_perfil_profissional->get_consulta_cpf_by_ids_relacao([$log->id_consulta], 'cpf.consta_obito');

        $dados += array(
            'protocolo' => $protocolo,
            'id_consulta' => $log->id_consulta,
            'cliente' => $cliente,
            'nome_usuario' => $usuario->nome_usuario,
            'data_consulta' => date('d/m/Y H:i:s', strtotime($log->data_cadastro)),
            'vencimento' => date('d/m/Y H:i:s', strtotime($log->validade)),
            'score' => $log->score,
            'consta_obito' => !empty($consulta_cpf) ? $consulta_cpf[0]->consta_obito : null,
            'ponto_corte' => $divisao_rank,
            'tipo_profissional' => $log->tipo_profissional
        );

        exit(json_encode($dados));
    }
    


}
