<style>
input:invalid {
    border-color: red !important;
}

input:valid {
    border-color: green !important;
}

#integracaoERPPJ-oportunidade input:valid {
    border-color: white !important;
}

#integracaoERPPJ-oportunidade input:invalid {
    border-color: red !important;
}
#integracaoERPPF-oportunidade input:valid {
    border-color: white !important;
}

#integracaoERPPF-oportunidade input:invalid {
    border-color: red !important;
}

#loading-screen.loading-screen-active {
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color: rgba(0, 0, 0, 0.5); /* transparência para mostrar que a tela está bloqueada */
}

.configurometroLoader {
        
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 2s linear infinite;
    }

    #loadingConfigurometro {
        display: block;
        z-index: 9999;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        background: rgba(0, 0, 0, 0.58);
        display: flex;
        justify-content: center;
        align-items: center;
    }



 #integracaoERPPJ input:valid {
    border-color: white !important;
}

#integracaoERPPJ input:invalid {
    border-color: red !important;
}
#integracaoERPPF input:valid {
    border-color: white !important;
}

#integracaoERPPF input:invalid {
    border-color: red !important;
}

#fileLabel {
        max-width: 550px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
}

.modal-xl {
    max-width: 1300px;
    width: 100%;
}

.sorting_disabled{
	width: fit-content !important;
}

    #tabelaRazaoValidacao th:nth-child(1),
    #tabelaRazaoValidacao td:nth-child(1) {
    width: 25%;
    }

    #tabelaRazaoValidacao th:nth-child(2),
    #tabelaRazaoValidacao td:nth-child(2) {
    width: 10%;
    }
    #tabelaRazaoValidacao th:nth-child(3),
    #tabelaRazaoValidacao td:nth-child(3) {
    width: 45%;
    }

    #tabelaRazaoValidacao th:nth-child(4),
    #tabelaRazaoValidacao td:nth-child(4) {
    width: 20%;
    }

    #dt_tableOportunidade th:nth-child(10),
    #dt_tableOportunidade td:nth-child(10) {
        min-width: 140px;
    }

    #dt_tableOportunidade_filter {
        width: 1000px !important;
    }

    .select2-selection__rendered {
        line-height: 30px !important;
    }

    .select2-container .select2-selection--single {
        height: 30px !important;
    }

    .select2-selection__arrow {
        height: 30px !important;
    }

    .select2-selection__clear {
        height: 30px !important;
    }
    .inputSoftware, .inputHardware {
        transition: box-shadow 1s ease-in-out;
        box-shadow: 0 0 0 #35f03b;
        background-color: #FFFFFF;
    }

    .highlight {
        box-shadow: 0 0 30px #35f03b;
        background-color: #d8f2d8;
    }
</style>


<?php 

$documento      = 0;
$emailUsuario   = " ";
    
    if (isset($_SESSION['documento'])) {
        $documento = $_SESSION['documento'];       
    }
    if (isset($_SESSION['emailUsuario'])) {
        $emailUsuario   = $_SESSION['emailUsuario'];
    }  
?>

<!-- alterações vindas da página de Clientes -->
<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<div class="modal fade" id="modalListagemDocumentos" tabindex="-1" role="dialog"
    aria-labelledby="modalListagemDocumentosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalListagemDocumentosLabel">Documentos do Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal formulario">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Arquivo:</label>
                        <div class="col-sm-8">
                            <label for="files" class="btn btn-default col-sm-12">Selecione um Arquivo</label>
                            <input id="files" type="file" class="btn btn-default" style="visibility:hidden;"
                                name="Arquivo" />
                        </div>
                        <div class="col-sm-1" style="padding-left: 0px !important">
                            <button type="button" class="btn btn-primary" title="Adicionar" id="AddArquivoCliente"><i
                                    class="fa fa-plus"></i>Adicionar</button>
                        </div>
                    </div>
                </form>
                <table class="table-responsive table-bordered table" id="tableListagemDocumentos">
                    <thead>
                        <tr>
                            <th>Data do envio</th>
                            <th>Documento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tableListagemDocumentosBody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalListagemDocumentos_oportunidades" tabindex="-1" role="dialog"
    aria-labelledby="modalListagemDocumentos_oportunidadesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalListagemDocumentos_oportunidadesLabel">Documentos do Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal formulario">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Arquivo:</label>
                        <div class="col-sm-8">
                            <label for="files_oportunidades" class="btn btn-default col-sm-12" id="fileLabel">Selecione um Arquivo</label>
                            <input id="files_oportunidades" type="file" class="btn btn-default" style="visibility:hidden;"
                                name="Arquivo" />
                            <input id="customerid_oportunidades" type="text" style="visibility:hidden;" name="customerid_oportunidades" />
                        </div>
                        <div class="col-sm-1" style="padding-left: 0px !important">
                            <button type="button" class="btn btn-primary" title="Adicionar" id="AddArquivoOportunidade"><i
                                    class="fa fa-plus"></i>Adicionar</button>
                        </div>
                    </div>
                </form>
                <table class="table-responsive table-bordered table" id="tableListagemDocumentos_oportunidades">
                    <thead>
                        <tr>
                            <th>Data do envio</th>
                            <th>Documento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tableListagemDocumentosBody_oportunidades">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<!-- fim das alterações vindas da página de clientes -->



<script>

 $("#closeModalERPPJ-oportunidade").click(function (e) {
    e.preventDefault();
    $('#modalDadosClienteERPPJ-oportunidade').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
 })



 $("#closeModalERPPF-oportunidade").click(function (e) {
    e.preventDefault();
    $('#modalDadosClienteERPPF-oportunidade').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
 })

</script>

<div class="row">
    <div class="col-md-6 feedback-alert">
        <?php echo $this->session->flashdata('sucesso');?>
        <?php echo $this->session->flashdata('error');?>
        <?php echo $this->session->flashdata('dados');?>
    </div>
</div>
<br>


<div class="row">
    <div class="col-md-12 mx-auto">
        <!-- chama a tabela conforme a busca -->
        <?php            
            $this->load->view('comercial_televenda/pedido/tabela_oportunidade');            
        ?>
    </div>
</div>

    



<!-- MODAL PARA CADASTRAR OPORTUNIDADE -->
 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Oportunidade</h5>
            </div>
            <div class="col-md-12" style="background-color: #FFFFFF;">
                <form action="<?php echo site_url('ComerciaisTelevendas/Pedidos/createOportunidade');?>" method="post"  id ='formProposta'>
                
                    <input type="hidden" class="form-control" name="idCotacao" placeholder="" id="idCotacao">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label >Documento do Cliente</label>
                                <input type="text" class="form-control input-sm" name="documentoCliente" placeholder=""
                                    value=<?php echo $documento; ?> id="documentoClienteOportunidade" required>
                                <span class="help help-block"></span>
                            </div>
                            <div id="divOrigem" class="col-sm-6">
                                <label >Origem</label>
                                <select name="origem" id="origem" class="form-control input-sm" required>
                                    <option value="">Escolha a origem da oportunidade</option>
                                    <option value="11">Fale Conosco</option>
                                    <option value="419400000">Chat</option>
                                    <option value="37">Cliente da Base</option>
                                    <option value="32">Indicação de Venda</option>
                                    <option value="16">Prospecção na Internet</option>
                                    <option value="100000000">Quem Indica Omnilink</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6">
                                <label >Quantidade de Veículos</label>
                                <input type="number" name="qtdVeiculos" id="qtdVeiculos" step="1" formControlName="qtdveiculosId"
                                    type="text" placeholder="Apenas números inteiros" class="form-control input-sm" value=""
                                    required="required" pattern="[0-9]+$">
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6">
                                <label >Plataforma</label>
                                <select class="form-control input-sm plataformaCad" id="plataformaCad" name="plataformaCad" required>
                                    <option value="" disabled selected>Selecione a plataforma</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6">
                                <label >Tipo de Pagamento</label>
                                <select name="tipo_pagamento" class="form-control input-sm tipo_pagamento" id="tipo_pagamento"
                                    required> </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6">
                                <label >Cenário da Venda</label>
                                <select class="form-control input-sm cenario_venda" id="cenario_venda" name="cenario_venda" required>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6">
                                <label >Condição do Pagamento</label>
                                <select name="condicao_pagamento" class="form-control input-sm condicao_pagamento"
                                    id="condicao_pagamento" required>
                                    <option value="">Selecione a Condição do Pagamento</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6">
                                <label>Modalidade de Venda</label>
                                <select name="modalidadeVenda" class="form-control input-sm" id="modalidadeVenda" required>
                                    <option value="1" selected>Veículo Novo</option>
                                    <option value="8">Serviços Adicionais</option>
                                    <option value="13">Reativação</option>
                                    <option value="9">OmniCarga</option>
                                    <option value="2">Tradein</option>
                                    <option value="3">Upgrade</option>
                                    <option value="6">Venda Avulsa de Acessórios</option>
                                    <option value="10">Venda de Serviço</option>
                                    <option value="7">Telemetria/Jornada/Imobilizador</option>
                                    <option value="5">Antena Avulsa</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6" id="col-clientRetiraArmazem">
                                <label>Cliente retira do armazém: </label>
                                <select name="clientRetiraArmazem" class="form-control input-sm" id="clientRetiraArmazem" required>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                            <div class="col-sm-6" id="col-armazem">
                                <label>Armazém: </label>
                                <select name="armazem" class="form-control input-sm" id="armazem" required>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6" id="col-responsavelRetirada">
                                <label>Responsável pela retirada do armazém: </label>
                                <input name="responsavelRetirada" class="data formatInput form-control input-sm" placeholder="Responsável pela retirada do armazém" autocomplete="off" id="responsavelRetirada" required/>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6" id="col-cpfResponsavelRetirada">
                                <label>CPF do responsável pela retirada do armazém: </label>
                                <input name="cpfResponsavelRetirada" class="data formatInput form-control input-sm" placeholder="Responsável pela retirada do armazém" autocomplete="off" id="cpfResponsavelRetirada" required/>
                                <span class="help help-block"></span>
                            </div>
                            <div id="divTempoContrato" class="col-sm-6">
                                <label >Tempo do Contrato (meses)</label>
                                <select name="tempoContrato" id="tempoContrato" class="form-control input-sm" required>
                                    <option value="">Escolha o Tempo do Contrato</option>
                                    <option value="12">12 meses</option>
                                    <option value="24">24 meses</option>
                                    <option value="36">36 meses</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6" id="divProduto">
                                <label >Produto</label>
                                <select class="form-control input-sm pesqID_Produto" id="ID_Produto" name="ID_Produto" type="text"
                                    required> </select>
                                <span class="help help-block"></span>
                            </div>
                            <div id="divTipoVeiculo" class="col-sm-6">
                                <label >Tipo de Veículo</label>
                                <select name="tipoVeiculo" class="form-control input-sm tipoVeiculo" id="tipoVeiculo" required>
                                    <option value="">Selecione o Tipo do Veículo</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-6" id="divPlanoSatelital">
                                <label >Plano Satelital</label>
                                <select name="planoSatelital" class="form-control input-sm planoSatelital" id="planoSatelital"> 
                                     <option value="">Selecione um Plano Satelital</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>  
                            <div class="col-sm-6" hidden>
                                <label >Editado por</label>
                                <input type="text" class="form-control input-sm" name="userNameVendedor" placeholder=""
                                    value=<?php echo $emailUsuario; ?> <?php 
                                        if (strlen($emailUsuario) != null) {
                                            echo $emailUsuario;
                                        }
                                        else {
                                            echo '""';
                                        }
                                    ?> readonly>
                                <span class="help help-block"></span>
                            </div>
                            <div id="divNomeCliente" class="col-sm-6">
                                <label>Nome Cliente</label>
                                <input type="text" class="form-control input-sm" name="nomeClienteOportunidade" id="nomeClienteOportunidade">
                                <span class="help help-block"></span>
                            </div>    
                    </div>        
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-dismiss="modal" title="Encerra o cadastro">Cancelar</button>
                        <button id="btnSalvar" class="btn btn-primary" type="submit"
                            title="Salva os dados preenchidos"> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<!-- MODAL PARA EDITAR OPORTUNIDADE -->
<div class="modal fade" id="editarOportunidadeModal" tabindex="-1" role="dialog" aria-labelledby="editarOportunidadeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 class="modal-title" id="editarOportunidadeModalLabel">Edição de Oportunidade</h5>
            </div>
            <div class="col-md-12" style="background-color: #FFFFFF;">
                <form action="<?php echo site_url('ComerciaisTelevendas/Pedidos/EditarOportunidade');?>" method="post"  id ='formEditarProposta'>
                
                    <input type="hidden" class="form-control" name="idCotacao" placeholder="" id="idCotacaoEditar">

                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="userNameVendedor" placeholder=""
                                value=<?php echo $emailUsuario; ?> <?php 
                                    if (strlen($emailUsuario) != null) {
                                        echo $emailUsuario;
                                    }
                                    else {
                                        echo '""';
                                    }
                                ?>
                            >
                            <div class="col-sm-4">
                                <label>Modalidade de Venda</label>
                                <select name="modalidadeVenda" class="form-control input-sm" id="modalidadeVendaEditar" required>
                                    <option value="1" selected>Veículo Novo</option>
                                    <option value="8">Serviços Adicionais</option>
                                    <option value="13">Reativação</option>
                                    <option value="9">OmniCarga</option>
                                    <option value="2">Tradein</option>
                                    <option value="3">Upgrade</option>
                                    <option value="6">Venda Avulsa de Acessórios</option>
                                    <option value="10">Venda de Serviço</option>
                                    <option value="7">Telemetria/Jornada/Imobilizador</option>
                                    <option value="5">Antena Avulsa</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div id="divOrigemEditar" class="col-sm-4">
                                <label >Origem</label>
                                <select name="origem" id="origemEditar" class="form-control input-sm" required>
                                    <option value="">Escolha a origem da oportunidade</option>
                                    <option value="11">Fale Conosco</option>
                                    <option value="419400000">Chat</option>
                                    <option value="37">Cliente da Base</option>
                                    <option value="32">Indicação de Venda</option>
                                    <option value="16">Prospecção na Internet</option>
                                    <option value="100000000">Quem Indica Omnilink</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-4">
                                <label >Quantidade de Veículos</label>
                                <input type="number" name="qtdVeiculos" id="qtdVeiculosEditar" step="1" formControlName="qtdveiculosId"
                                    type="text" placeholder="Apenas números inteiros" class="form-control input-sm" value=""
                                    required="required" pattern="[0-9]+$">
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-4" id="col-clientRetiraArmazemEditar">
                                <label>Cliente retira do armazém: </label>
                                <select name="clientRetiraArmazem" class="form-control input-sm" id="clientRetiraArmazemEditar" required>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-4" id="col-armazemEditar">
                                <label>Armazém: </label>
                                <select name="armazem" class="form-control input-sm" id="armazemEditar" required>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-4" id="col-responsavelRetiradaEditar">
                                <label>Responsável pela retirada: </label>
                                <input name="responsavelRetirada" class="data formatInput form-control input-sm" placeholder="Responsável pela retirada" autocomplete="off" id="responsavelRetiradaEditar" required/>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-4" id="col-cpfResponsavelRetiradaEditar">
                                <label>CPF do responsável pela retirada do armazém: </label>
                                <input name="cpfResponsavelRetirada" class="data formatInput form-control input-sm" placeholder="Responsável pela retirada do armazém" autocomplete="off" id="cpfResponsavelRetiradaEditar" required/>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-4">
                                <label >Tempo do Contrato (meses)</label>
                                <select name="tempoContrato" id="tempoContratoEditar" class="form-control input-sm" required>
                                    <option value="">Escolha o Tempo do Contrato</option>
                                    <option value="12">12 meses</option>
                                    <option value="24">24 meses</option>
                                    <option value="36">36 meses</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>   
                            <div class="col-sm-4" id="divProdutoEditar">
                                <label >Produto</label>
                                <select class="form-control input-sm pesqID_Produto" id="ID_ProdutoEditar" name="ID_Produto" type="text"
                                    required> </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-4">
                                <label >Cenário da Venda</label>
                                <select class="form-control input-sm cenario_venda" id="cenario_vendaEditar" name="cenario_venda" required>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-4" id="divPlanoSatelitalEditar">
                                <label >Plano Satelital</label>
                                <select name="planoSatelital" class="form-control input-sm planoSatelitalEditar" id="planoSatelitalEditar"> 
                                     <option value="">Selecione um Plano Satelital</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>     
                            <div class="col-sm-4">
                                <label >Tipo de Pagamento</label>
                                <select name="tipo_pagamento" class="form-control input-sm tipo_pagamento" id="tipo_pagamentoEditar"
                                    required> </select>
                                <span class="help help-block"></span>
                            </div>
                            <div class="col-sm-4">
                                <label >Condição do Pagamento</label>
                                <select name="condicao_pagamento" class="form-control input-sm condicao_pagamento"
                                    id="condicao_pagamentoEditar" required>
                                    <option value="">Selecione a Condição do Pagamento</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>
                            <div id="divTipoVeiculoEditar" class="col-sm-4">
                                <label >Tipo de Veículo</label>
                                <select name="tipoVeiculo" class="form-control input-sm tipoVeiculo" id="tipoVeiculoEditar" required>
                                    <option value="">Selecione o Tipo do Veículo</option>
                                </select>
                                <span class="help help-block"></span>
                            </div>           
                            <div class="col-sm-4 divVigencia" >
                                <label >Início da Vigência</label>                            
                                <input type="date" name="inicioVigencia" class="data formatInput form-control input-sm" placeholder="Início Vigência" autocomplete="off" id="inicioVigenciaEditar" value="" required/> 
                                <span class="help help-block"></span>
                            </div>     
                            <div class="col-sm-4 divVigencia">
                                <label >Término da Vigência</label>
                                <input type="date" name="terminoVigencia" class="data formatInput form-control input-sm" placeholder="TErmino Vigência" autocomplete="off" id="terminoVigenciaEditar" value="" required/> 
                                <span class="help help-block"></span>
                            </div>
                            <hr style="border-top: 1px solid #03A9F4; padding: 0px;" class="col-sm-12"/>

                            <div class="row">
                                <h4 class="col-sm-12">Signatário - SOFTWARE</h4>
                                <div class="col-sm-4 col-md-3">
                                    <button type="button" class="btn btn-primary" id="copiarDadosClienteSoftware" title="Preencher dados do signatário de software com dados do cliente"><i class="fa fa-copy"></i> Copiar Dados do Cliente</button>
                                    <span class="help help-block"></span>
                                </div>
                                <div class="col-sm-4 col-md-3">
                                    <button type="button" class="btn btn-primary" id="copiarDadosSignatarioSoftware" title="Preencher dados do signatário de software com dados do signatário"><i class="fa fa-copy"></i> Copiar Dados do Signatário</button>
                                    <span class="help help-block"></span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div id="divNomeClienteSoftware" class="col-sm-4">
                                    <label>Nome Signatário Software</label>
                                    <input type="text" class="form-control input-sm inputSoftware" name="nomeClienteSoftwareOportunidade" id="nomeClienteSoftwareOportunidade">
                                    <span class="help help-block"></span>
                                </div>
                                <div id="divEmailClienteSoftware" class="col-sm-4">
                                    <label>E-mail Signatário Software</label>
                                    <input type="email" class="form-control input-sm inputSoftware" name="emailClienteSoftwareOportunidade" id="emailClienteSoftwareOportunidade">
                                    <span class="help help-block"></span>
                                </div>
                                <div id="divDocumentoClienteSoftware" class="col-sm-4">
                                    <label>Documento Signatário Software</label>
                                    <input type="text" class="form-control input-sm inputSoftware" name="documentoClienteSoftwareOportunidade" id="documentoClienteSoftwareOportunidade">
                                    <span class="help help-block"></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <h4 class="col-sm-12">Signatário - HARDWARE</h4>
                                <div class="col-sm-4 col-md-3">
                                    <button type="button" class="btn btn-primary" id="copiarDadosClienteHardware" title="Preencher dados do signatário de hardware com dados do cliente"><i class="fa fa-copy"></i> Copiar Dados do Cliente</button>
                                    <span class="help help-block"></span>
                                </div>
                                <div class="col-sm-4 col-md-3">
                                    <button type="button" class="btn btn-primary" id="copiarDadosSignatarioHardware" title="Preencher dados do signatário de hardware com dados do signatário>Dados Signatário"><i class="fa fa-copy"></i> Copiar Dados do Signatário</button>
                                    <span class="help help-block"></span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div id="divNomeClienteHardware" class="col-sm-4">
                                    <label>Nome Signatário Hardware</label>
                                    <input type="text" class="form-control input-sm inputHardware" name="nomeClienteHardwareOportunidade" id="nomeClienteHardwareOportunidade">
                                    <span class="help help-block"></span>
                                </div>
                                <div id="divEmailClienteHardware" class="col-sm-4">
                                    <label>E-mail Signatário Hardware</label>
                                    <input type="email" class="form-control input-sm inputHardware" name="emailClienteHardwareOportunidade" id="emailClienteHardwareOportunidade">
                                    <span class="help help-block"></span>
                                </div>
                                <div id="divDocumentoClienteHardware" class="col-sm-4">
                                    <label>Documento Signatário Hardware</label>
                                    <input type="text" class="form-control input-sm inputHardware" name="documentoClienteHardwareOportunidade" id="documentoClienteHardwareOportunidade">
                                    <span class="help help-block"></span>
                                </div>
                            </div>
                        </div>    
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            data-dismiss="modal" title="Encerra o cadastro">Cancelar</button>
                        <button id="btnEditar" data-idCliente="" data-tipoCliente="" class="btn btn-primary" type="submit"
                            title="Salva os dados preenchidos"> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<!-- Modal para exibir histórico de status da AF -->
<div class="modal fade" id="modalHistoricoStatusAF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Histórico Status AF</h5>
                </br>
        <h5 class="modal-title" id="labelNumeroAFO"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table-responsive table-bordered table" id="tableHistoricoStatusAF" width="100%">
                    <thead>
                        <tr>
                            <th>Data do evento</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody id="tableHistoricoStatusAFBody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Itens do contrato de venda - Relacionar contratos -->
<div id="modalItensContratoVenda" class="modal fade" role="dialog" >
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="min-width: fit-content;">
            <form name="formItensContratoVenda">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("itens_contrato_venda")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div id="div_identificacao">
                        <button type="button" class="btn btn-primary" data-id='' data-idCotacao='' data-idTipoVeiculo="" id="btnAdicionarItemContratoVenda" title="Adicionar Item"><i class="fa fa-plus"></i> Adicionar Item</button>
                        <table class="table-responsive table-bordered table" id="tabelaItensContratoVenda" style="width: 100%">
                	    	<thead>
                                <tr class="tableheader">
                		        <th>Nome</th>
                                <th>Cliente</th>
                                <th>Veículo</th>
                                <th>Número de Série</th>
                                <th>Plano</th>
                                <th>Rastreador</th>
                                <th>Status</th>
                                <th>Data de Criação</th>
                                <th>Ações</th>
                		        </tr>
                	    	</thead>
                	    	<tbody>
                	    	</tbody>
                	    </table> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Relacionar contratos -->
<div id="modalContratosRelacionar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="min-width: fit-content;">
            <form name="formContratosRelacionar">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("adicionar_item_contrato")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div id="div_identificacao">
                        <table class="table-responsive table-bordered table" id="tabelaContratosRelacionar" style="width: 100%">
                	    	<thead>
                                <tr class="tableheader">
                                <th id="selecionarTodos">
                                    <input type="checkbox" id="checkTodos" name="checkTodos">
                                </th>
                                <th>Nome</th>
                                <th>Cliente</th>
                                <th>Veículo</th>
                                <th>Número de Série</th>
                                <th>Plano</th>
                                <th>Rastreador</th>
                                <th>Status</th>
                                <th>Data de Criação</th>
                		        </tr>
                	    	</thead>
                	    	<tbody>
                	    	</tbody>
                	    </table> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalDetalhamentoFrota" class="modal fade" role="dialog" >
    <div class="modal-dialog modal-xl" role="document" style="min-width: fit-content;">
        <div class="modal-content">
            <form name="formDetalhamentoFrota">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Detalhamento da Frota</h3>
                </div>
                <div class="modal-body scrollModal">
                    <div id="div_identificacao">
                        <button type="button" data-idComposicao = '' class="btn btn-primary" id="btnAbrirAdicionarDetalhamentoFrota" style="margin-bottom: 10px;"><i class="fa fa-plus"></i> Adicionar Detalhamento</button>
                        <table class="table-responsive table-bordered table" id="tabelaDetalhamentoFrota" style="width: 100%">
                	    	<thead>
                                <tr class="tableheader">
                                <th>Nome</th>
                		        <th>Número de Série do Rastreador</th>
                                <th>Número de Série da Antena Satelital</th>
                                <th>Quantidade</th>
                                <th class="acoes">Ações</th>
                		        </tr>
                	    	</thead>
                	    	<tbody>
                	    	</tbody>
                	    </table> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalAddDetalhamentoFrota" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            	<form id="formAddDetalhamentoFrota">
                	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 class="modal-title">Adicionar Detalhamento Frota</h3>
                	</div>
                	<div class="modal-body scrollModal">
						<div class="col-md-12">
                            <div class="row" style="margin-bottom: 6px;">
                                <div class="col-md-6 form-group bord">
                                    <label>Número de Série do Rastreador:</label>
                                    <input class="form-control input-sm" id="numeroSerieRastreador" type="text" placeholder="Digite o número de série do rastreador" required>
                                </div>
                                <div class="col-md-6 form-group bord">
                                    <label>Número de Série da Antena Satelital:</label>
                                    <input class="form-control input-sm" id="numeroSerieAntenaSatelital" type="text" placeholder="Digite o número de série da antena satelital" required>
                                </div>
                                <div class="col-md-6 form-group bord" hidden>
                                    <label>Vendedor:</label>
                                    <input type="text" class="form-control input-sm" id="emailVendedorAddDetalhamento"
                                        value=<?php echo $emailUsuario; ?> <?php 
                                            if (strlen($emailUsuario) != null) {
                                                echo $emailUsuario;
                                            }
                                            else {
                                                echo '""';
                                            }
                                        ?> readonly>
                                </div> 
                            </div>
						</div>
					</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>

                    <button class="btn btn-primary" type="submit" id="btnSalvarAddDetalhamentoFrota" style="margin-right: 15px;" >Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para listar razões de validação -->
<div id="modalRazaoValidacoes" class="modal fade" role="dialog" >
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="min-width: fit-content;">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("razao_validacao")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div id="div_identificacao">
                        <table class="table-responsive table-bordered table" id="tabelaRazaoValidacao" style="width: 100%">
                	    	<thead>
                                <tr class="tableheader">
                		        <th>Nome</th>
                                <th>Situação</th>
                                <th>Descrição</th>
                                <th>Observação</th>
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
<div id="modalPerderOportunidade" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="min-width: fit-content;">
            <form id="formPerderOportunidade">
                <div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?=lang("perder_oportunidade")?></h3>
                </div>
                <div class="modal-body scrollModal">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">Motivo da Perda:</label>
                            <select class="form-control input-sm" id="motivoPerda" name="motivoPerda" required>
                                <option value="" disabled selected>Selecione o motivo da perda</option>
                                <option value="419400000">Preço</option>
                                <option value="419400001">Opção pelo concorrente</option>
                                <option value="419400002">Sem orçamento</option>
                                <option value="419400003">Desistência</option>
                                <option value="419400004">Preço/Forma de pagto.</option>
                                <option value="419400005">Produto</option>
                                <option value="419400006">Entrega</option>
                                <option value="419400007">Outros</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">Concorrente:</label>
                            <select class="form-control input-sm" id="competidores" name="competidores" required>
                                <option value="" disabled selected>Selecione o concorrente</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Fechar</button>
                    <button class="btn btn-primary" data-idCotacao = "" type="submit" id="btnSalvarPerderOportunidade">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- inicio de clientes -->


    
<!-- SELECT2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


<script type="text/javascript">
jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-br-pre": function(a) {
        var brDatea = a.split('/');
        return (brDatea[2] + brDatea[1] + brDatea[0]) * 1;
    },
    "date-br-asc": function(a, b) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
    "date-br-desc": function(a, b) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});



$(document).ready(function() {
    $('#dt_table').DataTable({
        "ordering": true,
        "order": [
            [1, "desc"]
        ],
        "columnDefs": [{
            "type": "date-br",
            targets: [1]
        }],
        "language": {
            "searchPlaceholder": "Pesquisar na tabela",
            "decimal": "",
            "emptyTable": "Nenhum registro encontrado",
            "info": "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "0 Registros",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing": "Processando...",
            "search": "Pesquisar: ",
            "zeroRecords": "Nenhum registro encontrado",
            "paginate": {
                "first": "Anterior",
                "last": "Próxima",
                "next": "Próxima",
                "previous": "Anterior"
            }
        },
        bLengthChange: true
    });

});
</script>



<script type="text/javascript">

var options = {
    onKeyPress: function(cpf, ev, el, op) {
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        $(".documento").mask((cpf.length > 14) ? masks[1] : masks[0], op);
    }
}

$(".documento").length > 11 ? $(".documento").mask('00.000.000/0000-00', options) : $(".documento").mask(
    '000.000.000-00#', options);

var optionsHardware = {
    onKeyPress: function(cpf, ev, el, op) {
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        $("#documentoClienteHardwareOportunidade").mask((cpf.length > 14) ? masks[1] : masks[0], op);
    },
    onChange: function(cpf, ev, el, op) {
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        $("#documentoClienteHardwareOportunidade").mask((cpf.length > 14) ? masks[1] : masks[0], op);
    }
}

$("#documentoClienteHardwareOportunidade").length > 11 ? $("#documentoClienteHardwareOportunidade").mask('00.000.000/0000-00', options) : $("#documentoClienteHardwareOportunidade").mask(
    '000.000.000-00#', optionsHardware);

var optionsSoftware = {
    onKeyPress: function(cpf, ev, el, op) {
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        $("#documentoClienteSoftwareOportunidade").mask((cpf.length > 14) ? masks[1] : masks[0], op);
    },
    onChange: function(cpf, ev, el, op) {
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        $("#documentoClienteSoftwareOportunidade").mask((cpf.length > 14) ? masks[1] : masks[0], op);
    }
}

$("#documentoClienteSoftwareOportunidade").length > 11 ? $("#documentoClienteSoftwareOportunidade").mask('00.000.000/0000-00', options) : $("#documentoClienteSoftwareOportunidade").mask(
    '000.000.000-00#', optionsSoftware);
</script>


<script type="text/javascript">
// Quando o processamento do formulário estiver concluído, oculta a tela de carregamento
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('loading').style.display = 'none';
    });
});

async function getOportunidadesCliente(){
    let oportunidades = await $.ajax({
        url: '<?= site_url('ComerciaisTelevendas/Pedidos/getOportunidadeCliente') ?>' ,
        type: "POST",
        success: function(data) {
            return data;
        },
        error: function() {
            return '';
        }
    })   
    return oportunidades;
} 

async function getOportunidadesVendedor(){
    let oportunidades = await $.ajax({
        url: '<?= site_url('ComerciaisTelevendas/Pedidos/getOportunidadeVendedor') ?>' ,
        type: "POST",
        success: function(data) {
            return data;
        },
        error: function() {
            return '';
        }
    })

    return oportunidades;
}


var documento = '';
var cotacaoPedido = '';

async function integrarClientePeloPedido(data, cotacao){    
    documento = data;
    cotacaoPedido = cotacao;
    document.getElementById('loading').style.display = 'block';
    await $.ajax({
        url: `<?= site_url('ComerciaisTelevendas/Pedidos/integrarClientePeloPedido') ?>`,
        type: 'POST',
        data: {
            "documento" : data,
            "cotacao": cotacao
        },
        success: function (response){              
            response = JSON.parse(response) 
            if(response?.status != 200){  
                $.confirm({
                    title: "Não foi possível integrar o cliente",
                    content: "Os dados desse cliente estão incompletos, preencha as informações para completar a Integração com o ERP e Gerar o Pedido.",
                    buttons: {
                        atualizar: {
                            text: "Atualizar",
                            btnClass: "btn-blue",
                            action: function() {                                
                                $("#getResumoClienteERP").val(data);
                                $("#getResumoClienteERP").click();
                               
                            }
                        },
                        cancelar: {
                            text: "Cancelar",
                            btnClass: "btn-blue",
                            action: function() {
                                
                            }
                        }
                    }
                })
            } else {
                alert(response?.Message)
                BuscarOportunidadesVendedor();
            }
            
        },
        complete: function(){
            document.getElementById('loading').style.display = 'none';
        }
    }) 
    
}

var ICONS = {
    spinner: '<i class="fa fa-spinner fa-spin"></i>',
    success: '<i class="fa fa-check-circle"></i>',
    error: '<i class="fa fa-times-circle"></i>'

};




$("#rg-erp-pf").mask("0.000.000", {reverse: true});
$("#ie-erp-pj").mask("0000000-00", {reverse: true});
$("#cep-principal-erp-pf").mask("00000-000", {reverse: true});
$("#cep-cobranca-erp-pf").mask("00000-000", {reverse: true});
$("#cep-entrega-erp-pf").mask("00000-000", {reverse: true});
$("#cep-principal-erp-pj").mask("00000-000", {reverse: true});
$("#cep-cobranca-erp-pj").mask("00000-000", {reverse: true});
$("#cep-entrega-erp-pj").mask("00000-000", {reverse: true});



</script>

<script type="text/javascript">

    /* Verifica bandeira do cartão de crédito */

    $("#cep-principal-erp-pf").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-principal-erp-pf").val(dadosRetorno.logradouro);
				$("#bairro-principal-erp-pf").val(dadosRetorno.bairro);
				$("#cidade-principal-erp-pf").val(dadosRetorno.localidade);
				$("#estado-principal-erp-pf").val(dadosRetorno.uf);
                $("#complemento-principal-erp-pf").val('')
			}catch(ex){}
		});
    })

    $("#cep-principal-erp-pj").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-principal-erp-pj").val(dadosRetorno.logradouro);
				$("#bairro-principal-erp-pj").val(dadosRetorno.bairro);
				$("#cidade-principal-erp-pj").val(dadosRetorno.localidade);
				$("#estado-principal-erp-pj").val(dadosRetorno.uf);
                $("#complemento-principal-erp-pj").val('')
			}catch(ex){}
		});
    })

    $('#tab-cartaoCreditoCliente').click(function(e){
        e.preventDefault();
        $('#numeroCartaoCliente').val('');
        $('#codigoCartaoCliente').val('');
        $('#nomeCartaoCliente').val('');
        $('#mesValidadeCartaoCliente').val('');
        $('#anoValidadeCartaoCliente').val('');
        $('#bandeiraCartaoCliente').val(0);
        document.getElementById("numeroCartaoCliente").style.backgroundImage = "url('')";
        $('#codigoCartaoCliente').prop('disabled', true);
        $('#nomeCartaoCliente').prop('disabled', true);
        $('#mesValidadeCartaoCliente').prop('disabled', true);
        $('#anoValidadeCartaoCliente').prop('disabled', true);
        $('#selectBandeiraCliente').hide();
    });


$('#telefone-erp-pf').mask('00000-0000');
$('#telefone-erp-pf2').mask('00000-0000');
$('#telefone-erp-pj').mask('00000-0000');
$('#telefone-erp-pj2').mask('00000-0000');
$('#ddd-erp-pf').mask('00');
$('#ddd-erp-pf2').mask('00');
$('#ddd-erp-pj').mask('00');
$('#ddd-erp-pj2').mask('00');

</script>

<!-- Traduções -->        
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>




<!-- FIM DE CLIENTESSS -->


<script>
    $('#formProposta').submit(function(event) {
        $('#loading').show();
    });
    
    $('#formEditarProposta').submit(function(event) {
        $('#loading').show();
        let emailClienteSoftware = $("#emailClienteSoftwareOportunidade").val();
        let emailClienteHardware = $("#emailClienteHardwareOportunidade").val();
        let documentoClienteSoftware = $("#documentoClienteSoftwareOportunidade").val();
        let documentoClienteHardware = $("#documentoClienteHardwareOportunidade").val();

        if (emailClienteSoftware){
            if (!validateEmail(emailClienteSoftware)) {
                alert("E-mail Signatário Software Inválido!");
                 $('#loading').hide();
                return false;
            }
        }

        if (emailClienteHardware){
            if (!validateEmail(emailClienteHardware)) {
                alert("E-mail Signatário Hardware Inválido!");
                 $('#loading').hide();
                return false;
            }
        }

        if (documentoClienteSoftware){
            if (!validateCnpjCpf(documentoClienteSoftware)) {
                alert("Documento Signatário Software Inválido!");
                 $('#loading').hide();
                return false;
            }
        }

        if (documentoClienteHardware){
            if (!validateCnpjCpf(documentoClienteHardware)) {
                alert("Documento Signatário Hardware Inválido!");
                 $('#loading').hide();
                return false;
            }
        }
        

        function validateCnpjCpf(cnpjCpf) {
            // Remove caracteres não numéricos
            const cleanCnpjCpf = cnpjCpf.replace(/\D/g, '');

            // Verifica se é CNPJ (14 caracteres) ou CPF (11 caracteres)
            if (cleanCnpjCpf.length === 14) {
                // Validação de CNPJ
                const weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
                const sum = weights.reduce((acc, weight, index) => acc + parseInt(cleanCnpjCpf[index]) * weight, 0);
                const remainder = sum % 11;

                const firstDigit = remainder < 2 ? 0 : 11 - remainder;

                if (parseInt(cleanCnpjCpf[12]) !== firstDigit) {
                return false;
                }

                weights.unshift(6); // Adiciona peso 6 para o segundo dígito verificador
                const sumSecond = weights.reduce((acc, weight, index) => acc + parseInt(cleanCnpjCpf[index]) * weight, 0);
                const remainderSecond = sumSecond % 11;

                const secondDigit = remainderSecond < 2 ? 0 : 11 - remainderSecond;

                return parseInt(cleanCnpjCpf[13]) === secondDigit;
            } else if (cleanCnpjCpf.length === 11) {
                // Validação de CPF
                const cpfArray = cleanCnpjCpf.split('').map(Number);
                const sum = cpfArray.slice(0, 9).reduce((acc, value, index) => acc + value * (10 - index), 0);
                const remainder = sum % 11;

                const firstDigit = remainder < 2 ? 0 : 11 - remainder;

                if (cpfArray[9] !== firstDigit) {
                return false;
                }

                const sumSecond = cpfArray.slice(0, 10).reduce((acc, value, index) => acc + value * (11 - index), 0);
                const remainderSecond = sumSecond % 11;

                const secondDigit = remainderSecond < 2 ? 0 : 11 - remainderSecond;

                return cpfArray[10] === secondDigit;
            } else {
                // Tamanho inválido
                return false;
            }
        }
    });
</script>

<script>

     $("#submit-form-integracao-pj-oportunidade").click(async function(e) {
        e.preventDefault();
        let data = getDadosForm('integracaoERPPJ-oportunidade');

        let botao = $("#submit-form-integracao-pj-oportunidade");
        let htmlBotao = botao.html();
        botao.html(ICONS.spinner+' '+htmlBotao);
        botao.attr('disabled', true);
        
        let retorno = await $.ajax({
            url: `<?= site_url('ComerciaisTelevendas/Pedidos/integrarClienteERP') ?>`,
            type: 'POST',
            data: data,
            success: function (response){   
                documentoERP = '';
                $("#getResumoClienteERP").click()
                
                response = JSON.parse(response)
                alert(response?.Message)
                return response?.Status
            }
        })

        retorno = JSON.parse(retorno)
        retorno =retorno?.Status
        
        if(retorno == 200){
            await integrarClientePeloPedido(documento, cotacaoPedido)
        }

        botao.html(htmlBotao);
        botao.attr('disabled', false);       
   
        
    })


    $("#submit-form-integracao-pf-oportunidade").click(async function(e) {
        e.preventDefault();

        let botao = $("#submit-form-integracao-pf-oportunidade");
        let htmlBotao = botao.html();
        botao.html(ICONS.spinner+' '+htmlBotao);
        botao.attr('disabled', true);

        
        let data = getDadosForm('formIntegracaoERPPF-oportunidade');

        let retorno = await $.ajax({
            url: `<?= site_url('ComerciaisTelevendas/Pedidos/integrarClienteERP') ?>`,
            type: 'POST',
            data: data,
            success: function (response){   
                documentoERP = '';
                $("#getResumoClienteERP").click()
                
                response = JSON.parse(response)
                alert(response?.Message)
                return response?.Status
            }

        }) 
             
        retorno = JSON.parse(retorno)
        retorno =retorno?.Status

        if(retorno == 200){
            await integrarClientePeloPedido(documento, cotacaoPedido)
        }
        botao.html(htmlBotao);
        botao.attr('disabled', false);

       
    })
</script>

<script type="text/javascript">

    $("#cep-principal-erp-pf-oportunidade").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-principal-erp-pf-oportunidade").val(dadosRetorno.logradouro);
				$("#bairro-principal-erp-pf-oportunidade").val(dadosRetorno.bairro);
				$("#cidade-principal-erp-pf-oportunidade").val(dadosRetorno.localidade);
				$("#estado-principal-erp-pf-oportunidade").val(dadosRetorno.uf);
                $("#complemento-principal-erp-pf-oportunidade").val('')
			}catch(ex){}
		});
    })

    $("#cep-principal-erp-pj-oportunidade").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-principal-erp-pj-oportunidade").val(dadosRetorno.logradouro);
				$("#bairro-principal-erp-pj-oportunidade").val(dadosRetorno.bairro);
				$("#cidade-principal-erp-pj-oportunidade").val(dadosRetorno.localidade);
				$("#estado-principal-erp-pj-oportunidade").val(dadosRetorno.uf);
                $("#complemento-principal-erp-pj-oportunidade").val('')
			}catch(ex){}
		});
    })

    $("#cep-cobranca-erp-pf-oportunidade").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-cobranca-erp-pf-oportunidade").val(dadosRetorno.logradouro);
				$("#bairro-cobranca-erp-pf-oportunidade").val(dadosRetorno.bairro);
				$("#cidade-cobranca-erp-pf-oportunidade").val(dadosRetorno.localidade);
				$("#estado-cobranca-erp-pf-oportunidade").val(dadosRetorno.uf);
                $("#complemento-cobranca-erp-pf-oportunidade").val('')
			}catch(ex){}
		});
    })

    $("#cep-cobranca-erp-pj-oportunidade").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-cobranca-erp-pj-oportunidade").val(dadosRetorno.logradouro);
				$("#bairro-cobranca-erp-pj-oportunidade").val(dadosRetorno.bairro);
				$("#cidade-cobranca-erp-pj-oportunidade").val(dadosRetorno.localidade);
				$("#estado-cobranca-erp-pj-oportunidade").val(dadosRetorno.uf);
                $("#complemento-cobranca-erp-pj-oportunidade").val('')
			}catch(ex){}
		});
    })

    $("#cep-entrega-erp-pf-oportunidade").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-entrega-erp-pf-oportunidade").val(dadosRetorno.logradouro);
				$("#bairro-entrega-erp-pf-oportunidade").val(dadosRetorno.bairro);
				$("#cidade-entrega-erp-pf-oportunidade").val(dadosRetorno.localidade);
				$("#estado-entrega-erp-pf-oportunidade").val(dadosRetorno.uf);
                $("#complemento-entrega-erp-pf-oportunidade").val('')
			}catch(ex){}
		});
    })

    $("#cep-entrega-erp-pj-oportunidade").change(function () {
        var cep = this.value.replace(/[^0-9]/, "");

        var url = "https://viacep.com.br/ws/"+cep+"/json/";

        $.getJSON(url, function(dadosRetorno){
			try{
				$("#rua-entrega-erp-pj-oportunidade").val(dadosRetorno.logradouro);
				$("#bairro-entrega-erp-pj-oportunidade").val(dadosRetorno.bairro);
				$("#cidade-entrega-erp-pj-oportunidade").val(dadosRetorno.localidade);
				$("#estado-entrega-erp-pj-oportunidade").val(dadosRetorno.uf);
                $("#complemento-entrega-erp-pj-oportunidade").val('')
			}catch(ex){}
		});
    })




let selecttipo_pagamento = document.getElementById('tipo_pagamento');

$('#tipo_pagamento').change(function() {
    $('#condicao_pagamento').select2({
        width: '100%',
        placeholder: "Selecione a Condição do Pagamento",
        allowClear: true
    })
    let selectcondicao_pagamento = document.getElementById('condicao_pagamento');
    
    var valor = $('#tipo_pagamento').find(':selected').attr('tz_codigo_erp');

    var site_url = "<?= site_url() ?>";
    fetch(site_url + '/ComerciaisTelevendas/Pedidos/pegar_condicaoPagamentoCRM?value=' + valor)

        .then(response => {
            return response.text();
        })
        .then(texto => {
            selectcondicao_pagamento.innerHTML = texto;
            if(condicao_pagamento){
                $("#condicao_pagamento option").filter(function() {
                    return $(this).text().includes(condicao_pagamento);
                }).prop('selected', true).trigger('change');
            }
        });
});

let selecttipo_pagamentoEditar = document.getElementById('tipo_pagamentoEditar');

$('#tipo_pagamentoEditar').change(function() {
    $('#condicao_pagamentoEditar').select2({
        width: '100%',
        placeholder: "Selecione a Condição do Pagamento",
        allowClear: true
    })
    let selectcondicao_pagamentoEditar = document.getElementById('condicao_pagamentoEditar');
    
    var valor = $('#tipo_pagamentoEditar').find(':selected').attr('tz_codigo_erp');

    var site_url = "<?= site_url() ?>";
    fetch(site_url + '/ComerciaisTelevendas/Pedidos/pegar_condicaoPagamentoCRM?value=' + valor)

        .then(response => {
            return response.text();
        })
        .then(texto => {
            selectcondicao_pagamentoEditar.innerHTML = texto;
            if(condicao_pagamento){
                $("#condicao_pagamentoEditar option").filter(function() {
                    return $(this).text().includes(condicao_pagamento);
                }).prop('selected', true).trigger('change');
            }
        });
});
</script>

<script type="text/javascript">
    let dt_tableOportunidade;
$(document).ready(function() {
    $('input[type=file]').change(function(){
        var t = $(this).val();
        var labelText = 'Arquivo : ' + t.substr(12, t.length);
        $(this).prev('label').text(labelText);
    })

        dt_tableOportunidade = $('#dt_tableOportunidade').DataTable({
        "ordering": true,
        "order": [
            [2, "desc"]
        ],
        "columnDefs": [{
            "type": "date-br",
            targets: [2],
            
        }],
        "language": {
            "searchPlaceholder": "Pesquisar na tabela",
            "decimal": "",
            "emptyTable": "Nenhum registro encontrado",
            "info": "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "0 Registros",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing": "Processando...",
            "search": "Pesquisar: ",
            "zeroRecords": "Nenhum registro encontrado",
            "paginate": {
                "first": "Anterior",
                "last": "Próxima",
                "next": "Próxima",
                "previous": "Anterior"
            }
        }
    });
});
</script>


<script type="text/javascript">
var ICONS = {
    spinner: '<i class="fa fa-spinner fa-spin"></i>',
    success: '<i class="fa fa-check-circle"></i>',
    error: '<i class="fa fa-times-circle"></i>'

};


$(document).on('click', '.enviar_docsign', function(e) {
    e.preventDefault();
    $('.feedback-alert').html('');
    var botao = $(this);
    var quoteid = botao.attr('data-docsign');
    var htmlBotao = botao.html();
    botao.html(ICONS.spinner); // adiciona o ícone de spinner ao botão
    botao.attr('disabled', true);
    showLoadingScreen();
    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/enviarAssinaturaCRM') ?>",
        type: "POST",
        data: {
            idCotacao: quoteid
        },
        success: function(data) {
            botao.html(htmlBotao); // retorna o texto original do botão
            botao.attr('disabled', false);
            if (data) {
                data = JSON.parse(data);
                //alerta com a resposta do php ou alerta de erro
                data ? alert(data?.Message) : alert("Ocorreu um problema ao Enviar Assinatura, tente novamente.");
            } else {
                alert("Ocorreu um problema ao Enviar Assinatura, tente novamente.");
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            botao.html(htmlBotao); // retorna o texto original do botão
            botao.attr('disabled', false);
            alert("Ocorreu um erro ao processar a solicitação.");
        },
        complete: function() {
            // fecha a tela de carregamento
            hideLoadingScreen();
            window.location.reload();         
        }
    });
});

$(document).on('click', '#AddArquivoOportunidade', function(e) {
        e.preventDefault();

        $('.feedback-alert').html('');

        document.getElementById('loading').style.display = 'block';

		var dataForm = new FormData();
        
		//File data
		var customerid_oportunidades = $('#customerid_oportunidades').val();

		var file_data = $('#files_oportunidades')[0];
        file = file_data.files[0];

        if(!file){
            document.getElementById('loading').style.display = 'none';
            alert("Selecione um Arquivo.");
            return;
        }
        
        dataForm.append("Arquivo", file);
        dataForm.append("customerid", customerid_oportunidades);
        
        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/addArquivoClienteCRM') ?>",
            type: "POST",
            data: dataForm,     
			processData: false,
			contentType: false,  
            success: function(data){
                document.getElementById('loading').style.display = 'none';
                let datajson = JSON.parse(data);
                if(data && datajson['Status'] == 200){
                    alert("Arquivo enviado com sucesso.");
                    AtualizarTabelaDocumentosOportunidades(customerid_oportunidades);
                    $('#fileLabel').text('Selecione um Arquivo');

                }else{
                    alert("Ocorreu um problema ao Enviar o Arquivo.");
                }
            },
            error: function(error){
                document.getElementById('loading').style.display = 'none';
                alert("Ocorreu um problema ao Enviar o Arquivo.");
            },
            
        });  
     });





$(document).on('click', '.solicitar_status', async function(e) {
    e.preventDefault();

    $('.feedback-alert').html('');

    var botao3 = $(this);
    var status = botao3.attr('data-status');

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/solicitarStatus') ?>",
        type: "POST",
        data: {
            idCotacao: status
        },
        success: async function(data) {
            if (data) {
                
                var resposta = data;
                await buscarHistoricoAf(botao3, resposta, ICONS).then(function(response) {


                    // Destruir a tabela existente, se houver
                    if ($.fn.DataTable.isDataTable('#tableHistoricoStatusAF')) {
                        $('#tableHistoricoStatusAF').DataTable().destroy();
                    }

                    // Criar uma nova tabela DataTable
                    let tableHistoricoStatusAF = $('#tableHistoricoStatusAF')
                        .DataTable({
                                responsive: true,
                                ordering: false,
                                paging: true,
                                searching: true,
                                info: true,
                                language: lang.datatable,
                                deferRender: true,
                                lengthChange: false,
                                data: response.data,
                                columns: [{
                                        data: "dataEvento"
                                    }, // define a coluna "dataEvento" como a fonte de dados da primeira coluna da tabela
                                    {
                                        data: "observacoes"
                                    } // define a coluna "observacoes" como a fonte de dados da segunda coluna da tabela
                                ]
                            });

                    // Abrir o modal com a tabela atualizada
                    $('#modalHistoricoStatusAF').modal('show');
                });
            } else {
                alert("Ainda não há registro de Status para essa AF, tente novamente mais tarde.");
            }
        },
        error: function(xhr, textStatus, errorThrown) {

            $('#modalHistoricoStatusAF .modal-title').text('Erro');
            $('#modalHistoricoStatusAF .modal-body').text(
                'Ocorreu um erro ao processar a solicitação.');
            $('#modalHistoricoStatusAF').modal('show');
        }
    });
});

$(document).ready(function() {
    $('#tableHistoricoStatusAF').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "info": false
    });
});

async function buscarHistoricoAf(botao, resposta, ICONS) {
    $('.feedback-alert').html('');

    // Acessar o ID da AF a partir do objeto de resposta
    let idAF = resposta;
    botao = $(botao);
    let htmlBotao = botao.html();
    botao.html(ICONS.spinner);
    botao.attr('disabled', true);

  const requisicaoAjax = $.ajax({
    url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/buscarHistoricoAf') ?>",
    type: "POST",
    data: { idAF }
  });

  const requisicaoAjaxN = $.ajax({
    url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/buscarNumeroAf') ?>",
    type: "POST",
    data: { idAF }
  });
  
requisicaoAjax.then(function(callback) {        
        requisicaoAjaxN.then(function(callbackN) {
        callbackN = JSON.parse(callbackN); 
        callback = JSON.parse(callback);
        if (callback.status == 200) { 
            $('#labelNumeroAFO').text('Número da AF: ' + callbackN.data);
        
        $('#tableHistoricoStatusAF tbody').empty();
        preencherTabela(callback.data);
        if (callback.data.length == 0) {
                var table = document.getElementById("tableHistoricoStatusAF");
                var tbody = table.getElementsByTagName("tbody")[0];
                var row = tbody.insertRow();
                var cell = row.insertCell(0);
                cell.colSpan = 2;
                cell.className = "text-center";
                cell.textContent = "Nenhum registro encontrado.";
            }
            $("#modalHistoricoStatusAF").modal("show");            
        }
        else {alert("erro ao buscar dados"); }    
    })
});

    $('#modalHistoricoStatusAF').on('hidden.bs.modal', function(e) {
        botao.html(htmlBotao);
        botao.attr('disabled', false);
    });
}

function preencherTabela(data) {
    $('.feedback-alert').html('');

    var table = document.getElementById("tableHistoricoStatusAFBody");
    for (var i = 0; i < data.length; i++) {
        var row = table.insertRow();
        var dataEventoCell = row.insertCell(0);
        var observacoesCell = row.insertCell(1);
        dataEventoCell.innerHTML = data[i].dataEvento;
        observacoesCell.innerHTML = data[i].observacoes;
    }
    // Destruir a tabela existente, se houver
    if ($.fn.DataTable.isDataTable('#tableHistoricoStatusAF')) {
        $('#tableHistoricoStatusAF').DataTable().destroy();
    }

    // Criar uma nova tabela DataTable
    let tableHistoricoStatusAF = $('#tableHistoricoStatusAF')
    .DataTable({
        ordering: true,
        info: true,
        language: lang.datatable,
        deferRender: true,
        "order": [
            [0, "desc"]
        ],
    });
}

$(document).on('click', '.listagem_docs_oportunidades', function(e) {
    e.preventDefault();

    $('.feedback-alert').html('');

    document.getElementById('loading').style.display = 'block';
    var botaoDocumentos = $(this);
    var customerid = botaoDocumentos.attr('data-customerid');
    
    let htmlBotao = botaoDocumentos.html();
    botaoDocumentos.html(ICONS.spinner);
    botaoDocumentos.attr('disabled', true);
    $('#customerid_oportunidades').val(customerid);
    
    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/solicitarArquivos?documento=') ?>"+customerid,
        type: "GET",
        success: function(data) {
            document.getElementById('loading').style.display = 'none';
            let datajson = JSON.parse(data);
            if (data && datajson['Status'] == 200) {
                var resposta = datajson['Message'];                
                $('#tableListagemDocumentosBody_oportunidades').empty();   
                preencherTabelaDocumentosOportunidades(resposta);
                if (resposta.length == 0) {
                    var table = document.getElementById("tableListagemDocumentos_oportunidades");
                    var tbody = table.getElementsByTagName("tbody")[0];
                    var row = tbody.insertRow();
                    var cell = row.insertCell(0);
                    cell.colSpan = 2;
                    cell.className = "text-center";
                    cell.textContent = "Nenhum registro encontrado.";
                }

                $("#modalListagemDocumentos_oportunidades").modal("show");  

                $('#modalListagemDocumentos_oportunidades').on('hidden.bs.modal', function (e) {
                    botaoDocumentos.html(htmlBotao);
                    botaoDocumentos.attr('disabled', false);
                });
            } else {
                alert("Ocorreu um problema, tente novamente mais tarde.");
                botaoDocumentos.html(htmlBotao);
                botaoDocumentos.attr('disabled', false);
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            document.getElementById('loading').style.display = 'none';
            $('#modalListagemDocumentos_oportunidades .modal-title').text('Erro');
            $('#modalListagemDocumentos_oportunidades .modal-body').text('Ocorreu um erro ao processar a solicitação.');
            $('#modalListagemDocumentos_oportunidades').modal('show');
        },
        complete: function() {
            <?php $_SESSION['acao'] = "carregar"; ?>
        }
    });
});

function preencherTabelaDocumentosOportunidades(data) {
    $('.feedback-alert').html('');

    var table = document.getElementById("tableListagemDocumentos_oportunidades");
    for (var i = 0; i < data.length; i++) {
        tbody = table.getElementsByTagName("tbody")[0];
        var row = tbody.insertRow();
        var dataCell = row.insertCell(0);
        var documentoCell = row.insertCell(1);
        /* var acaoCell = row.insertCell(2); */
        var acoesCell = row.insertCell(2);
        dataCell.innerHTML = data[i].createdon;
        documentoCell.innerHTML = data[i].Subject;
        /* acaoCell.innerHTML = '<div> <a data-idAnnotation="'+data[i].idAnnotation+'" class="btn btn-primary download_docs" role="button" style="margin-top: 4px;" title="Download"><i class="fa fa-download"></i></a></button></div>'; */
        acoesCell.innerHTML = `<div>
                                    <button 
                                    id="btnRemoveDocumentoOportunidades"
						            class="btn"
						            title="Remover Documento"
						            style="margin: 0 auto; background-color: red; color: white; height: 34px;"
                                    onClick="javascript:removerDocumentoOportunidade(this, event, '${data[i].idAnnotation}')"
                                    data-documento="<?php echo isset($_SESSION['documento'])?$_SESSION['documento']: "";?>">
                                    <i class="fa fa-trash" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
					                </button>
                                    <a data-idAnnotation='${data[i].idAnnotation}' class="btn btn-primary download_docs" role="button" title="Download" style="height: 34px;"><i class="fa fa-download"></i></a></button>
                                </div>`;
    }
}

function removerDocumentoOportunidade(element, event, idAnnotation) {
    event.preventDefault();
    $('.feedback-alert').html('');
    if(confirm('Deseja realmente remover este documento?')){
        let botao = $(element);
        let documento = botao.attr('data-documento');
        botao.html('<i class="fa fa-spinner fa-spin"></i>');
        botao.attr('disabled', true);

        $.ajax({
            url: `<?= site_url('ComerciaisTelevendas/Pedidos/removerDocumentos') ?>`,
            type: 'POST',
            data: {idDocumento: idAnnotation},
            success: function (response){ 
                dataJson = JSON.parse(response)
                if(dataJson.status == 204){
                    alert('Documento removido com sucesso!');
                    botao.html('<i class="fa fa-trash"></i>');
                    botao.attr('disabled', false);
                    if (documento){
                        AtualizarTabelaDocumentosOportunidades(documento);
                    }else{
                        AtualizarTabelaDocumentosCotacao(cotacaoId);
                    }

                }else{
                    alert('Erro ao remover documento!');
                    botao.html('<i class="fa fa-trash"></i>');
                    botao.attr('disabled', false);
                } 
            },
            error: function (response) {
                alert('Erro ao remover documento!');
                botao.html('<i class="fa fa-trash"></i>');
                botao.attr('disabled', false);
                
            }
        });
    }else{
        return false;
    }
}

function AtualizarTabelaDocumentosOportunidades(customerid){
    $('.feedback-alert').html('');

    document.getElementById('loading').style.display = 'block';

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/solicitarArquivos?documento=') ?>"+customerid,
        type: "GET",
        success: function(data) {
            document.getElementById('loading').style.display = 'none';
            let datajson = JSON.parse(data);
            if (data && datajson['Status'] == 200) {
                var resposta = datajson['Message'];                
                $('#tableListagemDocumentosBody_oportunidades').empty();   
                preencherTabelaDocumentosOportunidades(resposta);
                if (resposta.length == 0) {
                    var table = document.getElementById("tableListagemDocumentos_oportunidades");
                    var tbody = table.getElementsByTagName("tbody")[0];
                    var row = tbody.insertRow();
                    var cell = row.insertCell(0);
                    cell.colSpan = 2;
                    cell.className = "text-center";
                    cell.textContent = "Nenhum registro encontrado.";
                }
            } else {
                alert("Ocorreu um problema, tente novamente mais tarde.");
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            document.getElementById('loading').style.display = 'none';
            $('#modalListagemDocumentos_oportunidades .modal-title').text('Erro');
            $('#modalListagemDocumentos_oportunidades .modal-body').text('Ocorreu um erro ao processar a solicitação.');
            $('#modalListagemDocumentos_oportunidades').modal('show');
        }
    });
}

async function CadastrarOportunidade(){
    $('.feedback-alert').html('');
    $('#idCotacao').val("");
    $('#tempoContrato').val("");
    $('#qtdVeiculos').val(0);
    $('#divOrigem').show();
    $('#origem').attr('required', true);
    $('#documentoClienteOportunidade').prop('disabled', false);
    $('#planoSatelital').prop('disabled', false);
    $('#qtdVeiculos').prop('readonly', false);
    $('#divNomeCliente').hide();
    $('#modalidadeVenda').val(1).trigger('change');
    $('#documentoClienteOportunidade').val("");

	resetarCamposClienteRetiraArmazem();

    $("#condicao_pagamento").select2({
        width: '100%',
        placeholder: "Selecione a Condição do Pagamento",
        allowClear: true
    })
    $("#condicao_pagamento").find('option').get(0).remove()
    $("#condicao_pagamento").prepend(
        `<option selected disabled value="">Selecione a Condição do Pagamento</option>`)

    $(".divVigencia").hide()
    $("#inicioVigencia").attr("required", false)
    $("#terminoVigencia").attr("required", false)
    $("#exampleModalLabel").text("Cadastro de Oportunidade")

    $("#formProposta").prop("action", "<?php echo site_url('ComerciaisTelevendas/Pedidos/createOportunidade');?>")

    document.getElementById('loading').style.display = 'block';

    await povoarPlanos().then(e => {
        $("#planoSatelital").select2({
            width: '100%',
            placeholder: "Selecione um tipo de plano",
            allowClear: true
        })
        $("#planoSatelital").find('option').get(0).remove()
        $("#planoSatelital").prepend(
            `<option selected disabled value="">Selecione um tipo de plano</option>`)
    })

    await povoarProdutos().then(e => {
        $("#ID_Produto").select2({
            width: '100%',
            placeholder: "Selecione o Produto",
            allowClear: true,
            // matcher: matchCustom
        })
        $("#ID_Produto").find('option').get(0).remove()
        $("#ID_Produto").prepend(
            `<option selected disabled value="">Selecione um produto</option>`)
    })

    $('#cenario_venda').select2({
        width: '100%',
        placeholder: "Selecione o cenário de venda",
        allowClear: true,
        language: "pt-BR",
    });

    $('#cenario_venda').empty();
    $('#cenario_venda').append('<option value="0" selected disabled>Aguardando seleção da plataforma...</option>');
    $('#cenario_venda').attr('disabled', true);
    
    await povoarPagamentos().then(e => {
        $("#tipo_pagamento").select2({
            width: '100%',
            placeholder: "Selecione a forma de pagamento",
            allowClear: true
        })
        $("#tipo_pagamento").find('option').get(0).remove()
        $("#tipo_pagamento").prepend(
            `<option selected disabled value="">Selecione um tipo de pagamento</option>`)
    })

    await povoarVeiculos().then(e => {
        $("#tipoVeiculo").select2({
            width: '100%',
            placeholder: "Selecione um tipo de veículo",
            allowClear: true
        })
        $("#tipoVeiculo").find('option').get(0).remove()
        $("#tipoVeiculo").prepend(
            `<option selected disabled value="">Selecione um tipo de veículo</option>`)
    })

    let plataformas  = await $.ajax ({
                            url: '<?= site_url('ComerciaisTelevendas/Pedidos/listarPlataformas') ?>',
                            dataType: 'json',
                            delay: 1000,
                            type: 'GET',
                            data: function (params) {
                                return {
                                    q: params.term
                                };
                            },
                            error: function(){
                                alert('Erro ao buscar plataformas, tente novamente');
                                btn.attr('disabled', false).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
                            }
                        });

    $('#plataformaCad').select2({
        data: plataformas,
        placeholder: "Selecione a plataforma",
        allowClear: true,
        language: "pt-BR",
        width: '100%'
        });
    
    $('#plataformaCad').on('select2:select', function (e) {
        var data = e.params.data;
    });

    $('#plataformaCad').val(null).trigger('change');

    planoSatelital = null;
    condicao_pagamento = null;

    $("#exampleModal").modal("show")
    document.getElementById('loading').style.display = 'none';

    await povoarArmazens().then(e => {
        $("#armazem").select2({
            width: '100%',
            placeholder: "Selecione o armazém",
            allowClear: true
        })
        $("#armazem").find('option').get(0).remove()
        $("#armazem").prepend(
            `<option selected disabled value="">Selecione um armazém</option>`)
    });
}

var planoSatelital;
var condicao_pagamento;

async function EditarOportunidade(botao){
    $('.feedback-alert').html('');
    $('#nomeClienteOportunidade').val('');
    $('#nomeClienteSoftwareOportunidade').val('');
    $('#emailClienteSoftwareOportunidade').val('');
    $('#documentoClienteSoftwareOportunidade').val('');
    $('#nomeClienteHardwareOportunidade').val('');
    $('#emailClienteHardwareOportunidade').val('');
    $('#documentoClienteHardwareOportunidade').val('');
    //Desabilitar
    $('#divOrigemEditar').hide();
    $('#origemEditar').attr('required', false);
    $('#planoSatelitalEditar').prop('disabled', true);
    $('#qtdVeiculosEditar').prop('readonly', false);

    $("#editarOportunidadeModalLabel").text("Edição de Oportunidade");

    document.getElementById('loading').style.display = 'block';

    await povoarProdutosEditar().then(e => {
        $("#ID_ProdutoEditar").select2({
            width: '100%',
            placeholder: "Selecione o Produto",
            allowClear: true,
            // matcher: matchCustom
        })
        $("#ID_ProdutoEditar").find('option').get(0).remove()
        $("#ID_ProdutoEditar").prepend(
            `<option selected disabled value="">Selecione um produto</option>`)
    })

    await povoarCenariosEditar().then(e => {
        $("#cenario_vendaEditar").select2({
            width: '100%',
            placeholder: "Selecione o Cenário",
            allowClear: true
        })
        $("#cenario_vendaEditar").find('option').get(0).remove()
        $("#cenario_vendaEditar").prepend(
            `<option selected disabled value="">Selecione um cenário de venda</option>`)
    })

    await povoarPagamentosEditar().then(e => {
        $("#tipo_pagamentoEditar").select2({
            width: '100%',
            placeholder: "Selecione a forma de pagamento",
            allowClear: true
        })
        $("#tipo_pagamentoEditar").find('option').get(0).remove()
        $("#tipo_pagamentoEditar").prepend(
            `<option selected disabled value="">Selecione um tipo de pagamento</option>`)
    })

    await povoarVeiculosEditar().then(e => {
        $("#tipoVeiculoEditar").select2({
            width: '100%',
            placeholder: "Selecione um tipo de veículo",
            allowClear: true
        })
        $("#tipoVeiculoEditar").find('option').get(0).remove()
        $("#tipoVeiculoEditar").prepend(
            `<option selected disabled value="">Selecione um tipo de veículo</option>`)
    })

    await povoarArmazensEditar().then(e => {
        $("#armazemEditar").select2({
            width: '100%',
            placeholder: "Selecione o armazém",
            allowClear: true
        })
        $("#armazemEditar").find('option').get(0).remove()
        $("#armazemEditar").prepend(
            `<option selected disabled value="">Selecione um armazém</option>`)
    });

    let idCotacao = $(botao).data('quoteid');

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/resumoCotacao') ?>",
        type: "POST",
        data: {
            idCotacao: idCotacao
        },
        success: async function(data) {
            let datajson = JSON.parse(data);
            let resumo = datajson['resumo'];
            var idModalidadeVenda = '';
            
            if (resumo['modalidadeVenda'] == 'Serviços Adicionais'){
                idModalidadeVenda = '8';
            }else if(resumo['modalidadeVenda'] == 'Veículo Novo'){
                idModalidadeVenda = '1';
            }else if (resumo['modalidadeVenda'] == 'Omnicarga'){
                idModalidadeVenda = '9';
            }else{
                idModalidadeVenda = '13';
            }

            
            if (resumo['composicao']['produto'] == "RASTREADOR INTELIGENTE RI4484") {
                resumo['composicao']['produto'] = "OMNITURBO"
            }
            else{
                if(resumo['composicao']['produto'] == "RASTREADOR INTELIGENTE RI4454"){ 
                    resumo['composicao']['produto'] = "OMNIDUAL"
                }
            }

            if (resumo['cliente']) {
                $("#editarOportunidadeModalLabel").text("Edição de Oportunidade - Cliente: " + resumo['cliente'])
            }

            $('#modalidadeVendaEditar').val(idModalidadeVenda).trigger('change');

            $('#idCotacaoEditar').val(idCotacao);

            $('#qtdVeiculosEditar').val(resumo['composicao']['quantidade']);

            // selects
            $('#tempoContratoEditar').val(resumo['tempoContrato']);

            if (idModalidadeVenda == '1'|| idModalidadeVenda == '9' || idModalidadeVenda == '13'){
                console.log('prod', resumo['composicao']['produto']);
                if (resumo['composicao']['produto']) {
                    $("#ID_ProdutoEditar option").filter(function() {
                        return $(this).text().includes(resumo['composicao']['produto']);
                    }).prop('selected', true).trigger('change');
                }
            }

            $("#tipo_pagamentoEditar option").filter(function() {
                return $(this).text().includes(resumo['tipoPagamento']);
            }).prop('selected', true).trigger('change');

			// Reseta os campos de retirada de armazem
			resetarCamposClienteRetiraArmazem('Editar');

			if (resumo['clientRetiraArmazem'] === 'SIM') {
				$("#clientRetiraArmazemEditar").val('1');

				if(resumo['armazem']) {
					$("#armazemEditar option").filter(function() {
						return $(this).text().includes(resumo['armazem']);
					}).prop('selected', true).trigger('change');
				}
				$("#col-armazemEditar").show();
				

				$("#responsavelRetiradaEditar").val(resumo['responsavelRetirada'] || '').attr('required', true);
				$("#col-responsavelRetiradaEditar").show();

				$('#cpfResponsavelRetiradaEditar').val(resumo['cpfResponsavelRetirada'] || '').attr('required', true);
				$('#col-cpfResponsavelRetiradaEditar').show();
			}

            $("#cenario_vendaEditar option").filter(function() {
                return $(this).text() == resumo['cenarioVenda'];
            }).prop('selected', true).trigger('change');

            $("#tipoVeiculoEditar option").filter(function() {
                return $(this).text() ==  (resumo['composicao']['tipoVeiculo']);
            }).prop('selected', true).trigger('change');

            setTimeout(function() {
                if (idModalidadeVenda == '1' || idModalidadeVenda == '13') {
                    if (resumo['cenarioVenda'] != 'VENDA DIRETA OMNIFROTA' && resumo['cenarioVenda'] != 'VENDA DIRETA OMNICARGA'){
                            if (resumo['licencas'].length > 0){
                                $("#planoSatelitalEditar option").filter(function() {
                                    return $(this).text() == (resumo['licencas'][0])['planoSatelital'] ? (resumo['licencas'][0])['planoSatelital'] : '';
                                }).prop('selected', true).trigger('change');
                                planoSatelital = (resumo['licencas'][0])['planoSatelital'] ? (resumo['licencas'][0])['planoSatelital'] : '';
                            } else {
                                $("#planoSatelitalEditar option").filter(function() {
                                    return $(this).text() == '';
                                }).prop('selected', true).trigger('change');
                                planoSatelital = '';
                            }
                    }
                }
            }, 3000);

            $('#nomeClienteSoftwareOportunidade').val(resumo['signatario_software'] ? resumo['signatario_software'] : '');
            $('#emailClienteSoftwareOportunidade').val(resumo['email_signatario_software'] ? resumo['email_signatario_software'] : '');
            $('#documentoClienteSoftwareOportunidade').val(resumo['documento_signatario_software'] ? resumo['documento_signatario_software'] : '');
            $('#nomeClienteHardwareOportunidade').val(resumo['signatario_hardware'] ? resumo['signatario_hardware'] : '');
            $('#emailClienteHardwareOportunidade').val(resumo['email_signatario_hardware'] ? resumo['email_signatario_hardware'] : '');
            $('#documentoClienteHardwareOportunidade').val(resumo['documento_signatario_hardware'] ? resumo['documento_signatario_hardware'] : '');

            condicao_pagamento = resumo['condicaoPagamento'];

            if(resumo['inicioVigencia']){
                let inicioVigencia = formatDate(resumo['inicioVigencia'])
                $('#inicioVigenciaEditar').val(inicioVigencia);
            }else{
                $('#inicioVigenciaEditar').val('');
            }

            if(resumo['terminoVigencia']){
                let terminoVigencia = formatDate(resumo['terminoVigencia'])
                $('#terminoVigenciaEditar').val(terminoVigencia);
            }else{
                $('#terminoVigenciaEditar').val('');
            }

        },
        error: function (xhr, textStatus, errorThrown) {
            document.getElementById('loading').style.display = 'none';
        },
        complete: function() {
            document.getElementById('loading').style.display = 'none';
        }
    });

    $("#editarOportunidadeModal").modal("show")
    var idCliente = $(botao).data('idclientecot');
    var tipoCliente = $(botao).data('tipocliente');

    $('#btnEditar').data('idCliente', idCliente);
    $('#btnEditar').data('tipoCliente', tipoCliente);
}

function resetarCamposClienteRetiraArmazem(acao='') {;
	// Reseta os campos de retirada de armazem
	$(`#clientRetiraArmazem${acao}`).val('2');

	$(`#armazem${acao}`).val(null).attr('required', false).trigger('change');
	$(`#col-armazem${acao}`).hide();
	
	$(`#responsavelRetirada${acao}`).val('').attr('required', false);
	$(`#col-responsavelRetirada${acao}`).hide();

	$(`#cpfResponsavelRetirada${acao}`).val('').attr('required', false);
	$(`#col-cpfResponsavelRetirada${acao}`).hide();
}

function formatDate(date){
    date = date.split('/');
    return date[2] + "-" + date[1] + "-" + date[0];
}

async function povoarProdutos() {
    $('.feedback-alert').html('');
    $("#ID_Produto").empty()
    $("#ID_Produto").prepend(
        `<option value="0">Buscando produtos...</option>`)
    $("#ID_Produto").select2({
        width: '100%',
        placeholder: "Buscando produtos disponíveis...",
        allowClear: true,
        // matcher: matchCustom
    })
    let produtos = await buscarProdutos()
    if (produtos) produtos = JSON.parse(produtos)

    if (produtos?.length) {
        produtos.forEach(produto => {
            let nomeDoProduto = ""
            if (produto.name == "RASTREADOR INTELIGENTE RI4484") {
                
                nomeDoProduto = "OMNITURBO"
            }
            else{
                if(produto.name == "RASTREADOR INTELIGENTE RI4454"){ 
                    nomeDoProduto = "OMNIDUAL"
                }
                else{
                    nomeDoProduto =  produto.name
                }
            }
            $("#ID_Produto").append(`<option data-produto="${produto.productid}" value="${produto.productnumber}">${nomeDoProduto} - ${produto.productnumber}</option>`)
            $("#ID_Produto").find('option').get(0).remove()
            $("#ID_Produto").prepend(`<option selected disabled value="">Selecione um produto</option>`)
        })
    } else {
        $("#ID_Produto").find('option').get(0).remove()
        $("#ID_Produto").prepend(`<option selected disabled value="">Nenhum produto encontrado</option>`)
        alert("Esta empresa não possui produtos cadastrados.")
    }
}

async function povoarProdutosEditar() {
    $('.feedback-alert').html('');
    $("#ID_ProdutoEditar").empty()
    $("#ID_ProdutoEditar").prepend(
        `<option value="0">Buscando produtos...</option>`)
    $("#ID_ProdutoEditar").select2({
        width: '100%',
        placeholder: "Buscando produtos disponíveis...",
        allowClear: true,
        // matcher: matchCustom
    })
    let produtos = await buscarProdutos()
    if (produtos) produtos = JSON.parse(produtos)

    if (produtos?.length) {
        produtos.forEach(produto => {
            let nomeDoProduto = ""
            if (produto.name == "RASTREADOR INTELIGENTE RI4484") {
                
                nomeDoProduto = "OMNITURBO"
            }
            else{
                if(produto.name == "RASTREADOR INTELIGENTE RI4454"){ 
                    nomeDoProduto = "OMNIDUAL"
                }
                else{
                    nomeDoProduto =  produto.name
                }
            }
            $("#ID_ProdutoEditar").append(`<option data-produto="${produto.productid}" value="${produto.productnumber}">${nomeDoProduto} - ${produto.productnumber}</option>`)
            $("#ID_ProdutoEditar").find('option').get(0).remove()
            $("#ID_ProdutoEditar").prepend(`<option selected disabled value="">Selecione um produto</option>`)
        })
    } else {
        $("#ID_ProdutoEditar").find('option').get(0).remove()
        $("#ID_ProdutoEditar").prepend(`<option selected disabled value="">Nenhum produto encontrado</option>`)
        alert("Esta empresa não possui produtos cadastrados.")
    }
}


$("#ID_Produto").on("change", async function() {
    $('.feedback-alert').html('');
    let selectedProduct = $(this).val();
    let planoSatelitalField = $("#planoSatelital"); 
    let cenarioVenda = $("#cenario_venda").find('option:selected').text();
    let nomeProduto = $("#ID_Produto").find('option:selected').text();

    if (!($('#modalidadeVenda').val() == '9')){
        if (cenarioVenda != 'VENDA DIRETA OMNIFROTA' && cenarioVenda != 'VENDA DIRETA OMNICARGA'){
            // Verifique se o produto selecionado é "OMNIDUAL"
            if (selectedProduct === "4100.0209.00" || nomeProduto.includes('RASTREADOR OMNIFROTA') || nomeProduto.includes('OMNICARGA')) {
                // Habilitar o campo de seleção do plano satelital
                planoSatelitalField.prop("disabled", true);
                // Remover a validação de campo obrigatório
                planoSatelitalField.removeAttr("required");          
            } else {
                // Desabilitar o campo de seleção do plano satelital
                planoSatelitalField.prop("disabled", false);        
                // Adicionar a validação para torná-lo obrigatório
                planoSatelitalField.prop("required", true);
                // Limpar a seleção atual, se houver
                planoSatelitalField.val(null).trigger("change");
                await povoarPlanos();
            }
        }else{
            planoSatelitalField.prop("disabled", true);
            planoSatelitalField.val(null).trigger("change");     
            planoSatelitalField.prop("required", false);
           
        }
    }
});

$('#cpfResponsavelRetirada').mask('000.000.000-00', {reverse: true});
$('#cpfResponsavelRetiradaEditar').mask('000.000.000-00', {reverse: true});

$('#clientRetiraArmazem').on('change', function() {
    if ($(this).val() == '1') {
        $('#armazem').attr('required', true);
        $('#col-armazem').show();
        $('#cpfResponsavelRetirada').attr('required', true);
        $('#col-cpfResponsavelRetirada').show();
        $('#responsavelRetirada').attr('required', true);
        $('#col-responsavelRetirada').show();
    } else {
        $('#armazem').attr('required', false);
        $('#col-armazem').hide();
        $('#cpfResponsavelRetirada').attr('required', false);
        $('#col-cpfResponsavelRetirada').hide();
        $('#responsavelRetirada').attr('required', false);
        $('#col-responsavelRetirada').hide();
    }
});

$('#clientRetiraArmazemEditar').on('change', function () {
    if ($(this).val() == '1') {
        $('#armazemEditar').attr('required', true);
        $('#col-armazemEditar').show();
        $('#cpfResponsavelRetiradaEditar').attr('required', true);
        $('#col-cpfResponsavelRetiradaEditar').show();
        $('#responsavelRetiradaEditar').attr('required', true);
        $('#col-responsavelRetiradaEditar').show();
    } else {
        $('#armazemEditar').attr('required', false);
        $('#col-armazemEditar').hide();
        $('#cpfResponsavelRetiradaEditar').attr('required', false);
        $('#col-cpfResponsavelRetiradaEditar').hide();
        $('#responsavelRetiradaEditar').attr('required', false);
        $('#col-responsavelRetiradaEditar').hide();
    }
});

$("#ID_ProdutoEditar").on("change", async function() {
    $('.feedback-alert').html('');
    let selectedProduct = $(this).val();
    let planoSatelitalField = $("#planoSatelitalEditar"); 
    let cenarioVenda = $("#cenario_vendaEditar").find('option:selected').text();
    let nomeProduto = $("#ID_ProdutoEditar").find('option:selected').text();

    if (!($('#modalidadeVendaEditar').val() == '9')){
        if (cenarioVenda != 'VENDA DIRETA OMNIFROTA' && cenarioVenda != 'VENDA DIRETA OMNICARGA'){
            // Verifique se o produto selecionado é "OMNIDUAL"
            if (selectedProduct === "4100.0209.00" || nomeProduto.includes('RASTREADOR OMNIFROTA') || nomeProduto.includes('OMNICARGA')) {
                // Habilitar o campo de seleção do plano satelital
                planoSatelitalField.prop("disabled", true);
                planoSatelitalField.val(null).trigger("change");
                // Remover a validação de campo obrigatório
                planoSatelitalField.removeAttr("required");          
            } else {
                // Desabilitar o campo de seleção do plano satelital
                planoSatelitalField.prop("disabled", false);        
                // Adicionar a validação para torná-lo obrigatório
                planoSatelitalField.prop("required", true);
                // Limpar a seleção atual, se houver
                planoSatelitalField.val(null).trigger("change");
                await povoarPlanosEditar();
            }
        }else{
            planoSatelitalField.prop("disabled", true);
            planoSatelitalField.val(null).trigger("change");     
            planoSatelitalField.prop("required", false);
           
        }
    }
});


async function buscarProdutos() {
    produtos = await $.ajax({
        url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_idProdutoCRM') ?>`,
        type: "POST",
        success: function(response) {
            return response
        },
        error: function(error) {
            return false
        },
    })
    return produtos
}

//################## fim do id produtos

//################## popular select do cenário de venda
$('#open-modal').click(async function() {
    CadastrarOportunidade();
})

async function povoarCenarios() {
    $("#cenario_venda").empty()
    $("#cenario_venda").prepend(
        `<option value="0">Buscando cenários...</option>`)
    $("#cenario_venda").select2({
        width: '100%',
        placeholder: "Buscando cenários disponíveis...",
        allowClear: true
    })
    let cenarios = await buscarCenarios()
    if (cenarios) cenarios = JSON.parse(cenarios)

    if (cenarios?.length) {
        cenarios.forEach(cenario => {
            $("#cenario_venda").append(
                `<option value="${cenario.tz_cenario_vendaid}">${cenario.tz_name}</option>`)
            $("#cenario_venda").find('option').get(0).remove()
            $("#cenario_venda").prepend(`<option selected disabled value="">Selecione um cenário</option>`)
        })
    } else {
        $("#cenario_venda").find('option').get(0).remove()
        $("#cenario_venda").prepend(`<option selected disabled value="">Nenhum cenário encontrado</option>`)
        alert("Esta empresa não possui cenários cadastrados.")
    }
}

async function povoarCenariosEditar() {
    $("#cenario_vendaEditar").empty()
    $("#cenario_vendaEditar").prepend(
        `<option value="0">Buscando cenários...</option>`)
    $("#cenario_vendaEditar").select2({
        width: '100%',
        placeholder: "Buscando cenários disponíveis...",
        allowClear: true
    })
    let cenarios = await buscarCenarios()
    if (cenarios) cenarios = JSON.parse(cenarios)

    if (cenarios?.length) {
        cenarios.forEach(cenario => {
            $("#cenario_vendaEditar").append(
                `<option value="${cenario.tz_cenario_vendaid}">${cenario.tz_name}</option>`)
            $("#cenario_vendaEditar").find('option').get(0).remove()
            $("#cenario_vendaEditar").prepend(`<option selected disabled value="">Selecione um cenário</option>`)
        })
    } else {
        $("#cenario_vendaEditar").find('option').get(0).remove()
        $("#cenario_vendaEditar").prepend(`<option selected disabled value="">Nenhum cenário encontrado</option>`)
        alert("Esta empresa não possui cenários cadastrados.")
    }
}

async function buscarCenarios() {
    cenarios = await $.ajax({
        url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_cenarioVendaCRM') ?>`,
        type: "POST",
        success: function(response) {
            return response
        },
        error: function(error) {
            return false
        },
    })
    return cenarios
}

//################## fim do cenario_venda

//################## popular select do tipo de pagamento

async function povoarPagamentos() {
    $("#tipo_pagamento").empty()
    $("#tipo_pagamento").prepend(
        `<option value="0">Buscando tipos de pagamento...</option>`)
    $("#tipo_pagamento").select2({
        width: '100%',
        placeholder: "Buscando os tipos de pagamento disponíveis...",
        allowClear: true
    })
    let pagamentos = await buscarPagamentos()
    if (pagamentos) pagamentos = JSON.parse(pagamentos)

    if (pagamentos?.length) {
        pagamentos.forEach(pagamento => {

            $("#tipo_pagamento").append(
                `<option tz_codigo_erp="${pagamento.tz_codigo_erp}"  value="${pagamento.tz_tipo_pagamentoid}">${pagamento.tz_name}</option>`
            )

            $("#tipo_pagamento").find('option').get(0).remove()
            $("#tipo_pagamento").prepend(
                `<option selected disabled value="">Selecione um tipo de pagamento</option>`)
        })
    } else {
        $("#tipo_pagamento").find('option').get(0).remove()
        $("#tipo_pagamento").prepend(
            `<option selected disabled value="">Nenhum tipo de pagamento encontrado</option>`)
        alert("Esta empresa não possui tipos de pagamentos cadastrados.")
    }
}

async function povoarPagamentosEditar() {
    $("#tipo_pagamentoEditar").empty()
    $("#tipo_pagamentoEditar").prepend(
        `<option value="0">Buscando tipos de pagamento...</option>`)
    $("#tipo_pagamentoEditar").select2({
        width: '100%',
        placeholder: "Buscando os tipos de pagamento disponíveis...",
        allowClear: true
    })
    let pagamentos = await buscarPagamentos()
    if (pagamentos) pagamentos = JSON.parse(pagamentos)

    if (pagamentos?.length) {
        pagamentos.forEach(pagamento => {

            $("#tipo_pagamentoEditar").append(
                `<option tz_codigo_erp="${pagamento.tz_codigo_erp}"  value="${pagamento.tz_tipo_pagamentoid}">${pagamento.tz_name}</option>`
            )

            $("#tipo_pagamentoEditar").find('option').get(0).remove()
            $("#tipo_pagamentoEditar").prepend(
                `<option selected disabled value="">Selecione um tipo de pagamento</option>`)
        })
    } else {
        $("#tipo_pagamentoEditar").find('option').get(0).remove()
        $("#tipo_pagamentoEditar").prepend(
            `<option selected disabled value="">Nenhum tipo de pagamento encontrado</option>`)
        alert("Esta empresa não possui tipos de pagamentos cadastrados.")
    }
}

async function buscarPagamentos() {
    pagamentos = await $.ajax({
        url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_tipoPagamentoCRM') ?>`,
        type: "POST",
        success: function(response) {
            return response
        },
        error: function(error) {
            return false
        },
    })
    return pagamentos
}

//################## fim do tipo_pagamento

//################## popular select do tipo de veículo

async function povoarVeiculos() {
    $("#tipoVeiculo").empty()
    $("#tipoVeiculo").prepend(
        `<option value="0">Buscando tipos de veículos...</option>`)
    $("#tipoVeiculo").select2({
        width: '100%',
        placeholder: "Buscando os tipos de veículos disponíveis...",
        allowClear: true
    })
    let veiculos = await buscarVeiculos()
    if (veiculos) veiculos = JSON.parse(veiculos)

    if (veiculos?.length) {
        veiculos.forEach(veiculo => {

            $("#tipoVeiculo").append(
                `<option value="${veiculo.tz_tipo_veiculoid}">${veiculo.tz_name}</option>`)

            $("#tipoVeiculo").find('option').get(0).remove()
            $("#tipoVeiculo").prepend(
                `<option selected disabled value="">Selecione um tipo de veículo</option>`)
        })
    } else {
        $("#tipoVeiculo").find('option').get(0).remove()
        $("#tipoVeiculo").prepend(`<option selected disabled value="">Nenhum tipo de veículo encontrado</option>`)
        alert("Esta empresa não possui tipos de veículos cadastrados.")
    }
}

async function povoarVeiculosEditar() {
    $("#tipoVeiculoEditar").empty()
    $("#tipoVeiculoEditar").prepend(
        `<option value="0">Buscando tipos de veículos...</option>`)
    $("#tipoVeiculoEditar").select2({
        width: '100%',
        placeholder: "Buscando os tipos de veículos disponíveis...",
        allowClear: true
    })
    let veiculos = await buscarVeiculos()
    if (veiculos) veiculos = JSON.parse(veiculos)

    if (veiculos?.length) {
        veiculos.forEach(veiculo => {

            $("#tipoVeiculoEditar").append(
                `<option value="${veiculo.tz_tipo_veiculoid}">${veiculo.tz_name}</option>`)

            $("#tipoVeiculoEditar").find('option').get(0).remove()
            $("#tipoVeiculoEditar").prepend(
                `<option selected disabled value="">Selecione um tipo de veículo</option>`)
        })
    } else {
        $("#tipoVeiculoEditar").find('option').get(0).remove()
        $("#tipoVeiculoEditar").prepend(`<option selected disabled value="">Nenhum tipo de veículo encontrado</option>`)
        alert("Esta empresa não possui tipos de veículos cadastrados.")
    }
}

async function buscarVeiculos() {
    veiculos = await $.ajax({
        url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_tipoVeiculoCRM') ?>`,
        type: "POST",
        success: function(response) {
            return response
        },
        error: function(error) {
            return false
        },
    })
    return veiculos
}

$('#ddd-erp-pj-oportunidade').mask('00');
$('#ddd-erp-pj-oportunidade2').mask('00');
$('#ddd-erp-pf-oportunidade').mask('00');
$('#ddd-erp-pf-oportunidade2').mask('00');
$('#telefone-erp-pj-oportunidade').mask('00000-0000');
$('#telefone-erp-pj-oportunidade2').mask('00000-0000');
$('#telefone-erp-pf-oportunidade').mask('00000-0000');
$('#telefone-erp-pf-oportunidade2').mask('00000-0000');

//################## fim do tipoVeiculo

//################## popular select de plano satelital
async function povoarPlanos() {
    $("#planoSatelital").empty()
    $("#planoSatelital").prepend(
        `<option value="0">Buscando tipos de planos...</option>`)
    $("#planoSatelital").select2({
        width: '100%',
        placeholder: "Selecione um tipo de plano",
        allowClear: true
    })
    let planos = await buscarPlanos()
    if (planos) planos = JSON.parse(planos)

    if (planos?.length) {
        planos.forEach(plano => {

            $("#planoSatelital").append(
                `<option value="${plano.tz_plano_satelitalid}">${plano.tz_name}</option>`)

            $("#planoSatelital").find('option').get(0).remove()
            $("#planoSatelital").prepend(
                `<option selected disabled value="">Selecione um tipo de plano</option>`)
        })
    } else {
        $("#planoSatelital").find('option').get(0).remove()
        $("#planoSatelital").prepend(`<option selected disabled value="">Nenhum tipo de plano encontrado</option>`)
       
    }
}

async function povoarPlanosEditar() {
    $("#planoSatelitalEditar").empty()
    $("#planoSatelitalEditar").prepend(
        `<option value="0">Buscando tipos de planos...</option>`)
    $("#planoSatelitalEditar").select2({
        width: '100%',
        placeholder: "Selecione um tipo de plano",
        allowClear: true
    })
    let planos = await buscarPlanosEditar()
    if (planos) planos = JSON.parse(planos)

    if (planos?.length) {
        planos.forEach(plano => {

            $("#planoSatelitalEditar").append(
                `<option value="${plano.tz_plano_satelitalid}">${plano.tz_name}</option>`)

            $("#planoSatelitalEditar").find('option').get(0).remove()
            $("#planoSatelitalEditar").prepend(
                `<option selected disabled value="">Selecione um tipo de plano</option>`)
        })
    } else {
        $("#planoSatelitalEditar").find('option').get(0).remove()
        $("#planoSatelitalEditar").prepend(`<option selected disabled value="">Nenhum tipo de plano encontrado</option>`)
       
    }
}

async function povoarArmazens() {
    $('#armazem').empty();
    $('#armazem').prepend(
        `<option value="0">Buscando armazéns...</option>`)
    $('#armazem').select2({
        width: '100%',
        placeholder: "Buscando armazéns disponíveis...",
        allowClear: true
    })
    let armazens = await buscarAlmoxarifados()
    if (armazens) armazens = JSON.parse(armazens)

    if (armazens?.length) {
        armazens.forEach(armazem => {
            $('#armazem').append(
                `<option value="${armazem.tz_armazemid}">${armazem.tz_name}</option>`)
            $('#armazem').find('option').get(0).remove()
            $('#armazem').prepend(
                `<option selected disabled value="">Selecione um armazém</option>`)
        })
    } else {
        $('#armazem').find('option').get(0).remove()
        $('#armazem').prepend(
            `<option selected disabled value="">Nenhum armazém encontrado</option>`)
        alert("Esta empresa não possui armazéns cadastrados.")
    }
}

async function povoarArmazensEditar() {
    $('#armazemEditar').empty();
    $('#armazemEditar').prepend(
        `<option value="0">Buscando armazéns...</option>`)
    $('#armazemEditar').select2({
        width: '100%',
        placeholder: "Buscando armazéns disponíveis...",
        allowClear: true
    })
    let armazens = await buscarAlmoxarifados()
    if (armazens) armazens = JSON.parse(armazens)

    if (armazens?.length) {
        armazens.forEach(armazem => {
            $('#armazemEditar').append(
                `<option value="${armazem.tz_armazemid}">${armazem.tz_name}</option>`)
            $('#armazemEditar').find('option').get(0).remove()
            $('#armazemEditar').prepend(
                `<option selected disabled value="">Selecione um armazém</option>`)
        })
    } else {
        $('#armazemEditar').find('option').get(0).remove()
        $('#armazemEditar').prepend(
            `<option selected disabled value="">Nenhum armazém encontrado</option>`)
        alert("Esta empresa não possui armazéns cadastrados.")
    }
}

async function buscarAlmoxarifados() {
    armazens = await $.ajax({
        url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_armazensCRM') ?>`,
        type: "POST",
        success: function(response) {
            return response
        },
        error: function(error) {
            return false
        },
    })
    return armazens

}

async function buscarPlanos() {
    var valor = $('#ID_Produto').find(':selected').attr('data-produto');
    if ( $('#ID_Produto').val()== 0) {
        return false;
    }
    planos = await $.ajax({
        url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_planoSatelitalCRM') ?>`+'?value='+valor,
        type: "POST",
        success: function(response) {
            return response
        },
        error: function(error) {
            return false
        },
    })
    return planos
}

async function buscarPlanosEditar() {
    var valor = $('#ID_ProdutoEditar').find(':selected').attr('data-produto');
    if ( $('#ID_ProdutoEditar').val()== 0) {
        return false;
    }
    planos = await $.ajax({
        url: `<?= site_url('ComerciaisTelevendas/Pedidos/pegar_planoSatelitalCRM') ?>`+'?value='+valor,
        type: "POST",
        success: function(response) {
            return response
        },
        error: function(error) {
            return false
        },
    })
    return planos
}
 
//################## fim do planoSatelital

</script>
      
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6/js/i18n/pt-BR.js"></script>

<script>

//################ scripts trazidos de clientes televendas
//se vc chegou até aqui e percebeu uma duplicação, não tenho culpa. foi uma modificação radial =)


$(document).ready(function() {
    $('#dt_table').DataTable({
        "ordering": true,
        "order": [
            [1, "desc"]
        ],
        "columnDefs": [{
            "type": "date-br",
            targets: [1]
        }],
        "language": {
            "searchPlaceholder": "Pesquisar na tabela",
            "decimal": "",
            "emptyTable": "Nenhum registro encontrado",
            "info": "Registro _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "0 Registros",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Exibir: _MENU_",
            "loadingRecords": "Carregando...",
            "processing": "Processando...",
            "search": "Pesquisar: ",
            "zeroRecords": "Nenhum registro encontrado",
            "paginate": {
                "first": "Anterior",
                "last": "Próxima",
                "next": "Próxima",
                "previous": "Anterior"
            }
        },
        bLengthChange: true
    });

});

$('#modalidadeVenda').on('change', function() {
    const valor = $(this).val();

    if (valor == '8') {
        $('#divProduto').hide();
        $('#divPlanoSatelital').hide();
        $('#ID_Produto').attr('required', false);
        $('#planoSatelital').attr('required', false);
        $('#tipoVeiculo').attr('required', false);
        $('#divTipoVeiculo').hide()
        $('#cenario_venda').prop('disabled', false);
        $('#cenario_venda').val(0).trigger('change');
        $('#divTempoContrato').show();
        $('#tempoContrato').attr('required', true);
    }else if (valor == '9'){
        $('#divProduto').show();
        $('#divPlanoSatelital').hide();
        $('#divTipoVeiculo').hide();
        $('#ID_Produto').attr('required', true);
        $('#tipoVeiculo').attr('required', false);
        $('#planoSatelital').attr('required', false);
        $('#cenario_venda').prop('disabled', false);
        $('#cenario_venda').val(0).trigger('change');
        $('#divTempoContrato').hide();
        $('#tempoContrato').attr('required', false);
    }else{
        $('#divProduto').show();
        $('#divPlanoSatelital').show();
        $('#divTipoVeiculo').show();
        $('#ID_Produto').attr('required', true);
        $('#planoSatelital').attr('required', true);
        $('#tipoVeiculo').attr('required', true);
        $('#cenario_venda').prop('disabled', false);
        $('#cenario_venda').val(0).trigger('change');
        $('#divTempoContrato').show();
        $('#tempoContrato').attr('required', true);
    }

    if (valor === '6') {
        $('#divProduto').hide();
        $('#ID_Produto').attr('required', false);
    }
});

$('#modalidadeVendaEditar').on('change', function() {
    const valor = $(this).val();

    if (valor == '8') {
        $('#divProdutoEditar').hide();
        $('#divPlanoSatelitalEditar').hide();
        $('#ID_ProdutoEditar').attr('required', false);
        $('#planoSatelitalEditar').attr('required', false);
        $('#tipoVeiculoEditar').attr('required', false);
        $('#divTipoVeiculoEditar').hide()
        $('#cenario_vendaEditar').prop('disabled', false);
        $('#cenario_vendaEditar').val(0).trigger('change');
    }else if (valor == '9'){
        $('#divProdutoEditar').show();
        $('#divPlanoSatelitalEditar').hide();
        $('#divTipoVeiculoEditar').hide();
        $('#ID_ProdutoEditar').attr('required', true);
        $('#tipoVeiculoEditar').attr('required', false);
        $('#cenario_vendaEditar').find('option').each(function () {
            if ($(this).text() === 'VENDA DIRETA OMNICARGA') {
                $(this).prop('selected', true).trigger('change');
                $('#cenario_vendaEditar').attr('disabled', true);
                $('#cenario_vendaEditar').after('<input type="hidden" name="cenario_venda" value="' + valor + '">');
            }
        });
        $('#planoSatelitalEditar').attr('required', false);
    }else{
        $('#divProdutoEditar').show();
        $('#divPlanoSatelitalEditar').show();
        $('#divTipoVeiculoEditar').show();
        $('#ID_ProdutoEditar').attr('required', true);
        $('#planoSatelitalEditar').attr('required', true);
        $('#tipoVeiculoEditar').attr('required', true);
        $('#cenario_vendaEditar').prop('disabled', false);
        $('#cenario_vendaEditar').val(0).trigger('change');
    }

    if (valor === '6') {
        $('#divProdutoEditar').hide();
        $('#ID_ProdutoEditar').attr('required', false);
    }
});

var tabelaItensContratoVenda = $('#tabelaItensContratoVenda').DataTable({
    responsive: true,
	ordering: false,
	paging: true,
	searching: true,
	info: true,
	language: lang.datatable,
	deferRender: true,
	lengthChange: false,
    columns: [
        { data: "nome"},
        { data: "cliente"},
        { data: "veiculo"},
        { data: "numSerieModuloPrincipal"},
        { data: "plano"},
        { data: "rastreador"},
        { data: "statusItemContrato"},
        { data: "dataCriacao",
            render: function (data, type, row) {
                return data ? moment(data).format('DD/MM/YYYY') : '';
            } },
        { 
            data: {'nome': 'nome'},
            orderable: false,
            render: function (data, type, row) {
                return `
                <button
                    class="btn btn-danger"
                    title="Dissociar"
                    id="btnDissociarItem"
                    onClick="javascript:dissociarItemContratoVenda(this,'${data['itemContratoVendaId']}', event)">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </button>`;
            }
        }
    ],
    columnDefs:[
        {
            targets: [8],
            className: 'text-center',
        }
    ],
})

var tabelaRazaoValidacao = $('#tabelaRazaoValidacao').DataTable({
    responsive: true,
	ordering: false,
	paging: true,
	searching: true,
	info: true,
    dom: '<"top length-selector"l><"top button-section"B>frtip',
    language: {
                loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
                searchPlaceholder: 'Pesquisar',
                emptyTable: "Nenhuma razão de validação encontrada.",
                info: "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
                infoEmpty: "Mostrando 0 até 0 de 0 resultados.",
                zeroRecords: "Nenhum resultado encontrado.",
                paginate: {
                    first: "Primeira",
                    last: "Última",
                    next: "Próxima",
                    previous: "Anterior"
                },
            },
	deferRender: true,
	lengthChange: false,
    columns: [
        { data: "tz_name"},
        { data: "tz_situacao",
            render: function (data, type, row) {
                switch (data) {
                    case 1:
                        return 'EM ANÁLISE';
                    case 2:
                        return 'APROVADO';
                    case 3:
                        return 'REPROVADO';
                    case 4:
                        return 'PENDÊNCIA VENDEDOR';
                    case 5: 
                        return 'APROVADO COM EXCEÇÃO';
                }
            } },
        { data: "tz_descricao"},
        { data: "tz_observacao"},
    ],
    buttons: [
                        {
                    extend: 'excelHtml5',
                    filename: 'Razões de Validação',
                    title: 'Razões de Validação',
                    exportOptions: {
                        columns: ':visible'
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-excel-o"></i> Exportar para Excel',
                    customizeData: function(data) {
                        for(var i = 0; i < data.body.length; i++) {
                            data.body[i][0] = '\u200C' + data.body[i][0]; 
                            data.body[i][1] = '\u200C' + data.body[i][1]; 
                            data.body[i][2] = '\u200C' + data.body[i][2];
                            data.body[i][3] = '\u200C' + data.body[i][3];
                        }
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];

                        $('row c[r^="A"]', sheet).attr('s', '0');
                        $('row c[r^="B"]', sheet).attr('s', '0');
                        $('row c[r^="C"]', sheet).attr('s', '0');
                        $('row c[r^="D"]', sheet).attr('s', '0');
                    }
                },
                {
                    extend: 'pdfHtml5',
                    filename: 'Razões de Validação',
                    exportOptions: {
                        columns: ':visible'
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-file-pdf-o"></i> Exportar para PDF',
                    customize: function(document) {
                        var titulo = "Razões de Validação";
                        pdfTemplateIsolated(document, titulo)
                    }
                },
                {
                    extend: 'print',
                    filename: 'Razões de Validação',
                    exportOptions: {
                        columns: ':visible'
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'btn btn-primary',
                    text: '<i class="fa fa-print"></i> Imprimir',
                    customize: function(window) {
                        var titulo = "Razões de Validação";
                        printTemplateImpressao(window, titulo);
                    }
                }
            ]
})

async function abrirModalRazaoValicacao(botao, idCotacao){
    $('.feedback-alert').html('');
    document.getElementById('loading').style.display = 'block';

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/listarRazaoValidacao') ?>",
        type: "POST",
        data: {
            idCotacao: idCotacao,
        },
        success: async function(data) {
            let datajson = JSON.parse(data);
            if (datajson.value) {
                tabelaRazaoValidacao.clear().draw();
                tabelaRazaoValidacao.rows.add(datajson.value).draw();
                $('#modalRazaoValidacoes').modal('show');
                document.getElementById('loading').style.display = 'none';
            }else{
                alert('Ocorreu um erro ao listar as razões de validação. Tente novamente.');
                tabelaItensContratoVenda.clear().draw();
                document.getElementById('loading').style.display = 'none';
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ocorreu um erro ao listar as razões de validação. Tente novamente.');
            document.getElementById('loading').style.display = 'none';
        },
    })
}

async function abrirModalItensContratoDeVenda(botao, idCliente, idCotacao){
    $('.feedback-alert').html('');
    var idTipoVeiculoCotacao = '';
    $('#btnAdicionarItemContratoVenda').data('id', idCliente)
    $('#btnAdicionarItemContratoVenda').data('idCotacao', idCotacao)

    document.getElementById('loading').style.display = 'block';

    await $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/resumoCotacao') ?>",
        type: "POST",
        data: {
            idCotacao: idCotacao
        },
        success: async function(data) {
            let datajson = JSON.parse(data);

            if (datajson['Status'] == 200 && datajson['resumo']){
                let resumo = datajson['resumo'];

                idTipoVeiculoCotacao = resumo?.composicao?.tz_tipo_veiculo_cotacaoid ? resumo.composicao.tz_tipo_veiculo_cotacaoid : '';
                $('#btnAdicionarItemContratoVenda').data('idTipoVeiculo', idTipoVeiculoCotacao)
                await listaItensContratoVenda(idTipoVeiculoCotacao);
                $('#modalItensContratoVenda').modal('show');
            }else{
                alert('Ocorreu um erro ao listar os itens de contrato de venda. Tente novamente.');
                tabelaItensContratoVenda.clear().draw();
                document.getElementById('loading').style.display = 'none';
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ocorreu um erro ao listar os itens de contrato de venda. Tente novamente.');
            document.getElementById('loading').style.display = 'none';
        },
    });
}

$('#btnAdicionarItemContratoVenda').click(async function() {
    $('.feedback-alert').html('');
    var idCliente = $(this).data('id');
    document.getElementById('loading').style.display = 'block';
    await $.ajax({
        url: `<?php echo site_url('ComerciaisTelevendas/Pedidos/getContratosRelacionar') ?>`,
        type: "GET",
        dataType: "json",
        data: {
            idCliente: idCliente
        },
        success: function (response){
            if (response.status === 200){
                if (response?.data?.length){
                    tabelaContratosRelacionar.clear().draw();
                    tabelaContratosRelacionar.rows.add(response.data).draw();
                    $('#modalContratosRelacionar').modal('show');
                }else{
                    alert('Não há contratos para associar.');
                }
            }else{
                alert('Ocorreu um erro ao listar os contratos. Tente novamente.');
            }
        },
        error: function (response) {
            alert('Ocorreu um erro ao listar os contratos. Tente novamente.');
        },
        complete: function() {
            document.getElementById('loading').style.display = 'none';
        }
    });
    
    
});

var tabelaContratosRelacionar = $('#tabelaContratosRelacionar').DataTable({
        responsive: true,
        ordering: false,
        paging: true,
        searching: true,
        info: true,
        language: lang.datatable,
        deferRender: true,
        lengthChange: false,
        columns: [
            {
                data: "itemContratoVendaId",
                orderable: false,

                render: function(data, type, row, meta) {
                    return `<input type="checkbox" data-row="${row.id}" data-id="${data}" id="checkContrato" class="checksClass">`;
                }
            },
            { data: 'nome'},
            { data: 'cliente'},
            { data: 'veiculo'},
            { data: 'numSerieModuloPrincipal'},
            { data: 'plano'},
            { data: 'rastreador'},
            { data: 'statusItemContrato'},
            { data: 'dataCriacao',
                render: function (data, type, row) {
                    return data ? moment(data).format('DD/MM/YYYY') : '';
                } 
            }
        ],
        columnDefs:[
            {
                targets: [0],
                className: 'text-center'
            }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Adicionar',
                className: 'btn btn-primary',
                action: function (){

                    var idCotacao = $('#btnAdicionarItemContratoVenda').data('idCotacao');
                    var contratos = [];
                    tabelaContratosRelacionar.rows().every(function(){
                        var row = this.node();
                        var check = $(row).find('input[type="checkbox"]');
                        if (check.prop('checked')){
                            contratos.push(check.data('id'));
                        }
                    })
                    if (contratos.length > 0){
                        document.getElementById('loading').style.display = 'block';
                        $.ajax({
                            url: `<?php echo site_url('ComerciaisTelevendas/Pedidos/associarDissociarContratosComposicaoCotacao') ?>`,
                            type: "POST",
                            data: {
                                idCotacao: idCotacao,
                                associar: "true",
                                contratos: contratos
                            },
                            success: function (response){
                                response = JSON.parse(response);
                                if (response.status === 200){
                                    alert('Item(s) de contrato de venda associado(s) com sucesso!');
                                    $('#modalContratosRelacionar').modal('hide');
                                    listaItensContratoVenda($('#btnAdicionarItemContratoVenda').data('idTipoVeiculo'));
                                }else if (response.status === 400){
                                    alert('Item(s) selecionado(s) já associado. Verifique o(s) item(s) selecionado(s) e tente novamente!');
                                }else{
                                    alert('Erro ao associar item(s) de contrato de venda. Tente novamente!');
                                }
                            },
                            error: function (response) {
                                alert('Erro ao associar item(s) de contrato de venda. Tente novamente!');
                            },
                            complete: function() {
                                document.getElementById('loading').style.display = 'none';
                            }
                        });
                    }else{
                        alert('Selecione, pelo menos, um item de contrato de venda para associar!');
                    }
                }
            }
        ]
    });

    function dissociarItemContratoVenda(botao, idContratoVenda, event){
        var idCotacao = $('#btnAdicionarItemContratoVenda').data('idCotacao');
        var botao = $(botao);
        if (confirm('Deseja dissociar este item de contrato de venda?')) {
            event.preventDefault();
            document.getElementById('loading').style.display = 'block';
            $.ajax({
                url: `<?php echo site_url('ComerciaisTelevendas/Pedidos/associarDissociarContratosComposicaoCotacao') ?>`,
                type: "POST",
                data: {
                    idCotacao: idCotacao,
                    associar: "false",
                    contratos: idContratoVenda
                },
                success: function (response){
                    response = JSON.parse(response);
                    if (response.status === 200){
                        alert('Item de contrato de venda dissociado com sucesso!');
                        tabelaItensContratoVenda.row(botao.parents('tr')).remove().draw();
                    }else{
                        alert('Erro ao dissociar item de contrato de venda. Tente novamente!');
                    }
                },
                error: function (response) {
                    alert('Erro ao dissociar item de contrato de venda. Tente novamente!');
                },
                complete: function() {
                    document.getElementById('loading').style.display = 'none';
                }
            });
        }else{
            return false;
        }
    }

    $('#modalContratosRelacionar').on('hidden.bs.modal', function (e) {
        $('#modalItensContratoVenda').css('overflow-y', 'scroll');
        $('#checkTodos').prop('checked', false);
    });

    $('#checkTodos').click(function(){

        for (var i = 1; i <= tabelaContratosRelacionar.page.info().pages; i++) {
            tabelaContratosRelacionar.page(i).draw('page');
        }

        tabelaContratosRelacionar.rows().every(function () {
            var row = this.node();
            var check = $(row).find('input[type="checkbox"]');
            if ($('#checkTodos').prop('checked')){
                check.prop('checked', true);
            }else{
                check.prop('checked', false);
            }    
        })
    });

    $('#tabelaContratosRelacionar').on('click', '.checksClass', function() {
        var todosCheck = true;
        if (!$(this).prop('checked')){
            $('#checkTodos').prop('checked', false);
        }
        tabelaContratosRelacionar.rows().every(function () {
            var row = this.node();
            var check = $(row).find('input[type="checkbox"]');
            
            if (!check.prop('checked')){
                todosCheck = false;
            }
        })

        if (todosCheck){
            $('#checkTodos').prop('checked', true);
        }
    });

    function listaItensContratoVenda(idTipoVeiculoCotacao){
        $('.feedback-alert').html('');
        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/getItensContratoVenda') ?>",
            type: "POST",
            data: {
                idTipoVeiculo: idTipoVeiculoCotacao,
            },
            success: function(data) {
                data = JSON.parse(data);
                dados = data.data;
                if (data.status == 200 && dados) {
                    if (dados.length > 0) {
                        tabelaItensContratoVenda.clear().draw();
                        tabelaItensContratoVenda.rows.add(dados).draw();
                    }else {
                        alert('Não existem itens de contrato associados para esta cotação.');
                        tabelaItensContratoVenda.clear().draw();
                    }
                }else{
                    alert('Não foi possível listar os itens de contrato de venda. Verifique os dados e tente novamente.');
                    tabelaItensContratoVenda.clear().draw();
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert('Ocorreu um erro ao listar os itens de contrato de venda. Tente novamente.');
                tabelaItensContratoVenda.clear().draw();
            },
            complete: function() {
                document.getElementById('loading').style.display = 'none';
            }
        })
    }

    $('#cenario_venda').on('change', function (){
        let cenarioVenda = $(this).find('option:selected').text();
        let nomeProduto = $("#ID_Produto").find('option:selected').text();
        let codProduto = $("#ID_Produto").val();

        if (!(nomeProduto.includes('RASTREADOR OMNIFROTA') || nomeProduto.includes('OMNICARGA') || codProduto == '4100.0209.00')){
        
            if (cenarioVenda == 'VENDA DIRETA OMNIFROTA'){
                $('#planoSatelital').attr('required', false);
                $('#planoSatelital').val(null).trigger('change');
                $('#planoSatelital').prop('disabled', true);
            }else if (cenarioVenda == 'VENDA DIRETA OMNICARGA'){
                $('#planoSatelital').attr('required', false);
                $('#planoSatelital').val(null).trigger('change');
                $('#planoSatelital').prop('disabled', true);
            }else if($('#modalidadeVenda').val() != '8'){
                $('#planoSatelital').attr('required', true);
                $('#planoSatelital').prop('disabled', false);
            }
        }else{
            $('#planoSatelital').attr('required', false);
            $('#planoSatelital').val(null).trigger('change');
            $('#planoSatelital').prop('disabled', true);
        }
    })

    $('#cenario_vendaEditar').on('change', function (){
        let cenarioVenda = $(this).find('option:selected').text();
        let nomeProduto = $("#ID_ProdutoEditar").find('option:selected').text();
        let codProduto = $("#ID_ProdutoEditar").val();

        if (!(nomeProduto.includes('RASTREADOR OMNIFROTA') || nomeProduto.includes('OMNICARGA') || codProduto == '4100.0209.00')){
        
            if (cenarioVenda == 'VENDA DIRETA OMNIFROTA'){
                $('#planoSatelitalEditar').attr('required', false);
                $('#planoSatelitalEditar').val(null).trigger('change');
                $('#planoSatelitalEditar').prop('disabled', true);
            }else if (cenarioVenda == 'VENDA DIRETA OMNICARGA'){
                $('#planoSatelitalEditar').attr('required', false);
                $('#planoSatelitalEditar').val(null).trigger('change');
                $('#planoSatelitalEditar').prop('disabled', true);
            }else if($('#modalidadeVenda').val() != '8'){
                $('#planoSatelitalEditar').attr('required', true);
                $('#planoSatelitalEditar').prop('disabled', false);
            }
        }else{
            $('#planoSatelitalEditar').attr('required', false);
            $('#planoSatelitalEditar').val(null).trigger('change');
            $('#planoSatelitalEditar').prop('disabled', true);
        }
    })



async function abrirDetalhamentoFrota(botao, idCotacao){
    $('.feedback-alert').html('');
    var idTipoVeiculoCotacao = '';
    document.getElementById('loading').style.display = 'block';

    await $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/resumoCotacao') ?>",
        type: "POST",
        data: {
            idCotacao: idCotacao
        },
        success: async function(data) {
            let datajson = JSON.parse(data);
            if (datajson['Status'] == 200 && datajson['resumo']){
                let resumo = datajson['resumo'];
                if (datajson['resumo']['modalidadeVenda'] == "Reativação"){
                    idTipoVeiculoCotacao = resumo?.composicao?.tz_tipo_veiculo_cotacaoid ? resumo.composicao.tz_tipo_veiculo_cotacaoid : '';
                    await listaDetalheFrota(idTipoVeiculoCotacao);
                }else{
                    alert('Cotação não pertence a modalidade de venda reativação.');
                    document.getElementById('loading').style.display = 'none';
                }
            }else{
                alert('Ocorreu um erro ao listar o detalhamento da frota. Tente novamente.');
                tabelaItensContratoVenda.clear().draw();
                document.getElementById('loading').style.display = 'none';
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ocorreu um erro ao listar o detalhamento da frota. Tente novamente.');
            document.getElementById('loading').style.display = 'none';
        },
    });
    $('#btnAbrirAdicionarDetalhamentoFrota').data('idComposicao', idTipoVeiculoCotacao);
    
}

function listaDetalheFrota(idTipoVeiculoCotacao){
    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/listarDetalhamentoFrota') ?>",
        type: "POST",
        data: {
            idTipoVeiculo: idTipoVeiculoCotacao,
        },
        success: function(data) {
            data = JSON.parse(data);
            dados = data.data;
            if (data.status == 200 && dados) {
                if (dados.length > 0) {
                    tabelaDetalhamentoFrota.clear().draw();
                    tabelaDetalhamentoFrota.rows.add(dados).draw();
                    $('#modalDetalhamentoFrota').modal('show');
                }else {
                    alert('Não existem dados a serem listados.');
                    tabelaDetalhamentoFrota.clear().draw();
                    $('#modalDetalhamentoFrota').modal('show');
                }
            }else{
                alert('Não foi possível listar os dados. Tente novamente.');
                tabelaDetalhamentoFrota.clear().draw();
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ocorreu um erro ao listar os dados. Tente novamente.');
            tabelaDetalhamentoFrota.clear().draw();
            document.getElementById('loading').style.display = 'none';
        },
        complete: function() {
            document.getElementById('loading').style.display = 'none';
        }
    })
}

var tabelaDetalhamentoFrota = $('#tabelaDetalhamentoFrota').DataTable({
    responsive: true,
	ordering: false,
	paging: true,
	searching: true,
	info: true,
	language: lang.datatable,
	deferRender: true,
	lengthChange: false,
    columns: [
        { data: "tz_name"},
        { data: "numeroSerieRastreador"},
        { data: "numeroSerieAntenaSatelital"},
        { data: "quantidade"},
        { data: {"tz_detalhamento_frotaid" : "tz_detalhamento_frotaid"},
            render: function (data) {
                return `
                    <button
                        id="btnRemoverItemDetalhamentoFrota"
                        class="btn btn-danger"
                        title="Remover Detalhamento da Frota"
                        onClick="javascript:removerDetalhamentoFrota(this,'${data['tz_detalhamento_frotaid']}', event)">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                    `;
            }
        }
    ],
})

function removerDetalhamentoFrota(botao, idDetalhamentoFrota, event){
    event.preventDefault();
    $('.feedback-alert').html('');
    
    if (confirm('Deseja realmente remover este detalhamento da frota?')){
        document.getElementById('loading').style.display = 'block';

        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/removerDetalhamentoFrota') ?>",
            type: "POST",
            data: {
                idDetalhamentoFrota: idDetalhamentoFrota,
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 204) {
                    alert('Detalhamento da frota removido com sucesso!');
                    tabelaDetalhamentoFrota.row(botao.parentNode.parentNode).remove().draw();
                }else{
                    alert('Não foi possível remover o detalhamento da frota. Tente novamente.');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert('Ocorreu um erro ao remover o detalhamento da frota. Tente novamente.');
                document.getElementById('loading').style.display = 'none';
                
            },
            complete: function() {
                document.getElementById('loading').style.display = 'none';
            }
        })
    }else{
        return false;
    }
}

$('#btnAbrirAdicionarDetalhamentoFrota').click(function() {
    $('.feedback-alert').html('');
    $('#modalAddDetalhamentoFrota').modal('show');
});

$('#formAddDetalhamentoFrota').submit(function(event) {
    event.preventDefault();

    document.getElementById('loading').style.display = 'block';

    let idComposicaoCotacao = $('#btnAbrirAdicionarDetalhamentoFrota').data('idComposicao');
    let numeroSerieRastreador = $('#numeroSerieRastreador').val();
    let numeroSerieAntenaSatelital = $('#numeroSerieAntenaSatelital').val();
    let emailVendedor = $('#emailVendedorAddDetalhamento').val();    

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/addDetalhamentoFrota') ?>",
        type: "POST",
        data: {
            idComposicaoCotacao: idComposicaoCotacao,
            numeroSerieRastreador: numeroSerieRastreador,
            numeroSerieAntenaSatelital: numeroSerieAntenaSatelital,
            emailVendedor: emailVendedor,
        },
        success: function(data) {
            data = JSON.parse(data);
            if (data.status == 200) {
                alert('Detalhamento da frota adicionado com sucesso!');
                $('#modalAddDetalhamentoFrota').modal('hide');
                listaDetalheFrota(idComposicaoCotacao);
            }else{
                alert(data?.data?.Message ? data.data.Message : 'Não foi possível adicionar o detalhamento da frota. Tente novamente.');
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ocorreu um erro ao adicionar o detalhamento da frota. Tente novamente.');
            document.getElementById('loading').style.display = 'none';
            
        },
        complete: function() {
            document.getElementById('loading').style.display = 'none';
        }
    })
});

$('#modalAddDetalhamentoFrota').on('hidden.bs.modal', function () {
    $('#numeroSerieRastreador').val('');
    $('#numeroSerieAntenaSatelital').val('');
});

function revisarCotacao(botao, idCotacao){
    $('.feedback-alert').html('');
    let atualizaTabela = false;
    if (confirm('Deseja realmente revisar esta cotação?')){
        document.getElementById('loading').style.display = 'block';

        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/acaoBotaoCotacao') ?>",
            type: "POST",
            data: {
                idCotacao: idCotacao,
                action: 2,
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.Status == 200) {
                    alert(data.Message);
                    BuscarOportunidadesVendedor();
                    atualizaTabela = true;
                }else if (data.Status == 400){
                    alert(data.Message ?? "Não é possível revisar esta cotação!")
                }else{
                    alert('Não foi possível revisar a cotação. Tente novamente.');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert('Ocorreu um erro ao revisar a cotação. Tente novamente.');
                document.getElementById('loading').style.display = 'none';
                
            },
            complete: function() {
                if (!atualizaTabela){
                    document.getElementById('loading').style.display = 'none';
                }
            }
        })
    }else{
        return false;
    }
}

$('#copiarDadosClienteSoftware').on('click', function() {
    var btn = $('#btnEditar');
    var btnCopiar = $(this);
    var idCliente = btn.data('idCliente');
    var tipoCliente = btn.data('tipoCliente');

    btnCopiar.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando dados...');

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/listarDadosCliente') ?>",
        type: "POST",
        dataType: "json",
        data: {
            idCliente: idCliente,
            tipoCliente: tipoCliente,
        },
        success: function(data) {
            if (data.cliente.status == 200) {
                $('#nomeClienteSoftwareOportunidade').val(data.cliente.nome);
                $('#emailClienteSoftwareOportunidade').val(data.cliente.email);
                $('#documentoClienteSoftwareOportunidade').val(data.cliente.documento);

                $('.inputSoftware').addClass('highlight').one('transitionend', function() {
                    $(this).removeClass('highlight');
                });
            }else{
                alert('Dados do cliente não encontrados.')
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ocorreu um erro ao copiar os dados do cliente. Tente novamente.');
            btnCopiar.prop('disabled', false).html('<i class="fa fa-copy"></i> Copiar Dados do Cliente');
        },
        complete: function() {
            btnCopiar.prop('disabled', false).html('<i class="fa fa-copy"></i> Copiar Dados do Cliente');
        }
    })
});

$('#copiarDadosSignatarioSoftware').click(function() {
    var btn = $('#btnEditar');
    var btnCopiar = $(this);
    var idCliente = btn.data('idCliente');
    var tipoCliente = btn.data('tipoCliente');

    btnCopiar.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando dados...');

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/listarDadosCliente') ?>",
        type: "POST",
        dataType: "json",
        data: {
            idCliente: idCliente,
            tipoCliente: tipoCliente,
        },
        success: function(data) {
            if (data.signatario.status == 200) {
                $('#nomeClienteSoftwareOportunidade').val(data.signatario.nome);
                $('#emailClienteSoftwareOportunidade').val(data.signatario.email);
                $('#documentoClienteSoftwareOportunidade').val(data.signatario.documento);

                $('.inputSoftware').addClass('highlight').one('transitionend', function() {
                    $(this).removeClass('highlight');
                });
            }else{
                alert('Dados do signatário não encontrados.')
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ocorreu um erro ao copiar os dados do signatário. Tente novamente.');
            btnCopiar.prop('disabled', false).html('<i class="fa fa-copy"></i> Copiar Dados do Signatário');
        },
        complete: function() {
            btnCopiar.prop('disabled', false).html('<i class="fa fa-copy"></i> Copiar Dados do Signatário');
        }
    })
});

$('#copiarDadosClienteHardware').click(function() {
    var btn = $('#btnEditar');
    var btnCopiar = $(this);
    var idCliente = btn.data('idCliente');
    var tipoCliente = btn.data('tipoCliente');

    btnCopiar.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando dados...');

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/listarDadosCliente') ?>",
        type: "POST",
        dataType: "json",
        data: {
            idCliente: idCliente,
            tipoCliente: tipoCliente,
        },
        success: function(data) {
            if (data.cliente.status == 200) {
                $('#nomeClienteHardwareOportunidade').val(data.cliente.nome);
                $('#emailClienteHardwareOportunidade').val(data.cliente.email);
                $('#documentoClienteHardwareOportunidade').val(data.cliente.documento);

                $('.inputHardware').addClass('highlight').one('transitionend', function() {
                    $(this).removeClass('highlight');
                });
            }else{
                alert('Dados do cliente não encontrados.')
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ocorreu um erro ao copiar os dados do cliente. Tente novamente.');
            btnCopiar.prop('disabled', false).html('<i class="fa fa-copy"></i> Copiar Dados do Cliente');
        },
        complete: function() {
            btnCopiar.prop('disabled', false).html('<i class="fa fa-copy"></i> Copiar Dados do Cliente');
        }
    })
});

$('#copiarDadosSignatarioHardware').click(function() {
    var btn = $('#btnEditar');
    var btnCopiar = $(this);
    var idCliente = btn.data('idCliente');
    var tipoCliente = btn.data('tipoCliente');

    btnCopiar.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando dados...');

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/listarDadosCliente') ?>",
        type: "POST",
        dataType: "json",
        data: {
            idCliente: idCliente,
            tipoCliente: tipoCliente,
        },
        success: function(data) {
            if (data.signatario.status == 200) {
                $('#nomeClienteHardwareOportunidade').val(data.signatario.nome);
                $('#emailClienteHardwareOportunidade').val(data.signatario.email);
                $('#documentoClienteHardwareOportunidade').val(data.signatario.documento);

                $('.inputHardware').addClass('highlight').one('transitionend', function() {
                    $(this).removeClass('highlight');
                });
            }else{
                alert('Dados do signatário não encontrados.')
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ocorreu um erro ao copiar os dados do signatário. Tente novamente.');
            btnCopiar.prop('disabled', false).html('<i class="fa fa-copy"></i> Copiar Dados do Signatário');
        },
        complete: function() {
            btnCopiar.prop('disabled', false).html('<i class="fa fa-copy"></i> Copiar Dados do Signatário');
        }
    })
});

async function perderCotacao(botao, idCotacao){
    $('.feedback-alert').html('');
    $('#btnSalvarPerderOportunidade').data('idCotacao', idCotacao);
    let btn = $(botao);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    let competidores  = await $.ajax ({
                            url: '<?= site_url('ComerciaisTelevendas/Pedidos/listarCompetidores') ?>',
                            dataType: 'json',
                            delay: 1000,
                            type: 'GET',
                            data: function (params) {
                                return {
                                    q: params.term
                                };
                            },
                            error: function(){
                                alert('Erro ao buscar concorrentes, tente novamente');
                            }
                        });

    $('#competidores').select2({
        data: competidores,
        placeholder: "Selecione o concorrente",
        allowClear: true,
        language: "pt-BR",
        width: '100%'
        });
    
    $('#competidores').on('select2:select', function (e) {
        var data = e.params.data;
    });

    btn.prop('disabled', false).html('<i class="fa fa-times"></i> Perder');
    $('#motivoPerda').val('');
    $('#competidores').val('').trigger('change');
    $('#modalPerderOportunidade').modal('show');
}

function removerLinhaTabela(numeroCotacao) {
    const dataTable = $('#dt_tableOportunidade').DataTable();
    dataTable.rows().eq(0).each(function(index) {
        var row = dataTable.row(index);
        var data = row.data();
        if (data && data[0] == numeroCotacao) {
            row.remove().draw();
            return false;
        }
    });
}

async function excluirCotacao(botao, idCotacao, numeroCotacao) {
    $(botao).prop('disabled', true);
    $(botao).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...');

    let atualizaTabela = false;

    if (confirm('Deseja realmente excluir esta cotação?')) {
        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/acaoBotaoCotacao') ?>",
            type: "POST",
            dataType: "json",
            data: {
                idCotacao: idCotacao,
                userNameVendedor: '<?= $_SESSION['emailUsuario'] ?>',
                action: 1
            },
            success: function(data) {
                if (data.Status == 200) {
                    removerLinhaTabela(numeroCotacao);
                    atualizaTabela = true;
                    alert(data.Message);
                } else if (data.Status == 400) {
                    alert(data.Message ?? "Não é possível excluir esta cotação!");
                } else {
                    alert('Não foi possível excluir a cotação. Tente novamente.');
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                alert('Ocorreu um erro ao excluir a cotação. Tente novamente.');
            },
            complete: function() {
                $(botao).prop('disabled', false);
                $(botao).html('<i class="fa fa-ban" aria-hidden="true"></i> Excluir</button>');
                
                document.getElementById('loading').style.display = 'none';

                if (!atualizaTabela) {
                    document.getElementById('loading').style.display = 'none';
                }
            }
        });
    } else {
        $(botao).prop('disabled', false);
        $(botao).html('<i class="fa fa-ban" aria-hidden="true"></i> Excluir</button>');
    }
}

$('#formPerderOportunidade').submit(function(event) {
    event.preventDefault();
    $('.feedback-alert').html('');
    let idCotacao = $('#btnSalvarPerderOportunidade').data('idCotacao');
    let competidor = $('#competidores').val();
    let idMotivoPerda = $('#motivoPerda').val();
    let btn = $('#btnSalvarPerderOportunidade');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Confirmando...');

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/acaoBotaoCotacao') ?>",
        type: "POST",
        data: {
            idCotacao: idCotacao,
            action: 6,
            idCompetidor: competidor,
            motivoPerda: idMotivoPerda,
        },
        success: function(data) {
            data = JSON.parse(data);
            if (data.Status == 200) {
                alert(data.Message);
                $('#modalPerderOportunidade').modal('hide');
                BuscarOportunidadesVendedor();
            }else{
                alert(data.Message ?? "Não é possível perder esta cotação!")
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Ocorreu um erro ao perder a cotação. Tente novamente.');
            btn.prop('disabled', false).html('Confirmar');
        },
        complete: function() {
            btn.prop('disabled', false).html('Confirmar');
        }
    })
});

$('#plataformaCad').on('change', function (){
    let plataforma = $(this).val();
    
    if (plataforma != '' && plataforma != null){
        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/listarCenariosDeVendaPorPlataforma') ?>",
            type: "POST",
            dataType: "json",
            data: {
                idPlataforma: plataforma,
            },
            beforeSend: function() {
                $('#cenario_venda').find('option').get(0).remove();
                $('#cenario_venda').append($('<option>', { 
                    value: 0,
                    text : 'Buscando cenários de venda...' ,
                    selected: true,
                    disabled: true
                
                }));
                $('#cenario_venda').attr('disabled', true);
            },
            success: function(data) {
                if (data.length > 0){
                    $('#cenario_venda').empty();
                    $('#cenario_venda').append($('<option>', { 
                        value: '',
                        text : 'Selecione o cenário de venda' ,
                        selected: true,
                        disabled: true

                    }));
                    $.each(data, function (i, item) {
                        $('#cenario_venda').append($('<option>', { 
                            value: item.id,
                            text : item.text 
                        }));
                    });
                    $('#cenario_venda').attr('disabled', false);
                    $('#planoSatelital').attr('required', true);
                    $('#divPlanoSatelital').show();
                }else{
                    $('#cenario_venda').empty();
                    $('#cenario_venda').append($('<option>', { 
                        value: 0,
                        text : 'Não há cenários de venda para esta plataforma.' ,
                        selected: true,
                        disabled: true

                    }));
                    $('#cenario_venda').attr('disabled', true);
                    $('#planoSatelital').attr('required', false);
                    $('#divPlanoSatelital').hide();
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert('Ocorreu um erro ao listar os cenários de venda. Tente novamente.');
                $('#cenario_venda').empty();
                $('#cenario_venda').append($('<option>', { 
                    value: 0,
                    text : 'Aguardando seleção da plataforma.',
                    selected: true,
                    disabled: true

                }));
                $('#cenario_venda').attr('disabled', true);
            },
        })
    }
})
</script>
