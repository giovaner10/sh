<?php
header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="Lote-NFSe-Mes-'.$mesdeemissao.'.xml"');

echo $xml;
exit();