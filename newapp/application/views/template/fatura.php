<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Fatura SHOW TECNOLOGIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url('assets/css')?>/estilosboleto.css" rel="stylesheet" type="text/css" />
</head>

<body>
	{faturas_conteudo}
    <div align="center" >
    <table class="tab" align="center">
      <tr>
        <td width="31%"><img src="<?php echo base_url('assets/media')?>/logo-show.png" class="logo" ></td>
        <td width="43%" valign="top" class="endshow"> SHOW PRESTADORA DE SERVI&Ccedil;OS
          DO BRASIL LTDA<br>
          Av. Ruy Barbosa, 104 - Centro<br>
          Guarabira - PB - CEP: 58.200-000<br>
          CNPJ: 09.338.999/0001-58<br>
          www.showtecnologia.com<br>
          Fone (83) 3271-6559
        </td>
        <td width="26%" align="right" valign="top"><div class="fatura" align="center"><strong>FATURA</strong></div>
		  
		  Numero:  {Id}<br>
                  Pago Em: {data_pgto}<br>
                  Emissão: {data_emissao}
                  <br>
                  Vencimento: {data_vencimento}
                  
          </td>
      </tr>
    </table>

        <table class="tab" align="center">
       <tr>
           <td colspan="4" class="fatura"><strong>Dados do Cliente</strong></td>
       </tr>
       <tr>
            <td  align="right">Nome:</td>
            <td  colspan="3" class="dcli">{nome}</td>
       </tr>
       <tr>
            <td align="right">Endereço:</td>
            <td colspan="3" class="dcli">{endereco} Nro.: {numero_cliente} - {complemento}</td>
       </tr>
       <tr>
            <td align="right">Bairro:</td>
            <td width="48%" class="dcli">{bairro}</td>
            <td width="10%" align="right">CNPJ:</td>
            <td width="30%" class="dcli">{cnpj}</td>
       </tr>
       <tr>
            <td align="right">Cidade:</td>
            <td class="dcli">{cidade} - {uf}</td>
            <td align="right">CEP:</td>
            <td class="dcli">{cep}</td>
        </tr>
        <tr>
            <td align="right">Fone:</td>
            <td class="dcli">{fone}</td>
            <td align="right">Contato:</td>
            <td class="dcli">{contato}</td>
        </tr>
        <tr>
            <td align="right">Email:</td>
            <td colspan="3" class="dcli">{email}</td>
        </tr>
    </table>


    <table class="tab" align="center" >
        <tr class="dcli">
            <td colspan="6" class="fatura"><strong>Produtos ou Servi&ccedil;os</strong></td>
        </tr>
        <tr>
            <td  class="prod">Descri&ccedil;&atilde;o</td>
            <td width="60" class="prod" align="center">Valor</td>
        </tr>
        {faturas_itens}    
        <tr class="prods">
            <td align="left">
                {descricao_item}
                
            </td>
            <td align="right" valign="top">{valor_item}</td>
        </tr>
        {/faturas_itens}
  

        <tr>
            <td>&nbsp;</td>
        </tr>        
    </table>
        
    <table class="tab" align="center">
        
        <tr>
            <td colspan="6">Autentica&ccedil;&atilde;o Mec&acirc;nica - Recibo do Sacado</td>
        </tr>
            
        <tr>
            <td rowspan="3" colspan="6" valign="top" align="justify" class="inform"><br>ISS isento conforme LEI COMPLEMENTAR N&ordm; 116, DE 31 DE JULHO DE 2003, A loca&ccedil;&atilde;o de bens im&oacute;veis ou m&oacute;veis n&atilde;o constitui uma presta&ccedil;&atilde;o de servi&ccedil;os, mas disponibiliza&ccedil;&atilde;o de um bem, seja ele im&oacute;vel ou m&oacute;vel para utiliza&ccedil;&atilde;o do locat&aacute;rio sem a presta&ccedil;&atilde;o de um servi&ccedil;o.</td>
            <td class="total" align="center" width="110">TOTAL R$</td>
            <td class="total" align="right" width="70">{sub_total}&nbsp;</td>
        </tr>
        <tr>
            <td class="total" align="center" width="110">TAXA BOLETO R$</td>
            <td class="total" align="right" width="70">{taxa_boleto}</td>
        </tr>
        <tr>
            <td class="total" align="center" width="110">TOTAL<br>&Agrave; PAGAR R$</td>
            <td class="total" align="right" valign="middle" width="70">{valor_total}</td>
        </tr>
    
    </table>
    <!-- INSERIR CÓDIGO BOLETO AQUI -->
	<?php
    $this->load->helper('My_boleto_bb');
    boleto_bb($data);
    ?>

</div>
{/faturas_conteudo}
</body>
</html>
