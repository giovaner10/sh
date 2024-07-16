
/**
    Função que retorna data formatada no padrão pt-br
    @param datetime
    @return string
*/
function formatDateTime(datetime){
    if(datetime && datetime != "0000-00-00 00:00:00"){
        let date = new Date(datetime);
        return `${date.toLocaleDateString('pt-br')} ${date.toLocaleTimeString('pt-br')}`;
    }else{
        return '';
    }
}
function formatDate(datetime){
    if(datetime && datetime != "0000-00-00"){
        let date = new Date(datetime);
        return `${date.toLocaleDateString('pt-br')}`;
    }else{
        return '';
    }
}
/**
 * Valida datas
 * @param {String} ini 
 * @param {String} fim 
 * @return {boolean}
 */
function validarDatas(ini, fim){

    let dataInicio = new Date(ini);
    let dataFim = new Date(fim);
    let today = new Date();

    if(ini == "" || fim == ""){
        alert("Infome a data de início e fim do período");
        return false
    }else{
        if(dataInicio > dataFim){
            alert("Informe a data de início anterior ou igual a data final.");
            return false;
        }
        else if(dataInicio > today || dataFim > today){
            alert("Não informe datas futuras");
            return false;
        }else{return true}
    }
}

/**
 * Valida a diferença entre duas datas
 * @param {String} dataInicio 
 * @param {String} dataFim 
 * @param {Interger} dias 
 * @return {boolean}
 */
function validarDiferençaDatas(dataInicio, dataFim, dias) {
    date1 = new Date(dataInicio)
    date2 = new Date(dataFim)
    // Descartando timezone e horário de verão
    var diferenca = Math.abs(date1 - date2); //diferença em milésimos e positivo
    var dia = 1000*60*60*24; // milésimos de segundo correspondente a um dia
    var total = Math.round(diferenca/dia); //valor total de dias arredondado 

    if(total > dias){
        return false;
    }else{
        return true;
    }
}

function returnStatusAtivoInativo(status){
    label = '';
    if(status == 1){
        label = '<span class="badge badge-success" style="background-color: green">Ativo</span>'
    }else{
        label = '<span class="badge badge-danger" style="">Inativo</span>';
    }
    return label;
}

/**
 * Retorna toggle button com status da isca
 * @param {Integer} isca 
 */
function returnStatus(status, id_isca){
    
    if(status == 1){
        return `<label class="switch statusIsca${id_isca}" id="statusIsca${id_isca}">
                    <input type="checkbox" checked onClick="ativarDesativarIsca(${status},${id_isca})">
                    <span class="slider"></span>
                </label>
                <br>
                <small><b>Ativa</b></small>`;
    }else{
        return `<label class="switch statusIsca${id_isca}" id="statusIsca${id_isca}">
                    <input type="checkbox" onClick="ativarDesativarIsca(${status},${id_isca})">
                    <span class="slider"></span>    
                </label>
                <br>
                <small><b>Inativa</b></small>`;
    }
}




