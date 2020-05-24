<?php


namespace Models;

class Treino extends BaseModel
{
    private int $id;
    private string $nome;
    private int $usuario_id;
    private bool $ativado;

    /**
     * @return bool
     */
    public function getAtivado(): bool
    {
        return $this->ativado;
    }

    /**
     * @param bool $ativado
     */
    public function setAtivado(bool $ativado): void
    {
        $this->ativado = $ativado;
    }

    /**
     * @return int
     */
    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    /**
     * @param int $usuario_id
     */
    public function setUsuarioId(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function fetchAll(): array
    {

        return $this->select('SELECT * FROM treinos ORDER BY id');

    }

    public function fetchById($id)
    {

        $results = $this->select('SELECT * FROM treinos WHERE id = :ID', array(
            ':ID' => $id
        ));

        return $results[0];
    }

    public function setData($data): void
    {
        $this->setId($data['id'] ?? 0);
        $this->setNome($data['nome'] ?? '');
        $this->setUsuarioId($data['usuario_id'] ?? 0);
        $this->setAtivado($data['ativado'] ?? false);
    }

    public function hasRelations($id)
    {
        $results = $this->select('SELECT count(*) as total FROM treino_exercicios WHERE treino_id = :ID', [
            ':ID' => $id
        ]);

        return ((int)$results[0]['total']) > 0;
    }

    public function save() {
        $this->query(
            'INSERT INTO treinos (nome, ativado, usuario_id) VALUES (:NOME, :ATIVADO, :USUARIO_ID)',
            [
                ':NOME'  => $this->getNome(),
                ':ATIVADO' => $this->getAtivado(),
                ':USUARIO_ID' => $this->getUsuarioId()
            ]);
    }

    public function update(){
        $this->query('UPDATE treinos SET nome = :NOME, ativado = :ATIVADO, usuario_id = :USUARIO_ID WHERE id = :ID', [
            ':ID'=>$this->getId(),
            ':NOME' => $this->getNome(),
            ':ATIVADO' => $this->getAtivado(),
            ':USUARIO_ID' => $this->getUsuarioId()
        ]);
    }

    public function delete($id) {
        if($this->hasRelations($id)) {
            return false;
        }

        $results = $this->query('DELETE FROM treinos WHERE id = :ID',[
            ':ID' => $id
        ]);

        return $results > 0;
    }
}