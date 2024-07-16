<?php
	include_once '../incVerificaSessao.php';
	include_once '../../classes/Usuario.class.php';
	
	$U = new Usuario();
?>

			<ul class="sf-menu sf-vertical">
				<li class="current" <?=$U->mostraLink($DB,"documentos","ListaArquivosAgenda")?>><a href="#a">Agenda</a></li>
				<li class="current" <?=$U->mostraLink($DB,"documentos","ListaArquivosCadastro")?>><a href="#a">Cadastro</a></li>
                                <li class="current" <?=$U->mostraLink($DB,"documentos","ListaArquivosContratos")?>><a href="#a">Contrato</a></li>
                                <li class="current" <?=$U->mostraLink($DB,"documentos","ListaArquivosDiversos")?>><a href="#a">Diversos</a></li>
                                <li class="current" <?=$U->mostraLink($DB,"documentos","ListaArquivosFinanceiro")?>><a href="#a">Financeiro</a></li>
                                <li class="current" <?=$U->mostraLink($DB,"documentos","ListaArquivosOS")?>><a href="#a">OS</a></li>
                                <li class="current" <?=$U->mostraLink($DB,"documentos","ListaArquivosRelatorios")?>><a href="#a">Relatório</a></li>
                                <li class="current" <?=$U->mostraLink($DB,"documentos","ListaArquivosUsuarios")?>><a href="#a">Usuário</a></li>
				
				
			</ul>
			