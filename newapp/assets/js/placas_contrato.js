var tablePlacas = false;
var excelRows = false;
var excelRowsIscas = false;

$(document).ready(function() {

    //instanciar datatable tabela placas
    if ($.fn.DataTable.isDataTable('#placasTable')) {
        return tablePlacas.destroy();
    }
    tablePlacas = $('#placasTable').DataTable( {
        responsive: true,
        processing: true,
        order: [0, 'desc'],
        otherOptions: {},
        initComplete: function() {
            $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
        },
        columnDefs: [
            {"className": "dt-center", "targets": "_all"}
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

    //instanciar datatable tabela chips
    if ($.fn.DataTable.isDataTable('#chipsTable')) {
        return tableChips.destroy();
    }
    tableChips = $('#chipsTable').DataTable( {
        responsive: true,
        processing: true,
        order: [0, 'desc'],
        otherOptions: {},
        initComplete: function() {
            $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
        },
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

    //instanciar datatable da tabela de iscas
    if ($.fn.DataTable.isDataTable('#iscasTable')) {
        return tableIscas.destroy();
    }
    tableIscas = $('#iscasTable').DataTable( {
        responsive: true,
        processing: true,
        order: [0, 'desc'],
        otherOptions: {},
        initComplete: function() {
            $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
        },
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


    //instanciar datatable da tabela de tornozeleira
    if ($.fn.DataTable.isDataTable('#tornozeleirasTable')) {
        return tableTornozeleiras.destroy();
    }
    tableTornozeleiras = $('#tornozeleirasTable').DataTable( {
        responsive: true,
        processing: true,
        order: [0, 'desc'],
        otherOptions: {},
        initComplete: function() {
            $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
        },
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

    //instanciar datatable da tabela de suprimentos
    if ($.fn.DataTable.isDataTable('#suprimentosTable')) {
        return tableSuprimentos.destroy();
    }
    tableSuprimentos = $('#suprimentosTable').DataTable( {
        responsive: true,
        processing: true,
        order: [0, 'desc'],
        otherOptions: {},
        initComplete: function() {
            $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
        },
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


});


//eventos das placas -------------------------------------------------------------------
$(document).on('click', '.placas', function(e) {
    e.preventDefault();

    //limpa a tabela
    tablePlacas.clear();
    tablePlacas.draw();

    //pega as inf guardadas no botao
    var botao = $(this);
    id_contrato = botao.attr('data-id_contrato');

    //adiona o numero do contrato ao titulo do modal
    $('#id_contrato_placas').html(id_contrato);

    //configurações do modal
    $('#placas_do_contrato').modal({
        show: true,
        keyboard: false
    });

    $.ajax({
        url: site_url + '/contratos/ajax_lista_placas',
        type: 'GET',
        dataType: 'JSON',
        data: {
            id_cliente: id_cliente,
            id_contrato: id_contrato,
        },
        beforeSend: function(){
            // criamos o loading
            $('#placasTable > tbody').html(
              '<tr class="odd">' +
                '<td valign="top" colspan="6" class="dataTables_empty">Carregando&hellip;</td>' +
              '</tr>'
            );
        },
        success:function(data){
            if (data.status == 'OK') {
                tablePlacas.clear();
                tablePlacas.rows.add(data.table);
                tablePlacas.draw();
            }else {
                tablePlacas.clear();
                tablePlacas.draw();
            }

        }
    });

});

//ATIVA/INATIVA CHIP/TORNOZELEIRA
$(document).on('click', '.status', function(e) {
    e.preventDefault();
    var botao = $(this);
    botao.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', true);
    var controller = $(this).attr('data-controller');
    var id = $(this).attr('data-id');
    var texto = botao.text();

    $.get(controller, function(result) {
        if(result.success) {
            if (botao.data('status') == 'ativo') {
               botao.addClass('active btn-success');
               botao.html('Ativo').attr('disabled', false);
               $('.inativo_'+id).removeClass('active btn-danger');
           }else{
               botao.addClass('active btn-danger');
               botao.html('Inativo').attr('disabled', false);
               $('.ativo_'+id).removeClass('active btn-success');
           }

        } else {
            alert(result.msg);
            botao.html('Ativo').attr('disabled', false).removeClass('active');
        }
    }, 'json');
});

//Inicio posicao -----------------------------------------------
$(document).on('click', '.posicao', function () {
   var botao = $(this);
   var status = botao.attr('data-status');
   var placa = botao.attr('data-placa');
   var href = $(this).attr('href');

   botao.html('<i class="fa fa-minus"></i> Posição');

   var tr = $(this).closest('tr');
   var row = tablePlacas.row( tr );

   if ( row.child.isShown() ) {
       // esconde a linha filha
       botao.html('<i class="fa fa-plus"></i> Posição');
       row.child.hide();

   }else {
       //esconde todas as linhas filhas abertas
      tablePlacas.rows().eq(0).each( function ( idx ) {
           var linha = tablePlacas.row( idx );
           if ( linha.child.isShown() )
               linha.child.hide();
       });

       // abre a linha filha
       var id_placa = row.data().id;
       row.child( linhaFilha(placa, status, id_placa)).show();
       if (status == 'ativo') {
           $.ajax({
               url: href,
               dataType: 'html',
               success: function (html) {
                   $('#vincularSpan'+id_placa).html(html);
               }
           });
       }

   }

});

// fim eventos das placas


//eventos chips  ------------------------------------------------------------------------
$(document).on('click', '.chips', function(e) {
    e.preventDefault();
    var botao = $(this);
    var url = botao.attr('href');
    var id_contrato = botao.attr('data-id_contrato');

    //adiona o numero do contrato ao titulo do modal
    $('#id_contrato_chips').html(id_contrato);

    //configurações do modal
    $('#chips_do_contrato').modal({
        show: true,
        keyboard: false
    });

    $.ajax({
        url: url,
        dataType: 'JSON',
        beforeSend: function(){
            // criamos o loading
            $('#chipsTable > tbody').html(
              '<tr class="odd">' +
                '<td valign="top" colspan="6" class="dataTables_empty">Carregando&hellip;</td>' +
              '</tr>'
            );
        },
        success:function(data){
            if (data.status == 'OK') {
                tableChips.clear();
                tableChips.rows.add(data.table);
                tableChips.draw();
            }else {
                tableChips.clear();
            }

        }
    });

});
//
// $('#addChip').click(function(event){
//     event.preventDefault()
//     var botao = $(this);
//     botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
//
//     var chip = $('input[name=chip]').val();
//     var id_contrato = $('input[name=id_contrato]').val();
//     var ccid = $('input[name=ccid]').val();
//     var status = $('input[name=status]:checked').val();
//
//     $('.placa-alert').css('display', 'block');
//
//     $.ajax({
//         url: site_url + '/contratos/ajax_add_chip/'+id_contrato+'/'+id_cliente,
//         type: 'POST',
//         data: {
//             chip: chip,
//             status: status,
//             ccid: ccid
//         },
//         dataType: 'json',
//         success: function(cback){
//             if (cback.success == true)
//                 $("#mensagem").html('<div class="alert alert-success"><p><b>'+cback.msg+' no contrato #'+id_contrato+'!</b></p></div>');
//             else
//                 $("#mensagem").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
//
//             botao.html('<i class="icon-plus icon-white"></i> Salvar').attr('disabled', false);
//
//             $('#novo_chip').on('hidden.bs.modal', function () {
//                 $('input[name=ccid]').val('');
//                     $('input[name=chip]').val('');
//                 $('input[name=id_contrato]').val('');
//             });
//
//             $('#novo_chip').modal('hide');
//
//         },
//         error: function(cback){
//             botao.html('<i class="icon-plus icon-white"></i> Salvar').attr('disabled', false);
//
//             $("#mensagem").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
//             $('#novo_chip').modal('hide');
//         }
//     });
//
// });

//CADASTRA UM NOVO CHIP AO CONTRATO
$("#formAddChip").submit(function(e){
     e.preventDefault();
     var dadosform = $(this).serialize();
     var botao = $('#addChip');

     $.ajax({
         url: site_url+'/contratos/ajax_add_chip/'+id_contrato+'/'+id_cliente,
         type: 'POST',
         data: dadosform,
         dataType: 'json',
         beforeSend: function () {
             botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
         },
         success: function(cback){
             if (cback.success){
                 $("#msn_novo_chip").html('<div class="alert alert-success"><p><b>'+cback.msg+' no contrato #'+id_contrato+'!</b></p></div>');
                 //reseta todos os dados do formulario
                 $('#formAddChip')[0].reset();

             }else{
                $("#msn_novo_chip").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
             }
         },
         error: function(cback){
             $("#msn_novo_chip").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
         },
         complete: function(cback){
             //mostra a mensagem de retorno
             $('.add_chip_alert').css('display', 'block');
             botao.html('Salvar').attr('disabled', false);
         }
     });
});


/*
* AO CLICAR NO BOTAO DE FEICHAR A MENSAGEM, A MESMA SERA REMOVIDA DA VIEW
*/
function fecharMensagem(mensagem){
    //esconde o campo da mensagem de cadastro de placa
    $('.'+mensagem).css('display', 'none');
}

// $(document).on('click', '.closeAlertAddPlaca', function(){
//     //esconde o campo da mensagem de cadastro de placa
//     $('.add_placa_alert').css('display', 'none');
// });


//fim eventos chips


//eventos iscas --------------------------------------------------------------------------------------
$(document).on('click', '.iscas', function(e) {
    e.preventDefault();
    var botao = $(this);
    var url = botao.attr('href');
    var id_contrato = botao.attr('data-id_contrato');

    //adiona o numero do contrato ao titulo do modal
    $('#id_contrato_iscas').html(id_contrato);

    //configurações do modal
    $('#iscas_do_contrato').modal({
        show: true,
        keyboard: false
    });

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data: { id_contrato: id_contrato },
        beforeSend: function(){
            // criamos o loading
            $('#iscasTable > tbody').html(
              '<tr class="odd">' +
                '<td valign="top" colspan="6" class="dataTables_empty">Carregando&hellip;</td>' +
              '</tr>'
            );
        },
        success:function(data){
            if (data.status == 'OK') {
                tableIscas.clear();
                tableIscas.rows.add(data.table);
                tableIscas.draw();
            }else {
                tableIscas.clear();
            }
        }
    });

});

//CADASTRA UMA NOVA ISCA AO CONTRATO
$("#formAddIsca").submit(function(e){
     e.preventDefault();
     var dadosform = $(this).serialize()+'&id_cliente='+id_cliente+'&id_contrato='+id_contrato;
     var botao = $('#addIsca');

    if(!validarDadosCadastroIscaContrato(dadosform)) {
        return false;
    }

     $.ajax({
         url: site_url + '/contratos/ajax_add_isca',
         type: 'POST',
         data: dadosform,
         dataType: 'json',
         beforeSend: function () {
             botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
         },
         success: function(cback){
             if (cback.success){
                 $("#msn_nova_isca").html('<div class="alert alert-success"><p><b>'+cback.msg+' no contrato #'+id_contrato+'!</b></p></div>');
                 //reseta todos os dados do formulario
                 $('#formAddIsca')[0].reset();
                 $("#serialCadIsca").val('').trigger('change') ;;

             }else{
                $("#msn_nova_isca").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
             }
         },
         complete: function(cback){
             //mostra a mensagem de retorno
             $('.add_isca_alert').css('display', 'block');
             botao.html('Salvar').attr('disabled', false);
         }
     });
});

function validarDadosCadastroIscaContrato(dados) {
    let data = dados.split('&');

    if(data[0].split('=')[0] != 'serial') { // Verifica se serial é null
        alert('Digite o serial.');
        return false;
    } else if(!data[1].split('=')[1]) { // Verifica se marca é null
        alert('Digite a marca.');
        return false;
    } else if(!data[2].split('=')[1]) {
        alert('Digite o modelo.');
        return false;
    }

    return true;
}

function editarIsca(id_isca){
    let btn = $("#btnEditarIsca"+id_isca);
    btn.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', true);
    $.ajax({
        url: site_url+'/iscas/isca/getDadosIsca',
        type: 'GET',
        data: {id_isca: id_isca},
        success: function(data){
            btn.html('<i class="fa fa-pencil fa-lg" aria-hidden="true"></i>').attr('disabled',false);

            let isca = JSON.parse(data);

            $("#editarIdIsca").val(isca.id);
            $("#editarSerialIsca").val(isca.serial);
            $("#editarModeloIsca").val(isca.modelo);
            $("#editarMarcaIsca").val(isca.marca);
            $("#editarDescricaoIsca").val(isca.descricao);

            $("#editar_isca").modal('show');
            $("#iscas_do_contrato").modal('hide');


        },
        error: function(error){
            btn.html('<i class="fa fa-pencil fa-lg" aria-hidden="true"></i>').attr('disabled',false);
            alert('Erro ao buscar Isca');
        }
    });

}

function validarDadosEditarIscaContrato(dados) {

    if(!dados.serial) { // Verifica se serial é null
        alert('Digite o serial.');
        return false;
    } else if(!dados.marca) { // Verifica se marca é null
        alert('Digite a marca.');
        return false;
    } else if(!dados.modelo) {
        alert('Digite o modelo.');
        return false;
    }

    return true;
}

//CADASTRA UMA NOVA ISCA AO CONTRATO
$("#formEditarIsca").submit(function(e){
    e.preventDefault();
    let data = {
        id: $('#editarIdIsca').val(),
        serial: $("#editarSerialIsca").val(),
        marca: $("#editarMarcaIsca").val(),
        modelo: $("#editarModeloIsca").val(),
        descricao: $("#editarDescricaoIsca").val(),
    }

    if(!validarDadosEditarIscaContrato(data)) {
        return false;
    }

    var botao = $('#editarIsca');

    $.ajax({
        url: site_url + '/iscas/isca/updateDadosIsca',
        type: 'POST',
        data: data,
        dataType: 'json',
        beforeSend: function () {
            botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
        },
        success: function(cback){
            alert(cback.msg);
            if(cback.status){
                $("#editar_isca").modal('hide');
                $("#iscas_do_contrato").modal('show');

                let isca = cback.isca;
                tableIscas.rows().every(function(){
                    const row = this;
                    const linha = row.data();

                    if(linha != undefined){
                        const id_row = linha[0];

                        if(id_row == isca.id){ //verifica o id
                            temp = linha;
                            temp[1] = isca.serial;
                            temp[2] = isca.marca;
                            temp[3] = isca.modelo;
                            temp[6] = isca.descricao;
                            row.data(temp).draw();
                        }
                    }
                });
            }

        },
        error: function(error){
            console.log(error)
            alert('Sessão expirada, faça login novamente.');
            location.reload();
        },
        complete: function(cback){
            //mostra a mensagem de retorno
            $('.add_isca_alert').css('display', 'block');
            botao.html('Salvar').attr('disabled', false);
        }
    });
});

$("#closeModalEditar").click(()=>{
    $("#iscas_do_contrato").modal('show');
});

function ativar_inativar_isca(id_isca,status){
    if(status==1){
        $('#ativo_'+id_isca).html('<i class="fa fa-spinner fa-spin"></i> Ativando').attr('disabled', true);
    }else{
        $('#inativo_'+id_isca).html('<i class="fa fa-spinner fa-spin"></i> Inativando').attr('disabled', true);
    }
    $.ajax({
        url: site_url+'/contratos/ajax_atualiza_status_isca/'+id_isca+'/'+status,
        type: 'POST',
        dataType: 'JSON',
        data: { id_contrato: id_contrato },
        success:function(data){
            console.log(data);
            alert(data.msg);
            if(status == 1){
                $("#buttons_radio_status_isca"+id_isca).html(`
                    <button type="button" onClick="ativar_inativar_isca(${id_isca},1)" id="ativo_${id_isca}" class="btn btn-small status ativo_${id_isca}  active btn-success" data-status="ativo" style="color: #fff; background-color: #449d44; border-color: #449d44;" data-id="${id_isca}">Ativo</button>
                    <button type="button" onClick="ativar_inativar_isca(${id_isca},0)" id="inativo_${id_isca}" class="btn btn-small status inativo_${id_isca}" data-status="inativo" style="color: #000; background-color: #efefef; border-color: #efefef;" data-id="${id_isca}">Inativo</button>
                `);
                $('#ativo_'+id_isca).html('Ativo').attr('disabled', false);
            }
            else{
                $("#buttons_radio_status_isca"+id_isca).html(`
                <button type="button" onClick="ativar_inativar_isca(${id_isca},1)" id="ativo_${id_isca}" class="btn btn-small status ativo_${id_isca} btn-success" data-status="ativo" style="color: #000; background-color: #efefef; border-color: #efefef;" data-id="${id_isca}">Ativo</button>
                <button type="button" onClick="ativar_inativar_isca(${id_isca},0)" id="inativo_${id_isca}" class="btn btn-small status inativo_${id_isca} active" data-status="inativo" style="color: #fff; background-color: #c9302c; border-color: #c9302c;" data-id="${id_isca}">Inativo</button>
                `);
                $('#inativo_'+id_isca).html('Inativo').attr('disabled', false);
            }


        }
    });

}
//fim eventos iscas

//eventos tornozeleiras --------------------------------------------------------------------------------------
$(document).on('click', '.tornozeleiras', function(e) {
    e.preventDefault();
    var botao = $(this);
    var url = botao.attr('href');
    var id_contrato = botao.attr('data-id_contrato');

    //adiona o numero do contrato ao titulo do modal
    $('#id_contrato_tornozeleira').html(id_contrato);

    //configurações do modal
    $('#tornozeleiras_do_contrato').modal({
        show: true,
        keyboard: false
    });

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data: { 
            id_cliente: id_cliente,
            id_contrato: id_contrato 
        },
        beforeSend: function(){
            // criamos o loading
            $('#tornozeleirasTable > tbody').html(
              '<tr class="odd">' +
                '<td valign="top" colspan="6" class="dataTables_empty">Carregando&hellip;</td>' +
              '</tr>'
            );
        },
        success:function(data){
            if (data.status == 'OK') {
                tableTornozeleiras.clear();
                tableTornozeleiras.rows.add(data.table);
                tableTornozeleiras.draw();
            }else {
                tableTornozeleiras.clear();
            }
        }
    });

});


//CADASTRA UMA NOVA TORNOZELEIRA AO CONTRATO
$("#formAddTornozeleira").submit(function(e){
     e.preventDefault();
     var dadosform = $(this).serialize()+'&id_cliente='+id_cliente+'&id_contrato='+id_contrato;
     var botao = $('#addTornozeleira');

     $.ajax({
         url: site_url + '/contratos/ajax_add_tornozeleira',
         type: 'POST',
         data: dadosform,
         dataType: 'json',
         beforeSend: function () {
             botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
         },
         success: function(cback){
             if (cback.success){
                 $("#msn_nova_tornozeleira").html('<div class="alert alert-success"><p><b>'+cback.msg+' no contrato #'+id_contrato+'!</b></p></div>');
                 //reseta todos os dados do formulario
                 $('#formAddTornozeleira')[0].reset();

             }else{
                $("#msn_nova_tornozeleira").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
             }
         },
         error: function(cback){
             $("#msn_nova_tornozeleira").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
         },
         complete: function(cback){
             //mostra a mensagem de retorno
             $('.add_tornozeleira_alert').css('display', 'block');
             botao.html('Salvar').attr('disabled', false);
         }
     });
});

//fim eventos tornozeleira

//eventos suprimentos --------------------------------------------------------------------------------------
$(document).on('click', '.suprimentos', function(e) {
    e.preventDefault();
    var botao = $(this);
    var url = botao.attr('href');
    var id_contrato = botao.attr('data-id_contrato');

    //adiona o numero do contrato ao titulo do modal
    $('#id_contrato_suprimento').html(id_contrato);

    //configurações do modal
    $('#suprimentos_do_contrato').modal({
        show: true,
        keyboard: false
    });

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data: { id_contrato: id_contrato },
        beforeSend: function(){
            // criamos o loading
            $('#suprimentosTable > tbody').html(
              '<tr class="odd">' +
                '<td valign="top" colspan="6" class="dataTables_empty">Carregando&hellip;</td>' +
              '</tr>'
            );
        },
        success:function(data){
            if (data.status == 'OK') {
                tableSuprimentos.clear();
                tableSuprimentos.rows.add(data.table);
                tableSuprimentos.draw();
            }else {
                tableSuprimentos.clear();
            }
        }
    });

});

$(document).on('click', '.btn_status', function () {
    let id_con_sup = $(this).attr('data-id');
    let id_sup = $(this).attr('data-id_sup');
    let status = $(this).attr('data-status');
    let id_cliente =$(this).attr('data-cliente');

    let previousHtml = $(this).html();
    $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + previousHtml);

    $.ajax({
        url: site_url + '/contratos/ajax_atualiza_status_sup/' + id_con_sup + '/' + id_sup + '/' + id_cliente + '/' + status,
        type: "POST",
        dataType: "json",
        cache: false,
        success: callback => {
            if (callback.success == true) {

                if ($(this).attr('data-status') == 'ativo') {
                    $(this).attr('data-status', 'inativo').removeClass('btn-success').addClass('btn-danger').html('Inativar');
                } else {
                    $(this).attr('data-status', 'ativo').removeClass('btn-danger').addClass('btn-success').html('Ativar');
                }
            } else {
                alert(callback.msg);
                $(this).html(previousHtml);
            }

            $(this).attr('disabled', false);
        },
        error: error => {
            $(this).attr('disabled', false);
        }
    });
});

//CADASTRA UMA NOVO SUPRIMENTO AO CONTRATO
$("#formAddSuprimento").submit(function(e){
     e.preventDefault();
     var dadosform = $(this).serialize()+'&id_cliente='+id_cliente+'&id_contrato='+id_contrato;
     var botao = $('#addSuprimento');

     $.ajax({
         url: site_url + '/contratos/ajax_add_suprimento',
         type: 'POST',
         data: dadosform,
         dataType: 'json',
         beforeSend: function () {
             botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
         },
         success: function(cback){
             if (cback.success){
                 $("#msn_novo_suprimento").html('<div class="alert alert-success"><p><b>'+cback.msg+' no contrato #'+id_contrato+'!</b></p></div>');
                 //reseta todos os dados do formulario
                 $('#formAddSuprimento')[0].reset();

             }else{
                $("#msn_novo_suprimento").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
             }
         },
         error: function(cback){
             $("#msn_novo_suprimento").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
         },
         complete: function(cback){
             //mostra a mensagem de retorno
             $('.add_suprimento_alert').css('display', 'block');
             botao.html('Salvar').attr('disabled', false);
         }
     });
});

//fim eventos suprimentos

function linhaFilha ( placa, status, id_placa ) {
    var vinc = false;
    if (status == 'ativo') {
        vinc = '<div style="background-color: #f5f5f5; padding: 10px;">'+
                    '<span id="vincularSpan'+id_placa+'">'+
                        'Carregando...'+
                    '</span>'+
                '</div>';
    }else {
        vinc = '<div class="alert alert-default">'+
                    '<h4>Placa #'+placa+' INATIVA!</h4>'+
                    '<p>Ação aceita apenas para placas <b>ATIVAS</b>.</p>'+
                '</div>';

    }
    return vinc;

}

//adiciona o id_contrato ao formulario
$(document).on('click', '.add_placa', function(e) {
    e.preventDefault();
    $("#id_contrato")[0].value =  $(this).attr('data-id_contrato');
});

//captura o id do contrato e reseta os formularios de cadastro
$(document).on('click', '.add', function(e) {
    e.preventDefault();
    id_contrato =  $(this).attr('data-id_contrato');

    //reseta todos os dados do formulario de add placa
    $('.add_placa_alert').css('display', 'none');
    $('#formAddPlaca')[0].reset();

    //reseta todos os dados do formulario de add multi placa
    $('.add_multi_placa_alert').css('display', 'none');
    $('#dvExcel').text('');
    document.getElementById('fileUpload').value='';

    //reseta todos os dados do formulario de add chip
    $('.add_chip_alert').css('display', 'none');
    $('#formAddChip')[0].reset();

    //reseta todos os dados do formulario de add isca
    $('.add_isca_alert').css('display', 'none');
    $('#formAddIsca')[0].reset();

    //reseta todos os dados do formulario de add tornozeleira
    $('.add_tornozeleira_alert').css('display', 'none');
    $('#formAddTornozeleira')[0].reset();

    $("#serialCadIsca").val('').trigger('change') ;;

});

$("#formAddPlaca").submit(function(e){
     e.preventDefault();
     var dadosform = $(this).serialize()+'&status=cadastrado&id_contrato='+id_contrato+'&id_cliente='+id_cliente;
     var botao = $('#addPlaca');

     $.ajax({
         url: site_url + '/contratos/ajax_add_placa/' + id_contrato,
         type: 'POST',
         data: dadosform,
         dataType: 'json',
         beforeSend: function () {
             botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...').attr('disabled', true);
         },
         success: function(cback){
             if (cback.success == true){
                 $("#msn_nova_placa").html('<div class="alert alert-success"><p><b>'+cback.msg+' no contrato #'+id_contrato+'!</b></p></div>');
                 //reseta todos os dados do formulario
                 $('#formAddPlaca')[0].reset();

             }else{
                $("#msn_nova_placa").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
             }
         },
         error: function(cback){
             $("#msn_nova_placa").html('<div class="alert alert-danger"><p><b>'+cback.msg+'</b></p></div>');
             // $('#nova_placa').modal('hide');
         },
         complete: function(cback){
             //mostra a mensagem de retorno
             $('.add_placa_alert').css('display', 'block');
             botao.html('<i class="icon-plus icon-white"></i> Salvar').attr('disabled', false);
         }
     });
});

//insere o valor id_contrato no modal add multi placas
$(document).on('click', '.add_multi_placa', function(e) {
    e.preventDefault();
    var botao = $(this);
    var id_contrato = botao.attr('data-id_contrato');
    $("#id_contrato_multiplaca")[0].value = id_contrato;

    //limpa os dados
    $('#dvExcel').text('');
    document.getElementById('fileUpload').value='';
    $('.add_multi_placa_alert').css('display', 'none');
});

//insere o valor id_contrato no modal de iscas em lote
$(document).on('click', '.add_contrato_iscas', function(e) {
    e.preventDefault();
    var botao = $(this);
    var id_contrato = botao.attr('data-id_contrato');
    var id_cliente = botao.attr('data-id_cliente');
    $("#id_contrato_iscas_lote")[0].value = id_contrato;
    $("#id_cliente_iscas_lote")[0].value = id_cliente;

    //limpa os dados
    $('#iscasLoteExcel').text('');
    $('#descricao_iscas').val('');
    $('#arquivoIscasLote').val('');

});


$('#cadastrarVeiculos').click(function(){
    var botao = $(this);
    botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
    var id_contrato = $('input[name=id_contrato_multiplaca]').val();

    $.ajax({
        url: site_url + '/veiculos/cadastrar_veiculo_lote',
        type: 'POST',
        dataType: 'json',
        data: {
            veiculos: JSON.stringify(excelRows),
            id_cliente: id_cliente,
            id_contrato: id_contrato
        },
        success: function (callback) {
            $('.add_multi_placa_alert').css('display', 'block');
            if (callback.status == 'OK')
                $("#msn_nova_multi_placa").html('<div class="alert alert-success"><p><b>'+callback.msg+' no contrato #'+id_contrato+'!</b></p></div>');
            else
                $("#msn_nova_multi_placa").html('<div class="alert alert-danger"><p><b>'+callback.msg+'</b></p></div>');

            botao.html('<i class="icon-plus icon-white"></i> Salvar').attr('disabled', false);

            $('#novas_placas').on('hidden.bs.modal', function () {  //limpa os campos do formulário
                $('input[name=file]').val('');
                $("#dvExcel tr").remove();
                document.getElementById('cadastrarVeiculos').disabled = true;
            });
        },
        error: function(callback){
            botao.html('<i class="icon-plus icon-white"></i> Salvar').attr('disabled', false);
            $('.add_multi_placa_alert').css('display', 'block');
            $("#msn_nova_multi_placa").html('<div class="alert alert-danger"><p><b>'+callback.msg+'</b></p></div>');
        }
    });

});

/*
* EVENTO PARA ATIVAR/EDITAR UMA PLACA/VEICULO
*/
$(document).on('click', '.ativar_placa', function(e) {
    e.preventDefault();
    var botao = $(this);
    list_id_placa = botao.attr('data-placa_id');
    list_href_ativar = botao.attr('href_ativar');
    list_href_inativar = botao.attr('href_inativar');
    btnAcaoPlaca = botao.attr('data-acao');

    var tr = $(this).closest('tr');
    row = tablePlacas.row( tr );

    //abre uma linha filha ou esconde-a
    if ( row.child.isShown() ) {
        row.child.hide();    // esconde a linha filha

    }else {

        //esconde todas as linhas filhas abertas
       tablePlacas.rows().eq(0).each( function ( idx ) {
            var linha = tablePlacas.row( idx );
            if ( linha.child.isShown() )
                linha.child.hide();
        });

        // abre a linha filha, carregando a view de editar/ativar
        row.child(
            '<div style="background-color: #f5f5f5; padding: 10px;">'+
                        '<span id="vincularSpan'+list_id_placa+'">'+
                            'Carregando...'+
                        '</span>'+
                    '</div>'
        ).show();
        $.ajax({
            url: list_href_ativar,
            dataType: 'html',
            success: function (html) {
                $('#vincularSpan'+list_id_placa).html(html);
            }
        });
    }

});


//ativa-editar placa-veiculo
$(document).on('click', '.inativar_placa', function(e) {
    e.preventDefault();
    var botao = $(this);
    var id_placa = botao.attr('data-placa_id');
    var href = botao.attr('href');
    botao.html('<i class="fa fa-spinner fa-spin"></i>');
    $.get(href, function(result) {
        if(!result.success) {
            botao.html('Inativar');
            alert(result.msg);
        } else {
            if (result.tipo) {
                alert(result.msg);
                //acoes do botao
                botao.html('Inativar').attr('disabled', true);
                //muda o status para inativo e alterado a cor do campo
                $(".status_"+id_placa).removeClass('label-primary')
                .addClass('label-default').text('inativo');
                //atualiza botao editar_veiculo
                $('.btn_ativo_'+id_placa).text('Ativar');
                //atualiza data-status do botao de posição
                $(".btnPosicao_"+id_placa).attr('data-status', 'inativo');

            }else{
                alert(result.msg);
                //acoes do botao
                botao.html('Inativar').attr('disabled', true);
                //muda o status para inativo
                $(".status_"+id_placa).removeClass('label-success')
                .addClass('label-default').text('inativo');
                //atualiza botao editar_veiculo
                $('.btn_ativo_'+id_placa).text('Ativar');
                //atualiza data-status do botao de posição
                $(".btnPosicao_"+id_placa).attr('data-status', 'inativo');
            }
        }
    }, 'json');
});

$('#modal_serial').attr('data-backdrop', 'static');

//confere se o tipo de arquivo é o requerido  -- cadastrar placas em lote
$('#fileUpload').change(function () {
   //Reference the FileUpload element.
   var fileUpload = document.getElementById("fileUpload");
   $('#dvExcel').text('');

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
       // Limpa os campos
       document.getElementById('fileUpload').value='';
       $('#dvExcel').text('');
   }
   document.getElementById('cadastrarVeiculos').disabled = false;

});

//confere se o tipo de arquivo é o requerido  -- cadastrar iscas em lote
$('#arquivoIscasLote').change(function () {
    //Reference the FileUpload element.
    var fileUpload = document.getElementById("arquivoIscasLote");
    $('#iscasLoteExcel').text('');

    //Validate whether File is valid Excel file.
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
    if (regex.test(fileUpload.value.toLowerCase())) {
        if (typeof (FileReader) != "undefined") {
            var reader = new FileReader();

            //For Browsers other than IE.
            if (reader.readAsBinaryString) {
                reader.onload = function (e) {
                    ProcessExcelIscas(e.target.result);
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
                    ProcessExcelIscas(data);
                };
                reader.readAsArrayBuffer(fileUpload.files[0]);
            }
        } else {
            alert("O browser não suporta HTML5.");
        }
    } else {
        alert("Por favor, use um arquivo excel válido.");
        // Limpa os campos
        document.getElementById('arquivoIscasLote').value='';
        $('#iscasLoteExcel').text('');
    }
    document.getElementById('cadastrarIscasLote').disabled = false;

 });

 /* Acao do botao de salvar na modal de cadastrar iscas em lote */
$('#cadastrarIscasLote').click(function(){
    var botao = $(this);
    botao.html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
    var descricao = $('#descricao_iscas').val();
    var id_contrato = $('input[name=id_contrato_iscas_lote]').val();
    var id_cliente = $('input[name=id_cliente_iscas_lote]').val();
    var iscas = JSON.stringify(excelRowsIscas);

    $.ajax({
        url: site_url + '/contratos/ajax_add_iscas_lote',
        type: 'POST',
        dataType: 'json',
        data: {
            iscas: iscas,
            descricao: descricao,
            id_contrato: id_contrato,
            id_cliente: id_cliente
        },
        success: function (callback) {
            let totalIscas = JSON.parse(iscas).length;
            let falhas = JSON.parse(callback.falhas);

            if (callback.status && falhas.length < totalIscas) {
                alert(
                    callback.msg
                    + (falhas.length
                        ? '\nErro ao cadastrar algumas iscas.'
                        + (gerarMensagemErro(falhas))
                        : ''
                    )
                );
                esconderModalIscasLoteLimparCampos();

            } else {
                alert(
                    callback.msg
                    + (falhas.length && !callback.msg
                        ? '\nErro ao cadastrar algumas iscas.'
                        + (gerarMensagemErro(falhas))
                        : ''
                    )
                );
            }
            botao.html('<i class="icon-plus icon-white"></i> Salvar').attr('disabled', false);

        },
        error: function(callback){
            alert("Erro ao cadastrar iscas, tente novamente.");
            botao.html('<i class="icon-plus icon-white"></i> Salvar').attr('disabled', false);
        }
    });

});

function gerarMensagemErro(falhas) {
    let notFound = [];
    let conflict = [];
    let forbidden = [];
    let unknown = [];

    falhas.forEach(falha => {
        errorCode = falha.msg.substr(0, 3);
        if(errorCode == '404') {
            notFound.push(falha.serial);
        } else if(errorCode == '409') {
            conflict.push(falha.serial);
        } else if(errorCode = '403') {
            forbidden.push(falha.serial);
        } else {
            unknown.push(falha.serial);
        }
    });

    return (
        (notFound.length ? '\nOs seguintes seriais não existem: ' + notFound.map( falha => { return falha }) + '.' : '')
        + (conflict.length ? '\nOs seguintes seriais já estão cadastrados em outros contratos: ' + conflict.map( falha => { return falha }) + '.' : '')
        + (forbidden.length ? '\nAs seguintes iscas não podem ser cadas tradas: ' + forbidden.map( falha => { return falha }) + '.' : '')
        + (unknown.length ? '\nOs seguintes seriais não podem ser cadastradas: ' + unknown.map( falha => { return falha }) + '.' : '')
    );
}

function esconderModalIscasLoteLimparCampos() {
    $('#nova_isca_lote').modal('hide');
    $('#nova_isca_lote').on('hidden.bs.modal', function () {  //limpa os campos do formulário
        $('input[name=file]').val('');
        $("#iscasLoteExcel tr").remove();
        document.getElementById('cadastrarIscasLote').disabled = true;
    });
}

function selecionarSecretaria(id_veic_contrato,grupo){
   $.ajax({
       type: "POST",
       url: site_url + '/contratos/vincular_secretaria',
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
   if ( excelRows[0].Placa && excelRows[0].Serial && excelRows[0].Nome) {
       //Create a HTML Table element.
       var table = document.createElement("table");
       table.width = "100%";
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
   }else {
       var dvExcel = document.getElementById("dvExcel");
       dvExcel.innerHTML = "Dados inconsistentes, Verifique que os nomes das colunas: Placa, Serial e Nome, não podem ter espaços ou qualquer outro caracter!";
       document.getElementById('cadastrarVeiculos').disabled = true;
       document.getElementById('fileUpload').value='';
   }


}

/* Processo o excel enviado para cadastro de iscas em lote */
function ProcessExcelIscas(data) {
    //Read the Excel File data.
    var workbook = XLSX.read(data, {
        type: 'binary'
    });

    //Fetch the name of First Sheet.
    var firstSheet = workbook.SheetNames[0];

    //Read all rows from First Sheet into an JSON array.
    excelRowsIscas = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
    if ( excelRowsIscas[0].Serial && excelRowsIscas[0].Modelo
        && excelRowsIscas[0].Marca && excelRowsIscas[0].Status) {
        //Create a HTML Table element.
        var table = document.createElement("table");
        table.width = "100%";
        table.border = "1";

        //Add the header row.
        var row = table.insertRow(-1);

        //Add the header cells.
        var headerCell = document.createElement("TH");
        headerCell.innerHTML = "Serial";
        row.appendChild(headerCell);

        headerCell = document.createElement("TH");
        headerCell.innerHTML = "Modelo";
        row.appendChild(headerCell);

        headerCell = document.createElement("TH");
        headerCell.innerHTML = "Marca";
        row.appendChild(headerCell);

        headerCell = document.createElement("TH");
        headerCell.innerHTML = "Placa";
        row.appendChild(headerCell);

        headerCell = document.createElement("TH");
        headerCell.innerHTML = "Status";
        row.appendChild(headerCell);

        //Add the data rows from Excel file.
        for (var i = 0; i < excelRowsIscas.length; i++) {
            //Add the data row.
            var row = table.insertRow(-1);

            //Add the data cells.
            var cell = row.insertCell(-1);
            cell.innerHTML = excelRowsIscas[i].Serial;

            cell = row.insertCell(-1);
            cell.innerHTML = excelRowsIscas[i].Modelo;

            cell = row.insertCell(-1);
            cell.innerHTML = excelRowsIscas[i].Marca;

            cell = row.insertCell(-1);
            cell.innerHTML = excelRowsIscas[i].Placa ?? '';

            cell = row.insertCell(-1);
            cell.innerHTML = excelRowsIscas[i].Status;
        }

        var dvExcel = document.getElementById("iscasLoteExcel");
        dvExcel.innerHTML = "";
        dvExcel.appendChild(table);
    }else {
        var dvExcel = document.getElementById("iscasLoteExcel");
        dvExcel.innerHTML = "Dados inconsistentes, Verifique que os nomes das colunas: Serial, Modelo, Marca, Placa, Status não podem ter espaços ou qualquer outro caractere!";
        document.getElementById('cadastrarIscasLote').disabled = true;
        document.getElementById('arquivoIscasLote').value='';
    }


 }
