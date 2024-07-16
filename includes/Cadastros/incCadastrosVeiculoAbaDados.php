<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Usuario
		extract($CAD -> coletaDados($id,13));
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
	$TChip = new Template(substr($template_dir,3)."/cadastros_veiculo.html");
	$TChip -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TChip -> Set("oper",		$oper);
	$TChip -> Set("id",		$id);
	$TChip -> Set("placa",		$placa);
	$TChip -> Set("marca",		$marca);
	$TChip -> Set("modelo", 	$modelo);
	$TChip -> Set("ano",		$ano);
	$TChip -> Set("chassis",	$chassis);
        $TChip -> Set("cor",            $cor);
	
        $TChip -> Set("cli",	$_SESSION['cliente']);
        $TChip -> Set("cont",	$_SESSION['contrato']);
	// Apresentando o Formulario
	$TChip -> Show("Veiculos_Formulario");
	
	// Definindo os STATUS
	//$st = array(	0 => "Cadastrado",
	//				1 => "Habilitado",
	//				2 => "Em uso",
	//				3 => "Cancelado"	);
	
	// Construindo ARRAY do Select
	//foreach ($st as $key => $value) {
		//$selected = ($key == $status)?1:0;
		//$aStatus[] = array($key,$value,$selected);
	//}
	// Apresentando o SELECT de STATUS
	//$HTML -> select("status",$aStatus);	
	
	// Finalizando o Formulario
	$TChip -> Show("Veiculos_Formulario_FIM");	
?>