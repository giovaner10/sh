function validarCPF(cpf)
{
	if (typeof cpf !== "string")
        return false
        
	cpf = cpf.replace(/[\s.-]*/igm, '')
	
	if (
		!cpf ||
		cpf.length != 11 ||
		cpf == "00000000000" ||
		cpf == "11111111111" ||
		cpf == "22222222222" ||
		cpf == "33333333333" ||
		cpf == "44444444444" ||
		cpf == "55555555555" ||
		cpf == "66666666666" ||
		cpf == "77777777777" ||
		cpf == "88888888888" ||
		cpf == "99999999999" 
	) {
		return false
	}

	var Soma;
	var Resto;
	Soma = 0;
	
	if (cpf == "00000000000" || cpf == "") return false;
	for (i = 1; i <= 9; i++)
        Soma = Soma + parseInt(cpf.substring(i - 1, i)) * (11 - i);

	Resto = (Soma * 10) % 11;

	if ((Resto == 10) || (Resto == 11))
        Resto = 0;

	if (Resto != parseInt(cpf.substring(9, 10)) )
        return false;

	Soma = 0;

	for (i = 1; i <= 10; i++)
        Soma = Soma + parseInt(cpf.substring(i-1, i)) * (12 - i);

	Resto = (Soma * 10) % 11;

	if ((Resto == 10) || (Resto == 11))
        Resto = 0;

	if (Resto != parseInt(cpf.substring(10, 11) ) )
        return false;
	
	return true;
}

function buscarEnderecoViaCEP(campo, complementoCampos = "")
{
	// Nova variável "cep" somente com dígitos.
    var cep = $(campo).val().replace(/\D/g, '');

    // Verifica se campo cep possui valor informado.
    if (cep != "")
	{
        // Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        // Valida o formato do CEP.
        if(validacep.test(cep))
		{
            // Preenche os campos com "..." enquanto consulta webservice.
            $(`#rua${complementoCampos}`).val("...");
            $(`#bairro${complementoCampos}`).val("...");
            $(`#cidade${complementoCampos}`).val("...");
            $(`#uf${complementoCampos}`).val("...");
            $(`#ibge${complementoCampos}`).val("...");

            // Consulta o webservice viacep.com.br/
            $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados)
			{
                if (!("erro" in dados))
				{
                    //Atualiza os campos com os valores da consulta.
                    $(`#rua${complementoCampos}`).val(dados.logradouro);
                    $(`#bairro${complementoCampos}`).val(dados.bairro);
                    $(`#cidade${complementoCampos}`).val(dados.localidade);
                    $(`#uf${complementoCampos}`).val(dados.uf);
                    $(`#ibge${complementoCampos}`).val(dados.ibge);
                }
                else
				{
                    // CEP pesquisado não foi encontrado.
                    limparFormularioBuscaViaCep(complementoCampos);
                    alert("CEP não encontrado.");
                }
            });
        }
        else
		{
            // CEP é inválido.
            limparFormularioBuscaViaCep(complementoCampos);
            alert("Formato de CEP inválido.");
        }
    }
    else
	{
        // CEP sem valor, limpa formulário.
        limparFormularioBuscaViaCep(complementoCampos);
    }
}

function limparFormularioBuscaViaCep(complementoCampos)
{
	// Limpa valores dos campos
	$(`#rua${complementoCampos}`).val("");
	$(`#bairro${complementoCampos}`).val("");
	$(`#cidade${complementoCampos}`).val("");
	$(`#uf${complementoCampos}`).val("");
	$(`#ibge${complementoCampos}`).val("");
}

// Abre modal de confirmacao de exclusao
function abrirModalConfirmarExclusaoBootstrap(identificador, idRegistro, texto, titulo)
{
    if ($(`#modalConfirmarExcluir${identificador}`).length)
        $(`#modalConfirmarExcluir${identificador}`).remove();

    if (!titulo)
        titulo = lang.modal_confirmacao_titulo;
        
    $('body').append(`
        <div id="modalConfirmarExcluir${identificador}" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="modalLabel" aria-hidden="true">
            
            <input type="hidden" id="modalConfirmar${identificador}Id">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
        
                    <div class="modal-header header-layout">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title" id="modalLabel">${titulo}</h3>
                    </div>
        
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class='row'>
                                <div class="col-md-12 input-container form-group">
                                    ${texto}
                                </div>
                            </div>
                            <hr style="margin: 0;">
                        </div>
                    </div>
        
                    <div class="modal-footer">
                        <div class="footer-group" style="flex-direction: row-reverse">
                            <button type="button" onclick="excluir${identificador}()" id="btnExcluir${identificador}" class="btn btn-danger">${lang.excluir}</button>
                        </div>
                    </div>
        
                </div>
            </div>
        </div>
    `);

    $(`#modalConfirmarExcluir${identificador}`).modal();
    $(`#modalConfirmar${identificador}Id`).val(idRegistro);
}

function fecharModalConfirmarExclusaoBootstrap(identificador)
{
    $(`#modalConfirmarExcluir${identificador}`).modal('hide');
}

function getModalConfirmarExclusaoBootstrapIdRegistro(identificador)
{
    return $(`#modalConfirmar${identificador}Id`).val();
}