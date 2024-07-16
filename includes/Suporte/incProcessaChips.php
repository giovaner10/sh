<?php
include '../../classes/Cadastro.class.php';
include '../../classes/DB.class.php';
//echo basename(getcwd()).'<br>';
$arq = '../'.$_GET['arq'];
//echo $arq;
mysql_select_db($dbs, $DB_);

$ponteiro = fopen ($arq,"r");
$a=1;
while (!feof ($ponteiro)) {
  $linha = trim(fgets($ponteiro,4096));
  if (!empty($linha)){
      echo $a.' - '.$linha;
      $ccid = trim(fgets($ponteiro,4096));
      echo ' - CCID : '.$ccid."<br>";

      $query="SELECT * FROM cad_chips WHERE ccid='$ccid'";
      $lista = mysql_query($query, $DB_) or die(mysql_error());
      $tlista = mysql_num_rows($lista);
      if ($tlista>0){
          echo  'CCID ja cadastrado...<br>';
      }else{
          $query="SELECT * FROM cad_chips WHERE numero='$linha'";
          $lista = mysql_query($query, $DB_) or die(mysql_error());
          $tlista = mysql_num_rows($lista);
          if ($tlista>0){
              echo  'LINHA ja cadastrado...<br>';
          }else{
              $query="insert into cad_chips (ccid,numero,operadora) values ('$ccid','$linha','VIVO')";
              $lista = mysql_query($query, $DB_) or die(mysql_error());
          /*    
              # dec/17/2012 16:55:18 by RouterOS 5.15
              # software id = WEE0-WR2C
              #
              /ppp secret
              add caller-id="" disabled=no limit-bytes-in=0 limit-bytes-out=0 name=\
              8381191312 password=show profile=default-encryption routes="" service=any
        */
              if ($a==1){
                  $dt = date('d-m-Y H:i:s');
                  $cmd = "# $dt Criado pelo sistema\n";
                  $cmd .= "# software id = WEE0-WR2C\n";
                  $cmd .= "#\n";
                  $cmd .= "/ppp secret\n";
              }else{$cmd = "";}
              
              $cmd .= 'add caller-id="" disabled=no limit-bytes-in=0 limit-bytes-out=0 name=\\'."\n";
              $cmd .= "$linha password=show profile=default-encryption routes=\"\" service=any\n";
              
              $fp = fopen($arq.".rsc", "a"); // abre o arquivo
              fwrite($fp, $cmd);
              fclose($fp); // fecha o arquivo

          }
      }
      
      $a++;
  }

}
fclose ($ponteiro);

?>

