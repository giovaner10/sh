<div class="container" style="margin-left: 30%;">
    <div class="page">
        <a href="<?php echo site_url('compras/produtos/') ?>">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-shopping-bag fa-2x"></i>
                    <h5 style="font-family: monospace; color: #0068b1">Cadastrar Produtos</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/fornecedor/') ?>">
            <div id="card-ativo" class="col-md-2 col-sm-6 ativo" style="background: #0068b1; height: 120px;">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-handshake-o fa-2x"></i>
                    <h5 style="padding-top: 20px;">Fornecedores</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao/') ?>">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-gavel fa-2x"></i>
                    <h5 style="font-family: monospace; color: #0068b1">Cotações</h5>
                </div>
            </div>
        </a>
        <a href="">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-cart-arrow-down fa-2x"></i>
                    <h5 style="font-family: monospace; color: #0068b1">Compras Realizadas</h5>
                </div>
            </div>
        </a>

    </div>
</div>

<div id="pagina" class="container">

    <div class="stepwizard col-md-offset-3">
        <div class="stepwizard-row setup-panel">
            <div class="stepwizard-step">
                <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
            </div>
            <div class="stepwizard-step">
                <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
            </div>
            <div class="stepwizard-step">
                <a href="#step-4" type="button" id="btnA" class="btn btn-success btn-circle" disabled="disabled"><i class="fa fa-check"></i></a>
            </div>
        </div>
    </div>

    <form role="form" id="form" action="" method="post">
        <div class="row setup-content" id="step-1">
            <div class="col-md-10 col-sm-10 col-xs-6 col-md-offset-2">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">Empresa</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="Razão Social"  />
                    </div>
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">CNPJ</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="cnpj form-control input-lg" placeholder="CNPJ" />
                    </div>
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">Endereço</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="Avenida/Rua/Local"  />
                    </div>
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">Complemento</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="Referência" />
                    </div>
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">Telefone</label>
                        <input id="inputs" maxlength="15" type="text" required="required" class="telefone form-control input-lg" placeholder="(00) 00000-0000"  />
                    </div>
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">Email</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="nome@email.com" />
                    </div>
                    <div class="form-group col-md-10 col-sm-10 col-lg-10 col-xs-12">
                        <label class="control-label">Observação</label>
                        <textarea id="inputs" maxlength="140" required="required" class="form-control" placeholder="Escreva sua observação" ></textarea>
                    </div>
                    <div class="col-md-10 col-sm-10 col-lg-10">
                        <button id="btn" class="btn btn-success nextBtn btn-lg pull-right" type="button" >Próximo</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row setup-content" id="step-3">
            <div class="col-md-10 col-sm-10 col-lg-10 col-xs-12 col-md-offset-2">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="form-group col-md-5 col-sm-5 col-lg-5 col-xs-12">
                        <label class="control-label">Prazo de Entrega</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="Dias Úteis"  />
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-lg-3 col-xs-12">
                        <label class="control-label">Tipo de frete</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="Ex.: Sedex" />
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-lg-2 col-xs-12">
                        <label class="control-label">Valor</label>
                        <input id="inputs" maxlength="100" type="text" required="required" class="form-control input-lg" placeholder="0,00" />
                    </div>
                    <div class="form-group col-md-10 col-sm-10 col-lg-10 col-xs-12">
                        <label class="control-label">Condições de Pagamento</label>
                        <select id="inputs" required="required" class="form-control input-lg">
                            <option>Selecione</option>
                            <option>À Vista</option>
                            <option>Cartão de Crédito</option>
                        </select>
                    </div>
                    <div class="form-group col-md-10 col-sm-10 col-lg-10 col-xs-12">
                        <label class="control-label">Observação</label>
                        <textarea id="inputs" rows="6" maxlength="140" required="required" class="form-control" placeholder="Escreva sua Observação" ></textarea>
                    </div>

                    <div class="col-md-10 col-sm-10">
                        <button id="btn" class="btn btn-success nextBtn btn-lg pull-right" type="button" >Finalizar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row setup-content" id="step-4">
            <div class="col-md-10 col-sm-10 col-lg-10 col-xs-12 col-md-offset-2">
                <div align="center" id="msg-sucesso" class="col-md-offset-1 col-md-12 col-sm-12 col-lg-12">
                    <h3>Fornecedor cadastrado<br/>
                        com sucesso</h3>
                    <div class="check">
                        <i class="fa fa-check"></i>
                    </div>
                    <div class="col-md-8 col-lg-8 col-sm-8">
                        <a href="<?php echo site_url('compras') ?>"><button id="btn" style="margin-right: 10%; margin-top: 30px;" class="btn btn-success nextBtn btn-lg pull-right" type="button" >Fechar</button></a>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="http://digitalbush.com/wp-content/uploads/2014/10/jquery.maskedinput.js"></script>
<script type="text/javascript">
    //MASKS
    jQuery("input.telefone")
        .mask("(99) 9999-9999?9")
        .focusout(function (event) {
            var target, phone, element;
            target = (event.currentTarget) ? event.currentTarget : event.srcElement;
            phone = target.value.replace(/\D/g, '');
            element = $(target);
            element.unmask();
            if(phone.length > 10) {
                element.mask("(99) 99999-999?9");
            } else {
                element.mask("(99) 9999-9999?9");
            }
        });
    $(document).ready(function(){
        $(".cnpj").mask("99.999.999/9999-99");});

    //FIM MASKS
    $(document).ready(function () {
        var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-primary').addClass('btn-default');
                $item.addClass('btn-primary');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();

            }
        });
//VALIDA BOTÃO NEXT
        allNextBtn.click(function(){
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'],input[type='url']"),
                isValid = true;

            $(".form-group").removeClass("has-error");
            for(var i=0; i<curInputs.length; i++){
                if (!curInputs[i].validity.valid){
                    isValid = false;
                    $(curInputs[i]).closest(".form-group").addClass("has-error");
                }
            }

            if (isValid)
                nextStepWizard.removeAttr('disabled').trigger('click');
        });
//FIM VALIDA BOTÃO NEXT
        $('div.setup-panel div a.btn-primary').trigger('click');
    });
</script>