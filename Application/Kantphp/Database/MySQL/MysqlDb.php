<?php

/**
 * @package KantPHP
 * 
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license BSD License.
 */
!defined('IN_KANT') && exit('Access Denied');

class MysqlDb extends DbQueryAbstract implements DbQueryInterface {

    //Connection identifier
    private $_dbh = '';
    private $_config;

    /**
     *
     * Open database connection
     *
     * @param config
     */
    public function open($config) {
        $this->_config = $config;
        if ($config['autoconnect'] == 1) {
            $this->_connect();
        }
    }

    /**
     *
     * Truly open database connection
     *
     * @return resource a MySQL link identifier on success, or false on
     * failure.
     */
    private function _connect() {
        $func = $this->_config['persistent'] == 1 ? 'mysql_pconnect' : 'mysql_connect';
        if (!$this->_dbh = @$func($this->_config['hostname'] . ":" . $this->_config['port'], $this->_config['username'], $this->_config['password'], 1)) {
            throw new KantException(sprintf('Can not connect to MySQL server or cannot use database.%s', mysql_error()));
        }
        if ($this->version() > '4.1') {
            $charset = isset($this->_config['charset']) ? $this->_config['charset'] : '';
            $serverset = $charset ? "character_set_connection='$charset',character_set_results='$charset',character_set_client=binary" : '';
            $serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',') . " sql_mode='' ") : '';
            $serverset && mysql_query("SET $serverset", $this->_dbh);
        }

        if ($this->_config['database'] && !@mysql_select_db($this->_config['database'], $this->_dbh)) {
            throw new KantException(sprintf('Can not use MySQL server or cannot use database.%s', mysql_error()));
        }
        $this->database = $this->_config['database'];
    }

    /**
     *
     * Get MySQL server info
     *
     * @return string the MySQL server version on success&return.falseforfailure;.
     */
    public function version() {
        if (!is_resource($this->_dbh)) {
            $this->_connect();
        }
        return mysql_get_server_info($this->_dbh);
    }

    /**
     * Close database connection
     */
    public function close() {
        if (is_resource($this->_dbh)) {
            @mysql_close($this->_dbh);
        }
    }

    /**
     * Regexp
     * @param type $key
     * @param type $type
     * @param type $value
     * @param type $split
     */
    public function whereRegexp($key, $value, $split = 'AND') {
        if (empty($key)) {
            return $this;
        }
        $where = $this->checkField($key) . ' REGEXP ' . $this->quote($value);
        $this->where .= ($this->where ? " $split " : '') . $where;
    }

    /**
     *
     * Execute SQL
     *
     * @param SQL string
     * @return resource
     */
    public function execute($sql, $method = '') {
        if (!is_resource($this->_dbh)) {
            $this->_connect();
        }
        $query = ($method == 'unbuffer') ? mysql_unbuffered_query($sql, $this->_dbh) : mysql_query($sql, $this->_dbh);
        if (!$query && $method != 'SILENT') {
            throw new KantException(sprintf("MySQL Query Error:%s,Error Code:%s", $sql, mysql_errno()));
        }
        $this->sqls[] = $sql;
        $this->queryCount++;
        return $query;
    }

    /**
     *  SQl query
     * @param type $sql
     * @param type $method
     * @return boolean
     * @throws KantException
     */
    public function query($sql, $method = 'SILENT') {
        $cacheSqlMd5 = 'sql_' . md5($sql);
        if ($this->ttl) {
            $rows = $this->cache->get($cacheSqlMd5);
            if (empty($rows)) {
                if (!is_resource($this->_dbh)) {
                    $this->_connect();
                }
                $query = ($method == 'unbuffer') ? mysql_unbuffered_query($sql, $this->_dbh) : mysql_query($sql, $this->_dbh);
                if (!$query && $method != 'SILENT') {
                    throw new KantException(sprintf("MySQL Query Error:%s,Error Code:%s", $sql, mysql_errno()));
                }
                if (is_resource($query) == false) {
                    return;
                }
                if (mysql_num_rows($query) == 0) {
                    return;
                }
                while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
                    $rows[] = $row;
                }
                $this->cache->set($cacheSqlMd5, $rows, $this->ttl);
            }
        } else {
            if (!is_resource($this->_dbh)) {
                $this->_connect();
            }
            $query = ($method == 'unbuffer') ? mysql_unbuffered_query($sql, $this->_dbh) : mysql_query($sql, $this->_dbh);
            if (!$query && $method != 'SILENT') {
                throw new KantException(sprintf("MySQL Query Error:%s,Error Code:%s", $sql, mysql_errno()));
            }
            if (is_resource($query) == false) {
                return;
            }
            if (mysql_num_rows($query) == 0) {
                return;
            }
            while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
                $rows[] = $row;
            }
            $this->cache->delete($cacheSqlMd5);
        }
        $this->sqls[] = $sql;
        $this->queryCount++;
        return $rows;
    }

    /**
     *
     * Get the ID generated in the last query
     *
     * @return int The ID generated for an AUTO_INCREMENT column by the previous
     * query on success, 0 if the previous
     * query does not generate an AUTO_INCREMENT value, or false if
     * no MySQL connection was established.
     */
    public function lastInsertId($primaryKey = null) {
        return ($id = mysql_insert_id($this->_dbh)) >= 0 ? $id : mysql_result($this->query("SELECT last_insert_id()"), 0);
    }

    /**
     *
     * Fetch a result row as an associative array
     *
     * @param resource resource
     * @return array an associative array of strings that corresponds to the fetched row, or
     * false if there are no more rows.
     */
    public function fetch($fetchMode = '', $clearVar = true) {
        $sql = $this->getSql(0);
        $result = $this->query($sql, $fetchMode);
        $this->cacheSql();
        if ($clearVar) {
            $this->clear();
        }
        return $result;
    }

    /**
     * Fetches the first column of the first row of the SQL result.
     * 
     * @param type $fetchMode
     * @param type $clearVar
     */
    public function fetchOne($clearVar = true) {
        if ($this->from) {
            $this->limit = 1;
        }
        $sql = $this->getSql(0);
        $query = mysql_query($sql, $this->_dbh);
        if (!$query) {
            throw new KantException(sprintf("MySQL Query Error:%s,Error Code:%s", $sql, mysql_errno()));
        }
        if (is_resource($query) == false) {
            return;
        }
        if (mysql_num_rows($query) == 0) {
            return;
        }
        $row = mysql_fetch_array($query, MYSQL_ASSOC);
        $this->sqls[] = $sql;
        $this->queryCount++;
        $this->cacheSql();
        if ($clearVar) {
            $this->clear();
        }
        return $row;
    }

    /**
     * 
     *  Get a result row as associative array from SQL query with easy method
     * 
     * @param string $select
     * @param string $from
     * @param string $where
     * @param string $groupBy
     * @param string $orderBy
     * @param string $limit
     * @return array
     */
    public function fetchEasy($select, $from, $where = null, $groupBy = null, $orderBy = null, $limit = null) {
        $this->select($select);
        $this->from($from);
        if ($where) {
            foreach ($where as $sk => $sv) {
                $this->where($sk, $sv);
            }
        }
        if ($groupBy) {
            $this->groupBy($groupBy);
        }
        if ($orderBy) {
            $this->orderBy($orderBy[0], $orderBy[1]);
        }
        if ($limit) {
            $this->limit($limit[0], $limit[1]);
        }
        return $this->fetch();
    }

    /**
     *  Insert Data
     * 
     * @param boolean $replace
     * @param boolean $clearVar
     * @return
     */
    public function insert($replace = false, $clearVar = true) {
        $sql = $this->insertSql($replace, 0);
        $this->execute($sql);
        $this->cacheSql();
        if ($clearVar) {
            $this->clear();
        }
        return $this->lastInsertId();
    }

    /**
     * Update Data
     * 
     * @param boolean $clearVar
     * @return 
     */
    public function update($clearVar = true) {
        $sql = $this->insertSql(true);
        $result = $this->execute($sql, 'unbuffer');
        $this->cacheSql();
        if ($clearVar) {
            $this->clear();
        }
        return $result;
    }

    /**
     * Delete Data
     * 
     * @param boolean $clearVar
     * @return
     */
    public function delete($clearVar = true) {
        $sql = $this->deleteSql();
        $result = $this->execute($sql);
        $this->cacheSql();
        if ($clearVar) {
            $this->clear();
        }
        return $result;
    }

    /**
     *
     * Get the number of rows in a result
     *
     * @param clear_var boolean
     * @return integer The number of rows in a result set on success&return.falseforfailure;.
     */
    public function count($clearVar = true) {
        $sql = $this->getSql(1);
        $row = $this->query($sql);
        $this->cacheSql();
        if ($clearVar) {
            $this->clear();
        }
        return $row[0]['count'];
    }

    /**
     * 
     * Start transaction
     */
    public function begin() {
        $this->execute('SET AUTOCOMMIT=0');
        $this->execute('BEGIN');
    }

    /**
     * 
     * Commit
     */
    public function commit() {
        $this->execute('COMMIT');
    }

    /**
     * 
     * Rollback
     */
    public function rollback() {
        $this->execute('ROLLBACK');
    }

    /**
     * Clone table structure and indexes
     * 
     * @param string $table
     * @param string $newTable
     * @return
     */
    public function cloneTable($table, $newTable) {
        $sql = "CREATE TABLE  $newTable(LIKE $table)";
        $result = $this->execute($sql);
        return $result;
    }

    /**
     * Determine whether table exists
     * 
     * @param string $table
     * @return boolean
     */
    public function tableExists($table) {
        $tables = $this->listTables();
        return in_array($table, $tables) ? true : false;
    }

    /**
     * List tables
     * 
     * @return
     */
    public function listTables() {
        $tables = array();
        $row = $this->query("SHOW TABLES");
        if (!empty($row)) {
            foreach ($row as $val) {
                $val = array_values($val);
                $tables[] = $val[0];
            }
        }
        return $tables;
    }

}
