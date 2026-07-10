<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Usuario extends CRUD
{

    protected $table = "Usuario";
    private $idUsuario;
    private $nomeUsuario;
    private $emailUsuario;
    private $senhaUsuario;
    private $nivelAcessoUsuario;

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function getNomeUsuario()
    {
        return $this->nomeUsuario;
    }

    public function setNomeUsuario($nomeUsuario)
    {
        $this->nomeUsuario = $nomeUsuario;
    }

    public function getEmailUsuario()
    {
        return $this->emailUsuario;
    }

    public function setEmailUsuario($emailUsuario)
    {
        $this->emailUsuario = $emailUsuario;
    }

    public function getSenhaUsuario()
    {
        return $this->senhaUsuario;
    }

    public function setSenhaUsuario($senhaUsuario)
    {
        $this->senhaUsuario = $senhaUsuario;
    }

    public function getNivelAcessoUsuario()
    {
        return $this->nivelAcessoUsuario;
    }

    public function setNivelAcessoUsuario($nivelAcessoUsuario)
    {
        $this->nivelAcessoUsuario = $nivelAcessoUsuario;
    }

    //Adiciona um usuário
    public function add()
    {
        $sql = "INSERT INTO $this->table (nomeUsuario, emailUsuario, senhaUsuario, nivelAcessoUsuario) 
            VALUES (:nomeUsuario, :emailUsuario, :senhaUsuario, :nivelAcessoUsuario)";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->bindParam(':nomeUsuario', $this->nomeUsuario, PDO::PARAM_STR);
            $stmt->bindParam(':emailUsuario', $this->emailUsuario, PDO::PARAM_STR);
            $stmt->bindParam(':senhaUsuario', $this->senhaUsuario, PDO::PARAM_STR);
            $stmt->bindParam(':nivelAcessoUsuario', $this->nivelAcessoUsuario, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Armazena o ID do usuário recém-criado na propriedade da classe
                $this->idUsuario = $this->db->lastInsertId();
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Erro ao criar usuário: " . $e->getMessage();
            return false;
        }
    }



    // Atualizar um usuário existente
    public function update($campo, $id)
    {
        $sql = "UPDATE $this->table 
                  SET  nomeUsuario = :nomeUsuario, emailUsuario = :emailUsuario, nivelAcessoUsuario = :nivelAcessoUsuario
                  WHERE $campo = :idUsuario";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->bindParam(':nomeUsuario', $this->nomeUsuario, PDO::PARAM_STR);
            $stmt->bindParam(':emailUsuario', $this->emailUsuario, PDO::PARAM_STR);
            $stmt->bindParam(':nivelAcessoUsuario', $this->nivelAcessoUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':idUsuario', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar usuário: " . $e->getMessage();
            return false;
        }
    }

    #Efetuar Login
    public function login()
    {
        $sql = "SELECT u.*, CASE 
            WHEN u.nivelAcessoUsuario = 1 THEN 'Admin'
        END AS nome FROM  $this->table u
         WHERE emailUsuario = :emailUsuario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':emailUsuario', $this->emailUsuario);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_OBJ);
            if (password_verify($this->senhaUsuario, $usuario->senhaUsuario)) {
                $_SESSION['user_id'] = $usuario->idUsuario;
                $_SESSION['user_name'] = $usuario->nome;
                $_SESSION['nivel_acesso'] = $usuario->nivelAcessoUsuario; // Armazena o nível de acesso
                $_SESSION['ultimaAtividade'] = time(); // Armazena a hora da última atividade
                $redirect_url = $_POST['redirect'] ?? 'dashboard.php';
                header("Location: $redirect_url");
                exit();
            }
        }

        return "Usuário ou Senha incorreta. Por favor, tente novamente.";
    }

    //Efetuar Logoff

    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    #Expirar 

    public function sessaoExpirou()
    {
        $tempo = 1800; // 30 minutos de inatividade
        if (isset($_SESSION['ultimaAtividade']) && (time() - $_SESSION['ultimaAtividade']) > $tempo) {
            $this->logout();
            return true;
        }
        $_SESSION['ultimaAtividade'] = time(); // Atualiza a hora da última atividade
        return false;
    }

    public function verificarNivelAcesso(array $nivelNecessario)
    {
        // Verifica se o nível de acesso do usuário atende ao nível necessário
        if (isset($_SESSION['nivel_acesso']) && in_array($_SESSION['nivel_acesso'], $nivelNecessario)) {
            return true; // Usuário tem permissão
        }

        return false; // Usuário não tem permissão

    }

    public function solicitarRecuperacaoSenha($email, $mensagem = null, $assunto = null)
    {
        require __DIR__ . '/../PHPMailer/src/Exception.php';
        require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
        require __DIR__ . '/../PHPMailer/src/SMTP.php';
        try {
            // Verifica se o e-mail está cadastrado
            $sql = "SELECT idUsuario FROM $this->table WHERE emailUsuario = :emailUsuario";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emailUsuario', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_OBJ);

                // Gera token seguro
                $token = bin2hex(random_bytes(32));
                $expira_em = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Insere token na tabela de recuperação
                $sql = "INSERT INTO RecuperacaoSenha (idUsuarioFK, tokenRecuperacaoSenha, expiraRecuperacaoSenha) 
                    VALUES (:idUsuarioFK, :tokenRecuperacaoSenha, :expiraRecuperacaoSenha)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':idUsuarioFK', $usuario->idUsuario, PDO::PARAM_INT);
                $stmt->bindParam(':tokenRecuperacaoSenha', $token, PDO::PARAM_STR);
                $stmt->bindParam(':expiraRecuperacaoSenha', $expira_em, PDO::PARAM_STR);
                $stmt->execute();

                // Monta link de recuperação
                $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
                $dominio = $_SERVER['HTTP_HOST'];
                $caminho = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

                $link = "$protocolo://$dominio$caminho/reset_senha.php?token=$token";

                // Configura PHPMailer
                $mail = new PHPMailer(true);

                try {
                    $config = parse_ini_file(__DIR__ . '/../config.ini', true)['email'];
                    // Configurações do servidor
                    $mail->isSMTP();
                    $mail->Host = $config['Host'];
                    $mail->SMTPAuth = $config['SMTPAuth'];
                    $mail->Username = $config['Username'];
                    $mail->Password = $config['Password'];
                    $mail->SMTPSecure = $config['SMTPSecure'];
                    $mail->Port = $config['Port'];                            // ou 465 para 'ssl'

                    // Remetente e destinatário
                    $mail->setFrom($config['Username'], 'Escala de Coroinhas N. S. de Fátima');
                    $mail->addAddress($email);

                    // Conteúdo
                    $mail->isHTML(true);
                    $mail->Subject = 'Recuperar de Senha';
                    $mail->Body = "
                    <p>Olá,</p>
                    <p>$mensagem</p>
                    <p>Clique no link abaixo para criar uma nova senha:</p>
                    <p><a href='$link'>$link</a></p>
                    <p>Este link expira em 1 hora.</p>
                    <p>Se você não solicitou isso, ignore este e-mail.</p>
                ";

                    $mail->AltBody = "Olá,\n\nAcesse o link para redefinir sua senha: $link\n\nEste link expira em 1 hora.";

                    $mail->send();
                    return true;

                } catch (Exception $e) {
                    error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
                    return false;
                }

            } else {
                return false; // E-mail não encontrado
            }

        } catch (PDOException $e) {
            error_log('Erro em solicitarRecuperacaoSenha: ' . $e->getMessage());
            return false;
        }
    }


    public function redefinirSenha($token, $novaSenha)
    {
        try {
            // Verifica o token
            $sql = "SELECT idUsuarioFK, ExpiraRecuperacaoSenha FROM RecuperacaoSenha WHERE tokenRecuperacaoSenha = :tokenRecuperacaoSenha";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':tokenRecuperacaoSenha', $token, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $dados = $stmt->fetch(PDO::FETCH_OBJ);
                if (strtotime($dados->ExpiraRecuperacaoSenha) >= time()) {
                    // Atualiza a senha
                    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
                    $sql = "UPDATE $this->table SET senhaUsuario = :novaSenha WHERE idUsuario = :idUsuario";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':novaSenha', $novaSenhaHash, PDO::PARAM_STR);
                    $stmt->bindParam(':idUsuario', $dados->idUsuarioFK, PDO::PARAM_INT);
                    $stmt->execute();
                    // Remove o token usado
                    $sql = "DELETE FROM RecuperacaoSenha WHERE tokenRecuperacaoSenha = :tokenRecuperacaoSenha";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':tokenRecuperacaoSenha', $token, PDO::PARAM_STR);
                    $stmt->execute();

                    return true;
                } else {
                    return false; // Token expirado
                }
            } else {
                return false; // Token inválido
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
