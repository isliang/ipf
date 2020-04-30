<?php
/**
 * User: isliang
 * Date: 2019-09-13
 * Time: 16:02
 * Email: yesuhuangsi@163.com
 **/

namespace Ipf\Dao;

use Ipf\Constant\CommConst;
use Ipf\Exception\MethodNotExistException;
use Ipf\Factory\MemcachedFactory;
use Ipf\Factory\RedisFactory;

class CachedDaoProcessor
{
    /**
     * @var DaoProcessor
     */
    private $processor;
    /**
     * @var DaoInfo
     */
    private $dao_info;

    /**
     * @var \Memcached
     */
    private $memcache;

    /**
     * @var \Predis\Client
     */
    private $redis;

    /**
     * @var CacheTagDao
     */
    private $cache_tag_dao;

    public function __construct($dao_info, $pool_read, $pool_write)
    {
        $this->dao_info = $dao_info;
        $this->memcache = MemcachedFactory::getInstance();
        $this->redis = RedisFactory::getInstance();
        $this->processor = new DaoProcessor($dao_info, $pool_read, $pool_write);
        $this->cache_tag_dao = CacheTagDao::getInstance();
    }

    private function buildMemcachedKey()
    {
        //表名+更新时间+查询参数
        $arr = [
            $this->dao_info->getDbName(),
            $this->dao_info->getTableName(),
            $this->getUpdateTime(),
            json_encode(func_get_args()),
        ];

        return implode('|', $arr);
    }

    private function generateUpdateTime()
    {
        return intval(microtime(true) * 1000);
    }

    private function getUpdateTime()
    {
        $key = $this->getRedisKey();
        $update_time = $this->redis->get($key);
        if (empty($info)) {
            $info = $this->cache_tag_dao->findOne([
                'database' => $this->dao_info->getDbName(),
                'table' => $this->dao_info->getTableName(),
            ]);
            if (empty($info)) {
                $info = [
                    'database' => $this->dao_info->getDbName(),
                    'table' => $this->dao_info->getTableName(),
                    'update_time' => $this->generateUpdateTime(),
                ];
                $this->cache_tag_dao->insert($info);
            }
            $this->redis->set($key, $info['update_time']);
            return $info['update_time'];
        }
        return  $update_time;
    }

    private function getRedisKey()
    {
        return $this->dao_info->getDbName() . '|' . $this->dao_info->getTableName();
    }

    private function updateUpdateTime()
    {
        $key = $this->getRedisKey();
        $info = [
            'database' => $this->dao_info->getDbName(),
            'table' => $this->dao_info->getTableName(),
            'update_time' => $this->generateUpdateTime(),
        ];
        $this->cache_tag_dao->insertOnDuplicateKeyUpdate($info);
        $this->redis->set($key, $info['update_time']);
        return  $info;
    }
    /**
     * 缓存策略一
     * memcache里缓存查询结果 key由database table
     * 表更新时间存放在db中，并在redis中缓存
     * 更新操作，包括插入和删除，成功之后，更新表更新时间(db+redis)
     * 频繁的插入更改的情况下，缓存效率较低
     *
     *
     */

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
        $memcached_key = call_user_func_array([$this, 'buildMemcachedKey'], func_get_args());
        $res = $this->memcache->get($memcached_key);
        if ($res) {
            return json_decode($res, true);
        } else {
            $info = $this->processor->find($where, $offset, $limit, $order, $fields, $force_write, $callback);
            $this->memcache->set($memcached_key, json_encode($info));
            return $info;
        }
    }

    public function findOne($where, $force_write = false)
    {
        $callback = function ($params) {
            return is_array($params) ? current($params) : null;
        };
        return $this->find($where, 0, 1, null, null, $force_write, $callback);
    }

    public function findByIds($pks, $force_write = false, $callback = null)
    {
        return $this->find([$this->dao_info->getPk() => $pks], null, null, null, null, $force_write, $callback);
    }


    public function findById($pk, $force_write = false)
    {
        $callback = function ($params) {
            return is_array($params) ? current($params) : null;
        };
        return $this->findByIds([$pk], $force_write, $callback);
    }

    public function findCount($where, $force_write = false)
    {
        $field = "COUNT(1) as c";
        $callback = function ($params) {
            $data = is_array($params) ? current($params) : [];
            return $data['c'] ?: 0;
        };
        return $this->find($where, 0, 1, null, $field, $force_write, $callback);
    }

    public function __call($method, $args)
    {
        if (!method_exists($this->processor, $method)) {
            throw new MethodNotExistException(get_called_class(), $method);
        }
        $res = call_user_func_array([$this->processor, $method], $args);
        if ($res) {
            $this->updateUpdateTime();
        }
        return $res;
    }
}
