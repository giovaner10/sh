<?php if (!defined('BASEPATH')) exit(lang('nenhum_acesso_direto_script_permitido'));
class Cadastro_Cliente extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
		
		$this->load->library('form_validation');
        $this->load->database();

    }

    public function index()
    {
        $dados['titulo'] = lang('pedidos');
           
        $_SESSION['emailUsuario'] = $this->auth->get_login_dados('email');
		
        $this->load->view('fix/header_NS', $dados);
        $this->load->view('comercial_televenda/pedido/cadastro_cliente_crm');
        $this->load->view('fix/footer_NS');
    }

   

	
	

	
}