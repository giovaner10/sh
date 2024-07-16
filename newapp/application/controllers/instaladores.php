<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Instaladores extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('instalador');
        $this->load->model('mapa_calor');
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->helper('util_instaladores_helper');
    }

    public function index()
    {
        redirect(site_url('instaladores/listar_instaladores'));
    }

    public function entrar()
    {
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'login',
                    'label' => 'Email',
                    'rules' => 'required|valid_email|trim'
                ),
                array(
                    'field' => 'senha',
                    'label' => 'Senha',
                    'rules' => 'required|trim|md5'
                )
            );

            $this->form_validation->set_rules($rules);
            if (!$this->form_validation->run()) {
                $dados['erro'] = validation_errors('<p>', '</p>');
            } else {
                if ($this->auth->logar_instalador($this->input->post('login'), $this->input->post('senha'))) {
                    redirect(site_url('home/instalador'));
                } else {
                    $dados['erro'] = 'Usuário ou senha invalidos.';
                }
            }
        }

        $dados['titulo'] = 'Acesso Restrito - SHOWNET';
        $this->lang->load('pt', 'portuguese');
        $this->load->view('new_views/fix/login', $dados);
    }

    public function autlog()
    {

        if ($this->auth->logar_instalador($this->input->post('login'), $this->input->post('senha'))) {
            echo '{"success":true}';
        } else {
            echo '{"success":false}';
        }
    }

    public function entrar2()
    {
        if ($this->input->post()) {
            $rules = array(
                array(
                    'field' => 'login',
                    'label' => 'Email',
                    'rules' => 'required|valid_email|trim'
                ),
                array(
                    'field' => 'senha',
                    'label' => 'Senha',
                    'rules' => 'required|trim|md5'
                )
            );

            $this->form_validation->set_rules($rules);
            if (!$this->form_validation->run()) {
                $dados['erro'] = validation_errors('<p>', '</p>');
            } else {
                if ($this->auth->logar_instalador($this->input->post('login'), $this->input->post('senha'))) {
                    redirect(site_url('home/instalador2'));
                } else {
                    $dados['erro'] = 'Username or password is invalid.';
                }
            }
        }

        $dados['titulo'] = 'Restricted access - SHOWNET';
        $this->lang->load('en', 'english');
        $this->load->view('new_views/fix/login', $dados);
    }

    public function sair($area = 'instalador')
    {
        $this->auth->logout($area);
        redirect(site_url('home/instalador'));
    }

    public function get_cidades()
    {
        $sigla = $this->input->post('sigla');
        $cidades = $this->instalador->get_cidades($sigla);
        exit(json_encode($cidades));
    }

    public function listar_instaladores()
    {
        $this->auth->is_logged('admin');
        $this->mapa_calor->registrar_acessos_url(site_url('/instaladores/listar_instaladores'));

        $dados['titulo'] = 'Show Tecnologia';
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $valores = $this->instalador->get_valores();
        $dados['valores'] = $valores;
        $qtd_instaladores = $this->instalador->get_total_instaladores();
        $dados['qtd_instaladores'] = $qtd_instaladores;

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('instaladores/listar_inst');
        $this->load->view('fix/footer_NS');
    }

    public function listar_instaladores_old()
    {
        $this->auth->is_logged('admin');
        $this->mapa_calor->registrar_acessos_url(site_url('/instaladores/listar_instaladores_old'));

        $dados['titulo'] = 'Show Tecnologia';


        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('instaladores/listar');
        $this->load->view('fix/footer_NS');
    }

    public function view_op()
    {
        $this->auth->is_logged('admin');
        $para_view['id'] = $_GET['id'];
        $para_view['titulo'] = 'Show Tecnologia';

        $this->load->view('fix/header', $para_view);
        $this->load->view('instaladores/view');
        $this->load->view('fix/footer');
    }

    public function ordens_pagamento()
    {
        $this->auth->is_logged('admin');

        $para_view['load'] = array('ag-grid', 'select2', 'mask');
        if ($_GET['id']) {
            $para_view['id'] = $_GET['id'];
            $para_view['titulo'] = lang('ordens_pagamento');

            $this->load->view('new_views/fix/header', $para_view);
            $this->load->view('instaladores/ordens_pagamento');
            $this->load->view('fix/footer_NS');
        } else {
            redirect(site_url('instaladores/listar_instaladores'));
        }

    }

    public function planilha_instaladores()
    {
        $this->auth->is_logged('admin');
        $this->session->unset_userdata('filtro_cliente');
        $this->load->model('cliente');
        $this->load->model('veiculo');
        $dados_clientes = $this->cliente->busca_cliente(array());
        $dados_usuarios = $this->usuario->get_usuGestor(array());
        $usuarios = array();
        $j_clientes = array();
        $id_cliente = array();
        $placas = array();
        if (count($dados_clientes) > 0) {
            foreach ($dados_clientes as $cli) {
                $j_clientes[] = $cli->nome;
                $id_cliente[] = $cli->id;
            }
        }
        if (count($dados_usuarios) > 0) {
            foreach ($dados_usuarios as $usuario) {
                $usuarios[] = $usuario->usuario;
            }
        }
        $dados['usuarios'] = json_encode($usuarios);
        $dados['j_clientes'] = json_encode($j_clientes);
        $dados['id_cliente'] = json_encode($id_cliente);
        $dados['titulo'] = 'Show Tecnologia';
        $this->load->view('fix/header', $dados);
        $this->load->view('instaladores/planilha');
        $this->load->view('fix/footer');
    }

    public function get_cli()
    {
        $this->load->model('cliente');
        $dados_clientes = $this->cliente->busca_cliente(array());
        $data = array();
        if (count($dados_clientes) > 0) {
            foreach ($dados_clientes as $cli) {
                $data[] = $cli;
            }
        }
        echo json_encode($data);
    }

    public function add()
    {
        $dados['titulo'] = 'Show Tecnologia - Instaladores';
        $valores = $this->instalador->get_valores();
        $dados['valores'] = $valores;
        // BUSCA QUANTIDADE DE INSTALADORES
        $qtd_instaladores = $this->instalador->get_total_instaladores();
        $dados['qtd_instaladores'] = $qtd_instaladores;

        if ($this->input->post()) {
            $dados = $this->input->post();
            $dados['data_criacao'] = date('Y-m-d H:i:s');
            $formatacaoData = 'd/m/Y';
            $dataNascimento = DateTime::createFromFormat($formatacaoData, $dados['data_nascimento']);
            $dados['data_nascimento'] = $dataNascimento->format('Y-m-d H:i:s');
            $dados['senha'] = md5($dados['senha']);
            $dados['rsenha'] = md5($dados['rsenha']);
            $tel = array('(', ')', ' ');
            $cpf = array('.', '-');
            $cnpj = array('.', '/', '-');
            $dados['telefone'] = str_replace($tel, '', $dados['telefone']);
            $dados['celular'] = str_replace($tel, '', $dados['celular']);
            if ($this->input->post('cpf') != null) {
                $dados['cpf'] = str_replace($cpf, '', $dados['cpf']);
            }
            if ($this->input->post('cnpj') != null) {
                $dados['cnpj'] = str_replace($cnpj, '', $dados['cnpj']);
            }
            if ($this->input->post('radio_cpf_titular') != null) {
                $dados['cpf_conta'] = $dados['cpf_titular'];
            }
            if ($this->input->post('radio_cnpj_titular') != null) {
                $dados['cpf_conta'] = $dados['cnpj_titular'];
            }
            unset($dados['radio_cpf_titular']);
            unset($dados['cpf_titular']);
            unset($dados['radio_cnpj_titular']);
            unset($dados['cnpj_titular']);
            $dados['valor_instalacao'] = str_replace(',', '.', $dados['valor_instalacao']);
            $dados['valor_retirada'] = str_replace(',', '.', $dados['valor_retirada']);
            $dados['valor_manutencao'] = str_replace(',', '.', $dados['valor_manutencao']);
            $dados['valor_desloc_km'] = str_replace(',', '.', $dados['valor_desloc_km']);
            $dados['pis'] = str_replace('.', '', $dados['pis']);
            $dados['pis'] = str_replace('-', '', $dados['pis']);

            $retorno = $this->instalador->add($dados);
            $dados['retorno'] = $retorno;
            $dados['block'] = false;
        } else {
            $dados['retorno'] = false;
            $dados['block'] = true;
        }

        $dados['titulo'] = "Show Tecnologia";
        $this->load->view('fix/header-new', $dados);
        $this->load->view('instaladores/signup');
        $this->load->view('fix/footer_new');
    }

    public function getAllBanks()
    {
        $nome = $this->input->post('nome');

        $result = $this->instalador->get_banks($nome);

        echo json_encode($result);
    }

    public function getBankByCode()
    {
        //$code = $this->input->post('code');

        $result = $this->instalador->get_bank_by_code($_GET['code']);

        echo json_encode($result);
    }

    public function inserir_instalador()
    {

        $this->form_validation->set_rules('nome', 'nome');
        $this->form_validation->set_rules('sobrenome', 'sobrenome');
        $this->form_validation->set_rules('data_nascimento', 'data_nascimento');
        $this->form_validation->set_rules('email', 'email');
        $this->form_validation->set_rules('senha', 'senha');
        $this->form_validation->set_rules('telefone', 'telefone');
        $this->form_validation->set_rules('celular', 'celular');
        $this->form_validation->set_rules('cep', 'cep');
        $this->form_validation->set_rules('endereco', 'endereco');
        $this->form_validation->set_rules('numero', 'numero');
        $this->form_validation->set_rules('bairro', 'bairro');
        $this->form_validation->set_rules('estado', 'estado');
        $this->form_validation->set_rules('cidade', 'cidade');
        $this->form_validation->set_rules('valor_instalacao', 'valor_instalacao');
        $this->form_validation->set_rules('valor_manutencao', 'valor_manutencao');
        $this->form_validation->set_rules('valor_retirada', 'valor_retirada');
        $this->form_validation->set_rules('valor_desloc_km', 'valor_desloc_km');
        $this->form_validation->set_rules('titular_conta', 'titular_conta');
        $this->form_validation->set_rules('banco', 'banco');
        $this->form_validation->set_rules('agencia', 'agencia');
        $this->form_validation->set_rules('conta', 'conta');
        $this->form_validation->set_rules('operacao', 'operacao');
        $this->form_validation->set_rules('tipo_conta', 'tipo_conta');


        if ($this->form_validation->run()) {

            $dados['nome'] = $this->input->post('nome');
            $dados['sobrenome'] = $this->input->post('sobrenome');
            $dados['email'] = $this->input->post('email');
            $dados['telefone'] = $this->input->post('telefone');
            $dados['celular'] = $this->input->post('celular');
            $dados['cep'] = $this->input->post('cep');
            $dados['endereco'] = $this->input->post('endereco');
            $dados['numero'] = $this->input->post('numero');
            $dados['bairro'] = $this->input->post('bairro');
            $dados['estado'] = $this->input->post('estado');
            $dados['cidade'] = $this->input->post('cidade');
            $dados['valor_instalacao'] = $this->input->post('valor_instalacao');
            $dados['valor_manutencao'] = $this->input->post('valor_manutencao');
            $dados['valor_retirada'] = $this->input->post('valor_retirada');
            $dados['valor_desloc_km'] = $this->input->post('valor_desloc_km');
            $dados['titular_conta'] = $this->input->post('titular_conta');
            $dados['banco'] = $this->input->post('banco');
            $dados['agencia'] = $this->input->post('agencia');
            $dados['conta'] = $this->input->post('conta');
            $dados['operacao'] = $this->input->post('operacao');
            $dados['tipo_conta'] = $this->input->post('tipo_conta');
            $dados['status'] = $this->input->post('status');
            $dados['data_cad'] = date('Y-m-d H:i:s');

            if ($this->input->post('id') != NULL) {

                $this->instaladores->atualizar($dados, $this->input->post('id'));

                $this->session->set_flashdata('editado', '<div class="alert alert-success" role="alert">Instalador editado com sucesso ! </div>');
                redirect('instaladores/listar_instaladores');
            } else {

                $this->instaladores->add($dados);
                $this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Instalador cadastrado com sucesso ! </div>');
                redirect('instaladores/listar_instaladores');
            }
        } else {
            $this->session->set_flashdata('dados', '<div class="alert alert-success" role="alert">Preencha todos os campos corretamente ! </div>');
            redirect('instaladores/listar_instaladores');
        }
    }

    public function inserir_instalador_new()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        function checkEmpty($value)
        {
            return isset($value) && $value !== '' && $value != null ? $value : null;
        }

        if (!checkEmpty($data['id'])) {
            if (checkEmpty($this->instalador->getInstallerParameters(['email' => $data['email']]))) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Já existe um instalador com este email!'
                );
                echo json_encode($response);
                return;
            }

            if (checkEmpty($data['cpf']) && checkEmpty($this->instalador->getInstallerParameters(['cpf' => $data['cpf']]))) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Já existe um instalador com este cpf!'
                );
                echo json_encode($response);
                return;
            }

            if (checkEmpty($data['cnpj']) && checkEmpty($this->instalador->getInstallerParameters(['cnpj' => $data['cnpj']]))) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Já existe um instalador com este cnpj!'
                );
                echo json_encode($response);
                return;
            }

            if (checkEmpty($this->instalador->getInstallerParameters(['pis' => $data['pis']]))) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Já existe um instalador com este pis!'
                );
                echo json_encode($response);
                return;
            }

            if (checkEmpty($this->instalador->getInstallerParameters(['cpf_conta' => $data['cpf_conta']]))) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Já existe um titular de conta com este cpf!'
                );
                echo json_encode($response);
                return;
            }

        }


        $dados_instalador = array(
            'nome' => checkEmpty($data['nome']),
            'sobrenome' => checkEmpty($data['sobrenome']),
            'data_nascimento' => checkEmpty($data['data_nascimento']),
            'email' => checkEmpty($data['email']),
            'senha' => isset($data['senha']) && $data['senha'] !== '' ? password_hash($data['senha'], PASSWORD_BCRYPT) : null,
            'rsenha' => isset($data['rsenha']) && $data['rsenha'] !== '' ? password_hash($data['rsenha'], PASSWORD_BCRYPT) : null,
            'telefone' => checkEmpty($data['telefone']),
            'celular' => checkEmpty($data['celular']),
            'cep' => checkEmpty($data['cep']),
            'endereco' => checkEmpty($data['endereco']),
            'numero' => checkEmpty($data['numero']),
            'bairro' => checkEmpty($data['bairro']),
            'estado' => checkEmpty($data['estado']),
            'cidade' => checkEmpty($data['cidade']),
            'valor_instalacao' => checkEmpty($data['valor_instalacao']),
            'valor_manutencao' => checkEmpty($data['valor_manutencao']),
            'valor_retirada' => checkEmpty($data['valor_retirada']),
            'valor_desloc_km' => checkEmpty($data['valor_desloc_km']),
            'banco' => checkEmpty($data['banco']),
            'agencia' => checkEmpty($data['agencia']),
            'operacao' => checkEmpty($data['operacao']),
            'conta' => checkEmpty($data['conta']),
            'cpf' => checkEmpty($data['cpf']),
            'cnpj' => checkEmpty($data['cnpj']) ? intval(str_replace(array('.', '-', '/'), '', $data['cnpj'])) : null,
            'rg' => checkEmpty($data['rg']),
            'pis' => checkEmpty($data['pis']),
            'estado_civil' => checkEmpty($data['estado_civil']),
            'tipo_conta' => checkEmpty($data['tipo_conta']),
            'cpf_conta' => checkEmpty($data['cpf_conta']),
            'titular_conta' => checkEmpty($data['titular_conta']),
            'data_criacao' => date('Y-m-d H:i:s'),
            'radioDoc' => checkEmpty($data['radioDoc'])
        );

        $dados_banco = array(
            'banco' => checkEmpty($data['banco']),
            'agencia' => checkEmpty($data['agencia']),
            'operacao' => checkEmpty($data['operacao']),
            'conta' => checkEmpty($data['conta']),
            'tipo' => checkEmpty($data['tipo_conta']),
            'cpf' => checkEmpty($data['cpf_conta']),
            'titular' => checkEmpty($data['titular_conta']),
            'data_cad' => date('Y-m-d H:i:s'),
            'status' => '1'
        );

        try {
            if (isset($data['id']) && !empty($data['id'])) {
                unset($dados_instalador['senha']);
                unset($dados_instalador['rsenha']);
                $this->instalador->atualizar($dados_instalador, $data['id']);
                $dados_banco['id_retorno'] = $data['id'];
                $dados_banco['cad_retorno'] = 'instalador';
                $this->instalador->updateBankAccount($dados_banco);
                $response = array(
                    'status' => 'success',
                    'message' => 'Instalador editado com sucesso!'
                );
            } else {
                $id_instalador = $this->instalador->addInstaller($dados_instalador);
                if ($id_instalador) {
                    $dados_banco['id_retorno'] = $id_instalador;
                    $dados_banco['cad_retorno'] = 'instalador';
                    $this->instalador->addBankAccount($dados_banco);
                    $response = array(
                        'status' => 'success',
                        'message' => 'Instalador cadastrado com sucesso!'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Erro ao cadastrar o instalador'
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Erro ao processar a requisição: ' . $e->getMessage());
            $response = array(
                'status' => 'error',
                'message' => 'Erro ao processar a requisição: ' . $e->getMessage()
            );
        }

        echo json_encode($response);
    }

    public function update_conta_new()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $cpf = $this->input->get('cpf');
        $installer_id = $this->input->get('installer_id');

        if (empty($cpf) || empty($installer_id)) {
            echo json_encode(
                array(
                    'status' => 'error',
                    'message' => 'CPF e ID do instalador são obrigatórios.'
                )
            );
            return;
        }

        function checkEmpty($value)
        {
            return isset($value) && $value !== '' && $value != null ? $value : null;
        }

        $dados_banco = array(
            'banco' => checkEmpty($data['banco']),
            'agencia' => checkEmpty($data['agencia']),
            'operacao' => checkEmpty($data['operacao']),
            'conta' => checkEmpty($data['conta']),
            'tipo' => checkEmpty($data['tipo_conta']),
            'cpf' => checkEmpty($data['cpf_conta']),
            'titular' => checkEmpty($data['titular_conta']),
            'data_cad' => date('Y-m-d H:i:s'),
            'status' => '1'
        );

        if (empty($dados_banco['banco']) || empty($dados_banco['agencia']) || empty($dados_banco['conta']) || empty($dados_banco['tipo']) || empty($dados_banco['cpf']) || empty($dados_banco['titular'])) {
            echo json_encode(
                array(
                    'status' => 'error',
                    'message' => 'Todos os campos bancários são obrigatórios.'
                )
            );
            return;
        }

        try {
            $this->db->trans_start();

            
            $this->instalador->update_bank_account($dados_banco, $cpf, $installer_id);

            $dados_instalador = array(
                'banco' => $dados_banco['banco'],
                'agencia' => $dados_banco['agencia'],
                'operacao' => $dados_banco['operacao'],
                'conta' => $dados_banco['conta'],
                'tipo_conta' => $dados_banco['tipo'],
                'cpf_conta' => $dados_banco['cpf'],
                'titular_conta' => $dados_banco['titular']
            );

            $this->instalador->update_installer_account($dados_instalador, $installer_id);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                echo json_encode(
                    array(
                        'status' => 'error',
                        'message' => 'Falha ao atualizar a conta bancária.'
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'status' => 'success',
                        'message' => 'Conta editada com sucesso!'
                    )
                );
            }
        } catch (Exception $e) {
            log_message('error', 'Erro ao processar a requisição: ' . $e->getMessage());
            echo json_encode(
                array(
                    'status' => 'error',
                    'message' => 'Erro ao processar a requisição: ' . $e->getMessage()
                )
            );
        }
    }



    public function updateContaByCpfConta()
    {
        $params = array('cpf' => $_GET['cpf'], 'installer_id' => $_GET['installer_id']);

        $result = $this->instalador->update_bank_account_cpf_and_id_installer();

        echo json_encode($result);
    }

    public function add2()
    {
        $valores = $this->instalador->get_valores();
        $dados['valores'] = $valores;

        $this->lang->load('en', 'english');
        $nome = 'Show Tecnologia - Installers';
        $host = "show.technology";
        $pais = "USA";

        $d = date("d", mktime());
        $m = date("m", mktime());
        $y = date("Y", mktime());
        $data = date("Y-m-d H:i:s", mktime(0, 0, 0, $m, $d - 1, $y));
        $where = "rastreamento.last_track.DATA <= '{$data}'";
        $desat = $this->instalador->get_with_last_track($where);
        $qtd_instaladores = $this->instalador->get_total_instaladores();
        $dados['qtd_instaladores'] = $qtd_instaladores;
        $dados['desatualizados'] = count($desat);
        $dados['host'] = $host;
        $dados['pais'] = $pais;

        if ($this->input->post()) {
            $dados = $this->input->post();
            $dados['data_criacao'] = date('Y-m-d H:i:s');
            $dados['senha'] = md5($dados['senha']);
            $dados['rsenha'] = md5($dados['rsenha']);
            $tel = array('(', ')', ' ');
            $cpf = array('.', '-');
            $dados['telefone'] = str_replace($tel, '', $dados['telefone']);
            $dados['celular'] = str_replace($tel, '', $dados['celular']);
            $dados['cpf'] = str_replace($cpf, '', $dados['cpf']);
            $dados['valor_instalacao'] = str_replace(',', '.', $dados['valor_instalacao']);
            $dados['valor_retirada'] = str_replace(',', '.', $dados['valor_retirada']);
            $dados['valor_manutencao'] = str_replace(',', '.', $dados['valor_manutencao']);
            $dados['valor_desloc_km'] = str_replace(',', '.', $dados['valor_desloc_km']);
            $retorno = $this->instalador->add($dados);
            $dados['retorno'] = $retorno;
            $dados['block'] = false;
        } else {
            $dados['retorno'] = false;
            $dados['block'] = true;
        }

        $dados['titulo'] = $nome;
        $this->load->view('instaladores/signup', $dados);
    }

    public function update_serv()
    {
        $this->load->model('ordem_servico');
        $data['id'] = $this->input->post('id');
        $this->ordem_servico->updateService($data);
    }

    public function saveOP()
    {
        $data['id_os'] = $this->input->post('id_os');
        $this->instalador->salvarOP($data);
    }

    public function cadServiceOp()
    {
        $this->load->model('ordem_servico');
        if ($this->input->post()) {
            $data['id_os'] = $this->input->post('id_os');
            //$data['id_op'] = $this->input->post('id_op');
            $data['servico'] = $this->input->post('servico');
            $data['cliente'] = $this->input->post('cliente');
            $data['placa'] = $this->input->post('placa');
            $data['serial'] = $this->input->post('serial');
            $data['user'] = $this->input->post('user');
            $data['solicitante'] = $this->input->post('solicitante');
            $data['data'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('data'))));
            $data['cod_rastreamento'] = $this->input->post('cod_rastreamento');
            $data['cod_autorizacao'] = (int) $this->input->post('cod_autorizacao');
            $data['valor'] = $this->input->post('valor');
            $data['id_instalador'] = (int) $this->input->post('id_instalador');
            $data['id_contas'] = (int) $this->input->post('id_contas');
            //pr($data);die;
            $this->ordem_servico->saveServiceOp($data);
        }
    }

    public function viewEdit()
    {
        if (isset($_GET['id']))
            $id = $_GET['id'];
        else
            $id = false;
        $data['contas'] = array();

        if ($id)
            $data['contas'] = $this->instalador->get_contasById($id);
        $data['titulo'] = 'Editar Dados';
        $this->load->view('fix/header4', $data);
        $this->load->view('instaladores/edit');
        $this->load->view('fix/footer4');
    }

    public function update_conta($id, $tec)
    {
        $dados = $this->input->post();
        $update = $this->instalador->update_conta($dados, $id);

        if ($update)
            $this->session->set_flashdata('sucesso', 'Conta editada com sucesso!');
        else
            $this->session->set_flashdata('erro', 'Não foi possível atualizar a conta.');

        redirect(site_url('instaladores/viewEdit') . '?id=' . $tec);
    }

    public function upgrade_contaById()
    {
        $id = $_POST['id'];
        $conta = $this->instalador->get_contaById($id);
        $contas = $this->instalador->get_contasById($conta->id_retorno);

        $cs = array();
        foreach ($contas as $conta) {
            if ($conta->status != '2')
                $cs[] = $conta->id;
        }

        $this->instalador->upgrade_contabank($id, $cs);
    }

    public function cadastrar_conta($id)
    {
        $dados = $this->input->post();
        $insert = $this->instalador->add_contaBank($dados, $id);

        if ($insert)
            $this->session->set_flashdata('sucesso', 'Conta cadastrada com sucesso!');
        else
            $this->session->set_flashdata('erro', 'Não foi possível cadastrar a conta.');

        redirect(site_url('instaladores/viewEdit') . '?id=' . $id);
    }

    public function editar_install()
    {
        $dataInstall = $this->instalador->get_dataInstalador($_GET['id']);
        echo json_encode($dataInstall);
    }

    function InstaladorListSelect()
    {
        $like = NULL;
        if ($search = $this->input->get('q'))
            $like = array('nome' => $search);

        echo json_encode(array('results' => $this->instalador->InstaladorListSelect(array(), 0, 10, 'nome', 'asc', 'nome as text, id', $like)));
    }

    public function saveUpdate()
    {
        $dados['id'] = $this->input->post('id');
        $dados = $this->input->post();
        $dados['valor_instalacao'] = str_replace(',', '.', $dados['valor_instalacao']);
        $dados['valor_retirada'] = str_replace(',', '.', $dados['valor_retirada']);
        $dados['valor_manutencao'] = str_replace(',', '.', $dados['valor_manutencao']);
        $dados['valor_desloc_km'] = str_replace(',', '.', $dados['valor_desloc_km']);
        $dados['pis'] = str_replace('.', '', $dados['pis']);
        $dados['pis'] = str_replace('-', '', $dados['pis']);
        $dataNascimento = DateTime::createFromFormat('d/m/Y', $dados['data_nascimento']);
        $dados['data_nascimento'] = $dataNascimento->format('Y-m-d H:i:s');

        $this->instalador->updateData($dados);
    }


    public function getOP()
    {
        $dadosOp = $this->instalador->getListOp();
        echo json_encode($dadosOp);
    }

    public function verifica_senha()
    {
        $this->load->model('fatura');
        $senha = md5($_POST['senha']);

        if ($senha == $this->fatura->senhaExclusaoFatura2())
            echo json_encode(array('status' => 'OK'));
        else
            echo json_encode(array('status' => 'ERRO'));
    }

    public function getOs()
    {
        $this->load->model('ordem_servico');
        $this->load->model('veiculo');
        $this->load->model('cliente');
        $this->load->model('equipamento');
        $this->load->model('usuario_gestor');

        $data = array();
        $data['id'] = $_GET['id'];
        $dados_os = $this->ordem_servico->getListOs($data);
        //pr($dados_os);die;
        $c = 0;
        if ($dados_os != null) {
            foreach ($dados_os as $key => $v) {
                $valor = '0,00';
                $dataFornecedor = $this->instalador->get_dataInstalador($v->id_instalador);
                $dados_bank = $this->instalador->get_primaryBank($v->id_instalador);
                $name_instalador = $dataFornecedor[0]->nome . ' ' . $dataFornecedor[0]->sobrenome;
                if (strlen($dados_bank->cpf) > 14) {
                    $cpf_titular = false;
                    $cnpj_titular = $dados_bank->cpf;
                } else {
                    $cpf_titular = $dados_bank->cpf;
                    $cnpj_titular = false;
                }
                $conta_instalador = $dados_bank->conta;
                $banco_instalador = $dados_bank->banco;
                $agencia_instalador = $dados_bank->agencia;
                $tipo_servico = $v->tipo_os == 1 ? 'Instalação' : ($v->tipo_os == 2 ? 'Manutenção' : ($v->tipo_os == 3 ? 'Troca' : 'Retirada'));

                // VALOR DO SERVIÇO POR INSTALAÇÃO
                if ($tipo_servico == 'Instalação')
                    $valor = number_format($dataFornecedor[0]->valor_instalacao, 2, ",", ".");
                elseif ($tipo_servico == 'Manutenção')
                    $valor = number_format($dataFornecedor[0]->valor_manutencao, 2, ",", ".");
                elseif ($tipo_servico == 'Retirada')
                    $valor = number_format($dataFornecedor[0]->valor_retirada, 2, ",", ".");
                else
                    $valor = number_format($dataFornecedor[0]->valor_manutencao, 2, ",", ".");

                $titular_conta = $dataFornecedor[0]->titular_conta;
                $cliente = $this->cliente->get_nameClient($v->id_cliente);
                $dataUsuario = $this->usuario_gestor->get_nameUser($v->id_usuario);
                $dateSolic = dh_for_humans($v->data_solicitacao, false, false);
                $dataFechamento = date('Y-m-d', strtotime($v->data_solicitacao));
                $usuario = isset($dataUsuario) ? $dataUsuario[0]->usuario : null;
                $cor = number_format($valor, 2, '.', '') > number_format($v->valor_mensal, 2, '.', '') ? 'red' : 'green';
                $data[] = array(
                    'id' => $v->id,
                    'serviço' => $tipo_servico,
                    'cliente' => $cliente,
                    'placa' => $v->placa,
                    'serial' => $v->serial,
                    'usuário' => $usuario,
                    'solicitante' => $v->solicitante,
                    'data' =>
                        "<input type='date' id='date$c' name='data' value='$dataFechamento' class='span2 input-table control-group' required style='background: whitesmoke; border: none; border-bottom: 1px solid #499bea; border-radius: 0px;'/>",
                    'valor' =>
                        "<input type='text' id='valor$c' name='valor' data-value='$v->valor_mensal' value='$valor' class='span1 value input-table control-group' required style='background: whitesmoke; border: none; border-bottom: 1px solid #499bea; border-radius: 0px; color: $cor !important;'/>",
                    'rastreamento' =>
                        "<input type='text' id='rast$c' name='rastreamento' value='$v->rastreio' class='span2 input-table control-group' style='background: whitesmoke; border: none; border-bottom: 1px solid #499bea; border-radius: 0px;'/>",
                    'autorização' =>
                        "<input type='text' id='autoriz$c' name='autorizacao' value='0' class='span1 input-table control-group' style='background: #f5f5f5; border: none; border-bottom: 1px solid #499bea; border-radius: 0px;'/>",
                    'limite' =>
                        "<input type='text' value='$v->valor_mensal' class='span1 value input-table control-group' required style='background: whitesmoke; border: none; border-bottom: 1px solid #499bea; border-radius: 0px; color: red;' disabled='disabled'/>",
                    'selecionar' =>
                        "<input type='checkbox' id='checkOrdem' name='checkOrdem[]' class='control-group'
                data-valor='valor$c'   
                data-date='date$c'   
                data-rastr='rast$c'   
                data-autoriz='autoriz$c'   
                data-servico='$tipo_servico'
                data-cliente='$cliente' 
                data-id='$v->id'       
                data-placa='$v->placa'
                data-serial='$v->serial'                     
                data-user='$usuario'                      
                data-solicitante='$v->solicitante'    
                data-cadastro='$dateSolic'
                data-fornecedor='$name_instalador'  
                data-titular='$titular_conta' 
                data-cpfTitular='$cpf_titular'
                data-cnpjTitular='$cnpj_titular'
                data-conta='$conta_instalador'
                data-bank='$banco_instalador'
                data-agencia='$agencia_instalador'
                data-weekly='$v->weekly'
                />"
                );
                $c++;
            }
        } else {
            $data[] = array(
                'id' => '',
                'serviço' => '',
                'cliente' => '',
                'placa' => '',
                'serial' => '',
                'usuário' => '',
                'solicitante' => 'NENHUMA O.S. CADASTRADA PARA ESSE INSTALADOR',
                'data' => '',
                'valor' => '',
                'rastreamento' => '',
                'autorização' => '',
                'selecionar' => ''
            );
        }

        echo json_encode($data);
    }

    public function getOsAgGrid()
    {
        $this->load->model('ordem_servico');
        $this->load->model('veiculo');
        $this->load->model('cliente');
        $this->load->model('equipamento');
        $this->load->model('usuario_gestor');

        $data = array();
        $parametros['id'] = $_GET['id'];
        $dados_os = $this->ordem_servico->getListOs($parametros);

        $c = 0;
        if ($dados_os != null) {
            foreach ($dados_os as $key => $v) {
                $valor = '0,00';
                $dataFornecedor = $this->instalador->get_dataInstalador($v->id_instalador);
                $dados_bank = $this->instalador->get_primaryBank($v->id_instalador);
                $name_instalador = $dataFornecedor[0]->nome . ' ' . $dataFornecedor[0]->sobrenome;
                if (strlen($dados_bank->cpf) > 14) {
                    $cpf_titular = false;
                    $cnpj_titular = $dados_bank->cpf;
                } else {
                    $cpf_titular = $dados_bank->cpf;
                    $cnpj_titular = false;
                }
                $conta_instalador = $dados_bank->conta;
                $banco_instalador = $dados_bank->banco;
                $agencia_instalador = $dados_bank->agencia;
                $tipo_servico = $v->tipo_os == 1 ? 'Instalação' : ($v->tipo_os == 2 ? 'Manutenção' : ($v->tipo_os == 3 ? 'Troca' : 'Retirada'));

                // VALOR DO SERVIÇO POR INSTALAÇÃO
                if ($tipo_servico == 'Instalação')
                    $valor = number_format($dataFornecedor[0]->valor_instalacao, 2, ",", ".");
                elseif ($tipo_servico == 'Manutenção')
                    $valor = number_format($dataFornecedor[0]->valor_manutencao, 2, ",", ".");
                elseif ($tipo_servico == 'Retirada')
                    $valor = number_format($dataFornecedor[0]->valor_retirada, 2, ",", ".");
                else
                    $valor = number_format($dataFornecedor[0]->valor_manutencao, 2, ",", ".");

                $titular_conta = $dataFornecedor[0]->titular_conta;
                $cliente = $this->cliente->get_nameClient($v->id_cliente);
                $dataUsuario = $this->usuario_gestor->get_nameUser($v->id_usuario);
                $dateSolic = dh_for_humans($v->data_solicitacao, false, false);
                $dataFechamento = date('Y-m-d', strtotime($v->data_solicitacao));
                $usuario = isset($dataUsuario) ? $dataUsuario[0]->usuario : null;
                $cor = number_format($valor, 2, '.', '') > number_format($v->valor_mensal, 2, '.', '') ? 'red' : 'green';
                $data[] = array(
                    'id' => $v->id,
                    'cliente' => $cliente,
                    'placa' => $v->placa,
                    'serial' => $v->serial,
                    'usuario' => $usuario,
                    'solicitante' => $v->solicitante,
                    'data' => $dataFechamento,
                    'valorMensal' => $v->valor_mensal,
                    'value' => $valor,
                    'rastreamento' => $v->rastreio,
                    'servico' => $tipo_servico,
                    'cadastro' => $dateSolic,
                    'fornecedor' => $name_instalador,
                    'titular' => $titular_conta,
                    'cpfTitular' => $cpf_titular,
                    'cnpjTitular' => $cnpj_titular,
                    'conta' => $conta_instalador,
                    'bank' => $banco_instalador,
                    'agencia' => $agencia_instalador,
                    'weekly' => $v->weekly,
                    'autorizacao' => '0',
                    'identificador' => $v->id . '-' . $v->placa
                );
                $c++;
            }
        }

        echo json_encode(
            array(
                'dados' => $data
            )
        );
    }

    public function pagosAgGrid()
    {
        $this->load->model('veiculo');
        $this->load->model('cliente');
        $this->load->model('equipamento');
        $this->load->model('usuario_gestor');
        $data = array();
        $where = $_GET['id'];
        $data_service = $this->instalador->get_servicesPagos($where);

        if ($data_service) {
            foreach ($data_service as $key => $v) {
                $valor = number_format($v->valor, 2, ',', ' ');
                $data[] = array(
                    'os' => $v->id_os,
                    'id' => $v->id,
                    'id_op' => $v->id_op,
                    'servico' => $v->servico,
                    'cliente' => $v->cliente,
                    'placa' => $v->placa,
                    'serial' => $v->serial,
                    'usuario' => $v->user,
                    'solicitante' => $v->solicitante,
                    'data' => $v->data,
                    'rastreamento' => $v->cod_rastreamento,
                    'autorizacao' => $v->cod_autorizacao,
                    'valor' => $valor
                );
            }
        }

        echo json_encode(
            array(
                'dados' => $data
            )
        );

    }

    public function pagos_ajax()
    {
        $this->load->model('veiculo');
        $this->load->model('cliente');
        $this->load->model('equipamento');
        $this->load->model('usuario_gestor');
        $data = array();
        $where = $_GET['id'];
        $data_service = $this->instalador->get_servicesPagos($where);
        //pr($data_service);die;
        foreach ($data_service as $key => $v) {
            $valor = number_format($v->valor, 2, ',', ' ');
            $data[] = array(
                'os' => $v->id_os,
                'serviço' => $v->servico,
                'cliente' => $v->cliente,
                'placa' => $v->placa,
                'serial' => $v->serial,
                'usuário' => $v->user,
                'solicitante' => $v->solicitante,
                'data' => dh_for_humans($v->data, false, false),
                'rastreamento' => $v->cod_rastreamento,
                'autorização' => $v->cod_autorizacao,
                'valor' => $valor,
                'selecionar' =>
                    "<input type='checkbox' id='checkOrdem2' name='checkOrdem[]' class='control-group'
            data-valor='$valor'   
            data-date='$v->data'   
            data-rastr='$v->cod_rastreamento'   
            data-autoriz='$v->cod_autorizacao'   
            data-servico='$v->servico'
            data-cliente='$v->cliente' 
            data-id='$v->id'       
            data-id_op='$v->id_op'       
            data-id_os='$v->id_os'       
            data-placa='$v->placa'
            data-serial='$v->serial'                     
            data-user='$v->user'                      
            data-solicitante='$v->solicitante'                      
            />"
            );
        }
        //pr(json_encode($data));die;
        echo json_encode($data);
    }

    public function getAllInstaladores()
    {
        $this->load->model('ordem_servico');
        $data = array();
        $data_service = $this->instalador->getId_Instalador();
        $stars = null;
        foreach ($data_service as $key => $v) {
            if ($v->comp_end == '1' && $v->comp_cpf == '1' && $v->comp_rg == '1' && $v->comp_conta == '1') {
                $btn_class = 'btn-success';
            } elseif ($v->comp_end == '0' && $v->comp_cpf == '0' && $v->comp_rg == '0' && $v->comp_conta == '0') {
                $btn_class = 'btn-default';
            } else {
                $btn_class = 'btn-warning';
            }

            $dataRating = $this->ordem_servico->getRating($v->id);
            //pr($dataRating);die;

            if ($dataRating) {
                $nota = $dataRating[0]->nota;
                switch ($nota):
                    case $nota == 0:
                        $stars = "<i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
                        break;
                    case $nota >= 0.1 && $nota <= 0.9:
                        $stars = "<i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
                        break;
                    case $nota == 1:
                        $stars = "<i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
                        break;
                    case $nota >= 1.1 && $nota <= 1.9:
                        $stars = "<i class='star fa fa-star'></i><i class='star fa fa-star-half-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
                        break;
                    case $nota == 2:
                        $stars = "<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
                        break;
                    case $nota >= 2.1 && $nota <= 2.9:
                        $stars = "<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-half-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
                        break;
                    case $nota == 3:
                        $stars = "<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
                        break;
                    case $nota >= 3.1 && $nota <= 3.9:
                        $stars = "<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-half-o'></i><i class='star fa fa-star-o'></i>";
                        break;
                    case $nota == 4:
                        $stars = "<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-o'></i>";
                        break;
                    case $nota >= 4.1 && $nota <= 4.9:
                        $stars = "<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star-half-o'></i>";
                        break;
                    case $nota == 5:
                        $stars = "<i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i><i class='star fa fa-star'></i>";
                        break;
                    default:
                        $stars = "<i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i><i class='star fa fa-star-o'></i>";
                endswitch;
            }
            //
            //            if ($dataRating) { $nota = $dataRating[0]->nota;
            //                if ($nota == 5){ $stars = "<i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i>"; }
            //                elseif ($nota >=4 && $nota <= 4.9){ $stars = "<i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star-o'></i>";}
            //                elseif ($nota >= 3 && $nota <= 3.9){ $stars = "<i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i>";}
            //                elseif ($nota >= 2 && $nota <= 2.9){ $stars = "<i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i>";}
            //                elseif ($nota >= 0.1 && $nota <= 0.5){ $stars = "<i class='fa fa-star'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i>";}
            //                elseif ($nota == null) { $stars = "<i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i>"; }
            //            } else {
            //                $stars = ' '; }

            $logar = "<a href='#' id='data' data-email='$v->email' data-senha='$v->senha' class='btn btn-primary btn-mini logar' title='Logar na conta do Usuário'><i class='icon-off icon-white'></i></a>";

            $dataRow = $this->ordem_servico->getCountOs($v->id);
            if ($v->block == 1) {
                $classBlock = 'btn-danger';
                $iconBlock = 'fa fa-ban';
            } else {
                $classBlock = 'btn-success';
                $iconBlock = 'fa fa-check-circle';
            }
            ;
            $numRowsOS = count($dataRow);
            $linkInst = "<a href='ordens_pagamento?id=$v->id'>$v->nome $v->sobrenome</a>";
            $linkEdit = "<a href='viewEdit?id=$v->id'><button class='btn btn-mini btn-primary'><i class='fa fa-edit'></i></button></a>";
            $linkComprov = "<button data-toggle='modal' href=" . site_url('contas/view_comprovantes/' . $v->id) . " data-conta='$v->id'
    				class='btn btn-mini dropdown-toggle $btn_class'
    				title='Digitalizar Documentos' data-target='#myModal_digitalizar'> 
    				<i class='fa fa-cloud-upload'></i> 
    				</button>";
            $linkBlock = "<button id='blockTec' data-id='$v->id' data-block='$v->block' class='btn btn-mini $classBlock'><i class='$iconBlock'></i></button>";
            $vInst = number_format($v->valor_instalacao, 2, ',', ' ');
            $vMan = number_format($v->valor_manutencao, 2, ',', ' ');
            $vRet = number_format($v->valor_retirada, 2, ',', ' ');
            $vDesl = number_format($v->valor_desloc_km, 2, ',', ' ');
            if ($this->input->get('pendente') && $numRowsOS) {
                $data[] = array(
                    'id' => "<span class='badge badge-inverse'>$v->id</span>",
                    'nota' => $stars,
                    'nome' => $linkInst,
                    'cidade' => $v->cidade,
                    'estado' => $v->estado,
                    'telefone' => $v->telefone,
                    'celular' => $v->celular,
                    'email' => $v->email,
                    'qtdDeOs' => "<span class='badge badge-warning'>$numRowsOS à Pagar</span>",
                    'valorInstalação' => "<span class='badge badge-info'>R$ $vInst</span>",
                    'valorManutenção' => "<span class='badge badge-info'>R$ $vMan</span>",
                    'valorRetirada' => "<span class='badge badge-info'>R$ $vRet</span>",
                    'valorDeslocamento' => "<span class='badge badge-info'>R$ $vDesl</span>",
                    'comprovantes' => $linkComprov,
                    'editar' => $linkEdit,
                    'bloqueio' => $linkBlock,
                    'acesso' => $logar,
                );
            } elseif (!$this->input->get('pendente')) {
                $data[] = array(
                    'id' => "<span class='badge badge-inverse'>$v->id</span>",
                    'nota' => $stars,
                    'nome' => $linkInst,
                    'cidade' => $v->cidade,
                    'estado' => $v->estado,
                    'telefone' => $v->telefone,
                    'celular' => $v->celular,
                    'email' => $v->email,
                    'qtdDeOs' => "<span class='badge badge-warning'>$numRowsOS à Pagar</span>",
                    'valorInstalação' => "<span class='badge badge-info'>R$ $vInst</span>",
                    'valorManutenção' => "<span class='badge badge-info'>R$ $vMan</span>",
                    'valorRetirada' => "<span class='badge badge-info'>R$ $vRet</span>",
                    'valorDeslocamento' => "<span class='badge badge-info'>R$ $vDesl</span>",
                    'comprovantes' => $linkComprov,
                    'editar' => $linkEdit,
                    'bloqueio' => $linkBlock,
                    'acesso' => $logar,
                );
            }
        }
        //pr(json_encode($data));die;
        echo json_encode($data);
    }

    public function getAllInstaladoresServerSide()
    {
        $startRow = $this->input->post('startRow', TRUE) ?: 0;
        $endRow = $this->input->post('endRow', TRUE) ?: 10;

        $searchOptionsRaw = $this->input->post('searchOptions', TRUE);
        $searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

        $searchFilters = [];
        if (isset($searchOptions['statusPagamento']) && $searchOptions['statusPagamento'] !== '') {
            $searchFilters['status_pg'] = (int) $searchOptions['statusPagamento'];
        }
        if (isset($searchOptions['nomeCompleto']) && $searchOptions['nomeCompleto'] !== '') {
            $searchFilters['nomeCompleto'] = $searchOptions['nomeCompleto'];
        }
        if (isset($searchOptions['cidade']) && $searchOptions['cidade'] !== '') {
            $searchFilters['cidade'] = $searchOptions['cidade'];
        }
        if (isset($searchOptions['estado']) && $searchOptions['estado'] !== '') {
            $searchFilters['estado'] = $searchOptions['estado'];
        }

        $data_service = $this->instalador->get_all_installer_details($startRow, $endRow, $searchFilters);
        $data = array_map(function ($v) {
            return [
                'id' => $v->id,
                'nota' => $v->avgNota ?: 0,
                'nome' => $v->nome . ' ' . $v->sobrenome,
                'email' => $v->email,
                'senha' => $v->senha,
                'cidade' => $v->cidade,
                'estado' => $v->estado,
                'telefone' => $v->telefone,
                'celular' => $v->celular,
                'email' => $v->email,
                'block' => $v->block,
                'qtdDeOs' => $v->qtdDeOs,
                'valorInstalacao' => $v->valor_instalacao,
                'valorManutencao' => $v->valor_manutencao,
                'valorRetirada' => $v->valor_retirada,
                'valorDeslocamento' => $v->valor_desloc_km,
            ];
        }, $data_service);

        $total_count = $this->instalador->get_all_installer_count($searchFilters);

        if ($total_count == 0) {
            $response = [
                'success' => false,
                'message' => "Dados não encontrados para os parâmetros informados.",
                'lastRow' => (int) $total_count
            ];
            echo json_encode($response);

        } else {
            $response = [
                'success' => true,
                'rows' => $data,
                'lastRow' => (int) $total_count
            ];

            echo json_encode($response);
        }

    }

    public function getAllInstaladorNames()
    {
        $installerName = $this->input->post('installerName', TRUE) ?: null;

        $data_service = $this->instalador->get_all_installer_names($installerName);
        $data = array_map(function ($v) {
            return [
                'id' => $v->id,
                'nome' => $v->nome . ' ' . $v->sobrenome,
            ];
        }, $data_service);

        echo json_encode($data);
    }

    public function getInstallerStealthAccess()
    {
        $installerId = (int) $this->input->post('installerId', TRUE) ?: null;
        $data_service = $this->instalador->get_all_installer_login($installerId);

        echo json_encode($data_service);
    }

    public function blockTec()
    {
        $data['id'] = $this->input->post('id');
        $data['block'] = $this->input->post('block');
        $this->instalador->bloquearTec($data);
    }

    public function getDocumentosInstalador()
	{
		$startRow = (int) $this->input->post('startRow');
		$endRow = (int) $this->input->post('endRow');

		$startRow++;

        $searchOptionsRaw = $this->input->post('searchOptions', TRUE);
        $searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

        $installerId = isset($searchOptions['installerId']) ? (int) $searchOptions['installerId'] : null;
        
		$response['status'] = '404';

		if ($installerId != "Não informado") {
			$response = getDocumentosPaginado($startRow, $endRow, $installerId);
		}

		if ($response['status'] == '200') {
			echo json_encode(
				array(
					"success" => true,
					"rows" => $response['resultado']['documentos'],
					"lastRow" => $response['resultado']['qntRetornos']
				)
			);
		} else if ($response['status'] == '404') {
			echo json_encode(
				array(
					"success" => false,
					"message" => $response['resultado']['mensagem'],
				)
			);
		} else {
			echo json_encode(
				array(
					"success" => false,
					"message" => $response['resultado']['mensagem'],
				)
			);
		}
	}

    public function insertDocument()
	{
		$installerId = $this->input->post('installerId');
		$nomeDocumento = $this->input->post('nomeDocumento');
		$documento = $this->input->post('documento');

		$data = array(
			"idInstalador" => $installerId,
			"nomeArquivo" => $nomeDocumento,
			"arquivoBase64" => $documento
		);

		$retorno = insertDocumentoInstaladorRoute($data);

        if ($retorno['status'] == '201') {
			echo json_encode(
				array(
					"success" => true,
					"message" => "doc_cadastrado"
				)
			);
		} else if ($retorno['status'] == '400' && isset($retorno['resultado']["mensagem"]) && $retorno['resultado']["mensagem"] == "Já existe um arquivo como mesmo nome para o funcionario.") {
			echo json_encode(
				array(
					"success" => false,
					"message" => "doc_existente"
				)
			);
		} else {
			echo json_encode(
				array(
					"success" => false,
					"message" => $retorno['resultado']['mensagem'],
				)
			);
		}
	}

    public function updateDocument()
	{
		$idDocumento = $this->input->post('idDocumento');
		$nomeDocumento = $this->input->post('nomeDocumento');
		$documento = $this->input->post('documento');
        
        if(!$nomeDocumento && !$documento){
            echo json_encode(
				array(
					"success" => false,
					"message" => "Insira algum dado para fazer a edição"
				)
			);
            return;
        }

		$data = array(
			"idDocumento" => $idDocumento
		);

        if($nomeDocumento) {
            $data['nomeArquivo'] = $nomeDocumento;
        }

        if($documento) {
            $data['documento'] = $documento;
        }

		$retorno = atualizarDocumentoInstaladorRoute($data);

        if ($retorno['status'] == '200') {
			echo json_encode(
				array(
					"success" => true,
					"message" => "doc_cadastrado"
				)
			);
		} else if ($retorno['status'] == '400' && isset($retorno['resultado']["mensagem"]) && $retorno['resultado']["mensagem"] == "Já existe um arquivo como mesmo nome para o funcionario.") {
			echo json_encode(
				array(
					"success" => false,
					"message" => "doc_existente"
				)
			);
		} else {
			echo json_encode(
				array(
					"success" => false,
					"message" => $retorno['resultado']['mensagem'],
				)
			);
		}
	}

    public function getDocumentoServerSide()
	{
		$documentId = $this->input->post('documentId');

		$response = getDocumentoByIdRoute($documentId);

		if ($response['status'] == '200') {
			echo json_encode($response['resultado']);
		} else if ($response['status'] == '404') {
			echo json_encode(
				array(
					"success" => false,
					"message" => $response['resultado']['mensagem'],
				)
			);
		} else {
			echo json_encode(
				array(
					"success" => false,
					"message" => $response['resultado']['mensagem'],
				)
			);
		}
	}

    public function getServiceOrderValuesServerSide()
	{
		$installerId = $this->input->post('id');

		$response = getAllServiceOrderValuesServerSideRoute($installerId);

		if ($response['status'] == '200') {
			echo json_encode($response['resultado']);
		} else if ($response['status'] == '404') {
			echo json_encode(
				array(
					"success" => false,
					"message" => $response['resultado']['mensagem'],
				)
			);
		} else {
			echo json_encode(
				array(
					"success" => false,
					"message" => $response['resultado']['mensagem'],
				)
			);
		}
	}

    public function getAllServiceOrderServerSide()
    {
        $startRow = $this->input->post('startRow', TRUE) ?: 0;
        $endRow = $this->input->post('endRow', TRUE) ?: 10;

        $searchOptionsRaw = $this->input->post('searchOptions', TRUE);
        $searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

        $searchFilters = [];
        if (isset($searchOptions['statusPagamento']) && $searchOptions['statusPagamento'] !== '') {
            $searchFilters['status_pg'] = (int) $searchOptions['statusPagamento'];
        }
        if (isset($searchOptions['nome']) && $searchOptions['nome'] !== '') {
            $searchFilters['nome'] = $searchOptions['nome'];
        }
        if (isset($searchOptions['sobrenome']) && $searchOptions['sobrenome'] !== '') {
            $searchFilters['sobrenome'] = $searchOptions['sobrenome'];
        }
        if (isset($searchOptions['cidade']) && $searchOptions['cidade'] !== '') {
            $searchFilters['cidade'] = $searchOptions['cidade'];
        }
        if (isset($searchOptions['estado']) && $searchOptions['estado'] !== '') {
            $searchFilters['estado'] = $searchOptions['estado'];
        }

        $data_service = $this->instalador->get_all_installer_details($startRow, $endRow, $searchFilters);
        $data = array_map(function ($v) {
            return [
                'id' => $v->id,
                'nota' => $v->avgNota ?: 0,
                'nome' => $v->nome . ' ' . $v->sobrenome,
                'email' => $v->email,
                'senha' => $v->senha,
                'cidade' => $v->cidade,
                'estado' => $v->estado,
                'telefone' => $v->telefone,
                'celular' => $v->celular,
                'email' => $v->email,
                'block' => $v->block,
                'qtdDeOs' => $v->qtdDeOs,
                'valorInstalacao' => $v->valor_instalacao,
                'valorManutencao' => $v->valor_manutencao,
                'valorRetirada' => $v->valor_retirada,
                'valorDeslocamento' => $v->valor_desloc_km,
            ];
        }, $data_service);

        $total_count = $this->instalador->get_all_installer_count($searchFilters);

        if($total_count == 0){
            $response = [
                'success' => false,
                'message' => "Dados não encontrados para os parâmetros informados.",
                'lastRow' => (int) $total_count
            ];
            echo json_encode($response);

        }else{
            $response = [
                'success' => true,
                'rows' => $data,
                'lastRow' => (int) $total_count
            ];
    
            echo json_encode($response);
        }

    }

    public function getAllServiceOrderServerSideNew()
    {
        $startRow = (int) $this->input->post('startRow');
		$endRow = (int) $this->input->post('endRow');

		$startRow++;

        $searchOptionsRaw = $this->input->post('searchOptions', TRUE);
        $searchOptions = $searchOptionsRaw ? json_decode($searchOptionsRaw, true) : [];

        $installerId = isset($searchOptions['id']) ? (int) $searchOptions['id'] : null;

        $retorno = getAllServiceOrderServerSideRoute($startRow, $endRow, $installerId);

        if($retorno['status'] == 200){
            $response = [
                'success' => true,
                'rows' => $retorno['resultado']['rows'],
                'lastRow' => $retorno['resultado']['lastRow']
            ];
            echo json_encode($response);

        }else{
            $response = [
                'success' => false,
                'message' => "Dados não encontrados para os parâmetros informados."
            ];

            echo json_encode($response);
        }

    }
}
