<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <title><?php echo lang('cadrepresentantes2')?></title>
  <link href="<?php echo base_url('media') ?>/css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  <link href="<?php echo base_url('media') ?>/css/home.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/animatecss/3.5.2/animate.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>
  <div class='container'>
    <h1></h1>
    <div class='navbar navbar-inverse'>
      <div class='navbar-inner nav-collapse' style="height: auto;">
        <ul class="nav">
          <li class="active"><a href="#">Home</a></li>
          <li class="active"><a href="http://www.showtecnologia.com"><i
                                class="icon-chevron-left icon-white"></i><?php echo lang('voltarsite')?></a></li>
        </ul>
      </div>
    </div>

    <div>
      <h1 style="font-size:25px">Escolha a opção desejada</h1>
      <div class='span8 main'>
      <?php if(!$retorno && $block): ?>
      <div>

    <div class="container">
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home">Solicitação de Contato</a></li>
        <li><a data-toggle="tab" href="#menu1">Cadastrar</a></li>
      </ul>
      
      <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
          <h3>Formulário de Contato</h3>
          <form id="contact-form"  action="http://187.28.21.50:8085/sistema/newapp/index.php/representantes/envia_email_cadastro" method="post" name="contactform">
            <div class="col-md-6">
              <label class="control-label">Nome</label>
              <input id="name" class="form-control" name="name" type="text" placeholder=" Seu Nome" required>

              <label class="control-label">E-mail</label>
              <input id="email" class="form-control" name="email" type="email" placeholder="Seu Email" required>

              <label class="control-label">Telefone</label>
              <input id="tel" class="form-control" name="tel" type="text" placeholder="Telefone para Contato" required>

              <label class="control-label">Endereço</label>
              <input id="end" class="form-control" name="end" type="text" placeholder="Endereço" required>

              <div class="g-recaptcha" data-sitekey="6LcN7SYUAAAAAGPAyuW6cQeE_Xz2_Afi7GoyFT1u"></div>

            </div><!-- /.col-md-6 -->

            <div class="col-md-6">
              <label class="control-label">Região de Interesse</label>
              <input id="cidade" class="form-control" name="cidade" type="text" placeholder="Cidade de Interesse" required>

              <label class="control-label">Mensagem</label>
              <textarea id="obs" class="form-control" name="obs" placeholder="Observações" rows="9" required></textarea>

              <div id="submit">
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;" >Enviar Solicitação de Contato</button>
              </div><!-- /#submit -->
            </div><!-- /.col-md-6 -->           
          </form><!-- /#contact-form -->
        </div>


        <div id="menu1" class="tab-pane fade">
          <div>
                  <form id="signup" class="form-horizontal" method="post" action="https://gestor.showtecnologia.com/sistema/newapp/index.php/representantes/add">
                    <div class="control-group">
                      <label class="control-label">Nome/Razão Social</label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="icon-user"></i></span>
                          <input type="text" class="input-xlarge" id="cNome" name="nome" placeholder="Digite o nome completo">
                        </div>
                      </div>
                    </div>

                    <?php if ($pais == 'USA'){ ?>
                    <div class="control-group ">
                      <label class="control-label">CPF / CNPJ</label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="icon-user"></i></span>
                          <input type="text" maxlength="18" name="cpf" placeholder="CPF/CNPJ">
                        </div>
                      </div>
                    </div>

                    <?php }else{ ?>
                    <div class="control-group ">
                      <label class="control-label">CPF/CNPJ</label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="icon-user"></i></span>
                          <input type="text" maxlength="18" name="cpf" placeholder="CPF/CNPJ">
                        </div>
                      </div>
                    </div>
                    <?php } ?>

                    <div class="control-group">
                      <label class="control-label"><?php echo lang('email')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="icon-envelope"></i></span>
                          <input type="text" class="input-xlarge" id="cEmail" name="email" placeholder="<?php echo lang('email')?>">
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('senha')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="icon-lock"></i></span>
                          <input type="Password" id="cSenha" class="input-xlarge" name="senha" placeholder="<?php echo lang('senha')?>">
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('csenha')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="icon-lock"></i></span>
                          <input type="Password" id="ccSenha" class="input-xlarge" name="rsenha" placeholder="<?php echo lang('csenha')?>">
                        </div>
                       </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('endereco')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-home"></i></span>
                          <input type="text" class="input-xlarge" id="cEndereco" name="endereco" placeholder="<?php echo lang('endereco')?>">
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('numero')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-home"></i></span>
                          <input type="text" class="input-mini" id="cNumero" name="numero" placeholder="<?php echo lang('numero')?>">
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('bairro')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-home"></i></span>
                          <input type="text" class="input-small" id="cBairro" name="bairro" placeholder="<?php echo lang('bairro')?>">
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('cep')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-envelope-o"></i></span>
                          <input type="text" class="input-small" id="cCEP" name="cep" placeholder="<?php echo lang('cep')?>">
                        </div>
                      </div>
                    </div>
                    <input type="hidden" class="input-small" id="cPais" name="pais" value="<?php echo $pais ?>" placeholder="">
                    
                    <?php if ($pais == 'USA'){ ?>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('estado')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-map-marker"></i></span>
                          <select style="width: 280px !important;" type="text" id="cEstado" name="estado" placeholder="<?php echo lang('estado')?>" class="input span12">
                            <option value=""><?php echo lang('esestado')?></option>
                            <option value="AK">Alaska</option>
                            <option value="AL">Alabama</option>
                            <option value="AR">Arkansas</option>
                            <option value="AZ">Arizona</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DC">District of Columbia</option>
                            <option value="DE">Delaware</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="IA">Iowa</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MD">Maryland</option>
                            <option value="ME">Maine</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MO">Missouri</option>
                            <option value="MS">Mississippi</option>
                            <option value="MT">Montana</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="NE">Nebraska</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NV">Nevada</option>
                            <option value="NY">New York</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VA">Virginia</option>
                            <option value="VT">Vermont</option>
                            <option value="WA">Washington</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WV">West Virginia</option>
                            <option value="WY">Wyoming</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <?php }else{ ?>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('estado')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-map-marker"></i></span>
                          <select type="text" id="cEstado" name="estado" placeholder="><?php echo lang('estado')?>" class="input span12">
                            <option value=""><?php echo lang('esestado')?></option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AM">Amazonas</option>
                            <option value="AP">Amapá</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espirito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="PR">Paraná</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SE">Sergipe</option>
                            <option value="SP">São Paulo</option>
                            <option value="TO">Tocantins</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <?php } ?>

                    <div class="control-group">
                      <label class="control-label"><?php echo lang('cidade')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-map-marker"></i></span>
                          <select type="text" id="cCidade" name="cidade" placeholder="<?php echo lang('cidade')?>" class="input span10"></select>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('telefone')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-phone"></i></span>
                          <input type="text" class="input-medium" id="cTelefone" name="telefone" placeholder="<?php echo lang('telefone')?>">
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('celular')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-mobile"></i></span>
                          <input type="text" class="input-medium" id="cCelular" name="celular" placeholder="<?php echo lang('celular')?>">
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('banco')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-bank"></i></span>
                          <select type="text" id="cBanco" name="banco" placeholder="<?php echo lang('banco')?>" class="input span10">
                            <option value="001">Banco do Brasil</option>
                            <option value="004">Banco do Nordeste</option>
                            <option value="237">Bradesco</option>
                            <option value="104">Caixa Econômica Federal</option>
                            <option value="341">Itaú</option>
                            <option value="008">Santander</option>
                            <option value="121000248">Wells Fargo</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('agencia')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-bank"></i></span>
                          <input type="text" class="input-small" id="cAgencia" name="agencia" placeholder="<?php echo lang('agencia')?>">
                        </div>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang('conta')?></label>
                      <div class="controls">
                        <div class="input-prepend">
                          <span class="add-on"><i class="fa fa-credit-card"></i></span>
                          <input type="text" class="input-small" id="cConta" name="conta" placeholder="<?php echo lang('conta')?>">
                        </div>
                      </div>
                    </div>

                    <div class="control-group pull pull-right">
                      <label class="control-label"></label>
                      <div class="controls">
                        <button type="submit" class="btn btn-primary" ><?php echo lang('cadastrar')?></button>
                      </div>
                    </div>
                  </form>
                  <!-- falta ! -->
                <?php elseif(!$retorno): ?> 
  <div class="alert alert-danger" style="margin-top: 60px;">
    <span class="mensagem"><?php echo lang('alertcpfdanger')?></span>
  </div>
  
  <?php else: ?>
  <div class="alert alert-success" style="margin-top: 60px;">
    <span class="mensagem"><?php echo lang('alertcpfsuccess')?></span>
  </div>

  <?php endif; ?>
  </div>
  <div class='span2 sidebar'>
    <h3></h3>
  </div>
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
    
      $('#cTelefone').mask('(99) 999999999', {clearIfNotMatch: true});
      $('#cCelular').mask('(99) 999999999', {clearIfNotMatch: true});
      $('#cCEP').mask('99999-999', {clearIfNotMatch: true});
      $('#csscard').mask('999-99-9999', {clearIfNotMatch: true});
      $("#signup").validate({
        rules:{
          nome:"required",
          sobrenome:"required",
          email:{
          required:true,
          email: true
          },
          senha:{
          required:true,
          minlength: 4
          },
          rsenha:{
          required:true,
          equalTo: "#cSenha"
          },
          endereco:"required",
          numero:"required",
          bairro:"required",
          cep:"required",
          estado:"required",
          cidade:"required",
          celular:"required",
          },

          errorClass: "help-inline"
      });
    });

</script>
</html>