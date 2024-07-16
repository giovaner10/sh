<?php
	include_once '../incVerificaSessao.php';
	include_once '../../classes/Usuario.class.php';	
	$U = new Usuario();
?>
	<ul class="sf-menu sf-vertical">
      <li>
         <a href="/show/chat/operator/users.php" target="_blank">Chat On-line</a>
      </li>
      <li>
			<a href="http://sistema.showtecnologia.com:85/show/controleinterno" target="controleinterno">Controle Interno</a>
		</li>
      <li id="Gestor">
			<a href="http://sistema.showtecnologia.com:85/show/GR" target="gestor">Gestor</a>
		</li>
      <li id="Site">
			<a href="http://showtecnologia.com" target="site">Site Show</a>
		</li>
      <li id="Cpr">
			<a href="http://sistema.showtecnologia.com:82/cpr/" target="site">CPR</a>
		</li>
      <li id="FLW">
		   <a href="http://www.fleetlink.com.br/index.php/usuario/autentica" target="site">Fleet Link Web</a>
		</li>                                
      <li id="FSW">
		   <a href="http://sistema.showtecnologia.com/fleetsystems/fsweb_dev.dll" target="site">Fleet System Web</a>
		</li>                                                    
      <li <?=$U->mostraLink($DB,"cad_clientes","CadastrosClientesLista")?>><a href="#aa">Veiculos Desatualizados</a></li>
      <li id="CMD">
		   <a href="http://sistema.showtecnologia.com:85/show/GR/CoMaNdOs/cmds.php" target="site">Comandos Maxtrack</a>
		</li>                                                    

   </ul>
