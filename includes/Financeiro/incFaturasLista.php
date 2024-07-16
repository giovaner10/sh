<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	
	$T = new Template($template_dir."/faturas.html");
        
	// Definindo tpldir
	$T -> Set("tpldir",substr($template_dir,6));
        
        if($acao=='FATURA_TROCA'){
                    include 'cfg.php';
                    mysql_select_db($dbs, $DB_);
                    
                    $query = "SELECT boleto_vencimento from cad_faturas where numero='$nfat' AND status not in('0','1')";
                    $lista = mysql_query($query, $DB_) or die(mysql_error());
                    $row_lista = mysql_fetch_assoc($lista);
                    $vven=$row_lista['boleto_vencimento'];
                    
                    $query = "UPDATE cad_faturas SET numero='$nfat' WHERE id=$id AND status not in('0','1')";
                    $lista = mysql_query($query, $DB_) or die(mysql_error());

                    $query = "SELECT  SUM(quantidade*valor_unitario) as tfat from cad_faturas where numero='$nfat' AND status not in('0','1')";
                    $lista = mysql_query($query, $DB_) or die(mysql_error());
                    $row_lista = mysql_fetch_assoc($lista);
                    
                    $vfat=$row_lista['tfat'];
                    
                    $query = "UPDATE cad_faturas SET boleto_vencimento='$vven', valor_boleto='$vfat', total_fatura='$vfat' WHERE numero='$nfat' AND status not in('0','1')";
                    $lista = mysql_query($query, $DB_) or die(mysql_error());
                    
                    
        }
        
        
        if (isset($idi)){
                    include 'cfg.php';
                    mysql_select_db($dbs, $DB_);
                    $query = "UPDATE cad_faturas SET status=0 WHERE numero=$idi";
                    $lista = mysql_query($query, $DB_) or die(mysql_error());
        }
	// Colocando o titulo na pagina
	$T -> Set("Faturas_Titulo","Faturamento".$idi);
	$T -> Show("Faturas_Titulo");

	// Definindo os parametros da acao do Flexigrid
	$T -> Show("Contratos_Acao");
	
	// Definindo Flexigrid
	$arrParametros = array ( "Grid_Id"        => "gridFaturas", 
							 "Grid_Titulo"    => "Lista de Faturas", 
							 "Grid_Tabela"    => "cad_faturas f, cad_clientes c ", 
							 "Grid_Campos"    => "f.id, f.numero, f.id_contrato, c.nome, date_format(f.boleto_vencimento, \'%d/%m/%Y\') AS data, f.quantidade, (f.valor_boleto + f.taxa_boleto) as total, CASE f.status WHEN 0 THEN \'A Pagar\' WHEN 1 THEN \'Pago\' WHEN 2 THEN \'Pendente de Envio\' END AS status", 
							 "Grid_Condicao"  => "f.id_cliente = c.id ", 
							 "Grid_SortName"  => "f.data_vencimento, c.nome, f.id_contrato ", 
							 "Grid_SortOrder" => "asc ",
							 "Grid_Linhas"	  => "15",
							 "Grid_Width" 	  => "730",
							 "Grid_Height" 	  => "350" );
	
	$arrColunas[] = array ( "", "id", "20", "left" );
	$arrColunas[] = array ( "Fatura", "f.numero", "40", "center" );
        $arrColunas[] = array ( "Contrato", "f.id_contrato", "55", "center" );
	$arrColunas[] = array ( "Cliente", "c.nome", "250", "left" );
	$arrColunas[] = array ( "Vencimento", "data", "80", "center" );
        $arrColunas[] = array ( "Qtd", "quantidade", "30", "center" );
        $arrColunas[] = array ( "Valor", "total", "50", "right" );
	$arrColunas[] = array ( "Status", "f.status", "200", "left" );
	
	$arrBusca[] = array ( "Num. Fatura", "f.numero", "true" );
	$arrBusca[] = array ( "Cliente", "c.nome", "false" );
//,"Lanc. Diversos"
        if ($_SESSION["usuario_perfil"]=='1'){
            $arrBotoes = array("Gerar","Editar","Imprimir","Lanc. Diversos","Alt.Data Pagto","Ret.Taxa","Baixar");
        }
        else
        {
            $arrBotoes = array("Gerar","Editar","Imprimir","Lanc. Diversos","Alt.Data Pagto","Ret.Taxa");    
        }

	$verBotoes = TRUE;
	
	$HTML -> grid($arrBotoes, $arrParametros, $arrColunas, $arrBusca, $verBotoes);
	
?>