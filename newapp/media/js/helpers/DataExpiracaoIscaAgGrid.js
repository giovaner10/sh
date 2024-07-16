class DataExpiracaoIsca {
    #idFormulario = "formAlterarDataExpiracao";
    #idModal = '#modalAlterarDataExpiracao';

    constructor (agGridOptions, colunaDataExpiracao, checkIscas, btnTriggerModal, iscas){
        this.agGridOptions = agGridOptions;
        this.colunaDataExpiracao = colunaDataExpiracao;
        this.checkIscas = checkIscas;
        this.btnTriggerModal = btnTriggerModal;
        this.iscas = iscas;
    }

    getForm(){
        return $("#" + this.#idFormulario);
    }
    
    getButtonTriggerModal(){
        return $("#" + this.btnTriggerModal);
    }

    exibirModal(){
        let selecionados = this.getIdsIscasSelecionadasAgGrid();

        if(selecionados.length > 0){
            $(this.#idModal).modal('show');
        } else {
            showAlert("warning","Selecione pelo menos uma isca.");
        }
    }

    #getDataAtual(){
        const date = new Date();
        const mes = date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1;
        const dia = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();
        const today = `${date.getFullYear()}-${mes}-${dia}`;
        return today;
    }

    carregarModalDataExpiracao(idDiv){
        const dataAtual = this.#getDataAtual();
        $("#" + idDiv).html(`
            <div class="modal fade" id="modalAlterarDataExpiracao" tabindex="-1" role="dialog" aria-labelledby="alterarDataExpiracaoModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form id="formAlterarDataExpiracao">
                            <div class="modal-header header-layout">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 class="modal-title" id="alterarDataExpiracaoModalLabel">Data de Expiração</h3>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="dataExpiracao">Data de Expiração</label>
                                        <input type="date" class="form-control" id="dataExpiracao" name="dataExpiracao" min-value="${dataAtual}" value="${dataAtual}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="horaExpiracao">Hora de Expiração</label>
                                        <input type="text" class="form-control time" id="horaExpiracao" name="horaExpiracao" value="23:59:59" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="btnAlterarDataExpiracao">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `);        
    }

    getDadosFormulario(){
        let dadosDataHora = this.getForm().serializeArray();
        let iscas = this.getIdsIscasSelecionadasAgGrid();
        let dadosForm = {};

        dadosDataHora.forEach(dado => {
            dadosForm[dado.name] = dado.value;
        });

        dadosForm['iscas'] = iscas;

        return dadosForm;
    }    

    getIdsIscasSelecionadasAgGrid(){
        let selectedNodes = this.agGridOptions.api.getSelectedNodes();
        return selectedNodes.map(node => node.data.id);
    }

    validarDados(data){
        const { dataExpiracao, horaExpiracao, iscas } = data;

        if(!dataExpiracao){
            showAlert("warning",'Selecione uma data de expiração!');
            return false;
        } else if(!horaExpiracao){
            showAlert("warning",'Selecione uma hora de expiração!');
            return false;
        } else {
            let now = new Date();
            let dataHora = new Date(`${dataExpiracao} ${horaExpiracao}`);
            if(dataHora == "Invalid Date"){
                showAlert("warning",'Data e hora de expiração inválida!');
                return false;
            }
            if(dataHora < now){
                showAlert("warning",'A data e hora de expiração deve ser maior do que a data e hora atual!');
                return false;
            }
        }

        if(!Array.isArray(iscas) || iscas.length == 0){
            showAlert("warning",'Selecione as iscas!');
            return false;
        }

        return true;
    }

    async alterarDataExpiracaoIscas(data, url){
        return new Promise((resolve, reject) => {
            if(this.validarDados(data)){
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function(callback){
                        resolve(callback);
                    },
                    error: function(error){
                        reject({
                            status: 0,
                            error,
                            message: 'Erro ao atualizar data de expiração!'
                        });
                    }
                });
            } else {
                reject({ status: 0 });
            }
        });
    }

    atualizarColunaDataExpiracao(data){
        try {
            const { dataExpiracao, horaExpiracao, iscas } = data;
            this.agGridOptions.api.forEachNode(node => {
                if(iscas.includes(node.data.id)){
                    node.setDataValue(this.colunaDataExpiracao, `${dataExpiracao} ${horaExpiracao}`);
                }
            });
        } catch (error) {
            console.error(error);
        }
    }

    submit(url){
        let button = $("#btnAlterarDataExpiracao");
        let data = this.getDadosFormulario();

        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando');
    
        this.alterarDataExpiracaoIscas(data, url)
            .then(callback => {
                button.attr('disabled', false).html('Salvar');
                if(callback.status){
                    showAlert("success",callback.message);
                    this.atualizarColunaDataExpiracao(data);
                    $(this.#idModal).modal('hide');
                } else {
                    showAlert("error",callback.message);
                }
            })
            .catch(error => {
                button.attr('disabled', false).html('Salvar');
                console.warn(error);
            });
    }
}


