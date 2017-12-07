<?php
define('TIME_NOW', time());
define('DIR_CACHE', "_cache/");

//my server
$server_list = array(
    CACHE_SERVER_NAME
);

if (MEMCACHE_ON) {
    //LIVE IP : 192.168.1.41 Port : 11211
    $memcache_server = array(
        array('host' => '192.168.1.41', 'port' => 11211, 'weight' => 100),
        //array('host' => '103.18.5.84', 'port' => 11211, 'weight' => 100),
        //array('host' => '10.10.10.3', 'port' => 11211, 'weight' => 1),
        //array('host' => '10.10.10.4', 'port' => 11211, 'weight' => 100),
    );
    define('MEMCACHE_ID', 'eNetviet');
}
if ( REDIS_ON ) {
    $redis_server = array(
        'host'  => '127.0.0.1',
        'port'  => 6379
    );
//    $redis_server = array(
//        'host'  => '103.28.37.66', // Server evara
//        'port'  => 6379
//    );
}
?>