$(document).ready(function () {

    $('#id_cliente').select2({
        ajax: {
            url: site_url + '/relatorios/listAjaxSelectClient',
            dataType: 'json'
        },
        width: '85%',
        language: idioma
    });


    //INSTANCIA A TABELA 
    tabelaConsultas = $('#tabelaConsultas').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        order: [0, 'asc'],
        columns: [
            { data: 'id' },
            {
                data: 'cpf_profissional',
                render: function (data, type, row, meta) {
                    return criarMascara(data, '###.###.###-##');
                }

            },
            { data: 'cnh' },
            {
                data: 'cpf_cnpj_proprietario',
                render: function (data, type, row, meta) {
                    if (data)
                        return data.length == 11 ? criarMascara(data, "###.###.###-##") : criarMascara(data, "##.###.###/####-##");
                    else
                        return '';
                }
            },
            { data: 'placa_veiculo' },
            { data: 'placa_carreta' },
            { data: 'placa_segunda_carreta' },
            { data: 'nome_usuario' },
            {
                data: 'acao',
                render: function (data, type, row, meta) {
                    if (data === 'cadastro') return lang.cadastro;
                    else return lang.consulta;
                }
            },
            {
                data: 'data_cadastro',
                render: function (data, type, row, meta) {
                    var data = data.split(' ');
                    var dataTemp = data[0].split('-');
                    return `${dataTemp[2]}/${dataTemp[1]}/${dataTemp[0]} ${data[1]}`;
                }
            },
            {
                data: 'admin',
                orderable: false,
                render: function (data, type, row, meta) {
                    return `<button class="btn btn-mini btn-primary visualizar_perfil" data-id="${row.id}" title="${lang.visualizar_perfil}" ><i class="fa fa-eye"></i></button>`;
                }
            }
        ],
        buttons: [
            {
                filename: filenameGenerator(lang.relatorio_consulta_perfis),
                extend: 'pdfHtml5',
                orientation: customPageExport('tabelaConsultas', 'orientation'),
                pageSize: customPageExport('tabelaConsultas', 'pageSize'),
                className: 'btn btn-outline-primary',
                text: 'PDF',
                messageTop: function () {
                    var di = $('#data_inicial').val();
                    var data_ini = new Date(di + ' 00:00:00').toLocaleDateString('pt-BR');
                    var df = $('#data_final').val();
                    var data_fim = new Date(df + ' 00:00:00').toLocaleDateString('pt-BR');
                    return $('#totais').text() + ' | ' + lang.cliente + ': ' + $('#id_cliente option:selected').text() + ' | ' + lang.periodo + ': ' + data_ini + ' - ' + data_fim;
                },
                customize: function (doc, tes) {
                    let titulo = lang.relatorio_consulta_perfis;
                    // Personaliza a página do PDF
                    pdfTemplate(doc, titulo)
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                }
            },
            {
                title: lang.relatorio_consulta_perfis,
                extend: 'excelHtml5',
                orientation: customPageExport('tabelaConsultas', 'orientation'),
                pageSize: customPageExport('tabelaConsultas', 'pageSize'),
                className: 'btn btn-outline-primary',
                text: 'EXCEL',
                messageTop: function () {
                    var di = $('#data_inicial').val();
                    var data_ini = new Date(di + ' 00:00:00').toLocaleDateString('pt-BR');
                    var df = $('#data_final').val();
                    var data_fim = new Date(df + ' 00:00:00').toLocaleDateString('pt-BR');
                    return $('#totais').text() + ' | ' + lang.cliente + ': ' + $('#id_cliente option:selected').text() + ' | ' + lang.periodo + ': ' + data_ini + ' - ' + data_fim;
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            },
            {
                title: lang.relatorio_consulta_perfis,
                extend: 'csvHtml5',
                orientation: customPageExport('tabelaConsultas', 'orientation'),
                pageSize: customPageExport('tabelaConsultas', 'pageSize'),
                className: 'btn btn-outline-primary',
                text: 'CSV',
                messageTop: function () {
                    var di = $('#data_inicial').val();
                    var data_ini = new Date(di + ' 00:00:00').toLocaleDateString('pt-BR');
                    var df = $('#data_final').val();
                    var data_fim = new Date(df + ' 00:00:00').toLocaleDateString('pt-BR');
                    return $('#totais').text() + ' | ' + lang.cliente + ': ' + $('#id_cliente option:selected').text() + ' | ' + lang.periodo + ': ' + data_ini + ' - ' + data_fim;
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            },
            {
                filename: filenameGenerator(lang.relatorio_consulta_perfis),
                extend: 'print',
                orientation: customPageExport('tabelaConsultas', 'orientation'),
                pageSize: customPageExport('tabelaConsultas', 'pageSize'),
                className: 'btn btn-outline-primary',
                text: lang.imprimir.toUpperCase(),
                messageTop: function () {
                    var di = $('#data_inicial').val();
                    var data_ini = new Date(di + ' 00:00:00').toLocaleDateString('pt-BR');
                    var df = $('#data_final').val();
                    var data_fim = new Date(df + ' 00:00:00').toLocaleDateString('pt-BR');
                    return $('#totais').text() + ' | ' + lang.cliente + ': ' + $('#id_cliente option:selected').text() + ' | ' + lang.periodo + ': ' + data_ini + ' - ' + data_fim;
                },
                customize: function (win) {
                    let titulo = lang.relatorio_consulta_perfis;
                    // Personaliza a página Impressa
                    printTemplate(win, titulo);
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            }
        ],
        language: lang.datatable
    });

});


//CARREGA A TABELA COM OS DADOS FILTRADOS
$('#formConsultasPerfis').submit(function (e) {
    e.preventDefault();

    var botao = $('.btnFormConsultasPerfis');
    var data_form = $(this).serialize();

    $.ajax({
        url: site_url + '/relatorios/ajax_custos_consultas_perfis',
        data: data_form,
        type: 'POST',
        dataType: 'json',
        beforeSend: function () {
            botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + lang.gerando);

            // criamos o loading
            $('#tabelaConsultas > tbody').html(
                `<tr class="odd">
                            <td valign="top" colspan="12" class="dataTables_empty">${lang.carregando}</td>
                        </tr>`
            );
        },
        success: function (retorno) {
            if (retorno.success) {
                // Atualiza Tabela
                tabelaConsultas.clear();
                tabelaConsultas.rows.add(retorno.table);
                tabelaConsultas.draw();

                //Atualiza os totais
                $('#totais').html('<b>' + lang.total_consultas + ':</b> ' + retorno.quantidadeTotal);

            } else {
                //Atualiza os totais
                $('#totais').html('');

                //Limpa a tabela
                tabelaConsultas.clear().draw();

                alert(retorno.msg);
            }
        },
        error: function () {
            //Atualiza os totais
            $('#totais').html('');

            //Limpa a tabela
            tabelaConsultas.clear().draw();

            alert(lang.operacao_nao_finalizada);
        },
        complete: function () {
            botao.attr('disabled', false).html(lang.gerar);
        }
    });

});


//Abre o modal de perfil para edita-lo
$(document).on('click', '.visualizar_perfil', function (e) {
    e.preventDefault();

    var botao = $(this);
    id_log = botao.attr('data-id');

    $('#tituloVisualizarPerfil').html(lang.consulta + ' #' + id_log);

    // //Limpa o modal
    limparModalVisualizarConsulta();

    $.ajax({
        url: site_url + '/PerfisProfissionais/ajax_load_consulta',
        type: 'GET',
        dataType: 'JSON',
        data: { id_log },
        beforeSend: function () {
            botao.html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', true);
            $('#div_alertas_consulta').css('display', 'none');
            $('#alertas_consulta').text('');
        },
        success: function (data) {
            if (data.success) {
                var id_consulta = data.retorno.id_consulta;
                var profissional = data.retorno.profissional;
                var proprietario = data.retorno.proprietario;
                var veiculo = data.retorno.veiculo;
                var carreta = data.retorno.carreta;
                var segunda_carreta = data.retorno.segunda_carreta;
                var consulta_cpf = data.retorno.consulta_cpf;
                var consulta_cnh = data.retorno.consulta_cnh;
                var consulta_antecedentes = data.retorno.consulta_antecedentes;
                var consulta_mandados = data.retorno.consulta_mandados;
                var consulta_processos = data.retorno.consulta_processos;
                var consulta_debitos = data.retorno.consulta_debitos;
                var consulta_veiculo = data.retorno.consulta_veiculo;
                var consulta_carreta = data.retorno.consulta_carreta;
                var consulta_segunda_carreta = data.retorno.consulta_segunda_carreta;
                var score = data.retorno.score;
                var protocolo = data.retorno.protocolo;
                var ponto_corte = data.retorno.ponto_corte;
                var consta_obito = data.retorno.consta_obito;

                let consulta_sucesso = ['1', '2'];

                $('#select_mandados').select2({
                    width: '100%'
                });

                $('#select_processos').select2({
                    width: '100%'
                });

                Object.keys(profissional).forEach(item => {
                    //ADICIONA OS VALORES DOS CAMPOS
                    $(`#${item}`).text(profissional[item]).css('margin-left', '10px');
                });

                if (Object.keys(proprietario).length) {
                    Object.keys(proprietario).forEach(item => {
                        //ADICIONA OS VALORES DOS CAMPOS
                        $(`#${item}_proprietario`).text(proprietario[item]).css('margin-left', '10px');
                    });
                    $('#menu_aba_proprietario').css('display', 'block');

                } else {
                    $('#menu_aba_proprietario').css('display', 'none');
                }

                if (Object.keys(veiculo).length) {
                    Object.keys(veiculo).forEach(item => {
                        //ADICIONA OS VALORES DOS CAMPOS
                        $(`#${item}_veiculo`).text(veiculo[item]).css('margin-left', '10px');
                    });

                    $('#menu_aba_veiculo').css('display', 'block');
                } else {
                    $('#menu_aba_veiculo').css('display', 'none');
                }

                if (Object.keys(carreta).length) {
                    Object.keys(carreta).forEach(item => {
                        //ADICIONA OS VALORES DOS CAMPOS
                        $(`#${item}_carreta`).text(carreta[item]).css('margin-left', '10px');
                    });

                    $('#menu_aba_carreta').css('display', 'block');
                } else {
                    $('#menu_aba_carreta').css('display', 'none');
                }

                if (Object.keys(segunda_carreta).length) {
                    Object.keys(segunda_carreta).forEach(item => {
                        //ADICIONA OS VALORES DOS CAMPOS
                        $(`#${item}_segunda_carreta`).text(segunda_carreta[item]).css('margin-left', '10px');
                    });

                    $('#menu_aba_segunda_carreta').css('display', 'block');
                } else {
                    $('#menu_aba_segunda_carreta').css('display', 'none');
                }            

                //PLOTA OS DADOS DAS CONSULTAS -------------------------------------------

                //DADOS DA CONSULTA CPF
                if (consulta_sucesso.indexOf(consulta_cpf.status_consulta) !== -1) {
                    //ADICIONA OS VALORES DOS CAMPOS
                    Object.keys(consulta_cpf).forEach(item => {
                        $(`#${item}_consulta_cpf`).html(consulta_cpf[item]).css('margin-left', '10px');                    
                    });

                    $('#div_resultado_info_cpf').css('display', 'block');
                }
                else {
                    $('#div_msg_info_cpf').css('display', 'block');
                    $('#msg_info_cpf').html(consulta_cpf['message_consulta']);
                }

                //DADOS DA CONSULTA CNH
                if (consulta_sucesso.indexOf(consulta_cnh.status_consulta) !== -1) {
                    //ADICIONA OS VALORES DOS CAMPOS
                    Object.keys(consulta_cnh).forEach(item => {
                        $(`#${item}_consulta_cnh`).html(consulta_cnh[item]).css('margin-left', '10px');
                    });

                    $('#div_resultado_info_cnh').css('display', 'block');
                }
                else {
                    $('#div_msg_info_cnh').css('display', 'block');
                    $('#msg_info_cnh').html(consulta_cnh['message_consulta']);
                }

                //DADOS DA CONSULTA ANTECEDENTES
                if (consulta_sucesso.indexOf(consulta_antecedentes.status_consulta) !== -1) {
                    //ADICIONA OS VALORES DOS CAMPOS
                    Object.keys(consulta_antecedentes).forEach(item => {
                        $(`#${item}_consulta_antecedentes`).html(consulta_antecedentes[item]).css('margin-left', '10px');
                    });

                    $('#div_resultado_info_antecedentes').css('display', 'block');
                }
                else {
                    $('#div_msg_info_antecedentes').css('display', 'block');
                    $('#msg_info_antecedentes').html(consulta_antecedentes['message_consulta']);
                }                            

                //DADOS DA CONSULTA VEICULO
                if (data.retorno.consulta_veiculo !== undefined) {
                    if (consulta_sucesso.indexOf(consulta_veiculo.status_consulta) !== -1) {
                        //ADICIONA OS VALORES DOS CAMPOS
                        Object.keys(consulta_veiculo).forEach(item => {
                            $(`#${item}_consulta_veiculo`).html(consulta_veiculo[item]).css('margin-left', '10px');
                        });

                        $('#div_resultado_info_veiculo').css('display', 'block');
                    }
                    else {
                        $('#div_msg_info_veiculo').css('display', 'block');
                        $('#msg_info_veiculo').html(consulta_veiculo['message_consulta']);
                    }                    
                }

                //DADOS DA CONSULTA CARRETA
                if (data.retorno.consulta_carreta !== undefined) {
                    if (consulta_sucesso.indexOf(consulta_carreta.status_consulta) !== -1) {
                        //ADICIONA OS VALORES DOS CAMPOS
                        Object.keys(consulta_carreta).forEach(item => {
                            $(`#${item}_consulta_carreta`).html(consulta_carreta[item]).css('margin-left', '10px');
                        });

                        $('#div_resultado_info_carreta').css('display', 'block');
                    }
                    else {
                        $('#div_msg_info_carreta').css('display', 'block');
                        $('#msg_info_carreta').html(consulta_carreta['message_consulta']);
                    }              
                }

                //DADOS DA CONSULTA DA SEGUNDA CARRETA
                if (data.retorno.consulta_segunda_carreta !== undefined) {
                    if (consulta_sucesso.indexOf(consulta_segunda_carreta.status_consulta) !== -1) {
                        //ADICIONA OS VALORES DOS CAMPOS
                        Object.keys(consulta_segunda_carreta).forEach(item => {
                            $(`#${item}_consulta_segunda_carreta`).html(consulta_segunda_carreta[item]).css('margin-left', '10px');
                        });

                        $('#div_resultado_info_segunda_carreta').css('display', 'block');
                    }
                    else {
                        $('#div_msg_info_segunda_carreta').css('display', 'block');
                        $('#msg_info_segunda_carreta').html(consulta_segunda_carreta['message_consulta']);
                    }              
                }                

                //DADOS DA CONSULTA MANDADOS DE PRISAO
                if (data.retorno.consulta_mandados !== undefined) {
                    if (consulta_sucesso.indexOf(consulta_mandados[0].status_consulta) !== -1) {
                        if (consulta_mandados[0].numero_processo) {
                            $('#div_resultado_info_mandados').css('display', 'block');
    
                            //MONTA O SELECT
                            $('#select_mandados').html('<option value="">' + lang.selecione_um_mandado + '</option>');
                            $.each(consulta_mandados, function (i, d) {
                                $('#select_mandados').append('<option value="' + d.id_consulta_mandados + '">' + d.numero_processo + '</option>');
                                mandados[d.id_consulta_mandados] = d;
                            });
                        }
                        else {
                            $('#div_msg_info_mandados').css('display', 'block');
                            $('#msg_info_mandados').html(lang.msg_documento_nao_possui_mandados);
                        }                        
                    }
                    else {
                        $('#div_msg_info_mandados').css('display', 'block');
                        $('#msg_info_mandados').html(consulta_mandados[0].message_consulta);
                    }
                }

                //DADOS DA CONSULTA PROCESSOS
                if (data.retorno.consulta_processos !== undefined) {                    
                    if (consulta_sucesso.indexOf(consulta_processos[0].status_consulta) !== -1) {
                        if (consulta_processos[0].numero) {
                            $('#div_resultado_info_processos').css('display', 'block');

                            //MONTA O SELECT
                            $('#select_processos').html('<option value="">' + lang.selecione_um_processo + '</option>');
                            $.each(consulta_processos, function (i, d) {
                                $('#select_processos').append('<option value="' + d.id_consulta_processos + '">' + d.numero + '</option>');
                                processos[d.id_consulta_processos] = d;
                            });
                        }
                        else {
                            $('#div_msg_info_processos').css('display', 'block');
                            $('#msg_info_processos').html(lang.msg_documento_nao_possui_processos);
                        }
                    }
                    else {
                        $('#div_msg_info_processos').css('display', 'block');
                        $('#msg_info_processos').html(consulta_processos[0].message_consulta);
                    }
                }

                //DADOS DA CONSULTA DEBITOS FINANCEIROS
                if (data.retorno.consulta_debitos !== undefined) {                    
                    if (consulta_sucesso.indexOf(consulta_debitos[0].status_consulta) !== -1) {
                        if (consulta_debitos[0].cnpj) {
                            $('#div_resultado_info_debitos').css('display', 'block');

                            //MONTA O SELECT
                            $('#select_debitos').html('<option value="">' + lang.selecione_um_debito + '</option>');
                            $.each(consulta_debitos, function (i, d) {
                                $('#select_debitos').append('<option value="' + d.id_consulta_debitos + '">' + d.cnpj + '</option>');
                                debitos[d.id_consulta_debitos] = d;
                            });
                        }
                        else {
                            $('#div_msg_info_debitos').css('display', 'block');
                            $('#msg_info_debitos').html(lang.msg_documento_nao_possui_debitos);
                        }
                    }
                    else {
                        $('#div_msg_info_debitos').css('display', 'block');
                        $('#msg_info_debitos').html(consulta_debitos[0].message_consulta);
                    }
                }

                if (consta_obito === 'sim') {
                    $('#div_alertas_consulta').css('display', 'block');
                    $('#alertas_consulta').text(lang.cpf_consta_obito);
                    $('#resultado_consulta').text(lang.nao_indicado.toUpperCase()).removeClass('label-success').addClass('label-danger');
                    score = '100';
                }
                else {
                    if (parseFloat(score) < parseFloat(ponto_corte)) {
                        $('#resultado_consulta').text(lang.nao_indicado.toUpperCase()).removeClass('label-success').addClass('label-danger');
                    }
                    else {
                        $('#resultado_consulta').text(lang.indicado.toUpperCase()).removeClass('label-danger').addClass('label-success');
                    }
                }
                
                //MOSTRA A PONTUACAO E O PROTOCOLO DA CONSULTA
                $('#pontuacao_consulta').html(score + ' / 1000').css('margin-left', '5px');
                $('#protocolo_consulta').html(protocolo).css('margin-left', '5px');


                //ABRE O MODAL
                $('#modalVisualizarConsultar').modal('show');

            } else {
                alert(data.msg);
            }
        },
        error: function () {
            alert(lang.falha_na_requisicao);
        },
        complete: function () {
            botao.html('<i class="fa fa-eye"></i>').attr('disabled', false);
        }
    });

});

$(document).on('change', '#select_mandados', function (e) {
    var id_mandado = $(this).val();
    if (mandados.length) {        
        if (id_mandado !== '') {
            $('#mostrar_divs_mandados').css('display', 'block');
            Object.keys(mandados[id_mandado]).forEach(item => {
                //ADICIONA OS VALORES DOS CAMPOS
                $(`#${item}_consulta_mandados`).html(mandados[id_mandado][item]).css('margin-left', '10px');
            });

        } else {
            $('#mostrar_divs_mandados').css('display', 'none');
            $(`.dados_mandados`).html('');
        }
    }
});

$(document).on('change', '#select_processos', function (e) {
    var id_processo = $(this).val();
    if (processos.length) {
        if (id_processo !== '') {
            $('#mostrar_divs_processos').css('display', 'block');
            Object.keys(processos[id_processo]).forEach(item => {
                //ADICIONA OS VALORES DOS CAMPOS
                $(`#${item}_consulta_processos`).html(processos[id_processo][item]).css('margin-left', '10px');
            });

        } else {
            $('#mostrar_divs_processos').css('display', 'none');
            $(`.dados_processos`).html('');
        }
    }
});


$(document).on('change', '#select_debitos', function (e) {
    var id_debito = $(this).val();
    if (debitos.length) {
        if (id_debito !== '') {
            $('#mostrar_divs_debitos').css('display', 'block');
            Object.keys(debitos[id_debito]).forEach(item => {
                //ADICIONA OS VALORES DOS CAMPOS
                $(`#${item}_consulta_debitos`).html(debitos[id_debito][item]).css('margin-left', '10px');
            });

        } else {
            $('#mostrar_divs_debitos').css('display', 'none');
            $(`.dados_debitos`).html('');
        }
    }
});


//ALTERA O ICONE DO COLLAPSE QUANDO CLICADO PARA ABRIR OU FECHAR
$('.collapse').on('shown.bs.collapse', function () {
    $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
}).on('hidden.bs.collapse', function () {
    $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
});


/*
* RESETA AS INFORMACOES DO FORMULARIO DO CADASTRAO DE PERFIL
*/
function limparModalVisualizarConsulta() {
    let campos_consulta_cpf = ['cpf', 'nome', 'data_nascimento', 'situacao_cadastral', 'data_inscricao', 'consta_obito', 'path_file', 'status_consulta', 'message_consulta'];
    let campos_consulta_cnh = ['numero_registro', 'numero_seguranca', 'nome_condutor', 'data_validade', 'data_emissao', 'categoria', 'numero_formulario_cnh', 'situacao', 'path_file', 'status_consulta', 'message_consulta'];
    let campos_consulta_veiculo = ['placa', 'codigo_renavam', 'chassi', 'ano_modelo', 'ano_fabricacao', 'numero_identificacao_proprietario', 'nome_proprietario', 'situacao', 'indicador_roubo_furto', 'indicador_pendencia_emissao', 'indicador_restricao_renajud', 'indicador_multa_renainf', 'path_file', 'status_consulta', 'message_consulta'];
    let campos_consulta_antecedentes = ['possui_anteced_criminais', 'numero_certidao', 'data_emissao', 'observacoes', 'path_file', 'status_consulta', 'message_consulta'];
    let campos_consulta_mandados = ['numero_processo', 'nome_pessoa', 'descricao_status', 'numero_peca', 'descricao_peca', 'data_expedicao', 'nome_orgao', 'status_consulta', 'message_consulta'];

    campos_consulta_cpf.forEach(element => { $(`#${element}_consulta_cpf`).html(''); });
    campos_consulta_cnh.forEach(element => { $(`#${element}_consulta_cnh`).html(''); });
    campos_consulta_veiculo.forEach(element => { $(`#${element}_consulta_veiculo`).html(''); });
    campos_consulta_veiculo.forEach(element => { $(`#${element}_consulta_carreta`).html(''); });
    campos_consulta_veiculo.forEach(element => { $(`#${element}_consulta_segunda_carreta`).html(''); });
    campos_consulta_antecedentes.forEach(element => { $(`#${element}_consulta_antecedentes`).html(''); });
    campos_consulta_mandados.forEach(element => { $(`#${element}_consulta_mandados`).html(''); });

    $('.detalhes_consulta').css('display', 'none');
}


/**
 * Cria um QrCode usando a api do google chart
*/
function GeraQRCode(conteudo, largura, altura) {
    var imagemQRCode = `<img src="https://chart.googleapis.com/chart?chs=${largura}x${altura}&cht=qr&chl=${conteudo}" title="QrCode" style="padding:0px;">`;
    return imagemQRCode;
}