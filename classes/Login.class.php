<?php
// Classe para o log de acesso
require_once("Log.class.php");

class Login {
	
	public $usuario_id;
	public $usuario_login;
	public $usuario_nome;
	public $usuario_perfil;
        public $codvendedor;


        // Metodo construtor
	function __construct(){	}
	
	// Funcao que verifica os dados do usuario
	function checa($db,$login,$senha) {

		// Coleta os dados do usuario
		$sql = "WHERE login = '".$_POST['login']."' AND senha = MD5('". $_POST['senha'] ."') AND status = 1";
		$db->selectTab('usuario','*',$sql);		
		
		// Caso os dados estejam corretos...
		if ($db->numRows($db->resultado) > 0) {
		
			$dados = $db->fetchArray($db->resultado);
			
			// Abrindo a sessao e definindo suas variaveis
			//session_start();
			$_SESSION["l0g4d0"] 		= "ok";
			$_SESSION["usuario_id"] 	= $this->usuario_id 	= $dados['id'];
			$_SESSION["usuario_login"] 	= $this->usuario_login 	= $dados['login'];
			$_SESSION["usuario_nome"] 	= $this->usuario_nome 	= $dados['nome'];
			$_SESSION["usuario_perfil"]     = $this->usuario_perfil = $dados['perfil'];
                        $_SESSION["codvendedor"]        = $this->usuario_codvendedor = $dados['codvendedor'];
			
			// Criando log de acesso
			$LOG = new Log();
			$LOG->insertLog($db, 'O usuário "'.$_SESSION["usuario_login"].'" entrou no sistema.');
			
			return true;
		}
		else return false;
	}
	
	// Funcao que verifica a permissao do usuario
	function permissoes($db,$perfil) {

		// Coleta as PERMISSOES de acordo com o PERFIL do usuario
		$sql = "WHERE id = '$perfil'";
		$db->selectTab('usuario','*',$sql);
		
		// Separando os dados
		$dados = mysql_fetch_array($db->resultado);
		return $dados;			
	}		
}
?>