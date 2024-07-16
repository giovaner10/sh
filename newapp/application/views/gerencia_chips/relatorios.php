<input type="text" class="search-grafico" placeholder="Digite o nº da linha..."/><i class="fa fa-search lupa-grafico"></i>
<div class="name-linha pull-right"> <h5> +55 83 987137261 </h5></div>
<div class="topo-grafic">
    <div class="title-grafic">
        <h5>Consumo de Dados</h5>
        <p class="text-muted">Limite de consumo de dados</p>
    </div>
</div>
<div id="canvas-holder">
    <canvas id="grafico_barra" width="300" height="70">
        <h5>Consumo de Dados</h5>
    </canvas>
</div>
<div id="canvas-holder" class="canvas-holder">
    <canvas id="GraficoDonut" class="grafico-donut" width="600" height="300">
    </canvas>
    <div class="donut-right">
        <h5>Agosto</h5>
        <h4>2016</h4><br/>
        <h5>Plano Atual</h5>
        <h4>300mb</h4><br/>
        <h5>Restante</h5>
        <h4>195mb</h4><br/>
        <p>Operadora Oi</p>
    </div>

    <div class="total-chips">
        <h4>Total de Chips</h4>
        <p>Todos os chips no sistema</p>
        <div class="total-chips-body">
            <h1>45</h1><h5>Contas <br/>Ativadas</h5>
        </div><hr/>
        <div class="total-chips-footer">
            <div class="pull-left"><h4>M2M:</h4></div>
            <div class="pull-right"><h5> 5.000</h5></div>
        </div><br/>
        <div class="total-chips-footer2">
            <div class="pull-left"><h4>Banda Larga:</h4></div>
            <div class="pull-right"><h5> 2.000</h5></div>
        </div><br/>
        <div class="total-chips-footer3">
            <div class="pull-left"><h4>Vinculados:</h4></div>
            <div class="pull-right"><h5> 7.000</h5></div>
        </div>

    </div>
</div>

<script>

    var ctx = document.getElementById("grafico_barra");
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Jan", "Fev", "Mar", "Abr", "Maio", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            datasets: [{
                label: 'Consumo Mensal',
                data: [30, 100, 250, 350, 1, 3, 250, 300, 50, 25, 11],
                backgroundColor: [
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)',
                    'rgba(248, 170, 39, 1)'

                ],
                borderColor: [
                    'rgba(248, 170, 39, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });

    //GRAFICO ROSCA

    var ctx = document.getElementById("GraficoDonut");
    var donut = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ["Consumo"],
            datasets: [{
                data: [170, 1],
                backgroundColor: [
                    'rgba(248, 170, 39, 1)',
                    'rgba(240, 238, 232, 1)'

                ]
            }]
        },
        options: {

            scales: {

            }
        }
    });


</script>
