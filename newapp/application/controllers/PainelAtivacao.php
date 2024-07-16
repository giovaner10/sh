<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PainelAtivacao extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->load->helper('painel_ativacao_helper');
    }

    // Paginas
	public function index() {

        // $this->auth->is_allowed('vis_painel_ativacao');

		$this->mapa_calor->registrar_acessos_url(site_url('/PainelAtivacao'));

		$dados['titulo'] = lang('painel_ativacao');
        $dados['load'] = array('ag-grid', 'select2', 'mask');
        
		$this->load->view('new_views/fix/header', $dados);
		$this->load->view('painelAtivacao/index');
		$this->load->view('fix/footer_NS');   
	}

    public function buscarDadosServerSide() {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $serial = $this->input->post('serial');
        $na = $this->input->post('numNA');
        $dataInicio = $this->input->post('dataInicio');
        $dataFim = $this->input->post('dataFim');

        $serial = str_replace(' ', '', $serial);
        $na = str_replace(' ', '', $na);

        $startRow++;

        $dados = get_DadosPaginated($serial, $na, $startRow, $endRow, $dataInicio, $dataFim);

        if ($dados['status'] == '200') {
            echo json_encode(array(
                "success" => true,
                "statusCode" => $dados['status'],
                "rows" => $dados['resultado']['dados'],
                "lastRow" => $dados['resultado']['qntDados']
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "statusCode" => $dados['status'],
                "message" => $dados['resultado']['mensagem'],
            ));
        }
        
    }

    public function baixarRelatorio() {
        $dataInicio = $this->input->post('dataInicio') ?: '';
        $dataFim = $this->input->post('dataFim') ?: '';
        $serial = $this->input->post('serial') ?: '';
        $na = $this->input->post('na') ?: '';
        $tipoArquivo = $this->input->post('tipoArquivo') ?: '';

        $result = get_baixarRelatorio($dataInicio, $dataFim, $serial, $na, $tipoArquivo);

        if ($result['status'] == 200) {

            if ($tipoArquivo === 'pdf') {
                $contentType = 'application/pdf';
                $contentDisposition = 'attachment';
                $fileExtension = 'pdf';
            } elseif ($tipoArquivo === 'xlsx') {
                $contentType = 'application/octet-stream';
                $contentDisposition = 'form-data; name="filename"; filename="relatorio_painel_ativacao'.$dataInicio.'-'.$dataFim.'.xlsx"';
                $fileExtension = 'xlsx';
            } elseif ($tipoArquivo === 'csv') {
                $contentType = 'application/octet-stream';
                $contentDisposition = 'form-data; name="filename"; filename="relatorio_painel_ativacao'.$dataInicio.'-'.$dataFim.'.csv"';
                $fileExtension = 'csv';
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo 'Tipo de arquivo não suportado.';
                exit();
            }

            header("Content-Type: $contentType");
            header("Content-Disposition: $contentDisposition");

            echo $result['resultado'];
            exit();
        } else {
            echo 'Erro ao baixar o relatório';
            exit();
        }

	}

    public function buscarProcessoAtivacao() {
		$codigo = $this->input->post('codigo');

        $result = get_ProcessoDeAtivacao($codigo);

        echo json_encode($result);

	}

    public function buscarFormularioAtivacao() {
		$codigo = $this->input->post('codigo');

        $result = get_FormularioDeAtivacao($codigo);

        echo json_encode($result);

	}

    public function buscarInventarioAtivacao() {
		$codigo = $this->input->post('codigo');

        $result = get_InventarioDeAtivacao($codigo);

        echo json_encode($result);

	}

    public function buscarItensInventarioAtivacao() {
		$codigo = $this->input->post('codigo');

        $result = get_ItensInventarioDeAtivacao($codigo);

        echo json_encode($result);

	}
}