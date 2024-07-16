$(document).ready( function () {
  var filterVal = '';
      $('input[type=search]').unbind(); 
      $('input[type=search]').on('keyup change', function() {
          var val = NeutralizeAccent(this.value);
          if(val !== filterVal) {
              filterVal = val;
              tabela = this.getAttribute('aria-controls');
              $('#' + tabela).DataTable().search(val).draw(); 
          }
      });
});

function NeutralizeAccent(data) {
  return !data
      ? ''
      : typeof data === 'string'
          ? data
          .replace(/\n/g, ' ')
          .replace(/[']/g, ' ')
          .replace(/[éÉěĚèêëÈÊËẽẼ]/g, 'e')
          .replace(/[šŠ]/g, 's')
          .replace(/[čČçÇ]/g, 'c')
          .replace(/[řŘ]/g, 'r')
          .replace(/[žŽ]/g, 'z')
          .replace(/[ýÝ]/g, 'y')
          .replace(/[áÁâàÂÀãÃäÄ]/g, 'a')
          .replace(/[íÍîïÎÏĩĨìÌ]/g, 'i')
          .replace(/[ťŤ]/g, 't')
          .replace(/[ďĎ]/g, 'd')
          .replace(/[ňŇñÑ]/g, 'n')
          .replace(/[óöôÓõÕÔòÒÖ]/g, 'o')
          .replace(/[úûÚůŮüÜũŨùÙÛ]/g, 'u')
          : data
}
