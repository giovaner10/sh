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

                        
		case 'CADASTRO_ENVIAR_EQUIPAMENTOS':
			include_once '../classes/Cadastro.class.php';
			$CAD = new Cadastro();
			if($oper == 1) echo $CAD -> editar($_REQUEST,$tipo);
			else echo $CAD ->cadastrar_equipamento($_REQUEST,$tipo);
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
                    
		case 'CADASTRO_NOTAFISCAL_ABAS':
			switch ($abaId) {
				case "dados": 		include_once 'Cadastros/incCadastrosNotaFiscalAbaDados.php'; 		break;
				//case "historico": 	include_once 'Cadastros/incCadastrosEquipamentosAbaHistorico.php'; 	break;
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
                        
                case 'CADASTRO_VEICULO_ABAS':
			switch ($abaId) {
				case "dados": 		include_once 'Cadastros/incCadastrosVeiculoAbaDados.php'; 	break;
				case "historico": 	include_once 'Cadastros/incCadastrosVeiculoAbaHistorico.php'; 	break;
			}
			break;
                        
							
		case 'CADASTRO_CHIPS_ABAS':
			switch ($abaId) {
				case "dados": 		include_once 'Cadastros/incCadastrosChipsAbaDados.php'; 		break;
				case "historico": 	include_once 'Cadastros/incCadastrosChipsAbaHistorico.php'; 	break;
			}
			break;

							
		case 'CADASTRO_CLIENTE_ABAS':
                        
			switch ($abaId) {
				case "dados":           include_once 'Cadastros/incCadastrosClientesAbaDados.php';	break;
				case "propostas": 	include_once 'Cadastros/incCadastrosClientesAbaPropostas.php'; 	break;
                                case "proposta": 	include_once 'Cadastros/incPropostasAbaDados.php'; 	break;; 	break;
                                case "contratos": 	include_once 'Cadastros/incCadastrosClientesAbaContratos.php'; 	break;
				case "os": 		include_once 'Cadastros/incCadastrosClientesAbaOS.php'; 	break;
                        	case "veiculos": 	include_once 'Cadastros/incCadastrosClientesAbaVeiculos.php'; 	break;
                                case "modulos": 	include_once 'Cadastros/incCadastrosClientesAbaModulos.php'; 	break;
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
                    
		case 'PROPOSTA_EMAIL':
                        include_once 'Cadastros/incPropostaEmail.php'; break;
			break;
                    
		case 'MOSTRA_VEICULO_DESATUALIZADO':
                        include_once 'Suporte/incVeiculoDesatualizado.php'; break;
			break;

		case 'ABRE_GESTOR':
                        include_once 'Suporte/incAbreGestor.php'; break;
			break;
                    
                
                case 'CONTRATO_EMAIL':
			include_once 'Contratos/incContratosEmail.php'; break;
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
                    
                case 'OS_EDITAR':
			include_once 'OS/incOSEditar.php';
                        echo "editado";
			break;
                    
                case 'OS_FECHAR':
                        $_SESSION['nom_cli'] = $cli;
                        $_SESSION['tipo'] = $tip;
                        $_SESSION['os'] = $id;
                        include_once 'OS/incEquipamentosOSLista.php';
			break;
                  
  		case 'OS_DEVOLVER':
                        include_once '../classes/Cadastro.class.php';
			//include_once '../classes/Usuario.class.php';
                        $CAD = new Cadastro();
                        $cont = $CAD->coletaDados($id, 10);
                        $os = $cont['id_os'];
                        $modulo = $cont['id_equipamento'];
                        $serial = $cont['serial'];
                        $instalador = $cont['id_instalador'];
                        //if ($cont['status']==0){
                            $USU = new Cadastro();
                            switch ($tipo){
                                case 1: echo $USU ->devolverModulos($DB,$LOG,$id,$login,2,$os,$modulo,$serial,'Instalador','4',$instalador); break;
                                case 2: echo $USU ->devolverModulos($DB,$LOG,$id,$login,2,$os,$modulo,$serial,'Empresa','1',$instalador); break;
                                case 3: echo $USU ->devolverModulos($DB,$LOG,$id,$login,2,$os,$modulo,$serial,'Teste','2',$instalador); break;
                            }
                        //}
                        $id=$os;
			break;

  		case 'OS_INSTALADA':
                    
                        include_once 'Contratos/incContratosAbaVeiculos.php'; 
                    
                        include_once '../classes/Cadastro.class.php';
			include_once '../classes/Usuario.class.php';
                        
                        $CAD = new Cadastro();
                        $cont = $CAD->coletaDados($id, 10);
                        $os = $cont['id_os'];
                        $modulo = $cont['id_equipamento'];
                        $serial = $cont['serial'];
                        $instalador = $cont['id_instalador'];
                        $contrato = $cont['id_contrato'];
                        $cliente = $cont['id_cliente'];
                        $cont_os = $CAD->coletaDados($os, 12);
                        $cliente = $cont_os['id_cliente'];

                        $USU = new Cadastro();
                        echo $USU ->instaladoModulos($DB,$LOG,$id,$login,1,$os,$modulo,$serial,'Instalador','4',$instalador,$placa,$contrato,$cliente);
                        $id=$os;
			break;

  		case 'OS_ADICIONAR':
                        include_once '../classes/Cadastro.class.php';
                    
                        $CAD = new Cadastro();
                        $modulo = $serial;
                        $cont = $CAD->coletaDados($modulo, 11);
                        if ($cont['id'] != ''){
                            $modulo = $cont['id'];
                        }
                        else
                        {
                            echo '<script type="text/javascript">var nmod = prompt ("Entre com o MODELO do MODULO!","")</script>';
                            $modulo = '00';
                        }
                        //$CAD1 = new Cadastro();
                        //$cont1 = $CAD1->coletaDados($os, 12);
                        
                        $nos = $_SESSION['os'];
                        
                        
                        
                        $contrato = '1';
                      
                        $USU = new Cadastro();
                        echo $USU ->adicionarModulos($nos,$modulo,$contrato);
                        $id=$os;
			break;

                    
  		case 'OS_DESFAZER':
                    	include_once '../classes/Cadastro.class.php';
			include_once '../classes/Usuario.class.php';
	$CAD = new Cadastro();
	$cont = $CAD->coletaDados($id, 10);
	$os = $cont['id_os'];
        $modulo = $cont['serial'];
                        if ($cont['status']!=0){
			$USU = new Cadastro();
			echo $USU ->desfazerModulos($DB,$LOG,$id,$login,0,$os,$modulo,'0');
                        }
                        $id=$os;
			break;
                    
               /*
		 * -> UPLOAD
		* --------------
		*/
                case 'ARQUIVO_ENVIAR':
                case 'ARQUIVO_BAIXAR':
			include_once 'Documentos/incArquivo.php'; break;
			break;
                case 'ARQUIVO_PROPOSTAS':
			include_once 'Documentos/incArquivoP.php'; break;
			break;
                    

		case 'CADASTRO_ARQUIVO':
			include_once '../classes/Cadastro.class.php';
			$CAD = new Cadastro();
			echo $CAD -> cadastrarFILE($_REQUEST,$tipo);
                        
			break;
               
		case 'CADASTRO_FATURAS':
			include_once '../classes/Cadastro.class.php';
			$CAD = new Cadastro();
			if($CAD -> novo_gerar_faturas($_REQUEST, $cli, $mes, $ano, $ins) === true){
				echo "cadastrado";
			}else{
				echo "erro";	
			}
			break;
                        
                case 'CADASTRO_BAIXAS':
                    include 'cfg.php';
                    mysql_select_db($dbs, $DB_);
                    $query = "SELECT numero FROM cad_faturas WHERE id = '$id'";
                    $lista = mysql_query($query, $DB_) or die(mysql_error());
                    $row_lista = mysql_fetch_assoc($lista);
                    $numero = $row_lista['numero'];
                    list($dia,$mes,$ano) = explode("/",$dpago);
                    $pgt = "$ano-$mes-$dia";

                    $query = "UPDATE cad_faturas SET status=1, valor_pago='$valor', data_pagto='$pgt' WHERE numero = '$numero'";
                    $lista = mysql_query($query, $DB_) or die(mysql_error());
                    
		mysql_free_result($lista);
                echo "cadastrado";
			break;                        

		case 'CADASTRO_FATURAS_DIVERSAS':
			include_once '../classes/Cadastro.class.php';
			$CAD = new Cadastro();
			if($CAD -> gerar_faturas_diversas($_REQUEST, $id, $des, $qtd, $vlr, $cnt, $nro) === true) echo "cadastrado";
			else echo "erro";	
			break;
                        
                        
                case 'FATURA_IMPRIMIR':
                    include_once '../incVerificaSessao.php';
                    include_once 'Financeiro/incFaturasImprimir.php';

//			include_once 'Financeiro/fatura.php'; break;
			break;
                    
                case 'FATURA_DIVERSOS':
			//include_once 'OS/incOSEditar.php';
                        //echo "editado";
			break;
                    
                  case 'RELATORIO_RECEBER':
                    include_once '../incVerificaSessao.php';
                    include_once 'Financeiro/incFaturasImprimirreceber.php';

			break;

                  case 'RELATORIO_PAGOS':
                    include_once '../incVerificaSessao.php';
                    include_once 'Financeiro/incFaturasImprimirpagos.php';

			break;
                    
                case 'ALTERA_BOLETO':
                    extract($_REQUEST);
                    include 'cfg.php';
                    mysql_select_db($dbs, $DB_);
                    //$query = "SELECT numero FROM cad_faturas WHERE id = '$id'";
                    //$lista = mysql_query($query, $DB_) or die(mysql_error());
                    //$row_lista = mysql_fetch_assoc($lista);
                    //$numero = $row_lista['numero'];
                    list($dia,$mes,$ano) = explode("/",$dven);
                    $pgt = "$ano-$mes-$dia";

                    $query = "UPDATE cad_faturas SET boleto_vencimento='$pgt', multa='$multa', juros='$juros', valor_boleto='$valor_boleto'  WHERE numero = '$nro'";
                    $lista = mysql_query($query, $DB_) or die(mysql_error());
                    
		mysql_free_result($lista);
                echo "cadastrado";
			break;                        
                   
                 case 'RETIRAR_TAXA':
                    include_once '../classes/Cadastro.class.php';
                    $CAD = new Cadastro();
                    if($CAD -> taxa($nfat) === true) echo "alterado";
                    else echo "erro";	
		 break;
                     
                    
                      
	}
	
?>