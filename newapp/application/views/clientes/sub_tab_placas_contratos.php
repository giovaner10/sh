<?php if ($type == 1): ?>
    <h3>Sim card do Contrato #<?php echo $id_contrato?></h3>
<?php elseif ($type == 2): ?>
    <h3>Tornozeleiras do Contrato #<?php echo $id_contrato?></h3>
<?php else: ?>
    <h3>Placas do Contrato #<?php echo $id_contrato?></h3>
<?php endif; ?>
<div class="alert alert-warning placa-alert" style="display:none">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="mensagem"></span>
</div>

<div class="well well-small" style="height: 30px;">

    <?php if ($type == 1): ?>
        <div class="span1 pull-left">
            <a href="<?php echo site_url('contratos/add_chip/'.$id_cliente.'/'.$id_contrato.'/'.$type)?>"
               class="btn btn-primary" data-toggle="modal"
               data-target="#nova_placa" title="Adicionar Chip">
                <i class="icon-plus icon-white"></i>
            </a>
        </div>
    <?php elseif ($type == 2): ?>
        <div class="span1 pull-left">
            <a href="<?php echo site_url('contratos/add_tornozeleira/'.$id_cliente.'/'.$id_contrato.'/'.$type)?>"
               class="btn btn-primary" data-toggle="modal"
               data-target="#nova_tornozeleira" title="Adicionar Tornozeleira">
                <i class="icon-plus icon-white"></i>
            </a>
        </div>
    <?php else: ?>
        <div class="pull-left">
            <div style="margin-right:10px; float:left;">
            <a href=""
               class="btn btn-primary" data-toggle="modal"
               data-target="#nova_placa" title="Adicionar Placa">
                <i class="icon-plus icon-white"></i>Placa
            </a>
        </div>
        </div>
        <div style="float:left;">
            <a href=""
               class="btn btn-primary" data-toggle="modal"
               data-target="#novas_placas" title="Adicionar Multiplas Placa">
                <i class="icon-plus icon-white"></i>Multiplas Placas
            </a>
        </div>
    <?php endif; ?>

    <?php if ($type == 1): ?>
        <div class="span4 pull-right">
            <?php echo form_open('#contratos', array('id' => 'busca_veiculo', 'style' => 'padding: 0; margin: 0;'))?>
            <div class="input-append">
                <?php if(isset($placa)):?>
                    <a href="<?php echo site_url('contratos/simCard/'.$id_cliente.'/'.$id_contrato.'/'.$type)?>" id="remove_filtro" class="btn btn-danger" title="Remover Filtro">
                        <i class="icon-remove icon-white"></i>
                    </a>
                <?php endif;?>
                <input id="placa_pesc" type="text" placeholder="Digite uma linha" autocomplete="off" value="<?php echo isset($placa) ? $placa : ''?>" data-controller="<?php echo site_url('contratos/simCard/'.$id_cliente.'/'.$id_contrato.'/'.$type)?>" name="placa_pesq" required>
                <button class="btn" type="submit"><i class="icon-search"></i></button>
            </div>
            <?php echo form_close()?>
        </div>
    <?php elseif ($type == 2): ?>
        <div class="span4 pull-right">
            <?php echo form_open(site_url('contratos/tornozeleiras/'.$id_cliente.'/'.$id_contrato.'/2/0'), array('id' => 'busca_veiculo', 'style' => 'padding: 0; margin: 0;'))?>
            <div class="input-append">
                <?php if(isset($placa)):?>
                    <a href="<?php echo site_url('contratos/tornozeleiras/'.$id_cliente.'/'.$id_contrato.'/'.$type.'/0')?>" id="remove_filtro" class="btn btn-danger" title="Remover Filtro">
                        <i class="icon-remove icon-white"></i>
                    </a>
                <?php endif;?>
                <input id="placa_pesc" type="text" placeholder="Digite o serial" autocomplete="off" value="<?php echo isset($placa) ? $placa : ''?>" data-controller="<?php echo site_url('contratos/tornozeleiras/'.$id_cliente.'/'.$id_contrato.'/'.$type.'/0')?>" name="placa_pesq" required>
                <button class="btn" type="submit"><i class="icon-search"></i></button>
            </div>
            <?php echo form_close()?>
        </div>
    <?php endif; ?>
</div>
<br>
<?php if ($type == 1): ?>
    <div class="row-fluid">
        <div class="span12">
            <table class="table table-bordered" id="placas">
                <thead>
                <th class="span2">#ID</th>
                <th class="span5">Sim Card</th>
                <th class="span3">CCID</th>
                <th class="span3">Status</th>
                </thead>
                <tbody>
                <?php if($placas):?>
                    <?php if(count($placas) > 0):?>
                        <?php foreach($placas as $placa):?>
                            <tr>
                                <td><?php echo $placa->id ?></td>
                                <td><?php echo $placa->numero ?></td>
                                <td><?php echo $placa->ccid ?></td>
                                <td style="text-align: center">
                                    <div class="btn-group" data-toggle="buttons-radio">
                                        <button type="button" class="btn btn-small status <?php echo $placa->status == 1 ? 'active btn-success' : ''?>"  data-status="ativo" data-controller="<?php echo site_url('contratos/ajax_atualiza_status_chip/'.$placa->id.'/ativo/'.$id_cliente.'')?>">Ativo</button>
                                        <button type="button" class="btn btn-small status <?php echo $placa->status == 0 ? 'active btn-danger' : ''?>" data-status="inativo" data-controller="<?php echo site_url('contratos/ajax_atualiza_status_chip/'.$placa->id.'/inativo/'.$id_cliente.'')?>">Inativo</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr>
                            <td colspan="5">Nenhuma placa cadastrada neste contrato.</td>
                        </tr>
                    <?php endif;?>
                <?php else:?>
                    <tr>
                        <td colspan="5">Placa não cadastrada neste contrato.</td>
                    </tr>
                <?php endif;?>
                </tbody>
            </table>
            <div class="pagination pagination-right">
                <?php echo $this->pagination->create_links()?>
            </div>
        </div>
    </div>
<?php elseif ($type == 2): ?>
    <div class="row-fluid">
        <div class="span12">
            <table class="table table-bordered" id="placas">
                <thead>
                <th class="span2">#ID</th>
                <th class="span5">Serial</th>
                <th class="span3">Data Cadastro</th>
                <th class="span3">Status</th>
                </thead>
                <tbody>
                <?php if($placas):?>
                    <?php if(count($placas) > 0):?>
                        <?php foreach($placas as $placa):?>
                            <tr>
                                <td><?php echo $placa->id ?></td>
                                <td><?php echo $placa->equipamento ?></td>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($placa->data_cadastro)) ?></td>
                                <td style="text-align: center">
                                    <div class="btn-group" data-toggle="buttons-radio">
                                        <button type="button" class="btn btn-small status <?php echo $placa->status == 'ativo' ? 'active btn-success' : ''?>"  data-status="ativo" data-controller="<?php echo site_url('contratos/ajax_atualiza_status_trz/'.$placa->id.'/ativo')?>">Ativo</button>
                                        <button type="button" class="btn btn-small status <?php echo $placa->status == 'inativo' ? 'active btn-danger' : ''?>" data-status="inativo" data-controller="<?php echo site_url('contratos/ajax_atualiza_status_trz/'.$placa->id.'/inativo')?>">Inativo</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr>
                            <td colspan="5">Nenhuma tornozeleira cadastrada neste contrato.</td>
                        </tr>
                    <?php endif;?>
                <?php else:?>
                    <tr>
                        <td colspan="5">Tornozeleira não cadastrada neste contrato.</td>
                    </tr>
                <?php endif;?>
                </tbody>
            </table>
            <div class="pagination pagination-right">
                <?php echo $this->pagination->create_links()?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div id="loadTabela" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>
    <div class="row-fluid">
       <div class="span12">
           <table class="table table-bordered" id="placasTable">
               <thead>
                   <th>#ID</th>
                   <th>Placa</th>
                   <th>Status</th>
                   <th>Vincular Veículo</th>
                   <th>Posição</th>
                   <th>Secretaria</th>
               </thead>
               <tbody></tbody>
           </table>
       </div>
    </div>
<?php endif; ?>

<?php if ($type == 1): ?>
    <div id="nova_placa" class="modal fade" tabindex="-1"
         role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true"></button>
            <h3 id="myModalLabel1">Novo Chip - Contrato #<?php echo $id_contrato?></h3>
        </div>
        <div class="modal-body">
            <p>Carregando...</p>
        </div>
    </div>
<?php elseif ($type == 2): ?>
    <div id="nova_tornozeleira" class="modal fade" tabindex="-1"
         role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true"></button>
            <h3 id="myModalLabel1">Nova Tornozeleira - Contrato #<?php echo $id_contrato?></h3>
        </div>
        <div class="modal-body">
            <p>Carregando...</p>
        </div>
    </div>
<?php else: ?>
    <div id="nova_placa" class="modal fade" tabindex="-1"
        role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
       <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal"
                   aria-hidden="true"></button>
           <h3 id="myModalLabel1">Cadastrar Nova Placa - Contrato #<?php echo $id_contrato?></h3>
       </div>
       <div class="modal-body">

           <?php echo form_open('contratos/ajax_add_placa/'.$id_contrato, array('id' => 'add_placa'),
               array('id_cliente' => $id_cliente, 'id_contrato' => $id_contrato))?>

           <div id="load" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>
           <div class="row-fluid">
               <div class="span12 ">
                   <div class="control-group">
                       <label>Placa:</label>
                       <input type="text" class="placa" name="placa" maxlength="8" required />
                   </div>
               </div>
               <!--/span-->
           </div>
           <div class="row-fluid">
               <div class="span12 ">
                   <label class="control-label">Status:</label>
                   <div class="control-group">
                       <label class="radio inline">
                           <input type="radio" name="status" value="ativo" required /> Ativo
                       </label> <label class="radio inline">
                           <input type="radio" name="status" value="inativo" required /> Inativo
                       </label>
                   </div>
               </div>
               <!--/span-->
           </div>


           <div class="row-fluid">
               <div class="form-actions">
                   <button type="button" class="btn btn-primary" id="addPlaca">
                       <i class="icon-plus icon-white"></i> Salvar
                   </button>
                   <a onclick="fecharModal('#nova_placa');" class="btn fechar">Fechar</a>
               </div>
           </div>

           <?php echo form_close()?>

       </div>
   </div>

   <div id="novas_placas" class="modal fade" tabindex="-1"
        role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
       <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal"
                   aria-hidden="true"></button>
           <h3 id="myModalLabel1">Cadastrar Multiplas Placas - Contrato #<?php echo $id_contrato?></h3>
           <b><p class="alert alert-warning placa-alert">O arquivo deve conter três colunas:</b> Placa, Serial, Nome  -  todas iniciando com letra maiúscula. <br><b>Formatos Suportados:</b> .xls e .xlsx</p>
           <b><p class="alert alert-info placa-alert">Apenas veículos que não constam no sistema serão cadastrados!</p></b>
       </div>
       <div class="modal-body">
           <div id="loadMultiPlacas" style="margin: 10% 45% 10% 45%; width: 50px; display: none; position:absolute; z-index:100000"><img src="<?php echo base_url('media/img/loading.gif')?>" /></div>
           <input type="file" name="file" id="fileUpload" />
           <div class="row-fluid">
               <button type="button" id="cadastrarVeiculos" class="btn btn-primary" disabled >
                   <i class="icon-plus icon-white"></i> Salvar</button>
               <a onclick="fecharModal('#novas_placas');" id="fechar_modal" class="btn fechar">Fechar</a>
           </div>
           <div id="dvExcel" style="margin-top:10px;"></div>
       </div>
   </div>
<?php endif; ?>

<div id="modal_serial" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true"></button>
        <h4 id="myModalLabel1">Vincular Veículo - Contrato #<?php echo $id_contrato?></h4>
    </div>
    <div class="modal-body">
        <p>Carregando...</p>
    </div>
</div>

<div id="modal_editar" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true"></button>
        <h4 id="myModalLabel1">Editar Veículo - Contrato #<?php echo $id_contrato?></h4>
    </div>
    <div class="modal-body">
        <p>Carregando...</p>
    </div>
</div>

<div id="modal_posicao" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true"></button>
        <h4 id="myModalLabel1">Posição Veículo</h4>
    </div>
    <div class="modal-body">
        <p>Carregando...</p>
    </div>
</div>

<div id="modal_serial_inativo" class="modal fade" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true"></button>
        <h4 id="myModalLabel1">Vincular Veículo - Contrato #<?php echo $id_contrato?></h4>
    </div>
    <div class="modal-body">
        <div class="alert alert-info">
            <h4>Placa INATIVA!</h4>
            <p>Ação aceita apenas para placas <b>ATIVAS</b>.</p>
        </div>
        <div class="row-fluid">
            <div class="form-actions">
                <a onclick="fecharModal('#modal_serial_inativo');" class="btn fechar">Fechar</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>

<script type="text/javascript">
    var excelRows = false;

    $(document).ready(function() {
        var tablePlacas = $('#placasTable').DataTable( {
            responsive: true,
            processing: true,
            order: [0, 'desc'],
            otherOptions: {},
		    initComplete: function() {
		        $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
		    },
            columns: [
                {data: 'id'},
                {data: 'placa'},
                {data: 'status'},
                {data: 'vincular'},
                {data: 'posicao'},
                {data: 'secretarias'}
            ],
            "language": {
                "decimal":        "",
                "emptyTable":     "Nenhum registro encontrado",
                "info":           "Registro _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "0 Registros",
                "infoFiltered":   "(filtered from _MAX_ total entries)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir: _MENU_",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar: ",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "Anterior",
                    "last":       "Próxima",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                }
            }
        });

        carrega_dados_tabela();

        function carrega_dados_tabela(){
            $('#loadTabela').css('display', 'block');
            $.ajax({
                type: "get",
                dataType: 'json',
                url: "<?= site_url('contratos/ajax_lista_placas') ?>",
                data: {
                    id_cliente: "<?= $id_cliente ?>",
                    id_contrato: "<?= $id_contrato ?>",
                },
                success: function(data){
                    // Atualiza Tabela
                    tablePlacas.clear();
                    tablePlacas.rows.add(data.table);
                    tablePlacas.draw();
                },
                error: function(data) {
                    tablePlacas.clear();
                    tablePlacas.draw();
                }
            });
            $('#loadTabela').css('display', 'none');
        }

        $('#addPlaca').click(function(event){
            $('#load').css('display', 'block');
            event.preventDefault()
            var cliente = $('input[name=id_cliente]').val();
            var contrato = $('input[name=id_contrato]').val();
            var placa = $('input[name=placa]').val();
            var stat = $('input[name=status]:checked').val();

            var urlP = $('#add_placa').attr('action');
            $.post(urlP, {placa: placa, status: stat, id_contrato: contrato, id_cliente: cliente},

            function(cback) {
                $('#load').css('display', 'none');
                $('.placa-alert').css('display', 'block');
                if(cback.success) {
                    $('span.mensagem').html(cback.msg);
                    carrega_dados_tabela();
                } else {
                    $('span.mensagem').html(cback.msg);
                }

                $('#nova_placa').on('hidden.bs.modal', function () {
                    $('input[name=placa]').val('');
                });
                $('#nova_placa').modal('hide');

            }, 'json');
        });

        $('#cadastrarVeiculos').click(function(){
            $('#loadMultiPlacas').css('display', 'block');
            $.ajax({
                url: '<?= site_url('veiculos/cadastrar_veiculo_lote') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    veiculos: JSON.stringify(excelRows),
                    id_cliente: '<?=$id_cliente?>',
                    id_contrato: '<?=$id_contrato?>'
                },
                success: function (callback) {
                    $('#loadMultiPlacas').css('display', 'none');
                    $('.placa-alert').css('display', 'block');
                    if (callback.status == 'OK') {
                        $('span.mensagem').html(callback.msg);

                        $('#novas_placas').on('hidden.bs.modal', function () {
                            $('input[name=file]').val('');
                            $("#dvExcel tr").remove();
                            document.getElementById('cadastrarVeiculos').disabled = true;
                        });
                        $('#novas_placas').modal('hide');

                       // tableSec.ajax.reload(null, false);
                       carrega_dados_tabela();
                    } else {
                        $('span.mensagem').html(callback.msg);
                        $('#novas_placas').modal('hide');
                    }
                }
            });

        });

        $('.modal-backdrop ').click(function(){
            alert('teste');
        })

        // paginação cliente
        $('.pag_cli a').click(function (ev) {
            ev.preventDefault();

            $('#loading').css('display', 'block');
            var urlPag = $(this).attr('href');

            $('#contratos').load(urlPag, function(result){
                //pane.tab('show');
                $('#loading').css('display', 'none');
            });
        });

        $(document).on('click', '.status', function(e) {
            e.preventDefault();
            var botao = $(this);
            controller = $(this).attr('data-controller');
            $.get(controller, function(result) {
                console.log(result);
                if(!result.success) {
                    //$('.inativo').button('toggle');
                    alert(result.msg);
                } else {
                    if ($(botao).data('status') == 'ativo') {
                        $(botao).addClass('active btn-success');
                        $(botao).closest('.btn-group').find('button:not(data-status["ativo"])').removeClass('active btn-danger');
                    }else{
                        $(botao).addClass('active btn-danger');
                        $(botao).closest('.btn-group').find('button:not(data-status["inativo"])').removeClass('active btn-success');
                    }
                }
            }, 'json');
        });

        $('.placas').click(function(e) {
            e.preventDefault();
            $('#loading').css('display', 'block');

            url = $(this).attr('href');

            $('#contratos').load(url, function(result) {
                //pane.tab('show');
                $('#loading').css('display', 'none');
            });
        });

        $('#remove_filtro').click(function(e) {
            e.preventDefault();

            $('#loading').css('display', 'block');
            var urlPag = $(this).attr('href');

            $('#contratos').load(urlPag, function(result) {
                $('#loading').css('display', 'none');
            });
        });

        $('#busca_veiculo').submit(function(e) {
            e.preventDefault();
            $('#loading').css('display', 'block');
            input = $('input[name=placa_pesq]');
            controller = input.attr('data-controller');
            url = controller+'/'+input.val();

            $.post(url, {placa_pesq: input.val()}, function(resultado) {
                $('#contratos').html(resultado);
                $('#loading').css('display', 'none');
            });
        });

        $('#modal_serial').attr('data-backdrop', 'static');
    });

    $('#fileUpload').change(function () {
       //Reference the FileUpload element.
       var fileUpload = document.getElementById("fileUpload");

       //Validate whether File is valid Excel file.
       var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
       if (regex.test(fileUpload.value.toLowerCase())) {
           if (typeof (FileReader) != "undefined") {
               var reader = new FileReader();

               //For Browsers other than IE.
               if (reader.readAsBinaryString) {
                   reader.onload = function (e) {
                       ProcessExcel(e.target.result);
                   };
                   reader.readAsBinaryString(fileUpload.files[0]);
               } else {
                   //For IE Browser.
                   reader.onload = function (e) {
                       var data = "";
                       var bytes = new Uint8Array(e.target.result);
                       for (var i = 0; i < bytes.byteLength; i++) {
                           data += String.fromCharCode(bytes[i]);
                       }
                       ProcessExcel(data);
                   };
                   reader.readAsArrayBuffer(fileUpload.files[0]);
               }
           } else {
               alert("O browser não suporta HTML5.");
           }
       } else {
           alert("Por favor, use um arquivo excel válido.");
           document.getElementById('fileUpload').value=''; // Limpa o campo
       }
       document.getElementById('cadastrarVeiculos').disabled = false;

   });

   function selecionarSecretaria(id_veic_contrato,grupo){
       $.ajax({
           type: "POST",
           url: "<?php echo site_url('contratos/vincular_secretaria')?>",
           data: {id_veic_contrato:id_veic_contrato,grupo:grupo},
           success: function(resposta){
           }
       });
   }

   function ProcessExcel(data) {
       //Read the Excel File data.
       var workbook = XLSX.read(data, {
           type: 'binary'
       });

       //Fetch the name of First Sheet.
       var firstSheet = workbook.SheetNames[0];

       //Read all rows from First Sheet into an JSON array.
       excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);

       //Create a HTML Table element.
       var table = document.createElement("table");
       table.border = "1";

       //Add the header row.
       var row = table.insertRow(-1);

       //Add the header cells.
       var headerCell = document.createElement("TH");
       headerCell.innerHTML = "Placa";
       row.appendChild(headerCell);

       headerCell = document.createElement("TH");
       headerCell.innerHTML = "Serial";
       row.appendChild(headerCell);

       headerCell = document.createElement("TH");
       headerCell.innerHTML = "Nome";
       row.appendChild(headerCell);

       //Add the data rows from Excel file.
       for (var i = 0; i < excelRows.length; i++) {
           //Add the data row.
           var row = table.insertRow(-1);

           //Add the data cells.
           var cell = row.insertCell(-1);
           cell.innerHTML = excelRows[i].Placa;

           cell = row.insertCell(-1);
           cell.innerHTML = excelRows[i].Serial;

           cell = row.insertCell(-1);
           cell.innerHTML = excelRows[i].Nome;
       }

       var dvExcel = document.getElementById("dvExcel");
       dvExcel.innerHTML = "";
       dvExcel.appendChild(table);
   }

</script>
