<div id="container">
		<table align="center" class="header" border=0 cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
                    <td width=150><img SRC="../../includes/Financeiro/imagens/logobb.jpg" width="" ></td>
			<td width=50>
                            <div class="field_cod_banco">
                                <?php echo $dadosboleto["codigo_banco_com_dv"]?>
                            </div>
			</td>
			<td class="linha_digitavel"><?php echo $dadosboleto["linha_digitavel"]?></td>
		</tr>
		</tbody>
		</table>

		<table align="center" class="line" cellspacing="0" cellpadding="0">
		<tbody>
		<tr class="titulos">
			<td class="local_pagto">Local de pagamento</td>
			<td class="vencimento2">Vencimento</td>
		</tr>
		<tr class="campos">
			<td class="local_pagto">QUALQUER BANCO AT&Eacute; O VENCIMENTO</td>
			<td class="vencimento2"><?php echo $dadosboleto["data_vencimento"]?></td>
		</tr>
		</tbody>
		</table>
		
		<table align="center" class="line" cellspacing="0" cellpadding="0">
		<tbody>
		<tr class="titulos">
			<td class="cedente2">Cedente</td>
			<td class="ag_cod_cedente2">Ag&ecirc;ncia/C&oacute;digo cedente</td>
		</tr>
		<tr class="campos">
			<td class="cedente2"><?php echo $dadosboleto["cedente"]?></td>
			<td class="ag_cod_cedente2"><?php echo $dadosboleto["agencia_codigo"]?></td>
		</tr>
		</tbody>
		</table>

		<table align="center" class="line" cellspacing="0" cellpadding="0">
		<tbody>
		<tr class="titulos">
			<td class="data_doc">Data do documento</td>
			<td class="num_doc2">No. documento</td>
			<td class="especie_doc">Esp&eacute;cie doc.</td>
			<td class="aceite">Aceite</td>
			<td class="data_process">Data process.</td>
			<td class="nosso_numero2">Nosso n&uacute;mero</td>
		</tr>
		<tr class="campos">
			<td class="data_doc"><?php echo $dadosboleto["data_documento"]?></td>
			<td class="num_doc2"><?php echo $dadosboleto["numero_documento"]?></td>
			<td class="especie_doc"><?php echo $dadosboleto["especie_doc"]?></td>
			<td class="aceite"><?php echo $dadosboleto["aceite"]?></td>
			<td class="data_process"><?php echo $dadosboleto["data_processamento"]?></td>
			<td class="nosso_numero2"><?php echo $dadosboleto["nosso_numero"]?></td>
		</tr>
		</tbody>
		</table>

		<table align="center" class="line" cellspacing="0" cellPadding="0">
		<tbody>
		<tr class="titulos">
			<td class="reservado">Uso do  banco</td>
			<td class="carteira">Carteira</td>
                        <td class="especie2">Esp&eacute;cie</td>
			<td class="qtd2">Quantidade</td>
			<td class="xvalor">x Valor</td>
			<td class="valor_doc2">(=) Valor documento</td>
		</tr>
		<tr class="campos">
			<td class="reservado">&nbsp;</td>
			<td class="carteira"><?php echo $dadosboleto["carteira"]?> <?php echo isset($dadosboleto["variacao_carteira"]) ? $dadosboleto["variacao_carteira"] : '&nbsp;' ?></td>
			<td class="especie2"><?php echo $dadosboleto["especie"]?></td>
			<td class="qtd2"><?php echo $dadosboleto["quantidade"]?></td>
			<td class="xvalor"><?php echo $dadosboleto["valor_unitario"]?></td>
			<td class="valor_doc2"><?php echo $dadosboleto["Mvalor_boleto"];?></td>
		</tr>
		</tbody>
		</table>
		
		
		<table align="center" class="line" cellspacing="0" cellpadding="0">
		<tbody>
		<tr><td class="last_line" rowspan="6">
			<table class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="instrucoes">
						Instru&ccedil;&otilde;es:
				</td>
			</tr>
			<tr class="campos">
				<td class="instrucoes" rowspan="5">
					<p><?php echo $dadosboleto["demonstrativo1"]; ?></p>		
					<p><?php echo $dadosboleto["demonstrativo2"]; ?></p>
					<p><?php echo $dadosboleto["demonstrativo3"]; ?></p>
					<p><?php echo $dadosboleto["instrucoes1"]; ?></p>
					<p><?php echo $dadosboleto["instrucoes2"]; ?></p>
					<p><?php echo $dadosboleto["instrucoes3"]; ?></p>
					<p><?php echo $dadosboleto["instrucoes4"]; ?></p>
				</td>
			</tr>
			</tbody>
			</table>
		</td></tr>
		
		<tr><td>
			<table align="center" class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="desconto2">(-) Desconto / Abatimento</td>
			</tr>
			<tr class="campos">
				<td class="desconto2">&nbsp;</td>
			</tr>
			</tbody>
			</table>
		</td></tr>
		
		<tr><td>
			<table align="center" class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="outras_deducoes2">(-) Outras dedu&ccedil;&otilde;es</td>
			</tr>
			<tr class="campos">
				<td class="outras_deducoes2">&nbsp;</td>
			</tr>
			</tbody>
			</table>
		</td></tr>

		<tr><td>
			<table align="center" class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="mora_multa2">(+) Mora / Multa</td>
			</tr>
			<tr class="campos">
				<td class="mora_multa2">&nbsp;</td>
			</tr>
			</tbody>
			</table>
		</td></tr>

		<tr><td>
			<table align="center" class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="outros_acrescimos2">(+) Outros Acr&eacute;scimos</td>
			</tr>
			<tr class="campos">
				<td class="outros_acrescimos2">&nbsp;</td>
			</tr>
			</tbody>
			</table>
		</td></tr>

		<tr><td class="last_line">
			<table class="line" cellspacing="0" cellpadding="0">
			<tbody>
			<tr class="titulos">
				<td class="valor_cobrado2">(=) Valor cobrado</td>
			</tr>
			<tr class="campos">
				<td class="valor_cobrado2">&nbsp;</td>
			</tr>
			</tbody>
			</table>
		</td></tr>
		</tbody>
		</table>
		
		
    <table align="center" class="line" cellspacing="0" cellPadding="0" >
		<tbody>
		<tr class="titulos">
			<td class="sacado2">Sacado</td>
		</tr>
		<tr class="campos">
			<td class="sacado2">
				<p><?php echo $dadosboleto["sacado"]?></p>
				<!--<p><?php echo $dadosboleto["endereco1"]?></p>-->
				<!--<p><?php echo $dadosboleto["endereco2"]?></p>-->
			</td>
		</tr>
		</tbody>
		</table>		
<!--		
		<table class="line" cellspacing="0" cellpadding="0">
		<tbody>
		<tr class="titulos">
			<td class="sacador_avalista" colspan="2">Sacador/Avalista</td>
		</tr>
		<tr class="campos">
			<td class="sacador_avalista">&nbsp;</td>
			<td class="cod_baixa">C&oacute;d. baixa</td>
		</tr>
		</tbody>
		</table>		
-->
    <table align="center" cellspacing=0 cellpadding=0 width=666 border=0>
        <TBODY>
            <TR>
                <TD width=666 align=right >
                    <font style="font-size: 10px;">
                        Autentica&ccedil;&atilde;o mec&acirc;nica - Ficha de Compensa&ccedil;&atilde;o
                    </font>
                </TD>
            </tr>
        </tbody>
</table>
    
		<div class="barcode">
			<?php fbarcode($dadosboleto["codigo_barras"]); ?>
		</div>
    <!--
		<div class="cut">
			<p>Corte na linha pontilhada</p>
		</div>
    -->
	</div>


