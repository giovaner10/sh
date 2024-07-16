<?php

        include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	
	if (file_exists(substr($template_dir,3)."/propostas.html")){
           $T = new Template(substr($template_dir,3)."/propostas.html");
        }
        else
        {
            $T = new Template(substr($template_dir,0)."/propostas.html");
        }
	
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Colocando o titulo na pagina
	$T -> Set("Contratos_Titulo","Cadastro de Propostas");
//	$T -> Show("Contratos_Titulo");

	// Definindo os parametros da acao do Flexigrid
	$T -> Show("Contratoss_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridPropostas", 
							 "Grid_Titulo"    => "Lista de Propostas", 
							 "Grid_Tabela"    => "propostas c, cad_clientes cl", 
							 "Grid_Campos"    => "c.id,cl.nome,c.quantidade_veiculos,date_format(c.data_cadastro, \'%d/%m/%Y %Hh%i\') AS data,CASE c.status WHEN 0 THEN \'Cadastrado\' WHEN 1 THEN \'Ativo\' WHEN 2 THEN \'Cancelado\' END AS status", 
							 "Grid_Condicao"  => "c.id_cliente = cl.id AND cl.id=".$_SESSION['passa_id'], 
							 "Grid_SortName"  => "id", 
							 "Grid_SortOrder" => "asc",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "630",
							 "Grid_Height" 	  => "350" );

        $arrColunas[] = array ( "Proposta", "id", "60", "center" );
	$arrColunas[] = array ( "Cliente", "cl.nome", "280", "left" );
	$arrColunas[] = array ( "Ve&iacute;culos", "c.quantidades_veiculos", "60", "center" );
	$arrColunas[] = array ( "Data Cadastro", "data", "100", "center" );
	$arrColunas[] = array ( "Status", "status", "60", "centar" );
	
	$arrBusca[] = array ( "Contrato", "id", "true" );
	$arrBusca[] = array ( "Cliente", "cl.nome", "false" );

	$arrBotoes = array("Nova","Alterar","Imprimir","Anexos");

	$verBotoes = true;
        
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
        
?>