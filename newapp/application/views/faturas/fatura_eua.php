<html dir="ltr">
	<head>
		<title>Invoice</title>
		<link href="https://www.paypalobjects.com/web/res/534/9f573d95c361e9852b098522f29c7/css/app.ltr.css" media="all" rel="stylesheet" type="text/css">
	</head>
	<body id="page">
		<?php setlocale(LC_MONETARY, 'en_US');?>
		<script>
			Number.prototype.formatMoney = function(c, d, t){
				var n = this, 
				c = isNaN(c = Math.abs(c)) ? 2 : c, 
				d = d == undefined ? "." : d, 
				t = t == undefined ? "," : t, 
				s = n < 0 ? "-" : "", 
				i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
				j = (j = i.length) > 3 ? j % 3 : 0;
				return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
			};
			var valores = [];
			var total_soma = 0;
			var tax = <?=$tax+1?>;
			var tax2 = <?=$tax?>;
		</script>
		<div role="content" id="content" class="containerCentered invoice" tabindex="-1">
			<section>
				<div class="sectionPrint invoiceDetails">
					<form name="invoiceDetailsForm" id="invoiceDetailsForm">
						<div class="row invoiceInfoPrint">
							<div style="display: none" id="isPayer"></div>
							<div class="col-xs-12">
								<div class="row" style="padding-right: 0px;margin-top: 0px;">
									<div class="col-xs-5" id="printPreview">
										<div class="businessLogo"><img border="0" id="logoUrl" alt="" style="width:250px" src="<?=base_url()?>media/img/Show-logo-us.png"></div>
										<div>
											<div class="headline" style="
												">SHOW SERVICE PROVIDER USA LLC</div>
											<div>
												<b>Show Technology</b>
												<p style="padding-top: 5px;">Universaty dr. Suite 105 Coral Springs, Florida, 33065</p>
												<p>1800-852-4032</p>
												<p>contact@myshowtec.com</p>
											</div>
										</div>
									</div>
									<div class="col-xs-5 col-xs-offset-2">
										<div class="row">
											<div class="col-xs-5"></div>
											<div class="col-xs-7" style="text-align: left; padding-left: 3px;">
												<div class="pageCurl">INVOICE</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-5"></div>
											<div class="col-xs-7" style="text-align: left; padding-left: 3px;padding-right: 0px;">
												<!--  -->
												<div class="row" style="padding-right: 0px;">
												</div>
											</div>
										</div>
										<div class="row invsummary">
											<div class="col-xs-5" style="text-align: right;padding-right: 10px;background-color: #c9f1fb !important;">Invoice:</div>
											<div class="col-xs-7" style="padding-left: 3px;background-color: #f5f5f5 !important;" id="invoiceNumber">#<?=$fatura->Id?></div>
										</div>
										<div class="row invsummary">
											<div class="col-xs-5" style="text-align: right;padding-right: 10px;background-color: #c9f1fb !important;">Invoice date:</div>
											<div class="col-xs-7" style="padding-left: 3px;background-color: #f5f5f5 !important;" id="invoiceDate"><?=data_for_us(data_for_humans($fatura->data_emissao))?></div>
										</div>
										<div class="row invsummary">
											<div class="col-xs-5" style="text-align: right;padding-right: 10px;background-color: #c9f1fb !important;">Due date:</div>
											<div class="col-xs-7" style="padding-left: 3px;background-color: #f5f5f5 !important;" id="invoiceDueDate"><?=data_for_us(data_for_humans($fatura->data_vencimento))?></div>
										</div>
										<div class="row invsummary">
											<div class="col-xs-5" style="text-align: right; padding-right: 10px;"></div>
											<div class="col-xs-7" style="padding-left: 3px;">
												<div class="paySummary text-center">
													<div>Amount due:</div>
													<div class="amount"><strong>$ <span id="total"></span><?php $soma=0;foreach($itens as $item){$soma+=$item->valor_item;}?></strong></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="sectionBottom"></div>
								<div class="row">
								</div>
								<div class="row" id="itemDetails">
									<!-- section header start-->
									<table style="width:100%;table-layout:fixed;">
										<thead class="itemdetailsheader">
											<tr style="
												background-color: #c9f1fb !important;
												">
												<th class="itemdescription">
													<div clascalcus="wrap">Description</div>
												</th>
												<!-- empty placeholder header for the discount -->
												<th class="itemprice text-right"></th>
												<th class="itemamount text-right">Amount</th>
											</tr>
										</thead>
										<tbody class="itemdetailsbody">
											<?php foreach ($itens as $key => $item) : ?>
												<tr>
													<td class="itemdescription">
														<div class="wrap"> <?=$item->descricao_item?></div>
													</td>
													<td class="itemprice text-right"></td>
													<td class="itemamount text-right">$ <span id="item<?=$key?>"></span></td>
												</tr>
												<script>
													valores.push(<?=$item->valor_item?>);
												</script>
											<?php endforeach; ?>
											<tr>
												<td class="itemdescription">
													<div class="wrap"> Tax of <?=$tax*100?>%</div>
												</td>
												<td class="itemprice text-right"></td>
												<td class="itemamount text-right">$ <span id="itemTax"></span></td>
											</tr>
										</tbody>
									</table>
									<!-- section header end-->
								</div>
								<div class="row" id="invoiceTotals">
									<table style="width:100%;">
										<tbody>
											<tr>
												<td>&nbsp;</td>
												<td class="text-right" style="width:25%;">Subtotal</td>
												<td class="text-right" style="width:15%;">$ <span id="total1"></span></td>
											</tr>
											<tr class="invoiceTotal" style="border-bottom: 0px;background-color: #c9f1fb !important;">
												<td></td>
												<td class="text-right" style="
													background-color: #c9f1fb !important;
													">Total</td>
												<td class="text-right" style="
													background-color: #c9f1fb !important;
													">$ <span id="total2"></span>&nbsp;USD</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div style="margin-top: 20px;"></div>
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
		<script>
			total_soma = <?=$soma?>;
			document.getElementById("total").innerHTML = (total_soma*tax).formatMoney(2, '.', ',');
			document.getElementById("total1").innerHTML = (total_soma*tax).formatMoney(2, '.', ',');
			document.getElementById("total2").innerHTML = (total_soma*tax).formatMoney(2, '.', ',');
			document.getElementById("itemTax").innerHTML = (total_soma*(tax2)).formatMoney(2, '.', ',');
			valores.forEach(function f(data,index){document.getElementById("item"+index).innerHTML = (data).formatMoney(2, '.', ',');});
		</script>
	</body>
</html>