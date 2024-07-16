<!-- Grafico -->
<script type="text/javascript" src="<?php echo base_url('assets/js/loader.js');?>"></script>
<style type="text/css">
    #preloader {
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
    }

    .loader {
        margin-top: 20%;
        margin-left: 45%;
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- CARREGA O GRAFICO -->
<div id="chart_div" style="height: 20%; width: 98%;"></div>
<h2 style="text-align: center;">DashBoard de Equipamentos</h2>

<div id="preloader"><div class="loader"></div></div>

<div class="container">
<!-- Gráfico individual -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel" style="width: 98%;">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <div id="continental" class="pull-left" style="width: 30%; height: 30%;"></div>
                <div id="maxtrack" class="pull-left" style="width: 30%; height: 30%;"></div>
                <div id="calamp" class="pull-left" style="width: 30%; height: 30%;"></div>
            </div>

            <div class="item">
                <div id="quanta" class="pull-left" style="width: 30%; height: 30%;"></div>
                <div id="svias" class="pull-left" style="width: 30%; height: 30%;"></div>
                <div id="suntech" class="pull-left" style="width: 30%; height: 30%;"></div>
            </div>

            <div class="item">
                <div id="spot" class="pull-left" style="width: 30%; height: 30%;"></div>
                <div id="queclink" class="pull-left" style="width: 30%; height: 30%;"></div>
                <div id="mix" class="pull-left" style="width: 30%; height: 30%;"></div>
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <i class="fa fa-angle-left" style="font-size: 70%;"></i>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <i class="fa fa-angle-right" style="font-size: 70%;"></i>
        </a>
    </div>
    <!-- Graficos por modelo -->
    <div id="ativos_sco" class="pull-left" style="width: 48%;"></div>
    <div id="ativos_mxt" class="pull-left" style="width: 48%;"></div>
    <div id="ativos_qrt" class="pull-left" style="width: 48%;"></div>
    <div id="ativos_svias" class="pull-left" style="width: 48%;"></div>
    <div id="ativos_cal" class="pull-left" style="width: 48%;"></div>
    <div id="ativos_st" class="pull-left" style="width: 48%;"></div>
    <div id="ativos_spot" class="pull-left" style="width: 48%;"></div>
    <div id="ativos_ql" class="pull-left" style="width: 48%;"></div>
    <div id="ativos_mix" class="pull-left" style="width: 48%;"></div>
</div> 


<script type="text/javascript">
// GRAFICO GERAL //
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawVisualization);

    function drawVisualization() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'TOTAL', 'ATIVOS', 'INATIVOS'],
            ['<?= $eqp_quantidades[0]->marca; ?>',  <?= $eqp_quantidades[0]->qntd; ?>,      <?= $eqp_status[0]->ativos; ?>,         <?= $eqp_quantidades[0]->qntd - $eqp_status[0]->ativos ?>],
            ['<?= $eqp_quantidades[1]->marca; ?>',  <?= $eqp_quantidades[1]->qntd; ?>,      <?= $eqp_status[1]->ativos; ?>,         <?= $eqp_quantidades[1]->qntd - $eqp_status[1]->ativos ?>],
            ['<?= $eqp_quantidades[2]->marca; ?>',  <?= $eqp_quantidades[2]->qntd; ?>,      <?= $eqp_status[2]->ativos; ?>,         <?= $eqp_quantidades[2]->qntd - $eqp_status[2]->ativos ?>],
            ['<?= $eqp_quantidades[3]->marca; ?>',  <?= $eqp_quantidades[3]->qntd; ?>,      <?= $eqp_status[3]->ativos; ?>,         <?= $eqp_quantidades[3]->qntd - $eqp_status[3]->ativos ?>],
            ['<?= $eqp_quantidades[4]->marca; ?>',  <?= $eqp_quantidades[4]->qntd; ?>,      <?= $eqp_status[4]->ativos; ?>,         <?= $eqp_quantidades[4]->qntd - $eqp_status[4]->ativos ?>],
            ['<?= $eqp_quantidades[5]->marca; ?>',  <?= $eqp_quantidades[5]->qntd; ?>,      <?= $eqp_status[5]->ativos; ?>,         <?= $eqp_quantidades[5]->qntd - $eqp_status[5]->ativos ?>],
            ['<?= $eqp_quantidades[6]->marca; ?>',  <?= $eqp_quantidades[6]->qntd; ?>,      <?= $eqp_status[6]->ativos; ?>,         <?= $eqp_quantidades[6]->qntd - $eqp_status[6]->ativos ?>],
            ['<?= $eqp_quantidades[7]->marca; ?>',  <?= $eqp_quantidades[7]->qntd; ?>,      <?= $eqp_status[7]->ativos; ?>,         <?= $eqp_quantidades[7]->qntd - $eqp_status[7]->ativos ?>],
            ['<?= $eqp_quantidades[8]->marca; ?>',  <?= $eqp_quantidades[8]->qntd; ?>,      <?= $eqp_status[8]->ativos; ?>,         <?= $eqp_quantidades[8]->qntd - $eqp_status[8]->ativos ?>]
        ]);

        var options = {
            title : 'Dashboard Equipamentos - Geral',
            vAxis: {title: 'Qntd'},
            hAxis: {title: 'Marcas'},
            seriesType: 'bars'
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
// ********************** FIM GRAFICO *************************** //
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(continental);
    google.charts.setOnLoadCallback(maxtrack);
    google.charts.setOnLoadCallback(quanta);
    google.charts.setOnLoadCallback(svias);
    google.charts.setOnLoadCallback(calamp);
    google.charts.setOnLoadCallback(suntech);
    google.charts.setOnLoadCallback(spot);
    google.charts.setOnLoadCallback(queclink);
    google.charts.setOnLoadCallback(mix);
    // CONTINENTAL
    function continental() {
        var data = google.visualization.arrayToDataTable([
              ['Status', 'Qtd'],
              ['Ativos',    <?= $eqp_status[0]->ativos; ?>],
              ['Inativos',    <?= $eqp_quantidades[0]->qntd - $eqp_status[0]->ativos; ?>]
        ]);

        var options = {
              title: '<?= $eqp_quantidades[0]->marca; ?>',
              pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('continental'));
        chart.draw(data, options);
    }

    // MAXTRACK
    function maxtrack() {
        var data = google.visualization.arrayToDataTable([
              ['Status', 'Qtd'],
              ['Ativos',    <?= $eqp_status[1]->ativos; ?>],
              ['Inativos',    <?= $eqp_quantidades[1]->qntd - $eqp_status[1]->ativos; ?>]
        ]);

        var options = {
              title: '<?= $eqp_quantidades[1]->marca; ?>',
              pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('maxtrack'));
        chart.draw(data, options);
    }
    // QUANTA
    function quanta() {
        var data = google.visualization.arrayToDataTable([
              ['Status', 'Qtd'],
              ['Ativos',    <?= $eqp_status[2]->ativos; ?>],
              ['Inativos',    <?= $eqp_quantidades[2]->qntd - $eqp_status[2]->ativos; ?>]
        ]);

        var options = {
              title: '<?= $eqp_quantidades[2]->marca; ?>',
              pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('quanta'));
        chart.draw(data, options);
    }
    // SVIAS
    function svias() {
        var data = google.visualization.arrayToDataTable([
              ['Status', 'Qtd'],
              ['Ativos',    <?= $eqp_status[3]->ativos; ?>],
              ['Inativos',    <?= $eqp_quantidades[3]->qntd - $eqp_status[3]->ativos; ?>]
        ]);

        var options = {
              title: '<?= $eqp_quantidades[3]->marca; ?>',
              pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('svias'));
        chart.draw(data, options);
    }
    // CALAMP
    function calamp() {
        var data = google.visualization.arrayToDataTable([
              ['Status', 'Qtd'],
              ['Ativos',    <?= $eqp_status[4]->ativos; ?>],
              ['Inativos',    <?= $eqp_quantidades[4]->qntd - $eqp_status[4]->ativos; ?>]
        ]);

        var options = {
              title: '<?= $eqp_quantidades[4]->marca; ?>',
              pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('calamp'));
        chart.draw(data, options);
    }
    // SUNTECH
    function suntech() {
        var data = google.visualization.arrayToDataTable([
              ['Status', 'Qtd'],
              ['Ativos',    <?= $eqp_status[5]->ativos; ?>],
              ['Inativos',    <?= $eqp_quantidades[5]->qntd - $eqp_status[5]->ativos; ?>]
        ]);

        var options = {
              title: '<?= $eqp_quantidades[5]->marca; ?>',
              pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('suntech'));
        chart.draw(data, options);
    }
    // SPOT
    function spot() {
        var data = google.visualization.arrayToDataTable([
              ['Status', 'Qtd'],
              ['Ativos',    <?= $eqp_status[6]->ativos; ?>],
              ['Inativos',    <?= $eqp_quantidades[6]->qntd - $eqp_status[6]->ativos; ?>]
        ]);

        var options = {
              title: '<?= $eqp_quantidades[6]->marca; ?>',
              pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('spot'));
        chart.draw(data, options);
    }
    // QUECLINK
    function queclink() {
        var data = google.visualization.arrayToDataTable([
              ['Status', 'Qtd'],
              ['Ativos',    <?= $eqp_status[7]->ativos; ?>],
              ['Inativos',    <?= $eqp_quantidades[7]->qntd - $eqp_status[7]->ativos; ?>]
        ]);

        var options = {
              title: '<?= $eqp_quantidades[7]->marca; ?>',
              pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('queclink'));
        chart.draw(data, options);
    }
    // MIX
    function mix() {
        var data = google.visualization.arrayToDataTable([
              ['Status', 'Qtd'],
              ['Ativos',    <?= $eqp_status[8]->ativos; ?>],
              ['Inativos',    <?= $eqp_quantidades[8]->qntd - $eqp_status[8]->ativos; ?>]
        ]);

        var options = {
              title: '<?= $eqp_quantidades[8]->marca; ?>',
              pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('mix'));
        chart.draw(data, options);
    }

    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(ativos_sco);
    google.charts.setOnLoadCallback(ativos_mxt);
    google.charts.setOnLoadCallback(ativos_qrt);
    google.charts.setOnLoadCallback(ativos_svias);
    google.charts.setOnLoadCallback(ativos_cal);
    google.charts.setOnLoadCallback(ativos_st);
    google.charts.setOnLoadCallback(ativos_spot);
    google.charts.setOnLoadCallback(ativos_ql);
    google.charts.setOnLoadCallback(ativos_mix);

    function ativos_sco() {
        var data = google.visualization.arrayToDataTable([
            ['Modelo', 'Total', 'Ativos'],
            ['<?= $eqp_status[0]->modelos[0]->modelo; ?>', <?= $eqp_quantidades[0]->modelos[0]->qntd; ?>, <?= $eqp_status[0]->modelos[0]->ativos; ?>]

        ]);

        var options = {
            title: '<?= $eqp_status[0]->marca; ?>',
            hAxis: {
              title: 'Ativos por Modelo',
              minValue: 0
            },
            vAxis: {
              title: 'Modelos'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('ativos_sco'));
        chart.draw(data, options);
    }

    function ativos_mxt() {
        var data = google.visualization.arrayToDataTable([
            ['Modelo', 'Total', 'Ativos'],
            ['<?= $eqp_status[1]->modelos[0]->modelo; ?>', <?= $eqp_quantidades[1]->modelos[0]->qntd; ?>, <?= $eqp_status[1]->modelos[0]->ativos; ?>],
            ['<?= $eqp_status[1]->modelos[1]->modelo; ?>', <?= $eqp_quantidades[1]->modelos[1]->qntd; ?>, <?= $eqp_status[1]->modelos[1]->ativos; ?>],
            ['<?= $eqp_status[1]->modelos[2]->modelo; ?>', <?= $eqp_quantidades[1]->modelos[2]->qntd; ?>, <?= $eqp_status[1]->modelos[2]->ativos; ?>],
            ['<?= $eqp_status[1]->modelos[3]->modelo; ?>', <?= $eqp_quantidades[1]->modelos[3]->qntd; ?>, <?= $eqp_status[1]->modelos[3]->ativos; ?>],
            ['<?= $eqp_status[1]->modelos[4]->modelo; ?>', <?= $eqp_quantidades[1]->modelos[4]->qntd; ?>, <?= $eqp_status[1]->modelos[4]->ativos; ?>],
            ['<?= $eqp_status[1]->modelos[5]->modelo; ?>', <?= $eqp_quantidades[1]->modelos[5]->qntd; ?>, <?= $eqp_status[1]->modelos[5]->ativos; ?>],
            ['<?= $eqp_status[1]->modelos[6]->modelo; ?>', <?= $eqp_quantidades[1]->modelos[6]->qntd; ?>, <?= $eqp_status[1]->modelos[6]->ativos; ?>]

        ]);

        var options = {
            title: '<?= $eqp_status[1]->marca; ?>',
            hAxis: {
              title: 'Ativos por Modelo',
              minValue: 0
            },
            vAxis: {
              title: 'Modelos'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('ativos_mxt'));
        chart.draw(data, options);
    }

    function ativos_qrt() {
        var data = google.visualization.arrayToDataTable([
            ['Modelo', 'Total', 'Ativos'],
            ['<?= $eqp_status[2]->modelos[0]->modelo; ?>', <?= $eqp_quantidades[2]->modelos[0]->qntd; ?>, <?= $eqp_status[2]->modelos[0]->ativos; ?>],
            ['<?= $eqp_status[2]->modelos[1]->modelo; ?>', <?= $eqp_quantidades[2]->modelos[1]->qntd; ?>, <?= $eqp_status[2]->modelos[1]->ativos; ?>]

        ]);

        var options = {
            title: '<?= $eqp_status[2]->marca; ?>',
            hAxis: {
              title: 'Ativos por Modelo',
              minValue: 0
            },
            vAxis: {
              title: 'Modelos'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('ativos_qrt'));
        chart.draw(data, options);
    }

    function ativos_svias() {
        var data = google.visualization.arrayToDataTable([
            ['Modelo', 'Total', 'Ativos'],
            ['<?= $eqp_status[3]->modelos[0]->modelo; ?>', <?= $eqp_quantidades[3]->modelos[0]->qntd; ?>, <?= $eqp_status[3]->modelos[0]->ativos; ?>]

        ]);

        var options = {
            title: '<?= $eqp_status[3]->marca; ?>',
            hAxis: {
              title: 'Ativos por Modelo',
              minValue: 0
            },
            vAxis: {
              title: 'Modelos'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('ativos_svias'));
        chart.draw(data, options);
    }

    function ativos_cal() {
        var data = google.visualization.arrayToDataTable([
            ['Modelo', 'Total', 'Ativos'],
            ['<?= $eqp_status[4]->modelos[0]->modelo; ?>', <?= $eqp_quantidades[4]->modelos[0]->qntd; ?>, <?= $eqp_status[4]->modelos[0]->ativos; ?>],
            ['<?= $eqp_status[4]->modelos[1]->modelo; ?>', <?= $eqp_quantidades[4]->modelos[1]->qntd; ?>, <?= $eqp_status[4]->modelos[1]->ativos; ?>],
            ['<?= $eqp_status[4]->modelos[2]->modelo; ?>', <?= $eqp_quantidades[4]->modelos[2]->qntd; ?>, <?= $eqp_status[4]->modelos[2]->ativos; ?>]
        ]);

        var options = {
            title: '<?= $eqp_status[4]->marca; ?>',
            hAxis: {
              title: 'Ativos por Modelo',
              minValue: 0
            },
            vAxis: {
              title: 'Modelos'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('ativos_cal'));
        chart.draw(data, options);
    }

    function ativos_st() {
        var data = google.visualization.arrayToDataTable([
            ['Modelo', 'Total', 'Ativos'],
            ['<?= $eqp_status[5]->modelos[0]->modelo; ?>', <?= $eqp_quantidades[5]->modelos[0]->qntd; ?>, <?= $eqp_status[5]->modelos[0]->ativos; ?>],
            ['<?= $eqp_status[5]->modelos[1]->modelo; ?>', <?= $eqp_quantidades[5]->modelos[1]->qntd; ?>, <?= $eqp_status[5]->modelos[1]->ativos; ?>]

        ]);

        var options = {
            title: '<?= $eqp_status[5]->marca; ?>',
            hAxis: {
              title: 'Ativos por Modelo',
              minValue: 0
            },
            vAxis: {
              title: 'Modelos'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('ativos_st'));
        chart.draw(data, options);
    }

    function ativos_spot() {
        var data = google.visualization.arrayToDataTable([
            ['Modelo', 'Total', 'Ativos'],
            ['<?= $eqp_status[6]->modelos[0]->modelo; ?>', <?= $eqp_quantidades[6]->modelos[0]->qntd; ?>, <?= $eqp_status[6]->modelos[0]->ativos; ?>],
            ['<?= $eqp_status[6]->modelos[1]->modelo; ?>', <?= $eqp_quantidades[6]->modelos[1]->qntd; ?>, <?= $eqp_status[6]->modelos[1]->ativos; ?>]

        ]);

        var options = {
            title: '<?= $eqp_status[6]->marca; ?>',
            hAxis: {
              title: 'Ativos por Modelo',
              minValue: 0
            },
            vAxis: {
              title: 'Modelos'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('ativos_spot'));
        chart.draw(data, options);
    }

    function ativos_ql() {
        var data = google.visualization.arrayToDataTable([
            ['Modelo', 'Total', 'Ativos'],
            ['<?= $eqp_status[7]->modelos[0]->modelo; ?>', <?= $eqp_quantidades[7]->modelos[0]->qntd; ?>, <?= $eqp_status[7]->modelos[0]->ativos; ?>],
            ['<?= $eqp_status[7]->modelos[1]->modelo; ?>', <?= $eqp_quantidades[7]->modelos[1]->qntd; ?>, <?= $eqp_status[7]->modelos[1]->ativos; ?>]

        ]);

        var options = {
            title: '<?= $eqp_status[7]->marca; ?>',
            hAxis: {
              title: 'Ativos por Modelo',
              minValue: 0
            },
            vAxis: {
              title: 'Modelos'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('ativos_ql'));
        chart.draw(data, options);
    }

    function ativos_mix() {
        var data = google.visualization.arrayToDataTable([
            ['Modelo', 'Total', 'Ativos'],
            ['<?= $eqp_status[8]->modelos[0]->modelo; ?>', <?= $eqp_quantidades[8]->modelos[0]->qntd; ?>, <?= $eqp_status[8]->modelos[0]->ativos; ?>]

        ]);

        var options = {
            title: '<?= $eqp_status[8]->marca; ?>',
            hAxis: {
              title: 'Ativos por Modelo',
              minValue: 0
            },
            vAxis: {
              title: 'Modelos'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('ativos_mix'));
        chart.draw(data, options);
    }

    $(document).ready(function() { // Carrega o loader

        //Esconde preloader
        $(window).load(function() {
            $('#preloader').fadeOut(1500);//1500 é a duração do efeito (1.5 seg)
        });

    });

</script>