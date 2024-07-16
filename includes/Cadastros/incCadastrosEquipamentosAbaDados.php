<?php
	// Classe necessaria
	include("../classes/Cadastro.class.php");
	// Criando objeto do tipo CADASTRO
	$CAD = new Cadastro();
		
	if($id != ""){
		// Coletando os dados do Usuario
		extract($CAD -> coletaDados($id,5));
		$titulo = " - EDITAR";
		$oper = 1;
		
		// corrigindo moeda
		$valor = number_format($valor,2,",",".");

                } else {
		$serial = $marca = $modelo = $valor = $ccid = $id_chip = "";
                $status = '1';
		$titulo = " - CADASTRAR";
		$oper = 2;
	}
	
	// Template EQUIPAMENTOS
	$TEquip = new Template(substr($template_dir,3)."/cadastros_equipamentos.html");
	$TEquip -> Set("tpldir",substr($template_dir,6));
	
	// Definindo os campos
	$TEquip -> Set("oper",	$oper);
	$TEquip -> Set("id",	$id);
	$TEquip -> Set("serial",$serial);
	$TEquip -> Set("marca",	$marca);
	$TEquip -> Set("modelo",$modelo);
        $TEquip -> Set("placa",$placa);
	$TEquip -> Set("valor",	$valor);
        //$TEquip -> Set("status",	$status);
        //$TEquip -> Set("id_cliente",	$id_cliente);
        //$TEquip -> Set("id_instalador",	$id_instalador);
	$TEquip -> Set("ccid",	$ccid);
	
	// Apresentando o Formulario
	$TEquip -> Show("Equipamentos_Formulario");
	
	// Definindo os STATUS
	$st = array(	0 => "Bloqueado",
					1 => "Cadastrado",
					2 => "Em teste",
					3 => "Em trânsito - OS",
					4 => "Em trânsito - Instalador",
					5 => "Em uso",
					6 => "Em manutenção"	);
	
	// Construindo ARRAY do Select
	foreach ($st as $key => $value) {
		$selected = ($key == $status)?1:0;
		$aStatus[] = array($key,$value,$selected);
	}
	// Apresentando o SELECT de STATUS
	//$HTML -> select("status",$aStatus);	
	
	// Finalizando o Formulario
	$TEquip -> Show("Equipamentos_Formulario_Continuacao");
	if($id != ""){
	// Coletando Chips Todos
        	$ch = $CAD -> coletaChipsTodos();
            
        }
        else
        {
	// Coletando Chips Habilitados
	$ch = $CAD -> coletaChipsHabilitados();
        }
	
	// Construindo ARRAY do Select
	foreach ($ch as $key => $value) {
		$selected = ($key == $id_chip)?1:0;
		$aChip[] = array($key,$value,$selected);
	}
	// Apresentando o SELECT de STATUS
	$HTML -> select("id_chip1",$aChip);

        			
	// Finalizando o Formulario
	$TEquip -> Set("id_chip",$id_chip);
	$TEquip -> Show("Equipamentos_Formulario_FIM");	
?>