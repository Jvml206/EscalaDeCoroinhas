<?php

class Celebracao extends CRUD
{
    protected $table = "Celebracao";
    private $idCelebracao;
    private $semana;
    private $diaSemana;
    private $turno;
    private $data;
    private $idComunidadeFK;

    public function getIdCelebracao()
    {
        return $this->idCelebracao;
    }
    public function setIdCelebracao($idCelebracao)
    {
        $this->idCelebracao = $idCelebracao;
    }

    public function getSemana()
    {
        return $this->semana;
    }
    public function setSemana($semana)
    {
        $this->semana = $semana;
    }

    public function getDiaSemana()
    {
        return $this->diaSemana;
    }
    public function setDiaSemana($diaSemana)
    {
        $this->diaSemana = $diaSemana;
    }

    public function getTurno()
    {
        return $this->turno;
    }
    public function setTurno($turno)
    {
        $this->turno = $turno;
    }

    public function getData()
    {
        return $this->data;
    }
    public function setData($data)
    {
        $this->data = $data;
    }

    public function getIdComunidadeFK()
    {
        return $this->idComunidadeFK;
    }
    public function setIdComunidadeFK($idComunidadeFK)
    {
        $this->idComunidadeFK = $idComunidadeFK;
    }

    public function add()
    {
        $sql = "INSERT INTO $this->table (semana, diaSemana, turno, data, idComunidadeFK) 
            VALUES (:semana, :diaSemana, :turno, :data, :idComunidadeFK)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":semana", $this->semana, PDO::PARAM_STR);
        $stmt->bindParam(":diaSemana", $this->diaSemana, PDO::PARAM_STR);
        $stmt->bindParam(":turno", $this->turno, PDO::PARAM_STR);
        $stmt->bindParam(":data", $this->data, PDO::PARAM_STR);
        $stmt->bindParam(":idComunidadeFK", $this->idComunidadeFK, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table SET 
            semana=:semana, 
            diaSemana=:diaSemana,
            turno=:turno,
            data=:data,
            idComunidadeFK=:idComunidadeFK
            WHERE $campo=:idCelebracao";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':semana', $this->semana);
        $stmt->bindParam(':diaSemana', $this->diaSemana);
        $stmt->bindParam(':turno', $this->turno);
        $stmt->bindParam(':data', $this->data);
        $stmt->bindParam(':idComunidadeFK', $this->idComunidadeFK);
        $stmt->bindParam(':idCelebracao', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getCelebracoesSexta(){
        $sql = "SELECT COUNT(idCelebracao) as total FROM $this->table WHERE diaSemana = 'Sexta' AND data >= CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchSexta(){
    $sql = "SELECT * FROM $this->table 
            WHERE diaSemana = 'Sexta' AND data >= CURDATE()
            ORDER BY data ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}