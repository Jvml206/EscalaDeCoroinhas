CREATE DATABASE IF NOT EXISTS escala_coroinhas;
USE escala_coroinhas;

DROP TABLE IF EXISTS Usuario;
CREATE TABLE IF NOT EXISTS Usuario (
    idUsuario INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nomeUsuario VARCHAR(255) NOT NULL,
    emailUsuario VARCHAR(100) NOT NULL UNIQUE,
    senhaUsuario VARCHAR(255) NOT NULL,
    nivelAcessoUsuario ENUM('1', '2', '3') NOT NULL # 1 - ADMIN ; 2 - COORDENADOR ; 3 - COORDENADOR DE OUTRA COM.
);

DROP TABLE IF EXISTS Coroinha;
CREATE TABLE IF NOT EXISTS Coroinha (
    idCoroinha INT AUTO_INCREMENT PRIMARY KEY,
    nomeCoroinha VARCHAR(100) NOT NULL,
    nivel ENUM('Nível 1','Nível 2','Acólito') NOT NULL,
    status ENUM('Servindo','Ex-coroinha') NOT NULL,
    preferenciaTurno ENUM('Manhã','Noite','Sem Preferência') NOT NULL,
    preferenciaDomingo VARCHAR(255) NOT NULL,
    podeSegunda ENUM('Sim','Não') NOT NULL,
    foto VARCHAR(500) NOT NULL,
    numeroServindo INT DEFAULT 0
);

DROP TABLE IF EXISTS Observacao;
CREATE TABLE IF NOT EXISTS Observacao (
    idObservacao INT AUTO_INCREMENT PRIMARY KEY,
    idCoroinhaFK INT NOT NULL,
    observacao TEXT NOT NULL,
    status ENUM('Em observação', 'Corrigida') DEFAULT ("Em observação")
);

DROP TABLE IF EXISTS Comunidade;
CREATE TABLE IF NOT EXISTS Comunidade (
    idComunidade INT AUTO_INCREMENT PRIMARY KEY,
    nomeComunidade VARCHAR(100) NOT NULL
);

DROP TABLE IF EXISTS Celebracao;
CREATE TABLE IF NOT EXISTS Celebracao (
    idCelebracao INT AUTO_INCREMENT PRIMARY KEY,
    semana VARCHAR(5) NOT NULL,            					-- 1,2,3,4,5
    diaSemana ENUM('Domingo','Segunda', 'Sexta') NOT NULL,
    turno ENUM('Manhã','Noite') NOT NULL,
    data date,
    idComunidadeFK INT,
    FOREIGN KEY (idComunidadeFK)
        REFERENCES comunidade(idComunidade)
);

DROP TABLE IF EXISTS Escala;
CREATE TABLE IF NOT EXISTS Escala (
    idEscala INT AUTO_INCREMENT PRIMARY KEY,
    idCelebracaoFK INT NOT NULL,
    idCoroinhaFK INT,
    idComunidadeFK INT,
    posicao INT NOT NULL,
    FOREIGN KEY (idCelebracaoFK)
        REFERENCES celebracao(idCelebracao)
        ON DELETE CASCADE,
    FOREIGN KEY (idCoroinhaFK)
        REFERENCES coroinha(idCoroinha)
        ON DELETE CASCADE,
	FOREIGN KEY (idComunidadeFK)
        REFERENCES comunidade(idComunidade)
        ON DELETE CASCADE
);

DROP TABLE IF EXISTS RecuperacaoSenha;
CREATE TABLE RecuperacaoSenha (
    idRecuperacaoSenha INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idUsuarioFK INT(11) NOT NULL,
    tokenRecuperacaoSenha VARCHAR(200) NOT NULL UNIQUE,
    expiraRecuperacaoSenha DATETIME NOT NULL,
    usadoRecuperacaoSenha TINYINT(1) DEFAULT 0,
    criadoRecuperacaoSenha DATETIME DEFAULT CURRENT_TIMESTAMP(),
    KEY idUsuarioFK (idUsuarioFK),
    CONSTRAINT recuperacao_senha_usuario FOREIGN KEY (idUsuarioFK) REFERENCES Usuario(idUsuario) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Calendario;
CREATE TABLE IF NOT EXISTS Calendario (
    idCalendario INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao VARCHAR(250) NOT NULL,
    dataInicio DATETIME NOT NULL,
    dataFim DATETIME NOT NULL,
    corDataCalendario VARCHAR(10) NOT NULL,
    local VARCHAR(100) NOT NULL
);

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `dashboard`()
BEGIN
    SELECT 
        -- Coroinha
        (SELECT COUNT(*) FROM Coroinha) AS totalCoroinhas,
        (SELECT COUNT(*) FROM Coroinha WHERE status = 'Servindo') AS coroinhasServindo,
        (SELECT COUNT(*) FROM Coroinha WHERE status = 'Ex-coroinha') AS exCoroinhas,
        (SELECT ROUND(AVG(numeroServindo),2) FROM Coroinha WHERE status = 'Servindo') AS mediaServindo,
        (SELECT COUNT(*) FROM Coroinha WHERE nivel = 'Nível 1') AS coroinhasNivel1,
        (SELECT COUNT(*) FROM Coroinha WHERE nivel = 'Nível 2') AS coroinhasNivel2,
        (SELECT COUNT(*) FROM Coroinha WHERE nivel = 'Acólito') AS coroinhasAcolitos,

        -- Comunidade
        (SELECT COUNT(*) FROM Comunidade) AS totalComunidades,

        -- Celebração
        (SELECT COUNT(*) FROM Celebracao) AS totalCelebracoes;
END$$
DELIMITER ;

select * from celebracao;
