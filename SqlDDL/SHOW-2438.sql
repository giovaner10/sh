INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
VALUES( 
  'cadastro_clientes_omnicom',
  'Omnicom/CadastroDeClientes',
  'vis_autorizacao_omnicom',
  'nao',
  322,
  'web',
  999,
  'ativo',
  'Cadastro de Clientes',
  'Customer Registration'
)

INSERT INTO showtecsystem.menu (nome, caminho, codigo_permissao, filhos, id_pai, icone, ordem, status, lang_pt, lang_en)
VALUES( 
  'omnicom',
  '',
  'vis_autorizacao_omnicom',
  'sim',
  NULL,
  'web',
  999,
  'ativo',
  'Omnicom',
  'Omnicom'
)

INSERT INTO showtecsystem.cad_permissoes_funcionarios (descricao, cod_permissao, status, data_cad, modulo)
VALUES ("Cadastro de Clientes Omnicom", "vis_autorizacao_omnicom",1,Now(),"Cadastros");