<?php if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

class dashboards_logistica extends CI_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->auth->is_logged('admin');
	}

	public function Index()
    {
		$dados['titulo'] = lang('tipo_movimento');
        $this->mapa_calor->registrar_acessos_url(site_url('/dashboards_logistica'));
		$this->load->view('fix/header_NS', $dados);
		$this->load->view('logistica/dash');
		$this->load->view('fix/footer_NS');   
	}
   
    public function buscarDadosDash(){   
        $dt_ini = data_for_humans($this->input->post('dt_ini'));
        $dt_fim = data_for_humans($this->input->post('dt_fim'));
        
        $tiposMovimentacaoID =  $this->input->post('tiposMovimentacaoID');

        if(!isset($tiposMovimentacaoID)){
            $tiposMovimentacaoID = "";
        }

        $this->load->helper('api_helper');
        $url = 'logistica/listarMovimentosExpedicaoManipulados?dataInicial='.$dt_ini.'&idTipoMovimento='.$tiposMovimentacaoID.'&dataFinal='.$dt_fim;
        $resultado = API_Helper::getAPIShow($url);

        echo json_encode($resultado); 
    }    
}