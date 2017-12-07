<?php
/**
 * Created by PhpStorm.
 * User: Ngọc Kiều
 * Date: 11/23/2016
 * Time: 5:06 PM
 */
define('ORGANIZATION_SOURCE_DATA', 'enetviet.com');
require_once(dirname(dirname(__FILE__)) . '/services/class_sync.php');

class PGOrganization extends PGSync{
    /**
     * NHÀ TRƯỜNG
     */
    public function check_school_input($aryInput){
        global $database;

        if ($aryInput['ma_so'] == '') {
            $this->is_error[] = 'Hãy chọn mã sở trực thuộc trường!';
        }
        if ($aryInput['ma_phong'] =='') {
            $this->is_error[] = 'Hãy chọn mã phòng trực thuộc trường!';
        }
        if ($aryInput['ma_truong'] =='') {
            $this->is_error[] = 'Hãy nhập mã trường!';
        }
        if ($database->db_num_rows($database->db_query("SELECT ma_truong FROM ".TBL_NHA_TRUONG." WHERE LOWER(ma_truong)='".strtolower($aryInput['ma_truong'])."'")) ) {
            $this->is_error[] = 'Mã trường đã tồn tại trong hệ thống. Hãy chọn 1 mã trường khác hoặc liên hệ quản trị viên để được kích hoạt!';
        }
        if (strlen($aryInput['ten_hien_thi']) < 6) {
            $this->is_error[] = 'Tên trường phải ít nhất 6 ký tự!';
        }
        if ($aryInput['nam_hoc'] == '') {
            $this->is_error[] = 'Hãy nhập mã năm học hiện tại!';
        }
        return true;
    }
    public function saveSchool($aryData, $cap_hoc = false, $admin_create = false){
        $aryInput[0] = $aryData;
        $jsonData = json_encode($aryInput);
        $source_data = ORGANIZATION_SOURCE_DATA;
        return $this->serviceTruong($jsonData, $cap_hoc, $source_data, $admin_create);
    }

    /**
     * KHỐI LỚP HỌC
     */
    public function get_list_grades( &$cap_hoc ){
        if ( !$cap_hoc ) return false;

        if ( $cap_hoc == 1 ){
            $arrayLevel = array(
                '1'     => 'Khối 1',
                '2'     => 'Khối 2',
                '3'     => 'Khối 3',
                '4'     => 'Khối 4',
                '5'     => 'Khối 5'
            );
        }else if ( $cap_hoc == 2 ){
            $arrayLevel = array(
                '6'     => 'Khối 6',
                '7'     => 'Khối 7',
                '8'     => 'Khối 8',
                '9'     => 'Khối 9'
            );
        }else if ( $cap_hoc == 3 ){
            $arrayLevel = array(
                '10'     => 'Khối 10',
                '11'     => 'Khối 11',
                '12'     => 'Khối 12'
            );
        }
        return $arrayLevel;
    }
    public function saveGrade( &$aryData ){
        $jsonData = json_encode($aryData);
        $source_data = ORGANIZATION_SOURCE_DATA;
        return $this->serviceKhoi($jsonData, $source_data);
    }

    /**
     * LỚP HỌC
     */
    public function check_class_input($aryInput, $update = false, $courseId = false){
        global $database;

        if (trim($aryInput['ten_lop']) == '') {
            $this->is_error[] = 'Tên lớp học vui lòng không để trống!';
        }
        if ( !$update ){
            if ($database->db_num_rows($database->db_query("SELECT id FROM ".TBL_LOP_HOC." WHERE LOWER(ma_truong)='".strtolower($aryInput['ma_truong'])."' AND LOWER(ten_lop)='".strtolower(trim($aryInput['ten_lop']))."'")) ) {
                $this->is_error[] = 'Tên lớp học '.$aryInput['ten_lop'].' đã tồn tại trong trường học. Vui lòng kiểm tra lại!';
            }
            if ($database->db_num_rows($database->db_query("SELECT ma_lop FROM ".TBL_LOP_HOC." WHERE LOWER(ma_lop)='".strtolower($aryInput['ma_lop'])."'")) ) {
                $this->is_error[] = 'Mã lớp '.$aryInput['ma_lop'].' đã tồn tại trong hệ thống. Liên hệ quản trị viên để được hỗ trợ';
            }
        }else{
            if ( $courseId ){
                if ($database->db_num_rows($database->db_query("SELECT id FROM ".TBL_LOP_HOC." WHERE id <> ".$courseId." AND LOWER(ma_truong)='".strtolower($aryInput['ma_truong'])."' AND LOWER(ten_lop)='".strtolower(trim($aryInput['ten_lop']))."'")) ) {
                    $this->is_error[] = 'Tên lớp học '.$aryInput['ten_lop'].' đã tồn tại trong trường học. Vui lòng kiểm tra lại!';
                }
            }
        }
        if ($aryInput['ma_khoi'] =='') {
            $this->is_error[] = 'Vui lòng chọn khối lớp học!';
        }
        return true;
    }
    public function saveCourse( &$aryData ){
        $jsonData = json_encode($aryData);
        $source_data = ORGANIZATION_SOURCE_DATA;
        return $this->serviceLophoc($jsonData, $source_data);
    }

    /**
     * MÔN HỌC
     */
    public function get_list_default_subjects( &$cap_hoc ){
        $arySubject = array();
        if ( !$cap_hoc ){

        }

        if ( $cap_hoc == 1 ) {
            $arySubject = array(
                'TV' => array('name' => 'Tiếng Việt', 'type' => 1),
                'TO' => array('name' => 'Toán', 'type' => 1),
                'KH' => array('name' => 'Khoa học', 'type' => 1),
                'LD' => array('name' => 'Lịch sử và Địa lý', 'type' => 1),
                'NN' => array('name' => 'Ngoại ngữ', 'type' => 1),
                'TH' => array('name' => 'Tin học', 'type' => 1),
                'DT' => array('name' => 'Tiếng dân tộc', 'type' => 1),
                'TX' => array('name' => 'Tự nhiên và Xã hội', 'type' => 2),
                'DD' => array('name' => 'Đạo đức', 'type' => 2),
                'AN' => array('name' => 'Âm nhạc', 'type' => 2),
                'MT' => array('name' => 'Mỹ thuật', 'type' => 2),
                'TC' => array('name' => 'Thủ công', 'type' => 2),
                'TD' => array('name' => 'Thể dục', 'type' => 2)
            );
        }else if ( $cap_hoc == 2 ){
            $arySubject = array(
                'VA' => array('name' => 'Ngữ văn', 'type' => 1),
                'TO' => array('name' => 'Toán', 'type' => 1),
                'LY' => array('name' => 'Vật lý', 'type' => 1),
                'HO' => array('name' => 'Hóa học', 'type' => 1),
                'SI' => array('name' => 'Sinh học', 'type' => 1),
                'SU' => array('name' => 'Lịch sử', 'type' => 1),
                'DI' => array('name' => 'Địa lý', 'type' => 1),
                'GD' => array('name' => 'GDCD', 'type' => 1),
                'TA' => array('name' => 'Tiếng Anh', 'type' => 1),
                'TI' => array('name' => 'Tin học', 'type' => 1),
                'CN' => array('name' => 'Công nghệ', 'type' => 1),
                'AN' => array('name' => 'Âm nhạc', 'type' => 2),
                'MT' => array('name' => 'Mỹ thuật', 'type' => 2),
                'TD' => array('name' => 'Thể dục', 'type' => 2)
            );
        }else if ( $cap_hoc == 3 ){
            $arySubject = array(
                'VA' => array('name' => 'Ngữ văn', 'type' => 1),
                'TO' => array('name' => 'Toán', 'type' => 1),
                'LY' => array('name' => 'Vật lý', 'type' => 1),
                'HO' => array('name' => 'Hóa học', 'type' => 1),
                'SI' => array('name' => 'Sinh học', 'type' => 1),
                'SU' => array('name' => 'Lịch sử', 'type' => 1),
                'DI' => array('name' => 'Địa lý', 'type' => 1),
                'GD' => array('name' => 'GDCD', 'type' => 1),
                'TA' => array('name' => 'Tiếng Anh', 'type' => 1),
                'TI' => array('name' => 'Tin học', 'type' => 1),
                'CN' => array('name' => 'Công nghệ', 'type' => 1),
                'QP' => array('name' => 'GDQP', 'type' => 1),
                'TD' => array('name' => 'Thể dục', 'type' => 2)
            );
        }

        return $arySubject;
    }
    public function check_subject_input($aryInput, $update = false, $subjectId = false){
        global $database;

        if ( !$update ){
            if (trim($aryInput['ma_mon_hoc']) == '') {
                $this->is_error[] = 'Mã môn học vui lòng không để trống!';
            }
            if ($database->db_num_rows($database->db_query("SELECT id FROM ".TBL_MON_HOC." WHERE LOWER(ma_truong)='".strtolower($aryInput['ma_truong'])."' AND LOWER(ma_mon_hoc)='".strtolower(trim($aryInput['ma_mon_hoc']))."'")) ) {
                $this->is_error[] = 'Mã môn học '.$aryInput['ten_mon_hoc'].' đã tồn tại trong trường học. Vui lòng kiểm tra lại!';
            }
        }
        if (trim($aryInput['ten_mon_hoc']) == '') {
            $this->is_error[] = 'Tên môn học vui lòng không để trống!';
        }
        if ( !$update ){
            if ($database->db_num_rows($database->db_query("SELECT id FROM ".TBL_MON_HOC." WHERE LOWER(ma_truong)='".strtolower($aryInput['ma_truong'])."' AND LOWER(ten_mon_hoc)='".strtolower(trim($aryInput['ten_mon_hoc']))."'")) ) {
                $this->is_error[] = 'Tên môn học '.$aryInput['ten_mon_hoc'].' đã tồn tại trong trường học. Vui lòng kiểm tra lại!';
            }
        }else{
            if ( $subjectId ){
                if ($database->db_num_rows($database->db_query("SELECT id FROM ".TBL_MON_HOC." WHERE id <> ".$subjectId." AND LOWER(ma_truong)='".strtolower($aryInput['ma_truong'])."' AND LOWER(ten_mon_hoc)='".strtolower(trim($aryInput['ten_mon_hoc']))."'")) ) {
                    $this->is_error[] = 'Tên môn học '.$aryInput['ten_mon_hoc'].' đã tồn tại trong trường học. Vui lòng kiểm tra lại!';
                }
            }
        }
        if (!$aryInput['kieu_mon']) {
            $this->is_error[] = 'Vui lòng chọn kiểu môn học!';
        }
        return true;
    }
    public function saveSubject( &$aryData ){
        $jsonData = json_encode($aryData);
        $source_data = ORGANIZATION_SOURCE_DATA;
        return $this->serviceMonhoc($jsonData, $source_data);
    }

    /**
     * LỚP MÔN (PHÂN CÔNG CHUYÊN MÔN)
     */
    public function saveClassSubject( &$aryData ){
        $jsonData = json_encode($aryData);
        $source_data = ORGANIZATION_SOURCE_DATA;
        return $this->serviceLopmon($jsonData, $source_data);
    }

    /**
     * GIÁO VIÊN
     */
    public function saveTeacher( &$aryData, $cap_hoc ){
        $jsonData = json_encode($aryData);
        $source_data = ORGANIZATION_SOURCE_DATA;
        return $this->serviceGiaovien($jsonData, $source_data, $cap_hoc);
    }

    /**
     * PHỤ HUYNH, HỌC SINH
     */
    public function mergeClass( &$ten_lop, $ma_truong ){
        global $database;

        if ( !$ten_lop || !$ma_truong ) return false;

        $query = "SELECT key_index FROM ".TBL_LOP_HOC." WHERE ma_truong = '{$ma_truong}' AND LOWER(ten_lop)='".strtolower($ten_lop)."' LIMIT 1";
        $result = $database->db_query($query);
        if ( $row = $database->db_fetch_assoc($result) ){
            return $row['key_index'];
        }
        return false;
    }
    public function saveParentStudent( &$aryData ){
        $jsonData = json_encode($aryData);
        $source_data = ORGANIZATION_SOURCE_DATA;
        return $this->servicePhuHuynhHocsinh($jsonData, $source_data);
    }

}