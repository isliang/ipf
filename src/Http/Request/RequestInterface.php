<?php
namespace Ipf\Http\Request;

interface RequestInterface
{
    public function getQuery($name = null);
    public function getPost($name = null);
    public function getFile($name = null);
    public function getHeader($name = null);
    public function getCookie($name = null);
    public function getIp();
    public function getMethod();
    public function getUri();
}