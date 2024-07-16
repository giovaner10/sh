<div class="container" id="menu-super">
    <div class="page">
        <a href="<?php echo site_url('compras/produtos/') ?>">
            <div id="card-topo" class="col-md-2 col-sm-6">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-shopping-bag fa-2x"></i>
                    <h5 style="font-family: monospace; color: #0068b1">Produtos</h5>
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
                    <h5 style="font-family: monospace; color: #0068b1">Cotações Aprovadas</h5>
                </div>
            </div>
        </a>

    </div>
</div>
<div id="box-table">

    <table id="tabela-fornecedor" class="table table-responsive table-lg table-hover">
        <!--a id="ult-cot" class="btn btn-default btn-lg btn-block">Últimas Cotações</a-->
        <thead>
        <tr style="color: #1878b8; font-size: 10px;">
            <th class="borda borda-down">Nº Código</th>
            <th class="borda borda-down">Fornecedor</th>
            <th class="borda borda-down">Descrição do Produto</th>
            <th class="borda borda-down">Custo</th>
            <th class="borda borda-down">Quantidade</th>
            <th class="borda borda-down">Entrega</th>
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
            <td class="borda-down" style="color: #00CC00">Aprovado</td>
        </tr>
        <tr>
            <td class="borda borda-down">0000</td>
            <td class="borda borda-down">Nome da Empresa</td>
            <td class="borda borda-down">Nome do Produto</td>
            <td class="borda borda-down">R$ 000,00</td>
            <td class="borda borda-down">350</td>
            <td class="borda-down borda">14/02/2013</td>
            <td class="borda-down" style="color: #00CC00">Aprovado</td>
        </tr>
        <tr>
            <td class="borda borda-down">0000</td>
            <td class="borda borda-down">Nome da Empresa</td>
            <td class="borda borda-down">Nome do Produto</td>
            <td class="borda borda-down">R$ 000,00</td>
            <td class="borda borda-down">350</td>
            <td class="borda-down borda">14/02/2013</td>
            <td class="borda-down" style="color: #00CC00">Aprovado</td>
        </tr>
        <tr>
            <td class="borda">0000</td>
            <td class="borda">Nome da Empresa</td>
            <td class="borda">Nome do Produto</td>
            <td class="borda">R$ 000,00</td>
            <td class="borda">350</td>
            <td class="borda">14/02/2013</td>
            <td style="color: #00CC00">Aprovado</td>
        </tr>

        </tbody>
    </table>
</div>
<button data-toggle="modal" data-target="#modal-empresa" type="button" class="btn-produto btn-primary">Novo Fornecedor</button>
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
                <span> <button formaction="<?php echo site_url('compras/cad_fornecedor/') ?>" class="btn btn-info">Criar</button></span>
            </div>
            </form>
        </div>
    </div>
</div>
