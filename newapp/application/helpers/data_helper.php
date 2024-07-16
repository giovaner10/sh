<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_data_exploded($data, $posicao, $mes_texto = false, $separador){
	 $CI =& get_instance();

	 $dt = explode($separador, $data);
	 if ($posicao == 1 && $mes_texto == true){
	 	return data_mes_texto($dt[$posicao]);
	 }
	 return $dt[$posicao];
}

function data_mes_texto($mes){
	switch($mes){
		case '01':
			$mes = 'jan';
			break;
		case '02':
			$mes = 'fev';
			break;
		case '03':
			$mes = 'mar';
			break;
		case '04':
			$mes = 'abr';
			break;
		case '05':
			$mes = 'mai';
			break;
		case '06':
			$mes = 'jun';
			break;
		case '07':
			$mes = 'jul';
			break;
		case '08':
			$mes = 'ago';
			break;
		case '09':
			$mes = 'set';
			break;
		case '10':
			$mes = 'out';
			break;
		case '11':
			$mes = 'nov';
			break;
		case '12':
			$mes = 'dez';
			break;
	}

	return $mes;
}

function data_mes_texto_completo($mes){
	switch($mes){
		case '01':
			$mes = 'Janeiro';
			break;
		case '02':
			$mes = 'Fevereiro';
			break;
		case '03':
			$mes = 'Março';
			break;
		case '04':
			$mes = 'Abril';
			break;
		case '05':
			$mes = 'Maio';
			break;
		case '06':
			$mes = 'Junho';
			break;
		case '07':
			$mes = 'Julho';
			break;
		case '08':
			$mes = 'Agosto';
			break;
		case '09':
			$mes = 'Setembro';
			break;
		case '10':
			$mes = 'Outubro';
			break;
		case '11':
			$mes = 'Novembro';
			break;
		case '12':
			$mes = 'Dezembro';
			break;
	}

	return $mes;
}

function data_for_humans($data){
    error_reporting(0);
	$dt = explode("-", str_replace('/', '-', $data));
	return $dt[2].'/'.$dt[1].'/'.$dt[0];
}

function dh_for_humans($data = false, $hora = true, $remove_data = false){
    error_reporting(0);
	if(!$data)
		return false;
	$dt = explode("-", $data);
	$hr = explode(" ", $dt[2]);

	if ($hora){
		return $hr[0].'/'.$dt[1].'/'.$dt[0].' '.$hr[1];
	}elseif($remove_data){
		return $hr[1];
	}
	return $hr[0].'/'.$dt[1].'/'.$dt[0];
}

function dh_for_unix($data, $hora = true, $sep = '/') {
	$d = explode(' ',$data);
	if ($hora) {
		if(!isset($d[1])){
			$d[1]="00:00:00";
		}
		return date('Y-m-d', strtotime(str_replace('/', '-', strval($d[0]))))." ".$d[1];
	} else {
		return date('Y-m-d', strtotime(str_replace('/', '-', strval($d[0]))));
	}

}



function data_for_unix($data){
	$dt = explode("/", $data);
	return $dt[2].'-'.$dt[1].'-'.$dt[0];
}

function dh_mktime_for_humans($mktime){
	return date("d/m/Y H:i:s",strtotime($mktime));
}

function validar_entre_datas($data_maior, $data_menor, $dif_dias){

	$d_maior = strtotime(data_for_unix($data_maior));
	$d_menor = strtotime(data_for_unix($data_menor));

	$diferenca = $d_maior - $d_menor;
	if(($diferenca/86400) <= $dif_dias)
		return true;

	return false;

}

/*
 * função para retornar data com mesmo dia próximo mês
 * corrige bug do mês de fevereiro
 */
function next_data($data_atual, $separador_data = '-'){

	$CI =& get_instance();
	$next_data = '';
	$mes = get_data_exploded($data_atual, 1, false, $separador_data);
	$dia = get_data_exploded($data_atual, 2, false, $separador_data);
	$ano = get_data_exploded($data_atual, 0, false, $separador_data);
	if($mes != '01'){

		$next_data = date('Y-m-d', strtotime("{$data_atual} next month"));

		if ($mes == '02' && $CI->session->userdata('dt_mar')){
				$next_data = $CI->session->userdata('dt_mar');
				$CI->session->unset_userdata('dt_mar');
		}

	}else{
		if($dia < 29){
			$next_data = date('Y-m-d', strtotime("{$data_atual} next month"));
		}else{
			$CI->session->set_userdata('dt_mar', $ano.'-03-'.$dia);
			$next_data = date('Y-m-d', strtotime("{$ano}-{$mes} last day of next month"));
		}

	}

	return $next_data;
}

function valida_periodo_vencimento($dt_inicio, $dt_fim){

	$ini = get_data_exploded($dt_inicio, 2, false, '-');
	$fim = get_data_exploded($dt_fim, 2, false, '-');

	if($ini == $fim)
		return true;

	return false;
}

/*
 * função para validar uma data
 * formato da data yyyy-mm-dd
 */
function is_date($data){
	$dt = explode('-', $data);
	$is_data = false;
	if(is_array($dt)){
		$is_data = checkdate($dt[1], $dt[2], $dt[0]);
	}

	return $is_data;
}

//diferença entre datas com sinal positivo para dias a mais ou negativo para dias a menos
function dif_datas($data_inicial, $data_final, $formato='%R%a'){
		$date  = new DateTime($data_inicial);
		$date2 = new DateTime($data_final);
		$intervalo = $date->diff($date2);

		return $intervalo->format($formato);
}

function diff_entre_datas($dt_ini, $dt_fim, $f_retorno = 'dias'){

$diferenca = 0;

	$dtini = new DateTime($dt_ini);
	$dtfim = new DateTime($dt_fim);

	$diff = $dtini->diff($dtfim);
	switch ($f_retorno)
	{
		case 'mes':
			$diferenca = floor($diff->days / 30);
			break;
		case 'horas':
			if ($diff->d > 0)
				$diferenca = $diff->format('%h.%i');
			else
				$diferenca = $diff->format('%d.%h.%i');
			break;
		case 'minutes':
			$diffSeconds = (new DateTime())->setTimeStamp(0)->add($diff)->getTimeStamp();
			$diferenca = $diffSeconds/60;
			break;
		case 'dias':
			$diferenca = $diff->days;
			break;

	}

	return $diferenca;


}

//////////////////////////////////////////////////////////////////////
//PARA: Date Should In YYYY-MM-DD Format
//RESULT FORMAT:
// '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
// '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
// '%m Month %d Day'                                            =>  3 Month 14 Day
// '%d Day %h Hours'                                            =>  14 Day 11 Hours
// '%d Day'                                                        =>  14 Days
// '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
// '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
// '%h Hours                                                    =>  11 Hours
// '%a Days                                                        =>  468 Days
//////////////////////////////////////////////////////////////////////
function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);

}

if ( ! function_exists('sum_hours'))
{
	function sum_hours($hour1, $hour2)
	{
		$seconds = 0;

		list($h1, $i1, $s1) = explode(':', $hour1);
		list($h2, $i2, $s2) = explode(':', $hour2);

		$seconds += $h1 * 3600;
		$seconds += $i1 * 60;
		$seconds += $s1;

		$seconds += $h2 * 3600;
		$seconds += $i2 * 60;
		$seconds += $s2;

		$hours    = floor($seconds / 3600);
		$seconds -= $hours * 3600;
		$minutes  = floor($seconds / 60);
		$seconds -= $minutes * 60;

		$hours   = $hours > 9 ? $hours : "0{$hours}";
		$minutes = $minutes > 9 ? $minutes : "0{$minutes}";
		$seconds = $seconds > 9 ? $seconds : "0{$seconds}";

		return "{$hours}:{$minutes}:{$seconds}";
	}
}

if ( ! function_exists('compare_time'))
{
	function compare_time($time1, $time2)
	{
		list($h1, $i1, $s1) = explode(':', $time1);
		list($h2, $i2, $s2) = explode(':', $time2);

		$t1 = 0;
		$t1 += $h1 * 3600;
		$t1 += $i1 * 60;
		$t1 += $s1;

		$t2 = 0;
		$t2 += $h2 * 3600;
		$t2 += $i2 * 60;
		$t2 += $s2;

		if ($t1 > $t2) {
			return 1;
		} else if ($t1 < $t2) {
			return -1;
		} else {
			return 0;
		}
	}
}

function diff_entre_datas_sintatico($dt_ini, $dt_fim, $f_retorno = 'dias'){

	$diferenca = 0;

	$dtini = new DateTime($dt_ini);
	$dtfim = new DateTime($dt_fim);

	$diff = $dtini->diff($dtfim);
	if ($f_retorno == 'mes') {
		$diferenca = floor($diff->days / 30);
	} elseif($f_retorno == 'horas') {

		$hour   = $diff->format('%h');
		$minute = $diff->format('%i');
		$second = $diff->format('%s');

	    $hours   = $hour > 9 ? $hour : "0{$hour}";
		$minutes = $minute > 9 ? $minute : "0{$minute}";
		$seconds = $second > 9 ? $second : "0{$second}";

	    $diferenca = "{$hours}:{$minutes}:{$seconds}";

	} elseif($f_retorno == 'minutes') {
		$diffSeconds = (new DateTime())->setTimeStamp(0)->add($diff)->getTimeStamp();
		//$diferenca= ($diff->format('%h') * 60) + $diff->format('%i');
		$diferenca = $diffSeconds/60;
	} else {
		$diferenca = $diff->days;
	}
	return $diferenca;
}

//ANALISA SE UMA DATA É VÁLIDA
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function dataFormatar($data, $formato = "d/m/Y")
{
	if ($data == "0000-00-00")
		return "";
		
    return date($formato, strtotime($data));
}


/*
 * função para retornar data com mesmo dia próximo mês
 */
function proximaDataMes($data_atual, $separador_data = '-') {
    $mes = get_data_exploded($data_atual, 1, false, $separador_data);
    $dia = get_data_exploded($data_atual, 2, false, $separador_data);
    $ano = get_data_exploded($data_atual, 0, false, $separador_data);

    $next_data = date('Y-m-d', strtotime("{$ano}-{$mes} last day of next month"));
    $ultimo_dia_prox_mes = get_data_exploded($next_data, 2, false, $separador_data);
    $proximo_mes = get_data_exploded($next_data, 1, false, $separador_data);
    $proximo_ano = get_data_exploded($next_data, 0, false, $separador_data);

    // Formate o dia e o mês com dois dígitos
    $dia_formatado = sprintf('%02d', $dia);
    $mes_formatado = sprintf('%02d', $proximo_mes);

    if ($dia > $ultimo_dia_prox_mes) {
        $next_data = "{$proximo_ano}-{$mes_formatado}-{$ultimo_dia_prox_mes}";
    } else {
        $next_data = "{$proximo_ano}-{$mes_formatado}-{$dia_formatado}";
    }

    return $next_data;
}


/**
 * Retorna a quantidade de dias do mes
*/
if ( ! function_exists('dias_no_mes')){
	function dias_no_mes($mes, $ano) {
		return date('t', mktime(0, 0, 0, $mes, 1, $ano));
	}
}

/**
 * Retorna a data formatada
*/
function data_formatada($data, $tem_ano = true) {
	$data = dataFormatar($data);

	if ($data == "") return '';

	$dt = explode("/", $data);

	if ($tem_ano) {
		return $dt[0].' de '.data_mes_texto_completo($dt[1]).' '.$dt[2];
	} else {
		return $dt[0].' de '.data_mes_texto_completo($dt[1]);
	}

	
}