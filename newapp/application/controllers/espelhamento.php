<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Espelhamento extends CI_Controller {

  public function __construct() {
    parent::__construct();    
    $this->load->helper('url');
    $this->load->model('cliente');
    $this->load->helper('espelhamento_helper');
    $this->auth->is_logged('admin');
    $this->load->model('auth');
  }

  public function getCentraisGR(){
    error_reporting(0);
    $cnpj = $this->input->get('cnpj');

    $centrais = $this->cliente->CentraisByCnpj($cnpj);
    $data['data']= array();
    if(count($centrais) > 0 ){
      foreach($centrais as $central){

        $response = veiculosEspelhados_api($central->ip,$central->porta);
        $decoded = json_decode($response);     
    
        //prepare data from response
        foreach($decoded->ListaVeiculos as $veiculo){
          $data['data'][] = array(
            trim($central->nome),
            $veiculo->idTerminal,
            $central->ip,
            $central->porta,
            $veiculo->placas,
            $this->cliente->ClientNameByPlate($veiculo->placas),
          );
        }
      }
      echo json_encode($data);
      die;
    }
   echo json_encode($data);
   die;
    
  }

  public function getCentrais(){

    //fetch data from helper_api
    $response = centrais_api();
    $decoded = json_decode($response);
    
    $data = array();    
    foreach($decoded->ListaCentrais as $res){

      $data[] = $res;
    }
    echo json_encode(['data'=> $data]);
    die;
  }

  public function getVeiculosEspelhados(){

    $ip    = $this->input->get('ip');
    $porta = $this->input->get('porta');

    //validate parameters in request
    if($ip == null && $ip == null){
      $response = [ 'erro' => 'IP e porta são Obrigatórios'];
      echo json_encode($response);
      die;
    }

    //fech data from helper api
    $response = veiculosEspelhados_api($ip,$porta);
    $decoded = json_decode($response);
    
    $data['data']= array();

    //prepare data from response
    foreach($decoded->ListaVeiculos as $veiculo){
      $data['data'][] = array(
        $veiculo->placas,
        $veiculo->idTerminal,
        $this->cliente->ClientNameByPlate($veiculo->placas),
      );
    }
    echo json_encode($data);
    
  }
}