<?php

namespace Application\Db\Driver;

use Application\Db\Exception\DatabaseException;

/**
 * Face of any database driver
 *
 * @package Application
 * @subpackage Db
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
interface DatabaseDriver {

	/**
	 * Connect to database
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $host
	 * @param int $port [$port]
	 *
	 * @return $this
	 * @throws DatabaseException
	 */
	public function connect($username, $password, $host, $port = null);

	/**
	 * Disconnect to database
	 *
	 * @return $this
	 * @throws DatabaseException
	 */
	public function disconnect();

	/**
	 * @param string $database
	 *
	 * @return $this
	 * @throws DatabaseException
	 */
	public function selectDatabase($database);

	/**
	 * @param string $charset
	 *
	 * @return $this
	 * @throws DatabaseException
	 */
	public function selectCharset($charset);

	/**
	 * Convert a text value into a database specific format that is suitable to
	 *
	 * @param mixed $value
	 * @return string
	 */
	public function quote($value);

	/**
	 * Execute the specified query & fetch the first row
	 *
	 * @param $query
	 * @return array
	 */
	public function queryRow($query);

	/**
	 * Execute the specified query & fetch all the rows
	 *
	 * @param $query
	 * @return array[]
	 */
	public function queryAll($query);

}
