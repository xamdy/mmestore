<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/10/17
 * Time: 15:21
 */
namespace App\Handle;

use Redis;
class SwooleHandle
{
    public function __construct()
    {

    }
    public function onOpen($serv, $request)
    {
        echo 'onOpen';
    }
    public function onMessage($serv,$frame)
    {
        echo 'onMessage';
    }
    public function onClose($serv,$fd)
    {
        echo 'onClose';
    }
}