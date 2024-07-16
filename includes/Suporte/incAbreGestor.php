<?php
include_once '../incVerificaSessao.php';
include_once "../classes/Cadastro.class.php";

extract($_REQUEST);
$CAD = new Cadastro();
extract($CAD -> coletaDados($id,1));
$usu = $_SESSION["usuario_nome"];
$CNPJ=preg_replace( '#[^0-9]#', '', $cnpj );
echo 'Usuario: '.$usu;
echo '<br>';
echo 'Clente : '.$nome;
echo '<br>';
echo 'Redirecionado para o GESTOR. ['.$CNPJ.']';

?>

<html>
<head>
<script type="text/javascript">
    window.open('http://187.95.236.236:85/show/GR/login.php?usu=<?=$usu;?>&cnpj=<?=$CNPJ;?>','gestor','gestor');
</script>
</head>
<body>
</body>
</html>