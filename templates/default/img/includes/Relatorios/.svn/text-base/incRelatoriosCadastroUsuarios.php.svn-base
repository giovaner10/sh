<?php
	include_once '../incVerificaSessao.php';
	require_once '../../classes/Relatorio.class.php';
	
	$R = new Relatorio($DB);
	
	$T = new Template($template_dir."/relatorios.html");
	$T -> Set("tpldir",$template_dir);
	$T -> Set("titulo","RELAT&Oacute;RIO DE USU&Aacute;RIOS");
	
	// Apresentando o INICIO da Pagina
	$T -> Show("InicioDaPagina");
	
	// Coletando os dados
	$dados = $R -> coletaUsuarios();
	$num = sizeOf($dados);
	
	// Apresentando os Dados
	$HTML -> tabelaCabecalho(array("Login","Nome","Perfil","Situa&ccedil;&atilde;o"));
	for ($i = 0; $i < $num; $i++) {
		$sel = ($i%2==0)?"":1;
		$HTML -> tabelaLinha(array($dados[$i]['login'],$dados[$i]['nome'],$dados[$i]['perfil'],$dados[$i]['status']),$sel);
	}	
	$HTML -> tabelaRodape();
	
	// Apresentando o FIM da Pagina
	$T -> Show("FimPagina");	
	
?>