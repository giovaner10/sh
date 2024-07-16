<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vendas extends CI_Controller {

	public function __construct(){
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('fatura');
        $this->load->model('contrato');
        $this->load->model('cliente');
        $this->load->model('cadastro');
        $this->load->model('veiculo');
        $this->load->model('equipamento');
        $this->load->model('usuario_gestor');
        $this->load->model('venda');	
	}
	
	public function index(){
		redirect('http://wwww.showtecnologia.com/404.html');
	}
	
    public function create_key() {
        $key = $this->input->post();
        $key['instalacao_veiculo'] = str_replace(',', '.', $key['instalacao_veiculo']);
        $key['mensal_veiculo'] = str_replace(',', '.', $key['mensal_veiculo']);
        $key['chave'] = generateRandomString();
        $return = $this->venda->create_key($key);
    }

    public function is_valid_key() {
        $c = cors();
        $return = $this->venda->is_valid_key($this->input->post('chave'));
        if($return)
            exit(json_encode($return));
        else
            exit("false");
    }

    public function get_cities() {
        $c = cors();
        $sigla = $this->input->post('sigla');
        $cidades = $this->venda->get_cities($sigla);
        exit(json_encode($cidades));
    }

	public function add() {
        $c = cors();
        $cliente = $this->input->post('cliente');
        $cartao = $this->input->post('cartao');
        $endereco = $this->input->post('endereco');
        $email = $this->input->post('email');
        $telefone = $this->input->post('telefone');
       
        // identifica que veio do formulario do site
        $cliente_site = $this->input->post('site');

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

            $cliente_razao_social = $cliente['razao_social'];
            $cliente_cnpj = $cliente['cnpj'];

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

            $telefone_setor = '545645';
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
            die(json_encode(array('mensagem' => '<div class="alert alert-danger"><p>Faltou Informar: <b>' . $mensagem . '</b></p></div>')));
        } else {

            $retorno = $this->cadastro->cadastrar_cliente($cliente,$cliente_site, $cartao, 
                    $endereco, $email, $telefone);
            
            if ($retorno['status'] == 1) {
                die(json_encode(array('mensagem' => '<div class="alert alert-danger"><p>Cliente já cadastrado com o nome: <br>' . $retorno['nome_cliente'] . '</b></p></div>')));
            } else if ($retorno['status'] == 2) {
                $contrato = $this->input->post('contrato');
                $valor_prestacao = 0;
                $multa_valor = 0;

                $dados = array(
                    "id_cliente" => $retorno['id_cliente'],
                    "id_vendedor" => $contrato['vendedor'],
                    "tipo_proposta" => $contrato['tipo'],
                    "quantidade_veiculos" => $contrato['numeros_veiculos'],
                    "meses" => $contrato['meses_contrato'],
                    "valor_mensal" => $contrato['mensal_por_veiculo'],
                    "valor_instalacao" => $contrato['instalacao_por_veiculo'],
                    "boleto" => 1,
                    "prestacoes" => 0,
                    "valor_prestacao" => 0,
                    "vencimento" => $contrato['dia_vencimento'],
                    "data_contrato" => date('Y-m-d H:i:s'),
                    "status" => 0,
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "multa" => $contrato['multa_contrato'],
                    "multa_valor" => str_replace(',', '.', $multa_valor)
                );

                $dados['data_contrato'] = date('Y-m-d H:i:s');

                $contrat = $this->contrato->cadastrar_contrato($dados);

                die(json_encode(array('mensagem' => '<div class="alert alert-success"><p>Cliente <b>' . $retorno['nome_cliente'] . '</b> cadastrado com sucesso</p></div>')));
            } else {
                die(json_encode(array('mensagem' => '<div class="alert alert-danger"><p><b>Cliente não cadastrado</b></p></div>')));
            }
        }
    }
}
