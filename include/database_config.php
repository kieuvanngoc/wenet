<?php
/**
 * Created by PhpStorm.
 * User: Ngọc Kiều
 * Date: 9/21/2016
 * Time: 5:31 PM
 */
defined('PG_PAGE') or die();

//Định nghĩa ROOT_URL_HOME
define('PG_URL_IMAGE', 'http://re24h.com.vn/');
define('PATH_IMAGE_ACTION', './images/action/');

$dir['root'] = DIR_ROOT;
$dir['include'] = "{$dir['root']}include/";
$dir['classes'] = "{$dir['include']}/mongo/classes/";

define('DIRECTORY_PATH_ROOT', $dir['root']);
define('DIRECTORY_PATH_CORE', $dir['include']);
define('DIRECTORY_PATH_CORE_MONGO', $dir['include'] . 'mongo/');
define('DIRECTORY_PATH_INC', $dir['include']);
define('DIRECTORY_PATH_CLASSES', $dir['classes']);

date_default_timezone_set('UTC');

define('FOLDER_CONTAINER_IMAGE', "/enetviet/uploads/stories/");
define('FOLDER_AVATAR_IMAGE', "/enetviet/uploads/avatars/");

/*-- pusher --*/
$pusher_app_id = '181722';
$pusher_app_key = 'd0edb6f7bb0c1e8ede15';
$pusher_app_secret = '46b4395ed02362e9af49';

// Services Untilities
define('DEMO_SERVICES_UNTILITIES', 0);
define('DEMO_SERVICES_MA_SO', 'HN');
define('DEMO_SERVICES_CAP23', 2);
define('DEMO_SERVICES_MAHS_CAP23', '101244070');
define('DEMO_SERVICES_CAP1', 1);
define('DEMO_SERVICES_MAHS_CAP1', '101244070');
?>