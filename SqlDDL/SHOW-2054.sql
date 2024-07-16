
UPDATE showtecsystem.menu SET caminho = 'PaineisOmnilink' WHERE nome = 'suporte_omnilink'

UPDATE showtecsystem.cad_permissoes_funcionarios SET descricao = 'Suporte Omnilink', cod_permissao = 'out_omnilink' WHERE descricao = 'SAC Omnilink'

UPDATE showtecsystem.cad_permissoes_funcionarios SET descricao = 'Auditoria Omnilink', cod_permissao = 'vis_auditoriaomnilink' WHERE descricao = 'Auditoria SAC Omnilink'

UPDATE showtecsystem.cad_permissoes_funcionarios SET descricao = 'Alterar Informações Itens de Contrato Om', cod_permissao = 'out_alterarInfoItensContratoOmnilink' WHERE descricao = 'Alterar Informações Itens de Contrato (SAC Om'

UPDATE showtecsystem.cad_permissoes_funcionarios SET descricao = 'editar suporte', cod_permissao = 'edi_editarsuporte' WHERE descricao = 'editar sac'

UPDATE showtecsystem.cad_permissoes_funcionarios SET descricao = 'Alterar Cadastro de Cliente', cod_permissao = 'edi_alterarcadastrodecliente' WHERE descricao = 'Alterar Cadastro de Cliente SAC'

UPDATE showtecsystem.cad_permissoes_funcionarios SET descricao = 'Liderança Suporte', cod_permissao = 'out_lideranca' WHERE descricao = 'Liderança SAC'

UPDATE showtecsystem.cad_permissoes_funcionarios SET descricao = 'Indicadores Suporte', cod_permissao = 'vis_indicadores' WHERE descricao = 'Indicadores SAC'

UPDATE showtecsystem.cad_permissoes_funcionarios SET descricao = 'Alteração OS (Suporte)', cod_permissao = 'edi_alteracaoos' WHERE descricao = 'Alteração OS (SAC)'

UPDATE showtecsystem.usuario SET permissoes = REPLACE(permissoes, 's:15:"out_omnilink"', 's:12:"out_omnilink"') WHERE permissoes like '%out_sacomnilink%';
UPDATE showtecsystem.usuario SET permissoes = REPLACE(permissoes, 's:24:"vis_auditoriasacomnilink"', 's:21:"vis_auditoriaomnilink"') WHERE permissoes like '%vis_auditoriasacomnilink%';
UPDATE showtecsystem.usuario SET permissoes = REPLACE(permissoes, 's:39:"out_alterarInfoItensContratoSacOmnilink"', 's:36:"out_alterarInfoItensContratoOmnilink"') WHERE permissoes like '%out_alterarInfoItensContratoSacOmnilink%';
UPDATE showtecsystem.usuario SET permissoes = REPLACE(permissoes, 's:13:"edi_editarsac"', 's:17:"edi_editarsuporte"') WHERE permissoes like '%edi_editarsac%';
UPDATE showtecsystem.usuario SET permissoes = REPLACE(permissoes, 's:31:"edi_alterarcadastrodeclientesac"', 's:28:"edi_alterarcadastrodecliente"') WHERE permissoes like '%edi_alterarcadastrodeclientesac%';
UPDATE showtecsystem.usuario SET permissoes = REPLACE(permissoes, 's:16:"out_liderancasac"', 's:13:"out_lideranca"') WHERE permissoes like '%out_liderancasac%';
UPDATE showtecsystem.usuario SET permissoes = REPLACE(permissoes, 's:18:"vis_indicadoressac"', 's:15:"vis_indicadores"') WHERE permissoes like '%vis_indicadoressac%';
UPDATE showtecsystem.usuario SET permissoes = REPLACE(permissoes, 's:18:"edi_alteracaoossac"', 's:15:"edi_alteracaoos"') WHERE permissoes like '%edi_alteracaoossac%';

UPDATE showtecsystem.menu SET nome = 'Indicadores Suporte' where nome = 'Indicadores SAC'
UPDATE showtecsystem.menu SET codigo_permissao = 'out_omnilink' where codigo_permissao = 'out_sacomnilink'
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_auditoriaomnilink' where codigo_permissao = 'vis_auditoriasacomnilink'
UPDATE showtecsystem.menu SET codigo_permissao = 'out_alterarInfoItensContratoOmnilink' where codigo_permissao = 'out_alterarInfoItensContratoSacOmnilink'
UPDATE showtecsystem.menu SET codigo_permissao = 'edi_editarsuporte' where codigo_permissao = 'edi_editarsac'
UPDATE showtecsystem.menu SET codigo_permissao = 'edi_alterarcadastrodecliente' where codigo_permissao = 'edi_alterarcadastrodeclientesac'
UPDATE showtecsystem.menu SET codigo_permissao = 'out_lideranca' where codigo_permissao = 'out_liderancasac'
UPDATE showtecsystem.menu SET codigo_permissao = 'vis_indicadores' where codigo_permissao = 'vis_indicadoressac'
UPDATE showtecsystem.menu SET codigo_permissao = 'edi_alteracaoos' where codigo_permissao = 'edi_alteracaoossac'

