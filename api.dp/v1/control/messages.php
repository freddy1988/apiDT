<?php

class messages
{
    const NAME_TABLE = "dp_messages";
    const ID = "id";
    const DESCRIPTION = "description";

    public static function getMessages(){
        $command = "SELECT * FROM " . self::NAME_TABLE;
        $sentencia = ConnectionDB::getInstance()->getDB()->prepare($command);
        if ($sentencia->execute()) {        
            $result = $sentencia->fetch();   
            return $result['description'];      
        }
    }

    public static function updateMessage($message){
                $consulta = "UPDATE " . self::NAME_TABLE .
                " SET " .
                self::DESCRIPTION . "=?" .
                " WHERE " . self::ID . "=1";

                $sentencia = ConnectionDB::getInstance()->getDB()->prepare($consulta);

                $sentencia->bindParam(1, $message);
                $sentencia->execute();
                return $sentencia->rowCount();
    }
}
?>