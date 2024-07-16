<div class="container-fluid" id="menu-hor">
    <div class="cards row col-md-10 col-lg-10 col-sm-10">
        <a href="<?php echo site_url('compras/produtos/') ?>">
            <div id="card" class="col-md-2 col-lg-2 col-sm-4 col-xs-12">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-shopping-bag fa-4x"></i>
                    <h4 style="font-family: sans-serif; color: #0068b1">Produtos</h4>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/fornecedores/') ?>">
            <div id="card" class="col-md-2 col-lg-2 col-sm-4 col-xs-12">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-handshake-o fa-4x"></i>
                    <h4 style="font-family: sans-serif; color: #0068b1">Fornecedores</h4>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao/') ?>">
            <div id="card" class="col-md-2 col-lg-2 col-sm-4 col-xs-12">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-gavel fa-4x"></i>
                    <h4 style="font-family: sans-serif; color: #0068b1">Cotações</h4>
                </div>
            </div>
        </a>
        <a href="<?php echo site_url('compras/cotacao_aprovada/') ?>">
            <div id="card" class="col-md-2 col-lg-2 col-sm-4 col-xs-12">
                <div class="content" style="text-align: center; margin-top: 20%;">
                    <i class="fa fa-cart-arrow-down fa-4x"></i>
                    <h4 style="font-family: sans-serif; color: #0068b1">Cotações Aprovadas</h4>
                </div>
            </div>
        </a>

    </div>
</div>
<div id="box-table">

    <table id="tabela-home" class="table table-responsive table-lg table-hover">
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
<div class="botoes">
    <button data-toggle="modal" data-target="#modal-empresa" type="button" class="btn-produto-home btn-primary">Novo Produto</button>
    <button data-toggle="modal" data-target="#modal-empresa" type="button" class="btn-fornecedor-home btn-warning">Novo Fornecedor</button>
    <button data-toggle="modal" data-target="#modal-empresa" type="button" class="btn-cotacao-home btn-success">Nova Cotação</button>
</div>

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
                <span> <button formaction="<?php echo site_url('compras/cad_produtos/') ?>" class="btn btn-info">Criar</button></span>
            </div>
            </form>
        </div>
    </div>
</div>