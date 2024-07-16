$(document).ready(function() {

  // Configuração do CKEditor
  CKEDITOR.replace('content', {
    
      toolbar: [{
          name: 'clipboard',
          items: ['Undo', 'Redo']
        },
        {
          name: 'styles',
          items: ['Format', 'Font', 'FontSize']
        },
        {
          name: 'basicstyles',
          items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat']
        },
        {
          name: 'paragraph',
          items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
        },
        {
          name: 'document',
          items: ['Source']
        }
      ],
  });

  // Evento para adicionar novas linhas de links
  $('#linksContainer').on('click', '.btn-add', function() {
    if ($('#linksContainer .row').length >= 5) {
      $('#linkAlert').show();
      return;
    }
    var newRow = $('#firstLinkRow').clone();
    newRow.find('input, select').val('');

    newRow.find('.btn-add')
          .removeClass('btn-primary btn-add')
          .addClass('btn-danger btn-remove')
          .text('-');
    $('#linksContainer').append(newRow); // Apende ao container
  });

  // Evento para remover linhas de links
  $('#linksContainer').on('click', '.btn-remove', function() {
    $(this).closest('.row').remove();
    if ($('#linksContainer .row').length < 5) {
      $('#linkAlert').hide();
    }
  });

  // Envio do formulário
  $('#formAddAutoajuda').on('submit', function(e) {
      e.preventDefault();
      $submitButton.prop('disabled', true).text('Carregando...');

      // validação
      let isValid = true;
      if (!$('#title').val().trim()) {
          $('#titleAlert').show().text('O título é obrigatório.');
          isValid = false;
      }
      if (!$('#resource').val().trim()) {
          $('#resourceAlert').show().text('O recurso é obrigatório.');
          isValid = false;
      }
      if (!CKEDITOR.instances.content.getData().trim()) {
          $('#contentAlert').show().text('A descrição é obrigatória.');
          isValid = false;
      }


      if (!isValid) {
        $submitButton.prop('disabled', false).text('Salvar');
        return;
      }

      var links = [];
      $('#linksContainer .row').each(function() {
        const description = $(this).find('[name="tituloDoLink"]').val().trim();
        const url = $(this).find('[name="url"]').val().trim(); 
        const type = $(this).find('[name="tipoDoLink"]').val().trim();
        const target = $(this).find('[name="destino"]').val().trim();

        // Verifica se algum campo está preenchido e outros não
        if ((description || url || type || target) && !(description && url && type && target)) {
            $(this).find('.alertValidation').each(function() {
                const input = $(this).prev().val().trim();
                if (!input) {
                    $(this).show().text('Este campo é obrigatório.');
                }
            });
            isValid = false;
        } else if (description && url && type && target) {
            links.push({ description, url, type, target }); // Adiciona à lista se todos forem válidos
        }
      });

    if (!isValid) {
      $submitButton.prop('disabled', false).text('Salvar');
      return; 
    }
  
      var formData = {
          title: $('#title').val(),
          resource: $('#resource').val(),
          content: CKEDITOR.instances.content.getData()
      };
  
      if (links.length > 0) {
          formData.links = links;
      }

      // Condição para ver se vai salvar ou editar o formulario
      if(abrirDetalhesChamado){
        salvarAutoajuda(e);
      }else{
     

      // Chamada AJAX para enviar os dados
      $.ajax({
        url: `${urlApiAutoajuda}/shownet/autohelper/create-topic`,
        type: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(formData),
        headers: {
          'x-access-token': 'd0763b6e9ada39138f3a23be3e799c00'
        },
        success: function(response) {
          Swal.fire({
            title: 'Sucesso!',
            text: 'Dados enviados com sucesso!',
            icon: 'success',
            confirmButtonText: 'Ok'
          });
          atualizarGrid(gridOptions)
          CKEDITOR.instances.content.setData('');
          resetLinkRows();
          $('#addAutoajuda').modal('hide');
        },
        error: function(xhr, status, error) {
          Swal.fire({
            title: 'Erro!',
            text: 'Falha ao enviar dados: ' + error,
            icon: 'error',
            confirmButtonText: 'Fechar'
          });
        },
        complete: function() {
          $submitButton.prop('disabled', false).text('Salvar');
          abrirDetalhesChamado = false
        }
      });  
    }
  });

// Remove as validações
$('#title, #resource').on('input', function() {
    $(this).next('.alertValidation').hide();
});

CKEDITOR.instances.content.on('change', function() {
    $('#contentAlert').hide();
});

  // Evento para abrir o modal
  $('.btn-cadastrar').click(function() {
      $('#formAddAutoajuda')[0].reset();
      CKEDITOR.instances.content.setData('');
      $('#titleAutoAjuda').text(`Cadastrar Autoajuda`);
      resetLinkRows();
      abrirDetalhesChamado = false; 
      $('#addAutoajuda').modal('show'); 
  });
});

// Evento para esconder validação dos links
$('#linksContainer').on('input change', 'input, select', function() {
  $(this).closest('.row').find('.alertValidation').hide();
});



// função para ativar o tooltip
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
