
/**
 * Funcao que valida os campos de formulario
 * "id_form" eh o nome do id do formulario
 * "functionSubmitForm" eh o nome da funcao que sera executada caso todos os campos passem pela validacao, obs.: apenas o nome sem o parenteses
*/
function validar_campos_form(id_form, functionSubmitForm = null) {
  ignore: [],

  $("#" + id_form).validate({
    // Se tudo estiver ok, prosegue com os envio do formulario
    submitHandler: function () {
      if (!functionSubmitForm) return true;
      functionSubmitForm();
    },

    //Configura para reconhecer o select2
    highlight: function (element, errorClass, validClass) {
      var elem = $(element);
      if (elem.hasClass("select2-hidden-accessible")) {
        $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
      } else {
        elem.addClass(errorClass);
      }
    },
    unhighlight: function (element, errorClass, validClass) {
      var elem = $(element);
      if (elem.hasClass("select2-hidden-accessible")) {
        $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
      } else {
        elem.removeClass(errorClass);
      }
    },
    errorPlacement: function (error, element) {
      var elem = $(element);
      if (elem.hasClass("select2-hidden-accessible")) {
        element = $("#select2-" + elem.attr("id") + "-container").parent();
        error.insertAfter(element);
      } 
      else if (element.parent().hasClass('input-group')) {
        error.insertAfter(element.parent());
      }
      else {
        error.insertAfter(element);
      }
    }
  });

  $.validator.messages.required = lang.campo_obrigatorio;
}