<?php
	include_once '../incVerificaSessao.php';
	include_once '../../classes/Usuario.class.php';
	
	$U = new Usuario();
?>

			<ul class="sf-menu sf-vertical">
				<li class="current" <?=$U->mostraLink($DB,"contrato","ContratosLista")?>><a href="#a">Contratos</a></li>
                                <li class="current" <?=$U->mostraLink($DB,"documentos","Digitalizar")?>><a href="#a">Digitalizar</a></li>
			</ul>