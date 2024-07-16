<?php

	// Verificando a sessao	
	include_once 'incVerificaSessao.php';
	
	extract($_REQUEST);
	
	switch($acao){
		
		/*
		 * -> USUARIOS
		 * ------------
		 */
		case 'CADASTRO_USUARIO_CADASTRAR':
		case 'CADASTRO_USUARIO_EDITAR':
			include_once 'Cadastros/incCadastrosUsuarios.php';
			break;	

		case 'CADASTRO_USUARIO_ENVIAR':
			include_once '../classes/Usuario.class.php';
			$USU = new Usuario();
			$USU -> editarUsuario($DB,$LOG,$_REQUEST);
			break;
			
		case 'CADASTRO_USUARIO_BLOQUEAR':
			include_once '../classes/Usuario.class.php';
			$USU = new Usuario();
			echo $USU -> bloquearUsuario($DB,$LOG,$id,$login,0);
			break;
						
		case 'CADASTRO_USUARIO_DESBLOQUEAR':
			include_once '../classes/Usuario.class.php';
			$USU = new Usuario();
			echo $USU -> bloquearUsuario($DB,$LOG,$id,$login,1);
			break;

		/*
		 * -> CADASTROS
		* --------------
		* TIPO : 1=Cliente, 2=Vendedor, 3=Instalador, 4=Fornecedor, 5=Equipamento, 6=Chip, 7=EquipamentoInstalador
		*/
		case 'CADASTRO_CADASTRAR':
		case 'CADASTRO_EDITAR':
			switch ($tipo) {
				case 1: include_once 'Cadastros/incCadastrosClientesAbas.php'; break;
				case 2: include_once 'Cadastros/incCadastrosVendedores.php'; break;
				case 3: include_once 'Cadastros/incCadastrosInstaladores.php'; break;
				case 4: include_once 'Cadastros/incCadastrosFornecedores.php'; break;
				case 5: include_once 'Cadastros/incCadastrosEquipamentos.php'; break;
// 				case 6: include_once 'Cadastros/incCadastrosChips.php'; break;
				case 7: include_once 'Cadastros/incCadastrosEquipamentosInstalador.php'; break;
			}
			break;
		
		case 'CADASTRO_ENVIAR':
			include_once '../classes/Cadastro.class.php';
			$CAD = new Cadastro();
			if($oper == 1) echo $CAD -> editar($_REQUEST,$tipo);
			else echo $CAD -> cadastrar($_REQUEST,$tipo);
			break;
		
		case 'CADASTRO_BLOQUEAR':
		case 'CADASTRO_DESBLOQUEAR':
			include_once '../classes/Cadastro.class.php';
			$CAD = new Cadastro();
			switch ($tipo) {
				case 5: $nome = $serial; break;
// 				case 6: $nome = $ccid; break;
			}
			echo $CAD -> alteraStatus($id,$tipo,$nome,$status);
			break;
			
		case 'CADASTRO_EQUIPAMENTOS_ABAS':
			switch ($abaId) {
				case "dados": 		include_once 'Cadastros/incCadastrosEquipamentosAbaDados.php'; 		break;
				case "historico": 	include_once 'Cadastros/incCadastrosEquipamentosAbaHistorico.php'; 	break;
			}
			break;
			
		case 'CADASTRO_EQUIPAMENTOS_INSTALADOR':
			include_once '../classes/Cadastro.class.php';
			$CAD = new Cadastro();
			$respAdic = $respRemov = true;
			if($adicionar != "") $respAdic  = $CAD -> adicionarModulosInstalador($id,$adicionar);
			if($remover != "") 	 $respRemov = $CAD -> removerModulosInstalador($id,$remover);
			if($respAdic === true && $respRemov === true) echo "ok";
			else echo "erro";
			break;
							
		case 'CADASTRO_CHIPS_ABAS':
			switch ($abaId) {
				case "dados": 		include_once 'Cadastros/incCadastrosChipsAbaDados.php'; 		break;
				case "historico": 	include_once 'Cadastros/incCadastrosChipsAbaHistorico.php'; 	break;
			}
			break;
							
		case 'CADASTRO_CLIENTE_ABAS':
			switch ($abaId) {
				case "dados": 		include_once 'Cadastros/incCadastrosClientesAbaDados.php'; 		break;
				case "contratos": 	include_once 'Cadastros/incCadastrosClientesAbaContratos.php'; 	break;
				case "os": 		include_once 'Cadastros/incCadastrosClientesAbaOS.php'; 		break;
				case "veiculos": 	include_once 'Cadastros/incCadastrosClientesAbaVeiculos.php'; 	break;
			}
			break;	

		/*
		 * -> CONTRATOS
		* --------------
		*/
		case 'CONTRATO_CADASTRAR':
		case 'CONTRATO_EDITAR':
			include_once 'Contratos/incContratos.php'; break;
			break;	

		case 'CONTRATO_IMPRIMIR':
			include_once 'Contratos/incContratosImprimir.php'; break;
			break;
			
		case 'CONTRATO_ABAS':
			switch ($abaId) {
				case "dados": 		include_once 'Contratos/incContratosAbaDados.php'; 		break;
				case "historico": 	include_once 'Contratos/incContratosAbaHistorico.php'; 	break;
				case "os": 		include_once 'Contratos/incContratosAbaOS.php'; 		break;
				case "veiculos": 	include_once 'Contratos/incContratosAbaVeiculos.php'; 	break;
			}
			break;
			
			
		/*
		 * -> OS
		* --------------
		*/
		case 'OS':
			switch ($tipo) {
				case "1": include_once 'OS/incOSInstalacao.php'; break;
				case "2": include_once 'OS/incOSManutencao.php'; break;
				case "3": include_once 'OS/incOSTroca.php'; 	 break;
				case "4": include_once 'OS/incOSRetirada.php'; 	 break;
			}
		break;

		case 'OS_CADASTRAR':
			include_once '../classes/Cadastro.class.php';
			$CAD = new Cadastro();
			if($CAD -> cadastrarOS($_REQUEST, $tipo_os) === true) echo "cadastrado";
			else echo "erro";	
// 			echo $CAD -> cadastrarOS($_REQUEST, $tipo);
		break;
                
                case 'OS_IMPRIMIR':
			include_once 'OS/incOSImprimir.php'; break;
			break;
               /*
		 * -> UPLOAD
		* --------------
		*/
                case 'ARQUIVO_ENVIAR':
                case 'ARQUIVO_BAIXAR':
			include_once 'Documentos/incArquivo.php'; break;
			break;

		case 'CADASTRO_ARQUIVO':
			include_once '../classes/Cadastro.class.php';
			$CAD = new Cadastro();
			echo $CAD -> cadastrarFILE($_REQUEST,$tipo);
                        
			break;
               
                    
	}
	
?>