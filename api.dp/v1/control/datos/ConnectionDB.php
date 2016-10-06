<?php
require_once 'login_mysql.php';

class ConnectionDB
{
    private static $db = null;

    private static $pdo;

    final private function __construct()
    {
        try {
            self::getDB();
        }catch (PDOException $e) {

        }
    }

    /**
     * Return instance class
     * @return ConnectionDB|null
     */
    public static function getInstance()
    {
        if (self::$db === null) {
            self::$db = new self();
        }
        return self::$db;
    }

    /**
     * Create connection
     * @return PDO Objeto PDO
     */
    public function getDB()
    {
        if (self::$pdo == null) {
            self::$pdo = new PDO(
                'mysql:dbname=' . DB .
                ';host=' . HOST . ";",
                USER,
                PASSWORD,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$pdo;
    }
    final protected function __clone()
    {
    }

    function _destructor()
    {
        self::$pdo = null;
    }
}