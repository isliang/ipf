<?php
/**
 * User: isliang
 * Date: 2019/10/15
 * Time: 9:35
 * Email: wslhdu@163.com
 **/

namespace Ipf\Entity;

class BaseEntity
{
    public function __construct($params)
    {
        $vars = $this->getVars();
        foreach ($vars as $var) {
            if (is_array($params)) {
                if (isset($params[$var])) {
                    $this->$var = $params[$var];
                }
            } elseif (is_object($params)) {
                if (isset($params->$var)) {
                    $this->$var = $params->$var;
                }
            }
        }
    }

    public function toArray()
    {
        $vars = $this->getVars();
        $arr = [];
        foreach ($vars as $var) {
            if (isset($this->$var) && !is_null($this->$var)) {
                $arr[$var] = $this->$var;
            }
        }
        return $arr;
    }

    public function getVars()
    {
        return array_keys(get_class_vars(get_called_class()));
    }
}