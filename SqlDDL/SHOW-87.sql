/* Menu */
CREATE TABLE `showtecsystem`.`menu` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `caminho` VARCHAR(150) NULL,
  `codigo_permissao` VARCHAR(45) NOT NULL,
  `filhos` ENUM('nao', 'sim') NOT NULL DEFAULT 'nao',
  `id_pai` INT(11) UNSIGNED NULL,
  `icone` VARCHAR(45) NULL,
  `ordem` INT(11) NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_id` (`id` ASC));
  UNIQUE INDEX `idx_ordem` (`ordem` ASC);
;
ALTER TABLE `showtecsystem`.`menu` 
ADD INDEX `idx_fk__menu__pai__menu` (`id_pai` ASC);
;
ALTER TABLE `showtecsystem`.`menu` 
ADD CONSTRAINT `fk__menu__pai__menu`
  FOREIGN KEY (`id_pai`)
  REFERENCES `showtecsystem`.`menu` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

/* Atalhos */
CREATE TABLE `showtecsystem`.`atalho_usuario` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` INT(11) UNSIGNED NOT NULL,
  `id_menu` INT(11) UNSIGNED NOT NULL,
  `ordem` INT(11) UNSIGNED NOT NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `data_exclusao` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_id` (`id` ASC),
  INDEX `idx_fk__atalho_usuario__id_menu__menu` (`id_menu` ASC),
  INDEX `idx_fk__atalho_usuario__id_usuario__usuario` (`id_usuario` ASC),
  CONSTRAINT `fk__atalho_usuario__id_menu__menu`
    FOREIGN KEY (`id_menu`)
    REFERENCES `showtecsystem`.`menu` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk__atalho_usuario__id_usuario__usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `showtecsystem`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


/* Inserts */
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`) VALUES ('tela_inicial', 'home', 'vis_novahome', 'nao', null, 'home');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`) VALUES ('a_empresa', null, '', 'sim', null, 'info');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`) VALUES ('sobre', 'Empresas/Sobre', 'vis_sobreaempresa', 'nao', '2', 'info');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`) VALUES ('contatos_corporativos', 'Empresas/ContatosCorporativos', 'vis_contatoscorporativos', 'nao', '2', 'contacts');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('organograma', 'Empresas/Organogramas', 'vis_organograma', 'nao', '2', 'account_tree', 'inativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`) VALUES ('departamentos', null, '', 'sim', null, null);
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`) VALUES ('gente_gestao', null, 'vis_genteegestao', 'sim', '6', null);
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`) VALUES ('informacoes_gerais', 'GentesGestoes/InformacoesGerais', 'vis_informacaogeral', 'nao', '7', 'groups');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`) VALUES ('desenvolvimento_organizacional', 'GentesGestoes/DesenvolvimentosOrganizacionais', 'vis_desenvolvimentoorganizacional', 'nao', '7', 'expand');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`) VALUES ('administracao_pessoal', 'GentesGestoes/AdministracoesPessoais', 'vis_administracaopessoal', 'nao', '7', 'admin_panel_settings');
INSERT INTO `showtecsystem`.`menu` (`nome`, `filhos`, `icone`, `status`) VALUES ('informacoes', 'sim', NULL, 'inativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('banners', 'cadastros/listar_banners', 'cad_banner', 'nao', '11', 'collections', 'inativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('financeiro', 'financeiro_acesso', 'sim', '6', NULL, 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('inadimplencias', 'faturas/inadimplencia', 'inadimplencias_faturas', 'nao', '13', 'disabled_by_default', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('ordem_de_pagamento', 'contas/pre_aprovacao', 'lancamentos', 'nao', '13', 'payment', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('fatura', 'faturas', 'sim', '13', NULL, 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('lista', 'faturas', 'faturas_visualiza', 'nao', '16', 'receipt', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('config_boleto', 'config_boleto', 'nao', '16', 'confirmation_number', 'inativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('baixa_por_retorno', 'faturas/baixar', 'faturas_retorno', 'nao', '13', 'attach_money', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('chave_de_desconto', 'chave_desconto', 'sim', '13', 'attach_money', 'inativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('criar', 'NovaChave', 'criar_chave_desconto', 'nao', '20', 'attach_money', 'inativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('listar', 'ListarChaves', 'listar_chave_desconto', 'nao', '20', 'attach_money', 'inativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('contas', 'contas_a_pagar', 'sim', '13', 'account_balance_wallet', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('showtecnologia', 'contas', 'contas_showtecnologia', 'nao', '23', 'account_balance_wallet', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('showtechnology', 'contas/contas_eua', 'contas_eua', 'nao', '23', 'account_balance_wallet', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('norio_momoi', 'contas/norio', 'contas_showtecnologia', 'nao', '23', 'account_balance_wallet', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('pneu_show', 'contas/pneushow', 'contas_pneushow', 'nao', '23', 'account_balance_wallet', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('baixa_por_extrato', 'extract', 'baixa_extrato_show|baixa_extrato_show', 'nao', '13', 'attach_money', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('ti', 'vis_ti', 'sim', '6', 'devices', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('engenharia', 'vis_engenharia', 'sim', '6', 'engineering', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('comercial_e_televendas', 'vis_comercial_televendas', 'sim', '6', 'shopping_cart', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('marketing', 'vis_marketing', 'sim', '6', 'tungsten', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('operacoes', 'vis_operacoes', 'sim', '6', 'device_hub', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('controle_de_qualidade', 'vis_controle_qualidade', 'sim', '6', 'hd', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('governanca_corporativa', 'vis_governanca_corporativa', ,'sim', '6', 'corporate_fare', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `filhos`, `icone`, `status`) VALUES ('cadastros', 'sim', 'app_registration', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('clientes', 'clientes', 'clientes_visualiza', 'nao', '36', 'person_add_alt', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('fornecedores', 'cadastro_fornecedor', 'cadastro_fornecedor', 'nao', '36', 'app_registration', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('documentacoes', 'documentacoes', 'documentacoes', 'nao', '36', 'post_add', 'inativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('funcionarios', 'usuarios', 'usuarios_visualiza', 'nao', '36', 'badge', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('veiculos', 'cadastros/veiculos', 'cad_veiculos', 'nao', '36', 'drive_eta', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('permissoes_gestor', 'cadastros/cadastro_produtos', 'cad_permissoes', 'nao', '36', 'key', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('permissoes_usuarios_show', 'usuarios/permissoesFuncionarios', 'cad_permissoes_funcionarios', 'nao', '36', 'key', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('equipamentos', 'equipamentos/listar', 'cad_equipamento', 'nao', '36', 'construction', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('suprimentos', 'suprimentos/listar', 'cad_equipamento', 'nao', '36', 'inventory_2', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('contratos_eptc', 'contratos_eptc/listar_contratos', 'cad_contratos_eptc', 'nao', '36', 'gavel', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('agendamento_de_servicos', 'agendamento', 'cad_agend_servico', 'nao', '36', 'post_add', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('instaladores', 'instaladores/listar_instaladores', 'cad_instaladores', 'nao', '36', 'install_mobile', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('logistica_de_equipamentos', 'gerencia_equipamentos', 'monitoramento', 'nao', '36', 'category', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('representantes', 'representantes/listar_representantes', 'cad_representantes', 'nao', '36', 'post_add', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('comandos', 'cadastros_comandos', 'cad_comandos', 'nao', '36', 'touch_app', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('licitacoes', 'licitacao/acompanhamento', 'licitacoes', 'nao', '36', 'post_add', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('linhas', 'cad_linhas', 'sim', '36', 'post_add', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('mikrotik', 'cadastros/linhas', 'cad_mikrotik', 'nao', '53', 'view_headline', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('chips', 'linhas/listChips', 'nao', '53', 'sim_card', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('comandos_sms', 'comandos/view', 'cad_veiculos', 'nao', '36', 'sms', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('cadastro_centrais', 'cad_centrais/index', 'cad_centrais', 'nao', '36', 'sms', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `filhos`, `icone`, `status`) VALUES ('relatorios', 'sim', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('assinaturas_eptc', 'relatorios/assinatura_eptc', 'nao', '57', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('contratos', 'rel_contratos', 'sim', '57', 'gavel', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('contratos', 'relatorios/contratos', 'rel_contratos', 'nao', '58', 'gavel', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('calculo_rescisao_de_contratos_privados', 'relatorios/rescisao_contratos_privados', 'rel_contratos', 'nao', '58', 'unsubscribe', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('quantitativo_contratos_veiculos', 'relatorios/quantitativoContratos', 'rel_contratos', 'nao', '58', 'format_list_numbered_rtl', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('tempo_logado', 'relatorios/tempo_logado', 'rel_tempo_logado', 'nao', '57', 'schedule', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('tickets', 'relatorios/rel_tickets', 'rel_tickets', 'nao', '57', 'confirmation_number', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('placas_ativas_inativas', 'relatorios/placas_ativas_inativas', 'rel_placas_ativas_inativas', 'nao', '57', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('financeiro', 'sim', '57', 'payments', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('faturas', 'relatorios/faturas', 'rel_financeiro_faturas', 'nao', '66', 'receipt', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('faturas_atrasadas', 'relatorios/faturas_atrasadas', 'rel_financeiro_faturas', 'nao', '66', 'receipt', 'ativo');

INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('faturas_processadas', 'relatorios/faturas_processadas', 'rel_financeiro_faturas', 'nao', '66', 'receipt', 'ativo'); 

INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('resumo_faturamento', 'relatorios/resumo_faturas', 'rel_financeiro_faturas', 'nao', '66', 'receipt', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('resumo_fatura_por_disponibilidade', 'relatorios/fatura_disponibilidade', 'rel_financeiro_faturas', 'nao', '66', 'receipt', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('clientes_inadimplentes', 'relatorios/fatura_cliente', 'rel_financeiro_faturas', 'nao', '66', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('envio_de_faturas', 'relatorios/faturas_enviadas', 'rel_financeiro_fatenviadas', 'nao', '66', 'receipt', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('contas_a_pagar', 'relatorios/contas', 'rel_contas', 'nao', '66', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('comissao', 'relatorios/comissao', 'comissao', 'nao', '66', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('relatorio_por_tipo_de_servico', 'relatorios/relatorio_tipo_servico', 'rel_tipo_servico', 'nao', '66', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('comissao_showroutes', 'relatorios/comissao_dev', 'comissao_showroutes', 'nao', '66', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('geracao_adesao', 'relatorios/rel_adesao', 'rel_adesao', 'nao', '66', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('faturas_geradas', 'relatorios/faturas_geradas', 'rel_financeiro_faturas', 'nao', '66', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('chip_linhas', 'sim', '57', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('equipamentos_linhas_desatualizados', 'equipamentos/equipamentos_parado', 'rel_eqp_desat', 'nao', '79', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('relatorio_linhas', 'linhas/listarchip', 'rel_eqp_desat', 'nao', '79', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('analise_de_fatura_operadora', 'linhas/detConta', 'analise_contaOp', 'nao', '79', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('clientes', 'sim', '57', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('clientes_por_uf', 'relatorios/clientes_uf', 'rel_clientes_uf', 'nao', '83', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('resumo_veiculos_disponiveis', 'relatorios/resumoVeiculosDisponiveis', 'rel_resumo_veic_disponiveis', 'nao', '83', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('veiculos_disponiveis', 'relatorios/veiculosDisponiveis', 'rel_veic_disponiveis', 'nao', '83', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('veiculos_dia_atualizacao', 'relatorios/veiculosDiaAtualizacao', 'rel_veic_disponiveis', 'nao', '83', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('veiculos_por_atividades', 'relatorios/veiculos_por_atividades', 'rel_veic_disponiveis', 'nao', '83', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('monitorados_dia_atualizacao', 'relatorios/monitoradosDiaAtualizacao', 'rel_monitorados_dia_atividade', 'nao', '83', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('relatorio_por_tempo_de_contrato', 'relatorios/veiculos_tempo_contrato', 'rel_veic_tempo_contrato', 'nao', '83', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('relatorio_clientes_publicos', 'relatorios/clientes_publicos', 'rel_clients_publicos', 'nao', '83', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('dashboard_veiculos_disponiveis', 'relatorios/dashboardVeiculosDisponiveis', 'rel_dash_veic', 'nao', '83', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('base_clientes', 'relatorios/base_clientes', 'vis_relatoriobasedeclientes', 'nao', '83', 'summarize', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `icone`, `status`) VALUES ('monitoramento', 'monitoramento', 'sim', 'web', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('dashboard_tickets', 'webdesk/view_dash', 'monitoramento', 'nao', '94', 'web', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('desatualizados', 'veiculos/desatualizados', 'veiculos_desatualizados', 'nao', '94', 'web', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('panico', 'monitor/monitor_panico', 'monitor_panico', 'nao', '94', 'web', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('equipamento_violado', 'monitor/equipamento_violado', 'equipamentos_violados', 'nao', '94', 'web', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('monitoramento_de_contratos', 'monitor/monitor_contratos', 'monitor_contrato', 'nao', '94', 'web', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('tickets', 'monitoramento/tickets', 'monitoramento', 'nao', '94', 'web', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('gateways', 'Gateways/gtw', 'monitoramento', 'nao', '94', 'web', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `icone`, `status`) VALUES ('iscas', 'dashboard_iscas|equipamentos_iscas|relatorios_iscas|comandos_iscas', 'sim', 'track_changes', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('dashboard_iscas', 'iscas/isca/dashboard', 'dashboard_iscas|equipamentos_iscas|relatorios', 'nao', '102', 'track_changes', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('monitoramento_de_iscas', 'monitoramento_iscas', 'dashboard_iscas|equipamentos_iscas|relatorios', 'nao', '102', 'track_changes', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('agendamentos', 'iscas/isca/agendamentos', 'dashboard_iscas|equipamentos_iscas|relatorios', 'nao', '102', 'track_changes', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('equipamentos', 'equipamentos_iscas', 'sim', '102', 'gps_fixed', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('iscas_em_estoque', 'iscas/isca', 'equipamentos_iscas', 'nao', '106', 'gps_fixed', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('iscas_vinculadas', 'iscas/isca/listarIscasVinculadas', 'equipamentos_iscas', 'nao', '106', 'gps_fixed', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('relatorios', 'relatorios_iscas', 'sim', '102', 'track_changes', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('comandos', 'iscas/comandos_isca', 'relatorios_iscas', 'nao', '109', 'track_changes', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('iscas', 'iscas/isca/relatorioIscas', 'relatorios_iscas', 'nao', '109', 'track_changes', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('comandos', 'comandos_iscas', 'sim', '102', 'track_changes', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('envio_unico', 'iscas/comandos_isca/envio_comandos', 'comandos_iscas', 'nao', '112', 'track_changes', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('envio_em_massa', 'monitoramento_iscas/comandos_iscas', 'comandos_iscas', 'nao', '112', 'track_changes', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `icone`, `status`) VALUES ('omniscore', 'vis_visualizarperfisdeprofissionais', 'sim', 'score', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('consultas_realizadas', 'PerfisProfissionais', 'vis_visualizarperfisdeprofissionais', 'nao', '115', 'score', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('custos_consultas_omniscore', 'relatorios/custos_perfis_profissionais', 'vis_custosdosperfisdeprofissionais', 'nao', '115', 'score', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `filhos`, `icone`, `status`) VALUES ('suporte', 'sim', 'textsms', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('ordem_de_servicos', 'servico', 'downloads_os', 'nao', '118', 'textsms', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('ticket', 'webdesk', 'visualizar_tickets', 'nao', '118', 'textsms', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('sac_omnilink', 'PaineisOmnilinkSac', 'out_sacomnilink', 'nao', '118', 'textsms', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('painel_infobip', 'PaineisInfobip', 'vis_painelinfobip', 'nao', '118', 'textsms', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('logs', 'comandos_iscas', 'sim', '118', 'textsms', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('cadastro_de_veiculos', 'veiculos/log_veiculos', 'comandos_iscas', 'nao', '123', 'textsms', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('envio_sms', 'relatorios/sms', 'rel_envio_sms', 'nao', '123', 'textsms', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `icone`, `status`) VALUES ('configuracoes', 'configuracoes', 'sim', 'settings', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('mensagens_notificacoes', 'mensagem_notificacao', 'sim', '126', 'settings', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('sms', 'configuracoes/notificacoes/sms', 'mensagem_notificacao', 'nao', '127', 'settings', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `filhos`, `icone`, `status`) VALUES ('dashboards', 'sim', 'dashboard', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('painel_os', 'Dashboards/buscar_pagina_dashboard?id=1', 'vis_dashboardspainelos', 'nao', '129', 'dashboard', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('painel_call_center', 'Dashboards/buscar_pagina_dashboard?id=2', 'vis_dashboardspainelcallcenter', 'nao', '129', 'dashboard', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('estoque_terceiros', 'Dashboards/buscar_pagina_dashboard?id=3', 'vis_dashboardsestoquedeterceiros', 'nao', '129', 'dashboard', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('painel_vendas', 'Dashboards/buscar_pagina_dashboard?id=4', 'vis_dashboardspaineldevendas', 'nao', '129', 'dashboard', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('primeira_comunicacao', 'Dashboards/buscar_pagina_dashboard?id=5', 'vis_dashboardsprimeiracomunicacao', 'nao', '129', 'dashboard', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('prestadores_pso_e_rvo', 'Dashboards/buscar_pagina_dashboard?id=6', 'vis_dashboardspagamentoprestadores', 'nao', '129', 'dashboard', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('consulta_af_e_rede', 'Dashboards/buscar_pagina_dashboard?id=7', 'vis_consultaaferede', 'nao', '129', 'dashboard', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `status`) VALUES ('custo_faturamento_a52', 'Dashboards/buscar_pagina_dashboard?id=9', 'vis_custofaturamentoxcustoa52', 'nao', '129', 'dashboard', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'Financeiros/InformacoesGerais', 'vis_financeiroinformacaogeral', 'nao', '13', 'paid', '21', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'ComerciaisTelevendas/InformacoesGerais', 'vis_comercialetelevendasinformacaogeral', 'nao', '31', 'shopping_cart', '51', 'inativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'TecnologiasInformacoes/InformacoesGerais', 'vis_tecnologiadainformacaoinformacaogeral', 'nao', '29', 'devices', '41', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'Engenharias/InformacoesGerais', 'vis_engenhariainformacaogeral', 'nao', '31', 'engineering', '46', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'Marketings/InformacoesGerais', 'vis_marketinginformacaogeral', 'nao', '31', 'tungsten', '56', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'GovernacasCorporativas/InformacoesGerais', 'vis_governancacorporativainformacaogeral', 'nao', '35', 'corporate_fare', '71', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'Operacoes/InformacoesGerais', 'vis_operacoesinformacaogeral', 'nao', '31', 'device_hub', '61', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('informacoes_gerais', 'ControlesQualidades/InformacoesGerais', 'vis_controledequalidadeinformacaogeral', 'nao', '34', 'hd', '66', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('indicadores_sac', 'Dashboards/buscar_pagina_dashboard?id=8', 'vis_indicadoressac', 'nao', '129', 'dashboard', '194', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('apresentacoes', 'ComerciaisTelevendas/Apresentacoes', 'vis_comercialetelevendasapresentacoes', 'nao', '31', 'shopping_cart', '52', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('propostas', 'ComerciaisTelevendas/Propostas', 'vis_comercialetelevendaspropostas', 'nao', '31', 'shopping_cart', '53', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('folhetos', 'ComerciaisTelevendas/Folhetos', 'vis_comercialetelevendasfolhetos', 'nao', '31', 'shopping_cart', '54', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('guias', 'ComerciaisTelevendas/Guias', 'vis_comercialetelevendasguias', 'nao', '31', 'shopping_cart', '55', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('termo_adesao', 'licitacao', 'add_termo', 'nao', '31', 'shopping_cart', '56', 'ativo');
INSERT INTO `showtecsystem`.`menu` (`nome`, `caminho`, `codigo_permissao`, `filhos`, `id_pai`, `icone`, `ordem`, `status`) VALUES ('pedidos', 'ComerciaisTelevendas/Pedidos', 'vis_comercialetelevendasinformacaogeral', 'nao', '31', 'shopping_cart', '57', 'ativo');
