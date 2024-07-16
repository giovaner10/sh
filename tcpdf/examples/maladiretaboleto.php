<?php
include_once '../../includes/incVerificaSessao.php';
//echo basename(getcwd());
if ($_SESSION['email']==''){
    echo 'Cliente sem email..';    
    exit;
}

session_start();
require_once("dompdf-master/dompdf_config.inc.php");

$html = $_SESSION['htmlpdf'];
//$html = iconv('UTF-8','Windows-1250',$html);
$html = utf8_encode($html);
//$html = '123';
//echo $html;
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();

//$dompdf->stream("sample.pdf");
//$dompdf->output();
file_put_contents('boleto.pdf', $dompdf->output());
//header('Location: file.pdf');
include '../../phpmailer/malaenviaboleto.php';

?>






