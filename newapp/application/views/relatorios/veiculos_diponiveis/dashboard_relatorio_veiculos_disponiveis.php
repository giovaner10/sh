<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/select2.css">
<style>
    .table > tbody > tr > td {
        vertical-align: middle;
    }
    .overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        background: rgba(70,20,15,0.3);
        z-index: 2;
        background-image: url(<?=base_url()?>media/img/loading2.gif);
        background-repeat: no-repeat;
        background-position: center center;
        background-size: 100px;
    }
</style>
<!--<style>
    .table > tbody > tr > td {
        vertical-align: middle;
    }
    .loader {
        border: 10px solid #f3f3f3; /* Light grey */
        border-top: 10px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    thead, tbody { display: block; }

    tbody {
        height: 300px;       /* Just for the demo          */
        overflow-y: auto;    /* Trigger vertical scroll    */
        overflow-x: hidden;  /* Hide the horizontal scroll */
    }
</style>-->
<script>
    function daysInMonth (month, year) {
        return new Date(year, month, 0).getDate();
    }
    Number.prototype.formatMoney = function(c, d, t){
        var n = this, 
            c = isNaN(c = Math.abs(c)) ? 2 : c, 
            d = d == undefined ? "." : d, 
            t = t == undefined ? "," : t, 
            s = n < 0 ? "-" : "", 
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
</script>
<style>(float)$valor_m/cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"))
    .modal .modal-dialog { width: 100%; } 
    .entrou{
        background-color: #00ff0a12;
    }
    .saiu{
        background-color: #ff00000a;
    }
</style>
<h3>Dashboard de Veículos Disponíveis</h3>
<span class="label label-success">
Quantidade de veículos nos contratos: <b class="badge">
    <?php foreach(array_reverse($contratos_dia) as $c_dia){
        $soma = 0;
        foreach($c_dia as $c){
            $soma+=$contratos[$c]->quantidade_veiculos; 
        }
        echo $soma;
        break;
    }?></b>
</span>
<span class="label label-info">
Valor diário nos contratos: <b class="badge">R$ 
<?php foreach(array_reverse($contratos_dia) as $key => $c_dia){$soma = 0;foreach($c_dia as $c){$soma+=$contratos[$c]->valor_mensal;}$data = explode('/',$key);echo number_format($soma/cal_days_in_month(CAL_GREGORIAN, $data[1], $data[2]),2, ',', '.');break;}?>
</b></span>
<h5>Gráfico de faturamento diário</h5>
<form action="<?=base_url()?>index.php/relatorios/dashboardVeiculosDisponiveis" method="get">
    <input type="text" id="di" name="di" placeholder="Data de inicio" style="margin-bottom: 0px;" <?php if($this->input->get('di')){echo 'value="'.$this->input->get('di').'"';}else{echo 'value="'.data_for_humans(date('Y-m-d', strtotime("-30 days", strtotime(date('Y-m-d'))))).'"';}?>>
    <input type="text" id="df" name="df" placeholder="Data final" style="margin-bottom: 0px;" <?php if($this->input->get('di')){echo 'value="'.$this->input->get('df').'"';}else{echo 'value="'.data_for_humans($df = date("Y-m-d")).'"';}?>> 
    <select name="cliente" class="span6" style="margin-bottom: 0px;width: 300px;" id="clientes"?>>
        <option value="">Lista de Clientes</option>
        <!-- <?php if($clientes):?>
            <?php foreach($clientes as $cliente):?>
            <option value="<?php echo $cliente->id?>" <?php echo set_selecionado($cliente->id, $this->input->get('cliente'), 'selected')?> >
                <?php echo $cliente->id." - ".$cliente->nome; ?>
            </option>
            <?php endforeach;?>
        <?php endif;?> -->
    </select> 
    <button type="submit" class="btn">Filtrar</button>
</form>
  <meta charset="UTF-8">
  <title>Line Chart Test</title>
  
<body onload="displayLineChart();">
  <div class="box chart-container" style="position: relative; height:50vh; width:96vw">
    <canvas id="lineChart" style="position: relative; height:50vh; width:96vw"></canvas>
  </div>
</body>
<!-- Modal -->
<div id="detalheVeiculos" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="titulo_modal_tipo"></span> de veículos no dia <span id="dia_alteracoes1"></span></h4>
      </div>
      <div class="modal-body">
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col" style="width: 250px;">Cliente</th>
                        <th scope="col">Placa</th>
                        <th scope="col">Serial</th>
                        <th scope="col">Usuário</th>
                    </tr>
                </thead>
                <tbody id="alteracoes_veiculos_detalhe">
                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>

  </div>
</div>
<script>
    $('input[id$=di]').datepicker({
		format: 'dd/mm/yyyy'
	});
	$('input[id$=df]').datepicker({
		format: 'dd/mm/yyyy'
	});
</script>
<div id="load" style="display:none;" class="overlay"></div>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js "></script>
<script type="text/javascript" language="javascript" src="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></script>
<script src="https://npmcdn.com/chart.js@2.7.2/dist/Chart.bundle.js"></script>
<script language="JavaScript">
    var table_veic=false;
    var table_ranking=false;
    var table_contrato=false;
    var table_historico=false;
    var qtd_veic_cont=[<?php foreach($contratos_dia as $c_dia){$soma = 0;foreach($c_dia as $c){$soma+=$contratos[$c]->quantidade_veiculos; }echo "'".$soma."',";}?>];
    var datas_completa = [<?php foreach($veiculos_disponiveis as $v){echo "'".explode(" ",dh_for_humans($v->datahora))[0]."',"; }?>];
    var datas = [<?php foreach($veiculos_disponiveis as $v){echo "'".explode('/',explode(" ",dh_for_humans($v->datahora))[0])[0]."/".explode('/',explode(" ",dh_for_humans($v->datahora))[0])[1]."',"; }?>];
    var days_in_months = [<?php foreach($veiculos_disponiveis as $v){echo "daysInMonth(".(explode('/',explode(" ",dh_for_humans($v->datahora))[0])[1]).",".(explode('/',explode(" ",dh_for_humans($v->datahora))[0])[2])."),"; }?>];
    var qtd_valor_cont=[<?php $index=0;foreach($contratos_dia as $c_dia){$soma = 0;foreach($c_dia as $c){$soma+=$contratos[$c]->valor_mensal; }echo $soma."/days_in_months[".$index."],";$index++;}?>];
    var myChart;
    var dataclick=datas_completa.length-1;
    var date_temp = datas_completa[dataclick];
    function getAlteracoesVeiculos(id_cliente,op){
        if(op){
            $("#titulo_modal_tipo").html("Entrada");
        }
        else{
            $("#titulo_modal_tipo").html("Saída");
        }
        $("#alteracoes_veiculos_detalhe").html('');
        document.getElementById("load").style.display=null;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                $("#alteracoes_veiculos_detalhe").html('');
                document.getElementById("load").style.display="none";
                var json = JSON.parse(xhr.responseText);
                var index = 1;
                json.veic.forEach(function write(data){
                    linhas=[
                        '<tr>'+
                            '<td>'+index+'</td>'+
                            '<td>'+data.nome+'</td>'+
                            '<td>'+data.placa+'</td>'+
                            '<td>'+data.serial+'</td>'+
                            '<td>'+data.usuario+'</td>'+
                        '</tr>'
                    ];
                    index++;
                    $("#alteracoes_veiculos_detalhe").append(linhas.join());
                });
                $('#detalheVeiculos').modal();
            }
        }
        xhr.open('GET', '<?=base_url()?>index.php/relatorios/alteracoesVeiculosDisponiveis?data='+date_temp+'&id_cliente='+id_cliente+'&op='+op, true);
        xhr.send(null);
    }
    function displayLineChart() {
        var ctx = document.getElementById("lineChart");
        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: datas,
                datasets: [
                    {
                        label: "Valor diário",
                        fill: false,
                        borderColor: 'green',
                        data: [<?php foreach($veiculos_disponiveis as $v){echo "'".$v->valor_diario."',"; }?>]
                    },
                    {
                        label: "Quantidade de veículos",
                        fill: false,
                        borderColor:'blue',
                        data: [<?php foreach($veiculos_disponiveis as $v){echo "'".$v->contador."',"; }?>]
                    },
                    {
                        label: "Quantidade total de veículos",
                        fill: false,
                        borderColor:'red',
                        data: [<?php foreach($contratos_dia as $c_dia){$soma = 0;foreach($c_dia as $c){$soma+=$contratos[$c]->quantidade_veiculos; }echo "'".$soma."',";}?>]
                    },
                    {
                        label: "Valor total no contrato",
                        fill: false,
                        borderColor:'black',
                        data: qtd_valor_cont
                    }
                ]
            },
            options: {
                hover: {
                    mode: 'nearest',
                    intersect: false
                },
                tooltips: {
                    mode: 'nearest',
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
                            var label = data.labels[tooltipItem.index];
                            dataclick = tooltipItem.index;
                            var data_separado = tooltipItem.xLabel.split('/');
                            return [
                                data.datasets[0].label + ': R$ ' +(Number(data.datasets[0].data[tooltipItem.index])).formatMoney(2, ',', '.'),
                                data.datasets[1].label + ': ' +data.datasets[1].data[tooltipItem.index],
                                "Valor médio por veículo: R$ "+  (Number(data.datasets[0].data[tooltipItem.index])/Number(data.datasets[1].data[tooltipItem.index])).formatMoney(2, ',', '.'),
                                "Mensalidade por veículo: R$ "+  ((Number(data.datasets[0].data[tooltipItem.index])/Number(data.datasets[1].data[tooltipItem.index]))*days_in_months[tooltipItem.index]).formatMoney(2, ',', '.'),
                                "Valor total nos contratos: R$ "+  qtd_valor_cont[tooltipItem.index].formatMoney(2, ',', '.'),
                                "Qtd. de veículos nos contratos: "+  qtd_veic_cont[tooltipItem.index]
                            ];
                        }
                    }
                }
            },
            responsive:true,
            maintainAspectRatio: false,
            height:200
        });
    ctx.onclick = function (evt) {
        getDadosDate(evt);
    }
    function getDadosDate(evt) {
        //$("#modalDoDia").modal()
        $("#tabelas_dash").html('<center><div class="loader"></div></center>');
        $("#dia_ranking").html(datas_completa[dataclick]);
        $("#dia_contrato").html(datas_completa[dataclick]);
        $("#dia_comunicando").html(datas_completa[dataclick]);
        $("#dia_alteracoes").html(datas_completa[dataclick]);
        $("#dia_alteracoes1").html(datas_completa[dataclick]);
        date_temp = datas_completa[dataclick];
        $(".overlay").css("background-image",
            "url(<?=base_url()?>media/img/loading2.gif)",
        );
        document.getElementById("load").style.display=null;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                $("#data").html(date_temp);
                $("#data1").html(date_temp);
                $("#data2").html(date_temp);
                $("#data3").html(date_temp);
                document.getElementById("load").style.display="none";
                var json = JSON.parse(xhr.responseText);
                var index = 1;
                var array_veic=[];
                json.veic_entrou.forEach(function write(data){
                    array_veic.push([1,data.id_cliente,index,data.nome,data.contador,'<i class="fa fa-sign-in" style="cursor:pointer;font-size: 18px;" title="Entrou"></i>']);
                    index++;
                });
                json.veic_saiu.forEach(function write(data){
                    array_veic.push([0,data.id_cliente,index,data.nome,data.contador,'<i class="fa fa-sign-out" style="cursor:pointer;font-size: 18px;" title="Saiu"></i>']);
                    index++;
                   
                });
                if(table_veic){
                    table_veic.clear();
                    table_veic.rows.add(array_veic);
                    table_veic.draw();
                }
                else{
                    table_veic = $('#veic').DataTable( {
                        data: array_veic,
                        "scrollY":        "300px",
                        "scrollCollapse": true,
                        "paging":         false,
                        "searching": false,
                        columns: [
                            { "bVisible": false },
                            { "bVisible": false },
                            { title: "#" },
                            { title: "Cliente" },
                            { title: "Quantidade" },
                            { title: "",
                            orderable: false}
                        ],
                        "order": [[ 2, "asc" ]]
                    } );
                }
                $('#veic').on('click', 'tr', function () {
                    var data = table_veic.row( this ).data();
                    getAlteracoesVeiculos(data[1],data[0]);
                    
                } );
                var array_cliente=[];
                json.cliente_ranking.forEach(function write(data,index){
                    array_cliente.push([(index+1),data.nome,"R$ "+Number(data.valor).formatMoney(2, ',', '.')]);
                });
                
                if(table_ranking){
                    table_ranking.clear();
                    table_ranking.rows.add(array_cliente);
                    table_ranking.draw();
                }
                else{
                    table_ranking=$('#ranking').DataTable( {
                        data: array_cliente,
                        "scrollY":        "300px",
                        "scrollCollapse": true,
                        "paging":         false,
                        "searching": false,
                        "order": [[ 0, "asc" ]],
                        columns: [
                            { title: "#" },
                            { title: "Cliente" },
                            { title: "Valor",
                            orderable: false,
                            "width": "20%"}
                        ]
                    } );
                }
                
                var contrato=[];
                json.veic_contrato_real.forEach(function write(data,index){
                    if(data.diferenca_total!=0){
                        contrato.push([data.nome,data.diferenca_total]);
                    }
                });
                if(table_contrato){
                    table_contrato.clear();
                    table_contrato.rows.add(contrato);
                    table_contrato.draw();
                }
                else{
                    table_contrato=$('#contrato').DataTable( {
                        data: contrato,
                        "scrollY":        "300px",
                        "scrollCollapse": true,
                        "paging":         false,
                        "searching": false,
                        "order": [[ 1, "asc" ]],
                        columns: [
                            { title: "Cliente" },
                            { title: "Quantidade" }
                        ]
                    } );
                }
                var array_historico=[];
                json.veic_sem_vinculo.forEach(function write(data,index){
                    if(data.status == 2) {
                        data.status = "Enviado ao Cliente";
                    } else if (data.status == 3) {
                        data.status = "Disponivel no Cliente";
                    } else if (data.status == 4) {
                        data.status = "Instalado";
                    } else {
                        data.status = "Em Estoque";
                    }
                    array_historico.push([index+1,data.placa,data.serial,data.status]);
                });

                if(table_historico){
                    table_historico.clear();
                    table_historico.rows.add(array_historico);
                    table_historico.draw();
                }
                else{
                    table_historico=$('#historico').DataTable( {
                        data: array_historico,
                        "scrollY":        "300px",
                        "scrollCollapse": true,
                        "paging":         false,
                        "searching": false,
                        columns: [
                            { title: "#" },
                            { title: "Placa" },
                            { title: "Serial" },
                            { title: "Status atual do equipamento" }
                        ]
                    } );
                }
                $('.dataTables_scrollBody').css('height', '250px');
            }
        }
        xhr.open('GET', '<?=base_url()?>index.php/relatorios/tabelaDashboardVeiculosDisponiveis?data='+datas_completa[dataclick], true);
        xhr.send(null); 
        
    };
    getDadosDate(false);
  }
  </script>
  <br><br>
<center>
<div class="span6" style="box-shadow: 0px 0px 20px #999;">
    <center>
        <h5 style="margin-bottom: 0;">Valor diário por cliente</h5>
        <b id="data"></b>
    </center>
    <table id="ranking" class="display" width="100%"></table>
</div>
<div class="span6" style="box-shadow: 0px 0px 20px #999;">
    <center>
        <h5 style="margin-bottom: 0;">Alterações de veículos</h5>
        <b id="data1"></b>
    </center>
    <table id="veic" class="display" width="100%"></table>
</div>


<div class="span6" style="margin-top: 30px;box-shadow: 0px 0px 20px #999;">
    <center>
        <h5 style="margin-bottom: 0;">Diferença de veículos real/contrato</h5>
        <b id="data2"></b>
    </center>
    <table id="contrato" class="display" width="100%"></table>
</div>
<div class="span6" style="margin-top: 30px;box-shadow: 0px 0px 20px #999;">
    <center>
        <h5 style="margin-bottom: 0;">Veículos atualizado, sem vinculação</h5>
        <b id="data3"></b>
    </center>
    <table id="historico" class="display" width="100%"></table>
</div>
</center>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $("#clientes").select2({
            ajax: {
                url:  `<?= site_url('Relatorios/ajax_cliente')?>`,
                dataType: 'json',
                delay: 2000,
                data: function(params){
                    return {
                        q: params.term,
                    }
                }
            },
            placeholder: 'Selecione o Cliente',
            allowClear: false,
            minimumInputLength: 3,
            debug: true
        });
    });
</script>