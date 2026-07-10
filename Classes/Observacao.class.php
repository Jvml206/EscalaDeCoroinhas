<?php

class Observacao extends CRUD
{
    protected $table = "Observacao";
    private $idObservacao;
    private $idCoroinhaFK;
    private $observacao;
    private $status;

    public function getIdObservacao()
    {
        return $this->idObservacao;
    }
    public function setIdObservacao($idObservacao)
    {
        $this->idObservacao = $idObservacao;
    }

    public function getIdCoroinhaFK()
    {
        return $this->idCoroinhaFK;
    }
    public function setIdCoroinhaFK($idCoroinhaFK)
    {
        $this->idCoroinhaFK = $idCoroinhaFK;
    }

    public function getObservacao()
    {
        return $this->observacao;
    }
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function add()
    {
        $sql = "INSERT INTO $this->table (idCoroinhaFK, observacao) 
            VALUES (:idCoroinhaFK, :observacao)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":idCoroinhaFK", $this->idCoroinhaFK, PDO::PARAM_INT);
        $stmt->bindParam(":observacao", $this->observacao, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table SET 
            idCoroinhaFK=:idCoroinhaFK,
            observacao=:observacao
            WHERE $campo=:idObservacao";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idCoroinhaFK', $this->idCoroinhaFK);
        $stmt->bindParam(':observacao', $this->observacao);
        $stmt->bindParam(':idObservacao', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getByCoroinha($idCoroinha)
    {
        $sql = "SELECT * FROM $this->table WHERE idCoroinhaFK = :idCoroinha";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idCoroinha', $idCoroinha, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function allObservacoesCoroinha($idCoroinhaFK)
    {
        $sql = "SELECT * FROM $this->table WHERE idCoroinhaFK = :idCoroinhaFK ORDER BY observacao ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idCoroinhaFK', $idCoroinhaFK, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateStatus(string $campo, int $id)
    {
        $sql = "UPDATE $this->table SET 
            status=:status
            WHERE $campo=:idObservacao";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':idObservacao', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}