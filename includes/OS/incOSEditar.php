<?php

	// Classe necessaria
	include_once '../incVerificaSessao.php';
	include("../classes/Cadastro.class.php");
	include("../classes/Util.class.php");
	
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
        
	$cont = $CAD->coletaDados($id, 8);
	// Coletando os dados do CONTRATO
	//extract($CAD -> coletaDados($cont['id_cliente'],7));
	// Coletando os dados do CLIENTE
	$cli = $CAD -> coletaDados($cont['id_cliente'],1);

	$T = new Template(substr($template_dir,3)."/os_manutencao.html");
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("OS_Titulo","OS - ".Util::zeros(6, $cont['id']));
	$T -> Show("OS_Titulo");
	
	// Colocando o sub-titulo na pagina
	$T -> Set("OS_SubTitulo_Id",$cont['tipo']." - ".utf8_encode($cli['nome']));
	$T -> Set("OS_SubTitulo_Nome",utf8_encode($nome));
	$T -> Show("OS_SubTitulo");
	
	$T -> Set("id_contrato",$id);
	$T -> Set("id_cliente",$id_cliente);
	$T -> Set("nome",utf8_encode($cli['nome']));
	$T -> Set("quantidade_equipamentos",$quantidade_veiculos);
	
	$endereco  = $cont['endereco_destino'];
        $T -> Set("solicitante",$cont['solicitante']);
        $T -> Set("data_solicitacao",Util::formataData($cont['data_solicitacao']));
        $T -> Set("contato",$cont['contato']);
        $T -> Set("telefone",$cont['telefone']);
        $T -> Set("endereco_destino",utf8_encode($cont['endereco_destino']));
        $T -> Set("instalador",utf8_encode($cont['nome']));
        $T -> Set("data_inicial",Util::formataData($cont['data_inicial']));
        $T -> Set("data_final",Util::formataData($cont['data_final']));
        $T -> Set("hora_inicial",$cont['hora_inicial']);
        $T -> Set("hora_final",$cont['hora_final']);
	$T -> Set("endereco_destino",utf8_encode($endereco));
        $T -> Set("observacoes",utf8_encode($cont['observacoes']));	
	$T -> Show("OS_Instalacao_Formulario");
	
        // Coletando lista de Clientes
	//$clientes = Util::arraySelectClientes($DB,$id_cliente);
	// Apresentando o SELECT de Clientes
	//$HTML -> select("id_cliente",$clientes);        
        
	// Apresentando o SELECT de Instaladores
	$instalador = Util::arraySelectInstaladores($DB,$cont['id_instalador']);
	//$T -> Set("id_instalador",$instalador);
        $HTML -> select("id_instalador",$instalador);
        
	$T -> Show("OS_Instalacao_Formulario_Continuacao_Instalador");
	
	// Coletando os Modulos Disponiveis
	//$mDisp = $CAD -> coletaModulosDisponiveis();	
	
	// Apresentando os Modulos que estao disponiveis
	//foreach ($mDisp as $key => $value) {
		//$HTML->checkradio(0, "modulos", $key);
		//echo $value."<br>";
	//}	
        
        $mDisp = $CAD -> coletaModulosOS($id);	
	//$HTML -> tabelaCabecalho(array("Equipamentos na OS","Placa do Veiculo"));
	
	// Apresentando os Modulos que estao disponiveis
	foreach ($mDisp as $key => $value) {
		$HTML->checkradio(0, "modulos", $key);
                //$HTML -> tabelaLinha(array($value, " "));
		echo $value."<br>";
	}
	$T -> Show("OS_Instalacao_Formulario_Continuacao_Modulos_Disponiveis");	

	
	
?>