const constDate = new Date();

function renderCalendar(){
    constDate.setDate(1);
    let monthDays = $(".days");
    
    
    // last day of month
    const lastDay = new Date(
        constDate.getFullYear(),
        constDate.getMonth() + 1,
        0
      ).getDate();
    
    const prevLastDay = new Date(constDate.getFullYear(),constDate.getMonth(), 0).getDate();
    
    const firstDayIndex = constDate.getDay();
    
    const lastDayIndex = new Date(constDate.getFullYear(), constDate.getMonth() + 1, 0).getDay();
    
    const nextDays = 7 - lastDayIndex - 1;

    // add month title
    $("#title_month").html(`${months[constDate.getMonth()]} ${constDate.getFullYear()}`);
    
    let days = "";
    
    for(let x = firstDayIndex; x > 0; x--){
        days += `<div class="prev-date">${prevLastDay - x + 1}</div>`;
    }
    
    for(let i = 1; i <= lastDay; i++){
        // monta string de data do agendamento
        const stringYear = constDate.getFullYear();
        const stringMonth = parseInt(constDate.getMonth() + 1) < 10 ? '0' + (constDate.getMonth() + 1) : (constDate.getMonth() + 1);
        const stringDay = (i < 10) ? '0'+i : i;
        const dataAgendamentos = stringYear +'-'+stringMonth+'-'+stringDay
        
        // dia atual
        if(i === new Date().getDate() && constDate.getMonth() === new Date().getMonth()){
            // se possui agendamento no dia
            if(num_agendamentos_por_dia[dataAgendamentos] != undefined){
                // Condição que garante que o dia do calendário vai ser renderizado mesmo que 
                // decremente o valor de agendamentos
                if(num_agendamentos_por_dia[dataAgendamentos] <= 0){
                    days += `<div onclick="get_agendamentos_por_dia('${dataAgendamentos}')" title="Não possui agendamento">${i}</div>`;
                }
                // caso não ultrapasse 15 agendamentos no dia
                else if(num_agendamentos_por_dia[dataAgendamentos] >= 0){
                    days += `<div class="today primary_color" onclick="get_agendamentos_por_dia('${dataAgendamentos}')" title="${num_agendamentos_por_dia[dataAgendamentos]} agendamento(s) pendente(s)">
                                <b>${i}</b>
                            </div>`;
                }
                
            }
            // caso não possua agendamento no dia
            else{
                days += `<div class="today" onclick="get_agendamentos_por_dia('${dataAgendamentos}')" title="Não possui agendamento">${i}</div>`;    
            }
        }
        // outros dias do mês
        else{
            // se possui agendamento no dia
            if(num_agendamentos_por_dia[dataAgendamentos] != undefined){
                // Condição que garante que o dia do calendário vai ser renderizado mesmo que 
                // decremente o valor de agendamentos
                if(num_agendamentos_por_dia[dataAgendamentos] <= 0){
                    days += `<div onclick="get_agendamentos_por_dia('${dataAgendamentos}')" title="Não possui agendamento">${i}</div>`;
                }
                // caso não ultrapasse 15 agendamentos no dia
                else if(num_agendamentos_por_dia[dataAgendamentos] > 0){
                    days += `<div class="primary_color" onclick="get_agendamentos_por_dia('${dataAgendamentos}')" title="${num_agendamentos_por_dia[dataAgendamentos]} agendamento(s) pendente(s)">
                                <b>${i}</b>
                            </div>`; 
                }
            }
            // caso não possua agendamento no dia
            else{
                days += `<div onclick="get_agendamentos_por_dia('${dataAgendamentos}')" title="Não possui agendamento">${i}</div>`;    
            }
        }
    }
    
    for(let j = 1; j <= nextDays; j++){
        days += `<div class="next-date">${j}</div>`;    
    }
    monthDays.html(days);
}

$(".prev").click(()=>{
    // diminui 1 mês
    constDate.setMonth(constDate.getMonth() - 1);

    // pega o intervalo do primeiro e ultimo dia do Mes
    const year = constDate.getFullYear();
    const month = parseInt(constDate.getMonth() + 1) < 10 ? '0' + (constDate.getMonth() + 1) : (constDate.getMonth() + 1);
    const firstDay = '01';
    // last day of month
    const lastDay = new Date(
        constDate.getFullYear(),
        constDate.getMonth() + 1,
        0
      ).getDate();
    const initDate = year +'-'+month+'-'+firstDay
    const endDate = year +'-'+month+'-'+lastDay
    // chama função para retornar agendamentos do mês no calendário
    ajax_get_agendamentos(initDate, endDate);

    renderCalendar();
});
$(".next").click(()=>{
    // aumenta 1 mês
    constDate.setMonth(constDate.getMonth() + 1);

    // pega o intervalo do primeiro e ultimo dia do Mes
    const year = constDate.getFullYear();
    const month = parseInt(constDate.getMonth() + 1) < 10 ? '0' + (constDate.getMonth() + 1) : (constDate.getMonth() + 1);
    const firstDay = '01';
    // last day of month
    const lastDay = new Date(
        constDate.getFullYear(),
        constDate.getMonth() + 1,
        0
      ).getDate();
    const initDate = year +'-'+month+'-'+firstDay
    const endDate = year +'-'+month+'-'+lastDay
    // chama função para retornar agendamentos do mês no calendário
    ajax_get_agendamentos(initDate, endDate);

    renderCalendar();
});

renderCalendar();