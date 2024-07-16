<div class="container" id="menu-super">
    <div class="page">
        <a href="<?php echo site_url('compras/produtos/') ?>">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-shopping-bag fa-2x"></i>
                    <h5 style="font-family: sans-serif; color: #0068b1">Produtos</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/fornecedores/') ?>">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-handshake-o fa-2x"></i>
                    <h5 style="color: #0068b1">Fornecedores</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao/') ?>">
            <div id="card-ativo" class="col-md-2 col-sm-6 ativo">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-gavel fa-2x"></i>
                    <h5 style="padding-top: 20px;">Cotações</h5>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao_aprovada/') ?>">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-cart-arrow-down fa-2x"></i>
                    <h5 style="font-family: sans-serif; color: #0068b1">Cotações Aprovadas</h5>
                </div>
            </div>
        </a>

    </div>
</div>
<div id="pagina">
    <div id="tabs" class="container">
        <ul class="nav nav-tabs">
            <li id="aba1"><a class="current" href="#tab1" data-toggle="tab" aria-expanded="true">Cotações Pendentes</a></li>
            <li id="aba3"><a class="" href="#tab3" data-toggle="tab" aria-expanded="false">Cotações Reprovadas</a></li>

        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="tab1">
                <div id="box-table">

                    <table id="tabela-cotacao" class="table table-responsive table-lg table-hover">
                        <!--a id="ult-cot" class="btn btn-default btn-lg btn-block">Últimas Cotações</a-->
                        <thead>
                        <tr>
                            <th class="borda-down">Nº Código</th>
                            <th class="borda-down">Usuário</th>
                            <th class="borda-down">Data</th>
                            <th class="borda-down">Validade</th>
                            <th class="borda-down">Loja</th>
                            <th class="borda-down">Nome</th>
                            <th class="borda-down">Visualizar</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="borda borda-down">0000</td>
                            <td class="borda borda-down">John Lennon</td>
                            <td class="borda borda-down">DD/MM/AAAA</td>
                            <td class="borda borda-down">12 Dias</td>
                            <td class="borda borda-down">Pneu Show</td>
                            <td class="borda-down borda">Cotação Desenvolvimento</td>
                            <td class="borda-down" style="color: #faac0d"><a href="#myModal3" style="text-decoration: none" data-toggle="modal" data-target="#myModal3"><button class="btn btn-buscar"><i class="fa fa-search"></i></button></td></a>
                        </tr>
                        <tr>
                            <td class="borda borda-down">0000</td>
                            <td class="borda borda-down">John Lennon</td>
                            <td class="borda borda-down">DD/MM/AAAA</td>
                            <td class="borda borda-down">12 Dias</td>
                            <td class="borda borda-down">Pneu Show</td>
                            <td class="borda-down borda">Cotação Desenvolvimento</td>
                            <td class="borda-down" style="color: #faac0d"><a href="#myModal3" style="text-decoration: none" data-toggle="modal" data-target="#myModal3"><button class="btn btn-buscar"><i class="fa fa-search"></i></button></td></a>
                        </tr>
                        <tr>
                            <td class="borda borda-down">0000</td>
                            <td class="borda borda-down">John Lennon</td>
                            <td class="borda borda-down">DD/MM/AAAA</td>
                            <td class="borda borda-down">12 Dias</td>
                            <td class="borda borda-down">Pneu Show</td>
                            <td class="borda-down borda">Cotação Desenvolvimento</td>
                            <td class="borda-down" style="color: #faac0d"><a href="#myModal3" style="text-decoration: none" data-toggle="modal" data-target="#myModal3"><button class="btn btn-buscar"><i class="fa fa-search"></i></button></td></a>
                        </tr>
                        </tbody>

                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tab3">
                <div id="box-table">

                    <table id="tabela-cotacao" class="table table-responsive table-lg table-hover">
                        <!--a id="ult-cot" class="btn btn-default btn-lg btn-block">Últimas Cotações</a-->
                        <thead>
                        <tr>
                            <th class="borda-down">Nº Código</th>
                            <th class="borda-down">Fornecedor</th>
                            <th class="borda-down">Descrição do Produto</th>
                            <th class="borda-down">Custo</th>
                            <th class="borda-down">Quantidade</th>
                            <th class="borda-down">Entrega</th>
                            <th class="borda-down">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="borda borda-down">0000</td>
                            <td class="borda borda-down">Nome da Empresa</td>
                            <td class="borda borda-down">Nome do Produto</td>
                            <td class="borda borda-down">R$ 000,00</td>
                            <td class="borda borda-down">350</td>
                            <td class="borda-down borda">14/02/2013</td>
                            <td class="borda-down" style="color: #f07859">Reprovada</td>
                        </tr>
                        <tr>
                            <td class="borda borda-down">0000</td>
                            <td class="borda borda-down">Nome da Empresa</td>
                            <td class="borda borda-down">Nome do Produto</td>
                            <td class="borda borda-down">R$ 000,00</td>
                            <td class="borda borda-down">350</td>
                            <td class="borda-down borda">14/02/2013</td>
                            <td class="borda-down" style="color: #f07859">Reprovada</td>
                        </tr>
                        <tr>
                            <td class="borda borda-down">0000</td>
                            <td class="borda borda-down">Nome da Empresa</td>
                            <td class="borda borda-down">Nome do Produto</td>
                            <td class="borda borda-down">R$ 000,00</td>
                            <td class="borda borda-down">350</td>
                            <td class="borda-down borda">14/02/2013</td>
                            <td class="borda-down" style="color: #f07859">Reprovada</td>
                        </tr>
                        <tr>
                            <td class="borda">0000</td>
                            <td class="borda">Nome da Empresa</td>
                            <td class="borda">Nome do Produto</td>
                            <td class="borda">R$ 000,00</td>
                            <td class="borda">350</td>
                            <td class="borda">14/02/2013</td>
                            <td style="color: #f07859">Reprovada</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>
<!-- Modal -->
<div class="modal fade" id="myModal3" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a data-dismiss="modal" aria-label="Close"><div class="fechar"><h3>Fechar</h3></div></a>
            <div class="modal-header">
                <!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
                <img id="logo-modal" class="img img-responsive modal-title" src="<?php echo base_url('media')?>/img/show-logo-bc.png">
                <h4>Cotações: 10.11.2016</h4>
            </div>
            <div class="modal-body">

                <table id="tab-cotacao" class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                        <th colspan="2">Melhor Custo/Benéficio<br/>
                            <div id="un" class="badge">Valor Total</div>  <span id="total" class="badge">Fornecedor</span>
                        </th>
                        <th colspan="2">Fornecedor 2<br/>
                            <div id="un" class="badge">Valor Total</div>  <span id="total" class="badge">Fornecedor</span>
                        </th>
                        <th colspan="2">Fornecedor 3<br/>
                            <div id="un" class="badge">Valor Total</div>  <span id="total" class="badge">Fornecedor</span>
                        </th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <form class="form-group">
                        <tr id="fornecedor">
                            <td id="prd1"><input type="checkbox"/> 145 Un - Max track</td>
                            <td>5.000</td>
                            <td id="col-forn1" class="col-forn1"><input name="optionsRadios" type="radio"/>  R$ 50,00</td>
                            <td id="col-forn1-1" class="col-forn1">Fulano</td>
                            <td id="col-forn2" class="col-forn2"><input name="optionsRadios" type="radio"/>  R$ 60,00</td>
                            <td id="col-forn2-2" class="col-forn2">José</td>
                            <td id="col-forn3" class="col-forn3"><input name="optionsRadios" type="radio"/>  R$ 70,00</td>
                            <td id="col-forn3-3" class="col-forn3">Maria</td>
                            <td>Autorizar</td>
                        </tr>
                        <tr id="fornecedor">
                            <td><input type="checkbox"/> 145 Un - Max track</td>
                            <td>5.000</td>
                            <td id="col-forn1" class="col-forn1"><input name="optionsRadios2" type="radio"/>  R$ 50,00</td>
                            <td id="col-forn1-1" class="col-forn1">R$ 250.000</td>
                            <td id="col-forn2" class="col-forn2"><input name="optionsRadios2" type="radio"/>  R$ 60,00</td>
                            <td id="col-forn2-2" class="col-forn2">R$ 300.000</td>
                            <td id="col-forn3" class="col-forn3"><input name="optionsRadios2" type="radio"/>  R$ 70,00</td>
                            <td id="col-forn3-3" class="col-forn3">R$ 350.000</td>
                            <td>Autorizar</td>
                        </tr>
                        <tr id="fornecedor">
                            <td><input type="checkbox"/> 145 Un - Max track</td>
                            <td>5.000</td>
                            <td id="col-forn1" class="col-forn1"><input name="optionsRadios3" type="radio"/>  R$ 50,00</td>
                            <td id="col-forn1-1" class="col-forn1">R$ 250.000</td>
                            <td id="col-forn2" class="col-forn2"><input name="optionsRadios3" type="radio"/>  R$ 60,00</td>
                            <td id="col-forn2-2" class="col-forn2">R$ 300.000</td>
                            <td id="col-forn3" class="col-forn3"><input name="optionsRadios3" type="radio"/>  R$ 70,00</td>
                            <td id="col-forn3-3" class="col-forn3">R$ 350.000</td>
                            <td>Autorizar</td>
                        </tr>
                        <tr id="fornecedor">
                            <td><input type="checkbox"/> 145 Un - Max track</td>
                            <td>5.000</td>
                            <td id="col-forn1" class="col-forn1"><input name="optionsRadios4" type="radio"/>  R$ 50,00</td>
                            <td id="col-forn1-1" class="col-forn1">R$ 250.000</td>
                            <td id="col-forn2" class="col-forn2"><input name="optionsRadios4" type="radio"/>  R$ 60,00</td>
                            <td id="col-forn2-2" class="col-forn2">R$ 300.000</td>
                            <td id="col-forn3" class="col-forn3"><input name="optionsRadios4" type="radio"/>  R$ 70,00</td>
                            <td id="col-forn3-3" class="col-forn3">R$ 350.000</td>
                            <td>Autorizar</td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-lg pull-right">Enviar</button>
                </form>

            </div>
        </div>
    </div>
</div>
<button data-toggle="modal" data-target="#modal-empresa" type="button" class="btn-produto btn-primary">Nova Cotação</button>
<div class="modal fade" id="modal-empresa" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5>Selecione a Empresa</h5>
            </div>
            <div class="modal-body">
                <form>
                    <div class="checkbox checkbox-success">
                        <input id="check5" value="pneushow" class="styled" type="checkbox" checked>
                        <label for="check5">
                            <strong>Pneu Show</strong>
                        </label>
                    </div>
                    <div class="checkbox checkbox-success">
                        <input id="check5"value="showtec" class="styled" type="checkbox">
                        <label for="check5">
                            <strong>Show Tecnologia</strong>
                        </label>
                    </div>
                    <div class="checkbox checkbox-success">
                        <input id="check5" value="sim2m" class="styled" type="checkbox">
                        <label for="check5">
                            <strong>SiM2M</strong>
                        </label>
                    </div>

            </div>
            <div class="modal-footer">
                <a type="link" data-dismiss="modal" aria-label="Close">Cancelar</a>
                <span> <button formaction="<?php echo site_url('compras/cad_cotacao/') ?>" class="btn btn-info">Criar</button></span>
            </div>
            </form>
        </div>
    </div>
</div>
<script>

    $(function() {
        $('.nav.nav-tabs > li > a').click(function() {
            $('.nav.nav-tabs > li > a.current').removeClass('current');
            $(this).addClass('current');
        });
    });
    //    $('.col-forn1').click(function() {
    //        $('#col-forn1 , #col-forn1-1').addClass('active');
    //    });
</script>

