<?php
	include_once '../incVerificaSessao.php';
	include_once '../../classes/Usuario.class.php';
	
	$U = new Usuario();
?>

			<ul class="sf-menu sf-vertical">
                            	<li class="current"><a href="#a">Sistema<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li <?=$U->mostraLink($DB,"cad_usuarios","CadastrosUsuariosLista")?>><a href="#aa">Usuários</a></li>
					</ul>
				</li>
				<li class="current"><a href="#a">Administrativo<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li <?=$U->mostraLink($DB,"cad_clientes","CadastrosClientesLista")?>><a href="#aa">Clientes</a></li>
						<li <?=$U->mostraLink($DB,"cad_administrativo","CadastrosVendedoresLista")?>><a href="#aa">Vendedores</a></li>
						<li <?=$U->mostraLink($DB,"cad_administrativo","CadastrosInstaladoresLista")?>><a href="#aa">Instaladores</a></li>
						<li <?=$U->mostraLink($DB,"cad_administrativo","CadastrosFornecedoresLista")?>><a href="#aa">Fornecedores</a></li>
                                                <li <?=$U->mostraLink($DB,"cad_administrativo","CadastrosNotaFiscalLista")?>><a href="#aa">Notas Fiscais</a></li>
					</ul>
				</li>	
				<li class="current"><a href="#a">Equipamentos<span class="sf-sub-indicator"> &#187;</span></a>
					<ul>
						<li><a href="#aa">Chips<span class="sf-sub-indicator"> &#187;</span></a>
							<ul>
								<li <?=$U->mostraLink($DB,"cad_equipamentos","CadastrosChipsLista")?>><a href="#aaa">Listar</a></li>
								<li <?=$U->mostraLink($DB,"cad_equipamentos","ImportaChips")?>><a href="#aaa">Importar Arquivo</a></li>
								<li <?=$U->mostraLink($DB,"cad_equipamentos","")?>><a href="#aaa">Desbloquear<br>na Operadora</a></li>
								<li <?=$U->mostraLink($DB,"cad_equipamentos","")?>><a href="#aaa">Cancelar<br>na Operadora</a></li>
							</ul>						
						</li>
						<li><a href="#aa">Módulos<span class="sf-sub-indicator"> &#187;</span></a>
							<ul>
								<li <?=$U->mostraLink($DB,"cad_equipamentos","CadastrosEquipamentosLista")?>><a href="#aaa">Listar</a></li>
								<li <?=$U->mostraLink($DB,"cad_equipamentos","CadastrosEquipamentosInstaladorLista")?>><a href="#aaa">Estoque do Instalador</a></li>
							</ul>						
						</li>
					</ul>
				</li>	
                                <!--<li class="current" <?=$U->mostraLink($DB,"documentos","Digitalizar")?>><a href="#a">Digitalizar</a></li>-->
			</ul>