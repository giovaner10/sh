<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class Insumos extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->auth->is_logged('admin');
	}

	public function index()
    {
        $this->auth->is_allowed('logistica_shownet');
		$dados['titulo'] = lang('insumos');
        $this->mapa_calor->registrar_acessos_url(site_url('/Insumos'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('logistica/insumo');
		$this->load->view('fix/footer_NS');   
	}


    public function listarInsumos(){

        $retorno = get_listarInsumosAll();

        echo json_encode($retorno);
    }

    public function cadastrarInsumo(){
        $dados = $this->input->post();

        $retorno = to_cadastrarInsumo($dados);

        echo json_encode($retorno);
    }

    public function editarInsumo(){
        $dados = $this->input->post();

        $retorno = to_editarCadInsumo($dados);

        echo json_encode($retorno);
    }

    public function inativarInsumo(){
        $dados = $this->input->post();

        $retorno = to_alterarStatusInsumo($dados);

        echo json_encode($retorno);
    }

}