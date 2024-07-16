<br>
<div class="containner">
	
    <h3 class="TitPage"><?=lang('fones_enderecos_corporativos')?></h3>

    <hr>
    
	<div id="tab_funcionarios" class="tab-pane-active">
		<table id="dt_funcionarios" class="table table-striped table-bordered">
			<thead>
				<tr class="tableheader">
					<th><?=lang('nome')?></th>
					<th><?=lang('cargo')?></th>
					<th>E-mail</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>


</div>

<script type="text/javascript">
	var dt_funcionarios = $("#dt_funcionarios").DataTable({
		"autoWidth": false,
		"processing": true,
		"dom": 'Bfrtip',
		"ajax": {
			"url":  "<?=site_url('/ashownet/ajax_list_funcionarios')?>",
			"type": "POST",
		},
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

</script>
