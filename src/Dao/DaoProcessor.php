<?php
/**
 * User: isliang
 * Date: 2019-09-13
 * Time: 16:02
 * Email: yesuhuangsi@163.com
 **/

namespace Ipf\Dao;

use Ipf\Constant\CommConst;
use Ipf\Factory\LogFactory;
use Ipf\Pool\MysqlPool;
use Ipf\Utils\SqlBuilderUtils;

class DaoProcessor
{
    /**
     * @var DaoInfo
     */
    private $dao_info;

    /**
     * @var MysqlPool
     */
    private $pool_read;

    /**
     * @var MysqlPool
     */
    private $pool_write;

    private $read_type_sql = [
        'SELECT',
        'DESC',
    ];

    public function __construct($dao_info, $pool_read, $pool_write)
    {
        $this->dao_info = $dao_info;
        $this->pool_read = $pool_read;
        $this->pool_write = $pool_write;
    }

    private function executeSql($sql, $params, $force_write = false, $callback = null)
    {
        $type = $this->getSqlType($sql);
        if (!in_array($type, $this->read_type_sql) || $force_write) {
            $pool = $this->pool_write;
        } else {
            $pool = $this->pool_read;
        }
        LogFactory::getInstance('sql')->info($sql . ':' . json_encode($params));
        return $pool->query($sql, $params, $type, $callback);
    }

    /**
     * @param $sql
     * @return string
     */
    private function getSqlType($sql)
    {
        return strtoupper(substr($sql, 0, strpos($sql, ' ')));
    }

    /**
     * @param $where
     * @param $offset
     * @param $limit
     * @param $fields
     * @param $order
     * @param $force_write
     * @param $callback
     * @return array|bool|int|string
     * @throws \Exception
     * SELECT * FROM test WHERE id > 1 order by id desc limit 1,10
     */
    public function find($where, $offset = CommConst::DEFAULT_SQL_OFFSET, $limit = CommConst::DEFAULT_SQL_LIMIT, $order = null, $fields = null, $force_write = false, $callback = null)
    {
        $params = [];
        $where_sql = '';
        $where_info = SqlBuilderUtils::buildWhere($where);
        if (!empty($where_info)) {
            $where_sql = $where_info['sql'];
            $params = array_merge($params, $where_info['values']);
        }

        $offset = is_null($offset) ? CommConst::DEFAULT_SQL_OFFSET : $offset;
        $limit = is_null($limit) ? CommConst::DEFAULT_SQL_LIMIT : $limit;

        $sql = "SELECT " . SqlBuilderUtils::buildFields($fields) .
            " FROM " . $this->dao_info->getTableName() .
            $where_sql .
            SqlBuilderUtils::buildOrder($order) .
            " LIMIT {$offset}, {$limit}";
        return $this->executeSql($sql, $params, $force_write, $callback);
    }

    public function findOne($where, $force_write = false)
    {
        $callback = function ($params) {
            return is_array($params) ? current($params) : null;
        };
        $this->find($where, 0, 1, null, null, $force_write, $callback);
    }

    public function findByIds($pks, $force_write = false, $callback = null)
    {
        $this->find([$this->dao_info->getPk() => $pks], null, null, null, null, $force_write, $callback);
    }


    public function findById($pk, $force_write = false)
    {
        $callback = function ($params) {
            return is_array($params) ? current($params) : null;
        };
        $this->findByIds([$pk], $force_write, $callback);
    }

    /**
     * @param $params
     * @param $is_ignore
     * @return array|bool|int|string
     * @throws \Exception
     * INSERT INTO test (`id`,`user_id`,`title`) values (?,?,?),(?,?,?)
     */
    public function insert($params, $is_ignore = false)
    {
        if (empty($params) || !is_array($params)) {
            return false;
        }
        $values = [];
        $fields = [];
        if (is_array(current($params))) {
            $fields_get = true;
            foreach ($params as $param) {
                foreach ($param as $k => $v) {
                    if ($fields_get) {
                        $fields[] = $k;
                    }
                    $values[] = $v;
                }
                $fields_get = false;
            }
            $value_sql = '(' . implode(',', array_fill(0, count($fields), '?')) . ')';
            $value_sql = implode(',', array_fill(0, count($params), $value_sql));
        } else {
            foreach ($params as $k => $v) {
                $fields[] = $k;
                $values[] = $v;
            }
            $value_sql = '(' . implode(',', array_fill(0, count($fields), '?')) . ')';
        }

        $ignore = $is_ignore ? 'IGNORE' : '';
        $sql = "INSERT {$ignore} INTO " . $this->dao_info->getTableName() .
            '(' . SqlBuilderUtils::buildFields($fields) . ') values '
            . $value_sql;

        return $this->executeSql($sql, $values);
    }

    /**
     * @param $params
     * @return bool
     * INSERT INTO test (`id`,`user_id`,`title`) values (?,?,?) ON DUPLICATE KEY UPDATE `id` = ?, `user_id` = ? ,`title` = ?
     */
    public function insertOnDuplicateKeyUpdate($params)
    {
        if (empty($params) || !is_array($params)) {
            return false;
        }
        $values = [];
        $fields = [];
        if (is_array(current($params))) {
            return false;
        }
        foreach ($params as $k => $v) {
            $fields[] = $k;
            $values[] = $v;
        }
        $value_sql = '(' . implode(',', array_fill(0, count($fields), '?')) . ')';
        $sql = "INSERT INTO " . $this->dao_info->getTableName() .
            '(' . SqlBuilderUtils::buildFields($fields) . ') values '
            . $value_sql . ' ON DUPLICATE KEY UPDATE ' . SqlBuilderUtils::buildWhereFields($fields);
        return $this->executeSql($sql, array_merge($values, $values));
    }

    /**
     * @param $params
     * @param $where
     * @return array|bool|int|string
     * @throws \Exception
     * UPDATE test SET `title` = ?, `user_id` = ? where id = 1
     */
    public function update($params, $where)
    {
        if (empty($params) || !is_array($where)) {
            return false;
        }
        $fields = [];
        $values = [];
        foreach ($params as $k => $v) {
            $fields[] = $k;
            $values[] = $v;
        }
        $where_sql = '';
        $where_info = SqlBuilderUtils::buildWhere($where);
        if (!empty($where_info)) {
            $where_sql = $where_info['sql'];
            $values = array_merge($values, $where_info['values']);
        }

        $sql = "UPDATE " . $this->dao_info->getTableName() . " SET "
            . SqlBuilderUtils::buildWhereFields($fields) . $where_sql;
        return $this->executeSql($sql, $values);
    }

    /**
     * @param $where
     * @return array|bool|int|string
     * @throws \Exception
     * DELETE FROM test WHERE id = 1
     */
    public function delete($where)
    {
        $where_info = SqlBuilderUtils::buildWhere($where);
        if (empty($where_info)) {
            return false;
        }
        $where_sql = $where_info['sql'];
        $params = $where_info['values'];
        $sql = "DELETE FROM " . $this->dao_info->getTableName() . $where_sql;
        return $this->executeSql($sql, $params);
    }

    public function getCount($where)
    {
        $field = "COUNT(1) as c";
        $callback = function ($params) {
            $data = is_array($params) ? current($params) : [];
            return $data['c'] ?: 0;
        };
        $this->find($where, 0, 1, null, $field, false, $callback);
    }

    /**
     * @param $where
     * @param $field
     * @param $count
     * @param bool $increase
     * @return bool
     * increase or decrease field by count (if param increase is false)
     */
    public function increase($where, $field, $count, $increase = true)
    {
        if (empty($field) || empty($count) || !is_array($where)) {
            return false;
        }
        $count = abs(intval($count));
        $values = [];
        $where_sql = '';
        $where_info = SqlBuilderUtils::buildWhere($where);
        if (!empty($where_info)) {
            $where_sql = $where_info['sql'];
            $values = $where_info['values'];
        }

        $sql = "UPDATE " . $this->dao_info->getTableName() . " SET "
            . SqlBuilderUtils::buildUpdateFields([$field => $count], $increase) . $where_sql;
        return $this->executeSql($sql, $values);
    }
}
