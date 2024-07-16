CREATE TABLE showtecsystem.dashboards (
	id int auto_increment NOT NULL,
	titulo varchar(70) NOT NULL,
	link_bi text NOT NULL,
	criado_em datetime NOT NULL,
	modificado_em datetime NOT NULL,
	criado_por varchar(100) NOT NULL,
	modificado_por varchar(100) NOT NULL,
	permissao varchar(100) NULL,
	ativo boolean DEFAULT false NOT NULL,
	CONSTRAINT dashboards_PK PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

INSERT INTO showtecsystem.cad_permissoes_funcionarios
(id, descricao, cod_permissao, status, data_cad, modulo)
VALUES(594, 'Cadastro Dashboard', 'cad_cadastrodashboard', '1', '2022-05-10 12:43:33', 'Dashboards');

INSERT INTO showtecsystem.dashboards
(id, titulo, link_bi, criado_em, modificado_em, criado_por, modificado_por, permissao, ativo)
VALUES(1, 'Painel OS', 'https://app.powerbi.com/view?r=eyJrIjoiZjhhZTYxZjctNjA3OC00ZTQwLWEzNGQtZDI5N2I3ZGEzMDQ0IiwidCI6IjE0ODAzODRlLTllMzYtNDJiYy1hNmVhLTJhMjVkZjhhZmIwZCJ9&pageName=ReportSection', '2022-05-10 14:35:27', '2022-05-10 14:35:27', 'lucas.cavalcanti@showtecnologia.com', 'lucas.cavalcanti@showtecnologia.com', 'vis_dashboardspainelos', 1);
INSERT INTO showtecsystem.dashboards
(id, titulo, link_bi, criado_em, modificado_em, criado_por, modificado_por, permissao, ativo)
VALUES(2, 'Painel CallCenter', 'https://app.powerbi.com/view?r=eyJrIjoiOTkwZmZmOWItNGQyMy00Mzk3LWJlNzMtZmFmNzc5MTA5NjA4IiwidCI6IjE0ODAzODRlLTllMzYtNDJiYy1hNmVhLTJhMjVkZjhhZmIwZCJ9&pageName=ReportSectiondea28b9277d8ce409b04', '2022-05-10 14:39:11', '2022-05-10 14:39:11', 'lucas.cavalcanti@showtecnologia.com', 'lucas.cavalcanti@showtecnologia.com', 'vis_dashboardspainelcallcenter', 1);
INSERT INTO showtecsystem.dashboards
(id, titulo, link_bi, criado_em, modificado_em, criado_por, modificado_por, permissao, ativo)
VALUES(3, 'Estoque de Terceiros', 'https://app.powerbi.com/view?r=eyJrIjoiMmIwYzcxNWMtNGQwMC00NjE1LWIxNDItMzUxNGFhMjA0YzFiIiwidCI6IjE0ODAzODRlLTllMzYtNDJiYy1hNmVhLTJhMjVkZjhhZmIwZCJ9&pageName=ReportSection71cc995ee5d7300ba296', '2022-05-10 14:40:53', '2022-05-10 14:40:53', 'lucas.cavalcanti@showtecnologia.com', 'lucas.cavalcanti@showtecnologia.com', 'vis_dashboardsestoquedeterceiros', 1);
INSERT INTO showtecsystem.dashboards
(id, titulo, link_bi, criado_em, modificado_em, criado_por, modificado_por, permissao, ativo)
VALUES(4, 'Painel de Vendas', 'https://app.powerbi.com/view?r=eyJrIjoiZTNmOWZmODQtY2Y5OC00ZjNiLWJkZWQtNTIxNmFlMGVmY2Y1IiwidCI6IjE0ODAzODRlLTllMzYtNDJiYy1hNmVhLTJhMjVkZjhhZmIwZCJ9&pageName=ReportSection', '2022-05-10 14:44:15', '2022-05-10 14:44:15', 'lucas.cavalcanti@showtecnologia.com', 'lucas.cavalcanti@showtecnologia.com', 'vis_dashboardspaineldevendas', 1);
INSERT INTO showtecsystem.dashboards
(id, titulo, link_bi, criado_em, modificado_em, criado_por, modificado_por, permissao, ativo)
VALUES(5, 'Primeira Comunicação', 'https://app.powerbi.com/view?r=eyJrIjoiZGM2MTNjYTgtZDM5OS00NjkwLWE3NjItNGI4NDNlM2QxZmQzIiwidCI6IjE0ODAzODRlLTllMzYtNDJiYy1hNmVhLTJhMjVkZjhhZmIwZCJ9&pageName=ReportSection', '2022-05-10 14:45:42', '2022-05-10 14:45:42', 'lucas.cavalcanti@showtecnologia.com', 'lucas.cavalcanti@showtecnologia.com', 'vis_dashboardsprimeiracomunicacao', 1);
INSERT INTO showtecsystem.dashboards
(id, titulo, link_bi, criado_em, modificado_em, criado_por, modificado_por, permissao, ativo)
VALUES(6, 'Prestadores - PSO e RVO', 'https://app.powerbi.com/view?r=eyJrIjoiZmQzYTM5ZjUtYTkyZC00NzBiLTk5YWItMWJhNjg2NWEyMGFhIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9&pageName=ReportSection', '2022-05-10 14:47:19', '2022-05-10 14:47:19', 'lucas.cavalcanti@showtecnologia.com', 'lucas.cavalcanti@showtecnologia.com', 'vis_dashboardspagamentoprestadores', 1);
INSERT INTO showtecsystem.dashboards
(id, titulo, link_bi, criado_em, modificado_em, criado_por, modificado_por, permissao, ativo)
VALUES(7, 'Consulta AF e Rede', 'https://app.powerbi.com/view?r=eyJrIjoiNDYxNzZjMTgtZDUxZC00NDM5LTliYmUtMzk3YTJkZTY5Mjg5IiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9&pageName=ReportSectionbfe6d0f5329053507f61', '2022-05-10 14:50:03', '2022-05-10 15:11:51', 'lucas.cavalcanti@showtecnologia.com', 'lucas.cavalcanti@showtecnologia.com', 'vis_consultaaferede', 1);
INSERT INTO showtecsystem.dashboards
(id, titulo, link_bi, criado_em, modificado_em, criado_por, modificado_por, permissao, ativo)
VALUES(8, 'Indicadores SAC', 'https://app.powerbi.com/view?r=eyJrIjoiY2ZkNWU1N2YtNTc4NS00Zjg5LTgxZGYtZjBkZTgxOTUxYjZmIiwidCI6ImFiODlkOGJjLTFlZjQtNGU3ZC1iMGNlLWM0M2ZiZmRmYWNlMyJ9&pageName=ReportSection62a733409b93b952ae68', '2022-05-10 15:22:53', '2022-05-10 15:22:53', 'lucas.cavalcanti@showtecnologia.com', 'lucas.cavalcanti@showtecnologia.com', '', 1);
INSERT INTO showtecsystem.dashboards
(id, titulo, link_bi, criado_em, modificado_em, criado_por, modificado_por, permissao, ativo)
VALUES(9, 'Custo faturamento x custo a52', 'https://app.powerbi.com/view?r=eyJrIjoiZjNmOTY1NDAtZGYwMC00YjIyLTgxMmQtYzNmZDZiNjZjNzg0IiwidCI6IjE0ODAzODRlLTllMzYtNDJiYy1hNmVhLTJhMjVkZjhhZmIwZCJ9', '2022-05-10 15:24:16', '2022-05-10 15:24:16', 'lucas.cavalcanti@showtecnologia.com', 'lucas.cavalcanti@showtecnologia.com', 'vis_custofaturamentoxcustoa52', 1);
