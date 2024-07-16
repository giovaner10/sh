let gridOptions;
let data;
let abrirDetalhesChamado = false;
let idTopic;
const $submitButton = $('#btnSalvarAutoajuda');

async function initGrid(gridOptionsParam) {
  gridOptions = gridOptionsParam;

  new agGrid.Grid(document.getElementById('table'), gridOptions);

  try {
    let dadosAutoajuda = await informacaoAutoajuda();
    gridOptions.api.setRowData(dadosAutoajuda);
  } catch (error) {
    console.error('Erro ao carregar os dados:', error);
  }
}

function updatePaginationPageSize(gridOptionsParam) {
  gridOptionsParam.paginationPageSize = parseInt($('#select-quantidade-por-pagina-dados').val());
  gridOptionsParam.api.paginationGoToPage(0);
  atualizarGrid(gridOptionsParam);
}

// função para resetar as linhas 
function resetLinkRows() {
  $('#linksContainer').find('.row:not(:first)').remove();
  var firstRow = $('#firstLinkRow');
  firstRow.find('input, select').val('');
  firstRow.find('.btn-remove').addClass('btn-primary btn-add')
           .removeClass('btn-danger btn-remove')
           .text('+');
}

async function informacaoAutoajuda() {
  try {
    const url = `${urlApiAutoajuda}/shownet/autohelper/get-all`;
    const result = await axios.get(url, { headers: { "x-access-token": "d0763b6e9ada39138f3a23be3e799c00" } });
    autoajudaData = result.data.dados;
    return result.data.dados;
  } catch (error) {
    console.error('Erro ao obter dados:', error);
    return [];
  }
}

function removeHtmlTags(text) {
  const regex = /<\/?[^>]+(>|$)/g;
  return text.replace(regex, '');
}

async function atualizarGrid(gridOptionsParam) {
  try {
      let dadosAutoajuda = await informacaoAutoajuda();
      gridOptionsParam.api.setRowData(dadosAutoajuda);
  } catch (error) {
      console.error('Erro ao atualizar a grid:', error);
  }
}

async function deletarTopico(id){
  try {
    const url = `${urlApiAutoajuda}/shownet/autohelper/delete/${id}`
    const result = await axios.delete(url, { headers: { "x-access-token": "d0763b6e9ada39138f3a23be3e799c00" } });
    atualizarGrid(gridOptions);
    return result.data.dados;
  } catch (error) {
    console.error('Erro ao obter os dados:', error);
  }
}

async function updateStatus(id, status){
  let statusBody = status === "Ativar" ? true : false;
  try {
    const url = `${urlApiAutoajuda}/shownet/autohelper/update/${id}`
    const result = await axios.put(url, {isActive: statusBody}, { headers: { "x-access-token": "d0763b6e9ada39138f3a23be3e799c00" } });
    atualizarGrid(gridOptions);
    return result.data.dados;
  } catch (error) {
    console.error('Erro ao obter os dados:', error);
  }
}

async function abrirModalDeConfirmaçao(deletar, id, status){
  if(deletar){
    await Swal.fire({
      title: 'Você deseja mesmo excluir esse item?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Deletar',
      cancelButtonText: 'Cancelar',
      showLoaderOnConfirm: true, 
      preConfirm: async () => { 
        await deletarTopico(id);
      }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire('Deletado!', '', 'success');
      }
    });
  } else {
    await Swal.fire({
      title: `Você deseja mesmo ${status} o status desse item?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: status,
      cancelButtonText: 'Cancelar',
      showLoaderOnConfirm: true, 
      preConfirm: async () => { 
        await updateStatus(id, status);
      }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire('Alterado!', '', 'success');
      }
    })
  }
}

document.addEventListener('DOMContentLoaded', async function () {
  await initGrid({
    columnDefs: [
      {
        headerName: 'Id',
        field: 'id',
        suppressSizeToFit: true,
        sort: 'desc'
      },
      {
        headerName: 'Titulo',
        field: 'title',
        flex: 3 
      },
      {
        headerName: 'Slug',
        field: 'resource',
        flex: 3 
      },
      {
        headerName: 'Positivos',
        field: 'positiveVotes',
        suppressSizeToFit: false,
        width: 70
      },
      {
        headerName: 'Negativos',
        field: 'negativeVotes',
        suppressSizeToFit: false,
        width: 70
      },
      {
        headerName: 'Eficiência',
        field: 'efficiencyAverage',
        suppressSizeToFit: true,
        width: 70
      },
      {
        headerName: 'Status',
        field: 'isActive',
        flex: 2,
        valueFormatter: function(params) {
          const isStatus = params.value === false ? "Inativo" : "Ativo"
          return isStatus;
      }
      },
      {
        headerName: 'Ações',
        width: 80,
        pinned: 'right',
        cellClass: "actions-button-cell",
        cellRenderer: function (options) {
          let id = options.data.id;
          let dropdownId = "dropdown-menu" + id;
          let buttonId = "dropdownMenuButton_" + id;
          let status = options.data.isActive === true ? "Inativar" : "Ativar"
          return `
            <div class="dropdown dropdown-table" style="position: relative;">
              <button class="btn btn-dropdown dropdown-toggle" type="button" id="${buttonId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:-6px; width:35px">
                <img src="${BaseURL + 'media/img/new_icons/icon_acoes.svg'}" alt="Ações">
              </button>
              <div class="dropdown-menu dropdown-menu-right shadow dropdown-menu-acoes" id="${dropdownId}" style="position:absolute;" aria-labelledby="${buttonId}">
              <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                 <a href="javascript:abrirModalDeConfirmaçao(false, '${id}', '${status}')" style="cursor: pointer; color: black;">${status}</a>
               </div>
                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;">
                  <a href="javascript:abrirDetalhes('${id}')" style="cursor: pointer; color: black;">Editar</a>
                </div>
                <div class="dropdown-item dropdown-item-acoes" style="cursor: pointer;" >
                  <a href="javascript:abrirModalDeConfirmaçao(true, '${id}')" style="cursor: pointer; color: black;">Excluir</a>
                </div>
              </div>
            </div>`;
        },
      },
    ],
    defaultColDef: {
      flex: 1,
      editable: false,
      sortable: true,
      filter: false,
      resizable: true,
      suppressMenu: true
    },
    localeText: {
      noRowsToShow: 'Nenhum registro para mostrar',
      page: 'Página',
      of: 'de',
      to: 'a',
      next: 'Próximo',
      last: 'Último',
      first: 'Primeiro',
      previous: 'Anterior',
    },
    pagination: true,
    paginationPageSize: parseInt($('#select-quantidade-por-pagina-dados').val()),
    domLayout: 'normal',
    rowModelType: 'clientSide',
    serverSideStoreType: 'none',
    overlayLoadingTemplate: '<span class="ag-overlay-loading-center"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: #06a9f6;"></i> <b> Carregando...</b></span>'
  });
});

$('#select-quantidade-por-pagina-dados').on('change', function() {
  updatePaginationPageSize(gridOptions);
});

// Função para abrir o modal com os dados preenchidos
function abrirDetalhes(id) {
  resetLinkRows();
  abrirDetalhesChamado = true;

  data = autoajudaData.find(item => item.id == id);
  if (!data) {
    console.error('Item não encontrado');
    return;
  }


  // Preencher os campos do formulário com os dados obtidos
  document.getElementById('id').value = data.id;
  document.getElementById('title').value = data.title;
  document.getElementById('resource').value = data.resource;

  $('#titleAutoAjuda').text(`Editar Autoajuda ${data.id}`);

  // Preencher o CKEditor com os dados
  if (CKEDITOR.instances.content) {
    CKEDITOR.instances.content.setData(data.content);
  } else {
    CKEDITOR.replace('content');
    CKEDITOR.instances.content.setData(data.content);
  }

  // Limpar links anteriores
  const linksContainer = document.getElementById('linksContainer');
  while (linksContainer.firstChild && linksContainer.firstChild.id !== 'firstLinkRow') {
    linksContainer.removeChild(linksContainer.firstChild);
  }

  // Preencher os links do formulário
  if (data.links && data.links.length > 0) {
    data.links.forEach((link, index) => {
      if (index > 0) {
        adicionarLink();
      }
      document.querySelectorAll('[name="tituloDoLink"]')[index].value = link.description;
      document.querySelectorAll('[name="url"]')[index].value = link.url;
      document.querySelectorAll('[name="tipoDoLink"]')[index].value = link.type;
      document.querySelectorAll('[name="destino"]')[index].value = link.target;
    });
  }

  // Abrir o modal
  $('#addAutoajuda').modal('show');
}

// Função para adicionar um novo link ao formulário
function adicionarLink() {
  const linksContainer = document.getElementById('linksContainer');
  const linkRow = document.getElementById('firstLinkRow').cloneNode(true);
  linkRow.id = '';
  linkRow.querySelectorAll('input').forEach(input => input.value = '');
  linkRow.querySelectorAll('select').forEach(select => select.value = '');
  linksContainer.appendChild(linkRow);
}

// Função para salvar os dados do formulário
async function salvarAutoajuda(event) {
  event.preventDefault();

  const id = document.getElementById('id').value;
  const title = document.getElementById('title').value;
  const resource = document.getElementById('resource').value;
  const content = CKEDITOR.instances.content.getData();
  const links = [];

  document.querySelectorAll('#linksContainer .row').forEach(row => {
    const tituloDoLink = row.querySelector('[name="tituloDoLink"]').value;
    const url = row.querySelector('[name="url"]').value;
    const tipoDoLink = row.querySelector('[name="tipoDoLink"]').value;
    const destino = row.querySelector('[name="destino"]').value;
    if (tituloDoLink && url) {
      links.push({ description: tituloDoLink, url: url, type: tipoDoLink, target: destino });
    }
  });
  

  const autoajudaData = { title, resource, content, isActive: data.isActive };

  if(links.length > 0) {
    autoajudaData.links = links
  } 

  try {
    const url = `${urlApiAutoajuda}/shownet/autohelper/update/${Number(id)}`
    const result = await axios.put(url, autoajudaData, { headers: { "x-access-token": "d0763b6e9ada39138f3a23be3e799c00" } });


    Swal.fire({
      title: 'Sucesso!',
      text: 'Dados enviados com sucesso!',
      icon: 'success',
      confirmButtonText: 'Ok'
    });
    atualizarGrid(gridOptions);
    resetLinkRows();
    CKEDITOR.instances.content.setData('');
    $('#addAutoajuda').modal('hide');
  } catch (error) {
    console.error('Erro ao salvar os dados:', error);
    Swal.fire({
      title: 'Erro!',
      text: 'Falha ao enviar dados: ' + error,
      icon: 'error',
      confirmButtonText: 'Fechar'
    });
  } finally {
    $submitButton.prop('disabled', false).text('Salvar');
    abrirDetalhesChamado = false;
  }
}

