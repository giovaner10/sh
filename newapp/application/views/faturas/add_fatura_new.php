<style>
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
    }

    select[readonly] {
        background: #eee;
        /*Simular campo inativo - Sugestão @GabrielRodrigues*/
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }
</style>

<div id="alerta" class="hide">
    <div class="alert alert-info" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        Escolha uma <b>DATA DE VENCIMENTO</b> e uma <b>FORMA DE PAGAMENTO</b> para adicionar um item
    </div>
</div>

<div class="row">
    <input name="valorTotal" id="valorTotal" type="hidden">
    <div class="form-group col-md-4">
        <label>Secretaria:</label>
        <select class="form-control desativar" id="secretariaId" name="secretaria">
            <option value="">
                Selecione uma secretaria
            </option>
        </select>
    </div>  
    <div class="form-group col-md-4">
        <label>Ticket:</label>
        <select class="form-control desativar" id="ticket" name="id_ticket">
            <option value="">
                Selecione um ticket
            </option>
        </select>
    </div>
    <div class="form-group col-md-4">
        <label>Data da fatura:</label>
        <input class="form-control" type="date" name="data_emissao" value="<?= date('Y-m-d') ?>" readonly>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label>Data de Vencimento:</label>
        <input class="form-control desativar dataNewFat" type="date" id="vencimento_fatura" name="data_vencimento" required>
    </div>
    <div class="form-group col-md-4">
        <label>Forma de pagamento:</label>
        <select class="form-control desativar" name="forma_pagamento" id="formaPagamento" required>
            <option value="">Escolha uma opção</option>
            <option value="1">Boleto</option>
            <option value="2">Cartão Crédito</option>
            <option value="3">Cartão Débito</option>
            <option value="4">Depósito/Transf. Bancária</option>
            <option value="5">Dinheiro</option>
        </select>
    </div>
    <div class="form-group col-md-4">
        <label>Nº Nota Fiscal:</label>
        <input class="form-control desativar" type="text" maxlength="10" name="nota_fiscal" value="">
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label>Mês de referência:</label>
        <input type="text" id="mes_referencia" name="mes_referencia" placeholder="00/0000 (mês/ano)" class="form-control ref desativar">
    </div>
    <div class="form-group col-md-4">
        <label>Início do período:</label>
        <input class="form-control desativar dataNewFat" type="date" id="periodo_inicial" name="periodo_inicial">

    </div>
    <div class="form-group col-md-4">
        <label>Fim do período:</label>
        <input class="form-control desativar dataNewFat" type="date" id="periodo_final" name="periodo_final" name="data_vencimento">
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label>Atividade:</label>
        <select class="form-control desativar" name="atividade" id="atividade" required>
            <option value="">Escolha uma Opção</option>
            <option value="1">Atividade de Monitoramento</option>
            <option value="2">Serviços Técnicos</option>
            <option value="3">Aluguel de outras máquinas e equipamentos</option>
            <option value="4">Suporte técnico, manutenção e outros serviços em tecnologia da informação</option>
            <option value="5">Desenvolvimento e licenciamento de programas de computador customizáveis</option>
            <option value="6">Serviços combinados de escritório e apoio administrativo</option>
            <option value="0">Outros</option>
        </select>
    </div>
    <!-- quantidade de veículos  -->
    <div class="form-group col-md-4">
        <label>Quantidade de veículos:</label>
        <input class="form-control desativar" type="number" name="qtd_veiculos" value="0">
    </div>
    <!-- data de inclusão -->
    <div class="form-group col-md-4">
        <label>Data de inclusão:</label>
        <input class="form-control" type="date" name="data_inclusao" value="<?= date('Y-m-d') ?>">
    </div>
</div>
<div class="row validar">
    <div class="col-md-6">
        <button type="button" id="add_itens" class="btn btn-success">Adicionar itens</button>
    </div>
</div>
<hr>
<div class="itens hide">
    <div class="well">
        <div id="conta">
            <div class="row">
                <div class="col-md-2" style="width: 85px">
                    <span>Qnt. Itens</span><br>
                    <span id="qntItens">0</span>
                </div>
                <div class="col-md-1" style="width: 120px;">
                    <span>Juros</span><br>
                    <span id="juros">R$ 0,00</span>
                </div>
                <div class="col-md-1" style="width: 120px;">
                    <span>Boleto</span><br>
                    <span id="boleto">R$ 0,00</span>
                </div>
                <div class="col-md-1" style="width: 120px;">
                    <span><b>Subtotal</b></span><br>
                    <span id="subtotal">R$ 0,00</span>
                </div>
                <div class="col-md-1">
                    <span>PIS</span><br>
                    <span id="pis1">0 %</span>
                </div>
                <div class="col-md-2" style="width: 95px">
                    <span>Cont. Social</span><br>
                    <span id="cont1">0 %</span>
                </div>
                <div class="col-md-1">
                    <span>IRPJ</span><br>
                    <span id="irpj1">0 %</span>
                </div>
                <div class="col-md-1">
                    <span>COFINS</span><br>
                    <span id="cofins1">0 %</span>
                </div>
                <div class="col-md-1">
                    <span>ISS</span><br>
                    <span id="iss1">0 %</span>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-2 pull-right">
                    <label><b>Total</b></label><br>
                    <span id="total">R$ 0,00</span>
                </div>
            </div>
        </div>
    </div>
    <div id="botao">
        <button title="Adicionar itens" class="adicionarItem btn btn-success hide"><i class="fa fa-plus"></i> Adicionar item</button>
    </div><br>

    <div class="row divItem0" id="0">
        <div class="form-group col-md-4">
            <label>Descrição</label>
            <textarea class="form-control item0" id="descricao0" name="itens[0][descricao_item]" rows="4" type="text" placeholder="Descrição do item" required disabled></textarea>
        </div>
        <div class="form-group col-md-2">
            <label>Valor</label>
            <input class="form-control valor item0" id="valor0" name="itens[0][valor_item]" type="text" placeholder="0,00" required disabled>
        </div>
        <div class="form-group col-md-2">
            <label>Tipo de Ítem</label>
            <select class="form-control item0 selectItem" data-numero_item="0" id="tipo_item0" name="itens[0][tipo_item]" required>
                <option value="" disabled selected></option>
                <option value="mensalidade">Mensalidade</option>
                <option value="adesao">Adesão</option>
                <option value="taxa">Taxa</option>
                <option value="avulso">Outros</option>
            </select>
        </div>
        <div class="col-md-3" id="divContratoItem0" style="display:none">
            <div>
                <label>Contrato</label>
                <input class="form-control contrato item0" id="contrato0" name="itens[0][relid_item]" type="number">
            </div>
        </div>
        <div class="col-md-3" id="divTaxaItem0" style="display:none">
            <div class="form-group">
                <label>Tipo de Taxa</label>
                <select class="form-control check-item0 item0" id="check-item0" name="itens[0][tipotaxa_item]">
                    <option value=""></option>
                    <option value="juros">Juros</option>
                    <option value="boleto">Boleto</option>
                </select>
            </div>
        </div>

        
        <button data-indice="0" title="Adicionar item" class="addLista btn btn-circle btn-success btn-mini" style="margin-top: 20px"><i class="fa fa-plus"></i></button>
        <button data-indice="0" title="Remover" class="removerI valor0 btn btn-danger btn-circle btn-mini hide" style="margin-top: 20px"><i class="fa fa-remove"></i></button>
    </div>
</div>

<script>
    $(document).ready(function() {

        $.jMaskGlobals.watchDataMask = true;
        $('.ref').mask('00/0000');
        $('.valor').mask("#.##0,00", {
            reverse: true
        });

        var item_indice = 1
        var quan_itens = 0;
        var pis = $('.pis').val()
        var cont = $('.csll').val()
        var irpj = $('.irpj').val()
        var cofins = $('.cofins').val()
        var iss = $('.iss').val()

        $('#pis1').text(pis + '%');
        $('#cont1').text(cont + '%');
        $('#irpj1').text(irpj + '%');
        $('#cofins1').text(cofins + '%');
        $('#iss1').text(iss + '%');

        valortotal = false;
        valorboleto = false;
        valorjuros = false;
        subtotal = false;

        //
        $('#add_itens').click(function(e) {
            // e.preventDefault()
            if (validar_dados()) {
                $('.desativar').attr('readonly', true);
                $('.itens').removeClass('hide');
                $('.validar').addClass('hide');
                $('#alerta').addClass('hide');
                $('.item0').removeAttr('disabled');

            } else {
                $('#alerta').removeClass('hide');
            }
        });

        //ADICIONAR ITEM A LISTA
        $('.itens').on('click', '.addLista', function(e) {
            e.preventDefault();

            let botao = $(this);
            let indice = botao.attr('data-indice');

            let classe = $('#item' + indice).val();
            var idValor = $('#valor' + indice).val();
            var idDescricao = $('#descricao' + indice).val();
            var idTaxa = $('#check-item' + indice).val();
            var idTipo = $('#tipo_item' + indice).val();
            var idContrato = $('#contrato' + indice).val();

            if (idDescricao == '' || idValor == '' || idTipo == null) {
                alert('Obrigatório preencher os campos: Descrição, Valor, Tipo de Ítem');
                return;

            } else {
                if ((idTipo == 'mensalidade' || idTipo == 'adesao') && idContrato == '') {
                    alert('Informe o contrato do item!');
                    return;

                } else if (idTipo == 'taxa' && idTaxa == '') {
                    alert('Informe o tipo de taxa!');
                    return;

                } else {
                    $('.' + classe).attr('readonly', true);
                    $('.adicionarItem').removeClass('hide')
                    $('.removerI').removeClass('hide')
                    $('.addLista').addClass('hide')
                    preenherValores(idValor, idTaxa, idTipo)
                }

            }
        });

        //ALTERNA ENTRE TIPOS DE ITEM, HABILITANDO/ DESABILITANDO TAXA
        $(document).on('change', '.selectItem', function(e) {
            e.preventDefault()
            let itemSelecionado = $(this).val();
            let numero_item = $(this).attr('data-numero_item');

            if (itemSelecionado == 'taxa') {
                $('#divTaxaItem' + numero_item).css('display', 'block');
                $('#divContratoItem' + numero_item).css('display', 'none');

            } else if (itemSelecionado == 'avulso') {
                $('#divTaxaItem' + numero_item).css('display', 'none');
                $('#divContratoItem' + numero_item).css('display', 'none');

            } else {
                $('#divTaxaItem' + numero_item).css('display', 'none');
                $('#divContratoItem' + numero_item).css('display', 'block');
            }
        });

        //INSERIR TAXA - BOLETO/JUROS
        $(document).on('change', ':checkbox', function(e) {
            e.preventDefault()
            var c = $(this).attr('class').split(' ')[1]
            if (this.checked) {
                $('#' + c).removeAttr('readonly');
            } else {
                $('#' + c).attr('readonly', true);
            }
        });

        //PREENCHER SELECT SECRETARIAS
        if (('<?= $secretarias ?>' !== '')) {
            var secretarias = JSON.parse('<?= $secretarias ?>');
            $.each(secretarias, function(i, d) {
                $('#secretariaId').append('<option value="' + d.id + '">' + d.nome + '</option>');
            })
            $('#secretariaId').select2();
        }

        if (('<?= $tickets ?>' !== '')) {
            var tickets = JSON.parse('<?= $tickets ?>');
            $.each(tickets, function(i, d) {
                $('#ticket').append('<option value="' + d.id + '">' + d.id + '</option>');
            })
            $('#ticket').select2();
        }

        //NOVO ITEM
        $('.itens').on('click', '.adicionarItem', function(e) {
            e.preventDefault();

            var indice = item_indice - 1;
            if ($('#descricao' + indice).val() == '' || $('#valor' + indice).val() == '') {
                return false;
            } else {
                $('.item' + indice).removeClass('hide').attr('readonly', true)
                $('.removerI').removeClass('hide');

                var itens = [
                    '<div class="row divItem' + item_indice + '" id="' + item_indice + '">',
                    '<div class="form-group col-md-4">',
                    '<label>Descrição</label>',
                    '<textarea class="form-control item' + item_indice + '" id="descricao' + item_indice + '" name="itens[' + item_indice + '][descricao_item]" rows="4" type="text" placeholder="Descrição do item" required></textarea>',
                    '</div>',
                    '<div class="form-group col-md-2">',
                    '<label>Valor</label>',
                    '<input class="form-control item' + item_indice + ' valor" id="valor' + item_indice + '" data-mask="#.##0,00" data-mask-reverse="true" name="itens[' + item_indice + '][valor_item]" type="text" placeholder="0,00" required>',
                    '</div>',
                    '<div class="form-group col-md-2">',
                    '<label>Tipo de Ítem</label>',
                    '<select class="form-control item' + item_indice + ' selectItem" data-numero_item="' + item_indice + '" id="tipo_item' + item_indice + '" name="itens[' + item_indice + '][tipo_item]" required >',
                    '<option value="" disabled selected></option>',
                    '<option value="mensalidade">Mensalidade</option>',
                    '<option value="adesao">Adesão</option>',
                    '<option value="taxa">Taxa</option>',
                    '<option value="avulso">Outros</option>',
                    '</select>',
                    '</div>',
                    '<div class="col-md-3" id="divContratoItem' + item_indice + '" style="display:none">',
                    '<div>',
                    '<label>Contrato</label>',
                    '<input class="form-control contrato item' + item_indice + '" id="contrato' + item_indice + '" name="itens[' + item_indice + '][relid_item]" type="number" >',
                    '</div>',
                    '</div>',
                    '<div class="col-md-3" id="divTaxaItem' + item_indice + '" style="display:none">',
                    '<div class="form-group">',
                    '<label>Tipo de Taxa</label>',
                    '<select class="form-control check-item' + item_indice + ' item' + item_indice + '" id="check-item' + item_indice + '" name="itens[' + item_indice + '][tipotaxa_item]" >',
                    '<option value=""></option>',
                    '<option value="juros">Juros</option>',
                    '<option value="boleto">Boleto</option>',
                    '</select>',
                    '</div>',
                    '</div>',
                    '<button data-indice="' + item_indice + '" title="Adicionar item" class="addLista btn btn-circle btn-success btn-mini" style="margin-top: 20px"><i class="fa fa-plus"></i></button>',
                    '<button data-indice="' + item_indice + '" title="Remover" class="removerI valor' + item_indice + ' btn btn-danger btn-circle btn-mini hide" style="margin-top: 20px"><i class="fa fa-remove"></i></button>',
                    '</div>'

                ].join('');
                $(itens).eq(0).insertAfter('#botao');
                $('.adicionarItem').addClass('hide');
                item_indice++;
            }
        })

        //REMOVER ITENS
        $('.itens').on('click', '.removerI', function(e) {
            e.preventDefault();
            let botao = $(this);
            let indice = botao.attr('data-indice');

            var idValor = $('#valor' + indice).val();
            var idTaxa = $('#check-item' + indice).val();
            var idTipo = $('#tipo_item' + indice).val();

            var valorAux = parseFloat(idValor.replace(/\./g, "").replace(',', '.'))
            quan_itens--;

            if (!(typeof idValor === 'undefined')) {
                $('#qntItens').text(quan_itens);
                valortotal = valortotal - parseFloat(valorAux);
                var valorFinal = valortotal - (valortotal * (parseFloat(pis) / 100)) - (valortotal * (parseFloat(iss) / 100)) - (valortotal * (parseFloat(cofins) / 100)) - (valortotal * (parseFloat(irpj) / 100)) - (valortotal * (parseFloat(cont) / 100));
                $('#total').text(valorFinal.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }))

                if (idTipo == 'taxa') {
                    if (idTaxa && idTaxa == 'boleto') {
                        valorboleto = valorboleto - parseFloat(valorAux);
                        $('#boleto').text(valorboleto.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL'
                        }))
                    }
                    if (idTaxa && idTaxa == 'juros') {
                        valorjuros = valorjuros - parseFloat(valorAux);
                        $('#juros').text(valorjuros.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL'
                        }))
                    }
                } else {
                    subtotal = subtotal - parseFloat(valorAux);
                    $('#subtotal').text(subtotal.toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    }))
                }

                //O VALOR TOTAL SERA SALVO SEM OS IMPOSTOS = SUBTOTAL, O CALCULO DOS IMPOSTOS É FEITO NA PAGINA DE IMPRESÃO OU EDIÇÃO
                $('#valorTotal').val(valortotal.toFixed(2));
            }

            $(this).closest('div').remove();
        });

        //VALIDAR DADOS
        function validar_dados() {
            if ($('#vencimento_fatura').val() == '' || $('#formaPagamento').val() == '') {
                return false;
            }
            return true;
        }

        //PREENCHER VALORES ITENS ADICIONADOS
        function preenherValores(valor, taxa, tipo) {
            valor = parseFloat(valor.replace(/\./g, "").replace(',', '.'))

            if (!(typeof valor === 'undefined')) {
                valortotal = valortotal + valor;
                var valorFinal = valortotal - (valortotal * (parseFloat(pis) / 100)) - (valortotal * (parseFloat(iss) / 100)) - (valortotal * (parseFloat(cofins) / 100)) - (valortotal * (parseFloat(irpj) / 100)) - (valortotal * (parseFloat(cont) / 100));

                $('#total').text(valorFinal.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }))
                if (tipo == 'taxa') {
                    if (taxa && taxa == 'boleto') {
                        valorboleto = valorboleto + parseFloat(valor);
                        $('#boleto').text(valorboleto.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL'
                        }))
                    }
                    if (taxa && taxa == 'juros') {
                        valorjuros = valorjuros + parseFloat(valor);
                        $('#juros').text(valorjuros.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL'
                        }))
                    }

                } else {
                    subtotal = subtotal + parseFloat(valor);
                    $('#subtotal').text(subtotal.toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    }))
                }
                quan_itens++;
                $('#qntItens').text(quan_itens);
                //O VALOR TOTAL SERA SALVO SEM OS IMPOSTOS = SUBTOTAL, O CALCULO DOS IMPOSTOS É FEITO NA PAGINA DE IMPRESÃO OU EDIÇÃO
                $('#valorTotal').val(valortotal.toFixed(2));
            }
        }

    });

    //LIMITA AS ESTRADAS DOS INPUTS DE DATAS PARA NO MAXIMO/MINIMO 1 ANO A FRENTE/ATRAS ???
    $(document).on('click', '.dataNewFat', function() {
        //minMaxDataImput('.dataNewFat', new Date(), 0, 0, 1);
    });
</script>