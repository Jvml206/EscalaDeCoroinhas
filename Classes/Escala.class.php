<?php

class Escala extends CRUD
{
    protected $table = "Escala";
    private $idEscala;
    private $idCelebracaoFK;
    private $idCoroinhaFK;
    private $idComunidadeFK;
    private $posicao;

    public function getIdEscala()
    {
        return $this->idEscala;
    }
    public function setIdEscala($idEscala)
    {
        $this->idEscala = $idEscala;
    }

    public function getIdCelebracaoFK()
    {
        return $this->idCelebracaoFK;
    }
    public function setIdCelebracaoFK($idCelebracaoFK)
    {
        $this->idCelebracaoFK = $idCelebracaoFK;
    }

    public function getIdCoroinhaFK()
    {
        return $this->idCoroinhaFK;
    }
    public function setIdCoroinhaFK($idCoroinhaFK)
    {
        $this->idCoroinhaFK = $idCoroinhaFK;
    }

    public function getIdComunidadeFK()
    {
        return $this->idComunidadeFK;
    }
    public function setIdComunidadeFK($idComunidadeFK)
    {
        $this->idComunidadeFK = $idComunidadeFK;
    }

    public function getPosicao()
    {
        return $this->posicao;
    }
    public function setPosicao($posicao)
    {
        $this->posicao = $posicao;
    }

    public function add()
    {
        $sql = "INSERT INTO $this->table (idCelebracaoFK, idCoroinhaFK, idComunidadeFK, posicao) 
        VALUES (:idCelebracaoFK, :idCoroinhaFK, :idComunidadeFK, :posicao)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":idCelebracaoFK", $this->idCelebracaoFK, PDO::PARAM_STR);
        $stmt->bindParam(":idCoroinhaFK", $this->idCoroinhaFK, PDO::PARAM_STR);
        $stmt->bindParam(":idComunidadeFK", $this->idComunidadeFK, PDO::PARAM_STR);
        $stmt->bindParam(":posicao", $this->posicao, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table SET 
        idCelebracaoFK=:idCelebracaoFK, 
        idCoroinhaFK=:idCoroinhaFK,
        idComunidadeFK=:idComunidadeFK,
        posicao=:posicao
        WHERE $campo=:idEscala";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idCelebracaoFK', $this->idCelebracaoFK);
        $stmt->bindParam(':idCoroinhaFK', $this->idCoroinhaFK);
        $stmt->bindParam(':idComunidadeFK', $this->idComunidadeFK);
        $stmt->bindParam(':posicao', $this->posicao);
        $stmt->bindParam(':idEscala', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getCelebracaoSexta()
    {
        $sql = "SELECT 
            c.idCelebracao,
            c.data,
            c.turno,
            e.posicao,
            co.idCoroinha,
            co.nomeCoroinha,
            co.nivel
        FROM Celebracao c
        LEFT JOIN Escala e   ON e.idCelebracaoFK = c.idCelebracao
        LEFT JOIN Coroinha co ON co.idCoroinha   = e.idCoroinhaFK
        WHERE c.diaSemana = 'Sexta'
            AND c.data >= CURDATE()
        ORDER BY c.data ASC, e.posicao ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Guarda a última mensagem de erro para a página exibir.
     */
    private string $erro = '';

    public function getErro(): string
    {
        return $this->erro;
    }

    /**
     * Ponto de entrada único: recebe os arrays vindos do $_POST
     * (escala e comunidade) e salva tudo dentro de uma transação.
     */
    public function salvarEscalaCompleta(array $dadosEscala, array $dadosComunidade): bool
    {
        try {
            $this->db->beginTransaction();

            foreach ($dadosEscala as $semana => $dias) {
                foreach ($dias as $dia => $turnos) {
                    foreach ($turnos as $turno => $posicoes) {

                        $idCelebracao = $this->buscarIdCelebracao($semana . '°', $dia, $turno);
                        if (!$idCelebracao) {
                            continue;
                        }

                        $this->deletarPorCelebracao($idCelebracao);

                        $comunidadeSemana = $dadosComunidade[$semana] ?? null;
                        if (!empty($comunidadeSemana)) {
                            $this->inserirComunidade($idCelebracao, 3, $comunidadeSemana);
                        }

                        foreach ($posicoes as $pos => $idCoroinha) {
                            if ($idCoroinha === "" || $idCoroinha === null || (int) $idCoroinha === 0) {
                                continue;
                            }
                            $this->inserirCoroinha($idCelebracao, $idCoroinha, $pos);
                        }
                    }
                }
            }

            $this->atualizarNumeroServindo();

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            $this->erro = $e->getMessage();
            return false;
        }
    }

    private function buscarIdCelebracao(string $semana, string $dia, string $turno): ?int
    {
        $sql = $this->db->prepare("
        SELECT idCelebracao 
        FROM Celebracao
        WHERE semana = :semana
          AND diaSemana = :dia
          AND turno = :turno
    ");
        $sql->execute([
            ":semana" => $semana,
            ":dia" => $dia,
            ":turno" => $turno
        ]);
        $celebracao = $sql->fetch(PDO::FETCH_OBJ);

        return $celebracao->idCelebracao ?? null;
    }

    private function deletarPorCelebracao(int $idCelebracao): void
    {
        $del = $this->db->prepare("DELETE FROM Escala WHERE idCelebracaoFK = :id");
        $del->execute([":id" => $idCelebracao]);
    }

    private function inserirComunidade(int $idCelebracao, int $posicao, $idComunidade): void
    {
        $sql = $this->db->prepare("
        INSERT INTO Escala (idCelebracaoFK, posicao, idComunidadeFK) 
        VALUES (:idCelebracao, :pos, :com)
    ");
        $sql->execute([
            ":idCelebracao" => $idCelebracao,
            ":pos" => $posicao,
            ":com" => $idComunidade
        ]);
    }

    private function inserirCoroinha(int $idCelebracao, $idCoroinha, int $posicao): void
    {
        $sql = $this->db->prepare("
        INSERT INTO Escala (idCelebracaoFK, idCoroinhaFK, posicao)
        VALUES (:celebracao, :coroinha, :posicao)
    ");
        $sql->execute([
            ":celebracao" => $idCelebracao,
            ":coroinha" => $idCoroinha,
            ":posicao" => $posicao
        ]);
    }

    private function atualizarNumeroServindo(): void
    {
        $this->db->exec("UPDATE Coroinha SET numeroServindo = 0");
        $this->db->exec("
        UPDATE Coroinha c 
        SET numeroServindo = (
            SELECT COUNT(*) FROM Escala e WHERE e.idCoroinhaFK = c.idCoroinha
        )
    ");
    }

    /**
     * Monta a matriz de escalas já preenchida, pronta pra alimentar
     * o formulário (selects marcados, etc).
     */
    public function montarMatrizEscalas(): array
    {
        $Escalas = [];
        $ComunidadesEscala = [];

        $sql = $this->db->query("
        SELECT 
            e.idCelebracaoFK,
            e.idCoroinhaFK,
            e.posicao,
            c.semana,
            c.diaSemana,
            c.turno,
            c.idComunidadeFK
        FROM Escala e
        JOIN Celebracao c 
        ON c.idCelebracao = e.idCelebracaoFK
    ");
        $result = $sql->fetchAll(PDO::FETCH_OBJ);

        foreach ($result as $r) {
            // Sexta não usa "semana" (sempre é '1°'), usa o idCelebracao como chave única
            if ($r->diaSemana === 'Sexta') {
                $chave = $r->idCelebracaoFK;
            } else {
                $chave = str_replace('°', '', $r->semana);
            }

            $Escalas[$chave][$r->diaSemana][$r->turno][$r->posicao] = $r->idCoroinhaFK;

            // Comunidade só se aplica a Domingo/Segunda nesse fluxo
            if ($r->diaSemana !== 'Sexta') {
                $ComunidadesEscala[$chave] = $r->idComunidadeFK;
            }
        }

        return [$Escalas, $ComunidadesEscala];
    }

    /**
     * Salva a escala das celebrações de Sexta-feira.
     * Espera um array no formato:
     * [idCelebracao => ['Noite' => [posicao => idCoroinha]]]
     */
    public function salvarEscalaSexta(array $dadosSexta): bool
    {
        try {
            $this->db->beginTransaction();

            foreach ($dadosSexta as $idCelebracao => $turnos) {
                $idCelebracao = (int) $idCelebracao;

                $this->deletarPorCelebracao($idCelebracao);

                foreach ($turnos as $turno => $posicoes) {
                    foreach ($posicoes as $pos => $idCoroinha) {
                        if ($idCoroinha === "" || $idCoroinha === null || (int) $idCoroinha === 0) {
                            continue;
                        }
                        $this->inserirCoroinha($idCelebracao, $idCoroinha, $pos);
                    }
                }
            }

            $this->atualizarNumeroServindo();

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            $this->erro = $e->getMessage();
            return false;
        }
    }
}