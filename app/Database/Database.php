<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Database;

use PDO;
use PDOException;
use psc7helper\App\Config\Config;
use psc7helper\App\Exception\DatabaseException;

class Database {

    /**
     * pdo
     * @var PDO
     */
    protected $pdo;

    /**
     * last
     * @var PDOStatment
     */
    protected $last;

    /**
     * counter
     * @var int
     */
    protected $counter;

    /**
     * __construct
     */
    public function __construct() {
        $this->setConnection();
    }

    /**
     * setConnection
     * @return $this
     */
    private function setConnection() {
        if ($this->pdo) {
            return $this;
        }
        try {
            $this->pdo = new PDO(
                'mysql:host=' . Config::get('dbHost') . ';port=' . Config::get('dbPort') . ';dbname=' . Config::get('dbName'), Config::get('dbUser'), Config::get('dbPass'),
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                )
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $ex) {
            DatabaseException::handle($ex, 'PDOException');
        }
        return $this;
    }

    /**
     * insert
     * @param string $databaseTable
     * @param array $fieldArray (fieldNameAsKey => Value)
     * @return $this
     */
    public function insert($databaseTable, $fieldArray = array()) {
        $keyArray = array();
        $valueArray = array();
        foreach (array_keys($fieldArray) as $key) {
            $keyArray[] = '`' . $key . '`';
            $valueArray[] = '?';
        }
        $query = array();
        $query[] = 'INSERT INTO `' . Config::get('dbPrefix') . $databaseTable . '` (';
        $query[] = implode(', ', $keyArray);
        $query[] = ') VALUES (';
        $query[] = implode(', ', $valueArray);
        $query[] = ')';
        $stmtQuery = implode(' ', $query);
        $this->executeQuery($stmtQuery, $fieldArray);
        return $this;
    }

    /**
     * insertIgnore
     * @param string $databaseTable
     * @param array $fieldArray (fieldNameAsKey => Value)
     * @return $this
     */
    public function insertIgnore($databaseTable, $fieldArray = array()) {
        $keyArray = array();
        $valueArray = array();
        foreach (array_keys($fieldArray) as $key) {
            $keyArray[] = '`' . $key . '`';
            $valueArray[] = '?';
        }
        $query = array();
        $query[] = 'INSERT IGNORE INTO `' . Config::get('dbPrefix') . $databaseTable . '` (';
        $query[] = implode(', ', $keyArray);
        $query[] = ') VALUES (';
        $query[] = implode(', ', $valueArray);
        $query[] = ')';
        $stmtQuery = implode(' ', $query);
        $this->executeQuery($stmtQuery, $fieldArray);
        return $this;
    }

    /**
     * update
     * @param string $databaseTable
     * @param array $fieldArray (fieldNameAsKey => Value)
     * @param array $conditionArray
     * @return $this
     */
    public function update($databaseTable, $fieldArray = array(), $conditionArray = array()) {
        $mergeArray = array();
        $i = 0;
        $setArray = array();
        foreach ($fieldArray as $key => $value) {
            if (!strpos($value, '+')) {
                $setArray[] = '`' . $key . '` = ?';
                $mergeArray[$i] = $value;
                $i++;
            } else {
                $setArray[] = '`' . $key . '` = ' . '`' . $value . '`';
            }
        }
        if (count($conditionArray) > 0) {
            $whereArray = array();
            foreach ($conditionArray as $key => $value) {
                $whereArray[] = '`' . $key . '` = ?';
                $mergeArray[$i] = $value;
                $i++;
            }
        }
        $query = array();
        $query[] = 'UPDATE `' . Config::get('dbPrefix') . $databaseTable . '` SET ';
        $query[] = implode(', ', $setArray);
        if (count($conditionArray) > 0) {
            $query[] = 'WHERE ';
            if (count($conditionArray) > 1) {
                $query[] = implode(' AND ', $whereArray);
            } else {
                $query[] = implode('', $whereArray);
            }
        }
        $stmtQuery = implode(' ', $query);
        $this->executeQuery($stmtQuery, $mergeArray);
        return $this;
    }

    /**
     * delete
     * @param string $databaseTable
     * @param array $conditionArray
     * @return $this
     */
    public function delete($databaseTable, $conditionArray = array()) {
        $whereArray = array();
        foreach (array_keys($conditionArray) as $key) {
            $whereArray[] = '`' . $key . '` = ?';
        }
        $query = array();
        $query[] = 'DELETE FROM `' . Config::get('dbPrefix') . $databaseTable . '` ';
        $query[] = 'WHERE ';
        if (count($conditionArray) > 1) {
            $query[] = implode(' AND ', $whereArray);
        } else {
            $query[] = implode('', $whereArray);
        }
        $stmtQuery = implode(' ', $query);
        $this->executeQuery($stmtQuery, $conditionArray);
        return $this;
    }

    /**
     * selectVar
     * @param string $query
     * @param array $arguments
     * @return $this
     */
    public function selectVar($query, $arguments = array()) {
        try {
            if (count($arguments) > 0) {
                $this->executeSelectQuery($query, $arguments);
            } else {
                $this->executeSelectQuery($query);
            }
            if ($this->last) {
                return $this->last->fetch(PDO::FETCH_NUM)[0];
            }
        } catch (PDOException $ex) {
            DatabaseException::handle($ex, 'PDOException');
        }
        return $this;
    }

    /**
     * selectAssoc
     * @param string $query
     * @param array $arguments
     * @return $this
     */
    public function selectAssoc($query, $arguments = false) {
        try {
            if (is_array($arguments) && count($arguments) > 0) {
                $this->executeSelectQuery($query, $arguments);
            } else {
                $this->executeSelectQuery($query);
            }
            if ($this->last) {
                return $this->last->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $ex) {
            DatabaseException::handle($ex, 'PDOException');
        }
        return $this;
    }

    /**
     * selectNum
     * @param string $query
     * @param array $arguments
     * @return $this
     */
    public function selectNum($query, $arguments = false) {
        try {
            if (is_array($arguments) && count($arguments) > 0) {
                $this->executeSelectQuery($query, $arguments);
            } else {
                $this->executeSelectQuery($query);
            }
            if ($this->last) {
                return $this->last->fetchAll(PDO::FETCH_NUM);
            }
        } catch (PDOException $ex) {
            DatabaseException::handle($ex, 'PDOException');
        }
        return $this;
    }

    /**
     * executeQuery
     * @param string $prepare
     * @param array $autobind
     * @return boolean
     */
    private function executeQuery($prepare, $autobind = array()) {
        try {
            $this->setLast();
            $stmt = $this->prepare($prepare);
            $this->autobind($stmt, $autobind);
            $stmt->execute();
            $this->last = $stmt;
            if (Config::get('dbLog')) {
                $this->logQuery($prepare);
            }
            $this->counter++;
        } catch (PDOException $ex) {
            DatabaseException::handle($ex, 'PDOException');
        }
        return true;
    }

    /**
     * executeSelectQuery
     * @return boolean
     */
    private function executeSelectQuery() {
        $arguments = func_get_args();
        $query = $arguments[0];
        $stmtQuery = str_replace('PREFIX_', Config::get('dbPrefix'), $query);
        try {
            $this->setLast();
            $stmt = $this->prepare($stmtQuery);
            if (count($arguments) > 1) {
                if (!is_array($arguments[1])) {
                    array_shift($arguments);
                    $this->autobind($stmt, $arguments);
                } else {
                    $this->autobind($stmt, $arguments[1]);
                }
            }
            $stmt->execute();
            $this->last = $stmt;
            if (Config::get('dbLog')) {
                $this->logQuery($stmtQuery);
            }
            $this->counter++;
        } catch (PDOException $ex) {
            DatabaseException::handle($ex, 'PDOException');
        }
        return true;
    }

    /**
     * setLast
     * @return $this
     */
    private function setLast() {
        if (is_object($this->last)) {
            $this->last->closeCursor();
            $this->last = null;
        }
        return $this;
    }

    /**
     * prepare
     * @return PDOStatment
     */
    private function prepare() {
        $arguments = func_get_args();
        try {
            $query = $arguments[0];
            $stmt = $this->pdo->prepare($query);
            if (count($arguments) > 1) {
                array_shift($arguments);
                $this->autobind($stmt, $arguments);
            }
            return $stmt;
        } catch (PDOException $ex) {
            DatabaseException::handle($ex, 'PDOException');
        }
    }

    /**
     * autobind
     * @param PDOStatment $stmt
     * @param array $arguments
     * @return boolean
     */
    private function autobind($stmt, $arguments = array()) {
        $i = 0;
        foreach ($arguments as $value) {
            $i++;
            if (is_string($value) && strlen($value) < 4000) {
                $typ = PDO::PARAM_STR;
            } elseif (is_string($value) && strlen($value) >= 4000) {
                $typ = PDO::PARAM_LOB;
            } elseif (is_null($value)) {
                $typ = PDO::PARAM_NULL;
            } elseif (is_array($value)) {
                $typ = PDO::PARAM_STR;
                $value = serialize($value);
            } elseif (is_int($value)) {
                $typ = PDO::PARAM_INT;
            } elseif (\is_double($value)) {
                $typ = PDO::PARAM_STR;
                $value = sprintf('%f', $value);
            } elseif (is_bool($value)) {
                $typ = PDO::PARAM_STR;
                $value = ($value === true) ? '1' : '0';
            } elseif (is_object($value) && is_a($value, 'DateTime')) {
                $typ = PDO::PARAM_STR;
                $value = $value->format('Y-m-d H:i:s');
            } elseif (is_object($value)) {
                $typ = PDO::PARAM_STR;
                if (method_exists($value, 'toSTring')) {
                    $value = $value->__toString();
                } else {
                    $value = 'OBJECT';
                }
            }
            $stmt->bindValue($i, $value, $typ);
        }
        return true;
    }

    /**
     * getConnection
     * @return array
     */
    public function getConnection() {
        $attributes = array(
            'ATTR_AUTOCOMMIT' => $this->pdo->getAttribute(\PDO::ATTR_AUTOCOMMIT),
            'ATTR_CASE' => $this->pdo->getAttribute(\PDO::ATTR_CASE),
            'ATTR_CLIENT_VERSION' => $this->pdo->getAttribute(\PDO::ATTR_CLIENT_VERSION),
            'ATTR_CONNECTION_STATUS' => $this->pdo->getAttribute(\PDO::ATTR_CONNECTION_STATUS),
            'ATTR_DRIVER_NAME' => $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME),
            'ATTR_ERRMODE' => $this->pdo->getAttribute(\PDO::ATTR_ERRMODE),
            'ATTR_PERSISTENT' => $this->pdo->getAttribute(\PDO::ATTR_PERSISTENT),
            'ATTR_SERVER_INFO' => $this->pdo->getAttribute(\PDO::ATTR_SERVER_INFO),
            'ATTR_SERVER_VERSION' => $this->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION)
        );
        return $attributes;
    }

    /**
     * getCounter
     * @return int
     */
    public function getCounter() {
        return $this->counter;
    }

    /**
     * getLastID
     * @return int
     */
    public function getLastID() {
        if ($this->pdo) {
            return $this->pdo->lastInsertId();
        }
        return 0;
    }

}
