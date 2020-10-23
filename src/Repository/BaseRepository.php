<?php
/**
 * User: isliang
 * Date: 2019/10/15
 * Time: 9:35
 * Email: wslhdu@163.com.
 **/

namespace Ipf\Repository;

use Ipf\Utils\TSingleton;

abstract class BaseRepository
{
    use TSingleton;

    public function __construct()
    {
        $this->initObject();
    }

    abstract protected function initObject();
}
