<?php
class Util {

	/**
	 * Metodo que coloca todas as letras em MAIUSCULA
	 * @param $texto - Texto que sofrera alteracoes
	 * @return Retona a palavra em MAIUSCULA
	 */
	public static function caxiaAlta($texto) {
		$array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", 
						"í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö",
						"ú", "ù", "û", "ü", "ç",
						"a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
						"k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
						"u", "v", "w", "x", "y", "z" );
		$array2 = array("Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", 
						"Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö",
						"Ú", "Ù", "Û", "Ü", "Ç",
						"A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
						"K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
						"U", "V", "W", "X", "Y", "Z" );
		return str_replace( $array1, $array2, $texto );
	}
	
	/**
	 * Metodo que retorna a LISTA DE ESTADO em uma ARRAY 
	 * @return multitype:string
	 */
	public static function estados() {
		$estados =  array(
						"AC"=>"ACRE", "AL"=>"ALAGOAS", "AP"=>"AMAPÁ", "AM"=>"AMAZONAS",
						"BA"=>"BAHIA", "CE"=>"CEARÁ", "DF"=>"DISTRITO FEDERAL",
						"ES"=>"ESPÍRITO SANTO", "GO"=>"GOIÁS", "MA"=>"MARANHÃO",
						"MT"=>"MATO GROSSO", "MS"=>"MATO GROSSO DO SUL",
						"MG"=>"MINAS GERAIS", "PA"=>"PARÁ", "PB"=>"PARAÍBA",
						"PR"=>"PARANÁ", "PE"=>"PERNAMBUCO", "PI"=>"PIAUÍ",
						"RJ"=>"RIO DE JANEIRO", "RN"=>"RIO GRANDE DO NORTE",
						"RS"=>"RIO GRANDE DO SUL", "RO"=>"RONDÔNIA", "RR"=>"RORAIMA",
						"SC"=>"SANTA CATARINA", "SP"=>"SÃO PAULO", "SE"=>"SERGIPE",
						"TO"=>"TOCANTINS"
					);
		return $estados;
	}
	

	/**
	 * Este método recebe um valor numérico e retorna uma
	 * string contendo o valor de entrada por extenso
	 * @author Luciano Azevedo - 16/02/2000
	 * @param $valor = tipo float
	 * @return Retorna o valor por extenso
	 */
	public static function valorPorExtenso($valor=0) {
	
		$singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
		$plural = array("", "", "mil", "milhões", "bilhões", "trilhões",
		"quatrilhões");

		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
		"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
		"sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
		"dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
		"sete", "oito", "nove");

		$z=0;
		$rt='';

		$valor = number_format($valor, 2, ".", ".");
		$inteiro = explode(".", $valor);
		for($i=0;$i<count($inteiro);$i++)
			for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
				$inteiro[$i] = "0".$inteiro[$i];

		// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
		$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
		for ($i=0;$i<count($inteiro);$i++) {
				$valor = $inteiro[$i];
				$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
				$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
				$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

				$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
						$ru) ? " e " : "").$ru;
						$t = count($inteiro)-1-$i;
						$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
								if ($valor == "000")$z++; elseif ($z > 0) $z--;
								if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
								if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
									($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
		}
		
		return($rt ? $rt : "zero");
	}	
	
	/**
	 * Metodo que recebe uma STRING de data e retorna
	 * outra STRING contendo esta data por extenso
	 * @param $data -String contendo a data (ex.: 2012-01-01)
	 * @return Retorna a data por extenso
	 */
	public static function dataPorExtenso($data){
		setlocale(LC_ALL, "pt_BR", "ptb");
		//strftime("%A, %d de %B de %Y", strtotime("1992-06-01"));
		return strftime("%d de %B de %Y", strtotime($data));
	}
	
	/**
	 * Metodo que formata a data recebida para (DD/MM/AAAA)
	 * @param $data -String contendo a data (ex.: 2012-01-01)
	 * @return Retorna a data por extenso
	 */
	public static function formataData($data){
		setlocale(LC_ALL, "pt_BR", "ptb");
		//strftime("%A, %d de %B de %Y", strtotime("1992-06-01"));
		return strftime("%d/%m/%Y", strtotime($data));
	}
	
	
	/**
	 * Metodo que corrige a localização do simbolo do milhar ('.')
	 * e o simbolo dos centavos (',') se um determinado valor
	 * @param $valor - FLOAT/DOUBLE/INT que sofrera alteracao
	 * @return Retorna o numero corrigido
	 */
	public static function formataValor($valor){
		return number_format($valor,2,",",".");
	}
	
	/**
	 * Metodo que monta o ARRAY para ser utilizada pela
	 * classe HTML na montagem do SELECT de Clientes
	 * @param $DB - Objeto com a conexao ao Banco de Dados
	 * @param $id - ID do cliente, caso exista, para deixa-lo selecionado no SELECT
	 * @return ARRAY resultante
	 */
	public static function arraySelectClientes($DB,$id = ""){
		
		$DB->selectTab("cad_clientes", "id, nome", "WHERE status = 1 ORDER BY nome ASC");
		$num = $DB->numRows($DB->resultado);
		
		while ($d = $DB->fetchArray($DB->resultado)) {
			$selected = ($d['id'] == $id)?1:0;
			$arrSel[] = array($d['id'],utf8_encode($d['nome']),$selected);
		}
		
		return $arrSel;
	}
	
	/**
	 * Metodo que monta o ARRAY para ser utilizada pela
	 * classe HTML na montagem do SELECT de Vendedores
	 * @param $DB - Objeto com a conexao ao Banco de Dados
	 * @param $id - ID do vendedor, caso exista, para deixa-lo selecionado no SELECT
	 * @return ARRAY resultante
	 */
	public static function arraySelectVendedores($DB,$id = ""){
	
		$DB->selectTab("cad_vendedores", "id, nome", "WHERE status = 1 ORDER BY nome ASC");
		$num = $DB->numRows($DB->resultado);
	
		while ($d = $DB->fetchArray($DB->resultado)) {
			$selected = ($d['id'] == $id)?1:0;
			$arrSel[] = array($d['id'],utf8_encode($d['nome']),$selected);
		}
	
		return $arrSel;
	}	
	
	/**
	 * Metodo que monta o ARRAY para ser utilizada pela
	 * classe HTML na montagem do SELECT de Instaladores
	 * @param $DB - Objeto com a conexao ao Banco de Dados
	 * @param $id - ID do instalador, caso exista, para deixa-lo selecionado no SELECT
	 * @return ARRAY resultante
	 */
	public static function arraySelectInstaladores($DB,$id = ""){
	
		$DB->selectTab("cad_instaladores", "id, nome", "WHERE status = 1 ORDER BY nome ASC");
		$num = $DB->numRows($DB->resultado);
	
		while ($d = $DB->fetchArray($DB->resultado)) {
			$selected = ($d['id'] == $id)?1:0;
			$arrSel[] = array($d['id'],utf8_encode($d['nome']),$selected);
		}
	
		return $arrSel;
	}	
	
	
	/**
	 * Metodo que monta o ARRAY para ser utilizada pela
	 * classe HTML na montagem do SELECT de Equipamentos (Modulos)
	 * @param $DB - Objeto com a conexao ao Banco de Dados
	 * @param $id - ID do equipamento, caso exista, para deixa-lo selecionado no SELECT
	 * @return ARRAY resultante
	 */
	public static function arraySelectEquipamentos($DB,$id = ""){
	
		$DB->selectTab( "cad_equipamentos", "id, serial AS modulo",
						"WHERE status = 2 ORDER BY modulo");
		$num = $DB->numRows($DB->resultado);
	
		while ($d = $DB->fetchArray($DB->resultado)) {
			$selected = ($d['id'] == $id)?1:0;
			$arrSel[] = array($d['id'],utf8_encode($d['modulo']),$selected);
		}
	
		return $arrSel;
	}	
	
        ///Ponhe zeros
        //str_pad($valor, 10, "0", STR_PAD_LEFT)
	public static function zeros($qtd,$id = ""){
            $ret = str_pad($id, $qtd, "0", STR_PAD_LEFT);
            return $ret;
	}	
	
}
?>