<?php

require 'datos/ConnectionDB.php';

class users
{
    const NAME_TABLE = "dp_users";
    const ID_USER = "id";
    const USERNAME = "username";
    const PASSWORD = "password";
    const API_KEY = "api_key";

    public function isUserExists($username) {
            $pdo = ConnectionDB::getInstance()->getDB();
            $comando = "SELECT " . self::ID_USER .
            " from " . self::NAME_TABLE .
            " WHERE " . self::USERNAME . " = ?";
            $sentencia = $pdo->prepare($comando);
            $sentencia->bindParam(1, $username);
            $resultado = $sentencia->execute();
            return $sentencia->fetchColumn(0) > 0;
    }
    
    private function validarContrasena($contrasenaPlana, $contrasenaHash){
        return password_verify($contrasenaPlana, $contrasenaHash);
    }

    private function encriptarContrasena($contrasenaPlana){
        if ($contrasenaPlana)
            return password_hash($contrasenaPlana, PASSWORD_DEFAULT);
        else 
            return null;
    }

    private function generarClaveApi(){
        return md5(microtime().rand());
    }

    public static function isValidApiKey($apiKey){
        $comando = "SELECT COUNT(" . self::ID_USER . ")" .
            " FROM " . self::NAME_TABLE .
            " WHERE " . self::API_KEY . "=?";

        $sentencia = ConnectionDB::getInstance()->getDB()->prepare($comando);

        $sentencia->bindParam(1, $apiKey);

        $sentencia->execute();

        return $sentencia->fetchColumn(0) > 0;
    }

    public static function getUserId($apiKey){
        $comando = "SELECT " . self::ID_USER .
            " FROM " . self::NAME_TABLE .
            " WHERE " . self::API_KEY . "=?";

        $sentencia = ConnectionDB::getInstance()->getDB()->prepare($comando);

        $sentencia->bindParam(1, $apiKey);

        if ($sentencia->execute()) {
            $resultado = $sentencia->fetch();
            return $resultado['id'];
        } else
            return null;
    }

    public static function crear($username,$password){
        if (!self::isUserExists($username)) {
            $contrasenaEncriptada = self::encriptarContrasena($password);
            $apiKey = self::generarClaveApi();
            $pdo = ConnectionDB::getInstance()->getDB();
            $comando = "INSERT INTO " . self::NAME_TABLE . " ( " .
                self::USERNAME . "," .
                self::PASSWORD . "," .
                self::API_KEY . ")" .
                " VALUES(?,?,?)";

            $sentencia = $pdo->prepare($comando);

            $sentencia->bindParam(1, $username);
            $sentencia->bindParam(2, $contrasenaEncriptada);
            $sentencia->bindParam(3, $apiKey);

            $resultado = $sentencia->execute();

            if ($resultado) {
                return USER_CREATED_SUCCESSFULLY;;
            }else{
                return USER_CREATE_FAILED;
            }
        }else{
            return USER_ALREADY_EXISTED;
        }

    }

    public static function autenticar($username, $password){
        $comando = "SELECT " . self::PASSWORD .
            " FROM " . self::NAME_TABLE .
            " WHERE " . self::USERNAME . "=?";

        $sentencia = ConnectionDB::getInstance()->getDB()->prepare($comando);
        $sentencia->bindParam(1, $username);
        $sentencia->execute();
        if ($sentencia){
            $resultado = $sentencia->fetch();
            if (self::validarContrasena($password, $resultado['password'])) {
                return true;
            }else 
                return false;
        }else{
            return false;
        }
    }

    public static function getUserByUsername($username){
        $comando = "SELECT " .
            self::USERNAME . "," .
            self::PASSWORD . "," .
            self::API_KEY .
            " FROM " . self::NAME_TABLE .
            " WHERE " . self::USERNAME . "=?";

        $sentencia = ConnectionDB::getInstance()->getDB()->prepare($comando);
        $sentencia->bindParam(1, $username);
        if ($sentencia->execute())
            return $sentencia->fetch(PDO::FETCH_ASSOC);
        else
            return null;
    }
}
?>