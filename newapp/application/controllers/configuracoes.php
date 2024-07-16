<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Configuracoes extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->auth->is_logged('admin');
		$this->load->model('agenda');
		$this->load->model('mapa_calor');
        $this->load->model('auth');
		$this->load->model('configuracao');
	}
	
	public function index (){
		echo 'configs';
	}

	
	public function mensagem_sms_old ($pagina = false){
		
		$this->load->model('configuracao');
		
		$titulo = 'Mensagens Notificação por SMS';
		$find = array();
		$msg = array();
		
		if ($this->input->post()){
		
			$filter = $this->input->post();
					
			$rules = array(array('field' => 'keyword', 'label' => 'Descrição', 'rules' => 'required'));
			$this->form_validation->set_rules($rules);
		
			if ($this->form_validation->run() === false){
					
				$msg['success'] = false;
				$msg['msg'] = validation_errors('<li>','</li>');
					
			}else{
				
				$find = "descricao LIKE '%{$filter['keyword']}%'";
		
			}
		
		}
		
		$paginacao = $pagina != false  ? $pagina : 0;
		$config['total_rows'] = $this->configuracao->has_found_messages($find);
		
		$this->pagination->initialize($config);
		
		$messages = $this->configuracao->find_message($find, $pagina, 15);
		
		$dados = array('titulo' => $titulo, 'mensagens' => $messages);
		$this->mapa_calor->registrar_acessos_url(site_url('/configuracoes/notificacoes/sms'));
		$this->load->view('fix/header', $dados);
		$this->load->view('configs/notifica/sms_view');
		$this->load->view('fix/footer');
	}


	public function buscar_sms_server_side() {
		$startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $descricao = $this->input->post('descricao');
		$mensagem = $this->input->post('mensagem');
		$find = array(
			'status' => 1
		);

		if ($descricao) {
			$find['descricao LIKE'] = "%{$descricao}%";
		}

		if ($mensagem) {
			$find['mensagem LIKE '] = "%{$mensagem}%";
		}

		$limit = $endRow - $startRow;
		$offset = $startRow;

		$qtdRegistros = $this->configuracao->has_found_messages($find);
		$messages = $this->configuracao->find_message($find, $offset, $limit);

		if ($messages) {
            echo json_encode(array(
                "success" => true,
                "rows" => $messages,
                "lastRow" => $qtdRegistros
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => 'Dados não encontrados para os parâmetros informados.'
            ));
        }

	}

	public function mensagem_sms() {
		
		$this->load->model('configuracao');
		
		$titulo = lang('sms_personalizado');
		
		$dados = array(
			'titulo' => $titulo,
			'load' => array('ag-grid', 'select2', 'mask')
		);
		
		$this->mapa_calor->registrar_acessos_url(site_url('/configuracoes/notificacoes/sms'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('configs/notifica/sms_new_view');
		$this->load->view('fix/footer_NS');
	}

	public function add_message(){
		$descricao = $this->input->post('descricao');
		$mensagem = $this->input->post('mensagem');
		$tipo = 1; // define tipo como sms

		$body = array(
			'descricao' => $descricao,
			'mensagem' => $mensagem,
			'tipo' => $tipo
		);
		
		$retorno = array('success' => false, 'msg' => '', 'mensagem' => false);

		if ($mensagem && $descricao) {
			$message_id = $this->configuracao->save_message($body);
		
			if ($message_id){
				
				$message = $this->configuracao->get_message(array('id_msg' => $message_id));
				$retorno['mensagem'] = array($message);
				$retorno['success'] = true;
				$retorno['msg'] = 'Mensagem adicionada com sucesso!';
				
			}else{
				
				$retorno['msg'] = 'Não foi possível adicionar a mensagem. Por favor tente novamente.';
			
			}
		} else {
			$retorno['msg'] = 'Algum campo obrigatório não foi enviado. Tente novamente';
		}
		
		echo json_encode($retorno);
	}

	public function edit_message(){
		$id_msg = $this->input->post('idSMS');
		$descricao = $this->input->post('descricao');
		$mensagem = $this->input->post('mensagem');
		$tipo = 1; // define tipo como sms

		$body = array(
			'descricao' => $descricao,
			'mensagem' => $mensagem,
			'tipo' => $tipo
		);
	
		$retorno = array('success' => false, 'msg' => '', 'mensagem' => false);
	
		if ($id_msg && $descricao && $mensagem){
	
			$update_status = $this->configuracao->update_message($id_msg, $body);

			if ($update_status){
					
				$message = $this->configuracao->get_message(array('id_msg' => $id_msg));
				$retorno['mensagem'] = array($message);
				$retorno['success'] = true;
				$retorno['msg'] = 'Mensagem editada com sucesso!';
					
			}else{
					
				$retorno['msg'] = 'Não foi possível atualizar a mensagem. Por favor tente novamente.';
					
			}
				
		} else {
			$retorno['msg'] = 'Não foi possível atualizar a mensagem, pois algum parâmetro obrigatório não foi enviado.';
		}
	
		echo json_encode($retorno);
	}

	public function delete_message(){
		$id_msg = $this->input->post('id');

		$body = array(
			'status' => 0
		);
	
		$retorno = array('success' => false, 'msg' => '', 'mensagem' => false);
	
		if ($id_msg){
	
			$update_status = $this->configuracao->update_message($id_msg, $body);

			if ($update_status){
				$retorno['success'] = true;
				$retorno['msg'] = 'Mensagem removida com sucesso!';
			}else{
					
				$retorno['msg'] = 'Não foi possível remover a mensagem. Por favor tente novamente.';
					
			}
				
		} else {
			$retorno['msg'] = 'Não foi possível identificar a mensagem a ser removida.';
		}
	
		echo json_encode($retorno);
	}

	public function get_msg_by_id() {
		$id = $this->input->post('id');

		$resultado = array(
			'success' => false,
			'msg' => ''
		);

		if ($id) {
			$resultado['success'] = true;
			$resultado['mensagem'] = $this->configuracao->get_message(array('id_msg' => $id));
			$resultado['msg'] = 'Mensagem obtida com sucesso!';
		} else {
			$resultado['msg'] = 'Não foi possível identificar a mensagem.';
		}

		echo json_encode($resultado);
	}
	
	public function form_msg_sms ($edit = false){
		
		$this->load->model('configuracao');
		$dados = array();
		
		if ($edit){
			
			$dados['controller_form'] = 'configuracoes/ajax_edit_message/'.$edit;
			$dados['message'] = $this->configuracao->get_message(array('id_msg' => $edit));
			
		}else{
			$dados['controller_form'] = 'configuracoes/ajax_save_message/';
		}
		
		$this->load->view('configs/notifica/form_msg_sms', $dados);
	}
	
	public function ajax_save_message(){
		
		$this->load->model('configuracao');
		$post = $this->input->post();
		
		$retorno = array('success' => false, 'msg' => '', 'mensagem' => false);
		
		if ($post){
		
			$rules_form = array(array('field' => 'descricao', 'label' => 'Descrição', 'rules' => 'required' ),
								array('field' => 'mensagem', 'label' => 'Mensagem', 'rules' => 'required' ),
								array('field' => 'tipo', 'label' => 'Tipo', 'rules' => 'required' )
							);
				
			$this->form_validation->set_rules($rules_form);
				
			if (!$this->form_validation->run()){
				
				$retorno['msg'] = validation_errors('<li>', '</li>');
				
			}else{
				
				$message_id = $this->configuracao->save_message($post);
				
				if ($message_id){
					
					$message = $this->configuracao->get_message(array('id_msg' => $message_id));
					$retorno['mensagem'] = array($message);
					$retorno['success'] = true;
					$retorno['msg'] = 'Mensagem adicionada com sucesso!';
					
				}else{
					
					$retorno['msg'] = 'Não foi possível adicionar a mensagem. Por favor tente novamente.';
					
				}
				
			}
			
		}
		
		echo json_encode($retorno);
	}
	
	public function ajax_edit_message($id_message){
	
		$this->load->model('configuracao');
		$post = $this->input->post();
	
		$retorno = array('success' => false, 'msg' => '', 'mensagem' => false);
	
		if ($post){
	
			$rules_form = array(array('field' => 'descricao', 'label' => 'Descrição', 'rules' => 'required' ),
					array('field' => 'mensagem', 'label' => 'Mensagem', 'rules' => 'required' ),
					array('field' => 'tipo', 'label' => 'Tipo', 'rules' => 'required' )
			);
	
			$this->form_validation->set_rules($rules_form);
	
			if (!$this->form_validation->run()){
	
				$retorno['msg'] = validation_errors('<li>', '</li>');
	
			}else{
	
				$update_status = $this->configuracao->update_message($id_message, $post);
	
				if ($update_status){
						
					$message = $this->configuracao->get_message(array('id_msg' => $id_message));
					$retorno['mensagem'] = array($message);
					$retorno['success'] = true;
					$retorno['msg'] = 'Mensagem atualizada com sucesso!';
						
				}else{
						
					$retorno['msg'] = 'Não foi possível atualizar a mensagem. Por favor tente novamente.';
						
				}
	
			}
				
		}
	
		echo json_encode($retorno);
	}
	
}