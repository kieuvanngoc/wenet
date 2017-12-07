<?php
/**
 * Created by PhpStorm.
 * User: Ngọc Kiều
 * Date: 4/2/2016
 * Time: 8:10 PM
 */
$debug = 0;

define('PG_URL_HOMEPAGE', 'http://localhost/crm/');
//define('PG_URL_HOMEPAGE', 'http://zama.vn/');
define('ROOT_URL', "http://localhost/crm/");
define('DIR_ROOT', 'D:/wamp/www/crm/');
define('DOMAIN', 'trangchuchuatinh');

/*-- MYSQL --*/
// LOCAL
$database_host = 'localhost';
$database_host_li = 'localhost';
$database_username = 'root';
$database_password = '';
$database_name = 'wenet_crm';

// WENET
//$database_host = '123.31.45.231:3306';
//$database_username = 'wenet_crm';
//$database_password = 'wenet_crm123@123!@#';
//$database_name = 'wenet_crm';

// SERVER
//$database_host = '42.112.27.32:33223';
////$database_host = '192.168.1.223:3306';
//$database_username = 'db_idea';
//$database_password = 'Dtv3ds5S';
//$database_name = 'db_idea';

define('MEMCACHE_ON', 0);
define('REDIS_ON', 0);
define('PAGE_CACHE', 0); // Cache cho smarty
define('CACHE_ON', 0);
define('CACHE_DB', 0);
define('CACHE_SERVER_NAME', 'localhost/crm/');

define('SETTING_DEBUG_PAPGE', 1);
define('REWRITE_ON', 1);

date_default_timezone_set('Asia/Ho_Chi_Minh');