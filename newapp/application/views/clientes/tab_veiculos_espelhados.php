<?php
if(isset($error)){
	echo "
	<div class='alert alert-warning' id='mens'>
		<strong>Atenção: </strong> ".$error."
	</div>
";
}
 ?>

<div class="row-fluid">
		<table id="table_veiculos_espelhados" class="table display responsive table-bordered">
			<thead>				
				<th>Central</th>				
				<th>ID Terminal</th>
				<th>IP</th>
				<th>Porta</th>				
				<th>Placa</th>				
				<th>Nome do Cliente</th>					
			</thead>
			<tbody>
			</tbody>
		</table>
</div>


<script>
	$('#table_veiculos_espelhados').DataTable({
			responsive:true,
			ajax:'<?php echo $url_espelhamento; ?>',	//url from clientes controller		
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
			ordering: true,
			paging: true
	});
</script>
