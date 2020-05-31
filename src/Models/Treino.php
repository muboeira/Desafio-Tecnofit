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

        $usuarioId = $this->getUsuarioId();

        if($this->getTreinoByName($this->getNome())) {
            return ['data' => false, 'message'=> 'Nome já existe'];
        }

        if($this->getAtivado() && $this->usuarioHasTreinoByStatus($usuarioId, true)){
            $this->updateAtivadoByUser($usuarioId, false);
        }

        $this->query(
            'INSERT INTO treinos (nome, ativado, usuario_id) VALUES (:NOME, :ATIVADO, :USUARIO_ID)',
            [
                ':NOME'  => $this->getNome(),
                ':ATIVADO' => $this->getAtivado(),
                ':USUARIO_ID' => $usuarioId
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
            $this->query('DELETE FROM treino_exercicios WHERE treino_id = :ID',[
                ':ID' => $id
            ]);
        }

        $results = $this->query('DELETE FROM treinos WHERE id = :ID',[
            ':ID' => $id
        ]);

        return $results > 0;
    }

    public function usuarioHasTreinoByStatus(int $usuarioId, bool $ativado = true)
    {
        $results = $this->select('SELECT count(*) as total FROM treinos WHERE usuario_id = :USUARIO_ID AND ATIVADO = :ATIVADO',[
            ':USUARIO_ID' => $usuarioId,
            ':ATIVADO' => $ativado
        ]);

        return ((int)$results[0]['total']) > 0;
    }

    public function getTreinoAtivoByUsuarioId(int $usuarioId)
    {
        $result = $this->select('SELECT * FROM treinos WHERE usuario_id = :USUARIO_ID AND ativado = true',[
            ':USUARIO_ID' => $usuarioId,
        ]);

        if(count($result) > 0) {
            $this->setData($result[0]);
            return true;
        }

        return false;
    }

    public function getTreinoByName(string $nome)
    {
        $results = $this->select('SELECT count(*) as total FROM treinos WHERE nome = :NOME',[
            ':NOME' => $nome,
        ]);

        return ((int)$results[0]['total']) > 0;
    }

    public function updateAtivadoByUser(int $usuarioId, bool $ativado)
    {
        $bool = $ativado ? 1 : 0;

        return $this->query('UPDATE treinos SET ativado = :ATIVADO WHERE usuario_id = :USUARIO_ID',[
            ':ATIVADO' => $bool,
            ':USUARIO_ID' => $usuarioId,
        ]);
    }

    public function updateAtivadoById(int $treinoId, bool $ativado)
    {
        $bool = $ativado ? 1 : 0;

        if($ativado) {
            $treino = $this->fetchById($treinoId);

            $this->updateAtivadoByUser($treino['usuario_id'], false);
        }

        return $this->query('UPDATE treinos SET ativado = :ATIVADO WHERE id = :ID',[
            ':ATIVADO' => $bool,
            ':ID' => $treinoId,
        ]);
    }
}