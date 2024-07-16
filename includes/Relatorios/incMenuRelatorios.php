<?php
	include_once '../incVerificaSessao.php';
	include_once '../../classes/Usuario.class.php';
	
	$U = new Usuario();
?>

			<ul class="sf-menu sf-vertical">
				<li class="current">
					<a href="#a">Cadastros<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li <?=$U->mostraLink($DB,"cad_usuarios","Relatorios-CadastroUsuarios")?>><a href="#aa">Usu&aacute;rios Cadastrados</a></li>
					</ul>
				</li>
				<li class="current" <?=$U->mostraLink($DB,"cad_usuarios","RelatoriosAcessos")?>><a href="#a">Acessos e A&ccedil;&otilde;es</a></li>
                                <li class="current" <?=$U->mostraLink($DB,"relatorios","")?>><a href="#a">Digitalizar</a></li>
			</ul>