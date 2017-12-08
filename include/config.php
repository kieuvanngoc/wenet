<?php
/**
 * Created by PhpStorm.
 * User: Ngọc Kiều
 * Date: 4/2/2016
 * Time: 8:10 PM
 */
$debug = 0;

define('PG_URL_HOMEPAGE', 'http://localhost/wenet/');
define('ROOT_URL', "http://localhost/wenet/");
define('DIR_ROOT', 'D:/wamp/www/wenet/');
define('DOMAIN', 'trangchuchuatinh');

/*-- MYSQL --*/
// LOCAL
$database_host = 'localhost';
$database_host_li = 'localhost';
$database_username = 'root';
$database_password = '';
$database_name = 'wenet_crm';

/*-- CACHE --*/
define('MEMCACHE_ON', 0);
define('REDIS_ON', 0);
define('PAGE_CACHE', 0); // Cache cho smarty
define('CACHE_ON', 0);
define('CACHE_DB', 0);
define('CACHE_SERVER_NAME', 'localhost/wenet/');

/*-- DEBUG --*/
define('SETTING_DEBUG_PAPGE', 1);
define('REWRITE_ON', 1);

date_default_timezone_set('Asia/Ho_Chi_Minh');