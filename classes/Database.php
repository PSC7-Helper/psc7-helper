<?php

class Db{
    /**
     * @var \PDO
     */
    public static $pdo;


    public static function fetchAll($q, $parameters = null){
        if($parameters !== null && !is_array($parameters)){
            $parameters = [$parameters];
        }
        if(is_array($parameters)){
            $stmt = static::$pdo->prepare($q);
            $stmt->execute($parameters);
        }else{
            $stmt = static::$pdo->query($q);
        }
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public static function fetch($q, $parameters = null){
        if($parameters !== null && !is_array($parameters)){
            $parameters = [$parameters];
        }
        if(is_array($parameters)){
            $stmt = static::$pdo->prepare($q);
            $stmt->execute($parameters);
        }else{
            $stmt = static::$pdo->query($q);
        }
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public static function execute($q, $parameters = null){
        if(is_array($parameters)){
            $stmt = static::$pdo->prepare($q);
            return $stmt->execute($parameters);
        }else{
            return static::$pdo->query($q);
        }
    }

    public static function insert($table, $data, $ignore = false, $update = false){

        if(is_array($data)){
            $query = "INSERT ".($ignore?'IGNORE ':'')."INTO ".$table." SET ";
            $parts = [];
            $parameters = [];
            foreach($data as $key => $value){
                $parts[] = " `".$key."` = :".$key." ";
                $parameters[':'.$key] = $value;
            }
            $query .= implode(',', $parts);

            if($update){
                $query .= " ON DUPLICATE KEY UPDATE ";
                foreach($data as $key => $value){
                    $parts[] = " `".$key."` = :".$key." ";
                }
                $query .= implode(',', $parts);
            }
            //echo $query;
            return static::execute($query, $parameters);
        } else{
            return false;
        }
    }

    public static function update($table, $data, $where = ''){

        if(is_array($data)){
            $query = "UPDATE ".$table." SET ";
            $parts = [];
            $parameters = [];
            foreach($data as $key => $value){
                $parts[] = " `".$key."` = :".$key." ";
                $parameters[':'.$key] = $value;
            }
            $query .= implode(',', $parts);

            if(!empty($where)){
                $query .= ' WHERE '.$where;
            }
            return static::execute($query, $parameters);
        } else{
            return false;
        }
    }

    public static function lastInsertId(){
        return static::$pdo->lastInsertId();
    }

}


$dbConnection = ShopwareHelper::getDBDataShopware();

Db::$pdo = new \PDO('mysql:host='.$dbConnection["host"].';port='.$dbConnection["port"].';dbname='.$dbConnection["dbname"].';charset=utf8', $dbConnection["username"], $dbConnection["password"]);
Db::$pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING );
