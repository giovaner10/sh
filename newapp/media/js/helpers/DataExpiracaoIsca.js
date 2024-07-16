class DataExpiracaoIsca {

    #idFormulario = "formAlterarDataExpiracao";
    #idModal = '#modalAlterarDataExpiracao';
    
    constructor (dataTable, colunaDataExpiracao, checkIscas, btnTriggerModal, iscas){
        this.dataTable = dataTable;
        this.colunaDataExpiracao = colunaDataExpiracao;
        this.checkIscas = checkIscas;
        this.btnTriggerModal = btnTriggerModal;
        this.iscas = iscas;
    }
    
    getForm(){
        return $("#"+this.#idFormulario);
    }
    getButtonTriggerModal(){
        return $("#"+this.btnTriggerModal);
    }

    
    exibirModal(){
        let selecionados = this.getIdsIscasSelecionadasDatatable();

        if(selecionados.length > 0){
            $(this.#idModal).modal('show');
        }
        else{
            alert("Selecione pelo menos uma isca.");
        }
    }

    #getDataAtual(){
        const date = new Date();
        const mes = date.getMonth()+1 < 10 ? "0"+(date.getMonth()+1) : date.getMonth()+1;
        const dia = date.getDate() < 10 ? "0"+(date.getDate()) : date.getDate();
        const today = `${date.getFullYear()}-${mes}-${dia}`;
        return today;
    }
    /**
     * Carega o html do modal para alteração da data de expiração
     * @param {String} idDiv Id da div onde será carregado o modal
     */
    carregarModalDataExpiracao(idDiv){
        const dataAtual = this.#getDataAtual();
        $("#"+idDiv).html(`
            <div class="modal fade" id="modalAlterarDataExpiracao" tabindex="-1" role="dialog" aria-labelledby="alterarDataExpiracaoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form id="formAlterarDataExpiracao">
                            <div class="modal-header">
                                <h4 class="modal-title" id="alterarDataExpiracaoModalLabel">Data de Expiração</h4>
                            </div>
                            <div class="modal-body">
                                    
                                <!-- Dados Isca -->
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
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success" id="btnAlterarDataExpiracao">Salvar</button>
                            </div>
                        </form>
                
                    </div>
                </div>
            </div>
        `);        
    }

    /**
     * Retorna os dados do formulário
     * @param {String} idFormulario 
     */
     getDadosFormulario(form){
        let dadosDataHora = form.serializeArray();
        let iscas = this.getIdsIscasSelecionadasDatatable();
        let dadosForm = {};

        // Adiciona dados de data e hora
        dadosDataHora.forEach(dado => {
            dadosForm[dado.name] = dado.value;
        });
        // Adiciona iscas
        dadosForm['iscas'] = iscas;

        return dadosForm;
    }    

    /**
     * Retorna array com ids das iscas selecionadas no modal
     * @param {Object} dataTable 
     * @param {String} nameCheckbox 
     * @returns {Array}
     */
    getIdsIscasSelecionadasDatatable(){
        return this.iscas;
    }

    /**
     * Valida dados do formulário
     * @param {Object} data 
     */
    validarDados(data){
        const { dataExpiracao, horaExpiracao, iscas } = data;

        if(dataExpiracao == null || dataExpiracao == undefined || dataExpiracao == ""){
            alert('Selecione uma data de expiração!');
            return false;
        }
        else if(horaExpiracao == null || horaExpiracao == undefined || horaExpiracao == ""){
            alert('Selecione uma hora de expiração!');
            return false;
        }
        else if(dataExpiracao && horaExpiracao){
            let now = new Date();
            let dataHora = new Date(dataExpiracao + " " + horaExpiracao);
            if(dataHora == "Invalid Date"){
                alert('Data e hora de expiração inválida!');
                return false;
            }
            if(dataHora < now){
                alert('A data e hora de expiração deve ser maior do que a data e hora atual!');
                return false;
            }
        }
        else if(iscas == null || iscas == undefined || !is_array(iscas) || iscas.length == 0){
            alert('Selecione as iscas!');
            return false;
        }
        else{
            return true;
        }

        return true;
    }

    /**
     * Função que faz requisição para o banco de dados e altera a data de expiração das iscas
     * @param {Object} data
     * @returns {Promise} 
     */
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
            }else{
                reject({
                    status:0,
                })
            }
        })
    }
    

    /**
     * Atualiza a coluna com data de expiração na tabela
     * @param {*} dataTable 
     * @param {*} data 
     * @param {*} classCheckboxIscas 
     * @param {*} colunaDataExpiracao 
     */
    atualizarColunaDataExpiracao(dataTable, data, classCheckboxIscas, colunaDataExpiracao){
        try {
            const { dataExpiracao, horaExpiracao, iscas } = data;
            // Desmarca todos os checkboxes do datatable
            $('.'+classCheckboxIscas).prop('checked', false);
        
            // percore o array com id das iscas e altera a data de expiração na tabela
            iscas.forEach(id => {
                let rowData = dataTable.row('#'+id).data();
                
                if(rowData){
                    rowData[colunaDataExpiracao] = formatDateTime(`${dataExpiracao} ${horaExpiracao}`);
            
                    dataTable.row('#'+id).data(rowData).draw(false);
                }
            })
        } catch (error) {
            console.error(error);
        }
        
    }

    /**
     * Faz requisição para atualizar data de expiração do datatable
     * @param {String} url 
     */
    submit(url){
        let button = $("#btnAlterarDataExpiracao");
        let form = this.getForm();
        let data = this.getDadosFormulario(form, this.dataTable, this.checkIscas);

        button.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando');
    
        this.alterarDataExpiracaoIscas(data, url)
            .then(callback => {
                button.attr('disabled', false).html('Salvar');
                if(callback.status){
                    alert(callback.message);
                    this.atualizarColunaDataExpiracao( this.dataTable, data, this.checkIscas, this.colunaDataExpiracao);
                    $("#modalAlterarDataExpiracao").modal('hide');
                }else{
                    alert(callback.message);
                }
            })
            .catch(error => {
                button.attr('disabled', false).html('Salvar');
                console.error(error);
                alert('Erro ao alterar data de expiração! Tente novamente.');
    
            });
    }
}