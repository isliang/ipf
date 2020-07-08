<?php

namespace Ipf\Dao;

class CacheTagDao extends BaseDao
{
    public function getDaoInfo()
    {
        $dao_info = new DaoInfo();
        $dao_info->setMasterDsn('cache_db_master');
        $dao_info->setSlaveDsn('cache_db_slave');
        $dao_info->setDbName('cache_db');
        $dao_info->setTableName('cache_tag');
        $dao_info->setPk('id');
        $dao_info->setSkipCache(true);

        return $dao_info;
    }
}
