<?php

class Comunidade extends CRUD
{
    protected $table = "Comunidade";
    private $idComunidade;
    private $nomeComunidade;

    public function getIdComunidade()
    {
        return $this->idComunidade;
    }
    public function setIdComunidade($idComunidade)
    {
        $this->idComunidade = $idComunidade;
    }

    public function getNomeComunidade()
    {
        return $this->nomeComunidade;
    }
    public function setNomeComunidade($nomeComunidade)
    {
        $this->nomeComunidade = $nomeComunidade;
    }

    public function add()
    {
        $sql = "INSERT INTO $this->table (nomeComunidade) 
            VALUES (:nomeComunidade)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":nomeComunidade", $this->nomeComunidade, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table SET 
            nomeComunidade=:nomeComunidade
            WHERE $campo=:idComunidade";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nomeComunidade', $this->nomeComunidade);
        $stmt->bindParam(':idComunidade', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function allOrder($order = 'ASC'){
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC'; // segurança
        $sql = "SELECT * FROM $this->table ORDER BY nomeComunidade $order"; // ordena por nome
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}