<?php

namespace Models;

class Usuario extends BaseModel {
    private int $id;
    private string $nome;
    private string $login;
    private string $senha;
    private string $role;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin($login): void
    {
        $this->login = $login;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha($senha): void
    {
        $this->senha = $senha;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole($role): void
    {
        $this->role = $role;
    }

    public function setData($data): void
    {
        $this->setId($data['id'] ?? 0);
        $this->setLogin($data['login'] ?? '');
        $this->setNome($data['nome'] ?? '');
        $this->setSenha($data['senha'] ?? '');
        $this->setRole($data['role'] ?? '');
    }

    public function fetchById($id){

        $results = $this->select('SELECT * FROM usuarios WHERE id = :ID', array(
            ':ID' =>$id
        ));

        return $results[0];
    }


    public function fetchAll(){

        return $this->select('SELECT * FROM usuarios;');

    }

    public function logar($login, $senha){

        $results = $this->select('SELECT * FROM usuarios WHERE login = :login AND senha = :senha', [
            ':login' => $login, ':senha' => $senha
        ]);

        if(count($results) > 0) {

            $this->setData($results[0]);
            return true;

        }

        return false;
    }

    public function save() {
    $login =$this->getLogin();
    $nome  = $this->getNome();
    $senha =$this->getSenha();
    $role  = $this->getRole();


    $this->query(
        'INSERT INTO usuarios (login, nome, senha, role) 
                     VALUES (:LOGIN, :NOME, :SENHA, :ROLE)',
        [
            ':LOGIN' => $login,
            ':NOME'  => $nome,
            ':SENHA' => $senha,
            ':ROLE'  => $role,
        ]);
}

    public function update(): void
    {

        $this->query('UPDATE usuarios SET login = :LOGIN, senha = :PASSWORD, nome = :NOME  WHERE id = :ID', array(
            ':ID'=>$this->getId(),
            ':LOGIN'=>$this->getLogin(),
            ':PASSWORD'=>$this->getSenha(),
            ':NOME' => $this->getNome()
        ));

    }

    public function delete($id) {
        return $this->query('DELETE FROM usuarios WHERE id = :ID', array(
            ':ID' =>$id
        ));
    }

    public function hasRelations($id)
    {
        $results = $this->select('SELECT count(*) as total FROM treinos WHERE usuario_id = :ID', [
            ':ID' => $id
        ]);

        return ((int)$results[0]['total']) > 0;
    }
}