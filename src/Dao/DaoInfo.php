<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 12:04
 * Email: yesuhuangsi@163.com
 **/

namespace Ipf\Dao;

class DaoInfo
{
    /**
     * @var string
     */
    private $table_name;

    /**
     * @var string
     */
    private $master_dsn;

    /**
     * @var string
     */
    private $slave_dsn;

    /**
     * @var string
     */
    private $db_name;

    /**
     * @var string
     */
    private $pk;

    /**
     * @var bool
     */
    private $skip_cache = false;

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table_name;
    }

    /**
     * @param string $table_name
     */
    public function setTableName(string $table_name)
    {
        $this->table_name = $table_name;
    }

    /**
     * @return string
     */
    public function getMasterDsn(): string
    {
        return $this->master_dsn;
    }

    /**
     * @param string $master_dsn
     */
    public function setMasterDsn(string $master_dsn)
    {
        $this->master_dsn = $master_dsn;
    }

    /**
     * @return string
     */
    public function getSlaveDsn(): string
    {
        return $this->slave_dsn;
    }

    /**
     * @param string $slave_dsn
     */
    public function setSlaveDsn(string $slave_dsn)
    {
        $this->slave_dsn = $slave_dsn;
    }

    /**
     * @return string
     */
    public function getDbName(): string
    {
        return $this->db_name;
    }

    /**
     * @param string $db_name
     */
    public function setDbName(string $db_name)
    {
        $this->db_name = $db_name;
    }

    /**
     * @return string
     */
    public function getPk(): string
    {
        return $this->pk;
    }

    /**
     * @param string $pk
     */
    public function setPk(string $pk)
    {
        $this->pk = $pk;
    }

    /**
     * @return bool
     */
    public function isSkipCache(): bool
    {
        return $this->skip_cache;
    }

    /**
     * @param bool $skip_cache
     */
    public function setSkipCache(bool $skip_cache): void
    {
        $this->skip_cache = $skip_cache;
    }
}