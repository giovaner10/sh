<center>
    <input name="btImprimir" id="btImprimir" type="button" class="botao" value="Imprimir" onClick="document.getElementById('btImprimir').style.display = 'none';print();document.getElementById('btImprimir').style.display = 'block';">
</center>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>e-Nota [Imprimir Nota]</title>
    <style type="text/css" >
        table.gridview {
            width:100%;
            font-size:10px;
            font-family:Verdana, Arial, Helvetica, sans-serif;
            border-bottom-width:thick;
            border-collapse:collapse;
        }
        table.gridview tr td {
            border:1px solid #000000;
        }
        table.gridview tr th {
            background-color:#CCCCCC;
            border:1px solid #000000;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
<div id="result"></div>
<center>

    <table width="800" border="0" cellspacing="0" cellpadding="2" style="border:#000000 1px solid;border-collapse:collapse">
        <tr>
            <td colspan="4" rowspan="3" width="75%" style="border:#000000 1px solid" align="center">
                <!-- tabela prefeitura inicio -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td rowspan="4" width="20%" align="center" valign="top">
                            <img src="<?= base_url('media/img/brasao_prefeituragba.png') ?>" width="100%" />
                            <br />
                        </td>
                        <td width="80%" class="cab01">PREFEITURA MUNICIPAL DE GUARABIRA</td>
                    </tr>
                    <tr>
                        <td class="cab03">SEFIN</td>
                    </tr>
                    <tr>
                        <td class="cab02">NOTA FISCAL ELETRÔNICA DE SERVIÇOS - NF-e</td>
                    </tr>
                </table>

                <!-- tabela prefeitura fim -->	</td>
            <td width="25%" colspan="2" align="left" style="border:#000000 1px solid">Número da Nota<br /><div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><strong><?= $nota_fiscal ?></strong></font></div></td>
        </tr>
        <tr>
            <td align="left" colspan="2" style="border:#000000 1px solid">Data e Hora de Emissão<br /><div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><strong><?= date('d/m/Y H:i:s') ?></strong></font></div></td>
        </tr>
        <tr>
            <td align="left" colspan="2" style="border:#000000 1px solid">Código de Verificação<br />
                <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><strong><?= $chave_nota ?></strong></font></div>
                <?php if (!empty($chave_nota)): ?>
                    <div style="float:right">
                        <img height="80px" style="float:right;margin-right:55px;" src="http://chart.apis.google.com/chart?cht=qr&chl=http://guarabiranfe.elmarinformatica.com.br/site/tomadores.php&tipo=T&chs=180x180"/>
                    </div>
                <?php endif; ?>
            </td>

        </tr>
        <tr>
            <td colspan="6" align="center" style="border:#000000 1px solid">

                <!-- tabela prestador -->
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="3" class="cab03" align="center">PRESTADOR DE SERVIÇOS</td>
                    </tr>
                    <tr>
                        <td rowspan="6">

                        </td>
                        <td align="left">CNPJ/CPF: <strong>09.338.999/0001-58</strong></td>
                        <td align="left">Inscrição Municipal: <strong><b>Não Informado</b></strong></td>
                    </tr>
                    <tr>
                        <td align="left">Nome: <strong>SHOW TECNOLOGIA</strong></td>
                        <td align="left">Inscrição Estadual: <strong><b>Não Informado</b></strong></td>
                    </tr>
                    <tr>
                        <td align="left">Razão Social: <strong>SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA-ME</strong></td>
                        <td align="left">PIS/PASEP: <b>Não Informado</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="left">Endereço: <strong>AV. RUI BARBOSA, 144</strong></td>
                    </tr>
                    <tr>
                        <td align="left">Município: <strong>GUARABIRA</strong></td>
                        <td align="left">UF: <strong>PB</strong></td>
                    </tr>
                </table>


                <!-- tabela prestador -->	</td>
        </tr>
        <tr>
            <td colspan="6" align="center" style="border:#000000 1px solid">
                <!-- tabela tomador inicio -->

                <table width="100%" border="0" cellspacing="0" cellpadding="2" align="center">
                    <tr>
                        <td colspan="3" class="cab03" align="center">TOMADOR DE SERVIÇOS</td>
                    </tr>
                    <tr>
                        <td colspan="3" align="left">&nbsp;&nbsp;Nome/Razão Social: <strong>
                                <?= $razao_social ?></strong></td>
                    </tr>
                    <tr>
                        <td align="left" width="450">&nbsp;&nbsp;CPF/CNPJ: <strong><?= $cnpj ?></strong></td>
                        <td colspan="2" align="left">Inscrição Municipal: <strong><B>NãO INFORMADO</B></strong></td>
                    </tr>
                    <tr>
                        <td align="left">&nbsp;&nbsp;Endereço: <strong><?= $endereco.' '.$end_numero. ' - '.$bairro ?></strong></td>
                        <td colspan="2" align="left">Inscrição Estadual: <strong><B>NãO INFORMADO</B></strong></td>
                    </tr>
                    <tr>
                        <td align="left">&nbsp;&nbsp;Município: <strong>
                                <?= $municipio ?></strong></td>
                        <td align="left">CEP: <strong><?= $cep ?></strong></td>
                    </tr>
                    <tr>
                        <td align="left">&nbsp;&nbsp;E-mail: <strong><?= $email ?></strong></td>
                        <td align="left">UF: <strong><?= $uf ?></strong></td>
                    </tr>
                </table>

                <!-- tabela tomador fim -->	</td>
        </tr>
        <tr>
            <td colspan="6" align="center" style="border:#000000 1px solid">

                <!-- tabela discrimacao dos servicos -->

                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td class="cab03" align="center">DISCRIMINAÇÃO DE SERVIÇOS E DEDUÇÕES</td>
                    </tr>
                    <tr>
                        <td height="400" align="left" valign="top">
                            <br />
                            <table class="gridview" align="center">
                                <tr>
                                    <th align="center">Código</th>
                                    <th align="center">Serviço</th>
                                    <th align="center">Alíquota (%) </th>
                                    <th align="center">Base de Calculo (R$)</th>
                                    <th align="center">Iss retido (R$)</th>
                                    <th align="center">Iss (R$)</th>
                                </tr>
                                <tr>
                                    <td align="center" ><?= $codservico ?></td>
                                    <td align="left"><?= $disc_servico ?></td>
                                    <td align="right"><?= $iss ?></td>
                                    <td align="right"><?= $valortotal ?></td>
                                    <td align="right"><?= $issretido ?></td>
                                    <td align="right"><?= $valoriss ?></td>
                                </tr>
                                <tr>
                                    <th colspan="6" align="left"><strong>Discriminação dos Serviços</strong></th>
                                </tr>
                                <tr>
                                    <td height="30" colspan="6">
                                        <?= $discriminacao ?>
                                    </td>
                                </tr>
                            </table>
                            <br />
                </table>


                <!-- tabela discrimacao dos servicos -->
            </td>
        </tr>




        <tr>
            <td colspan="6" class="cab03" align="center" style="border:#000000 1px solid">VALOR TOTAL = R$
                <?= $valortotal ?>	 /   VALOR LIQUIDO = <?= $liquido ?>
            </td>
        </tr>
        <!--<tr>
          <td colspan="6" align="left" style="border:#000000 1px solid">Código do Serviço<br /><strong> - </strong></td>
          </tr>-->
        <tr>
            <td style="border:#000000 1px solid">Valor Total das Deduções (R$)<br /><div align="right"><strong><?= $valordeducoes ?></strong></div></td>
            <td style="border:#000000 1px solid" colspan="2">Base de Cálculo (R$)<br /><div align="right"><strong><?= $basecalculo ?></strong></div></td>
            <td style="border:#000000 1px solid; display:none">
                Alíquota (%)
                <br />
                <div align="right">
                    <strong>
                        <?= $iss ?> %			  </strong>
                </div>
            </td>
            <td style="border:#000000 1px solid" colspan="3" align="center">
                Valor do ISS (R$)
                <br />
                <div align="right">
                    <strong>
                        <?= $valoriss ?>			  </strong>
                </div>
            </td>

            <td style="border:#000000 1px solid; display:none">
                Crédito
                <br />
                <div align="right">
                    <strong>
                        0,00			 </strong></div>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="border:#000000 1px solid" class="cab03">OUTRAS INFORMAÇÕES</td>
        </tr>

        <tr>
            <td colspan="6" style="border:#000000 1px solid" align="left">
                - Esta NF-e foi emitida com respaldo na Lei n&ordm; 0.000/00 e no Decreto n&ordm; 71/2015    06/03/2015<br />
            </td>
        </tr>

    </table>
    <br /><br />

</center>
</body>
</html>