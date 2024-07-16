<?php
$host = "localhost";
$user = "showtecsystem";
$pass = "sh0wt3csyst3m";
 
$base = "showtecsystem";
 
#####################  Conexão com o banco de dados  ################################
$conexao = mysql_connect($host, $user, $pass);
$banco = mysql_select_db($base) or die ("Não foi possível selecionar o Banco de dados.");
?>
