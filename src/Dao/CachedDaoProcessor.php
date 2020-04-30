<?php
/**
 * User: isliang
 * Date: 2019-09-13
 * Time: 16:02
 * Email: yesuhuangsi@163.com
 **/

namespace Ipf\Dao;

use Ipf\Constant\CommConst;
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

    public function __construct($dao_info, $pool_read, $pool_write)
    {
        $this->dao_info = $dao_info;
        $this->memcache = MemcachedFactory::getInstance();
        $this->redis = RedisFactory::getInstance();
        $this->processor = new DaoProcessor($dao_info, $pool_read, $pool_write);
    }

    public function buildMemcachedKey()
    {
        //表名+更新时间+查询参数
        $arr = [
            $this->dao_info->getDbName(),
            $this->dao_info->getTableName(),
            'method',
            $this->getUpdateTime(),
            json_encode(func_get_args()),
        ];

        return implode('|', $arr);
    }

    public function getUpdateTime()
    {
        $key = $this->dao_info->getDbName() . '|' .
            $this->dao_info->getTableName();
        $info = $this->redis->get($key);
        if (empty($info)) {
            $cache_tag_dao = CacheTagDao::getInstance();
            $info = $cache_tag_dao->findOne([
                'database' => $this->dao_info->getDbName(),
                'table' => $this->dao_info->getTableName(),
            ]);
            if (empty($info)) {
                $info = [
                    'database' => $this->dao_info->getDbName(),
                    'table' => $this->dao_info->getTableName(),
                    'update_time' => intval(microtime(true) * 1000),
                ];
                $cache_tag_dao->insert($info);
            }
            $this->redis->set($key, $info['update_time']);
            return $info['update_time'];
        }
        return  $info;
    }

    public function updateUpdateTime()
    {
        $key = $this->dao_info->getDbName() . '|' .
            $this->dao_info->getTableName();
        $cache_tag_dao = CacheTagDao::getInstance();
        $info = [
            'database' => $this->dao_info->getDbName(),
            'table' => $this->dao_info->getTableName(),
            'update_time' => intval(microtime(true) * 1000),
        ];
        $cache_tag_dao->insertOnDuplicateKeyUpdate($info);
        $this->redis->set($key, $info['update_time']);
        return  $info;
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

    public function getCount($where)
    {
        $memcached_key = call_user_func_array([$this, 'buildMemcachedKey'], func_get_args());
        $res = $this->memcache->get($memcached_key);
        if ($res) {
            return json_decode($res, true);
        } else {
            $info = $this->processor->getCount($where);
            $this->memcache->set($memcached_key, json_encode($info));
            return $info;
        }
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
        $res = $this->processor->insert($params, $is_ignore);
        if ($res) {
            $this->updateUpdateTime();
        }
        return $res;
    }

    /**
     * @param $params
     * @return bool
     * INSERT INTO test (`id`,`user_id`,`title`) values (?,?,?) ON DUPLICATE KEY UPDATE `id` = ?, `user_id` = ? ,`title` = ?
     */
    public function insertOnDuplicateKeyUpdate($params)
    {
        $res = $this->processor->insertOnDuplicateKeyUpdate($params);
        if ($res) {
            $this->updateUpdateTime();
        }
        return $res;
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
        $res = $this->processor->update($params, $where);
        if ($res) {
            $this->updateUpdateTime();
        }
        return $res;
    }

    /**
     * @param $where
     * @return array|bool|int|string
     * @throws \Exception
     * DELETE FROM test WHERE id = 1
     */
    public function delete($where)
    {
        $res = $this->processor->update($where);
        if ($res) {
            $this->updateUpdateTime();
        }
        return $res;
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
        $res = $this->processor->increase($where, $field, $count, $increase);
        if ($res) {
            $this->updateUpdateTime();
        }
        return $res;
    }
}
