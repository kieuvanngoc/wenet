<?php
require 'include/config.php';
ob_start();

// SET ERROR REPORTING
if ( $debug ){
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
}else{
	error_reporting(0);
	ini_set('display_errors', FALSE);
}

// CHECK FOR PAGE VARIABLE
if(!isset($page)) { $page = ""; }

// DEFINE SE PAGE CONSTANT
define('PG_DEBUG', TRUE);
define('PG_PAGE', TRUE);
define('PG_ROOT', realpath(dirname(__FILE__)));
define('PG_ADMIN_SAFE_MODE', false);

$webroot=str_replace('\\','/','http://'.$_SERVER['HTTP_HOST']);
$webroot.=$webroot[strlen($webroot)-1]!='/'?'/':'';
define('WEB_ROOT',$webroot);
define('PG_URL_ROOT', WEB_ROOT . implode('/', array_slice(explode('/', $_SERVER['PHP_SELF']), 1, -1)));

// SET INCLUDE PATH TO ROOT OF SE
set_include_path(get_include_path() . PATH_SEPARATOR . realpath("../"));

// SET ERROR REPORTING
if( PG_DEBUG )
{
	if( file_exists('include/globals/class_firephp.php') )
	{
		include "include/globals/class_firephp.php";
		$fb =& FirePHP::getInstance();
		$fb->registerErrorHandler();
	}
}
else
{
	ini_set('display_errors', FALSE);
	error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_USER_ERROR);
}
$fron_end = false;

// INCLUDE INFORMATION
include "include/database_config.php";
include "include/define.table.php";
include "include/functions_email.php";
include "include/functions_general.php";
include "include/functions_upload.php";
include "include/object.php";
include "include/cake/config.php";
include "include/environment/request.php";
include "include/environment/uri.php";
include "include/filter/filterinput.php";

// CACHE & MEMCACHE
require_once 'include/cache/memcache.config.php';
if(MEMCACHE_ON){
	CGlobal::$memcache_server=$memcache_server;
	require_once 'include/cache/memcache.class.php';
}
if(REDIS_ON){
	$Redis = new Redis();
	$Redis->connect($redis_server['host'], $redis_server['port']);
	require_once 'include/cache/RedisLib.php';
}
require_once 'include/cache/CGlobal.php';
require_once 'include/cache/CacheLib.php';

// INCLUDE CORE GLOBALS
include "include/globals/class_benchmark.php";
include "include/globals/class_database.php";
include "include/globals/class_datetime.php";
include "include/globals/class_error.php";
include "include/globals/class_navigation.php";
include "include/globals/class_settings.php";
include "include/globals/class_smarty.php";
include "include/globals/class_theme.php";
include "include/globals/class_validate.php";
include "include/globals/class_acl.php";
include "include/admins/class_admin.php";

// BENCHMARK
$_benchmark = PGBenchmark::getInstance('default');

PG_DEBUG ? $_benchmark->start('total') : NULL;
PG_DEBUG ? $_benchmark->start('include') : NULL;

// INITIATE DATABASE CONNECTION
$database =& PGDatabase::getInstance();

// SET LANGUAGE CHARSET
$database->db_set_charset('utf8');

// OBJECT
$object = new PGObject();

// CREATE DATETIME CLASS
$datetime = new PGDatetime();

// CREATE URL CLASS
$uri = PGURI::getInstance();

// CREATE PGFilterInput
$filter	= new PGFilterInput();

// ENSURE NO SQL INJECTIONS THROUGH POST OR GET ARRAYS
$_POST = security($_POST);
$_GET = security($_GET);
$_COOKIE = security($_COOKIE);

// GET SETTINGS
$settingClass = new PGSettings();
$setting = $settingClass->getSettings();

// INITIATE SMARTY
$smarty =& PGSmarty::getInstance();
$smarty->template_dir = PG_ROOT.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.'manager';
$smarty->compile_dir = PG_ROOT.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR.'manager';

// CREATE SESSION
$session_options = @unserialize($setting['setting_session_options']);
if( !empty($session_options) )
{
	if( !empty($session_options['storage']) ) Configure::write('Session.save', $session_options['storage']);
	if( !empty($session_options['name']) ) Configure::write('Session.cookie', $session_options['name']);
	if( !empty($session_options['expire']) ) Configure::write('Session.timeout', $session_options['expire']);
}

$session =& PGSession::getInstance(null, true);
$session->engine(@$session_options['storage'], $session_options);

if( defined('PG_SESSION_RESUME') && PG_SESSION_RESUME && isset($session_id) )
{
	$session->_userAgent = md5(env('HTTP_USER_AGENT') . Configure::read('Security.salt'));
	$session->id($session_id);
}
$session->start();

// CREATE ADMIN OBJECT AND ATTEMPT TO LOG ADMIN IN
$admin = new PGAdmin();
$admin->admin_checkCookies();

// CHECK EXIST ADMIN
$admin->checkExistAdmin();

// ADMIN IS NOT LOGGED IN AND NOT ON LOGIN PAGE
if($page != "login" && $page != "lostpass" && $page != "lostpass_reset" && $admin->admin_exists == 0)
{
	cheader("login.php");
}

$toolbar = "";
?>