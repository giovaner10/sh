function carregarBandeirasDdi(inputDdi, porcentagemWidthComponente) {
    if(!inputDdi)
        return
    
    if(porcentagemWidthComponente)
        $(".iti").css("width", porcentagemWidthComponente)

    return window.intlTelInput(inputDdi, {
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
        initialCountry: "br",
        separateDialCode: true,
        autoInsertDialCode: true,
        autoPlaceholder: "aggressive",
        customPlaceholder: function(placeHolderPais, dadosPaisSelecionado) {
            if(dadosPaisSelecionado?.iso2 === "br")
                return "(00) 00000-0000"

            return placeHolderPais
        }
    })
}

function adptarMascaraOnChangePais(idInputPrincipal, inputDdiBandeira){
    if(!idInputPrincipal)
        return

    const inputPrincipal = $(idInputPrincipal);

    inputPrincipal.on("countrychange", function() {
        if(inputDdiBandeira.s.dialCode.toString() === "55")
            inputPrincipal.mask("(00) 00000-0000")
        else
            inputPrincipal.unmask()
    })
}

function tratarNumeroComMascara(numero){
    if(!numero)
        return

    return numero.replace(/\D/g, "")
}