#=============== CRIAR TABELAS E VIEW ====================================
CREATE TABLE `comandos_suntech` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `descricao` VARCHAR( 255 ) NOT NULL
  `codigo_modelo` INT NOT NULL
) ENGINE = MYISAM;

CREATE TABLE modelos_suntech(
  `codigo_modelo` INT NOT NULL,
  `fabricante` VARCHAR( 255 ) NOT NULL,
  `nome_modelo` VARCHAR( 255 ) NOT NULL,
) ENGINE = MYISAM;


CREATE 
VIEW `comandos_suntech_modelos` AS
    SELECT 
        `ms`.`codigo_modelo` AS `codigo_modelo`,
        `ms`.`fabricante` AS `fabricante`,
        `ms`.`nome_modelo` AS `nome_modelo`,
        `cs`.`descricao` AS `descricao`
    FROM
        (`modelos_suntech` `ms`
        JOIN `comandos_suntech` `cs` ON ((`ms`.`codigo_modelo` = `cs`.`codigo_modelo`)))


# =================== INSERT NAS TABELAS  =================

INSERT INTO comandos_suntech(`descricao`, `codigo_modelo`)
VALUES
	('BLOQUEIO',1),('DESBLOQUEIO', 1),('REBOOT', 1),
		('BLOQUEIO',2),('DESBLOQUEIO', 2),('REBOOT', 2),
			('BLOQUEIO',3),('DESBLOQUEIO', 3),('REBOOT', 3),
				('BLOQUEIO',4),('DESBLOQUEIO', 4),('REBOOT', 4),
					('HABANTIFURTO',4),('DESANTIFURTO', 4),('BLOQUEIO', 5),
						('DESBLOQUEIO',5),('REBOOT', 5),('REBOOT', 6),
							('ATIVARRF',6),('DESATIVARRF', 6),('REBOOT', 7),
								('REBOOT',8),('BLOQUEIO', 18),('DESBLOQUEIO', 18),
									('REBOOT',18),('ATIVARRF', 18),('DESATIVARRF', 18),
	('BLOQUEIO',17),('DESBLOQUEIO', 17),('REBOOT', 17),
	('HABANTIFURTO',17),('DESANTIFURTO', 17),('BLOQUEIO', 40),
	('DESBLOQUEIO',40),('REBOOT', 40),('REBOOT', 42),
	('BLOQUEIO',44),('DESBLOQUEIO', 44),('REBOOT', 44),
	('BLOQUEIO',45),('DESBLOQUEIO', 45),('REBOOT', 45),
	('REBOOT',46),('ATIVARRF', 46),('DESATIVARRF', 46),
								('REBOOT',47),('ATIVARRF', 47),('DESATIVARRF', 47),
									('REBOOT',48),('ATIVARRF', 48),('DESATIVARRF', 48),
										('REBOOT',49),('ATIVARRF', 49),('DESATIVARRF', 49),
											('REBOOT',50),('ATIVARRF', 50),('DESATIVARRF', 50),
												('REBOOT',51),('ATIVARRF', 51),('DESATIVARRF', 51)
	

INSERT INTO systems.modelos_suntech(`codigo_modelo`,`fabricante`,`nome_modelo`)
VALUES('1','SUNTECH','ST300RI'),
		('2','SUNTECH','ST340'),
			('3','SUNTECH','ST340LC(4pin)'),
				('4','SUNTECH','ST300HR'),
					('5','SUNTECH','ST350'),
						('6','SUNTECH','ST480'),
							('7','SUNTECH','ST500'),
								('8','SUNTECH','ST380'),
									('18','SUNTECH','ST390'),
			('17','SUNTECH','ST340RB'),
			('40','SUNTECH','ST310U'),
			('42','SUNTECH','ST360'),
			('44','SUNTECH','ST340U'),
									('45','SUNTECH','ST340UR'),	
										('46','SUNTECH','ST410'),
											('47','SUNTECH','ST410 G'),
												('48','SUNTECH','ST419 NG'),
													('49','SUNTECH','ST440'),
														('50','SUNTECH','ST499'),
															('51','SUNTECH','ST489');