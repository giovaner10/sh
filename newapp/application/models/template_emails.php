<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_emails extends CI_Model {

    /**
     * @author Isaias Filho
     */

	public function __construct(){
		
		parent::__construct();
                
	}

	/**
	 * Template de email para os veiculos desatualizados
	 * @param array veiculos - Array/Objeto dos veículos desatualizados
	 * @return string
	 */
	public function email_veiculos_desatualizados_usuario($veiculos = null, $cliente = null)
	{
		$mensagem = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title></title>


		<!--[if gte mso 6]>
		  <style>
		      table.kmButtonBarContent {width:100% !important;}
		  </style>
		<![endif]-->
		<style type="text/css">
		  @media only screen and (max-width: 480px) {
		    body, table, td, p, a, li, blockquote {
		      -webkit-text-size-adjust: none !important;
		    }
		    body{
		      width: 100% !important;
		      min-width: 100% !important;
		    }
		    td[id=bodyCell] {
		      padding: 10px !important;
		    }
		    table[class=kmTextContentContainer] {
		      width: 100% !important;
		    }
		    table[class=kmBoxedTextContentContainer] {
		      width: 100% !important;
		    }
		    td[class=kmImageContent] {
		      padding-left: 0 !important;
		      padding-right: 0 !important;
		    }
		    img[class=kmImage] {
		      width:100% !important;
		    }
		    table[class=kmSplitContentLeftContentContainer],
		    table[class=kmSplitContentRightContentContainer],
		    table[class=kmColumnContainer] {
		      width:100% !important;
		    }
		    table[class=kmSplitContentLeftContentContainer] td[class=kmTextContent],
		    table[class=kmSplitContentRightContentContainer] td[class=kmTextContent],
		    table[class="kmColumnContainer"] td[class=kmTextContent] {
		      padding-top:9px !important;
		    }
		    td[class="rowContainer kmFloatLeft"],
		    td[class="rowContainer kmFloatLeft firstColumn"],
		    td[class="rowContainer kmFloatLeft lastColumn"] {
		      float:left;
		      clear: both;
		      width: 100% !important;
		    }
		    table[id=templateContainer],
		    table[class=templateRow],
		    table[id=templateHeader],
		    table[id=templateBody],
		    table[id=templateFooter] {
		      max-width:600px !important;
		      width:100% !important;
		    }
		    
		      h1 {
		        font-size:24px !important;
		        line-height:100% !important;
		      }
		    
		    
		      h2 {
		        font-size:20px !important;
		        line-height:100% !important;
		      }
		    
		    
		      h3 {
		        font-size:18px !important;
		        line-height:100% !important;
		      }
		    
		    
		      h4 {
		        font-size:16px !important;
		        line-height:100% !important;
		      }
		    
		    
		      td[class=rowContainer] td[class=kmTextContent] {
		        font-size:18px !important;
		        line-height:100% !important;
		        padding-right:18px !important;
		        padding-left:18px !important;
		      }
		    
		    
		      td[class=headerContainer] td[class=kmTextContent] {
		        font-size:18px !important;
		        line-height:100% !important;
		        padding-right:18px !important;
		        padding-left:18px !important;
		      }
		    
		    
		      td[class=bodyContainer] td[class=kmTextContent] {
		        font-size:18px !important;
		        line-height:100% !important;
		        padding-right:18px !important;
		        padding-left:18px !important;
		      }
		    
		    
		      td[class=footerContent] {
		        font-size:18px !important;
		        line-height:100% !important;
		      }
		    
		    td[class=footerContent] a {
		      display:block !important;
		    }
		  }
		</style>

		</head>
		<body style="margin: 0; padding: 0; background-color: #c7c7c7">
		<center>
		<table align="center" border="0" cellpadding="0" cellspacing="0" id="bodyTable" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0; background-color: #c7c7c7; height: 100%; margin: 0; width: 100%">
		<tbody>
		<tr>
		<td align="center" id="bodyCell" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top: 50px; padding-left: 20px; padding-bottom: 20px; padding-right: 20px; border-top: 0; height: 100%; margin: 0; width: 100%">
		<table border="0" cellpadding="0" cellspacing="0" id="templateContainer" width="600" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; border: 1px solid #aaa; background-color: #f4f4f4">
		<tbody>
		<tr>
		<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<table border="0" cellpadding="0" cellspacing="0" class="templateRow" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="rowContainer kmFloatLeft" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		<tr>
		<td align="center" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<table border="0" cellpadding="0" cellspacing="0" class="templateRow" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="rowContainer kmFloatLeft" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<table border="0" cellpadding="0" cellspacing="0" class="kmImageBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmImageBlockOuter">
		<tr>
		<td class="kmImageBlockInner" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding:9px;" valign="top">
		<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmImageContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="kmImageContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0; padding-top:0px;padding-bottom:0;padding-left:9px;padding-right:9px;text-align: center;">
		<img align="center" alt="Show Tecnologia" class="kmImage" src="https://d3k81ch9hvuctc.cloudfront.net/company%2F9TqzGS%2Fimages%2Flogo-show.png" width="510" height="185" style="border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; max-width: 100%; padding-bottom: 0; display: inline; vertical-align: bottom" />
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="kmDividerBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmDividerBlockOuter">
		<tr>
		<td class="kmDividerBlockInner" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:18px;padding-bottom:18px;padding-left:18px;padding-right:18px;">
		<table class="kmDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; border-top-width:1px;border-top-style:solid;border-top-color:#ccc;">
		<tbody>
		<tr><td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0"><span></span></td></tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmTextBlockOuter">
		<tr>
		<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; ">
		<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">
		<p style="margin: 0; padding-bottom: 1em"> </p>
		<p style="margin: 0; padding-bottom: 1em">Prezado '. ($cliente ? $cliente->nome : '') .',</p>
		<p style="margin: 0; padding-bottom: 1em">'. ($cliente ? $cliente->usuario : '') .',</p>
		<p style="margin: 0; padding-bottom: 0">Foi constatado que os veículos abaixo estão com os dados desatualizados a mais de 48hs em nosso sistema. Por favor, entre em contato com nosso suporte para que seja marcada a data da manutenção dos mesmo.</p>
		</td>
		</tr>
		<tr>
		<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">
		    
		    <table border="1" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		      <thead>
		        <tr>
		          <th style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">Veículo</th>
		          <th style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">Placa</th>
		          <th style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">Serial</th>
		          <th style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">Última atualização</th>
		        </tr>
		      </thead>
		      <tbody class="kmTextBlockOuter">';

		      foreach ($veiculos as $veiculo) {
		        $mensagem .= '<tr>
		          <td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">'.$veiculo->veiculo.'</td>
		          <td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">'.$veiculo->placa.'</td>
		          <td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">'.$veiculo->serial.'</td>
		          <td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px;">'.dh_for_humans($veiculo->ultima_atualizacao).'</td>
		        </tr>';
		      }
		

		$mensagem .= '</tbody>
		    </table>


		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmTextBlockOuter">
		<tr>
		<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:20px;padding-bottom:20px;">
		<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">
		<p style="margin: 0; padding-bottom: 1em"><strong>Atenciosamente,</strong></p>
		<p style="margin: 0; padding-bottom: 0"><strong>Show Tecnologia</strong></p>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="kmDividerBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmDividerBlockOuter">
		<tr>
		<td class="kmDividerBlockInner" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:18px;padding-bottom:18px;padding-left:18px;padding-right:18px;">
		<table class="kmDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; border-top-width:1px;border-top-style:solid;border-top-color:#ccc;">
		<tbody>
		<tr><td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0"><span></span></td></tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="kmTextBlock" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody class="kmTextBlockOuter">
		<tr>
		<td class="kmTextBlockInner" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:20px;padding-bottom:20px;">
		<table align="left" border="0" cellpadding="0" cellspacing="0" class="kmTextContentContainer" width="100%" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0">
		<tbody>
		<tr>
		<td class="kmTextContent" valign="top" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;">
		<p style="margin: 0; padding-bottom: 1em; text-align: center;"><span style="font-size: 9px;"><b>Este é um email automático, por favor não responda. Caso tenha alguma dúvida sobre o conteúdo deste email entre em contato através do email suporte@showtecnologia.com ou pelo telefone 83 3271.4060</b></span></p>
		<p style="margin: 0; padding-bottom: 0"> </p>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</center>
		</body>
		</html>';

		return $mensagem;
	}
	
	/**
	 * Template de email para abertura de ticker do suporte
	 * @param array dados
	 * @return string
	 */
	public function email_abertura_ticker_suporte($cliente_usuario = null, $veiculos = null)
	{
		$template = $this->load->view('template/emails/abertura_ticket_suporte', null, true);

		$template = str_replace('{{cliente}}', $cliente_usuario->nome, $template);
		$template = str_replace('{{usuario}}', $cliente_usuario->usuario, $template);
		$template = str_replace('{{ticket}}', $cliente_usuario->ticket, $template);
		$mensagem = str_replace('{{link}}', 'https://gestor.showtecnologia.com/gestor/index.php/webdesk/ticket/' . $cliente_usuario->ticket, $template);

		$lista = '';

		foreach ($veiculos as $veiculo) {
			$lista .= '<tr>
				<td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px; border-color: #cccccc;">'.$veiculo->placa.'</td>
				<td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px; border-color: #cccccc;">'.$veiculo->serial.'</td>
				<td style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 5px; border-color: #cccccc;">'.dh_for_humans($veiculo->ultima_atualizacao).'</td>
			</tr>';
		}

		$mensagem = str_replace('{{lista}}', $lista, $mensagem);

		return $mensagem;
	}

	/**
	 * Template de email para abertura de ticket pelo sistema interno
	 * @param array dados
	 * @return string
	 */
	public function email_abertura_ticket_interno($ticket_id, $nome_cliente, $nome_usuario, $assunto, $msg)
	{
		$template = $this->load->view('template/emails/abertura_ticket_interno', null, true);

		$template = str_replace('{{cliente}}', $nome_cliente, $template);
		$template = str_replace('{{usuario}}', $nome_usuario, $template);
		$template = str_replace('{{ticket}}', $ticket_id, $template);
		$template = str_replace('{{assunto}}', $assunto, $template);
		$template = str_replace('{{mensagem}}', $msg, $template);
		$mensagem = str_replace('{{link}}', 'https://gestor.showtecnologia.com/gestor/index.php/webdesk/ticket/' . $ticket_id, $template);

		return $mensagem;
	}
	
}
