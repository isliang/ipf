<?php
/**
 * User: isliang
 * Date: 2019/9/17
 * Time: 16:27
 * Email: wslhdu@163.com
 **/

namespace Isf\Pool;

class PoolResult
{
    private $resource;
    private $result;
    /**
     * @var int
     */
    private $target_id;

    public function __construct($resource, $result, $target_id)
    {
        $this->resource = $resource;
        $this->result = $result;
        $this->target_id = $target_id;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return int
     */
    public function getTargetId()
    {
        return $this->target_id;
    }
}
