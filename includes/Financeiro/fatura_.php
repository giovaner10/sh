<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Fatura SHOW TECNOLOGIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style type="text/css">
<!--
.fatura {
	background-color: #CCC;
        }
.endshow {
	font-family: "Times New Roman", Times, serif;
	font-size: 12px;
}
.inffatura {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
}
.dcli {
	border-bottom-style: dotted;
	border-bottom-width: 1px;
	border-bottom-color: #000;
        height: 0;
}
.prod {
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: groove;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
        height: 1;
}
.prods {
        height: 1;
}
.tab {
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
        font-size: 10;
        }

.tab1 {
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
        }

        
.total {
	border: 2px solid #000;
}
.inform {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
}

-->
</style>
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

    <table width="780" align="center" class="tab">
  <tr>
    <td><table width="100%" class="tab">
      <tr>
        <td width="31%"><img src="../../templates/default/img/logo-showtecnologia.png" width="202" height="73">
        </td>
        <td width="43%" valign="top" class="endshow">SHOW PRESTADORA DE SERVI&Ccedil;OS
          DO BRASIL LTDA<br>
          Av. Ruy Barbosa, 104 - Centro<br>
          Guarabira - PB - CEP: 58.200-000<br>
          CNPJ: 09.338.999/0001-58<br>
          www.showtecnologia.com<br>
          Fone (83) 3271-6559</td>
        <td width="26%" align="right" valign="top"><div class="fatura" align="center">
          <strong>FATURA</strong></div>
		  <div class="inffatura">
		  <p>Numero: <?php $fatura = Util::zeros(6,$row_lista['numero']);
                  echo Util::zeros(6,$row_lista['numero']);?><br>
                      <?php if (!empty($row_lista['data_pagto'])){ ?>
                  Pago Em: <?php echo Util::formataData($row_lista['data_pagto']);?><br>
                  <?php } else { echo '<br>';} ?>
                  Emiss&atilde;o: <?php $emis = Util::formataData($row_lista['data_emissao']);
                  echo Util::formataData($row_lista['data_emissao']); ?><br>
                  Vencimento: <?php
                  $venc = Util::formataData($row_lista['data_vencimento']);
                  echo Util::formataData($row_lista['data_vencimento']); ?></p>
		  </div></td>
      </tr>
      </table>
	</td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
  <td>
      <table width="100%" class="tab"><tr>
              <td colspan="4" class="fatura tab"><strong>Dados do Cliente</strong></td>
  </tr>
    <tr>
      <td width="8%" align="right">Nome:</td>
      <td colspan="3" class="dcli"><?php
      $clibol = $row_lista['nome'];
      echo $row_lista['nome'];?></td>
      </tr>
    <tr>
      <td align="right">Endere&ccedil;o:</td>
      <td colspan="3" class="dcli"><?php echo $row_lista['endereco'].' Nro.: '.$row_lista['nro'].' '.$row_lista['complemento'];?></td>
      </tr>
    <tr>
      <td align="right">Bairro:</td>
      <td width="44%" class="dcli"><?php echo $row_lista['bairro'];?></td>
      <td width="5%" align="right">CNPJ:</td>
      <td width="43%" class="dcli"><?php if (!empty($row_lista['cnpj'])){$clibol = $clibol.' - '.$row_lista['cnpj'];echo $row_lista['cnpj'];}else{$clibol = $clibol.' - '.$row_lista['cpf'];echo $row_lista['cpf'];}?></td>
    </tr>
    <tr>
      <td align="right">Cidade:</td>
      <td class="dcli"><?php $clibol = $clibol.' - '.$row_lista['cidade'].' - '.$row_lista['uf']; echo $row_lista['cidade'].' - '.$row_lista['uf'];?></td>
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
      <td colspan="3" class="dcli"><?php echo $row_lista['email'];?></td>
      </tr>
  </table>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td>
    <?php
      $status = $row_lista['status'];
      $valor_boleto = $row_lista['valor_boleto'];
      $boleto_vencimento = Util::formataData($row_lista['boleto_vencimento']);
      //03132220888 - Sergio
      
      if ($row_lista['taxa_boleto']>0){
           $tab = 270;      //600
      }
      else
      {
          $tab = 320;       //650;
      }
      ?>
    <table id="conten2" name="conten2" width="100%" class="tab" height="<?php echo $tab;?>px">
  <tr class="dcli">
    <td colspan="6" class="fatura tab"><strong>Produtos ou Servi&ccedil;os</strong></td></tr>
  <tr>
    <td width="20" class="prod">Item</td>
    <td width="20" class="prod">Contrato</td>
    <td width="400" class="prod">Descri&ccedil;&atilde;o</td>
    <td width="70" class="prod">Quantidade</td>
    <td width="70" class="prod">Valor Unit.</td>
    <td width="80" class="prod" align="center">Valor Total</td>
  </tr>
  <?php $item = 1; $ll = 22;
  $aceite = 'N';
  do {
  if ($row_lista['taxa_boleto']>0){
      $boleto = $row_lista['taxa_boleto'];
      $aceite = 'S';
      ?>
<script type="text/javascript">
//        document.getElementById("conten2").height = 1800; // passa para a div pai o height da div filho 
</script>      
      <?php
      
      
  }
  if (!empty($row_lista['instrucoes1'])){
      $instrucoes = $row_lista['instrucoes1'];
  }
  ?>
  <tr class="prods">
    <td align="center" valign="top"><?php echo Util::zeros(3, $item);    ?></td>
    <td align="center" valign="top"><?php echo $row_lista['id_contrato']; ?></td>
    <td>
        
        <?php echo $row_lista['descricao'];?>
            <?php
            
                    $query = "SELECT * FROM contratos_veiculos where id_contrato=".$row_lista['id_contrato']." order by placa";
                    $lista_placas = mysql_query($query, $DB_) or die(mysql_error());
                    $row_lista_placas = mysql_fetch_assoc($lista_placas);
                    $linha_placas = mysql_affected_rows();
                    if ($linha_placas!=0){
             ?>
        <table width="100%" class="tab prods" style="font-size: 10">
            <?php $ll = $ll - 2.5;
            $a=0; $aa=0;
            
           do {         
           ?>            

        <?php if ($a==0){ echo '<tr>';} ?>
                <td width="20%">
                    <?php echo $row_lista_placas['placa'];
                      $aa=$aa + 1;
                      if ($aa==5){$aa=0;$a=0;}else{$a=1;}
                    ?>
                </td>
        <?php if ($a==0){ echo '</tr>';} ?>
            
        <?php } while ($row_lista_placas = mysql_fetch_assoc($lista_placas));?>            
        </table>
       <?php }?>
    </td>
    <td align="center" valign="top"><?php echo number_format($row_lista['quantidade'],0,",",".");?>&nbsp;</td>
    <td align="right" valign="top"><?php echo number_format($row_lista['valor_unitario'],2,",",".");?>&nbsp;</td>
    <td align="right" valign="top"><?php echo number_format($row_lista['valor_total'],2,",",".");?>&nbsp;</td>
  </tr>
  

<?php $soma = $soma + $row_lista['valor_total']; $item++; } while ($row_lista = mysql_fetch_assoc($lista));?>

<?php

//for ($i = 1; $i <= $ll - linha; $i++) {?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?php// }?>  
    </table>
    
    <table class="tab" width="100%">
        
  <tr>
    <td width="20"></td>
    <td width="20"></td>
    <td width="400">Autentica&ccedil;&atilde;o Mac&acirc;nica - Recibo do Sacado</td>
    <td width="70"></td>
    <td width="70"></td>
    <td width="80"></td>
  </tr>
<?php if ($boleto!=0){ ?>        
  <tr>
      <td rowspan="3" colspan="3" valign="top" class="inform"><?php if (!empty($instrucoes)){echo nl2br($instrucoes).'<br>';}?>ISS isento conforme LEI COMPLEMENTAR N&ordm; 116, DE 31 DE JULHO DE 2003, A loca&ccedil;&atilde;o de bens im&oacute;veis ou m&oacute;veis n&atilde;o constitui uma presta&ccedil;&atilde;o de servi&ccedil;os, mas disponibiliza&ccedil;&atilde;o de um bem, seja ele im&oacute;vel ou m&oacute;vel para utiliza&ccedil;&atilde;o do locat&aacute;rio sem a presta&ccedil;&atilde;o de um servi&ccedil;o.</td>
      <td colspan="2" align="center" class="total">TOTAL R$</td>
      <td class="total" align="right"><?php echo number_format($soma,2,",",".");?>&nbsp;</td>
  </tr>
  <tr>
      <td colspan="2" align="center" class="total">TAXA BOLETO R$</td>
      <td class="total" align="right"><?php echo number_format($boleto,2,",",".");?>&nbsp;</td>
  </tr>
  <tr>
      <td colspan="2" align="center" class="total">TOTAL<br>&Agrave; PAGAR R$</td>
      <td class="total" align="right"><?php 
      echo number_format($soma + $boleto,2,",",".");?>&nbsp;</td>
  </tr>
  <?php } else {?>  
  <tr>
      <td rowspan="3" colspan="3" valign="top" class="inform"><?php if (!empty($instrucoes)){echo nl2br($instrucoes).'<br>';}?>ISS isento conforme LEI COMPLEMENTAR N&ordm; 116, DE 31 DE JULHO DE 2003, A loca&ccedil;&atilde;o de bens im&oacute;veis ou m&oacute;veis n&atilde;o constitui uma presta&ccedil;&atilde;o de servi&ccedil;os, mas disponibiliza&ccedil;&atilde;o de um bem, seja ele im&oacute;vel ou m&oacute;vel para utiliza&ccedil;&atilde;o do locat&aacute;rio sem a presta&ccedil;&atilde;o de um servi&ccedil;o.</td>
      <td colspan="2" align="center" class="total">TOTAL<br>&Agrave; PAGAR R$</td>
      <td class="total" align="right"><?php 
      echo number_format($soma,2,",",".");?>&nbsp;</td>
  </tr>
  <?php } ?>
    </table>
</td>
</tr>
<tr>
    <td>
        <img src="imagens/Tesourinha.png" height="10" width="20">-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    </td>
</tr>

<tr>
    <td>
    
    <table width="100%"  class="tab">
        <tr>
            <td>
                <?php include 'boleto_bb.php';    ?>
            </td>
        </tr>
        
    
    </table>

    </td>
</tr>
</table>  
</body>
</html>
<?php
//<div style="height: 1px" align="center"></div>
mysql_free_result($lista);
mysql_free_result($lista_placas);
?>