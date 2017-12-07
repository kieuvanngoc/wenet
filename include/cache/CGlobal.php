<?php
class CGlobal
{
    static $version = 3.0;

    static $js_ver = 3.5;
    static $css_ver = 3.5;

    static $configs = array();

    static $query_debug = "";
    static $query_time;
    static $conn_debug = "";
    static $error_handle = "";
    static $ftp_image_connect_id = array();

    static $my_server = array(
        CACHE_SERVER_NAME
    );
    static $request_uri;
    static $referer_url;
    static $query_string = '';

    //website info
    static $keywords;
    static $meta_desc;
    static $website_title;
    static $currency = array();
    static $max_upload_size = 1;
    static $logo = '';
    static $favicon = '';

    static $robotContent = 'INDEX, FOLLOW';
    static $gBContent = "index,follow,archive";

    static $memcache_connect_id = false;
    static $memcache_server = false;

    static $pg_noIndex = array('sign_out', 'error');

    static $arrPage = array();

    static $permission = array(
        "Core System" => array(
            "access admin page" => "Truy cập vào trang quản trị",
            "access content" => "Truy cập website",
        )
    );
    static $permission_group = array();
    static $oAuth = array();
    static $userComment = array();

    static $menu = array();

    static $imageSize = array();

    static $imageSizeKeys = array();

    static $province = array();

    static $web_status = 'online';

    static $hotline = '';
    static $yahoo_support = '';

    static $currentItem = array();
    static $manufacturers = array();
}

?>