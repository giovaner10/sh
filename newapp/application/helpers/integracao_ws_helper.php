<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//<-- Giovani de Alencar - Show 2019 -->//
function cUrlGetData($url) {

    $CI =& get_instance();
	$post_fields = '';
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $CI->config->item('base_url_rest').'index.php/api/'.$url.'');
    if ($post_fields && !empty($post_fields)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers = array(
    'Content-Type: application/x-www-form-urlencoded', 
    'SHOW-API-KEY: go00840cooswgwk8o4c0kwgws000okkk848kscko', //Auth token WebService
    'charset: utf-8', 
	));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);

    if (curl_errno($ch))
    {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    if($data)
    {
       $retorno = json_decode($data, true);
    	return $retorno;
    }else{

    	return false;
    }
}
//<!-- Giovani de Alencar - Show 2019 -->//

function cUrlSendCommand($dados) {
    $CI =& get_instance();
    $post_fields = $dados;
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $CI->config->item('base_url_rest').'index.php/api/send_command');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers = array(    
    'SHOW-API-KEY: go00840cooswgwk8o4c0kwgws000okkk848kscko', //Auth token WebService
    'charset: utf-8',
    ));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    if($data){
        $retorno = json_decode($data, true);
        return $retorno;
    }else{
        return false;
    }

}