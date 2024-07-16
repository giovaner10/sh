	
	 	<h3>Gerenciador de Tickets</h3>

        <hr class="featurette-divider">

        <br style="clear:both" />

	    <div class="span3" style="text-align: center; float: left;" >
	   		<ul  style="list-style: none; ">
	   			  <li style="margin-bottom: 5px;"><a href="<?php echo site_url('webdesk/novo')?>" title="" class="btn btn-primary btn-block">+ Abrir novo ticket</a></li>
	   			  <li style="margin-bottom: 5px;"><a href="<?php echo site_url('webdesk')?>" title="" class="btn btn-block ">Todos</a></li>
                  <li style="margin-bottom: 5px;"><a href="<?php echo site_url('webdesk/abertos')?>" title="" class="btn btn-block ">Em andamento</a></li>
                  <li style="margin-bottom: 5px;"><a href="<?php echo site_url('webdesk/fechados')?>" title="" class="btn btn-block ">Fechados</a></li>
            </ul>
        </div><!-- /.span3 -->

        <div class="span9" style="margin-left: 100px; background-color: #f5f5f5; height: 100px; border:1px solid #EEE9E9aa;">

        		 <?php if ($tickets): ?>	

				  <!-- <h4>Tickets</h4>

				  <table class="table table-hover">

			  	  
			  			<tr>
							<th>Id</th>
							<th>Departamento</th>
							<th>Assunto</th>
							<th>Abertura</th>
							<th>Status</th>
							<th>Administrar</th>
						</tr> -->
							
			      <?php foreach ($tickets as $ticket): ?>
					<tr>
					    <td><?php echo $ticket->mensagem ?></td>
					    <!-- <td><?php echo $ticket->departamento ?></td>
					    <td><?php echo $ticket->assunto ?></td>
					    <td><?php echo $ticket->data_abertura ?></td>
					    <td><?php echo $ticket->status == 3 ? '<span class="label label-success">Fechado</span>' : '<span class="label label-warning">Aberto</span>' ?></td>   
				  		<td><a href="<?php echo site_url('webdesk/conversa/'.$ticket->id)?>" title="Visualizar"><i class="icon-edit"></i></a> -->
				  	</tr>  
				  <?php endforeach ?>
				  <?php else: ?>
				  	<h2>Não há tickets</h2>
				  <?php endif ?>	

			  </table>

	    	  

	    </div>


        
