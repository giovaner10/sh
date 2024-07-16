<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

<h3>Ordens de Serviços</h3>

<hr class="featurette-divider">
<div class="well well-small">
    <div class="btn-group">
      <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
        Listar OS
        <span class="caret"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="<?php echo site_url('servico')?>" title=""><i class="icon-th-list"></i> Todas</a></li>
        <li><a href="<?php echo site_url('servico/os_abertas')?>" title=""><i class="icon-th-list"></i> Abertas</a></li>
        <li><a href="<?php echo site_url('servico/os_fechadas')?>" title=""><i class="icon-th-list"></i> Fechadas</a></li>
      </ul>
    </div>

    <div class="btn-group">
      <a class="btn  dropdown-toggle" data-toggle="dropdown" href="#">
        Gerar OS
        <span class="caret"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="<?php echo site_url('servico/instalacao')?>" title=""><i class="icon-th-list"></i> Instalação</a></li>
        <li><a href="<?php echo site_url('servico/manutencao_troca_retirada')?>" title=""><i class="icon-th-list"></i> Manutenção/Troca/Retirada</a></li>
      </ul>
    </div>
</div>

<br style="clear:both" />

<div class="container-fluid">
	<h4>Ordens de Serviços Abertas</h4>
    <table class="table" id="example">
        <thead>
			<tr>
				<th>OS</th>
				<th>Tipo</th>
				<th>Cliente</th>
				<th>Contrato</th>
				<th>Placa</th>
				<th>Data Cadastro</th>
        <th>Usuário</th>
				<th>Status</th>
				<th>Administrar</th>
			</tr>
        </thead>
        <tbody>
        </tbody>
	</table>
</div>

<script>
    var table = $('#example').DataTable( {
        destroy: true,
        processing: false,
        aLengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        serverSide: false,
        ordering: false,
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

        "ajax": "<?= site_url('servico/load_os/0') ?>"

    });
</script>