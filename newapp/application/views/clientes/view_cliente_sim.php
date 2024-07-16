<?php if($msg != ''):?>
<div class="alert">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>CONCLUIDO!</strong>
	<?php echo $msg?>
</div>
<?php endif;?>
<div class="alert hide">
	<!-- <button type="button" class="close" data-dismiss="alert">&times;</button> -->
	<span id="msg-retorno"></span>
</div>
<h3>Cliente - <?php echo word_limiter($cliente->nome, 7)?></h3>
<hr>
<div class="well well-small">
	<div class="span1">
		<div class="btn-group">
		</div>
	</div>
	<div class="span7">

	</div>
	<?php echo form_open('faturas/filtro')?>
	<div class="span4 input-append">
	</div>
	<?php echo form_close()?>
	<div class="clearfix"></div>
</div>

<ul class="nav nav-tabs hidden-print" id="tab_clientes">
	<?php $sess = $this->session->userdata('tab_cliente') == false ? '#usuarios' : $this->session->userdata('tab_cliente')?>
	<li class="<?php echo $sess == 'home' ? 'active' : ''?> inicio"><a href="#home" data-url="<?php echo site_url('clientes/dados_cliente/'.$id_cliente)?>" data-nome="home" data-toggle="tab">Dados Cadastro</a></li>
	<li class="<?php echo $sess == 'contratos' ? 'active' : ''?>"><a href="#contratos" data-url="<?php echo site_url('contratos/tab_listar_contratos/'.$id_cliente)?>" data-nome="contratos"  data-toggle="tab">Contratos</a></li>
	<li class="<?php echo $sess == 'debitos' ? 'active' : ''?>"><a href="#debitos" data-nome="debitos" data-toggle="tab">Débitos</a></li>
	<li class="<?php echo $sess == 'usuarios' ? 'active' : ''?>"><a href="#usuarios" data-nome="usuarios" data-toggle="tab">Usuários</a></li>
	<li class="<?php echo $sess == 'veiculos' ? 'active' : ''?>"><a href="#veiculos" data-url="<?php echo site_url('veiculos/tab_listar_gestor/'.$id_cliente)?>" data-nome="veiculos" data-toggle="tab">Veículos</a></li>
	<li class="<?php echo $sess == 'os' ? 'active' : ''?>"><a href="#os" data-nome="os" data-toggle="tab">Ordens de Serviço</a></li>
	<li class="<?php echo $sess == 'filiais' ? 'active' : ''?>"><a href="#filiais" data-nome="filiais" data-toggle="tab">Filiais</a></li>
	<li class="<?php echo $sess == 'preferencias' ? 'active' : ''?>"><a href="#preferencias" data-nome="preferencias" data-toggle="tab">Preferências</a></li>
</ul>

<div id="loading" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>

<div
	class="tab-content">
	<div class="tab-pane <?php echo $sess == 'home' ? 'active' : ''?>" id="home">...</div>
	<div class="tab-pane <?php echo $sess == 'contratos' ? 'active' : ''?>" id="contratos">...</div>

	<!-- inicio tab usuarios -->
	<div class="tab-pane <?php echo $sess == 'usuarios' ? 'active' : ''?>" id="usuarios">
		<div class="row-fluid">
			<div class="span1">
				<a href="<?php echo site_url('usuarios_gestor/add/'.$id_cliente)?>" class="btn btn-primary"
					data-toggle="modal" data-target="#novo_usuario"
					title="Novo Usuário"> <i class="icon-plus icon-white"></i>
				</a>
			</div>
		</div>
		<?php if($bloqueio || $this->auth->get_login('admin', 'email') == 'eduardo@showtecnologia.com'):?>
			<div id="bloqueio" style="display: none;">1</div>
		<?php endif;?>
		<table class="table table-bordered">
			<thead>
				<th>#ID</th>
				<th>Nome</th>
				<th>Usuário</th>
				<th>Tipo</th>
				<th>Administrar</th>
			</thead>
			<tbody>
			<?php if(count($usuarios) > 0):?>
				<?php foreach($usuarios as $usuario):?>
				<tr>
					<td><?php echo $usuario->code?></td>
					<td><?php echo $usuario->nome_usuario?></td>
					<td><?php echo $usuario->usuario?></td>
					<td><?php echo $usuario->tipo_usuario?></td>
					<td>
						<?php if ($usuario->status_usuario == 'inativo'):?>
							<a href="<?php echo site_url('usuarios_gestor/update_status/ativo/'.$usuario->code)?>" class="btn btn-danger btn-mini ativo" title="Liberar Acesso"><i class="icon-ban-circle icon-white"></i></a>
						<?php else :?>
							<a href="<?php echo site_url('usuarios_gestor/update_status/inativo/'.$usuario->code)?>" class="btn btn-success btn-mini inativo" title="Bloquear"><i class="icon-ok-circle  icon-white"></i></a>
						<?php endif;?>
						<a href="<?php echo site_url('usuarios_gestor/view/'.$id_cliente.'/'.$usuario->code)?>" data-toggle="modal" data-target="#view_usuario" class="btn btn-primary btn-mini" title="Atualizar Dados"><i class="icon-edit icon-white"></i></a>
						<a href="<?php echo site_url('usuarios_gestor/permissoes_modulos/'.$id_cliente.'/'.$usuario->code)?>" data-toggle="modal" data-target="#permissoes_usuario" class="btn btn-primary btn-mini" title="Acesso aos Módulos"><i class="fa fa-sitemap"></i> </a>
						<a href="" data-email="<?php echo $usuario->usuario?>" data-user="<?php echo $usuario->senha?>" class="btn btn-primary btn-mini logar" title="Logar na conta do Usuário"><i class="icon-off icon-white"></i></a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="5">Nenhum usuário cadastrado ou relacionado ao cliente.</td>
				</tr>
			<?php endif;?>
			</tbody>
		</table>
	</div>
	<!-- fim tab usuarios -->

	<!-- tab filiais -->
	<div class="tab-pane <?php echo $sess == 'veiculos' ? 'active' : ''?>" id="veiculos">
		<?php //$this->load->view('clientes/tab_veiculos')?>
	</div>
	<!-- fim tab veiculos -->

	<!-- tab filiais -->
	<div class="tab-pane <?php echo $sess == 'filiais' ? 'active' : ''?>" id="filiais">
		<?php $this->load->view('clientes/tab_filiais', $d_filiais)?>
	</div>

	<div class="tab-pane <?php echo $sess == 'settings' ? 'active' : ''?>" id="settings">...</div>
</div>

<div id="novo_usuario" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true"></button>
		<h3 id="myModalLabel1">Novo Usuário</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>
<div id="view_usuario" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">x</button>
		<h3 id="myModalLabel1">Dados Usuário</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
</div>
<div id="permissoes_usuario" class="modal fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">x</button>
		<h3 id="myModalLabel1">Permissões de Acesso aos Módulos</h3>
	</div>
	<div class="modal-body">
		<p>Carregando...</p>
	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
	<button class="btn btn-primary salvar-permissao" data-controller="<?php echo site_url('usuarios_gestor/ajax_permissoes_salvar/')?>">Salvar</button>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		var clientes = new ModuleClientes();
		clientes.init();
		if($('#bloqueio').text() != 1) {
			$('.ativo').prop('href', '#');
			$('.inativo').prop('href', '#');
		}

		$('#home').tab('show');

		$('#tab_clientes a').click(function (e) {
			$('#loading').css('display', 'block');
			 var sessao = $(this).attr('data-nome');
			 url = '<?php echo site_url('util/tab_active/tab_cliente')?>/'+sessao;
			 $.get(url, function(formulario){});

			 var urlTab = $(this).attr("data-url");
			 var href = this.hash;
			 var pane = $(this);

			 $(href).load(urlTab, function(result){
					pane.tab('show');
					$('#loading').css('display', 'none');
			});
		});

		$('#busca_usuario').submit(function(){

			keyWord = $('input[name=usuario]').val();
			controller = '<?php echo site_url('usuarios_gestor/view/'.$id_cliente)?>';
			url = controller+'/'+keyWord;

			$.get(url, function(formulario){
				$('#view_usuario .modal-body').html(formulario);
				$('#view_usuario').modal('show');
			});
			return false;
		});

		$('.logar').click(function(){
			var user = $(this).attr('data-email');
			var pass = $(this).attr('data-user');
			var tken = '9c6424f2bc466768ff161b6d9157d133';

			$.post('<?php echo site_url('api/logar_usuario_gestor')?>', {token: tken, email: user, senha: pass}, function(retorno){

				if(retorno.success){

					$.ajax({
						url: '<?php echo $this->config->item("base_url_gestor")?>index.php/login/gerar_sessao',
						// url: 'http://gestor-show.dev/index.php/login/gerar_sessao',
						type: 'post',
						dataType: 'json',
						data: {dados: retorno.usuario},
						success: function(resposta){
							if (resposta.success) {
								window.open('<?php echo $this->config->item("base_url_gestor")?>index.php/', '_blank');
							}
						}
					});
				} else {
					alert('Problema ao tentar logar. Tente novamente.');
				}
			}, "json");
			return false;
		});

		$(document).ready(function(){
		$('div#home').load('<?php echo site_url("clientes/dados_cliente/".$id_cliente)?>'); //aqui carregamos dentro o conteúdo dados_cliente dentro da div #home
		});

	});
</script>
