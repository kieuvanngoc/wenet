<?php
defined('PG_PAGE') or die();

class PGSettings
{
	function PGSettings()
	{
		return;
	}
	
	function getSettings()
	{		
		$settings = array(
			'setting_password_method' => 1,
			'setting_password_code_length' => 16,
			'setting_username' => 0,
			'setting_key_rc4' => 'zama_startup_2017',
			'setting_key_generation' => 'ZAMA',
			// SETTING DISPLAY CACHE
			'setting_cache_on'		=> 0,
			'setting_cache_time'	=> 0,
			'setting_cache_default' => 'file',
			'setting_cache_lifetime' => 120,
			'setting_cache_file_options' => 'a:2:{s:4:"root";s:7:"./cache";s:7:"locking";b:1;}',
			'setting_cache_memcache_options' => 'a:1:{s:7:"servers";a:1:{i:0;a:2:{s:4:"host";s:9:"localhost";s:4:"port";i:11211;}}}',
			'setting_cache_xcache_options' => '',
			'setting_session_options' => 'a:4:{s:7:"storage";s:4:"none";s:6:"expire";i:259200;s:4:"name";s:32:"fbbf55807d6bc9fe23bd03063ee6d557";s:7:"servers";a:1:{i:0;a:2:{s:4:"host";s:9:"localhost";s:4:"port";i:11211;}}}',
			'setting_banned_words' => '',
			'setting_debug_page' 	=> SETTING_DEBUG_PAPGE,
			'setting_list_limit' => 50,
			'setting_master_email'	=> '',
			'setting_manager_email'	=> '',
			'setting_all_email'		=> '',
			'setting_order_email'	=> '',
			'setting_banned_emails' => '',
			'setting_support_skype'	=> 'kieu.van.ngoc',
			'setting_support_yahoo'	=> 'kieuvanngoc105',
			'setting_support_phone' => '097.8686.055',
			'setting_support_email'	=> 'info.zamashop@gmail.com',
			'setting_hotline' => '097.8686.055',
			'setting_domain' => 'zama.vn',
			'setting_ios_app_id' => '913050358',
			'setting_google_play_app_id' => 'enetviet.corp.qi.enetvietnew',
			'setting_url_app_ios' => 'https://itunes.apple.com/us/app/enetviet/id913050358?ls=1&mt=8',
			'setting_url_app_android' => 'https://play.google.com/store/apps/details?id=enetviet.corp.qi.enetvietnew',
			'setting_author' => 'Kiều Văn Ngọc',
			'setting_ga_id'	=> '',
			'setting_show_facebook' => 0,
			'setting_facebook' => 'https://www.facebook.com/M%E1%BA%A1ng-gi%C3%A1o-d%E1%BB%A5c-tr%E1%BB%B1c-tuy%E1%BA%BFn-Enetviet-1746970022200783/',
			'setting_twitter' => '',
			'setting_youtube' => 'https://www.youtube.com/channel/UCxRqBHWzF1oPg82Hz1nOvpQ',
			'setting_google_plus' => 'https://plus.google.com/b/109593247089394433078/109593247089394433078/posts',
			'setting_face_app_id' => 0,
			'setting_banned_usernames' => '',
			'setting_password_code_length' => 8,
			'setting_signup_enable' => 1,
			'setting_signup_merchant_enable' => 0,
			'setting_signup_verify' => 1,
			'setting_signup_code' => 0,
			'setting_signup_randpass' => 0,
			'setting_signup_welcome' => 1,
			'setting_login_code' => 1,
			'setting_login_code_failedcount' => 3,
			'setting_gold_rate' => 95/100,
			'setting_transfer_expires' => 86400,
			'setting_amount_risk' => 10000000,
			'setting_vpc_version' => 2,
			'setting_slogan' => 'Tất cả vì tương lai con em chúng ta',
			'setting_title_web'	=> 'System CRM',
			'setting_keyword_web' => 'thoi trang, dat hang online, hang chat luong, hang dep, thoi trang nam nu, me va be, thoi trang bon mua',
			'setting_description_web' => 'Zama chuyên cung cấp các sản phẩm thời trang chất lượng cho khách hàng, các dòng sản phẩm thời trang phục vụ bốn mùa',
			'setting_company' => 'Zama Corp',
			'setting_version' => 'Zama - Copyright @2017',
			'setting_title_first' => 0,
			'resize_image_logo' => 80,
			'resize_news_image_tiny' => 80,
			'resize_news_image_thumbnail' => 150,
			'resize_news_image_normal' => 300,
			'resize_news_image_large' => 450,
			'resize_image_tiny'	=> 60,
			'resize_image_min'	=> 150,
			'resize_image_normal' => 230, // 230 x 167
			'resize_image_max'	=> 690, // 690 x 501
			'setting_comment_enable' => 0,
			// SETTING MAIL
			'mail_type' => 'smtp',	//default: smtp
			'mail_sendmailpath' => '/usr/sbin/sendmail',
			'mail_smtpauth' => true,
			'mail_smtpsecure' => 'none',
			'mail_smtpport' => 25,
			'mail_smtpuser' => 'noreply@evara.vn',
			'mail_smtppass' => '123$%^qwe',
			'mail_smtphost' => 'mail.evara.vn',
			'mail_soap_send' => '' 
		);
		return $settings;
	}
	
	//Show Page Title
	function showPageSetting($title){
		global $setting;

		if ($setting['setting_title_first'] == 0) $name_title = $setting['setting_title_web']." - ".$title;
		else $name_title = $title." - ".$setting['setting_title_web'];

		return $name_title;
	}
	
	
	//Show Page Title Follow data_type
	function showPageTitle($title){
		global $setting;
		
		if ($setting['setting_title_first'] == 0) $name_title = $setting['setting_title_web']." - ".$title;
		else $name_title = $title." - ".$setting['setting_title_web'];

		return $name_title;
	}

	//Get type user
	static function getTypeUser($type) {
		global $cache;

		$cacheTime = 2592000; // 30d
		$cacheKey = 'typeuser';

		//$type = $cache->get($cacheKey);
		if ($type) return $type;

		$type = array (
			1 => 'Người mua',
			2 => 'Người bán'
		);

		//$cache->set($cacheKey, $type , $cacheTime);

		return $type;
	}
}
?>