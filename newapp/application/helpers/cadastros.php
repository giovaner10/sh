<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cadastros extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('fatura');
        $this->load->model('contrato');
        $this->load->model('cliente');
        $this->load->model('cadastro');
    }

    // public function teste_cliente()
    // {		
    // 	$cliente = $this->input->post('cliente');
    // 	//Cliente
    // 	echo "<h3>--- CLIENTE ---</h3>";
    // 	echo "Pessoa: ".$cliente['pessoa'].'</br>';
    // 	echo "Nome: ".$cliente['nome'].'</br>';
    // 	echo "Rg: ".$cliente['rg'].'</br>';
    // 	echo "Orgao expeditor: ".$cliente['rg_orgao'].'</br>';
    // 	echo "Cpf: ".$cliente['cpf'].'</br>';
    // 	echo "Razao social: ".$cliente['razao_social'].'</br>';
    // 	echo "CNPJ: ".$cliente['cnpj'].'</br>';
    // 	echo "Incricao estadual: ".$cliente['ie'].'</br>';
    // 	echo "Informacoes: ".$cliente['informacoes'].'</br>';
    // 	//Cartão
    // 	echo "<h3>--- CARTAO ---</h3>";
    // 	$cartao = $this->input->post('cartao');
    // 	$quant_cartao = count($cartao);
    // 	for ($i = 0; $i < $quant_cartao; $i++){
    // 		echo "Id Cartao: ".$cartao[$i]['id'].'</br>';
    // 		echo "Status: ".$cartao[$i]['status'].'</br>';
    // 		echo "Numero: ".$cartao[$i]['numero'].'</br>';
    // 		echo "Vencimento: ".$cartao[$i]['vencimento'].'</br>';
    // 		echo "Codigo: ".$cartao[$i]['codigo'].'</br>';
    // 		echo "Nome: ".$cartao[$i]['nome'].'</br>';
    // 	}
    // 	//Endereço
    // 	echo "<h3>--- ENDERECO ---</h3>";
    // 	$endereco = $this->input->post('endereco');
    // 	$quant_endereco = count($endereco);
    // 	for ($i = 0; $i < $quant_endereco; $i++){
    // 		echo "Status: ".$endereco[$i]['status'].'</br>';
    // 		echo "Latitude: ".$endereco[$i]['latitude'].'</br>';
    // 		echo "Longitude: ".$endereco[$i]['longitude'].'</br>';
    // 		echo "CEP: ".$endereco[$i]['cep'].'</br>';
    // 		echo "Rua: ".$endereco[$i]['rua'].'</br>';
    // 		echo "Numero: ".$endereco[$i]['numero'].'</br>';
    // 		echo "Bairro: ".$endereco[$i]['bairro'].'</br>';
    // 		echo "Uf: ".$endereco[$i]['uf'].'</br>';
    // 		echo "Cidade: ".$endereco[$i]['cidade'].'</br>';
    // 		echo "Complemento: ".$endereco[$i]['complemento'].'</br>';
    // 	}
    // 	//Contatos - Email
    // 	echo "<h3>--- EMAIL ---</h3>";
    // 	$email = $this->input->post('email');
    // 	$quant_email = count($email);
    // 	for ($i = 0; $i < $quant_email; $i++){
    // 		echo "Status: ".$email[$i]['status'].'</br>';
    // 		echo "Email: ".$email[$i]['emails'].'</br>';
    // 		echo "Setor: ".$email[$i]['setor'].'</br>';
    // 		echo "Observacao: ".$email[$i]['observacao'].'</br>';
    // 	}
    // 	//Contatos - Telefone
    // 	echo "<h3>--- TELEFONE ---</h3>";
    // 	$telefone = $this->input->post('telefone');
    // 	$quant_telefone = count($telefone);
    // 	for ($i = 0; $i < $quant_telefone; $i++){
    // 		echo "Status: ".$telefone[$i]['status'].'</br>';
    // 		echo "DDD: ".$telefone[$i]['ddd'].'</br>';
    // 		echo "Numero: ".$telefone[$i]['numero'].'</br>';
    // 		echo "Setor: ".$telefone[$i]['setor'].'</br>';
    // 		echo "Observacao: ".$telefone[$i]['observacao'].'</br>';
    // 	}
    // }

    public function cadastrar_cliente() {

        $cliente = $this->input->post('cliente');
        $cartao = $this->input->post('cartao');
        $endereco = $this->input->post('endereco');
        $email = $this->input->post('email');
        $telefone = $this->input->post('telefone');

        //Cliente
        $cliente_pessoa = $cliente['pessoa'];
        $cliente_informacoes = $cliente['informacoes'];

        //Cartão
        $ocorrencia_numero = 0;
        $ocorrencia_bandeira = 0;
        $ocorrencia_vencimento = 0;
        $ocorrencia_codigo = 0;
        $ocorrencia_nome = 0;

        //Endereço
        $ocorrencia_cep = 0;
        $ocorrencia_rua = 0;
        $ocorrencia_numero_end = 0;
        $ocorrencia_bairro = 0;
        $ocorrencia_uf = 0;
        $ocorrencia_cidade = 0;
        $ocorrencia_uf = 0;

        //Email
        $ocorrencia_email = 0;
        $ocorrencia_email_setor = 0;

        //Telefone
        $ocorrencia_ddd = 0;
        $ocorrencia_numero_tel = 0;

        $mensagem = "";

        if ($cliente['nome']) {
            $cliente_nome = $cliente['nome'];
        } else {
            if ($mensagem != "") {
                $mensagem = $mensagem . ", Nome em Dados";
            } else {
                $mensagem = "Nome em Dados";
            }
        }

        if ($cliente_pessoa == 1) {

            if ($cliente['rg']) {
                $cliente_rg = $cliente['rg'];
            } else {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Número Identidade em Dados";
                } else {
                    $mensagem = "Número Identidade em Dados";
                }
            }

            if ($cliente['rg_orgao']) {
                $cliente_rg_orgao = $cliente['rg_orgao'];
            } else {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Orgão Expeditor em Dados";
                } else {
                    $mensagem = "Orgão Expeditor em Dados";
                }
            }

            if ($cliente['cpf']) {
                $cliente_cpf = $cliente['cpf'];
            } else {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Cpf em Dados";
                } else {
                    $mensagem = "Cpf em Dados";
                }
            }

            //Cartão
            $quant_cartao = count($cartao);

            for ($i = 0; $i < $quant_cartao; $i++) {

                if ($cartao[$i]['numero']) {
                    $cartao_numero = $cartao[$i]['numero'];
                } else if ($ocorrencia_numero == 0) {
                    if ($mensagem != "") {
                        $mensagem = $mensagem . ", Número em Cartões";
                        $ocorrencia_numero = $ocorrencia_numero + 1;
                    } else {
                        $mensagem = "Número em Cartões";
                        $ocorrencia_numero = $ocorrencia_numero + 1;
                    }
                }

                if ($cartao[$i]['bandeira']) {
                    $cartao_bandeira = $cartao[$i]['bandeira'];
                } else if ($ocorrencia_bandeira == 0) {
                    if ($mensagem != "") {
                        $mensagem = $mensagem . ", Bandeira em Cartões";
                        $ocorrencia_bandeira = $ocorrencia_bandeira + 1;
                    } else {
                        $mensagem = "Bandeira em Cartões";
                        $ocorrencia_bandeira = $ocorrencia_bandeira + 1;
                    }
                }

                if ($cartao[$i]['vencimento']) {
                    $cartao_vencimento = $cartao[$i]['vencimento'];
                } else if ($ocorrencia_vencimento == 0) {
                    if ($mensagem != "") {
                        $mensagem = $mensagem . ", Vencimento em Cartões";
                        $ocorrencia_vencimento = $ocorrencia_vencimento + 1;
                    } else {
                        $mensagem = "Vencimento em Cartões";
                        $ocorrencia_vencimento = $ocorrencia_vencimento + 1;
                    }
                }

                if ($cartao[$i]['codigo']) {
                    $cartao_codigo = $cartao[$i]['codigo'];
                } else if ($ocorrencia_codigo == 0) {
                    if ($mensagem != "") {
                        $mensagem = $mensagem . ", Código em Cartões";
                        $ocorrencia_codigo = $ocorrencia_codigo + 1;
                    } else {
                        $mensagem = "Código em Cartões";
                        $ocorrencia_codigo = $ocorrencia_codigo + 1;
                    }
                }


                if ($cartao[$i]['nome']) {
                    $cartao_nome = $cartao[$i]['nome'];
                } else if ($ocorrencia_nome == 0) {
                    if ($mensagem != "") {
                        $mensagem = $mensagem . ", Nome em Cartões";
                        $ocorrencia_nome = $ocorrencia_nome + 1;
                    } else {
                        $mensagem = "Nome em Cartões";
                        $ocorrencia_nome = $ocorrencia_nome + 1;
                    }
                }
            }
        } else {

            if ($cliente['razao_social']) {
                $cliente_razao_social = $cliente['razao_social'];
            } else {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Razão Social em Dados";
                } else {
                    $mensagem = "Razão Social em Dados";
                }
            }

            if ($cliente['cnpj']) {
                $cliente_cnpj = $cliente['cnpj'];
            } else {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Cnpj em Dados";
                } else {
                    $mensagem = "Cnpj em Dados";
                }
            }

            $cliente_ie = $cliente['ie'];

            //Cartão
            $quant_cartao = count($cartao);

            for ($i = 0; $i < $quant_cartao; $i++) {

                $cartao_numero = $cartao[$i]['numero'];
                $cartao_bandeira = $cartao[$i]['bandeira'];
                $cartao_vencimento = $cartao[$i]['vencimento'];
                $cartao_codigo = $cartao[$i]['codigo'];
                $cartao_nome = $cartao[$i]['nome'];
            }
        }


        //Endereço
        $quant_endereco = count($endereco);

        for ($i = 0; $i < $quant_endereco; $i++) {

            $endereco_latitude = $endereco[$i]['latitude'];
            $endereco_longitude = $endereco[$i]['longitude'];
            $endereco_complemento = $endereco[$i]['complemento'];

            if ($endereco[$i]['cep']) {
                $endereco_cep = $endereco[$i]['cep'];
            } else if ($ocorrencia_cep == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Cep em Endereços";
                    $ocorrencia_cep = $ocorrencia_cep + 1;
                } else {
                    $mensagem = "Cep em Endereços";
                    $ocorrencia_cep = $ocorrencia_cep + 1;
                }
            }

            if ($endereco[$i]['rua']) {
                $endereco_rua = $endereco[$i]['rua'];
            } else if ($ocorrencia_rua == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Rua em Endereços";
                    $ocorrencia_rua = $ocorrencia_rua + 1;
                } else {
                    $mensagem = "Rua em Endereços";
                    $ocorrencia_rua = $ocorrencia_rua + 1;
                }
            }

            if ($endereco[$i]['numero']) {
                $endereco_numero = $endereco[$i]['numero'];
            } else if ($ocorrencia_numero_end == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Número em Endereços";
                    $ocorrencia_numero_end = $ocorrencia_numero_end + 1;
                } else {
                    $mensagem = "Número em Endereços";
                    $ocorrencia_numero_end = $ocorrencia_numero_end + 1;
                }
            }

            if ($endereco[$i]['bairro']) {
                $endereco_bairro = $endereco[$i]['bairro'];
            } else if ($ocorrencia_bairro == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Bairro em Endereços";
                    $ocorrencia_bairro = $ocorrencia_bairro + 1;
                } else {
                    $mensagem = "Bairro em Endereços";
                    $ocorrencia_bairro = $ocorrencia_bairro + 1;
                }
            }

            if ($endereco[$i]['uf'] != "") {
                $endereco_uf = $endereco[$i]['uf'];
            } else if ($ocorrencia_uf == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Uf em Endereços";
                    $ocorrencia_uf = $ocorrencia_uf + 1;
                } else {
                    $mensagem = "Uf em Endereços";
                    $ocorrencia_uf = $ocorrencia_uf + 1;
                }
            }

            if ($endereco[$i]['cidade']) {
                $endereco_cidade = $endereco[$i]['cidade'];
            } else if ($ocorrencia_cidade == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Cidade em Endereços";
                    $ocorrencia_cidade = $ocorrencia_cidade + 1;
                } else {
                    $mensagem = "Cidade em Endereços";
                    $ocorrencia_cidade = $ocorrencia_cidade + 1;
                }
            }
        }

        //Contatos - Email
        $quant_email = count($email);

        for ($i = 0; $i < $quant_email; $i++) {

            $email_observacao = $email[$i]['observacao'];
            $email_setor = $email[$i]['setor'];

            //if ($email[$i]['setor']) {
            //    $email_setor = $email[$i]['setor'];
            //} else if ($ocorrencia_email_setor == 0) {
            //    if ($mensagem != "") {
            //        $mensagem = $mensagem . ", Setor do E-mail em Contatos";
            //        $ocorrencia_email_setor = $ocorrencia_email_setor + 1;
            //    } else {
            //        $mensagem = "Setor do E-mail em Contatos";
            //        $ocorrencia_email_setor = $ocorrencia_email_setor + 1;
            //    }
           // }

            if ($email[$i]['emails']) {
                $email_email = $email[$i]['emails'];
            } else if ($ocorrencia_email == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", E-mail em Contatos";
                    $ocorrencia_email = $ocorrencia_email + 1;
                } else {
                    $mensagem = "E-mail em Contatos";
                    $ocorrencia_email = $ocorrencia_email + 1;
                }
            }
        }

        //Contatos - Telefone
        $quant_telefone = count($telefone);

        for ($i = 0; $i < $quant_telefone; $i++) {

            $telefone_setor = $telefone[$i]['setor'];
            $telefone_observacao = $telefone[$i]['observacao'];


            if ($telefone[$i]['ddd']) {
                $telefone_telefone = $telefone[$i]['ddd'];
            } else if ($ocorrencia_ddd == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", DDD em Contatos";
                    $ocorrencia_ddd = $ocorrencia_ddd + 1;
                } else {
                    $mensagem = "DDD em Contatos";
                    $ocorrencia_ddd = $ocorrencia_ddd + 1;
                }
            }

            if ($telefone[$i]['numero']) {
                $telefone_numero = $telefone[$i]['numero'];
            } else if ($ocorrencia_numero_tel == 0) {
                if ($mensagem != "") {
                    $mensagem = $mensagem . ", Telefone em Contatos";
                    $ocorrencia_numero_tel = $ocorrencia_numero_tel + 1;
                } else {
                    $mensagem = "Telefone em Contatos";
                    $ocorrencia_numero_tel = $ocorrencia_numero_tel + 1;
                }
            }
        }


        if ($mensagem != "") {
            die(json_encode(array('mensagem' => '<div class="alert alert-error"><p>Faltou Informar: <b>' . $mensagem . '</b></p></div>')));
        } else {
            $retorno = $this->cadastro->cadastrar_cliente($cliente, $cartao, $endereco, $email, $telefone);
            if ($retorno['status'] == 1) {
                die(json_encode(array('mensagem' => '<div class="alert alert-error"><p><b>Cliente já cadastrado com o nome: ' . $retorno['nome_cliente'] . '</b></p></div>')));
            } else if ($retorno['status'] == 2) {
                die(json_encode(array('mensagem' => '<div class="alert alert-sucess"><p>Cliente <b>' . $retorno['nome_cliente'] . '</b> cadastrado com sucesso</p></div>')));
            } else {
                die(json_encode(array('mensagem' => '<div class="alert alert-error"><p><b>Cliente não cadastrado</b></p></div>')));
            }
        }
    }

    public function atualizar_cliente($cliente_id) {

        $cliente = $this->input->post('cliente');
        $cartao = $this->input->post('cartao');
        $endereco = $this->input->post('endereco');
        $email = $this->input->post('email');
        $telefone = $this->input->post('telefone');

        $retorno = $this->cadastro->atualizar_cliente($cliente_id, $cliente, $cartao, $endereco, $email, $telefone);

        if ($retorno['status'] == 1) {
            die(json_encode(array('mensagem' => '<div class="alert alert-sucess"><p>Dados do cliente <b>' . $retorno['nome_cliente'] . '</b> atualizados com sucesso</p></div>')));
        } else {
            die(json_encode(array('mensagem' => '<div class="alert alert-error"><p>Dados do cliente <b>' . $retorno['nome_cliente'] . '</b> não atualizados</p></div>')));
        }
    }

    /* Chamada da tela de veiculos
     * Os cliente nesta lista será do SHOW NET
     * Os Veiculos sarão do gestor, tendo como base o CNPJ do cadastro SHOW NET
     * 
     * A paginação não funcionou ao utilizar uma sub-pasta "cadastro"
     * 
     * Luciano - 27/07/2014
     * 
     */

    public function veiculos($page = 0) {

        $pesquisa = array();

        if ($this->input->get()) {
            $pesquisa = array($this->input->get('coluna') => $this->input->get('palavra'));
            $config['per_page'] = 99999;
        } else {
            $pesquisa = array();
            $config['per_page'] = 15;
        }

        $config['base_url'] = site_url('cadastros/veiculos/');
        //$config['total_rows'] = $this->cliente->get_total_veiculos_gestor($pesquisa); Fixado até encontrar solução definitiva
        $config['total_rows'] = 99999;

        $this->pagination->initialize($config);

        $dados['all_clientes'] = $this->cliente->listar(array(), 0, 99999);
        $j_clientes = array();
        if (count($dados['all_clientes']) > 0) {
            foreach ($dados['all_clientes'] as $cli) {
                $j_clientes[] = $cli->nome;
            }
        }

        $dados['j_clientes'] = json_encode($j_clientes);

        $dados['clientes'] = $this->cliente->get_veiculos_gestor($pesquisa, $page, $config['per_page'], 'nome,placa');

        $dados['titulo'] = 'Show Tecnologia';

        $this->load->view('fix/header', $dados);
        $this->load->view('veiculos/cadastro');
        $this->load->view('fix/footer');
    }

    /* Chamada da tela de enviar as linha para mikrotik
     * Luciano - 27/07/2014
     */

    public function linhas() {

        $dados['msg'] = '';
        $dados['titulo'] = 'Cadastro de linhas';
        $this->load->view('fix/header', $dados);
        $this->load->view('servicos/cad_linhas');
        $this->load->view('fix/footer');
    }

}
