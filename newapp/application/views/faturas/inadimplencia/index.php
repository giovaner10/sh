<!-- datapicket -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js "></script>
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></script>
        <script src="https://npmcdn.com/chart.js@2.7.2/dist/Chart.bundle.js"></script>
        <script>
        $(function() {
            $( "#calendarioini, #calendariofim" ).datepicker({
                //dateFormat: 'dd-mm-yy',
                showOn: "button",
                buttonImage: "<?=base_url();?>assets/inadimplencia/img/calendario.png",
                buttonImageOnly: true,
                changeMonth: true,
                changeYear: true,
                showButtonPanel:true,
                dateFormat: 'dd/mm/yy',
                dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
                dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
                dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
                monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
                todayNames: ['Hoje'],
            });
        });
        </script>

        <script type="text/javascript">
            var randomnb = function(){ return Math.round(Math.random()*300)};
        </script>


        <?php

            $ultima = 23;

            for($i=$ultima; $i>=0; $i--){
                if($valor[$i]->mes_atual != 0){
                    $dataCalamp = date('d/m/Y H:i:s', strtotime($valor[$i]->data));
                    continue;
                }
            }

            for($i=$ultima; $i>=0; $i--){
                if($valor[$i]->mes_anterior != 0){
                    $dataMaxtrack = date('d/m/Y H:i:s', strtotime($valor[$i]->data));
                    continue;
                }
            }

            for($i=$ultima; $i>=0; $i--){
                if($valor[$i]->doze_meses != 0){
                    $dataContinental = date('d/m/Y H:i:s', strtotime($valor[$i]->data));
                    continue;
                }
            }
        ?>


<h3><?=lang('inadimplencias')?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('departamentos')?> >
    <?=lang('financeiro')?> >
    <a href="<?=site_url('faturas/inadimplencia/index')?>"><?=lang('inadimplencias')?></a> 
    
</div>

<hr>

        <div id="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                <!--div class="<col-sm-10 col-xs-offset-1"-->
                    <div class="panel panel-primary painel animated fadeInDown" >
                    <div class="panel-heading" style="background-color: #000000;">
                        <header style="height: 50px; display: flex; align-items: center; justify-content: center;">
                            <h3 class="panel-title text-center"><i class="fa fa-line-chart" aria-hidden="true" style="color: white;"></i><strong style="color: white; margin: 0;"> Monitoramento de Faturas - Gráfico de Inadimplências</strong></h3>
                        </header>
                    </div>

                        <div class="panel-body corpo">                           
                            <section>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="calamp" style="margin-bottom: 25px; padding: 10px 0; background-color: green; border-radius: 5px; color: #FFFFFF; text-align: center;">
                                            <strong>Mês Atual -</strong> <?php echo '( <strong>' . round($valor[0]->mes_atual, 2) . ' % </strong>) - {' . $dataCalamp . '}'; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="maxtrack" style="margin-bottom: 25px; padding: 10px 0; background-color: blue; border-radius: 5px; color: #FFFFFF; text-align: center;">
                                            <strong>Mês Anterior -</strong> <?php echo '( <strong>' . round($valor[0]->mes_anterior, 2) . ' % </strong>) - {' . $dataMaxtrack . '}'; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="continental" style="margin-bottom: 25px; padding: 10px 0; background-color: red; border-radius: 5px; color: #FFFFFF; text-align: center;">
                                            <strong>Ano -</strong> <?php echo '( <strong>' . round($valor[0]->doze_meses, 2) . ' % </strong>) - {' . $dataContinental . '}'; ?>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <div class="span12 grafico">
                                    <div class="box">
                                        <div class="box-chart">

                                            <canvas id="GraficoLine" style="position: relative; height:50vh; width:96vw"></canvas>

                                            <script type="text/javascript">
                                            window.onload = function(){
                                                var ctx = document.getElementById("GraficoLine");
                                                myChart = new Chart(ctx, {
                                                    type: 'line',
                                                    data: {
                                                        labels: [
                                                            <?php
                                                                for($i=$ultima; $i>=0; $i--){
                                                                    echo '"' . date('(d/m) - H:i', strtotime($valor[$i]->data)) . '", ';
                                                                }
                                                            ?>],
                                                        datasets: [
                                                            {
                                                                label: "Mês atual",
                                                                fill: false,
                                                                borderColor: 'green',
                                                                data: [
                                                                <?php
                                                                    for($i=$ultima; $i>=0; $i--){
                                                                        echo (round($valor[$i]->mes_atual, 11) . ', ');
                                                                    }
                                                                ?>
                                                                ]
                                                            },
                                                            {
                                                                label: "Mês anterior",
                                                                fill: false,
                                                                borderColor:'blue',
                                                                data: [
                                                                <?php
                                                                    for($i=$ultima; $i>=0; $i--){
                                                                        echo (round($valor[$i]->mes_anterior, 11) . ', ');
                                                                    }
                                                                ?>
                                                                ]
                                                            },
                                                            {
                                                                label: "Ano",
                                                                fill: false,
                                                                borderColor:'red',
                                                                data: [
                                                                <?php
                                                                    for($i=$ultima; $i>=0; $i--){
                                                                        echo (round($valor[$i]->doze_meses, 11) . ', ');
                                                                    }
                                                                ?>
                                                                ]
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
                                                            custom: function(tooltip) {
                                                                if (!tooltip) return;
                                                                // disable displaying the color box;
                                                                tooltip.displayColors = false;
                                                            },
                                                            callbacks: {
                                                                label: function(tooltipItem, data) {
                                                                    var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
                                                                    var label = data.labels[tooltipItem.index];
                                                                    var data_separado = tooltipItem.xLabel.split('/');
                                                                    return [
                                                                        data.datasets[0].label+": "+data.datasets[0].data[tooltipItem.index].toFixed(2)+ " %",
                                                                        data.datasets[1].label+": "+data.datasets[1].data[tooltipItem.index].toFixed(2)+ " %",
                                                                        data.datasets[2].label+": "+data.datasets[2].data[tooltipItem.index].toFixed(2)+ " %",

                                                                    ];
                                                                }
                                                            }
                                                        }
                                                    },
                                                    responsive:true,
                                                    maintainAspectRatio: false
                                                });
                                            }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section style="background-color: #bbdefb; text-align: center;">
                                <form action="<?= base_url()?>index.php/faturas/geraRelatorio" method="POST">
                                    <div class="container">
                                        <div class="row" style="margin-left: 280px;">
                                            <div class="col-md-3 offset-md-2">
                                                <div class="form-group">
                                                    <label for="calendarioini"><strong>Início:</strong></label>
                                                    <input required type="text" id="calendarioini" name="inicio" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3 offset-md-2">
                                                <div class="form-group">
                                                    <label for="calendariofim"><strong>Fim:</strong></label>
                                                    <input required type="text" id="calendariofim" name="fim" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2 offset-md-2">
                                                <div class="form-group">
                                                    <button class="btn btn-success btn-block" type="submit" style="margin-top: 22px;">Gerar Relatório</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </section>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
