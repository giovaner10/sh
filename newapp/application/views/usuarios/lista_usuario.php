<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery.dataTables.css') ?>">
<script type="text/javascript" charset="utf8" src="<?php echo base_url('assets/js/jquery.dataTables.js') ?>"></script>
<style>
    .dt-buttons {
        display: none !important;
    }
</style>
<h3>Cadastro de Funcionários</h3>
<hr>
<div>
    <div class="row-fluid">
        <div class="form-group">
            <div class="btn-group">
                <a href="<?php echo site_url('usuarios/add')?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Adicionar</a>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="well well-small">
            <button class="btn btn-info" id="gerar_pdf">Gerar PDF</button>
            <button class="btn btn-info" id="gerar_excel">Gerar Excel</button>
            <button class="btn btn-info" id="gerar_csv">Gerar CSV</button>
            <button class="btn btn-info" id="gerar_print">Imprimir</button>
        </div>
    </div>
</div>
<table id="table" class="table table-bordered table-hover">
	<thead style="background-color: #0072cc !important; color: white !important;">
		<th class="span1">ID</th>
		<th class="span4">Nome</th>
        <th class="span2">Ocupação</th>
        <th class="span2">Contato</th>
        <th class="span3">Empresa</th>
        <th class="span2">Filial</th>
        <th class="span2">Data Nasc.</th>
		<th class="span1">Status</th>
		<th class="span1">Administrar</th>
	</thead>
	<tbody>
		<?php if(count($usuarios) > 0):?>
		<?php foreach ($usuarios as $usuario):?>
		<tr>
			<td><?php echo $usuario->id?></td>
			<td><?php echo $usuario->nome ?></td>
            <td><?= $usuario->ocupacao ?></td>
            <td><?= $usuario->telefone ?></td>
            <td><?= substr($usuario->empresa, 0, 15) ?></td>
            <td><?= $usuario->city_job ?></td>
            <td><?= date('d/m/Y', strtotime($usuario->data_nasc)) ?></td>
			<td id="status<?= $usuario->id ?>">
				<?php echo show_status($usuario->status) ?>
			</td>
			<td>
				<nobr>
                <a href="<?php echo site_url('usuarios/visualizar/'.$usuario->id)?>" class="btn btn-primary btn-sm" title="Visualizar Usuário">
                    <i class="fa fa-edit icon-white"></i>
                </a>
				<a class="btn btn-warning btn-sm" href="<?= site_url('contratos/contrato_trabalho').'/'.$usuario->id ?>" target="_blank" title="Imprimir Contrato">
                    <i class="fa fa-print"></i>
                </a>
                <?php if ($this->auth->is_allowed_block('admin_permissoes')): ?>
                    <?php if ($usuario->status == 1): ?>
                        <a id="buttonInativa<?= $usuario->id ?>" onclick="inativar(<?= $usuario->id ?>)" class="btn btn-danger btn-sm" title="Inativar Acesso">
                            <i id="icon<?= $usuario->id ?>" class="fa fa-remove"></i>
                        </a>
                    <?php else: ?>
                        <a id="buttonAtiva<?= $usuario->id ?>" onclick="ativar(<?= $usuario->id ?>)" class="btn btn-success btn-sm" title="Ativar Acesso">
                            <i id="icon<?= $usuario->id ?>" class="fa fa-check"></i>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                </nobr>
			</td>
		</tr>
		<?php endforeach;?>
    <?php endif; ?>
	</tbody>
</table>

<!-- modals -->
<div id="impressao_contrato" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Impressão de Faturas</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>
<div id="gerar_contrato" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Gerar Faturas por Contrato</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>

<script type="text/javascript" src="<?=base_url('assets/js/jquery.dataTables.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/dataTables.buttons.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/buttons.flash.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/jszip.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/pdfmake.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/vfs_fonts.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/buttons.html5.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/buttons.print.min.js')?>"></script>

<script>
    $(document).on('click', '#gerar_pdf', function() {
        $(".buttons-pdf").trigger("click");
    });

    $(document).on('click', '#gerar_excel', function() {
        $(".buttons-excel").trigger("click");
    });

    $(document).on('click', '#gerar_csv', function() {
        $(".buttons-csv").trigger("click");
    });

    $(document).on('click', '#gerar_print', function() {
        $(".buttons-print").trigger("click");
    });

    $('#table').DataTable( {
        oLanguage: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        },
        dom: 'Bfrtip',
        buttons: [
            {
                title: 'Cadastro de Funcionários',
                orientation: 'landscape',
                pageSize: 'A4',
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                title: 'Cadastro de Funcionários',
                orientation: 'landscape',
                pageSize: 'A4',
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                title: 'Cadastro de Funcionários',
                orientation: 'landscape',
                pageSize: 'A4',
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                title: 'Cadastro de Funcionários',
                orientation: 'landscape',
                pageSize: 'A4',
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });

    function ativar(id) {
        var url = "<?= site_url('usuarios/ativar').'/' ?>"+id;
        $.ajax({
            url : url,
            type : 'POST',
            dataType : 'json',
            success : function(){
                var button = document.getElementById('buttonAtiva'+id);
                button.removeAttribute('onclick');
                button.setAttribute('onclick', 'inativar('+id+')');
                button.removeAttribute('class');
                button.setAttribute('class', 'btn btn-sm btn-danger');
                button.removeAttribute('title');
                button.setAttribute('title', 'Inativar Acesso');
                button.removeAttribute('id');
                button.setAttribute('id', 'buttonInativa'+id);

                var icon = document.getElementById('icon'+id);
                icon.removeAttribute('class');
                icon.setAttribute('class', 'fa fa-remove');

                var status = document.getElementById('status'+id);
                status.innerHTML = '<span class="label label-success">Ativo</span>';
            },
            error : function () {
                alert('Sistema temporariamente indisponivel.');
            }
        });
    }

    function inativar(id) {
        var url = "<?= site_url('usuarios/inativar').'/' ?>"+id;
        $.ajax({
            url : url,
            type : 'POST',
            dataType : 'json',
            success : function(){
                var button = document.getElementById('buttonInativa'+id);
                button.removeAttribute('onclick');
                button.setAttribute('onclick', 'ativar('+id+')');
                button.removeAttribute('class');
                button.setAttribute('class', 'btn btn-mini btn-success');
                button.removeAttribute('title');
                button.setAttribute('title', 'Ativar Acesso');
                button.removeAttribute('id');
                button.setAttribute('id', 'buttonAtiva'+id);

                var icon = document.getElementById('icon'+id);
                icon.removeAttribute('class');
                icon.setAttribute('class', 'fa fa-check');

                var status = document.getElementById('status'+id);
                status.innerHTML = '<span class="label btn-danger">Inativo</span>';
            },
            error : function () {
                alert('Sistema temporariamente indisponivel.');
            }
        });
    }
</script>