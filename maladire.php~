<?php  
$emails=explode(',','azevedo.negocios@gmail.com');
//echo $_GET['pasta'];
//print_r($emails);

//IMPORTANDO CLASSE  
require_once "phpmailer/class.phpmailer.php";  
//INSTANCIANDO  
$mail = new PHPMailer();  
$mail->SetLanguage = "en";  
//$mail->CharSet  = "8859-1";  

// EMAIL DO REMETENTE DA MENSAGEM  
$mail->From = "suporte@showtecnologia.com";  

$mail->FromName = "Show Tecnologia";  

$mail->Mailer = "smtp"; //Usando protocolo SMTP  

$mail->Host = "smtp.showtecnologia.com"; //SERVIDOR SMTP  

// INFORMANDO NO EMAIL, O ASSUNTO DA MENSAGENS  

$mail->Subject = 'teste de envio'; //$_GET["assunto"];  

//SEU EMAIL E SENHA PARA USAR NA AUTENTICACAO SMTP  

$mail->Username = "luciano@showtecnologia.com";  

$mail->Password = "info1404";  

$mail->SMTPAuth = true; // AUTENTICACAO  

$mail->IsHTML(true);  

$mail->IsSMTP(); //SMTP  

//IMPORTANDO ARQUIVO HTML NO CORPO DA MENSAGEM (USADO MUITO COMO MALA DIRETA)  

$body           = 'teste envio de email pelo sistema local....'; //file_get_contents($_GET['pasta']);  

$mail->Body        = $body;  

   

//ENVIANDO  

foreach($emails as $key=>$value){
//   $mail->Subject = $emails[$key];  
   $mail->AddAddress("lucianocomputador@ig.com.br","Luciano Azevedo");  
   $mail->AddAddress($emails[$key]);     
   $mail->Send();  
}
    

//LIMPANDO RECIPIENTE  

$mail->ClearAllRecipients();  

   

//ENVIADO PELA 2 VEZ  

$mail->AddAddress("lucianocomputador@hotmail.com");  

$mail->Send();  

  

//LIMPANDO RECIPIENTE  

$mail->ClearAllRecipients();  
echo 'Enviados '.($key+1).' emails.';
?> 