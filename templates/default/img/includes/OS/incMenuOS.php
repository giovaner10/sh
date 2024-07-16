<?php
	include_once '../incVerificaSessao.php';
	include_once '../../classes/Usuario.class.php';
	
	$U = new Usuario();
?>

			<ul class="sf-menu sf-vertical">
				<li class="current"><a href="#a">Gerar OS<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li class="current" <?=$U->mostraLink($DB,"os","OSListaContratosNovos")?>><a href="#aa">Instalação</a></li>
						<li class="current" <?=$U->mostraLink($DB,"os","OSListaContratosManutencao")?>><a href="#aa">Manutenção</a></li>
						<li class="current" <?=$U->mostraLink($DB,"os","OSListaContratosTroca")?>><a href="#aa">Troca</a></li>
						<li class="current" <?=$U->mostraLink($DB,"os","OSListaContratosRetirada")?>><a href="#aa">Retirada</a></li>
					</ul>
				</li>
				<li class="current" <?=$U->mostraLink($DB,"os","")?>><a href="#a">Fechar OS</a></li>
				<li class="current" <?=$U->mostraLink($DB,"os","OSLista")?>><a href="#a">Lista</a></li>
                                <li class="current" <?=$U->mostraLink($DB,"os","")?>><a href="#a">Digitalizar</a></li>
				
				
			</ul>
			