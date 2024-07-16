<?php
error_reporting(0);
date_default_timezone_set("America/Recife");

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Acesso extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		//CARREGA OS ARQUIVOS DE LINGUAGEM
		$this->loadLanguague();
	}

	public function index()
	{
		redirect(site_url('acesso/entrar'));
	}

	public function entrar() {
		$uri_redirect = $_SESSION['uri_redirect'];

		$logged =  $this->isLogged_php();
		if ($logged) {
			redirect(site_url('Homes'));
		} 
		else {
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

					$email = $this->input->post('login');
					$data = date('Y-m-d');
					$horario_login = date('H:i:s');

					if ($this->auth->tempo_logado($email, $data)) {

						if ($this->auth->verifica_status_login($this->input->post('login'), $this->input->post('senha'))) {

							$retorno = $this->auth->verifica_status_login($this->input->post('login'), $this->input->post('senha'));

							if ($retorno['status'] == "1" && $retorno['data_retorno_ferias'] > $data) { //férias
								$dados['erro'] = "Você está de férias! <br> Acesso ao sistema temporáriamente bloqueado.";
							} elseif ($retorno['status'] == "1" && $retorno['data_retorno_ferias'] < $data) {

								$dadosFerias = array(
									'status_bloqueio' => '0',
									'data_saida_ferias' => '0000-00-00',
									'data_retorno_ferias' => '0000-00-00'
								);

								$this->db->update('showtecsystem.usuario', $dadosFerias, array('id' => $retorno['id']));

								$dados['success'] = 'Que bom que retornou! Favor efetue o login novamente!';
							}

							if ($retorno['status'] == "2") { //demitido
								$dados['erro'] = 'Desculpe, você não tem mais acesso ao sistema!';
							}
						} else {

							if ($this->auth->logar($this->input->post('login'), $this->input->post('senha'))) {
								$this->auth->cadastrar_sessao($email, $data, $horario_login);

								// // Loga na api do televendas
								// $this->auth->gerar_token_api_shownet();

								if (!empty($uri_redirect)) {
									unset($_SESSION['uri_redirect']);
									redirect(site_url($uri_redirect));
								}
								else {
									redirect(site_url('Homes'));
								}
							} 
							else {
								$dados['erro'] = 'Usuário ou senha invalidos.';
							}
						}
					} 
					else {
						$dados['erro'] = 'Tempo de expediente excedido.';
					}
				}
			}

			$dados['titulo'] = lang('acesso_restrito') . ' - ' . lang('show_tecnologia');
			$this->load->view('new_views/fix/login', $dados);
		}
	}

	/*
	* LOGIN AJAX
	*/
	public function entrarAjax()
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
				echo json_encode(array('success' => false, 'msg' => validation_errors('<p>', '</p>')));
			} else {
				$email = $this->input->post('login');
				$data = date('Y-m-d');
				$horario_login = date('H:i:s');

				if ($this->auth->tempo_logado($email, $data)) {

					if ($retorno = $this->auth->verifica_status_login($this->input->post('login'), $this->input->post('senha'))) {

						if ($retorno['status'] == "1") {
							if ($retorno['data_retorno_ferias'] > $data) { //férias
								echo json_encode(array('success' => false, 'msg' => lang('esta_em_ferias')));
							} else {
								$dadosFerias = array(
									'status_bloqueio' => '0',
									'data_saida_ferias' => '0000-00-00',
									'data_retorno_ferias' => '0000-00-00'
								);

								//ATUALIZA OS DADOS PARA 'VOLTOU A TRABALHAR'
								$this->db->update('showtecsystem.usuario', $dadosFerias, array('id' => $retorno['id']));

								//TENTAR LOGAR
								if ($this->auth->logar($this->input->post('login'), $this->input->post('senha'))) {
									$this->auth->cadastrar_sessao($email, $data, $horario_login);
									echo json_encode(array('success' => true, 'msg' => lang('login_sucesso')));
								} else {
									echo json_encode(array('success' => false, 'msg' => lang('usuario_senha_invalido')));
								}
							}
						} elseif ($retorno['status'] == "2") { //demitido
							echo json_encode(array('success' => false, 'msg' => lang('sem_acesso')));
						}
					} else {

						if ($this->auth->logar($this->input->post('login'), $this->input->post('senha'))) {
							$this->auth->cadastrar_sessao($email, $data, $horario_login);
							echo json_encode(array('success' => true, 'msg' => lang('login_sucesso')));
						} else {
							echo json_encode(array('success' => false, 'msg' => lang('usuario_senha_invalido')));
						}
					}
				} else {
					echo json_encode(array('success' => false, 'msg' => lang('expediente_excedido')));
				}
			}
		}
	}

	public function redefinirSenha()
	{
		$logged =  $this->isLogged_php();

		if ($logged) {
			redirect(site_url('Homes'));
		} else {

			if ($this->input->get() && !$this->input->post()) {
				$token = $this->input->get('token');
				$this->load->model('usuario');
				$usuario = $this->usuario->get("token_redefinicao = '$token'");

				if (count($usuario) == 1) {
                    $validade = $usuario->validade_token_redefinicao;
                    $horaAtual = date('Y-m-d h:i:s');

					if ($validade > $horaAtual) {
						$dados['token'] = $token;
						$dados['titulo'] = lang('acesso_restrito') . ' - ' . lang('redefinir_senha');
						$this->load->view('new_views/fix/redefinirSenha', $dados);
					} else {
						$dados['titulo'] = lang('acesso_restrito') . ' - ' . lang('show_tecnologia');
						$dados['erro'] = 'Token inválido';
						$this->load->view('new_views/fix/login', $dados);
					}
				}
			} else if ($this->input->post()) {
				$senha = $this->input->post('senha');
				$token = $this->input->post('token');

				$this->load->model('log_shownet');
				$this->load->model('usuario');
				$usuario = $this->usuario->get("token_redefinicao = '$token'");

				if (count($usuario) == 1) {
					if ($usuario->validade_token_redefinicao > date('Y-m-d h:i:s')) {
						$oldPassword = $usuario->senha;
						$this->usuario->atualizar($usuario->id, array(
							'senha' => $senha,
							'token_redefinicao' => null
						));

						$this->log_shownet->gravar_log(
							$usuario->id,
							'usuario',
							$usuario->id,
							2,
							'senha:' . md5($senha),
							'senha:' . $oldPassword
						);

						echo json_encode(array('resultado' => 'success'));
					} else {
						echo json_encode(array('resultado' => 'erro'));
					}
				}
			}
		}
	}
	public function senhaAleatoria($tamanho = 8)
	{

		$caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$quantidadeCaracteres = strlen($caracteres);
		$novaSenha = '';

		for ($i = 0; $i < $tamanho; $i++) {
			$novaSenha =  $novaSenha . $caracteres[rand(0, $quantidadeCaracteres - 1)];
		}

		return $novaSenha;
	}
    public function recuperarSenha()
    {
        $logged =  $this->isLogged_php();

        if ($logged) {
            redirect(site_url('Homes'));
        } else {
            if ($this->input->post()) {
                $email = $this->input->post('email');

                $this->load->model('usuario');
                $this->load->model('log_shownet');
                $this->load->model('sender');

                $usuario = $this->usuario->get("login = '$email'");

                if (count($usuario) > 1) {
                    $usuario = $usuario[0];
                }

                if ((count($usuario) == 1) && ($usuario->status == 1)) {
                    $senha = $this->senhaAleatoria(12);
                    $link = site_url('acesso/redefinirSenha') . "?token=" . md5($senha);

                    $this->usuario->atualizar($usuario->id, array(
                        'token_redefinicao' => md5($senha),
                        'validade_token_redefinicao' => date("Y-m-d h:i:s", strtotime("+120 minutes"))
                    ));

                    $POSTFIELDS = array(
                        'login' => $email,
                        'token' => md5($senha),
                        'url' => $link
                    );

                    $url = 'emails/criarSolicitacaoResetSenha';

                    $dados = $this->to_post($url, $POSTFIELDS);

                    if ($dados['status'] == 200) {
                        $this->log_shownet->gravar_log($usuario->id, 'usuario', $usuario->id, 2, 'senha:' . $usuario->senha, 'senha:');
                        echo json_encode(array("success" => "true", "message" => "Solicitação realizada com sucesso.", "status" => $dados['status']));
                    } else {
                        echo json_encode(array("success" => "false", "message" => $dados['resultado']['mensagem'], "status" => $dados['status']));

                    }

                } else {
                    echo json_encode(array('status' => 200, 'mensagem' => 'sucesso'));
                }
            } else {
                $this->load->view('new_views/fix/recuperarSenha');
            }
        }
    }
	/*
	* RETORNA ESTADO DE LOGIN DE UM USUARIO
	*/
	public function isLogged()
	{
		$area = $this->input->post('area');
		$login = $this->session->all_userdata();
		if (!isset($login['log_' . $area]) && !isset($login['log_' . $area]['logado'])) {
			$this->auth->salvar_hora();
			echo json_encode(array('logado' => false));
		} else {
			$this->auth->salvar_hora();
			echo json_encode(array('logado' => true));
		}
	}

	public function isLogged_php()
	{
		$area = 'admin';
		$login = $this->session->all_userdata();
		if (!isset($login['log_' . $area]) && !isset($login['log_' . $area]['logado'])) {
			$this->auth->salvar_hora();
			return false;
		} else {
			$this->auth->salvar_hora();
			return true;
		}
	}


	public function updateSenha()
	{
		if ($data = $this->input->post()) {
			if (isset($data['pass_atual']) && $data['pass_atual'] <> '' && isset($data['pass_nova']) && $data['pass_nova'] <> '') {
				$id_user = $this->auth->get_login('admin', 'user');
				if ($this->auth->updatePass($id_user, $data)) {
					$session = $this->auth->get_sessao('admin');
					$session['token'] = md5($data['pass_nova']);

					$this->auth->gerar_sessao($session);
					echo json_encode(array('status' => 'OK', 'msg' => 'Senha atualizada com sucesso.'));
				} else {
					echo json_encode(array('status' => 'ERROR', 'msg' => 'Verifique sua senha atual e tente novamente.'));
				}
			}
		} else {
			echo json_encode(array('status' => 'ERROR', 'msg' => 'Nenhum parâmetro enviado.'));
		}
	}

	public function sair($area)
	{

		// Remove token da api do televendas
		$this->auth->remover_token_api_shownet();
		$this->auth->salvar_hora();
		$this->auth->logout($area);
		
		// Remove a uri de redirecionamento da sessão
		unset($_SESSION['uri_redirect']);

		redirect(site_url('acesso/entrar'));
	}

	/*
	* PROCESSA O ARQUIVO DE LINGUAGEM E CONVERT PARA JSON
	*/
	public function loadLanguague()
	{

		//SETA O IDIOMA
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5);
		if ($lang === 'pt-BR') {
			//CARREGA OS ARQUIVO DO CI DE LINGUAGEM
			$this->lang->load('pt', 'portuguese');
			//CARREGA O ARQUIVO DE LINGUAGEM PARA SER CONVERTIDO EM JSON E GUARDADO NA SESSAO
			$file = 'application/language/portuguese/pt_lang.php';
		} else {
			//CARREGA OS ARQUIVO DO CI DE LINGUAGEM
			$this->lang->load('en', 'english');
			//CARREGA O ARQUIVO DE LINGUAGEM PARA SER CONVERTIDO EM JSON E GUARDADO NA SESSAO
			$file = 'application/language/english/en_lang.php';
		}

		//APARTIR DAQUI O ARQUIVO DE LINGUAGEM SERA CONVERTIDO EM JSON PARA POSSIBILITAR SEU USO EM ARQUIVOS JS
		$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$lang = array();
		foreach ($lines as $line_num => $line) {
			$linhaTemp = htmlspecialchars($line);

			if (is_numeric(strpos($linhaTemp, '$lang'))) {
				$chaves = array();
				$linha = explode('=', $linhaTemp);
				$valor = substr(htmlspecialchars($linha[1]), 2, -2);

				//PEGA AS CHAVES
				preg_match_all("/'([^']+)'/", $linha[0], $chaves);
				if (count($chaves) > 0) {
					$langLinha = $this->montarArrayDatatable(count($chaves[1]), $chaves[1], $valor);
					$lang = array_merge_recursive($lang, $langLinha);
				}
			}
		}
		//GUARDA NA SESSAO
		$this->session->set_userdata('lang', json_encode($lang));
	}

	//MONTA A MATRIZ DAS TRADUCOES DAS LINGUAGENS
	public function montarArrayDatatable($tamanho, $chaves, $valor)
	{
		$lang = array();
		switch ($tamanho) {
			case 1:
				$lang[$chaves[0]] = $valor;
				break;
			case 2:
				$lang[$chaves[0]][$chaves[1]] = $valor;
				break;
			case 3:
				$lang[$chaves[0]][$chaves[1]][$chaves[2]] = $valor;
				break;
			case 4:
				$lang[$chaves[0]][$chaves[1]][$chaves[3]][$chaves[4]] = $valor;
				break;
			default:
				break;
		}

		return $lang;
	}

	public function salvar_hora()
	{

		$this->auth->salvar_hora();
	}

	public function gerarTokenApiTelevendas()
	{
		$this->load->helper('api_televendas');
		$this->load->library('session');
		$token = '';
		$login = $this->auth->get_login_dados('email');
		$password = $this->auth->get_login_dados('senha');
		$resposta = json_decode(API_Televendas_Helper::login($login, $password));
		if (isset($resposta->token)) $token = $resposta->token;
		$sessao = $this->session->userdata('log_admin');
		$sessao['tokenApiTelevendas'] = $token;
		$this->session->set_userdata('log_admin', $sessao);

		echo json_encode(array('token' => $token));
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
