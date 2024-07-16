<div class="resultado span6" style="float: none;"></div>
<div class="container-fluid dados">
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Tipo Contrato:</label>
                <div class="form-group">
                    <select id="tipo_cont" name="contrato[tipo]" class="form-control" required>
                        <option value="">Selecione o Tipo de Contrato</option>
                        <option value="0">Gestor - Rastreador</option>
                        <option value="1">Chip de Dados</option>
                        <option value="2">Telemetria</option>
                        <option value="3">SIGA ME - NORIO MOMOI EPP</option>
                        <option value="4">Rastreamento Pessoal</option>
                        <option value="5">Gestão de Entregas</option>
                        <option value="6">Iscas</option>
                        <option value="7"><?=lang('licenca_uso_software')?></option>
                    </select>
                    <span class="help help-block"></span>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="control-label">Vendedor:</label>
                <select name="contrato[vendedor]" class="form-control pesq_vendedor" required>
                    <option value="">Selecione Vendedor</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?= $usuario->id?>"><?=$usuario->nome?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="display-eqp form-group col-md-6" style="display: none;">
                <label>Tipo Equipamento:</label>
                <select name="contrato[tipo_eqp]" class="form-control">
                    <option value="">Selecione o Tipo Equipamento</option>
                    <option>Tornozeleiras</option>
                    <option>Dispositivos S.O.S</option>
                </select>
            </div>
        </div>
    </div>

    <div class="dados_contrato">
        <label style="font-size: 15px">Dados do Contrato</label>
        <div class="row">
            <div class="form-group col-md-4">
                <label>Duração do contrato:</label>
                <select name="contrato[meses_contrato]" rel="tooltip" title="Para padrão (36 meses). Não alterar !" class="form-control" required>
                    <option value="36">Meses do Contrato (36)</option>
                    <?php for ($i=1; $i < 37; $i++) {
                        if ($i == 1) {
                            echo '<option value="'.$i.'">'.$i.' mês</option>';
                        }else{
                            echo '<option value="'.$i.'">'.$i.' meses</option>';
                        }
                    } ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Vencimento:</label>
                <select name="contrato[dia_vencimento]" rel="tooltip" title="Para padrão (dia 30). Não alterar !" class="form-control" required>
                    <option value="30">Dia Vencimento (dia 30)</option>
                    <?php for ($i=1; $i < 31; $i++) {
                        echo '<option value="'.$i.'">Dia '.$i.'</option>';
                    } ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Primeira mensalidade:</label>
                <input type="date" name="contrato[primeira_mensalidade]" value="<?php echo date('Y-m-d'); ?>" class="form-control" max="2999-12-31" required>
            </div>
        </div>
    </div>

    <div class="dados_veiculos">
        <div class="row">
            <div class="form-group col-md-6">
                <label id="label_numero_veiculos">Número de veículos:</label>
                <input type="text" id="numeros_veiculos" name="contrato[numeros_veiculos]" value="" class="form-control" placeholder="Número de Veículos" onkeypress="return SomenteNumero(event);" required>
            </div>
            <div class="form-group col-md-6">
                <label>Taxa de boleto:</label>
                <select name="contrato[taxa_boleto]" rel="tooltip" title="Para padrão (Sim). Não alterar !" class="form-control" required>
                    <option value="1">Tem taxa de Boleto? (Sim)</option>
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                </select>
            </div>
        </div>
    </div>

    <div class="dados_veiculos">
        <div class="row">
            <div class="form-group col-md-6">
                <label id="label_valor_mensal_veiculos">Valor mensal por veículo</label>
                <input type="text" id="mensal_por_veiculo" name="contrato[mensal_por_veiculo]"  value="" class="form-control" placeholder="Valor Mensal por Veículo" onKeyPress="return(MascaraMoeda(this,'.',',',event))" required>
            </div>
            <div class="form-group col-md-6">
                <label>Valor total mensal</label>
                <input type="text" id="total_mensal" name="contrato[total_mensal]" value="" class="form-control" placeholder="Total Mensal" readonly="readonly">
            </div>
        </div>
    </div>

    <div class="dados_contrato adesao_display">
        <label style="font-size: 15px">Adesão:</label>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Instalação por veículo</label>
                <input type="text" id="instalacao_por_veiculo" name="contrato[instalacao_por_veiculo]"  value="" class="form-control" placeholder="Valor Instalação por Veículo" onKeyPress="return(MascaraMoeda(this,'.',',',event))" required>
            </div>
            <div class="form-group col-md-6">
                <label>Total da instalação</label>
                <input type="text" id="total_instalacao" name="contrato[total_instalacao]" value="" class="form-control" placeholder="Total Instalação" readonly="readonly">
            </div>
        </div>
    </div>

    <div class="dados_contrato adesao_display">
        <div class="row">
            <div class="form-group col-md-4">
                <label>Quantidade de parcelas(instalação)</label>
                <input type="text" id="instalacao_parcelas" name="contrato[instalacao_parcelas]" value="" class="form-control" placeholder="Instalação em Parcelas" onkeypress="return SomenteNumero(event);" required>
            </div>
            <div class="form-group col-md-4">
                <label>Valor da parcela</label>
                <input type="text" id="valor_parcela_instalacao" name="contrato[valor_parcela_instalacao]" value="" class="form-control valor_parcela_instalacao" placeholder="Valor da Parcela" readonly="readonly">
            </div>
            <div class="form-group col-md-4">
                <label>Data da primeira parcela</label>
                <input class="form-control data_primeira_parcela" name="contrato[data_primeira_parcela]" type="date" value="<?php echo date('Y-m-d'); ?>" placeholder="Data da Primeira Parcela" required/>
            </div>
        </div>
    </div>

    <div class="dados_contrato">
        <label style="font-size: 15px">Multa Contratual:</label>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="form-check col-md-12" style="margin-left: 20px;">
                        <input class="sem_multa"type="radio" name="contrato[multa_contrato]" value="3">
                        <label>Sem Multa</label>
                    </div>
                    <div class="form-check col-md-12" style="margin-left: 20px;">
                        <input class="sem_multa" type="radio" name="contrato[multa_contrato]" value="1" checked>
                        <label class="radio-inblock"> Multa Proporcional ao Contrato</label>
                    </div>
                    <div class="form-check col-md-12" style="margin-left: 20px;">
                        <input class="com_multa" type="radio" name="contrato[multa_contrato]" value="2" >
                        <label class="radio-inblock">Multa Valor Negociado por Veiculo</label>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="contrato[valor_multa]" id="multa_cont" value="" class="form-control valor_multa" placeholder="Valor da Multa" onKeyPress="return(MascaraMoeda(this,'.',',',event))" readonly="readonly">
                    </div>
                </div>
            </div>
            <div class="dados_contrato col-md-6">
                <div class="row">
                    <div class="form-group col-md-8">
                        <label class="control-label">Data do Contrato:</label>
                        <input class="form-control data_contrato" name="contrato[data_contrato]" type="date" value="<?php echo date('Y-m-d'); ?>" placeholder="Data do Contrato" required/>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?php if ($ajuste): ?>
                    <input type="hidden" class="form-control" name="contrato[ajuste]" value="<?=$ajuste?>" placeholder="">
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">


    $(document).ready(function() {

        $('#tipo_cont').change(function () {
            if ($('#tipo_cont option:selected').val() == 4) {
                $('.adesao_display').removeAttr('style');
                $('.display-eqp').removeAttr('style');
                $('#label_numero_veiculos').text(lang.quantidade_pessoas_mes);
                $('#numeros_veiculos').attr('placeholder', lang.quantidade_pessoas_mes);
                $('#label_valor_mensal_veiculos').text(lang.valor_mensal_por_pessoa);
                $('#mensal_por_veiculo').attr('placeholder', lang.valor_mensal_por_pessoa);
                $('#instalacao_por_veiculo').attr('placeholder', 'Valor Instalação por Pessoa');

            } else if($('#tipo_cont option:selected').val() == 5) {
                $("#instalacao_parcelas").removeAttr('required');
                $("#instalacao_por_veiculo").removeAttr('required');
                $(".data_primeira_parcela").removeAttr('required');
                $('.display-eqp').attr('style', 'display:none');
                $('#label_numero_veiculos').text(lang.quantidade_pontos_mes);
                $('#numeros_veiculos').attr('placeholder', lang.quantidade_pontos_mes);
                $('#label_valor_mensal_veiculos').text(lang.valor_mensal_por_ponto);
                $('#mensal_por_veiculo').attr('placeholder', lang.valor_mensal_por_ponto);
                $('.adesao_display').attr('style', 'display: none;')

            } else if($('#tipo_cont option:selected').val() == 7) {
                $("#instalacao_parcelas").removeAttr('required');
                $("#instalacao_por_veiculo").removeAttr('required');
                $(".data_primeira_parcela").removeAttr('required');
                $('.display-eqp').attr('style', 'display:none');
                $('#label_numero_veiculos').text(lang.quantidade_veiculos_mes);
                $('#numeros_veiculos').attr('placeholder', lang.quantidade_veiculos_mes);
                $('#label_valor_mensal_veiculos').text(lang.valor_mensal_por_veiculo);
                $('#mensal_por_veiculo').attr('placeholder', lang.valor_mensal_por_veiculo);
                $('.adesao_display').attr('style', 'display: none;')

            } else {
                $("#instalacao_parcelas").attr('required');
                $("#instalacao_por_veiculo").attr('required');
                $(".data_primeira_parcela").attr('required');
                $('.adesao_display').removeAttr('style');
                $('.display-eqp').attr('style', 'display:none');
                $('#label_numero_veiculos').text(lang.quantidade_veiculos_mes);
                $('#numeros_veiculos').attr('placeholder', lang.quantidade_veiculos_mes);
                $('#label_valor_mensal_veiculos').text(lang.valor_mensal_por_veiculo);
                $('#mensal_por_veiculo').attr('placeholder', lang.valor_mensal_por_veiculo);
                $('.instalacao_por_veiculo').attr('placeholder', 'Valor Instalação por Veículo');
            }
        });

        $('.calendarioos').focus(function(){
            $(this).calendario({target: $(this)});
        });

        $("#mensal_por_veiculo").keypress(function(){

            var numeros_veiculos = $("#numeros_veiculos").val();
            var mensal_por_veiculo_parcial = $("#mensal_por_veiculo").val().replace('.', '');
            var mensal_por_veiculo = mensal_por_veiculo_parcial.replace(',', '.');

            var multi =  parseFloat(numeros_veiculos) * parseFloat(mensal_por_veiculo);

            $("#total_mensal").val((multi).formatMoney(2, ',', '.'));

        });

        $("#instalacao_por_veiculo").keypress(function(){

            var numeros_veiculos = $("#numeros_veiculos").val();
            var instalacao_por_veiculo_parcial = $('#instalacao_por_veiculo').val() ? $("#instalacao_por_veiculo").val().replace('.', '') : '0,00';
            var instalacao_por_veiculo = instalacao_por_veiculo_parcial.replace(',', '.');

            var multip =  parseFloat(numeros_veiculos) * parseFloat(instalacao_por_veiculo);

            $("#total_instalacao").val((multip).formatMoney(2, ',', '.'));

        });

        $(".data_primeira_parcela,.valor_parcela_instalacao,.data_contrato,.valor_multa").click(function(){

            var total_instalacao_parcial = $("#total_instalacao").val().replace('.', '');
            var total_instalacao = total_instalacao_parcial.replace(',', '.');
            var instalacao_parcelas = $("#instalacao_parcelas").val();

            var valor_parcelas = parseFloat(total_instalacao) / parseFloat(instalacao_parcelas);

            $("#valor_parcela_instalacao").val((valor_parcelas).formatMoney(2, ',', '.'));

        });

        $("[rel=tooltip]").tooltip({ placement: 'right'});

        $('.sem_multa').on('click', function(){
            $("#multa_cont").attr("readonly", true);
            $('#multa_cont').prop('required', false);

        });

        $('.com_multa').on('click', function(){
            $("#multa_cont").attr("readonly", false);
            $('#multa_cont').prop('required', true);

        });
    });

    function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
        var sep = 0;
        var key = '';
        var i = j = 0;
        var len = len2 = 0;
        var strCheck = '0123456789';
        var aux = aux2 = '';
        var whichCode = (window.Event) ? e.which : e.keyCode;
        if (whichCode == 13) return true;
        key = String.fromCharCode(whichCode); // Valor para o código da Chave
        if (strCheck.indexOf(key) == -1) return false; // Chave inválida
        len = objTextBox.value.length;
        for(i = 0; i < len; i++)
            if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
        aux = '';
        for(; i < len; i++)
            if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
        aux += key;
        len = aux.length;
        if (len == 0) objTextBox.value = '';
        if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
        if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
        if (len > 2) {
            aux2 = '';
            for (j = 0, i = len - 3; i >= 0; i--) {
                if (j == 3) {
                    aux2 += SeparadorMilesimo;
                    j = 0;
                }
                aux2 += aux.charAt(i);
                j++;
            }
            objTextBox.value = '';
            len2 = aux2.length;
            for (i = len2 - 1; i >= 0; i--)
                objTextBox.value += aux2.charAt(i);
            objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
        }
        return false;
    }

    function SomenteNumero(e){
        var tecla=(window.event)?event.keyCode:e.which;
        if((tecla>47 && tecla<58)) return true;
        else{
            if (tecla==8 || tecla==0) return true;
            else  return false;
        }
    }

    //Transforma numero em real//
    Number.prototype.formatMoney = function(c, d, t){
        var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".resultado").hide();
        $("#contratos").ajaxForm({
            target: '.resultado',
            dataType: 'json',
            success: function(retorno){
                $(".resultado").html(retorno.mensagem);
                $(".resultado").show();
            }
        });
    });

    $('.pesq_vendedor').select2({
        placeholder: "Selecione um vendedor",
    });

    $('#tipo_cont').select2({
        placeholder: "Selecione o tipo de contrato",
    });
</script>
