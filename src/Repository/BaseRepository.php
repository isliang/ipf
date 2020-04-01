<?php
/**
 * User: isliang
 * Date: 2019/10/15
 * Time: 9:35
 * Email: wslhdu@163.com
 **/

namespace Ipf\Repository;

abstract class BaseRepository
{
    public function __construct()
    {
        $this->initObject();
    }

    abstract protected function initObject();
}