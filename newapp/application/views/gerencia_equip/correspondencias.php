<script src="<?php echo base_url('assets');?>/js/jquery.min.js"></script>
<script src="<?php echo base_url('assets');?>/js/bootstrap.min.js"></script>
<?php 
if ($this->session->flashdata('sucesso')) { ?>
	<div class="alert alert-success">
  		<strong><?php echo $this->session->flashdata('sucesso'); ?></strong>
	</div>
<?php } elseif ($this->session->flashdata('erro')) { ?>
	<div class="alert alert-danger">
  <strong><?php echo $this->session->flashdata('erro'); ?></strong>
</div>
<?php } ?>
<div>
	<div class=" dropdown">
		<button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-plus"></i> Cadastrar
		<span class="caret"></span></button>
		<ul class="dropdown-menu">
    		<li><a type="button" data-toggle="modal" data-target="#destinatario"><i class="fa fa-user-plus"></i> Destinatário</a></li>
    		<li><a type="button" data-toggle="modal" data-target="#cadastrar"><i class="fa fa-truck"></i> Movimentação</a></li>
  		</ul>
        <a href="<?php echo base_url('gerencia_equipamentos')?>/correspondencias"><button type="button" class="btn btn-info" title="Início"><i class="fa fa-home"></i></button></a>
        <a href="<?php echo base_url('gerencia_equipamentos')?>/correspondencias/rec"><button type="button" class="btn btn-warning"><i class="fa fa-reorder"></i> Corresp. Recebidas</button></a>
        <a href="<?php echo base_url('gerencia_equipamentos')?>/correspondencias/env"><button type="button" class="btn btn-primary"><i class="fa fa-reorder"></i> Corresp. Enviadas</button></a>
	</div>    
    <form method="get" name="id" action="#" class="form-inline pull-right" >
        <div class="form-group">
        	<input type="text" class="form-control" name="id" placeholder="Pesquisar">
        </div>
        <div class="form-group">
            <select name="coluna" style="width: 125px !important" class="form-control">
                <option value="id">ID</option>
                <option value="dest">Destinatário</option>
                <option value="remetente">Remetente</option>
                <option value="cod_rastreio">Cod. Rastreio</option>
            </select>
            <button class="btn" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </form>
</div>
<div class="table-responsive">
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Tipo</th>
	      <th>Remetente</th>
	      <th>Destinatário</th>
	      <th>Cod. Rastreio</th>
	      <th>Status</th>
	      <th>Data Lançamento</th>
	      <th>Gerenciar</th>
	    </tr>
	  </thead>
	  <tbody>
	    <?php foreach ($movimentos as $movimento) { ?>
	    <tr>
		    <th scope="row"><?php echo $movimento->id; ?></th>
		    <td><?php if ($movimento->tipo_movimentacao == 1) {
		    	echo "Entrada";
		    } else {
		     	echo "Saída";
		    } ?>
		    </td>
		    <td><?php echo $movimento->remetente; ?></td>
		    <td><?php echo $movimento->nome; ?></td>
		    <td><?php echo $movimento->cod_rastreio; ?></td>
		    <td><?php if ($movimento->status == 0) {
		    	echo "Cadastrado";
		    } elseif ($movimento->status == 1) {
		      	echo "Enviado";
		    } elseif ($movimento->status == 2) {
		    	echo "Entregue";
		    } elseif ($movimento->status == 3) {
		      	echo "Devolvido";
		    } else {
		      	echo "Cancelado";
		    }?></td>
		    <td><?php echo $movimento->data_cadastro; ?></td>
		    <td>
                <!-- BOTÃO ENVIA E ENTREGA -->
                <?php if ($movimento->status == 0 && $movimento->tipo_movimentacao == 1 || $movimento->status == 1 && $movimento->tipo_movimentacao == 0) {?>
		      	<a class="open-enviar_corresp" data-id="<?php echo $movimento->id ?>" data-toggle="modal" href="#entregar_corresp"><button class="btn-success" title="Entregar" ><i class="fa fa-check-square-o"></i></button></a>
                <?php } elseif ($movimento->status == 0 && $movimento->tipo_movimentacao == 0) { ?>
                <a class="open-enviar_corresp" data-id="<?php echo $movimento->id ?>" data-toggle="modal" href="#enviar_corresp"><button class="btn-success" title="Enviar" href="../gerencia_equipamentos/enviar_corresp/<?php echo $movimento->id ?>"><i class="fa fa-check-square-o"></i></button></a>
                <?php } else { ?>
                <button title="Desativado"><i class="fa fa-check-square-o"></i></button>
                <?php } ?>

                <!-- BOTÃO DEVOLVE -->
                <?php if ($movimento->status == 1) {?>
		      	<a class="open-enviar_corresp" data-id="<?php echo $movimento->id ?>" data-toggle="modal" href="#devolver_corresp"><button class="btn-danger" title="Devolvido"><i class="fa fa-mail-reply-all"></i></button></a> 
		      	<?php } else { ?>
                <button title="Desativado"><i class="fa fa-mail-reply-all"></i></button>
                <?php } ?>

                <!-- BOTÃO ABRIR DETALHES -->
                <button class="btn-warning" title="Detalhar"><i class="fa fa-folder-open-o"></i></button>
		    </td>
	    </tr>
	    <?php } ?>
	  </tbody>
	</table>
	<?php if ($paginacao > 1) { ?>
	<div class="pagination">
		<ul>
	 		<?php for ($i=1; $i <= $paginacao ; $i++) { 
	 			if ($i == 1) { ?>
	 				<li><a href="?pag=0"> Inicio </a></li>
	 			<?php } else { ?>
	 				<li><a href="?pag=<?php echo $i.'0'; ?>"><?php echo $i; ?></a></li>
	 			<?php }
			} ?>
		</ul>
	</div>
	<?php } ?>
</div>

<!-- CADASTRO DE MOVIMENTAÇÃO -->
<div id="cadastrar" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cadastrar Movimentação</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="#">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Tipo:</label>
                        <label class="radio-inline"><input type="radio" name="tipo" value="1"> Saida</label>
                        <label class="radio-inline"><input type="radio" name="tipo" value="0"> Entrada</label>    
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Remetente: </label>
                        <input type="text" name="rem" placeholder="Nome do Remetente" required>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Destinatário: </label>
                        <select class="form-control" name="dest" required>
                        <?php if (isset($destinatarios)) {
                            foreach ($destinatarios as $destinatario) {
                                $nome = $destinatario->nome;
                                echo "<option> $nome </option>";
                            } 
                        } ?>                            
                        </select>
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

<!-- CADASTRAR COD DE RASTREIO - ENVIAR CORRESPONDENCIA -->
<div id="enviar_corresp" class="modal fade" role="dialog">
<<<<<<< HEAD
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cadastrar Cod. Ratreio</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post" action="../gerencia_equipamentos/enviar_corresp">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cadastrar Cod. Ratreio</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="../gerencia_equipamentos/enviar_corresp">
                    <input type="text" name="id" id="id" value="" style="display: none;">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Cod. Rastreio: </label>
                        <input type="text" name="cod_rast" placeholder="Nome do Remetente">
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
<!-- MODAL ENTREGAR CORRESPONDENCIA - COMENTS -->
<div id="entregar_corresp" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Comentário - Entrega</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post" action="../gerencia_equipamentos/entregar_corresp">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Comentário - Entrega</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="../gerencia_equipamentos/entregar_corresp">
                    <input type="text" name="id" id="id" value="" style="display: none;">
                    <div class="form-group" style="margin-bottom:10px;">
                        <textarea class="form-control" name="comment" rows="5" style="width: 95%;" required></textarea>
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
<!-- MODAL DEVOLVE CORRESPONDÊNCIA - COMENTS -->
<div id="devolver_corresp" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Comentário - Entrega</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post" action="../gerencia_equipamentos/devolver_corresp">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Comentário - Entrega</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="../gerencia_equipamentos/devolver_corresp">
                    <input type="text" name="id" id="id" value="" style="display: none;">
                    <div class="form-group" style="margin-bottom:10px;">
                        <textarea class="form-control" name="comment" rows="5" style="width: 95%;" required></textarea>
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
<!-- CADASTRO DE DESTINATÁRIOS -->
<div id="destinatario" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cadastrar Destinatario</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="<?php echo base_url('gerencia_equipamentos')?>/cad_destinatario">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Nome: </label>
                        <input type="text" name="nome" placeholder="Digite o Nome" required>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Responsavel: </label>
                        <input type="text" name="resp" placeholder="Digite o Responsável">
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Endereço: </label>
                        <input type="text" name="end" placeholder="Digite o Endereço" required>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Cidade: </label>
                        <input type="text" name="cidade" placeholder="Digite a Cidade" required>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Estado: </label>
                        <input type="text" name="estado" placeholder="Digite o Estado" required>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">CEP: </label>
                        <input type="text" name="cep" placeholder="Digite o CEP" required>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Tel.: </label>
                        <input type="text" name="tel" placeholder="Digite o Telefone" required>
                    </div>
                    <div class="form-group" style="margin-bottom:10px;">
                        <label class="control-label col-sm-2">Email: </label>
                        <input type="text" name="email" placeholder="Digite o Email" required>
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

<script type="text/javascript">

$(document).on("click", ".open-enviar_corresp", function () {
     var myBookId = $(this).data('id');
     $(".modal-body #id").val( myBookId );
});

</script>