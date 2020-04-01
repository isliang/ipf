<?php
/**
 * User: isliang
 * Date: 2019/10/16
 * Time: 15:56
 * Email: wslhdu@163.com
 **/

namespace Isf\Pool;

use Isf\Config\ConfigChecker;
use Isf\Config\ConfigLoader;
use Isf\Constant\CommConst;
use Isf\Exception\RedisNoUsableConnectionException;
use Swoole\Coroutine\Redis;

class RedisPool
{
    /**
     * @var Redis[]
     */
    private $pool = [];
    private $size = 0;
    private $config;
    private $target_id = 0;

    /**
     * @var \chan
     */
    private $chan;

    public function __construct($name)
    {
        $config = ConfigLoader::getConfig('redis', $name);
        ConfigChecker::checkRedisConfig($name, $config);
        $this->config = $config;
        $this->chan = new \chan(100);
    }

    /**
     * @return Redis
     * @throws RedisNoUsableConnectionException
     */
    public function getConnection()
    {
        if (!empty($this->pool)) {
            return array_pop($this->pool);
        } else {
            if ($this->size >= CommConst::MAX_REDIS_POOL_SIZE) {
                throw new RedisNoUsableConnectionException();
            }
            $redis = new Redis($this->config['options']);

            $redis->connect($this->config['host'], $this->config['port'], $this->config['serialize']);
            $this->size++;
            return $redis;
        }
    }

    public function query($method, $params, $callback)
    {
        $target_id = $this->target_id++;
        go(function () use ($method, $params, $callback, $target_id) {
            try {
                $redis = $this->getConnection();
                $result = call_user_func_array([$redis, $method], $params);
            } catch (\Exception $e) {
                throw $e;
            }
            $this->chan->push(new PoolResult($redis, call_user_func_array($callback, [$result]), $target_id));
        });
    }

    public function wait($count)
    {
        $result = [];
        while ($count--) {
            /**
             * @var $item PoolResult
             */
            $item = $this->chan->pop();
            $result[$item->getTargetId()] = $item->getResult();
            $this->pool[] = $item->getResource();
        }
        $this->target_id = 0;
        ksort($result);
        return $result;
    }
}