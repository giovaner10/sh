CREATE TABLE showtecsystem.auditoria_acesso_furtivo_gestor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_acessado INT NOT NULL,
    usuario_logado INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)