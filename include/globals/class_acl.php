<?php
defined('PG_PAGE') or die();

define('PER_MANAGE', 'manage');
define('PER_ADD', 'add');
define('PER_EDIT', 'edit');
define('PER_DELETE', 'delete');
define('PER_VIEW', 'view');
define('PER_UPDATE', 'update');

class PGAcl
{
	/**
	 * Access page list
	 * @var	array
	 */
	var $apl       = null;
	
	/**
	 * Access task list
	 * @var	array
	 */
	var $atl       = null;
	
	/**
	 * Access pages list
	 * @var	array
	 */
	var $pages       = null;

    var $group_pages = null;

    var $group_pages_default = null;
	
	/**
	 * Constructor
	 * @param array An arry of options to oeverride the class defaults
	 */
	function PGAcl()
	{
		global $admin, $list_modules;
		
		$this->apl = $this->atl = $this->pages = $this->group_pages = $this->group_pages_default = array();

		$this->atl = array(
			1 => 'manage',
			2 => 'add',
			3 => 'edit',
			4 => 'remove',
			5 => 'view',
			6 => 'save',
			7 => 'detail',
			8 => 'publish',
			9 => 'unpublish',
			10 => 'import',
			11 => 'export',
			12 => 'html',
			13 => 'send',
			14 => 'save2add'
		);

		$this->apl = array(
			1 => 'Administrator',
			2 => 'Manager Site',
			3 => 'User Site',
			4 => 'All Site',
			5 => 'Sale Manager Site',
			6 => 'Sale Site',
			7 => 'Reconciliation',
			8 => 'Support',
			9 => 'Translator'
		);

		// Super Admin, Administrator
		$this->pages = array(
			// 1. HOME
			'admin_quiz'			=> array(1, 5, 7),

			// 2. QUẢN LÝ DỮ LIỆU
			'admin_category'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 14),
			'admin_product'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 14),
			'admin_partner'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_trademark'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_landingpage'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_citydistrict'	=> array(1, 5, 7),

			// 3. QUẢN LÝ THÔNG TIN
			'admin_content'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_static'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_custom'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_faqs'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_quote'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 4. HÌNH ẢNH - VIDEO
			'admin_banner' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_video' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 5. BẢNG ĐIỀU KHIỂN
			'admin_manager_menu' 	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 14),
			'admin_tag'				=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_template' 		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_module' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 6. KHO HÀNG DỮ LIỆU
			'admin_order' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_receipt'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_delivery'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_product'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_revenue'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_check'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 7. TÀI KHOẢN
			'admin_account' 		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_user' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_member' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 11),
			'admin_customer'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_site'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 8. HỆ THỐNG
			'admin_setting' 		=> array(1, 3, 6),
			'admin_cache' 			=> array(1, 4, 5)
		);

		// Manager Site
        $this->group_manager_site_pages = array(
			// 1. HOME
			'admin_quiz'			=> array(1, 5, 7),

			// 2. QUẢN LÝ DỮ LIỆU
			'admin_category'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 14),
			'admin_product'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 14),
			'admin_partner'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_trademark'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_landingpage'		=> array(1, 5, 8),
			'admin_citydistrict'	=> array(1, 5),

			// 3. QUẢN LÝ THÔNG TIN
			'admin_content'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_static'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_custom'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_faqs'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_quote'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 4. HÌNH ẢNH - VIDEO
			'admin_banner' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_video' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 5. BẢNG ĐIỀU KHIỂN
			'admin_manager_menu' 	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 14),
			'admin_tag'				=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
//			'admin_template' 		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
//			'admin_module' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 6. KHO HÀNG DỮ LIỆU
			'admin_order' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_receipt'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_delivery'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_product'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_revenue'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_check'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 7. TÀI KHOẢN
			'admin_account' 		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_user' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_member' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 11),
//			'admin_customer'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
//			'admin_site'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 8. HỆ THỐNG
			'admin_setting' 		=> array(1, 3, 6),
			'admin_cache' 			=> array(1, 4, 5)
        );

		// User Site
		$this->group_user_site_pages = array(
			// 1. HOME
			'admin_quiz'			=> array(1, 5, 7),

			// 2. QUẢN LÝ DỮ LIỆU
			'admin_category'		=> array(1, 2, 3, 5, 6, 7, 8, 9, 14),
			'admin_product'			=> array(1, 2, 3, 5, 6, 7, 8, 9, 14),
			'admin_partner'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_trademark'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_landingpage'		=> array(1, 5, 8),
			'admin_citydistrict'	=> array(1, 5),

			// 3. QUẢN LÝ THÔNG TIN
			'admin_content'			=> array(1, 2, 3, 5, 6, 7, 8, 9),
			'admin_static'			=> array(1, 2, 3, 5, 6, 7, 8, 9),
			'admin_custom'			=> array(1, 2, 3, 5, 6, 7, 8, 9),
			'admin_faqs'			=> array(1, 2, 3, 5, 6, 7, 8, 9),
			'admin_quote'			=> array(1, 2, 3, 5, 6, 7, 8, 9),

			// 4. HÌNH ẢNH - VIDEO
			'admin_banner' 			=> array(1, 2, 3, 5, 6, 7, 8, 9),
			'admin_video' 			=> array(1, 2, 3, 5, 6, 7, 8, 9),

			// 5. BẢNG ĐIỀU KHIỂN
			'admin_manager_menu' 	=> array(1, 2, 3, 5, 6, 7, 8, 9, 14),
			'admin_tag'				=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
//			'admin_template' 		=> array(1, 2, 3, 5, 6, 7, 8, 9),
//			'admin_module' 			=> array(1, 2, 3, 5, 6, 7, 8, 9),

			// 6. KHO HÀNG DỮ LIỆU
//			'admin_order' 			=> array(1, 2, 3, 5, 6, 7, 8, 9),
//			'admin_depot_receipt'	=> array(1, 2, 3, 5, 6, 7, 8, 9),
//			'admin_depot_delivery'	=> array(1, 2, 3, 5, 6, 7, 8, 9),
//			'admin_depot'			=> array(1, 2, 3, 5, 6, 7, 8, 9),
//			'admin_depot_product'	=> array(1, 2, 3, 5, 6, 7, 8, 9),
//			'admin_depot_revenue'	=> array(1, 2, 3, 5, 6, 7, 8, 9),
//			'admin_depot_check'		=> array(1, 2, 3, 5, 6, 7, 8, 9),

			// 7. TÀI KHOẢN
			'admin_account' 		=> array(1, 2, 3, 5, 6, 7, 8, 9),
			'admin_user' 			=> array(1, 2, 3, 5, 6, 7, 8, 9),
			'admin_member' 			=> array(1, 2, 3, 5, 6, 7, 8, 9, 11),
//			'admin_customer'		=> array(1, 2, 3, 5, 6, 7, 8, 9),
//			'admin_site'			=> array(1, 2, 3, 5, 6, 7, 8, 9),

			// 8. HỆ THỐNG
			'admin_setting' 		=> array(1, 3, 6),
			'admin_cache' 			=> array(1, 4, 5)
		);

		// All Site
		$this->group_manager_all_site_pages = array(
			// 1. HOME
			'admin_quiz'			=> array(1, 5, 7),

			// 2. QUẢN LÝ DỮ LIỆU
			'admin_category'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 14),
			'admin_product'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 14),
			'admin_partner'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_trademark'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_landingpage'		=> array(1, 5, 8),
			'admin_citydistrict'	=> array(1, 5),

			// 3. QUẢN LÝ THÔNG TIN
			'admin_content'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_static'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_custom'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_faqs'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_quote'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 4. HÌNH ẢNH - VIDEO
			'admin_banner' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_video' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 5. BẢNG ĐIỀU KHIỂN
			'admin_manager_menu' 	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 14),
			'admin_tag'				=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
//			'admin_template' 		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
//			'admin_module' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 6. KHO HÀNG DỮ LIỆU
			'admin_order' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_receipt'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_delivery'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_product'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_revenue'	=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_depot_check'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 7. TÀI KHOẢN
			'admin_account' 		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_user' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
			'admin_member' 			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 11),
//			'admin_customer'		=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),
//			'admin_site'			=> array(1, 2, 3, 4, 5, 6, 7, 8, 9),

			// 8. HỆ THỐNG
			'admin_setting' 		=> array(1, 3, 6),
			'admin_cache' 			=> array(1, 4, 5)
		);

		// Sale Manager Site
		$this->group_manager_sale_pages = array(
			// 1. HOME
			'admin_quiz'			=> array(1, 5, 7),

			// 2. QUẢN LÝ DỮ LIỆU
			'admin_category'		=> array(1, 5, 11, 14),
			'admin_product'			=> array(1, 5, 11, 14),
			'admin_partner'			=> array(1, 5),
			'admin_trademark'		=> array(1, 5),
			'admin_landingpage'		=> array(1, 5, 8),
			'admin_citydistrict'	=> array(1, 5),

			// 3. QUẢN LÝ THÔNG TIN
			'admin_content'			=> array(1, 5, 11),
			'admin_static'			=> array(1, 5, 11),
			'admin_custom'			=> array(1, 5, 11),
			'admin_faqs'			=> array(1, 5, 11),
			'admin_quote'			=> array(1, 5, 11),

			// 4. HÌNH ẢNH - VIDEO
			'admin_banner' 			=> array(1, 5, 11),
			'admin_video' 			=> array(1, 5, 11),

			// 5. BẢNG ĐIỀU KHIỂN
//			'admin_manager_menu' 	=> array(1, 5, 11),
//			'admin_template' 		=> array(1, 5, 11),
//			'admin_module' 			=> array(1, 5, 11),

			// 6. KHO HÀNG DỮ LIỆU
			'admin_order' 			=> array(1, 5, 11),
			'admin_depot_receipt'	=> array(1, 5, 11),
			'admin_depot_delivery'	=> array(1, 5, 11),
			'admin_depot'			=> array(1, 5, 11),
			'admin_depot_product'	=> array(1, 5, 11),
			'admin_depot_revenue'	=> array(1, 5, 11),
			'admin_depot_check'		=> array(1, 5, 11),

			// 7. TÀI KHOẢN
			'admin_account' 		=> array(1, 5, 11),
			'admin_user' 			=> array(1, 5, 11),
			'admin_member' 			=> array(1, 5, 11),
//			'admin_customer'		=> array(1, 5, 11),
//			'admin_site'			=> array(1, 5, 11),

			// 8. HỆ THỐNG
//			'admin_setting' 		=> array(1, 3, 6),
//			'admin_cache' 			=> array(1, 4, 5)
		);

		// Sale Site
		$this->group_sale_pages = array(
			// 1. HOME
			'admin_quiz'			=> array(1, 5, 7),

			// 2. QUẢN LÝ DỮ LIỆU
			'admin_category'		=> array(1, 5, 11, 14),
			'admin_product'			=> array(1, 5, 11, 14),
			'admin_partner'			=> array(1, 5),
			'admin_trademark'		=> array(1, 5),
			'admin_landingpage'		=> array(1, 5, 8),
			'admin_citydistrict'	=> array(1, 5),

			// 3. QUẢN LÝ THÔNG TIN
			'admin_content'			=> array(1, 5, 11),
			'admin_static'			=> array(1, 5, 11),
			'admin_custom'			=> array(1, 5, 11),
			'admin_faqs'			=> array(1, 5, 11),
			'admin_quote'			=> array(1, 5, 11),

			// 4. HÌNH ẢNH - VIDEO
			'admin_banner' 			=> array(1, 5, 11),
			'admin_video' 			=> array(1, 5, 11),

			// 5. BẢNG ĐIỀU KHIỂN
//			'admin_manager_menu' 	=> array(1, 5, 11),
//			'admin_template' 		=> array(1, 5, 11),
//			'admin_module' 			=> array(1, 5, 11),

			// 6. KHO HÀNG DỮ LIỆU
			'admin_order' 			=> array(1, 5, 11),
			'admin_depot_receipt'	=> array(1, 5, 11),
			'admin_depot_delivery'	=> array(1, 5, 11),
			'admin_depot'			=> array(1, 5, 11),
			'admin_depot_product'	=> array(1, 5, 11),
			'admin_depot_revenue'	=> array(1, 5, 11),
			'admin_depot_check'		=> array(1, 5, 11),

			// 7. TÀI KHOẢN
			'admin_account' 		=> array(1, 5, 11),
//			'admin_user' 			=> array(1, 5, 11),
			'admin_member' 			=> array(1, 5, 11),
//			'admin_customer'		=> array(1, 5, 11),
//			'admin_site'			=> array(1, 5, 11),

			// 8. HỆ THỐNG
//			'admin_setting' 		=> array(1, 3, 6),
//			'admin_cache' 			=> array(1, 4, 5)
		);

		// Reconciliation
		$this->group_reconciliation_pages = array(
		);

		// Support
		$this->group_support_pages = array(
		);

		// Translator
		$this->group_translator_pages = array(
		);

		/*
		// Add module install for page
		if ( isset($list_modules) && $list_modules ){
			$valuePermision = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
			$valuePermision_user = array(1, 2, 3, 5, 6, 7, 8, 9);
			$valuePermision_sale = array(1, 5, 11);

			foreach( $list_modules as $key => $module ){
				$filename = 'admin_' . $key;
				if ( !in_array($filename, $this->pages) ){
					array_push_key($this->pages, $filename, $valuePermision);
				}
				if ( !in_array($filename, $this->group_manager_site_pages) ){
					array_push_key($this->group_manager_site_pages, $filename, $valuePermision);
				}
				if ( !in_array($filename, $this->group_user_site_pages) ){
					array_push_key($this->group_user_site_pages, $filename, $valuePermision_user);
				}
				if ( !in_array($filename, $this->group_manager_all_site_pages) ){
					array_push_key($this->group_manager_all_site_pages, $filename, $valuePermision);
				}
				if ( !in_array($filename, $this->group_manager_sale_pages) ){
					array_push_key($this->group_manager_sale_pages, $filename, $valuePermision_sale);
				}
				if ( !in_array($filename, $this->group_sale_pages) ){
					array_push_key($this->group_sale_pages, $filename, $valuePermision_sale);
				}
			}
		}
		*/
	}

	/**
	 * Check permission site default
	 */
	function checkPermissionSiteDefault($page, $action){
		global $database, $admin, $uri, $list_modules;

		if ( !$admin->admin_site_default )
			return;

		$aryPage = array(
			'admin_category',
			'admin_product',
			'admin_partner',
			'admin_trademark',
			'admin_landingpage',
			'admin_content',
			'admin_static',
			'admin_custom',
			'admin_faqs',
			'admin_quote',
			'admin_banner',
			'admin_video',
			'admin_manager_menu',
			'admin_tag',
			'admin_order'
		);
		/*
		// Add module install for page
		if ( isset($list_modules) && $list_modules ) {
			foreach ($list_modules as $key => $module) {
				$filename = 'admin_' . $key;
				if ( !in_array($filename, $aryPage) ){
					array_push($aryPage, $filename);
				}
			}
		}
		*/
		$aryAction = array(
			'edit',
			'publish',
			'unpublish',
			'remove'
		);

		if ( !in_array($page, $aryPage) ){
			return;
		}

		if ( in_array($action, $aryAction) ){
			if ( $page == 'admin_category' ){
				$table = TBL_CATEGORY;
				$field = 'category_site_id';
				$nameId = 'category_id';
			}else if ( $page == 'admin_product' ){
				$table = TBL_PRODUCT;
				$field = 'product_site_id';
				$nameId = 'product_id';
			}else if ( $page == 'admin_partner' ){
				$table = TBL_PARTNER;
				$field = 'partner_site_id';
				$nameId = 'partner_id';
			}else if ( $page == 'admin_trademark' ){
				$table = TBL_TRADEMARK;
				$field = 'tradenark_site_id';
				$nameId = 'trademark_id';
			}else if ( $page == 'admin_landingpage' ){
				$table = TBL_LANDINGPAGE;
				$field = 'site_id';
				$nameId = 'landingpage_id';
			}else if ( $page == 'admin_content' ){
				$table = TBL_CONTENT;
				$field = 'content_site_id';
				$nameId = 'content_id';
			}else if ( $page == 'admin_static' ){
				$table = TBL_STATIC;
				$field = 'static_site_id';
				$nameId = 'static_id';
			}else if ( $page == 'admin_custom' ){
				$table = TBL_CUSTOM;
				$field = 'custom_site_id';
				$nameId = 'custom_id';
			}else if ( $page == 'admin_faqs' ){
				$table = TBL_ANSWER;
				$field = 'answer_site_id';
				$nameId = 'id';
			}else if ( $page == 'admin_quote' ){
				$table = TBL_QUOTE;
				$field = 'quote_site_id';
				$nameId = 'quote_id';
			}else if ( $page == 'admin_banner' ){
				$table = TBL_BANNER;
				$field = 'banner_site_id';
				$nameId = 'banner_id';
			}else if ( $page == 'admin_video' ){
				$table = TBL_VIDEO;
				$field = 'video_site_id';
				$nameId = 'video_id';
			}else if ( $page == 'admin_manager_menu' ){
				$table = TBL_MENU;
				$field = 'menu_site_id';
				$nameId = 'menu_id';
			}else if ( $page == 'admin_tag' ){
				$table = TBL_TAG;
				$field = 'tag_site_id';
				$nameId = 'tag_id';
			}else if ( $page == 'admin_order' ){
				$table = TBL_ORDER;
				$field = 'order_site_id';
				$nameId = 'order_id';
//		}else if ( $page == 'admin_depot_receipt' ){
//			$table = TBL_DEPOT_RECEIPT;
//			$field = 'site_id';
//		}else if ( $page == 'admin_depot_delivery' ){
//			$table = TBL_DEPOT_DELIVERY;
//			$field = 'site_id';
//		}else{
//			$table = "";
//			$field = "";
			}else{
				$table = "";
				$field = 'site_id';
				$nameId = 'id';
			}

			$cid = PGRequest::getVar( 'cid', array(), 'post', 'array' );
			if ( is_array($cid) && count($cid))
				$valueId = $cid[0];
			else
				$valueId = PGRequest::GetInt($nameId, 0, 'GET');

			$sql = "SELECT ".$field." FROM ".$table." WHERE ".$nameId."=".$valueId." LIMIT 1";
			$result = $database->db_query($sql);
			if ( $row = $database->db_fetch_assoc($result) ){
				$check_site_id = $row[$field];
				if ( $check_site_id <> $admin->admin_site_default['site_id'] ){
					PGError::set_error('Bạn không thể sử dụng dữ liệu site khác khi đang cấu hình mặc định sử dụng site: <b>' . $admin->admin_site_default['site_name'] . '</b>');
					cheader($uri->base().$page.'.php');
				}else
					return;
			}else{
				PGError::set_error('Không tồn tại bản ghi bạn muốn '. $action);
				cheader($uri->base().$page.'.php');
			}
			return;
		}else
			return;
	}
	
	/**
	 * check permission of user
	 * @date created: 2010-09-29
	 * @param $type: type access for check
	 * return boolean true if pass and false if not pass
	 *
	 */
	function checkPermission($page, $action) {
		
		global $database;
		global $admin;

		if ( $action == 'add_color_site' || $action == 'delete_color_site' || $action == 'unlinkFile' )
			return true;

		$this->checkPermissionSiteDefault($page, $action);

		$aryAccess = array();
		
		$aryMethod = array_flip($this->atl);
		$actionId = isset($aryMethod[$action])?$aryMethod[$action]:0;
		if ($admin->admin_super || $admin->admin_info['admin_group'] == 1) return true;
		
		if ($admin->admin_info['admin_access']) {
			$aryAccess = unserialize($admin->admin_info['admin_access']);
			foreach ($aryAccess as $key=>$action_info) {
				if ($key == $page) {
					if (is_array($action_info) && in_array($actionId, $action_info)) {
						return true;
						break;
					}
				}
			}
		}
		
		return false;
	}
	
	/**
	 * show page error
	 * @date created: 2010-10-01
	 * @param $smarty: object smarty
	 * return void
	 */
	function showErrorPage($smarty) {
		$smarty->assign("error_header", "Lỗi truy cập");
		$smarty->assign("error_message", "Bạn không có quyền truy cập chức năng này");
		$smarty->assign("error_submit", "Quay lại");
		$toolbar  = '';
		$page = "admin_error";
		$page_title = "Thông báo lỗi truy cập";
		$task = '';		
		include "admin_footer.php";
		exit();
	}

	/**
	 * Check permission of admin
	 * Default site => return true
	 * Else => return error
	 */
	function checkPermissionAdminDefaultsite( &$page ){
		global $admin, $uri, $task;

		if ( !$admin->admin_site_default ){
			$error = 'Bạn cần cấu hình tài khoản quản trị của mình trực thuộc trường học cần thao tác!';
			PGError::set_error($error);
			cheader($uri->base().$page.'.php');
		}
		if ( ($task == 'add' || $task == 'remove') &&!$admin->admin_site_default['admin_create'] ){
			$error = 'Không được phép thao tác trường học trực thuộc HTTT!';
			PGError::set_error($error);
			cheader($uri->base().$page.'.php');
		}
		return;
	}

	/**
	 * Check permission remove account, subject
	 */
	function checkPermissionRemoveOfAdmin( &$page ){
		global $admin, $uri;

		if ( !$admin->admin_super ){
			$error = 'Bạn không đủ quyền xóa dữ liệu, chỉ tài khoản quản trị cấp cao mới được phép xóa dữ liệu này!';
			PGError::set_error($error);
			cheader($uri->base().$page.'.php');
		}
	}

}
