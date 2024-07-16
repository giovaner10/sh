<?php
	include_once '../incVerificaSessao.php';
	include_once '../../classes/Usuario.class.php';
	
	$U = new Usuario();
?>

			<ul class="sf-menu sf-vertical">
				<li class="current"><a href="#a">Cobrança<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li <?=$U->mostraLink($DB,"financeiro","")?>><a href="#aa">Boletos</a></li>
						<li <?=$U->mostraLink($DB,"financeiro","")?>><a href="#aa">Remessas</a></li>
						<li <?=$U->mostraLink($DB,"financeiro","")?>><a href="#aa">Retornos</a></li>
						<li <?=$U->mostraLink($DB,"financeiro","")?>><a href="#aa">Baixas<span class="sf-sub-indicator"> &#187;</span></a>
							<ul>
								<li <?=$U->mostraLink($DB,"financeiro","")?>><a href="#aaa">Manual</a></li>
								<li <?=$U->mostraLink($DB,"financeiro","")?>><a href="#aaa">Automática</a></li>
							</ul>
						</li>
					</ul>	
				</li>
				<li class="current"><a href="#a">Multa Recisória<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li <?=$U->mostraLink($DB,"financeiro","")?>><a href="#aa">Cálculo da Multa</a></li>
					</ul>
				</li>							
				<li class="current" <?=$U->mostraLink($DB,"financeiro","")?>><a href="#a">Relatórios</a></li>
                                <li class="current" <?=$U->mostraLink($DB,"financeiro","")?>><a href="#a">Digitalizar</a></li>
			</ul>
			