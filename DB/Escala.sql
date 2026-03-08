CREATE DATABASE IF NOT EXISTS escala_coroinhas;
USE escala_coroinhas;

CREATE TABLE IF NOT EXISTS Usuario (
    idUsuario INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    emailUsuario VARCHAR(100) NOT NULL UNIQUE,
    senhaUsuario VARCHAR(255) NOT NULL,
    nivelAcessoUsuario INT(11) NOT NULL
);

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

CREATE TABLE IF NOT EXISTS Comunidade (
    idComunidade INT AUTO_INCREMENT PRIMARY KEY,
    nomeComunidade VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS Celebracao (
    idCelebracao INT AUTO_INCREMENT PRIMARY KEY,
    semana VARCHAR(5) NOT NULL,            					-- 1,2,3,4,5
    diaSemana ENUM('Domingo','Segunda') NOT NULL,
    turno ENUM('Manhã','Noite') NOT NULL,
    idComunidadeFK INT,
    FOREIGN KEY (idComunidadeFK)
        REFERENCES comunidade(idComunidade)
);

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

INSERT INTO Usuario (emailUsuario,senhaUsuario,nivelAcessoUsuario)values('lopesdrimachado2@gmail.com','$2y$10$hi.H/Qj3lC6bu6lZJ4LaFeyQR1TWtSuuBhjYwBF4Lu.2i8Q8hOVny',1); #senha: 123
