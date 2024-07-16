<?php
include_once '../incVerificaSessao.php';
include("../../classes/Cadastro.class.php");

extract($_REQUEST);
$CAD = new Cadastro();
extract($CAD -> coletaDados($id,1));

?>
<table width="100%">
    <tr>
    <td>Relat&oacute;rio dos ve&iacute;culos desatualizados</td>
    <td align="right" valign="top">
        Data: <?=date('d/m/Y');?><br>
        Hora: <?=date('H:i:s');?>
    </td>
    </tr>
</table>

<table width="100%" border="1">
    <tr>
    <td colspan="6">Cliente: <?php echo $id.' - '.utf8_encode($nome);?></td>
    </tr>
    <tr>
        <th>Ord</th><th>Placa</th><th>Serial</th><th>Veiculo</th><th>Ult. Data</th><th>Endere&ccedil;o</th>
    </tr>
        
<?php
if (!empty($cnpj)){
    $CNPJ=preg_replace( '#[^0-9]#', '', $cnpj );
    $atrazo = date("Y-m-d", mktime(date('H'),date('i'),date('s'),date('m'), date('d')-1, date('Y')));
    $a=1;
// sem comunicacao        
    $query = "SELECT * FROM `systems`.`cadastro_veiculo` v WHERE right(v.serial,10) not in (select right(id,10) from `systems`.`resposta`) and v.CNPJ_ like '$CNPJ%' ";
    $busca = mysql_query($query, $DB_) or die(mysql_error());
    $row_busca = mysql_fetch_assoc($busca);
    $totalRows_busca = mysql_num_rows($busca);
    if ($totalRows_busca>0){ 
        do {?>
    <tr>
    <td><?=$a;?></td>
    <td><?=$row_busca['placa'];?></td>
    <td><?=$row_busca['serial'];?></td>
    <td><?=utf8_encode($row_busca['veiculo']);?></td>
    <td></td>
    <td></td>
    
    </tr>
        <?php $a++; } while($row_busca = mysql_fetch_array($busca));
        
    }
    
    
// desatualizados        
    $query = "SELECT * FROM `systems`.`cadastro_veiculo` v, `systems`.`resposta` r WHERE right(v.serial,10)=right(r.id,10) and v.CNPJ_ like '$CNPJ%' and left(data,10)<='$atrazo' order by r.data";
//    echo $query;
    $busca = mysql_query($query, $DB_) or die(mysql_error());
    $row_busca = mysql_fetch_assoc($busca);
    $totalRows_busca = mysql_num_rows($busca);
    if ($totalRows_busca>0){ 
        do {?>
    <tr>
    <td><?=$a;?></td>
    <td><?=$row_busca['placa'];?></td>
    <td><?=$row_busca['serial'];?></td>
    <td><?=utf8_encode($row_busca['veiculo']);?></td>
    <td><?=Util::dh_for_humans($row_busca['DATA']);?></td>
    <td><?=utf8_encode($row_busca['ENDERECO']);?></td>
    
    </tr>
        <?php $a++; } while($row_busca = mysql_fetch_array($busca));
        
    }
    
    
    }    ?>
   
</table>