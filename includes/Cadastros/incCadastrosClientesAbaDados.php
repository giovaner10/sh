<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	include("../classes/Util.class.php");
	// Criando objeto do tipo CADASTRO
	
        $CAD = new Cadastro();
	
	if($id != ""){
		// Coletando os dados do Usuario
		extract($CAD -> coletaDados($id,1));
		$titulo = " - EDITAR";
		$oper = 1;
                $_SESSION['idcliente']=$id;
                $_SESSION['nome_cliente']=$nome;
                $demail1 = 'Diretor';
                $demail2 = 'Financeiro';
                $demail3 = 'Suporte';
               
                
	} else {
		$nome = $endereco = $numero = $bairro = $cep =
		$cidade = $uf = $fone = $cel = $fax = $email =
		$informacoes = $cpf = $latitude = $longitude = 
		$cnpj = $complemento = $ponto_de_referencia = 
		$razao_social = $inscricao_estadual = $contato = "";
		$titulo = " - CADASTRAR";
		$oper = 2;
                $demail1 = 'Diretor';
                $demail2 = 'Financeiro';
                $demail3 = 'Suporte';
                $demail4 = '';
                $demail5 = '';
                $id_vendedor = $_SESSION['codvendedor'];
	}
               
                
	
	// Template VENDEDORES
	$TCli = new Template(substr($template_dir,3)."/cadastros_clientes.html");
	$TCli -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TCli -> Set("pj_checked",	($cnpj!=""||$cpf=="")?"checked":"");
	$TCli -> Set("pj_display",	($cnpj!=""||$cpf=="")?"":"none");
	$TCli -> Set("pf_checked",	($cpf!="")?"checked":"");
	$TCli -> Set("pf_display",	($cpf!="")?"":"none");
	$TCli -> Set("oper",		$oper);
	$TCli -> Set("id",			$id);
	$TCli -> Set("nome",		utf8_encode($nome));
	$TCli -> Set("endereco",	utf8_encode($endereco));
	$TCli -> Set("numero",		$numero);
	$TCli -> Set("complemento",	utf8_encode($complemento));
	$TCli -> Set("ponto_de_referencia",	utf8_encode($ponto_de_referencia));
	$TCli -> Set("bairro",		utf8_encode($bairro));
	$TCli -> Set("cidade",		utf8_encode($cidade));
	$TCli -> Set("fone",		$fone);
	$TCli -> Set("cel",			$cel);
	$TCli -> Set("fax",			$fax);
	$TCli -> Set("email",		$email);
        $TCli -> Set("email2",		$email2);
        $TCli -> Set("email3",		$email3);
        $TCli -> Set("email4",		$email4);
        $TCli -> Set("email5",		$email5);
        $TCli -> Set("demail1",		$demail1);
        $TCli -> Set("demail2",		$demail2);
        $TCli -> Set("demail3",		$demail3);
        $TCli -> Set("demail4",		$demail4);
        $TCli -> Set("demail5",		$demail5);
	$TCli -> Set("informacoes",	utf8_encode($informacoes));
	$TCli -> Set("cpf",			$cpf);
	$TCli -> Set("cnpj",		$cnpj);
        $TCli -> Set("cep",		$cep);
	$TCli -> Set("inscricao_estadual", $inscricao_estadual);
	$TCli -> Set("contato",		utf8_encode($contato));
	$TCli -> Set("razao_social", utf8_encode($razao_social));
	$TCli -> Set("latitude",	$latitude);
	$TCli -> Set("longitude",	$longitude);
      
        // Apresentando o Formulario
	$TCli -> Show("Clientes_Formulario_Cadastro_INICIO");

	// Array com os Status Cliente
	$status_ = Util::status_cliente();
	
	// Construindo ARRAY do Select
	foreach ($status_ as $key => $value) {
		$selected = ($key == $status)?1:0;
		$arrStatus[] = array($key,$value,$selected);
	}
	// Apresentando o SELECT de STATUS DO CLIENTE
	$HTML -> select("status",$arrStatus);
        
             	// Finalizando a apresentacao dos STATUS DO CONTRATO
	$TCli -> Show("Cliente_Formulario_Status");

	// Coletando lista de Vendedores
	$vendedores = Util::arraySelectVendedores($DB,$id_vendedor);
	// Apresentando o SELECT de Vendedores
	$HTML -> select("id_vendedor",$vendedores);	

	// Continuando o Formulario
	$TCli -> Show("Contratos_Formulario_Continuacao_Vendedor");	
        
        
        
	// Array com os Estados
	$estados = Util::estados();
	
	// Construindo ARRAY do Select
	foreach ($estados as $key => $value) {
		$selected = ($key == $uf)?1:0;
		$arrUf[] = array($key,$value,$selected);
	}
	// Apresentando o SELECT de ESTADOS
	$HTML -> select("uf",$arrUf);
	
	// Finalizando a apresentacao dos ESTADOS
	$TCli -> Show("Clientes_Formulario_Cadastro_UF_FIM");
	
	// Finalizando o Formulario
	$TCli -> Show("Clientes_Formulario_Cadastro_FIM");

?>