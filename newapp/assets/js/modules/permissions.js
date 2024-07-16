$(document).ready(function() {

    $('input[type=checkbox][name=permissoes[]]').each(function() {
        $(this).parent().addClass('display');
    });

    var basicPlan = function(self) {
        $('#mo').addClass('display');
        $('#ta').addClass('display');
        switch(self.val()) {
            case 'visualizar_bloqueio':
                self.parent().removeClass('display');
                break;
            case 'relatorios_excesso_velocidade':
                self.parent().removeClass('display');
                break;
            case 'relatorios_distancia_horario':
                self.parent().removeClass('display');
                break;
            case 'relatorios_coordenadas':
                self.parent().removeClass('display');
                break;
            case 'cadastros_veiculo':
                self.parent().removeClass('display');
                break;
            case 'configuracoes_parametro':
                self.parent().removeClass('display');
                break;
            case 'atendimentos_fatura':
                self.parent().removeClass('display');
                break;
            case 'administrar_usuarios':
                self.parent().removeClass('display');
                break;
        }
    }

    var flexPlan = function(self) {
        $('#mo').removeClass('display');
        $('#ta').addClass('display');
        switch(self.val()) {
            case 'home_cerca':
                self.parent().removeClass('display');
                break;
            case 'alertas_gestor':
                self.parent().removeClass('display');
                break;
            case 'relatorios_odometro':
                self.parent().removeClass('display');
                break;
            case 'relatorios_evento_cerca':
                self.parent().removeClass('display');
                break;
            case 'violacao_cerca':
                self.parent().removeClass('display');
                break;
            case 'relatorios_evento_ignicao':
                self.parent().removeClass('display');
                break;
            case 'relatorios_evento_detalhado':
                self.parent().removeClass('display');
                break;
            case 'relatorios_atualizados':
                self.parent().removeClass('display');
                break;
            case 'relatorios_tempo_parado':
                self.parent().removeClass('display');
                break;
            case 'relatorios_tempo_ligado':
                self.parent().removeClass('display');
                break;
            case 'relatorios_pontos_detalhados':
                self.parent().removeClass('display');
                break;
            case 'relatorios_play':
                self.parent().removeClass('display');
                break;
            case 'relatorios_percurso':
                self.parent().removeClass('display');
                break;
            case 'relatorios_analitico':
                self.parent().removeClass('display');
                break;
            case 'relatorios_viagem':
                self.parent().removeClass('display');
                break;
            case 'relatorios_ponto_interesse':
                self.parent().removeClass('display');
                break;
            case 'relatorios_status_atual':
                self.parent().removeClass('display');
                break;
            case 'monitoramento_panico':
                self.parent().removeClass('display');
                break;
            case 'monitoramento_violacao':
                self.parent().removeClass('display');
                break;
            case 'monitoramento_excesso_velocidade':
                self.parent().removeClass('display');
                break;
            case 'monitoramento_manutencao':
                self.parent().removeClass('display');
                break;
            case 'monitoramento_logistica':
                self.parent().removeClass('display');
                break;
            case 'cadastros_motorista':
                self.parent().removeClass('display');
                break;
            case 'cadastros_monitores':
                self.parent().removeClass('display');
                break;
            case 'areas_interesse':
                self.parent().removeClass('display');
                break;
            case 'movimento_indevido':
                self.parent().removeClass('display');
                break;
            case 'cadastros_abastecimento':
                self.parent().removeClass('display');
                break;
            case 'cadastros_despesas':
                self.parent().removeClass('display');
                break;
            case 'configuracoes_sms':
                self.parent().removeClass('display');
                break;
            case 'atendimentos_ordem_servico':
                self.parent().removeClass('display');
                break;
            case 'atendimentos_log_sms':
                self.parent().removeClass('display');
                break;
        }
    }

    var taxi = function(self) {
        $('#ta').removeClass('display');
        switch(self.val()) {
            case 'relatorios_taxi_log_taximetro':
                self.parent().removeClass('display');
                break;
            case 'relatorios_taxi_log_taximetro_valor':
                self.parent().removeClass('display');
                break;
            case 'relatorios_taxi_excesso_velocidade':
                self.parent().removeClass('display');
                break;
            case 'relatorios_taxi_area_atuacao':
                self.parent().removeClass('display');
                break;
            case 'relatorios_taxi_distancia':
                self.parent().removeClass('display');
                break;
            case 'relatorios_bandeira':
                self.parent().removeClass('display');
                break;
            case 'relatorios_alerta':
                self.parent().removeClass('display');
                break;
            case 'relatorios_tempo_ocupado':
                self.parent().removeClass('display');
                break;
            case 'relatorios_taxi_indice':
                self.parent().removeClass('display');
                break;
            case 'relatorios_desatualizados':
                self.parent().removeClass('display');
                break;
            case 'relatorios_tempos':
                self.parent().removeClass('display');
                break;
            case 'relatorios_status_taximetro':
                self.parent().removeClass('display');
                break;
            case 'relatorios_log_veiculo':
                self.parent().removeClass('display');
                break;
            case 'relatorios_violacao_modulo':
                self.parent().removeClass('display');
                break;
            case 'relatorios_remocao_bateria':
                self.parent().removeClass('display');
                break;
            case 'relatorios_panico':
                self.parent().removeClass('display');
                break;
        }
    }

    var notTaxi = function(self) {
        $('#mo').removeClass('display');
        $('#ta').addClass('display');
        switch(self.val()) {
            case 'relatorios_taxi_log_taximetro':
                break;
            case 'relatorios_taxi_log_taximetro_valor':
                break;
            case 'relatorios_taxi_excesso_velocidade':
                break;
            case 'relatorios_taxi_area_atuacao':
                break;
            case 'relatorios_taxi_distancia':
                break;
            case 'relatorios_bandeira':
                break;
            case 'relatorios_alerta':
                break;
            case 'relatorios_tempo_ocupado':
                break;
            case 'relatorios_taxi_indice':
                break;
            case 'relatorios_desatualizados':
                break;
            case 'relatorios_tempos':
                break;
            case 'relatorios_status_taximetro':
                break;
            case 'relatorios_log_veiculo':
                break;
            case 'relatorios_violacao_modulo':
                break;
            case 'relatorios_remocao_bateria':
                break;
            case 'relatorios_panico':
                break;
            default:
                self.parent().removeClass('display');
        }
    }

    $('input[type=checkbox][name=permissoes[]]').each(function() {
        basicPlan($(this));
    });

    $(document).on('click', '#basic',function() {
        $('input[type=checkbox][name=permissoes[]]').each(function() {
            $(this).parent().addClass('display');
            basicPlan($(this));
        });
    });

    $(document).on('click', '#flex',function() {
        $('input[type=checkbox][name=permissoes[]]').each(function() {
            $(this).parent().addClass('display');
            basicPlan($(this));
            flexPlan($(this));
        });
    });

    $(document).on('click', '#premium',function() {
        $('input[type=checkbox][name=permissoes[]]').each(function() {
            $(this).parent().addClass('display');
            notTaxi($(this));
        });
    });

    $(document).on('click', '#taxi',function() {
        $('input[type=checkbox][name=permissoes[]]').each(function() {
            $(this).parent().addClass('display');
            taxi($(this));
        });
    });
});