<?php
	include_once '../incVerificaSessao.php';
	include_once '../../classes/Usuario.class.php';
	
	$U = new Usuario();
?>

			<ul class="sf-menu sf-vertical">
				<li class="current"><a href="#a">Vendas<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li <?=$U->mostraLink($DB,"agenda","")?>><a href="#aa">Compromissos</a></li>
					</ul>
				</li>
				<li class="current"><a href="#a">Instalações<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li <?=$U->mostraLink($DB,"agenda","")?>><a href="#aa">Compromissos</a></li>
					</ul>
				</li>
				<li class="current"><a href="#a">Manutenções<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li <?=$U->mostraLink($DB,"agenda","")?>><a href="#aa">Compromissos</a></li>
					</ul>
				</li>
				<li class="current"><a href="#a">Calendário<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li <?=$U->mostraLink($DB,"agenda","")?>><a href="#aa">Geral</a></li>
						<li <?=$U->mostraLink($DB,"agenda","")?>><a href="#aa">Vendas</a></li>
						<li <?=$U->mostraLink($DB,"agenda","")?>><a href="#aa">Instalações</a></li>
						<li <?=$U->mostraLink($DB,"agenda","")?>><a href="#aa">Manutenções</a></li>
					</ul>
				</li>
                                <li class="current" <?=$U->mostraLink($DB,"agenda","")?>><a href="#a">Digitalizar</a></li>
			</ul>