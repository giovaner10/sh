<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_gestor extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->model('sender');
		$this->load->model('cliente');
		$this->load->model('usuario_gestor');
		$this->load->model('auth');
		$this->load->helper('api_helper');
		$this->load->model('log_shownet');
		$this->load->helper('util_helper');
	}

	public function add($id_cliente) {
		if ($this->input->post()) {
			pr($_POST); die('antigo');
			$rules = array(array(
							'field' => 'usuario',
							'label' => 'Usuário',
							'rules' => 'required|valid_email'),
							array(
							'field' => 'senha',
							'label' => 'Senha',
							'rules' => 'required'),
							array(
							'field' => 'tipo_usuario',
							'label' => 'Função',
							'rules' => 'required')
						);
			$this->form_validation->set_rules($rules);
			if (!$this->form_validation->run()) {
				$this->session->set_flashdata('msg', validation_errors());
			} else {
				$data = array(
					'nome_usuario' => $this->input->post('nome_usuario'),
					'email_usuario' => $this->input->post('usuario'),
					'senha_usuario' => $this->input->post('senha'),
					'link_ativa' => site_url('cliente/usuarios/ativar/'.md5($this->input->post('nome_usuario').'-'.$this->input->post('senha')))
				);
				$emails = array($this->input->post('usuario'));
				$mensagem = $this->parser->parse('template/emails/new_user_gestor', $data, true);
				try {
					$this->usuario_gestor->add($this->input->post());
					$this->session->set_flashdata('msg', 'Usuário cadastrado com sucesso. Uma mensagem
												foi enviada para o email cadastrado com os dados de acesso.');
					$envio = $this->sender->enviar_email('suporte@showtecnologia.com', 'Show Tecnologia', $emails,
							'Dados de acesso Show Tecnologia', $mensagem);
				} catch (Exception $e) {
					$this->session->set_flashdata('msg', $e->getMessage());
				}
			}
			redirect(site_url('clientes/view/'.$id_cliente));
		}
		$dados['cliente'] = $this->cliente->get(array('id' => $id_cliente));
        // $where_group = array('id_cliente' => $id_cliente);
        // $dados['groups'] = $this->usuario_gestor->get_groups_byClients($where_group);
        $empresa = $this->cliente->getPaisCliente($id_cliente);

        if ($empresa->informacoes != 'EUA')
            $this->load->view('usuarios_gestor/add', $dados);
        else
            $this->load->view('usuarios_gestor/add_eua', $dados);
	}

	public function ajaxAddUser() {
		if ($this->input->post()) {
			$rules = array(array(
							'field' => 'usuario',
							'label' => 'Usuário',
							'rules' => 'required|valid_email'),
							array(
							'field' => 'senha',
							'label' => 'Senha',
							'rules' => 'required'),
							array(
							'field' => 'tipo_usuario',
							'label' => 'Função',
							'rules' => 'required')
						);
			$this->form_validation->set_rules($rules);
			if (!$this->form_validation->run()) {
				echo json_encode(array('status' => 'ERRO', 'msg' => validation_errors()));
			} else {
				$data = array(
					'nome_usuario' => $this->input->post('nome_usuario'),
					'email_usuario' => $this->input->post('usuario'),
					'senha_usuario' => $this->input->post('senha'),
					'link_ativa' => site_url('cliente/usuarios/ativar/'.md5($this->input->post('nome_usuario').'-'.$this->input->post('senha')))
				);
				$emails = array($this->input->post('usuario'));
				$mensagem = $this->parser->parse('template/emails/new_user_gestor', $data, true);

				//para registro de log
				$id_user = $this->auth->get_login_dados('user');
				$id_user = (int) $id_user;

				try {
					//tenta adicionar o usuário, se o retorno for TRUE faz o restante da lógica
					if($insert = $this->usuario_gestor->add($this->input->post())){
						//registra o log 
						$result = $this->log_shownet->gravar_log($id_user, 'usuario_gestor', $insert, 'criar', 'null', $this->input->post()) ;
					
						// Id do grupo MASTER do cliente
						$idGrupoMaster = $this->db->select('id')->where(
							array(
								'id_cliente' 	=> $this->input->post('id_cliente'),
								'nome' 			=> 'MASTER'
								)
							)->get('showtecsystem.cadastro_grupo')->result();
	
						//cria o grupo master caso não haja nenhum
						if(!isset($idGrupoMaster[0])){
							$dados = array(
								'nome' => 'MASTER',
								'id_cliente' => $this->input->post('id_cliente'),
								'status' => 1
							);
	
							$this->db->insert('showtecsystem.cadastro_grupo', $dados);
							$result2 = $this->log_shownet->gravar_log($id_user, 'cadastro_grupo', $insert, 'criar', 'null', $dados) ;
						}
						
						echo json_encode(array('status' => 'OK', 'msg' => 'Usuário Cadastrado com sucesso.'));

					}else{
						echo json_encode(array('status' => 'ERRO', 'msg' =>	"Ocorreu um probelma ao cadastrar o usuário, tente novamente em alguns instantes."));
					}
					
				} catch (Exception $e) {
					echo json_encode(array('status' => 'ERRO', 'msg' => $e->getMessage()));
				}
			}
		} else {
			echo json_encode(array('status' => 'ERRO', 'msg' => 'Nenhum parâmetro foi passado. Verifique e tente novamente!'));
		}
	}

	public function view($id_cliente, $keyword = false) {
		if ($this->input->post()) {
			$rules = array(array(
					'field' => 'usuario',
					'label' => 'Usuário',
					'rules' => 'required|valid_email'),
					array(
							'field' => 'tipo_usuario',
							'label' => 'Função',
							'rules' => 'required')
			);
			$this->form_validation->set_rules($rules);
			if(!$this->form_validation->run()) {
				$this->session->set_flashdata('msg', validation_errors());
			} else {
				$data = array(
						'nome_usuario' => $this->input->post('nome_usuario'),
						'email_usuario' => $this->input->post('usuario'),
						'senha_usuario' => $this->input->post('senha'),
						'status_usuario' => $this->input->post('tipo_usuario')
				);
				$emails = array($this->input->post('usuario'));
				$mensagem = $this->parser->parse('template/emails/update_user_gestor', $data, true);
				try {
					$this->usuario_gestor->atualizar($this->input->post('code'), $this->input->post());
					$this->session->set_flashdata('msg', 'Usuário atualizado com sucesso.');
					$envio = $this->sender->enviar_email('suporte@showtecnologia.com', 'Show Tecnologia', $emails,
							'Dados atualizados Show Tecnologia', $mensagem);
				} catch (Exception $e) {
					$this->session->set_flashdata('msg', $e->getMessage());
				}
			}
			redirect(site_url('clientes/view/'.$id_cliente));
		}
		$dados['cliente'] = $this->cliente->get(array('id' => $id_cliente));
		$contratos = $this->cliente->getContratosAtivos($id_cliente);
		$isContratos = $contratos && count($contratos) > 0;
		$dados['isContratos'] = $isContratos;

		if ($keyword && is_numeric($keyword)) {
			$where = array('code' => $keyword);
		} else if ($keyword && is_string($keyword)) {
			$where = array('usuario' => $keyword);
		}
		$usuario = $this->usuario_gestor->get($where);
		$dados['usuario'] = $usuario;
		$dados['params'] = $this->auth->get_rel_params($usuario->CNPJ_);

		// $where_group = array('id_cliente' => $id_cliente);
		// $dados['groups'] = $this->usuario_gestor->get_groups_byClients($where_group);
		$this->load->view('usuarios_gestor/view_new', $dados);
	}

	public function getGruposUsuario($id_user) {
		try{
		$dados = $this->usuario_gestor->getGruposUser($id_user);
		$dados = array('grupos' => $dados);
		$this->load->view('usuarios_gestor/grupos_usuario', $dados);
		}catch(Exception $e){
			json_encode(array('status' => 'ERRO', 'msg' => $e->getMessage()));
		}
	}

	public function getVeiculosGrupo($id_group){
		try{
		$dados = $this->usuario_gestor->getVeiculosGrupo($id_group);
		$dados = array('veiculos' => $dados);
		$this->load->view('usuarios_gestor/veiculos_grupo', $dados);
		}catch(Exception $e){
			json_encode(array('status' => 'ERRO', 'msg' => $e->getMessage()));
		}
	}

	public function ajaxEditUser() {
		if ($this->input->post()) {
			
			$rules = array(array(
					'field' => 'usuario',
					'label' => 'Usuário',
					'rules' => 'required|valid_email'),
					array(
							'field' => 'tipo_usuario',
							'label' => 'Função',
							'rules' => 'required')
			);
			$this->form_validation->set_rules($rules);
			if(!$this->form_validation->run()) {
				echo json_encode(array('status' => 'ERRO', 'msg' => 'O campo usuário deve conter um endereço de e-mail válido.'));
			} else {
				$data = array(
						'nome_usuario' => $this->input->post('nome_usuario'),
						'email_usuario' => $this->input->post('usuario'),
						'senha_usuario' => $this->input->post('senha'),
						'status_usuario' => $this->input->post('tipo_usuario')
				);

				$dados_user_gestor = array(
					'id_cliente' => $this->input->post('id_cliente'),
					'code' => $this->input->post('code'),
					'nome_usuario' => $this->input->post('nome_usuario'),
					'usuario' => $this->input->post('usuario'),
					'senha' => $this->input->post('senha'),
					'cpf' => $this->input->post('cpf'),
					'tipo_usuario' => $this->input->post('tipo_usuario'),
					'celular' => $this->input->post('celular'),
					'tipo_wstt' => $this->input->post('tipo_wstt')
				);

				if(!($this->input->post('duplo_fator_autenticacao'))){
					$dados_user_gestor['duplo_fator_autenticacao'] = null;
				}

				$emails = array($this->input->post('usuario'));
				$mensagem = $this->parser->parse('template/emails/update_user_gestor', $data, true);

				//para registro de log
				$id_user = $this->auth->get_login_dados('user');
				$id_user = (int) $id_user;
						
				$id_usuario = $this->input->post('code');
				$usuario = $this->usuario_gestor->get(array('code' => $id_usuario));			

				$dados_usuarios_antigo = array(	
					'id_cliente' 	=> $usuario->id_cliente,
					'code' 			=> $usuario->code,
					'nome_usuario' 	=> $usuario->nome_usuario,
					'usuario' 		=> $usuario->usuario,
					'senha' 		=> $usuario->senha,
					'cpf' 			=> $usuario->cpf,
					'tipo_usuario' 	=> $usuario->tipo_usuario,
					'celular' 		=> $usuario->celular,										
					'tipo_wstt' 	=> $usuario->tipo_wstt,					
					'duplo_fator_autenticacao' 		=> $usuario->duplo_fator_autenticacao								
				);

				$dados_novos_formatados = $dados_user_gestor;
				$dados_novos_formatados['senha'] = md5($dados_novos_formatados['senha']);

				try {
					$this->usuario_gestor->atualizar($this->input->post('code'), $dados_user_gestor);
					$result = $this->log_shownet->gravar_log($id_user, 'usuario_gestor', $id_usuario, 'atualizar', $dados_usuarios_antigo, $dados_novos_formatados);
					
					$tipo_busca = $this->input->post('tipo-busca');
					$cliente_id_new = 0;
					if ($tipo_busca == 0){
						$cliente_id_new = $this->input->post('clienteIdcode');
					} else if ($tipo_busca == 1){
						$cliente_id_new = $this->input->post('clientecode');
					} else if ($tipo_busca == 2){
						$cliente_id_new = $this->input->post('clienteDoccode');
					} 
					$msg = 'Usuário Editado com sucesso.';
					if($cliente_id_new != 0 && $cliente_id_new != $usuario->id_cliente){
						$retorno_cliente = to_AlterarCliente(array(
							'idCliente' => $cliente_id_new,
							'idUsuario' => $this->input->post('code')
						));
						if($retorno_cliente['status'] != 200){
							$msg = 'Usuário Editado com sucesso. Mas não foi possivel mudar o cliente';
						}
					}

					// $envio = $this->sender->enviar_email('suporte@showtecnologia.com', 'Show Tecnologia', $emails,
					// 'Dados atualizados Show Tecnologia', $mensagem);
					echo json_encode(array('status' => 'OK', 'msg' => $msg));
				} catch (Exception $e) {
					echo json_encode(array('status' => 'ERRO', 'msg' => $e->getMessage()));
				}
			}
		} else {
			echo json_encode(array('status' => 'ERRO', 'msg' => 'Nenhum parâmetro encontrado. Tente novamente mais tarde!'));
		}
	}

	public function update_status($status, $id_usuario) {
		$usuario = $this->usuario_gestor->get(array('code' => $id_usuario));
		if (count($usuario) > 0){
			$atualiza_conta = $this->usuario_gestor->atualizar_conta($usuario->code, ['status_usuario' => $status, 'tentativas' => 0]);

			$dados_usuarios_antigo = array(				
				'status_usuario' => $usuario->status_usuario				
			);
			
			$id_user = $this->auth->get_login_dados('user');
			$id_user = (int) $id_user;
		
			$dados_novos_formatados = array(				
				'status_usuario' => $status				
			);

			if ($atualiza_conta) {
				$result = $this->log_shownet->gravar_log($id_user, 'usuario_gestor', $usuario->code, 'atualizar', $dados_usuarios_antigo, $dados_novos_formatados);
				echo json_encode(array('status' => 'OK', 'msg' => 'Usuário atualizado com sucesso.'));
			} else {
				echo json_encode(array('status' => 'ERRO', 'msg' => 'Não foi possível atualizar. Tente novamente'));
			}
		} else {
			echo json_encode(array('status' => 'ERRO', 'msg' => 'Usuário não encontrado.'));
		}
	}

	public function permissoes_modulos($id_cliente, $id_user) {
		$usuario = $this->usuario_gestor->get(array('code' => $id_user));
		$dados = array('usuario' => $usuario);
		$this->load->view('usuarios_gestor/permissoes_modulo', $dados);
	}

	public function ajax_permissoes_salvar() {
		$post = $this->input->post();
		$permissoesString = $this->input->post('permissoes');
		$permissoesArray = explode(',', $permissoesString);

		if ($permissoesArray) {
			// Recupera permissões enviadas na requisição
			$permissoes = json_encode( isset($permissoesArray) ? $permissoesArray : [] );

			// Recupera dados atuais do usuário
			$usuario = $this->usuario_gestor->get(array('code' => $post['id_user']));

			// Realiza atualização das permissões do usuário
			if ($this->usuario_gestor->atualizar_conta($post['id_user'], [ 'permissoes' => $permissoes ])) {
				// Grava log de alterações
				$this->log_shownet->gravar_log(
					$this->auth->get_login_dados('user'),
					'usuario_gestor',
					$post['id_user'],
					'atualizar',
					$usuario->permissoes,
					$permissoes
				);

				exit(json_encode([ 'success' => true, 'msg' => 'Permissões atualizadas com sucesso.' ]));
			}
		}

		exit( json_encode( [ 'success' => false, 'msg' => 'Nenhuma alteração efetuada.' ] ) );
	}

	public function ver_users() {
		$users = $this->usuario_gestor->ver_usuarios('2089');
		foreach ($users as $user) {
			$permissoes = unserialize($user->permissoes);
			if ( ! in_array('relatorios_taxi_log_taximetro', $permissoes)) {
				$permissoes[] = 'relatorios_taxi_log_taximetro';
				$d_update = array('permissoes' => serialize($permissoes));
				$this->usuario_gestor->atualizar_conta($user->code, $d_update);
				sleep(1);
			}
		}
	}

	public function user_avatar($id_cliente, $id_usuario) {
		$img = "http://187.28.21.50:8085/sistema/newapp/uploads/users/".$id_usuario.".jpg";
		$dados = array('id_cliente' => $id_cliente , 'id_usuario' => $id_usuario , 'imagem' => $img);
		$this->load->view('usuarios_gestor/user_avatar', $dados);
	}

	public function atualizar_avatar($id_cliente, $id_usuario) {
        // Recupera os dados dos campos
        $foto = $_FILES["arquivo"];

        // Se a foto estiver sido selecionada
		if (!empty($foto["name"])) {

            // Largura máxima em pixels
            $largura = 600;
            // Altura máxima em pixels
            $altura = 600;
            // Tamanho máximo do arquivo em bytes
            $tamanho = 100000;

            $error = array();

            // Verifica se o arquivo é uma imagem
            if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"])){
                $error[1] = "O arquivo não é uma imagem.";
            }

            // Pega as dimensões da imagem
            $dimensoes = getimagesize($foto["tmp_name"]);

            // Verifica se a largura da imagem é maior que a largura permitida
            if($dimensoes[0] > $largura) {
                $error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
            }

            // Verifica se a altura da imagem é maior que a altura permitida
            if($dimensoes[1] > $altura) {
                $error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
            }

            // Verifica se o tamanho da imagem é maior que o tamanho permitido
            if($foto["size"] > $tamanho) {
                $error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
            }

            // Se não houver nenhum erro
            if (count($error) == 0) {

                // Gera um nome único para a imagem
                $nome_imagem = $id_usuario;

                // Caminho de onde ficará a imagem
                $caminho_imagem = 'assets/images/users/' . $nome_imagem.'.jpg';

                // Faz o upload da imagem para seu respectivo caminho
                $retorno = move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_imagem);

                if ($retorno) {
                	$this->session->set_flashdata('retorno_ok', 'Perfil atualizado com Sucesso!');
                  	redirect('clientes/view/'.$id_cliente);
                } else {
                	$this->session->set_flashdata('erro', 'Erro ao carregar imagem! Por favor tente novamente mais tarde.');
                   	redirect('clientes/view/'.$id_cliente);
                }
            } else {
                $this->session->set_flashdata('retorno_erro', 'Imagens fora dos parâmetros! Verifique os parâmetros da imagem e tente novamente.');
                redirect('clientes/view/'.$id_cliente);
            }
        }
    }


	public function get_ajax_usuarios_gestores(){
		$like = NULL;
		$dados = array( );
		$id_cliente = $this->input->get('id_cliente');

		if ($search = $this->input->get('q'))
			$like = array('nome_usuario' => $search);

		$usuarios = $this->usuario_gestor->get_gestores($like, $id_cliente, 10);
		if ($usuarios) {
			foreach ($usuarios as $key => $usuario) {
				$dados[] = array(
					'text' => $usuario->text ? $usuario->text : $usuario->usuario,
					'id' => $usuario->id
				);
			}
		}
		echo json_encode(array('results' => $dados));
	}

	public function listar_gestores(){
		$gestores =  $this->usuario_gestor->get_gestores();
		$results = array( );
		if ($gestores) {
			foreach ($gestores as $key => $gestor) {
				$results[] = array(
					'id_cliente' => $gestor->id_cliente,
					'nome_usuario' => $gestor->nome_usuario
				);
			}
		}
		echo json_encode( $results );
	}

	/*
	* LISTA TODOS OS USUARIOS GESTORES ATIVOS DE UM CLIENTE
	*/
	public function listar_gestores_cliente($id_cliente){
		$gestores =  $this->usuario_gestor->get_gestores_cliente('code as id, nome_usuario as nome', array('id_cliente' => $id_cliente, 'status_usuario' => 'ativo'));
		if ($gestores) {
			echo json_encode( array('status' => true, 'usuarios' => $gestores) );
		}else {
			echo json_encode( array('status' => false, 'msn' => 'Nenhum Usuário Encontrado!') );
		}
	}

	public function sendSms(){
		if ($this->input->post()) {			
			$dados = (object) array(
				'mensagens' => array(
					[
					'remetente' => "Shownet",
					'texto' =>'Seu código de confirmação de 6 dígitos é:  '. $this->input->post('senha'),
					'destinatarios' => [ '55'.$this->input->post('celular')],
					]
				)
				);
			
			API_Helper::post('shownet/sms/enviar', $dados);
		}
	}

	public function sendEmail(){
		if ($this->input->post()) {			
			$dados = (object) array(
				'mensagem' => array(
					'remetente' => "noreply@shownet.com.br ",
					'destinatarios' => [$this->input->post('email')],
					'assunto' => 'Código de Confirmação do email',
					'corpo' =>'Seu código de confirmação de 6 dígitos é:  '. $this->input->post('senha'),
				),
				);
			
			API_Helper::post('shownet/email/enviar', $dados);
		}
	}
}
