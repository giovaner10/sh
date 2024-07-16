<?php 

include_once 'Template.class.php';

class Html {

	function __construct($tpldir = "") {
		
		if ($tpldir == "") { 
			global $template_dir;
			$tpldir = $template_dir;
		}
		
		$this->template_dir = $tpldir;

		//Define o template a ser usado
		$this->t = new Template($tpldir."/geral.html");
	}
	
/**
 * M�todo que desenha o cabecalho da tabela
 * @param $arrayTitulos Um vetor com os t�tulos da tabela
 * @return void
 */
	function tabelaCabecalho($arrayTitulos) {
		
		//Definindo a largura da tabela
		$this->t->Set("TabelaCabecalho_largura", "90%");
		
		//Mostra o inicio do cabecalho
		$this->t->Show("TabelaCabecalho");
		
		//Determina a quantidade de campos
		$tamArray = sizeof($arrayTitulos);
		
		//Define e apresenta as colunas
		for ($index = 0; $index < $tamArray; $index++) {
			$this->t->Set("TabelaCabecalho_campo", $arrayTitulos[$index]);
			$this->t->Show("TabelaCabecalhoCampos");
		}
		
		//Mostra a parte final do cabecalho
		$this->t->Show("TabelaCabecalhoFim");
	}

/**
 * M�todo que desenha uma linha da tabela
 * @param $arrayDados Um vetor com os dados da linha
 * @param $cor Define a cor da linha
 * @return unknown_type
 */	
	function tabelaLinha($arrayDados, $cor = "") {
		//Define a class da linha
		if ($cor == "") $this->t->Set("TabelaLinha_odd", "");
		else $this->t->Set("TabelaLinha_odd", "odd");
		
		//Define a primeira coluna
		$this->t->Set("TabelaLinha_campo1", $arrayDados[0]);
		
		//Imprime o inicio da linha
		$this->t->Show("TabelaLinha");
		
		//Determina a quantidade de campos
		$tamArray = sizeof($arrayDados);
		
		//Define e apresenta as colunas
		for ($index = 1; $index < $tamArray; $index++) {
			$this->t->Set("TabelaLinha_campo", $arrayDados[$index]);
			$this->t->Show("TabelaLinhaCampos");
		}
		
		//Mostra a parte final do cabecalho
		$this->t->Show("TabelaLinhaFim");
	}
	
/**
 * M�todo que desenha o fim da tabela
 * @return void
 */
	function tabelaRodape() {
		//Mostra a parte final da tabela
		$this->t->Show("TabelaFim");
	}

/**
 * M�todo que desenha o Inicio do Form
 * @param $action
 * @param $nome
 * @return void
 */	
	function formInicio($action, $nome = "") {
		//Define os par�metros do Form
		$this->t->Set("FormInicio_action",$action);
		$this->t->Set("FormInicio_nome",$nome);
		
		//Mostra o inicio do Form
		$this->t->Show("FormInicio");		
	}

/**
 * M�todo que desenha o Fim do Form
 * @return void
 */	
	function formFim() {
		//Mostra o fim do Form
		$this->t->Show("FormFim");		
	}	
	
/**
 * M�todo que carrega uma IMAGEM
 * @param  $src O path do arquivo da imagem
 * @param  $show Se show = 0 faz echo, se nao armazena o codigo HTML na var $this->echoSelect
 * @param  $width A largura da imagem
 * @param  $height A altura da imagem
 * @param  $onClick A funcao que sera chamada quando a imagem for clicada
 * @param  $border A expessura em pixels da borda
 * @return void
 */
	function imagem($src, $show = "", $width = "", $height = "", $onClick = "", $border = "") {
		//Define os par�metros da Imagem
		$this->t->Set("Imagem_src",$src);
		$this->t->Set("Imagem_width",$width);
		$this->t->Set("Imagem_height",$height);
		$this->t->Set("Imagem_onClick",$onClick);
		$this->t->Set("Imagem_border",$border);
		
		if ($onClick != "") $this->t->Set("Imagem_style","cursor:hand");
		else  $this->t->Set("Imagem_style","");
		
		//Mostra a Imagem
		$this->t->Show("Imagem",$show);		
		
		//Armazena o conte�do a ser impresso
		$this->echoImagem = $this->t->conteudo;
	}	
	
/**
 * M�todo que desenha um form select
 * @param String $nome O nome do campo
 * @param array $arrayDados 3 campos: valor, nome, selecionado = 1
 * @param onchange Funcao que sera executada no onchange do select
 * @param show Se show = 0 faz echo, se nao armazena o codigo HTML na var $this->echoSelect
 * @return void
 */	
	function select($nome, $arrayDados,$onchange = "", $show = "") {
		
		//Define o nome do select
		$this->t->Set("SelectInicio_nome", $nome);
		
		//Define o onchange do select
		$this->t->Set("SelectInicio_onChange", $onchange);
		
		//Mostra o inicio do select
		$this->t->Show("SelectInicio",$show);
		//Armazena o conte�do a ser impresso
		$echoSelect = $this->t->conteudo;

		//Define o tamanho do array
		$tamArray = sizeof($arrayDados);		

		for ($index = 0; $index < $tamArray; $index++) {
			//Define se o item esta pre-selecionado
			if ($arrayDados[$index][2] == 1) $this->t->Set("SelectItem_selected", " selected");
			else $this->t->Set("SelectItem_selected", "");
			
			//Define o nome e o valor do item
			$this->t->Set("SelectItem_valor", $arrayDados[$index][0]);
			$this->t->Set("SelectItem_nome", $arrayDados[$index][1]);
			
			//Mostra o item
			$this->t->Show("SelectItem",$show);
			//Armazena o conte�do a ser impresso
			$echoSelect .= $this->t->conteudo;
			
		}
		
		//Mostra o fim do select
		$this->t->Show("SelectFim",$show);
		//Armazena o conte�do a ser impresso
		$echoSelect .= $this->t->conteudo;
		//Armazena o conte�do a ser impresso
		$this->echoSelect = $echoSelect;
		
		
	}

/**
 * Metodo que desenha um INPUT do formulario
 * @param $tipo
 * @param $nome
 * @param $valor (nao eh obrigatorio)
 * @param $tamanho (nao eh obrigatorio)
 * @return void
 */	
	function input($tipo,$nome,$valor = "",$tamanho = "",$show = "") {
		//Define os par�metros do Input
		$this->t->Set("Input_tipo",$tipo);
		$this->t->Set("Input_nome",$nome);
		$this->t->Set("Input_valor",$valor);
		$this->t->Set("Input_tamanho",$tamanho);		
		
		//Mostra o Input
		$this->t->Show("Input", $show);		
		
		//Armazena o conte�do a ser impresso
		$this->echoInput = $this->t->conteudo;	
	}
	
/**
 * Metodo que desenha um TEXTAREA do formulario
 * @param String $nome
 * @param String $valor (nao eh obrigatorio)
 * @param Int $linhas (nao eh obrigatorio)
 * @param Int $colunas (nao eh obrigatorio)
 * @param String $show (nao eh obrigatorio)
 * return void
 */
	function textarea($nome,$valor = "",$linhas = "3",$colunas = "",$show = "") {
		//Define os parametros do Input
		$this->t->Set("TextArea_nome",$nome);
		$this->t->Set("TextArea_valor",$valor);
		$this->t->Set("TextArea_linhas",$linhas);
		$this->t->Set("TextArea_colunas",$colunas);		
		
		//Mostra o Input
		$this->t->Show("TextArea", $show);		
		
		//Armazena o conteudo a ser impresso
		$this->echoInput = $this->t->conteudo;	
	}	
	
/**
 * Metodo que desenha um CHECKBOX do formulario
 * @param $tipo 0 = checkbox 1 = radio
 * @param $nome O nome
 * @param $valor O valor
 * @param $marcado Se est� marcado (nao eh obrigatorio)
 * @return void
 */	
	function checkradio($tipo,$nome,$valor,$marcado = "",$show = "") {
		//Define os par�metros do Input
		if ($tipo === 0) {
			$this->t->Set("Checkradio_tipo","checkbox");
			$marc = "CHECKED";
		}
		else {
			$this->t->Set("Checkradio_tipo","radio");
			$marc = "SELECTED";
		}
		
		$this->t->Set("Checkradio_nome",$nome);
		$this->t->Set("Checkradio_valor",$valor);
		
		if ($marcado == "") $marc = "";
		$this->t->Set("Checkradio_marcado",$marc);		
		
		//Mostra o Input
		$this->t->Show("Checkradio",$show);
		//Armazena o conte�do a ser impresso
		$this->echoCheckRadio = $this->t->conteudo;		
	}

/**
 * Metodo que insere os INCLUDES do calendario
 * @return void
 */
	function calendarioIncludes() {
		
		$local_tpldir = substr($this->template_dir,6);
		
		//Seta os valores
		$this->t->Set("local_tpldir",$local_tpldir);
		
		//Apresenta o calendario
		$this->t->Show("Calendario_Includes");
	}	
	
/**
 * Metodo que desenha o calendario
 * @param $titulo O titulo do calendario
 * @param $id O id do form
 * @param $valor O valor j� definido
 * @return void
 */
	function calendario($titulo,$id,$valor = "",$size = "") {
		
		$local_tpldir = substr($this->template_dir,6);
		
		//Seta os valores
		$this->t->Set("local_tpldir",$local_tpldir);
		$this->t->Set("Calendario_Titulo",$titulo);
		$this->t->Set("Calendario_Id",$id);
		$this->t->Set("Calendario_Valor",$valor);
		$this->t->Set("Calendario_Size",$size);
		
		//Apresenta o calendario
		$this->t->Show("Calendario");
	}		
	
	
/**
 * Metodo que desenha o grid
 * @param $colTabela - indica qual a coluna da tabela ira utilizar
 * @param $arrParametros - Array com os par�metros
 * 					   	   [ Grid_Id, Grid_Titulo, Grid_Tabela, Grid_Campos, Grid_Condicao, Grid_SortName, Grid_SortOrder ]
 * @param $arrColunas - Array com os par�metros [ array ( 'titulo do campo', 'nome do campo' ) ]
 * @param $arrBusca - Array com os par�metros [ array ( 'titulo do campo', 'nome do campo', 'isDefault' ) ]
 * @param $verBotoes - Boolean com os par�metros [TRUE ou FALSE] para habilitar botoes
 * @return void
 */
	function grid($arrBotoes,$arrParametros,$arrColunas,$arrBusca,$verBotoes=FALSE) {
		
		$local_tpldir = substr($this->template_dir,6);
		
		//Seta os valores
		$this->t->Set("local_tpldir",$local_tpldir);
		$this->t->Set("Grid_Id",$arrParametros['Grid_Id']);
		
		//Apresenta o inicio do Grid
		$this->t->Show("Grid");		
		
		// Apresentando as COLUNAS
		$numColunas = sizeof($arrColunas);
		for ($i = 0; $i < $numColunas; $i++) {
			$this->t->Set("Grid_ColModel_Titulo",$arrColunas[$i][0]);
			$this->t->Set("Grid_ColModel_Campo",$arrColunas[$i][1]);
			$this->t->Set("Grid_ColModel_Width",$arrColunas[$i][2]);
			$this->t->Set("Grid_ColModel_Align",$arrColunas[$i][3]);
			$this->t->Set("Grid_ColModel_Virgula",($i == $numColunas - 1)?"":",");
			$this->t->Show("Grid_ColModel");			
		}
		
		// Continuacao...
		$this->t->Show("Grid_Continuacao");
		
		// Mostre os botoes se estiver habilitado
		if($verBotoes){
			// Apresentando os Botoes
			if ($arrBotoes != ""){
				$numBotoes = sizeof($arrBotoes);
				for ($i = 0; $i < $numBotoes; $i++) {			
					$this->t->Set("Grid_Buttons_Titulo",$arrBotoes[$i]);
					$this->t->Set("Grid_Buttons_Virgula",($i == $numBotoes - 1)?"":",");
					$this->t->Show("Grid_Buttons");			
				}
				
			}
		}
		
		// Continuacao 2...
		$this->t->Show("Grid_Continuacao2");
				
		// Apresentando as BUSCAS
		$numBusca = sizeof($arrBusca);
		for ($i = 0; $i < $numBusca; $i++) {
			$this->t->Set("Grid_SearchItems_Titulo",$arrBusca[$i][0]);
			$this->t->Set("Grid_SearchItems_Campo",$arrBusca[$i][1]);
			$this->t->Set("Grid_SearchItems_IsDefault",$arrBusca[$i][2]);
			$this->t->Set("Grid_SearchItems_Virgula",($i == $numBusca - 1)?"":",");
			$this->t->Show("Grid_SearchItems");			
		}		
		
		// Apresentando o Final do Grid
		$this->t->Set("Grid_Titulo",$arrParametros['Grid_Titulo']);
		$this->t->Set("Grid_Tabela",$arrParametros['Grid_Tabela']);
		$this->t->Set("Grid_Campos",$arrParametros['Grid_Campos']);
		$this->t->Set("Grid_Condicao",$arrParametros['Grid_Condicao']);
		$this->t->Set("Grid_SortName",$arrParametros['Grid_SortName']);
		$this->t->Set("Grid_SortOrder",$arrParametros['Grid_SortOrder']);
		$this->t->Set("Grid_Linhas",$arrParametros['Grid_Linhas']);
		$this->t->Set("Grid_Width",$arrParametros['Grid_Width']);
		$this->t->Set("Grid_Height",$arrParametros['Grid_Height']);
		$this->t->Show("Grid_Final");		

	}

/**
 * Metodo que inicializa as fun��es do jQuery
 * @param $tipo O tipo que ser� inicializado
 * @return void
 */
	function inicializa($tipo) {
		$local_dir = substr($this->template_dir,3);
		if($local_dir == "../templates/default")
			$local_dir = substr($local_dir,3);
		$this->t->Set("local_tpldir",$local_dir);
		$this->t->Show($tipo);
	}
	
}

?>