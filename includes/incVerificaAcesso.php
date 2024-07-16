<?php
//Inclui os arquivos das classes necessarias
include_once 'incVerificaSessao.php';
include_once '../classes/Usuario.class.php';

//Instancia o objeto usuario
$U = new Usuario();

//Salva a variavel
$modulo = $_GET['modulo'];

//Troca o nome do modulo pelo campo da tabela
switch($modulo) {
	case 'Financeiro':	 $modulo = 'financeiro'; 	break;
	case 'Contratos':	 $modulo = 'contrato';		break;
	case 'OS':	 	 $modulo = 'os'; 		break;
	case 'Agenda':		 $modulo = 'agenda'; 		break;
}

//Verifica a permissao dos modulos específicos
if ($modulo == 'MeusDados' OR $modulo == 'Relatorios') echo 'true';
else {
	
	// Verificando permissoes do modulo Cadastro
	if ($modulo == 'Cadastros'){
		if ( $U->permissao($DB,"cad_sistema") > 0 ||
			 $U->permissao($DB,"cad_adminisrativo") > 0 ||
			 $U->permissao($DB,"cad_equipamentos") > 0 ||
			 $U->permissao($DB,"cad_clientes") > 0 ||
                         $U->permissao($DB,"cad_usuarios") > 0
		){
				echo 'true';
		} else	echo 'false';
	}
	else{
		// Verificando a permissao dos demais modulos
		echo ($U->permissao($DB,$modulo) > 0)? 'true':'false';
	}
	
}
// echo true;
?>