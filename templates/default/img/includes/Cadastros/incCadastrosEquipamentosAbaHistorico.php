<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Usuario
		$d = $CAD -> coletaHistorico($id,5);
		$num = sizeOf($d);
		
		// Apresentando os Dados
		$HTML -> tabelaCabecalho(array("Data","Ocorr&ecirc;ncia"));
		for ($i = 0; $i < $num; $i++) {
			$sel = ($i%2==0)?"":1;
			$HTML -> tabelaLinha(array($d[$i]['data'],utf8_encode($d[$i]['ocorrencia'])),$sel);
		}
		$HTML -> tabelaRodape();		

	} 
	

	
?>