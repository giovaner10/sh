<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sender extends CI_Model {

	/**
	 * @author Isaias Filho
	 */

	public function __construct(){
		parent::__construct();
		$this->load->library('email');
		$config['protocol'] = 'smtp';
		$config['charset'] = 'utf-8';
		$config['mailtype'] = 'html';
		$config['bcc_batch_mode'] = true;
		$config['bcc_batch_size'] = 5000;
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_crypto'] = 'SSL';
		$config['smtp_user'] = 'suporteshowtech@gmail.com';
		$config['smtp_pass'] = '6zP6nw8ccW';
		$config['smtp_port'] = '465';
		$this->email->initialize($config);
		$this->email->set_newline("\r\n");
		$this->load->helper('path');
	}

	public function enviar_email($from_email, $from_nome, $to, $subject, $message, $cc = false, $bcc_emails = false, $anexo = false) {
		$this->email->clear(TRUE);
		$this->email->to($to);
		if ($cc != false) { $this->email->cc($cc); }
		if ($bcc_emails != false) { $this->email->bcc($bcc_emails); }
		if ($anexo != false) { $this->email->attach($anexo); }
		$this->email->from($from_email, $from_nome);
		$this->email->subject($subject);
		$this->email->message($message);
		if($this->email->send())
			return true;
		return false;
	}

	public function sendEmail( $from, $para, $assunto, $mensagem){
		$CI =& get_instance();

		$token_api_email = $CI->config->item('token_api_email');

        $body = [
            "remetente" => $from,
            "mensagem"    => $mensagem,
            "assunto"     => $assunto,
            "destinatarios"    => $para
         ];

        $response = from_relatorios_api($body, "email",  $token_api_email);
        return $response;
    }
	
	public function sendEmailAPI($assunto ,$mensagem, $destinatarios){
		$CI =& get_instance();
	
		$curl = curl_init();

		$token_api_email 	= $CI->config->item('token_api_email');
		$url_api_email 	= $CI->config->item('url_api_email');

		curl_setopt_array($curl, [
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPHEADER => array(
				'x-access-token: '.$token_api_email,
				'Content-Type: application/json'
			),
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode(array(
				"mensagem"		=> $mensagem,
				"assunto"		=> $assunto,
				"destinatarios"	=> $destinatarios,
				"remetente"		=> "no-reply@notificacaogestor.com"
			)),
			CURLOPT_URL => $url_api_email
		]);
		
		$response = curl_exec($curl);

		curl_close($curl);
        return $response;
	}
}
