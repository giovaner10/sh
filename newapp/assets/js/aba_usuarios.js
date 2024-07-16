$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#table_users')) {
        return table_users.destroy();
    }
    table_users = $('#table_users').DataTable({
        paging: true,
        autoFill: false,
        responsive: true,
        otherOptions: {},
        initComplete: function() {
            $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');

            //verifica se a permissão de cadastro está liberada para este usuário
            !permissoes['cad_cadastrarusuariosshownetgestor'] ? desabilitaBotao = `disabled title='Você não tem permissão para cadastrar novos usuários.'` : desabilitaBotao = ''

            let botao_novo_usuario = `
                <div>
                    <button class="btn btn-primary" id="btnUsuario" data-toggle="modal" data-modal="#novouser" onclick="render(this)" ${ desabilitaBotao } data-target="#novo_usuario"><i class="fa fa-plus"></i>
                        ${ lang.novo_usuario }
                    </button>
                </div>`;
            $('#table_users_length').html(botao_novo_usuario).css('margin-bottom', '10px');


            let filtros_usuarios = `                
                <div style="float: right;">
                    <form autocomplete="off">
                        <label>${lang.pesquisar}
                            <input type="search" id="pesquisa_tabela_usuarios" class="" placeholder="" aria-controls="table_users" value="" style="height: 28px;">
                        </label>
                    </form>
                </div>
                <div style="float: right; margin-right: 20px;">
                    <label>${lang.status}:
                        <select id="filtroStatusUsuarios" style="width:180px; height:28px;">
                            <option value="todos">${lang.todos}</option>
                            <option value="Liberado|Released">${lang.liberado}</option>
                            <option value="Bloqueado|Blocked">${lang.bloqueado }</option>
                        </select>
                    </label>
                </div>`;
            $('#table_users_filter').html(filtros_usuarios).css('width', '80%');            
        },
        language: langDatatable,
        processing: true,
    });


    if ($.fn.DataTable.isDataTable('#editUserPerm')) {
        return userPerm.destroy();
    }
    userPerm = $('#editUserPerm').DataTable({
        // ajax: site_url + "/cadastros/ajax_permissoes_cliente/" +id_cliente,
        columns: [
            {
                searchable: false,
                orderable: false
            },
            null
        ],
        order: [1, 'asc'],
        paging: false,
        info: false,
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
    // });

    /* LOGAR USUÁRIO */
    $(document).on('click','.logar', async function (e) {
        e.preventDefault();

        let botao = $(this);
        var user = $(this).attr('data-email');
        var pass = $(this).attr('data-user');
        var id = $(this).attr('data-id');
        var prestadora = informacoes;
        var host = window.location.host.split(":")[0];

        let urlGestor = '';

        if (prestadora === 'OMNILINK') {
            if (host === "localhost") {
                urlGestor = "http://localhost/gestor/index.php/";
            }
            else if (host === "homologa.showtecnologia.com" || host === '3.15.117.58') {
                urlGestor = `http://${window.location.host}/gestor/index.php`;
            }
            else {
                urlGestor = "https://gestor.omnilink.com.br/gestor/index.php/";
            }
        } 
        else {
            if (host === "localhost") {
                urlGestor = "http://localhost/gestor/index.php/";
            }
            else if (host === "homologa.showtecnologia.com" || host === '3.15.117.58') {
                urlGestor = `http://${window.location.host}/gestor/index.php`;
            }
            else  {
                urlGestor = "https://gestor.showtecnologia.com/gestor/index.php/";
            }
        }

        botao.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        const ip = await buscarIpUsuario()
            .then(resposta => resposta.ip)
            .catch(error => console.error('Erro ao tentar buscar o IP do usuário, erro:', error));

        const dadosUsuario = {
            usuario: user,
            senha: pass,
            id: id,
            ip: ip,
            origem: 'site_show_net'            
        }

        await logarNoUsuarioDoCliente(urlGestor, dadosUsuario, botao)

        return false;
    });

    //Metodo para logar no usuário
    async function logarNoUsuarioDoCliente(urlGestor, dadosUsuario, botao) {
        const url = `${site_url}/clientes/logar_usuario`;

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: dadosUsuario,
            success: async resposta => {
                await trataStatusResposta(dadosUsuario, resposta, urlGestor);
            },
            error: error => {
                alert('Não foi possível logar no usuário, tente novamente mais tarde.');
                console.error('Erro ao tentar logar no usuário, erro:', error);
            },
            complete: () => {
                botao.attr('disabled', false).html('<i class="fa fa-power-off"></i>');
            }
        });

    }

    async function buscarIpUsuario() {
        return $.ajax({
          url: `https://api.ipify.org?format=json`,
          type: 'GET',
          dataType: 'json',
        });  
      }    

    //Monsta o formulario e redireciona para o gestor
    function redirecionaParaGestor(dadosUsuario, resposta, urlGestor) {

        $.ajax({
            url: `${site_url}/clientes/salvar_auditoria`,
            type: "POST",
            dataType: "json",
            data: dadosUsuario,
            success: function (dados) {
            }
        });

        var form = document.createElement("form");
        form.target = "_blank";
        form.method = "GET";
        form.dataType = "json";
        form.action = urlGestor + 'login/iniciarAcesso';
        form.style.display = "none";

        var inputSessao = document.createElement("input");
        inputSessao.type = "hidden";
        inputSessao.name = 'token';
        inputSessao.value = resposta.token;
        form.appendChild(inputSessao);

        var inputUsuario = document.createElement("input");
        inputUsuario.type = "hidden";
        inputUsuario.name = 'usuario';
        inputUsuario.value = dadosUsuario.usuario;
        form.appendChild(inputUsuario);

        var inputSenha = document.createElement("input");
        inputSenha.type = "hidden";
        inputSenha.name = 'senha';
        inputSenha.value = dadosUsuario.senha;
        form.appendChild(inputSenha);

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    //Trata o status da resposta
    async function trataStatusResposta(dadosUsuario, resposta, urlGestor) {
        if ([1, 3].includes(resposta?.status)) return redirecionaParaGestor(dadosUsuario, resposta, urlGestor); // Status de sucesso e bloqueio parcial, redireciona para o gestor
        if ([0, 2, -6, -7, -8, -9].includes(resposta?.status)) return alert(resposta.mensagem); // Erros de: cliente bloqueado, usuario bloqueado e Erros de validação de filtro por IP
        if (resposta?.status === 5) { // Status de 2fa, mostra os campos de 2fa
            return alert('É necessário informar o código de autenticação de 2 fatores.');
        }
        if (resposta?.status === -2) return alert("Usuário não encontrado."); // Erro de usuário não encontrado
        if (resposta?.status === -4) return alert("Usuário ou senha inválido."); // Erro de usuário não encontrado
        if (resposta?.status === -5) return alert("Senha expirada."); // Erro de usuário não encontrado
        if (resposta?.status === -3) { // Erro de usuário bloqueado temporariamente
        return alert(`Acesso temporariamente bloqueado por excesso de tentativas incorretas. tente novamente em ${resposta?.segundosAteDesbloqueio} segundos.`);
        }
        
        // return alert('Usuário ou senha inválidos.');
        return alert("Falha na requisição, tente novamente mais tarde.");
    }


    /* ATIVAR USUÁRIO */
    $(document).on('click','.ativar',function () {
        var button_ativ = $(this);
        button_ativ.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>')

        $.getJSON(button_ativ.data('url'), function (callback) {
            if (callback.status == 'OK') {
                let code = button_ativ.data('id');
                table_users.ajax.reload(null, true);
                button_ativ.removeAttr('data-url').attr('data-url', site_url + '/usuarios_gestor/update_status/inativo/' + code);
                button_ativ.removeAttr('disabled').removeClass('btn-danger ativar').addClass('btn-success inativar').html('<i class="fa fa-check"></i>');
            } else {
                alert(callback.msg);
            }
        });
    });

    /* DESATIVAR USUÁRIO */
    $(document).on('click','.inativar', function () {
        var button_ativ = $(this);
        button_ativ.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>')

        $.getJSON(button_ativ.data('url'), function (callback) {
            if (callback.status == 'OK') {
                let code = button_ativ.data('id');
                table_users.ajax.reload(null, true);
                button_ativ.removeAttr('data-url').attr('data-url', site_url + '/usuarios_gestor/update_status/ativo/' + code);
                button_ativ.removeAttr('disabled').removeClass('btn-success inativar').addClass('btn-danger ativar').html('<i class="fa fa-ban"></i>');
            } else {
                alert(callback.msg);
            }
        });
    });

    //FORM PERMISSÃO USUARIO
    $('form#userPerm').submit(function() {
        let data = $(this).serialize();

        let permissoes = new Array();
        userPerm.$('input[name^="permissoes"]:checked').each(function(){
            permissoes.push(this.value);
        });

        let dados = {
            id_user: $('input[name=id_user]').val(),
            permissoes: permissoes.join(',')
        };
        $.ajax({
            url: site_url + "/usuarios_gestor/ajax_permissoes_salvar",
            type: "POST",
            dataType: "json",
            data: dados,
            beforeSend: settings => {
                $('form#userPerm button.btn-submit').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
            },
            success: callback => {
                $('form#userPerm button.btn-submit').attr('disabled', false).html('Salvar');
                alert(callback.msg);
                $('#modalEditPerm').modal('hide');
            },
            error: error => {
                $('form#userPerm button.btn-submit').attr('disabled', false).html('Salvar');
                console.log('Error: '+ error);
            }
        })

        return false;
    });

    // var clientes = new ModuleClientes();
    // clientes.init();
    if($('#bloqueio').text() != 1) {
        $('.ativo').prop('href', '#');
        $('.inativo').prop('href', '#');
    }

    /** Abre Modal de Edição */
    $(document).on('click', '.edit_permissoes', function() {

        let id_user = $(this).attr('data-id');
        // Insere ID_USER atual (click)
        $('form#userPerm input[name="id_user"]').val(id_user);

        // Limpa marcação de checkbox's
        $('form#userPerm input[type="checkbox"]').prop('checked', false);

        $.ajax({
            url: site_url + "/cadastros/ajax_permissoes_usuario",
            type: "post",
            dataType: 'json',
            data: {id: id_user},
            beforeSend: settings => {
                $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: callback => {
                $(this).attr('disabled', false).html('<i class="fa fa-sitemap"></i>');

                if (typeof callback !== 'undefined' && Array.isArray(callback)) {
                    $.each(callback, function(i, a) {
                        userPerm.$('input[value="'+a+'"]').prop('checked', true);
                    });
                }
                userPerm.search('').draw();
                $('#modalEditPerm').modal('show');
            },
            error: error => {
                $(this).attr('disabled', false).html('<i class="fa fa-sitemap"></i>');
            }
        })
    });

})


//ADICIONA O STATUS SELECIONADO PARA FILTRAR OS DADOS CARREGADOS NA TABELA DE USUARIOS
$(document).on('change', '#filtroStatusUsuarios', function (e) {
    e.preventDefault();

    let status = $("#filtroStatusUsuarios option:selected").val()
    if (status !== 'todos') {
        table_users.column(5).search(status, true, false).draw();
    }
    else {
        table_users.column(5).search('', true, false).draw();
    }
});

//GERENCIA A BUSCA NA TABELA
$(document).on('keyup', '#pesquisa_tabela_usuarios', function (e) {
    e.preventDefault();

    let status = $(this).val()
    table_users.search(status, true, false).draw();    
});