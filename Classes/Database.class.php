<?php

final class Database{
    private static $instance = null;
    private $connection;

    private function __construct() {

        try {
            $config = parse_ini_file(__DIR__.'/../config.ini',true)['database'];
            $dsn = '';
            if ($config['driver'] ==='mysql') {
                $dsn = "mysql:host={$config['host']};port={$config['port']}; dbname={$config['dbname']}; charset=utf8";
                //die($dsn);
            }
            elseif ($config['driver'] === 'pgsql') {
                $dsn = "pgsql:host={$config['host']}; port={$config['port']}; dbname={$config['dbname']}";
            }else {
                throw new Exception("Driver de banco de dados não suportado". $config['driver']);
            }
            $this->connection = new PDO($dsn, $config['username'], $config['password']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            error_log('Erro de conexão' .$e->getMessage());
            die('Erro ao conectar com o banco de dados. Tente novamente mais tarde');
        }
    }
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new database();
        }
        return self::$instance;
    }
    public function getConnection() {
        return $this->connection;
    }
}