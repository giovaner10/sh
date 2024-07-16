<?php

class Usuario {
	
	public $id;
	public $login;
	public $nome;
	public $perfil;	
	
	/**
	 * Metodo construtor
	 */
	function __construct(){
		global $_SESSION;
		$this->id = $_SESSION["usuario_id"];
		$this->login = $_SESSION["usuario_login"];
		$this->nome = $_SESSION["usuario_nome"];
		$this->perfil = $_SESSION["usuario_perfil"];
	}
	
	/**
	 * Metodo para RETORNAR o perfil do usuario
	 * @param $db - banco a ser utilizado
	 */
	function perfil($db) {
		
		$sql = "WHERE id=$this->perfil";
		$db -> selectTab('usuario_perfil','nome',$sql);
		$result = $db -> resultado;
		$linha = $db -> fetchArray($result);
		
		return $linha['nome'];
	}
	
	/**
	 * Metodo para RETORNAR a permissao do usuario
	 * @param $db - banco a ser utilizado
	 * @param $col - nome da coluna da tabela usuario
	 */
	function permissao($db,$col) {
		
		$sql = "WHERE id=$this->perfil";
		$db -> selectTab('usuario_perfil',$col,$sql);
		$result = $db -> resultado;
		$linha = $db -> fetchArray($result);
		
		return $linha[$col];
	}
	
	/**
	 * Metodo que retorna o LINK (id) do Menu caso o usuario
	 * tenha permissao para ver este item do Menu
	 * @param $DB - Objeto com a cneao aom Banco de Dados
	 * @param $perm - String com o modulo a ser pesquisada a permissao
	 * @param $link - String com o LINK que sera criado
	 * @return Se o usuario tiver permissao, retorna o link construido (ex.: id='LINK') 
	 */
        function mostraLink($DB,$perm,$link) {
		
		$id = "";
                
                global $url;
                if(substr($link,0,10) == "Relatorios"){
			$pRel = $this->permissao($DB,"relatorios");
			$p = $this->permissao($DB,$perm);
			
			if($pRel > 0 || $p > 0) $id = "id='$link'";
		} else {
			$p = $this->permissao($DB,$perm);
			if($p > 0) $id = "id='$link'";
		}
		
		return $id;
	}
	
	
	/**
	 * Metodo para VERIFICAR se a senha existe
	 * @param $db - banco de dados a ser usado
	 * @param $antiga_senha - antiga senha para ser mudada
	 * @return int
	 */
	function verifica_senha($DB,$antiga_senha){
		$DB -> selectTab("usuario","COUNT(*) as total","WHERE senha = '".MD5($antiga_senha)."' and ".
						 "login = '".$this->login."'");
		$d = $DB -> resultado;
		$linha = $DB -> fetchArray($d);
		return $linha['total'];
	}

	/**
	 * Metodo para ALTERAR a senha
	 * @param $DB - banco de dados a ser usado
	 * @param $LOG - log a ser usado
	 * @param $nova_senha - nova senha que vai ser mudada
	 */
	function altera_senha($DB,$LOG,$nova_senha){	
		$DB -> updateTab("usuario","senha = '".MD5($nova_senha)."'",
						 "login ='".$this->login."'");
			
		// Se existir ele cria um LOG e altera a senha			
		$LOG -> insertLog($DB,'O usuário "'.$this->login.'" alterou sua SENHA.');
	}
	
	/**
	 * Metodo que coleta os dados de um usuario
	 */
	function dadosDoUsuario($DB,$id) {
		$DB -> selectTab("usuario","*","WHERE id = '$id'");
		return $DB -> fetchArray($DB -> resultado);
	}	
	
	/**
	 * Metodo que coleta os PERFIS de usuario cadastrados no banco de dados
	 */
	function coletaPerfis($DB) {
		$DB -> selectTab("usuario_perfil","id,nome","ORDER BY nome");
		while($p = $DB -> fetchArray($DB -> resultado)){
			$perfil[$p['id']] = $p['nome'];
		}
		return $perfil;
	}

	/**
	 * Metodo para ATUALIZAR os dados do usuario
	 * @param $DB - banco a ser utilizado
	 * @param $LOG - log para registrar evento
	 * @param $request - recebe as informacoes vindas dos parametros POST ou GET
	 */
	function editarUsuario($DB,$LOG,$request) {

		extract($request);
		
		if ($id == "") {
			//Insere os campos no banco
			$DB->insertTab("usuario (login,nome,senha,perfil,codvendedor)", "'$login','".utf8_encode($nome)."',MD5('$senha'),'$perfil','$codvendedor'");
			
			// Se existir ele cria um LOG e altera a senha			
			$LOG -> insertLog($DB,'O usuário "'.$this->login.'" CADASTROU um novo usuário [ '. $login.' ].');
			
			echo "cadastrado";
		}
		else {
			
			$sql  = "login = '$login', nome = '".utf8_encode($nome)."', perfil = '$perfil', codvendedor='$codvendedor'";
			if ($senha != "") $sql .= ", senha = MD5('$senha')";
			
			//Faz o UPDATE
			$DB->updateTab(	"usuario", $sql, "id = '$id'");
			
			// Se existir ele cria um LOG e altera a senha			
			$LOG -> insertLog($DB,'O usuário "'.$this->login.'" EDITOU o usuário [ '. $login.' ].');
			
			echo "alterado";
		}
		
	}	
	
	/**
	 * Metodo que BLOQUEIA/DESBLOQUEIA um usuario
	 * @param $DB - Objeto com a conexao ao Banco de Dados
	 * @param $LOG - Objeto responsavel pelos LOGs
	 * @param $id - ID do usuario a ser bloqueado
	 * @param $login - LOGIN do usuario a ser bloqueado
	 * @param $acao - 0 = BLOQUEAR, 1 = DESBLOQUEAR
	 */
	function bloquearUsuario($DB,$LOG,$id,$login,$acao = 0) {
		//Faz o UPDATE
		$DB->updateTab(	"usuario", "status = $acao", "id = '$id'");
		$res = $DB->resultado;
		
		$titAcao = ($acao == 0)?"BLOQUEOU":"DESBLOQUEOU";
		
		if ($res === TRUE)	// Registra a ACAO no LOG		
		$LOG -> insertLog($DB,'O usuário "'.$this->login.'" '. $titAcao .' o usuário [ '. $login.' ].');
		
		return $res;
	}
        
	
}
?>