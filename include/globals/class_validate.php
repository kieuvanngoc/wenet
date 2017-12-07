<?php
defined('PG_PAGE') or die();

class PGValidation {
    /**
     * construct method
     */
    public function __construct() {}
    
    /**
	 * Check string member login
	 * @param string $strLogin
	 * @return boolean
	 */
	public static function isAlnum($str) {
		if (preg_match("/^[A-Za-z0-9_s]+$/", (string) $str)) {
			return true;
		}
		return false;
	}
	

    public static function isEmail($email) {
        $result = false;
        $pattern = '/^(([a-z0-9!#$%&\/*+-=?^_`\'{|}~]'.
                '[a-z0-9!#$%&\/*+-=?^_`\'{|}~.]*'.
                '[a-z0-9!#$%&\/*+-=?^_`\'{|}~])'.
                '|[a-z0-9!#$%&\/*+-?^_`\'{|}~]|'.
                '("[^",<>]+"))'.
                '[@]((?:[-a-z0-9]+\.)+[a-z]{2,})$/ix';
    
        $value = str_replace('\"', '', $email);
        $result = preg_match($pattern, $value);
        if ($result) {
            $aryItem = explode('@', $email);
            array_pop($aryItem);
            $value1 = join('@', $aryItem);
            if (strpos($value1, '..') !== false || $value1{0} == '.' || $value1{strlen($value1) - 1} == '.') {
                $result = false;
            }
        }
        return $result;
    }
    
    /**
     * Checks that a value is a valid URL according to http://www.w3.org/Addressing/URL/url-spec.txt
     *
     * @param string $check Value to check
     * @return boolean Success
     * @access public
     */
    public static function isURL($url, $domain=false) {
        if (!$domain) return preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
        else return preg_match('|^[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }

    public static function isNumber() {}

    public static function isDate() {}

    public static function isTime() {}

    public static function isPhone($string) {
        $numbersOnly = ereg_replace("[^0-9]", "", $string);
        $numberOfDigits = strlen($numbersOnly);
        if ($numberOfDigits == 7 || $numberOfDigits == 10 || $numberOfDigits == 11) {
            return $numbersOnly;
        } else {
            return false;
        }
    }

    public static function isMobile($string){
        $numbersOnly = ereg_replace("[^0-9]", "", $string);
        $numberOfDigits = strlen($numbersOnly);
        if ($numberOfDigits == 10 || $numberOfDigits == 11) {
            return $numbersOnly;
        } else {
            return false;
        }
    }

    /**
     * Check exist email in system
     */
    public function checkExistEmail($email, $account_type){
        global $database;

        if ( !$email || !$account_type ) return false;

        $email = strtolower($email);

        $output['isOk'] = 1;
        if ($account_type == SET_VALUE_GIAO_VIEN) {
            $table_name = TBL_GIAO_VIEN;
            $name_system = 'giáo viên';
        }else if ($account_type == SET_VALUE_PHU_HUYNH) {
            $table_name = TBL_PHU_HUYNH;
            $name_system = 'phụ huynh';
        }else if ($account_type == SET_VALUE_HOC_SINH) {
            $table_name = TBL_HOC_SINH;
            $name_system = 'phụ huynh';
        }

        if ( $database->db_num_rows($database->db_query("SELECT id FROM ".$table_name." WHERE LOWER(email)='{$email}'")) ) {
            $output['isOk'] = 0;
            $output['error'] = 'Địa chỉ email này đã được sử dụng.';
            $output['name'] = $table_name;
        }

        return $output;
    }

    /*
     * Check exist phone number in system
     */
    public function checkExistMobile($mobile, $account_type){
        global $database;

        if ( !$mobile || !$account_type ) return false;

        $output['isOk'] = 1;
        if ($account_type == SET_VALUE_GIAO_VIEN) {
            $table_name = TBL_GIAO_VIEN;
                $name_system = 'giáo viên';
            }else if ($account_type == SET_VALUE_PHU_HUYNH) {
            $table_name = TBL_PHU_HUYNH;
            $name_system = 'phụ huynh';
        }else if ($account_type == SET_VALUE_HOC_SINH) {
            $table_name = TBL_HOC_SINH;
        }

        if ( $database->db_num_rows($database->db_query("SELECT id FROM ".$table_name." WHERE phone='{$mobile}'")) ) {
            $output['isOk'] = 0;
            $output['error'] = 'Số điện thoại này đã được sử dụng.';
            $output['name'] = $table_name;
        }
        return $output;
    }
}
?>
