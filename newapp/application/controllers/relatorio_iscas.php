<?php

use function PHPSTORM_META\type;

if (!defined('BASEPATH')) exit(lang("nenhum_acesso_direto_script_permitido"));

ini_set('display_errors', 1);

class Relatorio_iscas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->helper('util_helper');
    }


    public function getHistoricoDia($serial) {

        $titulo = 'Histórico de iscas';
        $dados = array('titulo' => $titulo);
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('relatorios/relatorio_iscas', $serial);
        $this->load->view('fix/footer_NS');

	}
    
    public function buscarRelatorioIscasServerSide(){
        $itemInicial = $this->input->post('itemInicial');
        $itemFinal = $this->input->post('itemFinal');
        $serial = $this->input->post('serial');

        $dados = get_DadosIscasPaginated($itemInicial, $itemFinal, $serial);
		

		if ($dados['status'] == '200') {
			echo json_encode(array(
				"success" => true,
				"rows" => $dados['resultado']['eventosTracker'],
				"lastRow" => $dados['resultado']['qtdTotalEventos']
			));
		} else {
            echo json_encode(array(
                "success" => false,
                "message" => $dados['resultado']['mensagem'],
            ));
		}
    }
}