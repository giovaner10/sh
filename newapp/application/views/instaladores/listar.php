<link href="<?= base_url('media/css/jquery.dynatable.css') ?>" rel="stylesheet">
<link href="<?= base_url('media/css/contas.css') ?>" rel="stylesheet">
<style>
    .star {
        color: #f5ce00;
    }

    .gif {
        position: absolute;
        left: 50%;
        margin-left: -50px;
        width: 150px;
        height: 150px;
    }
</style>

<div id="myModal_digitalizar" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header" style="text-align: center;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Digitalizar Comprovantes</h4>
    </div>
    <div class="modal-body">
    </div>
</div>

<div style="padding-bottom: 10px;">
    <a href="<?= site_url('instaladores/add') ?>">
        <button class="btn btn-info">+ Cadastrar Inst.</button>
    </a>
    <a href="<?= base_url() ?>index.php/instaladores/listar_instaladores?pendente=1<?= $this->input->get('np') ? "&np=1" : "" ?>">
        <button class="btn btn-info">Pagamento pendente</button>
    </a>
    <a href="<?= base_url() ?>index.php/instaladores/listar_instaladores?np=1<?= $this->input->get('pendente') ? "&pendente=1" : "" ?>">
        <button class="btn btn-info">Exibir todos</button>
    </a>
</div>
<h3>Instaladores</h3>
<div class="gif" align="center" id="response"></div>
<div class="span13" style="float: none; margin-left: auto; margin-right: auto;">
    <table id="myTable" class="table table-bordered table-responsive table-striped tab-pane fade active in">
        <thead>
            <tr>
                <th>ID</th>
                <th style="width: 60px">Nota</th>
                <th>Nome</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Telefone</th>
                <th>Celular</th>
                <th>Email</th>
                <th data-dynatable-sorts="qtdDeOs">Qtd de OS</th>
                <th>Valor Instalação</th>
                <th>Valor Manutenção</th>
                <th>Valor Retirada</th>
                <th>Valor Deslocamento</th>
                <th>Bloqueio</th>
                <th>Comprovantes</th>
                <th>Editar</th>
                <th>Acesso</th>
            </tr>
        </thead>
        <tbody align="center">
            <tr>
                <td colspan="17">
                    <i style="color: #499BEA" id="spin" class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/agendamento', 'layout.css') ?>">

<script type="text/javascript" src="<?= base_url('media/js/jquery.dynatable.js') ?>"></script>

<script>
    $(document).ready(function() {
        var getJson = $.getJSON('getAllInstaladores<?= $this->input->get('pendente') ? "?pendente=1" : "" ?>', function(data) {
            var dynatable = $('#myTable').dynatable({
                features: {
                    paginate: <?= $this->input->get('np') ? 'false' : 'true' ?>,
                    sort: true
                },
                dataset: {
                    records: data
                }
            });
            $(document).on('click', '#blockTec', function() {
                var idTec = $(this).data('id');
                var block = $(this).data('block');
                $.ajax({
                    url: 'blockTec',
                    type: 'POST',
                    data: {
                        id: idTec,
                        block: block
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });
        });
    });

    $('#myModal_digitalizar').on('hidden.bs.modal', function() {
        $('.modal-body').html('');
    });

    $(document).on('click', '.logar', function() {
        var login = $(this).data('email');
        var senha = $(this).data('senha');
        $.ajax({
            type: "POST",
            url: "<?= base_url('') ?>index.php/instaladores/autlog",
            data: {
                login: login,
                senha: senha
            },
            beforeSend: function() {
                $('#response').html("<img src='<?= base_url('media/img/load.gif') ?>' />");
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.success) {
                    window.open('<?= base_url('') ?>index.php/home/instalador', '_blank');
                } else {
                    alert('Erro ao logar');
                }
                $('#response').html("");
            }
        });
    });
</script>