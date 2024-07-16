<?

$host = "localhost";

$user = "root";

$pass = "";

$base = "teste";

$conn = @mysql_connect($host, $user, $pass) or die ("<br><br><center><font size=2 color=red face=verdana><b>Problemas ao conectar no servidor:<br><br><i>" . mysql_error() . "</i></b></font></center>");

$banco = @mysql_select_db($base) or mysql_create_db($base);mysql_select_db($base) or die ("<br><br><center><font size=2 color=red face=verdana><b>Problemas ao criar banco:<br><br><i>" . mysql_error() . "</i></b></font></center>");



?>
