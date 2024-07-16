<style>
    body {
        background-color: #fff !important;
    }
</style>

<h3 style="text-align: center;">Listagem de Veículos</h3>
<hr>
    <?php if (isset($erro)) : ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>
        <table class="display table responsive table-bordered" id="tabelaCoordenadas" style="width: 100% !important;">
            <thead>
                <tr>
                    <th>Base</th>
                    <th>Botao de Pânico</th>
                    <th>Data</th>
                    <th>Data do Sistema </th>
                    <th>Direção</th>
                    <th>Endereço</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Engate da carreta</th>
                    <th>Estado</th>
                    <th>TeleEvento</th>
                    <th>Hodômetro</th>
                    <th>Tipo de Comunicacao</th>
                    <th>Ignição</th>
                    <th>Intervalo de transmissão</th>
                    <th>Lacre do baú </th>
                    <th>Motorista</th>
                    <th>Placa</th>
                    <th>Prefixo do Veiculo</th>
                    <th>RPM</th>
                    <th>Status do GPS</th>
                    <th>Status da Posição</th>
                    <th>Falha na trava do motorista</th>
                    <th>Temperaturas</th>
                    <th>Horimetro</th>
                    <th>Umidade</th>
                    <th>Velocidade</th>
                    <th>voltagem</th>
                    <th>Serial</th>
                    <th>Saída 1</th>
                    <th>Saída 2</th>
                </tr>
                <tbody>
                </tbody>
            </thead>
        </table>

<!-- <script>
$(document).ready(function() {
    $('#tabelaCoordenadas').DataTable({
            ordering: false,
            paging: true,
            info: true,
            processing: true,
            lengthChange: false,
            "bLengthChange": false,
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: lang.funcionarios,
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> EXCEL'
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: lang.funcionarios,
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF'
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: lang.funcionarios,
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-code-o"></i> CSV'
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: lang.funcionarios,
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> <?=strtoupper(lang('imprimir'))?>'

                }
            ],
		language: lang.datatable,

        });
});
</script> -->

<script>
    const dadosRelatorioCoordenada = <?= $dados ?>;
</script>

<script type="text/javascript" src="<?php echo base_url('newAssets/js/jquery-ui.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>

<!-- bibliotecas usadas apenas nessa pagina -->
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/select2.css">


<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js" ></script>


<script type="text/javascript" src="<?php echo base_url('newAssets/js/relatorio_coordenadas.js'); ?>"></script>



