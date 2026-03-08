<?php

abstract class CRUD
{
    protected $table;
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    //Metódos Abstratos
    abstract public function add();
    abstract public function update(string $campo, int $id);

    //Metódos listar todos ps registros
    public function all()
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //Metódo de buscar registro por campo
    public function search(string $campo, string $id)
    {
        $sql = "SELECT * FROM $this->table WHERE $campo = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_OBJ) : null;
    }

    public function searchStr(string $campo, string $string)
    {
        $sql = "SELECT * FROM $this->table WHERE $campo = :string AND status = 'Servindo'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":string", $string, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_OBJ) : [];
    }

    //Método para Excluir um registro pelo ID
    public function delete(string $campo, int $id)
    {
        $sql = "DELETE FROM $this->table WHERE $campo = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao excluir Registro' . $e->getMessage());
            return false;
        }
    }

    //Método para validar se o Usuario já existe
    public function validaUnico(string $campo, string $valor)
    {
        $sql = "SELECT * FROM $this->table WHERE $campo = :valor";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? false : true;
    }

    public function sp_exibir(string $procedure)
    {
        $sql = "CALL {$procedure}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function iniciaTrans()
    {
        $this->db->beginTransaction();
    }

    public function confirmaTrans()
    {
        $this->db->commit();
    }
    public function cancelarTrans()
    {
        $this->db->rollBack();
    }
}