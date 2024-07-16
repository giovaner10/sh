<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style>
    .tab {
        border-top-width: 1px;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-left-width: 1px;
        border-top-style: solid;
        border-right-style: solid;
        border-bottom-style: solid;
        border-left-style: solid;
        font-size: 12px;
        width:780px
    }

    .endshow {
        font-family: "Times New Roman", Times, serif;
        font-size: 12px;
    }        

    .fatura {
        background-color: #CCC;
        font-size: 12px;
    }
    .dcli {
        border-bottom-style: dotted;
        border-bottom-width: 1px;
        border-bottom-color: #000;
        height: 0;
        font-size: 12px;
    }        
    .prod {
        border-top-width: 1px;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-left-width: 1px;
        border-top-style: groove;
        border-right-style: solid;
        border-bottom-style: solid;
        border-left-style: solid;
        height: 1;
    }
    .inform {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
        border-top-width: 1px;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-left-width: 1px;
        border-top-style: solid;
        border-right-style: solid;
        border-bottom-style: solid;
        border-left-style: solid;
    }
    .total {
        border: 2px solid #000;
        width: 100px;
    }
</style>

<body>
    <div align="center" >
        <table class="tab" align="center">
            <tr>
                <td width="250"><img width="250" src="<?php echo base_url('media') ?>/img/_logo-showtecnologia.png" ></td>
                <td width="350" valign="top" align="left" class="endshow"> SHOW PRESTADORA DE SERVI&Ccedil;OS
                    DO BRASIL LTDA<br>
                    Av. Ruy Barbosa, 104 - Centro<br>
                    Guarabira - PB - CEP: 58.200-000<br>
                    CNPJ: 09.338.999/0001-58<br>
                    www.showtecnologia.com<br>
                    Fone (83) 3271-6559
                </td>
                <td align="right" valign="top">
                    <div class="fatura" align="center"><strong>FATURA</strong></div>
                    Numero:  <?php echo $data['numero_documento']; ?><br>
                    <?php if (!empty($row_lista['data_pagto'])) { ?>
                        Pago Em: <?php ?><br>
                        <?php
                    } else {
                        echo '';
                    }
                    ?>
                    Emiss&atilde;o: <?php echo $data['data_documento']; ?><br>
                    Vencimento: <?php echo $data['data_vencimento']; ?>
                </td>
            </tr>
        </table>

        <table class="tab" align="center">
            <tr>
                <td colspan="4" class="fatura"><strong>Dados do Cliente</strong></td>
            </tr>
            <tr>
                <td  align="right">Nome:</td>
                <td  colspan="3" class="dcli"><?php
                    $clibol = ''; //utf8_encode($row_lista['nome']);
                    echo $cliente->nome;
                    ?></td>
            </tr>
            <tr>
                <td align="right">Endere&ccedil;o:</td>
                <td colspan="3" class="dcli">
                    <?php
                    if (count($endereco) > 0) {
                        echo $endereco->rua;
                        echo $endereco->numero <> '' ? ' Nro. ' . $endereco->numero : '';
                        echo $endereco->complemento <> '' ? ' - ' . $endereco->complemento : '';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td align="right">Bairro:</td>
                <td class="dcli"><?php
                    if (count($endereco) > 0) {
                        echo $endereco->bairro;
                    }
                    ?></td>
                <td align="right">CNPJ:</td>
                <td  class="dcli"><?php
                    echo $cliente->cnpj;
                    ?></td>
            </tr>
            <tr>
                <td align="right">Cidade:</td>
                <td class="dcli"><?php
                    if (count($endereco) > 0) {
                        echo $endereco->cidade;
                    }
                    ?></td>
                <td align="right">CEP:</td>
                <td class="dcli"><?php
                    if (count($endereco) > 0) {
                        echo $endereco->cep;
                    }
                    ?></td>
            </tr>
            <tr>
                <td align="right">Fone:</td>
                <td class="dcli"><?php
                    if (count($telefone) > 0) {
                        echo $telefone->ddd . ' ' . $telefone->numero;
                    }
                    ?></td>
                <td align="right">Contato:</td>
                <td class="dcli"><?php
                    if (count($telefone) > 0) {
                        echo $telefone->contato;
                    }
                    ?></td>
            </tr>
            <tr>
                <td align="right">Email:</td>
                <td colspan="3" class="dcli"><?php
                    if (count($email) > 0) {
                        echo $email->email;
                    }
                    ?></td>
            </tr>
        </table>
        <table class="tab" align="center">
            <tr class="dcli">
                <td colspan="6" class="fatura tab"><strong>Produtos ou Servi&ccedil;os</strong></td></tr>
            <tr>
            <tr>
                <th width="20" class="prod">Item</th>
                <th width="20" class="prod">Contrato</th>
                <th width="400" class="prod">Descri&ccedil;&atilde;o</th>
                <th width="70" class="prod">Quantidade</th>
                <th width="70" class="prod">Valor Unit.</th>
                <th width="80" class="prod" align="center">Valor Total</th>
            </tr>

            <?php
            $i = 1;
            foreach ($debitos as $deb):
                ?>
                <tr>
                    <td><?php
                        echo $i;
                        $i++;
                        ?></td>
                    <td></td>
                    <td><?php echo $deb->descricao; ?></td>
                    <td align="center"><?php echo $deb->quantidade; ?></td>
                    <td align="right"><?php echo $deb->valor; ?></td>
                    <td align="right"><?php echo $deb->total; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>        

        <table class="tab">
            <tr>
                <td valign="top" class="inform">
                    ISS isento conforme LEI COMPLEMENTAR N&ordm; 116, DE 31 DE JULHO DE 2003, A loca&ccedil;&atilde;o de bens im&oacute;veis ou m&oacute;veis n&atilde;o constitui uma presta&ccedil;&atilde;o de servi&ccedil;os, mas disponibiliza&ccedil;&atilde;o de um bem, seja ele im&oacute;vel ou m&oacute;vel para utiliza&ccedil;&atilde;o do locat&aacute;rio sem a presta&ccedil;&atilde;o de um servi&ccedil;o.
                </td>
                <td align="center" class="total">
                    TOTAL FATURA R$
                </td>
                <td class="total" align="right">
                    <?php echo $data['valor_cobrado']; ?>&nbsp;
                </td>
            </tr>
        </table>            
    </div> 
    <?php
    $this->load->helper('My_boleto_bb');
    boleto_bb($data);
    ?>
</body>