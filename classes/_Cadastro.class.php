<?php

/**
 * Classe responsavel pelas acoes de CADASTRO deste sistema
 * 
 * @author Alessandro (atm@clicsolucoes.com.br)
 * 		   06.04.2012
 *
 * Ultima atualizacao: 26.04.2012
 */

class Cadastro {
	/**
	 * Metodo construtor
	 * @param $DB - Objeto com a conexao ao Banco de Dados
	 */
	function __construct() {
		global $_SESSION,$DB,$LOG;
		$this->id 	 = $_SESSION["usuario_id"];
		$this->login = $_SESSION["usuario_login"];
		$this->DB = $DB;
		$this->LOG = $LOG;
	}
	
	/**
	 * Metodo que coleta os dados de uma entidade de cadastro
	 * (Cliente,Vendedor,Instalador,Fornecedor,Equipamento,Chip)
	 * @param $id	- ID da entidade de cadastro
	 * @param $tipo - Inteiro que define o tipo (1=Cliente, 2=Vendedor, 3=Instalador, 4=Fornecedor, 5=Equipamento, 6=Chip, 7=Contrato)
	 * @return Retorna o resultado da consulta
	 */
	function coletaDados($id,$tipo) {
		
		switch ($tipo) {
			case 5:
				$this->DB->selectTab("cad_equipamentos e LEFT JOIN cad_chips c ON e.id_chip = c.id", "e.*, c.ccid", "WHERE e.id = '$id'");
			break;
			
			case 7:
				$this->DB->selectTab("contratos c, cad_clientes cl", "c.*, cl.nome", "WHERE c.id_cliente = cl.id AND c.id = '$id'");
			break;			
			
			default:
				// Define a tabela para a consulta
				$d = $this->defineTabelaETexto($tipo, "");
				// Realizando a consulta
				$this->DB->selectTab($d['tabela'], "*", "WHERE id = '$id'");
			break;
		}

		// Retornando o resultado
		return $this->DB->fetchArray($this->DB->resultado);
	}
	
	
	function cadastrarOS($REQUEST,$tipo) {
		switch ($tipo) {
			case "1": // Instalacao
				// Separando os dados
				$modulos = $REQUEST['modulos'];
				unset($REQUEST['modulos']);
				
				// Preparando os campos
				$c = $this->preparaCampos($REQUEST, 2);
				
				// Cadastrar dados da OS
				$this->DB->insertTab("os (".$c['campos'].")", $c['valores']);
				
				// Coletando IDs
				$id_os = $this->DB->insertId();
				$id_contrato = $REQUEST['id_contrato'];
								
				// Cadastrar modulos
				$mods = explode(",",$modulos);
				$nMods = sizeOf($mods);
				for ($i = 0; $i < $nMods; $i++) {
					// Cadastra modulo
					$this->DB->insertTab("os_equipamentos (id_os,id_equipamento,id_contrato)", "'$id_os','$mods[$i]','$id_contrato'");
					// Alterar status dos modulos na tabela cad_equipamentos
					$this->DB->updateTab("cad_equipamentos", "status = 4", "id = '$mods[$i]'");
					// Registrar historico dos modulos
					$this->DB->insertTab("cad_equipamentos_historico (id_equipamento,status,ocorrencia)", "'$mods[$i]','3','Enviado para instalacao - OS: $id_os'");
				}
				
				// Alterar status do contrato
				$this->DB->updateTab("contratos", "status = 1", "id = '$id_contrato'");
				// Registrar historico do contrato
				$this->DB->insertTab("contratos_historico (id_contrato,status,ocorrencia)", "'$id_contrato','1','Gerada a OS de Instalacao - $id_os'");
				
				// Se cadastrou registra no LOG a acao
				$this->LOG->insertLog($this->DB,'O usuário "'.$this->login.'" CADASTROU OS de Instalacao [ '.$id_os.' ].');
				
			break;
			
			case "2": // Manutencao
				; 
			break;
			case "3": // Troca
				;
			break;
			case "4": // Retirada
				;
			break;
		}
		
		return true;
	}
	
	
	/**
	 * Metodo que CADASTRA uma entidade de cadastro
	 * (Cliente,Vendedor,Instalador,Fornecedor,Equipamento,Chip)
	 * @param $REQUEST 	- ARRAY com os dados
	 * @param $tipo 	- Inteiro que define o tipo (1=Cliente, 2=Vendedor, 3=Instalador, 4=Fornecedor, 5=Equipamento, 6=Chip, 7=Contrato)
	 * @return Retorna TRUE (sucesso) ou FALSE (erro) 
	 */
	function cadastrar($REQUEST,$tipo) {
		
		// Coletando nome
		switch ($tipo) {
			case 5: 	$nome = $REQUEST['serial']; break;
			case 6: 	$nome = $REQUEST['ccid']; 	break;
			case 7: 	$nome = $REQUEST['id']; 	break;
			default:	$nome = $REQUEST['nome']; 	break;
		}
		
		// Definindo a tabela e o texto para o LOG
		$d = $this->defineTabelaETexto($tipo, "CADASTROU");
		
		// Preparando os campos
		$c = $this->preparaCampos($REQUEST, 2);
		
		//Insere os campos no banco
		$this->DB->insertTab($d['tabela']." (".$c['campos'].")", $c['valores']);
		if($this->DB->resultado){
			
			// Coleta o insertId
			$insID = $this->DB->insertId();
			
			switch ($tipo) {
				case 5:
					// Se for MODULO cadastrar o Historico
					$this->DB->insertTab("cad_equipamentos_historico (id_equipamento,ocorrencia)", "'$insID','Cadastrado'");
				break;
				
				case 6:
					// Se for CHIP cadastrar o Historico
					$this->DB->insertTab("cad_chips_historico (id_chip,ocorrencia)", "'$insID','Cadastrado'");
				break;				
				
				case 7:
					// Se for CONTRATO cadastrar o Historico
					$this->DB->insertTab("contratos_historico (id_contrato,ocorrencia)", "'$insID','Cadastrado'");
				break;				
			}
			
			
					
			// Se cadastrou registra no LOG a acao
			$this->LOG->insertLog($this->DB,'O usuário "'.$this->login.'" '. $d['texto'] .' [ '.$nome.' ].');
			$res = true;
		}else $res = false;
		
		return ($res === true)?"cadastrado":"erro";
	}
	
	/**
	 * Metodo que EDITA uma entidade de cadastro
	 * (Cliente,Vendedor,Instalador,Fornecedor,Equipamento,Chip)
	 * @param $REQUEST 	- ARRAY com os dados
	 * @param $tipo 	- Inteiro que define o tipo (1=Cliente, 2=Vendedor, 3=Instalador, 4=Fornecedor, 5=Equipamento, 6=Chip, 7=Contrato)
	 * @return Retorna TRUE (sucesso) ou FALSE (erro)
	 */
	function editar($REQUEST,$tipo) {
	
		// Coletando id
		$id	= $REQUEST['id'];

		// Acoes de acordo com o tipo
		switch ($tipo) {
			case 5:
				// Verifica se esta mudando de CHIP
				if ($REQUEST['id_chip1'] == ""){
					unset($REQUEST['id_chip1']);
					// Definindo a tabela e o texto para o LOG
					$d = $this->defineTabelaETexto($tipo, "EDITOU");
					// Preparando os campos
					$campos = $this->preparaCampos($REQUEST, 1);
					// Faz update no banco
					$this->DB->updateTab($d['tabela'], $campos, "id = '$id'");
					if($this->DB->resultado){
						// Se cadastrou registra no LOG a acao
						$this->LOG->insertLog($this->DB,'O usuário "'.$this->login.'" '. $d['texto'] .' [ '.$REQUEST['serial'].' ].');
						$res = true;
					}else $res = false;					
				}else{
					// Preservando o REQUEST
					$R = $REQUEST;
					$R['id_chip'] = $R['id_chip1'];
					unset($R['id_chip1']);
					
					// Preparando os campos
					$campos = $this->preparaCampos($R,1);
					// Faz update no banco
					$this->DB->updateTab("cad_equipamentos", $campos, "id = '$id'");
					// Coleta o CCID do novo chip
					$this->DB->selectTab("cad_chips","ccid","WHERE id = '".$REQUEST['id_chip1']."'");
					$d = $this->DB->fetchArray($this->DB->resultado);
					
					// Definindo os STATUS do MODULO
					$txt = "Em teste com CHIP ".$d['ccid'];
					$status = $REQUEST['status'];
					$id_chip = $REQUEST['id_chip1'];
					// Registrando historico
					$this->DB->insertTab("cad_equipamentos_historico (id_equipamento,ocorrencia,status,id_chip)", "'$id','$txt','$status','$id_chip'");	

					// Alterar o STATUS do chip Anterior
					$this->DB->updateTab("cad_chips", "status = 1, id_equipamento = ''", "id = '".$REQUEST['id_chip']."'");
					$this->DB->insertTab("cad_chips_historico (id_chip,ocorrencia,status)", "'".$REQUEST['id_chip']."','Habilitado','1'");
					
					// Registrar o NOVO chip
					$this->DB->updateTab("cad_chips", "status = 2, id_equipamento = '$id'", "id = '$id_chip'");
					$this->DB->insertTab("cad_chips_historico (id_chip,ocorrencia,status,id_equipamento)", "'$id_chip','Em uso no MODULO ".$REQUEST['serial']."','2','$id'");
					
					if($this->DB->resultado){
						// Se cadastrou registra no LOG a acao
						$this->LOG->insertLog($this->DB,'O usuário "'.$this->login.'" EDITOU o equipamento [ '.$REQUEST['serial'].' ].');
						$res = true;
					}else $res = false;					
				}
			break;
			
			case 6:
				// Coleta o STATUS atual
				$this->DB->selectTab("cad_chips","status,id_equipamento","WHERE id = '$id'");
				$d = $this->DB->fetchArray($this->DB->resultado);

				// Se houver mudanca de status
				if($d['status'] != $REQUEST['status']){
					
					if($d['status']==2){
						// Alterando o STATUS do modulo Anterior
						$this->DB->updateTab("cad_equipamentos", "status='1',id_chip=''", "id = '".$d['id_equipamento']."'");
						$this->DB->insertTab("cad_equipamentos_historico (id_equipamento,ocorrencia,status)", "'".$d['id_equipamento']."','Retirado o CHIP ".$REQUEST['ccid']."','1'");
					}
					
					// Preparando os campos
					$REQUEST['id_equipamento'] = '';
					$campos = $this->preparaCampos($REQUEST, 1);
					// Faz update no banco
					$this->DB->updateTab("cad_chips", $campos, "id = '$id'");					
										
					// Definindo os STATUS do CHIP
					$st = array(	0 => "Cadastrado",
									1 => "Habilitado",
									2 => "Em uso no MODULO $serial",
									3 => "Cancelado"	);
					// Registrando no historico
					$this->DB->insertTab("cad_chips_historico (id_chip,ocorrencia,status)", "'$id','".$st[$REQUEST['status']]."','".$REQUEST['status']."'");

				}else{
					// Preparando os campos
					$campos = $this->preparaCampos($REQUEST, 1);
					// Faz update no banco
					$this->DB->updateTab("cad_chips", $campos, "id = '$id'");
				}
				
				
				if($this->DB->resultado){
					// Se cadastrou registra no LOG a acao
					$this->LOG->insertLog($this->DB,'O usuário "'.$this->login.'" EDITOU o chip [ '.$REQUEST['ccid'].' ].');
					$res = true;
				}else $res = false;				
			break;			
			
			default:
				// Definindo a tabela e o texto para o LOG
				$d = $this->defineTabelaETexto($tipo, "EDITOU");
				// Preparando os campos
				$campos = $this->preparaCampos($REQUEST, 1);
				// Faz update no banco
				$this->DB->updateTab($d['tabela'], $campos, "id = '$id'");
				if($this->DB->resultado){
					
					if($tipo == 7) $REQUEST['nome'] = $REQUEST['id'];
					
					// Se cadastrou registra no LOG a acao
					$this->LOG->insertLog($this->DB,'O usuário "'.$this->login.'" '. $d['texto'] .' [ '.$REQUEST['nome'].' ].');
					$res = true;
				}else $res = false;			
			break;
		}
		
		return ($res === true)?"editado":"erro";
	}	
	
	/**
	 * Metodo que ADICIONA modulos no estoque do instalador
	 * @param $instalador - ID do instalador
	 * @param $modulos - STRING contendo os IDs dos modulos que serao adicionados (ex: 1,34,67)
	 * @return TRUE - sucesso, FALSE - erro
	 */	
	function adicionarModulosInstalador($instalador,$modulos) {
		// Coletando dados do instalador
		$inst = $this->coletaDados($instalador, 3);
		// Definir Status dos modulos para 4 (Em transito - Instalador) na tabela cad_equipamentos
		$this->DB->updateTab("cad_equipamentos", "status = 2", "id IN ($modulos)");
		// Separando os IDs dos modulos
		$ids = explode(',',$modulos);
		$numIds = sizeof($ids);
		for ($i = 0; $i < $numIds; $i++) {
			// Inserindo historico de cada modulo
			$this->DB->insertTab("cad_equipamentos_historico (id_equipamento,ocorrencia,status)", "'$ids[$i]','No Estoque de ".$inst['nome']."','4'");
			// Inserindo modulo no 'Estoque' do instalador
			$this->DB->insertTab("cad_equipamentos_instalador (id_equipamento,id_instalador,status)", "'$ids[$i]','$instalador','1'");						
		}
		return true;
	}	

	/**
	 * Metodo que REMOVE modulos no estoque do instalador
	 * @param $instalador - ID do instalador
	 * @param $modulos - STRING contendo os IDs dos modulos que serao removidos (ex: 1,34,67)
	 * @return TRUE - sucesso, FALSE - erro
	 */
	function removerModulosInstalador($instalador,$modulos) {
		// Definir Status dos modulos para 0 (Devolvido) na tabela cad_equipamentos_instalador
		$this->DB->updateTab("cad_equipamentos_instalador", 
							 "status = 0, data_devolucao_instalacao = now()", 
							 "id_instalador = $instalador AND status = 1 AND id_equipamento IN ($modulos)");
		// Definir Status dos modulos para 2 (Em teste) na tabela cad_equipamentos
		$this->DB->updateTab("cad_equipamentos", "status = 1", "id IN ($modulos)");
		// Separando os IDs dos modulos
		$ids = explode(',',$modulos);
		$numIds = sizeof($ids);
		for ($i = 0; $i < $numIds; $i++) {
			// Inserindo historico de cada modulo
			$this->DB->insertTab("cad_equipamentos_historico (id_equipamento,ocorrencia,status)", "'$ids[$i]','Devolvido ao escritorio','2'");
		}
		return true;
	}	

	/**
	 * Metodo que coleta os CHIPS HABILITADOS
	 * @return Retorna o resultado da consulta
	 */
	function coletaChipsHabilitados() {
		$this->DB->selectTab("cad_chips", "id, ccid", "WHERE status = 1 ORDER BY id");
		// Criando ARRAY com os resultados
		while($r = $this->DB -> fetchArray($this->DB -> resultado)){
			$res[$r['id']] = $r['ccid'];
		}
		// Retornando ARRAY resultante
		return $res;
	}
	
	/**
	 * Coleta os Modulos que estao em POSSE DO INSTALADOR
	 * @param $id_instalador - ID do instalador
	 * @return Retorna resultado da consulta em uma ARRAY
	 */
	function coletaModulosEstoqueInstalador($id_instalador) {
		$this->DB->selectTab("cad_equipamentos_instalador ei, cad_equipamentos e", 
							 "ei.id_equipamento AS id, e.serial AS modulo", 
							 "WHERE ei.id_equipamento = e.id
								    AND ei.status = 1
								    AND ei.id_instalador = $id_instalador
							  ORDER BY modulo");
		// Criando ARRAY com os resultados
		while($r = $this->DB -> fetchArray($this->DB -> resultado)){
			$res[$r['id']] = $r['modulo'];
		}
		// Retornando ARRAY resultante
		return $res;
	}

	/**
	 * Coleta os Modulos que estao DISPONIVEIS para instalacao
	 * @return Retorna resultado da consulta em uma ARRAY
	 */	
	function coletaModulosDisponiveis() {
		$this->DB->selectTab("cad_equipamentos", "id, serial",
							 "WHERE status = 2 ORDER BY serial");
		// Criando ARRAY com os resultados
		while($r = $this->DB -> fetchArray($this->DB -> resultado)){
			$res[$r['id']] = $r['serial'];
		}
		// Retornando ARRAY resultante
		return $res;
	}	
	
	/**
	 * Metodo auxiliar para definir a tabela e o texto do Log
	 * @param $tipo - Inteiro que define o tipo (1=Cliente, 2=Vendedor, 3=Instalador, 4=Fornecedor, 5=Equipamento, 6=Chip, 7=Contrato)
	 * @param $txt  - Texto da Acao
	 * @return Retorna ARRAY contendo a tabela e o texto para o log ("texto" => $txt, "tabela" => $tabela)
	 */
	function defineTabelaETexto($tipo,$txt) {
		switch ($tipo) {
			case 1: $txt = "$txt o(a) cliente "; 		$tabela = "cad_clientes";		break;
			case 2: $txt = "$txt o(a) vendedor(a) ";	$tabela = "cad_vendedores"; 	break;
			case 3: $txt = "$txt o(a) instalador(a) ";	$tabela = "cad_instaladores"; 	break;
			case 4: $txt = "$txt o(a) fornecedor(a) ";	$tabela = "cad_fornecedores"; 	break;
			case 5: $txt = "$txt o equipamento ";		$tabela = "cad_equipamentos"; 	break;
			case 6: $txt = "$txt o chip ";				$tabela = "cad_chips"; 			break;
			case 7: $txt = "$txt o contrato ";			$tabela = "contratos"; 			break;
		}
		return array("texto" => $txt, "tabela" => $tabela);
	}
	
	/**
	 * Metodo que BLOQUEIA/DESBLOQUEIA uma entidade de cadastro 
	 * (Cliente,Vendedor,Instalador,Fornecedor,Equipamento,Chip)
	 * @param $id - ID do usuario a ser bloqueado/desbloaqueado
	 * @param $tipo - Inteiro que define o tipo (1=Cliente, 2=Vendedor, 3=Instalador, 4=Fornecedor, 5=Equipamento, 6=Chip, 7=Contrato)
	 * @param $nome - Nome do bloqueado/desbloqueado
	 * @param $acao - 0 = BLOQUEAR, 1 = DESBLOQUEAR
	 * @return Retorna TRUE (sucesso) ou FALSE (erro)
	 */
	function alteraStatus($id,$tipo,$nome,$acao = 0) {
		
		// Definindo a tabela e o texto para o LOG
		$d = $this->defineTabelaETexto($tipo, ($acao == 0)?"BLOQUEOU":"DESBLOQUEOU");
		
		//Faz o UPDATE
		$this->DB->updateTab($d['tabela'], "status = $acao", "id = '$id'");
		if($this->DB->resultado){
			// Registra a ACAO no LOG
			$this->LOG->insertLog($this->DB,'O usuário "'.$this->login.'" '. $d['texto'] .'[ '. $nome .' ].');
			$res = true;
		} else $res = false;
		
		return ($res === true)?"ok":"";
	}	
	
	/**
	 * Metodo que prepara os campos para um INSERT ou UPDATE no Banco de Dados
	 * @param $REQUEST 	- Array com os dados
	 * @param $tipo		- Inteiro que define o tipo (1=UPDATE,2=INSERT) 
	 * @return Uma STRING para o UPDATE, ou uma ARRAY ("campos"=>$campos,"valores"=>$valores) para o INSERT
	 */
	public static function preparaCampos($REQUEST,$tipo) {
	
		include ("Util.class.php");
				
		// Campos para pular
		$keysSkip = array("PHPSESSID","id","acao","tipo","oper");
		
		// Campos de DATA
		$keysData = array("data_nascimento", "primeira_mensalidade", "data_inicial", "data_final", "data_solicitacao");	
		
		// Campos de MOEDA
		$keysMoeda = array( 
							"salario", "comissao_veiculo", "valor_veiculo", 
							"custo_mes", "valor", "valor_mensal", "valor_prestacao",
							"valor_instalacao"
						  );
		
		// Preparando os campos de acordo com o tipo de atualizacao
		switch ($tipo) {
	
			// Preparando os campos para o UPDATE
			case "1":
	
				$campos = "";
				$total = count($REQUEST);
	
				foreach($REQUEST as $key=>$value){

					if (!in_array($key, $keysSkip)){
						// Corrigindo os campos de DATA
						if (in_array($key, $keysData) && $value != "") {
							list($dia,$mes,$ano) = explode("/",$value);
							$value = "$ano-$mes-$dia";
						}
						
						// Corrigindo os campos de MOEDA
						if (in_array($key, $keysMoeda) && $value != "") {
							$value = str_replace(".", "", $value); 	// retira os pontos
							$value = str_replace(",", ".", $value); // substitui as virgulas por pontos
						}						
						
						$campos .= $key.'="'.Util::caxiaAlta(utf8_decode($value)).'",' ;
					}
				}
				// Retirando a ultima virgula
				$resultado = substr($campos,0,-1);
				break;
	
			// Preparando os campos para o INSERT
			case "2":
				$i = 0;
				foreach($REQUEST as $key => $value){
					
					if (!in_array($key, $keysSkip)){
						// Corrigindo os campos de DATA
						if (in_array($key, $keysData) && $value != "") {
							list($dia,$mes,$ano) = explode("/",$value);
							$value = "$ano-$mes-$dia";
						}
						
						// Corrigindo os campos de MOEDA
						if (in_array($key, $keysMoeda) && $value != "") {
							$value = str_replace(".", "", $value); 	// retira os pontos
							$value = str_replace(",", ".", $value); // substitui as virgulas por pontos
						}
								
						if ($i == 0) {
							$campos  = "$key" ;
							$valores = "'".Util::caxiaAlta(utf8_decode($value))."'";
						} else {
							$campos  .= ",$key" ;
							$valores .= ",'".Util::caxiaAlta(utf8_decode($value))."'";
						}
						$i++;
					}
					
				}
				$resultado = array("campos"=>$campos,"valores"=>$valores);
				break;
	
		}
		return $resultado;
	
	}

	/**
	 * Metodo que coleta os Historico das entidades de cadastro (chip,equipamento)
	 * @param $id - ID da entidade para consulta
	 * @param $tipo - Inteiro que define o tipo (5=Equipamento, 6=Chip, 7=Contrato)
	 * @return ARRAY com o resultado da consulta
	 */
	function coletaHistorico($id,$tipo) {
		// Definindo a tabela 
		switch ($tipo) {
			case 5:
				$tab = "cad_equipamentos_historico";
				$where = "id_equipamento";
			break;
			
			case 6:
				$tab = "cad_chips_historico";
				$where = "id_chip";
			break;
			
			case 7:
				$tab = "contratos_historico";
				$where = "id_contrato";
				break;			
		}
		// Realizando a consulta
		$this->DB->selectTab($tab, "date_format(data, '%d/%m/%Y  %H:%ih') AS data, ocorrencia", "WHERE $where = '$id' ORDER BY id DESC");
		// Criando ARRAY com os resultados
		while($r = $this->DB -> fetchArray($this->DB -> resultado)){
			$res[] = array(
							"data"  	 => $r['data'],
							"ocorrencia" => $r['ocorrencia']
						  );
		}
		// Retornando ARRAY resultante
		return $res;	
	}
	
	
	
}

?>