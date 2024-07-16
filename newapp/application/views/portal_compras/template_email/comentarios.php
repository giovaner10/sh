
<?php include_once ('application/views/portal_compras/template_email/top.php'); ?>

<table cellpadding="0" cellspacing="0" class="es-content" align="center"
	style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
	<tr>
		<td align="center" style="padding:0;Margin:0">
			<table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0"
				style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
				<tr>
					<td align="left"
						style="Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;padding-bottom:30px">
						<table cellpadding="0" cellspacing="0" width="100%"
							style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
							<tr>
								<td align="center" valign="top" style="padding:0;Margin:0;width:560px">
									<table cellpadding="0" cellspacing="0" width="100%" role="presentation"
										style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
										<tr>
											<td align="center" class="es-m-txt-c" style="padding:0;Margin:0;padding-bottom:10px">
												<h1
													style="Margin:0;line-height:26px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:26px;font-style:normal;font-weight:bold;color:#333333">
													COMENTÁRIOS DA SOLICITAÇÃO DE COMPRA
												</h1>
											</td>
										</tr>
										<tr>
											<td align="center" class="es-m-p0r es-m-p0l"
												style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:40px;padding-right:40px">
												<h2>
													Olá, a solicitação #<?= $idSolicitacao ?> Recebeu um novo comentário.
												</h2>
												<p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
													<!-- tabela com dados da solicitacao -->
												<table align="center" cellpadding="0" cellspacing="0" border="2" style="background-color:#ffffff; border:0; margin:0 auto; padding:10px; width:960px">
													<tbody>
														<tr>
															<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Nome</th>
															<td style="border: 1px solid #ddd; text-align:left; padding: 8px;"><?= !empty($nome) ? $nome : '' ?></td>
														</tr>
														<tr>
															<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">E-mail</th>
															<td style="border: 1px solid #ddd; text-align:left; padding: 8px;"><?= !empty($email) ? $email : '' ?></td>
														</tr>
														<tr>
															<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Comentário</th>
															<td style="border: 1px solid #ddd; text-align:left; padding: 8px;"><?= !empty($mensagem) ? $mensagem : '' ?></td>
														</tr>
														<tr>
															<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Data e Hora</th>
															<td style="border: 1px solid #ddd; text-align:left; padding: 8px;"><?= !empty($dataHoraCadastro) ? $dataHoraCadastro : '' ?></td>
														</tr>
															
														</tbody>
													</table>
												<br>
												<br>
												<br>
												Para visualizar a solicitação, clique no botão abaixo:
											</td>
										</tr>
										<tr>
											<td align="center" style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px">
												<span
													class="es-button-border"
													style="border-style:solid;border-color:#2CB543;background:#5C68E2;border-width:0px;display:inline-block;border-radius:6px;width:auto">
													<a
														href="<?= site_url() ?>/PortalCompras/Solicitacoes/visualizar/<?= $idSolicitacao ?>" class="es-button" target="_blank"
														style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:20px;border-style:solid;border-color:#5C68E2;border-width:10px 30px 10px 30px;display:inline-block;background:#5C68E2;border-radius:6px;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:24px;width:auto;text-align:center;border-left-width:30px;border-right-width:30px">
														Acessar Solicitação
													</a>
												</span>
											</td>
										</tr>
										<tr>
											<td align="center" class="es-m-p0r es-m-p0l"
												style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:40px;padding-right:40px">
												<p
													style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
														Caso não consiga acessar através do botão, por favor utilize o link abaixo:<br>
														<a
															target="_blank" href="<?= site_url() ?>/PortalCompras/Solicitacoes/visualizar/<?= $idSolicitacao ?>"
															style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#5C68E2;font-size:14px">
														<?= site_url() ?>/PortalCompras/Solicitacoes/visualizar/<?= $idSolicitacao ?>
													</a>
												</p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php include_once ('application/views/portal_compras/template_email/bottom.php'); ?>