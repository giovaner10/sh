<link href="<?php echo base_url('media') ?>/css/jquery.dynatable.css" rel="stylesheet">
<link href="<?php echo base_url('media') ?>/css/contas.css" rel="stylesheet">
<h5>Contatos Colaboradores</h5>
<h2 class="TitPage">Telefones e Ramais </h2>    
<div class="gif" align="center" id="response"></div>
<div class="span13" style="float: none; margin-left: auto; margin-right: auto;">
    <table id="myTable" class="table table-bordered table-responsive table-striped tab-pane fade active in">
        <thead>
            <tr>
                <th>Nome</th>
                <th data-dynatable-column="ocupacao">Departamento</th>
                <th data-dynatable-column="ramal_telefone">Telefone/Ramal</th>
                <th data-dynatable-column="login">Email</th>
            </tr>
        </thead>
        <tbody align="center">
            <tr>
                <td colspan="8" align="center">
                    <center>
                        <i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                    </center>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript" src="<?php echo base_url('media');?>/js/jquery.dynatable.js"></script>
<script>
    $(document).ready(function () {
        $('#tbody').html('');
        var getJson = $.getJSON('<?=base_url()?>index.php/contatos_colaboradores/getColaboradores', function (data) {
            $('#tbody').html('');
            
            var dynatable = $('#myTable').dynatable({
                dataset: {
                    records: data
                }
            });
        });
    });
</script>