<?php
error_reporting(0);
date_default_timezone_set("America/Recife");

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Andromeda extends CI_Controller
{

    protected $sac;
    private $timeZone;
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged('admin');
        $this->load->helper('sac_crm_helper');
        $this->load->model('auth');
        $this->load->model('mapa_calor');
        $this->sac = new SacCrmHelper();
        $this->timeZone = new DateTimeZone('America/Recife');
    }

    public function rastreamentoIndividual(){
        //$this->auth->is_allowed('out_omnilink');    
        
		$this->mapa_calor->registrar_acessos_url(site_url('/Andromeda/rastreamentoIndividual'));

        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

        
		$this->load->view('new_views/fix/header', $dados);
        $this->load->view('andromeda/rastreamentoIndividual.php');
        $this->load->view('fix/footer_NS.php');
    }

    public function rastreamentoLote(){
        //$this->auth->is_allowed('out_omnilink');
        $dados['load'] = array('ag-grid', 'select2', 'mask', 'XLSX');

		$this->mapa_calor->registrar_acessos_url(site_url('/Andromeda/rastreamentoLote'));

		$this->load->view('new_views/fix/header', $dados);
        $this->load->view('andromeda/rastreamentoLote.php');
        $this->load->view('fix/footer_NS.php');
    }

    public function rastrearSerialIndividual(){
        $serial =  $this->input->post()['serial'];
 
        $CI =& get_instance();
 
        # URL configurada para a API
+        $request = $CI->config->item('url_api_shownet_rest').'api/chips/';
 
        $token = $CI->config->item('token_api_shownet_rest');
 
        session_start();
       
        $curl = curl_init();
 
        curl_setopt_array($curl, array(
        CURLOPT_URL => $request.$serial,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '. $token
        ),
        ));
 
        if (curl_error($curl))  throw new Exception(curl_error($curl));
        $resultado = json_decode(curl_exec($curl), true);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $result = array();

        if ($statusCode == 200){
            foreach ($resultado as $key => $value) {
                $result[] = array(
                    "idEquipamento" => number_format($value['idEquipamento'], 0, '.', ''),
                    "idTecnologia" => $value['idTecnologia'],
                    "nomeTecnologia" => $value['nomeTecnologia'],
                    "idModelo" => $value['idModelo'],
                    "nomeModelo" => $value['nomeModelo'],
                    "status" => $value['status'],
                    "descricaoStatus" => $value['descricaoStatus'],
                    "fone" => $value['fone'],
                    "ddd" => $value['ddd'],
                    "operadora" => $value['operadora'],
                    "operadoraNome" => $value['operadoraNome'],
                    "data" => $value['data'],
                    "sumInput" => $value['sumInput'],
                    "sumOutput" => $value['sumOutput'],
                    "total" => $value['total'],
                );
            }
        }

 
        curl_close($curl);
 
        echo json_encode(
            array(
                'status' => $statusCode,
                'dados'  => $result
            )
        );
    }

    public function testeComunicacaoChip(){
        $serial =  $this->input->post()['id'];
       
        $retorno = to_testeComunicacaoChiop($serial);

        echo json_encode($retorno);
    }

    public function listarTesteComunicacaoChip(){
        $retorno = to_listarTesteComunicacaoChiop();

        echo json_encode($retorno);
    }

}