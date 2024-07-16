
<style>
    .dash{
        display: flex;
        justify-content: center;
    }

</style>
<h3 style="padding: 0 20px;"><?=lang("dashboard_gerencial")?></h3>

<div class="div-caminho-menus-pais">
    <a href="<?=site_url('Homes')?>">Home</a> > 
    <?=lang('logistica')?> >
    <?=lang('dashboard_gerencial')?>
</div>

<div style="margin-left: 25px;">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="dashboard-container">
                <hr class="divisor">
                <!-- Cabeçalho -->
                <div class="row">
                    <div class="col-sm-2" >
                        <div class="well alert" title="Total de Eventos diários">
                            <div class="row">
                                <div class="col-sm-8">
                                        <div class="well-titulo" style="font-weight: bold;">Volume</div>
                                        <div class="well-info" id="Volume">-</div>
                                </div>
                                <div class="col-sm-4">
                                <span class="material-icons md-36">qr_code_scanner</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="well danger" title="Total de Emergências diárias">
                            <div class="row">
                                <div class="col-sm-8">
                                        <div class="well-titulo" style="font-weight: bold;">Valor</div>
                                        <div class="well-info" id="Valor">-</div>
                                </div>
                                <div class="col-sm-4">
                                <span class="material-icons md-36">monetization_on</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="well danger" title="Total de Emergências diárias">
                            <div class="row">
                                <div class="col-sm-8">
                                        <div class="well-titulo" style="font-weight: bold;">Adicional</div>
                                        <div class="well-info" id="Adicional">-</div>
                                </div>
                                <div class="col-sm-4">
                                <span class="material-icons md-36">currency_exchange</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="well danger" title="Total de Emergências diárias">
                            <div class="row">
                                <div class="col-sm-8">
                                        <div class="well-titulo" style="font-weight: bold;">Clientes</div>
                                        <div class="well-info" id="Clientes">-</div>
                                </div>
                                <div class="col-sm-4">
                                <span class="material-icons md-36">handshake</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="well danger" title="Total de Emergências diárias">
                            <div class="row">
                                <div class="col-sm-8">
                                        <div class="well-titulo" style="font-weight: bold;">UF</div>
                                        <div class="well-info" id="UF">-</div>
                                </div>
                                <div class="col-sm-4">
                                <span class="material-icons md-36">south_america</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="well danger" title="Total de Emergências diárias">
                            <div class="row">
                                <div class="col-sm-8">
                                        <div class="well-titulo" style="font-weight: bold;">Cidades</div>
                                        <div class="well-info" id="Cidades">-</div>
                                </div>
                                <div class="col-sm-4">
                                <span class="material-icons md-36">apartment</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="divisor">
                <div class="row justify-content-center">
                    <!-- Filtros -->
                    <div class="col-sm-2" style="margin-top: 20px; padding-right:40px;">
                        <div class="alert alert-danger" id="alert" role="alert" hidden></div>
                        <hr class="divisor">
                        <form id="formBuscar">
                            <div class="form-group">
                                <label>Pesquisar por:</label>
                                <select name="tiposMovimentacaoID[]" class="form-control select2" multiple="multiple">
                                    <option value="1">Movimentação</option>
                                    <option value="2">Instalação</option>
                                    <option value="3">Outros</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="date" name="dt_ini" class="data formatInput form-control" placeholder="Data Início" autocomplete="off" id="dp1" required value="<?=date('Y-m-d', strtotime('-1 months', strtotime(date('Y-m-d'))))?>"/> 
                            </div>
                            
                            <div class="form-group">
                                <input type="date" name="dt_fim" class="data formatInput form-control" placeholder="Data Fim" autocomplete="off" id="dp2" required value="<?=date('Y-m-d')?>"/>
                            </div>

                            <div class="form-group">
                                <button type="submit" id="BtnBuscarDash" class="btn btn-primary" style="width: 100%;" >
                                    <i class="icon-list-alt icon-white"></i> Buscar
                                </button>
                            </div>
                        </form>
                        
                        <hr class="divisor">
                    </div>
                    <!-- Paineis -->
                    <div class="col-sm-10 row justify-content-center">
                        <div>
                            <ul class="nav nav-tabs nav-justified" role="tablist" style="margin-bottom: 15px">
                                <li role="presentation" class="active"><a href="#volumeMes" aria-controls="volumeMes" role="tab" data-toggle="tab">Volume por Mês</a></li>
                                <li role="presentation"><a href="#volumeCliente" aria-controls="volumeCliente" role="tab" data-toggle="tab">Volume por Cliente</a></li>
                                <li role="presentation"><a href="#volumeSemana" aria-controls="volumeSemana" role="tab" data-toggle="tab">Volume por Semana</a></li>
                                <li role="presentation"><a href="#valorCidade" aria-controls="valorCidade" role="tab" data-toggle="tab">Valor por Tipo/Cidade</a></li>
                            </ul>
                            <!-- volumeMes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="volumeMes">
                                    <div class="row justify-content-center">
                                        <div id="GraficoVolumeMesTotal" class="dash"></div>
                                        <hr class="divisor">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-4">
                                                <div id="GraficoVolumeMesPublico" class="dash"></div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div id="GraficoVolumeMesPrivado" class="dash"></div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div id="GraficoVolumeMesOutros" class="dash"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- volumeCliente -->
                                <div role="tabpanel" class="tab-pane" id="volumeCliente">
                                    <div class="row justify-content-center">
                                        <div id="GraficoVolumeClientesTotal" class="dash"></div>
                                        <hr class="divisor">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-4">
                                                <div id="GraficoVolumeClientesPublico" class="dash"></div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div id="GraficoVolumeClientesPrivado" class="dash"></div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div id="GraficoVolumeClientesOutros" class="dash"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- volumeSemana -->
                                <div role="tabpanel" class="tab-pane" id="volumeSemana">
                                    <div class="row justify-content-center">
                                        <div id="GraficoVolumeSemanaTotal" class="dash"></div>
                                        <hr class="divisor">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-4">
                                                <div id="GraficoVolumeSemanaPublico" class="dash"></div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div id="GraficoVolumeSemanaPrivado" class="dash"></div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div id="GraficoVolumeSemanaOutros" class="dash"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- valorCidade -->
                                <div role="tabpanel" class="tab-pane" id="valorCidade">
                                    <div class="row justify-content-center"  style="margin-top: 20px; padding: 0px 40px;">
                                        <table id="tabela" class="display" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Tipo de Movimentação</th>
                                                    <th>Volume</th>
                                                    <th>Valor</th>
                                                    <th>Valor Adicional</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>Total Geral</th>
                                                    <th id = "VolumeTotalTable"></th>
                                                    <th id = "ValorTotalTable"></th>
                                                    <th id = "ValorAdicionalTotalTable"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>


<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!-- Biblioteca de dash -->
<script src="https://cdn.jsdelivr.net/npm/ag-charts-community/dist/ag-charts-community.min.js"></script>
<!-- Biblioteca datatable -->
<link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.dataTables.min.js');?>"></script> -->
<!-- <link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.material.min.css');?>"> -->


<script>
    
    $(document).ready(function(){
        $('.select2').select2({
            placeholder: 'Selecione os Tipos',
        });
        BuscarDados();

        $('#BtnBuscarDash').click(function(e){
            e.preventDefault();
            BuscarDados();
        })
    });

    function BuscarDados(){
        var dataform = $('#formBuscar').serialize();
        $.ajax({
            url: '<?= site_url('dashboards_logistica/buscarDadosDash') ?>',
            type: 'POST',
            data: dataform,
            dataType: 'json',
            success: function(data){
                if(data.status == 200){
                    dados = data.data
                    if(dados){
                        gerarGraficos(dados);
                        gerarTabela(dados.valores);
                        $("#alert").prop("hidden", true);
                    }else{
                        $("#alert").prop("hidden", false);
                        $("#alert").text("Desculpe, não foi encontrado dados nesse período!");
                        LimparGrafico();
                    }
                }else{
                    $("#alert").text("Desculpe, não foi encontrado dados nesse período!");
                    $("#alert").prop("hidden", false);
                    LimparGrafico();
                }
            },
            error: function(erro){
                $("#alert").text("Desculpe, não foi possivel completar a operação!");
                $("#alert").prop("hidden", true);
                LimparGrafico();
            }
        })
    }

    function gerarGraficos(dados){
        gerarCabecalho(dados.cabecalhoDashboard);

        //Mes
        gerarGraficoVolumeMesTotal(dados.volumePorMes.total);
        gerarGraficoVolumeMesPublico(dados.volumePorMes.publico);
        gerarGraficoVolumeMesPrivado(dados.volumePorMes.privado);
        gerarGraficoVolumeMesOutros(dados.volumePorMes.outros);

        //Cliente
        gerarGraficoVolumeClientesTotal(dados.volumePorClientes.total);
        gerarGraficoVolumeClientesPublico(dados.volumePorClientes.publico);
        gerarGraficoVolumeClientesPrivado(dados.volumePorClientes.privado);
        gerarGraficoVolumeClientesOutros(dados.volumePorClientes.outros);

        //Semana
        gerarGraficoVolumeSemanaTotal(dados.volumePorSemanaMes.total);
        gerarGraficoVolumeSemanaPublico(dados.volumePorSemanaMes.publico);
        gerarGraficoVolumeSemanaPrivado(dados.volumePorSemanaMes.privado);
        gerarGraficoVolumeSemanaOutros(dados.volumePorSemanaMes.outros);
    }

    function gerarCabecalho(dados) {
        $('#Volume').text(dados.quantidadeVolume)
        $('#Valor').text(dados.valorTotal)
        $('#Adicional').text(dados.valorAdicional)
        $('#Clientes').text(dados.quantidadeClientes)
        $('#UF').text(dados.quantidadeUF)
        $('#Cidades').text(dados.quantidadeCidades)
    }

    function LimparGrafico(){
        
        $('#GraficoVolumeMesTotal').html('')
        $('#GraficoVolumeMesPublico').html('')
        $('#GraficoVolumeMesPrivado').html('')
        $('#GraficoVolumeMesOutros').html('')
        $('#GraficoVolumeClientesTotal').html('')
        $('#GraficoVolumeClientesPublico').html('')
        $('#GraficoVolumeClientesPrivado').html('')
        $('#GraficoVolumeClientesOutros').html('')
        $('#GraficoVolumeSemanaTotal').html('')
        $('#GraficoVolumeSemanaPublico').html('')
        $('#GraficoVolumeSemanaPrivado').html('')
        $('#GraficoVolumeSemanaOutros').html('')
        $('#valorCidade').html('')
        $('#Volume').text('')
        $('#Valor').text('')
        $('#Adicional').text('')
        $('#Clientes').text('')
        $('#UF').text('')
        $('#Cidades').text('')

        if(TabelaCriada){
            table = table.destroy()
        }
    }

    //Mes
    function gerarGraficoVolumeMesTotal(dados){
        $('#GraficoVolumeMesTotal').html('')
        GraficoVolumeMesTotal = $('#GraficoVolumeMesTotal')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Todos',
            },
            container: GraficoVolumeMesTotal,
            // theme: 'ag-vivid',
            type: 'column',
            height: 240,
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'column',
                    xKey: 'mes',
                    yKey: 'volume',
                    fill: '#03A9F4',
                    strokeWidth: 0,
                    label: {
                        placement: 'outside'
                    },
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                }
            ],
            data: dados
        });
    }

    function gerarGraficoVolumeMesPublico(dados){
        $('#GraficoVolumeMesPublico').html('')
        GraficoVolumeMesPublico = $('#GraficoVolumeMesPublico')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Público',
            },
            container: GraficoVolumeMesPublico,
            theme: 'ag-solar',
            type: 'column',
            height: 100,
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'column',
                    xKey: 'mes',
                    yKey: 'volume',
                    fill: '#FEAB85',
                    strokeWidth: 0,
                    label: {
                        placement: 'outside'
                    },
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                }
            ],
            data: dados
        });
    }

    function gerarGraficoVolumeMesPrivado(dados){
        $('#GraficoVolumeMesPrivado').html('')
        GraficoVolumeMesPrivado = $('#GraficoVolumeMesPrivado')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Privado',
            },
            container: GraficoVolumeMesPrivado,
            theme: 'ag-default',
            type: 'column',
            height: 100,
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'column',
                    xKey: 'mes',
                    yKey: 'volume',
                    fill: '#F7DE6F',
                    strokeWidth: 0,
                    label: {
                        placement: 'outside'
                    },
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                }
            ],
            data: dados
        });
    }
    
    function gerarGraficoVolumeMesOutros(dados){
        $('#GraficoVolumeMesOutros').html('')
        GraficoVolumeMesOutros = $('#GraficoVolumeMesOutros')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Outros',
            },
            container: GraficoVolumeMesOutros,
            theme: 'ag-vivid',
            type: 'column',
            height: 100,
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'column',
                    xKey: 'mes',
                    yKey: 'volume',
                    fill: '#DCE6F2',
                    strokeWidth: 0,
                    label: {
                        placement: 'outside'
                    },
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                }
            ],
            data: dados
        });
    }

    //Cliente
    function gerarGraficoVolumeClientesTotal(dados){
        $('#GraficoVolumeClientesTotal').html('')
        GraficoVolumeClientesTotal = $('#GraficoVolumeClientesTotal')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Todos',
            },
            container: GraficoVolumeClientesTotal,
            // theme: 'ag-vivid',
            type: 'bar',
            height: 240,
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'bar',
                    xKey: 'cliente',
                    yKey: 'volumes',
                    fill: '#03A9F4',
                    strokeWidth: 0,
                    label: {
                        placement: 'outside'
                    },
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                }
            ],
            data: dados
        });
    }
    
    function gerarGraficoVolumeClientesPublico(dados){
        $('#GraficoVolumeClientesPublico').html('')

        GraficoVolumeClientesPublico = $('#GraficoVolumeClientesPublico')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Público',
            },
            container: GraficoVolumeClientesPublico,
            type: 'bar',
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'bar',
                    xKey: 'cliente',
                    yKey: 'volumes',
                    fill: '#FEAB85',
                    strokeWidth: 0,
                    label: {
                        placement: 'outside'
                    },
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                }
            ],
            data: dados
        });
    }

    function gerarGraficoVolumeClientesPrivado(dados){
        $('#GraficoVolumeClientesPrivado').html('')

        GraficoVolumeClientesPrivado = $('#GraficoVolumeClientesPrivado')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Privado',
            },
            container: GraficoVolumeClientesPrivado,
            theme: 'ag-default',
            type: 'bar',
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'bar',
                    xKey: 'cliente',
                    yKey: 'volumes',
                    fill: '#F7DE6F',
                    strokeWidth: 0,
                    label: {
                        placement: 'outside'
                    },
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                }
            ],
            data: dados
        });
    }
    
    function gerarGraficoVolumeClientesOutros(dados){
        $('#GraficoVolumeClientesOutros').html('')

        GraficoVolumeClientesOutros = $('#GraficoVolumeClientesOutros')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Outros',
            },
            container: GraficoVolumeClientesOutros,
            type: 'bar',
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'bar',
                    xKey: 'cliente',
                    yKey: 'volumes',
                    fill: '#DCE6F2',
                    strokeWidth: 0,
                    label: {
                        placement: 'outside'
                    },
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                }
            ],
            data: dados
        });
    }

    //Semana
    function gerarGraficoVolumeSemanaTotal(dados){
        const result = dados.filter(semana => semana.volume > 0);
        $('#GraficoVolumeSemanaTotal').html('')
        GraficoVolumeSemanaTotal = $('#GraficoVolumeSemanaTotal')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Todos',
            },
            container: GraficoVolumeSemanaTotal,
            type: 'pie',
            height: 240,
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'pie',
                    angleKey: 'volume',
                    calloutLabelKey: 'semana',
                    sectorLabelKey: 'porcentagem',
                    sectorLabel: {
                        color: 'white',
                        fontWeight: 'bold',
                    },
                    strokeWidth: 0,
                    fills: ['#568736','#68A242', '#90BB7A', '#BED5B4'],
                    innerRadiusRatio: 0.50,
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                    // calloutLabel: {
                    //     formatter: ({ datum, calloutLabelKey, angleKey, sectorLabelKey }) => {
                    //         return `${datum[calloutLabelKey]}: ${datum[angleKey]} (${datum[sectorLabelKey]})`;
                    //     }
                    // },
                },
            ],
            data: result
        });
    }

    function gerarGraficoVolumeSemanaPublico(dados){
        const result = dados.filter(semana => semana.volume > 0);

        $('#GraficoVolumeSemanaPublico').html('')
        GraficoVolumeSemanaPublico = $('#GraficoVolumeSemanaPublico')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Público',
            },
            container: GraficoVolumeSemanaPublico,
            type: 'pie',
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'pie',
                    angleKey: 'volume',
                    calloutLabelKey: 'semana',
                    sectorLabelKey: 'porcentagem',
                    sectorLabel: {
                        color: 'white',
                        fontWeight: 'bold',
                    },
                    strokeWidth: 0,
                    fills: ['#BA6124','#DE752D', '#F09971', '#F5C2B1'],
                    innerRadiusRatio: 0.50,
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                    // calloutLabel: {
                    //     formatter: ({ datum, calloutLabelKey, angleKey, sectorLabelKey }) => {
                    //         return `${datum[calloutLabelKey]}`;
                    //     }
                    // },
                },
            ],
            data: result
        });
    }

    function gerarGraficoVolumeSemanaPrivado(dados){
        const result = dados.filter(semana => semana.volume > 0);
        $('#GraficoVolumeSemanaPrivado').html('')
        GraficoVolumeSemanaPrivado = $('#GraficoVolumeSemanaPrivado')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Privado',
            },
            container: GraficoVolumeSemanaPrivado,
            type: 'pie',
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'pie',
                    angleKey: 'volume',
                    calloutLabelKey: 'semana',
                    sectorLabelKey: 'porcentagem',
                    sectorLabel: {
                        color: 'white',
                        fontWeight: 'bold',
                    },
                    strokeWidth: 0,
                    fills: ['#C89600','#EFB300', '#FFCA69', '#FFDDAD'],
                    innerRadiusRatio: 0.50,
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                    // calloutLabel: {
                    //     formatter: ({ datum, calloutLabelKey, angleKey, sectorLabelKey }) => {
                    //         return `${datum[calloutLabelKey]}`;
                    //     }
                    // },
                },
            ],
            data: result
        });
    }

    function gerarGraficoVolumeSemanaOutros(dados){
        const result = dados.filter(semana => semana.volume > 0);
        $('#GraficoVolumeSemanaOutros').html('')
        GraficoVolumeSemanaOutros = $('#GraficoVolumeSemanaOutros')[0];
        agCharts.AgChart.create({
            title: {
                text: 'Outros',
            },
            container: GraficoVolumeSemanaOutros,
            type: 'pie',
            autoSize: true, 
            legend: {
                enabled: false
            },
            series: [
                {
                    type: 'pie',
                    angleKey: 'volume',
                    calloutLabelKey: 'semana',
                    sectorLabelKey: 'porcentagem',
                    sectorLabel: {
                        color: 'white',
                        fontWeight: 'bold',
                    },
                    strokeWidth: 0,
                    fills: ['#335899','#3F6AB7', '#B3BEDF', '#7991CE'],
                    innerRadiusRatio: 0.50,
                    highlightStyle: {
                        item: {
                            fill: '#4682B4'
                        }
                    },
                    // calloutLabel: {
                    //     formatter: ({ datum, calloutLabelKey, angleKey, sectorLabelKey }) => {
                    //         return `${datum[calloutLabelKey]}`;
                    //     }
                    // },
                },
            ],
            data: result
        });
    }

    var table;
    var TabelaCriada = false;
    function gerarTabela(dados){
        $('#VolumeTotalTable').text(dados.volumeTotal.toLocaleString('pt-BR'))
        $('#ValorTotalTable').text(dados.valorTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}))
        $('#ValorAdicionalTotalTable').text(dados.adicionalTotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}))
        
        if(TabelaCriada){
            table = table.destroy()
        }

        table = $('#tabela').DataTable({
            data: dados.ValoresPorTipo,
            columns: [
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                { data: 'tipo' },
                { 
                    data: 'volume',
                    render: function (data, type) {
                        return data.toLocaleString('pt-BR');
                    },
                },
                { 
                    data: 'valor',
                    render: function (data, type) {
                        return data.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                    },
                },
                { 
                    data: 'valorAdicional',
                    render: function (data, type) {
                        return data.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                    }, 
                },
            ],
            order: [[1, 'asc']],
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar: ",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Próxima",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                }
            }
        });

        if(!TabelaCriada){
            $('#tabela').on('requestChild.dt', function (e, row) {
                row.child(format(row.data())).show();
            });
            
            $('#tabela tbody').on('click', 'td.dt-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });
        }

        TabelaCriada= true;
    }

    function format(d) {
        // `d` is the original data object for the row
        clientes = d.valoresClientes
        tabela = ''
        tabela +='<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" class="table table-responsive">' 
        tabela +=    '<thead>'
        tabela +=    '    <tr>'
        tabela +=    '        <th></th>'
        tabela +=    '        <th>Cliente</th>'
        tabela +=    '        <th>Volume</th>'
        tabela +=    '        <th>Valor</th>'
        tabela +=    '        <th>Valor Adicional</th>'
        tabela +=    '    </tr>'
        tabela +=    '</thead>'
        tabela +=    '<tbody>'
        clientes.forEach(element => {
            tabela +=    '    <tr>'
            tabela +=    '        <th></th>'
            tabela +=    '        <th>' + element.cliente + '</th>'
            tabela +=    '        <th>' + element.volume.toLocaleString('pt-BR') + '</th>'
            tabela +=    '        <th>' + element.valor.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) + '</th>'
            tabela +=    '        <th>' + element.valorAdicional.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) + '</th>'
            tabela +=    '    </tr>'
        });
        tabela +=    '</tbody>'
        tabela +=    '</table>'

        return tabela;
    }

</script>
<!-- Traduções -->        