<style>
    .min{
        padding-top: 0px!important;
        padding-bottom: 0px!important;
        border-top: none!important;
    }
    p{
        margin: 0 0 0px!important;
        text-align: justify!important;
    }
    @page {
        margin-top: 15mm;
        margin-bottom: 15mm;
    }
</style>

<div class="no-print"><button class="btn btn-primary " onclick="print();"><i class="fa fa-print"></i> <?=lang('imprimir')?></button></div>
<table class="table" cellspacing="30">
    <tr>
        <th colspan="7" class="thLogo" style="border-top: none;"><img src="<?php echo base_url('media/img/logo_show_tecnologia_termo_de_adesao.png') ?>"/> <h3 class="pull-right title"><?=lang('tema_termo')?></h3></th>
    </tr>
<tbody class="tbodyBlue">
    <tr class="dadosCtt">
        <td colspan="7"><b><?=strtoupper(lang('dados_contratada'))?></b></td>
    </tr>
<tbody class="tbodyTopo">
    <tr>
        <td colspan="1"><?=strtoupper(lang('nome'))?>: <b><?=strtoupper(lang('nome_empresa_showtecnologia'));?></td>
        <td colspan="1"><?=strtoupper(lang('cnpj'))?>: <b><?=strtoupper('09.338.999/0001-58');?></td>
        <td colspan="1"><?=lang('insc_estadual')?>: <b><?=strtoupper(lang('isenta'));?></td>
        <td colspan="2"><?=lang('telefone')?>: <b><?=strtoupper('4020-2472');?></td>
        <td colspan="2"></td>

    </tr>
    <tr>
        <td colspan="2" ><?=lang('endereco')?>: <b><?=strtoupper(lang('endereco_contratada'));?></td>
        <td colspan="2"><?=lang('executivo_vendas')?>: <b><?=strtoupper($executivo_vendas);?></b></td>
        <td colspan="3"></td>
    </tr>
</tbody>
<tbody class="tbodyBlue">
<tr>
    <td colspan="7"><b><?=lang('endereco_contratante')?></b></td>
</tr>
</tbody>
<tbody class="tbodyTopo">
<tr>
    <td colspan="1"><?=lang('razao_social')?> / <?=lang('nome')?>: <b><?=strtoupper($razao_social);?></b></td>
    <td colspan="2"><?=lang('cnpj')?> / <?=lang('cpf')?>: <b><?=strtoupper($cnpj_cpf);?></b></td>
    <td colspan="4"><?=lang('insc_estadual')?>: <b><?=strtoupper($insc_estadual);?></b></td>
</tr>
<tr>
    <td colspan="1"><?=lang('email')?>: <b><?=strtoupper($email);?></b></td>
    <td colspan="2"><?=lang('fone_fixo')?>: <b><?=strtoupper($fone_fixo);?></b></td>
    <td colspan="4"><?=lang('fone_cel')?>: <b><?=strtoupper($fone_cel);?></b></td>
</tr>
<tr>
    <td colspan="1"><?=lang('endereco')?>: <b><?=strtoupper($rua);?></b></td>
    <td colspan="2"><?=lang('bairro')?>: <b><?=strtoupper($bairro);?></b></td>
    <td colspan="4"><?=lang('cidade')?>: <b><?=strtoupper($cidade);?></b></td>
</tr>
<tr>
    <td colspan="1"><?=lang('complemento')?>: <b><?=strtoupper($complemento);?></b></td>
    <td colspan="2"><?=lang('estado')?>: <b><?=strtoupper($uf);?></b></td>
    <td colspan="4"><?=lang('cep')?>: <b><?= strtoupper($cep); ?></b></td>
</tr>

</tbody>

<?php if (isset($endereco_entrega) && $endereco_entrega): ?>
    <tbody class="tbodyBlue">
        <tr>
            <td colspan="7"><b><?=lang('endereco_entrega')?></b></td>
        </tr>
    </tbody>
    <tbody class="tbodyTopo">
        <tr>
            <td colspan="1"><?=lang('endereco')?>: <b><?=strtoupper($endereco_entrega['rua']);?></b></td>
            <td colspan="2"><?=lang('bairro')?>: <b><?=strtoupper($endereco_entrega['bairro']);?></b></td>
            <td colspan="4"><?=lang('cidade')?>: <b><?=strtoupper($endereco_entrega['cidade']);?></b></td>
        </tr>
        <tr>
            <td colspan="1"><?=lang('complemento')?>: <b><?=strtoupper($endereco_entrega['complemento']);?></b></td>
            <td colspan="2"><?=lang('estado')?>: <b><?=strtoupper($endereco_entrega['uf']);?></b></td>
            <td colspan="4"><?=lang('cep')?>: <b><?= strtoupper($endereco_entrega['cep']); ?></b></td>
        </tr>
    </tbody>
<?php endif; ?>

<tbody class="tbodyBlue">
<tr>
    <td colspan="7"><b><?=strtoupper(lang('cond_comerciais'))?></b></td>
</tr>
</tbody>
<tbody class="tbodyTopo">
<tr>
    <td colspan="1"><?=lang('tipo_contrato')?>: <b><?=strtoupper($tipo_contrato);?></b></td>
    <td colspan="2"><?=lang('pessoa_contas_pagar')?>: <b><?=strtoupper($pessoa_contas_pagar);?></b></td>
    <td colspan="4"><?=lang('contato_contas_pagar')?>: <b><?=strtoupper($ctt_contas_pagar);?></b></td>
</tr>
<tr>
    <td colspan="1"><?=lang('email_financeiro')?>: <b><?=strtoupper($email_financeiro);?></b></td>
    <td colspan="2"><?=lang('qtd_equipamentos')?>: <b><?=strtoupper($qtd_eqp);?></b></td>
    <td colspan="4"><?=lang('bloqueio')?>: <b><?=strtoupper($bloqueio);?></b></td>
</tr>
</tbody>
<tbody class="tbodyBlue">
<tr>
    <td colspan="7"><b><?=lang('cond_pagamento')?></b></td>
</tr>
</tbody>
<tbody class="tbodyTopo">
    <tr>
        <td colspan="1"><?=lang('contato_contas_pagar')?>: <b><?=strtoupper($ctt_contas_pagar);?></b></td>
        <td colspan="2"><?=lang('email')?>: <b><?=strtoupper($email);?></b></td>
        <td colspan="4"><?=lang('inst_parcelas')?>: <b><?=strtoupper($inst_parcelas);?></b></td>
    </tr>
    <tr>
        <td colspan="1"><?=lang('pacote_servicos')?>: <b><?=strtoupper($pct_servicos);?></b></td>
        <td colspan="2"><?=lang('periodo_contato')?>: <b><?=strtoupper($periodo_contrato);?> meses</b></td>
        <td colspan="4"><?=lang('dia_vencimento')?>: <b><?= strtoupper($dt_vencimento);?></b></td>
    </tr>
    <tr>
        <td colspan="1"><?=lang('valor_inst_veic')?>: <b>R$ <?=strtoupper(number_format($valor_inst_veic, 2, ',', ' '));?></b></td>
        <td colspan="2"><?=lang('venc_adesao')?>: <b><?= validateDate($primeiro_venc_adesao , 'Y-m-d') ? date_format(date_create($primeiro_venc_adesao), 'd/m/Y') : strtoupper($primeiro_venc_adesao); ?></b></td>
        <td colspan="4"><?=lang('venc_mensalidade')?>: <b><?= validateDate($primeiro_venc_mens , 'Y-m-d') ? date_format(date_create($primeiro_venc_mens), 'd/m/Y') : strtoupper($primeiro_venc_mens); ?></b></td>
    </tr>
    <tr>
        <td colspan="7"><?=lang('valor_mensalidade_veic')?>: <b>R$ <?=strtoupper(number_format($valor_mens_veic, 2, ',', '.'));?></b></td>
    </tr>
    <tr>
        <td colspan="1"><?=lang('produto_adicional')?>: <b><?=strtoupper($produto_adicional);?></b></td>
        <td colspan="2"><?=lang('quantidade')?>: <b><?=strtoupper($qtd);?></b></td>
        <td colspan="4"><?=lang('valor_unitario')?>: <b>R$ <?=strtoupper(number_format($valor_final_un, 2, ',',' '));?></b></td>
    </tr>
    <tr>
        <td colspan="1"><?=lang('adicional_parcelas')?>: <b>R$ <?=strtoupper(number_format($adicional_parcelas, 2, ',', ' '));?></b></td>
        <td colspan="2"><?=lang('venc_adicional')?>: <b><?= validateDate($primeiro_venc_adicional , 'Y-m-d') ? date_format(date_create($primeiro_venc_adicional), 'd/m/Y') : strtoupper($primeiro_venc_adicional); ?></b></td>
        <td colspan="4"><?=lang('total')?>: <b>R$ <?=strtoupper(number_format($total, 2, ',', ' '));?></b></td>
    </tr>
    <tr>
        <td colspan="1"><?=lang('inst_sigilosa')?>: <b><?=strtoupper($inst_sigilosa);?></b></td>
        <td colspan="6"><?=lang('observacao')?>Observação: <b><?= strtoupper($obs); ?></b></td>
    </tr>
</tbody>

<tbody class="tbodyBlue"><tr><td colspan="7"></td></tr></tbody>
    <tbody class="tbodyTopo">
        <?=lang('termo_acordo_show') ?>
    </tbody>
    <br>
</table>
<table style="width:100%;margin-top:40px;">
    <tbody class="nomeShow">
        <tr>
            <td style="width:50%;">
                <div style="width:100%;">
                    <center>
                        <br><br>
                        <p style="text-align:center!important;">__________________________________________________________________________</p>
                        <i><?=lang('nome_empresa_showtecnologia_me')?></i><br/>
                        <i><?=lang('inf_assinatura_termo')?></i><br/>
                        <small style="..."><?=strtoupper($data_termo); ?></small>
                    </center>
                </div>
                <div style="width:100%; margin-top: 30px;">
                    <center>
                        <br><br>
                        <p style="text-align:center!important;">__________________________________________________________________________</p>
                        <i><?=strtoupper(lang('testemunha'))?></i><br/>
                    </center>
                </div>
            </td>
            <td style="width:50%;">
                <div style="width:100%;">
                    <center>
                        <br><br>
                        <p style="text-align:center!important;">__________________________________________________________________________</p>
                        <i><?=strtoupper($razao_social);?></p>
                        <i><?=lang('cpf')?>/<?=lang('cnpj')?>: <?= strtoupper($cnpj_cpf); ?></i><br/>
                        <small style="..."><?= $data_atual ?></small>
                    </center>
                </div>
                <div style="width:100%; margin-top: 30px;">
                    <center>
                        <br><br>
                        <p style="text-align:center!important;">__________________________________________________________________________</p>
                        <i><?=strtoupper(lang('testemunha'))?></i><br/>
                    </center>
                </div>
            </td>
        </tr>
    </tbody>
</table>
