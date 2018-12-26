<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/10/10
 * Time: 11:35
 */

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
class QueryListener
{
    public function __construct()
    {
        //
    }

    public function handle(QueryExecuted $event)
    {
        $sql = str_replace("?", "'%s'", $event->sql);

        $log = vsprintf($sql, $event->bindings);

        \Log::info($log);
    }
}