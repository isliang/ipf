<?php
/**
 * User: isliang
 * Date: 2019/9/17
 * Time: 11:03
 * Email: wslhdu@163.com
 **/

namespace Ipf\Pool;

use Ipf\Config\ConfigChecker;
use Ipf\Config\ConfigLoader;
use Ipf\Constant\CommConst;
use Ipf\Constant\ExceptionConst;
use Ipf\Exception\MysqlNoUsableConnectionException;

class MysqlPool
{
    /**
     * @var \PDO[]
     */
    private $pool = [];
    private $size = 0;
    private $config;


    public function __construct($name)
    {
        $config = ConfigLoader::getConfig('database', $name);
        ConfigChecker::checkPdoConfig($name, $config);
        $this->config = $config;
    }

    /**
     * @return \PDO
     * @throws MysqlNoUsableConnectionException
     */
    public function getConnection()
    {
        if (!empty($this->pool)) {
            return array_pop($this->pool);
        } else {
            if ($this->size >= CommConst::MAX_MYSQL_POOL_SIZE) {
                throw new MysqlNoUsableConnectionException();
            }
            $pdo = new \PDO(
                $this->config['dsn'],
                $this->config['username'],
                $this->config['password'],
                $this->config['options']);
            $this->size++;
            return $pdo;
        }
    }

    public function query($sql, $params, $type, $callback = null)
    {
        try {
            connection:
            $mysql = $this->getConnection();
            $stmt = $mysql->prepare($sql);
            $result = $stmt->execute($params);
            if ($result) {
                switch ($type) {
                    case "SELECT":
                        $result = $stmt->fetchAll();
                        break;
                    case "INSERT":
                    case "REPLACE":
                        $result = $mysql->lastInsertId();
                        break;
                    case "UPDATE":
                    case "DELETE":
                        $result = $stmt->rowCount();
                        break;
                }
            }
        } catch (\Exception $e) {
            if ($e->getCode() == ExceptionConst::CODE_MYSQL_SERVER_OFFLINE
                || $e->getCode() == ExceptionConst::CODE_LOST_CONNECTION_TO_MYSQL_SERVER) {
                $this->size--;
                goto connection;
            } else {
                throw $e;
            }
        }
        $this->pool[] = $mysql;
        return $callback ? call_user_func_array($callback, [$result]) : $result;
    }

}