<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cadastro_fornecedor extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();
		$this->auth->is_logged('admin');
		$this->load->model('auth');
		$this->load->library('form_validation');
		$this->load->model('cadastrar_fornecedor');
		$this->load->model('conta');
		$this->load->model('mapa_calor');
	}

	public function add()
	{
		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('fornecedores/cadastrarFornecedores');
		$this->load->view('fix/footer_NS');
	}
	public function index()
	{
		$dados['titulo'] = lang('fornecedores');
		$dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
		$this->mapa_calor->registrar_acessos_url(site_url('/cadastro_fornecedor'));
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('fornecedores/listar');
		$this->load->view('fix/footer_NS');
	}

	public function inserir_fornecedor()
	{

		$this->form_validation->set_rules('nome', 'nome');
		$this->form_validation->set_rules('razaosocial', 'razaosocial');
		$this->form_validation->set_rules('cnpj', 'cnpj');
		$this->form_validation->set_rules('inscricao_e', 'inscricao_e');
		$this->form_validation->set_rules('inscricao_m', 'inscricao_m');
		$this->form_validation->set_rules('empresa_r', 'empresa_r');
		$this->form_validation->set_rules('cep', 'cep');
		$this->form_validation->set_rules('rua', 'rua');
		$this->form_validation->set_rules('numero', 'numero');
		$this->form_validation->set_rules('bairro', 'bairro');
		$this->form_validation->set_rules('estado', 'estado');
		$this->form_validation->set_rules('cidade', 'cidade');
		$this->form_validation->set_rules('complemento', 'complemento');
		$this->form_validation->set_rules('email', 'email');
		$this->form_validation->set_rules('telefone', 'telefone');
		$this->form_validation->set_rules('telefone_one', 'telefone_one');


		if ($this->form_validation->run()) {

			$dados['nome']           =  $this->input->post('nome');
			$dados['razaosocial']    =  $this->input->post('razaosocial');
			$dados['cnpj']           =  $this->input->post('cnpj');
			$dados['inscricao_e']    =  $this->input->post('inscricao_e');
			$dados['inscricao_m']    =  $this->input->post('inscricao_m');
			$dados['empresa_r']      =  $this->input->post('empresa_r');
			$dados['cep']            =  $this->input->post('cep');
			$dados['rua']            =  $this->input->post('rua');
			$dados['numero']         =  $this->input->post('numero');
			$dados['bairro']         =  $this->input->post('bairro');
			$dados['estado']         =  $this->input->post('estado');
			$dados['cidade']         =  $this->input->post('cidade');
			$dados['complemento']    =  $this->input->post('complemento');
			$dados['email']          =  $this->input->post('email');
			$dados['telefone']       =  $this->input->post('telefone');
			$dados['telefone_one']   =  $this->input->post('telefone_one');
			$dados['status']         =  $this->input->post('status');
			$banco = $this->input->post('banco');
			$banco['data_cad'] = date('Y-m-d H:i:s');
			$banco['id_retorno'] = $this->input->post('id');

			if ($this->input->post('id') != NULL) {
				$this->cadastrar_fornecedor->update_fornecedor($dados, $this->input->post('id'));

				if($this->input->post('id_conta') == NULL){
					
					$this->cadastrar_fornecedor->addConta($banco);
					exit;
				}

				$this->cadastrar_fornecedor->update_conta($banco, $this->input->post('id_conta'));

				$this->session->set_flashdata('editado', '<div class="alert alert-success" role="alert">Fornecedor editado com sucesso ! </div>');
				// redirect('cadastro_fornecedor/index');
			} else {

				$this->cadastrar_fornecedor->addFornecedor($dados, $banco);
				$this->session->set_flashdata('sucesso', '<div class="alert alert-success" role="alert">Fornecedor cadastrado com sucesso ! </div>');
				// redirect('cadastro_fornecedor/index');
			}
		} else {
			$this->session->set_flashdata('dados', '<div class="alert alert-success" role="alert">Preencha todos os campos corretamente ! </div>');
			redirect('cadastro_fornecedor/index');
		}
	}


	public function getFornecedor()
	{
		echo json_encode($this->cadastrar_fornecedor->getFornecedor());
	}
	public function get_contas_fornecedores($fornecedor)
	{
		echo json_encode($this->conta->contaFornecedor($fornecedor));
	}
	public function editar($id = NULL)
	{	
		if ($this->input->is_ajax_request()) {
			// Se for uma requisição AJAX, responde com JSON
			$dados['fornecedores'] = $query = $this->cadastrar_fornecedor->getFornecedorByID($id);
			$dados['contas'] = $contas = $this->cadastrar_fornecedor->get_contasById($id);
			echo json_encode($dados);
			exit;
		}	
		if ($this->input->post()) {
			$this->cadastrar_fornecedor->update_fornecedor($this->input->post(), $id);
		}
		$query = $this->cadastrar_fornecedor->getFornecedorByID($id);
		$data['contas'] = array();
		$dados['titulo'] = "Show Tecnologia - Fornecedor";
		$dados['contas'] = $this->cadastrar_fornecedor->get_contasById($id);
		$dados['fornecedores'] = $query;
		$dados['id'] = $id;
		$this->load->view('fix/header4', $dados);
		$this->load->view('fornecedores/editar');
		$this->load->view('fix/footer4');
	}

	public function update_conta($id, $tec)
	{
		$dados = $this->input->post();
		$update = $this->cadastrar_fornecedor->update_conta($dados, $id);

		if ($update)
			$this->session->set_flashdata('sucesso', 'Conta editada com sucesso!');
		else
			$this->session->set_flashdata('erro', 'Não foi possível atualizar a conta.');

		redirect(site_url('cadastro_fornecedor/editar/' . $tec));
	}
	public function desativar_fornecedor()
	{
		if ($this->input->post()) {
			$update = $this->cadastrar_fornecedor->inativar_fornecedor($this->input->post('id'));
			if ($update) {
				echo json_encode(array('status' => true));
			} else {
				echo json_encode(array('status' => false));
			}
		}
	}
}
