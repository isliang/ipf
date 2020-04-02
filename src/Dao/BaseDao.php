<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 12:14
 * Email: yesuhuangsi@163.com
 **/

namespace Ipf\Dao;

use Ipf\Constant\CommConst;
use Ipf\Pool\MysqlPool;

abstract class BaseDao
{
    /**
     * @var DaoInfo
     */
    private $dao_info;

    /**
     * @var DaoProcessor
     */
    private $processor;

    /**
     * @var MysqlPool
     */
    private $pool_read;

    /**
     * @var MysqlPool
     */
    private $pool_write;

    private static $instance = null;

    /**
     * @return DaoInfo
     */
    abstract public function getDaoInfo();

    public function __construct()
    {
        $this->dao_info = $this->getDaoInfo();
        $this->pool_read = new MysqlPool($this->dao_info->getSlaveDsn());
        $this->pool_write = new MysqlPool($this->dao_info->getMasterDsn());
        $this->processor = new DaoProcessor($this->dao_info, $this->pool_read, $this->pool_write);
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            $class = get_called_class();
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function find($where, $offset = CommConst::DEFAULT_SQL_OFFSET, $limit = CommConst::DEFAULT_SQL_LIMIT, $order = null, $fields = null, $force_write = false)
    {
        return $this->processor->find($where, $offset, $limit, $order, $fields, $force_write);
    }

    public function findOne($where, $force_write = false)
    {
        return $this->processor->findOne($where, $force_write);
    }

    public function findByIds($pks, $force_write = false)
    {
        return $this->processor->findByIds($pks, $force_write);
    }

    public function findById($pk, $force_write = false)
    {
        return $this->processor->findById($pk, $force_write);
    }

    public function insert($params, $is_ignore = false)
    {
        return $this->processor->insert($params, $is_ignore);
    }

    public function insertOnDuplicateKeyUpdate($params)
    {
        return $this->processor->insertOnDuplicateKeyUpdate($params);
    }

    public function update($params, $where)
    {
        return $this->processor->update($params, $where);
    }

    public function increase($where, $field, $count, $increase = true)
    {
        return $this->processor->increase($where, $field, $count, $increase);
    }

    public function delete($where)
    {
        return $this->processor->delete($where);
    }

    public function getCount($where)
    {
        return $this->processor->getCount($where);
    }
}