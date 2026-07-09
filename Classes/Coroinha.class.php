<?php

class Coroinha extends CRUD
{
    protected $table = "Coroinha";
    private $idCoroinha;
    private $nomeCoroinha;
    private $nivel;
    private $status;
    private $preferenciaTurno;
    private $preferenciaDomingo;
    private $podeSegunda;
    private $numeroServindo;
    private $foto;

    public function getIdCoroinha()
    {
        return $this->idCoroinha;
    }
    public function setIdCoroinha($idCoroinha)
    {
        $this->idCoroinha = $idCoroinha;
    }

    public function getNomeCoroinha()
    {
        return $this->nomeCoroinha;
    }
    public function setNomeCoroinha($nomeCoroinha)
    {
        $this->nomeCoroinha = $nomeCoroinha;
    }

    public function getNivel()
    {
        return $this->nivel;
    }
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
    }

    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getPreferenciaTurno()
    {
        return $this->preferenciaTurno;
    }
    public function setPreferenciaTurno($preferenciaTurno)
    {
        $this->preferenciaTurno = $preferenciaTurno;
    }

    public function getPreferenciaDomingo()
    {
        return $this->preferenciaDomingo;
    }
    public function setPreferenciaDomingo($preferenciaDomingo)
    {
        $this->preferenciaDomingo = $preferenciaDomingo;
    }

    public function getPodeSegunda()
    {
        return $this->podeSegunda;
    }
    public function setPodeSegunda($podeSegunda)
    {
        $this->podeSegunda = $podeSegunda;
    }

    public function getNumeroServindo()
    {
        return $this->numeroServindo;
    }
    public function setNumeroServindo($numeroServindo)
    {
        $this->numeroServindo = $numeroServindo;
    }

    public function getFoto()
    {
        return $this->foto;
    }
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    public function add()
    {
        $sql = "INSERT INTO $this->table (nomeCoroinha, nivel, status, preferenciaTurno, preferenciaDomingo, podeSegunda, numeroServindo, foto) 
            VALUES (:nomeCoroinha, :nivel, :status, :preferenciaTurno, :preferenciaDomingo, :podeSegunda, :numeroServindo, :foto)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":nomeCoroinha", $this->nomeCoroinha, PDO::PARAM_STR);
        $stmt->bindParam(":nivel", $this->nivel, PDO::PARAM_STR);
        $stmt->bindParam(":status", $this->status, PDO::PARAM_STR);
        $stmt->bindParam(":preferenciaTurno", $this->preferenciaTurno, PDO::PARAM_STR);
        $stmt->bindParam(":preferenciaDomingo", $this->preferenciaDomingo, PDO::PARAM_STR);
        $stmt->bindParam(":podeSegunda", $this->podeSegunda, PDO::PARAM_STR);
        $stmt->bindParam(":numeroServindo", $this->numeroServindo, PDO::PARAM_INT);
        $stmt->bindParam(":foto", $this->foto, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table SET 
            nomeCoroinha=:nomeCoroinha, 
            nivel=:nivel,
            status=:status,
            preferenciaTurno=:preferenciaTurno,
            preferenciaDomingo=:preferenciaDomingo,
            podeSegunda=:podeSegunda,
            numeroServindo=:numeroServindo,
            foto=:foto
            WHERE $campo=:idCoroinha";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nomeCoroinha', $this->nomeCoroinha);
        $stmt->bindParam(':nivel', $this->nivel);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':preferenciaTurno', $this->preferenciaTurno);
        $stmt->bindParam(':preferenciaDomingo', $this->preferenciaDomingo);
        $stmt->bindParam(':podeSegunda', $this->podeSegunda);
        $stmt->bindParam(':numeroServindo', $this->numeroServindo);
        $stmt->bindParam(':foto', $this->foto);
        $stmt->bindParam(':idCoroinha', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateStatus(int $id)
    {
        $cor = $this->search('idCoroinha', $id);
        $status = ($cor->status === "Servindo") ? 'Ex-coroinha' : 'Servindo';
        $sql = "UPDATE $this->table SET status = :status WHERE idCoroinha = :idCoroinha";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':idCoroinha', $id);
        return $stmt->execute();
    }

    public function allOrder($order = 'ASC'){
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC'; // segurança
        $sql = "SELECT * FROM $this->table ORDER BY nomeCoroinha $order"; // ordena por nome
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function allInfos()
    {
        $sql = "SELECT * FROM $this->table ORDER BY nivel DESC, nomeCoroinha ASC"; // ordena por nível e depois por nome
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}