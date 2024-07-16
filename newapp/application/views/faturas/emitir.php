<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Emissão de Fatura</title>
  <link href="<?php echo base_url('media') ?>/css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  <style>
     .modal-emissao {
        width: 730px;
        margin-left: -350px;
      }
  </style>
</head>
<body>
  <div class='container'>
    <h1></h1>
    <div class='navbar navbar-inverse'>
      <div class='navbar-inner nav-collapse' style="height: auto;">
        <ul class="nav">
          <li class="active"><a href="#">Home</a></li>
          <li class="active"><a href="http://www.showtecnologia.com"><i
                                class="icon-chevron-left icon-white"></i> Voltar para o Site</a></li>
        </ul>
      </div>
    </div>
    <div id='content' class='row-fluid'>
      <div class='span2 sidebar'>
        <h3></h3>
      </div>
      <div class='span8 main'>
          <?php if(!$retorno && $block): ?>
            <div id="myModal_emissao" class="modal fade higherWider modal-emissao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="" id="loginModal">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h3>Emissão de Fatura</h3>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#create" data-toggle="tab">Emissão</a></li>
                    </ul>
                      <div class="tab-pane active in" id="create">
                        <div class="alert alert-info">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          Caso queira realizar o procedimento na sede da empresa, <b>não será cobrado por deslocamento.</b>
                          Caso deseje realizar o procedimento em outro local, indique abaixo a quantidade de km's da empresa até o local (ida e volta).
                          <br>*Serão computados os km's baseados no Google Maps.
                        </div>
                        <form id="signup" class="form-horizontal" method="post" action="gerar_fatura_eptc">
                          <div class="control-group ">
                                <label class="control-label">CPF ou CNPJ</label>
                            <div class="controls">
                                <div class="input-prepend">
                              <span class="add-on"><i class="icon-user"></i></span>
                                <input type="text" class="input-medium cpf" id="cpf" name="cpf" placeholder="digite apenas números">
                              </div>
                            </div>
                          </div>
                          <div class="control-group">
                                <label class="control-label">Prefixo</label>
                            <div class="controls">
                                <div class="input-prepend">
                              <span class="add-on"><i class="icon-envelope"></i></span>
                                <input type="text" class="input-medium" id="prefixo" name="prefixo" placeholder="prefixo">
                              </div>
                            </div>
                          </div>
                          <div class="control-group">
                                <label class="control-label">Procedimento</label>
                            <div class="controls">
                                <div class="input-prepend">
                              <span class="add-on"><i class="fa fa-search"></i></span>
                                <select type="text" id="procedimento" name="procedimento" placeholder="procedimento" class="input span12">
                                  <option value="">Escolha o procedimento</option>
                                  <option value="1">Reposição do GPS por dano ou extravio + Reinstalação: R$344,65</option>
                                  <option value="2">Retirar e reinstalar equipamento: $94,22</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="control-group">
                                <label class="control-label">KM's</label>
                            <div class="controls">
                                <div class="input-prepend">
                              <span class="add-on"><i class="fa fa-map-marker"></i></span>
                                <input type="text" class="input-small" id="kms" name="kms" placeholder="km's">
                              </div>
                            </div>
                          </div>
                          <div class="control-group pull pull-left">
                          <label class="control-label"></label>
                              <div class="controls">
                               <button type="submit" class="btn btn-primary">Gerar Fatura</button>
                              </div>
                          </div>
                        </form>  
                      </div>
                    </div>
                </div>
              </div>
            </div>
          <?php elseif(!$retorno): ?>
            <div class="alert alert-danger" style="margin-top: 60px;">
              <span class="mensagem">CPF/CNPJ ou prefixo incorretos. <a href="https://gestor.showtecnologia.com:85/sistema/newapp/index.php/faturas/emitir">Clique aqui para tentar novamente.</a></span>
            </div>
          <?php endif; ?>
      </div>
      <div class='span2 sidebar'>
        <h3></h3>
      </div>
    </div>
  </div>
</body>
<script src="<?php echo base_url('media') ?>/js/jquery.js"></script>
<script src="<?php echo base_url('media') ?>/js/bootstrap.js"></script>
<script src="<?php echo base_url('media') ?>/js/validate.js"></script>
<script src="<?php echo base_url('media') ?>/js/jquery-mask.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
      $('#myModal_emissao').modal('show');
      $('#cpf').mask('99999999999999999', {clearIfNotMatch: true});
      $('#kms').mask('99999999999999999', {clearIfNotMatch: true});
      $('#prefixo').mask('9999', {clearIfNotMatch: true});
      $("#signup").validate({
        rules:{
          cpf:"required",
          prefixo:"required",
          procedimento:"required",
          },

          errorClass: "help-inline"
      });
    });

</script>
</html>