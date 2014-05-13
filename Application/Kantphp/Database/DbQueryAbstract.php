<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
!defined('IN_KANT') && exit('Access Denied');

/**
 * Database query abstract class
 * 
 * @access public
 * @abstract
 * @since version 1.1
 */
abstract class DbQueryAbstract extends Base {

    public $dbTablepre = '';
    protected $table = '';
    protected $where = '';
    protected $set = '';
    protected $select = '';
    protected $from = '';
    protected $groupBy = '';
    protected $orderBy = '';
    protected $limit = '';
    protected $ttl = 0;
    protected $varFields = array('set', 'select', 'from', 'where', 'groupBy', 'orderBy', 'limit');
    protected $cacheFields = array('set' => '', 'select' => '', 'from' => '', 'where' => '', 'groupBy' => '', 'orderBy' => '', 'limit' => '');
    protected $sqls;
    protected $queryCount;

    /**
     *
     * Get a table
     *
     * @param tablename string
     */
    public function getTable($tablename) {
        return $this->dbTablepre . $tablename;
    }

    /**
     *
     * Set from in a SQL
     *
     * @param tablename string
     * @param asname string
     */
    public function from($tablename, $asname = null) {
        $this->from = $this->getTable($tablename) . ($asname ? " $asname" : '');
        return $this;
    }

    /**
     * Join table
     * 
     * @param string/array $join
     */
    public function join($join) {
        $joinStr = '';
        if (!empty($join)) {
            if (is_array($join)) {
                foreach ($join as $key => $_join) {
                    if (false !== stripos(strtoupper($_join), 'JOIN')) {
                        $joinStr .= ' ' . $_join;
                    } else {
                        $joinStr .= ' LEFT JOIN ' . $_join;
                    }
                }
            } else {
                $joinStr .= ' LEFT JOIN ' . $join;
            }
        }
        $this->from .= $joinStr;
        return $this;
    }

    /**
     *
     * Set query field
     *
     * @param fields string
     * @param asname string
     * @param fun string
     */
    public function select($fields, $asname = null, $fun = null) {
        $split = $this->select ? ',' : '';
        if (is_string($fields)) {
            if ($fun) {
                $fields = str_replace('?', $this->checkField($fields), $fun);
            } else {
                $fields = $this->checkField($fields);
            }
            $select = $fields . ($asname ? (' AS ' . $this->checkField($asname)) : '');
            $this->select .= $split . $select;
        } elseif (is_array($fields)) {
            foreach ($fields as $key) {
                $this->select .= $split . $this->checkField($fields);
                $split = ',';
            }
        }
        return $this;
    }

    /**
     *
     * Set the field's value
     *
     * @param key string
     * @param value string
     */
    public function set($key, $value = null) {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            $this->checkField($key);
            $this->set[$key] = $this->quote($value);
        }
        return $this;
    }

    /**
     *
     * Set the field's value cumulative
     *
     * @param key string
     * @param value string
     */
    public function setAdd($key, $value = 1) {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->setAdd($k, $v);
            }
        } else {
            $this->checkField($key);
            $this->set[$key] = $key . '+' . $this->quote($value);
        }
        return $this;
    }

    /**
     *
     * Set the field's value regressive
     *
     * @param key string
     * @param value string
     */
    public function setDec($key, $value = 1) {
        if ($value) {
            $this->checkField($key);
            $this->set[$key] = $key . '-' . $this->quote($value);
        }
        return $this;
    }

    /**
     *
     * Set query clause WHERE field = value
     *
     * @param key string
     * @param value string
     * @param split string
     */
    public function where($key, $value = '', $split = 'AND') {
        if (empty($key)) {
            return $this;
        }
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                if (is_array($v) && count($v) == 2 && is_array($v[1])) {
                    $fun = $v[0];
                    $args = array_merge(array($k), $v[1]);
                    call_user_func_array(array($this, $fun), $args);
                } else {
                    $this->where($k, $v, $split);
                }
            }
        } elseif (is_array($value)) {
            $this->whereIn($key, $value, $split);
        } else {
            $where = $this->checkField($key) . " = " . $this->quote($value);
            $this->where .= ($this->where ? " $split " : '') . $where;
        }
        return $this;
    }

    /**
     *
     * Set query clause WHERE with complex expression
     *
     * @param exp string
     * @param key string
     * @param value string
     * @param split string
     * @example whereExp(" ? = ? )", 'endtime', 0, 'OR');
     */
    public function whereExp($exp, $key, $value, $split = 'AND') {
        if (empty($key)) {
            return $this;
        }
        if (is_array($value)) {
            foreach ($value as $_k => $val) {
                $value[$_k] = $this->quote($val);
            }
            $value = implode(",", $value);
        }
        $where = sprintf(str_replace(" ? ", " %s ", $exp), $this->checkField($key), $value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    /**
     *
     * Set query clause WHERE field != 'value'
     *
     * @param key string
     * @param value string
     * @param split string
     */
    public function whereNotEqual($key, $value, $split = 'AND') {
        if (empty($key)) {
            return $this;
        }
        $where = $this->checkField($key) . " != " . $this->quote($value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    /**
     *
     * Set query clause WHERE field >= 'value' or field > 'value'
     *
     * @param key string
     * @param value string
     * @param equal integer
     * @param split string
     * @example whereMore('total', '40000', 1, 'OR')
     */
    public function whereMore($key, $value, $equal = 1, $split = 'AND') {
        if (empty($key)) {
            return $this;
        }
        $mark = $equal ? '>=' : '>';
        $where = $this->chekc_field($key) . $mark . $this->quote($value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    /**
     *
     * Set query clause WHERE field <= 'value' OR field < 'value'
     *
     * @param key string
     * @param value string
     * @param equal integer
     * @param split string
     * @example whereLess('price', '200', 1, 'AND')
     */
    public function whereLess($key, $value, $equal = 1, $split = 'AND') {
        if (empty($key)) {
            return $this;
        }
        $mark = $equal ? '<=' : '<';
        $where = $this->checkField($key) . $mark . $this->quote($value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    /**
     *
     * Set query clause WHERE field IN value
     *
     * @param key string
     * @param values string
     * @param split string
     */
    public function whereIn($key, $values, $split = 'AND') {
        if (count($values) == 0) {
            return $this;
        }
        if (count($values) == 1) {
            return $this->where($key, $values[0], $split);
        }
        foreach ($values as $_key => $_val) {
            $values[$_key] = $this->quote($_val);
        }
        $where = $this->checkField($key) . " IN (" . implode(",", $values) . ")";
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    /**
     *
     * Set query clause WHERE field NOT IN value
     *
     * @param key string
     * @param values string
     * @param split string
     */
    public function whereNotIn($key, $values, $split = 'AND') {
        if (empty($key)) {
            return $this;
        }
        if (count($values) == 0) {
            return $this;
        }
        foreach ($values as $_key => $_val) {
            $values[$key] = $this->quote($_val);
        }
        $where = $this->checkField($key) . " NOT IN (" . implode(",", $values) . ")";
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    /**
     *
     * Set query clause WHERE field BETWEEN $begin AND $end
     *
     * @param key string
     * @param begin string
     * @param end string
     * @param split string
     */
    public function whereBetweenAnd($key, $begin, $end, $split = 'AND') {
        if (empty($key)) {
            return $this;
        }
        $where = $this->checkField($key) . " BETWEEN " . $this->quote($begin) . " AND " . $this->quote($end);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    /**
     *
     * Set query clause WHERE field LIKE value
     *
     * @param key string
     * @param value string
     * @param split string
     * @param kh string
     */
    public function whereLike($key, $value, $split = 'AND', $kh = '') {
        if (empty($key)) {
            return $this;
        }
        $where = $this->checkField($key) . " LIKE " . $this->quote($value);
        $this->where .= ($this->where ? " $split " : '') . ($kh == '(' ? '(' : '') . $where . ($kh == ')' ? ')' : '');
        return $this;
    }

    /**
     *
     * Set query clause WHERE ... OR field = value
     *
     * @param key string
     * @param value string
     */
    public function whereOr($key, $value) {
        if (empty($key)) {
            return $this;
        }
        $where = $this->checkField($key) . " = " . $this->quote($value);
        $this->where .= ($this->where ? ' OR ' : '') . $where;
        return $this;
    }

    /**
     *
     * Set query clause WHERE CONCAT(field,field2) LIKE '100%'
     *
     * @param keys string
     * @param value string
     * @param split string
     * @param kh string
     */
    public function whereConcatLike($keys, $value, $split = 'AND', $kh = '') {
        if (empty($key)) {
            return $this;
        }
        if (is_string($keys)) {
            $keys = explode(',', $keys);
        }
        foreach ($keys as $k => $v) {
            $keys[$k] = $this->checkField(trim($v));
        }
        $where = "CONCAT(" . implode(',', $keys) . ") LIKE " . $this->quote($value);
        $this->where .= ($this->where ? " $split " : '') . ($kh == '(' ? '(' : '') . $where . ($kh == ')' ? ')' : '');
        return $this;
    }

    /**
     *
     * Set query clause WHERE EXISTS
     *
     * @param sql string
     * @param split string
     * @param kh string
     */
    public function wehreExist($sql, $split = 'AND', $kh = '') {
        if (empty($sql)) {
            return $this;
        }
        $where = 'exists(' . $this->getTable($sql) . ')';
        $this->where .= ($this->where ? " $split " : '') . ($kh == '(' ? '(' : '') . $where . ($kh == ')' ? ')' : '');
    }

    /**
     *
     * Set query clause GROUP BY
     *
     * @param groups string
     */
    public function groupBy($groups) {
        if (empty($groups)) {
            return $this;
        } elseif (is_array($groups)) {
            foreach ($groups as $key => $val) {
                $groups[$key] = $this->checkField($val);
            }
            $groupBy = implode(',', $groups);
        } elseif (is_string($groups)) {
            $groupBy = $this->checkField($groups);
        }
        $this->groupBy .= ($this->groupBy ? ',' : '') . $groupBy;
    }

    /**
     *
     * Set query clause ORDER BY
     *
     * @param field string
     * @param type string
     */
    public function orderBy($field, $type = 'ASC') {
        if (empty($field)) {
            return $this;
        } elseif (is_string($field)) {
            if (strpos($field, ' ')) {
                list($field, $type) = explode(' ', $field);
            }
            $orderBy = $this->checkField($field) . ($type == 'DESC' ? (' ' . $type) : '');
        } elseif (is_array($field)) {
            $split = '';
            foreach ($field as $key => $val) {
                $orderBy .= $split . $this->checkField($key) . ($val == 'DESC' ? (' ' . $val) : '');
                $split = ',';
            }
        }
        $this->orderBy .= ($this->orderBy ? ',' : '') . $orderBy;
        return $this;
    }

    /**
     *
     * Set query clause LIMIT
     *
     * @param start integer
     * @param offset integer
     */
    public function limit($start, $offset = '') {
        $start = (int) $start;
        $offset = (int) $offset;
        if (!$start && !$offset) {
            return;
        } elseif (!$offset) {
            $this->limit = "$start OFFSET 0 ";
        } else {
            $this->limit = "$offset OFFSET $start ";
        }
        return $this;
    }

    public function ttl($ttl) {
        if ($ttl === true) {
            //do nothing
        } elseif ($ttl > 0) {
            $this->ttl = $ttl;
        }
        return $this;
    }

    /**
     *
     * Combine a SQL
     *
     * @param get_count_sql boolean
     * @return SQL string
     */
    public function getSql($getCountSql = false) {
        foreach ($this->varFields as $v) {
            $$v = $v;
        }
        if (!$this->from) {
            throw new KantException('Invalid SQL: FROM');
        }
        $sql = "SELECT " . ($getCountSql ? "COUNT(*) as count" : ($this->select ? $this->select : "*")) .
                " FROM " . $this->from .
                ($this->where ? " WHERE " . $this->where : "") .
                ($getCountSql ? '' : ($this->groupBy ? " GROUP BY " . $this->groupBy : "")) .
                ($getCountSql ? '' : ($this->orderBy ? " ORDER BY " . $this->orderBy : "")) .
                ($getCountSql ? '' : ($this->limit ? " LIMIT " . $this->limit : ""));
        return $sql;
    }

    /**
     *
     * Combine an insert SQL
     *
     * @param replace boolean
     * @param update boolean
     * @return string
     */
    public function insertSql($update = false) {
        foreach ($this->varFields as $v) {
            $$v = $v;
        }
        if (empty($this->from)) {
            throw new KantException('Invalid sql: FROM(INSERT)');
        }
        if (empty($this->set)) {
            throw new KantException('Invalid sql: SET(INSERT)');
        }
        if ($update) {
            $split = $setsql = '';
            foreach ($this->set as $key => $val) {
                $setsql .= $split . $key . ' = ' . $val;
                $split = ', ';
            }
            $sql = "UPDATE " . $this->from . " SET " . $setsql . ($this->where ? " WHERE " . $this->where : "");
        } else {
            $setsql = $setkey = $setval = '';
            $setkey = implode(',', array_keys($this->set));
            $setval = implode(',', array_values($this->set));
            $sql = "INSERT INTO " . $this->from . "($setkey)  VALUES ($setval)";
        }
        return $sql;
    }

    /**
     *
     * Combine delete SQL
     *
     */
    public function deleteSql() {
        foreach ($this->varFields as $v) {
            $$v = $v;
        }
        if (!$this->from) {
            throw new KantException('Invalid SQL: FROM(DELETE)');
        }
        $sql = "DELETE FROM " . $this->from . ($this->where ? " WHERE " . $this->where : "");
        return $sql;
    }

    /**
     *
     * SQL roll back,the cached SQL is reset to be the current effective SQL
     *
     * @param vars string
     */
    public function sqlRollback($vars = null) {
        $arr = $vars ? ((is_array($vars) ? $vars : explode(',', $vars))) : $this->varFields;
        foreach ($arr as $v) {
            $this->$v = $this->cacheFields[$v];
        }
        return $this;
    }

    /**
     *
     * Clear SQL cache
     *
     * @param name string
     */
    public function clear($name = 'ALL') {
        if ($name == 'ALL') {
            foreach ($this->varFields as $v) {
                $this->$v = '';
            }
        } elseif (isset($name[$this->varFields])) {
            $this->$name = '';
        }
    }

    /**
     *
     * Cache SQL clause data
     *
     * @param clear boolean
     */
    public function cacheSql($clear = false) {
        foreach ($this->varFields as $v) {
            $this->cacheFields[$v] = $this->$v;
            if ($clear) {
                $this->$v = '';
            }
        }
    }

    public function getLastSqls() {
        return $this->sqls;
    }

    /**
     *
     * Check field
     *
     * @param string $field
     * @return string
     */
    public function checkField($field) {
        if (preg_match("/[\'\\\"\<\>\/]+/", $field)) {
            throw new KantException(sprintf('Invalid field:%s', $field));
            return;
        }
        return $field;
    }

    /**
     * Safely quotes a value for an SQL statement.
     * 
     * @param mixed $str
     */
    public function quote($str) {
        switch (gettype($str)) {
            case 'string':
                $str = "'" . addslashes($str) . "'";
                break;
            case 'boolean':
                $str = ($str === false) ? 0 : 1;
                break;
            default:
                $str = ($str === null) ? 'null' : $str;
                break;
        }
        return $str;
    }

}

?>
