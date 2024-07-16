<?php

function centraisGR_api($cnpj = null){  
  $url = "http://200.185.141.78:8092/service2.asmx";

  $soap_request = '<?xml version="1.0"?>
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://microsoft.com/webservices/">
    <soapenv:Header/>
    <soapenv:Body>
        <web:ConsultaCentraisGR>
          
          <web:CNPJ>'.$cnpj.'</web:CNPJ>
        </web:ConsultaCentraisGR>
    </soapenv:Body>
  </soapenv:Envelope>';
  
  
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "$soap_request");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);

  
  $xmlData = str_replace("soap:","",$output);
  libxml_use_internal_errors(true);
  $result = simplexml_load_string($xmlData,"SimpleXMLElement",LIBXML_NOCDATA);
  libxml_get_errors();
  
  $results = $result->Body->ConsultaCentraisGRResponse->ConsultaCentraisGRResult;

  return $results;
}

function centrais_api($cnpj = null){  
  $url = "http://200.185.141.78:8092/service2.asmx";

  $soap_request = '<?xml version="1.0"?>
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://microsoft.com/webservices/">
    <soapenv:Header/>
    <soapenv:Body>
        <web:ConsultaCentrais/>
    </soapenv:Body>
  </soapenv:Envelope>';
  
  
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "$soap_request");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);

  
  $xmlData = str_replace("soap:","",$output);
  libxml_use_internal_errors(true);
  $result = simplexml_load_string($xmlData,"SimpleXMLElement",LIBXML_NOCDATA);
  libxml_get_errors();
  
  $results = $result->Body->ConsultaCentraisResponse->ConsultaCentraisResult;

  return $results;
}

function veiculosEspelhados_api($ip=null, $porta=null){  
  
  $url = "http://200.185.141.78:8092/service2.asmx";

  $soap_request = '<?xml version="1.0"?>
  <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://microsoft.com/webservices/">
  <soapenv:Header/>
    <soapenv:Body>
        <web:ListaVeiculosEspelhados>      
          <web:IP>'.$ip.'</web:IP>
          <web:Porta>'.$porta.'</web:Porta>
        </web:ListaVeiculosEspelhados>
    </soapenv:Body>
  </soapenv:Envelope>';

  
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "$soap_request");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);

  
  $xmlData = str_replace("soap:","",$output);
  libxml_use_internal_errors(true);
  $result = simplexml_load_string($xmlData,"SimpleXMLElement",LIBXML_NOCDATA);
  libxml_get_errors();
  
  $results = $result->Body->ListaVeiculosEspelhadosResponse->ListaVeiculosEspelhadosResult;

  return $results;
}


function ConsultaCentraisNumeroSerie_api($serial = null)
{

  # Cria instância do CI
  $CI = & get_instance();

  # Cria retorno padrão
  $retorno = array();
   
  $url = $CI->config->item('base_url_espelhamento');

  # Verifica parâmetros
  if (isset($serial)) {
    # Cria chamada SOAP
    $soap_request = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:web="http://microsoft.com/webservices/">
          <soap:Header/>
          <soap:Body>
          <web:ConsultaCentraisNumeroSerie>
              <web:NumeroSerie>' . $serial . '</web:NumeroSerie>
          </web:ConsultaCentraisNumeroSerie>
          </soap:Body>
      </soap:Envelope>';

    
    # Executa chamada via CURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$soap_request");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);


    $xmlData = str_replace("soap:", "", $output);
    libxml_use_internal_errors(true);
    $result = simplexml_load_string($xmlData, "SimpleXMLElement", LIBXML_NOCDATA);
    libxml_get_errors();

    $results = $result->Body->ConsultaCentraisNumeroSerieResponse->ConsultaCentraisNumeroSerieResult;

    return $results;
  }
}