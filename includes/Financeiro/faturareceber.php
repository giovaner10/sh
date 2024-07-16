<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Faturas a Receber</title>
<style type="text/css">
.tab_1 {
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
}
.tab_1 tr td {
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
}
</style>
</head>

<body>
    <center>
    RELATÓRIO DAS FATURAS A RECEBER<br>
    Periodo de
    <?php echo $_GET['ini'];?>
    &Agrave;
    <?php echo $_GET['fim'];?>
    </center>
<?php


list($dia,$mes,$ano) = explode("/",$_GET['ini']);
$ini = "$ano-$mes-$dia";
list($dia,$mes,$ano) = explode("/",$_GET['fim']);
$fim = "$ano-$mes-$dia";
if (!empty($_GET['id'])){
$cli = "ft.id_cliente=".$_GET['id'].' AND ';
}
else{
    $cli = '';
}

                    include '../../classes/cfg.php';
                    include '../../classes/Util.class.php';
                    mysql_select_db($dbs, $DB_);
                    if ($_GET['ped'] == 1){
                        $query = "SELECT *, sum(valor_total) as valor FROM cad_clientes cl, cad_faturas ft WHERE $cli ft.status in ('2','0') AND ft.id_cliente=cl.id and ft.data_vencimento>='$ini' and ft.data_vencimento<='$fim' GROUP BY ft.numero ORDER BY ft.data_vencimento";
                    }
                    else
                    {
                        $query = "SELECT *, sum(valor_total) as valor FROM cad_clientes cl, cad_faturas ft WHERE $cli ft.status=0 AND ft.id_cliente=cl.id and ft.data_vencimento>='$ini' and ft.data_vencimento<='$fim' GROUP BY ft.numero ORDER BY ft.data_vencimento";
                    }
                    $lista = mysql_query($query, $DB_) or die(mysql_error());
                    $row_lista = mysql_fetch_assoc($lista);
                    $numero = $row_lista['numero'] + 1;

                    
?>
    <table width="800" align="center" class="tab_1">
  <tr>
    <th width="100" bgcolor="#FFCC99" scope="col">Fatura</th>
    <th width="350" bgcolor="#FFCC99" scope="col">Cliente</th>
    <th width="100" bgcolor="#FFCC99" scope="col">Emissão</th>    
    <th width="100" bgcolor="#FFCC99" scope="col">Vencimento</th>
    <th width="100" bgcolor="#FFCC99" scope="col">Valor</th>    
  </tr>
  <?php //echo $query;
  $tg = 0;
  $tt = 0;
  $ven_old = $row_lista['data_vencimento'];
  $linha = 0;
  do {
    if ($ven_old !=  $row_lista['data_vencimento']){
      ?>
    <tr>
        <th colspan="3" align="right" bgcolor="#99FFCC"><strong>Total no Venvimento:</strong></th>
        <th align="center" bgcolor="#99FFCC"><?php echo implode("/",array_reverse(explode("-",$ven_old)));?></th>
        <th align="right" bgcolor="#99FFCC"><?php echo number_format($tg,2,",",".");?></th>
    </tr>
        <?php
        $ven_old = $row_lista['data_vencimento'];
        $linha = $linha + 1;      
        $tg = 0;
        }
        ?>

  <tr>
    <td align="center"><?php echo Util::zeros(6, $row_lista['numero']);?></td>
    <td><font size="2"><?php echo utf8_encode($row_lista['nome']);?></font></td>
    <td align="center"><?php echo implode("/",array_reverse(explode("-",$row_lista['data_emissao'])));?></td>
    <td align="center"><?php echo implode("/",array_reverse(explode("-",$row_lista['data_vencimento'])));?></td>
    <td align="right"><?php echo number_format($row_lista['valor'],2,",",".");?></td>
  </tr>
  <?php
  $linha = $linha + 1;
  //if ($linha >= 40){ echo '<tr><th colspan="5"></th></tr>'; $linha = 0;}
  //<div style="page-break-before: always"></div>
  $tg = $tg + $row_lista['valor'];
  $tt = $tt + $row_lista['valor'];
  } while ($row_lista = mysql_fetch_assoc($lista));
  ?>
    <tr>
        <th colspan="3" align="right" bgcolor="#99FFCC"><strong>Total no Venvimento:</strong></th>
        <th align="center" bgcolor="#99FFCC"><?php echo implode("/",array_reverse(explode("-",$ven_old)));?></th>
        <th align="right" bgcolor="#99FFCC"><?php echo number_format($tg,2,",",".");?></th>
    </tr>
  <tr>
    <th colspan="3" align="right" bgcolor="#33FFFF"><strong>Total Geral:</strong></th>
    <th bgcolor="#33FFFF">&nbsp;</th>
    <th align="right" bgcolor="#99FFCC"><?php echo number_format($tt,2,",",".");?></th>
  </tr>
  
</table>
</body>
</html>
<?php
mysql_free_result($lista);
?>