<?php
namespace Repositories;

use PDO;
use PDOException;

 abstract class AbstractRepository {

    protected $connection;

    function __construct() {

        require __DIR__ . '/../dbconfig.php';

        try {
            $this->connection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {

        }
    }
    protected function executeQueryAndGetResult($query,$params = array(), $fetchAll = true,$returnLastInsertId = false){
        try {
            $stmt=$this->connection->prepare($query);
            $this->bindValuesToQuery($stmt, $params);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            if ($rowCount == 0) {
                return $this->handleZeroRowCount($query);
            }
             else if ($rowCount > 0) {
                return $this->handlePositiveRowCount($query, $stmt, $fetchAll, $returnLastInsertId);
            }

        } catch (PDOException $e) {
            echo $e;
        }
    }
    private function bindValuesToQuery($stmt, $params): void
    {
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    }
    private function handleZeroRowCount($query): ?bool
    {
        if (!stripos($query, 'insert') !== false || stripos($query, 'delete') !== false || stripos($query, 'update') !== false) {
            return false;
        }
        return null;
    }
    private function handlePositiveRowCount($query, $stmt, $fetchAll, $returnLastInsertId)
    {
         if (stripos($query, 'delete') !== false || stripos($query, 'update') !== false || stripos($query, 'insert') !== false) {
            if($returnLastInsertId){
                return $this->connection->lastInsertId();
            }
             return true;
        } else {
             if(!stripos($query, 'select')){
                 if ($fetchAll) {
                     return $stmt->fetchAll(PDO::FETCH_ASSOC);
                 } else {
                     return $stmt->fetch(PDO::FETCH_ASSOC);
                 }
             }

        }
    }
}