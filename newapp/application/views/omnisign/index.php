<style>
    .destinatario-label {
        display: inline-flex;
        align-items: center;
        margin: 2px;
        color: white;
        padding: 5px;
        border-radius: 5px;
    }

    .destinatario-label .acao {
        margin-left: 5px;
        cursor: pointer;
        color: #ffdddd;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= versionFile('assets/css/vendasDeSoftware', 'style.css') ?>">

<!-- Modal destinatarios -->
<div class="modal fade" id="destinatariosModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Destinatários do Email</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="destinatariosList"></div>
                <input type="email" id="novoDestinatario" placeholder="Adicionar novo destinatário" class="form-control">
                <div>
                    <span class="label label-info">Pendente</span>
                    <span class="label label-success">Assinado</span>
                    <span class="label label-danger">Rejeitado</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary btnAddNewSign" onclick="adicionarNovoDestinatario()">Adicionar</button>
            </div>
        </div>
    </div>
</div>

<div class="text-title">
    <h3 style="padding: 0 20px; margin-left: 15px;"><?=lang("assinatura_eletronica")?></h3>
    <h4 style="padding: 0 20px; margin-left: 15px;">
        <a style="text-decoration: none"><?= lang('inicio') ?></a>
    </h4>
</div>

<div class="row container-fluid">
    <div class="col-md-3">
        <div class="card menu-interno">
            <h4 style="margin-bottom: 0px !important;">Menu</h4>
            <ul>
                <li>
                    <a class='menu-interno-link selected' href="<?= site_url('omnisigns/index') ?>">Meus Documentos</a>
                </li>
                <li>
                    <a class='menu-interno-link' href="<?= site_url('omnisigns/inbox') ?>">Caixa de Entrada</a>
                </li>
            </ul>
        </div>
        <div class="card" style="padding-bottom: 20px;">
            <h4>Novo documento</h4>
            <form id="formNovoDocumento">
                <div class="form-group">
                    <label for="nomeDocumento">Nome do Documento</label>
                    <input type="text" class="form-control" name="nomeDocumento" id="nomeDocumento" required>
                </div>
                <div class="form-group">
                    <label for="categoriaDocumento">Categoria</label>
                    <select class="form-control" name="categoriaDocumento" id="categoriaDocumento" required>
                        <option value="">Selecione</option>
                        <option value="Financeiro">Financeiro</option>
                        <option value="Jurídico">Jurídico</option>
                        <option value="RH">RH</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="arquivoDocumento">Arquivo do Documento</label>
                    <input type="file" class="form-control-file" id="arquivoDocumento" name="arquivoDocumento" required>
                </div>
                <div class="form-group">
                    <label>Destinatários:</label>
                    <div id="destinatarios">
                        <div class="form-group">
                            <label for="nomeDestinatario-0">Nome:</label>
                            <input type="text" class="form-control" id="nomeDestinatario-0" name="destinatarios[0][nome]" required>
                        </div>
                        <div class="form-group">
                            <label for="emailDestinatario-0">Email:</label>
                            <input type="email" class="form-control" id="emailDestinatario-0" name="destinatarios[0][email]" required>
                        </div>
                    </div>
                    <hr>
                    <button type="button" id="adicionarDestinatario" class="btn btn-info">Adicionar Destinatário</button>
                </div>
            </form>

            <button type="submit" class="btn btn-primary" form="formNovoDocumento" id="salvarNovoDocumento">Salvar Documento</button>
        </div>
    </div>
    <div class="col-md-9 card-conteudo">
        <div class="row" style="margin-bottom:10px">
            <select id="select-quantidade-por-pagina-produtos" class="form-control" style="width: fit-content; float: left; margin-top: 10px;">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select><label style="margin-top: 15px; margin-left: 10px; float: left;">Resultados por página</label>
            <input class="form-control inputBusca" type="text" id="search-input-show-produtos" placeholder="Pesquisar" style="margin-top: 10px; float: right;">
        </div>
        <div class="row">
            <div id="myGrid" class="ag-theme-alpine my-grid" style="height: 460px; width:100%;"></div>
        </div>
    </div>
</div>

<script>
    var idFileOpen;

    // Colunas da tabela AG-Grid
    var columnDefs = [
        { headerName: "Nome do Documento", field: "nome" },
        { headerName: "Categoria", field: "categoria" },
        { headerName: "Data Cadastro", field: "data_cadastro" },
        {
            headerName: "Status",
            valueGetter: function(params) {
                switch (params.data.status) {
                    case '1':
                        return 'Assinado Parcialmente';
                    case '2':
                        return 'Assinado';
                    case '3':
                        return 'Rejeitado';
                    case '4':
                        return 'Cancelado';
                    default:
                        return 'Aguardando assinaturas';
                }
            }
        },
        {
            headerName: "Assinados",
            valueGetter: function(params) {
                var signed = params.data.signed ? params.data.signed : "0";
                var signature = params.data.signature ? params.data.signature : "0";
                return `${signed} / ${signature}`;
            }
        },
        {
            headerName: "Ações",
            pinned: 'right',
            cellRenderer: function(params) {
                let btnCancelar = `<button title="Cancelar documento" class="btn btn-sm btn-danger btn-cancelar" data-id="${params.data.id}"><i class="fa fa-times"></i></button>`;
                return `
                    <button title="Destinatários" class="btn btn-sm btn-info btn-destinatarios" data-id="${params.data.id}"><i class="fa fa-users"></i></button>
                    <a title="Documento" class="btn btn-sm btn-primary" target="_blank" href="${'../../'+params.data.arquivo}"><i class="fa fa-file-text-o"></i></a>
                    ${params.data.status != '4' ? btnCancelar : ''}
                `;
            }
        }
    ];

    // Configuração da AG-Grid
    var gridOptions = {
        columnDefs: columnDefs,
        getContextMenuItems: params => null,
        getRowNodeId: data => data.id,
        defaultColDef: {
            editable: false,
            sortable: true,
            minWidth: 100,
            minHeight: 100,
            filter: true,
            resizable: true,
        },
        sideBar: {
            toolPanels: [
                {
                    id: 'columns',
                    labelDefault: 'Columns',
                    labelKey: 'columns',
                    iconKey: 'columns',
                    toolPanel: 'agColumnsToolPanel',
                    toolPanelParams: {
                        suppressRowGroups: true,
                        suppressValues: true,
                        suppressPivots: true,
                        suppressPivotMode: true,
                        suppressColumnFilter: false,
                        suppressColumnSelectAll: false,
                        suppressColumnExpandAll: true,
                    },
                },
            ],
        },
        pagination: true,
        paginationPageSize: 10,
        onGridReady: function(event) {
            $.getJSON('getMyDocs', function(data) {
                event.api.setRowData(data);
            });
        }
    };

    // Espera o DOM carregar para inicializar a AG-Grid
    document.addEventListener('DOMContentLoaded', function () {
        var gridDiv = document.querySelector('#myGrid');
        new agGrid.Grid(gridDiv, gridOptions);

        var contadorDestinatario = 1;

        document.getElementById('adicionarDestinatario').addEventListener('click', function() {
            contadorDestinatario++;

            // Cria os campos de nome e email
            var div = document.createElement('div');
            div.setAttribute('class', 'destinatario');
            div.setAttribute('id', 'destinatario-' + contadorDestinatario);
            div.innerHTML = `
                <div class="form-group">
                    <label for="nomeDestinatario-${contadorDestinatario}">Nome:</label>
                    <input type="text" class="form-control" id="nomeDestinatario-${contadorDestinatario}" name="destinatarios[${contadorDestinatario}][nome]" required>
                </div>
                <div class="form-group">
                    <label for="emailDestinatario-${contadorDestinatario}">Email:</label>
                    <input type="email" class="form-control" id="emailDestinatario-${contadorDestinatario}" name="destinatarios[${contadorDestinatario}][email]" required>
                </div>
                <button type="button" class="btn btn-danger removerDestinatario">Remover</button>
                <hr>
            `;

            document.getElementById('destinatarios').appendChild(div);

            // Adiciona evento de clique para remover o destinatário
            div.querySelector('.removerDestinatario').addEventListener('click', function() {
                this.parentNode.remove();
            });
        });
    });

    document.getElementById('formNovoDocumento').addEventListener('submit', function(event){
        event.preventDefault();

        var formData = new FormData(this); // Cria um FormData com os dados do formulário, incluindo o arquivo

        // Desativa botão e inicia efeito
        $('#salvarNovoDocumento').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

        // Adicione aqui sua lógica de envio AJAX
        $.ajax({
            url: 'sendFileAsign',
            type: 'POST',
            data: formData,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status == true) {
                    gridOptions.api.applyTransaction({ add: [ response.data ] }); // Adiciona nova linha ao obter sucesso do novo registro
                }

                alert(response.message);
                $('#salvarNovoDocumento').removeAttr('disabled').html('Salvar Documento');
            },
            error: function(xhr, status, error) {
                $('#salvarNovoDocumento').removeAttr('disabled').html('Salvar Documento');
                alert('Não foi possível processar sua solicitação.');
            }
        });
    });

    /** Adiciona ao DOM o destinatario */
    function adicionarLabelDestinatario(element)
    {
        const lista = document.getElementById('destinatariosList');
        const label = document.createElement('span');

        switch (element.status) {
            case '1':
                label.classList.add('label-success');
                break;
            case '2':
                label.classList.add('label-danger');
                break;
            default:
                label.classList.add('label-info');
                break;
        }

        label.classList.add('destinatario-label');
        label.classList.add('novoDest-label-'+element.id);
        label.innerHTML = `
            ${element.destinatario}
            <span class="acao remover-destinatario btn-sm btn-danger" data-id="${element.id}"><i class="fa fa-close"></i></span>
            <span class="acao btnReenviarOmnisign btn-sm btn-success" data-id="${element.id}"><i class="fa fa-send"></i></span>
        `;
        lista.appendChild(label);
    }

    /** Função reenvia documento para assinatura ao destinatario */
    $(document).on('click', '.btnReenviarOmnisign', function() {
        let button = $(this);
        let id = button.attr('data-id');

        // Remove ação de clicks "pós"
        if (button.attr('disabled') == 'disabled') return false;

        // Verificação de segurança
        if (!confirm('Realmente deseja reenviar documento ao destinatário?'))
            return false;

        // Desabilita botão e inicia efeito de carregamento
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post('reenviarDocumento', { id }, response => {
            alert(response.message);
            button.removeAttr('disabled').html('<i class="fa fa-send"></i>');
        }, 'JSON').catch(e => {
            button.removeAttr('disabled').html('<i class="fa fa-send"></i>');
            alert('Não foi possível enviar o documento. Contate o administrador do sistema.');
        });
        
    });

    /** Função remove destinatario de um arquivo */
    $(document).on('click', '.remover-destinatario', function() {
        let button = $(this);
        let id = button.attr('data-id');

        // Remove ação de clicks "pós"
        if (button.attr('disabled') == 'disabled') return false;
        
        // Verificação de segurança
        if (!confirm('Realmente deseja remover o destinatário? Essa ação não poderá ser desfeita.'))
            return false;

        // Desabilita botão e inicia efeito de carregamento
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post('removeDestinatario', { id }, response => {
            if (response.status == true) {
                $('span.novoDest-label-'+id).remove();
            } else {
                button.removeAttr('disabled').html('<i class="fa fa-close"></i>');
                alert('Não foi possível remover o destinatário. Tente novamente em alguns minutos!');
            }
        }, 'JSON').catch(e => {
            button.removeAttr('disabled').html('<i class="fa fa-close"></i>');
            alert('Ocorreu um erro inesperado. Contate o administrador do sistema.');
        });
    });

    /** Função cancela um omnisign (Documento) */
    $(document).on('click', '.btn-cancelar', function() {
        let button = $(this);
        let idFile = button.attr('data-id');

        // Remove ação de clicks "pós"
        if (button.attr('disabled') == 'disabled') return false;

        // Confirma ação com usuário
        if (!confirm('Realmente deseja cancelar a solicitação de assinatura? Essa ação não poderá ser desfeita.')) return false;

        // Adiciona efeito ao botão e o desabilita
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post('cancelOmnisign', { idFile }, response => {
            if (response.status == true) {
                let node = gridOptions.api.getRowNode(idFile); // Recupera Node Ag-Grid
                let dadosDeAtualizacao = Object.assign({}, node.data); // Converte retorno para utilização
                dadosDeAtualizacao.status = '4'; // Atualiza status para cancelado
                
                // Atualiza registro da tabela
                gridOptions.api.updateRowData({ update: [ dadosDeAtualizacao ] });
            } else {
                button.removeAttr('disabled').html('<i class="fa fa-times"></i>');
                alert(response.message);
            }
        }, 'JSON').catch(e => {
            button.removeAttr('disabled').html('<i class="fa fa-times"></i>');
            alert('Ocorreu um erro inesperado. Contate o administrador do sistema.');
        });
    });

    /** Função busca dados de distanarios e exibe modal */
    $(document).on('click', '.btn-destinatarios', function() {
        let button = $(this);
        let idFile = button.attr('data-id');

        // Remove ação de clicks "pós"
        if (button.attr('disabled') == 'disabled') return false;

        // Limpa a lista de destinatarios
        $('#destinatariosList').html('');

        // Adiciona efeito ao botão e o desabilita
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.getJSON('getDestinatarios', { id: idFile }, response => {
            if (response.status == true) {
                response.data.forEach(element => {
                    adicionarLabelDestinatario(element);
                });

                // Adiciona id a variavel de controle de Documento pesquisado
                idFileOpen = idFile;
                
                // Manda habilitar ou desabilitar input e botão de acordo com status da linha
                validaStatusDocModalDestinatarios(idFile);

                $('#destinatariosModal').modal('show');
            }
        }).then(r => button.removeAttr('disabled').html('<i class="fa fa-users"></i>'))
        .catch(e => button.removeAttr('disabled').html('<i class="fa fa-users"></i>'));
    });

    /** Função responsável pelo controle do input e botão de cadastrar novos destinatarios do modal */
    function validaStatusDocModalDestinatarios(idFile)
    {
        let node = gridOptions.api.getRowNode(idFile); // Recupera Node Ag-Grid
        let dados = Object.assign({}, node.data); // Converte retorno para utilização

        if (dados.status == '4') {
            $('#novoDestinatario').attr('disabled', true);
            $('button.btnAddNewSign').attr('disabled', true);
        } else {
            $('#novoDestinatario').removeAttr('disabled');
            $('button.btnAddNewSign').removeAttr('disabled');
        }
    }


    /** Função adiciona um novo destinatário */
    function adicionarNovoDestinatario()
    {
        let button = $('button.btnAddNewSign');
        let email = $('#novoDestinatario').val();

        // Valida formatação do campo EMAIL
        if (!validarEmail(email)) {
            alert('Por favor, insira um email válido.');
            return false;
        } else if (validarDuplicidade(email)) {
            alert('Email já se encontra cadastrado.');
            return false;
        }

        // Desabilita botao e inicia efeito
        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.post('addDestinatario', { email, idFileOpen }, response => {
            if (response.status == true) {
                adicionarLabelDestinatario({ destinatario: email, id: idFileOpen, status: '0' });
            } else {
                alert(response.message);
            }

            button.removeAttr('disabled').html('Adicionar');
        }, 'JSON').catch(e => {
            alert('Não foi possível adicionar o destinatário. Contate o administrador do sistema.');
            button.removeAttr('disabled').html('Adicionar');
        });
    }

    function validarDuplicidade(signature) {
        var destinatarios = [];
        $("#destinatariosList .destinatario-label").each(function() {
            var textoCompleto = $(this).text();
            let email = textoCompleto.replace(/[\s]+/g, ' ').trim();
            email = email.split(" ")[0];
            destinatarios.push(email);
        });

        return destinatarios.includes(signature);
    }

    function validarEmail(email) {
        var regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        return regex.test(email);
    }
</script>