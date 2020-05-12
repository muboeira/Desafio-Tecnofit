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

    public function checkForRelation($id)
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
        if($this->checkForRelation($id)) {
            return false;
        }

        $results = $this->query('DELETE FROM exercicios WHERE id = :ID',[
            ':ID' => $id
        ]);

        return $results > 0;
    }
}