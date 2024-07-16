<?php
	include_once '../incVerificaSessao.php';
	include_once '../../classes/Usuario.class.php';
	
	$U = new Usuario();
        
        
?>

			<ul class="sf-menu sf-vertical">
				<li class="current"><a href="#a">Cobrança<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li ><a href="newapp/faturas" target="_blank">Faturas</a></li>
                                                <li <?=$U->mostraLink($DB,"financeiro","BoletosLista")?>><a href="#aa">Boletos</a></li>
						<li <?=$U->mostraLink($DB,"financeiro","")?>><a href="#aa">Remessas</a></li>
						<li><a href="newapp/faturas/baixar" target="_blank">Retornos</a></li>
						<!--<li <?=$U->mostraLink($DB,"financeiro","FaturasBMLista")?>><a href="#aa">Baixas Manual</a></li>-->
					</ul>	
				</li>
				<li class="current"><a href="#a">Multa Recisória<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li <?=$U->mostraLink($DB,"financeiro","")?>><a href="#aa">Cálculo da Multa</a></li>
					</ul>
				</li>							
				<li class="current" <?=$U->mostraLink($DB,"financeiro","")?>><a href="#a">Relatórios</a><span class="sf-sub-indicator"> &#187;</span>
                                    <ul>
					<li <?=$U->mostraLink($DB,"financeiro","FaturasReceber")?>><a href="#aaa">A Receber</a></li>
					<li <?=$U->mostraLink($DB,"financeiro","FaturasPagas")?>><a href="#aaa">Recebidos</a></li>
                                        <li <?=$U->mostraLink($DB,"financeiro","")?>><a href="#aaa">Lista Financeira</a></li>
                                    </ul>
                                </li>
                                <li class="current" ><a href="https://servicos.spc.org.br/spc/controleacesso/autenticacao/entry.action" target="_blank">SPC Brasil </a></li>
                                <li class="current" <?=$U->mostraLink($DB,"financeiro","")?>><a href="#a">Digitalizar</a></li>
			</ul>
			