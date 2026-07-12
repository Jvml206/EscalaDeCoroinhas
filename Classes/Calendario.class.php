<?php

class Calendario extends CRUD
{
    protected $table = "Calendario";
    private $idCalendario;
    private $titulo;
    private $descricao;
    private $dataInicio;
    private $dataFim;
    private $corDataCalendario;
    private $local;

    public function getIdCalendario()
    {
        return $this->idCalendario;
    }
    public function setIdCalendario($idCalendario)
    {
        $this->idCalendario = $idCalendario;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getDataInicio()
    {
        return $this->dataInicio;
    }
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }

    public function getDataFim()
    {
        return $this->dataFim;
    }
    public function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;
    }

    public function getCorDataCalendario()
    {
        return $this->corDataCalendario;
    }
    public function setCorDataCalendario($corDataCalendario)
    {
        $this->corDataCalendario = $corDataCalendario;
    }
    public function getLocal()
    {
        return $this->local;
    }
    public function setLocal($local)
    {
        $this->local = $local;
    }

    public function add()
    {
        $sql = "INSERT INTO $this->table (titulo, descricao, dataInicio, dataFim, corDataCalendario, local) 
            VALUES (:titulo, :descricao, :dataInicio, :dataFim, :corDataCalendario, :local)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":titulo", $this->titulo, PDO::PARAM_STR);
        $stmt->bindParam(":descricao", $this->descricao, PDO::PARAM_STR);
        $stmt->bindParam(":dataInicio", $this->dataInicio, PDO::PARAM_STR);
        $stmt->bindParam(":dataFim", $this->dataFim, PDO::PARAM_STR);
        $stmt->bindParam(":corDataCalendario", $this->corDataCalendario, PDO::PARAM_STR);
        $stmt->bindParam(":local", $this->local, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table SET 
            titulo=:titulo, 
            descricao=:descricao,
            dataInicio=:dataInicio,
            dataFim=:dataFim,
            corDataCalendario=:corDataCalendario,
            local=:local
            WHERE $campo=:idCalendario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':dataInicio', $this->dataInicio);
        $stmt->bindParam(':dataFim', $this->dataFim);
        $stmt->bindParam(':corDataCalendario', $this->corDataCalendario);
        $stmt->bindParam(':local', $this->local);
        $stmt->bindParam(':idCalendario', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}