CREATE DATABASE tecnofit;

USE tecnofit;

CREATE TABLE usuarios(
                         `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                         `login` varchar(80) UNIQUE COLLATE utf8_general_ci NOT NULL,
                         `nome` varchar(80) COLLATE utf8_general_ci NOT NULL,
                         `senha` varchar(50) COLLATE utf8_general_ci NOT NULL,
                         `role` varchar(80) COLLATE utf8_general_ci DEFAULT 'user'
);


CREATE TABLE exercicios(
                           `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                           `nome` varchar(255) UNIQUE COLLATE utf8_general_ci NOT NULL
);

CREATE TABLE treinos(
                        `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        `nome` varchar(255) COLLATE utf8_general_ci NOT NULL,
                        `ativado` boolean,
                        `usuario_id` int(11) UNSIGNED,
                        CONSTRAINT FK_UsuarioTreino FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE treino_exercicios(
                                  `treino_id` int(11) UNSIGNED,
                                  `exercicio_id` int(11) UNSIGNED,
                                  `sessoes` int(11),
                                  CONSTRAINT FK_TreinoExercicio FOREIGN KEY (treino_id) REFERENCES treinos(id),
                                  CONSTRAINT FK_ExercicioTreino FOREIGN KEY (exercicio_id) REFERENCES exercicios(id),
                                  PRIMARY KEY (`treino_id`, `exercicio_id`)
);

INSERT INTO usuarios(login, nome, senha, role) VALUES ('admin', 'Admin', 'pass', 'admin'), ('user1', 'João Y', 'pass1', 'user'),
                                                ('user2', 'João Z', 'pass2', 'user');

INSERT exercicios(nome) VALUES ('abdominal'),('biceps'),('costas'),('pernas'),('peito');

INSERT treinos(nome, usuario_id, ativado) VALUES ('Treino X', 1 , true),('Treino :)', 2 , true),('Treino Adaptação', 3 , false);

INSERT treino_exercicios(treino_id, exercicio_id, sessoes) VALUES (1, 1, 10), (1, 2, 12), (1, 3, 12), (1, 4, 15), (1, 5, 10), (2, 1, 10), (2, 2, 17), (3, 5, 10);