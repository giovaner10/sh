<style>
    .primary_color{
        color: #03A9F4;
    }
    .secondary_color{
        color: gray;
    }
    .danger_color{
        color: #F35353;
    }
    .success_color{
        color: #00c01f;
    }
    .margin_top{
        margin-top: 20px;
    }
    .select2-container .select2-selection--single{
        height: 35px !important;
        border-radius: 0 !important;
        border-color: #d2d6de !important;
    }
</style>
<link rel="stylesheet" type="text/css"  href="<?php echo base_url('media/calendario_agendamento/calendario_agendamento.css');?>">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<div class="row justify-content-center">
    <div class="col-sm-12">
        <h2 ><?= $titulo?></h2>
    </div>    
</div>

<div class="row">
    <div class="col-sm-2">
        
    </div>
</div>
<div class="row margin_top">
    <div class="col-sm-3">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="secondary_color">Calendário <span id="spinnner_titulo_calendario"></span></h4>
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-12">
                <!-- Calendário Dinâmico -->
                <div class="container_calendar">
                    <div class="calendar">
                        <div class="month">
                            <i class="fa fa-angle-double-left prev" aria-hidden="true"></i>
                            <div class="date">
                                <!-- Mês -->
                                <h4 id="title_month"></h4>
                                <!-- <p>Fri May 29, 2020</p> -->
                            </div>
                            
                            <i class="fa fa-angle-double-right next" aria-hidden="true"></i>
                        </div>
        
                        <div class="weekdays">
                            <div>Dom</div>
                            <div>Seg</div>
                            <div>Ter</div>
                            <div>Qua</div>
                            <div>Qui</div>
                            <div>Sex</div>
                            <div>Sab</div>
                        </div>
        
                        <div class="days"></div>
                    </div>
                </div><!-- End Calendário Dinâmico -->
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <div class="row">
            <div class="col-sm-4">
                <h4 class="secondary_color">Agendamentos <span id="diaExibido"></span></h4>
                <!-- Input que guarda a data que está sendo exibida pelo datatable no momento -->
                <input type="hidden" name="dataExibida" id="dataExibida">
            </div>
        </div>
        <table id="tabela_agendamentos" class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Serial</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Tipo</th>
                    <th>Situação</th>
                    <th>Ações</th>                    
                </tr>
            </thead>

            <tbody></tbody>
        </table>
    </div>    
</div>

<!-- Modal Cadastro/Edição Agend-->
<div class="modal fade" id="modalCadastroAgendamento" tabindex="-1" role="dialog" aria-labelledby="cadastroIscaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="cadastroIscaModalLabel"></h4>
        </div>
        <div class="modal-body">
            <form id="formCadastroIsca">
                <input type="hidden" name="id_agendamento" id="id_agendamento">
                <input type="hidden" name="edit_data_anterior_agendamento" id="edit_data_anterior_agendamento">
                <div class="row">
                    <div class="col-sm-3 form-group">
                        <label for="">Data</label>
                        <input type="date" class="form-control" name="dataAgendamento" id="dataAgendamento">
                    </div>
                    <div class="col-sm-3 form-group">
                        <label for="">Hora</label>
                        <input type="text" class="form-control" name="horaAgendamento" id="horaAgendamento">
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select class="form-control" name="tipo" id="tipo">
                                <option value="" selected></option>
                                <option value="instalacao">Instalação</option>
                                <option value="manutencao">Manutenção</option>
                                <option value="transferencia">Transferência</option>
                                <option value="retirada">Retirada</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">Serial</label>
                            <select name="serial" id="serial" style="width: 100%">
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Cliente</label>
                            <select name="cliente" id="cliente" style="width: 100%">
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Instalador</label>
                            <select name="instalador" id="instalador" style="width: 100%">
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-10 form-group">
                        <label for="rua">Rua</label>
                        <input type="text" class="form-control" name="rua" id="rua">
                    </div>
                    <div class="col-sm-2 form-group">
                        <label for="numero">Número</label>
                        <input type="text" class="form-control" name="numero" id="numero">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" class="form-control" name="bairro" id="bairro">
                    </div>
                    <div class="col-sm-4 form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" class="form-control" name="cidade" id="cidade">
                    </div>
                    <div class="col-sm-4 form-group">
                        <label for="uf">UF</label>
                        
                        <select class="form-control" name="uf" id="uf">
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                    </div>

                </div>

                <div class="row">  
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="descricao">Observação</label>
                            <textarea class="form-control" id="obs" name="obs" rows="3"></textarea>
                        </div>
                    </div>
                </div>

          </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="cadastrarIsca">Salvar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Cadastrar Isca-->
<div class="modal fade" id="modalVisualizarAgendamento" tabindex="-1" role="dialog" aria-labelledby="visualizarAgendamentoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Visualizar Agendamento</h4>
        </div>
        <div class="modal-body">
        <div class="row">
                    <div class="col-sm-3 form-group">
                        <label for="">Data</label>
                        <h4 id="showData"></h4>
                    </div>
                    <div class="col-sm-3 form-group">
                        <label for="">Hora</label>
                        <h4 id="showHora"></h4>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <h4 id="showTipo"></h4>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">Serial</label>
                            <h4 id="showSerial"></h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Cliente</label>
                            <h4 id="showCliente"></h4>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Instalador</label>
                            <h4 id="showInstalador"></h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label for="rua">Rua</label>
                        <h4 id="showRua"></h4>
                    </div>
                    <div class="col-sm-2 form-group">
                        <label for="numero">Número</label>
                        <h4 id="showNumero"></h4>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label for="bairro">Bairro</label>
                        <h4 id="showBairro"></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label for="cidade">Cidade</label>
                        <h4 id="showCidade"></h4>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="uf">UF</label>
                        <h4 id="showUF"></h4>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 form-group">
                        <label for="showSituacao">Situação</label>
                        <h4 id="showSituacao"></h4>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label for="numero">Status</label>
                        <h4 id="showStatus"></h4>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label for="showDataFim">Data Finalização</label>
                        <h4 id="showDataFim"></h4>
                    </div>
                </div>
                <div class="row">  
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="descricao">Observação</label>
                            <h4 id="showObs"></h4>
                        </div>
                    </div>
                </div>


        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <!-- <button type="button" class="btn btn-primary" id="cadastrarIsca">Salvar</button> -->
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.min.js"></script>


<!-- Estilo do botão status -->
<link rel="stylesheet" type="text/css" href="<?= base_url("media/css/toggle-button.css") ?>">
<!-- helper iscas -->
<script type="text/javascript" src="<?= base_url("media/js/helpers/iscas-helper.js") ?>"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript">
    // Selects
    $(document).ready(()=>{
        // Desabilita serial. A busca deve ser feita somente quando for selecionado o tipo
        $("#serial").prop('disabled',true);
        // abilita o serial quando o tipo for selecionado
        $("#tipo").change(()=>{
            if($("#tipo").val() == ''){
                $("#serial").prop('disabled',true);
            }
            else{
                $("#serial").prop('disabled',false);
            }
        })
        // Select Serial
        let selectSerial = $("#serial").select2({
            minimumInputLength: 3,
            ajax: {
                url: '<?php echo site_url('iscas/isca/ajaxGetIscaAgendamento') ?>',
                dataType: "json",
                type: "POST",
                delay: 250,
                data: function(params){

                    return{
                        searchTerm: params.term,
                        tipo: $("#tipo").val()
                    };
                    
                },
                processResults: function(data){
                    
                    return{
                        results: data
                    };
                },
                
                cache: true
            }
        });
        // Select Cliente
        $("#cliente").select2({
            minimumInputLength: 3,
            ajax: {
                url: '<?php echo site_url('iscas/isca/ajaxGetCliente') ?>',
                dataType: "json",
                type: "POST",
                delay: 250,
                data: function(params){
                    return{
                        searchTerm: params.term,
                    };
                },
                processResults: function(data){
                    
                    return{
                        results: data
                    };
                },
                cache: true
            }
        });
        // Select Instalador
        $("#instalador").select2({
            minimumInputLength: 3,
            ajax: {
                url: '<?php echo site_url('iscas/isca/ajaxGetInstalador') ?>',
                dataType: "json",
                type: "POST",
                delay: 250,
                data: function(params){
                    return{
                        searchTerm: params.term,
                    };
                },
                processResults: function(data){
                    
                    return{
                        results: data
                    };
                },
                cache: true
            }
        });

        $(".dt-buttons").prepend(`
            <button type="button" id="btnAbrirmodalCadastroAgendamento" class="btn btn-primary">
                <i class="fa fa-plus-square"></i>
                Novo Agendamento
            </button>
        `);
        $("#btnAbrirmodalCadastroAgendamento").click(()=>{
            limparModal();
            $("#cadastroIscaModalLabel").html("Cadastrar Agendamento");
            $("#modalCadastroAgendamento").modal("show");

        });
    });
    // Mask
    $(document).ready(function(){
        var mask = function (val) {
        val = val.split(":");
        return (parseInt(val[0]) > 19)? "HZ:M0:M0" : "H0:M0:M0";
        }

        pattern = {
            onKeyPress: function(val, e, field, options) {
                field.mask(mask.apply({}, arguments), options);
            },
            translation: {
                'H': { pattern: /[0-2]/, optional: false },
                'Z': { pattern: /[0-3]/, optional: false },
                'M': { pattern: /[0-5]/, optional: false }
            },
        };
        $('#horaAgendamento').mask(mask, pattern);
    });

    const months = [
        "Janeiro",
        "Fevereiro",
        "Março",
        "Abril",
        "Maio",
        "Junho",
        "Julho",
        "Agosto",
        "Setembro",
        "Outubro",
        "Novembro",
        "Dezembro"
    ];
    // dicionario que guarda o número de agendamentos por dia
    let num_agendamentos_por_dia = {};

    $(document).ready(function(){
        // Retorna os agendamentos do dia atual
        get_agendamentos_por_dia();

        let dia_atual = new Date();
        const stringYear = dia_atual.getFullYear();
        const stringMonth = parseInt(dia_atual.getMonth() + 1) < 10 ? '0' + (dia_atual.getMonth() + 1) : (dia_atual.getMonth() + 1);
        const stringDay = (parseInt(dia_atual.getDate()) < 10) ? '0'+parseInt(dia_atual.getDate()) : parseInt(dia_atual.getDate());
        const inicioMes = stringYear+'-'+stringMonth+'-01';
        const ultimoDiaDoMes = new Date(
            dia_atual.getFullYear(),
            dia_atual.getMonth() + 1,
            0
        ).getDate();
        const fimMes = stringYear+'-'+stringMonth+'-'+ultimoDiaDoMes;
        ajax_get_agendamentos(inicioMes, fimMes);
    });

    /**
     * Datatable
     */
    let tabela_agendamentos = $("#tabela_agendamentos").DataTable( {
            ordering:		true,
            scrollCollapse: true,
            paging:         true,
            dom: 'Bfrtip',
            columnDefs:[
                    { orderable: false, targets: [5,6] }
                ],
            initComplete: function () {
                $(document).on('click', '#gerar_pdf', function () {
                    $(".buttons-pdf").trigger("click");
                });

                $(document).on('click', '#gerar_excel', function () {
                    $(".buttons-excel").trigger("click");
                });

                $(document).on('click', '#gerar_csv', function () {
                    $(".buttons-csv").trigger("click");
                });

                $(document).on('click', '#gerar_print', function () {
                    $(".buttons-print").trigger("click");
                });
            },
            oLanguage: {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            },
            buttons: [
            {
                title: "Agendamentos",
                filename: "Agendamentos",
                messageTop: "",
                orientation: 'portrait',
                pageSize: 'LEGAL',
                extend: 'pdfHtml5',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5],
                    format: {
                        body: function(data, row, col, node){
                            const tipoElemento = node.firstChild;
                            
                            if(tipoElemento != null || tipoElemento != undefined){
                                // o único elemento da tabela que está dentro de uma div é o status
                                if(tipoElemento.nodeName == "DIV"){ 
                                    const element = tipoElemento.children.item(0);// elemento (select ou span)
                                    if(element != null){
                                        //se o elemento for um select, retorna o texto da opção selecionada
                                        if(element.type == 'select-one'){// select
                                            return (element.options[element.selectedIndex].text)
                                        }
                                        else{ // retorna o texto do spam
                                            return (element.textContent)
                                        }
                                    }
                                    
                                }
                                else{
                                    return data
                                }
                            }else{
                                return data;
                            }
                        }
                            
                    }
                },
                

                
            },
            {
                title: "Agendamentos",
                filename: "Agendamentos",
                messageTop: "",
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5],
                    format: {
                        body: function(data, row, col, node){
                            const tipoElemento = node.firstChild;
                            
                            if(tipoElemento != null || tipoElemento != undefined){
                                // o único elemento da tabela que está dentro de uma div é o status
                                if(tipoElemento.nodeName == "DIV"){ 
                                    const element = tipoElemento.children.item(0);// elemento (select ou span)
                                    
                                    if(element != null){
                                        //se o elemento for um select, retorna o texto da opção selecionada
                                        if(element.type == 'select-one'){// select
                                            return (element.options[element.selectedIndex].text)
                                        }
                                        else{ // retorna o texto do spam
                                            return (element.textContent)
                                        }
                                    }
                                    
                                }
                                else{
                                    return data
                                }
                            }else{
                                return data;
                            }
                        }
                            
                    }
                },

                
            },
            {
                title: "Agendamentos",
                filename: "Agendamentos",
                messageTop: "",
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5],
                    format: {
                        body: function(data, row, col, node){
                            const tipoElemento = node.firstChild;
                            
                            if(tipoElemento != null || tipoElemento != undefined){
                                // o único elemento da tabela que está dentro de uma div é o status
                                if(tipoElemento.nodeName == "DIV"){ 
                                    const element = tipoElemento.children.item(0);// elemento (select ou span)
                                    
                                    if(element != null){
                                        //se o elemento for um select, retorna o texto da opção selecionada
                                        if(element.type == 'select-one'){// select
                                            return (element.options[element.selectedIndex].text)
                                        }
                                        else{ // retorna o texto do spam
                                            return (element.textContent)
                                        }
                                    }
                                    
                                }
                                else{
                                    return data
                                }
                            }else{
                                return data;
                            }
                        }
                            
                    }
                },

            },
            {
                title: "Agendamentos",
                filename: "Agendamentos",
                messageTop: "",
                orientation: 'landscape',
                pageSize: 'LEGAL',
                extend: 'print',
                text: 'Imprimir',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5],
                    format: {
                        body: function(data, row, col, node){
                            const tipoElemento = node.firstChild;
                            
                            if(tipoElemento != null || tipoElemento != undefined){
                                // o único elemento da tabela que está dentro de uma div é o status
                                if(tipoElemento.nodeName == "DIV"){ 
                                    const element = tipoElemento.children.item(0);// elemento (select ou span)
                                    
                                    if(element != null){
                                        //se o elemento for um select, retorna o texto da opção selecionada
                                        if(element.type == 'select-one'){// select
                                            return (element.options[element.selectedIndex].text)
                                        }
                                        else{ // retorna o texto do spam
                                            return (element.textContent)
                                        }
                                    }
                                    
                                }
                                else{
                                    return data
                                }
                            }else{
                                return data;
                            }
                        }
                            
                    }
                },

            }
        ],
    });

    /**
     * Função que atualiza a tabela com os agendamentos do dia de acordo com o grupo selecionado
     */
    function get_agendamentos_por_dia(dia = null){
        
        // tabela_agendamentos
        if( $.fn.DataTable.isDataTable('#tabela_agendamentos')){
            // Remove linhas do datatable
            tabela_agendamentos.rows().remove().draw();
        }
        let data;
        
        $("#diaExibido").html('<i class="fa fa-spinner fa-spin"></i>');

        if(dia === null){
            let dia_atual = new Date();
            const stringYear = dia_atual.getFullYear();
            const stringMonth = parseInt(dia_atual.getMonth() + 1) < 10 ? '0' + (dia_atual.getMonth() + 1) : (dia_atual.getMonth() + 1);
            const stringDay = (parseInt(dia_atual.getDate()) < 10) ? '0'+parseInt(dia_atual.getDate()) : parseInt(dia_atual.getDate());
            const dataAgendamentos = stringYear +'-'+stringMonth+'-'+stringDay
            data = {
                dia : dataAgendamentos
            }
        }else{
            data = {
                dia : dia
            }
        }

        $.ajax({
            url: '<?= site_url('iscas/isca/ajax_get_agendamentos_por_dia'); ?>',
            type: 'GET',
            data: data,
            success: function (callback) {
                let agendamentos = JSON.parse(callback);

                $("#diaExibido").html(` - ${returnData(data.dia)}`);
                $("#dataExibida").val(data.dia) // adiciona em um input hidden, a data que está sendo exibida no datatable
                
                if(agendamentos.length > 0){
                    for(i in agendamentos){
                        
                        tabela_agendamentos.row.add([
                            agendamentos[i].id,
                            agendamentos[i].serial,
                            returnData(agendamentos[i].data_agendamento),
                            agendamentos[i].data_agendamento.split(" ")[1],
                            returnTipo(agendamentos[i].tipo),
                            returnSituacao(agendamentos[i].situacao, agendamentos[i].status ,agendamentos[i].id),
                            returnBtnAcoes(agendamentos[i].status ,agendamentos[i].id)
                        ]).draw();


                    }
                    
                       
                   
                }

            },
            error: function (error) {
                console.log(error)
                $("#diaExibido").html(` - ${returnData(data.dia)}`);
            }
        });
    }

    /**
     * Função que atualiza calendário com todos os agendamentos do grupo selecionado
     */
    function ajax_get_agendamentos(initDate, endDate){

        $("#spinnner_titulo_calendario").html('<i class="fa fa-spinner fa-spin"></i>');

        const data = {
            initDate: initDate,
            endDate: endDate,
        }

        // limpa o calendário
        num_agendamentos_por_dia = {};
        renderCalendar();

        $.ajax({
            url: '<?= site_url('iscas/isca/ajax_get_agendamentos'); ?>',
            type: 'GET',
            data: data,
            success: function (callback) {
                let agendamentos = JSON.parse(callback);
                // limpa os agendamentos
                num_agendamentos_por_dia = {}
                
                if(agendamentos.length > 0){
                    agendamentos.forEach(agend => {
                        // caso o agendamento ainda não tenha sido finalizado, faz a contagem dos agendamentos e adiciona
                        // no dicionário que será inserido no calendário
                        if(agend.status_confirmacao != "1"){
                            
                            const data_agendamento = agend.data_agendamento.split(' ')[0];
                            if(num_agendamentos_por_dia[data_agendamento] != undefined){
                                num_agendamentos_por_dia[data_agendamento] += 1;
                            }else{
                                num_agendamentos_por_dia[data_agendamento] = 1;
                            }
                        }
                    
                    });

                    // Chama função para renderizar o calendário
                    renderCalendar();
                }
                $("#spinnner_titulo_calendario").html('');
            },
            error: function (error) {
                $("#spinnner_titulo_calendario").html('');
                console.log(error)
            }
        });
        }

    
    

    $("#cadastrarIsca").click(()=>{
        if($("#id_agendamento").val() === ""){
            cadastrarAgendamento();
        }else{
            editarAgendamento();
        }
    });
    /**
     * Cadastrar Agendamento
     */
    function cadastrarAgendamento(){
        const button = $("#cadastrarIsca");
        const data = {
            dataAgendamento: $("#dataAgendamento").val(),
            horaAgendamento: $("#horaAgendamento").val(),
            tipo: $("#tipo").val(),
            serial: $("#serial").val(),
            cliente: $("#cliente").val(),
            instalador: $("#instalador").val(),
            rua: $("#rua").val(),
            numero: $("#numero").val(),
            bairro: $("#bairro").val(),
            cidade: $("#cidade").val(),
            uf: $("#uf").val(),
            obs: $("#obs").val(),
        };
        
        if(validarParametros(data)){

            button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando');
            $.ajax({
                url: '<?php echo site_url('iscas/isca/ajaxCadastrarAgendamento') ?>',
                dataType: "json",
                type: "POST",
                data: data,
                success: function(callback){
                    let agend = callback
                    
                    alert("Agendamento cadastrado com sucesso.");
                    button.attr('disabled', false).html('Salvar');
                    $("#modalCadastroAgendamento").modal("hide");

                    // se o agendamento inserido for na data que está sendo exibida, adiciona ele no datatable
                    if($("#dataExibida").val() === agend.data_agendamento.split(" ")[0]){
                        // Adiciona linha ao datatable
                        
                        tabela_agendamentos.row.add([
                            agend.id,
                            agend.serial,
                            returnData(agend.data_agendamento),
                            agend.data_agendamento.split(" ")[1],
                            returnTipo(agend.tipo),
                            returnSituacao(agend.situacao, agend.status ,agend.id),
                            returnBtnAcoes(agend.status ,agend.id)
                        ]).draw();
                        // reordena tabela pela data dos agendamentos
                        tabela_agendamentos.column(1).data().order([1, 'asc']).draw();
                    }

                    // caso o agendamento ainda não tenha sido finalizado, faz a contagem dos agendamentos e adiciona
                    // no dicionário que será inserido no calendário
                    const data_agendamento = data.dataAgendamento;
                    if(num_agendamentos_por_dia[data_agendamento] != undefined){
                        num_agendamentos_por_dia[data_agendamento] += 1;
                    }else{
                        num_agendamentos_por_dia[data_agendamento] = 1;
                    }

                    // renderiza o calendário
                    renderCalendar();

                },
                error: function(error){
                    button.attr('disabled', false).html('Salvar');
                }
            });
        }
    }
    /**
     * Editar Agendamento
     */
    function editarAgendamento(){
        const button = $("#cadastrarIsca");
        const data = {
            id: $("#id_agendamento").val(),
            dataAgendamento: $("#dataAgendamento").val(),
            horaAgendamento: $("#horaAgendamento").val(),
            tipo: $("#tipo").val(),
            serial: $("#serial").val(),
            cliente: $("#cliente").val(),
            instalador: $("#instalador").val(),
            rua: $("#rua").val(),
            numero: $("#numero").val(),
            bairro: $("#bairro").val(),
            cidade: $("#cidade").val(),
            uf: $("#uf").val(),
            obs: $("#obs").val(),
        };

        const data_agendamento_anterior = $("#edit_data_anterior_agendamento").val();
        
        if(validarParametros(data)){
            
            button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando');
            $.ajax({
                url: '<?php echo site_url('iscas/isca/ajaxEditarAgendamento') ?>',
                dataType: "json",
                type: "POST",
                data: data,
                success: function(callback){
                    alert(callback.msg);
                    if(callback.status){
                        const agend = callback.agend;
                        button.attr('disabled', false).html('Salvar');
                        $("#modalCadastroAgendamento").modal("hide");
    
                        const data_agendamento_atual = agend.data_agendamento.split(' ')[0];

                        // Atualiza a contagem de agendamentos por data se a data de agendamento anterior for diferente da atual
                        if(data_agendamento_atual != data_agendamento_anterior){
                            // subtrai a data anterior
                            if(num_agendamentos_por_dia[data_agendamento_anterior] > 0){
                                num_agendamentos_por_dia[data_agendamento_anterior] -= 1;
                            }
                            // soma a data atual
                            if(num_agendamentos_por_dia[data_agendamento_atual] != undefined){
                                num_agendamentos_por_dia[data_agendamento_atual] += 1;
                            }else{
                                num_agendamentos_por_dia[data_agendamento_atual] = 1;
                            }
                        }
                        renderCalendar();

                        // Atualiza Datatable
                        tabela_agendamentos.rows().every(function(){
                            const row = this;
                            const linha = row.data();

                            if(linha != undefined){
                                const id_row = linha[0];
                                if(id_row == agend.id){ //verifica o id do agendamento
                                    if(linha[2] != returnData(agend.data_agendamento.split(" ")[0])){// verifica se a nova data é diferente da anterior
                                        
                                        row.remove().draw();;//remove a linha
                                    }
                                    else{// atualiza a linha
                                        row.data([
                                            agend.id,
                                            agend.serial,
                                            returnData(agend.data_agendamento.split(" ")[0]),
                                            agend.data_agendamento.split(" ")[1],
                                            returnTipo(agend.tipo),
                                            returnSituacao(agend.situacao, agend.status ,agend.id),
                                            returnBtnAcoes(agend.status ,agend.id)
                                        ]).draw();
                                        
                                    }
    
                                }
                            }
                        });
                    }


                },
                error: function(error){
                    button.attr('disabled', false).html('Salvar');
                }
            });
        }
    }
    function limparModal(){
        $("#id_agendamento").val("");
        $("#edit_data_anterior_agendamento").val("");
        $("#dataAgendamento").val("");
        $("#horaAgendamento").val("");
        $("#tipo").val("");
        $("#serial").val("").change();
        $("#cliente").val("").change();
        $("#instalador").val("").change();
        $("#rua").val("");
        $("#numero").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#uf").val("");
        $("#obs").val("");
    
    }
    // Valida parâmetros da requisição
    function validarParametros(data){

        if(validarDataHora(data.dataAgendamento, data.horaAgendamento) == false){
            return false;
        }
        else if (data.tipo == ""){
            alert("Selecione o tipo.");
            return false;
        }
        else if (data.serial == null){
            alert("Selecione o serial.");
            return false;
        }
        else if (data.cliente == null){
            alert("Selecione o cliente.");
            return false;
        }    
        else if (data.instalador == null){
            alert("Selecione o instalador.");
            return false;
        }
        else if (data.rua == ""){
            alert("Informe a rua.");
            return false;
        }
        else if (data.numero == ""){
            alert("Informe o número.");
            return false;
        }
        else if (data.bairro  == ""){
            alert("Informe o bairro.");
            return false;
        }
        else if (data.cidade == ""){
            alert("Informe a cidade.");
            return false;
        }
        else if (data.uf == ""){
            alert("Selecione o Estado.");
            return false;
        }    
        
        
        else{
            return true;
        }
    }

    /**
     * Valida campos de data e hora
     */
    function validarDataHora(data, hora){
        if(data == ""){
            alert("Informe a data do agendamento");
            return false;
        }
        else if(hora == ""){
            alert("Informe a hora do agendamento");
            return false;
        }else{
            const dataAgend = new Date(data + " " + hora);
            const now = new Date();
            
            if(dataAgend === "Invalid Date"){
                alert("Informe uma data de gendamento válida");
                return false;
            }
            else if(dataAgend < now){
                alert("Informe uma data de agendamento posterior a data atual");
                return false;
            }
            else if(dataAgend > now.setFullYear(now.getFullYear() + 1)){
                alert("Informe uma data de agendamento com, no máximo, um ano da data atual");
                return false;
            }
            else{
                return true;
            }    
        }
        return true;
    }
    function returnTipo(tipo){
        switch (tipo) {
            case 'instalacao': 
                return "Instalação"
                break;
            case 'manutencao': 
                return "Manutenção"
                break;
            case 'transferencia': 
                return "Transferência"
                break;
            case 'retirada': 
                return "Retirada"
                break;
            default:
                break;
        }
    }
    // 
    function returnSituacao(situacao, status_confirmacao,id_agendamento){
        let html = `<div id="divStatusAgendamento${id_agendamento}">`;

        // Caso o agendamento esteja confirmado retorna apenas o span
        if(status_confirmacao == 1){
            switch (situacao) {
                case 'em_aberto': 
                    html += `<span>Em Aberto</span>`;
                    break;
                case 'executado': 
                    html += `<span>Executado</span>`;
                    break;
                case 'cancelado': 
                    html += `<span>Cancelado</span>`;
                    break;
                case 'visita_frustrada': 
                    html += `<span>Visita Frustrada</span>`;
                    break;
                case 'com_pendencias': 
                    html += `<span>Com Pendências<span>`;
                    break;
                default:
                    break;
            }
        }else{
            switch (situacao) {
                case 'em_aberto': 
                    html +=     `<select id="select_status_agendamento${id_agendamento}" class="form-control">
                                    <option class="form-control" value="em_aberto" selected>Em Aberto</option>
                                    <option class="form-control" value="executado">Executado</option>
                                    <option class="form-control" value="cancelado">Cancelado</option>
                                    <option class="form-control" value="visita_frustrada">Visita Frustrada</option>
                                    <option class="form-control" value="com_pendencias">Com Pendências</option>
                                </select>`;
                    break;
                case 'executado': 
                    
                    html +=     `<select id="select_status_agendamento${id_agendamento}" class="form-control">
                                    <option class="form-control" value="em_aberto">Em Aberto</option>
                                    <option class="form-control" value="executado" selected>Executado</option>
                                    <option class="form-control" value="cancelado">Cancelado</option>
                                    <option class="form-control" value="visita_frustrada">Visita Frustrada</option>
                                    <option class="form-control" value="com_pendencias">Com Pendências</option>
                                </select>`;
                    break;
                case 'cancelado': 
                    html +=     `<select id="select_status_agendamento${id_agendamento}" class="form-control">
                                    <option class="form-control" value="em_aberto">Em Aberto</option>
                                    <option class="form-control" value="executado">Executado</option>
                                    <option class="form-control" value="cancelado" selected>Cancelado</option>
                                    <option class="form-control" value="visita_frustrada">Visita Frustrada</option>
                                    <option class="form-control" value="com_pendencias">Com Pendências</option>
                                </select>`;
                    break;
                case 'visita_frustrada': 
                    html +=     `<select id="select_status_agendamento${id_agendamento}" class="form-control">
                                    <option class="form-control" value="em_aberto">Em Aberto</option>
                                    <option class="form-control" value="executado">Executado</option>
                                    <option class="form-control" value="cancelado">Cancelado</option>
                                    <option class="form-control" value="visita_frustrada selected">Visita Frustrada</option>
                                    <option class="form-control" value="com_pendencias">Com Pendências</option>
                                </select>`;
                    break;
                case 'com_pendencias': 
                    html +=     `<select id="select_status_agendamento${id_agendamento}" class="form-control">
                                    <option class="form-control" value="em_aberto">Em Aberto</option>
                                    <option class="form-control" value="executado">Executado</option>
                                    <option class="form-control" value="cancelado">Cancelado</option>
                                    <option class="form-control" value="visita_frustrada">Visita Frustrada</option>
                                    <option class="form-control" value="com_pendencias" selected>Com Pendências</option>
                                </select>`;
                    break;
                default:
                    break;
            }
        }

        html += '</div>';
        return html;
    }
    function returnData(data){
        const arrayData = data.split(" ")[0];
        const dia = arrayData.split("-")[2];
        const mes = arrayData.split("-")[1];
        const ano = arrayData.split("-")[0];
        return `${dia}/${mes}/${ano}`;
    }

    function returnBtnAcoes(status_confirmacao, id_agendamento){
        let html = '';
        if(status_confirmacao == 1){
            html +=    `<div id="btnsAdministrar${id_agendamento}">
                            <a id="btnVisualizarAgend${id_agendamento}" class="btn btn-primary" onclick="visualizarAgendamento(${id_agendamento})"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <a class="btn btn-success" style="background-color: gray !important;border-color: gray !important;" onclick="alert('O agendamento já foi confirmado anteriormente');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a class="btn btn-success" onclick="alert('O agendamento já foi confirmado anteriormente');"><i class="fa fa-check" aria-hidden="true"></i></a>
                        </div>`;
        }else{
            html +=    `<div id="btnsAdministrar${id_agendamento}">
                            <a id="btnVisualizarAgend${id_agendamento}" class="btn btn-primary" onclick="visualizarAgendamento(${id_agendamento})"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <a class="btn btn-primary" id="btnEditarAgend${id_agendamento}" onclick="showModalEditarAgendamento(${id_agendamento})"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a id="btnConfirmarAgend${id_agendamento}" class="btn btn-primary" onclick="confirmarAgend(${id_agendamento})"><i class="fa fa-check" aria-hidden="true"></i></a>
                        </div>`;
        }
        return html;
    }

    function visualizarAgendamento(id){
        
        const button = $("#btnVisualizarAgend"+id);
        button.attr('disabled',true).html('<i class="fa fa-spinner fa-spin"></i>');

        

        limparModal();
        $.ajax({
            url: '<?php echo site_url('iscas/isca/ajaxGetAgendamento') ?>',
            dataType: "json",
            type: "GET",
            data: {id: id},
            success: function(callback){
                const agend = (callback);
                
                if(agend.status == true){

                    $("#showData").html(returnData(agend.dados.data_agendamento.split(" ")[0]));
                    $("#showHora").html(agend.dados.data_agendamento.split(" ")[1]);
                    $("#showTipo").html(agend.dados.tipo);
                    $("#showSerial").html(agend.dados.serial);
                    $("#showCliente").html(agend.dados.nome_cliente);
                    $("#showInstalador").html(agend.dados.nome_instalador);
                    $("#showRua").html(agend.dados.rua);
                    $("#showNumero").html(agend.dados.numero);
                    $("#showBairro").html(agend.dados.bairro);
                    $("#showCidade").html(agend.dados.cidade);
                    $("#showUF").html(agend.dados.uf);
                    $("#showObs").html(agend.dados.obs);
                    $("#showSituacao").html(returnSituacao(agend.dados.situacao, 1,agend.dados.id));
                    $("#showStatus").html(agend.dados.status == 1 ? "Finalizado" : "Aguardando Confirmação");
                    $("#showDataFim").html(agend.dados.data_resultado != null ? returnData(agend.dados.data_resultado.split(" ")[0]) : '');
                    $("#modalVisualizarAgendamento").modal('show');

                }else{
                    alert(agend.msg);
                }
                button.attr('disabled', false).html('<i class="fa fa-eye" aria-hidden="true"></i>');
            },
            error: function(error){
                button.attr('disabled', false).html('<i class="fa fa-eye" aria-hidden="true"></i>');
                alert("Erro ao carregar agendamento.")
            }
        });
    }
    /**
     * Abre modal com informações para editar o agendamento
     */
    function showModalEditarAgendamento(id){
        const button = $(`#btnEditarAgend${id}`);
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        limparModal();
        $.ajax({
            url: '<?php echo site_url('iscas/isca/ajaxGetAgendamento') ?>',
            dataType: "json",
            type: "GET",
            data: {id: id},
            success: function(callback){
                const agend = (callback);
                
                if(agend.status == true){
                    $("#cadastroIscaModalLabel").html("Editar Agendamento");
                    $("#id_agendamento").val(agend.dados.id);
                    $("#edit_data_anterior_agendamento").val(agend.dados.data_agendamento.split(" ")[0]);
                    $("#dataAgendamento").val(agend.dados.data_agendamento.split(" ")[0]);
                    $("#horaAgendamento").val(agend.dados.data_agendamento.split(" ")[1]);
                    $("#tipo").val(agend.dados.tipo);
                    $("#serial")
                        .prop("disabled",false)
                        .append($("<option selected></option>")
                        .val(agend.dados.isca_id)
                        .text(agend.dados.serial))
                        .trigger('change');
                    
                    $("#cliente")
                        .append($("<option selected></option>")
                        .val(agend.dados.cliente_id)
                        .text(agend.dados.nome_cliente))
                        .trigger('change');
                    $("#instalador")
                        .append($("<option selected></option>")
                        .val(agend.dados.instalador_id)
                        .text(agend.dados.nome_instalador))
                        .trigger('change');

                    $("#rua").val(agend.dados.rua);
                    $("#numero").val(agend.dados.numero);
                    $("#bairro").val(agend.dados.bairro);
                    $("#cidade").val(agend.dados.cidade);
                    $("#uf").val(agend.dados.uf);
                    $("#obs").val(agend.dados.obs);

                    $("#modalCadastroAgendamento").modal("show");
                }else{
                    alert(agend.msg);
                }
                button.attr('disabled', false).html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');
            },
            error: function(error){
                button.attr('disabled', false).html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');
                alert("Erro ao carregar agendamento.")
            }
        });
    }
    /**
     * Confirma o agendamento
     */
    function confirmarAgend(id){
        const statusAgendamento = $("#select_status_agendamento"+id).val();

        if(statusAgendamento === 'em_aberto'){
            alert('Informe a situação do agendamento.');
            return;
        }else{
            let confirma = confirm("Você deseja finalizar o agendamento?");
            if(confirma){
                const button = $("#btnConfirmarAgend"+id);
                const data = {
                    id:id, 
                    situacao: statusAgendamento,
                }
                button.attr('disabled',true).html('<i class="fa fa-spinner fa-spin"></i>');
                $.ajax({
                    url: '<?= site_url('iscas/isca/ajax_confirmar_agendamento'); ?>',
                    type: 'POST',
                    data: data,
                    success: function (callback) {
                        resposta = JSON.parse(callback);
                        agendamento = resposta.agendamento;
                        if(resposta.status == true){
                            alert(returnAlertConfirmSituacao(resposta.agendamento.situacao));
                            
                            $(`#btnsAdministrar${id}`).html(returnBtnAcoes(1, id));
                            $("#divStatusAgendamento"+id).html(returnSituacao(resposta.agendamento.situacao, 1 , id),);
                            // Subtrai o agendamento do dic com os agendamentos diários para ser renderizado no calendário
                            const data_agend = agendamento.data_agendamento.split(' ')[0];
                            if(num_agendamentos_por_dia[data_agend] != undefined && num_agendamentos_por_dia[data_agend] > 0){
                                num_agendamentos_por_dia[data_agend] -= 1;
                            }
                            renderCalendar();
                        }
                        button.attr('disabled',false).html('<i class="fa fa-check" aria-hidden="true"></i>');
                        
                    },
                    error: function (error) {
                        console.log(error)
                        button.attr('disabled',false).html('<i class="fa fa-check" aria-hidden="true"></i>');
                    }
                });
            }
        }

    }

    function returnAlertConfirmSituacao(situacao){
        
        if(situacao == 'executado'){
            return 'Executado com Sucesso!';
        }
        else if(situacao == 'cancelado'){
            return 'Cancelado com Sucesso!';
        }
        else{
            return 'Situação alterada com Sucesso!'
        }
        
    }
</script>

<script type="text/javascript" charset="utf8" src="<?php echo base_url('media/calendario_agendamento/calendario_agendamento.js');?>"></script>