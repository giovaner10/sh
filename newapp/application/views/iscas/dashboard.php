
<div class="row justify-content-center">
    <div class="col-sm-12">
        <div class="dashboard-container">
            <!-- Título -->
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <h2 ><?= $titulo?></h2>
                </div>    
            </div>
            <hr class="divisor">
            <!-- Row Eventos Emergencias -->
            <div class="row">
                <!-- Eventos -->
                <div class="col-sm-2">
                    <div class="well alert" title="Total de Eventos diários">
                        <div class="row">
                            <div class="col-sm-8">
                                    <div class="well-titulo" style="font-weight: bold;">Eventos</div>
                                    <div class="well-info" id="eventos">-</div>
                            </div>
                            <div class="col-sm-4">
                            <span class="material-icons md-36">flag</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Emergencias -->
                <div class="col-sm-2">
                    <div class="well danger" title="Total de Emergências diárias">
                        <div class="row">
                            <div class="col-sm-8">
                                    <div class="well-titulo" style="font-weight: bold;">Emergências</div>
                                    <div class="well-info" id="emergencia">-</div>
                            </div>
                            <div class="col-sm-4">
                            <span class="material-icons md-36">warning</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Row Eventos Emergencias -->

            <hr class="divisor">

            <!-- Row Info Usuários -->
            <div class="row">
                <!-- Veículos Ativos - Inativos -->
                <div class="col-sm-2">
                    <div class="well primary-rounded" title="Número total de iscas" style="height: 160px;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="well-rounded-titulo">Iscas</div>
                                <div class="well-info" id="totalIscas">-</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="well-rounded-titulo">- Iscas Ativas</div>
                                <div class="well-info" id="iscasAtivas">-</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="well-rounded-titulo">- Iscas Inativas</div>
                                        <div class="well-info" id="iscasInativas">-</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="material-icons md-36" >directions_car</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Usuários Web/ Chave App -->
                <!-- Usuários Web -->
                <div class="col-sm-2">
                <div class="well primary-rounded" title="Usuários Web Ativos - Gestor" style="height: 160px;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="well-rounded-titulo">Usuários Web Gestor</div>
                                <div class="well-info" id="totalUsuariosWeb">-</div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: -10px;">
                            <div class="col-sm-12">
                                <div class="well-rounded-titulo">- Contas</div>
                                <div class="well-info" id="totalUsuariosWebGestor">-</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="well-rounded-titulo">- Subcontas</div>
                                        <div class="well-info" id="totalUsuariosWebSubconta">-</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="material-icons md-36">public</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Clientes/Agendamento -->
                <!-- clientes -->
                <div class="col-sm-2">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="well primary-rounded" title="Clientes que possuem iscas">
                                <div class="row">
                                    <div class="col-sm-8">
                                            <div class="well-rounded-titulo">Clientes</div>
                                            <div class="well-info" id="numClientes">-</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="material-icons md-36">groups</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estoque -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="well primary-rounded" title="Iscas em estoque">
                                <div class="row">
                                    <div class="col-sm-8">
                                            <div class="well-rounded-titulo">Iscas em Estoque</div>
                                            <div class="well-info" id="iscasEstoque">-</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="material-icons md-36" style="margin-top:15px">move_to_inbox</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Agendamento -->
                    <!-- <div class="row">
                        <div class="col-sm-12">
                            <div class="well primary-rounded" title="Agendamentos diário">
                                <div class="row">
                                    <div class="col-sm-8">
                                            <div class="well-rounded-titulo">Agendamento <?php echo date("d/m/Y") ?></div>
                                            <div class="well-info">-</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="material-icons md-36" style="bottom: -35px;">today</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>

                <!-- Instaladores/Estoque -->
                <!-- Instaladores -->
                <div class="col-sm-2">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="well primary-rounded" title="Instaladores">
                                <div class="row">
                                    <div class="col-sm-8">
                                            <div class="well-rounded-titulo">Instaladores</div>
                                            <div class="well-info" id="instaladores">-</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="material-icons md-36">engineering</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instaladores/Estoque -->
                <!-- Instaladores -->
                <div class="col-sm-2">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="well primary-rounded" title="Usuários Administradores">
                                <div class="row">
                                    <div class="col-sm-8">
                                            <div class="well-rounded-titulo">User Admin.</div>
                                            <div class="well-info" id="usuariosAdmin">-</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="material-icons md-36">person</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Estoque -->
                    <!-- <div class="row">
                        <div class="col-sm-12">
                            <div class="well primary-rounded" title="Devoluções de iscas">
                                <div class="row">
                                    <div class="col-sm-8">
                                            <div class="well-rounded-titulo">Devoluções</div>
                                            <div class="well-info">-</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="material-icons md-36">unarchive</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>

                

                
                <!-- OS -->
                <div class="col-sm-2">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="well primary-rounded" title="Ordens de Serviço">
                                <div class="row">
                                    <div class="col-sm-8">
                                            <div class="well-rounded-titulo">OS</div>
                                            <div class="well-info" id="os">-</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <span class="material-icons md-36">insert_drive_file</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Row Info Usuários -->

            <hr class="divisor">

            <!-- row info Equipamentos -->
            <div class="row">
                <!-- Comunicado -->
                <div class="col-sm-3">
                    <div class="well success-rounded" title="Iscas que estão comunicando">
                        <div class="row">
                            <div class="col-sm-8">
                                    <div class="well-rounded-titulo" >Comunicando</div>
                                    <br>
                                    <div class="well-info" id="comunicado">-</div>
                            </div>
                            <div class="col-sm-4">
                                <span class="material-icons md-36 right">router</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sem comunicação 24 -->
                <div class="col-sm-3">
                    <div class="well secondary-rounded" title="Iscas sem comunicação há 24 horas">
                        <div class="row">
                            <div class="col-sm-8">
                                    <div class="well-rounded-titulo">Sem comunicação há 24 horas</div>
                                    <div class="well-info" id="semComunicacao24Horas">-</div>
                            </div>
                            <div class="col-sm-4">
                                <span class="material-icons md-36 right">router</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- sem comunicação 2 dias -->
                <div class="col-sm-3">
                    <div class="well alert-rounded" title=" Iscas sem comunicação há 2 dias">
                        <div class="row">
                            <div class="col-sm-8">
                                    <div class="well-rounded-titulo">Sem comunicação há 2 dias</div>
                                    <br>
                                    <div class="well-info" id="semComunicacao2Dias">-</div>
                            </div>
                            <div class="col-sm-4">
                                <span class="material-icons md-36 right">router</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sem comunicação +2 dias -->
                <div class="col-sm-3">
                    <div class="well danger-rounded" title="Iscas sem comunicação há mais de dois dias">
                        <div class="row">
                            <div class="col-sm-8">
                                    <div class="well-rounded-titulo">Sem comunicação há mais de 2 dias</div>
                                    <div class="well-info" id="semComunicacaoMais2Dias">-</div>
                            </div>
                            <div class="col-sm-4">
                                <span class="material-icons md-36 right">router</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end row info Equipamentos -->

            <hr class="divisor">

            <!-- Row Gráficos Iscas -->
            <div class="row row-card-margin">
                <!-- Gráfico Comunicação das iscas -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            COMUNICAÇÃO DAS ISCAS <span class="span-load-grafico"></span>
                        </div>
                        <div class="card-body">
                            <div id="graficoComunicacaoIscas"></div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico - Iscas ativas ao longo do ano -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            ISCAS ATIVAS AO LONGO DO ANO <span class="span-load-grafico"></span>
                        </div>
                        <div class="card-body">
                        <div id="graficoIscasAtivasAno"></div>
                        </div>
                    </div>
                </div>
            </div><!-- end Row Gráficos Iscas -->

            <div class="row row-card-margin">
                <!-- Gráfico - DESVINCULAÇÕES AO LONGO DO ANO -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            DESVINCULAÇÕES AO LONGO DO ANO <span class="span-load-grafico"></span>
                        </div>
                        <div class="card-body">
                            <div id="graficoDesvinculacoesAno"></div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico Comunicação das iscas -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            MANUTENÇÕES AO LONGO DO ANO <span class="span-load-grafico"></span>
                        </div>
                        <div class="card-body">
                            <div id="graficoHistoricoOs"></div>
                        </div>
                    </div>
                </div>

                
            </div>

        </div><!-- end dashboard-container -->
    </div>
</div><!-- end row dashboard -->

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('media/css/iscas-administrativo.css');?>">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
    const meses = [
        'JAN',
        'FEV',
        'MAR',
        'ABR',
        'MAI',
        'JUN',
        'JUL',
        'AGO',
        'SET',
        'OUT',
        'NOV',
        'DEZ',
    ]
    
    // Inicializa a lib google charts
    google.charts.load("current", {packages:['line',"corechart"]});

    // google.charts.setOnLoadCallback(drawgraficoHistoricoOs);
  
    // Animação carregando informações
    loadingInfosView('<i class="fa fa-spinner fa-spin"></i>');

    // faz a primeira requisição para a api
    getDadosDashboard();
    // Faz requisições para a api a cada 30s
    setInterval(()=>{
        getDadosDashboard();
    }, 60000);
    
    function getDadosDashboard() {
        $.ajax({  
            type: "GET",  
            url: '<?= site_url("iscas/isca/ajaxDadosDashboard") ?>',
            success: function(res) {  
                response = JSON.parse(res);
                
                if(response.status == true){
                    dados = response.dados
                    atualizaInfosView(dados);
                    

                    // Atualiza gráfico Comunicação das Iscas
                    google.charts.setOnLoadCallback( () =>{
                        const container = document.querySelector('#graficoComunicacaoIscas');
                        // informações que serão plotadas no gráfico
                        
                        const data = new google.visualization.arrayToDataTable([
                            ['Character', 'Iscas'],
                            ['Comunicando', dados.comunicado],
                            ['Sem comunicação nas últimas 24h', dados.semComunicacao24Horas],
                            ['Sem comunicação há 2 dias', dados.semComunicacao2Dias],
                            ['Sem comunicação há mais de 2 dias', dados.semComunicacaoMais2Dias],
                    
                        ]);
                        const formatter = new google.visualization.NumberFormat(
                            {pattern: '#####'}
                        )
                        formatter.format(data, 1);
                        const options = {
                            height: 300,
                            pieHole: 0.5,
                            slices: {
                                0: {color: '#009900'},
                                1: {color: '#808080'},
                                2: {color: '#ffae00'},
                                3: {color: '#e60000'},
                            },

                        }
                        const chart = new google.visualization.PieChart(container);
                        chart.draw(data, options);
                    });

                    // Atualiza Gráfico Histórico de iscas
                    google.charts.setOnLoadCallback(()=>{
                        const container = document.querySelector('#graficoIscasAtivasAno');
                        // informações que serão plotadas no gráfico
                        const data = new google.visualization.DataTable();
                        data.addColumn('string', 'Meses');
                        data.addColumn('number', 'Iscas');
                        
                        let row = [];
                        for(let i = 0; i < dados.historicoComunicacao.length; i ++){
                            row.push([meses[i],dados.historicoComunicacao[i]]);
                        }
                        
                        data.addRows(row);                    
                        const formatter = new google.visualization.NumberFormat(
                            {pattern: '#####'}
                        )
                        formatter.format(data, 1);
                        const options = {
                            height: 300,
                            colors: ['#009900'],

                        }
                        const chart = new google.visualization.ColumnChart(container);
                        chart.draw(data, options);
                    });

                    // Atualiza gráfico de desvinculação de iscas
                    google.charts.setOnLoadCallback(()=>{
                        const container = document.querySelector('#graficoDesvinculacoesAno');
                        // informações que serão plotadas no gráfico

                        const data = new google.visualization.DataTable();
                        data.addColumn('string', 'Meses');
                        data.addColumn('number', 'Desvinculações');
                        let row = [];
                        for(let i = 0; i < dados.historicoDesvinculacaoIscasAno.length; i ++){
                            row.push([meses[i],dados.historicoDesvinculacaoIscasAno[i]]);
                        }
                        
                        data.addRows(row);   
                        const formatter = new google.visualization.NumberFormat(
                            {pattern: '#####'}
                        )
                        formatter.format(data, 1);
                        const options = {
                            height: 300,
                            colors: ['#e60000'],

                        }
                        const chart = new google.visualization.ColumnChart(container);
                        chart.draw(data, options);
                    });

                    // Atualiza Gráfico de OS
                    google.charts.setOnLoadCallback(()=>{
                        const container = document.querySelector('#graficoHistoricoOs');
                        // informações que serão plotadas no gráfico

                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Meses');
                        data.addColumn('number', 'Em Aberto');
                        data.addColumn('number', 'Executado');
                        data.addColumn('number', 'Cancelado');
                        data.addColumn('number', 'Visita Frustrada');
                        data.addColumn('number', 'Com Pendências');

                        let row = [];
                        for(let i = 0; i < 12; i ++){
                            row.push([
                                meses[i],
                                dados.os.historicoAnualOs.em_aberto[i], 
                                dados.os.historicoAnualOs.executado[i],
                                dados.os.historicoAnualOs.cancelado[i],
                                dados.os.historicoAnualOs.visita_frustrada[i],
                                dados.os.historicoAnualOs.com_pendencias[i],
                            ]);
                        }
                        
                        data.addRows(row);   

                        const formatter = new google.visualization.NumberFormat(
                            {pattern: '#####'}
                        )
                        formatter.format(data, 1);
                        const options = {
                            height: 300,
                            colors: ['#4285f4','#0f9d58','#db4437','#f4b400','#ab47bc']
                        }

                        var chart = new google.charts.Line(container);

                        chart.draw(data, google.charts.Line.convertOptions(options));

                    });

                }else{
                    alert(response.msg);
                    // Retira animação caso não consiga carregar os dados
                    loadingInfosView('-');
                }
            }  ,
            error: function (){
                alert("Erro ao carregar dados do Dashboard");
            }
        });	
    }
    '<span class="sr-only">Loading...</span>'
    function loadingInfosView(htmlSpinner){
        $("#totalIscas").html(htmlSpinner);
        $("#iscasAtivas").html(htmlSpinner);
        $("#iscasInativas").html(htmlSpinner);
        $("#iscasEstoque").html(htmlSpinner);
        $("#numClientes").html(htmlSpinner);
        $("#comunicado").html(htmlSpinner);
        $("#semComunicacao24Horas").html(htmlSpinner);
        $("#semComunicacao2Dias").html(htmlSpinner);
        $("#semComunicacaoMais2Dias").html(htmlSpinner);
        $("#eventos").html(htmlSpinner);
        $("#emergencia").html(htmlSpinner);
        $("#instaladores").html(htmlSpinner);
        $("#os").html(htmlSpinner);
        $("#usuariosAdmin").html(htmlSpinner);
        $("#totalUsuariosWeb").html(htmlSpinner);
        $("#totalUsuariosWebGestor").html(htmlSpinner);
        $("#totalUsuariosWebSubconta").html(htmlSpinner);

        $(".span-load-grafico").html(htmlSpinner);

    }
    function atualizaInfosView(data){
        $("#totalIscas").html(data.iscasAtivas + data.iscasInativas);
        $("#iscasAtivas").html(data.iscasAtivas);
        $("#iscasInativas").html(data.iscasInativas);
        $("#iscasEstoque").html(data.iscasEstoque);
        $("#numClientes").html(data.numClientes);
        $("#comunicado").html(data.comunicado);
        $("#semComunicacao24Horas").html(data.semComunicacao24Horas);
        $("#semComunicacao2Dias").html(data.semComunicacao2Dias);
        $("#semComunicacaoMais2Dias").html(data.semComunicacaoMais2Dias);
        $("#eventos").html(data.numEventos);
        $("#emergencia").html(data.numEmergencias);
        $("#instaladores").html(data.instaladores);
        $("#os").html(data.os.num_os);
        $("#usuariosAdmin").html(data.numUsuariosAdministrativos);
        $("#totalUsuariosWeb").html(data.numUsuariosAtivos.usuariosGestor + data.numUsuariosAtivos.usuariosSubconta);
        $("#totalUsuariosWebGestor").html(data.numUsuariosAtivos.usuariosGestor);
        $("#totalUsuariosWebSubconta").html(data.numUsuariosAtivos.usuariosSubconta);
        

        // Limpa o spinner dos graficos
        $(".span-load-grafico").html("");

    }

    /**
     * Função que desenha o grafico com as manutenções ao longo do ano
     */
    // function drawgraficoHistoricoOs(){
    //     const container = document.querySelector('#graficoHistoricoOs');
    //     // informações que serão plotadas no gráfico

    //     const data = new google.visualization.DataTable();
    //     data.addColumn('string', 'Meses');
    //     data.addColumn('number', 'Manutenções');
    //     data.addRows(d);
        
    //     const options = {
    //         height: 300,
    //         colors: ['#0062ff'],

    //     }
    //     const chart = new google.visualization.ColumnChart(container);
    //     chart.draw(data, options);
    // }




</script>