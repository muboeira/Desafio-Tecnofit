<?php

namespace Models;

class Exercicio extends BaseModel
{
    private int $id;
    private string $nome;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    public function setData($data): void
    {
        $this->setId($data['id'] ?? 0);
        $this->setNome($data['nome'] ?? '');
    }

    public function fetchAll(): array
    {

        return $this->select('SELECT * FROM exercicios ORDER BY id');

    }

    public function fetchById($id)
    {

        $results = $this->select('SELECT * FROM exercicios WHERE id = :ID', array(
            ':ID' => $id
        ));

        return $results[0];
    }

    public function fetchByTreinoId($treinoId) {

        $results = $this->select('SELECT * FROM treino_exercicios te INNER JOIN exercicios e ON e.id = te.exercicio_id WHERE te.treino_id = :ID', array(
            ':ID' => $treinoId
        ));

        return $results;
    }

    public function fetchAllNotInTreino($treinoId) {

        $results = $this->select('SELECT * FROM exercicios WHERE id NOT IN (SELECT exercicio_id from treino_exercicios WHERE treino_id = :ID)', array(
            ':ID' => $treinoId
        ));

        return $results;
    }

    public function addExercicioToTreino($treinoId, $exercicioId, $sessoes) {
        $result = $this->query(
            'INSERT INTO treino_exercicios (treino_id, exercicio_id, sessoes)
                        VALUES (:TREINO_ID, :EXERCICIO_ID, :SESSOES)',
            [
                ':TREINO_ID'  => $treinoId,
                ':EXERCICIO_ID'  => $exercicioId,
                ':SESSOES'  => $sessoes,
            ]);

        return $result;
    }

    public function deleteExercicioFromTreino($treinoId, $exercicioId) {
        $results = $this->query('DELETE FROM treino_exercicios 
                                                WHERE treino_id = :TREINO_ID 
                                                  AND exercicio_id = :EXERCICIO_ID',[
            ':TREINO_ID' => $treinoId,
            ':EXERCICIO_ID' => $exercicioId,
        ]);

        return $results > 0;
    }

    public function hasRelations($id)
    {
        $results = $this->select('SELECT count(*) as total FROM treino_exercicios WHERE exercicio_id = :ID', [
            ':ID' => $id
        ]);

        return ((int)$results[0]['total']) > 0;
    }

    public function save() {
        $this->query(
            'INSERT INTO exercicios (nome) VALUES (:NOME)',
            [
                ':NOME'  => $this->getNome(),
            ]);
    }

    public function update(){
        $this->query('UPDATE exercicios SET nome = :NOME WHERE id = :ID', [
            ':ID'=>$this->getId(),
            ':NOME' => $this->getNome()
        ]);
    }

    public function delete($id) {
        if($this->hasRelations($id)) {
            return false;
        }

        $results = $this->query('DELETE FROM exercicios WHERE id = :ID',[
            ':ID' => $id
        ]);

        return $results > 0;
    }
}