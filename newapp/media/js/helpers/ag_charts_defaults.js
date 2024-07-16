const AG_GRID_DEFAULT_OVERLAYS =  {
    noData: {
        renderer: () => {
            return `
                <div
                    style="
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        width: 100%;
                        height: 100%;
                    "
                >
                    <div
                        style="
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            padding: 6px;
                            word-wrap: normal;
                            color: #343434;
                            font-weight: 500;
                            font-size: 8pt;
                            border-radius: 1rem;
                            border: none;
                            background-color: #ecebef;
                            text-align: center;
                            max-width: 200px;
                            height: auto;
                        "
                    >
                        <span>Nenhum dado encontrado para os parâmetros informados</span>
                    </div>
                </div>
            `;
        }
    }
}


const AG_GRID_DEFAULT_OVERLAYS_FIRMWARE =  {
    noData: {
        renderer: () => {
            return `
                <div
                    style="
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        width: 100%;
                        height: 100%;
                    "
                >
                    <div
                        style="
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            padding: 6px;
                            word-wrap: normal;
                            color: #343434;
                            font-weight: 500;
                            font-size: 8pt;
                            border-radius: 1rem;
                            border: none;
                            background-color: #ecebef;
                            text-align: center;
                            max-width: 200px;
                            height: auto;
                        "
                    >
                        <span>Nenhum dado encontrado para o gráfico.</span>
                    </div>
                </div>
            `;
        }
    }
}
