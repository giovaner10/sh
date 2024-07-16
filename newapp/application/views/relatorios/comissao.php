<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js" ></script>

<style>
    .pagination {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .paginate_button {
        float: left;
        border: 1px solid #D0D0D0;
    }

    .paginate_button a {
        display: block;
        padding: 10px;
        text-decoration: none
    }

    .total_comissao {
        font-size: 18px;
        font-weight: bolder;
    }
    
</style>

<h3>Relatório de Comissão</h3>
<hr>

<div class="alert alert-danger" style="display:none">
    <span id="msg_erro"></span>
</div>

<div class="well">
    <form method="get" accept-charset="utf-8">
        <span style="margin-right: 5px;">
            <select name="vendedor" class="" style="margin-top: -12px;margin-bottom: 0px;width: 250px;" id="vendedores">
                <option value="">Vendedores</option>
                <?php foreach($vendedores as $vendedor): ?>
                    <option value="<?php echo $vendedor->id; ?>"><?php echo $vendedor->nome; ?></option>
                <?php endforeach ?>
            </select>
        </span>
        <span class="input-group-addon" >
            <i class="fa fa-calendar" style="font-size: 22px;"></i>
        </span>
        <input class="datepicker" type="text" name="data_inicio" required placeholder="Data Início" autocomplete="off" id="dt_ini" />
        
        <span class="input-group-addon" style="margin-left: 5px;">
            <i class="fa fa-calendar" style="font-size: 22px;"></i>
        </span>
        <input class="datepicker2" type="text" name="data_fim" required placeholder="Data Fim" autocomplete="off" id="dt_fim" />
        
        <span class="input-group-addon" style="margin-left: 5px;">
            <i class="fa fa-percent" style="font-size: 20px;"></i>
        </span>
        <input id="porcent_adesao" type="number" name="adesao" required placeholder="Porcentagem da adesão" value="34"/>
        
        <span class="input-group-addon" style="margin-left: 5px;">
            <i class="fa fa-percent" style="font-size: 20px;"></i>
        </span>
        <input id="porcent_mensalidade" type="number" name="mensalidade" required placeholder="Porcentagem da mensalidade" value="63"/>

        <span class="input-group-addon" style="float: right;">
            <button id="gerar_relatorio" class="btn">
                <i class="icon-list-alt"></i> Gerar
            </button>
        </span>
    </form>
</div>

<div class="tabela table-responsive" style="display: none;">
    <span class="total_comissao"></span>
    <hr>
    
    <table id="tabela_comissao" class="table table-striped table-hover">
        <thead>
            <th class="span2">Contrato</th>
            <th class="span2">Cliente</th>
            <th class="span2">Veículos Contratados</th>
            <th class="span2">Instalados no período</th>
            <th class="span2">Valor Adesão</th>
            <th class="span2">Total Ad. Período</th>
            <th class="span2">Valor Mensalidade</th>
            <th class="span2">Total Mens. Período</th>
            <th class="span2">Comissão Adesão</th>
            <th class="span2">Comissão Mensalidade</th>
            <th class="span2">Total Comissão</th>
        </thead>
    </table>
</div>


<script>
    $(function() {
        var dic_comissao = {
            'url_comissao': "<?php echo site_url('relatorios/comissao') ?>"
        }

        var table = $('#tabela_comissao').DataTable({
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Qntd: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar:",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Avançar",
                    "next":       "Avançar",
                    "previous":   "Início"
                }
            },
        })

        function convertDate(data) {
            var dia = data.substring(0, 2);
            var mes = data.substring(3, 5);
            var ano = data.substring(6);
            
            var newDate = ano + "-" + mes + "-" + dia

            return newDate;
        }

        $('#gerar_relatorio').on('click', function(event) {
            event.preventDefault()
            $('.alert').css('display', 'none')
            
            if($('#vendedores').val() == "") {
                $('.alert').css('display', '')
                $('#msg_erro').html("Selecione um vendedor.")
            }
            else if($('#dt_ini').val() == "" || $('#dt_fim').val() == "") {
                $('.alert').css('display', '')
                $('#msg_erro').html("Informe um período válido.")
            }
            else if($('#porcent_adesao').val() == "") {
                $('.alert').css('display', '')
                $('#msg_erro').html("Informe a porcentagem de comissão da adesão.")
            }
            else if($('#porcent_mensalidade').val() == "") {
                $('.alert').css('display', '')
                $('#msg_erro').html("Informe a porcentagem de comissão da mensalidade.")
            }
            else {
                $('#gerar_relatorio').html("<i class='fa fa-spinner fa-spin'></i> Gerar")
                $('#gerar_relatorio').attr('disabled', 'true')
                var id_vendedor = $('#vendedores').val()
                var data_inicial = $('#dt_ini').val()
                var data_fim = $('#dt_fim').val()
                var percent_adesao = $('#porcent_adesao').val()
                var percent_mensalidade = $('#porcent_mensalidade').val()

                data_inicial = convertDate(data_inicial)
                data_fim = convertDate(data_fim)

                $.ajax({
                    type: 'GET',
                    url: dic_comissao['url_comissao'],
                    dataType: 'JSON',
                    data: {
                        'request': true,
                        'id_vendedor': id_vendedor,
                        'dt_inicial': data_inicial,
                        'dt_final': data_fim,
                        'percent_adesao': percent_adesao,
                        'percent_mensalidade': percent_mensalidade
                    },
                    success: function(comissoes) {
                        var total = 0
                        
                        table.clear().draw();
                        
                        comissoes.forEach(comissao => {
                            total += comissao.total_comissao
                            if(comissao.quantidade_instalados != 0) {
                                    table.row.add([
                                    comissao.id,
                                    comissao.nome,
                                    comissao.quantidade_veiculos,
                                    comissao.quantidade_instalados,
                                    convertMoeda(comissao.valor_instalacao),
                                    convertMoeda(comissao.total_instalacao),
                                    convertMoeda(comissao.valor_mensal),
                                    convertMoeda(comissao.total_mensalidade),
                                    convertMoeda(comissao.comissao_adesao),
                                    convertMoeda(comissao.comissao_mensalidade),
                                    convertMoeda(comissao.total_comissao)
                                ]).draw(false);
                            }                            
                        });
                        $('.total_comissao').html("Total: " + convertMoeda(total))
                        $('.tabela').show()
                        $('#gerar_relatorio').removeAttr('disabled')
                        $('#gerar_relatorio').html("<i class='icon-list-alt'></i> Gerar")
                    },
                    error: function(response) {
                        console.log("erro")
                    },
                })
            }
        })
        
        function convertMoeda(valor) {
            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valor);
        }
    }) 
</script>