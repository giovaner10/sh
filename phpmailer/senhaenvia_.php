<?php 


$conteudo = "Usuario: $usuario<br>Senha: $senha";

//$emails=explode(',','azevedo.negocios@gmail.com');
//echo $_GET['pasta'];
//print_r($emails);
//$conteudo = $_GET['cc']; 

//IMPORTANDO CLASSE  
require_once "class.phpmailer.php";  
//INSTANCIANDO  
$mail = new PHPMailer();  
$mail->SetLanguage = "en";  
//$mail->CharSet  = "8859-1";  

// EMAIL DO REMETENTE DA MENSAGEM  
$mail->From = "lucianocomputador@ig.com.br";  

$mail->FromName = "Show Tecnologia";  

$mail->Mailer = "smtp"; //Usando protocolo SMTP  

$mail->Host = "smtp.ig.com.br"; //SERVIDOR SMTP  

// INFORMANDO NO EMAIL, O ASSUNTO DA MENSAGENS  

$mail->Subject = 'Senha de acesso ao sistema'; //$_GET["assunto"];  

//SEU EMAIL E SENHA PARA USAR NA AUTENTICACAO SMTP  

$mail->Username = "lucianocomputador@ig.com.br";  

$mail->Password = "info1404";  

$mail->SMTPAuth = true; // AUTENTICACAO  

$mail->IsHTML(true);  

$mail->IsSMTP(); //SMTP  

//ANEXO
////$file_pdf = chunk_split(base64_encode($doc));
//$mail->AddAttachment($file_pdf); 
//$mail->AddStringAttachment($docpdf, "arquivo.pdf", "base64", "application/pdf");


//IMPORTANDO ARQUIVO HTML NO CORPO DA MENSAGEM (USADO MUITO COMO MALA DIRETA)  

$body           = $conteudo; //stream_get_contents($conteudo);//file_get_contents($conteudo);  

//$mail->AddAttachment('uploads/$file'); 

$mail->Body        = $body;  

   

//ENVIANDO  

//foreach($emails as $key=>$value){
//   $mail->Subject = $emails[$key];  
//   $mail->AddAddress("lucianocomputador@ig.com.br","Luciano Azevedo");  
//   $mail->AddAddress($emails[$key]);     
//   $mail->Send();  
//}
    

//LIMPANDO RECIPIENTE  

//$mail->ClearAllRecipients();  

   

//ENVIADO PELA 2 VEZ  

$mail->AddAddress('azevedo.negocios@gmail.com');  
if ($mail!=''){
   //$mail->AddAddress($_SESSION['email']);
   if ($mail->Send()){
      //$LOG->insertLog($DB, 'O usuário "'.$_SESSION["usuario_login"].'" enviou email.');
      echo 'Enviado com sucesso';
   }
   else {
      echo 'ERROR:::Email Não enviado';    
   }
}
else{
    echo 'Cliente sem email.';
}
    
//LIMPANDO RECIPIENTE  

$mail->ClearAllRecipients();  
//echo 'Enviados '.($key+1).' emails.';

?> 