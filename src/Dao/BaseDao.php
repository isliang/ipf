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
    private static $pool_read;

    /**
     * @var MysqlPool
     */
    private static $pool_write;

    private static $pool_initialized = false;

    /**
     * @return DaoInfo
     */
    abstract public function getDaoInfo();

    public function __construct()
    {
        $this->dao_info = $this->getDaoInfo();
        if (empty(self::$pool_initialized)) {
            self::$pool_read = new MysqlPool($this->dao_info->getSlaveDsn());
            self::$pool_write = new MysqlPool($this->dao_info->getMasterDsn());
            self::$pool_initialized = true;
        }
        $this->processor = new DaoProcessor($this->dao_info, self::$pool_read, self::$pool_write);
    }

    public function find($where, $offset = CommConst::DEFAULT_SQL_OFFSET, $limit = CommConst::DEFAULT_SQL_LIMIT, $order = null, $fields = null, $force_write = false)
    {
        $this->processor->find($where, $offset, $limit, $order, $fields, $force_write);
    }

    public function findOne($where, $force_write = false)
    {
        $this->processor->findOne($where, $force_write);
    }

    public function findByIds($pks, $force_write = false)
    {
        $this->processor->findByIds($pks, $force_write);
    }

    public function findById($pk, $force_write = false)
    {
        $this->processor->findById($pk, $force_write);
    }

    public function insert($params, $is_ignore = false)
    {
        $this->processor->insert($params, $is_ignore);
    }

    public function insertOnDuplicateKeyUpdate($params)
    {
        $this->processor->insertOnDuplicateKeyUpdate($params);
    }

    public function update($params, $where)
    {
        $this->processor->update($params, $where);
    }

    public function increase($where, $field, $count, $increase = true)
    {
        $this->processor->increase($where, $field, $count, $increase);
    }

    public function delete($where)
    {
        $this->processor->delete($where);
    }

    public function getCount($where)
    {
        $this->processor->getCount($where);
    }
}