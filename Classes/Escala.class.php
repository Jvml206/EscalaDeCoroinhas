<?php

class Escala extends CRUD{
    protected $table = "Escala";
    private $idEscala;
    private $idCelebracaoFK;
    private $idCoroinhaFK;
    private $posicao;
    
    public function getIdEscala() {
        return $this->idEscala;
    }
    public function setIdEscala($idEscala) {
        $this->idEscala = $idEscala;
    }

    public function getIdCelebracaoFK() {
        return $this->idCelebracaoFK;
    }
    public function setIdCelebracaoFK($idCelebracaoFK) {
        $this->idCelebracaoFK = $idCelebracaoFK;
    }

    public function getIdCoroinhaFK() {
        return $this->idCoroinhaFK;
    }
    public function setIdCoroinhaFK($idCoroinhaFK) {
        $this->idCoroinhaFK = $idCoroinhaFK;
    }

    public function getPosicao() {
        return $this->posicao;
    }
    public function setPosicao($posicao) {
        $this->posicao = $posicao;
    }

    public function add(){
        $sql = "INSERT INTO $this->table (idCelebracaoFK, idCoroinhaFK, idComunidadeFK, posicao) 
            VALUES (:idCelebracaoFK, :idCoroinhaFK, :idComunidadeFK, :posicao)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":idCelebracaoFK", $this->idCelebracaoFK, PDO::PARAM_STR);
        $stmt->bindParam(":idCoroinhaFK", $this->idCoroinhaFK, PDO::PARAM_STR);
        $stmt->bindParam(":posicao", $this->posicao, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function update(string $campo, int $id){
        $sql = "UPDATE $this->table SET 
            idCelebracaoFK=:idCelebracaoFK, 
            idCoroinhaFK=:idCoroinhaFK,
            idComunidadeFK=:idComunidadeFK,
            posicao=:posicao
            WHERE $campo=:idEscala";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idCelebracaoFK', $this->idCelebracaoFK);
        $stmt->bindParam(':idCoroinhaFK', $this->idCoroinhaFK);
        $stmt->bindParam(':idEscala', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}