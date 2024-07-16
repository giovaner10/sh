<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	include("../classes/Util.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Arquivo
		extract($CAD -> coletaDados($id,9));
		$titulo = " - DOWNLOAD";
		$oper = 1;
	} else {
		//$arquivo = $descricao = $pasta = "";
		$titulo = " - UPLOAD";
		$oper = 2;
	}
        
	
	// Template ARQUIVO
	$T = new Template(substr($template_dir,3)."/cadastros_documentos.html");
	$T -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$T -> Set("oper",			$oper);
        if($id_os!=''){
            	$T -> Set("id",				$id_os);
                $pasta = 'OS';
        }else{
            	$T -> Set("id",				$id);
        }


	$T -> Set("arquivo",			utf8_encode($arquivo));
	$T -> Set("descricao",			utf8_encode($descricao));
	$T -> Set("pasta",			$pasta);
        $T -> Set("ida",			$pasta);
	
	// Colocando o titulo na pagina
	$T -> Set("Documento_Titulo","Transferir Arquivo". $titulo);
	$T -> Show("Documento_Titulo");
	
	if ($id != ""){
		// Colocando o sub-titulo na pagina
		$T -> Set("Documento_SubTitulo_Id",  $id);
		$T -> Set("Documento_SubTitulo_Nome", $arquivo);
		$T -> Show("Documento_SubTitulo");
	}
        else
        {
         // Colocando o sub-titulo na pagina
		$T -> Set("Documento_SubTitulo_Id",  "Pasta:");
                $T -> Set("Documento_SubTitulo_Nome", $pasta);
		$T -> Show("Documento_SubTitulo");
   
        }
				
	// Apresentando o Formulario]
	$T -> Show("Documento_Formulario_Cadastro_INICIO");
	
	
        
	// Finalizando o Formulario
	$T -> Show("Documento_Formulario_Cadastro_FIM");
	
	
?>