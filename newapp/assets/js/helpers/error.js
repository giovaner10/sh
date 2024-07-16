function showError(id, message) {
    $(`#${id}`).addClass('is-invalid');
    $(`#${id}-invalid`).html(message);
    $(`#${id}-invalid`).show();

  showAlert('error', message);
}

function hideError(ids) {
  ids.forEach(id => {
    $(`#${id}`).removeClass('is-invalid');
    $(`#${id}-invalid`).hide();
  });
 }