<style xmlns="http://www.w3.org/1999/html">
    label {
        font-weight: bold;
    }

    .row {
        max-width: 100%;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
    }

    .element {
        background: none;
        display: contents;
    }

    .coluna {
        float: left;
        max-width: 30px;
        height: auto;
        margin: 5px;
        text-align: center;
    }

    #selecionarTodos form {
        margin: 0px !important;
    }

    #modalEditPerm .modal-body {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .inpuImposto {
        float: left;
        width: 60%;
        margin-left: 10px;
    }
    .noMarginTop {
        margin-top: 0px!important;
    }
    .my_readonly {
        opacity: .4;
        cursor: default !important;
        pointer-events: none;
    }

</style>

<?php 
    $pontuacao_por_consulta = round((1000/8), 2); 
    
    //Constroi o select de produtos
    $opcoesSelectProdutos = '';
    if (!empty($produtos)) {
        foreach ($produtos as $produto) {
            $opcoesSelectProdutos .= '<option value="' . $produto->id . '">' . $produto->nome . '</option>';
        }
    }
?>


<br>

<div class="btn-group">
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Adicionar <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a href="<?php echo site_url('clientes/registro') ?>">Cliente</a></li>
        <li><a href="<?php echo site_url('clientes/registro_embarcadores') ?>">Parceiro</a></li>
    </ul>
</div>
<hr>
<div class="alert alert-info">Informe o cliente a ser pesquisado</div>
<div>

    <div class="col-md-2">
        <label>Pesquisar por:</label>
        <select id="sel-pesquisa" class="form-control">
            <option value="0">Nome</option>
            <option value="1">CPF</option>
            <option value="2">CNPJ</option>
            <option value="3">ID</option>
            <option value="4">Usuário</option>
        </select>
    </div>

    <form id="formPesquisa">
        <div class="row">
            <div id="pesquisa" class="col-md-4">
                <label>Nome: </label>
                <br>
                <select class="form-control pesqnome" id="pesqnome" name="nome" type="text" style="width: 460px; margin-top: 21px;"></select>
            </div>
            <div id="pesquisacpf" class="col-md-4">
                <label>CPF: </label>
                <input class="form-control cpf" id="pesqcpf" name="cpf" type="text" />
            </div>
            <div id="pesquisacnpj" class="col-md-4">
                <label>CNPJ: </label>
                <input class="form-control cnpj" id="pesqcnpj" name="cnpj" type="text" />
            </div>
            <div id="pesquisaId" class="col-md-4">
                <label>ID: </label>
                <input class="form-control " id="pesqId" name="id" type="number" min="0" />
            </div>
            <div id="pesquisaUsuario" class="col-md-4" style="display:none;" >
                <label>Usuário: </label>
                <br>
                <select class="form-control pesqUsuario" id="pesqUsuario" name="usuario" type="text" style="width: 460px; margin-top: 21px;"> </select>    
            </div>
            <button class="btn btn-primary" id="pesquisacliente" type="submit" style="margin-top: 21px">Pesquisar</button>
        </div>
    </form>
</div>
<br>
<hr>
<div class="aviso hide">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <div class="alert alert-warning"> <strong>Cliente não encontrado</strong>, verifique os dados!</div>
</div>
<div class="container-fluid painelCliente hide">
    <div class="row">
        <div class="col-md-4" style="border: outset; padding: 9px; min-height: 740px;">
            <div class="row" style="margin-top: 7px">
                <div id="idCliente" class="col-md-3" style="font-size: 16px">
                </div>
                <div class="col-md-3" style="margin-left: 90px">
                    <b>Status:</b> <span id="status"></span>
                </div>
                <div class="col-md-3">
                    <b>Situação:</b> <a id="situacao"></a>
                </div>
            </div>
            <hr>
            <div class="fisica hide">
                <label><b>Nome:</b> <span id="nome1"></span></label><br>
                <label><b>Identidade:</b> <span id="identidade1"></span></label><br>
                <label><b>Orgão Expeditor:</b> <span id="rg_orgao1"></span></label><br>
                <label><b>CPF:</b> <span id="cpf1"></span></label><br>
                <label><b>Data de Nascimento:</b> <span id="data_nascimento1"></span></label><br>
            </div>
            <div class="juridica">
                <label><b>Nome:</b> <span id="nomeemp"></span></label><br>
                <label><b>Razão Social:</b> <span id="razaosocial1"></span></label><br>
                <label><b>Inscrição Estadual:</b> <span id="insc_estadual"></span></label><br>
                <label><b>CNPJ:</b> <span id="cnpj1"></span></label><br>
            </div>
            <hr>
            <div>
                <label><b>Usuário:</b> <span id="usuario1">...</span></label><br>
                <label><b>E-mail:</b> <span id="email1">...</span></label><br>
                <label><b>Telefone:</b> <span id="telefone1"></span></label><br>
                <label><b>Tipo:</b> <span>...</span></label><br>
                <label><b>Origem:</b> <span>ShowNet</span></label><br>
                <label><b>Cadastrado em:</b> <span id="cadastrado1"></span></label><br>
            </div>
            <hr>
            <div>
                <label><b>Endereço:</b> <span id="endereco1"></span></label>
                <div class="row">
                    <div class="col-md-6">
                        <label><b>Bairro:</b> <span id="bairro1"></span></label>
                    </div>
                    <div class="col-sm-4">
                        <label><b>Número:</b> <span id="numero1"></span></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label><b>Cidade:</b> <span id="cidade1"></span></label>
                </div>
                <div class="col-md-6">
                    <label><b>Estado:</b> <span id="uf1"></span></label>
                </div>
            </div>
            <hr>
            <div>
                <label><b>Empresa:</b> <span id="empresa"></span></label><br>
                <label><b>Vendedor:</b> <span id="vendedor"></span></label><br>
                <label><b>Orgão:</b> <span id="orgao"></span></label><br>
            </div>
            <hr>
            <div align="center">
                <button class="btn btn-danger" id="inativarCliente" type="button">Inativar</button>
                <button class="btn btn-warning" id="bloquearCliente" type="button">Block Inad</button>
                <button class="btn btn-warning hide" id="desbloquearCliente" type="button">Desbloqueio Inad.</button>
                <button class="btn negativar btn" id="negativar_positivar" data-acao="0" type="button">Negativar</button>
                <button class="btn positivar hide" id="negativar_positivar" data-acao="1" type="button">Positivar</button>
            </div>
        </div>

        <div class="col-md-8" style="padding: 10px; max-height: 733px; min-height: 740px; border: outset">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#dados" class="show">Dados</a></li>
                <li class="cartao"><a data-toggle="tab" href="#cartao">Cartão</a></li>
                <li class="endereco"><a data-toggle="tab" href="#endereco">Endereço</a></li>
                <li class="contatos"><a data-toggle="tab" href="#contatos">Contatos</a> </li>
                <li class="atendimento hide"><a data-toggle="tab" href="#atendimento">Atendimento</a></li>
                <li class="impostos"><a data-toggle="tab" href="#impostos">Impostos</a></li>
                <li class="plano" id="produtos"><a data-toggle="tab" href="#produto">Produto e Permissões</a></li>
                <li class="linker"><a data-toggle="tab" href="#linker">Integração Linker</a></li>
                <li class="seguranca"><a data-toggle="tab" href="#seguranca">Segurança</a></li>
                <li class="logotipo"><a data-toggle="tab" href="#logotipo">Logotipo</a></li>
                <?php if ($this->auth->is_allowed_block('vis_visualizarperfisdeprofissionais')) : ?>
                    <li class="omniscore"><a data-toggle="tab" href="#omniscore">Omniscore</a></li>
                <?php endif; ?>
                <li class="omniGr"><a data-toggle="tab" href="#omniGr">OmniGr</a></li>
            </ul>
            <div class="tab-content">
                <!-- ABA DADOS CLIENTE -->
                <div id="dados" class="tab-pane fade in active" style="overflow: auto; max-height: 600px">
                    <form id="formDados">
                        <br>
                        <div class="editar">
                            <a id="editar" title="Editar" class="btn" style="position: absolute; right: 15px;">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </a>
                        </div>
                        <div id="salvar" hidden style="float: right">
                            <button id="salvar-dados" type="submit" class="btn btn-primary">Salvar</button>
                            <button id="cancelar" class="btn btn-default">Cancelar</button>
                        </div>
                        <?php include('_tabDados.php') ?>
                    </form>
                </div>
                <!-- ABA CARTOES-->
                <div id="cartao" class="tab-pane fade" style="overflow: auto; max-height: 600px">
                    <form id="formCartao">
                        <br>
                        <div>
                            <div style="float: left;">
                                <button id="adicionar-cartao" title="Adicionar" class="btn btn-circle btn-success adicionar" data-campos="cartao" disabled><i class="fa fa-plus"></i></button>
                            </div>
                            <div id="salvar-cartao" hidden style="float: right">
                                <button id="btnsalvar-cartao" type="submit" class="btn btn-primary">Salvar</button>
                                <button id="cancelar-cartao" class="btn btn-default">Cancelar</button>
                            </div>
                        </div>
                        <div class="editar-cartao">
                            <a id="editar-cartao" title="Editar" class="btn" style="position: absolute; right: 15px;">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </a>
                        </div><br><br><br>
                        <input type="hidden" name="idCliente" class="idcliente">
                        <div class="cartoes">

                        </div>
                    </form>
                </div>
                <!-- ABA ENDERECOS -->
                <div id="endereco" class="tab-pane fade" style="max-height: 600px; overflow: auto">
                    <form id="formEnderecos">
                        <br>
                        <div>
                            <div style="float: left;">
                                <button id="adicionar-end" title="Adicionar" class="btn btn-circle btn-success adicionar" disabled="disabled" data-campos="endereco"><i class="fa fa-plus"></i></button>
                            </div>
                            <div id="salvar-end" hidden style="float: right">
                                <button id="salvar-endereco" type="submit" class="btn btn-primary">Salvar</button>
                                <button id="cancelar-end" class="btn btn-default">Cancelar</button>
                            </div>
                        </div>
                        <div class="editar-end">
                            <a id="editar-end" title="Editar" class="btn" style="position: absolute; right: 15px;">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </a>
                        </div><br><br><br>
                        <input type="hidden" name="idCliente" class="idcliente">
                        <div class="enderecos">
                        </div>
                    </form>
                </div>
                <!-- ABA CONTATOS -->
                <div id="contatos" class="tab-pane fade" style="max-height: 600px; overflow: auto">
                    <br>
                    <div style="float: left;">
                        <button title="Adicionar" class="btn btn-circle btn-success adicionar" data-campos="email"><i class="fa fa-plus"></i></button> <label>E-mail</label>
                    </div>
                    <div class="emails"></div>
                    <hr>
                    <div style="float: left;">
                        <button title="Adicionar" class="btn btn-circle btn-success adicionar" data-campos="telefone"><i class="fa fa-plus"></i></button> <label>Telefone</label>
                    </div>
                    <div class="telefones"></div>
                </div>
                <!-- ABA ATENDIMENTO-->
                <div id="atendimento" class="tab-pane fade">

                </div>
                <!-- ABA IMPOSTOS-->
                <div id="impostos" class="tab-pane fade">
                    <form id="formImpost" enctype="multipart/form-data">
                        <div class="editar-imposto">
                            <a id="editar-imposto" title="Editar" class="btn" style="position: absolute; right: 15px;">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </a>
                        </div>
                        <br>
                        <div id="salvar-imposto" hidden style="float: right">
                            <button id="btnsalvar-imposto" type="submit" class="btn btn-primary">Salvar</button>
                            <button id="cancelar-imposto" class="btn btn-default">Cancelar</button>
                        </div><br>

                        <input type="hidden" name="idCliente" class="idcliente">
                        <div class="row">
                            <br>
                            <div class="form-group col-md-4">
                                <label>Nota fiscal:</label>
                                <input class="form-control dadosImposto" type="text" id="notafiscal" name="cod_servico" disabled>
                            </div>
                            <div class="form-group col-md-8">
                                <label>Descrição do serviço</label>
                                <input class="form-control dadosImposto" type="text" id="descriminacao_servico" name="descriminacao_servico" placeholder="Descrição do serviço" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>IRPJ:</label>
                                <input class="form-control dadosImposto irpj" type="number" step="any" id="irpj" name="irpj" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Contribuição Social:</label>
                                <input class="form-control dadosImposto csll" type="number" step="any" id="csll" name="csll" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">PIS:</label>
                                <input class="form-control dadosImposto pis" type="number" step="any" id="pis" name="pis" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>COFINS:</label>
                                <input class="form-control dadosImposto cofins" type="number" step="any" id="cofins" name="cofins" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label>ISS:</label>
                                <input class="form-control dadosImposto iss" type="number" step="any" name="iss" id="iss" disabled>
                            </div>
                        </div>
                        <label> Anexo:</label>
                        <div class="upload" style="width: 120px; margin-left: 0px; position: absolute;">
                            <div class="hide" id="imgImposto" align="center">
                                <div>
                                    <img id="blah" src="#" style="height: 80px;" />
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="dadosImposto" type='file' id="imgInp" name="image" disabled>
                                <a class="hide" id="removeImg">Remover</a>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- ABA PRODUTO E PERMISSOES-->
                <div id="produto" class="tab-pane fade">
                    <form id="formPermissao">
                        <div class="editar-permissao">
                            <a id="editar-permissao" title="Editar" class="btn" style="position: absolute; right: 15px;">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </a>
                        </div>
                        <br>
                        <div id="salvar-permissao" hidden style="float: right">
                            <button id="btnsalvar-permissao" type="submit" class="btn btn-primary">Salvar</button>
                            <button id="cancelar-permissao" class="btn btn-default">Cancelar</button>
                            <br>
                        </div>
                        <br>
                        <div style="padding: 10px 0px 10px 0px">
                            <label>Observações:</label>
                            <textarea class="form-control adt" style="width: 100%; height: 50px" placeholder="Digite aqui as observações" rows="5" id="observacoes" name="observacoes" disabled></textarea>
                        </div>
                        
                        <div style="padding: 10px 0px 10px 0px">
                            <div class="control-group">
                                <label><?=lang('produtos')?>:</label>
                                <select class="form-control adt" name="ids_produtos" id="ids_produtos" data-placeholder="<?=lang('selecione_os_produtos')?>" multiple="multiple" disabled>
                                    <?= $opcoesSelectProdutos ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- ABA INTEGRACAO LINKER-->
                <div id="linker" class="tab-pane fade">
                    <form id="intLinker">
                        <br>
                        <div class="editar-linker">
                            <a id="editar-linker" title="Editar" class="btn" style="position: absolute; right: 15px;">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </a>
                        </div>
                        <div id="salvar-linker" hidden style="float: right">
                            <button id="btnsalvar-linker" type="submit" class="btn btn-primary">Salvar</button>
                            <button id="cancelar-linker" class="btn btn-default">Cancelar</button>
                        </div>
                        <input type="hidden" name="idCliente" class="idcliente">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Usuário: </label>
                                <input class="form-control dadosLinker" id="usuario_linker" name="usuario_linker" type="text" required disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Senha: </label>
                                <input class="form-control dadosLinker" id="senha_linker" name="senha_linker" type="text" required disabled>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- ABA SEGURANÇA-->
                <div id="seguranca" class="tab-pane fade">
                    <form id="segurancaForm">
                        <br>
                        <div class="editar-seguranca">
                            <a id="editar-seguranca" title="Editar" class="btn" style="position: absolute; right: 15px;">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </a>
                        </div>
                        <div id="salvar-seguranca" hidden style="float: right">
                            <button id="btnsalvar-seguranca" type="submit" class="btn btn-primary">Salvar</button>
                            <button id="cancelar-seguranca" class="btn btn-default">Cancelar</button>
                        </div>
                        <input type="hidden" name="idCliente" class="idcliente">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Forçar Atualização de Senhas (60 dias): </label>

                                <div class="form-check">
                                    <input class="form-check-input dadosForcarTrocaSenha" type="radio" name="forcarTrocaSenha" id="forcarTrocaSenhaInativo" value="0" disabled>
                                    <label class="form-check-label" for="forcarTrocaSenhaInativo">
                                        Não
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input dadosForcarTrocaSenha" type="radio" name="forcarTrocaSenha" id="forcarTrocaSenhaAtivo" value="1" disabled>
                                    <label class="form-check-label" for="forcarTrocaSenhaAtivo">
                                        Sim
                                    </label>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <!-- ABA LOGOTIPO-->
                <div id="logotipo" class="tab-pane fade">
                    <form id="logotipoForm">
                        <div class="editar-logotipo">
                            <a id="editar-logotipo" title="Editar" class="btn" style="position: absolute; right: 15px;">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </a>
                        </div>
                        <br>
                        <div id="salvar-logotipo" hidden style="float: right">
                            <button id="btnsalvar-logotipo" type="submit" class="btn btn-primary">Salvar</button>
                            <button id="cancelar-logotipo" class="btn btn-default">Cancelar</button>
                            <br>
                        </div>
                        <br>

                        <input type="hidden" name="idCliente" class="idcliente">

                        <div style="padding: 10px 0px 10px 0px">
                            <div class="control-group">
                                <div class="controls">
                                    <div class="col-sm-12" style="margin-top: 10px;">
                                        <label>Logotipo atual no Gestor: </label>
                                        <br>
                                        <div style="background-color: #555; height: 50px;">
                                            <img id="logotipo_cliente" style="max-width: 700px; height: 47px; margin-left:2px; padding: 3px; object-fit: cover;" />
                                        </div>
                                    </div>

                                    <div class="col-sm-12" style="margin-top: 10px;">
                                        <label>Nova Logotipo: </label>
                                        <input type="file" class="dadosLogotipo" name="logotipo" id="input_logotipo" accept="<?= implode(',', $extensoesArquivos) ?>" disabled>
                                    </div>
                                    <div class="col-sm-12" style="margin-top: 10px;">
                                        <span class="label label-warning"><?=lang('msg_arquivo2')?>. <?=lang('limite_tam_arq_2m')?></span>
                                    </div>
                                    <div class="col-sm-12">
                                        <br>
                                        <div class="alert alert-warning">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <span> Para melhor visualização, utilize logotipos com pelos menos 500 pixels de largura e 45 pixels de altura. </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- ABA OMNISEARCH-->                
                <div id="omniscore" class="tab-pane fade">
                    <form id="omniscoreForm">                
                        <div class="editar-omniscore">
                            <a id="editar-omniscore" title="Editar" class="btn" style="position: absolute; right: 15px;">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </a>
                        </div>
                        <br>
                        <div id="salvar-omniscore" hidden style="float: right">
                            <button id="btnsalvar-omniscore" type="submit" class="btn btn-primary"><?=lang('salvar')?></button>
                            <button id="cancelar-omniscore" class="btn btn-default"><?=lang('cancelar')?></button>
                            <br>
                        </div>
                        <br>
                        <div class="control-group">
                            <div class="controls col-sm-12">
                                <label style="margin-top: 20px;"><?= lang('liberacao_acesso_omniscore') ?></label>
                                <div>
                                    <p><?= lang('msg_liberacao_acesso_omniscore') ?></p>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input class="dadosOmnisearch acesso_omniscore" type="radio" id="liberar_omniscore" name="acesso" value="liberado" disabled>
                                            <label for="liberar_omniscore"  style="margin-right:10px;" ><?=lang('liberar')?></label>
                                            <input class="dadosOmnisearch acesso_omniscore" type="radio" id="bloquear_omniscore" name="acesso" value="bloqueado" checked disabled>
                                            <label for="bloquear_omniscore"><?=lang('bloquear')?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="omniGr" class="tab-pane fade">
                    <form id="omniGrForm">                
                        <div class="editar-omniGr">
                            <a id="editar-omniGr" title="Editar" class="btn" style="position: absolute; right: 15px;">
                                <i class="fa fa-pencil-square fa-2x"></i>
                            </a>
                        </div>
                        <br>
                        <div id="salvar-omniGr" hidden style="float: right">
                            <button id="btnsalvar-omniGr" type="submit" class="btn btn-primary"><?=lang('salvar')?></button>
                            <button id="cancelar-omniGr" class="btn btn-default"><?=lang('cancelar')?></button>
                            <br>
                        </div>
                        <br>
                        <div class="control-group">
                            <div class="controls col-sm-12">
                                <label style="margin-top: 20px;"><?= lang('liberar_acesso_gr')?> </label>
                                <div>
                                    <p><?= lang('msg_liberar_acesso_gr')?></p>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input class="dadosOmniGr acesso_omniGr" type="radio" id="liberar_gr" name="acesso" value="gr" disabled>
                                            <label for="liberar_gr"  style="margin-right:10px;" >GR</label>
                                            <input class="dadosOmniGr acesso_omniGr" style="margin-left: 15px;" type="radio" id="liberar_ator" name="acesso" value="ator" checked disabled >
                                            <label for="liberar_ator">Ator</label>
                                            <input class="dadosOmniGr acesso_omniGr" style="margin-left: 15px;" type="radio" id="bloquear_acesso_gr" name="acesso" value="bloqueado" checked disabled>
                                            <label for="bloquear_acesso_gr">Bloqueado</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>                            
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12" style="border: outset; padding: 10px">
            <ul class="nav nav-tabs">
                <li class="contratos"><a data-toggle="tab" href="#contratos" class="showc">Contratos</a></li>
                <li class="debitos"><a data-toggle="tab" href="#debitos">Débitos</a></li>
                <li class="usuarios active"><a data-toggle="tab" href="#usuarios">Usuários</a></li>
                <li class="veiculos"><a data-toggle="tab" href="#veiculos">Veículos</a> </li>
                <li class="os"><a data-toggle="tab" href="#os">Ordem de Serviço</a></li>
                <li class="preferencias"><a data-toggle="tab" href="#preferencias">Preferências</a></li>
                <li class="api"><a data-toggle="tab" href="#api">API</a></li>
                <li class="veiculos_espelhados"><a data-toggle="tab" href="#veiculos_espelhados">Veículos espelhados</a></li>
                <li class="equipamentos"><a data-toggle="tab" href="#equipamentos">Equipamentos</a></li>
                <li class="centrais"><a data-toggle="tab" href="#centrais">Centrais</a></li>
                <li class="secretarias"><a data-toggle="tab" href="#secretarias">Secretarias</a></li>
                <li class="filiais"><a data-toggle="tab" href="#filiais">Filiais</a></li>
                <li class="tickets"><a data-toggle="tab" href="#tickets"><?= lang('tickets') ?></a></li>
                <li class="arquivos"><a data-toggle="tab" href="#arquivos"><?= lang('arquivos') ?></a></li>
            </ul>
            <div class="tab-content">
                <!-- ABA CONTRATOS -->
                <div id="contratos" class="tab-pane fade">
                    <br>
                    <div>
                        <div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-plus"></i> Novo Contrato</button>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a title="Contrato" onclick="render(this)" data-toggle="modal" data-modal="#cadastroContrato" data-target="#modal_adicionar_contrato" data-url="<?php echo site_url('contratos/cad_contrato_new') ?>">
                                            Contrato
                                        </a>
                                    </li>
                                    <li><a title="Contrato para Ajuste" onclick="render(this)" data-toggle="modal" data-modal="#cadastroContrato" data-target="#modal_adicionar_contrato" data-url="<?php echo site_url('contratos/cad_contrato_new?ajuste=1') ?>">
                                            Contato p/ ajuste
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="btn-group">
                                <a id="acompanhar-faturamento" target="_blank" class="btn btn-default" title="Acompanhar faturamento"><i class="fa fa-list"></i> Detalhar disponibilidade</a>
                            </div>
                        </div>
                        <div class="placa-alert alert" style="display:none; margin-bottom:20px!important;">
                            <button type="button" class="close close_msn_contratos">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <span id="mensagem"></span>
                        </div>
                    </div><br>
                    <div>
                        <table class="display table responsive table-bordered" id="tabelaContratos" style="text-align:center; width: 100%">
                            <thead>
                                <tr>
                                    <th>Contrato</th>
                                    <th>Vendedor</th>
                                    <th>Ítens</th>
                                    <th>Ítens Ativos</th>
                                    <th>Valor Mensal</th>
                                    <th>Valor Instalação</th>
                                    <th>Status</th>
                                    <th>Digitalizar</th>
                                    <th style="width: 310px;">Administrar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- ABA DEBITOS -->
                <div id="debitos" class="tab-pane fade">
                    <div style="margin-top: 15px;">
                        <div class="debito-alert alert" style="display:none;">
                            <button type="button" class="close_debito" style="float:right;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <span id="mensagem_debito"></span>
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Opções <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#"><i class="fa fa-check"></i> Mesclar Faturas</a></li>
                                <li><a href="#"><i class="fa fa-check"></i> Enviar p/ Cliente</a></li>
                                <li><a onclick='getcheckBox()' style="cursor:pointer;"><i class="fa fa-remove"></i> Cancelar faturas</a></li>
                                <li><a onclick="imprimir_faturas()" style="cursor:pointer;"><i class="fa fa-print"></i> Imprimir faturas</a></li>
                                <li><a onclick='getcheckBox("a cancelar")' style="cursor:pointer;"><i class="fa fa-ban"></i> À Cancelar faturas</a></li>
                                <li class="dropdown-submenu">
                                    <a href="javascript:;">
                                        <i class="fa fa-th-list"></i> Exibir
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo site_url('faturas') ?>"> 10 registros </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <button data-toggle="modal" id="fatura-nova" class="btn btn-primary fatura-nova" onclick="render(this)" data-modal="#novaFatura" data-target="#nova_fatura">
                            <i class="fa fa-plus"></i> Adicionar Fatura
                        </button>
                        <button class="btn btn-primary gerarParaContrato" title="Adicionar">
                            Gerar por Contrato
                        </button>
                        <button data-toggle="modal" onclick="render(this)" data-modal="#impressao-contrato" data-target="#impressao_contrato" id="impressaopcontrato" class="btn btn-primary">
                            <i class="icon-print"></i> Imprimir por Contrato
                        </button>

                        <button title="Copiar link da cobrança recorrente." class="btn btn-primary" onclick="copyToClipboard('cobranca_recorrente_cliente')">
                            Cobrança recorrente
                        </button>                
                        <p style="display:none;" id="cobranca_recorrente_cliente"></p>

                        <button title="<?=lang('dar_baixa_extratos')?>" class="btn btn-primary" id="btnPagamentos"><?=lang('pagamentos')?></button>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group" style="margin-top: 20px">
                                <label>Status:</label>
                                <div class="btn-group">
                                    <button type="btn" class="filtroStatusFatura btn btn-info" data-status="todas">Todas</button>
                                    <button type="btn" class="filtroStatusFatura btn btn-success" data-status="f_pagas">Pagas</button>
                                    <button type="btn" class="filtroStatusFatura btn btn-warning" data-status="f_abertas">A Pagar</button>
                                    <button type="btn" class="filtroStatusFatura btn btn-secundary" data-status="f_a_cancelar">A Cancelar</button>
                                    <button type="btn" class="filtroStatusFatura btn btn-danger" data-status="f_canceladas">Canceladas</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7"><br>
                            <div class="form-group" style="margin-top: 10px">
                                <p style="font-size: 14px; text-align: right; float:right">
                                    <strong>Pagos: </strong><span id="somatorioPagos" style="margin-right:15px">R$ 0,00</span>
                                    <strong>Resta à Pagar: </strong><span id="somatorioFaltaPagar" style="margin-right:15px">R$ 0,00</span>
                                    <strong>Atrasados: </strong><span id="somatorioAtradas" style="margin-right:15px">R$ 0,00</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="col-md-12">
                            <div class="col-md-7"></div>
                            <div class="col-md-5" style="text-align:right;">
                                <select id="filtroDebitos" style="width:180px; height:24px;">
                                    <option value="todos" selected disabled><?= strtoupper(lang('selec_filtro')) ?></option>
                                    <option value="Id">ID</option>
                                    <option value="id_ticket"><?= strtoupper(lang('ticket')) ?></option>
                                    <option value="nota_fiscal"><?= strtoupper(lang('nota_fiscal')) ?></option>
                                    <option value="data_vencimento"><?= strtoupper(lang('venc_fatura')) ?></option>
                                    <option value="secretaria"><?= strtoupper("secretaria") ?></option>
                                </select>
                                <input type="number" id="searchTableDebitos" autocomplete="off" style="width:40%;" disabled>
                                <select id= "secretaria" hidden>
                                    <option value="-1" selected> Selecione a secretaria </option>
                                </select>
                                <button type="button" id="btnSearchDebito" class="btn btn-primary" disabled><i class="fa fa-search"></i></button>
                                <button type="button" id="btnResetsearchDebito" class="btn btn-default" style="">Reset</button>
                            </div>
                        </div>
                        <table class="display table responsive table-striped table-bordered" id="tableDebitos" style="text-align:center; width: 100%">
                            <thead>
                                <th style="padding-left: 10px; width: 52px;" id="selecionarTodos">
                                    <p style="margin: 0px;"><input type="checkbox" id="checkTodos" name="checkTodos"> Todas</p>
                                </th>
                                <th>ID </th>
                                <th>Venc. da Fatura</th>
                                <th>Valor Total</th>
                                <th>Nº Nota fiscal</th>
                                <th>Mês de referência</th>
                                <th>Inicío do P.</th>
                                <th>Fim do P.</th>
                                <th>Data Pagamento</th>
                                <th>Valor Pago</th>
                                <th>Secretaria</th>
                                <th>Ticket</th>
                                <th>Atividade</th>
                                <th>Status</th>
                                <th>Admin</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- ABA USUARIOS -->
                <div id="usuarios" class="tab-pane active">
                    <br>                    
                    <?php if ($this->auth->is_allowed_block('clientes_bloqueio') || $this->auth->get_login('admin', 'email') == 'eduardo@showtecnologia.com') : ?>
                        <!--<div id="bloqueio" style="display: none;">1</div>-->
                    <?php endif; ?>
                    <table id="table_users" class="table responsive table-bordered">
                        <thead>
                            <th>#ID</th>
                            <th>Nome</th>
                            <th>Usuário</th>
                            <th>Celular</th> 
                            <th>Tipo</th>
                            <th><?=lang('status')?></th>
                            <th>Administrar</th>
                            <th>Mais informações</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- ABA VEICULOS -->
                <div id="veiculos" class="tab-pane fade">
                    <br>
                    <div>
                        <span class="label label-info"><i class="fa fa-info-circle"></i></span>
                    </div>

                    <table id="table-veiculos" class="table responsive table-bordered">
                        <thead>
                            <th>#ID</th>
                            <th>Veículo</th>
                            <th>Placa</th>
                            <th>Serial</th>
                            <th>Prefixo</th>
                            <th>Contrato</th>
                            <th>Administrar</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- ABA OS -->
                <div id="os" class="tab-pane fade">
                    <br>
                    <table id="table-os" class="table responsive table-bordered">
                        <thead>
                            <th>OS</th>
                            <th>Tipo</th>
                            <th>Cliente</th>
                            <th>Veículos</th>
                            <th>Data Cadastro</th>
                            <th>Usuário</th>
                            <th>Status</th>
                            <th>Imprimir</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- ABA PREFERENCIAS -->
                <div id="preferencias" class="tab-pane fade">

                </div>
                <!-- ABA API -->
                <div id="api" class="tab-pane fade">
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Chave:</label>
                            <span id="chave-api"></span>
                        </div>
                    </div>
                    <button id="gerar-chaveapi" class="btn btn-primary hide">Gerar chave</button>
                </div>
                <!-- ABA VEICULOS ESPELHADOS -->
                <div id="veiculos_espelhados" class="tab-pane fade">
                    <br>
                    <table id="table_veiculos_espelhados" class="table responsive table-bordered">
                        <thead>
                            <th>Central</th>
                            <th>ID Terminal</th>
                            <th>IP</th>
                            <th>Porta</th>
                            <th>Placa</th>
                            <th>Nome do Cliente</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- ABA EQUIPAMENTOS -->
                <div id="equipamentos" class="tab-pane fade">
                    <br>
                    <div>
                        <ul class="nav nav-tabs">
                            <li class="em-uso active"><a data-toggle="tab" href="#em-uso">Em uso</a></li>
                            <li class="retirados"><a data-toggle="tab" href="#retirados">Retirados</a></li>
                            <li class="disponiveis"><a data-toggle="tab" href="#disponiveis">Disponíveis</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="em-uso" class="tab-pane fade active in">
                                <table id="equipamentos-uso" class="table responsive table-bordered">
                                    <thead>
                                        <th>Placa</th>
                                        <th>Serial</th>
                                        <th>Linha</th>
                                        <th>CCID</th>
                                        <th>Operadora</th>
                                        <th>Data de Cadastro</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div id="retirados" class="tab-pane fade">
                                <table id="equipamentos-retirados" class="table responsive table-bordered">
                                    <thead>
                                        <th class="span2">Serial</th>
                                        <th class="span2">Placa</th>
                                        <th class="span2">Data de retirada</th>
                                        <th class="span2">Status</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div id="disponiveis" class="tab-pane fade">
                                <table id="equipamentos-disponiveis" class="table responsive table-bordered">
                                    <thead>
                                        <th class="span2">Serial</th>
                                        <th class="span2">Linha</th>
                                        <th class="span2">CCID</th>
                                        <th class="span2">Operadora</th>
                                        <th class="span2">Data de Envio</th>
                                        <th class="span2">Data de Recimento</th>
                                        <th class="span2">Status</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ABA CENTRAIS-->
                <div id="centrais" class="tab-pane fade">
                    <br>
                    <button class="btn btn-success" id="vincCentral" data-target="#vincular_central" data-modal="#central" onclick="render(this)" data-url="<?= site_url('clientes/centrais') ?>" data-toggle="modal"> Vincular central</button>
                    <table id="table_centrais" class="table responsive table-bordered">
                        <thead>
                            <th>Central</th>
                            <th>IP</th>
                            <th>Porta</th>
                            <th>Status</th>
                            <th>Administrar</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="secretarias" class="tab-pane fade">

                </div>
                <div id="filiais" class="tab-pane fade">

                </div>
                <!-- ABA DE TICKETS -->
                <div id="tickets" class="tab-pane fade">
                    <div class="col-md-12" style="margin-top: 15px;">
                        <div class="form-group" style="margin-top: 20px">
                            <label><?= lang('status') ?>:</label>
                            <div class="btn-group">
                                <button type="btn" class="btn btn-info statusTickets" data-status="todos"><?= lang('todos') ?> </button>
                                <button type="btn" class="btn btn-warning statusTickets" data-status="t_andamento"><?= lang('em_andamento') ?> </button>
                                <button type="btn" class="btn btn-success statusTickets" data-status="t_concluido"><?= lang('concluidos') ?> </button>
                            </div>
                            <button type="button" class="btn btn-primary addTicket" style="margin-left:15px;" data-toggle="modal" data-target="#novoTicket"><i class="fa fa-plus"> <?= lang('novo_ticket') ?></i></button>
                        </div>

                        <!-- mostra mensagem de respostas -->
                        <div class="ticket-alert" style="display:none; margin-top:10px;">
                            <button type="button" class="close" onclick="fecharMensagem('ticket-alert')">&times;</button>
                            <span id="msgTicket"></span>
                        </div>
                        <div id="exTab2" class="container-fluid">
                            <div class="row">
                                <div class=".col-md-12 .col-sm-12 .col-xs-6 .col-lg-12">
                                    <table class="table table-striped responsive" id="tableTickets" width='100%'>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th><?= lang('usuario') ?></th>
                                                <th><?= lang('email') ?></th>
                                                <th id="itemTicket"><?= lang('placa') ?></th>
                                                <th><?= lang('departamento') ?></th>
                                                <th><?= lang('assunto') ?></th>
                                                <th><?= lang('responsavel') ?></th>
                                                <th><?= lang('ultima_int') ?></th>
                                                <th>Status</th>
                                                <th><?= lang('visualizar') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB DE ARQUIVOS -->
                <div class="tab-pane fade" id="arquivos">
                    <br>
                    <div>
                        <button class="btn btn-primary" id="btnArquivo" data-toggle="modal" data-modal="#novoarquivo" onclick="render(this)" data-target="#novo_arquivo" title="Novo Arquivo"><i class="fa fa-plus"></i>
                            Novo arquivo
                        </button>
                    </div>
                    <table id="tableFiles" class="table responsive table-bordered">
                        <thead>
                            <th>Nome</th>
                            <th>Link</th>
                            <th>Descrição</th>
                            <th>Administrar</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!--modal abertura de novo ticket-->
<div id="novoTicket" class="modal fade" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" style="width:60%!important">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3><?= lang('novo_ticket') ?></h3>
            </div>
            <div class="modal-body">
                <!-- MENSAGENS (ALERTS) -->
                <div class="novo_ticket_alert" style="display:none">
                    <button type="button" class="close" onclick="fecharMensagem('novo_ticket_alert')">&times;</button>
                    <span id="mensagem_ticket_alert"></span>
                </div>

                <form id="formNovoTicket">
                    <div class="col-md-12 form-group">
                        <div class="col-md-6 form-group" style="margin: 0px; padding: 0px;">
                            <div class="col-md-12 form-group">
                                <label class="control-label"><?= lang('selecione_usuario') ?></label>
                                <select class="t_select_usuario" name="id_usuario" data-placeholder="" required style="width: 100%;">
                                    <option value="" disabled selected></option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="control-label"><?= lang('assunto') ?></label>
                                <input type="text" class="form-control span6" name="assunto" id="t_assunto" placeholder="" autocomplete="off" required required />
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="control-label"><?= lang('prioridade') ?></label>
                                <select type="text" id="prioridade" name="prioridade" data-placeholder="" class="form-control span6" required>
                                    <option value="" disabled selected></option>
                                    <option value="1"><?= lang('p_baixa') ?></option>
                                    <option value="2"><?= lang('p_media') ?></option>
                                    <option value="3"><?= lang('p_alta') ?></option>
                                </select>
                            </div>
                            <input type="hidden" name="id_cliente" id="t_input_id_cliente">
                            <input type="hidden" name="usuario" id="t_input_usuario">
                            <input type="hidden" name="nome_usuario" id="t_input_nome_usuario">
                        </div>
                        <div class="col-md-6 form-group" style="margin: 0px; padding: 0px;">
                            <div class="col-md-12 form-group">
                                <label class="control-label" id="item_novo_ticket"><?= lang('selec_placa') ?></label>
                                <select id="t_placa" class="form-control span6" data-placeholder="" name="placa" required disabled>
                                    <option value="" disabled selected></option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="control-label"><?= lang('departamento') ?></label>
                                <select id="t_departamento" class="form-control span6" name="departamento" required>
                                    <option value="" disabled selected></option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label><?= lang('anexar') ?></label>
                                <input type="file" name="arquivo" class="filestyle span6">
                                <span class="help-block" style="font-size: 11px;"><?= lang('msg_arquivo') ?></span>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="t_msg_caracter"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="control-label"> <?= lang('descricao') ?></label>
                            <textarea name="descricao" rows="6" placeholder="" id="t_descricao_ticket" class="form-control span6 t_maxlength" required></textarea>
                            <span class="label" id="content-countdown" style="float:right; color:black" title="500">0</span>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:0px;">
                        <div class="col-md-12 form-group" style="float: right;">
                            <button type="reset" class="btn" onclick="limparModalNovoTickt()"> <?= lang('limpar') ?></button>
                            <button type="submit" class="btn btn-primary" id="salvar_ticket"> <?= lang('adicionar') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--MODAL VISUALIZAR TICKET-->
<div id="modalViewTicket" class="modal fade" data-toggle="modal" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width:60%!important;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3><?= lang('responder_ticket') ?> #<span id="tituloVerTicket"></span></h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <!-- MENSAGENS (ALERTS) -->
                    <div class="resposta_ticket_alert" style="display:none">
                        <button type="button" class="close" onclick="fecharMensagem('resposta_ticket_alert')">&times;</button>
                        <span id="mensagem_resposta_alert"></span>
                    </div>
                    <div class=".col-md-12 .col-sm-12 .col-xs-6 .col-lg-12">
                        <div class="">
                            <h4 id="assuntoTicket"></h4>
                            <span id="dataTicket"></span>
                        </div>
                        <table class="table responsive" id="mensagensTicket" width='100%'>
                            <thead>
                                <tr>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:0px;">
                <div id="divFormComent" style="display:none">
                    <form id="formRespotaTicket">
                        <div class="col-md-12" style="margin-top: 15px;">
                            <div class="col-md-12">
                                <textarea name="resposta" cols="1" rows="3" placeholder="<?= lang('nova_resposta') ?>" class="textarea span9" style="width:100%;" required></textarea>
                            </div>
                            <div class="col-md-12" style="margin-top:10px;">
                                <div class="col-md-5">
                                    <input type="file" name="arquivo" class="filestyle" data-buttonText="<?= lang('arquivo') ?>">
                                    <input type="hidden" name="id_cliente" id="idClienteRespostaTicket" value="">
                                </div>
                                <div class="col-md-7">
                                    <label class="label label-primary" style="float:left;"><?= lang('email_trello') ?> </label>
                                    <input type="text" name="coment_trello" id="coment_trello" class="form-control" value="" />
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top: 15px;">
                                <div class="botao_fechar" style="float: left;">
                                    <button data-href="" class="btn btn-danger fechar_ticket"><?= lang('fechar_ticket') ?></button>
                                </div>
                                <div class="botoes_resposta" style="float: right;">
                                    <button class="btn btn-default" type="reset"><?= lang('limpar') ?></button>
                                    <button type="submit" class="btn btn-primary salvar_resposta_ticket" data-href="#"><?= lang('salvar') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div style="float: right; margin-top: 15px; display:none" id="divReabrirTicket">
                    <a data-href="#" title="" class="btn btn-warning reabrirTicket"><?= lang('reabrir_ticket') ?></a></li>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal negativar/positivar cliente -->
<div id="modal_negativar_positivar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="titulo_neg_posit">Negativar/Positivar</h3>
            </div>
            <div class="modal-body">
                <div class="formulario">
                    <form method="post" name="formcontato" id="ContactForm2" enctype="multipart/form-data" action="<?php echo site_url('clientes/negativarPositivar') ?>">
                        <div class="form-group col-md-12" style="text-align: center;">
                            <textarea id="descricao" name="descricao" placeholder="Descrição" class="form-control" required></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <input type="hidden" class="idcliente" name="id_cliente" id="id_cliente">
                            <input type="hidden" name="acao" id="input_acao">
                        </div>
                        <div class="form-group col-md-12">
                            <input type="file" id="arquivo_cliente" name="arquivo_cliente" accept="application/pdf" required>
                            <span class="label label-warning ">.pdf</span>
                        </div>
                        <div align="right">
                            <button type="submit" class="btn btn-primary negativar_positivar" disabled>Processar</button>
                        </div>
                    </form>
                    <br><br>
                    <div>
                        <table class="table table-striped table-bordered" id="digi_neg_posit">
                            <thead>
                                <th>#</th>
                                <th>Usuário</th>
                                <th>Data</th>
                                <th>Descrição</th>
                                <th>Ação</th>
                                <th>Visualizar</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ABA CONTRATOS -->

<!-- MODAL ADICIONAR CONTRATO-->
<div id="modal_adicionar_contrato" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 class="modal-title" id="myModalLabel">Adicionar contrato</h3>
            </div>
            <div class="modal-body">
                <form id="formAddContrato">
                    <input type="hidden" name="contrato[cliente_id]" class="idcliente">
                    <div id="cadastroContrato">

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="btnsalvar-contrato" type="submit">Salvar</button>
                        <button class="btn btn-default" type="button" data-dismiss="modal">Fechar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal comentarios de uma fatura -->
<div id="modalComentariosFatura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="titulo_coment_fatura"></h3>
            </div>
            <div class="modal-body">
                <div class="formulario">
                    <form id="formComentariosFaturas" >                        
                        <div class="form-group">
                            <label class="col-md-12" style="padding-left:0px" for="comentario_fatura"> <?=lang('comentario')?> </label>                        
                            <textarea id="comentario_fatura" name="comentario" class="form-control" required></textarea>
                        </div>
                        <div align="right">
                            <button type="submit" class="btn btn-primary" id="btnFormComentariosFaturas"><?=lang('comentar')?></button>
                        </div>
                    </form>
                    <br><br>
                    <div>
                        <table class="table table-striped table-bordered" id="tabela_comentarios_faturas">
                            <thead>
                                <th>Id</th>
                                <th><?=lang('comentario')?></th>
                                <th><?=lang('usuario')?></th>
                                <th><?=lang('data_cadastro')?></th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal cancelar uma fatura -->
<div id="modalCancelarFatura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="titulo_cancelar_fatura"></h3>
            </div>
            <div class="modal-body">
                <h5><?=lang('msg_cancelar_fatura')?></h5>
                <div class="formulario">
                    <form id="formCancelarFatura" >                        
                        <div class="form-group">
                            <label class="col-md-12" style="padding-left:0px" for="motivo_cancelar_fatura"> <?=lang('motivo_cancelamento')?> </label>                        
                            <textarea id="motivo_cancelar_fatura" name="motivo" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12" style="padding-left:0px" for="senha_cancelar_fatura"> <?=lang('digite_senha_confirmacao')?> </label>
                            <input size="16" type="password" class="form-control" id="senha_cancelar_fatura" name="senha_exclusao" required />
                        </div>
                        <footer>
                            <div align="right">
                                <button type="submit" class="btn btn-primary" id="btnFormCancelarFatura"><?=lang('cancelar')?></button>
                            </div>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ver/editar uma fatura -->
<div id="modalEditarFatura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false">
    <div class="modal-dialog" style="width:85%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="titulo_editar_fatura"></h3>
            </div>
            <div class="modal-body">
                <div class="formulario">
                    <form id="formEditarFatura" >
                        <div class="row">
                            <div class="col-md-9">
                                <div>
                                    <h4><?=lang('dados_fatura')?></h4><hr>
                                    <div class="form-group col-md-3">
                                        <label class="col-md-12 required" style="padding-left:0px" for="status_fatura"> <?=lang('status')?> </label>
                                        <h5 id="status_fatura" ></h5>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="col-md-12 required" style="padding-left:0px" for="data_emissao"> <?=lang('data_emissao')?> </label>
                                        <input type="date" class="form-control" id="data_emissao_fatura" name="data_emissao" max="2999-12-28" disabled />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="col-md-12 required" style="padding-left:0px" for="data_vencimento"> <?=lang('vencimento')?> </label>
                                        <input type="date" class="form-control" id="data_vencimento_fatura" name="data_vencimento" max="2999-12-28" required disabled />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0px" for="dataatualizado_fatura"> <?=lang('vencimento_att')?> </label>
                                        <input type="date" class="form-control" id="dataatualizado_fatura_fatura" name="dataatualizado_fatura" min="<?=date('Y-m-d')?>" max="2999-12-28" />
                                    </div>                             
                                    <div class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0px" for="chave_nfe"> <?=lang('chave_nfe')?> </label>
                                        <input type="text" class="form-control" id="chave_nfe_fatura" name="chave_nfe" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0px" for="nota_fiscal"> <?=lang('nota_fiscal')?> </label>
                                        <input type="text" class="form-control" id="nota_fiscal_fatura" name="nota_fiscal" />
                                    </div>                                
                                    <div class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0px" for="periodo_inicial"> <?=lang('periodo_inicial')?> </label>
                                        <input type="date" class="form-control" id="periodo_inicial_fatura" name="periodo_inicial" min="2021-01-01" max="2999-12-28" />
                                    </div>                                
                                    <div class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0px" for="periodo_final"> <?=lang('periodo_final')?> </label>
                                        <input type="date" class="form-control" id="periodo_final_fatura" name="periodo_final" min="2021-01-01" max="2999-12-28" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="col-md-12" style="padding-left:0px" for="mes_referencia"> <?=lang('mes_referencia')?> </label>
                                        <input type="text" class="form-control ref" id="mes_referencia_fatura" name="mes_referencia" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="iss"> ISS (%)
                                            <input type="number" class="form-control inputImposto" id="iss_fatura" name="iss" step="any" min="0.0" />
                                        </label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="pis"> PIS (%)
                                            <input type="number" class="form-control inputImposto" id="pis_fatura" name="pis" step="any" min="0.0" />
                                        </label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="cofins"> COFINS (%)
                                            <input type="number" class="form-control inputImposto" id="cofins_fatura" name="cofins" step="any" min="0.0" />
                                        </label>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="irpj"> IRPJ (%)
                                            <input type="number" class="form-control inputImposto" id="irpj_fatura" name="irpj" step="any" min="0.0" />
                                        </label>
                                    </div>                                
                                    <div class="form-group col-md-2">
                                        <label for="csll"> CSLL (%)
                                            <input type="number" class="form-control inputImposto" id="csll_fatura" name="csll" step="any" min="0.0" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h4><?=lang('quatitativos_fatura')?></h4><hr>
                                <div class="well well-small pull-right">
                                    <div class="col-md-12">
                                        <div class="col-md-6"><?=lang('qtd_itens')?></div>
                                        <div class="col-md-6"><span id="qtd_itens_fatura"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6"><?=lang('juros')?></div>
                                        <div class="col-md-6">R$ <span id="juros_fatura"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6"><?=lang('taxa_boleto')?></div>
                                        <div class="col-md-6">R$ <span id="boleto_fatura"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6"><?=lang('subTotal')?></div>
                                        <div class="col-md-6">R$ <span id="subTotal_fatura"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6"><?=lang('total')?></div>
                                        <div class="col-md-6">R$ <span id="total_fatura"></div>
                                    </div>                                                                     
                                </div>
                            </div>
                        </div>
                        <hr>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <h4><?=lang('itens_fatura')?></h4><hr>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-4">
                                    <label><?=lang('descricao')?></label>
                                    <textarea class="form-control" id="descricao_itemFatura" rows="2" placeholder="<?=lang('descricao_item')?>"></textarea>
                                </div>
                                <div class="form-group col-md-2">
                                    <label><?=lang('valor')?></label>
                                    <input class="form-control moeda" id="valor_itemFatura" type="text" placeholder="0,00">
                                </div>
                                <div class="form-group col-md-2">
                                    <label><?=lang('tipo_item')?></label>
                                    <select class="form-control selectItem" id="tipo_itemFatura">
                                        <option value="" disabled selected></option>
                                        <option value="mensalidade"><?=lang('mensalidade')?></option>
                                        <option value="adesao"><?=lang('adesao')?></option>
                                        <option value="taxa"><?=lang('taxa')?></option>
                                        <option value="avulso"><?=lang('outros')?></option>
                                    </select>
                                </div>
                                <div class="col-md-2" id="divContratoItemFatura" style="display:none">
                                    <div>
                                        <label><?=lang('contrato')?></label>
                                        <input class="form-control" id="contrato_itemFatura" type="number">
                                    </div>
                                </div>
                                <div class="col-md-2" id="divTaxaItemFatura" style="display:none">
                                    <div class="form-group">
                                        <label><?=lang('tipo_taxa')?></label>
                                        <select class="form-control" id="tipotaxa_itemFatura">
                                            <option value=""></option>
                                            <option value="juros"><?=lang('juros')?></option>
                                            <option value="boleto"><?=lang('boleto')?></option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" id="valor_total_fatura" name="valor_total">
                                <button type="button" class="btn btn-primary btn-mini" id="btnNovoItemFatura" style="margin-top: 22px"><i class="fa fa-plus"></i> <?=lang('adicionar_item')?></button>
                            </div>
                            <div id="itensDaFatura"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <div class="col-md-12 form-group">
                                    <label><?=lang('motivo')?></label>
                                    <textarea class="form-control" name="motivo_edicao" rows="2" placeholder="<?=lang('motivo_edicao')?>" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div align="right">
                                <button type="submit" class="btn btn-primary" id="btnFormEditarFatura"><?=lang('atualizar')?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Modal editar vendedor-->
<div id="modalVendedor" class="modal fade" data-toggle="modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Vendedor - Contrato <small id="small_contrato"></small></h4>
            </div>
            <div class="modal-body">
                <input id="idContrato_vend" value="" type="hidden">
                <div class="form-group">
                    <label>Vendedor:</label>
                    <select class="vendedor" id="Optvendedor" style="display:none;"></select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary edit_vend">Vincular</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Fim do aditivo -->
<div id="fim_aditivo_modal" class="modal fade" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Fim do Aditivo</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="dataFim_aditivo_contrato" id="input_dataFim_aditivo_contrato">
                    <div class="form-group col-md-12">
                        <label>Selecione o Dia:</label>
                        <input class="form-control" type="date" name="dataFim_aditivo" id="input_dataFim_aditivo" max="2099-12-31" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary dataFim">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal cancelar contrato -->
<div id="myModal_cancelar_contrato" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 class="modal-title" id="myModalLabel">Cancelamento do Contrato</h3>
            </div>
            <div class="modal-body">
                <div id="cancelarContrato">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal digitalizar -->
<div id="myModal_digitalizar" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="background-color: #fff!important;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3>Digitalizar Contrato</h3>
        </div>
        <div class="modal-body">
            <div class="formulario">
                <div class="digi_alert alert" style="display:none; margin-bottom:0!important;">
                    <button type="button" class="close close_digi">&times;</button>
                    <span id="mensagem_digi"></span>
                </div>
                <form method="post" name="formdigi" id="formDigiContrato" enctype="multipart/form-data" action="<?php echo site_url('contratos/digitalizacao_contrato') ?>">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <textarea type="text" id="descricao_digi" name="descricao" placeholder="Descrição" class="input col-md-12" required></textarea>
                            <input type="hidden" name="id_contrato" id="id_contrato_digi">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="file" name="arquivo" id="arquivo_digi" accept="application/pdf" data-buttonText="Arquivo" required>
                        </div>
                        <div class="form-group col-md-6">
                            Arquivo:
                            <span class="label label-warning ">.pdf</span>
                        </div>
                    </div>

                    <div class="modal-footer" style="float: none; margin-left: auto; margin-right: auto; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary btn-small btn_digi" disabled>Enviar</button>
                    </div>
                </form>

                <!-- tabela de documentos digitalizados -->
                <div>
                    <h4><label class="label label-primary col-md-12">Lista de Arquivos</label></h4>
                    <table class="table table-striped table-bordered" id="table-digitalizar">
                        <thead>
                            <th>#</th>
                            <th>Descrição</th>
                            <th>Visualizar</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal placas no Contrato-->
<div id="placas_do_contrato" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3>Placas do Contrato #<span id="id_contrato_placas"><span></h3>
            </div>
            <div class="modal-body">
                <!-- tabela de placas -->
                <div>
                    <table class="table table-bordered" id="placasTable">
                        <thead>
                            <th>#ID</th>
                            <th>Placa</th>
                            <th>Posição</th>
                            <th>Status</th>
                            <th>Ação</th>
                            <!-- <th>Secretaria</th> -->
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal iscas no Contrato-->
<div id="iscas_do_contrato" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog" style="width:90%!important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3>Iscas do Contrato #<span id="id_contrato_iscas"><span></h3>
            </div>
            <div class="modal-body">
                <!-- tabela de iscas -->
                <div>
                    <table class="display table responsive table-bordered" id="iscasTable">
                        <thead>
                            <th>#ID</th>
                            <th>Serial</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Data de Cadastro</th>
                            <th>Status</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cadastrar Iscas em lotes -->
<div class="modal fade" id="nova_isca_lote" tabindex="-1" role="dialog" aria-labelledby="cadastroIscaLoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="cadastroIscaModalLabel">Cadastrar Iscas em Lote</h3>
                <b>
                    <p class="alert alert-warning placa-alert">O arquivo deve conter cinco colunas:
                </b> Serial, Modelo, Marca, Placa, Status - todas iniciando com letra maiúscula. <br>
                <b>O Status deve ser:</b> ativo ou inativo - iniciando com letra minúscula.<br>
                <b>O Serial deve iniciar com a letra "i" maiúscula.</b>&nbsp;Exemplo: I00000000<br>
                <b>Formatos Suportados:</b> .xls e .xlsx<br>
                <b>Formato do Arquivo: </b><a href="<?= base_url('uploads/iscas/modelo_cadastro_iscas_lote.xlsx') ?>" download="Cadastro de Iscas em Lote.xlsx">Baixe aqui</a>
                </p>
                <b>
                    <p class="alert alert-info placa-alert">Apenas iscas que não constam no sistema serão cadastradas!</p>
                </b>
            </div>
            <div class="modal-body">
                <label for="arquivo_digi">Arquivo:</label>
                <div class="form-group">
                    <input type="file" name="arquivo" id="arquivoIscasLote" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" data-buttonText="Arquivo" required>
                    <input type="hidden" name="id_contrato_iscas_lote" id="id_contrato_iscas_lote" />
                    <input type="hidden" name="id_cliente_iscas_lote" id="id_cliente_iscas_lote" />
                    <div id="iscasLoteExcel" style="margin-top:10px;"></div>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" id="descricao_iscas" name="descricao_iscas" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="cadastrarIscasLote">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal sim cards do contrato -->
<div id="chips_do_contrato" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3>Sim card do Contrato #<span id="id_contrato_chips"><span></h3>
            </div>
            <div class="modal-body">
                <!-- tabela de chips -->
                <div>
                    <table class="table table-bordered" id="chipsTable">
                        <thead>
                            <th>#ID</th>
                            <th>Sim Card</th>
                            <th>CCID</th>
                            <th>Status</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal registros/tornozeleira do contrato -->
<div id="tornozeleiras_do_contrato" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3>Tornozeleiras do Contrato #<span id="id_contrato_tornozeleira"><span></h3>
            </div>
            <div class="modal-body">
                <!-- tabela de iscas -->
                <div>
                    <table class="table table-bordered" id="tornozeleirasTable">
                        <thead>
                            <th>#ID</th>
                            <th>Serial</th>
                            <th>Data Cadastro</th>
                            <th>Status</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal registros/suprimentos do contrato -->
<div id="suprimentos_do_contrato" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3>Suprimentos do Contrato #<span id="id_contrato_suprimento"><span></h3>
            </div>
            <div class="modal-body">
                <div>
                    <table class="table table-bordered" id="suprimentosTable">
                        <thead>
                            <th>#ID</th>
                            <th>Serial</th>
                            <th>Descrição</th>
                            <th>Tipo</th>
                            <th>Data Cadastro</th>
                            <th>Ação</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Adicionar nova Placa-->
<div id="nova_placa" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closeModalAddPlaca" data-dismiss="modal" aria-hidden="true">x</button>
                <h3>Cadastrar - Placa</h3>
            </div>
            <div class="modal-body">
                <div class="add_placa_alert alert" style="display:none; margin-bottom: 0px!important;">
                    <button type="button" class="close" onclick="fecharMensagem('add_placa_alert')">&times;</button>
                    <span id="msn_nova_placa"></span>
                </div>
                <form id="formAddPlaca">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Placa:</label>
                            <input class="form-control" name="placa" type="text" maxlength="8" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="addPlaca">Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal adicionar multiplas placas-->
<div id="novas_placas" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Cadastrar - Múltiplas Placas</span></h3>
                <b>
                    <p class="alert alert-warning placa-alert">O arquivo deve conter três colunas:
                </b> Placa, Serial, Nome - todas iniciando com letra maiúscula. <br><b>Formatos Suportados:</b> .xls e .xlsx</p>
                <b>
                    <p class="alert alert-info placa-alert">Apenas veículos que não constam no sistema serão cadastrados!</p>
                </b>
            </div>
            <div class="modal-body">
                <div class="add_multi_placa_alert alert" style="display:none; margin-bottom: 0px!important;">
                    <button type="button" class="close" onclick="fecharMensagem('add_multi_placa_alert')">&times;</button>
                    <span id="msn_nova_multi_placa"></span>
                </div>

                <input class="form-control-file" type="file" name="file" id="fileUpload" />
                <input type="hidden" name="id_contrato_multiplaca" id="id_contrato_multiplaca" />
                <div id="dvExcel" style="margin-top:10px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="cadastrarVeiculos" class="btn btn-primary" disabled>Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal adicionar chip -->
<div id="novo_chip" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog" style="width:25%!important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Cadastrar Novo Chip</h3>
            </div>
            <div class="modal-body">
                <div class="add_chip_alert alert" style="display:none; margin-bottom: 0px!important;">
                    <button type="button" class="close" onclick="fecharMensagem('add_chip_alert')">&times;</button>
                    <span id="msn_novo_chip"></span>
                </div>
                <form id="formAddChip">
                    <!-- <input type="hidden" name="id_contrato" id="id_contrato" /> -->
                    <div class="row">
                        <!-- <div class="form-group col-md-6">
                            <label>Chip:</label>
                            <input type="text" class="form-control" name="chip" autocomplete="off" placeholder="Ex.: 8399999999" required />
                        </div> -->
                        <div class="form-group col-md-12">
                            <label>CCID:</label>
                            <input type="text" class="form-control" name="ccid" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Status</label>
                            <div class="form-check">
                                <input type="radio" name="status" value="ativo" required>
                                Ativo
                                <input type="radio" name="status" value="inativo" style="margin-left: 20px;" required>
                                Inativo
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="addChip">Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal adicionar isca -->
<div id="nova_isca" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 id="myModalLabel1">Cadastrar Nova Isca</h3>
            </div>
            <div class="modal-body">
                <div class="add_isca_alert alert" style="display:none; margin-bottom: 0px!important;">
                    <button type="button" class="close" onclick="fecharMensagem('add_isca_alert')">&times;</button>
                    <span id="msn_nova_isca"></span>
                </div>
                <form id="formAddIsca">
                    <!-- <input type="hidden" name="id_contrato" id="id_contrato" /> -->
                    <div class="row">
                        <!-- <div class="form-group col-md-6">
                            <label>Serial:*</label>
                            <input class="form-control" type="text" autocomplete="off" name="serial"/>
                        </div> -->

                        <div class="form-group col-md-6">
                            <div class="control-group">
                                <label for="serialCadIsca">Serial: </label>
                                <select class="form-control pesq_serial" style="width:100%" id="serialCadIsca" name="serial" type="text"></select>
                            </div>
                        </div>


                        <div class="form-group col-md-6">
                            <label>Marca:*</label>
                            <input class="form-control" type="text" autocomplete="off" name="marca" value="Suntech" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Modelo:*</label>
                            <input class="form-control" type="text" autocomplete="off" name="modelo" />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Status:</label><br>
                            <label class="radio-inline">
                                <input type="radio" name="status" value="1">
                                Ativo
                            </label>
                            <label class="radio-inline" style="margin-left: 20px;">
                                <input type="radio" name="status" value="0">
                                Inativo
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Descrição:</label>
                            <textarea class="form-control" name="descricao" autocomplete="off" rows="3"></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="addIsca">Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal adicionar isca -->
<div id="editar_isca" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeModalEditar" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Editar Isca</h3>
            </div>
            <div class="modal-body">
                <div class="add_isca_alert alert" style="display:none; margin-bottom: 0px!important;">
                    <button type="button" class="close" onclick="fecharMensagem('add_isca_alert')">&times;</button>
                    <span id="msn_nova_isca"></span>
                </div>
                <form id="formEditarIsca">
                    <input type="hidden" name="editarIdIsca" id="editarIdIsca" />
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Serial:*</label>
                            <input class="form-control" type="text" autocomplete="off" id="editarSerialIsca" name="editarSerialIsca" />
                        </div>

                        <div class="form-group col-md-6">
                            <label>Marca:*</label>
                            <input class="form-control" type="text" autocomplete="off" id="editarMarcaIsca" name="editarMarcaIsca" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Modelo:*</label>
                            <input class="form-control" type="text" autocomplete="off" id="editarModeloIsca" name="editarModeloIsca" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Descrição:</label>
                            <textarea class="form-control" id="editarDescricaoIsca" autocomplete="off" rows="3"></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="editarIsca">Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- modal adicionar tornozeleira-->
<div id="nova_tornozeleira" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Cadastrar Nova Tornozeleira</h3>
            </div>
            <div class="modal-body">
                <div class="add_tornozeleira_alert alert" style="display:none; margin-bottom: 0px!important;">
                    <button type="button" class="close" onclick="fecharMensagem('add_tornozeleira_alert')">&times;</button>
                    <span id="msn_nova_tornozeleira"></span>
                </div>
                <form id="formAddTornozeleira">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Serial:</label>
                            <select class="form-control pesq_equipamentos" style="width:100%" name="equipamento" type="text" required></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Status</label>
                            <div class="form-check">
                                <input type="radio" name="status" value="ativo" required>
                                Ativo
                                <input type="radio" name="status" value="inativo" style="margin-left: 20px;" required>
                                Inativo
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="addTornozeleira">Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- modal adicionar suprimento-->
<div id="novo_suprimento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Cadastrar Novo Suprimento</h3>
            </div>
            <div class="modal-body">
                <div class="add_suprimento_alert alert" style="display:none; margin-bottom: 0px!important;">
                    <button type="button" class="close" onclick="fecharMensagem('add_suprimento_alert')">&times;</button>
                    <span id="msn_novo_suprimento"></span>
                </div>
                <form id="formAddSuprimento">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Serial:</label>
                            <select class="form-control pesq_suprimentos" style="width:100%" name="id_suprimento" type="text" required></select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Status</label>
                            <div class="form-check">
                                <input type="radio" name="status" value="ativo" required>
                                Ativo
                                <input type="radio" name="status" value="inativo" style="margin-left: 20px;" required>
                                Inativo
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="addSuprimento">Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!--MODAL ABA DEBITOS-->
<!--Impressao contrato-->
<div id="impressao_contrato" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Impressão de Faturas</h3>
            </div>
            <div class="modal-body">
                <div id="impressao-contrato">Carregando...</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal A cancelar faturas-->
<div id="a_cancela_fatura" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">À Cancelar Faturas</h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Tem certeza que deseja mudar o status desta(s) fatura(s) para à cancelar?</strong>
                    <span id="lista_de_faturas"></span>
                </div>
                <div class="row">
                    <div class="a_cancelar_alert alert col-md-12" style="display:none; margin-bottom: 0px!important;">
                        <button type="button" class="close" onclick="fecharMensagem('a_cancelar_alert')">&times;</button>
                        <span id="msn_a_cancelar"></span>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Descreva o motivo</label>
                        <textarea rows="4" class="form-control" name="motivo_a_cancelar" required></textarea>
                    </div>
                </div>
                <label>Método de Confirmação: </label>
                <button type="button" class="btn btn-warning trocaParaSenha">Trocar</button>

                <div class="row" id="faturas_subst_a_cancelar">
                    <div class="form-group col-md-12">
                        <label>Faturas Substitutas:</label>
                        <input type="text" class="form-control" name="faturas_substitutas" placeholder="Ex.: 1234567,654875,698758" value="">
                    </div>
                </div>
                <div class="row" id="senha_a_cancelar" style="display: none">
                    <div class="form-group col-md-6">
                        <label>Senha:</label>
                        <input class="form-control" type="password" name="senha_a_cancelar">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary aCancelarFatura">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal A cancelar fatura individual-->
<div id="a_cancela_fatura_individual" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">À Cancelar Fatura</h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Tem certeza que deseja mudar o status da fatura <span id="fatura_individual"></span> para À CANCELAR?</strong>
                </div>

                <input type="hidden" name="id_fatura_individual" id="input_id_fatura_individual">
                <input type="hidden" name="status_individual" id="input_status_individual">

                <div class="row">
                    <div class="a_cancelar_ind_alert alert col-md-12" style="display:none; margin-bottom: 0px!important;">
                        <button type="button" class="close" onclick="fecharMensagem('a_cancelar_ind_alert')">&times;</button>
                        <span id="msn_a_cancelar_ind"></span>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Descreva o motivo</label>
                        <textarea rows="4" class="form-control" name="motivo_a_cancelar_individual" required></textarea>
                    </div>
                </div>
                <label>Método de confirmação: </label>
                <button type="button" class="btn btn-warning trocaParaSenhaInd">Trocar</button>

                <div class="row" id="faturas_subst_a_cancelar_ind">
                    <div class="form-group col-md-12">
                        <label>Faturas substitutas:</label>
                        <input type="text" class="form-control" name="faturas_substitutas_ind" placeholder="Ex.: 1234567,654875,698758" value="">
                    </div>
                </div>
                <div class="row" id="senha_a_cancelar_ind" style="display: none">
                    <div class="form-group col-md-6">
                        <label>Senha:</label>
                        <input class="form-control" type="password" name="senha_a_cancelar_ind">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary aCancelarFaturaIndividual">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Gerar por Contrato-->
<div id="gerar_faturas_contrato" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Gerar Faturas por Contrato</h3>
            </div>
            <div class="modal-body">
                <div class="">
                    <div class="itens_novos-alert alert" style="display:none; margin-bottom:0px;">
                        <button type="button" class="close" onclick="fecharMensagem('itens_novos-alert')">&times;</button>
                        <span id="msn_itens_novos"></span>
                    </div>
                    <form id="form_gerar_fatura_contrato">
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label style="float:left; margin-right:10px;">Selecione o contrato</label>
                                <select name="id_contrato" id="id_contrato" class="form-control" style="width:60%" required onfocus="this.size=10;" onblur="this.size=1;" onchange="this.size=1; this.blur();">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-warning btn-mini" data-toggle="collapse" data-target="#person">Personalizar</button>
                            </div>
                        </div>

                        <div id="person" class="collapse out">
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="alert alert-info placa-alert">Escolha as datas para gerar os itens de faturas: adesão e mensalidades do contrato.</p><br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Adesão: </h4>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="control-label">Data Início:</label>
                                    <input type="text" name="data_adesao" class="span6 mes_ano" placeholder="Mês/Ano" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Mensalidade: </h4>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Data Início:</label>
                                    <input type="text" name="data_mensalidade_inicio" class="span6 mes_ano" placeholder="Mês/Ano" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Data Fim:</label>
                                    <input type="text" name="data_mensalidade_fim" class="span6 mes_ano" placeholder="Mês/Ano" />
                                </div>
                            </div>
                        </div>

                        <div class="row" style="display:none; margin: 10px;" id="divTableItensNovos">
                            <table class="display table responsive table-bordered" id="tableItensNovos" style="text-align:center;">
                                <thead>
                                    <tr>
                                        <th>Fatura</th>
                                        <th>Ítem</th>
                                        <th>Vencimento</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secundary btn_limpar_faturas_contrato">Limpar</button>
                <button type="submit" class="btn btn-primary btn_gerar_faturas_contrato">Gerar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal nova fatura -->
<div id="nova_fatura" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel1">
    <div class="modal-dialog" style="width:80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Nova fatura</h3>
            </div>
            <div class="modal-body">
                <form id="formAdicionarFatura">
                    <input type="hidden" name="cliente_id" class="idcliente">
                    <input type="hidden" class="irpj" name="irpj">
                    <input type="hidden" class="csll" name="csll">
                    <input type="hidden" class="pis" name="pis">
                    <input type="hidden" class="cofins" name="cofins">
                    <input type="hidden" class="iss" name="iss">

                    <div id="novaFatura">

                    </div>
                    <div class="modal-footer">
                        <button id="btnsalvar-fatura" type="submit" class="btn btn-primary">Gravar fatura</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancelar faturas-->
<div id="cancela_fatura" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Cancelar Faturas</h3>
            </div>
            <div class="modal-body">
                <div style="display:none">
                    <input type="hidden" name="id_fatura" value="86277">
                </div>
                <div class="alert alert-danger">
                    <strong>Tem certeza que deseja cancelar estas faturas?</strong>
                    <span id="lista_faturas"></span>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">Qual o motivo do cancelamento?</label>
                        <textarea rows="4" class="form-control" name="motivo" required=""></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Digite a senha para confirmar:</label>
                        <input class="form-control" name="senha_exclusao" size="16" type="password" required="">
                    </div>
                </div>
                <div class="row">
                    <div id="resultado_cancelar_fatura" class="col-md-12">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="cancelar_faturas()" class="btn btn-danger">
                    <i class="icon-remove icon-white"></i> Cancelar Faturas
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Enviar anexo-->
<div id="envia_anexo" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" onclick="fecharModalAnexo()" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1">Enviar Anexo</h3>
            </div>
            <div class="modal-body">
                <div class="alerta"></div>
                <div>
                    <strong>Número de Anexos </strong>
                    <label id="countAnexo" class="badge badge-info">0</label>
                    <ul class="listAnexo">

                    </ul>
                </div>

                <form id="formUpload" method="post" enctype="multipart/form-data">
                    <input type="file" name="arquivo" required id="anexo" formenctype="multipart/form-data">
                    <input type="hidden" id="id_fatura" name="id_fatura" value="">
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="sendAnexo">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAIS USUARIOS-->
<!-- Adicionar usuário-->
<div id="novo_usuario" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content" style="padding-left: 20px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h3 id="myModalLabel1">Novo Usuário</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Cliente:</label>
                        <input type="text" class="form-control nome" name="id_cliente" disabled>
                    </div>
                </div>
                <div id="novouser">

                </div>
            </div>
        </div>
    </div>

</div>

<!-- Dados do usuário editar-->
<div id="view_usuario" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Dados Usuário</h3>
            </div>
            <div class="modal-body">
                <div id="body_ediUser">Carregando...</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de grupos do usuario -->
<div id="view_usuario_grupos" class="modal fade bs-example-modal-lg" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1" style="margin-left: 10px;">Grupos do Usuário</h3>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <div id="body_gruposUser">Carregando...</div>
            </div>
        </div>
    </div>
</div>
<!-- Modal de Veículos do grupo  -->
<div class="modal fade" id="view_veiculos_grupo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id='btnFecharMVeicGrupos' aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Veículos do grupo</h4>
            </div>
            <div class="modal-body" id="body_veiculosGrupo" style="padding: 30px;">
                <div id="body_gruposUser">Carregando...</div>
            </div>
        </div>
    </div>
</div>

<!-- Edição de permissões USUARIO -->
<div id="modalEditPerm" class="modal fade" role="dialog" tabindex="-1" data-toggle="modal" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="userPerm" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Permissões do Usuário</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_user" />
                    <table id="editUserPerm" class="table table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="checked_all" data-form="userPerm" /></th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-submit btn-success">Salvar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAIS CENTRAL-->
<div id="vincular_central" class="modal fade" tabindex="-1" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1">Vincular central</h3>
            </div>
            <div class="modal-body">
                <form id="formCentral">
                    <input type="hidden" name="id_cliente" class="idcliente">
                    <div id="central">Carregando...</div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ALTERAR TIPO DE FATURAMENTO -->
<div id="modalTipoFaturamento" class="modal fade" data-toggle="modal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="myModalLabel1"><?= lang('selecionar_faturamento') ?></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <div class="tipo_faturamento_alert alert" style="display:none; margin-bottom:20px!important;">
                            <button type="button" class="close" onclick="fecharMensagem('tipo_faturamento_alert')">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <span id="msgTipoFaturamento"></span>
                        </div>
                        <form id="formTipoFaturamento">
                            <div class="col-md-12" id="divTipoFaturamento">
                            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnSalvarTipoFaturamento"><?= lang('salvar') ?></button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAIS ARQUIVOS-->
<!-- Adicionar arquivo-->
<div id="novo_arquivo" class="modal fade" tabindex="-1" data-toggle="modal " role="dialog" aria-labelledby="myModalLabel1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 id="myModalLabel1">Novo Arquivo</h3>
            </div>
            <form id="formFile" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <label for="arquivoCliente">Arquivo: *</label>
                    <div class="form-group">
                        <input type="file" name="arquivo" id="arquivoCliente" data-buttonText="Arquivo" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <textarea class="form-control" id="descricaoArquivo" name="descricao" rows="3"></textarea>
                    </div>
                    <input id="clienteIdArquivo" type="hidden" value="" name="id_cliente">
                </div>
                <div class="modal-footer">
                    <button id="btnSubmit" class="btn btn-primary" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    
    let tableEqpUso;
    $('#filtroDebitos').on('change', function() {
        var filtro = $(this).val();
        if (filtro == 'secretaria'){
            const id_cliente = $('#id_cliente').val()
            var secretarias = $.ajax({
                url: '<?= site_url('faturas/getSecretarias') ?>',
                dataType: 'json',
                data: {id_cliente: id_cliente},
                type: 'POST',
                success: function(data) {
                    console.log(data)
                    var options = '<option value="">Selecione</option>';
                    $.each(data, function(i, item) {
                        options += '<option value="' + item.id + '">' + item.nome + '</option>';
                    });
                    $('#secretaria').html(options);
                }
            })
            //$('#secretaria').select2({
            //});
            $('#secretaria').show();
            
            $("#searchTableDebitos").hide()
        }
        else{
            $('#secretaria').hide();
            $("#searchTableDebitos").show()
        }
    });



    //pega as permissões do usuário no shownet e passa para ser trabalho no JS
    const permissoes = JSON.parse('<?= isset($permissoes) ? $permissoes : "{}" ?>');
    $('.pesq_suprimentos').select2({
        ajax: {
            url: '<?= site_url('suprimentos/ajaxListSelect') ?>',
            dataType: 'json',
            delay: 1000,

        },
        placeholder: "Selecione o suprimento",
        allowClear: true,
        minimumInputLength: 3,
    });

    function limparSerialSuprimento() {
        $('.pesq_suprimentos').empty().trigger('change');
    }

    $('.pesq_equipamentos').select2({
        ajax: {
            url: '<?= site_url('equipamentos/ajaxListSelect2') ?>',
            dataType: 'json',
            delay: 1000,
        },
        placeholder: "Selecione o equipamento",
        allowClear: true,
        minimumInputLength: 3,
    });

    function limparSerial() {
        $('.pesq_equipamentos').empty().trigger('change');
    }

    //variavel global
    var id_cliente = false;
    let logo_gestor = '<?=base_url('media/img/Logo_Show_110px_negativo.png')?>';

    // Permissões atuais do cliente
    //var permissoes_client = JSON.parse('<?//= json_encode(array()) ?>//');
    $('#pesquisa').show();
    $('#pesquisacpf').hide();
    $('#pesqcpf').attr('disabled', true);
    $('#pesquisacnpj').hide();
    $('#pesqcnpj').attr('disabled', true);
    $('#pesquisaId').hide();
    $('#pesqId').attr('disabled', true);
    $('#pesquisaUsuario').hide();
    $('#pesqUsuario').attr('disabled', true); 

    //Render Modal
    function render(a) {
        var url = $(a).attr('data-url');
        var modal = $(a).data('modal');
        $(modal).html('<h5>Carregando...</h5>');
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $(modal).html(data);
            },
            error: function(e) {
                console.log(e);
            }
        });
    }

    //Pesquisa
    $('#sel-pesquisa').on('change', function() {
        let tipo = $('#sel-pesquisa').val();
        if (tipo == 0) {
           // console.log('pesquisa');
            $('#pesquisa').show();
            $('#pesqnome').removeAttr('disabled');
            $('#pesquisacpf').hide();
            $('#pesqcpf').attr('disabled', true);
            $('#pesquisacnpj').hide();
            $('#pesqcnpj').attr('disabled', true);
            $('#pesquisaId').hide();
            $('#pesqId').removeAttr('disabled', true);
            $('#pesquisaUsuario').hide();
            $('#pesqUsuario').attr('disabled', true);
        } else if (tipo == 1) {
            $('#pesquisacpf').show();
            $('#pesqcpf').removeAttr('disabled');
            $('#pesquisacnpj').hide();
            $('#pesqcnpj').attr('disabled', true);
            $('#pesquisa').hide();
            $('#pesqnome').attr('disabled', true);
            $('#pesquisaId').hide();
            $('#pesqId').removeAttr('disabled', true);
            $('#pesqUsuario').attr('disabled', true);
            $('#pesquisaUsuario').hide();
        } else if (tipo == 2) {
            $('#pesquisacnpj').show();
            $('#pesqcnpj').removeAttr('disabled');
            $('#pesquisacpf').hide();
            $('#pesqcpf').attr('disabled', true);
            $('#pesquisa').hide();
            $('#pesqnome').attr('disabled', true);
            $('#pesquisaId').hide();
            $('#pesqId').removeAttr('disabled', true);
            $('#pesqUsuario').attr('disabled', true);
            $('#pesquisaUsuario').hide();
        } else if (tipo == 4) {
            $('#pesquisaUsuario').show();
            $('#pesqUsuario').removeAttr('disabled');
            $('#pesquisacpf').hide();
            $('#pesqcpf').attr('disabled', true);
            $('#pesquisacnpj').hide();
            $('#pesqcnpj').attr('disabled', true);
            $('#pesquisa').hide();
            $('#pesqnome').attr('disabled', true);
            $('#pesquisaId').hide();
            $('#pesqId').removeAttr('disabled', true);
        } else {
            $('#pesquisaId').show();
            $('#pesqId').removeAttr('disabled');
            $('#pesquisacpf').hide();
            $('#pesqcpf').attr('disabled', true);
            $('#pesquisa').hide();
            $('#pesqnome').attr('disabled', true);
            $('#pesquisacnpj').hide();
            $('#pesqcnpj').attr('disabled', true);
            $('#pesqUsuario').attr('disabled', true);
            $('#pesquisaUsuario').hide();
        }
    });

    //imagem
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imgInp").change(function() {
        readURL(this);
        $('#removeImg').removeClass('hide');
        $('#imgImposto').removeClass('hide')
    });

    $('#removeImg').click(function() {
        $("#imgInp").val('');
        $('#removeImg').addClass('hide');
        $('#imgImposto').addClass('hide')
    })

    $(document).ready(function() {

        $('.vendedor').select2({
            width: '400px'
        });

        $('.pesqnome').select2({
            ajax: {
                url: '<?= site_url('clientes/ajaxListSelect') ?>',
                dataType: 'json',
                delay: 1000,
            },
            placeholder: "Selecione o cliente",
            allowClear: true,
            minimumInputLength: 3,
        });

        $('#pesqUsuario').select2({
            
            ajax: {
                url: '<?= site_url('clientes/listarUsuarios') ?>',
                dataType: 'json',
                delay: 1000,
                processResults: function(data) {
                    console.log(data);
                    return {
                        results: $.map(data.results, function(item) {
                            return {
                                text: item.text + ' - ' + item.text2,
                                id: item.id
                            }
                        })
                    };
                },
            },

            placeholder: "Selecione o usuário",
            allowClear: true,
            minimumInputLength: 3,
        });
    })

    $('#formPesquisa').submit(function() {
        var data = $(this).serialize();
        $('#pesquisacliente').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Pesquisando')
        $.ajax({
            url: site_url + '/clientes/get_cliente',
            dataType: 'json',
            type: 'post',
            data: data,  
            success: function(callback) {
                limpar();
                if (callback == '') {
                    $('#pesquisacliente').removeAttr('disabled').html('Pesquisar');
                    $('.aviso').removeClass('hide');
                               
                } else if (callback.cliente.cnpj) {
                    $('#pesquisacliente').removeAttr('disabled').html('Pesquisar')
                    telefone_indice = callback.telefones.length ? callback.telefones.length : 0;
                    cnpj = callback.cliente.cnpj;
                    id_cliente = callback.cliente.id;
                    logotipo_cliente = callback.logotipo;
                    informacoes = callback.cliente.informacoes;
                    cartao_indice = callback.cartoes.length ? callback.cartoes.length : 0;
                    endereco_indice = callback.enderecos.length ? callback.enderecos.length : 0;
                    email_indice = callback.emails.length ? callback.emails.length : 0;
                    id_plano = callback.cliente.id_plano ? callback.cliente.id_plano : null;
                    permissoes_client = callback.cliente.permissoes ? callback.cliente.permissoes : null;
                    gmt = callback.cliente.gmt ? callback.cliente.gmt : null;
                    dados_omniscore = callback.dados_omniscore;
                    ids_produtos = JSON.parse(callback.cliente.ids_produtos) || null;

                    blockPanelsClientOmni(callback.cliente.informacoes);
                    painelLateralPJ(callback)
                    statusCliente(callback.cliente.status)
                    empresaCliente(callback.cliente.informacoes)
                    preencherDados(callback);
                    preencherEndereco(callback)
                    preencherContatos(callback);
                    preencherCartoes(callback);
                    botoesUrls(id_cliente);
                    initDataTable(id_cliente);
                    setarRadioSeguranca(callback.cliente.troca_periodica_senhas);
                    preencherDadosOmnisearch(dados_omniscore);
                    preencherDadosOmniGr(callback.cliente.acesso_omnigr);


                    $('.painelCliente').removeClass('hide');
                    $('.idcliente').val(callback.cliente.id);
                    $('.juridica').removeClass('hide');
                    $('.fisica').addClass('hide');
                    $("#logotipo_cliente").attr("src", logotipo_cliente);

                } else {
                    $('#pesquisacliente').removeAttr('disabled').html('Pesquisar')
                    
                    id_cliente = callback.cliente.id;
                    informacoes = callback.cliente.informacoes;
                    logotipo_cliente = callback.logotipo;
                    telefone_indice = callback.telefones.length ? callback.telefones.length : 0;
                    cartao_indice = callback.cartoes.length ? callback.cartoes.length : 0;
                    endereco_indice = callback.enderecos.length ? callback.enderecos.length : 0;
                    email_indice = callback.emails.length ? callback.emails.length : 0;
                    id_plano = callback.cliente.id_plano ? callback.cliente.id_plano : null;
                    permissoes_client = callback.cliente.permissoes ? callback.cliente.permissoes : null;
                    gmt = callback.cliente.gmt ? callback.cliente.gmt : null;
                    dados_omniscore = callback.dados_omniscore;
                    ids_produtos = JSON.parse(callback.cliente.ids_produtos) || null;

                    $('.painelCliente').removeClass('hide')
                    $('.fisica').removeClass('hide');
                    $('.juridica').addClass('hide');
                    $('.idcliente').val(callback.cliente.id);
                    $("#logotipo_cliente").attr("src", logotipo_cliente);
                    
                    blockPanelsClientOmni(callback.cliente.informacoes);
                    preencherDadosOmnisearch(dados_omniscore);                    
                    painelLateralPF(callback)
                    statusCliente(callback.cliente.status)
                    empresaCliente(callback.cliente.informacoes)
                    preencherDados(callback);
                    preencherEndereco(callback)
                    preencherContatos(callback);
                    preencherCartoes(callback);
                    botoesUrls(id_cliente);
                    initDataTable(id_cliente);
                    setarRadioSeguranca(callback.cliente.troca_periodica_senhas);

                }
                if (informacoes === 'SIMM2M') {
                    $('#itemTicket').text("<?= lang('chip') ?>");
                    $('#item_novo_ticket').text("<?= lang('selec_chip') ?>");
                }
            },
        });
        return false;
    });

    function painelLateralPJ(callback) {
        $('#nomeemp').text(callback.cliente.nome);
        if (callback.situacao.diff != false) {
            callback.situacao.diff >= 1 ? $('#situacao').html('<i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i>').attr('title', 'Situação: Inadimplente') : $('#situacao').html('<i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i>').attr('title', 'Situação: Adimplente');
        }
        $('#idCliente').text('ID: ' + callback.cliente.id);
        $('#razaosocial1').text(callback.cliente.razao_social);
        $('#insc_estadual').text(callback.cliente.inscricao_estadual);
        $('#cnpj1').text(callback.cliente.cnpj);
        $('#usuario1').text(callback.cliente.email);
        $('#email1').text(callback.cliente.email);
        $('#telefone1').text(callback.cliente.fone);
        $('#cadastrado1').text(callback.cliente.data_cadastro);
        $('#endereco1').text(callback.cliente.endereco);
        $('#numero1').text(callback.cliente.numero);
        $('#bairro1').text(callback.cliente.bairro);
        $('#cidade1').text(callback.cliente.cidade);
        $('#uf1').text(callback.cliente.uf);
        $('#vendedor').text(callback.vendedor);
        $('#orgao').text(callback.cliente.orgao);
    }

    function painelLateralPF(callback) {

        let datanascimento = callback.cliente.data_nascimento.replace(/(\d*)-(\d*)-(\d*).*/, '$3/$2/$1');

        if (callback.situacao.diff != false) {
            callback.situacao.diff >= 1 ? $('#situacao').html('<i class="fa fa-thumbs-o-down fa-lg" aria-hidden="true"></i>').attr('title', 'Situação: Inadimplente') : $('#situacao').html('<i class="fa fa-thumbs-o-up fa-lg" aria-hidden="false"></i>').attr('title', 'Situação: Adimplente');
        }
        $('#nome1').text(callback.cliente.nome);
        $('#idCliente').text('ID: ' + callback.cliente.id);
        $('#identidade1').text(callback.cliente.identidade);
        $('#rg_orgao1').text(callback.cliente.orgaoexp);
        $('#cpf1').text(callback.cliente.cpf);
        $('#data_nascimento1').text(datanascimento);
        $('#usuario1').text(callback.cliente.email);
        $('#email1').text(callback.cliente.email);
        $('#telefone1').text(callback.cliente.fone);
        $('#cadastrado1').text(callback.cliente.data_cadastro);
        $('#endereco1').text(callback.cliente.endereco);
        $('#numero1').text(callback.cliente.numero);
        $('#bairro1').text(callback.cliente.bairro);
        $('#cidade1').text(callback.cliente.cidade);
        $('#uf1').text(callback.cliente.uf);
        $('#vendedor').text(callback.vendedor);
        $('#orgao').text(callback.cliente.orgao);
    }

    function preencherDados(callback) {
        $('.nome').val(callback.cliente.nome);
        callback.cliente.informacoes == 'EMBARCADORES' ? $('.tipocliente').val('PARCEIRO') : $('.tipocliente').val('CLIENTE')
        $('#identidade').val(callback.cliente.identidade);
        $('#orgaoexp').val(callback.cliente.orgaoexp);
        $('#cpf').val(callback.cliente.cpf);
        $('.empresa').val(callback.cliente.informacoes);
        $('.orgao').val(callback.cliente.orgao);
        $('#data_nascimento').val(callback.cliente.data_nascimento);
        $('#id_vendedor').val(callback.cliente.id_vendedor);
        $('#id_vendedor').trigger('change'); //select2
        callback.cliente.opentech == 1 ? $('.opentech').attr('checked', true) : '';
        callback.cliente.velocidade_via == 1 ? $('.excessoVia').attr('checked', true) : '';
        $('#razaosocial').val(callback.cliente.razao_social);
        $('#cnpj').val(callback.cliente.cnpj);
        $('#ie').val(callback.cliente.inscricao_estadual);
        $('#notafiscal').val(callback.cliente.cod_servico);
        $('#descriminacao_servico').val(callback.cliente.descriminacao_servico);
        $('.irpj').val(callback.cliente.IRPJ);
        $('.csll').val(callback.cliente.Cont_Social);
        $('.pis').val(callback.cliente.PIS);
        $('.cofins').val(callback.cliente.COFINS);
        $('.iss').val(callback.cliente.ISS);
        $('#usuario_linker').val(callback.cliente.usuario_linker);
        $('#senha_linker').val(callback.cliente.senha_linker);
        $('#gmt').val(callback.cliente.gmt);
    }

    function preencherEndereco(callback) {
        if (callback.enderecos != false) {
            callback.enderecos.forEach((e, index) => {
                var template = [
                    '<div class="row" id="' + index + '">',
                    '<label> | Endereço</label>',
                    '<a href="#" title="Remover" class="remover dadosEnd"  data-id="' + e.id + '" style="float: left; margin-left: 20px; color: red" disabled="true"><i class="fa fa-trash-o fa-lg"></i></a><br></br>',
                    '<div class="form-group col-md-3">',
                    '<input type="hidden" name="endereco[' + index + '][id]" value="' + e.id + '">',
                    '<label>CEP:</label>',
                    '<input class="form-control dadosEnd cep" value="' + e.cep + '" name="endereco[' + index + '][cep]" type="text" required disabled>',
                    '</div>',
                    '<div class="form-group col-md-6">',
                    '<label>Rua:</label>',
                    '<input class="form-control dadosEnd" value="' + e.rua + '" name="endereco[' + index + '][rua]" type="text" required disabled>',
                    '</div>',
                    '<div class="form-group col-md-1">',
                    '<label>Número:</label>',
                    '<input class="form-control dadosEnd" value="' + e.numero + '" name="endereco[' + index + '][numero]" type="text" disabled>',
                    '</div>',
                    '<div class="form-group col-md-4">',
                    '<label>Bairro:</label>',
                    '<input class="form-control dadosEnd" value="' + e.bairro + '" name="endereco[' + index + '][bairro]" type="text" disabled>',
                    '</div>',
                    '<div class="form-group col-md-2">',
                    '<label>Estado:</label>',
                    '<select class="form-control dadosEnd" id="sel-estado' + index + '" name="endereco[' + index + '][uf]" required disabled>',
                    '<option value="">UF</option>',
                    '<option value="AC">AC</option>',
                    '<option value="AL">AL</option>',
                    '<option value="AP">AP</option>',
                    '<option value="AM">AM</option>',
                    '<option value="BA">BA</option>',
                    '<option value="CE">CE</option>',
                    '<option value="DF">DF</option>',
                    '<option value="ES">ES</option>',
                    '<option value="GO">GO</option>',
                    '<option value="MA">MA</option>',
                    '<option value="MT">MT</option>',
                    '<option value="MS">MS</option>',
                    '<option value="MG">MG</option>',
                    '<option value="PA">PA</option>',
                    '<option value="PB">PB</option>',
                    '<option value="PR">PR</option>',
                    '<option value="PE">PE</option>',
                    '<option value="PI">PI</option>',
                    '<option value="RJ">RJ</option>',
                    '<option value="RN">RN</option>',
                    '<option value="RS">RS</option>',
                    '<option value="RO">RO</option>',
                    '<option value="RR">RR</option>',
                    '<option value="SC">SC</option>',
                    '<option value="SP">SP</option>',
                    '<option value="SE">SE</option>',
                    '<option value="TO">TO</option>',
                    '</select>',
                    '</div>',
                    '<div class="form-group col-md-4">',
                    '<label>Cidade:</label>',
                    '<input class="form-control dadosEnd" value="' + e.cidade + '" name="endereco[' + index + '][cidade]" type="text" required disabled>',
                    '</div>',
                    '<div class="form-group col-md-4">',
                    '<label>Complemento:</label>',
                    '<input class="form-control dadosEnd" value="' + e.complemento + '" name="endereco[' + index + '][complemento]" type="text" disabled>',
                    '</div>',
                    '<div class="form-group col-md-3">',
                    '<label>Latitude:</label>',
                    '<input class="form-control dadosEnd" value="' + e.latitude + '" name="endereco[' + index + '][latitude]" type="text" disabled>',
                    '</div>',
                    '<div class="form-group col-md-3">',
                    '<label>Longitude:</label>',
                    '<input class="form-control dadosEnd" value="' + e.longitude + '" name="endereco[' + index + '][longitude]" type="text" disabled>',
                    '</div>',
                    '</div>'
                ].join('');

                $('.enderecos').append(template);
                $("#sel-estado" + index).val(e.uf);
            });
        }
    }

    function preencherContatos(callback) {
        if (callback.emails != false) {
            callback.emails.forEach((e, index) => {
                var template = `<div class="row" id="contato_email_${ index }">
                        <form id="formEmailContato${index}">
                            <div class="col-md-12">
                                <label>${lang.email}</label>
                            </div>
                            <div class="form-group col-md-4 noMarginTop">
                                <input class="form-control imputContato" name="email" type="email" value="${ e.email }" placeholder="${lang.email}" required disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <select class="form-control imputContato" name="setor" id="sel_email${ index }" required disabled>                            
                                    <option value="">${lang.selecione_setor}</option>
                                    <option value="0">${lang.financeiro}</option>
                                    <option value="1">${lang.diretoria}</option>
                                    <option value="2">${lang.suporte}</option>
                                    <option value="3">${lang.pessoal}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 noMarginTop">
                                <input class="form-control imputContato" type="text" name="observacao" value="${ e.observacao }" placeholder="${lang.observacao}" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <button type="button" title="${lang.editar}" class="btn btn-sm btn-primary editar_contato" data-atributo="email" data-id="${ e.id }" data-index="${ index }"><i class="fa fa-edit"></i></button>
                                <button type="button" title="${lang.remover}" class="btn btn-sm btn-danger remover_contato remover_email_${index}" data-atributo="email" data-id="${ e.id }" data-index="${ index }"><i class="fa fa-trash-o fa-lg"></i></button>
                            </div>
                        </form>
                    </div>`;

                $('.emails').append(template);
                $("#sel_email" + index).val(e.setor);
            });
        }
        if (callback.telefones != false) {
            callback.telefones.forEach((t, index) => {
                var template = `<div class="row" id="contato_telefone_${ index }">
                        <form id="formTelefoneContato${index}">
                            <div class="col-md-12">
                                <label>${lang.telefone}</label>
                            </div>
                            <div class="form-group col-md-1">
                                <input class="form-control dadosCont ddd imputContato" name="ddd" type="text" placeholder="DDD" value="${ t.ddd }" required disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <input class="form-control dadosCont fone imputContato" name="numero" type="text" placeholder="${lang.numero}" value="${ t.numero }" required disabled>
                            </div>
                            <div class="form-group col-md-3">
                                <select class="form-control dadosCont imputContato" name="setor" id="sel_tel${ index }" required disabled>
                                    <option value="">${lang.selecione_setor}</option>
                                    <option value="0">${lang.financeiro}</option>
                                    <option value="1">${lang.diretoria}</option>
                                    <option value="2">${lang.suporte}</option>
                                    <option value="3">${lang.pessoal}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 noMarginTop">
                                <input class="form-control dadosCont imputContato" name="observacao" type="text" placeholder="${lang.observacao}" value="${ t.observacao }" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <button type="button" title="${lang.editar}" class="btn btn-sm btn-primary editar_contato" data-atributo="telefone" data-id="${ t.id }" data-index="${ index }"><i class="fa fa-edit"></i></button>
                                <button type="button" title="${lang.remover}" class="btn btn-sm btn-danger remover_contato remover_telefone_${index}" data-atributo="telefone" data-id="${ t.id }" data-index="${ index }"><i class="fa fa-trash-o fa-lg"></i></button>
                            </div>                        
                        </form>
                    </div>`;

                $('.telefones').append(template);
                $("#sel_tel" + index).val(t.setor);
            })
        }
    }

    function preencherCartoes(callback) {
        if (callback.cartoes != false) {
            callback.cartoes.forEach((c, index) => {
                var template = [
                    '<div class="row" id="' + index + '">',
                    '<a href="#" title="Remover" class="remover" data-id="' + c.id + '" style="float: left; margin-left: 20px; color: red"><i class="fa fa-trash-o fa-lg"></i></a>',
                    '<input type="hidden" name="cartao[' + index + '][id]" value="' + c.id + '">',
                    '<div class="form-group col-sm-4">',
                    '<label> | Nome</label>',
                    '<input class="form-control dadosCartao" value="' + c.nome + '" name="cartao[' + index + '][nome]" type="text" placeholder="Nome do cartão" disabled>',
                    '</div>',
                    '<div class="form-group col-sm-3">',
                    '<label>Número</label>',
                    '<input class="form-control dadosCartao numero_cartao" value="' + c.numero + '" name="cartao[' + index + '][numero]" type="text" placeholder="Número do cartão" disabled>',
                    '</div>',
                    '<div class="form-group col-sm-2">',
                    '<label>Bandeira</label>',
                    '<input class="form-control dadosCartao" value="' + c.bandeira + '" name="cartao[' + index + '][bandeira]" type="text" placeholder="Bandeira" disabled>',
                    '</div>',
                    '<div class="form-group col-sm-1" style="width: 95px;">',
                    '<label>Validade</label>',
                    '<input class="form-control dadosCartao vencimento_cartao" value="' + c.vencimento + '" name="cartao[' + index + '][vencimento]" type="text" placeholder="00/00" disabled>',
                    '</div>',
                    '<div class="form-group col-sm-1" style="width: 85px;">',
                    '<label>CVV</label>',
                    '<input class="form-control dadosCartao codigo_cartao" value="' + c.codigo + '" name="cartao[' + index + '][codigo]" type="text" placeholder="CVV" disabled>',
                    '</div>',
                    '</div>'
                ].join('');

                $('.cartoes').append(template);
            })
        }
    }

    function preencherDadosOmnisearch(dados_omniscore) {

        //limpa o form do omniscore
        $('#omniscoreForm')[0].reset();

        //ploca os dados do omniscore do cliente
        if (dados_omniscore && Object.keys(dados_omniscore).length) {
            $(".dadosOmnisearch").prop("checked", false);
            
            if (dados_omniscore.acesso === 'liberado') $("#liberar_omniscore").prop("checked", true);
            else $("#bloquear_omniscore").prop("checked", true);
        }
    }

    function preencherDadosOmniGr(acessoOmniGr){
        $('#omniGrForm')[0].reset();

        if(acessoOmniGr == 'gr'){
            $("#liberar_gr").prop("checked", true);
        }
        else if(acessoOmniGr == 'ator'){
            $("#liberar_ator").prop("checked", true)
        }
        else if(acessoOmniGr == 'bloqueado'){
            $("#bloquear_acesso_gr").prop("checked", true)
        } 

        

    }

    function botoesUrls(id) {

        $('.fatura-nova').attr('data-url', '<?= site_url('faturas/add_new') ?>?id=' + id)
        $('#cobranca_recorrente_cliente').text('https://gestor.showtecnologia.com/gestor/index.php/api/assinatura_paypal?id_cliente=' + id);
        $('#impressaopcontrato').attr('data-url', "<?php echo site_url('faturas/form_fatura_contrato_new') ?>?id=" + id);
        $('#btnUsuario').attr('data-url', "<?php echo site_url('clientes/get_grupos') ?>?id=" + id);
        $('#acompanhar-faturamento').attr('href', "<?php echo site_url('contratos/faturamento/') ?>/" + id);
    }

    function initDataTable(id, tabela = false) {

        if (tabela == 'debitos') {
            loadTableDebitos();
            loadTotaisTableDebitos(id_cliente);

        } else {

            /* Força a limpeza do search do datatable*/
            table_users.search('').draw();
            tableVeic.search('').draw();
            tableOs.search('').draw();
            tableVeiculosEspelhados.search('').draw();
            tableEqpUso.search('').draw();
            tableEqpDisponiveis.search('').draw();
            tableEqpRetirados.search('').draw();
            tableEqpUso.search('').draw();
            tableCentrais.search('').draw();
            tableTickets.search('').draw();
            tableArquivos.search('').draw();

            //tabela usuario
            table_users.ajax.url('<?= site_url('clientes/ajaxListUsers?id_cliente=') ?>' + id).load()
            userPerm.ajax.url('<?= site_url('/cadastros/ajax_permissoes_cliente') ?>/' + id).load()

            //Tabela contratos
            loadTableContratos();

            //Tabela debitos
            loadTableDebitos();
            loadTotaisTableDebitos(id_cliente);

            // TABELA TICKETS
            $.ajax({
                type: "post",
                dataType: 'json',
                url: site_url + '/webdesk/listarTicketsCliente',
                data: {
                    id_cliente: id_cliente
                },
                beforeSend: function() {
                    // criamos o loading
                    $('#tableTickets > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="15" class="dataTables_empty"><?= lang('carregando') ?></td>' +
                        '</tr>'
                    );
                },
                success: function(callback) {
                    // Atualiza Tabela
                    if (callback.status) {
                        tableTickets.clear();
                        tableTickets.rows.add(callback.data);
                        tableTickets.draw();
                    } else {
                        tableTickets.clear();
                        tableTickets.draw();
                    }

                },
                error: function(callback) {
                    tableTickets.clear();
                    tableTickets.draw();
                }
            });

            //tabela veiculos
            tableVeic.ajax.url('<?= site_url('veiculos/listar_veiculos_cliente') ?>/' + id).load()

            //tabelaOS
            tableOs.ajax.url('<?= site_url('servico/os_cliente_new') ?>/' + id).load();

            //aba API
            $.get('<?php echo site_url('clientes/tab_api/') ?>/' + id_cliente, function(callback) {
                $('#gerar-chaveapi').addClass('hide');
                if (callback.data === '' || typeof callback.data == 'undefined') {
                    $('#gerar-chaveapi').removeClass('hide');
                } else {
                    $('#chave-api').text(callback.data);
                }
            }, 'json');

            //Tabelas Equipamentos
            tableEqpDisponiveis.ajax.url('<?= site_url('clientes/equip_disponiveis') ?>/'+id).load();
            tableEqpRetirados.ajax.url('<?= site_url('clientes/equip_retirados') ?>/'+id).load();
            tableEqpUso.ajax.url('<?= site_url('clientes/equip_emUso') ?>/'+id).load();

            //Veiculos Espelhados
            tableVeiculosEspelhados.ajax.url('<?= site_url('espelhamento/getCentraisGR?cnpj=') ?>' + cnpj).load();

            //Table Centrais
            tableCentrais.ajax.url('<?= site_url('clientes/get_centrais_cliente') ?>/' + id).load();

            //Table Arquivos
            tableArquivos.ajax.url('<?= site_url('arquivos/get_arquivos') ?>/' + id).load();

            $('#clienteIdArquivo').val(id_cliente);
        }

    }

    function setarRadioSeguranca(troca) {
        if (troca == 0 || troca == "0") {
            $("#forcarTrocaSenhaAtivo").attr('checked', false);
            $("#forcarTrocaSenhaInativo").attr('checked', true);
        } else {
            $("#forcarTrocaSenhaInativo").attr('checked', false);
            $("#forcarTrocaSenhaAtivo").attr('checked', true);
        }
    }

    function limpar() {
        $('.aviso').addClass('hide');
        $('.painelCliente').addClass('hide')
        $('.cartoes').html("");
        $('.enderecos').html("");
        $('.emails').html("");
        $('.telefones').html("");
        id_cliente = false;
        permissoes_client = false;
        $('.fatura-nova').removeAttr('data-url');
        $('#impressaopcontrato').removeAttr('data-url');
        $('#btnUsuario').removeAttr('data-url');
        tableEqpUso.clear().draw();
        tableEqpRetirados.clear().draw();
        tableEqpDisponiveis.clear().draw();
        table_users.clear().draw();
        userPerm.clear().draw();
        tableVeic.clear().draw();
        tableOs.clear().draw();
        tableVeiculosEspelhados.clear().draw();
        tableCentrais.clear().draw();
        tableArquivos.clear().draw();
    }

    /** Remove ABAS não utilizadas por clientes omnilink */
    function blockPanelsClientOmni(prestadora) {
        switch (prestadora) {
            case 'OMNILINK':
                $('li.contratos').addClass('hide');
                $('li.debitos').addClass('hide');
                $('li.os').addClass('hide');
                $('li.tickets').addClass('hide');
                $('li.arquivos').addClass('hide');
                $('li.secretarias').addClass('hide');
                break;
            case 'EMBARCADORES':
                $('li.contratos').addClass('hide');
                $('li.debitos').addClass('hide');
                $('li.os').addClass('hide');
                $('li.tickets').addClass('hide');
                $('li.arquivos').addClass('hide');
                $('li.secretarias').addClass('hide');
                break;
            default:
                $('li.contratos').removeClass('hide');
                $('li.debitos').removeClass('hide');
                $('li.os').removeClass('hide');
                $('li.tickets').removeClass('hide');
                $('li.arquivos').removeClass('hide');
                $('li.secretarias').removeClass('hide');
                break;
        }
    }

    function statusCliente(callback) {
        switch (callback) {
            case '0':
                $('#status').text('Bloqueado');
                break;
            case '1':
                $('#status').text('Ativo');
                break;
            case '2':
                $status = $('#status').text('Prospectado');
                break;
            case '3':
                $status = $('#status').text('Prospectado');
                break;
            case '4':
                $status = $('#status').text('A reativar');
                break;
            case '5':
                $status = $('#status').text('Inativo');
                break;
            case '6':
                $status = $('#status').text('Bloqueio Parcial');
                $('#desbloquearCliente').removeClass('hide')
                $('#bloquearCliente').addClass('hide')
                break;
            case '7':
                $status = $('#status').text('Negativo');
                $('#bloquearCliente').attr('disabled', true);
                $('.positivar').removeClass('hide');
                $('.negativar').addClass('hide');
                break;
        }
    }

    function empresaCliente(callback) {
        switch (callback) {
            case 'TRACKER':
                $('#empresa').text('SHOW PRESTADORA DE SERVIÇOS DO BRASIL LTDA - ME');
                break;
            case 'SIGAMY':
                $('#empresa').text('SIGAMY');
                break;
            case 'SIMM2M':
                $('#empresa').text('SIMM2M');
                break;
            case 'NORIO':
                $('#empresa').text('SIGA ME - NORIO MOMOI EPP');
                break;
            case 'OMNILINK':
                $('#empresa').text('OMNILINK');
                break;
            case 'EUA':
                $('#empresa').text('SHOW TECNOLOGIA EUA');
                break;
            case 'EMBARCADORES':
                $('#empresa').text('PARCEIRO');
                break;
        }
    }

    function loadTableContratos() {

        //tabela contratos
        if ($.fn.DataTable.isDataTable('#tabelaContratos')) {
            tableContratos.destroy();
        }
        tableContratos = $('#tabelaContratos').DataTable({
            serverSide: true,
            ordering: false,
            searching: true,
            dom: 'lrtip',
            info: false,
            responsive: true,
            processing: false,
            lengthChange: false,
            order: [0, 'desc'],
            columnDefs: [{
                "className": "dt-center",
                "targets": "_all"
            }],
            otherOptions: {},
            initComplete: function() {
                $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
            },
            ajax: {
                url: site_url + '/contratos/ajax_load_contratos',
                type: 'POST',
                data: {
                    id_cliente: id_cliente,
                    tipo_proposta: function() { return $('#tipo_contrato').val() },
                    tipo_busca: function() { return $('#tipo_busca').val() }
                },
                dataType: 'json',
                beforeSend: function() {
                    // criamos o loading
                    $('#tabelaContratos > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="15" class="dataTables_empty"><?= lang('carregando') ?></td>' +
                        '</tr>'
                    );
                },
            },
            columns: [{
                    data: "contrato"
                },
                {
                    data: "vendedor"
                },
                {
                    data: "itens"
                },
                {
                    data: "itens_ativos"
                },
                {
                    data: "valor_mensal"
                },
                {
                    data: "valor_instalacao"
                },
                {
                    data: "status"
                },
                {
                    data: "digitalizar"
                },
                {
                    data: "administrar"
                }
            ],
            language: lang.datatable
        });

        var search_contratos = document.createRange().createContextualFragment(`
        <div>
            <div class="col-xs-7" style="float:right; left: 45px; padding: 0px 0px; margin-bottom:5px">
                <form id="formBuscaContrato">
                    <div class="col-xs-4" style="padding: 0px 0px">
                        <div class="col-xs-2">
                            <label>${lang.filtrar}:</label>
                        </div>
                        <div class="col-xs-10">
                            <select id="tipo_contrato" class="form-control">
                                <option selected="" value="">${lang.todos}</option>
                                <option value="0">${lang.gestor_rastreador}</option>
                                <option value="1">${lang.chip_dados}</option>
                                <option value="2">${lang.telemetria}</option>
                                <option value="3">${lang.sigame}</option>
                                <option value="4">${lang.rastreador_pessoal}</option>
                                <option value="5">${lang.gestor_entregas}</option>
                                <option value="6">${lang.iscas}</option>
                                <option value="7">${lang.licenca_uso_software}</option>
                            </select>
                        </div>
                    </div>  
                    <div class="col-xs-8" style="padding: 0px 0px">
                        <div class="col-xs-1">
                            <label>${lang.buscar}:</label>
                        </div>
                        
                        <div class="col-xs-4">
                            <input type="search" id="search_tabela" name="search_tabela" placeholder="" class="form-control">
                        </div>
                        
                        <div class="col-xs-4">
                            <select id="tipo_busca" class="form-control">
                                <option selected value="id_contrato">${lang.id} ${lang.contrato}</option>
                                <option value="placa">${lang.placa}</option>
                                <option value="isca">${lang.isca}</option>
                                <option value="tornozeleira">${lang.tornozeleira}</option>
                                <option value="suprimento">${lang.suprimento}</option>
                                <option value="simcard">${lang.sim_card}</option>
                                <option value="placa">${lang.licenca_uso_software}</option>
                            </select>
                        </div>

                        <div class="col-xs-3">
                            <button class="btn btn-primary" id="btnBuscarContrato" title="Pesquisar"><i class="fa fa-search" aria-hidden="true"></i></button>
                            <button class="btn btn-danger" id="btnResetContrato" title="Limpar Pesquisa"><i style="font-size: 15px !important;" class="fa fa-trash" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>`);

        $('#tabelaContratos_wrapper').prepend(search_contratos);

        $("#tipo_contrato").on('change', function() {
            tableContratos.ajax.reload();
        });

        $('#search_tabela').keypress(function (event) {                                       
            if (event.which == 13) {
                let botao = $('#btnBuscarContrato');

                botao.html('<i class="fa fa-spinner fa-spin"></i>');
                tableContratos.search($('#search_tabela').val()).draw();
                botao.html('<i class="fa fa-search"></i>');

                event.preventDefault();

                return false;
            }else{
                return true;
            }
        });

        //Atualiza os valores totais para os itens dos contratos
        att_totais_contratos(id_cliente);
    }

    $(document).on('click', '#btnBuscarContrato', function(e) {
        e.preventDefault();

        let botao = $(this);
        
        botao.html('<i class="fa fa-spinner fa-spin"></i>');
        tableContratos.search($('#search_tabela').val()).draw();
        botao.html('<i class="fa fa-search"></i>');
    });

    //EVENTO PARA LIMPAR TABELA
    $(document).on('click', '#btnResetContrato', function(e) {
        e.preventDefault();
        var botao = $(this);

        $('#search_tabela').val('');
        $('#tipo_busca').val('id_contrato');
        $('#tipo_contrato').val('');

        botao.html('<i class="fa fa-spinner fa-spin"></i>');
        tableContratos.search($('#search_tabela').val()).draw();
        botao.html('<i class="fa fa-trash"></i>');
    });

    //Atualiza os totais para itens de contratos
    function att_totais_contratos(id_cliente) {
        //carrega os somatorios totais que sera mostrado acima da tabela de contratos
        $.ajax({
            url: site_url + '/contratos/total_itens_ativos',
            type: 'POST',
            data: {
                id_cliente
            },
            dataType: 'json',
            success: function(retorno) {
                if (retorno.status) {
                    //setando headers das colunas
                    $(tableContratos.columns(2).header()).html('Ítens <span class="badge badge-info pull-right">' + retorno.totais.itens + '</span>');
                    $(tableContratos.column(3).header()).html('Ítens Ativos <span class="badge badge-info pull-right">' + retorno.totais.itens_ativos + '</span>');
                    $(tableContratos.column(4).header()).html('Valor Mensal <span class="badge badge-info pull-right">' + retorno.totais.mensalidades + '</span>');
                    $(tableContratos.column(5).header()).html('Valor Instalação  <span class="badge badge-info pull-right">' + retorno.totais.instalacao + '</span>');
                    $(tableContratos.column(6).header()).html('Status  <span class="badge badge-info pull-right">' + retorno.totais.contratos_ativos + '</span>');
                }
            }
        });
    }

    

    //TABELA DEBITOS
    function loadTableDebitos() {
        if ($.fn.DataTable.isDataTable('#tableDebitos')) {
            tableDebitos.destroy();
        }
        tableDebitos = $('#tableDebitos').DataTable({
            otherOptions: {},
            initComplete: function() {
                $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
            },
            serverSide: true,
            ordering: true,
            searching: false,
            responsive: true,
            processing: true,
            order: [2, 'desc'],
            paging: true,
            columnDefs: [{
                    "className": "dt-center",
                    "targets": "_all"
                },
                {
                    orderable: false,
                    targets: [0, 5, 13, 14]
                }
            ],
            ajax: {
                url: site_url + '/faturas/ajaxDebitosServeSide',
                type: 'POST',
                data: {
                    id_cliente: id_cliente,
                    status: filtro_status_fatura,
                    filtroDebitos: $('#filtroDebitos').val(),
                    searchDebitos: $('#searchTableDebitos').val(),
                    secretaria: $('#secretaria').val(),
                },
                dataType: 'json'
            },
            columns: [{
                    data: "check",
                    render: function(data, type, row, meta) {
                        return '<p><input type="checkbox" name="cod_fatura[]" class="cod_fatura" value="' + row.id + '"/></p>';
                    }

                },
                {
                    data: "id"
                },
                {
                    data: "data_vencimento"
                },
                {
                    data: "valor_total"
                },
                {
                    data: "nota_fiscal"
                },
                {
                    data: "mes_referencia"
                },
                {
                    data: "inicio_periodo"
                },
                {
                    data: "fim_periodo"
                },
                {
                    data: "data_pagto"
                },
                {
                    data: "valor_pago"
                },
                {
                    data: "secretaria"
                },
                {
                    data: "ticket"
                },
                {
                    data: "atividade"
                },
                {
                    data: "status"
                },
                {
                    data: "admin"
                }
            ],
            language: lang.datatable
        });
    }

    function loadTotaisTableDebitos(id_cliente) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '<?= site_url('/faturas/ajaxTotaisFaturamento') ?>',
            data: {
                id_cliente: id_cliente
            },
            success: function(data) {
                //Atualiza Somatorios
                $('#somatorioPagos').text(data.somatorioPagos);
                $('#somatorioFaltaPagar').text(data.somatorioFaltaPagar);
                $('#somatorioAtradas').text(data.somatorioAtradas);
            },
            error: function(data) {}
        });
    }

    var id_contrato = false;
    var cnpj = false;
    var informacoes = false;
    var tableDigi = false;
    var tableNegativarPositivar = false;
    var tableNegPos = false;
    var tableTickets = false;
    var tableArquivos = false;
    var botaoDigi = false;
    var id_plano = false;
    var permissoes_client = false;
    var planos_escolhidos = false;
    var list_id_placa = '';
    var list_href_ativar = '';
    var list_href_inativar = '';
    var btnAcaoPlaca = '';
    var cartao_indice = 0;
    var endereco_indice = 0;
    var email_indice = 0;
    var telefone_indice = 0;
    var gmt = '';
    var site_url = '<?= site_url() ?>';
    var img_contrato = "<?= versionFile('media/img/icons/contratos', 'contrato.svg') ?>";
    var img_taxa_boleto = "<?= versionFile('media/img/icons/contratos', 'taxa_de_boleto.svg') ?>";
    var img_fatura = "<?= versionFile('media/img/icons/contratos', 'fatura.svg') ?>";
    var img_data_fim = "<?= versionFile('media/img/icons/contratos', 'fim_do_aditivo.svg') ?>";
    var img_digitalizar = "<?= versionFile('media/img/icons/contratos', 'digitalizados.svg') ?>";
    var filtro_status_fatura = 'todas';
    var item_indice_fat = 0;

    $('.adicionar').on('click', function(e) {
        e.preventDefault();

        if ($(this).data('campos') == 'cartao') {

            var template = [
                '<div class="row" id="' + cartao_indice + '">',
                '<a href="#" title="Remover" class="remover" style="float: left; margin-left: 20px; color: red"><i class="fa fa-trash-o fa-lg"></i></a></br>',
                '<div class="form-group col-sm-4">',
                '<label>Nome</label>',
                '<input class="form-control dadosCartao" name="cartao[' + cartao_indice + '][nome]" type="text" placeholder="Nome do cartão">',
                '</div>',
                '<div class="form-group col-sm-3">',
                '<label>Número</label>',
                '<input class="form-control dadosCartao numero_cartao" name="cartao[' + cartao_indice + '][numero]" type="text" placeholder="Número do cartão">',
                '</div>',
                '<div class="form-group col-sm-2">',
                '<label>Bandeira</label>',
                '<input class="form-control dadosCartao" name="cartao[' + cartao_indice + '][bandeira]" type="text" placeholder="Bandeira">',
                '</div>',
                '<div class="form-group col-sm-1" style="width: 95px;">',
                '<label>Validade</label>',
                '<input class="form-control dadosCartao vencimento_cartao" name="cartao[' + cartao_indice + '][vencimento]" type="text" placeholder="00/00">',
                '</div>',
                '<div class="form-group col-sm-1" style="width: 85px;">',
                '<label>CVV</label>',
                '<input class="form-control dadosCartao codigo_cartao" name="cartao[' + cartao_indice + '][codigo]" type="text" placeholder="CVV">',
                '</div>',
                '</div>'
            ].join('');

            $('.cartoes').append(template);
            cartao_indice++

        } else if ($(this).data('campos') == 'endereco') {
            var template = [
                '<div class="row" id="' + endereco_indice + '">',
                '<label> | Endereço</label>',
                '<a href="#" title="Remover" class="remover dadosEnd" style="float: left; margin-left: 20px; color: red"><i class="fa fa-trash-o fa-lg"></i></a><br></br>',
                '<div class="form-group col-md-3">',
                '<label>CEP:</label>',
                '<input class="form-control dadosEnd cep" name="endereco[' + endereco_indice + '][cep]" type="text" required>',
                '</div>',
                '<div class="form-group col-md-6">',
                '<label>Rua:</label>',
                '<input class="form-control dadosEnd" name="endereco[' + endereco_indice + '][rua]" type="text" required>',
                '</div>',
                '<div class="form-group col-md-1">',
                '<label>Número:</label>',
                '<input class="form-control dadosEnd" name="endereco[' + endereco_indice + '][numero]" type="text">',
                '</div>',
                '<div class="form-group col-md-4">',
                '<label>Bairro:</label>',
                '<input class="form-control dadosEnd" name="endereco[' + endereco_indice + '][bairro]" type="text">',
                '</div>',
                '<div class="form-group col-md-2">',
                '<label>Estado:</label>',
                '<select class="form-control dadosEnd"  name="endereco[' + endereco_indice + '][uf]" required>',
                '<option value="">UF</option>',
                '<option value="AC">AC</option>',
                '<option value="AL">AL</option>',
                '<option value="AP">AP</option>',
                '<option value="AM">AM</option>',
                '<option value="BA">BA</option>',
                '<option value="CE">CE</option>',
                '<option value="DF">DF</option>',
                '<option value="ES">ES</option>',
                '<option value="GO">GO</option>',
                '<option value="MA">MA</option>',
                '<option value="MT">MT</option>',
                '<option value="MS">MS</option>',
                '<option value="MG">MG</option>',
                '<option value="PA">PA</option>',
                '<option value="PB">PB</option>',
                '<option value="PR">PR</option>',
                '<option value="PE">PE</option>',
                '<option value="PI">PI</option>',
                '<option value="RJ">RJ</option>',
                '<option value="RN">RN</option>',
                '<option value="RS">RS</option>',
                '<option value="RO">RO</option>',
                '<option value="RR">RR</option>',
                '<option value="SC">SC</option>',
                '<option value="SP">SP</option>',
                '<option value="SE">SE</option>',
                '<option value="TO">TO</option>',
                '</select>',
                '</div>',
                '<div class="form-group col-md-4">',
                '<label>Cidade:</label>',
                '<input class="form-control dadosEnd" name="endereco[' + endereco_indice + '][cidade]" type="text" required>',
                '</div>',
                '<div class="form-group col-md-4">',
                '<label>Complemento:</label>',
                '<input class="form-control dadosEnd" name="endereco[' + endereco_indice + '][complemento]" type="text">',
                '</div>',
                '<div class="form-group col-md-3">',
                '<label>Latitude:</label>',
                '<input class="form-control dadosEnd" name="endereco[' + endereco_indice + '][latitude]" type="text">',
                '</div>',
                '<div class="form-group col-md-3">',
                '<label>Longitude:</label>',
                '<input class="form-control dadosEnd" name="endereco[' + endereco_indice + '][longitude]" type="text">',
                '</div>',
                '</div>'
            ].join('')

            $('.enderecos').append(template);
            endereco_indice++
        } else if ($(this).data('campos') == 'email') {
            var template = `<div class="row" id="contato_email_${ email_indice }">
                    <form id="formEmailContato${email_indice}">
                        <div class="col-md-12">
                            <label>${lang.email}</label>
                        </div>
                        <div class="form-group col-md-4 noMarginTop">
                            <input class="form-control imputContato" name="email" type="email" placeholder="${lang.email}" required >
                        </div>
                        <div class="form-group col-md-3">
                            <select class="form-control imputContato" name="setor" id="sel_email${ email_indice }" required >                            
                                <option value="">${lang.selecione_setor}</option>
                                <option value="0">${lang.financeiro}</option>
                                <option value="1">${lang.diretoria}</option>
                                <option value="2">${lang.suporte}</option>
                                <option value="3">${lang.pessoal}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 noMarginTop">
                            <input class="form-control imputContato" type="text" name="observacao" placeholder="${lang.observacao}" >
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" title="${lang.salvar}" class="btn btn-sm btn-primary salvar_contato" data-index="${email_indice}" data-atributo="email" style="float: left; margin-right: 3px;"><i class="fa fa-save"></i></button>
                            <button type="button" title="${lang.remover}" class="btn btn-sm btn-danger remover_contato remover_email_${email_indice}" data-index="${email_indice}" data-atributo="email"><i class="fa fa-trash-o fa-lg"></i></button>
                        </div>
                    </form>
                </div>`;

            $('.emails').append(template);
            email_indice++;

        } else if ($(this).data('campos') == 'telefone') {
            var template = `<div class="row" id="contato_telefone_${ telefone_indice }">
                    <form id="formTelefoneContato${telefone_indice}">
                        <div class="col-md-12">
                            <label>${lang.telefone}</label>
                        </div>
                        <div class="form-group col-md-1">
                            <input class="form-control dadosCont ddd imputContato" name="ddd" type="text" placeholder="DDD" required>
                        </div>
                        <div class="form-group col-md-3">
                            <input class="form-control dadosCont fone imputContato" name="numero" type="text" placeholder="${lang.numero}" required>
                        </div>
                        <div class="form-group col-md-3">
                            <select class="form-control dadosCont imputContato" name="setor" id="sel_tel${ telefone_indice }" required>
                                <option value="">${lang.selecione_setor}</option>
                                <option value="0">${lang.financeiro}</option>
                                <option value="1">${lang.diretoria}</option>
                                <option value="2">${lang.suporte}</option>
                                <option value="3">${lang.pessoal}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 noMarginTop">
                            <input class="form-control dadosCont imputContato" name="observacao" type="text" placeholder="${lang.observacao}">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" title="${lang.salvar}" class="btn btn-sm btn-primary salvar_contato" data-atributo="telefone" data-index="${telefone_indice}" style="float: left; margin-right: 3px;"><i class="fa fa-save"></i></button>
                            <button type="button" title="${lang.remover}" class="btn btn-sm btn-danger remover_contato remover_telefone_${telefone_indice}" data-atributo="telefone" data-index="${telefone_indice}"><i class="fa fa-trash-o fa-lg"></i></button>
                        </div>
                    </form>
                </div>`;

            $('.telefones').append(template);
            telefone_indice++;
        }
    });

    $('#formDados').submit(function(e) {
        e.preventDefault()
        var data = $(this).serialize();
        $('#salvar-dados').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('cadastros/atualizar_dados') ?>',
            data: data,
            success: function(callback) {
                if (callback.status === 'OK') {
                    $('#salvar-dados').removeAttr('disabled').text('Salvar');
                    $('.editar').removeClass('hide');
                    $('#salvar').attr('hidden', true);
                    $('.dadosC').attr('disabled', true)
                    alert(callback.msg);
                } else {
                    $('#salvar-endereco').removeAttr('disabled').html('Salvar');
                    alert(callback.msg);
                }
            },
            error: function() {
                $('#salvar-dados').removeAttr('disabled').html('Salvar');
                alert('Ocorreu um erro, tente novamente!')
            }
        });
        return false;
    });


    $('#formEnderecos').submit(function(e) {
        e.preventDefault()
        var data = $(this).serialize();
        $('#salvar-endereco').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('cadastros/atualizar_endereco') ?>',
            data: data,
            success: function(callback) {
                if (callback.status === 'OK') {
                    $('#salvar-endereco').removeAttr('disabled').text('Salvar');
                    $("#adicionar-end").attr('disabled', 'disabled');
                    $('.editar-end').removeClass('hide');
                    $('#salvar-end').attr('hidden', true);
                    $('.dadosEnd').attr('disabled', true)
                    alert(callback.msg);
                } else {
                    $('#salvar-endereco').removeAttr('disabled').html('Salvar');
                    alert(callback.msg);
                }
            },
            error: function() {
                $('#salvar-endereco').removeAttr('disabled').html('Salvar');
                alert('Ocorreu um erro, tente novamente!')
            }
        });
        return false;
    });

    $('#formCartao').submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        $('#btnsalvar-cartao').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('cadastros/atualizar_cartao') ?>',
            data: data,
            success: function(callback) {
                if (callback.status === 'OK') {
                    $('#btnsalvar-cartao').removeAttr('disabled').text('Salvar');
                    $("#adicionar-cartao").attr('disabled', true);
                    $('.editar-cartao').removeClass('hide');
                    $('#salvar-cartao').attr('hidden', true);
                    $('.dadosCartao').attr('disabled', true)
                    alert(callback.msg);
                } else {
                    $('#btnsalvar-cartao').removeAttr('disabled').html('Salvar');
                    alert(callback.msg);
                }
            },
            error: function() {
                $('#btnsalvar-cartao').removeAttr('disabled').html('Salvar');
                alert('Ocorreu um erro, tente novamente!')
            }
        });
        return false;
    });


    $('#formImpost').submit(function(e) {
        e.preventDefault();
        var data = new FormData($('#formImpost').get(0));
        $('#btnsalvar-imposto').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('cadastros/atualizar_impostos') ?>',
            data: data,
            processData: false,
            contentType: false,
            success: function(callback) {
                if (callback.status === 'OK') {
                    $('#btnsalvar-imposto').removeAttr('disabled').text('Salvar');
                    $('.editar-imposto').removeClass('hide');
                    $('#salvar-imposto').attr('hidden', true);
                    $('.dadosImposto').attr('disabled', true);

                    //ATUALIZA OS DADOS DOS IMPOSTOS NOS IMPUTS DO MODAL DE NOVA FATURA                    
                    $('.pis').val(callback.dados.PIS);
                    $('.csll').val(callback.dados.Cont_Social);
                    $('.irpj').val(callback.dados.IRPJ);
                    $('.cofins').val(callback.dados.COFINS);
                    $('.iss').val(callback.dados.ISS);

                    alert(callback.msg);
                } else {
                    $('#btnsalvar-imposto').removeAttr('disabled').html('Salvar');
                    alert(callback.msg);
                }
            },
            error: function() {
                $('#btnsalvar-imposto').removeAttr('disabled').html('Salvar');
                alert('Ocorreu um erro, tente novamente!')
            }
        });
        return false;
    });

    $('#formPermissao').submit(function(e) {
        e.preventDefault();

        let botao = $('#btnsalvar-permissao');

        const novo_produtos = $('#ids_produtos').val() || [];

        const data = {
            id_cliente: id_cliente,
            ids_produtos: novo_produtos,
            observacoes: $('#observacoes').val()
        }
        
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('cadastros/atualizar_produtos_cliente') ?>',
            data: data,
            beforeSend: function() {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
            },
            success: function(resposta) {
                if (resposta.status) {
                    userPerm.ajax.url('<?= site_url('/cadastros/ajax_permissoes_cliente') ?>/' + id_cliente).load()
                    botao.removeAttr('disabled').text('Salvar');
                    $('.editar-permissao').removeClass('hide');
                    $('#salvar-permissao').attr('hidden', true);
                    $('.adt').attr('disabled', true)
                    $('.adt').attr('readonly', true)

                    ids_produtos = novo_produtos;
                   
                    alert(resposta.mensagem);
                } else {
                    botao.removeAttr('disabled').html('Salvar');
                    alert(resposta.mensagem);
                }
            },
            error: function() {
                botao.removeAttr('disabled').html('Salvar');
                alert('Ocorreu um erro, tente novamente!')
            }
        });
        return false;
    });

    $('#intLinker').submit(function(e) {
        e.preventDefault()
        var data = $(this).serialize();
        $('#btnsalvar-linker').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('cadastros/integracao_linker') ?>',
            data: data,
            success: function(callback) {
                if (callback.status === true) {
                    $('#btnsalvar-linker').removeAttr('disabled').text('Salvar');
                    $('.editar-linker').removeClass('hide');
                    $('#salvar-linker').attr('hidden', true);
                    $('.dadosLinker').attr('disabled', true)
                    alert(callback.msg);
                } else {
                    $('#btnsalvar-linker').removeAttr('disabled').html('Salvar');
                    alert(callback.msg);
                }
            },
            error: function() {
                $('#btnsalvar-linker').removeAttr('disabled').html('Salvar');
                alert('Ocorreu um erro, tente novamente!')
            }
        });
        return false;
    });

    $('#segurancaForm').submit(function(e) {
        e.preventDefault()
        var data = $(this).serialize();
        $('#btnsalvar-seguranca').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('cadastros/forcar_atualizacao_senha') ?>',
            data: data,
            success: function(callback) {
                $('#btnsalvar-seguranca').removeAttr('disabled').html('Salvar');
                if (callback.status === true) {
                    $('#btnsalvar-seguranca').removeAttr('disabled').text('Salvar');
                    $('.editar-seguranca').removeClass('hide');
                    $('#salvar-seguranca').attr('hidden', true);
                    $('.dadosForcarTrocaSenha').attr('disabled', true)
                    alert(callback.msg);
                } else {
                    $('#btnsalvar-seguranca').removeAttr('disabled').html('Salvar');
                    alert(callback.msg);
                }
            },
            error: function() {
                $('#btnsalvar-seguranca').removeAttr('disabled').html('Salvar');
                alert('Ocorreu um erro, tente novamente!')
            }
        });
        return false;
    });

    $('#logotipoForm').submit(function(e) {
        e.preventDefault()
        let data = new FormData($('#logotipoForm')[0]);
        $('#btnsalvar-logotipo').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
        $.ajax({
            type: 'post',
            url: '<?= site_url('cadastros/atualizar_logotipo_cliente') ?>',
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(callback) {
                $('#btnsalvar-logotipo').removeAttr('disabled').html('Salvar');
                if (callback.status === 'Success') {
                    $('#btnsalvar-logotipo').removeAttr('disabled').text('Salvar');
                    $('.editar-logotipo').removeClass('hide');
                    $('#salvar-logotipo').attr('hidden', true);
                    $('.dadosLogotipo').attr('disabled', true);

                    $("#logotipo_cliente").attr("src", callback.logotipo);

                    alert(callback.message);
                } else {
                    $('#btnsalvar-logotipo').removeAttr('disabled').html('Salvar');
                    alert(callback.message);
                }
            },
            error: function() {
                $('#btnsalvar-logotipo').removeAttr('disabled').html('Salvar');
                alert('Ocorreu um erro, tente novamente!')
            }
        });
        return false;
    });

    $('#omniscoreForm').submit(function(e) {
        e.preventDefault();

        let data = $(this).serialize() + '&id_cliente=' + id_cliente;
        $('#btnsalvar-omniscore').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando);

        $.ajax({
            url: '<?= site_url('PerfisProfissionais/atualiaza_config_omniscore') ?>',
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function(callback) {                
                if (callback.success) {
                    $('#btnsalvar-omniscore').removeAttr('disabled').text(lang.salvar);
                    $('.editar-omniscore').removeClass('hide');
                    $('#salvar-omniscore').attr('hidden', true);
                    $('.dadosOmnisearch').attr('disabled', true);                    
                }

                alert(callback.msg);
            },
            error: function() {                
                alert(lang.erro_inesperado)
            },
            complete: function() {
                $('#btnsalvar-omniscore').removeAttr('disabled').html(lang.salvar);
            }
        });
    });

    $('#omniGrForm').submit(function(e) { 
        e.preventDefault();

        let data = $(this).serialize() + '&id_cliente=' + id_cliente;
        $('#btnsalvar-omniGr').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.salvando);

        $.ajax({
            url: '<?= site_url('cadastros/modificarAcessoOmniGr') ?>',
            data: data,
            type: 'POST',
            dataType: 'JSON',
            success: function(callback) {                
                if (callback.success) {
                    $('#btnsalvar-omniGr').removeAttr('disabled').text(lang.salvar);
                    $('.editar-omniGr').removeClass('hide');
                    $('#salvar-omniGr').attr('hidden', true);
                    $('.dadosOmniGr').attr('disabled', true);                    
                    alert('Alteração realizada com sucesso!');
                }
                else{
                    alert(ang.erro_inesperado)
                }

            },
            error: function() {                
                alert(lang.erro_inesperado)
            },
            complete: function() {
                $('#btnsalvar-omniGr').removeAttr('disabled').html(lang.salvar);
            }
        });
    });


    $('#formAddContrato').submit(function(e) {
        e.preventDefault()
        var data = $(this).serialize();
        $('#btnsalvar-contrato').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('contratos/cadastrar_contrato') ?>',
            data: data,
            success: function(callback) {
                if (callback.status === 'OK') {
                    loadTableContratos();
                    alert(callback.mensagem);
                    $('#btnsalvar-contrato').removeAttr('disabled').text('Salvar');
                    $('#modal_adicionar_contrato').modal('hide')
                } else {
                    $('#btnsalvar-contato').removeAttr('disabled').html('Salvar');
                    alert(callback.mensagem);
                }
            },
            error: function() {
                $('#btnsalvar-contrato').removeAttr('disabled').html('Salvar');
                alert('Ocorreu um erro, tente novamente!')
            }
        });
        return false;
    })

    $('#formAdicionarFatura').submit(function(e) {
        e.preventDefault()
        var valor = $('#valorTotal').val();
        var itens = $('.itens').hasClass('hide');

        if (valor == '' || valor === "0.00" || itens == true) {
            alert('Erro!\nFatura sem itens, adicione para gravar a fatura!')
            return false;
        }
        
        if ($('.addLista').css('display') !== 'none') {
            alert(lang.msg_erro_confirmar_itens)
            return false;
        }


        var data = $(this).serialize();
        console.log(data);
        $('#btnsalvar-fatura').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...');
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '<?= site_url('faturas/gerar_fatura_new') ?>',
            data: data,
            success: function(callback) {
                if (callback.status === true) {
                    loadTableDebitos();
                    loadTotaisTableDebitos(id_cliente);
                    alert(callback.msg);
                    $('#btnsalvar-fatura').removeAttr('disabled').text('Gravar fatura');
                    $('#nova_fatura').modal('hide')
                } else {
                    $('#btnsalvar-fatura').removeAttr('disabled').html('Gravar fatura');
                    alert(callback.msg);
                }
            },
            error: function() {
                $('#btnsalvar-fatura').removeAttr('disabled').html('Gravar fatura');
                alert('Ocorreu um erro, tente novamente!')
            }
        });
        return false;
    });

    $('#editar').on('click', function(e) {
        e.preventDefault();
        $('.editar').addClass('hide');
        $('#salvar').removeAttr('hidden');
        $('.dadosC').removeAttr('disabled');
    })

    $('#cancelar').on('click', function(e) {
        e.preventDefault();
        $('.editar').removeClass('hide');
        $('#salvar').attr('hidden', true);
        $('.dadosC').attr('disabled', true)
    })

    $('#editar-cartao').on('click', function(e) {
        e.preventDefault();
        $('.editar-cartao').addClass('hide');
        $('#adicionar-cartao').removeAttr('disabled');
        $('#salvar-cartao').removeAttr('hidden');
        $('.dadosCartao').removeAttr('disabled')
    })

    $('#cancelar-cartao').on('click', function(e) {
        e.preventDefault();
        $("#adicionar-cartao").attr('disabled', true);
        $('.editar-cartao').removeClass('hide');
        $('#salvar-cartao').attr('hidden', true);
        $('.dadosCartao').attr('disabled', true)
    })

    $('#editar-end').on('click', function(e) {
        e.preventDefault();
        $('.editar-end').addClass('hide');
        $('#adicionar-end').removeAttr('disabled');
        $('#salvar-end').removeAttr('hidden');
        $('.dadosEnd').removeAttr('disabled')
    })

    $('#cancelar-end').on('click', function(e) {
        e.preventDefault();
        $("#adicionar-end").attr('disabled', 'disabled');
        $('.editar-end').removeClass('hide');
        $('#salvar-end').attr('hidden', true);
        $('.dadosEnd').attr('disabled', true)
    })

    /**
     * EVENTO PARA LIBERAR CAMPOS DO CONTATO PARA EDICAO
    */
    $(document).on('click', '.editar_contato', function(e) {
        e.preventDefault();
        let botao = $(this);
        let index = botao.attr('data-index');
        let atributo = botao.attr('data-atributo');

        let idForm = '';
        if (atributo === 'email') idForm = 'formEmailContato' + index;
        else idForm = 'formTelefoneContato' + index;

        //Habilita os campos do contato
        $('#' + idForm + ' :input').attr('disabled', false);
        //Configura o botao para "Atualizacao"
        botao.removeClass('editar_contato')
        .addClass('salvar_contato')
        .attr('title', lang.salvar)
        .html('<i class="fa fa-save"></i>');
    });

    /**
     * Atualiza o email do cliente
    */
    $(document).on('click', '.salvar_contato', function(e) {
        e.preventDefault();
        let botao = $(this);
        let id = botao.attr('data-id');
        let index = botao.attr('data-index');
        let atributo = botao.attr('data-atributo');

        let idForm = '';
        let url = ''; 

        if (atributo === 'email') {
            idForm = 'formEmailContato' + index;
            
            if (typeof id === 'undefined') url = '<?= site_url('clientes/save_email') ?>';                
            else url = '<?= site_url('clientes/update_email') ?>/' + id;
        
        } 
        else if (atributo === 'telefone') {
            idForm = 'formTelefoneContato' + index;

            if (typeof id === 'undefined') url = '<?= site_url('clientes/save_telefone') ?>';                
            else url = '<?= site_url('clientes/update_telefone') ?>/' + id;
            
        }

        let data = $('#' + idForm).serialize() + '&cliente_id=' + id_cliente;

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: url,
            data: data,
            beforeSend: function () {
                botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(callback) {
                if (callback.success) {
                    //Desabilita os campos do contato
                    $('.imputContato').attr('disabled', true);

                    //configura o botao de salvar para editar
                    botao.removeClass('salvar_contato')
                    .addClass('editar_contato')                    
                    .attr('title', lang.editar)
                    .attr('disabled', false)
                    .html('<i class="fa fa-edit"></i>');

                    if (typeof callback.id_insert !== 'undefined') {
                        //Adiciona o id ao botao de edicao/salvar
                        botao.attr('data-id', callback.id_insert);
                        
                        //Adiciona o id ao botao de remocao
                        $('.remover_' + atributo + '_' + index).attr('data-id', callback.id_insert);
                    }

                    alert(callback.msg);

                } else {
                    botao.html('<i class="fa fa-save"></i>');
                    alert(callback.msg);
                }
            },
            error: function() {
                botao.html('<i class="fa fa-save"></i>');
                alert(lang.erro_inesperado);
            }
        });

    });

    /**
     * Remove/Inativa um email
    */
    $(document).on('click', '.remover_contato', function(e) {
        e.preventDefault();
        
        let botao = $(this);
        let id = botao.attr('data-id');
        let index = botao.attr('data-index');
        let atributo = botao.attr('data-atributo');
        let url = '';

        if (confirm(lang.confirmar_remocao_contato)) {

            if (typeof id === 'undefined') {
                $('#contato_' + atributo + '_' + index).remove();
            }
            else {
                if (atributo === 'email')
                    url = '<?= site_url('clientes/remover_email') ?>/' + id;
                else if (atributo === 'telefone') 
                    url = '<?= site_url('clientes/remover_telefone') ?>/' + id;
                
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: url,
                    beforeSend: function () {
                        botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(callback) {
                        if (callback.success) {                    
                            alert(callback.msg);
                            $('#contato_' + atributo + '_' + index).remove();

                        } else {
                            alert(callback.msg);
                            botao.attr('disabled', false).html('<i class="fa fa-trash-o fa-lg"></i>');
                        }
                    },
                    error: function() {
                        alert(lang.erro_inesperado)
                        botao.attr('disabled', false).html('<i class="fa fa-trash-o fa-lg"></i>');
                    }
                });
            }
        
        }

    });
    

    $('#editar-imposto').on('click', function(e) {
        e.preventDefault();
        $('.editar-imposto').addClass('hide');
        $('#salvar-imposto').removeAttr('hidden');
        $('.dadosImposto').removeAttr('disabled');
        // $('.upload').removeClass('hide')
    })

    $('#cancelar-imposto').on('click', function(e) {
        e.preventDefault();
        $('.editar-imposto').removeClass('hide');
        $('#salvar-imposto').attr('hidden', true);
        $('.dadosImposto').attr('disabled', true)
        // $('.upload').addClass('hide')
    })

    $('#editar-permissao').on('click', function(e) {
        e.preventDefault();
        $('.editar-permissao').addClass('hide');
        $('#salvar-permissao').removeAttr('hidden');
        $('.adt').removeAttr('disabled')
        $('.adt').attr('readonly', false)
    })

    $('#cancelar-permissao').on('click', function(e) {
        e.preventDefault();
        $('.editar-permissao').removeClass('hide');
        $('#salvar-permissao').attr('hidden', true);
        $('.adt').attr('disabled', 'disabled')
        $('.adt').attr('readonly', true)
    });

    $('#editar-linker').on('click', function(e) {
        e.preventDefault();
        $('.editar-linker').addClass('hide');
        $('#salvar-linker').removeAttr('hidden');
        $('.dadosLinker').removeAttr('disabled');
    })

    $('#cancelar-linker').on('click', function(e) {
        e.preventDefault();
        $('.editar-linker').removeClass('hide');
        $('#salvar-linker').attr('hidden', true);
        $('.dadosLinker').attr('disabled', true)
    })
    $('#editar-seguranca').on('click', function(e) {
        e.preventDefault();
        $('.editar-seguranca').addClass('hide');
        $('#salvar-seguranca').removeAttr('hidden');
        $('.dadosForcarTrocaSenha').removeAttr('disabled');
    })

    $('#cancelar-seguranca').on('click', function(e) {
        e.preventDefault();
        $('.editar-seguranca').removeClass('hide');
        $('#salvar-seguranca').attr('hidden', true);
        $('.dadosForcarTrocaSenha').attr('disabled', true)
    })

    $('#editar-logotipo').on('click', function(e) {
        e.preventDefault();
        $('.editar-logotipo').addClass('hide');
        $('#salvar-logotipo').removeAttr('hidden');
        $('.dadosLogotipo').removeAttr('disabled');
    })

    $('#cancelar-logotipo').on('click', function(e) {
        e.preventDefault();
        $('.editar-logotipo').removeClass('hide');
        $('#salvar-logotipo').attr('hidden', true);
        $('.dadosLogotipo').attr('disabled', true)
    })

    $('#editar-omniscore').on('click', function(e) {
        e.preventDefault();
        $('.editar-omniscore').addClass('hide');
        $('#salvar-omniscore').removeAttr('hidden');
        $('.dadosOmnisearch').removeAttr('disabled');
    })

    $('#cancelar-omniscore').on('click', function(e) {
        e.preventDefault();
        $('.editar-omniscore').removeClass('hide');
        $('#salvar-omniscore').attr('hidden', true);
        $('.dadosOmnisearch').attr('disabled', true);        
    })

    $('#editar-omniGr').on('click', function(e) {
        e.preventDefault();
        $('.editar-omniGr').addClass('hide');
        $('#salvar-omniGr').removeAttr('hidden');
        $('.acesso_omniGr').removeAttr('disabled');
    })

    $('#cancelar-omniGr').on('click', function(e) {
        e.preventDefault();
        $('.editar-omniGr').removeClass('hide');
        $('#salvar-omniGr').attr('hidden', true);
        $('.acesso_omniGr').attr('disabled', true);        
    })


    //GERAR CHAVE API
    $('#gerar-chaveapi').on('click', function(e) {
        e.preventDefault();
        $(this).text("Gerando Chave...").attr('disabled', true);
        var url = '<?= site_url('clientes/gerar_chave_api/') ?>/' + id_cliente;

        $.get(url, function(data) {
            $("#chave-api").text(data.chave);
            $(this).text("Gerar chave").removeAttr('disabled');

        });
    });

    //Vincular Central
    $('#formCentral').submit(function() {
        var data = $(this).serialize();
        var button = $('#btn_vincular_central');
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Salvando...')
        $.ajax({
            url: '<?= site_url('clientes/vincular_central_cliente') ?>',
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(callback) {
                if (callback.status === true) {
                    tableCentrais.ajax.reload(null, false);
                    alert(callback.msg)
                    button.removeAttr('disabled').html('Salvar');
                    $('#vincular_central').modal('hide');
                } else {
                    alert(callback.msg)
                    button.removeAttr('disabled').html('Salvar');
                }
            },
            error: function() {
                alert('Não foi possível vincular a central');
            }
        })
        return false;
    });

    //Inativar Cliente
    $('#inativarCliente').on('click', function() {
        $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Inativando...')
        $.post('<?= site_url('clientes/inativar_cliente') ?>/' + id_cliente, function(callback) {
            if (callback.status === true) {
                $('#inativarCliente').removeAttr('disabled').html('Inativar');
                $('#status').text('Inativo');
                alert(callback.msg);
            } else {
                $('#inativarCliente').removeAttr('disabled').html('Inativar');
                alert(callback.msg);
            }
        }, 'json')
    });

    //Bloqueio Parcial
    $('#bloquearCliente').on('click', function() {
        $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Bloqueando...')
        $.post('<?= site_url('clientes/bloqParcial') ?>/' + id_cliente, function(callback) {
            if (callback.status === true) {
                $('#bloquearCliente').removeAttr('disabled').html('Block Inad').addClass('hide');
                $('#status').text('Bloqueio Parcial');
                $('#desbloquearCliente').removeClass('hide')
                alert('Cliente bloqueado parcialmente');

            } else {
                $('#bloquearCliente').removeAttr('disabled').html('Block Inad');
                alert('Erro ao bloquear o cliente');
            }
        }, 'json')
    })

    //Desbloqueio parcial cliente
    $('#desbloquearCliente').on('click', function() {
        $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>Desbloqueando...')
        $.post('<?= site_url('clientes/desbloqueioParcial') ?>/' + id_cliente, function(callback) {
            if (callback.status === true) {
                $('#desbloquearCliente').removeAttr('disabled').html('Desbloqueio Inad.').addClass('hide');
                $('#status').text('Ativo');
                $('#bloquearCliente').removeClass('hide')
                alert('Cliente desbloqueado parcialmente');
            } else {
                $('#desbloquearCliente').removeAttr('disabled').html('Desbloqueio Inad.');
                alert('Erro ao desbloquear o cliente');
            }
        }, 'json')
    })

    /** Checked All */
    $(document).on('change', '.checked_all', function() {

        let checked = false;
        let form = $(this).data('form');

        if ($(this).prop('checked'))
            checked = true;

        $('form#' + form + ' input[type="checkbox"]').each(function(i, a) {
            $(a).prop('checked', checked);
        });
    });

    //confere o tipo de arquivo  -- negativar / positivar
    $('#arquivo_cliente').change(function() {
        //Reference the FileUpload element.
        var fileUpload = document.getElementById("arquivo_cliente");

        //Validate whether File is valid pdf file.
        var regex = /^(.)+(.pdf)$/;
        if (regex.test(fileUpload.value.toLowerCase())) {
            $('.negativar_positivar').prop('disabled', false);
        } else {
            alert("Por Favor, Use um Arquivo PDF Válido.");
            //limpa os dados do input file
            $("#arquivo_cliente").val(null);
            $('input[type="text"]').val('');
            $('.negativar_positivar').attr('disabled', true);
        }
    });

    //carrega dados modal
    $(document).on('click', '#negativar_positivar', function() {
        //limpas os valores dos campos do formulario
        $('textarea[name=descricao]').val(null);
        $("#arquivo_cliente").val(null);
        $('input[type="text"]').val('');
        $('.negativar_positivar').attr('disabled', true);

        var botao = $(this);
        if (botao.attr('data-acao') == 0) {
            $("#titulo_neg_posit").text('Negativar Cliente #' + id_cliente);
        } else {
            $("#titulo_neg_posit").text('Positivar Cliente #' + id_cliente);
        }

        // $("#id_cliente")[0].value = botao.attr('data-id_cliente');
        $("#input_acao")[0].value = botao.attr('data-acao');

        //carrega a tabela de arquivos digitalizados para clientes negativados/positivados
        tableNegativarPositivar = $('#digi_neg_posit').DataTable({
            ajax: {
                url: "<?= site_url('clientes/ajax_digi_neg_posit') ?>",
                type: 'POST',
                data: {
                    id: id_cliente
                }
            },
            order: [0, 'desc'],
            processing: true,
            destroy: true,
            pagingType: 'numbers',
            language: lang.datatable,
            dom: 'Bfrtip',
            responsive: true,
            info: false,
            columns: [{
                    data: 'id'
                },
                {
                    data: 'id_usuario'
                },
                {
                    data: 'data_cadastro'
                },
                {
                    data: 'descricao'
                },
                {
                    data: 'acao',
                    render: function(data) {
                        if (data == 0) {
                            return 'Negativação';
                        } else {
                            return 'Positivação';
                        }
                    }
                },
                {
                    data: 'link',
                    render: function(data) {
                        <?php if ($this->auth->is_allowed_block('clientes_arquivo')) : ?>
                            return '<a href="' + data + '" target="_blank" class="btn btn-success btn-mini"><i class="fa fa-eye"></i>Visualizar</a>';
                        <?php endif; ?>
                        return '<a href="' + data + '" target="_blank" class="btn btn-success btn-mini" disabled><i class="fa fa-eye"></i>Visualizar</a>';
                    }
                }
            ],

        });

        $('#modal_negativar_positivar').modal();
    });

    $('.pesq_serial').select2({
        ajax: {
            url: '<?= site_url('equipamentos/pesquisaEquipamentos') ?>',
            dataType: 'json',
            delay: 1000,
        },
        placeholder: "Selecione um serial",
        allowClear: true,
        minimumInputLength: 3,
    });

    $(document).on('click', '.deleteFile', function(e) {
        e.preventDefault();

        let botao = $(this);
        let id = $(this).data('id');

        botao.attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            url: '<?= site_url('arquivos/deletar') ?>',
            type: 'post',
            data: {
                arquivo_id: id
            },
            success: function(callback) {
                let response = JSON.parse(callback);
                if (response.status) {
                    alert('Arquivo deletado com sucesso.');
                } else {
                    alert('Erro ao deletar arquivo.');
                }
            },
            error: function(callback) {
                alert('Erro ao deletar arquivo.');
            },
            complete: function(callback) {
                tableArquivos.ajax.reload();
            }
        });
    });

    $('#formFile').submit(function(e) {
        e.preventDefault();

        var form = document.getElementById('formFile');
        var formData = new FormData(form);
        let botao = $('#btnSubmit', this);

        botao.attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Salvando');
        $.ajax({
            url: '<?= site_url('arquivos/salvar') ?>',
            type: 'post',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(callback) {
                if (callback.status) {
                    alert('Arquivo salvo com sucesso.');
                    limparModalArquivos();
                } else {
                    alert('Erro ao salvar arquivo.');
                }
            },
            error: function(callback) {
                alert('Erro ao salvar arquivo.');
            },
            complete: function(callback) {
                tableArquivos.ajax.reload();
                botao.html(lang.salvar).removeAttr('disabled');
            }
        });
    });

    $(document).ready(function() {
        var botao = false;
        $("#ContactForm2").ajaxForm({
            dataType: 'json',
            beforeSend: function() {
                $('.clientes-alert').css('display', 'block');
                botao = $('.negativar_positivar');
                botao.attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Processando...');
            },
            success: function(retorno) {
                if (retorno.success) {
                    if (retorno.acao == 0) {
                        alert('Cliente negativado com sucesso!');
                        $('#status').text('Negativo'); //muda o status
                        $(".positivar").removeClass('hide'); //atualiza o botao
                        $(".negativar").addClass('hide'); //atualiza o botao
                        $("#bloquearCliente").attr('disabled', true); //atualiza o botao

                    } else {
                        alert('Cliente positivado com sucesso!');
                        $('#status').text('Ativo');
                        $(".positivar").addClass('hide'); //atualiza o botao
                        $(".negativar").removeClass('hide'); //atualiza o botao
                        $("#bloquearCliente").removeAttr('disabled'); //atualiza o botao
                    }

                    //disabilita o botao de enviar o pedido
                    $('.negativar_positivar').prop('disabled', true);
                } else {
                    alert(retorno.msg);
                }
                botao.text('Processar');
                $("#modal_negativar_positivar").modal('hide');
            }
        });
    });

    function formataDataHora(data = new Date()) {
        var dia = ("0" + (parseInt(data.getDate()))).slice(-2);
        var mes = ("0" + (parseInt(data.getMonth()) + 1)).slice(-2);
        var ano = ("0" + (parseInt(data.getFullYear()))).slice(-2);
        var hora = ("0" + (parseInt(data.getHours()))).slice(-2);
        var min = ("0" + (parseInt(data.getMinutes()))).slice(-2);
        var seg = ("0" + (parseInt(data.getSeconds()))).slice(-2);

        var str_data = dia + '/' + mes + '/' + ano;
        var str_hora = hora + ':' + min + ':' + seg;
        return str_data + ' ' + str_hora;
    }

    function limparModalArquivos() {
        $('#novo_arquivo').modal('hide');
        $('#descricaoArquivo').val('');

        let input = $('#arquivoCliente');
        input.replaceWith(input.val('').clone(true));
    }

    $(document).on('focus', '.cpf', function() {
        $('.cpf').mask('999.999.999-99');
    });
    $(document).on('focus', '.cnpj', function() {
        $('.cnpj').mask('99.999.999/9999-99');
    });
    $(document).on('focus', '.ie', function() {
        $('.ie').mask('99.999.99-9');
    });
    $(document).on('focus', '.numero_cartao', function() {
        $('.numero_cartao').mask('9999-9999-9999-9999');
    });
    $(document).on('focus', '.codigo_cartao', function() {
        $('.codigo_cartao').mask('999');
    });
    $(document).on('focus', '.vencimento_cartao', function() {
        $('.vencimento_cartao').mask('99/99');
    });
    $(document).on('focus', '.data', function() {
        $('.data').mask('11/11/1111');
    });
    $(document).on('focus', '.tempo', function() {
        $('.tempo').mask('00:00:00');
    });
    $(document).on('focus', '.datatempo', function() {
        $('.datatempo').mask('99/99/9999 00:00:00');
    });
    $(document).on('focus', '.cep', function() {
        $('.cep').mask('99.999-999');
    });
    $(document).on('focus', '.ddd', function() {
        $('.ddd').mask('99');
    });
    var behavior = function(val) {
            return val.replace(/\D/g, '').length === 9 ? '00000-0000' : '0000-00009';
        },
        options = {
            onKeyPress: function(val, e, field, options) {
                field.mask(behavior.apply({}, arguments), options);
            }
        };
    $(document).on('focus', '.fone', function() {
        $('.fone').mask(behavior, options);
    });
    $(document).on('focus', '.ip', function() {
        $('.ip').mask('999.999.999.999');
    });
    $(document).on('focus', '.porta', function() {
        $('.porta').mask('9999');
    });
    $(document).on('focus', '.moeda', function(){ $(this).mask("#.##0,00", {reverse: true}) });
    $(document).on('focus', '.ref', function() { $('.ref').mask('00/0000') });

    
    $(document).on('click', '#input_logotipo', function (e) {      
        var uploadField = document.getElementById("input_logotipo");
        if (uploadField != null) {
            uploadField.onchange = function () {
                let extensoes_suportadas = ['image/jpeg', 'image/jpg', 'image/png'];

                if (uploadField.files[0].size > (1024 * 1024 * 2)) {  //limita o tamanho da imagem em 2m
                    alert(lang.limite_tam_arq_2m);
                    uploadField.value = "";
                    $('#logotipo_cliente').attr('src', logo_gestor);
                } 
                else if (extensoes_suportadas.indexOf(uploadField.files[0].type) === -1) {
                    alert(lang.msg_arquivo2);
                    uploadField.value = "";
                    $('#logotipo_cliente').attr('src', logo_gestor);
                }
                else {
                    $('#logotipo_cliente').attr('src', window.URL.createObjectURL(this.files[0]));
                }
            };

        }
    });

    $(document).on('keypress', 'input#pesqId', function(e) {
        var $this = $(this);
        var chave = (window.event)?event.keyCode:e.which;
        var tratarponto = $this.data('accept-dot');
        var tratarvirgula = $this.data('accept-comma');
        var aceitarPonto = (typeof tratarponto !== 'undefined' && (tratarponto == true || tratarponto == 1)?true:false);
        var aceitarVirgula = (typeof tratarvirgula !== 'undefined' && (tratarvirgula == true || tratarvirgula == 1)?true:false);
  
		if((chave > 47 && chave < 58) || (chave == 46 && aceitarPonto) || (chave == 44 && aceitarVirgula)) {
    	        return true;
  	        } else {
 			    return (chave == 8 || chave == 0)?true:false;
 		    }
    });

    //Ao clicar na aba de produtos, carrega os select2, aguardando 300ms para carregar
    $(document).on('click', '#produtos', function (e) {        
        $('#ids_produtos').val((ids_produtos && Array.isArray(ids_produtos)) ? ids_produtos : null);
        $('#ids_produtos').select2({
            width: '100%',
            placeholder: lang.selecione_o_produto
        });
    });
    // Constantes globais
    const BASE_URL_NODE = "<?=$this->config->item('base_url_relatorios')?>";

</script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'aba_contratos_new.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'placas_contrato.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'aba_debitos.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'aba_usuarios.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'aba_veiculos.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'aba_os.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'aba_equipamentos.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'aba_veiculos_espelhados.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'aba_centrais.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'aba_tickets.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'aba_arquivos.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'xlsx.full.min.js') ?>"></script>
<script type="text/javascript" src="<?= versionFile('assets/js', 'jszip.js') ?>"></script>