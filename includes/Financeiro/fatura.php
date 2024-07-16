<?php
session_start();
ob_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Fatura SHOW TECNOLOGIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../../templates/default/css/estilosboleto.css" rel="stylesheet" type="text/css" />



</head>
<?php 
                    include '../../classes/cfg.php';
                    include '../../classes/Util.class.php';
                    mysql_select_db($dbs, $DB_);
                    $query = "SELECT *, cl.numero as nro FROM cad_clientes cl, cad_faturas ft where cl.id=ft.id_cliente AND ft.numero=".$_GET['numero'];
                    $lista = mysql_query($query, $DB_) or die(mysql_error());
                    $row_lista = mysql_fetch_assoc($lista);
                    $linha = mysql_affected_rows();
?>

<body>
    <div align="center" >
    <table class="tab" align="center">
      <tr>
        <td width="31%"><img src="../../templates/default/img/logo-showtecnologia.png" class="logo" ></td>
        <td width="43%" valign="top" class="endshow"> SHOW PRESTADORA DE SERVI&Ccedil;OS
          DO BRASIL LTDA<br>
          Av. Ruy Barbosa, 104 - Centro<br>
          Guarabira - PB - CEP: 58.200-000<br>
          CNPJ: 09.338.999/0001-58<br>
          www.showtecnologia.com<br>
          Fone (83) 3271-6559
        </td>
        <td width="26%" align="right" valign="top"><div class="fatura" align="center"><strong>FATURA</strong></div>
		  
		  Numero:  <?php $fatura = Util::zeros(6,$row_lista['numero']);
                           echo Util::zeros(6,$row_lista['numero']);?><br>
                           <?php if (!empty($row_lista['data_pagto'])){ ?>
                  Pago Em: <?php echo Util::formataData($row_lista['data_pagto']);?><br>
                           <?php } else { echo '';} ?>
                  Emiss&atilde;o: <?php $emis = Util::formataData($row_lista['data_emissao']);
                  echo Util::formataData($row_lista['data_emissao']); ?><br>
                  Vencimento: <?php
                  $venc = Util::formataData($row_lista['data_vencimento']);
                  echo Util::formataData($row_lista['data_vencimento']); ?>
                  
          </td>
      </tr>
    </table>

        <table class="tab" align="center">
       <tr>
           <td colspan="4" class="fatura"><strong>Dados do Cliente</strong></td>
       </tr>
       <tr>
            <td  align="right">Nome:</td>
            <td  colspan="3" class="dcli"><?php
            $clibol = utf8_encode($row_lista['nome']);
            echo utf8_encode($row_lista['nome']);?></td>
       </tr>
       <tr>
            <td align="right">Endere&ccedil;o:</td>
            <td colspan="3" class="dcli"><?php echo utf8_encode($row_lista['endereco']).' Nro.: '.$row_lista['nro'].' '.utf8_encode($row_lista['complemento']);?></td>
       </tr>
       <tr>
            <td align="right">Bairro:</td>
            <td width="48%" class="dcli"><?php echo $row_lista['bairro'];?></td>
            <td width="10%" align="right">CNPJ:</td>
            <td width="30%" class="dcli"><?php if (!empty($row_lista['cnpj'])){$clibol = $clibol.' - '.$row_lista['cnpj'];echo $row_lista['cnpj'];}else{$clibol = $clibol.' - '.$row_lista['cpf'];echo $row_lista['cpf'];}?></td>
       </tr>
       <tr>
            <td align="right">Cidade:</td>
            <td class="dcli"><?php $clibol = $clibol.' - '.utf8_encode($row_lista['cidade']).' - '.$row_lista['uf']; echo utf8_encode($row_lista['cidade']).' - '.$row_lista['uf'];?></td>
            <td align="right">CEP:</td>
            <td class="dcli"><?php echo $row_lista['cep'];?></td>
        </tr>
        <tr>
            <td align="right">Fone:</td>
            <td class="dcli"><?php echo $row_lista['fone'];?></td>
            <td align="right">Contato:</td>
            <td class="dcli"><?php echo $row_lista['contato'];?></td>
        </tr>
        <tr>
            <td align="right">Email:</td>
            <td colspan="3" class="dcli"><?php $cli_email=$row_lista['email2']; echo $cli_email;?></td>
        </tr>
    </table>

<?php
      $status = $row_lista['status'];
      $valor_boleto = $row_lista['valor_boleto'];
      $boleto_vencimento = Util::formataData($row_lista['boleto_vencimento']);
      //03132220888 - Sergio
      $mostra = 0;
      if ($row_lista['mostra_taxa_fat']==1){
          $mostra = 1;
      }
      if ($row_lista['taxa_boleto']>0){
           $tab = 270;      //600
      }
      else
      {
          $tab = 320;       //650;
      }
      //height="<?php echo $tab;px ?>
    <table class="tab" align="center" >
        <tr class="dcli">
            <td colspan="6" class="fatura"><strong>Produtos ou Servi&ccedil;os</strong></td>
        </tr>
        <tr>
            <td width="20" class="prod">Item</td>
            <td width="30" class="prod">Contrato</td>
            <td  class="prod">Descri&ccedil;&atilde;o</td>
            <td width="20" class="prod" align="center">Quantidade</td>
            <td width="60" class="prod" align="center">Valor Unit.</td>
            <td width="60" class="prod" align="center">Valor Total</td>
        </tr>
            

    
  <?php $item = 1; $ll = 22;
  $aceite = 'N';
  do {
  if ($row_lista['taxa_boleto']>0){
      $boleto = $row_lista['taxa_boleto'];
      $aceite = 'S';
      ?>

      <?php
      
      
  }
  if (!empty($row_lista['instrucoes1'])){
      $instrucoes = $row_lista['instrucoes1'];
  }
  ?>
        <tr class="prods">
            <td align="center" valign="top"><?php echo Util::zeros(3, $item);    ?></td>
            <td align="center" valign="top"><?php echo $row_lista['id_contrato']; ?></td>
            <td align="left">
                <?php echo utf8_encode($row_lista['descricao']);?>
                <div class="placas">
                    <table>
                        <tr>
                <?php
                    $query = "SELECT * FROM contratos_veiculos where id_contrato=".$row_lista['id_contrato']." order by placa";
                    $lista_placas = mysql_query($query, $DB_) or die(mysql_error());
                    $row_lista_placas = mysql_fetch_assoc($lista_placas);
                    $linha_placas = mysql_affected_rows();
                    if ($linha_placas!=0){
                ?>
                
                <?php $ll = $ll - 2.5;
                      $a=1; $aa=0;
                      do {         
                             echo '<td width=65px>'.$row_lista_placas['placa'].'</td>';
                             $aa=$aa + 1;
                             if ($aa==5){$aa=0;$a=0;}else{$a=1;}
                             if ($a==0){ echo '</tr><tr>';}
                         } while ($row_lista_placas = mysql_fetch_assoc($lista_placas));
     }?>
                        </tr>
                        </table>
                </div>
            </td>
            <td align="center" valign="top"><?php echo number_format($row_lista['quantidade'],0,",",".");?>&nbsp;</td>
            <td align="right" valign="top"><?php echo number_format($row_lista['valor_unitario'],2,",",".");?>&nbsp;</td>
            <td align="right" valign="top"><?php echo number_format($row_lista['valor_total'],2,",",".");?>&nbsp;</td>
        </tr>
  

<?php $soma = $soma + $row_lista['valor_total']; $item++; } while ($row_lista = mysql_fetch_assoc($lista));?>
        
        <tr>
            <td>&nbsp;</td>
        </tr>        
    </table>
        
    <table class="tab" align="center">
        
        <tr>
            <td colspan="6">Autentica&ccedil;&atilde;o Mec&acirc;nica - Recibo do Sacado</td>
        </tr>
    <?php if ($mostra==0){ //$boleto!=0?>        
        <tr>
            <td rowspan="3" colspan="6" valign="top" align="justify" class="inform"><?php if (!empty($instrucoes)){echo nl2br($instrucoes).'<br>';}?>ISS isento conforme LEI COMPLEMENTAR N&ordm; 116, DE 31 DE JULHO DE 2003, A loca&ccedil;&atilde;o de bens im&oacute;veis ou m&oacute;veis n&atilde;o constitui uma presta&ccedil;&atilde;o de servi&ccedil;os, mas disponibiliza&ccedil;&atilde;o de um bem, seja ele im&oacute;vel ou m&oacute;vel para utiliza&ccedil;&atilde;o do locat&aacute;rio sem a presta&ccedil;&atilde;o de um servi&ccedil;o.</td>
            <td class="total" align="center" width="110">TOTAL R$</td>
            <td class="total" align="right" width="70"><?php echo number_format($soma,2,",",".");?>&nbsp;</td>
        </tr>
        <tr>
            <td class="total" align="center" width="110">TAXA BOLETO R$</td>
            <td class="total" align="right" width="70"><?php echo number_format($boleto,2,",",".");?>&nbsp;</td>
        </tr>
        <tr>
            <td class="total" align="center" width="110">TOTAL<br>&Agrave; PAGAR R$</td>
            <td class="total" align="right" valign="middle" width="70"><?php echo number_format($soma + $boleto,2,",",".");?>&nbsp;</td>
        </tr>
    <?php } else {?>  
        <tr>
            <td rowspan="3" colspan="6" valign="top" align="justify" class="inform"><?php if (!empty($instrucoes)){echo nl2br($instrucoes).'<br>';}?>ISS isento conforme LEI COMPLEMENTAR N&ordm; 116, DE 31 DE JULHO DE 2003, A loca&ccedil;&atilde;o de bens im&oacute;veis ou m&oacute;veis n&atilde;o constitui uma presta&ccedil;&atilde;o de servi&ccedil;os, mas disponibiliza&ccedil;&atilde;o de um bem, seja ele im&oacute;vel ou m&oacute;vel para utiliza&ccedil;&atilde;o do locat&aacute;rio sem a presta&ccedil;&atilde;o de um servi&ccedil;o.</td>
            <td class="total" align="center" width="110">TOTAL<br>&Agrave; PAGAR R$</td>
            <td class="total" align="right" valign="middle" width="70"><?php echo number_format($soma,2,",",".");?>&nbsp;</td>
        </tr>
    <?php } ?>
    </table>
    <img src="../../includes/Financeiro/imagens/Tesourinha.png" height="10" width="20">-------------------------------------------------------------------------------------------------------------------------------------------
    <?php include 'boleto_bb.php';?>
    <table class="tab" align="center">
    <tr><td>
    <table class="header" align="center">
        <tr>
            <td ><img SRC="../../includes/Financeiro/imagens/logobb.jpg"></td>
            <td >
                <div class="field_cod_banco">
                    <?php echo $dadosboleto["codigo_banco_com_dv"]?>
                </div>
            </td>
            <td class="linha_digitavel"><?php echo $dadosboleto["linha_digitavel"]?></td>
        </tr>
    </table>
    <table align="center" class="line" cellspacing="0" cellpadding="0">
	<tbody>
	<tr class="titulos">
            <td class="local_pagto">Local de pagamento</td>
            <td class="vencimento2">Vencimento</td>
	</tr>
	<tr class="campos">
            <td class="local_pagto">QUALQUER BANCO AT&Eacute; O VENCIMENTO</td>
            <td class="vencimento2"><?php echo $dadosboleto["data_vencimento"]?></td>
        </tr>
	</tbody>
    </table>            
    <table align="center" class="line" cellspacing="0" cellpadding="0">
    <tbody>
    <tr class="titulos">
        <td class="cedente2">Cedente</td>
	<td class="ag_cod_cedente2">Ag&ecirc;ncia/C&oacute;digo cedente</td>
    </tr>
    <tr class="campos">
        <td class="cedente2"><?php echo $dadosboleto["cedente"]?></td>
	<td class="ag_cod_cedente2"><?php echo $dadosboleto["agencia_codigo"]?></td>
    </tr>
    </tbody>
    </table>
    <table align="center" class="line" cellspacing="0" cellpadding="0">
    <tbody>
        <tr class="titulos">
            <td class="data_doc">Data do documento</td>
            <td class="num_doc2">No. documento</td>
            <td class="especie_doc">Esp&eacute;cie doc.</td>
            <td class="aceite">Aceite</td>
            <td class="data_process">Data process.</td>
            <td class="nosso_numero2">Nosso n&uacute;mero</td>
	</tr>
	<tr class="campos">
            <td class="data_doc"><?php echo $dadosboleto["data_documento"]?></td>
            <td class="num_doc2"><?php echo $dadosboleto["numero_documento"]?></td>
            <td class="especie_doc"><?php echo $dadosboleto["especie_doc"]?></td>
            <td class="aceite"><?php echo $dadosboleto["aceite"]?></td>
            <td class="data_process"><?php echo $dadosboleto["data_processamento"]?></td>
            <td class="nosso_numero2"><?php echo $dadosboleto["nosso_numero"]?></td>
        </tr>
    </tbody>
    </table>
    <table align="center" class="line" cellspacing="0" cellPadding="0">
    	<tbody>
            <tr class="titulos">
                <td class="reservado">Uso do  banco</td>
		<td class="carteira">Carteira</td>
                <td class="especie2">Esp&eacute;cie</td>
		<td class="qtd2">Quantidade</td>
                <td class="xvalor">x Valor</td>
		<td class="valor_doc2">(=) Valor documento</td>
            </tr>
            <tr class="campos">
                <td class="reservado">&nbsp;</td>
		<td class="carteira"><?php echo $dadosboleto["carteira"]?> <?php echo isset($dadosboleto["variacao_carteira"]) ? $dadosboleto["variacao_carteira"] : '&nbsp;' ?></td>
		<td class="especie2"><?php echo $dadosboleto["especie"]?></td>
		<td class="qtd2"><?php echo $dadosboleto["quantidade"]?></td>
		<td class="xvalor"><?php echo $dadosboleto["valor_unitario"]?></td>
		<td class="valor_doc2"><?php echo $dadosboleto["Mvalor_boleto"];?></td>
            </tr>
	</tbody>
    </table>
            
		<table align="center" class="line" cellspacing="0" cellpadding="0">
		<tbody>
		<tr><td class="last_line" rowspan="6">
			<table class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="instrucoes">
						Instru&ccedil;&otilde;es:
				</td>
			</tr>
			<tr class="campos">
				<td class="instrucoes" rowspan="5">
					<p><?php echo $dadosboleto["demonstrativo1"]; ?></p>		
					<p><?php echo $dadosboleto["demonstrativo2"]; ?></p>
					<p><?php echo $dadosboleto["demonstrativo3"]; ?></p>
					<p><?php echo $dadosboleto["instrucoes1"]; ?></p>
					<p><?php echo $dadosboleto["instrucoes2"]; ?></p>
					<p><?php echo $dadosboleto["instrucoes3"]; ?></p>
					<p><?php echo $dadosboleto["instrucoes4"]; ?></p>
				</td>
			</tr>
			</tbody>
			</table>
		</td>
		
		<td>
			<table align="center" class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="desconto2">(-) Desconto / Abatimento</td>
			</tr>
			<tr class="campos">
				<td class="desconto2">&nbsp;</td>
			</tr>
			</tbody>
			</table>
		</td></tr>
		
		<tr><td>
			<table align="center" class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="outras_deducoes2">(-) Outras dedu&ccedil;&otilde;es</td>
			</tr>
			<tr class="campos">
				<td class="outras_deducoes2">&nbsp;</td>
			</tr>
			</tbody>
			</table>
		</td></tr>

		<tr><td>
			<table align="center" class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="mora_multa2">(+) Mora / Multa</td>
			</tr>
			<tr class="campos">
				<td class="mora_multa2">&nbsp;</td>
			</tr>
			</tbody>
			</table>
		</td></tr>

		<tr><td>
			<table align="center" class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="outros_acrescimos2">(+) Outros Acr&eacute;scimos</td>
			</tr>
			<tr class="campos">
				<td class="outros_acrescimos2">&nbsp;</td>
			</tr>
			</tbody>
			</table>
		</td></tr>

		<tr><td class="last_line">
			<table class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="valor_cobrado2">(=) Valor cobrado</td>
			</tr>
			<tr class="campos">
				<td class="valor_cobrado2">&nbsp;</td>
			</tr>
			</tbody>
			</table>
		</td></tr>
		</tbody>
		</table>

                <table align="center" class="line" cellspacing="0" cellPadding="0" >
		<tbody>
		<tr class="titulos">
			<td class="sacado2">Sacado</td>
		</tr>
		<tr class="campos">
			<td class="sacado2">
				<p><?php echo $dadosboleto["sacado"]?></p>
				<!--<p><?php echo $dadosboleto["endereco1"]?></p>-->
				<!--<p><?php echo $dadosboleto["endereco2"]?></p>-->
			</td>
		</tr>
		</tbody>
		</table>		
    <tr>
        <td align="right">
            Autentica&ccedil;&atilde;o mec&acirc;nica - Ficha de Compensa&ccedil;&atilde;o&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td>
            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php fbarcode($dadosboleto["codigo_barras"]); ?>
        </td>
    </tr>
            

            
    </td></tr>
    </table>


</div>
</body>
</html>
<?php
//<div style="height: 1px" align="center"></div>
mysql_free_result($lista);
mysql_free_result($lista_placas);

$out2 = ob_get_contents();

ob_end_clean();

echo $out2;

//$aqui="<tr><td><br></br>Para aceitar <br><a href='http://187.95.236.236:85/sistema/aceite.php?cd=$id_cliente&nr=$id' class='classname'>Click Aqui</a><br></br></td></tr>";
//$aqui="<tr><td><br></br>Para aceitar <br><a href='http://showtecnologia.com/aceito.php?cd=$id_cliente&nr=$id' class='classname'>Click Aqui</a><br></br></td></tr>";
//$out2=str_replace('<tr><td id="aqui"></td></tr>',$aqui,$out2);
//$out2=str_replace('./../../templates/default/img/','http://187.95.236.236:85/sistema/templates/default/img/',$out2);

//$_SESSION['tipoproposta'] = $cont['tipo_proposta'];
$_SESSION['assunto'] = 'Boleto SHOW TECNOLOGIA';
$_SESSION['conteudo'] = "
    Prezado cliente,
    <br>
    <br>
    Segue em anexo fatura com vencimento para ".$dadosboleto['data_vencimento']."
    <br>
    <br>
    Show Tecnologia<br>
    financeiro@showtecnologia.com<br>
    Tel.: (83) 3271-6559 ou 3271-4060.

";
$_SESSION['htmlpdf'] = utf8_decode($out2);
$_SESSION['email'] = $cli_email;

//include_once 'tcpdf/examples/maladireta.php';
?>