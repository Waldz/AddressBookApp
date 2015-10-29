<?php

namespace Application\Db\Driver;

use Application\Db\Exception\DatabaseException;

/**
 * Database helper
 *
 * @package Application
 * @subpackage Db
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class MysqlDriver
{

    /**
     * @var \mysqli
     */
    private $connection;

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $host
     * @param int $port [$port]
     *
     * @return $this
     * @throws DatabaseException
     */
    public function connect($username, $password, $host, $port = 3306)
    {
        $mysqli = new \mysqli($host, $username, $password, '', $port);
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
     * @param string $database
     *
     * @return $this
     * @throws DatabaseException
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
     * @param string $charset
     *
     * @return $this
     * @throws DatabaseException
     */
    public function selectCharset($charset)
    {
        $query = "SET NAMES '". $charset ."'";
        $this->doQuery($query);

        return $this;
    }

    /**
     * @return $this
     * @throws DatabaseException
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
     * @param string $query
     *
     * @returns resource Result
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
    }

}
