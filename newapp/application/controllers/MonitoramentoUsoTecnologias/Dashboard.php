<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));
class Dashboard extends CI_Controller
{
    public function __construct()
    {
		parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
		$this->load->helper('util_monitoramento_helper');
	}
    // Paginas
	public function index()
    {   
		$this->mapa_calor->registrar_acessos_url(site_url('/MonitoramentoUsoTecnologias/Dashboard'));
		$dados['titulo'] = lang('dashboard_monitoramento_tecnologias');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX', 'ag-charts');
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('MonitoramentoUsoTecnologias/Dashboard');
		$this->load->view('fix/footer_NS');   
	}


	// Buscas:
	public function buscarDadosTecnologiaByPeriodo(){

        $dataInicio =  $this->input->post('dataInicio');
        $dataFim =  $this->input->post('dataFim');

        $dados = get_dadosTecnologiaByPeriodo($dataInicio, $dataFim);

        echo json_encode($dados);
    }

	public function buscarDadosTecnologiaByIdCliente(){

        $idCliente =  (int)$this->input->post('idCliente');
      

        $dados = get_dadosTecnologiaByIdCliente($idCliente);

        echo json_encode($dados);
    }

	public function buscarDadosByIdTecnologia(){

        $idTecnologia =  (int) $this->input->post('idTecnologia');
      

        $dados = get_dadosTecnologiaById($idTecnologia);

        echo json_encode($dados);
    }

	public function buscarDadosTecnologiaClienteEPeriodo(){
        $dataInicio =  $this->input->post('dataInicio');
        $dataFim =  $this->input->post('dataFim');
        $idCliente =  (int) $this->input->post('idCliente');
     
        $dados = get_dadosTecnologiabyClienteEPeriodo($dataInicio, $dataFim, $idCliente);

        echo json_encode($dados);

    }

    public function buscarClientes(){
     
        $dados = get_Clientes();

        echo json_encode($dados);
    }
}