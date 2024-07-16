<style>
    .inputEnd.empty{
        border-color: red !important;
    }
    .dataTables_length{
        position: absolute;
    }
</style>
<div id="loading" style="display: none;">
    <div class="loader"></div>
</div>


<br>

<div class="row">
    <div class="col-md-6 feedback-alert">
        <?php echo $this->session->flashdata('sucesso');?>
        <?php echo $this->session->flashdata('error');?>
        <?php echo $this->session->flashdata('dados');?>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mx-auto">	
        <table class="table table-striped table-bordered table-hover responsive" id="tabelaClientesPorVendedor">
            <thead>
                <tr class="tableheader">
                    <th>Nome</th>
                    <th>Nome Fantasia</th>
                    <th>Documento</th>
                    <th>Data de Nascimento</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Login Vendedor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>   
    </div>
</div>

<div id="modalEditCliente" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            	<form id="formEditCliente">
                	<div class="modal-header" style="border-bottom: 3px solid #03A9F4;">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 class="modal-title">Editar Cliente</h3>
                	</div>
                	<div class="modal-body scrollModal">
						<div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Nome: </label>
                                    <input type="text" class="form-control input-sm" id="nomeClienteEditar" name="name" required>
                                </div>
                                <div id="divSobrenomeCliente" class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">Sobrenome: </label>
                                    <input type="text" class="form-control input-sm" id="sobrenomeClienteEditar" name="sobrenome">
                                </div>
                                <div id="divnomeFantasiaCliente" class="col-md-6 form-group" style="border-left: 3px solid #03A9F4" hidden>
                                    <label class="control-label">Nome Fantasia: </label>
                                    <input type="text" class="form-control input-sm" id="nomeFantasiaClienteEditar" name="nome_fantasia">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                    <label class="control-label">E-mail:</label>
                                    <input type="text" class="form-control input-sm" id="emailClienteEditar" name="email" required>
                                </div>
                                <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                    <label>Login Vendedor:</label>
                                    <input type="text" class="form-control input-sm" id="loginVendedorClienteEditar" name="loginVendedor" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                    <label>Documento:</label>
                                    <input type="text" class="form-control input-sm" id="documentoClienteEditar" name="documento" readonly>
                                </div>
                                <div id="divdataNascimentoCliente" class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                    <label>Data de Nascimento:</label>
                                    <input type="date" class="form-control input-sm" id="dataNascimentoClienteEditar" name="dataNascimento">
                                </div>
                                <div id="divinscricaoEstadualCliente" class="col-md-6 form-group" style="border-left: 3px solid #03A9F4" hidden>
                                    <label class="control-label">Inscrição Estadual:</label>
                                    <input type="text" class="form-control input-sm" id="inscricaoEstadualClienteEditar" name="inscricao_estadual">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-3 form-group" style="border-left: 3px solid #03A9F4">
                                            <label class="control-label">DDD:</label>
                                            <input type="text" class="form-control input-sm ddd" id="dddClienteEditar" name="ddd_telefone" required>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <label class="control-label">Telefone:</label>
                                            <input type="text" class="form-control input-sm fone" id="telefoneClienteEditar" name="telefone" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-3 form-group" style="border-left: 3px solid #03A9F4">
                                            <label>DDD:</label>
                                            <input type="text" class="form-control input-sm ddd" id="dddClienteEditar2" name="ddd_telefone2">
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <label>Telefone:</label>
                                            <input type="text" class="form-control input-sm fone" id="telefoneClienteEditar2" name="telefone2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="divSignatario" class="row" hidden>
                                <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                    <label>Signatário:</label>
                                    <input type="text" class="form-control input-sm" id="signatarioClienteEditar" name="signatario_mei">
                                </div>
                                <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                    <label>Email Signatário:</label>
                                    <input type="text" class="form-control input-sm" id="emailSignatarioClienteEditar" name="email_signatario_mei">
                                </div>
                            </div>
                            <div id="divSignatario2" class="row" hidden>
                                <div class="col-md-6 form-group" style="border-left: 3px solid #03A9F4">
                                    <label>Documento Signatário:</label>
                                    <input type="text" class="form-control input-sm documento" id="documentoSignatarioClienteEditar" name="documento_signatario_mei">
                                </div>
                            </div>
                            <br>
                            <hr>
                            <h4>Endereços</h4>

                            <ul class="nav nav-tabs nav-justified">
                                <li class="nav-item"><a id="tab-endereco-principal" href="" data-toggle="tab" class="nav-link active">Endereço Principal</a></li>
                                <li class="nav-item"><a id="tab-endereco-cobranca" href="" data-toggle="tab" class="nav-link">Endereço Cobrança</a></li>
                                <li class="nav-item"><a id="tab-endereco-entrega" href="" data-toggle="tab" class="nav-link">Endereço Entrega</a></li>                
                            </ul>

                            <div id="divEnderecoPrincipal">
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">CEP: </label>
                                        <input type="text" class="form-control input-sm cep inputEnd" id="cepPrincipalClienteEditar" name="cep_principal">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Número / Complemento:</label>
                                        <input type="text" class="form-control input-sm" id="complementoPrincipalClienteEditar" name="complemento_principal">
                                    </div>
                                </div>
                                <br>
                                <div class="row"> 
                                    <div class="col-md-6">
                                        <label class="control-label">Rua: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="ruaPrincipalClienteEditar" name="rua_principal">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Bairro: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="bairroPrincipalClienteEditar" name="bairro_principal">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Cidade: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="cidadePrincipalClienteEditar" name="cidade_principal">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Estado: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="estadoPrincipalClienteEditar" name="estado_principal">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="help help-block"></span>
                                        <label>Copiar Endereço Cobrança: </label>
                                        <input type="checkbox" id="copiar-endereco-cobranca-principal" name="copiar_endereco_cobranca_principal" value="1">
                                    </div>
                                    <div class="col-md-4">
                                        <span class="help help-block"></span>
                                        <label>Copiar Endereço Entrega: </label>
                                        <input type="checkbox" id="copiar-endereco-entrega-principal" name="copiar_endereco_entrega_principal" value="1">
                                    </div>
                                </div>
                            </div>
                            <div id="divEnderecoCobranca" hidden>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">CEP: </label>
                                        <input type="text" class="form-control input-sm cep inputEnd" id="cepCobrancaClienteEditar" name="cep_cobranca">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Número / Complemento: </label>
                                        <input type="text" class="form-control input-sm" id="complementoCobrancaClienteEditar" name="complemento_cobranca">
                                    </div>                
                                </div>
                                <br>
                                <div class="row"> 
                                    <div class="col-md-6">
                                        <label class="control-label">Rua: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="ruaCobrancaClienteEditar" name="rua_cobranca">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Bairro: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="bairroCobrancaClienteEditar" name="bairro_cobranca">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Cidade: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="cidadeCobrancaClienteEditar" name="cidade_cobranca">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Estado: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="estadoCobrancaClienteEditar" name="estado_cobranca">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="help help-block"></span>
                                        <label>Copiar Endereço Principal: </label>
                                        <input type="checkbox" id="copiar-endereco-principal-cobranca" name="copiar_endereco_principal_cobranca" value="1">
                                    </div>
                                    <div class="col-md-4">
                                        <span class="help help-block"></span>
                                        <label>Copiar Endereço Entrega: </label>
                                        <input type="checkbox" id="copiar-endereco-entrega-cobranca" name="copiar_endereco_entrega_cobranca" value="1">
                                    </div>
                                </div>
                            </div>
                            <div id="divEnderecoEntrega" hidden>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">CEP: </label>
                                        <input type="text" class="form-control input-sm cep inputEnd" id="cepEntregaClienteEditar" name="cep_entrega">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Número / Complemento: </label>
                                        <input type="text" class="form-control input-sm" id="complementoEntregaClienteEditar" name="complemento_entrega">
                                    </div>                    
                                </div> 
                                <br>
                                <div class="row"> 
                                    <div class="col-md-6">
                                        <label class="control-label">Rua: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="ruaEntregaClienteEditar" name="rua_entrega">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Bairro: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="bairroEntregaClienteEditar" name="bairro_entrega">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Cidade: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="cidadeEntregaClienteEditar" name="cidade_entrega">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Estado: </label>
                                        <input type="text" class="form-control input-sm inputEnd" id="estadoEntregaClienteEditar" name="estado_entrega">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="help help-block"></span>
                                        <label>Copiar Endereço Principal: </label>
                                        <input type="checkbox" id="copiar-endereco-principal-entrega" name="copiar_endereco_principal_entrega" value="1">
                                    </div>
                                    <div class="col-md-4">
                                        <span class="help help-block"></span>
                                        <label>Copiar Endereço Cobrança: </label>
                                        <input type="checkbox" id="copiar-endereco-cobranca-entrega" name="copiar_endereco_cobranca_entrega" value="1">
                                    </div>
                                </div>
                            </div>   
						</div>
					</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true" style="margin-top: 10px">Fechar</button>

                    <button class="btn btn-primary" type="submit" id="btnEditarCadCliente" style="margin-right: 15px; margin-top: 10px">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">  
    <div class="col-sm-12">
        <div class="col-sm-12">
            <form id="EdicaoPJ" hidden>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>Nome: </label>
                        <input type="text" class="form-control" id="nome-pj" name="name" required>
                    </div>
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>Nome Fantasia: </label>
                        <input type="text" class="form-control" id="nome-fantasia-pj" name="nome_fantasia" value="" >
                    </div>
                </div>
                <span class="help help-block"></span>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>E-mail: </label>
                        <input type="text" class="form-control" id="email-pj" name="email" value="" required>
                    </div>
                    <div class="col-md-6 style=" style="border-left: 3px solid #03A9F4">
                        <label>Login Vendedor: </label>
                        <input type="text" class="form-control" id="vendedor-pj" name="loginVendedor" value="" readonly>
                    </div>
                </div>
                <span class="help help-block"></span>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>Documento: </label>
                        <input type="text" class="form-control" id="documento-pj" name="documento" value="" readonly >
                    </div>
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>Inscrição Estadual: </label>
                        <input type="text" class="form-control" id="ie-pj" data-mask="0000000-00" name="inscricao_estadual" required>
                    </div>
                </div>
                <span class="help help-block"></span>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <div class="row">
                            <div class="col-md-2">
                                <Label>DDD: </Label>
                                <input type="text" class="form-control ddd" id="ddd-pj" name="ddd_telefone" >
                            </div>
                            <div class="col-md-10">
                                <Label>Telefone: </Label>
                                <input type="text" class="form-control fone" id="telefone-pj" name="telefone" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <div class="row">
                            <div class="col-md-2">
                                <Label>DDD: </Label>
                                <input type="text" class="form-control ddd" id="ddd-pj2" name="ddd_telefone2" >
                            </div>
                            <div class="col-md-10">
                                <Label>Telefone: </Label>
                                <input type="text" class="form-control fone" id="telefone-pj2" name="telefone2" >
                            </div>
                        </div>
                    </div>
                </div>
                <span class="help help-block"></span>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <div class="row">
                            <div class="col-md-12">
                                <Label>Signatario: </Label>
                                <input type="text" class="form-control" id="signatario_mei-pj" name="signatario_mei" >
                            </div>
                        </div>
                    </div>   
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <div class="row">
                            <div class="col-md-12">
                                <Label>Email Signatario: </Label>
                                <input type="text" class="form-control" id="email_signatario_mei-pj" name="email_signatario_mei" >
                            </div>
                        </div>
                    </div>   
                </div>
                <span class="help help-block"></span>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <div class="row">
                            <div class="col-md-12">
                                <Label>Documento Signatario: </Label>
                                <input type="text" class="form-control documento" id="documento_signatario_mei-pj" name="documento_signatario_mei" >
                            </div>
                        </div>
                    </div>   
                </div>
                <span class="help help-block"></span>
                <hr>
                <h4>Endereços</h4>

                <ul class="nav nav-tabs nav-justified">
                    <li role="presentation" class="active"><a id="endereco-principal-pj-a" href="#">Endereço Principal</a></li>
                    <li role="presentation"><a id="endereco-cobranca-pj-a" href="#">Endereço Cobrança</a></li>
                    <li role="presentation"><a id="endereco-entrega-pj-a" href="#">Endereço Entrega</a></li>                
                </ul>

                <script>
                    $("#endereco-principal-pj-a").click(function(){
                        $("#endereco-principal-pj").show();
                        $("#endereco-cobranca-pj").hide();
                        $("#endereco-entrega-pj").hide();

                        $("#endereco-principal-pj-a").parent().attr('class', "active")
                        $("#endereco-cobranca-pj-a").parent().removeAttr('class')
                        $("#endereco-entrega-pj-a").parent().removeAttr('class')
                    });

                    $("#endereco-cobranca-pj-a").click(function(){
                        $("#endereco-principal-pj").hide();
                        $("#endereco-cobranca-pj").show();
                        $("#endereco-entrega-pj").hide();

                        $("#endereco-principal-pj-a").parent().removeAttr('class')
                        $("#endereco-cobranca-pj-a").parent().attr('class', "active")
                        $("#endereco-entrega-pj-a").parent().removeAttr('class')
                    });

                    $("#endereco-entrega-pj-a").click(function(){
                        $("#endereco-principal-pj").hide();
                        $("#endereco-cobranca-pj").hide();
                        $("#endereco-entrega-pj").show();

                        $("#endereco-principal-pj-a").parent().removeAttr('class')
                        $("#endereco-cobranca-pj-a").parent().removeAttr('class')
                        $("#endereco-entrega-pj-a").parent().attr('class', "active")
                    });

                </script>

                <div id="endereco-principal-pj">
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>CEP: </label>
                            <input type="text" class="form-control cep" id="cep-principal-pj" name="cep_principal" required>
                        </div>
                        <div class="col-md-6">
                            <label>Rua: </label>
                            <input type="text" class="form-control" id="rua-principal-pj" name="rua_principal" required>
                        </div>
                        <div class="col-md-3">
                            <label>Número / Complemento:</label>
                            <input type="text" class="form-control" id="complemento-principal-pj" name="complemento_principal" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>  
                    <div class="row"> 
                        <div class="col-md-4">
                            <label>Bairro: </label>
                            <input type="text" class="form-control" id="bairro-principal-pj" name="bairro_principal" required>
                        </div>
                        <div class="col-md-4">
                            <label>Cidade: </label>
                            <input type="text" class="form-control" id="cidade-principal-pj" name="cidade_principal" required>
                        </div>
                        <div class="col-md-4">
                            <label>Estado: </label>
                            <input type="text" class="form-control" id="estado-principal-pj" name="estado_principal" required>
                        </div>
                    </div>
                </div>
                <div id="endereco-cobranca-pj" hidden>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>CEP: </label>
                            <input type="text" class="form-control cep" id="cep-cobranca-pj" name="cep_cobranca" required>
                        </div>
                        <div class="col-md-6">
                            <label>Rua: </label>
                            <input type="text" class="form-control" id="rua-cobranca-pj" name="rua_cobranca" required>
                        </div>
                        <div class="col-md-3">
                            <label>Número / Complemento: </label>
                            <input type="text" class="form-control" id="complemento-cobranca-pj" name="complemento_cobranca" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row"> 
                        <div class="col-md-4">
                            <label>Bairro: </label>
                            <input type="text" class="form-control" id="bairro-cobranca-pj" name="bairro_cobranca" required>
                        </div>
                        <div class="col-md-4">
                            <label>Cidade: </label>
                            <input type="text" class="form-control" id="cidade-cobranca-pj" name="cidade_cobranca" required>
                        </div>
                        <div class="col-md-4">
                            <label>Estado: </label>
                            <input type="text" class="form-control" id="estado-cobranca-pj" name="estado_cobranca" required>
                        </div>
                    </div>
                </div>
                <div id="endereco-entrega-pj" hidden>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>CEP: </label>
                            <input type="text" class="form-control cep" id="cep-entrega-pj" name="cep_entrega" required>
                        </div>
                        <div class="col-md-6">
                            <label>Rua: </label>
                            <input type="text" class="form-control" id="rua-entrega-pj" name="rua_entrega" required>
                        </div>
                        <div class="col-md-3">
                            <label>Número / Complemento: </label>
                            <input type="text" class="form-control" id="complemento-entrega-pj" name="complemento_entrega" required>
                        </div>                    
                    </div> 
                    <span class="help help-block"></span>
                    <div class="row"> 
                        <div class="col-md-4">
                            <label>Bairro: </label>
                            <input type="text" class="form-control" id="bairro-entrega-pj" name="bairro_entrega" required>
                        </div>
                        <div class="col-md-4">
                            <label>Cidade: </label>
                            <input type="text" class="form-control" id="cidade-entrega-pj" name="cidade_entrega" required>
                        </div>
                        <div class="col-md-4">
                            <label>Estado: </label>
                            <input type="text" class="form-control" id="estado-entrega-pj" name="estado_entrega" required>
                        </div>
                    </div>
                </div>   
            </form>
        </div>
        
        <div class="col-sm-12">
            <form id="EdicaoPF" hidden>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>Nome: </label>
                        <input type="text" class="form-control" id="nome-pf" name="name" required>
                    </div>
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>Sobrenome: </label>
                        <input type="text" class="form-control" id="sobrenome-pf" name="sobrenome" value="">
                    </div>
                </div>
                <span class="help help-block"></span>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>E-mail: </label>
                        <input type="text" class="form-control" id="email-pf" name="email" value="" required>
                    </div>
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>Login Vendedor: </label>
                        <input type="text" class="form-control" id="vendedor-pf" name="loginVendedor" value="" readonly>
                    </div>
                </div>
                <span class="help help-block"></span>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>Documento: </label>
                        <input type="text" class="form-control" id="documento-pf" name="documento" value="" readonly>
                    </div>
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>RG: </label>
                        <input type="text" class="form-control" id="rg-pf" name="rg" value="" required>
                    </div>
                </div>
                <span class="help help-block"></span>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <label>Data de Nascimento: </label>
                        <input type="date" class="form-control" id="nascimento-pf" name="dataNascimento" value="" >
                    </div>
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <div class="row">   
                            <div class="col-md-2">
                                <Label>DDD: </Label>
                                <input type="text" class="form-control ddd" id="ddd-pf" name="ddd_telefone" >
                            </div>
                            <div class="col-md-10">
                                <Label>Telefone: </Label>
                                <input type="text" class="form-control fone" id="telefone-pf" name="telefone" >
                            </div>
                        </div>
                    </div>
                </div>
                <span class="help help-block"></span>
                <div class="row">
                    <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                        <div class="row">
                            <div class="col-md-2">
                                <Label>DDD: </Label>
                                <input type="text" class="form-control ddd" id="ddd-pf2" name="ddd_telefone2" >
                            </div>
                            <div class="col-md-10">
                                <Label>Telefone: </Label>
                                <input type="text" class="form-control fone" id="telefone-pf2" name="telefone2" >
                            </div>
                        </div>
                    </div>   
                </div>
                <span class="help help-block"></span>
                <hr>
                <h4>Endereços</h4>

                <ul class="nav nav-tabs nav-justified">
                    <li role="presentation" class="active"><a id="endereco-principal-pf-a" href="#">Endereço Principal</a></li>
                    <li role="presentation"><a id="endereco-cobranca-pf-a" href="#">Endereço Cobrança</a></li>
                    <li role="presentation"><a id="endereco-entrega-pf-a" href="#">Endereço Entrega</a></li>                
                </ul>

                <script>
                    $("#endereco-principal-pf-a").click(function(){
                        $("#endereco-principal-pf").show();
                        $("#endereco-cobranca-pf").hide();
                        $("#endereco-entrega-pf").hide();

                        $("#endereco-principal-pf-a").parent().attr('class', "active")
                        $("#endereco-cobranca-pf-a").parent().removeAttr('class')
                        $("#endereco-entrega-pf-a").parent().removeAttr('class')
                    });

                    $("#endereco-cobranca-pf-a").click(function(){
                        $("#endereco-principal-pf").hide();
                        $("#endereco-cobranca-pf").show();
                        $("#endereco-entrega-pf").hide();

                        $("#endereco-principal-pf-a").parent().removeAttr('class')
                        $("#endereco-cobranca-pf-a").parent().attr('class', "active")
                        $("#endereco-entrega-pf-a").parent().removeAttr('class')
                    });

                    $("#endereco-entrega-pf-a").click(function(){
                        $("#endereco-principal-pf").hide();
                        $("#endereco-cobranca-pf").hide();
                        $("#endereco-entrega-pf").show();

                        $("#endereco-principal-pf-a").parent().removeAttr('class')
                        $("#endereco-cobranca-pf-a").parent().removeAttr('class')
                        $("#endereco-entrega-pf-a").parent().attr('class', "active")
                    });

                </script>

                <div id="endereco-principal-pf">
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>CEP: </label>
                            <input type="text" class="form-control cep" id="cep-principal-pf" name="cep_principal" required>
                        </div>
                        <div class="col-md-6">
                            <label>Rua: </label>
                            <input type="text" class="form-control" id="rua-principal-pf" name="rua_principal" required>
                        </div>
                        <div class="col-md-3">
                            <label>Número / Complemento: </label>
                            <input type="text" class="form-control" id="complemento-principal-pf" name="complemento_principal" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>  
                    <div class="row"> 
                        <div class="col-md-4">
                            <label>Bairro: </label>
                            <input type="text" class="form-control" id="bairro-principal-pf" name="bairro_principal" required>
                        </div>
                        <div class="col-md-4">
                            <label>Cidade: </label>
                            <input type="text" class="form-control" id="cidade-principal-pf" name="cidade_principal" required>
                        </div>
                        <div class="col-md-4">
                            <label>Estado: </label>
                            <input type="text" class="form-control" id="estado-principal-pf" name="estado_principal" required>
                        </div>
                    </div>
                </div>
                <div id="endereco-cobranca-pf" hidden>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>CEP: </label>
                            <input type="text" class="form-control cep" id="cep-cobranca-pf" name="cep_cobranca" required>
                        </div>
                        <div class="col-md-6">
                            <label>Rua: </label>
                            <input type="text" class="form-control" id="rua-cobranca-pf" name="rua_cobranca" required>
                        </div>
                        <div class="col-md-3">
                            <label>Número / Complemento:</label>
                            <input type="text" class="form-control" id="complemento-cobranca-pf" name="complemento_cobranca" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row"> 
                        <div class="col-md-4">
                            <label>Bairro: </label>
                            <input type="text" class="form-control" id="bairro-cobranca-pf" name="bairro_cobranca" required>
                        </div>
                        <div class="col-md-4">
                            <label>Cidade: </label>
                            <input type="text" class="form-control" id="cidade-cobranca-pf" name="cidade_cobranca" required>
                        </div>
                        <div class="col-md-4">
                            <label>Estado: </label>
                            <input type="text" class="form-control" id="estado-cobranca-pf" name="estado_cobranca" required>
                        </div>
                    </div>
                </div>
                <div id="endereco-entrega-pf" hidden>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>CEP: </label>
                            <input type="text" class="form-control cep" id="cep-entrega-pf" name="cep_entrega" required>
                        </div>
                        <div class="col-md-6">
                            <label>Rua: </label>
                            <input type="text" class="form-control"  id="rua-entrega-pf" name="rua_entrega" required>
                        </div>
                        <div class="col-md-3">
                            <label>Número / Complemento:</label>
                            <input type="text" class="form-control" id="complemento-entrega-pf" name="complemento_entrega" required>
                        </div>                    
                    </div> 
                    <span class="help help-block"></span>
                    <div class="row"> 
                        <div class="col-md-4">
                            <label>Bairro: </label>
                            <input type="text" class="form-control" id="bairro-entrega-pf" name="bairro_entrega" required>
                        </div>
                        <div class="col-md-4">
                            <label>Cidade: </label>
                            <input type="text" class="form-control" id="cidade-entrega-pf" name="cidade_entrega" required>
                        </div>
                        <div class="col-md-4">
                            <label>Estado: </label>
                            <input type="text" class="form-control" id="estado-entrega-pf" name="estado_entrega" required>
                        </div>
                    </div>
                </div>  
            </form>
        </div>
    </div>  
</div>


    
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

    // preencherCamposCliente("");

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
</script>


<script type="text/javascript">

// Quando o processamento do formulário estiver concluído, oculta a tela de carregamento
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('loading').style.display = 'none';
    });
});



//Fechar o dropdown ao clicar fora 
$(document).on('click', function(e) {
    if (!e.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
});

var tabelaClientePorVendedor = $('#tabelaClientesPorVendedor').DataTable({
    responsive: false,
    ordering: true,
    paging: true,
    searching: true,
    info: true,
    order: [0, 'asc'],
        ajax: {
            url: '<?= site_url('ComerciaisTelevendas/Pedidos/clientesPorVendedorTelevendas') ?>',
            type: 'GET',
            dataType: 'json',
            data: {emailVendedor: '<?= $_SESSION['emailUsuario'] ?>'
            },
            success: function(data){
                if (data.status == 200){
                    tabelaClientePorVendedor.clear().draw();
                    tabelaClientePorVendedor.rows.add(data.results).draw();
                }else{
                    tabelaClientePorVendedor.clear().draw();
                }
            },
            error: function(){
                alert('Erro ao buscar clientes');
                tabelaClientePorVendedor.clear().draw();
            }
        },
    language:{
        loadingRecords: '<b>Processando...</b> <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: #06a9f6;"></i>',
        searchPlaceholder:  'Pesquisar',
        search:             'Pesquisar:',
        emptyTable:         "Nenhum cliente a ser listado",
        info:               "Mostrando _START_ até _END_ de _TOTAL_ resultados.",
        infoEmpty:          "Mostrando 0 até 0 de 0 resultados.",
        lengthMenu:         "Exibir: _MENU_",
        zeroRecords:        "Nenhum resultado encontrado.",
        paginate: {
            first:          "Primeira",
            last:           "Última",
            next:           "Próxima",
            previous:       "Anterior"
        },
    },
    deferRender: true,
    lengthChange: true,
    columns: [
        {data: 'nome'},
        {data: 'nomeFantasia'},
        {data: 'cpfCnpj'},
        {data: 'dataNascimento'},
        {data: 'telefone'},
        {data: 'email'},
        {data: 'loginVendedor'},
        {data: {'nome': 'nome', 'sobrenome': 'sobrenome', 'nomeFantasia': 'nomeFantasia', 'cpfCnpj': 'cpfCnpj', 'rg': 'rg', 'dataNascimento': 'dataNascimento', 'telefone': 'telefone', 'email': 'email', 'signatarioMei': 'signatarioMei', 'emailSignatarioMei': 'emailSignatarioMei', 'documentoSignatarioMei' : 'documentoSignatarioMei', 'tipoCliente' : 'tipoCliente', 'endereco' : 'endereco', 'loginVendedor' : 'loginVendedor', 'dataCriacao' : 'dataCriacao', 'vendedor' : 'vendedor'  },
            render: function(data){
                return `
                    <button 
						class="btn btn-primary"
						title="Editar Cliente"
						id="btnEditarCliente"
                        onClick="javascript:editCliente(this, '${data['cpfCnpj']}', event)">
						<i class="fa fa-pencil" aria-hidden="true"></i>
					</button>
                    `;
            }

        }
    ]
})
$('#tabelaClientesPorVendedor_wrapper').css('display', 'grid');

$(document).ready(function() {

    dt_table = $('#dt_table').DataTable({
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

    //Mostrar o dropdown ao clicar
    $('#btnDropdown').on('click', function(e) {
        e.preventDefault();
        $('#myDropdown')[0].classList.toggle("show");
    });

    $('#tableHistoricoStatusAFclie').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "info": false
    });

    //Mostrar botão dropdown somente se for pesquisado algum cliente
    var documento = $("#listagem_docs").attr('data-documento');
    if (documento) {
        $("#pesquisa_avancada").show();
        $("#btnSalvarCliente").show();
    } else {
        $("#pesquisa_avancada").hide();
        $("#btnSalvarCliente").hide();
    }

    $('input[type=file]').change(function() {
        var t = $(this).val();
        var labelText = 'Arquivo : ' + t.substr(12, t.length);
        $(this).prev('label').text(labelText);
    })

    $('.listagem_docs').on('click', function(e) {
        e.preventDefault();

        $('.feedback-alert').html('');
        
        document.getElementById('loading').style.display = 'block';

        var botaoDocumentos = $(this);
        var documento = botaoDocumentos.attr('data-documento');
        let htmlBotao = botaoDocumentos.html();
        botaoDocumentos.html(ICONS.spinner);
        botaoDocumentos.attr('disabled', true);

        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/solicitarArquivos?documento=') ?>" +
                documento,
            type: "GET",
            success: function(data) {
                document.getElementById('loading').style.display = 'none';

                let datajson = JSON.parse(data);
                if (data && datajson['Status'] == 200) {
                    var resposta = datajson['Message'];
                    $('#tableListagemDocumentos tbody').empty();
                    preencherTabelaDocumentos(resposta);
                    if (resposta.length == 0) {
                        var table = document.getElementById("tableListagemDocumentos");
                        var tbody = table.getElementsByTagName("tbody")[0];
                        var row = tbody.insertRow();
                        var cell = row.insertCell(0);
                        cell.colSpan = 2;
                        cell.className = "text-center";
                        cell.textContent = "Nenhum registro encontrado.";
                    }

                    $("#modalListagemDocumentos").modal("show");

                    $('#modalListagemDocumentos').on('hidden.bs.modal', function(e) {
                        botaoDocumentos.html(htmlBotao);
                        botaoDocumentos.attr('disabled', false);
                    });
                } else {
                    alert("Ocorreu um problema, tente novamente mais tarde.");
                    botaoDocumentos.html(htmlBotao);
                    botaoDocumentos.attr('disabled', false);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                document.getElementById('loading').style.display = 'none';
                $('#modalListagemDocumentos .modal-title').text('Erro');
                $('#modalListagemDocumentos .modal-body').text(
                    'Ocorreu um erro ao processar a solicitação.');
                $('#modalListagemDocumentos').modal('show');
            }})
        });
});

    $('#AddArquivoCliente').on('click', function(e) {
        e.preventDefault();
        var dataForm = new FormData();
        document.getElementById('loading').style.display = 'block';

        //File data
        var documento = $("#listagem_docs").attr('data-documento');

        var file_data = $('#files')[0];
        file = file_data.files[0];

        if (!file) {
            document.getElementById('loading').style.display = 'none';
            alert("Selecione um Arquivo.");
            return;
        }
        dataForm.append("Arquivo", file);
        dataForm.append("documento", documento);

        $.ajax({
            url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/addArquivoClienteCRM') ?>",
            type: "POST",
            data: dataForm,
            processData: false,
            contentType: false,
            success: function(data) {
                document.getElementById('loading').style.display = 'none';
                let datajson = JSON.parse(data);
                               
                if (data && datajson['Status'] == 200) {
                    alert("Arquivo enviado com sucesso.");
                    AtualizarTabelaDocumentos(documento);
                } else {
                    alert("Ocorreu um problema ao Enviar o Arquivo.");
                }
            },
            error: function(error) {
                document.getElementById('loading').style.display = 'none';
                alert("Ocorreu um problema ao Enviar o Arquivo.");
            }
        });
    });




async function buscarHistoricoAfclie(botao, resposta, ICONS) {
    // Acessar o ID da AF a partir do objeto de resposta
    let idAF = resposta;
    botao = $(botao);
    let htmlBotao = botao.html();
    botao.html(ICONS.spinner);
    botao.attr('disabled', true);

    const requisicaoAjax = $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/buscarHistoricoAf') ?>",
        type: "POST",
        data: {
            idAF
        }
    });

    const requisicaoAjaxN = $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/buscarNumeroAf') ?>",
        type: "POST",
        data: {
            idAF
        }
    });


    requisicaoAjax.then(function(callback) {
        requisicaoAjaxN.then(function(callbackN) {
            callbackN = JSON.parse(callbackN);
            callback = JSON.parse(callback);
            if (callback.status == 200) {
                $('#labelNumeroAF').text('Número da AF: ' + callbackN.data);

                // limpar a tabela antes de preencher com novos dados
                $('#tableHistoricoStatusAFclie tbody').empty();
                preencherTabelaclie(callback.data);
                if (callback.data.length == 0) {
                    var table = document.getElementById("tableHistoricoStatusAFclie");
                    var tbody = table.getElementsByTagName("tbody")[0];
                    var row = tbody.insertRow();
                    var cell = row.insertCell(0);
                    cell.colSpan = 2;
                    cell.className = "text-center";
                    cell.textContent = "Nenhum registro encontrado.";
                }
                $("#modalHistoricoStatusAFclie").modal("show");

            } else {
                alert("erro ao buscar dados");
            }
        })
    });

    $('#modalHistoricoStatusAFclie').on('hidden.bs.modal', function(e) {
        botao.html(htmlBotao);
        botao.attr('disabled', false);
    });
}

function preencherTabelaclie(data) {
    var table = document.getElementById("tableHistoricoStatusAFBodyclie");
    for (var i = 0; i < data.length; i++) {
        var row = table.insertRow();
        var dataEventoCell = row.insertCell(0);
        var observacoesCell = row.insertCell(1);
        dataEventoCell.innerHTML = data[i].dataEvento;
        observacoesCell.innerHTML = data[i].observacoes;
    }
}

function AtualizarTabelaDocumentos(documento) {

    document.getElementById('loading').style.display = 'block';
    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/solicitarArquivos?documento=') ?>" + documento,
        type: "GET",
        success: function(data) {
            document.getElementById('loading').style.display = 'none';
            let datajson = JSON.parse(data);
            if (data && datajson['Status'] == 200) {
                var resposta = datajson['Message'];
                $('#tableListagemDocumentosBody').empty();
                preencherTabelaDocumentos(resposta);
                if (resposta.length == 0) {
                    var table = document.getElementById("tableListagemDocumentos");
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
        error: function(xhr, textStatus, errorThrown) {
            document.getElementById('loading').style.display = 'none';
            $('#modalListagemDocumentos .modal-title').text('Erro');
            $('#modalListagemDocumentos .modal-body').text('Ocorreu um erro ao processar a solicitação.');
            $('#modalListagemDocumentos').modal('show');
        }
    });
}





function preencherCamposCliente(documento) {  
    document.getElementById('loading').style.display = 'block';

    if(documento == ""){
        documento = '<?php if (isset($_SESSION['documento'])){echo $_SESSION['documento'];} ?>'
    }
    if (documento) {
        $("#pesquisa_avancada").show();
        $("#btnSalvarCliente").show();
        $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/resumoClienteCRM');?>",
        type: "POST",
        data: {
            documento: documento
        },
        success: function(data) {
            resposta = JSON.parse(data)            
            document.getElementById('loading').style.display = 'none';
            if(resposta?.status === 200){
                if(resposta?.dados?.Message){
                   documento = resposta?.dados?.Message?.documento;
                   if (resposta?.dados?.Message?.documento.length > 14) {
                        $("#nome-pj").val(resposta?.dados?.Message?.name ? resposta?.dados?.Message?.name : '')
                        $("#nome-fantasia-pj").val(resposta?.dados?.Message?.nome_fantasia ? resposta?.dados?.Message?.nome_fantasia : '')
                        $("#email-pj").val(resposta?.dados?.Message?.email ? resposta?.dados?.Message?.email : '')
                        $("#vendedor-pj").val(resposta?.dados?.Message?.loginVendedor ? resposta?.dados?.Message?.loginVendedor : '')
                        $("#documento-pj").val(resposta?.dados?.Message?.documento ? resposta?.dados?.Message?.documento : '')
                        $("#ie-pj").val(resposta?.dados?.Message?.inscricao_estadual ? resposta?.dados?.Message?.inscricao_estadual : '')
                        $("#ddd-pj").val(resposta?.dados?.Message?.ddd_telefone ? resposta?.dados?.Message?.ddd_telefone : '')
                        $("#ddd-pj2").val(resposta?.dados?.Message?.ddd_telefone2 ? resposta?.dados?.Message?.ddd_telefone2 : '')
                        $("#telefone-pj").val(resposta?.dados?.Message?.telefone ? resposta?.dados?.Message?.telefone : '')
                        $("#telefone-pj2").val(resposta?.dados?.Message?.telefone2 ? resposta?.dados?.Message?.telefone2 : '')
                        
                        $("#cep-principal-pj").val(resposta?.dados?.Message?.endereco_principal?.cep ? resposta?.dados?.Message?.endereco_principal?.cep : '')
                        $("#complemento-principal-pj").val(resposta?.dados?.Message?.endereco_principal?.complemento ? resposta?.dados?.Message?.endereco_principal?.complemento : '')
                        $("#rua-principal-pj").val(resposta?.dados?.Message?.endereco_principal?.rua ? resposta?.dados?.Message?.endereco_principal?.rua : '')
                        $("#bairro-principal-pj").val(resposta?.dados?.Message?.endereco_principal?.bairro ? resposta?.dados?.Message?.endereco_principal?.bairro : '')
                        $("#cidade-principal-pj").val(resposta?.dados?.Message?.endereco_principal?.cidade ? resposta?.dados?.Message?.endereco_principal?.cidade : '')
                        $("#estado-principal-pj").val(resposta?.dados?.Message?.endereco_principal?.estado ? resposta?.dados?.Message?.endereco_principal?.estado : '')
                        $("#cep-cobranca-pj").val(resposta?.dados?.Message?.endereco_cobranca?.cep ? resposta?.dados?.Message?.endereco_cobranca?.cep : '')
                        $("#complemento-cobranca-pj").val(resposta?.dados?.Message?.endereco_cobranca?.complemento ? resposta?.dados?.Message?.endereco_cobranca?.complemento : '')
                        $("#rua-cobranca-pj").val(resposta?.dados?.Message?.endereco_cobranca?.rua ? resposta?.dados?.Message?.endereco_cobranca?.rua : '')
                        $("#bairro-cobranca-pj").val(resposta?.dados?.Message?.endereco_cobranca?.bairro ? resposta?.dados?.Message?.endereco_cobranca?.bairro : '')
                        $("#cidade-cobranca-pj").val(resposta?.dados?.Message?.endereco_cobranca?.cidade ? resposta?.dados?.Message?.endereco_cobranca?.cidade : '')
                        $("#estado-cobranca-pj").val(resposta?.dados?.Message?.endereco_cobranca?.estado ? resposta?.dados?.Message?.endereco_cobranca?.estado : '')
                        $("#cep-entrega-pj").val(resposta?.dados?.Message?.endereco_entrega?.cep ? resposta?.dados?.Message?.endereco_entrega?.cep : '')
                        $("#complemento-entrega-pj").val(resposta?.dados?.Message?.endereco_entrega?.complemento ? resposta?.dados?.Message?.endereco_entrega?.complemento : '')
                        $("#rua-entrega-pj").val(resposta?.dados?.Message?.endereco_entrega?.rua ? resposta?.dados?.Message?.endereco_entrega?.rua : '')
                        $("#bairro-entrega-pj").val(resposta?.dados?.Message?.endereco_entrega?.bairro ? resposta?.dados?.Message?.endereco_entrega?.bairro : '')
                        $("#cidade-entrega-pj").val(resposta?.dados?.Message?.endereco_entrega?.cidade ? resposta?.dados?.Message?.endereco_entrega?.cidade : '')
                        $("#estado-entrega-pj").val(resposta?.dados?.Message?.endereco_entrega?.estado ? resposta?.dados?.Message?.endereco_entrega?.estado : '')

                        $("#signatario_mei-pj").val(resposta?.dados?.Message?.signatario_mei ? resposta?.dados?.Message?.signatario_mei : '')
                        $("#email_signatario_mei-pj").val(resposta?.dados?.Message?.email_signatario_mei ? resposta?.dados?.Message?.email_signatario_mei : '')
                        $("#documento_signatario_mei-pj").val(resposta?.dados?.Message?.documento_signatario_mei ? resposta?.dados?.Message?.documento_signatario_mei : '')
            
                        $("#EdicaoPJ").show()
                        $("#EdicaoPF").hide()

                    } else {
                        $("#nome-pf").val(resposta?.dados?.Message?.name ? resposta?.dados?.Message?.name : '')
                        $("#sobrenome-pf").val(resposta?.dados?.Message?.sobrenome ? resposta?.dados?.Message?.sobrenome : '')
                        $("#email-pf").val(resposta?.dados?.Message?.email ? resposta?.dados?.Message?.email : '')
                        $("#vendedor-pf").val(resposta?.dados?.Message?.loginVendedor ? resposta?.dados?.Message?.loginVendedor : '')
                        $("#documento-pf").val(resposta?.dados?.Message?.documento ? resposta?.dados?.Message?.documento : '')
                        $("#rg-pf").val(resposta?.dados?.Message?.rg ? resposta?.dados?.Message?.rg : '')
                        $("#nascimento-pf").val(resposta?.dados?.Message?.dataNascimento ? (new Date(resposta?.dados?.Message?.dataNascimento)).toISOString().slice(0,10) : '')
                        $("#ddd-pf").val(resposta?.dados?.Message?.ddd_telefone ? resposta?.dados?.Message?.ddd_telefone : '')
                        $("#ddd-pf2").val(resposta?.dados?.Message?.ddd_telefone2 ? resposta?.dados?.Message?.ddd_telefone2 : '')
                        $("#telefone-pf").val(resposta?.dados?.Message?.telefone ? resposta?.dados?.Message?.telefone : '')
                        $("#telefone-pf2").val(resposta?.dados?.Message?.telefone2 ? resposta?.dados?.Message?.telefone2 : '')

                        
                        $("#cep-principal-pf").val(resposta?.dados?.Message?.endereco_principal?.cep ? resposta?.dados?.Message?.endereco_principal?.cep : '')
                        $("#complemento-principal-pf").val(resposta?.dados?.Message?.endereco_principal?.complemento ? resposta?.dados?.Message?.endereco_principal?.complemento : '')
                        $("#rua-principal-pf").val(resposta?.dados?.Message?.endereco_principal?.rua ? resposta?.dados?.Message?.endereco_principal?.rua : '')
                        $("#bairro-principal-pf").val(resposta?.dados?.Message?.endereco_principal?.bairro ? resposta?.dados?.Message?.endereco_principal?.bairro : '')
                        $("#cidade-principal-pf").val(resposta?.dados?.Message?.endereco_principal?.cidade ? resposta?.dados?.Message?.endereco_principal?.cidade : '')
                        $("#estado-principal-pf").val(resposta?.dados?.Message?.endereco_principal?.estado ? resposta?.dados?.Message?.endereco_principal?.estado : '')
                        $("#cep-cobranca-pf").val(resposta?.dados?.Message?.endereco_cobranca?.cep ? resposta?.dados?.Message?.endereco_cobranca?.cep : '')
                        $("#complemento-cobranca-pf").val(resposta?.dados?.Message?.endereco_cobranca?.complemento ? resposta?.dados?.Message?.endereco_cobranca?.complemento : '')
                        $("#rua-cobranca-pf").val(resposta?.dados?.Message?.endereco_cobranca?.rua ? resposta?.dados?.Message?.endereco_cobranca?.rua : '')
                        $("#bairro-cobranca-pf").val(resposta?.dados?.Message?.endereco_cobranca?.bairro ? resposta?.dados?.Message?.endereco_cobranca?.bairro : '')
                        $("#cidade-cobranca-pf").val(resposta?.dados?.Message?.endereco_cobranca?.cidade ? resposta?.dados?.Message?.endereco_cobranca?.cidade : '')
                        $("#estado-cobranca-pf").val(resposta?.dados?.Message?.endereco_cobranca?.estado ? resposta?.dados?.Message?.endereco_cobranca?.estado : '')
                        $("#cep-entrega-pf").val(resposta?.dados?.Message?.endereco_entrega?.cep ? resposta?.dados?.Message?.endereco_entrega?.cep : '')
                        $("#complemento-entrega-pf").val(resposta?.dados?.Message?.endereco_entrega?.complemento ? resposta?.dados?.Message?.endereco_entrega?.complemento : '')
                        $("#rua-entrega-pf").val(resposta?.dados?.Message?.endereco_entrega?.rua ? resposta?.dados?.Message?.endereco_entrega?.rua : '')
                        $("#bairro-entrega-pf").val(resposta?.dados?.Message?.endereco_entrega?.bairro ? resposta?.dados?.Message?.endereco_entrega?.bairro : '')
                        $("#cidade-entrega-pf").val(resposta?.dados?.Message?.endereco_entrega?.cidade ? resposta?.dados?.Message?.endereco_entrega?.cidade : '')
                        $("#estado-entrega-pf").val(resposta?.dados?.Message?.endereco_entrega?.estado ? resposta?.dados?.Message?.endereco_entrega?.estado : '')
            
                        $("#EdicaoPF").show()
                        $("#EdicaoPJ").hide()
                    }
                }
            }else if(resposta?.status === 500){
                limparClienteForm();
                alert("Cliente não cadastrado! Vamos lhe direcionar para a página de cadastro.")
                $("#modalCadastroCliente").modal('show');
                if(documento.length > 14){
                    $("#formCadastroPF").hide();
                    $("#formCadastroPJ").show();
                }else{
                    $("#formCadastroPF").show();
                    $("#formCadastroPJ").hide();
                }
                $("#pesquisa_avancada").hide();
                $("#btnSalvarCliente").hide();
                $("#EdicaoPJ").hide()
                $("#EdicaoPF").hide()
            }
        },
        error: function (resposta){
            document.getElementById('loading').style.display = 'none';
        }
    });
    } else {
        $("#pesquisa_avancada").hide();
        $("#btnSalvarCliente").hide();
        $("#EdicaoPJ").hide()
        $("#EdicaoPF").hide()
        document.getElementById('loading').style.display = 'none';
    }
   
}

function validarCliente(documento, aba){

    document.getElementById('loading').style.display = 'block';

    $.ajax({
        url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/ajax_getClienteCRM');?>",
        type: "POST",
        data: {
            documento: documento
        },
        success: function(data) {
            resposta = JSON.parse(data)   
            document.getElementById('loading').style.display = 'none';
             if(resposta?.status === 200){
                if(!resposta?.clienteCadastrado){
                    limparClienteForm();
                    alert("Cliente não cadastrado! Vamos lhe direcionar para a página de cadastro.")
                    $("#modalCadastroCliente").modal('show');
                    if(documento.length > 14){
                        $("#formCadastroPF").hide();
                        $("#formCadastroPJ").show();
                    }else{
                        $("#formCadastroPF").show();
                        $("#formCadastroPJ").hide();
                    }
                    $("#pesquisa_avancada").hide();
                    $("#btnSalvarCliente").hide();
                    $("#EdicaoPJ").hide()
                    $("#EdicaoPF").hide()
                } else {
                    if (aba != 'quotes'){
                        $("#open-modal").click();
                        $('#documentoClienteOportunidade').val(documento);
                    }
                }
            }
        },
        error: function (resposta){            
            document.getElementById('loading').style.display = 'none';
        }
    });
}


function preencherTabelaDocumentos(data) {
    var table = document.getElementById("tableListagemDocumentos");
    for (var i = 0; i < data.length; i++) {
        tbody = table.getElementsByTagName("tbody")[0];
        var row = tbody.insertRow();
        var dataCell = row.insertCell(0);
        var documentoCell = row.insertCell(1);
        var acoesCell = row.insertCell(2);
        dataCell.innerHTML = data[i].createdon;
        documentoCell.innerHTML = data[i].Subject;        

        acoesCell.innerHTML = `<div>
                                    <button 
                                    id="btnRemoveDocumento"
						            class="btn"
						            title="Remover Documento"
						            style="margin: 0 auto; background-color: red; color: white;"
                                    onClick="javascript:removerDocumento(this, event, '${data[i].idAnnotation}')"
                                    data-documento="<?php echo isset($_SESSION['documento'])?$_SESSION['documento']: "";?>">
                                    <i class="fa fa-trash" aria-hidden="true" style="display: flex;align-items: center;justify-content: center; "></i>
					                </button>

                                    <a data-idAnnotation= '${data[i].idAnnotation}' class="btn btn-primary download_docs" role="button" title="Download"><i class="fa fa-download"></i></a></button>
                                 </div>
                                `;        

    }
    
}
</script>


<script>
    function getDadosForm(idForm) {
        let dadosForm = $(`#${idForm}`).serializeArray();
        let dados = {};
        
        for (let c in dadosForm) {
            dados[dadosForm[c].name] = dadosForm[c].value;
        }

        return dados;
    }

</script>

<script>
    
 $("#btnSalvarCliente").click(async function(e) {
    e.preventDefault();

    $('.feedback-alert').html('');

    let data = false;

    var documento = $("#listagem_docs").attr('data-documento');
    if(documento.length > 14){
        data = getDadosForm('EdicaoPJ');
    }else{
        data = getDadosForm('EdicaoPF');
    }

    let botao = $("#btnSalvarCliente");

    let htmlBotao = botao.html();
    botao.html(ICONS.spinner+' '+htmlBotao);
    botao.attr('disabled', true);
    
    let retorno = await $.ajax({
        url: `<?= site_url('ComerciaisTelevendas/Pedidos/atualizarClienteERP') ?>`,
        type: 'POST',
        data: data,
        success: function (response){   
            response = JSON.parse(response)
            alert(response?.Message)
            if(response?.Status == 200){
                preencherCamposCliente(documento);
            }
        },
        error: function (response){ 
            response = JSON.parse(response)
            alert(response?.Message);
        },
    })

    botao.html(htmlBotao);
    botao.attr('disabled', false);
})




    $('#copiar-endereco-principal-cobranca-erp-pf').change(function() {
        var checked = this.checked;
        if ($('#copiar-endereco-entrega-cobranca-erp-pf').prop('checked')) {
          $('#copiar-endereco-entrega-cobranca-erp-pf').prop('checked', false);
        }
        $('#cep-cobranca-erp-pf').val(checked ? $('#cep-principal-erp-pf').val() : '');
        $('#rua-cobranca-erp-pf').val(checked ? $('#rua-principal-erp-pf').val() : '');
        $('#complemento-cobranca-erp-pf').val(checked ? $('#complemento-principal-erp-pf').val() : '');
        $('#bairro-cobranca-erp-pf').val(checked ? $('#bairro-principal-erp-pf').val() : '');
        $('#cidade-cobranca-erp-pf').val(checked ? $('#cidade-principal-erp-pf').val() : '');
        $('#estado-cobranca-erp-pf').val(checked ? $('#estado-principal-erp-pf').val() : '');
    });

    $('#copiar-endereco-entrega-cobranca-erp-pf').change(function() {
        var checked = this.checked;
        if ($('#copiar-endereco-principal-cobranca-erp-pf').prop('checked')) {
          $('#copiar-endereco-principal-cobranca-erp-pf').prop('checked', false);
        }
        $('#cep-cobranca-erp-pf').val(checked ? $('#cep-entrega-erp-pf').val() : '');
        $('#rua-cobranca-erp-pf').val(checked ? $('#rua-entrega-erp-pf').val() : '');
        $('#complemento-cobranca-erp-pf').val(checked ? $('#complemento-entrega-erp-pf').val() : '');
        $('#bairro-cobranca-erp-pf').val(checked ? $('#bairro-entrega-erp-pf').val() : '');
        $('#cidade-cobranca-erp-pf').val(checked ? $('#cidade-entrega-erp-pf').val() : '');
        $('#estado-cobranca-erp-pf').val(checked ? $('#estado-entrega-erp-pf').val() : '');
    });

    $('#copiar-endereco-principal-entrega-erp-pf').change(function() {
        var chekced = this.checked;
        if ($('#copiar-endereco-cobranca-entrega-erp-pf').prop('checked')) {
          $('#copiar-endereco-cobranca-entrega-erp-pf').prop('checked', false);
        }
        $('#cep-entrega-erp-pf').val(chekced ? $('#cep-principal-erp-pf').val() : '');
        $('#rua-entrega-erp-pf').val(chekced ? $('#rua-principal-erp-pf').val() : '');
        $('#complemento-entrega-erp-pf').val(chekced ? $('#complemento-principal-erp-pf').val() : '');
        $('#bairro-entrega-erp-pf').val(chekced ? $('#bairro-principal-erp-pf').val() : '');
        $('#cidade-entrega-erp-pf').val(chekced ? $('#cidade-principal-erp-pf').val() : '');
        $('#estado-entrega-erp-pf').val(chekced ? $('#estado-principal-erp-pf').val() : '');
    })

    $('#copiar-endereco-cobranca-entrega-erp-pf').change(function() {
        var chekced = this.checked;
        if ($('#copiar-endereco-principal-entrega-erp-pf').prop('checked')) {
          $('#copiar-endereco-principal-entrega-erp-pf').prop('checked', false);
        }
        $('#cep-entrega-erp-pf').val(chekced ? $('#cep-cobranca-erp-pf').val() : '');
        $('#rua-entrega-erp-pf').val(chekced ? $('#rua-cobranca-erp-pf').val() : '');
        $('#complemento-entrega-erp-pf').val(chekced ? $('#complemento-cobranca-erp-pf').val() : '');
        $('#bairro-entrega-erp-pf').val(chekced ? $('#bairro-cobranca-erp-pf').val() : '');
        $('#cidade-entrega-erp-pf').val(chekced ? $('#cidade-cobranca-erp-pf').val() : '');
        $('#estado-entrega-erp-pf').val(chekced ? $('#estado-cobranca-erp-pf').val() : '');
    })

    $('#copiar-endereco-entrega-cobranca-erp-pf').change(function() {
        var checked = this.checked;
        if ($('#copiar-endereco-principal-cobranca-erp-pf').prop('checked')) {
          $('#copiar-endereco-principal-cobranca-erp-pf').prop('checked', false);
        }
        $('#cep-cobranca-erp-pf').val(checked ? $('#cep-entrega-erp-pf').val() : '');
        $('#rua-cobranca-erp-pf').val(checked ? $('#rua-entrega-erp-pf').val() : '');
        $('#complemento-cobranca-erp-pf').val(checked ? $('#complemento-entrega-erp-pf').val() : '');
        $('#bairro-cobranca-erp-pf').val(checked ? $('#bairro-entrega-erp-pf').val() : '');
        $('#cidade-cobranca-erp-pf').val(checked ? $('#cidade-entrega-erp-pf').val() : '');
        $('#estado-cobranca-erp-pf').val(checked ? $('#estado-entrega-erp-pf').val() : '');
    });

    $('#copiar-endereco-principal-cobranca-erp-pj-clientes').change(function() {
        var chekced = this.checked;
        if ($('#copiar-endereco-entrega-cobranca-erp-pj-clientes').prop('checked')) {
          $('#copiar-endereco-entrega-cobranca-erp-pj-clientes').prop('checked', false);
        }
        $('#cep-cobranca-erp-pj').val(chekced ? $('#cep-principal-erp-pj').val() : '');
        $('#rua-cobranca-erp-pj').val(chekced ? $('#rua-principal-erp-pj').val() : '');
        $('#complemento-cobranca-erp-pj').val(chekced ? $('#complemento-principal-erp-pj').val() : '');
        $('#bairro-cobranca-erp-pj').val(chekced ? $('#bairro-principal-erp-pj').val() : '');
        $('#cidade-cobranca-erp-pj').val(chekced ? $('#cidade-principal-erp-pj').val() : '');
        $('#estado-cobranca-erp-pj').val(chekced ? $('#estado-principal-erp-pj').val() : '');
    })

    $('#copiar-endereco-entrega-cobranca-erp-pj-clientes').change(function() {
        var chekced = this.checked;
        if ($('#copiar-endereco-principal-cobranca-erp-pj-clientes').prop('checked')) {
          $('#copiar-endereco-principal-cobranca-erp-pj-clientes').prop('checked', false);
        }
        $('#cep-cobranca-erp-pj').val(chekced ? $('#cep-entrega-erp-pj').val() : '');
        $('#rua-cobranca-erp-pj').val(chekced ? $('#rua-entrega-erp-pj').val() : '');
        $('#complemento-cobranca-erp-pj').val(chekced ? $('#complemento-entrega-erp-pj').val() : '');
        $('#bairro-cobranca-erp-pj').val(chekced ? $('#bairro-entrega-erp-pj').val() : '');
        $('#cidade-cobranca-erp-pj').val(chekced ? $('#cidade-entrega-erp-pj').val() : '');
        $('#estado-cobranca-erp-pj').val(chekced ? $('#estado-entrega-erp-pj').val() : '');
    })

    $('#copiar-endereco-principal-entrega-erp-pj-clientes').change(function() {
        var chekced = this.checked;
        if ($('#copiar-endereco-cobranca-entrega-erp-pj-clientes').prop('checked')) {
          $('#copiar-endereco-cobranca-entrega-erp-pj-clientes').prop('checked', false);
        }
        $('#cep-entrega-erp-pj').val(chekced ? $('#cep-principal-erp-pj').val() : '');
        $('#rua-entrega-erp-pj').val(chekced ? $('#rua-principal-erp-pj').val() : '');
        $('#complemento-entrega-erp-pj').val(chekced ? $('#complemento-principal-erp-pj').val() : '');
        $('#bairro-entrega-erp-pj').val(chekced ? $('#bairro-principal-erp-pj').val() : '');
        $('#cidade-entrega-erp-pj').val(chekced ? $('#cidade-principal-erp-pj').val() : '');
        $('#estado-entrega-erp-pj').val(chekced ? $('#estado-principal-erp-pj').val() : '');
    })

    $('#copiar-endereco-cobranca-entrega-erp-pj-clientes').change(function() {
        var chekced = this.checked;
        if ($('#copiar-endereco-principal-entrega-erp-pj-clientes').prop('checked')) {
          $('#copiar-endereco-principal-entrega-erp-pj-clientes').prop('checked', false);
        }
        $('#cep-entrega-erp-pj').val(chekced ? $('#cep-cobranca-erp-pj').val() : '');
        $('#rua-entrega-erp-pj').val(chekced ? $('#rua-cobranca-erp-pj').val() : '');
        $('#complemento-entrega-erp-pj').val(chekced ? $('#complemento-cobranca-erp-pj').val() : '');
        $('#bairro-entrega-erp-pj').val(chekced ? $('#bairro-cobranca-erp-pj').val() : '');
        $('#cidade-entrega-erp-pj').val(chekced ? $('#cidade-cobranca-erp-pj').val() : '');
        $('#estado-entrega-erp-pj').val(chekced ? $('#estado-cobranca-erp-pj').val() : '');
    })
        
    $("#cep-principal-pj").on("blur", function(e){
        try{
            let cep = this.value.replace(".","").replace("-","")
            $.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
                if (!("erro" in endereco)) {
                    $("#bairro-principal-pj").val(endereco.bairro)
                    $("#cidade-principal-pj").val(endereco.localidade)
                    $("#estado-principal-pj").val(endereco.uf)
                    $("#rua-principal-pj").val(endereco.logradouro)
                    $("#complemento-principal-pj").val(endereco.complemento)

                } else{
                }
            })
        }catch(exception){

        }
    })

    $("#cep-entrega-pj").on("blur", function(e){
        try{
            let cep = this.value.replace(".","").replace("-","")
            $.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
                if (!("erro" in endereco)) {
                    $("#bairro-entrega-pj").val(endereco.bairro)
                    $("#cidade-entrega-pj").val(endereco.localidade)
                    $("#estado-entrega-pj").val(endereco.uf)
                    $("#rua-entrega-pj").val(endereco.logradouro)
                    $("#complemento-entrega-pj").val(endereco.complemento)

                } else{
                }
            })
        }catch(exception){

        }
    })

    $("#cep-cobranca-pj").on("blur", function(e){
        try{
            let cep = this.value.replace(".","").replace("-","")
            $.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
                if (!("erro" in endereco)) {
                    $("#bairro-cobranca-pj").val(endereco.bairro)
                    $("#cidade-cobranca-pj").val(endereco.localidade)
                    $("#estado-cobranca-pj").val(endereco.uf)
                    $("#rua-cobranca-pj").val(endereco.logradouro)
                    $("#complemento-cobranca-pj").val(endereco.complemento)

                } else{
                }
            })
        }catch(exception){

        }
    })    

    $("#cep-principal-pf").on("blur", function(e){
        try{
            let cep = this.value.replace(".","").replace("-","")
            $.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
                if (!("erro" in endereco)) {
                    $("#bairro-principal-pf").val(endereco.bairro)
                    $("#cidade-principal-pf").val(endereco.localidade)
                    $("#estado-principal-pf").val(endereco.uf)
                    $("#rua-principal-pf").val(endereco.logradouro)
                    $("#complemento-principal-pf").val(endereco.complemento)

                } else{
                }
            })
        }catch(exception){

        }
    })

    $("#cep-entrega-pf").on("blur", function(e){
        try{
            let cep = this.value.replace(".","").replace("-","")
            $.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
                if (!("erro" in endereco)) {
                    $("#bairro-entrega-pf").val(endereco.bairro)
                    $("#cidade-entrega-pf").val(endereco.localidade)
                    $("#estado-entrega-pf").val(endereco.uf)
                    $("#rua-entrega-pf").val(endereco.logradouro)
                    $("#complemento-entrega-pf").val(endereco.complemento)

                } else{
                }
            })
        }catch(exception){

        }
    })

    $("#cep-cobranca-pf").on("blur", function(e){
        try{
            let cep = this.value.replace(".","").replace("-","")
            $.getJSON(`https://viacep.com.br/ws/${cep}/json`, function(endereco) {
                if (!("erro" in endereco)) {
                    $("#bairro-cobranca-pf").val(endereco.bairro)
                    $("#cidade-cobranca-pf").val(endereco.localidade)
                    $("#estado-cobranca-pf").val(endereco.uf)
                    $("#rua-cobranca-pf").val(endereco.logradouro)
                    $("#complemento-cobranca-pf").val(endereco.complemento)

                } else{
                }
            })
        }catch(exception){

        }
    })

    function buscarCliente(documento){
        var nomeVendedor = '';
        if (documento){
            $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/resumoClienteCRM');?>",
                type: "POST",
                data: {
                    documento: documento
                },
                success: function(data){
                    resposta = JSON.parse(data)
                    if (resposta?.status === 200){
                        if (resposta?.dados?.Message){
                            tabelaClientePorVendedor.clear().draw();
                            
                            var array = {
                                'nome' : resposta?.dados?.Message?.name ? (resposta?.dados?.Message?.sobrenome ? resposta?.dados?.Message?.name + ' ' + resposta?.dados?.Message?.sobrenome : resposta?.dados?.Message?.name) : '',
                                'nomeFantasia' : resposta?.dados?.Message?.nome_fantasia ? resposta?.dados?.Message?.nome_fantasia : '',
                                'cpfCnpj' : resposta?.dados?.Message?.documento ? resposta?.dados?.Message?.documento : '',
                                'dataNascimento' : resposta?.dados?.Message?.dataNascimento ? new Date(resposta?.dados?.Message?.dataNascimento).toLocaleDateString(    ) : '',
                                'telefone' : (resposta?.dados?.Message?.ddd_telefone && resposta?.dados?.Message?.telefone) ? '(' + resposta?.dados?.Message?.ddd_telefone + ')' + ' ' + resposta?.dados?.Message?.telefone : '',
                                'email' : resposta?.dados?.Message?.email ? resposta?.dados?.Message?.email : '',
                                'loginVendedor' : resposta?.dados?.Message?.loginVendedor ? resposta?.dados?.Message?.loginVendedor : '',
                            }
                            tabelaClientePorVendedor.row.add(array).draw();
                            document.getElementById('loading').style.display = 'none';
                            formCliente = true;
                            $("#tab-oportunidades").click();
                            $("#open-modal").click();
                            $("#documentoClienteOportunidade").val(documento);
                        }
                    }else if(resposta?.status === 500){
                        document.getElementById('loading').style.display = 'none';
                        tabelaClientePorVendedor.clear().draw();
                        limparClienteForm();
                        alert("Cliente não cadastrado! Vamos lhe direcionar para a página de cadastro.")
                        $("#modalCadastroCliente").modal('show');

                        if(documento.length > 14){
                            $("#formCadastroPF").hide();
                            $("#formCadastroPJ").show();
                        }else{
                            $("#formCadastroPF").show();
                            $("#formCadastroPJ").hide();
                        }

                    }

                },
                error: function (resposta){
                    tabelaClientePorVendedor.clear().draw();
                    document.getElementById('loading').style.display = 'none';
                }

            })
        }else{
            document.getElementById('loading').style.display = 'none';
        }
    }

    async function editCliente(botao, documento, event){
        event.preventDefault();

        $('#tab-endereco-principal').click();

        var digitosDocumento = documento.replace(/\D/g, '');
        if (digitosDocumento.length == 11){
            $('#divnomeFantasiaCliente').hide();
            $('#nomeFantasiaClienteEditar').attr('required', false);
            $('#divSobrenomeCliente').show();
            $('#sobrenomeClienteEditar').attr('required', true);
            $('#divdataNascimentoCliente').show();
            $('#divinscricaoEstadualCliente').hide();
            $('#inscricaoEstadualClienteEditar').attr('required', false);
            $('#divSignatario').hide();
            $('#divSignatario2').hide();
        }else if (digitosDocumento.length == 14){
            $('#divnomeFantasiaCliente').show();
            $('#nomeFantasiaClienteEditar').attr('required', true);
            $('#divSobrenomeCliente').hide();
            $('#sobrenomeClienteEditar').attr('required', false);
            $('#divdataNascimentoCliente').hide();
            $('#divinscricaoEstadualCliente').show();
            $('#inscricaoEstadualClienteEditar').attr('required', true);

            $('#divSignatario').show();
            $('#divSignatario2').show();
        }

        botao = $(botao);
        botao.attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>')
        if (documento){
            await $.ajax({
                url: "<?php echo site_url('ComerciaisTelevendas/Pedidos/resumoClienteCRM');?>",
                type: "POST",
                data: {
                    documento: documento
                },
                success: function(data){
                    resposta = JSON.parse(data)
                    if (resposta?.status === 200){
                        if (resposta?.dados?.Message){
                            $('#nomeClienteEditar').val(resposta?.dados?.Message?.name ? resposta?.dados?.Message?.name : '');
                            $('#sobrenomeClienteEditar').val(resposta?.dados?.Message?.sobrenome ? resposta?.dados?.Message?.sobrenome : '');
                            $('#nomeFantasiaClienteEditar').val(resposta?.dados?.Message?.nome_fantasia ? resposta?.dados?.Message?.nome_fantasia : '');
                            $('#emailClienteEditar').val(resposta?.dados?.Message?.email ? resposta?.dados?.Message?.email : '');
                            $('#loginVendedorClienteEditar').val(resposta?.dados?.Message?.loginVendedor ? resposta?.dados?.Message?.loginVendedor : '');
                            $('#documentoClienteEditar').val(resposta?.dados?.Message?.documento ? resposta?.dados?.Message?.documento : '');
                            $('#dataNascimentoClienteEditar').val(resposta?.dados?.Message?.dataNascimento ? new Date(resposta?.dados?.Message?.dataNascimento).toISOString().slice(0,10) : '');
                            $('#inscricaoEstadualClienteEditar').val(resposta?.dados?.Message?.inscricao_estadual ? resposta?.dados?.Message?.inscricao_estadual : '');
                            $('#dddClienteEditar').val(resposta?.dados?.Message?.ddd_telefone ? resposta?.dados?.Message?.ddd_telefone : '');
                            $('#telefoneClienteEditar').val(resposta?.dados?.Message?.telefone ? resposta?.dados?.Message?.telefone : '');
                            $('#dddClienteEditar2').val(resposta?.dados?.Message?.ddd_telefone2 ? resposta?.dados?.Message?.ddd_telefone2 : '');
                            $('#telefoneClienteEditar2').val(resposta?.dados?.Message?.telefone2 ? resposta?.dados?.Message?.telefone2 : '');
                            $('#signatarioClienteEditar').val(resposta?.dados?.Message?.signatario_mei ? resposta?.dados?.Message?.signatario_mei : '');
                            $('#emailSignatarioClienteEditar').val(resposta?.dados?.Message?.email_signatario_mei ? resposta?.dados?.Message?.email_signatario_mei : '');
                            $('#documentoSignatarioClienteEditar').val(resposta?.dados?.Message?.documento_signatario_mei ? resposta?.dados?.Message?.documento_signatario_mei : '');
                            $('#cepPrincipalClienteEditar').val(resposta?.dados?.Message?.endereco_principal?.cep ? resposta?.dados?.Message?.endereco_principal?.cep : '');
                            $('#ruaPrincipalClienteEditar').val(resposta?.dados?.Message?.endereco_principal?.rua ? resposta?.dados?.Message?.endereco_principal?.rua : '');
                            $('#complementoPrincipalClienteEditar').val(resposta?.dados?.Message?.endereco_principal?.complemento ? resposta?.dados?.Message?.endereco_principal?.complemento : '');
                            $('#bairroPrincipalClienteEditar').val(resposta?.dados?.Message?.endereco_principal?.bairro ? resposta?.dados?.Message?.endereco_principal?.bairro : '');
                            $('#cidadePrincipalClienteEditar').val(resposta?.dados?.Message?.endereco_principal?.cidade ? resposta?.dados?.Message?.endereco_principal?.cidade : '');
                            $('#estadoPrincipalClienteEditar').val(resposta?.dados?.Message?.endereco_principal?.estado ? resposta?.dados?.Message?.endereco_principal?.estado : '');
                            $('#cepCobrancaClienteEditar').val(resposta?.dados?.Message?.endereco_cobranca?.cep ? resposta?.dados?.Message?.endereco_cobranca?.cep : '');
                            $('#ruaCobrancaClienteEditar').val(resposta?.dados?.Message?.endereco_cobranca?.rua ? resposta?.dados?.Message?.endereco_cobranca?.rua : '');
                            $('#complementoCobrancaClienteEditar').val(resposta?.dados?.Message?.endereco_cobranca?.complemento ? resposta?.dados?.Message?.endereco_cobranca?.complemento : '');
                            $('#bairroCobrancaClienteEditar').val(resposta?.dados?.Message?.endereco_cobranca?.bairro ? resposta?.dados?.Message?.endereco_cobranca?.bairro : '');
                            $('#cidadeCobrancaClienteEditar').val(resposta?.dados?.Message?.endereco_cobranca?.cidade ? resposta?.dados?.Message?.endereco_cobranca?.cidade : '');
                            $('#estadoCobrancaClienteEditar').val(resposta?.dados?.Message?.endereco_cobranca?.estado ? resposta?.dados?.Message?.endereco_cobranca?.estado : '');
                            $('#cepEntregaClienteEditar').val(resposta?.dados?.Message?.endereco_entrega?.cep ? resposta?.dados?.Message?.endereco_entrega?.cep : '');
                            $('#ruaEntregaClienteEditar').val(resposta?.dados?.Message?.endereco_entrega?.rua ? resposta?.dados?.Message?.endereco_entrega?.rua : '');
                            $('#complementoEntregaClienteEditar').val(resposta?.dados?.Message?.endereco_entrega?.complemento ? resposta?.dados?.Message?.endereco_entrega?.complemento : '');
                            $('#bairroEntregaClienteEditar').val(resposta?.dados?.Message?.endereco_entrega?.bairro ? resposta?.dados?.Message?.endereco_entrega?.bairro : '');
                            $('#cidadeEntregaClienteEditar').val(resposta?.dados?.Message?.endereco_entrega?.cidade ? resposta?.dados?.Message?.endereco_entrega?.cidade : '');
                            $('#estadoEntregaClienteEditar').val(resposta?.dados?.Message?.endereco_entrega?.estado ? resposta?.dados?.Message?.endereco_entrega?.estado : '');
                        }
                        botao.attr('disabled', false).html('<i class="fa fa-pencil"></i>');
                    }
                },
                error: function (resposta){
                    alert("Erro ao buscar dados do cliente.Tente novamente.");
                    botao.attr('disabled', false).html('<i class="fa fa-pencil"></i>');
                }
            })
        }

        var input = document.getElementsByClassName('inputEnd');
        for (var i = 0; i < input.length; i++) {

            input[i].addEventListener('input', function() {
              if (this.value.trim() === '') {
                this.classList.add('empty');
              } else {
                this.classList.remove('empty');
              }
          
          
            });
        
            if (input[i].value.trim() === '') {
                input[i].classList.add('empty');
            }
        }
        $('#modalEditCliente').modal('show');
    }

    $('#modalEditCliente').on('hidden.bs.modal', function () {
        var input = document.getElementsByClassName('inputEnd');
        for (var i = 0; i < input.length; i++) {
            input[i].classList.remove('empty');
        }

        $('#formEditCliente').trigger('reset');
    })

    $('#formEditCliente').submit(function(e){
        e.preventDefault();
        var idDivs = ["divEnderecoPrincipal", "divEnderecoCobranca", "divEnderecoEntrega"];
        var camposVazios = false;
        var data = getDadosForm('formEditCliente');

        idDivs.forEach(idDiv => {
            if (verificarCamposEndereco(idDiv)) {
                camposVazios = true;
            }
        });

        if (camposVazios) {
            alert('Preencha os campos obrigatórios de endereço');
            return;
        }else{
            $('#btnEditarCadCliente').attr('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
            $.ajax({
                url: '<?= site_url('ComerciaisTelevendas/Pedidos/atualizarClienteERP') ?>',
                type: "POST",
                data: data,
                success: function(resposta){
                    resposta = JSON.parse(resposta)
                    if (resposta?.Status === 200){
                        alert(resposta?.Message);
                        $('#modalEditCliente').modal('hide');
                        $('#btnEditarCadCliente').attr('disabled', false).html('Editar');
                        tabelaClientePorVendedor.ajax.reload();
                    } else {
                        alert("Erro ao atualizar cliente. Tente novamente.");
                        $('#btnEditarCadCliente').attr('disabled', false).html('Editar');
                    }
                },
                error: function (resposta){
                    alert("Erro ao atualizar cliente. Tente novamente.");
                    $('#btnEditarCadCliente').attr('disabled', false).html('Editar');
                },
                done : function(){
                    $('#btnEditarCadCliente').attr('disabled', false).html('Editar');
                }
            })
        }
    })

    $('#tab-endereco-principal').click(function() {
        $('#divEnderecoPrincipal').show();
        $('#divEnderecoCobranca').hide();
        $('#divEnderecoEntrega').hide();
    });

    $('#tab-endereco-cobranca').click(function() {
        $('#divEnderecoPrincipal').hide();
        $('#divEnderecoCobranca').show();
        $('#divEnderecoEntrega').hide();
    });

    $('#tab-endereco-entrega').click(function() {
        $('#divEnderecoPrincipal').hide();
        $('#divEnderecoCobranca').hide();
        $('#divEnderecoEntrega').show();
    });

    function verificarCamposEndereco(divId) {
        const div = document.getElementById(divId);
        const elementosInputs = div.querySelectorAll(".inputEnd");
        let vazio = false;

        elementosInputs.forEach(input => {
            const id = input.id;
            const dado = $('#' + id).val();
            if (dado === '') {
                vazio = true;
            }
        });

        return vazio;
    }

    $('#cepPrincipalClienteEditar').on('blur', function() {
        var cep = $(this).val();
        cep = cep.replace(".", "").replace("-", "");

        if (cep.length === 8) {
            try{
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    success: function(resposta) {
                        if (!resposta.erro) {
                            $('#ruaPrincipalClienteEditar').val(resposta.logradouro);
                            $('#bairroPrincipalClienteEditar').val(resposta.bairro);
                            $('#cidadePrincipalClienteEditar').val(resposta.localidade);
                            $('#estadoPrincipalClienteEditar').val(resposta.uf);
                        }
                    },
                    error: function(resposta) {
                        alert('Não foi possível buscar o CEP. Tente novamente');
                    }
                });
            }catch(exception){
                alert('Não foi possível buscar o CEP. Tente novamente');
            }
        }else{
            alert('Preencha o CEP corretamente');
        }
    });

    $('#cepCobrancaClienteEditar').on('blur', function() {
        var cep = $(this).val();
        cep = cep.replace(".", "").replace("-", "");

        if (cep.length === 8) {
            try{
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    success: function(resposta) {
                        if (!resposta.erro) {
                            $('#ruaCobrancaClienteEditar').val(resposta.logradouro);
                            $('#bairroCobrancaClienteEditar').val(resposta.bairro);
                            $('#cidadeCobrancaClienteEditar').val(resposta.localidade);
                            $('#estadoCobrancaClienteEditar').val(resposta.uf);
                        }
                    },
                    error: function(resposta) {
                        alert('Não foi possível buscar o CEP. Tente novamente');
                    }
                });
            }catch(exception){
                alert('Não foi possível buscar o CEP. Tente novamente');
            }
        }else{
            alert('Preencha o CEP corretamente');
        }
    });

    $('#cepEntregaClienteEditar').on('blur', function() {
        var cep = $(this).val();
        cep = cep.replace(".", "").replace("-", "");

        if (cep.length === 8) {
            try{
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: 'GET',
                    success: function(resposta) {
                        if (!resposta.erro) {
                            $('#ruaEntregaClienteEditar').val(resposta.logradouro);
                            $('#bairroEntregaClienteEditar').val(resposta.bairro);
                            $('#cidadeEntregaClienteEditar').val(resposta.localidade);
                            $('#estadoEntregaClienteEditar').val(resposta.uf);
                        }
                    },
                    error: function(resposta) {
                        alert('Não foi possível buscar o CEP. Tente novamente');
                    }
                });
            }catch(exception){
                alert('Não foi possível buscar o CEP. Tente novamente');
            }
        }else{
            alert('Preencha o CEP corretamente');
        }
    });

    $('#copiar-endereco-cobranca-principal').change(function() {

        var checked = this.checked;

        if ($('#copiar-endereco-entrega-principal').prop('checked')) {
            $('#copiar-endereco-entrega-principal').prop('checked', false);
        }

        if (!checked)
            return;

        if($('#cepCobrancaClienteEditar').val() == "" || $('#ruaCobrancaClienteEditar').val() == "" || $('#bairroCobrancaClienteEditar').val() == "" || $('#cidadeCobrancaClienteEditar').val() == "" || $('#estadoCobrancaClienteEditar').val() == ""){
            alert("Endereço cobrança possui campos vazios");
            $(this).prop('checked', false)
            return;
        }

        $('#cepPrincipalClienteEditar').val(checked ? $('#cepCobrancaClienteEditar').val() : '');
        $('#ruaPrincipalClienteEditar').val(checked ? $('#ruaCobrancaClienteEditar').val() : '');
        $('#complementoPrincipalClienteEditar').val(checked ? $('#complementoCobrancaClienteEditar').val() : '');
        $('#bairroPrincipalClienteEditar').val(checked ? $('#bairroCobrancaClienteEditar').val() : '');
        $('#cidadePrincipalClienteEditar').val(checked ? $('#cidadeCobrancaClienteEditar').val() : '');
        $('#estadoPrincipalClienteEditar').val(checked ? $('#estadoCobrancaClienteEditar').val() : '');
    });

    $('#copiar-endereco-entrega-principal').change(function() {

        var checked = this.checked;

        if ($('#copiar-endereco-cobranca-principal').prop('checked')) {
            $('#copiar-endereco-cobranca-principal').prop('checked', false);
        }

        if (!checked)
            return;

        if($('#cepEntregaClienteEditar').val() == "" || $('#ruaEntregaClienteEditar').val() == "" || $('#bairroEntregaClienteEditar').val() == "" || $('#cidadeEntregaClienteEditar').val() == "" || $('#estadoEntregaClienteEditar').val() == ""){
            alert("Endereço entrega possui campos vazios");
            $(this).prop('checked', false)
            return;
        }

        $('#cepPrincipalClienteEditar').val(checked ? $('#cepEntregaClienteEditar').val() : '');
        $('#ruaPrincipalClienteEditar').val(checked ? $('#ruaEntregaClienteEditar').val() : '');
        $('#complementoPrincipalClienteEditar').val(checked ? $('#complementoEntregaClienteEditar').val() : '');
        $('#bairroPrincipalClienteEditar').val(checked ? $('#bairroEntregaClienteEditar').val() : '');
        $('#cidadePrincipalClienteEditar').val(checked ? $('#cidadeEntregaClienteEditar').val() : '');
        $('#estadoPrincipalClienteEditar').val(checked ? $('#estadoEntregaClienteEditar').val() : '');
    });

    $('#copiar-endereco-principal-cobranca').change(function() {

        var checked = this.checked;

        if ($('#copiar-endereco-entrega-cobranca').prop('checked')) {
            $('#copiar-endereco-entrega-cobranca').prop('checked', false);
        }

        if (!checked)
            return;

        if($('#cepPrincipalClienteEditar').val() == "" || $('#ruaPrincipalClienteEditar').val() == "" || $('#bairroPrincipalClienteEditar').val() == "" || $('#cidadePrincipalClienteEditar').val() == "" || $('#estadoPrincipalClienteEditar').val() == ""){
            alert("Endereço principal possui campos vazios");
            $(this).prop('checked', false)
            return;
        }

        $('#cepCobrancaClienteEditar').val(checked ? $('#cepPrincipalClienteEditar').val() : '');
        $('#ruaCobrancaClienteEditar').val(checked ? $('#ruaPrincipalClienteEditar').val() : '');
        $('#complementoCobrancaClienteEditar').val(checked ? $('#complementoPrincipalClienteEditar').val() : '');
        $('#bairroCobrancaClienteEditar').val(checked ? $('#bairroPrincipalClienteEditar').val() : '');
        $('#cidadeCobrancaClienteEditar').val(checked ? $('#cidadePrincipalClienteEditar').val() : '');
        $('#estadoCobrancaClienteEditar').val(checked ? $('#estadoPrincipalClienteEditar').val() : '');
    });

    $('#copiar-endereco-entrega-cobranca').change(function() {

        var checked = this.checked;

        if ($('#copiar-endereco-principal-cobranca').prop('checked')) {
            $('#copiar-endereco-principal-cobranca').prop('checked', false);
        }

        if (!checked)
            return;

        if($('#cepEntregaClienteEditar').val() == "" || $('#ruaEntregaClienteEditar').val() == "" || $('#bairroEntregaClienteEditar').val() == "" || $('#cidadeEntregaClienteEditar').val() == "" || $('#estadoEntregaClienteEditar').val() == ""){
            alert("Endereço entrega possui campos vazios");
            $(this).prop('checked', false)
            return;
        }

        $('#cepCobrancaClienteEditar').val(checked ? $('#cepEntregaClienteEditar').val() : '');
        $('#ruaCobrancaClienteEditar').val(checked ? $('#ruaEntregaClienteEditar').val() : '');
        $('#complementoCobrancaClienteEditar').val(checked ? $('#complementoEntregaClienteEditar').val() : '');
        $('#bairroCobrancaClienteEditar').val(checked ? $('#bairroEntregaClienteEditar').val() : '');
        $('#cidadeCobrancaClienteEditar').val(checked ? $('#cidadeEntregaClienteEditar').val() : '');
        $('#estadoCobrancaClienteEditar').val(checked ? $('#estadoEntregaClienteEditar').val() : '');
    });

    $('#copiar-endereco-principal-entrega').change(function() {

        var checked = this.checked;

        if ($('#copiar-endereco-cobranca-entrega').prop('checked')) {
            $('#copiar-endereco-cobranca-entrega').prop('checked', false);
        }

        if (!checked)
            return;

        if($('#cepPrincipalClienteEditar').val() == "" || $('#ruaPrincipalClienteEditar').val() == "" || $('#bairroPrincipalClienteEditar').val() == "" || $('#cidadePrincipalClienteEditar').val() == "" || $('#estadoPrincipalClienteEditar').val() == ""){
            alert("Endereço principal possui campos vazios");
            $(this).prop('checked', false)
            return;
        }

        $('#cepEntregaClienteEditar').val(checked ? $('#cepPrincipalClienteEditar').val() : '');
        $('#ruaEntregaClienteEditar').val(checked ? $('#ruaPrincipalClienteEditar').val() : '');
        $('#complementoEntregaClienteEditar').val(checked ? $('#complementoPrincipalClienteEditar').val() : '');
        $('#bairroEntregaClienteEditar').val(checked ? $('#bairroPrincipalClienteEditar').val() : '');
        $('#cidadeEntregaClienteEditar').val(checked ? $('#cidadePrincipalClienteEditar').val() : '');
        $('#estadoEntregaClienteEditar').val(checked ? $('#estadoPrincipalClienteEditar').val() : '');
    });

    $('#copiar-endereco-cobranca-entrega').change(function() {

        var checked = this.checked;

        if ($('#copiar-endereco-principal-entrega').prop('checked')) {
            $('#copiar-endereco-principal-entrega').prop('checked', false);
        }

        if (!checked)
            return;

        if($('#cepCobrancaClienteEditar').val() == "" || $('#ruaCobrancaClienteEditar').val() == "" || $('#bairroCobrancaClienteEditar').val() == "" || $('#cidadeCobrancaClienteEditar').val() == "" || $('#estadoCobrancaClienteEditar').val() == ""){
            alert("Endereço cobrança possui campos vazios");
            $(this).prop('checked', false)
            return;
        }

        $('#cepEntregaClienteEditar').val(checked ? $('#cepCobrancaClienteEditar').val() : '');
        $('#ruaEntregaClienteEditar').val(checked ? $('#ruaCobrancaClienteEditar').val() : '');
        $('#complementoEntregaClienteEditar').val(checked ? $('#complementoCobrancaClienteEditar').val() : '');
        $('#bairroEntregaClienteEditar').val(checked ? $('#bairroCobrancaClienteEditar').val() : '');
        $('#cidadeEntregaClienteEditar').val(checked ? $('#cidadeCobrancaClienteEditar').val() : '');
        $('#estadoEntregaClienteEditar').val(checked ? $('#estadoCobrancaClienteEditar').val() : '');
    });
</script>



<style>

input:invalid {
    border-color: red !important;
}

input:valid {
    border-color: green !important;
}

#integracaoERPPJ input:valid {
    border-color: green !important;
}

#integracaoERPPJ input:invalid {
    border-color: red !important;
}
#integracaoERPPF input:valid {
    border-color: green !important;
}

#integracaoERPPF input:invalid {
    border-color: red !important;
}

.dropdown {
    position: relative;
}

.dropdown-content {
    display: none;
    position: absolute;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0);
    z-index: 1;
}

.dropdown-content a {
    text-decoration: none;
    display: block;
}

.show {
    display: block;
}
</style>
