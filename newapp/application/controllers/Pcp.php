<?php
error_reporting(0);

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pcp extends CI_Controller {
    private $timeZone;
    public function __construct() {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->model('auth');   
        $this->load->helper('util_iscas_helper');     
        $this->load->model('mapa_calor');
        $this->timeZone = new DateTimeZone('America/Sao_Paulo');        
    }

    public function iscas() {
        $this->auth->is_allowed('out_omnilink');

        $dados['titulo'] = lang('pcp_iscas');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        $this->mapa_calor->registrar_acessos_url(site_url('/Pcp/iscasClientes'));
		$this->load->view('new_views/fix/header', $dados);
        $this->load->view('pcp/iscas');
		$this->load->view('fix/footer_NS');   
    }

    public function iscasClientes() {
        $this->auth->is_allowed('out_omnilink');
    
        $dados['titulo'] = lang('pcp_iscas_cliente');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
    
        $this->mapa_calor->registrar_acessos_url(site_url('/Pcp/iscasClientes'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('pcp/iscasCliente');
        $this->load->view('fix/footer_NS');
    }

    public function lora() {
        $this->auth->is_allowed('out_omnilink');
    
        $dados['titulo'] = lang('pcp_lora');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
    
        $this->mapa_calor->registrar_acessos_url(site_url('/Pcp/lora'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('pcp/rastreadorLora');
        $this->load->view('fix/footer_NS');
    }

    public function comandosLora() {
        $this->auth->is_allowed('out_omnilink');
    
        $dados['titulo'] = lang('pcp_comandos_lora');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
    
        $this->mapa_calor->registrar_acessos_url(site_url('/Pcp/comandosLora'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('pcp/comandosLora');
        $this->load->view('fix/footer_NS');
    }

    public function associacaoLora() {
        $this->auth->is_allowed('out_omnilink');
    
        $dados['titulo'] = lang('pcp_associacao_lora');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');
    
        $this->mapa_calor->registrar_acessos_url(site_url('/Pcp/associacaoLora'));
        $this->load->view('new_views/fix/header', $dados);
        $this->load->view('pcp/associacaoLora');
        $this->load->view('fix/footer_NS');
    }
    
    public function verificarSerial(){
        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        $retorno = post_seriaispcp($dadosRecebidos);

        echo json_encode($retorno);
    }

    public function verificarSerialDadosClientes(){
        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        $retorno = post_seriais_clientes($dadosRecebidos);

        echo json_encode($retorno);
    }

    public function verificarSerialLora(){
        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        $retorno = post_seriaiLora($dadosRecebidos);
    
        echo json_encode($retorno);
    }

    public function enviarComandoLoRa()
    {
        $POSTFIELDS = array(
            'idTerminal' =>  $this->input->post('idTerminal'),
            'tipoComando' => (int)$this->input->post('tipoComando')
        );

        $dados = post_enviarComando($POSTFIELDS);
        echo json_encode($dados);
    }

    public function buscarHistoricoComandos()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $serial = $this->input->post('serial');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal = $this->input->post('dataFinal');


        if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $dataInicial)) {
            $dataInicialObj = DateTime::createFromFormat('Y-m-d', $dataInicial);
            $dataInicial = $dataInicialObj->format('d/m/Y');
        }

        if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $dataFinal)) {
            $dataFinalObj = DateTime::createFromFormat('Y-m-d', $dataFinal);
            $dataFinal = $dataFinalObj->format('d/m/Y');
        }

        $startRow++;

        $dados = get_historicoComandos($startRow, $endRow, $serial, $dataInicial, $dataFinal);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['comandos'],
                "lastRow" => $dados['resultado']['qtdComandos']
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

    public function clientesSelect2() {
        $itemInicio = $this->input->post('itemInicio');
        $itemFim = $this->input->post('itemFim');
        $nome = $this->input->post('searchTerm');
        $id = $this->input->post('id');

        $dados = get_clientesPCP($itemInicio, $itemFim, $nome, $id);
        echo json_encode($dados);
    }
    

    public function associarLora()
    {
        $POSTFIELDS = array(
            'idCliente' => (int)$this->input->post('idCliente'),
            'serial' =>  $this->input->post('serial'),
            'idLora' =>  $this->input->post('idLora'),
            
        );

        $dados = post_cadastrarAssociacao($POSTFIELDS);
        echo json_encode($dados);
    }

    public function buscarAssociacoesLoRa()
    {
        $startRow = (int) $this->input->post('startRow');
        $endRow = (int) $this->input->post('endRow');
        $idCliente = (int) $this->input->post('idCliente');
        $idLora = (int) $this->input->post('idLora');
        $serial = $this->input->post('serial');
        $status =(int)  $this->input->post('status');

        $startRow++;

        $dados = get_associacoesLora($startRow, $endRow, $idCliente, $idLora, $serial, $status);

        if ($dados['status'] == 200) {
            echo json_encode(array(
                "success" => true,
                "rows" => $dados['resultado']['comandos'],
                "lastRow" => $dados['resultado']['qtdRetornos']
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

    function alterarStatusAssociacao(){
        $POSTFIELDS = array(
            'id' => (int) $this->input->post('id'),
            'status' => (int)$this->input->post('status')
        );

        $dados = patch_alterarStatusAssociacao($POSTFIELDS);
        echo json_encode($dados);
    }

    public function editarAssociacaoLoRa()
    {
        $POSTFIELDS = array(
            'id' => (int)$this->input->post('id'),
            'serial' => $this->input->post('serial'),
            'idCliente' => (int)$this->input->post('idCliente'),
            'idLora' => $this->input->post('idLora'),
            'status' => (int)$this->input->post('status')
        );
    
        $dados = post_EditarAssociacao($POSTFIELDS);
        echo json_encode($dados);
    }
    
    
}