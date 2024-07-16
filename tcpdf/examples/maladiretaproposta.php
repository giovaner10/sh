<?php
include_once '../../includes/incVerificaSessao.php';
//file_put_contents('teste.txt', $_SESSION['html']);

if ($_SESSION['email']==''){
    echo 'Cliente sem email.';    
    exit;
}

$anexo="./../../templates/default/img/propostas/proposta".$_SESSION['tipoproposta'].".pdf";

$anexo_=array_pop(explode('/',$anexo));

require_once '../../phpmailer/malaenviaP.php';

?>