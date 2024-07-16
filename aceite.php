<?php
include 'classes/cfg.php';

function Iniciais($nome,$minusculas = true){
        preg_match_all('/\s?([A-Z])/',$nome,$matches);
        $ret = implode('',$matches[1]);
        if (strlen($ret)<3){
           $nome = explode(" ", $nome);
           return  strtolower($nome[0]);
        }
        return $minusculas?
                strtolower($ret) :
                $ret;
}


function geraSenha($tamanho = 8, $maiusculas = false, $numeros = true, $simbolos = false)
{
// Caracteres de cada tipo
$lmin = 'abcdefghijklmnopqrstuvwxyz';
$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$num = '1234567890';
$simb = '!@#$%*-';
 
// Variáveis internas
$retorno = '';
$caracteres = '';
 
// Agrupamos todos os caracteres que poderão ser utilizados
$caracteres .= $lmin;
if ($maiusculas) $caracteres .= $lmai;
if ($numeros) $caracteres .= $num;
if ($simbolos) $caracteres .= $simb;
 
// Calculamos o total de caracteres possíveis
$len = strlen($caracteres);
 
for ($n = 1; $n <= $tamanho; $n++) {
// Criamos um número aleatório de 1 até $len para pegar um dos caracteres
$rand = mt_rand(1, $len);
// Concatenamos um dos caracteres na variável $retorno
$retorno .= $caracteres[$rand-1];
}
 
return $retorno;
}


mysql_select_db($dbs, $DB_);
$query_cliente = "SELECT * FROM cad_clientes  WHERE id=".$_GET['cd'];
$lista_cliente = mysql_query($query_cliente, $DB_) or die(mysql_error());
$row_lista_cliente = mysql_fetch_assoc($lista_cliente);

    $nome=$row_lista_cliente['nome'];
if (empty($row_lista_cliente['cnpj'])){
    $cnpj=$row_lista_cliente['cpf'];
}
else{
    $cnpj=$row_lista_cliente['cnpj'];
}


//$usuario = preg_replace("/[^a-zA-Z0-9\s]/", "", $nome);
//$usuario = Iniciais(ucwords(strtolower($usuario))).date(s);
$emails=explode(';',$row_lista_cliente['email']);
$usuario = strtolower($emails[0]);
$senha = geraSenha();
                    
$query = "SELECT * FROM contratos  WHERE id_cliente=".$_GET['cd']." and id_proposta=".$_GET['nr'];
$lista = mysql_query($query, $DB_) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
if ($row_lista>0)
{
    mysql_close($lista);
    header('Location: http://showtecnologia.com/Aceite.php');
}
else
{

    $DATA = date('Y-m-d');
    $query = "INSERT INTO `contratos`(`id_cliente`, `id_vendedor`, `id_proposta`, `tipo_proposta`, `quantidade_veiculos`, `meses`, `valor_mensal`, `boleto`, `mostra_taxa_fat`, `valor_instalacao`, `prestacoes`, `valor_prestacao`, `data_prestacao`, `prestacao_paga`, `vencimento`, `primeira_mensalidade`, `status`, `data_contrato`, `data_cadastro`, `multa`, `multa_valor`)  SELECT `id_cliente`, `id_vendedor`, `id`, `tipo_proposta`, `quantidade_veiculos`, `meses`, `valor_mensal`, `boleto`, `mostra_taxa_fat`, `valor_instalacao`, `prestacoes`, `valor_prestacao`, `data_prestacao`, `prestacao_paga`, `vencimento`, `primeira_mensalidade`, '2', '$DATA', '$DATA', `multa`, `multa_valor` FROM `propostas` WHERE id_cliente=".$_GET['cd']." and id=".$_GET['nr'];
    echo "inserindo contrato<br>";
    $atualiza = mysql_query($query, $DB_) or die(mysql_error());    
    
//    $query = "UPDATE `contratos` SET `status`='2' WHERE id=".$_GET['nr'];
//    $atualiza = mysql_query($query, $DB_) or die(mysql_error());    
    
    $query = "UPDATE `propostas` SET `status`='1' WHERE id=".$_GET['nr'];
    echo "atualizando proposta<br>";
    $atualiza = mysql_query($query, $DB_) or die(mysql_error());    
    

$query_cliente = "SELECT * FROM `systems`.`cadastro_clientes`  WHERE CNPJ_='$cnpj'";
$lista_cliente = mysql_query($query_cliente, $DB_) or die(mysql_error());
$row_lista_cliente = mysql_fetch_assoc($lista_cliente);

if ($row_lista_cliente>0)
{
}
 else {
    
     $query = "INSERT INTO `systems`.`cadastro_clientes` (
                `nome`,
                `cpf_cnpj`,
                `CNPJ_`)
              VALUES (
                '$nome',
                '$cnpj',
                '$cnpj')";
    echo "cadastrando cliente no gestor<br>";
    $atualiza = mysql_query($query, $DB_) or die(mysql_error());    
    
    $query = "INSERT INTO  `systems`.`cadastro_usuario` (
                `nome` ,
                `usuario` ,
                `senha` ,
                `CNPJ_`
            )
            VALUES (
                '$nome',
                '$usuario',
                 password('$senha'),
                '$cnpj')";
    echo "cadastrando usuario no gestor<br>";
    $atualiza = mysql_query($query, $DB_) or die(mysql_error());    

    //
    echo "enviando email...<br>";
    include 'phpmailer/senhaenvia.php?email='.$row_lista_cliente['email'];
 
    mysql_close($lista);
    mysql_close($lista_cliente);
    //header('Location: http://showtecnologia.com/Aceite.php');
}

//gestor();

}


//echo "CNPJ: $cnpj<br>";
//echo "<br>Usuario: $usuario<br>";
//echo "Senha: $senha<br>";

//     include 'phpmailer/senhaenvia_.php';


?>