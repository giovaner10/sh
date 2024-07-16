
<?php
	// Classe necessaria
	include("../classes/Util.class.php");
	include("../classes/Cadastro.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Usuario
		extract($CAD -> coletaDados($id,77));
		$titulo = " - EDITAR";
		$oper = 1;
		
		// corrigindo pontuacao dos valores
		$valor_mensal 		= Util::formataValor($valor_mensal);
		$valor_instalacao 	= Util::formataValor($valor_instalacao);
		$valor_prestacao 	= Util::formataValor($valor_prestacao);
                $valor_entrada   	= Util::formataValor($valor_entrada);
		
		// Corrigindo Data
		$primeira_mensalidade = Util::formataData($primeira_mensalidade);
                $data_prestacao = Util::formataData($data_prestacao);
                $data_contrato        = Util::formataData($data_contrato);
                $vencimento_proporsta      = Util::formataData($vencimento_proposta);
                
                $_SESSION['cliente'] = $id_cliente;
                $_SESSION['proposta'] = $id;
                
                
		
	} else {
		$valor_mensal = $valor_instalacao = $valor_prestacao = 
		$id_cliente = $id_vendedor = $quantidade_veiculos = $vencimento_proposta =
		$meses = $prestacoes = $vencimento = $primeira_mensalidade = $data_prestacao = "";
		$titulo = " - CADASTRAR";
		$oper = 2;
              
	}
	
	// Template VENDEDORES
	$TCont = new Template(substr($template_dir,3)."/propostas.html");
	$TCont -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
        $TCont -> Set("id_cliente",	$_SESSION['idcliente']);
	$TCont -> Set("idP",		$id);
	$TCont -> Set("oper",		$oper);
// 	$TCont -> Set("id_cliente",	$id_cliente);
 	$TCont -> Set("id_vendedor",    $id_vendedor);
	$TCont -> Set("quantidade_veiculos", $quantidade_veiculos);
	$TCont -> Set("meses",		$meses);
	$TCont -> Set("prestacoes",	$prestacoes);
        $TCont -> Set("valor_entrada",	$valor_entrada);
	$TCont -> Set("vencimento",	$vencimento);
	$TCont -> Set("primeira_mensalidade",	$primeira_mensalidade);
        $TCont -> Set("data_contrato",	$data_contrato);
        $TCont -> Set("vencimento_proposta",	$vencimento_proposta);
	$TCont -> Set("valor_prestacao",	$valor_prestacao);
        $TCont -> Set("data_prestacao",         $data_prestacao);
	$TCont -> Set("valor_instalacao",	$valor_instalacao);
	$TCont -> Set("valor_mensal",		$valor_mensal);
        $TCont -> Set("multa_valor",	$multa_valor);
        

        if ($multa=="1"){
            $TCont -> Set("check1",	"checked");
        }
        if ($multa=="2"){
            $TCont -> Set("check2",	"checked");
        }
        if ($multa=="3"){
            $TCont -> Set("check3",	"checked");
        }

                
	// Apresentando o Formulario
	$TCont -> Show("Contratos_Formulario");
/*	
        if ($oper != 1) {
	// Coletando lista de Clientes
	$clientes = Util::arraySelectClientes($DB,$id_cliente);
	// Apresentando o SELECT de Clientes
	$HTML -> select("id_cliente",$clientes);
        }
        else
        {
            echo $_SESSION['nomcli'];
        }
*/
        // Array com os Tipo Prposta
	$tipo_ = Util::tipo_proposta();
	
	// Construindo ARRAY do Select
	foreach ($tipo_ as $key => $value) {
		$selected = ($key == $tipo_proposta)?1:0;
		$arrTipo[] = array($key,$value,$selected);
	}
        
	// Apresentando o SELECT de TIPO PROPOSTA
	$HTML -> select("tipo_proposta",$arrTipo);
        
        // Continuando o Formulario
	$TCont -> Show("Contratos_Formulario_Continuacao_Cliente");
		
	// Coletando lista de Vendedores
	$vendedores = Util::arraySelectVendedores($DB,$id_vendedor);
	// Apresentando o SELECT de Vendedores
	$HTML -> select("id_vendedor",$vendedores);	

	// Continuando o Formulario
	$TCont -> Show("Contratos_Formulario_Continuacao_Vendedor");	
        

	// Array com os Status Contrato
	$status_ = Util::status_proposta();
	
	// Construindo ARRAY do Select
	foreach ($status_ as $key => $value) {
		$selected = ($key == $status)?1:0;
		$arrStatus[] = array($key,$value,$selected);
	}
	// Apresentando o SELECT de STATUS DO CONTRATO
	$HTML -> select("status",$arrStatus);
        
     	// Finalizando a apresentacao dos STATUS DO CONTRATO
	$TCont -> Show("Contrato_Formulario_Status");

        
       
        
?>