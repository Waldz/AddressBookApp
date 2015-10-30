<?php

namespace Application\Db\Driver;

use Application\Db\Exception\DatabaseException;
use mysqli;
use mysqli_result;

/**
 * Class MysqlDriver
 *
 * @package Application
 * @subpackage Db
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class MysqlDriver implements DatabaseDriver {

    /**
     * @var mysqli
     */
    private $connection;

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * {@inheritdoc}
     */
    public function connect($username, $password, $host, $port = null)
    {
        $port = $port ?: 3306;

        $mysqli = new mysqli($host, $username, $password, '', $port);
        if ($mysqli->connect_error) {
            throw new DatabaseException(sprintf(
                'Cant connect to MySQL server (%s: %s)',
                $mysqli->connect_errno,
                $mysqli->connect_error
            ));
        }

        $this->connection = $mysqli;
    }

    /**
     * {@inheritdoc}
     */
    public function disconnect()
    {
        if (isset($this->connection)) {
            $result = $this->connection->close();
            if (!$result) {
                throw new DatabaseException('Failed to disconnect from MySQL server');
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function selectDatabase($database)
    {
        $result = $this->connection->select_db($database);
        if (!$result) {
            throw new DatabaseException('Failed to select MySQL database: '.$database);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function selectCharset($charset)
    {
        $query = "SET NAMES '". $charset ."'";
        $this->doQuery($query);

        return $this;
    }

    /**
     * Convert a text value into a database specific format that is suitable to
     *
     * @param mixed $value
     * @return string
     */
    public function quote($value)
    {
        if (is_null($value)) {
            return 'NULL';
        }

        return "'". $this->connection->real_escape_string($value) ."'";
    }

    /**
     * Execute the specified query & fetch the first row
     *
     * @param $query
     * @return array
     */
    public function queryRow($query)
    {
        $result = $this->doQuery($query);
        return $result->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * Execute the specified query & fetch all the rows
     *
     * @param $query
     * @return array[]
     */
    public function queryAll($query)
    {
        $result = $this->doQuery($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @param string $query
     *
     * @returns mysqli_result
     * @throws DatabaseException
     */
    public function doQuery($query)
    {
        if (!isset($this->connection)) {
            throw new \UnexpectedValueException('Firstly connect() to MySQL server');
        }

        $result = $this->connection->query($query);
        if (!$result) {
            throw new DatabaseException(sprintf(
                'Cant connect to MySQL server (%s: %s)',
                $this->connection->errno,
                $this->connection->error
            ));
        }

        return $result;
    }

}
