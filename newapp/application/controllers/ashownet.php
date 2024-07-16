<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ashownet extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('ashownett');
		$this->load->model('arquivo');		
		$this->load->model('cadastro');	
		$this->load->model('usuario');
		$this->load->model('mapa_calor');
        $this->auth->is_logged('admin');
        $this->load->model('auth');
	}

	public function sobre_old(){
		$this->auth->is_logged('admin');
		$dados['titulo'] = 'Sobre';

		$dados['sobre'] = $this->ashownett->getDados('cad_sobre_empresa');

		$this->load->view('fix/header4', $dados);
		$this->load->view('ashownet/sobreaempresa');
		$this->load->view('fix/footer4');

	}
	public function sobre(){
		//$this->auth->is_logged('admin');
		$dados['titulo'] = lang('sobre');

		$dados['sobre'] = $this->ashownett->getDados('cad_sobre_empresa');

		$this->load->view('fix/header-new', $dados);
		$this->load->view('ashownet/sobreaempresa');
		$this->load->view('fix/footer_new');

	}

	public function missao_visao(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Missã, Visão e Valores';

	    $dados['sobre'] = $this->ashownett->getDados('cad_sobre_empresa');

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/missaoevisao');
	    $this->load->view('fix/footer4');

	}

	public function contatos_corporativos(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = lang('contatos_corporativos').' - '.lang('nome_aplicacao');
		$dados['load'] = array('buttons_html5');
		
		$this->load->view('fix/header-new', $dados);
	    $this->load->view('ashownet/contatoscorporativos');
	    $this->load->view('fix/footer_new');

	}

	//LISTA OS DADOS DA TABELA DE DADOS DE ATENDIMENTO CLIENTES
	public function ajaxAtendimentoOuProjetos(){
		$table = array();
		$tipo = $this->input->post('tipo');
	    $listAtendimentos = $this->ashownett->ajaxAtendimentoOuProjetos($tipo);
		if ($listAtendimentos) {
			foreach ($listAtendimentos as $key => $atend) {
				$btnAdmin = '';
				if ($this->auth->is_allowed_block('cad_contatos_corporativos')) {
					$btnAdmin = '<button class="btn btn-primary btnEditContatoCorp" data-id="'.$atend->id.'" data-tipo="'.$tipo.'" title="'.lang('Editar').'"><i class="fa fa-edit"></i></button>
								<button class="btn btn-danger btnExcluiContatoCorp" data-id="'.$atend->id.'" data-tipo="'.$tipo.'" title="'.lang('Remover').'"><i class="fa fa-remove"></i></button>';
				}
				$table['data'][] = array(
					'titulo' => $atend->titulo,
					'descricao' => $atend->descricao,
					'admin' => $btnAdmin
				);
			}
		}
		echo json_encode($table);
	}


	//LISTA OS DADOS DA TABELA DE DADOS DE ATENDIMENTO CLIENTES
	public function ajaxDadosMatrizFiliais(){
		$table = array();
		$loja = $this->input->post('loja');
	    $dadosLojas = $this->ashownett->getDadosMatrizFiliais($loja);
		if ($dadosLojas) {
			foreach ($dadosLojas as $key => $loj) {
				$btnAdmin = '';
				if ($this->auth->is_allowed_block('cad_contatos_corporativos')) {
					$btnAdmin = '<button class="btn btn-primary btnEditContatoCorp" data-id="'.$loj->id.'" data-tipo="'.$loja.'" title="'.lang('Editar').'"><i class="fa fa-edit"></i></button>
								<button class="btn btn-danger btnExcluiContatoCorp" data-id="'.$loj->id.'" data-tipo="'.$loja.'" title="'.lang('Remover').'"><i class="fa fa-remove"></i></button>';
				}
				$table['data'][] = array(
					'endereco' => '<b>'.$loj->cidade.' - '.$loj->uf.'</b><br>'.$loj->complemento.'<br>'.$loj->endereco.', '.$loj->numero.', '.$loj->bairro.'<br>'.$loj->cidade.' - '.$loj->uf.' CEP: '.$loj->cep.'<br>',
					'telefone' => $loj->telefone,
					'cnpj' => $loj->cnpj,
					'admin' => $btnAdmin
				);
			}
		}
		echo json_encode($table);
	}

	public function empresa_folhetos(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Empresa follhetos';

	    $dados['lista_folhetos'] = $this->ashownett->getFolhetos();
	    $this->load->view('fix/header-new', $dados);
	    $this->load->view('ashownet/folhetos');
	    $this->load->view('fix/footer_new');
	    
	}
	public function ajaxListFolhetos()
	{
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		$folhetos = $this->arquivo->get_folhetos();
		$data = array();
		foreach ($folhetos as $folheto){
			$row = array();
			$row[] = '<a href="'.base_url("uploads/folhetos/$folheto->file").'"><iframe src="'.base_url("uploads/folhetos/$folheto->file").'"width="200" height="200" style="border: none;"></iframe></a>';
			$row[] = $folheto->descricao;
			$row[] = '<div style="display: inline-block;">
						<a  href="'.base_url("uploads/folhetos/$folheto->file").'" class="btn btn-primary btn-download-folheto" 
							folheto_id="'.$folheto->id.'"
							data-folheto_id="'.$folheto->id.'">
							<i class="fa fa-download"></i>
						</a>
						<button class="btn btn-primary btn-edit-folheto" 
							folheto_id="'.$folheto->id.'"
							data-folheto_id="'.$folheto->id.'">
							<i class="fa fa-edit"></i>
						</button>
						<button class="btn btn-danger btn-del-folheto" 
							folheto_id="'.$folheto->id.'"
							data-folheto_id="'.$folheto->id.'">
							<i class="fa fa-times"></i>
						</button>
					</div>';
			$data[] = $row;
		}
		$json = array(
			"draw" => $this->input->post("draw"),
			"data" => $data,
		);

		echo json_encode($json);
		
	}
	public function ajax_save_folheto(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		$json = array();
		$json["status"] = 1;
		$json["error_list"] = array();
		$data = $this->input->post();
		//die(json_encode(array($data)));
		if (empty($data["descricao"])) {
			$json["error_list"]["#descricao"] = "Descrição é obrigatorio!";
		} 
		if($data["arquivo_folheto"] == "" &&  $data["arquivo_folheto"] == NULL ){
			if ($this->cadastro->verificaCadastroArquivo($data["descricao"], 'arquivos', 'folheto') == FALSE) {
				$this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Desculpe esse arquivo já foi cadastrado!</div>');
				redirect('ashownet/empresa_folhetos');
			}
		}else{
			$nome_arquivo = basename($data["arquivo_folheto"]);
			$path = $data["arquivo_folheto"];
			$retorno = $this->cadastro->digitalizacaoFolheto($data["descricao"], $nome_arquivo, $path);
			echo json_encode($json);
		}

	}
	public function ajax_edit_folheto(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		$json = array();
		$json["status"] = 1;
		$json["error_list"] = array();
		$folheto_id = $this->input->post("folheto_id");
		$descricao_edit = $this->input->post("descricao_edit");
		$arquivo_folheto_edit = $this->input->post("arquivo_folheto_edit");
		$nome_arquivo = basename($arquivo_folheto_edit);
		$data = $this->input->post();
		//die(json_encode(array($data)));
		if (empty($data["descricao_edit"])) {
			$json["error_list"]["#descricao"] = "Descrição é obrigatorio!";
		} 
			$retorno = $this->cadastro->editArquivoFormulario($descricao_edit, $nome_arquivo, $arquivo_folheto_edit, 'folhetos', $folheto_id);
			//$retorno = $this->cadastro->digitalizacaoFolheto($data["descricao"], $nome_arquivo, $path);
			echo json_encode($json);
	}

	

	public function ajax_folhetos_data(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$json = array();
		$json["status"] = 1;
		$json["input"] = array();

		$folheto_id = $this->input->post("folheto_id");
		$data = $this->arquivo->get_folhetos_by_id($folheto_id)->result_array()[0];
		//die(json_encode(array($data)));
		$json["input"]["folheto_id"] = $data["id"];
		$json["input"]["descricao_edit"] = $data["descricao"];
		$json["input"]["arquivo_folheto_edit"] = $data["path"];
		$json["input"]["name_arquivo_folheto_edit"] = $data["file"];

		echo json_encode($json);

	}
	public function ajax_import_arq_folheto(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$config["upload_path"] = "./uploads/folhetos/";
		$config["allowed_types"] = "pptx|ppt|pdf|doc|docx|xlsx|xls|png|jpg";
		$config["overwrite"] = TRUE;
		$config['encrypt_name'] = 'true';
		$this->upload->initialize($config);
		$this->load->library("upload", $config);

		$json = array();
		$json["status"] = 1;

		if (!$this->upload->do_upload("folheto_file")) {
			$json["status"] = 0;
			$json["error"] = $this->upload->display_errors("","");
		} else {
			if ($this->upload->data()["file_size"] <= 10024) {
				$file_name = $this->upload->data()["file_name"];
				$json["arq_path"] = base_url() . "uploads/folhetos/" . $file_name;
			} else {
				$json["status"] = 0;
				$json["error"] = "Arquivo não deve ser maior que 1 MB!";
			}

		}

		echo json_encode($json);
	}
	public function ajax_delete_folheto_folder(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		$name_arquivo_folheto_edit = $this->input->post("name_arquivo_folheto_edit");
		$json = array();
		$json["status"] = 1;
		$caminho = './uploads/folhetos/'. $name_arquivo_folheto_edit;
		if (!unlink($caminho)) {
			$json["status"] = 0;
			$json["error"] = "Arquivo não conseguiu ser modificado";
		 } 

		echo json_encode($json);

	}

	public function apresentacoes(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Apresentações';
	    $dados['apresentacoes'] = $this->arquivo->getApresentacao();

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/apresentacoes');
	    $this->load->view('fix/footer4');

	}

	public function politicas_formularios(){
	    $this->auth->is_logged('admin');
	    $this->load->model('ashownett');
	    $dados['titulo'] = 'Políticas e Formulários';
	    $dados['assuntos'] = $this->ashownett->getAssuntos('cad_assuntos');

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/politicaseformularios/listar_pf');
	    $this->load->view('fix/footer4');

	}

	public function produtos(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Produtos';
		//$dados['lista_produtos'] = $this->ashownett->getProdutos();
		$dados['lista_produtos'] = $this->ashownett->getProdutosAll();
		//die(json_encode((array($dados))));

	    $this->load->view('fix/header-new', $dados);
	    $this->load->view('ashownet/produtos');
	    $this->load->view('fix/footer_new');

	}

	public function produto_view($id_assunto){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Produtos';

	    $dados['lista_produtos'] = $this->ashownett->getProdutosView($id_assunto);

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/produtos_view');
	    $this->load->view('fix/footer4');

	}

	public function ajax_save_produto(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		$json = array();
		$json["status"] = 1;
		$json["error_list"] = array();
		$data = $this->input->post();
		//die(json_encode(array($data)));
		if (empty($data["nome"])) {
			$json["error_list"]["#nome"] = "Nome é obrigatorio!";
		} else{
			$nome_arquivo = basename($data["arquivo_produto"]);
			$path = $data["arquivo_produto"];
			$nome = $data["nome"];
			$descricao = $data["descricao"];
			$retorno = $this->ashownett->addNewProduto($nome,$descricao,$nome_arquivo,$path);
			//die(json_encode(array($retorno)));
			if($retorno["success"]){
				$json["id_cadastro"] = $retorno["id"];
				$json["id_assunto"] = $retorno["id_assunto"];
				$json["id_produto"] = $retorno["id_produto"];
				$json["msg"] = $retorno["msg"];
			}else{
				$json["status"] = 0;
				$json["error_list"] = $retorno["msg"];
			}
			
		}
		echo json_encode($json);

	}
	public function ajax_edit_produto(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		$json = array();
		$json["status"] = 1;
		$json["error_list"] = array();
		$data = $this->input->post();
		//die(json_encode(array($data)));
		if (empty($data["nome_arq_produto"])) {
			$json["error_list"]["#nome"] = "Nome é obrigatorio!";
		} else{

			if($data["arquivo_produt"] != $data["arquivo_produt_old"]){
				$nome_arquivo_old = basename($data["arquivo_produt_old"]);
				$caminho = './uploads/produtos/'. $nome_arquivo_old;
				unlink($caminho);
			}
			$id_produt = $data["id_prod_edit"];
			$nome_arquivo = basename($data["arquivo_produt"]);
			$path = $data["arquivo_produt"];
			$nome = $data["nome_arq_produto"];
			$descricao = $data["descricao_arq_produto"];
			$retorno = $this->ashownett->addEditProduto($id_produt,$nome,$descricao,$nome_arquivo,$path);
			//die(json_encode(array($retorno)));	
			if($retorno["success"]){
				$json["id_assunto"] = $retorno["id_assunto"];
				$json["id_produto"] = $retorno["id_produto"];
				$json["msg"] = $retorno["msg"];
			}else{
				$json["status"] = 0;
				$json["error_list"] = $retorno["msg"];
			}
			
		}
		echo json_encode($json);

	}



	public function ajax_import_arq_produto(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$config["upload_path"] = "./uploads/produtos/";
		$config["allowed_types"] = "pptx|ppt|pdf|doc|docx|xlsx|xls|png|jpg";
		$config["overwrite"] = TRUE;
		$config['encrypt_name'] = 'true';
		$this->upload->initialize($config);
		$this->load->library("upload", $config);

		$json = array();
		$json["status"] = 1;

		if (!$this->upload->do_upload("produto_file")) {
			$json["status"] = 0;
			$json["error"] = $this->upload->display_errors("","");
		} else {
			if ($this->upload->data()["file_size"] <= 10024) {
				$file_name = $this->upload->data()["file_name"];
				$json["arq_path"] = base_url() . "uploads/produtos/" . $file_name;
			} else {
				$json["status"] = 0;
				$json["error"] = "Arquivo não deve ser maior que 1 MB!";
			}

		}

		echo json_encode($json);
	}

	public function ajax_list_arq_produtos(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		
	    
		$id_assunto = $this->input->post('id');
		
		$produtos = $this->ashownett->getProdutosView($id_assunto);
		$data = array();
		foreach ($produtos as $produto){
			$arquivo = explode(".", $produto->file);
				$extensao = $arquivo[1];
				if(!($extensao == 'jpg' || $extensao == 'png' || $extensao == 'jpeg' || $extensao == 'JPG' || $extensao == 'PNG' || $extensao == 'JPEG')){
					$row = array();
					$row[] = '<iframe src="'.base_url("uploads/produtos/$produto->file").'"width="200" height="200" style="border: none;"></iframe>';
					$row[] = $produto->descricao;
					$row[] = '<div style="display: inline-block;">
								<a  href="'.base_url("uploads/produtos/$produto->file").'" class="btn btn-primary btn-download-produto" 
									produto="'.$produto->id.'"
									data-produto_id="'.$produto->id.'">
									<i class="fa fa-download"></i>
								</a> &nbsp;'.
								// <button class="btn btn-primary btn-edit-produto" 
								// produto_id="'.$produto->id.'"
								// 	data-produto_id="'.$produto->id.'">
								// 	<i class="fa fa-edit"></i>
								// </button>
								'<button class="btn btn-danger btn-del-produto" 
								produto_id="'.$produto->id.'"
									data-produto_id="'.$produto->id.'">
									<i class="fa fa-times"></i>
								</button>
							</div>';
						$data[] = $row;
				}
		}
		$json = array(
			"draw" => $this->input->post("draw"),
			"data" => $data,
		);

		echo json_encode($json);

	}
	public function get_data_product(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$json = array();
		$json["status"] = 1;
		$json["input"] = array();

		$id = $this->input->post("id");
		$id_produto = $this->input->post("id_produt");
		$data = $this->ashownett->getProdutoById($id,$id_produto);
		foreach($data as $input){
			$arquivo = explode(".", $input->file);
				$extensao = $arquivo[1];
				if(($extensao == 'jpg' || $extensao == 'png' || $extensao == 'jpeg' || $extensao == 'JPG' || $extensao == 'PNG' || $extensao == 'JPEG')){
					$json["input"]["file"] = $input->file;
					$json["input"]["path"] = $input->path;
					$json["input"]["assunto"] = $input->assunto;
					$json["input"]["descricao"] = $input->descricao;
					$json["input"]["id"] = $input->id;
					$json["input"]["id_produto"] = $input->id_produto;
				}

		}
		echo json_encode($json);

		//die(json_encode(array($data)));
		//die(json_encode(array($json["input"]["file"])));
	}


	public function ajax_save_arq_produto(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		$json = array();
		$json["status"] = 1;
		$json["error_list"] = array();
		$data = $this->input->post();
		//die(json_encode(array($data)));
		if (empty($data["descricao_arq"])) {
			$json["error_list"]["#descricao"] = "Descrição é obrigatorio!";
		} else{
			$id_assunto = $data["id_produt_edit"];
			$nome_arquivo = basename($data["arquivo_produto"]);
			$path = $data["arquivo_produto"];
			$nome = $data["nome_arq"];
			$descricao = $data["descricao_arq"];
			$retorno = $this->ashownett->addNewArqProduto($id_assunto,$descricao,$nome_arquivo,$path);
			//die(json_encode(array($retorno)));
			if($retorno["success"]){
				$json["id_assunto"] = $retorno["id_assunto"];
				$json["id_produto"] = $retorno["id_produto"];
				$json["msg"] = $retorno["msg"];
			}else{
				$json["status"] = 0;
				$json["error_list"] = $retorno["msg"];
			}
			
		}
		echo json_encode($json);

	}


	public function engenharia_suporte(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Engenharia Suporte';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'engenharia_suporte'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/engenharia_suporte');
	    $this->load->view('fix/footer4');

	}

	public function engenharia_teste_homologacao(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Engenharia Teste e Homologação';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'engenharia_teste_homologacao'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/engenharia_teste_homologacao');
	    $this->load->view('fix/footer4');

	}

	public function espaco_ti(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Espaço TI';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'espaco_ti'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/espaco_ti');
	    $this->load->view('fix/footer4');

	}

	public function marketing_briefing(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Marketing Briefing';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'marketing_briefing'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/marketing_briefing');
	    $this->load->view('fix/footer4');

	}

	public function marketing_campanhas(){
	    $this->auth->is_logged('admin');
	    $this->load->model('ashownett');
	    $dados['titulo'] = 'Marketing Campanhas';
	    $dados['assuntos'] = $this->ashownett->getAssuntos('cad_assunto_campanhas');

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/marketing/campanhas/listar_campanhas');
	    $this->load->view('fix/footer4');

	}

	public function apresentacoes_comerciais(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Apresentações Comerciais';
	    $dados['apresentacoes'] = $this->ashownett->getApresentacao('cad_apresentacao_comerciais');
		$this->mapa_calor->registrar_acessos_url(site_url('/ashownet/apresentacoes_comerciais'));
	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/apresentacoes_comerciais');
	    $this->load->view('fix/footer4');

	}

	public function comite_guerra(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Comitê de Guerra';
	    $dados['apresentacoes'] = $this->ashownett->getApresentacao('cad_comite_guerra');

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/comite_guerra');
	    $this->load->view('fix/footer4');

	}

	public function televendas_comunicados(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Televendas Comunicados';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'televendas_comunicados'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/televendas_comunicados');
	    $this->load->view('fix/footer4');

	}

	public function propostas_comerciais(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Propostas Comerciais';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'propostas_comerciais'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/propostas_comerciais');
	    $this->load->view('fix/footer4');

	}

	public function politicas_procedimentos(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Política e Procedimentos Comerciais';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'politicas_procedimentos'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/politicas_procedimentos');
	    $this->load->view('fix/footer4');

	}

	public function guia_produtos(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Guia de Produtos';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'guia_produtos'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/guia_produtos');
	    $this->load->view('fix/footer4');

	}

	public function precos_acessorios(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Tabela de Preços e Acessórios';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'precos_acessorios'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/precos_acessorios');
	    $this->load->view('fix/footer4');

	}

	public function inteligencia_mercado(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Inteligência de Mercado';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'inteligencia_mercado'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/inteligencia_mercado');
	    $this->load->view('fix/footer4');

	}

	public function governanca_corporativa(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Governança Corporativa';

	    $dados['lista_arquivos'] = $this->arquivo->getArquivo("pasta = 'governanca_corporativa'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/governanca_corporativa');
	    $this->load->view('fix/footer4');

	}

	public function gente_gestao(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Gente & Gestão';

	    $dados['dados'] = $this->ashownett->getDados('cad_gente_gestao');

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/gente_gestao');
	    $this->load->view('fix/footer4');

	}

	public function desenv_organizacional(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Desenvolvimento Organizacional';

	    $dados['dados'] = $this->arquivo->getArquivo("pasta = 'gente_gestao/desenv_organizagional'");
	    $dados['parcerias'] = $this->arquivo->getParceria();

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/desenv_organizacional');
	    $this->load->view('fix/footer4');

	}

	public function treinamentos(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Treinamentos';

	    $dados['dados'] = $this->arquivo->getArquivo("pasta = 'gente_gestao/desenv_organizagional/treinamentos'");

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/treinamentos');
	    $this->load->view('fix/footer4');

	}

	public function politicas_formulariosRH(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Políticas e Formulários';

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/politicas_formulariosrh');
	    $this->load->view('fix/footer4');

	}

	public function plano_de_voo(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Plano de Voo';

	    $dados['planos'] = $this->ashownett->getDados('plano_de_voo_questionario');

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/plano_de_voo');
	    $this->load->view('fix/footer4');

	}

	public function conectando_liderancas(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Conectando Lideranças';

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/conectando_liderancas');
	    $this->load->view('fix/footer4');

	}

	public function adm_pessoal(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Administração de Pessoal';

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/adm_pessoal');
	    $this->load->view('fix/footer4');

	}

	public function central_unimed(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Central Nacional Unimed';

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/gente_gestao/adm_pessoal/unimed/lista_unimed');
	    $this->load->view('fix/footer4');

	}

	public function central_bradesco(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Bradesco Saúde';

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/gente_gestao/adm_pessoal/bradesco/lista_bradesco');
	    $this->load->view('fix/footer4');

	}

	public function correcao_irrf(){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Correção Imposto de Renda';

	    $dados['dados'] = $this->ashownett->getDados('cad_correcao_irrf');

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/correcao_irrf');
	    $this->load->view('fix/footer4');

	}

	public function formulario_adp(){
	    $this->auth->is_logged('admin');

	    $dados['titulo'] = 'Formulário ADP';

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/formulario_adp');
	    $this->load->view('fix/footer4');

	}

	public function docs_pendentes($id){
	    $this->auth->is_logged('admin');
	    $dados['titulo'] = 'Documentos Pendentes';

	    $dados['lista_dados'] = $this->ashownett->getDadosEdit('cad_docs_pendentes', array('id_funcionario' => $id, 'recebido' => 0));

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/docs_pendentes');
	    $this->load->view('fix/footer4');

	}

	public function desconto_coparticipacao($id){
	    $this->auth->is_logged('admin');

	    $dados['titulo'] = 'Descontos de Coparticipação';

	    $dados2 = $this->input->post();
	    $mes = $dados2['mesCpt'];

	    $dados['lista_dados'] = $this->ashownett->getDadosEditDesconto($id, $mes);

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/desconto_coparticipacao');
	    $this->load->view('fix/footer4');

	}

	public function atividades($id){
	    $this->auth->is_logged('admin');

	    $dados['titulo'] = 'Meus Treinamentos';

	    $dados['lista_dados'] = $this->ashownett->getDadosAtividades($id);

	    $this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/gente_gestao/desenv_organizacional/atividades/atividades');

	    $this->load->view('fix/footer4');

	}

	public function iso(){
	    $this->auth->is_logged('admin');

	    $dados['titulo'] = 'Arquivos ISO - Controle de Qualidade';

	    $dados['lista_dados'] = $this->ashownett->getArquivosIso();

		$this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/iso');

	    $this->load->view('fix/footer4');

	}

	public function comercial(){
	    $this->auth->is_logged('admin');

	    $dados['titulo'] = 'Comercial';

	    $dados['lista_dados'] = $this->ashownett->getArquivosComercial();

		$this->load->view('fix/header4', $dados);
	    $this->load->view('ashownet/comercial');

	    $this->load->view('fix/footer4');

	}

	public function ajax_list_funcionarios(){
		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}
		
		//$funcionarios = $this->ashownett->getFuncionarios();
		$funcionarios = $this->usuario->listar();
		// die(json_encode(array($funcionarios)));
		$data = array();
		foreach($funcionarios as $funcionario){
			$row = array();
			$row[] = $funcionario->nome;
			$row[] = $funcionario->ocupacao;
			$row[] = $funcionario->login;
			$data[] = $row;

		}
		$json = array(
			"draw" => $this->input->post("draw"),
			"data" => $data,
		);

		echo json_encode($json);

	}

}
