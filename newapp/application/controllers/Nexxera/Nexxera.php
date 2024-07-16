<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

ini_set('display_errors', 1);

class Nexxera extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->load->helper('util_nexxera_helper');
    }

	public function index()
    {   
		$this->mapa_calor->registrar_acessos_url(site_url('/Nexxera/Nexxera'));

		$dados['titulo'] = "Nexxera";
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('nexxera/nexxera');
		$this->load->view('fix/footer_NS');
	}

    public function historicoEnvios(){
        $itemInicio = $this->input->post('startRow');
        $itemFim = $this->input->post('endRow');
        $tipoDocumento = $this->input->post('tipoDocumento');
        $dataInicio = $this->input->post('dataInicio');
        $dataFim = $this->input->post('dataFim');
        
        $itemInicio++;

        $dados = get_historicoEnvios($itemInicio, $itemFim, $tipoDocumento, $dataInicio, $dataFim);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['logNexxera'],
                "lastRow" => $dados['resultado']['quantLogNexxera']
            ));
        } else if ($dados['status'] == 404) {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
        }
    }
}