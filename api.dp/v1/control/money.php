<?php

class money
{
    const NAME_TABLE = "dp_money";
    const ID_MONEY = "id";
    const NAME = "name";
    const PRICE = "price";
    const ID_USER = "id_user";
    const UPDATE = "update_date";

    public function isMoneyExists($name) {
            $pdo = ConnectionDB::getInstance()->getDB();
            $comando = "SELECT " . self::ID_MONEY .
            " from " . self::NAME_TABLE .
            " WHERE " . self::NAME . " = ?";
            $sentencia = $pdo->prepare($comando);
            $sentencia->bindParam(1, $name);
            $resultado = $sentencia->execute();
            return $sentencia->fetchColumn(0) > 0;
    }

    public static function update($idUser, $idMoney, $name=NULL, $price=NULL){
            if($name==NULL){
                self::updateMoney($idUser, $idMoney,$price);
                $consulta = "UPDATE " . self::NAME_TABLE .
                " SET " . self::ID_USER . "=?," .
                self::PRICE . "=?" .
                " WHERE " . self::ID_MONEY . "=?";

                $sentencia = ConnectionDB::getInstance()->getDB()->prepare($consulta);

                $sentencia->bindParam(1, $idUser);
                $sentencia->bindParam(2, $price);
                $sentencia->bindParam(3, $idMoney);
                $sentencia->execute();
                return $sentencia->rowCount();
            }
            if($price==NULL){
                $consulta = "UPDATE " . self::NAME_TABLE .
                " SET " . self::ID_USER . "=?," .
                self::NAME . "=?" .
                " WHERE " . self::ID_MONEY . "=?";

                $sentencia = ConnectionDB::getInstance()->getDB()->prepare($consulta);

                $sentencia->bindParam(1, $idUser);
                $sentencia->bindParam(2, $name);
                $sentencia->bindParam(3, $idMoney);
                $sentencia->execute();
                return $sentencia->rowCount();
            }
            if($price!=NULL && $name!=NULL){
                self::updateMoney($idUser, $idMoney,$price);
                $consulta = "UPDATE " . self::NAME_TABLE .
                " SET " . self::ID_USER . "=?," .
                self::NAME . "=?," .
                self::PRICE . "=?" .
                " WHERE " . self::ID_MONEY . "=?";

                $sentencia = ConnectionDB::getInstance()->getDB()->prepare($consulta);

                $sentencia->bindParam(1, $idUser);
                $sentencia->bindParam(2, $name);
                $sentencia->bindParam(3, $price);
                $sentencia->bindParam(4, $idMoney);
                $sentencia->execute();
                return $sentencia->rowCount();
            }       
            
    }

    public static function getNameById($idMoney){
            $command = "SELECT " .
                self::NAME ." FROM " . self::NAME_TABLE ." WHERE " . self::ID_MONEY . "=?";
            $sentencia = ConnectionDB::getInstance()->getDB()->prepare($command);

            $sentencia->bindParam(1, $idMoney);

            $sentencia->execute();

            return $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    public static function getMoney(){      
        $command = "SELECT " .
                self::NAME . "," .
                self::PRICE . "," . 
                self::UPDATE . "," .
                self::ID_MONEY ." FROM " . self::NAME_TABLE;
        $sentencia = ConnectionDB::getInstance()->getDB()->prepare($command);
        if ($sentencia->execute()) {        
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);         
        }
    }

    public static function createMoney($idUser,$name,$price){
        if (!self::isMoneyExists($name)) {     
            $command = "INSERT INTO " . self::NAME_TABLE . " ( " .
                self::NAME . "," .
                self::PRICE . "," .
                self::ID_USER . ")" .
                " VALUES(?,?,?)";     
            $pdo = ConnectionDB::getInstance()->getDB();
            $sentencia = $pdo->prepare($command);
            $sentencia->bindParam(1, $name);
            $sentencia->bindParam(2, $price);
            $sentencia->bindParam(3, $idUser);

            if ($sentencia->execute()) {        
                return $pdo->lastInsertId();         
            }
            else{
                return "ERROR";
            }
        }
        else{
            return NULL;
        }
    }

    public static function getHistoryMoney($idMoney,$start= NULL,$end=NULL){     
            if($start==null && $end==null){
                $command = "SELECT price,update_date FROM dp_history_money WHERE id_money = ? order by update_date desc";
                $sentencia = ConnectionDB::getInstance()->getDB()->prepare($command);
                $sentencia->bindParam(1, $idMoney);    
            }
            else{    
                $command = "SELECT price,update_date FROM dp_history_money WHERE id_money = ?    
                and (DATE(update_date) BETWEEN ? AND ?) order by update_date desc";       
                $sentencia = ConnectionDB::getInstance()->getDB()->prepare($command);
                $sentencia->bindParam(1, $idMoney);
                $sentencia->bindParam(2, $start);
                $sentencia->bindParam(3, $end);
            }
            if ($sentencia->execute()) {        
                return $sentencia->fetchAll(PDO::FETCH_ASSOC);         
            }
    }

    public static function updateMoney($idUser, $idMoney, $price){
            $pdo = ConnectionDB::getInstance()->getDB();

            $command = "INSERT INTO dp_history_money (price,id_money,id_user) VALUES(?,?,?)";

            $sentencia = $pdo->prepare($command);
            $sentencia->bindParam(1, $price);
            $sentencia->bindParam(2, $idMoney);
            $sentencia->bindParam(3, $idUser);
            $sentencia->execute();
            return $sentencia->rowCount();
    }
}

