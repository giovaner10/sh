<?php

include('JSON.php');
include('funcoes_red.php');
$result = array();

if (isset($_FILES['photoupload']) )
{	$file = $_FILES['photoupload']['tmp_name'];
	$nfile = $_FILES['photoupload']['name'];
	$error = false;
	$size = false;

$caminho="../Documentos/Agenda/".$nfile;


if (!move_uploaded_file($file,$caminho)){
//    echo 'Arquivo em branco, envie um arquivo XML de NF-e<hr><a href="ler_nota.php">Voltar</a>';
//	exit;
	}
	else
	 { //echo 'Arquivo(s) enviado(s) com sucesso...'; echo '<hr><a href="ler_nota.php">Voltar</a>';
	 }

}
else
{
	$result['result'] = 'error';
	$result['error'] = 'Arquivo ausente ou erro interno!';
}


			
if (!headers_sent() )
{
	header('Content-type: application/json');
}
 
echo json_encode($result);

?>