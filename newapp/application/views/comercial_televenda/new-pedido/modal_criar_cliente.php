<div class="modal" id="modalCadastroCliente" role="dialog" aria-labelledby="modalCadastroCliente" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 3px solid #03A9F4 !important">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div class="row">
                    <div class="col-md-10">
                        <h3 class="modal-title">Cadastro de Cliente</h3>
                    </div>
                    
                </div>
            </div>
            <div class="modal-body">
              <div class="row" id="radioTipoCliente">
                <div class="col-md-12 p-5">
                  <label style="margin-right: 5px">Tipo do cliente: </label>
                  <label style="margin-right: 10px">
                    <input type="radio" name="tipoCliente" value="pf" checked/>
                    PF
                  </label>

                  <label>
                    <input type="radio" name="tipoCliente" value="pj" />
                    PJ
                  </label>
                </div>
              </div>
                <form id="formCadastroPF" class="col-md-12">
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Nome:</label>
                            <input type="text" class="form-control" id="nome-form-pf" name="name" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Sobrenome:</label>
                            <input type="text" class="form-control" id="sobrenome-form-pf" name="sobrenome" value="" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">E-mail:</label>
                            <input type="text" class="form-control" id="email-form-pf" name="email" value="" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                
                        <label class="control-label">Login Vendedor:</label>
                            <input type="text" class="form-control" id="vendedor-form-pf" name="loginVendedor" value="<?= $this->auth->get_login_dados('email')?>" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Documento:</label>
                            <input type="text" class="form-control" id="documento-form-pf" name="documento" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <div class="row">
                                <div class="col-md-2">
                                    <Label class="control-label" style="display: flex;">DDD:</Label>
                                    <input type="text" class="form-control ddd" id="ddd-form-pf" name="ddd_telefone" required>
                                </div>
                                <div class="col-md-10">
                                    <Label class="control-label">Telefone:</Label>
                                    <input type="text" class="form-control fone" id="telefone-form-pf" name="telefone" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <hr>
                    <h4>Endereços</h4>
                    <div id="endereco-principal-form-pf">
                        <br>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label class="control-label">CEP:</label>
                                <input type="text" class="form-control cep" id="cep-principal-form-pf" name="cep_principal" required>
                            </div>
                            
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label class="control-label">Número / Complemento:</label>
                                <input type="text" class="form-control" id="complemento-principal-form-pf" name="complemento_principal" required>
                            </div>   
                        </div>
                        <span class="help help-block"></span>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label class="control-label">Rua:</label>
                                <input type="text" class="form-control" id="rua-principal-form-pf" name="rua_principal" required>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label class="control-label">Bairro:</label>
                                <input type="text" class="form-control" id="bairro-principal-form-pf" name="bairro_principal" required>
                            </div>
                        </div>
                        <span class="help help-block"></span>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label>Cidade:</label>
                                <input type="text" class="form-control" id="cidade-principal-form-pf" name="cidade_principal" required>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label>Estado:</label>
                                <input type="text" class="form-control" id="estado-principal-form-pf" name="estado_principal" required>
                            </div>
                        </div>      
                        <br>
                    </div> 
                </form>
                
                <form id="formCadastroPJ"  class="col-md-12"  hidden>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Nome:</label>
                            <input type="text" class="form-control" id="nome-form-pj" name="name" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Nome Fantasia:</label>
                            <input type="text" class="form-control" id="nome-fantasia-form-pj" name="nome_fantasia" value="" >
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">E-mail:</label>
                            <input type="text" class="form-control" id="email-form-pj" name="email" value="" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label class="control-label">Login Vendedor:</label>
                            <input type="text" class="form-control" id="vendedor-form-pj" name="loginVendedor" value="<?= $this->auth->get_login_dados('email')?>" required>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <div class="row">
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <label>Documento:</label>
                            <input type="text" class="form-control" id="documento-form-pj" name="documento" required>
                        </div>
                        <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                            <div class="row">
                                <div class="col-md-2">
                                    <Label class="control-label" style="display: flex;">DDD:</Label>
                                    <input type="text" class="form-control ddd" id="ddd-form-pj" name="ddd_telefone" required>
                                </div>
                                <div class="col-md-10">
                                    <Label class="control-label">Telefone:</Label>
                                    <input type="text" class="form-control fone" id="telefone-form-pj" name="telefone" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="help help-block"></span>
                    <hr>
                    <h4>Endereços</h4>
                    <div id="endereco-principal-form-pj">
                        <br>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label class="control-label">CEP:</label>
                                <input type="text" class="form-control cep" id="cep-principal-form-pj" name="cep_principal" required>
                            </div>
                            
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <label class="control-label">Número / Complemento:</label>
                                <input type="text" class="form-control" id="complemento-principal-form-pj" name="complemento_principal" required>
                            </div>  
                        </div>
                        <span class="help help-block"></span>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label class="control-label">Rua:</label>
                                <input type="text" class="form-control" id="rua-principal-form-pj" name="rua_principal" required>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label class="control-label">Bairro:</label>
                                <input type="text" class="form-control" id="bairro-principal-form-pj" name="bairro_principal" required>
                            </div>
                        </div>
                        <span class="help help-block"></span>
                        <div class="row">
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label>Cidade:</label>
                                <input type="text" class="form-control" id="cidade-principal-form-pj" name="cidade_principal" required>
                            </div>
                            <div class="col-md-6" style="border-left: 3px solid #03A9F4">
                                <span class="help help-block"></span>
                                <label>Estado:</label>
                                <input type="text" class="form-control" id="estado-principal-form-pj" name="estado_principal" required>
                            </div>
                        </div>
                        <br>
                    </div>
                </form>

                <span class="help help-block"></span>
                <br>
                <hr>
                <h4>Arquivos</h4>
                <hr> 
                <div class="row">
                    <div class="col-md-9">
                        <label for="filesForm" class="btn btn-default col-md-12">Selecione um Arquivo</label>
                        <input id="filesForm" type="file" class="btn btn-default"  style="visibility:hidden;" name="arquivos"/>
                    </div>
                    <div class="col-md-3" >
                        <button type="button" class="btn btn-primary col-md-12" title="Adicionar" id="AddArquivoForm"><i class="fa fa-plus"></i> Adicionar</button>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table-responsive table-bordered table" id="tableArquivosForm">
                            <thead>
                                <tr>
                                    <th>Nome do Arquivo</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit-form">Cadastrar Cliente</button>
                
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>