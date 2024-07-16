import '../app';

export class AgGridMock {
    constructor(route){
        this.route = route;
    }

    async getServerSideDadosMock() {
        const dataUrl = this.route; 
        const ezMock = await EzMock.create(dataUrl, 'link');
    
        return {
            getRows: async (params) => {
                try {
                    const response = await ezMock.paginatedEndpointMock(params.request.startRow, params.request.endRow);
    
                    if (response.success) {
                        params.successCallback(response.rows, response.lastRow);
                    } else {
                        throw new Error('Dados não recebidos corretamente');
                    }
                } catch (error) {
                    console.error('Erro ao obter dados:', error);
                    params.failCallback();
                }
            }
        };
    }
}