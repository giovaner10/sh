SET @comercial_televendas_pedidos_id = (select ID from showtecsystem.menu where caminho  = 'ComerciaisTelevendas/Pedidos')
UPDATE showtecsystem.menu SET codigo_permissao = 'pedidos' WHERE id = @comercial_televendas_pedidos_id