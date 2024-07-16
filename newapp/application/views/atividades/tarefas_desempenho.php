<link href="<?php echo base_url('media') ?>/css/tarefas.css" rel="stylesheet">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- ********* Verificação de Alertas ******** -->

<?php 
$retorno = $this->session->flashdata('sucesso');
$erro = $this->session->flashdata('erro');
if (isset($retorno) && $retorno != '') {
    echo "<div class='alert alert-success'>
    <strong>".$retorno."</strong></div>";
} elseif (isset($erro) && $erro != '') {
    echo "<div class='alert alert-danger'>
    <strong>".$erro."</strong></div>";
} elseif (empty($atividades)) {
    echo "<div class='alert alert-danger'>
    <strong>Nenhuma atividade encontrada!</strong></div>";
} ?>

<!-- ***************** CARREGA OS GRAFICOS ***************** -->
<?php if (!empty($detalhamento)) { ?>
<div class="container">
    <?php if ($id_user == '101' || $id_user == '2') { ?>
    <div style="" id="chart_div" style="width: 800px; height: 400px;"></div>
    <?php } elseif ($id_user != '101' && $id_user != '2') { ?>
    <div style="float: right;" id="piechart" style="width: 800px; height: 400px;"></div>
    <?php } ?>
</div>
<?php } ?>

<div class="container">
    <h3><i class="fa fa-clock-o" style="font-size:36px"></i> Atividades</h3>
    
    <?php if ($id_user == '101' || $id_user == '2') { ?>
    <div class="menu-cad" style="float: left; position: relative">
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#cadastrar"> + NOVA ATIVIDADE</button>
        <?php if (isset($_GET['id_dev'])) { ?> 
        <a href="tarefas/index"><button type="button" class="btn btn-info btn-lg">VOLTAR</button></a>
        <?php } ?>
    </div>
    <div style="float: right; position: relative">
        <form class="form-inline" style="margin: 10px !important" method="get" action="<?php echo base_url('tarefas')?>/index">
            <select class="form-control" name="id_dev">
                <?php foreach ($devs as $dev) {
                    $nome = $dev->nome;
                    echo "<option value='$dev->id'> $nome </option>";
                }?>
            </select>
            <button class="btn btn-info btn-xs" type="submit">Filtrar</button>
        </form>
    </div>
    <?php } ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Atividade</th>
                <th>Resumo</th>
                <th>Responsável</th>
                <th>Inicio</th>
                <th>Fim</th>
                <th>Prazo</th>
                <th>Status</th>
                <th class="col-md-1">Ações</th>
            </tr>
        </thead>
        <?php
        foreach ($atividades as $atividade) { ?>
        <tbody align="center">
            <tr>
                <th scope="row"><?php echo $atividade->id; ?></th>
                <td id="atividade"><?php echo $atividade->nome_atividade; ?></td>
                <td id="resumo"><?php echo $atividade->resumo; ?></td>
                <td id="resp"><?php echo $atividade->nome_desenvolvedor; ?></td>
                <td id="inicio" style="text-align:center;">
                    <?php if ($atividade->nome_status == "Pendente") { ?>
                        <a href="<?php echo base_url('tarefas') ?>/ini_atividade?<?php echo 'id='.$atividade->id ?>"><i class="fa fa-play-circle-o" style="font-size:24px"></i></a>
                    <?php } else { ?>
                        <?php echo $atividade->inicio; }?>
                </td>
                <td id="fim" style="text-align:center;">
                    <?php if ($atividade->nome_status == "Em andamento") { ?>
                        <a href="<?php echo base_url('tarefas') ?>/fim_atividade?<?php echo 'id='.$atividade->id ?>"><i class="fa fa-stop-circle-o" style="font-size:24px"></i></a>
                    <?php } else {
                        echo $atividade->fim;
                        } ?>
                    </td>
                <td id="prazo">
                    <?php if ($atividade->prazo == date('Y-m-d'. " 18:00:00") && $atividade->nome_status != "Concluido") {
                        echo $atividade->prazo;
                        echo " <i class='fa fa-exclamation-triangle' style='font-size:18px; color:#FFBF00' title='Prazo: Hoje!'></i>";
                    } elseif ($atividade->nome_status == "Concluido" && $atividade->prazo >= $atividade->fim) {
                        echo $atividade->prazo;
                        echo " <i class='fa fa-check-square-o' style='font-size:18px; color: green' title='Concluido dentro do prazo!'></i>";
                    } elseif ($atividade->nome_status == "Concluido" && $atividade->prazo < $atividade->fim) {
                        echo $atividade->prazo;
                        echo " <i class='fa fa-hourglass-end' style='font-size:18px; color: red' title='Concluido fora do prazo!'></i>";
                    } else {
                        echo $atividade->prazo; 
                    } ?>
                </td>
                <td id="status"><?php echo $atividade->nome_status; ?></td>
                <td id="obs" style="text-align:center;">
                    <?php if ($atividade->id_status != 6 && $atividade->id_status != 10) {
                        echo "<a href=".base_url('tarefas')."/cancelar_atividade?id=".$atividade->id."&status=".$atividade->id_status."><i class='fa fa-ban' style='font-size:24px;' title='Cancelar'></i></a> 
                            <a data-toggle='modal' data-target='#trans_ativ' onclick=setaDadosModal($atividade->id)><i class='fa fa-exchange' style='font-size:24px;' title='Transferir'></i></a>";
                    } else {
                        echo "<i class='fa fa-ban' style='font-size:24px;' title='Cancelar'></i> 
                            <i class='fa fa-exchange' style='font-size:24px;' title='Transferir'></i>";
                    } ?>

                </td>
            </tr>
        </tbody>
        <?php } ?>
    </table>
    <?php if (!empty($tot_pag)) { ?>
    <div class="pagination pagination-centered">
      <ul>
        <li><a href="?pag=0">Inicio</a></li>
        <?php for($i=0; $i < $tot_pag; $i++) { 
            if ($i == 0) { ?>
                <li><a href="?pag=<?php echo $i; ?>"><?php echo $i+1; ?></a></li>
            <?php } else { ?>
                <li><a href="?pag=<?php echo $i."0"; ?>"><?php echo $i+1; ?></a></li>
            <?php }?>
        <?php } ?>
        <li><a href="#">Fím</a></li>
      </ul>
    </div>
    <?php } ?>

</div>

<!--*************** MODAL DE CADASTRO DE ATIVIDADE ****************** -->

<div id="cadastrar" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CADASTRO DE ATIVIDADE</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="<?php echo base_url('tarefas')?>/cadastrar_atividade">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Atividade:</label>
                        <input type="text" name="atividade" placeholder="Digite o titulo da atividade" required>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Resumo da Aitividade:</label>
                        <input type="text" name="resumo" placeholder="Descreva a atividade" required>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Responsável:</label>
                        <select class="form-control" name="resp" required>
                        <?php foreach ($devs as $dev) {
                            $nome = $dev->nome;
                            echo "<option> $nome </option>";
                        }?>                            
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Prazo:</label>
                        <input type="date" name="prazo" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--*************** MODAL DE CADASTRO DE DESENVOLVEDORES ****************** -->

<div id="cad_dev" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CADASTRAR NOVO DESENVOLVEDOR</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="<?php echo base_url('tarefas')?>/cad_desenvolvedor">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Nome:</label>
                        <input type="text" name="name_dev" placeholder="Digite o nome" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ************************* MODAL DETALHES ***************************** -->
<div id="trans_ativ" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">TRANSFERIR ATIVIDADES</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="<?php echo base_url('tarefas')?>/transferir_atividade">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">ID da atividade:</label>
                        <input class="form-control" name="id_atividade" id="id_atividade" size="4" readonly>
                    </div>    
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Para:</label>
                        <select class="form-control" name="resp" required>
                        <?php foreach ($devs as $dev) {
                            $nome = $dev->nome;
                            echo "<option> $nome </option>";
                        }?>                            
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Transferir</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- ******************************************************************** -->

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});

function setaDadosModal(valor) {
    document.getElementById('id_atividade').value = valor;
};
</script>

<!-- ************************** GRAFICO CIRCULO ************************ -->
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Atividades'],
          ['Em Andamento',     <?php echo $detalhamento['andamento'];?>],
          ['Canceladas',      <?php echo $detalhamento['cancelado'];?>],
          ['Con. Fora do Prazo',  <?php echo $detalhamento['concluido_f'];?>],
          ['Con. Dentro do Prazo', <?php echo $detalhamento['concluido_d'];?>],
          ['Pendentes',    <?php echo $detalhamento['pendente'];?>]
        ]);

        var options = {
          title: 'Grafico de Desempenho'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
</script>

<!-- ********************* GRAFICO DE BARRAS ****************** -->
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
         ['Month', 'Pendentes', 'Em Andamento', 'Concluido Dentro', 'Concluido Fora', 'Cancelados'],
         ['<?php echo $detalhamento[0]['nome_dev'] ?>',  <?php echo $detalhamento[0]['pendente'] ?>,      <?php echo $detalhamento[0]['andamento'] ?>,         <?php echo $detalhamento[0]['concluido_d'] ?>,             <?php echo $detalhamento[0]['concluido_f'] ?>,           <?php echo $detalhamento[0]['cancelado'] ?>],
         ['<?php echo $detalhamento[1]['nome_dev'] ?>',  <?php echo $detalhamento[1]['pendente'] ?>,      <?php echo $detalhamento[1]['andamento'] ?>,         <?php echo $detalhamento[1]['concluido_d'] ?>,             <?php echo $detalhamento[1]['concluido_f'] ?>,           <?php echo $detalhamento[1]['cancelado'] ?>],
         ['<?php echo $detalhamento[2]['nome_dev'] ?>',  <?php echo $detalhamento[2]['pendente'] ?>,      <?php echo $detalhamento[2]['andamento'] ?>,         <?php echo $detalhamento[2]['concluido_d'] ?>,             <?php echo $detalhamento[2]['concluido_f'] ?>,           <?php echo $detalhamento[2]['cancelado'] ?>],
         ['<?php echo $detalhamento[3]['nome_dev'] ?>',  <?php echo $detalhamento[3]['pendente'] ?>,      <?php echo $detalhamento[3]['andamento'] ?>,         <?php echo $detalhamento[3]['concluido_d'] ?>,             <?php echo $detalhamento[3]['concluido_f'] ?>,           <?php echo $detalhamento[3]['cancelado'] ?>]
      ]);

    var options = {
      title : 'Desempenho por Desenvolvedor',
      vAxis: {title: 'Tarefas'},
      hAxis: {title: 'Detalhamento'},
      seriesType: 'bars',
      series: {5: {type: 'line'}}
    };

    var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
    </script>