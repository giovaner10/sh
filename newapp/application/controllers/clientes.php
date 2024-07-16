<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->auth->is_logged('admin');
        $this->load->model('auth');
		$this->load->model('veiculo');
		$this->load->model('cliente');
		$this->load->model('usuario_gestor');
		$this->load->model('parlacom');
		$this->load->helper('text');
		$this->load->helper("util_helper");
		$this->load->model('logistica');
		$this->load->model('cadastro');
		$this->load->model('contrato');
		$this->load->model('usuario');
		$this->load->model('mapa_calor');
		$this->load->model('ocorrencia_cliente');
		$this->load->helper('api_helper');
	}
	
	function index_old() {
		$data['titulo'] = 'Clientes';
		$this->load->view('fix/header4', $data);
		$this->load->view('clientes/lista_cliente0');
		$this->load->view('fix/footer4');
	}

    function index_antigo() {
        $data['titulo'] = 'Clientes';
        // $data['planos'] = $this->cadastro->getPlanos();
        $data['consultores'] = $this->contrato->get_users();
        $data['produtos'] = $this->cadastro->get_produtos('*', array('status' => '1'));
		$data['extensoesArquivos'] = ['.jpg', '.jpeg', '.png'];

		//passa as permissões necessárias para a página
		$data['permissoes'] = json_encode(array(
            'cad_cadastrarusuariosshownetgestor' => $this->auth->is_allowed_block('cad_cadastrarusuariosshownetgestor')
        ));

        $this->load->view('fix/header-new', $data);
        $this->load->view('clientes/novaView', $data);
        $this->load->view('fix/footer_new');
    }
	
    function index() {	
		$this->mapa_calor->registrar_acessos_url(site_url('/clientes'));
        $data['titulo'] = 'Clientes';
        // $data['planos'] = $this->cadastro->getPlanos();
        $data['consultores'] = $this->contrato->get_users();
        $data['produtos'] = $this->cadastro->get_produtos('*', array('status' => '1'));
		$data['extensoesArquivos'] = ['.jpg', '.jpeg', '.png'];

		//passa as permissões necessárias para a página
		$data['permissoes'] = json_encode(array(
            'cad_cadastrarusuariosshownetgestor' => $this->auth->is_allowed_block('cad_cadastrarusuariosshownetgestor')
        ));

        $data["load"] = ["select2", "mask", "jquery-form", 'ag-grid'];
		
        $this->load->view('new_views/fix/header', $data);
        $this->load->view('clientes/novaView_NS', $data);
        $this->load->view('fix/footer_NS');
    }

	function ajax_list() {
		$retorno = array();
		$dados = $this->cliente->listar(array());
		if ($dados) {
			foreach ($dados as $d)
				$retorno[] = $d->id.'-'.$d->nome;
		}
		echo json_encode($retorno);
	}

	function bloqParcial($id_cliente) {
	    $retorno = $this->usuario_gestor->bloquearUser_parcial(array('id_cliente' => $id_cliente, 'status_usuario' => 'ativo'));
	    if ($retorno){
	        echo json_encode(array('status' => true));
        }else{
            echo json_encode(array('status' => false));
        }
    }

    function desbloqueioParcial($id_cliente) {
        $retorno = $this->usuario_gestor->desbloquearUser_parcial(array('id_cliente' => $id_cliente, 'status_usuario' => 'parcial'));
        if ($retorno){
            echo json_encode(array('status' => true));
        }else{
            echo json_encode(array('status' => false));
        }
    }

	function negativarPositivar() {
		$dados = $this->input->post();
        $arquivo = isset($_FILES) ? $_FILES['arquivo_cliente']['name'] : false;

		if ($arquivo) {
			if ($arq = $this->upload_cliente()) {
				$this->cliente->digit_negativa_positiva($dados['id_cliente'], $dados['descricao'], './uploads/clientes', $arq['file_name'], $dados['acao'], 'negativa_positiva');
				$negPos = $this->usuario_gestor->negativar_positivar($dados['id_cliente'], $dados['acao']);

				if ($negPos) {
						echo json_encode(array( 'success' => true, 'acao' => $dados['acao'], 'id_cliente' => $dados['id_cliente'] ));
				}else {
					echo json_encode(array( 'success' => false, 'msg' => 'Processo não realizado no momento, tente mais tarde!'));
				}

			} else {
				die(json_encode(array('success' => false, 'msg' => $this->upload->display_errors())));
			}
		} else {
			die(json_encode(array('success' => false, 'msg' => 'Processo não realizado, favor carregar o arquivo!')));
		}

    }

	//salva o arquivo no diretorio
	private function upload_cliente($upload_path='uploads/clientes/') {
		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'pdf|png|jpeg|jpg';
        $config['max_size']	= '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $config['encrypt_name']  = 'true';
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('arquivo_cliente'))
            return $this->upload->data();
        else
            return false;
    }


	//carrega arquivos digitalizados para clientes negativado/positivado
	public function ajax_digi_neg_posit() {
	  $id_cliente = $this->input->post('id');

	  $arquivos = $this->cliente->get_arqui_clientes($id_cliente, 'negativa_positiva');

		if ($arquivos) {
			foreach ($arquivos as $key => $arq) {
				$date = new DateTime($arq->data_cadastro);
				$arq->data_cadastro =  $date->format('d/m/Y H:i:s');
				$arq->id_usuario = !is_null($arq->id_usuario) ? $this->usuario->getUser($arq->id_usuario, 'nome')[0]->nome : '';

				if($arq->caminho == 's3/whatsapp_bot'){
					$arq->link = site_url("clientes/getComprovantePagamento/?key=$arq->nome_arquivos");
				}
			}
	   }
	  echo json_encode(array('status' => true, 'data' => $arquivos));
	}

	public function getComprovantePagamento(){
		$key = $this->input->get('key');		
		$arquivo = API_Helper::get("clientes/comprovanteDesbloqueio/$key");
		$dados = json_decode($arquivo);
		
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="Comprovante de pagamento.pdf"');
    	echo base64_decode($dados->data);
	}

	function getShow() {
		echo $this->cliente->getShow();
	}

	function getSimm2m() {
		echo $this->cliente->getSimm2m();
	}

	function getSigamy() {
		echo $this->cliente->getSigamy();
	}

	function getNorio() {
		echo $this->cliente->getNorio();
	}

    function getEua() {
        echo $this->cliente->getEua();
    }

    /*
    * REQUISIÇÃO AJAX PARA MONTAGEM DO DATATABLE CLIENTES
    */
    function ajax_listClient() {
    	$company = 'TRACKER'; # Código de identificação da prestadora
    	$draw = 1; # Draw Datatable
        $start = 0; # Contador (Start request)
        $limit = 10; # Limite de registros na consulta
        $where = NULL; # Campo pesquisa
        if ($this->input->get()) {
        	$company = $this->input->get('company');
            $draw = $this->input->get('draw');
            $search = $this->input->get('search')['value'];
            $start = $this->input->get('start'); # Contador (Página Atual)
            $limit = $this->input->get('length'); # Limite (Atual)

        }
		echo json_encode($this->cliente->getAjaxListClients($start, $limit, $search, $draw, $company));
    }

    /**
    * Função monta DataTable com lista de Embarcadores
    **/
    public function ajax_listEmbarcadores() {
		$draw = 1; # Draw Datatable
        $start = 0; # Contador (Start request)
        $limit = 10; # Limite de registros na consulta
        $where = NULL; # Campo pesquisa
        if ($this->input->get()) {
            $draw = $this->input->get('draw');
            $search = $this->input->get('search')['value'];
            $start = $this->input->get('start'); # Contador (Página Atual)
            $limit = $this->input->get('length'); # Limite (Atual)
        }

        echo json_encode($this->cliente->getAjaxListEmbarcadores($start, $limit, $search, $draw));
    }

    /*
    * MONTA VIEW CLIENTE
    */
	public function view($id_cliente = false) {
		$this->auth->is_allowed('clientes_visualiza');

		if ($id_cliente && is_numeric($id_cliente)){
			$dados['cliente'] = $this->cliente->get(array('id' => $id_cliente));
		} elseif ($this->input->post('cliente')) {
			$dados['cliente'] = $this->cliente->get(array('nome' => $this->input->post('cliente')));
			$id_cliente = $dados['cliente']->id;
		} else {
			$this->load->view('fix/header', array('titulo' => 'ERROR'));
			$this->load->view('erros/403');
			$this->load->view('fix/footer');
			return;
		}

		$where_group = array('id_cliente' => $id_cliente);
        $dados['groups'] = $this->usuario_gestor->get_groups_byClients($where_group);
		$dados['id_cliente'] = $id_cliente;
		$dados['titulo'] = 'Clientes';

		$this->load->view('fix/header', $dados);
		$this->load->view('clientes/view_cliente');
		$this->load->view('fix/footer');
	}

	/*
	* MONTA DATATABLE DAS FILIAIS (VIEW CLIENTE)
	*/
	public function ajaxListFiliais() {
		$data['data'] = array();
		if ($id_cliente = $this->input->get('id_cliente')) {
			$filiais = $this->cliente->listar(array('id_nivel' => $id_cliente));
			if ($filiais) {
				foreach ($filiais as $filial) {
					$data['data'][] = array(
						$filial->id,
						$filial->nome,
						show_status($filial->status),
						'<a href="'.site_url('clientes/migra_matriz/'.$id_cliente.'/'.$filial->id).'" data-toggle="modal" data-target="#migra_matriz" class="btn btn-primary btn-mini" title="Alterar p/ matriz"><i class="icon-home icon-white"></i> </a>'
					);
				}
			}
		}

		echo json_encode($data);
	}

	/*
	* MONTA DATATABLE DOS USUÁRIOS DO CLIENTE (VIEW CLIENTE)
	*/
	public function ajaxListUsers() {
		$data['data'] = array();
		
		if ($id_cliente = $this->input->get('id_cliente')) {
			$usuarios = $this->usuario_gestor->listar(array('id_cliente' => $id_cliente));
			if ($usuarios) {
				foreach ($usuarios as $user) {
					$button_on = $user->status_usuario == 'inativo' ? '<a data-url="'.site_url('usuarios_gestor/update_status/ativo/'.$user->code).'" data-id="'.$user->code.'" class="btn btn-danger ativo ativar" title="Liberar Acesso"> <i class="fa fa-ban"></i></a>' : '<a data-url="'.site_url('usuarios_gestor/update_status/inativo/'.$user->code).'" data-id="'.$user->code.'" class="btn btn-success inativo inativar" title="Bloquear"><i class="fa fa-check" ></i></a>';
					$butoes_informacoes = ' <a data-url="'.site_url('usuarios_gestor/getGruposUsuario/'.$user->code).'" data-id="'.$user->code.'" data-toggle="modal" data-target="#view_usuario_grupos" onclick="render(this)" data-modal="#body_gruposUser" class="btn btn-primary" style="margin-left: 5px" title="Grupos ao qual o usuário pertence"><i class="fa fa-users"  aria-hidden="true"></i></a>';

					$data['data'][] = array(
						$user->code,
						$user->nome_usuario,
						$user->usuario,
						$user->celular,
						$user->tipo_usuario,
						$user->status_usuario == 'ativo' ? lang('liberado') : lang('bloqueado'),
						'<div style="display: flex">'.
							$button_on.
							' <a data-url="'.site_url('usuarios_gestor/view/'.$id_cliente.'/'.$user->code).'" data-toggle="modal" data-target="#view_usuario" onclick="render(this)" data-modal="#body_ediUser" style="margin-left: 5px" class="btn btn-primary" title="Atualizar Dados"><i class="fa fa-edit" aria-hidden="true"></i></a>'.
							' <a data-id="'.$user->code.'" class="btn btn-primary edit_permissoes" style="margin-left: 5px" title="Acesso aos Módulos"><i class="fa fa-sitemap" aria-hidden="true"></i> </a>'.
							' <a data-id="'.$user->code.'" data-email="'.$user->usuario.'" data-user="'.$user->senha.'" class="btn btn-primary logar" style="margin-left: 5px" title="Logar na conta do Usuário"><i class="fa fa-power-off" aria-hidden="true"></i></a>'.
							$butoes_informacoes
						.'</div>'
					);
				}
			}
		}
	
		echo json_encode($data);
	}
	
	

	public function get_grupos(){
        $id_cliente = $this->input->get('id');
        $dados = array();
        $s = array();
        $grupos = $this->cliente->getAjaxListSecretaria($id_cliente);
        if ($grupos){
            foreach ($grupos as $grupo){
                $s[] = array(
                    'id' => $grupo->id,
                    'nome' => $grupo->nome,
                );
            }
        }
        $dados['grupos'] = json_encode($s);

        $this->load->view('clientes/add_usuario', $dados);
    }

	public function view_filial($id_cliente) {
		$this->auth->is_allowed('clientes_update');
		if ($this->input->post('acao')) {
			$cliente = $this->input->post('id_cliente');
			$id_filial = $this->input->post('id_filial');
			if ($this->cliente->atualizar($id_filial, array('id_nivel' => $cliente)))
				$this->session->set_flashdata('msg', 'Filial adicionada com sucesso');
			redirect(site_url('clientes/view/'.$id_cliente));
		}
		$keyword = $this->input->post('keyword');
		if ($keyword && is_numeric($keyword)) {
			$where = array('id' => $keyword);
		} elseif ($keyword && is_string($keyword)) {
			$where = array('nome' => urldecode($keyword));
		}
		$dados['filial'] = $this->cliente->get($where);
		$dados['cliente'] = $this->cliente->get(array('id' => $id_cliente));
		$this->load->view('clientes/relaciona_filial', $dados);
	}

	public function migra_matriz($id_cliente, $id_filial) {
		$this->auth->is_allowed('clientes_update');
		if ($this->input->post()) {
			if ($this->cliente->atualizar($this->input->post('id_filial'), array('id_nivel' => 0))) {
				$this->session->set_flashdata('msg', 'Filial alterada para Matriz com sucesso.');
			} else {
				$this->session->set_flashdata('msg', 'Não foi possível alterar. Tente novamente.');
			}
			redirect(site_url('clientes/view/'.$id_cliente));
		}
		$dados['filial'] = $this->cliente->get(array('id' => $id_filial));
		$this->load->view('clientes/migra_matriz', $dados);
	}

	public function ajax_listar ($pagina = false) {
		$this->auth->is_allowed('clientes_visualiza');
		$paginacao = $pagina != false  ? $pagina : 0;
		$dados['msg'] = $this->session->flashdata('msg');
		$dados['erros'] = $this->session->flashdata('erro');
		$config['base_url'] = site_url().'/clientes/ajax_listar/';
		$config['uri_segment'] = 3;
		$config['total_rows'] = $this->cliente->total_lista(array('status' => 1));
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="pag-ajax">';
		$config['num_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li class="pag-ajax">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li class="pag-ajax">';
		$config['perv_tag_close'] = '</li>';
		$config['last_link'] = 'Fim';
		$config['last_tag_open'] = '<li class="pag-ajax">';
		$config['last_tag_close'] = '</li>';
		$config['first_link'] = 'Início';
		$config['first_tag_open'] = '<li class="pag-ajax">';
		$config['first_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$dados['clientes'] = $this->cliente->listar(array('status' => 1), $paginacao, 15);
		$dados['all_clientes'] = $this->cliente->listar(array(), 0, 99999);
		$j_clientes = array();
		if (count($dados['all_clientes']) > 0) {
			foreach ($dados['all_clientes'] as $cli)
				$j_clientes[] = $cli->nome;
		}
		$dados['j_clientes'] = json_encode($j_clientes);
		$dados['titulo'] = 'Clientes';
		$this->load->view('clientes/clientes', $dados);
	}

	public function filtrar ($pagina = false) {
		if ($this->input->post()) {
			$nome_cliente = $this->input->post('nome_cliente');
			$status_cliente = $this->input->post('status_cliente');
			if ($nome_cliente) {
				if (is_numeric($nome_cliente)) {
					$cond_query['id'] = $this->input->post('nome_cliente');
				}else{
					$cond_query['nome'] = $this->input->post('nome_cliente');
				}
				$this->session->set_userdata('filtro_cliente', $cond_query);
			} elseif ($status_cliente || $status_cliente == 0) {
				if ($status_cliente != 'all') {
					$cond_query['status'] = $status_cliente;
					$this->session->set_userdata('filtro_cliente', $cond_query);
				} else {
					$this->session->unset_userdata('filtro_cliente', array('status'));
				}
			}
		}
		$where = $this->session->userdata('filtro_cliente') === false ? array() : $this->session->userdata('filtro_cliente');
		$dados['status_cliente'] = isset($where['status']) ? $where['status'] : 'all';
		$dados['nome_cliente'] = isset($where['nome']) ? $where['nome'] : '';
		$paginacao = $pagina != false  ? $pagina : 0;
		$dados['msg'] = $this->session->flashdata('msg');
		$dados['erros'] = $this->session->flashdata('erro');
		$config['base_url'] = site_url().'/clientes/filtrar/';
		$config['uri_segment'] = 3;
		$config['total_rows'] = $this->cliente->total_lista($where);
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="pag-ajax">';
		$config['num_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li class="pag-ajax">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li class="pag-ajax">';
		$config['perv_tag_close'] = '</li>';
		$config['last_link'] = 'Fim';
		$config['last_tag_open'] = '<li class="pag-ajax">';
		$config['last_tag_close'] = '</li>';
		$config['first_link'] = 'Início';
		$config['first_tag_open'] = '<li class="pag-ajax">';
		$config['first_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$dados['clientes'] = $this->cliente->listar($where, $paginacao, 15);
		$dados['all_clientes'] = $this->cliente->listar(array(), 0, 99999);
		$j_clientes = array();
		if (count($dados['all_clientes']) > 0) {
			foreach ($dados['all_clientes'] as $cli)
				$j_clientes[] = $cli->nome;
		}
		$dados['j_clientes'] = json_encode($j_clientes);
		$this->load->view('clientes/clientes', $dados);
	}

	public function get_usuarios() {
		if ($usuarios = $this->cliente->get_usuarios($this->input->get('id'))) {
			foreach ($usuarios as $usuario) {
				$dados[] = array(
					'id' => $usuario->code,
					'nome' => $usuario->usuario,
				);
			}
			die(json_encode($dados));
		}
		die(json_encode(array()));
	}

	/*
	* Lista clientes para select2
	*/
	function ajaxListSelect() {
		$like = NULL;
		if ($search = $this->input->get('q'))
			$like = array('nome' => $search);

		$results = $this->cliente->listar(array(), 0, 10, 'nome', 'asc', 'nome as text, id, cpf, cnpj', $like);
		
		if ($results){
			foreach ($results as $key => $value) {
				$digitosCpf = preg_replace('/\D/', '', $value->cpf);
				$verificaCpf = strlen($digitosCpf);
				$documento = $verificaCpf == 11 ? $value->cpf : $value->cnpj;
				$results[$key]->text = $value->text.' ('.$documento.')';
			}
		}
		echo json_encode(array('results' => $results));
	}

	public function listarUsuarios() {
		$like = NULL;
		if ($search = $this->input->get('q'))
			$like = array('usuario' => $search);

		echo json_encode(array('results' => $this->cliente->listarUsers(array(), 0, 10, 'usuario', 'asc', 'usuario as text , nome_usuario as text2, id_cliente as id', $like )));
		
	}

	public function listarOcorrencia() {

	$id = $this->input->get('id');

	 echo json_encode($this->ocorrencia_cliente->getOcorrencias($id));
		
	}

	public function deletarOcorrencia() {

		$id = $this->input->get('id');
	
		 echo json_encode($this->ocorrencia_cliente->deletarOcorrencia($id));
			
	}

	public function salvarOcorrencia() {

		$idCliente = $this->input->post('idCliente');
		$descricao = $this->input->post('descricao');

		$data = array(
			'idCliente' => $idCliente,
			'descricao' => $descricao
		);

		echo json_encode($this->ocorrencia_cliente->salvar($data));      
	}


	public function editarOcorrencia() {

		$idDesc = $this->input->post('idDesc');
		$descricao = $this->input->post('descricao');

		echo json_encode($this->ocorrencia_cliente->editarOcorrencia($idDesc, $descricao));      
	}
   

	function nacionalidade()
    {
        $dados['titulo'] = 'Seletor de Nacionalidade';

        $this->load->view('fix/header', $dados);
        $this->load->view('clientes/seletor_nacionalidade');
        $this->load->view('fix/footer');
    }

	public function registro_antigo() {
		$this->load->model('contrato');
		$dados['titulo'] = 'Clientes';
		$consultores =  $this->contrato->get_users();
		$dados['consultores'] = $consultores;

		$this->load->view('fix/header4', $dados);
		$this->load->view('clientes/registro');
		$this->load->view('fix/footer4');
	}

	public function registro() {
		$this->load->model('contrato');
		$data['titulo'] = 'Clientes';
		$consultores =  $this->contrato->get_users();
		$data['consultores'] = $consultores;
		$data['usuario'] = $this->usuario->getUser(
            $this->auth->get_login_dados('user') # id do usuario
        )[0];
		
        $data["load"] = ["select2", "mask", "jquery-form"];

        $this->load->view('fix/header_NS', $data);
        $this->load->view('clientes/registro_NS', $data);
        $this->load->view('fix/footer_NS');
	}

	/**
	* Função cadastra parceiros (Embarcadores)
	**/
	public function registro_embarcadores_antigo()
	{
        $dados['titulo'] = 'Cadastro de Parceiros';
        $this->load->view('fix/header', $dados);
        $this->load->view('clientes/registro_embarcadores');
        $this->load->view('fix/footer');
	}

	public function registro_embarcadores()
	{
        $data['titulo'] = 'Cadastro de Parceiros';
		
        $data["load"] = ["select2", "mask", "jquery-form"];
		
        $this->load->view('fix/header_NS', $data);
        $this->load->view('clientes/registro_embarcadores_NS', $data);
        $this->load->view('fix/footer_NS');
	}

    public function registro_eua() {
        $this->load->model('contrato');
        $dados['titulo'] = 'Clientes';
        $consultores =  $this->contrato->get_users();
        $dados['consultores'] = $consultores;

        $this->load->view('fix/header', $dados);
        $this->load->view('clientes/registro_eua');
        $this->load->view('fix/footer');
    }

    public function consulta_cnpj($cnpj)
    {
        $a = str_replace('.','',$cnpj);
        $b = str_replace('/', '', $a);
        $cnpj = str_replace('-', '', $b);
        return json_decode(@file_get_contents('http://receitaws.com.br/v1/cnpj/'.$cnpj));
    }

    public function get_cliente(){
	    $dados = array();
	    $cliente = false;
	    if ($this->input->post('cnpj')) {
            $cliente = $this->cliente->clientePorCNPJ($this->input->post('cnpj'));
        }elseif ($this->input->post('cpf')){
            $cliente = $this->cliente->clientePorCPF($this->input->post('cpf'));
        }elseif ($this->input->post('nome')){
            $cliente = $this->cliente->clientePorNome($this->input->post('nome'));
        }elseif ($this->input->post('id')){
			$cliente = $this->cliente->clientePorNome($this->input->post('id'));
		}elseif ($this->input->post('usuario')){
			$cliente = $this->cliente->clientePorUsuario($this->input->post('usuario'));
		}
		if ($cliente)
		{
			$id = $cliente[0]->id;
	        $id_vendedor = $cliente[0]->id_vendedor;
			$logotipo = $cliente[0]->logotipo && file_exists($cliente[0]->logotipo) ? base_url($cliente[0]->logotipo) : base_url('media/img/Logo_Show_110px_negativo.png');
			
			// add '+' to mask in property gmt
			if (strpos($cliente[0]->gmt, '-') !== false)
				$cliente[0]->gmt = $cliente[0]->gmt;
			else
				$cliente[0]->gmt = '+'.$cliente[0]->gmt;

			$dados['cliente'] = $cliente[0];
			$dados['logotipo'] = $logotipo;
            $dados['emails'] = $this->cliente->get_clientes_emails($id);
            $dados['cartoes'] = $this->cliente->get_clientes_cartoes($id);
            $dados['enderecos'] = $this->cliente->get_clientes_enderecos($id);
            $dados['telefones'] = $this->cliente->get_clientes_telefones($id);
            $dados['vendedor'] = $this->cliente->getClienteVendedor($id_vendedor);
            $dados['permissoes'] = $this->get_permissoes_cliente($cliente[0]);

			$dados['planos_cliente'] = [];
			$planos_cliente = $this->cadastro->get_planos_cliente('nome', $id);
			foreach ($planos_cliente as $plano) {
				array_push($dados['planos_cliente'], $plano->nome);
			}
			$dados['planos_cliente'] = json_encode($dados['planos_cliente']);

            $dados['permissoesExtras'] = $this->get_permissoes_cliente_extras($cliente[0]);			

			
			$dados['ids_produtos'] = json_decode($cliente[0]->ids_produtos);

			//Dados de configuracao omniscore
			$dados['dados_omniscore'] = isset($cliente[0]->configuracao_omniscore) && $cliente[0]->configuracao_omniscore ? json_decode($cliente[0]->configuracao_omniscore) : null;

            $situacao = $this->cliente->getDiff($id);
            if (isset($situacao[0])) {
                rsort($situacao);
                $dados['situacao'] = $situacao[0];
            }else{
                $dados['situacao'] = false;
            }
        }

	    echo json_encode($dados);
    }

	public function dados_cliente($id_cliente) {
		$this->load->model('usuario');
		$this->load->model('contrato');
		$this->load->model('cadastro');
		$dados['titulo'] = 'Clientes';
		$dados['cliente_dados'] = $this->cliente->get_clientes($id_cliente);
		$dados['cliente_receita'] = $this->consulta_cnpj($dados['cliente_dados'][0]->cnpj);
		$dados['cliente_cartoes'] = $this->cliente->get_clientes_cartoes($id_cliente);
		$dados['cliente_enderecos'] = $this->cliente->get_clientes_enderecos($id_cliente);
		$dados['cliente_emails'] = $this->cliente->get_clientes_emails($id_cliente);
		$dados['cliente_telefones'] = $this->cliente->get_clientes_telefones($id_cliente);
		$dados['cliente_vendedor'] = $this->cliente->getClienteVendedor($dados['cliente_dados'][0]->id_vendedor);
		$dados['atendentes'] = $this->usuario->atendenteSim2m($id_cliente);
		$dados['vendedores'] = $this->usuario->vendedorSim2m($id_cliente);
		$dados['consultores'] = $this->contrato->get_users();

		/** Produtos disponíveis*/
		$dados['produtos'] = $this->cadastro->get_produtos('*', array('status' => '1'));

		$dados['planos_cliente'] = [];
		$planos_cliente = $this->cadastro->get_planos_cliente('nome', $id_cliente);

		foreach ($planos_cliente as $plano) {
			array_push($dados['planos_cliente'], $plano->nome);
		}

		$dados['planos_cliente'] = json_encode($dados['planos_cliente']);

		$dados['permissoes'] = $this->get_permissoes_cliente($dados['cliente_dados'][0]);
		$dados['permissoesExtras'] = $this->get_permissoes_cliente_extras($dados['cliente_dados'][0]);

		if ($dados['cliente_dados'][0]->pais == 'EUA')
		    $this->load->view('clientes/dados_cliente_eua', $dados);
		else
            $this->load->view('clientes/dados_cliente', $dados);
	}

	public function get_permissoes_cliente($cliente){
		$permissoes_cliente = json_decode($cliente->permissoes);
		$planos_cliente_ids = [];
		$planos_cliente = $this->cadastro->get_planos_cliente('id_plano', $cliente->id);

		foreach ($planos_cliente as $plano) {
			array_push($planos_cliente_ids, $plano->id_plano);
		}

		if($cliente->id_produto){
			$id_plano = isset($cliente->id_plano) ? $cliente->id_plano : null;
			$id_produto = isset($cliente->id_produto) ? $cliente->id_produto : null;
			$permissoes = $this->cadastro->getPermissoesProdutoOptions($id_produto, $permissoes_cliente, $id_plano, true);

			return $permissoes;
		}
	}

	public function get_permissoes_cliente_extras($cliente){
		$permissoes_cliente = json_decode($cliente->permissoes);
		$id_produto = $cliente->id_produto;

		$planos_cliente_ids = [];
		$planos_cliente = $this->cadastro->get_planos_cliente('id_plano', $cliente->id);

		foreach ($planos_cliente as $plano) {
			array_push($planos_cliente_ids, $plano->id_plano);
		}

		$permissoes = $this->cadastro->getPermissoesModulosOptionsExtras($permissoes_cliente, $id_produto, $planos_cliente_ids, true);

		return $permissoes;
	}

	public function get_permissoes_produto(){

		$permissoes_cliente = $this->input->post('permissoes');

		$id_produto = $this->input->post('id_produto');
		$id_cliente = $this->input->post('id_cliente');

		$planos_cliente_ids = [];
		$planos_cliente = $this->cadastro->get_planos_cliente('id_plano', $id_cliente);
		foreach ($planos_cliente as $plano) {
			array_push($planos_cliente_ids, $plano->id_plano);
		}

		$permissoes = $this->cadastro->getPermissoesProdutoOptions($id_produto, $permissoes_cliente, $planos_cliente_ids);
		$permissoesExtras = $this->cadastro->getPermissoesModulosOptionsExtras($permissoes_cliente, $id_produto, $planos_cliente_ids, false);

		if($permissoes){ // se produto nao tem planos
			exit(json_encode(array('status' => true, 'permissoes' => $permissoes, 'permissoesExtras' => $permissoesExtras)));
		}else{
			exit(json_encode(array('status' => false, 'permissoesExtras' => $permissoesExtras)));
		}

	}

	public function tab_api($id_cliente) {
		$cliente = $this->db->get_where('cad_clientes', array('id' => $id_cliente));
		if ($cliente->result()) {
			$dados['chave'] = $cliente->row(0)->chave_api;
		} else {
			$dados['chave'] = '';
		}
		echo json_encode(array('data' => $dados['chave']));
//		$this->load->view('clientes/tab_api', $dados);
	}

	public function tab_veiculos_espelhados($id_cliente){

		//get client from database
		$cliente = $this->db->get_where('cad_clientes', array('id' => $id_cliente));
		$cnpj = $cliente->row(0)->cnpj;

		if( $cnpj == null ){
			$dados['error'] = "Só é possivel listar veículos com CNPJ";
		}else{
			//set variables to the view
			$dados['cliente'] = $cliente;
			$dados['url_espelhamento'] = base_url('index.php/espelhamento/getCentraisGR?').'cnpj='.$cnpj;
		}

		//return view
		$this->load->view('clientes/tab_veiculos_espelhados',$dados);
	}

	public function tab_equip($id_cliente) {
		$dados['eq_disponiveis'] = $this->logistica->equipDisponivel($id_cliente);
		$i = 0;
		foreach ($dados['eq_disponiveis'] as $eq_disponivel) {
			if ($eq_disponivel->status != "ativo") {
				$i++;
			}
		}
		$dados['count_disponiveis'] = $i;
		$dados['eq_instalados'] = $this->logistica->listar_veiculos_instalados($id_cliente);
		$dados['eq_retirados'] = array_merge($this->logistica->equipRetirados($id_cliente), $this->logistica->equipSubst($id_cliente));
		$dados['id_cliente'] = $id_cliente;
		$this->load->view('clientes/tab_equip', $dados);
	}

    public function equip_disponiveis($id_cliente) {
        $dados = $this->logistica->equipDisponivel($id_cliente);
        echo json_encode(array('data' => $dados));

    }

    public function equip_emUso($id_cliente){
        $dados = $this->logistica->listar_veiculos_instalados($id_cliente);
        echo json_encode(array('data' => $dados));

    }

    public function equip_retirados($id_cliente){
        $dados = array_merge($this->logistica->equipRetirados($id_cliente), $this->logistica->equipSubst($id_cliente));
        echo json_encode(array('data' => $dados));
    }

	public function relatorio_qtd_instalacoes($id_cliente) {
        $dados = get_listar_relatorio_qtd_instalacoes($id_cliente);
        echo json_encode(array('data' => $dados));
    }

    public function centrais(){
	    $id_cliente = $this->input->get('id_cliente');

	    $dados = array();
	    $dados['central'] = $this->cliente->get_centrais();
	    $resultado = get_listarGrupos($id_cliente);
		$grupo = array();
	    if($resultado['status'] == 200){
			$grupo = $resultado['results'];
		}
		$dados['grupos'] = $grupo;
	    $this->load->view('clientes/vincular_central', $dados);
    }
	
    public function editar_centrais(){
	    $id_cliente = $this->input->get('id_cliente');
	    $id = $this->input->get('id');

		$centrais = get_centrais_cliente($id_cliente);
		$centralUpdate = null;
		$gruposSelecionados = array();

		if ($centrais['status'] == 200) {
            foreach ($centrais['dados'] as $central) {
				if($central['idClientCentral'] == $id){
					$centralUpdate = $central;
					$centralUpdate['id_cliente'] = $id_cliente;
					
					$grupos_filter = array_filter($central['compartilhamentoGrupos'], function($grupo) {
						return $grupo['nomeGrupo'];
					});

					$gruposSelecionados = array_map(function($grupo) {
						return $grupo['idGrupoCompart'];
					}, $grupos_filter);

					break;
				}
            }
        }

	    $dados = array();
	    $dados['central'] = $this->cliente->get_centrais();

	    $resultado = get_listarGrupos($id_cliente);
		$grupo = array();
	    if($resultado){
			$grupo = $resultado['results'];
		}
		$dados['grupos'] = $grupo;
		$dados['centralUpdate'] = $centralUpdate;
		$dados['gruposSelecionados'] = $gruposSelecionados;
		
	    $this->load->view('clientes/editar_central', $dados);
    }

    public function editar_central_cliente(){
	    $idCompartilhamento = $this->input->post('idCompartilhamento');
	    $idCliente = $this->input->post('idCliente');
	    $idCentral = $this->input->post('idCentral');
	    $idGrupoAntigo = $this->input->post('idGrupoAntigo');
	    $idGrupo = $this->input->post('idGrupo');
	    $status = $this->input->post('status');

	    $id_user = $this->auth->get_login_dados('user');

		$idGrupos = json_encode($idGrupo);

		$body = array( 
			"idCliente" => $idCliente, 
			"userCadastro" => $id_user, 
			"idCentral" => $idCentral, 
			"idGrupos" => $idGrupos, 
		);

		$retornoAPI = editarCompartilhamentoGrupo($body);

		// if($idGrupo){
		// 	$body["status"] = 1;
		// 	if($idCompartilhamento){
		// 		$retornoAPI = editarCompartilhamentoGrupo($body);
		// 	}else{
		// 		$retornoAPI = cadastrarCompartilhamentoGrupo($body);
		// 	}
		// }else{
		// 	$body["status"] = 0;
		// 	$body["idGrupo"] = $idGrupoAntigo;
		// 	if($idCompartilhamento){
		// 		$retornoAPI = editarCompartilhamentoGrupo($body);
		// 	}else{
		// 		echo json_encode(array('msg' => 'Erro ao atualizar a central', 'status' => false));
		// 		return;
		// 	}
		// }

        if ($retornoAPI['status'] == 200) {
			echo json_encode(array('msg' => 'Central atualizada com sucesso', 'status' => true));
		} else {
			echo json_encode(array('msg' => 'Erro ao atualizar a central', 'status' => false));
		}
    }


    public function vincular_central_cliente(){
	    $id_cliente = $this->input->post('id_cliente');
	    $id_central = $this->input->post('central');
	    $id_grupo = $this->input->post('grupo');

	    $id_user = $this->auth->get_login_dados('user');

		$body = array(
			"idCliente" => $id_cliente, 
			"idCentral" => $id_central, 
			"idGrupo" => $id_grupo, 
			"userCadastro" => $id_user, 
		);

        if (!$this->cliente->verificar_cliente_central($id_cliente, $id_central)) {
            // verificar se central ja está vinculada ao cliente
			if($id_grupo){
				$retornoAPI = cadastrarCompartilhamentoGrupo($body);
			
				if ($retornoAPI['status'] == 200) {
					$retorno = $this->cliente->cliente_central($id_cliente, $id_central);
					// Grava log de ação de vinculo
					$this->cliente->log_cliente_central([
						'id_cliente' => $id_cliente,
						'id_central' => $id_central,
						'id_user'		=> $id_user,
						'data_cadastro'	=> date('Y-m-d H:i:s'),
						'acao' 			=> '1'
					]);
	
					echo json_encode(array('msg' => 'Central vinculada com sucesso', 'status' => true));
				} else {
					echo json_encode(array('msg' => 'Erro ao vincular a central', 'status' => false));
				}
			}else{
				$retorno = $this->cliente->cliente_central($id_cliente, $id_central);
				if($retorno){
					// Grava log de ação de vinculo
					$this->cliente->log_cliente_central([
						'id_cliente' => $id_cliente,
						'id_central' => $id_central,
						'id_user'		=> $id_user,
						'data_cadastro'	=> date('Y-m-d H:i:s'),
						'acao' 			=> '1'
					]);
	
					echo json_encode(array('msg' => 'Central vinculada com sucesso', 'status' => true));
				}else {
					echo json_encode(array('msg' => 'Erro ao vincular a central', 'status' => false));
				}
			}
        }else{
            echo json_encode(array('msg' => 'Central já vinculada ao cliente, verifique o status!', 'status' => false));
        }
    }

    public function get_centrais_cliente($id_cliente){
	    $dados = array();

		$centrais = get_centrais_cliente($id_cliente);
	    if ($centrais['status'] == 200) {
            foreach ($centrais['dados'] as $central) {
				$grupos = $central['compartilhamentoGrupos'];

				$grupos_filter = array_filter($grupos, function($grupo) {
					return $grupo['nomeGrupo'];
				});

				$gruposText = "-";

				if(count($grupos_filter) > 0){
					$gruposText = "";
				}else{
					$gruposText = "-";
				}

				$count = 1;
				foreach ($grupos_filter as $grupo) {
					if($count == count($grupos_filter)){
						$gruposText .= $grupo['nomeGrupo'];
					}else{
						$gruposText .= $grupo['nomeGrupo'] .  " / ";
					}
					$count ++;
				}

				$status = strtoupper($central['statusClientCentral']);
				$acao = $status  == "ATIVO" ? '<button class="btn btn-success desativarCentral" data-id="'.$central['idClientCentral'].'" title="Desativar"><i class="fa fa-check"></i></button>'
					: '<button class="btn btn-danger ativarCentral"  data-id="'.$central['idClientCentral'].'" title="Ativar"><i class="fa fa-ban"></i></button>';

				$acao = $acao . '<button class="btn btn-info editarCentral" title="Editar" style="margin-left: 5px;" data-target="#editar_central" data-id="'.$central['idClientCentral'].'" data-modal="#centralEditar" onclick="renderEdit(this)" data-url="'.site_url('clientes/editar_centrais').'" ><i class="fa fa-edit"></i></button>';

                $dados[] = array(
                    $central['nomeCentral'],
                    $central['ipCentral'],
                    $central['portaCentral'],
                    // $central['nomeGrupo'] && $status == "ATIVO" ? $central['nomeGrupo'] : "-",
                    $gruposText && $status == "ATIVO" ? $gruposText : "-",
                    $central['statusClientCentral'],
                    $acao,
                );
            }
        }

		// $centrais = $this->cliente->get_centrais_cliente($id_cliente);
	    // if ($centrais) {
        //     foreach ($centrais as $central) {
        //         $dados[] = array(
        //             $central->nome,
        //             $central->ip,
        //             $central->porta,
        //             $central->status == 1 ? 'Ativo' : 'Inativo',
        //             $central->status == 1 ? '<button class="btn btn-success desativarCentral" data-id="'.$central->id.'" title="Desativar"><i class="fa fa-check"></i></button>'
        //                 : '<button class="btn btn-danger ativarCentral"  data-id="'.$central->id.'" title="Ativar"><i class="fa fa-ban"></i></button>',
        //         );
        //     }
        // }
		
	    echo json_encode(array('data' => $dados));
    }

    public function desativar_central(){
	    $id = $this->input->post('id');

		$registro = $this->cliente->getRegistroVinculoCentral($id);
		if ($registro) {
			$retorno = $this->cliente->desativar_central($id);

			// Grava log de ação de vinculo
			$this->cliente->log_cliente_central([
				'id_cliente' => $registro->id_cliente,
				'id_central' => $registro->id_central,
				'id_user'		=> $this->auth->get_login_dados('user'),
				'data_cadastro'	=> date('Y-m-d H:i:s'),
				'acao' 			=> '0'
			]);

			echo json_encode($retorno);
		}
    }

	
    public function editar_central(){

	    $id = $this->input->post('id');

		$body = array();
		$registro = editarCompartilhamentoGrupo($body);

		if ($registro) {
			$retorno = $this->cliente->desativar_central($id);
			echo json_encode($retorno);
		}
    }

    public function ativar_central(){
	    $id = $this->input->post('id');
		$registro = $this->cliente->getRegistroVinculoCentral($id);
		
		if ($registro) {
			$retorno = $this->cliente->ativar_central($id);

			if ($retorno){
				// Grava log de ação de vinculo
				$this->cliente->log_cliente_central([
					'id_cliente' => $registro->id_cliente,
					'id_central' => $registro->id_central,
					'id_user'		=> $this->auth->get_login_dados('user'),
					'data_cadastro'	=> date('Y-m-d H:i:s'),
					'acao' 			=> '1'
				]);

				echo json_encode($retorno);
			}		
		}
    }

	public function gerar_chave_api($id_cliente) {
		$key = 'go00840cooswgwk8o4c0kwgws000okkk848kscko';
		$context = stream_context_create(
			array(
				'http' => array(
					'method' => 'PUT',
					'header' => "Content-Type: type=application/json\r\n"
					. "SHOW-API-KEY: $key"
				)
			)
		);
		$records = @file_get_contents("https://192.99.106.10/rest/index.php/chave/key", null, $context);
		if ($records) {
			$records = json_decode($records);
			if ($records->status) {
				$this->db->update('showtecsystem.cad_clientes', array('chave_api' => $records->key),
								array('id' => $id_cliente));
				echo $records->key;
			} else {
				echo $records->message;
			}
		} else {
			echo 'Não foi possível acessar o WS.';
		}
	}	

	public function inativar_clie($id_cliente) {
	    if ($this->auth->is_allowed_block('inativa_clie')) {
            $verifica = $this->cliente->verificaStatusClie($id_cliente);

            if ($verifica) {
                $resultado = $this->cliente->atualiza_status_cliente($id_cliente, 5);
                if ($resultado) {
                    $this->session->set_flashdata('sucesso', 'Cadastro inativado com sucesso.');
                    redirect('clientes');
                } else {
                    $this->session->set_flashdata('erro', 'Não foi possível inativar o cadastro, tente novamente mais tarde..');
                    redirect('clientes');
                }
            } else {
                $this->session->set_flashdata('erro', 'O Cadastro do cliente já se encontra inativado ou cancelado.');
                redirect('clientes');
            }
        } else {
            $this->session->set_flashdata('erro', 'Você não tem permissão para inativar o cliente.');
            redirect('clientes');
        }
	}

    public function inativar_cliente($id_cliente) {
        if ($this->auth->is_allowed_block('inativa_clie')) {
            $verifica = $this->cliente->verificaStatusClie($id_cliente, 5);
            if ($verifica) {
                $resultado = $this->cliente->atualiza_status_cliente($id_cliente, 5);
                if ($resultado) {
                    echo json_encode(array('status' => true, 'msg' => 'Cadastro inativado com sucesso.'));
                } else {
                    echo json_encode(array('status' => false, 'msg' => 'Não foi possível inativar o cadastro, tente novamente mais tarde..'));
                }
            } else {
                echo json_encode(array('status' => false, 'msg' => 'O Cadastro do cliente já se encontra inativado ou cancelado.'));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Você não tem permissão para inativar o cliente.'));
        }
    }

	public function temp() {
		$seriais = array(
			856153,
			856122,
			896893,
			834036,
			947337,
			856166,
			800682,
			837879,
			821715,
			822769,
			821408,
			821685,
			947280,
			800706,
			834075,
			837908,
			803258,
			834062,
			858200,
			849510,
			856131,
			861007,
			832542,
			858110,
			834026,
			834088,
			842833,
			823034,
			856102,
			947389,
			803252,
			834090,
			834022,
			851143,
			856108,
			800293,
			821709,
			834076,
			803244,
			837921,
			854392,
			891983,
			856173,
			850902,
			837277,
			837910,
			852483,
			856097,
			861005,
			823037,
			860982,
			856111,
			851592,
			856100,
			850830,
			852426,
			857862,
			849573,
			847331,
			827600,
			832627,
			852491,
			941200,
			843347,
			837878,
			800294,
			947313,
			860980,
			842537,
			858166,
			856119,
			850934,
			834095,
			842692,
			832073,
			821435,
			842690,
			851123,
			834033,
			854650,
			858177,
			837751,
			947401,
			847376,
			834051,
			800289,
			889420,
			800666,
			834097,
			850870,
			834074,
			827941,
			837768,
			810945,
			897939,
			821409,
			849543,
			947336,
			851200,
			834085,
			947421,
			821706,
			860985,
			841120,
			947184,
		);

		foreach ($seriais as $s) {
			//die(pr($s));
			$insert[$s] = array(
				'marca' => 'OMNILINK',
				'modelo' => 'OM',
				'serial' => 'OM'.$s,
				'status' => 1
			);
		}

		if ($this->cliente->insert_temp($insert))
			echo "Deu tudo certo!";
		else
			echo "Deu tudo Errado :( !";
	}

	function tab_secretarias($id_cliente){
		$dados['id_cliente'] = $id_cliente;
		$this->load->view('clientes/tab_secretarias', $dados);
	}

	function list_secretarias(){
		$id_cliente = $this->input->get('id_cliente');
		if ($id_cliente) {
			$secretarias = $this->cliente->getAjaxListSecretaria($id_cliente);

			if ($secretarias) {
				foreach ($secretarias as $key => $sec) {
					if ($sec->status == 1) {
						$status = '<button type="button" class="btn btn-small status active btn-success" data-status="ativo" data-controller="'.site_url('clientes/ajax_atualiza_status_secretaria/'.$sec->id.'/1').'">Ativo</button>
	                               <button type="button" class="btn btn-small status data-status="inativo" data-controller="'.site_url('clientes/ajax_atualiza_status_secretaria/'.$sec->id.'/0').'">Inativo</button>';
					}else{
						$status = '<button type="button" class="btn btn-small status" data-status="ativo" data-controller="'.site_url('clientes/ajax_atualiza_status_secretaria/'.$sec->id.'/1').'">Ativo</button>
	                               <button type="button" class="btn btn-small status active btn-danger" data-status="inativo" data-controller="'.site_url('clientes/ajax_atualiza_status_secretaria/'.$sec->id.'/0').'">Inativo</button>';
					}
					$data['table'][] = array(
						'id'     => $sec->id,
						'nome'   => $sec->nome,
						'status' => '<div class="btn-group" data-toggle="buttons-radio">
	                                    '.$status.'
	                                </div>',
						'editar' => '<button type="button" class="btn btn-small btn-primary edit_sec" data-toggle="modal" data-target="#editar_secretaria" data-id="'.$sec->id.'" data-nome="'.$sec->nome.'" >Editar</button>'

					);
				}

				echo json_encode($data);
			}else {
				return false;
			}
		}else {
			return false;
		}
	}

	public function ajax_atualiza_status_secretaria($id, $status) {
		$dados = array(
			'status' => $status
		);
		$up = $this->cadastro->atualiza_grupo($id, $dados);
		if( $up ){
			echo json_encode(array('success' => true, 'msg' => 'Status da secretaria #'.$id.' alterado com sucesso.'));
		}else {
			echo json_encode(array('success' => false, 'msg' => 'Selecione um status diferente do atual'));
		}
    }

	public function ajax_atualiza_nome_secretaria() {
		$id = $this->input->get('id');
		$nome = $this->input->get('nome');
		$dados = array(
			'nome' => $nome
		);
		$up = $this->cadastro->atualiza_grupo($id, $dados);
		if( $up ){
			echo json_encode(array('success' => true, 'msg' => 'Secretaria #'.$id.' atualizada com sucesso.'));
		}else {
			echo json_encode(array('success' => false, 'msg' => 'Erro ao atualizar a secretaria #'.$id));
		}
    }

	function get_ajax_secretaria(){
		$id_cliente = $this->input->get('id_cliente');
		if ($id_cliente) {
			$secretarias = $this->cliente->getAjaxListSecretaria($id_cliente);
			if ($secretarias) {
				foreach ($secretarias as $key => $sec) {
					$data['results'][] = array(
						'id' => $sec->id.' - '.$sec->nome,
						'text' => $sec->nome
					);
				}

				echo json_encode($data);
			}else {
				return false;
			}
		}else {
			return false;
		}
	}

	function add_secretaria(){
		if ($this->input->post()) {
			$nome = $this->input->post('nome');
			$id_cliente = $this->input->post('id_cliente');
			$this->cliente->add_secretaria($nome, $id_cliente);
			echo json_encode(array('status' => 'OK', 'msg' => 'Secretaria Cadastrada com sucesso.'));
		}else {
			echo json_encode(array('status' => 'ERRO', 'msg' => 'Nenhum parâmetro foi passado. Verifique e tente novamente!'));
		}
	}

	/*
    * LISTA CLIENTE - SELECT2 BUSCA PELO NOME OU ID DO CLIENTE
    */
    public function listAjaxSelectClient() {
        $data = array('results' => array());

        if ($search = $this->input->get('term')) { # Se usuário realizar busca no select
            $clientes = $this->cliente->listarClientesFilter($search); # Filtra clientes
        } else { # Se não vir filtro
            $clientes = $this->cliente->listar(array(), 0, 50); # Lista 50 registros
        }

        if ($clientes) {
            foreach ($clientes as $key => $cliente) {
                $data['results'][] = array(
                    'id' => $cliente->id.' - '.$cliente->nome,
                    'text' => $cliente->id.' - '.$cliente->nome
                );
            }
        }

        echo json_encode($data);
    }

	/*
    * SALVA UM EMAIL DO CLIENTE
    */
    public function save_email() 
	{
		$this->load->model('email_model');
	    if( $dados = $this->input->post() ){

			$dados += array(
				'data_criado' => date('Y-m-d'),
				'hora_criado' => date('H:i:s')
			);

			if ($id_insert = $this->email_model->insertEmail($dados ))
				exit(json_encode( array('success' => true, 'msg' => lang('email_cadastrado_sucesso'), 'id_insert' => $id_insert) ));
			else {
				exit(json_encode( array('success' => false, 'msg' => lang('email_nao_cadastrado')) ));
			}
		}
		
		exit( json_encode( array('success' => false, 'msg' => lang('erro_params')) ) );	
    }

	/*
    * CADASTRA UM TELEFONE DO CLIENTE
    */
    public function save_telefone() 
	{
		$this->load->model('telefone');
	    if( $dados = $this->input->post() ){

			$dados += array(
				'data_criado' => date('Y-m-d'),
				'hora_criado' => date('H:i:s')
			);

			if ($id_insert = $this->telefone->insertTelefone($dados))
				exit(json_encode( array('success' => true, 'msg' => lang('telefone_cadastrado_sucesso'), 'id_insert' => $id_insert) ));
			else {
				exit(json_encode( array('success' => false, 'msg' => lang('telefone_nao_cadastrado')) ));
			}
		}

		exit( json_encode( array('success' => false, 'msg' => lang('erro_params')) ) );	
    }

	/*
    * REMOVE UM EMAIL DO CLIENTE
    */
    public function remover_email($id_email) {	    
        $this->load->model('email_model');
		//atualiza o email para lixo	
		if ($this->email_model->atulizarEmail($id_email, array('lixo' => '1' ) )) {
			exit(json_encode( array('success' => true, 'msg' => lang('email_removido_sucesso')) ));
		}
		exit(json_encode( array('success' => false, 'msg' => lang('email_removido_falha')) ));
    }

	/*
    * REMOVE UM TELEFONE DO CLIENTE
    */
    public function remover_telefone($id_telefone) {
        $this->load->model('telefone');
		if ($this->telefone->atulizarTelefone($id_telefone, array('lixo' => '1' ) )) {
			exit(json_encode( array('success' => true, 'msg' => lang('telefone_removido_sucesso')) ));
		}
		exit(json_encode( array('success' => false, 'msg' => lang('telefone_removido_falha')) ));
    }

	/*
    * ATUALIZA UM EMAIL DO CLIENTE
    */
    public function update_email($id_email) 
	{
		$this->load->model('email_model');
	    if( $dados = $this->input->post() ){

			$dados += array(
				'data_modificado' => date('Y-m-d'),
				'hora_modificado' => date('H:i:s')
			);

			if ($this->email_model->atulizarEmail($id_email, $dados ))
				exit(json_encode( array('success' => true, 'msg' => lang('email_atualizado_sucesso')) ));
			else {
				exit(json_encode( array('success' => false, 'msg' => lang('email_nao_atualizado')) ));
			}
		}
		
		exit( json_encode( array('success' => false, 'msg' => lang('erro_params')) ) );	
    }

	/*
    * ATUALIZA UM TELEFONE DO CLIENTE
    */
    public function update_telefone($id_telefone) 
	{
		$this->load->model('telefone');
	    if( $dados = $this->input->post() ){

			$dados += array(
				'data_modificado' => date('Y-m-d'),
				'hora_modificado' => date('H:i:s')
			);

			if ($this->telefone->atulizarTelefone($id_telefone, $dados ))
				exit(json_encode( array('success' => true, 'msg' => lang('telefone_atualizado_sucesso')) ));
			else {
				exit(json_encode( array('success' => false, 'msg' => lang('telefone_nao_atualizado')) ));
			}
		}

		exit( json_encode( array('success' => false, 'msg' => lang('erro_params')) ) );	
    }

	
	public function logar_usuario() {
		$dados = $this->input->post();
		
		$id_user_logado = $this->auth->get_login_dados('user');
		$id_user_logado = (int) $id_user_logado;
		$dados['id_user_logado'] = $id_user_logado;

		if (!$dados) {
			exit(json_encode( array('success' => false, 'msg' => lang('erro_params')) ));
		}

		$body = array(
			'usuario' => $dados['usuario'],
			'senha' => $dados['senha'],
			'ip' => $dados['ip'],
			'origem' => $dados['origem']
		);
		
		$resposta = from_relatorios_api($body, "autenticacao/gestor/login");
    	echo $resposta;
	}

	public function salvar_auditoria(){
		$dados = $this->input->post();
		$id_user_logado = $this->auth->get_login_dados('user');
		$id_user_logado = (int) $id_user_logado;
		$dados['id_user_logado'] = $id_user_logado;

		$auditoria = salvar_auditoria($dados);
		echo json_encode($auditoria);
	}

	

}
