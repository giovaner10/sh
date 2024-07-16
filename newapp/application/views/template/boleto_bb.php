<img src="<?php echo base_url('assets/media')?>/tesourinha.png" height="10" width="20">-------------------------------------------------------------------------------------------------------------------------------------------
    <?php //include 'boleto_bb.php';?>
    <table class="tab" align="center">
    <tr><td>
    <table class="header" align="center">
        <tr>
            <td ><img SRC="<?php echo base_url('assets/media')?>/logobb.jpg"></td>
            <td >
                <div class="field_cod_banco">
                    codigo banco com dv
                </div>
            </td>
            <td class="linha_digitavel">linha digitavel</td>
        </tr>
    </table>
    <table align="center" class="line" cellspacing="0" cellpadding="0">
	<tbody>
	<tr class="titulos">
            <td class="local_pagto">Local de pagamento</td>
            <td class="vencimento2">Vencimento</td>
	</tr>
	<tr class="campos">
            <td class="local_pagto">QUALQUER BANCO AT&Eacute; O VENCIMENTO</td>
            <td class="vencimento2">vencimento boleto</td>
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
        <td class="cedente2">cedente</td>
	<td class="ag_cod_cedente2">agencia código</td>
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
            <td class="data_doc">data documento</td>
            <td class="num_doc2">numero documento</td>
            <td class="especie_doc">especie doc</td>
            <td class="aceite">aceite</td>
            <td class="data_process">data processamento</td>
            <td class="nosso_numero2">nosso numero</td>
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
		<td class="carteira">carteira variação carteira </td>
		<td class="especie2">especie</td>
		<td class="qtd2">quantidade</td>
		<td class="xvalor">valor unitario</td>
		<td class="valor_doc2">m valor boleto</td>
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
					<p>demosntrativo 1</p>		
					<p>demonstrativo 2</p>
					<p>demonstrativo 3</p>
					<p>instru 1</p>
					<p>instru 2</p>
					<p>instru 3</p>
					<p>instru 4</p>
				</td>
			</tr>
			</tbody>
			</table>
		</td>
		
		<td>
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
				<p>sacado</p>
				cnpj
				cidade estado
			</td>
		</tr>
		</tbody>
		</table>		
    <tr>
        <td align="right">
            Autentica&ccedil;&atilde;o mec&acirc;nica - Ficha de Compensa&ccedil;&atilde;o&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td>
            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;codigo de barras
        </td>
    </tr>
            

            
    </td></tr>
    </table>