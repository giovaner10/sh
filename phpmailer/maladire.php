<?php 
     	include_once '../includes/incVerificaSessao.php';
        
// Classes necessarias
require_once '../classes/DB.class.php';
require_once '../classes/Log.class.php';

$DB = new DB ();        
$LOG = new Log();

//$doc = $pdf->Output('contrato_001.pdf', 'S');

if ($_SESSION['email']==''){
   echo 'Cliente sem email, verifique cadastro...' ;
   exit;
    
}
$conteudo = $_SESSION['conteudo'];

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
$mail->From = "suporte@showtecnologia.com";  

$mail->FromName = "Show Tecnologia";  

$mail->Mailer = "smtp"; //Usando protocolo SMTP  

$mail->Host = "smtp.showtecnologia.com"; //SERVIDOR SMTP  

// INFORMANDO NO EMAIL, O ASSUNTO DA MENSAGENS  

$mail->Subject = $_SESSION['assunto']; //'teste de envio'; //$_GET["assunto"];  

//SEU EMAIL E SENHA PARA USAR NA AUTENTICACAO SMTP  

$mail->Username = "luciano@showtecnologia.com";  

$mail->Password = "info1404";  

$mail->SMTPAuth = true; // AUTENTICACAO  

$mail->IsHTML(true);  

$mail->IsSMTP(); //SMTP  

//ANEXO
////$file_pdf = chunk_split(base64_encode($doc));
//$mail->AddAttachment($file_pdf); 
$mail->AddStringAttachment($_SESSION['docpdf'], $_SESSION['arquivo'], "base64", "application/pdf");


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

$mail->AddAddress($_SESSION["email"]);  

if ($mail->Send()){
   $LOG->insertLog($DB, 'O usuário "'.$_SESSION["usuario_login"].'" enviou email.');
   echo 'Enviado com sucesso';
}
 else {
   echo 'Não enviado';    
}
//LIMPANDO RECIPIENTE  

$mail->ClearAllRecipients();  
//echo 'Enviados '.($key+1).' emails.';

?> 
