<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Representantes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('representante');
        $this->load->model('mapa_calor');
        $this->load->library('upload');
    }

    public function index(){
        redirect(site_url('representantes/entrar'));
    }

    public function entrar(){
        if($this->input->post()){
            $rules = array(array('field' => 'login',
                                    'label' => 'Email',
                                    'rules' => 'required|valid_email|trim'),
                            array('field' => 'senha',
                                    'label' => 'Senha',
                                    'rules' => 'required|trim|md5'));

            $this->form_validation->set_rules($rules);       
            if(!$this->form_validation->run()){ 
                $dados['erro'] = validation_errors('<p>','</p>');  
            }else{
                if($this->auth->logar_representante($this->input->post('login'), $this->input->post('senha'))){
                    redirect(site_url('home/representante'));
                
                }else{
                    $dados['erro'] = 'Usuário ou senha invalidos.';
                }
            }  
        }

        $dados['titulo'] = 'Acesso Restrito - SHOWNET';
        $this->lang->load('pt', 'portuguese');
        $this->load->view('new_views/fix/login', $dados);    
    }

    public function entrar2(){
        if($this->input->post()){
            $rules = array(array('field' => 'login',
                                    'label' => 'Email',
                                    'rules' => 'required|valid_email|trim'),
                            array('field' => 'senha',
                                    'label' => 'Senha',
                                    'rules' => 'required|trim|md5'));

            $this->form_validation->set_rules($rules);       
            if(!$this->form_validation->run()){ 
                $dados['erro'] = validation_errors('<p>','</p>');  
            }else{
                if($this->auth->logar_representante($this->input->post('login'), $this->input->post('senha'))){
                    redirect(site_url('home/representante2'));
                
                }else{
                    $dados['erro'] = 'Username or password is invalid.';
                }
            }  
        }

        $dados['titulo'] = 'Restricted access - SHOWNET';
        $this->lang->load('en', 'english');
        $this->load->view('new_views/fix/login', $dados);
        
    }

    public function sair($area = 'representante'){ 
        $this->auth->logout($area);
        redirect(site_url('home/representante'));

        
    }

    public function get_cidades() {
        $sigla = $this->input->post('sigla');
        $pais = $this->input->post('sigla2');

        $cidades = $this->representante->get_cidades($sigla,$pais);
        exit(json_encode($cidades));
    }

    public function listar_representantes() {
        $this->auth->is_allowed_block('cad_representantes');
        $this->mapa_calor->registrar_acessos_url(site_url('/representantes/listar_representantes'));

		$para_view['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$para_view['titulo'] = lang('representantes');

		$this->load->view('new_views/fix/header', $para_view);
		$this->load->view('representantes/listar_new');
		$this->load->view('fix/footer_NS');
	}

    public function get_representantes_ajax() {
		$startRow = (int) $this->input->post('startRow');
		$endRow = (int) $this->input->post('endRow');
		$coluna = $this->input->post('coluna');
		$valor = $this->input->post('valor');

		$limit = $endRow - $startRow;
		$offset = $startRow;

		if ($valor) {
			$pesquisa = array(
				"coluna" => $coluna,
				"palavra" => $valor
			);
			$result =  $this->representante->get_pesquisa_representantes($limit, $offset, $pesquisa);
		} else {
			$result =  $this->representante->get_pesquisa_representantes($limit, $offset);
		}

		if ($result) {
			if ($result["success"]) {
				echo json_encode(
					array(
						"statusCode" => 200,
						"success" => true,
						"rows" => $result['rows'],
						"lastRow" => $result['lastRow'],
					)
				);
			} else {
				echo json_encode(
					array(
						"statusCode" => 404,
						"success" => false,
						"rows" => $result['rows'],
						"lastRow" => $result['lastRow'],
						"message" => 'Não foi possível encontrar representantes.'
					)
				);
			}
		} else {
			echo json_encode(
				array(
					"statusCode" => 500,
					"success" => false,
					"message" => 'Erro ao realizar a listagem.'
				)
			);
		}
		
	}

    public function get_informacoes_representante($id) {
        try {
            $representante = $this->representante->get_representante($id);
            if ($representante && count($representante)) {
                $representante = $representante[0];

                $dados = array(
                    [
                        'coluna' => "Nome Completo",
                        'valor' => $representante->nome . " " . ($representante->sobrenome ? $representante->sobrenome : "")
                    ],
                    [
                        'coluna' => "CPF/CSS",
                        'valor' => $representante->cpf
                    ],
                    [
                        'coluna' => "País",
                        'valor' => $representante->pais ? ($representante->pais == "BRA" ? "BRASIL" : ($representante->pais == "USA" ? "ESTADOS UNIDOS" : $representante->pais)) : ""
                    ],
                    [
                        'coluna' => "Endereço",
                        'valor' => ($representante->endereco ? $representante->endereco : '') . ($representante->numero ? ', nº '.$representante->numero : '') . ($representante->bairro ? ', '.$representante->bairro : '') . ($representante->cidade ? ', '.$representante->cidade: '') . ($representante->estado ? ' - '.$representante->estado : '')
                    ],
                    [
                        'coluna' => "CEP",
                        'valor' => $representante->cep
                    ],
                    [
                        'coluna' => "Banco",
                        'valor' => $representante->banco
                    ],
                    [
                        'coluna' => "Agência",
                        'valor' => $representante->agencia
                    ],
                    [
                        'coluna' => "Conta",
                        'valor' => $representante->conta
                    ],
                    [
                        'coluna' => "Telefone",
                        'valor' => $representante->telefone
                    ],
                    [
                        'coluna' => "Celular",
                        'valor' => $representante->celular
                    ],
                    [
                        'coluna' => "E-mail",
                        'valor' => $representante->email
                    ],
                    [
                        'coluna' => "E-mail Show",
                        'valor' => $representante->emailshow
                    ]
                );
                echo json_encode(
                    array(
                        "statusCode" => 200,
                        "message" => 'Dados do representante encontrados!',
                        "dados" => $dados
                    )
                );
            } else {
                echo json_encode(
                    array(
                        "statusCode" => 404,
                        "message" => 'Não foi possível encontrar os dados do representante!',
                    )
                );
            }
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "statusCode" => 500,
                    "message" => 'Erro ao buscar os dados do representante.'
                )
            );
        }
    }

    public function get_arquivos($id) {
        try {
            $arquivos = $this->representante->get_files($id);

            if ($arquivos && count($arquivos) > 0) {
                echo json_encode(
                    array(
                        "statusCode" => 200,
                        "message" => 'Arquivos encontrados.',
                        "dados" => $arquivos
                    )
                );
            } else {
                echo json_encode(
                    array(
                        "statusCode" => 404,
                        "message" => 'Sem arquivos para exibir.',
                        "dados" => []
                    )
                );
            }
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "statusCode" => 500,
                    "message" => 'Erro ao buscar arquivos digitalizados.'
                )
            );
        }

    }

    public function post_arquivo() {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $representante = $this->input->post('id_representante_anexo');
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false; 

        if ($descricao == "") {
            die(json_encode(array('success' => false, 'message' => 'Informe descrição!')));
        } else {
            if ($arquivo) {
                if ($dados = $this->upload()) {
                    $nome_arquivo = $dados['file_name'];
                    $arquivo_enviado = true;
                    
                }
                if($arquivo_enviado) {
                    $retorno = $this->representante->digitalizacao($representante, $descricao, $nome_arquivo);
                    die(json_encode(array('success' => true, 'message' => 'Arquivo enviado com sucesso!', 'registro' => $retorno)));
                } else {
                    die(json_encode(array('success' => false, 'message' => 'Processo não realizado!')));
                }
            } else {
                die(json_encode(array('success' => false, 'message' => 'Processo não realizado!')));
            }       
        }
    }

    public function get_email_representante($id){   
        $linhas = $this->representante->get_representante($id);

        try {
            if ($linhas && count($linhas) > 0) {
                foreach ($linhas as $linha) {
                    $emailshow = $linha->emailshow;
                    
                }
    
                echo json_encode(
                    array(
                        "statusCode" => 200,
                        "email" => $emailshow
                    )
                );
            } else {
                echo json_encode(
                    array(
                        "statusCode" => 404,
                        "message" => 'Dados não encontrados para o representante informado.'
                    )
                );
            }

        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "statusCode" => 500,
                    "message" => 'Erro ao buscar os dados do representante.'
                )
            );
        }
    }
    
    public function editar_email_show() {
        try {
            $dados['id'] = $this->input->post('idRepresentante');
            $dados['emailshow'] = $this->input->post('emailShow');
            
            $retorno = $this->representante->atualizar($dados);
            if($retorno) 
                echo json_encode(
                    array(
                        "statusCode" => 200,
                        "message" => 'E-mail Show editado com sucesso.'
                    )
                );
            else
                echo json_encode(
                    array(
                        "statusCode" => 404,
                        "message" => 'Não foi possível editar o E-mail Show do representante.'
                    )
                );
        } catch (\Exception $e) {
            echo json_encode(
                array(
                    "statusCode" => 500,
                    "message" => 'Não foi possível editar o E-mail Show do representante.'
                )
            );
        }
    }

    public function listar_representantes_old($page = 0) {   
        $this->load->library('pagination');
        $this->auth->is_logged('admin');
        if($this->input->get()){      
            $para_view['representantes'] =  $this->representante->listar_pesquisa_representantes($this->input->get());
        }else{
            $config['base_url'] = site_url('representantes/listar_representantes');
            $config['total_rows'] = $this->representante->get_total_representantes();
            $config['per_page'] = 40;

            $this->pagination->initialize($config);

            $para_view['representantes'] = $this->representante->get_representantes($page, $config['per_page']);
        }

        $para_view['titulo'] = 'Show Tecnologia';
        $this->mapa_calor->registrar_acessos_url(site_url('/representantes/listar_representantes'));
        $this->load->view('fix/header', $para_view);
        $this->load->view('representantes/listar');
        $this->load->view('fix/footer');
        
    }

    public function add() {
        
        $this->lang->load('pt', 'portuguese');
        $nome = 'Show Tecnologia - Representantes';
        $host = "showtecnologia";
        $pais = "BRA";
        
        $d = date("d",mktime());
        $m = date("m",mktime());
        $y = date("Y",mktime());
        $data = date ("Y-m-d H:i:s",mktime (0,0,0,$m,$d-1,$y));
        $qtd_representantes = $this->representante->get_total_representantes();
        $dados['qtd_representantes'] = $qtd_representantes;
        $dados['host'] = $host;
        $dados['pais'] = $pais;

        if($this->input->post()) {
            $dados = $this->input->post();
            $dados['data_criacao'] = date('Y-m-d H:i:s');
            $dados['senha'] = md5($dados['senha']);
            $dados['rsenha'] = md5($dados['rsenha']);
            $tel = array('(', ')', ' ');
            $cpf = array('.', '-');
            $dados['telefone'] = str_replace($tel, '', $dados['telefone']);
            $dados['celular'] = str_replace($tel, '', $dados['celular']);
            $dados['cpf'] = str_replace($cpf, '', $dados['cpf']);
            $retorno = $this->representante->add($dados);
            $dados['retorno'] = $retorno;
            $dados['block'] = false;

            //----------------- Envia email de confirmação de cadastro --------------------//
            if ($retorno) {
                $this->load->model('sender');
                $send_email = $this->sender->enviar_email($dados['email'], $dados['nome'], 'fabiana@showtecnologia.com',
                    'Novo Cadastro de Representante', 'Nome :'.$dados['nome'].'<br/>Tel.: '.$dados['celular'],
                    $cc = false, $bcc_emails = false, $anexo = false);
            }
            // ----------------------- Fim Envia Email ------------------------------------//

        }else{
                $dados['retorno'] = false;
                $dados['block'] = true;
        }

        $dados['titulo'] = $nome;
        $this->load->view('representantes/signup', $dados);
    }

    public function add2() {

        $this->lang->load('en', 'english');
        $nome = 'Show Technology - Representatives';
        $host = "show.technology";
        $pais = "USA";
       
        $d = date("d",mktime());
        $m = date("m",mktime());
        $y = date("Y",mktime());
        $data = date ("Y-m-d H:i:s",mktime (0,0,0,$m,$d-1,$y));
        $qtd_representantes = $this->representante->get_total_representantes();
        $dados['qtd_representantes'] = $qtd_representantes;
        $dados['host'] = $host;
        $dados['pais'] = $pais;

        if($this->input->post()) {
            $dados = $this->input->post();
            $dados['data_criacao'] = date('Y-m-d H:i:s');
            $dados['senha'] = md5($dados['senha']);
            $dados['rsenha'] = md5($dados['rsenha']);
            $tel = array('(', ')', ' ');
            $cpf = array('.', '-');
            $dados['telefone'] = str_replace($tel, '', $dados['telefone']);
            $dados['celular'] = str_replace($tel, '', $dados['celular']);
            $dados['cpf'] = str_replace($cpf, '', $dados['cpf']);
            $retorno = $this->representante->add($dados);
            $dados['retorno'] = $retorno;
            $dados['block'] = false;
        }else{
            $dados['retorno'] = false;
            $dados['block'] = true;
        }
        
        $dados['titulo'] = $nome;
        $this->load->view('representantes/signup', $dados);
    }


    public function digitalizar($id) {
        $data['arquivos'] = $this->representante->get_files($id);
        $data['id_representante'] = $id;

        $this->load->view('representantes/digitalizar', $data);
    }


    public function view_file($path) {  
        redirect(base_url().'uploads/representantes/'.$path);
    }


    public function digitalizacao($representante_id) {
        $nome_arquivo = "";
        $descricao = $this->input->post('descricao');
        $representante = $representante_id;
        $arquivo = isset($_FILES) ? $_FILES['arquivo']['name'] : false; 

        if ($descricao == "") {
            die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Informe descrição!</div>')));
        }else{

            if ($arquivo) {
                if ($dados = $this->upload()) {
                    $nome_arquivo = $dados['file_name'];
                    $arquivo_enviado = true;
                    
                }
                if($arquivo_enviado) {
                    $retorno = $this->representante->digitalizacao($representante, $descricao, $nome_arquivo);
                    die(json_encode(array('success' => true, 'mensagem' => '<div class="alert alert-success">Processo realizado com sucesso!</div>', 'registro' => $retorno)));
                }else{
                    die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
                }
            }else{
                die(json_encode(array('success' => false, 'mensagem' => '<div class="alert alert-error">Processo não realizado!</div>')));
            }       
        }
    }


    private function upload() {
        $config['upload_path'] = './uploads/representantes';
        $config['allowed_types'] = 'pdf|jpg|png|jpeg|gif';
        $config['max_size'] = '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $config['encrypt_name']  = 'true';
        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('arquivo')) {
            $data = $this->upload->data();
            return $data;  
        }else{  
            $error = array('error' => $this->upload->display_errors());
            return false;
        }
    }

    public function infos($id) {
        $data['representantes'] = $this->representante->get_representante($id);

        $this->load->view('representantes/info', $data);
    }
    

    public function editar($id){   
        $linhas = $this->representante->get_representante($id);

        if (count($linhas) > 0) {
            foreach ($linhas as $linha) {
                $emailshow = $linha->emailshow;
                
            }
        }
        
        $dados['id'] = $id;
        $dados['emailshow'] = $emailshow;
        $this->load->view('representantes/editar', $dados);
    }


    public function editanto_email($id){
        $dados['id'] = $id;
        $dados['emailshow'] = $this->input->post('descricao');
        
        $retorno = $this->representante->atualizar($dados);
            if($retorno) 
                $dados['retorno'] = true;
            else
                $dados['retorno'] = false;
        
        $page = 0;
        $config['base_url'] = site_url('representantes/listar_representantes');
        $config['total_rows'] = $this->representante->get_total_representantes();
        $config['per_page'] = 40;

        $this->pagination->initialize($config);

        $para_view['representantes'] = $this->representante->get_representantes($page, $config['per_page']);
        $para_view['titulo'] = 'Show Tecnologia';

        $this->load->view('fix/header', $para_view);
        $this->load->view('representantes/listar');
        $this->load->view('fix/footer');
        
    }

    public function envia_email_cadastro() {
        if($this->input->post()) {
            $dados = $this->input->post();
            $this->load->model('sender');
            $send_email = $this->sender->enviar_email($dados['email'], $dados['name'], 'saulomendes25@hotmail.com',
            'Novo Cadastro de Representante - Solicitação de Contato', 'Nome :'.$dados['name'].'<br/>Tel.: '.$dados['tel'].'<br/>End.: '.$dados['end'].'<br/>Obs.: '.$dados['obs'].'Região de Interesse: '.$dados['cidade'], $cc = false, $bcc_emails = false, $anexo = false);

            if ($send_email) {
                echo '
                    <script>
                    alert("Enviado com Sucesso!");
                    location.href="add";
                    </script>       
                ';
            } else {
                echo '<script>
                alert("ERRO, o email não foi enviado!");
                location.href="add";
                </script>';
            }

        } else {
            echo '<script>
                alert("Favor verifique os dados e tente novamente!");
                location.href="add";
                </script>';
        }
        
    }

}