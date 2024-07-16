<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Usuario
		extract($CAD -> coletaDados($id,6));
		$titulo = " - EDITAR";
		$oper = 1;
		
		// corrigindo moeda
		$custo_mes = number_format($custo_mes,2,",",".");
	} else {
		$ccid = $numero = $operadora = $mb_mes = $custo_mes = "";
		$titulo = " - CADASTRAR";
		$oper = 2;
	}
	
	// Template VENDEDORES
	$TChip = new Template(substr($template_dir,3)."/cadastros_chips.html");
	$TChip -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TChip -> Set("oper",		$oper);
	$TChip -> Set("id",			$id);
	$TChip -> Set("ccid",		$ccid);
	$TChip -> Set("numero",		$numero);
	$TChip -> Set("operadora",	$operadora);
	$TChip -> Set("mb_mes",		$mb_mes);
	$TChip -> Set("custo_mes",	$custo_mes);
	
	// Apresentando o Formulario
	$TChip -> Show("Chips_Formulario");
	
	// Definindo os STATUS
	$st = array(	0 => "Cadastrado",
					1 => "Habilitado",
					2 => "Em uso",
					3 => "Cancelado"	);
	
	// Construindo ARRAY do Select
	foreach ($st as $key => $value) {
		$selected = ($key == $status)?1:0;
		$aStatus[] = array($key,$value,$selected);
	}
	// Apresentando o SELECT de STATUS
	$HTML -> select("status",$aStatus);	
	
	// Finalizando o Formulario
	$TChip -> Show("Chips_Formulario_FIM");	
?>