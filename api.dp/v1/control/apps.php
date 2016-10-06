<?php

class apps
{
    const NAME_TABLE = "dp_applications";
    const ID = "id";
    const APP_NAME = "app_name";
    const ACCESS_KEY = "access_key";

    public static function isValidAppKey($appKey){
        $command = "SELECT COUNT(" . self::ID . ")" .
            " FROM " . self::NAME_TABLE .
            " WHERE BINARY " . self::ACCESS_KEY . "=?";

        $sentence = ConnectionDB::getInstance()->getDB()->prepare($command);

        $sentence->bindParam(1, $appKey);

        $sentence->execute();

        return $sentence->fetchColumn(0) > 0;
    }

    public static function getAppId($appKey){
        $command = "SELECT " . self::ID .
            " FROM " . self::NAME_TABLE .
            " WHERE " . self::ACCESS_KEY . "=?";

        $sentence = ConnectionDB::getInstance()->getDB()->prepare($command);

        $sentence->bindParam(1, $appKey);

        if ($sentence->execute()) {
            $result = $sentence->fetch();
            return $result['id'];
        } else
            return null;
    }
}
?>