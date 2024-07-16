<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Envio_fatura extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->model('sender');
	}

	public function inserir($dados) {
		$this->db->insert_batch('envio_fatura', $dados);
		if($this->db->affected_rows() > 0)
			return $this->db->insert_id();
		return false;
	}

	public function listar($where = array(), $inicio = 0, $limite = 999999, $order_campo = 'envio.id_envio', $order = 'DESC') {
		$retornos = array();
		$query = $this->db->select('cli.nome, cli.email2, envio.*')
				->join('cad_faturas as fat', 'fat.numero = envio.id_fatura')
				->join('cad_clientes as cli', 'cli.id = fat.id_cliente')
				->where($where)
				->order_by($order_campo, $order)
				->get('envio_fatura as envio', $limite, $inicio);
		if($query->num_rows() > 0)
			$retornos = $query->result();
		return $retornos;
	}
	
	public function atualizar($id_envio, $dados) {
		$atualizou = false;
		$this->db->update('envio_fatura', $dados, array('id_envio' => $id_envio));
		if ($this->db->affected_rows() > 0)
			$atualizou = true;
		return $atualizou;
	}
	
	public function listar_relatorio($where = array(), $inicio = 0, $limite = 300) {
		$envio = array();
		$query = $this->db->select('envio.*, cli.nome')
					  	  ->join('cad_faturas as fat', 'fat.numero = envio.id_fatura')
					  	  ->join('cad_clientes as cli', 'cli.id = fat.id_cliente')
					  	  ->order_by('id_envio', 'DESC')
					  	  ->get('envio_fatura as envio', $limite, $inicio);
		
		if ($query->num_rows() > 0)
			$envio = $query->result();
		return $envio;	
	}

	public function envia_fatura($id_fatura, $id_cliente) {
		// CODIFICA ID P/ ENVIO PARA VIEW
		$fatura_code = base64_encode($id_fatura);
		
		// BUSCA DADOS DO CLIENTE
		$emails = $this->cliente->get_clientes_emails($id_cliente);
		$envio = '';
		
		if ($emails) {
			foreach ($emails as $email) {
				$msg = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
				<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
					<meta content='text/html; charset=utf-8' http-equiv='Content-Type' />
					<style type='text/css'>
						@media only screen and (max-width: 480px) {
							body, table, td, p, a, li, blockquote { -webkit-text-size-adjust: none !important; }
							body{ width: 100% !important; min-width: 100% !important; }
							td[id=bodyCell] { padding: 10px !important; }
							table[class=kmTextContentContainer] { width: 100% !important; }
							table[class=kmBoxedTextContentContainer] { width: 100% !important; }
							td[class=kmImageContent] { padding-left: 0 !important; padding-right: 0 !important; }
							img[class=kmImage] { width:100% !important; }
							table[class=kmSplitContentLeftContentContainer],
							table[class=kmSplitContentRightContentContainer],
							table[class=kmColumnContainer] { width:100% !important; }
							table[class=kmSplitContentLeftContentContainer] td[class=kmTextContent],
							table[class=kmSplitContentRightContentContainer] td[class=kmTextContent],
							table[class='kmColumnContainer'] td[class=kmTextContent] { padding-top:9px !important; }
							td[class='rowContainer kmFloatLeft'],
							td[class='rowContainer kmFloatLeft firstColumn'],
							td[class='rowContainer kmFloatLeft lastColumn'] { float:left; clear: both; width: 100% !important; }
							table[id=templateContainer],
							table[class=templateRow],
							table[id=templateHeader],
							table[id=templateBody],
							table[id=templateFooter] { max-width:600px !important; width:100% !important; }
							h1 { font-size:24px !important; line-height:100% !important; }
							h2 { font-size:20px !important; line-height:100% !important; }
							h3 { font-size:18px !important; line-height:100% !important; }
							h4 { font-size:16px !important; line-height:100% !important; }
							td[class=rowContainer] td[class=kmTextContent] { font-size:18px !important; line-height:100% !important; padding-right:18px !important; padding-left:18px !important; }
							td[class=headerContainer] td[class=kmTextContent] { font-size:18px !important; line-height:100% !important; padding-right:18px !important; padding-left:18px !important; }
							td[class=bodyContainer] td[class=kmTextContent] { font-size:18px !important; line-height:100% !important; padding-right:18px !important; padding-left:18px !important; }
							td[class=footerContent] { font-size:18px !important; line-height:100% !important; }
							td[class=footerContent] a { display:block !important; }
						}
					</style>
				</head>
				<body style='margin: 0; padding: 0; background-color: #c7c7c7'>
				<center>
					<table align='center' border='0' cellpadding='0' cellspacing='0' id='bodyTable' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0; background-color: #c7c7c7; height: 100%; margin: 0; width: 100%'>
						<tbody>
						<tr>
							<td align='center' id='bodyCell' valign='top' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top: 50px; padding-left: 20px; padding-bottom: 20px; padding-right: 20px; border-top: 0; height: 100%; margin: 0; width: 100%'>
								<table border='0' cellpadding='0' cellspacing='0' id='templateContainer' width='600' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; border: 1px solid #aaa; background-color: #f4f4f4'>
									<tbody>
									<tr>
										<td align='center' valign='top' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
											<table border='0' cellpadding='0' cellspacing='0' class='templateRow' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
												<tbody>
												<tr>
													<td class='rowContainer kmFloatLeft' valign='top' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
														<table border='0' cellpadding='0' cellspacing='0' class='kmImageBlock' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
															<tbody class='kmImageBlockOuter'>
															<tr>
																<td class='kmImageBlockInner' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding:9px;' valign='top'>
																	<table align='left' border='0' cellpadding='0' cellspacing='0' class='kmImageContentContainer' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
																		<tbody>
																		<tr>
																			<td class='kmImageContent' valign='top' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding: 0; padding-top:0px;padding-bottom:0;padding-left:9px;padding-right:9px;text-align: center;'>
																				<img align='center' alt='Show Tecnologia' class='kmImage' src='https://d3k81ch9hvuctc.cloudfront.net/company%2F9TqzGS%2Fimages%2Flogo-show.png' width='510' height='185' style='border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; max-width: 100%; padding-bottom: 0; display: inline; vertical-align: bottom' />
																			</td>
																		</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
															</tbody>
														</table>

														<table border='0' cellpadding='0' cellspacing='0' width='100%' class='kmDividerBlock' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
															<tbody class='kmDividerBlockOuter'>
															<tr>
																<td class='kmDividerBlockInner' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:18px;padding-bottom:18px;padding-left:18px;padding-right:18px;'>
																	<table class='kmDividerContent' border='0' cellpadding='0' cellspacing='0' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; border-top-width:1px;border-top-style:solid;border-top-color:#ccc;'>
																		<tbody>
																		<tr>
																			<td style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'><span></span></td>
																		</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
															</tbody>
														</table>

														<table border='0' cellpadding='0' cellspacing='0' class='kmTextBlock' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
															<tbody class='kmTextBlockOuter'>
															<tr>
																<td class='kmTextBlockInner' valign='top' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0;'>
														<table align='left' border='0' cellpadding='0' cellspacing='0' class='kmTextContentContainer' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
															<tbody>
																<tr>
								<td class='kmTextContent' valign='top' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: center; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;'>
									<p style='margin: 0; padding-bottom: 0'><a target='_blank' href='https://gestor.showtecnologia.com/sistema/newapp/index.php/cliente/faturas/view_email/$fatura_code' style='background: #213f9a; display: inline-block; color: #ffffff; padding: 10px 20px; font-size: 16px; font-weight: bold; text-decoration: none; border-radius: 5px;' onmouseover='this.style.background='#333333' onmouseout='this.style.background='#213f9a'>Download</a></p>
								</td>
							</tr>
							</tbody>
						</table>
																</td>
															</tr>
															</tbody>
														</table>
														<table border='0' cellpadding='0' cellspacing='0' class='kmTextBlock' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
															<tbody class='kmTextBlockOuter'>
															<tr>
																<td class='kmTextBlockInner' valign='top' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:20px;padding-bottom:20px;'>
																	<table align='left' border='0' cellpadding='0' cellspacing='0' class='kmTextContentContainer' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
																		<tbody>
																		<tr>
																			<td class='kmTextContent' valign='top' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;'>
																				<p style='margin: 0; padding-bottom: 1em'><strong>Atenciosamente,</strong></p>
																				<p style='margin: 0; padding-bottom: 0'><strong>Show Tecnologia</strong></p>
																			</td>
																		</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
															</tbody>
														</table>
														<table border='0' cellpadding='0' cellspacing='0' class='kmTextBlock' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
															<tbody class='kmTextBlockOuter'>
															<tr>
																<td class='kmTextBlockInner' valign='top' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; padding-top:20px;padding-bottom:20px;'>
																	<hr style='border: none; display: block; margin: 0 20px; height: 1px; background-color: #cccccc; margin-bottom: 20px;' />
																	<table align='left' border='0' cellpadding='0' cellspacing='0' class='kmTextContentContainer' width='100%' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0'>
																		<tbody>
																		<tr>
																			<td class='kmTextContent' valign='top' style='border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; color: #505050; font-family: Helvetica; font-size: 14px; line-height: 150%; text-align: left; padding-top:9px;padding-bottom:9px;padding-left:18px;padding-right:18px;'>
																				<p style='margin: 0; padding-bottom: 1em; text-align: center;'><span style='font-size: 9px;'><b>Este é um email automático, por favor não responda. Caso tenha alguma dúvida sobre o conteúdo deste email entre em contato através do email suporte@showtecnologia.com ou pelo telefone 83 3271.4060</b></span></p>
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
				</html>";
			    
			    $envio = $this->sender->enviar_email('suporte@showtecnologia.com', 'Show Tecnologia ', strtolower($email->email), 'Fatura #'.$id_fatura, $msg, '', false, false);
			}
		}	    
	    return $envio;
	}
}