<?php
//echo phpinfo();
include 'classes/cfg.php';

mysql_select_db($dbs, $DB_);
$query = "SELECT * FROM cad_faturas group by numero";
$lista = mysql_query($query, $DB_) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);

do {
    $nro = $row_lista['numero'];
    $queryS = "SELECT sum(valor_total) as total FROM cad_faturas where numero='$nro' group  by numero";
    $listaS = mysql_query($queryS, $DB_) or die(mysql_error());
    $row_listaS = mysql_fetch_assoc($listaS);
    $total = $row_listaS['total'];    
    
    
    $queryU = "UPDATE cad_faturas SET total_fatura = $total, valor_boleto=$total where numero='$nro'";
    $listaU = mysql_query($queryU, $DB_) or die(mysql_error());
    
    

    
} while ($row_lista = mysql_fetch_assoc($lista));

    $queryU = "UPDATE cad_faturas SET valor_boleto=total_fatura+multa+juros where multa>0 and juros>0";
    $listaU = mysql_query($queryU, $DB_) or die(mysql_error());

?>
