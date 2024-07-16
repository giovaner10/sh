<?php 

/*
require_once('/home/sysvon45/public_html/painel/application/libraries/PHPMailer/vendor/phpmailer/phpmailer/src/PHPMailer.php');
require_once('/home/sysvon45/public_html/painel/application/libraries/PHPMailer/vendor/phpmailer/phpmailer/src/SMTP.php');
require_once('/home/sysvon45/public_html/painel/application/libraries/PHPMailer/vendor/phpmailer/phpmailer/src/Exception.php');
*/

require_once('../../libraries/PHPMailer/vendor/phpmailer/phpmailer/src/PHPMailer.php');
require_once('../../libraries/PHPMailer/vendor/phpmailer/phpmailer/src/SMTP.php');
require_once('../../libraries/PHPMailer/vendor/phpmailer/phpmailer/src/Exception.php');

switch (date("m")) {
    case "01":    $mes = "JANEIRO";     break;
    case "02":    $mes = "FEVEREIRO";   break;
    case "03":    $mes = "MARÇO";       break;
    case "04":    $mes = "ABRIL";       break;
    case "05":    $mes = "MAIO";        break;
    case "06":    $mes = "JUNHO";       break;
    case "07":    $mes = "JULHO";       break;
    case "08":    $mes = "AGOSTO";      break;
    case "09":    $mes = "SETEMBRO";    break;
    case "10":    $mes = "OUTUBRO";     break;
    case "11":    $mes = "NOVEMBRO";    break;
    case "12":    $mes = "DEZEMBRO";    break;
}

$dia = date('d');
$mesNiver = date('m');

$query = $this->db->query("SELECT * FROM cad_aniversariantes WHERE MONTH(data_nasc) = '$mesNiver' AND DAY(data_nasc) = '$dia' AND ativo = '1'");

foreach ($query->result_array() as $row) {

    if($row['empresa'] == "Omnlink"){
        $empresa = "OMNLINK";
    }else{
        $empresa = "SHOWNET";
    }
    
    $mensagem = "teste";
    
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    // DEFINI��O DOS DADOS DE AUTENTICA��O - Voc� deve auterar conforme o seu dom�nio!
    $mail->IsSMTP(); // Define que a mensagem ser� SMTP
    $mail->Host = "mail.sysvon.com.br"; // Seu endere�o de host SMTP
    $mail->SMTPAuth = true; // Define que ser� utilizada a autentica��o -  Mantenha o valor "true"
    $mail->Port = '465'; // Porta de comunica��o SMTP - Mantenha o valor "587"
    $mail->SMTPSecure = 'ssl'; // Define se � utilizado SSL/TLS - Mantenha o valor "false"
    $mail->SMTPAutoTLS = false; // Define se, por padr�o, ser� utilizado TLS - Mantenha o valor "false"
    $mail->Username = 'naoresponda@sysvon.com.br'; // Conta de email existente e ativa em seu dom�nio
    $mail->Password = 'Mendes@1487'; // Senha da sua conta de email
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    // DADOS DO REMETENTE
    $mail->Sender = "naoresponda@sysvon.com.br"; // Conta de email existente e ativa em seu dom�nio
    $mail->From = "naoresponda@sysvon.com.br"; // Sua conta de email que ser� remetente da mensagem
    $mail->FromName = "Sysvon | Sistema de Vendas Online"; // Nome da conta de email
    // DADOS DO DESTINAT�RIO
    $mail->AddAddress("$row[email]"); //$dadosEmpresa[email] Define qual conta de email receber� a mensagem
    //$mail->AddCC('wmendesprog@gmail.com'); // Define qual conta de email receberá uma cópia
    //$mail->AddBCC('wmendesprog@gmail.com'); // Define qual conta de email receberá uma cópia oculta
    
    $mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
    //$mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
    // email do destinatario.
    $mail->Subject = "aaaaaaaaaaaaaaaaaaaaa";
    $mail->Body = "$mensagem";
    //echo $mensagem;
    if ($mail->Send()) {
        echo "Resumo das vendas ".date("d/m/Y")." enviado com sucesso para".$row['email'];
    } else {
        //echo $mail->ErrorInfo;
        echo "Erro ao enviar o resumo das vendas diario.",$mail->ErrorInfo;
    }    
    
?>
<table border="0" cellspacing="0" cellpadding="0" width="100%"
	style="width: 100.0%">
	<tbody>
		<tr>
			<td valign="top" style="padding: 0cm 0cm 0cm 0cm">
				<div align="center">
					<table border="0" cellspacing="0" cellpadding="0" width="0"
						style="width: 450.0pt">
						<tbody>
							<tr style="height: 88.5pt">
								<td style="padding: .75pt .75pt .75pt .75pt; height: 88.5pt">
									<p class="MsoNormal" align="center" style="text-align: center">
										<span
											style="font-size: 30.0pt; font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: #939598"><?php echo $empresa;?><u></u><u></u></span>
									</p>
								</td>
								<td style="padding: .75pt .75pt .75pt .75pt; height: 88.5pt">
									<p class="MsoNormal" align="center" style="text-align: center">
										<span style="font-size: 18.0pt; font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: #939598">
											ANIVERSARIANTE DO DIA<u></u><u></u>
										</span>
									</p>
								</td>
							</tr>
							<tr>
								<td colspan="2" valign="top"
									style="padding: .75pt .75pt .75pt .75pt">
									<table border="0" cellspacing="0" cellpadding="0"
										style="background: white">
										<tbody>
											<tr style="height: 122.25pt">
												<td width="199"
													style="width: 150.0pt; background: #ffae00; padding: 0cm 0cm 0cm 0cm; height: 122.25pt">
													<div align="center">
														<table border="0" cellspacing="0" cellpadding="0"
															style="background: #ffae00">
															<tbody>
																<tr>
																	<td style="padding: 0cm 0cm 0cm 0cm">
																		<p class="MsoNormal">
																			<strong>
																				<span style="font-size: 60.0pt; font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: white">08</span>
																			</strong>
																			<span style="font-size: 60.0pt; font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: white"><u></u><u></u></span>
																		</p>
																	</td>
																</tr>
																<tr>
																	<td style="padding: 0cm 0cm 0cm 0cm">
																		<p class="MsoNormal">
																			<strong>
																				<span style="font-size: 10.5pt; font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: white">
																					DE <?php echo $mes;?>
																				</span>
																			</strong>
																			<span style="font-size: 10.5pt; font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: white"><u></u><u></u></span>
																		</p>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</td>
												<td width="399"
													style="width: 300.0pt; background: #ffecc2; padding: 0cm 0cm 0cm 0cm; height: 122.25pt">
													<div align="center">
														<table border="0" cellspacing="0" cellpadding="0"
															style="background: #ffecc2">
															<tbody>
																<tr>
																	<td style="padding: 0cm 0cm 0cm 0cm">
																		<p class="MsoNormal" align="center"
																			style="text-align: center">
																			<span
																				style="font-size: 45.0pt; font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: #ee7b00">PARABÉNS!<u></u><u></u></span>
																		</p>
																	</td>
																</tr>
																<tr>
																	<td style="padding: 0cm 0cm 0cm 0cm">
																		<p class="MsoNormal" align="center" style="text-align: center">
																			<strong>
																				<span style="font-size: 10.5pt; font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: #ee7b00">A
																					OMNILINK DESEJA UM FELIZ ANIVERSÁRIO PARA:
																				</span>
																			</strong>
																			<span style="font-size: 10.5pt; font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: #ee7b00"><u></u><u></u></span>
																		</p>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr style="height: 3.75pt">
								<td colspan="2"
									style="padding: .75pt .75pt .75pt .75pt; height: 3.75pt">
									<p class="MsoNormal">
										&nbsp;<u></u><u></u>
									</p>
								</td>
							</tr>
							<tr>
								<td width="600" colspan="2"
									style="width: 396.75pt; padding: .75pt .75pt .75pt .75pt">
									<div align="center">
										<table border="0" cellspacing="0" cellpadding="0" width="0"
											style="width: 396.75pt">
											<tbody>
												<tr>
													<td style="padding: 2.25pt 2.25pt 2.25pt 2.25pt">
														<p class="MsoNormal" align="center"
															style="text-align: center">
															<strong>
    															<span style="font-size: 10.5pt; font-family: &amp; quot;Arial&amp;quot;,sans-serif">
    																<?php echo strtoupper($row['nome']);?>
    															</span>
    														</strong>
    														<span style="font-size: 10.5pt; font-family: &amp; quot;Arial&amp;quot;,sans-serif"><u></u><u></u></span>
														</p>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr style="height: 3.75pt">
								<td colspan="2"
									style="padding: .75pt .75pt .75pt .75pt; height: 3.75pt">
									<p class="MsoNormal">
										&nbsp;<u></u><u></u>
									</p>
								</td>
							</tr>
							<tr style="height: 88.5pt">
								<td colspan="2"
									style="padding: .75pt .75pt .75pt .75pt; height: 88.5pt">
									<p class="MsoNormal" align="center" style="text-align: center">
										<strong><span
											style="font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: #ee7b00">Lembre-se
												de cumprimentar seus colegas de trabalho neste dia especial!</span></strong><span
											style="font-family: &amp; quot; Arial &amp;quot; , sans-serif; color: #ee7b00"><u></u><u></u></span>
									</p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<?php } ?>