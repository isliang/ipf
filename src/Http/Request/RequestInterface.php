<?php
namespace Ipf\Http\Request;

interface RequestInterface
{
    /**
     * @param string|null $name
     * @return mixed
     */
    public function getQuery($name = null);

    /**
     * @param string|null $name
     * @return mixed
     */
    public function getPost($name = null);

    /**
     * @param string|null $name
     * @return mixed
     */
    public function getFile($name = null);

    /**
     * @param string|null $name
     * @return mixed
     */
    public function getHeader($name = null);

    /**
     * @param string|null $name
     * @return mixed
     */
    public function getCookie($name = null);
    public function getIp();
    public function getMethod();
    public function getUri();
}