<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('cliente');
        $this->load->model('veiculo');
        $this->load->model('relatorio');
        $this->load->model('envio_fatura');
        $this->load->model('usuario');
        $this->load->model('contrato');
        $this->load->model('fatura');
        $this->load->model('forum_show');
        $this->load->model('auth');
    }

    public function index(){

        $dados['titulo'] = 'Show Tecnologia';
        $this->load->view('fix/header', $dados);
        $this->load->view('forum/index');
        $this->load->view('fix/footer');

    }

    public function get_forum(){
        
        echo json_encode($this->forum_show->get(array()));

    }
    public function novo_topico(){
        $usuario = $this->auth->get_login_dados();
        $data=array('data'=>date("Y-m-d H:i:s"),'assunto'=>$this->input->post('assunto'),'id_user'=>$usuario['user']);
        $this->forum_show->insert($data);
        echo json_encode(array('status'=>'success'));

    }
}