<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 10:53
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Constant;

class CommConst
{
    const SIZE_RESPONSE_WRITE_BUFFER = 1048576; //1024*1024

    const DEFAULT_SQL_OFFSET = 0;

    const DEFAULT_SQL_LIMIT = 500;

    //mysql数据库连接池最大数量
    const MAX_MYSQL_POOL_SIZE = 100;

    //redis连接池最大数量
    const MAX_REDIS_POOL_SIZE = 100;
}
