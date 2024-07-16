<section class="container-fluid no-print">
    <h3>Resumo de Fatura por Disponibilidade</h3>
    <div id="divFatura">
        <label>Digite o nº da Fatura, ou do contrato, ou o nome do cliente </label>
        <input type="text" name="fatura" id="faturas" class="span6" placeholder="Fatura - Contrato - Cliente - Vencimento" data-provide="typeahead" data-source='<?php echo $faturas?>' data-items="6">
        <button id="btnSuccessFa" class="btn btn-success">Enviar</button>
    </div>
</section>
<section class="print">
    <h4>RESUMO - Fatura Gerada por Disponibilidade</h4>
</section>
<br>
<section id="sectionTable" class="container-fluid">
    <button class="btn btn-info no-print" id="print">Imprimir <i class="fa fa-print"></i></button><br><br>
    <table id="tableConsumo" class="table table-responsive table-bordered">
        <thead>
        <tr>
            <th class="span2">Data</th>
            <th>Quantidade de Veículos Monitorados</th>
            <th>Valor p/ Dia</th>
            <th class="no-print">Veículos Monitorados</th>
        </tr>
        </thead>
        <tbody id="tbody"></tbody>
    </table>
</section>
<script>
    $(document).ready(function() {
        $('#sectionTable').hide();
        var arrayBoards = [];
        var boards = [];
        $('#btnSuccessFa').click(function () {
            $('#sectionTable').hide();
            arrayBoards = [];
            $('#tbody').html('');
            var button = $(this);
            button.attr('disabled', true).text('Carregando...');
            //BUSCA OS DADOS DA FATURA ATRAVÉS DO ID DA FATURA
            var infos = $('#faturas').val().split('-');
            $.getJSON('getResumoDisponibilidade', {id: infos[0], dt_vencimento: infos[3], id_contrato: infos[1]}, function (callback) {
                if (callback.length > 0) {
                    $.each(callback, function (index, obj) {
                        arrayBoards = obj.placas_ativas.toString();
                        var tpl = [
                            '<tr>' +
                            '<td>'+obj.data+'</td>'+
                            '<td>'+obj.veic_monitorados+' Veículos</td>'+
                            '<td>R$ '+parseFloat(obj.valorDia)+'</td>'+
                            '<td class="no-print"><button id="visPlacas" class="btn" data-placas='+arrayBoards+' data-id="'+index+'">Visualizar</button></td>'+
                            '<tr/>'+
                            '<tr style="background: lightgrey">' +
                            '<td colspan="4">' +
                            '<ul style="list-style: none">' +
                            '<li id="trPl" class="trPlacas'+index+'">' +
                            '</li>' +
                            '</ul>' +
                            '</td>'+
                            '</tr>'
                        ].join();
                        $('#tableConsumo').append(tpl);
                        $('#sectionTable').show('1');
                        button.attr('disabled', false).text('Enviar');
                        $('.trPlacas'+index).hide();
                    });
                } else {
                    button.attr('disabled', false).text('Enviar');
                    $('#sectionTable').hide();
                    alert('Não existe dados referente a essa fatura ou a fatura nº:'+ infos[0] +' não existe!')
                }
            });
        });
        $(document).on('click', '#visPlacas', function () {
            var buttonVis = $(this);
            boards = JSON.parse(buttonVis.attr('data-placas'));
            var index = buttonVis.attr('data-id');
            $.each(boards, function (i, board) {
                var plac = [
                    '<label class="badge badge-inverse" style="margin: 5px">'+board.placa+'</label>'
                ].join();
                $('.trPlacas'+index).append(plac).show('1');
            });
            $(buttonVis).text('Esconder').addClass('esconde'+index).css('background', '#ca2e36');
            $(document).on('click', '.esconde'+index, function () {
                $('.trPlacas'+index).html('').hide('1');
                $(this).text('Visualizar').removeClass('esconde'+index).css('background', '#2b4eca');
            });
        });
        $(document).on('click', '#print', function () {
            window.print();
        });
    });
</script>