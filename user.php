<?php
/**
 * Logic xu ly cua module thanh vien
 * Quyen user co 3 quyen
 * 1. administrator: Co tat ca cac quyen
 * 2. manager-site: Quan ly user tren site
 * 3. user-site: Co quyen tren site (order)
 * 
 * I. Chuc nang list user: List tat ca user voi dieu kien check quyen user:
 * 		1. Neu la super-admin: list tat ca user khac chinh no
 * 		1. Neu la admin: Thi list ra tat ca user co id > 1 va khac no
 * 		2. Neu la manager-site: List tat ca user theo dieu kien site-id va khac no
 * 		3. Neu la user-site: Khong list duoc ra ban ghi nao
 */
$page = "user";
$page_title = "Quản trị thành viên";
include "header.php";

$task = PGRequest::getCmd('task', 'view');
if ($task=='cancel') $task='view';

$objAcl = new PGAcl();
if (!$objAcl->checkPermission($page, $task)) {
	$objAcl->showErrorPage($smarty);
}

switch ($task) {
	case "view":
		$page_title = "Quản trị thành viên";
		$filter_group = PGRequest::getInt('filter_group', 0, 'POST');
		$filter_status = PGRequest::getInt('filter_status', 0, 'POST');
		$search = strtolower( PGRequest::getString('search', '', 'POST') );
		
		$p = PGRequest::getInt('p', 1, 'POST');
		$limit = PGRequest::getInt('limit', $setting['setting_list_limit'], 'POST');
		
		$adminId = $admin->admin_info['admin_id'];
		
		if ($admin->admin_super) {
			$where[] = " admin_id>1";
		}
		elseif ($admin->admin_info['admin_group'] == 1) {
			$where[] = " admin_id>1 AND admin_group>1 AND admin_id<>".$adminId;
		}
		elseif ($admin->admin_info['admin_group'] == 2) {
			$sql2 = "SELECT admin_id FROM ".TBL_ADMIN." WHERE admin_created=".$adminId;
			$results2 = $database->getCol($database->db_query($sql2));
			
			$result = array_unique(array_merge_recursive((array)$results1, (array)$results2));
			$strAdminId = (count($result)) ? join(",", $result) : 0;
			$where[] = " admin_id>1 AND admin_group>2 AND admin_id IN(".$strAdminId.") AND admin_id<>".$adminId;
		}
		else {
			$where[] = " admin_group>3";
		}
		
		//LOC THEO TU KHOA
		if ($search){
			$where[] = "(admin_name LIKE '%".$search."%' OR admin_email LIKE '%".$search."%' OR admin_username LIKE '%".$search."%')";
		}
		//CHON TAT CA USER THEO GROUP
		if ($filter_group) {			
			$where[] = " admin_group=".$filter_group;
		}
		//CHON TAT CA USER TRANG THAI KICH HOAT
		if ($filter_status) {			
			$where[] = " admin_enabled=".($filter_status-1);
		}
		
		// BUILD THE WHERE CLAUSE OF THE CONTENT RECORD QUERY
		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');
		
		// GET THE TOTAL NUMBER OF RECORDS
		$query = "SELECT COUNT(*) AS total FROM ".TBL_ADMIN." $where";
		$results = $database->db_fetch_assoc($database->db_query($query));
		
		// PHAN TRANG
		$pager = new pager($limit, $results['total'], $p);
		$offset = $pager->offset;
		
		// LAY DANH SACH CHI TIET
		$query = "SELECT * FROM ".TBL_ADMIN." $where LIMIT $offset, $limit";
		$results = $database->db_query($query);
		
		//ARRAY GROUP USERS
		$objAcl = new PGAcl();
		$arrGroup = $objAcl->apl;
		$arrPermiss = $objAcl->atl;
		
		$arySiteId = $admin->get_list_sites();
		while ($row = $database->db_fetch_assoc($results)){
			$row['admin_registerDate'] = $datetime->datetimeDisplay($row['admin_registerDate']);
			$row['admin_lastvisitDate']	= $datetime->datetimeDisplay($row['admin_lastvisitDate']);
			$row['admin_user_group'] = $row['admin_group'];
			$row['admin_group']	= $arrGroup[$row['admin_group']];
			$row['admin_site'] = array();
			if ($row['admin_access']) {
				$aryAccess = unserialize($row['admin_access']);
				
				$pageAccess = array();
				foreach ($aryAccess as $key=>$access) {
					if (count($access) && is_array($access)) {
						foreach ($access as $kp=>$perm) {
							$pageAccess[$key][$kp] = $arrPermiss[$perm];
						}
						$pageAccess[$key] = join(", ", $pageAccess[$key]);
					}
				}
				$row['admin_access'] = $pageAccess;
			}
			foreach ($arySiteId as $keyS=>$itemS) {
				if ($row['admin_id'] == $itemS['admin_id']) {
					$row['admin_site'][] = $itemS['site_name'];
				}
			}
			$users[] = $row;
		}

		if (isset($users)) $smarty->assign('users', $users);
		$smarty->assign('totalRecords', $results['total']);
		$smarty->assign('filter_status', $filter_status);
		$smarty->assign('filter_group', $filter_group);
		$smarty->assign('search', $search);
		$smarty->assign('arrGroup', $arrGroup);
		$smarty->assign('datapage', $pager->page_link());
		$smarty->assign('p', $p);
		break;
		
	case "add":
		$page_title = "Thêm mới thành viên";
		
		//ARRAY GROUP USERS
		$objAcl = new PGAcl();
		$arrGroup = $objAcl->apl;
		$arrPermiss = $objAcl->atl;
		$pages = $objAcl->pages;
		
		$adminAccess = ($admin->admin_info['admin_access']) ? unserialize($admin->admin_info['admin_access']) : array();
		
		$ajax = PGRequest::getInt('ajax', 0);
		if ($ajax) {
			$aryInput['admin_name'] = PGRequest::getVar('admin_name', '', 'POST');
			$aryInput['admin_email'] = PGRequest::getVar('admin_email', '', 'POST');
			$aryInput['admin_username'] = PGRequest::getVar('admin_username', '', 'POST');
			$aryInput['admin_password'] = PGRequest::getVar('admin_password', '', 'POST');
			$aryInput['admin_group'] = PGRequest::getInt('cbo_group', 0, 'POST');
			$aryInput['admin_site'] = PGRequest::getVar('cbo_site', '', 'POST');
			
			$pageAccess = array();
			foreach ($pages as $key1=>$ps1) {
				$pageAccess[$key1] = PGRequest::getVar($key1, '', 'POST');
			}
			
			$aryOutput = array();
			$aryOutput['intOK'] = 1;
			
			//THUC HIEN CHECK THONG TIN INPUT
			$admin->check_user_input($aryInput);
			
			if (!$admin->is_error) {
				$username = $aryInput['admin_username'];
				$password = $aryInput['admin_password'];
				$name = $database->db_real_escape_string($aryInput['admin_name']);
				$email = $aryInput['admin_email'];
				$password = $aryInput['admin_password'];
				$group = $aryInput['admin_group'];
				
				$access = (count($pageAccess)) ? serialize($pageAccess) : '';
				if ($group == 1) $access = '';
				
		    	if (!$admin->admin_create($username, $password, $name, $email, $group, $access)) {
		    		$aryOutput['strError'] = "Lỗi hệ thống";
		    		$aryOutput['intOK'] = 0;
		    	}
		    	else {
		    		if ($group > 1) {
		    			$userId = $database->db_insert_id();
		    			$admin->insertAdminSite($userId, $aryInput['admin_site']);
		    		}
		    	}
		  	}
		  	else {
		  		$aryOutput['strError'] = (is_array($admin->is_error))?join("<br>", $admin->is_error):"";
		  		$aryOutput['intOK'] = 0;
		  	}
		  	echo json_encode($aryOutput);
			exit();
		}
		
		$aryPages = array();
		foreach ($pages as $key1=>$ps1) {
			foreach ($ps1 as $key2=>$ps2) {
				$aryPages[$key1][$ps2] = $arrPermiss[$ps2];
			}
		}
	
		$smarty->assign('arrGroup', $arrGroup);
		$smarty->assign('userInfo', $admin->admin_info);
		$smarty->assign('arrPermiss', $arrPermiss);
		$smarty->assign('adminAccess', $adminAccess);
		$smarty->assign('aryPages', $aryPages);
		
		break;
		
	case "edit":
		$page_title = "Sửa thông tin thành viên";
		
		//ARRAY GROUP USERS
		$objAcl = new PGAcl();
		$arrGroup = $objAcl->apl;
		$arrPermiss = $objAcl->atl;
		$pages = $objAcl->pages;

		$adminId = PGRequest::getInt('id', 0);
		$ajax = PGRequest::getInt('ajax', 0);
		
		$adminEdit = new PGAdmin($adminId);

		$query = "SELECT * FROM ".TBL_ADMIN." WHERE admin_id>1 AND admin_id=".$adminId;
		if ($admin->admin_info['admin_group'] > 1) {
			$query .= " AND admin_group>".$admin->admin_info['admin_group'];
		}
		$aryAdmin = $database->getRow($database->db_query($query));
		
		if (!$aryAdmin) cheader($uri->base().'admin_users.php');

		if ($adminId) {
			$arySiteId = $database->getCol($database->db_query("SELECT site_id FROM ".TBL_ADMIN_SITE." WHERE admin_id=".$adminId));
		}

		$aryAccess = array();
		if ($aryAdmin['admin_access']) {
			$aryAccess = unserialize($aryAdmin['admin_access']);
		}

		if ($ajax) {
			$aryInput['admin_id'] = $adminId;
			$aryInput['admin_name'] = PGRequest::getVar('admin_name', '', 'POST');
			$aryInput['admin_email'] = PGRequest::getVar('admin_email', '', 'POST');
			$aryInput['admin_username'] = PGRequest::getVar('admin_username', '', 'POST');
			$aryInput['admin_password'] = PGRequest::getVar('admin_password', '', 'POST');
			$aryInput['admin_group'] = PGRequest::getInt('cbo_group', 0, 'POST');
			$aryInput['admin_enabled'] = PGRequest::getInt('admin_enabled', 0, 'POST');
			$aryInput['admin_site'] = PGRequest::getVar('cbo_site', '', 'POST');
			
			$pageAccess = array();
			foreach ($pages as $key1=>$ps1) {
				$pageAccess[$key1] = PGRequest::getVar($key1, '', 'POST');
			}
			$aryInput['admin_access'] = (count($pageAccess)) ? serialize($pageAccess) : '';
			if ($aryInput['admin_group'] == 1) $aryInput['admin_access'] = '';
			
			$aryOutput = array();
			$aryOutput['intOK'] = 1;
			
			//THUC HIEN CHECK THONG TIN INPUT
			$adminEdit->check_user_input($aryInput, true);
			
			if (!$adminEdit->is_error) {
		    	if (!$adminEdit->update_user($aryInput)) {
		    		$aryOutput['strError'] = "Lỗi hệ thống";
		    		$aryOutput['intOK'] = 0;
		    	}
		    	else {
					if ( $aryInput['admin_site'] )
	    				$adminEdit->insertAdminSite($adminId, $aryInput['admin_site'], $aryInput['admin_group']);
		    	}
		  	}
		  	else {
		  		$aryOutput['strError'] = (is_array($adminEdit->is_error))?join("<br>", $adminEdit->is_error):"";
		  		$aryOutput['intOK'] = 0;
		  	}
		  	
		  	echo json_encode($aryOutput);
			exit();
		}
		
		$aryPages = array();
		foreach ($pages as $key1=>$ps1) {
			foreach ($ps1 as $key2=>$ps2) {
				$aryPages[$key1][$ps2] = $arrPermiss[$ps2];
			}
		}
		$adminAccess = ($admin->admin_info['admin_access']) ? unserialize($admin->admin_info['admin_access']) : array();
		$smarty->assign('adminAccess', $adminAccess);
		$smarty->assign('arrGroup', $arrGroup);
		$smarty->assign('arySiteId', $arySiteId);
		$smarty->assign('aryAccess', $aryAccess);
		$smarty->assign('arrPermiss', $arrPermiss);
		$smarty->assign('adminId', $adminId);
		$smarty->assign('users', $aryAdmin);
		$smarty->assign('aryPages', $aryPages);
		$smarty->assign('userInfo', $admin->admin_info);
		break;

	case "remove":
		$cid = PGRequest::getVar( 'cid', array(), 'post', 'array' );
		if (count($cid)) {
		  	$database->db_query("DELETE FROM ".TBL_ADMIN." WHERE admin_id IN(".implode(",", $cid).")");
		  	$database->db_query("DELETE FROM ".TBL_ADMIN_SITE." WHERE admin_id IN(".implode(",", $cid).")");
		}
		cheader($uri->base().'admin_user.php');
		
		break;
	
	case "delcache":
		memcacheLib::clear();
		cheader($_SERVER['HTTP_REFERER']);
		break;

    case "change_group":
        $group_id = PGRequest::getVar('group_id', '', 'POST');
        $adminId = PGRequest::getInt('admin_id', 0);
        $objAcl = new PGAcl();
        $arrGroup = $objAcl->apl;
        $arrPermiss = $objAcl->atl;
        $pages = array();
        $userInfo = $admin->admin_info;

        $group_pages_default = $objAcl->group_manager_site_pages;
        if ( $group_id ){
            if ( $group_id == 2 ) // Manager School
                $pages = $objAcl->group_manager_site_pages;
            else if ( $group_id == 3 ) // User School
                $pages = $objAcl->group_user_site_pages;
            else if ( $group_id == 4 ) // All School
                $pages = $objAcl->group_manager_all_site_pages;
            else if ( $group_id == 5 ) // Sale Manager
                $pages = $objAcl->group_manager_sale_pages;
            else if ( $group_id == 6 ) // Sale
                $pages = $objAcl->group_sale_pages;
            else if ( $group_id == 7 ) // Reconciliation
                $pages = $objAcl->group_reconciliation_pages;
            else if ( $group_id == 8 ) // Support
                $pages = $objAcl->group_support_pages;
            else if ( $group_id == 9 ) // Translator
                $pages = $objAcl->group_translator_pages;
            else
                $pages = $objAcl->group_translator_pages;

        }

        $aryPages = array();//Conver type acl
        foreach ($pages as $key1=>$ps1) {
            foreach ($ps1 as $key2=>$ps2) {
                $aryPages[$key1][$ps2] = $arrPermiss[$ps2];
            }
        }

        $adminAccess = array();
        $aryAccess = array();
        if($adminId) {
            $adminAccess = ($admin->admin_info['admin_access']) ? unserialize($admin->admin_info['admin_access']) : array();
            $query = "SELECT * FROM " . TBL_ADMIN . " WHERE admin_id>1 AND admin_id=" . $adminId;
            if ($admin->admin_info['admin_group'] > 1) {
                $query .= " AND admin_group>" . $admin->admin_info['admin_group'];
            }
            $aryAdmin = $database->getRow($database->db_query($query));
            if (!$aryAdmin) cheader($uri->base() . 'admin_users.php');
            $aryAccess = array();
            if ($aryAdmin['admin_access']) {
                $aryAccess = unserialize($aryAdmin['admin_access']);
            }
        }

        $group_pages = $objAcl->group_pages;
        $html = get_pages_by_group_id($group_id,$userInfo,$aryPages,$adminAccess,$aryAccess);
        if($html){
            $request['status'] = true;
            $request['html'] = $html;
        }else{
            $request['status'] = false;
            $request['html'] = '';
        }
        echo json_encode($request);
        exit();
        break;
}

function get_pages_by_group_id($group_id=null,$userInfo,$aryPages,$adminAccess,$aryAccess){
    $html = '';
    if(count($aryPages)&&$group_id) {
        foreach ($aryPages as $kp => $pages) {
            $html .= '<b>' . $kp . '</b><br>';
            foreach ($pages as $kp1 => $access) {
                $html .= '<label style="font-size: 13px;" for="' . $kp . '_' . $kp1 . '">';
                if ($userInfo . admin_group > 1) {
                    foreach ($adminAccess as $kp2 => $access2) {
                        if ($kp == $kp2) {
                            foreach ($access2 as $kp3 => $access3) {
                                if ($access3 == $kp1) {
                                    $html .= '<span class="lbl">' . $access . '</span>&nbsp;&nbsp;';
                                    $html .= '<input type="checkbox" value="' . $kp1 . '" name="' . $kp . '[]" id="' . $kp . '_' . $kp1 . '"';
                                    foreach ($aryAccess[$kp] as $kp2 => $access2) {
                                        if ($access2 == $kp1) {
                                            $html .= 'checked';
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $html .= '<span class="lbl">' . $access . '</span>&nbsp;&nbsp;';
                    $html .= '<input type="checkbox" value="' . $kp1 . '" name="' . $kp . '[]" id="' . $kp . '_' . $kp1 . '"';
                    foreach ($aryAccess[$kp] as $kp2 => $access2) {
                        if ($access2 == $kp1) {
                            $html .= 'checked';
                        }
                    }
                }
                $html .= '></label> &nbsp;';
            }
            $html .= '<br><br>';
        }
    }else{
        $html = '<h4>Chưa tồn tại quyền cho nhóm này!</h4>';
    }
    return $html;
}

$smarty->assign('sites', $sites);

if ($task == 'view') {	
	$toolbar = createToolbarAce('add','remove');
}
elseif ($task == 'edit' || $task == 'add') {
	$toolbar = createToolbarAce('save','cancel');
}

include "footer.php";
?>