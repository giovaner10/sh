<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->model('usuario');
		$this->load->model('instalador');
		$this->load->model('representante');
		$this->load->helper('instalador_helper');

		//SETA O IDIOMA
		$lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
		if ($lang == "pt-BR")
			$this->lang->load('pt', 'portuguese');
		else
			$this->lang->load('en', 'english');
	}

	public function tempo_logado($email,$data){

		$this->db->select('tempo_logado');
		$this->db->where('usuario_email', $email);
		$this->db->where('data', $data);
		$sessoes = $this->db->get('showtecsystem.horario_acesso')->result();
		if(count($sessoes)) {

			$this->db->where('login', $email);
			$this->db->select('tempo_logado');
			$usuario = $this->db->get('showtecsystem.usuario')->row(0);
			$tempo_max = $usuario->tempo_logado;
			$tempo_max = '05:00:00';
			$tempo_logado = '00:00:00';
			foreach($sessoes as $sessao){
				$tempo_logado = sum_hours($tempo_logado, $sessao->tempo_logado);
			}

			return true;
			// if(!(compare_time($tempo_logado, $tempo_max) == -1)){
			// 	return false;
			// }
			// else{
			// 	return true;
			// }
		}else{
			return true;
		}
	}
	
	public function cadastrar_sessao($email,$data,$horario_login){

		$dados = array('usuario_email' => $email, 'data' => $data, 'horario_login' => $horario_login, 'horario_logout' => $horario_login);
 		$this->db->insert('showtecsystem.horario_acesso', $dados);
		if($this->db->affected_rows() > 0) {

			return true;

		}else{

			return false;
		}
	}

	public function salvar_hora() {

		$data = date('Y-m-d');
		$hora_atual = date('H:i:s');
		$hora_sessao_max = date('01:00:00');
		$usuario_email = $this->auth->get_login('admin', 'email');

		$this->db->where('usuario_email', $usuario_email);
		$this->db->where('data', $data);
		$this->db->order_by('id', 'DESC');
		$acesso = $this->db->get('showtecsystem.horario_acesso')->row(0);

		$diff = dateDifference($hora_atual, $acesso->horario_login, '%h:%i:%s');
		if(count($acesso)) {
			$this->db->where('id', $acesso->id);
			$this->db->update('showtecsystem.horario_acesso', array('horario_logout' => $hora_atual,
																'tempo_logado' => $diff));
		}else{

			
			$this->cadastrar_sessao($usuario_email,$data,$hora_atual);
			$this->salvar_hora();
		}

		return true;
	}

	public function verifica_status_login($email, $senha){

	    $usuarioFerias = $this->usuario->get(array('login' => $email, 'senha' => $senha));
	    if($usuarioFerias->status_bloqueio == '1'){
	        return array('id' => $usuarioFerias->id  , 'status' => $usuarioFerias->status_bloqueio, 'data_retorno_ferias' => $usuarioFerias->data_retorno_ferias);

	    } elseif($usuarioFerias->status_bloqueio == '2'){
	        return array('id' => $usuarioFerias->id  , 'status' => $usuarioFerias->status_bloqueio, 'data_retorno_ferias' => $usuarioFerias->data_retorno_ferias);
	    }
		return false;
	}

	public function logar($email, $senha){

		$usuario = $this->usuario->get(array('login' => $email, 'senha' => $senha, 'status' => 1));
		if($usuario){

			//Pega o idioma
			$idoma = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];

			$sessao = array(
				'user' => $usuario->id,
				'email' => $usuario->login,
				'cpf' => $usuario->cpf,
				'funcao' => $usuario->funcao,
				'nome' => $usuario->nome,
				'senha' => $usuario->senha,
				'idioma' => $idoma,
				'permissoes' => unserialize($usuario->permissoes),
				'logado' => true,
				'token' => $usuario->token,
				'initSession' => time(),		//nao alterar, usado para finalizar sessão do usuario
				'tokenApiTelevendas' => '',
				'expireTokenApiTelevendas' => 0,
			);

			if($this->gerar_sessao($sessao))
				return true;

			return false;

		}

	}

	public function updatePass($id_user, $data) {
		if (is_numeric($id_user)) {
			$this->db->update('showtecsystem.usuario',
				array('senha' => md5($data['pass_nova']), 'token' => md5($data['pass_nova'])),
				array('id' => $id_user, 'senha' => md5($data['pass_atual']))
			);
			if ($this->db->affected_rows() == 1)
				return true;
		}

		return false;
	}

	public function logar_instalador($email, $senha){

		$usuario = $this->instalador->get(array('email' => $email, 'senha' => $senha));
		json_decode(login_instalador($email,$senha));
		if($usuario){

			$sessao = array('user' => $usuario->id, 'email' => $usuario->email,
							'nome' => $usuario->nome, 'permissoes' => 'area_instalador',
							'logado' => true);

			if($this->gerar_sessao_instalador($sessao))
				return true;

			return false;

		}

	}

	public function logar_representante($email, $senha){

		$usuario = $this->representante->get(array('email' => $email, 'senha' => $senha));
		if($usuario){

			$sessao = array('user' => $usuario->id, 'email' => $usuario->email,
							'token'=> $usuario->token,
							'nome' => $usuario->nome, 'permissoes' => 'area_representante',
							'logado' => true);

			if($this->gerar_sessao_representante($sessao))
				return true;

			return false;

		}

	}

	public function logout($area){

		$this->session->unset_userdata('log_'.$area);

		return true;


	}


	public function gerar_sessao($d_sessao, $area = 'admin'){

		$sessao = array('log_'.$area => $d_sessao);
		$this->session->set_userdata($sessao);

		if($this->session->userdata('log_'.$area))
			return true;

		return false;

	}

	public function is_logged($area, $controller = 'acesso/entrar') {
		$uri_parts = explode('index.php/', $_SERVER['REQUEST_URI']);
		if (isset($uri_parts[1])) {
			$uri = $uri_parts[1];
		} else {
			$uri = ''; 
		}
		$_SESSION['uri_redirect'] = $uri; 
	
		$login = $this->session->all_userdata();
		if (!isset($login['log_'.$area]) || !isset($login['log_'.$area]['logado'])) {
			redirect($controller, 'refresh', 302);
		}
	}

	public function get_login($area, $atributo){
		$login = $this->session->userdata('log_'.$area);
		if(count($login) > 0)
			return $login[$atributo];
		return false;
	}

    public function get_login_dados($atributo = false){
        $login = $this->session->userdata('log_admin');
        if(count($login) > 0 && $atributo)
            return $login[$atributo];
        return $login;
    }

    public function get_sessao($area){
        $login = $this->session->userdata('log_'.$area);
        if(count($login) > 0)
            return $login;
        return false;
    }

	/*
	 * pega parametros dos relatorios
	 * return object
	 */
	public function get_rel_params($cnpj){

		$query = $this->db->get_where('systems.parametro_relatorio', array('cnpj' => $cnpj));

		if($query->num_rows() > 0){
			foreach($query->result() as $params);
		}

		return isset($params) ? $params : array();

	}

	public function is_allowed($permissao = '', $sessao = 'admin'){

		$login = $this->session->userdata('log_'.$sessao);
		if(!is_array($login['permissoes']) || !in_array($permissao, $login['permissoes'])){
			redirect('erros/erro_403');
		}

	}

	public function is_allowed_block($permissao = '', $sessao = 'admin'){

		$login = $this->session->userdata('log_'.$sessao);
		if(!is_array($login['permissoes']) || !in_array($permissao, $login['permissoes']))
			return false;
		else
			return true;
	}

	private function gerar_sessao_instalador($d_sessao, $area = 'instalador'){

		$sessao = array('log_'.$area => $d_sessao);
		$this->session->set_userdata($sessao);

		if($this->session->userdata('log_'.$area))
			return true;

		return false;

	}

	private function gerar_sessao_representante($d_sessao, $area = 'representante'){

		$sessao = array('log_'.$area => $d_sessao);
		$this->session->set_userdata($sessao);

		if($this->session->userdata('log_'.$area))
			return true;

		return false;

	}

	// public function get_token_api_shownet($area = 'admin', $controller = 'acesso/entrar') {
	public function is_logged_api_shownet($area = 'admin', $controller = 'acesso/entrar') {
		$sessao = $this->session->userdata('log_' . $area);
		if ( empty($sessao['tokenApiTelevendas']) || empty($sessao['expireTokenApiTelevendas']) || $sessao['expireTokenApiTelevendas'] < time() ) {
			$this->gerar_token_api_shownet();
		}
	}

	public function gerar_token_api_shownet($area = 'admin') {
		$this->load->helper('api_televendas');
		
		$sessao = $this->session->userdata('log_' . $area);
		$usuario = $this->usuario->get(array('login' => $sessao['email']));

		$resposta = json_decode(API_Televendas_Helper::login($sessao['email'], $usuario->senha));
		if (!empty($resposta->token)) {
      $sessao['tokenApiTelevendas'] = $resposta->token;
      $sessao['expireTokenApiTelevendas'] = time() + 1000 * 60 * 60 * 2; // 2 horas
    }
    else {
      $sessao['tokenApiTelevendas'] = '';
      $sessao['expireTokenApiTelevendas'] = 0;
    }

    $this->session->set_userdata('log_' . $area, $sessao);
	}

	public function remover_token_api_shownet($area = 'admin') {
		$sessao = $this->session->userdata('log_' . $area);
		$sessao['tokenApiTelevendas'] = '';
		$sessao['expireTokenApiTelevendas'] = 0;
		$this->session->set_userdata('log_' . $area, $sessao);
	}
	

	public function listar_permissoes_usuario($id){
		$this->db->select('permissoes');
		$this->db->where('id', $id);
		$permissoes = $this->db->get('showtecsystem.usuario')->row(0);

		return unserialize($permissoes->permissoes);
	}

	public function get_logged_user_id($area = 'admin') {
		$sessao = $this->session->userdata('log_' . $area);
		if (!empty($sessao) && isset($sessao['user'])) {
			return $sessao['user'];
		}
		return false;
	}
	

}
