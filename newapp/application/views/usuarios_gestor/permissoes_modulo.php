<style type="text/css">
    .display-permissoes {
        display-permissoes: none !important;
    }
</style>
<script src=""></script>
<div class="row-fluid">
    <input type="hidden" value="<?php echo $usuario->id_cliente ?>" name="cli" />
    <input type="hidden" value="<?php echo $usuario->code ?>" name="user" />
    <div class="span12" style="text-align: center;">
        <div class="btn-group">
            <button class="btn" id="basic">Basic</button>
            <button class="btn" id="flex">Flex</button>
            <button class="btn" id="premium">Premium</button>
            <button class="btn" id="taxi">Táxi</button>
        </div>
    </div>
    <div class="container">
        <div class="row-fluid">
            <div class="span3">
                <br>
                <label class="control-label"><b>
                        <h4>Painel principal</h4>
                    </b></label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="visualizar_bloqueio" <?php echo set_selecionado('visualizar_bloqueio', unserialize($usuario->permissoes), 'checked') ?> />
                    Visualizar Bloqueio
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="icon_panic" <?php echo set_selecionado('icon_panic', unserialize($usuario->permissoes), 'checked') ?> />
                    Visualizar Pânico
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="mensagens_app" <?php echo set_selecionado('mensagens_app', unserialize($usuario->permissoes), 'checked') ?> />
                    App Mensagens
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="ftp_cliente" <?php echo set_selecionado('ftp_cliente', unserialize($usuario->permissoes), 'checked') ?> />
                    FTP Envio
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="home_cerca" <?php echo set_selecionado('home_cerca', unserialize($usuario->permissoes), 'checked') ?> />
                    Cerca Eletrônica
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="alertas_gestor" <?php echo set_selecionado('alertas_gestor', unserialize($usuario->permissoes), 'checked') ?> />
                    Alertas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="posto" <?php echo set_selecionado('posto', unserialize($usuario->permissoes), 'checked') ?> />
                    Posto
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="gestor_entrega" <?php echo set_selecionado('gestor_entrega', unserialize($usuario->permissoes), 'checked') ?> />
                    Gestor de Entregas
                </label>
            </div>
            <div class="span3 display-permissoes" id="mo">
                <br>
                <label class="control-label"><b>
                        <h4>Monitoramento</h4>
                    </b></label>
                <label class="control-label">
                    <input type="checkbox" name="permissoes[]" value="monitoramento" checked="checked" <?php echo set_selecionado('monitoramento', unserialize($usuario->permissoes), 'checked') ?> />
                    <b>Monitoramento</b>
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="monitoramento_iscas" <?php echo set_selecionado('monitoramento_iscas', unserialize($usuario->permissoes), 'checked') ?> />
                    Monitoramento de Iscas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="monitoramento_panico" <?php echo set_selecionado('monitoramento_panico', unserialize($usuario->permissoes), 'checked') ?> />
                    Pânico
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="show_cidadao" <?php echo set_selecionado('show_cidadao', unserialize($usuario->permissoes), 'checked') ?> />
                    Show Cidadão - Denúncias
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="monitoramento_evento" <?php echo set_selecionado('monitoramento_evento', unserialize($usuario->permissoes), 'checked') ?> />
                    Monitoramento de Eventos
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="monitoramento_grid" <?php echo set_selecionado('monitoramento_grid', unserialize($usuario->permissoes), 'checked') ?> />
                    Grid de Monitoramento
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="geo" <?php echo set_selecionado('geo', unserialize($usuario->permissoes), 'checked') ?> />
                    GEO - Gestor de Entregas Online
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="monitoramento_violacao" <?php echo set_selecionado('monitoramento_violacao', unserialize($usuario->permissoes), 'checked') ?> />
                    Equipamento violado
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="monitoramento_excesso_velocidade" <?php echo set_selecionado('monitoramento_excesso_velocidade', unserialize($usuario->permissoes), 'checked') ?> />
                    Excesso de Velocidade
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="monitoramento_manutencao" <?php echo set_selecionado('monitoramento_manutencao', unserialize($usuario->permissoes), 'checked') ?> />
                    Alertas de Manutenção
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="monitoramento_logistica" <?php echo set_selecionado('monitoramento_logistica', unserialize($usuario->permissoes), 'checked') ?> />
                    Logistica
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="mapa_shape" <?php echo set_selecionado('mapa_shape', unserialize($usuario->permissoes), 'checked') ?> />
                    Mapa Shape
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cnh_vencida" <?php echo set_selecionado('cnh_vencida', unserialize($usuario->permissoes), 'checked') ?> />
                    CNH Vencida
                </label>
            </div>
            <div class="span3">
                <br>
                <label class="control-label"><b>
                        <h4>Atendimento</h4>
                    </b></label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="atendimentos_fatura" <?php echo set_selecionado('atendimentos_fatura', unserialize($usuario->permissoes), 'checked') ?> />
                    Faturas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="atendimentos_ordem_servico" <?php echo set_selecionado('atendimentos_ordem_servico', unserialize($usuario->permissoes), 'checked') ?> />
                    Ordem de Serviços
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="downloads_contrato_permissionario" <?php echo set_selecionado('downloads_contrato_permissionario', unserialize($usuario->permissoes), 'checked') ?> />
                    Contrato Permissionário
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="downloads_contrato_eptc" <?php echo set_selecionado('downloads_contrato_eptc', unserialize($usuario->permissoes), 'checked') ?> />
                    Contratos EPTC
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="comissao" <?php echo set_selecionado('comissao', unserialize($usuario->permissoes), 'checked') ?> />
                    Relatório de Comissão
                </label>
            </div>
            <div class="span3 display-permissoes" style="margin-left: 0px;" id="ta">
                <br>
                <label class="control-label"><b>
                        <h4>Táxi</h4>
                    </b> </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_taxi_log_taximetro" <?php echo set_selecionado('relatorios_taxi_log_taximetro', unserialize($usuario->permissoes), 'checked') ?> />
                    Log taximetro
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_taxi_log_taximetro_valor" <?php echo set_selecionado('relatorios_taxi_log_taximetro_valor', unserialize($usuario->permissoes), 'checked') ?> />
                    Exibir valor corrida (Log taxímetro)
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_taxi_excesso_velocidade" <?php echo set_selecionado('relatorios_taxi_excesso_velocidade', unserialize($usuario->permissoes), 'checked') ?> />
                    Excesso de velocidade
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_taxi_area_atuacao" <?php echo set_selecionado('relatorios_taxi_area_atuacao', unserialize($usuario->permissoes), 'checked') ?> />
                    Área Atuação
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_taxi_distancia" <?php echo set_selecionado('relatorios_taxi_distancia', unserialize($usuario->permissoes), 'checked') ?> />
                    Distância
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_bandeira" <?php echo set_selecionado('relatorios_bandeira', unserialize($usuario->permissoes), 'checked') ?> />
                    Bandeira
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_alerta" <?php echo set_selecionado('relatorios_alerta', unserialize($usuario->permissoes), 'checked') ?> />
                    Alertas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_tempo_ocupado" <?php echo set_selecionado('relatorios_tempo_ocupado', unserialize($usuario->permissoes), 'checked') ?> />
                    Tempo Ocupado
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_taxi_indice" <?php echo set_selecionado('relatorios_taxi_indice', unserialize($usuario->permissoes), 'checked') ?> />
                    Índices
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_desatualizados" <?php echo set_selecionado('relatorios_desatualizados', unserialize($usuario->permissoes), 'checked') ?> />
                    Desatualizados
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_tempos" <?php echo set_selecionado('relatorios_tempos', unserialize($usuario->permissoes), 'checked') ?> />
                    Tempos
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_status_taximetro" <?php echo set_selecionado('relatorios_status_taximetro', unserialize($usuario->permissoes), 'checked') ?> />
                    Status Taxímetro
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_log_veiculo" <?php echo set_selecionado('relatorios_log_veiculo', unserialize($usuario->permissoes), 'checked') ?> />
                    Log Veículo
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_violacao_modulo" <?php echo set_selecionado('relatorios_violacao_modulo', unserialize($usuario->permissoes), 'checked') ?> />
                    Violação do Módulo Antifurto
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_remocao_bateria" <?php echo set_selecionado('relatorios_remocao_bateria', unserialize($usuario->permissoes), 'checked') ?> />
                    Remoção da Bateria Principal do Veículo
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_panico" <?php echo set_selecionado('relatorios_panico', unserialize($usuario->permissoes), 'checked') ?> />
                    Pânico
                </label>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="container">
        <div class="row-fluid">
            <div class="span3">
                <br>
                <label class="control-label"><b>
                        <h4>Relatórios</h4>
                    </b></label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="quantidade_transmissoes" <?php echo set_selecionado('quantidade_transmissoes', unserialize($usuario->permissoes), 'checked') ?> />
                    Quantidade de Transmissoes
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorio_sintatico" <?php echo set_selecionado('relatorio_sintatico', unserialize($usuario->permissoes), 'checked') ?> />
                    Relatórios Sintático
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_diario_motorista" <?php echo set_selecionado('relatorios_diario_motorista', unserialize($usuario->permissoes), 'checked') ?> />
                    Resumo diário do motorista
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorio_condutor_em_atividade" <?php echo set_selecionado('relatorio_condutor_em_atividade', unserialize($usuario->permissoes), 'checked') ?> />
                    Condutor em atividade
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_ins" <?php echo set_selecionado('relatorios_ins', unserialize($usuario->permissoes), 'checked') ?> />
                    Gerenciador de INS
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_excesso_velocidade" <?php echo set_selecionado('relatorios_excesso_velocidade', unserialize($usuario->permissoes), 'checked') ?> />
                    Excesso de Velocidade
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_distancia_horario" <?php echo set_selecionado('relatorios_distancia_horario', unserialize($usuario->permissoes), 'checked') ?> />
                    Km's Rodados
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_log_app" <?php echo set_selecionado('relatorios_log_app', unserialize($usuario->permissoes), 'checked') ?> />
                    Log do App Jornada
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_visita" <?php echo set_selecionado('relatorios_visita', unserialize($usuario->permissoes), 'checked') ?> />
                    Visita
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_odometro" <?php echo set_selecionado('relatorios_odometro', unserialize($usuario->permissoes), 'checked') ?> />
                    Odômetro
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_desempenho_operacional" <?php echo set_selecionado('relatorios_desempenho_operacional', unserialize($usuario->permissoes), 'checked') ?> />
                    Desempenho Operacional
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_desempenho_expediente" <?php echo set_selecionado('relatorios_desempenho_expediente', unserialize($usuario->permissoes), 'checked') ?> />
                    Desempenho Expediente
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_desempenho_ociosidade" <?php echo set_selecionado('relatorios_desempenho_ociosidade', unserialize($usuario->permissoes), 'checked') ?> />
                    Ociosidade X Status/Ignição
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="violacao_cerca" <?php echo set_selecionado('violacao_cerca', unserialize($usuario->permissoes), 'checked') ?> />
                    Violção de cerca
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="fence_time" <?php echo set_selecionado('fence_time', unserialize($usuario->permissoes), 'checked') ?> />
                    Tempo de Permanência - Cerca
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="tms" <?php echo set_selecionado('tms', unserialize($usuario->permissoes), 'checked') ?> />
                    TMS - Tempo Médio de Saída
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="driver_fence" <?php echo set_selecionado('driver_fence', unserialize($usuario->permissoes), 'checked') ?> />
                    Detalhamento de Motorista
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_evento_cerca" <?php echo set_selecionado('relatorios_evento_cerca', unserialize($usuario->permissoes), 'checked') ?> />
                    Evento Cerca
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_evento_ignicao" <?php echo set_selecionado('relatorios_evento_ignicao', unserialize($usuario->permissoes), 'checked') ?> />
                    Evento Ignição
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_evento_detalhado" <?php echo set_selecionado('relatorios_evento_detalhado', unserialize($usuario->permissoes), 'checked') ?> />
                    Evento Detalhado
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorio_transmissao_analitica" <?php echo set_selecionado('relatorio_transmissao_analitica', unserialize($usuario->permissoes), 'checked') ?> />
                    Transmissao Analítica
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_atualizados" <?php echo set_selecionado('relatorios_atualizados', unserialize($usuario->permissoes), 'checked') ?> />
                    Atualizados
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_jornada_trabalho" <?php echo set_selecionado('relatorios_jornada_trabalho', unserialize($usuario->permissoes), 'checked') ?> />
                    Jornada de Trabalho
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="detalhamento_jornada" <?php echo set_selecionado('detalhamento_jornada', unserialize($usuario->permissoes), 'checked') ?> />
                    Resumo de Jornada
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_tempo_ocioso" <?php echo set_selecionado('relatorios_tempo_ocioso', unserialize($usuario->permissoes), 'checked') ?> />
                    Tempo Ocioso
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_tempo_parado" <?php echo set_selecionado('relatorios_tempo_parado', unserialize($usuario->permissoes), 'checked') ?> />
                    Tempo Parado
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_tempo_ligado" <?php echo set_selecionado('relatorios_tempo_ligado', unserialize($usuario->permissoes), 'checked') ?> />
                    Tempo Ligado
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_pontos_detalhados" <?php echo set_selecionado('relatorios_pontos_detalhados', unserialize($usuario->permissoes), 'checked') ?> />
                    Rota
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_play" <?php echo set_selecionado('relatorios_play', unserialize($usuario->permissoes), 'checked') ?> />
                    Mapa Rota
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_percurso" <?php echo set_selecionado('relatorios_percurso', unserialize($usuario->permissoes), 'checked') ?> />
                    Percurso
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_coordenadas" <?php echo set_selecionado('relatorios_coordenadas', unserialize($usuario->permissoes), 'checked') ?> />
                    Coordenadas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="rel_tickets" <?php echo set_selecionado('rel_tickets', unserialize($usuario->permissoes), 'checked') ?> />
                    Ticket's
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="acionamento_portas" <?php echo set_selecionado('acionamento_portas', unserialize($usuario->permissoes), 'checked') ?> />
                    Acionamento Portas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_tacografo" <?php echo set_selecionado('relatorios_tacografo', unserialize($usuario->permissoes), 'checked') ?> />
                    Tacógrafo
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_motByBoard" <?php echo set_selecionado('relatorios_motByBoard', unserialize($usuario->permissoes), 'checked') ?> />
                    Motoristas por Veículo
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_veicByMotorista" <?php echo set_selecionado('relatorios_veicByMotorista', unserialize($usuario->permissoes), 'checked') ?> />
                    Veículos por motorista
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_analitico" <?php echo set_selecionado('relatorios_analitico', unserialize($usuario->permissoes), 'checked') ?> />
                    Analítico
                </label>
                <label class="checkbox" style="width: 306px;">
                    <input type="checkbox" name="permissoes[]" value="rel_usuarios" <?php echo set_selecionado('rel_usuarios', unserialize($usuario->permissoes), 'checked') ?> />
                    Relatório de Usuários
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_bdv" <?php echo set_selecionado('relatorios_bdv', unserialize($usuario->permissoes), 'checked') ?> />
                    BDV - Eletrônico
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_viagem" <?php echo set_selecionado('relatorios_viagem', unserialize($usuario->permissoes), 'checked') ?> />
                    Viagem
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_ponto_motorista" <?php echo set_selecionado('relatorios_ponto_motorista', unserialize($usuario->permissoes), 'checked') ?> />
                    Ponto Motorista
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="rank_driver" <?php echo set_selecionado('rank_driver', unserialize($usuario->permissoes), 'checked') ?> />
                    Relatório Rank de Motorista
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_telemetria" <?php echo set_selecionado('relatorios_telemetria', unserialize($usuario->permissoes), 'checked') ?> />
                    Telemetria
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_ponto_interesse" <?php echo set_selecionado('relatorios_ponto_interesse', unserialize($usuario->permissoes), 'checked') ?> />
                    Ponto de Interesse
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="raio_interesse" <?php echo set_selecionado('raio_interesse', unserialize($usuario->permissoes), 'checked') ?> />
                    Raio de Interesse
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_expediente" <?php echo set_selecionado('relatorios_expediente', unserialize($usuario->permissoes), 'checked') ?> />
                    Horário Fora do Expediente
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_status_atual" <?php echo set_selecionado('relatorios_status_atual', unserialize($usuario->permissoes), 'checked') ?> />
                    Status Atual
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_aceleracao_brusca" <?php echo set_selecionado('relatorios_aceleracao_brusca', unserialize($usuario->permissoes), 'checked') ?> />
                    Aceleração Brusca
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_freada_brusca" <?php echo set_selecionado('relatorios_freada_brusca', unserialize($usuario->permissoes), 'checked') ?> />
                    Freada Brusca
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorios_risco_tombamento" <?php echo set_selecionado('relatorios_risco_tombamento', unserialize($usuario->permissoes), 'checked') ?> />
                    Curva Acentuada
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorio_acesso" <?php echo set_selecionado('relatorio_acesso', unserialize($usuario->permissoes), 'checked') ?> />
                    Curva Acentuada
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="dashboard" <?php echo set_selecionado('dashboard', unserialize($usuario->permissoes), 'checked') ?> />
                    Dashboard
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorio_ga" <?php echo set_selecionado('relatorio_ga', unserialize($usuario->permissoes), 'checked') ?> />
                    Relatório GA
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="relatorio_sensores" <?php echo set_selecionado('relatorio_sensores', unserialize($usuario->permissoes), 'checked') ?> />
                    Relatório Sensores
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="perfil_condutor" <?php echo set_selecionado('perfil_condutor', unserialize($usuario->permissoes), 'checked') ?> />
                    Perfil Condutor
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="disponibilidade_sistema" <?php echo set_selecionado('disponibilidade_sistema', unserialize($usuario->permissoes), 'checked') ?> />
                    Disponibilidade do Sistema
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="indisponibilidade_veiculo" <?php echo set_selecionado('indisponibilidade_veiculo', unserialize($usuario->permissoes), 'checked') ?> />
                    Indisponibilidade de Veículos
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="deslocamento_acumulado" <?php echo set_selecionado('deslocamento_acumulado', unserialize($usuario->permissoes), 'checked') ?> />
                    Deslocamento Acumulado Diário
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="rel_cadastroVeic" <?php echo set_selecionado('rel_cadastroVeic', unserialize($usuario->permissoes), 'checked') ?> />
                    Cadastro de Veículo
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="rel_cadastroMot" <?php echo set_selecionado('rel_cadastroMot', unserialize($usuario->permissoes), 'checked') ?> />
                    Cadastro de Motorista
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="rel_temperatura" <?php echo set_selecionado('rel_temperatura', unserialize($usuario->permissoes), 'checked') ?> />
                    Temperatura
                </label>
            </div>
            <div class="span3">
                <br>
                <label class="control-label"><b>
                        <h4>Cadastros</h4>
                    </b></label>
                <label class="control-label">
                    <input type="checkbox" name="permissoes[]" value="cadastros" checked="checked" <?php echo set_selecionado('cadastros', unserialize($usuario->permissoes), 'checked') ?> />
                    <b>Cadastro</b>
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cadastros_veiculo" <?php echo set_selecionado('cadastros_veiculo', unserialize($usuario->permissoes), 'checked') ?> />
                    Veículos
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cadastros_motorista" <?php echo set_selecionado('cadastros_motorista', unserialize($usuario->permissoes), 'checked') ?> />
                    Motoristas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="vincular_motorista" <?php echo set_selecionado('vincular_motorista', unserialize($usuario->permissoes), 'checked') ?> />
                    Vincular Motoristas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cadastros_monitores" <?php echo set_selecionado('cadastros_monitores', unserialize($usuario->permissoes), 'checked') ?> />
                    Ajudantes/Monitores
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="areas_interesse" <?php echo set_selecionado('areas_interesse', unserialize($usuario->permissoes), 'checked') ?> />
                    Áreas de Interesse
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="movimento_indevido" <?php echo set_selecionado('movimento_indevido', unserialize($usuario->permissoes), 'checked') ?> />
                    Movimento Indevido
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="gerenciar_reservas" <?php echo set_selecionado('gerenciar_reservas', unserialize($usuario->permissoes), 'checked') ?> />
                    Gerenciador de Reservas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cad_checklist" <?php echo set_selecionado('cad_checklist', unserialize($usuario->permissoes), 'checked') ?> />
                    Cadastro de Checklists
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cad_fornecedor" <?php echo set_selecionado('cad_fornecedor', unserialize($usuario->permissoes), 'checked') ?> />
                    Cadastro de Fornecedores
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cad_multas" <?php echo set_selecionado('cad_multas', unserialize($usuario->permissoes), 'checked') ?> />
                    Cadastro de Multas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cad_clientes" <?php echo set_selecionado('cad_clientes', unserialize($usuario->permissoes), 'checked') ?> />
                    Cadastro de Clientes
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cadastros_programacao_petrobras" <?php echo set_selecionado('cadastros_programacao_petrobras', unserialize($usuario->permissoes), 'checked') ?> />
                    Programação Petrobras
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cadastros_abastecimento" <?php echo set_selecionado('cadastros_abastecimento', unserialize($usuario->permissoes), 'checked') ?> />
                    Abastecimento
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cadastros_form_roubo" <?php echo set_selecionado('cadastros_form_roubo', unserialize($usuario->permissoes), 'checked') ?> />
                    Formulário de Roubo
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cadastros_despesas" <?php echo set_selecionado('cadastros_despesas', unserialize($usuario->permissoes), 'checked') ?> />
                    Despesas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cadastros_macro" <?php echo set_selecionado('cadastros_macro', unserialize($usuario->permissoes), 'checked') ?> />
                    Macro
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="espelhamento" <?php echo set_selecionado('espelhamento', unserialize($usuario->permissoes), 'checked') ?> />
                    Espelhamento
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="interval_transmission" <?php echo set_selecionado('interval_transmission', unserialize($usuario->permissoes), 'checked') ?> />
                    Intervalo de Transmissão
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="acidente" <?php echo set_selecionado('acidente', unserialize($usuario->permissoes), 'checked') ?> />
                    Acidentes
                </label>
            </div>
            <div class="span3">
                <br>
                <label class="control-label"><b>
                        <h4>Configurações</h4>
                    </b></label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="configuracoes_parametro" <?php echo set_selecionado('configuracoes_parametro', unserialize($usuario->permissoes), 'checked') ?> />
                    Notificações
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="status_entregas" <?php echo set_selecionado('status_entregas', unserialize($usuario->permissoes), 'checked') ?> />
                    Status de Entrega - SHOWROUTES
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="tempo_parada" <?php echo set_selecionado('tempo_parada', unserialize($usuario->permissoes), 'checked') ?> />
                    Tempo de Parada - SHOWROUTES
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="sinergye" <?php echo set_selecionado('sinergye', unserialize($usuario->permissoes), 'checked') ?> />
                    Sistema Chromos - Sinergye
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="config_ftp" <?php echo set_selecionado('config_ftp', unserialize($usuario->permissoes), 'checked') ?> />
                    FTP
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="calc_coordenadas" <?php echo set_selecionado('calc_coordenadas', unserialize($usuario->permissoes), 'checked') ?> />
                    Calculadora de Coordenadas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="automaticBlocking" <?php echo set_selecionado('automaticBlocking', unserialize($usuario->permissoes), 'checked') ?> />
                    Agendamento de Bloqueio e Desbloqueio
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="business" <?php echo set_selecionado('business', unserialize($usuario->permissoes), 'checked') ?> />
                    Integração Business Intelligence (B.I)
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="configuracoes_sms" <?php echo set_selecionado('configuracoes_sms', unserialize($usuario->permissoes), 'checked') ?> />
                    SMS Rota
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="administrar_usuarios" <?php echo set_selecionado('administrar_usuarios', unserialize($usuario->permissoes), 'checked') ?> />
                    Usuários
                </label>
                <label class="checkbox" style="width: 306px;">
                    <input type="checkbox" name="permissoes[]" value="relatorios_motorista_coordenadas" <?php echo set_selecionado('relatorios_motorista_coordenadas', unserialize($usuario->permissoes), 'checked') ?> />
                    Nome do Motorista no Relatório de Coordenadas
                </label>
                <label class="checkbox" style="width: 306px;">
                    <input type="checkbox" name="permissoes[]" value="temperatura_coordenadas" <?php echo set_selecionado('temperatura_coordenadas', unserialize($usuario->permissoes), 'checked') ?> />
                    Temperatura no Relatório de Coordenadas
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="enviar_comando_gestor" <?php echo set_selecionado('enviar_comando_gestor', unserialize($usuario->permissoes), 'checked') ?> />
                    Comandos no Gestor
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="atendimentos_envio_arquivo" <?php echo set_selecionado('atendimentos_envio_arquivo', unserialize($usuario->permissoes), 'checked') ?> />
                    Envio de Arquivos
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="atendimentos_log_sms" <?php echo set_selecionado('atendimentos_log_sms', unserialize($usuario->permissoes), 'checked') ?> />
                    Logs - SMS
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="google" <?php echo set_selecionado('google', unserialize($usuario->permissoes), 'checked') ?> />
                    Google
                </label>
                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cerca_fp" <?php echo set_selecionado('cerca_fp', unserialize($usuario->permissoes), 'checked') ?> />
                    Cerca FP
                </label>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="container">
        <div class="row-fluid">
            <div class="span3">
                <br>
                <label class="control-label"><b>
                        <h4>Monitoramento de Apenados</h4>
                    </b></label>

                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="admin_detentos" <?php echo set_selecionado('admin_detentos', unserialize($usuario->permissoes), 'checked') ?> />
                    <b>Administrar Monitoramento</b>
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cad_detentos" <?php echo set_selecionado('cad_detentos', unserialize($usuario->permissoes), 'checked') ?> />
                    Cadastro de Detentos
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="cad_vitimas" <?php echo set_selecionado('cad_vitimas', unserialize($usuario->permissoes), 'checked') ?> />
                    Cadastro de Vítimas
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="rel_homonimos" <?php echo set_selecionado('rel_homonimos', unserialize($usuario->permissoes), 'checked') ?> />
                    Relatório de Homônimos
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="permissoes[]" value="rel_eventos_tornozeleira" <?php echo set_selecionado('rel_eventos_tornozeleira', unserialize($usuario->permissoes), 'checked') ?> />
                    Eventos Tornozeleira
                </label>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= base_url('assets/js/modules/permissions.js') ?>"></script>