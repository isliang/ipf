<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 12:14
 * Email: yesuhuangsi@163.com
 **/

namespace Ipf\Dao;

use Ipf\Constant\CommConst;
use Ipf\Exception\MethodNotExistException;
use Ipf\Pool\MysqlPool;

/**
 * Class BaseDao
 * @package Ipf\Dao
 * @method find($where, $offset = CommConst::DEFAULT_SQL_OFFSET, $limit = CommConst::DEFAULT_SQL_LIMIT, $order = null, $fields = null, $force_write = false)
 * @method findOne($where, $force_write = false)
 * @method findByIds($pks, $force_write = false)
 * @method findById($pk, $force_write = false)
 * @method findCount($where)
 * @method insert($params, $is_ignore = false)
 * @method insertOnDuplicateKeyUpdate($params)
 * @method update($params, $where)
 * @method increase($where, $field, $count, $increase = true)
 * @method delete($where)
 */
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

    private static $instance = [];

    /**
     * @return DaoInfo
     */
    abstract public function getDaoInfo();

    public function __construct()
    {
        $this->dao_info = $this->getDaoInfo();
        $this->pool_read = new MysqlPool($this->dao_info->getSlaveDsn());
        $this->pool_write = new MysqlPool($this->dao_info->getMasterDsn());
        $this->processor = $this->dao_info->isSkipCache() ?
            new DaoProcessor($this->dao_info, $this->pool_read, $this->pool_write) :
            new CachedDaoProcessor($this->dao_info, $this->pool_read, $this->pool_write)
        ;
    }

    /**
     * @return BaseDao
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if (empty(self::$instance[$class])) {
            self::$instance[$class] = new $class;
        }
        return self::$instance[$class];
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws MethodNotExistException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->processor, $name)) {
            return call_user_func_array([$this->processor, $name], $arguments);
        }
        throw new MethodNotExistException(get_called_class(), $name);
    }
}