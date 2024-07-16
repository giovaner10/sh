<?php

/**
 * Classe responsavel pela coleta dos dados para a geracoes
 * dos diversos relatorios deste Sistema
 * 
 * @author ATM (atm@clicsolucoes.com.br)
 * 		   03.04.2012
 *
 */

class Relatorio {
	/**
	 * Metodo construtor
	 * @param $DB - Objeto com a conexao ao Banco de Dados
	 */
	function __construct($DB) {
		$this->DB = $DB;
	}
	
	/**
	 * Metodo que coleta os usuarios cadastrados no Banco de Dados
	 * e retorna uma ARRAY com estes dados
	 * @return Ex.: Login do 1o. registro - $array[0]['login']
	 */
	function coletaUsuarios() {
		$this->DB -> selectTab(	
								"usuario u, usuario_perfil p",
								"u.login,u.nome,p.nome perfil, CASE u.status WHEN 0 THEN 'Bloqueado' WHEN 1 THEN 'Ativo' END AS status",
								"WHERE u.perfil = p.id ORDER BY perfil,login"
							  );
		while($r = $this->DB -> fetchArray($this->DB -> resultado)){
			$res[] = array(
							"login"  => $r['login'],
							"nome"   => $r['nome'],
							"perfil" => $r['perfil'],
							"status" => $r['status']
						  );
		}
		return $res;
	}
	
	
}

?>