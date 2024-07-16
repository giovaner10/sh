<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



function is_activo($valor){
	$CI =& get_instance();
	if($CI->router->fetch_class() == $valor || $CI->router->fetch_method() == $valor)
		return 'active';
}

function set_selecionado($palavra, $busca, $retorno){

	if(is_array($busca)){
		return in_array($palavra, $busca) ? $retorno : '';
	}
	return $palavra == $busca ? $retorno : '';

}

function grava_url($url){
	$CI =& get_instance();
	$CI->session->set_userdata('url_anterior', $url);

}

function get_url(){
	$CI =& get_instance();
	return $CI->session->userdata('url_anterior');

}

function cpf_to_bd($cpf){

	return str_replace(array('-', '.'), '', $cpf);

}

function cnpj_to_bd($cnpj){

	return str_replace(array('-', '.', '/'), '', $cnpj);

}

/*
 * grava logs no banco de dados
*/
function grava_log($log){
	$CI =& get_instance();
	$CI->load->model('gerenciador/log');

	$CI->log->gravar($log);
}

function pegar_endereco($lat, $lng)
{
	$endereco = utf8_encode(file_get_contents('http://192.168.0.200:8181/reverse_geo?lat='.$lat.'&long='.$lng.'&key=qazwsxedcr&tipo=txt'));

	return $endereco;
}


function dh_to_br($data)
{
	$data_hora = explode(' ', $data);

	$data = explode('-', $data_hora[0]); // 0 - ano, 1 - mes, 2 - dia
	$hora = explode(':', $data_hora[1]); // 0 - hora, 1 - minuto, 2 - segundo

	return $data[2] . '/' . $data[1] . '/' . $data[0] . ' ' . $hora[0] . ':' . $hora[1] . ':' . $hora[2];
}

function status_fatura($status, $vencimento, $tooltip = ''){
	$back_status = 'erro nos params';
	$hoje = date('Y-m-d');

	switch ($status){
		case 0 :
			if($hoje > $vencimento){
				$diff = strtotime($hoje) - strtotime($vencimento);
				$atraso = floor($diff/86400);
				$back_status = '<span class="label label-important">Atrasado ('.$atraso.' dias)</span>';
			}else{
				$back_status = '<span class="label label-warning">A pagar</span>';
			}
			break;
		case 1 :
			$back_status = '<span class="label label-success">Pago</span>';
			break;
		case 2 :
			if($hoje > $vencimento){
				$diff = strtotime($hoje) - strtotime($vencimento);
				$atraso = floor($diff/86400);
				$back_status = '<span class="label label-important">Atrasado ('.$atraso.' dias)</span>';
			}else{
				$back_status = '<span class="label label-info">Não enviado</span>';
			}
			break;
		case 3 :
			$back_status = '<span class="label">Cancelado</span>';
	}

	return $back_status;
}

function label_nova_data($status_fatura, $dt_atualizado){
	if($dt_atualizado != '' && $status_fatura != 1){
		return '<span class="label label-warning">Venc. Atualizado</span>';
	}
}

/*
 * função para calcular juros por dia
* retorna o valor dos juros
*/
function calcula_juros($valor, $taxa, $num_dias){

	$val_percent = $valor/100;
	$multa = $val_percent * 2;
	$total_juros = ($val_percent * $taxa) * $num_dias;
	$total_juros += $multa;

	return round($total_juros, 2);

}

function show_icon_order($p_order, $c_order, $order){

	$img = '';
	if($order == 'asc' && $p_order == $c_order){
		$img = '<i class="icon-chevron-up"></i>';
	}elseif($order == 'desc' && $p_order == $c_order){
		$img = '<i class="icon-chevron-down"></i>';
	}

	return $img;
}

function compara_valor_porcent($val1, $val2){

	$porcent = 0;

	if ($val1 > $val2){
		$val_maior = $val1;
		$val_menor = $val2;
	}else{
		$val_maior = $val2;
		$val_menor = $val1;
	}

	$dif_val = $val_maior / $val_menor;
	if ($dif_val >= 1 && $dif_val < 2){
		$dif_val = round(($dif_val -1), 2);
	}

	if ($dif_val > 0)
		$porcent = round(($dif_val * 100), 2);

	return $porcent;


}

function show_status($status){
	$back_status = 'erro nos params';

	switch ($status){
		case 0 :
			$back_status = '<span class="label">Inativo</span>';
			break;
		case 1 :
			$back_status = '<span class="label label-success">Ativo</span>';
			break;
		case 2 :
			$back_status = '<span class="label label-important">Bloqueado</span>';
			break;
		case 3 :
			$back_status = '<span class="label label-inverse">Cancelado</span>';
			break;

	}

	return $back_status;
}

function show_status_contrato($status){
	$back_status = 'erro nos params';

	switch ($status){
		case 0 :
			$back_status = '<span class="label">Cadastrado</span>';
			break;
		case 1 :
			$back_status = '<span class="label label-warning">Em trânsito OS</span>';
			break;
		case 2 :
			$back_status = '<span class="label label-success">Ativo</span>';
			break;
		case 3 :
			$back_status = '<span class="label label-inverse">Cancelado</span>';
			break;
		case 4 :
			$back_status = '<span class="label label-info">Em Teste</span>';
			break;
		case 5 :
			$back_status = '<span class="label label-important">Bloqueado</span>';
			break;
		case 6 :
			$back_status = '<span class="label">Encerrado</span>';
			break;
	}

	return $back_status;
}



function pr($array)
{
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}