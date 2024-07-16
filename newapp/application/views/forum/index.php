<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js "></script>
<script type="text/javascript" language="javascript" src="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></script>

<h3>Fórum</h3>
<hr>
<a class="btn btn-primary" style="margin-bottom: 15px;" data-toggle="modal" data-target="#add"><i class="fa fa-plus"> Novo tópico</i></a>
<div class="row-fluid">
	<table id="forum" class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Usuário</th>
			<th>Assunto</th>
			<th>Data</th>
			<th style="width:40px"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1</td>
			<td>gabriel.bandeira@showtecnologia.com</td>
			<td>Criando Fórum</td>
			<td> 30/05/2018 08:50:11</td>
			<td><i class="fa fa-comments"/> 4</td>
		</tr>
	</tbody>
	</table>
</div>	
<hr>
<div id="add" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1">Adicionar tópico</h3>
    </div>
    <div class="modal-body">
        <input type="text" id="assunto" placeholder="Assunto" name="assunto">
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <a href="#" id="salvar" class="btn btn-primary" onclick="criar_topico()">Adicionar</a>
    </div>
</div>
<script>
	var forum = false;
	function get_table(){
		$.getJSON( "<?=base_url()?>index.php/forum/get_forum", function( data ) {
			console.log(data);
			var array_forum=[];
			data.forEach(function write(data,index){
				array_forum.push([data.id,data.nome,data.assunto,data.data,"<i class='fa fa-comments'/> "+data.visualizacoes]);
			});
			console.log(array_forum);
			if(forum){
				forum.clear();
				forum.rows.add(array_forum);
				forum.draw();
			}
			else{
				forum=$('#forum').DataTable( {
					data: array_forum,
					"scrollCollapse": true,
					columns: [
						{ title: "#" },
						{ title: "Usuário" },
						{ title: "Assunto" },
						{ title: "Data" },
						{ title: "" }
					]
				} );
			}
		});
	}
	function criar_topico(){
		$.ajax({
			type: "POST",
			url: "<?=base_url()?>index.php/forum/novo_topico",
			data: $('input').serialize(),
			success: function retorno(data){
				console.log(JSON.parse(data));
				if(temp1.status=="success"){
					console.log("ok");
				}
			}
		});
	}
	setInterval(get_table(), 500);
</script>
