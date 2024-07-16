<form id="formEditUser">
    <input type="hidden" name="id_cliente" value="<?= $cliente->id?>">
    <input type="hidden" name="code" value="<?= $usuario->code?>">

    <?php if ($this->auth->is_allowed_block('edit_cliente_usuario') && !$isContratos):?>
    
        <div class="alert alert-info col-md-12" style="margin-bottom: 50px;">    
            <div class ="col-md-12">
                <p class ="col-md-12">Informe o Cliente caso queira alterar</p>
            </div>
        </div>
        <div class="row">
            <div id ="divBuscarPor"class="col-md-2 col-sm-2 form-group  style="border-left: 3px solid #03A9F4"bord" >
                <label>Pesquisar por:</label>
                <select class="form-control input-sm" name="tipo-busca" id="tipo-busca">
                    <option value="0">Id</option>
                    <option value="1">Nome</option>
                    <option value="2">Documento</option>
                </select>
            </div>
            <div id ="divNomeCliente" class="col-md-8 col-sm-6" style="border-left: 8px solid #03A9F4;">
                <label>Cliente:</label>
                <select class="form-control input-sm" name="clientecode" id="cliente" type="text" style="width: 100%;">
                </select>
            </div>
            <div id ="divDocCliente" class="col-md-6 col-sm-6 bord" hidden>
                <label>Cliente:</label>
                <input class="form-control input-sm" id="clienteDoc" type="text" placeholder="Digite o CPF/CNPJ do cliente">
                </input>
                <input type="hidden" name="clienteDoccode" id="clienteDoccode">
            </div>
            <div id ="divIdCliente" class="col-md-6 col-sm-6 bord" style="display: none;">
                <label>Cliente:</label>
                <input class="form-control input-sm" id="clienteId" type="text" placeholder="Digite o ID do cliente">
                </input>
                <input type="hidden" name="clienteIdcode" id="clienteIdcode">
            </div>

            <div id ="divBtnPesquisaCliente" class="col-md-2 col-sm-2" style="display: contents;" hidden>
                <button id="btnPesquisaClienteDoc" class="btn btn-primary" type="button" style="margin-top: 23px;">Buscar</button>
                <button id="btnLimparPesquisaClienteDoc" class="btn btn-danger" type="button" style="margin-top: 23px;">Limpar</button>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="form-group col-md-12">
                <label>Cliente:</label>
                <input class="form-control" value="<?php echo $cliente->nome?>" disabled>
            </div>
        </div>
    <?php endif ?> 

    <div class="row">
        <div class="form-group col-md-6">
            <label>Nome:</label>
            <input class="form-control" type="text" name="nome_usuario" id="nome_usuario" value="<?php echo $usuario->nome_usuario;?>" required />
        </div>
        <div class="form-group col-md-6">
            <label>Email:</label>
            <input class="form-control" type="email" name="usuario" id="usuario" value="<?php echo $usuario->usuario?>"required />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label class="control-label">Senha:</label>
            <input class="form-control" type="password" name="senha" id="senha" placeholder="*********" />
        </div>
        <div class="form-group col-md-6">
            <label>CPF:</label>
            <input type="text" name="cpf" class="form-control cpf" id="cpfs" value="<?php echo $usuario->cpf?>" required />
        </div>

    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label>Função:</label>
            <select class="form-control" name="tipo_usuario" id="tipo_usuario" required>
                <option value="">Escolha uma opção</option>
                <option value="administrador" <?php echo set_selecionado('administrador', $usuario->tipo_usuario, 'selected')?>>Administração</option>
                <option value="monitoramento" <?php echo set_selecionado('monitoramento', $usuario->tipo_usuario, 'selected')?>>Monitoramento</option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label>Tel.:</label>
            <input type="text" class="form-control tel" name="celular" id="celular" value="<?php echo $usuario->celular?>" required />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Base de Integração:</label>
            <select class="form-control" name="tipo_wstt" id="tipo_wstt" required>
                <option value="">Escolha uma opção</option>
                <option value="0" <?php echo set_selecionado('0', $usuario->tipo_wstt, 'selected')?>>Gestor + Lat + Sem Obtem Eventos</option>
                <option value="1" <?php echo set_selecionado('1', $usuario->tipo_wstt, 'selected')?>>MHS +Lat+ Sem Obtem Eventos</option>
                <option value="2" <?php echo set_selecionado('2', $usuario->tipo_wstt, 'selected')?>>Gestor + Lat2+ Sem Obtem Eventos</option>
                <option value="3" <?php echo set_selecionado('3', $usuario->tipo_wstt, 'selected')?>>Mhs + Lat2+ Sem Obtem Eventos</option>
                <option value="4" <?php echo set_selecionado('4', $usuario->tipo_wstt, 'selected')?>>Gestor + Lat + Com Obtem Eventos</option>
                <option value="5" <?php echo set_selecionado('5', $usuario->tipo_wstt, 'selected')?>>MHS +Lat+ Com Obtem Eventos</option>
                <option value="6" <?php echo set_selecionado('6', $usuario->tipo_wstt, 'selected')?>>Gestor + Lat2+ Com Obtem Eventos</option>
                <option value="7" <?php echo set_selecionado('7', $usuario->tipo_wstt, 'selected')?>>Mhs + Lat2+ Com Obtem Eventos</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-check col-md-6">
            <input class="form-check-input" type="checkbox" value="" id="exigir_duplo_fator_autenticacao" <?php if($usuario->duplo_fator_autenticacao != null) echo 'checked';?>>
            <label class="form-check-label" for="exigir_duplo_fator_autenticacao">
                <b>Exibir Duplo Fator de Autenticação</b>
            </label>
        </div>
        <div class="form-group col-md-6" id="row_tipo_autenticacao" <?php if($usuario->duplo_fator_autenticacao != null) echo 'style="display: block"'; else echo 'style="display: none"';?>>
            <label for="titulo"><?= lang('tipo_autenticacao') ?></label>
            <select class="form-control" name="duplo_fator_autenticacao" id="tipo_autenticacao">
                <option disabled <?php if($usuario->duplo_fator_autenticacao == null) echo 'selected' ?>></option>
                <option value="sms" <?php if($usuario->duplo_fator_autenticacao == 'sms') echo 'selected' ?>>SMS</option>
                <option value="email" <?php if($usuario->duplo_fator_autenticacao == 'email') echo 'selected' ?>>E-mail</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button id="btnEditUser" class="btn btn-primary">Salvar</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $(".cpf").mask("999.999.999-99");
        $(".tel").mask("(99) 99999-9999");

        $("#exigir_duplo_fator_autenticacao").change(function(){

            $("#tipo_autenticacao").val('');
            if($("#exigir_duplo_fator_autenticacao").is(':checked')){
                $("#row_tipo_autenticacao").css('display','block');
            }else{
                $("#row_tipo_autenticacao").css('display','none');
            }

        });

        $('#formEditUser').submit(function () {
            var button_editUser = $('#btnEditUser');
            var dados_edit = $(this).serialize();
            if($("#exigir_duplo_fator_autenticacao").is(':checked') && ($("#tipo_autenticacao").val() == "" || $("#tipo_autenticacao").val() == null)){
                alert("Selecione o Tipo de Autenticação");
                return false;
            }else{
                button_editUser.attr('disabled', 'true').html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
                $.ajax({
                    url: '<?= site_url('usuarios_gestor/ajaxEditUser') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: dados_edit,
                    success: function (callback) {
                        if (callback.status == 'OK') {
                            button_editUser.removeAttr('disabled').html('Salvar');
                            alert(callback.msg)
                            $('#view_usuario').modal('hide')
                            table_users.ajax.reload(null, false);
                        } else {
                            button_editUser.removeAttr('disabled').html('Salvar');
                            alert(callback.msg);
                        }
                    },
                    error: function (e){
                        button_editUser.removeAttr('disabled').html('<i class="icon-ok icon-white"></i> Salvar');
                        alert('Erro ao salvar, tente novamente!');
                    }
                });
            }
            return false;
        });  

        let tipo_user = '<?=$usuario->tipo_usuario;?>';

        if(tipo_user == 'MASTER' || tipo_user == 'master') {
            $('#tipo_usuario').attr('disabled','disabled');
            $('#nome_usuario').attr('disabled','disabled');
            $('#usuario').attr('disabled','disabled');
            $('#senha').attr('disabled','disabled');
            $('#cpfs').attr('disabled','disabled');
            $('#celular').attr('disabled','disabled');
            $('#tipo_wstt').attr('disabled','disabled');
            $('#exigir_duplo_fator_autenticacao').attr('disabled','disabled');     
        }
        
        $('#tipo-busca').change(function(){ 
            if ($(this).val() == 1){
                $('#clienteDoc').val('');
                $('#clienteDoc').attr('disabled', false);
                $('#clienteId').val('');
                $('#clienteId').attr('disabled', false);

                $('#divDocCliente').hide();
                $('#divBtnPesquisaCliente').hide();
                $('#divIdCliente').hide();
                $("#divNomeCliente").show();
                $("#cliente").select2({
                    ajax: {
                        url: '<?= site_url('MovimentosEstoque/buscar_cliente') ?>',
                        dataType: 'json',
                        delay: 1000,
                        type: 'GET',
                        data: function (params) {
                            return {
                                q: params.term,
                                tipoBusca: 'nome'
                            };
                        },
                    },
                    placeholder: "Digite o nome do cliente",
                    allowClear: true,
                    minimumInputLength: 3,
                    language: "pt-BR",
                    width: 'resolve',
                })
            }else if($(this).val() == 2){
                $('#cliente').val('');
                $('#clienteId').val('');
                $('#clienteId').attr('disabled', false);
                $("#divNomeCliente").hide();
                $('#divIdCliente').hide();
                $('#divBtnPesquisaCliente').show();
                $('#divDocCliente').show();

                $("#clienteDoc").inputmask({
                mask: ["999.999.999-99", "99.999.999/9999-99"],
                keepStatic: true,
                placeholder: " ",
                });
            }else if($(this).val() == 0){
                $('#cliente').val('');
                $('#clienteDoc').val('');
                $('#clienteDoc').attr('disabled', false);
                $("#divNomeCliente").hide();
                $('#divBtnPesquisaCliente').show();
                $('#divDocCliente').hide();
                $('#divIdCliente').show();
            }
        })
        
        $('#btnPesquisaClienteDoc').click(function(){
            if ($('#divDocCliente').is(':visible')){
                $('#clienteDoc').attr('disabled', true)
                $('#btnPesquisaClienteDoc')
                .html('<i class="fa fa-spinner fa-spin"></i>')
                .attr('disabled', true)

                var documento = $('#clienteDoc').val();
                if (documento != ''){
                    $.ajax({
                        url: '<?= site_url('MovimentosEstoque/buscar_cliente') ?>',
                        type: 'GET',
                        dataType: 'json',
                        data: {q: documento,
                            tipoBusca: 'cpfCnpj'},
                        success: function(data){
                            if(data.results.length){
                                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                                $('#clienteDoc').attr('disabled', false)
                                $('#clienteDoc').inputmask('remove')
                                $('#clienteDoc').val(data.results[0].text)
                                $('#clienteDoc').attr('disabled', true)
                                $('#clienteDoccode').val(data.results[0].id)
                                $('#cep').val(data.results[0].cep)
                                $('#endereco').val(data.results[0].endereco)
                                $('#uf').val(data.results[0].uf)
                                $('#bairro').val(data.results[0].bairro)
                                $('#cidade').val(data.results[0].cidade)
                                $('#tipo-orgao').val(data.results[0].orgao)
                                BuscarVeiculos();
                            }else{
                                alert('Cliente não ativo ou não encontrado')
                                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                                $('#clienteDoccode').val('')
                                $('#clienteDoc').val('')
                                $('#clienteDoc').attr('disabled', false)
                                $("#addveiculo").empty()
                                $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
                            }
                        }
                    })
                }else{
                    alert('Digite o cpf ou cnpj do cliente')
                    $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                    $('#clienteDoccode').val('')    
                    $('#clienteDoc').attr('disabled', false)
                    $("#addveiculo").empty()
                    $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
                }
            }else{
                $('#btnPesquisaClienteDoc')
                .html('<i class="fa fa-spinner fa-spin"></i>')
                .attr('disabled', true)
                var id = $('#clienteId').val();
                if (id != ''){
                    $.ajax({
                        url: '<?= site_url('MovimentosEstoque/buscar_cliente') ?>',
                        type: 'GET',
                        dataType: 'json',
                        data: {q: id,
                            tipoBusca: 'id'},
                        success: function(data){
                            if(data.results.length){
                                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                                $('#clienteId').attr('disabled', false)
                                $('#clienteId').val(data.results[0].text)
                                $('#clienteId').attr('disabled', true)
                                $('#clienteIdcode').val(data.results[0].id)
                                $('#cep').val(data.results[0].cep)
                                $('#endereco').val(data.results[0].endereco)
                                $('#uf').val(data.results[0].uf)
                                $('#bairro').val(data.results[0].bairro)
                                $('#cidade').val(data.results[0].cidade)
                                $('#tipo-orgao').val(data.results[0].orgao)
                                BuscarVeiculos();
                            }else{
                                alert('Cliente não ativo ou não encontrado')
                                $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                                $('#clienteId').val('')
                                $('#clienteId').attr('disabled', false)
                                $('#clienteIdcode').val('')
                                
                                $("#addveiculo").empty()
                                $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
                            }
                        }
                    })
                }else{
                    alert('Digite o id do cliente')
                    $('#btnPesquisaClienteDoc').html('Buscar').attr('disabled', false)
                    $('#clienteId').attr('disabled', false)
                    $('#clienteIdcode').val('')
                            
                    $("#addveiculo").empty()
                    $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
                }
            }
        })
        
        $('#btnLimparPesquisaClienteDoc').click(function(){
            $('#cep').val('')
            $('#endereco-expedicao').val('')
            $('#uf-expedicao').val('')
            $('#bairro-expedicao').val('')
            $('#cidade-expedicao').val('')
            $('#tipo-orgao-expedicao').val('')
            if ($('#divDocCliente').is(':visible')){
                $('#cliente-expedicaoDoc').val('')
                $('#cliente-expedicaoDoc').attr('disabled', false)
                $("#cliente-expedicaoDoc").inputmask({
                    mask: ["999.999.999-99", "99.999.999/9999-99"],
                    keepStatic: true,
                    placeholder: " ",
                    });
                $('#clienteDoccode').val('')
            }else{
                $('#cliente-expedicaoId').val('')
                $('#cliente-expedicaoId').attr('disabled', false)
                $('#clienteIdcode').val('')
            }
        })

        $('#cliente-expedicao').on('select2:unselecting ', function (e) {
            $("#addveiculo").empty()
            $("#addveiculo").append($('<option>', {value : 0, text : 'Selecione uma Opção' }).prop('disabled', true).prop('selected', true))
        });

        $('#tipo-busca').val(2).trigger('change');   

        // window.addEventListener('keypress', e => {
        //     if(e.keyCode === 13){
        //         verificar()
        //     }
        // })  
    });

</script>
