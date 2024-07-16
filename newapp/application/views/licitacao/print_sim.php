<style>
    p{
        margin: 0 0 0px!important;
        align=justify;
    }
</style>

<div class="no-print"><button class="btn btn-primary " onclick="print();"><i class="fa fa-print"></i> <?=lang('imprimir')?></button></div>
<table class="table" cellspacing="30">
    <tr>
        <th colspan="7" class="thLogo" style="border-top: none;"><img src="<?php echo base_url('media/img/Logo_Sim2m2_termo_de_adesao.png') ?>"/> <h3 class="pull-right title"><?=lang('tema_termo')?></h3></th>
    </tr>

    <tbody class="tbodyBlue">
        <tr class="dadosCtt">
            <td colspan="7"><b><?=lang('dados_contratada')?></b></td>
        </tr>
    </tbody>
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
        <td colspan="1"><?=lang('tipo_contrato')?>: <b><?=$tipo_contrato;?></b></td>
        <td colspan="2"><?=lang('periodo_contato')?>: <b><?= $periodo_contrato; ?> meses</b></td>
        <td colspan="4"><?=lang('qtd_chips')?>: <b><?=$quant_chips;?></b></td>
    </tr>
    <tr>
        <td colspan="1"><?=lang('operadora')?>: <b><?=$operadora;?></b></td>
        <td colspan="6"><?=lang('pacote_megas')?>: <b><?= $pct_megas; ?></b></td>
    </tr>

    </tbody>
    <tbody class="tbodyBlue">
    <tr>
        <td colspan="7"><b><?=lang('cond_pagamento')?></b></td>
    </tr>
    </tbody>
    <tbody class="tbodyTopo">
        <tr>
            <td colspan="1"><?=lang('contato_contas_pagar')?>: <b><?= $contato_contas_pagar; ?></b></td>
            <td colspan="2"><?=lang('pessoa_contas_pagar')?>: <b><?= $pessoa_contas_pagar; ?></b></td>
            <td colspan="4"><?=lang('email_financeiro')?>: <b><?= $email_financeiro; ?></b></td>
        </tr>
        <tr>
            <td colspan="1"><?=lang('valor_ativacao_chip')?>: <b><?=number_format($valor_ativacao_chip, 2, ',',' ');?></b></td>
            <td colspan="2"><?=lang('valor_mensalidade_chip')?>: <b><?=number_format($valor_mensalidade_chip, 2, ',',' ');?></b></td>
            <td colspan="4"><?=lang('taxa_envio')?>: <b><?=number_format($taxa_envio, 2, ',',' '); ?></b></td>
        </tr>
        <tr>
            <td colspan="1"><?=lang('dia_vencimento')?>: <b><?= $data_vencimento ?></b></td>
            <td colspan="2"><?=lang('venc_ativacao')?>: <b><?= date('d/m/Y', strtotime($venc_ativacao));?></b></td>
            <td colspan="4"><?=lang('venc_taxa')?>: <b><?= date('d/m/Y', strtotime($venc_ativacao)); ?></b></td>
        </tr>
        <tr>
            <td colspan="1"><?=lang('venc_mensalidade')?>: <b><?= date('d/m/Y', strtotime($primeiro_vencimento_mensalidade)); ?></b></td>
            <td colspan="6"><?=lang('observacao')?>: <b><?=$observacoes;?></b></td>
        </tr>
    </tbody>
    <tbody class="tbodyBlue"><tr><td colspan="7"></td></tr></tbody>

    <tbody class="tbodyTopo">
        <tr>
            <td colspan="7" class="tdLetrasMiudas">
                <?=lang('termo_acordo_sim') ?>
            </td>
        </tr>
    </tbody>
    <table style="width:100%;margin-top:60px;">
        <tbody class="nomeShow">
            <tr>
                <td style="width:50%;">
                    <div style="width:100%;">
                        <center>
                            <p style="text-align:center!important;">__________________________________________________________________________</p>
                            <i><?=lang('nome_empresa_showtecnologia_me')?></i><br/>
                            <i><?=lang('inf_assinatura_termo')?></i><br/>
                            <small style="..."><?= $data_termo ?></small>
                        </center>
                    </div>
                    <div style="width:100%; margin-top: 20px;">
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
                            <p>__________________________________________________________________________</p>
                            <i><?=strtoupper($razao_social);?></p>
                            <i><?=lang('cpf')?>/<?=lang('cnpj')?>: <?= strtoupper($cnpj_cpf); ?></i><br/>
                            <small style="..."><?= $data_atual ?></small>
                        </center>
                    </div>
                    <div style="width:100%; margin-top: 20px;">
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
</table>
