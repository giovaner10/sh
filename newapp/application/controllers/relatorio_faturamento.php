<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Relatorio_faturamento extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('fatura');
        $this->load->model('mapa_calor');
    }
    
    function index() {
        $dados['titulo'] = lang('relatorio_faturamento');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->auth->is_allowed('relatorio_faturamento');
        $this->mapa_calor->registrar_acessos_url(site_url('/relatorio_faturamento'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/relatorio_faturamento');
		$this->load->view('fix/footer_NS');
    }

    function geraRelatioFaturamento() {
        $data_inicial = $this->input->post('data_inicial');
        $data_final = $this->input->post('data_final');
        $empresa = $this->input->post('empresa');
        $status_fatura = $this->input->post('status');
        $orgao = $this->input->post('orgao');
        
        $geraRelatioFaturamento = $this->fatura->getRelatorioFaturas($data_inicial, $data_final, $empresa, $status_fatura, $orgao);
        
        if ($geraRelatioFaturamento !== false) {
            echo json_encode(array('success' => true, 'data' => $geraRelatioFaturamento));
        } else {
            echo json_encode(array('success' => false, 'data' => []));
        }
    }
}